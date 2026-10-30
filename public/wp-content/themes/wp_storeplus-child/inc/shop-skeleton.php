<?php
/**
 * Shop / category archive: product-card skeleton instead of the white spinner box.
 *
 * Parent theme shows `.irantheme-preloader-abs` (spinner) while AJAX fills
 * `.irantheme-wc-archive-products` (shop page always AJAX; category SSR then AJAX on filter).
 * We swap that preloader for the same product bones as the landing skeleton
 * (inc/home-skeleton.php → storeplus_child_skeleton_bone).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Archive grid column count from theme option.
 *
 * @return int
 */
function storeplus_child_shop_skeleton_columns() {
	$col = (string) get_option( 'irantheme_wc_archive_col_count_radio', 'col-4' );
	$map = array(
		'col-3' => 3,
		'col-4' => 4,
		'col-5' => 5,
		'col-6' => 6,
		'col-7' => 7,
		'col-8' => 8,
	);
	$cols = isset( $map[ $col ] ) ? (int) $map[ $col ] : 4;
	return (int) apply_filters( 'storeplus_child_shop_skeleton_columns', $cols );
}

/**
 * How many product bones to draw (about two rows).
 *
 * @param int $columns Grid columns.
 * @return int
 */
function storeplus_child_shop_skeleton_count( $columns ) {
	$columns = max( 2, (int) $columns );
	$count   = $columns * 2;
	return (int) apply_filters( 'storeplus_child_shop_skeleton_count', $count, $columns );
}

/**
 * Inner skeleton markup (product cards matching landing).
 *
 * @param int|null $columns Optional column override.
 * @return string
 */
function storeplus_child_shop_skeleton_inner_html( $columns = null ) {
	$columns = null === $columns ? storeplus_child_shop_skeleton_columns() : max( 2, (int) $columns );
	$count   = storeplus_child_shop_skeleton_count( $columns );

	$bones = '';
	if ( function_exists( 'storeplus_child_skeleton_bone_html' ) ) {
		for ( $i = 0; $i < $count; $i++ ) {
			$bones .= storeplus_child_skeleton_bone_html( 'product' );
		}
	}

	return '<div class="sp-skel-loader sp-skel-loader--products sp-shop-skel" role="status" aria-live="polite" aria-label="' . esc_attr( 'در حال بارگذاری محصولات' ) . '">'
		. '<div class="irantheme-skeleton-grid sp-skel-grid" data-columns="' . (int) $columns . '" data-type="product">'
		. $bones
		. '</div></div>';
}

/**
 * Full preloader shell with skeleton (replaces spinner).
 *
 * @param string $base_color Theme base colour class (white|dark).
 * @param bool   $visible    Show immediately (shop first paint / empty grid).
 * @return string
 */
function storeplus_child_shop_skeleton_preloader_html( $base_color = 'white', $visible = false ) {
	$base_color = in_array( $base_color, array( 'white', 'dark' ), true ) ? $base_color : 'white';
	$classes    = 'irantheme-preloader-abs sp-shop-skel-preloader ' . $base_color;
	$style      = '';
	if ( $visible ) {
		$classes .= ' active';
		$style    = ' style="visibility:visible;opacity:1"';
	}

	return '<div class="' . esc_attr( $classes ) . '"' . $style . ' data-sp-shop-skel="1">'
		. storeplus_child_shop_skeleton_inner_html()
		. '</div>';
}

/**
 * Whether this archive will AJAX-load products on first paint (empty SSR grid).
 *
 * @return bool
 */
function storeplus_child_shop_skeleton_pending_first_paint() {
	if ( ! function_exists( 'is_shop' ) ) {
		return false;
	}
	/* Category / tag SSR products; main shop + other tax archives wait for AJAX. */
	if ( is_product_category() || is_product_tag() || is_tax( 'product_cat' ) ) {
		return false;
	}
	return is_shop() || is_product_taxonomy();
}

/**
 * Swap the spinner preloader for product-card skeletons in archive HTML.
 *
 * @param string $html Full page HTML.
 * @return string
 */
function storeplus_child_shop_replace_preloader_with_skeleton( $html ) {
	/* Product-card bones are retired. Navigation uses the logo page loader. */
	return $html;

	if ( ! is_string( $html ) || '' === $html ) {
		return $html;
	}
	if ( false === strpos( $html, 'irantheme-preloader-abs' ) ) {
		return $html;
	}
	if ( false !== strpos( $html, 'data-sp-shop-skel=' ) ) {
		return $html;
	}
	if ( ! function_exists( 'storeplus_child_skeleton_bone' ) ) {
		return $html;
	}

	$base = 'white';
	if ( preg_match( '/irantheme-preloader-abs[^"\'>]*\b(dark|white)\b/', $html, $m ) ) {
		$base = $m[1];
	}

	$visible = storeplus_child_shop_skeleton_pending_first_paint();
	$skel    = storeplus_child_shop_skeleton_preloader_html( $base, $visible );

	$replaced = preg_replace(
		'/<div\b[^>]*\birantheme-preloader-abs\b[^>]*>\s*<i\b[^>]*irantheme-preloader-loader[^>]*>\s*<\/i>\s*<\/div>/is',
		$skel,
		$html,
		1,
		$count
	);

	if ( $count > 0 && is_string( $replaced ) ) {
		$html = $replaced;
	}

	return $html;
}
