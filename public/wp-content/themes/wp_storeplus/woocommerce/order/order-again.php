<?php

/**
 * Order again button
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-again.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @wc-version 7.8.0
 * @author M-MALEKZADEH77
 */

defined('ABSPATH') || exit;
?>

<div class="order-again irantheme-myaccount-order-order-again">
	<a href="<?php echo esc_url($order_again_url); ?>" class="<?php echo esc_attr($wp_button_class); ?> irantheme-myaccount-order-order-again-link"><i class="fa-light fa-rotate-right"></i><?php esc_html_e('Order again', 'woocommerce'); ?></a>
</div>