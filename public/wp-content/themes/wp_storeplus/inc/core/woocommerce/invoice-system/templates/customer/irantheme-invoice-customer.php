<?php
if (! defined('ABSPATH')) {
    exit;
}
// Paper type
$paper = get_option('irantheme_wc_invoice_customer_paper_size_orientation_select', 'A4-portrait');

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
$seller_post_code = get_option('irantheme_wc_invoice_shop_pcode');
$seller_address = get_option('irantheme_wc_invoice_shop_address');
$admin_descriptions = get_option('irantheme_wc_invoice_customer_admin_descriptions');

// Buyer meta variable
$buyer_name    = $order->get_formatted_billing_full_name();
$buyer_phone   = $order->get_billing_phone();
$buyer_email = $order->get_billing_email();
$buyer_state_code = $order->get_billing_state();
$buyer_city = $order->get_billing_city();
$buyer_country = $order->get_billing_country();
$buyer_full_state_name = WC()->countries->states[$buyer_country][$buyer_state_code] ?? $buyer_state_code;
$buyer_post_code = $order->get_billing_postcode();
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
$watermark_text = ! $is_official_invoice ? 'پیش‌ فاکتور' : '';

// Signature visibility
$signature_visibility = get_option('irantheme_wc_invoice_customer_signature_visibility_checkbox', 'on');
?>

<header class="irantheme-invoice-head irantheme-invoice-customer-head">
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
    <div class="store-info">
        <h2 class="invoice-title"><?php echo $is_official_invoice ? 'صورتحساب فروش کالا و خدمات' : 'پیش‌ فاکتور فروش کالا و خدمات'; ?></h2>
        <h1 class="shop-name"><?php echo esc_html($seller_name); ?></h1>
        <p class="shop-website"><?php echo esc_html($seller_website); ?></p>
    </div>
    <div class="order-meta">
        <table class="order-meta-table">
            <tbody>
                <tr>
                    <td class="order-meta-table-row-label">شماره:</td>
                    <td class="order-meta-table-row-val"><?php echo $order->get_order_number(); ?></td>
                </tr>
                <tr>
                    <td class="order-meta-table-row-label">تاریخ:</td>
                    <td class="order-meta-table-row-val"><?php echo $order_date; ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</header>
<div class="irantheme-invoice-content irantheme-invoice-customer-content">
    <?php if (!empty($watermark_text)): ?>
        <div class="invoice-watermark"><?php echo $watermark_text; ?></div>
    <?php endif; ?>
    <div class="irantheme-invoice-table-wrapper seller-table">
        <table class="irantheme-invoice-table seller-table">
            <?php if ($paper != 'A5-landscape'): ?>
                <thead>
                    <tr>
                        <th>مشخصات فروشنده</th>
                    </tr>
                </thead>
            <?php endif; ?>
            <tbody>
                <tr>
                    <?php if ($paper == 'A5-landscape'): ?>
                        <td class='seller-meta-row-label'>فروشنده</td>
                    <?php endif; ?>
                    <td>
                        <div class="seller-meta-wrapper">
                            <div class="seller-meta-row">
                                <p><strong>نام: </strong><?php echo esc_html($seller_name != '' ? $seller_name : '---'); ?></p>
                                <p><strong>شماره اقتصادی: </strong><?php echo esc_html($seller_economy_code != '' ? $seller_economy_code : '---'); ?></p>
                                <?php if ($seller_melli_code): ?>
                                    <p><strong>شناسه ملی: </strong><?php echo esc_html($seller_melli_code); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="seller-meta-row">
                                <p><strong>تلفن: </strong><?php echo esc_html($seller_phone != '' ? $seller_phone : '---'); ?></p>
                                <p><strong>ایمیل: </strong><?php echo esc_html($seller_email != '' ? $seller_email : '---'); ?></p>
                                <p><strong>کد پستی: </strong> <?php echo esc_html($seller_post_code != '' ? $seller_post_code : '---'); ?></p>
                            </div>
                            <div class="seller-meta-row">
                                <p><strong>نشانی: </strong><?php echo esc_html($seller_address != '' ? $seller_address : '---'); ?></p>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="irantheme-invoice-table-wrapper buyer-table">
        <table class="irantheme-invoice-table buyer-table">
            <?php if ($paper != 'A5-landscape'): ?>
                <thead>
                    <tr>
                        <th>مشخصات خریدار</th>
                    </tr>
                </thead>
            <?php endif; ?>
            <tbody>
                <tr>
                    <?php if ($paper == 'A5-landscape'): ?>
                        <td class='buyer-meta-row-label'>خریدار</td>
                    <?php endif; ?>
                    <td>
                        <div class="buyer-meta-wrapper">
                            <div class="buyer-meta-row">
                                <p><strong>نام: </strong><?php echo $buyer_name != '' ? $buyer_name : '---'; ?></p>
                                <p><strong>تلفن: </strong><?php echo $buyer_phone != '' ? $buyer_phone : '---'; ?></p>
                                <p><strong>ایمیل: </strong> <?php echo esc_html($buyer_email); ?></p>
                            </div>
                            <div class="buyer-meta-row">
                                <p><strong>استان: </strong><?php echo $buyer_full_state_name != '' ? $buyer_full_state_name : '---'; ?></p>
                                <p><strong>شهر: </strong><?php echo $buyer_city != '' ? $buyer_city : '---'; ?></p>
                                <p><strong>کد پستی: </strong><?php echo $buyer_post_code != '' ? $buyer_post_code : '---'; ?></p>
                            </div>
                            <div class="buyer-meta-row">
                                <p><strong>نشانی: </strong><?php echo $buyer_address != '' ? $buyer_address : '---'; ?></p>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="irantheme-invoice-table-wrapper inv-details-table">
        <table class="irantheme-invoice-table inv-details-table">
            <thead>
                <tr>
                    <th class="id">ردیف</th>
                    <th class="product-name">شرح کالا / خدمات</th>
                    <th class="quantity">تعداد</th>
                    <th class="unit-price">قیمت واحد <span>(<?php echo $currency_symbol_txt; ?>)</span></th>
                    <th class="total">جمع کل <span>(<?php echo $currency_symbol_txt; ?>)</span></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                foreach ($order->get_items() as $item_id => $item) :
                ?>
                    <tr>
                        <td class="id"><?php echo $i++; ?></td>
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
                ?>
                    <tr class="invoice-total-row">
                        <td class="invoice-total-label" colspan="4">
                            <?php if ($total['type'] == 'total') {
                                echo $total['label'] . ' ' . $this->get_total_in_words($order);
                            } else {
                                echo $total['label'];
                            }
                            ?>
                        </td>
                        <td class="invoice-total-value">
                            <?php
                            echo strip_tags($total['value']);
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr class='invoice-desc-row'>
                    <td colspan='5'>
                        <p><strong>توصیحات: </strong><?php echo $admin_descriptions != '' ? $admin_descriptions : 'از خرید شما سپاسگزاریم.'; ?></p>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
    <?php if ($signature_visibility == 'on'): ?>
        <div class="irantheme-invoice-signature-wrapper">
            <div class="signature-box signature-buyer">
                <div class="signature-label">مهر و امضای خریدار:</div>
            </div>
            <div class="signature-box signature-seller">
                <div class="signature-stamp">
                    <?php
                    echo wp_get_attachment_image(
                        $seller_signature,
                        'small',
                        false,
                        [
                            'alt' => __('تصویر مهر و امضای فروشنده', IRANTHEME_TEXTDOMAIN),
                            'loading' => 'lazy',
                        ]
                    ); ?>
                </div>
                <div class="signature-label">مهر و امضای فروشنده:</div>
            </div>
        </div>
    <?php endif; ?>
</div>