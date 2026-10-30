<?php

/**
 * My Addresses
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-address.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @wc-version 9.3.0
 * @author M-MALEKZADEH77
 */

defined('ABSPATH') || exit;

$customer_id = get_current_user_id();
$col_class = '';
$padding_e_class = 'pe-xl-0 ';
$padding_s_class = 'ps-xl-0 ';
if (! wc_ship_to_billing_address_only() && wc_shipping_enabled()) {
	$get_addresses = apply_filters(
		'woocommerce_my_account_get_addresses',
		array(
			'billing'  => __('Billing address', 'woocommerce'),
			'shipping' => __('Shipping address', 'woocommerce'),
		),
		$customer_id
	);
	$col_class = 'col-xxl-6 col-xl-6 col-lg-12 col-md-12';
} else {
	$get_addresses = apply_filters(
		'woocommerce_my_account_get_addresses',
		array(
			'billing' => __('Billing address', 'woocommerce'),
		),
		$customer_id
	);
	$col_class = 'col-12';
}

$customer = new \WC_Customer($customer_id);
$countries = new \WC_Countries();

// gets country and state codes
$billing_country = $customer->get_billing_country();
$billing_state   = $customer->get_billing_state();

// gets the full names of the country and state
$billing_full_country = (isset($countries->countries[$billing_country])) ? $countries->countries[$billing_country] : $billing_country;
$billing_full_state   = ($billing_country && $billing_state && isset($countries->states[$billing_country][$billing_state])) ? $countries->states[$billing_country][$billing_state] : $billing_state;

// gets country and state codes
$shipping_country = $customer->get_shipping_country();
$shipping_state   = $customer->get_shipping_state();

// gets the full names of the country and state
$shipping_full_country = (isset($countries->countries[$shipping_country])) ? $countries->countries[$shipping_country] : $shipping_country;
$shipping_full_state   = ($shipping_country && $shipping_state && isset($countries->states[$shipping_country][$shipping_state])) ? $countries->states[$shipping_country][$shipping_state] : $shipping_state;
?>
<div class="irantheme-myaccount-address">
	<h2 class="irantheme-myaccount-content-title"><i class="fa-light fa-location-dot"></i>آدرس ها</h2>
	<div class="irantheme-myaccount-address-holder row m-0 p-0">
		<p class="irantheme-myaccount-address-note">
			<i class="fa-light fa-seal-question irantheme-tip-icon"></i>
			<?php echo apply_filters('woocommerce_my_account_my_address_description', esc_html__('The following addresses will be used on the checkout page by default.', 'woocommerce')); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
			?>
		</p>
		<?php foreach ($get_addresses as $name => $address_title) : ?>
			<?php $address = wc_get_account_formatted_address($name); ?>
			<?php if ($name == 'billing'): ?>
				<?php
				$billing_address_1 = $customer->get_billing_address_1();
				$billing_address_2 = $customer->get_billing_address_2();
				$billing_address = $billing_address_1;
				if ($billing_address_2) {
					$billing_address = $billing_address . ',' . $billing_address_2;
				}
				?>
				<div class="irantheme-myaccount-address-billing irantheme-myaccount-address-billing-col <?php echo $col_class != 'col-12' ? $padding_e_class : ''; ?> <?php echo esc_attr($col_class); ?>">
					<div class="irantheme-myaccount-orders-table-wrap">
						<table class="irantheme-myaccount-orders-table">
							<thead class="irantheme-myaccount-orders-table-radius irantheme-mono-thead-style">
								<tr>
									<th colspan="2"><?php esc_html_e('Billing address', 'woocommerce'); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php if ($address): ?>
									<tr>
										<td>نام و نام خانوادگی</td>
										<td><?php echo esc_html($customer->get_billing_first_name() . ' ' . $customer->get_billing_last_name()); ?></td>
									</tr>
									<tr>
										<td>آدرس</td>
										<td><?php echo esc_html($billing_address); ?></td>
									</tr>
									<tr>
										<td>شهر</td>
										<td><?php echo esc_html($customer->get_billing_city()); ?></td>
									</tr>
									<tr>
										<td>استان</td>
										<td><?php echo esc_html(wc_strtoupper($billing_full_state)); ?></td>
									</tr>
									<tr>
										<td>کشور</td>
										<td><?php echo esc_html(wc_strtoupper($billing_full_country)); ?></td>
									</tr>
									<tr>
										<td>کدپستی</td>
										<td><?php echo esc_html($customer->get_billing_postcode()); ?></td>
									</tr>
									<?php if ($customer->get_billing_phone()) : ?>
										<tr>
											<td>تلفن</td>
											<td><?php echo esc_html($customer->get_billing_phone()); ?></td>
										</tr>
									<?php endif; ?>

									<?php if ($customer->get_billing_email()) : ?>
										<tr>
											<td>ایمیل</td>
											<td><?php echo esc_html($customer->get_billing_email()); ?></td>
										</tr>
									<?php endif; ?>
								<?php else: ?>
									<tr>
										<td colspan="2"><?php esc_html_e('You have not set up this type of address yet.', 'woocommerce'); ?></td>
									</tr>
								<?php endif; ?>
							</tbody>
							<?php /**
							 * Used to output content after core address fields.
							 *
							 * @param string $name Address type.
							 * @since 8.7.0
							 */
							do_action('woocommerce_my_account_after_my_address', $name); ?>
						</table>
					</div>
					<div class="irantheme-myaccount-address-action-btn">
						<a href="<?php echo esc_url(wc_get_endpoint_url('edit-address', $name)); ?>" class="edit irantheme-myaccount-address-action-btn-link">
							<i class="fa-light fa-pen-to-square"></i>
							<?php
							printf(
								/* translators: %s: Address title */
								$address ? esc_html__('Edit %s', 'woocommerce') : esc_html__('Add %s', 'woocommerce'),
								esc_html($address_title)
							);
							?>
						</a>
					</div>
				</div>
			<?php elseif ($name == 'shipping') : ?>
				<?php
				$shipping_address_1 = $customer->get_shipping_address_1();
				$shipping_address_2 = $customer->get_shipping_address_2();
				$shipping_address = $shipping_address_1;
				if ($shipping_address_2) {
					$shipping_address = $shipping_address . ' , ' . $shipping_address_2;
				}
				?>
				<div class="irantheme-myaccount-address-shipping irantheme-myaccount-address-shipping-col <?php echo $col_class != 'col-12' ? $padding_s_class : ''; ?> <?php echo esc_attr($col_class); ?>">
					<div class="irantheme-myaccount-orders-table-wrap">
						<table class="irantheme-myaccount-orders-table">
							<thead class="irantheme-myaccount-orders-table-radius irantheme-mono-thead-style">
								<tr>
									<th colspan="2"><?php esc_html_e('Shipping address', 'woocommerce'); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php if ($address): ?>
									<tr>
										<td>نام و نام خانوادگی</td>
										<td><?php echo esc_html($customer->get_shipping_first_name() . ' ' . $customer->get_shipping_last_name()); ?></td>
									</tr>
									<tr>
										<td>آدرس</td>
										<td><?php echo esc_html($shipping_address); ?></td>
									</tr>
									<tr>
										<td>شهر</td>
										<td><?php echo esc_html($customer->get_shipping_city()); ?></td>
									</tr>
									<tr>
										<td>استان</td>
										<td><?php echo esc_html(wc_strtoupper($shipping_full_state)); ?></td>
									</tr>
									<tr>
										<td>کشور</td>
										<td><?php echo esc_html(wc_strtoupper($shipping_full_country)); ?></td>
									</tr>
									<tr>
										<td>کدپستی</td>
										<td><?php echo esc_html($customer->get_shipping_postcode()); ?></td>
									</tr>
									<?php if ($customer->get_shipping_phone()) : ?>
										<tr>
											<td>تلفن</td>
											<td><?php echo esc_html($customer->get_shipping_phone()); ?></td>
										</tr>
									<?php endif; ?>
								<?php else: ?>
									<tr>
										<td colspan="2"><?php esc_html_e('You have not set up this type of address yet.', 'woocommerce'); ?></td>
									</tr>
								<?php endif; ?>
							</tbody>
							<?php /**
							 * Used to output content after core address fields.
							 *
							 * @param string $name Address type.
							 * @since 8.7.0
							 */
							do_action('woocommerce_my_account_after_my_address', $name); ?>
						</table>
					</div>
					<div class="irantheme-myaccount-address-action-btn">
						<a href="<?php echo esc_url(wc_get_endpoint_url('edit-address', $name)); ?>" class="edit irantheme-myaccount-address-action-btn-link">
							<i class="fa-light fa-pen-to-square"></i>
							<?php
							printf(
								/* translators: %s: Address title */
								$address ? esc_html__('Edit %s', 'woocommerce') : esc_html__('Add %s', 'woocommerce'),
								esc_html($address_title)
							);
							?>
						</a>
					</div>
				</div>
			<?php endif; ?>
		<?php endforeach; ?>
	</div>
</div>