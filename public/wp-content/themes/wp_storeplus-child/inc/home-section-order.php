<?php
/**
 * Home section order — PHP (no DOM surgery).
 *
 * Mirrors the previous front-end JS placement so first paint already matches:
 *  - bestsellers (mini-products) directly under «آخرین محصولات»
 *  - banner slot 2 under bestsellers
 *  - brands directly above blog («مطالب وبلاگ»)
 *  - banner slot 3 directly above brands
 *
 * Front-end only. Never writes post meta. Page Builder JSON in the DB stays intact.
 *
 * @package StorePlus_Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Plain heading text from a page-builder section (spaces kept for matching).
 *
 * @param array $section Section row.
 * @return string
 */
function storeplus_child_section_heading_text( $section ) {
	if ( empty( $section['section_fields'] ) || ! is_array( $section['section_fields'] ) ) {
		return '';
	}
	$sf = $section['section_fields'];
	$raw = '';
	if ( ! empty( $sf['text_editor_heading'] ) ) {
		$raw = (string) $sf['text_editor_heading'];
	} elseif ( ! empty( $sf['heading_text'] ) ) {
		$raw = (string) $sf['heading_text'];
	}
	return trim( preg_replace( '/\s+/u', ' ', wp_strip_all_tags( $raw ) ) );
}

/**
 * Compact heading (no spaces) for Persian title matching.
 *
 * @param array $section Section row.
 * @return string
 */
function storeplus_child_section_heading_compact( $section ) {
	return preg_replace( '/\s+/u', '', storeplus_child_section_heading_text( $section ) );
}

/**
 * Find index of «آخرین محصولات» (woocommerce_products), else first plain products section.
 *
 * @param array $items sections_items.
 * @return int|null
 */
function storeplus_child_home_find_latest_index( array $items ) {
	$fallback = null;
	foreach ( $items as $i => $section ) {
		if ( ( $section['data_value'] ?? '' ) !== 'woocommerce_products' ) {
			continue;
		}
		$h = storeplus_child_section_heading_text( $section );
		if ( false !== strpos( $h, 'آخرین محصولات' ) ) {
			return (int) $i;
		}
		if ( null === $fallback ) {
			$fallback = (int) $i;
		}
	}
	return $fallback;
}

/**
 * Find bestsellers mini-products index (title match preferred).
 *
 * @param array $items sections_items.
 * @return int|null
 */
function storeplus_child_home_find_bestsellers_index( array $items ) {
	$fallback = null;
	foreach ( $items as $i => $section ) {
		if ( ( $section['data_value'] ?? '' ) !== 'woocommerce_mini_products' ) {
			continue;
		}
		$c = storeplus_child_section_heading_compact( $section );
		if ( false !== strpos( $c, 'پرفروشترین' ) || false !== strpos( $c, 'پرفروش' ) ) {
			return (int) $i;
		}
		if ( null === $fallback ) {
			$fallback = (int) $i;
		}
	}
	return $fallback;
}

/**
 * @param array  $items sections_items.
 * @param string $data_value Section type.
 * @return int|null
 */
function storeplus_child_home_find_type_index( array $items, $data_value ) {
	foreach ( $items as $i => $section ) {
		if ( ( $section['data_value'] ?? '' ) === $data_value ) {
			return (int) $i;
		}
	}
	return null;
}

/**
 * @param array $items sections_items.
 * @return int[] Banner indices in current order.
 */
function storeplus_child_home_banner_indices( array $items ) {
	$out = array();
	foreach ( $items as $i => $section ) {
		if ( ( $section['data_value'] ?? '' ) === 'business_ibanner' ) {
			$out[] = (int) $i;
		}
	}
	return $out;
}

/**
 * Pull item at index and reindex.
 *
 * @param array $items Items.
 * @param int   $index Index.
 * @return array{0:array|null,1:array} [item, items]
 */
function storeplus_child_home_pull_index( array $items, $index ) {
	if ( ! isset( $items[ $index ] ) ) {
		return array( null, $items );
	}
	$item = $items[ $index ];
	unset( $items[ $index ] );
	return array( $item, array_values( $items ) );
}

/**
 * Insert $item so it sits at $at (0-based). Reindexes.
 *
 * @param array $items Items.
 * @param int   $at    Target index.
 * @param array $item  Section.
 * @return array
 */
function storeplus_child_home_insert_at( array $items, $at, array $item ) {
	$at = max( 0, min( (int) $at, count( $items ) ) );
	array_splice( $items, $at, 0, array( $item ) );
	return $items;
}

/**
 * Reorder homepage sections to match the previous JS layout contract.
 *
 * @param array $items sections_items.
 * @return array{items:array,changed:bool}
 */
function storeplus_child_home_reorder_sections( array $items ) {
	$changed = false;
	if ( count( $items ) < 2 ) {
		return array(
			'items'   => $items,
			'changed' => false,
		);
	}

	/* 1) Bestsellers directly under latest products. */
	$best_i   = storeplus_child_home_find_bestsellers_index( $items );
	$latest_i = storeplus_child_home_find_latest_index( $items );
	if ( null !== $best_i && null !== $latest_i && $best_i !== $latest_i ) {
		$desired = $latest_i + 1;
		if ( $best_i !== $desired ) {
			list( $best, $items ) = storeplus_child_home_pull_index( $items, $best_i );
			if ( $best ) {
				$latest_i = storeplus_child_home_find_latest_index( $items );
				$at       = ( null === $latest_i ) ? count( $items ) : $latest_i + 1;
				$items    = storeplus_child_home_insert_at( $items, $at, $best );
				$changed  = true;
			}
		}
	}

	/* 2) Brands directly above blog (fallback: after last non-footer section — blog missing). */
	$brands_i = storeplus_child_home_find_type_index( $items, 'woocommerce_box_brands' );
	$blog_i   = storeplus_child_home_find_type_index( $items, 'post_simple' );
	if ( null !== $brands_i ) {
		if ( null !== $blog_i ) {
			$desired = ( $brands_i < $blog_i ) ? $blog_i - 1 : $blog_i;
			if ( $brands_i !== $desired ) {
				list( $brands, $items ) = storeplus_child_home_pull_index( $items, $brands_i );
				if ( $brands ) {
					$blog_i  = storeplus_child_home_find_type_index( $items, 'post_simple' );
					$at      = ( null === $blog_i ) ? count( $items ) : $blog_i;
					$items   = storeplus_child_home_insert_at( $items, $at, $brands );
					$changed = true;
				}
			}
		}
	}

	/* 3) Banner slot 2 (2nd ibanner) after bestsellers; slot 3 (3rd) before brands. */
	$banners = storeplus_child_home_banner_indices( $items );
	if ( isset( $banners[1] ) ) {
		$box2_i = $banners[1];
		$best_i = storeplus_child_home_find_bestsellers_index( $items );
		if ( null !== $best_i ) {
			$desired = $best_i + 1;
			if ( $box2_i !== $desired ) {
				list( $box2, $items ) = storeplus_child_home_pull_index( $items, $box2_i );
				if ( $box2 ) {
					$best_i  = storeplus_child_home_find_bestsellers_index( $items );
					$at      = ( null === $best_i ) ? count( $items ) : $best_i + 1;
					$items   = storeplus_child_home_insert_at( $items, $at, $box2 );
					$changed = true;
				}
			}
		}
	}

	$banners = storeplus_child_home_banner_indices( $items );
	if ( isset( $banners[2] ) ) {
		$box3_i   = $banners[2];
		$brands_i = storeplus_child_home_find_type_index( $items, 'woocommerce_box_brands' );
		if ( null !== $brands_i ) {
			$desired = ( $box3_i < $brands_i ) ? $brands_i - 1 : $brands_i;
			if ( $box3_i !== $desired ) {
				list( $box3, $items ) = storeplus_child_home_pull_index( $items, $box3_i );
				if ( $box3 ) {
					$brands_i = storeplus_child_home_find_type_index( $items, 'woocommerce_box_brands' );
					$at       = ( null === $brands_i ) ? count( $items ) : $brands_i;
					$items    = storeplus_child_home_insert_at( $items, $at, $box3 );
					$changed  = true;
				}
			}
		}
	}

	return array(
		'items'   => array_values( $items ),
		'changed' => $changed,
	);
}

/**
 * Drop demo «ترندها / Store Trends» grid block; keep category tiles under the hero.
 *
 * @param array $items sections_items.
 * @return array{items:array,changed:bool}
 */
function storeplus_child_home_apply_layout_fixes( array $items ) {
	$changed = false;
	$out     = array();

	foreach ( $items as $section ) {
		$type = isset( $section['data_value'] ) ? (string) $section['data_value'] : '';
		if ( 'woocommerce_grid_products' === $type ) {
			$changed = true;
			continue;
		}
		if ( 'woocommerce_categories' === $type && ! empty( $section['section_fields'] ) && is_array( $section['section_fields'] ) ) {
			$sf = $section['section_fields'];
			if ( ! empty( $sf['text_editor_heading'] ) && preg_match( '/Store Trends|Trends|ترند/u', (string) $sf['text_editor_heading'] ) ) {
				$sf['text_editor_heading'] = '';
				$section['section_fields']   = $sf;
				$changed                     = true;
			}
			if ( empty( $sf['all_checkbox'] ) ) {
				$sf['all_checkbox']        = 'on';
				$section['section_fields'] = $sf;
				$changed                   = true;
			}
			if ( empty( $sf['hide_empty_checkbox'] ) ) {
				$sf['hide_empty_checkbox'] = 'on';
				$section['section_fields'] = $sf;
				$changed                   = true;
			}
			if ( empty( $sf['col_count_radio'] ) ) {
				$sf['col_count_radio']     = 'col-8';
				$section['section_fields'] = $sf;
				$changed                   = true;
			}
			if ( empty( $sf['product_count_radio'] ) ) {
				$sf['product_count_radio'] = 'hide';
				$section['section_fields'] = $sf;
				$changed                   = true;
			}
		}
		$out[] = $section;
	}

	return array(
		'items'   => array_values( $out ),
		'changed' => $changed,
	);
}

/**
 * Whether PHP home section order is enabled.
 *
 * @return bool
 */
function storeplus_child_home_section_order_enabled() {
	return (bool) apply_filters( 'storeplus_child_home_section_order_enabled', true );
}

/**
 * True for the store front page (static front page or blog-as-front).
 * Uses page_on_front as a fallback when query flags are noisy (extra query args).
 *
 * @return bool
 */
function storeplus_child_is_store_front_page() {
	if ( is_front_page() ) {
		return true;
	}
	$front = (int) get_option( 'page_on_front' );
	return (bool) ( $front && did_action( 'wp' ) && is_page( $front ) );
}

/**
 * Front-page meta filter: reorder sections for render only.
 *
 * @param mixed  $value     Filter value.
 * @param int    $object_id Post ID.
 * @param string $meta_key  Meta key.
 * @param bool   $single    Single.
 * @return mixed
 */
function storeplus_child_home_section_order_meta( $value, $object_id, $meta_key, $single ) {
	static $busy = false;
	if ( $busy || ! storeplus_child_home_section_order_enabled() || is_admin() || wp_doing_ajax() || wp_doing_cron() ) {
		return $value;
	}
	if ( ! function_exists( 'irantheme_get_meta_data_sections_by_page_id' ) ) {
		return $value;
	}
	$sections_key = irantheme_get_meta_data_sections_by_page_id();
	if ( $meta_key !== $sections_key ) {
		return $value;
	}
	$front = (int) get_option( 'page_on_front' );
	if ( ! $front || (int) $object_id !== $front ) {
		return $value;
	}
	/*
	 * During early meta reads the main query may not be ready yet. Allow the
	 * front page ID itself; after `wp` also accept is_front_page / is_page.
	 */
	if ( did_action( 'wp' ) && ! storeplus_child_is_store_front_page() ) {
		return $value;
	}

	$busy = true;
	$raw  = get_post_meta( $object_id, $meta_key, true );
	$busy = false;
	if ( ! $raw ) {
		return $value;
	}

	$data = class_exists( '\Irantheme_Dev\Irantheme_JSON_Helper' )
		? \Irantheme_Dev\Irantheme_JSON_Helper::decode( $raw )
		: ( is_string( $raw ) ? json_decode( $raw, true ) : $raw );
	if ( class_exists( '\Irantheme_Dev\Irantheme_JSON_Helper' ) && is_array( $data ) ) {
		$data = \Irantheme_Dev\Irantheme_JSON_Helper::normalize_sections( $data );
	}
	if ( empty( $data['sections_items'] ) || ! is_array( $data['sections_items'] ) ) {
		return $value;
	}

	$result = storeplus_child_home_reorder_sections( $data['sections_items'] );
	$fixes  = storeplus_child_home_apply_layout_fixes( $result['items'] );
	/*
	 * Even when order is already correct, mark that PHP owns layout so JS
	 * safety nets can no-op without a second pass.
	 */
	$GLOBALS['storeplus_child_home_order_owned'] = true;

	if ( ! $result['changed'] && ! $fixes['changed'] ) {
		return $value;
	}

	$data['sections_items'] = $fixes['items'];

	$encoded = class_exists( '\Irantheme_Dev\Irantheme_JSON_Helper' ) && method_exists( '\Irantheme_Dev\Irantheme_JSON_Helper', 'encode' )
		? \Irantheme_Dev\Irantheme_JSON_Helper::encode( $data )
		: wp_json_encode( $data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );

	if ( ! is_string( $encoded ) || '' === $encoded ) {
		return $value;
	}

	return $single ? $encoded : array( $encoded );
}
add_filter( 'get_post_metadata', 'storeplus_child_home_section_order_meta', 20, 4 );

/**
 * Mark body so layout JS can no-op when PHP owns home order.
 *
 * @param string[] $classes Body classes.
 * @return string[]
 */
function storeplus_child_home_section_order_body_class( $classes ) {
	if ( ! storeplus_child_home_section_order_enabled() ) {
		return $classes;
	}
	$front = (int) get_option( 'page_on_front' );
	$on_front = storeplus_child_is_store_front_page()
		|| ( $front && is_page( $front ) )
		|| ( ! empty( $GLOBALS['storeplus_child_home_order_owned'] ) );
	if ( $on_front ) {
		$classes[] = 'sp-php-home-order';
	}
	return $classes;
}
add_filter( 'body_class', 'storeplus_child_home_section_order_body_class' );
