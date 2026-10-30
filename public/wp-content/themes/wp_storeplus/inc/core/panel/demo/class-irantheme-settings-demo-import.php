<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;


if (!class_exists('Irantheme_Settings_Demo_Import')) {

    /**
     * Irantheme demo import
     * Configuration of demo import
     * 
     * @package Wordpress
     * @subpackage Storeplus
     * @since v1.0
     * @author M-MALEKZADEH77
     * @see v1.0
     * 
     * Demo import
     */
    class Irantheme_Settings_Demo_Import
    {
        // page_title => new_page_id
        private static $array_of_new_page_id_by_title;
        // page_old_id => new_page_id
        private static $array_of_new_page_id_by_old_id;
        // old_post_id => new_post_id
        private static $array_of_new_post_id;
        // old_product_id => new_product_id
        private static $array_of_new_product_id;
        // old_post_cat_id => new_post_cat_id
        private static $array_of_new_post_cat_id;
        // old_product_cat_id => new_product_cat_id
        private static $array_of_new_product_cat_id;
        // old_nav_menu_id => new_nav_menu_id
        private static $array_of_new_menu_id;
        // Constructor initialize
        public function __construct()
        {
            self::$array_of_new_page_id_by_title = array();
            self::$array_of_new_page_id_by_old_id = array();
            self::$array_of_new_post_id = array();
            self::$array_of_new_product_id = array();
            self::$array_of_new_post_cat_id = array();
            self::$array_of_new_product_cat_id = array();
            self::$array_of_new_menu_id = array();
        }


        /**
         * Import demo from xml file url
         * @param string
         * @return void
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        public static function import_demo_from_xml_url($xml_url)
        {
            // Get xml file url
            $xml = simplexml_load_file($xml_url);

            self::install_plugin_required_before_import($xml);
            self::import_wc_attributes_from_xml($xml); // ADD THIS LINE
            self::import_pages_from_xml($xml);
            self::import_post_cat_from_xml($xml);
            self::import_post_from_xml($xml);
            self::import_wc_product_cat_from_xml($xml);
            self::import_wc_product_from_xml($xml);
            self::import_nav_menus_from_xml($xml);
            self::import_sidebar_from_xml($xml);
            self::import_woocommerce_extra_options_from_xml($xml);
            self::import_sections_templates_from_xml($xml);
            self::import_settings_options_from_xml($xml);
            self::import_page_config_from_xml($xml);
            self::import_page_header_settings_from_xml($xml);
            self::import_pages_sections_from_xml($xml);
        }


        /**
         * Import page from xml content
         * @param string
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function import_page_from_xml($xml, $page_id)
        {
            self::import_wc_attributes_from_xml($xml); // ADD THIS LINE
            self::import_pages_from_xml($xml, $page_id);
            self::import_post_cat_from_xml($xml);
            self::import_post_from_xml($xml);
            self::import_wc_product_cat_from_xml($xml);
            self::import_wc_product_from_xml($xml);
            self::import_nav_menus_from_xml($xml);
            self::import_sidebar_from_xml($xml);
            self::import_woocommerce_extra_options_from_xml($xml);
            self::import_page_config_from_xml($xml);
            self::import_page_header_settings_from_xml($xml);
            self::import_pages_sections_from_xml($xml);
        }


        /**
         * Check json validation
         * @param string
         * @return void
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        public static function json_validate($json_string): bool
        {
            if (is_string(strval($json_string))) {
                json_decode($json_string);
                return json_last_error() === JSON_ERROR_NONE && !is_numeric(strval($json_string));
            } else {
                return false;
            }
        }

        /**
         * Remove CDATA section from string
         * @param string $string
         * @return string
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        public static function removeCData($string)
        {
            $pattern = '/<!\[CDATA\[(.*?)\]\]>/s';

            $result = '';
            preg_replace_callback($pattern, function ($matches) use (&$result) {
                $result .= $matches[1] . ' '; // Append the CDATA content to the result
            }, $string);

            // Trim any trailing space
            $result = trim($result);
            if ($result == '') {
                return $string;
            } else {
                return $result;
            }
        }

        /**
         * Upload media by filed type for save field settings value in import demo
         * @param string $value
         * @param string $field_type
         * @return string|bool attachment id or strings of urls | false
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        public static function media_uploader_by_type($value, $field_type)
        {
            $media_var = false;

            // Check value string is url?
            if (filter_var($value, FILTER_VALIDATE_URL)) {
                // Check url is image or video?
                if ($field_type == 'video' || $field_type == 'image') {
                    // Upload and get attachment id
                    $media_id = Irantheme_Common_Static::get_file_url_media_library($value);
                    if ($media_id) {
                        // Replace media id to url value
                        $media_var = $media_id;
                    }
                } elseif ($field_type == 'media_gallery') {
                    // Check if value contains comma (multiple values) or single value
                    if (strpos($value, ',') !== false) {
                        // Multiple URLs case
                        $urls = explode(',', $value);
                        $media_ids = array();
                        foreach ($urls as $url) {
                            $url = trim($url); // Clean whitespace
                            if (filter_var($url, FILTER_VALIDATE_URL)) {
                                // Upload and get attachment url
                                $attachment_url = wp_get_attachment_url(Irantheme_Common_Static::get_file_url_media_library($url));
                                if ($attachment_url) {
                                    array_push($media_ids, $attachment_url);
                                }
                            }
                        }
                        if (!empty($media_ids)) {
                            // Replace media id to url value
                            $media_var = implode(',', $media_ids);
                        }
                    } else {
                        // Single URL case (no comma)
                        $attachment_url = wp_get_attachment_url(Irantheme_Common_Static::get_file_url_media_library($value));
                        if ($attachment_url) {
                            $media_var = $attachment_url;
                        }
                    }
                }
            }

            return $media_var;
        }

        /**
         * Upload media without filed type (by search in string) for save field settings value in import demo
         * @param string $value
         * @return string|bool attachment id or strings of urls | false
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        public static function media_uploader_without_type($value)
        {
            $media_var = false;
            // url extension holder
            $media_ext = '';
            // Media id holder
            $media_id = '';
            // Check value string is url?
            if (filter_var($value, FILTER_VALIDATE_URL)) {
                // Get url extension
                $media_ext
                    = explode('/', wp_check_filetype($value)['type'])[0];
                // Check url is image or video?
                if ($media_ext == 'video' || $media_ext == 'image') {
                    // Upload and get attachment id
                    $media_id = Irantheme_Common_Static::get_file_url_media_library($value);
                    // Replace media id to url value
                    if ($media_id) {
                        $media_var = $media_id;
                    }
                }
                // For media gallery
                // Check string and find ','
            } elseif (strpos($value, ',') !== false) {
                // Explode string with ','
                $urls = explode(',', $value);
                // Array of media id
                $media_ids = array();

                foreach ($urls as $url) {
                    // If current string has url
                    if (filter_var($url, FILTER_VALIDATE_URL)) {
                        // Upload and get attachment id
                        array_push($media_ids, wp_get_attachment_url(Irantheme_Common_Static::get_file_url_media_library($url)));
                    }
                }
                if ($media_ids) {
                    // Replace new urls string to $option_value
                    $media_var = implode(',', $media_ids);
                }
            }
            return $media_var;
        }

        /**
         * Check field type is select by name
         * @param string $field_name
         * @return string
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        public static function field_type_is_select_by_name($field_name, $field_value)
        {
            $new_value_array = array();
            $array_of_value = explode(',', $field_value);
            if ($field_name == 'post_category_selection') {
                foreach ($array_of_value as $value) {
                    if (in_array($value, array_keys(self::$array_of_new_post_cat_id))) {
                        array_push($new_value_array, self::$array_of_new_post_cat_id[$value]);
                    }
                }
                $array_of_value = $new_value_array;
            } elseif ($field_name == 'product_category_selection' || $field_name == 'product_once_category_selection') {
                foreach ($array_of_value as $value) {
                    if (in_array($value, array_keys(self::$array_of_new_product_cat_id))) {
                        array_push($new_value_array, self::$array_of_new_product_cat_id[$value]);
                    }
                }
                $array_of_value = $new_value_array;
            } elseif ($field_name == 'post_selection') {
                foreach ($array_of_value as $value) {
                    if (in_array($value, array_keys(self::$array_of_new_post_id))) {
                        array_push($new_value_array, self::$array_of_new_post_id[$value]);
                    }
                }
                $array_of_value = $new_value_array;
            } elseif ($field_name == 'product_selection') {
                foreach ($array_of_value as $value) {
                    if (in_array($value, array_keys(self::$array_of_new_product_id))) {
                        array_push($new_value_array, self::$array_of_new_product_id[$value]);
                    }
                }
                $array_of_value = $new_value_array;
            } elseif ($field_name == 'menu_select' || preg_match('/^irantheme_.+_menu_select$/', $field_name)) {
                foreach ($array_of_value as $value) {
                    if (in_array($value, array_keys(self::$array_of_new_menu_id))) {
                        array_push($new_value_array, self::$array_of_new_menu_id[$value]);
                    }
                }
                $array_of_value = $new_value_array;
            }
            return implode(',', $array_of_value);
        }

        /**
         * Install or activated plugin required before import demo
         * @param object $xml object of xml file 
         * @return void
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        public static function install_plugin_required_before_import($xml)
        {
            $plugin_field = Irantheme_Settings_Plugins::get_plugins_fields();
            foreach ($xml->irantheme_plugins_requirements->plugin_item as $plugin_item_key => $plugin_item_value) {
                foreach ($plugin_item_value as $key => $value) {
                    foreach ($plugin_field as $field) {
                        if ($field['slug'] == strval($value)) {
                            $plugin_name = $field['name'];
                            $plugin_slug = $field['slug'];
                        }
                    }
                    $install_status = Irantheme_Settings_Plugins::is_plugin_installed($plugin_name);
                    $plugins = Irantheme_Settings_Plugins::get_plugins_attributes();
                    if ($install_status) {
                        $active_status = Irantheme_Settings_Plugins::is_plugin_activate(strval($plugin_slug));
                        if (!$active_status) {
                            activate_plugin($plugins[$plugin_slug]);
                        }
                    } else {
                        Irantheme_Settings_Plugins::install_plugin($plugin_slug);
                        activate_plugin($plugins[$plugin_slug]);
                    }
                }
            }
        }


        /**
         * Import WooCommerce attributes from XML
         * @param object $xml object of xml file 
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function import_wc_attributes_from_xml($xml)
        {
            if (!is_woocommerce_activated()) {
                return;
            }

            global $wpdb;

            foreach ($xml->irantheme_wc_attributes->attribute_item as $attribute_data) {
                $attribute_id = (string)$attribute_data->id;
                $attribute_name = (string)$attribute_data->name;
                $attribute_label = (string)$attribute_data->label;
                $attribute_type = (string)$attribute_data->type;
                $attribute_orderby = (string)$attribute_data->orderby;
                $has_archives = (string)$attribute_data->has_archives;

                // Get taxonomy type
                $taxonomy_type = isset($attribute_data->irantheme_taxonomy_type) ? (string)$attribute_data->irantheme_taxonomy_type : 'normal';

                // Check if attribute already exists
                $existing_attribute = $wpdb->get_row(
                    $wpdb->prepare(
                        "SELECT attribute_id FROM {$wpdb->prefix}woocommerce_attribute_taxonomies 
                WHERE attribute_name = %s",
                        $attribute_name
                    )
                );

                if ($existing_attribute) {
                    // Update existing attribute
                    $wpdb->update(
                        $wpdb->prefix . 'woocommerce_attribute_taxonomies',
                        array(
                            'attribute_label' => $attribute_label,
                            'attribute_type' => $attribute_type,
                            'attribute_orderby' => $attribute_orderby,
                            'attribute_public' => $has_archives,
                        ),
                        array('attribute_name' => $attribute_name)
                    );
                    $new_attribute_id = $existing_attribute->attribute_id;
                } else {
                    // Insert new attribute
                    $wpdb->insert(
                        $wpdb->prefix . 'woocommerce_attribute_taxonomies',
                        array(
                            'attribute_name' => $attribute_name,
                            'attribute_label' => $attribute_label,
                            'attribute_type' => $attribute_type,
                            'attribute_orderby' => $attribute_orderby,
                            'attribute_public' => $has_archives,
                        )
                    );
                    $new_attribute_id = $wpdb->insert_id;
                }

                // Save attribute custom options
                if (isset($attribute_data->irantheme_taxonomy_type)) {
                    update_option(
                        "wc_attribute_irantheme_taxonomy_type-{$new_attribute_id}",
                        (string)$attribute_data->irantheme_taxonomy_type
                    );
                }

                if (isset($attribute_data->irantheme_add_to_filter)) {
                    update_option(
                        "wc_attribute_irantheme_taxonomy_add_to_filter-{$new_attribute_id}",
                        (string)$attribute_data->irantheme_add_to_filter
                    );
                }

                if (isset($attribute_data->irantheme_filter_position)) {
                    update_option(
                        "wc_attribute_irantheme_taxonomy_index_filter-{$new_attribute_id}",
                        (string)$attribute_data->irantheme_filter_position
                    );
                }

                if (isset($attribute_data->irantheme_filter_icon)) {
                    update_option(
                        "wc_attribute_irantheme_taxonomy_icon_filter-{$new_attribute_id}",
                        (string)$attribute_data->irantheme_filter_icon
                    );
                }

                // Clear attribute cache
                delete_transient('wc_attribute_taxonomies');
                wp_cache_delete('wc_attribute_taxonomies', 'transient');

                // IMPORTANT: Get taxonomy name (with pa_ prefix)
                $taxonomy_name = wc_attribute_taxonomy_name($attribute_name);

                // Register taxonomy if not already registered
                if (!taxonomy_exists($taxonomy_name)) {
                    // Register the taxonomy
                    register_taxonomy(
                        $taxonomy_name,
                        array('product'),
                        array(
                            'labels' => array(
                                'name' => $attribute_label,
                            ),
                            'hierarchical' => false,
                            'show_ui' => true,
                            'query_var' => true,
                            'rewrite' => array('slug' => $taxonomy_name),
                        )
                    );
                }

                // Import terms
                if (isset($attribute_data->terms)) {
                    $terms_data = json_decode((string)$attribute_data->terms, true);

                    if (!empty($terms_data) && is_array($terms_data)) {
                        foreach ($terms_data as $term_item) {
                            $term_name = isset($term_item['name']) ? $term_item['name'] : '';
                            $term_slug = isset($term_item['slug']) ? $term_item['slug'] : sanitize_title($term_name);
                            $term_description = isset($term_item['description']) ? $term_item['description'] : ''; // ADD THIS LINE
                            $term_type_value = isset($term_item['term_type_value']) ? $term_item['term_type_value'] : '';

                            if (empty($term_name)) {
                                continue;
                            }

                            // Check if term exists
                            $existing_term = term_exists($term_name, $taxonomy_name);

                            if (!$existing_term) {
                                // Create new term with description
                                $new_term = wp_insert_term(
                                    $term_name,
                                    $taxonomy_name,
                                    array(
                                        'slug' => $term_slug,
                                        'description' => $term_description, // ADD THIS LINE
                                    )
                                );

                                if (!is_wp_error($new_term) && isset($new_term['term_id'])) {
                                    $term_id = $new_term['term_id'];

                                    // Save term type value
                                    if ($taxonomy_type == 'brand' && !empty($term_type_value)) {
                                        // For brand, term_type_value is an image URL
                                        $attachment_id = self::media_uploader_without_type($term_type_value);
                                        if ($attachment_id) {
                                            update_option("_term_type_{$taxonomy_name}_{$term_id}", $attachment_id);
                                        }
                                    } elseif ($taxonomy_type == 'color' && !empty($term_type_value)) {
                                        // For color, term_type_value is a hex color code
                                        update_option("_term_type_{$taxonomy_name}_{$term_id}", $term_type_value);
                                    }

                                    // Also update term meta if needed (for compatibility with some plugins)
                                    if (function_exists('update_term_meta')) {
                                        if ($taxonomy_type == 'color' && !empty($term_type_value)) {
                                            update_term_meta($term_id, 'color', $term_type_value);
                                        } elseif ($taxonomy_type == 'brand' && !empty($term_type_value)) {
                                            $attachment_id = self::media_uploader_without_type($term_type_value);
                                            if ($attachment_id) {
                                                update_term_meta($term_id, 'brand_logo', $attachment_id);
                                            }
                                        }
                                    }
                                }
                            } else {
                                // Update existing term
                                $term_id = is_array($existing_term) ? $existing_term['term_id'] : $existing_term;

                                // Update term slug and description if needed
                                $term = get_term($term_id, $taxonomy_name);
                                if ($term && !is_wp_error($term)) {
                                    $update_args = array();

                                    if ($term->slug != $term_slug) {
                                        $update_args['slug'] = $term_slug;
                                    }

                                    if ($term->description != $term_description) {
                                        $update_args['description'] = $term_description;
                                    }

                                    if (!empty($update_args)) {
                                        wp_update_term($term_id, $taxonomy_name, $update_args);
                                    }
                                }

                                // Update term type value
                                if ($taxonomy_type == 'brand' && !empty($term_type_value)) {
                                    $attachment_id = self::media_uploader_without_type($term_type_value);
                                    if ($attachment_id) {
                                        update_option("_term_type_{$taxonomy_name}_{$term_id}", $attachment_id);
                                        if (function_exists('update_term_meta')) {
                                            update_term_meta($term_id, 'brand_logo', $attachment_id);
                                        }
                                    }
                                } elseif ($taxonomy_type == 'color' && !empty($term_type_value)) {
                                    update_option("_term_type_{$taxonomy_name}_{$term_id}", $term_type_value);
                                    if (function_exists('update_term_meta')) {
                                        update_term_meta($term_id, 'color', $term_type_value);
                                    }
                                }
                            }
                        }
                    }
                }
            }

            // Flush rewrite rules to ensure taxonomies are registered
            flush_rewrite_rules();

            // Re-register taxonomies to ensure they're available
            foreach (wc_get_attribute_taxonomies() as $attribute) {
                $taxonomy_name = wc_attribute_taxonomy_name($attribute->attribute_name);
                if (taxonomy_exists($taxonomy_name)) {
                    register_taxonomy_for_object_type($taxonomy_name, 'product');
                }
            }
        }


        /**
         * Import post category data from xml file
         * @param object $xml object of xml file 
         * @return void
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        public static function import_post_cat_from_xml($xml)
        {
            foreach ($xml->irantheme_posts_categories->category_item as $category) {
                $categories = array();
                // Check if category already exists
                $cat_ID = get_cat_ID(strval($category->id));
                //If it doesn't exist create new category
                if ($cat_ID == 0) {
                    $cat_name = array('cat_name' => strval($category->title));
                    wp_insert_category($cat_name);
                }

                //Get ID of category again incase a new one has been created
                array_push($categories, get_cat_ID(strval($category->title)));
                self::$array_of_new_post_cat_id[strval($category->id)] = get_cat_ID(strval($category->title));
            }
        }


        /**
         * Import post data from xml file
         * @param object $xml object of xml file 
         * @return void
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        public static function import_post_from_xml($xml)
        {
            foreach ($xml->irantheme_posts->post_item as $post) {
                $args = array(
                    'post_type' => 'post',
                    'post_status' => 'publish',
                    'posts_per_page' => -1,
                );
                // Get all posts
                $all_posts = new \WP_Query($args);
                $existing_post_id = false;
                if ($all_posts->have_posts()) {
                    while ($all_posts->have_posts()) : $all_posts->the_post();
                        if (get_the_title() == (string)$post->title) {
                            // Get the post id if post is existing
                            $existing_post_id = get_the_ID();
                        }

                    endwhile;
                }

                // Create default category id array
                $category_id_array = explode(',', (string)$post->categories);
                // Holder of new category ids
                $category_new_id_array = array();
                foreach ($category_id_array as $cat_id) {
                    if (in_array($cat_id, array_keys(self::$array_of_new_post_cat_id))) {
                        // Add new id of category to array for used in post
                        array_push($category_new_id_array, self::$array_of_new_post_cat_id[$cat_id]);
                    }
                }
                $post_id = false;
                // If post is existing
                if ($existing_post_id) {
                    $post_id = $existing_post_id;
                } else {
                    // Insert post
                    $post_id = wp_insert_post(array(
                        'post_type' => 'post',
                        'post_title' => (string)$post->title,
                        'post_content' => self::removeCData((string)$post->content),
                        'post_excerpt' => self::removeCData((string)$post->excerpt),
                        'post_status' => 'publish',
                        'tags_input' => explode(
                            ',',
                            (string)$post->tags
                        ),
                        'post_category' => $category_new_id_array
                    ));
                }
                // Check post is created?
                if ($post_id) {
                    // Add new post id to array
                    self::$array_of_new_post_id[(string)$post->id] = $post_id;
                    if (!$existing_post_id) {
                        // Add thumbnail of post
                        $thumbnail_id = Irantheme_Common_Static::get_file_url_media_library((string)$post->thumbnail_url);
                        set_post_thumbnail($post_id, $thumbnail_id);
                    }
                }
            }
        }

        /**
         * Import product category data from xml file
         * @param object $xml object of xml file 
         * @return void
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        public static function import_wc_product_cat_from_xml($xml)
        {
            if (!is_woocommerce_activated()) {
                return;
            }

            $category_ids_sorted = array();
            foreach ($xml->irantheme_products_categories->category_product_item as $category_item) {
                $category_ids_sorted[(string)$category_item->id] = ['title' => (string)$category_item->title, 'parent' => (string)$category_item->parent, 'attachment' => (string)$category_item->attachment];
            }
            ksort($category_ids_sorted);
            foreach ($category_ids_sorted as $cat_key => $cat_val) {
                //Check if category already exists
                $category = get_term_by('name', (string)$cat_val['title'], 'product_cat');
                $cat_id = $category ? $category->term_id : null;
                $parent_id = isset(self::$array_of_new_product_cat_id[(string)$cat_val['parent']]) ? (int)self::$array_of_new_product_cat_id[(string)$cat_val['parent']] : 0;
                $attachment_id = '';
                if ((string)$cat_val['attachment'] != '') {
                    $attachment_id =
                        Irantheme_Common_Static::get_file_url_media_library((string)$cat_val['attachment']);
                }
                //If it doesn't exist create new category
                if (is_null($cat_id)) {
                    $new_cat = wp_insert_term(
                        (string)$cat_val['title'], // the term 
                        'product_cat', // the taxonomy
                        array(
                            'parent' => $parent_id, // Set the parent term ID
                        )
                    );
                    $cat_id = $new_cat['term_id'];
                    // Set the thumbnail for the product category
                    update_term_meta($cat_id, 'thumbnail_id', $attachment_id);
                }
                self::$array_of_new_product_cat_id[strval($cat_key)] = $cat_id;
            }
        }

        /**
         * Import woocommerce product data from xml file
         * @param object $xml object of xml file 
         * @return void
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        public static function import_wc_product_from_xml($xml)
        {
            if (!is_woocommerce_activated()) {
                return;
            }

            foreach ($xml->irantheme_products->product_item as $product) {
                $product_id = '';
                $wc_product = new \WC_Product_Simple();
                // Get all product
                $all_products = new \WP_Query(array('post_type' => 'product', 'posts_per_page' => -1, 'post_status' => 'publish'));
                $existing_product_id = '';
                if ($all_products->have_posts()) {
                    while ($all_products->have_posts()) : $all_products->the_post();
                        $_product = wc_get_product(get_the_ID());
                        if ($_product->get_name() == (string)$product->title || ($_product->get_sku() == (string)$product->sku && (string)$product->sku != '')) {
                            // Get id of exist product
                            $existing_product_id = get_the_ID();
                        }
                    endwhile;
                }
                if ($existing_product_id == '') {
                    // Create product using CRUD methods
                    $wc_product->set_status('publish');
                    // Content
                    $wc_product->set_name((string)$product->title);
                    $wc_product->set_short_description(self::removeCData((string)$product->excerpt));
                    $wc_product->set_description(self::removeCData((string)$product->content));
                    $wc_product->set_sku((string)$product->sku);

                    // Stock
                    $wc_product->set_stock_status((string)$product->stock_status);
                    $wc_product->set_stock_quantity((string)$product->stock_quantity);
                    if ((string)$product->stock_quantity != '') {
                        $wc_product->set_manage_stock(true);
                    }

                    // Price
                    $wc_product->set_regular_price((string)$product->regular_price);
                    $wc_product->set_sale_price((string)$product->sale_price);

                    // Sale schedule
                    $wc_product->set_date_on_sale_from((string)$product->date_on_sale_from);
                    $wc_product->set_date_on_sale_to((string)$product->date_on_sale_to);

                    // Categories-----------------------------------------------------------------------
                    // Create default category id array
                    $category_id_array = explode(',', $product->categories);

                    // Holder of new category ids
                    $category_new_id_array = array();
                    foreach ($category_id_array as $cat_id) {
                        if (in_array($cat_id, array_keys(self::$array_of_new_product_cat_id))) {
                            // Add new id of category to array for used in post
                            array_push($category_new_id_array, self::$array_of_new_product_cat_id[$cat_id]);
                        }
                    }
                    $wc_product->set_category_ids($category_new_id_array);

                    // Tags
                    $tags = array();
                    $tag_array = explode(',', $product->tags);
                    foreach ($tag_array as $tag_name) {
                        //Check if tag already exists
                        $tag = get_term_by('name', $tag_name, 'product_tag');
                        $tag_id = $tag ? $tag->term_id : null;
                        //If it doesn't exist create new tag
                        if (is_null($tag_id)) {
                            $tag_id = wp_insert_term(
                                (string)$tag_name, // the term 
                                'product_tag', // the taxonomy
                            );
                        }

                        //Get ID of tag again incase a new one has been created
                        array_push(
                            $tags,
                            $tag_id
                        );
                    }
                    $wc_product->set_tag_ids($tags);

                    // Thumbnail
                    $thumbnail_id = Irantheme_Common_Static::get_file_url_media_library((string)$product->thumbnail_url);
                    $wc_product->set_image_id($thumbnail_id);

                    // Gallery
                    $gallery_image_ids = array();
                    $gallery_array = explode(',', $product->gallery_ids);
                    $image_id = '';
                    if ($product->gallery_ids) {
                        foreach ($gallery_array as $gallery_url) {
                            $image_id = Irantheme_Common_Static::get_file_url_media_library((string)$gallery_url);

                            //Get ID of tag again incase a new one has been created
                            array_push(
                                $gallery_image_ids,
                                $image_id
                            );
                        }
                    }
                    $wc_product->set_gallery_image_ids($gallery_image_ids);

                    // ============================================================
                    // ========== FIXED ATTRIBUTE IMPORT LOGIC ====================
                    // ============================================================
                    // Attributes
                    $attributes = array();

                    // Get attribute names and values from XML
                    $attr_name_array = explode(',', (string)$product->attributes_name);
                    $attr_term_array = explode(',', (string)$product->attributes_value);

                    // Make sure both arrays have the same length
                    $count = min(count($attr_name_array), count($attr_term_array));

                    for ($i = 0; $i < $count; $i++) {
                        $attr_name = trim($attr_name_array[$i]);
                        $attr_values = explode('|', $attr_term_array[$i]);

                        if (empty($attr_name) || empty($attr_values)) {
                            continue;
                        }

                        // Check if this is a taxonomy-based attribute (starts with 'pa_')
                        $taxonomy_name = wc_attribute_taxonomy_name($attr_name);
                        $taxonomy = taxonomy_exists($taxonomy_name);

                        if ($taxonomy) {
                            // This is a taxonomy-based attribute
                            $attribute = new \WC_Product_Attribute();
                            $attribute->set_id(wc_attribute_taxonomy_id_by_name($attr_name));
                            $attribute->set_name($taxonomy_name);

                            // Get term IDs for the attribute values
                            $term_ids = array();
                            foreach ($attr_values as $value) {
                                $value = trim($value);
                                if (empty($value)) {
                                    continue;
                                }

                                // Try to find the term by name or slug
                                $term = get_term_by('name', $value, $taxonomy_name);
                                if (!$term) {
                                    $term = get_term_by('slug', sanitize_title($value), $taxonomy_name);
                                }

                                if ($term && !is_wp_error($term)) {
                                    $term_ids[] = $term->term_id;
                                } else {
                                    // If term doesn't exist, create it
                                    $new_term = wp_insert_term($value, $taxonomy_name, array(
                                        'slug' => sanitize_title($value),
                                    ));

                                    if (!is_wp_error($new_term) && isset($new_term['term_id'])) {
                                        $term_ids[] = $new_term['term_id'];
                                    }
                                }
                            }

                            if (!empty($term_ids)) {
                                $attribute->set_options($term_ids);
                                $attribute->set_position($i);
                                $attribute->set_visible(true);
                                $attribute->set_variation(false);
                                $attributes[] = $attribute;
                            }
                        } else {
                            // This is a custom attribute (not taxonomy-based)
                            $attribute = new \WC_Product_Attribute();
                            $attribute->set_name($attr_name);
                            $attribute->set_options($attr_values);
                            $attribute->set_position($i);
                            $attribute->set_visible(true);
                            $attribute->set_variation(false);
                            $attributes[] = $attribute;
                        }
                    }
                    $wc_product->set_attributes($attributes);
                    // ============================================================

                    // Save
                    wp_suspend_cache_invalidation(true);
                    wp_defer_term_counting(false);
                    $wc_product->save();

                    // Get id of new product
                    $product_id = $wc_product->get_id();

                    // add custom fields product new badge checkbox
                    $product_new_badge_checkbox = (string)$product->product_new_badge_checkbox;
                    update_post_meta($product_id, 'irantheme_custom_field_product_new_badge_checkbox', $product_new_badge_checkbox);

                    // add custom fields product new badge date
                    $product_new_badge_date = (string)$product->product_new_badge_date;
                    update_post_meta($product_id, 'irantheme_custom_field_product_new_badge_date', $product_new_badge_date);

                    // add custom fields product detail badge
                    $product_detail_badge = (string)$product->product_detail_badge;
                    update_post_meta($product_id, 'irantheme_custom_field_product_detail_badge', $product_detail_badge);

                    // add custom fields product attr
                    $product_attr = (string)$product->product_attr;
                    update_post_meta($product_id, 'irantheme_custom_field_product_attr', $product_attr);

                    // add custom fields product color var image checkbox
                    $product_color_var_image_checkbox = (string)$product->product_color_var_image_checkbox;
                    update_post_meta($product_id, 'irantheme_custom_field_product_color_var_image_checkbox', $product_color_var_image_checkbox);

                    // add custom fields product tip
                    $product_tip = self::removeCData((string)$product->product_tip);
                    update_post_meta($product_id, 'irantheme_custom_field_product_tip', $product_tip);

                    // add custom fields shipping time checkbox
                    $product_shipping_time_checkbox = self::removeCData((string)$product->product_shipping_time_checkbox);
                    update_post_meta($product_id, 'irantheme_custom_field_shipping_time', $product_shipping_time_checkbox);
                } else if ($existing_product_id != '') {
                    $product_id = $existing_product_id;
                }
                self::$array_of_new_product_id[(string)$product->get_id()] = $product_id;
            }
        }


        /**
         * Import woocommerce extra options from xml file
         * @param object $xml object of xml file 
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function import_woocommerce_extra_options_from_xml($xml)
        {
            if (is_woocommerce_activated()) {
                foreach ($xml->irantheme_woocommerce_extra_options->option_item as $option) {
                    foreach ($option as $option_name => $option_value) {
                        // Set option value
                        update_option($option_name, strval($option_value));
                    }
                }
            }
        }


        /**
         * Import irantheme settings options from xml file
         * @param object $xml object of xml file 
         * @return void
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        public static function import_settings_options_from_xml($xml)
        {
            foreach ($xml->irantheme_settings_options->option_item as $option) {
                foreach ($option as $option_name => $option_value) {
                    // Register option 
                    register_setting(Irantheme_Settings_Options::get_menu_slug(), $option_name);

                    // If value is media , get media data for save
                    $media_val = self::media_uploader_without_type(self::removeCData((string)$option_value));
                    if ($media_val) {
                        // Replace media data to option value
                        $option_value = $media_val;
                    } else {
                        $option_value = self::field_type_is_select_by_name($option_name, $option_value);
                    }
                    // Set option value
                    update_option($option_name, self::removeCData((string)$option_value));
                }
            }
        }


        /**
         * Import pages from xml file
         * Compatible with new save value structure
         * @param object $xml object of xml file 
         * @return void
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        public static function import_pages_from_xml($xml, $page_id_parameter = null)
        {
            foreach ($xml->irantheme_pages->page_item as $page) {
                if ($page_id_parameter == null) {
                    // Insert new page
                    $page_id = wp_insert_post(array(
                        'post_title' => (string)$page->title,
                        'post_content' =>
                        self::removeCData(
                            (string)$page->content
                        ),
                        'post_status' => 'publish',
                        'post_type' => 'page'
                    ));
                } else {
                    $page_id = wp_update_post(array(
                        'ID' => $page_id_parameter,
                        'post_title' => (string)$page->title,
                        'post_content' =>
                        self::removeCData(
                            (string)$page->content
                        ),
                        'post_status' => 'publish',
                        'post_type' => 'page'
                    ));
                }

                // Check page is created?
                if ($page_id) {
                    // Add new page id with title to array
                    self::$array_of_new_page_id_by_title[(string)$page->title] = $page_id;
                    self::$array_of_new_page_id_by_old_id[(string)$page->id] = $page_id;
                    // Upload and get thumbnail id
                    $thumbnail_id = Irantheme_Common_Static::get_file_url_media_library((string)$page->thumbnail_url);
                    // Set thumbnail
                    set_post_thumbnail($page_id, $thumbnail_id);
                    // Check page type
                    if ($page->rel) {
                        switch ($page->rel) {
                            case 'front_page':
                                // Set home page
                                update_option('page_on_front', $page_id);
                                break;
                            case 'home_page':
                                // Set blog page
                                update_option('page_for_posts', $page_id);
                                break;
                            case 'myaccount':
                                // Set myaccount page
                                update_option('woocommerce_myaccount_page_id', $page_id);
                                break;
                            case 'shop':
                                // Set shop page
                                update_option('woocommerce_shop_page_id', $page_id);
                                break;
                            case 'cart':
                                // Set cart page
                                update_option('woocommerce_cart_page_id', $page_id);
                                break;
                            case 'checkout':
                                // Set checkout page
                                update_option('woocommerce_checkout_page_id', $page_id);
                                break;

                            default:
                                break;
                        }
                    }
                }
            }
            // If front page or posts page is set
            if (get_option('page_on_front') != 0 || get_option('page_for_posts') != 0) {
                // Change show on front to page type
                update_option('show_on_front', 'page');
            } else {
                // else Change show on front to posts type
                update_option('show_on_front', 'posts');
            }
        }

        /**
         * Import page config from xml file
         * @param object $xml object of xml file 
         * @return void
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        public static function import_page_config_from_xml($xml)
        {
            // Get section fields
            $page_config_fields_raw = Irantheme_Page_Builder_Ajax::get_function_fields('page_config_settings', false);
            foreach ($xml->irantheme_page_config->page_config_item as $details_item_key => $details_item_value) {
                // Holder of field option value
                $option_values = array();
                $current_page_title = '';
                foreach ($details_item_value as $key => $value) {
                    if ($key == 'title') {
                        $current_page_title = strval($value);
                        // Next loop
                        continue;
                    }
                    // Hold page config value
                    $option_values[$key] = strval($value);
                }
                // Get page meta data json
                $page_config_fields = array();
                $page_meta_data_name = irantheme_get_meta_data_page_config_by_page_id();

                foreach ($page_config_fields_raw as $fields_settings) {
                    foreach ($fields_settings['fields'] as $fields) {
                        // If value is media , get media data for save
                        $media_val = self::media_uploader_by_type(self::removeCData((string)$option_values[$fields['id']]), $fields['type']);
                        if ($media_val) {
                            // Replace media data to option value
                            $option_values[$fields['id']] = $media_val;
                        }

                        $page_config_fields[$fields['id']] = self::removeCData((string)$option_values[$fields['id']]);
                    }
                }
                // Update page with wp_slash for proper WordPress escaping
                $page_meta_data_array['page_config_fields'] = $page_config_fields;
                update_post_meta(
                    self::$array_of_new_page_id_by_title[$current_page_title],
                    $page_meta_data_name,
                    wp_slash(wp_json_encode($page_meta_data_array))
                );
            }
        }

        /**
         * Import page header settings from xml file
         * @param object $xml object of xml file 
         * @return void
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        public static function import_page_header_settings_from_xml($xml)
        {
            // Get section fields
            $page_header_setting_fields_raw = Irantheme_Page_Builder_Ajax::get_function_fields('header', false);
            foreach ($xml->irantheme_pages_header_settings->page_config_item as $details_item_key => $details_item_value) {
                // Holder of field option value
                $option_values = array();
                $current_page_title = '';
                foreach ($details_item_value as $key => $value) {
                    if ($key == 'title') {
                        $current_page_title = strval($value);
                        // Next loop
                        continue;
                    }
                    // Hold page config value
                    $option_values[$key] = strval($value);
                }
                // Get page meta data json
                $page_header_setting_fields = array();
                $page_meta_data_name = irantheme_get_meta_data_header_by_page_id();
                foreach ($page_header_setting_fields_raw as $fields_settings) {
                    foreach ($fields_settings['fields'] as $fields) {
                        // If value is media , get media data for save
                        $media_val = self::media_uploader_by_type(self::removeCData((string)$option_values[$fields['id']]), $fields['type']);
                        if ($media_val) {
                            // Replace media data to option value
                            $option_values[$fields['id']] = $media_val;
                        } else {
                            $option_values[$fields['id']] = self::field_type_is_select_by_name($fields['id'], self::removeCData((string)$option_values[$fields['id']]));
                        }

                        $page_header_setting_fields[$fields['id']] = self::removeCData((string)$option_values[$fields['id']]);
                    }
                }
                // Update page with wp_slash for proper WordPress escaping
                $page_meta_data_array['header_fields'] = $page_header_setting_fields;

                update_post_meta(
                    self::$array_of_new_page_id_by_title[$current_page_title],
                    $page_meta_data_name,
                    wp_slash(wp_json_encode($page_meta_data_array))
                );
            }
        }


        /**
         * Import pages sections from xml file
         * @param object $xml object of xml file 
         * @return void
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        public static function import_pages_sections_from_xml($xml)
        {
            foreach ($xml->irantheme_pages_sections->page_sections_item as $section_item_key => $section_item_value) {
                $current_page_title = '';
                $holder_sections = array();

                foreach ($section_item_value as $key => $value) {
                    if ($key == 'title') {
                        $current_page_title = strval($value);
                        continue;
                    } elseif ($key == 'sections') {
                        // Use JSON Helper for safe decode
                        $associative_array = Irantheme_JSON_Helper::decode(self::removeCData($value));
                        $sections_array = array();
                        $sections_array['sections_items'] = array();

                        foreach ($associative_array as $page_sections) {
                            foreach ($page_sections as $section_item_key => $section_item_values) {
                                $sections_item = array(
                                    'data_type' => $section_item_values['data_type'],
                                    'data_value' => $section_item_values['data_value'],
                                    'data_page' => self::$array_of_new_page_id_by_title[$current_page_title],
                                    'data_ajax' => $section_item_values['data_ajax'],
                                    'section_fields' => array(),
                                );

                                $fields_settings_all = Irantheme_Page_Builder_Ajax::get_function_fields($section_item_values['data_value'], true);

                                foreach ($fields_settings_all as $fields_settings) {
                                    foreach ($fields_settings['fields'] as $fields) {
                                        if (self::json_validate($section_item_values['section_fields'][$fields['id']])) {
                                            // Use JSON Helper for safe decode
                                            $json_decoded = Irantheme_JSON_Helper::decode($section_item_values['section_fields'][$fields['id']]);
                                            $object_holder = array();

                                            foreach ($json_decoded as $json_obj) {
                                                $object_item = array(
                                                    'object_id' => $json_obj['object_id'],
                                                    'fields' => array()
                                                );

                                                foreach ($json_obj['fields'] as $json_obj_key => $json_obj_value) {
                                                    // Handle product_selection field
                                                    if ($json_obj_key == 'product_selection') {
                                                        $object_item['fields'][$json_obj_key] = self::process_product_selection_value($json_obj_value);
                                                    }
                                                    // Handle media_gallery field
                                                    elseif ($json_obj_key == 'media_gallery_uploader') {
                                                        $processed_value = self::process_media_gallery_field($json_obj_value);
                                                        $object_item['fields'][$json_obj_key] = $processed_value;
                                                    }
                                                    // Handle bg_image field (product thumbnail)
                                                    elseif ($json_obj_key == 'bg_image') {
                                                        $media_id = self::media_uploader_without_type($json_obj_value);
                                                        if ($media_id) {
                                                            $object_item['fields'][$json_obj_key] = $media_id;
                                                        } else {
                                                            $object_item['fields'][$json_obj_key] = $json_obj_value;
                                                        }
                                                    } else {
                                                        $media_id = self::media_uploader_without_type($json_obj_value);
                                                        if ($media_id) {
                                                            $new_value = $media_id;
                                                        } else {
                                                            $new_value = self::field_type_is_select_by_name($json_obj_key, $json_obj_value);
                                                        }
                                                        $object_item['fields'][$json_obj_key] = $new_value;
                                                    }
                                                }
                                                array_push($object_holder, $object_item);
                                            }

                                            // Keep objects_json as ARRAY (not string!)
                                            $sections_item['section_fields'][$fields['id']] = $object_holder;
                                        } else {
                                            $field_value = strval($section_item_values['section_fields'][$fields['id']]);

                                            // Handle product_selection field
                                            if ($fields['id'] == 'product_selection') {
                                                $sections_item['section_fields'][$fields['id']] = self::process_product_selection_value($field_value);
                                            }
                                            // Handle media_gallery field
                                            elseif ($fields['id'] == 'media_gallery_uploader') {
                                                $sections_item['section_fields'][$fields['id']] = self::process_media_gallery_field($field_value);
                                            } else {
                                                $media_val = self::media_uploader_by_type($field_value, $fields['type']);

                                                if ($media_val) {
                                                    $sections_item['section_fields'][$fields['id']] = $media_val;
                                                } else {
                                                    $sections_item['section_fields'][$fields['id']] = self::field_type_is_select_by_name(
                                                        $fields['id'],
                                                        $field_value
                                                    );
                                                }
                                            }
                                        }
                                    }
                                }
                                array_push($sections_array['sections_items'], $sections_item);
                            }
                        }

                        $section_option_name = irantheme_get_meta_data_sections_by_page_id();
                        // Use wp_slash for proper WordPress escaping
                        update_post_meta(
                            self::$array_of_new_page_id_by_title[$current_page_title],
                            $section_option_name,
                            wp_slash(wp_json_encode($sections_array))
                        );
                    }
                }
            }
        }

        /**
         * Process product selection value to replace old product IDs with new ones
         * @param string $value
         * @return string
         */
        private static function process_product_selection_value($value)
        {
            if (empty($value)) {
                return '';
            }

            // Handle single product ID
            if (is_numeric($value) && isset(self::$array_of_new_product_id[$value])) {
                return self::$array_of_new_product_id[$value];
            }

            // Handle comma-separated list of product IDs
            if (strpos($value, ',') !== false) {
                $ids = explode(',', $value);
                $new_ids = array();
                foreach ($ids as $id) {
                    $id = trim($id);
                    if (is_numeric($id) && isset(self::$array_of_new_product_id[$id])) {
                        $new_ids[] = self::$array_of_new_product_id[$id];
                    } else {
                        $new_ids[] = $id;
                    }
                }
                return implode(',', $new_ids);
            }

            return $value;
        }

        /**
         * Process media gallery field with proper handling of single/multiple values
         * @param string $value
         * @return string
         */
        private static function process_media_gallery_field($value)
        {
            if (empty($value)) {
                return '';
            }

            $media_urls = array();

            // Handle both single and multiple URLs
            if (strpos($value, ',') !== false) {
                $urls = explode(',', $value);
                foreach ($urls as $url) {
                    $url = trim($url);
                    if (!empty($url) && filter_var($url, FILTER_VALIDATE_URL)) {
                        $attachment_id = Irantheme_Common_Static::get_file_url_media_library($url);
                        if ($attachment_id) {
                            $attachment_url = wp_get_attachment_url($attachment_id);
                            if ($attachment_url) {
                                $media_urls[] = $attachment_url;
                            }
                        }
                    }
                }
            } else {
                if (filter_var($value, FILTER_VALIDATE_URL)) {
                    $attachment_id = Irantheme_Common_Static::get_file_url_media_library($value);
                    if ($attachment_id) {
                        $attachment_url = wp_get_attachment_url($attachment_id);
                        if ($attachment_url) {
                            $media_urls[] = $attachment_url;
                        }
                    }
                }
            }

            return !empty($media_urls) ? implode(',', $media_urls) : '';
        }

        /**
         * Import sections templates from xml file
         * @param object $xml object of xml file 
         * @return void
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        public static function import_sections_templates_from_xml($xml)
        {
            foreach ($xml->irantheme_templates->template_option->irantheme_page_builder_templates as $section_item_key => $section_item_value) {

                // Use JSON Helper for safe decode
                $associative_array = Irantheme_JSON_Helper::decode(self::removeCData($section_item_value));
                // Loop templates
                foreach ($associative_array as &$template_sections) {
                    $fields_settings_all = Irantheme_Page_Builder_Ajax::get_function_fields($template_sections['data_value'], true);
                    // Loop on fields of section type
                    foreach ($fields_settings_all as $fields_settings) {
                        foreach ($fields_settings['fields'] as $fields) {
                            if (self::json_validate($template_sections['section_fields'][$fields['id']])) {
                                // Use JSON Helper for safe decode
                                $json_decoded = Irantheme_JSON_Helper::decode($template_sections['section_fields'][$fields['id']]);
                                $object_holder = array();
                                foreach ($json_decoded as $json_obj) {
                                    $object_item = array(
                                        'object_id' => $json_obj['object_id'],
                                        'fields' => array()
                                    );
                                    foreach ($json_obj['fields'] as $json_obj_key => $json_obj_value) {
                                        // Handle product_selection field
                                        if ($json_obj_key == 'product_selection') {
                                            $object_item['fields'][$json_obj_key] = self::process_product_selection_value($json_obj_value);
                                        }
                                        // Handle media_gallery field
                                        elseif ($json_obj_key == 'media_gallery_uploader') {
                                            $object_item['fields'][$json_obj_key] = self::process_media_gallery_field($json_obj_value);
                                        } else {
                                            $media_id = self::media_uploader_without_type($json_obj_value);
                                            if ($media_id) {
                                                $new_value = $media_id;
                                            } else {
                                                $new_value = self::field_type_is_select_by_name($json_obj_key, $json_obj_value);
                                            }
                                            $object_item['fields'][$json_obj_key] = $new_value;
                                        }
                                    }
                                    array_push($object_holder, $object_item);
                                }
                                // Keep objects_json as ARRAY (not string!)
                                $template_sections['section_fields'][$fields['id']] = $object_holder;
                            } else {
                                $field_value = strval($template_sections['section_fields'][$fields['id']]);

                                // Handle product_selection field
                                if ($fields['id'] == 'product_selection') {
                                    $template_sections['section_fields'][$fields['id']] = self::process_product_selection_value($field_value);
                                }
                                // Handle media_gallery field
                                elseif ($fields['id'] == 'media_gallery_uploader') {
                                    $template_sections['section_fields'][$fields['id']] = self::process_media_gallery_field($field_value);
                                } else {
                                    $media_val = self::media_uploader_by_type($field_value, $fields['type']);
                                    if ($media_val) {
                                        $template_sections['section_fields'][$fields['id']] = $media_val;
                                    } else {
                                        $template_sections['section_fields'][$fields['id']] = self::field_type_is_select_by_name($fields['id'], $field_value);
                                    }
                                }
                            }
                        }
                    }
                }
                unset($template_sections);
                // Save with wp_slash for proper WordPress escaping
                update_option('irantheme_page_builder_templates', wp_slash(wp_json_encode($associative_array)));
            }
        }

        /**
         * Import pages sections fields from xml file
         * @param object $xml object of xml file 
         * @return void
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        public static function import_nav_menus_from_xml($xml)
        {

            foreach ($xml->irantheme_nav_menus->nav_menu_item as $nav_menu_item_key => $nav_menu_item_value) {
                foreach ($nav_menu_item_value as $key => $value) {
                    if ($key == 'id') {
                        $old_key = strval($value);
                        continue;
                    } elseif ($key == 'title') {
                        // Check if the menu exists
                        $menu_name   = strval($value);
                        $menu_exists = is_nav_menu($menu_name);
                        continue;
                    } elseif ($key == 'menu_locations' && !$menu_exists) {
                        $menu_id = wp_create_nav_menu($menu_name);
                        $current_menu_locations = explode(',', strval($value));
                        if ($current_menu_locations != array('')) {
                            $locations = get_nav_menu_locations();
                            foreach ($current_menu_locations as $location_name) {
                                $locations[$location_name] = $menu_id;
                            }
                            set_theme_mod('nav_menu_locations', $locations);
                        }
                        continue;
                    }
                    // If nav menu is exist
                    if ($menu_exists) {
                        // Get nav menu id
                        $_term = get_term_by('name', $menu_name, 'nav_menu');
                        // Assign id of nav menu exist
                        $menu_id = $_term->term_id;
                    }
                    // Add menu id to array
                    self::$array_of_new_menu_id[$old_key] = $menu_id;
                    $new_ids = array();
                    // If it doesn't exist, let's create it.
                    if (!$menu_exists) {
                        // Create menu
                        foreach (json_decode($value) as $k => $v) {
                            $category = '';
                            $cat_id = 0;
                            $new_url = '';
                            $obj_ID = 0;
                            if ($v->object == 'page') {
                                $new_url = get_permalink(self::$array_of_new_page_id_by_old_id[$v->object_id]);
                                $obj_ID = self::$array_of_new_page_id_by_old_id[$v->object_id];
                            } elseif ($v->object == 'category') {
                                $cat_id = get_cat_ID($v->title);
                                $new_url = get_category_link($cat_id);
                                $obj_ID = $cat_id;
                            } elseif ($v->object == 'product_cat') {
                                //Check if category already exists
                                $category = get_term_by('name', $v->title, 'product_cat');
                                $cat_id = $category->term_id;
                                $new_url = get_term_link((int)$cat_id, 'product_cat');
                                $obj_ID = $cat_id;
                            } elseif ($v->object == 'custom') {
                                $new_url = $v->url;
                            }

                            $menu_item_data = array(
                                'menu-item-title'       => $v->title,
                                'menu-item-object-id'   => ($obj_ID != 0) ? $obj_ID : '',
                                'menu-item-object'      => $v->object,
                                'menu-item-status'      => $v->post_status,
                                'menu-item-type'        => $v->type,
                                'menu-item-type-label'  => $v->type_label,
                                'menu-item-url'         => ($new_url != '' || $new_url != 0) ? $new_url : '#',
                                'menu-item-parent-id'   => $v->menu_item_parent,
                                'menu-item-position'    => $v->menu_order,
                                'menu-item-description' => $v->description,
                                'menu-item-attr-title'  => $v->attr_title,
                                'menu-item-target'      => $v->target,
                                'menu-item-classes'     => implode(' ', $v->classes),
                                'menu-item-xfn'         => $v->xfn,
                            );
                            if ($v->menu_item_parent != 0) {
                                $menu_item_data['menu-item-parent-id'] = $new_ids[$v->menu_item_parent];
                            }
                            // Save new item
                            $item_id = wp_update_nav_menu_item($menu_id, 0, $menu_item_data);
                            // Holder of old id and new id of item
                            $new_ids[$v->ID] = $item_id;
                            // Get all field id of nav menu meta option
                            $all_meta_fields = Irantheme_Nav_Menu::admin_fields_elements_nav_menus();
                            foreach ($v->irantheme_options as $option_key => $option_value) {
                                foreach ($all_meta_fields as $fields) {
                                    if ($option_key == $fields['id']) {
                                        // Combination prefix and custom field name to generate register name
                                        $register_name = Irantheme_Nav_Menu::get_name_post_meta_mixed($fields['id'], $item_id);
                                        $sanitized_data = sanitize_text_field($option_value);
                                        if ($fields['type'] == 'image' && $sanitized_data) {
                                            $sanitized_data = Irantheme_Common_Static::get_file_url_media_library($sanitized_data);
                                        }
                                        update_post_meta($item_id, $register_name, $sanitized_data);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        /**
         * Import pages sections fields from xml file
         * @param object $xml object of xml file 
         * @return void
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        public static function import_sidebar_from_xml($xml)
        {

            foreach ($xml->irantheme_widgets_blocks->widget_item as $widget_item_key => $widget_item_value) {
                foreach ($widget_item_value as $key => $value) {
                    update_option($key, json_decode(self::removeCData($value), true));
                }
            }
        }
    }

    new Irantheme_Settings_Demo_Import();
}
