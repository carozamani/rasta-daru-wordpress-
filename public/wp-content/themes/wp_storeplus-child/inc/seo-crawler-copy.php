<?php
/**
 * SEO copy hygiene: keep useful text visible (no cloaking).
 *
 * Product «درباره محصول» is rendered visibly in product-pdp-content.php.
 * Category / brand blurbs stay visible in the UI.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue CSS (empty-about hide + E-E-A-T / footer nav chrome).
 */
function storeplus_child_seo_crawler_assets() {
	if ( is_admin() ) {
		return;
	}
	$css = STOREPLUS_CHILD_DIR . '/assets/css/seo-crawler-copy.css';
	if ( ! file_exists( $css ) ) {
		return;
	}
	wp_enqueue_style(
		'storeplus-child-seo-crawler-copy',
		STOREPLUS_CHILD_URI . '/assets/css/seo-crawler-copy.css',
		array(),
		(string) filemtime( $css )
	);
}
add_action( 'wp_enqueue_scripts', 'storeplus_child_seo_crawler_assets', 45 );

/**
 * Body class for empty-about rules.
 */
add_action(
	'body_class',
	static function ( $classes ) {
		$classes[] = 'sp-seo-crawler-copy';
		return $classes;
	}
);

/**
 * Visible editorial byline on guides (E-E-A-T for YMYL health content).
 *
 * @param string $content Post content.
 * @return string
 */
function storeplus_child_seo_eeat_byline( $content ) {
	if ( is_admin() || ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}
	static $done = false;
	if ( $done ) {
		return $content;
	}
	$done = true;

	$site   = get_bloginfo( 'name', 'display' ) ?: 'رستا دارو';
	$byline = '<aside class="sp-eeat-byline" dir="rtl">'
		. '<p><strong>نویسنده / ناشر:</strong> تحریریه ' . esc_html( $site ) . '</p>'
		. '<p>این مطلب جنبهٔ اطلاع‌رسانی عمومی دارد و جایگزین مشاورهٔ پزشک یا داروساز نیست. قبل از مصرف دارو یا مکمل با متخصص مشورت کنید.</p>'
		. '</aside>';

	return $byline . $content;
}
add_filter( 'the_content', 'storeplus_child_seo_eeat_byline', 8 );

/**
 * Crawlable SEO footer nav links.
 *
 * @return array<int,array{text:string,url:string}>
 */
function storeplus_child_seo_footer_nav_links() {
	$links = array();
	$menu  = wp_get_nav_menu_object( 'منو فوتر سئو' );
	if ( ! $menu ) {
		$opt = get_option( 'sp_seo_menu_ids', array() );
		if ( ! empty( $opt['footer'] ) ) {
			$menu = wp_get_nav_menu_object( (int) $opt['footer'] );
		}
	}
	if ( $menu ) {
		$items = wp_get_nav_menu_items( $menu->term_id );
		if ( $items ) {
			foreach ( $items as $item ) {
				if ( empty( $item->url ) || empty( $item->title ) ) {
					continue;
				}
				$links[] = array(
					'text' => $item->title,
					'url'  => $item->url,
				);
			}
		}
	}
	if ( ! $links ) {
		$fallbacks = array(
			'درباره ما'         => array( 'درباره-ما' ),
			'تماس با ما'        => array( 'تماس-با-ما' ),
			'سوالات متداول'     => array( 'سوالات-متداول' ),
			'راهنمای خرید'      => array( 'rahnama-kharid' ),
			'شرایط بازگشت کالا' => array( 'bazgasht-kala' ),
			'قوانین و مقررات'   => array( 'ghavanin' ),
			'حریم خصوصی'        => array( 'harim-khosusi' ),
		);
		foreach ( $fallbacks as $title => $slugs ) {
			$page = null;
			foreach ( $slugs as $slug ) {
				$page = get_page_by_path( $slug );
				if ( $page ) {
					break;
				}
			}
			if ( $page ) {
				$links[] = array(
					'text' => $title,
					'url'  => get_permalink( $page ),
				);
			}
		}
	}
	return $links;
}

/**
 * Print SEO footer links in HTML source for crawlers + users.
 */
function storeplus_child_seo_footer_nav_html() {
	if ( is_admin() || wp_doing_ajax() || is_feed() ) {
		return;
	}
	$links = storeplus_child_seo_footer_nav_links();
	if ( ! $links ) {
		return;
	}
	echo '<nav class="sp-seo-footer-nav" dir="rtl" aria-label="لینک‌های مهم فروشگاه">';
	echo '<p class="sp-seo-footer-nav__title">لینک‌های مفید</p>';
	echo '<ul class="sp-seo-footer-nav__list">';
	foreach ( $links as $row ) {
		echo '<li><a href="' . esc_url( $row['url'] ) . '">' . esc_html( $row['text'] ) . '</a></li>';
	}
	echo '</ul></nav>';
}
add_action( 'wp_footer', 'storeplus_child_seo_footer_nav_html', 4 );

/**
 * Merge SEO menu into footer JS columns when not already covered.
 *
 * @param array $cols Columns.
 * @return array
 */
function storeplus_child_seo_footer_cols_merge( $cols ) {
	$links = storeplus_child_seo_footer_nav_links();
	if ( ! $links ) {
		return $cols;
	}
	$seen = array();
	foreach ( (array) $cols as $col ) {
		foreach ( (array) ( $col['links'] ?? array() ) as $l ) {
			if ( ! empty( $l['url'] ) ) {
				$seen[ untrailingslashit( $l['url'] ) ] = true;
			}
		}
	}
	$extra = array();
	foreach ( $links as $row ) {
		$key = untrailingslashit( $row['url'] );
		if ( isset( $seen[ $key ] ) ) {
			continue;
		}
		$extra[]        = $row;
		$seen[ $key ] = true;
	}
	if ( ! $extra ) {
		return $cols;
	}
	$cols   = is_array( $cols ) ? $cols : array();
	$cols[] = array(
		'title' => 'صفحات مهم',
		'links' => array_slice( $extra, 0, 8 ),
	);
	return $cols;
}
add_filter( 'storeplus_child_footer_extra_cols', 'storeplus_child_seo_footer_cols_merge', 30 );
