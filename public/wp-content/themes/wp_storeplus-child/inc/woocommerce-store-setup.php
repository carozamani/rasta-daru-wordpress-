<?php
/**
 * WooCommerce store setup for رستا دارو (domain-independent).
 * Currency, Iran-only selling/shipping, tax stance, Persian emails, WC pages.
 *
 * Run: Tools → تنظیمات فروشگاه ووکامرس
 * Or:  /wp-content/themes/wp_storeplus-child/tools/run-wc-setup.php?force=1 (local admin)
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const SP_WC_SETUP_OPTION = 'sp_wc_store_setup_v1';

add_action(
	'admin_menu',
	static function () {
		add_management_page(
			'تنظیمات فروشگاه ووکامرس',
			'تنظیمات فروشگاه ووکامرس',
			'manage_options',
			'sp-wc-setup',
			'sp_wc_setup_admin_page'
		);
	}
);

/**
 * Admin UI.
 */
function sp_wc_setup_admin_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$ran = null;
	if ( isset( $_POST['sp_wc_setup_run'] ) && check_admin_referer( 'sp_wc_setup' ) ) {
		$ran = sp_wc_setup_run( ! empty( $_POST['sp_force'] ) );
	}
	echo '<div class="wrap"><h1>تنظیمات فروشگاه ووکامرس — رستا دارو</h1>';
	if ( is_array( $ran ) ) {
		echo '<div class="notice notice-success"><p><strong>انجام شد.</strong></p><ul>';
		foreach ( $ran as $line ) {
			echo '<li>' . esc_html( $line ) . '</li>';
		}
		echo '</ul></div>';
	}
	$done = get_option( SP_WC_SETUP_OPTION );
	if ( $done ) {
		echo '<p>آخرین اجرا: <code>' . esc_html( (string) $done ) . '</code></p>';
	}
	echo '<form method="post">';
	wp_nonce_field( 'sp_wc_setup' );
	echo '<p>واحد پول (IRT)، فروش/ارسال فقط ایران، مناطق حمل، متن ایمیل‌های سفارش، و اتصال صفحات ووکامرس.</p>';
	echo '<p><label><input type="checkbox" name="sp_force" value="1"> اجرای مجدد اجباری</label></p>';
	echo '<p><button class="button button-primary" name="sp_wc_setup_run" value="1">اعمال تنظیمات</button></p>';
	echo '</form></div>';
}

/**
 * Run all WC setup steps.
 *
 * @param bool $force Force re-run.
 * @return string[]
 */
function sp_wc_setup_run( $force = false ) {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return array( 'WooCommerce فعال نیست.' );
	}
	if ( ! $force && get_option( SP_WC_SETUP_OPTION ) ) {
		return array( 'قبلاً اجرا شده — برای تکرار، «اجرای مجدد اجباری» را بزنید.' );
	}

	$log = array_merge(
		sp_wc_setup_general(),
		sp_wc_setup_tax(),
		sp_wc_setup_shipping(),
		sp_wc_setup_emails(),
		sp_wc_setup_pages(),
		sp_wc_setup_accounts_checkout()
	);

	update_option( SP_WC_SETUP_OPTION, gmdate( 'c' ), false );
	$log[] = 'گزینه ' . SP_WC_SETUP_OPTION . ' ذخیره شد.';

	if ( function_exists( 'wc_delete_product_transients' ) ) {
		wc_delete_product_transients();
	}
	delete_transient( 'woocommerce_cache_excluded_uris' );

	return $log;
}

/**
 * Currency, store address, sell/ship only Iran.
 *
 * @return string[]
 */
function sp_wc_setup_general() {
	update_option( 'woocommerce_currency', 'IRT' );
	update_option( 'woocommerce_currency_pos', 'right_space' );
	update_option( 'woocommerce_price_thousand_sep', '.' );
	update_option( 'woocommerce_price_decimal_sep', ',' );
	update_option( 'woocommerce_price_num_decimals', '0' );

	update_option( 'woocommerce_default_country', 'IR:ESF' );
	update_option( 'woocommerce_store_address', 'خیابان مارنان، پل مارنان' );
	update_option( 'woocommerce_store_address_2', '' );
	update_option( 'woocommerce_store_city', 'اصفهان' );
	update_option( 'woocommerce_store_postcode', '81736' );

	update_option( 'woocommerce_allowed_countries', 'specific' );
	update_option( 'woocommerce_specific_allowed_countries', array( 'IR' ) );
	update_option( 'woocommerce_ship_to_countries', 'specific' );
	update_option( 'woocommerce_specific_ship_to_countries', array( 'IR' ) );
	update_option( 'woocommerce_default_customer_address', 'base' );
	update_option( 'woocommerce_calc_shipping', 'yes' );
	update_option( 'woocommerce_shipping_cost_requires_address', 'no' );
	update_option( 'woocommerce_enable_shipping_calc', 'yes' );

	update_option( 'woocommerce_weight_unit', 'g' );
	update_option( 'woocommerce_dimension_unit', 'cm' );

	update_option( 'woocommerce_enable_coupons', 'yes' );
	update_option( 'woocommerce_calc_discounts_sequentially', 'no' );

	return array( 'واحد پول IRT، فروش و ارسال فقط ایران، آدرس فروشگاه اصفهان تنظیم شد.' );
}

/**
 * Tax stance for Iranian OTC pharmacy: final shelf prices, no separate VAT line.
 * (Many OTC/supplement SKUs are sold as final price; Rx is out of scope online.)
 *
 * @return string[]
 */
function sp_wc_setup_tax() {
	update_option( 'woocommerce_calc_taxes', 'no' );
	update_option( 'woocommerce_prices_include_tax', 'yes' );
	update_option( 'woocommerce_tax_display_shop', 'incl' );
	update_option( 'woocommerce_tax_display_cart', 'incl' );
	update_option( 'woocommerce_tax_total_display', 'single' );

	return array( 'مالیات جداگانه خاموش است؛ قیمت‌های نمایش‌داده‌شده نهایی فرض می‌شوند (مناسب مکمل/OTC).' );
}

/**
 * Create Iran shipping zones if missing.
 *
 * @return string[]
 */
function sp_wc_setup_shipping() {
	if ( ! class_exists( 'WC_Shipping_Zones' ) ) {
		return array( 'کلاس مناطق حمل در دسترس نیست.' );
	}

	$existing = WC_Shipping_Zones::get_zones();
	$names    = array();
	foreach ( $existing as $z ) {
		$names[] = $z['zone_name'];
	}

	$created = array();

	if ( ! in_array( 'اصفهان (پیک و پست)', $names, true ) ) {
		$zone = new WC_Shipping_Zone();
		$zone->set_zone_name( 'اصفهان (پیک و پست)' );
		$zone->set_zone_order( 1 );
		$zone->save();
		$zone->add_location( 'IR:ESF', 'state' );

		$flat_id = $zone->add_shipping_method( 'flat_rate' );
		sp_wc_setup_method_settings(
			$flat_id,
			array(
				'title'      => 'پیک موتوری اصفهان',
				'cost'       => '65000',
				'tax_status' => 'none',
			)
		);

		$post_id = $zone->add_shipping_method( 'flat_rate' );
		sp_wc_setup_method_settings(
			$post_id,
			array(
				'title'      => 'پست پیشتاز اصفهان',
				'cost'       => '45000',
				'tax_status' => 'none',
			)
		);

		$free_id = $zone->add_shipping_method( 'free_shipping' );
		sp_wc_setup_method_settings(
			$free_id,
			array(
				'title'            => 'ارسال رایگان اصفهان (بالای ۲ میلیون)',
				'requires'         => 'min_amount',
				'min_amount'       => '2000000',
				'ignore_discounts' => 'no',
			)
		);
		$created[] = 'اصفهان';
	}

	if ( ! in_array( 'سراسر ایران', $names, true ) ) {
		$zone = new WC_Shipping_Zone();
		$zone->set_zone_name( 'سراسر ایران' );
		$zone->set_zone_order( 10 );
		$zone->save();
		$zone->add_location( 'IR', 'country' );

		$post_id = $zone->add_shipping_method( 'flat_rate' );
		sp_wc_setup_method_settings(
			$post_id,
			array(
				'title'      => 'پست پیشتاز سراسر کشور',
				'cost'       => '95000',
				'tax_status' => 'none',
			)
		);

		$tipax_id = $zone->add_shipping_method( 'flat_rate' );
		sp_wc_setup_method_settings(
			$tipax_id,
			array(
				'title'      => 'تیپاکس / باربری',
				'cost'       => '145000',
				'tax_status' => 'none',
			)
		);

		$free_id = $zone->add_shipping_method( 'free_shipping' );
		sp_wc_setup_method_settings(
			$free_id,
			array(
				'title'            => 'ارسال رایگان (بالای ۳٫۵ میلیون)',
				'requires'         => 'min_amount',
				'min_amount'       => '3500000',
				'ignore_discounts' => 'no',
			)
		);
		$created[] = 'سراسر ایران';
	}

	/* Rest of world: disable / leave empty so checkout stays IR-only. */
	$rest = new WC_Shipping_Zone( 0 );
	foreach ( $rest->get_shipping_methods( true ) as $method ) {
		$opt = get_option( 'woocommerce_' . $method->id . '_' . $method->instance_id . '_settings', array() );
		if ( ! is_array( $opt ) ) {
			$opt = array();
		}
		$opt['enabled'] = 'no';
		update_option( 'woocommerce_' . $method->id . '_' . $method->instance_id . '_settings', $opt );
	}

	if ( ! $created && $existing ) {
		return array( 'مناطق حمل از قبل وجود داشت؛ تغییری لازم نبود.' );
	}
	return array( 'مناطق حمل ساخته شد: ' . ( $created ? implode( '، ', $created ) : 'بدون تغییر' ) . '.' );
}

/**
 * Persist shipping method instance settings.
 *
 * @param int   $instance_id Instance ID.
 * @param array $settings    Settings.
 */
function sp_wc_setup_method_settings( $instance_id, array $settings ) {
	global $wpdb;
	if ( ! $instance_id ) {
		return;
	}
	$row = $wpdb->get_row( $wpdb->prepare( "SELECT method_id FROM {$wpdb->prefix}woocommerce_shipping_zone_methods WHERE instance_id = %d", $instance_id ) );
	if ( ! $row ) {
		return;
	}
	$method_id = $row->method_id;
	$key       = 'woocommerce_' . $method_id . '_' . $instance_id . '_settings';
	$current   = get_option( $key, array() );
	if ( ! is_array( $current ) ) {
		$current = array();
	}
	$settings['enabled'] = 'yes';
	update_option( $key, array_merge( $current, $settings ) );

	$wpdb->update(
		$wpdb->prefix . 'woocommerce_shipping_zone_methods',
		array( 'is_enabled' => 1 ),
		array( 'instance_id' => $instance_id ),
		array( '%d' ),
		array( '%d' )
	);
}

/**
 * Persian email branding + subjects/headings/additional content.
 * Local SMTP may not deliver — texts are prepared for production.
 *
 * @return string[]
 */
function sp_wc_setup_emails() {
	$from_name = 'رستا دارو';
	$from_mail = get_option( 'admin_email' );
	if ( ! is_email( $from_mail ) || false !== strpos( $from_mail, 'irantheme' ) ) {
		$from_mail = 'noreply@rastadarou.ir';
	}

	update_option( 'woocommerce_email_from_name', $from_name );
	update_option( 'woocommerce_email_from_address', $from_mail );
	update_option( 'woocommerce_email_header_image', '' );
	update_option( 'woocommerce_email_footer_text', 'رستا دارو — فروشگاه آنلاین محصولات سلامت و مکمل<br>پشتیبانی: ۰۳۱-۳۳۶۱۲۳۴۵ | info@rastadarou.ir<br>اصفهان، خیابان مارنان' );
	update_option( 'woocommerce_email_base_color', '#0b6b45' );
	update_option( 'woocommerce_email_background_color', '#f5f7f6' );
	update_option( 'woocommerce_email_body_background_color', '#ffffff' );
	update_option( 'woocommerce_email_text_color', '#1a1a1a' );

	$support = "\n\nدر صورت نیاز با پشتیبانی رستا دارو تماس بگیرید:\n۰۳۱-۳۳۶۱۲۳۴۵\ninfo@rastadarou.ir";

	$emails = array(
		'new_order'                 => array(
			'enabled'             => 'yes',
			'subject'             => '[{site_title}] سفارش جدید #{order_number}',
			'heading'             => 'سفارش جدید #{order_number}',
			'additional_content'  => 'یک سفارش جدید در رستا دارو ثبت شد. جزئیات را در پیشخوان بررسی کنید.',
			'recipient'           => get_option( 'admin_email' ),
		),
		'cancelled_order'           => array(
			'enabled'            => 'yes',
			'subject'            => '[{site_title}] لغو سفارش #{order_number}',
			'heading'            => 'سفارش #{order_number} لغو شد',
			'additional_content' => 'این سفارش لغو شده است.',
		),
		'failed_order'              => array(
			'enabled'            => 'yes',
			'subject'            => '[{site_title}] پرداخت ناموفق سفارش #{order_number}',
			'heading'            => 'پرداخت ناموفق #{order_number}',
			'additional_content' => 'پرداخت این سفارش ناموفق بوده است.',
		),
		'customer_on_hold_order'    => array(
			'enabled'            => 'yes',
			'subject'            => 'سفارش شما در {site_title} در انتظار بررسی است — #{order_number}',
			'heading'            => 'سفارش شما ثبت شد و در انتظار تایید است',
			'additional_content' => 'به‌محض تایید پرداخت/موجودی، وضعیت سفارش به‌روز می‌شود.' . $support,
		),
		'customer_processing_order' => array(
			'enabled'            => 'yes',
			'subject'            => 'سفارش #{order_number} در {site_title} در حال آماده‌سازی است',
			'heading'            => 'از خرید شما متشکریم',
			'additional_content' => 'سفارش شما دریافت شد و در حال آماده‌سازی برای ارسال است.' . $support,
		),
		'customer_completed_order'  => array(
			'enabled'            => 'yes',
			'subject'            => 'سفارش #{order_number} از {site_title} تکمیل شد',
			'heading'            => 'سفارش شما ارسال / تکمیل شد',
			'additional_content' => 'امیدواریم از خریدتان راضی باشید. در صورت مشکل در بسته‌بندی یا کالا، حداکثر ۷۲ ساعت با پشتیبانی هماهنگ کنید.' . $support,
		),
		'customer_refunded_order'   => array(
			'enabled'            => 'yes',
			'subject'            => 'بازگشت وجه سفارش #{order_number} در {site_title}',
			'heading'            => 'وجه سفارش شما برگشت داده شد',
			'additional_content' => 'بازگشت وجه طبق سیاست فروشگاه انجام شده است.' . $support,
		),
		'customer_invoice'          => array(
			'enabled'            => 'yes',
			'subject'            => 'فاکتور سفارش #{order_number} — {site_title}',
			'heading'            => 'فاکتور سفارش #{order_number}',
			'additional_content' => 'جزئیات فاکتور در ادامه آمده است.' . $support,
		),
		'customer_note'             => array(
			'enabled'            => 'yes',
			'subject'            => 'یادداشت جدید درباره سفارش شما در {site_title}',
			'heading'            => 'یادداشت سفارش',
			'additional_content' => $support,
		),
		'customer_reset_password'   => array(
			'enabled'            => 'yes',
			'subject'            => 'بازنشانی رمز عبور — {site_title}',
			'heading'            => 'بازنشانی رمز عبور',
			'additional_content' => 'اگر این درخواست از طرف شما نبوده، این پیام را نادیده بگیرید.',
		),
		'customer_new_account'      => array(
			'enabled'            => 'yes',
			'subject'            => 'خوش آمدید به {site_title}',
			'heading'            => 'حساب کاربری شما ساخته شد',
			'additional_content' => 'اکنون می‌توانید سفارش‌های دارو و مکمل را از حساب کاربری پیگیری کنید.' . $support,
		),
		'customer_failed_order'     => array(
			'enabled'            => 'yes',
			'subject'            => 'پرداخت سفارش #{order_number} در {site_title} ناموفق بود',
			'heading'            => 'پرداخت ناموفق',
			'additional_content' => 'می‌توانید دوباره از صفحه سفارش برای پرداخت اقدام کنید.' . $support,
		),
		'customer_cancelled_order'  => array(
			'enabled'            => 'yes',
			'subject'            => 'سفارش #{order_number} در {site_title} لغو شد',
			'heading'            => 'سفارش لغو شد',
			'additional_content' => 'اگر فکر می‌کنید اشتباهی رخ داده، با پشتیبانی تماس بگیرید.' . $support,
		),
	);

	$count = 0;
	foreach ( $emails as $id => $fields ) {
		$key = 'woocommerce_' . $id . '_settings';
		$cur = get_option( $key, array() );
		if ( ! is_array( $cur ) ) {
			$cur = array();
		}
		update_option( $key, array_merge( $cur, $fields ) );
		$count++;
	}

	/* Keep emails "enabled" at system level; delivery depends on host SMTP. */
	update_option( 'woocommerce_email_merchant_notification_settings', array( 'enabled' => 'yes' ) );

	return array(
		sprintf( 'برندینگ و متن فارسی %d قالب ایمیل سفارش آماده شد (از نام: %s).', $count, $from_name ),
		'توجه: روی لوکال ممکن است ایمیل واقعاً ارسال نشود؛ برای پروداکشن SMTP (مثلاً WP Mail SMTP) وصل کنید.',
	);
}

/**
 * Ensure WC core pages exist, published, and contain the right shortcode/block.
 *
 * @return string[]
 */
function sp_wc_setup_pages() {
	$map = array(
		'shop'      => array(
			'option'  => 'woocommerce_shop_page_id',
			'title'   => 'فروشگاه',
			'slug'    => 'shop',
			'content' => '', // archive; content optional
		),
		'cart'      => array(
			'option'  => 'woocommerce_cart_page_id',
			'title'   => 'سبد خرید',
			'slug'    => 'cart',
			'content' => "<!-- wp:woocommerce/cart -->\n<div class=\"wp-block-woocommerce-cart alignwide is-loading\"><div class=\"wp-block-woocommerce-cart\" data-block-name=\"woocommerce/cart\"></div></div>\n<!-- /wp:woocommerce/cart -->",
			'short'   => '[woocommerce_cart]',
		),
		'checkout'  => array(
			'option'  => 'woocommerce_checkout_page_id',
			'title'   => 'پرداخت',
			'slug'    => 'checkout',
			'content' => "<!-- wp:woocommerce/checkout -->\n<div class=\"wp-block-woocommerce-checkout is-loading\" data-block-name=\"woocommerce/checkout\"></div>\n<!-- /wp:woocommerce/checkout -->",
			'short'   => '[woocommerce_checkout]',
		),
		'myaccount' => array(
			'option'  => 'woocommerce_myaccount_page_id',
			'title'   => 'حساب کاربری',
			'slug'    => 'my-account',
			'content' => '[woocommerce_my_account]',
			'short'   => '[woocommerce_my_account]',
		),
	);

	$log_bits = array();
	foreach ( $map as $key => $cfg ) {
		$id = (int) get_option( $cfg['option'] );
		if ( $id && 'publish' === get_post_status( $id ) ) {
			$post    = get_post( $id );
			$content = (string) $post->post_content;
			$needs   = false;
			if ( 'shop' !== $key ) {
				$short = $cfg['short'] ?? '';
				$has   = ( $short && false !== strpos( $content, $short ) )
					|| false !== strpos( $content, 'woocommerce/' . ( 'myaccount' === $key ? 'my-account' : $key ) )
					|| false !== strpos( $content, 'wp-block-woocommerce-' . $key );
				if ( ! $has && ! empty( $cfg['content'] ) ) {
					wp_update_post(
						array(
							'ID'           => $id,
							'post_content' => $cfg['content'],
							'post_title'   => $cfg['title'],
						)
					);
					$needs = true;
				} elseif ( $post->post_title !== $cfg['title'] && in_array( $key, array( 'myaccount' ), true ) ) {
					wp_update_post(
						array(
							'ID'         => $id,
							'post_title' => $cfg['title'],
						)
					);
				}
			}
			$log_bits[] = $cfg['title'] . ' (#' . $id . ')' . ( $needs ? ' محتوا ترمیم شد' : ' OK' );
			continue;
		}

		/* Create missing page. */
		$new_id = wp_insert_post(
			array(
				'post_title'   => $cfg['title'],
				'post_name'    => $cfg['slug'],
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'post_content' => $cfg['content'],
			),
			true
		);
		if ( ! is_wp_error( $new_id ) && $new_id ) {
			update_option( $cfg['option'], $new_id );
			$log_bits[] = $cfg['title'] . ' ساخته شد (#' . $new_id . ')';
		} else {
			$log_bits[] = $cfg['title'] . ' ساخته نشد';
		}
	}

	/* Terms already from content finisher. */
	$terms = (int) get_option( 'woocommerce_terms_page_id' );
	if ( ! $terms ) {
		$p = get_page_by_path( 'ghavanin' );
		if ( $p ) {
			update_option( 'woocommerce_terms_page_id', (string) $p->ID );
			$terms = (int) $p->ID;
		}
	}
	$log_bits[] = $terms ? 'صفحه قوانین متصل است (#' . $terms . ')' : 'صفحه قوانین هنوز تنظیم نشده';

	update_option( 'woocommerce_cart_redirect_after_add', 'no' );
	update_option( 'woocommerce_enable_ajax_add_to_cart', 'yes' );

	return array( 'صفحات ووکامرس: ' . implode( ' | ', $log_bits ) );
}

/**
 * Accounts + checkout behaviour for pharmacy store.
 *
 * @return string[]
 */
function sp_wc_setup_accounts_checkout() {
	update_option( 'woocommerce_enable_guest_checkout', 'no' );
	update_option( 'woocommerce_enable_checkout_login_reminder', 'yes' );
	update_option( 'woocommerce_enable_signup_and_login_from_checkout', 'yes' );
	update_option( 'woocommerce_enable_myaccount_registration', 'yes' );
	update_option( 'woocommerce_registration_generate_username', 'yes' );
	update_option( 'woocommerce_registration_generate_password', 'no' );

	update_option( 'woocommerce_checkout_phone_field', 'required' );
	update_option( 'woocommerce_checkout_company_field', 'hidden' );
	update_option( 'woocommerce_checkout_address_2_field', 'optional' );
	update_option( 'woocommerce_checkout_highlight_required_fields', 'yes' );

	update_option( 'woocommerce_hold_stock_minutes', '30' );
	update_option( 'woocommerce_manage_stock', 'yes' );
	update_option( 'woocommerce_notify_low_stock', 'yes' );
	update_option( 'woocommerce_notify_no_stock', 'yes' );
	update_option( 'woocommerce_stock_email_recipient', get_option( 'admin_email' ) );
	update_option( 'woocommerce_notify_low_stock_amount', '5' );
	update_option( 'woocommerce_notify_no_stock_amount', '0' );

	update_option( 'woocommerce_placeholder_image', 0 );

	return array( 'حساب کاربری اجباری در تسویه، تلفن الزامی، مدیریت موجودی و هشدار کمبود فعال شد.' );
}
