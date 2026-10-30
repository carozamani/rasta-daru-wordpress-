<?php
/**
 * Order received (success) message — only for non-failed orders.
 *
 * @see parent: wp_storeplus/woocommerce/checkout/order-received.php
 * @package StorePlus_Child
 * @var WC_Order|false $order
 */

defined( 'ABSPATH' ) || exit;

$inv_visibility = get_option( 'irantheme_wc_invoice_customer_thankyou_btn_visibility_checkbox', 'on' );

if ( ! $order || ! is_a( $order, 'WC_Order' ) ) {
	wc_print_notice( __( 'اطلاعات سفارش در دسترس نیست. لطفاً با پشتیبانی تماس بگیرید.', IRANTHEME_TEXTDOMAIN ), 'error' );
	return;
}

/* Safety: failed/cancelled must never render the green success block. */
if ( function_exists( 'storeplus_child_order_result_is_failure' ) && storeplus_child_order_result_is_failure( $order ) ) {
	return;
}

$status      = $order->get_status();
$status_map  = array(
	'pending'    => 'در انتظار پرداخت',
	'processing' => 'در حال پردازش',
	'on-hold'    => 'در انتظار بررسی',
	'completed'  => 'تکمیل شده',
	'cancelled'  => 'لغو شده',
	'refunded'   => 'مسترد شده',
	'failed'     => 'ناموفق',
);
$status_text = isset( $status_map[ $status ] ) ? $status_map[ $status ] : $status;
$status_html = '<span class="order-status ' . esc_attr( $status ) . '">' . esc_html( $status_text ) . '</span>';
$order_no    = '<span class="order-number">' . esc_html( $order->get_order_number() ) . '</span>';
$message     = sprintf( 'سفارش شما با شماره پیگیری %s ثبت شده و هم اکنون در وضعیت %s می باشد.', $order_no, $status_html );
$message     = apply_filters( 'woocommerce_thankyou_order_received_text', $message, $order );
?>

<div class="irantheme-wc-checkout-order-success-message">
	<div class="irantheme-wc-checkout-order-success-message-icon">
		<i class="fa-light fa-circle-check" aria-hidden="true"></i>
	</div>
	<p class="irantheme-wc-checkout-order-success-message-title">
		سفارش شما با موفقیت ثبت شد.
	</p>
	<p class="irantheme-wc-checkout-order-success-message-desc">
		<?php echo $message; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built with escaped fragments above ?>
	</p>
	<div class="irantheme-wc-checkout-order-success-message-meta">
		<div class="meta-item order-no">
			<i class="meta-icon fa-light fa-clipboard-list" aria-hidden="true"></i>
			<div class="meta-text">
				<p class="meta-text-title">شماره سفارش</p>
				<p class="meta-text-value"><?php echo esc_html( $order->get_order_number() ); ?></p>
			</div>
		</div>
		<div class="meta-item order-reg-date">
			<i class="meta-icon fa-light fa-calendar-clock" aria-hidden="true"></i>
			<div class="meta-text">
				<p class="meta-text-title">تاریخ ثبت سفارش</p>
				<p class="meta-text-value"><?php echo esc_html( wc_format_datetime( $order->get_date_created(), 'H:i - Y/m/d' ) ); ?></p>
			</div>
		</div>
		<div class="meta-item order-payment">
			<i class="meta-icon fa-light fa-credit-card" aria-hidden="true"></i>
			<div class="meta-text">
				<p class="meta-text-title">روش پرداخت</p>
				<p class="meta-text-value"><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></p>
			</div>
		</div>
	</div>
	<div class="irantheme-wc-checkout-order-success-message-btn">
		<?php if ( is_user_logged_in() ) : ?>
			<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>" class="button irantheme-btn irantheme-btn-medium irantheme-btn-low-radius order-details">
				<i class="fa-light fa-arrow-right" aria-hidden="true"></i><?php esc_html_e( 'مشاهده جزییات سفارش', IRANTHEME_TEXTDOMAIN ); ?>
			</a>
			<?php if ( 'on' === $inv_visibility && $order->has_status( array( 'completed', 'processing', 'on-hold', 'pending' ) ) && class_exists( '\Irantheme_Dev\Irantheme_Woocommerce_Invoice_Core' ) ) : ?>
				<a href="<?php echo esc_url( \Irantheme_Dev\Irantheme_Woocommerce_Invoice_Core::get_print_url( 'customer', $order->get_id() ) ); ?>" class="button irantheme-btn irantheme-btn-medium irantheme-btn-dark-outline irantheme-btn-low-radius invoice">
					<i class="fa-light fa-print" aria-hidden="true"></i><?php esc_html_e( 'مشاهده فاکتور', IRANTHEME_TEXTDOMAIN ); ?>
				</a>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</div>
