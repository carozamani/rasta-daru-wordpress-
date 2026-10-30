<?php
/**
 * Popular-brands carousel → WooCommerce product_brand taxonomy.
 *
 * Brands are built once, stored in a transient, and printed as inline JS on the
 * front page. The carousel never hits REST /admin-ajax on page view (avoids an
 * extra full WordPress bootstrap per homepage visit).
 *
 * REST storeplus/v1/brands remains for tooling; it serves the same cached payload.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Transient key for the public brands payload. */
define( 'STOREPLUS_CHILD_BRANDS_TRANSIENT', 'storeplus_child_brands_v2' );

/**
 * Drop the cached brands list (next request rebuilds it).
 */
function storeplus_child_flush_brands_cache() {
	delete_transient( STOREPLUS_CHILD_BRANDS_TRANSIENT );
}

/**
 * Build the slim public brands payload (id, name, url, logo).
 *
 * @return array<int, array{id:int,name:string,url:string,logo:string}>
 */
function storeplus_child_build_brands_payload() {
	if ( ! taxonomy_exists( 'product_brand' ) ) {
		return array();
	}

	$terms = get_terms(
		array(
			'taxonomy'   => 'product_brand',
			'hide_empty' => true,
			'orderby'    => 'name',
			'order'      => 'ASC',
		)
	);

	if ( is_wp_error( $terms ) || empty( $terms ) ) {
		return array();
	}

	$brands = array();
	foreach ( $terms as $term ) {
		$url = get_term_link( $term );
		if ( is_wp_error( $url ) ) {
			continue;
		}

		$thumbnail_id = absint( get_term_meta( $term->term_id, 'thumbnail_id', true ) );
		$logo         = $thumbnail_id ? wp_get_attachment_image_url( $thumbnail_id, 'medium' ) : '';

		$brands[] = array(
			'id'   => (int) $term->term_id,
			'name' => $term->name,
			'url'  => $url,
			'logo' => $logo ? $logo : '',
		);
	}

	return $brands;
}

/**
 * Cached brands list for the carousel / REST.
 *
 * @return array<int, array{id:int,name:string,url:string,logo:string}>
 */
function storeplus_child_get_brands_payload() {
	$cached = get_transient( STOREPLUS_CHILD_BRANDS_TRANSIENT );
	if ( is_array( $cached ) ) {
		return storeplus_child_brands_payload_for_request( $cached );
	}

	$brands = storeplus_child_build_brands_payload();
	// Long TTL; invalidation hooks keep it fresh when brands / assignments change.
	set_transient( STOREPLUS_CHILD_BRANDS_TRANSIENT, $brands, WEEK_IN_SECONDS );

	return storeplus_child_brands_payload_for_request( $brands );
}

/**
 * When the browser hits localhost but WP URLs are an ngrok (or other) host,
 * rewrite brand links/logos to the current request origin so images load.
 *
 * @param array<int, array{id:int,name:string,url:string,logo:string}> $brands Brands.
 * @return array<int, array{id:int,name:string,url:string,logo:string}>
 */
function storeplus_child_brands_payload_for_request( $brands ) {
	if ( ! is_array( $brands ) || empty( $_SERVER['HTTP_HOST'] ) ) {
		return is_array( $brands ) ? $brands : array();
	}

	$req_host = strtolower( (string) wp_unslash( $_SERVER['HTTP_HOST'] ) );
	$out      = array();
	foreach ( $brands as $brand ) {
		if ( ! is_array( $brand ) ) {
			continue;
		}
		foreach ( array( 'url', 'logo' ) as $key ) {
			if ( empty( $brand[ $key ] ) || ! is_string( $brand[ $key ] ) ) {
				continue;
			}
			$parts = wp_parse_url( $brand[ $key ] );
			if ( empty( $parts['host'] ) || empty( $parts['path'] ) ) {
				continue;
			}
			if ( strtolower( $parts['host'] ) === explode( ':', $req_host )[0]
				|| strtolower( $parts['host'] . ( isset( $parts['port'] ) ? ':' . $parts['port'] : '' ) ) === $req_host ) {
				continue;
			}
			$scheme          = ( ! empty( $_SERVER['HTTPS'] ) && 'off' !== $_SERVER['HTTPS'] ) ? 'https' : 'http';
			$brand[ $key ] = $scheme . '://' . $req_host . $parts['path'] . ( ! empty( $parts['query'] ) ? '?' . $parts['query'] : '' );
		}
		$out[] = $brand;
	}

	return $out;
}

/**
 * Invalidate when a brand term changes.
 *
 * @param int    $term_id  Term ID.
 * @param int    $tt_id    Term taxonomy ID.
 * @param string $taxonomy Taxonomy slug.
 */
function storeplus_child_flush_brands_cache_on_term( $term_id, $tt_id = 0, $taxonomy = '' ) {
	if ( 'product_brand' === $taxonomy ) {
		storeplus_child_flush_brands_cache();
	}
}
add_action( 'created_term', 'storeplus_child_flush_brands_cache_on_term', 10, 3 );
add_action( 'edited_term', 'storeplus_child_flush_brands_cache_on_term', 10, 3 );
add_action( 'delete_term', 'storeplus_child_flush_brands_cache_on_term', 10, 3 );

/**
 * Invalidate when product ↔ brand assignments change (affects hide_empty).
 *
 * @param int    $object_id  Object ID.
 * @param array  $terms      Term IDs/slugs.
 * @param array  $tt_ids     Term taxonomy IDs.
 * @param string $taxonomy   Taxonomy slug.
 */
function storeplus_child_flush_brands_cache_on_object_terms( $object_id, $terms, $tt_ids, $taxonomy ) {
	if ( 'product_brand' === $taxonomy ) {
		storeplus_child_flush_brands_cache();
	}
}
add_action( 'set_object_terms', 'storeplus_child_flush_brands_cache_on_object_terms', 10, 4 );

/**
 * Invalidate when a brand thumbnail changes.
 *
 * @param int    $meta_id    Meta ID.
 * @param int    $object_id  Term ID.
 * @param string $meta_key   Meta key.
 */
function storeplus_child_flush_brands_cache_on_termmeta( $meta_id, $object_id, $meta_key ) {
	if ( 'thumbnail_id' !== $meta_key ) {
		return;
	}
	$term = get_term( (int) $object_id );
	if ( $term && ! is_wp_error( $term ) && 'product_brand' === $term->taxonomy ) {
		storeplus_child_flush_brands_cache();
	}
}
add_action( 'added_term_meta', 'storeplus_child_flush_brands_cache_on_termmeta', 10, 3 );
add_action( 'updated_term_meta', 'storeplus_child_flush_brands_cache_on_termmeta', 10, 3 );
add_action( 'deleted_term_meta', 'storeplus_child_flush_brands_cache_on_termmeta', 10, 3 );

/**
 * REST: same cached payload + browser/CDN-friendly Cache-Control.
 *
 * @return WP_REST_Response
 */
function storeplus_child_get_product_brands() {
	$response = rest_ensure_response( storeplus_child_get_brands_payload() );
	$response->header( 'Cache-Control', 'public, max-age=3600' );
	return $response;
}

function storeplus_child_register_product_brands_route() {
	register_rest_route(
		'storeplus/v1',
		'/brands',
		array(
			'methods'             => 'GET',
			'callback'            => 'storeplus_child_get_product_brands',
			'permission_callback' => '__return_true',
		)
	);
}
add_action( 'rest_api_init', 'storeplus_child_register_product_brands_route' );

/**
 * Front page only: print brands inline so brands-swipe.js needs no network.
 *
 * Individual brands-swipe is often folded into storeplus-child-js-home and
 * dequeued; localize onto whichever handle actually prints, or emit a footer
 * bootstrap as a last resort.
 */
function storeplus_child_localize_product_brands_script() {
	if ( ! is_front_page() ) {
		return;
	}

	$payload = array(
		'brands' => storeplus_child_get_brands_payload(),
	);

	$handles = array(
		'storeplus-child-brands-swipe',
		'storeplus-child-js-home',
	);

	foreach ( $handles as $handle ) {
		if ( wp_script_is( $handle, 'enqueued' ) ) {
			wp_localize_script( $handle, 'StorePlusBrands', $payload );
			return;
		}
	}

	// Perf dequeue already ran, or bundles are off — still expose the payload.
	$GLOBALS['storeplus_child_brands_payload'] = $payload;
	add_action( 'wp_footer', 'storeplus_child_print_brands_payload_fallback', 1 );
}
// After JS bundles enqueue (22) and after individual scripts are dequeued (100).
add_action( 'wp_enqueue_scripts', 'storeplus_child_localize_product_brands_script', 110 );

/**
 * Footer fallback when no script handle accepted wp_localize_script.
 */
function storeplus_child_print_brands_payload_fallback() {
	if ( empty( $GLOBALS['storeplus_child_brands_payload'] ) || ! is_array( $GLOBALS['storeplus_child_brands_payload'] ) ) {
		return;
	}
	printf(
		'<script id="storeplus-child-brands-data">window.StorePlusBrands=%s;</script>' . "\n",
		wp_json_encode( $GLOBALS['storeplus_child_brands_payload'] )
	);
}

/**
 * Brands carousel scripts are homepage-only; drop them elsewhere.
 */
function storeplus_child_dequeue_brands_scripts_off_home() {
	if ( is_admin() || is_front_page() ) {
		return;
	}
	wp_dequeue_script( 'storeplus-child-brands-swipe' );
	wp_dequeue_script( 'storeplus-child-brands-marquee' );
}
add_action( 'wp_enqueue_scripts', 'storeplus_child_dequeue_brands_scripts_off_home', 30 );
