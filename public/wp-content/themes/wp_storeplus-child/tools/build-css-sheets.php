<?php
require __DIR__ . '/_boot.php';

/**
 * Build stable CSS sheets from parts/ (tokens kept; scattered overrides merged).
 *
 * Usage (Local PHP):
 *   php tools/build-css-sheets.php
 *
 * Product card: parts/product-card.css → product-card.css (single source; no historical onion).
 * Standalone sync: discount-badge, price-unified, mini-card-cleanup, slider-corners
 *   are copied from parts/ so root never drifts.
 *
 * Safe !important trim (assembled sheets only): drops !important on cosmetic props when
 * the selector already includes #irantheme- / html / body / :root. Layout/visibility props kept.
 *
 * @package StorePlus_Child
 */

$root  = dirname( __DIR__ );
$css   = $root . '/assets/css';
$parts = $css . '/parts';

if ( ! is_dir( $parts ) ) {
	fwrite( STDERR, "Missing parts dir: $parts\n" );
	exit( 1 );
}

/**
 * Sheet manifest: stable file => ordered part basenames (no .css).
 *
 * @var array<string, string[]>
 */
$manifest = array(
	'tokens'        => array( 'space-8', 'text-colors' ),
	'site-critical' => array(
		'page-background',
		'main-margin',
		'responsive-container',
		'no-page-x-scroll',
		'remove-topbar',
		'header-compact',
		'sticky-header',
		'navbar-mobile-flush',
		'full-width-hero-header',
		'remove-service-strip',
	),
	'site-suppress' => array(
		'remove-gallery-social',
		'remove-wishlist',
		'remove-english-badges',
		'remove-footer-app',
		'remove-footer-extras',
	),
	'site-chrome'   => array(
		'header-scroll-border',
		'mini-profile',
		'mega-menu-mask',
		'menu-rearrange',
		'card-hover-border',
		'cta-system',
	),
	'site-footer'   => array( 'footer-rearrange' ),
	'home-critical' => array( 'home-skeleton' ),
	'home'          => array(
		'hero-ratio',
		'banner-two',
		'banner-ratio',
		'category-arrows',
		'brands-logo-only',
		'brands-swipe',
		'brands-marquee',
		'landing-polish',
	),
	'home-rails'    => array(
		'discount-badge',
		'offers-strip',
		'offers-frame',
		'category-card-gradient',
		'category-card-fixed',
		'bestsellers-panel-lite',
		'hide-empty-sections',
		'mini-card-cleanup',
		'slider-corners',
		'price-unified',
	),
	'blog'          => array( 'post-cards', 'blog-latest-three' ),
	'shop'          => array( 'shop-reference', 'shop-redesign', 'brand-chips', 'active-filter-chips' ),
	/* Canonical product card — not layered with home-rails parts. */
	'product-card'  => array( 'product-card' ),
);

/**
 * Parts mirrored to css/{name}.css for direct enqueue (perf-assets / commerce extras).
 *
 * @var string[]
 */
$sync_standalones = array(
	'discount-badge',
	'price-unified',
	'mini-card-cleanup',
	'slider-corners',
);

/**
 * Properties that must keep !important (parent theme / inline / FOUC).
 *
 * @var string[]
 */
$keep_important_props = array(
	'display',
	'visibility',
	'opacity',
	'position',
	'inset',
	'top',
	'right',
	'bottom',
	'left',
	'z-index',
	'content',
	'pointer-events',
	'clip',
	'clip-path',
	'transform',
	'width',
	'min-width',
	'max-width',
	'height',
	'min-height',
	'max-height',
	'padding',
	'padding-top',
	'padding-right',
	'padding-bottom',
	'padding-left',
	'padding-block',
	'padding-inline',
	'margin',
	'margin-top',
	'margin-right',
	'margin-bottom',
	'margin-left',
	'margin-block',
	'margin-inline',
	'flex',
	'flex-grow',
	'flex-shrink',
	'flex-basis',
	'flex-direction',
	'flex-wrap',
	'gap',
	'row-gap',
	'column-gap',
	'grid',
	'grid-template-columns',
	'grid-template-rows',
	'background',
	'background-color',
	'background-image',
	'border',
	'border-color',
	'border-width',
	'border-style',
	'border-radius',
	'border-top',
	'border-right',
	'border-bottom',
	'border-left',
	'box-shadow',
	'filter',
	'overflow',
	'overflow-x',
	'overflow-y',
	'color',
	'font-size',
	'font-weight',
	'line-height',
	'text-decoration',
	'text-decoration-line',
	'text-decoration-color',
	'text-decoration-thickness',
	'direction',
	'text-align',
	'aspect-ratio',
	'object-fit',
	'object-position',
	'animation',
);

/**
 * Cosmetic props safe to drop !important when selector is already hot.
 *
 * @var string[]
 */
$trim_important_props = array(
	'transition',
	'transition-property',
	'transition-duration',
	'transition-timing-function',
	'transition-delay',
	'will-change',
	'backface-visibility',
	'appearance',
	'-webkit-appearance',
	'user-select',
	'-webkit-user-select',
	'letter-spacing',
	'word-spacing',
	'text-transform',
	'white-space',
	'touch-action',
	'scroll-behavior',
	'caret-color',
	'outline-offset',
);

/**
 * @param string   $css        CSS.
 * @param string[] $trim_props Props allowed to lose !important.
 * @param string[] $keep_props Props that must never lose !important.
 * @return array{0:string,1:int}
 */
function sp_css_trim_cosmetic_important( $css, array $trim_props, array $keep_props ) {
	$removed = 0;
	$trim    = array_map( 'strtolower', $trim_props );
	$keep    = array_map( 'strtolower', $keep_props );

	$out = preg_replace_callback(
		'/([^{}]+)\{([^{}]*)\}/s',
		static function ( $m ) use ( &$removed, $trim, $keep ) {
			$selector = $m[1];
			$body     = $m[2];
			$hot      = (bool) preg_match( '/(#irantheme-[\w-]+|\bhtml\b|\bbody\b|:root)/i', $selector );
			if ( ! $hot || false === stripos( $body, '!important' ) ) {
				return $m[0];
			}

			$body2 = preg_replace_callback(
				'/([a-zA-Z-]+)\s*:\s*([^;]*?)\s*!important\s*(;?)/',
				static function ( $d ) use ( &$removed, $trim, $keep ) {
					$prop = strtolower( trim( $d[1] ) );
					if ( in_array( $prop, $keep, true ) || ! in_array( $prop, $trim, true ) ) {
						return $d[0];
					}
					++$removed;
					$semi = '' !== $d[3] ? $d[3] : ';';
					return $d[1] . ': ' . trim( $d[2] ) . $semi;
				},
				$body
			);

			return $selector . '{' . $body2 . '}';
		},
		$css
	);

	return array( is_string( $out ) ? $out : $css, $removed );
}

/**
 * @param string $parts_dir Parts dir.
 * @param string $name      Basename.
 * @return string
 */
function sp_part_path( $parts_dir, $name ) {
	$p = $parts_dir . '/' . $name . '.css';
	if ( file_exists( $p ) ) {
		return $p;
	}
	$fallback = dirname( $parts_dir ) . '/' . $name . '.css';
	return file_exists( $fallback ) ? $fallback : '';
}

$total_in_imp  = 0;
$total_out_imp = 0;
$total_trimmed = 0;

foreach ( $manifest as $sheet => $part_names ) {
	$chunks   = array();
	$chunks[] = "/**\n * Stable sheet: {$sheet}.css\n * Built by tools/build-css-sheets.php — edit parts/ then rebuild.\n * Do not hand-edit this file for long-lived changes.\n */\n";

	foreach ( $part_names as $name ) {
		$path = sp_part_path( $parts, $name );
		if ( ! $path ) {
			fwrite( STDERR, "WARN missing part: {$name} (sheet {$sheet})\n" );
			continue;
		}
		$raw = file_get_contents( $path );
		if ( false === $raw ) {
			fwrite( STDERR, "WARN unreadable: {$path}\n" );
			continue;
		}
		$total_in_imp += substr_count( strtolower( $raw ), '!important' );
		$chunks[]      = "\n/* ========== part: {$name}.css ========== */\n" . rtrim( $raw ) . "\n";
	}

	$merged = implode( '', $chunks );
	list( $trimmed, $n ) = sp_css_trim_cosmetic_important( $merged, $trim_important_props, $keep_important_props );
	$total_trimmed += $n;
	$total_out_imp += substr_count( strtolower( $trimmed ), '!important' );

	$dest = $css . '/' . $sheet . '.css';
	if ( false === file_put_contents( $dest, $trimmed ) ) {
		fwrite( STDERR, "FAIL write {$dest}\n" );
		exit( 1 );
	}
	$kb = round( strlen( $trimmed ) / 1024, 1 );
	echo "OK  {$sheet}.css  {$kb}KB  trimmed_cosmetic_important={$n}\n";
}

/* Sync standalones from parts → css root (avoid drift duplicates). */
echo "--- sync standalones from parts ---\n";
foreach ( $sync_standalones as $name ) {
	$src = sp_part_path( $parts, $name );
	if ( ! $src ) {
		fwrite( STDERR, "WARN missing standalone part: {$name}\n" );
		continue;
	}
	$raw = file_get_contents( $src );
	if ( false === $raw ) {
		continue;
	}
	$banner = "/**\n * Synced from parts/{$name}.css by tools/build-css-sheets.php — edit the part, then rebuild.\n */\n";
	$dest   = $css . '/' . $name . '.css';
	file_put_contents( $dest, $banner . $raw );
	echo "OK  sync {$name}.css\n";
}

/* Legacy stub: product-card-final (do not enqueue; canonical is product-card.css). */
$final_alias = $parts . '/product-card-final.css';
file_put_contents(
	$final_alias,
	"/**\n * DEPRECATED — do not enqueue.\n * Canonical: parts/product-card.css → assets/css/product-card.css\n */\n"
);
echo "OK  parts/product-card-final.css stub (deprecated)\n";

/* Legacy full bestsellers panel: not enqueued; home uses bestsellers-panel-lite via home-rails. */
$bs = $css . '/bestsellers-panel.css';
if ( file_exists( $bs ) ) {
	$head = file_get_contents( $bs, false, null, 0, 400 );
	if ( is_string( $head ) && false === strpos( $head, 'LEGACY UNUSED' ) ) {
		$raw = file_get_contents( $bs );
		file_put_contents(
			$bs,
			"/* LEGACY UNUSED on front — home loads bestsellers-panel-lite via home-rails.css.\n * Kept for reference / emergency rollback only. Do not enqueue.\n */\n" . $raw
		);
		echo "OK  marked bestsellers-panel.css as legacy unused\n";
	}
}

echo "---\n";
echo "parts !important counted:  {$total_in_imp}\n";
echo "sheets !important out:     {$total_out_imp}\n";
echo "cosmetic !important trims: {$total_trimmed}\n";
echo "DONE\n";
