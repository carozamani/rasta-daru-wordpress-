<?php
/**
 * StorePlus Child - functions.php
 *
 * Clean baseline: the site renders like the parent theme, plus only the files listed
 * below. To enable a file, add its name to the lists (CSS in assets/css/, JS in
 * assets/js/, PHP in inc/).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'STOREPLUS_CHILD_DIR', get_stylesheet_directory() );
define( 'STOREPLUS_CHILD_URI', get_stylesheet_directory_uri() );

/**
 * Files to load.
 *
 * CSS/JS entries are name => scope:
 *   site          — every front request (chrome)
 *   commerce      — Woo views with product UI (home, PDP, shop/cat/tag, cart, checkout, account)
 *   home          — front page only
 *   home_product  — front page + single product (shared homepage rails)
 *   blog          — front page + blog surfaces (post cards)
 */
function storeplus_child_files() {
	return array(
		'inc' => array(
			'fonts',                 // critical font preload + woff2 @font-face (loads with first paint)
			'perf-assets',           // CSS scope bundles, defer JS, soft dequeues, LCP preload
			'critical-launch-fix',  // ngrok/LINKE/.local URL scrub + PB footer hygiene (critical launch)
			'remove-color-filter',   // removes the colour box from the shop filter sidebar
			'shop-search-topbar',    // move product search from sidebar into archive top bar
			'shop-products-stage',   // preloader/grid wrapper — brand chips stay visible while loading
			'shop-archive-price-guard', // strip price_from=NaN (missing price inputs) so archives stay populated
			'mobile-filter-chips',   // mobile: category/price/status/brand chips → filter modals
			'active-filter-chips',   // shop: applied filters as removable chips above product cards
			'home-banners',          // home page banners managed from the admin menu (Home banners)
			'product-brands',        // exposes native WooCommerce brands to the home-page brand slider
			'hide-php-warnings',     // PHP warnings/notices are logged instead of printed into the page
			'remove-wishlist',       // removes the favorites tab / save actions
			'remove-topbar',         // turns the header topbar off
			'remove-delivery-date',  // checkout: removes "زمان تحویل مرسوله" picker (not used on this project)
			'remove-delivery-map',   // checkout: removes "موقعیت دقیق تحویل روی نقشه" (broken / unused)
			'remove-checkout-country', // checkout: hides country selector (Iran only)
			'site-logo',             // site logo: jabedee-logo.png imported to media and used in header/footer
			'offers-timer',          // admin setting (Settings > تایمر شگفت‌انگیز) for the offers box countdown
			'category-menu',         // header dropdown menu built from the product categories (Products > منوی دسته‌بندی هدر)
			'bestsellers-cart',      // mini-products: add-to-cart button at render time (no HTML buffer)
			'seo-ai',                // Schema.org, Open Graph, Twitter, robots.txt, llms.txt (AI/GEO SEO)
			'geo-ai',                // GEO: AI catalog, rich llms.txt, FAQ schema, crawler allowlist
			'floating-support',      // FAB: WhatsApp / phone / Telegram from store-contact
			'launch-checklist',      // Tools → لانچ SEO / AI checklist (Google + GEO)
			'smoke-production',      // Tools → تست دود home/PDP/cart/checkout + harden پروداکشن
			'seo-crawler-copy',      // long SEO copy: hidden in UI, kept in HTML for crawlers
			'product-installments',  // product: enable installments + list providers (DigiPay, Snapp Pay, …)
			'product-pdp-content',   // product PDP: health content + per-product section visibility (admin tab)
			'home-offers-fix',      // front page شگفت‌انگیز: fill empty custom selection so the box is not hidden
			'home-skeleton',         // suppresses section skeleton shells (logo page loader replaces them)
			'shop-skeleton',         // shop AJAX skeleton retired; file kept so the swap stays a no-op
			'page-loader',           // full-page logo + bounce dots + footer art until ready
			'qty-stepper',           // shared quantity stepper (.sp-qty) for PDP / cart / sidebar
			'site-footer',           // one global footer on every page (strip PB footers, dedupe)
			'home-section-order',    // PHP: home section/banner order (JS becomes safety-net only)
			'menu-dedupe',           // PHP: drop duplicate «وبلاگ» from secondary nav
			'require-login-checkout', // guests cannot place orders — must login/register first
			'checkout-order-result', // thank-you: separate success vs failed UI + Gateland fail → failed status
			'store-content-finish',   // one-shot store content: pages, footer, products, menus
			'woocommerce-store-setup', // IRT, Iran shipping, Persian emails, WC pages
			'store-contact',          // single source of truth for phone/address/email
			'seo-enrich',             // Yoast per product/cat, blog posts, SEO menus, media alts
			'post-cards',             // blog cards: strip excerpt/meta + critical CSS (no FOUC)
		),
		/*
		 * Stable CSS sheets (built from assets/css/parts/ via tools/build-css-sheets.php).
		 * Edit parts/ then rebuild — do not resurrect one-off override filenames here.
		 * tokens + site-critical (+ home-critical on front) are also inlined as critical by perf-assets.
		 */
		'css' => array(
			'tokens'                  => 'site',          // 8pt scale + ink palette
			'site-critical'           => 'site',          // above-fold chrome / layout shell
			'site-suppress'           => 'site',          // hide unused parent chrome
			'site-chrome'             => 'site',          // header polish, CTA, mega-menu, cards hover
			'site-footer'             => 'site',          // footer layout styles (PHP-rendered markup)
			/* commerce = shop/PDP/cart/checkout/account only (NOT homepage). */
			'cart-page'               => 'commerce',
			'checkout-order-result'   => 'commerce',
			/* Canonical vertical product card — also forced onto the front page (see enqueue + perf). */
			'product-card'            => 'commerce',
			'slider-corners'          => 'commerce',
			'mini-card-cleanup'       => 'commerce',
			/* home rails: offers / cats / bestsellers (card look comes from product-card.css). */
			'home-rails'              => 'home_product',
			'home-critical'           => 'home',          /* skeleton — also inlined as critical on front */
			'home'                    => 'home',          /* hero / banners / brands / landing polish */
			'blog'                    => 'blog',          /* post cards + latest-three */
		),
		'js'  => array(
			'sticky-header'           => 'site',
			'footer-rearrange'        => 'site',
			'menu-rearrange'          => 'site',
			'cta-system'              => 'site',
			'mega-menu-mask'          => 'site',
			'slider-corners'          => 'commerce',
			'discount-badge-top'      => 'home_product', /* home + (via perf) commerce */
			'offers-frame'            => 'home_product',
			'category-card-fixed'     => 'home_product',
			'slider-peek'             => 'home_product',
			'hide-empty-sections'     => 'home_product',
			'hero-slider-autoplay'    => 'home',
			'brands-after-offers'     => 'home',
			'brands-swipe'            => 'home',
			'brands-marquee'          => 'home',
		),
	);
}

/**
 * Front-end JS that DOM-surgery layout used to own — skipped when the PHP path is on.
 * Files stay on disk as kill-switch safety nets (disable via filters).
 *
 * @return string[] Script base names (no .js).
 */
function storeplus_child_php_layout_skip_js_names() {
	$skip = array();

	/* Menu: skip JS only when the PHP dedupe module is loaded. */
	if ( function_exists( 'storeplus_child_menu_dedupe_blog' ) ) {
		$skip[] = 'menu-rearrange';
	}
	/*
	 * Footer rearrange JS is intentionally NOT skipped: it no-ops when
	 * data-sp-php-footer is set, and finishes social→copyright if PHP could not
	 * (e.g. truncated markup). footer-content.js stays gated in footer-content.php.
	 */
	/* Home section/banner order: skip JS only when PHP order path is on. */
	if ( function_exists( 'storeplus_child_home_section_order_enabled' ) && storeplus_child_home_section_order_enabled() ) {
		$skip[] = 'brands-after-offers';
		$skip[] = 'bestsellers-after-latest';
		$skip[] = 'banner-slots-place';
	}

	return array_values( array_unique( apply_filters( 'storeplus_child_php_layout_skip_js', $skip ) ) );
}

/**
 * Whether the current request should load a given asset scope.
 *
 * @param string $scope Scope key from storeplus_child_files().
 * @return bool
 */
function storeplus_child_asset_scope_matches( $scope ) {
	switch ( $scope ) {
		case 'site':
			return true;

		case 'home':
			return is_front_page();

		case 'home_product':
			/* Front page product rails only — PDP uses commerce + product-page sheet. */
			return is_front_page();

		case 'commerce':
			/* Intentionally excludes the front page (product-card is added separately there). */
			if ( is_front_page() ) {
				return false;
			}
			if ( ! function_exists( 'is_woocommerce' ) ) {
				return false;
			}
			return is_woocommerce() || is_cart() || is_checkout() || is_account_page();

		case 'blog':
			/* Blog cards on home stay; other blog surfaces unchanged. */
			return is_front_page()
				|| is_home()
				|| is_singular( 'post' )
				|| is_category()
				|| is_tag()
				|| ( is_archive() && ! ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) );

		default:
			return true;
	}
}

$storeplus_child_files = storeplus_child_files();

foreach ( $storeplus_child_files['inc'] as $storeplus_child_inc ) {
	$storeplus_child_path = STOREPLUS_CHILD_DIR . '/inc/' . $storeplus_child_inc . '.php';
	if ( file_exists( $storeplus_child_path ) ) {
		require_once $storeplus_child_path;
	}
}

/**
 * Front-end assets, scoped per view after the parent's 'irantheme-main-css' and 'style'.
 * CSS is served as scope bundles by inc/perf-assets.php when enabled; JS stays per-file + defer.
 */
function storeplus_child_enqueue_assets() {
	$files   = storeplus_child_files();
	$bundled = function_exists( 'storeplus_child_perf_enabled' ) && storeplus_child_perf_enabled();

	if ( ! $bundled ) {
		$prev = array( 'irantheme-main-css', 'style' );
		foreach ( $files['css'] as $name => $scope ) {
			$match = storeplus_child_asset_scope_matches( $scope );
			/* Same product-card sheet on the landing page as shop/PDP. */
			if ( ! $match && 'product-card' === $name && is_front_page() ) {
				$match = true;
			}
			if ( ! $match ) {
				continue;
			}
			$rel  = '/assets/css/' . $name . '.css';
			$path = STOREPLUS_CHILD_DIR . $rel;
			if ( ! file_exists( $path ) || 0 === filesize( $path ) ) {
				continue;
			}
			$handle = 'storeplus-child-' . $name;
			wp_enqueue_style( $handle, STOREPLUS_CHILD_URI . $rel, $prev, filemtime( $path ) );
			$prev = array( $handle );
		}
	}

	/* JS: when perf pipeline is on, scope bundles are enqueued by perf-assets.php. */
	if ( function_exists( 'storeplus_child_perf_enabled' ) && storeplus_child_perf_enabled() ) {
		return;
	}

	$skip_js = storeplus_child_php_layout_skip_js_names();
	foreach ( $files['js'] as $name => $scope ) {
		if ( ! storeplus_child_asset_scope_matches( $scope ) ) {
			continue;
		}
		if ( in_array( $name, $skip_js, true ) ) {
			continue;
		}
		$rel  = '/assets/js/' . $name . '.js';
		$path = STOREPLUS_CHILD_DIR . $rel;
		if ( ! file_exists( $path ) || 0 === filesize( $path ) ) {
			continue;
		}
		wp_enqueue_script( 'storeplus-child-' . $name, STOREPLUS_CHILD_URI . $rel, array(), filemtime( $path ), true );
	}
}
add_action( 'wp_enqueue_scripts', 'storeplus_child_enqueue_assets', 20 );


/**
 * Shop archive styles — stable shop.css (reference + redesign + brand/active chips).
 * Perf path loads this via the shop bundle; this is the non-perf fallback only.
 */
function storeplus_child_enqueue_shop_sheet() {
	if ( function_exists( 'storeplus_child_perf_enabled' ) && storeplus_child_perf_enabled() ) {
		return;
	}
	if ( ! function_exists( 'is_shop' ) || ! ( is_shop() || is_product_category() || is_product_tag() ) ) {
		return;
	}
	$css = STOREPLUS_CHILD_DIR . '/assets/css/shop.css';
	if ( file_exists( $css ) ) {
		wp_enqueue_style( 'storeplus-child-shop', STOREPLUS_CHILD_URI . '/assets/css/shop.css', array( 'irantheme-main-css', 'style' ), filemtime( $css ) );
	}
}
add_action( 'wp_enqueue_scripts', 'storeplus_child_enqueue_shop_sheet', 40 );


/**
 * Single product gallery: overlaid thumbnails, hover zoom, click viewer. Product pages only.
 * Files: assets/css/product-gallery.css, assets/js/product-gallery.js
 */
function storeplus_child_enqueue_product_gallery() {
	if ( ! function_exists( 'is_product' ) || ! is_product() ) {
		return;
	}
	$css = STOREPLUS_CHILD_DIR . '/assets/css/product-gallery.css';
	$js  = STOREPLUS_CHILD_DIR . '/assets/js/product-gallery.js';
	if ( file_exists( $css ) && ! ( function_exists( 'storeplus_child_perf_enabled' ) && storeplus_child_perf_enabled() ) ) {
		wp_enqueue_style( 'storeplus-child-product-gallery', STOREPLUS_CHILD_URI . '/assets/css/product-gallery.css', array( 'irantheme-main-css', 'style' ), filemtime( $css ) );
	}
	if ( file_exists( $js ) ) {
		wp_enqueue_script( 'storeplus-child-product-gallery', STOREPLUS_CHILD_URI . '/assets/js/product-gallery.js', array(), filemtime( $js ), true );
	}
}
add_action( 'wp_enqueue_scripts', 'storeplus_child_enqueue_product_gallery', 30 );



/**
 * Single product page redesign: layout, buy box, tabs, trust list. Product pages only.
 * Files: assets/css/product-page.css, assets/js/product-page.js
 * Loads after product-gallery.css (priority 35).
 */
function storeplus_child_enqueue_product_page() {
	if ( ! function_exists( 'is_product' ) || ! is_product() ) {
		return;
	}
	$css = STOREPLUS_CHILD_DIR . '/assets/css/product-page.css';
	$js  = STOREPLUS_CHILD_DIR . '/assets/js/product-page.js';

	if ( file_exists( $css ) && ! ( function_exists( 'storeplus_child_perf_enabled' ) && storeplus_child_perf_enabled() ) ) {
		wp_enqueue_style(
			'storeplus-child-product-page',
			STOREPLUS_CHILD_URI . '/assets/css/product-page.css',
			array( 'irantheme-main-css', 'style', 'storeplus-child-product-gallery' ),
			filemtime( $css )
		);
	}

	if ( file_exists( $js ) ) {
		wp_enqueue_script(
			'storeplus-child-product-page',
			STOREPLUS_CHILD_URI . '/assets/js/product-page.js',
			array( 'storeplus-child-qty-stepper' ),
			filemtime( $js ),
			true
		);

		// Reassurance list under the buy button. Edit the texts here; icons: shield, truck, card, headset.
		$trust = apply_filters(
			'storeplus_child_product_trust_items',
			array(
				array( 'icon' => 'shield',  'text' => 'ضمانت اصالت و سلامت کالا' ),
				array( 'icon' => 'truck',   'text' => 'ارسال سریع به سراسر کشور' ),
				array( 'icon' => 'card',    'text' => 'پرداخت امن و آنلاین' ),
				array( 'icon' => 'headset', 'text' => 'پشتیبانی و مشاوره قبل از خرید' ),
			)
		);
		$product_id = (int) get_queried_object_id();
		$cart_state = function_exists( 'storeplus_child_pdp_cart_state' )
			? storeplus_child_pdp_cart_state( $product_id )
			: array( 'qty' => 0, 'key' => '' );
		$ajax_url = admin_url( 'admin-ajax.php' );
		if ( class_exists( 'WC_AJAX' ) ) {
			$wc_url = WC_AJAX::get_endpoint( 'pp_set_cart_qty' );
			/* Guard broken Local/CLI URLs like "https:/?wc-ajax=…" */
			if ( is_string( $wc_url ) && preg_match( '#https?://[^/]+#', $wc_url ) ) {
				$ajax_url = $wc_url;
			} else {
				$ajax_url = home_url( '/?wc-ajax=pp_set_cart_qty' );
			}
		}
		$pp_data = array(
			'trust'        => $trust,
			'title'        => 'خدمات رستا دارو به مشتریان:',
			'productId'    => $product_id,
			'cartQty'      => (int) $cart_state['qty'],
			'cartKey'      => (string) $cart_state['key'],
			'ajaxUrl'      => $ajax_url,
			'ajaxFallback' => admin_url( 'admin-ajax.php' ),
			'cartNonce'    => wp_create_nonce( 'sp_pp_cart' ),
		);
		if ( function_exists( 'storeplus_child_pdp_visibility' ) ) {
			$pp_data['visibility'] = storeplus_child_pdp_visibility( $product_id );
		}
		$pp_data = apply_filters( 'storeplus_child_product_page_data', $pp_data );
		wp_add_inline_script( 'storeplus-child-product-page', 'window.spProductPage=' . wp_json_encode( $pp_data, JSON_UNESCAPED_UNICODE ) . ';', 'before' );
	}
}
add_action( 'wp_enqueue_scripts', 'storeplus_child_enqueue_product_page', 35 );


/**
 * Resolve a store legal/help page URL from finish option IDs, slug, or WC fallback.
 *
 * @param string $key          Key in sp_store_finish_page_ids.
 * @param string $fallback_slug Page slug.
 * @param string $wc_page       Optional WooCommerce page key (e.g. myaccount).
 * @return string
 */
function storeplus_child_store_page_url( $key, $fallback_slug, $wc_page = '' ) {
	$ids = get_option( 'sp_store_finish_page_ids', array() );
	if ( ! empty( $ids[ $key ] ) ) {
		$url = get_permalink( (int) $ids[ $key ] );
		if ( $url ) {
			return $url;
		}
	}
	$page = get_page_by_path( $fallback_slug );
	if ( $page ) {
		return get_permalink( $page );
	}
	if ( $wc_page && function_exists( 'wc_get_page_permalink' ) ) {
		$url = wc_get_page_permalink( $wc_page );
		if ( $url ) {
			return $url;
		}
	}
	return home_url( '/' . ltrim( $fallback_slug, '/' ) . '/' );
}

/**
 * Footer: extra link column(s) for the JS safety net only.
 * Primary path: inc/footer-php-render.php reads storeplus_child_footer_extra_cols directly.
 * When PHP footer is on we still localize spFooterCols if footer-rearrange.js is loaded,
 * so a partial PHP pass can be finished client-side without a blank guide column.
 */
function storeplus_child_footer_extra_cols() {
	$handle = '';
	if ( wp_script_is( 'storeplus-child-js-site', 'enqueued' ) ) {
		$handle = 'storeplus-child-js-site';
	} elseif ( wp_script_is( 'storeplus-child-footer-rearrange', 'enqueued' ) ) {
		$handle = 'storeplus-child-footer-rearrange';
	} elseif ( wp_script_is( 'storeplus-child-footer-content', 'enqueued' ) ) {
		$handle = 'storeplus-child-footer-content';
	}
	if ( ! $handle ) {
		return;
	}
	$cols = apply_filters( 'storeplus_child_footer_extra_cols', array() );
	if ( ! is_array( $cols ) ) {
		$cols = array();
	}
	wp_add_inline_script( $handle, 'window.spFooterCols=' . wp_json_encode( $cols, JSON_UNESCAPED_UNICODE ) . ';', 'before' );
}
add_action( 'wp_enqueue_scripts', 'storeplus_child_footer_extra_cols', 25 );


/**
 * Home page bestsellers placement — JS safety net only (primary: inc/home-section-order.php).
 * File: assets/js/bestsellers-after-latest.js
 */
function storeplus_child_enqueue_bestsellers_after_latest() {
	if ( function_exists( 'storeplus_child_home_section_order_enabled' ) && storeplus_child_home_section_order_enabled() ) {
		return; /* PHP owns order. */
	}
	if ( function_exists( 'storeplus_child_perf_enabled' ) && storeplus_child_perf_enabled() ) {
		return; /* Absorbed into home JS bundle. */
	}
	if ( ! is_front_page() ) {
		return;
	}
	$js = STOREPLUS_CHILD_DIR . '/assets/js/bestsellers-after-latest.js';
	if ( file_exists( $js ) ) {
		wp_enqueue_script( 'storeplus-child-bestsellers-after-latest', STOREPLUS_CHILD_URI . '/assets/js/bestsellers-after-latest.js', array(), filemtime( $js ), true );
	}
}
add_action( 'wp_enqueue_scripts', 'storeplus_child_enqueue_bestsellers_after_latest', 20 );

/**
 * Banner slot placement — JS safety net only (primary: inc/home-section-order.php).
 * File: assets/js/banner-slots-place.js
 */
function storeplus_child_enqueue_banner_slots_place() {
	if ( function_exists( 'storeplus_child_home_section_order_enabled' ) && storeplus_child_home_section_order_enabled() ) {
		return; /* PHP owns order. */
	}
	if ( function_exists( 'storeplus_child_perf_enabled' ) && storeplus_child_perf_enabled() ) {
		return; /* Absorbed into home JS bundle. */
	}
	if ( ! is_front_page() ) {
		return;
	}
	$js = STOREPLUS_CHILD_DIR . '/assets/js/banner-slots-place.js';
	if ( ! file_exists( $js ) ) {
		return;
	}
	wp_enqueue_script(
		'storeplus-child-banner-slots-place',
		STOREPLUS_CHILD_URI . '/assets/js/banner-slots-place.js',
		array( 'storeplus-child-bestsellers-after-latest', 'storeplus-child-brands-after-offers' ),
		filemtime( $js ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'storeplus_child_enqueue_banner_slots_place', 25 );


/**
 * Hero slider module: slides are managed from the admin menu "اسلایدر صفحه اصلی".
 * File: inc/hero-slides.php (swaps the page builder's slider slides on the front page).
 */
$storeplus_child_hero_module = STOREPLUS_CHILD_DIR . '/inc/hero-slides.php';
if ( file_exists( $storeplus_child_hero_module ) ) {
	require_once $storeplus_child_hero_module;
}


/**
 * Top banner module: thin banner above the header, managed from the admin menu "بنر بالای هدر".
 * File: inc/top-banner.php (image / mobile image / green-gradient text banner, optional close button).
 */
$storeplus_child_top_banner = STOREPLUS_CHILD_DIR . '/inc/top-banner.php';
if ( file_exists( $storeplus_child_top_banner ) ) {
	require_once $storeplus_child_top_banner;
}


/**
 * Footer content module: quick links, customer guide, contact info and social icons are edited
 * from the admin menu "محتوای فوتر".
 * Files: inc/footer-content.php (+ assets/js/footer-content.js as fallback only)
 */
$storeplus_child_footer_content = STOREPLUS_CHILD_DIR . '/inc/footer-content.php';
if ( file_exists( $storeplus_child_footer_content ) ) {
	require_once $storeplus_child_footer_content;
}

/**
 * Footer layout + content in PHP (primary). Must load after footer-content.php (spfc_front_config)
 * and site-footer.php (output buffer). JS files remain as safety nets that no-op when PHP succeeds.
 * File: inc/footer-php-render.php
 */
$storeplus_child_footer_php = STOREPLUS_CHILD_DIR . '/inc/footer-php-render.php';
if ( file_exists( $storeplus_child_footer_php ) ) {
	require_once $storeplus_child_footer_php;
}


/**
 * WP admin: notice boxes (e.g. Gateland promo) are not full width, and the top spacing of the
 * main content equals the left/right spacing (20px).
 */
function storeplus_child_admin_notice_layout() {
	?>
	<style id="storeplus-child-admin-layout">
		#wpbody-content > .notice,
		#wpbody-content > .updated,
		#wpbody-content > .error,
		#wpbody-content .wrap > .notice,
		#wpbody-content .wrap > .updated,
		#wpbody-content .wrap > .error {
			box-sizing: border-box !important;
			width: auto !important;
			max-width: 1100px !important;
			margin: 20px 20px 0 20px !important;
		}
		#wpbody-content {
			padding-top: 20px !important;
		}
		#wpbody-content > .wrap,
		#wpbody-content .wrap {
			margin-top: 20px !important;
			margin-right: 20px !important;
			margin-left: 20px !important;
		}
		#wpbody-content > .wrap .notice,
		#wpbody-content > .wrap .updated,
		#wpbody-content > .wrap .error {
			margin-right: 0 !important;
			margin-left: 0 !important;
		}
	</style>
	<?php
}
add_action( 'admin_head', 'storeplus_child_admin_notice_layout', 999 );


/**
 * WP admin: Persian WooCommerce promo notice (.pw_notice)
 * - text on the right, button on the left (RTL), room kept for the dismiss X
 * - notice is moved under the page title
 */
function storeplus_child_admin_pw_notice() {
	?>
	<style id="storeplus-child-pw-notice">
		#wpbody-content .pw_notice.notice {
			padding: 4px 14px 4px 44px !important;
			box-sizing: border-box !important;
			max-width: 1100px !important;
		}
		#wpbody-content .pw_notice.notice > p {
			display: block !important;
			margin: 8px 0 !important;
			overflow: hidden;
			text-align: right;
			line-height: 2.2;
		}
		#wpbody-content .pw_notice.notice > p > a {
			float: left !important;
			margin: 0 !important;
		}
		#wpbody-content .pw_notice.notice > p > a .button {
			margin: 0 !important;
		}
		#wpbody-content .pw_notice.notice.pw-moved {
			margin: 15px 0 15px 0 !important;
		}
	</style>
	<?php
}
add_action( 'admin_head', 'storeplus_child_admin_pw_notice', 999 );


/**
 * Flat design: kill theme/plugin shadows without the expensive `html *` universal rule.
 * Targets tokens + common shadow carriers (class names containing "shadow").
 */
function storeplus_child_no_shadows_css() {
	?>
	<style id="storeplus-child-no-shadows">
		:root {
			--sd-shadow: none;
			--bs-box-shadow: none;
			--bs-box-shadow-sm: none;
			--bs-box-shadow-lg: none;
		}
		[class*="shadow"],
		[class*="shadow"]::before,
		[class*="shadow"]::after,
		.irantheme-woocommerce-products-item,
		.irantheme-woocommerce-mini-products-item,
		.irantheme-sidebar-box-holder,
		.irantheme-wc-archive-filter-box,
		.irantheme-header-main,
		.irantheme-footer-nav-section,
		.sp-product-card,
		.pp-buy,
		.pp-mbar {
			box-shadow: none !important;
			text-shadow: none !important;
		}
	</style>
	<?php
}
add_action( 'wp_head', 'storeplus_child_no_shadows_css', 99999 );
add_action( 'admin_head', 'storeplus_child_no_shadows_css', 99999 );
add_action( 'login_head', 'storeplus_child_no_shadows_css', 99999 );


/**
 * Admin notice fix: keep the dismiss X clear of the button (button gets a left margin, X centered).
 */
function storeplus_child_admin_pw_notice_fix() {
	?>
	<style id="storeplus-child-pw-notice-fix">
		html body #wpbody-content .pw_notice.notice {
			position: relative !important;
		}
		html body #wpbody-content .pw_notice.notice > p > a {
			margin: 0 0 0 40px !important;
		}
		html body #wpbody-content .pw_notice.notice > .notice-dismiss {
			top: 50% !important;
			left: 8px !important;
			right: auto !important;
			transform: translateY(-50%) !important;
			margin: 0 !important;
			padding: 0 !important;
			width: 28px !important;
			height: 28px !important;
		}
	</style>
	<?php
}
add_action( 'admin_footer', 'storeplus_child_admin_pw_notice_fix', 1000 );


/**
 * Admin notice: no visible jump. The notice stays hidden until it is moved under the page title;
 * the move runs immediately at the end of the page (before the old delayed script, which then skips it).
 */
function storeplus_child_admin_pw_notice_hide() {
	?>
	<style id="storeplus-child-pw-notice-hide">
		html body #wpbody-content .pw_notice.notice:not(.pw-moved) { display: none !important; }
	</style>
	<?php
}
add_action( 'admin_head', 'storeplus_child_admin_pw_notice_hide', 1000 );

function storeplus_child_admin_pw_notice_move_now() {
	?>
	<script>
	(function () {
		var n = document.querySelector('#wpbody-content .pw_notice');
		if (!n || n.classList.contains('pw-moved')) { return; }
		var wrap = document.querySelector('#wpbody-content .wrap');
		var anchor = wrap && (wrap.querySelector('.wp-header-end') || wrap.querySelector('h1, h2'));
		if (anchor && anchor.parentNode) {
			anchor.parentNode.insertBefore(n, anchor.nextSibling);
		}
		n.classList.add('pw-moved');
	})();
	</script>
	<?php
}
add_action( 'admin_footer', 'storeplus_child_admin_pw_notice_move_now', 998 );


/**
 * Admin list tables (slides, posts, products ...): search box on the left, above the table box.
 */
function storeplus_child_admin_search_left() {
	?>
	<style id="storeplus-child-search-left">
		html body #wpbody-content .wrap .search-box {
			float: left !important;
			clear: none !important;
			position: static !important;
			width: auto !important;
			margin: 0 0 12px 0 !important;
			display: flex !important;
			align-items: center;
			gap: 8px;
			text-align: left;
		}
		html body #wpbody-content .wrap .search-box input[type="search"],
		html body #wpbody-content .wrap .search-box input[name="s"] {
			float: none !important;
			width: 220px !important;
			max-width: 100% !important;
			margin: 0 !important;
		}
		html body #wpbody-content .wrap .search-box input[type="submit"],
		html body #wpbody-content .wrap .search-box .button {
			float: none !important;
			margin: 0 !important;
		}
		html body #wpbody-content .wrap .tablenav.top {
			clear: both !important;
		}
	</style>
	<?php
}
add_action( 'admin_head', 'storeplus_child_admin_search_left', 1000 );


/**
 * Home page blog section ("مطالب وبلاگ"): JS only — CSS lives in stable blog.css.
 * File: assets/js/blog-latest-three.js
 */
function storeplus_child_enqueue_blog_latest_three() {
	if ( ! storeplus_child_asset_scope_matches( 'blog' ) ) {
		return;
	}
	$js = STOREPLUS_CHILD_DIR . '/assets/js/blog-latest-three.js';
	if ( ! file_exists( $js ) ) {
		return;
	}
	wp_enqueue_script( 'storeplus-child-blog-latest-three', STOREPLUS_CHILD_URI . '/assets/js/blog-latest-three.js', array(), filemtime( $js ), true );

	$posts_page = (int) get_option( 'page_for_posts' );
	$blog_url   = $posts_page ? get_permalink( $posts_page ) : '';
	wp_add_inline_script( 'storeplus-child-blog-latest-three', 'window.spBlogAll=' . wp_json_encode( $blog_url ? $blog_url : '' ) . ';', 'before' );
}
add_action( 'wp_enqueue_scripts', 'storeplus_child_enqueue_blog_latest_three', 30 );


/**
 * Product prices: same look in vertical and horizontal (mini) cards - right-aligned, "تومان" after the number.
 * File: assets/css/price-unified.css (loads after every other card file)
 */
function storeplus_child_enqueue_price_unified() {
	if ( function_exists( 'storeplus_child_perf_enabled' ) && storeplus_child_perf_enabled() ) {
		return;
	}
	if ( ! storeplus_child_asset_scope_matches( 'commerce' ) ) {
		return;
	}
	$css = STOREPLUS_CHILD_DIR . '/assets/css/price-unified.css';
	if ( ! file_exists( $css ) ) {
		return;
	}
	$deps = array( 'irantheme-main-css', 'style' );
	foreach ( array( 'storeplus-child-mini-card-cleanup', 'storeplus-child-bestsellers-panel', 'storeplus-child-product-card' ) as $dep ) {
		if ( wp_style_is( $dep, 'enqueued' ) || wp_style_is( $dep, 'registered' ) ) {
			$deps[] = $dep;
		}
	}
	wp_enqueue_style( 'storeplus-child-price-unified', STOREPLUS_CHILD_URI . '/assets/css/price-unified.css', $deps, filemtime( $css ) );
}
add_action( 'wp_enqueue_scripts', 'storeplus_child_enqueue_price_unified', 40 );



/**
 * Home banners quick manager: upload / reorder the banners of both home banner boxes from one admin page
 * (بنرهای صفحه اصلی > مدیریت سریع).
 * File: inc/home-banners-manager.php (works together with inc/home-banners.php).
 */
$storeplus_child_hb_manager = STOREPLUS_CHILD_DIR . '/inc/home-banners-manager.php';
if ( file_exists( $storeplus_child_hb_manager ) ) {
	require_once $storeplus_child_hb_manager;
}


/**
 * Brand categories: each product brand can be assigned to one or more product categories
 * (Products > Brands). File: inc/brand-categories.php
 */
$storeplus_child_brand_cats = STOREPLUS_CHILD_DIR . '/inc/brand-categories.php';
if ( file_exists( $storeplus_child_brand_cats ) ) {
	require_once $storeplus_child_brand_cats;
}


/**
 * Shop brand filter: chips above product cards that follow the selected categories, and brand archive
 * pages that list ALL products of the brand (sidebar filters narrow them down).
 * Files: inc/brand-filter.php, assets/js/brand-filter.js, assets/css/brand-chips.css
 */
$storeplus_child_brand_filter = STOREPLUS_CHILD_DIR . '/inc/brand-filter.php';
if ( file_exists( $storeplus_child_brand_filter ) ) {
	require_once $storeplus_child_brand_filter;
}


/**
 * Product card component: ONE card (vertical layout) for every product card of the site.
 * The parent theme's Irantheme_Section_Woocommerce::get_product_item() hands each product to
 * storeplus_child_product_card() (see the hook at the top of that method).
 * File: inc/product-card.php - look: assets/css/product-card.css
 */
$storeplus_child_product_card = STOREPLUS_CHILD_DIR . '/inc/product-card.php';
if ( file_exists( $storeplus_child_product_card ) ) {
	require_once $storeplus_child_product_card;
}

/**
 * WooCommerce Brands: the parent theme switches the feature off on every request; this keeps it on so the home brands
 * carousel, the shop brand filter and the brand pages (all built on product_brand) have their data.
 * File: inc/enable-brands.php (manage brands in Products > Brands)
 */
$storeplus_child_enable_brands = STOREPLUS_CHILD_DIR . '/inc/enable-brands.php';
if ( file_exists( $storeplus_child_enable_brands ) ) {
	require_once $storeplus_child_enable_brands;
}
