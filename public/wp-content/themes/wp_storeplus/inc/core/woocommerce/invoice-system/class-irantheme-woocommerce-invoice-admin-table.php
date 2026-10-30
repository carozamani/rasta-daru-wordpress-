<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('WP_List_Table')) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * WooCommerce Invoice Admin Table Class
 * * WP_List_Table implementation for managing official invoices using WC_Order_Query (HPOS Compatible).
 * Displays a sortable, filterable table of orders with official invoice requests,
 * including customer legal info, request status, and action buttons for approval/rejection.
 *
 * @package IranTheme_Dev
 * @author M-MALEKZADEH77
 * @since v1.0.2
 * @version v1.1.0
 */
class Irantheme_Woocommerce_Invoice_Admin_Table extends \WP_List_Table
{
    private $per_page = 20;

    /**
     * Constructor. Sets up the WP_List_Table with singular and plural labels.
     *
     * @return void
     * @author M-MALEKZADEH77
     * @since v1.0.2
     * @version v1.0
     */
    public function __construct()
    {
        parent::__construct([
            'singular' => 'invoice',
            'plural'   => 'invoices',
            'ajax'     => false,
        ]);
    }

    /**
     * Defines the columns for the admin table.
     *
     * @return array Associative array of column slugs and display names.
     * @author M-MALEKZADEH77
     * @since v1.0.2
     * @version v1.0
     */
    public function get_columns()
    {
        return [
            'cb'             => '<input type="checkbox" />',
            'order_number'   => 'شماره سفارش',
            'customer_type'  => 'نوع مشتری',
            'customer'       => 'نام حقیقی / حقوقی',
            'legal_info'     => 'اطلاعات حقوقی',
            'request_date'   => 'تاریخ درخواست فاکتور',
            'order_date'     => 'تاریخ سفارش',
            'order_total'    => 'مبلغ کل',
            'request_status' => 'وضعیت درخواست',
            'actions'        => 'عملیات صدور / بررسی',
        ];
    }

    /**
     * Defines the sortable columns for the table.
     *
     * @return array Associative array of column slugs => [orderby field, default order direction].
     * @author M-MALEKZADEH77
     * @since v1.0.2
     * @version v1.0
     */
    protected function get_sortable_columns()
    {
        return [
            'order_number'      => ['ID', true],
            'order_date'        => ['date', true],
            'request_date'      => ['date', true],
            'order_total'       => ['total', false],
        ];
    }

    /**
     * Renders the checkbox column for bulk actions.
     *
     * @param array $item The current row item data.
     * @return string HTML for the checkbox input.
     * @author M-MALEKZADEH77
     * @since v1.0.2
     * @version v1.0
     */
    protected function column_cb($item)
    {
        return sprintf('<input type="checkbox" name="order_ids[]" value="%s" />', $item['order_id']);
    }

    /**
     * Default column rendering fallback. Handles all custom columns.
     *
     * @param array  $item        The current row item data.
     * @param string $column_name The column slug.
     * @return string HTML content for the column.
     * @author M-MALEKZADEH77
     * @since v1.0.2
     * @version v1.0
     */
    protected function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'order_number':
                return sprintf(
                    '<a href="%s" class="row-title">#%s</a>',
                    esc_url(admin_url('admin.php?page=wc-orders&action=edit&id=' . $item['order_id'])),
                    esc_html($item['order_number'])
                );
            case 'customer_type':
                $customer_type_label = '';
                if ($item['customer_type'] == 'person') {
                    $customer_type_label = 'حقیقی';
                } elseif ($item['customer_type'] == 'company') {
                    $customer_type_label = 'حقوقی';
                }
                return sprintf('<strong>%s</strong>', esc_html($customer_type_label));

            case 'customer':
                return esc_html($item['customer']);

            case 'legal_info':
                $legal_info = '';
                if (!empty($item['national_id'])) {
                    $legal_info .= sprintf('<small style="color:#646970;">شناسه ملی: %s</small><br>', esc_html($item['national_id']));
                }
                if (!empty($item['economic_id'])) {
                    $legal_info .= sprintf('<small style="color:#646970;">کد اقتصادی: %s</small><br>', esc_html($item['economic_id']));
                }
                if (!empty($item['registration_id'])) {
                    $legal_info .= sprintf('<small style="color:#646970;">شماره ثبت: %s</small>', esc_html($item['registration_id']));
                }
                return $legal_info;

            case 'request_date': // Render the new request date column
                return !empty($item['request_date']) ? esc_html($item['request_date']) : '<span style="color:#a7aaad; font-style:italic;">ثبت نشده</span>';

            case 'order_date':
                return esc_html($item['order_date']);
            case 'order_total':
                return wp_kses_post($item['order_total']);
            case 'request_status':
                return wp_kses_post($this->get_status_badge($item['request_status']));
            case 'actions':
                return wp_kses_post($this->get_action_buttons($item));
            default:
                return '';
        }
    }

    /**
     * Generates an HTML status badge for the invoice request status.
     *
     * @param string $status The status value ('approved', 'rejected', or other/pending).
     * @return string HTML span with appropriate styling.
     * @author M-MALEKZADEH77
     * @since v1.0.2
     * @version v1.0
     */
    private function get_status_badge($status)
    {
        switch ($status) {
            case 'approved':
                return '<span class="badge-approved" style="color:#00650e; background:#e5f4e7; padding:4px 10px; border-radius:4px; font-weight:600; font-size:11px;">تایید شده</span>';
            case 'rejected':
                return '<span class="badge-rejected" style="color:#b32d2e; background:#fbeae5; padding:4px 10px; border-radius:4px; font-weight:600; font-size:11px;">رد شده</span>';
            default:
                return '<span class="badge-pending" style="color:#c25e00; background:#fef3e6; padding:4px 10px; border-radius:4px; font-weight:600; font-size:11px;">در انتظار بررسی</span>';
        }
    }

    /**
     * Generates action buttons for each row (approve/reject or print link).
     *
     * @param array $item The current row item data.
     * @return string HTML for action buttons.
     * @author M-MALEKZADEH77
     * @since v1.0.2
     * @version v1.0
     */
    private function get_action_buttons($item)
    {
        $order_id = $item['order_id'];
        $status   = $item['request_status'];
        $buttons  = [];

        if ($status === 'pending') {
            $approve_url = wp_nonce_url(
                add_query_arg(['it_invoice_action' => 'approve', 'order_id' => $order_id]),
                'it_invoice_action_nonce'
            );
            $reject_url = wp_nonce_url(
                add_query_arg(['it_invoice_action' => 'reject', 'order_id' => $order_id]),
                'it_invoice_action_nonce'
            );

            $buttons[] = sprintf('<a href="%s" class="button button-small" style="background:#007cba; color:#fff; border-color:#007cba;">تایید</a>', esc_url($approve_url));
            $buttons[] = sprintf('<a href="%s" class="button button-small" style="color:#b32d2e; border-color:#b32d2e; margin-right:4px;">رد درخواست</a>', esc_url($reject_url));
        } elseif ($status === 'approved') {
            // Standard static link builder integration from your Core Class
            $print_url = \Irantheme_Dev\Irantheme_Woocommerce_Invoice_Core::get_print_url('official', $order_id);
            $buttons[] = sprintf('<a href="%s" target="_blank" class="button button-small action-print" style="background:#2271b1; color:#fff;"><span class="dashicons dashicons-printer" style="font-size:16px; margin-top:3px;"></span> چاپ فاکتور رسمی</a>', esc_url($print_url));
        } else {
            $buttons[] = '<span style="color:#a7aaad; font-style:italic;">رد شده توسط واحد مالی</span>';
        }

        return implode(' ', $buttons);
    }

    /**
     * Defines the bulk actions dropdown options.
     *
     * @return array Associative array of bulk action values => display names.
     * @author M-MALEKZADEH77
     * @since v1.0.2
     * @version v1.0
     */
    protected function get_bulk_actions()
    {
        return [
            'approve_bulk' => 'تایید گروهی درخواست‌ها',
            'reject_bulk'  => 'رد گروهی درخواست‌ها',
        ];
    }

    /**
     * Renders the subsubsub navigation filters (All, Pending, Approved, Rejected)
     * with dynamic count badges.
     *
     * @return array Array of view links.
     * @author M-MALEKZADEH77
     * @since v1.0.2
     * @version v1.0
     */
    protected function get_views()
    {
        $current = isset($_GET['status_filter']) ? sanitize_text_field($_GET['status_filter']) : 'all';
        $base_url = admin_url('admin.php?page=irantheme-official-invoices');

        // Dynamically calculate counts via optimized WC query wrappers
        $counts = Irantheme_Woocommerce_Invoice_Admin::get_invoice_status_counts();

        return [
            'all'      => sprintf('<a href="%s" class="%s">همه <span class="count">(%d)</span></a>', remove_query_arg('status_filter', $base_url), ($current === 'all' ? 'current' : ''), $counts['all']),
            'pending'  => sprintf('<a href="%s" class="%s">در انتظار بررسی <span class="count">(%d)</span></a>', add_query_arg('status_filter', 'pending', $base_url), ($current === 'pending' ? 'current' : ''), $counts['pending']),
            'approved' => sprintf('<a href="%s" class="%s">تایید شده <span class="count">(%d)</span></a>', add_query_arg('status_filter', 'approved', $base_url), ($current === 'approved' ? 'current' : ''), $counts['approved']),
            'rejected' => sprintf('<a href="%s" class="%s">رد شده <span class="count">(%d)</span></a>', add_query_arg('status_filter', 'rejected', $base_url), ($current === 'rejected' ? 'current' : ''), $counts['rejected']),
        ];
    }

    /**
     * Prepares the table items for display. Fetches orders with invoice request meta
     * using WC_Order_Query, applies sorting, filtering, pagination, and search.
     *
     * @return void
     * @author M-MALEKZADEH77
     * @since v1.0.2
     * @version v1.0
     */
    public function prepare_items()
    {
        $this->_column_headers = [$this->get_columns(), [], $this->get_sortable_columns()];

        $current_page  = $this->get_pagenum();
        $status_filter = isset($_GET['status_filter']) ? sanitize_text_field($_GET['status_filter']) : 'all';
        $orderby       = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'date';
        $order         = isset($_GET['order']) ? strtoupper(sanitize_text_field($_GET['order'])) : 'DESC';
        $search_term   = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';

        // Build robust meta query mapping rule
        $meta_query = [
            'relation' => 'AND',
            [
                'key'     => '_official_invoice_requested',
                'value'   => 'yes',
                'compare' => '=',
            ]
        ];

        if ($status_filter !== 'all') {
            $meta_query[] = [
                'key'     => '_official_invoice_approved',
                'value'   => $status_filter,
                'compare' => '=',
            ];
        }

        $query_args = [
            'limit'      => $this->per_page,
            'page'       => $current_page,
            'paginate'   => true,
            'status'     => array_keys(wc_get_order_statuses()), // Query all standard statuses
            'meta_query' => $meta_query,
            'orderby'    => $orderby,
            'order'      => $order,
        ];

        // Integrate search filter context seamlessly if initiated by user
        if (!empty($search_term)) {
            $query_args['search_term'] = $search_term;
        }

        // Fetch Order objects safely via standard wrapper
        $orders_data = wc_get_orders($query_args);
        $table_items = [];

        foreach ($orders_data->orders as $order) {
            $status_meta = $order->get_meta('_official_invoice_approved');
            $status_meta = empty($status_meta) ? 'pending' : $status_meta;

            $customer_type = $order->get_meta('billing_invoice_type');
            $name  = $order->get_billing_first_name() . ' ' . $order->get_billing_last_name() . ' (' . $order->get_billing_email() . ')';
            if ($customer_type == 'company') $name  = $order->get_meta('billing_legal_company_name');
            $national_id  = $order->get_meta('billing_national_id');
            $economic_id  = $order->get_meta('billing_economic_id');
            $registration_id  = $order->get_meta('billing_registration_id');
            $request_timestamp = $order->get_meta('_official_invoice_requested_date');
            $formatted_request_date = !empty($request_timestamp) ? date_i18n('Y/m/d H:i', $request_timestamp) : '';

            $table_items[] = [
                'order_id'       => $order->get_id(),
                'order_number'   => $order->get_order_number(),
                'customer_type'       => $customer_type,
                'customer'       => $name,
                'national_id'    => $national_id,
                'economic_id'    => $economic_id,
                'registration_id' => $registration_id,
                'request_date'   => $formatted_request_date,
                'order_date'     => $order->get_date_created() ? $order->get_date_created()->date_i18n('Y/m/d H:i') : '',
                'order_total'    => $order->get_formatted_order_total(),
                'request_status' => $status_meta,
            ];
        }

        $this->items = $table_items;

        $this->set_pagination_args([
            'total_items' => $orders_data->total,
            'per_page'    => $this->per_page,
            'total_pages' => $orders_data->max_num_pages,
        ]);
    }
}

/**
 * WooCommerce Invoice Admin Orchestration Class
 * * Main class responsible for adding the admin menu page, handling single and bulk
 * actions (approve/reject), rendering the list table, and managing transient cache
 * for status counts. Integrates with the official invoice core for number generation.
 *
 * @package IranTheme_Dev
 * @author M-MALEKZADEH77
 * @since v1.0.2
 * @version v1.0.2
 */
class Irantheme_Woocommerce_Invoice_Admin
{
    /**
     * Constructor. Hooks into admin_menu, admin_init, and order update actions.
     *
     * @return void
     * @author M-MALEKZADEH77
     * @since v1.0.2
     * @version v1.0
     */
    public function __construct()
    {
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_init', [$this, 'handle_single_action']);
        add_action('admin_init', [$this, 'handle_bulk_actions']);

        // Clean notification cache whenever an order is saved/updated
        add_action('woocommerce_update_order', [$this, 'clear_invoice_admin_cache']);
    }

    /**
     * Adds a submenu page under WooCommerce to manage official invoices.
     * Displays a notification badge with the count of pending requests.
     *
     * @return void
     * @author M-MALEKZADEH77
     * @since v1.0.2
     * @version v1.0
     */
    public function add_admin_menu()
    {
        $counts = self::get_invoice_status_counts();
        $menu_title = 'فاکتورهای رسمی';

        if ($counts['pending'] > 0) {
            $menu_title .= sprintf(' <span class="awaiting-mod count-%d"><span class="pending-count">%d</span></span>', $counts['pending'], $counts['pending']);
        }

        add_submenu_page(
            'woocommerce',
            'مدیریت فاکتورهای رسمی بازار',
            $menu_title,
            'manage_woocommerce',
            'irantheme-official-invoices',
            [$this, 'render_page']
        );
    }

    /**
     * Renders the admin page for official invoice management.
     * Displays the WP_List_Table with search and bulk action support.
     *
     * @return void
     * @author M-MALEKZADEH77
     * @since v1.0.2
     * @version v1.0
     */
    public function render_page()
    {
        $table = new Irantheme_Woocommerce_Invoice_Admin_Table();
        $table->prepare_items();
?>
        <div class="wrap">
            <h1 class="wp-heading-inline">مدیریت درخواست فاکتورهای رسمی و ارزیابی مالی</h1>
            <hr class="wp-header-end">

            <?php settings_errors('irantheme_invoice_messages'); ?>

            <form method="get" style="margin-top: 15px;">
                <input type="hidden" name="page" value="irantheme-official-invoices" />
                <?php if (isset($_GET['status_filter'])) : ?>
                    <input type="hidden" name="status_filter" value="<?php echo esc_attr($_GET['status_filter']); ?>" />
                <?php endif; ?>

                <?php $table->search_box('جستجوی سفارش', 'search_id'); ?>
                <?php $table->display(); ?>
            </form>
        </div>
<?php
    }

    /**
     * Handles single approval or rejection actions from the table row buttons.
     * Generates the official invoice number upon approval if legal info is complete,
     * otherwise marks as preview invoice.
     *
     * @return void
     * @author M-MALEKZADEH77
     * @since v1.0.2
     * @version v1.0
     */
    public function handle_single_action()
    {
        if (empty($_GET['it_invoice_action']) || empty($_GET['order_id']) || empty($_GET['_wpnonce'])) {
            return;
        }
        if (!wp_verify_nonce($_GET['_wpnonce'], 'it_invoice_action_nonce') || !current_user_can('manage_woocommerce')) {
            return;
        }

        $action   = sanitize_text_field($_GET['it_invoice_action']);
        $order_id = absint($_GET['order_id']);
        $order    = wc_get_order($order_id);

        if (!$order) {
            return;
        }

        if ($action === 'approve') {
            // Generate official invoice number at the moment of confirmation
            $invoice_number = Irantheme_Woocommerce_Invoice_Core::generate_official_invoice_number_safely($order_id);

            if ($invoice_number !== false) {
                // The information is complete and the number has been created
                $order->update_meta_data('_official_invoice_approved', 'approved');
                $order->save();
                $msg = 'فاکتور رسمی تایید شد. شماره فاکتور: ' . $invoice_number;
            } else {
                // Information is incomplete - preview mode
                $order->update_meta_data('_official_invoice_approved', 'approved');
                $order->update_meta_data('_is_preview_invoice', 'yes');
                $order->save();
                $msg = 'تایید شد اما به دلیل ناقص بودن اطلاعات حقوقی، پیش‌فاکتور صادر می‌شود.';
            }
            add_settings_error('irantheme_invoice_messages', 'invoice_changed', $msg, 'success');
        } elseif ($action === 'reject') {
            $order->update_meta_data('_official_invoice_approved', 'rejected');
            $order->save();
            add_settings_error('irantheme_invoice_messages', 'invoice_changed', 'درخواست فاکتور رسمی رد شد.', 'success');
        }

        $this->clear_invoice_admin_cache();
        wp_safe_redirect(remove_query_arg(['it_invoice_action', 'order_id', '_wpnonce']));
        exit;
    }

    /**
     * Processes bulk actions (approve_bulk or reject_bulk) from the table.
     * Updates the status for multiple orders and attempts to generate invoice numbers
     * for approved orders where eligible.
     *
     * @return void
     * @author M-MALEKZADEH77
     * @since v1.0.2
     * @version v1.0
     */
    public function handle_bulk_actions()
    {
        $wp_list_action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : (isset($_GET['action2']) ? sanitize_text_field($_GET['action2']) : '');
        if (!in_array($wp_list_action, ['approve_bulk', 'reject_bulk']) || empty($_GET['order_ids'])) {
            return;
        }
        if (!current_user_can('manage_woocommerce')) {
            return;
        }

        $order_ids  = array_map('absint', $_GET['order_ids']);
        $new_status = ($wp_list_action === 'approve_bulk') ? 'approved' : 'rejected';
        $counter    = 0;

        foreach ($order_ids as $id) {
            $order = wc_get_order($id);
            if ($order) {
                if ($new_status === 'approved') {
                    // Attempt to generate invoice number
                    $invoice_number = Irantheme_Woocommerce_Invoice_Core::generate_official_invoice_number_safely($id);
                    if ($invoice_number === false) {
                        // Incomplete information -> prefactor
                        $order->update_meta_data('_is_preview_invoice', 'yes');
                    }
                }
                $order->update_meta_data('_official_invoice_approved', $new_status);
                $order->save();
                $counter++;
            }
        }

        if ($counter > 0) {
            $this->clear_invoice_admin_cache();
            $msg = sprintf('وضعیت فاکتور رسمی %d سفارش با موفقیت بروزرسانی شد.', $counter);
            add_settings_error('irantheme_invoice_messages', 'bulk_success', $msg, 'success');
        }

        wp_safe_redirect(remove_query_arg(['action', 'action2', 'order_ids', '_wpnonce']));
        exit;
    }

    /**
     * Retrieves the counts of orders with official invoice requests, grouped by status.
     * Uses a transient cache to reduce database load.
     *
     * @return array Associative array with keys 'all', 'pending', 'approved', 'rejected'.
     * @author M-MALEKZADEH77
     * @since v1.0.2
     * @version v1.0
     */
    public static function get_invoice_status_counts()
    {
        $cached_counts = get_transient('it_invoice_admin_counts');
        if ($cached_counts !== false) {
            return $cached_counts;
        }

        $statuses = ['pending', 'approved', 'rejected'];
        $output   = ['all' => 0, 'pending' => 0, 'approved' => 0, 'rejected' => 0];

        foreach ($statuses as $status) {
            $query_args = [
                'limit'      => -1, // Fetch all matching to count properly
                'return'     => 'ids',
                'status'     => array_keys(wc_get_order_statuses()),
                'meta_query' => [
                    [
                        'key'     => '_official_invoice_requested',
                        'value'   => 'yes',
                        'compare' => '=',
                    ],
                    [
                        'key'     => '_official_invoice_approved',
                        'value'   => $status,
                        'compare' => '=',
                    ]
                ]
            ];

            $results = wc_get_orders($query_args);

            // Сount the array elements securely
            $count = is_array($results) ? count($results) : 0;

            $output[$status] = $count;
            $output['all']  += $count;
        }

        set_transient('it_invoice_admin_counts', $output, DAY_IN_SECONDS);
        return $output;
    }

    /**
     * Clears the cached invoice status counts transient.
     * Called when orders are updated or bulk actions are performed.
     *
     * @return void
     * @author M-MALEKZADEH77
     * @since v1.0.2
     * @version v1.0
     */
    public function clear_invoice_admin_cache()
    {
        delete_transient('it_invoice_admin_counts');
    }
}

new Irantheme_Woocommerce_Invoice_Admin();
