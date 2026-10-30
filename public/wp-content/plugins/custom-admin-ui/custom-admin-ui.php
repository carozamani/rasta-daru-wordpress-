<?php
/**
 * Plugin Name:       Custom Admin UI
 * Description:       Modern, update-safe redesign of the WordPress admin: refined sidebar and toolbar, dark mode, quick-search palette (Ctrl+/), RTL and Persian ready.
 * Version:           1.0.0
 * Requires at least: 5.8
 * Requires PHP:      7.2
 * Text Domain:       custom-admin-ui
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( defined( 'CAU_VERSION' ) ) {
	return;
}

define( 'CAU_VERSION', '1.0.0' );
define( 'CAU_DIR', plugin_dir_path( __FILE__ ) );
define( 'CAU_URL', plugin_dir_url( __FILE__ ) );
define( 'CAU_OPTION', 'cau_settings' );

/* -------------------------------------------------------------------------
 * Helpers
 * ---------------------------------------------------------------------- */

function cau_t( $en, $fa ) {
	return ( 0 === strpos( determine_locale(), 'fa' ) ) ? $fa : $en;
}

function cau_defaults() {
	return array(
		'accent'       => '#1b7a3a',
		'mode'         => 'auto',
		'hide_wp_logo' => 1,
		'footer_text'  => '',
	);
}

function cau_get( $key = null ) {
	$opts = wp_parse_args( get_option( CAU_OPTION, array() ), cau_defaults() );
	return null === $key ? $opts : ( isset( $opts[ $key ] ) ? $opts[ $key ] : null );
}

function cau_hex_to_rgb( $hex ) {
	$hex = ltrim( $hex, '#' );
	if ( 3 === strlen( $hex ) ) {
		$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
	}
	return array( hexdec( substr( $hex, 0, 2 ) ), hexdec( substr( $hex, 2, 2 ) ), hexdec( substr( $hex, 4, 2 ) ) );
}

function cau_shade( $hex, $amount ) {
	list( $r, $g, $b ) = cau_hex_to_rgb( $hex );
	$target = $amount < 0 ? 0 : 255;
	$p      = abs( $amount );
	$mix    = function ( $c ) use ( $target, $p ) { return (int) round( $c + ( $target - $c ) * $p ); };
	return sprintf( '#%02x%02x%02x', $mix( $r ), $mix( $g ), $mix( $b ) );
}

function cau_accent() {
	$a = sanitize_hex_color( (string) cau_get( 'accent' ) );
	return $a ? $a : '#1b7a3a';
}

function cau_vars_css() {
	$accent = cau_accent();
	list( $r, $g, $b ) = cau_hex_to_rgb( $accent );
	$d10 = cau_shade( $accent, -0.10 );
	$d18 = cau_shade( $accent, -0.18 );
	$d20 = cau_shade( $accent, -0.20 );
	$lgt = cau_shade( $accent,  0.38 );
	return sprintf(
		':root,body.cau-ui{--cau-accent:%1$s;--cau-accent-rgb:%2$d,%3$d,%4$d;--cau-accent-dark:%5$s;--cau-accent-light:%6$s;--cau-accent-hover:%7$s;--wp-admin-theme-color:%1$s;--wp-admin-theme-color--rgb:%2$d,%3$d,%4$d;--wp-admin-theme-color-darker-10:%8$s;--wp-admin-theme-color-darker-20:%9$s}',
		$accent, $r, $g, $b, $d18, $lgt, cau_shade( $accent, 0.08 ), $d10, $d20
	);
}

function cau_sidebar_critical_css() {
	$accent     = cau_accent();
	$accent_sub = cau_shade( $accent, 0.08 );

	return "
/* CAU critical sidebar overrides - default WP menu, only accent colour customised */
body.wp-admin #adminmenu li.menu-top:hover>a.menu-top,
body.wp-admin #adminmenu li.opensub>a.menu-top,
body.wp-admin #adminmenu li>a.menu-top:focus,
body.wp-admin #adminmenu li.current>a.menu-top,
body.wp-admin #adminmenu li.wp-has-current-submenu>a.wp-has-current-submenu,
body.wp-admin #adminmenu li.wp-has-current-submenu.opensub>a.wp-has-current-submenu{
	background-color:{$accent}!important;
	color:#fff!important;
}
body.wp-admin #adminmenu .awaiting-mod,
body.wp-admin #adminmenu .update-plugins{background:{$accent}!important;color:#fff!important}

/* Toolbar */
body.wp-admin #wpadminbar{background:#111!important;border-bottom:1px solid #222!important}
body.wp-admin #wpadminbar .ab-top-menu>li.hover>.ab-item,
body.wp-admin #wpadminbar .ab-top-menu>li:hover>.ab-item,
body.wp-admin #wpadminbar:not(.mobile) .ab-top-menu>li>.ab-item:focus{
	background:rgba(255,255,255,.07)!important;color:#fff!important;
}
body.wp-admin #wpadminbar li #adminbarsearch.adminbar-focused:before,
body.wp-admin #wpadminbar .ab-item:before,
body.wp-admin #wpadminbar .ab-icon:before{color:#888!important}
";
}

function cau_font_face_css() {
	$file = CAU_DIR . 'assets/fonts/Vazirmatn-Variable.woff2';
	if ( ! file_exists( $file ) ) { return ''; }
	$url = esc_url( CAU_URL . 'assets/fonts/Vazirmatn-Variable.woff2' );
	return '@font-face{font-family:"Vazirmatn";src:url("' . $url . '") format("woff2");font-weight:100 900;font-display:swap}';
}

/* -------------------------------------------------------------------------
 * Admin: assets
 * ---------------------------------------------------------------------- */

add_action( 'admin_enqueue_scripts', 'cau_enqueue', 999 );
function cau_enqueue() {
	$css = CAU_DIR . 'assets/css/admin.css';
	$js  = CAU_DIR . 'assets/js/admin.js';

	wp_enqueue_style(
		'cau-admin',
		CAU_URL . 'assets/css/admin.css',
		array( 'colors', 'admin-bar' ),
		file_exists( $css ) ? filemtime( $css ) : CAU_VERSION
	);

	wp_enqueue_script(
		'cau-admin',
		CAU_URL . 'assets/js/admin.js',
		array(),
		file_exists( $js ) ? filemtime( $js ) : CAU_VERSION,
		true
	);

	wp_localize_script( 'cau-admin', 'CAU', array(
		'i18n' => array(
			'title'       => cau_t( 'Quick search', 'جستجوی سریع' ),
			'placeholder' => cau_t( 'Search admin pages…', 'جستجو در صفحات مدیریت…' ),
			'empty'       => cau_t( 'No matching pages.', 'صفحه‌ای پیدا نشد.' ),
			'hint'        => cau_t( 'Enter to open, Esc to close', 'Enter برای باز کردن، Esc برای بستن' ),
		),
	) );
}

/* Priority 1 — flash prevention */
add_action( 'admin_head', 'cau_head', 1 );
function cau_head() {
	$mode = cau_get( 'mode' );
	$mode = in_array( $mode, array( 'auto', 'light', 'dark' ), true ) ? $mode : 'auto';
	echo '<style id="cau-vars">' . cau_font_face_css() . cau_vars_css() . '</style>' . "\n"; // phpcs:ignore
	?>
<script>
(function(){
	var d=document.documentElement,p=null;
	try{p=localStorage.getItem('cau-mode')}catch(e){}
	if(p!=='light'&&p!=='dark'){p='<?php echo esc_js($mode);?>';}
	if(p==='auto'){p=(window.matchMedia&&matchMedia('(prefers-color-scheme:dark)').matches)?'dark':'light';}
	d.setAttribute('data-cau-theme',p);
})();
</script>
	<?php
}

/* Priority 999 — sidebar critical overrides, after all <link> tags */
add_action( 'admin_head', 'cau_head_late', 999 );
function cau_head_late() {
	echo '<style id="cau-critical">' . cau_sidebar_critical_css() . '</style>' . "\n"; // phpcs:ignore
}

add_filter( 'admin_body_class', 'cau_body_class' );
function cau_body_class( $classes ) {
	$classes .= ' cau-ui';
	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( ! ( $screen && method_exists( $screen, 'is_block_editor' ) && $screen->is_block_editor() ) ) {
		$classes .= ' cau-full';
	}
	return $classes;
}

/* -------------------------------------------------------------------------
 * Toolbar
 * ---------------------------------------------------------------------- */

add_action( 'admin_bar_menu', 'cau_admin_bar', 999 );
function cau_admin_bar( $bar ) {
	if ( ! is_admin() ) { return; }
	if ( cau_get( 'hide_wp_logo' ) ) { $bar->remove_node( 'wp-logo' ); }

	$search_label = cau_t( 'Quick search', 'جستجوی سریع' );
	$theme_label  = cau_t( 'Toggle dark mode', 'تغییر حالت تیره' );
	$search_icon  = '<svg width="16" height="16" viewBox="0 0 16 16" aria-hidden="true" focusable="false"><circle cx="7" cy="7" r="4.75" fill="none" stroke="currentColor" stroke-width="1.5"/><path d="M10.6 10.6 14 14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>';
	$theme_icon   = '<svg width="16" height="16" viewBox="0 0 16 16" aria-hidden="true" focusable="false"><circle cx="8" cy="8" r="6.25" fill="none" stroke="currentColor" stroke-width="1.5"/><path d="M8 1.75a6.25 6.25 0 0 1 0 12.5z" fill="currentColor"/></svg>';

	$bar->add_node( array(
		'id' => 'cau-theme', 'parent' => 'top-secondary', 'href' => '#',
		'title' => $theme_icon . '<span class="screen-reader-text">' . esc_html( $theme_label ) . '</span>',
		'meta'  => array( 'class' => 'cau-bar-btn', 'title' => $theme_label ),
	) );
	$bar->add_node( array(
		'id' => 'cau-search', 'parent' => 'top-secondary', 'href' => '#',
		'title' => $search_icon . '<span class="cau-bar-label">' . esc_html( $search_label ) . '</span><span class="cau-kbd">Ctrl /</span>',
		'meta'  => array( 'class' => 'cau-bar-btn', 'title' => $search_label ),
	) );
}

add_filter( 'admin_footer_text', function() {
	$t = (string) cau_get( 'footer_text' );
	return '' === $t ? '' : esc_html( $t );
}, 99 );
add_filter( 'update_footer', '__return_empty_string', 99 );

/* -------------------------------------------------------------------------
 * Login
 * ---------------------------------------------------------------------- */

add_action( 'login_enqueue_scripts', 'cau_login_assets' );
function cau_login_assets() {
	$css = CAU_DIR . 'assets/css/login.css';
	wp_enqueue_style( 'cau-login', CAU_URL . 'assets/css/login.css', array( 'login' ),
		file_exists( $css ) ? filemtime( $css ) : CAU_VERSION );
	wp_add_inline_style( 'cau-login', cau_font_face_css() . cau_vars_css() );
}

add_filter( 'login_headerurl',  function() { return home_url( '/' ); } );
add_filter( 'login_headertext', function() { return get_bloginfo( 'name' ); } );

/* -------------------------------------------------------------------------
 * Settings page
 * ---------------------------------------------------------------------- */

add_action( 'admin_menu', 'cau_register_menu' );
function cau_register_menu() {
	$title = cau_t( 'Admin appearance', 'ظاهر پنل مدیریت' );
	add_options_page( $title, $title, 'manage_options', 'custom-admin-ui', 'cau_render_settings' );
}

add_action( 'admin_init', 'cau_register_settings' );
function cau_register_settings() {
	register_setting( 'cau_group', CAU_OPTION, array(
		'type' => 'array', 'sanitize_callback' => 'cau_sanitize', 'default' => cau_defaults(),
	) );
}

function cau_sanitize( $in ) {
	$d = cau_defaults(); $in = is_array( $in ) ? $in : array(); $out = array();
	$a = isset( $in['accent'] ) ? sanitize_hex_color( $in['accent'] ) : '';
	$out['accent'] = $a ? $a : $d['accent'];
	$out['mode']   = ( isset( $in['mode'] ) && in_array( $in['mode'], array( 'auto','light','dark' ), true ) ) ? $in['mode'] : $d['mode'];
	$out['hide_wp_logo'] = empty( $in['hide_wp_logo'] ) ? 0 : 1;
	$out['footer_text']  = isset( $in['footer_text'] ) ? sanitize_text_field( $in['footer_text'] ) : '';
	return $out;
}

add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), function( $links ) {
	$url = admin_url( 'options-general.php?page=custom-admin-ui' );
	array_unshift( $links, '<a href="' . esc_url( $url ) . '">' . esc_html( cau_t( 'Settings', 'تنظیمات' ) ) . '</a>' );
	return $links;
} );

function cau_render_settings() {
	if ( ! current_user_can( 'manage_options' ) ) { return; }
	$s = cau_get();
	?>
	<div class="wrap">
		<h1><?php echo esc_html( cau_t( 'Admin appearance', 'ظاهر پنل مدیریت' ) ); ?></h1>
		<form method="post" action="options.php">
			<?php settings_fields( 'cau_group' ); ?>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="cau-accent"><?php echo esc_html( cau_t( 'Accent colour', 'رنگ اصلی' ) ); ?></label></th>
					<td><input type="color" id="cau-accent" name="<?php echo esc_attr(CAU_OPTION);?>[accent]" value="<?php echo esc_attr(cau_accent());?>"></td>
				</tr>
				<tr>
					<th scope="row"><label for="cau-mode"><?php echo esc_html( cau_t( 'Default theme', 'حالت پیش‌فرض' ) ); ?></label></th>
					<td>
						<select id="cau-mode" name="<?php echo esc_attr(CAU_OPTION);?>[mode]">
							<option value="auto"  <?php selected($s['mode'],'auto');?>><?php echo esc_html(cau_t('Follow system','مطابق سیستم'));?></option>
							<option value="light" <?php selected($s['mode'],'light');?>><?php echo esc_html(cau_t('Light','روشن'));?></option>
							<option value="dark"  <?php selected($s['mode'],'dark');?>><?php echo esc_html(cau_t('Dark','تیره'));?></option>
						</select>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php echo esc_html( cau_t( 'Toolbar', 'نوار بالا' ) ); ?></th>
					<td><label><input type="checkbox" name="<?php echo esc_attr(CAU_OPTION);?>[hide_wp_logo]" value="1" <?php checked(!empty($s['hide_wp_logo']));?>> <?php echo esc_html(cau_t('Hide WordPress logo menu','مخفی کردن منوی لوگوی وردپرس'));?></label></td>
				</tr>
				<tr>
					<th scope="row"><label for="cau-footer"><?php echo esc_html( cau_t( 'Footer text', 'متن فوتر' ) ); ?></label></th>
					<td><input type="text" class="regular-text" id="cau-footer" name="<?php echo esc_attr(CAU_OPTION);?>[footer_text]" value="<?php echo esc_attr($s['footer_text']);?>"></td>
				</tr>
			</table>
			<?php submit_button( cau_t( 'Save changes', 'ذخیره تغییرات' ) ); ?>
		</form>
	</div>
	<?php
}

/* -------------------------------------------------------------------------
 * Notice jump fix
 * WordPress (common.js) moves every admin notice from the top of
 * #wpbody-content to below the page title after DOM ready, which makes the
 * page jump. Notices carrying the .inline class are never moved, so we mark
 * the top-level ones before that happens: they stay where they are printed
 * and are visible from the first paint.
 * ---------------------------------------------------------------------- */
add_action( 'admin_footer', 'cau_notice_jump_fix', 1 );
function cau_notice_jump_fix() {
	?>
<script>
(function(){
	var n = document.querySelectorAll('#wpbody-content > .notice, #wpbody-content > .updated, #wpbody-content > .error, #wpbody-content > .update-nag');
	for (var i = 0; i < n.length; i++) { n[i].classList.add('inline', 'cau-notice'); }
})();
</script>
	<?php
}

/* -------------------------------------------------------------------------
 * Modern main-content styling (assets/css/modern.css), loaded after admin.css.
 * Remove this block to return to the previous look.
 * ---------------------------------------------------------------------- */
add_action( 'admin_enqueue_scripts', 'cau_enqueue_modern', 1000 );
function cau_enqueue_modern() {
	$css = CAU_DIR . 'assets/css/modern.css';
	if ( ! file_exists( $css ) ) { return; }
	wp_enqueue_style( 'cau-modern', CAU_URL . 'assets/css/modern.css', array( 'cau-admin' ), filemtime( $css ) );
}
