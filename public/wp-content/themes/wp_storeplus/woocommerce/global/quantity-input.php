<?php

/**
 * Product quantity inputs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/quantity-input.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @wc-version 9.4.0
 * @author	M-MALEKZADEH77
 *
 * @var bool   $readonly If the input should be set to readonly mode.
 * @var string $type     The input type attribute.
 */

use Irantheme_Dev\Irantheme_Color_Static;

defined('ABSPATH') || exit;

/* translators: %s: Quantity. */
$label = !empty($args['product_name']) ? sprintf(esc_html__('%s quantity', 'woocommerce'), wp_strip_all_tags($args['product_name'])) : esc_html__('Quantity', 'woocommerce');

// Get details color for btn hover mode
$details_color = get_option('irantheme_single_product_details_color');
$extra_color_class = '';
if ($details_color !== 'off') {
	$extra_color_class = Irantheme_Color_Static::get_class_color_mode('color-hover', $details_color);
}

?>
<div class="irantheme-single-product-quantity quantity">
	<?php
	/**
	 * Hook to output something before the quantity input field.
	 *
	 * @since 7.2.0
	 */
	do_action('woocommerce_before_quantity_input_field');
	?>
	<label class="screen-reader-text" for="<?php echo esc_attr($input_id); ?>"><?php echo esc_attr($label); ?></label>
	<button class="irantheme-quantity-btn irantheme-quantity-selector-minus <?php echo esc_attr($extra_color_class); ?>"><i class="fa-light fa-minus"></i></button>
	<input
		type="text"
		<?php echo $readonly ? 'readonly="readonly"' : ''; ?>
		id="<?php echo esc_attr($input_id); ?>"
		class="irantheme-input-text-quantity <?php echo esc_attr(join(' ', (array) $classes)); ?>"
		name="<?php echo esc_attr($input_name); ?>"
		value="<?php echo esc_attr($input_value); ?>"
		aria-label="<?php esc_attr_e('Product quantity', 'woocommerce'); ?>"
		size="4" min="<?php echo esc_attr($min_value); ?>"
		max="<?php echo esc_attr(0 < $max_value ? $max_value : ''); ?>"
		<?php if (!$readonly) : ?>
		step="<?php echo esc_attr($step); ?>"
		placeholder="<?php echo esc_attr($placeholder); ?>"
		inputmode="<?php echo esc_attr($inputmode); ?>"
		autocomplete="<?php echo esc_attr(isset($autocomplete) ? $autocomplete : 'on'); ?>"
		<?php endif; ?> />
	<button class="irantheme-quantity-btn irantheme-quantity-selector-plus <?php echo esc_attr($extra_color_class); ?>"><i class="fa-light fa-plus"></i></button>
	<?php
	/**
	 * Hook to output something after quantity input field
	 *
	 * @since 3.6.0
	 */
	do_action('woocommerce_after_quantity_input_field');
	?>
</div>
<?php
