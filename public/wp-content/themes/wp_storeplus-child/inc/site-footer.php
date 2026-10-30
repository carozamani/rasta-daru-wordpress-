<?php
/**
 * Single site footer — same component on every front-end page (like the header).
 *
 * Why footers drifted before:
 *  - Home often embeds a Page Builder footer section with its own fields.
 *  - Cart / product / shop call Irantheme_Footer_Generator::display_footer().
 *  - landing-polish.css restyled the home footer only.
 *
 * This module:
 *  1) Strips footer_* sections from Page Builder output on the front end.
 *  2) Ensures exactly one global footer is printed (Footer_Generator template).
 *  3) Dedupes if a template also called display_footer() already.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const SP_FOOTER_SECTION_VALUES = array(
	'footer_nav',
	'footer_classic',
	'footer_master',
	'footer_copyright',
);

/**
 * Front-end only: remove footer blocks from page-builder section JSON
 * so the homepage (and any PB page) cannot ship a second, different footer.
 *
 * @param mixed  $value    Filtered meta value (null = use default).
 * @param int    $object_id Post ID.
 * @param string $meta_key Meta key.
 * @param bool   $single   Single flag.
 * @return mixed
 */
function storeplus_child_strip_pb_footer_sections( $value, $object_id, $meta_key, $single ) {
	if ( 'irantheme_page_sections' !== $meta_key ) {
		return $value;
	}
	if ( is_admin() && ! wp_doing_ajax() ) {
		return $value;
	}
	/* REST / builder AJAX still needs full JSON for editing. */
	if ( wp_doing_ajax() && is_admin() ) {
		return $value;
	}
	if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
		return $value;
	}

	static $busy = false;
	if ( $busy ) {
		return $value;
	}
	$busy = true;
	$raw  = get_post_meta( (int) $object_id, 'irantheme_page_sections', true );
	$busy = false;

	if ( ! is_string( $raw ) || '' === $raw ) {
		return $value;
	}

	$decoded = null;
	if ( class_exists( '\Irantheme_Dev\Irantheme_JSON_Helper' ) ) {
		$decoded = \Irantheme_Dev\Irantheme_JSON_Helper::decode( $raw );
		if ( is_array( $decoded ) ) {
			$decoded = \Irantheme_Dev\Irantheme_JSON_Helper::normalize_sections( $decoded );
		}
	}
	if ( ! is_array( $decoded ) ) {
		$try = json_decode( $raw, true );
		$decoded = is_array( $try ) ? $try : null;
	}
	if ( ! is_array( $decoded ) || empty( $decoded['sections_items'] ) || ! is_array( $decoded['sections_items'] ) ) {
		return $value;
	}

	$changed = false;
	$kept    = array();
	foreach ( $decoded['sections_items'] as $section ) {
		$val = isset( $section['data_value'] ) ? (string) $section['data_value'] : '';
		if ( in_array( $val, SP_FOOTER_SECTION_VALUES, true ) ) {
			$changed = true;
			continue;
		}
		$kept[] = $section;
	}
	if ( ! $changed ) {
		return $value;
	}

	$decoded['sections_items'] = $kept;

	$out = class_exists( '\Irantheme_Dev\Irantheme_JSON_Helper' )
		? \Irantheme_Dev\Irantheme_JSON_Helper::encode( $decoded )
		: wp_json_encode( $decoded, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
	if ( ! is_string( $out ) || '' === $out ) {
		return $value;
	}

	return $single ? $out : array( $out );
}
add_filter( 'get_post_metadata', 'storeplus_child_strip_pb_footer_sections', 10, 4 );

/**
 * Print the global theme footer once.
 */
function storeplus_child_render_site_footer() {
	static $done = false;
	if ( $done ) {
		return;
	}
	$done = true;

	if ( ! class_exists( 'Irantheme_Footer_Generator', false ) ) {
		return;
	}
	\Irantheme_Footer_Generator::display_footer();
}

/**
 * Before footer.php: if the buffer has no real footer yet, print the global one.
 * Templates that already called display_footer() keep theirs; OB dedupe keeps one.
 */
function storeplus_child_maybe_render_site_footer() {
	if ( is_admin() ) {
		return;
	}
	$buf = '';
	if ( ob_get_level() > 0 ) {
		$buf = (string) ob_get_contents();
	}
	/* Must be a real <footer> node — class names also appear in CSS/JS strings. */
	if ( $buf && preg_match( '/<footer\b[^>]*\birantheme-footer-(?:nav|classic)-section\b/i', $buf ) ) {
		return;
	}
	storeplus_child_render_site_footer();
}
add_action( 'get_footer', 'storeplus_child_maybe_render_site_footer', 0 );

/**
 * Start a page buffer so we can peek for an existing footer and collapse duplicates.
 */
function storeplus_child_site_footer_buffer_start() {
	if ( is_admin() || wp_doing_ajax() || wp_is_json_request() ) {
		return;
	}
	ob_start( 'storeplus_child_site_footer_buffer_end' );
}
add_action( 'template_redirect', 'storeplus_child_site_footer_buffer_start', 0 );

/**
 * Keep a single real footer; tag it as the site component.
 *
 * Uses balanced footer matching (see storeplus_child_footer_find_sections) so
 * nested Page Builder <footer class="…hover-*"> nodes cannot truncate the chunk.
 *
 * @param string $html Full page HTML.
 * @return string
 */
function storeplus_child_site_footer_buffer_end( $html ) {
	if ( ! is_string( $html ) || '' === $html ) {
		return $html;
	}
	$original = $html;

	$sections = function_exists( 'storeplus_child_footer_find_sections' )
		? storeplus_child_footer_find_sections( $html )
		: array();

	if ( $sections ) {
		$keep = end( $sections );
		foreach ( array_reverse( $sections ) as $sec ) {
			if ( false !== strpos( $sec['html'], 'data-sp-site-footer' ) ) {
				$keep = $sec;
				break;
			}
		}

		if ( count( $sections ) > 1 ) {
			/* Remove every site footer from the end so offsets stay valid. */
			for ( $i = count( $sections ) - 1; $i >= 0; $i-- ) {
				$html = substr_replace( $html, '', $sections[ $i ]['start'], $sections[ $i ]['length'] );
			}
			$anchor = '<div class="irantheme-tips-holder"></div>';
			if ( false !== strpos( $html, $anchor ) ) {
				$html = str_replace( $anchor, $keep['html'] . "\n" . $anchor, $html );
			} else {
				$html = preg_replace( '/<\/body>/i', $keep['html'] . "\n</body>", $html, 1 );
			}
		}
	} else {
		/* Legacy fallback only when the balanced finder is unavailable. */
		$pattern = '/<footer\b[^>]*\birantheme-footer-(?:nav|classic)-section\b[^>]*>.*?<\/footer>/is';
		if ( ! preg_match_all( $pattern, $html, $matches ) ) {
			return $html;
		}
		$all  = $matches[0];
		$keep = end( $all );
		foreach ( array_reverse( $all ) as $chunk ) {
			if ( false !== strpos( $chunk, 'data-sp-site-footer' ) ) {
				$keep = $chunk;
				break;
			}
		}
		if ( count( $all ) > 1 ) {
			$html   = preg_replace( $pattern, '', $html );
			$anchor = '<div class="irantheme-tips-holder"></div>';
			if ( false !== strpos( $html, $anchor ) ) {
				$html = str_replace( $anchor, $keep . "\n" . $anchor, $html );
			} else {
				$html = preg_replace( '/<\/body>/i', $keep . "\n</body>", $html, 1 );
			}
		}
	}

	/*
	 * Mark the surviving footer as the one site component (idempotent).
	 * Insert the attribute before the closing ">" of the opening tag so we never
	 * split a multi-class attribute (e.g. "...-section white") mid-string.
	 */
	$html = preg_replace(
		'/(<footer\b[^>]*\birantheme-footer-(?:nav|classic)-section\b[^>]*)(?![^>]*\bdata-sp-site-footer=)([^>]*)(>)/i',
		'$1$2 data-sp-site-footer="1"$3',
		$html,
		1
	);

	/*
	 * Server-side footer layout + admin content (inc/footer-php-render.php).
	 * Runs after dedupe so we transform exactly one footer. On failure the
	 * original HTML is kept and legacy JS remains as a safety net.
	 */
	if ( function_exists( 'storeplus_child_footer_php_apply' ) ) {
		try {
			$php_footer = storeplus_child_footer_php_apply( $html );
		} catch ( Throwable $e ) {
			return $original;
		}
		if ( is_string( $php_footer ) && '' !== $php_footer ) {
			$html = $php_footer;
		}
	}

	if ( '' === $html || false === stripos( $html, '<body' ) ) {
		return $original;
	}

	return $html;
}
