<?php

if (! defined('ABSPATH')) {
    exit;
}
if (is_woocommerce_activated()) {
    // Call invoice system classes
    require get_theme_file_path('/inc/core/woocommerce/invoice-system/class-irantheme-woocommerce-invoice-core.php');
    require get_theme_file_path('/inc/core/woocommerce/invoice-system/class-irantheme-woocommerce-invoice-hooks.php');
    require get_theme_file_path('/inc/core/woocommerce/invoice-system/class-irantheme-woocommerce-invoice-extension.php');
    require get_theme_file_path('/inc/core/woocommerce/invoice-system/class-irantheme-woocommerce-invoice-admin-table.php');
}
