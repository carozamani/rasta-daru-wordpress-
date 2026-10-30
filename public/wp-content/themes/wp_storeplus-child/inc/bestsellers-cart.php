<?php
/**
 * Best sellers / mini-products: real add-to-cart control on each card.
 *
 * Printed during section render via storeplus_child_mini_cart_button( $product )
 * (hooked from the parent mini-products template). No full-page output buffer
 * and no url_to_postid() per card.
 *
 *  - simple, purchasable, in-stock -> AJAX add to cart (assets/js/bestsellers-cart.js)
 *  - variable / out of stock / other -> opens the product page
 *
 * Styles: home-rails → bestsellers-panel-lite (full bestsellers-panel.css is legacy/unused).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Brand name for a product (Woo brands taxonomy, then pa_brand attribute).
 *
 * @param WC_Product|mixed $product Product.
 * @return string
 */
function storeplus_child_mini_brand_name( $product ) {
	if ( ! $product instanceof WC_Product ) {
		return '';
	}
	$id = (int) $product->get_id();
	if ( ! $id ) {
		return '';
	}
	foreach ( array( 'product_brand', 'pa_brand' ) as $tax ) {
		if ( ! taxonomy_exists( $tax ) ) {
			continue;
		}
		$terms = get_the_terms( $id, $tax );
		if ( is_array( $terms ) && ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			return (string) $terms[0]->name;
		}
	}
	return '';
}

/**
 * Echo brand badge on the mini-card photo (bottom-right).
 * Discount uses the shared .irantheme-woocommerce-products-discount from the price
 * row; discount-badge-top.js moves it onto the image (same component as other cards).
 *
 * @param WC_Product|mixed $product Product.
 */
function storeplus_child_mini_brand_badge( $product ) {
	$name = storeplus_child_mini_brand_name( $product );
	if ( '' === $name ) {
		return;
	}
	printf(
		'<span class="irantheme-woocommerce-products-brand-badge">%s</span>',
		esc_html( $name )
	);
}

/**
 * Echo the mini-card cart control for one product.
 *
 * @param WC_Product|mixed $product Product instance from the mini-products loop.
 */
function storeplus_child_mini_cart_button( $product ) {
	if ( ! $product instanceof WC_Product ) {
		return;
	}

	$product_id = (int) $product->get_id();
	if ( ! $product_id ) {
		return;
	}

	$cart_svg  = '<svg class="sp-mini-cart__cart" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>';
	$check_svg = '<svg class="sp-mini-cart__check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>';
	$spinner   = '<span class="sp-mini-cart__spin" aria-hidden="true"></span>';

	if ( $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock() ) {
		printf(
			'<a href="%s" class="sp-mini-cart sp-mini-cart--ajax" data-product_id="%d" rel="nofollow" aria-label="%s" title="%s">%s%s%s</a>',
			esc_url( $product->add_to_cart_url() ),
			$product_id,
			esc_attr( 'افزودن به سبد خرید' ),
			esc_attr( 'افزودن به سبد خرید' ),
			$cart_svg,
			$check_svg,
			$spinner
		);
		return;
	}

	printf(
		'<a href="%s" class="sp-mini-cart sp-mini-cart--link" aria-label="%s" title="%s">%s</a>',
		esc_url( $product->get_permalink() ),
		esc_attr( 'مشاهده محصول' ),
		esc_attr( 'مشاهده محصول' ),
		$cart_svg
	);
}

/**
 * Cart AJAX script: home (bestsellers) + product pages (discovery rails).
 */
function storeplus_child_bs_cart_enqueue() {
	$need = is_front_page()
		|| ( function_exists( 'is_product' ) && is_product() );
	if ( ! $need ) {
		return;
	}

	$js = STOREPLUS_CHILD_DIR . '/assets/js/bestsellers-cart.js';
	if ( ! file_exists( $js ) ) {
		return;
	}

	wp_enqueue_script(
		'storeplus-child-bestsellers-cart',
		STOREPLUS_CHILD_URI . '/assets/js/bestsellers-cart.js',
		array(),
		filemtime( $js ),
		true
	);
	wp_add_inline_script(
		'storeplus-child-bestsellers-cart',
		'window.spMiniCart=' . wp_json_encode(
			array(
				'ajax' => add_query_arg( 'wc-ajax', 'add_to_cart', home_url( '/' ) ),
			)
		) . ';',
		'before'
	);
}
add_action( 'wp_enqueue_scripts', 'storeplus_child_bs_cart_enqueue', 25 );
