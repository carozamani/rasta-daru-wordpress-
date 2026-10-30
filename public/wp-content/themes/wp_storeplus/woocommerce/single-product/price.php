<?php

/**
 * Single Product Price
 */
if (!defined('ABSPATH')) {
	exit;
}

global $product;

$extra_class_price = $product->is_type('variable') ? 'product-variable' : 'not-product-variable';
$currency_pos = get_option('woocommerce_currency_pos');

if ($product->is_in_stock()) :
	$price_to_display = $product->get_price_html();
	if ($product->is_type('variable')) {
		$variation = null;
		$default_attributes = $product->get_default_attributes();
		$data_store = WC_Data_Store::load('product');

		$render_sale_price = function ($variation) use ($product) {
			return '<span class="price-offer-percentage">' . irantheme_get_sale_percentage($product) . '</span>'
				. '<del aria-hidden="true">' . wc_price($variation->get_regular_price()) . '</del>'
				. '<ins>' . wc_price($variation->get_sale_price()) . '</ins>';
		};

		$min_price = $product->get_variation_price('min');
		$max_price = $product->get_variation_price('max');

		$variations = $product->get_available_variations();
		$prices = array_map(fn($v) => $v['display_price'], $variations);
		$sales = array_map(fn($v) => $v['is_on_sale'], $variations);

		$uniform_price = count(array_unique($prices)) === 1;
		$uniform_sale  = count(array_unique($sales)) === 1;

		// بلوک اصلاح شده
		if ($uniform_price && $uniform_sale) {
			$variation_id = $data_store->find_matching_product_variation($product, $default_attributes);
			$variation = wc_get_product($variation_id);

			if ($variation && $variation->is_on_sale()) {
				// ⭐ اصلاح شده: از قیمت‌های متغیر به جای قیمت‌های محصول والد استفاده کنید.
				$regular_price = $variation->get_regular_price();
				$sale_price = $variation->get_sale_price();

				$price_to_display = '<span class="price-label">قیمت: </span><span class="price-offer-percentage">' . irantheme_get_sale_percentage($variation) . '</span>'
					. '<del aria-hidden="true">' . wc_price($regular_price) . '</del>'
					. '<ins>' . wc_price($sale_price) . '</ins>';
			} else {
				// ⭐ اگر تخفیف وجود نداشت، فقط قیمت عادی را نمایش بده.
				$price_to_display = '<span class="price-label">قیمت: </span>' . wc_price($min_price);
			}
		} elseif (empty($default_attributes)) {
			$price_to_display = '<span class="price-label">شروع قیمت از: </span>' . wc_price($min_price);
		} else {
			$variation_id = $data_store->find_matching_product_variation($product, $default_attributes);
			$variation = wc_get_product($variation_id);
			if ($variation) {
				$price_to_display = $variation->is_on_sale()
					? '<span class="price-label">قیمت: </span>' . $render_sale_price($variation)
					: '<span class="price-label">قیمت: </span>' . wc_price($variation->get_price());
			} else {
				$price_to_display = '<span class="price-label">شروع قیمت از: </span>' . wc_price($min_price);
			}
		}
	} elseif ($product->is_type('simple') && $product->is_on_sale()) {
		// Logic for Simple Products
		$sale_percentage = wp_kses_post(irantheme_get_sale_percentage($product));
		$price_to_display = '<span class="price-label">قیمت: </span><span class="price-offer-percentage">' . $sale_percentage . '</span>';
		$price_to_display .= '<del aria-hidden="true">' . wc_price($product->get_regular_price()) . '</del>';
		$price_to_display .= '<ins>' . wc_price($product->get_sale_price()) . '</ins>';
	} else {
		// ⭐ اضافه کردن price-label برای محصولات ساده بدون تخفیف
		$price_to_display = '<span class="price-label">قیمت: </span>' . $price_to_display;
	}
?>
	<p class="<?php echo esc_attr(apply_filters('woocommerce_product_price_class', 'price')); ?> irantheme-woocommerce-product-price <?php echo esc_attr($extra_class_price); ?> <?php echo esc_attr($currency_pos); ?>">
		<span class="price <?php echo $product->is_on_sale() ? 'offer-percentage' : ''; ?>"><?php echo $price_to_display; ?></span>
	</p>
<?php endif; ?>