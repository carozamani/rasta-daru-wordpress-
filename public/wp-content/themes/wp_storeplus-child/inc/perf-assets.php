<?php
/**
 * Professional front-end asset pipeline.
 *
 * - Scope-correct CSS/JS (front page also loads the lean product-card.css from parts/)
 * - Minified on-disk bundles with content hash
 * - Critical CSS inlined; non-critical CSS loaded async (media=print onload)
 * - Deferred JS; optional JS scope bundles
 * - Soft dequeues + LCP preload
 *
 * @package StorePlus_Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Pipeline on/off. */
function storeplus_child_perf_enabled() {
	return (bool) apply_filters( 'storeplus_child_perf_enabled', true );
}

function storeplus_child_perf_cache_dir() {
	return trailingslashit( WP_CONTENT_DIR ) . 'cache/storeplus-child';
}

function storeplus_child_perf_cache_url( $filename ) {
	return trailingslashit( content_url( 'cache/storeplus-child' ) ) . ltrim( $filename, '/' );
}

function storeplus_child_perf_ensure_cache_dir() {
	$dir = storeplus_child_perf_cache_dir();
	if ( ! is_dir( $dir ) ) {
		wp_mkdir_p( $dir );
	}
	$index = $dir . '/index.php';
	if ( is_dir( $dir ) && ! file_exists( $index ) ) {
		file_put_contents( $index, "<?php\n// Silence.\n" ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents
	}
	return is_dir( $dir ) && is_writable( $dir );
}

/**
 * Rewrite relative url(...) in a CSS file so paths still resolve after the
 * file is copied into wp-content/cache/storeplus-child/.
 *
 * Absolute / protocol-relative / data: / root-absolute URLs are left alone.
 *
 * @param string $css         Raw CSS.
 * @param string $source_path Absolute path of the CSS file on disk.
 * @return string
 */
function storeplus_child_perf_rewrite_css_urls( $css, $source_path ) {
	$base_dir  = wp_normalize_path( dirname( $source_path ) );
	$theme_dir = wp_normalize_path( STOREPLUS_CHILD_DIR );
	$theme_uri = untrailingslashit( STOREPLUS_CHILD_URI );

	return (string) preg_replace_callback(
		'/url\(\s*([\'"]?)([^\'")]+)\1\s*\)/i',
		static function ( $m ) use ( $base_dir, $theme_dir, $theme_uri ) {
			$url = trim( $m[2] );
			if ( '' === $url || preg_match( '~^(data:|https?:|//|/|#)~i', $url ) ) {
				return $m[0];
			}

			$parts = wp_parse_url( $url );
			$path  = isset( $parts['path'] ) ? (string) $parts['path'] : $url;
			$resolved = realpath( $base_dir . '/' . $path );
			if ( false === $resolved ) {
				return $m[0];
			}

			$resolved = wp_normalize_path( $resolved );
			if ( 0 !== strpos( $resolved, $theme_dir ) ) {
				return $m[0];
			}

			$rel = ltrim( substr( $resolved, strlen( $theme_dir ) ), '/' );
			$out = $theme_uri . '/' . str_replace( '\\', '/', $rel );
			if ( ! empty( $parts['query'] ) ) {
				$out .= '?' . $parts['query'];
			}
			if ( ! empty( $parts['fragment'] ) ) {
				$out .= '#' . $parts['fragment'];
			}

			return 'url("' . $out . '")';
		},
		$css
	);
}

/**
 * Conservative CSS minify (safe for calc(), urls, data-URIs).
 *
 * @param string $css Raw CSS.
 * @return string
 */
function storeplus_child_perf_minify_css( $css ) {
	$css = preg_replace( '!/\*.*?\*/!s', '', $css );
	/*
	 * Protect descendant combinator before :is() / :where().
	 * Without this, whitespace collapse can turn
	 * "body.home :is(.section)" into "body.home:is(.section)" (never matches).
	 * Do NOT touch :not() / :has() — those are usually element pseudos (div:not(.x)).
	 */
	$css = preg_replace( '/\s+:((?:is|where)\()/i', '§SPDESC§$1', $css );
	$css = preg_replace( '/\s+/', ' ', $css );
	/* Colon ":" omitted from strip set for the same reason. */
	$css = preg_replace( '/\s*([{};,>~+])\s*/', '$1', $css );
	$css = str_replace( '§SPDESC§', ' :', $css );
	$css = str_replace( array( ';}', '; }' ), '}', $css );
	return trim( (string) $css );
}

/**
 * Conservative JS minify (whitespace / comments only — no rename).
 *
 * @param string $js Raw JS.
 * @return string
 */
function storeplus_child_perf_minify_js( $js ) {
	$js = preg_replace( '!\s*/\*.*?\*/\s*!s', "\n", $js );
	$js = preg_replace( '/^\s*\/\/.*$/m', '', $js );
	$js = preg_replace( "/\n\s*\n+/", "\n", $js );
	$js = preg_replace( '/[ \t]+/', ' ', $js );
	return trim( (string) $js );
}

/**
 * @param string   $kind  css|js
 * @param string   $scope Scope key.
 * @param string[] $paths Absolute paths.
 * @return array{url:string,ver:string,bytes:int}|null
 */
function storeplus_child_perf_build_bundle( $kind, $scope, $paths ) {
	$paths = array_values(
		array_filter(
			$paths,
			static function ( $p ) {
				return is_string( $p ) && $p && file_exists( $p ) && filesize( $p ) > 0;
			}
		)
	);
	if ( ! $paths ) {
		return null;
	}

	$mtimes = array();
	foreach ( $paths as $p ) {
		$mtimes[] = (string) filemtime( $p );
	}
	$hash = substr( md5( $kind . '|' . $scope . '|' . implode( '|', $paths ) . '|' . implode( ',', $mtimes ) ), 0, 12 );
	$ext  = ( 'js' === $kind ) ? 'js' : 'css';
	$file = 'bundle-' . sanitize_key( $scope ) . '-' . $hash . '.' . $ext;
	$dest = storeplus_child_perf_cache_dir() . '/' . $file;

	if ( ! file_exists( $dest ) ) {
		if ( ! storeplus_child_perf_ensure_cache_dir() ) {
			return null;
		}
		$out = '';
		foreach ( $paths as $p ) {
			$raw = file_get_contents( $p ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
			if ( false === $raw ) {
				continue;
			}
			if ( 'js' === $kind ) {
				$out .= storeplus_child_perf_minify_js( $raw ) . "\n;";
			} else {
				$out .= storeplus_child_perf_minify_css( storeplus_child_perf_rewrite_css_urls( $raw, $p ) );
			}
		}
		$out = ( 'js' === $kind ) ? storeplus_child_perf_minify_js( $out ) : storeplus_child_perf_minify_css( $out );
		if ( false === file_put_contents( $dest, $out ) ) { // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents
			return null;
		}
		storeplus_child_perf_prune_old_bundles( $scope, $ext, $file );
	}

	return array(
		'url'   => storeplus_child_perf_cache_url( $file ),
		'ver'   => $hash,
		'bytes' => (int) filesize( $dest ),
	);
}

/**
 * Keep only the newest bundle per scope+ext (avoid cache bloat).
 *
 * @param string $scope Scope.
 * @param string $ext   css|js
 * @param string $keep  Filename to keep.
 */
function storeplus_child_perf_prune_old_bundles( $scope, $ext, $keep ) {
	$dir = storeplus_child_perf_cache_dir();
	$pre = 'bundle-' . sanitize_key( $scope ) . '-';
	foreach ( (array) glob( $dir . '/' . $pre . '*.' . $ext ) as $path ) {
		if ( basename( $path ) !== $keep ) {
			@unlink( $path ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		}
	}
}

/**
 * Resolve css path by handle name.
 *
 * @param string $name Name without .css.
 * @return string Absolute path or empty.
 */
function storeplus_child_perf_css_path( $name ) {
	$name = preg_replace( '/\.css$/', '', (string) $name );
	$path = STOREPLUS_CHILD_DIR . '/assets/css/' . $name . '.css';
	return ( file_exists( $path ) && filesize( $path ) > 0 ) ? $path : '';
}

/**
 * @param string $name Name without .js.
 * @return string
 */
function storeplus_child_perf_js_path( $name ) {
	$name = preg_replace( '/\.js$/', '', (string) $name );
	$path = STOREPLUS_CHILD_DIR . '/assets/js/' . $name . '.js';
	return ( file_exists( $path ) && filesize( $path ) > 0 ) ? $path : '';
}

/**
 * Critical chrome CSS names (blocking / inlined).
 *
 * @return string[]
 */
function storeplus_child_perf_critical_css_names() {
	$names = array(
		'tokens',        /* 8pt + ink — must paint before chrome that consumes them */
		'site-critical', /* above-fold chrome (built from parts/) */
	);
	if ( function_exists( 'is_front_page' ) && is_front_page() ) {
		$names[] = 'home-critical'; /* landing skeleton — first paint */
	}
	return apply_filters( 'storeplus_child_perf_critical_css', $names );
}

/**
 * Collect CSS groups for this request.
 *
 * Tiers:
 * - critical: above-fold chrome (inlined)
 * - site: remaining chrome (async)
 * - home / home_product / blog: front rails (async)
 * - commerce / shop / pdp: storefront (async except when needed)
 *
 * @return array<string, string[]>
 */
function storeplus_child_perf_collect_css_groups() {
	$groups = array();
	$add    = static function ( &$groups, $scope, $name ) {
		$path = storeplus_child_perf_css_path( $name );
		if ( ! $path ) {
			return;
		}
		if ( ! isset( $groups[ $scope ] ) ) {
			$groups[ $scope ] = array();
		}
		if ( ! in_array( $path, $groups[ $scope ], true ) ) {
			$groups[ $scope ][] = $path;
		}
	};

	$critical = storeplus_child_perf_critical_css_names();
	$files    = storeplus_child_files();

	foreach ( $files['css'] as $name => $scope ) {
		$match = storeplus_child_asset_scope_matches( $scope );
		/* Landing uses the same product-card.css as shop/PDP (not the old lite final sheet). */
		if ( ! $match && 'product-card' === $name && function_exists( 'is_front_page' ) && is_front_page() ) {
			$match = true;
			$scope = 'home_product';
		}
		if ( ! $match ) {
			continue;
		}
		/* Critical sheets (tokens / site-critical / home-critical) never go into deferred scopes. */
		if ( in_array( $name, $critical, true ) ) {
			$add( $groups, 'critical', $name );
			continue;
		}
		$add( $groups, $scope, $name );
	}

	foreach ( $critical as $name ) {
		if ( ! isset( $groups['critical'] ) || ! in_array( storeplus_child_perf_css_path( $name ), $groups['critical'], true ) ) {
			$add( $groups, 'critical', $name );
		}
	}

	/* Site deferred extras (not in the stable registry sheets). */
	if ( function_exists( 'storeplus_child_float_support_should_show' ) && storeplus_child_float_support_should_show() ) {
		$add( $groups, 'site', 'floating-support' );
	}

	if ( storeplus_child_asset_scope_matches( 'commerce' ) ) {
		$add( $groups, 'commerce', 'qty-stepper' );
		$add( $groups, 'commerce', 'discount-badge' );
	}

	if ( function_exists( 'is_product' ) && is_product() ) {
		$add( $groups, 'pdp', 'product-gallery' );
		$add( $groups, 'pdp', 'product-page' );
		$add( $groups, 'pdp', 'qty-stepper' );
		$add( $groups, 'pdp', 'price-unified' );
	}

	if ( function_exists( 'is_shop' ) && ( is_shop() || is_product_category() || is_product_tag() ) ) {
		$add( $groups, 'shop', 'shop' ); /* stable shop.css (reference + redesign + brand/active chips) */
	}

	/* Dedupe: never put critical files into other scopes again. */
	if ( ! empty( $groups['critical'] ) ) {
		foreach ( array( 'site', 'home', 'home_product', 'blog', 'commerce', 'shop', 'pdp' ) as $scope ) {
			if ( empty( $groups[ $scope ] ) ) {
				continue;
			}
			$groups[ $scope ] = array_values( array_diff( $groups[ $scope ], $groups['critical'] ) );
			if ( ! $groups[ $scope ] ) {
				unset( $groups[ $scope ] );
			}
		}
	}

	return apply_filters( 'storeplus_child_perf_css_groups', $groups );
}

/**
 * Inline critical CSS + register async bundles.
 */
function storeplus_child_perf_enqueue_css_bundles() {
	if ( is_admin() || ! storeplus_child_perf_enabled() ) {
		return;
	}

	$groups = storeplus_child_perf_collect_css_groups();
	if ( ! $groups ) {
		return;
	}

	/* Inline critical. */
	if ( ! empty( $groups['critical'] ) ) {
		$bundle = storeplus_child_perf_build_bundle( 'css', 'critical', $groups['critical'] );
		if ( $bundle ) {
			$path = storeplus_child_perf_cache_dir() . '/bundle-critical-' . $bundle['ver'] . '.css';
			$css  = file_exists( $path ) ? file_get_contents( $path ) : ''; // phpcs:ignore
			if ( $css ) {
				wp_register_style( 'storeplus-child-bundle-critical', false, array( 'irantheme-main-css', 'style' ), $bundle['ver'] );
				wp_enqueue_style( 'storeplus-child-bundle-critical' );
				wp_add_inline_style( 'storeplus-child-bundle-critical', $css );
			}
		}
		unset( $groups['critical'] );
	}

	$order = array( 'site', 'home_product', 'home', 'blog', 'commerce', 'shop', 'pdp' );
	$prev  = array( 'irantheme-main-css', 'style', 'storeplus-child-bundle-critical' );
	$seen  = array();

	foreach ( $order as $scope ) {
		if ( empty( $groups[ $scope ] ) ) {
			continue;
		}
		$bundle = storeplus_child_perf_build_bundle( 'css', $scope, $groups[ $scope ] );
		if ( ! $bundle ) {
			continue;
		}
		$handle = 'storeplus-child-bundle-' . $scope;
		wp_enqueue_style( $handle, $bundle['url'], $prev, $bundle['ver'] );
		$prev            = array( $handle );
		$seen[ $scope ] = true;
	}

	foreach ( $groups as $scope => $paths ) {
		if ( isset( $seen[ $scope ] ) ) {
			continue;
		}
		$bundle = storeplus_child_perf_build_bundle( 'css', $scope, $paths );
		if ( ! $bundle ) {
			continue;
		}
		$handle = 'storeplus-child-bundle-' . $scope;
		wp_enqueue_style( $handle, $bundle['url'], $prev, $bundle['ver'] );
		$prev = array( $handle );
	}
}
add_action( 'wp_enqueue_scripts', 'storeplus_child_perf_enqueue_css_bundles', 18 );

/**
 * Async-load only non-layout polish bundles.
 * Layout scopes (site/home/home_product/pdp/commerce/shop) stay render-blocking
 * to avoid FOUC and broken cascade — async was collapsing UI.
 *
 * @param string $html   Tag HTML.
 * @param string $handle Handle.
 * @param string $href   Href.
 * @param string $media  Media.
 * @return string
 */
function storeplus_child_perf_async_styles( $html, $handle, $href, $media ) {
	unset( $media );
	if ( is_admin() || ! storeplus_child_perf_enabled() ) {
		return $html;
	}
	if ( 0 !== strpos( $handle, 'storeplus-child-bundle-' ) ) {
		return $html;
	}
	/* Never async layout-critical scopes. */
	$sync = array(
		'storeplus-child-bundle-critical',
		'storeplus-child-bundle-site',
		'storeplus-child-bundle-home',
		'storeplus-child-bundle-home_product',
		'storeplus-child-bundle-pdp',
		'storeplus-child-bundle-commerce',
		'storeplus-child-bundle-shop',
	);
	if ( in_array( $handle, $sync, true ) ) {
		return $html;
	}
	$href = esc_url( $href );
	return '<link rel="preload" as="style" href="' . $href . '" />' . "\n" .
		'<link rel="stylesheet" id="' . esc_attr( $handle ) . '-css" href="' . $href . '" media="print" onload="this.media=\'all\'" />' . "\n" .
		'<noscript><link rel="stylesheet" href="' . $href . '" /></noscript>' . "\n";
}
add_filter( 'style_loader_tag', 'storeplus_child_perf_async_styles', 10, 4 );

/**
 * JS files for a scope (registry + extras).
 *
 * @param string $scope Scope.
 * @return string[] Absolute paths.
 */
function storeplus_child_perf_js_paths_for_scope( $scope ) {
	$paths   = array();
	$files   = storeplus_child_files();
	$skip_js = function_exists( 'storeplus_child_php_layout_skip_js_names' )
		? storeplus_child_php_layout_skip_js_names()
		: array();
	foreach ( $files['js'] as $name => $js_scope ) {
		if ( $js_scope !== $scope ) {
			continue;
		}
		if ( ! storeplus_child_asset_scope_matches( $scope ) ) {
			continue;
		}
		if ( in_array( $name, $skip_js, true ) ) {
			continue;
		}
		$p = storeplus_child_perf_js_path( $name );
		if ( $p ) {
			$paths[] = $p;
		}
	}

	if ( 'home' === $scope && is_front_page() ) {
		foreach ( array( 'bestsellers-after-latest', 'banner-slots-place' ) as $extra ) {
			if ( in_array( $extra, $skip_js, true ) ) {
				continue;
			}
			$p = storeplus_child_perf_js_path( $extra );
			if ( $p ) {
				$paths[] = $p;
			}
		}
	}

	if ( 'commerce' === $scope && storeplus_child_asset_scope_matches( 'commerce' ) ) {
		$p = storeplus_child_perf_js_path( 'discount-badge-top' );
		if ( $p && ! in_array( $p, $paths, true ) ) {
			$paths[] = $p;
		}
	}

	return array_values( array_unique( $paths ) );
}

/**
 * Replace per-file child JS with minified scope bundles (deferred).
 */
function storeplus_child_perf_enqueue_js_bundles() {
	if ( is_admin() || ! storeplus_child_perf_enabled() ) {
		return;
	}

	$scopes = array( 'site', 'home_product', 'home', 'commerce' );
	foreach ( $scopes as $scope ) {
		if ( ! storeplus_child_asset_scope_matches( $scope ) && ! ( 'home' === $scope && is_front_page() ) ) {
			continue;
		}
		/* home_product / home only on front. */
		if ( in_array( $scope, array( 'home', 'home_product' ), true ) && ! is_front_page() ) {
			continue;
		}
		$paths = storeplus_child_perf_js_paths_for_scope( $scope );
		if ( ! $paths ) {
			continue;
		}
		$bundle = storeplus_child_perf_build_bundle( 'js', $scope, $paths );
		if ( ! $bundle ) {
			continue;
		}
		wp_enqueue_script( 'storeplus-child-js-' . $scope, $bundle['url'], array(), $bundle['ver'], true );
	}
}
add_action( 'wp_enqueue_scripts', 'storeplus_child_perf_enqueue_js_bundles', 22 );

/**
 * Skip individual registry JS when JS bundles are active.
 */
function storeplus_child_perf_dequeue_individual_js() {
	if ( ! storeplus_child_perf_enabled() ) {
		return;
	}
	$files = storeplus_child_files();
	foreach ( array_keys( $files['js'] ) as $name ) {
		wp_dequeue_script( 'storeplus-child-' . $name );
	}
	foreach ( array( 'bestsellers-after-latest', 'banner-slots-place' ) as $extra ) {
		wp_dequeue_script( 'storeplus-child-' . $extra );
	}
}
add_action( 'wp_enqueue_scripts', 'storeplus_child_perf_dequeue_individual_js', 100 );

/**
 * @param string $handle Handle.
 * @return bool
 */
function storeplus_child_perf_style_is_bundled( $handle ) {
	if ( ! storeplus_child_perf_enabled() ) {
		return false;
	}
	if ( 0 === strpos( $handle, 'storeplus-child-bundle-' ) ) {
		return false;
	}
	static $names = null;
	if ( null === $names ) {
		$names = array_merge(
			array_keys( storeplus_child_files()['css'] ),
			array(
				/* Legacy part handles — still dequeued if something enqueues them. */
				'remove-service-strip',
				'banner-ratio',
				'landing-polish',
				'brand-chips',
				'shop-reference',
				'shop-redesign',
				'blog-latest-three',
				'post-cards',
				'product-card-final',
				'home-skeleton',
				'price-unified',
				'qty-stepper',
				'product-gallery',
				'product-page',
				'floating-support',
				'mini-card-cleanup',
				'slider-corners',
				'discount-badge',
			)
		);
		$names = array_unique( $names );
	}
	foreach ( $names as $n ) {
		if ( 'storeplus-child-' . $n === $handle ) {
			return true;
		}
	}
	return false;
}

function storeplus_child_perf_dequeue_bundled_styles() {
	if ( ! storeplus_child_perf_enabled() ) {
		return;
	}
	global $wp_styles;
	if ( ! $wp_styles instanceof WP_Styles ) {
		return;
	}
	foreach ( (array) $wp_styles->queue as $handle ) {
		if ( storeplus_child_perf_style_is_bundled( $handle ) ) {
			wp_dequeue_style( $handle );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'storeplus_child_perf_dequeue_bundled_styles', 110 );
add_action( 'wp_print_styles', 'storeplus_child_perf_dequeue_bundled_styles', 5 );

/**
 * @param string $tag    Tag.
 * @param string $handle Handle.
 * @param string $src    Src.
 * @return string
 */
function storeplus_child_perf_defer_scripts( $tag, $handle, $src ) {
	unset( $src );
	if ( is_admin() || ! storeplus_child_perf_enabled() ) {
		return $tag;
	}
	/*
	 * Do NOT idle-load home JS: brands populate + hide-empty race and leave
	 * whole sections display:none. Defer preserves document order.
	 */
	if ( 0 !== strpos( $handle, 'storeplus-child-' ) ) {
		return $tag;
	}
	if ( false !== strpos( $tag, ' defer' ) || false !== strpos( $tag, ' async' ) ) {
		return $tag;
	}
	return preg_replace( '/\s*<script\s/i', '<script defer ', $tag, 1 );
}
add_filter( 'script_loader_tag', 'storeplus_child_perf_defer_scripts', 10, 3 );

function storeplus_child_perf_soft_dequeue() {
	if ( is_admin() || ! storeplus_child_perf_enabled() ) {
		return;
	}
	if ( ! is_user_logged_in() ) {
		wp_dequeue_style( 'dashicons' );
	}
	wp_deregister_script( 'wp-embed' );
	/* Keep jquery-migrate — parent theme / plugins may rely on it. */

	/* Block library only on classic storefront views (not posts with Gutenberg). */
	$skip_blocks = is_front_page()
		|| ( function_exists( 'is_shop' ) && ( is_shop() || is_product_taxonomy() ) )
		|| ( function_exists( 'is_product' ) && is_product() )
		|| ( function_exists( 'is_cart' ) && ( is_cart() || is_checkout() ) );
	if ( $skip_blocks ) {
		wp_dequeue_style( 'wp-block-library' );
		wp_dequeue_style( 'wp-block-library-theme' );
		wp_dequeue_style( 'wc-blocks-style' );
		wp_dequeue_style( 'wc-blocks-vendors-style' );
	}

	$needs_wc = is_front_page()
		|| ( function_exists( 'is_woocommerce' ) && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) );
	if ( ! $needs_wc ) {
		wp_dequeue_style( 'woocommerce-general' );
		wp_dequeue_style( 'woocommerce-layout' );
		wp_dequeue_style( 'woocommerce-smallscreen' );
		wp_dequeue_script( 'wc-cart-fragments' );
	}

	/* Cart fragments on homepage: keep for mini-cart badge, but delay via idle is unsafe — leave. */
}
add_action( 'wp_enqueue_scripts', 'storeplus_child_perf_soft_dequeue', 100 );

add_filter(
	'heartbeat_settings',
	static function ( $settings ) {
		if ( ! is_admin() ) {
			$settings['interval'] = 60;
		}
		return $settings;
	}
);

function storeplus_child_perf_lcp_image_url() {
	if ( is_front_page() && function_exists( 'storeplus_child_get_hero_slides' ) ) {
		$slides = storeplus_child_get_hero_slides();
		if ( ! empty( $slides[0]['image_id'] ) ) {
			$url = wp_get_attachment_image_url( (int) $slides[0]['image_id'], 'large' );
			return is_string( $url ) ? $url : '';
		}
	}
	if ( function_exists( 'is_product' ) && is_product() ) {
		$pid = (int) get_queried_object_id();
		if ( $pid && function_exists( 'wc_get_product' ) ) {
			$p = wc_get_product( $pid );
			if ( $p && $p->get_image_id() ) {
				$url = wp_get_attachment_image_url( (int) $p->get_image_id(), 'woocommerce_single' );
				return is_string( $url ) ? $url : '';
			}
		}
	}
	return '';
}

add_action(
	'wp_head',
	static function () {
		if ( ! storeplus_child_perf_enabled() ) {
			return;
		}
		$url = storeplus_child_perf_lcp_image_url();
		if ( ! $url ) {
			return;
		}
		printf( '<link rel="preload" as="image" href="%s" fetchpriority="high" />' . "\n", esc_url( $url ) );
	},
	2
);

/**
 * Default lazy + async decode for attachment images (respect eager / high priority).
 *
 * @param array $attr Attributes.
 * @return array
 */
function storeplus_child_perf_image_attrs( $attr ) {
	if ( ! storeplus_child_perf_enabled() || is_admin() ) {
		return $attr;
	}
	$loading = isset( $attr['loading'] ) ? (string) $attr['loading'] : '';
	$prio    = isset( $attr['fetchpriority'] ) ? (string) $attr['fetchpriority'] : '';
	if ( 'eager' === $loading || 'high' === $prio ) {
		if ( empty( $attr['decoding'] ) ) {
			$attr['decoding'] = 'async';
		}
		return $attr;
	}
	if ( '' === $loading ) {
		$attr['loading'] = 'lazy';
	}
	if ( empty( $attr['decoding'] ) ) {
		$attr['decoding'] = 'async';
	}
	/* Do not force fetchpriority=low — can demote above-fold product images. */
	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'storeplus_child_perf_image_attrs', 30 );

/**
 * Resource hints: faster origin + font path warm-up.
 *
 * @param array  $urls          URLs.
 * @param string $relation_type Relation.
 * @return array
 */
function storeplus_child_perf_resource_hints( $urls, $relation_type ) {
	if ( ! storeplus_child_perf_enabled() ) {
		return $urls;
	}
	if ( 'dns-prefetch' === $relation_type || 'preconnect' === $relation_type ) {
		$home = wp_parse_url( home_url(), PHP_URL_HOST );
		if ( $home && is_array( $urls ) ) {
			$urls[] = '//' . $home;
		}
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'storeplus_child_perf_resource_hints', 10, 2 );

/**
 * Mark PDP main image as LCP candidate when Woo prints it.
 *
 * @param array      $attr    Attrs.
 * @param WP_Post    $attachment Attachment.
 * @param string|int $size    Size.
 * @return array
 */
function storeplus_child_perf_pdp_main_image( $attr, $attachment, $size ) {
	unset( $size );
	if ( ! storeplus_child_perf_enabled() || ! function_exists( 'is_product' ) || ! is_product() ) {
		return $attr;
	}
	static $done = false;
	if ( $done ) {
		return $attr;
	}
	$product = wc_get_product( get_queried_object_id() );
	if ( ! $product || (int) $product->get_image_id() !== (int) $attachment->ID ) {
		return $attr;
	}
	$done                   = true;
	$attr['loading']        = 'eager';
	$attr['fetchpriority']  = 'high';
	$attr['decoding']       = 'async';
	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'storeplus_child_perf_pdp_main_image', 25, 3 );

/**
 * Admin bar: child CSS payload hint for the current view (debug).
 */
function storeplus_child_perf_admin_bar( $wp_admin_bar ) {
	if ( ! is_user_logged_in() || ! current_user_can( 'manage_options' ) || is_admin() ) {
		return;
	}
	$dir   = storeplus_child_perf_cache_dir();
	$total = 0;
	$n     = 0;
	if ( is_dir( $dir ) ) {
		foreach ( (array) glob( $dir . '/bundle-*.css' ) as $f ) {
			/* Approximate: sum newest-looking files that match current enqueue pattern is hard;
			 * show total cache CSS size instead. */
			$total += filesize( $f );
			$n++;
		}
	}
	$wp_admin_bar->add_node(
		array(
			'id'    => 'sp-perf-bundles',
			'title' => sprintf( 'CSS cache: %d files / %.0f KB', $n, $total / 1024 ),
			'href'  => false,
		)
	);
}
add_action( 'admin_bar_menu', 'storeplus_child_perf_admin_bar', 100 );
