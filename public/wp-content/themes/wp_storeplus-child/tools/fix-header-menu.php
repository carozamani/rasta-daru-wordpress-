<?php
require __DIR__ . '/_boot.php';

/**
 * Remove static page links from header menus (local admin only).
 */
require_once dirname( __DIR__, 4 ) . '/wp-load.php';
require_once __DIR__ . '/_security.php';
storeplus_child_tools_assert_allowed();
header( 'Content-Type: application/json; charset=utf-8' );

$remove_titles = array(
	'فروشگاه',
	'درباره ما',
	'تماس با ما',
	'سوالات متداول',
	'قوانین و مقررات',
);

$remove_slugs = array(
	'shop',
	'درباره-ما',
	'تماس-با-ما',
	'سوالات-متداول',
	'ghavanin',
	'harim-khosusi',
);

$locations = get_nav_menu_locations();
$report    = array(
	'locations' => $locations,
	'removed'   => array(),
	'menus'     => array(),
);

$menu_ids = array();
foreach ( $locations as $loc => $mid ) {
	if ( $mid ) {
		$menu_ids[ (int) $mid ] = true;
	}
}
/* Also scan named secondary menu used by this theme. */
foreach ( array( 'منوی ثانویه', 'منو فوتر', 'منوی اصلی' ) as $name ) {
	$m = wp_get_nav_menu_object( $name );
	if ( $m ) {
		$menu_ids[ (int) $m->term_id ] = true;
	}
}

foreach ( array_keys( $menu_ids ) as $menu_id ) {
	$menu  = wp_get_nav_menu_object( $menu_id );
	$items = wp_get_nav_menu_items( $menu_id );
	$list  = array();
	foreach ( (array) $items as $item ) {
		$title = trim( wp_strip_all_tags( $item->title ) );
		$url   = (string) $item->url;
		$hit   = in_array( $title, $remove_titles, true );
		if ( ! $hit ) {
			foreach ( $remove_slugs as $slug ) {
				if ( false !== strpos( $url, '/' . rawurlencode( $slug ) . '/' )
					|| false !== strpos( $url, '/' . $slug . '/' )
					|| false !== strpos( $url, $slug ) ) {
					/* Only match clear page URLs, not product-category paths accidentally. */
					if ( in_array( $title, $remove_titles, true )
						|| preg_match( '#/(shop|ghavanin|harim-khosusi|درباره-ما|تماس-با-ما|سوالات-متداول)/?#u', rawurldecode( $url ) ) ) {
						$hit = true;
						break;
					}
				}
			}
		}
		/* Title-based removal is the reliable signal for these header links. */
		$hit = in_array( $title, $remove_titles, true );

		$list[] = array( 'id' => $item->ID, 'title' => $title, 'url' => $url, 'remove' => $hit );
		if ( $hit ) {
			wp_delete_post( $item->ID, true );
			$report['removed'][] = array(
				'menu'  => $menu ? $menu->name : (string) $menu_id,
				'title' => $title,
				'id'    => $item->ID,
			);
		}
	}
	$report['menus'][] = array(
		'id'    => $menu_id,
		'name'  => $menu ? $menu->name : '',
		'items' => $list,
	);
}

echo wp_json_encode( $report, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
