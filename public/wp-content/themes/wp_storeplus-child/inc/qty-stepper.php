<?php
/**
 * Shared quantity stepper (.sp-qty) — PHP markup helper.
 *
 * Usage:
 *   echo storeplus_child_qty_stepper( array( 'qty' => 2, 'size' => 'sm' ) );
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render stepper HTML.
 *
 * @param array $args {
 *     @type int    $qty        Current quantity (default 1).
 *     @type string $size       sm|md|lg (default md).
 *     @type bool   $full       Full width (default false).
 *     @type string $cart_key   Optional cart line key.
 *     @type string $product_id Optional product id.
 *     @type string $class      Extra classes.
 * }
 * @return string
 */
function storeplus_child_qty_stepper( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'qty'        => 1,
			'size'       => 'md',
			'full'       => false,
			'cart_key'   => '',
			'product_id' => '',
			'class'      => '',
		)
	);

	$size = in_array( $args['size'], array( 'sm', 'md', 'lg' ), true ) ? $args['size'] : 'md';
	$qty  = max( 1, (int) $args['qty'] );
	$fa   = array( '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹' );
	$qty_fa = preg_replace_callback(
		'/\d/',
		static function ( $m ) use ( $fa ) {
			return $fa[ (int) $m[0] ];
		},
		(string) $qty
	);

	$classes = array( 'sp-qty', 'sp-qty--' . $size );
	if ( ! empty( $args['full'] ) ) {
		$classes[] = 'sp-qty--full';
	}
	if ( $args['class'] ) {
		$classes[] = $args['class'];
	}

	$trash = $qty <= 1;
	$dec_class = 'sp-qty__btn sp-qty__dec' . ( $trash ? ' is-trash' : '' );
	$dec_label = $trash ? 'حذف از سبد خرید' : 'کاهش تعداد';
	$minus_svg = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M5 12h14"/></svg>';
	$plus_svg  = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>';
	$trash_svg = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.85" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 7h16"/><path d="M9 7V5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/><path d="M6 7l1 12a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2l1-12"/><path d="M10 11v6M14 11v6"/></svg>';

	$attrs = ' data-sp-qty="1"';
	if ( $args['cart_key'] ) {
		$attrs .= ' data-cart-key="' . esc_attr( $args['cart_key'] ) . '"';
	}
	if ( $args['product_id'] ) {
		$attrs .= ' data-product-id="' . esc_attr( (string) $args['product_id'] ) . '"';
	}

	ob_start();
	?>
	<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" role="group" aria-label="<?php echo esc_attr( 'تعداد' ); ?>"<?php echo $attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<button type="button" class="<?php echo esc_attr( $dec_class ); ?>" aria-label="<?php echo esc_attr( $dec_label ); ?>">
			<?php echo $trash ? $trash_svg : $minus_svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</button>
		<span class="sp-qty__val"><?php echo esc_html( $qty_fa ); ?></span>
		<button type="button" class="sp-qty__btn sp-qty__inc" aria-label="<?php echo esc_attr( 'افزایش تعداد' ); ?>">
			<?php echo $plus_svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</button>
	</div>
	<?php
	return (string) ob_get_clean();
}

/**
 * Enqueue shared qty assets on commerce views only (home/PDP/shop/cart/checkout/account).
 */
function storeplus_child_qty_stepper_assets() {
	if ( function_exists( 'storeplus_child_asset_scope_matches' ) && ! storeplus_child_asset_scope_matches( 'commerce' ) ) {
		return;
	}
	$css = STOREPLUS_CHILD_DIR . '/assets/css/qty-stepper.css';
	$js  = STOREPLUS_CHILD_DIR . '/assets/js/qty-stepper.js';
	if ( file_exists( $css ) && ! ( function_exists( 'storeplus_child_perf_enabled' ) && storeplus_child_perf_enabled() ) ) {
		wp_enqueue_style(
			'storeplus-child-qty-stepper',
			STOREPLUS_CHILD_URI . '/assets/css/qty-stepper.css',
			array( 'irantheme-main-css', 'style' ),
			filemtime( $css )
		);
	}
	if ( file_exists( $js ) ) {
		wp_enqueue_script(
			'storeplus-child-qty-stepper',
			STOREPLUS_CHILD_URI . '/assets/js/qty-stepper.js',
			array(),
			filemtime( $js ),
			true
		);
		wp_add_inline_script(
			'storeplus-child-qty-stepper',
			'window.ajaxurl=window.ajaxurl||' . wp_json_encode( admin_url( 'admin-ajax.php' ) ) . ';',
			'before'
		);
	}
}
add_action( 'wp_enqueue_scripts', 'storeplus_child_qty_stepper_assets', 25 );
