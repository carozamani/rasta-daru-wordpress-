<?php
/**
 * Removes the "رنگ" (colour) box from the shop filter sidebar.
 *
 * The parent theme prints one filter box per WooCommerce attribute that has the option
 * "add to filter" turned on (option: wc_attribute_irantheme_taxonomy_add_to_filter-{attribute id}).
 * Here that option is forced to "off" on the front end for every attribute of type "color",
 * so the box (and its colour dots) is not printed - on the shop page and in the mobile filter drawer.
 *
 * Nothing is deleted: the attribute, its terms and the colour swatches on product pages stay as they are.
 * The admin screens are not touched (the checkbox in Products > Attributes keeps its saved value).
 * To bring the box back, remove 'remove-color-filter' from the 'inc' list in functions.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function storeplus_child_disable_color_filter() {
	if ( is_admin() || ! function_exists( 'wc_get_attribute_taxonomies' ) ) {
		return;
	}

	foreach ( wc_get_attribute_taxonomies() as $attr ) {
		$id = (int) $attr->attribute_id;
		if ( 'color' !== get_option( "wc_attribute_irantheme_taxonomy_type-{$id}" ) ) {
			continue;
		}
		add_filter(
			"pre_option_wc_attribute_irantheme_taxonomy_add_to_filter-{$id}",
			'storeplus_child_color_filter_off'
		);
	}
}
add_action( 'wp', 'storeplus_child_disable_color_filter' );

function storeplus_child_color_filter_off() {
	return 'off';
}


/**
 * Removes the "محدوده قیمت" (price range) box from the shop filter sidebar.
 *
 * The parent theme prints the box only when 'price' is part of the option
 * irantheme_wc_archive_filter_widget_checkbox_multi (comma separated list of default filters).
 * On the front end 'price' is stripped from that list, so the box (inputs + slider) is not printed,
 * on the shop page and in the mobile filter drawer. The saved admin option is not touched.
 * To bring it back, delete this block.
 */
function storeplus_child_strip_price_filter( $value ) {
	if ( is_admin() || ! is_string( $value ) ) {
		return $value;
	}
	$items = array_filter( array_map( 'trim', explode( ',', $value ) ), 'strlen' );
	$items = array_diff( $items, array( 'price' ) );
	return implode( ',', $items );
}
add_filter( 'option_irantheme_wc_archive_filter_widget_checkbox_multi', 'storeplus_child_strip_price_filter' );
