<?php

/**
 * Variable product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/variable.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @wc-version 9.6.0
 * @author	M-MALEKZADEH77
 */

use Irantheme_Dev\Irantheme_Color_Static;

defined('ABSPATH') || exit;

global $product;

// Get details color for reset btn hover mode
$details_color = get_option('irantheme_single_product_details_color');
$extra_color_class = '';
if ($details_color !== 'off' && is_product()) {
	$extra_color_class = Irantheme_Color_Static::get_class_color_mode('color-hover', $details_color);
}
$attribute_keys  = array_keys($attributes);
$variations_json = wp_json_encode($available_variations);
$variations_attr = function_exists('wc_esc_json') ? wc_esc_json($variations_json) : _wp_specialchars($variations_json, ENT_QUOTES, 'UTF-8', true);
// Get replace image option 
$replace_image = get_post_meta($product->get_id(), 'irantheme_custom_field_product_color_var_image_checkbox', true);
do_action('woocommerce_before_add_to_cart_form'); ?>

<form class="irantheme-variations-form irantheme-wc-single-add-to-cart-form variations_form cart" action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint($product->get_id()); ?>" data-product_variations="<?php echo $variations_attr; // WPCS: XSS ok. 
																																																																																					?>">
	<?php do_action('woocommerce_before_variations_form'); ?>

	<?php if (empty($available_variations) && false !== $available_variations) : ?>
		<p class="irantheme-out-of-stock stock out-of-stock"><?php echo esc_html(apply_filters('woocommerce_out_of_stock_message', __('This product is currently out of stock and unavailable.', 'woocommerce'))); ?></p>
	<?php else : ?>
		<table class="irantheme-variations variations" cellspacing="0" role="presentation">
			<tbody>
				<?php foreach ($attributes as $attribute_name => $options) : ?>
					<?php
					$_attr_id = wc_attribute_taxonomy_id_by_name($attribute_name);
					$_taxonomy_type = get_option("wc_attribute_irantheme_taxonomy_type-{$_attr_id}");
					?>
					<tr>
						<th class="irantheme-variation-label label"><label for="<?php echo esc_attr(sanitize_title($attribute_name)); ?>"><?php echo wc_attribute_label($attribute_name); ?></label>
							<?php
							if ($_taxonomy_type == 'color' && $replace_image == 'on'):
							?>
								<span class="irantheme-variation-label-switch-selected" data-empty-text="انتخاب نشده">انتخاب نشده</span>
							<?php
							endif;
							?>
						</th>
						<?php
						$extra_class = '';
						$custom_var_class = '';
						if ($_taxonomy_type == 'color') {
							$extra_class = ' irantheme-select-type-custom-var';
							$custom_var_class = 'irantheme-variation-custom-value';
						} elseif ($_taxonomy_type == 'size') {
							$extra_class = ' irantheme-select-type-custom-var';
							$custom_var_class = 'irantheme-variation-custom-value';
						}																												?>
						<td class="irantheme-variation-value value <?php echo esc_attr($custom_var_class); ?>">
							<?php
							wc_dropdown_variation_attribute_options(
								array(
									'options'   => $options,
									'attribute' => $attribute_name,
									'product'   => $product,
									'class'   => 'irantheme-variation-select' . $extra_class,
								)
							);
							echo end($attribute_keys) === $attribute_name ? wp_kses_post(apply_filters('woocommerce_reset_variations_link', '<a class="irantheme-reset-variation reset_variations ' . $extra_color_class . '" href="#">' . esc_html__('Clear', 'woocommerce') . '</a>')) : '';
							?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<?php do_action('woocommerce_after_variations_table'); ?>

		<div class="irantheme-single-variation-wrap single_variation_wrap <?php echo esc_attr($alignment_extra_class); ?>">
			<?php
			/**
			 * Hook: woocommerce_before_single_variation.
			 */
			do_action('woocommerce_before_single_variation');

			/**
			 * Hook: woocommerce_single_variation. Used to output the cart button and placeholder for variation data.
			 *
			 * @since 2.4.0
			 * @hooked woocommerce_single_variation - 10 Empty div for variation data.
			 * @hooked woocommerce_single_variation_add_to_cart_button - 20 Qty and cart button.
			 */
			do_action('woocommerce_single_variation');

			/**
			 * Hook: woocommerce_after_single_variation.
			 */
			do_action('woocommerce_after_single_variation');
			?>
		</div>
	<?php endif; ?>

	<?php do_action('woocommerce_after_variations_form'); ?>
</form>

<?php
do_action('woocommerce_after_add_to_cart_form');
