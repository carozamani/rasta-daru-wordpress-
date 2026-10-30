<?php
/**
 * Shop archive layout tweaks (child theme):
 * 1) Move product search from the filter sidebar into the top bar.
 * 2) Desktop: price + status into the sidebar (stacked). Brand stays as chips
 *    above the product grid (not a sidebar checklist).
 *    Mobile keeps panels in DOM for filter-chip modals (CSS/JS hide the sidebar copies).
 *
 * Sidebar stays on the right of products (RTL default: filter col first).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @return bool
 */
function storeplus_child_shop_search_topbar_enabled() {
	return (bool) apply_filters( 'storeplus_child_shop_search_topbar_enabled', true );
}

/**
 * @return bool
 */
function storeplus_child_is_product_archive() {
	return function_exists( 'is_shop' )
		&& ( is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy() );
}

/**
 * Start output buffer on product archives.
 */
function storeplus_child_shop_search_topbar_buffer_start() {
	if ( is_admin() || ! storeplus_child_shop_search_topbar_enabled() ) {
		return;
	}
	if ( ! storeplus_child_is_product_archive() ) {
		return;
	}
	ob_start( 'storeplus_child_shop_search_topbar_buffer_end' );
}
add_action( 'template_redirect', 'storeplus_child_shop_search_topbar_buffer_start', 20 );

/**
 * Extract first matching <aside>…</aside> by class token.
 *
 * @param string $html  HTML.
 * @param string $class Class token.
 * @return array{0:string,1:string} [aside_html, html_without_aside]
 */
function storeplus_child_shop_extract_aside( $html, $class ) {
	$class = preg_quote( $class, '/' );
	if ( ! preg_match(
		'/<aside\b[^>]*\b' . $class . '\b[^>]*>.*?<\/aside>/is',
		$html,
		$m
	) ) {
		return array( '', $html );
	}
	return array( $m[0], str_replace( $m[0], '', $html ) );
}

/**
 * Extract first matching element by class with nested tag depth (div/section).
 *
 * @param string $html  HTML.
 * @param string $class Class token.
 * @return array{0:string,1:string}
 */
function storeplus_child_shop_extract_by_class( $html, $class ) {
	$class = preg_quote( $class, '/' );
	if ( ! preg_match(
		'/<(div|section)\b[^>]*\b' . $class . '\b[^>]*>/i',
		$html,
		$m,
		PREG_OFFSET_CAPTURE
	) ) {
		return array( '', $html );
	}

	$tag   = $m[1][0];
	$start = (int) $m[0][1];
	$pos   = $start + strlen( $m[0][0] );
	$depth = 1;
	$len   = strlen( $html );
	$open  = '/<' . $tag . '\b[^>]*>/i';
	$close = '/<\/' . $tag . '\s*>/i';

	while ( $pos < $len && $depth > 0 ) {
		$next_open  = preg_match( $open, $html, $om, PREG_OFFSET_CAPTURE, $pos ) ? (int) $om[0][1] : -1;
		$next_close = preg_match( $close, $html, $cm, PREG_OFFSET_CAPTURE, $pos ) ? (int) $cm[0][1] : -1;

		if ( $next_close < 0 ) {
			break;
		}

		if ( $next_open >= 0 && $next_open < $next_close ) {
			++$depth;
			$pos = $next_open + strlen( $om[0][0] );
			continue;
		}

		--$depth;
		$pos = $next_close + strlen( $cm[0][0] );
		if ( 0 === $depth ) {
			$chunk = substr( $html, $start, $pos - $start );
			$rest  = substr( $html, 0, $start ) . substr( $html, $pos );
			return array( $chunk, $rest );
		}
	}

	return array( '', $html );
}

/**
 * Relocate search + price/status/brand on product archives.
 *
 * @param string $html Full page HTML.
 * @return string
 */
function storeplus_child_shop_search_topbar_buffer_end( $html ) {
	if ( ! is_string( $html ) || '' === $html ) {
		return $html;
	}

	/* ----- 1) Search → top bar ----- */
	if ( false === strpos( $html, 'data-sp-shop-search-top' )
		&& false !== strpos( $html, 'irantheme-wc-archive-filter-search' )
		&& false !== strpos( $html, 'irantheme-wc-archive-top-bar' ) ) {
		list( $aside, $html ) = storeplus_child_shop_extract_aside( $html, 'irantheme-wc-archive-filter-search' );
		if ( $aside ) {
			$item = '<div class="irantheme-wc-archive-top-bar-item irantheme-wc-archive-top-search" data-sp-shop-search-top="1">'
				. $aside
				. '</div>';
			$replaced = preg_replace(
				'/(<div\b[^>]*\birantheme-wc-archive-top-bar\b[^>]*>)/i',
				'$1' . $item,
				$html,
				1,
				$count
			);
			if ( $count > 0 && is_string( $replaced ) ) {
				$html = $replaced;
			}
		}
	}

	/* ----- 2) Price + status → sidebar; brand chips stay above product cards ----- */
	if ( false === strpos( $html, 'data-sp-shop-priority-filters' ) ) {
		list( $price, $html )  = storeplus_child_shop_extract_aside( $html, 'irantheme-wc-archive-filter-price' );
		list( $status, $html ) = storeplus_child_shop_extract_aside( $html, 'irantheme-wc-archive-filter-status' );

		/* Strip leftover sidebar brand wrapper if an older buffer left one. */
		if ( false !== strpos( $html, 'data-sp-brand-sidebar' ) ) {
			list( $old_brand_box, $html ) = storeplus_child_shop_extract_aside( $html, 'sp-brand-sidebar-box' );
			if ( $old_brand_box && false !== strpos( $old_brand_box, 'sp-brand-chips' )
				&& false === strpos( $html, 'sp-brand-chips' ) ) {
				list( $brand_inner, ) = storeplus_child_shop_extract_by_class( $old_brand_box, 'sp-brand-chips' );
				if ( $brand_inner && false !== strpos( $html, 'irantheme-wc-archive-product-col' ) ) {
					$replaced = preg_replace(
						'/(<div\b[^>]*\birantheme-wc-archive-product-col\b[^>]*>)/i',
						'$1' . $brand_inner,
						$html,
						1,
						$count
					);
					if ( $count > 0 && is_string( $replaced ) ) {
						$html = $replaced;
					}
				}
			}
		}

		if ( $price || $status ) {
			$block = '<div class="sp-shop-priority-filters" data-sp-shop-priority-filters="1">'
				. $price
				. $status
				. '</div>';

			$placed = false;
			if ( false !== strpos( $html, 'irantheme-wc-archive-main-filter' ) ) {
				/* Top of sidebar: price → status, then category / attributes. */
				$replaced = preg_replace(
					'/(<div\b[^>]*\birantheme-wc-archive-main-filter\b[^>]*>)/i',
					'$1' . $block,
					$html,
					1,
					$count
				);
				if ( $count > 0 && is_string( $replaced ) ) {
					$html   = $replaced;
					$placed = true;
				}
			}
			/* Fallback: keep above products if sidebar missing. */
			if ( ! $placed && false !== strpos( $html, 'irantheme-wc-archive-product-col' ) ) {
				$replaced = preg_replace(
					'/(<div\b[^>]*\birantheme-wc-archive-product-col\b[^>]*>)/i',
					'$1' . $block,
					$html,
					1,
					$count
				);
				if ( $count > 0 && is_string( $replaced ) ) {
					$html = $replaced;
				}
			}
		}
	}

	/* ----- 3) Spinner preloader → product-card skeleton (same bones as landing) ----- */
	if ( function_exists( 'storeplus_child_shop_replace_preloader_with_skeleton' ) ) {
		$html = storeplus_child_shop_replace_preloader_with_skeleton( $html );
	}

	if ( function_exists( 'storeplus_child_shop_wrap_products_stage_html' ) ) {
		$html = storeplus_child_shop_wrap_products_stage_html( $html );
	}

	return $html;
}
