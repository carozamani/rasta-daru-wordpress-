<?php
/**
 * Header menu dedupe — PHP (replaces menu-rearrange.js DOM surgery).
 *
 * If «وبلاگ» already exists in the primary navbar menu, remove the duplicate
 * from the secondary navbar menu (same contract as assets/js/menu-rearrange.js).
 *
 * @package StorePlus_Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Normalize menu item title for comparison.
 *
 * @param string $s Title.
 * @return string
 */
function storeplus_child_menu_norm_title( $s ) {
	return trim( preg_replace( '/\s+/u', ' ', wp_strip_all_tags( (string) $s ) ) );
}

/**
 * True when a title looks like the blog entry.
 *
 * @param string $title Title.
 * @return bool
 */
function storeplus_child_menu_is_blog_title( $title ) {
	$t = storeplus_child_menu_norm_title( $title );
	return ( 'وبلاگ' === $t ) || ( false !== strpos( $t, 'وبلاگ' ) && mb_strlen( $t ) <= 12 );
}

/**
 * Whether the primary header menu already contains a blog item.
 *
 * @return bool
 */
function storeplus_child_menu_primary_has_blog() {
	$locations = get_nav_menu_locations();
	$menu_id   = isset( $locations['iranthemeNavbarMainMenu'] ) ? (int) $locations['iranthemeNavbarMainMenu'] : 0;
	if ( ! $menu_id ) {
		return false;
	}
	$items = wp_get_nav_menu_items( $menu_id );
	if ( ! $items ) {
		return false;
	}
	foreach ( $items as $item ) {
		if ( storeplus_child_menu_is_blog_title( $item->title ) ) {
			return true;
		}
	}
	return false;
}

/**
 * Drop duplicate blog from secondary menu when primary already has it.
 *
 * @param WP_Post[] $items Menu items.
 * @param stdClass  $args  wp_nav_menu args.
 * @return WP_Post[]
 */
function storeplus_child_menu_dedupe_blog( $items, $args ) {
	if ( is_admin() || empty( $items ) || ! is_array( $items ) ) {
		return $items;
	}

	$theme_location = '';
	if ( is_object( $args ) && ! empty( $args->theme_location ) ) {
		$theme_location = (string) $args->theme_location;
	} elseif ( is_array( $args ) && ! empty( $args['theme_location'] ) ) {
		$theme_location = (string) $args['theme_location'];
	}

	if ( 'iranthemeNavbarSecondaryMenu' !== $theme_location ) {
		return $items;
	}

	if ( ! storeplus_child_menu_primary_has_blog() ) {
		return $items;
	}

	$out = array();
	foreach ( $items as $item ) {
		if ( storeplus_child_menu_is_blog_title( $item->title ) ) {
			continue;
		}
		$out[] = $item;
	}
	return $out;
}
add_filter( 'wp_nav_menu_objects', 'storeplus_child_menu_dedupe_blog', 20, 2 );

/**
 * Signal legacy menu-rearrange.js to no-op.
 */
add_filter(
	'language_attributes',
	static function ( $output ) {
		if ( is_admin() ) {
			return $output;
		}
		if ( false !== strpos( $output, 'data-sp-php-menu' ) ) {
			return $output;
		}
		return $output . ' data-sp-php-menu="1"';
	}
);
