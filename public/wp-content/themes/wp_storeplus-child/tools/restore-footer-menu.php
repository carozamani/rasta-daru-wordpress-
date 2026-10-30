<?php
require __DIR__ . '/_boot.php';

/**
 * Restore footer menu links (not header). Local admin only.
 */
require_once dirname( __DIR__, 4 ) . '/wp-load.php';
require_once __DIR__ . '/_security.php';
storeplus_child_tools_assert_allowed();
header( 'Content-Type: application/json; charset=utf-8' );

$menu = wp_get_nav_menu_object( 'منو فوتر' );
if ( ! $menu ) {
	echo wp_json_encode( array( 'ok' => false, 'error' => 'footer menu missing' ) );
	exit;
}

$want = array(
	'درباره ما'     => home_url( '/درباره-ما/' ),
	'تماس با ما'    => home_url( '/تماس-با-ما/' ),
	'سوالات متداول' => home_url( '/سوالات-متداول/' ),
);

$existing = array();
foreach ( (array) wp_get_nav_menu_items( $menu->term_id ) as $mi ) {
	$existing[] = trim( wp_strip_all_tags( $mi->title ) );
}

$added = array();
foreach ( $want as $title => $url ) {
	if ( in_array( $title, $existing, true ) ) {
		continue;
	}
	$id = wp_update_nav_menu_item(
		$menu->term_id,
		0,
		array(
			'menu-item-title'  => $title,
			'menu-item-url'    => $url,
			'menu-item-status' => 'publish',
			'menu-item-type'   => 'custom',
		)
	);
	$added[] = array( 'title' => $title, 'id' => $id );
}

echo wp_json_encode( array( 'ok' => true, 'added' => $added ), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
