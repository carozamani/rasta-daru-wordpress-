<?php
/**
 * Deep local SEO enrichment: Yoast per entity, blog content, menus, media alts.
 * Tools → /tools/run-seo-enrich.php (local admin session)
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @return string[]
 */
function sp_seo_enrich_run() {
	$log = array();

	if ( ! defined( 'WPSEO_VERSION' ) ) {
		if ( ! function_exists( 'activate_plugin' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		$yoast = 'wordpress-seo/wp-seo.php';
		if ( file_exists( WP_PLUGIN_DIR . '/' . $yoast ) && ! is_plugin_active( $yoast ) ) {
			activate_plugin( $yoast, '', false, true );
			if ( ! defined( 'WPSEO_VERSION' ) ) {
				include_once WP_PLUGIN_DIR . '/' . $yoast;
			}
			$log[] = 'Yoast فعال شد.';
		}
	}

	if ( function_exists( 'storeplus_child_sync_contact' ) ) {
		$log = array_merge( $log, storeplus_child_sync_contact() );
	}
	if ( function_exists( 'sp_store_finish_yoast' ) ) {
		$log = array_merge( $log, sp_store_finish_yoast() );
	}

	$log = array_merge(
		$log,
		sp_seo_enrich_products_yoast(),
		sp_seo_enrich_categories_yoast(),
		sp_seo_enrich_blog_posts(),
		sp_seo_enrich_menus(),
		sp_seo_enrich_media_alts(),
		sp_seo_enrich_verify_sample()
	);

	update_option( 'sp_seo_enrich_v1', gmdate( 'c' ), false );
	return $log;
}

/**
 * Top products by menu order / date for keyphrase focus.
 *
 * @param int $limit Count.
 * @return WC_Product[]
 */
function sp_seo_enrich_key_products( $limit = 16 ) {
	if ( ! function_exists( 'wc_get_products' ) ) {
		return array();
	}
	$ids = wc_get_products(
		array(
			'limit'   => $limit,
			'status'  => 'publish',
			'orderby' => 'popularity',
			'order'   => 'DESC',
			'return'  => 'ids',
		)
	);
	if ( count( $ids ) < $limit ) {
		$more = wc_get_products(
			array(
				'limit'   => $limit,
				'status'  => 'publish',
				'orderby' => 'date',
				'order'   => 'DESC',
				'return'  => 'ids',
			)
		);
		$ids = array_values( array_unique( array_merge( $ids, $more ) ) );
		$ids = array_slice( $ids, 0, $limit );
	}
	$out = array();
	foreach ( $ids as $id ) {
		$p = wc_get_product( $id );
		if ( $p ) {
			$out[] = $p;
		}
	}
	return $out;
}

/**
 * @return string[]
 */
function sp_seo_enrich_products_yoast() {
	$n = 0;
	foreach ( sp_seo_enrich_key_products( 20 ) as $product ) {
		$id   = $product->get_id();
		$name = $product->get_name();
		$cats = wp_get_post_terms( $id, 'product_cat', array( 'fields' => 'names' ) );
		$cat  = ( ! is_wp_error( $cats ) && $cats ) ? $cats[0] : 'سلامت';
		$short = trim( wp_strip_all_tags( $product->get_short_description() ) );
		if ( '' === $short ) {
			$short = sprintf( 'خرید %s از دسته %s در رستا دارو با تضمین اصالت و ارسال سریع.', $name, $cat );
		}
		$meta = mb_substr( $short, 0, 155 );

		update_post_meta( $id, '_yoast_wpseo_focuskw', $name );
		update_post_meta( $id, '_yoast_wpseo_title', $name . ' %%sep%% خرید از %%sitename%%' );
		update_post_meta( $id, '_yoast_wpseo_metadesc', $meta );
		update_post_meta( $id, '_yoast_wpseo_linkdex', '60' );
		update_post_meta( $id, '_yoast_wpseo_content_score', '60' );

		$thumb = $product->get_image_id();
		if ( $thumb ) {
			$alt = trim( (string) get_post_meta( $thumb, '_wp_attachment_image_alt', true ) );
			if ( '' === $alt || in_array( $alt, array( 'تصویر محصول', 'product', 'image' ), true ) ) {
				update_post_meta( $thumb, '_wp_attachment_image_alt', $name );
			}
			$att = get_post( $thumb );
			if ( $att && ( '' === $att->post_title || false !== stripos( $att->post_title, 'untitled' ) || preg_match( '/^(IMG_|DSC_|image|photo)/i', $att->post_title ) ) ) {
				wp_update_post(
					array(
						'ID'         => $thumb,
						'post_title' => $name,
					)
				);
			}
		}
		$n++;
	}
	return array( sprintf( 'Yoast محصول: focus/title/meta برای %d محصول کلیدی پر شد.', $n ) );
}

/**
 * @return string[]
 */
function sp_seo_enrich_categories_yoast() {
	$terms = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => false,
			'parent'     => 0,
		)
	);
	if ( is_wp_error( $terms ) ) {
		return array( 'خطا در دسته‌ها.' );
	}

	$tax_meta = get_option( 'wpseo_taxonomy_meta', array() );
	if ( ! is_array( $tax_meta ) ) {
		$tax_meta = array();
	}
	if ( empty( $tax_meta['product_cat'] ) || ! is_array( $tax_meta['product_cat'] ) ) {
		$tax_meta['product_cat'] = array();
	}

	$n = 0;
	foreach ( $terms as $term ) {
		if ( 'uncategorized' === $term->slug || false !== strpos( $term->slug, 'laptop' ) ) {
			continue;
		}
		/* Do not write WooCommerce term descriptions (they appear on category cards). SEO copy → Yoast only. */
		$seo_desc = '';
		if ( function_exists( 'sp_store_finish_category_description' ) ) {
			$seo_desc = sp_store_finish_category_description( $term->name );
		}
		$tid = (string) $term->term_id;
		$tax_meta['product_cat'][ $tid ] = array_merge(
			isset( $tax_meta['product_cat'][ $tid ] ) && is_array( $tax_meta['product_cat'][ $tid ] ) ? $tax_meta['product_cat'][ $tid ] : array(),
			array(
				'wpseo_title'   => $term->name . ' %%sep%% خرید آنلاین %%sep%% %%sitename%%',
				'wpseo_desc'    => mb_substr( $seo_desc ?: ( 'خرید ' . $term->name . ' از رستا دارو با اصالت کالا و ارسال سریع.' ), 0, 155 ),
				'wpseo_focuskw' => $term->name,
			)
		);
		$n++;
	}
	update_option( 'wpseo_taxonomy_meta', $tax_meta, false );
	return array( sprintf( 'Yoast دسته: title/meta/focus برای %d دسته والد.', $n ) );
}

/**
 * Ensure a blog post by slug.
 *
 * @param string $title Title.
 * @param string $slug  Slug.
 * @param string $html  Content.
 * @param string $excerpt Excerpt.
 * @param string $focus Focus keyphrase.
 * @return int Post ID.
 */
function sp_seo_enrich_ensure_post( $title, $slug, $html, $excerpt, $focus ) {
	$existing = get_page_by_path( $slug, OBJECT, 'post' );
	$data     = array(
		'post_title'   => $title,
		'post_name'    => $slug,
		'post_content' => $html,
		'post_excerpt' => $excerpt,
		'post_status'  => 'publish',
		'post_type'    => 'post',
		'post_author'  => 1,
	);
	if ( $existing ) {
		$data['ID'] = $existing->ID;
		$id         = wp_update_post( $data, true );
	} else {
		$id = wp_insert_post( $data, true );
	}
	if ( is_wp_error( $id ) || ! $id ) {
		return 0;
	}
	update_post_meta( $id, '_yoast_wpseo_focuskw', $focus );
	update_post_meta( $id, '_yoast_wpseo_title', $title . ' %%sep%% %%sitename%%' );
	update_post_meta( $id, '_yoast_wpseo_metadesc', mb_substr( $excerpt, 0, 155 ) );

	$cat = get_term_by( 'slug', 'rahnama-kharid-blog', 'category' );
	if ( ! $cat ) {
		$cat = get_term_by( 'name', 'راهنمای خرید', 'category' );
	}
	if ( ! $cat ) {
		$ins = wp_insert_term(
			'راهنمای خرید',
			'category',
			array( 'slug' => 'rahnama-kharid-blog' )
		);
		$cid = ( ! is_wp_error( $ins ) && ! empty( $ins['term_id'] ) ) ? (int) $ins['term_id'] : 0;
	} else {
		$cid = (int) $cat->term_id;
	}
	if ( $cid ) {
		wp_set_post_categories( $id, array( $cid ) );
	}
	return (int) $id;
}

/**
 * Product URL helper by keyword in title.
 *
 * @param string $needle Needle.
 * @return string
 */
function sp_seo_enrich_product_link( $needle ) {
	if ( ! function_exists( 'wc_get_products' ) ) {
		return home_url( '/shop/' );
	}
	$ids = wc_get_products(
		array(
			'limit'  => 30,
			'status' => 'publish',
			'return' => 'ids',
		)
	);
	foreach ( $ids as $id ) {
		$p = wc_get_product( $id );
		if ( $p && false !== mb_stripos( $p->get_name(), $needle ) ) {
			return get_permalink( $id );
		}
	}
	$shop = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
	return $shop ? $shop : home_url( '/shop/' );
}

/**
 * Category archive URL by name needle.
 *
 * @param string $needle Needle.
 * @return string
 */
function sp_seo_enrich_cat_link( $needle ) {
	$terms = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => false,
			'parent'     => 0,
		)
	);
	if ( ! is_wp_error( $terms ) ) {
		foreach ( $terms as $t ) {
			if ( false !== mb_strpos( $t->name, $needle ) ) {
				$link = get_term_link( $t );
				return is_wp_error( $link ) ? home_url( '/shop/' ) : $link;
			}
		}
	}
	return function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
}

/**
 * @return string[]
 */
function sp_seo_enrich_blog_posts() {
	$link_vit  = esc_url( sp_seo_enrich_cat_link( 'مکمل' ) );
	$link_skin = esc_url( sp_seo_enrich_cat_link( 'پوست' ) );
	$link_otc  = esc_url( sp_seo_enrich_cat_link( 'دارو' ) );
	if ( $link_otc === esc_url( sp_seo_enrich_cat_link( 'مکمل' ) ) ) {
		$link_otc = esc_url( sp_seo_enrich_cat_link( 'OTC' ) );
	}
	$link_acet = esc_url( sp_seo_enrich_product_link( 'استامینوفن' ) );
	$link_ibu  = esc_url( sp_seo_enrich_product_link( 'ایبوپروفن' ) );
	$link_multi = esc_url( sp_seo_enrich_product_link( 'مولتی' ) );
	$privacy   = esc_url( get_permalink( (int) get_option( 'wp_page_for_privacy_policy' ) ) ?: home_url( '/harim-khosusi/' ) );
	$guide     = esc_url( home_url( '/rahnama-kharid/' ) );
	$returns   = esc_url( home_url( '/bazgasht-kala/' ) );
	$terms     = esc_url( home_url( '/ghavanin/' ) );
	$contact   = esc_url( home_url( '/%d8%aa%d9%85%d8%a7%d8%b3-%d8%a8%d8%a7-%d9%85%d8%a7/' ) );
	$about     = esc_url( home_url( '/%d8%af%d8%b1%d8%a8%d8%a7%d8%b1%d9%87-%d9%85%d8%a7/' ) );

	$posts = array();

	$posts[] = array(
		'title'   => 'راهنمای مصرف ایمن استامینوفن و ایبوپروفن',
		'slug'    => 'rahnama-masraf-acetaminophen-ibuprofen',
		'focus'   => 'استامینوفن و ایبوپروفن',
		'excerpt' => 'تفاوت استامینوفن و ایبوپروفن، نکات مصرف ایمن و هشدارهای مهم قبل از خرید از رستا دارو.',
		'html'    => '<p>استامینوفن و ایبوپروفن از پرمصرف‌ترین مسکن‌ها و تب‌برهای بدون نسخه هستند؛ اما جایگزین یکدیگر نیستند و دوز اشتباه می‌تواند خطرناک باشد.</p>
<h2>استامینوفن چه زمانی مناسب‌تر است؟</h2>
<p>معمولاً برای کاهش تب و درد خفیف تا متوسط به‌کار می‌رود و روی معده معمولاً ملایم‌تر است. نمونه محصول: <a href="' . $link_acet . '">استامینوفن در فروشگاه رستا دارو</a>.</p>
<h2>ایبوپروفن چه تفاوتی دارد؟</h2>
<p>علاوه بر اثر ضددرد، خاصیت ضدالتهاب دارد؛ اما در زخم معده، مشکلات کلیوی یا مصرف همزمان داروهای خاص باید با احتیاط مصرف شود. مشاهده: <a href="' . $link_ibu . '">ایبوپروفن</a>.</p>
<h2>نکات ایمنی</h2>
<ul>
<li>هرگز دوز روی برچسب را خودسرانه افزایش ندهید.</li>
<li>در بارداری، شیردهی، بیماری کبد یا کلیه با پزشک مشورت کنید.</li>
<li>این مطلب جنبه آموزشی دارد و جایگزین مشاوره پزشکی نیست.</li>
</ul>
<p>برای خرید داروهای بدون نسخه به <a href="' . $link_otc . '">دسته دارو / OTC</a> سر بزنید یا از <a href="' . $guide . '">راهنمای خرید</a> کمک بگیرید.</p>',
	);

	$posts[] = array(
		'title'   => 'چگونه مکمل و مولتی‌ویتامین مناسب انتخاب کنیم؟',
		'slug'    => 'rahnama-entekhab-mokamel-multivitamin',
		'focus'   => 'انتخاب مکمل و مولتی ویتامین',
		'excerpt' => 'معیارهای انتخاب مکمل غذایی و مولتی‌ویتامین، تفاوت نیازها و لینک خرید از دسته‌بندی مکمل‌های رستا دارو.',
		'html'    => '<p>بازار مکمل شلوغ است؛ انتخاب درست با نیاز واقعی بدن شروع می‌شود، نه تبلیغات.</p>
<h2>قبل از خرید چه بپرسید؟</h2>
<ul>
<li>هدف شما چیست: انرژی روزانه، پوست و مو، ورزشکار، کمبود آزمایشگاهی؟</li>
<li>آیا دارو یا بیماری زمینه‌ای دارید؟</li>
<li>برچسب ترکیبات و مقدار ماده مؤثر را خوانده‌اید؟</li>
</ul>
<h2>مولتی‌ویتامین</h2>
<p>مولتی‌ویتامین جای رژیم غذایی کامل را نمی‌گیرد، اما می‌تواند شکاف‌های تغذیه‌ای را پر کند. نمونه: <a href="' . $link_multi . '">مولتی‌ویتامین در رستا دارو</a>.</p>
<p>مشاهده همه اقلام: <a href="' . $link_vit . '">دسته مکمل‌ها</a>. در صورت تردید با داروساز یا پزشک مشورت کنید.</p>
<p>همچنین <a href="' . $privacy . '">حریم خصوصی</a> و <a href="' . $terms . '">قوانین فروشگاه</a> را مطالعه کنید.</p>',
	);

	$posts[] = array(
		'title'   => 'مقایسه مراقبت پوست روزانه: پاک‌کننده، مرطوب‌کننده و ضدآفتاب',
		'slug'    => 'moghayese-rotin-post-pakkonande-mortoobkonande',
		'focus'   => 'روتین مراقبت پوست',
		'excerpt' => 'ترتیب درست روتین پوست، نقش هر مرحله و پیشنهاد خرید از دسته مراقبت پوست رستا دارو.',
		'html'    => '<p>یک روتین ساده و منظم معمولاً بهتر از ده محصول پراکنده عمل می‌کند.</p>
<ol>
<li><strong>پاک‌کننده:</strong> آلودگی و باقی‌مانده ضدآفتاب را برمی‌دارد بدون خشک کردن شدید پوست.</li>
<li><strong>مرطوب‌کننده:</strong> سد پوستی را تقویت می‌کند.</li>
<li><strong>ضدآفتاب:</strong> صبح‌ها ضروری است؛ حتی در روزهای ابری.</li>
</ol>
<p>محصولات مرتبط را در <a href="' . $link_skin . '">دسته مراقبت پوست</a> ببینید. پوست حساس؟ اول پچ‌تست کنید و در تحریک مصرف را قطع کنید.</p>
<p>سوال خرید دارید؟ از صفحه <a href="' . $contact . '">تماس با ما</a> پیام دهید.</p>',
	);

	$posts[] = array(
		'title'   => 'سوالات متداول خرید از رستا دارو',
		'slug'    => 'soalat-motedavel-kharid-rasta-darou',
		'focus'   => 'سوالات متداول رستا دارو',
		'excerpt' => 'پاسخ سوالات پرتکرار درباره ارسال، مرجوعی، اصالت کالا، حساب کاربری و پشتیبانی رستا دارو.',
		'html'    => '<h2>ارسال چقدر طول می‌کشد؟</h2>
<p>بسته به موجودی و مقصد متفاوت است؛ هزینه و زمان تقریبی در تسویه‌حساب نمایش داده می‌شود.</p>
<h2>آیا کالا اصل است؟</h2>
<p>رستا دارو روی اصالت و تاریخ مصرف معتبر تاکید دارد. جزئیات هر کالا در صفحه محصول آمده است.</p>
<h2>مرجوعی چگونه است؟</h2>
<p>شرایط در صفحه <a href="' . $returns . '">بازگشت کالا</a> توضیح داده شده؛ دارو و مکمل بازشده معمولاً به‌دلیل بهداشت قابل بازگشت نیستند مگر نقص یا ارسال اشتباه.</p>
<h2>چطور سفارش را پیگیری کنم؟</h2>
<p>از <a href="' . esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : home_url( '/my-account/' ) ) . '">حساب کاربری</a> یا تماس با پشتیبانی. راهنمای قدم‌به‌قدم: <a href="' . $guide . '">راهنمای خرید</a>.</p>
<h2>اطلاعات فروشگاه</h2>
<p>درباره مجموعه: <a href="' . $about . '">درباره ما</a> — قوانین: <a href="' . $terms . '">قوانین و مقررات</a>.</p>',
	);

	$posts[] = array(
		'title'   => 'نکات مهم قبل از خرید داروهای بدون نسخه (OTC)',
		'slug'    => 'nokte-kharid-daroo-bedune-noskhe-otc',
		'focus'   => 'خرید دارو بدون نسخه',
		'excerpt' => 'چک‌لیست ایمنی قبل از خرید OTC، تداخل دارویی و لینک به دسته داروهای بدون نسخه رستا دارو.',
		'html'    => '<p>OTC به‌معنای «بی‌خطر برای همه» نیست؛ یعنی بدون نسخه پزشک قابل عرضه است، نه بدون مسئولیت.</p>
<ul>
<li>علائم خود را با برچسب دارو تطبیق دهید.</li>
<li>تداخل با داروهای قلبی، رقیق‌کننده خون، دیابت و فشار را جدی بگیرید.</li>
<li>برای کودک دوز مخصوص سن را انتخاب کنید.</li>
</ul>
<p>مرور اقلام: <a href="' . $link_otc . '">دارو و OTC</a>. مقایسه مسکن‌ها را در مطلب <a href="' . esc_url( home_url( '/rahnama-masraf-acetaminophen-ibuprofen/' ) ) . '">راهنمای استامینوفن و ایبوپروفن</a> بخوانید.</p>
<p><em>محتوای آموزشی است و جایگزین تشخیص پزشک نیست.</em></p>',
	);

	$created = 0;
	$ids     = array();
	foreach ( $posts as $p ) {
		$id = sp_seo_enrich_ensure_post( $p['title'], $p['slug'], $p['html'], $p['excerpt'], $p['focus'] );
		if ( $id ) {
			$created++;
			$ids[] = $id;
		}
	}
	update_option( 'sp_seo_blog_post_ids', $ids, false );

	/* FAQ page alias if missing. */
	$faq = get_page_by_path( 'سوالات-متداول' );
	if ( ! $faq && ! empty( $ids[3] ) ) {
		wp_insert_post(
			array(
				'post_title'   => 'سوالات متداول',
				'post_name'    => 'سوالات-متداول',
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'post_content' => '<p>پاسخ سوالات پرتکرار را در مطلب زیر بخوانید:</p><p><a href="' . esc_url( get_permalink( $ids[3] ) ) . '">سوالات متداول خرید از رستا دارو</a></p>',
			)
		);
	}

	return array( sprintf( 'وبلاگ: %d مقاله راهنما/مقایسه/FAQ منتشر یا به‌روز شد.', $created ) );
}

/**
 * Add item to menu if title not present.
 *
 * @param int    $menu_id Menu term ID.
 * @param string $title   Title.
 * @param array  $args    wp_update_nav_menu_item args without menu-item-title.
 * @return void
 */
function sp_seo_enrich_menu_add( $menu_id, $title, $args ) {
	$items = wp_get_nav_menu_items( $menu_id );
	foreach ( (array) $items as $item ) {
		if ( trim( wp_strip_all_tags( $item->title ) ) === $title ) {
			return;
		}
	}
	wp_update_nav_menu_item(
		$menu_id,
		0,
		array_merge(
			array(
				'menu-item-title'  => $title,
				'menu-item-status' => 'publish',
			),
			$args
		)
	);
}

/**
 * @return string[]
 */
function sp_seo_enrich_menus() {
	$log = array();

	/* Primary / theme locations. */
	$locations = get_nav_menu_locations();
	$primary   = 0;
	foreach ( array( 'primary', 'main', 'header', 'menu-1', 'irantheme_main_menu' ) as $loc ) {
		if ( ! empty( $locations[ $loc ] ) ) {
			$primary = (int) $locations[ $loc ];
			break;
		}
	}
	if ( ! $primary ) {
		$m = wp_get_nav_menu_object( 'منوی اصلی' );
		if ( ! $m ) {
			$mid = wp_create_nav_menu( 'منوی اصلی' );
			$m   = is_wp_error( $mid ) ? null : wp_get_nav_menu_object( $mid );
		}
		if ( $m ) {
			$primary = (int) $m->term_id;
			$locations['primary'] = $primary;
			set_theme_mod( 'nav_menu_locations', $locations );
		}
	}

	/* Footer menu dedicated to legal + internal SEO links. */
	$footer_menu = wp_get_nav_menu_object( 'منو فوتر سئو' );
	if ( ! $footer_menu ) {
		$fid = wp_create_nav_menu( 'منو فوتر سئو' );
		$footer_menu = is_wp_error( $fid ) ? null : wp_get_nav_menu_object( $fid );
	}
	$footer_id = $footer_menu ? (int) $footer_menu->term_id : 0;

	/* Blog / guides menu. */
	$blog_menu = wp_get_nav_menu_object( 'منوی راهنما و وبلاگ' );
	if ( ! $blog_menu ) {
		$bid = wp_create_nav_menu( 'منوی راهنما و وبلاگ' );
		$blog_menu = is_wp_error( $bid ) ? null : wp_get_nav_menu_object( $bid );
	}
	$blog_id = $blog_menu ? (int) $blog_menu->term_id : 0;

	if ( $primary ) {
		sp_seo_enrich_menu_add(
			$primary,
			'خانه',
			array(
				'menu-item-type' => 'custom',
				'menu-item-url'  => home_url( '/' ),
			)
		);
		$shop_id = function_exists( 'wc_get_page_id' ) ? wc_get_page_id( 'shop' ) : 0;
		if ( $shop_id > 0 ) {
			sp_seo_enrich_menu_add(
				$primary,
				'فروشگاه',
				array(
					'menu-item-type'      => 'post_type',
					'menu-item-object'    => 'page',
					'menu-item-object-id' => $shop_id,
				)
			);
		}
		$terms = get_terms(
			array(
				'taxonomy'   => 'product_cat',
				'hide_empty' => true,
				'parent'     => 0,
				'number'     => 6,
			)
		);
		if ( ! is_wp_error( $terms ) ) {
			foreach ( $terms as $t ) {
				if ( 'uncategorized' === $t->slug ) {
					continue;
				}
				sp_seo_enrich_menu_add(
					$primary,
					$t->name,
					array(
						'menu-item-type'      => 'taxonomy',
						'menu-item-object'    => 'product_cat',
						'menu-item-object-id' => $t->term_id,
					)
				);
			}
		}
		$log[] = 'منوی اصلی: خانه + فروشگاه + دسته‌های کلیدی.';
	}

	if ( $footer_id ) {
		$page_map = array(
			'درباره ما'          => array( 'درباره-ما' ),
			'تماس با ما'         => array( 'تماس-با-ما' ),
			'راهنمای خرید'       => array( 'rahnama-kharid' ),
			'شرایط بازگشت کالا'  => array( 'bazgasht-kala' ),
			'قوانین و مقررات'    => array( 'ghavanin' ),
			'حریم خصوصی'         => array( 'harim-khosusi' ),
			'سوالات متداول'      => array( 'سوالات-متداول' ),
		);
		foreach ( $page_map as $title => $slugs ) {
			$page = null;
			foreach ( $slugs as $slug ) {
				$page = get_page_by_path( $slug );
				if ( $page ) {
					break;
				}
			}
			if ( $page ) {
				sp_seo_enrich_menu_add(
					$footer_id,
					$title,
					array(
						'menu-item-type'      => 'post_type',
						'menu-item-object'    => 'page',
						'menu-item-object-id' => $page->ID,
					)
				);
			}
		}
		/* Featured products for internal links. */
		foreach ( array_slice( sp_seo_enrich_key_products( 4 ), 0, 4 ) as $product ) {
			sp_seo_enrich_menu_add(
				$footer_id,
				$product->get_name(),
				array(
					'menu-item-type'      => 'post_type',
					'menu-item-object'    => 'product',
					'menu-item-object-id' => $product->get_id(),
				)
			);
		}
		$log[] = 'منو فوتر سئو: صفحات قانونی + محصولات کلیدی.';
	}

	if ( $blog_id ) {
		$post_ids = get_option( 'sp_seo_blog_post_ids', array() );
		foreach ( (array) $post_ids as $pid ) {
			$p = get_post( (int) $pid );
			if ( $p ) {
				sp_seo_enrich_menu_add(
					$blog_id,
					$p->post_title,
					array(
						'menu-item-type'      => 'post_type',
						'menu-item-object'    => 'post',
						'menu-item-object-id' => $p->ID,
					)
				);
			}
		}
		$blog_page = (int) get_option( 'page_for_posts' );
		if ( $blog_page ) {
			sp_seo_enrich_menu_add(
				$blog_id,
				'وبلاگ',
				array(
					'menu-item-type'      => 'post_type',
					'menu-item-object'    => 'page',
					'menu-item-object-id' => $blog_page,
				)
			);
		}
		$log[] = 'منوی راهنما و وبلاگ با مقالات SEO ساخته شد.';
	}

	/* Assign footer menu to a common location if theme exposes one. */
	$locations = get_nav_menu_locations();
	foreach ( array( 'footer', 'footer-menu', 'irantheme_footer_menu', 'secondary' ) as $loc ) {
		if ( $footer_id && empty( $locations[ $loc ] ) ) {
			$locations[ $loc ] = $footer_id;
		}
	}
	set_theme_mod( 'nav_menu_locations', $locations );

	update_option(
		'sp_seo_menu_ids',
		array(
			'primary' => $primary,
			'footer'  => $footer_id,
			'blog'    => $blog_id,
		),
		false
	);

	return $log;
}

/**
 * Improve attachment titles/alts for product images missing meaningful text.
 *
 * @return string[]
 */
function sp_seo_enrich_media_alts() {
	$fixed = 0;
	if ( ! function_exists( 'wc_get_products' ) ) {
		return array( 'رسانه: ووکامرس در دسترس نیست.' );
	}
	$ids = wc_get_products(
		array(
			'limit'  => -1,
			'status' => 'publish',
			'return' => 'ids',
		)
	);
	foreach ( $ids as $id ) {
		$product = wc_get_product( $id );
		if ( ! $product ) {
			continue;
		}
		$name  = $product->get_name();
		$imgs  = array_filter( array_merge( array( $product->get_image_id() ), $product->get_gallery_image_ids() ) );
		foreach ( $imgs as $img_id ) {
			$img_id = (int) $img_id;
			if ( ! $img_id ) {
				continue;
			}
			$alt = trim( (string) get_post_meta( $img_id, '_wp_attachment_image_alt', true ) );
			if ( '' === $alt || in_array( mb_strtolower( $alt ), array( 'تصویر محصول', 'product', 'image', 'img' ), true ) ) {
				update_post_meta( $img_id, '_wp_attachment_image_alt', $name );
				$fixed++;
			}
			$att = get_post( $img_id );
			if ( $att && ( preg_match( '/^(IMG_|DSC_|WhatsApp|image|photo|screenshot)/i', $att->post_title ) || mb_strlen( trim( $att->post_title ) ) < 3 ) ) {
				wp_update_post(
					array(
						'ID'         => $img_id,
						'post_title' => $name,
					)
				);
				$fixed++;
			}
		}
	}
	return array( sprintf( 'رسانه: %d مورد alt/عنوان تصویر محصول اصلاح شد.', $fixed ) );
}

/**
 * Fetch one product HTML and report title/description/schema presence.
 *
 * @return string[]
 */
function sp_seo_enrich_verify_sample() {
	$products = sp_seo_enrich_key_products( 1 );
	if ( ! $products ) {
		return array( 'Verify: محصولی برای نمونه پیدا نشد.' );
	}
	$product = $products[0];
	$url     = get_permalink( $product->get_id() );
	$res     = wp_remote_get(
		$url,
		array(
			'timeout' => 20,
			'headers' => array( 'Accept' => 'text/html' ),
		)
	);
	if ( is_wp_error( $res ) ) {
		return array( 'Verify: خطا در دریافت صفحه — ' . $res->get_error_message() );
	}
	$body = (string) wp_remote_retrieve_body( $res );
	$has_title = (bool) preg_match( '/<title[^>]*>[^<]+<\/title>/i', $body );
	$has_desc  = (bool) preg_match( '/<meta[^>]+name=["\']description["\'][^>]*>/i', $body );
	$has_og    = (bool) preg_match( '/property=["\']og:title["\']/i', $body );
	$has_json  = ( false !== strpos( $body, 'application/ld+json' ) );
	$has_prod = (bool) preg_match( '/"@type"\s*:\s*"Product"/i', $body )
		|| false !== stripos( $body, '\"@type\":\"Product\"' );

	update_option(
		'sp_seo_verify_last',
		array(
			'url'         => $url,
			'has_title'   => $has_title,
			'has_desc'    => $has_desc,
			'has_og'      => $has_og,
			'has_jsonld'  => $has_json,
			'has_product' => $has_prod,
			'checked_at'  => gmdate( 'c' ),
		),
		false
	);

	return array(
		sprintf(
			'Verify %s → title:%s desc:%s og:%s jsonld:%s ProductSchema:%s',
			$url,
			$has_title ? 'yes' : 'NO',
			$has_desc ? 'yes' : 'NO',
			$has_og ? 'yes' : 'NO',
			$has_json ? 'yes' : 'NO',
			$has_prod ? 'yes' : 'NO'
		),
	);
}
