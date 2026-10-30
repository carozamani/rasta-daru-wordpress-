<?php
/**
 * Smoke tests (home / PDP / cart / checkout) + production harden checklist.
 *
 * Admin: Tools → تست دود / پروداکشن
 * CLI:   php tools/run-smoke.php
 *
 * Local-safe: HTTP probes use cURL (bypass WP_HTTP_BLOCK_EXTERNAL).
 * Does NOT change DB table prefix (destructive) — only reports it.
 *
 * @package StorePlus_Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @return bool
 */
function storeplus_child_smoke_enabled() {
	return (bool) apply_filters( 'storeplus_child_smoke_enabled', true );
}

/**
 * Register Tools page.
 */
function storeplus_child_smoke_menu() {
	if ( ! storeplus_child_smoke_enabled() ) {
		return;
	}
	add_management_page(
		'تست دود و Harden پروداکشن',
		'تست دود / پروداکشن',
		'manage_options',
		'rasta-smoke-production',
		'storeplus_child_smoke_render'
	);
}
add_action( 'admin_menu', 'storeplus_child_smoke_menu' );

/**
 * Current environment string.
 *
 * @return string
 */
function storeplus_child_smoke_env() {
	if ( function_exists( 'wp_get_environment_type' ) ) {
		return (string) wp_get_environment_type();
	}
	return defined( 'WP_ENVIRONMENT_TYPE' ) ? (string) WP_ENVIRONMENT_TYPE : 'production';
}

/**
 * @return bool
 */
function storeplus_child_smoke_is_local_env() {
	return in_array( storeplus_child_smoke_env(), array( 'local', 'development' ), true );
}

/**
 * HTTP GET that does not use the WP HTTP API (so Local block-external cannot break probes).
 *
 * @param string $url Absolute URL.
 * @return array{ok:bool,code:int,final_url:string,body:string,detail:string,ms:int}
 */
function storeplus_child_smoke_http_get( $url ) {
	$url  = esc_url_raw( $url );
	$t0   = microtime( true );
	$code = 0;
	$body = '';
	$final = $url;
	$err  = '';

	if ( function_exists( 'curl_init' ) ) {
		$ch = curl_init( $url );
		curl_setopt_array(
			$ch,
			array(
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_MAXREDIRS      => 5,
				CURLOPT_CONNECTTIMEOUT => 8,
				CURLOPT_TIMEOUT        => 20,
				CURLOPT_SSL_VERIFYPEER => false,
				CURLOPT_SSL_VERIFYHOST => 0,
				CURLOPT_USERAGENT      => 'StorePlus-Smoke/1.0',
				CURLOPT_HTTPHEADER     => array( 'Accept: text/html,application/xhtml+xml;q=0.9,*/*;q=0.8' ),
			)
		);
		$raw = curl_exec( $ch );
		if ( false === $raw ) {
			$err = curl_error( $ch );
		} else {
			$body  = (string) $raw;
			$code  = (int) curl_getinfo( $ch, CURLINFO_RESPONSE_CODE );
			$final = (string) curl_getinfo( $ch, CURLINFO_EFFECTIVE_URL );
		}
		curl_close( $ch );
	} else {
		$ctx = stream_context_create(
			array(
				'http' => array(
					'timeout'         => 20,
					'follow_location' => 1,
					'max_redirects'   => 5,
					'header'          => "User-Agent: StorePlus-Smoke/1.0\r\n",
					'ignore_errors'   => true,
				),
				'ssl'  => array(
					'verify_peer'      => false,
					'verify_peer_name' => false,
				),
			)
		);
		$raw = @file_get_contents( $url, false, $ctx ); // phpcs:ignore
		if ( false === $raw ) {
			$err = 'file_get_contents failed';
		} else {
			$body = (string) $raw;
			if ( ! empty( $http_response_header[0] ) && preg_match( '/\s(\d{3})\s/', $http_response_header[0], $m ) ) {
				$code = (int) $m[1];
			} else {
				$code = 200;
			}
		}
	}

	$ms = (int) round( ( microtime( true ) - $t0 ) * 1000 );
	$ok = ( '' === $err && $code >= 200 && $code < 400 );
	return array(
		'ok'        => $ok,
		'code'      => $code,
		'final_url' => $final,
		'body'      => $body,
		'detail'    => $err
			? $err
			: ( 'HTTP ' . $code . ' · ' . $ms . 'ms · ' . $final ),
		'ms'        => $ms,
	);
}

/**
 * Pick one published product URL for PDP smoke.
 *
 * @return string
 */
function storeplus_child_smoke_product_url() {
	if ( ! function_exists( 'wc_get_products' ) ) {
		return '';
	}
	$ids = wc_get_products(
		array(
			'limit'  => 1,
			'status' => 'publish',
			'return' => 'ids',
			'orderby'=> 'date',
			'order'  => 'DESC',
		)
	);
	if ( empty( $ids[0] ) ) {
		return '';
	}
	$url = get_permalink( (int) $ids[0] );
	return is_string( $url ) ? $url : '';
}

/**
 * Assert markers in HTML body.
 *
 * @param string               $body HTML.
 * @param array<string,string> $needles needle => label.
 * @return array{ok:bool,missing:string[]}
 */
function storeplus_child_smoke_assert_markers( $body, array $needles ) {
	$missing = array();
	foreach ( $needles as $needle => $label ) {
		if ( false === strpos( $body, $needle ) ) {
			$missing[] = $label . ' (`' . $needle . '`)';
		}
	}
	return array(
		'ok'      => empty( $missing ),
		'missing' => $missing,
	);
}

/**
 * Run front-end smoke suite.
 *
 * @return array<int,array{ok:bool,id:string,label:string,detail:string}>
 */
function storeplus_child_smoke_run_pages() {
	$rows = array();

	/* --- HOME --- */
	$home = storeplus_child_smoke_http_get( home_url( '/' ) );
	$hm   = storeplus_child_smoke_assert_markers(
		$home['body'],
		array(
			'irantheme-footer-nav-section' => 'فوتر سایت',
			'data-sp-php-footer'           => 'فوتر PHP',
			'sp-php-home-order'            => 'ترتیب سکشن PHP',
			'storeplus-child'              => 'دارایی‌های child theme',
		)
	);
	$rows[] = array(
		'ok'     => $home['ok'] && $hm['ok'],
		'id'     => 'home',
		'label'  => 'Home — صفحه اصلی',
		'detail' => $home['ok']
			? ( $hm['ok'] ? $home['detail'] : ( 'مارکر کم: ' . implode( '، ', $hm['missing'] ) ) )
			: $home['detail'],
	);

	/* --- PDP --- */
	$purl = storeplus_child_smoke_product_url();
	if ( ! $purl ) {
		$rows[] = array(
			'ok'     => false,
			'id'     => 'pdp',
			'label'  => 'PDP — صفحه محصول',
			'detail' => 'هیچ محصول publish شده‌ای پیدا نشد',
		);
	} else {
		$pdp = storeplus_child_smoke_http_get( $purl );
		$pm  = storeplus_child_smoke_assert_markers(
			$pdp['body'],
			array(
				'woocommerce'        => 'کلاس WooCommerce',
				'product'            => 'مارکاپ محصول',
				'data-sp-php-footer' => 'فوتر PHP',
			)
		);
		$rows[] = array(
			'ok'     => $pdp['ok'] && $pm['ok'],
			'id'     => 'pdp',
			'label'  => 'PDP — صفحه محصول',
			'detail' => $pdp['ok']
				? ( $pm['ok'] ? $pdp['detail'] : ( 'مارکر کم: ' . implode( '، ', $pm['missing'] ) ) )
				: $pdp['detail'],
		);
	}

	/* --- CART --- */
	$cart_url = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : home_url( '/cart/' );
	$cart     = storeplus_child_smoke_http_get( $cart_url );
	$cm       = storeplus_child_smoke_assert_markers(
		$cart['body'],
		array(
			'woocommerce'            => 'WooCommerce',
			'data-sp-php-footer'     => 'فوتر PHP',
		)
	);
	$rows[] = array(
		'ok'     => $cart['ok'] && $cm['ok'],
		'id'     => 'cart',
		'label'  => 'Cart — سبد خرید',
		'detail' => $cart['ok']
			? ( $cm['ok'] ? $cart['detail'] : ( 'مارکر کم: ' . implode( '، ', $cm['missing'] ) ) )
			: $cart['detail'],
	);

	/* --- CHECKOUT ---
	 * Guests are redirected to login/register (require-login-checkout). That is success.
	 */
	$checkout_url = function_exists( 'wc_get_checkout_url' ) ? wc_get_checkout_url() : home_url( '/checkout/' );
	$chk          = storeplus_child_smoke_http_get( $checkout_url );
	$final        = (string) $chk['final_url'];
	$is_checkout  = ( false !== strpos( $final, 'checkout' ) );
	$is_auth_gate = ( false !== strpos( $final, 'my-account' ) )
		|| ( false !== strpos( $final, 'login' ) )
		|| ( false !== strpos( $chk['body'], 'sp_need_auth' ) )
		|| ( false !== strpos( $final, 'sp_need_auth' ) );
	$chk_ok = $chk['ok'] && ( $is_checkout || $is_auth_gate );
	$rows[] = array(
		'ok'     => $chk_ok,
		'id'     => 'checkout',
		'label'  => 'Checkout — تسویه‌حساب / دروازه ورود',
		'detail' => $chk_ok
			? ( $is_auth_gate
				? ( 'دروازه لاگین فعال است (مهمان → حساب) · ' . $chk['detail'] )
				: $chk['detail'] )
			: ( $chk['ok']
				? ( 'انتظار checkout یا ریدایرکت auth؛ رسید به: ' . $final )
				: $chk['detail'] ),
	);

	return $rows;
}

/**
 * Production / security harden rows (read-only checks).
 *
 * @return array<int,array{ok:bool,id:string,label:string,detail:string,severity:bool}>
 */
function storeplus_child_smoke_harden_rows() {
	global $table_prefix;
	$rows   = array();
	$env    = storeplus_child_smoke_env();
	$local  = storeplus_child_smoke_is_local_env();
	$prod   = ! $local;

	$rows[] = array(
		'ok'       => true,
		'id'       => 'env',
		'label'    => 'محیط فعلی (WP_ENVIRONMENT_TYPE)',
		'detail'   => $env . ( $local ? ' — روی Local سخت‌گیری پروداکشن عمداً نرم است' : ' — حالت پروداکشن' ),
		'severity' => false,
	);

	$https = is_ssl() || ( 0 === strpos( home_url(), 'https://' ) );
	$rows[] = array(
		'ok'       => $local ? true : $https,
		'id'       => 'https',
		'label'    => 'HTTPS روی دامنه',
		'detail'   => $https
			? home_url( '/' )
			: ( $local
				? 'روی Local طبیعی است؛ قبل از لانچ SSL + ریدایرکت HTTP→HTTPS الزامی است'
				: 'FAIL: پروداکشن بدون HTTPS' ),
		'severity' => $prod && ! $https,
	);

	$force_ssl = defined( 'FORCE_SSL_ADMIN' ) && FORCE_SSL_ADMIN;
	$rows[]    = array(
		'ok'       => $local ? true : $force_ssl,
		'id'       => 'force_ssl_admin',
		'label'    => 'FORCE_SSL_ADMIN',
		'detail'   => $force_ssl
			? 'true'
			: ( $local
				? 'فعلاً false روی Local — با WP_ENVIRONMENT_TYPE=production خودکار true می‌شود'
				: 'باید true باشد' ),
		'severity' => $prod && ! $force_ssl,
	);

	$file_edit = defined( 'DISALLOW_FILE_EDIT' ) && DISALLOW_FILE_EDIT;
	$rows[]    = array(
		'ok'       => $file_edit,
		'id'       => 'disallow_file_edit',
		'label'    => 'DISALLOW_FILE_EDIT (ویرایشگر فایل تم/پلاگین)',
		'detail'   => $file_edit ? 'true — ویرایشگر فایل در ادمین بسته است' : 'باید true باشد',
		'severity' => ! $file_edit,
	);

	$file_mods = defined( 'DISALLOW_FILE_MODS' ) && DISALLOW_FILE_MODS;
	$rows[]    = array(
		'ok'       => $local ? true : $file_mods,
		'id'       => 'disallow_file_mods',
		'label'    => 'DISALLOW_FILE_MODS (نصب/آپدیت از ادمین)',
		'detail'   => $file_mods
			? 'true — نصب تم/پلاگین از ادمین بسته است'
			: ( $local
				? 'false روی Local (عمدی برای توسعه) — روی production باید true شود'
				: 'باید true باشد مگر استقرار از CI/Git' ),
		'severity' => $prod && ! $file_mods,
	);

	$prefix = is_string( $table_prefix ) ? $table_prefix : '';
	$prefix_ok = ( '' !== $prefix && 'wp_' !== $prefix );
	$rows[]    = array(
		'ok'       => $local ? true : $prefix_ok,
		'id'       => 'db_prefix',
		'label'    => 'پیشوند جداول دیتابیس ($table_prefix)',
		'detail'   => $prefix_ok
			? ( '`' . $prefix . '` — غیرپیش‌فرض' )
			: ( '`' . $prefix . '` پیش‌فرض است — هنگام مهاجرت با Duplicator به پیشوند تصادفی عوض کنید؛ اینجا خودکار تغییر نمی‌دهیم (مخرب است)' ),
		'severity' => $prod && ! $prefix_ok,
	);

	$debug = defined( 'WP_DEBUG' ) && WP_DEBUG;
	$rows[] = array(
		'ok'       => $local ? true : ! $debug,
		'id'       => 'wp_debug',
		'label'    => 'WP_DEBUG خاموش روی پروداکشن',
		'detail'   => $debug ? 'WP_DEBUG=true' : 'false',
		'severity' => $prod && $debug,
	);

	$debug_display = defined( 'WP_DEBUG_DISPLAY' ) && WP_DEBUG_DISPLAY;
	$rows[]        = array(
		'ok'       => ! $debug_display,
		'id'       => 'wp_debug_display',
		'label'    => 'WP_DEBUG_DISPLAY خاموش',
		'detail'   => $debug_display ? 'true — خطاها در فرانت چاپ می‌شوند' : 'false',
		'severity' => $debug_display,
	);

	/* xmlrpc_enabled filter returns false when MU plugin hooked __return_false. */
	$xmlrpc_enabled_val = apply_filters( 'xmlrpc_enabled', true );
	$rows[]             = array(
		'ok'       => ! $xmlrpc_enabled_val,
		'id'       => 'xmlrpc',
		'label'    => 'XML-RPC غیرفعال (mu-plugin)',
		'detail'   => ! $xmlrpc_enabled_val ? 'قفل است' : 'هنوز باز است — harden-site-security را چک کنید',
		'severity' => (bool) $xmlrpc_enabled_val,
	);

	$mu = file_exists( WP_CONTENT_DIR . '/mu-plugins/harden-site-security.php' );
	$rows[] = array(
		'ok'       => $mu,
		'id'       => 'mu_harden',
		'label'    => 'mu-plugin سخت‌گیری سایت',
		'detail'   => $mu ? 'harden-site-security.php موجود است' : 'فایل MU پیدا نشد',
		'severity' => ! $mu,
	);

	$http_block = defined( 'WP_HTTP_BLOCK_EXTERNAL' ) && WP_HTTP_BLOCK_EXTERNAL;
	$rows[]     = array(
		'ok'       => $local ? $http_block : ! $http_block,
		'id'       => 'http_block',
		'label'    => 'WP_HTTP_BLOCK_EXTERNAL',
		'detail'   => $http_block
			? ( $local
				? 'true روی Local (عمدی) — روی production باید false یا allowlist پرداخت/پیامک باشد'
				: 'true روی production می‌تواند درگاه/SMS را بشکند' )
			: 'false — درخواست‌های خروجی آزاد (یا فقط allowlist)',
		'severity' => $prod && $http_block,
	);

	$host_dyn = defined( 'WP_HOME' ) && defined( 'WP_SITEURL' ) && $prod;
	/* Dynamic host block only runs when env=local in wp-config — warn if somehow WP_HOME is host-derived on prod. */
	$rows[] = array(
		'ok'       => true,
		'id'       => 'host_header',
		'label'    => 'بلاک URL داینامیک Host-header',
		'detail'   => $local
			? 'روی Local فعال است (فقط localhost / .local / ngrok) — به production کپی نشود'
			: 'روی production نباید از HTTP_HOST برای WP_HOME استفاده شود',
		'severity' => false,
	);

	return $rows;
}

/**
 * Full report (smoke + harden).
 *
 * @return array{smoke:array,harden:array,ok_smoke:int,total_smoke:int,ok_harden:int,total_harden:int,blocking:int}
 */
function storeplus_child_smoke_full_report() {
	$smoke  = storeplus_child_smoke_run_pages();
	$harden = storeplus_child_smoke_harden_rows();
	$ok_s   = count( array_filter( $smoke, static function ( $r ) { return ! empty( $r['ok'] ); } ) );
	$ok_h   = count( array_filter( $harden, static function ( $r ) { return ! empty( $r['ok'] ); } ) );
	$block  = count( array_filter( $harden, static function ( $r ) { return ! empty( $r['blocking'] ); } ) );
	return array(
		'smoke'        => $smoke,
		'harden'       => $harden,
		'ok_smoke'     => $ok_s,
		'total_smoke'  => count( $smoke ),
		'ok_harden'    => $ok_h,
		'total_harden' => count( $harden ),
		'blocking'     => $block,
	);
}

/**
 * Admin page.
 */
function storeplus_child_smoke_render() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$run = isset( $_GET['sp_smoke_run'] ) && check_admin_referer( 'sp_smoke_run' );
	$report = null;
	if ( $run ) {
		$report = storeplus_child_smoke_full_report();
		set_transient( 'sp_smoke_last_report', $report, HOUR_IN_SECONDS );
	} else {
		$cached = get_transient( 'sp_smoke_last_report' );
		if ( is_array( $cached ) ) {
			$report = $cached;
		}
	}

	$run_url = wp_nonce_url(
		admin_url( 'tools.php?page=rasta-smoke-production&sp_smoke_run=1' ),
		'sp_smoke_run'
	);
	?>
	<div class="wrap" dir="rtl" style="max-width:960px">
		<h1>تست دود + Harden پروداکشن</h1>
		<p style="font-size:14px;color:#3c434a;line-height:1.7">
			حداقل smoke روی <strong>Home / PDP / Cart / Checkout</strong> با درخواست HTTP واقعی،
			به‌همراه چک‌لیست امنیتی SSL، ویرایش فایل، پیشوند DB و ثابت‌های wp-config.
			روی Local بعضی موارد عمداً نرم هستند؛ روی <code>production</code> سخت‌گیری خودکار می‌شود.
		</p>
		<p>
			<a class="button button-primary" href="<?php echo esc_url( $run_url ); ?>">اجرای تست دود الآن</a>
			<a class="button" href="<?php echo esc_url( admin_url( 'tools.php?page=rasta-launch-seo-ai' ) ); ?>">چک‌لیست SEO / AI</a>
		</p>
		<p style="color:#646970;font-size:13px">CLI: <code>php wp-content/themes/wp_storeplus-child/tools/run-smoke.php</code></p>

		<?php if ( $report ) : ?>
			<?php
			$smoke_pass = (int) $report['ok_smoke'] === (int) $report['total_smoke'];
			$block_n    = (int) $report['blocking'];
			?>
			<div class="notice notice-<?php echo $smoke_pass && 0 === $block_n ? 'success' : ( $smoke_pass ? 'warning' : 'error' ); ?>" style="padding:12px 14px;margin:1rem 0">
				<strong>نتیجه:</strong>
				Smoke <?php echo (int) $report['ok_smoke']; ?>/<?php echo (int) $report['total_smoke']; ?>
				· Harden <?php echo (int) $report['ok_harden']; ?>/<?php echo (int) $report['total_harden']; ?>
				<?php if ( $block_n ) : ?>
					· <span style="color:#b32d2e"><?php echo (int) $block_n; ?> مورد مسدودکننده برای لانچ</span>
				<?php endif; ?>
			</div>

			<h2>۱) Smoke صفحات فروشگاه</h2>
			<table class="widefat striped" style="margin-bottom:1.5rem">
				<thead><tr><th>وضعیت</th><th>صفحه</th><th>جزئیات</th></tr></thead>
				<tbody>
				<?php foreach ( $report['smoke'] as $row ) : ?>
					<tr>
						<td><?php echo ! empty( $row['ok'] ) ? '✅' : '❌'; ?></td>
						<td><?php echo esc_html( $row['label'] ); ?></td>
						<td style="word-break:break-all;font-size:12px"><?php echo esc_html( $row['detail'] ); ?></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>

			<h2>۲) Harden پروداکشن</h2>
			<table class="widefat striped" style="margin-bottom:1.5rem">
				<thead><tr><th>وضعیت</th><th>مورد</th><th>جزئیات</th></tr></thead>
				<tbody>
				<?php foreach ( $report['harden'] as $row ) : ?>
					<tr<?php echo ! empty( $row['blocking'] ) ? ' style="background:#fcf0f1"' : ''; ?>>
						<td><?php echo ! empty( $row['ok'] ) ? '✅' : ( ! empty( $row['blocking'] ) ? '🚫' : '⚠️' ); ?></td>
						<td><?php echo esc_html( $row['label'] ); ?></td>
						<td style="word-break:break-all;font-size:12px"><?php echo esc_html( $row['detail'] ); ?></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		<?php else : ?>
			<p class="notice notice-info" style="padding:10px 12px">هنوز گزارشی نیست — دکمه «اجرای تست دود الآن» را بزنید.</p>
		<?php endif; ?>

		<h2>۳) چک‌لیست دستی روز لانچ</h2>
		<ol style="line-height:1.8">
			<li>در سرور واقعی <code>WP_ENVIRONMENT_TYPE</code> را به <code>production</code> تغییر دهید (نه local).</li>
			<li>SSL را فعال کنید؛ <code>FORCE_SSL_ADMIN</code> و در صورت امکان HSTS در وب‌سرور.</li>
			<li><code>DISALLOW_FILE_EDIT</code> باید true بماند؛ <code>DISALLOW_FILE_MODS</code> روی production true می‌شود.</li>
			<li>پیشوند DB را فقط هنگام مهاجرت (Duplicator / نصب تازه) عوض کنید — روی دیتابیس فعلی Local تغییر خودکار نمی‌دهیم.</li>
			<li>بلاک URL داینامیک مبتنی بر <code>HTTP_HOST</code> را به production کپی نکنید.</li>
			<li>پس از استقرار: دوباره همین صفحه یا CLI smoke را روی دامنه واقعی اجرا کنید.</li>
		</ol>

		<p style="margin-top:1.5rem;padding:12px 14px;background:#f6f7f7;border-right:4px solid #1a5c2e">
			اسنیپت آماده پروداکشن:
			<code>wp-content/themes/wp_storeplus-child/tools/wp-config-production.snippet.php</code>
		</p>
	</div>
	<?php
}
