<?php

/**
 * Single Product tabs
 * @author	M-MALEKZADEH77
 * @wc-version 9.6.0
 * @since 1.0
 * @see 1.0
 */

use Irantheme_Dev\Irantheme_Color_Static;
use Irantheme_Dev\Irantheme_Common_Static;

if (!defined('ABSPATH')) {
	exit;
}
global $product;
// Get details color for tabs active mode
$tabs_color = get_option('irantheme_single_product_details_color');
$extra_color_class = '';
if ($tabs_color !== 'off') {
	$extra_color_class = Irantheme_Color_Static::get_class_color_mode('color', $tabs_color);
	$extra_color_class .= ' ' . Irantheme_Color_Static::get_class_color_mode('border', $tabs_color);
}

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 *
 * @see woocommerce_default_product_tabs()
 */
$product_tabs = apply_filters('woocommerce_product_tabs', array());

if (!empty($product_tabs)) : ?>

	<div class="irantheme-single-product-content-tabs-container">
		<ul class="irantheme-single-product-content-tabs-list" role="tablist">
			<?php
			$tab_counter = 1;
			?>
			<?php foreach ($product_tabs as $key => $product_tab) :
				$review_count = $product->get_review_count();
				if ($review_count > 99) {
					$review_count = '+99';
				}
			?>
				<li class="irantheme-single-product-content-tabs-list-item <?php echo $tab_counter == 1 ? "active" : ""; ?>" id="irantheme-tab-title-<?php echo esc_attr($key); ?>" data-active-class="<?php echo esc_attr($extra_color_class); ?>" data-rel="irantheme-single-product-content-tabs-panel-<?php echo esc_attr($key); ?>">
					<a id="irantheme_tab_<?php echo esc_attr($key); ?>" class="irantheme-single-product-content-tabs-list-item-anchor" role="tab" aria-controls="tab-<?php echo esc_attr($key); ?>">
						<span>
							<?php
							if ($key != 'reviews') {
								echo wp_kses_post(apply_filters('woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key));
							} else {
								echo '<span class="review-text">دیدگاه ها</span><span class="review-count">' . $review_count . '</span>';
							}
							?>
						</span>
					</a>
				</li>
				<?php $tab_counter++; ?>
			<?php endforeach; ?>
		</ul>
		<?php
		$tab_counter = 1;
		?>
		<?php foreach ($product_tabs as $key => $product_tab) : ?>
			<div class="irantheme-single-product-content-tabs-panel irantheme-single-product-content-tabs-panel-<?php echo esc_attr($key); ?>" id="irantheme-single-product-content-tabs-panel-<?php echo esc_attr($key); ?>" role="tabpanel" aria-labelledby="tab-title-<?php echo esc_attr($key); ?>" style="<?php echo $tab_counter != 1 ? "display:none;" : ""; ?>">
				<div class="irantheme-single-product-content-tabs-panel-inner-wrapper">
					<?php if ($product_tab['callback'] == 'woocommerce_product_description_tab') : ?>
						<div class="irantheme-single-text">
						<?php endif; ?>
						<?php
						if (isset($product_tab['callback'])) {
							call_user_func($product_tab['callback'], $key, $product_tab);
						}
						?>
						<?php if ($product_tab['callback'] == 'woocommerce_product_description_tab') : ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<?php $tab_counter++; ?>
		<?php endforeach; ?>

		<?php do_action('woocommerce_product_after_tabs');
		?>
	</div>

<?php endif; ?>