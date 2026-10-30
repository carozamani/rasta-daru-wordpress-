<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;


if (!class_exists('Irantheme_Resource_Enqueue')) {

    /**
     * Add backend styles and scripts
     * 
     * @package Wordpress
     * @subpackage Storeplus
     * @since v1.0
     * @author iranianclever
     * @see v1.0
     * 
     */
    class Irantheme_Resource_Enqueue
    {
        /**
         * // Constructor initialize
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public function __construct()
        {
            add_action('admin_enqueue_scripts', array($this, 'admin_enqueue'));
            add_action('wp_enqueue_scripts', array($this, 'front_enqueue'));
            add_action('admin_enqueue_scripts', array($this, 'enqueue_seo_bridges'));
        }


        /**
         * Add admin scripts
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @version v1.0.3
         */
        public static function admin_enqueue()
        {
            // We're including the farbtastic script & styles here because they're needed for the colour picker
            // If you're not including a colour picker field then you can leave these calls out as well as the farbtastic dependency for the wpt-admin-js script below
            wp_enqueue_style('farbtastic');
            wp_enqueue_script('farbtastic');
            // We're including the WP media scripts here because they're needed for the image upload field
            // If you're not including an image upload then you can leave this function call out
            wp_enqueue_media();
            // Init css
            wp_register_style('irantheme-admin-init-css', IRANTHEME_URI . '/assets/admin/css/init.css', array(), filemtime(IRANTHEME_DIR . '/assets/admin/css/init.css'));
            wp_enqueue_style('irantheme-admin-init-css');
            // Adding stylesheet files ======================================================
            self::load_selected_font('iransansx');
            // Settings css
            wp_register_style('irantheme-admin-settings-css', IRANTHEME_URI . '/assets/admin/css/settings.css', array(), filemtime(IRANTHEME_DIR . '/assets/admin/css/settings.css'));
            wp_enqueue_style('irantheme-admin-settings-css');
            // Nav menu css
            wp_register_style('irantheme-admin-nav-menu-css', IRANTHEME_URI . '/assets/admin/css/nav-menu.css', array(), filemtime(IRANTHEME_DIR . '/assets/admin/css/nav-menu.css'));
            wp_enqueue_style('irantheme-admin-nav-menu-css');

            // Check storeplus settings pages
            $current_page = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : '';
            if (in_array($current_page, [
                Irantheme_Settings_Options::get_menu_slug(),
                Irantheme_Settings_Status::get_menu_slug(),
                Irantheme_Settings_Plugins::get_menu_slug(),
                Irantheme_Settings_Cleaner::get_menu_slug(),
                Irantheme_Settings_Demo::get_menu_slug()
            ]) || Irantheme_Page_Builder::is_page_builder()) {
                // Bootstrap css
                wp_register_style('irantheme-admin-bootstrap-css-grid', IRANTHEME_URI . '/assets/admin/libs/bootstrap-grid.min.css', array(), filemtime(IRANTHEME_DIR . '/assets/admin/libs/bootstrap-grid.min.css'));
                wp_enqueue_style('irantheme-admin-bootstrap-css-grid');
                wp_register_style('irantheme-admin-bootstrap-css-utilities', IRANTHEME_URI . '/assets/admin/libs/bootstrap-utilities.min.css', array(), filemtime(IRANTHEME_DIR . '/assets/admin/libs/bootstrap-utilities.min.css'));
                wp_enqueue_style('irantheme-admin-bootstrap-css-utilities');
            }

            // Check is storeplus page builder
            if (Irantheme_Page_Builder::is_page_builder()) {
                // Swiper css
                wp_register_style('irantheme-admin-swiper-css', IRANTHEME_URI . '/assets/admin/libs/swiper-bundle.min.css', array(), filemtime(IRANTHEME_DIR . '/assets/admin/libs/swiper-bundle.min.css'));
                wp_enqueue_style('irantheme-admin-swiper-css');
                // Builder css
                wp_register_style('irantheme-admin-builder-css', IRANTHEME_URI . '/assets/admin/css/builder.css', array(), filemtime(IRANTHEME_DIR . '/assets/admin/css/builder.css'));
                wp_enqueue_style('irantheme-admin-builder-css');
            }
            // ========================================================================================
            // Settings js
            wp_register_script('irantheme-admin-settings-js', IRANTHEME_URI . '/assets/admin/js/settings.js', array('jquery'), filemtime(IRANTHEME_DIR . '/assets/admin/js/settings.js'), true);
            wp_enqueue_script('irantheme-admin-settings-js');
            // Nav menu js
            wp_register_script('irantheme-admin-nav-menu-js', IRANTHEME_URI . '/assets/admin/js/nav-menu.js', array('jquery'), filemtime(IRANTHEME_DIR . '/assets/admin/js/nav-menu.js'), true);
            wp_enqueue_script('irantheme-admin-nav-menu-js');

            // Check is storeplus page builder
            if (Irantheme_Page_Builder::is_page_builder()) {
                // Masonry js
                wp_register_script('irantheme-admin-masonry-js', IRANTHEME_URI . '/assets/admin/libs/masonry.pkgd.min.js', array(), filemtime(IRANTHEME_DIR . '/assets/admin/libs/masonry.pkgd.min.js'), true);
                wp_enqueue_script('irantheme-admin-masonry-js');
                // Imagesloaded js
                wp_register_script('irantheme-admin-imagesloaded-js', IRANTHEME_URI . '/assets/admin/libs/imagesloaded.pkgd.min.js', array(), filemtime(IRANTHEME_DIR . '/assets/admin/libs/imagesloaded.pkgd.min.js'), true);
                wp_enqueue_script('irantheme-admin-imagesloaded-js');
                // Swiper js
                wp_register_script('irantheme-admin-swiper-js', IRANTHEME_URI . '/assets/admin/libs/swiper-bundle.min.js', array(), filemtime(IRANTHEME_DIR . '/assets/admin/libs/swiper-bundle.min.js'), true);
                wp_enqueue_script('irantheme-admin-swiper-js');
                // Jquery js
                wp_register_script('irantheme-admin-jquery-ui-js', IRANTHEME_URI . '/assets/admin/libs/jquery-ui.min.js', array('jquery'), filemtime(IRANTHEME_DIR . '/assets/admin/libs/jquery-ui.min.js'), true);
                wp_enqueue_script('irantheme-admin-jquery-ui-js');
                // Builder js
                wp_register_script('irantheme-admin-builder-js', IRANTHEME_URI . '/assets/admin/js/builder.js', array('jquery', 'irantheme-admin-jquery-ui-js', 'irantheme-admin-masonry-js', 'irantheme-admin-imagesloaded-js', 'irantheme-admin-swiper-js'), filemtime(IRANTHEME_DIR . '/assets/admin/js/builder.js'), true);
                wp_enqueue_script('irantheme-admin-builder-js');
            }

            // Set custom js wp data
            wp_localize_script('irantheme-admin-settings-js', 'iranthemeWpData', array(
                'nonce' => wp_create_nonce('irantheme_nonce'),
                'adminAjax' => admin_url('admin-ajax.php'),
                'postId' => Irantheme_Page_Builder::get_current_page_id(),
                'pageBuilderStoreplusEditable' => Irantheme_Page_Builder::is_editable_by_user()
            ));
        }


        /**
         * Add front scripts
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @version v1.0.3
         */
        public static function front_enqueue()
        {
            // Check is storeplus page builder
            if (!Irantheme_Page_Builder::is_page_builder()) {
                // Adding javascript files =======================================================
                wp_enqueue_script('irantheme-main-js', IRANTHEME_URI . '/build/index.js', array('jquery'), filemtime(IRANTHEME_DIR . '/build/index.js'), true);

                // Add custom JavaScript before all other code execution
                $custom_js = "
(function() {
    window.addEventListener('load', function() {
        
        // Handle all skeleton sections (all have swiper - same logic for all)
        document.querySelectorAll('.irantheme-skeleton-section').forEach(function(section) {
            
            let attempts = 0;
            const maxAttempts = 40; // 2 seconds max (40 * 50ms)
            
            const checkSwiper = setInterval(function() {
                attempts++;
                const swiperContainer = section.querySelector('.swiper-container, .swiper');
                
                // Check if swiper is ready (same condition for all)
                if (swiperContainer && swiperContainer.swiper) {
                    clearInterval(checkSwiper);
                    
                    // Same delay for all sections
                    setTimeout(function() {
                        removeSkeleton(section);
                        
                        // Refresh swiper (same for all)
                        if (swiperContainer.swiper) {
                            swiperContainer.swiper.update();
                            swiperContainer.swiper.updateSlides();
                            swiperContainer.swiper.updateSize();
                        }
                    }, 150); // Uniform delay
                }
                
                // Safe timeout (same for all)
                if (attempts >= maxAttempts) {
                    clearInterval(checkSwiper);
                    removeSkeleton(section);
                }
            }, 50);
        });
        
        function removeSkeleton(section) {
            if (section.classList.contains('irantheme-section-loaded')) return;
            
            section.classList.add('irantheme-section-loaded');
            const skeleton = section.querySelector('.irantheme-skeleton-loader');
            if (skeleton) skeleton.style.display = 'none';
            
            const content = section.querySelector('.irantheme-side-load-skeleton');
            if (content) content.style.display = 'block';
        }
    });
})();
";

                wp_add_inline_script('irantheme-main-js', $custom_js, 'before');

                // Adding stylesheet files ======================================================
                $page_id_temp = Irantheme_Page_Builder::get_current_page_id();
                // Get page meta data json
                $page_config_fields = Irantheme_Page_Builder_Ajax::get_page_settings($page_id_temp, 'page_config', 'page_config_fields');
                $font_main_select = '';
                // Get page is customizable
                $page_customizable = $page_config_fields['irantheme_page_customizable_checkbox'] ?? 'off';
                if ($page_customizable == 'on') {
                    // Get font family --------------------------------------------------------
                    $font_main_select = $page_config_fields['irantheme_font_main_select'];
                } else {
                    // Get font family --------------------------------------------------------
                    $font_main_select = get_option('irantheme_font_main_select');
                }
                self::load_selected_font($font_main_select);
                // Adding style sheet (main)
                wp_enqueue_style('irantheme-main-css', IRANTHEME_URI . '/build/index.css', array(), filemtime(IRANTHEME_DIR . '/build/index.css'));
                wp_enqueue_style('style', get_stylesheet_uri());

                // Get number of OTP code digits
                $code_expiration_time = get_option('irantheme_otp_login_code_resend_time');

                /**
                 * Localize script data for frontend
                 * Contains all necessary data for JavaScript functionality
                 */
                wp_localize_script('irantheme-main-js', 'iranthemeWpData', array(
                    // Site information
                    'rootUrl' => get_site_url(),
                    'restUrl' => rest_url(),
                    'adminAjax' => admin_url('admin-ajax.php'),
                    'currentPageId' => Irantheme_Page_Builder::get_current_page_id(),
                    'currentUrl' => get_permalink(Irantheme_Page_Builder::get_current_page_id()),
                    'permalink_type' => get_option('permalink_structure'),

                    // Security
                    'nonce' => wp_create_nonce('wp_rest'), // This is the correct action for REST API

                    // User authentication
                    'isUserLoggedIn' => is_user_logged_in(),

                    // Page builder settings
                    'pageBuilderStoreplusEditable' => Irantheme_Page_Builder::is_editable_by_user(),

                    // Gallery comment system specific data
                    'galleryNonce' => wp_create_nonce('irantheme_gallery_nonce'),

                    'resend_cooldown_seconds' => (int) $code_expiration_time,
                    'security'                => wp_create_nonce('otp_login_woo_nonce')
                ));

                if (is_woocommerce_activated()) {
                    $woocommerceActivateStatus = 1;
                    wp_localize_script(
                        'irantheme-main-js',
                        'iranthemeWoo',
                        array(
                            'ajaxUrl'        => admin_url('admin-ajax.php'),
                            'ajaxPost'       => admin_url('admin-post.php'),
                            'restUrl'        => rest_url('wp/v2/product'),
                            'shopName'       => sanitize_title_with_dashes(sanitize_title_with_dashes(get_bloginfo('name'))),
                            'inWishlist'     => esc_html__("Already in wishlist", "text-domain"),
                            'woocommerceIsActivate' => $woocommerceActivateStatus,
                            'shoppingCartUrl' => wc_get_cart_url(),
                        )
                    );
                }
            }
        }


        /**
         * Add fonts selected
         * @param string $selected_font selecting font
         * @return void
         * @author iranianclever
         * @since v1.0.3
         * @version v1.0.3
         */
        public static function load_selected_font($selected_font)
        {
            // FontAwesome is always loaded
            wp_enqueue_style('iconsax', IRANTHEME_URI . '/assets/fonts/iconsax/css/iconsax.css');
            wp_enqueue_style('fontawesome-base', IRANTHEME_URI . '/assets/fonts/fontawesome-pro/css/fontawesome.css');
            wp_enqueue_style('fontawesome-light', IRANTHEME_URI . '/assets/fonts/fontawesome-pro/css/light.css');
            wp_enqueue_style('fontawesome-solid', IRANTHEME_URI . '/assets/fonts/fontawesome-pro/css/solid.css');
            wp_enqueue_style('fontawesome-brands', IRANTHEME_URI . '/assets/fonts/fontawesome-pro/css/brands.css');

            // Load selected font
            switch ($selected_font) {
                case 'vazirmatn':
                    wp_enqueue_style('vazirmatn', IRANTHEME_URI . '/assets/fonts/vazirmatn/css/Vazirmatn-font-face.css');
                    break;
                case 'shabnam':
                    wp_enqueue_style('shabnam', IRANTHEME_URI . '/assets/fonts/shabnam/css/shabnam-font-face.css');
                    break;
                case 'sahel':
                    wp_enqueue_style('sahel', IRANTHEME_URI . '/assets/fonts/sahel/css/Sahel-font-face.css');
                    break;
                case 'iransansx':
                    wp_enqueue_style('iransansx', IRANTHEME_URI . '/assets/fonts/iransansx/css/Iransansx-font-face.css');
                    break;
                case 'iranyekanx':
                    wp_enqueue_style('iranyekanx', IRANTHEME_URI . '/assets/fonts/iranyekanx/css/Iranyekanx-font-face.css');
                    break;
                case 'tanha':
                    wp_enqueue_style('tanha', IRANTHEME_URI . '/assets/fonts/tanha/css/tanha-font-face.css');
                    break;
            }
        }


        /**
         * Enqueue SEO plugin bridge scripts only when the target plugin
         * is actually active.
         *
         * @param string $hook
         * @return void
         */
        public function enqueue_seo_bridges(string $hook): void
        {
            if (! in_array($hook, ['post.php', 'post-new.php'], true)) {
                return;
            }

            // Rank Math
            if (self::is_rank_math_active()) {
                $path = IRANTHEME_DIR . '/assets/admin/js/rank-math-bridge.js';
                if (file_exists($path)) {
                    wp_enqueue_script(
                        'irantheme-rank-math-bridge',
                        IRANTHEME_URI . '/assets/admin/js/rank-math-bridge.js',
                        ['jquery', 'wp-hooks'],
                        filemtime($path),
                        true
                    );

                    wp_localize_script('irantheme-rank-math-bridge', 'iranthemeAjax', [
                        'nonce'    => wp_create_nonce('irantheme_builder'),
                        'ajax_url' => admin_url('admin-ajax.php'),
                    ]);
                }
            }

            // Yoast SEO
            if (self::is_yoast_active()) {
                $path = IRANTHEME_DIR . '/assets/admin/js/yoast-bridge.js';
                if (file_exists($path)) {
                    wp_enqueue_script(
                        'irantheme-yoast-bridge',
                        IRANTHEME_URI . '/assets/admin/js/yoast-bridge.js',
                        ['jquery', 'yoast-seo-post-analysis'],
                        filemtime($path),
                        true
                    );

                    wp_localize_script('irantheme-yoast-bridge', 'iranthemeYoast', [
                        'nonce'    => wp_create_nonce('irantheme_builder'),
                        'ajax_url' => admin_url('admin-ajax.php'),
                    ]);
                }
            }
        }


        /**
         * Check if Rank Math SEO is active.
         *
         * Uses the version constant which is defined as soon as the plugin
         * loads. Safer than checking for the class name.
         *
         * @return bool
         */
        public static function is_rank_math_active(): bool
        {
            return defined('RANK_MATH_VERSION')
                || class_exists('RankMath')
                || function_exists('rank_math');
        }

        /**
         * Check if Yoast SEO is active.
         *
         * Uses the version constant which is defined as soon as the plugin
         * loads. Safer than checking for the class name.
         *
         * @return bool
         */
        public static function is_yoast_active(): bool
        {
            return defined('WPSEO_VERSION')
                || class_exists('WPSEO_Options')
                || function_exists('yoast');
        }

        /**
         * Check if All in One SEO is active.
         *
         * @return bool
         */
        public static function is_aioseo_active(): bool
        {
            return defined('AIOSEO_VERSION')
                || function_exists('aioseo');
        }

        /**
         * Check if SEOPress is active.
         *
         * @return bool
         */
        public static function is_seopress_active(): bool
        {
            return defined('SEOPRESS_VERSION')
                || function_exists('seopress_init');
        }
    }

    new Irantheme_Resource_Enqueue();
}
