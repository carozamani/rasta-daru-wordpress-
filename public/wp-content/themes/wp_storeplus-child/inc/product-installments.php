<?php
/**
 * Product installments: admin can enable installment purchase and list providers
 * (one per line: DigiPay, Snapp Pay, …). Shown on the single product buy card.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SP_INSTALLMENTS_ENABLED_KEY', '_sp_installments_enabled' );
define( 'SP_INSTALLMENTS_PROVIDERS_KEY', '_sp_installments_providers' );

/**
 * @param int $product_id Product ID.
 * @return bool
 */
function storeplus_child_installments_enabled( $product_id ) {
	return 'yes' === get_post_meta( (int) $product_id, SP_INSTALLMENTS_ENABLED_KEY, true );
}

/**
 * @param int $product_id Product ID.
 * @return string[] Non-empty trimmed provider names.
 */
function storeplus_child_installments_providers( $product_id ) {
	$raw = (string) get_post_meta( (int) $product_id, SP_INSTALLMENTS_PROVIDERS_KEY, true );
	if ( '' === trim( $raw ) ) {
		return array();
	}
	$lines = preg_split( '/\r\n|\r|\n/', $raw );
	$out   = array();
	foreach ( (array) $lines as $line ) {
		$line = trim( wp_strip_all_tags( $line ) );
		if ( '' !== $line ) {
			$out[] = $line;
		}
	}
	return array_values( array_unique( $out ) );
}

/**
 * WooCommerce product data panel fields.
 */
function storeplus_child_installments_product_fields() {
	echo '<div class="options_group show_if_simple show_if_variable show_if_external">';

	woocommerce_wp_checkbox(
		array(
			'id'          => SP_INSTALLMENTS_ENABLED_KEY,
			'label'       => 'فروش اقساطی',
			'description' => 'اگر فعال باشد، باکس خرید اقساطی در صفحه محصول نشان داده می‌شود.',
			'desc_tip'     => true,
			'value'       => get_post_meta( get_the_ID(), SP_INSTALLMENTS_ENABLED_KEY, true ),
		)
	);

	woocommerce_wp_textarea_input(
		array(
			'id'          => SP_INSTALLMENTS_PROVIDERS_KEY,
			'label'       => 'شرکت‌های اقساطی',
			'description' => 'هر شرکت را در یک خط بنویسید؛ مثلاً دیجی‌پی، اسنپ‌پی، تارا، …',
			'desc_tip'    => true,
			'placeholder' => "دیجی‌پی\nاسنپ‌پی\nتارا",
			'rows'        => 5,
			'value'       => get_post_meta( get_the_ID(), SP_INSTALLMENTS_PROVIDERS_KEY, true ),
		)
	);

	echo '</div>';
}
add_action( 'woocommerce_product_options_general_product_data', 'storeplus_child_installments_product_fields' );

/**
 * Save product fields + keep theme badge "has_installments" in sync.
 *
 * @param int $product_id Product ID.
 */
function storeplus_child_installments_save_product( $product_id ) {
	// phpcs:ignore WordPress.Security.NonceVerification.Missing -- WooCommerce already verified.
	$enabled = isset( $_POST[ SP_INSTALLMENTS_ENABLED_KEY ] ) && 'yes' === $_POST[ SP_INSTALLMENTS_ENABLED_KEY ]
		? 'yes'
		: 'no';
	update_post_meta( $product_id, SP_INSTALLMENTS_ENABLED_KEY, $enabled );

	$providers = '';
	if ( isset( $_POST[ SP_INSTALLMENTS_PROVIDERS_KEY ] ) ) {
		$providers = sanitize_textarea_field( wp_unslash( $_POST[ SP_INSTALLMENTS_PROVIDERS_KEY ] ) );
	}
	update_post_meta( $product_id, SP_INSTALLMENTS_PROVIDERS_KEY, $providers );

	// Sync with the theme's product detail badge checklist.
	$badges_raw = (string) get_post_meta( $product_id, 'irantheme_custom_field_product_detail_badge', true );
	$badges     = array_filter( array_map( 'trim', explode( ',', $badges_raw ) ) );
	$has_badge  = in_array( 'has_installments', $badges, true );

	if ( 'yes' === $enabled && ! $has_badge ) {
		$badges[] = 'has_installments';
		update_post_meta( $product_id, 'irantheme_custom_field_product_detail_badge', implode( ',', $badges ) );
	} elseif ( 'no' === $enabled && $has_badge ) {
		$badges = array_values( array_diff( $badges, array( 'has_installments' ) ) );
		update_post_meta( $product_id, 'irantheme_custom_field_product_detail_badge', implode( ',', $badges ) );
	}
}
add_action( 'woocommerce_process_product_meta', 'storeplus_child_installments_save_product' );

/**
 * Markup for the single product page.
 *
 * @param int|null $product_id Optional product ID.
 */
function storeplus_child_render_product_installments( $product_id = null ) {
	/* Big installments card removed from PDP — providers show via buy signals only. */
	return;
}
