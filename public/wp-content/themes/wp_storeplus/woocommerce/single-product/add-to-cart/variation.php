<?php

/**
 * Single variation display
 *
 * This is a javascript-based template for single variations (see https://codex.wordpress.org/Javascript_Reference/wp.template).
 * The values will be dynamically replaced after selecting attributes.
 *
 * @see https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @wc-version 9.3.0
 * @author	M-MALEKZADEH77
 */

use Irantheme_Dev\Irantheme_Color_Static;

defined('ABSPATH') || exit;
?>
<script type="text/template" id="tmpl-variation-template">
	<div class="irantheme-woocommerce-variation-description woocommerce-variation-description">{{{ data.variation.variation_description }}}</div>
	<div class="irantheme-woocommerce-variation-price woocommerce-variation-price">{{{ data.variation.price_html }}}</div>
	<div class="irantheme-woocommerce-variation-availability woocommerce-variation-availability">{{{ data.variation.availability_html }}}</div>
</script>
<script type="text/template" id="tmpl-unavailable-variation-template">
	<p role="alert"><?php esc_html_e('Sorry, this product is unavailable. Please choose a different combination.', 'woocommerce'); ?></p>
</script>