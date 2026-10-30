<?php
/**
 * Checkout: hide "کشور / منطقه" — store only ships in Iran.
 * Forces billing/shipping country to IR and removes the selector (classic + block).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Only Iran as allowed / selling country (WC hides the field when one country remains). */
add_filter(
	'woocommerce_countries_allowed_countries',
	static function ( $countries ) {
		$label = ( is_array( $countries ) && isset( $countries['IR'] ) ) ? $countries['IR'] : 'ایران';
		return array( 'IR' => $label );
	},
	20
);

add_filter(
	'woocommerce_countries_selling_countries',
	static function ( $countries ) {
		$label = ( is_array( $countries ) && isset( $countries['IR'] ) ) ? $countries['IR'] : 'ایران';
		return array( 'IR' => $label );
	},
	20
);

add_filter( 'default_checkout_billing_country', static function () {
	return 'IR';
} );
add_filter( 'default_checkout_shipping_country', static function () {
	return 'IR';
} );

/** Strip country fields from classic checkout forms. */
add_filter(
	'woocommerce_checkout_fields',
	static function ( $fields ) {
		if ( isset( $fields['billing']['billing_country'] ) ) {
			unset( $fields['billing']['billing_country'] );
		}
		if ( isset( $fields['shipping']['shipping_country'] ) ) {
			unset( $fields['shipping']['shipping_country'] );
		}
		return $fields;
	},
	99
);

add_filter(
	'woocommerce_billing_fields',
	static function ( $fields ) {
		unset( $fields['billing_country'] );
		return $fields;
	},
	99
);

add_filter(
	'woocommerce_shipping_fields',
	static function ( $fields ) {
		unset( $fields['shipping_country'] );
		return $fields;
	},
	99
);

/** Keep customer country fixed to Iran during checkout. */
add_action(
	'woocommerce_checkout_init',
	static function () {
		if ( ! function_exists( 'WC' ) || ! WC()->customer ) {
			return;
		}
		WC()->customer->set_billing_country( 'IR' );
		WC()->customer->set_shipping_country( 'IR' );
	},
	5
);

add_action(
	'woocommerce_checkout_process',
	static function () {
		$_POST['billing_country']  = 'IR';
		$_POST['shipping_country'] = 'IR';
	},
	1
);

/** CSS backup for classic leftovers + block checkout country input. */
add_action(
	'wp_head',
	static function () {
		if ( ! function_exists( 'is_checkout' ) || ( ! is_checkout() && ! is_account_page() ) ) {
			return;
		}
		echo '<style id="sp-hide-checkout-country">'
			. '#billing_country_field,'
			. '#shipping_country_field,'
			. '.woocommerce-billing-fields #billing_country_field,'
			. '.woocommerce-shipping-fields #shipping_country_field,'
			. '.wc-block-components-country-input,'
			. '.wc-block-components-address-form__country,'
			. '.wc-block-checkout__billing-fields .wc-block-components-country-input,'
			. '.wc-block-checkout__shipping-fields .wc-block-components-country-input{'
			. 'display:none!important;height:0!important;overflow:hidden!important;'
			. 'margin:0!important;padding:0!important;border:0!important}'
			. '</style>' . "\n";
	},
	20
);
