<?php
/**
 * Brand filter chips above shop product cards + brand archive behaviour.
 *
 * 1) Shop / category / tag pages: brand chips (WooCommerce brands, taxonomy product_brand) render above the
 *    product grid (not in the sidebar). Same theme AJAX filter markup (data-tax="sp-brand"), mapped to
 *    product_brand on the server. Chip list follows selected categories (Products > Brands >
 *    "دسته‌بندی‌های برند"). Brands with no category stay visible.
 *    Desktop layout: a "برند ها :" title followed by the chips, placed ABOVE the white products box.
 *
 * 2) Brand archive page (home slider brand click): ALL products of that brand; sidebar filters still apply.
 *    Brand chips are not shown there.
 *
 * Files: inc/brand-filter.php, assets/js/brand-filter.js, assets/css/brand-chips.css
 * (needs inc/brand-categories.php for the assignments).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const SP_BF_TAX = 'pa_sp-brand'; // fake attribute taxonomy used by the sidebar box (data-tax="sp-brand")

/* ---------------------------------------------------------------------------
 * Server side filtering
 * ------------------------------------------------------------------------- */

/** Brand term of the page that sent the current AJAX request (brand archive), or null. */
function storeplus_child_bf_brand_from_referer() {
	static $cache = false;
	if ( false !== $cache ) {
		return $cache;
	}
	$cache = null;
	$ref   = isset( $_SERVER['HTTP_REFERER'] ) ? esc_url_raw( wp_unslash( $_SERVER['HTTP_REFERER'] ) ) : '';
	if ( '' === $ref || ! taxonomy_exists( 'product_brand' ) ) {
		return $cache;
	}
	$ref_path = trailingslashit( rawurldecode( (string) wp_parse_url( $ref, PHP_URL_PATH ) ) );

	foreach ( array_reverse( explode( '/', trim( $ref_path, '/' ) ) ) as $seg ) {
		if ( '' === $seg || ctype_digit( $seg ) || 'page' === $seg ) {
			continue;
		}
		$term = get_term_by( 'slug', $seg, 'product_brand' );
		if ( ! $term ) {
			continue;
		}
		$link = get_term_link( $term );
		if ( is_wp_error( $link ) ) {
			continue;
		}
		$link_path = trailingslashit( rawurldecode( (string) wp_parse_url( $link, PHP_URL_PATH ) ) );
		if ( 0 === strpos( $ref_path, $link_path ) ) {
			$cache = $term;
			return $cache;
		}
	}
	return $cache;
}

/**
 * Runs on every WP_Query: maps the sidebar's fake "brand attribute" to product_brand, and on a brand
 * archive limits the AJAX product list to that brand (the other sidebar filters are added on top).
 */
function storeplus_child_bf_pre_get_posts( $query ) {
	if ( is_admin() && ! wp_doing_ajax() ) {
		return;
	}
	if ( 'product' !== $query->get( 'post_type' ) || -1 === (int) $query->get( 'posts_per_page' ) ) {
		return; // only the theme's filtered archive query
	}

	$tax_query = $query->get( 'tax_query' );
	if ( ! is_array( $tax_query ) ) {
		$tax_query = array();
	}
	$changed = false;

	foreach ( $tax_query as $i => $clause ) {
		if ( is_array( $clause ) && isset( $clause['taxonomy'] ) && SP_BF_TAX === $clause['taxonomy'] ) {
			$tax_query[ $i ]['taxonomy'] = 'product_brand';
			$changed                     = true;
		}
	}

	$is_filter_ajax = wp_doing_ajax() && isset( $_REQUEST['action'] ) && 'apply_filter_to_archive_product' === $_REQUEST['action'];
	if ( $is_filter_ajax ) {
		$brand = storeplus_child_bf_brand_from_referer();
		if ( $brand ) {
			$tax_query[] = array(
				'taxonomy' => 'product_brand',
				'field'    => 'term_id',
				'terms'    => array( (int) $brand->term_id ),
			);
			$changed     = true;
		}
	}

	if ( $changed ) {
		$query->set( 'tax_query', $tax_query );
	}
}
add_action( 'pre_get_posts', 'storeplus_child_bf_pre_get_posts' );

/* ---------------------------------------------------------------------------
 * Sidebar box
 * ------------------------------------------------------------------------- */

if ( ! defined( 'STOREPLUS_CHILD_BF_BOX_TRANSIENT' ) ) {
	// v2: markup now contains the "برند ها :" title (bumped so cached v1 markup is not reused).
	define( 'STOREPLUS_CHILD_BF_BOX_TRANSIENT', 'storeplus_child_bf_chips_v2' );
}
if ( ! defined( 'STOREPLUS_CHILD_BF_CATS_TRANSIENT' ) ) {
	define( 'STOREPLUS_CHILD_BF_CATS_TRANSIENT', 'storeplus_child_bf_brand_cats_v1' );
}

/**
 * Drop cached brand-filter markup / category maps.
 */
function storeplus_child_bf_flush_cache() {
	delete_transient( STOREPLUS_CHILD_BF_BOX_TRANSIENT );
	delete_transient( STOREPLUS_CHILD_BF_CATS_TRANSIENT );
}

/**
 * Map of brand_id => widened product_cat slugs (request + transient cache).
 *
 * @return array<int, string[]>
 */
function storeplus_child_bf_brand_cats_map() {
	static $map = null;
	if ( null !== $map ) {
		return $map;
	}

	$cached = get_transient( STOREPLUS_CHILD_BF_CATS_TRANSIENT );
	if ( is_array( $cached ) ) {
		$map = $cached;
		return $map;
	}

	$map = array();
	if ( ! function_exists( 'storeplus_child_brand_category_ids' ) || ! taxonomy_exists( 'product_brand' ) ) {
		return $map;
	}

	$brands = get_terms(
		array(
			'taxonomy'   => 'product_brand',
			'hide_empty' => false,
			'fields'     => 'ids',
		)
	);
	if ( is_wp_error( $brands ) ) {
		return $map;
	}

	foreach ( $brands as $brand_id ) {
		$brand_id = (int) $brand_id;
		$ids      = array();
		foreach ( storeplus_child_brand_category_ids( $brand_id ) as $cat_id ) {
			$ids[] = (int) $cat_id;
			$kids  = get_term_children( $cat_id, 'product_cat' );
			if ( ! is_wp_error( $kids ) ) {
				$ids = array_merge( $ids, array_map( 'intval', $kids ) );
			}
			$ids = array_merge( $ids, array_map( 'intval', get_ancestors( $cat_id, 'product_cat', 'taxonomy' ) ) );
		}
		$slugs = array();
		foreach ( array_unique( $ids ) as $id ) {
			$t = get_term( $id, 'product_cat' );
			if ( $t && ! is_wp_error( $t ) ) {
				$slugs[] = $t->slug;
			}
		}
		$map[ $brand_id ] = $slugs;
	}

	set_transient( STOREPLUS_CHILD_BF_CATS_TRANSIENT, $map, WEEK_IN_SECONDS );
	return $map;
}

/** Category slugs a brand belongs to, widened to sub-categories and parent categories. */
function storeplus_child_bf_brand_cat_slugs( $brand_id ) {
	$map = storeplus_child_bf_brand_cats_map();
	$brand_id = (int) $brand_id;
	return isset( $map[ $brand_id ] ) ? $map[ $brand_id ] : array();
}

function storeplus_child_bf_is_filter_page() {
	if ( ! function_exists( 'is_shop' ) ) {
		return false;
	}
	if ( is_tax( 'product_brand' ) ) {
		return false; // brand archive: no brand box
	}
	return is_shop() || is_product_category() || is_product_tag() || is_post_type_archive( 'product' );
}

function storeplus_child_bf_box_html() {
	$cached = get_transient( STOREPLUS_CHILD_BF_BOX_TRANSIENT );
	if ( is_string( $cached ) ) {
		return $cached;
	}

	if ( ! taxonomy_exists( 'product_brand' ) ) {
		set_transient( STOREPLUS_CHILD_BF_BOX_TRANSIENT, '', HOUR_IN_SECONDS );
		return '';
	}
	$brands = get_terms(
		array(
			'taxonomy'   => 'product_brand',
			'hide_empty' => true,
			'orderby'    => 'name',
			'order'      => 'ASC',
		)
	);
	if ( is_wp_error( $brands ) || empty( $brands ) ) {
		set_transient( STOREPLUS_CHILD_BF_BOX_TRANSIENT, '', HOUR_IN_SECONDS );
		return '';
	}

	$items = '';
	foreach ( $brands as $brand ) {
		$cats   = storeplus_child_bf_brand_cat_slugs( $brand->term_id );
		$items .= sprintf(
			'<label class="sp-brand-chip irantheme-wc-archive-filter-content-label" data-sp-cats="%s">'
			. '<input type="checkbox" id="irantheme_wc_archive_filter_sp-brand_%s" class="sp-brand-chip__input" name="product_filter_item[]" value="%s">'
			. '<span class="sp-brand-chip__text">%s</span>'
			. '</label>',
			esc_attr( implode( ',', $cats ) ),
			esc_attr( $brand->slug ),
			esc_attr( $brand->slug ),
			esc_html( $brand->name )
		);
	}

	$html = '<div class="sp-brand-chips sp-brand-filter" role="group" aria-label="برند">'
		. '<span class="sp-brand-chips__title">برند ها :</span>'
		. '<button type="button" class="sp-brand-chips__nav sp-brand-chips__nav--next" aria-label="برندهای بیشتر" hidden><i class="fa-light fa-chevron-left" aria-hidden="true"></i></button>'
		. '<div class="sp-brand-chips__track irantheme-wc-archive-dynamic-filter irantheme-wc-archive-filter-sp-brand" data-tax="sp-brand">'
		. $items
		. '</div></div>';

	set_transient( STOREPLUS_CHILD_BF_BOX_TRANSIENT, $html, WEEK_IN_SECONDS );
	return $html;
}

/**
 * Print brand chips above product cards (hooked from archive-product.php — no page HTML buffer).
 */
function storeplus_child_bf_render_box() {
	if ( ! storeplus_child_bf_is_filter_page() ) {
		return;
	}
	echo storeplus_child_bf_box_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built with escaped pieces
}
add_action( 'storeplus_child_before_archive_products', 'storeplus_child_bf_render_box' );

/**
 * Invalidate brand-filter caches when brands / category assignments change.
 *
 * @param int    $term_id  Term ID.
 * @param int    $tt_id    Term taxonomy ID.
 * @param string $taxonomy Taxonomy slug.
 */
function storeplus_child_bf_flush_cache_on_term( $term_id, $tt_id = 0, $taxonomy = '' ) {
	if ( in_array( $taxonomy, array( 'product_brand', 'product_cat' ), true ) ) {
		storeplus_child_bf_flush_cache();
	}
}
add_action( 'created_term', 'storeplus_child_bf_flush_cache_on_term', 20, 3 );
add_action( 'edited_term', 'storeplus_child_bf_flush_cache_on_term', 20, 3 );
add_action( 'delete_term', 'storeplus_child_bf_flush_cache_on_term', 20, 3 );

/**
 * @param int    $meta_id   Meta ID.
 * @param int    $object_id Term ID.
 * @param string $meta_key  Meta key.
 */
function storeplus_child_bf_flush_cache_on_termmeta( $meta_id, $object_id, $meta_key ) {
	if ( defined( 'SP_BRAND_CATS_META' ) && SP_BRAND_CATS_META === $meta_key ) {
		storeplus_child_bf_flush_cache();
	}
}
add_action( 'added_term_meta', 'storeplus_child_bf_flush_cache_on_termmeta', 10, 3 );
add_action( 'updated_term_meta', 'storeplus_child_bf_flush_cache_on_termmeta', 10, 3 );
add_action( 'deleted_term_meta', 'storeplus_child_bf_flush_cache_on_termmeta', 10, 3 );

/* ---------------------------------------------------------------------------
 * Script: brand list follows the selected categories
 * ------------------------------------------------------------------------- */
function storeplus_child_bf_enqueue() {
	if ( ! storeplus_child_bf_is_filter_page() ) {
		return;
	}

	/*
	 * brand-chips.css is baked into the stable shop.css sheet (perf shop bundle
	 * or storeplus_child_enqueue_shop_sheet). Only enqueue the legacy file
	 * when neither path is active.
	 */
	$shop_covered = ( function_exists( 'storeplus_child_perf_enabled' ) && storeplus_child_perf_enabled() )
		|| wp_style_is( 'storeplus-child-shop', 'enqueued' )
		|| wp_style_is( 'storeplus-child-bundle-shop', 'enqueued' );
	$css = STOREPLUS_CHILD_DIR . '/assets/css/brand-chips.css';
	if ( ! $shop_covered && file_exists( $css ) ) {
		wp_enqueue_style(
			'storeplus-child-brand-chips',
			STOREPLUS_CHILD_URI . '/assets/css/brand-chips.css',
			array( 'irantheme-main-css', 'style' ),
			filemtime( $css )
		);
	}

	$js = STOREPLUS_CHILD_DIR . '/assets/js/brand-filter.js';
	if ( ! file_exists( $js ) ) {
		return;
	}
	wp_enqueue_script( 'storeplus-child-brand-filter', STOREPLUS_CHILD_URI . '/assets/js/brand-filter.js', array(), filemtime( $js ), true );

	$cat = '';
	if ( is_product_category() ) {
		$term = get_queried_object();
		$cat  = ( $term && ! empty( $term->slug ) ) ? $term->slug : '';
	}
	wp_add_inline_script( 'storeplus-child-brand-filter', 'window.spBrandFilter=' . wp_json_encode( array( 'cat' => $cat ) ) . ';', 'before' );
}
add_action( 'wp_enqueue_scripts', 'storeplus_child_bf_enqueue', 30 );
