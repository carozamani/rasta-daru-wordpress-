<?php
/**
 * Home banners - quick manager page.
 *
 * One admin screen (بنرهای صفحه اصلی > مدیریت سریع) to upload / replace / reorder / remove the banners
 * of the three home page banner boxes without creating separate posts.
 *
 * - Data is stored in the option "sp_home_banners_manager": array( slot => array( array( image_id, link ), ... ) ).
 * - On the front page the banners of a box are swapped into the page builder's "بنر تصویری" section,
 *   using the same helpers as inc/home-banners.php (so the design stays identical).
 * - Priority: this page > banners created as posts in "همه بنرها" > the page builder's own banners.
 *   A box that is empty here falls through to the next source.
 * - Every banner is shown 3:1 (width : height). This page tells the uploader the ratio and checks the
 *   size of each chosen image (see sp_hb_ratio_status() in inc/home-banners.php).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const SP_HB_MGR_OPTION = 'sp_home_banners_manager';
const SP_HB_MGR_SLUG   = 'sp-home-banners-manager';

/* ---------------------------------------------------------------------------
 * Menu
 * ------------------------------------------------------------------------- */
function sp_hb_mgr_menu() {
	add_submenu_page(
		'edit.php?post_type=home_banner',
		'مدیریت سریع بنرها',
		'مدیریت سریع',
		'manage_options',
		SP_HB_MGR_SLUG,
		'sp_hb_mgr_page'
	);
}
add_action( 'admin_menu', 'sp_hb_mgr_menu' );

function sp_hb_mgr_assets( $hook ) {
	if ( false === strpos( (string) $hook, SP_HB_MGR_SLUG ) ) {
		return;
	}
	wp_enqueue_media();
}
add_action( 'admin_enqueue_scripts', 'sp_hb_mgr_assets' );

/* ---------------------------------------------------------------------------
 * Data
 * ------------------------------------------------------------------------- */
function sp_hb_mgr_slots() {
	return function_exists( 'storeplus_child_home_banners_slots' )
		? storeplus_child_home_banners_slots()
		: array(
			1 => 'باکس اول (زیر پیشنهادهای شگفت‌انگیز)',
			2 => 'باکس دوم (زیر پرفروش‌ترین محصولات)',
			3 => 'باکس سوم (بالای برندهای محبوب)',
		);
}

function sp_hb_mgr_get() {
	$data = get_option( SP_HB_MGR_OPTION, array() );
	return is_array( $data ) ? $data : array();
}

function sp_hb_mgr_save() {
	if ( ! isset( $_POST['sp_hb_mgr_nonce'] ) || ! wp_verify_nonce( $_POST['sp_hb_mgr_nonce'], 'sp_hb_mgr_save' ) ) {
		return false;
	}
	if ( ! current_user_can( 'manage_options' ) ) {
		return false;
	}

	$imgs  = isset( $_POST['sp_hb_img'] ) && is_array( $_POST['sp_hb_img'] ) ? wp_unslash( $_POST['sp_hb_img'] ) : array();
	$links = isset( $_POST['sp_hb_link'] ) && is_array( $_POST['sp_hb_link'] ) ? wp_unslash( $_POST['sp_hb_link'] ) : array();
	$clean = array();

	foreach ( array_keys( sp_hb_mgr_slots() ) as $slot ) {
		$clean[ $slot ] = array();
		$slot_imgs      = isset( $imgs[ $slot ] ) && is_array( $imgs[ $slot ] ) ? $imgs[ $slot ] : array();
		foreach ( $slot_imgs as $i => $image_id ) {
			$image_id = (int) $image_id;
			if ( $image_id <= 0 || ! wp_attachment_is_image( $image_id ) ) {
				continue;
			}
			$link             = isset( $links[ $slot ][ $i ] ) ? esc_url_raw( trim( (string) $links[ $slot ][ $i ] ) ) : '';
			$clean[ $slot ][] = array(
				'image_id' => $image_id,
				'link'     => $link,
			);
		}
	}

	update_option( SP_HB_MGR_OPTION, $clean, false );
	return true;
}

/* ---------------------------------------------------------------------------
 * Admin page
 * ------------------------------------------------------------------------- */
function sp_hb_mgr_row( $slot, $image_id, $link ) {
	$thumb  = $image_id ? wp_get_attachment_image( $image_id, array( 240, 80 ) ) : '';
	$status = ( $image_id && function_exists( 'sp_hb_ratio_status' ) ) ? sp_hb_ratio_status( $image_id ) : '';
	?>
	<div class="sp-hb-row">
		<div class="sp-hb-thumb"><?php echo $thumb; // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
		<div class="sp-hb-fields">
			<input type="hidden" name="sp_hb_img[<?php echo esc_attr( $slot ); ?>][]" value="<?php echo (int) $image_id; ?>" class="sp-hb-img">
			<input type="text" name="sp_hb_link[<?php echo esc_attr( $slot ); ?>][]" value="<?php echo esc_attr( $link ); ?>" placeholder="لینک بنر (اختیاری) https://..." class="sp-hb-link" dir="ltr">
			<div class="sp-hb-ratio"><?php echo $status; // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
			<div class="sp-hb-actions">
				<button type="button" class="button sp-hb-change">تغییر تصویر</button>
				<button type="button" class="button sp-hb-up" title="بالا">▲</button>
				<button type="button" class="button sp-hb-down" title="پایین">▼</button>
				<button type="button" class="button sp-hb-remove">حذف</button>
			</div>
		</div>
	</div>
	<?php
}

function sp_hb_mgr_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$saved = false;
	if ( isset( $_POST['sp_hb_mgr_nonce'] ) ) {
		$saved = sp_hb_mgr_save();
	}

	$slots     = sp_hb_mgr_slots();
	$data      = sp_hb_mgr_get();
	$ratio_txt = defined( 'SP_HB_RATIO_TEXT' ) ? SP_HB_RATIO_TEXT : 'نسبت هر بنر باید ۳ به ۱ باشد، مثلاً ۱۸۰۰×۶۰۰ پیکسل.';
	?>
	<div class="wrap sp-hb-wrap">
		<h1>مدیریت سریع بنرهای صفحه اصلی</h1>
		<?php if ( $saved ) : ?>
			<div class="notice notice-success is-dismissible"><p>بنرها ذخیره شد.</p></div>
		<?php endif; ?>
		<div class="sp-hb-ratio-note"><strong>اندازه بنر:</strong> <?php echo esc_html( $ratio_txt ); ?></div>
		<p>تصویر بنرها را اینجا آپلود کنید. یک بنر در یک باکس تمام عرض می‌شود، دو بنر هرکدام نصف عرض و سه بنر یا بیشتر سه‌تا در هر ردیف؛ در همه‌ی حالت‌ها هر بنر با نسبت ۳ به ۱ نمایش داده می‌شود. بهتر است همه‌ی بنرهای یک باکس هم‌اندازه باشند. اگر یک باکس را خالی بگذارید، بنرهای قبلی (از «همه بنرها» یا صفحه‌ساز) نمایش داده می‌شود.</p>

		<form method="post">
			<?php wp_nonce_field( 'sp_hb_mgr_save', 'sp_hb_mgr_nonce' ); ?>

			<?php foreach ( $slots as $slot => $label ) : ?>
				<div class="sp-hb-box" data-slot="<?php echo (int) $slot; ?>">
					<h2><?php echo esc_html( $label ); ?></h2>
					<div class="sp-hb-list">
						<?php
						$items = isset( $data[ $slot ] ) && is_array( $data[ $slot ] ) ? $data[ $slot ] : array();
						foreach ( $items as $item ) {
							sp_hb_mgr_row( $slot, (int) $item['image_id'], isset( $item['link'] ) ? $item['link'] : '' );
						}
						?>
					</div>
					<button type="button" class="button button-secondary sp-hb-add">+ افزودن بنر (نسبت ۳ به ۱)</button>
				</div>
			<?php endforeach; ?>

			<p><button type="submit" class="button button-primary button-large">ذخیره بنرها</button></p>
		</form>

		<template id="sp-hb-template">
			<?php sp_hb_mgr_row( '__SLOT__', 0, '' ); ?>
		</template>
	</div>

	<style>
		.sp-hb-ratio-note{background:#eefaf3;border:1px solid #b7e1c8;border-radius:8px;color:#0b4a2f;padding:10px 14px;margin:12px 0;max-width:900px;line-height:1.9}
		.sp-hb-box{background:#fff;border:1px solid #dcdcde;border-radius:12px;padding:16px 20px;margin:16px 0;max-width:900px}
		.sp-hb-box h2{margin-top:0}
		.sp-hb-row{display:flex;gap:16px;align-items:center;border:1px solid #e3e3e3;border-radius:10px;padding:10px;margin-bottom:10px;background:#fafafa}
		.sp-hb-thumb{width:240px;height:80px;flex:0 0 240px;background:#eee;border-radius:8px;overflow:hidden;display:flex;align-items:center;justify-content:center}
		.sp-hb-thumb img{width:100%;height:100%;object-fit:cover}
		.sp-hb-fields{flex:1;display:flex;flex-direction:column;gap:8px}
		.sp-hb-link{width:100%;text-align:left}
		.sp-hb-ratio{font-size:12px;line-height:1.8}
		.sp-hb-ratio:empty{display:none}
		.sp-hb-actions{display:flex;gap:6px;flex-wrap:wrap}
	</style>

	<script>
	(function () {
		var tpl = document.getElementById('sp-hb-template');
		var TARGET = 3; // width / height

		function ratioMessage(w, h) {
			if (!w || !h) { return ''; }
			var r = w / h;
			if (Math.abs(r - TARGET) <= 0.06) {
				return '<span style="color:#0b6b45;font-weight:600">✓ اندازه ' + w + '×' + h + ' و نسبت ۳ به ۱ درست است.</span>';
			}
			return '<span style="color:#b32d2e;font-weight:600">⚠ اندازه ' + w + '×' + h + ' است (نسبت ' + r.toFixed(2) + ' به ۱). برای بهترین نتیجه تصویر را با نسبت ۳ به ۱ بارگذاری کنید.</span>';
		}

		function pick(row) {
			var frame = wp.media({ title: 'انتخاب تصویر بنر (نسبت ۳ به ۱، مثلاً ۱۸۰۰×۶۰۰ پیکسل)', button: { text: 'استفاده از این تصویر' }, library: { type: 'image' }, multiple: false });
			frame.on('select', function () {
				var a = frame.state().get('selection').first().toJSON();
				var url = (a.sizes && a.sizes.medium) ? a.sizes.medium.url : a.url;
				row.querySelector('.sp-hb-img').value = a.id;
				row.querySelector('.sp-hb-thumb').innerHTML = '<img src="' + url + '" alt="">';
				var note = row.querySelector('.sp-hb-ratio');
				if (note) { note.innerHTML = ratioMessage(a.width, a.height); }
			});
			frame.open();
		}

		document.addEventListener('click', function (e) {
			var t = e.target;
			var row = t.closest ? t.closest('.sp-hb-row') : null;

			if (t.classList.contains('sp-hb-add')) {
				var box = t.closest('.sp-hb-box');
				var slot = box.getAttribute('data-slot');
				var wrap = document.createElement('div');
				wrap.innerHTML = tpl.innerHTML.replace(/__SLOT__/g, slot).trim();
				var newRow = wrap.firstElementChild;
				box.querySelector('.sp-hb-list').appendChild(newRow);
				pick(newRow);
			} else if (row && t.classList.contains('sp-hb-change')) {
				pick(row);
			} else if (row && t.classList.contains('sp-hb-remove')) {
				row.parentNode.removeChild(row);
			} else if (row && t.classList.contains('sp-hb-up') && row.previousElementSibling) {
				row.parentNode.insertBefore(row, row.previousElementSibling);
			} else if (row && t.classList.contains('sp-hb-down') && row.nextElementSibling) {
				row.parentNode.insertBefore(row.nextElementSibling, row);
			}
		});
	})();
	</script>
	<?php
}

/* ---------------------------------------------------------------------------
 * Front end
 * ------------------------------------------------------------------------- */

/** Banners saved on the manager page, grouped by box (empty boxes left out). */
function sp_hb_mgr_front_groups() {
	static $groups = null;
	if ( null !== $groups ) {
		return $groups;
	}
	$groups = array();

	foreach ( sp_hb_mgr_get() as $slot => $items ) {
		if ( ! is_array( $items ) ) {
			continue;
		}
		foreach ( $items as $i => $item ) {
			$image_id = isset( $item['image_id'] ) ? (int) $item['image_id'] : 0;
			if ( $image_id <= 0 || ! wp_attachment_is_image( $image_id ) ) {
				continue;
			}
			$groups[ (int) $slot ][] = array(
				'id'       => 900000 + ( (int) $slot * 100 ) + (int) $i,
				'image_id' => $image_id,
				'link'     => isset( $item['link'] ) ? $item['link'] : '',
				'title'    => (string) get_post_meta( $image_id, '_wp_attachment_image_alt', true ),
			);
		}
	}

	return $groups;
}

/*
 * Front HTML swap is owned by storeplus_child_home_front_transform() in home-banners.php.
 * That pass merges quick-manager groups (this file) over CPT banners in one buffer — no nested ob.
 */
