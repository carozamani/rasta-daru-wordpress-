<?php

/**
 * External product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/external.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @wc-version 7.0.1
 * @author	M-MALEKZADEH77
 */

use Irantheme_Dev\Irantheme_Color_Static;

defined('ABSPATH') || exit;

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

do_action('woocommerce_before_add_to_cart_form'); ?>

<form class="irantheme-external-form cart irantheme-wc-single-add-to-cart-form irantheme-add-to-cart-ajax-disabled" action="<?php echo esc_url($product_url); ?>" method="get">
	<div class="irantheme-woocommerce-external-add-to-cart irantheme-wc-single-add-to-cart-wrapper">
		<?php do_action('woocommerce_before_add_to_cart_button'); ?>

		<button type="submit" class="irantheme-single-add-to-cart-button single_add_to_cart_button button alt irantheme-single-add-to-cart-button-external <?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?>  <?php echo $btn_color_class; ?> <?php echo $btn_bg_color_class; ?> <?php echo $btn_hover_color_class; ?> <?php echo $btn_border_color_class; ?>"><span><?php echo esc_html($button_text); ?></span><i class="irantheme-animate-rotate"></i></button>

		<?php wc_query_string_form_fields($product_url); ?>

		<?php do_action('woocommerce_after_add_to_cart_button'); ?>

		<a href="<?php echo esc_url($product_url); ?>" class="irantheme-single-add-to-cart-secoundary-button-external d-none"><i class="fa-light fa-hand-pointer"></i>درصورت باز نشدن صفحه بصورت خودکار، اینجا کلیک کنید.</a>
	</div>
</form>

<?php do_action('woocommerce_after_add_to_cart_form'); ?>