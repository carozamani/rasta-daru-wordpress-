<?php
require __DIR__ . '/_boot.php';

/**
 * HTTP/CLI runner for store content finish (local admin only).
 * Open while logged in as admin on *.local, or run via WP-CLI/php CLI.
 */

$wp_load = dirname( __DIR__, 4 ) . '/wp-load.php';
if ( ! file_exists( $wp_load ) ) {
	status_header( 500 );
	echo 'wp-load missing';
	exit;
}

require_once $wp_load;
require_once __DIR__ . '/_security.php';
storeplus_child_tools_assert_allowed();

if ( ! function_exists( 'sp_store_finish_run' ) ) {
	$mod = dirname( __DIR__ ) . '/inc/store-content-finish.php';
	if ( file_exists( $mod ) ) {
		require_once $mod;
	}
}

header( 'Content-Type: application/json; charset=utf-8' );

if ( ! function_exists( 'sp_store_finish_run' ) ) {
	echo wp_json_encode( array( 'ok' => false, 'error' => 'finisher not loaded' ) );
	exit;
}

$force = ! empty( $_GET['force'] ) || ( PHP_SAPI === 'cli' && in_array( '--force', $argv ?? array(), true ) );
$log   = sp_store_finish_run( $force );
echo wp_json_encode( array( 'ok' => true, 'log' => $log ), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
