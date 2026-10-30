<?php
/**
 * Critical launch fixes: bad absolute hosts, PB markup junk, HTML hygiene.
 *
 * - Rewrites ngrok / LINKE-JADID-INJA / stale .local hosts out of front HTML.
 * - Strips Page Builder hover <footer> separators (invalid landmarks + bloat).
 * - Provides a one-shot DB scrubber for stored content (serialized-safe).
 *
 * @package StorePlus_Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Host fragments that must never appear in public HTML or stored content.
 *
 * @return string[]
 */
function storeplus_child_critical_bad_host_needles() {
	return array(
		'ngrok-free.dev',
		'ngrok.io',
		'ngrok.app',
		'LINKE-JADID-INJA',
		'linke-jadid-inja',
	);
}

/**
 * True when a host is a stale local/dev tunnel that should become the current origin.
 *
 * @param string $host Host (no scheme).
 * @return bool
 */
function storeplus_child_critical_is_stale_host( $host ) {
	$host = strtolower( (string) $host );
	if ( '' === $host ) {
		return false;
	}
	foreach ( storeplus_child_critical_bad_host_needles() as $needle ) {
		if ( false !== strpos( $host, strtolower( $needle ) ) ) {
			return true;
		}
	}
	/*
	 * Local-only hostnames must never ship in stored HTML/content.
	 * Prefer relative paths so Local / ngrok / production all resolve correctly.
	 */
	if ( 'rastadarou.local' === $host || 'rastadarou.local' === explode( ':', $host )[0] ) {
		return true;
	}
	return false;
}

/**
 * Convert an absolute URL to a same-origin path (keeps query/hash).
 * Leaves non-http(s), anchors, and already-relative paths alone.
 *
 * @param string $url URL.
 * @return string
 */
function storeplus_child_critical_url_to_path( $url ) {
	if ( ! is_string( $url ) || '' === $url || '#' === $url ) {
		return $url;
	}
	if ( 0 === strpos( $url, '/' ) && 0 !== strpos( $url, '//' ) ) {
		return $url;
	}
	if ( ! preg_match( '#^https?://#i', $url ) ) {
		return $url;
	}
	$parts = wp_parse_url( $url );
	if ( ! is_array( $parts ) || empty( $parts['path'] ) ) {
		return home_url( '/' );
	}
	$out = $parts['path'];
	if ( ! empty( $parts['query'] ) ) {
		$out .= '?' . $parts['query'];
	}
	if ( ! empty( $parts['fragment'] ) ) {
		$out .= '#' . $parts['fragment'];
	}
	return $out;
}

/**
 * Rewrite one URL if its host is bad/stale; otherwise return unchanged.
 *
 * @param string $url URL.
 * @return string
 */
function storeplus_child_critical_rewrite_url( $url ) {
	if ( ! is_string( $url ) || '' === $url ) {
		return $url;
	}
	if ( ! preg_match( '#^https?://#i', $url ) ) {
		return $url;
	}
	$parts = wp_parse_url( $url );
	if ( ! is_array( $parts ) || empty( $parts['host'] ) ) {
		return $url;
	}
	if ( ! storeplus_child_critical_is_stale_host( $parts['host'] ) ) {
		return $url;
	}
	/* Relative path — host-agnostic in DB and HTML. */
	return storeplus_child_critical_url_to_path( $url );
}

/**
 * Recursively rewrite bad hosts inside strings / arrays.
 *
 * @param mixed $value Value.
 * @return mixed
 */
function storeplus_child_critical_rewrite_deep( $value ) {
	if ( is_string( $value ) ) {
		if ( '' === $value ) {
			return $value;
		}
		/* Fast path: skip strings with no suspicious needles. */
		$hay = $value;
		$hit = false;
		foreach ( storeplus_child_critical_bad_host_needles() as $n ) {
			if ( false !== stripos( $hay, $n ) ) {
				$hit = true;
				break;
			}
		}
		if ( ! $hit && false !== stripos( $hay, 'rastadarou.local' ) ) {
			$hit = true;
		}
		if ( ! $hit ) {
			return $value;
		}

		/* Drop LINKE media URLs entirely from comma-separated gallery lists (files are missing). */
		$value = preg_replace_callback(
			'#https?://LINKE-JADID-INJA[^,\s"\'\\\\]*#i',
			static function () {
				return '';
			},
			$value
		);
		$value = preg_replace( '/,+/', ',', $value );
		$value = trim( $value, ", \t\n\r" );

		/* Rewrite remaining absolute bad hosts (escaped JSON \/ and plain). */
		$value = preg_replace_callback(
			'#https?:\\\\?/\\\\?/([A-Za-z0-9._:-]+)([^"\'\s<]*)#',
			static function ( $m ) {
				$host = strtolower( $m[1] );
				$path = isset( $m[2] ) ? $m[2] : '';
				$path = str_replace( '\/', '/', $path );
				if ( ! storeplus_child_critical_is_stale_host( $host ) ) {
					return $m[0];
				}
				$full = 'http://' . $host . $path;
				$new  = storeplus_child_critical_rewrite_url( $full );
				/* Preserve JSON-escaped slashes when the original used them. */
				if ( false !== strpos( $m[0], '\/' ) ) {
					return str_replace( '/', '\/', $new );
				}
				return $new;
			},
			$value
		);

		return $value;
	}

	if ( is_array( $value ) ) {
		foreach ( $value as $k => $v ) {
			$value[ $k ] = storeplus_child_critical_rewrite_deep( $v );
		}
		return $value;
	}

	return $value;
}

/**
 * Page-builder hover chrome incorrectly uses <footer> — convert to <div>.
 *
 * @param string $html HTML.
 * @return string
 */
function storeplus_child_critical_normalize_pb_footers( $html ) {
	if ( ! is_string( $html ) || false === stripos( $html, 'irantheme-page-builder-hover-' ) ) {
		return $html;
	}
	/*
	 * Page Builder hover chrome wrongly uses <footer>. Convert innermost matches
	 * first so nested section/object separators both become presentational <div>s.
	 */
	$prev = null;
	$guard = 0;
	while ( $prev !== $html && $guard < 50 ) {
		$prev = $html;
		$guard++;
		$html = preg_replace_callback(
			'/<footer(\s+[^>]*\birantheme-page-builder-hover-(?:section|object|object-group)-separator\b[^>]*)>(.*?)<\/footer>/is',
			static function ( $m ) {
				$attrs = $m[1];
				if ( false === stripos( $attrs, 'role=' ) ) {
					$attrs .= ' role="presentation"';
				}
				return '<div' . $attrs . '>' . $m[2] . '</div>';
			},
			$html
		);
		if ( ! is_string( $html ) ) {
			return $prev;
		}
	}
	return $html;
}

/**
 * Final HTML pass for every front response.
 *
 * @param string $html HTML.
 * @return string
 */
function storeplus_child_critical_html_fix( $html ) {
	if ( ! is_string( $html ) || '' === $html ) {
		return $html;
	}
	$original = $html;

	$html = storeplus_child_critical_normalize_pb_footers( $html );
	$html = storeplus_child_critical_rewrite_deep( $html );

	if ( ! is_string( $html ) || '' === $html || false === stripos( $html, '<html' ) ) {
		return $original;
	}
	return $html;
}

/**
 * Start the outermost page buffer (runs after nested theme buffers).
 */
function storeplus_child_critical_buffer_start() {
	if ( is_admin() || wp_doing_ajax() || wp_is_json_request() || is_feed() ) {
		return;
	}
	if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
		return;
	}
	ob_start( 'storeplus_child_critical_html_fix' );
}
add_action( 'template_redirect', 'storeplus_child_critical_buffer_start', -1 );

/**
 * Attachment / content URL filters (covers cases outside full-page HTML).
 *
 * @param string $url URL.
 * @return string
 */
function storeplus_child_critical_filter_url( $url ) {
	return storeplus_child_critical_rewrite_url( (string) $url );
}
add_filter( 'wp_get_attachment_url', 'storeplus_child_critical_filter_url', 99 );
add_filter( 'attachment_link', 'storeplus_child_critical_filter_url', 99 );
add_filter( 'the_content', 'storeplus_child_critical_rewrite_deep', 99 );
add_filter( 'widget_text', 'storeplus_child_critical_rewrite_deep', 99 );

/**
 * Scrub one DB row value (serialized / JSON / plain).
 *
 * @param mixed $value Raw.
 * @return array{0:mixed,1:bool} [new value, changed?]
 */
function storeplus_child_critical_scrub_value( $value ) {
	$raw = $value;
	if ( is_string( $value ) ) {
		$trim = ltrim( $value );
		if ( '' !== $trim && ( '{' === $trim[0] || '[' === $trim[0] ) ) {
			$json = json_decode( $value, true );
			if ( JSON_ERROR_NONE === json_last_error() && null !== $json ) {
				$new = storeplus_child_critical_rewrite_deep( $json );
				if ( $new !== $json ) {
					$enc = wp_json_encode( $new, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
					return array( is_string( $enc ) ? $enc : $value, true );
				}
				return array( $value, false );
			}
		}
		$maybe = maybe_unserialize( $value );
		if ( $maybe !== $value && ( is_array( $maybe ) || is_object( $maybe ) ) ) {
			$new = storeplus_child_critical_rewrite_deep( $maybe );
			if ( $new !== $maybe ) {
				return array( maybe_serialize( $new ), true );
			}
			return array( $value, false );
		}
		$new = storeplus_child_critical_rewrite_deep( $value );
		return array( $new, $new !== $value );
	}
	if ( is_array( $value ) || is_object( $value ) ) {
		$new = storeplus_child_critical_rewrite_deep( $value );
		return array( $new, $new !== $value );
	}
	return array( $raw, false );
}

/**
 * One-shot DB scrub for critical bad hosts. Safe for serialized options/meta.
 *
 * @return string[] Log lines.
 */
function storeplus_child_critical_scrub_database() {
	global $wpdb;
	$log   = array();
	$fixed = 0;

	/* Posts: content + excerpt via API; guid via $wpdb (wp_update_post ignores guid). */
	$ids = $wpdb->get_col(
		"SELECT ID FROM {$wpdb->posts} WHERE post_content LIKE '%ngrok%' OR post_content LIKE '%LINKE-JADID%' OR post_content LIKE '%rastadarou.local%' OR guid LIKE '%ngrok%' OR guid LIKE '%LINKE-JADID%' OR guid LIKE '%rastadarou.local%' OR post_excerpt LIKE '%ngrok%' OR post_excerpt LIKE '%LINKE-JADID%'"
	);
	foreach ( (array) $ids as $id ) {
		$post = get_post( (int) $id );
		if ( ! $post ) {
			continue;
		}
		$update = array( 'ID' => (int) $id );
		$dirty  = false;
		foreach ( array( 'post_content', 'post_excerpt' ) as $field ) {
			list( $new, $changed ) = storeplus_child_critical_scrub_value( $post->$field );
			if ( $changed ) {
				$update[ $field ] = $new;
				$dirty            = true;
			}
		}
		if ( $dirty ) {
			wp_update_post( $update );
			$fixed++;
		}
		list( $new_guid, $guid_changed ) = storeplus_child_critical_scrub_value( $post->guid );
		if ( $guid_changed ) {
			$wpdb->update( $wpdb->posts, array( 'guid' => $new_guid ), array( 'ID' => (int) $id ) );
			$fixed++;
		}
	}
	$log[] = 'posts scrubbed: ' . $fixed;

	/* Post meta. */
	$pm = 0;
	$rows = $wpdb->get_results(
		"SELECT meta_id, meta_value FROM {$wpdb->postmeta} WHERE meta_value LIKE '%ngrok%' OR meta_value LIKE '%LINKE-JADID%' OR meta_value LIKE '%rastadarou.local%'"
	);
	foreach ( (array) $rows as $row ) {
		list( $new, $changed ) = storeplus_child_critical_scrub_value( $row->meta_value );
		if ( $changed ) {
			$wpdb->update( $wpdb->postmeta, array( 'meta_value' => $new ), array( 'meta_id' => (int) $row->meta_id ) );
			$pm++;
		}
	}
	$log[] = 'postmeta scrubbed: ' . $pm;

	/* Options (skip siteurl/home — Local dynamic URL owns those). */
	$op = 0;
	$rows = $wpdb->get_results(
		"SELECT option_id, option_name, option_value FROM {$wpdb->options} WHERE option_value LIKE '%ngrok%' OR option_value LIKE '%LINKE-JADID%' OR option_value LIKE '%rastadarou.local%'"
	);
	foreach ( (array) $rows as $row ) {
		if ( in_array( $row->option_name, array( 'siteurl', 'home' ), true ) ) {
			continue;
		}
		list( $new, $changed ) = storeplus_child_critical_scrub_value( $row->option_value );
		if ( $changed ) {
			$wpdb->update( $wpdb->options, array( 'option_value' => $new ), array( 'option_id' => (int) $row->option_id ) );
			$op++;
		}
	}
	$log[] = 'options scrubbed: ' . $op;

	/* Term meta. */
	$tm = 0;
	$rows = $wpdb->get_results(
		"SELECT meta_id, meta_value FROM {$wpdb->termmeta} WHERE meta_value LIKE '%ngrok%' OR meta_value LIKE '%LINKE-JADID%' OR meta_value LIKE '%rastadarou.local%'"
	);
	foreach ( (array) $rows as $row ) {
		list( $new, $changed ) = storeplus_child_critical_scrub_value( $row->meta_value );
		if ( $changed ) {
			$wpdb->update( $wpdb->termmeta, array( 'meta_value' => $new ), array( 'meta_id' => (int) $row->meta_id ) );
			$tm++;
		}
	}
	$log[] = 'termmeta scrubbed: ' . $tm;

	/* Drop stale menu / brand caches so they rebuild on current host. */
	delete_transient( 'sp_catmenu_blueprint_v1' );
	delete_transient( 'sp_catmenu_blueprint_v2' );
	delete_transient( 'storeplus_child_brands_v2' );
	if ( function_exists( 'sp_catmenu_flush_cache' ) ) {
		sp_catmenu_flush_cache();
	}
	if ( function_exists( 'storeplus_child_flush_brands_cache' ) ) {
		storeplus_child_flush_brands_cache();
	}
	$log[] = 'transients flushed (catmenu + brands)';

	/* LiteSpeed / object cache if present. */
	if ( function_exists( 'wp_cache_flush' ) ) {
		wp_cache_flush();
		$log[] = 'object cache flushed';
	}
	if ( class_exists( 'LiteSpeed\Purge', false ) ) {
		try {
			\LiteSpeed\Purge::purge_all();
			$log[] = 'LiteSpeed purge_all';
		} catch ( Throwable $e ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedCatch
			// ignore.
		}
	}

	update_option( 'storeplus_child_critical_scrubbed_at', gmdate( 'c' ), false );
	return $log;
}
