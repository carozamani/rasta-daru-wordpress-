<?php

if (! defined('ABSPATH')) {
    exit;
}
// Paper type
$paper = get_option('irantheme_wc_invoice_thermal_paper_size_orientation_select', '80mm-portrait');

/* 
Admin paper type override (Development / Debugging)
This override allows administrator to temporary change then invoice paper size and orientation using URL query parameter.

Example:
?inv_paper_size=A5-portrait or &inv_paper_size=A5-portrait

It is useful for:
    - testing different invoice layout
    - previewing print formats
    - debugging css print issus
    - developing new paper templates

Regular customers cannot use this override
*/
if (current_user_can('manage_woocommerce') && isset($_GET['inv_paper_size'])) {
    $paper = sanitize_text_field($_GET['inv_paper_size']);
}

/** @var WC_Order $order */

// Seller meta variable
$seller_name    = get_option('irantheme_wc_invoice_shop_name');
$seller_logo    = get_option('irantheme_wc_invoice_shop_logo');
$seller_signature    = get_option('irantheme_wc_invoice_shop_signature');
$seller_phone   = get_option('irantheme_wc_invoice_shop_phone_number');
$seller_website = get_option('irantheme_wc_invoice_shop_website_url');
$seller_email = get_option('irantheme_wc_invoice_shop_email');
$seller_economy_code = get_option('irantheme_wc_invoice_shop_economy_code');
$seller_melli_code = get_option('irantheme_wc_invoice_shop_melli_code');
$seller_reg_code = get_option('irantheme_wc_invoice_shop_reg_code');
$seller_post_code = get_option('irantheme_wc_invoice_shop_pcode');
$seller_city = get_option('irantheme_wc_invoice_shop_city');
$seller_state = get_option('irantheme_wc_invoice_shop_state');
$seller_address = get_option('irantheme_wc_invoice_shop_address');
$admin_descriptions = get_option('irantheme_wc_invoice_thermal_admin_descriptions');

// Buyer meta variable
$buyer_name    = $order->get_formatted_billing_full_name();
$buyer_phone   = $order->get_billing_phone();
$buyer_email = $order->get_billing_email();
$buyer_state = $order->get_billing_state();
$buyer_city = $order->get_billing_city();
$buyer_post_code = $order->get_billing_postcode();
$buyer_economy_code = '12345678901234';
$buyer_melli_code = '1130453502';
$buyer_reg_code = '1130453502';
$buyer_address = $order->get_billing_address_1();

// Reg. order date
$order_date = wc_format_datetime($order->get_date_created(), 'Y/m/d');

// Currency symbol
$currency_symbol_txt = '';
if (get_woocommerce_currency() === 'IRR') {
    $currency_symbol_txt = 'ریال';
} elseif (get_woocommerce_currency() === 'IRT') {
    $currency_symbol_txt = 'تومان';
} else {
    $currency_symbol_txt = get_woocommerce_currency_symbol();
}

// Watermark
$is_official_invoice = in_array($order->get_status(), array('completed', 'processing'));
?>
<header class="irantheme-invoice-head irantheme-invoice-thermal-head">
    <div class="store-logo">
        <?php
        echo wp_get_attachment_image(
            $seller_logo,
            'small',
            false,
            [
                'alt' => __('لوگو' . esc_attr($seller_name), IRANTHEME_TEXTDOMAIN),
                'loading' => 'lazy',
            ]
        ); ?>
    </div>
    <h2 class="store-info-item store-name"><?php echo esc_html($seller_name); ?></h2>
    <?php if ($seller_address != ''): ?>
        <p class="store-info-item store-address"><strong>آدرس: </strong><?php echo esc_html($seller_address); ?></p>
    <?php endif; ?>
    <?php if ($seller_phone != ''): ?>
        <p class="store-info-item store-phone"><strong>تلفن: </strong><?php echo esc_html($seller_phone); ?></p>
    <?php endif; ?>
</header>
<div class="irantheme-invoice-thermal-separator"></div>
<div class="irantheme-invoice-thermal-order-meta">
    <div class='order-meta-item'>
        <td class="order-meta-item-label">شماره:</td>
        <td class="order-meta-item-val"><?php echo $order->get_order_number(); ?></td>
    </div>
    <div class='order-meta-item'>
        <td class="order-meta-item-label">تاریخ:</td>
        <td class="order-meta-item-val"><?php echo $order_date; ?></td>
    </div>
</div>
<div class="irantheme-invoice-thermal-separator"></div>
<div class="irantheme-invoice-thermal-customer-info">
    <div class="customer-info-row">
        <p class="customer-info-item"><strong>نام خریدار: </strong><?php echo esc_html($buyer_name); ?></p>
        <p class="customer-info-item"><strong>تلفن: </strong><?php echo esc_html($buyer_phone != '' ? $buyer_phone : '---'); ?></p>
    </div>
    <div class="customer-info-row">
        <p class="customer-info-item"><strong>آدرس: </strong><?php echo esc_html($buyer_address != '' ? $buyer_address : '---'); ?></p>
    </div>
</div>
<div class="irantheme-invoice-thermal-separator"></div>
<table class="irantheme-invoice-thermal-table inv-product-table">
    <thead>
        <tr>
            <th class="product-name">کالا / خدمات</th>
            <th class="quantity">تعداد</th>
            <th class="unit-price">قیمت واحد</th>
            <th class="total">جمع کل <span>(<?php echo $currency_symbol_txt; ?>)</span></th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($order->get_items() as $item_id => $item) :
        ?>
            <tr>
                <td class="product-name"><?php echo $item->get_name(); ?></td>
                <td class="quantity"><?php echo $item->get_quantity(); ?></td>
                <td class="unit-price"><?php echo number_format($order->get_item_subtotal($item, false, false)); ?></td>
                <td class="total"><?php echo number_format($item->get_total()); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <?php
        $totals = $order->get_order_item_totals();
        foreach ($totals as $key => $total) :
            $is_final = ($key === 'order_total');

            // Decline payment method
            if ($key === 'payment_method') continue;
        ?>
            <?php if ($total['type'] == 'total'): ?>

                <tr class="invoice-total-row final-total">
                    <td class="invoice-total-label" colspan="2">
                        مبلغ قابل پرداخت:
                    </td>
                    <td class="invoice-total-value" colspan="2">
                        <?php
                        echo strip_tags($total['value']);
                        ?>
                    </td>
                </tr>
            <?php else: ?>
                <tr class="invoice-total-row additions-row">
                    <td class="invoice-total-label" colspan="3">
                        <?php
                        echo $total['label'];
                        ?>
                    </td>
                    <td class="invoice-total-value" colspan="1">
                        <?php
                        echo strip_tags($total['value']);
                        ?>
                    </td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
    </tfoot>
</table>
<footer class="irantheme-invoice-footer irantheme-invoice-thermal-footer">
    <div class='invoice-desc'>
        <p><?php echo $admin_descriptions != '' ? $admin_descriptions : 'از خرید شما سپاسگزاریم.'; ?></p>
    </div>
    <div class="irantheme-invoice-thermal-separator"></div>
    <div class="invoice-website">
        <p><?php echo esc_html($seller_name); ?></p>
        <p>|</p>
        <p><?php echo esc_html($seller_website); ?></p>
    </div>
</footer>