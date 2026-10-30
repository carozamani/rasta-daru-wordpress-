<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;


if (!class_exists('Irantheme_Ajax_Woocommerce')) {

    /**
     * Class for woocommerce ajax triggers
     *
     * @package Wordpress
     * @subpackage irantheme
     * @since irantheme - custom fields
     * @author iranianclever
     * @see v1.0
     *
     */

    class Irantheme_Ajax_Woocommerce
    {
        /**
         * Constructor
         * @return void
         * @param void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        function __construct()
        {
            // Add action ajax to ...
            add_action('wp_ajax_get_woocommerce_ajax_products', array($this, 'get_woocommerce_ajax_products'));
            add_action('wp_ajax_nopriv_get_woocommerce_ajax_products', array($this, 'get_woocommerce_ajax_products'));

            // AJAX handler for getting cart total
            add_action('wp_ajax_get_cart_total_ajax', array($this, 'get_cart_total_ajax'));
            add_action('wp_ajax_nopriv_get_cart_total_ajax', array($this, 'get_cart_total_ajax'));
        }


        /**
         * Return cart total ajax
         * @return void
         * @param void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public function get_cart_total_ajax()
        {
            if (is_woocommerce_activated()) {
                $cart_total = WC()->cart->get_cart_total();
                wp_send_json_success(array('total' => $cart_total));
            }

            wp_die();
        }


        /**
         * Return products of ajax requests with category data
         * @return void
         * @param void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public function get_woocommerce_ajax_products()
        {
            // Holder fields values (Fake)
            $fields_values = array();
            // Get filter type
            $filter_type = $_POST['filter_type'];
            // Get filter data
            $filter_data = $_POST['filter_data'];
            // Get filter data all if existed
            $filter_data_all = $_POST['filter_data_all'];
            // Get product number count
            $product_number = $_POST['product_number'];
            // Set product count number fields in fields_values
            $fields_values['product_count_number'] = $product_number;
            // Get product options
            $product_options = $_POST['product_options'];
            $product_options_array = explode('|', $product_options);

            // Fetch product options and convert array to string
            $product_features = $product_options_array[0];
            $product_features_array = explode(',', $product_features);
            $base_color = $product_options_array[1];
            $details_color_select = $product_options_array[2];
            $details_color_select2 = $product_options_array[3];
            $product_discount_checkbox = $product_options_array[4];
            $product_total_sales_checkbox = $product_options_array[5];
            $product_discount_schedule_checkbox = $product_options_array[6];
            $product_color_attribute_checkbox = $product_options_array[7];
            $product_image_options_class = $product_options_array[8];
            $product_image_extra_options_style_class = $product_options_array[9];
            $product_border_class = $product_options_array[10];
            $product_image_hover_changer_class = $product_options_array[11];
            $product_image_hover_mode_class = $product_options_array[12];
            $product_hover_mode_class = $product_options_array[13];
            $post_content_align = $product_options_array[14];
            $product_shadow_class = $product_options_array[15];
            $slider_mode_column = $product_options_array[16];
            $product_excerpt_count = $product_options_array[17];
            $product_extra_options_checkbox = $product_options_array[18];
            $aos_delay = $product_options_array[19];
            $aos_delay = intval($aos_delay);
            $data_load_attr = $product_options_array[20];
            $data_load_duration = $product_options_array[21];
            $border_radius_range = $product_options_array[22];
            $product_padding_class = $product_options_array[23];
            $product_transparent_class = $product_options_array[24];

            if ($filter_type == 'selected') {
                $products_post = Irantheme_Section_Utils::get_product_content($fields_values, $filter_data);
                while ($products_post->have_posts()) :
                    $products_post->the_post();
                    global $product;
                    // Get product item
                    Irantheme_Section_Woocommerce::get_product_item($product, $product_features_array, $base_color, $details_color_select, $details_color_select2, $product_discount_checkbox, $product_total_sales_checkbox, $product_discount_schedule_checkbox, $product_color_attribute_checkbox, $product_image_options_class, $product_image_extra_options_style_class, $product_border_class, $product_image_hover_changer_class, $product_image_hover_mode_class, $product_hover_mode_class, $post_content_align, $product_shadow_class, $slider_mode_column, $product_excerpt_count, $product_extra_options_checkbox, $aos_delay, $data_load_attr, $data_load_duration, $border_radius_range, $product_padding_class, $product_transparent_class);

                // Increase aos delay for next item
                // $aos_delay += 200;
                endwhile;
                wp_reset_postdata();
            } else if ($filter_type == 'category') {
                // Check all category or one category
                if ($filter_data == 'all') {
                    $fields_values['product_category_selection'] = $filter_data_all;
                    $products_post = Irantheme_Section_Utils::get_product_content($fields_values, 'category');
                } else {
                    $fields_values['product_once_category_selection'] = $filter_data;
                    $products_post = Irantheme_Section_Utils::get_product_content($fields_values, 'once_category');
                }
                while ($products_post->have_posts()) :
                    $products_post->the_post();
                    global $product;
                    // Get product item
                    Irantheme_Section_Woocommerce::get_product_item($product, $product_features_array, $base_color, $details_color_select, $details_color_select2, $product_discount_checkbox, $product_total_sales_checkbox, $product_discount_schedule_checkbox, $product_color_attribute_checkbox, $product_image_options_class, $product_image_extra_options_style_class, $product_border_class, $product_image_hover_changer_class, $product_image_hover_mode_class, $product_hover_mode_class, $post_content_align, $product_shadow_class, $slider_mode_column, $product_excerpt_count, $product_extra_options_checkbox, $aos_delay, $data_load_attr, $data_load_duration, $border_radius_range, $product_padding_class, $product_transparent_class);

                // Increase aos delay for next item
                // $aos_delay += 200;
                endwhile;
                wp_reset_postdata();
            }
            wp_die();
        }
    } // End Class
    $woocommerce_ajax = new Irantheme_Ajax_Woocommerce();
} // End if class exists statement
