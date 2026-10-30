<?php
/**
 * Post cards: no excerpt / author / comment / read-more in markup,
 * and card shell CSS is critical on the front page (no FOUC).
 *
 * @package StorePlus_Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Inline blog.css with critical CSS on the front page so cards never flash
 * parent-theme layout before the deferred blog bundle arrives.
 *
 * @param string[] $names Critical sheet basenames.
 * @return string[]
 */
function storeplus_child_post_cards_critical_css( $names ) {
	if ( ! is_array( $names ) ) {
		$names = array();
	}
	if ( function_exists( 'is_front_page' ) && is_front_page() && ! in_array( 'blog', $names, true ) ) {
		$names[] = 'blog';
	}
	return $names;
}
add_filter( 'storeplus_child_perf_critical_css', 'storeplus_child_post_cards_critical_css' );

/**
 * CSS class tokens to remove from post cards (excerpt / meta / theme read-more).
 *
 * @return string[]
 */
function storeplus_child_post_cards_strip_classes() {
	return array(
		'irantheme-post-simple-item-content-description',
		'irantheme-post-box-item-content-description',
		'irantheme-post-simple-item-content-read-more',
		'irantheme-post-box-item-content-read-more',
		'irantheme-post-simple-item-content-more-details',
	);
}

/**
 * Build day/month date-badge markup for a post (matches parent theme structure).
 *
 * @param int $post_id Post ID.
 * @return string
 */
function storeplus_child_post_cards_date_badge_html( $post_id ) {
	$post_id = (int) $post_id;
	if ( $post_id <= 0 ) {
		return '';
	}

	$day   = get_the_date( 'j', $post_id );
	$month = get_the_date( 'F', $post_id );
	if ( ! is_string( $day ) || '' === $day || ! is_string( $month ) || '' === $month ) {
		return '';
	}

	return '<div class="irantheme-post-simple-item-date white position-up-right sp-post-date-badge" aria-label="'
		. esc_attr( trim( $day . ' ' . $month ) ) . '">'
		. '<span class="irantheme-post-simple-item-date-day">' . esc_html( $day ) . '</span>'
		. '<span class="irantheme-post-simple-item-date-month">' . esc_html( $month ) . '</span>'
		. '</div>';
}

/**
 * Resolve post ID from a card fragment (permalink in title/image link).
 *
 * @param string $fragment Card markup.
 * @return int
 */
function storeplus_child_post_cards_resolve_post_id( $fragment ) {
	if ( ! is_string( $fragment ) || '' === $fragment ) {
		return 0;
	}

	if ( preg_match(
		'#<h3\b[^>]*\birantheme-post-(?:simple|box)-item-content-title\b[^>]*>\s*<a\b[^>]*\bhref=(["\'])(.*?)\1#iu',
		$fragment,
		$m
	) ) {
		$url = html_entity_decode( $m[2], ENT_QUOTES | ENT_HTML5, 'UTF-8' );
		$id  = url_to_postid( $url );
		if ( $id > 0 ) {
			return (int) $id;
		}
	}

	if ( preg_match(
		'#<div\b[^>]*\birantheme-post-(?:simple|box)-item-img\b[^>]*>\s*<a\b[^>]*\bhref=(["\'])(.*?)\1#iu',
		$fragment,
		$m
	) ) {
		$url = html_entity_decode( $m[2], ENT_QUOTES | ENT_HTML5, 'UTF-8' );
		$id  = url_to_postid( $url );
		if ( $id > 0 ) {
			return (int) $id;
		}
	}

	return 0;
}

/**
 * Ensure a date badge sits on the media tile (inject when parent omitted it).
 *
 * Parent skips the badge when image visibility is on but the post has no
 * thumbnail — common for the grey 16:9 placeholder cards.
 *
 * @param string $fragment Card markup.
 * @return string
 */
function storeplus_child_post_cards_ensure_date_badge( $fragment ) {
	if ( ! is_string( $fragment ) || '' === $fragment ) {
		return $fragment;
	}
	if ( false !== stripos( $fragment, 'irantheme-post-simple-item-date' )
		|| false !== stripos( $fragment, 'irantheme-post-box-item-date' ) ) {
		return $fragment;
	}

	$post_id = storeplus_child_post_cards_resolve_post_id( $fragment );
	$badge   = storeplus_child_post_cards_date_badge_html( $post_id );
	if ( '' === $badge ) {
		return $fragment;
	}

	/* Prefer inside the image wrapper (absolute top-right of media). */
	$next = preg_replace(
		'#(<div\b[^>]*\birantheme-post-simple-item-img-wrapper\b[^>]*>)#iu',
		'$1' . $badge,
		$fragment,
		1
	);
	if ( is_string( $next ) && $next !== $fragment ) {
		return $next;
	}

	/* Without-image: place as first child of the inner wrapper (CSS pins to media well). */
	$next = preg_replace(
		'#(<div\b[^>]*\birantheme-post-simple-item-inner-wrapper\b[^>]*>)#iu',
		'$1' . $badge,
		$fragment,
		1
	);
	return ( is_string( $next ) && '' !== $next ) ? $next : $fragment;
}

/**
 * Strip strip-classes from a single card HTML fragment (safe DOM scope).
 *
 * @param string $fragment Card markup.
 * @return string
 */
function storeplus_child_post_cards_strip_fragment( $fragment ) {
	if ( ! is_string( $fragment ) || '' === $fragment ) {
		return $fragment;
	}

	$flat = array(
		'#<p\b[^>]*\b(?:irantheme-post-simple-item-content-description|irantheme-post-box-item-content-description)\b[^>]*>[\s\S]*?</p>#iu',
		'#<a\b[^>]*\b(?:irantheme-post-simple-item-content-read-more|irantheme-post-box-item-content-read-more)\b[^>]*>[\s\S]*?</a>#iu',
	);
	foreach ( $flat as $re ) {
		$next = preg_replace( $re, '', $fragment );
		if ( is_string( $next ) ) {
			$fragment = $next;
		}
	}

	if ( false !== stripos( $fragment, 'irantheme-post-simple-item-content-more-details' )
		&& class_exists( 'DOMDocument' ) ) {
		$prev    = libxml_use_internal_errors( true );
		$dom     = new DOMDocument();
		$wrapped = '<?xml encoding="utf-8"><div id="sp-pc-frag">' . $fragment . '</div>';
		$ok      = $dom->loadHTML( $wrapped, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
		libxml_clear_errors();
		libxml_use_internal_errors( $prev );
		if ( $ok ) {
			$xpath = new DOMXPath( $dom );
			$nodes = $xpath->query( '//*[contains(concat(" ", normalize-space(@class), " "), " irantheme-post-simple-item-content-more-details ")]' );
			if ( $nodes ) {
				$remove = array();
				foreach ( $nodes as $node ) {
					$remove[] = $node;
				}
				foreach ( $remove as $node ) {
					if ( $node->parentNode ) {
						$node->parentNode->removeChild( $node );
					}
				}
			}

			$root = $dom->getElementById( 'sp-pc-frag' );
			if ( $root ) {
				$out = '';
				foreach ( $root->childNodes as $child ) {
					$out .= $dom->saveHTML( $child );
				}
				if ( is_string( $out ) && '' !== $out ) {
					$fragment = $out;
				}
			}
		}
	}

	return storeplus_child_post_cards_ensure_date_badge( $fragment );
}

/**
 * Drop excerpt / read-more / author-comment blocks from post-simple (and post-box) cards,
 * and ensure each card has a date badge on the media tile.
 *
 * @param string $html Full page or fragment HTML.
 * @return string
 */
function storeplus_child_post_cards_strip_html( $html ) {
	if ( ! is_string( $html ) || '' === $html ) {
		return $html;
	}

	$has_cards = ( false !== stripos( $html, 'irantheme-post-simple-item' )
		|| false !== stripos( $html, 'irantheme-post-box-item' ) );
	if ( ! $has_cards ) {
		return $html;
	}

	/* Per-article strip + date badge keeps the full document intact. */
	$next = preg_replace_callback(
		'#<article\b[^>]*\b(?:irantheme-post-simple-item|irantheme-post-box-item)\b[^>]*>[\s\S]*?</article>#iu',
		static function ( $m ) {
			return storeplus_child_post_cards_strip_fragment( $m[0] );
		},
		$html
	);
	if ( is_string( $next ) && '' !== $next ) {
		$html = $next;
	}

	/* Flat safety net for description / read-more left outside <article>. */
	foreach ( array(
		'#<p\b[^>]*\b(?:irantheme-post-simple-item-content-description|irantheme-post-box-item-content-description)\b[^>]*>[\s\S]*?</p>#iu',
		'#<a\b[^>]*\b(?:irantheme-post-simple-item-content-read-more|irantheme-post-box-item-content-read-more)\b[^>]*>[\s\S]*?</a>#iu',
	) as $re ) {
		$flat = preg_replace( $re, '', $html );
		if ( is_string( $flat ) ) {
			$html = $flat;
		}
	}

	return $html;
}

/**
 * Home front buffer: strip card meta after hero/banner swaps + inject date badges.
 *
 * @param string $html Document HTML.
 * @return string
 */
function storeplus_child_post_cards_home_transform( $html ) {
	return storeplus_child_post_cards_strip_html( $html );
}
add_filter( 'storeplus_child_home_front_html', 'storeplus_child_post_cards_home_transform', 20 );
