<?php
/**
 * Admin: چک‌لیست لانچ گوگل + AI برای رستا دارو.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register menu under Tools.
 */
function storeplus_child_launch_checklist_menu() {
	add_management_page(
		'چک‌لیست لانچ SEO و AI',
		'لانچ SEO / AI',
		'manage_options',
		'rasta-launch-seo-ai',
		'storeplus_child_launch_checklist_render'
	);
}
add_action( 'admin_menu', 'storeplus_child_launch_checklist_menu' );

/**
 * Lightweight HTTP probe (HEAD, then GET fallback).
 *
 * @param string $url Absolute URL.
 * @return array{ok:bool,code:int,detail:string}
 */
function storeplus_child_launch_http_probe( $url ) {
	$args = array(
		'timeout'     => 8,
		'redirection' => 3,
		'sslverify'   => false,
		'headers'     => array( 'Accept' => '*/*' ),
	);
	$res = wp_remote_head( $url, $args );
	if ( is_wp_error( $res ) || (int) wp_remote_retrieve_response_code( $res ) >= 400 ) {
		$res = wp_remote_get(
			$url,
			array_merge(
				$args,
				array(
					'headers' => array( 'Accept' => '*/*', 'Range' => 'bytes=0-0' ),
				)
			)
		);
	}
	if ( is_wp_error( $res ) ) {
		return array(
			'ok'     => false,
			'code'   => 0,
			'detail' => $res->get_error_message(),
		);
	}
	$code = (int) wp_remote_retrieve_response_code( $res );
	return array(
		'ok'     => $code >= 200 && $code < 400,
		'code'   => $code,
		'detail' => 'HTTP ' . $code . ' — ' . $url,
	);
}

/**
 * Live status row helpers.
 *
 * @return array<int,array{ok:bool,label:string,detail:string}>
 */
function storeplus_child_launch_checklist_status() {
	$rows   = array();
	$public = (int) get_option( 'blog_public' ) === 1;
	$rows[] = array(
		'ok'     => $public,
		'label'  => 'سایت برای موتورهای جستجو قابل ایندکس است (Settings → Reading)',
		'detail' => $public ? 'blog_public = 1' : 'فعلاً «Discourage search engines» روشن است — روی سرور واقعی باید خاموش شود',
	);

	$https = is_ssl() || ( 0 === strpos( home_url(), 'https://' ) );
	$rows[] = array(
		'ok'     => $https,
		'label'  => 'HTTPS روی دامنه اصلی',
		'detail' => $https ? home_url( '/' ) : 'روی لوکال طبیعی است؛ قبل از لانچ SSL اجباری است',
	);

	$yoast = defined( 'WPSEO_VERSION' );
	$rows[] = array(
		'ok'     => $yoast,
		'label'  => 'Yoast SEO فعال',
		'detail' => $yoast ? 'نسخه ' . WPSEO_VERSION : 'افزونه را فعال کنید',
	);

	$endpoints = array(
		array(
			'label' => 'فایل llms.txt برای مدل‌های زبانی (HTTP واقعی)',
			'url'   => home_url( '/llms.txt' ),
		),
		array(
			'label' => 'کاتالوگ AI محصولات /ai-catalog.md (HTTP واقعی)',
			'url'   => home_url( '/ai-catalog.md' ),
		),
		array(
			'label' => 'robots.txt در دسترس (HTTP واقعی)',
			'url'   => home_url( '/robots.txt' ),
		),
		array(
			'label' => 'سایت‌مپ XML (HTTP واقعی)',
			'url'   => defined( 'WPSEO_VERSION' ) ? home_url( '/sitemap_index.xml' ) : home_url( '/wp-sitemap.xml' ),
		),
	);
	foreach ( $endpoints as $ep ) {
		$probe  = storeplus_child_launch_http_probe( $ep['url'] );
		$rows[] = array(
			'ok'     => $probe['ok'],
			'label'  => $ep['label'],
			'detail' => $probe['detail'],
		);
	}

	$c = function_exists( 'storeplus_child_contact' ) ? storeplus_child_contact() : array();
	$placeholder_phone = ! empty( $c['mobile_tel'] ) && false !== strpos( $c['mobile_tel'], '1234567' );
	$rows[] = array(
		'ok'     => ! $placeholder_phone,
		'label'  => 'شماره تماس / واتساپ واقعی (نه تست)',
		'detail' => $placeholder_phone
			? 'هنوز اعداد نمونه است — گزینه sp_store_contact را با شماره واقعی پر کنید'
			: ( $c['mobile_display'] ?? 'OK' ),
	);

	$products = function_exists( 'wc_get_products' )
		? count( wc_get_products( array( 'limit' => 1, 'status' => 'publish', 'return' => 'ids' ) ) )
		: 0;
	$rows[] = array(
		'ok'     => $products > 0,
		'label'  => 'حداقل یک محصول منتشرشده',
		'detail' => $products ? 'WooCommerce آماده است' : 'محصولی پیدا نشد',
	);

	$faq_page = get_page_by_path( 'سوالات-متداول' );
	if ( ! $faq_page ) {
		$faq_page = get_page_by_path( 'soalat-motedavel' );
	}
	$rows[] = array(
		'ok'     => (bool) $faq_page,
		'label'  => 'صفحه سوالات متداول برای FAQPage',
		'detail' => $faq_page ? get_permalink( $faq_page ) : 'اسلاگ سوالات-متداول پیدا نشد',
	);

	$seo_menu = wp_get_nav_menu_object( 'منو فوتر سئو' );
	$rows[]   = array(
		'ok'     => (bool) $seo_menu,
		'label'  => 'منوی فوتر سئو (لینک داخلی قانونی)',
		'detail' => $seo_menu ? 'منو فوتر سئو آماده است' : 'یک‌بار seo-enrich را اجرا کنید یا لینک‌های فوتر را دستی بسازید',
	);

	return $rows;
}

/**
 * Render admin page.
 */
function storeplus_child_launch_checklist_render() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$status = storeplus_child_launch_checklist_status();
	$ok_n   = count( array_filter( $status, static function ( $r ) { return ! empty( $r['ok'] ); } ) );
	$total  = count( $status );
	?>
	<div class="wrap" dir="rtl" style="max-width:920px">
		<h1>چک‌لیست لانچ — گوگل + هوش مصنوعی</h1>
		<p style="font-size:14px;color:#3c434a">
			این صفحه مخصوص <strong>رستا دارو</strong> است. روی لوکال فقط آماده‌سازی انجام می‌شود؛ ایندکس واقعی بعد از دامنه و HTTPS است.
			ردیف‌های endpoint با درخواست HTTP واقعی از همین سرور بررسی می‌شوند.
		</p>

		<h2>وضعیت فعلی (<?php echo (int) $ok_n; ?> از <?php echo (int) $total; ?>)</h2>
		<table class="widefat striped" style="margin-bottom:1.5rem">
			<thead>
				<tr><th>وضعیت</th><th>مورد</th><th>جزئیات</th></tr>
			</thead>
			<tbody>
				<?php foreach ( $status as $row ) : ?>
					<tr>
						<td><?php echo $row['ok'] ? '✅' : '⚠️'; ?></td>
						<td><?php echo esc_html( $row['label'] ); ?></td>
						<td style="word-break:break-all"><?php echo esc_html( $row['detail'] ); ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

		<h2>۱) روز لانچ — گوگل</h2>
		<ol style="line-height:1.7">
			<li>دامنه واقعی + SSL (HTTPS اجباری، ریدایرکت HTTP→HTTPS).</li>
			<li>در <em>Settings → Reading</em> تیک «Discourage search engines» را بردارید.</li>
			<li>حساب <a href="https://search.google.com/search-console" target="_blank" rel="noopener">Google Search Console</a> بسازید، دامنه/URL-prefix را Verify کنید.</li>
			<li>سایت‌مپ را Submit کنید:
				<code><?php echo esc_html( defined( 'WPSEO_VERSION' ) ? home_url( '/sitemap_index.xml' ) : home_url( '/wp-sitemap.xml' ) ); ?></code>
			</li>
			<li>چند URL مهم را با URL Inspection ایندکس درخواست دهید: خانه، فروشگاه، ۲–۳ محصول پرفروش، ۱ مقاله راهنما، سوالات متداول.</li>
			<li>در Yoast: عنوان/توضیح سازمان، لوگو، شبکه‌های اجتماعی واقعی را یک‌بار چک کنید.</li>
			<li>Rich Results / Schema: صفحه محصول باید Product + Offer داشته باشد؛ FAQها FAQPage.</li>
		</ol>

		<h2>۲) روز لانچ — ChatGPT و مدل‌های AI</h2>
		<ol style="line-height:1.7">
			<li>بعد از انتشار دامنه، این دو آدرس را در مرورگر باز کنید و مطمئن شوید ۲۰۰ می‌دهند:
				<ul>
					<li><code><?php echo esc_html( home_url( '/llms.txt' ) ); ?></code></li>
					<li><code><?php echo esc_html( home_url( '/ai-catalog.md' ) ); ?></code></li>
				</ul>
			</li>
			<li>در <code>robots.txt</code> باید GPTBot / ClaudeBot / PerplexityBot و یک Sitemap دیده شود.</li>
			<li>نام برند یکدست باشد: «رستا دارو» / Rasta Darou در عنوان سایت، فوتر، اینستاگرام، تلگرام.</li>
			<li>لینک شبکه‌های اجتماعی واقعی در فوتر و Yoast Social (sameAs) — بدون پروفایل خالی.</li>
			<li>هر محصول توضیح یکتا داشته باشد (کپی تکراری = شانس کمتر برای استناد AI).</li>
			<li>پس از چند هفته: در ChatGPT / Perplexity بپرسید «از کجا مکمل … بخرم ایران» و ببینید آیا دامنه شما ظاهر می‌شود؛ اگر نه، محتوا و بک‌لینک را تقویت کنید.</li>
		</ol>

		<h2>۳) هفته‌های اول (رشد اعتبار)</h2>
		<ul style="line-height:1.7">
			<li>هر هفته ۱ راهنمای کاربردی (FAQ، مقایسه، نحوه مصرف عمومی بدون ادعای درمانی قطعی).</li>
			<li>نظرات واقعی خریداران روی محصول (اسکیما امتیاز بعداً قوی‌تر می‌شود).</li>
			<li>بک‌لینک طبیعی: معرفی در دایرکتوری‌های معتبر، همکاری اینفلوئنسر سلامت، صفحه اینستاگرام با لینک بیو.</li>
			<li>Core Web Vitals: LiteSpeed / کش تصویر؛ موبایل را در PageSpeed چک کنید.</li>
			<li>شماره واتساپ/تلفن واقعی را در <code>sp_store_contact</code> ذخیره کنید تا FAB و صفحات قانونی یکی شوند.</li>
		</ul>

		<h2>۴) چیزهایی که عمداً نباید بکنید</h2>
		<ul style="line-height:1.7">
			<li>خرید بک‌لینک اسپم یا محتوای AI بی‌کیفیت انبوه.</li>
			<li>ادعای درمانی قطعی برای دارو/مکمل در عنوان و Schema.</li>
			<li>مخفی‌کردن متن محصول فقط برای ربات‌ها (cloaking) — «درباره محصول» باید برای کاربر هم دیده شود.</li>
			<li>بستن ربات‌های AI در robots (GPTBot و مشابه) مگر سیاست حریم خصوصی خلاف آن باشد.</li>
			<li>انتظار ایندکس فوری لوکال — <code>*.local</code> برای گوگل و ChatGPT دیده نمی‌شود.</li>
		</ul>

		<p style="margin-top:2rem;padding:12px 14px;background:#f0f6fc;border-right:4px solid #1a5c2e">
			<strong>یادآوری:</strong> دکمه شناور پشتیبانی (واتساپ / تماس / تلگرام) روی فرانت فعال است و از همین اطلاعات تماس خوانده می‌شود.
			مسیر ادمین این چک‌لیست: <em>ابزارها ← لانچ SEO / AI</em>
			—
			قبل از لانچ حتماً <a href="<?php echo esc_url( admin_url( 'tools.php?page=rasta-smoke-production' ) ); ?>">تست دود / Harden پروداکشن</a>
			(Home · PDP · Cart · Checkout + SSL / فایل ادیت / پیشوند DB) را هم اجرا کنید.
		</p>
	</div>
	<?php
}
