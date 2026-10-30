<?php
/**
 * Floating support FAB: WhatsApp, phone, Telegram (from store-contact).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Whether Tawk.to live chat widget is linked (page + widget IDs set).
 */
function storeplus_child_tawk_is_linked() {
	$page_id   = (string) get_option( 'tawkto-embed-widget-page-id', '' );
	$widget_id = (string) get_option( 'tawkto-embed-widget-widget-id', '' );
	return '' !== $page_id && '' !== $widget_id;
}

/**
 * Whether to render the FAB on this request.
 */
function storeplus_child_float_support_should_show() {
	if ( is_admin() || wp_doing_ajax() || is_feed() ) {
		return false;
	}
	if ( function_exists( 'is_checkout' ) && is_checkout() && ! is_wc_endpoint_url( 'order-received' ) ) {
		return false; /* keep checkout calm */
	}
	/* Avoid two floating buttons once Tawk.to is connected. */
	if ( storeplus_child_tawk_is_linked() ) {
		return false;
	}
	/**
	 * Filter: hide floating support.
	 *
	 * @param bool $show Whether to show.
	 */
	return (bool) apply_filters( 'storeplus_child_float_support_show', true );
}

/**
 * Enqueue assets.
 */
function storeplus_child_float_support_assets() {
	if ( ! storeplus_child_float_support_should_show() ) {
		return;
	}
	$css = STOREPLUS_CHILD_DIR . '/assets/css/floating-support.css';
	$js  = STOREPLUS_CHILD_DIR . '/assets/js/floating-support.js';
	if ( file_exists( $css ) && ! ( function_exists( 'storeplus_child_perf_enabled' ) && storeplus_child_perf_enabled() ) ) {
		wp_enqueue_style(
			'storeplus-child-floating-support',
			STOREPLUS_CHILD_URI . '/assets/css/floating-support.css',
			array(),
			(string) filemtime( $css )
		);
	}
	if ( file_exists( $js ) ) {
		wp_enqueue_script(
			'storeplus-child-floating-support',
			STOREPLUS_CHILD_URI . '/assets/js/floating-support.js',
			array(),
			(string) filemtime( $js ),
			true
		);
	}
}
add_action( 'wp_enqueue_scripts', 'storeplus_child_float_support_assets', 40 );

/**
 * Markup in footer.
 */
function storeplus_child_float_support_markup() {
	if ( ! storeplus_child_float_support_should_show() ) {
		return;
	}
	$c = function_exists( 'storeplus_child_contact' ) ? storeplus_child_contact() : array();
	$wa = ! empty( $c['whatsapp'] ) ? preg_replace( '/\D+/', '', $c['whatsapp'] ) : '';
	$tel = ! empty( $c['mobile_tel'] ) ? $c['mobile_tel'] : ( ! empty( $c['phone_tel'] ) ? $c['phone_tel'] : '' );
	$tg  = ! empty( $c['telegram'] ) ? $c['telegram'] : '';
	$hours = ! empty( $c['hours'] ) ? $c['hours'] : '';
	$brand = ! empty( $c['brand'] ) ? $c['brand'] : get_bloginfo( 'name' );

	$wa_msg = rawurlencode( 'سلام، درباره خرید از ' . $brand . ' سوال داشتم.' );
	$wa_url = $wa ? 'https://wa.me/' . $wa . '?text=' . $wa_msg : '';

	if ( ! $wa_url && ! $tel && ! $tg ) {
		return;
	}
	?>
	<div class="sp-float-support" data-sp-float-support>
		<div class="sp-float-support__panel" id="sp-float-support-panel" hidden>
			<p class="sp-float-support__title">پشتیبانی <?php echo esc_html( $brand ); ?></p>
			<?php if ( $hours ) : ?>
				<p class="sp-float-support__hours"><?php echo esc_html( $hours ); ?></p>
			<?php endif; ?>
			<ul class="sp-float-support__list">
				<?php if ( $wa_url ) : ?>
					<li>
						<a class="sp-float-support__link sp-float-support__link--wa" href="<?php echo esc_url( $wa_url ); ?>" target="_blank" rel="noopener noreferrer">
							<span class="sp-float-support__icon" aria-hidden="true">
								<svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.435 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
							</span>
							<span>چت واتساپ</span>
						</a>
					</li>
				<?php endif; ?>
				<?php if ( $tel ) : ?>
					<li>
						<a class="sp-float-support__link sp-float-support__link--tel" href="<?php echo esc_url( 'tel:' . $tel ); ?>">
							<span class="sp-float-support__icon" aria-hidden="true">
								<svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M6.62 10.79a15.15 15.15 0 006.59 6.59l2.2-2.2a1 1 0 011.01-.24c1.12.37 2.33.57 3.58.57a1 1 0 011 1V20a1 1 0 01-1 1C10.4 21 3 13.6 3 4a1 1 0 011-1h3.5a1 1 0 011 1c0 1.25.2 2.46.57 3.58a1 1 0 01-.25 1.02l-2.2 2.19z"/></svg>
							</span>
							<span>تماس تلفنی</span>
						</a>
					</li>
				<?php endif; ?>
				<?php if ( $tg ) : ?>
					<li>
						<a class="sp-float-support__link sp-float-support__link--tg" href="<?php echo esc_url( $tg ); ?>" target="_blank" rel="noopener noreferrer">
							<span class="sp-float-support__icon" aria-hidden="true">
								<svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M11.944 0A12 12 0 000 12a12 12 0 0012 12 12 12 0 0012-12A12 12 0 0012 0h-.056zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 01.171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>
							</span>
							<span>تلگرام</span>
						</a>
					</li>
				<?php endif; ?>
			</ul>
		</div>
		<button type="button" class="sp-float-support__fab" aria-expanded="false" aria-controls="sp-float-support-panel" aria-label="باز کردن پشتیبانی">
			<span class="sp-float-support__fab-open" aria-hidden="true">
				<svg viewBox="0 0 24 24" width="26" height="26" fill="currentColor"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/></svg>
			</span>
			<span class="sp-float-support__fab-close" aria-hidden="true">
				<svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
			</span>
		</button>
	</div>
	<?php
}
add_action( 'wp_footer', 'storeplus_child_float_support_markup', 40 );
