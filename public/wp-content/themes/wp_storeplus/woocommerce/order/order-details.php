<?php

/**
 * Order details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @wc-version 9.6.0
 * @author M-MALEKZADEH77
 *
 * @var bool $show_downloads Controls whether the downloads table should be rendered.
 */

// phpcs:disable WooCommerce.Commenting.CommentHooks.MissingHookComment

defined('ABSPATH') || exit;

$order = wc_get_order($order_id); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

if (! $order) {
	return;
}

$order_items        = $order->get_items(apply_filters('woocommerce_purchase_order_item_types', 'line_item'));
$show_purchase_note = $order->has_status(apply_filters('woocommerce_purchase_note_order_statuses', array('completed', 'processing')));
$downloads          = $order->get_downloadable_items();

if ($show_downloads) {
	wc_get_template(
		'order/order-downloads.php',
		array(
			'downloads'  => $downloads,
			'show_title' => true,
		)
	);
}
?>
<section class="woocommerce-order-details irantheme-myaccount-order-section irantheme-myaccount-order-details">
	<?php do_action('woocommerce_order_details_before_order_table', $order); ?>

	<h2 class="woocommerce-order-details__title irantheme-myaccount-content-title"><i class="iconsax" icon-name="receipt"></i><?php esc_html_e('Order details', 'woocommerce'); ?></h2>
	<div class="irantheme-myaccount-orders-table-wrap">
		<table class="woocommerce-table woocommerce-table--order-details shop_table order_details irantheme-myaccount-orders-table">

			<thead class="irantheme-myaccount-orders-table-radius">
				<tr>
					<th class="woocommerce-table__product-name product-name"><?php esc_html_e('Product', 'woocommerce'); ?></th>
					<th class="woocommerce-table__product-quantity product-quantity"><?php esc_html_e('Quantity', 'woocommerce'); ?></th>
					<th class="woocommerce-table__product-sale-price product-sale-price"><?php esc_html_e('Price', 'woocommerce'); ?></th>
					<th class="woocommerce-table__product-table product-total"><?php esc_html_e('Total', 'woocommerce'); ?></th>
				</tr>
			</thead>

			<tbody>
				<?php
				do_action('woocommerce_order_details_before_order_table_items', $order);

				foreach ($order_items as $item_id => $item) {
					$product = $item->get_product();

					wc_get_template(
						'order/order-details-item.php',
						array(
							'order'              => $order,
							'item_id'            => $item_id,
							'item'               => $item,
							'show_purchase_note' => $show_purchase_note,
							'purchase_note'      => $product ? $product->get_purchase_note() : '',
							'product'            => $product,
						)
					);
				}

				do_action('woocommerce_order_details_after_order_table_items', $order);
				?>
			</tbody>
			<tfoot>
				<?php
				foreach ($order->get_order_item_totals() as $key => $total) {
				?>
					<tr>
						<th colspan="3" scope="row"><?php echo esc_html($total['label']); ?></th>
						<td><?php echo wp_kses_post($total['value']); ?></td>
					</tr>
				<?php
				}
				?>
				<?php if ($order->get_customer_note()) : ?>
					<tr>
						<th colspan="3"><?php esc_html_e('Note:', 'woocommerce'); ?></th>
						<td><?php echo wp_kses(nl2br(wptexturize($order->get_customer_note())), array()); ?></td>
					</tr>
				<?php endif; ?>
			</tfoot>
		</table>
	</div>
	<?php do_action('woocommerce_order_details_after_order_table', $order); ?>
</section>