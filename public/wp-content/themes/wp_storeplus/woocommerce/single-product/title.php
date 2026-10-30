<?php

/**
 * Single Product title
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/title.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://woo.com/document/template-structure/
 * @package    WooCommerce\Templates
 * @wc-version    1.6.4
 * @author	   M-MALEKZADEH77
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}
global $product;
// Get product attributes for find brand
$attributes = $product->get_attributes();

foreach ($attributes as $attr_name => $attr) {
	// Get taxonomy type value
	$taxonomy_type =
		irantheme_get_attribute_custom_option_value($attr['name']);
	$brand_value = '';
	$brand_img_ids = array();
	foreach ($attr->get_terms() as $term) {
		if ($taxonomy_type == 'brand') {
			// Get value of brand attr
			$brand_value = $term->name;
			$brand_img_id = get_option("_term_type_{$attr['name']}_{$term->term_id}");
			if ($brand_img_id != '') {
				array_push($brand_img_ids, $brand_img_id);
			}
		}
	}
}
if (is_product() && $brand_img_ids) : ?>
	<div class="irantheme-woocommerce-product-title-wrapper row">
	<?php
endif;
the_title('<h1 class="product_title entry-title irantheme-woocommerce-product-title ' . ($brand_img_ids ? "col-lg-7 col-md-7 col-sm-12" : "") . '">', '</h1>');
if (is_product() && $brand_img_ids) :
	?>
		<div class="irantheme-woocommerce-product-brand-logo-wrapper col-lg-5 col-md-5 col-sm-12">
			<?php
			foreach ($brand_img_ids as $id) :
				$image_src
					= wp_get_attachment_image_src($id, 'full')[0];
			?>
				<div class="irantheme-woocommerce-product-brand-logo">
					<img src="<?php echo esc_attr($image_src); ?>" srcset="<?php echo esc_attr(wp_get_attachment_image_srcset($id, 'full')); ?>" sizes="<?php echo wp_get_attachment_image_sizes($id, 'full') ?>" alt="<?php echo esc_attr($brand_value); ?>">
				</div>
			<?php
			endforeach;
			?>
		</div>
	</div>
<?php
endif;
