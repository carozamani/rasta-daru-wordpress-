<?php
/**
 * Home «شگفت‌انگیز»: keep the page-builder offers section visible with real products.
 *
 * The builder often saves type=custom with an empty product list + skeleton AJAX.
 * When AJAX fails (or hide-empty runs first), the whole box disappears.
 * We normalize fields on the front page so SSR cards render (same idea as PDP).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Patch homepage page-builder JSON so offers always has a product query.
 *
 * @param mixed  $value     Current filter value (null = fall through to DB).
 * @param int    $object_id Post ID.
 * @param string $meta_key  Meta key.
 * @param bool   $single    Single flag.
 * @return mixed
 */
function storeplus_child_home_offers_fix_meta( $value, $object_id, $meta_key, $single ) {
	static $busy = false;
	if ( $busy || is_admin() || wp_doing_ajax() || wp_doing_cron() ) {
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
	/* Only when rendering the front page (meta is also read in admin/builder). */
	if ( ! is_front_page() && ! ( did_action( 'wp' ) && is_page( $front ) ) ) {
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

	$changed = false;
	foreach ( $data['sections_items'] as &$section ) {
		if ( empty( $section['data_value'] ) || 'woocommerce_offers_products' !== $section['data_value'] ) {
			continue;
		}
		if ( empty( $section['section_fields'] ) || ! is_array( $section['section_fields'] ) ) {
			continue;
		}
		$fields = &$section['section_fields'];
		if ( function_exists( 'storeplus_child_pdp_prepare_home_section_fields' ) ) {
			storeplus_child_pdp_prepare_home_section_fields( 'woocommerce_offers_products', $fields );
			/*
			 * prepare() turns data_load on so PDP skips the skeleton shell.
			 * On the front page we want the opposite: SSR cards + skeleton until
			 * removeSkeleton() marks the section loaded.
			 */
			$fields['data_load_checkbox']         = 'off';
			$fields['heading_data_load_checkbox'] = 'off';
			$changed = true;
		}
		unset( $fields );
	}
	unset( $section );

	if ( ! $changed ) {
		return $value;
	}

	$encoded = class_exists( '\Irantheme_Dev\Irantheme_JSON_Helper' ) && method_exists( '\Irantheme_Dev\Irantheme_JSON_Helper', 'encode' )
		? \Irantheme_Dev\Irantheme_JSON_Helper::encode( $data )
		: wp_json_encode( $data );

	return $single ? $encoded : array( $encoded );
}
add_filter( 'get_post_metadata', 'storeplus_child_home_offers_fix_meta', 10, 4 );
