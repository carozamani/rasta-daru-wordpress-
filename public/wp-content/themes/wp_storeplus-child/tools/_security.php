<?php
/**
 * Gate for one-shot theme tools (defence in depth).
 *
 * Primary lock: tools/_boot.php — HTTP always 404 (CLI only).
 * Nginx also denies the theme tools directory under /wp-content/themes/ (see nginx-security.snippet.conf).
 *
 * This helper remains for any future in-process callers after wp-load.php.
 * Call AFTER wp-load.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Abort unless this request is allowed to run maintenance tools.
 *
 * @return void
 */
function storeplus_child_tools_assert_allowed() {
	if ( PHP_SAPI === 'cli' || PHP_SAPI === 'phpdbg' ) {
		return;
	}

	$host = isset( $_SERVER['HTTP_HOST'] ) ? (string) $_SERVER['HTTP_HOST'] : '';
	$ok_host = ( false !== strpos( $host, 'localhost' ) )
		|| ( false !== strpos( $host, '127.0.0.1' ) )
		|| ( false !== strpos( $host, '.local' ) );

	if ( ! $ok_host ) {
		status_header( 403 );
		header( 'Content-Type: text/plain; charset=utf-8' );
		exit( 'Host not allowed' );
	}

	$env = function_exists( 'wp_get_environment_type' )
		? wp_get_environment_type()
		: ( defined( 'WP_ENVIRONMENT_TYPE' ) ? WP_ENVIRONMENT_TYPE : '' );

	if ( 'local' !== $env ) {
		status_header( 403 );
		header( 'Content-Type: text/plain; charset=utf-8' );
		exit( 'Not a local environment' );
	}

	if ( ! is_user_logged_in() || ! current_user_can( 'manage_options' ) ) {
		status_header( 403 );
		header( 'Content-Type: text/plain; charset=utf-8' );
		exit( 'Admin login required' );
	}
}
