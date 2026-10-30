<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;

if (!class_exists('Irantheme_Section_Woocommerce_Gallery_Social')) {
    /**
     * A class for create section
     *
     * @package Wordpress
     * @subpackage irantheme
     * @since irantheme - custom fields
     * @author iranianclever
     * @see v1.0
     *
     */
    class Irantheme_Section_Woocommerce_Gallery_Social extends Irantheme_Section
    {
        /**
         * Constructor
         * @return void
         * @param void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        function __construct() {}

        /**
         * Constructor
         * @return void
         * @param void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function fields($status_default)
        {
            $heading_content = '';
            $settings = array(
                Irantheme_Section_Utils::heading_title_settings_generator($status_default, 'nones', false, false, $heading_content),
                array(
                    'title' => 'تنظیمات بصری',
                    'id' => 'layout',
                    'description'            => __('تنظیمات بصری مانند :ارتفاع، رنگ ها و...', self::$static_textdomain),
                    'icon' => 'fa-paintbrush-pencil',
                    'fields' => array(
                        // Json objects
                        array(
                            'id'             => 'objects_json',
                            'label'            => __(
                                'محتوا شی ها',
                                self::$static_textdomain
                            ),
                            'description'    => __('محتوای مخفی شی ها', self::$static_textdomain),
                            'type'            => 'hidden_json',
                            'default'        => $status_default ? Irantheme_Section_Default::get_default_ids_objects(16, 'woocommerce_gallery_social', $status_default) : '',
                        ),
                        array(
                            'id'             => 'base_color_select',
                            'label'            => __(
                                'رنگ پایه',
                                self::$static_textdomain
                            ),
                            'description'    => __('برای انتخاب رنگ پایه از لیست، رنگ دلخواه خود را انتخاب کنید.رنگ محتواو پس زمینه این بخش بر اساس این رنگ تنظیم خواهد شد.', self::$static_textdomain),
                            'type'            => 'select',
                            'options'        => array('white' => 'سفید', 'dark' => 'مشکی'),
                            'default'        => $status_default ? 'white' : '',
                            'keywords' => array('color', 'background', 'پس زمینه', 'رنگ'),
                        ),
                        array(
                            'id'             => 'details_color_select',
                            'label'            => __(
                                'رنگ نیمه بالا',
                                self::$static_textdomain
                            ),
                            'description'    => __('برای انتخاب رنگ نیمه بالای پس زمینه گالری از لیست رنگ دلخواه خود را انتخاب کنید. این رنگ تاثیر مستقیمی در زیبایی جزئيات دارد.', self::$static_textdomain),
                            'type'            => 'select',
                            'options'        => Irantheme_Color_Static::get_all_colors_options(),
                            'color_preview' => true,
                            'default'        => 'primary',
                            'keywords' => array('رنگ جزئیات', 'detail color'),
                        ),
                        array(
                            'id'             => 'details_color_select2',
                            'label'            => __(
                                'رنگ نیمه پایین',
                                self::$static_textdomain
                            ),
                            'description'    => __('برای انتخاب رنگ نیمه پایین پس زمینه گالری از لیست رنگ دلخواه خود را انتخاب کنید. این رنگ تاثیر مستقیمی در زیبایی جزئيات دارد.', self::$static_textdomain),
                            'type'            => 'select',
                            'options'        => Irantheme_Color_Static::get_all_colors_options(),
                            'color_preview' => true,
                            'default'        => 'secondary',
                            'keywords' => array('رنگ جزئیات', 'detail color'),
                        ),
                        array(
                            'id'             => 'wrapper_mode_checkbox',
                            'label'            => __(
                                'اتصال به نگهدارنده خارجی سایت',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت غیر فعال بودن این گزینه بخش های (سکشن ها) سایت از نگهدارنده داخلی سایت استفاده می کنند، شما با فعال کردن این گزینه می توانید نگهدارنده این بخش را به نگهدارنده خارجی سایت تغییر دهید.در این صورت، این بخش از کناره ها به اندازه نگهدارنده خارجی سایت بزرگ شده وبه آن می چسبد.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'off' : '',
                            'keywords' => array('نگهدارنده خارجی', 'اتصال', 'fullscreen', 'تمام صفحه', 'نگه دارنده', 'نگهدارنده', 'container', 'wrapper'),
                        ),
                    )
                ),
                // Get slider fields
                Irantheme_Section_Utils::slider_fields_generator($status_default, array('autoplay', 'delay'), 'on', 'off', 'off', 'on'),
                // Get layout scale fields
                Irantheme_Section_Utils::layout_scale_fields_generator($status_default, array('radius', 'inner_padding', 'outer_padding'), 'on', 2, 1, 'off', 'off', 0, 0, 1),
                // Get background fields
                Irantheme_Section_Utils::background_fields_generator($status_default, array('image', 'color', 'mask', 'parallax'), '', 'off', true, 'off', 'dark', 0.6, 'off'),
                // Get transform fields
                Irantheme_Section_Utils::transform_fields_generator($status_default),
                // Get data load animation fields
                Irantheme_Section_Utils::data_load_fields_generator($status_default),
                // Get custom html & css fields
                Irantheme_Section_Utils::html_css_fields_generator($status_default, array('element_id', 'css')),
            );

            $settings = apply_filters('irantheme_store_visual_demo_settings', $settings);

            return $settings;
        }


        /**
         * Return custom object fields (Fields)
         * @return array
         * @param int
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function object_fields($index, $status_default = false)
        {
            $images_default = '';
            $content_default = '';
            $gallery_default = '';
            $type_default = '';
            $products_default = '';

            // Check status is deafult or no
            if (is_numeric($index)) {

                // List of images
                $images = array(
                    'https://irantheme.com/wp-content/uploads/2026/05/3g426trd.webp',
                    'https://irantheme.com/wp-content/uploads/2026/05/uteltsjb.webp',
                    'https://irantheme.com/wp-content/uploads/2026/05/6omp4fl2.webp',
                    'https://irantheme.com/wp-content/uploads/2026/05/o92y83lp.webp',
                    'https://irantheme.com/wp-content/uploads/2026/05/j0cmvuub.webp',
                    'https://irantheme.com/wp-content/uploads/2026/05/8ojgyzb0.webp',
                    'https://irantheme.com/wp-content/uploads/2026/05/ng2wovw6.webp',
                    'https://irantheme.com/wp-content/uploads/2026/05/6q2setrw.webp',
                    'https://irantheme.com/wp-content/uploads/2026/05/yj8tvhnb.webp',
                    'https://irantheme.com/wp-content/uploads/2026/05/0628nyga.webp',
                    'https://irantheme.com/wp-content/uploads/2026/05/ctuzdqyf.webp',
                    'https://irantheme.com/wp-content/uploads/2026/05/ucc5deo8.webp',
                    'https://irantheme.com/wp-content/uploads/2026/05/bcocd7g2.webp',
                    'https://irantheme.com/wp-content/uploads/2026/05/r06s9uya.webp',
                    'https://irantheme.com/wp-content/uploads/2026/05/63fag61z.webp',
                    'https://irantheme.com/wp-content/uploads/2026/05/kid6jvtz.webp',
                );

                // List of gallery
                $gallery = array(
                    'https://irantheme.com/wp-content/uploads/2026/05/ffttovr6.webp',
                    'https://irantheme.com/wp-content/uploads/2026/05/qav9085j.webp',
                    'https://irantheme.com/wp-content/uploads/2026/05/oz9u39en.webp',
                    'https://irantheme.com/wp-content/uploads/2026/05/k2712jtc.webp',
                    'https://irantheme.com/wp-content/uploads/2026/05/akrf74hv.webp',
                    'https://irantheme.com/wp-content/uploads/2026/05/l5u4ls2k.webp',
                    'https://irantheme.com/wp-content/uploads/2026/05/xx7n8gxk.webp',
                    'https://irantheme.com/wp-content/uploads/2026/05/2hvzu40v.webp',
                    'https://irantheme.com/wp-content/uploads/2026/05/mbnioo7r.webp',
                    'https://irantheme.com/wp-content/uploads/2026/05/1fe9a4yg.webp',
                    'https://irantheme.com/wp-content/uploads/2026/05/3ctea61e.webp',
                    'https://irantheme.com/wp-content/uploads/2026/05/6e5oatnm.webp',
                    'https://irantheme.com/wp-content/uploads/2026/05/hifondyf.webp',
                    'https://irantheme.com/wp-content/uploads/2026/05/lctzmktk.webp',
                    'https://irantheme.com/wp-content/uploads/2026/05/c56kjve6.webp',
                    'https://irantheme.com/wp-content/uploads/2026/05/bl88mztj.webp',
                );

                // Type
                $type = array(
                    'special',
                    '',
                    '',
                    'video',
                    '',
                    '',
                    'hot',
                    '',
                    '',
                    'special',
                    '',
                    '',
                    'video',
                    '',
                    '',
                    'hot',
                );

                // List of default products
                $product = array_keys(Irantheme_Custom_Fields::get_all_posts('product'))[0];

                // Default content
                $content = array(
                    'نخرررش',
                    'پمپ باد',
                    'ریش تراش',
                    'کدوم بخرم',
                    'پاوربانک',
                    'چرا گرون؟',
                    'هدفون متفاوت',
                    'ردیاب؟!!',
                    'پرینتر سه بعدی',
                    'گفتن نخر',
                    'عمر باتری',
                    'اسکای کندی',
                    'کنسول بازی',
                    'سونی',
                    'کابل شارژ',
                    'هایلو',
                );

                // Set default value
                $images_default = $status_default === 'media' ? Irantheme_Common_Static::get_file_url_media_library($images[$index]) : '';
                $gallery_default = $status_default === 'media' ? wp_get_attachment_url(Irantheme_Common_Static::get_file_url_media_library($gallery[$index])) : '';
                $content_default = $content[$index];
                $type_default = $type[$index];
                // Set default value
                $products_default = $product;
            }

            // Custom fields
            $object_fields = array(
                array(
                    'title' => 'تنظیم محتوا',
                    'id' => 'edit_object',
                    'description'            => __('تنظیم مقادیر عنصر', self::$static_textdomain),
                    'icon' => 'fa-folder-grid',
                    'fields' => array(
                        array(
                            'id'             => 'heading',
                            'label'            => __('عنوان بنر', self::$static_textdomain),
                            'description'    => __('در صورت لزوم و نیاز به معرفی بنر یا محصول می توانید توضیحاتی را ارائه دهید.'),
                            'type'            => 'text',
                            'default'        => $content_default,
                            'placeholder'    => __('', self::$static_textdomain),
                        ),
                        array(
                            'id'             => 'product_selection',
                            'label'            => __(
                                'انتخاب دستی محصول',
                                self::$static_textdomain
                            ),
                            'description'    => __('شما می توانید محصول مورد نظر خود را به صورت دستی انتخاب کنید. این محصول در نقطه کانون داغ به نمایش در خواهد آمد.', self::$static_textdomain),
                            'type'            => 'select',
                            'options'        => Irantheme_Custom_Fields::get_all_posts('product'),
                            'default' => $products_default,
                            'placeholder' => __('محتوا خالی می باشد', self::$static_textdomain)
                        ),
                    )
                ),
                array(
                    'title' => 'گالری',
                    'id' => 'media',
                    'description'            => __('تنظیمات بصری مانند : تصویر زمینه، ارتفاع، رنگ ها و...', self::$static_textdomain),
                    'icon' => 'fa-images',
                    'fields' => array(
                        array(
                            'id'             => 'gallery_type_radio',
                            'label'            => __(
                                'نوع گالری',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه ها می توانید نوع گالری خود را مشخص نمایید. با تنظیم این مقدار کادر مخصوصی جهت نمایش نوع محتوا به نمایش در خواهد آمد.', self::$static_textdomain),
                            'type'            => 'radio',
                            'options'        => array('normal' => 'ساده', 'video' => 'ویدئو', 'special' => 'ویژه', 'hot' => 'داغ'),
                            'default'        => $type_default,
                            'keywords' => array('استایل مرز هدر', 'header border mode', 'استایل سایه', 'استایل خط صاف')
                        ),
                        array(
                            'id'             => 'media_gallery_uploader',
                            'label'            => __('نگارخانه', self::$static_textdomain),
                            'description'    => __('لطفا رسانه های مد نظر خود را بارگذاری نمایید.', self::$static_textdomain),
                            'type'            => 'media_gallery',
                            'default'        => $gallery_default,
                            'keywords' => array('showcase', 'blog', 'master', 'hero', 'ویترین', 'هیرو', 'تصویر زمینه'),
                        ),
                    )
                ),
                // Get background fields
                Irantheme_Section_Utils::background_fields_generator(true, array('image'), $images_default),
            );
            return $object_fields;
        }


        /**
         * Get gallery comments count for a specific object
         * @param string $object_id
         * @return int
         */
        public static function get_gallery_comments_count($object_id)
        {
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
                )
            );

            $query = new \WP_Query($args);
            return $query->found_posts;
        }

        /**
         * Get gallery comments for a specific object
         * @param string $object_id
         * @return array
         */
        public static function get_gallery_comments($object_id)
        {
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

            $query = new \WP_Query($args);
            return $query->posts;
        }



        /**
         * Get gallery comments count for multiple objects in a single query.
         *
         * @param array $object_ids
         * @return array
         */
        private static function get_gallery_comments_counts(array $object_ids)
        {
            $object_ids = array_values(array_filter(array_unique(array_map('strval', $object_ids))));

            if (empty($object_ids)) {
                return array();
            }

            $counts = array_fill_keys($object_ids, 0);

            $query = new \WP_Query(array(
                'post_type'              => 'gallery_comment',
                'post_status'            => 'publish',
                'posts_per_page'         => -1,
                'fields'                 => 'ids',
                'no_found_rows'          => true,
                'update_post_meta_cache' => false,
                'update_post_term_cache' => false,
                'meta_query'             => array(
                    array(
                        'key'     => 'irantheme_woocommerce_gallery_social_meta_value_key',
                        'value'   => $object_ids,
                        'compare' => 'IN',
                    ),
                ),
            ));

            if (empty($query->posts)) {
                return $counts;
            }

            /*
             * The object ID is stored in post meta, so the query result only
             * gives us comment IDs. Read the relationship meta in one batch.
             */
            $comment_ids = array_map('intval', $query->posts);

            if (function_exists('update_meta_cache')) {
                update_meta_cache('post', $comment_ids);
            }

            foreach ($comment_ids as $comment_id) {
                $object_id = (string) get_post_meta(
                    $comment_id,
                    'irantheme_woocommerce_gallery_social_meta_value_key',
                    true
                );

                if (isset($counts[$object_id])) {
                    $counts[$object_id]++;
                }
            }

            return $counts;
        }

        /**
         * Get products for multiple product IDs in a single WooCommerce request.
         *
         * @param array $product_ids
         * @return array
         */
        private static function get_gallery_products(array $product_ids)
        {
            if (!function_exists('wc_get_products')) {
                return array();
            }

            $product_ids = array_values(array_unique(array_filter(array_map('absint', $product_ids))));

            if (empty($product_ids)) {
                return array();
            }

            $products = wc_get_products(array(
                'include' => $product_ids,
                'limit'   => -1,
                'return'  => 'objects',
            ));

            $products_by_id = array();

            foreach ($products as $product) {
                if ($product instanceof \WC_Product) {
                    $products_by_id[$product->get_id()] = $product;
                }
            }

            return $products_by_id;
        }


        /**
         * Render elements
         * @param array,string,string,string,string
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function render($fields_values, $data_type, $data_value, $data_page, $data_ajax)
        {
            // Json post data for post generation ------------------------------------------------------------
            $json_post_array = Irantheme_JSON_Helper::normalize_objects($fields_values['objects_json']);

            if (!is_array($json_post_array)) {
                $json_post_array = array();
            }

            // Background options ------------------------------------------------------------
            $background_image_data = $fields_values['bg_image'];
            $background_color_data = $fields_values['bg_color'];
            $background_parallax_checkbox = $fields_values['bg_parallax_checkbox'];
            $background_color_css = Irantheme_Section_Utils::get_background_color_css($background_color_data);
            $background_image_css = Irantheme_Section_Utils::get_background_image_css($background_image_data);
            $background_parallax_attr = Irantheme_Section_Utils::get_background_parallax_attr($background_parallax_checkbox, $background_image_data);

            // Get data load style --------------------------------------------------------------
            $data_load_attr = Irantheme_Section_Utils::get_data_load_attr_type($fields_values);
            $data_load_duration = Irantheme_Section_Utils::get_data_load_duration($fields_values);
            $aos_delay = Irantheme_Section_Utils::get_data_load_delay($fields_values);

            // Get heading data load style --------------------------------------------------------------
            $heading_data_load_checkbox = $fields_values['heading_data_load_checkbox'];
            $heading_data_load_attr = Irantheme_Section_Utils::get_heading_data_load_attr_type($fields_values);
            $heading_data_load_duration = Irantheme_Section_Utils::get_heading_data_load_duration($fields_values);
            $heading_aos_delay = Irantheme_Section_Utils::get_heading_data_load_delay($fields_values);

            // Fix conflict skeleton with data load (Aos) --------------------------------------------------------------
            $data_load_checkbox = $fields_values['data_load_checkbox'];
            $skeleton_section_class = 'irantheme-skeleton-section';
            if ($data_load_checkbox == 'on' || $heading_data_load_checkbox == 'on') {
                $skeleton_section_class = '';
            }

            // Get section heading --------------------------------------------------------------
            $tinymce_heading = $fields_values['text_editor_heading'];

            // Get border radius --------------------------------------------------------------
            $border_radius_range = $fields_values['border_radius_range'];
            $border_radius_style = Irantheme_Section_Utils::get_border_radius_css($border_radius_range);

            // Base color --------------------------------------------------------------
            $base_color = $fields_values['base_color_select'];
            $base_color_class = Irantheme_Common_Static::get_base_color_class($base_color);

            // Details color --------------------------------------------------------------
            $details_color = $fields_values['details_color_select'];
            $details_color2 = $fields_values['details_color_select2'];
            $details_color_css = '';
            $details_color_css2 = '';

            if ($details_color != 'off') {
                $details_color_css = Irantheme_Color_Static::get_hex_color($details_color);
            }

            if ($details_color2 != 'off') {
                $details_color_css2 = Irantheme_Color_Static::get_hex_color($details_color2);
            }

            $details_color_result = sprintf(
                "background: linear-gradient(to bottom, #%s, #%s);",
                $details_color_css,
                $details_color_css2
            );

            $slider_mode_columns_data_attr = Irantheme_Section_Service::get_slider_data_column('col-13');

            // Get section id --------------------------------------------------------------
            $section_id = $fields_values['element_id'];
            $section_id_attribute = '';

            if ($section_id) {
                $section_id_attribute = sprintf('id=%s', $section_id);
            }

            // Section transform to up and down --------------------------------------------------------------
            $section_transfrom_values = Irantheme_Section_Utils::get_transform_values_arrays(
                $data_page,
                $fields_values,
                true,
                false,
                false
            );
            $section_transform_extra_css = $section_transfrom_values['overflow'];
            $section_transform_translate_css = $section_transfrom_values['css'];

            // Total objects count for navigation
            $total_objects = count($json_post_array);

            /*
             * Prepare all data used by the fixed stories before rendering.
             *
             * The previous implementation performed product lookup and two
             * comment queries inside the object loop. These lookups are now
             * collected first and resolved in batches.
             */
            $media_element_array = array();
            $object_ids = array();
            $product_ids = array();

            foreach ($json_post_array as $object_post) {
                if (!is_array($object_post)) {
                    continue;
                }

                $object_id = isset($object_post['object_id']) ? (string) $object_post['object_id'] : '';

                if ($object_id !== '') {
                    $object_ids[] = $object_id;
                }

                $object_fields = isset($object_post['fields']) && is_array($object_post['fields'])
                    ? $object_post['fields']
                    : array();

                $product_id = isset($object_fields['product_selection'])
                    ? absint($object_fields['product_selection'])
                    : 0;

                if ($product_id > 0) {
                    $product_ids[] = $product_id;
                }

                $media_element = isset($object_fields['media_gallery_uploader'])
                    ? (string) $object_fields['media_gallery_uploader']
                    : '';

                $media_element_array[$object_id] = $media_element !== ''
                    ? array_values(array_filter(array_map('trim', explode(',', $media_element))))
                    : array();
            }

            $comment_counts = self::get_gallery_comments_counts($object_ids);
            $products_by_id = is_woocommerce_activated()
                ? self::get_gallery_products($product_ids)
                : array();
?>
            <!-- ============================= Section gallery social ============================= -->
            <section <?php echo esc_attr($section_id_attribute); ?> class="irantheme-section irantheme-gallery-social-section <?php echo esc_attr($skeleton_section_class); ?>" <?php echo esc_attr(Irantheme_Page_Builder::get_attr_data_section($data_type, $data_value, $data_page, $data_ajax)); ?> style="<?php echo esc_attr(Irantheme_Section_Service::get_custom_outer_padding_css($fields_values)); ?><?php echo esc_attr($section_transform_extra_css); ?>">
                <div class="irantheme-gallery-social <?php echo esc_attr($base_color_class); ?> irantheme-background-jarallax" style="<?php echo esc_attr($background_color_css); ?><?php echo esc_attr($background_image_css); ?><?php echo esc_attr(Irantheme_Section_Service::get_custom_inner_padding_css($fields_values)); ?>" <?php echo $background_parallax_attr; ?>>

                    <?php
                    // Get background mask checkbox
                    $mask_checkbox = $fields_values['bg_mask_checkbox'];
                    // Get background mask color
                    $mask_color = $fields_values['bg_mask_color'];
                    // Get background mask range
                    $mask_range = $fields_values['bg_mask_range'];
                    // Get showcase mask --------------------------------------------------------------
                    Irantheme_Section_Utils::get_background_mask_tag_html($mask_checkbox, $mask_color, $mask_range);
                    ?>

                    <?php
                    // Set wrapper (Container) --------------------------------------------------------------
                    $container_checkbox = $fields_values['wrapper_mode_checkbox'];
                    if ($container_checkbox != 'on') :
                    ?>
                        <!-- Inner container -->
                        <div class="irantheme-container-inner">
                        <?php endif; ?>

                        <?php if ($tinymce_heading != '' && $heading_data_load_checkbox == 'on'): ?>
                            <div class="irantheme-tinymce-heading-aos-holder" <?php echo ($heading_data_load_attr); ?> data-aos-delay="<?php echo ($heading_aos_delay); ?>" data-aos-duration="<?php echo ($heading_data_load_duration); ?>">
                            <?php endif; ?>

                            <?php
                            // Get tinymce content --------------------------------------------------------------
                            Irantheme_Common_Static::tinymce_heading($fields_values);
                            if ($tinymce_heading != '' && $heading_data_load_checkbox == 'on'):
                            ?>
                            </div>
                        <?php endif; ?>

                        <?php
                        $skeleton_load_class = '';
                        if ($data_load_checkbox != 'on' && $heading_data_load_checkbox != 'on') {
                            // Skeleton html --------------------------------------------------------------
                            irantheme_get_skeleton_html('col-12', 'thumbnail', $base_color_class);
                            $skeleton_load_class = 'irantheme-side-load-skeleton';
                        }
                        ?>

                        <!-- Authors post -->
                        <div class="irantheme-gallery-social-post-wrapper swiper irantheme-gallery-social-post-swiper <?php echo esc_attr($skeleton_load_class); ?>" style="<?php echo esc_attr($section_transform_translate_css); ?>" <?php echo ($slider_mode_columns_data_attr); ?> <?php echo esc_attr(Irantheme_Section_Utils::get_auto_slider_data_attribute($fields_values, true)); ?> <?php echo esc_attr(Irantheme_Section_Utils::get_auto_slider_delay_data_attribute($fields_values)); ?>>
                            <div class="irantheme-object-group irantheme-gallery-social-post-container swiper-wrapper" data-json="<?php echo esc_attr('objects_json'); ?>">
                                <?php
                                $object_counter = 0;
                                foreach ($json_post_array as $object_post) :
                                    if (!is_array($object_post)) {
                                        continue;
                                    }

                                    $object_fields = isset($object_post['fields']) && is_array($object_post['fields'])
                                        ? $object_post['fields']
                                        : array();

                                    $object_id = isset($object_post['object_id']) ? (string) $object_post['object_id'] : '';
                                    $gallery_heading = isset($object_fields['heading']) ? $object_fields['heading'] : '';
                                    $background_image_data = isset($object_fields['bg_image']) ? $object_fields['bg_image'] : 0;
                                    $gallery_type = isset($object_fields['gallery_type_radio']) ? $object_fields['gallery_type_radio'] : '';
                                ?>
                                    <div class="irantheme-object swiper-slide" data-object="<?php echo esc_attr($object_id); ?>" data-index="<?php echo esc_attr($object_counter); ?>" data-total="<?php echo esc_attr($total_objects); ?>">
                                        <!-- Post item -->
                                        <div class="irantheme-gallery-social-post-item irantheme-common-media-gallery-item" style="<?php echo esc_attr($border_radius_style); ?>" <?php echo ($data_load_attr); ?> data-aos-delay="<?php echo ($aos_delay); ?>" data-aos-duration="<?php echo ($data_load_duration); ?>">
                                            <div class="irantheme-gallery-social-post-item-image" style="<?php echo esc_attr($details_color_result); ?>">
                                                <div class="irantheme-gallery-social-post-item-mask">
                                                    <i class="fa-light fa-arrow-up-right-and-arrow-down-left-from-center"></i>
                                                </div>
                                                <?php
                                                echo wp_get_attachment_image(
                                                    $background_image_data,
                                                    'thumbnail',
                                                    false,
                                                    array(
                                                        'class' => 'irantheme-gallery-social-post-item-img',
                                                        'alt'   => esc_attr($gallery_heading ?: __('گالری شبکه‌های اجتماعی', self::$static_textdomain)),
                                                    )
                                                );
                                                ?>
                                                <?php if ($gallery_type != 'normal'):
                                                    $gallery_type_calss = '';

                                                    if ($gallery_type == 'video') {
                                                        $gallery_type_calss = 'video';
                                                    } elseif ($gallery_type == 'special') {
                                                        $gallery_type_calss = 'special';
                                                    } elseif ($gallery_type == 'hot') {
                                                        $gallery_type_calss = 'hot';
                                                    }
                                                ?>
                                                    <cite class="irantheme-gallery-social-post-item-type <?php echo esc_attr($gallery_type_calss); ?>">
                                                        <?php
                                                        switch ($gallery_type) {
                                                            case 'video':
                                                                echo 'ویدئو';
                                                                break;
                                                            case 'special':
                                                                echo 'ویژه';
                                                                break;
                                                            case 'hot':
                                                                echo 'داغ';
                                                                break;
                                                        }
                                                        ?>
                                                    </cite>
                                                <?php endif; ?>
                                            </div>
                                            <h4 class="irantheme-gallery-social-post-item-heading"><?php _e($gallery_heading); ?></h4>
                                        </div>
                                        <?php
                                        Irantheme_Page_Builder::get_hidden_require_object_buttons($data_page);
                                        ?>
                                    </div>
                                <?php
                                    // Increase aos delay for next item
                                    $aos_delay += 200;
                                    $object_counter++;
                                endforeach;
                                ?>
                                <?php
                                Irantheme_Page_Builder::get_hidden_require_object_group_buttons($data_page);
                                ?>
                            </div>
                        </div>

                        <?php
                        if ($container_checkbox != 'on') :
                        ?>
                        </div>
                    <?php endif; ?>

                    <?php
                    $object_index = 0;
                    $fixed_counter = 0;
                    $total_fixed_objects = count($media_element_array);

                    foreach ($media_element_array as $data_object => $media_elements) {
                        $object_post = isset($json_post_array[$object_index]) && is_array($json_post_array[$object_index])
                            ? $json_post_array[$object_index]
                            : array();

                        $object_fields = isset($object_post['fields']) && is_array($object_post['fields'])
                            ? $object_post['fields']
                            : array();

                        // Product info
                        $product_selection_select = isset($object_fields['product_selection'])
                            ? absint($object_fields['product_selection'])
                            : 0;

                        $product = isset($products_by_id[$product_selection_select])
                            ? $products_by_id[$product_selection_select]
                            : null;

                        $product_id = $product instanceof \WC_Product ? $product->get_id() : 0;
                        $product_url = $product instanceof \WC_Product ? $product->get_permalink() : '';
                        $product_image_id = $product instanceof \WC_Product ? $product->get_image_id() : 0;
                        $product_title = $product instanceof \WC_Product ? $product->get_name() : '';

                        // Get gallery comments count from the batched result.
                        $comments_count = isset($comment_counts[(string) $data_object])
                            ? (int) $comment_counts[(string) $data_object]
                            : 0;
                    ?>
                        <div class="irantheme-gallery-social-fixed" data-code="<?php echo esc_attr($data_object); ?>" data-index="<?php echo esc_attr($fixed_counter); ?>" data-total="<?php echo esc_attr($total_fixed_objects); ?>">

                            <?php if ($total_fixed_objects > 1): ?>
                                <!-- Navigation buttons (Jump between stories) -->
                                <button type="button"
                                    class="irantheme-gallery-social-fixed-nav irantheme-gallery-social-fixed-nav-prev"
                                    data-direction="prev"
                                    aria-label="<?php echo esc_attr('استوری قبلی', self::$static_textdomain); ?>"
                                    <?php echo $fixed_counter === 0 ? 'disabled' : ''; ?>>
                                    <i class="fa-light fa-chevron-right"></i>
                                </button>
                                <button type="button"
                                    class="irantheme-gallery-social-fixed-nav irantheme-gallery-social-fixed-nav-next"
                                    data-direction="next"
                                    aria-label="<?php echo esc_attr('استوری بعدی', self::$static_textdomain); ?>"
                                    <?php echo $fixed_counter === $total_fixed_objects - 1 ? 'disabled' : ''; ?>>
                                    <i class="fa-light fa-chevron-left"></i>
                                </button>
                            <?php endif; ?>

                            <!-- Holder -->
                            <div class="irantheme-gallery-social-fixed-holder">
                                <!-- Holder -->
                                <div class="irantheme-gallery-social-fixed-holder-media">
                                    <div class="irantheme-gallery-social-fixed-holder-media-wrapper">
                                        <!-- Header -->
                                        <div class="irantheme-gallery-social-fixed-holder-media-header">
                                            <ul class="irantheme-gallery-social-fixed-holder-media-header-list">
                                                <!-- Like -->
                                                <li class="irantheme-gallery-social-fixed-holder-media-header-list-item">
                                                    <?php irantheme_get_post_like($data_object); ?>
                                                </li>
                                                <!-- Comments -->
                                                <li class="irantheme-gallery-social-fixed-holder-media-header-list-item">
                                                    <a href="#" class="irantheme-gallery-social-fixed-holder-media-header-list-item-link irantheme-gallery-social-fixed-comments-open">
                                                        <i class="fa-light fa-comments"></i>
                                                        <cite><?php echo esc_html($comments_count); ?></cite>
                                                    </a>
                                                </li>
                                                <!-- Close -->
                                                <li class="irantheme-gallery-social-fixed-holder-media-header-list-item">
                                                    <a href="#" class="irantheme-gallery-social-fixed-holder-media-header-list-item-link irantheme-gallery-social-fixed-close">
                                                        <i class="fa-light fa-times"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>

                                        <?php if ($product instanceof \WC_Product) : ?>
                                            <!-- Product selected -->
                                            <div class="irantheme-gallery-social-fixed-holder-media-product">
                                                <!-- Image -->
                                                <div class="irantheme-gallery-social-fixed-holder-media-product-image">
                                                    <a href="<?php echo esc_url($product_url); ?>">
                                                        <?php
                                                        echo wp_get_attachment_image(
                                                            $product_image_id,
                                                            'medium',
                                                            false,
                                                            array(
                                                                'class'   => 'irantheme-woocommerce-products-item-img irantheme-product-image',
                                                                'alt'     => __('تصویر محصول', self::$static_textdomain),
                                                                'loading' => 'lazy',
                                                                'decoding' => 'async',
                                                            )
                                                        );
                                                        ?>
                                                    </a>
                                                </div>
                                                <!-- Heading -->
                                                <div class="irantheme-gallery-social-fixed-holder-media-product-heading">
                                                    <a href="<?php echo esc_url($product_url); ?>">
                                                        <h2><?php echo wp_trim_words(strip_shortcodes($product_title), 8); ?></h2>
                                                    </a>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <!-- Swiper Container -->
                                        <div class="irantheme-gallery-social-fixed-swiper swiper">
                                            <div class="swiper-wrapper">
                                                <?php foreach ($media_elements as $media_url):
                                                    $media_type = irantheme_get_media_type($media_url);
                                                ?>
                                                    <div class="swiper-slide" data-media-type="<?php echo esc_attr($media_type); ?>">
                                                        <?php if ($media_type === 'image'): ?>
                                                            <!-- Image Slide -->
                                                            <div class="media-container image-container">
                                                                <img src="<?php echo esc_url($media_url); ?>"
                                                                    alt="Gallery Image"
                                                                    class="swiper-image">
                                                            </div>
                                                        <?php elseif ($media_type === 'video'): ?>
                                                            <!-- Video Slide - Instagram Style (Lazy Loaded) -->
                                                            <div class="media-container video-container">
                                                                <video
                                                                    preload="none"
                                                                    class="swiper-video"
                                                                    playsinline
                                                                    loop
                                                                    data-src="<?php echo esc_url($media_url); ?>"
                                                                    data-media-type="video">
                                                                    Your browser does not support the video tag.
                                                                </video>
                                                                <div class="video-play-button">
                                                                    <i class="fa-light fa-play"></i>
                                                                </div>
                                                                <div class="video-paused-overlay">
                                                                    <i class="fa-light fa-pause"></i>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>

                                            <?php
                                            Irantheme_Section_Utils::get_slider_arrow_pagination(
                                                true,
                                                false,
                                                true,
                                                $base_color_class,
                                                $border_radius_range,
                                                'top',
                                                'center'
                                            );
                                            ?>
                                        </div>

                                        <!-- Comments -->
                                        <div class="irantheme-gallery-social-fixed-comments" data-object="<?php echo esc_attr($data_object); ?>">
                                            <?php
                                            /*
                                             * Comments are loaded by GallerySocial.js when the story is opened.
                                             * Keeping the initial list empty removes all comment/user queries from
                                             * the initial page render while preserving the existing AJAX behavior.
                                             */
                                            ?>
                                            <button class="irantheme-button-default irantheme-gallery-social-fixed-comments-close"><i class="fa-light fa-arrow-left-long"></i></button>
                                            <?php if (is_user_logged_in()): ?>
                                                <div class="irantheme-gallery-social-fixed-comments-comment irantheme-input-form-fields">
                                                    <textarea class="irantheme-gallery-social-fixed-comments-comment-textarea" placeholder="<?php echo esc_attr('نظر خود را وارد کنید...', self::$static_textdomain); ?>"></textarea>
                                                    <button class="irantheme-gallery-social-fixed-comments-comment-button irantheme-btn irantheme-btn-white-outline irantheme-btn-block" type="button" data-object="<?php echo esc_attr($data_object); ?>"><?php esc_html_e('ثبت'); ?><i class="iconsax" icon-name="edit-1"></i></button>
                                                </div>
                                            <?php endif; ?>

                                            <!-- Comments List -->
                                            <div class="irantheme-gallery-social-fixed-comments-list"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                        $fixed_counter++;
                        $object_index++;
                    }
                    ?>
                </div>
                <?php
                Irantheme_Page_Builder::get_hidden_require_section_buttons($data_page);
                ?>
                <?php
                // Style scoped
                Irantheme_Section_Utils::display_custom_css_code_style($fields_values);
                ?>
            </section>
<?php
        }
    }
}
