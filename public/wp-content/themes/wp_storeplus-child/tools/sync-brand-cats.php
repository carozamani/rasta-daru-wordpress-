<?php
require __DIR__ . '/_boot.php';

/**
 * Auto-assign brand → categories from products that have both (local admin only).
 */
require_once dirname( __DIR__, 4 ) . '/wp-load.php';
require_once __DIR__ . '/_security.php';
storeplus_child_tools_assert_allowed();
header( 'Content-Type: application/json; charset=utf-8' );

if ( ! taxonomy_exists( 'product_brand' ) || ! taxonomy_exists( 'product_cat' ) ) {
	echo wp_json_encode( array( 'ok' => false, 'error' => 'taxonomies missing' ) );
	exit;
}

if ( ! defined( 'SP_BRAND_CATS_META' ) ) {
	define( 'SP_BRAND_CATS_META', '_sp_brand_cats' );
}

$brand_map = array(); // brand_id => [ cat_id => true ]

$q = new WP_Query(
	array(
		'post_type'              => 'product',
		'post_status'            => 'publish',
		'posts_per_page'         => -1,
		'fields'                 => 'ids',
		'no_found_rows'          => true,
		'update_post_meta_cache' => false,
		'update_post_term_cache' => true,
	)
);

foreach ( $q->posts as $pid ) {
	$brands = wp_get_post_terms( $pid, 'product_brand', array( 'fields' => 'ids' ) );
	$cats   = wp_get_post_terms( $pid, 'product_cat', array( 'fields' => 'ids' ) );
	if ( is_wp_error( $brands ) || is_wp_error( $cats ) || ! $brands || ! $cats ) {
		continue;
	}
	foreach ( $brands as $bid ) {
		$bid = (int) $bid;
		if ( ! isset( $brand_map[ $bid ] ) ) {
			$brand_map[ $bid ] = array();
		}
		foreach ( $cats as $cid ) {
			$cid = (int) $cid;
			if ( $cid ) {
				$brand_map[ $bid ][ $cid ] = true;
			}
		}
	}
}

$report = array();
foreach ( $brand_map as $brand_id => $cat_set ) {
	$ids = array_values( array_map( 'intval', array_keys( $cat_set ) ) );
	sort( $ids );
	update_term_meta( $brand_id, SP_BRAND_CATS_META, $ids );

	$b     = get_term( $brand_id, 'product_brand' );
	$names = array();
	foreach ( $ids as $cid ) {
		$t = get_term( $cid, 'product_cat' );
		if ( $t && ! is_wp_error( $t ) ) {
			$names[] = $t->name;
		}
	}
	$report[] = array(
		'brand'      => $b && ! is_wp_error( $b ) ? $b->name : (string) $brand_id,
		'brand_id'   => $brand_id,
		'categories' => $names,
		'cat_ids'    => $ids,
	);
}

/* Brands with products but no categories left empty on purpose (won't filter-hide incorrectly if truly uncategorized). */
$all_brands = get_terms(
	array(
		'taxonomy'   => 'product_brand',
		'hide_empty' => false,
		'fields'     => 'ids',
	)
);
$untouched = array();
if ( ! is_wp_error( $all_brands ) ) {
	foreach ( $all_brands as $bid ) {
		if ( ! isset( $brand_map[ (int) $bid ] ) ) {
			$t = get_term( $bid, 'product_brand' );
			$untouched[] = $t && ! is_wp_error( $t ) ? $t->name : (string) $bid;
		}
	}
}

if ( function_exists( 'storeplus_child_bf_flush_cache' ) ) {
	storeplus_child_bf_flush_cache();
} else {
	delete_transient( 'storeplus_child_bf_box_v1' );
	delete_transient( 'storeplus_child_bf_brand_cats_v1' );
}

echo wp_json_encode(
	array(
		'ok'        => true,
		'updated'   => count( $report ),
		'brands'    => $report,
		'untouched' => $untouched,
	),
	JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
);
