<?php
/**
 * Landing-page skeleton shells that mirror the live home layout
 * (hero 3:1, category tiles, product cards, brands logos, blog cards, offers frame).
 *
 * Parent theme calls irantheme_get_skeleton_html(); that delegates here when we return true.
 * Keep wrapper class .irantheme-skeleton-loader so the parent's removeSkeleton() JS still works.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Map builder column class to a bone count.
 *
 * @param string $col_count Column class (col-3 … col-12).
 * @param string $type      Skeleton type.
 * @return int
 */
function storeplus_child_skeleton_count( $col_count, $type ) {
	if ( 'slider' === $type ) {
		return 1;
	}

	$map = array(
		'col-12' => 12,
		'col-8'  => 8,
		'col-6'  => 6,
		'col-5'  => 5,
		'col-4'  => 4,
		'col-3'  => 3,
	);

	return isset( $map[ $col_count ] ) ? (int) $map[ $col_count ] : 4;
}

/**
 * Map builder column class to data-columns value.
 *
 * @param string $col_count Column class.
 * @return int
 */
function storeplus_child_skeleton_columns( $col_count ) {
	$map = array(
		'col-12' => 12,
		'col-8'  => 8,
		'col-6'  => 6,
		'col-5'  => 5,
		'col-4'  => 4,
		'col-3'  => 3,
	);

	return isset( $map[ $col_count ] ) ? (int) $map[ $col_count ] : 4;
}

/**
 * Markup for one bone cell.
 *
 * Returns a string so callers that already run inside an output-buffer
 * callback (bestsellers / shop) never call ob_start(). PHP fatals on
 * ob_start() inside a display handler and discards the whole page.
 *
 * @param string $type Skeleton type from the parent section.
 * @return string
 */
function storeplus_child_skeleton_bone_html( $type ) {
	switch ( $type ) {
		case 'slider':
			return '<div class="sp-skel sp-skel--hero" aria-hidden="true"><div class="sp-skel__shimmer sp-skel__hero"></div></div>';

		case 'image_medium':
			return '<div class="sp-skel sp-skel--cat" aria-hidden="true"><div class="sp-skel__shimmer sp-skel__thumb"></div><div class="sp-skel__shimmer sp-skel__line sp-skel__line--cat"></div></div>';

		case 'text':
			return '<div class="sp-skel sp-skel--brand" aria-hidden="true"><div class="sp-skel__shimmer sp-skel__logo"></div></div>';

		case 'thumbnail':
			return '<div class="sp-skel sp-skel--thumb" aria-hidden="true"><div class="sp-skel__shimmer sp-skel__dot"></div></div>';

		case 'card':
		case 'product':
		default:
			return '<div class="sp-skel sp-skel--product" aria-hidden="true"><div class="sp-skel__shimmer sp-skel__media"></div><div class="sp-skel__body"><div class="sp-skel__shimmer sp-skel__line sp-skel__line--t1"></div><div class="sp-skel__shimmer sp-skel__line sp-skel__line--t2"></div><div class="sp-skel__foot"><div class="sp-skel__shimmer sp-skel__cart"></div><div class="sp-skel__price-col"><div class="sp-skel__shimmer sp-skel__line sp-skel__line--p1"></div><div class="sp-skel__shimmer sp-skel__line sp-skel__line--p2"></div></div></div></div></div>';
	}
}

/**
 * Render one bone cell for the given type.
 *
 * @param string $type Skeleton type from the parent section.
 */
function storeplus_child_skeleton_bone( $type ) {
	echo storeplus_child_skeleton_bone_html( $type ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static skeleton markup.
}

/**
 * Homepage skeleton HTML. Returns true when rendered (parent must stop).
 *
 * @param string $col_count        Column class.
 * @param string $type             Skeleton type.
 * @param string $base_color_class Legacy colour class (kept on wrapper for compatibility).
 * @return bool
 */
function storeplus_child_get_skeleton_html( $col_count, $type, $base_color_class ) {
	/*
	 * Storefront uses the full-page logo loader (inc/page-loader.php).
	 * Returning true stops the parent theme from printing its own bones.
	 * The builder preview in wp-admin still gets the parent skeleton.
	 */
	if ( is_admin() && ! wp_doing_ajax() ) {
		return false;
	}
	unset( $col_count, $type, $base_color_class );
	return true;

	$type    = is_string( $type ) ? $type : 'card';
	$count   = storeplus_child_skeleton_count( $col_count, $type );
	$columns = storeplus_child_skeleton_columns( $col_count );
	$mods    = array( 'irantheme-skeleton-loader', 'sp-skel-loader' );

	if ( 'slider' === $type ) {
		$mods[] = 'skeleton-slider-mode';
		$mods[] = 'sp-skel-loader--hero';
	} elseif ( 'image_medium' === $type ) {
		$mods[] = 'sp-skel-loader--cats';
	} elseif ( 'text' === $type ) {
		$mods[] = 'sp-skel-loader--brands';
	} elseif ( 'thumbnail' === $type ) {
		$mods[] = 'sp-skel-loader--thumbs';
	} else {
		$mods[] = 'sp-skel-loader--products';
	}

	if ( is_string( $base_color_class ) && '' !== $base_color_class ) {
		$mods[] = $base_color_class;
	}
	?>
	<div class="<?php echo esc_attr( implode( ' ', $mods ) ); ?>" role="status" aria-live="polite" aria-label="<?php echo esc_attr( 'در حال بارگذاری' ); ?>">
		<div class="irantheme-skeleton-grid sp-skel-grid" data-columns="<?php echo (int) $columns; ?>" data-type="<?php echo esc_attr( $type ); ?>">
			<?php
			for ( $i = 0; $i < $count; $i++ ) {
				storeplus_child_skeleton_bone( $type );
			}
			?>
		</div>
	</div>
	<?php

	return true;
}

/**
 * Mini-products (bestsellers) has no native skeleton in the parent theme.
 * Inject the same shell pattern as offers: skeleton loader + side-load content.
 */
function storeplus_child_bestsellers_skel_buffer_start() {
	if ( is_admin() || wp_doing_ajax() || ! is_front_page() ) {
		return;
	}
	ob_start( 'storeplus_child_bestsellers_skel_buffer_end' );
}
/* Bestsellers are server-rendered. The page loader covers them; do not inject bones. */

/**
 * @param string $html Full page HTML.
 * @return string
 */
function storeplus_child_bestsellers_skel_buffer_end( $html ) {
	if ( ! is_string( $html ) || '' === $html ) {
		return $html;
	}
	$original = $html;
	if ( false === strpos( $html, 'irantheme-woocommerce-mini-products-section' ) ) {
		return $html;
	}
	if ( false !== strpos( $html, 'data-sp-bs-skel' ) ) {
		return $html;
	}

	if ( ! preg_match(
		'/<section\b[^>]*\birantheme-woocommerce-mini-products-section\b[^>]*>.*?<\/section>/is',
		$html,
		$m
	) ) {
		return $html;
	}

	$section = $m[0];

	/* Mark section for parent removeSkeleton(). */
	if ( false === strpos( $section, 'irantheme-skeleton-section' ) ) {
		$section = preg_replace(
			'/\bclass=(["\'])([^"\']*\birantheme-woocommerce-mini-products-section\b[^"\']*)\1/i',
			'class=$1$2 irantheme-skeleton-section$1',
			$section,
			1
		);
	}

	/* Hide real swiper until skeleton is cleared. */
	if ( false === strpos( $section, 'irantheme-side-load-skeleton' ) ) {
		$section = preg_replace(
			'/(<div\b[^>]*\birantheme-woocommerce-product-swiper\b[^>]*\bclass=)(["\'])([^"\']*)\2/i',
			'$1$2$3 irantheme-side-load-skeleton$2',
			$section,
			1
		);
	}

	$bones = '';
	for ( $i = 0; $i < 4; $i++ ) {
		$bones .= storeplus_child_skeleton_bone_html( 'product' );
	}
	$skel = '<div class="irantheme-skeleton-loader sp-skel-loader sp-skel-loader--products" data-sp-bs-skel="1" role="status" aria-live="polite" aria-label="' . esc_attr( 'در حال بارگذاری' ) . '">'
		. '<div class="irantheme-skeleton-grid sp-skel-grid" data-columns="4" data-type="card">'
		. $bones
		. '</div></div>';

	if ( false === strpos( $section, 'data-sp-bs-skel' ) ) {
		$section = preg_replace(
			'/(<div\b[^>]*\birantheme-woocommerce-product-swiper\b)/i',
			$skel . '$1',
			$section,
			1
		);
	}

	$next = str_replace( $m[0], $section, $html );
	if ( '' === $next || false === stripos( $next, '<body' ) ) {
		return $original;
	}
	return $next;
}

