<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * WooCommerce Official Invoice Extension Class
 * * Manages user legal information fields in the account area.
 * * Handles manual, semi-auto, and full-auto invoice request processing.
 * * Adds admin order meta boxes for invoice status and legal data.
 * * Auto-generates official invoices on order status change based on mode.
 *
 * @package IranTheme_Dev
 * @author M-MALEKZADEH77
 * @since v1.0.2
 * @version v1.0.2
 */
class Irantheme_Woocommerce_Invoice_Extension
{
    /**
     * Constructor. Hooks into WordPress and WooCommerce actions to set up the extension.
     *
     * @return void
     * @author M-MALEKZADEH77
     * @since v1.0.2
     * @version v1.0
     */
    public function __construct()
    {
        // Quick buttons metabox in the order edit sidebar
        add_action('add_meta_boxes', [$this, 'add_invoice_quick_actions_metabox'], 10, 2);

        // User account fields
        add_action('woocommerce_edit_account_form', [$this, 'add_fields_to_edit_account_form']);
        add_action('woocommerce_save_account_details', [$this, 'save_edit_account_form_fields']);
        add_action('woocommerce_checkout_create_order', [$this, 'copy_legal_meta_from_user_to_order'], 10, 2);

        // Process the invoice request
        add_action('template_redirect', [$this, 'handle_invoice_request']);

        // Invoice box in user account
        add_action('woocommerce_view_order', [$this, 'render_order_details_invoice_box'], 20);

        // Admin management
        add_action('woocommerce_admin_order_data_after_billing_address', [$this, 'render_admin_invoice_status']);
        add_action('woocommerce_process_shop_order_meta', [$this, 'save_admin_invoice_status']);

        // Auto-export in full_auto mode
        add_action('woocommerce_order_status_changed', [$this, 'auto_generate_on_order_status_change'], 10, 4);
        add_action('woocommerce_order_status_changed', [$this, 'auto_approve_pending_on_status_change'], 20, 4);

        // Hide our invoice meta keys from the Custom Fields metabox
        add_filter('is_protected_meta', [$this, 'hide_invoice_meta_from_custom_fields'], 10, 3);
    }

    /**
     * Adds a meta box for quick invoice and label printing actions to the order edit page.
     * The meta box is placed in the side column on order edit screens for both classic
     * `shop_order` post type and the newer WooCommerce custom order table screen (`wc-orders`).
     *
     * @param string        $post_type  The current post type (unused, but required for add_meta_box hook).
     * @param WP_Post|null  $post       The current post object (unused, required for hook compatibility).
     * @return void
     * @author M-MALEKZADEH77
     * @since v1.0.2
     * @version v1.0
     */
    public function add_invoice_quick_actions_metabox($post_type = '', $post = null)
    {
        $screens = ['shop_order', 'woocommerce_page_wc-orders'];
        foreach ($screens as $screen) {
            add_meta_box(
                'irantheme_invoice_quick_actions',
                'چاپ سریع فاکتور و برچسب',
                [$this, 'render_invoice_quick_actions_metabox'],
                $screen,
                'side',
                'default'
            );
        }
    }

    /**
     * Render quick actions metabox (side) with three-state logic
     * 
     * @param WP_Post|null $post The current post object
     * @return void
     * @author M-MALEKZADEH77
     * @since v1.0.2
     * @version v1.0
     */
    public function render_invoice_quick_actions_metabox($post)
    {
        $order = wc_get_order($post->ID);
        if (!$order) return;
        $order_id = $order->get_id();

        $existing_number = $order->get_meta('_irantheme_wc_official_invoice_number');
        $eligible = Irantheme_Woocommerce_Invoice_Core::is_order_eligible_for_official_invoice($order);

        // 1. If invoice already issued → simple view button
        if (!empty($existing_number)) {
            $official_button = '<a class="button button-primary" href="' . esc_url(\Irantheme_Dev\Irantheme_Woocommerce_Invoice_Core::get_print_url('official', $order_id)) . '" target="_blank"><i class="fa-light fa-print"></i> مشاهده فاکتور رسمی</a>';
        }
        // 2. Not issued but legal info complete → issue button with confirmation
        elseif ($eligible) {
            $confirm_attr = ' onclick="return confirm(\'آیا مطمئن هستید؟ با صدور فاکتور رسمی، شماره فاکتور تولید خواهد شد.\');"';
            $official_button = '<a class="button button-primary" href="' . esc_url(\Irantheme_Dev\Irantheme_Woocommerce_Invoice_Core::get_print_url('official', $order_id)) . '" target="_blank"' . $confirm_attr . '><i class="fa-light fa-print"></i> صدور فاکتور رسمی</a>';
        }
        // 3. Not issued and legal info incomplete → view preview (invalid invoice)
        else {
            $official_button = '<a class="button button-primary" href="' . esc_url(\Irantheme_Dev\Irantheme_Woocommerce_Invoice_Core::get_print_url('official', $order_id)) . '" target="_blank"><i class="fa-light fa-print"></i> مشاهده فاکتور رسمی (پیش‌فاکتور)</a>';
        }
?>
        <style>
            .irantheme-quick-actions .button {
                display: block !important;
                width: 100%;
                text-align: center;
                padding: 8px 12px !important;
                margin: 4px 0 !important;
                border-radius: 6px !important;
                font-weight: 500;
                transition: all 0.2s ease;
            }

            .irantheme-quick-actions .button-primary {
                background: #346df9 !important;
                border-color: #2a5ad6 !important;
                color: #fff !important;
            }

            .irantheme-quick-actions .button-primary:hover {
                background: #2a5ad6 !important;
                transform: translateY(-1px);
            }
        </style>
        <div class="irantheme-quick-actions" style="display:flex; flex-direction:column; gap:6px;">
            <a class="button button-primary" href="<?php echo esc_url(\Irantheme_Dev\Irantheme_Woocommerce_Invoice_Core::get_print_url('customer', $order_id)); ?>" target="_blank"><i class="fa-light fa-print"></i> فاکتور عادی</a>
            <?php echo $official_button; ?>
            <a class="button button-primary" href="<?php echo esc_url(\Irantheme_Dev\Irantheme_Woocommerce_Invoice_Core::get_print_url('shipping', $order_id)); ?>" target="_blank"><i class="fa-light fa-print"></i> رسید پستی</a>
            <a class="button button-primary" href="<?php echo esc_url(\Irantheme_Dev\Irantheme_Woocommerce_Invoice_Core::get_print_url('thermal', $order_id)); ?>" target="_blank"><i class="fa-light fa-print"></i> فاکتور حرارتی</a>
            <a class="button button-primary" href="<?php echo esc_url(\Irantheme_Dev\Irantheme_Woocommerce_Invoice_Core::get_print_url('shipping-thermal', $order_id)); ?>" target="_blank"><i class="fa-light fa-print"></i> برچسب پستی حرارتی</a>
        </div>
    <?php
    }

    /**
     * Checking the completeness of the user's legal information (based on profile meta) 
     *     
     * @param int $user_id User ID
     * @return void
     * @author M-MALEKZADEH77
     * @since v1.0.2
     * @version v1.0
     */
    private function is_invoice_profile_complete($user_id)
    {
        $invoice_type = get_user_meta($user_id, 'billing_invoice_type', true) ?: 'person';
        $national_id  = get_user_meta($user_id, 'billing_national_id', true);
        $company_name = get_user_meta($user_id, 'billing_legal_company_name', true);
        $reg_id       = get_user_meta($user_id, 'billing_registration_id', true);
        $econ_id      = get_user_meta($user_id, 'billing_economic_id', true);

        if ($invoice_type === 'person') {
            return !empty($national_id);
        } else {
            return (!empty($company_name) && !empty($national_id) && !empty($reg_id) && !empty($econ_id));
        }
    }

    /**
     * Legal information fields in the user account
     * 
     * @return void
     * @author M-MALEKZADEH77
     * @since v1.0.2
     * @version v1.0
     */
    public function add_fields_to_edit_account_form()
    {
        if ($this->get_issue_mode() == 'disabled') return;
        $user_id = get_current_user_id();
        $invoice_type = get_user_meta($user_id, 'billing_invoice_type', true) ?: 'person';
    ?>
        <fieldset class="irantheme-myaccount-fieldset">
            <legend>اطلاعات حقوقی (فاکتور رسمی)</legend>
            <div class="irantheme-myaccount-input">
                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                    <label for="billing_invoice_type">نوع درخواست‌کننده فاکتور</label>
                    <select name="billing_invoice_type" id="billing_invoice_type" class="woocommerce-Input input-text">
                        <option value="person" <?php selected($invoice_type, 'person'); ?>>شخص حقیقی (فردی / اصناف)</option>
                        <option value="company" <?php selected($invoice_type, 'company'); ?>>شخص حقوقی (شرکت ثبت‌شده)</option>
                    </select>
                </p>
                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide legal-company-field" style="display: none;">
                    <label for="billing_legal_company_name">نام رسمی و ثبت‌شده شرکت</label>
                    <input type="text" class="woocommerce-Input input-text" name="billing_legal_company_name" id="billing_legal_company_name" value="<?php echo esc_attr(get_user_meta($user_id, 'billing_legal_company_name', true)); ?>" />
                </p>
                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                    <label id="billing_national_id_label" for="billing_national_id">کد ملی خریدار</label>
                    <input type="text" class="woocommerce-Input input-text" name="billing_national_id" id="billing_national_id" value="<?php echo esc_attr(get_user_meta($user_id, 'billing_national_id', true)); ?>" />
                </p>
                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide legal-company-field" style="display: none;">
                    <label for="billing_registration_id">شماره ثبت شرکت</label>
                    <input type="text" class="woocommerce-Input input-text" name="billing_registration_id" id="billing_registration_id" value="<?php echo esc_attr(get_user_meta($user_id, 'billing_registration_id', true)); ?>" />
                </p>
                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide legal-company-field" style="display: none;">
                    <label for="billing_economic_id">کد اقتصادی</label>
                    <input type="text" class="woocommerce-Input input-text" name="billing_economic_id" id="billing_economic_id" value="<?php echo esc_attr(get_user_meta($user_id, 'billing_economic_id', true)); ?>" />
                </p>
            </div>
            <script type="text/javascript">
                document.addEventListener('DOMContentLoaded', function() {
                    var switcher = document.getElementById('billing_invoice_type');

                    function toggleInvoiceFields() {
                        var type = switcher.value;
                        var companyFields = document.querySelectorAll('.legal-company-field');
                        var nationalLabel = document.getElementById('billing_national_id_label');
                        if (type === 'company') {
                            companyFields.forEach(function(f) {
                                f.style.display = 'block';
                            });
                            if (nationalLabel) nationalLabel.textContent = 'شناسه ملی شرکت';
                        } else {
                            companyFields.forEach(function(f) {
                                f.style.display = 'none';
                            });
                            if (nationalLabel) nationalLabel.textContent = 'کد ملی خریدار';
                        }
                    }
                    if (switcher) {
                        switcher.addEventListener('change', toggleInvoiceFields);
                        toggleInvoiceFields();
                    }
                });
            </script>
        </fieldset>
    <?php
    }

    /**
     * Save user legal information (remove extra fields if type is changed to real)    
     * 
     * @param int $user_id User ID
     * @return void
     * @author M-MALEKZADEH77
     * @since v1.0.2
     * @version v1.0
     */
    public function save_edit_account_form_fields($user_id)
    {
        if ($this->get_issue_mode() == 'disabled') return;
        $fields = [
            'billing_invoice_type',
            'billing_legal_company_name',
            'billing_national_id',
            'billing_registration_id',
            'billing_economic_id'
        ];

        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                update_user_meta($user_id, $field, sanitize_text_field($_POST[$field]));
            }
        }

        $invoice_type = sanitize_text_field($_POST['billing_invoice_type'] ?? 'person');
        if ($invoice_type === 'person') {
            delete_user_meta($user_id, 'billing_legal_company_name');
            delete_user_meta($user_id, 'billing_registration_id');
            delete_user_meta($user_id, 'billing_economic_id');
        }
    }

    /**
     * Copies legal information from the current user to the order at checkout.
     * Only non‑empty values (not '' or null) are copied; zero ('0') is valid.
     *
     * @param WC_Order $order The order object being created.
     * @param array    $data  Checkout data (unused, required by hook compatibility).
     * @return void
     * @author M-MALEKZADEH77
     * @since v1.0.2
     * @version v1.0
     */
    public function copy_legal_meta_from_user_to_order($order, $data)
    {
        if ($this->get_issue_mode() == 'disabled') return;
        $user_id = get_current_user_id();
        if (!$user_id) return;

        $fields = [
            'billing_invoice_type',
            'billing_legal_company_name',
            'billing_national_id',
            'billing_registration_id',
            'billing_economic_id'
        ];

        foreach ($fields as $field) {
            $value = get_user_meta($user_id, $field, true);
            // The value zero ('0') is valid, just reject absolute blanks
            if ($value !== '' && $value !== null) {
                $order->update_meta_data($field, sanitize_text_field($value));
            }
        }
    }

    /**
     * Hides specific order meta keys from the Custom Fields metabox.
     *
     * @param bool   $protected Whether the meta key is protected.
     * @param string $meta_key  The meta key name.
     * @param string $meta_type The type of meta (e.g., 'post', 'user', 'comment').
     * @return bool
     */
    public function hide_invoice_meta_from_custom_fields($protected, $meta_key, $meta_type)
    {
        $hidden_metas = [
            'billing_invoice_type',
            'billing_legal_company_name',
            'billing_national_id',
            'billing_registration_id',
            'billing_economic_id',
            '_irantheme_wc_official_invoice_number',
            '_official_invoice_requested',
            '_official_invoice_approved',
            '_official_invoice_requested_date',
            '_is_preview_invoice',
        ];

        if ($meta_type === 'post' && in_array($meta_key, $hidden_metas, true)) {
            return true;
        }

        return $protected;
    }

    /**
     * Synchronizes user legal information with the order by copying specific meta fields.
     * Updates the order meta data and saves the order immediately.
     *
     * @param int       $user_id The ID of the user whose legal meta data will be copied.
     * @param WC_Order  $order   The order object to update with the user's legal information.
     * @return void
     * @author M-MALEKZADEH77
     * @since v1.0.2
     * @version v1.0
     */
    private function sync_legal_meta_from_user_to_order($user_id, $order)
    {
        $fields = [
            'billing_invoice_type',
            'billing_legal_company_name',
            'billing_national_id',
            'billing_registration_id',
            'billing_economic_id'
        ];
        foreach ($fields as $field) {
            $value = get_user_meta($user_id, $field, true);
            if ($value !== '' && $value !== null) {
                $order->update_meta_data($field, sanitize_text_field($value));
            }
        }
        // Save immediately for use in eligibility
        $order->save();
    }

    /**
     * Retrieves the invoice issuance mode setting from the theme options.
     *
     * @return string The selected issue mode ('manual' or other values as configured, defaults to 'manual').
     * @author M-MALEKZADEH77
     * @since v1.0.2
     * @version v1.0
     */
    private function get_issue_mode()
    {
        return get_option('irantheme_wc_invoice_official_issue_mode_select', 'manual');
    }

    /**
     * Processes the official invoice request initiated by a customer from their account page.
     * Validates nonce, order ownership, and current invoice status. Synchronizes the user's
     * legal information with the order, then handles the request based on the plugin's
     * issue mode setting (`full_auto`, `semi_auto`, `manual`, or `disabled`). Generates
     * the invoice, updates order meta, and redirects back with appropriate success or
     * error notices.
     *
     * @return void
     * @author M-MALEKZADEH77
     * @since v1.0.2
     * @version v1.0
     */
    public function handle_invoice_request()
    {
        if (empty($_GET['it_request_official_invoice']) || !is_user_logged_in()) {
            return;
        }
        if (!wp_verify_nonce($_GET['_wpnonce'] ?? '', 'it_request_official_invoice')) {
            return;
        }

        $order_id = absint($_GET['it_request_official_invoice']);
        $order = wc_get_order($order_id);
        if (!$order || $order->get_customer_id() != get_current_user_id()) {
            return;
        }
        if ($order->get_meta('_official_invoice_approved') === 'approved') {
            wc_add_notice('فاکتور رسمی این سفارش قبلاً صادر شده است.', 'error');
            wp_safe_redirect(wc_get_account_endpoint_url('view-order') . '/' . $order_id);
            exit;
        }

        $mode = $this->get_issue_mode();
        $user_id = get_current_user_id();

        if ($mode === 'disabled') {
            wc_add_notice('امکان ثبت درخواست فاکتور رسمی وجود ندارد.', 'error');
            wp_safe_redirect(wc_get_account_endpoint_url('orders'));
            exit;
        }

        // Always pass user information to the order (updated)
        $this->sync_legal_meta_from_user_to_order($user_id, $order);
        $profile_complete = $this->is_invoice_profile_complete($user_id);

        // full_auto mode
        if ($mode === 'full_auto') {
            if (!$profile_complete) {
                wc_add_notice('برای دریافت فاکتور رسمی، ابتدا اطلاعات حقوقی خود را تکمیل کنید.', 'error');
                wp_safe_redirect(wc_get_account_endpoint_url('view-order') . '/' . $order_id);
                exit;
            }
            $invoice_number = Irantheme_Woocommerce_Invoice_Core::generate_official_invoice_number_safely($order_id);
            if ($invoice_number !== false) {
                $order->update_meta_data('_official_invoice_requested', 'yes');
                $order->update_meta_data('_official_invoice_approved', 'approved');
                $order->update_meta_data('_official_invoice_requested_date', current_time('timestamp'));
                $order->save();
                delete_transient('it_invoice_admin_counts');
                wc_add_notice('فاکتور رسمی با موفقیت صادر شد.', 'success');
            } else {
                wc_add_notice('خطا در صدور فاکتور رسمی. لطفاً با پشتیبانی تماس بگیرید.', 'error');
            }
            wp_safe_redirect(wc_get_account_endpoint_url('view-order') . '/' . $order_id);
            exit;
        }

        // manual and semi_auto mode
        if (!$profile_complete) {
            wc_add_notice('جهت ثبت درخواست، ابتدا اطلاعات حقوقی خود را در حساب کاربری تکمیل کنید.', 'error');
            if (isset($_GET['return_to_view_order']) && $_GET['return_to_view_order'] === 'yes') {
                wp_safe_redirect(wc_get_account_endpoint_url('view-order') . '/' . $order_id);
            } else {
                wp_safe_redirect(wc_get_account_endpoint_url('orders'));
            }
            exit;
        }

        $order->update_meta_data('_official_invoice_requested', 'yes');

        if ($mode === 'semi_auto') {
            $target_statuses = ['processing', 'completed'];
            $is_in_target = in_array($order->get_status(), $target_statuses);
            $eligible = Irantheme_Woocommerce_Invoice_Core::is_order_eligible_for_official_invoice($order);
            if ($eligible && $is_in_target) {
                $invoice_number = Irantheme_Woocommerce_Invoice_Core::generate_official_invoice_number_safely($order_id);
                if ($invoice_number !== false) {
                    $order->update_meta_data('_official_invoice_approved', 'approved');
                } else {
                    $order->update_meta_data('_official_invoice_approved', 'approved');
                    $order->update_meta_data('_is_preview_invoice', 'yes');
                }
            } else {
                $order->update_meta_data('_official_invoice_approved', 'pending');
            }
        } else {
            // manual
            $order->update_meta_data('_official_invoice_approved', 'pending');
        }

        $order->update_meta_data('_official_invoice_requested_date', current_time('timestamp'));
        $order->save();
        delete_transient('it_invoice_admin_counts');

        wc_add_notice('درخواست فاکتور رسمی با موفقیت ثبت شد.', 'success');

        if (isset($_GET['return_to_view_order']) && $_GET['return_to_view_order'] === 'yes') {
            wp_safe_redirect(wc_get_account_endpoint_url('view-order') . '/' . $order_id);
        } elseif (isset($_GET['return_to_thankyou']) && $_GET['return_to_thankyou'] === 'yes') {
            $thankyou_url = wc_get_checkout_url() . 'order-received/' . $order_id . '/';
            $order_key = $order->get_order_key();
            if ($order_key) {
                $thankyou_url = add_query_arg('key', $order_key, $thankyou_url);
            }
            wp_safe_redirect($thankyou_url);
        } else {
            wp_safe_redirect(wc_get_account_endpoint_url('orders'));
        }
        exit;
    }

    /**
     * Renders the invoice information box on the order details page in the user account area.
     * Displays a link to the regular invoice. If the official invoice feature is not disabled,
     * shows appropriate buttons or status messages for requesting/viewing the official invoice
     * based on the current issue mode (`manual`, `semi_auto`, `full_auto`), the user's profile
     * completion status, the order status, and any existing request/approval meta data.
     *
     * @param int $order_id The ID of the order being viewed.
     * @return void
     * @author M-MALEKZADEH77
     * @since v1.0.2
     * @version v1.0
     */
    public function render_order_details_invoice_box($order_id)
    {
        $order = wc_get_order($order_id);
        if (!$order || !$order->has_status(['pending', 'processing', 'on-hold', 'completed'])) {
            return;
        }

        // Official invoice issue :'disabled', 'manual', 'semi_auto', 'full_auto'
        $mode = $this->get_issue_mode();
        // Customer invoice issue :'on', 'off'
        $customer_inv_visibility = get_option('irantheme_wc_invoice_customer_account_btn_visibility_checkbox', 'on');

        if ($mode == 'disabled' && $customer_inv_visibility == 'off') {
            return;
        }

        $requested = $order->get_meta('_official_invoice_requested');
        $status    = $order->get_meta('_official_invoice_approved');
        $profile_complete = $this->is_invoice_profile_complete(get_current_user_id());
        $target_statuses = ['processing', 'completed'];
        $is_in_target = in_array($order->get_status(), $target_statuses);

    ?>
        <div class="irantheme-myaccount-view-order-print-invoice">
            <div class="irantheme-myaccount-view-order-print-invoice-wrap">
                <h3 class="irantheme-myaccount-view-order-print-invoice-title">فاکتورها و مدارک سفارش</h3>
                <?php if ($customer_inv_visibility == 'on'): ?>
                    <div class="irantheme-myaccount-view-order-print-invoice-row invoice-customer">
                        <div class="irantheme-myaccount-view-order-print-invoice-row-label">
                            <i class="fa-light fa-file-invoice"></i> <span>فاکتور عادی سفارش</span>
                        </div>
                        <div class="irantheme-myaccount-view-order-print-invoice-row-value">
                            <a href="<?php echo esc_url(\Irantheme_Dev\Irantheme_Woocommerce_Invoice_Core::get_print_url('customer', $order_id)); ?>"
                                target="_blank" class="button irantheme-btn irantheme-btn-medium irantheme-btn-medium-radius irantheme-btn-primary invoice-btn invoice-customer-btn">
                                <i class="fa-light fa-print"></i> مشاهده فاکتور
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if ($mode !== 'disabled'): ?>
                    <div class="irantheme-myaccount-view-order-print-invoice-row invoice-official">
                        <div class="irantheme-myaccount-view-order-print-invoice-row-label">
                            <i class="fa-light fa-building-columns"></i> <span>فاکتور رسمی سفارش</span>
                        </div>
                        <div class="irantheme-myaccount-view-order-print-invoice-row-value">

                            <?php if ($mode === 'manual') : ?>
                                <?php if ($requested === 'yes' && $status === 'approved') : ?>
                                    <a href="<?php echo esc_url(\Irantheme_Dev\Irantheme_Woocommerce_Invoice_Core::get_print_url('official', $order_id)); ?>"
                                        target="_blank" class="button irantheme-btn irantheme-btn-medium irantheme-btn-medium-radius irantheme-btn-primary invoice-btn invoice-official-btn">
                                        <i class="fa-light fa-print"></i> مشاهده فاکتور رسمی
                                    </a>
                                <?php elseif ($requested === 'yes' && $status === 'pending') : ?>
                                    <div class="invoice-status-wrapper">
                                        <span class="invoice-status invoice-status-pending"><strong>وضعیت درخواست:</strong> در حال بررسی</span>
                                    </div>
                                <?php elseif ($requested === 'yes' && $status === 'rejected') : ?>
                                    <div class="invoice-status-wrapper">
                                        <span class="invoice-status invoice-status-rejected"><strong>وضعیت درخواست:</strong> رد شده</span>
                                    </div>
                                <?php else : // Request not registered
                                ?>
                                    <?php if ($profile_complete) : ?>
                                        <a href="<?php echo esc_url(wp_nonce_url(add_query_arg(['it_request_official_invoice' => $order_id, 'return_to_view_order' => 'yes']), 'it_request_official_invoice')); ?>"
                                            class="button irantheme-btn irantheme-btn-medium irantheme-btn-medium-radius irantheme-btn-dark invoice-btn invoice-official-submit-request-btn">
                                            ثبت درخواست
                                        </a>
                                    <?php else : ?>
                                        <a href="#" class="button irantheme-btn irantheme-btn-medium irantheme-btn-medium-radius irantheme-btn-dark invoice-btn invoice-official-submit-request-btn"
                                            style="opacity:0.4; pointer-events:none; cursor:not-allowed;">ثبت درخواست (غیرفعال)</a>
                                    <?php endif; ?>
                                <?php endif; ?>

                            <?php elseif ($mode === 'semi_auto') : ?>
                                <?php if ($requested === 'yes' && $status === 'approved') : ?>
                                    <a href="<?php echo esc_url(\Irantheme_Dev\Irantheme_Woocommerce_Invoice_Core::get_print_url('official', $order_id)); ?>"
                                        target="_blank" class="button irantheme-btn irantheme-btn-medium irantheme-btn-medium-radius irantheme-btn-primary invoice-btn invoice-official-btn">
                                        <i class="fa-light fa-print"></i> مشاهده فاکتور رسمی
                                    </a>
                                <?php elseif ($requested === 'yes' && $status === 'pending') : ?>
                                    <div class="invoice-status-wrapper">
                                        <span class="invoice-status invoice-status-pending"><strong>وضعیت درخواست:</strong> در حال بررسی</span>
                                    </div>
                                <?php elseif ($requested === 'yes' && $status === 'rejected') : ?>
                                    <div class="invoice-status-wrapper">
                                        <span class="invoice-status invoice-status-rejected"><strong>وضعیت درخواست:</strong> رد شده</span>
                                    </div>
                                <?php else : // Request not registered
                                ?>
                                    <?php if ($profile_complete) : ?>
                                        <a href="<?php echo esc_url(wp_nonce_url(add_query_arg(['it_request_official_invoice' => $order_id, 'return_to_view_order' => 'yes']), 'it_request_official_invoice')); ?>"
                                            class="button irantheme-btn irantheme-btn-medium irantheme-btn-medium-radius irantheme-btn-dark invoice-btn invoice-official-submit-request-btn">
                                            ثبت درخواست
                                        </a>
                                    <?php else : ?>
                                        <a href="#" class="button irantheme-btn irantheme-btn-medium irantheme-btn-medium-radius irantheme-btn-dark invoice-btn invoice-official-submit-request-btn"
                                            style="opacity:0.4; pointer-events:none; cursor:not-allowed;">ثبت درخواست (غیرفعال)</a>
                                    <?php endif; ?>
                                <?php endif; ?>

                            <?php elseif ($mode === 'full_auto') : ?>
                                <?php if ($requested === 'yes' && $status === 'approved') : ?>
                                    <a href="<?php echo esc_url(\Irantheme_Dev\Irantheme_Woocommerce_Invoice_Core::get_print_url('official', $order_id)); ?>"
                                        target="_blank" class="button irantheme-btn irantheme-btn-medium irantheme-btn-medium-radius irantheme-btn-primary invoice-btn invoice-official-btn">
                                        <i class="fa-light fa-print"></i> مشاهده فاکتور رسمی
                                    </a>
                                <?php elseif ($requested === 'yes' && $status === 'pending') : ?>
                                    <div class="invoice-status-wrapper">
                                        <span class="invoice-status invoice-status-pending"><strong>وضعیت درخواست:</strong> در حال بررسی</span>
                                    </div>
                                <?php elseif ($requested === 'yes' && $status === 'rejected') : ?>
                                    <div class="invoice-status-wrapper">
                                        <span class="invoice-status invoice-status-rejected"><strong>وضعیت درخواست:</strong> رد شده</span>
                                    </div>
                                <?php else : // Request not registered
                                ?>
                                    <?php if ($profile_complete && $is_in_target) : ?>
                                        <a href="<?php echo esc_url(wp_nonce_url(add_query_arg(['it_request_official_invoice' => $order_id, 'return_to_view_order' => 'yes']), 'it_request_official_invoice')); ?>"
                                            class="button irantheme-btn irantheme-btn-medium irantheme-btn-medium-radius irantheme-btn-primary invoice-btn invoice-official-submit-request-btn">
                                            صدور فاکتور رسمی
                                        </a>
                                    <?php elseif (!$profile_complete) : ?>
                                        <a href="#" class="button irantheme-btn irantheme-btn-medium irantheme-btn-medium-radius irantheme-btn-dark invoice-btn invoice-official-submit-request-btn"
                                            style="opacity:0.4; pointer-events:none; cursor:not-allowed;"><i class="fa-light fa-print"> </i>مشاهده فاکتور رسمی (غیرفعال)</a>
                                    <?php else : ?>
                                        <a href="#" class="button irantheme-btn irantheme-btn-medium irantheme-btn-medium-radius irantheme-btn-dark invoice-btn invoice-official-submit-request-btn"
                                            style="opacity:0.4; pointer-events:none; cursor:not-allowed;"><i class="fa-light fa-print"> </i>مشاهده فاکتور رسمی (غیرفعال)</a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if (!($requested === 'yes' && $status === 'approved')): ?>
                        <div class="irantheme-myaccount-view-order-print-invoice-row invoice-official-notice">
                            <?php if ($mode === 'manual') : ?>
                                <?php if ($requested === 'yes' && $status === 'pending') : ?>
                                    <p class="invoice-tip pending"><i class="fa-light fa-circle-info"></i> درخواست شما ثبت شده و در حال بررسی است. پس از تایید، دکمه دریافت فعال خواهد شد.</p>
                                <?php elseif ($requested === 'yes' && $status === 'rejected') : ?>
                                    <p class="invoice-tip rejected"><i class="fa-light fa-circle-xmark"></i> درخواست فاکتور رسمی رد شده است. با پشتیبانی تماس بگیرید.</p>
                                <?php else : ?>
                                    <?php if (!$profile_complete) : ?>
                                        <p class="invoice-tip warning"><i class="fa-light fa-triangle-exclamation"></i> برای ثبت درخواست، ابتدا اطلاعات حقوقی خود را در <a href="<?php echo esc_url(wc_get_account_endpoint_url('edit-account')); ?>">حساب کاربری</a> تکمیل کنید.</p>
                                    <?php else : ?>
                                        <p class="invoice-tip info"><i class="fa-light fa-circle-info"></i> در صورت نیاز، درخواست فاکتور رسمی ثبت کنید. پس از تایید واحد مالی، فاکتور قابل دریافت خواهد بود.</p>
                                    <?php endif; ?>
                                <?php endif; ?>

                            <?php elseif ($mode === 'semi_auto') : ?>
                                <?php if ($requested === 'yes' && $status === 'pending') : ?>
                                    <p class="invoice-tip pending"><i class="fa-light fa-circle-info"></i> درخواست شما ثبت شده است. پس از تکمیل سفارش، فاکتور به طور خودکار صادر خواهد شد.</p>
                                <?php elseif ($requested === 'yes' && $status === 'rejected') : ?>
                                    <p class="invoice-tip rejected"><i class="fa-light fa-circle-xmark"></i> درخواست فاکتور رسمی رد شده است. با پشتیبانی تماس بگیرید.</p>
                                <?php else : ?>
                                    <?php if (!$profile_complete) : ?>
                                        <p class="invoice-tip warning"><i class="fa-light fa-triangle-exclamation"></i> برای ثبت درخواست، ابتدا اطلاعات حقوقی خود را در <a href="<?php echo esc_url(wc_get_account_endpoint_url('edit-account')); ?>">حساب کاربری</a> تکمیل کنید.</p>
                                    <?php else : ?>
                                        <p class="invoice-tip info"><i class="fa-light fa-circle-info"></i> پس از ثبت درخواست و تکمیل سفارش، فاکتور به طور خودکار صادر می‌شود.</p>
                                    <?php endif; ?>
                                <?php endif; ?>

                            <?php elseif ($mode === 'full_auto') : ?>
                                <?php if ($requested === 'yes' && $status === 'pending') : ?>
                                    <p class="invoice-tip pending"><i class="fa-light fa-circle-info"></i> درخواست در حال بررسی است. در صورت نیاز، با پشتیبانی تماس بگیرید.</p>
                                <?php elseif ($requested === 'yes' && $status === 'rejected') : ?>
                                    <p class="invoice-tip rejected"><i class="fa-light fa-circle-xmark"></i> درخواست فاکتور رسمی رد شده است.</p>
                                <?php else : ?>
                                    <?php if (!$profile_complete) : ?>
                                        <p class="invoice-tip warning"><i class="fa-light fa-triangle-exclamation"></i> برای ثبت درخواست، ابتدا اطلاعات حقوقی خود را در <a href="<?php echo esc_url(wc_get_account_endpoint_url('edit-account')); ?>">حساب کاربری</a> تکمیل کنید.</p>
                                    <?php elseif (!$is_in_target) : ?>
                                        <p class="invoice-tip info"><i class="fa-light fa-circle-info"></i> پس از تایید سفارش توسط مدیر، فاکتور رسمی به طور خودکار صادر خواهد شد.</p>
                                    <?php else : ?>
                                        <p class="invoice-tip info"><i class="fa-light fa-circle-info"></i> با کلیک روی دکمه «صدور فاکتور رسمی» در بالا، فاکتور شما صادر خواهد شد.</p>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; // end if not approved 
                    ?>
                <?php endif; // end if mode !== disabled 
                ?>
            </div>
        </div>
    <?php
    }

    /**
     * Renders the official invoice management panel on the admin order edit page.
     * Displays the order's legal information fields (editable per order) and the
     * invoice request status. Provides controls to change the approval status
     * (pending/approved/rejected) and a button to manually generate the official
     * invoice if eligible.
     *
     * @param WC_Order $order The order object.
     * @return void
     * @author M-MALEKZADEH77
     * @since v1.0.2
     * @version v1.0
     */
    public function render_admin_invoice_status($order)
    {
        $order_id = $order->get_id();
        $requested = $order->get_meta('_official_invoice_requested');
        $status = $order->get_meta('_official_invoice_approved');
        if ($requested === 'yes' && empty($status)) {
            $status = 'pending';
        }
        $invoice_number = $order->get_meta('_irantheme_wc_official_invoice_number');

        // Legal information from order meta
        $invoice_type = $order->get_meta('billing_invoice_type');
        $company_name = $order->get_meta('billing_legal_company_name');
        $national_id  = $order->get_meta('billing_national_id');
        $reg_id       = $order->get_meta('billing_registration_id');
        $econ_id      = $order->get_meta('billing_economic_id');

        $user_id = $order->get_customer_id();
        if (empty($invoice_type) && $user_id) {
            $invoice_type = get_user_meta($user_id, 'billing_invoice_type', true) ?: 'person';
        }
        if (empty($company_name) && $user_id) {
            $company_name = get_user_meta($user_id, 'billing_legal_company_name', true);
        }
        if (empty($national_id) && $user_id) {
            $national_id = get_user_meta($user_id, 'billing_national_id', true);
        }
        if (empty($reg_id) && $user_id) {
            $reg_id = get_user_meta($user_id, 'billing_registration_id', true);
        }
        if (empty($econ_id) && $user_id) {
            $econ_id = get_user_meta($user_id, 'billing_economic_id', true);
        }
    ?>
        <div class="irantheme-admin-official-invoice" style="background:#f8f9fa; border:1px solid #dee2e6; padding:15px; border-radius:10px; margin-top:15px;">
            <h4 style="margin:0 0 10px 0;">فاکتور رسمی (مدیریت و اطلاعات حقوقی سفارش)</h4>
            <div style="margin-bottom:20px; padding:10px; background:#fff; border:1px solid #e2e4e7;border-radius:5px;">
                <p style="margin-top:0;"><strong>اطلاعات حقوقی سفارش (قابل ویرایش برای این سفارش)</strong></p>
                <div style="display:flex; flex-wrap:wrap; gap:10px;">
                    <div style="flex:1; min-width:200px;">
                        <label for="billing_invoice_type">نوع خریدار:</label>
                        <select name="billing_invoice_type" id="billing_invoice_type" style="width:100%;">
                            <option value="person" <?php selected($invoice_type, 'person'); ?>>شخص حقیقی</option>
                            <option value="company" <?php selected($invoice_type, 'company'); ?>>شخص حقوقی</option>
                        </select>
                    </div>
                    <div class="legal-company-field" style="flex:2; display:<?php echo ($invoice_type === 'company') ? 'block' : 'none'; ?>;">
                        <label for="billing_legal_company_name">نام شرکت:</label>
                        <input type="text" name="billing_legal_company_name" id="billing_legal_company_name" value="<?php echo esc_attr($company_name); ?>" style="width:100%;" />
                    </div>
                </div>
                <div style="display:flex; flex-wrap:wrap; gap:10px; margin-top:10px;">
                    <div style="flex:1;">
                        <label id="national_label" for="billing_national_id"><?php echo ($invoice_type === 'company') ? 'شناسه ملی' : 'کد ملی'; ?>:</label>
                        <input type="text" name="billing_national_id" id="billing_national_id" value="<?php echo esc_attr($national_id); ?>" style="width:100%;" />
                    </div>
                    <div class="legal-company-field" style="flex:1; display:<?php echo ($invoice_type === 'company') ? 'block' : 'none'; ?>;">
                        <label for="billing_registration_id">شماره ثبت:</label>
                        <input type="text" name="billing_registration_id" id="billing_registration_id" value="<?php echo esc_attr($reg_id); ?>" style="width:100%;" />
                    </div>
                    <div class="legal-company-field" style="flex:1; display:<?php echo ($invoice_type === 'company') ? 'block' : 'none'; ?>;">
                        <label for="billing_economic_id">کد اقتصادی:</label>
                        <input type="text" name="billing_economic_id" id="billing_economic_id" value="<?php echo esc_attr($econ_id); ?>" style="width:100%;" />
                    </div>
                </div>
                <p style="font-size:12px; color:#666; margin:5px 0 0;"><i class="fa-light fa-warning"></i> این اطلاعات فقط برای این سفارش ذخیره می‌شوند و روی پروفایل کاربر تأثیری ندارند.</p>
            </div>

            <div style="border-top:1px solid #ddd; padding-top:10px;">
                <?php if ($requested === 'yes') : ?>
                    <p><strong>وضعیت درخواست صدور فاکتور رسمی:</strong>
                        <?php if ($status === 'approved') : ?>
                            <span style="color:green;"><i class="fa-regular fa-circle-check"></i> تایید شده</span>
                            <?php if ($invoice_number) : ?>
                                <br><strong>شماره فاکتور رسمی:</strong> <code><?php echo esc_html($invoice_number); ?></code>
                            <?php endif; ?>
                            <br><a href="<?php echo esc_url(\Irantheme_Dev\Irantheme_Woocommerce_Invoice_Core::get_print_url('official', $order_id)); ?>" target="_blank" class="button button-primary" style="margin-top:5px;"><i class="fa-light fa-print"></i> چاپ فاکتور رسمی</a>
                        <?php elseif ($status === 'rejected') : ?>
                            <span style="color:red;"><i class="fa-regular fa-circle-x"></i> رد شده</span>
                        <?php else : ?>
                            <span style="color:orange;"><i class="fa-regular fa-hourglass-clock"></i> در انتظار بررسی</span>
                        <?php endif; ?>
                    </p>
                    <p><label for="official_invoice_status">تغییر وضعیت:</label></p>
                    <select id="official_invoice_status" name="official_invoice_status" style="width:100%;">
                        <option value="pending" <?php selected($status, 'pending'); ?>>در انتظار بررسی</option>
                        <option value="approved" <?php selected($status, 'approved'); ?>>تایید شده (صدور فاکتور)</option>
                        <option value="rejected" <?php selected($status, 'rejected'); ?>>رد شده</option>
                    </select>
                <?php else : ?>
                    <p>هنوز درخواست فاکتور رسمی ثبت نشده است.</p>
                    <?php
                    // Check if legal info is complete and invoice not yet generated
                    $eligible_now = Irantheme_Woocommerce_Invoice_Core::is_order_eligible_for_official_invoice($order);
                    if ($eligible_now && empty($invoice_number)) :
                    ?>
                        <br><button type="submit" name="it_generate_invoice_now" value="1" class="button button-secondary" style="margin-top:5px;">صدور فاکتور رسمی (همین حالا)</button>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
        <script type="text/javascript">
            document.addEventListener('DOMContentLoaded', function() {
                var switcher = document.getElementById('billing_invoice_type');
                if (!switcher) return;

                function toggleFields() {
                    var type = switcher.value;
                    var companyFields = document.querySelectorAll('.irantheme-admin-official-invoice .legal-company-field');
                    var nationalLabel = document.getElementById('national_label');
                    if (type === 'company') {
                        companyFields.forEach(function(f) {
                            f.style.display = 'block';
                        });
                        if (nationalLabel) nationalLabel.textContent = 'شناسه ملی شرکت';
                    } else {
                        companyFields.forEach(function(f) {
                            f.style.display = 'none';
                        });
                        if (nationalLabel) nationalLabel.textContent = 'کد ملی خریدار';
                    }
                }
                switcher.addEventListener('change', toggleFields);
                toggleFields();
            });
        </script>
<?php
    }

    /**
     * Saves the official invoice request status and order-specific legal information
     * submitted from the admin order edit panel. Updates the order meta with the
     * provided legal fields, handles manual invoice generation via the
     * `it_generate_invoice_now` button, and updates the approval status
     * (pending/approved/rejected). When approving an invoice, attempts to generate
     * the official invoice number if not already present.
     *
     * @param int $order_id The ID of the order being saved.
     * @return void
     * @author M-MALEKZADEH77
     * @since v1.0.2
     * @version v1.0
     */
    public function save_admin_invoice_status($order_id)
    {
        $order = wc_get_order($order_id);
        if (!$order) return;

        // Save edited legal information in metabox
        $legal_fields = ['billing_invoice_type', 'billing_legal_company_name', 'billing_national_id', 'billing_registration_id', 'billing_economic_id'];
        foreach ($legal_fields as $field) {
            if (isset($_POST[$field])) {
                $order->update_meta_data($field, sanitize_text_field($_POST[$field]));
            }
        }

        // If the buyer type is changed to natural person, clear the legal entity meta
        $invoice_type = sanitize_text_field($_POST['billing_invoice_type'] ?? '');
        if ($invoice_type === 'person') {
            $order->delete_meta_data('billing_legal_company_name');
            $order->delete_meta_data('billing_registration_id');
            $order->delete_meta_data('billing_economic_id');
        }

        // Handle manual generation button (it_generate_invoice_now)
        if (isset($_POST['it_generate_invoice_now']) && $_POST['it_generate_invoice_now'] == '1') {
            $eligible = Irantheme_Woocommerce_Invoice_Core::is_order_eligible_for_official_invoice($order);
            if ($eligible) {
                $invoice_number = Irantheme_Woocommerce_Invoice_Core::generate_official_invoice_number_safely($order_id);
                if ($invoice_number !== false) {
                    $order->update_meta_data('_official_invoice_requested', 'yes');
                    $order->update_meta_data('_official_invoice_approved', 'approved');
                    $order->update_meta_data('_official_invoice_requested_date', current_time('timestamp'));
                    $order->save();
                    delete_transient('it_invoice_admin_counts');
                }
            }
        }

        $allowed_statuses = ['pending', 'approved', 'rejected'];
        $status = sanitize_text_field($_POST['official_invoice_status'] ?? '');
        if (in_array($status, $allowed_statuses, true)) {
            if ($status === 'approved') {
                $existing = $order->get_meta('_irantheme_wc_official_invoice_number');
                if (empty($existing)) {
                    $eligible = Irantheme_Woocommerce_Invoice_Core::is_order_eligible_for_official_invoice($order);
                    if ($eligible) {
                        $invoice_number = Irantheme_Woocommerce_Invoice_Core::generate_official_invoice_number_safely($order_id);
                        if ($invoice_number === false) {
                            $order->update_meta_data('_is_preview_invoice', 'yes');
                        }
                    } else {
                        $order->update_meta_data('_is_preview_invoice', 'yes');
                    }
                }
            }
            $order->update_meta_data('_official_invoice_approved', $status);
        }
        $order->save();
        delete_transient('it_invoice_admin_counts');
    }

    /**
     * Automatically generates the official invoice in `full_auto` mode when the order status changes.
     * Syncs the user's legal information to the order, checks eligibility, and if eligible,
     * generates the official invoice number and marks the request as approved.
     *
     * @param int       $order_id   The ID of the order.
     * @param string    $old_status The previous order status.
     * @param string    $new_status The new order status.
     * @param WC_Order  $order      The order object.
     * @return void
     * @author M-MALEKZADEH77
     * @since v1.0.2
     * @version v1.0
     */
    public function auto_generate_on_order_status_change($order_id, $old_status, $new_status, $order)
    {
        $mode = $this->get_issue_mode();
        if ($mode !== 'full_auto') return;

        $target_statuses = ['processing', 'completed'];
        if (!in_array($new_status, $target_statuses)) return;

        $requested = $order->get_meta('_official_invoice_requested');
        if ($requested === 'yes') return;

        $user_id = $order->get_customer_id();
        if ($user_id) {
            $this->sync_legal_meta_from_user_to_order($user_id, $order);
        }

        $eligible = Irantheme_Woocommerce_Invoice_Core::is_order_eligible_for_official_invoice($order);
        if (!$eligible) return;

        $invoice_number = Irantheme_Woocommerce_Invoice_Core::generate_official_invoice_number_safely($order_id);
        if ($invoice_number !== false) {
            $order->update_meta_data('_official_invoice_requested', 'yes');
            $order->update_meta_data('_official_invoice_approved', 'approved');
            $order->update_meta_data('_official_invoice_requested_date', current_time('timestamp'));
            $order->save();
        }
    }

    /**
     * Automatically approves pending official invoice requests in `semi_auto` mode when the order status changes.
     * When the order status transitions to `processing` or `completed` and a pending request exists,
     * syncs the user's legal information to the order, checks eligibility, and generates the official
     * invoice number if eligible, then marks the request as approved.
     *
     * @param int       $order_id   The ID of the order.
     * @param string    $old_status The previous order status.
     * @param string    $new_status The new order status.
     * @param WC_Order  $order      The order object.
     * @return void
     * @author M-MALEKZADEH77
     * @since v1.0.2
     * @version v1.0
     */
    public function auto_approve_pending_on_status_change($order_id, $old_status, $new_status, $order)
    {
        $mode = $this->get_issue_mode();
        if ($mode !== 'semi_auto') return;

        $target_statuses = ['processing', 'completed'];
        if (!in_array($new_status, $target_statuses)) return;

        $requested = $order->get_meta('_official_invoice_requested');
        $status = $order->get_meta('_official_invoice_approved');
        if ($requested !== 'yes' || $status !== 'pending') return;

        $user_id = $order->get_customer_id();
        if ($user_id) {
            $this->sync_legal_meta_from_user_to_order($user_id, $order);
        }

        $eligible = Irantheme_Woocommerce_Invoice_Core::is_order_eligible_for_official_invoice($order);
        if (!$eligible) return;

        $invoice_number = Irantheme_Woocommerce_Invoice_Core::generate_official_invoice_number_safely($order_id);
        if ($invoice_number !== false) {
            $order->update_meta_data('_official_invoice_approved', 'approved');
            $order->update_meta_data('_official_invoice_requested_date', current_time('timestamp'));
            $order->save();
        }
    }
}

new Irantheme_Woocommerce_Invoice_Extension();
