<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;


if (!class_exists('Irantheme_Page_Builder_Ajax')) {

    /**
     * Irantheme page builder
     * Configuration of page builder
     * 
     * @package Wordpress
     * @subpackage Storeplus
     * @since v1.0
     * @author iranianclever
     * @see v1.0
     * 
     * Page builder settings
     */
    class Irantheme_Page_Builder_Ajax
    {
        // Class info
        private static $text_domain = IRANTHEME_TEXTDOMAIN;

        // Map: data_value => section class name (for get_function_fields)
        private static $fields_section_map = array(
            'showcase_simple'                       => 'Irantheme_Section_Showcase_Simple',
            'showcase_service'                      => 'Irantheme_Section_Showcase_Service',
            'showcase_tag'                          => 'Irantheme_Section_Showcase_Tag',
            'showcase_video'                        => 'Irantheme_Section_Showcase_Video',
            'showcase_split'                        => 'Irantheme_Section_Showcase_Split',
            'showcase_author'                       => 'Irantheme_Section_Showcase_Author',
            'showcase_counter'                      => 'Irantheme_Section_Showcase_Counter',
            'slider_simple'                         => 'Irantheme_Section_Slider_Simple',
            'service_master'                        => 'Irantheme_Section_Service_Master',
            'service_number'                        => 'Irantheme_Section_Service_Number',
            'service_semi_number'                   => 'Irantheme_Section_Service_Semi_Number',
            'service_note_slider'                   => 'Irantheme_Section_Service_Note_Slider',
            'service_semi_master'                   => 'Irantheme_Section_Service_Semi_Master',
            'service_semi_split_image'              => 'Irantheme_Section_Service_Semi_Split_Image',
            'service_progress'                      => 'Irantheme_Section_Service_Progress',
            'service_semi_grid_image'               => 'Irantheme_Section_Service_Semi_Grid_Image',
            'business_authors_post'                 => 'Irantheme_Section_Business_Authors_Post',
            'business_viewpoints'                   => 'Irantheme_Section_Business_Viewpoints',
            'business_box_reviews'                  => 'Irantheme_Section_Business_Box_Reviews',
            'business_ibanner'                      => 'Irantheme_Section_Business_Ibanner',
            'business_ads'                          => 'Irantheme_Section_Business_Ads',
            'business_department'                   => 'Irantheme_Section_Business_Department',
            'business_brands'                       => 'Irantheme_Section_Business_Brands',
            'business_portfolio'                    => 'Irantheme_Section_Business_Portfolio',
            'business_plane_service'                => 'Irantheme_Section_Business_Plane_Service',
            'business_modern_plane_service'         => 'Irantheme_Section_Business_Modern_Plane_Service',
            'business_about_me'                     => 'Irantheme_Section_Business_About_Me',
            'communication_contact'                 => 'Irantheme_Section_Communication_Contact',
            'communication_line_contact'            => 'Irantheme_Section_Communication_Line_Contact',
            'communication_google_map'              => 'Irantheme_Section_Communication_Goggle_Map',
            'communication_newsletter'              => 'Irantheme_Section_Communication_Newsletter',
            'communication_faq'                     => 'Irantheme_Section_Communication_Faq',
            'gallery_master'                        => 'Irantheme_Section_Gallery_Master',
            'gallery_banner'                        => 'Irantheme_Section_Gallery_Banner',
            'gallery_slider'                        => 'Irantheme_Section_Gallery_Slider',
            'gallery_type'                          => 'Irantheme_Section_Gallery_Type',
            'woocommerce_gallery_social'            => 'Irantheme_Section_Woocommerce_Gallery_Social',
            'post_gallery'                          => 'Irantheme_Section_Post_Gallery',
            'page_text'                             => 'Irantheme_Section_Page_Text',
            'post_box'                              => 'Irantheme_Section_Post_Box',
            'post_simple'                           => 'Irantheme_Section_Post_Simple',
            'post_sidebar'                          => 'Irantheme_Section_Post_Sidebar',
            'woocommerce_products'                  => 'Irantheme_Section_Woocommerce_Products',
            'woocommerce_categories'                => 'Irantheme_Section_Woocommerce_Categories',
            'woocommerce_mini_products'             => 'Irantheme_Section_Woocommerce_Mini_Products',
            'woocommerce_grid_products'             => 'Irantheme_Section_Woocommerce_Grid_Products',
            'woocommerce_offers_products'           => 'Irantheme_Section_Woocommerce_Offers_Products',
            'woocommerce_hotspot'                   => 'Irantheme_Section_Woocommerce_Hotspot',
            'woocommerce_category_products'         => 'Irantheme_Section_Woocommerce_Category_Products',
            'woocommerce_slider'                    => 'Irantheme_Section_Woocommerce_Slider',
            'woocommerce_box_categories'            => 'Irantheme_Section_Woocommerce_Box_Categories',
            'woocommerce_box_brands'                => 'Irantheme_Section_Woocommerce_Box_Brands',
            'woocommerce_ajax_categories_products'  => 'Irantheme_Section_Woocommerce_Ajax_Categories_Products',
            'footer_classic'                        => 'Irantheme_Section_Footer_Classic',
            'footer_master'                         => 'Irantheme_Section_Footer_Master',
            'footer_copyright'                      => 'Irantheme_Section_Footer_Copyright',
            'footer_nav'                            => 'Irantheme_Section_Footer_Nav',
        );

        // Map: data_value => section class name (for get_object_fields) - subset that supports objects
        private static $object_section_map = array(
            'showcase_service'                      => 'Irantheme_Section_Showcase_Service',
            'showcase_tag'                          => 'Irantheme_Section_Showcase_Tag',
            'service_master'                        => 'Irantheme_Section_Service_Master',
            'service_semi_master'                   => 'Irantheme_Section_Service_Semi_Master',
            'service_semi_number'                   => 'Irantheme_Section_Service_Semi_Number',
            'service_note_slider'                   => 'Irantheme_Section_Service_Note_Slider',
            'service_number'                        => 'Irantheme_Section_Service_Number',
            'service_progress'                      => 'Irantheme_Section_Service_Progress',
            'gallery_banner'                        => 'Irantheme_Section_Gallery_Banner',
            'gallery_slider'                        => 'Irantheme_Section_Gallery_Slider',
            'gallery_type'                          => 'Irantheme_Section_Gallery_Type',
            'business_brands'                       => 'Irantheme_Section_Business_Brands',
            'business_portfolio'                    => 'Irantheme_Section_Business_Portfolio',
            'business_plane_service'                => 'Irantheme_Section_Business_Plane_Service',
            'business_modern_plane_service'         => 'Irantheme_Section_Business_Modern_Plane_Service',
            'business_about_me'                     => 'Irantheme_Section_Business_About_Me',
            'business_viewpoints'                   => 'Irantheme_Section_Business_Viewpoints',
            'business_authors_post'                 => 'Irantheme_Section_Business_Authors_Post',
            'business_box_reviews'                  => 'Irantheme_Section_Business_Box_Reviews',
            'business_ibanner'                      => 'Irantheme_Section_Business_Ibanner',
            'business_ads'                          => 'Irantheme_Section_Business_Ads',
            'business_department'                   => 'Irantheme_Section_Business_Department',
            'communication_faq'                     => 'Irantheme_Section_Communication_Faq',
            'communication_contact'                 => 'Irantheme_Section_Communication_Contact',
            'communication_line_contact'            => 'Irantheme_Section_Communication_Line_Contact',
            'post_sidebar'                          => 'Irantheme_Section_Post_Sidebar',
            'footer_master'                         => 'Irantheme_Section_Footer_Master',
            'footer_nav'                            => 'Irantheme_Section_Footer_Nav',
            'footer_classic'                        => 'Irantheme_Section_Footer_Classic',
            'slider_simple'                         => 'Irantheme_Section_Slider_Simple',
            'woocommerce_hotspot'                   => 'Irantheme_Section_Woocommerce_Hotspot',
            'woocommerce_grid_products'             => 'Irantheme_Section_Woocommerce_Grid_Products',
            'woocommerce_slider'                    => 'Irantheme_Section_Woocommerce_Slider',
            'woocommerce_gallery_social'            => 'Irantheme_Section_Woocommerce_Gallery_Social',
        );

        // Constructor initialize
        public function __construct() {}


        /**
         * add object fields with json
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        function add_object()
        {
            // Get post send data
            $json_data_name = $_POST['json_data_name'];
            $data_type = $_POST['data_type'];
            $data_value = $_POST['data_value'];
            $data_page = $_POST['data_page'];
            $data_ajax = $_POST['data_ajax'];

            // Check all data
            if (!$data_type || !$data_value || !$data_page || !$data_ajax || !$json_data_name) {
                @ini_set('display_errors', 0);
                wp_die();
            }

            // Get object tabs with data value
            $object_tabs = self::get_object_fields($data_value, 0, 'media');

            // Create new object array
            $new_object = array();
            $new_object['object_id'] = Irantheme_Section_Default::get_generate_data_ajax(8);
            $new_object['fields'] = array();

            // Loop from object fields and append default values to new object
            foreach ($object_tabs as $object_fields) {
                foreach ($object_fields['fields'] as $field_item) {
                    $new_object['fields'][$field_item['id']] = urldecode($field_item['default']);
                }
            }

            // Add new section to page sections holder
            $sections_holder_name = irantheme_get_meta_data_sections_by_page_id();
            $sections_data_json = get_post_meta($data_page, $sections_holder_name, true);
            $sections_array = Irantheme_JSON_Helper::decode($sections_data_json);


            // Loop from sections
            foreach ($sections_array['sections_items'] as &$section) {
                // Check section data ajax
                if ($section['data_ajax'] == $data_ajax) {
                    // Add temporary current section
                    $temp_section = $section['section_fields'];
                    foreach ($temp_section as $key => $value) {
                        // Check field with name of target field
                        if ($key == $json_data_name) {
                            // Json decoding objects (array or legacy string)
                            $object_json_fields = Irantheme_JSON_Helper::normalize_objects($value);
                            // Append new object
                            array_push($object_json_fields, $new_object);
                            // Keep objects_json as ARRAY (not string!)
                            $temp_section[$key] = $object_json_fields;
                            // Return temporary section to main holder section
                            $section['section_fields'] = $temp_section;
                            break;
                        }
                    }
                    break;
                }
            }

            // Update object with wp_slash to prevent WP from stripping backslashes
            update_post_meta($data_page, $sections_holder_name, wp_slash(Irantheme_JSON_Helper::encode($sections_array)));
            wp_die();
        }


        /**
         * move object fields with json
         * @param void
         * @return bool
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        function move_object()
        {
            // Get post send data
            $object_order_raw = $_POST['object_order'];
            $json_data_name = $_POST['json_data_name'];
            $data_type = $_POST['data_type'];
            $data_value = $_POST['data_value'];
            $data_page = $_POST['data_page'];
            $data_ajax = $_POST['data_ajax'];

            // Check all data
            if (!$data_type || !$data_value || !$data_page || !$data_ajax || !$json_data_name || !$object_order_raw) {
                @ini_set('display_errors', 0);
                wp_die();
            }

            // Convert object order string to array
            $object_order_ids = explode('_', $object_order_raw);

            $object_order_result = array();

            // Add new section to page sections holder
            $sections_holder_name = irantheme_get_meta_data_sections_by_page_id();
            $sections_data_json = get_post_meta($data_page, $sections_holder_name, true);
            $sections_array = Irantheme_JSON_Helper::decode($sections_data_json);

            // Loop from sections
            foreach ($sections_array['sections_items'] as &$section) {
                // Check section data ajax
                if ($section['data_ajax'] == $data_ajax) {
                    // Add temporary current section
                    $temp_section = $section['section_fields'];
                    foreach ($temp_section as $key => $value) {
                        // Check field with name of target field
                        if ($key == $json_data_name) {
                            // Json decoding objects (array or legacy string)
                            $object_json_fields = Irantheme_JSON_Helper::normalize_objects($value);
                            foreach ($object_order_ids as $order_id) {
                                foreach ($object_json_fields as $array_item) {
                                    if ($array_item['object_id'] == $order_id) {
                                        array_push($object_order_result, $array_item);
                                    }
                                }
                            }
                            // Keep objects_json as ARRAY (not string!)
                            $temp_section[$key] = $object_order_result;
                            // Return temporary section to main holder section
                            $section['section_fields'] = $temp_section;
                            break;
                        }
                    }
                    break;
                }
            }

            // Update object with wp_slash
            update_post_meta($data_page, $sections_holder_name, wp_slash(Irantheme_JSON_Helper::encode($sections_array)));
            wp_die();
        }


        /**
         * edit object fields with json
         * @param void
         * @return bool
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        function edit_object()
        {
            $json_data_name = $_POST['json_data_name'];
            $data_type = $_POST['data_type'];
            $data_value = $_POST['data_value'];
            $data_page = $_POST['data_page'];
            $data_ajax = $_POST['data_ajax'];
            $object_id = $_POST['object_id'];

            // Check all data
            if (!$data_type || !$data_value || !$data_page || !$data_ajax || !$json_data_name || !$object_id) {
                @ini_set('display_errors', 0);
                wp_die();
            }

            // Get object fields with data value
            $object_fields = self::get_object_fields($data_value);

            // Add new section to page sections holder
            $sections_holder_name = irantheme_get_meta_data_sections_by_page_id();
            $sections_data_json = get_post_meta($data_page, $sections_holder_name, true);
            $sections_array = Irantheme_JSON_Helper::decode($sections_data_json);

            $object_json_fields = array();
            $object_target = array();

            // Loop from sections
            foreach ($sections_array['sections_items'] as &$section) {
                // Check section data ajax
                if ($section['data_ajax'] == $data_ajax) {
                    // Add temporary current section
                    $temp_section = $section['section_fields'];
                    foreach ($temp_section as $key => $value) {
                        // Check field with name of target field
                        if ($key == $json_data_name) {
                            // Json decoding objects (array or legacy string)
                            $object_json_fields = Irantheme_JSON_Helper::normalize_objects($value);
                            foreach ($object_json_fields as $object_item) {
                                if ($object_item['object_id'] == $object_id) {
                                    $object_target = $object_item;
                                    // Prevent to error of new fields updates
                                    $object_fields_temp = self::get_object_fields($data_value, 0);
                                    if (!isset($object_target['fields']) || !is_array($object_target['fields'])) {
                                        $object_target['fields'] = array();
                                    }
                                    Irantheme_Page_Builder::hydrate_missing_fields($object_fields_temp, $object_target['fields']);
                                    break;
                                }
                            }
                            break;
                        }
                    }
                    break;
                }
            }
            // Return data fields
            $object_fields_values = isset($object_target['fields']) ? $object_target['fields'] : array();
            self::html_panel_ajax($data_page, 'save_object', $object_fields, $object_fields_values, $data_value, $data_ajax, $object_id, $json_data_name);
            wp_die();
        }


        /**
         * delete object json in section
         * @param void
         * @return bool
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        function delete_object()
        {
            $object_id = $_POST['object_id'];
            $json_data_name = $_POST['json_data_name'];
            $data_type = $_POST['data_type'];
            $data_value = $_POST['data_value'];
            $data_page = $_POST['data_page'];
            $data_ajax = $_POST['data_ajax'];

            // Check all data
            if (!$data_type || !$data_value || !$data_page || !$data_ajax || !$json_data_name || !$object_id) {
                @ini_set('display_errors', 0);
                wp_die();
            }

            // Add new section to page sections holder
            $sections_holder_name = irantheme_get_meta_data_sections_by_page_id();
            $sections_data_json = get_post_meta($data_page, $sections_holder_name, true);
            $sections_array = Irantheme_JSON_Helper::decode($sections_data_json);

            // Loop from sections
            foreach ($sections_array['sections_items'] as &$section) {
                // Check section data ajax
                if ($section['data_ajax'] == $data_ajax) {
                    // Add temporary current section
                    $temp_section = $section['section_fields'];
                    foreach ($temp_section as $key => $value) {
                        // Check field with name of target field
                        if ($key == $json_data_name) {
                            // Json decoding objects (array or legacy string)
                            $object_json_fields = Irantheme_JSON_Helper::normalize_objects($value);
                            $index = 0;
                            foreach ($object_json_fields as $object_item) {
                                if ($object_item['object_id'] == $object_id) {
                                    // Remove object from data array
                                    array_splice($object_json_fields, $index, 1);
                                    break;
                                }
                                $index++;
                            }
                            // Keep objects_json as ARRAY (not string!)
                            $temp_section[$key] = $object_json_fields;
                            // Return temporary section to main holder section
                            $section['section_fields'] = $temp_section;
                            break;
                        }
                    }
                    break;
                }
            }

            // Update object with wp_slash
            update_post_meta($data_page, $sections_holder_name, wp_slash(Irantheme_JSON_Helper::encode($sections_array)));
            wp_die();
        }


        /**
         * Copy object json in section
         * @param void
         * @return bool
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        function copy_object()
        {
            $object_id = $_POST['object_id'];
            $json_data_name = $_POST['json_data_name'];
            $data_type = $_POST['data_type'];
            $data_value = $_POST['data_value'];
            $data_page = $_POST['data_page'];
            $data_ajax = $_POST['data_ajax'];

            // Check all data
            if (!$data_type || !$data_value || !$data_page || !$data_ajax || !$json_data_name || !$object_id) {
                @ini_set('display_errors', 0);
                wp_die();
            }

            // Add new section to page sections holder
            $sections_holder_name = irantheme_get_meta_data_sections_by_page_id();
            $sections_data_json = get_post_meta($data_page, $sections_holder_name, true);
            $sections_array = Irantheme_JSON_Helper::decode($sections_data_json);

            $object_id_created = '';

            // Loop from sections
            foreach ($sections_array['sections_items'] as &$section) {
                // Check section data ajax
                if ($section['data_ajax'] == $data_ajax) {
                    // Add temporary current section
                    $temp_section = $section['section_fields'];
                    foreach ($temp_section as $key => $value) {
                        // Check field with name of target field
                        if ($key == $json_data_name) {
                            // Json decoding objects (array or legacy string)
                            $object_json_fields = Irantheme_JSON_Helper::normalize_objects($value);
                            // Index for position assignment
                            $index = 0;
                            foreach ($object_json_fields as $object_item) {
                                $index++;
                                if ($object_item['object_id'] == $object_id) {
                                    // Copy new object array
                                    $new_object_item = $object_item;
                                    // Generate new object id for new object and assignment
                                    $object_id_created = Irantheme_Section_Default::get_generate_data_ajax(8);
                                    $new_object_item['object_id'] = $object_id_created;
                                    // Insert new object
                                    Irantheme_Section_Utils::array_insert($object_json_fields, $index, array($new_object_item));
                                    break;
                                }
                            }
                            // Keep objects_json as ARRAY (not string!)
                            $temp_section[$key] = $object_json_fields;
                            // Return temporary section to main holder section
                            $section['section_fields'] = $temp_section;
                            break;
                        }
                    }
                    break;
                }
            }

            // Update object with wp_slash
            update_post_meta($data_page, $sections_holder_name, wp_slash(Irantheme_JSON_Helper::encode($sections_array)));
            wp_die();
        }


        /**
         * move object fields with json
         * @param string
         * @return array
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_object_fields($section_data_value, $index = null, $status_default = false)
        {
            // Use map instead of if/elseif chain (much faster, no cache)
            if (!isset(self::$object_section_map[$section_data_value])) {
                return array();
            }

            $class = '\\' . __NAMESPACE__ . '\\' . self::$object_section_map[$section_data_value];
            if (!class_exists($class) || !method_exists($class, 'object_fields')) {
                return array();
            }

            return $class::object_fields($index, $status_default);
        }


        /**
         * Add new section and register fields
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        function add_section()
        {
            $data_type = $_POST['data_type'];
            $data_value = $_POST['data_value'];
            $data_page = $_POST['data_page'];
            $data_ajax = $_POST['data_ajax'];

            // Check all data
            if (
                !$data_type || !$data_value || !$data_page || !$data_ajax
            ) {
                @ini_set('display_errors', 0);
                wp_die();
            }

            // Add new section to page sections holder
            $sections_holder_name = irantheme_get_meta_data_sections_by_page_id();
            $sections_data_json = get_post_meta($data_page, $sections_holder_name, true);
            $sections_array = Irantheme_JSON_Helper::decode($sections_data_json);

            if (empty($sections_array) || !isset($sections_array['sections_items'])) {
                $sections_array = array();
                $sections_array['sections_items'] = array();
            }
            $sections_item = array(
                'data_type' => $data_type,
                'data_value' => $data_value,
                'data_page' => $data_page,
                'data_ajax' => $data_ajax,
                'section_fields' => array(),
            );
            $fields_settings_all = self::get_function_fields($data_value, 'media');
            foreach ($fields_settings_all as $fields_settings) {
                foreach ($fields_settings['fields'] as $fields) {
                    // Adding page config fields to array json
                    $sections_item['section_fields'] = array_merge($sections_item['section_fields'], array(
                        $fields['id'] => urldecode($fields['default'])
                    ));
                }
            }

            array_push($sections_array['sections_items'], $sections_item);
            update_post_meta($data_page, $sections_holder_name, wp_slash(Irantheme_JSON_Helper::encode($sections_array)));
            wp_die();
        }


        /**
         * Delete section from sections holder and delete fields of section deleted
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        function delete_section()
        {
            $data_type = $_POST['data_type'];
            $data_value = $_POST['data_value'];
            $data_page = $_POST['data_page'];
            $data_ajax = $_POST['data_ajax'];

            // Check all data
            if (
                !$data_type || !$data_value || !$data_page || !$data_ajax
            ) {
                @ini_set('display_errors', 0);
                wp_die();
            }

            // Remove target section from sections holder
            $sections_holder_name = irantheme_get_meta_data_sections_by_page_id();
            $sections_data = get_post_meta($data_page, $sections_holder_name, true);
            $sections_array = Irantheme_JSON_Helper::decode($sections_data);
            // Index for position assignment
            $index = 0;
            foreach ($sections_array['sections_items'] as $section_item) {
                if ($section_item['data_ajax'] == $data_ajax) {
                    // Remove object from data array
                    array_splice($sections_array['sections_items'], $index, 1);
                    break;
                }
                $index++;
            }
            update_post_meta($data_page, $sections_holder_name, wp_slash(Irantheme_JSON_Helper::encode($sections_array)));
            wp_die();
        }


        /**
         * Create options of section and return fields
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        function edit_section()
        {
            $data_type = $_POST['data_type'];
            $data_value = $_POST['data_value'];
            $data_page = $_POST['data_page'];
            $data_ajax = $_POST['data_ajax'];

            // Check all data
            if (
                !$data_type || !$data_value || !$data_page || !$data_ajax
            ) {
                @ini_set('display_errors', 0);
                wp_die();
            }

            $fields_settings_all = self::get_function_fields($data_value, false);

            if ($data_value == 'header') {
                // get_page_settings() hydrates header_fields with defaults
                $page_meta_data_array = self::get_page_settings($data_page, 'header');
                $action_type = 'header_fields';
                $page_fields_fetch = $page_meta_data_array;
            } else {
                // Get page sections
                $page_meta_data_array = self::get_page_settings($data_page, 'section', 'sections_items');

                $action_type = 'section_fields';
                $page_fields_fetch = array();

                // Loop from sections
                foreach ($page_meta_data_array as $section) {
                    // Check current section with data ajax sent
                    if ($section['data_ajax'] == $data_ajax) {
                        $page_fields_fetch = $section;
                        break;
                    }
                }

                if (empty($page_fields_fetch)) {
                    $page_fields_fetch = array('section_fields' => array());
                }

                // Hydrate missing fields in the section
                $fields_settings = Irantheme_Page_Builder_Ajax::get_function_fields($data_value, true);
                Irantheme_Page_Builder::hydrate_missing_fields(
                    $fields_settings,
                    $page_fields_fetch['section_fields'],
                    $data_value
                );
            }

            // Return data fields
            self::html_panel_ajax($data_page, $action_type, $fields_settings_all, $page_fields_fetch[$action_type], $data_value, $data_ajax);
            wp_die();
        }


        /**
         * Save received data ajax from preview sections edited
         * @param string,string
         * @return string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        function move_section()
        {
            // Get page id from ajax request
            $page_id = $_POST['page_id'];
            // Get order sections from ajax request
            $sections_order_raw = $_POST['sections_order_raw'];

            // Check all data
            if (!$page_id || !$sections_order_raw) {
                @ini_set('display_errors', 0);
                wp_die();
            }

            // Convert sections ajax to array
            $sections_order_ajax = explode('_', $sections_order_raw);
            $section_order_result = array(
                'sections_items' => array()
            );

            // Generate sections holder name
            $json_data_name = irantheme_get_meta_data_sections_by_page_id();
            // Get prev sections json
            $json_data_raw = get_post_meta($page_id, $json_data_name, true);
            // Convert all sections json to array
            $json_data_array = Irantheme_JSON_Helper::decode($json_data_raw);

            if (empty($json_data_array) || !isset($json_data_array['sections_items'])) {
                wp_die();
            }

            foreach ($sections_order_ajax as $order_ajax) {
                foreach ($json_data_array['sections_items'] as $array_item) {
                    if ($array_item['data_ajax'] == $order_ajax) {
                        array_push($section_order_result['sections_items'], $array_item);
                    }
                }
            }

            // Update sections order with wp_slash
            update_post_meta($page_id, $json_data_name, wp_slash(Irantheme_JSON_Helper::encode($section_order_result)));
            wp_die();
        }


        /**
         * Copy section json in section
         * @param void
         * @return bool
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        function copy_section()
        {
            $data_type = $_POST['data_type'];
            $data_value = $_POST['data_value'];
            $data_page = $_POST['data_page'];
            $data_ajax = $_POST['data_ajax'];

            // Check all data
            if (!$data_type || !$data_value || !$data_page || !$data_ajax) {
                @ini_set('display_errors', 0);
                wp_die();
            }

            // Add new section to page sections holder
            $sections_holder_name = irantheme_get_meta_data_sections_by_page_id();
            $sections_data_json = get_post_meta($data_page, $sections_holder_name, true);
            $sections_array = Irantheme_JSON_Helper::decode($sections_data_json);

            if (empty($sections_array) || !isset($sections_array['sections_items'])) {
                wp_die();
            }

            // Index for position assignment
            $index = 0;
            // Loop from sections
            foreach ($sections_array['sections_items'] as &$section) {
                // Increase index position (In start loop increased for append to next section)
                $index++;
                // Check section data ajax
                if ($section['data_ajax'] == $data_ajax) {
                    // Copy new section array
                    $new_section_item = $section;
                    // Generate new section id for new section and assignment
                    $section_id_created = Irantheme_Section_Default::get_generate_data_ajax(16);
                    $new_section_item['data_ajax'] = $section_id_created;
                    // Insert new object
                    Irantheme_Section_Utils::array_insert($sections_array['sections_items'], $index, array($new_section_item));
                    break;
                }
            }

            // Update object with wp_slash
            update_post_meta($data_page, $sections_holder_name, wp_slash(Irantheme_JSON_Helper::encode($sections_array)));
            wp_die();
        }


        /**
         * Return table of templates
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        function fetch_templates()
        {
?>
            <table class="irantheme-templates-table">
                <tbody class="table irantheme-templates-tbody">
                    <tr class="irantheme-templates-table-tr irantheme-templates-table-tr-heading">
                        <!-- Index -->
                        <th><?php _e('ردیف'); ?></th>
                        <!-- Title -->
                        <th><?php _e('عنوان'); ?></th>
                        <!-- Owner -->
                        <th><?php _e('کاربر سازنده'); ?></th>
                        <!-- Date -->
                        <th><?php _e('تاریخ ذخیره'); ?></th>
                        <!-- Status -->
                        <th><?php _e('وضعیت'); ?></th>
                        <!-- Append or delete -->
                        <th><?php _e('بارگذاری یا حذف'); ?></th>
                    </tr>
                    <?php
                    // Get template data from db
                    $holder_templates_json = get_option('irantheme_page_builder_templates');
                    $holder_templates_associative = Irantheme_JSON_Helper::decode($holder_templates_json);

                    // Get footer template ajax-code
                    $footer_template_code = get_option('irantheme_footer_template_select');
                    $footer_template_checkbox = get_option('irantheme_footer_template_checkbox');

                    // Check empty template data
                    if (!empty($holder_templates_associative)) {
                        $index = 0;
                        foreach ($holder_templates_associative as $template_tab) {
                            $index++;
                    ?>
                            <tr class="irantheme-templates-table-tr" data-type="<?php echo esc_attr($template_tab['data_type']); ?>" data-value="<?php echo esc_attr($template_tab['data_value']); ?>" data-ajax="<?php echo esc_attr($template_tab['data_ajax']); ?>" data-keywords="<?php echo esc_attr($template_tab['template_name']); ?>">
                                <!-- Index -->
                                <td>
                                    <span class="irantheme-templates-table-tr-index">
                                        <b>#<?php echo $index; ?></b>
                                    </span>
                                </td>
                                <!-- Title -->
                                <td>
                                    <span class="irantheme-templates-table-tr-title">
                                        <?php
                                        $template_tab_title = __('بدون عنوان', self::$text_domain);
                                        if ($template_tab['template_name']) {
                                            $template_tab_title = $template_tab['template_name'];
                                        }
                                        echo esc_html($template_tab_title);
                                        ?>
                                    </span>
                                </td>
                                <!-- Author -->
                                <td>
                                    <span class="irantheme-templates-table-tr-author"><?php _e($template_tab['template_author']); ?></span>
                                </td>
                                <!-- Date -->
                                <td>
                                    <span class="irantheme-templates-table-tr-date"><?php _e($template_tab['template_date']); ?></span>
                                </td>
                                <!-- Status -->
                                <td>
                                    <?php
                                    if ($footer_template_checkbox == 'on' && $template_tab['data_ajax'] == $footer_template_code):
                                    ?>
                                        <span class="irantheme-template-table-tr-status warning">
                                            <cite><?php _e('در حال استفاده توسط فوتر'); ?></cite>
                                        </span>
                                    <?php else: ?>
                                        <span class="irantheme-template-table-tr-status success">
                                            <cite><?php _e('وضعیت عادی'); ?></cite>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <!-- Actions -->
                                <td>
                                    <span class="irantheme-template-table-tr-group">
                                        <!-- Add template -->
                                        <button class="irantheme-template-table-tr-action" type="button" data-action="add">
                                            <i class="fa-light fa-download"></i>
                                            <i id="irantheme-spinner-alt" class="irantheme-animate-rotate"></i>
                                            <svg class="irantheme-success-checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                                                <circle class="irantheme-success-checkmark-circle" cx="26" cy="26" r="25" fill="none" />
                                                <path class="irantheme-success-checkmark-check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" />
                                            </svg>
                                            <em><?php _e('بارگذاری'); ?></em>
                                        </button>
                                        <!-- Remove template -->
                                        <button class="irantheme-template-table-tr-action remove" type="button" data-action="remove">
                                            <i class="fa-light fa-trash-xmark"></i>
                                            <i id="irantheme-spinner-alt" class="irantheme-animate-rotate"></i>
                                            <em><?php _e('حذف کردن'); ?></em>
                                        </button>
                                    </span>
                                </td>
                            </tr>
                        <?php
                        }
                    } else {
                        ?>
                        <!-- Empty template -->
                        <tr class="irantheme-templates-table-tr">
                            <td colspan="6">
                                <span class="irantheme-template-table-tr-empty">
                                    <i class="fa-light fa-brake-warning"></i>
                                    <?php _e('الگویی موجود نمی باشد.') ?>
                                </span>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        <?php
            wp_die();
        }


        /**
         * Add section to holder template json
         * @param void
         * @return bool
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        function create_template()
        {
            $data_type = $_POST['data_type'];
            $data_value = $_POST['data_value'];
            $data_page = $_POST['data_page'];
            $data_ajax = $_POST['data_ajax'];
            $template_name = $_POST['template_name'];

            // Check all data
            if (!$data_type || !$data_value || !$data_page || !$data_ajax || !$template_name) {
                @ini_set('display_errors', 0);
                wp_die();
            }

            // Add new section to page sections holder
            $sections_holder_name = irantheme_get_meta_data_sections_by_page_id();
            $sections_data_json = get_post_meta($data_page, $sections_holder_name, true);
            $sections_array = Irantheme_JSON_Helper::decode($sections_data_json);
            // Fetch holder templates
            $templates_holder = get_option('irantheme_page_builder_templates');
            $templates_array = Irantheme_JSON_Helper::decode($templates_holder);

            if (empty($sections_array) || !isset($sections_array['sections_items'])) {
                wp_die();
            }

            // Loop from sections
            foreach ($sections_array['sections_items'] as &$section) {
                // Check section data ajax
                if ($section['data_ajax'] == $data_ajax) {
                    // Template new section array
                    $new_template_item = $section;
                    // Generate new section id for new section and assignment
                    $section_id_created = Irantheme_Section_Default::get_generate_data_ajax(16);
                    $new_template_item['data_ajax'] = $section_id_created;
                    // Set template name
                    $new_template_item['template_name'] = $template_name;
                    // Get current user
                    $current_user = wp_get_current_user();
                    $new_template_item['template_author'] = $current_user->user_login;
                    // Get current time as a timestamp
                    $current_time = current_time('timestamp');
                    $formatted_date = date_i18n('F j, Y H:i:s', $current_time);
                    $new_template_item['template_date'] = $formatted_date;
                    // Insert new section
                    array_push($templates_array, $new_template_item);
                    break;
                }
            }

            // Update holder templates with wp_slash
            update_option('irantheme_page_builder_templates', wp_slash(Irantheme_JSON_Helper::encode($templates_array)));
            wp_die();
        }


        /**
         * Add section to holder template json
         * @param void
         * @return bool
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        function add_template()
        {
            $data_type = $_POST['data_type'];
            $data_value = $_POST['data_value'];
            $data_page = $_POST['data_page'];
            $data_ajax = $_POST['data_ajax'];

            // Check all data
            if (!$data_type || !$data_value || !$data_page || !$data_ajax) {
                @ini_set('display_errors', 0);
                wp_die();
            }

            // Add new section to page sections holder
            $sections_holder_name = irantheme_get_meta_data_sections_by_page_id();
            $sections_data_json = get_post_meta($data_page, $sections_holder_name, true);
            $sections_array = Irantheme_JSON_Helper::decode($sections_data_json);

            if (empty($sections_array) || !isset($sections_array['sections_items'])) {
                $sections_array = array();
                $sections_array['sections_items'] = array();
            }
            // Fetch holder templates
            $templates_holder = get_option('irantheme_page_builder_templates');
            $templates_array = Irantheme_JSON_Helper::decode($templates_holder);

            // Loop from sections
            foreach ($templates_array as &$template) {
                // Check template data ajax
                if (isset($template['data_ajax']) && $template['data_ajax'] == $data_ajax) {
                    // Template new template array
                    $new_template_item = $template;
                    // Generate new template id for new template and assignment
                    $template_id_created = Irantheme_Section_Default::get_generate_data_ajax(16);
                    $new_template_item['data_ajax'] = $template_id_created;
                    $new_template_item['data_page'] = $data_page;
                    unset($new_template_item['template_name']);
                    unset($new_template_item['template_author']);
                    unset($new_template_item['template_date']);

                    if (!isset($new_template_item['section_fields']) || !is_array($new_template_item['section_fields'])) {
                        $new_template_item['section_fields'] = array();
                    }

                    // Normalize objects_json fields in template section
                    if (array_key_exists('objects_json', $new_template_item['section_fields'])) {
                        $new_template_item['section_fields']['objects_json'] = Irantheme_JSON_Helper::normalize_objects(
                            $new_template_item['section_fields']['objects_json']
                        );
                    }

                    // Prevent to error of new fields updates
                    $fields_settings_all = Irantheme_Page_Builder_Ajax::get_function_fields($new_template_item['data_value'], true);
                    Irantheme_Page_Builder::hydrate_missing_fields($fields_settings_all, $new_template_item['section_fields'], $data_value);

                    // Insert new template
                    array_push($sections_array['sections_items'], $new_template_item);
                    break;
                }
            }

            // Update holder templates with wp_slash
            update_post_meta($data_page, $sections_holder_name, wp_slash(Irantheme_JSON_Helper::encode($sections_array)));
            wp_die();
        }


        /**
         * Remove section to holder template json
         * @param void
         * @return bool
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        function remove_template()
        {
            $data_type = $_POST['data_type'];
            $data_value = $_POST['data_value'];
            $data_page = $_POST['data_page'];
            $data_ajax = $_POST['data_ajax'];

            // Check all data
            if (!$data_type || !$data_value || !$data_page || !$data_ajax) {
                @ini_set('display_errors', 0);
                wp_die();
            }

            // Fetch holder templates
            $templates_holder = get_option('irantheme_page_builder_templates');
            $templates_array = Irantheme_JSON_Helper::decode($templates_holder);

            // Index for position assignment
            $index = 0;

            // Loop from sections
            foreach ($templates_array as &$template) {
                // Check template data ajax
                if (isset($template['data_ajax']) && $template['data_ajax'] == $data_ajax) {
                    // Remove object from data array
                    array_splice($templates_array, $index, 1);
                    break;
                }
                $index++;
            }

            // Update holder templates with wp_slash
            update_option('irantheme_page_builder_templates', wp_slash(Irantheme_JSON_Helper::encode($templates_array)));
            wp_die();
        }


        /**
         * Update section fields sended by form
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        function update_options()
        {
            // Get form data ajax (unslash first to remove WP slashes)
            $data_form = isset($_POST['data_form']) ? wp_unslash($_POST['data_form']) : '';
            // Get action type
            $action_type = isset($_POST['action_type']) ? sanitize_text_field($_POST['action_type']) : '';
            // Get page id
            $page_id = isset($_POST['data_page']) ? absint($_POST['data_page']) : 0;
            // Get data ajax
            $data_ajax = isset($_POST['data_ajax']) ? sanitize_text_field($_POST['data_ajax']) : '';
            // Get object id
            $object_id = isset($_POST['object_id']) ? sanitize_text_field($_POST['object_id']) : '';
            // Get json data name and get value
            $json_object_field_name = isset($_POST['json_object_name']) ? sanitize_text_field($_POST['json_object_name']) : '';

            // Parse form data properly (handles HTML entities, = and & inside values)
            $parsed_fields = array();
            wp_parse_str($data_form, $parsed_fields);

            if ($action_type == 'page_config_fields') {
                // Get page meta data json
                $page_meta_data_name = irantheme_get_meta_data_page_config_by_page_id();
                // Get page meta data json
                $page_meta_data_array = self::get_page_settings($page_id, 'page_config');
                // Loop from parsed data fields
                foreach ($parsed_fields as $field_key => $field_value) {
                    // Update holder json array key with new value
                    $page_meta_data_array[$action_type][$field_key] = $field_value;
                }
            } elseif ($action_type == 'header_fields') {
                // Get page meta data json
                $page_meta_data_name = irantheme_get_meta_data_header_by_page_id();
                // Get page meta data json
                $page_meta_data_array = self::get_page_settings($page_id, 'header');
                // Loop from parsed data fields
                foreach ($parsed_fields as $field_key => $field_value) {
                    // Update holder json array key with new value
                    $page_meta_data_array[$action_type][$field_key] = $field_value;
                }
            } elseif ($action_type == 'section_fields') {
                // Get page meta data json
                $page_meta_data_name = irantheme_get_meta_data_sections_by_page_id();
                // Get page meta data json
                $page_meta_data_array = self::get_page_settings($page_id, 'section');
                if (!empty($page_meta_data_array['sections_items'])) {
                    foreach ($page_meta_data_array['sections_items'] as &$section) {
                        if ($section['data_ajax'] == $data_ajax) {
                            // Loop from parsed data fields
                            foreach ($parsed_fields as $field_key => $field_value) {
                                // Update holder json array key with new value
                                $section['section_fields'][$field_key] = $field_value;
                            }
                            break;
                        }
                    }
                    unset($section);
                }
            } elseif ($action_type == 'save_object') {
                // Get page meta data json
                $page_meta_data_name = irantheme_get_meta_data_sections_by_page_id();
                // Get page meta data json
                $page_meta_data_array = self::get_page_settings($page_id, 'section');

                if (!empty($page_meta_data_array['sections_items'])) {
                    // Loop from sections
                    foreach ($page_meta_data_array['sections_items'] as &$section) {
                        // Check section data ajax
                        if ($section['data_ajax'] == $data_ajax) {
                            // Add temporary current section
                            $temp_section = $section['section_fields'];
                            foreach ($temp_section as $key => $value) {
                                // Check field with name of target field
                                if ($key == $json_object_field_name) {
                                    // Json decoding objects (array or legacy string)
                                    $object_json_fields = Irantheme_JSON_Helper::normalize_objects($value);
                                    $holder_array_result = array();
                                    foreach ($object_json_fields as $object_field_item) {
                                        if ($object_field_item['object_id'] == $object_id) {
                                            // Loop from parsed data fields
                                            foreach ($parsed_fields as $field_key => $field_value) {
                                                if (!isset($object_field_item['fields']) || !is_array($object_field_item['fields'])) {
                                                    $object_field_item['fields'] = array();
                                                }
                                                $object_field_item['fields'][$field_key] = $field_value;
                                            }
                                        }
                                        array_push($holder_array_result, $object_field_item);
                                    }
                                    // Keep objects_json as ARRAY (not string!)
                                    $temp_section[$key] = $holder_array_result;
                                    // Return temporary section to main holder section
                                    $section['section_fields'] = $temp_section;
                                    break;
                                }
                            }
                            break;
                        }
                    }
                    unset($section);
                }
            }

            // Update page with wp_slash
            update_post_meta($page_id, $page_meta_data_name, wp_slash(Irantheme_JSON_Helper::encode($page_meta_data_array)));

            // Clearing catch
            wp_die();
        }


        /**
         * Header recovery fields values with settings options
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        function header_recovery()
        {
            // Get page id
            $data_page = $_POST['data_page'];
            // Get data ajax
            $data_type = $_POST['data_type'];
            // Get data ajax
            $data_value = $_POST['data_value'];
            // Get data ajax
            $data_ajax = $_POST['data_ajax'];

            // Check all data
            if (!$data_type || !$data_value || !$data_page || !$data_ajax) {
                @ini_set('display_errors', 0);
                wp_die();
            }

            // Get page meta data json
            $page_meta_data_name = irantheme_get_meta_data_header_by_page_id();
            // Get page meta data json
            $page_meta_data_array = self::get_page_settings($data_page, 'header');
            // Holder result fields
            $page_fields_fetch = $page_meta_data_array;
            // Check page config key is exists?
            $fields_settings_all = self::get_function_fields($data_value, true);
            // Append page config empty array
            $page_fields_fetch['header_fields'] = array();
            foreach ($fields_settings_all as $fields_settings) {
                foreach ($fields_settings['fields'] as $fields) {
                    // Adding page config fields to array json
                    $page_fields_fetch['header_fields'] = array_merge($page_fields_fetch['header_fields'], array(
                        $fields['id'] => get_option($fields['id'])
                    ));
                }
            }
            // Enable header customization customary
            $page_fields_fetch['header_fields']['irantheme_header_customizable_checkbox'] = 'on';

            // Update page with wp_slash
            update_post_meta($data_page, $page_meta_data_name, wp_slash(Irantheme_JSON_Helper::encode($page_fields_fetch)));
            // Clearing catch
            wp_die();
        }


        // /**
        //  * Page html generator
        //  * @param void
        //  * @return void
        //  * @author iranianclever
        //  * @since v1.0
        //  * @see v1.0
        //  */
        // function save_page_html_to_meta($page_id)
        // {
        //     // Get page meta data json
        //     $page_config_fields = self::get_page_settings($page_id, 'page_config', 'page_config_fields');
        //     // Get page is customizable
        //     $page_customizable = $page_config_fields['irantheme_page_customizable_checkbox'] ?? 'off';
        //     $page_content_rendered = '';
        //     // Check page builder is enabled
        //     if ($page_customizable == 'on') :
        //         ob_start();
        //         Irantheme_Header_Main::get_header($page_id);
        //         Irantheme_Page_Builder::display_section($page_id);
        //         get_footer();
        //         $page_content_rendered .= ob_get_clean();
        //     endif;
        //     update_post_meta($page_id, irantheme_get_meta_data_html_by_page_id(), $page_content_rendered);
        // }


        /**
         * Restore default fields
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        function restore_default()
        {
            // Get action type
            $action_type = $_POST['action_type'];
            // Get page id
            $data_page = $_POST['data_page'];
            // Get data ajax
            $data_ajax = $_POST['data_ajax'];
            // Get data value
            $data_value = $_POST['data_value'];
            // Get object id
            $object_id = $_POST['object_id'];
            // Get json data name and get value
            $json_object_field_name = $_POST['json_object_name'];

            // Check all data
            if (!$action_type || !$data_page) {
                @ini_set('display_errors', 0);
                wp_die();
            }

            $fields_settings_all = array();
            $fields_target = array();

            if ($action_type == 'page_config_fields') {
                // Get page fields ----------------------------------------------------
                $fields_settings_all = self::get_function_fields('page_config_settings', true);

                foreach ($fields_settings_all as $fields_settings) {
                    foreach ($fields_settings['fields'] as $fields) {
                        // Adding page config fields to array json
                        $fields_target = array_merge($fields_target, array(
                            $fields['id'] => $fields['default']
                        ));
                    }
                }
            } elseif ($action_type == 'header_fields') {
                // Get header fields ----------------------------------------------------
                $fields_settings_all = self::get_function_fields('header', true);

                foreach ($fields_settings_all as $fields_settings) {
                    foreach ($fields_settings['fields'] as $fields) {
                        // Adding page config fields to array json
                        $fields_target = array_merge($fields_target, array(
                            $fields['id'] => $fields['default']
                        ));
                    }
                }
            } elseif ($action_type == 'section_fields') {
                // Get section fields ----------------------------------------------------
                $fields_settings_all = self::get_function_fields($data_value, true);

                foreach ($fields_settings_all as $fields_settings) {
                    foreach ($fields_settings['fields'] as $fields) {
                        // Adding page config fields to array json
                        $fields_target = array_merge($fields_target, array(
                            $fields['id'] => $fields['default']
                        ));
                    }
                }
            } elseif ($action_type == 'save_object') {
                // Get object fields with data value ----------------------------------------------------
                $fields_settings_all = self::get_object_fields($data_value, 0);

                foreach ($fields_settings_all as $fields_settings) {
                    foreach ($fields_settings['fields'] as $fields) {
                        // Adding page config fields to array json
                        $fields_target = array_merge($fields_target, array(
                            $fields['id'] => $fields['default']
                        ));
                    }
                }
            }

            // Return data fields
            self::html_panel_ajax($data_page, $action_type, $fields_settings_all, $fields_target, $data_value, $data_ajax, $object_id, $json_object_field_name);
            // Clearing catch
            wp_die();
        }


        /**
         * Render fields panel
         *
         * get_page_settings() already hydrates page_config_fields with defaults
         * from page_config_settings(), so this method only needs to fetch the
         * field definitions and hand both to the renderer.
         *
         * @param string $page_config_type
         * @param string $page_id
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function render_fields_panel($page_config_type, $page_id)
        {
            // get_page_settings() hydrates page_config_fields with defaults
            $page_meta_data_array = self::get_page_settings($page_id, 'page_config');

            // Get field definitions for rendering (status_default = false for UI)
            $fields_settings_all = self::get_function_fields($page_config_type, false);

            // Render
            self::html_panel_ajax(
                $page_id,
                'page_config_fields',
                $fields_settings_all,
                $page_meta_data_array['page_config_fields']
            );
        }


        /**
         * Get page settings by page id and meta type
         * 
         * Reads stored settings, ensures the expected meta key is present as an
         * array, and hydrates page_config_fields / header_fields with their
         * defaults from the schema. Sections are not hydrated here because each
         * section's fields are hydrated by their own rendering/hydration path.
         *
         * @param string $page_id
         * @param string $meta_type  page_config | header | section
         * @param string $key        Optional sub-key to return only that slice
         * @return array
         * @author iranianclever
         * @since v1.0
         * @version v1.0.6
         */
        public static function get_page_settings($page_id, $meta_type, $key = '')
        {
            if ($meta_type === 'page_config') {
                // Get page meta data json
                $page_meta_data_name = irantheme_get_meta_data_page_config_by_page_id();
            } elseif ($meta_type === 'header') {
                // Get page meta data json
                $page_meta_data_name = irantheme_get_meta_data_header_by_page_id();
            } elseif ($meta_type === 'section') {
                // Get page meta data json
                $page_meta_data_name = irantheme_get_meta_data_sections_by_page_id();
            } else {
                return array();
            }

            $meta_value = get_post_meta($page_id, $page_meta_data_name, true);

            // Use JSON Helper decode (handles legacy and new formats)
            $page_meta_data_array = Irantheme_JSON_Helper::decode($meta_value);

            if (!is_array($page_meta_data_array)) {
                $page_meta_data_array = array();
            }

            // Hydrate page_config_fields with defaults from page_config_settings()
            if ($meta_type === 'page_config') {
                if (!isset($page_meta_data_array['page_config_fields']) || !is_array($page_meta_data_array['page_config_fields'])) {
                    $page_meta_data_array['page_config_fields'] = array();
                }
                Irantheme_Page_Builder::hydrate_missing_fields(
                    Irantheme_Section_Utils::page_config_settings(),
                    $page_meta_data_array['page_config_fields']
                );
            }

            // Hydrate header_fields with defaults from header()
            if ($meta_type === 'header') {
                if (!isset($page_meta_data_array['header_fields']) || !is_array($page_meta_data_array['header_fields'])) {
                    $page_meta_data_array['header_fields'] = array();
                }
                Irantheme_Page_Builder::hydrate_missing_fields(
                    Irantheme_Section_Utils::header(),
                    $page_meta_data_array['header_fields']
                );
            }

            if ($key) {
                return isset($page_meta_data_array[$key]) ? $page_meta_data_array[$key] : array();
            }

            return $page_meta_data_array;
        }


        /**
         * Create a panel ajax for page config options, this function get showcase type and create it.
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        function create_options_page()
        {
            $page_config_id = $_POST['page_id'];
            $page_config_type = $_POST['page_type'];

            // Check all data
            if (!$page_config_id || !$page_config_type) {
                wp_die();
            }

            self::render_fields_panel($page_config_type, $page_config_id);
            wp_die();
        }


        /**
         * Return array of all footer templates
         * @return array
         * @param void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_all_footer_templates()
        {
            // Fetch holder templates
            $templates_holder = get_option('irantheme_page_builder_templates');
            $templates_array = Irantheme_JSON_Helper::decode($templates_holder);
            $result_array = array();
            foreach ($templates_array as $template) {
                if ($template['data_type'] == 'footer') {
                    // Create array
                    $temp = array(
                        $template['data_ajax'] => __('فوتر : ', self::$text_domain) . $template['template_name'],
                    );
                    // Push temp
                    $result_array = array_replace($result_array, $temp);
                }
            }
            return $result_array;
        }


        /**
         * Get function fields match
         * @param string,bool
         * @return array
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_function_fields($data_value, $status_default)
        {
            // Handle special cases
            if ($data_value == 'page_config_settings') {
                return Irantheme_Section_Utils::page_config_settings();
            }
            if ($data_value == 'header') {
                return Irantheme_Section_Utils::header();
            }

            // Use map instead of switch (much faster, no cache)
            if (!isset(self::$fields_section_map[$data_value])) {
                return array();
            }

            $class = '\\' . __NAMESPACE__ . '\\' . self::$fields_section_map[$data_value];
            if (!class_exists($class) || !method_exists($class, 'fields')) {
                return array();
            }

            return $class::fields($status_default);
        }


        /**
         * Generate HTML for displaying panel fields
         * @param string,string,string,string,string
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function html_panel_ajax($data_page, $data_action, $fields_settings_raw, $fields_settings_values, $data_value = null, $data_ajax = null, $data_object = null, $data_json_object = null)
        {
            // Safety net: ensure values are fully hydrated before rendering.
            // This protects against callers that forget to hydrate upstream.
            Irantheme_Page_Builder::hydrate_missing_fields(
                $fields_settings_raw,
                $fields_settings_values,
                $data_value
            );
        ?>
            <!-- Panel ajax -->
            <div class="irantheme-settings-visual-panel-ajax" data-action="<?php echo esc_attr($data_action); ?>" data-value="<?php echo esc_attr($data_value); ?>" data-page="<?php echo esc_attr($data_page); ?>" data-ajax="<?php echo esc_attr($data_ajax); ?>" data-object="<?php echo esc_attr($data_object); ?>" data-json-object="<?php echo esc_attr($data_json_object); ?>">
                <div class="irantheme-settings-visual-panel-ajax-holder">
                    <!-- Settings options -->
                    <div class="irantheme-settings-options">
                        <?php
                        // Get section fields
                        $fields_settings_all = $fields_settings_raw;
                        foreach ($fields_settings_all as $fields_settings) :
                        ?>
                            <!-- Wrapper options -->
                            <div class="irantheme-settings-options-wrapper">
                                <!-- Toggle heading of options -->
                                <div class="irantheme-settings-options-wrapper-toggle">
                                    <!-- Icon -->
                                    <div class="irantheme-settings-options-wrapper-toggle-icon">
                                        <i class="fa-light <?php echo esc_attr($fields_settings['icon']); ?>"></i>
                                    </div>
                                    <!-- Heading -->
                                    <div class="irantheme-settings-options-wrapper-toggle-heading">
                                        <h2><?php _e($fields_settings['title']); ?></h2>
                                        <!-- <p><?php _e($fields_settings['description']); ?></p> -->
                                    </div>
                                    <!-- Icon Angle -->
                                    <div class="irantheme-settings-options-wrapper-toggle-angle">
                                        <i class="iconsax" icon-name="add"></i>
                                    </div>
                                </div>
                                <!-- Holder options -->
                                <div class="irantheme-settings-options-wrapper-holder" style="display:none;">
                                    <table class="irantheme-settings-visual-panel-ajax-table form-table">
                                        <tbody>
                                            <?php foreach ($fields_settings['fields'] as $field_item) :
                                                if ($field_item['type'] == 'hidden_json') {
                                                    continue;
                                                }
                                                $value = isset($fields_settings_values[$field_item['id']]) ? $fields_settings_values[$field_item['id']] : '';
                                            ?>
                                                <tr>
                                                    <th scope="row"><?php _e($field_item['label'], self::$text_domain); ?></th>
                                                    <td><?php echo Irantheme_Settings::display_fields($field_item, $field_item['id'], $value); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php
                        endforeach;
                        ?>
                    </div>
                </div>
            </div>
<?php
        }
    }
}
