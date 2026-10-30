<?php
/**
 * Keep WooCommerce Brands (taxonomy product_brand) switched ON.
 *
 * The parent theme turns the feature off on every request
 * (wp_storeplus/inc/core/global-woocommerce.php: update_option( 'wc_feature_woocommerce_brands_enabled', 'no' )).
 * With the feature off the taxonomy does not exist, so everything built on it stays empty or missing:
 *   - the home page "برند های محبوب" carousel (cached inline data from inc/product-brands.php + brands-swipe.js)
 *   - the shop sidebar brand filter and brand archive pages (inc/brand-filter.php)
 *   - Products > Brands with logos and categories (inc/brand-categories.php)
 *
 * This filter makes the parent's write store "yes" instead, so the parent theme itself is not edited.
 * Brands are managed in Products > Brands (name, logo, categories) and assigned in each product's edit page.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function storeplus_child_keep_brands_enabled() {
	return 'yes';
}
add_filter( 'pre_update_option_wc_feature_woocommerce_brands_enabled', 'storeplus_child_keep_brands_enabled' );
