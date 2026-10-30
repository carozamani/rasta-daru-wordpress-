<?php
/**
 * Full-page loader: logo, bouncing dots, and pharmacy line-art background.
 *
 * Shown on the first paint of every front-end page until the destination
 * document / shop grid is ready. Section skeleton shells are not printed
 * (see storeplus_child_get_skeleton_html()).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Skip wp-admin, feeds, and the customizer preview.
 *
 * @return bool
 */
function storeplus_child_page_loader_skip() {
	if ( is_admin() || wp_doing_ajax() || is_feed() ) {
		return true;
	}
	if ( function_exists( 'is_customize_preview' ) && is_customize_preview() ) {
		return true;
	}
	return false;
}

/**
 * Versioned asset URL helper.
 *
 * @param string $rel Path under the child theme, e.g. '/assets/logo/x.png'.
 * @return string
 */
function storeplus_child_page_loader_asset_url( $rel ) {
	$path = STOREPLUS_CHILD_DIR . $rel;
	$url  = STOREPLUS_CHILD_URI . $rel;
	if ( file_exists( $path ) ) {
		$url = add_query_arg( 'v', (string) filemtime( $path ), $url );
	}
	return $url;
}

/**
 * Logo file shipped with the child theme (transparent wordmark).
 *
 * @return string
 */
function storeplus_child_page_loader_logo_url() {
	return storeplus_child_page_loader_asset_url( '/assets/logo/jabedee-logo.png' );
}

/**
 * Critical CSS + a class on <html> before the body paints.
 */
function storeplus_child_page_loader_head() {
	if ( storeplus_child_page_loader_skip() ) {
		return;
	}
	$bg = esc_url( storeplus_child_page_loader_asset_url( '/assets/img/page-loader-bg.svg' ) );
	?>
	<style id="sp-page-loader-css">
		html.sp-loading,
		html.sp-loading body { overflow: hidden; }
		.sp-page-loader {
			position: fixed;
			inset: 0;
			z-index: 2147483000;
			display: flex;
			align-items: center;
			justify-content: center;
			background:
				radial-gradient(ellipse 55% 48% at 50% 46%, rgba(255,255,255,.94) 0%, rgba(244,247,243,.55) 42%, transparent 72%),
				linear-gradient(165deg, #f7faf6 0%, #eef4ec 48%, #e7efe5 100%);
			transition: opacity .38s ease, visibility .38s ease;
			overflow: hidden;
		}
		.sp-page-loader.is-done {
			opacity: 0;
			visibility: hidden;
			pointer-events: none;
		}
		.sp-page-loader__bg {
			position: absolute;
			inset: -4%;
			pointer-events: none;
			user-select: none;
			background:
				url("<?php echo $bg; ?>") center / cover no-repeat;
			opacity: 0;
			animation: sp-page-loader-art-in .85s ease .05s forwards;
			will-change: opacity, transform;
		}
		.sp-page-loader__art {
			position: absolute;
			pointer-events: none;
			user-select: none;
			opacity: 0;
			animation: sp-page-loader-art-in .75s ease .12s forwards;
			will-change: opacity, transform;
		}
		.sp-page-loader__art img {
			display: block;
			width: 100%;
			height: auto;
		}
		/* Corner clusters — large, overlapping the edges so the frame feels full */
		.sp-page-loader__art--tr {
			top: -8%;
			right: -6%;
			width: min(52vw, 560px);
		}
		.sp-page-loader__art--tl {
			top: -10%;
			left: -8%;
			width: min(46vw, 500px);
			transform: scaleX(-1);
			animation-name: sp-page-loader-art-in-flip;
		}
		.sp-page-loader__art--br {
			bottom: -10%;
			right: -8%;
			width: min(48vw, 520px);
			transform: scaleX(-1);
			animation-name: sp-page-loader-art-in-flip;
		}
		.sp-page-loader__art--bl {
			bottom: -8%;
			left: -6%;
			width: min(52vw, 560px);
		}
		.sp-page-loader__art--ml {
			top: 38%;
			left: -12%;
			width: min(34vw, 360px);
			opacity: 0;
			animation: sp-page-loader-art-in .8s ease .18s forwards;
		}
		.sp-page-loader__art--mr {
			top: 36%;
			right: -12%;
			width: min(34vw, 360px);
			transform: scaleX(-1);
			animation-name: sp-page-loader-art-in-flip;
			animation-delay: .2s;
		}
		.sp-page-loader__veil {
			position: absolute;
			inset: 0;
			pointer-events: none;
			background:
				radial-gradient(ellipse 36% 32% at 50% 46%, rgba(255,255,255,.82) 0%, rgba(255,255,255,.28) 52%, transparent 74%);
			z-index: 1;
		}
		.sp-page-loader__inner {
			position: relative;
			z-index: 2;
			display: flex;
			flex-direction: column;
			align-items: center;
			gap: 28px;
			animation: sp-page-loader-rise .55s ease both;
		}
		.sp-page-loader__logo {
			width: 112px;
			height: auto;
			display: block;
			filter: drop-shadow(0 8px 24px rgba(27, 122, 58, 0.08));
		}
		.sp-page-loader__dots {
			display: flex;
			align-items: center;
			justify-content: center;
			gap: 10px;
			height: 22px;
			direction: ltr;
		}
		.sp-page-loader__dot {
			width: 9px;
			height: 9px;
			border-radius: 50%;
			background: #1a9a45;
			animation: sp-page-loader-bounce 1s ease-in-out infinite;
		}
		.sp-page-loader__dot:nth-child(1) { animation-delay: 0s; }
		.sp-page-loader__dot:nth-child(2) { animation-delay: .16s; }
		.sp-page-loader__dot:nth-child(3) { animation-delay: .32s; }
		@keyframes sp-page-loader-bounce {
			0%, 70%, 100% { transform: translateY(0); opacity: .45; }
			35% { transform: translateY(-11px); opacity: 1; }
		}
		@keyframes sp-page-loader-rise {
			from { opacity: 0; transform: translateY(10px); }
			to { opacity: 1; transform: translateY(0); }
		}
		@keyframes sp-page-loader-art-in {
			from { opacity: 0; transform: scale(.97); }
			to { opacity: 1; transform: scale(1); }
		}
		@keyframes sp-page-loader-art-in-flip {
			from { opacity: 0; transform: scaleX(-1) scale(.97); }
			to { opacity: 1; transform: scaleX(-1) scale(1); }
		}
		.irantheme-skeleton-loader,
		.sp-skel-loader,
		.sp-skel,
		.sp-shop-skel,
		.sp-shop-skel-preloader {
			display: none !important;
		}
		/*
		 * Parent theme hides .irantheme-side-load-skeleton until window.load + Swiper
		 * init. Section bones are disabled here, so that left empty holes after this
		 * overlay lifts. Show real section content from first paint.
		 */
		.irantheme-side-load-skeleton {
			display: block !important;
		}
		@media (max-width: 900px) {
			.sp-page-loader__art--tr { width: min(62vw, 420px); top: -6%; right: -10%; }
			.sp-page-loader__art--tl { width: min(56vw, 380px); top: -8%; left: -12%; }
			.sp-page-loader__art--br { width: min(58vw, 400px); bottom: -8%; right: -12%; }
			.sp-page-loader__art--bl { width: min(62vw, 420px); bottom: -6%; left: -10%; }
			.sp-page-loader__art--ml,
			.sp-page-loader__art--mr { width: min(42vw, 280px); }
		}
		@media (max-width: 480px) {
			.sp-page-loader__logo { width: 88px; }
			.sp-page-loader__inner { gap: 22px; }
			.sp-page-loader__dot { width: 8px; height: 8px; }
			.sp-page-loader__dots { gap: 8px; }
			.sp-page-loader__art--ml,
			.sp-page-loader__art--mr { display: none; }
			.sp-page-loader__art--tr,
			.sp-page-loader__art--bl { width: min(72vw, 300px); }
			.sp-page-loader__art--tl,
			.sp-page-loader__art--br { width: min(68vw, 280px); }
		}
		@media (prefers-reduced-motion: reduce) {
			.sp-page-loader { transition: none; }
			.sp-page-loader__inner,
			.sp-page-loader__bg,
			.sp-page-loader__art,
			.sp-page-loader__dot { animation: none; }
			.sp-page-loader__bg,
			.sp-page-loader__art { opacity: 1; }
			.sp-page-loader__art--tl,
			.sp-page-loader__art--br,
			.sp-page-loader__art--mr { transform: scaleX(-1); }
			.sp-page-loader__dot { opacity: .7; }
		}
	</style>
	<noscript><style>html.sp-loading,html.sp-loading body{overflow:auto!important}.sp-page-loader{display:none!important}</style></noscript>
	<script>
		document.documentElement.classList.add('sp-loading');
		/* Hard stop in <head>, so a stuck footer script can never leave the page white. */
		setTimeout(function () {
			var el = document.getElementById('sp-page-loader');
			document.documentElement.classList.remove('sp-loading');
			if (!el) return;
			el.classList.add('is-done');
			el.setAttribute('aria-hidden', 'true');
			el.style.display = 'none';
		}, 4000);
	</script>
	<?php
}
add_action( 'wp_head', 'storeplus_child_page_loader_head', 1 );

/**
 * Overlay markup. First node in <body>, so it covers the page from first paint.
 */
function storeplus_child_page_loader_markup() {
	if ( storeplus_child_page_loader_skip() ) {
		return;
	}
	$logo      = storeplus_child_page_loader_logo_url();
	$art_left  = storeplus_child_page_loader_asset_url( '/assets/img/loader-art-left.svg' );
	$art_right = storeplus_child_page_loader_asset_url( '/assets/img/loader-art-right.svg' );
	?>
	<div id="sp-page-loader" class="sp-page-loader" role="status" aria-live="polite" aria-label="<?php echo esc_attr( 'در حال بارگذاری' ); ?>">
		<div class="sp-page-loader__bg" aria-hidden="true"></div>
		<div class="sp-page-loader__art sp-page-loader__art--tr" aria-hidden="true">
			<img src="<?php echo esc_url( $art_right ); ?>" alt="" width="440" height="440" decoding="async" />
		</div>
		<div class="sp-page-loader__art sp-page-loader__art--tl" aria-hidden="true">
			<img src="<?php echo esc_url( $art_left ); ?>" alt="" width="440" height="440" decoding="async" />
		</div>
		<div class="sp-page-loader__art sp-page-loader__art--br" aria-hidden="true">
			<img src="<?php echo esc_url( $art_right ); ?>" alt="" width="440" height="440" decoding="async" />
		</div>
		<div class="sp-page-loader__art sp-page-loader__art--bl" aria-hidden="true">
			<img src="<?php echo esc_url( $art_left ); ?>" alt="" width="440" height="440" decoding="async" />
		</div>
		<div class="sp-page-loader__art sp-page-loader__art--ml" aria-hidden="true">
			<img src="<?php echo esc_url( $art_left ); ?>" alt="" width="440" height="440" decoding="async" />
		</div>
		<div class="sp-page-loader__art sp-page-loader__art--mr" aria-hidden="true">
			<img src="<?php echo esc_url( $art_right ); ?>" alt="" width="440" height="440" decoding="async" />
		</div>
		<div class="sp-page-loader__veil" aria-hidden="true"></div>
		<div class="sp-page-loader__inner">
			<img class="sp-page-loader__logo" src="<?php echo esc_url( $logo ); ?>" alt="" width="899" height="370" decoding="async" />
			<div class="sp-page-loader__dots" aria-hidden="true">
				<span class="sp-page-loader__dot"></span>
				<span class="sp-page-loader__dot"></span>
				<span class="sp-page-loader__dot"></span>
			</div>
		</div>
	</div>
	<?php
}
add_action( 'wp_body_open', 'storeplus_child_page_loader_markup', 0 );

/**
 * Hide the overlay once the destination document / shop grid is ready.
 * Do not re-show it on click of the current page (that froze the UI like a screenshot).
 */
function storeplus_child_page_loader_script() {
	if ( storeplus_child_page_loader_skip() ) {
		return;
	}
	?>
	<script id="sp-page-loader-js">
	(function () {
		var root = document.getElementById('sp-page-loader');
		if (!root) return;

		var started = Date.now();
		var MAX = 2500;
		var done = false;
		var timer = 0;

		function revealStuckSections() {
			var nodes = document.querySelectorAll('.irantheme-skeleton-section:not(.irantheme-section-loaded)');
			for (var i = 0; i < nodes.length; i++) {
				var section = nodes[i];
				section.classList.add('irantheme-section-loaded');
				var sk = section.querySelector('.irantheme-skeleton-loader');
				if (sk) sk.style.display = 'none';
				var contents = section.querySelectorAll('.irantheme-side-load-skeleton');
				for (var c = 0; c < contents.length; c++) {
					contents[c].style.display = 'block';
				}
			}
		}

		function shopPending() {
			var grid = document.querySelector('.irantheme-wc-archive-products');
			if (!grid) return false;
			if (grid.children.length > 0) return false;
			var err = document.querySelector('.irantheme-wc-archive-ajax-error');
			if (err && (err.classList.contains('active') || err.classList.contains('show'))) return false;
			return true;
		}

		function pending() {
			if (document.readyState === 'loading') return true;
			if (shopPending()) return true;
			return false;
		}

		function finish() {
			if (done) return;
			done = true;
			revealStuckSections();
			root.classList.add('is-done');
			root.setAttribute('aria-hidden', 'true');
			document.documentElement.classList.remove('sp-loading');
		}

		function tick() {
			if (done) return;
			if ((Date.now() - started) >= MAX) {
				finish();
				return;
			}
			if (!pending()) finish();
		}

		function arm() {
			if (timer) clearInterval(timer);
			timer = setInterval(function () {
				tick();
				if (done) clearInterval(timer);
			}, 50);
		}
		/* Unhide SSR sections under the overlay immediately — do not wait for window.load. */
		revealStuckSections();
		arm();
		window.addEventListener('load', finish);
		document.addEventListener('visibilitychange', tick);

		/* Back/forward cache: show loader again only when restoring a frozen page. */
		window.addEventListener('pageshow', function (e) {
			if (!e.persisted) return;
			done = false;
			started = Date.now();
			root.classList.remove('is-done');
			root.style.display = '';
			arm();
			tick();
		});
	})();
	</script>
	<?php
}
add_action( 'wp_footer', 'storeplus_child_page_loader_script', 5 );
