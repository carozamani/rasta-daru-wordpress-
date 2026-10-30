<?php
/**
 * Home banners module: manage the banner boxes of the home page from a dedicated admin menu
 * ("بنرهای صفحه اصلی"), just like products / hero slides.
 *
 * How it works
 * - Registers the post type "home_banner". Each banner = title (alt text) + banner image
 *   (featured image) + link + order + "box" (which banner box of the home page shows it).
 * - The home page has three banner boxes (page builder section "بنر تصویری" / business_ibanner):
 *   box 1 under offers, box 2 under best sellers, box 3 above popular brands. Positions are
 *   Slot order on the front page is applied server-side by inc/home-section-order.php
 *   (banner-slots-place.js is a kill-switch safety net only).
 * - On the front page, the banners inside a box are swapped for the banners of this module that
 *   are assigned to that box (see storeplus_child_home_banners_swap). If a box has NO published
 *   banner here, the page builder's own banners are shown as before, so nothing breaks.
 * - Width: 1 banner = full width, 2 banners = half each, 3 or more = three per row (theme default).
 * - Ratio: every banner is shown 3:1 (width : height), see parts/banner-ratio.css (in home.css). The admin
 *   screens tell the uploader the 3:1 ratio and warn when an image has a different one.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Ratio of every home banner (width / height) and the text shown to the admin. */
const SP_HB_RATIO      = 3.0;
const SP_HB_RATIO_TEXT = 'نسبت هر بنر باید ۳ به ۱ باشد (عرض = سه برابر ارتفاع)، مثلاً ۱۸۰۰×۶۰۰ یا ۱۲۰۰×۴۰۰ پیکسل. تصویری با نسبت دیگر از اطراف بریده می‌شود.';

/**
 * Short status line for an image: "ok" when it is 3:1 (small tolerance), otherwise a warning with
 * the real ratio. Returns an empty string when the size is unknown.
 */
function sp_hb_ratio_status( $image_id ) {
	$meta = $image_id ? wp_get_attachment_metadata( (int) $image_id ) : array();
	$w    = isset( $meta['width'] ) ? (int) $meta['width'] : 0;
	$h    = isset( $meta['height'] ) ? (int) $meta['height'] : 0;
	if ( $w <= 0 || $h <= 0 ) {
		return '';
	}
	$ratio = $w / $h;
	if ( abs( $ratio - SP_HB_RATIO ) <= 0.06 ) {
		return '<span style="color:#0b6b45;font-weight:600">✓ اندازه ' . (int) $w . '×' . (int) $h . ' و نسبت ۳ به ۱ درست است.</span>';
	}
	return '<span style="color:#b32d2e;font-weight:600">⚠ اندازه ' . (int) $w . '×' . (int) $h . ' است (نسبت ' . esc_html( number_format_i18n( $ratio, 2 ) ) . ' به ۱). برای بهترین نتیجه تصویر را با نسبت ۳ به ۱ بارگذاری کنید.</span>';
}

/**
 * Loads the 3:1 ratio style of the home banners (after the files listed in functions.php).
 */
function storeplus_child_banner_ratio_assets() {
	if ( function_exists( 'storeplus_child_perf_enabled' ) && storeplus_child_perf_enabled() ) {
		return;
	}
	if ( ! is_front_page() ) {
		return;
	}
	$rel  = '/assets/css/banner-ratio.css';
	$path = STOREPLUS_CHILD_DIR . $rel;
	if ( file_exists( $path ) ) {
		wp_enqueue_style( 'storeplus-child-banner-ratio', STOREPLUS_CHILD_URI . $rel, array( 'irantheme-main-css', 'style' ), filemtime( $path ) );
	}
}
add_action( 'wp_enqueue_scripts', 'storeplus_child_banner_ratio_assets', 30 );

/* ---------------------------------------------------------------------------
 * 1) Post type
 * ------------------------------------------------------------------------- */
function storeplus_child_register_home_banners() {
	register_post_type(
		'home_banner',
		array(
			'labels'              => array(
				'name'                  => 'بنرهای صفحه اصلی',
				'singular_name'         => 'بنر',
				'menu_name'             => 'بنرهای صفحه اصلی',
				'add_new'               => 'افزودن بنر',
				'add_new_item'          => 'افزودن بنر جدید',
				'edit_item'             => 'ویرایش بنر',
				'new_item'              => 'بنر جدید',
				'view_item'             => 'مشاهده بنر',
				'search_items'          => 'جستجوی بنرها',
				'not_found'             => 'هنوز بنری اضافه نشده است.',
				'not_found_in_trash'    => 'بنری در زباله‌دان نیست.',
				'all_items'             => 'همه بنرها',
				'featured_image'        => 'تصویر بنر',
				'set_featured_image'    => 'انتخاب تصویر بنر (نسبت ۳ به ۱)',
				'remove_featured_image' => 'حذف تصویر بنر',
				'use_featured_image'    => 'استفاده به عنوان تصویر بنر',
			),
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_rest'        => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'has_archive'         => false,
			'rewrite'             => false,
			'menu_icon'           => 'dashicons-format-gallery',
			'menu_position'       => 27,
			'supports'            => array( 'title', 'thumbnail', 'page-attributes' ),
		)
	);
}
add_action( 'init', 'storeplus_child_register_home_banners' );

// Makes sure the "banner image" box exists even if the theme does not enable featured images.
function storeplus_child_home_banners_thumbnails_support() {
	add_theme_support( 'post-thumbnails' );
}
add_action( 'after_setup_theme', 'storeplus_child_home_banners_thumbnails_support', 20 );

/* The banner boxes of the home page that can be filled from this module. */
function storeplus_child_home_banners_slots() {
	return array(
		1 => 'باکس اول (زیر پیشنهادهای شگفت‌انگیز)',
		2 => 'باکس دوم (زیر پرفروش‌ترین محصولات)',
		3 => 'باکس سوم (بالای برندهای محبوب)',
	);
}

/* ---------------------------------------------------------------------------
 * 2) Admin: box + link fields and help text
 * ------------------------------------------------------------------------- */

/* Under the "تصویر بنر" box (where the image is uploaded): the 3:1 ratio + a check of the chosen image. */
function storeplus_child_home_banners_thumbnail_hint( $content, $post_id ) {
	if ( 'home_banner' !== get_post_type( $post_id ) ) {
		return $content;
	}
	$status = sp_hb_ratio_status( get_post_thumbnail_id( $post_id ) );
	$hint   = '<p style="margin:10px 0 0;padding:8px 10px;background:#eefaf3;border:1px solid #b7e1c8;border-radius:6px;color:#0b4a2f;line-height:1.9">'
		. '<strong>اندازه بنر:</strong> ' . esc_html( SP_HB_RATIO_TEXT )
		. ( $status ? '<br>' . $status : '' )
		. '</p>';
	return $content . $hint;
}
add_filter( 'admin_post_thumbnail_html', 'storeplus_child_home_banners_thumbnail_hint', 10, 2 );

function storeplus_child_home_banners_meta_box() {
	add_meta_box(
		'storeplus_child_home_banner_link',
		'تنظیمات بنر',
		'storeplus_child_home_banners_meta_box_html',
		'home_banner',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'storeplus_child_home_banners_meta_box' );

function storeplus_child_home_banners_meta_box_html( $post ) {
	wp_nonce_field( 'storeplus_child_home_banner_save', 'storeplus_child_home_banner_nonce' );
	$link = get_post_meta( $post->ID, '_sp_home_banner_link', true );
	$slot = (int) get_post_meta( $post->ID, '_sp_home_banner_slot', true );
	$slots = storeplus_child_home_banners_slots();
	if ( ! isset( $slots[ $slot ] ) ) {
		$slot = 1;
	}
	?>
	<p>
		<strong>محل نمایش</strong><br>
		<?php foreach ( $slots as $key => $label ) : ?>
			<label style="display:block;margin:4px 0">
				<input type="radio" name="sp_home_banner_slot" value="<?php echo (int) $key; ?>" <?php checked( $slot, $key ); ?>>
				<?php echo esc_html( $label ); ?>
			</label>
		<?php endforeach; ?>
	</p>
	<p>
		<label for="sp_home_banner_link"><strong>لینک بنر (اختیاری)</strong></label><br>
		<input type="text" id="sp_home_banner_link" name="sp_home_banner_link" value="<?php echo esc_attr( $link ); ?>" style="width:100%;direction:ltr;text-align:left" placeholder="https://example.com/page">
		<span class="description">با کلیک روی بنر، کاربر به این آدرس می‌رود. خالی بگذارید تا بنر لینک نداشته باشد.</span>
	</p>
	<p>
		<strong>عنوان:</strong> فقط برای مدیریت و متن جایگزین (alt) تصویر استفاده می‌شود و روی بنر نمایش داده نمی‌شود.<br>
		<strong>تصویر:</strong> از کادر «تصویر بنر» (سمت چپ صفحه) تصویر را انتخاب یا آپلود کنید. <strong style="color:#b32d2e">نسبت هر بنر باید ۳ به ۱ باشد</strong> (مثلاً ۱۸۰۰×۶۰۰ پیکسل). بهتر است همه‌ی بنرهای یک باکس هم‌اندازه باشند.<br>
		<strong>چیدمان:</strong> یک بنر در یک باکس تمام عرض را می‌گیرد، دو بنر هرکدام نصف عرض، و سه بنر یا بیشتر سه‌تا‌سه‌تا در هر ردیف نمایش داده می‌شوند. در همه‌ی حالت‌ها هر بنر ۳ به ۱ نمایش داده می‌شود.<br>
		<strong>ترتیب نمایش:</strong> در کادر «ویژگی‌ها» عدد «ترتیب» را بدهید (۰ اول، ۱ دوم، ...).<br>
		<strong>پنهان کردن یک بنر:</strong> آن را پیش‌نویس کنید یا به زباله‌دان بفرستید. اگر برای یک باکس هیچ بنری منتشر نباشد، همان بنرهای قبلی صفحه‌ساز نمایش داده می‌شود.
	</p>
	<?php
}

function storeplus_child_home_banners_save( $post_id ) {
	if ( ! isset( $_POST['storeplus_child_home_banner_nonce'] ) || ! wp_verify_nonce( $_POST['storeplus_child_home_banner_nonce'], 'storeplus_child_home_banner_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$link = isset( $_POST['sp_home_banner_link'] ) ? esc_url_raw( trim( wp_unslash( $_POST['sp_home_banner_link'] ) ) ) : '';
	if ( '' === $link ) {
		delete_post_meta( $post_id, '_sp_home_banner_link' );
	} else {
		update_post_meta( $post_id, '_sp_home_banner_link', $link );
	}

	$slot = isset( $_POST['sp_home_banner_slot'] ) ? (int) $_POST['sp_home_banner_slot'] : 1;
	if ( ! isset( storeplus_child_home_banners_slots()[ $slot ] ) ) {
		$slot = 1;
	}
	update_post_meta( $post_id, '_sp_home_banner_slot', $slot );
	storeplus_child_flush_home_banners_cache();
}
add_action( 'save_post_home_banner', 'storeplus_child_home_banners_save' );

/* Admin list: thumbnail / box / link / order columns, sorted by box then order. */
function storeplus_child_home_banners_columns( $columns ) {
	return array(
		'cb'       => isset( $columns['cb'] ) ? $columns['cb'] : '',
		'sp_thumb' => 'تصویر',
		'title'    => 'عنوان',
		'sp_slot'  => 'محل نمایش',
		'sp_link'  => 'لینک',
		'sp_order' => 'ترتیب',
		'date'     => isset( $columns['date'] ) ? $columns['date'] : 'تاریخ',
	);
}
add_filter( 'manage_home_banner_posts_columns', 'storeplus_child_home_banners_columns' );

function storeplus_child_home_banners_column_content( $column, $post_id ) {
	if ( 'sp_thumb' === $column ) {
		$thumb = get_the_post_thumbnail( $post_id, array( 160, 90 ) );
		echo $thumb ? $thumb : '<span style="color:#b32d2e">بدون تصویر</span>'; // phpcs:ignore WordPress.Security.EscapeOutput
	} elseif ( 'sp_slot' === $column ) {
		$slot  = (int) get_post_meta( $post_id, '_sp_home_banner_slot', true );
		$slots = storeplus_child_home_banners_slots();
		echo esc_html( isset( $slots[ $slot ] ) ? $slots[ $slot ] : $slots[1] );
	} elseif ( 'sp_link' === $column ) {
		$link = get_post_meta( $post_id, '_sp_home_banner_link', true );
		echo $link ? '<span dir="ltr">' . esc_html( $link ) . '</span>' : '—';
	} elseif ( 'sp_order' === $column ) {
		echo (int) get_post_field( 'menu_order', $post_id );
	}
}
add_action( 'manage_home_banner_posts_custom_column', 'storeplus_child_home_banners_column_content', 10, 2 );

function storeplus_child_home_banners_admin_order( $query ) {
	if ( is_admin() && $query->is_main_query() && 'home_banner' === $query->get( 'post_type' ) && ! $query->get( 'orderby' ) ) {
		$query->set( 'orderby', 'menu_order date' );
		$query->set( 'order', 'ASC' );
	}
}
add_action( 'pre_get_posts', 'storeplus_child_home_banners_admin_order' );

/* ---------------------------------------------------------------------------
 * 3) Front end: read the banners
 * ------------------------------------------------------------------------- */

/** Transient key for CPT home banners grouped by slot. */
define( 'STOREPLUS_CHILD_HOME_BANNERS_TRANSIENT', 'storeplus_child_home_banners_v1' );

/**
 * Drop the cached CPT banner groups.
 */
function storeplus_child_flush_home_banners_cache() {
	delete_transient( STOREPLUS_CHILD_HOME_BANNERS_TRANSIENT );
}

/**
 * All published banners that have an image, grouped by box: array( slot => list of banners ).
 */
function storeplus_child_get_home_banners() {
	static $groups = null;
	if ( null !== $groups ) {
		return $groups;
	}

	$cached = get_transient( STOREPLUS_CHILD_HOME_BANNERS_TRANSIENT );
	if ( is_array( $cached ) ) {
		$groups = $cached;
		return $groups;
	}

	$groups = array();
	$slots  = storeplus_child_home_banners_slots();
	$posts  = get_posts(
		array(
			'post_type'        => 'home_banner',
			'post_status'      => 'publish',
			'numberposts'      => 24,
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
			continue; // a banner without an image is skipped
		}
		$slot = (int) get_post_meta( $post->ID, '_sp_home_banner_slot', true );
		if ( ! isset( $slots[ $slot ] ) ) {
			$slot = 1;
		}
		$groups[ $slot ][] = array(
			'id'       => $post->ID,
			'image_id' => $image_id,
			'link'     => get_post_meta( $post->ID, '_sp_home_banner_link', true ),
			'title'    => get_the_title( $post ),
		);
	}

	set_transient( STOREPLUS_CHILD_HOME_BANNERS_TRANSIENT, $groups, WEEK_IN_SECONDS );

	return $groups;
}

/**
 * Banner groups for the front: quick-manager slots win over CPT banners.
 *
 * @return array<int, array<int, array{id:int,image_id:int,link:string,title:string}>>
 */
function storeplus_child_home_banners_merged_groups() {
	static $merged = null;
	if ( null !== $merged ) {
		return $merged;
	}

	$merged = array();
	$cpt    = storeplus_child_get_home_banners();
	$mgr    = function_exists( 'sp_hb_mgr_front_groups' ) ? sp_hb_mgr_front_groups() : array();

	foreach ( array_keys( storeplus_child_home_banners_slots() ) as $slot ) {
		if ( ! empty( $mgr[ $slot ] ) ) {
			$merged[ $slot ] = $mgr[ $slot ];
		} elseif ( ! empty( $cpt[ $slot ] ) ) {
			$merged[ $slot ] = $cpt[ $slot ];
		}
	}

	return $merged;
}

/**
 * Builds the columns of one banner box. It copies the classes of the parent theme's banner items
 * so every existing style / script keeps working.
 *
 * @param array  $banners   Banners of this box.
 * @param string $col_class Bootstrap column classes of the original columns.
 * @param string $item_style Inline style of the original items (holds the border radius).
 */
function storeplus_child_home_banners_html( $banners, $col_class, $item_style ) {
	$count = count( $banners );
	if ( 1 === $count ) {
		$col_class = 'col-12';
	} elseif ( 2 === $count ) {
		$col_class = 'col-lg-6 col-md-6 col-sm-12 col-12';
	}

	$html = '';
	foreach ( $banners as $banner ) {
		$img = wp_get_attachment_image(
			$banner['image_id'],
			'large',
			false,
			array(
				'class'    => 'irantheme-business-ibanner-post-item-image-src',
				'alt'      => $banner['title'],
				'loading'  => 'lazy',
				'decoding' => 'async',
			)
		);
		if ( ! $img ) {
			continue;
		}
		if ( $banner['link'] ) {
			$img = '<a href="' . esc_url( $banner['link'] ) . '" class="irantheme-business-ibanner-post-item-image-link">' . $img . '</a>';
		}

		$html .= '<div class="irantheme-object ' . esc_attr( $col_class ) . '" data-object="sp-home-banner-' . (int) $banner['id'] . '">'
			. '<div class="irantheme-business-ibanner-post-item align-items-start irantheme-set-height"' . ( '' !== $item_style ? ' style="' . esc_attr( $item_style ) . '"' : '' ) . '>'
			. '<div class="irantheme-business-ibanner-post-item-image">' . $img . '</div>'
			. '</div>'
			. '</div>';
	}

	return $html;
}

/**
 * Replaces the content of one banner box's row (the element with the class
 * irantheme-business-ibanner-post-container) that lies inside the section starting at $sec_pos.
 *
 * @return array|null array( 'html' => new page html, 'end' => offset after the new content ) or null.
 */
function storeplus_child_home_banners_swap_box( $html, $sec_pos, $banners ) {
	$sec_end = strpos( $html, '</section>', $sec_pos );
	$pos     = strpos( $html, 'irantheme-business-ibanner-post-container', $sec_pos );
	if ( false === $sec_end || false === $pos || $pos > $sec_end ) {
		return null;
	}

	// The row element: find its tag name and the end of its opening tag.
	$tag_start = strrpos( substr( $html, 0, $pos ), '<' );
	if ( false === $tag_start || ! preg_match( '#\G<([a-z0-9]+)#i', $html, $tm, 0, $tag_start ) ) {
		return null;
	}
	$tag = $tm[1];
	$gt  = strpos( $html, '>', $pos );
	if ( false === $gt ) {
		return null;
	}
	$inner_start = $gt + 1;

	// Find its closing tag (count nested tags of the same name).
	$depth     = 1;
	$cursor    = $inner_start;
	$inner_end = false;
	while ( $depth > 0 && preg_match( '#<(/?)' . preg_quote( $tag, '#' ) . '\b#i', $html, $m, PREG_OFFSET_CAPTURE, $cursor ) ) {
		$depth += ( '/' === $m[1][0] ) ? -1 : 1;
		if ( 0 === $depth ) {
			$inner_end = $m[0][1];
		}
		$cursor = $m[0][1] + 1;
	}
	if ( false === $inner_end || $inner_end > $sec_end ) {
		return null;
	}

	// Reuse the original column classes and the item's border radius.
	$inner     = substr( $html, $inner_start, $inner_end - $inner_start );
	$col_class = 'col-xxl-4 col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12';
	if ( preg_match( '#<div class="irantheme-object ([^"]*)"#i', $inner, $cm ) && trim( $cm[1] ) !== '' ) {
		$col_class = trim( $cm[1] );
	}
	$item_style = 'border-radius:1rem;';
	if ( preg_match( '#irantheme-business-ibanner-post-item[^"]*"\s+style="([^"]*)"#i', $inner, $sm ) && preg_match( '#border-radius:[^;"]+;?#i', $sm[1], $rm ) ) {
		$item_style = $rm[0];
	}

	$new = storeplus_child_home_banners_html( $banners, $col_class, $item_style );
	if ( '' === $new ) {
		return null;
	}

	return array(
		'html' => substr( $html, 0, $inner_start ) . $new . substr( $html, $inner_end ),
		'end'  => $inner_start + strlen( $new ),
	);
}

/**
 * Empty shell for a missing banner box (filled later by swap, or hidden by hide-empty).
 *
 * @param int $slot Slot number (1–3).
 */
function storeplus_child_home_banners_shell_html( $slot = 3 ) {
	$slot = (int) $slot;
	return '<section class="irantheme-section irantheme-business-ibanner-section" data-sp-hb-slot="' . $slot . '">'
		. '<div class="irantheme-business irantheme-business-ibanner white irantheme-background-jarallax" style="background-color:transparent;padding-top:2rem;padding-bottom:2rem;padding-left:0rem;padding-right:0rem;">'
		. '<div class="irantheme-container-inner">'
		. '<div class="irantheme-business-ibanner-post-wrapper " style="">'
		. '<div class="irantheme-object-group irantheme-business-ibanner-post-container row justify-content-center" data-json="objects_json"></div>'
		. '</div></div></div></section>';
}

/**
 * Ensure three banner sections exist and tag them with data-sp-hb-slot="1|2|3".
 * Extra shells are inserted just before the popular brands section (or before the blog / footer).
 *
 * @param string $html Page HTML.
 * @return string
 */
function storeplus_child_home_banners_ensure_slots( $html ) {
	$needed = count( storeplus_child_home_banners_slots() );
	$count  = preg_match_all( '#<section[^>]*irantheme-business-ibanner-section#i', $html );
	$count  = is_int( $count ) ? $count : 0;

	while ( $count < $needed ) {
		$shell = storeplus_child_home_banners_shell_html( $count + 1 );
		$anchors = array(
			'irantheme-woocommerce-box-brands-section',
			'irantheme-post-simple-section',
			'irantheme-footer',
		);
		$inserted = false;
		foreach ( $anchors as $cls ) {
			if ( preg_match( '#<section[^>]*' . preg_quote( $cls, '#' ) . '#i', $html, $m, PREG_OFFSET_CAPTURE ) ) {
				$pos      = $m[0][1];
				$html     = substr( $html, 0, $pos ) . $shell . substr( $html, $pos );
				$inserted = true;
				break;
			}
		}
		if ( ! $inserted ) {
			/* Never append after </body>/</html> — that dumps banners under the footer. */
			if ( preg_match( '/<footer\b[^>]*\birantheme-footer-(?:nav|classic)-section\b/i', $html, $fm, PREG_OFFSET_CAPTURE ) ) {
				$html     = substr( $html, 0, $fm[0][1] ) . $shell . substr( $html, $fm[0][1] );
				$inserted = true;
			} elseif ( preg_match( '/<\/body>/i', $html, $bm, PREG_OFFSET_CAPTURE ) ) {
				$html     = substr( $html, 0, $bm[0][1] ) . $shell . substr( $html, $bm[0][1] );
				$inserted = true;
			}
		}
		if ( ! $inserted ) {
			continue;
		}
		$count++;
	}

	// Tag each banner section with its slot index (1-based, in document order).
	$slot = 0;
	$html = preg_replace_callback(
		'#<section([^>]*irantheme-business-ibanner-section[^>]*)>#i',
		function ( $m ) use ( &$slot ) {
			$slot++;
			$attrs = $m[1];
			if ( preg_match( '#\bdata-sp-hb-slot\s*=#i', $attrs ) ) {
				$attrs = preg_replace( '#\bdata-sp-hb-slot\s*=\s*["\']?\d+["\']?#i', 'data-sp-hb-slot="' . $slot . '"', $attrs );
			} else {
				$attrs .= ' data-sp-hb-slot="' . $slot . '"';
			}
			return '<section' . $attrs . '>';
		},
		$html
	);

	return $html;
}

/**
 * Fill banner boxes from merged sources (manager wins per slot over CPT).
 * Any problem -> that box (or the whole page) is returned untouched.
 *
 * @param string $html Page HTML.
 * @return string
 */
function storeplus_child_home_banners_swap( $html ) {
	$html   = storeplus_child_home_banners_ensure_slots( $html );
	$groups = storeplus_child_home_banners_merged_groups();
	$offset = 0;
	$slot   = 0;

	while ( $slot < 10 && preg_match( '#<section[^>]*irantheme-business-ibanner-section#i', $html, $m, PREG_OFFSET_CAPTURE, $offset ) ) {
		$slot++;
		$sec_pos = $m[0][1];
		$offset  = $sec_pos + 10; // move past this section's opening tag

		if ( empty( $groups[ $slot ] ) ) {
			continue; // nothing for this box -> keep the page builder's banners
		}
		$res = storeplus_child_home_banners_swap_box( $html, $sec_pos, $groups[ $slot ] );
		if ( null !== $res ) {
			$html   = $res['html'];
			$offset = $res['end'];
		}
	}

	return $html;
}

/**
 * Single front-page HTML pass: hero slides + banner slots (replaces nested buffers).
 *
 * @param string $html Page HTML.
 * @return string
 */
function storeplus_child_home_front_transform( $html ) {
	if ( ! is_string( $html ) || '' === $html ) {
		return $html;
	}
	$original = $html;
	try {
		if ( function_exists( 'storeplus_child_hero_swap' ) ) {
			$next = storeplus_child_hero_swap( $html );
			if ( is_string( $next ) && '' !== $next ) {
				$html = $next;
			}
		}
		$next = storeplus_child_home_banners_swap( $html );
		if ( is_string( $next ) && '' !== $next ) {
			$html = $next;
		}
		/**
		 * Last chance to mutate front-page HTML (post-card strip, etc.).
		 *
		 * @param string $html Document HTML after hero/banner swaps.
		 */
		$html = apply_filters( 'storeplus_child_home_front_html', $html );
	} catch ( Throwable $e ) {
		return $original;
	}
	/* Never let a transform wipe the document — that is the white landing page. */
	if ( '' === $html || false === stripos( $html, '<body' ) ) {
		return $original;
	}
	return $html;
}

/**
 * One output buffer on the front page (hero + banners). No nested HTML copies.
 */
function storeplus_child_home_front_start_buffer() {
	if ( is_admin() || is_feed() || is_embed() || is_customize_preview() || wp_doing_ajax() ) {
		return;
	}
	if ( ! is_front_page() ) {
		return;
	}
	ob_start( 'storeplus_child_home_front_transform' );
}
add_action( 'template_redirect', 'storeplus_child_home_front_start_buffer', 0 );

/**
 * Flush CPT banner cache when a banner is trashed/deleted or its thumbnail changes.
 *
 * @param int $post_id Post ID.
 */
function storeplus_child_flush_home_banners_cache_on_post( $post_id ) {
	if ( 'home_banner' === get_post_type( $post_id ) ) {
		storeplus_child_flush_home_banners_cache();
	}
}
add_action( 'trashed_post', 'storeplus_child_flush_home_banners_cache_on_post' );
add_action( 'before_delete_post', 'storeplus_child_flush_home_banners_cache_on_post' );
add_action( 'untrashed_post', 'storeplus_child_flush_home_banners_cache_on_post' );

/**
 * @param int    $meta_id    Meta ID.
 * @param int    $post_id    Post ID.
 * @param string $meta_key   Meta key.
 */
function storeplus_child_flush_home_banners_cache_on_thumb( $meta_id, $post_id, $meta_key ) {
	if ( '_thumbnail_id' === $meta_key && 'home_banner' === get_post_type( $post_id ) ) {
		storeplus_child_flush_home_banners_cache();
	}
}
add_action( 'added_post_meta', 'storeplus_child_flush_home_banners_cache_on_thumb', 10, 3 );
add_action( 'updated_post_meta', 'storeplus_child_flush_home_banners_cache_on_thumb', 10, 3 );
add_action( 'deleted_post_meta', 'storeplus_child_flush_home_banners_cache_on_thumb', 10, 3 );
