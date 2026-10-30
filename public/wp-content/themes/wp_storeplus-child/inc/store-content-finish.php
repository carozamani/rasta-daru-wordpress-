<?php
/**
 * One-shot / re-runnable store content finisher for رستا دارو.
 * Tools → تکمیل محتوای فروشگاه  OR  tools/run-store-finish.php (local admin session).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const SP_STORE_FINISH_OPTION = 'sp_store_content_finish_v1';

add_action(
	'admin_menu',
	static function () {
		add_management_page(
			'تکمیل محتوای فروشگاه',
			'تکمیل محتوای فروشگاه',
			'manage_options',
			'sp-store-finish',
			'sp_store_finish_admin_page'
		);
	}
);

/**
 * Admin UI.
 */
function sp_store_finish_admin_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$ran = null;
	if ( isset( $_POST['sp_store_finish_run'] ) && check_admin_referer( 'sp_store_finish' ) ) {
		$ran = sp_store_finish_run( ! empty( $_POST['sp_force'] ) );
	}
	$done = get_option( SP_STORE_FINISH_OPTION );
	echo '<div class="wrap"><h1>تکمیل محتوای فروشگاه — رستا دارو</h1>';
	if ( is_array( $ran ) ) {
		echo '<div class="notice notice-success"><p><strong>انجام شد.</strong></p><ul>';
		foreach ( $ran as $line ) {
			echo '<li>' . esc_html( $line ) . '</li>';
		}
		echo '</ul></div>';
	}
	if ( $done ) {
		echo '<p>آخرین اجرا: <code>' . esc_html( (string) $done ) . '</code></p>';
	}
	echo '<form method="post">';
	wp_nonce_field( 'sp_store_finish' );
	echo '<p>صفحات ثابت، فوتر، توضیحات محصولات خالی، موجودی، آیکن دسته‌ها و لینک‌های منو را کامل می‌کند.</p>';
	echo '<p><label><input type="checkbox" name="sp_force" value="1"> اجرای مجدد اجباری</label></p>';
	echo '<p><button class="button button-primary" name="sp_store_finish_run" value="1">اجرای تکمیل محتوا</button></p>';
	echo '</form></div>';
}

/**
 * Run all finishers.
 *
 * @param bool $force Re-run even if already marked done.
 * @return string[] Log lines.
 */
function sp_store_finish_run( $force = false ) {
	$log = array();
	if ( ! $force && get_option( SP_STORE_FINISH_OPTION ) ) {
		$log[] = 'قبلاً اجرا شده — برای تکرار، «اجرای مجدد اجباری» را بزنید.';
		return $log;
	}

	$log = array_merge(
		$log,
		sp_store_finish_pages(),
		sp_store_finish_footer(),
		sp_store_finish_products(),
		sp_store_finish_categories(),
		sp_store_finish_yoast(),
		sp_store_finish_menus(),
		sp_store_finish_home_polish()
	);

	if ( function_exists( 'storeplus_child_critical_scrub_database' ) ) {
		$log = array_merge( $log, storeplus_child_critical_scrub_database() );
	}

	update_option( SP_STORE_FINISH_OPTION, gmdate( 'c' ), false );
	$log[] = 'گزینه ' . SP_STORE_FINISH_OPTION . ' ذخیره شد.';
	return $log;
}

/**
 * Ensure page by slug (Persian or latin).
 *
 * @param string $title Title.
 * @param string $slug  Slug.
 * @param string $html  Content HTML.
 * @return int Page ID.
 */
function sp_store_finish_ensure_page( $title, $slug, $html ) {
	$existing = get_page_by_path( $slug );
	if ( ! $existing ) {
		$q = new WP_Query(
			array(
				'post_type'      => 'page',
				'name'           => $slug,
				'posts_per_page' => 1,
				'post_status'    => 'any',
			)
		);
		if ( $q->have_posts() ) {
			$existing = $q->posts[0];
		}
	}
	if ( $existing ) {
		wp_update_post(
			array(
				'ID'           => $existing->ID,
				'post_title'   => $title,
				'post_content' => $html,
				'post_status'  => 'publish',
			)
		);
		return (int) $existing->ID;
	}
	return (int) wp_insert_post(
		array(
			'post_title'   => $title,
			'post_name'    => $slug,
			'post_content' => $html,
			'post_status'  => 'publish',
			'post_type'    => 'page',
			'post_author'  => 1,
		),
		true
	);
}

/**
 * Static pages: about, contact, terms, privacy, buying guide, returns.
 *
 * @return string[]
 */
function sp_store_finish_pages() {
	$log = array();

	$terms_html = <<<'HTML'
<h2>قوانین و مقررات استفاده از فروشگاه رستا دارو</h2>
<p>ورود به وب‌سایت و ثبت سفارش به‌منزله پذیرش این قوانین است. رستا دارو فروشگاه آنلاین محصولات سلامت، مکمل، مراقبت شخصی و داروهای بدون نسخه است.</p>
<h3>۱. ثبت‌نام و حساب کاربری</h3>
<p>برای ثبت سفارش باید حساب کاربری بسازید. اطلاعات ورود محرمانه است و مسئولیت حفظ آن با کاربر است. اطلاعات تماس باید صحیح و قابل دسترسی باشد.</p>
<h3>۲. سفارش و پرداخت</h3>
<p>قیمت‌ها به تومان نمایش داده می‌شوند و تا قبل از تایید نهایی سفارش ممکن است به‌روزرسانی شوند. پرداخت از طریق درگاه‌های فعال فروشگاه انجام می‌شود. پس از تایید پرداخت، سفارش وارد فرآیند آماده‌سازی می‌شود.</p>
<h3>۳. ارسال و تحویل</h3>
<p>زمان ارسال بسته به موجودی و مقصد متفاوت است. هزینه ارسال در مرحله تسویه‌حساب اعلام می‌شود. تحویل به آدرس ثبت‌شده در سفارش انجام می‌گیرد.</p>
<h3>۴. محدودیت‌های قانونی دارو</h3>
<p>فروش داروهای نسخه‌ای طبق مقررات کشور فقط با شرایط قانونی انجام می‌شود. مصرف هر محصول دارویی یا مکمل باید طبق برچسب و توصیه پزشک/داروساز باشد. رستا دارو جایگزین مشاوره پزشکی نیست.</p>
<h3>۵. مالکیت محتوا</h3>
<p>تمامی متون، تصاویر و برند رستا دارو متعلق به فروشگاه است و استفاده تجاری بدون مجوز ممنوع است.</p>
HTML;

	$privacy_html = <<<'HTML'
<h2>حریم خصوصی کاربران رستا دارو</h2>
<p>ما به حریم خصوصی شما احترام می‌گذاریم. این صفحه توضیح می‌دهد چه داده‌هایی جمع‌آوری می‌شود و چگونه استفاده می‌گردد.</p>
<h3>داده‌های جمع‌آوری‌شده</h3>
<ul>
<li>اطلاعات حساب: نام، شماره موبایل، ایمیل</li>
<li>اطلاعات سفارش و آدرس تحویل</li>
<li>داده‌های فنی مانند کوکی سبد خرید و نشست ورود</li>
</ul>
<h3>هدف استفاده</h3>
<p>پردازش سفارش، پشتیبانی مشتری، بهبود تجربه خرید و رعایت الزامات قانونی. اطلاعات شما به اشخاص ثالث فروخته نمی‌شود.</p>
<h3>کوکی‌ها</h3>
<p>برای حفظ سبد خرید، ورود و تنظیمات نمایش از کوکی استفاده می‌شود. می‌توانید کوکی‌ها را از مرورگر مدیریت کنید؛ غیرفعال کردن برخی کوکی‌ها ممکن است خرید را مختل کند.</p>
<h3>امنیت و نگهداری</h3>
<p>دسترسی به داده‌ها محدود به پرسنل مجاز است. برای پرسش درباره حریم خصوصی از صفحه تماس با ما پیام دهید.</p>
HTML;

	$guide_html = <<<'HTML'
<h2>راهنمای خرید از رستا دارو</h2>
<ol>
<li>محصول را از فروشگاه یا جستجو پیدا کنید و به سبد اضافه کنید.</li>
<li>وارد حساب کاربری شوید یا ثبت‌نام کنید.</li>
<li>آدرس و شیوه ارسال را تکمیل کنید.</li>
<li>روش پرداخت را انتخاب و سفارش را ثبت کنید.</li>
<li>وضعیت سفارش را از حساب کاربری پیگیری کنید.</li>
</ol>
<p>در صورت نیاز به مشاوره محصول، از طریق تماس با پشتیبانی پیام دهید.</p>
HTML;

	$returns_html = <<<'HTML'
<h2>شرایط بازگشت کالا</h2>
<p>سلامت و ایمنی محصولات برای ما اولویت است.</p>
<ul>
<li>کالای سالم و پلمب‌شده در مهلت اعلام‌شده قابل بررسی برای مرجوعی است.</li>
<li>داروها، مکمل‌ها و محصولات بهداشتی بازشده معمولاً به‌دلیل مسائل بهداشتی قابل بازگشت نیستند مگر نقص تولید یا ارسال اشتباه.</li>
<li>برای شروع مرجوعی، با پشتیبانی تماس بگیرید و شماره سفارش را اعلام کنید.</li>
</ul>
HTML;

	$about_html = <<<'HTML'
<h2>درباره رستا دارو</h2>
<p><strong>رستا دارو</strong> فروشگاه آنلاین محصولات سلامت، مراقبت شخصی، مکمل غذایی و داروهای بدون نسخه است. هدف ما دسترسی آسان، شفاف و مطمئن به محصولات معتبر برای خانواده‌هاست.</p>
<p>از تامین‌کنندگان معتبر خرید می‌کنیم، موجودی را به‌روز نگه می‌داریم و پشتیبانی پاسخگو ارائه می‌دهیم. مرکز فعالیت ما اصفهان است و ارسال به سراسر کشور انجام می‌شود.</p>
HTML;

	$contact_html = <<<'HTML'
<h2>تماس با رستا دارو</h2>
<p>هفت روز هفته از ساعت ۸ صبح تا ۱۲ شب پاسخگوی شما هستیم.</p>
<ul>
<li><strong>تلفن:</strong> ۰۳۱-۳۳۶۱۲۳۴۵</li>
<li><strong>موبایل:</strong> ۰۹۱۳۱۲۳۴۵۶۷</li>
<li><strong>ایمیل:</strong> info@rastadarou.ir</li>
<li><strong>آدرس:</strong> ایران، اصفهان، خیابان مارنان، پل مارنان</li>
</ul>
HTML;

	$ids = array(
		'terms'   => sp_store_finish_ensure_page( 'قوانین و مقررات', 'ghavanin', $terms_html ),
		'privacy' => sp_store_finish_ensure_page( 'حریم خصوصی', 'harim-khosusi', $privacy_html ),
		'guide'   => sp_store_finish_ensure_page( 'راهنمای خرید', 'rahnama-kharid', $guide_html ),
		'returns' => sp_store_finish_ensure_page( 'شرایط بازگشت کالا', 'bazgasht-kala', $returns_html ),
		'about'   => sp_store_finish_ensure_page( 'درباره ما', 'درباره-ما', $about_html ),
		'contact' => sp_store_finish_ensure_page( 'تماس با ما', 'تماس-با-ما', $contact_html ),
	);

	/* Also refresh WP privacy policy page if set. */
	$wp_privacy = (int) get_option( 'wp_page_for_privacy_policy' );
	if ( $wp_privacy ) {
		wp_update_post(
			array(
				'ID'           => $wp_privacy,
				'post_title'   => 'حریم خصوصی',
				'post_content' => $privacy_html,
				'post_status'  => 'publish',
			)
		);
		$ids['wp_privacy'] = $wp_privacy;
	} else {
		update_option( 'wp_page_for_privacy_policy', $ids['privacy'] );
	}

	update_option( 'woocommerce_terms_page_id', (string) $ids['terms'] );
	update_option( 'woocommerce_checkout_privacy_policy_text', 'اطلاعات شخصی شما برای پردازش سفارش و پشتیبانی استفاده می‌شود. جزئیات در [privacy_policy] آمده است.' );
	update_option( 'woocommerce_checkout_terms_and_conditions_checkbox_text', 'قوانین و مقررات سایت را مطالعه کرده و می‌پذیرم ([terms]).' );

	update_option( 'sp_store_finish_page_ids', $ids, false );
	$log[] = 'صفحات ثابت ساخته/به‌روز شد: قوانین، حریم خصوصی، راهنما، بازگشت، درباره، تماس.';
	return $log;
}

/**
 * Footer content with real page URLs.
 *
 * @return string[]
 */
function sp_store_finish_footer() {
	$ids = get_option( 'sp_store_finish_page_ids', array() );
	$url = static function ( $key, $fallback_slug ) use ( $ids ) {
		if ( ! empty( $ids[ $key ] ) ) {
			$u = get_permalink( (int) $ids[ $key ] );
			if ( $u ) {
				return $u;
			}
		}
		return home_url( '/' . $fallback_slug . '/' );
	};

	$data = array(
		'quick'   => array(
			'title' => 'دسترسی سریع',
			'links' => array(
				array( 'text' => 'فروشگاه', 'url' => function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' ) ),
				array( 'text' => 'درباره ما', 'url' => $url( 'about', 'درباره-ما' ) ),
				array( 'text' => 'تماس با ما', 'url' => $url( 'contact', 'تماس-با-ما' ) ),
				array( 'text' => 'سوالات متداول', 'url' => home_url( '/سوالات-متداول/' ) ),
				array( 'text' => 'وبلاگ', 'url' => home_url( '/وبلاگ/' ) ),
			),
		),
		'guide'   => array(
			'title' => 'راهنمای مشتریان',
			'links' => array(
				array( 'text' => 'راهنمای خرید', 'url' => $url( 'guide', 'rahnama-kharid' ) ),
				array( 'text' => 'شرایط بازگشت کالا', 'url' => $url( 'returns', 'bazgasht-kala' ) ),
				array( 'text' => 'قوانین و مقررات', 'url' => $url( 'terms', 'ghavanin' ) ),
				array( 'text' => 'حریم خصوصی', 'url' => $url( 'privacy', 'harim-khosusi' ) ),
				array( 'text' => 'پیگیری سفارش', 'url' => function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : home_url( '/my-account/' ) ),
			),
		),
		'contact' => array(
			'title' => 'اطلاعات تماس',
			'intro' => 'هفت روز هفته از ساعت ۸ صبح تا ۱۲ شب پاسخگوی شما عزیزان هستیم',
			'rows'  => array(
				array( 'label' => 'شماره مرکز تماس', 'value' => '03133612345 - 09131234567' ),
				array( 'label' => 'آدرس رایانامه', 'value' => 'info@rastadarou.ir' ),
				array( 'label' => 'آدرس شعبه', 'value' => 'ایران - اصفهان - خیابان مارنان - پل مارنان' ),
			),
		),
		'social'  => array(
			array( 'type' => 'instagram', 'url' => 'https://www.instagram.com/rastadarou/', 'image_id' => 0 ),
			array( 'type' => 'telegram', 'url' => 'https://t.me/rastadarou', 'image_id' => 0 ),
			array( 'type' => 'whatsapp', 'url' => 'https://wa.me/989131234567', 'image_id' => 0 ),
		),
	);

	update_option( 'sp_footer_content', $data, false );
	return array( 'فوتر با لینک‌های واقعی قوانین/حریم خصوصی/راهنما به‌روز شد.' );
}

/**
 * Build Persian product copy from title + categories.
 *
 * @param WC_Product $product Product.
 * @return array{content:string,short:string,about:string,usage:string,warnings:string}
 */
function sp_store_finish_product_copy( $product ) {
	$name   = $product->get_name();
	$cats   = wp_get_post_terms( $product->get_id(), 'product_cat', array( 'fields' => 'names' ) );
	$cat    = ( ! is_wp_error( $cats ) && $cats ) ? $cats[0] : 'سلامت';
	$brand  = '';
	if ( taxonomy_exists( 'product_brand' ) ) {
		$brands = wp_get_post_terms( $product->get_id(), 'product_brand', array( 'fields' => 'names' ) );
		if ( ! is_wp_error( $brands ) && $brands ) {
			$brand = $brands[0];
		}
	}
	$sku = $product->get_sku();
	$brand_bit = $brand ? ( ' برند <strong>' . esc_html( $brand ) . '</strong>' ) : '';
	$sku_bit   = $sku ? ( ' کد کالا: <code>' . esc_html( $sku ) . '</code>.' ) : '';

	$short = sprintf(
		'خرید %s از دسته «%s»%s در رستا دارو — اصالت کالا، ارسال سریع و پشتیبانی هفت‌روزه.',
		$name,
		$cat,
		$brand ? ( '، برند ' . $brand ) : ''
	);

	$content  = '<p><strong>' . esc_html( $name ) . '</strong> محصولی از دسته <strong>' . esc_html( $cat ) . '</strong>' . $brand_bit . ' است که در فروشگاه آنلاین رستا دارو با شرایط نگهداری استاندارد عرضه می‌شود.' . $sku_bit . '</p>';
	$content .= '<p>این کالا برای تکمیل سبد سلامت، مراقبت شخصی یا مصرف روزمره انتخاب شده است. مشخصات دقیق، ترکیبات و نحوه مصرف روی بسته‌بندی درج شده؛ همیشه برچسب را قبل از استفاده بخوانید.</p>';
	$content .= '<h3>چرا از رستا دارو؟</h3><ul>';
	$content .= '<li>تضمین اصالت و تاریخ مصرف معتبر</li>';
	$content .= '<li>ارسال سریع از انبار اصفهان به سراسر کشور</li>';
	$content .= '<li>پشتیبانی هفت روز هفته برای پیگیری سفارش</li>';
	$content .= '</ul>';
	$content .= '<p><em>توجه:</em> محتوای این صفحه جنبه اطلاع‌رسانی دارد و جایگزین مشاوره پزشک یا داروساز نیست. در بیماری زمینه‌ای، بارداری، شیردهی یا مصرف همزمان دارو با متخصص مشورت کنید.</p>';

	$about    = $name . ( $brand ? ( ' از برند ' . $brand ) : '' ) . ' در دسته ' . $cat . '؛ عرضه در رستا دارو با کنترل موجودی و نگهداری استاندارد.';
	$usage    = 'طبق دستور درج‌شده روی بسته‌بندی یا توصیه پزشک/داروساز مصرف شود. دوز خودسرانه توصیه نمی‌شود.';
	$warnings = 'دور از دسترس کودکان نگهداری شود. در صورت بروز حساسیت، مصرف را قطع و با پزشک مشورت کنید. این متن جایگزین مشاوره پزشکی نیست.';

	return compact( 'content', 'short', 'about', 'usage', 'warnings' );
}

/**
 * Fill empty descriptions, ensure price/stock, PDP meta, image alt.
 *
 * @return string[]
 */
function sp_store_finish_products() {
	if ( ! function_exists( 'wc_get_products' ) ) {
		return array( 'WooCommerce در دسترس نیست — محصولات رد شد.' );
	}

	$ids = wc_get_products(
		array(
			'limit'  => -1,
			'status' => array( 'publish', 'draft' ),
			'return' => 'ids',
			'type'   => array( 'simple', 'variable' ),
		)
	);

	$filled = 0;
	$stock  = 0;
	$price  = 0;
	$skus   = 0;
	$brands = 0;
	$alts   = 0;
	$yoast  = 0;

	$junk = static function ( $text ) {
		if ( '' === $text ) {
			return true;
		}
		if ( mb_strlen( $text ) < 40 ) {
			return true;
		}
		$needles = array(
			'لورم',
			'Lorem',
			'ipsum',
			'LINKE-JADID',
			'placeholder',
			'نمونه متن',
			'متن آزمایشی',
			'romak',
			'demo text',
			'محصولات منتخب رستا دارو در دسته',
			'تکمیل سبد سلامت و مراقبت روزمره',
		);
		foreach ( $needles as $n ) {
			if ( false !== stripos( $text, $n ) ) {
				return true;
			}
		}
		return false;
	};

	foreach ( $ids as $id ) {
		$product = wc_get_product( $id );
		if ( ! $product ) {
			continue;
		}

		$raw_content   = trim( wp_strip_all_tags( $product->get_description() ) );
		$raw_short     = trim( wp_strip_all_tags( $product->get_short_description() ) );
		$needs_content = $junk( $raw_content );
		$needs_short   = $junk( $raw_short );

		/* SKU before copy so descriptions can include it. */
		if ( ! $product->get_sku() ) {
			$sku = sprintf( 'RD-%05d', (int) $id );
			$product->set_sku( $sku );
			$product->save();
			$product = wc_get_product( $id );
			$skus++;
		}

		/* Assign brand when product title clearly contains an existing brand name. */
		if ( taxonomy_exists( 'product_brand' ) ) {
			$existing = wp_get_post_terms( $id, 'product_brand', array( 'fields' => 'ids' ) );
			if ( is_wp_error( $existing ) || empty( $existing ) ) {
				$pool = get_terms(
					array(
						'taxonomy'   => 'product_brand',
						'hide_empty' => false,
						'number'     => 200,
					)
				);
				if ( ! is_wp_error( $pool ) && $pool ) {
					$title_l = mb_strtolower( $product->get_name() );
					$matched = 0;
					foreach ( $pool as $bt ) {
						$bn = trim( (string) $bt->name );
						if ( mb_strlen( $bn ) < 2 ) {
							continue;
						}
						if ( false !== mb_stripos( $title_l, mb_strtolower( $bn ) ) ) {
							$matched = (int) $bt->term_id;
							break;
						}
					}
					if ( $matched ) {
						wp_set_object_terms( $id, array( $matched ), 'product_brand', false );
						$brands++;
						$product = wc_get_product( $id );
					}
				}
			}
		}

		$copy   = sp_store_finish_product_copy( $product );
		$update = array( 'ID' => $id );
		if ( $needs_content ) {
			$update['post_content'] = $copy['content'];
		}
		if ( $needs_short ) {
			$update['post_excerpt'] = $copy['short'];
		}
		if ( count( $update ) > 1 ) {
			wp_update_post( $update );
			$filled++;
		}

		update_post_meta( $id, '_sp_pdp_about', $copy['about'] );
		update_post_meta( $id, '_sp_pdp_usage', $copy['usage'] );
		update_post_meta( $id, '_sp_pdp_warnings', $copy['warnings'] );
		if ( ! get_post_meta( $id, '_sp_pdp_storage', true ) ) {
			update_post_meta( $id, '_sp_pdp_storage', 'در جای خشک و خنک، دور از نور مستقیم خورشید نگهداری شود.' );
		}
		if ( ! get_post_meta( $id, '_sp_pdp_origin', true ) ) {
			update_post_meta( $id, '_sp_pdp_origin', 'ایران / وارداتی مجاز' );
		}

		/* Yoast focus + meta for products missing them. */
		if ( defined( 'WPSEO_VERSION' ) ) {
			$name = $product->get_name();
			if ( ! get_post_meta( $id, '_yoast_wpseo_focuskw', true ) ) {
				update_post_meta( $id, '_yoast_wpseo_focuskw', $name );
				$yoast++;
			}
			if ( ! get_post_meta( $id, '_yoast_wpseo_title', true ) ) {
				update_post_meta( $id, '_yoast_wpseo_title', $name . ' %%sep%% خرید از %%sitename%%' );
			}
			if ( ! get_post_meta( $id, '_yoast_wpseo_metadesc', true ) ) {
				$meta = mb_substr( wp_strip_all_tags( $copy['short'] ), 0, 155 );
				update_post_meta( $id, '_yoast_wpseo_metadesc', $meta );
			}
		}

		/* Stock. */
		if ( ! $product->get_manage_stock() ) {
			$product->set_manage_stock( true );
			$product->set_stock_quantity( max( 25, (int) $product->get_stock_quantity() ) );
			$product->set_stock_status( 'instock' );
			$product->save();
			$stock++;
		} elseif ( (int) $product->get_stock_quantity() <= 0 && $product->is_in_stock() ) {
			$product->set_stock_quantity( 30 );
			$product->save();
			$stock++;
		}

		/* Price fallback for empty. */
		if ( '' === $product->get_regular_price() && '' === $product->get_price() && 'simple' === $product->get_type() ) {
			$fallback = 99000 + ( (int) $id % 20 ) * 5000;
			$product->set_regular_price( (string) $fallback );
			$product->set_price( (string) $fallback );
			$product->save();
			$price++;
		}

		/* Featured image alt. */
		$thumb = $product->get_image_id();
		if ( $thumb ) {
			$alt = trim( (string) get_post_meta( $thumb, '_wp_attachment_image_alt', true ) );
			if ( '' === $alt || in_array( $alt, array( 'تصویر محصول', 'product', 'image' ), true ) ) {
				update_post_meta( $thumb, '_wp_attachment_image_alt', $product->get_name() );
				$alts++;
			}
		}
	}

	return array(
		sprintf(
			'محصولات: توضیح %d | SKU %d | برند %d | alt تصویر %d | Yoast %d | موجودی %d | قیمت %d.',
			$filled,
			$skus,
			$brands,
			$alts,
			$yoast,
			$stock,
			$price
		),
	);
}

/**
 * Persian SEO description for a product category by name keywords.
 *
 * @param string $name Category name.
 * @return string HTML/plain description.
 */
function sp_store_finish_category_description( $name ) {
	$templates = array(
		'مکمل'   => 'در این بخش، مکمل‌های غذایی، ویتامین‌ها و محصولات تقویتی منتخب رستا دارو را مشاهده می‌کنید. همه کالاها با کنترل اصالت و تاریخ مصرف عرضه می‌شوند. در صورت بیماری زمینه‌ای یا مصرف همزمان دارو، پیش از مصرف با پزشک یا داروساز مشورت کنید.',
		'ویتامین' => 'مجموعه ویتامین‌ها و مواد معدنی رستا دارو برای تأمین نیازهای روزمره بدن گردآوری شده است. محصولات این دسته از برندهای معتبر انتخاب شده‌اند و مشخصات کامل روی صفحه هر کالا آمده است. دوز مصرف را مطابق برچسب یا توصیه متخصص رعایت کنید.',
		'پوست'   => 'محصولات مراقبت پوست رستا دارو شامل پاک‌کننده، مرطوب‌کننده، ضدآفتاب و روتین روزانه است. هدف ما دسترسی آسان به برندهای معتبر با ارسال سریع است. برای پوست حساس، ترکیب محصول را بخوانید و در صورت تحریک مصرف را متوقف کنید.',
		'آرایشی' => 'در دسته آرایشی و مراقبت پوست، محصولات منتخب برای روتین روزانه و تکمیل سبد زیبایی ارائه شده است. اصالت کالا تضمین می‌شود و پشتیبانی فروشگاه پاسخگوی سوالات خرید است.',
		'مو'     => 'شامپو، نرم‌کننده، سرم و مراقبت تخصصی مو را در این دسته پیدا کنید. محصولات برای انواع مو انتخاب شده‌اند؛ برچسب ترکیبات را بخوانید و در صورت حساسیت با متخصص مشورت کنید.',
		'بهداشت' => 'اقلام بهداشت فردی و مراقبت روزمره خانواده در این دسته گردآوری شده‌اند. خرید از رستا دارو با موجودی به‌روز و ارسال از اصفهان انجام می‌شود.',
		'گیاهی'  => 'محصولات گیاهی و طبیعی منتخب برای کسانی که رویکرد ملایم‌تری به مراقبت و سلامت دارند. مصرف گیاهی هم نیازمند احتیاط است؛ تداخل با داروهای دیگر را جدی بگیرید.',
		'دارو'   => 'در این بخش، داروهای بدون نسخه (OTC) و اقلام دارویی مجاز مطابق مقررات عرضه می‌شوند. رستا دارو جایگزین مشاوره پزشکی نیست. دوز و شرایط مصرف را از برچسب محصول و داروساز بپرسید.',
		'OTC'    => 'داروهای بدون نسخه و اقلام OTC مجاز در رستا دارو با کنترل موجودی و تاریخ مصرف ارائه می‌شوند. برای مصرف ایمن، دستورالعمل بسته را بخوانید.',
		'تجهیز'  => 'تجهیزات و لوازم پزشکی خانگی برای پایش و مراقبت روزمره در این دسته قرار دارند. مشخصات فنی هر کالا در صفحه محصول آمده و پشتیبانی خرید پاسخگوست.',
		'پزشکی'  => 'لوازم و تجهیزات مرتبط با مراقبت پزشکی خانگی را اینجا ببینید. انتخاب درست به نیاز شما بستگی دارد؛ در موارد درمانی با پزشک مشورت کنید.',
		'مادر'   => 'محصولات ویژه مادر و کودک با تمرکز بر ایمنی و برندهای معتبر. همیشه سن مصرف و هشدارهای برچسب را بررسی کنید.',
		'کودک'   => 'مراقبت کودک نیازمند دقت در انتخاب برند و ترکیبات است. در این دسته اقلام منتخب رستا دارو برای والدین گردآوری شده است.',
		'دهان'   => 'مراقبت دهان و دندان شامل مسواک، خمیردندان و اقلام بهداشتی مرتبط است. برای کودکان، محصول متناسب با سن را انتخاب کنید.',
	);

	foreach ( $templates as $needle => $text ) {
		if ( false !== mb_strpos( $name, $needle ) ) {
			return $text;
		}
	}

	return sprintf(
		'خرید آنلاین «%1$s» از فروشگاه رستا دارو با تضمین اصالت، موجودی به‌روز و ارسال سریع به سراسر کشور. در این دسته محصولات منتخب سلامت و مراقبت گردآوری شده‌اند؛ مشخصات هر کالا را در صفحه محصول بخوانید و در صورت نیاز با پشتیبانی مشورت کنید.',
		$name
	);
}

/**
 * Attach category icons, clear card descriptions, set Yoast meta, hide legacy demo categories.
 *
 * @return string[]
 */
function sp_store_finish_categories() {
	$map = array(
		'supplements'     => 'supplements.png',
		'mokamelha'       => 'supplements.png',
		'skin-care'       => 'skin.png',
		'hair-care'       => 'hair.png',
		'personal-care'   => 'hygiene.png',
		'hygiene'         => 'hygiene.png',
		'herbal-natural'  => 'herbal.png',
		'otc'             => 'otc.png',
		'daroha'          => 'otc.png',
		'medical-devices' => 'equipment.png',
		'mother-child'    => 'mother-child.png',
		'madar-koodak'    => 'mother-child.png',
	);

	/* Fuzzy match by name keywords. */
	$name_map = array(
		'مکمل'   => 'supplements.png',
		'پوست'   => 'skin.png',
		'مو'     => 'hair.png',
		'بهداشت' => 'hygiene.png',
		'گیاهی'  => 'herbal.png',
		'دارو'   => 'otc.png',
		'OTC'    => 'otc.png',
		'تجهیز'  => 'equipment.png',
		'پزشکی'  => 'equipment.png',
		'مادر'   => 'mother-child.png',
		'کودک'   => 'mother-child.png',
		'آرایشی' => 'skin.png',
	);

	$base = WP_CONTENT_DIR . '/uploads/category-icons/';
	$done = 0;
	$desc = 0;

	$terms = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => false,
			'parent'     => 0,
		)
	);
	if ( is_wp_error( $terms ) ) {
		return array( 'خطا در خواندن دسته‌ها.' );
	}

	foreach ( $terms as $term ) {
		/* Hide legacy laptop/demo. */
		if ( false !== strpos( $term->slug, 'laptop' ) || false !== strpos( $term->name, 'کامپیوتر' ) || false !== strpos( $term->name, 'لپ' ) ) {
			update_term_meta( $term->term_id, '_sp_catmenu_hidden', '1' );
		}

		/* Keep category cards description-free; SEO copy lives in Yoast meta only. */
		$raw_desc = trim( wp_strip_all_tags( (string) $term->description ) );
		if ( '' !== $raw_desc ) {
			wp_update_term(
				$term->term_id,
				'product_cat',
				array(
					'description' => '',
				)
			);
			$desc++;
		}

		/* Yoast taxonomy meta. */
		if ( defined( 'WPSEO_VERSION' ) ) {
			$tax_meta = get_option( 'wpseo_taxonomy_meta', array() );
			if ( ! is_array( $tax_meta ) ) {
				$tax_meta = array();
			}
			if ( empty( $tax_meta['product_cat'] ) || ! is_array( $tax_meta['product_cat'] ) ) {
				$tax_meta['product_cat'] = array();
			}
			$tid = (string) $term->term_id;
			if ( empty( $tax_meta['product_cat'][ $tid ]['wpseo_desc'] ) ) {
				$tax_meta['product_cat'][ $tid ]['wpseo_desc'] = mb_substr( sp_store_finish_category_description( $term->name ), 0, 155 );
			}
			if ( empty( $tax_meta['product_cat'][ $tid ]['wpseo_title'] ) ) {
				$tax_meta['product_cat'][ $tid ]['wpseo_title'] = $term->name . ' %%sep%% خرید آنلاین %%sep%% %%sitename%%';
			}
			if ( empty( $tax_meta['product_cat'][ $tid ]['wpseo_focuskw'] ) ) {
				$tax_meta['product_cat'][ $tid ]['wpseo_focuskw'] = $term->name;
			}
			update_option( 'wpseo_taxonomy_meta', $tax_meta, false );
		}

		$thumb = (int) get_term_meta( $term->term_id, 'thumbnail_id', true );
		if ( $thumb ) {
			continue;
		}

		$file = $map[ $term->slug ] ?? '';
		if ( ! $file ) {
			foreach ( $name_map as $needle => $fname ) {
				if ( false !== mb_strpos( $term->name, $needle ) ) {
					$file = $fname;
					break;
				}
			}
		}
		if ( ! $file || ! file_exists( $base . $file ) ) {
			continue;
		}

		$att_id = sp_store_finish_sideload_local( $base . $file, 'آیکن دسته ' . $term->name );
		if ( $att_id ) {
			update_term_meta( $term->term_id, 'thumbnail_id', $att_id );
			$done++;
		}
	}

	if ( function_exists( 'sp_catmenu_flush_cache' ) ) {
		sp_catmenu_flush_cache();
	}

	return array(
		sprintf( 'دسته‌ها: توضیح کارت برای %d دسته خالی شد؛ آیکن برای %d دسته؛ کش منو پاک شد.', $desc, $done ),
	);
}

/**
 * Configure Yoast SEO: organization, social, title/metadesc templates, sitemaps.
 *
 * @return string[]
 */
function sp_store_finish_yoast() {
	if ( ! defined( 'WPSEO_VERSION' ) ) {
		return array( 'Yoast نصب نیست — تنظیمات سئو رد شد.' );
	}

	$log = array();

	update_option( 'blogname', 'رستا دارو' );
	update_option( 'blogdescription', 'فروشگاه آنلاین سلامت، مکمل، مراقبت شخصی و داروهای بدون نسخه' );
	update_option( 'blog_public', '1' );
	$log[] = 'نام و توضیح سایت وردپرس تنظیم شد؛ ایندکس مجاز است.';

	$logo_id  = 0;
	$logo_url = '';
	if ( function_exists( 'storeplus_child_saved_image_id' ) && defined( 'STOREPLUS_CHILD_LOGO_OPTION' ) ) {
		$logo_id = (int) storeplus_child_saved_image_id( STOREPLUS_CHILD_LOGO_OPTION );
	}
	if ( ! $logo_id ) {
		$logo_id = (int) get_theme_mod( 'custom_logo' );
	}
	if ( $logo_id ) {
		$logo_url = (string) wp_get_attachment_image_url( $logo_id, 'full' );
	}

	$titles = get_option( 'wpseo_titles', array() );
	if ( ! is_array( $titles ) ) {
		$titles = array();
	}

	$titles['separator']                 = 'sc-dash';
	$titles['company_or_person']         = 'company';
	$titles['company_name']              = 'رستا دارو';
	$titles['company_alternate_name']    = 'Rasta Darou';
	$titles['website_name']              = 'رستا دارو';
	$titles['alternate_website_name']    = 'RastaDarou';
	$titles['org-description']           = 'فروشگاه آنلاین محصولات سلامت، مکمل غذایی، مراقبت پوست و مو، بهداشت فردی و داروهای بدون نسخه با ارسال سریع.';
	$titles['org-email']                 = 'info@rastadarou.ir';
	$titles['org-phone']                 = '031-33612345';
	$titles['org-legal-name']            = 'رستا دارو';
	$titles['breadcrumbs-enable']        = true;
	$titles['breadcrumbs-home']          = 'خانه';
	$titles['breadcrumbs-sep']           = '»';
	$titles['stripcategorybase']         = false;
	$titles['open_graph_frontpage_title'] = '%%sitename%% %%sep%% %%sitedesc%%';
	$titles['open_graph_frontpage_desc']  = 'خرید آنلاین مکمل، مراقبت پوست و مو، بهداشت و OTC از رستا دارو با تضمین اصالت و ارسال سریع.';

	if ( $logo_id ) {
		$titles['company_logo_id'] = $logo_id;
		$titles['company_logo']    = $logo_url;
		$titles['open_graph_frontpage_image_id'] = $logo_id;
		$titles['open_graph_frontpage_image']    = $logo_url;
	}

	/* Title & meta templates. */
	$titles['title-home-wpseo']           = '%%sitename%% %%sep%% %%sitedesc%%';
	$titles['metadesc-home-wpseo']        = 'رستا دارو؛ فروشگاه آنلاین سلامت، مکمل، مراقبت شخصی و داروهای بدون نسخه با ارسال سریع از اصفهان.';
	$titles['title-product']              = '%%title%% %%sep%% خرید از %%sitename%%';
	$titles['metadesc-product']           = 'خرید %%title%% از رستا دارو با تضمین اصالت، قیمت به‌روز و ارسال سریع. %%excerpt%%';
	$titles['title-tax-product_cat']      = '%%term_title%% %%sep%% خرید آنلاین %%sep%% %%sitename%%';
	$titles['metadesc-tax-product_cat']   = 'خرید %%term_title%% از فروشگاه رستا دارو. %%term_description%%';
	$titles['title-tax-product_brand']    = 'برند %%term_title%% %%sep%% %%sitename%%';
	$titles['metadesc-tax-product_brand'] = 'محصولات برند %%term_title%% را با اصالت کالا و ارسال سریع از رستا دارو بخرید.';
	$titles['title-ptarchive-product']    = 'فروشگاه %%sep%% %%sitename%%';
	$titles['metadesc-ptarchive-product'] = 'مشاهده همه محصولات سلامت و مراقبت در فروشگاه آنلاین رستا دارو.';
	$titles['noindex-product']            = false;
	$titles['noindex-tax-product_cat']    = false;
	$titles['schema-page-type-product']   = 'ItemPage';
	$titles['display-metabox-pt-product'] = true;
	$titles['display-metabox-tax-product_cat'] = true;

	update_option( 'wpseo_titles', $titles, false );
	$log[] = 'Yoast: سازمان، لوگو، بردکرامب و الگوی عنوان/توضیح محصول و دسته تنظیم شد.';

	$social = get_option( 'wpseo_social', array() );
	if ( ! is_array( $social ) ) {
		$social = array();
	}
	$social['instagram_url']     = 'https://www.instagram.com/rastadarou/';
	$social['facebook_site']     = 'https://www.facebook.com/rastadarou';
	$social['twitter_site']      = 'rastadarou';
	$social['youtube_url']       = '';
	$social['linkedin_url']      = '';
	$social['other_social_urls'] = array(
		'https://t.me/rastadarou',
		'https://wa.me/989131234567',
	);
	$social['opengraph']         = true;
	$social['twitter']           = true;
	update_option( 'wpseo_social', $social, false );
	$log[] = 'Yoast Social: اینستاگرام، تلگرام، واتساپ و Open Graph فعال شد.';

	$wpseo = get_option( 'wpseo', array() );
	if ( ! is_array( $wpseo ) ) {
		$wpseo = array();
	}
	$wpseo['enable_xml_sitemap']       = true;
	$wpseo['enable_admin_bar_menu']    = true;
	$wpseo['disable_course_trainer']   = true;
	$wpseo['first_time_install']       = false;
	$wpseo['should_redirect_after_install_free'] = false;
	update_option( 'wpseo', $wpseo, false );

	/* Mark first-time configuration progress so wizard feels complete. */
	update_option( 'wpseo_ftc_progress', array( 'completed' => true ), false );
	update_option( 'wpseo_tracking_notice', 'dismissed', false );

	/* Sync theme social options used by schema sameAs. */
	update_option( 'irantheme_instagram_social_url', 'https://www.instagram.com/rastadarou/' );
	update_option( 'irantheme_telegram_social_url', 'https://t.me/rastadarou' );
	update_option( 'irantheme_facebook_social_url', 'https://www.facebook.com/rastadarou' );

	$log[] = 'سایت‌مپ XML یواست فعال شد؛ پروفایل‌های اجتماعی تم همگام شد.';
	return $log;
}

/**
 * Import a local file into Media Library if needed.
 *
 * @param string $path Absolute path.
 * @param string $title Title.
 * @return int Attachment ID.
 */
function sp_store_finish_sideload_local( $path, $title ) {
	$basename = basename( $path );
	$existing = get_posts(
		array(
			'post_type'      => 'attachment',
			'title'          => $title,
			'posts_per_page' => 1,
			'post_status'    => 'inherit',
			'fields'         => 'ids',
		)
	);
	if ( $existing ) {
		return (int) $existing[0];
	}

	/* Match by filename in uploads. */
	$found = get_posts(
		array(
			'post_type'      => 'attachment',
			'posts_per_page' => 1,
			'post_status'    => 'inherit',
			'fields'         => 'ids',
			'meta_query'     => array(
				array(
					'key'     => '_wp_attached_file',
					'value'   => $basename,
					'compare' => 'LIKE',
				),
			),
		)
	);
	if ( $found ) {
		return (int) $found[0];
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$upload = wp_upload_bits( $basename, null, file_get_contents( $path ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	if ( ! empty( $upload['error'] ) ) {
		return 0;
	}
	$filetype = wp_check_filetype( $basename );
	$att_id   = wp_insert_attachment(
		array(
			'post_mime_type' => $filetype['type'],
			'post_title'     => $title,
			'post_content'   => '',
			'post_status'    => 'inherit',
		),
		$upload['file']
	);
	if ( is_wp_error( $att_id ) || ! $att_id ) {
		return 0;
	}
	$meta = wp_generate_attachment_metadata( $att_id, $upload['file'] );
	wp_update_attachment_metadata( $att_id, $meta );
	update_post_meta( $att_id, '_wp_attachment_image_alt', $title );
	return (int) $att_id;
}

/**
 * Fix placeholder / broken menu URLs; trim demo items from primary menu.
 *
 * @return string[]
 */
function sp_store_finish_menus() {
	$fixed = 0;
	$items = get_posts(
		array(
			'post_type'      => 'nav_menu_item',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
		)
	);

	$bad_hosts = array( 'LINKE-JADID-INJA', 'rastadarou.local', 'ngrok-free.dev', 'ngrok.io' );

	foreach ( $items as $item ) {
		$url = get_post_meta( $item->ID, '_menu_item_url', true );
		if ( ! $url || '#' === $url ) {
			continue;
		}
		$dirty = false;
		foreach ( $bad_hosts as $host ) {
			if ( false !== stripos( $url, $host ) ) {
				$dirty = true;
				break;
			}
		}
		if ( ! $dirty ) {
			continue;
		}
		$path = wp_parse_url( $url, PHP_URL_PATH );
		$query = wp_parse_url( $url, PHP_URL_QUERY );
		$new   = home_url( $path ? $path : '/' );
		if ( $query ) {
			$new .= ( false === strpos( $new, '?' ) ? '?' : '&' ) . $query;
		}
		update_post_meta( $item->ID, '_menu_item_url', esc_url_raw( $new ) );
		$fixed++;
	}

	/* Do not inject static pages into the header menu — those belong in the footer. */

	return array( sprintf( 'لینک‌های خراب منو اصلاح شد: %d مورد.', $fixed ) );
}

/**
 * Light homepage polish: top banner link, offer flags, clear hero typos.
 *
 * @return string[]
 */
function sp_store_finish_home_polish() {
	$log = array();

	$tb = get_option( 'wdc_top_banner', array() );
	if ( ! is_array( $tb ) ) {
		$tb = array();
	}
	$tb['enabled'] = '1';
	if ( empty( $tb['text'] ) ) {
		$tb['text'] = 'ارسال سریع اصفهان و سراسر کشور | خرید اقساطی با اعتبار اسنپ‌پی';
	}
	$tb['link'] = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
	update_option( 'wdc_top_banner', $tb );

	/* Flag a handful of on-sale products as amazing offers. */
	if ( function_exists( 'wc_get_products' ) ) {
		$sale_ids = wc_get_products(
			array(
				'limit'    => 8,
				'status'   => 'publish',
				'on_sale'  => true,
				'return'   => 'ids',
				'orderby'  => 'date',
				'order'    => 'DESC',
			)
		);
		$n = 0;
		foreach ( $sale_ids as $pid ) {
			update_post_meta( $pid, '_amazing_offer', 'yes' );
			$n++;
		}
		$log[] = sprintf( '%d محصول حراج به پیشنهاد شگفت‌انگیز اضافه شد.', $n );
	}

	/* Fix nonsense home_banner titles. */
	$banners = get_posts(
		array(
			'post_type'      => 'home_banner',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
		)
	);
	$titles = array(
		'تخفیف مکمل‌های ورزشی',
		'مراقبت پوست و ضدآفتاب',
		'برندهای معتبر سلامت',
		'دارو و OTC بدون نسخه',
		'ارسال سریع رستا دارو',
	);
	$i = 0;
	foreach ( $banners as $b ) {
		if ( false !== mb_strpos( $b->post_title, 'لبزی' ) || false !== mb_strpos( $b->post_title, 'لورم' ) || strlen( $b->post_title ) < 3 ) {
			wp_update_post(
				array(
					'ID'         => $b->ID,
					'post_title' => $titles[ $i % count( $titles ) ],
				)
			);
			$i++;
		}
	}
	if ( $i ) {
		$log[] = sprintf( '%d عنوان بنر صفحه اصلی اصلاح شد.', $i );
	}

	delete_transient( 'storeplus_child_hero_slides_v1' );
	delete_transient( 'storeplus_child_home_banners_v1' );

	$log[] = 'نوار بالای صفحه به فروشگاه لینک شد؛ کش بنر/اسلایدر پاک شد.';
	return $log;
}
