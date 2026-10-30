<?php
/**
 * Shop / category archive query guards.
 *
 * 1) Parent theme JS reads missing price inputs as NaN and writes
 *    ?price_from=NaN&price_to=NaN — empty product grids.
 * 2) Parent theme resolves category SSR via basename(REQUEST_URI), which
 *    breaks when a query string is present — also empty category grids.
 *
 * @package StorePlus_Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Drop non-numeric price_from / price_to from request bags before the theme reads them.
 */
function storeplus_child_shop_sanitize_price_params() {
	$keys = array( 'price_from', 'price_to' );
	$bags = array( 'GET' => &$_GET, 'POST' => &$_POST, 'REQUEST' => &$_REQUEST );

	foreach ( $bags as &$bag ) {
		if ( ! is_array( $bag ) ) {
			continue;
		}
		foreach ( $keys as $key ) {
			if ( ! isset( $bag[ $key ] ) ) {
				continue;
			}
			$raw = $bag[ $key ];
			if ( is_array( $raw ) || ! is_numeric( $raw ) ) {
				unset( $bag[ $key ] );
			}
		}
	}
	unset( $bag );
}
add_action( 'init', 'storeplus_child_shop_sanitize_price_params', 0 );

/**
 * Parent theme resolves the category slug via basename(REQUEST_URI), which breaks when
 * the URL has a query string (basename becomes "?ordering_type=date") and yields 0 products.
 * Prefer the queried term slug for SSR archive rendering.
 */
function storeplus_child_shop_fix_category_query_slug() {
	if ( is_admin() || ! function_exists( 'is_product_category' ) || ! is_product_category() ) {
		return;
	}
	if ( ! empty( $_GET['product_cat'] ) ) {
		return;
	}
	$term = get_queried_object();
	if ( ! ( $term instanceof WP_Term ) || 'product_cat' !== $term->taxonomy || '' === $term->slug ) {
		return;
	}
	$_GET['product_cat']     = $term->slug;
	$_REQUEST['product_cat'] = $term->slug;
}
add_action( 'wp', 'storeplus_child_shop_fix_category_query_slug', 4 );

/**
 * Enqueue front-end guard on product archives.
 */
function storeplus_child_shop_price_guard_enqueue() {
	if ( ! function_exists( 'is_shop' ) ) {
		return;
	}
	if ( ! ( is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy() ) ) {
		return;
	}

	$js = STOREPLUS_CHILD_DIR . '/assets/js/shop-archive-price-guard.js';
	if ( ! file_exists( $js ) ) {
		return;
	}

	$deps = array();
	if ( wp_script_is( 'jquery', 'registered' ) ) {
		$deps[] = 'jquery';
	}

	wp_enqueue_script(
		'storeplus-child-shop-archive-price-guard',
		STOREPLUS_CHILD_URI . '/assets/js/shop-archive-price-guard.js',
		$deps,
		filemtime( $js ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'storeplus_child_shop_price_guard_enqueue', 34 );
