<?php

/**
 * Single variation cart button
 *
 * @see https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @wc-version 7.0.1
 * @author	M-MALEKZADEH77
 */

use Irantheme_Dev\Irantheme_Color_Static;

defined('ABSPATH') || exit;

global $product;
$btn_color_customization_checkbox = get_option('irantheme_single_product_add_to_card_btn_color_customization_checkbox');
$bg_color = get_option('irantheme_single_product_add_to_card_btn_bg_color');
$for_color = get_option('irantheme_single_product_add_to_card_btn_color');
$btn_bg_color_class = '';
$btn_color_class = '';
$btn_hover_color_class = '';
$btn_border_color_class = '';
if ($btn_color_customization_checkbox == 'on') {
	if ($bg_color != 'off') {
		$btn_bg_color_class = esc_attr(Irantheme_Color_Static::get_class_color_mode('bg', null, Irantheme_Color_Static::get_hex_color($bg_color)));
		$btn_hover_color_class = esc_attr(Irantheme_Color_Static::get_class_color_mode('color-hover', null, Irantheme_Color_Static::get_hex_color($bg_color)));
		$btn_border_color_class = esc_attr(Irantheme_Color_Static::get_class_color_mode('border', null, Irantheme_Color_Static::get_hex_color($bg_color)));
	}
	if ($for_color == 'off') {
		$btn_color_class = Irantheme_Color_Static::get_optimal_text_color(Irantheme_Color_Static::get_hex_color($bg_color));
	} else {
		$btn_color_class = 'text-color-' . $for_color;
	}
}
?>
<div class="irantheme-woocommerce-variation-add-to-cart irantheme-wc-single-add-to-cart-wrapper woocommerce-variation-add-to-cart variations_button">
	<?php do_action('woocommerce_before_add_to_cart_button'); ?>

	<?php
	do_action('woocommerce_before_add_to_cart_quantity');

	woocommerce_quantity_input(
		array(
			'min_value'   => apply_filters('woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product),
			'max_value'   => apply_filters('woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product),
			'input_value' => isset($_POST['quantity']) ? wc_stock_amount(wp_unslash($_POST['quantity'])) : $product->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
		)
	);

	do_action('woocommerce_after_add_to_cart_quantity');
	?>

	<button type="submit" class="irantheme-single-add-to-cart-button single_add_to_cart_button button alt<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?> <?php echo $btn_color_class; ?> <?php echo $btn_bg_color_class; ?> <?php echo $btn_hover_color_class; ?> <?php echo $btn_border_color_class; ?>"><span><?php echo esc_html($product->single_add_to_cart_text()); ?></span><i class="irantheme-animate-rotate"></i></button>

	<?php
	do_action('woocommerce_after_add_to_cart_button');
	?>

	<input type="hidden" name="add-to-cart" value="<?php echo absint($product->get_id()); ?>" />
	<input type="hidden" name="product_id" value="<?php echo absint($product->get_id()); ?>" />
	<input type="hidden" name="variation_id" class="variation_id" value="0" />
</div>