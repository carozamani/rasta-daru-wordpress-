<?php
/**
 * Checkout order result: success vs failed must look different.
 *
 * WooCommerce thank-you URL is shared; content must branch on order status.
 * Gateland on payment failure used to keep status "pending" and send the user
 * to the pay URL — we mark the order failed and send them to thank-you so the
 * failed UI (retry pay) is shown instead of a fake success screen.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Whether this order should show the failed / unpaid-failure result UI.
 *
 * @param WC_Order|false|null $order Order.
 * @return bool
 */
function storeplus_child_order_result_is_failure( $order ) {
	if ( ! $order instanceof WC_Order ) {
		return false;
	}
	return $order->has_status( array( 'failed', 'cancelled' ) );
}

/**
 * Body class so checkout steps can restyle on failure.
 *
 * @param string[] $classes Body classes.
 * @return string[]
 */
function storeplus_child_order_result_body_class( $classes ) {
	if ( ! function_exists( 'is_wc_endpoint_url' ) || ! is_wc_endpoint_url( 'order-received' ) ) {
		return $classes;
	}
	$order_id = absint( get_query_var( 'order-received' ) );
	if ( ! $order_id ) {
		return $classes;
	}
	$order = wc_get_order( $order_id );
	if ( storeplus_child_order_result_is_failure( $order ) ) {
		$classes[] = 'woocommerce-order-failed';
		$classes[] = 'sp-order-result-failed';
	} else {
		$classes[] = 'sp-order-result-success';
	}
	return $classes;
}
add_filter( 'body_class', 'storeplus_child_order_result_body_class' );

/**
 * Gateland: after a failed verify, mark order failed (not pending).
 *
 * @param WC_Order $order    Order.
 * @param array    $response Gateway verify payload.
 */
function storeplus_child_gateland_mark_failed( $order, $response = array() ) {
	unset( $response );
	if ( ! $order instanceof WC_Order ) {
		return;
	}
	if ( $order->is_paid() || $order->has_status( array( 'processing', 'completed', 'on-hold' ) ) ) {
		return;
	}
	$order->update_status( 'failed', 'پرداخت توسط درگاه تأیید نشد.' );
	$GLOBALS['storeplus_gateland_fail_redirect'] = $order->get_checkout_order_received_url();
}
add_action( 'gateland_webhook_failed', 'storeplus_child_gateland_mark_failed', 10, 2 );

/**
 * Rewrite Gateland's post-fail redirect (pay URL) → thank-you with failed UI.
 *
 * @param string $location Redirect target.
 * @return string
 */
function storeplus_child_gateland_fail_redirect( $location ) {
	if ( empty( $GLOBALS['storeplus_gateland_fail_redirect'] ) ) {
		return $location;
	}
	$url = (string) $GLOBALS['storeplus_gateland_fail_redirect'];
	unset( $GLOBALS['storeplus_gateland_fail_redirect'] );
	return $url ? $url : $location;
}
add_filter( 'wp_redirect', 'storeplus_child_gateland_fail_redirect', 10 );
