<?php
require __DIR__ . '/_boot.php';

/**
 * Deep SEO enrich runner (local admin only).
 */

require_once dirname( __DIR__, 4 ) . '/wp-load.php';
require_once __DIR__ . '/_security.php';
storeplus_child_tools_assert_allowed();

header( 'Content-Type: application/json; charset=utf-8' );

$child = dirname( __DIR__ );
foreach ( array( '/inc/store-contact.php', '/inc/store-content-finish.php', '/inc/seo-enrich.php' ) as $rel ) {
	$path = $child . $rel;
	if ( file_exists( $path ) ) {
		require_once $path;
	}
}

if ( ! function_exists( 'sp_seo_enrich_run' ) ) {
	echo wp_json_encode( array( 'ok' => false, 'error' => 'seo-enrich not loaded' ), JSON_UNESCAPED_UNICODE );
	exit;
}

$log = sp_seo_enrich_run();
echo wp_json_encode( array( 'ok' => true, 'log' => $log ), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
