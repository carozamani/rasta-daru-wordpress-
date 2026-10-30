<?php

/**
 * Shipping Label Layout Renderer
 * 
 * This file renders the HTML wrapper for all shipping label types.
 * It loads the correct label template and paper size automatically.
 *
 * Supported label types:
 * - paper-label
 * - thermal-label
 *
 * Supported paper sizes:
 * - A5 portrait / landscape
 * - A6 portrait / landscape
 * - 100x150 portrait
 * - 100x70 landscape
 * - 80x50 landscape
 *
 * Required variables (passed from class-irantheme-woocommerce-invoice-core.php):
 * - $order: WC_Order object
 * - $label_type: string ('paper-label' or 'thermal-label')
 *
 * @package IranTheme_Dev
 * @author M-MALEKZADEH77
 * @since v1.0.2
 */

if (!defined('ABSPATH')) {
    exit;
}

// VALIDATE VARIABLES
if (!isset($order) || !is_object($order)) {
    wp_die('شیء سفارش در رندرکننده برچسب حمل و نقل وجود ندارد.');
}

if (!isset($label_type) || empty($label_type)) {
    wp_die('نوع برچسب در رندرکننده برچسب حمل و نقل وجود ندارد.');
}

// LABEL CONFIGURATION
$label_options = [
    'paper-label' => [
        'option'  => 'irantheme_wc_shipping_label_paper_size_orientation_select',
        'default' => 'A5-landscape',
        'styles'  => [
            'A5-portrait' => [
                'css'   => 'paper-label/shipping-paper-label-A5-portrait.css',
                'class' => 'size-A5-portrait',
            ],
            'A5-landscape' => [
                'css'   => 'paper-label/shipping-paper-label-A5-landscape.css',
                'class' => 'size-A5-landscape',
            ],
            'A6-portrait' => [
                'css'   => 'paper-label/shipping-paper-label-A6-portrait.css',
                'class' => 'size-A6-portrait',
            ],
            'A6-landscape' => [
                'css'   => 'paper-label/shipping-paper-label-A6-landscape.css',
                'class' => 'size-A6-landscape',
            ],
        ],
    ],
    'thermal-label' => [
        'option'  => 'irantheme_wc_shipping_thermal_label_paper_size_orientation_select',
        'default' => '150x100-landscape',
        'styles'  => [
            '100x150-portrait' => [
                'css'   => 'thermal-label/shipping-thermal-label-100x150-portrait.css',
                'class' => 'size-100x150-portrait',
            ],
            '150x100-landscape' => [
                'css'   => 'thermal-label/shipping-thermal-label-150x100-landscape.css',
                'class' => 'size-150x100-landscape',
            ],
            '100x100-square' => [
                'css'   => 'thermal-label/shipping-thermal-label-100x100-square.css',
                'class' => 'size-100x100-square',
            ],
            '50x80-portrait' => [
                'css'   => 'thermal-label/shipping-thermal-label-50x80-portrait.css',
                'class' => 'size-50x80-portrait',
            ],
            '80x50-landscape' => [
                'css'   => 'thermal-label/shipping-thermal-label-80x50-landscape.css',
                'class' => 'size-80x50-landscape',
            ],
        ],
    ],
];

// VALIDATE LABEL TYPE

if (!isset($label_options[$label_type])) {
    wp_die('نوع برچسب نامعتبر: ' . esc_html($label_type));
}

$config = $label_options[$label_type];

// GET PAPER SIZE FROM DATABASE

$paper = get_option($config['option'], $config['default']);

/*
|--------------------------------------------------------------------------
| Admin Debug Override (via URL parameter)
|--------------------------------------------------------------------------
| Example: ?label_size=A6-portrait
*/
if (current_user_can('manage_woocommerce') && isset($_GET['label_size'])) {
    $override = sanitize_text_field($_GET['label_size']);
    if (isset($config['styles'][$override])) {
        $paper = $override;
    }
}

/* =====================================================
 * 5. VALIDATE PAPER SIZE
===================================================== */

if (!isset($config['styles'][$paper])) {
    $paper = $config['default'];
}

/* =====================================================
 * 6. CSS FILE PATH
===================================================== */
$template_css = IRANTHEME_URI
    . '/inc/core/woocommerce/invoice-system/assets/css/templates/shipping/'
    . $config['styles'][$paper]['css'];

/* =====================================================
 * 7. BODY CLASS
===================================================== */

$body_class = 'irantheme-shipping-label irantheme-shipping-' . esc_attr($label_type) . ' ' . esc_attr($config['styles'][$paper]['class']);

/* =====================================================
 * 8. TEMPLATE FILE PATH
===================================================== */
$template_file = IRANTHEME_DIR
    . '/inc/core/woocommerce/invoice-system/templates/shipping/'
    . $label_type . '/irantheme-shipping-' . $label_type . '.php';

if (!file_exists($template_file)) {
    wp_die('قالب برچسب یافت نشد: ' . esc_html($template_file));
}

/* =====================================================
 * 9. GET TEMPLATE CONTENT
===================================================== */

ob_start();
include $template_file;
$template_content = ob_get_clean();

/* =====================================================
 * 10. RENDER HTML WRAPPER
===================================================== */
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    $page_title = $is_bulk ? 'چاپ گروهی برچسب پستی' : 'برچسب پستی ' . esc_html($order->get_order_number());
    ?>
    <title><?php echo $page_title; ?></title>

    <!-- Core CSS files -->
    <link rel="stylesheet" href="<?php echo IRANTHEME_URI; ?>/assets/admin/css/init.css">
    <link rel="stylesheet" href="<?php echo IRANTHEME_URI; ?>/assets/admin/css/reset.css">
    <link rel="stylesheet" href="<?php echo IRANTHEME_URI; ?>/inc/core/woocommerce/invoice-system/assets/css/core/base.css">
    <link rel="stylesheet" href="<?php echo IRANTHEME_URI; ?>/inc/core/woocommerce/invoice-system/assets/css/core/screen.css">
    <link rel="stylesheet" href="<?php echo IRANTHEME_URI; ?>/inc/core/woocommerce/invoice-system/assets/css/core/print.css">

    <!-- Label-specific CSS -->
    <link rel="stylesheet" href="<?php echo esc_url($template_css); ?>">

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
    <div class="irantheme-shipping-label-preview-area">
        <div class="irantheme-shipping-label-paper <?php echo esc_attr($config['styles'][$paper]['class']); ?>">
            <!-- Template content (injected here) -->
            <?php echo $template_content; ?>
        </div>
    </div>
</body>

</html>
<?php
// End of file