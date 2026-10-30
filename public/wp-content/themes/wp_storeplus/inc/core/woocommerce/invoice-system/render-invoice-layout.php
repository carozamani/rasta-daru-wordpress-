<?php

/**
 * Invoice Layout Renderer
 * 
 * This file renders the HTML wrapper (DOCTYPE, head, body) for all invoice types.
 * It loads the appropriate template content based on invoice type.
 * 
 * Required variables (passed from core.php):
 * - $order: WC_Order object
 * - $invoice_type: string (customer, official, thermal, shipping, shipping-thermal)
 * - $is_admin: boolean (optional)
 * - $current_user_id: int (optional)
 * 
 * @package IranTheme_Dev
 * @author M-MALEKZADEH77
 * @since v1.0.2
 */

if (!defined('ABSPATH')) {
    exit;
}

// =============================================
// VALIDATE REQUIRED VARIABLES
// =============================================

// Check if required variables are set (they come from core.php)
if (!isset($order) || !is_object($order)) {
    wp_die('Required variable $order is missing in layout renderer.');
}

if (!isset($invoice_type) || empty($invoice_type)) {
    wp_die('Required variable $invoice_type is missing in layout renderer.');
}

// =============================================
// 1. PAPER CONFIGURATION
// =============================================

// Paper settings for each invoice type
$paper_options = [
    'customer' => [
        'option'  => 'irantheme_wc_invoice_customer_paper_size_orientation_select',
        'default' => 'A4-portrait',
        'styles'  => [
            'A4-portrait'  => 'customer/invoice-customer-A4-portrait.css',
            'A5-portrait'  => 'customer/invoice-customer-A5-portrait.css',
            'A5-landscape' => 'customer/invoice-customer-A5-landscape.css',
        ],
    ],
    'official' => [
        'option'  => 'irantheme_wc_invoice_official_paper_size_orientation_select',
        'default' => 'A4-landscape',
        'styles'  => [
            'A4-landscape' => 'official/invoice-official-A4-landscape.css',
        ],
    ],
    'thermal' => [
        'option'  => 'irantheme_wc_invoice_thermal_paper_size_orientation_select',
        'default' => '80mm-portrait',
        'styles'  => [
            '80mm-portrait' => 'thermal/invoice-thermal-80mm-portrait.css',
        ],
    ],
    'shipping' => [
        'option'  => 'irantheme_wc_invoice_shipping_paper_size_orientation_select',
        'default' => 'A5-landscape',
        'styles'  => [
            'A5-landscape' => 'shipping/invoice-shipping-A5-landscape.css',
        ],
    ],
    'shipping-thermal' => [
        'option'  => 'irantheme_wc_invoice_shipping_thermal_paper_size_orientation_select',
        'default' => 'thermal-landscape',
        'styles'  => [
            'thermal-landscape' => 'shipping-thermal/invoice-shipping-thermal-landscape.css',
        ],
    ],
];

// Validate invoice type
if (!isset($paper_options[$invoice_type])) {
    wp_die('Invalid invoice type: ' . esc_html($invoice_type));
}

$config = $paper_options[$invoice_type];

// Get saved paper size from database
$paper = get_option($config['option'], $config['default']);

// Admin override via URL parameter (for testing/debugging only)
if (current_user_can('manage_woocommerce') && isset($_GET['inv_paper_size'])) {
    $override = sanitize_text_field($_GET['inv_paper_size']);
    if (isset($config['styles'][$override])) {
        $paper = $override;
    }
}

// Validate paper size exists
if (!isset($config['styles'][$paper])) {
    $paper = $config['default'];
}

// Get CSS file path
$template_css = IRANTHEME_URI . '/inc/core/woocommerce/invoice-system/assets/css/templates/' . $config['styles'][$paper];

// Get body class
$body_class = 'irantheme-invoice irantheme-invoice-' . esc_attr($invoice_type) . ' paper-' . esc_attr($paper);

// =============================================
// 2. GET TEMPLATE CONTENT
// =============================================

// Template file path (only body content, no HTML wrapper)
// Note: $invoice_type is already defined and validated
$template_file = IRANTHEME_DIR . '/inc/core/woocommerce/invoice-system/templates/' . $invoice_type . '/irantheme-invoice-' . $invoice_type . '.php';

if (!file_exists($template_file)) {
    wp_die('قالب یافت نشد: ' . esc_html($template_file));
}

// Start output buffering to capture template content
ob_start();

// Make sure $order is available inside the template
// Also pass $currency_symbol_txt if needed (will be defined in template)
include $template_file;
$template_content = ob_get_clean();

$page_title = 'فاکتور شماره: ' . esc_html($order->get_order_number());
// =============================================
// 3. RENDER HTML WRAPPER
// =============================================
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo $page_title; ?></title>

    <!-- Core CSS files -->
    <link rel="stylesheet" href="<?php echo IRANTHEME_URI; ?>/assets/admin/css/init.css">
    <link rel="stylesheet" href="<?php echo IRANTHEME_URI; ?>/assets/admin/css/reset.css">
    <link rel="stylesheet" href="<?php echo IRANTHEME_URI; ?>/inc/core/woocommerce/invoice-system/assets/css/core/base.css">
    <link rel="stylesheet" href="<?php echo IRANTHEME_URI; ?>/inc/core/woocommerce/invoice-system/assets/css/core/screen.css">
    <link rel="stylesheet" href="<?php echo IRANTHEME_URI; ?>/inc/core/woocommerce/invoice-system/assets/css/core/print.css">

    <!-- Template-specific CSS -->
    <link rel="stylesheet" href="<?php echo esc_url($template_css); ?>">

    <!-- Print page break styles for bulk printing -->
    <style>
        .print-page-break {
            page-break-after: always;
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>

<body class="<?php echo esc_attr($body_class); ?>">
    <?php if (!$is_bulk): ?>
        <!-- Print controls (visible only on screen, hidden in print) -->
        <div class="irantheme-invoice-system-action-bar no-print">
            <p class="action-bar-title"><?php echo $page_title; ?></p>
            <div class="action-bar-btn">
                <button class="btn-print" onclick="window.print()">
                    چاپ
                </button>
                <button class="btn-back" onclick="window.history.back()">
                    بازگشت
                </button>
            </div>
        </div>
    <?php endif; ?>
    <div class="irantheme-invoice-preview-area">
        <div class="irantheme-invoice-paper size-<?php echo esc_attr($paper); ?>">

            <!-- Template content (injected here) -->
            <?php echo $template_content; ?>


        </div>
    </div>
</body>

</html>
<?php
// End of file