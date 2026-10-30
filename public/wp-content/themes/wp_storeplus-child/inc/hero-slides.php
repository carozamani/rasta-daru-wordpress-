<?php
/**
 * Hero slider module: manage the home page slider images from a dedicated admin menu
 * ("اسلایدر صفحه اصلی"), just like products.
 *
 * How it works
 * - Registers the post type "hero_slide". Each slide = title + slide image (featured image) + link + order.
 * - The parent theme still renders the slider (section "اسلایدر ساده" of the page builder), so the
 *   design, Swiper, autoplay, 3:1 ratio and round corners stay exactly the same.
 * - On the front page, the slides of the page builder are swapped for the slides of this module
 *   (see storeplus_child_hero_swap). If this module has NO published slide, the page builder's own
 *   slides are shown as before, so nothing breaks.
 *
 * Files used by the look: assets/css/hero-ratio.css, assets/js/hero-slider-autoplay.js,
 * assets/css/slider-corners.css, assets/js/slider-corners.js (unchanged).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ---------------------------------------------------------------------------
 * 1) Post type
 * ------------------------------------------------------------------------- */
function storeplus_child_register_hero_slides() {
	register_post_type(
		'hero_slide',
		array(
			'labels'              => array(
				'name'                  => 'اسلایدر صفحه اصلی',
				'singular_name'         => 'اسلاید',
				'menu_name'             => 'اسلایدر صفحه اصلی',
				'add_new'               => 'افزودن اسلاید',
				'add_new_item'          => 'افزودن اسلاید جدید',
				'edit_item'             => 'ویرایش اسلاید',
				'new_item'              => 'اسلاید جدید',
				'view_item'             => 'مشاهده اسلاید',
				'search_items'          => 'جستجوی اسلایدها',
				'not_found'             => 'هنوز اسلایدی اضافه نشده است.',
				'not_found_in_trash'    => 'اسلایدی در زباله‌دان نیست.',
				'all_items'             => 'همه اسلایدها',
				'featured_image'        => 'تصویر اسلاید',
				'set_featured_image'    => 'انتخاب تصویر اسلاید',
				'remove_featured_image' => 'حذف تصویر اسلاید',
				'use_featured_image'    => 'استفاده به عنوان تصویر اسلاید',
			),
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_rest'        => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'has_archive'         => false,
			'rewrite'             => false,
			'menu_icon'           => 'dashicons-images-alt2',
			'menu_position'       => 26,
			'supports'            => array( 'title', 'thumbnail', 'page-attributes' ),
		)
	);
}
add_action( 'init', 'storeplus_child_register_hero_slides' );

// Makes sure the "slide image" box exists even if the theme does not enable featured images.
function storeplus_child_hero_thumbnails_support() {
	add_theme_support( 'post-thumbnails' );
}
add_action( 'after_setup_theme', 'storeplus_child_hero_thumbnails_support', 20 );

/* ---------------------------------------------------------------------------
 * 2) Admin: link field + help text
 * ------------------------------------------------------------------------- */
function storeplus_child_hero_meta_box() {
	add_meta_box(
		'storeplus_child_hero_link',
		'تنظیمات اسلاید',
		'storeplus_child_hero_meta_box_html',
		'hero_slide',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'storeplus_child_hero_meta_box' );

function storeplus_child_hero_meta_box_html( $post ) {
	wp_nonce_field( 'storeplus_child_hero_save', 'storeplus_child_hero_nonce' );
	$link = get_post_meta( $post->ID, '_sp_hero_link', true );
	?>
	<p>
		<label for="sp_hero_link"><strong>لینک اسلاید (اختیاری)</strong></label><br>
		<input type="text" id="sp_hero_link" name="sp_hero_link" value="<?php echo esc_attr( $link ); ?>" style="width:100%;direction:ltr;text-align:left" placeholder="https://example.com/page">
		<span class="description">با کلیک روی تصویر اسلاید، کاربر به این آدرس می‌رود. خالی بگذارید تا تصویر لینک نداشته باشد.</span>
	</p>
	<p>
		<strong>تصویر:</strong> از کادر «تصویر اسلاید» (سمت چپ صفحه) تصویر را انتخاب یا آپلود کنید.<br>
		<strong>اندازه پیشنهادی:</strong> نسبت ۳ به ۱، مثلاً ۱۹۲۰×۶۴۰ پیکسل (اسلایدر روی همین نسبت تنظیم شده و تصاویر دیگر بریده می‌شوند).<br>
		<strong>ترتیب نمایش:</strong> در کادر «ویژگی‌های برگه» عدد «ترتیب» را بدهید (۰ اول، ۱ دوم، ...).<br>
		<strong>پنهان کردن یک اسلاید:</strong> آن را پیش‌نویس کنید یا به زباله‌دان بفرستید.
	</p>
	<?php
}

function storeplus_child_hero_save( $post_id ) {
	if ( ! isset( $_POST['storeplus_child_hero_nonce'] ) || ! wp_verify_nonce( $_POST['storeplus_child_hero_nonce'], 'storeplus_child_hero_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$link = isset( $_POST['sp_hero_link'] ) ? esc_url_raw( trim( wp_unslash( $_POST['sp_hero_link'] ) ) ) : '';
	if ( '' === $link ) {
		delete_post_meta( $post_id, '_sp_hero_link' );
	} else {
		update_post_meta( $post_id, '_sp_hero_link', $link );
	}
	storeplus_child_flush_hero_slides_cache();
}
add_action( 'save_post_hero_slide', 'storeplus_child_hero_save' );

/* Admin list: thumbnail / link / order columns, sorted by order. */
function storeplus_child_hero_columns( $columns ) {
	return array(
		'cb'       => isset( $columns['cb'] ) ? $columns['cb'] : '',
		'sp_thumb' => 'تصویر',
		'title'    => 'عنوان',
		'sp_link'  => 'لینک',
		'sp_order' => 'ترتیب',
		'date'     => isset( $columns['date'] ) ? $columns['date'] : 'تاریخ',
	);
}
add_filter( 'manage_hero_slide_posts_columns', 'storeplus_child_hero_columns' );

function storeplus_child_hero_column_content( $column, $post_id ) {
	if ( 'sp_thumb' === $column ) {
		$thumb = get_the_post_thumbnail( $post_id, array( 180, 60 ) );
		echo $thumb ? $thumb : '<span style="color:#b32d2e">بدون تصویر</span>'; // phpcs:ignore WordPress.Security.EscapeOutput
	} elseif ( 'sp_link' === $column ) {
		$link = get_post_meta( $post_id, '_sp_hero_link', true );
		echo $link ? '<span dir="ltr">' . esc_html( $link ) . '</span>' : '—';
	} elseif ( 'sp_order' === $column ) {
		echo (int) get_post_field( 'menu_order', $post_id );
	}
}
add_action( 'manage_hero_slide_posts_custom_column', 'storeplus_child_hero_column_content', 10, 2 );

function storeplus_child_hero_admin_order( $query ) {
	if ( is_admin() && $query->is_main_query() && 'hero_slide' === $query->get( 'post_type' ) && ! $query->get( 'orderby' ) ) {
		$query->set( 'orderby', 'menu_order date' );
		$query->set( 'order', 'ASC' );
	}
}
add_action( 'pre_get_posts', 'storeplus_child_hero_admin_order' );

/* ---------------------------------------------------------------------------
 * 3) Front end: read the slides
 * ------------------------------------------------------------------------- */

/** Transient key for hero slide payload. */
define( 'STOREPLUS_CHILD_HERO_SLIDES_TRANSIENT', 'storeplus_child_hero_slides_v1' );

/**
 * Drop the cached hero slides list.
 */
function storeplus_child_flush_hero_slides_cache() {
	delete_transient( STOREPLUS_CHILD_HERO_SLIDES_TRANSIENT );
}

function storeplus_child_get_hero_slides() {
	static $slides = null;
	if ( null !== $slides ) {
		return $slides;
	}

	$cached = get_transient( STOREPLUS_CHILD_HERO_SLIDES_TRANSIENT );
	if ( is_array( $cached ) ) {
		$slides = $cached;
		return $slides;
	}

	$slides = array();
	$posts  = get_posts(
		array(
			'post_type'        => 'hero_slide',
			'post_status'      => 'publish',
			'numberposts'      => 12,
			'orderby'          => array(
				'menu_order' => 'ASC',
				'date'       => 'DESC',
			),
			'no_found_rows'    => true,
			'suppress_filters' => true,
		)
	);

	foreach ( $posts as $post ) {
		$image_id = get_post_thumbnail_id( $post->ID );
		if ( ! $image_id ) {
			continue; // a slide without an image is skipped
		}
		$slides[] = array(
			'image_id' => $image_id,
			'link'     => get_post_meta( $post->ID, '_sp_hero_link', true ),
			'title'    => get_the_title( $post ),
		);
	}

	set_transient( STOREPLUS_CHILD_HERO_SLIDES_TRANSIENT, $slides, WEEK_IN_SECONDS );

	return $slides;
}

/**
 * Builds the slide markup. It copies the classes of the parent theme's slides so every
 * existing style / script (3:1 ratio, round corners, dots, autoplay) keeps working.
 *
 * @param string $slide_style The inline style of the original first slide (holds the corner radius).
 */
function storeplus_child_hero_slides_html( $slide_style ) {
	$html = '';

	foreach ( storeplus_child_get_hero_slides() as $n => $slide ) {
		$img = wp_get_attachment_image(
			$slide['image_id'],
			'large',
			false,
			array(
				'class'         => 'irantheme-swiper-section-slider-simple-image-img',
				'alt'           => $slide['title'],
				'loading'       => 0 === $n ? 'eager' : 'lazy',
				'decoding'      => 0 === $n ? 'sync' : 'async',
				'fetchpriority' => 0 === $n ? 'high' : 'low',
			)
		);
		if ( ! $img ) {
			continue;
		}
		if ( $slide['link'] ) {
			$img = '<a href="' . esc_url( $slide['link'] ) . '" class="irantheme-swiper-section-slider-simple-image-link">' . $img . '</a>';
		}

		$html .= '<div class="irantheme-object irantheme-showcase-simple white swiper-slide"' . ( '' !== $slide_style ? ' style="' . $slide_style . '"' : '' ) . '>'
			. '<div class="irantheme-swiper-section-slider-simple-image">' . $img . '</div>'
			. '</div>';
	}

	return $html;
}

/**
 * Replace the slider's .swiper-wrapper content with this module's slides.
 * Called from the single home front buffer (storeplus_child_home_front_transform).
 * Any problem -> the original page is returned untouched.
 *
 * @param string $html Page HTML.
 * @return string
 */
function storeplus_child_hero_swap( $html ) {
	if ( ! storeplus_child_get_hero_slides() ) {
		return $html;
	}

	$marker = 'irantheme-object-group swiper-wrapper irantheme-showcase-simple-section';
	$pos    = strpos( $html, $marker );
	if ( false === $pos ) {
		return $html;
	}

	$gt = strpos( $html, '>', $pos );
	if ( false === $gt ) {
		return $html;
	}
	$inner_start = $gt + 1;

	// Find the </div> that closes the wrapper (count nested divs).
	$depth     = 1;
	$cursor    = $inner_start;
	$inner_end = false;
	while ( $depth > 0 && preg_match( '#<(/?)div\b#i', $html, $m, PREG_OFFSET_CAPTURE, $cursor ) ) {
		$depth += ( '/' === $m[1][0] ) ? -1 : 1;
		if ( 0 === $depth ) {
			$inner_end = $m[0][1];
		}
		$cursor = $m[0][1] + 1;
	}
	if ( false === $inner_end ) {
		return $html;
	}

	$inner = substr( $html, $inner_start, $inner_end - $inner_start );

	// Keep the original slide's inline style (it carries the border radius).
	$style = '';
	if ( preg_match( '#<div[^>]*swiper-slide[^>]*\sstyle="([^"]*)"#i', $inner, $sm ) ) {
		$style = $sm[1];
	}

	$new = storeplus_child_hero_slides_html( $style );
	if ( '' === $new ) {
		return $html;
	}

	return substr( $html, 0, $inner_start ) . $new . substr( $html, $inner_end );
}

/**
 * Flush hero cache when a slide is trashed/deleted or its thumbnail changes.
 *
 * @param int $post_id Post ID.
 */
function storeplus_child_flush_hero_slides_cache_on_post( $post_id ) {
	if ( 'hero_slide' === get_post_type( $post_id ) ) {
		storeplus_child_flush_hero_slides_cache();
	}
}
add_action( 'trashed_post', 'storeplus_child_flush_hero_slides_cache_on_post' );
add_action( 'before_delete_post', 'storeplus_child_flush_hero_slides_cache_on_post' );
add_action( 'untrashed_post', 'storeplus_child_flush_hero_slides_cache_on_post' );

/**
 * @param int    $meta_id  Meta ID.
 * @param int    $post_id  Post ID.
 * @param string $meta_key Meta key.
 */
function storeplus_child_flush_hero_slides_cache_on_thumb( $meta_id, $post_id, $meta_key ) {
	if ( '_thumbnail_id' === $meta_key && 'hero_slide' === get_post_type( $post_id ) ) {
		storeplus_child_flush_hero_slides_cache();
	}
}
add_action( 'added_post_meta', 'storeplus_child_flush_hero_slides_cache_on_thumb', 10, 3 );
add_action( 'updated_post_meta', 'storeplus_child_flush_hero_slides_cache_on_thumb', 10, 3 );
add_action( 'deleted_post_meta', 'storeplus_child_flush_hero_slides_cache_on_thumb', 10, 3 );
