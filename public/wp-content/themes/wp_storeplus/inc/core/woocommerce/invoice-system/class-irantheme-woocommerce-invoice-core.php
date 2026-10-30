<?php


namespace Irantheme_Dev;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * WooCommerce Invoice Core Class
 * * Handles URL endpoints, template rendering, and number to words conversion.
 * Also manages secure official invoice sequence generation and validation.
 * @package IranTheme_Dev
 * @author M-MALEKZADEH77
 * @since v1.0.2
 * @version v1.1.0
 */
class Irantheme_Woocommerce_Invoice_Core
{
    /**
     * Whitelisting allowed invoice types to prevent LFI attacks
     * @var array
     */
    private $allowed_invoice_types = [
        'customer',
        'official',
        'shipping',
        'thermal',
        'shipping-thermal'
    ];

    /**
     * Constructor. Hooks into WordPress actions to set up the invoice endpoint,
     * query variables, template rendering, and rewrite rule flushing.
     *
     * @return void
     * @author M-MALEKZADEH77
     * @since v1.0.2
     * @version v1.0
     */
    public function __construct()
    {
        // Create custom endpoint
        add_action('init', [$this, 'add_invoice_endpoint'], 1);
        add_filter('query_vars', [$this, 'add_invoice_query_vars']);

        // Render invoice template
        add_action('template_redirect', [$this, 'render_invoice_template']);

        // Automatically flush rewrite rules on theme activation
        add_action('after_switch_theme', [$this, 'flush_rewrite_rules_on_activation']);
    }

    /**
     * Generates a secure print URL for an invoice or shipping label.
     * Supports both pretty permalinks and plain permalinks.
     * Adds a nonce token for security validation.
     *
     * @param string     $invoice_type Type of invoice (customer, official, shipping, thermal, shipping-thermal).
     * @param int|array  $order_ids    Single order ID or array of order IDs for bulk printing.
     * @return string                  Full URL with nonce token.
     * @author M-MALEKZADEH77
     * @since v1.0.2
     * @version v1.0
     */
    public static function get_print_url($invoice_type, $order_ids)
    {
        $is_bulk = is_array($order_ids);

        if ($is_bulk) {

            $ids = array_map('absint', $order_ids);

            $token = wp_create_nonce('irantheme_bulk_invoice');

            $bulk_key = wp_generate_uuid4();

            set_transient(
                'irantheme_bulk_invoice_' . $bulk_key,
                $ids,
                MINUTE_IN_SECONDS * 10
            );

            if (get_option('permalink_structure')) {

                return add_query_arg(
                    [
                        'bulk_key' => $bulk_key,
                        'token'    => $token,
                    ],
                    home_url('/print-invoice/' . rawurlencode($invoice_type) . '/bulk/')
                );
            }

            return add_query_arg(
                [
                    'print-invoice' => 'bulk',
                    'inv_type'      => $invoice_type,
                    'bulk_key'      => $bulk_key,
                    'token'         => $token,
                ],
                home_url('/')
            );
        }

        $order_id = absint($order_ids);

        $token = wp_create_nonce('irantheme_invoice_' . $order_id);

        if (get_option('permalink_structure')) {

            return add_query_arg(
                [
                    'token' => $token,
                ],
                home_url('/print-invoice/' . rawurlencode($invoice_type) . '/' . $order_id . '/')
            );
        }

        return add_query_arg(
            [
                'print-invoice' => $order_id,
                'inv_type'      => $invoice_type,
                'token'         => $token,
            ],
            home_url('/')
        );
    }

    /**
     * Flushes WordPress rewrite rules on theme activation.
     * Ensures the custom invoice endpoint is registered and rules are rebuilt.
     *
     * @return void
     * @author M-MALEKZADEH77
     * @since v1.0.2
     * @version v1.0
     */
    public function flush_rewrite_rules_on_activation()
    {
        $this->add_invoice_endpoint();
        flush_rewrite_rules();
    }

    /**
     * Adds a custom rewrite rule for the invoice endpoint.
     * Format: site.com/print-invoice/{invoice_type}/{order_ids}/
     *
     * @return void
     * @author M-MALEKZADEH77
     * @since v1.0.2
     * @version v1.0
     */
    public function add_invoice_endpoint()
    {
        add_rewrite_rule(
            '^print-invoice/([^/]+)/([^/]+)/?$',
            'index.php?inv_type=$matches[1]&print-invoice=$matches[2]',
            'top'
        );
    }

    /**
     * Adds custom query variables for the invoice endpoint.
     *
     * @param array $vars Existing query variables.
     * @return array      Modified query variables with 'print-invoice' and 'inv_type'.
     * @author M-MALEKZADEH77
     * @since v1.0.2
     * @version v1.0
     */
    public function add_invoice_query_vars($vars)
    {
        $vars[] = 'print-invoice';
        $vars[] = 'inv_type';
        return $vars;
    }

    /**
     * Checks if an order is eligible for official invoice generation.
     * Eligibility requires legal information to be complete based on buyer type
     * (person needs national ID; company needs company name, national ID, registration ID, economic ID).
     *
     * @param \WC_Order $order The order object.
     * @return bool            True if eligible, false otherwise.
     * @author M-MALEKZADEH77
     * @since v1.0.2
     * @version v1.0
     */
    public static function is_order_eligible_for_official_invoice($order)
    {
        if (!$order) {
            return false;
        }

        // Get field information from order meta (copied from account class)
        $invoice_type = $order->get_meta('billing_invoice_type') ?: 'person';
        $national_id  = $order->get_meta('billing_national_id');
        $company_name = $order->get_meta('billing_legal_company_name');
        $reg_id       = $order->get_meta('billing_registration_id');
        $econ_id      = $order->get_meta('billing_economic_id');

        // Logic for checking the accuracy of fields based on the type of buyer
        if ($invoice_type === 'person') {
            // Natural person: National ID is required
            if (empty($national_id)) {
                return false;
            }
        } else {
            // Legal entity: Company name, national ID, registration number, and economic code are required.
            if (empty($company_name) || empty($national_id) || empty($reg_id) || empty($econ_id)) {
                return false;
            }
        }

        return true;
    }


    /**
     * Securely generates the next sequential official invoice number.
     * Uses a database transaction with retry logic to prevent duplicates.
     * Handles prefix, padding, and optional yearly reset.
     *
     * @param int $order_id The order ID.
     * @return string|bool  Formatted invoice number, or false if not eligible or generation fails.
     * @author M-MALEKZADEH77
     * @since v1.0.2
     * @version v1.0
     */
    public static function generate_official_invoice_number_safely($order_id)
    {
        $order = wc_get_order($order_id);
        if (!$order) return false;
        $existing = $order->get_meta('_irantheme_wc_official_invoice_number');
        if (!empty($existing)) return $existing;
        if (!self::is_order_eligible_for_official_invoice($order)) return false;

        global $wpdb;
        $max_attempts = 5;
        for ($attempt = 1; $attempt <= $max_attempts; $attempt++) {
            $next_number = (int) get_option('irantheme_wc_invoice_official_serial_next_number', 1001);
            $new_number = $next_number + 1;
            $updated = $wpdb->query($wpdb->prepare(
                "UPDATE {$wpdb->options} SET option_value = %s 
             WHERE option_name = 'irantheme_wc_invoice_official_serial_next_number' AND option_value = %s",
                $new_number,
                $next_number
            ));
            if ($updated) {
                $prefix = get_option('irantheme_wc_invoice_official_serial_prefix', 'INV-{year}-');
                $padding = (int) get_option('irantheme_wc_invoice_official_serial_padding', 4);
                $auto_reset = get_option('irantheme_wc_invoice_official_serial_auto_reset_checkbox', 'off');
                $reset_start = (int) get_option('irantheme_wc_invoice_official_serial_reset_start_number', 1001);
                $current_year = function_exists('jdate') ? jdate('Y') : date('Y');
                $last_saved_year = get_option('irantheme_wc_invoice_official_serial_last_year', $current_year);
                if ($auto_reset === 'on' && $current_year !== $last_saved_year) {
                    $next_number = $reset_start;
                    $new_number = $next_number + 1;
                    update_option('irantheme_wc_invoice_official_serial_last_year', $current_year);
                    update_option('irantheme_wc_invoice_official_serial_next_number', $new_number);
                }
                $formatted_number = str_pad((string) $next_number, $padding, '0', STR_PAD_LEFT);
                $final_prefix = str_replace('{year}', $current_year, $prefix);
                $final_invoice_number = $final_prefix . $formatted_number;
                $order->update_meta_data('_irantheme_wc_official_invoice_number', $final_invoice_number);
                $order->save();
                return $final_invoice_number;
            }
            if ($attempt < $max_attempts) usleep(10000);
        }
        error_log("Invoice number generation failed after {$max_attempts} attempts for order {$order_id}");
        return false;
    }

    /**
     * Main template renderer for invoice and shipping label printing.
     * Handles single and bulk requests, validates nonce and permissions,
     * and includes the appropriate layout template.
     *
     * @return void
     * @author M-MALEKZADEH77
     * @since v1.0.2
     * @version v1.0
     */
    public function render_invoice_template()
    {
        global $wp_query;

        // Check if we are on invoice endpoint
        if (
            !isset($wp_query->query_vars['print-invoice'])
            && !isset($_GET['print-invoice'])
        ) {
            return;
        }

        // Fix 404 when plain permalink mode is active
        $wp_query->is_404 = false;
        status_header(200);

        // Get order IDs from URL
        $order_ids_raw = isset($wp_query->query_vars['print-invoice'])
            ? sanitize_text_field($wp_query->query_vars['print-invoice'])
            : sanitize_text_field($_GET['print-invoice'] ?? '');

        // Detect bulk mode: if IDs contain comma, it's bulk printing
        $is_bulk = ($order_ids_raw === 'bulk');

        // SECURITY: Nonce verification
        if ($is_bulk) {

            if (
                !isset($_GET['token']) ||
                !wp_verify_nonce($_GET['token'], 'irantheme_bulk_invoice')
            ) {
                wp_die(
                    'دسترسی غیرمجاز.',
                    'خطای امنیتی',
                    ['response' => 403]
                );
            }

            $bulk_key = sanitize_text_field($_GET['bulk_key'] ?? '');

            $order_ids = get_transient(
                'irantheme_bulk_invoice_' . $bulk_key
            );

            if (empty($order_ids) || !is_array($order_ids)) {
                wp_die(
                    'لینک چاپ گروهی منقضی شده است.',
                    'خطا',
                    ['response' => 403]
                );
            }

            delete_transient(
                'irantheme_bulk_invoice_' . $bulk_key
            );
        } else {

            if (
                !isset($_GET['token']) ||
                !wp_verify_nonce($_GET['token'], 'irantheme_invoice_' . $order_ids_raw)
            ) {
                wp_die(
                    'دسترسی غیرمجاز.',
                    'خطای امنیتی',
                    ['response' => 403]
                );
            }

            $order_ids = [
                absint($order_ids_raw)
            ];
        }

        if (empty($order_ids)) {
            wp_die('هیچ سفارشی برای چاپ دریافت نشد.', 'خطا درخواست', ['response' => 400]);
        }

        // Get and validate invoice type (whitelist check)
        $invoice_type = isset($wp_query->query_vars['inv_type'])
            ? sanitize_text_field($wp_query->query_vars['inv_type'])
            : (
                isset($_GET['inv_type'])
                ? sanitize_text_field($_GET['inv_type'])
                : 'customer'
            );

        if (!in_array($invoice_type, $this->allowed_invoice_types)) {
            wp_die('نوع فاکتور نامعتبر یا غیرمجاز است.', 'خطای امنیتی', ['response' => 403]);
        }

        // DETERMINE LAYOUT RENDERER BASED ON INVOICE TYPE
        // Get path of invoice renderer file
        $layout_renderer = IRANTHEME_DIR . '/inc/core/woocommerce/invoice-system/render-invoice-layout.php';
        // Shipping label type
        $label_type = null;
        $bulk_page_title = 'چاپ گروهی فاکتور';
        // Special handling for shipping labels
        if ($invoice_type === 'shipping') {
            $label_type = 'paper-label';
            // Get path of shipping label renderer file
            $layout_renderer = IRANTHEME_DIR . '/inc/core/woocommerce/invoice-system/render-shipping-label-layout.php';
            $bulk_page_title = 'چاپ گروهی برچسب پستی';
        } elseif ($invoice_type === 'shipping-thermal') {
            $label_type = 'thermal-label';
            // Get path of shipping label renderer file
            $layout_renderer = IRANTHEME_DIR . '/inc/core/woocommerce/invoice-system/render-shipping-label-layout.php';
            $bulk_page_title = 'چاپ گروهی برچسب پستی';
        }

        if (!file_exists($layout_renderer)) {
            wp_die('هیچ قالب نمایشی برای چاپ این فاکتور یافت نشد.', 'خطای فنی', ['response' => 500]);
        }

        // RENDER INVOICES
        ob_start();

        $current_user_id = get_current_user_id();
        $is_admin = current_user_can('manage_woocommerce');
        $total_orders = count($order_ids);

        if ($is_bulk) {
            echo '<div class="irantheme-invoice-system-action-bar no-print">
            <p class="action-bar-title">' . $bulk_page_title . '</p>
            <div class="action-bar-btn">
                <button class="btn-print" onclick="window.print()">
                    چاپ
                </button>
                <button class="btn-back" onclick="window.history.back()">
                    بازگشت
                </button>
            </div>
        </div>';
        }
        foreach ($order_ids as $index => $id) {
            $order = wc_get_order($id);
            if (!$order) continue;

            $is_owner = $order->get_customer_id() === $current_user_id;
            if (!$is_admin && !$is_owner) continue;

            // Dynamic handling of the official invoice before loading the template
            $official_invoice_number = '';
            $is_preview_mode = false;

            if ($invoice_type === 'official') {
                $generated_number = self::generate_official_invoice_number_safely($id);
                if (!$generated_number) {
                    $is_preview_mode = true;
                    // Display dash due to insufficient customer legal information
                    $official_invoice_number = '------';
                } else {
                    $official_invoice_number = $generated_number;
                }
            }

            // For shipping labels, pass $label_type to the layout renderer
            include $layout_renderer;

            if ($index < $total_orders - 1) {
                echo '<div class="print-page-break" style="page-break-after: always;"></div>';
            }
        }

        $output = ob_get_clean();

        if (empty($output)) {
            wp_die('هیچ سفارشی یافت نشد یا مجوز مشاهده این فاکتورها را ندارید.', 'خطای دسترسی', ['response' => 403]);
        }

        echo $output;
        exit;
    }

    /**
     * Converts a number to Persian words.
     *
     * @param int|float|string $number The number to convert.
     * @return string                  The number in Persian words.
     * @author M-MALEKZADEH77
     * @since v1.0.2
     * @version v1.0
     */
    private function number_to_words($number)
    {
        $number = (int) $number;
        if ($number == 0) return 'صفر';
        return $this->convert_integer_to_words($number);
    }

    /**
     * Converts an integer to Persian words, handling thousands, millions, etc.
     *
     * @param int $number The integer to convert.
     * @return string     The integer in Persian words.
     * @author M-MALEKZADEH77
     * @since v1.0.2
     * @version v1.0
     */
    private function convert_integer_to_words($number)
    {
        if ($number == 0) return 'صفر';

        $groups = [
            0 => '',
            1 => 'هزار',
            2 => 'میلیون',
            3 => 'میلیارد',
            4 => 'بیلیون',
            5 => 'تریلیون'
        ];

        $result = [];
        $group_index = 0;

        while ($number > 0) {
            $three_digit = $number % 1000;

            if ($three_digit > 0) {
                $words = $this->convert_three_digit($three_digit);
                if ($group_index > 0) {
                    $words .= ' ' . $groups[$group_index];
                }
                array_unshift($result, $words);
            }

            $number = (int) floor($number / 1000);
            $group_index++;
        }

        return implode(' و ', $result);
    }

    /**
     * Converts a three‑digit number to Persian words.
     *
     * @param int $number The three‑digit number (0‑999).
     * @return string     The three‑digit number in Persian words.
     * @author M-MALEKZADEH77
     * @since v1.0.2
     * @version v1.0
     */
    private function convert_three_digit($number)
    {
        $ones = ['', 'یک', 'دو', 'سه', 'چهار', 'پنج', 'شش', 'هفت', 'هشت', 'نه'];
        $tens = ['', 'ده', 'بیست', 'سی', 'چهل', 'پنجاه', 'شصت', 'هفتاد', 'هشتاد', 'نود'];
        $hundreds = ['', 'یکصد', 'دویست', 'سیصد', 'چهارصد', 'پانصد', 'ششصد', 'هفتصد', 'هشتصد', 'نهصد'];
        $teens = ['ده', 'یازده', 'دوازده', 'سیزده', 'چهارده', 'پانزده', 'شانزده', 'هفده', 'هجده', 'نوزده'];

        $parts = [];

        $hundred = (int) floor($number / 100);
        if ($hundred > 0) $parts[] = $hundreds[$hundred];

        $remainder = $number % 100;

        if ($remainder >= 10 && $remainder <= 19) {
            $parts[] = $teens[$remainder - 10];
        } else {
            $ten = (int) floor($remainder / 10);
            $one = $remainder % 10;

            if ($ten > 0) $parts[] = $tens[$ten];
            if ($one > 0) $parts[] = $ones[$one];
        }

        return implode(' و ', $parts);
    }

    /**
     * Returns the order total amount in Persian words with currency name.
     *
     * @param \WC_Order $order The order object.
     * @return string          The total amount in words (e.g., "یک هزار تومان").
     * @author M-MALEKZADEH77
     * @since v1.0.2
     * @version v1.0
     */
    public function get_total_in_words($order)
    {
        $total = (int) $order->get_total();
        $currency = get_woocommerce_currency();
        $currency_name = ($currency === 'IRT') ? 'تومان' : 'ریال';
        $words = $this->number_to_words($total);

        return $words . ' ' . $currency_name;
    }
}

// Initialize the class
new Irantheme_Woocommerce_Invoice_Core();
