<?php

/**
 * "Order received" message.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/order-received.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @wc-version 8.8.0
 * @author	M-MALEKZADEH77
 *
 * @var WC_Order|false $order
 */

defined('ABSPATH') || exit;

$inv_visibility = get_option('irantheme_wc_invoice_customer_thankyou_btn_visibility_checkbox', 'on');
?>

<div class="irantheme-wc-checkout-order-success-message">
	<?php
	/**
	 * Filter the message shown after a checkout is complete.
	 *
	 * @since 2.2.0
	 *
	 * @param string         $message The message.
	 * @param WC_Order|false $order   The order created during checkout, or false if order data is not available.
	 */

	use Irantheme_Dev\Irantheme_Woocommerce_Invoice_Core;

	if (! $order || ! is_a($order, 'WC_Order')) {
		wc_print_notice(__('اطلاعات سفارش در دسترس نیست. لطفاً با پشتیبانی تماس بگیرید.', IRANTHEME_TEXTDOMAIN), 'error');
		wp_redirect(wc_get_page_permalink('myaccount'));
		exit;
	} else {
		if ($order) {
			$status   = $order->get_status();

			$status_text = '';
			switch ($status) {
				case 'pending':
					$status_text = 'در انتظار پرداخت';
					break;
				case 'processing':
					$status_text = 'در حال پردازش';
					break;
				case 'on-hold':
					$status_text = 'در انتظار بررسی';
					break;
				case 'completed':
					$status_text = 'تکمیل شده';
					break;
				case 'cancelled':
					$status_text = 'لغو شده';
					break;
				case 'refunded':
					$status_text = 'مسترد شده';
					break;
				case 'failed':
					$status_text = 'ناموفق';
					break;
				default:
					$status_text = $status;
			}
			$status_html = '<span class="order-status ' . $status . '">' . $status_text . '</span>';
			$order_number_html = '<span class="order-number">' . $order->get_order_number() . '</span>';
			$message = sprintf('سفارش شما با شماره پیگیری %s ثبت شده و هم اکنون در وضعیت %s می باشد.', $order_number_html, $status_html);
		} else {
			$message = esc_html__('Thank you. Your order has been received.', 'woocommerce');
		}

		$message = apply_filters(
			'woocommerce_thankyou_order_received_text',
			$message,
			$order
		);
	?>
		<div class="irantheme-wc-checkout-order-success-message-icon">
			<i class="fa-light fa-circle-check"></i>
		</div>
		<p class="irantheme-wc-checkout-order-success-message-title">
			سفارش شما با موفقیت ثبت شد.
		</p>
		<p class="irantheme-wc-checkout-order-success-message-desc">
			<?php
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $message;
			?>
		</p>
		<div class="irantheme-wc-checkout-order-success-message-meta">
			<div class="meta-item order-no">
				<i class="meta-icon fa-light fa-clipboard-list"></i>
				<div class="meta-text">
					<p class="meta-text-title">شماره سفارش</p>
					<p class="meta-text-value"><?php echo $order->get_order_number(); ?></p>
				</div>
			</div>
			<div class="meta-item order-reg-date">
				<i class="meta-icon fa-light fa-calendar-clock"></i>
				<div class="meta-text">
					<p class="meta-text-title">تاریخ ثبت سفارش</p>
					<p class="meta-text-value"><?php echo wc_format_datetime($order->get_date_created(), 'H:m - Y/m/d'); ?></p>
				</div>
			</div>
			<div class="meta-item order-payment">
				<i class="meta-icon fa-light fa-credit-card"></i>
				<div class="meta-text">
					<p class="meta-text-title">روش پرداخت</p>
					<p class="meta-text-value"><?php echo $order->get_payment_method_title(); ?></p>
				</div>
			</div>
		</div>
		<div class="irantheme-wc-checkout-order-success-message-btn">
			<?php if (is_user_logged_in()) : ?>
				<a href="<?php echo esc_url($order->get_view_order_url()); ?>" class="button irantheme-btn irantheme-btn-medium irantheme-btn-low-radius order-details"><i class="fa-light fa-arrow-right"></i><?php esc_html_e('مشاهده جزییات سفارش', IRANTHEME_TEXTDOMAIN); ?></a>
				<?php if ($inv_visibility == 'on' && $order->has_status(['completed', 'processing', 'on-hold', 'pending'])) : ?>
					<a href="<?php echo esc_url(Irantheme_Woocommerce_Invoice_Core::get_print_url('customer', $order->get_id())); ?>" class="button irantheme-btn irantheme-btn-medium irantheme-btn-dark-outline irantheme-btn-low-radius invoice"><i class="fa-light fa-print"></i><?php esc_html_e('مشاهده فاکتور', IRANTHEME_TEXTDOMAIN); ?></a>
				<?php endif; ?>
			<?php endif; ?>
		</div>
	<?php } ?>
</div>