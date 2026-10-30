<?php
/**
 * Plugin Name: Fast 404 for missing media files
 * Description: Answers requests for files under /wp-content/uploads/ that no longer exist with an immediate 404,
 *              instead of booting the whole of WordPress (plugins, theme, WooCommerce) for every broken image.
 *              Safe to delete once the media library no longer references removed files.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

( function () {
	if ( empty( $_SERVER['REQUEST_URI'] ) || 'GET' !== ( $_SERVER['REQUEST_METHOD'] ?? '' ) && 'HEAD' !== ( $_SERVER['REQUEST_METHOD'] ?? '' ) ) {
		return;
	}

	$path = parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );
	if ( ! is_string( $path ) ) {
		return;
	}
	$path = rawurldecode( $path );

	// Only plain media files inside the uploads folder.
	if ( false !== strpos( $path, '..' ) || ! preg_match( '#/wp-content/uploads/.+\.(png|jpe?g|gif|webp|svg|avif|ico|mp4)$#i', $path ) ) {
		return;
	}

	$file = rtrim( ABSPATH, '/\\' ) . substr( $path, strpos( $path, '/wp-content/' ) );
	if ( file_exists( $file ) ) {
		return; // The web server normally serves existing files itself; nothing to do.
	}

	status_header( 404 );
	header( 'Content-Type: text/plain; charset=utf-8' );
	header( 'Cache-Control: no-store' );
	echo 'Not found';
	exit;
} )();
