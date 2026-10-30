<?php
/**
 * Thankyou page — success and failure are separate UIs.
 *
 * @see parent: wp_storeplus/woocommerce/checkout/thankyou.php
 * @package StorePlus_Child
 * @var WC_Order $order
 */

defined( 'ABSPATH' ) || exit;

$is_failure = function_exists( 'storeplus_child_order_result_is_failure' )
	? storeplus_child_order_result_is_failure( $order )
	: ( $order && $order->has_status( 'failed' ) );
?>

<div class="woocommerce-order irantheme-wc-checkout-order-result <?php echo $is_failure ? 'sp-order-result--failed' : 'sp-order-result--success'; ?>">

	<?php if ( $order ) : ?>

		<?php do_action( 'woocommerce_before_thankyou', $order->get_id() ); ?>

		<?php if ( $is_failure ) : ?>

			<div class="irantheme-wc-checkout-order-failed-message sp-order-failed">
				<div class="irantheme-wc-checkout-order-failed-message-icon sp-order-failed__icon" aria-hidden="true">
					<i class="fa-light fa-circle-xmark"></i>
				</div>
				<p class="irantheme-wc-checkout-order-failed-message-title sp-order-failed__title">
					<?php
					echo esc_html(
						$order->has_status( 'cancelled' )
							? 'سفارش لغو شد.'
							: 'پرداخت ناموفق بود.'
					);
					?>
				</p>
				<p class="irantheme-wc-checkout-order-failed-message-desc sp-order-failed__desc">
					<?php
					if ( $order->has_status( 'cancelled' ) ) {
						echo esc_html( 'این سفارش لغو شده است. در صورت نیاز می‌توانید دوباره خرید کنید.' );
					} else {
						echo esc_html( 'متأسفانه پرداخت توسط درگاه تأیید نشد. می‌توانید دوباره تلاش کنید یا با پشتیبانی تماس بگیرید.' );
					}
					?>
				</p>

				<div class="irantheme-wc-checkout-order-success-message-meta sp-order-failed__meta">
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
					<?php if ( $order->get_payment_method_title() ) : ?>
						<div class="meta-item order-payment">
							<i class="meta-icon fa-light fa-credit-card" aria-hidden="true"></i>
							<div class="meta-text">
								<p class="meta-text-title">روش پرداخت</p>
								<p class="meta-text-value"><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></p>
							</div>
						</div>
					<?php endif; ?>
				</div>

				<div class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions irantheme-wc-checkout-order-failed-message-actions sp-order-failed__actions">
					<?php if ( $order->needs_payment() ) : ?>
						<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button irantheme-btn irantheme-btn-medium pay">
							<i class="fa-light fa-credit-card" aria-hidden="true"></i><?php echo esc_html( 'تلاش مجدد پرداخت' ); ?>
						</a>
					<?php endif; ?>
					<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="button irantheme-btn irantheme-btn-medium irantheme-btn-dark-outline">
						<i class="fa-light fa-shop" aria-hidden="true"></i><?php echo esc_html( 'بازگشت به فروشگاه' ); ?>
					</a>
					<?php if ( is_user_logged_in() ) : ?>
						<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button irantheme-btn irantheme-btn-medium account">
							<i class="fa-light fa-address-card" aria-hidden="true"></i><?php esc_html_e( 'حساب کاربری', 'woocommerce' ); ?>
						</a>
					<?php endif; ?>
				</div>
			</div>

		<?php else : ?>

			<?php wc_get_template( 'checkout/order-received.php', array( 'order' => $order ) ); ?>

		<?php endif; ?>

		<?php
		remove_action( 'woocommerce_thankyou', 'woocommerce_order_details_table', 10 );
		do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() );
		do_action( 'woocommerce_thankyou', $order->get_id() );
		?>

	<?php else : ?>

		<?php wc_get_template( 'checkout/order-received.php', array( 'order' => false ) ); ?>

	<?php endif; ?>

</div>
