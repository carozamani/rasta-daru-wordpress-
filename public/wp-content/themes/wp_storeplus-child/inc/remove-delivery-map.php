<?php
/**
 * Remove checkout "موقعیت دقیق تحویل روی نقشه" (delivery map picker).
 *
 * The parent theme wires it when option irantheme_wc_shopping_cart_delivery_map_checkbox = on.
 * We force that option off, unhook every Delivery_Map callback, and hide leftover markup.
 * Parent theme files are not modified.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const SP_DELIVERY_MAP_OPTION = 'irantheme_wc_shopping_cart_delivery_map_checkbox';

/** Never treat the feature as enabled, even if admin still has the checkbox on. */
add_filter( 'pre_option_' . SP_DELIVERY_MAP_OPTION, static function () {
	return 'off';
}, 10 );

/** Persist off so parent construct skips hooks on later boots. */
add_action( 'after_setup_theme', static function () {
	if ( 'on' === get_option( SP_DELIVERY_MAP_OPTION, '' ) ) {
		update_option( SP_DELIVERY_MAP_OPTION, 'off', true );
	}
}, 5 );

/**
 * Unhook all Irantheme_Woocommerce_Delivery_Map callbacks (instance methods need the live object).
 */
function storeplus_child_unhook_delivery_map() {
	if ( ! class_exists( '\Irantheme_Dev\Irantheme_Woocommerce_Delivery_Map', false ) ) {
		return;
	}

	$tags = array(
		'woocommerce_init',
		'woocommerce_set_additional_field_value',
		'woocommerce_after_order_notes',
		'woocommerce_checkout_update_order_review',
		'woocommerce_checkout_process',
		'woocommerce_checkout_create_order',
		'woocommerce_checkout_update_order_meta',
		'woocommerce_order_details_after_order_table',
		'woocommerce_email_order_details',
		'woocommerce_admin_order_data_after_shipping_address',
		'manage_edit-shop_order_columns',
		'manage_shop_order_posts_custom_column',
		'woocommerce_shop_order_search_fields',
		'wp_enqueue_scripts',
		'wp_footer',
	);

	foreach ( $tags as $tag ) {
		if ( empty( $GLOBALS['wp_filter'][ $tag ] ) ) {
			continue;
		}
		$hook = $GLOBALS['wp_filter'][ $tag ];
		if ( ! isset( $hook->callbacks ) || ! is_array( $hook->callbacks ) ) {
			continue;
		}
		foreach ( $hook->callbacks as $priority => $callbacks ) {
			foreach ( $callbacks as $cb ) {
				$fn = $cb['function'];
				if ( ! is_array( $fn ) || ! isset( $fn[0] ) || ! is_object( $fn[0] ) ) {
					continue;
				}
				if ( $fn[0] instanceof \Irantheme_Dev\Irantheme_Woocommerce_Delivery_Map ) {
					remove_action( $tag, $fn, (int) $priority );
					remove_filter( $tag, $fn, (int) $priority );
				}
			}
		}
	}
}
add_action( 'init', 'storeplus_child_unhook_delivery_map', 1 );
add_action( 'wp_loaded', 'storeplus_child_unhook_delivery_map', 1 );
add_action( 'woocommerce_init', 'storeplus_child_unhook_delivery_map', 999 );

/** Hide any leftover classic / block checkout UI. */
add_action(
	'wp_head',
	static function () {
		if ( ! function_exists( 'is_checkout' ) || ! is_checkout() ) {
			return;
		}
		echo '<style id="sp-hide-delivery-map">'
			. '#irantheme_delivery_map_field,'
			. '#irantheme_delivery_map,'
			. '#irantheme_delivery_map_block,'
			. '.irantheme-checkout-box--map,'
			. '.irantheme-map-search,'
			. '.irantheme-map-actions,'
			. '.sp-map-address,'
			. '.wc-block-components-checkout-additional-information-block [id*="delivery-latitude"],'
			. '.wc-block-components-checkout-additional-information-block [id*="delivery-longitude"],'
			. '.wc-block-components-checkout-additional-information-block [id*="delivery-map-address"],'
			. '.wc-block-components-address-form [id*="irantheme/delivery-latitude"],'
			. '.wc-block-components-address-form [id*="irantheme/delivery-longitude"],'
			. '.wc-block-components-address-form [id*="irantheme/delivery-map-address"],'
			. 'div[id*="irantheme-delivery-map"],'
			. 'div[id*="irantheme\\/delivery-latitude"],'
			. 'div[id*="irantheme\\/delivery-longitude"],'
			. 'div[id*="irantheme\\/delivery-map-address"]{'
			. 'display:none!important;height:0!important;overflow:hidden!important;margin:0!important;padding:0!important;border:0!important}'
			. '</style>' . "\n";
	},
	20
);
