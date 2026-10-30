<?php
/**
 * Shop archive: isolate AJAX preloader + product grid in a stage wrapper.
 * Brand chips (and mobile filter UI) stay outside the stage so loading never hides them.
 *
 * @package StorePlus_Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @return bool
 */
function storeplus_child_shop_products_stage_enabled() {
	return (bool) apply_filters( 'storeplus_child_shop_products_stage_enabled', true );
}

/**
 * Find byte offset right after the closing </div> of the element that opens at $open_pos.
 *
 * @param string $html      HTML document.
 * @param int    $open_pos  Offset of opening `<div`.
 * @return int|false End offset (after `</div>`) or false.
 */
function storeplus_child_shop_html_div_close_end( $html, $open_pos ) {
	if ( ! preg_match( '/<div\b[^>]*>/i', $html, $m, PREG_OFFSET_CAPTURE, $open_pos ) ) {
		return false;
	}

	$len   = strlen( $html );
	$pos   = (int) $m[0][1] + strlen( $m[0][0] );
	$depth = 1;

	while ( $pos < $len && $depth > 0 ) {
		$next_open  = preg_match( '/<div\b[^>]*>/i', $html, $om, PREG_OFFSET_CAPTURE, $pos ) ? (int) $om[0][1] : -1;
		$next_close = preg_match( '/<\/div\s*>/i', $html, $cm, PREG_OFFSET_CAPTURE, $pos ) ? (int) $cm[0][1] : -1;

		if ( $next_close < 0 ) {
			return false;
		}

		if ( $next_open >= 0 && $next_open < $next_close ) {
			++$depth;
			$pos = $next_open + strlen( $om[0][0] );
			continue;
		}

		--$depth;
		$pos = $next_close + strlen( $cm[0][0] );
		if ( 0 === $depth ) {
			return $pos;
		}
	}

	return false;
}

/**
 * Wrap preloader + ajax error + products grid in .sp-shop-products-stage (SSR HTML).
 *
 * @param string $html Full page HTML.
 * @return string
 */
function storeplus_child_shop_wrap_products_stage_html( $html ) {
	if ( ! storeplus_child_shop_products_stage_enabled() ) {
		return $html;
	}
	if ( ! is_string( $html ) || '' === $html ) {
		return $html;
	}
	if ( false !== strpos( $html, 'data-sp-products-stage="1"' ) ) {
		return $html;
	}
	if ( false === strpos( $html, 'irantheme-wc-archive-product-col' )
		|| false === strpos( $html, 'irantheme-wc-archive-products' ) ) {
		return $html;
	}

	$col_pos = strpos( $html, 'irantheme-wc-archive-product-col' );
	if ( false === $col_pos ) {
		return $html;
	}

	$preloader_pos = strpos( $html, 'irantheme-preloader-abs', $col_pos );
	$grid_pos      = strpos( $html, 'irantheme-wc-archive-products', $col_pos );
	if ( false === $grid_pos ) {
		return $html;
	}

	$near        = false !== $preloader_pos ? $preloader_pos : $grid_pos;
	$chunk       = substr( $html, $col_pos, $near - $col_pos );
	$rel         = strrpos( $chunk, '<div' );
	$stage_open_at = false === $rel ? false : $col_pos + $rel;
	if ( false === $stage_open_at ) {
		return $html;
	}

	if ( ! preg_match( '/<div\b[^>]*\birantheme-wc-archive-products\b/i', $html, $gm, PREG_OFFSET_CAPTURE, $col_pos ) ) {
		return $html;
	}
	$grid_open = (int) $gm[0][1];

	$grid_end = storeplus_child_shop_html_div_close_end( $html, $grid_open );
	if ( false === $grid_end ) {
		return $html;
	}

	$open_tag = '<div class="sp-shop-products-stage" data-sp-products-stage="1">';
	$html     = substr( $html, 0, $stage_open_at ) . $open_tag . substr( $html, $stage_open_at );
	$grid_end += strlen( $open_tag );

	$html = substr( $html, 0, $grid_end ) . '</div><!-- /sp-shop-products-stage -->' . substr( $html, $grid_end );

	return $html;
}

/**
 * Enqueue stage helper (re-wrap after theme AJAX mutates the column).
 */
function storeplus_child_shop_products_stage_enqueue() {
	if ( ! storeplus_child_shop_products_stage_enabled() ) {
		return;
	}
	if ( ! function_exists( 'is_shop' ) ) {
		return;
	}
	if ( ! ( is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy() ) ) {
		return;
	}

	$js = STOREPLUS_CHILD_DIR . '/assets/js/shop-products-stage.js';
	if ( ! file_exists( $js ) ) {
		return;
	}

	wp_enqueue_script(
		'storeplus-child-shop-products-stage',
		STOREPLUS_CHILD_URI . '/assets/js/shop-products-stage.js',
		array(),
		filemtime( $js ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'storeplus_child_shop_products_stage_enqueue', 35 );
