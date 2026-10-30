<?php

/**
 * Order Customer Details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details-customer.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @wc-version 8.7.0
 * @author M-MALEKZADEH77
 */

defined('ABSPATH') || exit;

$show_shipping = !wc_ship_to_billing_address_only() && $order->needs_shipping_address();
?>
<section class="woocommerce-customer-details irantheme-myaccount-details-customer">

	<?php if ($show_shipping) : ?>

		<section class="irantheme-myaccount-details-customer-addresses">

		<?php endif; ?>

		<?php $countries = new \WC_Countries();

		// gets country and state codes
		$billing_country = $order->get_billing_country();
		$billing_state   = $order->get_billing_state();

		// gets the full names of the country and state
		$billing_full_country = (isset($countries->countries[$billing_country])) ? $countries->countries[$billing_country] : $billing_country;
		$billing_full_state   = ($billing_country && $billing_state && isset($countries->states[$billing_country][$billing_state])) ? $countries->states[$billing_country][$billing_state] : $billing_state;

		// gets country and state codes
		$shipping_country = $order->get_shipping_country();
		$shipping_state   = $order->get_shipping_state();

		// gets the full names of the country and state
		$shipping_full_country = (isset($countries->countries[$shipping_country])) ? $countries->countries[$shipping_country] : $shipping_country;
		$shipping_full_state   = ($shipping_country && $shipping_state && isset($countries->states[$shipping_country][$shipping_state])) ? $countries->states[$shipping_country][$shipping_state] : $shipping_state;
		?>
		<?php
		$billing_address_1 = $order->get_billing_address_1();
		$billing_address_2 = $order->get_billing_address_2();
		$billing_address = $billing_address_1;
		if ($billing_address_2) {
			$billing_address = $billing_address . ',' . $billing_address_2;
		}
		$shipping_address_1 = $order->get_shipping_address_1();
		$shipping_address_2 = $order->get_shipping_address_2();
		$shipping_address = $shipping_address_1;
		if ($shipping_address_2) {
			$shipping_address = $shipping_address . ',' . $shipping_address_2;
		}
		?>
		<div class="irantheme-myaccount-orders-table-wrap">
			<table class="irantheme-myaccount-orders-table">
				<thead class="irantheme-myaccount-orders-table-radius irantheme-mono-thead-style">
					<tr>
						<th colspan="2"><?php esc_html_e('Billing address', 'woocommerce'); ?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>نام و نام خانوادگی</td>
						<td><?php echo esc_html($order->get_billing_first_name() . ' ' . $order->get_billing_last_name()); ?></td>
					</tr>
					<tr>
						<td>آدرس</td>
						<td><?php echo esc_html($billing_address); ?></td>
					</tr>
					<!-- <tr>
						<td>شهر</td>
						<td><?php //echo esc_html($order->billing_city); 
							?></td>
					</tr>
					<tr>
						<td>استان</td>
						<td><?php //echo esc_html(wc_strtoupper($billing_full_state)); 
							?></td>
					</tr>
					<tr>
						<td>کشور</td>
						<td><?php //echo esc_html(wc_strtoupper($billing_full_country)); 
							?></td>
					</tr> -->
					<tr>
						<td>کدپستی</td>
						<td><?php echo esc_html($order->get_billing_postcode()); ?></td>
					</tr>
					<?php if ($order->get_billing_phone()) : ?>
						<tr>
							<td>تلفن</td>
							<td><?php echo esc_html($order->get_billing_phone()); ?></td>
						</tr>
					<?php endif; ?>

					<?php if ($order->get_billing_email()) : ?>
						<tr>
							<td>ایمیل</td>
							<td><?php echo esc_html($order->get_billing_email()); ?></td>
						</tr>
					<?php endif; ?>
				</tbody>
				<?php
				/**
				 * Action hook fired after an address in the order customer details.
				 *
				 * @since 8.7.0
				 * @param string $address_type Type of address (billing or shipping).
				 * @param WC_Order $order Order object.
				 */
				do_action('woocommerce_order_details_after_customer_address', 'billing', $order);
				?>
			</table>
		</div>
		<?php if ($show_shipping) : ?>
			<div class="irantheme-myaccount-orders-table-wrap">
				<table class="irantheme-myaccount-orders-table">
					<thead class="irantheme-myaccount-orders-table-radius irantheme-mono-thead-style">
						<tr>
							<th colspan="2"><?php esc_html_e('shipping address', 'woocommerce'); ?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>نام و نام خانوادگی</td>
							<td><?php echo esc_html($order->shipping_first_name . ' ' . $order->shipping_last_name); ?></td>
						</tr>
						<tr>
							<td>آدرس</td>
							<td><?php echo esc_html($shipping_address); ?></td>
						</tr>
						<!-- <tr>
							<td>شهر</td>
							<td><?php //echo esc_html($order->shipping_city); 
								?></td>
						</tr>
						<tr>
							<td>استان</td>
							<td><?php //echo esc_html(wc_strtoupper($shipping_full_state)); 
								?></td>
						</tr>
						<tr>
							<td>کشور</td>
							<td><?php //echo esc_html(wc_strtoupper($shipping_full_country)); 
								?></td>
						</tr> -->
						<tr>
							<td>کدپستی</td>
							<td><?php echo esc_html($order->shipping_postcode); ?></td>
						</tr>
						<?php if ($order->get_shipping_phone()) : ?>
							<tr>
								<td>تلفن</td>
								<td><?php echo esc_html($order->get_shipping_phone()); ?></td>
							</tr>
						<?php endif; ?>
					</tbody>
					<?php
					/**
					 * Action hook fired after an address in the order customer details.
					 *
					 * @since 8.7.0
					 * @param string $address_type Type of address (billing or shipping).
					 * @param WC_Order $order Order object.
					 */
					do_action('woocommerce_order_details_after_customer_address', 'shipping', $order);
					?>
				</table>
			</div>
		</section>
	<?php endif; ?>


	<?php do_action('woocommerce_order_details_after_customer_details', $order); ?>

</section>