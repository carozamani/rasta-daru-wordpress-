<?php

/**
 * View Order
 *
 * Shows the details of a particular order on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/view-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @wc-version 3.0.0
 * @author M-MALEKZADEH77
 */

defined('ABSPATH') || exit;

$notes = $order->get_customer_order_notes();
// We make sure the order belongs to the user. This will also be true if the user is a guest, and the order belongs to a guest (userID === 0).
$show_customer_details = $order->get_user_id() === get_current_user_id();

$actions            = array_filter(
	wc_get_account_orders_actions($order),
	function ($action) {
		return 'View' !== $action['name'];
	}
);
?>
<div class="irantheme-myaccount-view-order">
	<div class="irantheme-myaccount-view-order-holder row m-0 p-0">
		<div class="irantheme-myaccount-view-order-invoice pe-lg-0 col-xxl-8 col-xl-7 col-lg-12 col-md-12">
			<?php do_action('woocommerce_view_order', $order_id); ?>
		</div>
		<div class="irantheme-myaccount-view-order-details ps-lg-0 col-xxl-4 col-xl-5 col-lg-12 col-md-12">
			<div class="irantheme-myaccount-view-order-details-summary">
				<div class="irantheme-myaccount-orders-table-wrap">
					<table class="irantheme-myaccount-orders-table">
						<thead class="irantheme-myaccount-orders-table-radius irantheme-mono-thead-style">
							<tr>
								<th colspan="2">گزارش</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th>شماره سفارش</th>
								<td><?php echo $order->get_order_number(); ?></td>
							</tr>
							<tr>
								<th>تاریخ</th>
								<td><?php echo wc_format_datetime($order->get_date_created()); ?></td>
							</tr>
							<tr>
								<th>وضعیت</th>
								<td><span class="irantheme-woocommerce-order-status order-<?php echo esc_attr($order->get_status()); ?>"><?php echo wc_get_order_status_name($order->get_status()); ?></span></td>
							</tr>
							<?php
							if (! empty($actions)) :
							?>
								<?php
								$actions_flag = false;
								foreach ($actions as $key => $action) {
									if ($key != 'view') {
										$actions_flag = true;
									}
								}
								?>
								<?php if ($actions_flag): ?>
									<tr>
										<th class="order-actions--heading"><?php esc_html_e('Actions', 'woocommerce'); ?>:</th>
										<td>
											<div class="irantheme-myaccount-orders-action">
												<?php
												$wp_button_class = wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : '';
												foreach ($actions as $key => $action) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
													if ($key != 'view') {
														if (empty($action['aria-label'])) {
															// Generate the aria-label based on the action name.
															/* translators: %1$s Action name, %2$s Order number. */
															$action_aria_label = sprintf(__('%1$s order number %2$s', 'woocommerce'), $action['name'], $order->get_order_number());
														} else {
															$action_aria_label = $action['aria-label'];
														}
														echo '<a href="' . esc_url($action['url']) . '" class="woocommerce-button' . esc_attr($wp_button_class) . ' button ' . sanitize_html_class($key) . ' order-actions-button irantheme-myaccount-orders-action-item" aria-label="' . esc_attr($action_aria_label) . '">' . esc_html($action['name']) . '</a>';
														unset($action_aria_label);
													}
												}
												?>
											</div>
										</td>
									</tr>
								<?php endif; ?>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="irantheme-myaccount-view-order-details-customer">
				<?php
				/**
				 * Action hook fired after the order details.
				 *
				 * @since 4.4.0
				 * @param WC_Order $order Order data.
				 */
				do_action('woocommerce_after_order_details', $order);

				if ($show_customer_details) {
					wc_get_template('order/order-details-customer.php', array('order' => $order));
				} ?>
			</div>
			<?php if ($notes) : ?>
				<div class="irantheme-myaccount-view-order-details-updates">
					<div class="irantheme-myaccount-orders-table-wrap">
						<table class="irantheme-myaccount-orders-table">
							<thead>
								<th colspan="2"><?php esc_html_e('Order updates', 'woocommerce'); ?></th>
							</thead>
							<tbody>
								<?php foreach ($notes as $note) : ?>
									<tr>
										<td><?php echo date_i18n(esc_html__('l jS \o\f F Y, h:ia', 'woocommerce'), strtotime($note->comment_date)); ?></td>
										<td class="irantheme-myaccount-view-order-details-updates-note"><?php echo wpautop(wptexturize($note->comment_content)); ?></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>