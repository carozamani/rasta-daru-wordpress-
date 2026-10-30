<?php

namespace Irantheme_Dev;

use WP_Query;

if (!defined('ABSPATH')) exit;


if (!class_exists('Irantheme_Custom_Fields')) {

    /**
     * Create custom fields for post types service
     *
     * @package Wordpress
     * @subpackage irantheme
     * @since irantheme - custom fields
     * @author iranianclever
     * @see v1.0
     *
     */

    class Irantheme_Custom_Fields
    {
        public $prefix;
        public $post_type;
        protected static $textdomain = IRANTHEME_TEXTDOMAIN;
        public $name;
        public $title;
        public $meta_box_section;
        public $capability;
        public $context;
        public $priority;


        /**
         * Constructor
         * @return void
         * @param void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public function __construct($name, $title, $context = 'normal', $priority = 'high')
        {
            $this->prefix = 'irantheme_';
            $this->name = $name;
            $this->title = $title;
            $this->post_type = array($this->name);
            $this->meta_box_section = sprintf('irantheme_custom_fields_%s_panel_ajax', $this->name);
            $this->capability = 'edit_pages';
            $this->context = $context;
            $this->priority = $priority;

            add_action('admin_menu', array($this, 'create_custom_fields'));
            add_action('save_post', array($this, 'save_custom_fields'), 1, 2);
            // Comment this line out if you want to keep default custom fields meta box
            add_action('do_meta_boxes', array($this, 'remove_default_custom_fields'), 10, 3);
        }


        /**
         * Return array of title and posts ids from all post type
         * @return array
         * @param string,string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_all_posts($post_type, $post_count = -1)
        {
            // Holder of results
            $post_info_results = array();

            // Query post
            $post_query = new WP_Query(array(
                'post_type'              => $post_type,
                'post_status'            => 'publish',
                'posts_per_page'         => $post_count,
                'no_found_rows'          => true,
                'update_post_meta_cache' => false,
                'update_post_term_cache' => false,
                'ignore_sticky_posts'    => true,
            ));

            if (!empty($post_query->posts)) {
                foreach ($post_query->posts as $post) {
                    // Skip password-protected posts (matches get_the_title() behavior)
                    if (!empty($post->post_password)) {
                        continue;
                    }

                    // Apply the_title filter for full backward compatibility
                    $raw_title = apply_filters('the_title', $post->post_title, $post->ID);

                    $title = wp_trim_words($raw_title, 5) . ' (شناسه: ' . $post->ID . ')';

                    $post_info_results[$post->ID] = $title;
                }
            }

            return $post_info_results;
        }


        /**
         * Return array of nav menus locations
         * @return array
         * @param void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_all_menus($have_none = false)
        {
            $nav_menus = wp_get_nav_menus();
            $nav_menus_result = array();
            if ($have_none) {
                // Create array post info
                $temp = array(
                    'none' => __('بدون منو', self::$textdomain),
                );
                // Push temp with post info
                $nav_menus_result = array_replace($nav_menus_result, $temp);
            }
            foreach ($nav_menus as $menu) {
                $title = wp_trim_words($menu->name, 5);
                // Create array post info
                $temp = array(
                    $menu->term_id => $title,
                );
                // Push temp with post info
                $nav_menus_result = array_replace($nav_menus_result, $temp);
            }
            return $nav_menus_result;
        }


        /**
         * Return array of sidebars in widgets
         * @return array
         * @param void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_all_sidebars()
        {
            $result_array = array();
            foreach (array_keys(get_option('sidebars_widgets')) as $sidebar_id) {
                // Create array
                $temp = array(
                    $sidebar_id => __('شناسه سایدربار : ', self::$textdomain) . $sidebar_id,
                );
                // Push temp
                $result_array = array_replace($result_array, $temp);
            }
            return $result_array;
        }


        /**
         * Return array of title and count post from category
         * @return array
         * @param string,string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_all_categories($post_type, $post_count = -1)
        {
            // Holder of results
            $cate_info_results = array();

            // Query post
            $categories = get_categories(array(
                'taxonomy' => $post_type,
                'orderby' => 'name',
                'show_count' => 1,
                "hide_empty" => false,
            ));

            foreach ($categories as $cate) {
                $title = wp_trim_words($cate->name, 5) . ' (' . $cate->count . ' عدد)';
                // Create array post info
                $temp = array(
                    $cate->term_id => $title,
                );
                // Push temp with post info
                $cate_info_results = array_replace($cate_info_results, $temp);
            }
            return $cate_info_results;
        }


        /**
         * Return custom fields (Fields)
         * @return array
         * @param void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public function get_fields()
        {
            // Custom fields
            $custom_fields = array();
            return $custom_fields;
        }


        /**
         * Remove the default Custom Fields meta box
         */
        public function remove_default_custom_fields($type, $context, $post)
        {
            foreach (array('normal', 'advanced', 'side') as $context) {
                foreach ($this->post_type as $post_type) {
                    remove_meta_box('custompost', $post_type, $context);
                }
            }
        }


        /**
         * Create the new Custom Fields meta box
         */
        public function create_custom_fields()
        {
            if (function_exists('add_meta_box')) {
                foreach ($this->post_type as $post_type) {
                    add_meta_box($this->meta_box_section, sprintf('تنظیمات %s', $this->title), array($this, 'display_custom_fields'), $post_type, $this->context, $this->priority);
                }
            }
        }


        /**
         * Display the new Custom Fields meta box
         */
        public function display_custom_fields()
        {
            global $post;
?>
            <div class="irantheme-settings" data-settings="customFields">
                <?php
                wp_nonce_field($this->meta_box_section, 'irantheme_custom_fields_wpnonce', false, true);
                foreach ($this->get_fields() as $customField) {
                    // Check scope
                    $output = false;
                    if ($post->post_type == $this->name)
                        $output = true;
                    // Check capability
                    if (!current_user_can($this->capability, $post->ID))
                        $output = false;
                    // Output if allowed
                    if ($output) { ?>
                        <div class="form-field form-required mb-2">
                            <b class="irantheme-heading-option"><?php _e($customField['label']); ?></b>
                            <?php
                            // Combination prefix and custom field name to generate register name
                            $register_name = $customField['id'];
                            // Get post meta value
                            $post_meta_value = get_post_meta($post->ID, $register_name, true);
                            echo Irantheme_Settings::display_fields($customField, $register_name, $post_meta_value);
                            ?>
                        </div>
                <?php
                    }
                } ?>
            </div>
<?php
        }


        /**
         * Save the new Custom Fields values
         */
        public function save_custom_fields($post_id, $post)
        {
            if (!isset($_POST['irantheme_custom_fields_wpnonce']) || !wp_verify_nonce($_POST['irantheme_custom_fields_wpnonce'], $this->meta_box_section))
                return;
            if (!current_user_can('edit_post', $post_id))
                return;
            if (!in_array($post->post_type, $this->post_type))
                return;
            foreach ($this->get_fields() as $customField) {
                $register_name = $customField['id'];
                if (current_user_can($this->capability, $post_id)) {
                    if (isset($_POST[$register_name]) && trim($_POST[$register_name])) {
                        $value = $_POST[$register_name];
                        // Auto-paragraphs for any tinymce
                        if ($customField['type'] == "tinymce") $value = wpautop($value);
                        update_post_meta($post_id, $register_name, $value);
                    } else {
                        delete_post_meta($post_id, $register_name);
                    }
                }
            }
        }
    } // End Class
} // End if class exists statement
