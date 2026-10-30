<?php
if (! defined('ABSPATH')) {
    exit;
}
// Paper type
$paper = get_option('irantheme_wc_shipping_label_paper_size_orientation_select', 'A6-landscape');

/* 
Admin paper type override (Development / Debugging)
This override allows administrator to temporary change then invoice paper size and orientation using URL query parameter.

Example:
?label_size=A6-portrait

It is useful for:
    - testing different invoice layout
    - previewing print formats
    - debugging css print issus
    - developing new paper templates

Regular customers cannot use this override
*/
if (current_user_can('manage_woocommerce') && isset($_GET['label_size'])) {
    $paper = sanitize_text_field($_GET['label_size']);
}

/** @var WC_Order $order */

$sender_logo    = get_option('irantheme_wc_invoice_shop_logo');
$sender_name    = get_option('irantheme_wc_invoice_shop_name');
$sender_phone   = get_option('irantheme_wc_invoice_shop_phone_number');
$sender_address = get_option('irantheme_wc_invoice_shop_address');
$sender_post_code     = get_option('irantheme_wc_invoice_shop_pcode');
$sender_city = get_option('irantheme_wc_invoice_shop_city');
$sender_state = get_option('irantheme_wc_invoice_shop_state');

$shipping_state_code = $order->get_shipping_state();
$shipping_country = $order->get_shipping_country();
$shipping_full_state_name = WC()->countries->states[$shipping_country][$shipping_state_code] ?? $shipping_state_code;

// Reg. order date
$order_date = wc_format_datetime($order->get_date_created(), 'Y/m/d');
?>
<div class="irantheme-shipping-label-container irantheme-shipping-paper-label-container">
    <div class="irantheme-shipping-paper-label-content">
        <?php
        // Show logo separately in A6 and A5 portrait
        if (in_array($paper, array('A6-portrait', 'A5-portrait'))): ?>
            <div class="sender-logo">
                <?php
                echo wp_get_attachment_image(
                    $sender_logo,
                    'small',
                    false,
                    [
                        'alt' => __('لوگو' . esc_attr($sender_name), IRANTHEME_TEXTDOMAIN),
                        'loading' => 'lazy',
                    ]
                ); ?>
            </div>
            <div class="irantheme-shipping-label-separator"></div>
        <?php endif; ?>
        <div class="sender-shipping-box">
            <?php
            // Show logo in sender shipping box in A6 and A5 landscape
            if (in_array($paper, array('A6-landscape', 'A5-landscape'))): ?>
                <div class="sender-logo">
                    <?php
                    echo wp_get_attachment_image(
                        $sender_logo,
                        'small',
                        false,
                        [
                            'alt' => __('لوگو' . esc_attr($sender_name), IRANTHEME_TEXTDOMAIN),
                            'loading' => 'lazy',
                        ]
                    ); ?>
                </div>
            <?php endif; ?>
            <div class="shipping-info">
                <p class="info-name"><span><strong>فرستنده: </strong><?php echo $sender_name; ?></span><span><strong>:FROM</strong></span></p>
                <p class="info-state-city"><cite><strong>استان: </strong><?php echo esc_html($sender_state); ?></cite> <cite><strong>شهر: </strong><?php echo esc_html($sender_city); ?></cite></p>
                <p class="info-address"><strong>آدرس: </strong><?php echo $sender_address; ?></p>
                <p class="info-post-code"><strong>کد پستی: </strong><?php echo $sender_post_code; ?></p>
                <p class="info-phone"><strong>تلفن: </strong><?php echo $sender_phone; ?></p>
            </div>
        </div>
        <div class="irantheme-shipping-label-separator"></div>
        <div class="receiver-shipping-box">
            <div class="shipping-info">
                <p class="info-name"><span><strong>گیرنده: </strong><?php echo $order->get_shipping_first_name() . ' ' . $order->get_shipping_last_name(); ?></span><span><strong>:TO</strong></span></p>
                <p class="info-state-city"><cite><strong>استان: </strong><?php echo $shipping_full_state_name; ?></cite> <cite><strong>شهر: </strong><?php echo $order->get_shipping_city(); ?></cite></p>
                <p class="info-address"><strong>آدرس: </strong><?php echo $order->get_shipping_address_1(); ?></p>
                <p class="info-post-code"><strong>کد پستی: </strong><?php echo $order->get_shipping_postcode(); ?></p>
                <p class="info-phone"><strong>تلفن: </strong><?php echo $order->get_shipping_phone(); ?></p>
            </div>
            <?php
            // Show post label area in receiver shipping box in A6 and A5 landscape
            if (in_array($paper, array('A6-landscape', 'A5-landscape'))): ?>
                <div class="post-label-area">
                    <p class="post-label-area-title">محل الصاق لیبل پستی</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="irantheme-shipping-paper-label-meta">
        <p class="meta-item order-number"><strong>شماره سفارش: </strong><?php echo $order->get_order_number(); ?></p>
        <p class="meta-item order-reg-date"><strong>تاریخ ثبت سفارش: </strong><?php echo $order_date; ?></p>
    </div>
</div>