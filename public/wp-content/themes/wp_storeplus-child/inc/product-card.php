<?php
/**
 * Product card - ONE component for every vertical product card of the site.
 *
 *   storeplus_child_product_card( $product, array( 'layout' => 'vertical' ) );
 *
 * Where it is used
 * - The parent theme draws every card (home sliders, offers, category sections, AJAX categories,
 *   hotspot, shop / filter loop, related, up-sells) with Irantheme_Section_Woocommerce::get_product_item().
 *   That method now starts with a small hook that hands the product to this function, so there is
 *   ONE markup and ONE look for all of them (the look = assets/css/product-card.css).
 *   If this file is missing, the hook is skipped and the parent's own card is drawn as before.
 *   (Backup of the parent file before the hook: class-irantheme-section-woocommerce.php.bak-spcard)
 *
 * What the card always contains (the section settings for category / rating / excerpt / colour dots /
 * hover icons / sales box / countdown / borders / shadows / alignment no longer change the card):
 *   image tile  +  red discount pill on its corner (only when on sale)
 *   2-line title
 *   dashed divider, then price (old price above the final price) and the soft green cart tile
 *
 * Property "layout": only "vertical" exists. The horizontal card of the best-sellers box is a different
 * component (mini product card). Any other value falls back to "vertical".
 *
 * The existing class names of the parent theme are kept on purpose (products-col, products-item, image,
 * content, heading, content-bottom, add-to-cart-button ...) so the sliders, the WooCommerce AJAX add to
 * cart, hide-empty-sections, discount-badge-top.js and product-card.css keep working unchanged.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Layouts the component can draw.
 *
 * @return string[]
 */
function storeplus_child_product_card_layouts() {
	return array( 'vertical' );
}

/**
 * Draws one product card.
 *
 * @param WC_Product $product Product to draw.
 * @param array      $args    layout (vertical) | show_brand (bool) | show_discount (bool) | image_fit (cover|contain) | col_class | hover_class | extra_css | aos_attr | aos_delay | aos_duration
 *                            The last five are what the sections pass to the old method (slider column class,
 *                            hover mode class, inline style of the column and the data-load animation).
 */
function storeplus_child_product_card( $product, $args = array() ) {
	if ( ! $product instanceof WC_Product ) {
		return;
	}

	$args = wp_parse_args(
		$args,
		array(
			'layout'       => 'vertical',
			'show_brand'   => true,      // brand name above the title
			'show_discount' => true,     // red discount pill on the image
			'image_fit'    => 'cover',   // cover (full-bleed photo) | contain (whole product visible)
			'col_class'    => '',
			'hover_class'  => '',
			'extra_css'    => '',
			'aos_attr'     => '',
			'aos_delay'    => '',
			'aos_duration' => '',
		)
	);

	$layout = in_array( $args['layout'], storeplus_child_product_card_layouts(), true ) ? $args['layout'] : 'vertical';

	// Only one layout today; a new one = a new storeplus_child_product_card_<layout>() function.
	call_user_func( 'storeplus_child_product_card_' . $layout, $product, $args );
}

/**
 * The vertical card (the look of the home page product sliders).
 */
function storeplus_child_product_card_vertical( $product, $args ) {
	$id        = $product->get_id();
	$permalink = get_permalink( $id );
	$title     = get_the_title( $id );
	$in_stock  = $product->is_in_stock();

	// Discount pill: only for a product that is really on sale.
	$percent = '';
	if ( ! empty( $args['show_discount'] ) && $in_stock && $product->is_on_sale() && function_exists( 'irantheme_get_sale_percentage' ) ) {
		$percent = irantheme_get_sale_percentage( $product );
		$percent = $percent ? (string) $percent : '';
	}

	// Brand shown as a badge on the image (bottom-right). Source 1: WooCommerce brands (taxonomy product_brand).
	// Source 2: the product attribute "برند" (taxonomy pa_brand), which this site's products use.
	// Nothing is printed when the product has neither.
	$brand_name = '';
	if ( ! empty( $args['show_brand'] ) ) {
		foreach ( array( 'product_brand', 'pa_brand' ) as $brand_tax ) {
			if ( ! taxonomy_exists( $brand_tax ) ) {
				continue;
			}
			$brand_terms = get_the_terms( $id, $brand_tax );
			if ( is_array( $brand_terms ) && ! empty( $brand_terms ) ) {
				$brand_name = $brand_terms[0]->name;
				break;
			}
		}
	}

	// Cart tile: AJAX add to cart for simple purchasable products, otherwise it opens the product page.
	$ajax_class = '';
	$cart_link  = $permalink;
	if ( $in_stock && $product->is_type( 'simple' ) && $product->is_purchasable() ) {
		$ajax_class = 'ajax_add_to_cart';
		$cart_link  = home_url( '/?add-to-cart=' . $id );
	}

	$image_fit = in_array( $args['image_fit'], array( 'cover', 'contain' ), true ) ? $args['image_fit'] : 'cover';

	$item_classes = 'irantheme-woocommerce-products-item sp-product-card sp-product-card--vertical';
	if ( ! $in_stock ) {
		$item_classes .= ' irantheme-out-of-stock-product';
	}
	?>
	<div class="irantheme-woocommerce-products-col <?php echo esc_attr( $args['col_class'] ); ?> <?php echo esc_attr( $args['hover_class'] ); ?>" style="<?php echo esc_attr( $args['extra_css'] ); ?>" <?php echo $args['aos_attr']; // phpcs:ignore WordPress.Security.EscapeOutput -- built by the theme's own data-load helper ?> data-aos-delay="<?php echo esc_attr( $args['aos_delay'] ); ?>" data-aos-duration="<?php echo esc_attr( $args['aos_duration'] ); ?>">
		<div class="<?php echo esc_attr( $item_classes ); ?>" data-sp-card="vertical" data-sp-image-fit="<?php echo esc_attr( $image_fit ); ?>" data-product-id="<?php echo (int) $id; ?>">

			<!-- Image tile + discount pill -->
			<div class="irantheme-woocommerce-products-item-image">
				<a href="<?php echo esc_url( $permalink ); ?>" class="irantheme-product-image-holder" aria-label="<?php echo esc_attr( $title ); ?>">
					<?php
					$thumb_id = get_post_thumbnail_id( $id );
					$img_atts = array(
						'class'    => 'irantheme-woocommerce-products-item-img irantheme-product-image',
						'alt'      => $title,
						'loading'  => 'lazy',
						'decoding' => 'async',
					);
					$meta = $thumb_id ? wp_get_attachment_metadata( $thumb_id ) : null;
					if ( is_array( $meta ) && ! empty( $meta['width'] ) && ! empty( $meta['height'] ) ) {
						$img_atts['width']  = (int) $meta['width'];
						$img_atts['height'] = (int) $meta['height'];
					}
					echo wp_get_attachment_image( // phpcs:ignore WordPress.Security.EscapeOutput
						$thumb_id,
						'medium',
						false,
						$img_atts
					);
					?>
				</a>
				<?php if ( ! $in_stock ) : ?>
					<div class="irantheme-woocommerce-products-item-abs">
						<div class="irantheme-woocommerce-products-item-abs-item">
							<i class="iconsax" icon-name="not-allowed-3"></i>
							<span><cite>ناموجود</cite></span>
						</div>
					</div>
				<?php endif; ?>
				<?php if ( '' !== $percent ) : ?>
					<span class="irantheme-woocommerce-products-discount" data-sp-badge-top="1"><?php echo wp_kses( $percent, array( 'i' => array( 'class' => true ), 'span' => array( 'class' => true ) ) ); // the theme returns HTML (icon + number) ?></span>
				<?php endif; ?>
				<?php if ( '' !== $brand_name ) : ?>
					<span class="irantheme-woocommerce-products-brand-badge"><?php echo esc_html( $brand_name ); ?></span>
				<?php endif; ?>
			</div>

			<!-- Title, divider, price + cart tile -->
			<div class="irantheme-woocommerce-products-item-content">
				<h2 class="irantheme-woocommerce-products-item-content-heading">
					<a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $title ); ?></a>
				</h2>

				<div class="irantheme-woocommerce-products-item-content-bottom">
					<?php if ( $in_stock ) : ?>
						<div class="irantheme-woocommerce-products-item-add-to-cart">
							<a href="<?php echo esc_url( $cart_link ); ?>" value="<?php echo (int) $id; ?>" class="irantheme-woocommerce-products-item-add-to-cart-button <?php echo esc_attr( $ajax_class ); ?> add_to_cart_button" data-product_id="<?php echo (int) $id; ?>" aria-label="<?php echo esc_attr( 'افزودن به سبد خرید' ); ?>">
								<i class="iconsax irantheme-woocommerce-products-item-add-to-cart-button-plus" icon-name="shopping-cart"></i>
								<i class="fa-light fa-check irantheme-woocommerce-products-item-add-to-cart-button-success"></i>
								<i class="irantheme-animate-rotate" style="display: none;"></i>
							</a>
						</div>
					<?php endif; ?>
					<div class="irantheme-woocommerce-products-item-content-bottom-price">
						<?php
						if ( class_exists( '\Irantheme_Dev\Irantheme_Section_Woocommerce' ) ) {
							// Old price (struck) above the final price. The % pill is drawn on the image, not here.
							\Irantheme_Dev\Irantheme_Section_Woocommerce::display_product_price( $product, false, 'off' );
						} else {
							echo wp_kses_post( $product->get_price_html() );
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
}
