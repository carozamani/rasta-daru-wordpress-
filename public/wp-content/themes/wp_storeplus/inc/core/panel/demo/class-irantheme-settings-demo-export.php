<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;


if (!class_exists('Irantheme_Settings_Demo_Export')) {

    /**
     * Irantheme demo export
     * Configuration of demo export
     * 
     * @package Wordpress
     * @subpackage Storeplus
     * @since v1.0
     * @author iranianclever
     * @see v1.0
     * 
     * Demo export
     */
    class Irantheme_Settings_Demo_Export
    {
        // Constructor initialize
        public function __construct()
        {
            // self::get_all_widgets_blocks();
        }


        /**
         * Get all sidebars for demo export xml
         * @param void
         * @return array
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_all_widgets_blocks()
        {
            $all_blocks_widgets = array(
                'item_name' => 'widget_item',
                'fields' => array(
                    array(
                        'widget_block' => self::add_cdata(wp_json_encode(get_option('widget_block')))
                    ),
                    array(
                        'sidebars_widgets' => self::add_cdata(wp_json_encode(get_option('sidebars_widgets')))
                    )
                )
            );
            return $all_blocks_widgets;
        }


        /**
         * Get all sections of pages for demo export xml
         * @param void
         * @return array
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_all_nav_menus()
        {
            // Holder of all pages with necessary fields
            $all_nav_menus = array(
                'item_name' => 'nav_menu_item',
                'fields' => array()
            );
            // Get all published pages
            $nav_menus = wp_get_nav_menus();
            $counter = 0;
            foreach ($nav_menus as $nav_menu) {
                // Convert all nav menu items objects to array (Convert to json is require for all objects)
                $nav_menu_items = json_decode(wp_json_encode(wp_get_nav_menu_items($nav_menu->term_id)), true);
                $index = 0;
                foreach ($nav_menu_items as $item) {
                    $item_id = $item['ID'];
                    $options = array(
                        'irantheme_options' => array()
                    );
                    // Get nav menu options fields
                    foreach (Irantheme_Nav_Menu::admin_fields_elements_nav_menus() as $item_field) {
                        // Get field name
                        $item_field_name = Irantheme_Nav_Menu::get_name_post_meta_mixed($item_field['id'], $item_id);
                        // Get field value
                        $item_field_value = get_post_meta($item_id, $item_field_name, true);
                        // Check attachment type for assign url value
                        if ($item_field['type'] == 'image' && $item_field_value) {
                            $item_field_value = wp_get_attachment_url($item_field_value);
                        }
                        // Apply htmlspecialchars to text fields
                        if (in_array($item_field['type'], array('text', 'textarea', 'text_editor', 'text_secret'))) {
                            $item_field_value = htmlspecialchars($item_field_value, ENT_XML1, 'UTF-8');
                        }
                        // Merge new field value
                        $options['irantheme_options'] = array_merge($options['irantheme_options'], array(
                            $item_field['id'] => $item_field_value,
                        ));
                    }
                    // Merge options of per item nav menus
                    $nav_menu_items[$index] = array_merge($nav_menu_items[$index], $options);
                    $index++;
                }
                $nav_menu_items_json = wp_json_encode($nav_menu_items);
                // Get menu locations of current menu
                $menu_locations = array();
                foreach (get_nav_menu_locations() as $key => $value) {
                    if ($value == $nav_menu->term_id) {
                        array_push($menu_locations, $key);
                    }
                }
                // Holder of current post
                $nav_menu_item = array(
                    'id' => $nav_menu->term_id,
                    'title' => htmlspecialchars($nav_menu->name, ENT_XML1, 'UTF-8'),
                    'menu_locations' => implode(',', $menu_locations),
                    'nav_menu_item_' . $counter => $nav_menu_items_json
                );
                // Push current post to all posts holder
                array_push($all_nav_menus['fields'], $nav_menu_item);
                $counter++;
            }
            return $all_nav_menus;
        }


        /**
         * Get all sections of pages for demo export xml
         * @param void
         * @return array
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_all_pages_sections($page_id = null)
        {
            // Holder of all pages with necessary fields
            $all_pages_header_settings = array(
                'item_name' => 'page_sections_item',
                'fields' => array()
            );
            // Args of wp query
            $args = array(
                'post_type'        => 'page',
                'post_status' => 'publish',
            );
            // Get all published pages
            $pages = get_pages($args);
            foreach ($pages as $page) {
                $page_config_fields = Irantheme_Page_Builder_Ajax::get_page_settings($page->ID, 'page_config', 'page_config_fields');
                // Get page is customizable
                $page_customizable = $page_config_fields['irantheme_page_customizable_checkbox'] ?? 'off';
                // Check page builder is enabled
                if ($page_customizable != 'on') {
                    continue;
                }

                // Check page_id parameter for page xml backup
                if ($page_id == null || $page->ID == $page_id) {
                    // Holder of current post
                    $page_sections_item = array(
                        'title' => htmlspecialchars($page->post_title, ENT_XML1, 'UTF-8'),
                    );
                    // Get meta data sections of current page
                    $associative_fields = Irantheme_Page_Builder_Ajax::get_page_settings($page->ID, 'section');
                    // Check visual field value
                    if ($associative_fields && isset($associative_fields['sections_items'])) {
                        foreach ($associative_fields['sections_items'] as &$section) {
                            $data_value = $section['data_value'];
                            // Get section fields
                            $sections_fields = Irantheme_Page_Builder_Ajax::get_function_fields($data_value, false);
                            foreach ($sections_fields as $fields_settings) {
                                foreach ($fields_settings['fields'] as $fields) {
                                    // Get section field value
                                    $option_value = isset($section['section_fields'][$fields['id']]) ? $section['section_fields'][$fields['id']] : '';
                                    // Check option value for getting attachment url
                                    if ($option_value) {
                                        if ($fields['type'] == 'image' || $fields['type'] == 'video') {
                                            $section['section_fields'][$fields['id']] = wp_get_attachment_url($option_value);
                                        } elseif ($fields['type'] == 'hidden_json') {
                                            $objects_fields = Irantheme_Page_Builder_Ajax::get_object_fields($data_value);
                                            // Check hidden json and convert attachment fields to url
                                            // objects_json is now an ARRAY, so we don't need to decode it
                                            $objects_json = Irantheme_JSON_Helper::normalize_objects($option_value);
                                            $objects_holder = array();
                                            if (is_array($objects_json)) {
                                                foreach ($objects_json as &$object) {
                                                    if (!isset($object['fields']) || !is_array($object['fields'])) {
                                                        $object['fields'] = array();
                                                    }
                                                    foreach ($objects_fields as $objects_sample) {
                                                        foreach ($objects_sample['fields'] as $object_sample) {
                                                            if ($object_sample['type'] == 'image' || $object_sample['type'] == 'video') {
                                                                if (isset($object['fields'][$object_sample['id']])) {
                                                                    $object['fields'][$object_sample['id']] = wp_get_attachment_url($object['fields'][$object_sample['id']]);
                                                                }
                                                            }
                                                        }
                                                    }
                                                    array_push($objects_holder, $object);
                                                }
                                                unset($object);
                                            }
                                            $section['section_fields'][$fields['id']] = wp_json_encode($objects_holder);
                                        }
                                    }
                                }
                            }
                        }
                        unset($section);
                    } else {
                        continue;
                    }
                    // Add cdata to associative fields
                    $associative_fields = self::add_cdata(wp_json_encode($associative_fields));
                    $associative_fields = str_replace('&nbsp;', '\u00A0', $associative_fields);
                    $page_sections_item['sections'] = $associative_fields;
                    // Push current post to all posts holder
                    array_push($all_pages_header_settings['fields'], $page_sections_item);
                }

                // Check page_id parameter
                if ($page->ID == $page_id) {
                    break;
                }
            }
            return $all_pages_header_settings;
        }


        /**
         * Get all page config for demo export xml
         * @param void
         * @return array
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_all_pages_header_settings($page_id = null)
        {
            // Holder of all pages with necessary fields
            $all_pages_header_settings = array(
                'item_name' => 'page_config_item',
                'fields' => array()
            );
            // Args of wp query
            $args = array(
                'post_type'        => 'page',
                'post_status' => 'publish',
            );
            // Get all published pages
            $pages = get_pages($args);
            // Get section fields
            $header_settings_fields = Irantheme_Page_Builder_Ajax::get_function_fields('header', false);
            foreach ($pages as $page) {
                $page_header_fields = Irantheme_Page_Builder_Ajax::get_page_settings($page->ID, 'header', 'header_fields');
                // Get page is customizable
                $header_customizable = isset($page_header_fields['irantheme_header_customizable_checkbox']) ? $page_header_fields['irantheme_header_customizable_checkbox'] : '';
                if ($header_customizable != 'on') {
                    continue;
                }

                // Check page_id parameter for page xml backup
                if ($page_id == null || $page->ID == $page_id) {
                    // Holder of current post
                    $page_header_settings = array(
                        'title' => htmlspecialchars($page->post_title, ENT_XML1, 'UTF-8'),
                    );
                    foreach ($header_settings_fields as $fields_settings) {
                        foreach ($fields_settings['fields'] as $fields) {
                            $option_name = $fields['id'];
                            $option_value = isset($page_header_fields[$option_name]) ? $page_header_fields[$option_name] : '';
                            // Check option value for getting attachment url
                            if ($option_value) {
                                if ($fields['type'] == 'image' || $fields['type'] == 'video') {
                                    $option_value = wp_get_attachment_url($option_value);
                                }
                                // Add cdata to text values
                                if ($fields['type'] == 'text_editor' || $fields['type'] == 'textarea' || $fields['type'] == 'text' || $fields['type'] == 'text_secret' || $fields['type'] == 'password') {
                                    $option_value = self::add_cdata($option_value);
                                }
                            }
                            $page_header_settings = array_merge($page_header_settings, array(
                                $option_name => $option_value
                            ));
                        }
                    }
                    // Push current post to all posts holder
                    array_push($all_pages_header_settings['fields'], $page_header_settings);
                }

                // Check page_id parameter
                if ($page->ID == $page_id) {
                    break;
                }
            }
            return $all_pages_header_settings;
        }


        /**
         * Get all page config for demo export xml
         * @param void
         * @return array
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_all_pages_configs($page_id = null)
        {
            // Holder of all pages with necessary fields
            $all_pages_configs = array(
                'item_name' => 'page_config_item',
                'fields' => array()
            );
            // Args of wp query
            $args = array(
                'post_type'        => 'page',
                'post_status' => 'publish',
            );
            // Get all published pages
            $pages = get_pages($args);
            // Get section fields
            $page_config_fields_raw = Irantheme_Page_Builder_Ajax::get_function_fields('page_config_settings', false);
            foreach ($pages as $page) {
                $page_config_fields = Irantheme_Page_Builder_Ajax::get_page_settings($page->ID, 'page_config', 'page_config_fields');
                // Get page is customizable
                $page_customizable = $page_config_fields['irantheme_page_customizable_checkbox'] ?? 'off';

                // Check page builder is enabled
                if ($page_customizable != 'on') {
                    continue;
                }

                // Check page_id parameter for page xml backup
                if ($page_id == null || $page->ID == $page_id) {
                    // Holder of current post
                    $page_item = array(
                        'title' => htmlspecialchars($page->post_title, ENT_XML1, 'UTF-8'),
                    );
                    foreach ($page_config_fields_raw as $fields_settings) {
                        foreach ($fields_settings['fields'] as $fields) {
                            $option_name = $fields['id'];
                            $option_value = isset($page_config_fields[$fields['id']]) ? $page_config_fields[$fields['id']] : '';
                            // Check option value for getting attachment url
                            if ($option_value) {
                                if ($fields['type'] == 'image' || $fields['type'] == 'video') {
                                    $option_value = wp_get_attachment_url($option_value);
                                }
                                // Add cdata to text values
                                if ($fields['type'] == 'text_editor' || $fields['type'] == 'textarea' || $fields['type'] == 'text' || $fields['type'] == 'text_secret' || $fields['type'] == 'password') {
                                    $option_value = self::add_cdata($option_value);
                                }
                            }
                            $page_item = array_merge($page_item, array(
                                $option_name => $option_value
                            ));
                        }
                    }
                    // Push current post to all posts holder
                    array_push($all_pages_configs['fields'], $page_item);
                }

                // Check page_id parameter
                if ($page->ID == $page_id) {
                    break;
                }
            }
            return $all_pages_configs;
        }


        /**
         * Get all pages for demo export xml
         * @param void
         * @return array
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_all_pages($page_id = null)
        {
            // Holder of all pages with necessary fields
            $all_pages = array(
                'item_name' => 'page_item',
                'fields' => array()
            );
            // Args of wp query
            $args = array(
                'post_type'        => 'page',
                'post_status' => 'publish',
            );
            // Get all published pages
            $pages = get_pages($args);
            // Get front page id
            $front_page_id = get_option('page_on_front');
            // Get home page id
            $home_page_id = get_option('page_for_posts');
            // Get privacy policy page id
            $privacy_policy_id = get_option('wp_page_for_privacy_policy');
            foreach ($pages as $page) {
                if ($page->ID == $privacy_policy_id) {
                    continue;
                }

                // // Check page_id parameter for page xml backup
                if ($page_id == null || $page->ID == $page_id) {
                    // Get content
                    $page_content = $page->post_content;

                    // Holder of current post
                    $page_item = array(
                        'id' => $page->ID,
                        'title' => htmlspecialchars($page->post_title, ENT_XML1, 'UTF-8'),
                        'content' => self::add_cdata($page_content),
                        'thumbnail_url' => get_the_post_thumbnail_url($page->ID, 'full'),
                    );

                    // Append front page and home page field
                    if ($front_page_id == $page->ID) {
                        $page_item = array_merge($page_item, array(
                            'rel' => 'front_page'
                        ));
                    } elseif ($home_page_id == $page->ID) {
                        $page_item = array_merge($page_item, array(
                            'rel' => 'home_page'
                        ));
                    } elseif (is_woocommerce_activated()) {
                        // Check woocommerce pages to reject
                        if ($page->ID == wc_get_page_id('cart')) {
                            $page_item = array_merge($page_item, array(
                                'rel' => 'cart'
                            ));
                        } elseif ($page->ID == wc_get_page_id('checkout')) {
                            $page_item = array_merge($page_item, array(
                                'rel' => 'checkout'
                            ));
                        } elseif ($page->ID == get_option('woocommerce_myaccount_page_id')) {
                            $page_item = array_merge($page_item, array(
                                'rel' => 'myaccount'
                            ));
                        } elseif ($page->ID == wc_get_page_id('shop')) {
                            $page_item = array_merge($page_item, array(
                                'rel' => 'shop'
                            ));
                        }
                    } else {
                        // In otherwise add empty rel
                        $page_item = array_merge($page_item, array(
                            'rel' => ''
                        ));
                    }
                    // Push current post to all posts holder
                    array_push($all_pages['fields'], $page_item);
                }
                // // Check page_id parameter for page xml backup
                if ($page->ID == $page_id) {
                    break;
                }
            }
            return $all_pages;
        }


        /**
         * Get all fields of settings options
         * @param void
         * @return array
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_all_settings_options()
        {
            // Holder of all posts with necessary fields
            $all_settings_options = array(
                'item_name' => 'option_item',
                'fields' => array()
            );
            $all_fields_settings = Irantheme_Fields_Options::fields();
            foreach ($all_fields_settings as $section => $data) {
                foreach ($data['submenu'] as $key => $value) {
                    // If fields not found, go to next section
                    if (!$value['fields']) {
                        continue;
                    }
                    foreach ($value['fields'] as $fields) {
                        $option_name = $fields['id'];
                        $option_value = get_option($option_name);
                        // Check option value for getting attachment url
                        if ($option_value) {
                            if ($fields['type'] == 'image' || $fields['type'] == 'video') {
                                $option_value = wp_get_attachment_url($option_value);
                            }
                            // Add cdata to text values
                            if ($fields['type'] == 'text_editor' || $fields['type'] == 'textarea' || $fields['type'] == 'text' || $fields['type'] == 'text_secret' || $fields['type'] == 'password') {
                                $option_value = self::add_cdata($option_value);
                            }
                        }
                        array_push($all_settings_options['fields'], array(
                            $option_name => $option_value
                        ));
                    }
                }
            }
            return $all_settings_options;
        }


        /**
         * Get all fields of settings options
         * @param void
         * @return array
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_all_woocommerce_extra_options()
        {
            // Holder of all posts with necessary fields
            $all_extra_options = array(
                'item_name' => 'option_item',
                'fields' => array()
            );

            // Check woocommerce activation
            if (!is_woocommerce_activated()) {
                return $all_extra_options;
            }

            // Add woocommerce currency symbol
            array_push($all_extra_options['fields'], array(
                'woocommerce_currency' => get_option('woocommerce_currency')
            ));
            // Add woocommerce currency symbol position
            array_push($all_extra_options['fields'], array(
                'woocommerce_currency_pos' => get_option('woocommerce_currency_pos')
            ));

            return $all_extra_options;
        }


        /**
         * Get templates from get_option
         * @param void
         * @return array
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_templates()
        {
            // Holder of all posts with necessary fields
            $all_extra_options = array(
                'item_name' => 'template_option',
                'fields' => array()
            );

            // Get meta data sections of current page (use JSON Helper for safety)
            $associative_fields = Irantheme_JSON_Helper::decode(get_option('irantheme_page_builder_templates'));
            if (!$associative_fields) {
                $associative_fields = array();
            }
            // Check visual field value
            if ($associative_fields) {
                foreach ($associative_fields as &$section) {
                    $data_value = $section['data_value'];
                    // Get section fields
                    $sections_fields = Irantheme_Page_Builder_Ajax::get_function_fields($data_value, false);
                    foreach ($sections_fields as $fields_settings) {
                        foreach ($fields_settings['fields'] as $fields) {
                            // Get section field value
                            $option_value = isset($section['section_fields'][$fields['id']]) ? $section['section_fields'][$fields['id']] : '';
                            // Check option value for getting attachment url
                            if ($option_value) {
                                if (
                                    $fields['type'] == 'image' || $fields['type'] == 'video'
                                ) {
                                    $section['section_fields'][$fields['id']] = wp_get_attachment_url($option_value);
                                } elseif ($fields['type'] == 'hidden_json') {
                                    $objects_fields = Irantheme_Page_Builder_Ajax::get_object_fields($data_value);
                                    // Check hidden json and convert attachment fields to url
                                    $objects_json = Irantheme_JSON_Helper::normalize_objects($option_value);
                                    $objects_holder = array();
                                    if (is_array($objects_json)) {
                                        foreach ($objects_json as &$object) {
                                            if (!isset($object['fields']) || !is_array($object['fields'])) {
                                                $object['fields'] = array();
                                            }
                                            foreach ($objects_fields as $objects_sample) {
                                                foreach ($objects_sample['fields'] as $object_sample) {
                                                    if ($object_sample['type'] == 'image' || $object_sample['type'] == 'video') {
                                                        if (isset($object['fields'][$object_sample['id']])) {
                                                            $object['fields'][$object_sample['id']] = wp_get_attachment_url($object['fields'][$object_sample['id']]);
                                                        }
                                                    }
                                                }
                                            }
                                            array_push($objects_holder, $object);
                                        }
                                        unset($object);
                                    }
                                    $section['section_fields'][$fields['id']] = wp_json_encode($objects_holder);
                                }
                            }
                        }
                    }
                }
                unset($section);
            }
            // Add cdata to associative fields
            $associative_fields = self::add_cdata(wp_json_encode($associative_fields));
            $associative_fields = str_replace(
                '&nbsp;',
                '\u00A0',
                $associative_fields
            );
            // Add associative fields to main holder fields
            array_push($all_extra_options['fields'], array(
                'irantheme_page_builder_templates' => $associative_fields
            ));
            return $all_extra_options;
        }


        /**
         * Get all fields of categories
         * @param void
         * @return array
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_all_categories()
        {
            // Holder of all posts with necessary fields
            $all_categories = array(
                'item_name' => 'category_item',
                'fields' => array()
            );
            $args = array(
                'taxonomy' => 'category',
                'hide_empty' => false
            );
            foreach (get_terms($args) as $category) {
                array_push($all_categories['fields'], array(
                    'id' => $category->term_id,
                    'title' => $category->name,
                    'parent' => $category->parent,
                ));
            }
            return $all_categories;
        }


        /**
         * Get all fields of product categories
         * @param void
         * @return array
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_all_categories_product()
        {
            // Holder of all posts with necessary fields
            $all_categories = array(
                'item_name' => 'category_product_item',
                'fields' => array()
            );

            // Check woocommerce activation
            if (!is_woocommerce_activated()) {
                return $all_categories;
            }

            $args = array(
                'taxonomy' => 'product_cat',
                'hide_empty' => false
            );
            foreach (get_terms($args) as $category) {
                // Apply htmlspecialchars to category name and description
                array_push($all_categories['fields'], array(
                    'id' => $category->term_id,
                    'title' => htmlspecialchars($category->name, ENT_XML1, 'UTF-8'),
                    'description' => htmlspecialchars($category->description, ENT_XML1, 'UTF-8'),
                    'parent' => $category->parent,
                    'attachment' => wp_get_attachment_url(get_term_meta($category->term_id, 'thumbnail_id', true))
                ));
            }
            return $all_categories;
        }


        /**
         * Get all WooCommerce attributes for demo export
         * @param void
         * @return array
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_all_wc_attributes()
        {
            $all_attributes = array(
                'item_name' => 'attribute_item',
                'fields' => array()
            );

            // Check woocommerce activation
            if (!is_woocommerce_activated()) {
                return $all_attributes;
            }

            global $wpdb;

            // Get all attribute taxonomies
            $attributes = wc_get_attribute_taxonomies();

            foreach ($attributes as $attribute) {
                $attribute_id = $attribute->attribute_id;
                $attribute_name = $attribute->attribute_name;
                $taxonomy_name = wc_attribute_taxonomy_name($attribute_name);

                // Get attribute options
                $attribute_options = array(
                    'id' => $attribute_id,
                    'name' => $attribute_name,
                    'label' => $attribute->attribute_label,
                    'type' => $attribute->attribute_type,
                    'orderby' => $attribute->attribute_orderby,
                    'has_archives' => $attribute->attribute_public,
                );

                // Get custom field values
                $taxonomy_type = get_option("wc_attribute_irantheme_taxonomy_type-{$attribute_id}");
                $add_to_filter = get_option("wc_attribute_irantheme_taxonomy_add_to_filter-{$attribute_id}");
                $filter_position = get_option("wc_attribute_irantheme_taxonomy_index_filter-{$attribute_id}");
                $filter_icon = get_option("wc_attribute_irantheme_taxonomy_icon_filter-{$attribute_id}");

                if ($taxonomy_type) {
                    $attribute_options['irantheme_taxonomy_type'] = $taxonomy_type;
                }
                if ($add_to_filter) {
                    $attribute_options['irantheme_add_to_filter'] = $add_to_filter;
                }
                if ($filter_position) {
                    $attribute_options['irantheme_filter_position'] = $filter_position;
                }
                if ($filter_icon) {
                    $attribute_options['irantheme_filter_icon'] = $filter_icon;
                }

                // Get all terms for this attribute
                $terms = get_terms(array(
                    'taxonomy' => $taxonomy_name,
                    'hide_empty' => false,
                ));

                $term_data = array();
                if (!empty($terms) && !is_wp_error($terms)) {
                    foreach ($terms as $term) {
                        $term_item = array(
                            'term_id' => $term->term_id,
                            'name' => htmlspecialchars($term->name, ENT_XML1, 'UTF-8'),
                            'slug' => $term->slug,
                            'description' => htmlspecialchars($term->description, ENT_XML1, 'UTF-8'),
                        );

                        // Get term type value (color hex or brand logo ID)
                        $term_type_value = get_option("_term_type_{$taxonomy_name}_{$term->term_id}");
                        if ($term_type_value) {
                            // Check if it's an image (brand logo)
                            if ($taxonomy_type == 'brand' && is_numeric($term_type_value)) {
                                $term_item['term_type_value'] = wp_get_attachment_url($term_type_value);
                            } else {
                                $term_item['term_type_value'] = $term_type_value;
                            }
                        }

                        $term_data[] = $term_item;
                    }
                }

                $attribute_options['terms'] = wp_json_encode($term_data);

                array_push($all_attributes['fields'], $attribute_options);
            }

            return $all_attributes;
        }


        /**
         * Get all posts for demo export xml (Title, content, excerpt, thumbnail, categories, tags, status, post_type)
         * @param void
         * @return array
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_all_posts()
        {
            // Holder of all posts with necessary fields
            $all_posts = array(
                'item_name' => 'post_item',
                'fields' => array()
            );
            // Args of wp query
            $args = array(
                'posts_per_page'   => -1,
                'post_type'        => 'post',
                'post_status' => 'publish',
            );
            $wp_query = new \WP_Query($args);
            while ($wp_query->have_posts()) {
                $wp_query->the_post();
                // Get all categories of post and push to category holder
                $post_categories = array();
                foreach (get_the_category(get_the_ID()) as $post_category) {
                    array_push($post_categories, $post_category->term_id);
                }
                // Get all tags of post and push to tags holder
                $post_tags = array();
                foreach (get_the_tags(get_the_ID()) as $post_tag) {
                    array_push($post_tags, $post_tag->name);
                }
                // Holder of current post
                $post_item = array(
                    'id' => get_the_ID(),
                    'title' =>
                    htmlspecialchars(get_the_title(), ENT_XML1, 'UTF-8'),
                    'content' => self::add_cdata(get_the_content()),
                    'excerpt' => self::add_cdata(get_the_excerpt()),
                    'thumbnail_url' => get_the_post_thumbnail_url(get_the_ID(), 'full'),
                    'categories' => $post_categories,
                    'tags' => $post_tags,
                );
                // Push current post to all posts holder
                array_push($all_posts['fields'], $post_item);
            }
            wp_reset_query();
            return $all_posts;
        }


        /**
         * Get all products for demo export xml (Title, content, excerpt, thumbnail, categories, tags, status, post_type)
         * @param void
         * @return array
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_all_products()
        {
            // Holder of all products with necessary fields
            $all_products = array(
                'item_name' => 'product_item',
                'fields' => array()
            );

            // Check woocommerce activation
            if (!is_woocommerce_activated()) {
                return $all_products;
            }

            // Args of wp query
            $args = array(
                'posts_per_page'   => -1,
                'post_type'        => 'product',
                'post_status' => 'publish',
            );
            $wp_query = new \WP_Query($args);
            while ($wp_query->have_posts()) {
                $wp_query->the_post();
                global $product;
                // Get all categories of product and push to category holder
                $post_categories = array();
                foreach (get_the_terms($product->get_id(), 'product_cat') as $term) {
                    array_push($post_categories, $term->term_id);
                }
                // Get all tags of product and push to tags holder
                $post_tags = array();
                foreach (get_the_terms($product->get_id(), 'product_tag') as $term) {
                    array_push($post_tags, $term->name);
                }
                // Get all attributes of product and push to attributes holder
                $attributes_name = array();
                $attributes_value = array();
                foreach ($product->get_attributes() as $attr_name => $attr) {
                    // استفاده از متد get_name() اگر موجود باشد
                    if (method_exists($attr, 'get_name')) {
                        $attribute_label = $attr->get_name();
                    } else {
                        $attribute_label = str_replace('pa_', '', $attr_name);
                        $attribute_label = str_replace('_', ' ', $attribute_label);
                        $attribute_label = ucwords($attribute_label);
                    }

                    $attribute_label = mb_convert_encoding($attribute_label, 'UTF-8', 'auto');

                    if ($attr->is_taxonomy()) {
                        // برای attributeهای taxonomy-based
                        $attribute_value = $product->get_attribute($attr_name);
                    } else {
                        // برای attributeهای custom
                        $attribute_value = $attr->get_options();
                        if (is_array($attribute_value)) {
                            $attribute_value = implode('|', $attribute_value);
                        }
                    }

                    array_push($attributes_name, $attribute_label);
                    array_push($attributes_value, $attribute_value);
                }
                // Holder of current product
                $post_item = array(
                    'id' => get_the_ID(),
                    'title' => htmlspecialchars(get_the_title(), ENT_XML1, 'UTF-8'),
                    'content' => self::add_cdata(get_the_content()),
                    'excerpt' => self::add_cdata(get_the_excerpt()),
                    'thumbnail_url' => get_the_post_thumbnail_url($product->get_id(), 'full'),
                    'categories' => $post_categories,
                    'tags' => $post_tags,
                    'sku' => $product->get_sku(),
                    'stock_status' => $product->get_stock_status(),
                    'stock_quantity' => $product->get_stock_quantity(),
                    'regular_price' => $product->get_regular_price(),
                    'attributes_name' => mb_convert_encoding(implode(',', $attributes_name), 'UTF-8', 'auto'),
                    'attributes_value' => implode(',', $attributes_value),
                    // Custom fields
                    'product_new_badge_checkbox' => get_post_meta($product->get_id(), 'irantheme_custom_field_product_new_badge_checkbox', true),
                    'product_new_badge_date' => get_post_meta($product->get_id(), 'irantheme_custom_field_product_new_badge_date', true),
                    'product_detail_badge' => get_post_meta($product->get_id(), 'irantheme_custom_field_product_detail_badge', true),
                    'product_attr' => get_post_meta($product->get_id(), 'irantheme_custom_field_product_attr', true),
                    'product_color_var_image_checkbox' => get_post_meta($product->get_id(), 'irantheme_custom_field_product_color_var_image_checkbox', true),
                    'product_tip' => self::add_cdata(get_post_meta($product->get_id(), 'irantheme_custom_field_product_tip', true)),
                    'shipping_time' => self::add_cdata(get_post_meta($product->get_id(), 'irantheme_custom_field_shipping_time', true)),
                );
                // Get all types of prices
                if ($product->is_on_sale()) {
                    $post_item = array_merge($post_item, array(
                        'sale_price' => $product->get_sale_price(),
                        'date_on_sale_from' => $product->get_date_on_sale_from(),
                        'date_on_sale_to' => $product->get_date_on_sale_to(),
                    ));
                }
                // Get gallery attachment urls
                $gallery_attachment_ids = $product->get_gallery_image_ids();
                $gallery_ids = array();
                foreach ($gallery_attachment_ids as $gallery_id) {
                    array_push($gallery_ids, wp_get_attachment_url($gallery_id));
                }
                if (!empty($gallery_ids)) {
                    $post_item = array_merge($post_item, array(
                        'gallery_ids' => $gallery_ids
                    ));
                }
                // Push current product to all products holder
                array_push($all_products['fields'], $post_item);
            }
            wp_reset_query();
            return $all_products;
        }


        /**
         * Get all sidebars for demo export xml
         * @param void
         * @return array
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_all_plugins_requirements()
        {
            $all_plugins = array(
                'item_name' => 'plugin_item',
                'fields' => array()
            );
            // Check woocommerce activation
            if (is_woocommerce_activated()) {
                array_push($all_plugins['fields'], array(
                    'plugin_name' => 'woocommerce'
                ));
            }
            return $all_plugins;
        }


        /**
         * Check option name is section field
         * @param string
         * @return bool
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function is_section_field($option_name)
        {
            $last_option_name_separated = end(explode('_', $option_name));
            if (strlen($last_option_name_separated) == 16) {
                return true;
            }
            return false;
        }


        /**
         * Add cdata to string
         * @param string
         * @return string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function add_cdata($string)
        {
            if ($string || $string === '0') {
                // Replace HTML entities with their numeric equivalents
                $string = self::convert_html_entities_to_numeric($string);
                return sprintf('<![CDATA[%s]]>', $string);
            }
            return '';
        }

        /**
         * Convert HTML entities to numeric entities for XML compatibility
         * @param string
         * @return string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function convert_html_entities_to_numeric($string)
        {
            if (empty($string)) {
                return $string;
            }

            // Common HTML entities that cause issues in XML
            $html_entities = array(
                '&hellip;' => '&#8230;',
                '&ndash;' => '&#8211;',
                '&mdash;' => '&#8212;',
                '&lsquo;' => '&#8216;',
                '&rsquo;' => '&#8217;',
                '&ldquo;' => '&#8220;',
                '&rdquo;' => '&#8221;',
                '&bull;' => '&#8226;',
                '&trade;' => '&#8482;',
                '&copy;' => '&#169;',
                '&reg;' => '&#174;',
                '&nbsp;' => '&#160;',
                '&cent;' => '&#162;',
                '&pound;' => '&#163;',
                '&euro;' => '&#8364;',
                '&yen;' => '&#165;',
                '&sect;' => '&#167;',
                '&laquo;' => '&#171;',
                '&raquo;' => '&#187;',
            );

            $string = str_replace(array_keys($html_entities), array_values($html_entities), $string);

            // Also handle any other named entities by converting them to numeric
            $string = preg_replace_callback('/&([a-z0-9]+|#[0-9]{1,6}|#x[0-9a-f]{1,6});/i', function ($matches) {
                $entity = $matches[0];
                // If it's already a numeric entity, return as is
                if (strpos($entity, '&#') === 0) {
                    return $entity;
                }
                // Convert named entity to numeric (this is a fallback)
                return htmlentities($entity, ENT_XML1, 'UTF-8');
            }, $string);

            return $string;
        }


        /**
         * Convert data of current site to xml file
         * @param int page id
         * @param bool extra data
         * @return string url
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function convert_data_to_xml($page_id = null, $extra_data = false)
        {
            if ($page_id == null) {
                $root_array = array(
                    'irantheme_title' => 'IRANTHEME XML DEMO EXPORT',
                    'irantheme_xml_file_name' => 'irantheme_xml_demo_export.xml',
                    'irantheme_xml_content' => array(
                        'irantheme_plugins_requirements' => self::get_all_plugins_requirements(),
                        'irantheme_posts_categories' => self::get_all_categories(),
                        'irantheme_products_categories' => self::get_all_categories_product(),
                        'irantheme_wc_attributes' => self::get_all_wc_attributes(),
                        'irantheme_products' => self::get_all_products(),
                        'irantheme_posts' => self::get_all_posts(),
                        'irantheme_nav_menus' => self::get_all_nav_menus(),
                        'irantheme_widgets_blocks' => self::get_all_widgets_blocks(),
                        'irantheme_woocommerce_extra_options' => self::get_all_woocommerce_extra_options(),
                        'irantheme_templates' => self::get_templates(),
                        'irantheme_settings_options' => self::get_all_settings_options(),
                        'irantheme_pages' => self::get_all_pages(),
                        'irantheme_page_config' => self::get_all_pages_configs(),
                        'irantheme_pages_header_settings' => self::get_all_pages_header_settings(),
                        'irantheme_pages_sections' => self::get_all_pages_sections(),
                    )
                );
            } else {
                // Similar update for page export
                $root_array = array(
                    'irantheme_title' => 'IRANTHEME XML PAGE EXPORT',
                    'irantheme_xml_file_name' => 'irantheme_xml_page_export.xml',
                    'irantheme_xml_content' => array()
                );

                if ($extra_data) {
                    // Page necessary data
                    $page_extra_array = array(
                        'irantheme_plugins_requirements' => self::get_all_plugins_requirements(),
                        'irantheme_posts_categories' => self::get_all_categories(),
                        'irantheme_products_categories' => self::get_all_categories_product(),
                        'irantheme_wc_attributes' => self::get_all_wc_attributes(),
                        'irantheme_products' => self::get_all_products(),
                        'irantheme_posts' => self::get_all_posts(),
                        'irantheme_nav_menus' => self::get_all_nav_menus(),
                        'irantheme_widgets_blocks' => self::get_all_widgets_blocks(),
                        'irantheme_woocommerce_extra_options' => self::get_all_woocommerce_extra_options(),
                    );
                    // Merge necessary data of page
                    $root_array['irantheme_xml_content'] = array_merge($root_array['irantheme_xml_content'], $page_extra_array);
                }

                $page_requires_array = array(
                    'irantheme_pages' => self::get_all_pages($page_id),
                    'irantheme_page_config' => self::get_all_pages_configs($page_id),
                    'irantheme_pages_header_settings' => self::get_all_pages_header_settings($page_id),
                    'irantheme_pages_sections' => self::get_all_pages_sections($page_id),
                );
                // Merge necessary data of page
                $root_array['irantheme_xml_content'] = array_merge($root_array['irantheme_xml_content'], $page_requires_array);
            }

            // Create the xml document
            $xmlDoc = new \DOMDocument();

            $root = $xmlDoc->appendChild($xmlDoc->createElement("irantheme_root"));

            $root->appendChild($xmlDoc->createElement("title", $root_array['irantheme_title']));

            foreach ($root_array['irantheme_xml_content'] as $xml_key => $xml_value) {
                $xml_items = $root->appendChild($xmlDoc->createElement($xml_key));

                foreach ($xml_value['fields'] as $post_item) {
                    if (!empty($post_item)) {
                        $xml_post = $xml_items->appendChild($xmlDoc->createElement($xml_value['item_name']));
                        foreach ($post_item as $key => $val) {
                            if (is_array($val)) {
                                $temp = implode(',', $val);
                                $xml_post->appendChild($xmlDoc->createElement($key, $temp));
                            } else {
                                $xml_post->appendChild($xmlDoc->createElement($key, $val));
                            }
                        }
                    }
                }
            }

            header("Content-Type: text/plain");

            // Make the output 
            $xmlDoc->formatOutput = true;

            // Get xml file name
            $file_name = $root_array['irantheme_xml_file_name'];

            // Save xml file
            $xmlDoc->save($file_name);

            // Modify current url
            $php_self = str_replace('admin-ajax.php', '', $_SERVER['PHP_SELF']);

            // Create xml url file
            $url = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://" . $_SERVER['HTTP_HOST'] . $php_self . $file_name;

            // Return xml file name
            return $url;
        }
    }

    new Irantheme_Settings_Demo_Export();
}
