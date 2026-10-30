<?php

/**
 * The Template for displaying all single products
 * @see         https://woo.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @wc-version     1.6.4
 * @author      M-MALEKZADEH77
 */

use Irantheme_Dev\Irantheme_Color_Static;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
get_header('shop');

global $post, $product;

$col_size = get_option('irantheme_single_product_col_size');
$gallery_size_class = '';
if ($col_size == 'wide-gallery') {
	$gallery_size_class = 'col-lg-7';
} elseif ($col_size == 'wide-content') {
	$gallery_size_class = 'col-lg-5';
} elseif ($col_size == 'gallery-content-equal') {
	$gallery_size_class = 'col-lg-6';
}
$gallery_layout = get_option('irantheme_single_product_gallery_layout_radio');
$gallery_layout_class = '';
switch ($gallery_layout) {
	case 'thumb-grid':
		$gallery_layout_class = 'irantheme-product-gallery-thumb-grid';
		break;
	case 'thumb-slider':
		$gallery_layout_class = 'irantheme-product-gallery-thumb-slider';
		break;
	case 'thumb-slider-vertical':
		$gallery_layout_class = 'irantheme-product-gallery-thumb-slider-vertical';
		break;
	case 'grid':
		$gallery_layout_class = 'irantheme-product-gallery-grid';
		break;
	default:
		$gallery_layout_class = '';
		break;
}
$details_color = get_option('irantheme_single_product_details_color');
$details_color_rgb = Irantheme_Color_Static::hex_to_rgb(Irantheme_Color_Static::get_hex_color($details_color));
$details_color_class = '';
if ($details_color != 'off') {
	$details_color_class = esc_attr(Irantheme_Color_Static::get_class_color_mode('color', null, Irantheme_Color_Static::get_hex_color($details_color)));
}

$share_checkbox = get_option('irantheme_single_product_share_checkbox');
?>
<div class="irantheme-single-product">
	<div class="irantheme-container-inner">
		<?php while (have_posts()) : ?>
			<?php the_post(); ?>
			<?php
			$product_id = get_the_ID();
			$product = wc_get_product($product_id);
			$_product = wc_get_product($product_id);
			$attachment_ids = $_product->get_gallery_image_ids();
			$attachment_urls = array();
			$product_thumbnail_id = get_post_thumbnail_id($product_id);
			$product_thumbnail = wp_get_attachment_url($product_thumbnail_id);
			$product_status = $product->get_stock_status();
			if ($product_thumbnail) {
				array_push($attachment_urls, $product_thumbnail);
			}
			foreach ($attachment_ids as $value) {
				array_push($attachment_urls, wp_get_attachment_url($value));
			}
			if (count($attachment_urls) <= 1) {
				$gallery_layout = 'grid';
				$gallery_layout_class = 'irantheme-product-gallery-grid';
			}
			$item_index = 0;
			?>

			<div class="irantheme-product-breadcrumb">
				<?php woocommerce_breadcrumb(); ?>
			</div>

			<?php
			$is_downloadable_product = $product->is_downloadable() || irantheme_are_all_variations_downloadable($product);
			?>

			<?php if ($is_downloadable_product): ?>
				<?php
				// Load downloadable product file
				wc_get_template('/template-parts/woocommerce/single-product-downloadable.php', array(
					'product' => $product,
					'post' => $post,
					'product_id' => $product_id,
					'product_status' => $product_status,
					'attachment_ids' => $attachment_ids,
					'attachment_urls' => $attachment_urls,
					'product_thumbnail_id' => $product_thumbnail_id,
					'product_thumbnail' => $product_thumbnail,
					'item_index' => $item_index,
					'gallery_layout_class' => $gallery_layout_class,
					'gallery_layout' => $gallery_layout,
					'details_color' => $details_color,
					'share_checkbox' => $share_checkbox,
				));
				?>
			<?php else: ?>
				<?php
				// Load physical product file
				wc_get_template('/template-parts/woocommerce/single-product-physical.php', array(
					'product' => $product,
					'post' => $post,
					'product_id' => $product_id,
					'product_status' => $product_status,
					'attachment_ids' => $attachment_ids,
					'attachment_urls' => $attachment_urls,
					'product_thumbnail_id' => $product_thumbnail_id,
					'product_thumbnail' => $product_thumbnail,
					'item_index' => $item_index,
					'gallery_layout_class' => $gallery_layout_class,
					'gallery_layout' => $gallery_layout,
					'details_color' => $details_color,
					'share_checkbox' => $share_checkbox,
				));
				?>
			<?php endif; ?>
			<?php
			// Get template part of single product navbar mobile
			get_template_part('/template-parts/woocommerce/util-single-product-navbar-mobile');
			?>
		<?php endwhile; // end of the loop. 
		?>
	</div>
</div>
<?php
Irantheme_Footer_Generator::display_footer();
get_footer('shop');
?>