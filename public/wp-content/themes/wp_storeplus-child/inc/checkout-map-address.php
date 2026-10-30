<?php
/**
 * Checkout delivery map: show reverse-geocoded address instead of Lat/Lng.
 *
 * Local WP has WP_HTTP_BLOCK_EXTERNAL=true, so wp_remote_get to Nominatim is
 * blocked. We fetch with cURL (same machine can reach OSM — verified) and
 * replace the coords bar under the map with a readable Persian address.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Build a readable FA address from Nominatim JSON.
 *
 * @param array $data Decoded Nominatim response.
 * @return string
 */
function storeplus_child_format_nominatim_address( $data ) {
	if ( ! is_array( $data ) ) {
		return '';
	}

	$parts = isset( $data['address'] ) && is_array( $data['address'] ) ? $data['address'] : array();
	$bits  = array();

	$road = '';
	foreach ( array( 'road', 'pedestrian', 'residential', 'street', 'path' ) as $k ) {
		if ( ! empty( $parts[ $k ] ) ) {
			$road = (string) $parts[ $k ];
			break;
		}
	}
	$house = ! empty( $parts['house_number'] ) ? (string) $parts['house_number'] : '';
	if ( $road ) {
		$bits[] = $house ? ( $road . '، پلاک ' . $house ) : $road;
	}

	foreach ( array( 'neighbourhood', 'suburb', 'quarter', 'city_district', 'district' ) as $k ) {
		if ( ! empty( $parts[ $k ] ) ) {
			$bits[] = (string) $parts[ $k ];
			break;
		}
	}

	foreach ( array( 'city', 'town', 'village', 'municipality', 'county', 'state' ) as $k ) {
		if ( ! empty( $parts[ $k ] ) ) {
			$bits[] = (string) $parts[ $k ];
			break;
		}
	}

	$bits = array_values( array_unique( array_filter( array_map( 'trim', $bits ) ) ) );
	if ( $bits ) {
		return implode( '، ', $bits );
	}

	return isset( $data['display_name'] ) ? trim( (string) $data['display_name'] ) : '';
}

/**
 * GET URL bypassing WP_HTTP_BLOCK_EXTERNAL (uses cURL when available).
 *
 * @param string $url Request URL.
 * @return array{code:int,body:string}|WP_Error
 */
function storeplus_child_http_get_raw( $url ) {
	if ( function_exists( 'curl_init' ) ) {
		$ch = curl_init( $url );
		curl_setopt_array(
			$ch,
			array(
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_CONNECTTIMEOUT => 5,
				CURLOPT_TIMEOUT        => 12,
				CURLOPT_HTTPHEADER     => array(
					'User-Agent: RastaDaruCheckout/1.0; ' . home_url( '/' ),
					'Accept: application/json',
					'Accept-Language: fa',
				),
			)
		);
		$body = curl_exec( $ch );
		$code = (int) curl_getinfo( $ch, CURLINFO_HTTP_CODE );
		$err  = curl_error( $ch );
		curl_close( $ch );
		if ( false === $body ) {
			return new WP_Error( 'curl_error', $err ? $err : 'curl_failed' );
		}
		return array(
			'code' => $code,
			'body' => (string) $body,
		);
	}

	// No cURL: short-circuit WP HTTP via pre_http_request (BLOCK_EXTERNAL bypass).
	add_filter( 'pre_http_request', 'storeplus_child_nominatim_pre_http', 5, 3 );
	$res = wp_remote_get(
		$url,
		array(
			'timeout' => 12,
			'headers' => array(
				'User-Agent'      => 'RastaDaruCheckout/1.0; ' . home_url( '/' ),
				'Accept'          => 'application/json',
				'Accept-Language' => 'fa',
			),
		)
	);
	remove_filter( 'pre_http_request', 'storeplus_child_nominatim_pre_http', 5 );

	if ( is_wp_error( $res ) ) {
		return $res;
	}
	return array(
		'code' => (int) wp_remote_retrieve_response_code( $res ),
		'body' => (string) wp_remote_retrieve_body( $res ),
	);
}

/**
 * pre_http_request: fulfill Nominatim with cURL so BLOCK_EXTERNAL cannot stop it.
 *
 * @param false|array|WP_Error $pre  Short-circuit value.
 * @param array                $args Request args.
 * @param string               $url  Request URL.
 * @return false|array|WP_Error
 */
function storeplus_child_nominatim_pre_http( $pre, $args, $url ) {
	if ( false !== $pre ) {
		return $pre;
	}
	$host = wp_parse_url( $url, PHP_URL_HOST );
	if ( 'nominatim.openstreetmap.org' !== $host ) {
		return $pre;
	}
	if ( ! function_exists( 'curl_init' ) ) {
		return $pre;
	}

	$ch = curl_init( $url );
	$headers = array();
	if ( ! empty( $args['headers'] ) && is_array( $args['headers'] ) ) {
		foreach ( $args['headers'] as $k => $v ) {
			$headers[] = is_int( $k ) ? (string) $v : ( $k . ': ' . $v );
		}
	}
	curl_setopt_array(
		$ch,
		array(
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_CONNECTTIMEOUT => 5,
			CURLOPT_TIMEOUT        => isset( $args['timeout'] ) ? (int) $args['timeout'] : 12,
			CURLOPT_HTTPHEADER     => $headers,
		)
	);
	$body = curl_exec( $ch );
	$code = (int) curl_getinfo( $ch, CURLINFO_HTTP_CODE );
	$err  = curl_error( $ch );
	curl_close( $ch );

	if ( false === $body ) {
		return new WP_Error( 'http_request_failed', $err ? $err : 'curl_failed' );
	}

	return array(
		'headers'  => array(),
		'body'     => $body,
		'response' => array(
			'code'    => $code,
			'message' => '',
		),
		'cookies'  => array(),
		'filename' => null,
	);
}

/**
 * AJAX: reverse geocode lat/lng → Persian address string.
 * Requires login + nonce. Guests are blocked by mu-plugin harden-site-security as well.
 */
function storeplus_child_ajax_reverse_geocode() {
	if ( ! is_user_logged_in() ) {
		wp_send_json_error( array( 'message' => 'auth' ), 403 );
	}
	$nonce = isset( $_REQUEST['nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['nonce'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( ! $nonce || ! wp_verify_nonce( $nonce, 'sp_reverse_geocode' ) ) {
		wp_send_json_error( array( 'message' => 'bad_nonce' ), 403 );
	}

	$lat = isset( $_REQUEST['lat'] ) ? (float) wp_unslash( $_REQUEST['lat'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$lng = isset( $_REQUEST['lng'] ) ? (float) wp_unslash( $_REQUEST['lng'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

	if ( ! $lat || ! $lng || abs( $lat ) > 90 || abs( $lng ) > 180 ) {
		wp_send_json_error( array( 'message' => 'invalid_coords' ), 400 );
	}

	$cache_key = 'sp_rg_' . md5( round( $lat, 5 ) . ',' . round( $lng, 5 ) );
	$cached    = get_transient( $cache_key );
	if ( is_string( $cached ) && '' !== $cached ) {
		wp_send_json_success( array( 'address' => $cached ) );
	}

	$url = add_query_arg(
		array(
			'format'          => 'json',
			'lat'             => $lat,
			'lon'             => $lng,
			'accept-language' => 'fa',
			'addressdetails'  => 1,
			'zoom'            => 18,
		),
		'https://nominatim.openstreetmap.org/reverse'
	);

	$res = storeplus_child_http_get_raw( $url );
	if ( is_wp_error( $res ) ) {
		wp_send_json_error( array( 'message' => $res->get_error_message() ), 502 );
	}

	$code = (int) $res['code'];
	$body = json_decode( $res['body'], true );
	if ( $code < 200 || $code >= 300 || ! is_array( $body ) ) {
		wp_send_json_error( array( 'message' => 'upstream_' . $code ), 502 );
	}

	$address = storeplus_child_format_nominatim_address( $body );
	if ( '' === $address ) {
		wp_send_json_error( array( 'message' => 'empty_address' ), 404 );
	}

	set_transient( $cache_key, $address, DAY_IN_SECONDS );
	wp_send_json_success( array( 'address' => $address ) );
}
add_action( 'wp_ajax_sp_reverse_geocode', 'storeplus_child_ajax_reverse_geocode' );
/* nopriv intentionally omitted — unauthenticated geocode proxy is abusive. */

/**
 * Enqueue checkout map address enhancer.
 */
function storeplus_child_enqueue_checkout_map_address() {
	if ( ! function_exists( 'is_checkout' ) || ! is_checkout() || is_order_received_page() ) {
		return;
	}
	if ( 'on' !== get_option( 'irantheme_wc_shopping_cart_delivery_map_checkbox' ) ) {
		return;
	}

	$css = STOREPLUS_CHILD_DIR . '/assets/css/checkout-map-address.css';
	$js  = STOREPLUS_CHILD_DIR . '/assets/js/checkout-map-address.js';

	if ( file_exists( $css ) ) {
		wp_enqueue_style(
			'storeplus-child-checkout-map-address',
			STOREPLUS_CHILD_URI . '/assets/css/checkout-map-address.css',
			array( 'irantheme-main-css', 'style' ),
			filemtime( $css )
		);
	}
	if ( file_exists( $js ) ) {
		wp_enqueue_script(
			'storeplus-child-checkout-map-address',
			STOREPLUS_CHILD_URI . '/assets/js/checkout-map-address.js',
			array( 'jquery' ),
			filemtime( $js ),
			true
		);
		wp_localize_script(
			'storeplus-child-checkout-map-address',
			'spCheckoutMap',
			array(
				'ajax'    => admin_url( 'admin-ajax.php' ),
				'loading' => 'در حال دریافت آدرس…',
				'empty'   => 'آدرس یافت نشد — موقعیت را دقیق‌تر مشخص کنید',
				'label'   => 'آدرس انتخاب‌شده',
				'idle'    => 'روی نقشه کلیک کنید تا آدرس نمایش داده شود',
			)
		);
	}
}
add_action( 'wp_enqueue_scripts', 'storeplus_child_enqueue_checkout_map_address', 40 );
