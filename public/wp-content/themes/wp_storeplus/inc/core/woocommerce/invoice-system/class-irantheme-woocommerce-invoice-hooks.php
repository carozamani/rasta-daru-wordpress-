<?php

namespace Irantheme_Dev;

if (! defined('ABSPATH')) {
    exit;
}

/**
 * WooCommerce Invoice Hooks Class
 * * Adds custom invoice action column to admin orders list (HPOS compatible).
 * * Registers bulk print actions for invoices and shipping labels.
 * * Renders action buttons with state-based logic for official invoices.
 * * Provides admin styles, forced column visibility, and JavaScript confirmation for bulk actions.
 *
 * @package IranTheme_Dev
 * @author M-MALEKZADEH77
 * @since v1.0.2
 * @version v1.0.2
 */
class Irantheme_Woocommerce_Invoice_Hooks
{

    /**
     * Constructor. Hooks into WordPress and WooCommerce filters and actions to add
     * custom order list columns, bulk actions, admin styles, and HPOS compatibility.
     *
     * @return void
     * @author M-MALEKZADEH77
     * @since v1.0.2
     * @version v1.0
     */
    public function __construct()
    {
        // Add custom column for orders list (HPOS)
        add_filter('manage_woocommerce_page_wc-orders_columns', [$this, 'add_custom_invoice_column_hpos'], 20);
        add_action('manage_woocommerce_page_wc-orders_custom_column', [$this, 'render_custom_column_content_hpos'], 10, 2);

        // Add admin styles for the new column
        add_action('admin_head', [$this, 'add_admin_styles']);

        // Force enable the custom column visibility
        add_action('admin_head', [$this, 'force_enable_custom_column']);

        // Bulk actions for invoice printing
        add_filter('bulk_actions-woocommerce_page_wc-orders', [$this, 'register_bulk_invoice_action']);
        add_filter('handle_bulk_actions-woocommerce_page_wc-orders', [$this, 'handle_bulk_invoice_action'], 10, 3);

        // Bulk actions for shipping label printing
        add_filter('bulk_actions-woocommerce_page_wc-orders', [$this, 'register_bulk_shipping_label_action']);
        add_filter('handle_bulk_actions-woocommerce_page_wc-orders', [$this, 'handle_bulk_shipping_label_action'], 10, 3);

        // Classic mode support for older WooCommerce versions
        add_action('before_woocommerce_init', [$this, 'declare_hpos_compatibility']);

        // Add confirmation message for group operations
        add_action('admin_footer', [$this, 'add_bulk_action_confirm_js']);
    }

    /**
     * Declares HPOS (High-Performance Order Storage) compatibility for WooCommerce.
     * Ensures the plugin works with custom order tables in future WooCommerce versions.
     *
     * @return void
     * @author M-MALEKZADEH77
     * @since v1.0.2
     * @version v1.0
     */
    public function declare_hpos_compatibility()
    {
        if (class_exists('\Automattic\WooCommerce\Utilities\FeaturesUtil')) {
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
        }
    }

    /**
     * Adds a custom column 'irantheme_invoice_actions' to the WooCommerce orders list (HPOS).
     * Places the column after order status or order total, otherwise at the end.
     *
     * @param array $columns Existing columns.
     * @return array Modified columns with the new column added.
     * @author M-MALEKZADEH77
     * @since v1.0.2
     * @version v1.0
     */
    public function add_custom_invoice_column_hpos($columns)
    {
        $new_columns = [];

        foreach ($columns as $key => $column) {
            $new_columns[$key] = $column;

            // Add our column after order status or total
            if ($key === 'order_status' || $key === 'order_total') {
                $new_columns['irantheme_invoice_actions'] = 'فاکتور و برچسب';
            }
        }

        // If column not added yet, add it at the end
        if (!isset($new_columns['irantheme_invoice_actions'])) {
            $new_columns['irantheme_invoice_actions'] = 'فاکتور و برچسب';
        }

        return $new_columns;
    }

    /**
     * Renders the content for the custom invoice actions column in HPOS orders list.
     * Delegates button rendering to the render_buttons() method.
     *
     * @param string $column Current column name.
     * @param mixed  $order  Order object (WC_Order).
     * @return void
     * @author M-MALEKZADEH77
     * @since v1.0.2
     * @version v1.0
     */
    public function render_custom_column_content_hpos($column, $order)
    {
        // If the current column is not our column, do nothing (exit the function)
        if ($column !== 'irantheme_invoice_actions') {
            return;
        }

        $order_id = $order->get_id();
        $this->render_buttons($order_id);
    }

    /**
     * Renders invoice action buttons for a single order row in the admin orders list.
     * Buttons include regular invoice, official invoice (with state-based logic),
     * shipping receipt, thermal invoice, and thermal shipping label.
     *
     * @param int|string $order_id The order ID.
     * @return void
     * @author M-MALEKZADEH77
     * @since v1.0.2
     * @version v1.0
     */
    private function render_buttons($order_id)
    {
        $order = wc_get_order($order_id);
        if (!$order) return;

        $existing_number = $order->get_meta('_irantheme_wc_official_invoice_number');
        $eligible = \Irantheme_Dev\Irantheme_Woocommerce_Invoice_Core::is_order_eligible_for_official_invoice($order);

        // 1. If there is a number → View button (no message)
        if (!empty($existing_number)) {
            $official_button = '<a class="irantheme-invoice-btn irantheme-btn-official" href="' . esc_url(Irantheme_Woocommerce_Invoice_Core::get_print_url('official', $order_id)) . '" target="_blank" title="مشاهده فاکتور رسمی">
                                <span class="dashicons dashicons-media-spreadsheet"></span>
                                <span class="btn-label">مشاهده فاکتور رسمی</span>
                            </a>';
        }
        // 2. The number does not exist and the information is complete → Issue button with confirmation message
        elseif ($eligible) {
            $confirm_attr = ' onclick="return confirm(\'آیا مطمئن هستید؟ با صدور فاکتور رسمی، شماره فاکتور تولید خواهد شد.\');"';
            $official_button = '<a class="irantheme-invoice-btn irantheme-btn-official" href="' . esc_url(Irantheme_Woocommerce_Invoice_Core::get_print_url('official', $order_id)) . '" target="_blank" title="صدور فاکتور رسمی"' . $confirm_attr . '>
                                <span class="dashicons dashicons-media-spreadsheet"></span>
                                <span class="btn-label">صدور فاکتور رسمی</span>
                            </a>';
        }
        // 3. Information is incomplete → View button (no message, invalid invoice) 
        else {
            $official_button = '<a class="irantheme-invoice-btn irantheme-btn-official" href="' . esc_url(Irantheme_Woocommerce_Invoice_Core::get_print_url('official', $order_id)) . '" target="_blank" title="مشاهده فاکتور رسمی (پیش‌فاکتور)">
                                <span class="dashicons dashicons-media-spreadsheet"></span>
                                <span class="btn-label">مشاهده فاکتور رسمی</span>
                            </a>';
        }

        // Button output (other buttons unchanged)
?>
        <div class="irantheme-invoice-actions-group">
            <a class="irantheme-invoice-btn irantheme-btn-official"
                href="<?php echo esc_url(Irantheme_Woocommerce_Invoice_Core::get_print_url('customer', $order_id)); ?>"
                target="_blank" title="فاکتور عادی">
                <span class="dashicons dashicons-media-spreadsheet"></span>
                <span class="btn-label">فاکتور عادی</span>
            </a>

            <?php echo $official_button; ?>

            <a class="irantheme-invoice-btn irantheme-btn-shipping"
                href="<?php echo esc_url(Irantheme_Woocommerce_Invoice_Core::get_print_url('shipping', $order_id)); ?>"
                target="_blank" title="رسید پستی A5">
                <span class="dashicons dashicons-email-alt"></span>
                <span class="btn-label">رسید پستی</span>
            </a>

            <a class="irantheme-invoice-btn irantheme-btn-thermal"
                href="<?php echo esc_url(Irantheme_Woocommerce_Invoice_Core::get_print_url('thermal', $order_id)); ?>"
                target="_blank" title="فاکتور حرارتی">
                <span class="dashicons dashicons-printer"></span>
                <span class="btn-label">فاکتور حرارتی</span>
            </a>

            <a class="irantheme-invoice-btn irantheme-btn-shipping-thermal"
                href="<?php echo esc_url(Irantheme_Woocommerce_Invoice_Core::get_print_url('shipping-thermal', $order_id)); ?>"
                target="_blank" title="برچسب پستی حرارتی">
                <span class="dashicons dashicons-tag"></span>
                <span class="btn-label">برچسب پستی حرارتی</span>
            </a>
        </div>
    <?php
    }

    /**
     * Adds custom CSS styles for the invoice actions column and buttons on the WooCommerce orders page.
     * Styles enhance the appearance of buttons and ensure responsive layout.
     *
     * @return void
     * @author M-MALEKZADEH77
     * @since v1.0.2
     * @version v1.0
     */
    public function add_admin_styles()
    {
        // only adds style in the WooCommerce orders page
        $screen = get_current_screen();
        if (!$screen || $screen->id !== 'woocommerce_page_wc-orders') return;
    ?>
        <style>
            /* Custom column styling */
            .column-irantheme_invoice_actions {
                width: 280px !important;
                min-width: 280px !important;
            }

            /* Button group container */
            .irantheme-invoice-actions-group {
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
                align-items: center;
            }

            /* Button styling with labels */
            .irantheme-invoice-btn {
                display: inline-flex !important;
                align-items: center;
                gap: 4px;
                padding: 1px 8px !important;
                border-radius: 4px;
                font-size: 12px;
                font-weight: 500;
                text-decoration: none;
                transition: all 0.2s ease;
                background: #f1f1f1;
                color: #333;
                border: 1px solid #ddd;
                cursor: pointer;
            }

            .irantheme-invoice-btn .dashicons {
                font-size: 14px;
                width: 14px;
                height: 14px;
            }

            .btn-label {
                font-size: 11px;
            }

            /* Official invoice button */
            .irantheme-btn-official {
                background: #f0f0f0;
                border-color: #b4b9be;
            }

            .irantheme-btn-official:hover {
                background: #e0e0e0;
                transform: translateY(-1px);
            }

            /* Shipping receipt button */
            .irantheme-btn-shipping {
                background: #e5ffef;
                border-color: #008a20;
                color: #008a20;
            }

            .irantheme-btn-shipping:hover {
                background: #d4edda;
            }

            /* Thermal invoice button */
            .irantheme-btn-thermal {
                background: #fff9e5;
                border-color: #ffc107;
                color: #856404;
            }

            .irantheme-btn-thermal:hover {
                background: #fff3cd;
            }

            /* Thermal shipping label button */
            .irantheme-btn-shipping-thermal {
                background: #e5f4ff;
                border-color: #007cba;
                color: #00518a;
            }

            .irantheme-btn-shipping-thermal:hover {
                background: #cce5ff;
            }

            /* Hide our buttons from original actions column if they appear */
            .column-wc_actions .irantheme-invoice-actions-group {
                display: none;
            }

            /* Hover effects */
            .irantheme-invoice-btn:hover {
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                transform: translateY(-1px);
            }

            /* Responsive design for mobile */
            @media screen and (max-width: 782px) {
                .column-irantheme_invoice_actions {
                    width: auto !important;
                }

                .irantheme-invoice-actions-group {
                    flex-direction: column;
                    align-items: stretch;
                }

                .irantheme-invoice-btn {
                    justify-content: center;
                    width: 100%;
                }

                .btn-label {
                    font-size: 11px;
                }
            }
        </style>
    <?php
    }

    /**
     * Forces the custom invoice actions column to be visible for all users.
     * Removes the column from the user's hidden columns list if present.
     *
     * @return void
     * @author M-MALEKZADEH77
     * @since v1.0.2
     * @version v1.0
     */
    public function force_enable_custom_column()
    {
        $user_id = get_current_user_id();
        $screen  = get_current_screen();
        if (! $screen || $screen->id !== 'woocommerce_page_wc-orders') return;

        $option = 'manage' . $screen->id . 'columnshidden';
        $hidden = get_user_meta($user_id, $option, true);

        if (is_array($hidden)) {
            // Remove old wc_actions from hidden if exists
            $old_key = array_search('wc_actions', $hidden);
            if ($old_key !== false) {
                unset($hidden[$old_key]);
            }

            // Ensure our custom column is not hidden
            $custom_key = array_search('irantheme_invoice_actions', $hidden);
            if ($custom_key !== false) {
                unset($hidden[$custom_key]);
            }

            update_user_meta($user_id, $option, $hidden);
        }
    }

    /**
     * Registers bulk actions for printing invoices (official, customer, thermal).
     *
     * @param array $bulk_actions Existing bulk actions.
     * @return array Modified bulk actions with added invoice printing actions.
     * @author M-MALEKZADEH77
     * @since v1.0.2
     * @version v1.0
     */
    public function register_bulk_invoice_action($bulk_actions)
    {
        $bulk_actions['bulk_print_official_invoice'] = 'چاپ گروهی فاکتور رسمی';
        $bulk_actions['bulk_print_customer_invoice'] = 'چاپ گروهی فاکتور عادی';
        $bulk_actions['bulk_print_thermal_invoice'] = 'چاپ گروهی فاکتور حرارتی';
        return $bulk_actions;
    }

    /**
     * Registers bulk actions for printing shipping labels (paper and thermal).
     *
     * @param array $bulk_actions Existing bulk actions.
     * @return array Modified bulk actions with added shipping label printing actions.
     * @author M-MALEKZADEH77
     * @since v1.0.2
     * @version v1.0
     */
    public function register_bulk_shipping_label_action($bulk_actions)
    {
        $bulk_actions['bulk_print_shipping_paper_label'] = 'چاپ گروهی برچسب پستی';
        $bulk_actions['bulk_print_shipping_thermal_label'] = 'چاپ گروهی برچسب پستی حرارتی';
        return $bulk_actions;
    }

    /**
     * Handles bulk invoice print actions by redirecting to the print URL.
     * Supports official, customer, and thermal invoice types.
     *
     * @param string $redirect_to The redirect URL (unused).
     * @param string $action      The triggered bulk action.
     * @param array  $order_ids   Array of order IDs.
     * @return string The print URL for the selected invoice type.
     * @author M-MALEKZADEH77
     * @since v1.0.2
     * @version v1.0
     */
    public function handle_bulk_invoice_action($redirect_to, $action, $order_ids)
    {
        if (!in_array($action, array('bulk_print_official_invoice', 'bulk_print_customer_invoice', 'bulk_print_thermal_invoice'))) {
            return $redirect_to;
        }

        $type = 'official';

        if ($action === 'bulk_print_customer_invoice') {
            $type = 'customer';
        }

        if ($action === 'bulk_print_thermal_invoice') {
            $type = 'thermal';
        }

        return Irantheme_Woocommerce_Invoice_Core::get_print_url(
            $type,
            $order_ids
        );
    }

    /**
     * Handles bulk shipping label print actions by redirecting to the print URL.
     * Supports paper shipping label and thermal shipping label types.
     *
     * @param string $redirect_to The redirect URL (unused).
     * @param string $action      The triggered bulk action.
     * @param array  $order_ids   Array of order IDs.
     * @return string The print URL for the selected shipping label type.
     * @author M-MALEKZADEH77
     * @since v1.0.2
     * @version v1.0
     */
    public function handle_bulk_shipping_label_action($redirect_to, $action, $order_ids)
    {
        if (!in_array($action, array('bulk_print_shipping_paper_label', 'bulk_print_shipping_thermal_label'))) {
            return $redirect_to;
        }

        $type = 'shipping';

        if ($action === 'bulk_print_shipping_thermal_label') {
            $type = 'shipping-thermal';
        }

        return Irantheme_Woocommerce_Invoice_Core::get_print_url(
            $type,
            $order_ids
        );
    }

    /**
     * Adds JavaScript confirmation dialog for bulk invoice and shipping label actions.
     * Displays a warning message before generating official invoices in bulk.
     *
     * @return void
     * @author M-MALEKZADEH77
     * @since v1.0.2
     * @version v1.0
     */
    public function add_bulk_action_confirm_js()
    {
        $screen = get_current_screen();
        if (!$screen || $screen->id !== 'woocommerce_page_wc-orders') return;
    ?>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                $('#bulk-action-form').on('submit', function(e) {
                    var action = $('#bulk-action-selector-top').val();
                    var actions_to_confirm = [
                        'bulk_print_official_invoice',
                        'bulk_print_customer_invoice',
                        'bulk_print_thermal_invoice',
                        'bulk_print_shipping_paper_label',
                        'bulk_print_shipping_thermal_label'
                    ];
                    if (actions_to_confirm.includes(action)) {
                        var confirm_msg = (action === 'bulk_print_official_invoice') ?
                            'آیا مطمئن هستید؟ با چاپ گروهی فاکتور رسمی، برای هر سفارش شماره فاکتور جداگانه تولید خواهد شد.' :
                            'آیا مطمئن هستید؟ این عملیات ممکن است شامل صدور فاکتورهای رسمی باشد.';
                        if (!confirm(confirm_msg)) {
                            e.preventDefault();
                            return false;
                        }
                    }
                });
            });
        </script>
<?php
    }
}

// Initialize the class
new Irantheme_Woocommerce_Invoice_Hooks();
