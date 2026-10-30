<?php

/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/dashboard.php.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @wc-version 4.4.0
 * @author M-MALEKZADEH77
 */

use Irantheme_Dev\Irantheme_Color_Static;
use Irantheme_Dev\Irantheme_Common_Static;
use Irantheme_Dev\Irantheme_Section_Woocommerce;

if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}
$customer_id = get_current_user_id();
$customer = new \WC_Customer($customer_id);
?>
<div class="irantheme-myaccount-dashboard">
	<div class="irantheme-myaccount-dashboard-holder">
		<div class="row">
			<div class="irantheme-myaccount-dashboard-card-holder irantheme-myaccount-dashboard-welcome-card col-lg-6 col-md-12">
				<div class="irantheme-myaccount-dashboard-welcome-card-content">
					<div class="irantheme-myaccount-dashboard-welcome-card-content-profile">
						<?php
						// Get user id
						$user_id = get_current_user_id();
						// Get avatar url user
						$avatar_image_url = irantheme_get_user_avatar_url($user_id);

						// Current user
						$current_user = get_user_by('id', $user_id);
						?>
						<!-- Avatar -->
						<div class="irantheme-myaccount-dashboard-welcome-card-content-profile-avatar">
							<img src="<?php echo esc_url_raw($avatar_image_url); ?>" alt="<?php echo esc_attr('آواتار کاربر'); ?>">
						</div>
						<?php if ($customer->get_first_name() || $customer->get_last_name()): ?>
							<h2 class="irantheme-myaccount-dashboard-welcome-card-content-profile-name"><strong>سـلام، </strong><?php echo esc_html($customer->get_first_name() . ' ' . $customer->get_last_name()); ?><span> عزیز!</span></h2>
							<a href="<?php echo esc_url(wc_get_account_endpoint_url('edit-account')); ?>" class="irantheme-myaccount-dashboard-welcome-card-content-profile-edit">
								<p class="irantheme-myaccount-dashboard-welcome-card-content-profile-edit-title">ویرایش</p>
								<i class="fa-light fa-pen-to-square irantheme-myaccount-dashboard-welcome-card-content-profile-edit-icon"></i>
							</a>
						<?php else: ?>
							<h2 class="irantheme-myaccount-dashboard-welcome-card-content-profile-name">سـلام!</h2>
							<a href="<?php echo esc_url(wc_get_account_endpoint_url('edit-account')); ?>" class="irantheme-myaccount-dashboard-welcome-card-content-profile-edit">
								<p class="irantheme-myaccount-dashboard-welcome-card-content-profile-edit-title">ویرایش</p>
								<i class="fa-light fa-pen-to-square irantheme-myaccount-dashboard-welcome-card-content-profile-edit-icon"></i>
							</a>
						<?php endif; ?>
					</div>
					<div class="irantheme-myaccount-dashboard-welcome-card-content-welcome-text">
						<p class="welcome-text">
							به حساب کاربری خود خوش آمدید. از اینجا می‌توانید وضعیت سفارش‌ها،
							اطلاعات حساب و خریدهای خود را مدیریت کنید.
						</p>
					</div>
					<?php
					$previous_login = (int)get_user_meta(get_current_user_id(), 'previous_login', true);
					$date_now = current_datetime()->getTimestamp();
					$date_diff = $date_now - $previous_login;

					$previous_login_date = '';
					$previous_login_label = '';

					if (! $previous_login) {
						$previous_login_label = 'این اولین ورود شماست!';
					} else {
						if ($date_diff < DAY_IN_SECONDS) {
							$previous_login_label = 'آخرین ورود شما:';
							$previous_login_date = human_time_diff($previous_login, $date_now) . ' پیش';
						} elseif ($date_diff < WEEK_IN_SECONDS) {
							$previous_login_label = 'آخرین ورود شما:';
							$previous_login_date = human_time_diff($previous_login, $date_now) . ' پیش';
						} else {
							$previous_login_label = 'آخرین ورود شما:';
							$previous_login_date = jdate('j F Y ساعت H:i', $previous_login);
							// $previous_login_date = wp_date('j F Y ساعت H:i', $previous_login);
						}
					}
					?>
					<div class="irantheme-myaccount-dashboard-welcome-card-content-meta">
						<p class="last-login">
							<span class="last-login-label"><?php echo esc_html($previous_login_label); ?></span>
							<span class="last-login-date"><?php echo esc_html($previous_login_date); ?></span>
						</p>
						<p class="total-order">
							<span class="total-order-label">تعداد کل سفارش ها:</span>
							<span class="total-order-count"><?php echo irantheme_get_customer_order_count('total'); ?></span>
						</p>
					</div>
				</div>


			</div>
			<div class="irantheme-myaccount-dashboard-card-holder irantheme-myaccount-dashboard-order-summary col-lg-6 col-md-12">
				<div class="irantheme-myaccount-dashboard-order-summary-content">
					<div class="irantheme-myaccount-dashboard-order-summary-content-header">
						<h3 class="card-header">وضعیت سفارش‌های شما</h3>
						<a href="/my-account/orders" class="card-link">مشاهده همه <i class="fa-light fa-angle-left"></i></a>
					</div>

					<div class="irantheme-myaccount-dashboard-order-summary-content-order-status">
						<div class="irantheme-myaccount-dashboard-order-summary-content-order-status-item">
							<span class="irantheme-myaccount-dashboard-order-summary-content-order-status-item-count"><?php echo irantheme_get_customer_order_count('on-hold'); ?></span>
							<span class="irantheme-myaccount-dashboard-order-summary-content-order-status-item-label">در انتظار بررسی</span>
						</div>

						<div class="irantheme-myaccount-dashboard-order-summary-content-order-status-item">
							<span class="irantheme-myaccount-dashboard-order-summary-content-order-status-item-count"><?php echo irantheme_get_customer_order_count('pending'); ?></span>
							<span class="irantheme-myaccount-dashboard-order-summary-content-order-status-item-label">در انتظار پرداخت</span>
						</div>

						<div class="irantheme-myaccount-dashboard-order-summary-content-order-status-item">
							<span class="irantheme-myaccount-dashboard-order-summary-content-order-status-item-count"><?php echo irantheme_get_customer_order_count('processing'); ?></span>
							<span class="irantheme-myaccount-dashboard-order-summary-content-order-status-item-label">در حال پردازش</span>
						</div>

						<div class="irantheme-myaccount-dashboard-order-summary-content-order-status-item">
							<span class="irantheme-myaccount-dashboard-order-summary-content-order-status-item-count"><?php echo irantheme_get_customer_order_count('completed'); ?></span>
							<span class="irantheme-myaccount-dashboard-order-summary-content-order-status-item-label">تکمیل شده</span>
						</div>
					</div>

					<div class="irantheme-myaccount-dashboard-order-summary-content-last-order">
						<p class="irantheme-myaccount-dashboard-order-summary-content-last-order-heading">آخرین سفارش فعال شما</p>
						<?php
						$order_args = [
							'customer_id' => get_current_user_id(),
							'status'      => ['pending', 'processing', 'on-hold'],
							'limit'       => 1,
							'orderby'     => 'date',
							'order'       => 'DESC',
						];
						$customer_last_order = wc_get_orders($order_args);
						$customer_last_order = $customer_last_order[0];
						if ($customer_last_order) :
							$order_id           = $customer_last_order->get_order_number();
							$order_total_html   = $customer_last_order->get_formatted_order_total();
							$order_items_count  = $customer_last_order->get_item_count();
							$order_status_slug  = $customer_last_order->get_status();
							$order_status_name  = wc_get_order_status_name($order_status_slug);
							$order_date   = wp_date('Y/m/d', $customer_last_order->get_date_created()->getTimestamp());

							$view_order_url     = $customer_last_order->get_view_order_url();
						?>
							<div class="table-wrap">
								<table>
									<tbody>
										<tr class="irantheme-myaccount-dashboard-order-summary-content-last-order-details">
											<td class="irantheme-myaccount-dashboard-order-summary-content-last-order-details-icon">
												<a href="<?php echo esc_url($view_order_url); ?>">
													<i class="fa-light fa-hashtag"></i>
												</a>
											</td>
											<td class="irantheme-myaccount-dashboard-order-summary-content-last-order-details-code">
												<a href="<?php echo esc_url($view_order_url); ?>">
													<span><?php echo $order_id; ?></span>
												</a>
											</td>
											<td class="irantheme-myaccount-dashboard-order-summary-content-last-order-details-date">
												<a href="<?php echo esc_url($view_order_url); ?>">
													<span><?php echo $order_date; ?></span>
												</a>
											</td>
											<td class="irantheme-myaccount-dashboard-order-summary-content-last-order-details-invoice">
												<a href="<?php echo esc_url($view_order_url); ?>">
													<?php echo $order_total_html; ?> برای <?php echo $order_items_count; ?> مورد
												</a>
											</td>
											<td class="irantheme-myaccount-dashboard-order-summary-content-last-order-details-status">
												<a href="<?php echo esc_url($view_order_url); ?>">
													<span class="order-status <?php echo esc_attr($order_status_slug); ?>">
														<?php echo $order_status_name; ?>
													</span>
												</a>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						<?php else: ?>
							<div class="table-wrap">
								<table>
									<tbody>
										<tr class="irantheme-myaccount-dashboard-order-summary-content-last-order-details">
											<td class="irantheme-myaccount-dashboard-order-summary-content-last-order-details-empty">
												شما در حال حاضر سفارش فعالی ندارید
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="irantheme-myaccount-dashboard-card-holder irantheme-myaccount-dashboard-last-orders col-12">
				<div class="irantheme-myaccount-dashboard-last-orders-content">
					<p class="irantheme-myaccount-dashboard-last-orders-content-heading">لیست آخرین سفارش ها</p>
					<?php
					$orders = wc_get_orders([
						'customer_id' => get_current_user_id(),
						'limit'       => 5,
						'orderby'     => 'date',
						'order'       => 'DESC',
					]);

					if (!empty($orders)) :
					?>
						<div class="table-wrap">
							<table class="irantheme-myaccount-dashboard-last-orders-content-table">
								<thead>
									<tr class="irantheme-myaccount-dashboard-last-orders-content-table-row-head">
										<th>شماره سفارش</th>
										<th>تاریخ</th>
										<th>وضعیت</th>
										<th>مجموع</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($orders as $order) :

										$order_id          = $order->get_order_number();
										$order_date        = wp_date('Y/m/d', $order->get_date_created()->getTimestamp());
										$order_status_slug = $order->get_status();
										$order_status_name = wc_get_order_status_name($order_status_slug);
										$order_url         = $order->get_view_order_url();
										$order_total_html   = $order->get_formatted_order_total();
										$order_items_count  = $order->get_item_count();
									?>
										<tr class="irantheme-myaccount-dashboard-last-orders-content-table-row">
											<td class="irantheme-myaccount-dashboard-last-orders-content-table-row-col-code">
												<a href="<?php echo esc_url($order_url); ?>">
													<span><?php echo $order_id; ?></span>
												</a>
											</td>
											<td class="irantheme-myaccount-dashboard-last-orders-content-table-row-col-date">
												<span><?php echo $order_date; ?></span>
											</td>
											<td class="irantheme-myaccount-dashboard-last-orders-content-table-row-col-status">
												<span class="order-status <?php echo esc_attr($order_status_slug); ?>">
													<?php echo $order_status_name; ?>
												</span>
											</td>
											<td class="irantheme-myaccount-dashboard-last-orders-content-table-row-col-invoice">
												<span class="invoice-wrap">
													<?php echo $order_total_html; ?> برای <?php echo $order_items_count; ?> مورد
												</span>
											</td>
											<td class="irantheme-myaccount-dashboard-last-orders-content-table-row-col-view">
												<a href="<?php echo esc_url($order_url); ?>">
													<i class="fa-light fa-arrow-up-right-from-square icon"></i>
													مشاهده جزئیات
												</a>
											</td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					<?php else: ?>
						<div class="table-wrap">
							<table class="irantheme-myaccount-dashboard-last-orders-content-table">
								<tbody>
									<tr class="irantheme-myaccount-dashboard-last-orders-content-table-row">
										<td class="irantheme-myaccount-dashboard-last-orders-content-table-row-col-empty">
											هنوز سفارشی ثبت نکرده اید
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="irantheme-myaccount-dashboard-card-holder irantheme-myaccount-dashboard-latest-views col-xl-6 col-lg-12 col-md-12">
				<div class="irantheme-myaccount-dashboard-latest-views-content">
					<p class="irantheme-myaccount-dashboard-latest-views-content-heading">اخیرا مشاهده کرده اید</p>
					<div class="irantheme-myaccount-dashboard-latest-views-content-list irantheme-myaccount-dashboard-horizontal-product-list">
						<?php
						$recently_viewed_products_ids = irantheme_get_recently_viewed_products();
						if (empty($recently_viewed_products_ids)) : ?>
							<div class="irantheme-myaccount-dashboard-latest-views-content-list-item irantheme-myaccount-dashboard-horizontal-product-list-item empty">
								<p class="desc">هنوز محصولی مشاهده نکرده‌اید.</p>
							</div>
							<?php
						else:
							$recently_viewed_products_args = array(
								'post_type' => 'product',
								'post__in' => $recently_viewed_products_ids,
								'post_status'    => array('publish'),
								'orderby'        => 'post__in',
							);
							$recently_viewed_products_products = new WP_Query($recently_viewed_products_args);
							if ($recently_viewed_products_products->have_posts()):
								while ($recently_viewed_products_products->have_posts()):
									$recently_viewed_products_products->the_post();
									global $product;
									$is_call_for_price = get_post_meta($product->get_id(), 'irantheme_product_meta_is_call_for_price', true);
							?>
									<div class="irantheme-myaccount-dashboard-latest-views-content-list-item irantheme-myaccount-dashboard-horizontal-product-list-item" data-product="<?php echo get_the_ID(); ?>">
										<div class="irantheme-myaccount-dashboard-latest-views-content-list-item-wrapper irantheme-myaccount-dashboard-horizontal-product-list-item-wrapper <?php echo $product->get_stock_status(); ?>">
											<div class="irantheme-myaccount-dashboard-latest-views-content-list-item-thumbnail irantheme-myaccount-dashboard-horizontal-product-list-item-thumbnail">
												<a href="<?php the_permalink(); ?>">
													<img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail'); ?>" alt="<?php echo get_the_title(); ?>">
												</a>
											</div>
											<div class="irantheme-myaccount-dashboard-latest-views-content-list-item-content irantheme-myaccount-dashboard-horizontal-product-list-item-content">
												<div class="irantheme-myaccount-dashboard-latest-views-content-list-item-content-heading irantheme-myaccount-dashboard-horizontal-product-list-item-content-heading">
													<a href="<?php the_permalink(); ?>">
														<?php the_title(); ?>
													</a>
												</div>
												<div class="irantheme-myaccount-dashboard-latest-views-content-list-item-content-info irantheme-myaccount-dashboard-horizontal-product-list-item-content-info">
													<div class="irantheme-myaccount-dashboard-latest-views-content-list-item-content-info-price irantheme-myaccount-dashboard-horizontal-product-list-item-content-info-price">
														<?php if ($is_call_for_price != 'yes'): ?>
															<?php Irantheme_Section_Woocommerce::display_product_price($product, true); ?>
														<?php else: ?>
															<span class="price irantheme-woocommerce-products-price">تماس بگیرید</span>
														<?php endif; ?>
													</div>
													<?php if ($product->get_stock_status() == 'onbackorder'): ?>
														<span class="stock-status irantheme-woocommerce-products-stock-status">پیش خرید!</span>
													<?php endif; ?>
												</div>
											</div>
										</div>
									</div>
						<?php
								endwhile;
								wp_reset_postdata();
							endif;
						endif;
						?>
					</div>
				</div>
			</div>
			<div class="irantheme-myaccount-dashboard-card-holder irantheme-myaccount-dashboard-latest-favorites col-xl-6 col-lg-12 col-md-12">
				<div class="irantheme-myaccount-dashboard-latest-favorites-content">
					<p class="irantheme-myaccount-dashboard-latest-favorites-content-heading">علاقه‌مندی های اخیر</p>
					<div class="irantheme-myaccount-dashboard-latest-favorites-content-list irantheme-myaccount-dashboard-horizontal-product-list">
						<?php
						$wishlist_ids_raw = get_user_meta(get_current_user_id(), 'wishlist', true);
						$wishlist_ids = explode(',', $wishlist_ids_raw);
						$wishlist_ids = array_slice($wishlist_ids, -3, null, true);
						$wishlist_ids = array_reverse($wishlist_ids);
						$wishlist_args = array(
							'post_type' => 'product',
							'post__in' => $wishlist_ids,
							'post_status'    => array('publish'),
							'orderby'        => 'post__in',
						);
						$wishlist_products = new WP_Query($wishlist_args);
						if ($wishlist_products->have_posts()):
							while ($wishlist_products->have_posts()):
								$wishlist_products->the_post();
								global $product;
								$is_call_for_price = get_post_meta($product->get_id(), 'irantheme_product_meta_is_call_for_price', true);
						?>
								<div class="irantheme-myaccount-dashboard-latest-favorites-content-list-item irantheme-myaccount-dashboard-horizontal-product-list-item" data-product="<?php echo get_the_ID(); ?>">
									<div class="irantheme-myaccount-dashboard-latest-favorites-content-list-item-wrapper irantheme-myaccount-dashboard-horizontal-product-list-item-wrapper <?php echo $product->get_stock_status(); ?>">
										<div class="irantheme-myaccount-dashboard-latest-favorites-content-list-item-thumbnail irantheme-myaccount-dashboard-horizontal-product-list-item-thumbnail">
											<a href="<?php the_permalink(); ?>">
												<img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail'); ?>" alt="<?php echo get_the_title(); ?>">
											</a>
										</div>
										<div class="irantheme-myaccount-dashboard-latest-favorites-content-list-item-content irantheme-myaccount-dashboard-horizontal-product-list-item-content">
											<div class="irantheme-myaccount-dashboard-latest-favorites-content-list-item-content-heading irantheme-myaccount-dashboard-horizontal-product-list-item-content-heading">
												<a href="<?php the_permalink(); ?>">
													<?php the_title(); ?>
												</a>
											</div>
											<div class="irantheme-myaccount-dashboard-latest-favorites-content-list-item-content-info irantheme-myaccount-dashboard-horizontal-product-list-item-content-info">
												<div class="irantheme-myaccount-dashboard-latest-favorites-content-list-item-content-info-price irantheme-myaccount-dashboard-horizontal-product-list-item-content-info-price">
													<?php if ($is_call_for_price != 'yes'): ?>
														<?php Irantheme_Section_Woocommerce::display_product_price($product, true); ?>
													<?php else: ?>
														<span class="price irantheme-woocommerce-products-price">تماس بگیرید</span>
													<?php endif; ?>
												</div>
												<?php if ($product->get_stock_status() == 'onbackorder'): ?>
													<span class="stock-status irantheme-woocommerce-products-stock-status">پیش خرید!</span>
												<?php endif; ?>
											</div>
										</div>
									</div>
								</div>
							<?php
							endwhile;
							wp_reset_postdata();
						else:
							?>
							<div class="irantheme-myaccount-dashboard-latest-favorites-content-list-item irantheme-myaccount-dashboard-horizontal-product-list-item empty">
								<p class="desc">لیست علاقه مندی های شما خالی می باشد.</p>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
</div>
<?php
/**
 * My Account dashboard.
 *
 * @since 2.6.0
 */
do_action('woocommerce_account_dashboard');

/**
 * Deprecated woocommerce_before_my_account action.
 *
 * @deprecated 2.6.0
 */
do_action('woocommerce_before_my_account');

/**
 * Deprecated woocommerce_after_my_account action.
 *
 * @deprecated 2.6.0
 */
do_action('woocommerce_after_my_account');
	
	/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
