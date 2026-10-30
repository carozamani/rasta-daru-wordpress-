<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;

/**
 * WooCommerce Support Ticket System
 * * @package WordPress
 * @subpackage Storeplus
 * @since v1.0
 * * This class provides a complete support ticket system for WooCommerce
 */
if (!class_exists('Irantheme_Woocommerce_Support_Ticket')) {
    class Irantheme_Woocommerce_Support_Ticket
    {
        private $terms = array();

        /**
         * Constructor
         * @return void
         * @param void
         * @author iranianclever
         * @since v1.0
         * @version v1.0
         */
        public function __construct()
        {
            $tickets_support_checkbox = get_option('irantheme_wc_tickets_checkbox');
            if ($tickets_support_checkbox == 'on'):
                add_action('init', array($this, 'register_ticket_status_taxonomy'));
                add_action('init', array($this, 'create_default_ticket_statuses'));
                add_action('woocommerce_account_menu_items', array($this, 'add_support_tab_to_myaccount'), 10, 1);
                add_action('init', array($this, 'add_support_tickets_endpoint'));
                add_filter('woocommerce_endpoint_tickets_title', array($this, 'support_tickets_endpoint_title'));
                add_filter('woocommerce_account_tickets_endpoint', array($this, 'tickets_endpoint_content'));
                // Keep legacy endpoint titles and rendering for backward compatibility.
                add_filter('woocommerce_endpoint_support-tickets_title', array($this, 'support_tickets_endpoint_title'));
                add_filter('woocommerce_endpoint_view-ticket_title', array($this, 'support_tickets_endpoint_title'));
                add_filter('woocommerce_endpoint_add-ticket_title', array($this, 'support_tickets_endpoint_title'));
                add_filter('woocommerce_account_support-tickets_endpoint', array($this, 'support_tickets_content'));
                add_filter('add_meta_boxes', array($this, 'add_ticket_meta_boxes'));
                // Register activation hook for flush rewrite rules on theme switch
                add_action('after_switch_theme', array($this, 'flush_rewrite_rules_on_activation'));
                // Add this to disable Gutenberg for tickets
                add_action('init', array($this, 'add_meta_box_support'));

                // Register ticket reply post type
                add_action('init', array($this, 'register_ticket_reply_post_type'));

                // Add admin reply meta box
                add_action('add_meta_boxes', array($this, 'add_admin_reply_meta_box'));

                // Save admin reply
                add_action('save_post_support_ticket', array($this, 'save_admin_reply'));

                // Display replies in frontend via AJAX
                add_action('wp_ajax_submit_ticket_reply', array($this, 'handle_ticket_reply_submission'));

                // Track when user views a ticket
                add_action('template_redirect', array($this, 'track_ticket_view'));

                // Process forms early, before any output
                add_action('template_redirect', array($this, 'process_ticket_forms_early'));

                // Added: Hook to add the notification bubble to the admin menu
                add_action('admin_menu', array($this, 'add_ticket_notification_bubble'));

                $this->terms = array(
                    'باز' => 'open',
                    'در حال بررسی' => 'under-review',
                    'پاسخ داده شده' => 'answered',
                    'بسته' => 'closed',
                );
            endif;
        }


        /**
         * Handle ticket reply AJAX submissions.
         * Uses a unique nonce name (ticket_reply_nonce) and action (submit_ticket_reply)
         * to avoid conflicts with the product Q&A reply system which uses
         * (user_reply_nonce) and (submit_user_reply).
         * @return void
         * @author iranianclever
         * @since v1.0
         * @version v1.1
         */
        public function handle_ticket_reply_submission()
        {
            if (!is_user_logged_in()) {
                wp_send_json_error(array('message' => 'برای ارسال پاسخ باید وارد حساب کاربری خود شوید.'), 401);
            }

            // Unique nonce name and action for the ticket system
            if (!isset($_POST['ticket_reply_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['ticket_reply_nonce'])), 'submit_ticket_reply')) {
                wp_send_json_error(array('message' => 'درخواست نامعتبر است. صفحه را تازه‌سازی کرده و دوباره تلاش کنید.'), 403);
            }

            $ticket_id = isset($_POST['ticket_id']) ? absint(wp_unslash($_POST['ticket_id'])) : 0;
            $ticket = $ticket_id ? get_post($ticket_id) : null;
            $user_id = get_current_user_id();

            if (!$ticket || $ticket->post_type !== 'support_ticket' || (int) $ticket->post_author !== (int) $user_id) {
                wp_send_json_error(array('message' => 'شما دسترسی به این تیکت ندارید.'), 403);
            }

            $status = wp_get_post_terms($ticket_id, 'ticket_status', array('fields' => 'names'));
            $status_name = !empty($status) && !is_wp_error($status) ? $status[0] : 'باز';
            if (!in_array($status_name, array('باز', 'در حال بررسی', 'پاسخ داده شده'), true)) {
                wp_send_json_error(array('message' => 'این تیکت بسته شده و امکان ارسال پاسخ وجود ندارد.'), 400);
            }

            $reply_content = isset($_POST['user_reply_message']) ? sanitize_textarea_field(wp_unslash($_POST['user_reply_message'])) : '';
            if ($reply_content === '') {
                wp_send_json_error(array('message' => 'لطفاً متن پاسخ را وارد کنید.'), 400);
            }

            $reply_id = wp_insert_post(array(
                'post_type' => 'ticket_reply',
                'post_content' => $reply_content,
                'post_status' => 'publish',
                'post_author' => $user_id,
            ), true);

            if (is_wp_error($reply_id) || !$reply_id) {
                wp_send_json_error(array('message' => 'خطا در ارسال پاسخ. لطفاً مجدد تلاش کنید.'), 500);
            }

            update_post_meta($reply_id, '_ticket_id', $ticket_id);
            wp_set_object_terms($ticket_id, 'در حال بررسی', 'ticket_status');
            $this->send_user_reply_notification_email($ticket_id, $reply_content);

            wp_send_json_success(array(
                'message' => 'پاسخ شما با موفقیت ارسال شد.',
                'ticket_id' => $ticket_id,
                'redirect_url' => esc_url_raw($this->get_view_ticket_url($ticket_id)),
            ));
        }


        /**
         * Process ticket forms early (before any output) to prevent headers already sent errors.
         * Handles both new ticket submission and user reply submission.
         * @return void
         * @param void
         * @author iranianclever
         * @since v1.0
         * @version v1.0
         */
        public function process_ticket_forms_early()
        {
            // Only process on my-account pages
            if (!is_user_logged_in()) {
                return;
            }

            // Process new ticket form
            if (isset($_POST['submit_ticket'])) {
                $this->process_new_ticket_form();
            }

            // Process user reply form (unique name: submit_ticket_reply, not submit_user_reply)
            if (isset($_POST['submit_ticket_reply'])) {
                $ticket_id = absint(get_query_var('view-ticket', 0));

                // Read ticket ID from the new tickets endpoint.
                if (!$ticket_id) {
                    $tickets_endpoint = get_query_var('tickets', '');
                    if (is_string($tickets_endpoint) && ctype_digit($tickets_endpoint)) {
                        $ticket_id = absint($tickets_endpoint);
                    }
                }

                // Fallback to legacy endpoint parsing
                if (!$ticket_id) {
                    $current_endpoint = get_query_var('support-tickets', '');
                    if (is_string($current_endpoint) && str_contains($current_endpoint, 'view-ticket/')) {
                        $ticket_exploded = explode('/', $current_endpoint);
                        $ticket_id = absint($ticket_exploded[1] ?? 0);
                    }
                }

                if ($ticket_id) {
                    $this->handle_user_reply_submission($ticket_id);
                }
            }
        }


        /**
         * Counts open or under-review support tickets.
         * Tickets must have the 'باز' (open) or 'در حال بررسی' (under-review) term in the 'ticket_status' taxonomy.
         * @return int The count of pending tickets
         * @param int
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public function get_pending_tickets_count()
        {
            // CPT: support_ticket
            // Taxonomy: ticket_status
            // Terms: 'باز' (open), 'در حال بررسی' (under-review)

            $args = array(
                'post_type'      => 'support_ticket',
                'post_status'    => 'publish',
                'tax_query'      => array(
                    'relation' => 'OR',
                    array(
                        'taxonomy' => 'ticket_status',
                        'field'    => 'slug',
                        'terms'    => array('open', 'under-review'),
                    ),
                ),
                'posts_per_page' => -1,
                'fields'         => 'ids',
            );

            // Use get_posts to ensure correct counting based on taxonomy terms
            $tickets = get_posts($args);

            return count($tickets);
        }


        /**
         * Finds the support ticket admin menu item and injects the red notification bubble 
         * if there are pending tickets.
         * @return void
         * @param void
         * @author iranianclever
         * @since v1.0
         * @version v1.0
         */
        public function add_ticket_notification_bubble()
        {
            // Access the global admin menu variable
            global $menu;

            $pending_count = $this->get_pending_tickets_count();

            if ($pending_count > 0) {
                // The URL (Slug) of the main custom post type menu 'support_ticket'
                // This is typically the standard format for CPTs
                $menu_slug = 'edit.php?post_type=support_ticket';

                foreach ($menu as $key => $value) {
                    // Column [2] is the Menu URL (Menu Slug)
                    if ($menu[$key][2] == $menu_slug) {
                        // The 'awaiting-mod' class displays the standard red WordPress notification circle.
                        $menu[$key][0] .= ' <span class="awaiting-mod">' . number_format_i18n($pending_count) . '</span>';
                        break;
                    }
                }
            }
        }


        /**
         * Register ticket status taxonomy
         * @return void
         * @param void
         * @author iranianclever
         * @since v1.0
         * @version v1.0
         */
        function register_ticket_status_taxonomy()
        {
            $labels = array(
                'name' => 'وضعیت تیکت',
                'singular_name' => 'وضعیت',
                'search_items' => 'جستجوی وضعیت‌ها',
                'all_items' => 'همه وضعیت‌ها',
                'edit_item' => 'ویرایش وضعیت',
                'update_item' => 'بروزرسانی وضعیت',
                'add_new_item' => 'افزودن وضعیت جدید',
            );

            $args = array(
                'labels' => $labels,
                'hierarchical' => true,
                'show_ui' => true,
                'show_admin_column' => true,
                'show_in_rest' => false,
            );

            register_taxonomy('ticket_status', 'support_ticket', $args);
        }


        /**
         * Register ticket reply post type
         * @return void
         * @param void
         * @author iranianclever
         * @since v1.0
         * @version v1.0
         */
        function register_ticket_reply_post_type()
        {
            // Register support ticket
            register_post_type('support_ticket', array(
                'labels' => array(
                    'name' => 'تیکت‌های پشتیبانی',
                    'singular_name' => 'تیکت',
                    'menu_name' => 'پشتیبانی',
                    'add_new' => 'تیکت جدید',
                    'add_new_item' => 'افزودن تیکت جدید',
                    'edit_item' => 'ویرایش تیکت',
                    'new_item' => 'تیکت جدید',
                    'view_item' => 'مشاهده تیکت',
                    'search_items' => 'جستجوی تیکت‌ها',
                ),
                'public' => false,
                'show_ui' => true,
                'show_in_menu' => true,
                'capability_type' => 'post',
                'map_meta_cap' => true,
                'supports' => array('title', 'editor'),
                'menu_icon' => 'dashicons-tickets',
                'show_in_rest' => true,
                // Add this to ensure content is displayed in Gutenberg
                'template' => array(
                    array('core/paragraph', array(
                        'placeholder' => 'متن تیکت...',
                    )),
                ),
            ));

            register_post_type(
                'ticket_reply',
                array(
                    'labels' => array(
                        'name' => 'پاسخ تیکت‌ها',
                        'singular_name' => 'پاسخ تیکت',
                    ),
                    'public' => false,
                    'show_ui' => false,
                    'show_in_menu' => false,
                    'show_in_rest' => false,
                    'supports' => array('editor', 'author'),
                )
            );
        }


        /**
         * Add admin reply meta box
         * @return void
         * @param void
         * @author iranianclever
         * @since v1.0
         * @version v1.0
         */
        function add_admin_reply_meta_box()
        {
            add_meta_box(
                'ticket_admin_reply',
                'پاسخ پشتیبانی',
                array($this, 'display_admin_reply_meta_box'),
                'support_ticket',
                'normal',
                'high'
            );

            // Meta box for reply history
            add_meta_box(
                'ticket_replies_history',
                'تاریخچه پاسخ‌ها',
                array($this, 'display_ticket_replies_history'),
                'support_ticket',
                'normal',
                'default'
            );
        }


        /**
         * Display admin reply meta box
         * @return void
         * @param mixed post mixes
         * @author iranianclever
         * @since v1.0
         * @version v1.0
         */
        function display_admin_reply_meta_box($post)
        {
            wp_nonce_field('save_admin_reply', 'admin_reply_nonce');

            // Check if we should use a simple textarea instead of wp_editor
            $use_simple_editor = false;

            // If Gutenberg is active, use simple textarea
            if (function_exists('use_block_editor_for_post') && use_block_editor_for_post($post)) {
                $use_simple_editor = true;
            }

            // Also check if the classic editor plugin is active
            if (!class_exists('Classic_Editor') && !defined('CLASSIC_EDITOR_VERSION')) {
                $use_simple_editor = true;
            }
?>
            <div class="admin-reply-box">
                <p>
                    <label for="admin_reply_message"><strong>پاسخ پشتیبانی:</strong></label>
                </p>
                <?php if ($use_simple_editor) : ?>
                    <textarea name="admin_reply_message" id="admin_reply_message" rows="8" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;"></textarea>
                    <p class="description">از HTML ساده مانند <code>&lt;strong&gt;</code> و <code>&lt;a&gt;</code> می‌توانید استفاده کنید.</p>
                <?php else : ?>
                    <?php
                    wp_editor('', 'admin_reply_message', array(
                        'textarea_name' => 'admin_reply_message',
                        'textarea_rows' => 8,
                        'media_buttons' => false,
                        'teeny' => true
                    ));
                    ?>
                <?php endif; ?>
                <p>
                    <label>
                        <input type="checkbox" name="close_ticket_after_reply" value="1">
                        بستن تیکت پس از ارسال پاسخ
                    </label>
                </p>
                <p class="description">
                    پس از ارسال پاسخ، وضعیت تیکت به "پاسخ داده شده" تغییر خواهد کرد.
                </p>
            </div>
            <?php
        }

        /**
         * Display reply history
         * @return string date string
         * @param mixed post mixed
         * @author iranianclever
         * @since v1.0
         * @version v1.0
         */
        function display_ticket_replies_history($post)
        {
            // 1. Prepare the list of admin user IDs once.
            $admin_users = get_users(array(
                'role' => 'administrator',
                'fields' => 'ID'
            ));

            // 2. Get all replies and prepare the array (no change)
            $replies = $this->get_ticket_replies($post->ID);
            $thread_messages = array();

            // 3. Add the original ticket message
            $thread_messages[] = array(
                'type'      => 'original_ticket',
                'post'      => $post,
                'author_id' => $post->post_author,
                'date_gmt'  => get_post_time('G', true, $post),
            );

            // 4. Add all subsequent replies
            if ($replies) {
                foreach ($replies as $reply) {
                    $thread_messages[] = array(
                        'type'      => 'reply',
                        'post'      => $reply,
                        'author_id' => $reply->post_author,
                        'date_gmt'  => get_post_time('G', true, $reply),
                    );
                }
            }

            // 5. Sort messages
            usort($thread_messages, function ($a, $b) {
                return $a['date_gmt'] <=> $b['date_gmt'];
            });

            // 6. Display sorted history
            if (!empty($thread_messages)) :
            ?>
                <div class="ticket-replies-history">
                    <?php foreach ($thread_messages as $message) :
                        $message_post = $message['post'];
                        $author = get_userdata($message['author_id']);
                        $is_admin = in_array($message['author_id'], $admin_users);

                        // The original ticket message is always displayed as a user
                        if ($message['type'] === 'original_ticket') {
                            $is_admin = false;
                            $content = $message_post->post_content;
                        } else {
                            $content = $message_post->post_content;
                        }

                        $reply_class = $is_admin ? 'admin-reply' : 'user-reply';
                        $reply_title = $is_admin ? '<i class="iconsax" icon-name="user-octagon"></i> پشتیبانی' : '<i class="iconsax" icon-name="user-2"></i> کاربر';

                        // Skip if author no longer exists
                        if (!$author) {
                            continue;
                        }

                        // Message output
                    ?>
                        <div class="ticket-reply <?php echo $reply_class; ?>">
                            <div class="reply-header">
                                <strong><?php echo $reply_title; ?>:</strong>
                                <?php echo esc_html($author->display_name); ?>
                                <span class="reply-date">- <?php echo get_the_date('Y/m/d H:i', $message_post); ?></span>
                            </div>
                            <div class="reply-content">
                                <?php
                                // Use wpautop for formatting but avoid the_content filter which can break in Gutenberg
                                echo wpautop(wp_kses_post($content));
                                ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <p>هنوز پاسخی ارسال نشده است.</p>
            <?php endif;
        }


        /**
         * Add support for classic editor in meta boxes
         * @return void
         * @param void
         * @author iranianclever
         * @since v1.0
         * @version v1.0
         */
        function add_meta_box_support()
        {
            add_filter('gutenberg_can_edit_post_type', array($this, 'disable_gutenberg_for_tickets'), 10, 2);
            add_filter('use_block_editor_for_post_type', array($this, 'disable_gutenberg_for_tickets'), 10, 2);
        }

        /**
         * Disable Gutenberg for support tickets
         * @return bool
         * @param bool $current_status
         * @param string $post_type
         * @author iranianclever
         * @since v1.0
         * @version v1.0
         */
        function disable_gutenberg_for_tickets($current_status, $post_type)
        {
            if ($post_type === 'support_ticket') {
                return false;
            }
            return $current_status;
        }


        /**
         * Get ticket replies
         * @return wp_post post mixed
         * @param int ticket id
         * @author iranianclever
         * @since v1.0
         * @version v1.0
         */
        function get_ticket_replies($ticket_id)
        {
            return get_posts(array(
                'post_type' => 'ticket_reply',
                'meta_query' => array(
                    array(
                        'key' => '_ticket_id',
                        'value' => $ticket_id,
                        'compare' => '=',
                        'type' => 'NUMERIC',
                    ),
                ),
                'numberposts' => -1,
                'orderby' => 'date',
                'order' => 'ASC'
            ));
        }


        /**
         * Improved admin reply save function
         * @return void
         * @param int post id
         * @author iranianclever
         * @since v1.0
         * @version v1.0
         */
        function save_admin_reply($post_id)
        {
            // Check nonce
            if (!isset($_POST['admin_reply_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['admin_reply_nonce'])), 'save_admin_reply')) {
                return;
            }

            // Check auto-save
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return;
            }

            // Check permissions
            if (!current_user_can('edit_post', $post_id)) {
                return;
            }

            // Save reply if message exists
            if (!empty($_POST['admin_reply_message'])) {
                $reply_content = wp_kses_post(wp_unslash($_POST['admin_reply_message']));

                // Create reply post
                $reply_id = wp_insert_post(array(
                    'post_type' => 'ticket_reply',
                    'post_content' => $reply_content,
                    'post_status' => 'publish',
                    'post_author' => get_current_user_id(),
                ));

                if ($reply_id && !is_wp_error($reply_id)) {
                    // Link reply to ticket
                    update_post_meta($reply_id, '_ticket_id', $post_id);

                    // Change ticket status - if not closed, change to "پاسخ داده شده"
                    if (isset($_POST['close_ticket_after_reply']) && $_POST['close_ticket_after_reply'] == '1') {
                        wp_set_object_terms($post_id, 'بسته', 'ticket_status');
                    } else {
                        wp_set_object_terms($post_id, 'پاسخ داده شده', 'ticket_status');
                    }

                    // Send email to user
                    $this->send_reply_notification_email($post_id, $reply_content);

                    // Success message
                    add_action('admin_notices', function () {
                        echo '<div class="notice notice-success is-dismissible"><p>پاسخ با موفقیت ارسال شد.</p></div>';
                    });
                }
            } else {
                // Even without reply, allow closing the ticket
                if (isset($_POST['close_ticket_after_reply']) && $_POST['close_ticket_after_reply'] == '1') {
                    wp_set_object_terms($post_id, 'بسته', 'ticket_status');
                }
            }
        }


        /**
         * Send email when admin replies
         * @return void
         * @param int ticket id
         * @param string reply content
         * @author iranianclever
         * @since v1.0
         * @version v1.0
         */
        function send_reply_notification_email($ticket_id, $reply_content)
        {
            $ticket = get_post($ticket_id);
            if (!$ticket) {
                return;
            }

            $user_id = $ticket->post_author;
            $user = get_userdata($user_id);

            if (!$user) {
                return;
            }

            $subject = sanitize_text_field('پاسخ به تیکت پشتیبانی شما: ' . $ticket->post_title);
            $ticket_url = $this->get_view_ticket_url($ticket_id);

            $safe_user_name = esc_html($user->display_name);
            $safe_ticket_title = esc_html($ticket->post_title);
            $safe_reply_content = wpautop(wp_kses_post($reply_content));
            $ticket_url = esc_url($ticket_url);

            $message = "
        <div style='font-family: Tahoma, sans-serif; direction: rtl; text-align: right;'>
            <h2 style='color: #333;'>پاسخ به تیکت پشتیبانی</h2>
            <p>کاربر گرامی {$safe_user_name},</p>
            <p>به تیکت شما با موضوع &quot;<strong>{$safe_ticket_title}</strong>&quot; پاسخ داده شد.</p>
            <div style='background: #f8f9fa; padding: 15px; border-right: 4px solid #007cba; margin: 15px 0;'>
                <h4 style='margin-top: 0;'>پاسخ پشتیبانی:</h4>
                {$safe_reply_content}
            </div>
            <p>برای مشاهده کامل تیکت و پاسخ‌ها، روی لینک زیر کلیک کنید:</p>
            <p><a href='{$ticket_url}' style='background: #007cba; color: white; padding: 10px 20px; text-decoration: none; border-radius: 3px; display: inline-block;'>مشاهده تیکت</a></p>
            <hr style='border: none; border-top: 1px solid #eee; margin: 20px 0;'>
            <p style='color: #666; font-size: 12px;'>این ایمیل به صورت خودکار ارسال شده است. لطفاً به آن پاسخ ندهید.</p>
        </div>
    ";

            $headers = array('Content-Type: text/html; charset=UTF-8');

            wp_mail($user->user_email, $subject, $message, $headers);
        }


        /**
         * Create default statuses with smart migration to prevent duplicate terms.
         * Handles the case where existing installs have Persian-slug terms and
         * new installs use English slugs.
         * @return void
         * @param void
         * @author iranianclever
         * @since v1.0
         * @version v1.1
         */
        function create_default_ticket_statuses()
        {
            // Map: Persian name => English slug
            $statuses = array(
                'باز'           => 'open',
                'در حال بررسی'  => 'under-review',
                'پاسخ داده شده' => 'answered',
                'بسته'          => 'closed',
            );

            foreach ($statuses as $name => $slug) {
                // Look for a term by English slug
                $existing_by_slug = get_term_by('slug', $slug, 'ticket_status');

                // Look for a term by Persian name
                $existing_by_name = get_term_by('name', $name, 'ticket_status');

                // Case 1: Neither exists → create fresh
                if (!$existing_by_slug && !$existing_by_name) {
                    wp_insert_term($name, 'ticket_status', array('slug' => $slug));
                    continue;
                }

                // Case 2: Only slug exists → nothing to do
                if ($existing_by_slug && !$existing_by_name) {
                    continue;
                }

                // Case 3: Only name exists (old install) → migrate its slug to English
                if (!$existing_by_slug && $existing_by_name) {
                    wp_update_term($existing_by_name->term_id, 'ticket_status', array(
                        'slug' => $slug,
                    ));
                    continue;
                }

                // Case 4: Both exist but are different terms → merge into the slug-based term
                if ($existing_by_slug && $existing_by_name && $existing_by_slug->term_id !== $existing_by_name->term_id) {
                    // Move all tickets from the old Persian-slug term to the new English-slug term
                    $posts = get_posts(array(
                        'post_type' => 'support_ticket',
                        'numberposts' => -1,
                        'fields' => 'ids',
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'ticket_status',
                                'field' => 'term_id',
                                'terms' => $existing_by_name->term_id,
                            ),
                        ),
                    ));

                    foreach ($posts as $post_id) {
                        wp_set_object_terms($post_id, $existing_by_slug->term_id, 'ticket_status', false);
                    }

                    // Delete the old Persian-slug term
                    wp_delete_term($existing_by_name->term_id, 'ticket_status');
                }
            }
        }


        /**
         * Return count unread tickets
         *
         * A ticket is considered "unread" when:
         *  - It has at least one admin reply newer than the user's last view, OR
         *  - Its current status is "answered" (پاسخ داده شده), regardless of view history.
         *
         * @return int unread tickets
         * @param void
         * @author iranianclever
         * @since v1.0
         * @version v1.2
         */
        public static function count_unread_tickets()
        {
            if (!is_user_logged_in()) {
                return 0;
            }

            $user_id = get_current_user_id();

            // Get all user tickets without loading complete post objects.
            $tickets = get_posts(array(
                'post_type' => 'support_ticket',
                'post_status' => 'publish',
                'author' => $user_id,
                'numberposts' => -1,
                'fields' => 'ids',
                'no_found_rows' => true,
                'update_post_meta_cache' => false,
                'update_post_term_cache' => true,
            ));

            if (!$tickets) {
                return 0;
            }

            // Fetch the latest administrator reply for all tickets in one query.
            $admin_users = get_users(array(
                'role' => 'administrator',
                'fields' => 'ID',
            ));

            if (empty($admin_users)) {
                return 0;
            }

            $latest_replies = get_posts(array(
                'post_type' => 'ticket_reply',
                'post_status' => 'publish',
                'numberposts' => -1,
                'author__in' => $admin_users,
                'meta_query' => array(
                    array(
                        'key' => '_ticket_id',
                        'value' => array_map('absint', $tickets),
                        'compare' => 'IN',
                        'type' => 'NUMERIC',
                    ),
                ),
                'orderby' => 'date',
                'order' => 'DESC',
                'no_found_rows' => true,
                'update_post_meta_cache' => true,
                'update_post_term_cache' => false,
            ));

            $latest_reply_dates = array();
            foreach ($latest_replies as $reply) {
                $reply_ticket_id = absint(get_post_meta($reply->ID, '_ticket_id', true));
                if ($reply_ticket_id && !isset($latest_reply_dates[$reply_ticket_id])) {
                    $latest_reply_dates[$reply_ticket_id] = strtotime($reply->post_date);
                }
            }

            $unread_count = 0;
            foreach ($tickets as $ticket_id) {

                // --- NEW: Check ticket status first ---
                // If the ticket status is "answered" (پاسخ داده شده), always count it as unread.
                $ticket_status_terms = wp_get_post_terms($ticket_id, 'ticket_status', array('fields' => 'names'));
                $ticket_status_name  = (!is_wp_error($ticket_status_terms) && !empty($ticket_status_terms))
                    ? $ticket_status_terms[0]
                    : '';

                if ($ticket_status_name === 'پاسخ داده شده') {
                    $unread_count++;
                    continue;
                }
                // --- END NEW ---

                // Skip tickets without admin replies.
                if (empty($latest_reply_dates[$ticket_id])) {
                    continue;
                }

                // A ticket with an admin reply is unread if the user has not viewed it
                // since the last admin reply.
                $last_view_date = get_user_meta($user_id, '_ticket_last_view_' . $ticket_id, true);
                if (!$last_view_date || $latest_reply_dates[$ticket_id] > (int) $last_view_date) {
                    $unread_count++;
                }
            }

            return $unread_count;
        }


        /**
         * Check if ticket has unread admin replies
         * @return bool
         * @param int ticket id
         * @author iranianclever
         * @since v1.0
         * @version v1.0
         */
        public static function has_unread_admin_replies($ticket_id)
        {
            $last_admin_reply_date = self::get_last_admin_reply_date($ticket_id);
            $last_view_date = self::get_user_last_view_date($ticket_id);

            // If there are admin replies and user hasn't viewed since last reply
            if ($last_admin_reply_date && (!$last_view_date || $last_admin_reply_date > $last_view_date)) {
                return true;
            }

            return false;
        }


        /**
         * Get last admin reply date
         * @return string
         * @param int ticket id
         * @author iranianclever
         * @since v1.0
         * @version v1.0
         */
        public static function get_last_admin_reply_date($ticket_id)
        {
            // Find all admin users
            $admin_users = get_users(array(
                'role' => 'administrator',
                'fields' => 'ID'
            ));

            $replies = get_posts(array(
                'post_type' => 'ticket_reply',
                'meta_query' => array(
                    array(
                        'key' => '_ticket_id',
                        'value' => $ticket_id,
                        'compare' => '=',
                        'type' => 'NUMERIC',
                    ),
                ),
                'numberposts' => 1,
                'orderby' => 'date',
                'order' => 'DESC',
                'author__in' => $admin_users
            ));

            if ($replies) {
                return strtotime($replies[0]->post_date);
            }

            return false;
        }


        /**
         * Get user last view date
         * @return string
         * @param int ticket id
         * @author iranianclever
         * @since v1.0
         * @version v1.0
         */
        public static function get_user_last_view_date($ticket_id)
        {
            return get_user_meta(get_current_user_id(), '_ticket_last_view_' . $ticket_id, true);
        }


        /**
         * Update user last view date
         * @return string
         * @param int ticket id
         * @author iranianclever
         * @since v1.0
         * @version v1.0
         */
        function update_user_last_view_date($ticket_id)
        {
            update_user_meta(get_current_user_id(), '_ticket_last_view_' . $ticket_id, current_time('timestamp'));
        }


        /**
         * Track when user views a ticket
         * @return void
         * @param void
         * @author iranianclever
         * @since v1.0
         * @version v1.0
         */
        function track_ticket_view()
        {
            if (!is_user_logged_in()) {
                return;
            }

            $ticket_id = 0;

            // Method 1: Read the ticket ID from the new tickets endpoint.
            $tickets_endpoint = get_query_var('tickets', '');
            if (is_string($tickets_endpoint) && ctype_digit($tickets_endpoint)) {
                $ticket_id = absint($tickets_endpoint);
            }

            // Method 2: Legacy direct endpoint (e.g. /my-account/view-ticket/123/).
            $direct_view = get_query_var('view-ticket', '');
            if (!empty($direct_view) && is_numeric($direct_view)) {
                $ticket_id = absint($direct_view);
            }

            // Method 3: Read the legacy support-tickets endpoint value.
            if (!$ticket_id) {
                $endpoint_value = get_query_var('support-tickets', '');
                if (!empty($endpoint_value) && is_string($endpoint_value)) {
                    // endpoint_value = 'view-ticket/123'
                    if (preg_match('#view-ticket/(\d+)#', $endpoint_value, $matches)) {
                        $ticket_id = absint($matches[1]);
                    }
                }
            }

            // Method 4: Fallback — parse the request URL directly
            if (!$ticket_id) {
                $request_uri = isset($_SERVER['REQUEST_URI']) ? sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI'])) : '';
                if (preg_match('#view-ticket/(\d+)#', $request_uri, $matches)) {
                    $ticket_id = absint($matches[1]);
                }
            }

            if ($ticket_id > 0) {
                $ticket = get_post($ticket_id);
                if ($ticket && $ticket->post_type === 'support_ticket' && (int) $ticket->post_author === (int) get_current_user_id()) {
                    $this->update_user_last_view_date($ticket_id);
                    $this->update_notification_count();
                }
            }
        }


        /**
         * Update notification count (optional - for AJAX updates)
         * @return void
         * @param void
         * @author iranianclever
         * @since v1.0
         * @version v1.0
         */
        function update_notification_count()
        {
            // Clear any cached unread count for the current user
            wp_cache_delete('irantheme_unread_tickets_' . get_current_user_id(), 'irantheme');

            // This can be used for real-time updates if needed
            do_action('support_tickets_notification_updated');
        }


        /**
         * Update notification count (optional - for AJAX updates)
         * @return array
         * @param array items (tabs of my account)
         * @author iranianclever
         * @since v1.0
         * @version v1.0
         */
        function add_support_tab_to_myaccount($items)
        {
            $unread_count = self::count_unread_tickets();
            $notification_text = '';

            if ($unread_count > 0) {
                // Change: only put the count inside a marked-up string (e.g., with a space)
                $notification_text = ' ( ' . $unread_count . ' )';
            }

            $new_items = array();
            $inserted = false;

            // Add support tab after orders or favorites
            foreach ($items as $key => $value) {
                $new_items[$key] = $value;

                if (!$inserted && ($key === 'orders' || $key === 'favorites')) {
                    $new_items['tickets'] = 'تیکت ها' . $notification_text;
                    $inserted = true;
                }
            }

            // Fallback: append at the end if no anchor was found
            if (!$inserted) {
                $new_items['tickets'] = 'تیکت ها' . $notification_text;
            }

            return $new_items;
        }


        /**
         * Add rewrite endpoints of tickets
         * @return void
         * @param void
         * @author iranianclever
         * @since v1.0
         * @version v1.0
         */
        function add_support_tickets_endpoint()
        {
            // New compact endpoint: /my-account/tickets/{ticket-id-or-new}/
            add_rewrite_endpoint('tickets', EP_ROOT | EP_PAGES);

            // Keep legacy endpoints registered so existing links continue to work.
            add_rewrite_endpoint('support-tickets', EP_ROOT | EP_PAGES);
            add_rewrite_endpoint('view-ticket', EP_ROOT | EP_PAGES);
            add_rewrite_endpoint('add-ticket', EP_ROOT | EP_PAGES);
        }


        /**
         * Set titles of endpoint tickets
         * @return string title of parts of ticket
         * @param void
         * @author iranianclever
         * @since v1.0
         * @version v1.0
         */
        function support_tickets_endpoint_title($title)
        {
            global $wp_query;

            if (isset($wp_query->query_vars['tickets']) && !is_admin()) {
                $endpoint_value = $wp_query->query_vars['tickets'];
                if ($endpoint_value === 'new') {
                    $title = 'ایجاد تیکت جدید';
                } elseif (is_string($endpoint_value) && ctype_digit($endpoint_value)) {
                    $title = 'مشاهده تیکت';
                } else {
                    $title = 'تیکت‌های پشتیبانی';
                }
            } elseif (isset($wp_query->query_vars['support-tickets']) && !is_admin()) {
                $title = 'تیکت‌های پشتیبانی';
            } elseif (isset($wp_query->query_vars['view-ticket']) && !is_admin()) {
                $title = 'مشاهده تیکت';
            } elseif (isset($wp_query->query_vars['add-ticket']) && !is_admin()) {
                $title = 'ایجاد تیکت جدید';
            }

            return $title;
        }


        /**
         * Set ticket content
         * @return void
         * @param void
         * @author iranianclever
         * @since v1.0
         * @version v1.0
         */
        function tickets_endpoint_content()
        {
            $endpoint_value = get_query_var('tickets', '');

            // Display a single ticket when the endpoint contains a numeric ticket ID.
            if (is_string($endpoint_value) && ctype_digit($endpoint_value)) {
                $ticket_id = absint($endpoint_value);
                if ($ticket_id > 0) {
                    $this->display_single_ticket($ticket_id);
                    return;
                }
            }

            // Display the new ticket form at /my-account/tickets/new/.
            if ($endpoint_value === 'new') {
                $this->display_new_ticket_form();
                return;
            }

            // Default: display the user's ticket list at /my-account/tickets/.
            $this->display_tickets_list();
        }


        /**
         * Set legacy ticket content
         * @return void
         * @param void
         * @author iranianclever
         * @since v1.0
         * @version v1.0
         */
        function support_tickets_content()
        {
            $current_endpoint = get_query_var('support-tickets', '');
            $view_ticket = get_query_var('view-ticket', '');
            $add_ticket = get_query_var('add-ticket', '');

            // Check view ticket endpoint
            if (!empty($view_ticket)) {
                $ticket_id = absint($view_ticket);
                if ($ticket_id > 0) {
                    $this->display_single_ticket($ticket_id);
                    return;
                }
            }

            // Check create ticket endpoint
            if (!empty($add_ticket)) {
                $this->display_new_ticket_form();
                return;
            }

            // Check legacy endpoint for compatibility
            if (!empty($current_endpoint)) {
                if (str_contains($current_endpoint, 'view-ticket/')) {
                    $ticket_exploded = explode('/', $current_endpoint);
                    $ticket_id = absint($ticket_exploded[1] ?? 0);
                    if ($ticket_id > 0) {
                        $this->display_single_ticket($ticket_id);
                        return;
                    }
                } elseif ($current_endpoint === 'add-ticket') {
                    $this->display_new_ticket_form();
                    return;
                }
            }

            // Default: display tickets list
            $this->display_tickets_list();
        }


        /**
         * Display tickets list
         * @return void
         * @param void
         * @author iranianclever
         * @since v1.0
         * @version v1.0
         */
        function display_tickets_list()
        {
            $user_id = get_current_user_id();

            $tickets = get_posts(array(
                'post_type' => 'support_ticket',
                'author' => $user_id,
                'numberposts' => -1,
                'orderby' => 'date',
                'order' => 'DESC',
            ));

            ?>
            <div class="irantheme-MyAccount-support">
                <div class="irantheme-MyAccount-support-heading">
                    <div class="irantheme-MyAccount-support-heading-title">
                        <h2 class="support-tickets-irantheme-title irantheme-myaccount-content-title p-0"><i class="fa-light fa-tickets"></i>تیکت های پشتیبانی</h2>
                        <!-- <p>مدیریت، پاسخ و ایجاد تیکت های ارسالی</p> -->
                    </div>
                    <div class="irantheme-MyAccount-support-heading-btn">
                        <a href="<?php echo esc_url($this->get_add_ticket_url()); ?>" class="irantheme-btn irantheme-btn-primary irantheme-btn-radius"><i class="iconsax" icon-name="add"></i>ایجاد تیکت جدید</a>
                    </div>
                </div>
                <?php if ($tickets) : ?>
                    <div class="irantheme-myaccount-orders-table-wrap">
                        <table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table irantheme-myaccount-orders-table irantheme-MyAccount-support-table">
                            <thead class="irantheme-myaccount-orders-table-radius">
                                <tr>
                                    <th>شماره تیکت</th>
                                    <th>موضوع</th>
                                    <th>اولویت</th>
                                    <th>وضعیت</th>
                                    <th>تاریخ ایجاد</th>
                                    <th>آخرین تغییر</th>
                                    <th>عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tickets as $ticket) :
                                    $status = wp_get_post_terms($ticket->ID, 'ticket_status', array('fields' => 'names'));
                                    $status_name = (!is_wp_error($status) && !empty($status)) ? $status[0] : 'باز';
                                    $status_class = $this->terms[$status_name] ?? sanitize_title($status_name);

                                    // Get ticket priority
                                    $priority = get_post_meta($ticket->ID, '_ticket_priority', true);
                                    $priority_text = $this->get_priority_text($priority);
                                    $priority_class = $this->get_priority_class($priority);
                                ?>
                                    <tr class="woocommerce-orders-table__row order irantheme-MyAccount-support-table-tr">
                                        <td class="ticket-number irantheme-MyAccount-support-table-td" data-title="شماره تیکت"><a href="<?php echo esc_url($this->get_view_ticket_url($ticket->ID)); ?>"><?php echo esc_html($ticket->ID); ?></a></td>
                                        <td class="ticket-subject irantheme-MyAccount-support-table-td" data-title="موضوع">
                                            <a href="<?php echo esc_url($this->get_view_ticket_url($ticket->ID)); ?>">
                                                <p><?php echo esc_html($ticket->post_title); ?></p>
                                            </a>
                                        </td>
                                        <td class="ticket-priority irantheme-MyAccount-support-table-td" data-title="اولویت">
                                            <span class="priority-badge priority-<?php echo esc_attr($priority_class); ?>">
                                                <?php echo esc_html($priority_text); ?>
                                            </span>
                                        </td>
                                        <td class="ticket-status irantheme-MyAccount-support-table-td" data-title="وضعیت"><span class="status-badge status-<?php echo esc_attr($status_class); ?>"><?php echo esc_html($status_name); ?></span></td>
                                        <td class="ticket-date irantheme-MyAccount-support-table-td" data-title="تاریخ"><?php echo get_the_date('Y/m/d H:i', $ticket->ID); ?></td>
                                        <td class="ticket-date irantheme-MyAccount-support-table-td" data-title="آخرین تغییر"><?php echo get_the_modified_date('Y/m/d H:i', $ticket->ID); ?></td>
                                        <td class="ticket-actions irantheme-MyAccount-support-table-td" data-title="عملیات">
                                            <a href="<?php echo esc_url($this->get_view_ticket_url($ticket->ID)); ?>" class="ticket-show view"><i class="fa-light fa-arrow-up-right-from-square"></i>مشاهده</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else : ?>
                    <div class="ticket-not-found irantheme-MyAccount-support-ticket-not-found">
                        <div class="ticket-not-found irantheme-MyAccount-support-ticket-not-found-wrapper">
                            <i class="fa-light fa-clipboard-list icon"></i>
                            <h5 class="title">هنوز هیچ تیکتی ایجاد نکرده‌اید.</h5>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        <?php
        }


        /**
         * Return priorities
         * @return string Return priority text
         * @param void
         * @author iranianclever
         * @since v1.0
         * @version v1.0
         */
        private function get_priority_text($priority)
        {
            $priorities = array(
                'low' => 'کم',
                'high' => 'زیاد',
                'urgent' => 'فوری',
            );

            return isset($priorities[$priority]) ? $priorities[$priority] : 'معمولی';
        }


        /**
         * Return priorities class
         * @return string Return priority text
         * @param string priority class
         * @author iranianclever
         * @since v1.0
         * @version v1.0
         */
        private function get_priority_class($priority)
        {
            $classes = array(
                'low' => 'low',
                'high' => 'high',
                'urgent' => 'urgent',
            );

            return isset($classes[$priority]) ? $classes[$priority] : 'normal';
        }


        /**
         * Create and display form ticket (new)
         * @return void
         * @param void
         * @author iranianclever
         * @since v1.0
         * @version v1.0
         */
        function display_new_ticket_form()
        {
            // Form submission is handled in process_ticket_forms_early()
        ?>
            <div class="irantheme-MyAccount-support">
                <div class="irantheme-MyAccount-support-ticket-register">
                    <div class="irantheme-MyAccount-support-ticket-register-heading">
                        <div class="irantheme-MyAccount-support-ticket-register-heading-title">
                            <h2 class="support-tickets-irantheme-title irantheme-myaccount-content-title p-0"><i class="fa-light fa-ticket"></i>ایجاد تیکت جدید</h2>
                            <p class="desc">ایجاد تیکت جهت ارتباط با پشتیبانی</p>
                        </div>
                        <div class="irantheme-MyAccount-support-ticket-register-heading-btn">
                            <a href="<?php echo esc_url($this->get_support_tickets_url()); ?>" class="irantheme-btn irantheme-btn-dark-outline irantheme-btn-medium irantheme-btn-radius"><i class="fa-light fa-arrow-left"></i>بازگشت</a>
                        </div>
                    </div>

                    <form method="post" class="woocommerce-EditAccountForm edit-account">
                        <?php wp_nonce_field('submit_ticket', 'ticket_nonce'); ?>

                        <p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
                            <label for="ticket_subject">موضوع تیکت <span class="required">*</span></label>
                            <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="ticket_subject" id="ticket_subject" value="<?php echo (isset($_POST['ticket_subject']) ? esc_attr(wp_unslash($_POST['ticket_subject'])) : ''); ?>" required style="width: 100%; padding: 10px;" />
                        </p>

                        <!-- Add priority field -->
                        <p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
                            <label for="ticket_priority">اولویت<span class="required">*</span></label>
                            <select class="woocommerce-Input woocommerce-Input--select input-select" name="ticket_priority" id="ticket_priority" style="width: 100%; padding: 10px;">
                                <option value="low" <?php echo (isset($_POST['ticket_priority']) && sanitize_key(wp_unslash($_POST['ticket_priority'])) == 'low') ? 'selected' : ''; ?>>کم</option>
                                <option value="normal" <?php echo (isset($_POST['ticket_priority']) && sanitize_key(wp_unslash($_POST['ticket_priority'])) == 'normal') ? 'selected' : ''; ?>>معمولی</option>
                                <option value="high" <?php echo (isset($_POST['ticket_priority']) && sanitize_key(wp_unslash($_POST['ticket_priority'])) == 'high') ? 'selected' : ''; ?>>زیاد</option>
                                <option value="urgent" <?php echo (isset($_POST['ticket_priority']) && sanitize_key(wp_unslash($_POST['ticket_priority'])) == 'urgent') ? 'selected' : ''; ?>>فوری</option>
                            </select>
                        </p>

                        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                            <label for="ticket_message">شرح مشکل یا سوال <span class="required">*</span></label>
                            <textarea class="woocommerce-Input woocommerce-Input--text input-text" name="ticket_message" id="ticket_message" rows="8" required style="width: 100%; padding: 10px;"><?php echo (isset($_POST['ticket_message']) ? esc_textarea(wp_unslash($_POST['ticket_message'])) : ''); ?></textarea>
                        </p>

                        <p>
                            <button type="submit" class="woocommerce-Button irantheme-btn irantheme-btn-medium irantheme-btn-radius irantheme-btn-primary" name="submit_ticket"><i class="fa-solid fa-paper-plane-top"></i>ارسال تیکت</button>
                        </p>

                    </form>
                </div>
            </div>
            <?php
        }


        /**
         * Process new ticket form submission.
         * Must be called before any output.
         * @return void
         * @param void
         * @author iranianclever
         * @since v1.0
         * @version v1.0
         */
        private function process_new_ticket_form()
        {
            // Verify nonce
            if (!isset($_POST['ticket_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['ticket_nonce'])), 'submit_ticket')) {
                return;
            }

            $user_id = get_current_user_id();
            $current_user = wp_get_current_user();
            $subject = isset($_POST['ticket_subject']) ? sanitize_text_field(wp_unslash($_POST['ticket_subject'])) : '';
            $message = isset($_POST['ticket_message']) ? sanitize_textarea_field(wp_unslash($_POST['ticket_message'])) : '';
            $priority = isset($_POST['ticket_priority']) ? sanitize_key(wp_unslash($_POST['ticket_priority'])) : '';

            // Validation
            if (empty($subject) || empty($message) || !in_array($priority, array('low', 'normal', 'high', 'urgent'), true)) {
                wc_add_notice('لطفاً تمام فیلدهای ضروری را پر کنید.', 'error');
                return;
            }

            $ticket_id = wp_insert_post(array(
                'post_title' => $subject,
                'post_content' => $message,
                'post_type' => 'support_ticket',
                'post_status' => 'publish',
                'post_author' => $user_id,
            ));

            if ($ticket_id && !is_wp_error($ticket_id)) {
                update_post_meta($ticket_id, '_ticket_user_id', $user_id);
                update_post_meta($ticket_id, '_ticket_user_email', $current_user->user_email);
                update_post_meta($ticket_id, '_ticket_priority', $priority);
                wp_set_object_terms($ticket_id, 'باز', 'ticket_status');

                wc_add_notice('تیکت شما با موفقیت ایجاد شد.', 'success');

                // Redirect to the view ticket page
                $redirect_url = $this->get_view_ticket_url($ticket_id);

                if (!headers_sent()) {
                    wp_safe_redirect($redirect_url);
                    exit;
                } else {
                    echo '<script>window.location.href = "' . esc_url($redirect_url) . '";</script>';
                    exit;
                }
            } else {
                wc_add_notice('خطا در ایجاد تیکت. لطفاً مجدد تلاش کنید.', 'error');
            }
        }


        /**
         * Display single ticket
         * @return void
         * @param int ticket id
         * @author iranianclever
         * @since v1.0
         * @version v1.0
         */
        function display_single_ticket($ticket_id)
        {
            $ticket = get_post($ticket_id);
            $user_id = get_current_user_id();

            // Check ticket ownership and post type
            if (!$ticket || $ticket->post_type !== 'support_ticket' || $ticket->post_author != $user_id) :
            ?>
                <div class="woocommerce-error">تیکت مورد نظر یافت نشد.</div>
            <?php
                return;
            endif;

            $status = wp_get_post_terms($ticket->ID, 'ticket_status', array('fields' => 'names'));
            $status_name = (!is_wp_error($status) && !empty($status)) ? $status[0] : 'باز';
            $status_class = $this->terms[$status_name] ?? 'open';
            $replies = $this->get_ticket_replies($ticket_id);

            // Get ticket priority
            $priority = get_post_meta($ticket_id, '_ticket_priority', true);
            $priority_text = $this->get_priority_text($priority);
            $priority_class = $this->get_priority_class($priority);

            ?>
            <div class="irantheme-MyAccount-support">
                <div class="irantheme-MyAccount-support-ticket-content">
                    <div class="irantheme-MyAccount-support-ticket-content-heading">
                        <div class="irantheme-MyAccount-support-ticket-content-heading-title-wrapper">
                            <div class="irantheme-MyAccount-support-ticket-content-heading-title">
                                <h3 class="irantheme-MyAccount-support-ticket-content-heading-title-subject" title="<?php echo esc_html($ticket->post_title); ?>"><?php echo esc_html($ticket->post_title); ?></h3>
                                <span class="irantheme-MyAccount-support-ticket-content-heading-title-id">
                                    <cite>شماره:</cite>
                                    <?php echo esc_html($ticket_id); ?>
                                </span>
                            </div>
                            <a href="<?php echo esc_url($this->get_support_tickets_url()); ?>" class="irantheme-btn irantheme-btn-dark-outline irantheme-btn-radius">بازگشت<i class="fa-light fa-arrow-left"></i></a>
                        </div>
                        <div class="irantheme-MyAccount-support-ticket-content-heading-meta-wrapper">
                            <div class="irantheme-MyAccount-support-ticket-content-heading-meta">
                                <span class="ticket-status status-<?php echo esc_attr(sanitize_title($status_class)); ?>" title="وضعیت"><?php echo esc_html($status_name); ?></span>
                                <span class="ticket-priority priority-<?php echo esc_attr($priority_class); ?>" title="اولویت"><?php echo esc_html($priority_text); ?></span>
                                <span class="ticket-date"><i class="fa-light fa-calendar-lines"></i>تاریخ ایجاد: <?php echo get_the_date('Y/m/d - H:i', $ticket->ID); ?></span>
                                <span class="ticket-date"><i class="fa-light fa-calendar-lines-pen"></i>آخرین تغییر: <?php echo get_the_modified_date('Y/m/d - H:i', $ticket->ID); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="irantheme-MyAccount-support-ticket-content-body">
                        <?php
                        $author = get_userdata($ticket->post_author);
                        ?>
                        <!-- Display ticket conversation -->
                        <div class="irantheme-MyAccount-support-ticket-content-body-conversations">
                            <?php
                            // Get admin user IDs
                            $admin_users = get_users(array(
                                'role' => 'administrator',
                                'fields' => 'ID'
                            ));

                            // Create conversation array
                            $conversation = array();

                            // Add original ticket
                            $conversation[] = array(
                                'content' => $ticket->post_content,
                                'author_id' => $ticket->post_author,
                                'date' => $ticket->post_date,
                                'type' => 'ticket',
                                'is_admin' => false
                            );

                            // Add replies
                            if ($replies) {
                                foreach ($replies as $reply) {
                                    $is_admin = in_array($reply->post_author, $admin_users);
                                    $conversation[] = array(
                                        'content' => $reply->post_content,
                                        'author_id' => $reply->post_author,
                                        'date' => $reply->post_date,
                                        'type' => 'reply',
                                        'is_admin' => $is_admin
                                    );
                                }
                            }

                            // Sort messages by date (oldest first)
                            usort($conversation, function ($a, $b) {
                                return strtotime($a['date']) - strtotime($b['date']);
                            });

                            // =============================================
                            // GROUP MESSAGES BY SENDER
                            // This creates groups of consecutive messages from the same sender
                            // =============================================
                            $grouped_conversation = array(); // Will hold all message groups
                            $current_group = array();        // Current group being built
                            $last_is_admin = null;           // Track previous message sender type
                            $last_date = null;               // Hold date of last message
                            foreach ($conversation as $index => $message) {
                                $current_is_admin = $message['is_admin'];
                                $current_date = date_i18n('Y-m-d', strtotime($message['date']));
                                // If sender changed OR date changed, start new group
                                if (($current_is_admin !== $last_is_admin || $current_date !== $last_date) && $last_is_admin !== null) {
                                    // Save the previous group
                                    $grouped_conversation[] = $current_group;
                                    // Start a new group
                                    $current_group = array();
                                }

                                // Add current message to current group
                                $current_group[] = $message;
                                $last_is_admin = $current_is_admin;
                                $last_date = $current_date;
                            }

                            // Add last group
                            if (!empty($current_group)) {
                                $grouped_conversation[] = $current_group;
                            }

                            // =============================================
                            // DISPLAY GROUPED MESSAGES
                            // Each group is displayed with proper styling
                            // =============================================

                            // Variable to track last displayed date
                            $last_displayed_date = '';

                            foreach ($grouped_conversation as $group_index => $group) :
                                $group_count = count($group); // Number of messages in this group
                                $is_admin_group = $group[0]['is_admin']; // Sender type of this group

                                // Get date for this group (from first message)
                                $group_date = date_i18n('Y-m-d', strtotime($group[0]['date']));

                                // =============================================
                                // DATE SEPARATOR - Show when date changes
                                // Like WhatsApp shows date above first message of each day
                                // =============================================
                                if ($group_date !== $last_displayed_date) {
                                    // Format date for display
                                    $today = date_i18n('Y-m-d');
                                    $yesterday = date_i18n('Y-m-d', strtotime('-1 day'));

                                    if ($group_date === $today) {
                                        $date_label = 'امروز';
                                    } elseif ($group_date === $yesterday) {
                                        $date_label = 'دیروز';
                                    } else {
                                        $date_label = date_i18n('j F Y', strtotime($group[0]['date']));
                                    }
                            ?>
                                    <div class="chat-date-divider">
                                        <span class="chat-date-divider-text"><?php echo esc_html($date_label); ?></span>
                                    </div>
                                <?php
                                    $last_displayed_date = $group_date;
                                }
                                ?>

                                <div class="irantheme-MyAccount-support-ticket-content-body-conversations-message-group">
                                    <?php
                                    foreach ($group as $msg_index => $message) :
                                        $author = get_userdata($message['author_id']);
                                        $message_class = $is_admin_group ? 'admin-message' : 'user-message';
                                        $message_title = $is_admin_group ? 'پشتیبانی' : 'شما';

                                        // Skip if author no longer exists
                                        if (!$author) {
                                            continue;
                                        }

                                        // =========================================
                                        // POSITION CLASSES
                                        // These classes help style message corners
                                        // =========================================

                                        $position_class = '';
                                        if (count($group) === 1) {
                                            $position_class = ' alone-in-group';     // Alone messages
                                        } else {
                                            if ($msg_index === 0) {
                                                $position_class = ' first-in-group';      // First message in group
                                            } elseif ($msg_index === $group_count - 1) {
                                                $position_class = ' last-in-group';       // Last message in group
                                            } else {
                                                $position_class = ' middle-in-group';     // Middle messages
                                            }
                                        }

                                        // =========================================
                                        // AVATAR CLASS
                                        // Only the last message in group shows avatar
                                        // =========================================

                                        $avatar_class = ($msg_index === $group_count - 1) ? ' has-avatar' : '';

                                        // =========================================
                                        // NAME CLASS
                                        // Only the first message in group shows name
                                        // =========================================

                                        $name_class = ($msg_index === 0) ? ' show-name' : ' hide-name';
                                    ?>
                                        <div class="irantheme-MyAccount-support-ticket-content-body-conversations-message <?php echo esc_attr($message_class . $position_class . $avatar_class . $name_class); ?>">

                                            <?php if ($msg_index === $group_count - 1): ?>
                                                <!-- Avatar for last message in group -->
                                                <div class="message-avatar <?php echo $message['is_admin'] ? 'admin-avatar' : 'user-avatar'; ?>">
                                                    <?php if ($message['is_admin']) : ?>
                                                        <i class="fa-light fa-user-headset"></i>
                                                    <?php else : ?>
                                                        <i class="fa-light fa-user"></i>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>

                                            <div class="message-bubble">

                                                <?php if ($msg_index === 0): ?>
                                                    <!-- Message header only for first message in group -->
                                                    <div class="message-heading">
                                                        <strong class="message-heading-username">
                                                            <?php echo esc_html($message_title); ?> (<?php echo esc_html($author->display_name); ?>)
                                                        </strong>
                                                    </div>
                                                <?php endif; ?>
                                                <!-- Message content -->
                                                <div class="message-text">
                                                    <?php echo wpautop(wp_kses_post($message['content'])); ?>
                                                </div>

                                                <!-- Time for last message in group (optional) -->
                                                <div class="message-time">
                                                    <i class="fa-light fa-clock icon"></i>
                                                    <span class="time">
                                                        <?php echo date_i18n('H:i', strtotime($message['date'])); ?>
                                                    </span>
                                                </div>

                                            </div>
                                        </div>
                                    <?php
                                    endforeach;
                                    ?>
                                </div>
                            <?php
                            endforeach;
                            ?>
                        </div>

                        <!-- Display reply form if ticket is not closed -->
                        <?php if ($status_name != 'بسته') : ?>
                            <?php $this->display_user_reply_form($ticket_id); ?>
                        <?php else : ?>
                            <div class="irantheme-MyAccount-support-ticket-content-body-ticket-closed">
                                <div class="irantheme-MyAccount-support-ticket-content-body-ticket-closed-wrapper">
                                    <i class="fa-regular fa-do-not-enter icon"></i>
                                    <p class="title">
                                        این تیکت بسته شده و امکان ارسال پاسخ جدید وجود ندارد.
                                    </p>
                                    <p class="desc">
                                        در صورت نیاز می‌توانید یک تیکت جدید ایجاد کنید.
                                    </p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php
        }


        /**
         * Display user reply form
         * Uses a unique nonce name (ticket_reply_nonce) and action (submit_ticket_reply)
         * to avoid conflicts with the product Q&A reply system.
         * @return void
         * @param int ticket id
         * @author iranianclever
         * @since v1.0
         * @version v1.1
         */
        function display_user_reply_form($ticket_id)
        {
            // Form submission is handled in process_ticket_forms_early()
        ?>
            <div class="irantheme-MyAccount-support-ticket-content-body-reply">
                <form method="post" class="irantheme-MyAccount-support-ticket-content-body-reply-form">
                    <?php wp_nonce_field('submit_ticket_reply', 'ticket_reply_nonce'); ?>

                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <textarea class="woocommerce-Input woocommerce-Input--text input-text irantheme-MyAccount-support-ticket-content-body-reply-form-input" name="user_reply_message" id="user_reply_message" placeholder="پیام خود را بنویسید..." rows="5" required></textarea>
                    </p>

                    <div class="irantheme-MyAccount-support-ticket-content-body-reply-form-actions">
                        <button type="submit" class="irantheme-MyAccount-support-ticket-content-body-reply-form-actions-submit irantheme-btn irantheme-btn-primary" name="submit_ticket_reply"><i class="fa-solid fa-paper-plane-top"></i>ارسال</button>
                    </div>

                </form>
            </div>
            <?php
        }


        /**
         * Process user reply
         * Must be called before any output.
         * Uses unique nonce and action names to avoid conflicts with product Q&A.
         * @return bool
         * @param int ticket id
         * @author iranianclever
         * @since v1.0
         * @version v1.1
         */
        function handle_user_reply_submission($ticket_id)
        {
            // This function must be called before ANY output
            // Unique submit button name for the ticket system
            if (!isset($_POST['submit_ticket_reply'])) {
                return false;
            }

            // Unique nonce name and action for the ticket system
            if (!isset($_POST['ticket_reply_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['ticket_reply_nonce'])), 'submit_ticket_reply')) {
                wc_add_notice('درخواست نامعتبر است. صفحه را تازه‌سازی کرده و دوباره تلاش کنید.', 'error');
                return false;
            }

            if (!is_user_logged_in()) {
                wc_add_notice('برای ارسال پاسخ باید وارد حساب کاربری خود شوید.', 'error');
                return false;
            }

            $user_id = get_current_user_id();
            $ticket = get_post($ticket_id);

            // Check ticket ownership and post type
            if (!$ticket || $ticket->post_type !== 'support_ticket' || $ticket->post_author != $user_id) {
                wc_add_notice('شما دسترسی به این تیکت ندارید.', 'error');
                return false;
            }

            // Check ticket status
            $status = wp_get_post_terms($ticket_id, 'ticket_status', array('fields' => 'names'));
            $status_name = (!is_wp_error($status) && !empty($status)) ? $status[0] : 'باز';
            $allowed_statuses = array('باز', 'در حال بررسی', 'پاسخ داده شده');

            if (!in_array($status_name, $allowed_statuses)) {
                wc_add_notice('این تیکت بسته شده و امکان ارسال پاسخ وجود ندارد.', 'error');
                return false;
            }

            $reply_content = isset($_POST['user_reply_message']) ? sanitize_textarea_field(wp_unslash($_POST['user_reply_message'])) : '';

            if (empty($reply_content)) {
                wc_add_notice('لطفاً متن پاسخ را وارد کنید.', 'error');
                return false;
            }

            // Create reply post
            $reply_id = wp_insert_post(array(
                'post_type' => 'ticket_reply',
                'post_content' => $reply_content,
                'post_status' => 'publish',
                'post_author' => $user_id,
            ));

            if ($reply_id && !is_wp_error($reply_id)) {
                // Link reply to ticket
                update_post_meta($reply_id, '_ticket_id', $ticket_id);

                // Change ticket status to "در حال بررسی"
                wp_set_object_terms($ticket_id, 'در حال بررسی', 'ticket_status');

                // Send email to admin
                $this->send_user_reply_notification_email($ticket_id, $reply_content);

                wc_add_notice('پاسخ شما با موفقیت ارسال شد.', 'success');

                // Redirect before ANY output
                $redirect_url = $this->get_view_ticket_url($ticket_id);

                if (!headers_sent()) {
                    wp_safe_redirect($redirect_url);
                    exit;
                } else {
                    echo '<script>window.location.href = "' . esc_url($redirect_url) . '";</script>';
                    exit;
                }
            } else {
                wc_add_notice('خطا در ارسال پاسخ. لطفاً مجدد تلاش کنید.', 'error');
                return false;
            }

            return true;
        }


        /**
         * Helper function to get the endpoint URL
         * @return string endpoint url
         * @param void
         * @author iranianclever
         * @since v1.0
         * @version v1.0
         */
        private function get_support_tickets_url()
        {
            return wc_get_endpoint_url('tickets', '', wc_get_page_permalink('myaccount'));
        }


        /**
         * Return view ticket url
         * @return string endpoint url
         * @param void
         * @author iranianclever
         * @since v1.0
         * @version v1.0
         */
        private function get_view_ticket_url($ticket_id)
        {
            return wc_get_endpoint_url('tickets', absint($ticket_id), wc_get_page_permalink('myaccount'));
        }


        /**
         * Return add ticket url
         * @return string endpoint url
         * @param void
         * @author iranianclever
         * @since v1.0
         * @version v1.0
         */
        private function get_add_ticket_url()
        {
            return wc_get_endpoint_url('tickets', 'new', wc_get_page_permalink('myaccount'));
        }


        /**
         * Send email to admin when user replies
         * @return string endpoint url
         * @param int ticket id
         * @param string reply content
         * @author iranianclever
         * @since v1.0
         * @version v1.0
         */
        function send_user_reply_notification_email($ticket_id, $reply_content)
        {
            $ticket = get_post($ticket_id);
            if (!$ticket) {
                return;
            }

            $user = get_userdata($ticket->post_author);

            if (!$user) {
                return;
            }

            $subject = sanitize_text_field('پاسخ جدید به تیکت: ' . $ticket->post_title);
            $admin_email = sanitize_email(get_option('admin_email', ''));
            $ticket_url = admin_url('post.php?post=' . $ticket_id . '&action=edit');

            $safe_ticket_title = esc_html($ticket->post_title);
            $safe_reply_content = wpautop(wp_kses_post($reply_content));
            $safe_user_name = esc_html($user->display_name);
            $safe_user_email = esc_html($user->user_email);
            $ticket_url = esc_url($ticket_url);

            $message = "
        <div style='font-family: Tahoma, sans-serif; direction: rtl; text-align: right;'>
            <h2 style='color: #333;'>پاسخ جدید از کاربر</h2>
            <p>تیکت با موضوع &quot;<strong>{$safe_ticket_title}</strong>&quot; پاسخ جدید دریافت کرد.</p>
            <div style='background: #f8f9fa; padding: 15px; border-right: 4px solid #28a745; margin: 15px 0;'>
                <h4 style='margin-top: 0;'>پاسخ کاربر:</h4>
                {$safe_reply_content}
            </div>
            <div style='background: #fff3cd; padding: 10px; border-radius: 3px; margin: 15px 0;'>
                <strong>اطلاعات کاربر:</strong><br>
                نام: {$safe_user_name}<br>
                ایمیل: {$safe_user_email}
            </div>
            <p>برای مشاهده و پاسخ به تیکت، روی لینک زیر کلیک کنید:</p>
            <p><a href='{$ticket_url}' style='background: #007cba; color: white; padding: 10px 20px; text-decoration: none; border-radius: 3px; display: inline-block;'>مدیریت تیکت</a></p>
            <hr style='border: none; border-top: 1px solid #eee; margin: 20px 0;'>
            <p style='color: #666; font-size: 12px;'>این ایمیل به صورت خودکار ارسال شده است.</p>
        </div>
    ";

            $headers = array('Content-Type: text/html; charset=UTF-8');

            // Send email to admin
            if (is_email($admin_email)) {
                wp_mail($admin_email, $subject, $message, $headers);
            }

            // Send email to additional admins if exists
            $additional_emails = $this->get_additional_admin_emails();
            foreach ($additional_emails as $email) {
                wp_mail($email, $subject, $message, $headers);
            }
        }


        /**
         * Get additional admin emails
         * @return array emails
         * @param void
         * @author iranianclever
         * @since v1.0
         * @version v1.0
         */
        function get_additional_admin_emails()
        {
            $emails = array();

            // You can get additional emails from WordPress settings or options
            $additional_emails = get_option('support_ticket_additional_emails', '');
            if (!empty($additional_emails)) {
                $configured_emails = array_map('trim', explode(',', $additional_emails));
                foreach ($configured_emails as $email) {
                    $email = sanitize_email($email);
                    if (is_email($email)) {
                        $emails[] = $email;
                    }
                }
            }

            $emails = array_unique($emails);
            $primary_email = sanitize_email(get_option('admin_email', ''));
            if ($primary_email) {
                $emails = array_diff($emails, array($primary_email));
            }

            return array_values($emails);
        }


        /**
         * Add ticket metabox
         * @return void
         * @param void
         * @author iranianclever
         * @since v1.0
         * @version v1.0
         */
        function add_ticket_meta_boxes()
        {
            add_meta_box(
                'ticket_user_info',
                'اطلاعات کاربر',
                array($this, 'display_ticket_user_meta_box'),
                'support_ticket',
                'side',
                'high'
            );
        }


        /**
         * Display meta box of ticekt user
         * @return void
         * @param mixed post mixed
         * @author iranianclever
         * @since v1.0
         * @version v1.0
         */
        function display_ticket_user_meta_box($post)
        {
            $user_id = get_post_meta($post->ID, '_ticket_user_id', true);
            $priority = get_post_meta($post->ID, '_ticket_priority', true);
            $priority_text = $this->get_priority_text($priority);

            if ($user_id) :
                $user = get_userdata($user_id);
                if ($user) :
            ?>
                    <p><strong>نام کاربر:</strong> <?php echo esc_html($user->display_name); ?></p>
                    <p><strong>ایمیل:</strong> <?php echo esc_html($user->user_email); ?></p>
                    <p><strong>شناسه کاربر:</strong> <?php echo esc_html($user_id); ?></p>
                    <p><strong>اولویت:</strong> <span class="priority-badge priority-<?php echo esc_attr($this->get_priority_class($priority)); ?>"><?php echo esc_html($priority_text); ?></span></p>
                <?php else : ?>
                    <p>کاربر حذف شده است.</p>
                <?php endif;
            else : ?>
                <p>اطلاعات کاربر یافت نشد.</p>
<?php endif;
        }


        /**
         * Flush rewrite rules on activation
         * @return void
         * @param void
         * @author iranianclever
         * @since v1.0
         * @version v1.0
         */
        function flush_rewrite_rules_on_activation()
        {
            $this->add_support_tickets_endpoint();
            flush_rewrite_rules();
        }
    }
    new Irantheme_Woocommerce_Support_Ticket();
}
