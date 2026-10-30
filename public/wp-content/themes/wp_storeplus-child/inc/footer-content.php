<?php
/**
 * Footer content module: admin menu "محتوای فوتر".
 * - quick access links (title + any number of links)
 * - customer guide links (title + any number of links)
 * - contact info (title, intro line, label/value rows)
 * - social icons (preset icon or own image + link)
 *
 * Primary front-end path: inc/footer-php-render.php (reads spfc_front_config + guide filter).
 * Legacy JS (assets/js/footer-content.js) is enqueued only as a safety net when PHP footer is off,
 * or still loaded but no-ops when data-sp-php-footer is present.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const SPFC_OPTION = 'sp_footer_content';

/* ---------- presets ---------- */
function spfc_presets() {
	return array(
		'instagram' => array( 'label' => 'اینستاگرام',  'class' => 'fa-brands fa-instagram' ),
		'telegram'  => array( 'label' => 'تلگرام',      'class' => 'fa-brands fa-telegram' ),
		'whatsapp'  => array( 'label' => 'واتس‌اپ',     'class' => 'fa-brands fa-whatsapp' ),
		'youtube'   => array( 'label' => 'یوتیوب',      'class' => 'fa-brands fa-youtube' ),
		'aparat'    => array( 'label' => 'آپارات',      'class' => '' ),
		'linkedin'  => array( 'label' => 'لینکدین',     'class' => 'fa-brands fa-linkedin-in' ),
		'x'         => array( 'label' => 'ایکس (توییتر)', 'class' => 'fa-brands fa-x-twitter' ),
		'facebook'  => array( 'label' => 'فیسبوک',      'class' => 'fa-brands fa-facebook-f' ),
		'tiktok'    => array( 'label' => 'تیک‌تاک',     'class' => 'fa-brands fa-tiktok' ),
		'pinterest' => array( 'label' => 'پینترست',     'class' => 'fa-brands fa-pinterest-p' ),
		'threads'   => array( 'label' => 'تردز',        'class' => 'fa-brands fa-threads' ),
		'discord'   => array( 'label' => 'دیسکورد',     'class' => 'fa-brands fa-discord' ),
		'github'    => array( 'label' => 'گیت‌هاب',     'class' => 'fa-brands fa-github' ),
		'email'     => array( 'label' => 'ایمیل',       'class' => 'fa-solid fa-envelope' ),
		'phone'     => array( 'label' => 'تلفن',        'class' => 'fa-solid fa-phone' ),
		'website'   => array( 'label' => 'وب‌سایت',     'class' => 'fa-solid fa-globe' ),
		'custom'    => array( 'label' => 'آیکون دلخواه (آپلود تصویر)', 'class' => '' ),
	);
}

function spfc_aparat_svg() {
	return '<svg fill="currentColor" viewBox="0 0 24 24" role="img" xmlns="http://www.w3.org/2000/svg"><path d="M12.001 1.594c-9.27-.003-13.913 11.203-7.36 17.758a10.403 10.403 0 0 0 17.76-7.355c0-5.744-4.655-10.401-10.4-10.403zM6.11 6.783c.501-2.598 3.893-3.294 5.376-1.103 1.483 2.19-.422 5.082-3.02 4.582A2.97 2.97 0 0 1 6.11 6.783zm4.322 8.988c-.504 2.597-3.897 3.288-5.377 1.096-1.48-2.192.427-5.08 3.025-4.579a2.97 2.97 0 0 1 2.352 3.483zm1.26-2.405c-1.152-.223-1.462-1.727-.491-2.387.97-.66 2.256.18 2.04 1.334a1.32 1.32 0 0 1-1.548 1.053zm6.198 3.838c-.501 2.598-3.893 3.293-5.376 1.103-1.484-2.191.421-5.082 3.02-4.583a2.97 2.97 0 0 1 2.356 3.48zm-1.967-5.502c-2.598-.501-3.293-3.896-1.102-5.38 2.19-1.483 5.081.422 4.582 3.02a2.97 2.97 0 0 1-3.48 2.36zM13.59 23.264l2.264.61a3.715 3.715 0 0 0 4.543-2.636l.64-2.402a11.383 11.383 0 0 1-7.448 4.428zm7.643-19.665L18.87 2.97a11.376 11.376 0 0 1 4.354 7.62l.65-2.459A3.715 3.715 0 0 0 21.231 3.6zM.672 13.809l-.541 2.04a3.715 3.715 0 0 0 2.636 4.543l2.107.562a11.38 11.38 0 0 1-4.203-7.145zM10.357.702 8.15.126a3.715 3.715 0 0 0-4.547 2.637l-.551 2.082A11.376 11.376 0 0 1 10.358.702z"/></svg>';
}

/* ---------- defaults (same content the footer shows today) ---------- */
function spfc_page_url( $slug ) {
	return home_url( '/' . rawurlencode( $slug ) . '/' );
}

function spfc_defaults() {
	return array(
		'quick'   => array(
			'title' => 'دسترسی سریع',
			'links' => array(
				array( 'text' => 'وبلاگ',          'url' => spfc_page_url( 'وبلاگ' ) ),
				array( 'text' => 'سوالات متداول',  'url' => spfc_page_url( 'سوالات-متداول' ) ),
				array( 'text' => 'فرصت های شغلی',  'url' => spfc_page_url( 'فرصت-های-شغلی' ) ),
				array( 'text' => 'درباره ما',      'url' => spfc_page_url( 'درباره-ما' ) ),
				array( 'text' => 'تماس با ما',     'url' => spfc_page_url( 'تماس-با-ما' ) ),
			),
		),
		'guide'   => array(
			'title' => 'راهنمای مشتریان',
			'links' => array(
				array( 'text' => 'راهنمای خرید',      'url' => home_url( '/rahnama-kharid/' ) ),
				array( 'text' => 'شرایط بازگشت کالا', 'url' => home_url( '/bazgasht-kala/' ) ),
				array( 'text' => 'قوانین و مقررات',   'url' => home_url( '/ghavanin/' ) ),
				array( 'text' => 'حریم خصوصی',        'url' => home_url( '/harim-khosusi/' ) ),
				array( 'text' => 'پیگیری سفارش',      'url' => function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : home_url( '/my-account/' ) ),
			),
		),
		'contact' => array(
			'title' => 'اطلاعات تماس',
			'intro' => 'هفت روز هفته از ساعت ۸ صبح تا ۱۲ شب پاسخگوی شما عزیزان هستیم',
			'rows'  => array(
				array( 'label' => 'شماره مرکز تماس', 'value' => '03133612345 - 09131234567' ),
				array( 'label' => 'آدرس رایانامه',   'value' => 'info@rastadarou.ir' ),
				array( 'label' => 'آدرس شعبه',       'value' => 'ایران - اصفهان - خیابان مارنان - پل مارنان' ),
			),
		),
		'social'  => array(
			array( 'type' => 'instagram', 'url' => '#', 'image_id' => 0 ),
			array( 'type' => 'telegram',  'url' => '#', 'image_id' => 0 ),
			array( 'type' => 'youtube',   'url' => '#', 'image_id' => 0 ),
			array( 'type' => 'aparat',    'url' => '#', 'image_id' => 0 ),
		),
	);
}

function spfc_get() {
	$d     = spfc_defaults();
	$saved = get_option( SPFC_OPTION, null );
	if ( ! is_array( $saved ) ) {
		return $d;
	}
	foreach ( $d as $key => $val ) {
		if ( ! isset( $saved[ $key ] ) ) {
			$saved[ $key ] = $val;
		}
	}
	return $saved;
}

/* ---------- sanitize ---------- */
function spfc_clean_links( $rows ) {
	$out = array();
	foreach ( (array) $rows as $r ) {
		if ( ! is_array( $r ) ) {
			continue;
		}
		$text = sanitize_text_field( $r['text'] ?? '' );
		if ( '' === $text ) {
			continue;
		}
		$url   = esc_url_raw( trim( $r['url'] ?? '' ) );
		$out[] = array( 'text' => $text, 'url' => $url ? $url : '#' );
	}
	return $out;
}

function spfc_sanitize( $in ) {
	$in      = is_array( $in ) ? $in : array();
	$presets = spfc_presets();

	$contact_rows = array();
	foreach ( (array) ( $in['contact']['rows'] ?? array() ) as $r ) {
		if ( ! is_array( $r ) ) {
			continue;
		}
		$label = sanitize_text_field( $r['label'] ?? '' );
		$value = sanitize_text_field( $r['value'] ?? '' );
		if ( '' === $label && '' === $value ) {
			continue;
		}
		$contact_rows[] = array( 'label' => $label, 'value' => $value );
	}

	$social = array();
	foreach ( (array) ( $in['social'] ?? array() ) as $s ) {
		if ( ! is_array( $s ) ) {
			continue;
		}
		$type = isset( $presets[ $s['type'] ?? '' ] ) ? $s['type'] : 'instagram';
		$url  = esc_url_raw( trim( $s['url'] ?? '' ) );
		$img  = absint( $s['image_id'] ?? 0 );
		if ( '' === $url ) {
			continue;
		}
		if ( 'custom' === $type && ! $img ) {
			continue;
		}
		$social[] = array( 'type' => $type, 'url' => $url, 'image_id' => ( 'custom' === $type ? $img : 0 ) );
	}

	return array(
		'quick'   => array(
			'title' => sanitize_text_field( $in['quick']['title'] ?? '' ),
			'links' => spfc_clean_links( $in['quick']['links'] ?? array() ),
		),
		'guide'   => array(
			'title' => sanitize_text_field( $in['guide']['title'] ?? '' ),
			'links' => spfc_clean_links( $in['guide']['links'] ?? array() ),
		),
		'contact' => array(
			'title' => sanitize_text_field( $in['contact']['title'] ?? '' ),
			'intro' => sanitize_text_field( $in['contact']['intro'] ?? '' ),
			'rows'  => $contact_rows,
		),
		'social'  => $social,
	);
}

/* ---------- admin ---------- */
add_action( 'admin_menu', function () {
	add_menu_page( 'محتوای فوتر', 'محتوای فوتر', 'manage_options', 'sp-footer-content', 'spfc_page', 'dashicons-editor-table', 60 );
} );

add_action( 'admin_init', function () {
	register_setting( 'spfc_group', SPFC_OPTION, array( 'sanitize_callback' => 'spfc_sanitize' ) );
} );

add_action( 'admin_enqueue_scripts', function ( $hook ) {
	if ( 'toplevel_page_sp-footer-content' === $hook ) {
		wp_enqueue_media();
		wp_enqueue_script( 'jquery-ui-sortable' );
	}
} );

function spfc_link_row( $name, $i, $l ) {
	$base = SPFC_OPTION . '[' . $name . '][links][' . $i . ']';
	echo '<div class="spfc-row">';
	echo '<span class="spfc-handle dashicons dashicons-menu" title="جابجایی"></span>';
	echo '<input type="text" name="' . esc_attr( $base ) . '[text]" value="' . esc_attr( $l['text'] ) . '" placeholder="عنوان لینک">';
	echo '<input type="text" dir="ltr" name="' . esc_attr( $base ) . '[url]" value="' . esc_attr( $l['url'] ) . '" placeholder="https://...">';
	echo '<button type="button" class="button-link-delete spfc-del">حذف</button>';
	echo '</div>';
}

function spfc_links_repeater( $name, $links ) {
	echo '<div class="spfc-rep">';
	echo '<div class="spfc-rows">';
	foreach ( $links as $i => $l ) {
		spfc_link_row( $name, $i, $l );
	}
	echo '</div>';
	echo '<button type="button" class="button spfc-add">+ افزودن لینک</button>';
	echo '<script type="text/template" class="spfc-tpl">';
	spfc_link_row( $name, '__i__', array( 'text' => '', 'url' => '' ) );
	echo '</script></div>';
}

function spfc_contact_row( $i, $r ) {
	$base = SPFC_OPTION . '[contact][rows][' . $i . ']';
	echo '<div class="spfc-row">';
	echo '<span class="spfc-handle dashicons dashicons-menu" title="جابجایی"></span>';
	echo '<input type="text" name="' . esc_attr( $base ) . '[label]" value="' . esc_attr( $r['label'] ) . '" placeholder="عنوان (مثلاً شماره تماس)">';
	echo '<input type="text" name="' . esc_attr( $base ) . '[value]" value="' . esc_attr( $r['value'] ) . '" placeholder="مقدار">';
	echo '<button type="button" class="button-link-delete spfc-del">حذف</button>';
	echo '</div>';
}

function spfc_social_row( $i, $s ) {
	$presets = spfc_presets();
	$base    = SPFC_OPTION . '[social][' . $i . ']';
	$img_id  = (int) ( $s['image_id'] ?? 0 );
	$img_url = $img_id ? wp_get_attachment_image_url( $img_id, 'thumbnail' ) : '';
	echo '<div class="spfc-row spfc-social-row">';
	echo '<span class="spfc-handle dashicons dashicons-menu" title="جابجایی"></span>';
	echo '<select class="spfc-type" name="' . esc_attr( $base ) . '[type]">';
	foreach ( $presets as $key => $p ) {
		echo '<option value="' . esc_attr( $key ) . '" ' . selected( $s['type'], $key, false ) . '>' . esc_html( $p['label'] ) . '</option>';
	}
	echo '</select>';
	echo '<input type="text" dir="ltr" name="' . esc_attr( $base ) . '[url]" value="' . esc_attr( $s['url'] ) . '" placeholder="https://instagram.com/...">';
	echo '<span class="spfc-custom">';
	echo '<input type="hidden" class="spfc-img-id" name="' . esc_attr( $base ) . '[image_id]" value="' . esc_attr( $img_id ) . '">';
	echo '<span class="spfc-img-preview">' . ( $img_url ? '<img src="' . esc_url( $img_url ) . '" alt="">' : '' ) . '</span>';
	echo '<button type="button" class="button spfc-pick">انتخاب تصویر</button>';
	echo '</span>';
	echo '<button type="button" class="button-link-delete spfc-del">حذف</button>';
	echo '</div>';
}

function spfc_page() {
	$o = spfc_get();
	?>
	<div class="wrap spfc" dir="rtl">
		<h1>محتوای فوتر</h1>
		<p class="description">لینک‌ها، عنوان ستون‌ها، اطلاعات تماس و شبکه‌های اجتماعی فوتر را از اینجا تغییر بده. ردیف‌ها را با آیکون ≡ جابجا کن.</p>
		<form method="post" action="options.php">
			<?php settings_fields( 'spfc_group' ); ?>

			<div class="spfc-card">
				<h2>لینک‌های دسترسی سریع</h2>
				<p><label>عنوان ستون: <input type="text" class="regular-text" name="<?php echo esc_attr( SPFC_OPTION ); ?>[quick][title]" value="<?php echo esc_attr( $o['quick']['title'] ); ?>"></label></p>
				<?php spfc_links_repeater( 'quick', $o['quick']['links'] ); ?>
			</div>

			<div class="spfc-card">
				<h2>راهنمای مشتریان</h2>
				<p><label>عنوان ستون: <input type="text" class="regular-text" name="<?php echo esc_attr( SPFC_OPTION ); ?>[guide][title]" value="<?php echo esc_attr( $o['guide']['title'] ); ?>"></label></p>
				<?php spfc_links_repeater( 'guide', $o['guide']['links'] ); ?>
			</div>

			<div class="spfc-card">
				<h2>اطلاعات تماس</h2>
				<p><label>عنوان ستون: <input type="text" class="regular-text" name="<?php echo esc_attr( SPFC_OPTION ); ?>[contact][title]" value="<?php echo esc_attr( $o['contact']['title'] ); ?>"></label></p>
				<p><label>متن بالای اطلاعات (پررنگ نمایش داده می‌شود):<br>
					<input type="text" class="large-text" name="<?php echo esc_attr( SPFC_OPTION ); ?>[contact][intro]" value="<?php echo esc_attr( $o['contact']['intro'] ); ?>"></label></p>
				<div class="spfc-rep">
					<div class="spfc-rows">
						<?php foreach ( $o['contact']['rows'] as $i => $r ) { spfc_contact_row( $i, $r ); } ?>
					</div>
					<button type="button" class="button spfc-add">+ افزودن مورد (تلفن، ایمیل، آدرس ...)</button>
					<script type="text/template" class="spfc-tpl"><?php spfc_contact_row( '__i__', array( 'label' => '', 'value' => '' ) ); ?></script>
				</div>
			</div>

			<div class="spfc-card">
				<h2>شبکه‌های اجتماعی</h2>
				<p class="description">آیکون را از لیست انتخاب کن، یا «آیکون دلخواه» را بزن و تصویر خودت را آپلود کن (ایتا، بله، روبیکا و ...).</p>
				<div class="spfc-rep">
					<div class="spfc-rows">
						<?php foreach ( $o['social'] as $i => $s ) { spfc_social_row( $i, $s ); } ?>
					</div>
					<button type="button" class="button spfc-add">+ افزودن شبکه اجتماعی</button>
					<script type="text/template" class="spfc-tpl"><?php spfc_social_row( '__i__', array( 'type' => 'instagram', 'url' => '', 'image_id' => 0 ) ); ?></script>
				</div>
			</div>

			<?php submit_button( 'ذخیره' ); ?>
		</form>
	</div>
	<style>
		.spfc-card{background:#fff;border:1px solid #dcdcde;border-radius:8px;padding:6px 20px 18px;margin:16px 0;max-width:900px}
		.spfc-card h2{margin:12px 0}
		.spfc-row{display:flex;align-items:center;gap:8px;margin-bottom:8px;background:#f6f7f7;border:1px solid #e0e0e0;border-radius:6px;padding:6px 8px}
		.spfc-row input[type=text]{flex:1 1 0;min-width:0}
		.spfc-row select{flex:0 0 190px}
		.spfc-handle{cursor:move;color:#8c8f94}
		.spfc-del{color:#b32d2e!important;text-decoration:none;flex:0 0 auto}
		.spfc-custom{display:none;align-items:center;gap:8px}
		.spfc-social-row.is-custom .spfc-custom{display:inline-flex}
		.spfc-img-preview img{width:28px;height:28px;object-fit:contain;display:block}
		.spfc-rows .ui-sortable-placeholder{visibility:visible!important;height:42px;background:#e8f5ee;border:1px dashed #1f9d63;border-radius:6px;margin-bottom:8px}
	</style>
	<script>
	jQuery(function($){
		var counter = 1000;

		function refreshCustom(scope){
			(scope || $('.spfc-social-row')).each(function(){
				var row = $(this);
				row.toggleClass('is-custom', row.find('.spfc-type').val() === 'custom');
			});
		}

		$('.spfc-rows').sortable({handle:'.spfc-handle', axis:'y', placeholder:'ui-sortable-placeholder'});
		refreshCustom();

		$(document).on('click', '.spfc-add', function(){
			var rep = $(this).closest('.spfc-rep');
			var html = rep.find('script.spfc-tpl').html().replace(/__i__/g, 'n' + (counter++));
			var row = $(html);
			rep.find('.spfc-rows').append(row);
			refreshCustom(row.filter('.spfc-social-row'));
		});

		$(document).on('click', '.spfc-del', function(){
			$(this).closest('.spfc-row').remove();
		});

		$(document).on('change', '.spfc-type', function(){
			refreshCustom($(this).closest('.spfc-social-row'));
		});

		$(document).on('click', '.spfc-pick', function(e){
			e.preventDefault();
			var row = $(this).closest('.spfc-row');
			var frame = wp.media({title:'انتخاب آیکون', multiple:false, library:{type:'image'}});
			frame.on('select', function(){
				var a = frame.state().get('selection').first().toJSON();
				row.find('.spfc-img-id').val(a.id);
				var src = (a.sizes && a.sizes.thumbnail) ? a.sizes.thumbnail.url : a.url;
				row.find('.spfc-img-preview').html('<img src="' + src + '" alt="">');
			});
			frame.open();
		});
	});
	</script>
	<?php
}

/* ---------- front end ---------- */

// Customer guide column — consumed by footer-php-render.php (and JS fallback if PHP is off).
add_filter( 'storeplus_child_footer_extra_cols', function ( $cols ) {
	$o = spfc_get();
	$g = $o['guide'];
	if ( empty( $g['links'] ) ) {
		return array();
	}
	return array( array( 'title' => $g['title'], 'links' => $g['links'] ) );
} );

function spfc_front_config() {
	$o       = spfc_get();
	$presets = spfc_presets();

	$social = array();
	foreach ( $o['social'] as $s ) {
		$type = isset( $presets[ $s['type'] ] ) ? $s['type'] : 'instagram';
		$item = array( 'url' => $s['url'], 'label' => $presets[ $type ]['label'] );
		if ( 'custom' === $type ) {
			$src = wp_get_attachment_image_url( (int) $s['image_id'], 'thumbnail' );
			if ( ! $src ) {
				continue;
			}
			$item['img'] = $src;
		} elseif ( 'aparat' === $type ) {
			$item['svg'] = spfc_aparat_svg();
		} else {
			$item['icon'] = $presets[ $type ]['class'];
		}
		$social[] = $item;
	}

	return array(
		'quick'   => $o['quick'],
		'contact' => $o['contact'],
		'social'  => $social,
	);
}

/**
 * Legacy JS footer filler — safety net only when PHP footer is disabled.
 * Kill-switch: add_filter( 'storeplus_child_footer_php_enabled', '__return_false' ).
 */
add_action( 'wp_enqueue_scripts', function () {
	if ( function_exists( 'storeplus_child_footer_php_enabled' ) && storeplus_child_footer_php_enabled() ) {
		return;
	}
	$rel  = '/assets/js/footer-content.js';
	$path = STOREPLUS_CHILD_DIR . $rel;
	if ( ! file_exists( $path ) ) {
		return;
	}
	$deps = array();
	if ( wp_script_is( 'storeplus-child-js-site', 'registered' ) || wp_script_is( 'storeplus-child-js-site', 'enqueued' ) ) {
		$deps[] = 'storeplus-child-js-site';
	} elseif ( wp_script_is( 'storeplus-child-footer-rearrange', 'registered' ) || wp_script_is( 'storeplus-child-footer-rearrange', 'enqueued' ) ) {
		$deps[] = 'storeplus-child-footer-rearrange';
	}
	wp_enqueue_script( 'storeplus-child-footer-content', STOREPLUS_CHILD_URI . $rel, $deps, filemtime( $path ), true );
	wp_add_inline_script(
		'storeplus-child-footer-content',
		'window.spFooterContent=' . wp_json_encode( spfc_front_config(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP ) . ';',
		'before'
	);
}, 30 );

/**
 * Prevent footer FOUC until layout+content are ready.
 * PHP path sets data-footer-ready + html.sp-footer-ready in the HTML — first paint is visible.
 * Safety timeout still covers the rare JS-fallback path.
 */
add_action( 'wp_head', function () {
	echo "<style id=\"sp-footer-fouc\">"
		. ".irantheme-footer-nav-section:not([data-footer-ready]),"
		. ".irantheme-footer-classic-section:not([data-footer-ready]){"
		. "opacity:0!important}"
		. "html.sp-footer-ready .irantheme-footer-nav-section,"
		. "html.sp-footer-ready .irantheme-footer-classic-section,"
		. ".irantheme-footer-nav-section[data-footer-ready],"
		. ".irantheme-footer-classic-section[data-footer-ready]{"
		. "opacity:1!important;transition:opacity .12s ease}"
		. "</style>\n";
	echo "<script>setTimeout(function(){document.documentElement.classList.add('sp-footer-ready');"
		. "document.querySelectorAll('.irantheme-footer-nav-section,.irantheme-footer-classic-section')"
		. ".forEach(function(el){el.setAttribute('data-footer-ready','1')})},700);</script>\n";
}, 1 );
