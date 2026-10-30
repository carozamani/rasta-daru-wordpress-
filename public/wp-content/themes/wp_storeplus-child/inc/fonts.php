<?php
/**
 * Critical font loading (best practice).
 *
 * Problem: parent theme discovers fonts only after its CSS loads, and declares
 * .woff before .woff2 — so the browser starts the text font late and often
 * downloads the heavier format.
 *
 * Also: parent can print `body { font-family: ; }` when the font option is empty,
 * which overrides our early critical CSS and makes the site fall back to system fonts.
 * And if we dequeue the parent font sheet before our replacement is ready, nothing loads.
 *
 * Fix:
 *  1. Preload Regular + Bold woff2 as early as possible.
 *  2. Inline matching @font-face in <head> so preload is usable immediately.
 *  3. font-display: swap.
 *  4. Replace parent font-face CSS only after our sheet is registered.
 *  5. Late !important body/UI font-family so an empty parent style cannot win.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Active theme font key (parent option).
 *
 * @return string
 */
function storeplus_child_font_key() {
	$key = (string) get_option( 'irantheme_font_main_select', 'iransansx' );
	$key = trim( $key );
	$ok  = array( 'iransansx', 'iranyekanx', 'vazirmatn', 'sahel', 'shabnam', 'tanha' );
	if ( '' === $key || ! in_array( $key, $ok, true ) ) {
		return 'iransansx';
	}
	return $key;
}

/**
 * Font descriptor for preload + @font-face.
 *
 * @return array{family:string,dir:string,regular:string,bold:string,regular_fallback?:string,bold_fallback?:string}
 */
function storeplus_child_font_pack() {
	$map = array(
		'iransansx'  => array(
			'family'            => 'IRANSansX',
			'dir'               => 'iransansx',
			'regular'           => 'IRANSansXFaNum-Regular.woff2',
			'bold'              => 'IRANSansXFaNum-Bold.woff2',
			'regular_fallback'  => 'IRANSansX-Regular.woff2',
			'bold_fallback'     => 'IRANSansX-Bold.woff2',
		),
		'iranyekanx' => array(
			'family'            => 'IRANYekanX',
			'dir'               => 'iranyekanx',
			'regular'           => 'IRANYekanXFaNum-Regular.woff2',
			'bold'              => 'IRANYekanXFaNum-Bold.woff2',
			'regular_fallback'  => 'IRANYekanX-Regular.woff2',
			'bold_fallback'     => 'IRANYekanX-Bold.woff2',
		),
		'vazirmatn'  => array(
			'family'  => 'Vazirmatn',
			'dir'     => 'vazirmatn',
			'regular' => 'Vazirmatn-FD-Regular.woff2',
			'bold'    => 'Vazirmatn-FD-Bold.woff2',
		),
		'sahel'      => array(
			'family'  => 'Sahel',
			'dir'     => 'sahel',
			'regular' => 'Sahel-FD.woff2',
			'bold'    => 'Sahel-Bold-FD.woff2',
		),
		'shabnam'    => array(
			'family'  => 'Shabnam',
			'dir'     => 'shabnam',
			'regular' => 'Shabnam-FD.woff2',
			'bold'    => 'Shabnam-Bold-FD.woff2',
		),
		'tanha'      => array(
			'family'  => 'Tanha',
			'dir'     => 'tanha',
			'regular' => 'Tanha-FD.woff2',
			'bold'    => 'Tanha-FD.woff2',
		),
	);

	$key = storeplus_child_font_key();
	return isset( $map[ $key ] ) ? $map[ $key ] : $map['iransansx'];
}

/**
 * Absolute URL + filesystem path for a font file under the parent theme.
 *
 * @param string $dir  Font folder name.
 * @param string $file Filename (woff2).
 * @return array{url:string,path:string}|null
 */
function storeplus_child_font_file( $dir, $file ) {
	$file = (string) $file;
	if ( '' === $file ) {
		return null;
	}
	$rel  = '/assets/fonts/' . $dir . '/woff2/' . $file;
	$path = get_template_directory() . $rel;
	if ( ! is_file( $path ) ) {
		$rel  = '/assets/fonts/' . $dir . '/' . $file;
		$path = get_template_directory() . $rel;
	}
	if ( ! is_file( $path ) ) {
		return null;
	}
	return array(
		'url'  => get_template_directory_uri() . $rel,
		'path' => $path,
	);
}

/**
 * Resolve regular + bold files (with non-FaNum fallbacks).
 *
 * @return array{family:string,regular:array{url:string,path:string},bold:array{url:string,path:string}}|null
 */
function storeplus_child_font_faces() {
	$pack = storeplus_child_font_pack();
	$dir  = $pack['dir'];

	$regular = storeplus_child_font_file( $dir, $pack['regular'] );
	if ( ! $regular && ! empty( $pack['regular_fallback'] ) ) {
		$regular = storeplus_child_font_file( $dir, $pack['regular_fallback'] );
	}

	$bold = storeplus_child_font_file( $dir, $pack['bold'] );
	if ( ! $bold && ! empty( $pack['bold_fallback'] ) ) {
		$bold = storeplus_child_font_file( $dir, $pack['bold_fallback'] );
	}
	if ( ! $bold ) {
		$bold = $regular;
	}

	if ( ! $regular || ! $bold ) {
		return null;
	}

	return array(
		'family'  => $pack['family'],
		'regular' => $regular,
		'bold'    => $bold,
	);
}

/**
 * Shared @font-face + apply rules.
 *
 * @param array{family:string,regular:array{url:string},bold:array{url:string}} $faces Faces.
 * @param bool                                                                  $important Force !important on apply.
 * @return string
 */
function storeplus_child_font_css( array $faces, $important = false ) {
	$imp    = $important ? '!important' : '';
	$family = $faces['family'];
	$stack  = $family . ',Tahoma,Arial,sans-serif';

	$css  = '@font-face{font-family:' . $family . ';font-style:normal;font-weight:400;'
		. 'font-display:swap;src:url("' . esc_url( $faces['regular']['url'] ) . '") format("woff2")}';
	$css .= '@font-face{font-family:' . $family . ';font-style:normal;font-weight:700;'
		. 'font-display:swap;src:url("' . esc_url( $faces['bold']['url'] ) . '") format("woff2")}';
	/* Map common extra weights (800 used on PDP) onto Bold so UI never falls back to system fonts. */
	$css .= '@font-face{font-family:' . $family . ';font-style:normal;font-weight:500;'
		. 'font-display:swap;src:url("' . esc_url( $faces['regular']['url'] ) . '") format("woff2")}';
	$css .= '@font-face{font-family:' . $family . ';font-style:normal;font-weight:600;'
		. 'font-display:swap;src:url("' . esc_url( $faces['bold']['url'] ) . '") format("woff2")}';
	$css .= '@font-face{font-family:' . $family . ';font-style:normal;font-weight:800;'
		. 'font-display:swap;src:url("' . esc_url( $faces['bold']['url'] ) . '") format("woff2")}';
	$css .= '@font-face{font-family:' . $family . ';font-style:normal;font-weight:900;'
		. 'font-display:swap;src:url("' . esc_url( $faces['bold']['url'] ) . '") format("woff2")}';

	$css .= 'html,body,button,input,select,textarea,.tooltip,'
		. '.irantheme-input-form-fields input[type=submit],'
		. '.irantheme-input-form-fields input[type=reset],'
		. '.irantheme-input-form-fields input[type=button],'
		. '.irantheme-input-form-fields button{'
		. 'font-family:' . $stack . $imp . '}';

	return $css;
}

/**
 * Preload + critical @font-face in <head> (runs before stylesheets).
 */
function storeplus_child_fonts_critical_head() {
	if ( is_admin() ) {
		return;
	}

	$faces = storeplus_child_font_faces();
	if ( ! $faces ) {
		return;
	}

	$seen = array();
	foreach ( array( $faces['regular']['url'], $faces['bold']['url'] ) as $url ) {
		if ( isset( $seen[ $url ] ) ) {
			continue;
		}
		$seen[ $url ] = true;
		printf(
			'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin fetchpriority="high">' . "\n",
			esc_url( $url )
		);
	}

	echo '<style id="sp-fonts-critical">' . storeplus_child_font_css( $faces, false ) . '</style>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
add_action( 'wp_head', 'storeplus_child_fonts_critical_head', 0 );

/**
 * Late override: beat parent `irantheme-internal-style` if it printed an empty font-family.
 */
function storeplus_child_fonts_late_head() {
	if ( is_admin() ) {
		return;
	}
	$faces = storeplus_child_font_faces();
	if ( ! $faces ) {
		return;
	}
	echo '<style id="sp-fonts-apply">' . storeplus_child_font_css( $faces, true ) . '</style>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
add_action( 'wp_head', 'storeplus_child_fonts_late_head', 99 );

/**
 * Drop parent font-face CSS only when our lean woff2 sheet is ready.
 */
function storeplus_child_fonts_replace_parent_sheet() {
	if ( is_admin() ) {
		return;
	}

	$faces = storeplus_child_font_faces();
	if ( ! $faces ) {
		/* Keep parent font CSS — do not dequeue into a fontless page. */
		return;
	}

	$handles = array( 'iransansx', 'iranyekanx', 'vazirmatn', 'sahel', 'shabnam', 'tanha' );
	foreach ( $handles as $h ) {
		wp_dequeue_style( $h );
		wp_deregister_style( $h );
	}

	$ver = (string) max( (int) filemtime( $faces['regular']['path'] ), (int) filemtime( $faces['bold']['path'] ) );
	$css = storeplus_child_font_css( $faces, true );

	wp_register_style( 'storeplus-child-fonts', false, array(), $ver );
	wp_enqueue_style( 'storeplus-child-fonts' );
	wp_add_inline_style( 'storeplus-child-fonts', $css );
}
add_action( 'wp_enqueue_scripts', 'storeplus_child_fonts_replace_parent_sheet', 100 );

/**
 * Ensure the parent option always has a valid font key (avoids empty font-family CSS).
 */
function storeplus_child_fonts_ensure_option() {
	$raw = get_option( 'irantheme_font_main_select', null );
	$key = is_string( $raw ) ? trim( $raw ) : '';
	$ok  = array( 'iransansx', 'iranyekanx', 'vazirmatn', 'sahel', 'shabnam', 'tanha' );
	if ( '' === $key || ! in_array( $key, $ok, true ) ) {
		update_option( 'irantheme_font_main_select', 'iransansx', true );
	}
}
add_action( 'init', 'storeplus_child_fonts_ensure_option', 5 );
