<?php
/**
 * Rasta Daru PDP content + per-product visibility controls.
 *
 * Admin (product data tab «صفحه محصول»):
 *  - Toggle which PDP blocks/tabs appear for this product
 *  - Fill pharmacy/supplement content (form, dose, ingredients, usage, warnings…)
 *
 * Front: helpers + health markup + tab filters + body classes for JS/CSS.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Meta: JSON map of section_key => '1'|'0'. Empty/missing = use defaults (all on). */
define( 'SP_PDP_VIS_KEY', '_sp_pdp_visibility' );

/** Meta keys for health content. */
define( 'SP_PDP_FORM_KEY', '_sp_pdp_form' );
define( 'SP_PDP_PACK_KEY', '_sp_pdp_pack' );
define( 'SP_PDP_DOSE_KEY', '_sp_pdp_dose' );
define( 'SP_PDP_AGE_KEY', '_sp_pdp_age' );
define( 'SP_PDP_ORIGIN_KEY', '_sp_pdp_origin' );
define( 'SP_PDP_LICENSE_KEY', '_sp_pdp_license' );
define( 'SP_PDP_INGREDIENTS_KEY', '_sp_pdp_ingredients' );
define( 'SP_PDP_USAGE_KEY', '_sp_pdp_usage' );
define( 'SP_PDP_WARNINGS_KEY', '_sp_pdp_warnings' );
define( 'SP_PDP_SUITABLE_KEY', '_sp_pdp_suitable' );
define( 'SP_PDP_NOT_SUITABLE_KEY', '_sp_pdp_not_suitable' );
define( 'SP_PDP_NAME_EN_KEY', '_sp_pdp_name_en' );
define( 'SP_PDP_MANUFACTURER_KEY', '_sp_pdp_manufacturer' );
define( 'SP_PDP_EXPIRY_KEY', '_sp_pdp_expiry' );
define( 'SP_PDP_STORAGE_KEY', '_sp_pdp_storage' );
define( 'SP_PDP_FAQ_KEY', '_sp_pdp_faq' );
define( 'SP_PDP_GENDER_KEY', '_sp_pdp_gender' );
define( 'SP_PDP_ABOUT_KEY', '_sp_pdp_about' );

/**
 * Section registry: key => [ label, group, default ].
 * default true = shown unless admin turns it off.
 *
 * @return array<string, array{label:string,group:string,default:bool}>
 */
function storeplus_child_pdp_section_defs() {
	return array(
		// Above the fold — identity
		'brand'           => array( 'label' => 'برند', 'group' => 'بالای صفحه', 'default' => true ),
		'category'        => array( 'label' => 'دسته‌بندی', 'group' => 'بالای صفحه', 'default' => true ),
		'badges'          => array( 'label' => 'بج‌های محصول (اصل، اقساط، …)', 'group' => 'بالای صفحه', 'default' => true ),
		'name_en'         => array( 'label' => 'نام انگلیسی زیر عنوان', 'group' => 'بالای صفحه', 'default' => true ),
		'health_chips'    => array( 'label' => 'جدول مشخصات سریع (شکل، تعداد، دوز، انقضا…)', 'group' => 'بالای صفحه', 'default' => true ),
		'short_desc'      => array( 'label' => 'خلاصه / بولت‌های فایده', 'group' => 'بالای صفحه', 'default' => true ),
		'tags'            => array( 'label' => 'برچسب‌ها', 'group' => 'بالای صفحه', 'default' => true ),
		'attr_summary'    => array( 'label' => 'ویژگی‌های کلیدی (خلاصه)', 'group' => 'بالای صفحه', 'default' => true ),
		'tip'             => array( 'label' => 'نکته محصول', 'group' => 'بالای صفحه', 'default' => true ),
		'suitable'        => array( 'label' => 'مناسب برای / مناسب نیست برای', 'group' => 'بالای صفحه', 'default' => true ),

		// Buy card
		'rating'          => array( 'label' => 'بج امتیاز / ستاره (بالای باکس خرید)', 'group' => 'باکس خرید', 'default' => true ),
		'trust'           => array( 'label' => 'نوار اعتماد (اصالت، ارسال، پرداخت…)', 'group' => 'باکس خرید', 'default' => true ),
		'buy_signals'     => array( 'label' => 'نشان‌های باکس خرید (انقضا، مجوز، اصالت)', 'group' => 'باکس خرید', 'default' => true ),
		'seller_shipping' => array( 'label' => 'فروشنده و زمان ارسال', 'group' => 'باکس خرید', 'default' => true ),
		'sku'             => array( 'label' => 'شناسه کالا', 'group' => 'باکس خرید', 'default' => true ),
		'stock'           => array( 'label' => 'پیام موجودی انبار', 'group' => 'باکس خرید', 'default' => true ),
		'installments'    => array( 'label' => 'باکس خرید اقساطی', 'group' => 'باکس خرید', 'default' => true ),

		// Document card
		'about_product'   => array( 'label' => 'باکس درباره محصول / توضیحات', 'group' => 'توضیحات', 'default' => true ),

		// Health fields (admin-only storage; not rendered in the doc card)
		'ingredients'     => array( 'label' => 'فیلد ترکیبات (ادمین)', 'group' => 'فیلدهای سلامت', 'default' => true ),
		'usage'           => array( 'label' => 'فیلد نحوه مصرف (ادمین)', 'group' => 'فیلدهای سلامت', 'default' => true ),
		'warnings'        => array( 'label' => 'فیلد هشدارها (ادمین)', 'group' => 'فیلدهای سلامت', 'default' => true ),
		'storage'         => array( 'label' => 'فیلد نگهداری (ادمین)', 'group' => 'فیلدهای سلامت', 'default' => true ),
		'faq'             => array( 'label' => 'فیلد سوالات متداول (ادمین)', 'group' => 'فیلدهای سلامت', 'default' => true ),

		// Discovery
		'tab_description' => array( 'label' => 'تب معرفی ووکامرس (مخفی)', 'group' => 'تب‌ها و کشف', 'default' => false ),
		'tab_attributes'  => array( 'label' => 'تب مشخصات فنی (مخفی)', 'group' => 'تب‌ها و کشف', 'default' => false ),
		'tab_reviews'     => array( 'label' => 'تب دیدگاه‌ها (مخفی)', 'group' => 'تب‌ها و کشف', 'default' => false ),
		'tab_qa'          => array( 'label' => 'تب پرسش و پاسخ (مخفی)', 'group' => 'تب‌ها و کشف', 'default' => false ),
		'upsells'         => array( 'label' => 'محصولات پیشنهادی (upsell)', 'group' => 'تب‌ها و کشف', 'default' => true ),
		'related'         => array( 'label' => 'محصولات مرتبط', 'group' => 'تب‌ها و کشف', 'default' => true ),
	);
}

/**
 * @param int $product_id Product ID.
 * @return array<string, bool> section => visible
 */
function storeplus_child_pdp_visibility( $product_id ) {
	$product_id = (int) $product_id;
	$defs       = storeplus_child_pdp_section_defs();
	$out        = array();
	foreach ( $defs as $key => $def ) {
		$out[ $key ] = ! empty( $def['default'] );
	}

	$raw = get_post_meta( $product_id, SP_PDP_VIS_KEY, true );
	if ( ! is_string( $raw ) || '' === $raw ) {
		return $out;
	}

	$saved = json_decode( $raw, true );
	if ( ! is_array( $saved ) ) {
		return $out;
	}

	foreach ( $defs as $key => $def ) {
		if ( array_key_exists( $key, $saved ) ) {
			$out[ $key ] = ( '1' === (string) $saved[ $key ] || 1 === $saved[ $key ] || true === $saved[ $key ] );
		}
	}

	return $out;
}

/**
 * @param int    $product_id Product ID.
 * @param string $section    Section key.
 * @return bool
 */
function storeplus_child_pdp_show( $product_id, $section ) {
	$map = storeplus_child_pdp_visibility( $product_id );
	if ( ! array_key_exists( $section, $map ) ) {
		return true;
	}
	return (bool) $map[ $section ];
}

/**
 * Sanitize multiline list (one item per line).
 *
 * @param string $raw Raw textarea.
 * @return string[]
 */
function storeplus_child_pdp_lines( $raw ) {
	$raw = (string) $raw;
	if ( '' === trim( $raw ) ) {
		return array();
	}
	$lines = preg_split( '/\r\n|\r|\n/', $raw );
	$out   = array();
	foreach ( (array) $lines as $line ) {
		$line = trim( wp_strip_all_tags( $line ) );
		if ( '' !== $line ) {
			$out[] = $line;
		}
	}
	return array_values( array_unique( $out ) );
}

/**
 * Parse ingredients lines: "نام | مقدار" or plain name.
 *
 * @param string $raw Textarea.
 * @return array<int, array{name:string,amount:string}>
 */
function storeplus_child_pdp_parse_ingredients( $raw ) {
	$rows = array();
	foreach ( storeplus_child_pdp_lines( $raw ) as $line ) {
		$parts = array_map( 'trim', explode( '|', $line, 2 ) );
		$rows[] = array(
			'name'   => $parts[0],
			'amount' => isset( $parts[1] ) ? $parts[1] : '',
		);
	}
	return $rows;
}

/**
 * Collected health fields for a product.
 *
 * @param int $product_id Product ID.
 * @return array<string, mixed>
 */
function storeplus_child_pdp_health_data( $product_id ) {
	$product_id = (int) $product_id;
	return array(
		'form'          => (string) get_post_meta( $product_id, SP_PDP_FORM_KEY, true ),
		'pack'          => (string) get_post_meta( $product_id, SP_PDP_PACK_KEY, true ),
		'dose'          => (string) get_post_meta( $product_id, SP_PDP_DOSE_KEY, true ),
		'age'           => (string) get_post_meta( $product_id, SP_PDP_AGE_KEY, true ),
		'origin'        => (string) get_post_meta( $product_id, SP_PDP_ORIGIN_KEY, true ),
		'license'       => (string) get_post_meta( $product_id, SP_PDP_LICENSE_KEY, true ),
		'ingredients'   => (string) get_post_meta( $product_id, SP_PDP_INGREDIENTS_KEY, true ),
		'usage'         => (string) get_post_meta( $product_id, SP_PDP_USAGE_KEY, true ),
		'warnings'      => (string) get_post_meta( $product_id, SP_PDP_WARNINGS_KEY, true ),
		'suitable'      => (string) get_post_meta( $product_id, SP_PDP_SUITABLE_KEY, true ),
		'not_suitable'  => (string) get_post_meta( $product_id, SP_PDP_NOT_SUITABLE_KEY, true ),
		'name_en'       => (string) get_post_meta( $product_id, SP_PDP_NAME_EN_KEY, true ),
		'manufacturer'  => (string) get_post_meta( $product_id, SP_PDP_MANUFACTURER_KEY, true ),
		'expiry'        => (string) get_post_meta( $product_id, SP_PDP_EXPIRY_KEY, true ),
		'storage'       => (string) get_post_meta( $product_id, SP_PDP_STORAGE_KEY, true ),
		'faq'           => (string) get_post_meta( $product_id, SP_PDP_FAQ_KEY, true ),
		'gender'        => (string) get_post_meta( $product_id, SP_PDP_GENDER_KEY, true ),
		'about'         => (string) get_post_meta( $product_id, SP_PDP_ABOUT_KEY, true ),
	);
}

/**
 * Parse FAQ lines: "سوال؟ :: پاسخ" (one pair per line).
 *
 * @param string $raw Textarea.
 * @return array<int, array{q:string,a:string}>
 */
function storeplus_child_pdp_parse_faq( $raw ) {
	$items = array();
	foreach ( storeplus_child_pdp_lines( $raw ) as $line ) {
		$parts = preg_split( '/\s*::\s*/', $line, 2 );
		if ( count( $parts ) < 2 ) {
			$parts = preg_split( '/\s*\|\s*/', $line, 2 );
		}
		if ( count( $parts ) < 2 ) {
			continue;
		}
		$q = trim( $parts[0] );
		$a = trim( $parts[1] );
		if ( '' !== $q && '' !== $a ) {
			$items[] = array( 'q' => $q, 'a' => $a );
		}
	}
	return $items;
}

/* --------------------------------------------------------------------------
 * Admin: WooCommerce product data tab
 * -------------------------------------------------------------------------- */


require_once __DIR__ . '/product-pdp-admin.php';


/* --------------------------------------------------------------------------
 * Front: body classes + trust filter + tabs + markup
 * -------------------------------------------------------------------------- */

/**
 * Body classes so CSS/JS can hide blocks without rewriting every theme partial.
 *
 * @param string[] $classes Classes.
 * @return string[]
 */
function storeplus_child_pdp_body_class( $classes ) {
	if ( ! function_exists( 'is_product' ) || ! is_product() ) {
		return $classes;
	}
	$product_id = get_queried_object_id();
	if ( ! $product_id ) {
		return $classes;
	}
	$classes[] = 'sp-pdp-controlled';
	$classes[] = 'pp-pdp';
	foreach ( storeplus_child_pdp_visibility( $product_id ) as $key => $show ) {
		if ( ! $show ) {
			$classes[] = 'sp-pdp-hide-' . sanitize_html_class( $key );
		}
	}
	return $classes;
}
add_filter( 'body_class', 'storeplus_child_pdp_body_class' );

/**
 * Mark <html> early + critical hero grid so first paint matches final layout
 * (JS used to wrap DOM after load → FOUC of Bootstrap columns).
 */
function storeplus_child_pdp_critical_head() {
	if ( ! function_exists( 'is_product' ) || ! is_product() ) {
		return;
	}
	echo "<script>document.documentElement.classList.add('pp-pdp');</script>\n";
	echo '<style id="pp-pdp-critical">'
		. 'body.single-product,#irantheme-main{background:#f6f7f8!important}'
		. 'body.single-product #irantheme-main .irantheme-single-product{box-sizing:border-box;width:100%;max-width:1560px!important;margin-inline:auto;padding-inline:16px}'
		. '#irantheme-main .irantheme-physical-product .pp-trust-bar{max-width:1560px;width:100%;margin-inline:auto;box-sizing:border-box}'
		. '#irantheme-main .irantheme-physical-product .pp-doc,'
		. '#irantheme-main .irantheme-physical-product .pp-discovery,'
		. '#irantheme-main .irantheme-physical-product .pp-related-rail,'
		. '#irantheme-main .irantheme-physical-product .pp-reviews,'
		. '#irantheme-main .irantheme-single-product-related-wrapper,'
		. '#irantheme-main .irantheme-single-product-upsell-wrapper{max-width:1560px;width:100%;margin-inline:auto;box-sizing:border-box}'
		. 'body.single-product #irantheme-main .irantheme-single-product>.irantheme-container-inner{width:100%!important;max-width:none!important;padding-inline:0!important;margin-inline:0!important}'
		. '#irantheme-main .irantheme-single-product-wrapper{box-sizing:border-box;width:100%;max-width:none;padding:8px 0 72px}'
		. '@media(min-width:992px){'
		. '#irantheme-main .irantheme-single-product-main-content-wrapper.row{'
		. 'display:grid!important;grid-template-columns:minmax(0,1.1fr) minmax(0,1.2fr) minmax(300px,360px);'
		. 'column-gap:32px;row-gap:24px;align-items:stretch;margin:0!important;width:100%;--bs-gutter-x:0}'
		. '#irantheme-main .irantheme-physical-product .irantheme-single-product-main-content.col-lg-9,'
		. '#irantheme-main .irantheme-physical-product .irantheme-single-product-main-content>.row,'
		. '#irantheme-main .irantheme-physical-product .pp-stage{display:contents!important}'
		. '#irantheme-main .irantheme-physical-product .pp-stage__gallery{grid-column:1;grid-row:1}'
		. '#irantheme-main .irantheme-physical-product .pp-stage__identity{grid-column:2;grid-row:1;min-width:0}'
		. '#irantheme-main .irantheme-physical-product .pp-buy{grid-column:3;grid-row:1;align-self:stretch;'
		. 'background:#fff;border:1px solid #e8ece9;border-radius:14px;padding:16px;'
		. 'box-shadow:0 8px 28px rgba(16,24,18,.07)}'
		. '#irantheme-main .irantheme-physical-product .pp-buy>.irantheme-single-product-shopping-summary,'
		. '#irantheme-main .irantheme-physical-product .pp-stage__identity>.product-info,'
		. '#irantheme-main .irantheme-physical-product .product-image{'
		. 'width:100%!important;max-width:none!important;flex:none!important;padding:0!important;margin:0!important}'
		. '#irantheme-main .irantheme-physical-product .pp-stage__identity form.cart,'
		. '#irantheme-main .irantheme-physical-product .pp-stage__identity .single_variation_wrap,'
		. '#irantheme-main .irantheme-physical-product .pp-stage__identity .woocommerce-variation-add-to-cart{'
		. 'display:none!important}'
		. '}'
		/* Rails boot: hide related / bestsellers / offers until Swiper+offers JS settle (no jumble flash). */
		. '@keyframes pp-skel{0%{background-position:100% 0}100%{background-position:-100% 0}}'
		. 'body.single-product .pp-related-rail:not(.is-rails-ready),'
		. 'body.single-product .pp-discovery .pp-home-section:not(.is-rails-ready){'
		. 'position:relative;min-height:280px;overflow:hidden;border-radius:16px;margin-block:12px}'
		. 'body.single-product .pp-related-rail:not(.is-rails-ready)>*,'
		. 'body.single-product .pp-discovery .pp-home-section:not(.is-rails-ready)>*{visibility:hidden!important}'
		. 'body.single-product .pp-related-rail:not(.is-rails-ready)::after,'
		. 'body.single-product .pp-discovery .pp-home-section:not(.is-rails-ready)::after{'
		. 'content:"";position:absolute;inset:0;z-index:30;border-radius:16px;'
		. 'background:linear-gradient(90deg,#eef1ef 0%,#f7f8f7 42%,#eef1ef 85%);'
		. 'background-size:220% 100%;animation:pp-skel 1.15s ease-in-out infinite}'
		. 'body.single-product .pp-related-rail.is-rails-ready,'
		. 'body.single-product .pp-discovery .pp-home-section.is-rails-ready{min-height:0}'
		. 'body.single-product .pp-related-rail.is-rails-ready::after,'
		. 'body.single-product .pp-discovery .pp-home-section.is-rails-ready::after{display:none}'
		/* Backup: never let uninitialised Swipers wrap/stack into a mess. */
		. '#irantheme-main .swiper:not(.swiper-initialized){overflow:hidden!important}'
		. '#irantheme-main .swiper:not(.swiper-initialized)>.swiper-wrapper{'
		. 'display:flex!important;flex-wrap:nowrap!important;transform:none!important;width:max-content!important}'
		. '#irantheme-main .swiper:not(.swiper-initialized) .swiper-slide{flex-shrink:0!important}'
		. '</style>' . "\n";
	echo '<noscript><style id="pp-pdp-critical-noscript">'
		. 'body.single-product .pp-related-rail:not(.is-rails-ready)>*,'
		. 'body.single-product .pp-discovery .pp-home-section:not(.is-rails-ready)>*{visibility:visible!important}'
		. 'body.single-product .pp-related-rail:not(.is-rails-ready)::after,'
		. 'body.single-product .pp-discovery .pp-home-section:not(.is-rails-ready)::after{display:none}'
		. '</style></noscript>' . "\n";
}
add_action( 'wp_head', 'storeplus_child_pdp_critical_head', 0 );

/**
 * Hide trust strip when admin disables it for this product.
 *
 * @param array $items Trust items.
 * @return array
 */
function storeplus_child_pdp_filter_trust( $items ) {
	if ( ! function_exists( 'is_product' ) || ! is_product() ) {
		return $items;
	}
	$id = get_queried_object_id();
	if ( $id && ! storeplus_child_pdp_show( $id, 'trust' ) ) {
		return array();
	}
	return $items;
}
add_filter( 'storeplus_child_product_trust_items', 'storeplus_child_pdp_filter_trust', 20 );

/**
 * Respect installments visibility toggle (in addition to enable flag).
 *
 * @param int|null $product_id Product ID.
 */
function storeplus_child_pdp_gate_installments( $product_id = null ) {
	$product_id = $product_id ? (int) $product_id : (int) get_the_ID();
	if ( $product_id && ! storeplus_child_pdp_show( $product_id, 'installments' ) ) {
		return;
	}
	if ( function_exists( 'storeplus_child_render_product_installments' ) ) {
		storeplus_child_render_product_installments( $product_id );
	}
}

/**
 * Product tabs: add health tabs; remove Woo tabs when hidden.
 *
 * @param array $tabs Tabs.
 * @return array
 */
function storeplus_child_pdp_product_tabs( $tabs ) {
	if ( ! is_product() ) {
		return $tabs;
	}
	$product_id = get_the_ID();

	if ( ! storeplus_child_pdp_show( $product_id, 'tab_description' ) ) {
		unset( $tabs['description'] );
	}
	if ( ! storeplus_child_pdp_show( $product_id, 'tab_attributes' ) ) {
		unset( $tabs['additional_information'] );
	}
	if ( ! storeplus_child_pdp_show( $product_id, 'tab_reviews' ) ) {
		unset( $tabs['reviews'] );
	}
	if ( ! storeplus_child_pdp_show( $product_id, 'tab_qa' ) ) {
		unset( $tabs['product_qa'] );
		foreach ( array_keys( $tabs ) as $key ) {
			if ( false !== stripos( (string) $key, 'qa' ) || false !== stripos( (string) $key, 'question' ) ) {
				unset( $tabs[ $key ] );
			}
		}
	}

	// Health content renders in the in-flow .pp-health panel (not duplicate Woo tabs).
	unset( $tabs['sp_ingredients'], $tabs['sp_usage'], $tabs['sp_warnings'] );

	return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'storeplus_child_pdp_product_tabs', 99 );

/**
 * Tab: ingredients.
 */
function storeplus_child_pdp_tab_ingredients() {
	$product_id = get_the_ID();
	$rows       = storeplus_child_pdp_parse_ingredients( get_post_meta( $product_id, SP_PDP_INGREDIENTS_KEY, true ) );
	if ( empty( $rows ) ) {
		return;
	}
	echo '<div class="sp-pdp-tab sp-pdp-tab--ingredients">';
	echo '<h2 class="sp-pdp-tab__title">ترکیبات</h2>';
	echo '<table class="sp-pdp-ingredients"><thead><tr><th>ماده</th><th>مقدار</th></tr></thead><tbody>';
	foreach ( $rows as $row ) {
		echo '<tr><td>' . esc_html( $row['name'] ) . '</td><td>' . esc_html( $row['amount'] ) . '</td></tr>';
	}
	echo '</tbody></table></div>';
}

/**
 * Tab: usage.
 */
function storeplus_child_pdp_tab_usage() {
	$text = (string) get_post_meta( get_the_ID(), SP_PDP_USAGE_KEY, true );
	if ( '' === trim( $text ) ) {
		return;
	}
	echo '<div class="sp-pdp-tab sp-pdp-tab--usage">';
	echo '<h2 class="sp-pdp-tab__title">نحوه مصرف</h2>';
	echo '<div class="sp-pdp-prose">' . nl2br( esc_html( $text ) ) . '</div>';
	echo '</div>';
}

/**
 * Tab: warnings.
 */
function storeplus_child_pdp_tab_warnings() {
	$text = (string) get_post_meta( get_the_ID(), SP_PDP_WARNINGS_KEY, true );
	if ( '' === trim( $text ) ) {
		return;
	}
	echo '<div class="sp-pdp-tab sp-pdp-tab--warnings">';
	echo '<h2 class="sp-pdp-tab__title">هشدارها و موارد احتیاط</h2>';
	echo '<div class="sp-pdp-prose sp-pdp-prose--warn">' . nl2br( esc_html( $text ) ) . '</div>';
	echo '<p class="sp-pdp-disclaimer">اطلاعات این صفحه جنبه اطلاع‌رسانی دارد و جایگزین مشاوره پزشک یا داروساز نیست.</p>';
	echo '</div>';
}

/**
 * Related products visibility.
 * WooCommerce passes ($related_posts, $product_id, $args) — accept variadic safely.
 *
 * @param int[] $related Related IDs.
 * @return int[]
 */
function storeplus_child_pdp_related_ids( $related ) {
	$product_id = 0;
	if ( func_num_args() >= 2 ) {
		$product_id = (int) func_get_arg( 1 );
	}
	if ( ! $product_id && function_exists( 'is_product' ) && is_product() ) {
		$product_id = (int) get_queried_object_id();
	}
	if ( $product_id && ! storeplus_child_pdp_show( $product_id, 'related' ) ) {
		return array();
	}
	return is_array( $related ) ? $related : array();
}
add_filter( 'woocommerce_related_products', 'storeplus_child_pdp_related_ids', 20, 3 );

/**
 * Also gate related query args (1 argument only in WC).
 *
 * @param array $args Query args.
 * @return array
 */
function storeplus_child_pdp_related_args( $args ) {
	$product_id = function_exists( 'is_product' ) && is_product() ? (int) get_queried_object_id() : 0;
	if ( $product_id && ! storeplus_child_pdp_show( $product_id, 'related' ) ) {
		$args['posts_per_page'] = 0;
	}
	return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'storeplus_child_pdp_related_args', 20 );

/**
 * Empty upsells when hidden.
 *
 * @param int[]      $ids IDs.
 * @param WC_Product $product Product.
 * @return int[]
 */
function storeplus_child_pdp_upsell_ids( $ids, $product ) {
	if ( $product && is_a( $product, 'WC_Product' ) && ! storeplus_child_pdp_show( $product->get_id(), 'upsells' ) ) {
		return array();
	}
	return $ids;
}
add_filter( 'woocommerce_product_get_upsell_ids', 'storeplus_child_pdp_upsell_ids', 20, 2 );
add_filter( 'woocommerce_product_variation_get_upsell_ids', 'storeplus_child_pdp_upsell_ids', 20, 2 );

/**
 * Homepage page-builder sections (exact field bags).
 *
 * @return array<int, array<string, mixed>>
 */
function storeplus_child_pdp_home_sections() {
	static $items = null;
	if ( null !== $items ) {
		return $items;
	}
	$items = array();
	$home  = (int) get_option( 'page_on_front' );
	if ( ! $home ) {
		return $items;
	}
	$key = function_exists( 'irantheme_get_meta_data_sections_by_page_id' )
		? irantheme_get_meta_data_sections_by_page_id()
		: 'irantheme_page_sections';
	$raw = get_post_meta( $home, $key, true );
	if ( ! $raw ) {
		return $items;
	}
	$data = class_exists( '\Irantheme_Dev\Irantheme_JSON_Helper' )
		? \Irantheme_Dev\Irantheme_JSON_Helper::decode( $raw )
		: ( is_string( $raw ) ? json_decode( $raw, true ) : $raw );
	if ( class_exists( '\Irantheme_Dev\Irantheme_JSON_Helper' ) && is_array( $data ) ) {
		$data = \Irantheme_Dev\Irantheme_JSON_Helper::normalize_sections( $data );
	}
	if ( ! empty( $data['sections_items'] ) && is_array( $data['sections_items'] ) ) {
		$items = $data['sections_items'];
	}
	return $items;
}

/**
 * First homepage section matching a page-builder data_value.
 *
 * @param string $data_value Section type key.
 * @return array<string, mixed>|null
 */
function storeplus_child_pdp_home_section( $data_value ) {
	foreach ( storeplus_child_pdp_home_sections() as $section ) {
		if ( ! empty( $section['data_value'] ) && $section['data_value'] === $data_value ) {
			return $section;
		}
	}
	return null;
}

/**
 * Prep homepage section fields for SSR on the PDP (no skeleton / AJAX).
 *
 * Homepage «شگفت‌انگیز» is often `custom` with empty selection + skeleton AJAX.
 * On PDP we force a real product query and skip the skeleton shell.
 *
 * @param string               $data_value Section key.
 * @param array<string, mixed> $fields     Section fields (by ref).
 */
function storeplus_child_pdp_prepare_home_section_fields( $data_value, array &$fields ) {
	/* Homepage sections carry large outer/inner padding — strip on PDP so rails sit tight. */
	$fields['custom_outer_padding_checkbox'] = 'off';
	$fields['custom_inner_padding_checkbox'] = 'off';
	$fields['outer_padding_top_range']       = 0;
	$fields['outer_padding_bottom_range']    = 0;
	$fields['inner_padding_top_range']       = 0;
	$fields['inner_padding_bottom_range']    = 0;

	/*
	 * Offers is often `custom` + empty selection + skeleton AJAX (home + PDP).
	 * Mini-products uses an object-group layout — do not force data_load there
	 * (it blanks the SSR cards). Only normalize offers.
	 */
	if ( 'woocommerce_offers_products' !== $data_value ) {
		return;
	}

	/* Skip skeleton shell — SSR product cards instead of empty AJAX fill. */
	$fields['data_load_checkbox']         = 'on';
	$fields['heading_data_load_checkbox'] = 'on';

	$type = isset( $fields['product_content_type_radio'] ) ? (string) $fields['product_content_type_radio'] : '';
	$probe_needed = in_array( $type, array( '', 'custom' ), true );
	if ( ! $probe_needed && class_exists( '\Irantheme_Dev\Irantheme_Section_Utils' ) ) {
		$q0 = \Irantheme_Dev\Irantheme_Section_Utils::get_product_content( $fields );
		$probe_needed = ! ( $q0 && ! empty( $q0->post_count ) );
	}
	if ( ! $probe_needed ) {
		return;
	}

	/* Reuse a resolved offers query across PDP views (invalidated on product save). */
	$cached = get_transient( 'storeplus_child_pdp_offers_probe_v1' );
	if ( is_array( $cached ) && ! empty( $cached['product_content_type_radio'] ) ) {
		foreach ( $cached as $k => $v ) {
			$fields[ $k ] = $v;
		}
		return;
	}

	$resolved = null;
	foreach ( array( 'discount', 'sales', 'latest' ) as $candidate ) {
		$probe = $fields;
		$probe['product_content_type_radio'] = $candidate;
		$probe['product_count_number']       = max( 4, (int) ( $fields['product_count_number'] ?? 8 ) );
		if ( ! class_exists( '\Irantheme_Dev\Irantheme_Section_Utils' ) ) {
			$resolved = array(
				'product_content_type_radio' => $candidate,
				'product_count_number'       => $probe['product_count_number'],
			);
			break;
		}
		$q = \Irantheme_Dev\Irantheme_Section_Utils::get_product_content( $probe );
		if ( $q && ! empty( $q->post_count ) ) {
			$resolved = array(
				'product_content_type_radio' => $candidate,
				'product_count_number'       => $probe['product_count_number'],
			);
			break;
		}
	}

	if ( null === $resolved ) {
		$ids = array();
		if ( function_exists( 'wc_get_products' ) ) {
			$ids = wc_get_products(
				array(
					'limit'   => 8,
					'status'  => 'publish',
					'on_sale' => true,
					'return'  => 'ids',
				)
			);
			if ( empty( $ids ) ) {
				$ids = wc_get_products(
					array(
						'limit'   => 8,
						'status'  => 'publish',
						'orderby' => 'date',
						'order'   => 'DESC',
						'return'  => 'ids',
					)
				);
			}
		}
		if ( ! empty( $ids ) && is_array( $ids ) ) {
			$resolved = array(
				'product_content_type_radio' => 'custom',
				'product_selection'          => implode( ',', array_map( 'intval', $ids ) ),
				'product_count_number'       => count( $ids ),
			);
		}
	}

	if ( is_array( $resolved ) ) {
		foreach ( $resolved as $k => $v ) {
			$fields[ $k ] = $v;
		}
		set_transient( 'storeplus_child_pdp_offers_probe_v1', $resolved, HOUR_IN_SECONDS );
	}
}

/**
 * Drop cached PDP offers probe when the catalog changes.
 */
function storeplus_child_pdp_flush_offers_probe() {
	delete_transient( 'storeplus_child_pdp_offers_probe_v1' );
}
add_action( 'woocommerce_new_product', 'storeplus_child_pdp_flush_offers_probe' );
add_action( 'woocommerce_update_product', 'storeplus_child_pdp_flush_offers_probe' );
add_action( 'woocommerce_delete_product', 'storeplus_child_pdp_flush_offers_probe' );

/**
 * Render one homepage page-builder section (same class + fields as home).
 *
 * @param string $data_value Section type: woocommerce_mini_products | woocommerce_offers_products.
 * @return bool
 */
function storeplus_child_pdp_render_home_section( $data_value ) {
	$section = storeplus_child_pdp_home_section( $data_value );
	if ( ! $section || empty( $section['section_fields'] ) || ! is_array( $section['section_fields'] ) ) {
		return false;
	}

	$map = array(
		'woocommerce_mini_products'      => '\Irantheme_Dev\Irantheme_Section_Woocommerce_Mini_Products',
		'woocommerce_offers_products'    => '\Irantheme_Dev\Irantheme_Section_Woocommerce_Offers_Products',
		'woocommerce_category_products'  => '\Irantheme_Dev\Irantheme_Section_Woocommerce_Category_Products',
	);
	if ( empty( $map[ $data_value ] ) || ! class_exists( $map[ $data_value ] ) || ! method_exists( $map[ $data_value ], 'render' ) ) {
		return false;
	}

	$fields = $section['section_fields'];
	if ( class_exists( '\Irantheme_Dev\Irantheme_Page_Builder_Ajax' ) && class_exists( '\Irantheme_Dev\Irantheme_Page_Builder' ) ) {
		$settings = \Irantheme_Dev\Irantheme_Page_Builder_Ajax::get_function_fields( $data_value, true );
		if ( is_array( $settings ) ) {
			\Irantheme_Dev\Irantheme_Page_Builder::hydrate_missing_fields( $settings, $fields, $data_value );
		}
	}

	storeplus_child_pdp_prepare_home_section_fields( $data_value, $fields );

	$data_type = isset( $section['data_type'] ) ? $section['data_type'] : 'section';
	$data_page = isset( $section['data_page'] ) ? $section['data_page'] : (string) (int) get_option( 'page_on_front' );
	/* Neutral ajax id — theme must not treat this as a live builder slot to re-fetch. */
	$data_ajax = 'pdp-static-' . $data_value;
	$class     = $map[ $data_value ];

	echo '<div class="pp-home-section pp-home-section--' . esc_attr( $data_value ) . '" id="pp-sec-' . esc_attr( $data_value ) . '" data-pp-static="1">';
	ob_start();
	$class::render( $fields, $data_type, $data_value, $data_page, $data_ajax );
	$html = (string) ob_get_clean();
	/* Strip page-builder / AJAX hooks so front JS does not empty SSR cards. */
	$html = preg_replace( '/\sdata-(?:ajax|page|type|value|section|json)=("[^"]*"|\'[^\']*\')/i', '', $html );
	$html = str_replace(
		array(
			'irantheme-skeleton-section',
			'irantheme-woocommerce-ajax-categories-products-swiper',
			'irantheme-side-load-skeleton',
		),
		array( '', 'pp-discovery-swiper', '' ),
		$html
	);
	echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- theme section HTML
	echo '</div>';
	return true;
}

/**
 * Bestsellers on PDP — same vertical storeplus_child_product_card as landing rails
 * (related / offers / category), not the horizontal mini-products card.
 *
 * Product query + title come from the homepage «پر فروش ترین محصولات» section.
 *
 * @return bool
 */
function storeplus_child_pdp_render_bestsellers_rail() {
	if ( ! function_exists( 'storeplus_child_product_card' ) ) {
		return storeplus_child_pdp_render_home_section( 'woocommerce_mini_products' );
	}

	$heading = 'پر فروش ترین محصولات';
	$link    = '#';
	$fields  = array(
		'product_content_type_radio' => 'sales',
		'product_count_number'       => 8,
	);

	$section = storeplus_child_pdp_home_section( 'woocommerce_mini_products' );
	if ( $section && ! empty( $section['section_fields'] ) && is_array( $section['section_fields'] ) ) {
		$fields = $section['section_fields'];
		if ( class_exists( '\Irantheme_Dev\Irantheme_Page_Builder_Ajax' ) && class_exists( '\Irantheme_Dev\Irantheme_Page_Builder' ) ) {
			$settings = \Irantheme_Dev\Irantheme_Page_Builder_Ajax::get_function_fields( 'woocommerce_mini_products', true );
			if ( is_array( $settings ) ) {
				\Irantheme_Dev\Irantheme_Page_Builder::hydrate_missing_fields( $settings, $fields, 'woocommerce_mini_products' );
			}
		}
		storeplus_child_pdp_prepare_home_section_fields( 'woocommerce_mini_products', $fields );
		if ( ! empty( $fields['heading_text'] ) ) {
			$heading = (string) $fields['heading_text'];
		}
		if ( isset( $fields['heading_link'] ) && '' !== (string) $fields['heading_link'] ) {
			$link = (string) $fields['heading_link'];
		}
	}

	$query = null;
	if ( class_exists( '\Irantheme_Dev\Irantheme_Section_Utils' ) ) {
		$query = \Irantheme_Dev\Irantheme_Section_Utils::get_product_content( $fields );
		if ( ! $query || empty( $query->post_count ) ) {
			foreach ( array( 'sales', 'latest', 'discount' ) as $candidate ) {
				$probe                              = $fields;
				$probe['product_content_type_radio'] = $candidate;
				$probe['product_count_number']       = max( 4, (int) ( $fields['product_count_number'] ?? 8 ) );
				$q                                   = \Irantheme_Dev\Irantheme_Section_Utils::get_product_content( $probe );
				if ( $q && ! empty( $q->post_count ) ) {
					$query = $q;
					break;
				}
			}
		}
	}

	if ( ! $query || empty( $query->post_count ) ) {
		return false;
	}

	$cols = 5;
	if ( ! empty( $fields['col_count_radio'] ) && preg_match( '/col-(\d+)/', (string) $fields['col_count_radio'], $m ) ) {
		$cols = max( 3, min( 6, (int) $m[1] ) );
	}

	echo '<div class="pp-home-section pp-home-section--woocommerce_mini_products" id="pp-sec-woocommerce_mini_products" data-pp-static="1">';
	?>
	<section class="irantheme-section irantheme-woocommerce-products-section pp-bestsellers-rail" aria-label="<?php echo esc_attr( $heading ); ?>">
		<div class="irantheme-woocommerce-products white">
			<div class="irantheme-container-inner">
				<div class="irantheme-woocommerce-products-wrapper">
					<div class="pp-bestsellers-heading">
						<h3 class="pp-bestsellers-heading__title"><?php echo esc_html( $heading ); ?></h3>
						<?php if ( $link && '#' !== $link ) : ?>
							<a href="<?php echo esc_url( $link ); ?>" class="pp-bestsellers-heading__link" aria-label="<?php echo esc_attr( 'مشاهده همه' ); ?>">
								<i class="fas fa-angle-left" aria-hidden="true"></i>
							</a>
						<?php endif; ?>
					</div>
					<div class="irantheme-woocommerce-product-swiper swiper irantheme-woocommerce-products-post-swiper pp-discovery-swiper" data-columns="<?php echo (int) $cols; ?>">
						<div class="irantheme-woocommerce-slider-arrow-btn">
							<?php
							if ( class_exists( '\Irantheme_Dev\Irantheme_Section_Utils' ) ) {
								\Irantheme_Dev\Irantheme_Section_Utils::get_slider_arrow_pagination( 'on', 'on', 'off', 'white', 1, 'bottom', 'center' );
							}
							?>
						</div>
						<div class="swiper-wrapper">
							<?php
							while ( $query->have_posts() ) {
								$query->the_post();
								$product = function_exists( 'wc_get_product' ) ? wc_get_product( get_the_ID() ) : null;
								if ( ! $product || ! $product->is_visible() ) {
									continue;
								}
								storeplus_child_product_card(
									$product,
									array(
										'layout'    => 'vertical',
										'col_class' => 'swiper-slide',
									)
								);
							}
							wp_reset_postdata();
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php
	echo '</div>';
	return true;
}

/**
 * Discovery rails: bestsellers (vertical product_card) then amazing offers.
 *
 * @param int|null $product_id Unused; kept for call-site compat.
 */
function storeplus_child_pdp_render_discovery_rails( $product_id = null ) {
	unset( $product_id );
	/* Bestsellers and شگفت‌انگیز stay on the homepage only. */
}

/**
 * Related products — exact homepage «محصولات دسته» section (category card + product_card).
 *
 * @param int|null $product_id Current product.
 */
function storeplus_child_pdp_render_related_box( $product_id = null ) {
	$product_id = (int) ( $product_id ? $product_id : get_the_ID() );
	if ( ! $product_id || ! function_exists( 'wc_get_related_products' ) ) {
		return;
	}
	if ( function_exists( 'storeplus_child_pdp_show' ) && ! storeplus_child_pdp_show( $product_id, 'related' ) ) {
		return;
	}

	$ids = wc_get_related_products( $product_id, 8 );
	$ids = array_values( array_filter( array_map( 'absint', (array) $ids ) ) );
	if ( empty( $ids ) ) {
		return;
	}

	$cat_id = 0;
	$cats   = function_exists( 'wc_get_product_term_ids' ) ? wc_get_product_term_ids( $product_id, 'product_cat' ) : array();
	if ( ! empty( $cats ) ) {
		$cat_id = (int) $cats[0];
	}

	/*
	 * Prefer the real homepage category-products section (same class, fields, CSS, JS).
	 * Force related IDs + this product's category for the green side card.
	 */
	$section = storeplus_child_pdp_home_section( 'woocommerce_category_products' );
	$class   = '\Irantheme_Dev\Irantheme_Section_Woocommerce_Category_Products';
	if ( $section && ! empty( $section['section_fields'] ) && is_array( $section['section_fields'] ) && class_exists( $class ) ) {
		$fields = $section['section_fields'];
		if ( class_exists( '\Irantheme_Dev\Irantheme_Page_Builder_Ajax' ) && class_exists( '\Irantheme_Dev\Irantheme_Page_Builder' ) ) {
			$settings = \Irantheme_Dev\Irantheme_Page_Builder_Ajax::get_function_fields( 'woocommerce_category_products', true );
			if ( is_array( $settings ) ) {
				\Irantheme_Dev\Irantheme_Page_Builder::hydrate_missing_fields( $settings, $fields, 'woocommerce_category_products' );
			}
		}

		$fields['data_load_checkbox']              = 'on';
		$fields['heading_data_load_checkbox']      = 'on';
		$fields['product_content_type_radio']      = 'custom';
		$fields['product_selection']               = implode( ',', $ids );
		$fields['product_count_number']            = count( $ids );
		if ( $cat_id ) {
			$fields['product_once_category_selection'] = (string) $cat_id;
		}
		storeplus_child_pdp_prepare_home_section_fields( 'woocommerce_category_products', $fields );

		$data_type = isset( $section['data_type'] ) ? $section['data_type'] : 'section';
		$data_page = isset( $section['data_page'] ) ? $section['data_page'] : (string) (int) get_option( 'page_on_front' );

		echo '<div class="pp-related-rail pp-home-section pp-home-section--woocommerce_category_products" id="pp-sec-related" data-pp-static="1">';
		ob_start();
		$class::render( $fields, $data_type, 'woocommerce_category_products', $data_page, 'pdp-static-related' );
		$html = (string) ob_get_clean();
		$html = preg_replace( '/\sdata-(?:ajax|page|type|value|section|json)=("[^"]*"|\'[^\']*\')/i', '', $html );
		$html = str_replace(
			array(
				'irantheme-skeleton-section',
				'irantheme-woocommerce-ajax-categories-products-swiper',
				'irantheme-side-load-skeleton',
			),
			array( '', 'pp-discovery-swiper', '' ),
			$html
		);
		/* Force side card title to «محصولات مرتبط» while keeping category image/link. */
		$html = preg_replace(
			'/(<h3 class="irantheme-woocommerce-category-products-cat-item-heading">)(.*?)(<\/h3>)/u',
			'$1' . esc_html( 'محصولات مرتبط' ) . '$3',
			$html,
			1
		);
		/* Ensure category-card-fixed keeps the green card on the right (RTL). */
		$html = preg_replace(
			'/(<section\b[^>]*\birantheme-woocommerce-category-products-section\b[^>]*)(>)/i',
			'$1 data-cp-side="right"$2',
			$html,
			1
		);
		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- theme section HTML
		echo '</div>';
		return;
	}

	/* Fallback markup if homepage has no category-products section saved. */
	if ( ! function_exists( 'storeplus_child_product_card' ) ) {
		return;
	}
	$cat_title = 'محصولات مرتبط';
	$cat_link  = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );
	$cat_thumb = 0;
	if ( $cat_id ) {
		$term = get_term( $cat_id, 'product_cat' );
		if ( $term && ! is_wp_error( $term ) ) {
			$link = get_term_link( $term );
			if ( ! is_wp_error( $link ) ) {
				$cat_link = $link;
			}
			$cat_thumb = (int) get_term_meta( $term->term_id, 'thumbnail_id', true );
		}
	}
	?>
	<div class="pp-related-rail" id="pp-sec-related" data-pp-static="1">
		<section class="irantheme-section irantheme-woocommerce-category-products-section irantheme-woocommerce-products-section" data-cp-side="right" aria-label="<?php echo esc_attr( 'محصولات مرتبط' ); ?>">
			<div class="irantheme-woocommerce-products white">
				<div class="irantheme-container-inner">
					<div class="irantheme-woocommerce-products-wrapper">
						<div class="irantheme-woocommerce-product-swiper swiper irantheme-woocommerce-products-post-swiper" data-columns="4">
							<div class="irantheme-woocommerce-slider-arrow-btn">
								<?php
								if ( class_exists( '\Irantheme_Dev\Irantheme_Section_Utils' ) ) {
									\Irantheme_Dev\Irantheme_Section_Utils::get_slider_arrow_pagination( 'on', 'on', 'off', 'white', 1, 'bottom', 'center' );
								}
								?>
							</div>
							<div class="swiper-wrapper">
								<div class="irantheme-woocommerce-products-col irantheme-heading-desktop-mode irantheme-woocommerce-category-products-cat swiper-slide d-flex flex-column justify-content-center">
									<div class="irantheme-woocommerce-category-products-cat-item">
										<a href="<?php echo esc_url( $cat_link ); ?>" class="irantheme-woocommerce-category-products-cat-item-link"><?php echo esc_html( 'مشاهده دسته بندی' ); ?><i class="fa-light fa-angle-left" aria-hidden="true"></i></a>
										<?php
										if ( $cat_thumb ) {
											echo wp_get_attachment_image(
												$cat_thumb,
												'medium',
												false,
												array(
													'class'   => 'irantheme-woocommerce-category-products-cat-item-image',
													'alt'     => esc_attr( $cat_title ),
													'loading' => 'lazy',
												)
											);
										}
										?>
										<h3 class="irantheme-woocommerce-category-products-cat-item-heading"><?php echo esc_html( $cat_title ); ?></h3>
									</div>
								</div>
								<?php
								foreach ( $ids as $rid ) {
									$rel = wc_get_product( $rid );
									if ( ! $rel || ! $rel->is_visible() ) {
										continue;
									}
									storeplus_child_product_card(
										$rel,
										array(
											'layout'    => 'vertical',
											'col_class' => 'swiper-slide',
										)
									);
								}
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
	<?php
}

/**
 * Approved product reviews for the PDP box.
 *
 * @param int $product_id Product ID.
 * @return WP_Comment[]
 */
function storeplus_child_pdp_get_reviews( $product_id ) {
	$product_id = (int) $product_id;
	if ( ! $product_id ) {
		return array();
	}
	$comments = get_comments(
		array(
			'post_id' => $product_id,
			'status'  => 'approve',
			'type'    => 'review',
			'orderby' => 'comment_date_gmt',
			'order'   => 'DESC',
			'number'  => 20,
		)
	);
	return is_array( $comments ) ? $comments : array();
}

/**
 * Sale percentage label for PDP (same helper the product cards use when available).
 *
 * @param WC_Product $product Product.
 * @return string Empty when not on sale.
 */
function storeplus_child_pdp_sale_percent_label( $product ) {
	if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
		return '';
	}
	$n = 0;
	if ( function_exists( 'irantheme_get_sale_percentage' ) ) {
		$pct = irantheme_get_sale_percentage( $product );
		if ( false !== $pct && null !== $pct && '' !== $pct && 0 !== $pct && '0' !== (string) $pct ) {
			$raw = is_string( $pct ) ? wp_strip_all_tags( $pct ) : (string) $pct;
			if ( preg_match( '/(\d+(?:\.\d+)?)/u', $raw, $m ) ) {
				$n = (int) round( (float) $m[1] );
			}
		}
	}
	if ( $n <= 0 && $product->is_on_sale() ) {
		$regular = (float) $product->get_regular_price();
		$sale    = (float) $product->get_sale_price();
		if ( $regular > 0 && $sale >= 0 && $sale < $regular ) {
			$n = (int) round( 100 - ( $sale / $regular * 100 ) );
		}
	}
	if ( $n <= 0 ) {
		return '';
	}
	return sprintf( '%s٪', number_format_i18n( $n ) );
}

/**
 * Theme rating badge (product-review-summary) for the top of the buy card.
 * Star on the right, discount chip on the left (no reviews-count chip).
 *
 * @param int|null $product_id Product ID.
 */
function storeplus_child_pdp_render_rating_badge( $product_id = null ) {
	$product_id = (int) ( $product_id ? $product_id : get_the_ID() );
	if ( ! $product_id || ! function_exists( 'wc_get_product' ) ) {
		return;
	}

	$product = wc_get_product( $product_id );
	if ( ! $product ) {
		return;
	}

	$show_rating = ! function_exists( 'storeplus_child_pdp_show' ) || storeplus_child_pdp_show( $product_id, 'rating' );
	$show_rating = $show_rating && function_exists( 'wc_review_ratings_enabled' ) && wc_review_ratings_enabled();

	$sale_label = storeplus_child_pdp_sale_percent_label( $product );
	if ( ! $show_rating && ! $sale_label ) {
		return;
	}

	$rating_count = (int) $product->get_rating_count();
	$review_count = (int) $product->get_review_count();
	$average      = (float) $product->get_average_rating();
	?>
	<div class="product-review-summary pp-buy-rating">
		<?php if ( $show_rating ) : ?>
			<?php if ( $rating_count > 0 ) : ?>
				<a class="product-rating-star" href="#pp-sec-reviews" rel="nofollow">
					<i class="fas fa-star" aria-hidden="true"></i>
					<span class="average"><?php echo esc_html( number_format_i18n( $average, 1 ) ); ?></span>
					<span class="review-count">(<?php echo esc_html( sprintf( 'امتیاز %s خریدار', number_format_i18n( $review_count ) ) ); ?>)</span>
				</a>
			<?php else : ?>
				<a class="product-rating-star product-rating-star--empty" href="#pp-sec-reviews" rel="nofollow">
					<i class="fas fa-star" aria-hidden="true"></i>
					<span class="average">—</span>
					<span class="review-count">(هنوز امتیازی ثبت نشده)</span>
				</a>
			<?php endif; ?>
		<?php endif; ?>
		<?php if ( $sale_label ) : ?>
			<span class="pp-buy-discount irantheme-woocommerce-products-discount" aria-label="<?php echo esc_attr( 'تخفیف ' . $sale_label ); ?>">
				<?php echo esc_html( $sale_label ); ?>
			</span>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Star row markup (1–5).
 *
 * @param int $rating Rating.
 * @return string
 */
function storeplus_child_pdp_stars_html( $rating ) {
	$rating = max( 0, min( 5, (int) $rating ) );
	$html   = '<span class="pp-reviews__stars" aria-label="' . esc_attr( sprintf( '%d از ۵', $rating ) ) . '">';
	for ( $i = 1; $i <= 5; $i++ ) {
		$on    = $i <= $rating ? ' is-on' : '';
		$html .= '<span class="pp-reviews__star' . $on . '" aria-hidden="true">★</span>';
	}
	$html .= '</span>';
	return $html;
}

/**
 * Force every product review into moderation queue (admin must approve).
 *
 * @param int|string|false $approved    Approval status.
 * @param array            $commentdata Comment payload.
 * @return int|string|false
 */
function storeplus_child_pdp_hold_product_reviews( $approved, $commentdata ) {
	$post_id = isset( $commentdata['comment_post_ID'] ) ? (int) $commentdata['comment_post_ID'] : 0;
	$type    = isset( $commentdata['comment_type'] ) ? (string) $commentdata['comment_type'] : '';
	if ( 'review' === $type || ( $post_id && 'product' === get_post_type( $post_id ) ) ) {
		return 0; /* hold — pending */
	}
	return $approved;
}
add_filter( 'pre_comment_approved', 'storeplus_child_pdp_hold_product_reviews', 99, 2 );

/**
 * Keep Woo reviews + ratings on for the storefront (no DB writes).
 *
 * @param mixed $value Option value.
 * @return string
 */
function storeplus_child_pdp_force_reviews_on( $value ) {
	return 'yes';
}
add_filter( 'option_woocommerce_enable_reviews', 'storeplus_child_pdp_force_reviews_on' );
add_filter( 'option_woocommerce_enable_review_rating', 'storeplus_child_pdp_force_reviews_on' );

/**
 * Handle PDP review form POST (creates pending Woo review).
 */
function storeplus_child_pdp_handle_review_submit() {
	if ( empty( $_POST['sp_pdp_review_nonce'] ) || empty( $_POST['sp_pdp_review_submit'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['sp_pdp_review_nonce'] ) ), 'sp_pdp_review' ) ) {
		return;
	}

	$product_id = isset( $_POST['comment_post_ID'] ) ? absint( $_POST['comment_post_ID'] ) : 0;
	if ( ! $product_id || 'product' !== get_post_type( $product_id ) ) {
		return;
	}
	if ( ! comments_open( $product_id ) && 'open' !== get_post_field( 'comment_status', $product_id ) ) {
		/* Soft-open reviews for this product if closed by mistake. */
		wp_update_post(
			array(
				'ID'             => $product_id,
				'comment_status' => 'open',
			)
		);
	}

	$rating = isset( $_POST['rating'] ) ? absint( $_POST['rating'] ) : 0;
	$rating = max( 1, min( 5, $rating ) );
	$body   = isset( $_POST['comment'] ) ? trim( wp_unslash( (string) $_POST['comment'] ) ) : '';
	$body   = sanitize_textarea_field( $body );

	$user   = wp_get_current_user();
	$author = '';
	$email  = '';
	if ( $user && $user->exists() ) {
		$author = $user->display_name ? $user->display_name : $user->user_login;
		$email  = $user->user_email;
	} else {
		$author = isset( $_POST['author'] ) ? sanitize_text_field( wp_unslash( $_POST['author'] ) ) : '';
		$email  = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	}

	$redirect = get_permalink( $product_id );
	$redirect = $redirect ? $redirect : home_url( '/' );
	$redirect = add_query_arg( array(), $redirect ) . '#pp-sec-reviews';

	if ( '' === $body || '' === $author || ! is_email( $email ) ) {
		wp_safe_redirect( add_query_arg( 'pp_review', 'invalid', $redirect ) );
		exit;
	}

	$commentdata = array(
		'comment_post_ID'      => $product_id,
		'comment_author'       => $author,
		'comment_author_email' => $email,
		'comment_author_url'   => '',
		'comment_content'      => $body,
		'comment_type'         => 'review',
		'comment_parent'       => 0,
		'user_id'              => $user && $user->exists() ? (int) $user->ID : 0,
		'comment_approved'     => 0,
	);

	/* Bypass WP auto-approve for logged-in admins — still hold. */
	remove_filter( 'pre_comment_approved', 'storeplus_child_pdp_hold_product_reviews', 99 );
	add_filter( 'pre_comment_approved', '__return_zero', 100 );
	$comment_id = wp_new_comment( $commentdata, true );
	remove_filter( 'pre_comment_approved', '__return_zero', 100 );
	add_filter( 'pre_comment_approved', 'storeplus_child_pdp_hold_product_reviews', 99, 2 );

	if ( is_wp_error( $comment_id ) || ! $comment_id ) {
		wp_safe_redirect( add_query_arg( 'pp_review', 'error', $redirect ) );
		exit;
	}

	update_comment_meta( (int) $comment_id, 'rating', $rating );
	wp_update_comment(
		array(
			'comment_ID'       => (int) $comment_id,
			'comment_approved' => '0',
		)
	);

	wp_safe_redirect( add_query_arg( 'pp_review', 'pending', $redirect ) );
	exit;
}
add_action( 'template_redirect', 'storeplus_child_pdp_handle_review_submit' );

/**
 * Review submit form markup.
 *
 * @param int $product_id Product ID.
 */
function storeplus_child_pdp_render_review_form( $product_id ) {
	$product_id = (int) $product_id;
	if ( ! $product_id ) {
		return;
	}

	$user     = wp_get_current_user();
	$logged   = $user && $user->exists();
	$flash    = isset( $_GET['pp_review'] ) ? sanitize_key( wp_unslash( $_GET['pp_review'] ) ) : '';
	$action   = esc_url( get_permalink( $product_id ) );
	?>
	<div class="pp-reviews__form-wrap" id="pp-review-form">
		<h3 class="pp-reviews__form-title">ثبت دیدگاه شما</h3>
		<p class="pp-reviews__form-note">پس از ارسال، دیدگاه شما ابتدا توسط مدیر بررسی و تأیید می‌شود و سپس نمایش داده خواهد شد.</p>

		<?php if ( 'pending' === $flash ) : ?>
			<div class="pp-reviews__flash pp-reviews__flash--ok" role="status">
				دیدگاه شما ثبت شد و پس از تأیید مدیر نمایش داده می‌شود.
			</div>
		<?php elseif ( 'invalid' === $flash ) : ?>
			<div class="pp-reviews__flash pp-reviews__flash--err" role="alert">
				لطفاً نام، ایمیل معتبر و متن دیدگاه را کامل وارد کنید.
			</div>
		<?php elseif ( 'error' === $flash ) : ?>
			<div class="pp-reviews__flash pp-reviews__flash--err" role="alert">
				ثبت دیدگاه با خطا روبه‌رو شد. لطفاً دوباره تلاش کنید.
			</div>
		<?php endif; ?>

		<?php if ( 'pending' !== $flash ) : ?>
		<form class="pp-reviews__form" method="post" action="<?php echo $action; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
			<?php wp_nonce_field( 'sp_pdp_review', 'sp_pdp_review_nonce' ); ?>
			<input type="hidden" name="comment_post_ID" value="<?php echo esc_attr( (string) $product_id ); ?>" />
			<input type="hidden" name="sp_pdp_review_submit" value="1" />

			<div class="pp-reviews__rating" role="group" aria-label="<?php echo esc_attr( 'امتیاز شما' ); ?>">
				<span class="pp-reviews__rating-label">امتیاز شما</span>
				<div class="pp-reviews__rating-stars" data-pp-rating>
					<?php for ( $i = 5; $i >= 1; $i-- ) : ?>
						<input type="radio" name="rating" id="pp-rating-<?php echo esc_attr( (string) $i ); ?>" value="<?php echo esc_attr( (string) $i ); ?>" <?php checked( $i, 5 ); ?> required />
						<label for="pp-rating-<?php echo esc_attr( (string) $i ); ?>" title="<?php echo esc_attr( sprintf( '%d از ۵', $i ) ); ?>">★</label>
					<?php endfor; ?>
				</div>
			</div>

			<?php if ( ! $logged ) : ?>
				<div class="pp-reviews__row">
					<label class="pp-reviews__field">
						<span class="pp-reviews__label">نام</span>
						<input type="text" name="author" required maxlength="80" autocomplete="name" placeholder="نام شما" />
					</label>
					<label class="pp-reviews__field">
						<span class="pp-reviews__label">ایمیل</span>
						<input type="email" name="email" required maxlength="100" autocomplete="email" placeholder="email@example.com" dir="ltr" />
					</label>
				</div>
			<?php else : ?>
				<p class="pp-reviews__as">در حال ارسال به‌عنوان <strong><?php echo esc_html( $user->display_name ); ?></strong></p>
			<?php endif; ?>

			<label class="pp-reviews__field pp-reviews__field--full">
				<span class="pp-reviews__label">متن دیدگاه</span>
				<textarea name="comment" rows="4" required maxlength="2000" placeholder="تجربهٔ خود از این محصول را بنویسید…"></textarea>
			</label>

			<button type="submit" class="pp-reviews__submit">ارسال دیدگاه</button>
		</form>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Reviews box under discovery rails. Empty state when no reviews.
 *
 * @param int|null $product_id Product ID.
 */
function storeplus_child_pdp_render_reviews_box( $product_id = null ) {
	$product_id = (int) ( $product_id ? $product_id : get_the_ID() );
	if ( ! $product_id ) {
		return;
	}

	$reviews = storeplus_child_pdp_get_reviews( $product_id );
	$product = function_exists( 'wc_get_product' ) ? wc_get_product( $product_id ) : null;
	$avg     = $product ? (float) $product->get_average_rating() : 0;
	$count   = count( $reviews );
	$visible = 5;
	?>
	<section class="pp-reviews" id="pp-sec-reviews" aria-label="<?php echo esc_attr( 'دیدگاه‌های کاربران' ); ?>">
		<div class="pp-reviews__grid">
			<div class="pp-reviews__aside">
				<header class="pp-reviews__head">
					<h2 class="pp-reviews__title">دیدگاه کاربران</h2>
					<?php if ( $count > 0 ) : ?>
						<div class="pp-reviews__summary">
							<?php echo storeplus_child_pdp_stars_html( (int) round( $avg ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<span class="pp-reviews__avg"><?php echo esc_html( number_format_i18n( $avg, 1 ) ); ?></span>
							<span class="pp-reviews__count"><?php echo esc_html( sprintf( 'از %s دیدگاه', number_format_i18n( $count ) ) ); ?></span>
						</div>
					<?php endif; ?>
				</header>
				<?php storeplus_child_pdp_render_review_form( $product_id ); ?>
			</div>

			<div class="pp-reviews__comments">
				<?php if ( empty( $reviews ) ) : ?>
					<div class="pp-reviews__empty" role="status">
						<span class="pp-reviews__empty-icon" aria-hidden="true">
							<svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M10 12h28a3 3 0 0 1 3 3v16a3 3 0 0 1-3 3H20l-7 7v-7H10a3 3 0 0 1-3-3V15a3 3 0 0 1 3-3Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
								<path d="M16 22h16M16 28h10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
							</svg>
						</span>
						<p class="pp-reviews__empty-title">هنوز دیدگاهی ثبت نشده</p>
						<p class="pp-reviews__empty-text">اولین نفری باشید که تجربهٔ خرید این محصول را به اشتراک می‌گذارد.</p>
					</div>
				<?php else : ?>
					<ul class="pp-reviews__list" data-pp-reviews-list data-visible="<?php echo esc_attr( (string) $visible ); ?>">
						<?php foreach ( $reviews as $i => $comment ) : ?>
							<?php
							$rating = (int) get_comment_meta( $comment->comment_ID, 'rating', true );
							$name   = $comment->comment_author ? $comment->comment_author : 'خریدار';
							$date   = get_comment_date( 'Y/m/d', $comment );
							$body   = trim( wp_strip_all_tags( $comment->comment_content ) );
							$extra  = $i >= $visible;
							?>
							<li class="pp-reviews__item<?php echo $extra ? ' is-extra' : ''; ?>"<?php echo $extra ? ' hidden' : ''; ?>>
								<div class="pp-reviews__body">
									<div class="pp-reviews__meta">
										<span class="pp-reviews__author"><?php echo esc_html( $name ); ?></span>
										<?php if ( $rating ) : ?>
											<?php echo storeplus_child_pdp_stars_html( $rating ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
										<?php endif; ?>
										<time class="pp-reviews__date" datetime="<?php echo esc_attr( get_comment_date( 'c', $comment ) ); ?>"><?php echo esc_html( $date ); ?></time>
									</div>
									<p class="pp-reviews__text"><?php echo esc_html( $body ); ?></p>
								</div>
							</li>
						<?php endforeach; ?>
					</ul>
					<?php if ( $count > $visible ) : ?>
						<button type="button" class="pp-reviews__more" data-pp-reviews-more aria-expanded="false">
							<span class="pp-reviews__more-label">نمایش بیشتر</span>
							<span class="pp-reviews__more-count">(<?php echo esc_html( number_format_i18n( $count - $visible ) ); ?>)</span>
						</button>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Resolve brand for PDP: prefer product_brand taxonomy, then attribute-type brand.
 *
 * @param int $product_id Product ID.
 * @return array{name:string,url:string,logo:string}|null
 */
function storeplus_child_pdp_resolve_brand( $product_id ) {
	$product_id = (int) $product_id;
	if ( ! $product_id ) {
		return null;
	}

	if ( taxonomy_exists( 'product_brand' ) ) {
		$terms = get_the_terms( $product_id, 'product_brand' );
		if ( $terms && ! is_wp_error( $terms ) ) {
			$term = $terms[0];
			$url  = get_term_link( $term );
			$tid  = absint( get_term_meta( $term->term_id, 'thumbnail_id', true ) );
			$logo = $tid ? wp_get_attachment_image_url( $tid, 'thumbnail' ) : '';
			return array(
				'name' => $term->name,
				'url'  => is_wp_error( $url ) ? '' : $url,
				'logo' => $logo ? $logo : '',
			);
		}
	}

	$product = wc_get_product( $product_id );
	if ( ! $product || ! function_exists( 'irantheme_get_attribute_custom_option_value' ) ) {
		return null;
	}
	foreach ( $product->get_attributes() as $attr ) {
		$type = irantheme_get_attribute_custom_option_value( $attr['name'] );
		if ( 'brand' !== $type ) {
			continue;
		}
		$terms = $attr->get_terms();
		if ( empty( $terms ) || ! is_array( $terms ) ) {
			continue;
		}
		$term = $terms[0];
		$url  = get_term_link( $term );
		return array(
			'name' => $term->name,
			'url'  => ( ! is_wp_error( $url ) ) ? $url : '',
			'logo' => '',
		);
	}
	return null;
}

/**
 * Brand row for the PDP identity card (logo + green name).
 *
 * @param int|null $product_id Product ID.
 */
function storeplus_child_pdp_render_brand( $product_id = null ) {
	$product_id = $product_id ? (int) $product_id : (int) get_the_ID();
	if ( ! $product_id || ! storeplus_child_pdp_show( $product_id, 'brand' ) ) {
		return;
	}
	$brand = storeplus_child_pdp_resolve_brand( $product_id );
	if ( ! $brand ) {
		return;
	}
	echo '<div class="sp-pdp-brand">';
	if ( ! empty( $brand['logo'] ) ) {
		echo '<img class="sp-pdp-brand__logo" src="' . esc_url( $brand['logo'] ) . '" alt="' . esc_attr( $brand['name'] ) . '" width="44" height="44" loading="lazy" />';
	}
	if ( ! empty( $brand['url'] ) ) {
		echo '<a class="sp-pdp-brand__name" href="' . esc_url( $brand['url'] ) . '">' . esc_html( $brand['name'] ) . '</a>';
	} else {
		echo '<span class="sp-pdp-brand__name">' . esc_html( $brand['name'] ) . '</span>';
	}
	echo '</div>';
}

/**
 * Category line under the product title (replaces English subtitle).
 *
 * @param int|null $product_id Product ID.
 */
function storeplus_child_pdp_render_category_line( $product_id = null ) {
	$product_id = $product_id ? (int) $product_id : (int) get_the_ID();
	if ( ! $product_id || ! storeplus_child_pdp_show( $product_id, 'category' ) ) {
		return;
	}
	$raw = get_the_terms( $product_id, 'product_cat' );
	if ( ! $raw || is_wp_error( $raw ) ) {
		return;
	}
	$default = (int) get_option( 'default_product_cat' );
	$parts   = array();
	foreach ( $raw as $term ) {
		if ( $default === (int) $term->term_id ) {
			continue;
		}
		$url = get_term_link( $term );
		if ( ! is_wp_error( $url ) ) {
			$parts[] = '<a class="sp-pdp-cats__link" href="' . esc_url( $url ) . '">' . esc_html( $term->name ) . '</a>';
		} else {
			$parts[] = '<span class="sp-pdp-cats__text">' . esc_html( $term->name ) . '</span>';
		}
		if ( count( $parts ) >= 3 ) {
			break;
		}
	}
	if ( ! $parts ) {
		return;
	}
	echo '<p class="sp-pdp-cats">' . implode( '<span class="sp-pdp-cats__sep" aria-hidden="true">، </span>', $parts ) . '</p>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * English product name under H1 (kept for optional use; identity card uses category instead).
 *
 * @param int|null $product_id Product ID.
 */
function storeplus_child_pdp_render_name_en( $product_id = null ) {
	$product_id = $product_id ? (int) $product_id : (int) get_the_ID();
	if ( ! $product_id || ! storeplus_child_pdp_show( $product_id, 'name_en' ) ) {
		return;
	}
	$name = trim( (string) get_post_meta( $product_id, SP_PDP_NAME_EN_KEY, true ) );
	if ( '' === $name ) {
		return;
	}
	echo '<p class="sp-pdp-name-en" lang="en" dir="ltr">' . esc_html( $name ) . '</p>';
}

/**
 * Compact buy-box signals (expiry / authenticity) under CTA.
 *
 * @param int|null $product_id Product ID.
 */
function storeplus_child_pdp_render_buy_signals( $product_id = null ) {
	$product_id = $product_id ? (int) $product_id : (int) get_the_ID();
	if ( ! $product_id || ! storeplus_child_pdp_show( $product_id, 'buy_signals' ) ) {
		return;
	}
	$h        = storeplus_child_pdp_health_data( $product_id );
	$expiry   = trim( (string) $h['expiry'] );
	$inst_val = storeplus_child_pdp_buy_signal_installments( $product_id );
	$items    = array(
		array( 'label' => 'ضمانت', 'val' => '۷ روز بازگشت کالا' ),
		array( 'label' => 'ارسال', 'val' => 'سریع به سراسر کشور' ),
		array( 'label' => 'انقضا', 'val' => '' !== $expiry ? $expiry : '--' ),
		array( 'label' => 'خرید اقساطی', 'val' => '' !== $inst_val ? $inst_val : '--' ),
	);

	echo '<ul class="sp-pdp-buy-signals">';
	foreach ( $items as $it ) {
		$empty = ( '--' === $it['val'] );
		echo '<li class="sp-pdp-buy-signals__item' . ( $empty ? ' is-empty' : '' ) . '">';
		echo '<span class="sp-pdp-buy-signals__label">' . esc_html( $it['label'] ) . '</span>';
		echo '<span class="sp-pdp-buy-signals__value">' . esc_html( $it['val'] ) . '</span>';
		echo '</li>';
	}
	echo '</ul>';
}

/**
 * Installments line for buy-box signals (under expiry).
 *
 * @param int $product_id Product ID.
 * @return string Empty = hide row.
 */
function storeplus_child_pdp_buy_signal_installments( $product_id ) {
	$product_id = (int) $product_id;
	if ( ! $product_id || ! storeplus_child_pdp_show( $product_id, 'installments' ) ) {
		return '';
	}

	$enabled = function_exists( 'storeplus_child_installments_enabled' )
		&& storeplus_child_installments_enabled( $product_id );

	if ( ! $enabled ) {
		$badges = array_filter(
			array_map(
				'trim',
				explode( ',', (string) get_post_meta( $product_id, 'irantheme_custom_field_product_detail_badge', true ) )
			)
		);
		if ( ! in_array( 'has_installments', $badges, true ) ) {
			return '';
		}
	}

	if ( function_exists( 'storeplus_child_installments_providers' ) ) {
		$providers = storeplus_child_installments_providers( $product_id );
		if ( ! empty( $providers ) ) {
			return implode( '، ', $providers );
		}
	}

	return 'امکان‌پذیر';
}

/**
 * PDP add-to-cart button label.
 *
 * @param string $text Default text.
 * @return string
 */
function storeplus_child_pdp_add_to_cart_text( $text ) {
	if ( function_exists( 'is_product' ) && is_product() ) {
		return 'ثبت در سبد خرید';
	}
	return $text;
}
add_filter( 'woocommerce_product_single_add_to_cart_text', 'storeplus_child_pdp_add_to_cart_text' );

/**
 * Full-width trust strip (GeneSabz mid-page bar).
 *
 * @param int|null $product_id Product ID.
 */
function storeplus_child_pdp_render_trust_bar( $product_id = null ) {
	$product_id = $product_id ? (int) $product_id : (int) get_the_ID();
	if ( $product_id && ! storeplus_child_pdp_show( $product_id, 'trust' ) ) {
		return;
	}
	$items = array(
		array(
			'text' => 'داروخانه مجاز',
			'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 10l9-7 9 7v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-9z"/><path d="M9 21V12h6v9"/></svg>',
		),
		array(
			'text' => 'ارسال سریع',
			'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 7h11v10H3z"/><path d="M14 10h4l3 3v4h-7"/><circle cx="7" cy="17" r="1.5"/><circle cx="17.5" cy="17" r="1.5"/></svg>',
		),
		array(
			'text' => 'ضمانت بازگشت',
			'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 12a9 9 0 1 0 3-6.7"/><path d="M3 4v5h5"/></svg>',
		),
		array(
			'text' => 'اصالت کالا',
			'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 3l7 3v5c0 4.5-2.8 7.8-7 9-4.2-1.2-7-4.5-7-9V6l7-3z"/><path d="M9.5 12l1.8 1.8L15 10"/></svg>',
		),
	);
	echo '<div class="pp-trust-bar" aria-label="' . esc_attr( 'خدمات رستا دارو' ) . '">';
	foreach ( $items as $it ) {
		echo '<div class="pp-trust-bar__item">';
		echo '<span class="pp-trust-bar__icon" aria-hidden="true">' . $it['icon'] . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '<span class="pp-trust-bar__text">' . esc_html( $it['text'] ) . '</span>';
		echo '</div>';
	}
	echo '</div>';
}

/**
 * Mid-page document: admin «درباره محصول» (box always shown; empty state when no text).
 *
 * @param int|null $product_id Product ID.
 */
function storeplus_child_pdp_render_health_panel( $product_id = null ) {
	storeplus_child_pdp_render_document( $product_id );
}

/**
 * Renders the about-product box. Always prints the shell when visibility is on;
 * if senior admin left `_sp_pdp_about` blank, content is an empty state.
 *
 * @param int|null $product_id Product ID.
 */
function storeplus_child_pdp_render_document( $product_id = null ) {
	$product_id = $product_id ? (int) $product_id : (int) get_the_ID();
	if ( ! $product_id || ! storeplus_child_pdp_show( $product_id, 'about_product' ) ) {
		return;
	}

	$health = storeplus_child_pdp_health_data( $product_id );
	$about  = isset( $health['about'] ) ? trim( (string) $health['about'] ) : '';
	$empty  = ( '' === $about );

	/* Visible to shoppers + crawlers (no cloaking / no layout clip). */
	echo '<div class="pp-doc pp-doc--about' . ( $empty ? ' is-empty' : '' ) . '" id="pp-doc">';
	echo '<section class="pp-doc__section" id="pp-sec-about">';
	echo '<h2 class="pp-doc__title">' . esc_html( 'درباره محصول' ) . '</h2>';
	if ( $empty ) {
		echo '<div class="pp-doc__empty" role="status">';
		echo '<p class="pp-doc__empty-title">' . esc_html( 'توضیحی ثبت نشده' ) . '</p>';
		echo '</div>';
	} else {
		echo '<div class="pp-doc__prose">' . nl2br( esc_html( $about ) ) . '</div>';
	}
	echo '</section>';
	echo '</div>';
}

/**
 * Specs card (separate from identity) — every field is always shown.
 * Empty values print as «--».
 *
 * @param int|null $product_id Product ID.
 */
function storeplus_child_pdp_render_health_chips( $product_id = null ) {
	$product_id = $product_id ? (int) $product_id : (int) get_the_ID();
	if ( ! $product_id || ! storeplus_child_pdp_show( $product_id, 'health_chips' ) ) {
		return;
	}
	$h = storeplus_child_pdp_health_data( $product_id );

	$suitable_first = '';
	$yes            = storeplus_child_pdp_lines( $h['suitable'] );
	if ( ! empty( $yes ) ) {
		$suitable_first = $yes[0];
	}

	$map = array(
		array( 'label' => 'وزن / تعداد', 'val' => $h['pack'] ),
		array( 'label' => 'مناسب برای', 'val' => $suitable_first ),
		array( 'label' => 'شکل محصول', 'val' => $h['form'] ),
		array( 'label' => 'کشور سازنده', 'val' => $h['origin'] ),
		array( 'label' => 'رده سنی', 'val' => $h['age'] ),
		array( 'label' => 'جنسیت مصرف', 'val' => $h['gender'] ),
		array( 'label' => 'دوز', 'val' => $h['dose'] ),
		array( 'label' => 'سازنده', 'val' => $h['manufacturer'] ),
		array( 'label' => 'کد مجوز', 'val' => $h['license'] ),
		array( 'label' => 'انقضا', 'val' => $h['expiry'] ),
		array( 'label' => 'شرایط نگهداری', 'val' => $h['storage'] ),
	);

	echo '<div class="sp-pdp-facts" aria-label="' . esc_attr( 'مشخصات محصول' ) . '">';
	echo '<div class="sp-pdp-facts__head"><span class="sp-pdp-facts__title">مشخصات محصول</span></div>';
	echo '<ul class="sp-pdp-facts__list">';
	foreach ( $map as $row ) {
		$val = trim( (string) $row['val'] );
		if ( '' === $val ) {
			$val = '--';
		}
		$empty = ( '--' === $val );
		echo '<li class="sp-pdp-facts__item' . ( $empty ? ' is-empty' : '' ) . '">';
		echo '<span class="sp-pdp-facts__label">' . esc_html( $row['label'] ) . ':</span> ';
		echo '<span class="sp-pdp-facts__value">' . esc_html( $val ) . '</span>';
		echo '</li>';
	}
	echo '</ul></div>';
}

/**
 * Render suitable / not suitable chips.
 *
 * @param int|null $product_id Product ID.
 */
function storeplus_child_pdp_render_suitable( $product_id = null ) {
	$product_id = $product_id ? (int) $product_id : (int) get_the_ID();
	if ( ! $product_id || ! storeplus_child_pdp_show( $product_id, 'suitable' ) ) {
		return;
	}
	$yes = storeplus_child_pdp_lines( get_post_meta( $product_id, SP_PDP_SUITABLE_KEY, true ) );
	$no  = storeplus_child_pdp_lines( get_post_meta( $product_id, SP_PDP_NOT_SUITABLE_KEY, true ) );
	if ( empty( $yes ) && empty( $no ) ) {
		return;
	}
	echo '<div class="sp-pdp-suitable">';
	if ( ! empty( $yes ) ) {
		echo '<div class="sp-pdp-suitable__col sp-pdp-suitable__col--yes">';
		echo '<p class="sp-pdp-suitable__title">مناسب برای</p>';
		echo '<ul class="sp-pdp-suitable__list">';
		foreach ( $yes as $item ) {
			echo '<li>' . esc_html( $item ) . '</li>';
		}
		echo '</ul></div>';
	}
	if ( ! empty( $no ) ) {
		echo '<div class="sp-pdp-suitable__col sp-pdp-suitable__col--no">';
		echo '<p class="sp-pdp-suitable__title">مناسب نیست برای</p>';
		echo '<ul class="sp-pdp-suitable__list">';
		foreach ( $no as $item ) {
			echo '<li>' . esc_html( $item ) . '</li>';
		}
		echo '</ul></div>';
	}
	echo '</div>';
}

/**
 * Cart qty for a product currently in the session cart.
 *
 * @param int $product_id Product or variation ID.
 * @return array{qty:int,key:string}
 */
function storeplus_child_pdp_cart_state( $product_id ) {
	$out        = array( 'qty' => 0, 'key' => '' );
	$product_id = (int) $product_id;
	if ( ! $product_id || ! function_exists( 'WC' ) || ! WC()->cart ) {
		return $out;
	}
	foreach ( WC()->cart->get_cart() as $key => $item ) {
		$pid = isset( $item['product_id'] ) ? (int) $item['product_id'] : 0;
		$vid = isset( $item['variation_id'] ) ? (int) $item['variation_id'] : 0;
		if ( $pid === $product_id || $vid === $product_id ) {
			$out['qty'] += isset( $item['quantity'] ) ? (int) $item['quantity'] : 0;
			if ( '' === $out['key'] ) {
				$out['key'] = (string) $key;
			}
		}
	}
	return $out;
}

/**
 * AJAX: set cart line qty for PDP counter (0 = remove; missing line + qty>0 = add).
 */
function storeplus_child_pdp_ajax_set_qty() {
	$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
	if ( ! $nonce || ! wp_verify_nonce( $nonce, 'sp_pp_cart' ) ) {
		wp_send_json_error( array( 'message' => 'bad_nonce' ), 403 );
	}
	if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
		wp_send_json_error( array( 'message' => 'cart' ), 400 );
	}
	$key          = isset( $_POST['cart_item_key'] ) ? wc_clean( wp_unslash( $_POST['cart_item_key'] ) ) : '';
	$product_id   = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
	$variation_id = isset( $_POST['variation_id'] ) ? absint( $_POST['variation_id'] ) : 0;
	$get_only     = ! empty( $_POST['get'] );

	$variation = array();
	if ( isset( $_POST['variation'] ) && is_array( $_POST['variation'] ) ) {
		foreach ( wp_unslash( $_POST['variation'] ) as $attr => $val ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			$variation[ sanitize_title( $attr ) ] = wc_clean( $val );
		}
	}

	if ( ! $get_only ) {
		$qty = isset( $_POST['quantity'] ) ? (int) $_POST['quantity'] : 0;

		if ( $key && WC()->cart->get_cart_item( $key ) ) {
			if ( $qty <= 0 ) {
				WC()->cart->remove_cart_item( $key );
			} else {
				WC()->cart->set_quantity( $key, $qty, true );
			}
		} elseif ( $product_id ) {
			$state = storeplus_child_pdp_cart_state( $product_id );
			if ( $state['key'] ) {
				if ( $qty <= 0 ) {
					WC()->cart->remove_cart_item( $state['key'] );
				} else {
					WC()->cart->set_quantity( $state['key'], $qty, true );
				}
			} elseif ( $qty > 0 ) {
				$added = WC()->cart->add_to_cart( $product_id, $qty, $variation_id, $variation );
				if ( ! $added ) {
					wp_send_json_error( array( 'message' => 'add_failed' ), 400 );
				}
			}
		}
	}

	$state = storeplus_child_pdp_cart_state( $product_id );
	$data  = array(
		'qty' => $state['qty'],
		'key' => $state['key'],
	);

	ob_start();
	woocommerce_mini_cart();
	$mini              = ob_get_clean();
	$data['fragments'] = apply_filters(
		'woocommerce_add_to_cart_fragments',
		array(
			'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini . '</div>',
		)
	);
	$data['cart_hash'] = WC()->cart->get_cart_hash();

	wp_send_json_success( $data );
}
add_action( 'wc_ajax_pp_set_cart_qty', 'storeplus_child_pdp_ajax_set_qty' );
add_action( 'wc_ajax_nopriv_pp_set_cart_qty', 'storeplus_child_pdp_ajax_set_qty' );
add_action( 'wp_ajax_pp_set_cart_qty', 'storeplus_child_pdp_ajax_set_qty' );
add_action( 'wp_ajax_nopriv_pp_set_cart_qty', 'storeplus_child_pdp_ajax_set_qty' );
