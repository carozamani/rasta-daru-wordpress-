<?php
/**
 * Footer layout + content — PHP (no DOM surgery).
 *
 * Applies the same contract as footer-rearrange.js + footer-content.js:
 *  - swap contact / useful-links columns
 *  - inject guide (extra) link column
 *  - move social icons into the copyright bar; badges into the old social column
 *  - fill quick links / contact / social from «محتوای فوتر»
 *
 * Runs inside the existing site-footer output buffer. Marks the footer ready
 * so FOUC CSS and the legacy JS become no-ops.
 *
 * @package StorePlus_Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Whether PHP footer rendering is enabled.
 *
 * @return bool
 */
function storeplus_child_footer_php_enabled() {
	return (bool) apply_filters( 'storeplus_child_footer_php_enabled', true );
}

/**
 * Find real site footers with balanced matching.
 *
 * Page Builder injects nested <footer class="irantheme-page-builder-hover-*">
 * nodes inside badges; a non-greedy regex cuts the fragment early and drops
 * the copyright bar — so social→copyright rearrange never runs.
 *
 * @param string $html Full page (or fragment) HTML.
 * @return array<int,array{start:int,length:int,html:string}>
 */
function storeplus_child_footer_find_sections( $html ) {
	$out = array();
	if ( ! is_string( $html ) || '' === $html ) {
		return $out;
	}

	$len    = strlen( $html );
	$offset = 0;
	$open_re = '/<footer\b[^>]*\birantheme-footer-(?:nav|classic)-section\b[^>]*>/i';

	while ( $offset < $len && preg_match( $open_re, $html, $m, PREG_OFFSET_CAPTURE, $offset ) ) {
		$start = (int) $m[0][1];
		$pos   = $start + strlen( $m[0][0] );
		/*
		 * Stack of open <footer> kinds. Page Builder injects nested
		 * <footer class="irantheme-page-builder-*"> (and stray closes) inside
		 * badges; a plain depth counter hits 0 too early and drops the copyright bar.
		 */
		$stack = array( 'site' );
		$skips = 0;

		while ( $pos < $len && $stack ) {
			$next_open  = stripos( $html, '<footer', $pos );
			$next_close = stripos( $html, '</footer>', $pos );

			if ( false === $next_close ) {
				$offset = $len;
				break 2;
			}

			if ( false !== $next_open && $next_open < $next_close
				&& preg_match( '/^<footer\b[^>]*>/i', substr( $html, $next_open, 512 ), $om ) ) {
				$open_tag = $om[0];
				$kind     = ( false !== stripos( $open_tag, 'page-builder' ) ) ? 'pb' : 'site';
				$stack[]  = $kind;
				$pos      = $next_open + strlen( $open_tag );
				continue;
			}

			array_pop( $stack );
			$pos = $next_close + 9; /* strlen('</footer>') */
			if ( ! $stack ) {
				$candidate = substr( $html, $start, $pos - $start );
				/*
				 * Stray </footer> inside badges can empty the stack before the
				 * copyright bar. If the bar is missing, ignore this close.
				 */
				if ( $skips < 80
					&& false === stripos( $candidate, 'footer-nav-copyright' )
					&& false === stripos( $candidate, 'footer-classic-copyright' ) ) {
					$stack[] = 'site';
					++$skips;
					continue;
				}
				$out[]  = array(
					'start'  => $start,
					'length' => $pos - $start,
					'html'   => $candidate,
				);
				$offset = $pos;
				break;
			}
		}

		if ( $stack ) {
			break;
		}
	}

	return $out;
}

/**
 * Build social media <li> inner HTML from admin config.
 *
 * @param array $list Items from spfc_front_config()['social'].
 * @return string
 */
function storeplus_child_footer_php_social_lis_html( array $list ) {
	$html = '';
	foreach ( $list as $s ) {
		if ( ! is_array( $s ) ) {
			continue;
		}
		$url   = isset( $s['url'] ) ? esc_url( $s['url'] ) : '#';
		$label = isset( $s['label'] ) ? (string) $s['label'] : '';
		$attrs = 'href="' . $url . '"';
		if ( $label ) {
			$attrs .= ' aria-label="' . esc_attr( $label ) . '"';
		}
		if ( $url && preg_match( '#^https?://#i', $url ) ) {
			$attrs .= ' target="_blank" rel="noopener"';
		}
		$inner = '';
		if ( ! empty( $s['img'] ) ) {
			$inner = '<img src="' . esc_url( $s['img'] ) . '" alt="' . esc_attr( $label ) . '">';
		} elseif ( ! empty( $s['svg'] ) ) {
			/* Trusted SVG from spfc_aparat_svg() only. */
			$inner = (string) $s['svg'];
		} elseif ( ! empty( $s['icon'] ) ) {
			$inner = '<i class="' . esc_attr( $s['icon'] ) . '"></i>';
		}
		$html .= '<li><a ' . $attrs . '>' . $inner . '</a></li>';
	}
	return $html;
}

/**
 * Useful-links list markup for a given prefix.
 *
 * @param string $p     Prefix (irantheme-footer-nav|classic).
 * @param array  $links [ ['text'=>,'url'=>], ... ].
 * @return string
 */
function storeplus_child_footer_php_links_ul( $p, array $links ) {
	$ul = '<ul class="' . esc_attr( $p ) . '-useful-links-list">';
	foreach ( $links as $item ) {
		if ( ! is_array( $item ) ) {
			continue;
		}
		$text = isset( $item['text'] ) ? (string) $item['text'] : '';
		$url  = isset( $item['url'] ) ? esc_url( $item['url'] ) : '#';
		if ( '' === $text ) {
			continue;
		}
		$ul .= '<li class="' . esc_attr( $p ) . '-useful-links-list-item">'
			. '<a class="' . esc_attr( $p ) . '-useful-links-list-item-link" href="' . $url . '">'
			. esc_html( $text )
			. '</a></li>';
	}
	$ul .= '</ul>';
	return $ul;
}

/**
 * Whether a badge object node is Samandehi / namad-e-reza (not Enamad).
 *
 * @param DOMElement $el Badge .irantheme-object node.
 * @return bool
 */
function storeplus_child_footer_php_is_samandehi_badge( DOMElement $el ) {
	$parts = array(
		(string) $el->getAttribute( 'id' ),
		(string) $el->getAttribute( 'class' ),
		(string) $el->getAttribute( 'src' ),
		(string) $el->getAttribute( 'href' ),
	);
	foreach ( $el->getElementsByTagName( '*' ) as $child ) {
		if ( ! ( $child instanceof DOMElement ) ) {
			continue;
		}
		$parts[] = (string) $child->getAttribute( 'src' );
		$parts[] = (string) $child->getAttribute( 'href' );
		$parts[] = (string) $child->getAttribute( 'id' );
		$parts[] = (string) $child->getAttribute( 'data-src' );
	}
	$hay = strtolower( implode( ' ', $parts ) );
	if ( false !== strpos( $hay, 'enamad' ) || false !== strpos( $hay, 'e-namad' ) ) {
		return false;
	}
	return ( false !== strpos( $hay, 'samandehi' )
		|| false !== strpos( $hay, 'rezi' )
		|| false !== strpos( $hay, 'namadreza' )
		|| false !== strpos( $hay, 'logo.samandehi' ) );
}

/**
 * Strip page-builder chrome / nested landmark tags from a badge node.
 * Nested <footer>/<header> inside badges break DOMDocument and drop later columns.
 * Also removes Samandehi (نماد رضا) so only Enamad (نماد اعتماد) remains.
 *
 * @param DOMElement $badge Badge wrapper.
 * @return void
 */
function storeplus_child_footer_php_sanitize_badge( DOMElement $badge ) {
	$kill = array();
	foreach ( $badge->getElementsByTagName( '*' ) as $el ) {
		if ( ! ( $el instanceof DOMElement ) ) {
			continue;
		}
		$tag = strtolower( $el->tagName );
		$cls = (string) $el->getAttribute( 'class' );
		if ( false !== strpos( $cls, 'irantheme-page-builder-action-object' )
			|| false !== strpos( $cls, 'irantheme-page-builder-hover-object' )
			|| 'header' === $tag
			|| 'footer' === $tag
			|| 'nav' === $tag ) {
			$kill[] = $el;
			continue;
		}
		/* Direct badge tiles (.irantheme-object) that are Samandehi / rezi. */
		if ( preg_match( '/(?:^|\s)irantheme-object(?:\s|$)/', $cls )
			&& storeplus_child_footer_php_is_samandehi_badge( $el ) ) {
			$kill[] = $el;
		}
	}
	foreach ( $kill as $el ) {
		if ( $el->parentNode ) {
			$el->parentNode->removeChild( $el );
		}
	}

	/* Keep Enamad only: if any enamad tile remains, drop every other badge tile. */
	$tiles      = array();
	$has_enamad = false;
	foreach ( $badge->getElementsByTagName( '*' ) as $el ) {
		if ( ! ( $el instanceof DOMElement ) ) {
			continue;
		}
		$cls = (string) $el->getAttribute( 'class' );
		if ( ! preg_match( '/(?:^|\s)irantheme-object(?:\s|$)/', $cls ) ) {
			continue;
		}
		$tiles[] = $el;
		if ( ! storeplus_child_footer_php_is_samandehi_badge( $el ) ) {
			$blob = strtolower(
				(string) $el->getAttribute( 'id' ) . ' ' .
				(string) $el->getAttribute( 'src' ) . ' ' .
				(string) $el->getAttribute( 'href' )
			);
			foreach ( $el->getElementsByTagName( '*' ) as $child ) {
				if ( $child instanceof DOMElement ) {
					$blob .= ' ' . strtolower( (string) $child->getAttribute( 'src' ) );
					$blob .= ' ' . strtolower( (string) $child->getAttribute( 'href' ) );
					$blob .= ' ' . strtolower( (string) $child->getAttribute( 'id' ) );
				}
			}
			if ( false !== strpos( $blob, 'enamad' ) || false !== strpos( $blob, 'e-namad' ) ) {
				$has_enamad = true;
			}
		}
	}
	if ( $has_enamad && count( $tiles ) > 1 ) {
		foreach ( $tiles as $el ) {
			$blob = strtolower( (string) $el->getAttribute( 'id' ) );
			foreach ( $el->getElementsByTagName( '*' ) as $child ) {
				if ( $child instanceof DOMElement ) {
					$blob .= ' ' . strtolower( (string) $child->getAttribute( 'src' ) );
					$blob .= ' ' . strtolower( (string) $child->getAttribute( 'href' ) );
					$blob .= ' ' . strtolower( (string) $child->getAttribute( 'id' ) );
				}
			}
			$is_enamad = ( false !== strpos( $blob, 'enamad' ) || false !== strpos( $blob, 'e-namad' ) );
			if ( ! $is_enamad && $el->parentNode ) {
				$el->parentNode->removeChild( $el );
			}
		}
	}
}

/**
 * Strip page-builder edit chrome (+ button, hover separators) from a footer subtree.
 *
 * @param DOMElement $root Footer (or wrap) element.
 * @return void
 */
function storeplus_child_footer_php_strip_builder_chrome( DOMElement $root ) {
	$kill = array();
	foreach ( $root->getElementsByTagName( '*' ) as $el ) {
		if ( ! ( $el instanceof DOMElement ) ) {
			continue;
		}
		$cls = (string) $el->getAttribute( 'class' );
		if ( false !== strpos( $cls, 'irantheme-page-builder-action-object' )
			|| false !== strpos( $cls, 'irantheme-page-builder-hover-object' ) ) {
			$kill[] = $el;
		}
	}
	foreach ( $kill as $el ) {
		if ( $el->parentNode ) {
			$el->parentNode->removeChild( $el );
		}
	}
}

/**
 * Remove newsletter title + form from a footer subtree (theme leftovers).
 *
 * @param DOMElement $root Subtree root (footer or column).
 * @param string     $p    Prefix (irantheme-footer-nav|classic).
 * @return void
 */
function storeplus_child_footer_php_strip_newsletter_from( DOMElement $root, $p ) {
	$forms = storeplus_child_footer_php_find_all_class( $root, 'irantheme-communication-newsletter-form' );
	foreach ( $forms as $form ) {
		$prev = $form->previousSibling;
		while ( $prev && ! ( $prev instanceof DOMElement ) ) {
			$prev = $prev->previousSibling;
		}
		if ( $prev instanceof DOMElement ) {
			$cls = (string) $prev->getAttribute( 'class' );
			if ( false !== strpos( $cls, $p . '-title' ) || false !== strpos( $cls, 'footer-nav-title' ) ) {
				$prev->parentNode->removeChild( $prev );
			}
		}
		if ( $form->parentNode ) {
			$form->parentNode->removeChild( $form );
		}
	}
}

/**
 * Contact details inner HTML.
 *
 * @param array $contact Contact config.
 * @return string
 */
function storeplus_child_footer_php_contact_html( array $contact ) {
	$html = '';
	if ( ! empty( $contact['intro'] ) ) {
		$html .= '<p>' . esc_html( (string) $contact['intro'] ) . '</p>';
	}
	foreach ( (array) ( $contact['rows'] ?? array() ) as $row ) {
		if ( ! is_array( $row ) ) {
			continue;
		}
		$label = isset( $row['label'] ) ? (string) $row['label'] : '';
		$value = isset( $row['value'] ) ? (string) $row['value'] : '';
		$text  = ( $label && $value ) ? ( $label . ' : ' . $value ) : ( $label ? $label : $value );
		if ( '' === $text ) {
			continue;
		}
		$html .= '<p>' . esc_html( $text ) . '</p>';
	}
	return $html;
}

/**
 * Ensure / update a column title <b class="{p}-title">.
 *
 * @param DOMDocument $dom DOM.
 * @param DOMElement  $col Column.
 * @param string      $p   Prefix.
 * @param string      $text Title text (empty = remove).
 */
function storeplus_child_footer_php_set_title( DOMDocument $dom, DOMElement $col, $p, $text ) {
	$title = null;
	foreach ( $col->childNodes as $child ) {
		if ( $child instanceof DOMElement && $child->getAttribute( 'class' ) && false !== strpos( $child->getAttribute( 'class' ), $p . '-title' ) ) {
			$title = $child;
			break;
		}
	}
	$text = trim( (string) $text );
	if ( '' === $text ) {
		if ( $title && $title->parentNode ) {
			$title->parentNode->removeChild( $title );
		}
		return;
	}
	if ( ! $title ) {
		$title = $dom->createElement( 'b' );
		$title->setAttribute( 'class', $p . '-title' );
		if ( $col->firstChild ) {
			$col->insertBefore( $title, $col->firstChild );
		} else {
			$col->appendChild( $title );
		}
	}
	while ( $title->firstChild ) {
		$title->removeChild( $title->firstChild );
	}
	$title->appendChild( $dom->createTextNode( $text ) );
}

/**
 * Import an HTML fragment into $dom and return the first element child.
 *
 * @param DOMDocument $dom  DOM.
 * @param string      $html Fragment.
 * @return DOMNode|null
 */
function storeplus_child_footer_php_import_html( DOMDocument $dom, $html ) {
	if ( '' === $html ) {
		return null;
	}
	$tmp = new DOMDocument();
	libxml_use_internal_errors( true );
	$wrapped = '<?xml encoding="utf-8"><div id="sp-frag">' . $html . '</div>';
	$tmp->loadHTML( $wrapped, LIBXML_HTML_NODEFDTD );
	libxml_clear_errors();
	$frag_root = $tmp->getElementById( 'sp-frag' );
	if ( ! $frag_root ) {
		return null;
	}
	$imported = null;
	foreach ( iterator_to_array( $frag_root->childNodes ) as $child ) {
		$node = $dom->importNode( $child, true );
		if ( ! $imported ) {
			$imported = $node;
		}
	}
	return $imported;
}

/**
 * Replace all children of $el with HTML fragment.
 *
 * @param DOMDocument $dom  DOM.
 * @param DOMElement  $el   Element.
 * @param string      $html HTML.
 */
function storeplus_child_footer_php_set_inner_html( DOMDocument $dom, DOMElement $el, $html ) {
	while ( $el->firstChild ) {
		$el->removeChild( $el->firstChild );
	}
	if ( '' === $html ) {
		return;
	}
	$tmp = new DOMDocument();
	libxml_use_internal_errors( true );
	$tmp->loadHTML( '<?xml encoding="utf-8"><div id="sp-frag">' . $html . '</div>', LIBXML_HTML_NODEFDTD );
	libxml_clear_errors();
	$frag_root = $tmp->getElementById( 'sp-frag' );
	if ( ! $frag_root ) {
		return;
	}
	foreach ( iterator_to_array( $frag_root->childNodes ) as $child ) {
		$el->appendChild( $dom->importNode( $child, true ) );
	}
}

/**
 * Closest ancestor with class containing $class_needle.
 *
 * @param DOMNode|null $node Node.
 * @param string       $class_needle Class substring.
 * @return DOMElement|null
 */
function storeplus_child_footer_php_closest_class( $node, $class_needle ) {
	while ( $node ) {
		if ( $node instanceof DOMElement ) {
			$class = $node->getAttribute( 'class' );
			if ( $class && false !== strpos( $class, $class_needle ) ) {
				return $node;
			}
		}
		$node = $node->parentNode;
	}
	return null;
}

/**
 * Query first descendant whose class list contains $class_needle.
 *
 * @param DOMElement $root Root.
 * @param string     $class_needle Class.
 * @return DOMElement|null
 */
function storeplus_child_footer_php_find_class( DOMElement $root, $class_needle ) {
	$xpath = new DOMXPath( $root->ownerDocument );
	$nodes = $xpath->query( './/*[contains(concat(" ", normalize-space(@class), " "), " ' . $class_needle . ' ")]', $root );
	if ( $nodes && $nodes->length ) {
		$el = $nodes->item( 0 );
		return $el instanceof DOMElement ? $el : null;
	}
	return null;
}

/**
 * All descendants with exact class token.
 *
 * @param DOMElement $root Root.
 * @param string     $class_needle Class.
 * @return DOMElement[]
 */
function storeplus_child_footer_php_find_all_class( DOMElement $root, $class_needle ) {
	$xpath = new DOMXPath( $root->ownerDocument );
	$nodes = $xpath->query( './/*[contains(concat(" ", normalize-space(@class), " "), " ' . $class_needle . ' ")]', $root );
	$out   = array();
	if ( $nodes ) {
		foreach ( $nodes as $n ) {
			if ( $n instanceof DOMElement ) {
				$out[] = $n;
			}
		}
	}
	return $out;
}

/**
 * Transform one footer element (nav or classic).
 *
 * @param DOMDocument $dom    Document.
 * @param DOMElement  $footer Footer element.
 * @param string      $p      Prefix.
 * @param array       $cfg    spfc_front_config().
 * @param array       $extra  Extra columns from filter.
 * @return void
 */
function storeplus_child_footer_php_transform_one( DOMDocument $dom, DOMElement $footer, $p, array $cfg, array $extra ) {
	/* Drop page-builder + / hover chrome leaked into front HTML. */
	storeplus_child_footer_php_strip_builder_chrome( $footer );

	/* ----- content: quick links ----- */
	if ( ! empty( $cfg['quick'] ) && is_array( $cfg['quick'] ) ) {
		$blocks = storeplus_child_footer_php_find_all_class( $footer, $p . '-useful-links' );
		$block  = null;
		foreach ( $blocks as $b ) {
			$col = storeplus_child_footer_php_closest_class( $b, $p . '-col' );
			if ( $col && false !== strpos( $col->getAttribute( 'class' ), 'footer-extra-col' ) ) {
				continue;
			}
			$block = $b;
			break;
		}
		if ( $block ) {
			$col = storeplus_child_footer_php_closest_class( $block, $p . '-col' );
			$q   = $cfg['quick'];
			if ( empty( $q['links'] ) ) {
				if ( $col ) {
					$col->setAttribute( 'style', trim( ( $col->getAttribute( 'style' ) ? $col->getAttribute( 'style' ) . ';' : '' ) . 'display:none' ) );
				}
			} else {
				if ( $col ) {
					$style = preg_replace( '/display\s*:\s*none\s*;?/i', '', (string) $col->getAttribute( 'style' ) );
					$col->setAttribute( 'style', trim( $style, " \t;" ) );
					if ( '' === $col->getAttribute( 'style' ) ) {
						$col->removeAttribute( 'style' );
					}
				}
				storeplus_child_footer_php_set_inner_html( $dom, $block, storeplus_child_footer_php_links_ul( $p, $q['links'] ) );
				if ( $col ) {
					storeplus_child_footer_php_set_title( $dom, $col, $p, $q['title'] ?? '' );
				}
			}
		}
	}

	/* ----- content: contact ----- */
	if ( ! empty( $cfg['contact'] ) && is_array( $cfg['contact'] ) ) {
		$details = storeplus_child_footer_php_find_class( $footer, $p . '-details' );
		if ( $details ) {
			$col = storeplus_child_footer_php_closest_class( $details, $p . '-col' );
			$c   = $cfg['contact'];
			$rows = (array) ( $c['rows'] ?? array() );
			if ( empty( $c['intro'] ) && ! $rows ) {
				if ( $col ) {
					$col->setAttribute( 'style', trim( ( $col->getAttribute( 'style' ) ? $col->getAttribute( 'style' ) . ';' : '' ) . 'display:none' ) );
				}
			} else {
				if ( $col ) {
					$style = preg_replace( '/display\s*:\s*none\s*;?/i', '', (string) $col->getAttribute( 'style' ) );
					$col->setAttribute( 'style', trim( $style, " \t;" ) );
					if ( '' === $col->getAttribute( 'style' ) ) {
						$col->removeAttribute( 'style' );
					}
				}
				storeplus_child_footer_php_set_inner_html( $dom, $details, storeplus_child_footer_php_contact_html( $c ) );
				if ( $col ) {
					storeplus_child_footer_php_set_title( $dom, $col, $p, $c['title'] ?? '' );
				}
			}
		}
	}

	/* ----- content: social ----- */
	if ( isset( $cfg['social'] ) && is_array( $cfg['social'] ) ) {
		$socials = storeplus_child_footer_php_find_all_class( $footer, 'irantheme-social-media' );
		$lis     = storeplus_child_footer_php_social_lis_html( $cfg['social'] );
		foreach ( $socials as $ul ) {
			if ( empty( $cfg['social'] ) ) {
				$ul->setAttribute( 'style', trim( ( $ul->getAttribute( 'style' ) ? $ul->getAttribute( 'style' ) . ';' : '' ) . 'display:none' ) );
				storeplus_child_footer_php_set_inner_html( $dom, $ul, '' );
			} else {
				$style = preg_replace( '/display\s*:\s*none\s*;?/i', '', (string) $ul->getAttribute( 'style' ) );
				$ul->setAttribute( 'style', trim( $style, " \t;" ) );
				if ( '' === $ul->getAttribute( 'style' ) ) {
					$ul->removeAttribute( 'style' );
				}
				storeplus_child_footer_php_set_inner_html( $dom, $ul, $lis );
			}
		}
	}

	/* ----- swap details <-> useful links ----- */
	if ( ! $footer->hasAttribute( 'data-footer-cols-swapped' ) ) {
		$links   = storeplus_child_footer_php_find_class( $footer, $p . '-useful-links' );
		$details = storeplus_child_footer_php_find_class( $footer, $p . '-details' );
		if ( $links && $details ) {
			$links_col   = storeplus_child_footer_php_closest_class( $links, $p . '-col' );
			$details_col = storeplus_child_footer_php_closest_class( $details, $p . '-col' );
			if ( $links_col && $details_col && $links_col !== $details_col && $links_col->parentNode === $details_col->parentNode ) {
				$footer->setAttribute( 'data-footer-cols-swapped', '1' );
				$links_col->parentNode->insertBefore( $details_col, $links_col );
			}
		}
	}

	/* ----- extra guide column(s) ----- */
	if ( ! $footer->hasAttribute( 'data-footer-extra-cols' ) && $extra ) {
		$links = storeplus_child_footer_php_find_class( $footer, $p . '-useful-links' );
		$links_col = $links ? storeplus_child_footer_php_closest_class( $links, $p . '-col' ) : null;
		if ( $links_col && $links_col->parentNode ) {
			$footer->setAttribute( 'data-footer-extra-cols', '1' );
			$ref = $links_col;
			foreach ( $extra as $c ) {
				if ( empty( $c['links'] ) || ! is_array( $c['links'] ) ) {
					continue;
				}
				$clone = $links_col->cloneNode( true );
				if ( ! $clone instanceof DOMElement ) {
					continue;
				}
				$class = trim( $clone->getAttribute( 'class' ) . ' footer-extra-col' );
				$clone->setAttribute( 'class', $class );

				$wrap = storeplus_child_footer_php_find_class( $clone, $p . '-useful-links' );
				if ( ! $wrap ) {
					continue;
				}
				storeplus_child_footer_php_set_inner_html( $dom, $wrap, storeplus_child_footer_php_links_ul( $p, $c['links'] ) );

				/* Remove any cloned title; set from config. */
				foreach ( iterator_to_array( $clone->childNodes ) as $child ) {
					if ( $child instanceof DOMElement && false !== strpos( $child->getAttribute( 'class' ), $p . '-title' ) ) {
						$clone->removeChild( $child );
					}
				}
				if ( ! empty( $c['title'] ) ) {
					storeplus_child_footer_php_set_title( $dom, $clone, $p, (string) $c['title'] );
				}

				if ( $ref->nextSibling ) {
					$ref->parentNode->insertBefore( $clone, $ref->nextSibling );
				} else {
					$ref->parentNode->appendChild( $clone );
				}
				$ref = $clone;
			}
		}
	}

	/* ----- social → copyright bar; badges → old social column; strip newsletter ----- */
	if ( ! $footer->hasAttribute( 'data-footer-rearranged' ) ) {
		$social    = storeplus_child_footer_php_find_class( $footer, 'irantheme-social-media' );
		$badge     = storeplus_child_footer_php_find_class( $footer, $p . '-badge' );
		$copyright = storeplus_child_footer_php_find_class( $footer, $p . '-copyright' );

		/* Page Builder often disables the copyright strip — recreate it for the reference layout. */
		if ( ! $copyright ) {
			$shell = storeplus_child_footer_php_find_class( $footer, $p );
			if ( ! $shell ) {
				$shell = $footer;
			}
			$copyright = $dom->createElement( 'div' );
			$copyright->setAttribute( 'class', $p . '-copyright' );
			$inner = $dom->createElement( 'div' );
			$inner->setAttribute( 'class', 'irantheme-container-inner' );
			$text = $dom->createElement( 'div' );
			$text->setAttribute( 'class', $p . '-copyright-text' );
			$p_el = $dom->createElement( 'p' );
			$year = gmdate( 'Y' );
			$p_el->appendChild(
				$dom->createTextNode(
					sprintf(
						/* translators: %s: calendar year */
						'کلیه حقوق مادی و معنوی این سایت محفوظ است %s رستا دارو',
						$year
					)
				)
			);
			$text->appendChild( $p_el );
			$inner->appendChild( $text );
			$copyright->appendChild( $inner );
			$shell->appendChild( $copyright );
		}

		if ( $copyright ) {
			$footer->setAttribute( 'data-footer-rearranged', '1' );
			$social_col = $social ? storeplus_child_footer_php_closest_class( $social, $p . '-col' ) : null;

			if ( $social ) {
				$prev = $social->previousSibling;
				while ( $prev && ! ( $prev instanceof DOMElement ) ) {
					$prev = $prev->previousSibling;
				}
				if ( $prev instanceof DOMElement && false !== strpos( $prev->getAttribute( 'class' ), $p . '-title' ) ) {
					$prev->parentNode->removeChild( $prev );
				}

				$host = storeplus_child_footer_php_find_class( $copyright, 'irantheme-container-inner' );
				if ( ! $host ) {
					$host = $copyright;
				}
				$host->appendChild( $social );
			}

			if ( $badge ) {
				storeplus_child_footer_php_sanitize_badge( $badge );
			}

			/*
			 * Enamad gets its own column as the LAST child of the columns row, so contact,
			 * link columns and the trust badge sit in one flex row (space-between).
			 */
			$details     = storeplus_child_footer_php_find_class( $footer, $p . '-details' );
			$contact_col = $details ? storeplus_child_footer_php_closest_class( $details, $p . '-col' ) : null;
			if ( $badge && $contact_col && $contact_col->parentNode ) {
				$badge_col = $dom->createElement( 'div' );
				$badge_col->setAttribute( 'class', $p . '-col footer-badges-col' );
				$badge_col->appendChild( $badge );
				$contact_col->parentNode->appendChild( $badge_col );
			} elseif ( $badge && $social_col ) {
				$class = trim( $social_col->getAttribute( 'class' ) . ' footer-badges-col' );
				$social_col->setAttribute( 'class', $class );
				$social_col->appendChild( $badge );
			}

			if ( $social_col ) {
				storeplus_child_footer_php_strip_newsletter_from( $social_col, $p );
				/* Social moved out — hide the emptied column so it does not take a grid cell. */
				if ( ! $social_col->hasChildNodes() || '' === trim( $social_col->textContent ) ) {
					$social_col->setAttribute(
						'class',
						trim( str_replace( 'footer-badges-col', '', $social_col->getAttribute( 'class' ) ) )
					);
				}
			}
		}
	}

	/* Always strip newsletter chrome if theme re-injects it. */
	storeplus_child_footer_php_strip_newsletter_from( $footer, $p );
	$badge_now = storeplus_child_footer_php_find_class( $footer, $p . '-badge' );
	if ( $badge_now ) {
		storeplus_child_footer_php_sanitize_badge( $badge_now );
	}

	/*
	 * FOUC: content/columns are ready (data-footer-ready).
	 * Full PHP ownership (data-sp-php-footer) only when social→copyright is done,
	 * or there is no social block to move. Otherwise legacy JS finishes the move
	 * on the live DOM (it skips swap/extra when those data-* flags are already set).
	 */
	$footer->setAttribute( 'data-footer-ready', '1' );
	$has_social = (bool) storeplus_child_footer_php_find_class( $footer, 'irantheme-social-media' );
	if ( $footer->hasAttribute( 'data-footer-rearranged' ) || ! $has_social ) {
		$footer->setAttribute( 'data-sp-php-footer', '1' );
	}
}

/**
 * Transform a single footer HTML fragment; returns null on failure.
 *
 * @param string $footer_html Footer markup.
 * @param array  $cfg         Front config.
 * @param array  $extra       Extra cols.
 * @return string|null
 */
/**
 * Neutralize nested landmark tags inside a footer fragment (keep the outer <footer>).
 * Page Builder injects nested <footer>/<header> in badges; DOMDocument then drops sibling columns.
 *
 * @param string $html Footer fragment starting with <footer …>.
 * @return string
 */
function storeplus_child_footer_php_neutralize_inner_landmarks( $html ) {
	if ( ! is_string( $html ) || '' === $html || ! preg_match( '/^(\s*<footer\b[^>]*>)/i', $html, $open ) ) {
		return $html;
	}
	$open_tag = $open[1];
	$rest     = substr( $html, strlen( $open_tag ) );
	/* Closing outer footer is the last </footer>. */
	$last = strripos( $rest, '</footer>' );
	if ( false === $last ) {
		return $html;
	}
	$inner = substr( $rest, 0, $last );
	$close = substr( $rest, $last );
	$inner = preg_replace( '/<(footer|header|nav)(\s[^>]*)?>/i', '<div$2 data-sp-was="$1">', $inner );
	$inner = preg_replace( '/<\/(footer|header|nav)>/i', '</div>', $inner );
	/* Drop page-builder action chrome (+ button) and hover separators — front only. */
	$inner = preg_replace(
		'/<header\b[^>]*irantheme-page-builder-action-object(?:-group)?-button-wrapper[\s\S]*?<\/header>/i',
		'',
		$inner
	);
	$inner = preg_replace(
		'/<div\b[^>]*irantheme-page-builder-action-object(?:-group)?-button-wrapper[\s\S]*?<\/div>\s*<\/div>/i',
		'',
		$inner
	);
	$inner = preg_replace(
		'/<div\b[^>]*irantheme-page-builder-hover-object(?:-group)?-separator[^>]*>\s*(?:<div\b[^>]*>\s*<\/div>\s*)*<\/div>/i',
		'',
		$inner
	);
	return $open_tag . $inner . $close;
}

function storeplus_child_footer_php_transform_fragment( $footer_html, array $cfg, array $extra ) {
	$footer_html = storeplus_child_footer_php_neutralize_inner_landmarks( $footer_html );
	$dom = new DOMDocument();
	libxml_use_internal_errors( true );
	$ok = $dom->loadHTML(
		'<?xml encoding="utf-8"><div id="sp-footer-wrap">' . $footer_html . '</div>',
		LIBXML_HTML_NODEFDTD
	);
	libxml_clear_errors();
	if ( ! $ok ) {
		return null;
	}

	$wrap = $dom->getElementById( 'sp-footer-wrap' );
	if ( ! $wrap ) {
		return null;
	}

	$prefixes = array( 'irantheme-footer-nav', 'irantheme-footer-classic' );
	$touched  = false;
	foreach ( $prefixes as $p ) {
		foreach ( storeplus_child_footer_php_find_all_class( $wrap, $p . '-section' ) as $footer ) {
			if ( 'footer' !== strtolower( $footer->tagName ) ) {
				continue;
			}
			storeplus_child_footer_php_transform_one( $dom, $footer, $p, $cfg, $extra );
			$touched = true;
		}
	}
	if ( ! $touched ) {
		return null;
	}

	$out = '';
	foreach ( iterator_to_array( $wrap->childNodes ) as $child ) {
		$out .= $dom->saveHTML( $child );
	}
	return is_string( $out ) && '' !== $out ? $out : null;
}

/**
 * Apply PHP footer transform to full page HTML (footer fragments only — never re-serializes the whole page).
 *
 * @param string $html Page HTML.
 * @return string
 */
function storeplus_child_footer_php_apply( $html ) {
	if ( ! storeplus_child_footer_php_enabled() || ! is_string( $html ) || '' === $html ) {
		return $html;
	}
	if ( false === stripos( $html, 'irantheme-footer-nav-section' )
		&& false === stripos( $html, 'irantheme-footer-classic-section' ) ) {
		return $html;
	}

	$sections = storeplus_child_footer_find_sections( $html );
	if ( ! $sections ) {
		return $html;
	}

	$cfg   = function_exists( 'spfc_front_config' ) ? spfc_front_config() : array();
	$extra = apply_filters( 'storeplus_child_footer_extra_cols', array() );
	if ( ! is_array( $extra ) ) {
		$extra = array();
	}

	$touched = false;
	/* Replace from the end so earlier offsets stay valid. */
	for ( $i = count( $sections ) - 1; $i >= 0; $i-- ) {
		$sec = $sections[ $i ];
		if ( false !== strpos( $sec['html'], 'data-sp-php-footer="1"' ) ) {
			continue;
		}
		$transformed = storeplus_child_footer_php_transform_fragment( $sec['html'], $cfg, $extra );
		if ( ! is_string( $transformed ) || '' === $transformed ) {
			continue;
		}
		$html    = substr_replace( $html, $transformed, $sec['start'], $sec['length'] );
		$touched = true;
	}

	if ( ! $touched || ! is_string( $html ) ) {
		return $html;
	}

	/* html.sp-footer-ready — FOUC CSS already honors this. */
	if ( false === strpos( $html, 'sp-footer-ready' ) ) {
		$html = preg_replace_callback(
			'/<html\b([^>]*)>/i',
			static function ( $m ) {
				$attrs = $m[1];
				if ( preg_match( '/\bclass\s*=\s*"([^"]*)"/i', $attrs, $cm ) ) {
					$attrs = preg_replace(
						'/\bclass\s*=\s*"[^"]*"/i',
						'class="' . trim( $cm[1] . ' sp-footer-ready' ) . '"',
						$attrs,
						1
					);
				} elseif ( preg_match( "/\bclass\s*=\s*'([^']*)'/i", $attrs, $cm ) ) {
					$attrs = preg_replace(
						"/\bclass\s*=\s*'[^']*'/i",
						"class='" . trim( $cm[1] . ' sp-footer-ready' ) . "'",
						$attrs,
						1
					);
				} else {
					$attrs .= ' class="sp-footer-ready"';
				}
				return '<html' . $attrs . '>';
			},
			$html,
			1
		);
	}

	return $html;
}
