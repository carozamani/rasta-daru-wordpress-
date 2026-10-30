<?php
require __DIR__ . '/_boot.php';

/**
 * CLI/HTTP runner: scrub ngrok / LINKE / stale .local hosts from the database.
 *
 * php tools/run-critical-scrub.php
 */

$wp_load = dirname( __DIR__, 4 ) . '/wp-load.php';
if ( ! file_exists( $wp_load ) ) {
	fwrite( STDERR, "wp-load missing\n" );
	exit( 1 );
}

require_once $wp_load;

if ( PHP_SAPI !== 'cli' ) {
	require_once __DIR__ . '/_security.php';
	storeplus_child_tools_assert_allowed();
	header( 'Content-Type: application/json; charset=utf-8' );
}

if ( ! function_exists( 'storeplus_child_critical_scrub_database' ) ) {
	$mod = dirname( __DIR__ ) . '/inc/critical-launch-fix.php';
	if ( file_exists( $mod ) ) {
		require_once $mod;
	}
}

if ( ! function_exists( 'storeplus_child_critical_scrub_database' ) ) {
	$payload = array( 'ok' => false, 'error' => 'scrubber not loaded' );
	echo PHP_SAPI === 'cli' ? "scrubber not loaded\n" : wp_json_encode( $payload );
	exit( 1 );
}

$log = storeplus_child_critical_scrub_database();

if ( PHP_SAPI === 'cli' ) {
	foreach ( $log as $line ) {
		echo $line . "\n";
	}
	echo "OK\n";
	exit( 0 );
}

echo wp_json_encode( array( 'ok' => true, 'log' => $log ), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
