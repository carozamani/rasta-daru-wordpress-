<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;


if (!class_exists('Irantheme_Page_Builder')) {

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
    class Irantheme_Page_Builder
    {
        // Section render map (data_value => class name) - no cache, just array lookup
        private static $section_render_map = array(
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
            'service_note_slider'                   => 'Irantheme_Section_Service_Note_Slider',
            'service_semi_master'                   => 'Irantheme_Section_Service_Semi_Master',
            'service_semi_number'                   => 'Irantheme_Section_Service_Semi_Number',
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
            'page_text'                             => 'Irantheme_Section_Page_Text',
            'post_gallery'                          => 'Irantheme_Section_Post_Gallery',
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

        // Constructor initialize
        public function __construct() {}


        /**
         * Get menu slug of current class
         * @param void
         * @return string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_menu_slug()
        {
            return 'irantheme_settings_visual';
        }


        /**
         * Add page builder link button to page editing
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        function irantheme_page_set_meta_box()
        {
            global $post;

            if (!isset($post->ID)) {
                return;
            }

            // check post status auto-draft
            if (get_post_status($post->ID) === 'auto-draft') {
                return;
            }

            // check post type viewable
            $post_type_object = get_post_type_object($post->post_type);
            if (!is_post_type_viewable($post_type_object)) {
                return;
            }

            // check post status is publish, draft or pending
            if (!in_array($post->post_status, array('publish', 'draft', 'pending'), true)) {
                return;
            }

            // check current page is index page
            $index_page_id = get_option('page_for_posts');
            if ($index_page_id == $post->ID) {
                return;
            }

            add_meta_box('wpc_edit_cont', 'صفحه ساز استورپلاس', array($this, 'irantheme_page_meta_box_markup'), null, 'side', 'high');
        }


        /**
         * Add page builder link button to page editing
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        function irantheme_page_meta_box_markup($post)
        {
            // Check post type viewable
            if (get_post_status($post->ID) === 'auto-draft') {
                return;
            }

            // Check post type viewable
            if ('trash' === $post->post_status) {
                return;
            }

            // Check user permission
            if (!current_user_can('edit_post', $post->ID)) {
                return;
            }

            // Add filter to modify builder params
            add_filter('pbwp_editor_builder_params', 'pbwp_editor_builder_params_filter', 99, 2);

            // Add page builder link button
            $wpcEdit  = '<div class="wpc_metabox_edit_cont irantheme-settings">';
            $wpcEdit .= '<p class="irantheme-status-description mb-2">' . __('با استفاده از دکمه زیر می توانید صفحه جاری را به طور دلخواه در صفحه ساز استورپلاس شخصی سازی کنید.', IRANTHEME_TEXTDOMAIN) . '</p>';
            $wpcEdit .= '<a id="wpc_metabox_edit_link" class="irantheme-button-default irantheme-button-primary" href="' . Irantheme_Page_Builder::get_page_builder_url($post->ID) . '"><span class="fa-light fa-layer-group"></span>' . esc_html__('ویرایش با صفحه ساز استورپلاس', 'page-builder-wp') . '</a>';
            $wpcEdit .= '</div>';

            echo wp_kses_post($wpcEdit);
        }


        /**
         * Add page builder link for pages actions in admin page
         * @param array,WP_Post
         * @return array
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public function apply_row_actions($actions, $post)
        {
            // Only apply to pages - not posts, products, or any other post type
            if ($post->post_type !== 'page') {
                return $actions;
            }

            $post_type_object = get_post_type_object($post->post_type);

            // Check post type viewable
            if (!is_post_type_viewable($post_type_object)) {
                return $actions;
            }

            // Check post status is publish, draft or pending
            if (!in_array($post->post_status, array('publish', 'draft', 'pending'), true)) {
                return $actions;
            }

            // Check current page is index page
            if (get_option('page_for_posts') == $post->ID) {
                return $actions;
            }

            // Check user permission
            if ('trash' === $post->post_status || !current_user_can('edit_post', $post->ID)) {
                return $actions;
            }

            // Add page builder link to actions
            $actions['edit_storeplus_builder'] = sprintf(
                '<a href="%s" class="irantheme-admin-edit-page-row-action" title="%s" data-page="%d" data-type="%s">%s</a>',
                esc_url($this->get_page_builder_url($post->ID)),
                esc_attr__('ویرایش با استورپلاس', IRANTHEME_TEXTDOMAIN),
                absint($post->ID),
                esc_attr($post->post_type),
                esc_html__('ویرایش با استورپلاس', IRANTHEME_TEXTDOMAIN)
            );

            return $actions;
        }


        /**
         * Register fields of pages for saving actions in page builder preview
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function register_pages_fields()
        {
            $args = array(
                'type'         => 'string',
                'description'  => 'Irantheme page builder meta data.',
                'single'       => true,
                'show_in_rest' => false,
            );

            register_post_meta('page', 'irantheme_page_config', $args);
            register_post_meta('page', 'irantheme_page_header', $args);
            register_post_meta('page', 'irantheme_page_sections', $args);
        }


        /**
         * Register page builder template field
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function register_template_field()
        {
            register_setting(self::get_menu_slug(), 'irantheme_page_builder_templates');
        }


        public static function sanitize_json($input)
        {
            // Remove slashes if exist
            $input = wp_unslash($input);
            // Decode
            $decoded = json_decode($input, true);
            if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
                return '{}';
            }
            // Re-encode
            return wp_json_encode($decoded);
        }


        /**
         * Check is page builder?
         * @param void
         * @return bool
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function is_page_builder()
        {
            $page_id = isset($_GET['post_id']) ? sanitize_text_field($_GET['post_id']) : 0;
            $post_type = isset($_GET['post_type']) ? sanitize_text_field($_GET['post_type']) : 0;
            $post_action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : 0;
            if (get_post_status($page_id) && $post_action == 'storeplus_page_builder' && $post_type == 'page') {
                // Check blog page posts
                if ($page_id == get_option('page_for_posts')) {
                    wp_redirect(get_permalink($page_id));
                    exit();
                } else {
                    return true;
                }
            }
            return false;
        }


        /**
         * Create a extra link in wp admin bar for comfortable access to page builder
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        function add_builder_link_wp_admin_bar($admin_bar)
        {
            if (!is_admin()) {
                // Status url
                $status_url = admin_url('admin.php?page=' . 'irantheme_settings_status');
                // plugins url
                $plugins_url = admin_url('admin.php?page=' . 'irantheme_settings_plugins');
                // options url
                $options_url = admin_url('admin.php?page=' . 'irantheme_settings_options');
                // demo url
                $demo_url = admin_url('admin.php?page=' . 'irantheme_settings_demo');
                // cleaner url
                $cleaner_url = admin_url('admin.php?page=' . 'irantheme_settings_cleaner');

                // Template settings
                $admin_bar->add_menu(array(
                    'id' => 'irantheme-storeplus-settings-icon',
                    'title' => '<span class="ab-icon"></span>' . __('تنظیمات قالب', 'some-textdomain'),
                    'href' => $options_url,
                    'meta' => array(
                        'target' => '_self',
                    )
                ));

                // Template settings sub
                $admin_bar->add_menu(array(
                    'id' => 'irantheme-storeplus-settings-sub-status',
                    'parent' => 'irantheme-storeplus-settings-icon',
                    'title' => __('وضعیت سیستم', 'some-textdomain'),
                    'href' => $status_url,
                    'meta' => array(
                        'target' => '_self',
                    )
                ));

                // Template settings sub
                $admin_bar->add_menu(array(
                    'id' => 'irantheme-storeplus-settings-sub-plugins',
                    'parent' => 'irantheme-storeplus-settings-icon',
                    'title' => __('نصب پلاگین ها', 'some-textdomain'),
                    'href' => $plugins_url,
                    'meta' => array(
                        'target' => '_self',
                    )
                ));

                // Template settings sub
                $admin_bar->add_menu(array(
                    'id' => 'irantheme-storeplus-settings-sub-settings',
                    'parent' => 'irantheme-storeplus-settings-icon',
                    'title' => __('تنظیمات قالب', 'some-textdomain'),
                    'href' => $options_url,
                    'meta' => array(
                        'target' => '_self',
                    )
                ));

                // Template settings sub
                $admin_bar->add_menu(array(
                    'id' => 'irantheme-storeplus-settings-sub-demos',
                    'parent' => 'irantheme-storeplus-settings-icon',
                    'title' => __('دمو های قالب', 'some-textdomain'),
                    'href' => $demo_url,
                    'meta' => array(
                        'target' => '_self',
                    )
                ));

                // Template settings sub
                $admin_bar->add_menu(array(
                    'id' => 'irantheme-storeplus-settings-sub-cleaning',
                    'parent' => 'irantheme-storeplus-settings-icon',
                    'title' => __('بهینه و پاکسازی', 'some-textdomain'),
                    'href' => $cleaner_url,
                    'meta' => array(
                        'target' => '_self',
                    )
                ));
            }

            // Check access fo current user
            if (self::is_editable_by_user() && is_page()) {
                global $post;

                // Desktop version
                $admin_bar->add_menu(array(
                    'id' => 'irantheme-storeplus-page-builder-icon',
                    'title' => '<span class="ab-icon"></span>' . __('ویرایش با استورپلاس', 'some-textdomain'),
                    'href' => self::get_page_builder_url($post->ID),
                    'meta' => array(
                        'target' => '_self',
                        'title' => __('ویرایش با صفحه ساز', 'some-textdomain'),
                    )
                ));

                // Mobile version (simplified)
                $admin_bar->add_menu(array(
                    'id' => 'irantheme-storeplus-page-builder-icon-mobile',
                    'title' => '<span class="ab-icon"></span>',
                    'href' => self::get_page_builder_url($post->ID),
                    'meta' => array(
                        'target' => '_self',
                        'title' => __('ویرایش با صفحه ساز استورپلاس', 'some-textdomain'),
                        'class' => 'mobile-only'
                    )
                ));
            }
        }


        // Add CSS to handle mobile display
        function storeplus_admin_bar_mobile_css()
        {
            echo '<style>
        @media screen and (max-width: 782px) {
            #wp-admin-bar-irantheme-storeplus-page-builder-icon { display: none !important; }
            #wp-admin-bar-irantheme-storeplus-page-builder-icon-mobile { display: block !important; }
        }
        @media screen and (min-width: 783px) {
            #wp-admin-bar-irantheme-storeplus-page-builder-icon-mobile { display: none !important; }
        }
    </style>';
        }


        /**
         * Check url and display elements of page builder
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public function render()
        {
            // Is page valid?
            if (self::is_page_builder()) {
                Irantheme_Resource_Enqueue::admin_enqueue();
                $this->page_builder_html();
                exit();
            }
        }


        // /**
        //  * Return page html generator
        //  * @param int
        //  * @return void
        //  * @author iranianclever
        //  * @since v1.0
        //  * @see v1.0
        //  */
        // public static function display_html_page($page_id = null)
        // {
        //     $html = get_post_meta($page_id, irantheme_get_meta_data_html_by_page_id(), true);
        //     echo $html;
        // }


        /**
         * Display sections of current page
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function display_section($page_id = null)
        {
            if ($page_id == null) {
                // Get global query info
                global $wp_query;
                if (is_front_page()) {
                    $post_id = get_option('page_on_front');
                } else if (is_home()) {
                    $post_id = get_option('page_for_posts');
                } else {
                    // Get current page id
                    $post_id = $wp_query->get_queried_object_id();
                }
            } else {
                $post_id = $page_id;
            }
            // Concatenation visual field name with current page id
            $section_option_name = irantheme_get_meta_data_sections_by_page_id();
            // Get current page visual field value
            $section_string_json = get_post_meta($post_id, $section_option_name, true);
            // Convert json data to php array (with fallback for legacy data)
            $associative_fields = Irantheme_JSON_Helper::decode($section_string_json);
            // Normalize objects_json fields to arrays
            $associative_fields = Irantheme_JSON_Helper::normalize_sections($associative_fields);

            // Check visual field value
            if (!empty($associative_fields['sections_items'])) {

                // // ⚡ BENCHMARK: Total start time
                // $benchmark_total_start = microtime(true);
                // $benchmark_results = array();

                foreach ($associative_fields['sections_items'] as $section) {
                    $data_type = $section['data_type'];
                    $data_value = $section['data_value'];
                    $data_page = $section['data_page'];
                    $data_ajax = $section['data_ajax'];

                    // // ⚡ BENCHMARK: Section start time
                    // $benchmark_section_start = microtime(true);
                    // $benchmark_memory_before = memory_get_usage();

                    // Prevent to error of new fields updates
                    $fields_settings = Irantheme_Page_Builder_Ajax::get_function_fields($data_value, true);
                    self::hydrate_missing_fields($fields_settings, $section['section_fields'], $data_value);

                    // Use map instead of switch (much faster)
                    if (isset(self::$section_render_map[$data_value])) {
                        $class = '\\' . __NAMESPACE__ . '\\' . self::$section_render_map[$data_value];
                        if (class_exists($class) && method_exists($class, 'render')) {
                            $class::render($section['section_fields'], $data_type, $data_value, $data_page, $data_ajax);
                        }
                    }

                    // // ⚡ BENCHMARK: Section end time
                    // $benchmark_section_end = microtime(true);
                    // $benchmark_memory_after = memory_get_usage();

                    // $benchmark_results[] = array(
                    //     'value'         => $data_value,
                    //     'title'         => self::get_section_title_from_map($data_value),
                    //     'time'          => ($benchmark_section_end - $benchmark_section_start) * 1000, // ms
                    //     'memory'        => $benchmark_memory_after - $benchmark_memory_before, // bytes
                    //     'count_objects' => self::count_section_objects($section['section_fields']),
                    // );

                    // // ⚡ BENCHMARK: Output report for this section
                    // self::benchmark_output_section($data_value, $benchmark_results[count($benchmark_results) - 1]);
                }

                // // ⚡ BENCHMARK: Total report
                // $benchmark_total_end = microtime(true);
                // self::benchmark_output_total($benchmark_results, ($benchmark_total_end - $benchmark_total_start) * 1000);
            }

            // Get page builder hidden require section adder
            Irantheme_Page_Builder::get_hidden_require_section_adder($post_id);
        }


        /**
         * ⚡ BENCHMARK: Get section human-readable title
         */
        private static function get_section_title_from_map($data_value)
        {
            $titles = array(
                'showcase_simple'                      => 'ویترین ساده',
                'showcase_service'                     => 'ویترین خدمات',
                'showcase_tag'                         => 'ویترین برچسب',
                'showcase_video'                       => 'ویترین ویدیو',
                'showcase_split'                       => 'ویترین تقسیم شده',
                'showcase_author'                      => 'ویترین نویسنده',
                'showcase_counter'                     => 'ویترین شمارنده',
                'slider_simple'                        => 'اسلایدر ساده',
                'service_master'                       => 'خدمات اصلی',
                'service_number'                       => 'خدمات شماره',
                'service_note_slider'                  => 'اسلایدر یادداشت',
                'service_semi_master'                  => 'خدمات نیمه اصلی',
                'service_semi_number'                  => 'خدمات نیمه شماره',
                'service_semi_split_image'             => 'خدمات نیمه تصویر',
                'service_progress'                     => 'خدمات پیشرفت',
                'service_semi_grid_image'              => 'خدمات نیمه گرید',
                'business_authors_post'                => 'نویسندگان تجاری',
                'business_viewpoints'                  => 'دیدگاه‌های تجاری',
                'business_box_reviews'                 => 'نظرات جعبه‌ای',
                'business_ibanner'                     => 'بنر تجاری',
                'business_ads'                         => 'تبلیغات تجاری',
                'business_department'                  => 'دپارتمان تجاری',
                'business_brands'                      => 'برندهای تجاری',
                'business_portfolio'                   => 'نمونه کار تجاری',
                'business_plane_service'               => 'خدمات پلن تجاری',
                'business_modern_plane_service'        => 'خدمات پلن مدرن',
                'business_about_me'                    => 'درباره من',
                'communication_contact'                => 'تماس',
                'communication_line_contact'           => 'تماس خط',
                'communication_google_map'             => 'نقشه گوگل',
                'communication_newsletter'             => 'خبرنامه',
                'communication_faq'                    => 'سوالات متداول',
                'gallery_master'                       => 'گالری اصلی',
                'gallery_banner'                       => 'بنر گالری',
                'gallery_slider'                       => 'اسلایدر گالری',
                'gallery_type'                         => 'گالری نوع',
                'woocommerce_gallery_social'           => 'گالری اجتماعی ووکامرس',
                'page_text'                            => 'متن صفحه',
                'post_gallery'                         => 'گالری نوشته',
                'post_box'                             => 'جعبه نوشته',
                'post_simple'                          => 'نوشته ساده',
                'post_sidebar'                         => 'نوشته سایدبار',
                'woocommerce_products'                 => 'محصولات ووکامرس',
                'woocommerce_categories'               => 'دسته‌بندی‌ها',
                'woocommerce_mini_products'            => 'محصولات کوچک',
                'woocommerce_grid_products'            => 'محصولات گرید',
                'woocommerce_offers_products'          => 'محصولات پیشنهادی',
                'woocommerce_hotspot'                  => 'هات‌اسپات',
                'woocommerce_category_products'        => 'محصولات دسته',
                'woocommerce_slider'                   => 'اسلایدر ووکامرس',
                'woocommerce_box_categories'           => 'دسته‌های جعبه‌ای',
                'woocommerce_box_brands'               => 'برندهای جعبه‌ای',
                'woocommerce_ajax_categories_products' => 'محصولات دسته آژاکس',
                'footer_classic'                       => 'فوتر کلاسیک',
                'footer_master'                        => 'فوتر اصلی',
                'footer_copyright'                     => 'کپی رایت',
                'footer_nav'                           => 'فوتر ناوبری',
            );

            return isset($titles[$data_value]) ? $titles[$data_value] : $data_value;
        }


        /**
         * ⚡ BENCHMARK: Count objects in a section
         */
        private static function count_section_objects($section_fields)
        {
            if (!isset($section_fields['objects_json'])) {
                return 0;
            }

            $objects = Irantheme_JSON_Helper::normalize_objects($section_fields['objects_json']);
            if (!is_array($objects)) {
                return 0;
            }

            return count($objects);
        }


        /**
         * ⚡ BENCHMARK: Output report for a section
         */
        private static function benchmark_output_section($data_value, $result)
        {
            // Only show for admins or when debugging is enabled
            if (!current_user_can('manage_options') && !defined('WP_DEBUG')) {
                return;
            }

            $time_ms = number_format($result['time'], 2);
            $memory_kb = number_format($result['memory'] / 1024, 2);
            $title = $result['title'];
            $objects = $result['count_objects'];

            // Determine severity color
            if ($result['time'] > 500) {
                $color = '#e74c3c'; // Red - Critical
                $severity = 'CRITICAL';
            } elseif ($result['time'] > 200) {
                $color = '#e67e22'; // Orange - Warning
                $severity = 'WARNING';
            } elseif ($result['time'] > 100) {
                $color = '#f1c40f'; // Yellow - Moderate
                $severity = 'MODERATE';
            } else {
                $color = '#27ae60'; // Green - OK
                $severity = 'OK';
            }

            echo "\n<!-- ============================================== -->\n";
            echo '<div style="' .
                'background:#1a1a1a;' .
                'color:#fff;' .
                'font-family:Consolas,Monaco,monospace;' .
                'font-size:13px;' .
                'padding:10px 14px;' .
                'margin:4px 0;' .
                'border-left:5px solid ' . $color . ';' .
                'border-radius:4px;' .
                'direction:ltr;' .
                'text-align:left;' .
                '">';
            echo '<span style="color:' . $color . ';font-weight:bold;">[' . $severity . ']</span> ';
            echo '<span style="color:#3498db;font-weight:bold;">' . esc_html($data_value) . '</span> ';
            echo '<span style="color:#95a5a6;">(' . esc_html($title) . ')</span><br>';
            echo '<span style="color:#ecf0f1;">⏱ Time: <strong style="color:' . $color . ';">' . $time_ms . ' ms</strong></span> | ';
            echo '<span style="color:#ecf0f1;">💾 Memory: <strong>' . $memory_kb . ' KB</strong></span> | ';
            echo '<span style="color:#ecf0f1;">📦 Objects: <strong>' . $objects . '</strong></span>';
            echo '</div>';
            echo "\n<!-- ============================================== -->\n";
        }


        /**
         * ⚡ BENCHMARK: Output total report
         */
        private static function benchmark_output_total($results, $total_time_ms)
        {
            // Only show for admins or when debugging is enabled
            if (!current_user_can('manage_options') && !defined('WP_DEBUG')) {
                return;
            }

            // Sort by time descending
            usort($results, function ($a, $b) {
                return $b['time'] <=> $a['time'];
            });

            $total_time_formatted = number_format($total_time_ms, 2);
            $total_memory = 0;
            foreach ($results as $r) {
                $total_memory += $r['memory'];
            }
            $total_memory_kb = number_format($total_memory / 1024, 2);

            echo "\n<!-- ============================================== -->\n";
            echo '<div style="' .
                'background:#0d1117;' .
                'color:#fff;' .
                'font-family:Consolas,Monaco,monospace;' .
                'font-size:13px;' .
                'padding:16px 20px;' .
                'margin:12px 0;' .
                'border:2px solid #3498db;' .
                'border-radius:8px;' .
                'direction:ltr;' .
                'text-align:left;' .
                '">';

            echo '<div style="font-size:16px;font-weight:bold;color:#3498db;margin-bottom:12px;">';
            echo '📊 IRANTHEME PAGE BUILDER - SECTION BENCHMARK REPORT';
            echo '</div>';

            echo '<div style="background:#161b22;padding:10px;border-radius:4px;margin-bottom:12px;">';
            echo '<div style="color:#ecf0f1;">Total Sections: <strong style="color:#3498db;">' . count($results) . '</strong></div>';
            echo '<div style="color:#ecf0f1;">Total Render Time: <strong style="color:#e74c3c;">' . $total_time_formatted . ' ms</strong></div>';
            echo '<div style="color:#ecf0f1;">Total Memory Used: <strong style="color:#e67e22;">' . $total_memory_kb . ' KB</strong></div>';
            echo '</div>';

            echo '<table style="width:100%;border-collapse:collapse;font-size:12px;">';
            echo '<thead>';
            echo '<tr style="background:#21262d;color:#3498db;">';
            echo '<th style="padding:8px;text-align:left;border-bottom:1px solid #30363d;">#</th>';
            echo '<th style="padding:8px;text-align:left;border-bottom:1px solid #30363d;">Section</th>';
            echo '<th style="padding:8px;text-align:left;border-bottom:1px solid #30363d;">Title</th>';
            echo '<th style="padding:8px;text-align:right;border-bottom:1px solid #30363d;">Time (ms)</th>';
            echo '<th style="padding:8px;text-align:right;border-bottom:1px solid #30363d;">Memory (KB)</th>';
            echo '<th style="padding:8px;text-align:right;border-bottom:1px solid #30363d;">Objects</th>';
            echo '<th style="padding:8px;text-align:right;border-bottom:1px solid #30363d;">% of Total</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            $index = 1;
            foreach ($results as $r) {
                $time = number_format($r['time'], 2);
                $memory = number_format($r['memory'] / 1024, 2);
                $percent = $total_time_ms > 0 ? number_format(($r['time'] / $total_time_ms) * 100, 1) : '0.0';

                // Color code by time
                if ($r['time'] > 500) {
                    $row_color = '#e74c3c';
                } elseif ($r['time'] > 200) {
                    $row_color = '#e67e22';
                } elseif ($r['time'] > 100) {
                    $row_color = '#f1c40f';
                } else {
                    $row_color = '#27ae60';
                }

                echo '<tr style="border-bottom:1px solid #21262d;">';
                echo '<td style="padding:8px;color:#95a5a6;">' . $index . '</td>';
                echo '<td style="padding:8px;color:#3498db;font-weight:bold;">' . esc_html($r['value']) . '</td>';
                echo '<td style="padding:8px;color:#ecf0f1;">' . esc_html($r['title']) . '</td>';
                echo '<td style="padding:8px;text-align:right;color:' . $row_color . ';font-weight:bold;">' . $time . '</td>';
                echo '<td style="padding:8px;text-align:right;color:#ecf0f1;">' . $memory . '</td>';
                echo '<td style="padding:8px;text-align:right;color:#ecf0f1;">' . $r['count_objects'] . '</td>';
                echo '<td style="padding:8px;text-align:right;color:' . $row_color . ';">' . $percent . '%</td>';
                echo '</tr>';

                $index++;
            }

            echo '</tbody>';
            echo '</table>';

            echo '<div style="margin-top:12px;padding:8px;background:#161b22;border-radius:4px;font-size:11px;color:#95a5a6;">';
            echo '🟢 OK (&lt;100ms) &nbsp; | &nbsp; 🟡 MODERATE (100-200ms) &nbsp; | &nbsp; 🟠 WARNING (200-500ms) &nbsp; | &nbsp; 🔴 CRITICAL (&gt;500ms)';
            echo '</div>';

            echo '</div>';
            echo "\n<!-- ============================================== -->\n";
        }


        /**
         * Recursively add missing fields to section fields based on base fields
         * @param array,array,string
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function hydrate_missing_fields(array $base_fields, array &$section_fields, $data_value = null)
        {
            // Loop from base fields (Section raw fields)
            foreach ($base_fields as $fields_settings) {
                foreach ($fields_settings['fields'] as $fields) {
                    // Check section keys and assign new field to section is n't exists
                    if (!array_key_exists($fields['id'], $section_fields)) {
                        $section_fields[$fields['id']] = $fields['default'];
                    }

                    // Check if the field is 'objects_json' and has a non-null value
                    if ($fields['id'] == 'objects_json' && $data_value != null) {
                        // Prevent to error of new fields updates
                        $object_fields_temp = Irantheme_Page_Builder_Ajax::get_object_fields($data_value, 0);

                        // Check if objects_json key exists in section fields, if not, initialize it as an empty array
                        if (!array_key_exists('objects_json', $section_fields)) {
                            $section_fields['objects_json'] = array();
                        }

                        // Normalize objects_json to array (handles legacy string format)
                        $json_post_array = Irantheme_JSON_Helper::normalize_objects($section_fields['objects_json']);

                        if (!empty($json_post_array)) {
                            foreach ($json_post_array as &$object_target) {
                                if (!isset($object_target['fields']) || !is_array($object_target['fields'])) {
                                    $object_target['fields'] = array();
                                }
                                // Recursively adding new fields of objects that is not exists in current section objects
                                self::hydrate_missing_fields($object_fields_temp, $object_target['fields']);
                            }
                            unset($object_target);
                        }

                        // Keep objects_json as ARRAY (not string!)
                        $section_fields['objects_json'] = $json_post_array;
                    }
                }
            }
        }


        /**
         * Generate page builder html
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public function page_builder_html()
        {
?>
            <!DOCTYPE html>
            <html <?php language_attributes(); ?>>

            <head>
                <meta charset="<?php bloginfo('charset'); ?>" />
                <meta http-equiv="X-UA-Compatible" content="IE=edge" />
                <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                <?php //wp_print_styles();
                ?>
                <?php print_late_styles(); ?>
                <?php //print_admin_styles(); 
                ?>
                <?php //do_action('wp_print_footer_scripts', '_wp_footer_scripts'); 
                ?>
                <?php //wp_print_head_scripts(); 
                ?>
                <?php //print_head_scripts();
                ?>
            </head>

            <body>
                <div class="irantheme-settings" data-settings="visual">
                    <!-- Preloader -->
                    <div class="irantheme-preloader dark">
                        <i class="irantheme-animate-rotate irantheme-preloader-loader"></i>
                    </div>
                    <!-- Holder (Parent) -->
                    <div class="irantheme-page-builder-row">
                        <!-- Panel settings -->
                        <div class="irantheme-page-builder-holder-panel">
                            <aside class="irantheme-page-builder-panel">
                                <!-- Form ajax -->
                                <form id="irantheme-form-visual-panel-ajax" class="irantheme-page-builder-panel-form" method="post" action="options.php" enctype="multipart/form-data">
                                    <!-- Header -->
                                    <div class="irantheme-settings-visual-panel-ajax-header">
                                        <div class="irantheme-settings-visual-panel-ajax-header-logo">
                                            <?php Irantheme_Settings::get_logo_html(); ?>
                                            <div class="irantheme-settings-visual-panel-ajax-header-logo-title">صفحه ساز استورپلاس</div>
                                        </div>

                                        <div class="irantheme-settings-visual-panel-ajax-header-media">
                                            <!-- Mobile screen -->
                                            <button type="button" class="irantheme-button-default irantheme-settings-visual-panel-ajax-header-media-toggle irantheme-settings-visual-panel-ajax-header-media-toggle-mobile" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="حالت موبایل"><i class="fa-light fa-mobile"></i></button>
                                            <!-- Tablet screen -->
                                            <button type="button" class="irantheme-button-default irantheme-settings-visual-panel-ajax-header-media-toggle irantheme-settings-visual-panel-ajax-header-media-toggle-tablet" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="حالت تبلت"><i class="fa-light fa-tablet"></i></button>
                                            <!-- Desktop screen -->
                                            <button type="button" class="irantheme-button-default irantheme-settings-visual-panel-ajax-header-media-toggle irantheme-settings-visual-panel-ajax-header-media-toggle-desktop active" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="حالت دسکتاپ"><i class="fa-light fa-desktop"></i></button>
                                        </div>
                                    </div>
                                    <!-- Wrapper of all panel settings -->
                                    <div class="irantheme-page-builder-panel-settings">
                                        <!-- Holder guide -->
                                        <div class="irantheme-page-builder-panel-guide">
                                            <!-- Slider guide -->
                                            <div class="swiper irantheme-page-builder-panel-guide-swiper">
                                                <div class="swiper-wrapper">
                                                    <div class="swiper-slide">
                                                        <div class="irantheme-page-builder-panel-guide-image" style="background-image:url('https://irantheme.com/wp-content/uploads/2025/05/panel-slider-demo.jpg');"></div>
                                                        <h2 class="irantheme-page-builder-panel-guide-heading"><?php _e('از صفحه سازی خسته ای؟ می تونی از دمو های آماده استفاده کنی'); ?></h2>
                                                        <p class="irantheme-page-builder-panel-guide-desc"><?php _e('خیلی راحت و فقط با یک کلیک کل سایتت رو تبدیل به فروشگاه یا هر سایت دیگه ای کن، فقط کافیه به جزئيات بیشتر این اسلاید سر بزنی.'); ?></p>
                                                        <a href="#" class="irantheme-page-builder-panel-guide-link">جزئیات بیشتر</a>
                                                    </div>
                                                    <div class="swiper-slide">
                                                        <div class="irantheme-page-builder-panel-guide-image" style="background-image:url('https://irantheme.com/wp-content/uploads/2025/05/panel-slider-edit.jpg');"></div>
                                                        <h2 class="irantheme-page-builder-panel-guide-heading"><?php _e('فقط با چند کلیک صفحاتت رو بساز و ویرایش کن!') ?></h2>
                                                        <p class="irantheme-page-builder-panel-guide-desc"><?php _e('کافیه از طریق دکمه "افزودن بخش" در محیط صفحه ساز به راحتی و با یک کلیک بلوک های مختلف بسازی و فقط با چند کلیک اونها رو ویرایش و شخصی سازی کنی.'); ?></p>
                                                        <a href="#" class="irantheme-page-builder-panel-guide-link">جزئیات بیشتر</a>
                                                    </div>
                                                    <div class="swiper-slide">
                                                        <div class="irantheme-page-builder-panel-guide-image" style="background-image:url('https://irantheme.com/wp-content/uploads/2025/05/panel-slider-section.jpg');"></div>
                                                        <h2 class="irantheme-page-builder-panel-guide-heading"><?php _e('تفاوت استورپلاس با دیگر صفحه ساز ها'); ?></h2>
                                                        <p class="irantheme-page-builder-panel-guide-desc"><?php _e('یکی از مزیت های اصلی استورپلاس استفاده از طراحان حرفه ای جهت ویرایش سایت شماست، به طوری که نیازی به ساخت جز به جز سایت خودتون ندارید.'); ?></p>
                                                        <a href="#" class="irantheme-page-builder-panel-guide-link">جزئیات بیشتر</a>
                                                    </div>
                                                    <div class="swiper-slide">
                                                        <div class="irantheme-page-builder-panel-guide-image" style="background-image:url('https://irantheme.com/wp-content/uploads/2025/05/panel-slider-sections.jpg');"></div>
                                                        <h2 class="irantheme-page-builder-panel-guide-heading"><?php _e('نیاز به الگو ها داری؟ برات ساختیم'); ?></h2>
                                                        <p class="irantheme-page-builder-panel-guide-desc"><?php _e('با استفاده از الگو نیازی به باز تولید بلوک های سفارشی خودتون ندارید و به راحتی می تونید بلوک هاتون رو ذخیره و در دیگر صفحات اضافه کنید.'); ?></p>
                                                        <a href="#" class="irantheme-page-builder-panel-guide-link">جزئیات بیشتر</a>
                                                    </div>
                                                </div>
                                                <div class="swiper-pagination"></div>
                                            </div>
                                        </div>
                                        <!-- Holder fields of settings options -->
                                        <div class="irantheme-page-builder-panel-fields">
                                            <!-- Preloader -->
                                            <div class="irantheme-preloader-abs blur white">
                                                <i class="irantheme-animate-rotate irantheme-preloader-loader"></i>
                                            </div>
                                            <?php
                                            // Display page config settings fields
                                            Irantheme_Page_Builder_Ajax::render_fields_panel('page_config_settings', self::get_current_page_id());
                                            ?>
                                        </div>
                                    </div>
                                    <!-- Addons -->
                                    <div class="irantheme-page-builder-panel-addons">
                                        <!-- Notification -->
                                        <div class="irantheme-page-builder-panel-addons-note active">تنظیمات صفحه</div>
                                        <!-- Buttons features -->
                                        <div class="irantheme-page-builder-panel-addons-features">
                                            <!-- Exit -->
                                            <a href="<?php echo get_page_link($_GET['post_id']); ?>" class="irantheme-visual-panel-ajax-exit irantheme-button-feature" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="خروج" title="خروج"><i class="fa-light fa-right-from-bracket"></i></a>
                                            <!-- Maximize -->
                                            <span class="irantheme-visual-panel-ajax-increase irantheme-button-feature" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="بزرگنمایی پنل" title="بزرگنمایی پنل"><i class="fa-light fa-arrow-up-right-and-arrow-down-left-from-center"></i></span>
                                            <!-- Preview -->
                                            <a href="<?php echo get_page_link($_GET['post_id']); ?>" target="_blank" class="irantheme-visual-panel-ajax-display irantheme-button-feature" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="پیش نمایش" title="پیش نمایش"><i class="fa-light fa-display"></i></a>
                                            <!-- page config settings -->
                                            <span class="irantheme-visual-panel-ajax-settings irantheme-button-feature" data-type="page_config_settings" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="تنظیمات صفحه" title="تنظیمات صفحه"><i class="fa-solid fa-cog"></i></span>
                                            <!-- Close settings -->
                                            <span class="irantheme-visual-panel-ajax-close irantheme-button-feature" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="خالی کردن" title="خالی کردن"><i class="fa-light fa-empty-set"></i></span>
                                            <!-- Restore to default -->
                                            <span class="irantheme-reset-section irantheme-button-feature" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="بازنشانی تنظیمات" title="بازنشانی تنظیمات"><i class="fa-light fa-arrows-rotate"></i></span>
                                            <!-- Prev page -->
                                            <?php
                                            $prev_class = '';
                                            if (self::get_prev_post_url() == false) {
                                                $prev_class = 'irantheme-button-disabled';
                                            }
                                            // Prev tooltip generator
                                            $prev_attr = '';
                                            if (self::get_prev_post_title()) {
                                                $prev_attr = sprintf('data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="صفحه قبل (%s)"', wp_trim_words(self::get_prev_post_title(), 5));
                                            }
                                            ?>
                                            <a href="<?php echo self::get_prev_post_url(); ?>" class="irantheme-visual-panel-ajax-prev irantheme-button-feature <?php echo esc_attr($prev_class); ?>" <?php echo $prev_attr; ?> title="صفحه قبل"><i class="fa-light fa-arrow-right"></i></a>
                                            <!-- Next page -->
                                            <?php
                                            $next_class = '';
                                            if (self::get_next_post_url() == false) {
                                                $next_class = 'irantheme-button-disabled';
                                            }
                                            // Next tooltip generator
                                            $next_attr = '';
                                            if (self::get_next_post_title()) {
                                                $next_attr = sprintf('data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="صفحه بعد (%s)"', wp_trim_words(self::get_next_post_title(), 5));
                                            }
                                            ?>
                                            <a href="<?php echo self::get_next_post_url(); ?>" class="irantheme-visual-panel-ajax-next irantheme-button-feature <?php echo esc_attr($next_class); ?>" <?php echo $next_attr; ?> title="صفحه بعد"><i class="fa-light fa-arrow-left"></i></a>
                                        </div>
                                        <!-- Buttons actions -->
                                        <div class="irantheme-page-builder-panel-addons-actions">
                                            <!-- Save button -->
                                            <button type="submit" class="irantheme-visual-panel-ajax-save irantheme-button-default irantheme-button-action">
                                                <i id="irantheme-spinner-alt" class="irantheme-animate-rotate"></i>
                                                <svg class="irantheme-success-checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                                                    <circle class="irantheme-success-checkmark-circle" cx="26" cy="26" r="25" fill="none" />
                                                    <path class="irantheme-success-checkmark-check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" />
                                                </svg>
                                                ذخیره کردن
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </aside>
                        </div>

                        <!-- Live preview -->
                        <div class="irantheme-page-builder-holder-preview">
                            <main class="irantheme-page-builder-preview">
                                <!-- Preloader -->
                                <div class="irantheme-preloader-abs blur dark">
                                    <i class="irantheme-animate-rotate irantheme-preloader-loader"></i>
                                </div>
                                <iframe class="irantheme-page-builder-preview-iframe" src="<?php echo self::get_current_page_url(); ?>" frameborder="0"></iframe>
                            </main>
                            <!-- Go to side -->
                            <div class="irantheme-page-builder-panel-side-toggle"><i class="fa-light fa-angle-left"></i></div>
                        </div>

                        <?php
                        // Get section adder html
                        self::get_section_adder();
                        ?>

                        <?php
                        // Get export import page html
                        self::get_export_import_page();
                        ?>

                        <?php
                        // Get template adder html
                        self::get_template_adder();
                        ?>
                    </div>
                    <?php //wp_print_footer_scripts(); 
                    Irantheme_Icons_Selector::nav_menu_icons_elements(false);
                    ?>
                </div>

                <!-- Tips holder -->
                <div class="irantheme-tips-holder"></div>

                <?php wp_print_media_templates(); ?>
                <?php print_footer_scripts(); ?>
                <?php //wp_print_admin_notice_templates(); 
                ?>
            </body>

            </html>
        <?php
        }


        /**
         * Return section adder html
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        private static function get_section_adder()
        {
        ?>
            <!-- Section adder ========================================================================== -->
            <div class="irantheme-settings-visual-sections p-0">
                <!-- Holder sections -->
                <div class="irantheme-settings-visual-sections-main">
                    <div class="irantheme-settings-visual-sections-group">
                        <div class="irantheme-settings-visual-sections-categories">
                            <div class="irantheme-settings-visual-sections-categories-item active" data-value="all" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="<?php _e('همه بخش ها'); ?>" title="<?php _e('همه بخش ها'); ?>">
                                <span class="irantheme-settings-visual-sections-categories-item-icon"><i class="fa-light fa-ball-pile"></i></span>
                            </div>
                            <?php
                            // Generating tab option
                            foreach (Irantheme_Page_Builder_Elements::categories() as $section_tab) {
                            ?>
                                <div class="irantheme-settings-visual-sections-categories-item" data-value="<?php echo esc_attr($section_tab['section_type']); ?>" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="<?php _e($section_tab['section_title']); ?>" title="<?php _e($section_tab['section_title']); ?>">
                                    <span class="irantheme-settings-visual-sections-categories-item-icon"><i class="<?php _e($section_tab['section_icon']); ?>"></i></span>
                                </div>
                            <?php
                            }
                            ?>
                        </div>

                        <div class="irantheme-settings-visual-sections-holder">
                            <!-- Sections holder header -->
                            <div class="irantheme-settings-visual-sections-header">
                                <div class="irantheme-settings-visual-sections-header-alignment">
                                    <div class="irantheme-settings-visual-sections-header-caption">
                                        <!-- Search ============================================= -->
                                        <div class="irantheme-settings-visual-sections-search">
                                            <div class="irantheme-settings-visual-sections-search-icon">
                                                <i class="fa-light fa-magnifying-glass"></i>
                                            </div>
                                            <input class="irantheme-settings-visual-sections-search-input" type="text" placeholder="جستجو بخش مورد نظر ..." />
                                        </div>
                                    </div>
                                    <!-- Close panel ============================================= -->
                                    <div class="irantheme-settings-visual-sections-header-close-panel">
                                        <span class="fa-light fa-xmark irantheme-settings-visual-sections-header-close-panel-icon"></span>
                                    </div>
                                </div>
                            </div>
                            <!-- List sections thumbs ============================================= -->
                            <div class="irantheme-sections-thumb-list">
                                <div class="irantheme-sections-masonry">
                                    <div class="irantheme-sections-masonry-sizer"></div>
                                    <?php
                                    foreach (Irantheme_Page_Builder_Elements::fields() as $section_tab) {
                                    ?>
                                        <div class="irantheme-sections-thumb-item-holder irantheme-sections-masonry-item">
                                            <!-- Section item -->
                                            <div class="irantheme-sections-thumb-item" data-type="<?php echo esc_attr(implode('_', $section_tab['section_type'])); ?>" data-value="<?php echo esc_attr($section_tab['element_value']); ?>" data-keywords="<?php echo esc_attr($section_tab['element_title']); ?>">
                                                <!-- Image -->
                                                <div class="irantheme-sections-thumb-item-image"><img class="irantheme-sections-thumb-item-image-img" src="<?php echo $section_tab['element_thumb']; ?>" alt="Thumbnail section" />
                                                    <div class="irantheme-sections-thumb-item-image-zoom-in">
                                                        <span class="dashicons dashicons-fullscreen-alt"></span>
                                                    </div>
                                                </div>
                                                <!-- Features -->
                                                <div class="irantheme-sections-thumb-item-heading">
                                                    <!-- Feature add section to demo -->
                                                    <div class="irantheme-sections-thumb-feature-add">
                                                        <button class="irantheme-sections-thumb-feature-item" type="button" data-action="add">
                                                            <i class="fa-light fa-download"></i>
                                                            <i id="irantheme-spinner-alt" class="irantheme-animate-rotate"></i>
                                                            <svg class="irantheme-success-checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                                                                <circle class="irantheme-success-checkmark-circle" cx="26" cy="26" r="25" fill="none" />
                                                                <path class="irantheme-success-checkmark-check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" />
                                                            </svg>
                                                            <em><?php _e('بارگذاری'); ?></em>
                                                        </button>
                                                    </div>
                                                </div>
                                                <!-- End tag -->
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="irantheme-section-thumb-magnification">
                    <button class="irantheme-section-thumb-magnification-close-btn" type="button"><span class="dashicons dashicons-no-alt"></span></button>
                    <figure class="irantheme-section-thumb-magnification-figure">
                        <img src="" alt="" class="irantheme-section-thumb-magnification-figure-img">
                    </figure>
                </div>
            </div>
        <?php
        }


        /**
         * Return template adder html
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        private static function get_template_adder()
        {
        ?>
            <!-- Template adder ========================================================================== -->
            <div class="irantheme-settings-visual-templates p-0">
                <!-- Holder templates -->
                <div class="irantheme-settings-visual-templates-main">
                    <div class="irantheme-settings-visual-templates-group">
                        <div class="irantheme-settings-visual-templates-holder">
                            <!-- Preloader -->
                            <div class="irantheme-preloader-abs blur white">
                                <i class="irantheme-animate-rotate irantheme-preloader-loader"></i>
                            </div>
                            <!-- templates holder header -->
                            <div class="irantheme-settings-visual-templates-header">
                                <div class="irantheme-settings-visual-templates-header-alignment">
                                    <div class="irantheme-settings-visual-templates-header-caption">
                                        <!-- Search ============================================= -->
                                        <div class="irantheme-settings-visual-templates-search">
                                            <div class="irantheme-settings-visual-templates-search-icon">
                                                <i class="fa-light fa-magnifying-glass"></i>
                                            </div>
                                            <input class="irantheme-settings-visual-templates-search-input" type="text" placeholder="جستجو در عنوان الگو ..." />
                                        </div>
                                    </div>
                                    <!-- Close panel ============================================= -->
                                    <div class="irantheme-settings-visual-templates-header-close-panel">
                                        <span class="fa-light fa-xmark irantheme-settings-visual-templates-header-close-panel-icon"></span>
                                    </div>
                                </div>
                            </div>
                            <!-- List templates thumbs ============================================= -->
                            <div class="irantheme-templates-holder"></div>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        }


        /**
         * Return export and import page html
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        private static function get_export_import_page()
        {
        ?>
            <!-- Template adder ========================================================================== -->
            <div class="irantheme-settings-visual-export-import p-0">
                <!-- Holder export import -->
                <div class="irantheme-settings-visual-export-import-main">
                    <div class="irantheme-settings-visual-export-import-group">
                        <div class="irantheme-settings-visual-export-import-holder">
                            <!-- Preloader -->
                            <div class="irantheme-preloader-abs blur white">
                                <i class="irantheme-animate-rotate irantheme-preloader-loader"></i>
                            </div>
                            <!-- export import holder header -->
                            <div class="irantheme-settings-visual-export-import-header">
                                <div class="irantheme-settings-visual-export-import-header-alignment">
                                    <div class="irantheme-settings-visual-export-import-header-caption">
                                        <?php _e('پشتیبان گیری از برگه'); ?>
                                    </div>
                                    <!-- Close panel ============================================= -->
                                    <div class="irantheme-settings-visual-export-import-header-close-panel">
                                        <span class="fa-light fa-xmark irantheme-settings-visual-export-import-header-close-panel-icon"></span>
                                    </div>
                                </div>
                            </div>
                            <!-- List export import thumbs ============================================= -->
                            <div class="irantheme-export-import-holder">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="irantheme-upload-container">
                                            <!-- Description -->
                                            <p class="irantheme-export-import-holder-desc">
                                                <?php _e('با استفاده از این بخش شما می توانید فایل XML که قبلا تولید شده است را در اینجا بازگذاری و محتوای خود را بازگردانی کنید. توجه داشته باشید که درون ریزی فقط بر روی صفحه جاری انجام خواهد شد.'); ?>
                                            </p>
                                            <?php Irantheme_Settings::get_notification('warning', 'هشدار: در صورت درون ریزی در برگه جاری، تمامی داده های این برگه حذف و داده های فایل XML جایگزین خواهد شد.', 'm-0 mb-4 d-flex') ?>
                                            <hr>
                                            <h1 class="irantheme-upload-title"><?php _e('درون ریزی فایل'); ?></h1>
                                            <p class="irantheme-upload-description"><?php _e('کشیدن و رها کردن فایل یا انتخاب فایل با کلیک'); ?></p>
                                            <div class="irantheme-upload-area" id="irantheme-uploadArea">
                                                <div class="irantheme-upload-icon">
                                                    <i class="fa-light fa-cloud-upload-alt"></i>
                                                </div>
                                                <p class="irantheme-upload-text"><?php _e('فایلی را انتخاب یا در اینجا رها کنید'); ?></p>
                                                <p class="irantheme-upload-hint"><?php _e('پشتیبانی از'); ?>: XML (Max 10MB)</p>

                                                <input type="file" id="irantheme-fileInput" name="file">
                                            </div>

                                            <div class="irantheme-file-info" id="irantheme-fileInfo">
                                                <div class="irantheme-file-name">
                                                    <span id="irantheme-fileName"><?php _e('فایلی انتخاب نشده'); ?></span>
                                                </div>
                                                <div class="irantheme-file-size" id="irantheme-fileSize">۰ KB</div>
                                            </div>

                                            <div class="irantheme-progress-bar-import" id="irantheme-progressBarImport">
                                                <div class="irantheme-progress-import" id="irantheme-progressImport"></div>
                                            </div>

                                            <button class="irantheme-upload-button" id="irantheme-importPageButton" disabled><?php _e('شروع درون ریزی'); ?></button>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="irantheme-upload-container">
                                            <!-- Description -->
                                            <p class="irantheme-export-import-holder-desc">
                                                <?php _e('با استفاده از این بخش شما می توانید فایل پشتیبان XML خود را تولید و دانلود نمایید. این فایل در قسمت "درون ریزی" می تواند مورد استفاده قرار گیرد. توجه داشته باشید که فایل پشتیبان مربوط به صفحه جاری می باشد.'); ?>
                                            </p>
                                            <?php Irantheme_Settings::get_notification('info', 'نکته: شما می توانید فایل پشتیبان را در سایت جاری یا دیگر سایت ها با قالب استورپلاس بازیابی کنید.', 'm-0 mb-4 d-flex') ?>
                                            <hr>
                                            <h1 class="irantheme-upload-title"><?php _e('برون ریزی فایل'); ?></h1>
                                            <p class="irantheme-upload-description"><?php _e('دانلود و دریافت فایل پشتیبان با فرمت XML'); ?></p>

                                            <div class="irantheme-export-area">
                                                <div class="irantheme-export-icon">
                                                    <i class="fa-light fa-file-export"></i>
                                                </div>
                                                <p class="irantheme-export-text"><?php _e('برگه شما برای برون ریزی آماده است'); ?></p>
                                                <p class="irantheme-export-hint"><?php _e('فایل پشتیبان شما با فرمت XML قابل دانلود می باشد.'); ?></p>
                                            </div>

                                            <div class="irantheme-option-group">
                                                <label class="irantheme-option-label">
                                                    <input type="checkbox" id="irantheme-includeMetadata">
                                                    <?php _e('داده های اضافی شامل: محصولات ووکامرس، نوشته های سایت، دسته بندی ها، فهرست ها و ویجت ها.') ?>
                                                </label>
                                            </div>

                                            <div class="irantheme-progress-bar-export" id="irantheme-progressBarExport">
                                                <div class="irantheme-progress-export" id="irantheme-progressExport"></div>
                                            </div>

                                            <div class="irantheme-success-message" id="irantheme-successMessage">
                                                <?php _e('بروی ریزی با موفقیت انجام شد!'); ?>
                                                <a href="" class="irantheme-success-export-download d-none" target="_blank" download><?php _e('دانلود فایل'); ?></a>
                                            </div>

                                            <button class="irantheme-export-button" id="irantheme-exportPageButton"><?php _e('شروع برون ریزی'); ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }


        /**
         * Return page builder hidden require hover section separator
         * @param void
         * @return mixed
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        private static function get_hidden_require_hover_section_separator($page_id)
        {
            // Get page meta data json
            $page_config_fields = Irantheme_Page_Builder_Ajax::get_page_settings($page_id, 'page_config', 'page_config_fields');
            // Get page is customizable
            $page_customizable = $page_config_fields['irantheme_page_customizable_checkbox'] ?? 'off';

            // Check page builder is enabled
            if ($page_customizable == 'on') {
            ?>
                <!-- Hover section separator -->
                <footer class="irantheme-page-builder-hover-section-separator">
                    <div class="irantheme-page-builder-hover-section-separator-item irantheme-page-builder-hover-section-separator-top"></div>
                    <div class="irantheme-page-builder-hover-section-separator-item irantheme-page-builder-hover-section-separator-right"></div>
                    <div class="irantheme-page-builder-hover-section-separator-item irantheme-page-builder-hover-section-separator-bottom"></div>
                    <div class="irantheme-page-builder-hover-section-separator-item irantheme-page-builder-hover-section-separator-left"></div>
                </footer>
            <?php
            }
        }


        /**
         * Return page builder hidden require section buttons configuration
         * @param bool
         * @return mixed
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_hidden_require_section_buttons($page_id)
        {
            // Get page meta data json
            $page_config_fields = Irantheme_Page_Builder_Ajax::get_page_settings($page_id, 'page_config', 'page_config_fields');
            // Get page is customizable
            $page_customizable = $page_config_fields['irantheme_page_customizable_checkbox'] ?? 'off';

            // Check page builder is enabled
            if ($page_customizable == 'on') {
            ?>
                <!-- Action buttons for sections -->
                <header class="irantheme-page-builder-action-section-button-wrapper">
                    <div class="irantheme-page-builder-action-section-button">
                        <button type="button" class="irantheme-page-builder-action-section-button-item" data-action="move-up" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="انتقال به بالا" title="انتقال به بالا"><i class="fa-light fa-arrow-up"></i></button>
                        <button type="button" class="irantheme-page-builder-action-section-button-item" data-action="move-down" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="انتقال به پایین" title="انتقال به پایین"><i class="fa-light fa-arrow-down"></i></button>
                        <button type="button" class="irantheme-page-builder-action-section-button-item" data-action="template" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="افزودن به الگو" title="افزودن به الگو"><i class="fa-light fa-puzzle-piece"></i></i></button>
                        <button type="button" class="irantheme-page-builder-action-section-button-item" data-action="copy" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="رونوشت" title="رونوشت"><i class="fa-light fa-copy"></i></i></button>
                        <button type="button" class="irantheme-page-builder-action-section-button-item" data-action="edit" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="ویرایش" title="ویرایش"><i class="fa-light fa-sliders-up"></i></i></button>
                        <button type="button" class="irantheme-page-builder-action-section-button-item" data-action="trash" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="حذف" title="حذف"><i class="fa-light fa-times"></i></button>
                    </div>
                </header>
            <?php
                self::get_hidden_require_hover_section_separator($page_id);
            }
        }


        /**
         * Return page builder hidden require hover object separator
         * @param void
         * @return mixed
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        private static function get_hidden_require_hover_object_separator($page_id)
        {
            // Get page meta data json
            $page_config_fields = Irantheme_Page_Builder_Ajax::get_page_settings($page_id, 'page_config', 'page_config_fields');
            // Get page is customizable
            $page_customizable = $page_config_fields['irantheme_page_customizable_checkbox'] ?? 'off';

            // Check page builder is enabled
            if ($page_customizable == 'on') {
            ?>
                <!-- Hover object separator -->
                <footer class="irantheme-page-builder-hover-object-separator">
                    <div class="irantheme-page-builder-hover-object-separator-item irantheme-page-builder-hover-object-separator-top"></div>
                    <div class="irantheme-page-builder-hover-object-separator-item irantheme-page-builder-hover-object-separator-right"></div>
                    <div class="irantheme-page-builder-hover-object-separator-item irantheme-page-builder-hover-object-separator-bottom"></div>
                    <div class="irantheme-page-builder-hover-object-separator-item irantheme-page-builder-hover-object-separator-left"></div>
                </footer>
            <?php
            }
        }


        /**
         * Return page builder hidden require hover object group separator
         * @param void
         * @return mixed
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        private static function get_hidden_require_hover_object_group_separator($page_id)
        {
            // Get page meta data json
            $page_config_fields = Irantheme_Page_Builder_Ajax::get_page_settings($page_id, 'page_config', 'page_config_fields');
            // Get page is customizable
            $page_customizable = $page_config_fields['irantheme_page_customizable_checkbox'] ?? 'off';

            // Check page builder is enabled
            if ($page_customizable == 'on') {
            ?>
                <!-- Hover object group separator -->
                <footer class="irantheme-page-builder-hover-object-group-separator">
                    <div class="irantheme-page-builder-hover-object-group-separator-item irantheme-page-builder-hover-object-group-separator-top"></div>
                    <div class="irantheme-page-builder-hover-object-group-separator-item irantheme-page-builder-hover-object-group-separator-right"></div>
                    <div class="irantheme-page-builder-hover-object-group-separator-item irantheme-page-builder-hover-object-group-separator-bottom"></div>
                    <div class="irantheme-page-builder-hover-object-group-separator-item irantheme-page-builder-hover-object-group-separator-left"></div>
                </footer>
            <?php
            }
        }


        /**
         * Return page builder hidden require object buttons configuration
         * @param void
         * @return mixed
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_hidden_require_object_buttons($page_id)
        {
            // Get page meta data json
            $page_config_fields = Irantheme_Page_Builder_Ajax::get_page_settings($page_id, 'page_config', 'page_config_fields');
            // Get page is customizable
            $page_customizable = $page_config_fields['irantheme_page_customizable_checkbox'] ?? 'off';

            // Check page builder is enabled
            if ($page_customizable == 'on') {
            ?>
                <!-- Action buttons for objects -->
                <header class="irantheme-page-builder-action-object-button-wrapper">
                    <div class="irantheme-page-builder-action-object-button">
                        <button type="button" class="irantheme-page-builder-action-object-button-item" data-action="before" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="انتقال به عقب" title="انتقال به عقب"><i class="fa-light fa-arrow-right"></i></button>
                        <button type="button" class="irantheme-page-builder-action-object-button-item" data-action="after" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="انتقال به جلو" title="انتقال به جلو"><i class="fa-light fa-arrow-left"></i></button>
                        <button type="button" class="irantheme-page-builder-action-object-button-item" data-action="copy" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="رونوشت" title="رونوشت"><i class="fa-light fa-copy"></i></button>
                        <button type="button" class="irantheme-page-builder-action-object-button-item" data-action="edit" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="ویرایش" title="ویرایش"><i class="fa-light fa-edit"></i></i></button>
                        <button type="button" class="irantheme-page-builder-action-object-button-item" data-action="trash" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="حذف" title="حذف"><i class="fa-light fa-times"></i></button>
                    </div>
                </header>
            <?php
                self::get_hidden_require_hover_object_separator($page_id);
            }
        }



        /**
         * Return page builder hidden require object buttons configuration
         * @param void
         * @return mixed
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_hidden_require_object_group_buttons($page_id)
        {
            // Get page meta data json
            $page_config_fields = Irantheme_Page_Builder_Ajax::get_page_settings($page_id, 'page_config', 'page_config_fields');
            // Get page is customizable
            $page_customizable = $page_config_fields['irantheme_page_customizable_checkbox'] ?? 'off';

            // Check page builder is enabled
            if ($page_customizable == 'on') {
                self::get_hidden_require_hover_object_group_separator($page_id);
            ?>
                <!-- Action buttons for objects -->
                <header class="irantheme-page-builder-action-object-group-button-wrapper">
                    <div class="irantheme-page-builder-action-object-group-button">
                        <button type="button" class="irantheme-page-builder-action-object-group-button-item irantheme-page-builder-add-object-button-item" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="اضافه کردن عنصر"><i class="fa-light fa-plus"></i></button>
                    </div>
                </header>
            <?php
            }
        }


        /**
         * Return page builder hidden require header buttons configuration
         * @param void
         * @return mixed
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_hidden_require_header_buttons($page_id)
        {
            // Get page meta data json
            $page_config_fields = Irantheme_Page_Builder_Ajax::get_page_settings($page_id, 'page_config', 'page_config_fields');
            // Get page is customizable
            $page_customizable = $page_config_fields['irantheme_page_customizable_checkbox'] ?? 'off';

            // Check page builder is enabled
            if ($page_customizable == 'on') {
                self::get_hidden_require_hover_section_separator($page_id);
            ?>
                <!-- Action buttons for sections (Header) -->
                <div class="irantheme-page-builder-action-section-button-wrapper-header">
                    <div class="irantheme-page-builder-action-section-button">
                        <button type="button" class="irantheme-page-builder-action-section-button-item" data-action="edit" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="تنظیمات هدر" title="تنظیمات هدر"><i class="fa-light fa-sliders-up"></i></button>
                        <button type="button" class="irantheme-page-builder-action-section-button-item" data-action="header_recover" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="تنظیم مقادیر با هدر تنظیمات قالب" title="تنظیم مقادیر با هدر تنظیمات قالب"><i class="fa-light fa-bring-front"></i></button>
                    </div>
                </div>
            <?php
            }
        }


        /**
         * Return page builder hidden require section adder
         * @param void
         * @return mixed
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_hidden_require_section_adder($page_id)
        {
            // Get page meta data json
            $page_config_fields = Irantheme_Page_Builder_Ajax::get_page_settings($page_id, 'page_config', 'page_config_fields');
            // Get page is customizable
            $page_customizable = $page_config_fields['irantheme_page_customizable_checkbox'] ?? 'off';

            // Check page builder is enabled
            if ($page_customizable == 'on') {
            ?>
                <!-- Holder adders -->
                <div class="irantheme-page-builder-holder-adders irantheme-btn-group irantheme-btn-group-inline">
                    <!-- Section adders -->
                    <button type="button" class="irantheme-page-builder-section-adder-open irantheme-btn irantheme-btn-dark irantheme-btn-medium irantheme-btn-low-radius"><?php esc_html_e('افزودن بخش'); ?><i class="fa-light fa-layer-plus ms-0"></i></button>
                    <!-- Template adders -->
                    <button type="button" class="irantheme-page-builder-template-adder-open irantheme-btn irantheme-btn-primary irantheme-btn-medium irantheme-btn-low-radius"><?php esc_html_e('مشاهده الگو ها'); ?><i class="fa-light fa-puzzle-piece ms-0"></i></button>
                    <!-- Template adders -->
                    <button type="button" class="irantheme-page-builder-export-import-open irantheme-btn irantheme-btn-dark-outline irantheme-btn-medium irantheme-btn-low-radius"><?php esc_html_e('درون و برون ریزی'); ?><i class="fa-light fa-file-export ms-0"></i></button>
                </div>
<?php
            }
        }


        /**
         * Generate previous page url
         * @param void
         * @return mixed
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_prev_post_url()
        {
            // Get a global post reference since get_adjacent_post() references it
            global $post;
            // Store the existing post object for later so we don't lose it
            $oldGlobal = $post;
            // Get the post object for the specified post and place it in the global variable
            $post = get_post($_GET['post_id']);
            // Get the post object for the previous post
            $previous_post = get_previous_post();
            // Reset our global object
            $post = $oldGlobal;
            if ('' == $previous_post) {
                return false;
            }
            $id = $previous_post->ID;
            return self::get_page_builder_url($id);
        }


        /**
         * Generate previous page title
         * @param void
         * @return mixed
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_prev_post_title()
        {
            // Get a global post reference since get_adjacent_post() references it
            global $post;
            // Store the existing post object for later so we don't lose it
            $oldGlobal = $post;
            // Get the post object for the specified post and place it in the global variable
            $post = get_post($_GET['post_id']);
            // Get the post object for the previous post
            $previous_post = get_previous_post();
            // Reset our global object
            $post = $oldGlobal;
            if ('' == $previous_post) {
                return false;
            }
            $id = $previous_post->ID;
            return get_the_title($id);
        }


        /**
         * Generate next page url
         * @param void
         * @return mixed
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_next_post_url()
        {
            // Get a global post reference since get_adjacent_post() references it
            global $post;
            // Store the existing post object for later so we don't lose it
            $oldGlobal = $post;
            // Get the post object for the specified post and place it in the global variable
            $post = get_post($_GET['post_id']);
            // Get the post object for the next post
            $next_post = get_next_post();
            // Reset our global object
            $post = $oldGlobal;
            if ('' == $next_post) {
                return false;
            }
            $id = $next_post->ID;
            return self::get_page_builder_url($id);
        }


        /**
         * Generate next page title
         * @param void
         * @return mixed
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_next_post_title()
        {
            // Get a global post reference since get_adjacent_post() references it
            global $post;
            // Store the existing post object for later so we don't lose it
            $oldGlobal = $post;
            // Get the post object for the specified post and place it in the global variable
            $post = get_post($_GET['post_id']);
            // Get the post object for the next post
            $next_post = get_next_post();
            // Reset our global object
            $post = $oldGlobal;
            if ('' == $next_post) {
                return false;
            }
            $id = $next_post->ID;
            return get_the_title($id);
        }


        /**
         * Generate page builder editable url
         * @param string
         * @return string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_page_builder_url($id)
        {
            $url = add_query_arg(
                [
                    'post_id' => $id,
                    'post_type' => get_post_type($id),
                    'action' => 'storeplus_page_builder',
                ],
                admin_url('post.php')
            );

            return $url;
        }


        /**
         * Get current page url
         * @param void
         * @return int
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_current_page_url()
        {
            $page_id = $_GET['post_id'];
            $url = get_permalink($page_id);
            return $url;
        }


        /**
         * Get attr data section
         * @param string,string,string,string
         * @return string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_attr_data_section($data_type, $data_value, $data_page, $data_ajax)
        {
            if (!self::is_editable_by_user()) {
                return;
            }
            $format = sprintf('data-type=%s data-value=%s data-page=%s data-ajax=%s', $data_type, $data_value, $data_page, $data_ajax);
            return $format;
        }


        /**
         * Get attr data header
         * @param string,string,string,string
         * @return string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_attr_data_header()
        {
            if (!self::is_editable_by_user()) {
                return;
            }
            // Header data page holder
            $header_data_page = sprintf('data-page=%s', self::get_current_page_id());
            // Header data ajax holder
            $header_data_ajax = sprintf('data-ajax=%s', 'empty');
            $format = sprintf('data-type=%s data-value=%s %s %s', 'header', 'header', $header_data_page, $header_data_ajax);
            return $format;
        }


        /**
         * Get current page id
         * @param void
         * @return string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_current_page_id()
        {
            global $wp_query;
            $current_id = $wp_query->get_queried_object_id();
            if (isset($_GET['action']) && $_GET['action'] == 'edit') {
                global $post;
                $current_id = $post->ID;
            } else if (Irantheme_Page_Builder::is_page_builder()) {
                $current_id = $_GET['post_id'];
            }
            return $current_id;
        }


        /**
         * Check user access to page builder
         * @param void
         * @return bool
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function is_editable_by_user()
        {
            global $wp_query;
            if (!isset($wp_query)) {
                return false;
            }

            $is_editable = true;

            // Don't show in the customizer.
            if (is_customize_preview()) {
                $is_editable = false;
            }

            // Don't show for non-single pages.
            if (is_single() || is_archive() || !is_page() && !is_home() && !is_front_page()) {
                $is_editable = false;
            }

            // Don't show if we are not in the main query.
            if (!is_main_query()) {
                $is_editable = false;
            }

            // Don't show if post data is not available.
            if (empty($GLOBALS['post'])) {
                $is_editable = false;
            }

            if ($is_editable) {

                global $post;
                if (is_single()) {
                    if ($post) {
                        $is_editable = current_user_can('edit_post', $post->ID);
                    } else {
                        $is_editable = current_user_can('edit_posts');
                    }
                } else {
                    if ($post) {
                        $is_editable = current_user_can('edit_page', $post->ID);
                    } else {
                        $is_editable = current_user_can('edit_pages');
                    }
                }
            }

            return $is_editable;
        }
    }
}
