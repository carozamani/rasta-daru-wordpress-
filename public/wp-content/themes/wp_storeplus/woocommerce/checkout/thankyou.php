<?php

/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @wc-version 8.1.0
 * @author M-MALEKZADEH77
 * 
 * @var WC_Order $order
 */

defined('ABSPATH') || exit;
?>

<div class="woocommerce-order irantheme-wc-checkout-order-result">

	<?php
	if ($order) :

		do_action('woocommerce_before_thankyou', $order->get_id());
	?>
		<?php if ($order->has_status('failed')) : ?>
			<div class="irantheme-wc-checkout-order-failed-message">
				<div class="irantheme-wc-checkout-order-failed-message-icon">
					<i class="fa-light fa-circle-exclamation"></i>
				</div>
				<p class="irantheme-wc-checkout-order-failed-message-desc">
					<?php esc_html_e('Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce'); ?>
				</p>
				<div class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions irantheme-wc-checkout-order-failed-message-actions">
					<a href="<?php echo esc_url($order->get_checkout_payment_url()); ?>" class="button irantheme-btn irantheme-btn-medium pay"><i class="fa-light fa-credit-card"></i><?php esc_html_e('Pay', 'woocommerce'); ?></a>
					<?php if (is_user_logged_in()) : ?>
						<a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="button irantheme-btn irantheme-btn-medium account"><i class="fa-light fa-address-card"></i><?php esc_html_e('My account', 'woocommerce'); ?></a>
					<?php endif; ?>
				</div>
			</div>
		<?php else : ?>
			<?php wc_get_template('checkout/order-received.php', array('order' => $order)); ?>
		<?php endif; ?>
		<?php
		// Remove Order Details Table
		remove_action('woocommerce_thankyou', 'woocommerce_order_details_table', 10);
		do_action('woocommerce_thankyou', $order->get_id());
		?>
</div>
<?php else : ?>
	<?php wc_get_template('checkout/order-received.php', array('order' => false)); ?>
<?php endif; ?>

</div>