<?php

/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://woo.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @wc-version  9.6.0
 * @author 		M-MALEKZADEH77
 */

use Irantheme_Dev\Irantheme_Section_Service;
use Irantheme_Dev\Irantheme_Section_Utils;
use Irantheme_Dev\Irantheme_Section_Woocommerce;

if (!defined('ABSPATH')) {
	exit;
}

// 💡 1. دریافت متغیر گلوبال $product و تنظیمات کلی
global $product;

// اگر شیء محصول وجود ندارد، خارج شو.
if (! $product) {
	return;
}

// Needed variable
// Count columns of products --------------------------------------------------------------
$col_count_products = get_option('irantheme_up_sells_products_col_count_radio');
$slider_mode_column = Irantheme_Section_Service::get_bootstrap_class($col_count_products);
// Needed Variable
// Details color --------------------------------------------------------------
$details_color_select = get_option('irantheme_up_sells_products_details_color_select');
$details_color_select2 = get_option('irantheme_up_sells_products_details_color_select2');

// Get product features --------------------------------------------------------------
$product_features = get_option('irantheme_up_sells_products_features_checkbox_multi');
$product_features = explode(',', $product_features);
// Count columns of products --------------------------------------------------------------
$col_count_products = get_option('irantheme_up_sells_products_col_count_radio');
// Get product content alignment --------------------------------------------------------------
$post_content_align = get_option('irantheme_up_sells_products_align_content_radio');
// Get product count of word excerpt --------------------------------------------------------------
$product_excerpt_count = get_option('irantheme_up_sells_products_excerpt_count_number');

// Get product count of word excerpt --------------------------------------------------------------
$product_hover_mode = get_option('irantheme_up_sells_products_hover_mode_radio');

$product_hover_mode_class = '';
if ($product_hover_mode == 'shadow') {
	$product_hover_mode_class = 'irantheme-woocommerce-products-hover-shadow';
} elseif ($product_hover_mode == 'scale') {
	$product_hover_mode_class = 'irantheme-woocommerce-products-hover-scale';
}

// Get image switchable thumbnail with gallery image --------------------------------------------------------------
$product_image_hover_changer = get_option('irantheme_up_sells_products_image_changer_checkbox');

$product_image_hover_changer_class = '';
if ($product_image_hover_changer == 'on') {
	$product_image_hover_changer_class = 'irantheme-woocommerce-products-item-image-changer';
}

// Get product options checkbox --------------------------------------------------------------
$product_border_checkbox = get_option('irantheme_up_sells_products_border_checkbox');

$product_border_class = '';
if ($product_border_checkbox == 'on') {
	$product_border_class = 'irantheme-woocommerce-products-item-border';
}

// Get border radius --------------------------------------------------------------
$border_radius_range = get_option('irantheme_up_sells_products_border_radius_range', 0.5);

// Get product discount schedule checkbox --------------------------------------------------------------
$product_discount_schedule_checkbox = get_option('irantheme_up_sells_products_discount_schedule_checkbox');

// Get product discount checkbox --------------------------------------------------------------
$product_discount_checkbox = get_option('irantheme_up_sells_products_discount_checkbox');

// Get product total sales checkbox --------------------------------------------------------------
$product_total_sales_checkbox = get_option('irantheme_up_sells_products_total_sales_checkbox');

// Get product color attribute checkbox --------------------------------------------------------------
$product_color_attribute_checkbox = get_option('irantheme_up_sells_products_color_attribute_checkbox');

// Get product options checkbox --------------------------------------------------------------
$product_shadow_checkbox = get_option('irantheme_up_sells_products_shadow_checkbox');

$product_shadow_class = '';
if ($product_shadow_checkbox == 'on') {
	$product_shadow_class = 'irantheme-woocommerce-products-item-shadow';
}

// Get product options checkbox --------------------------------------------------------------
$product_padding_checkbox = get_option('irantheme_up_sells_products_padding_status_checkbox');

$product_padding_class = '';
if ($product_padding_checkbox == 'on') {
	$product_padding_class = 'irantheme-woocommerce-products-item-padding';
}

// Get product options checkbox --------------------------------------------------------------
$product_transparent_checkbox = get_option('irantheme_up_sells_products_transparent_checkbox');

$product_transparent_class = '';
if ($product_transparent_checkbox == 'on') {
	$product_transparent_class = 'irantheme-woocommerce-products-item-transparent';
}

// Get product options checkbox --------------------------------------------------------------
$product_extra_options_checkbox = get_option('irantheme_up_sells_products_extra_options_checkbox');
$product_extra_options_style_radio = get_option('irantheme_up_sells_products_extra_option_on_image_style_radio');
$extra_options_hover_checkbox = get_option('irantheme_up_sells_products_extra_options_hover_checkbox');
$product_image_options_class = '';
$product_image_extra_options_style_class = '';
if ($product_extra_options_checkbox == 'on') {
	$product_image_options_class = 'active';
	switch ($product_extra_options_style_radio) {
		case 'vertical-left':
			$product_image_extra_options_style_class = 'irantheme-extra-option-vertical-left';
			break;
		case 'horizontal-center':
			$product_image_extra_options_style_class = 'irantheme-extra-option-horizontal-center';
			break;
		case 'horizontal-bottom':
			$product_image_extra_options_style_class = 'irantheme-extra-option-horizontal-bottom';
			break;
	}
	if ($extra_options_hover_checkbox == 'on') {
		$product_image_extra_options_style_class .= ' hover-active';
	}
}

// Get product image hover mode --------------------------------------------------------------
$product_image_hover_mode = get_option('irantheme_up_sells_products_image_hover_mode_radio');

$product_image_hover_mode_class = '';
if ($product_image_hover_mode == 'flashing') {
	$product_image_hover_mode_class = 'irantheme-image-hover-flashing';
} elseif ($product_image_hover_mode == 'shine') {
	$product_image_hover_mode_class = 'irantheme-image-hover-shine';
} elseif ($product_image_hover_mode == 'circle') {
	$product_image_hover_mode_class = 'irantheme-image-hover-circle';
}

// Set slider mode --------------------------------------------------------------
$slider_mode_checkbox = get_option('irantheme_up_sells_products_slider_checkbox');
// Init wrapper and row and item with default classes
$slider_mode_column = 'swiper-slide';

// Get related products manually
$args = apply_filters('woocommerce_product_upsells', array(
	'posts_per_page' => -1,
	'orderby'        => 'rand',
));

$upsell_product_ids = $product->get_upsell_ids();
$upsell_products_objects = array_map('wc_get_product', $upsell_product_ids);
$upsell_products_objects = array_filter($upsell_products_objects);

if ($upsell_products_objects) :
?>

	<section class="irantheme-woocommerce-upsells-related irantheme-woocommerce-products-section  related products">
		<div class="irantheme-woocommerce-upsells-related-wrapper irantheme-woocommerce-products white">
			<?php
			$heading = apply_filters('woocommerce_product_upsells_products_heading', __('محصولات پیشنهادی'));
			?>
			<div class="irantheme-woocommerce-upsells-related-heading-wrapper">
				<?php
				if ($heading) :
				?>
					<h2 class="irantheme-woocommerce-upsells-related-heading"><?php echo esc_html($heading); ?></h2>
				<?php endif; ?>
			</div>
			<div class="irantheme-woocommerce-product-swiper swiper irantheme-woocommerce-products-post-swiper irantheme-woocommerce-upsells-related-product" <?php echo (Irantheme_Section_Service::get_slider_data_column($col_count_products)); ?>>
				<div class="irantheme-woocommerce-slider-arrow-btn">
					<?php
					// Slider arrow option
					Irantheme_Section_Utils::get_slider_arrow_pagination('on', 'on', 'on', 'white', 1, 'bottom', 'center');
					?>
				</div>
				<div class="swiper-wrapper">
					<?php foreach ($upsells as $upsell) : ?>
						<?php
						// 🌟 تنظیم POST DATA برای توابعی که ممکن است در داخل تابع شما استفاده شوند (مثل the_permalink)
						$post_object = get_post($upsell->get_id());
						setup_postdata($GLOBALS['post'] = $post_object);
						// Get product item
						Irantheme_Section_Woocommerce::get_product_item($upsell, $product_features, 'white', $details_color_select, $details_color_select2, $product_discount_checkbox, $product_total_sales_checkbox, $product_discount_schedule_checkbox, $product_color_attribute_checkbox, $product_image_options_class, $product_image_extra_options_style_class, $product_border_class, $product_image_hover_changer_class, $product_image_hover_mode_class, $product_hover_mode_class, $post_content_align, $product_shadow_class, $slider_mode_column, $product_excerpt_count, $product_extra_options_checkbox, '', '', '', $border_radius_range, $product_padding_class, $product_transparent_class);
						?>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</section>
<?php
endif;

wp_reset_postdata();
