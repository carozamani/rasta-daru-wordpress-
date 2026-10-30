<?php
require __DIR__ . '/_boot.php';

/**
 * Activate Yoast (if needed) and re-run SEO/finish pieces (local admin only).
 */

require_once dirname( __DIR__, 4 ) . '/wp-load.php';
require_once __DIR__ . '/_security.php';
storeplus_child_tools_assert_allowed();

header( 'Content-Type: application/json; charset=utf-8' );

$log = array();

if ( ! function_exists( 'activate_plugin' ) ) {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
}

$yoast = 'wordpress-seo/wp-seo.php';
if ( ! is_plugin_active( $yoast ) ) {
	if ( ! file_exists( WP_PLUGIN_DIR . '/' . $yoast ) ) {
		echo wp_json_encode( array( 'ok' => false, 'error' => 'Yoast plugin files missing' ), JSON_UNESCAPED_UNICODE );
		exit;
	}
	$result = activate_plugin( $yoast, '', false, true );
	if ( is_wp_error( $result ) ) {
		echo wp_json_encode( array( 'ok' => false, 'error' => $result->get_error_message() ), JSON_UNESCAPED_UNICODE );
		exit;
	}
	$log[] = 'Yoast SEO فعال شد.';
	if ( ! defined( 'WPSEO_VERSION' ) ) {
		include_once WP_PLUGIN_DIR . '/' . $yoast;
	}
} else {
	$log[] = 'Yoast از قبل فعال بود.';
}

if ( ! function_exists( 'sp_store_finish_yoast' ) ) {
	$mod = dirname( __DIR__ ) . '/inc/store-content-finish.php';
	if ( file_exists( $mod ) ) {
		require_once $mod;
	}
}

if ( function_exists( 'sp_store_finish_pages' ) ) {
	$log = array_merge( $log, sp_store_finish_pages(), sp_store_finish_footer() );
}
if ( function_exists( 'sp_store_finish_products' ) ) {
	$log = array_merge( $log, sp_store_finish_products() );
}
if ( function_exists( 'sp_store_finish_categories' ) ) {
	$log = array_merge( $log, sp_store_finish_categories() );
}
if ( function_exists( 'sp_store_finish_yoast' ) ) {
	$log = array_merge( $log, sp_store_finish_yoast() );
}

$active = get_option( 'active_plugins', array() );
$log[]  = 'WPSEO_VERSION=' . ( defined( 'WPSEO_VERSION' ) ? WPSEO_VERSION : 'undefined' );
$log[]  = 'active_plugins_has_yoast=' . ( in_array( $yoast, (array) $active, true ) ? 'yes' : 'no' );

echo wp_json_encode( array( 'ok' => true, 'log' => $log ), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
