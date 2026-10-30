<?php
require __DIR__ . '/_boot.php';

/**
 * HTTP/CLI runner for WooCommerce store setup (local admin only).
 */

require_once dirname( __DIR__, 4 ) . '/wp-load.php';
require_once __DIR__ . '/_security.php';
storeplus_child_tools_assert_allowed();

if ( ! function_exists( 'sp_wc_setup_run' ) ) {
	$mod = dirname( __DIR__ ) . '/inc/woocommerce-store-setup.php';
	if ( file_exists( $mod ) ) {
		require_once $mod;
	}
}

header( 'Content-Type: application/json; charset=utf-8' );

if ( ! function_exists( 'sp_wc_setup_run' ) ) {
	echo wp_json_encode( array( 'ok' => false, 'error' => 'setup not loaded' ) );
	exit;
}

$force = ! empty( $_GET['force'] ) || ( PHP_SAPI === 'cli' && in_array( '--force', $argv ?? array(), true ) );
$log   = sp_wc_setup_run( $force );
echo wp_json_encode( array( 'ok' => true, 'log' => $log ), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
