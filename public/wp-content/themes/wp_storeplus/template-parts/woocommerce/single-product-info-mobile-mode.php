<?php

global $product;
$product_id = get_the_ID();
$product = wc_get_product($product_id);
$_product = wc_get_product($product_id);
$attachment_ids = $_product->get_gallery_image_ids();
$attachment_urls = array();
$product_thumbnail_id = get_post_thumbnail_id($product_id);
$product_thumbnail = wp_get_attachment_url($product_thumbnail_id);
$product_status = $product->get_stock_status();
$full_width_class = '';
$auto_hide_class = '';
$base_color_class = '';

?>
<div class="irantheme-single-product-shopping-summary-wrapper shopping-summary-mobile-mode">
	<?php
	$variable_selected_checkbox = get_option('irantheme_single_product_variable_selected_checkbox', 'on');
	$seller_name_checkbox = get_option('irantheme_single_product_seller_name_checkbox');
	$seller_name_text = get_option('irantheme_single_product_seller_name');
	$shipping_time_checkbox = get_option('irantheme_single_product_shipping_time_checkbox', 'on');
	$shipping_time_text = get_post_meta($product_id, 'irantheme_custom_field_shipping_time', true);
	$is_call_for_price = get_post_meta($product->get_id(), 'irantheme_product_meta_is_call_for_price', true);
	if ($product->is_in_stock() && $product->get_price() != '' && $is_call_for_price != 'yes'):
		if ($variable_selected_checkbox != 'off' && (($seller_name_checkbox != 'off' && $seller_name_text != '') || ($shipping_time_checkbox != 'off' && $shipping_time_text != ''))):
	?>
			<div class="product-selected-features">
				<ul class="product-selected-features-list">
					<?php if ($product->is_type('variable') && 'on' == get_option('irantheme_single_product_variable_selected_checkbox', 'on')): ?>
						<p class="product-selected-features-list-title">مشخصات انتخاب شده:</p>
					<?php endif; ?>
					<?php
					if ($seller_name_checkbox == 'on'):
					?>
						<li class="product-selected-features-list-item no-change">
							<span class="product-selected-features-list-item-label">
								<i class="product-selected-features-list-item-label-icon iconsax" icon-name="shop"></i>
								<span class="product-selected-features-list-item-label-text"><?php _e('فروشنده:'); ?></span>
							</span>
							<span class="product-selected-features-list-item-value"><?php echo esc_attr($seller_name_text); ?></span>
						</li>
					<?php endif; ?>
					<?php
					if (isset($product) && $product->is_type('variable') && 'on' == get_option('irantheme_single_product_variable_selected_checkbox', 'on')):
						$variation_attributes = $product->get_variation_attributes();
						$default_attributes = $product->get_default_attributes();
						$decoded_default_attributes = [];

						foreach ($default_attributes as $key => $value) {
							// Decode the attribute name and its value
							$decoded_key = urldecode($key);
							$decoded_value = urldecode($value);
							$decoded_default_attributes[$decoded_key] = $decoded_value;
						}
					?>
						<?php foreach ($variation_attributes as $attribute_name => $options) : ?>
							<?php
							$default_value_name = '';
							$default_value_slug = '';
							// Get taxonomy type value
							$attribute_icon = irantheme_get_attribute_custom_option_value($attribute_name, 'filter_icon');
							// Check for the presence of a default value in the decoded array
							if (isset($decoded_default_attributes[$attribute_name])) {
								$default_value_slug = $decoded_default_attributes[$attribute_name];
							}

							if ($default_value_slug) {
								// Finding the term object corresponding to the default value
								$term = get_term_by('slug', $default_value_slug, $attribute_name);
								if ($term && !is_wp_error($term)) {
									$default_value_name = $term->name;
								}
							}

							$display_value = empty($default_value_name) ? 'انتخاب نشده' : $default_value_name;
							?>
							<li id="product_selected_features_item_<?php echo esc_attr(sanitize_title($attribute_name)); ?>"
								data-attribute-name="<?php echo esc_attr(sanitize_title($attribute_name)); ?>"
								class="product-selected-features-list-item">
								<span class="product-selected-features-list-item-label">
									<i class="product-selected-features-list-item-label-icon <?php echo esc_attr($attribute_icon == '' ? 'fa-light fa-cube' : $attribute_icon); ?>"></i>
									<span class="product-selected-features-list-item-label-text">
										<?php echo wc_attribute_label($attribute_name) . ':'; ?>
									</span>
								</span>
								<span class="product-selected-features-list-item-value">
									<?php echo esc_html($display_value); ?>
								</span>
							</li>
						<?php endforeach; ?>
					<?php endif; ?>
					<?php
					if ('on' == $shipping_time_checkbox && "" != $shipping_time_text):
					?>
						<li class="product-selected-features-list-item no-change">
							<span class="product-selected-features-list-item-label">
								<i class="product-selected-features-list-item-label-icon fa-light iconsax" icon-name="truck-speed"></i>
								<span class="product-selected-features-list-item-label-text">
									<?php _e('زمان ارسال:'); ?>
								</span>
							</span>
							<span class="product-selected-features-list-item-value"><?php echo esc_attr($shipping_time_text); ?></span>
						</li>
					<?php endif; ?>
				</ul>
			</div>
	<?php
		endif;
	endif;
	?>
	<div class="product-add-to-card">
		<?php
		$is_call_for_price = get_post_meta($product->get_id(), 'irantheme_product_meta_is_call_for_price', true);
		if ($is_call_for_price != 'yes'):
		?>
			<?php if (($product_status == 'instock' || $product_status == 'onbackorder') && $product->get_price() != ''): ?>
				<?php if ($variable_selected_checkbox != 'off' && (($seller_name_checkbox != 'off' && $seller_name_text != '') || ($shipping_time_checkbox != 'off' && $shipping_time_text != ''))): ?>
					<div class="irantheme-single-product-divider"></div>
				<?php endif; ?>
				<div class="product-add-to-card-price">
					<?php woocommerce_template_single_price(); ?>
				</div>
				<?php if (($product->is_type('simple') && $product->get_manage_stock()) || $product->is_type('variable')) : ?>
					<div class="product-add-to-card-stock-status">
						<?php
						if ($product->is_type('simple')) {
							$low_stock_threshold = 5;
							$stock_quantity = $product->get_stock_quantity();
							if ($product_status == 'instock') {
								if ($stock_quantity <= $low_stock_threshold) {
									printf(
										'<p class="product-add-to-card-stock-status-item low-stock">فقط %s عدد در انبار باقی مانده!</p>',
										esc_html($stock_quantity)
									);
								} else {
									printf(
										'<p class="product-add-to-card-stock-status-item in-stock">موجود در انبار: %s عدد</p>',
										esc_html($stock_quantity)
									);
								}
							} elseif ($product_status == 'onbackorder') {
								$backorder_status = $product->get_backorders();
								if ($backorder_status == 'notify') {
									printf(
										'<p class="product-add-to-card-stock-status-item on-backorder">موجود در انبار: قابل پیش خرید</p>',
										esc_html($stock_quantity)
									);
								}
							}
						}
						?>
					</div>
				<?php endif; ?>
				<div class="product-add-to-card-button">
					<?php do_action('woocommerce_single_variation'); ?>
				</div>
			<?php elseif ($product_status == 'outofstock' || $product->get_price() == ''): ?>
				<span class="product-add-to-card-tip outofstock">
					<span class="product-add-to-card-tip-icon">
						<i class="fa-light fa-cart-circle-exclamation"></i>
					</span>
					<span class="product-add-to-card-tip-text">متاسفانه این کالا فعلا موجود نیست.</span>
				</span>
			<?php endif; ?>
		<?php else: ?>
			<?php
			$last_price = get_post_meta($product_id, 'irantheme_product_meta_last_price_amount', true);
			$last_price_date = get_post_meta($product_id, 'irantheme_product_meta_last_price_date', true);
			$support_phone_number = get_option('irantheme_single_product_support_phone_number', '0913XXXXXXX');
			?>
			<?php if ($last_price != '' && $last_price_date != ''): ?>
				<div class="product-add-to-card-last-price">
					<div class="product-add-to-card-last-price-label">
						<span>آخرین قیمت</br>ثــــبت شــده</span>
					</div>
					<div class="product-add-to-card-last-price-value">
						<span class="product-add-to-card-last-price-value-date"><?php echo esc_attr($last_price_date); ?></span>
						<span class="product-add-to-card-last-price-value-price"><?php echo wc_price(esc_attr($last_price)); ?></span>
					</div>
				</div>
			<?php endif; ?>
			<span class="product-add-to-card-tip call-for-price-info">
				<span class="product-add-to-card-tip-icon">
					<i class="fa-light fa-phone-volume"></i>
				</span>
				<span class="product-add-to-card-tip-text">برای استعلام قیمت و موجودی تماس بگیرید.</span>
			</span>
			<span class="product-add-to-card-call-info">
				<span class="product-add-to-card-call-info-label"><?php _e('شماره تماس پشتیبانی'); ?></span>
				<span class="product-add-to-card-call-info-number"><?php echo esc_html($support_phone_number); ?></span>
			</span>
		<?php endif; ?>
	</div>
	<?php if (!$product->is_downloadable() || !irantheme_are_all_variations_downloadable($product)): ?>
		<?php if (get_option('irantheme_single_product_update_date') == 'on'): ?>
			<div class="product-last-update">
				<p class="product-last-update-label">
					<i class="fa-light fa-calendar-lines-pen"></i>
					<?php _e('آخرین بروزرسانی:'); ?>
				</p>
				<p class="product-last-update-date"><?php echo get_the_modified_date('Y/m/d'); ?></p>
			</div>
		<?php endif; ?>
	<?php endif; ?>
</div>