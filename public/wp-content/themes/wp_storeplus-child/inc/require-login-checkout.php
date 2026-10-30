<?php
/**
 * Require account before checkout / place order.
 * Guests can browse cart; checkout redirects to login/register, then back.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Persist Woo options once (admin can still see them; filters hard-lock guest off). */
add_action(
	'init',
	static function () {
		if ( 'no' !== get_option( 'woocommerce_enable_guest_checkout', 'yes' ) ) {
			update_option( 'woocommerce_enable_guest_checkout', 'no' );
		}
		if ( 'yes' !== get_option( 'woocommerce_enable_signup_and_login_from_checkout', 'no' ) ) {
			update_option( 'woocommerce_enable_signup_and_login_from_checkout', 'yes' );
		}
		if ( 'yes' !== get_option( 'woocommerce_enable_checkout_login_reminder', 'no' ) ) {
			update_option( 'woocommerce_enable_checkout_login_reminder', 'yes' );
		}
	},
	5
);

/** Hard lock: never allow guest checkout even if option is flipped in admin. */
add_filter( 'pre_option_woocommerce_enable_guest_checkout', static function () {
	return 'no';
} );

add_filter( 'pre_option_woocommerce_enable_signup_and_login_from_checkout', static function () {
	return 'yes';
} );

/** Cookie holding a guest cart snapshot across login/register. */
const STOREPLUS_CHILD_CART_COOKIE = 'sp_pending_cart';

/**
 * Where to send guests (login / register page) with return to checkout.
 *
 * @return string
 */
function storeplus_child_checkout_auth_url() {
	$checkout = function_exists( 'wc_get_checkout_url' ) ? wc_get_checkout_url() : home_url( '/checkout/' );
	$account  = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : wp_login_url( $checkout );
	if ( ! $account ) {
		$account = wp_login_url( $checkout );
	}
	return add_query_arg(
		array(
			'redirect_to'  => $checkout,
			'sp_need_auth' => '1',
		),
		$account
	);
}

/**
 * Preferred post-auth URL when the shopper was gated from checkout.
 * Empty string = leave theme/Woo default redirect alone.
 *
 * @return string
 */
function storeplus_child_after_auth_url() {
	$candidates = array();
	if ( ! empty( $_REQUEST['redirect_to'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$candidates[] = esc_url_raw( wp_unslash( $_REQUEST['redirect_to'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	}
	if ( ! empty( $_REQUEST['redirect'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$candidates[] = esc_url_raw( wp_unslash( $_REQUEST['redirect'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	}
	if ( ! empty( $_COOKIE['sp_after_auth'] ) ) {
		$candidates[] = esc_url_raw( wp_unslash( $_COOKIE['sp_after_auth'] ) );
	}

	foreach ( $candidates as $url ) {
		if ( $url && wp_validate_redirect( $url, false ) ) {
			return $url;
		}
	}

	/* Only force checkout when we explicitly marked this as a checkout-auth hop. */
	if ( ! empty( $_GET['sp_need_auth'] ) || ! empty( $_COOKIE['sp_after_auth'] ) || ! empty( $_COOKIE[ STOREPLUS_CHILD_CART_COOKIE ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return function_exists( 'wc_get_checkout_url' ) ? wc_get_checkout_url() : '';
	}

	return '';
}

/**
 * Snapshot current cart lines so they survive login/register session handoff.
 *
 * @return void
 */
function storeplus_child_snapshot_guest_cart() {
	if ( ! function_exists( 'WC' ) || ! WC()->cart || WC()->cart->is_empty() ) {
		return;
	}

	$items = array();
	foreach ( WC()->cart->get_cart() as $line ) {
		$items[] = array(
			'product_id'   => (int) $line['product_id'],
			'variation_id' => isset( $line['variation_id'] ) ? (int) $line['variation_id'] : 0,
			'quantity'     => (float) $line['quantity'],
			'variation'    => isset( $line['variation'] ) && is_array( $line['variation'] ) ? $line['variation'] : array(),
		);
	}

	if ( ! $items ) {
		return;
	}

	$payload = wp_json_encode( $items );
	$sig     = hash_hmac( 'sha256', $payload, wp_salt( 'auth' ) );
	$value   = rawurlencode( $payload ) . '|' . $sig;

	if ( function_exists( 'wc_setcookie' ) ) {
		wc_setcookie( STOREPLUS_CHILD_CART_COOKIE, $value, time() + DAY_IN_SECONDS, false, true );
	} else {
		setcookie( STOREPLUS_CHILD_CART_COOKIE, $value, time() + DAY_IN_SECONDS, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN, is_ssl(), true );
	}
	$_COOKIE[ STOREPLUS_CHILD_CART_COOKIE ] = $value;

	$checkout = function_exists( 'wc_get_checkout_url' ) ? wc_get_checkout_url() : home_url( '/checkout/' );
	if ( function_exists( 'wc_setcookie' ) ) {
		wc_setcookie( 'sp_after_auth', $checkout, time() + DAY_IN_SECONDS, false, true );
	} else {
		setcookie( 'sp_after_auth', $checkout, time() + DAY_IN_SECONDS, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN, is_ssl(), true );
	}
	$_COOKIE['sp_after_auth'] = $checkout;

	/* Keep Woo session cookie alive while guest is on the account page. */
	if ( WC()->session && method_exists( WC()->session, 'set_customer_session_cookie' ) ) {
		WC()->session->set( 'sp_cart_backup', $items );
		WC()->session->set_customer_session_cookie( true );
	}
}

/**
 * Read cart snapshot cookie.
 *
 * @return array
 */
function storeplus_child_read_cart_snapshot() {
	if ( empty( $_COOKIE[ STOREPLUS_CHILD_CART_COOKIE ] ) ) {
		return array();
	}
	$raw = wp_unslash( $_COOKIE[ STOREPLUS_CHILD_CART_COOKIE ] );
	$parts = explode( '|', $raw, 2 );
	if ( count( $parts ) !== 2 ) {
		return array();
	}
	$payload = rawurldecode( $parts[0] );
	$sig     = $parts[1];
	if ( ! hash_equals( hash_hmac( 'sha256', $payload, wp_salt( 'auth' ) ), $sig ) ) {
		return array();
	}
	$items = json_decode( $payload, true );
	return is_array( $items ) ? $items : array();
}

/**
 * Clear cart snapshot cookie.
 *
 * @return void
 */
function storeplus_child_clear_cart_snapshot() {
	if ( function_exists( 'wc_setcookie' ) ) {
		wc_setcookie( STOREPLUS_CHILD_CART_COOKIE, '', time() - YEAR_IN_SECONDS, false, true );
		wc_setcookie( 'sp_after_auth', '', time() - YEAR_IN_SECONDS, false, true );
	}
	unset( $_COOKIE[ STOREPLUS_CHILD_CART_COOKIE ], $_COOKIE['sp_after_auth'] );
}

/**
 * Restore snapped cart into the logged-in session (merge; never wipe existing lines).
 *
 * @return void
 */
function storeplus_child_restore_guest_cart() {
	if ( ! is_user_logged_in() || ! function_exists( 'WC' ) || ! WC()->cart ) {
		return;
	}

	$items = storeplus_child_read_cart_snapshot();
	if ( ! $items && WC()->session ) {
		$backup = WC()->session->get( 'sp_cart_backup' );
		if ( is_array( $backup ) ) {
			$items = $backup;
		}
	}
	if ( ! $items ) {
		return;
	}

	foreach ( $items as $line ) {
		$product_id   = isset( $line['product_id'] ) ? absint( $line['product_id'] ) : 0;
		$variation_id = isset( $line['variation_id'] ) ? absint( $line['variation_id'] ) : 0;
		$qty          = isset( $line['quantity'] ) ? (float) $line['quantity'] : 0;
		$variation    = isset( $line['variation'] ) && is_array( $line['variation'] ) ? $line['variation'] : array();
		if ( ! $product_id || $qty <= 0 ) {
			continue;
		}

		$already = false;
		foreach ( WC()->cart->get_cart() as $existing ) {
			$same_product   = (int) $existing['product_id'] === $product_id;
			$same_variation = (int) ( $existing['variation_id'] ?? 0 ) === $variation_id;
			if ( $same_product && $same_variation ) {
				$already = true;
				break;
			}
		}
		if ( $already ) {
			continue;
		}

		WC()->cart->add_to_cart( $product_id, $qty, $variation_id, $variation );
	}

	if ( WC()->session ) {
		WC()->session->set( 'sp_cart_backup', null );
	}
	storeplus_child_clear_cart_snapshot();
}

/**
 * Guests hitting checkout (except thank-you / order-pay) → account page.
 */
add_action(
	'template_redirect',
	static function () {
		if ( is_admin() || wp_doing_ajax() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
			return;
		}
		if ( ! function_exists( 'is_checkout' ) || ! is_checkout() ) {
			return;
		}
		if ( is_user_logged_in() ) {
			storeplus_child_restore_guest_cart();
			return;
		}
		/* Thank-you and pay-for-order links must stay reachable. */
		if ( function_exists( 'is_wc_endpoint_url' ) && ( is_wc_endpoint_url( 'order-received' ) || is_wc_endpoint_url( 'order-pay' ) ) ) {
			return;
		}
		storeplus_child_snapshot_guest_cart();
		wp_safe_redirect( storeplus_child_checkout_auth_url() );
		exit;
	},
	5
);

/** Restore snapshot after Woo has finished loading the session cart. */
add_action( 'woocommerce_cart_loaded_from_session', 'storeplus_child_restore_guest_cart', 20 );

/**
 * Classic checkout: block place-order if somehow still guest.
 */
add_action(
	'woocommerce_after_checkout_validation',
	static function ( $data, $errors ) {
		if ( is_user_logged_in() ) {
			return;
		}
		if ( is_wp_error( $errors ) ) {
			$errors->add( 'sp_login_required', 'برای ثبت سفارش ابتدا وارد حساب کاربری شوید یا ثبت‌نام کنید.' );
		}
	},
	10,
	2
);

/**
 * Store API / Blocks: reject guest checkout with a clear Persian message.
 */
add_action(
	'woocommerce_store_api_checkout_update_order_from_request',
	static function () {
		if ( is_user_logged_in() ) {
			return;
		}
		$msg = 'برای ثبت سفارش ابتدا وارد حساب کاربری شوید یا ثبت‌نام کنید.';
		if ( class_exists( '\Automattic\WooCommerce\StoreApi\Exceptions\RouteException' ) ) {
			throw new \Automattic\WooCommerce\StoreApi\Exceptions\RouteException(
				'woocommerce_rest_checkout_login_required',
				$msg,
				401
			);
		}
		throw new Exception( $msg );
	},
	1
);

/**
 * After login/register, send user back to checkout and keep cart.
 */
add_filter(
	'woocommerce_login_redirect',
	static function ( $redirect ) {
		$url = storeplus_child_after_auth_url();
		return $url ? $url : $redirect;
	},
	20
);

add_filter(
	'woocommerce_registration_redirect',
	static function ( $redirect ) {
		$url = storeplus_child_after_auth_url();
		return $url ? $url : $redirect;
	},
	20
);

/**
 * Theme OTP login defaults to wp-admin — send checkout shoppers back to checkout.
 */
add_filter(
	'irantheme_otp_login_redirect',
	static function ( $url ) {
		$target = storeplus_child_after_auth_url();
		if ( $target ) {
			return $target;
		}
		/* Never dump customers into wp-admin after storefront OTP login. */
		if ( $url && false !== strpos( $url, 'wp-admin' ) ) {
			return function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : home_url( '/' );
		}
		return $url;
	},
	20
);

/**
 * Theme login form omits Woo's hidden redirect field — inject when gated from checkout.
 */
add_action(
	'woocommerce_login_form',
	static function () {
		$redirect = storeplus_child_after_auth_url();
		if ( ! $redirect ) {
			return;
		}
		echo '<input type="hidden" name="redirect" value="' . esc_url( $redirect ) . '" />';
	}
);

add_action(
	'woocommerce_register_form',
	static function () {
		$redirect = storeplus_child_after_auth_url();
		if ( ! $redirect ) {
			return;
		}
		echo '<input type="hidden" name="redirect" value="' . esc_url( $redirect ) . '" />';
	}
);

/**
 * Notice on account page when redirected from checkout.
 */
add_action(
	'woocommerce_before_customer_login_form',
	static function () {
		if ( empty( $_GET['sp_need_auth'] ) && empty( $_COOKIE['sp_after_auth'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return;
		}
		wc_print_notice( 'برای ثبت سفارش ابتدا وارد شوید یا یک حساب کاربری بسازید. سبد خرید شما حفظ می‌شود.', 'notice' );
	}
);

/**
 * After successful traditional registration, Woo logs the user in — restore cart on next load.
 * Also keep Woo session cookie so guest cart can migrate.
 */
add_action(
	'woocommerce_created_customer',
	static function () {
		if ( function_exists( 'WC' ) && WC()->session && method_exists( WC()->session, 'set_customer_session_cookie' ) ) {
			WC()->session->set_customer_session_cookie( true );
		}
	},
	20
);

add_action(
	'wp_login',
	static function () {
		if ( function_exists( 'WC' ) && WC()->session && method_exists( WC()->session, 'set_customer_session_cookie' ) ) {
			WC()->session->set_customer_session_cookie( true );
		}
	},
	5
);
