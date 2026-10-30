<?php
if (! defined('ABSPATH')) {
    exit;
}
// Paper type
$paper = get_option('irantheme_wc_invoice_official_paper_size_orientation_select', 'A4-landscape');

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
$admin_descriptions = get_option('irantheme_wc_invoice_official_admin_descriptions');

// Buyer meta variable
$buyer_type = $order->get_meta('billing_invoice_type');
$buyer_name    = $order->get_formatted_billing_full_name();
if ($buyer_type == 'company') {
    $buyer_name    = $order->get_meta('billing_legal_company_name');
}
$buyer_phone   = $order->get_billing_phone();
$buyer_email = $order->get_billing_email();
$buyer_city = $order->get_billing_city();
$buyer_state_code = $order->get_billing_state();
$buyer_country = $order->get_billing_country();
$buyer_full_state_name = WC()->countries->states[$buyer_country][$buyer_state_code] ?? $buyer_state_code;
$buyer_post_code = $order->get_billing_postcode();
$buyer_economy_code = $order->get_meta('billing_economic_id');
$buyer_melli_code = $order->get_meta('billing_national_id');
$buyer_reg_code = $order->get_meta('billing_registration_id');
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
$watermark_text = ! $is_official_invoice ? 'فاقد اعتبار قانونی' : '';
$watermark_text = $is_preview_mode ? 'فاقد اعتبار قانونی' : '';
?>
<div class="irantheme-invoice-head irantheme-invoice-official-head">
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
        <h2 class="invoice-title"><?php echo $is_official_invoice && !$is_preview_mode ? 'صورتحساب فروش کالا و خدمات' : 'پیش‌ فاکتور فروش کالا و خدمات'; ?></h2>
        <h1 class="shop-name"><?php echo esc_html($seller_name); ?></h1>
    </div>
    <div class="order-meta">
        <table class="order-meta-table">
            <tbody>
                <tr>
                    <td class="order-meta-table-row-label">شماره:</td>
                    <td class="order-meta-table-row-val"><?php echo esc_html($official_invoice_number); ?></td>
                </tr>
                <tr>
                    <td class="order-meta-table-row-label">تاریخ:</td>
                    <td class="order-meta-table-row-val"><?php echo $order_date; ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="irantheme-invoice-content irantheme-invoice-official-content">
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
                                <p><strong>نام شخص حقیقی/حقوقی: </strong><?php echo esc_html($seller_name != '' ? $seller_name : '---'); ?></p>
                                <p><strong>شماره اقتصادی: </strong><?php echo esc_html($seller_economy_code != '' ? $seller_economy_code : '---'); ?></p>
                                <p><strong>شماره ثبت: </strong><?php echo esc_html($seller_reg_code != '' ? $seller_reg_code : '---'); ?></p>
                            </div>
                            <div class="seller-meta-row">
                                <p><strong class='full-address'>نشانی کامل: </strong><strong>استان: </strong><?php echo $seller_state != '' ? $seller_state : '---'; ?></p>
                                <p><strong>شهر: </strong><?php echo $seller_city != '' ? $seller_city : '---'; ?></p>
                                <p><strong>کد پستی: </strong> <?php echo esc_html($seller_post_code != '' ? $seller_post_code : '---'); ?></p>
                                <p><strong>شناسه ملی: </strong><?php echo esc_html($seller_melli_code != '' ? $seller_melli_code : '---'); ?></p>
                            </div>
                            <div class="seller-meta-row">
                                <p><strong>نشانی: </strong><?php echo esc_html($seller_address != '' ? $seller_address : '---'); ?></p>
                                <p><strong>تلفن: </strong><?php echo esc_html($seller_phone != '' ? $seller_phone : '---'); ?></p>
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
                                <p><strong>نام شخص حقیقی/حقوقی: </strong><?php echo $buyer_name != '' ? $buyer_name : '---'; ?></p>
                                <p><strong>شماره اقتصادی: </strong><?php echo esc_html($buyer_economy_code != '' ? $buyer_economy_code : '---'); ?></p>
                                <p><strong>شماره ثبت: </strong><?php echo esc_html($buyer_reg_code != '' ? $buyer_reg_code : '---'); ?></p>
                            </div>
                            <div class="buyer-meta-row">
                                <p><strong class="full-address">نشانی کامل: </strong><strong>استان: </strong><?php echo $buyer_full_state_name != '' ? $buyer_full_state_name : '---'; ?></p>
                                <p><strong>شهر: </strong><?php echo $buyer_city != '' ? $buyer_city : '---'; ?></p>
                                <p><strong>کد پستی: </strong><?php echo $buyer_post_code != '' ? $buyer_post_code : '---'; ?></p>
                                <p><strong>شماره/شناسه ملی: </strong><?php echo esc_html($buyer_melli_code != '' ? $buyer_melli_code : '---'); ?></p>
                            </div>
                            <div class="buyer-meta-row">
                                <p><strong>نشانی: </strong><?php echo $buyer_address != '' ? $buyer_address : '---'; ?></p>
                                <p><strong>تلفن: </strong><?php echo $buyer_phone != '' ? $buyer_phone : '---'; ?></p>
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
                    <th class="row-id">ردیف</th>
                    <th class="product-id">کد کالا</th>
                    <th class="product-name">شرح کالا یا خدمات</th>
                    <th class="quantity">تعداد / مقدار</th>
                    <th class="unit">واحد اندازه گیری</th>
                    <th class="unit-price">مبلغ واحد <span>(<?php echo $currency_symbol_txt; ?>)</span></th>
                    <th class="total">مبلغ کل <span>(<?php echo $currency_symbol_txt; ?>)</span></th>
                    <th class="discount">مبلغ تخفیف <span>(<?php echo $currency_symbol_txt; ?>)</span></th>
                    <th class="after-discount">مبلغ کل پس از تخفیف <span>(<?php echo $currency_symbol_txt; ?>)</span></th>
                    <th class="tax">جمع مالیات و عوارض <span>(<?php echo $currency_symbol_txt; ?>)</span></th>
                    <th class="final-total">جمع مبلغ کل بعلاوه جمع مالیات و عوارض <span>(<?php echo $currency_symbol_txt; ?>)</span></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $row = 1;
                // Initialize totals for summary row
                $sum_total = 0;
                $sum_discount = 0;
                $sum_after_discount = 0;
                $sum_tax = 0;
                $sum_final_total = 0;

                // Collect extra fees (packaging, etc.) to display later in tfoot
                $extra_fees = array();

                // Process all order items: line items (products),and shipping
                $all_items = $order->get_items(array('line_item', 'shipping'));

                foreach ($all_items as $item_id => $item) {
                    $item_type = $item->get_type();

                    // 1. Handle product items (line_item)
                    if ('line_item' === $item_type) {
                        $product = $item->get_product();
                        if ($product && is_object($product)) {
                            $sku = $product->get_sku() ? $product->get_sku() : '---';
                            if ($product->is_type('variation')) {
                                $unit = $product->get_meta('_variation_unit');
                            } else {
                                $unit = $product->get_meta('_product_unit');
                            }
                            $unit = ! empty($unit) ? $unit : 'عدد';
                        } else {
                            $sku = '---';
                            $unit = 'عدد';
                        }

                        $subtotal        = $order->get_item_subtotal($item, false, false);
                        $total           = $item->get_subtotal();
                        $discount        = $total - $item->get_total();
                        $after_discount  = $item->get_total();
                        $tax             = $item->get_total_tax();
                        $final_total     = $after_discount + $tax;

                        $product_name    = $item->get_name();
                        $quantity        = $item->get_quantity();
                    }
                    // 2. Handle shipping (will be displayed later as a separate row in tbody)
                    elseif ('shipping' === $item_type) {
                        // Shipping is handled separately below, so skip here to avoid duplication
                        continue;
                    }
                    // Skip any other types
                    else {
                        continue;
                    }

                    // Accumulate totals for line items
                    $sum_total += $total;
                    $sum_discount += $discount;
                    $sum_after_discount += $after_discount;
                    $sum_tax += $tax;
                    $sum_final_total += $final_total;
                ?>
                    <tr>
                        <td class="row-id"><?php echo $row++; ?></td>
                        <td class="product-id"><?php echo $sku; ?></td>
                        <td class="product-name"><?php echo $product_name; ?></td>
                        <td class="quantity"><?php echo $quantity; ?></td>
                        <td class="unit"><?php echo esc_html($unit); ?></td>
                        <td class="unit-price"><?php echo number_format($subtotal); ?></td>
                        <td class="total"><?php echo number_format($total); ?></td>
                        <td class="discount"><?php echo number_format($discount); ?></td>
                        <td class="after-discount"><?php echo number_format($after_discount); ?></td>
                        <td class="tax"><?php echo number_format($tax); ?></td>
                        <td class="final-total"><?php echo number_format($final_total); ?></td>
                    </tr>
                <?php
                } // end foreach $all_items

                // Add shipping as a separate row (after all products and before tfoot)
                $shipping_total = $order->get_shipping_total();
                $shipping_tax   = $order->get_shipping_tax();
                if ($shipping_total > 0) :
                    $shipping_final = $shipping_total + $shipping_tax;

                    // Accumulate shipping into totals
                    $sum_total += $shipping_total;
                    $sum_after_discount += $shipping_total;
                    $sum_tax += $shipping_tax;
                    $sum_final_total += $shipping_final;
                ?>
                    <tr>
                        <td class="row-id"><?php echo $row++; ?></td>
                        <td class="product-id">---</td>
                        <td class="product-name">هزینه حمل و نقل</td>
                        <td class="quantity">1</td>
                        <td class="unit">عدد</td>
                        <td class="unit-price"><?php echo number_format($shipping_total); ?></td>
                        <td class="total"><?php echo number_format($shipping_total); ?></td>
                        <td class="discount">0</td>
                        <td class="after-discount"><?php echo number_format($shipping_total); ?></td>
                        <td class="tax"><?php echo number_format($shipping_tax); ?></td>
                        <td class="final-total"><?php echo number_format($shipping_final); ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <!-- Summary row (subtotal after discount + tax before final total) -->
                <tr class="summary-row">
                    <td colspan="6"><strong>جمع کل: </strong></td>
                    <td><?php echo number_format($sum_total); ?></td>
                    <td><?php echo number_format($sum_discount); ?></td>
                    <td><?php echo number_format($sum_after_discount); ?></td>
                    <td><?php echo number_format($sum_tax); ?></td>
                    <td><?php echo number_format($sum_final_total); ?></td>
                </tr>
                <?php
                // Fee rows
                $order_fees = $order->get_items('fee');
                foreach ($order_fees as $fee_id => $fee) {
                    $fee_name    = $fee->get_name();
                    $fee_total   = $fee->get_total();
                    $fee_total_tax = $fee->get_total_tax();
                    $fee_total_with_tax = $fee_total + $fee_total_tax;
                ?>
                    <tr class="extra-fee-row">
                        <td colspan="9"><strong><?php echo esc_html($fee_name); ?>:</strong></td>
                        <td class="tax"><?php echo number_format($fee_total_tax); ?></td>
                        <td class="final-total"><?php echo number_format($fee_total_with_tax); ?></td>
                    </tr>
                <?php
                }
                ?>
                <!-- Final total row (uses WooCommerce order total) -->
                <tr class="total-row">
                    <td colspan="10">
                        <strong>جمع کل پس از کسر تخفیف با احتساب مالیات و عوارض: </strong>
                        <?php echo $this->get_total_in_words($order); ?>
                    </td>
                    <td><strong><?php echo number_format($order->get_total()); ?></strong></td>
                </tr>

                <?php if ($admin_descriptions != '') : ?>
                    <tr class="desc-row">
                        <td class="desc-label" colspan="2">توضیحات: </td>
                        <td class="desc-value" colspan="9"><?php echo esc_html($admin_descriptions); ?></td>
                    </tr>
                <?php endif; ?>
            </tfoot>
        </table>
    </div>
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
</div>