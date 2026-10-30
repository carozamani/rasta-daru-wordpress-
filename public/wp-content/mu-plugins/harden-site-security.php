<?php
/**
 * Plugin Name: Harden site security
 * Description: Blocks dangerous theme AJAX for guests, locks XML-RPC/user enum, security headers, REST user listing, login throttle.
 * Version: 1.3.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * JSON 403 for blocked AJAX.
 */
function storeplus_sec_forbid_ajax() {
	wp_send_json_error( array( 'message' => 'forbidden' ), 403 );
}

/**
 * Theme endpoints that must never be public (and newsletter blast admin-only).
 */
function storeplus_sec_lock_theme_ajax() {
	$block_nopriv = array(
		'irantheme_reset_otp_restrictions',
		'irantheme_ajax_send_test_sms',
		'newsletter_send_mail', /* Parent theme: mass mail with no capability check */
		'sp_reverse_geocode',   /* Unauthenticated HTTP proxy if map module enabled */
	);

	foreach ( $block_nopriv as $action ) {
		remove_all_actions( 'wp_ajax_nopriv_' . $action );
		add_action( 'wp_ajax_nopriv_' . $action, 'storeplus_sec_forbid_ajax', 0 );
	}

	/* Admin newsletter only — run before theme callback (priority 10). */
	add_action(
		'wp_ajax_newsletter_send_mail',
		static function () {
			if ( ! current_user_can( 'manage_options' ) ) {
				storeplus_sec_forbid_ajax();
			}
		},
		0
	);
}

add_action( 'init', 'storeplus_sec_lock_theme_ajax', 999 );
add_action( 'wp_loaded', 'storeplus_sec_lock_theme_ajax', 999 );

/**
 * Block public access to theme junk / tooling that must never ship as assets.
 */
function storeplus_sec_block_theme_junk() {
	if ( is_admin() ) {
		return;
	}
	$uri = isset( $_SERVER['REQUEST_URI'] ) ? (string) wp_unslash( $_SERVER['REQUEST_URI'] ) : '';
	if ( '' === $uri ) {
		return;
	}
	$path = strtolower( (string) wp_parse_url( $uri, PHP_URL_PATH ) );
	if ( '' === $path ) {
		return;
	}

	/* Audit dumps accidentally left in web root. */
	if ( preg_match( '#^/_(home|shop|product|cart)[^/]*\.html$#', $path ) ) {
		status_header( 404 );
		nocache_headers();
		exit;
	}

	if ( ! preg_match( '#/wp-content/themes/[^/]+/#', $path ) ) {
		return;
	}

	$blocked = false;
	if ( preg_match( '#/(probe\.html|_user-bug)#', $path ) ) {
		$blocked = true;
	} elseif ( false !== strpos( $path, '/tools/' ) ) {
		$blocked = true;
	} elseif ( preg_match( '#/_(archive|restore-bak)(/|$)#', $path ) ) {
		$blocked = true;
	} elseif ( preg_match( '#\.(bak|old|tmp)([./?]|$)#', $path ) || false !== strpos( $path, '.php.bak' ) ) {
		$blocked = true;
	} elseif ( preg_match( '#/_test|_check#', $path ) ) {
		$blocked = true;
	}

	if ( $blocked ) {
		status_header( 404 );
		nocache_headers();
		exit;
	}
}
add_action( 'init', 'storeplus_sec_block_theme_junk', 0 );

add_filter( 'xmlrpc_enabled', '__return_false' );

add_action(
	'init',
	static function () {
		remove_action( 'wp_head', 'rsd_link' );
		remove_action( 'wp_head', 'wlwmanifest_link' );
		remove_action( 'wp_head', 'wp_shortlink_wp_head' );
		remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
		remove_action( 'template_redirect', 'rest_output_link_header', 11 );
		remove_action( 'template_redirect', 'wp_shortlink_header', 11 );
	},
	20
);

/**
 * Keep sitemaps useful: drop author + support-ticket tax noise from Yoast index.
 */
add_filter( 'wpseo_sitemap_exclude_author', '__return_true' );
add_filter(
	'wpseo_sitemap_exclude_taxonomy',
	static function ( $exclude, $taxonomy ) {
		$noise = array( 'ticket_status', 'product_shipping_class' );
		return in_array( (string) $taxonomy, $noise, true ) ? true : $exclude;
	},
	10,
	2
);

/**
 * Block leftover WP junk files if they reappear in the docroot (readme/license).
 */
function storeplus_sec_block_wp_junk_files() {
	if ( is_admin() ) {
		return;
	}
	$uri  = isset( $_SERVER['REQUEST_URI'] ) ? (string) wp_unslash( $_SERVER['REQUEST_URI'] ) : '';
	$path = strtolower( (string) wp_parse_url( $uri, PHP_URL_PATH ) );
	if ( '' === $path ) {
		return;
	}
	if ( preg_match( '#^/(readme\.html|license\.txt|wp-config-sample\.php)/?$#', $path ) ) {
		status_header( 404 );
		nocache_headers();
		exit;
	}
}
add_action( 'init', 'storeplus_sec_block_wp_junk_files', 0 );

/**
 * Block author enumeration.
 */
function storeplus_sec_block_author_enum() {
	if ( is_admin() ) {
		return;
	}
	if ( isset( $_GET['author'] ) && (string) $_GET['author'] !== '' ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		wp_safe_redirect( home_url( '/' ), 301 );
		exit;
	}
	if ( is_author() ) {
		wp_safe_redirect( home_url( '/' ), 301 );
		exit;
	}
}
add_action( 'template_redirect', 'storeplus_sec_block_author_enum', 1 );

/**
 * @return string
 */
function storeplus_sec_login_errors() {
	return 'ورود ناموفق بود. نام کاربری یا رمز عبور اشتباه است.';
}
add_filter( 'login_errors', 'storeplus_sec_login_errors' );

/**
 * @param WP_Error|bool|mixed $result Prior.
 * @return WP_Error|bool|mixed
 */
function storeplus_sec_rest_users( $result ) {
	if ( true === $result || is_wp_error( $result ) ) {
		return $result;
	}
	if ( ! empty( $_SERVER['REQUEST_URI'] ) && false !== strpos( (string) $_SERVER['REQUEST_URI'], '/wp/v2/users' ) ) {
		if ( ! current_user_can( 'list_users' ) ) {
			return new WP_Error( 'rest_user_cannot_view', 'ممنوع', array( 'status' => 401 ) );
		}
	}
	return $result;
}
add_filter( 'rest_authentication_errors', 'storeplus_sec_rest_users', 20 );

/**
 * Never advertise the PHP version. (expose_php=Off in php.ini is the real fix on
 * the server; this covers hosts where php.ini is not editable.)
 */
if ( ! headers_sent() ) {
	header_remove( 'X-Powered-By' );
}
@ini_set( 'expose_php', '0' ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged,WordPress.PHP.IniSet.Risky

/**
 * Build the Content-Security-Policy value.
 *
 * Audit result (home, shop, cart, checkout, my-account): no third-party scripts,
 * styles or fonts are loaded. The only external origins are the Leaflet delivery
 * map (unpkg + OpenStreetMap tiles + Nominatim), which exists on cart / checkout /
 * account pages only, so those hosts are added there and nowhere else.
 *
 * 'unsafe-inline' for script/style stays until inline <script> blocks (~96 on the
 * home page) are moved to files or given nonces â€” that is a theme refactor, not a
 * header change. Everything else is locked down.
 *
 * form-action is intentionally NOT set: browsers also apply it to the redirect
 * chain after a POST, which breaks off-site payment-gateway redirects.
 *
 * @return string
 */
function storeplus_sec_csp() {
	$self = "'self'";

	$d = array(
		'default-src'     => array( $self ),
		'script-src'      => array( $self, "'unsafe-inline'" ),
		'style-src'       => array( $self, "'unsafe-inline'" ),
		'img-src'         => array( $self, 'data:', 'blob:', 'https:' ),
		'font-src'        => array( $self, 'data:' ),
		'connect-src'     => array( $self ),
		'media-src'       => array( $self, 'data:', 'blob:' ),
		'frame-src'       => array( $self, 'https://www.youtube.com', 'https://www.youtube-nocookie.com', 'https://www.aparat.com' ),
		'worker-src'      => array( $self, 'blob:' ),
		'manifest-src'    => array( $self ),
		'frame-ancestors' => array( $self ),
		'base-uri'        => array( $self ),
		'object-src'      => array( "'none'" ),
	);

	// Delivery-map pages only (Leaflet from unpkg, OSM tiles, Nominatim lookups).
	$needs_map = did_action( 'wp' ) && function_exists( 'is_checkout' )
		&& ( is_checkout() || is_cart() || ( function_exists( 'is_account_page' ) && is_account_page() ) );
	if ( $needs_map ) {
		$d['script-src'][]  = 'https://unpkg.com';
		$d['style-src'][]   = 'https://unpkg.com';
		$d['connect-src'][] = 'https://nominatim.openstreetmap.org';
	}

	/**
	 * Adjust the policy, e.g. add a chat widget or analytics origin:
	 * add_filter( 'storeplus_sec_csp_directives', fn( $d ) => $d + [...] );
	 */
	$d = apply_filters( 'storeplus_sec_csp_directives', $d, $needs_map );

	$parts = array();
	foreach ( $d as $name => $sources ) {
		$parts[] = $name . ' ' . implode( ' ', array_unique( (array) $sources ) );
	}
	if ( is_ssl() ) {
		$parts[] = 'upgrade-insecure-requests';
	}

	return implode( '; ', $parts ) . ';';
}

/**
 * Security headers.
 *
 * Set STOREPLUS_CSP_REPORT_ONLY to true in wp-config.php to trial a policy change
 * (violations show in the browser console) without breaking the site.
 */
function storeplus_sec_headers() {
	if ( headers_sent() ) {
		return;
	}
	if ( is_admin() ) {
		return;
	}

	header_remove( 'X-Powered-By' );

	header( 'X-Content-Type-Options: nosniff' );
	header( 'X-Frame-Options: SAMEORIGIN' );
	header( 'Referrer-Policy: strict-origin-when-cross-origin' );
	header( 'Permissions-Policy: geolocation=(), microphone=(), camera=()' );
	header( 'Cross-Origin-Opener-Policy: same-origin-allow-popups' );

	$csp_header = ( defined( 'STOREPLUS_CSP_REPORT_ONLY' ) && STOREPLUS_CSP_REPORT_ONLY )
		? 'Content-Security-Policy-Report-Only'
		: 'Content-Security-Policy';
	header( $csp_header . ': ' . storeplus_sec_csp() );

	// HSTS only on a real HTTPS production host (never on Local / http).
	if ( is_ssl() && 'production' === wp_get_environment_type() ) {
		header( 'Strict-Transport-Security: max-age=31536000; includeSubDomains' );
	}
}
add_action( 'send_headers', 'storeplus_sec_headers' );
add_action( 'template_redirect', 'storeplus_sec_headers', 1 );
add_action( 'login_init', 'storeplus_sec_headers', 1 );

/**
 * @param bool    $available Available.
 * @param WP_User $user      User.
 * @return bool
 */
function storeplus_sec_app_passwords( $available, $user ) {
	if ( $user instanceof WP_User && user_can( $user, 'manage_options' ) ) {
		return $available;
	}
	return false;
}
add_filter( 'wp_is_application_passwords_available_for_user', 'storeplus_sec_app_passwords', 10, 2 );

/**
 * @param null|WP_User|WP_Error $user     User.
 * @param string                $username Username.
 * @param string                $password Password.
 * @return null|WP_User|WP_Error
 */
function storeplus_sec_login_throttle( $user, $username, $password ) {
	unset( $password );
	if ( '' === (string) $username ) {
		return $user;
	}
	$ip  = isset( $_SERVER['REMOTE_ADDR'] ) ? (string) $_SERVER['REMOTE_ADDR'] : '0';
	$key = 'sp_login_fail_' . md5( $ip );
	$n   = (int) get_transient( $key );
	if ( $n >= 8 ) {
		return new WP_Error( 'sp_login_locked', 'تعداد تلاش ورود بیش از حد مجاز است. چند دقیقه دیگر دوباره تلاش کنید.' );
	}
	return $user;
}
add_filter( 'authenticate', 'storeplus_sec_login_throttle', 30, 3 );

/**
 * @param string $username Username.
 */
function storeplus_sec_login_failed( $username ) {
	unset( $username );
	$ip  = isset( $_SERVER['REMOTE_ADDR'] ) ? (string) $_SERVER['REMOTE_ADDR'] : '0';
	$key = 'sp_login_fail_' . md5( $ip );
	$n   = (int) get_transient( $key );
	set_transient( $key, $n + 1, 15 * MINUTE_IN_SECONDS );
}
add_action( 'wp_login_failed', 'storeplus_sec_login_failed' );

add_action(
	'wp_login',
	static function () {
		$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? (string) $_SERVER['REMOTE_ADDR'] : '0';
		delete_transient( 'sp_login_fail_' . md5( $ip ) );
	}
);
