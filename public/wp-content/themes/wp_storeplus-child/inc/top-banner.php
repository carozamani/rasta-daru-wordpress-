<?php
/**
 * Top banner (thin banner above the header).
 * Admin menu: "بنر بالای هدر". Image (desktop + optional mobile) OR text on a brand-green gradient.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ---------- defaults ---------- */
function wdc_tb_defaults() {
	return array(
		'enabled'         => 1,
		'image_id'        => 0,
		'image_mobile_id' => 0,
		'link'            => '',
		'text'            => '',
		'text_color'      => '#ffffff',
		'color1'          => '#0b6b45', // brand green (dark)
		'color2'          => '#2fb374', // brand green (light)
		'height'          => 40,
		'height_mobile'   => 36,
		'closable'        => 0,
	);
}

function wdc_tb_get() {
	return wp_parse_args( get_option( 'wdc_top_banner', array() ), wdc_tb_defaults() );
}

/* ---------- admin menu ---------- */
add_action( 'admin_menu', function () {
	add_menu_page( 'بنر بالای هدر', 'بنر بالای هدر', 'manage_options', 'wdc-top-banner', 'wdc_tb_page', 'dashicons-megaphone', 59 );
} );

add_action( 'admin_init', function () {
	register_setting( 'wdc_tb_group', 'wdc_top_banner', array( 'sanitize_callback' => 'wdc_tb_sanitize' ) );
} );

function wdc_tb_sanitize( $in ) {
	$d = wdc_tb_defaults();
	return array(
		'enabled'         => empty( $in['enabled'] ) ? 0 : 1,
		'image_id'        => absint( $in['image_id'] ?? 0 ),
		'image_mobile_id' => absint( $in['image_mobile_id'] ?? 0 ),
		'link'            => esc_url_raw( $in['link'] ?? '' ),
		'text'            => sanitize_text_field( $in['text'] ?? '' ),
		'text_color'      => sanitize_hex_color( $in['text_color'] ?? '' ) ?: $d['text_color'],
		'color1'          => sanitize_hex_color( $in['color1'] ?? '' ) ?: $d['color1'],
		'color2'          => sanitize_hex_color( $in['color2'] ?? '' ) ?: $d['color2'],
		'height'          => max( 24, min( 120, absint( $in['height'] ?? 40 ) ) ),
		'height_mobile'   => max( 24, min( 120, absint( $in['height_mobile'] ?? 36 ) ) ),
		'closable'        => empty( $in['closable'] ) ? 0 : 1,
	);
}

add_action( 'admin_enqueue_scripts', function ( $hook ) {
	if ( 'toplevel_page_wdc-top-banner' === $hook ) {
		wp_enqueue_media();
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
	}
} );

/* ---------- admin page ---------- */
function wdc_tb_media_field( $key, $id, $hint = '', $size = '' ) {
	$url = $id ? wp_get_attachment_image_url( $id, 'medium' ) : '';
	echo '<div class="wdc-media">';
	echo '<input type="hidden" name="wdc_top_banner[' . esc_attr( $key ) . ']" value="' . esc_attr( $id ) . '">';
	echo '<div class="preview" style="margin-bottom:8px">' . ( $url ? '<img src="' . esc_url( $url ) . '" style="max-width:400px;height:auto">' : '' ) . '</div>';
	echo '<button type="button" class="button pick">انتخاب / آپلود تصویر</button> ';
	echo '<button type="button" class="button remove">حذف تصویر</button>';
	if ( $size ) {
		echo '<span style="margin-inline-start:12px;font-weight:600;color:#0b6b45">سایز پیشنهادی: <span dir="ltr">' . esc_html( $size ) . '</span> پیکسل</span>';
	}
	if ( $hint ) {
		echo '<p class="description">' . esc_html( $hint ) . '</p>';
	}
	echo '</div>';
}

function wdc_tb_page() {
	$o = wdc_tb_get();
	?>
	<div class="wrap" dir="rtl">
		<h1>بنر باریک بالای هدر</h1>
		<form method="post" action="options.php">
			<?php settings_fields( 'wdc_tb_group' ); ?>
			<table class="form-table">
				<tr>
					<th>نمایش بنر</th>
					<td><label><input type="checkbox" name="wdc_top_banner[enabled]" value="1" <?php checked( $o['enabled'], 1 ); ?>> فعال باشد</label></td>
				</tr>
				<tr>
					<th>تصویر دسکتاپ</th>
					<td><?php wdc_tb_media_field( 'image_id', $o['image_id'], 'اگر تصویر باشد، متن و گرادیانت نمایش داده نمی‌شود.', '1920 × ' . (int) $o['height'] ); ?></td>
				</tr>
				<tr>
					<th>تصویر موبایل (اختیاری)</th>
					<td><?php wdc_tb_media_field( 'image_mobile_id', $o['image_mobile_id'], 'اگر خالی باشد، تصویر دسکتاپ در موبایل هم نشان داده می‌شود.', '750 × ' . ( (int) $o['height_mobile'] * 2 ) ); ?></td>
				</tr>
				<tr>
					<th>متن بنر</th>
					<td><input type="text" class="large-text" name="wdc_top_banner[text]" value="<?php echo esc_attr( $o['text'] ); ?>" placeholder="مثلاً: ارسال رایگان برای خریدهای بالای ۲ میلیون تومان"></td>
				</tr>
				<tr>
					<th>لینک (اختیاری)</th>
					<td><input type="url" class="large-text" dir="ltr" name="wdc_top_banner[link]" value="<?php echo esc_attr( $o['link'] ); ?>"></td>
				</tr>
				<tr>
					<th>رنگ متن</th>
					<td><input type="text" class="wdc-color" name="wdc_top_banner[text_color]" value="<?php echo esc_attr( $o['text_color'] ); ?>"></td>
				</tr>
				<tr>
					<th>گرادیانت سبز (بدون تصویر)</th>
					<td>
						از: <input type="text" class="wdc-color" name="wdc_top_banner[color1]" value="<?php echo esc_attr( $o['color1'] ); ?>">
						تا: <input type="text" class="wdc-color" name="wdc_top_banner[color2]" value="<?php echo esc_attr( $o['color2'] ); ?>">
					</td>
				</tr>
				<tr>
					<th>ارتفاع بنر دسکتاپ (px)</th>
					<td><input type="number" min="24" max="120" name="wdc_top_banner[height]" value="<?php echo esc_attr( $o['height'] ); ?>"><p class="description">تصویر همیشه در یک کادر با همین ارتفاع ثابت قرار می‌گیرد؛ اگر نسبت عکس فرق کند، لبه‌های اضافه‌ی آن بریده می‌شود.</p></td>
				</tr>
				<tr>
					<th>ارتفاع بنر موبایل (px)</th>
					<td><input type="number" min="24" max="120" name="wdc_top_banner[height_mobile]" value="<?php echo esc_attr( $o['height_mobile'] ); ?>"></td>
				</tr>
				<tr>
					<th>دکمه بستن</th>
					<td><label><input type="checkbox" name="wdc_top_banner[closable]" value="1" <?php checked( $o['closable'], 1 ); ?>> کاربر بتواند بنر را ببندد</label></td>
				</tr>
			</table>
			<?php submit_button( 'ذخیره' ); ?>
		</form>
	</div>
	<script>
	jQuery(function($){
		$('.wdc-color').wpColorPicker();
		$('.wdc-media').each(function(){
			var box = $(this), frame;
			box.find('.pick').on('click', function(e){
				e.preventDefault();
				if(!frame){
					frame = wp.media({title:'انتخاب تصویر', multiple:false, library:{type:'image'}});
					frame.on('select', function(){
						var a = frame.state().get('selection').first().toJSON();
						box.find('input[type=hidden]').val(a.id);
						box.find('.preview').html('<img src="'+a.url+'" style="max-width:400px;height:auto">');
					});
				}
				frame.open();
			});
			box.find('.remove').on('click', function(){
				box.find('input[type=hidden]').val('');
				box.find('.preview').empty();
			});
		});
	});
	</script>
	<?php
}

/* ---------- front end ---------- */
function wdc_tb_hash( $o ) {
	return substr( md5( wp_json_encode( $o ) ), 0, 10 );
}

function wdc_tb_render() {
	$o = wdc_tb_get();
	if ( ! $o['enabled'] ) {
		return;
	}

	$has_img  = $o['image_id'] && wp_get_attachment_image_url( $o['image_id'], 'full' );
	$has_mob  = $has_img && $o['image_mobile_id'] && wp_get_attachment_image_url( $o['image_mobile_id'], 'full' );
	$has_text = '' !== $o['text'];
	if ( ! $has_img && ! $has_text ) {
		return;
	}

	$cls = 'wdc-top-banner ' . ( $has_img ? 'has-image' : 'has-text' ) . ( $has_mob ? ' has-mobile' : '' );
	echo '<div class="' . esc_attr( $cls ) . '">';
	if ( $o['link'] ) {
		echo '<a href="' . esc_url( $o['link'] ) . '">';
	}

	if ( $has_img ) {
		echo wp_get_attachment_image( $o['image_id'], 'full', false, array( 'class' => 'wdc-tb-desktop' ) );
		if ( $has_mob ) {
			echo wp_get_attachment_image( $o['image_mobile_id'], 'full', false, array( 'class' => 'wdc-tb-mobile' ) );
		}
	} else {
		echo '<span>' . esc_html( $o['text'] ) . '</span>';
	}

	if ( $o['link'] ) {
		echo '</a>';
	}
	if ( $o['closable'] ) {
		echo '<button type="button" class="wdc-tb-close" aria-label="بستن" data-hash="' . esc_attr( wdc_tb_hash( $o ) ) . '">&times;</button>';
	}
	echo '</div>';
}
add_action( 'wp_body_open', 'wdc_tb_render', 1 );

add_action( 'wp_head', function () {
	$o = wdc_tb_get();
	if ( ! $o['enabled'] ) {
		return;
	}
	$hash = wdc_tb_hash( $o );
	?>
	<script>try{if(localStorage.getItem('wdc_tb_closed')==='<?php echo esc_js( $hash ); ?>')document.documentElement.classList.add('wdc-tb-hidden')}catch(e){}</script>
	<style>
	.wdc-top-banner{width:100%;position:relative;z-index:1000;line-height:0;text-align:center}
	.wdc-tb-hidden .wdc-top-banner{display:none!important}
	.wdc-top-banner a{display:block;color:inherit;text-decoration:none}
	.wdc-top-banner{--wdc-tb-h:<?php echo (int) $o['height']; ?>px;--wdc-tb-hm:<?php echo (int) $o['height_mobile']; ?>px}
	.wdc-top-banner.has-image{height:var(--wdc-tb-h);overflow:hidden}
	.wdc-top-banner.has-image a{height:100%}
	.wdc-top-banner img{display:block;width:100%;height:100%;object-fit:cover;object-position:center}
	.wdc-top-banner .wdc-tb-mobile{display:none}
	.wdc-top-banner.has-text{
		background:linear-gradient(90deg,<?php echo esc_html( $o['color1'] ); ?>,<?php echo esc_html( $o['color2'] ); ?>);
		color:<?php echo esc_html( $o['text_color'] ); ?>;
		min-height:var(--wdc-tb-h);display:flex;align-items:center;justify-content:center;
		padding:6px 40px;font-size:14px;font-weight:600;line-height:1.5
	}
	.wdc-top-banner.has-text a{color:<?php echo esc_html( $o['text_color'] ); ?>}
	.wdc-tb-close{
		position:absolute;top:50%;inset-inline-end:10px;transform:translateY(-50%);
		width:24px;height:24px;border:0;border-radius:50%;cursor:pointer;
		background:rgba(0,0,0,.35);color:#fff;font-size:18px;line-height:22px;padding:0;z-index:2
	}
	.wdc-tb-close:hover{background:rgba(0,0,0,.6)}
	@media(max-width:768px){
		.wdc-top-banner.has-text{font-size:12px;min-height:var(--wdc-tb-hm)}
		.wdc-top-banner.has-image{height:var(--wdc-tb-hm)}
		.wdc-top-banner.has-mobile .wdc-tb-desktop{display:none}
		.wdc-top-banner.has-mobile .wdc-tb-mobile{display:block}
	}
	</style>
	<?php
} );

add_action( 'wp_footer', function () {
	$o = wdc_tb_get();
	if ( ! $o['enabled'] || ! $o['closable'] ) {
		return;
	}
	?>
	<script>
	document.addEventListener('click',function(e){
		var b=e.target.closest('.wdc-tb-close'); if(!b) return;
		try{localStorage.setItem('wdc_tb_closed',b.dataset.hash)}catch(err){}
		document.documentElement.classList.add('wdc-tb-hidden');
	});
	</script>
	<?php
} );
