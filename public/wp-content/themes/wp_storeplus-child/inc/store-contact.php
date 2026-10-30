<?php
/**
 * Single source of truth for رستا دارو contact details.
 * Edit via option `sp_store_contact` (or defaults below), then re-run SEO enrich.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Default contact profile used until real values are saved in options.
 *
 * @return array<string,string>
 */
function storeplus_child_contact_defaults() {
	return array(
		'brand'         => 'رستا دارو',
		'legal_name'    => 'رستا دارو',
		'email'         => 'info@rastadarou.ir',
		'phone_display' => '۰۳۱ ۳۳۶۱ ۲۳۴۵',
		'phone_tel'     => '+983133612345',
		'mobile_display'=> '۰۹۱۳ ۱۲۳ ۴۵۶۷',
		'mobile_tel'    => '+989131234567',
		'whatsapp'      => '989131234567',
		'hours'         => 'همه‌روزه ۸ صبح تا ۱۲ شب',
		'address'       => 'اصفهان، خیابان مارنان، پل مارنان',
		'address_full'  => 'ایران، اصفهان، خیابان مارنان، پل مارنان',
		'city'          => 'اصفهان',
		'province'      => 'اصفهان',
		'postal_code'   => '۸۱۷۳۹',
		'instagram'     => 'https://www.instagram.com/rastadarou/',
		'telegram'      => 'https://t.me/rastadarou',
		'map_note'      => 'ارسال به سراسر کشور؛ پیک در اصفهان',
	);
}

/**
 * Merged contact (option overrides defaults).
 *
 * @return array<string,string>
 */
function storeplus_child_contact() {
	$saved = get_option( 'sp_store_contact', array() );
	if ( ! is_array( $saved ) ) {
		$saved = array();
	}
	return array_merge( storeplus_child_contact_defaults(), array_filter( $saved, 'is_string' ) );
}

/**
 * Push contact into WooCommerce, theme support phone, footer, and legal pages snippets.
 *
 * @return string[]
 */
function storeplus_child_sync_contact() {
	$c   = storeplus_child_contact();
	$log = array();

	update_option( 'woocommerce_store_address', $c['address'] );
	update_option( 'woocommerce_store_city', $c['city'] );
	update_option( 'woocommerce_default_country', 'IR:ES' );
	update_option( 'woocommerce_store_postcode', preg_replace( '/\D+/', '', $c['postal_code'] ) ?: '81739' );

	update_option( 'irantheme_single_product_support_phone_number', $c['mobile_tel'] );
	update_option( 'admin_email', $c['email'] );

	update_option( 'irantheme_instagram_social_url', $c['instagram'] );
	update_option( 'irantheme_telegram_social_url', $c['telegram'] );

	$footer = get_option( 'sp_footer_content', array() );
	if ( ! is_array( $footer ) ) {
		$footer = array();
	}
	$footer['contact'] = array(
		'title' => 'اطلاعات تماس',
		'intro' => $c['hours'] . ' پاسخگوی شما هستیم',
		'rows'  => array(
			array(
				'label' => 'شماره مرکز تماس',
				'value' => $c['phone_display'] . ' — ' . $c['mobile_display'],
			),
			array(
				'label' => 'آدرس رایانامه',
				'value' => $c['email'],
			),
			array(
				'label' => 'آدرس شعبه',
				'value' => $c['address_full'],
			),
		),
	);
	$footer['social'] = array(
		array( 'type' => 'instagram', 'url' => $c['instagram'], 'image_id' => 0 ),
		array( 'type' => 'telegram', 'url' => $c['telegram'], 'image_id' => 0 ),
		array( 'type' => 'whatsapp', 'url' => 'https://wa.me/' . $c['whatsapp'], 'image_id' => 0 ),
	);
	update_option( 'sp_footer_content', $footer, false );

	$about = get_page_by_path( 'درباره-ما' );
	if ( $about ) {
		$html = '<h2>درباره ' . esc_html( $c['brand'] ) . '</h2>';
		$html .= '<p><strong>' . esc_html( $c['brand'] ) . '</strong> فروشگاه آنلاین محصولات سلامت، مکمل غذایی، مراقبت پوست و مو، بهداشت فردی و داروهای بدون نسخه است. تمرکز ما روی اصالت کالا، موجودی شفاف و پشتیبانی واقعی برای خرید مطمئن خانواده‌هاست.</p>';
		$html .= '<p>مرکز فعالیت ما در <strong>' . esc_html( $c['city'] ) . '</strong> است و ارسال به سراسر کشور انجام می‌شود. ' . esc_html( $c['map_note'] ) . '.</p>';
		$html .= '<h3>راه‌های ارتباطی</h3><ul>';
		$html .= '<li><strong>تلفن:</strong> ' . esc_html( $c['phone_display'] ) . '</li>';
		$html .= '<li><strong>موبایل / واتساپ:</strong> ' . esc_html( $c['mobile_display'] ) . '</li>';
		$html .= '<li><strong>ایمیل:</strong> ' . esc_html( $c['email'] ) . '</li>';
		$html .= '<li><strong>آدرس:</strong> ' . esc_html( $c['address_full'] ) . '</li>';
		$html .= '<li><strong>ساعات پاسخگویی:</strong> ' . esc_html( $c['hours'] ) . '</li>';
		$html .= '</ul>';
		wp_update_post(
			array(
				'ID'           => $about->ID,
				'post_content' => $html,
				'post_status'  => 'publish',
			)
		);
		$log[] = 'صفحه درباره ما با مشخصات تماس به‌روز شد.';
	}

	$contact = get_page_by_path( 'تماس-با-ما' );
	if ( $contact ) {
		$html = '<h2>تماس با ' . esc_html( $c['brand'] ) . '</h2>';
		$html .= '<p>' . esc_html( $c['hours'] ) . ' از طریق تلفن، واتساپ و ایمیل پاسخگوی سوالات خرید و پیگیری سفارش هستیم.</p>';
		$html .= '<ul>';
		$html .= '<li><strong>تلفن ثابت:</strong> <a href="tel:' . esc_attr( $c['phone_tel'] ) . '">' . esc_html( $c['phone_display'] ) . '</a></li>';
		$html .= '<li><strong>موبایل:</strong> <a href="tel:' . esc_attr( $c['mobile_tel'] ) . '">' . esc_html( $c['mobile_display'] ) . '</a></li>';
		$html .= '<li><strong>واتساپ:</strong> <a href="https://wa.me/' . esc_attr( $c['whatsapp'] ) . '" rel="noopener noreferrer" target="_blank">' . esc_html( $c['mobile_display'] ) . '</a></li>';
		$html .= '<li><strong>ایمیل:</strong> <a href="mailto:' . esc_attr( $c['email'] ) . '">' . esc_html( $c['email'] ) . '</a></li>';
		$html .= '<li><strong>آدرس:</strong> ' . esc_html( $c['address_full'] ) . '</li>';
		$html .= '</ul>';
		$html .= '<p>برای پیگیری سفارش وارد <a href="' . esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : home_url( '/my-account/' ) ) . '">حساب کاربری</a> شوید یا شماره سفارش را هنگام تماس اعلام کنید.</p>';
		wp_update_post(
			array(
				'ID'           => $contact->ID,
				'post_content' => $html,
				'post_status'  => 'publish',
			)
		);
		$log[] = 'صفحه تماس با ما به‌روز شد.';
	}

	update_option(
		'woocommerce_email_footer_text',
		esc_html( $c['brand'] ) . ' — فروشگاه آنلاین سلامت و مکمل<br>پشتیبانی: ' . esc_html( $c['phone_display'] ) . ' | ' . esc_html( $c['email'] ) . '<br>' . esc_html( $c['address'] )
	);

	$log[] = 'فوتر، ووکامرس و شماره پشتیبانی محصول همگام شد.';
	$log[] = 'توجه: اگر تلفن/آدرس نهایی فرق دارد، گزینه sp_store_contact را با مقادیر واقعی پر کنید و دوباره enrich را اجرا کنید.';
	return $log;
}
