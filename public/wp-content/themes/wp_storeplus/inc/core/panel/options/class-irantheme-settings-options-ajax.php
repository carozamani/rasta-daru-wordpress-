<?php

namespace Irantheme_Dev;

use WP_Query;

if (!defined('ABSPATH')) exit;

/**
 * The Storeplus Theme Settings Options Ajax class
 * A Customizable Class and Settings Ajax
 * 
 * @package Wordpress
 * @subpackage Storeplus
 * @author iranianclever
 * @since v1.0
 * @see v1.0
 * 
 * This class is a source for receive and send ajax request
 */
if (!class_exists('Irantheme_Settings_Options_Ajax')) {
    class Irantheme_Settings_Options_Ajax
    {
        // Constructor initialize
        public function __construct()
        {
            // Add action ajax to newsletter get mail
            add_action('wp_ajax_newsletter_get_mail', array($this, 'newsletter_get_mail'));
            add_action('wp_ajax_nopriv_newsletter_get_mail', array($this, 'newsletter_get_mail'));

            // Add action ajax to newsletter send mail
            add_action('wp_ajax_newsletter_send_mail', array($this, 'newsletter_send_mail'));
            add_action('wp_ajax_nopriv_newsletter_send_mail', array($this, 'newsletter_send_mail'));

            // Add action ajax to otp send sms
            add_action('wp_ajax_irantheme_ajax_send_test_sms', array($this, 'irantheme_ajax_send_test_sms'));
            add_action('wp_ajax_nopriv_irantheme_ajax_send_test_sms', array($this, 'irantheme_ajax_send_test_sms'));

            add_action('wp_ajax_irantheme_ajax_request_otp_woo', array($this, 'irantheme_ajax_request_otp_woo'));
            add_action('wp_ajax_nopriv_irantheme_ajax_request_otp_woo', array($this, 'irantheme_ajax_request_otp_woo'));

            add_action('wp_ajax_irantheme_ajax_verify_otp_woo', array($this, 'irantheme_ajax_verify_otp_woo'));
            add_action('wp_ajax_nopriv_irantheme_ajax_verify_otp_woo', array($this, 'irantheme_ajax_verify_otp_woo'));

            add_action('wp_ajax_irantheme_reset_otp_restrictions',  array($this, 'irantheme_reset_otp_restrictions'));
            add_action('wp_ajax_nopriv_irantheme_reset_otp_restrictions',  array($this, 'irantheme_reset_otp_restrictions'));

            // Add action ajax to cart items navigation
            add_action('wp_ajax_cart_list_items_navigation', array($this, 'cart_list_items_navigation'));
            add_action('wp_ajax_nopriv_cart_list_items_navigation', array($this, 'cart_list_items_navigation'));

            // Add quantity update actions
            add_action('wp_ajax_update_cart_quantity', array($this, 'update_cart_quantity'));
            add_action('wp_ajax_nopriv_update_cart_quantity', array($this, 'update_cart_quantity'));

            // Add action ajax to wishlist items navigation
            add_action('wp_ajax_wishlist_list_items_navigation', array($this, 'wishlist_list_items_navigation'));
            add_action('wp_ajax_nopriv_wishlist_list_items_navigation', array($this, 'wishlist_list_items_navigation'));

            // Add action ajax to wishlist items wc favorites
            add_action('wp_ajax_wishlist_list_items_wc_favorites', array($this, 'wishlist_list_items_wc_favorites'));
            add_action('wp_ajax_nopriv_wishlist_list_items_wc_favorites', array($this, 'wishlist_list_items_wc_favorites'));

            // Add action ajax to remove item form cart items in navigation
            add_action('wp_ajax_remove_item_from_cart', array($this, 'remove_item_from_cart'));
            add_action('wp_ajax_nopriv_remove_item_from_cart', array($this, 'remove_item_from_cart'));

            // Add action ajax to fetch count items form cart items in navigation
            add_action('wp_ajax_fetch_count_items_from_cart', array($this, 'fetch_count_items_from_cart'));
            add_action('wp_ajax_nopriv_fetch_count_items_from_cart', array($this, 'fetch_count_items_from_cart'));

            // Add action ajax to fetch count items form cart items in navigation
            add_action('wp_ajax_product_fast_access', array($this, 'product_fast_access'));
            add_action('wp_ajax_nopriv_product_fast_access', array($this, 'product_fast_access'));

            // Add action ajax to archive product
            add_action('wp_ajax_apply_filter_to_archive_product', array($this, 'apply_filter_to_archive_product'));
            add_action('wp_ajax_nopriv_apply_filter_to_archive_product', array($this, 'apply_filter_to_archive_product'));

            // Add action ajax to archive product
            add_action('wp_ajax_get_all_product_attributes', array($this, 'get_all_product_attributes'));
            add_action('wp_ajax_nopriv_get_all_product_attributes', array($this, 'get_all_product_attributes'));

            // Add action ajax to gallery comment (submit)
            add_action('wp_ajax_submit_gallery_comment', array($this, 'submit_gallery_comment'));
            add_action('wp_ajax_nopriv_submit_gallery_comment', array($this, 'submit_gallery_comment'));

            // Add action ajax to gallery comment (fetch)
            add_action('wp_ajax_get_gallery_comments', array($this, 'get_gallery_comments'));
            add_action('wp_ajax_nopriv_get_gallery_comments', array($this, 'get_gallery_comments'));
        }


        /**
         * AJAX handler for submitting gallery comments
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public function submit_gallery_comment()
        {
            // Verify nonce (wp_verify_nonce خودش slashes رو مدیریت می‌کنه، unslash لازم نیست)
            if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'irantheme_gallery_nonce')) {
                wp_send_json_error(['message' => 'Invalid security token']);
                return;
            }

            // Check if user is logged in
            if (!is_user_logged_in()) {
                wp_send_json_error(['message' => 'You must be logged in to comment']);
                return;
            }

            // sanitize_text_field خودش wp_unslash رو صدا می‌زنه
            $object_id = isset($_POST['object_id']) ? sanitize_text_field($_POST['object_id']) : '';
            $comment_text = isset($_POST['comment']) ? sanitize_textarea_field($_POST['comment']) : '';
            $user_id = get_current_user_id();

            if (empty($object_id) || empty($comment_text)) {
                wp_send_json_error(['message' => 'Missing required fields']);
                return;
            }

            // Create the comment post
            $comment_post = array(
                'post_type' => 'gallery_comment',
                'post_status' => 'publish',
                'post_title' => sprintf('نظر از کاربر %s', get_userdata($user_id)->display_name),
                'post_content' => $comment_text,
                'post_author' => $user_id,
                'meta_input' => array(
                    'irantheme_woocommerce_gallery_social_meta_value_key' => $object_id,
                    'irantheme_woocommerce_gallery_social_meta_value_user_id' => $user_id,
                    'irantheme_woocommerce_gallery_social_meta_value_comment' => $comment_text,
                    'irantheme_woocommerce_gallery_social_meta_value_parent_comment' => '',
                )
            );

            $comment_id = wp_insert_post($comment_post);

            if (is_wp_error($comment_id)) {
                wp_send_json_error(['message' => 'Failed to save comment']);
                return;
            }

            // Get updated comment count
            $count_args = array(
                'post_type' => 'gallery_comment',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'meta_query' => array(
                    array(
                        'key' => 'irantheme_woocommerce_gallery_social_meta_value_key',
                        'value' => $object_id,
                        'compare' => '='
                    )
                )
            );
            $count_query = new WP_Query($count_args);

            $user_info = get_userdata($user_id);
            wp_send_json_success(array(
                'id' => $comment_id,
                'user_name' => $user_info ? $user_info->display_name : 'کاربر',
                'comment' => $comment_text,
                'avatar' => get_avatar_url($user_id),
                'count' => $count_query->found_posts,
            ));
        }


        /**
         * AJAX handler for getting gallery comments
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public function get_gallery_comments()
        {
            // sanitize_text_field خودش unslash می‌زنه
            $object_id = isset($_GET['object_id']) ? sanitize_text_field($_GET['object_id']) : '';

            if (empty($object_id)) {
                wp_send_json_error(['message' => 'Missing object ID']);
                return;
            }

            $args = array(
                'post_type' => 'gallery_comment',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'meta_query' => array(
                    array(
                        'key' => 'irantheme_woocommerce_gallery_social_meta_value_key',
                        'value' => $object_id,
                        'compare' => '='
                    )
                ),
                'orderby' => 'date',
                'order' => 'DESC'
            );

            $query = new WP_Query($args);
            $html = '';

            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    $user_id = get_post_meta(get_the_ID(), 'irantheme_woocommerce_gallery_social_meta_value_user_id', true);
                    $comment_text = get_post_meta(get_the_ID(), 'irantheme_woocommerce_gallery_social_meta_value_comment', true);
                    $user_info = get_userdata($user_id);
                    $user_name = $user_info ? $user_info->display_name : __('کاربر ناشناس', IRANTHEME_TEXTDOMAIN);
                    $avatar_url = $user_info ? get_avatar_url($user_id) : IRANTHEME_URI . '/assets/images/user.webp';

                    $html .= sprintf(
                        '<div class="irantheme-gallery-social-fixed-comments-review" data-comment-id="%d">
                            <div class="irantheme-gallery-social-fixed-comments-review-avatar">
                                <img src="%s" alt="%s avatar" width="48" height="48">
                            </div>
                            <div class="irantheme-gallery-social-fixed-comments-review-content">
                                <strong class="irantheme-gallery-social-fixed-comments-review-content-name">%s</strong>
                                <p class="irantheme-gallery-social-fixed-comments-review-content-comment">%s</p>
                            </div>
                        </div>',
                        get_the_ID(),
                        esc_url($avatar_url),
                        esc_attr($user_name),
                        esc_html($user_name),
                        esc_html($comment_text)
                    );
                }
                wp_reset_postdata();
            }

            wp_send_json_success(array(
                'html' => $html,
                'count' => $query->found_posts
            ));
        }


        /**
         * ...
         * @param bool
         * @return void
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        public static function apply_filter_to_archive_product()
        {

            global $product;

            $product_flag = false;
            // Get all woocommerce products
            $product_args = array(
                'posts_per_page'   => -1,
                'post_type'        => 'product',
            );
            $wc_all_products = new WP_Query($product_args);
            // Check woocommerce have product
            if ($wc_all_products->have_posts()) {
                $product_flag = true;
            }
            // Get data from ajax request
            $is_ajax = isset($_POST['is_ajax']) && $_POST['is_ajax'] == 1 ? true : false;
            if ($is_ajax) {
                // sanitize_text_field خودش unslash می‌زنه
                $product_cat = isset($_POST['product_cat']) ? sanitize_text_field($_POST['product_cat']) : '';
                // داده آرایه‌ایه، پس wp_unslash لازمه (sanitize_text_field روی آرایه کار نمی‌کنه)
                $dynamic_data_filter = isset($_POST['data_filter']) ? wp_unslash((array) $_POST['data_filter']) : array();
                $in_stock = isset($_POST['in_stock']) ? sanitize_text_field($_POST['in_stock']) : '';
                $on_sale = isset($_POST['on_sale']) ? sanitize_text_field($_POST['on_sale']) : '';
                $price_from = isset($_POST['price_from']) ? sanitize_text_field($_POST['price_from']) : '';
                $price_to = isset($_POST['price_to']) ? sanitize_text_field($_POST['price_to']) : '';
                // absint خودش با slashes کار می‌کنه، unslash لازم نیست
                $page_number = isset($_POST['page_number']) ? absint($_POST['page_number']) : 1;
                $ordering_type = isset($_POST['ordering_type']) ? sanitize_text_field($_POST['ordering_type']) : '';
                $search_keyword = isset($_POST['search_keyword']) ? sanitize_text_field($_POST['search_keyword']) : '';
            } else {
                $product_cat = isset($_GET['product_cat']) ? sanitize_text_field($_GET['product_cat']) : '';
                if (!$product_cat) {
                    $product_cat = basename(esc_url_raw($_SERVER['REQUEST_URI']));
                }
                // داده آرایه‌ایه، wp_unslash لازمه
                $dynamic_data_filter = isset($_GET['data_filter']) ? wp_unslash((array) $_GET['data_filter']) : array();
                $in_stock = isset($_GET['in_stock']) ? sanitize_text_field($_GET['in_stock']) : '';
                $on_sale = isset($_GET['on_sale']) ? sanitize_text_field($_GET['on_sale']) : '';
                $price_from = isset($_GET['price_from']) ? sanitize_text_field($_GET['price_from']) : '';
                $price_to = isset($_GET['price_to']) ? sanitize_text_field($_GET['price_to']) : '';
                $page_number = isset($_GET['page_number']) ? absint($_GET['page_number']) : 1;
                $ordering_type = isset($_GET['ordering_type']) ? sanitize_text_field($_GET['ordering_type']) : '';
                $search_keyword = '';
            }

            $current_page = $page_number;
            // Get base color --------------------------------------------------------------
            $base_color = get_option('irantheme_wc_archive_base_color_select');

            // Get col count of product
            $col_count = get_option('irantheme_wc_archive_col_count_radio');
            $col_count_num = 4;
            $col_count_class = 'irantheme-wc-archive-grid-four-col';
            if ($col_count == 'col-3') {
                $col_count_num = 3;
                $col_count_class = 'irantheme-wc-archive-grid-three-col';
            } elseif ($col_count == 'col-4') {
                $col_count_num = 4;
                $col_count_class = 'irantheme-wc-archive-grid-four-col';
            } elseif ($col_count == 'col-5') {
                $col_count_num = 5;
                $col_count_class = 'irantheme-wc-archive-grid-five-col';
            } elseif ($col_count == 'col-6') {
                $col_count_num = 6;
                $col_count_class = 'irantheme-wc-archive-grid-six-col';
            } elseif ($col_count == 'col-7') {
                $col_count_num = 7;
                $col_count_class = 'irantheme-wc-archive-grid-seven-col';
            } elseif ($col_count == 'col-8') {
                $col_count_num = 8;
                $col_count_class = 'irantheme-wc-archive-grid-eight-col';
            }

            // Get post per page number
            $post_per_page = get_option('irantheme_wc_archive_product_num');

            // Details color --------------------------------------------------------------
            $details_color_select = get_option('irantheme_wc_archive_details_color_select');
            $details_color_select2 = get_option('irantheme_wc_archive_details_color_select2');

            // Get product feature --------------------------------------------------------------
            $product_features = get_option('irantheme_wc_archive_features_checkbox_multi');
            $product_features = explode(',', $product_features);

            // Get product discount checkbox --------------------------------------------------------------
            $product_discount_checkbox = get_option('irantheme_wc_archive_discount_checkbox');

            // Get product total sale checkbox --------------------------------------------------------------
            $product_total_sale_checkbox = get_option('irantheme_wc_archive_total_sales_checkbox');

            // Get product color attribute checkbox --------------------------------------------------------------
            $product_color_attribute_checkbox = get_option('irantheme_wc_archive_color_attribute_checkbox');

            // Get product options checkbox --------------------------------------------------------------
            $product_extra_options_checkbox = get_option('irantheme_wc_archive_extra_options_checkbox');
            $product_extra_options_style_radio =  get_option('irantheme_wc_archive_extra_option_on_image_style_radio');
            $extra_options_hover_checkbox =  get_option('irantheme_wc_archive_extra_options_hover_checkbox');

            $product_image_options_class = '';
            $product_image_extra_options_style_class = '';
            if ($product_extra_options_checkbox == 'on') {
                $product_image_options_class = 'active';
                switch ($product_extra_options_style_radio) {
                    case 'vertical-left':
                        $product_image_extra_options_style_class = 'irantheme-extra-option-vertical-left';
                        break;
                    case 'horizontal-center':
                        $product_image_extra_options_style_class = 'irantheme-extra-option-horizontal-center';
                        break;
                    case 'horizontal-bottom':
                        $product_image_extra_options_style_class = 'irantheme-extra-option-horizontal-bottom';
                        break;
                }
                if ($extra_options_hover_checkbox == 'on') {
                    $product_image_extra_options_style_class .= ' hover-active';
                }
            }

            // Get product content alignment --------------------------------------------------------------
            $post_content_align = get_option('irantheme_wc_archive_align_content_radio');

            // Get product count of word excerpt --------------------------------------------------------------
            $product_excerpt_count = get_option('irantheme_wc_archive_excerpt_count_number');

            // Get product discount schedule checkbox --------------------------------------------------------------
            $product_discount_schedule_checkbox = get_option('irantheme_wc_archive_discount_schedule_checkbox');

            // Get product hover mode --------------------------------------------------------------
            $product_hover_mode = get_option('irantheme_wc_archive_hover_mode_radio');

            $product_hover_mode_class = '';
            if ($product_hover_mode == 'shadow') {
                $product_hover_mode_class = 'irantheme-woocommerce-products-hover-shadow';
            } elseif ($product_hover_mode == 'scale') {
                $product_hover_mode_class = 'irantheme-woocommerce-products-hover-scale';
            }

            // Get image switchable thumbnail with gallery image --------------------------------------------------------------
            $product_image_hover_changer = get_option('irantheme_wc_archive_image_changer_checkbox');

            $product_image_hover_changer_class = '';
            if ($product_image_hover_changer == 'on') {
                $product_image_hover_changer_class = 'irantheme-woocommerce-products-item-image-changer';
            }

            // Get product image hover mode --------------------------------------------------------------
            $product_image_hover_mode = get_option('irantheme_wc_archive_image_hover_mode_radio');

            $product_image_hover_mode_class = '';
            if ($product_image_hover_mode == 'flashing') {
                $product_image_hover_mode_class = 'irantheme-image-hover-flashing';
            } elseif ($product_image_hover_mode == 'shine') {
                $product_image_hover_mode_class = 'irantheme-image-hover-shine';
            } elseif ($product_image_hover_mode == 'circle') {
                $product_image_hover_mode_class = 'irantheme-image-hover-circle';
            }

            // Get product border checkbox --------------------------------------------------------------
            $product_border_checkbox = get_option('irantheme_wc_archive_border_checkbox');

            $product_border_class = '';
            if ($product_border_checkbox == 'on') {
                $product_border_class = 'irantheme-woocommerce-products-item-border';
            }

            // Get product shadow class --------------------------------------------------------------
            $product_shadow_checkbox = get_option('irantheme_wc_archive_shadow_checkbox');

            $product_shadow_class = '';
            if ($product_shadow_checkbox == 'on') {
                $product_shadow_class = 'irantheme-woocommerce-products-item-shadow';
            }

            // Get product options checkbox --------------------------------------------------------------
            $product_padding_checkbox = get_option('irantheme_wc_archive_padding_status_checkbox');

            $product_padding_class = '';
            if ($product_padding_checkbox == 'on') {
                $product_padding_class = 'irantheme-woocommerce-products-item-padding';
            }

            // Get product options checkbox --------------------------------------------------------------
            $product_transparent_checkbox = get_option('irantheme_wc_archive_transparent_checkbox');

            $product_transparent_class = '';
            if ($product_transparent_checkbox == 'on') {
                $product_transparent_class = 'irantheme-woocommerce-products-item-transparent';
            }

            // Get shop page radius
            $shop_page_radius = get_option('irantheme_wc_archive_border_radius_range');
            $radius_style = 'border-radius:' . $shop_page_radius . 'rem;';

            // Check value of categories
            $categories = '';
            if ($product_cat != '') {
                // Explode string value for assign to query
                $categories = explode(',', $product_cat);
                if (strpos($product_cat, ',', 1)) {
                    array_pop($categories);
                }
            }

            // Get max price of products
            // Get the global WordPress database object.
            global $wpdb;
            // Build the WooCommerce product lookup table name.
            $table = $wpdb->prefix . 'wc_product_meta_lookup';
            // Check if the lookup table exists for compatibility.
            if ($wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $table)) === $table) {
                // Get the highest published product price from the optimized lookup table.
                $product_max_price = (float) $wpdb->get_var(
                    "SELECT MAX(lookup.max_price)
        			FROM {$table} AS lookup
        			INNER JOIN {$wpdb->posts} AS posts
            			ON lookup.product_id = posts.ID
        			WHERE posts.post_status = 'publish'
            			AND posts.post_type = 'product'"
                );
            } else {
                // Fallback for older WooCommerce versions.
                $product_max_price = (float) $wpdb->get_var(
                    "SELECT MAX(CAST(pm.meta_value AS DECIMAL(20,2)))
        			FROM {$wpdb->postmeta} AS pm
        			INNER JOIN {$wpdb->posts} AS posts
            			ON pm.post_id = posts.ID
        			WHERE pm.meta_key = '_price'
            			AND posts.post_status = 'publish'
            			AND posts.post_type IN ('product', 'product_variation')"
                );
            }
            // Round the value up for the price slider.
            $product_max_price = ceil($product_max_price);

            $current_page == 0 ? $current_page = 1 : $current_page = $current_page;
            $offset = (($current_page - 1) * $post_per_page);
            $custom_query_args = array(
                'posts_per_page'      => $post_per_page,
                'offset'              => $offset,
                'post_type'           => 'product',
                'post_status'         => 'publish',
                'post__in' => array(),
                'orderby'             => 'title',
                'order'               => 'ASC',
                'tax_query'           => array(),
                'meta_query'          => array(),
            );
            // Orderby price asc
            if ($ordering_type == 'price') {
                $custom_query_args["orderby"] = 'meta_value_num';
                $custom_query_args["meta_key"] = '_price';
            }
            // Orderby price desc
            if ($ordering_type == 'price-desc') {
                $custom_query_args["orderby"] = 'meta_value_num';
                $custom_query_args["meta_key"] = '_price';
                $custom_query_args["order"] = 'DESC';
            }
            // Orderby price desc
            if ($ordering_type == 'rating') {
                $custom_query_args["orderby"] = 'meta_value_num';
                $custom_query_args["meta_key"] = '_wc_average_rating';
                $custom_query_args["order"] = 'DESC';
            }
            // Orderby popularity desc
            if ($ordering_type == 'popularity') {
                $custom_query_args["orderby"] = 'meta_value_num';
                $custom_query_args["meta_key"] = 'total_sales';
                $custom_query_args["order"] = 'DESC';
            }
            // Orderby date desc
            if ($ordering_type == 'date') {
                $custom_query_args["orderby"] = 'date';
                $custom_query_args["order"] = 'DESC';
            }
            if ($on_sale == 'true') {
                if (!empty(wc_get_product_ids_on_sale())) {
                    $custom_query_args["post__in"] = wc_get_product_ids_on_sale();
                } else {
                    $custom_query_args["post__in"] = array(0);
                }
            }
            if ($categories != "") {
                // Product category filter
                // $_query_args = array('category' => $categories);
                // $_productsTest = wc_get_products($_query_args);
                // $product_id_array = array();
                // foreach ($_productsTest as $product) {
                //     array_push($product_id_array, $product->get_id());
                // }
                // $custom_query_args["post__in"] = $product_id_array;
                array_push($custom_query_args["tax_query"], array(
                    'taxonomy' => 'product_cat',
                    'terms'    => $categories,
                    'field'    => 'slug',
                ));
            }

            if ($dynamic_data_filter) {
                foreach ($dynamic_data_filter as $key => $value) {
                    // Decode the taxonomy key (convert %D8%A8%D8%B1%D9%86%D8%AF to برند)
                    $decoded_key = rawurldecode($key);
                    $taxonomy = str_replace('filter_', 'pa_', $decoded_key);

                    // Decode the whole value string (handles double encoding)
                    $decoded_value = rawurldecode($value);
                    $array_value = explode(',', $decoded_value);
                    array_pop($array_value); // remove trailing empty element (same as original)

                    // Decode each term individually (in case of double percent-encoding)
                    $terms = array_map('rawurldecode', $array_value);

                    $custom_query_args["tax_query"][] = array(
                        'taxonomy'        => $taxonomy,
                        'field'           => 'slug',
                        'terms'           => $terms,
                        'operator'        => 'IN',
                    );
                }
            }

            if ($price_from != null && $price_to != null) {
                if ($price_from != 0 || $price_to != $product_max_price) {
                    array_push($custom_query_args["meta_query"], array(
                        'key'     => '_price',
                        'value'   => array($price_from, $price_to),
                        'compare' => 'BETWEEN',
                        'type'    => 'NUMERIC'
                    ));
                }
            }

            if ($in_stock == 'true') {
                array_push($custom_query_args["meta_query"], array(
                    'key' => '_stock_status',
                    'value' => 'instock'
                ));
            }
            if ($search_keyword != '') {
                $custom_query_args["s"] = $search_keyword;
            }
            $products =
                new \WP_Query($custom_query_args);
            $product_total = $products->found_posts;
?>
            <div class="irantheme-woocommerce-products-section" data-products="<?php echo esc_attr($product_total); ?>" data-offset="<?php echo esc_attr($offset); ?>" data-per-page="<?php echo esc_attr($post_per_page); ?>">
                <?php if ($product_flag): ?>
                    <?php if ($product_total > 0): ?>
                        <div class="irantheme-woocommerce-products <?php echo esc_attr($base_color); ?>">
                            <div class="irantheme-woocommerce-products-list <?php echo esc_attr($col_count_class); ?>">
                                <?php
                                if ($products->have_posts()) :
                                    while ($products->have_posts()) :
                                        $products->the_post();
                                        global $product;
                                        // Get product item
                                        Irantheme_Section_Woocommerce::get_product_item($product, $product_features, $base_color, $details_color_select, $details_color_select2, $product_discount_checkbox, $product_total_sale_checkbox, $product_discount_schedule_checkbox, $product_color_attribute_checkbox, $product_image_options_class, $product_image_extra_options_style_class, $product_border_class, $product_image_hover_changer_class, $product_image_hover_mode_class, $product_hover_mode_class, $post_content_align, $product_shadow_class, '', $product_excerpt_count, $product_extra_options_checkbox, '', '', '', $shop_page_radius, $product_padding_class, $product_transparent_class);
                                    endwhile;
                                endif;
                                ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="irantheme-search-results-not-found">
                            <i class="fa-light fa-magnifying-glass <?php echo esc_attr(Irantheme_Color_Static::get_class_color_mode('color-fill', 'primary')) ?> <?php echo esc_attr(Irantheme_Color_Static::get_class_color_mode('bg-glass', 'primary')) ?>"></i>
                            <h3 class="irantheme-search-results-not-found-title">نتیجه ای یافت نشد <?php _e(':('); ?></h3>
                            <p class="irantheme-search-results-not-found-desc">نتیجه ای برای جستجوی شما یافت نشد!
                                </br>
                                لطفا فیلتر های دیگر را امتحان کنید
                            </p>
                        </div>
                    <?php endif; ?>
                    <?php $product_count = $products->found_posts; ?>
                    <?php $page_count = ceil($product_count / $post_per_page); ?>
                    <?php if ($page_count > 1) : ?>
                        <div class="irantheme-pagination-holder">
                            <div class="irantheme-pagination <?php echo esc_attr($base_color); ?> irantheme-wc-archive-ajax-pagination">
                                <?php if ($current_page == $page_count || ($current_page < $page_count && $current_page > 1)) : ?>
                                    <a class="prev page-numbers">صفحه قبل</a>
                                <?php endif; ?>
                                <?php if ($page_count < 7) : ?>
                                    <?php for ($i = 1; $i <= $page_count; $i++) : ?>
                                        <?php if ($current_page == $i) : ?>
                                            <span aria-current="page" class="page-numbers current" data-page="<?php echo $i; ?>"><?php echo $i; ?></span>
                                        <?php else : ?>
                                            <a class="page-numbers" data-page="<?php echo $i; ?>"><?php echo $i; ?></a>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                <?php elseif ($page_count >= 7) : ?>
                                    <?php if ($current_page <= 3): ?>
                                        <?php for ($i = 1; $i <= 4; $i++) : ?>
                                            <?php if ($current_page == $i) : ?>
                                                <span aria-current="page" class="page-numbers current" data-page="<?php echo $i; ?>"><?php echo $i; ?></span>
                                            <?php else : ?>
                                                <a class="page-numbers" data-page="<?php echo $i; ?>"><?php echo $i; ?></a>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                        <a class="page-dots"><?php echo '...'; ?></a>
                                        <a class="page-numbers" data-page="<?php echo $page_count; ?>"><?php echo $page_count; ?></a>
                                    <?php elseif ($current_page > 3 && $current_page <= $page_count - 3) : ?>
                                        <a class="page-numbers" data-page="<?php echo 1; ?>"><?php echo "1"; ?></a>
                                        <a class="page-dots"><?php echo '...'; ?></a>
                                        <a class="page-numbers" data-page="<?php echo $current_page - 1; ?>"><?php echo $current_page - 1; ?></a>
                                        <span aria-current="page" class="page-numbers current" data-page="<?php echo $current_page; ?>"><?php echo $current_page; ?></span>
                                        <a class="page-numbers" data-page="<?php echo $current_page + 1; ?>"><?php echo $current_page + 1; ?></a>
                                        <a class="page-dots"><?php echo '...'; ?></a>
                                        <a class="page-numbers" data-page="<?php echo $page_count; ?>"><?php echo $page_count; ?></a>
                                    <?php elseif ($current_page > $page_count - 3 && $current_page <= $page_count) : ?>
                                        <a class="page-numbers" data-page="<?php echo 1; ?>"><?php echo "1"; ?></a>
                                        <a class="page-dots"><?php echo '...'; ?></a>
                                        <?php for ($i = $page_count - 3; $i <= $page_count; $i++) : ?>
                                            <?php if ($current_page == $i) : ?>
                                                <span aria-current="page" class="page-numbers current" data-page="<?php echo $i; ?>"><?php echo $i; ?></span>
                                            <?php else : ?>
                                                <a class="page-numbers" data-page="<?php echo $i; ?>"><?php echo $i; ?></a>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <?php if ($current_page == 1 || ($current_page < $page_count && $current_page > 1)) : ?>
                                    <a class="next page-numbers">صفحه بعد</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="irantheme-product-not-found">
                        <i class="fa-light fa-box-open <?php echo esc_attr(Irantheme_Color_Static::get_class_color_mode('color-fill', 'primary')); ?> <?php echo esc_attr(Irantheme_Color_Static::get_class_color_mode('bg-glass', 'primary')); ?>"></i>
                        <h3 class="irantheme-product-not-found-title">محصولی جهت نمایش وجود ندارد!!!</h3>
                    </div>
                <?php endif ?>
            </div>
            <?php
            // Rest post data (Clear main query)
            if ($is_ajax) {
                wp_reset_postdata();
                die();
            }
        }

        /**
         * Display count cart list items
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public function product_fast_access()
        {
            // absint خودش با slashes کار می‌کنه
            $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;

            // Security check
            if (!$product_id) {
                wp_die('Invalid request');
            }

            $product = wc_get_product($product_id);

            if (!$product) {
                wp_die('Product not found');
            }

            $product_image_id = $product->get_image_id();
            $attachment_ids = $product->get_gallery_image_ids();
            ?>
            <div class="irantheme-product-fast-access-box">
                <div class="row">
                    <div class="col-lg-6">
                        <!-- Gallery fast access -->
                        <div class="irantheme-product-fast-access-box-gallery irantheme-product-image-holder">
                            <div class="irantheme-product-fast-access-box-gallery-slider swiper">
                                <div class="swiper-wrapper">
                                    <?php
                                    // Product image
                                    if ($product_image_id) {
                                    ?>
                                        <img class="swiper-slide irantheme-product-image" src="<?php echo wp_get_attachment_image_url($product_image_id, 'medium_large'); ?>" alt="<?php echo esc_attr('تصویر محصول'); ?>">
                                    <?php
                                    }
                                    // Product galley
                                    foreach ($attachment_ids as $value) {
                                    ?>
                                        <img class="swiper-slide irantheme-product-image" src="<?php echo wp_get_attachment_image_url($value, 'medium_large'); ?>" alt="<?php echo esc_attr('تصویر محصول'); ?>">
                                    <?php
                                    }
                                    ?>
                                </div>
                                <?php
                                Irantheme_Section_Utils::get_slider_arrow_pagination('on', 'on', 'on', 'white', 1, 'bottom', 'center');
                                ?>
                            </div>
                            <!-- Close button -->
                            <button type="button" class="irantheme-btn-void irantheme-product-fast-access-close">
                                <i class="fa-light fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="irantheme-product-fast-access-box-content">
                            <div class="irantheme-product-fast-access-box-content-top">
                                <!-- Title -->
                                <div class="irantheme-product-fast-access-box-content-top-title">
                                    <i class="fa-light fa-cart-shopping-fast"></i>
                                    <cite><?php _e('دسترسی سریع به محصول'); ?></cite>
                                </div>
                                <!-- Rating -->
                                <div class="irantheme-product-fast-access-box-content-top-rating">
                                    <span><?php echo esc_attr($product->get_average_rating()); ?></span>
                                    <i class="fa-solid fa-star"></i>
                                </div>
                            </div>
                            <div class="irantheme-product-fast-access-box-content-text">
                                <!-- Heading -->
                                <h2 class="irantheme-product-fast-access-box-content-text-heading">
                                    <a href="<?php echo get_the_permalink($product_id); ?>">
                                        <?php echo get_the_title($product_id); ?>
                                    </a>
                                </h2>
                                <!-- Excerpt -->
                                <p class="irantheme-product-fast-access-box-content-text-excerpt">
                                    <?php echo wp_trim_words(get_the_content(null, false, $product_id), 19); ?>
                                </p>
                            </div>
                            <?php
                            $add_to_cart_ajax_class = 'ajax_add_to_cart';
                            if ($product->is_type('variable')) {
                                $add_to_cart_ajax_class = '';
                            }
                            ?>
                            <div class="irantheme-woocommerce-products-item-add-to-cart">
                                <?php
                                // Arrow url --------------------------------------------------------------
                                $details_color_rgba_css = '';
                                $details_color_rgb_array = Irantheme_Color_Static::hex_to_rgb(Irantheme_Color_Static::get_hex_color('primary'));
                                $details_color_rgba_css = 'background-color:rgba(' . $details_color_rgb_array[0] . ',' . $details_color_rgb_array[1] . ',' . $details_color_rgb_array[2] . ',' . '0.1' . ')';
                                $details_color_class = Irantheme_Color_Static::get_class_color_mode('color', null, Irantheme_Color_Static::get_hex_color('primary'));
                                ?>
                                <!-- Add to cart -->
                                <a href="<?php echo esc_url(home_url('/?add-to-cart=' . $product->get_id())); ?>" value="<?php echo esc_attr($product->get_id()); ?>" class="irantheme-woocommerce-products-item-add-to-cart-button <?php echo esc_attr($add_to_cart_ajax_class); ?> add_to_cart_button" data-product_id="<?php echo $product->get_id(); ?>" style="<?php echo esc_attr($details_color_rgba_css); ?>">
                                    <i class="iconsax irantheme-woocommerce-products-item-add-to-cart-button-plus <?php echo esc_attr('white'); ?> <?php echo esc_attr($details_color_class); ?>" icon-name="basket-happy"></i>
                                    <i class="fa-light fa-check irantheme-woocommerce-products-item-add-to-cart-button-success <?php echo esc_attr('white'); ?> <?php echo esc_attr($details_color_class); ?>"></i>
                                    <i class="irantheme-animate-rotate <?php echo esc_attr('white'); ?> <?php echo esc_attr($details_color_class); ?>" style="display: none;"></i>
                                </a>
                                <!-- Price -->
                                <div class="irantheme-product-fast-access-box-content-price">
                                    <?php Irantheme_Section_Woocommerce::display_product_price($product, true, 'primary'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            // Rest post data (Clear main query)
            wp_reset_postdata();
            die();
        }


        /**
         * Remove item from cart list
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public function remove_item_from_cart()
        {
            $id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;

            if (!$id) {
                wp_die('Invalid product ID');
            }

            $product = wc_get_product($id);

            if (!$product) {
                wp_die('Product not found');
            }

            if (!WC()->cart) {
                wp_die('Cart not available');
            }

            if ($product->is_type('variable')) {
                foreach (WC()->cart->get_cart() as $key => $item) {
                    $product_var = wc_get_product($item['product_id']);
                    if ($product_var && $product_var->is_type('variable')) {
                        WC()->cart->remove_cart_item($key);
                        return true;
                    }
                }
            } else {
                $cart = WC()->instance()->cart;
                $cart_id = $cart->generate_cart_id($id);
                $cart_item_id = $cart->find_product_in_cart($cart_id);

                if ($cart_item_id) {
                    $cart->set_quantity($cart_item_id, 0);
                    return true;
                }
            }
            return false;
        }


        /**
         * Display count cart list items
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public function fetch_count_items_from_cart()
        {
            if (WC()->cart) {
                echo WC()->cart->get_cart_contents_count();
            } else {
                echo 0;
            }
            // Rest post data (Clear main query)
            wp_reset_postdata();
            die();
        }


        /**
         * Update cart quantity via AJAX
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public function update_cart_quantity()
        {
            // Check if cart item key is set
            if (!isset($_POST['cart_item_key'])) {
                wp_die('Invalid request');
            }

            if (!WC()->cart) {
                wp_die('Cart not available');
            }

            // sanitize_text_field خودش unslash می‌زنه
            $cart_item_key = sanitize_text_field($_POST['cart_item_key']);
            $action = isset($_POST['action_type']) ? sanitize_text_field($_POST['action_type']) : '';

            // Get cart item
            $cart_item = WC()->cart->get_cart_item($cart_item_key);

            if (!$cart_item) {
                wp_die('Cart item not found');
            }

            // Get current quantity
            $current_qty = $cart_item['quantity'];
            $product_id = $cart_item['product_id'];
            $_product = wc_get_product($product_id);

            if (!$_product) {
                wp_die('Product not found');
            }

            // Calculate new quantity
            if ($action === 'increase') {
                $new_qty = $current_qty + 1;
            } elseif ($action === 'decrease') {
                $new_qty = max(1, $current_qty - 1); // Don't go below 1
            } else {
                $new_qty = $current_qty;
            }

            // Check max quantity if set
            $max_qty = $_product->get_max_purchase_quantity();
            if ($max_qty > 0 && $new_qty > $max_qty) {
                $new_qty = $max_qty;
            }

            // Update cart
            $updated = WC()->cart->set_quantity($cart_item_key, $new_qty);

            if ($updated) {
                // Return updated HTML for the cart
                $this->cart_list_items_navigation();
            } else {
                echo 'error';
            }

            wp_die();
        }


        /**
         * Return cart list items
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public function cart_list_items_navigation()
        {
            ?>
                <!-- Cart items -->
                <?php
                do_action('woocommerce_before_cart');
                $cart_count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
                if ($cart_count != 0) {
                ?>
                    <form class="woocommerce-cart-form irantheme-woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">

                        <div class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">

                            <div class="irantheme-woocommerce-cart-navigation-list">

                                <?php do_action('woocommerce_before_cart_contents'); ?>

                                <?php
                                foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                                    $_product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                                    $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
                                    $product_name = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);
                                    $product = wc_get_product($product_id);

                                    if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
                                        $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
                                ?>
                                        <!-- Product item -->
                                        <div class="woocommerce-cart-form__cart-item <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?> irantheme-woocommerce-cart-navigation-list-item">

                                            <!-- Thumbnail -->
                                            <div class="product-thumbnail irantheme-woocommerce-cart-navigation-list-item-thumbnail">
                                                <?php
                                                $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);

                                                if (!$product_permalink) {
                                                    echo $thumbnail; // PHPCS: XSS ok.
                                                } else {
                                                    printf('<a href="%s">%s</a>', esc_url($product_permalink), $thumbnail); // PHPCS: XSS ok.
                                                }
                                                ?>
                                            </div>

                                            <div class="irantheme-woocommerce-cart-navigation-list-item-content">
                                                <!-- Product categories -->
                                                <div class="irantheme-woocommerce-cart-navigation-list-item-content-categories">
                                                    <!-- List categories -->
                                                    <div class="irantheme-woocommerce-cart-navigation-list-item-content-categories-list">
                                                        <?php
                                                        $categories = get_the_terms($product->get_id(), 'product_cat');
                                                        if (!empty($categories)) {
                                                            foreach ($categories as $category) {
                                                                echo '<a href="' . get_term_link($category) . '">' . $category->name . '</a>';
                                                            }
                                                        }
                                                        ?>
                                                    </div>
                                                    <!-- Rating -->
                                                    <div class="irantheme-woocommerce-cart-navigation-list-item-content-categories-rating">
                                                        <!-- Rating -->
                                                        <div class="irantheme-woocommerce-products-item-content-rating">
                                                            <span><?php echo esc_attr($product->get_average_rating()); ?></span>
                                                            <i class="fa-solid fa-star"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Product name -->
                                                <div class="product-name irantheme-woocommerce-cart-navigation-list-item-content-heading" data-title="<?php esc_attr_e('Product', 'woocommerce'); ?>">
                                                    <?php
                                                    if (!$product_permalink) {
                                                        echo wp_kses_post($product_name . '&nbsp;');
                                                    } else {
                                                        /**
                                                         * This filter is documented above.
                                                         *
                                                         * @since 2.1.0
                                                         */
                                                        echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', esc_url($product_permalink), $_product->get_name()), $cart_item, $cart_item_key));
                                                    }

                                                    do_action('woocommerce_after_cart_item_name', $cart_item, $cart_item_key);

                                                    // Meta data.
                                                    echo wc_get_formatted_cart_item_data($cart_item); // PHPCS: XSS ok.

                                                    // Backorder notification.
                                                    if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity'])) {
                                                        echo wp_kses_post(apply_filters('woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__('Available on backorder', 'woocommerce') . '</p>', $product_id));
                                                    }
                                                    ?>
                                                </div>

                                                <!-- Quantity -->
                                                <div class="irantheme-woocommerce-cart-navigation-list-item-content-quantity">
                                                    <div class="irantheme-quantity-input-wrapper">
                                                        <button type="button" class="irantheme-quantity-btn irantheme-quantity-minus"
                                                            data-cart-item-key="<?php echo esc_attr($cart_item_key); ?>">
                                                            <i class="fas fa-minus"></i>
                                                        </button>

                                                        <span class="irantheme-quantity-display">
                                                            <?php echo esc_html($cart_item['quantity']); ?>
                                                        </span>

                                                        <button type="button" class="irantheme-quantity-btn irantheme-quantity-plus"
                                                            data-cart-item-key="<?php echo esc_attr($cart_item_key); ?>">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                    </div>
                                                    <div class="irantheme-woocommerce-cart-navigation-list-item-content-info">
                                                        <!-- Price -->
                                                        <div class="product-price irantheme-woocommerce-cart-navigation-list-item-content-info-price" data-title="<?php esc_attr_e('Price', 'woocommerce'); ?>">
                                                            <?php
                                                            Irantheme_Section_Woocommerce::display_product_price($product);
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Remove item -->
                                                <div class="irantheme-woocommerce-cart-navigation-list-item-remove">
                                                    <button type="button" class="irantheme-btn-void irantheme-woocommerce-cart-navigation-list-item-remove-button" data-product-id="<?php echo esc_attr($product_id); ?>">
                                                        <i class="iconsax" icon-name="trash"></i>
                                                        <?php _e('حذف'); ?>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                <?php
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </form>

                    <!-- Footer -->
                    <div class="cart-collaterals irantheme-woocommerce-cart-navigation-footer">
                        <!-- Subtotal -->
                        <div class="irantheme-woocommerce-cart-navigation-footer-subtotal">
                            <cite class="irantheme-woocommerce-cart-navigation-footer-subtotal-title"><?php _e('جمع مبلغ:'); ?></cite>
                            <span class="irantheme-woocommerce-cart-navigation-footer-subtotal-total"><?php echo WC()->cart->get_cart_total(); ?></span>
                        </div>
                        <!-- View cart page -->
                        <div class="irantheme-woocommerce-cart-navigation-footer-view-cart">
                            <a class="irantheme-btn irantheme-btn-block irantheme-btn-medium" href="<?php echo wc_get_cart_url(); ?>"><?php _e('صفحه سبد خرید'); ?></a>
                        </div>
                        <!-- Checkout -->
                        <div class="irantheme-woocommerce-cart-navigation-footer-checkout">
                            <a href="<?php echo wc_get_checkout_url(); ?>" class="irantheme-btn irantheme-btn-dark irantheme-btn-medium irantheme-btn-block"><?php _e('ادامه فرایند خرید'); ?></a>
                        </div>
                    </div>
                <?php do_action('woocommerce_after_cart');
                } else {
                ?>
                    <div class="irantheme-woocommerce-cart-navigation-empty">
                        <i class="irantheme-woocommerce-cart-navigation-empty-icon iconsax" icon-name="basket-x-1"></i>
                        <h3 class="irantheme-woocommerce-cart-navigation-empty-title"><?php _e('سبد خرید شما خالی است!'); ?></h3>
                        <p class="irantheme-woocommerce-cart-navigation-empty-desc"><?php _e('به نظر می رسد شما هنوز محصولی را به سبد خرید خود اضافه نکرده اید.'); ?></p>
                    </div>
                <?php
                }
                // Rest post data (Clear main query)
                wp_reset_postdata();
                die();
            }


            /**
             * Return wishlist list items
             * @param void
             * @return void
             * @author iranianclever
             * @since v1.0
             * @see v1.0
             */
            public function wishlist_list_items_navigation()
            {
                // Get string product ids
                $wishlist_ids_raw = isset($_POST['wishlist_ids']) ? sanitize_text_field($_POST['wishlist_ids']) : '';
                // Convert product ids to array
                $wishlist_ids = !empty($wishlist_ids_raw) ? array_map('absint', explode(',', $wishlist_ids_raw)) : array();
                $args = array(
                    'post_type' => 'product',
                    'post__in' => $wishlist_ids,
                    'post_status'    => array('publish'),
                );
                $products = new WP_Query($args);
                if ($products->have_posts()) {
                ?>
                    <?php
                    while ($products->have_posts()) {
                        $products->the_post();
                        global $product;
                        $stock = $product->get_stock_status();
                        $stock_text = '';
                        if ($stock == 'instock') {
                            $stock_text = 'موجود است';
                        } elseif ($stock == 'no') {
                            $stock_text = 'موجود نیست';
                        } elseif ($stock == 'outofstock') {
                            $stock_text = 'تمام شد!';
                        } elseif ($stock == 'onbackorder') {
                            $stock_text = 'پیش خرید';
                        }
                    ?>
                        <div class="irantheme-woocommerce-cart-navigation-list">
                            <div class="irantheme-woocommerce-cart-navigation-list-item" data-product="<?php echo get_the_ID(); ?>">
                                <div class="irantheme-woocommerce-cart-navigation-list-item-thumbnail">
                                    <a href="<?php the_permalink(); ?>">
                                        <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail'); ?>" alt="<?php echo get_the_title(); ?>">
                                    </a>
                                </div>
                                <div class="irantheme-woocommerce-cart-navigation-list-item-content">
                                    <div class="irantheme-woocommerce-cart-navigation-list-item-content-heading">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_title(); ?>
                                        </a>
                                    </div>
                                    <div class="irantheme-woocommerce-cart-navigation-list-item-content-info">
                                        <div class="irantheme-woocommerce-cart-navigation-list-item-content-info-price">
                                            <?php echo $product->get_price() . ' ' . get_woocommerce_currency_symbol(); ?>
                                        </div>
                                        <div class="irantheme-woocommerce-cart-navigation-list-item-content-info-separator"><i class="fa-light fa-arrow-left"></i></div>
                                        <div class="irantheme-woocommerce-cart-navigation-list-item-content-info-stock">
                                            <?php echo esc_html($stock_text); ?>
                                        </div>
                                    </div>
                                    <!-- Remove item -->
                                    <div class="irantheme-woocommerce-cart-navigation-list-item-remove">
                                        <button type="button" class="irantheme-btn-void irantheme-woocommerce-cart-navigation-list-item-remove-button wishlist-remove" data-product-id="<?php echo esc_attr(get_the_ID()); ?>">
                                            <i class="iconsax" icon-name="trash"></i>
                                            <?php _e('حذف'); ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                <?php
                } else {
                ?>
                    <div class="irantheme-woocommerce-cart-navigation-empty">
                        <i class="irantheme-woocommerce-cart-navigation-empty-icon iconsax" icon-name="heart-slash"></i>
                        <h3 class="irantheme-woocommerce-cart-navigation-empty-title"><?php _e('لیست علاقه مندی خالیست!'); ?></h3>
                        <p class="irantheme-woocommerce-cart-navigation-empty-desc"><?php _e('به نظر می رسد شما هنوز محصولی را به علاقمندی های خود اضافه نکردید.'); ?></p>
                    </div>
                <?php
                }

                // Rest post data (Clear main query)
                wp_reset_postdata();
                die();
            }


            /**
             * Return wishlist list items for wc dashboard
             * @param void
             * @return void
             * @author M-MALEKZADEH77
             * @since v1.0.2
             * @see v1.0
             */
            public function wishlist_list_items_wc_favorites()
            {
                // Get string product ids
                $wishlist_ids_raw = isset($_POST['wishlist_ids']) ? sanitize_text_field($_POST['wishlist_ids']) : '';
                // Convert product ids to array
                $wishlist_ids = !empty($wishlist_ids_raw) ? array_map('absint', explode(',', $wishlist_ids_raw)) : array();
                $args = array(
                    'post_type' => 'product',
                    'post__in' => $wishlist_ids,
                    'post_status'    => array('publish'),
                    'orderby'        => 'post__in',
                );
                $products = new WP_Query($args);
                if ($products->have_posts()) {
                ?>
                    <?php
                    while ($products->have_posts()) {
                        $products->the_post();
                        global $product;
                        $is_call_for_price = get_post_meta($product->get_id(), 'irantheme_product_meta_is_call_for_price', true);
                    ?>
                        <div class="irantheme-myaccount-favorites-content-list-item col-xxl-3 col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12" data-product="<?php echo get_the_ID(); ?>">
                            <div class="irantheme-myaccount-favorites-content-list-item-wrapper <?php echo $product->get_stock_status(); ?>">
                                <div class="irantheme-myaccount-favorites-content-list-item-thumbnail">
                                    <a href="<?php the_permalink(); ?>">
                                        <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail'); ?>" alt="<?php echo get_the_title(); ?>">
                                    </a>
                                    <div class="irantheme-myaccount-favorites-content-list-item-thumbnail-abs">
                                        <?php if ($product->is_in_stock()) :
                                            if (irantheme_get_sale_percentage($product) != '0') : ?>
                                                <!-- Discount -->
                                                <div class="irantheme-myaccount-favorites-content-list-item-thumbnail-abs-discount">
                                                    <?php echo irantheme_get_sale_percentage($product); ?>
                                                </div>
                                            <?php endif;
                                        else: ?>
                                            <div class="irantheme-myaccount-favorites-content-list-item-thumbnail-abs-out-of-stock">
                                                <?php _e('ناموجود'); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="irantheme-myaccount-favorites-content-list-item-content">
                                    <div class="irantheme-myaccount-favorites-content-list-item-content-heading">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_title(); ?>
                                        </a>
                                    </div>
                                    <div class="irantheme-myaccount-favorites-content-list-item-content-info">
                                        <div class="irantheme-myaccount-favorites-content-list-item-content-info-price">
                                            <?php if ($is_call_for_price != 'yes'): ?>
                                                <?php Irantheme_Section_Woocommerce::display_product_price($product); ?>
                                            <?php else: ?>
                                                <span class="price irantheme-woocommerce-products-price">تماس بگیرید</span>
                                            <?php endif; ?>
                                        </div>
                                        <?php if ($product->get_stock_status() == 'onbackorder'): ?>
                                            <span class="stock-status irantheme-woocommerce-products-stock-status">پیش خرید!</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="irantheme-myaccount-favorites-content-list-item-action">
                                    <div class="irantheme-myaccount-favorites-content-list-item-action-remove">
                                        <button type="button" class="irantheme-btn-void irantheme-myaccount-favorites-content-list-item-action-remove-button wc-favorite-wishlist-remove"><i class="fa-light fa-trash-can"></i></button>
                                    </div>
                                    <?php
                                    $add_to_cart_ajax_class = 'favorite_ajax_add_to_cart';
                                    if ($product->is_type('variable')) {
                                        $add_to_cart_ajax_class = '';
                                    }
                                    if ($is_call_for_price == 'yes') {
                                        $add_to_cart_ajax_class = '';
                                    }
                                    ?>
                                    <?php
                                    $add_to_card_btn_color_customization_checkbox = get_option('irantheme_single_product_add_to_card_btn_color_customization_checkbox');
                                    $add_to_card_btn_bg_color = get_option('irantheme_single_product_add_to_card_btn_bg_color');
                                    $add_to_card_btn_for_color = get_option('irantheme_single_product_add_to_card_btn_color');
                                    $add_to_card_btn_bg_color_class = '';
                                    $add_to_card_btn_color_class = '';
                                    $add_to_card_btn_hover_color_class = '';
                                    $add_to_card_btn_border_color_class = '';
                                    if ($add_to_card_btn_color_customization_checkbox == 'on') {
                                        if ($add_to_card_btn_bg_color != 'off') {
                                            $add_to_card_btn_bg_color_class = esc_attr(Irantheme_Color_Static::get_class_color_mode('bg', null, Irantheme_Color_Static::get_hex_color($add_to_card_btn_bg_color)));
                                            $add_to_card_btn_hover_color_class = esc_attr(Irantheme_Color_Static::get_class_color_mode('color-hover', null, Irantheme_Color_Static::get_hex_color($add_to_card_btn_bg_color)));
                                            $add_to_card_btn_border_color_class = esc_attr(Irantheme_Color_Static::get_class_color_mode('border', null, Irantheme_Color_Static::get_hex_color($add_to_card_btn_bg_color)));
                                        }
                                        if ($add_to_card_btn_for_color == 'off') {
                                            $add_to_card_btn_color_class = Irantheme_Color_Static::get_optimal_text_color(Irantheme_Color_Static::get_hex_color($add_to_card_btn_bg_color));
                                        } else {
                                            $add_to_card_btn_color_class = 'text-color-' . $add_to_card_btn_for_color;
                                        }
                                    }
                                    ?>
                                    <div class="irantheme-myaccount-favorites-content-list-item-action-add-to-cart">
                                        <?php if ($is_call_for_price != 'yes'): ?>
                                            <?php if ($product->get_stock_status() != 'outofstock'): ?>
                                                <!-- Add to cart -->
                                                <a href="<?php echo $product->add_to_cart_url() ?>" value="<?php echo esc_attr($product->get_id()); ?>" class="irantheme-myaccount-favorites-content-list-item-action-add-to-cart-button <?php echo esc_attr($add_to_cart_ajax_class); ?> add_to_cart_button <?php echo $product->get_stock_status(); ?> <?php echo $add_to_card_btn_color_class; ?> <?php echo $add_to_card_btn_bg_color_class; ?> <?php echo $add_to_card_btn_hover_color_class; ?> <?php echo $add_to_card_btn_border_color_class; ?>" data-product_id="<?php echo $product->get_id(); ?>">
                                                    <i class="fa-light fa-check irantheme-icon-check irantheme-myaccount-favorites-content-list-item-action-add-to-cart-button-success"></i>
                                                    <i class="irantheme-animate-rotate" style="display: none;"></i>
                                                    <span>افزودن به سبد خرید</span>
                                                </a>
                                            <?php else: ?>
                                                <a value="<?php echo esc_attr($product->get_id()); ?>" class="irantheme-myaccount-favorites-content-list-item-action-add-to-cart-button <?php echo $product->get_stock_status(); ?>" data-product_id="<?php echo $product->get_id(); ?>">
                                                    <span>ناموجود</span>
                                                </a>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <a href="<?php echo $product->get_permalink() ?>" value="<?php echo esc_attr($product->get_id()); ?>" class="irantheme-myaccount-favorites-content-list-item-action-add-to-cart-button <?php echo $add_to_card_btn_color_class; ?> <?php echo $add_to_card_btn_bg_color_class; ?> <?php echo $add_to_card_btn_hover_color_class; ?> <?php echo $add_to_card_btn_border_color_class; ?>" data-product_id="<?php echo $product->get_id(); ?>">
                                                <span>جزئیات بیشتر</span>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
    <?php
                }

                // Rest post data (Clear main query)
                wp_reset_postdata();
                die();
            }


            /**
             * Newsletter send mail
             * @param void
             * @return void
             * @author iranianclever
             * @since v1.0
             * @see v1.0
             */
            public function newsletter_send_mail()
            {
                // sanitize_text_field خودش unslash می‌زنه
                $newsletter_subject = isset($_POST['subject']) ? sanitize_text_field($_POST['subject']) : '';
                $newsletter_content = isset($_POST['content']) ? wp_kses_post($_POST['content']) : '';
                // Get all mails raw
                $mails_option = get_option('irantheme_newsletter_all_mails_textarea');
                // Create array from all mails line by line
                $all_mails = preg_split('/\r\n|\r|\n/', $mails_option);
                $status_mail = false;
                foreach ($all_mails as $mail) {
                    if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                        // Send mail
                        $status_mail = wp_mail($mail, $newsletter_subject, $newsletter_content);
                    }
                    // Check status send mails
                    if ($status_mail) {
                        echo $mail . ' ارسال شد.' . "\n";
                    } else {
                        echo $mail . ' ارسال نشد.' . "\n";
                    }
                }
                wp_die();
            }


            /**
             * Newsletter get mail
             * @param void
             * @return void
             * @author iranianclever
             * @since v1.0
             * @see v1.0
             */
            public function newsletter_get_mail()
            {
                // sanitize_email خودش unslash می‌زنه
                $newsletter_new_mail = isset($_POST['new_mail']) ? sanitize_email($_POST['new_mail']) : '';
                // Get all mails raw
                $mails_option = get_option('irantheme_newsletter_all_mails_textarea');
                // Check for valid mail
                if (filter_var($newsletter_new_mail, FILTER_VALIDATE_EMAIL)) {
                    // Check for prevent writing double mail
                    if (strpos($mails_option, $newsletter_new_mail) === false) {
                        // Add new email to holder
                        $mails_option .= "\n" . $newsletter_new_mail;
                        // Update option value
                        update_option('irantheme_newsletter_all_mails_textarea', $mails_option);
                    } else {
                        // Duplicate mail
                        die('repeat');
                    }
                } else {
                    // Wrong mail
                    die('wrong');
                }
            }
            // ============================================================================
            // ----------------------------- Send otp code --------------------------------
            // ============================================================================

            /**
             * Generate otp code
             * @param int $length length of otp code
             * @return string OTP code generated
             * @author M-MALEKZADEH77
             * @since 1.0
             * @see 1.0
             */
            public static function generate_otp_code($length = 6)
            {
                return strval(rand(pow(10, $length - 1), pow(10, $length) - 1));
            }

            /**
             * Request OTP code and save it or register a new user
             * @param string $phone_number user mobile number
             * @return array|WP_Error Code information (if successful) or WP_Error (if error).
             * @author M-MALEKZADEH77
             * @since 1.0
             * @see 1.0
             */
            public static function request_otp_for_login($phone_number)
            {
                // Mobile number validation
                if (! preg_match('/^(0?9[0-9]{9})$/', $phone_number)) {
                    return new \WP_Error(
                        'invalid_phone',
                        esc_html__('شماره موبایل وارد شده معتبر نیست. لطفاً با فرمت 09xxxxxxxxx یا 9xxxxxxxxx وارد کنید.')
                    );
                }

                // First we remove all non-numeric characters
                $phone_number = preg_replace('/[^0-9]/', '', $phone_number);

                // If the number was 10 digits and started with '9' (i.e. 912...), we add a '0' to the beginning
                if (strlen($phone_number) === 10 && substr($phone_number, 0, 1) === '9') {
                    $phone_number = '0' . $phone_number;
                }

                // At this point, $phone_number must be exactly 11 digits long and start with '09'.
                // If after this standardization it is still not 11 digits long or does not start with '09',
                // then the input was very unusual and an error occurred.
                if (strlen($phone_number) !== 11 || substr($phone_number, 0, 2) !== '09') {
                    return new \WP_Error(
                        'standardization_failed',
                        esc_html__('خطا در پردازش شماره موبایل. لطفاً شماره را بررسی کنید.')
                    );
                }

                // Find a user by mobile number
                $user_query = new \WP_User_Query(array(
                    'meta_key'     => 'mobile_number',
                    'meta_value'   => $phone_number,
                    'number'       => 1,
                    'fields'       => 'ID',
                ));
                $user_ids = $user_query->get_results();

                $user_id = 0;
                if (empty($user_ids)) {
                    return new \WP_Error('user_not_found_redirect_to_register', 'شماره موبایل شما در سیستم ثبت نشده است.');
                } else {
                    $user_id = $user_ids[0];
                }

                // Check the advanced security status
                $advanced_security_enabled = get_option('irantheme_otp_login_restrictions_login_checkbox');

                // Get exception IPs from settings
                $excluded_ips_raw = get_option('irantheme_otp_excluded_ips');
                $excluded_ips_array = array_map('trim', explode(',', $excluded_ips_raw));
                $excluded_ips_array = array_filter($excluded_ips_array); // حذف ورودی های خالی

                // The cooldown value that the user entered
                $cooldown_duration = get_option('irantheme_otp_login_code_resend_time');
                // Ensuring that the value is at least 1 second so that it does not divide by zero and the logic works correctly
                $cooldown_duration = max(1, (int)$cooldown_duration);


                // Fixed settings for the monitoring interval
                $resend_period_user = 10 * MINUTE_IN_SECONDS; // 10 minute interval for user
                $resend_period_ip = 10 * MINUTE_IN_SECONDS; // 10 minute interval for IP

                // Dynamic calculation of maximum allowed requests based on cooldown and monitoring interval
                $max_possible_requests_user = floor($resend_period_user / $cooldown_duration) + 1;
                $max_possible_requests_ip = floor($resend_period_ip / $cooldown_duration) + 1;

                // Set the maximum number of requests allowed to activate the lock.
                $max_requests_in_period_user =  $max_possible_requests_user - 2;
                // Consider a higher number of allowed requests for the IP.
                $max_requests_in_period_ip =  $max_possible_requests_ip - 1;

                // Ensure that at least one request is allowed
                $max_requests_in_period_user = max(1, $max_requests_in_period_user);
                $max_requests_in_period_ip = max(1, $max_requests_in_period_ip);

                // Get time limit from setting
                $lockout_duration_time = get_option('irantheme_otp_login_restrictions_login_time');
                // Time limit
                $lockout_duration = (int)$lockout_duration_time * MINUTE_IN_SECONDS;

                // Get the user's IP
                $current_ip = self::get_user_ip_address();


                // **Check if advanced security is disabled OR if the current IP is in the exclusion list**
                // If either is true, skip advanced security checks and clear any existing lockouts for this user/IP.
                if ($advanced_security_enabled != 'on' || in_array($current_ip, $excluded_ips_array)) {
                    // Clear any existing lockout metadata for the user
                    delete_user_meta($user_id, '_otp_request_times');
                    delete_user_meta($user_id, '_otp_lockout_until');

                    // Clear any existing lockout transients for the IP
                    delete_transient('otp_ip_request_times_' . $current_ip);
                    delete_transient('otp_ip_lockout_' . $current_ip);

                    // Proceed only with the basic cooldown check
                    $last_requested_time = get_user_meta($user_id, '_otp_last_requested', true);
                    if ($last_requested_time && (time() - $last_requested_time < $cooldown_duration)) {
                        $remaining_cooldown = $cooldown_duration - (time() - $last_requested_time);

                        return new \WP_Error(
                            'resend_cooldown_active',
                            sprintf(esc_html__('لطفاً قبل از درخواست مجدد کد، %s ثانیه صبر کنید.'), $remaining_cooldown),
                            ['remaining_time' => $remaining_cooldown] // Send remaining time as error data for JS
                        );
                    }
                    // If cooldown is also fine, jump to OTP generation
                    goto generate_and_send_otp;
                }


                // 1. Check if the user (phone number) is currently restricted (only if advanced security is enabled)
                $lockout_until = get_user_meta($user_id, '_otp_lockout_until', true);
                if ($advanced_security_enabled == 'on' && $lockout_until && time() < $lockout_until) {
                    $remaining_lockout = $lockout_until - time();
                    return new \WP_Error(
                        'user_locked_out',
                        sprintf(esc_html__('شما به دلیل تلاش‌های ناموفق متعدد به مدت %s دقیقه محدود شده‌اید. لطفاً پس از %s ثانیه دیگر تلاش کنید.'), round($lockout_duration / MINUTE_IN_SECONDS), $remaining_lockout),
                        ['remaining_time' => $remaining_lockout] // Send remaining time as error data for JS
                    );
                } else if ($advanced_security_enabled == 'on' && $lockout_until && time() >= $lockout_until) {
                    // If the timeout has passed, we clear the lock metadata
                    delete_user_meta($user_id, '_otp_lockout_until');
                    // Clear history after lockout ends
                    delete_user_meta($user_id, '_otp_request_times');
                }

                // 2. Check if IP is currently restricted (only if advanced security is enabled)
                // We use transients for IP restriction so that no metadata is needed on the user
                $ip_lockout_until = get_transient('otp_ip_lockout_' . $current_ip);
                if ($advanced_security_enabled == 'on' && $ip_lockout_until && time() < $ip_lockout_until) {
                    $remaining_lockout = $ip_lockout_until - time();
                    return new \WP_Error(
                        'ip_locked_out',
                        sprintf(esc_html__('تلاش‌های زیادی از این IP صورت گرفته است و به مدت %s ثانیه محدود شده‌اید. لطفاً پس از این زمان تلاش کنید.'), $remaining_lockout),
                        ['remaining_time' => $remaining_lockout] // Send remaining time as error data for JS
                    );
                } else if ($advanced_security_enabled == 'on' && $ip_lockout_until && time() >= $ip_lockout_until) {
                    delete_transient('otp_ip_lockout_' . $current_ip);
                    delete_transient('otp_ip_request_times_' . $current_ip); // Clear history after lockout ends

                }


                // 3. Check cooldown (without changing your logic)
                // Server-side code resubmission limit
                $last_requested_time = get_user_meta($user_id, '_otp_last_requested', true);
                // If the last request time exists and is less than the cooldown time
                if ($last_requested_time && (time() - $last_requested_time < $cooldown_duration)) {
                    $remaining_cooldown = $cooldown_duration - (time() - $last_requested_time);
                    // Return a WP_Error to let JavaScript know there is still time left.
                    return new \WP_Error(
                        'resend_cooldown_active',
                        sprintf(esc_html__('لطفاً قبل از درخواست مجدد کد، %s ثانیه صبر کنید.'), $remaining_cooldown),
                        ['remaining_time' => $remaining_cooldown]
                    );
                }

                // 4. Handling repeated requests (for both user and IP) when advanced security is enabled
                // This block is only reached if advanced security is enabled AND IP is NOT excluded.

                // Manage recurring requests based on User (phone number) 
                $user_request_times = get_user_meta($user_id, '_otp_request_times', true);
                if (!is_array($user_request_times)) {
                    $user_request_times = array();
                }

                // Clear request times older than the user's desired range
                $user_request_times = array_filter($user_request_times, function ($timestamp) use ($resend_period_user) {
                    return $timestamp > (time() - $resend_period_user);
                });

                // Add the current request time
                $user_request_times[] = time();

                // If the number of requests for the user exceeds the maximum allowed
                if (count($user_request_times) > $max_requests_in_period_user) {
                    update_user_meta($user_id, '_otp_lockout_until', time() + $lockout_duration);
                    update_user_meta($user_id, '_otp_request_times', array()); // Clear request history
                    return new \WP_Error(
                        'too_many_otp_requests_user',
                        sprintf(esc_html__('شما بیش از حد مجاز درخواست کد جدید داده‌اید و به مدت %s دقیقه محدود شده‌اید.'), round($lockout_duration / MINUTE_IN_SECONDS)),
                        ['remaining_time' => $lockout_duration] // Send lockout duration
                    );
                }

                // Save updated request times for the user
                update_user_meta($user_id, '_otp_request_times', $user_request_times);

                // Manage repeated requests based on IP
                $ip_request_times = get_transient('otp_ip_request_times_' . $current_ip);
                if (!is_array($ip_request_times)) {
                    $ip_request_times = array();
                }

                // Clear request times older than the desired range for the IP
                $ip_request_times = array_filter($ip_request_times, function ($timestamp) use ($resend_period_ip) {
                    return $timestamp > (time() - $resend_period_ip);
                });

                // Add the current request time
                $ip_request_times[] = time();

                // If the number of requests from the IP exceeds the maximum allowed
                if (count($ip_request_times) > $max_requests_in_period_ip) {
                    set_transient('otp_ip_lockout_' . $current_ip, time() + $lockout_duration, $lockout_duration); // IP lock
                    delete_transient('otp_ip_request_times_' . $current_ip); // Clear request history
                    return new \WP_Error(
                        'too_many_otp_requests_ip',
                        sprintf(esc_html__('تلاش‌های زیادی از این IP برای درخواست کد انجام شده است و به مدت %s دقیقه محدود شده‌اید.'), round($lockout_duration / MINUTE_IN_SECONDS)),
                        ['remaining_time' => $lockout_duration] // Send lockout duration
                    );
                }

                // Store updated request times for IP (with expiration to prevent infinite accumulation)
                set_transient('otp_ip_request_times_' . $current_ip, $ip_request_times, $resend_period_ip + 60); // 60 seconds extra to be sure

                // Label for goto statement
                generate_and_send_otp:
                // Get number of otp code digits
                $otp_number_code_digits = get_option('irantheme_otp_login_number_code_digits');
                // Get expiration otp code time
                $expiration_time = get_option('irantheme_otp_login_code_expiration_time', 5);
                // Generate code
                $otp_code = self::generate_otp_code($otp_number_code_digits);
                // Generate expiration code time
                $expiration = time() + ($expiration_time * MINUTE_IN_SECONDS);

                // Store OTP code and expiration time in user metadata
                update_user_meta($user_id, '_otp_code', $otp_code);
                update_user_meta($user_id, '_otp_expiration', $expiration);
                update_user_meta($user_id, '_otp_last_requested', time());

                // Send OTP code with sms
                $sms_sent = self::send_sms_otp($phone_number, $otp_code);

                if (! $sms_sent) {
                    return new \WP_Error('sms_send_failed', 'خطا در ارسال کد اعتبارسنجی، لطفاً دقایقی دیگر تلاش کنید.');
                }

                return array(
                    'code' => $otp_code,
                    'current_ip' => $current_ip,
                    'message' => 'کد اعتبار سنجی با موفقیت ارسال شد.',
                    'otp_expires_in'  => $expiration - time(),
                    'resend_cooldown' => $cooldown_duration
                );
            }

            /**
             * Get user IP 
             * @return string $ip user ip
             * @author M-MALEKZADEH77
             * @since 1.0
             * @see 1.0
             */
            public static function get_user_ip_address()
            {
                if (! empty($_SERVER['HTTP_CLIENT_IP'])) {
                    $ip = $_SERVER['HTTP_CLIENT_IP'];
                } elseif (! empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                    $ip_array = explode(',', $ip);
                    $ip = trim(end($ip_array)); // معمولا آخرین IP واقعی کاربر است
                } else {
                    $ip = $_SERVER['REMOTE_ADDR'];
                }
                return sanitize_text_field($ip);
            }

            /**
             * OTP code validation and user login             *
             * @param int    $phone_number phone number
             * @param string $submitted_code OTP code entered by the user
             * @return WP_User|WP_Error User object (if successful) or WP_Error (if error)
             * @author M-MALEKZADEH77
             * @since 1.0
             * @see 1.0
             */
            public static function verify_otp_and_login($phone_number, $submitted_code)
            {
                // Start: Phone number validation and user ID retrieval
                if (! preg_match('/^(0?9[0-9]{9})$/', $phone_number)) {
                    return new \WP_Error(
                        'invalid_phone',
                        esc_html__('شماره موبایل وارد شده معتبر نیست. لطفاً با فرمت 09xxxxxxxxx یا 9xxxxxxxxx وارد کنید.')
                    );
                }
                $phone_number = preg_replace('/[^0-9]/', '', $phone_number);
                if (strlen($phone_number) === 10 && substr($phone_number, 0, 1) === '9') {
                    $phone_number = '0' . $phone_number;
                }
                if (strlen($phone_number) !== 11 || substr($phone_number, 0, 2) !== '09') {
                    return new \WP_Error(
                        'standardization_failed',
                        esc_html__('خطا در پردازش شماره موبایل. لطفاً شماره را بررسی کنید.')
                    );
                }

                $user_query = new \WP_User_Query(array(
                    'meta_key'     => 'mobile_number',
                    'meta_value'   => $phone_number,
                    'number'       => 1,
                    'fields'       => 'ID',
                ));
                $user_ids = $user_query->get_results();

                if (empty($user_ids)) {
                    return new \WP_Error('user_not_found', esc_html__('کاربری با این شماره موبایل یافت نشد.'));
                }
                $user_id = $user_ids[0];
                // Get the full WP_User object
                $user = get_user_by('ID', $user_id);
                // --- End: Phone number validation and user ID retrieval ---


                // Get stored OTP code and expiration time
                $stored_otp_code = get_user_meta($user_id, '_otp_code', true);
                $expiration_time = get_user_meta($user_id, '_otp_expiration', true);
                // Current failed attempts for this code
                $current_attempts = (int) get_user_meta($user_id, '_otp_attempts', true);

                // Get advanced security settings
                $advanced_security_enabled = get_option('irantheme_otp_login_restrictions_login_checkbox');
                // Max attempts before lockout (default 3)
                $max_attempts = apply_filters('irantheme_otp_max_login_attempts', 3);

                $lockout_duration_time = get_option('irantheme_otp_login_restrictions_login_time', 15);
                $lockout_duration = (int)$lockout_duration_time * MINUTE_IN_SECONDS;

                // Get current IP for IP-based lockout check
                $current_ip = self::get_user_ip_address(); // Assuming this method is accessible

                // Get excluded IPs from settings
                $excluded_ips_raw = get_option('irantheme_otp_excluded_ips', '');
                $excluded_ips_array = array_map('trim', explode(',', $excluded_ips_raw));
                $excluded_ips_array = array_filter($excluded_ips_array);


                // --- Start: Consolidated Lockout Check (User & IP) and Exclusions ---
                // If advanced security is NOT enabled OR the current IP is in the exclusion list,
                // clear any existing user/IP lockout and proceed without further lockout checks.
                if ($advanced_security_enabled != 'on' || in_array($current_ip, $excluded_ips_array)) {
                    delete_user_meta($user_id, '_otp_lockout_until');
                    // Clear attempts for excluded IP/disabled security
                    delete_user_meta($user_id, '_otp_attempts');
                    delete_transient('otp_ip_lockout_' . $current_ip);
                    // Note: No return here, allow code verification to proceed if not explicitly locked
                } else {
                    // Only if not excluded, check for existing user lockout
                    $user_lockout_until = get_user_meta($user_id, '_otp_lockout_until', true);
                    if ($user_lockout_until && time() < $user_lockout_until) {
                        $remaining_lockout = $user_lockout_until - time();
                        return new \WP_Error(
                            'user_locked_out',
                            sprintf(esc_html__('حساب کاربری شما به دلیل تلاش‌های ناموفق متعدد به مدت %s دقیقه محدود شده‌اید. لطفاً پس از %s ثانیه دیگر تلاش کنید.'), round($lockout_duration / MINUTE_IN_SECONDS), $remaining_lockout)
                        );
                    } else if ($user_lockout_until && time() >= $user_lockout_until) {
                        delete_user_meta($user_id, '_otp_lockout_until');
                        // Clear attempts after lockout expires
                        delete_user_meta($user_id, '_otp_attempts');
                    }

                    // Only if not excluded, check for existing IP lockout
                    $ip_lockout_until = get_transient('otp_ip_lockout_' . $current_ip);
                    if ($ip_lockout_until && time() < $ip_lockout_until) {
                        $remaining_lockout = $ip_lockout_until - time();
                        return new \WP_Error(
                            'ip_locked_out',
                            sprintf(esc_html__('این IP به دلیل تلاش‌های مشکوک به مدت %s دقیقه محدود شده است. لطفاً پس از %s ثانیه دیگر تلاش کنید.'), round($lockout_duration / MINUTE_IN_SECONDS), $remaining_lockout)
                        );
                    } else if ($ip_lockout_until && time() >= $ip_lockout_until) {
                        delete_transient('otp_ip_lockout_' . $current_ip);
                    }
                }
                // --- End: Consolidated Lockout Check ---


                // 1. Check if OTP is expired or not found
                if (empty($stored_otp_code) || empty($expiration_time) || time() > $expiration_time) {
                    // Clear attempts, even for excluded IPs or disabled security, as the code is no longer valid.
                    delete_user_meta($user_id, '_otp_attempts');
                    delete_user_meta($user_id, '_otp_code');
                    delete_user_meta($user_id, '_otp_expiration');

                    // Also ensure any user lockout is cleared if the code itself expired (allows a fresh start).
                    // This is important because the user might have been locked, but the code sent prior to lockout has now expired.
                    if (get_user_meta($user_id, '_otp_lockout_until', true) && time() >= get_user_meta($user_id, '_otp_lockout_until', true)) {
                        delete_user_meta($user_id, '_otp_lockout_until');
                    }

                    return new \WP_Error(
                        'otp_expired',
                        esc_html__('کد اعتبارسنجی منقضی شده است. لطفاً دوباره درخواست دهید.')
                    );
                }

                // 2. Verify submitted code
                if ($submitted_code === $stored_otp_code) {
                    // Code is correct: Perform login
                    wp_clear_auth_cookie(); // Clear any existing auth cookies
                    wp_set_current_user($user_id); // Set the current user
                    wp_set_auth_cookie($user_id, true); // Set new authentication cookie (true for Remember Me)

                    // Clear all OTP-related metadata on successful login
                    delete_user_meta($user_id, '_otp_code');
                    delete_user_meta($user_id, '_otp_expiration');
                    delete_user_meta($user_id, '_otp_attempts');
                    delete_user_meta($user_id, '_otp_lockout_until'); // Clear lockout if user logged in
                    delete_transient('otp_ip_request_times_' . $current_ip); // Clear IP request history
                    delete_transient('otp_ip_lockout_' . $current_ip); // Clear IP lockout

                    do_action('wp_login', $user->user_login, $user); // Trigger WordPress login action

                    return array(
                        'success' => true,
                        'message' => esc_html__('ورود موفقیت‌آمیز بود. در حال هدایت...'),
                        'redirect_url' => apply_filters('irantheme_otp_login_redirect', admin_url()) // Redirect to admin or custom URL
                    );
                } else {
                    // Code is incorrect: Increment attempt count
                    $current_attempts++; // Increment the failed attempts counter
                    update_user_meta($user_id, '_otp_attempts', $current_attempts); // Save the new counter

                    // If advanced security is enabled and attempts exceed max, lock the user and IP
                    if ($advanced_security_enabled && $current_attempts >= $max_attempts) {
                        // Clear codes and attempts
                        delete_user_meta($user_id, '_otp_code');
                        delete_user_meta($user_id, '_otp_expiration');
                        delete_user_meta($user_id, '_otp_attempts');

                        // Set user lockout
                        update_user_meta($user_id, '_otp_lockout_until', time() + $lockout_duration);

                        // Apply IP lockout
                        set_transient('otp_ip_lockout_' . $current_ip, time() + $lockout_duration, $lockout_duration);

                        return new \WP_Error(
                            'too_many_otp_failed_attempts_lockout',
                            sprintf(esc_html__('کد وارد شده اشتباه است. شما %s بار اشتباه کد را وارد کرده‌اید. به دلیل تلاش‌های مکرر ناموفق، حساب کاربری شما و IP شما به مدت %s دقیقه محدود شد.'), $current_attempts, round($lockout_duration / MINUTE_IN_SECONDS))
                        );
                    } else {
                        // Code is incorrect, but not yet locked out
                        $remaining_attempts = $max_attempts - $current_attempts;

                        // Do NOT clear _otp_code or _otp_expiration here.
                        // The user should be able to re-enter the code if it's still valid and attempts remain.

                        return new \WP_Error(
                            'invalid_otp',
                            sprintf(esc_html__('کد وارد شده اشتباه است. شما %s تلاش دیگر برای وارد کردن کد صحیح دارید.'), $remaining_attempts)
                        );
                    }
                }
            }

            /**
             * Reset otp login restrictions
             *
             * @author M-MALEKZADEH77
             * @since 1.0
             * @see 1.0
             */
            public static function irantheme_reset_otp_restrictions()
            {
                // Check nonce for security
                if (! isset($_POST['nonce']) || ! wp_verify_nonce($_POST['nonce'], 'otp_login_woo_nonce')) {
                    wp_send_json_error(array('message' => esc_html__('خطای امنیتی: Nonce نامعتبر.')));
                    wp_die();
                }


                $phone_number = isset($_POST['phone_number']) ? sanitize_text_field($_POST['phone_number']) : '';
                $ip_address = self::get_user_ip_address();

                // 1. Find User ID (if the user is registered in the system with this number)
                $user_query = new \WP_User_Query(array(
                    'meta_key'     => 'mobile_number',
                    'meta_value'   => $phone_number,
                    'number'       => 1,
                    'fields'       => 'ID',
                ));
                $user_ids = $user_query->get_results();
                $user_id = empty($user_ids) ? 0 : $user_ids[0];

                // 2. Clear user metadata (if user_id is valid)
                // If the user is not registered, this block will not be executed and is not needed.
                if ($user_id) {
                    delete_user_meta($user_id, '_otp_last_requested');
                    delete_user_meta($user_id, '_otp_request_times');
                }

                // 3. Clean up IP transits (this is necessary for every user, whether registered or not)
                delete_transient('otp_ip_request_times_' . $ip_address);

                wp_send_json_success('Restrictions were successfully reset.');
                wp_die();
            }

            /**
             * SMS sending With the panel selected
             *
             * @param string $phone_number Destination mobile number
             * @param string $code OTP code
             * @return bool True if successful, False otherwise
             * @author M-MALEKZADEH77
             * @since 1.0
             * @see 1.0
             */
            public static function send_sms_otp($phone_number, $code)
            {
                $selected_provider = get_option('irantheme_sms_panel_provider');
                $api_key = get_option('irantheme_sms_panel_api_key');
                $custom_message_template = get_option('irantheme_sms_panel_custom_message', 'کد فعالسازی شما: [code]');
                $final_message_text = str_replace('{OTP}', $code, $custom_message_template);

                $request_args = array(
                    'method'    => 'POST',
                    'timeout'   => 45,
                    'sslverify' => true, // Must be true in Production environment!
                );

                // Default: Error if no panel is selected
                if (empty($selected_provider)) {
                    error_log('OTP SMS: No SMS provider selected in theme options.');
                    return false;
                }

                switch ($selected_provider) {
                    case 'kavenegar':
                        $kavenegar_otp_pattern_id = get_option('irantheme_sms_panel_kavenegar_pattern');
                        $kavenegar_sender_number = get_option('irantheme_sms_panel_kavenegar_sender');
                        $kavenegar_web_service_status = get_option('irantheme_sms_panel_kavenegar_web_service_status');

                        if (empty($api_key)) {
                            error_log('KavehNegar config missing.');
                            return ['success' => false, 'message' => 'تنظیمات کاوه نگار ناقص است.'];
                        }
                        if ($kavenegar_web_service_status == 'on') {
                            if (empty($kavenegar_otp_pattern_id)) {
                                error_log('KavehNegar config missing.');
                                return ['success' => false, 'message' => 'تنظیمات کاوه نگار ناقص است.'];
                            }
                            $api_endpoint = 'https://api.kavenegar.com/v1/' . $api_key . '/verify/lookup.json';
                            $request_args['body'] = array(
                                'receptor' => (string)$phone_number,
                                'token'    => (string)$code,
                                'template' => (string)$kavenegar_otp_pattern_id,
                            );
                        } else {
                            if (empty($final_message_text) ||  empty($kavenegar_sender_number)) {
                                error_log('KavehNegar config missing.');
                                return ['success' => false, 'message' => 'تنظیمات کاوه نگار ناقص است.'];
                            }
                            $api_endpoint = 'https://api.kavenegar.com/v1/' . $api_key . '/sms/send.json';
                            $request_args['body'] = array(
                                'receptor' => (string)$phone_number,
                                'message'    => (string)$final_message_text,
                                'sender' => (string)$kavenegar_sender_number,
                            );
                        }

                        break;

                    case 'melipayamak':
                        $melipayamak_username = get_option('irantheme_sms_panel_melipayamak_username');
                        $melipayamak_password = get_option('irantheme_sms_panel_melipayamak_password');
                        $melipayamak_sender_number = get_option('irantheme_sms_panel_melipayamak_sender');
                        $melipayamak_web_service_status = get_option('irantheme_sms_panel_melipayamak_web_service_status');
                        $melipayamak_otp_pattern_id = get_option('irantheme_sms_panel_melipayamak_pattern');

                        if (empty($melipayamak_username) || empty($melipayamak_password)) {
                            $error_message = 'MeliPayamak config missing (Username, Password, or Pattern ID).';
                            error_log($error_message);
                            return ['success' => false, 'message' => 'تنظیمات ملی پیامک ناقص است (نام کاربری، رمز عبور یا شناسه الگو).'];
                        }
                        if ($melipayamak_web_service_status == 'on') {
                            if (empty($melipayamak_otp_pattern_id)) {
                                $error_message = 'MeliPayamak config missing (Username, Password, or Pattern ID).';
                                error_log($error_message);
                                return ['success' => false, 'message' => 'تنظیمات ملی پیامک ناقص است (نام کاربری، رمز عبور یا شناسه الگو).'];
                            }
                            $api_endpoint = 'https://rest.payamak-panel.com/api/SendSMS/BaseServiceNumber';
                            $request_args['body'] = array(
                                'username' => (string)$melipayamak_username,
                                'password' => (string)$melipayamak_password,
                                'text'     => (string)$code, // Just the variable part (OTP code) as a string
                                'to'       => (string)$phone_number,  // Single phone number as string
                                'bodyId'   => (int)$melipayamak_otp_pattern_id, // Pattern ID as int
                            );
                        } else {
                            if (empty($melipayamak_sender_number) || empty($final_message_text)) {
                                $error_message = 'MeliPayamak config missing (Username, Password, or Pattern ID).';
                                error_log($error_message);
                                return ['success' => false, 'message' => 'تنظیمات ملی پیامک ناقص است (نام کاربری، رمز عبور یا شناسه الگو).'];
                            }
                            $api_endpoint = 'https://rest.payamak-panel.com/api/SendSMS/SendSMS';
                            $request_args['body'] = array(
                                'username' => (string)$melipayamak_username,
                                'password' => (string)$melipayamak_password,
                                'from' => (string)$melipayamak_sender_number,
                                'to'       => (string)$phone_number,  // Single phone number as string
                                'text'     => (string)$final_message_text, // Just the variable part (OTP code) as a string
                            );
                        }
                        $request_args['headers'] = array('Content-Type' => 'application/x-www-form-urlencoded');
                        break;

                    case 'farazsms':
                        $farazsms_sender_number = get_option('irantheme_sms_panel_farazsms_sender');
                        $farazsms_otp_pattern_id = get_option('irantheme_sms_panel_farazsms_pattern');
                        $farazsms_variable_name = get_option('irantheme_sms_panel_farazsms_variable_name');
                        $farazsms_web_service_status = get_option('irantheme_sms_panel_farazsms_web_service_status');

                        if (empty($api_key)) {
                            $error_message = 'FarazSMS config missing.';
                            error_log($error_message);
                            return ['success' => false, 'message' => 'تنظیمات فراز اس ام اس ناقص است.'];
                        }
                        if ($farazsms_web_service_status == 'on') {
                            if (empty($farazsms_variable_name) || empty($farazsms_sender_number) || empty($farazsms_otp_pattern_id)) {
                                $error_message = 'FarazSMS config missing.';
                                error_log($error_message);
                                return ['success' => false, 'message' => 'تنظیمات فراز اس ام اس ناقص است.'];
                            }
                            $api_endpoint = 'https://edge.ippanel.com/v1/api/send';
                            $request_args['body'] = json_encode(array(
                                'sending_type' => 'pattern',
                                'from_number' => (string)$farazsms_sender_number,
                                'code' => (string)$farazsms_otp_pattern_id,
                                'recipients' => array($phone_number),
                                'params' => array(
                                    $farazsms_variable_name => $code
                                )
                            ));
                        } else {
                            if (empty($final_message_text) || empty($farazsms_sender_number)) {
                                $error_message = 'FarazSMS config missing.';
                                error_log($error_message);
                                return ['success' => false, 'message' => 'تنظیمات فراز اس ام اس ناقص است.'];
                            }
                            $api_endpoint = 'https://edge.ippanel.com/v1/api/send';
                            $request_args['body'] = json_encode(array(
                                'sending_type' => 'webservice',
                                'from_number' => (string)$farazsms_sender_number,
                                'message' => (string)$final_message_text,
                                'params' => array(
                                    'recipients' => array($phone_number),
                                )
                            ));
                        }
                        $request_args['headers'] = array(
                            'Content-Type'  => 'application/json',
                            'Authorization' => $api_key,
                        );
                        break;

                    case 'smsir':
                        $smsir_otp_pattern_id = get_option('irantheme_sms_panel_smsir_pattern');
                        $smsir_variable_name = get_option('irantheme_sms_panel_smsir_variable_name');
                        $smsir_sender_number = get_option('irantheme_sms_panel_smsir_sender');
                        $smsir_web_service_status = get_option('irantheme_sms_panel_smsir_web_service_status');

                        if (empty($api_key)) {
                            $error_message = 'SMS.ir config missing (API Key or Template ID).';
                            error_log($error_message);
                            return ['success' => false, 'message' => 'تنظیمات اس‌ام‌اس.آی‌آر ناقص است (API Key یا شناسه الگو).'];
                        }

                        if ($smsir_web_service_status == 'on') {
                            if (empty($smsir_otp_pattern_id) || empty($smsir_variable_name)) {
                                $error_message = 'SMS.ir config missing (API Key or Template ID).';
                                error_log($error_message);
                                return ['success' => false, 'message' => 'تنظیمات اس‌ام‌اس.آی‌آر ناقص است (API Key یا شناسه الگو).'];
                            }
                            // Receive SMS.ir token
                            $api_endpoint = 'https://api.sms.ir/v1/send/verify';
                            $request_args['body'] = json_encode(array(
                                'mobile'     => $phone_number,
                                'templateId' => (int)$smsir_otp_pattern_id,
                                'parameters' => array(
                                    array(
                                        'name'  => (string)$smsir_variable_name, // نام متغیر در الگوی شما در پنل SMS.ir (مثلاً %code%)
                                        'value' => (string)$code
                                    )
                                )
                            ));
                        } else {
                            if (empty($smsir_sender_number) || empty($final_message_text)) {
                                $error_message = 'SMS.ir config missing (API Key or Template ID).';
                                error_log($error_message);
                                return ['success' => false, 'message' => 'تنظیمات اس‌ام‌اس.آی‌آر ناقص است (API Key یا شناسه الگو).'];
                            }
                            // Receive SMS.ir token
                            $api_endpoint = 'https://api.sms.ir/v1/send/bulk';
                            $request_args['body'] = json_encode(array(
                                'lineNumber'     => (int)$smsir_sender_number,
                                'MessageText'     => (string)$final_message_text,
                                'mobiles'     => array((string)$phone_number)
                            ));
                        }

                        $request_args['headers'] = array(
                            'Content-Type' => 'application/json',
                            'Accept'       => 'text/plain', // این هدر را هم طبق مستندات اضافه می‌کنیم
                            'x-api-key'    => $api_key,
                        );
                        break;

                    default:
                        error_log('No SMS provider selected or invalid provider.');
                        return false;
                }

                // If endpoint is not defined (e.g. in case of initial configuration error)
                if (! isset($api_endpoint) || empty($api_endpoint)) {
                    $error_message = 'SMS API: Endpoint not defined for selected provider.';
                    error_log($error_message);
                    return ['success' => false, 'message' => 'خطا: آدرس API برای ارائه دهنده انتخاب شده تعریف نشده است.'];
                }

                // Send final request to SMS panel API
                $response = wp_remote_post($api_endpoint, $request_args);

                if (is_wp_error($response)) {
                    $error_message = 'SMS API Call Error: ' . $response->get_error_message();
                    error_log($error_message);
                    return ['success' => false, 'message' => 'خطا در برقراری ارتباط با سرویس پیامک: ' . $response->get_error_message()];
                }

                $body = wp_remote_retrieve_body($response);
                $data = json_decode($body, true);

                if ($selected_provider == 'melipayamak') {
                    // اگر پاسخ عدد بود (مثلاً شناسه پیامک یا کد خطا)
                    if (is_numeric($body)) {
                        $data = (int)$body; // تبدیل به عدد صحیح
                    }
                    // در غیر این صورت، $data همان آرایه JSON دیکد شده خواهد بود.
                }

                $check_result = self::check_sms_provider_response($selected_provider, $data);

                if ($check_result['success']) {
                    return ['success' => true, 'message' => 'پیامک با موفقیت ارسال شد.'];
                } else {
                    $error_message = $check_result['message'];
                    $error_code = $check_result['code'];

                    update_option('irantheme_sms_panel_log', (sprintf(
                        'ارسال پیامک ناموفق بود. ارائه دهنده: %s، کد خطا: %s، پیام: %s زمان: %s',
                        $selected_provider,
                        $error_code,
                        $error_message,
                        date_i18n('Y-m-d H:i:s')
                    )));
                    // SMS sent failed
                    return false;
                }
            }

            /**
             * Checking and returning the SMS service provider's response
             *
             * @param string $selected_provider selected provider
             * @param object $data ptovider response
             * @return bool|array True if successful, array of error data otherwise
             * @author M-MALEKZADEH77
             * @since 1.0
             * @see 1.0
             */
            public static function check_sms_provider_response($selected_provider, $data)
            {
                switch ($selected_provider) {
                    case 'kavenegar':
                        if (isset($data['return']['status']) && $data['return']['status'] == 200) {
                            return ['success' => true];
                        } else {
                            // Kaveh Negar: StatusCode and Message
                            $error_code = isset($data['return']['status']) ? $data['return']['status'] : 'unknown_kavenegar_code';
                            $error_message = isset($data['return']['message']) ? $data['return']['message'] : 'خطای نامشخص از کاوه نگار';
                            return [
                                'success' => false,
                                'code'    => $error_code,
                                'message' => $error_message . ' (کد: ' . $error_code . ')',
                                'raw_data' => $data
                            ];
                        }
                        break;
                    case 'melipayamak':
                        if ((isset($data['Value']) && isset($data['RetStatus'])) && ($data['RetStatus'] == 1 && $data['StrRetStatus'] === 'Ok')) {
                            return ['success' => true];
                        } else {
                            $message = self::get_melipayamak_error_message($data['Value']);
                            $code = $data['Value'] ?? 'N/A';
                            return [
                                'success' => false,
                                'code'    => $code,
                                'message' => $message . ' (کد: ' . $code . ')',
                                'raw_data' => $data
                            ];
                        }
                        break;
                    case 'farazsms':
                        if (isset($data['meta']['status']) && $data['meta']['status'] == true) {
                            return ['success' => true];
                        } else {
                            // Faraz SMS: status and message
                            $error_code = isset($data['meta']['message_code']) ? $data['meta']['message_code'] : 'unknown_farazsms_code';
                            $error_message = isset($data['meta']['message']) ? $data['meta']['message'] : 'خطای نامشخص از فراز اس ام اس';
                            return [
                                'success' => false,
                                'code'    => $error_code,
                                'message' => $error_message . ' (کد: ' . $error_code . ')',
                                'raw_data' => $data
                            ];
                        }
                        break;
                    case 'smsir':
                        if (is_array($data) && isset($data['status'])) {
                            if ((is_array($data) && isset($data['status'])) && $data['status'] == 1) {
                                return ['success' => true];
                            } else {
                                $message = $data['message'] ?? 'خطای ناشناخته از اس‌ام‌اس.آی‌آر';
                                $code = $data['status'];
                                return [
                                    'success' => false,
                                    'code'    => $code,
                                    'message' => $message . ' (کد: ' . $code . ')',
                                    'raw_data' => $data
                                ];
                            }
                        }
                        break;
                    default:
                        return [
                            'success' => false,
                            'code'    => 'unsupported_provider',
                            'message' => 'پنل پیامکی انتخاب شده پشتیبانی نمی‌شود.'
                        ];
                        break;
                }
            }

            /**
             * Processing AJAX requests for OTP code validation from the frontend
             * @author M-MALEKZADEH77
             * @since 1.0
             * @see 1.0
             */
            public static function irantheme_ajax_verify_otp_woo()
            {
                // Check Nonce for security (wp_verify_nonce خودش slashes رو مدیریت می‌کنه)
                if (! isset($_POST['nonce']) || ! wp_verify_nonce($_POST['nonce'], 'otp_login_woo_nonce')) {
                    wp_send_json_error(array('message' => esc_html__('خطای امنیتی: Nonce نامعتبر.')));
                    wp_die();
                }


                // sanitize_text_field خودش unslash می‌زنه
                $phone_number = isset($_POST['phone_number']) ? sanitize_text_field($_POST['phone_number']) : '';
                $otp_code     = isset($_POST['otp_code']) ? sanitize_text_field($_POST['otp_code']) : '';

                // Validate inputs before proceeding
                if (empty($phone_number) || empty($otp_code)) {
                    wp_send_json_error(array('message' => esc_html__('شماره موبایل و کد اعتبارسنجی نمی‌توانند خالی باشند.')));
                    wp_die();
                }

                // Call the updated verify_otp_and_login method
                $result = self::verify_otp_and_login($phone_number, $otp_code);

                if (is_wp_error($result)) {
                    // Handle different error types with specific messages if needed
                    wp_send_json_error(array(
                        'message' => $result->get_error_message(),
                        'code'    => $result->get_error_code(),
                    ));
                    wp_die();
                } else {
                    wp_send_json_success(array(
                        'message'      => $result['message'],
                        'redirect_url' => $result['redirect_url']
                    ));
                    wp_die();
                }
            }

            /**
             * Processes AJAX request to request OTP code on WooCommerce login page.
             * This function comprehensively handles errors and sends appropriate responses to JavaScript.
             * @author M-MALEKZADEH77
             * @since 1.0
             * @see 1.0
             */
            public static function irantheme_ajax_request_otp_woo()
            {
                // 1. Check Nonce for security (wp_verify_nonce خودش slashes رو مدیریت می‌کنه)
                if (! isset($_POST['nonce']) || ! wp_verify_nonce($_POST['nonce'], 'otp_login_woo_nonce')) {
                    wp_send_json_error(array('message' => esc_html__('خطای امنیتی: Nonce نامعتبر.')));
                    wp_die();
                }


                // 2. Receive and prepare mobile number (sanitize_text_field خودش unslash می‌زنه)
                $phone_number = isset($_POST['phone_number']) ? sanitize_text_field($_POST['phone_number']) : '';

                // 3. Initial validation: mobile number not empty
                if (empty($phone_number)) {
                    wp_send_json_error(array('message' => esc_html__('لطفاً شماره موبایل خود را وارد کنید.')));
                    wp_die();
                    // Stop executing the function after sending an error
                    return;
                }

                // 4. Mobile Number Format Validation (regex)
                // This regex only validates 11-digit mobile numbers that start with '09'.
                if (! preg_match('/^(0?9[0-9]{9})$/', $phone_number)) {
                    wp_send_json_error(array('message' => esc_html__('شماره موبایل وارد شده معتبر نیست. لطفاً با فرمت 09xxxxxxxxx وارد کنید.', 'your-textdomain')));
                    wp_die();
                    // Stop executing the function after sending an error
                    return;
                }

                // 5. Call the OTP request logic (request_otp_for_login)
                // This function is responsible for finding the user, generating the OTP, and sending the SMS.
                $result = self::request_otp_for_login($phone_number);

                // 6. Handling the result returned from request_otp_for_login
                // We check whether result is a WP_Error object.
                if (is_wp_error($result)) {
                    $error_code = $result->get_error_code();
                    $error_message = $result->get_error_message();
                    // If the error code indicates a need to redirect to registration.
                    if ($error_code === 'user_not_found_redirect_to_register') {
                        wp_send_json_error(array(
                            'message' => $error_message,
                            'redirect_to_register' => true // This flag is used by JavaScript for redirects.
                        ));
                        wp_die();
                        return;
                    }
                    // Handle other errors returned from request_otp_for_login
                    // This includes errors like 'sms_send_failed' or any other error that
                    // request_otp_for_login might return.
                    else {
                        wp_send_json_error(array(
                            'code' => $error_code,
                            'message' => $error_message
                        ));
                        wp_die();
                        return;
                    }
                }
                // 7. If there were no errors and the operation was successful
                else {
                    // Send a success response to JavaScript
                    wp_send_json_success(array(
                        'message' => $result['message'], // The message returned from request_otp_for_login
                        'current_ip' => $result['current_ip'], // The user ID to which the code was sent.
                        'code' => $result['code'],
                    ));
                    wp_die();
                }
            }

            /**
             * Send test sms for check panel settings
             * @author M-MALEKZADEH77
             * @since 1.0
             * @see 1.0
             */
            public static function irantheme_ajax_send_test_sms()
            {
                // sanitize_text_field خودش unslash می‌زنه
                $phone_number = isset($_POST['phone_number']) ? sanitize_text_field($_POST['phone_number']) : '';

                // Phone number validation
                if (empty($phone_number)) {
                    wp_send_json_error(array('message' => 'لطفاً شماره موبایل تست را وارد کنید.'));
                    wp_die();
                }
                if (! preg_match('/^(0?9[0-9]{9})$/', $phone_number)) {
                    wp_send_json_error(array('message' => 'شماره موبایل تست نامعتبر است.'));
                    wp_die();
                }
                // Generate a random code for testing
                $test_code = self::generate_otp_code();

                // Send a test SMS with a custom code
                $sms_result = self::send_sms_otp($phone_number, $test_code);

                if ($sms_result['success']) {
                    wp_send_json_success(array('message' => $sms_result['message']));
                    wp_die();
                } else {
                    wp_send_json_error(array('message' => $sms_result['message'] . ' لطفاً تنظیمات پنل پیامک را بررسی کنید و از اتصال اینترنت سرور مطمئن شوید.'));
                    wp_die();
                }
            }

            private static function get_melipayamak_error_message($code)
            {
                switch ((int)$code) {
                    case -1:
                        return 'محدودیت تعداد ارسال روزانه';
                    case -2:
                        return 'محدودیت تعداد ارسال ساعت';
                    case -3:
                        return 'محدودیت تعداد ارسال دقیقه';
                    case -4:
                        return 'اعتبار کافی نیست';
                    case -5:
                        return 'شماره فرستنده معتبر نیست';
                    case -6:
                        return 'متن خالی است';
                    case -7:
                        return 'شماره گیرنده نامعتبر است';
                    case -8:
                        return 'قالب (BodyId) نامعتبر است یا فعال نیست';
                    case -9:
                        return 'عدم تایید الگو';
                    case -10:
                        return 'نام کاربری یا رمز عبور اشتباه است';
                    case -11:
                        return 'پیام تکراری (برای جلوگیری از اسپم)';
                    case -12:
                        return 'خطای داخلی سامانه';
                    case -13:
                        return 'شماره گیرنده مسدود شده است';
                    case -14:
                        return 'شماره فرستنده مسدود شده است';
                    case -15:
                        return 'تعداد متغیرها در الگو مطابقت ندارد';
                    case -16:
                        return 'شماره گیرنده خارج از سرویس دهی است';
                    default:
                        return 'خطای نامشخص از ملی پیامک (کد: ' . $code . ')';
                }
            }

            /**
             * Return array of name and label from product attributes (for Product edit page in woo)

             * @return array
             * @author M-MALEKZADEH
             * @since v1.0
             * @see v1.0
             */
            public static function get_all_product_attributes()
            {
                if (!isset($_POST['product_id']) || empty($_POST['product_id'])) {
                    wp_send_json_error('آی‌دی محصول ارسال نشده است.');
                }

                // absint خودش با slashes کار می‌کنه
                $product_id = absint($_POST['product_id']);
                $product = wc_get_product($product_id);

                if (!$product) {
                    wp_send_json_error('محصول یافت نشد.');
                }

                $attributes_results = array();
                $attributes = $product->get_attributes();

                foreach ($attributes as $attribute_name => $attribute_object) {
                    $label = '';
                    $slug = '';

                    // For taxonomy-based features (global)
                    if ($attribute_object->is_taxonomy()) {
                        $taxonomy = $attribute_object->get_taxonomy_object();
                        // Get displayable label
                        $label = $taxonomy ? $taxonomy->attribute_label : $attribute_name;
                        $slug = $attribute_name;
                    } else {
                        // For custom properties
                        $label = $attribute_object->get_name();
                        $slug = sanitize_title($label);
                    }
                    if ($label != '' && $slug != '') {
                        $attributes_results[] = array(
                            'label' => $label,
                            'value' => $slug,
                            'decoded_label' => rawurldecode($label),
                            'decoded_value' => rawurldecode($slug),
                            'is_taxonomy' => $attribute_object->is_taxonomy(),
                            'is_visible' => $attribute_object->get_visible(),
                        );
                    }
                }

                wp_send_json_success($attributes_results);
            }
        }


        $settings_options_ajax = new Irantheme_Settings_Options_Ajax();
    }
