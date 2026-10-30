<?php
/**
 * Wishlist removal.
 *
 * Takes the "favorites" tab out of My Account and blocks the endpoint and the AJAX
 * actions that save wishlist data. The buttons themselves are hidden in
 * assets/css/remove-wishlist.css. The parent theme is not modified.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// My Account menu: drop the "علاقه مندی ها" tab.
add_filter(
	'woocommerce_account_menu_items',
	function ( $items ) {
		unset( $items['favorites'] );
		return $items;
	},
	99
);

// My Account: the /favorites/ endpoint renders nothing.
add_action(
	'wp',
	function () {
		remove_action( 'woocommerce_account_favorites_endpoint', 'irantheme_favorites_content' );
	},
	99
);

// Wishlist save actions (admin-post) are unhooked.
add_action(
	'init',
	function () {
		remove_action( 'admin_post_nopriv_user_wishlist_update', 'update_wishlist_ajax' );
		remove_action( 'admin_post_user_wishlist_update', 'update_wishlist_ajax' );
	},
	99
);
