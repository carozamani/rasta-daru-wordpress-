<?php

namespace Irantheme_Dev;

use Irantheme_Ajax_Search_Box;

if (!defined('ABSPATH')) exit;

if (!class_exists('Irantheme_Header_Main')) {

    /**
     * Irantheme header configuration methods and elements
     * And header features and constructs
     * 
     * @package Wordpress
     * @subpackage Storeplus
     * @since v1.0
     * 
     */
    class Irantheme_Header_Main
    {
        // Initialization
        function __construct() {}


        /**
         * Get logo
         * @return string
         * @see v1.0
         */
        public static function irantheme_get_logo(string $force_color = '', int $force_height = 0, $page_id = null)
        {
            // Main holder logo
            $logo_result = null;

            // Get logo text checkbox
            $logo_mode = irantheme_get_header_option('irantheme_logo_mode_radio', $page_id);
            // Get logo text size number
            $logo_text_size = irantheme_get_header_option('irantheme_logo_text_size_number', $page_id);

            // Get logo svg code
            $logo_svg_code = irantheme_get_header_option('irantheme_logo_svg_code_textarea', $page_id);

            // Get header option color
            $header_option_color = irantheme_get_header_option('irantheme_header_base_color_select', $page_id);

            //Get mono logo
            $mono_logo = irantheme_get_header_option('irantheme_mono_logo_image', $page_id);
            $url_mono_logo = null;

            // Get logo black
            $logo_black = irantheme_get_header_option('irantheme_logo_black_image', $page_id);
            $url_logo_black = null;

            // Get logo white
            $logo_white = irantheme_get_header_option('irantheme_logo_white_image', $page_id);
            $url_logo_white = null;

            // Get height logo image
            $logo_height = $force_height ? $force_height : irantheme_get_header_option('irantheme_logo_height_number', $page_id);

            //Get logo mobile mono checkbox
            $logo_mobile_mono_checker = irantheme_get_header_option('irantheme_logo_mobile_mono_checkbox', $page_id);

            //Get logo mobile svg checkbox
            $logo_mobile_svg_checker = irantheme_get_header_option('irantheme_logo_mobile_svg_checkbox', $page_id);

            //Get logo mobile switchable checkbox
            $logo_mobile_switchable_checker = irantheme_get_header_option('irantheme_logo_mobile_switchable_checkbox', $page_id);

            // Get logo mobile svg code
            $logo_mobile_svg_code = irantheme_get_header_option('irantheme_logo_mobile_svg_code_textarea', $page_id);

            //Get mobile mono logo
            $mobile_mono_logo = irantheme_get_header_option('irantheme_logo_mobile_mono_image', $page_id);
            $url_mono_logo = null;

            // Get mobile logo black
            $mobile_logo_black = irantheme_get_header_option('irantheme_logo_mobile_black_image', $page_id);
            $url_logo_black = null;

            // Get mobile logo white
            $mobile_logo_white = irantheme_get_header_option('irantheme_logo_mobile_white_image', $page_id);
            $url_logo_white = null;

            // Get height mobile logo image
            $logo_mobile_height = $force_height ? $force_height : irantheme_get_header_option('irantheme_logo_mobile_height_number', $page_id);

            // Get height mobile logo svg
            $logo_mobile_svg_height = $force_height ? $force_height : irantheme_get_header_option('irantheme_logo_mobile_svg_height_number', $page_id);

            // Custom class for logo mobile
            $logo_mobile_class = '';
            // Get text replacement
            $text_replacement = irantheme_get_header_option('irantheme_logo_replacement_text', $page_id);
            // Check logo text
            if ($logo_mode == 'text') {
                if ($force_color == 'white') {
                    $class_force_color = 'irantheme-color-white';
                } else if ($force_color == 'dark') {
                    $class_force_color = 'irantheme-color-dark';
                }
                // Add logo text instead logo image and svg
                $tag = is_home() ? 'h1' : 'div';
                $logo_result = '<' . esc_attr($tag) . ' class="irantheme-logo-text-holder"><a href="' . esc_url(get_home_url('/')) . '" class="irantheme-logo-text ' . $class_force_color . '" style="font-size:' . $logo_text_size . 'px;" rel="home">' . esc_html(get_bloginfo('name')) . '</a></' . esc_attr($tag) . '>';
            } elseif ($logo_mode == 'svg') {
                // Check svg checker and svg code to assignment
                if ($logo_mode == 'svg') {
                    if ($logo_svg_code) {
                        if ($force_color == 'white') {
                            $class_force_color = 'irantheme-fill-white';
                        } else if ($force_color == 'dark') {
                            $class_force_color = 'irantheme-fill-dark';
                        }
                        // Check logo mobile svg is set
                        if ($logo_mobile_svg_checker == 'on' && $logo_mobile_svg_code) {
                            $logo_mobile_class = "irantheme-has-logo-mobile ";
                        }
                        // Wrapped with logo holder
                        $logo_result = '<a href="' . esc_url(get_home_url('/')) . '" class="irantheme-logo-svg-holder ' . $logo_mobile_class . $class_force_color . '" style="height:' . $logo_height . 'px;">';
                        // Assign svg code to logo result
                        $logo_result .= $logo_svg_code;
                        // End wrapper
                        $logo_result .= '</a>';
                        // Check mobile svg checker and svg code to assignment
                        if ($logo_mobile_svg_checker == 'on' && $logo_mobile_svg_code) {
                            // Wrapped with logo holder
                            $logo_result .= '<a href="' . esc_url(get_home_url('/')) . '" class="irantheme-logo-svg-holder irantheme-logo-mobile ' . $class_force_color . '" style="height:' . $logo_mobile_svg_height . 'px;">';
                            // Assign svg code to logo result
                            $logo_result .= $logo_mobile_svg_code;
                            // End wrapper
                            $logo_result .= '</a>';
                        }
                    }
                    // Check mono logo checker and mono logo to assignment
                }
            } else if ($logo_mode == 'upload_mono') {
                if ($mono_logo) {
                    // Convert logo id to image src
                    $url_mono_logo = wp_get_attachment_image_src($mono_logo, 'full')[0];
                    // Check logo mobile mono is set
                    if ($logo_mobile_mono_checker == 'on' && $mobile_mono_logo) {
                        $logo_mobile_class = "irantheme-has-logo-mobile ";
                    }
                    // Wrapped with logo holder
                    $logo_result = '<a href="' . get_home_url('/') . '"class="irantheme-logo-img-holder ' . $logo_mobile_class . '" style="height:' . $logo_height . 'px;">';
                    // Create image to logo result
                    $logo_result .= '<img src="' . $url_mono_logo . '"class="irantheme-logo-img" alt="' . $text_replacement . '" />';
                    // End wrapper
                    $logo_result .= '</a>';
                    // Check mobile logo checker and mono logo to assignment
                    if ($logo_mobile_mono_checker == 'on' && $mobile_mono_logo) {
                        // Convert logo id to image src
                        $url_mobile_mono_logo = wp_get_attachment_image_src($mobile_mono_logo, 'full')[0];
                        // Wrapped with logo holder
                        $logo_result .= '<a href="' . get_home_url('/') . '"class="irantheme-logo-img-holder irantheme-logo-mobile" style="height:' . $logo_mobile_height . 'px;">';
                        // Create image to logo result
                        $logo_result .= '<img src="' . $url_mobile_mono_logo . '"class="irantheme-logo-img" alt="' . $text_replacement . '" />';
                        // End wrapper
                        $logo_result .= '</a>';
                    }
                }
            } else if ($logo_mode == 'upload_multi') {
                if ($logo_black || $logo_white) {
                    // Check logo black src
                    if ($logo_black) {
                        // Convert logo id to image src
                        $url_logo_black = wp_get_attachment_image_src($logo_black, 'full')[0];
                    }
                    // Check logo black white
                    if ($logo_white) {
                        // Convert logo id to image src
                        $url_logo_white = wp_get_attachment_image_src($logo_white, 'full')[0];
                    }
                    // Check logo mobile mono is set
                    if ($logo_mobile_switchable_checker == 'on') {
                        if ($mobile_logo_black || $mobile_logo_white) {
                            $logo_mobile_class = "irantheme-has-logo-mobile ";
                        }
                    }
                    // Wrapped with logo holder
                    $logo_result = '<a href="' . get_home_url('/') . '" class="irantheme-logo-img-holder ' . $logo_mobile_class . '" style="height:' . $logo_height . 'px;" data-img-dark="' . $url_logo_black . '" data-img-white="' . $url_logo_white . '">';
                    if ($force_color) {
                        if ($force_color == 'white') {
                            // Create image to logo result
                            $logo_result .= '<img src="' . $url_logo_white . '" class="irantheme-logo-img" alt="' . $text_replacement . '" />';
                        } else if ($force_color == 'dark') {
                            // Create image to logo result
                            $logo_result .= '<img src="' . $url_logo_black . '" class="irantheme-logo-img" alt="' . $text_replacement . '" />';
                        }
                    } else {
                        if ('dark' == $header_option_color) {
                            // Create image to logo result
                            $logo_result .= '<img src="' . $url_logo_white . '" class="irantheme-logo-img" alt="' . $text_replacement . '" />';
                        } else if ('white' == $header_option_color) {
                            // Create image to logo result
                            $logo_result .= '<img src="' . $url_logo_black . '" class="irantheme-logo-img" alt="' . $text_replacement . '" />';
                        }
                    }
                    // End wrapper
                    $logo_result .= '</a>';
                }
                // Check mobile logo checker and switchable logo to assignment
                if ($logo_mobile_switchable_checker == 'on') {
                    if ($mobile_logo_black || $mobile_logo_white) {
                        // Check logo black src
                        if ($mobile_logo_black) {
                            // Convert logo id to image src
                            $url_mobile_logo_black = wp_get_attachment_image_src($mobile_logo_black, 'full')[0];
                        }
                        // Check logo black white
                        if ($mobile_logo_white) {
                            // Convert logo id to image src
                            $url_mobile_logo_white = wp_get_attachment_image_src($mobile_logo_white, 'full')[0];
                        }
                        // Wrapped with logo holder
                        $logo_result .= '<a href="' . get_home_url('/') . '" class="irantheme-logo-img-holder irantheme-logo-mobile" style="height:' . $logo_mobile_height . 'px;" data-img-dark="' . $url_mobile_logo_black . '" data-img-white="' . $url_mobile_logo_white . '">';
                        if ($force_color) {
                            if ($force_color == 'white') {
                                // Create image to logo result
                                $logo_result .= '<img src="' . $url_mobile_logo_white . '" class="irantheme-logo-img irantheme-logo-mobile-img" alt="' . $text_replacement . '" />';
                            } else if ($force_color == 'dark') {
                                // Create image to logo result
                                $logo_result .= '<img src="' . $url_mobile_logo_black . '" class="irantheme-logo-img irantheme-logo-mobile-img" alt="' . $text_replacement . '" />';
                            }
                        } else {
                            if ('dark' == $header_option_color) {
                                // Create image to logo result
                                $logo_result .= '<img src="' . $url_mobile_logo_white . '" class="irantheme-logo-img irantheme-logo-mobile-img" alt="' . $text_replacement . '" />';
                            } else if ('white' == $header_option_color) {
                                // Create image to logo result
                                $logo_result .= '<img src="' . $url_mobile_logo_black . '" class="irantheme-logo-img irantheme-logo-mobile-img" alt="' . $text_replacement . '" />';
                            }
                        }
                        // End wrapper
                        $logo_result .= '</a>';
                    }
                }
            }
            return $logo_result;
        }


        /**
         * Get header logo elements
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_logo($page_id = null)
        {
?>
            <!-- Logo -->
            <div class="irantheme-logo-brand">
                <?php echo self::irantheme_get_logo('', 0, $page_id); ?>
            </div>
        <?php
        }


        /**
         * Get header navbar main menu
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_navbar_main_menu($page_id = null)
        {
        ?>
            <?php
            if (irantheme_get_header_option('irantheme_header_alignment_radio', $page_id) != 'navigation' && irantheme_get_header_option('irantheme_header_alignment_radio', $page_id) != 'toggle-menu') :
                // Main menu
                self::irantheme_navbar_main_menu($page_id);
            endif;
            ?>
        <?php
        }


        /**
         * Get header navbar secondary menu
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_navbar_secondary_menu($page_id = null)
        {
        ?>
            <?php
            if (irantheme_get_header_option('irantheme_header_secondary_menu_checkbox', $page_id) == 'on' && irantheme_get_header_option('irantheme_header_alignment_radio', $page_id) != 'toggle-menu') {
                // Secondary menu
                self::irantheme_navbar_secondary_menu(3, $page_id);
            }
            ?>
        <?php
        }


        /**
         * Get social media
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_social_media($page_id = null)
        {
        ?>
            <?php
            if (irantheme_get_header_option('irantheme_nav_menu_social_media_checkbox', $page_id) == 'on' && irantheme_get_header_option('irantheme_header_alignment_radio', $page_id) != 'toggle-menu') :
            ?>
                <?php
                // Get social media
                Irantheme_Common_Static::get_social_media();
                ?>
            <?php endif; ?>
        <?php
        }


        /**
         * Get navbar options icons
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_options($page_id = null)
        {
            // Get desktop multiple options
            $desktop_multiple_checkbox = irantheme_get_header_option('irantheme_desktop_tool_multiple_checkboxes', $page_id);
            $desktop_multiple_checkbox_array = explode(',', $desktop_multiple_checkbox);
            // Assign desktop hiding class
            $desktop_options_status_class = 'irantheme-navbar-options-desktop-hide';
            // Check desktop items for remove hiding class
            if (in_array('search', $desktop_multiple_checkbox_array) || in_array('wishlist', $desktop_multiple_checkbox_array) || in_array('basket', $desktop_multiple_checkbox_array) || in_array('profile', $desktop_multiple_checkbox_array)) {
                $desktop_options_status_class = '';
            }

            // Initialize trigger variables with safe defaults
            $data_trigger_desktop = '';
            $data_trigger_mobile = '';

            if (irantheme_get_header_option('irantheme_header_alignment_radio', $page_id) == 'toggle-menu') {
                $data_trigger_desktop = irantheme_get_header_option('irantheme_desktop_trigger_menu_mode', $page_id);
            }
            $data_trigger_mobile = irantheme_get_header_option('irantheme_mobile_trigger_menu_mode', $page_id);
        ?>
            <!-- Options -->
            <div class="irantheme-navbar-options <?php echo esc_attr($desktop_options_status_class); ?>">
                <!-- Icon trigger options -->
                <ul class="irantheme-navbar-options-list justify-content-end">
                    <!-- Navigation toggle -->
                    <li class="irantheme-navbar-options-list-item irantheme-navbar-trigger-holder" data-trigger-desktop="<?php echo esc_attr($data_trigger_desktop); ?>" data-trigger-mobile="<?php echo esc_attr($data_trigger_mobile); ?>">
                        <!-- Open nav -->
                        <button class="irantheme-navbar-trigger irantheme-btn-void">
                            <span></span>
                            <span></span>
                            <span></span>
                        </button>
                    </li>

                    <!-- Search toggle -->
                    <li class="irantheme-navbar-options-list-item irantheme-search-box-toggle <?php echo esc_attr(self::irantheme_get_navbar_option_visibility_modes_class('search')); ?>"><a href="#" class="irantheme-navbar-options-list-item-link"><i class="iconsax" icon-name="search-normal-2"></i></a></li>

                    <!-- User simple -->
                    <?php
                    if (irantheme_get_header_option('irantheme_simple_profile_checkbox', $page_id) == 'on') :
                        // Get wordpress dashboard url
                        $dashboard_url = get_edit_profile_url();
                        // If woocommerce is activated add dashboard wc to dashboard_url
                        if (is_woocommerce_activated()) {
                            $dashboard_url = wc_get_account_endpoint_url('dashboard');
                        }
                    ?>
                        <li class="irantheme-navbar-options-list-item <?php echo esc_attr(self::irantheme_get_navbar_option_visibility_modes_class('profile')); ?>">
                            <a href="<?php echo esc_url_raw($dashboard_url); ?>" class="irantheme-navbar-options-list-item-link">
                                <i class="iconsax" icon-name="user-2"></i>
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- Wishlist toggle -->
                    <li class="irantheme-navbar-options-list-item <?php echo esc_attr(self::irantheme_get_navbar_option_visibility_modes_class('wishlist', $page_id)); ?>">
                        <button class="irantheme-btn-void irantheme-navbar-options-list-item-link irantheme-woocommerce-wishlist-navigation-open">
                            <i class="iconsax" icon-name="heart"></i>
                            <?php
                            // Get wishlist count
                            irantheme_woocommerce_wishlist_count();
                            ?>
                        </button>
                    </li>

                    <!-- Shopping cart toggle -->
                    <li class="irantheme-navbar-options-list-item <?php echo esc_attr(self::irantheme_get_navbar_option_visibility_modes_class('basket', $page_id)); ?>">
                        <button class="irantheme-btn-void irantheme-navbar-options-list-item-link irantheme-woocommerce-cart-navigation-open">
                            <i class="iconsax" icon-name="basket-1"></i>
                            <?php
                            // Get shopping number cart count
                            irantheme_woocommerce_shopping_cart_count();
                            ?>
                        </button>
                    </li>
                </ul>
                <?php if (irantheme_get_header_option('irantheme_header_alignment_radio', $page_id) != 'navigation' && irantheme_get_header_option('irantheme_simple_profile_checkbox', $page_id)  != 'on') : ?>
                    <!-- User options -->
                    <?php
                    // Check the Show Profile checkbox 
                    // Set color mini profile with header color
                    if (self::irantheme_get_header_base_color_class_option($page_id) == 'irantheme-header-white') {
                        $mini_profile_color = 'white';
                    } else if (self::irantheme_get_header_base_color_class_option($page_id) == 'irantheme-header-dark') {
                        $mini_profile_color = 'dark';
                    }
                    $profile_name = false;
                    $profile_email = false;
                    $profile_more = false;
                    // Get account detail multi checkbox
                    $profile_detail = irantheme_get_header_option('irantheme_profile_tool_multiple_checkboxes', $page_id);
                    $profile_detail = explode(',', $profile_detail);
                    foreach ($profile_detail as $profile_detail_value) {
                        if ($profile_detail_value == 'profile_name') {
                            $profile_name = true;
                        }
                        if ($profile_detail_value == 'profile_email') {
                            $profile_email = true;
                        }
                        if ($profile_detail_value == 'profile_more') {
                            $profile_more = true;
                        }
                    }
                    self::irantheme_display_mini_profile('large', 'top', $mini_profile_color, 'left', true, $profile_name, $profile_email, $profile_more, self::irantheme_get_navbar_option_visibility_modes_class('profile', $page_id), $page_id);
                    ?>
                <?php endif; ?>
            </div>
            <?php
        }


        /**
         * Get header columns
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_header_columns($page_id = null)
        {
            $header_mode = irantheme_get_header_option('irantheme_header_alignment_radio', $page_id);
            switch ($header_mode) {
                case 'column-three':
            ?>
                    <!-- Col item -->
                    <div class="irantheme-header-col">
                        <?php
                        // Get logo
                        self::get_logo();
                        ?>
                    </div>
                    <!-- Col item -->
                    <div class="irantheme-header-col">
                        <?php
                        self::navbar_search_box_search_form($page_id, false, true);
                        ?>
                    </div>
                    <!-- Col item -->
                    <div class="irantheme-header-col">
                        <?php
                        // Get social media
                        self::get_social_media();
                        // Get options
                        self::get_options();
                        ?>
                    </div>
                <?php
                    break;

                case 'right':
                ?>
                    <!-- Col item -->
                    <div class="irantheme-header-col">
                        <?php
                        // Get logo
                        self::get_logo();
                        ?>
                        <?php
                        // Get main menu
                        self::get_navbar_main_menu();
                        ?>
                    </div>
                    <!-- Col item -->
                    <div class="irantheme-header-col">
                        <?php
                        // Get secondary menu
                        self::get_navbar_secondary_menu();
                        // Get social media
                        self::get_social_media();
                        // Get options
                        self::get_options();
                        ?>
                    </div>
                <?php
                    break;

                case 'left':
                ?>
                    <!-- Col item -->
                    <div class="irantheme-header-col">
                        <?php
                        // Get logo
                        self::get_logo();
                        ?>
                    </div>
                    <!-- Col item -->
                    <div class="irantheme-header-col">
                        <?php
                        // Get main menu
                        self::get_navbar_main_menu();
                        // Get secondary menu
                        self::get_navbar_secondary_menu();
                        // Get social media
                        self::get_social_media();
                        // Get options
                        self::get_options();
                        ?>
                    </div>
                <?php
                    break;

                case 'center':
                    $header_base_color = irantheme_get_header_option('irantheme_header_base_color_select', $page_id);
                    // Set base color class
                    $base_color_class = Irantheme_Common_Static::get_base_color_class($header_base_color);
                ?>
                    <!-- Col item -->
                    <div class="irantheme-header-col">
                        <?php
                        // Get logo
                        self::get_logo();
                        ?>
                    </div>
                    <hr class="irantheme-header-col-column-separator <?php echo esc_attr($base_color_class); ?>" />
                    <!-- Col item -->
                    <div class="irantheme-header-col">
                        <?php
                        // Get main menu
                        self::get_navbar_main_menu();
                        // Get secondary menu
                        self::get_navbar_secondary_menu();
                        // Get social media
                        self::get_social_media();
                        // Get options
                        self::get_options();
                        ?>
                    </div>
                <?php
                    break;

                case 'center-split':
                ?>
                    <!-- Col item -->
                    <div class="irantheme-header-col irantheme-header-col-logo">
                        <?php
                        // Get logo
                        self::get_logo();
                        // Get social media
                        self::get_social_media();
                        ?>
                    </div>
                    <!-- Col item -->
                    <div class="irantheme-header-col irantheme-header-col-menu">
                        <?php
                        // Get logo
                        self::get_logo();
                        // Get main menu
                        self::get_navbar_main_menu();
                        ?>
                    </div>
                    <!-- Col item -->
                    <div class="irantheme-header-col">
                        <?php
                        // Get secondary menu
                        self::get_navbar_secondary_menu();
                        // Get options
                        self::get_options();
                        ?>
                    </div>
                <?php
                    break;

                case 'center-double':
                ?>
                    <!-- Col item -->
                    <div class="irantheme-header-col">
                        <?php
                        // Get main menu
                        self::get_navbar_main_menu();
                        ?>
                    </div>
                    <!-- Col item -->
                    <div class="irantheme-header-col">
                        <?php
                        // Get logo
                        self::get_logo();
                        ?>
                    </div>
                    <!-- Col item -->
                    <div class="irantheme-header-col">
                        <?php
                        // Get secondary menu
                        self::get_navbar_secondary_menu();
                        // Get social media
                        self::get_social_media();
                        // Get options
                        self::get_options();
                        ?>
                    </div>
                <?php
                    break;

                case 'justify':
                ?>
                    <!-- Col item -->
                    <div class="irantheme-header-col">
                        <?php
                        // Get logo
                        self::get_logo();
                        ?>
                    </div>
                    <!-- Col item -->
                    <div class="irantheme-header-col">
                        <?php
                        // Get main menu
                        self::get_navbar_main_menu();
                        ?>
                    </div>
                    <!-- Col item -->
                    <div class="irantheme-header-col">
                        <?php
                        // Get secondary menu
                        self::get_navbar_secondary_menu();
                        // Get social media
                        self::get_social_media();
                        // Get options
                        self::get_options();
                        ?>
                    </div>
                <?php
                    break;

                case 'navigation':
                ?>
                    <!-- Col item -->
                    <div class="irantheme-header-col">
                        <?php
                        // Get logo
                        self::get_logo();
                        ?>
                    </div>
                    <!-- Col item -->
                    <div class="irantheme-header-col">
                        <?php
                        // Get options
                        self::get_options();
                        ?>
                    </div>
                <?php
                    break;

                case 'toggle-menu':
                ?>
                    <!-- Col item -->
                    <div class="irantheme-header-col">
                        <?php
                        // Get logo
                        self::get_logo();
                        ?>
                    </div>
                    <!-- Col item -->
                    <div class="irantheme-header-col">
                        <?php
                        // Get options
                        self::get_options();
                        ?>
                    </div>
            <?php
                    break;
            }
        }


        /**
         * Return bool if current url is page and header customizable is on
         * @param void
         * @return bool
         * @see v1.0
         */
        public static function irantheme_is_header_full_width($page_id = null)
        {
            $header_full_width = irantheme_get_header_option('irantheme_header_full_width', $page_id);
            if ($header_full_width == 'on') {
                return true;
            }
            return false;
        }


        /**
         * Get header class alignment
         * @param void
         * @return string
         * @see v1.0
         */
        public static function irantheme_get_header_class_alignment($page_id = null)
        {
            // Get option for check & assign header custom value
            $option = irantheme_get_header_option('irantheme_header_alignment_radio', $page_id);
            // Check value option
            if ($option) {
                // Evolution option value and return specific class
                switch ($option) {
                    case 'column-three':
                        return 'irantheme-header-alignment-column-three';
                        break;

                    case 'right':
                        return 'irantheme-header-alignment-right';
                        break;

                    case 'left':
                        return 'irantheme-header-alignment-left';
                        break;

                    case 'center':
                        return 'irantheme-header-alignment-center';
                        break;

                    case 'center-split':
                        return 'irantheme-header-alignment-center-split';
                        break;

                    case 'center-double':
                        return 'irantheme-header-alignment-center-double';
                        break;

                    case 'justify':
                        return 'irantheme-header-alignment-justify';
                        break;

                    case 'navigation':
                        return 'irantheme-header-alignment-navigation';
                        break;

                    case 'toggle-menu':
                        return 'irantheme-header-alignment-toggle-menu';
                        break;

                    default:
                        return '';
                        break;
                }
            }
        }


        /**
         * Check header have width limit
         * @return bool
         * @param void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function irantheme_is_header_width_limit($page_id = null)
        {
            $option = irantheme_get_header_option('irantheme_header_width_limit', $page_id);
            if ($option == 'on') {
                return true;
            }
            return false;
        }


        /**
         * Get header radius css
         * @return bool
         * @param void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function irantheme_get_header_radius_css($page_id = null)
        {
            $header_radius_top_range = irantheme_get_header_option('irantheme_header_radius_top_range', $page_id);
            $header_radius_bottom_range = irantheme_get_header_option('irantheme_header_radius_bottom_range', $page_id);
            $header_radius_css = '';
            if (!self::irantheme_is_header_full_width($page_id) && self::irantheme_is_header_width_limit($page_id)) {
                $header_radius_css = 'border-bottom-right-radius:' . $header_radius_bottom_range . 'rem;border-bottom-left-radius:' . $header_radius_bottom_range . 'rem;';
                $header_radius_css .= 'border-top-right-radius:' . $header_radius_top_range . 'rem;border-top-left-radius:' . $header_radius_top_range . 'rem;';
            }
            return $header_radius_css;
        }


        /**
         * Get header transparency options from settings options
         * @param void
         * @return string
         * @see v1.0
         */
        public static function irantheme_get_header_transparency_css_option($page_id = null)
        {
            // Holder of range transparency
            $header_transparent_attr = '';
            // Check is enable transparent option field
            if (irantheme_get_header_option('irantheme_header_bg_transparent_checkbox', $page_id) == 'on') {
                $header_transparency_range = irantheme_get_header_option('irantheme_header_bg_transparency_range', $page_id);
                $header_transparency_range = (100 - $header_transparency_range) / 100;
                $header_bg_select = irantheme_get_header_option('irantheme_header_base_color_select', $page_id);
                // Get header background color
                $header_bg_option = irantheme_get_header_option('irantheme_header_bg_color_select', $page_id);
                if ($header_bg_option == 'off') {
                    // Check header color class
                    if ('white' == $header_bg_select) {
                        // Add white transparency
                        $header_transparent_attr = 'background: rgba(255, 255, 255, ' . $header_transparency_range . ') !important;';
                    } else if ('dark' == $header_bg_select) {
                        // Add dark transparency
                        $header_transparent_attr = 'background: rgba(31, 34, 41, ' . $header_transparency_range . ') !important;';
                    }
                } else {
                    $hex_color = Irantheme_Color_Static::get_hex_color($header_bg_option);
                    // Add white transparency
                    $rgb_color = Irantheme_Color_Static::hex_to_rgb($hex_color);
                    $header_transparent_attr = 'background: rgba(' . $rgb_color[0] . ', ' . $rgb_color[1] . ', ' . $rgb_color[2] . ', ' . $header_transparency_range . ') !important;';
                }
            }
            return $header_transparent_attr;
        }


        /**
         * Get header blur options from settings options
         * @param void
         * @return string
         * @see v1.0
         */
        public static function irantheme_get_header_blur_css_option($page_id = null)
        {
            // Holder of range blur
            $header_blur_attr = '';
            // Check is enable transparent option field
            if (irantheme_get_header_option('irantheme_header_bg_transparent_checkbox', $page_id) == 'on') {
                $header_blur_range = irantheme_get_header_option('irantheme_header_bg_blur_range', $page_id);
                // Add css blur
                $header_blur_attr = 'backdrop-filter:blur(' . $header_blur_range . 'px);-webkit-backdrop-filter:blur(' . $header_blur_range . 'px);';
            }
            return $header_blur_attr;
        }


        /**
         * Return header transparent class, if option enabled
         * @param void
         * @return string
         * @see v1.0
         */
        public static function irantheme_get_header_transparent_class_option($page_id = null)
        {
            if (irantheme_get_header_option('irantheme_header_bg_transparent_checkbox', $page_id) == 'on') {
                return 'irantheme-header-transparency';
            }
        }


        /**
         * Get header background color class from settings options
         * @param void
         * @return string
         * @see v1.0
         */
        public static function irantheme_get_header_base_color_class_option($page_id = null)
        {
            // Get class bg color header
            $header_class = '';
            // Check header bg color option
            if ('white' == irantheme_get_header_option('irantheme_header_base_color_select', $page_id)) {
                $header_class .= 'irantheme-header-white';
            } else if ('dark' == irantheme_get_header_option('irantheme_header_base_color_select', $page_id)) {
                $header_class .= 'irantheme-header-dark';
            }
            return $header_class;
        }

        /**
         * Return header background color
         * @param int
         * @return string
         * @author iranianclever
         * @see v1.0
         */
        public static function irantheme_get_header_bg_color($page_id = null)
        {
            // Custom color
            $header_css_color = '';
            // Get header background color
            $header_bg_option = irantheme_get_header_option('irantheme_header_bg_color_select', $page_id);
            if ($header_bg_option != 'off') {
                $header_css_color = 'background: #' . esc_attr(Irantheme_Color_Static::get_hex_color($header_bg_option)) . ';';
            }
            return $header_css_color;
        }


        /**
         * Get header border bottom class from settings options
         * @param int
         * @return string
         * @see v1.0
         */
        public static function irantheme_get_header_border_bottom_class_option($page_id = null)
        {
            // Get class border mode header
            $header_class = '';
            $header_border_style = irantheme_get_header_option('irantheme_header_border_mode', $page_id);
            // Check header border mode option
            if ($header_border_style == 'border') {
                $header_class = 'irantheme-header-border-mode';
                // Check header shadow mode option
            } else if ($header_border_style == 'shadow') {
                $header_class = 'irantheme-header-shadow-mode';
            }
            return $header_class;
        }


        /**
         * Get header height from settings options
         * @param void
         * @return string
         * @see v1.0
         */
        public static function irantheme_get_header_height_option($page_id = null)
        {
            // Holder of height
            $header_height = '';
            // Get header height option
            $header_option = irantheme_get_header_option('irantheme_header_height', $page_id);
            // Check option value
            if ($header_option || $header_option === '0') {
                // Assign height option
                $header_height = 'padding: ' . ($header_option * 0.025) . 'rem 0;';
            }
            return $header_height;
        }


        /**
         * Get header background class color on scroll triggered
         * @param void
         * @return string
         * @see v1.0
         */
        public static function irantheme_get_header_bg_class_color_on_scroll($page_id = null)
        {
            // Holder of class
            $header_class = '';
            //Get option for check & assign header custom color value
            if (irantheme_get_header_option('irantheme_header_fixed_checkbox', $page_id) == 'on') {
                // Check sticky option
                if (irantheme_get_header_option('irantheme_header_sticky_checkbox', $page_id) == 'on') {
                    // Check value of option
                    if (irantheme_get_header_option('irantheme_header_bg_color_on_scroll_checkbox', $page_id) == 'on') {
                        // Assign specific class color scrolled
                        $header_class = 'irantheme-header-color-scrolled';
                        // Get option color
                        $header_option_color = irantheme_get_header_option('irantheme_header_bg_color_on_scroll_select', $page_id);
                        // Check option color
                        if ('white' == $header_option_color) {
                            $header_class .= ' irantheme-header-white-important';
                        } else if ('dark' == $header_option_color) {
                            $header_class .= ' irantheme-header-dark-important';
                        }
                    }
                }
            }
            return $header_class;
        }


        /**
         * Return type container header (Full width or compatible with main wrapper)
         * @param void
         * @return string
         * @see v1.0
         */
        public static function irantheme_get_header_class_container($page_id = null)
        {
            // Holder of container type
            $header_class = '';
            // Get header full width option
            $header_full_width = self::irantheme_is_header_full_width($page_id);
            $header_width_limit = self::irantheme_is_header_width_limit($page_id);
            // Check value header full width option
            if (!$header_full_width && !$header_width_limit) {
                // Assign header container class
                $header_class = 'irantheme-container-inner px-0';
            } else {
                // Assign header container class
                $header_class = 'irantheme-container-header-full';
            }
            return $header_class;
        }


        /**
         * Return type container header (Full width or compatible with main wrapper)
         * @param void
         * @return string
         * @see v1.0
         */
        public static function irantheme_get_search_box_class_container($page_id = null)
        {
            // Holder of container type
            $header_class = '';
            // Get header full width option
            $header_full_width = self::irantheme_is_header_full_width($page_id);
            $header_width_limit = self::irantheme_is_header_width_limit($page_id);
            // Check value header full width option
            if (!$header_full_width && !$header_width_limit) {
                // Assign header container class
                $header_class = 'irantheme-container-inner';
            } else {
                // Assign header container class
                $header_class = 'irantheme-wrapper-padding';
            }
            return $header_class;
        }


        /**
         * Return header fixed class
         * @param void
         * @return string
         * @see v1.0
         */
        public static function irantheme_get_header_class_fixed($page_id = null)
        {
            // Holder of fixed class
            $header_class = '';
            // Get header fixed checkbox option
            $header_fixed = irantheme_get_header_option('irantheme_header_fixed_checkbox', $page_id);
            // Check value header fixed checkbox option
            if ($header_fixed == 'on') {
                // Assign header fixed class
                $header_class = 'irantheme-header-fixed';
            }
            return $header_class;
        }


        /**
         * Return header sticky class
         * @param void
         * @return string
         * @see v1.0
         */
        public static function irantheme_get_header_class_sticky($page_id = null)
        {
            // Holder of sticky class
            $header_class = '';
            if (irantheme_get_header_option('irantheme_header_fixed_checkbox', $page_id) == 'on') {
                // Get header sticky checkbox option
                $header_sticky = irantheme_get_header_option('irantheme_header_sticky_checkbox', $page_id);
                // Check value header sticky checkbox option
                if ($header_sticky == 'on') {
                    // Assign header sticky class
                    $header_class = 'irantheme-header-sticky';
                }
            }
            return $header_class;
        }


        /**
         * Return header class shrink
         * @param void
         * @return string
         * @see v1.0
         */
        public static function irantheme_get_header_class_shrink($page_id = null)
        {
            // Holder of shrink class
            $header_class = '';
            if (self::irantheme_get_header_class_sticky($page_id)) {
                // Get header shrink checkbox option
                $header_shrink = irantheme_get_header_option('irantheme_header_shrink_on_scroll_checkbox', $page_id);
                // Check value header shrink checkbox option
                if ($header_shrink == 'on') {
                    // Assign header shrink class
                    $header_class = 'irantheme-header-shrink';
                }
            }
            return $header_class;
        }


        // =============================================== Global on navbar


        /**
         * Return navbar option show/hide classes
         * @param void
         * @return string
         * @see v1.0
         */
        public static function irantheme_get_navbar_option_visibility_modes_class($type_option, $page_id = null)
        {
            // Get desktop status
            $desktop_status = irantheme_get_header_option('irantheme_desktop_tool_multiple_checkboxes', $page_id);
            $desktop_status = explode(',', $desktop_status);
            // Get mobile status section
            $mobile_status_section = irantheme_get_header_option('irantheme_overwrite_setting_mobile_tool_checkbox', $page_id);
            $mobile_status = $desktop_status;
            if ($mobile_status_section == 'on') {
                // Get mobile status
                $mobile_status = irantheme_get_header_option('irantheme_mobile_tool_multiple_checkboxes', $page_id);
                $mobile_status = explode(',', $mobile_status);
            }
            // Css classes holder
            $css_classes = '';

            if (!in_array($type_option, $desktop_status)) {
                $css_classes = 'irantheme-navbar-options-list-item-desktop-hide';
            }

            if (!in_array($type_option, $mobile_status)) {
                $css_classes .= ' irantheme-navbar-options-list-item-mobile-hide';
            }

            return $css_classes;
        }


        /**
         * Return require css to right display header
         * @param void
         * @return string
         * @see v1.0
         */
        public static function irantheme_get_header_necessary_css($page_id = null)
        {
            $header_css = '';
            // Check navigation side status and hide header
            if (irantheme_get_header_option('irantheme_header_alignment_radio', $page_id) == 'navigation') {
                $header_css .= 'display:none;';
            }
            return $header_css;
        }


        /**
         * Return require css to right display header
         * @param void
         * @return string
         * @see v1.0
         */
        public static function irantheme_get_header_alignment_necessary_css($page_id = null)
        {
            $header_css = '';
            $header_position_fixed = irantheme_get_header_option('irantheme_header_fixed_checkbox', $page_id);
            $header_position_sticky = irantheme_get_header_option('irantheme_header_sticky_checkbox', $page_id);
            // Get containers options ranges --------------------------------------------------------
            // Get page meta data json
            $page_config_fields = Irantheme_Page_Builder_Ajax::get_page_settings($page_id, 'page_config', 'page_config_fields');
            $outer_container = $page_config_fields['irantheme_outer_container'];
            if ($outer_container && $outer_container < 100 && $header_position_fixed == 'on' && $header_position_sticky == 'on') {
                $right_calc = (100 - $outer_container) / 2;
                $header_css .= 'width:' . $outer_container . '%;right:' . $right_calc . '%;';
            }
            return $header_css;
        }


        /**
         * Get dropdown menu
         * @return string
         * @see v1.0
         */
        public static function irantheme_dropdown_main_menu($page_id = null)
        {
            $dropdown_nav_menu_id = irantheme_get_header_option('irantheme_dropdown_nav_menu_select', $page_id);
            if ($dropdown_nav_menu_id == 'none') {
                return;
            }
            wp_nav_menu(array(
                'menu' => $dropdown_nav_menu_id,
                'menu_class' => 'irantheme-navbar-dropdown-menu-ul',
                'container_class' => 'irantheme-navbar-dropdown-menu',
                // 'depth' => 4,
                'fallback_cb' => false,
                'theme_location' => 'iranthemeDropdownMainMenu'
            ));
        }


        /**
         * Get navbar secondary menu
         * @return void
         * @param void
         * @see v1.0
         */
        public static function irantheme_navbar_secondary_menu(int $depth = 4, $page_id = null)
        {
            $header_secondary_menu_id = irantheme_get_header_option('irantheme_header_secondary_menu_select', $page_id);
            if ($header_secondary_menu_id == 'none') {
                return;
            }
            wp_nav_menu(array(
                'menu' => $header_secondary_menu_id,
                'menu_class' => 'irantheme-navbar-menu-ul',
                'container_class' => 'irantheme-container-navbar-menu irantheme-secondary-navbar-menu',
                'depth' => $depth,
                'fallback_cb' => false,
                'theme_location' => 'iranthemeNavbarSecondaryMenu'
            ));
        }


        /**
         * Get navbar main menu
         * @return void
         * @param void
         * @see v1.0
         */
        public static function irantheme_navbar_main_menu($page_id = null)
        {
            $header_main_menu_id = irantheme_get_header_option('irantheme_header_main_menu_select', $page_id);
            if ($header_main_menu_id == 'none') {
                return;
            }
            wp_nav_menu(array(
                'menu' => $header_main_menu_id,
                'menu_class' => 'irantheme-navbar-menu-ul',
                'container_class' => 'irantheme-container-navbar-menu irantheme-primary-navbar-menu',
                'depth' => 4,
                'fallback_cb' => false,
                'theme_location' => 'iranthemeNavbarMainMenu'
            ));
        }


        /**
         * Return header sticky class
         * @param void
         * @return string
         * @see v1.0
         */
        public static function irantheme_display_mini_profile($size = 'large', $position = 'bottom', $color = 'white', $position_dropdown = 'center', $element_hidden = false, $profile_name = true, $profile_email = true, $profile_ellipsis = true, $classes = '', $page_id = null)
        {
            // Set mini profile width
            if ($size == 'large') {
                $profile_width = '300px;';
            } else if ($size == 'medium') {
                $profile_width = '250px;';
            } else if ($size == 'small') {
                $profile_width = '200px;';
            }

            // Set mini profile position
            if ($position == 'top') {
                $profile_position = 'top:100%;';
            } else if ($position == 'bottom') {
                $profile_position = 'bottom:100%;';
            }

            // Set mini profile color
            if ($color == 'white') {
                $profile_color = 'irantheme-mini-profile-white';
            } else if ($color == 'dark') {
                $profile_color = 'irantheme-mini-profile-dark';
            }

            // Set mini profile position dropdown (Default css is center)
            if ($position_dropdown == 'right') {
                $dropdown_position = 'right:0;';
            } else if ($position_dropdown == 'left') {
                $dropdown_position = 'left:0;';
            } else if ($position_dropdown == 'center') {
                $dropdown_position = 'left:50%;transform:translateX(-50%);';
            }
            ?>
            <div class="irantheme-mini-profile <?php echo esc_attr($profile_color); ?> <?php echo esc_attr($classes); ?>">
                <?php
                if (is_user_logged_in()) {
                    // Get user id
                    $user_id = get_current_user_id();
                    // Current user
                    $current_user = get_user_by('id', $user_id);
                ?>
                    <!-- Button toggle -->
                    <button class="irantheme-btn-void irantheme-mini-profile-holder irantheme-mini-profile-toggle bg-dark-trans">
                        <!-- User avatar -->
                        <div class="irantheme-mini-profile-holder-avatar">
                            <i class="iconsax" icon-name="user-2-circle"></i>
                            <span class="irantheme-mini-profile-holder-avatar-status <?php echo esc_attr(Irantheme_Color_Static::get_class_color_mode('bg', 'primary')) ?>"></span>
                        </div>
                        <?php if ($profile_name || $profile_email) : ?>
                            <!-- User info -->
                            <div class="irantheme-mini-profile-holder-info <?php echo esc_attr($element_hidden ? 'irantheme-element-mobile-hidden' : ''); ?>">
                                <?php if ($profile_name) : ?>
                                    <strong class="irantheme-mini-profile-holder-info-heading"><?php echo esc_html($current_user->display_name); ?></strong>
                                <?php endif; ?>
                                <?php if ($profile_email) : ?>
                                    <span class="irantheme-mini-profile-holder-info-details"><?php echo esc_html($current_user->user_email); ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <?php if ($profile_ellipsis) : ?>
                            <!-- Arrow dropdown menu -->
                            <span class="irantheme-mini-profile-holder-arrow <?php echo esc_attr($element_hidden ? 'irantheme-element-mobile-hidden' : ''); ?>"><i class="iconsax" icon-name="chevron-down"></i></span>
                        <?php endif; ?>
                    </button>
                    <!-- Dropdown menu -->
                    <div class="irantheme-mini-profile-dropdown" style="width:<?php echo esc_attr($profile_width); ?><?php echo esc_attr($profile_position); ?><?php echo esc_attr($dropdown_position); ?>">
                        <!-- Short profile user -->
                        <div class="irantheme-mini-profile-dropdown-holder">
                            <!-- Avatar -->
                            <div class="irantheme-mini-profile-dropdown-holder-avatar">
                                <span class="irantheme-mini-profile-dropdown-holder-avatar-holder">
                                    <i class="iconsax" icon-name="user-2-circle"></i>
                                    <span class="irantheme-mini-profile-dropdown-holder-avatar-holder-status <?php echo esc_attr(Irantheme_Color_Static::get_class_color_mode('bg', 'primary')) ?>"></span>
                                </span>
                            </div>
                            <!-- Name -->
                            <div class="irantheme-mini-profile-dropdown-holder-info">
                                <strong class="irantheme-mini-profile-dropdown-holder-info-name"><?php echo esc_html($current_user->display_name); ?></strong>
                                <span class="irantheme-mini-profile-dropdown-holder-info-role"><?php _e('رده کاربری‌ : ') . _e(irantheme_get_user_role($user_id)); ?></span>
                            </div>
                        </div>
                        <!-- User short menu -->
                        <div class="irantheme-mini-profile-dropdown-holder-menu">
                            <!-- Menu list -->
                            <ul class="irantheme-mini-profile-dropdown-holder-menu-list">
                                <?php if (is_woocommerce_activated()) {
                                ?>
                                    <!-- Menu item -->
                                    <li class="irantheme-mini-profile-dropdown-holder-menu-list-item irantheme-menu-item-separator">
                                        <a href="<?php echo irantheme_get_woocommerce_get_cart_url(); ?>" class="irantheme-mini-profile-dropdown-holder-menu-list-item-link <?php echo esc_attr(Irantheme_Color_Static::get_class_color_mode('color-hover', 'primary')) ?>">
                                            <i class="iconsax" icon-name="shopping-cart"></i>
                                            <div class="irantheme-mini-profile-dropdown-holder-menu-cart">
                                                <!-- Price -->
                                                <div class="irantheme-mini-profile-dropdown-holder-menu-cart-cell">
                                                    <div class="irantheme-mini-profile-dropdown-holder-menu-cart-cell-title">
                                                        <?php _e('سبد خرید'); ?>
                                                    </div>
                                                    <span class="irantheme-mini-profile-dropdown-holder-menu-cart-cell-price">
                                                        <?php echo WC()->cart->get_cart_total(); ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                <?php
                                } ?>
                                <!-- Menu item -->
                                <?php
                                // If woocommerce is activated add dashboard wc to dashboard_url
                                if (is_woocommerce_activated()) {
                                    $dashboard_url = wc_get_account_endpoint_url('dashboard');
                                } else {
                                    // Get wordpress dashboard url
                                    $dashboard_url = get_edit_profile_url($user_id);
                                }
                                ?>
                                <li class="irantheme-mini-profile-dropdown-holder-menu-list-item">
                                    <a href="<?php echo esc_url($dashboard_url); ?>" class="irantheme-mini-profile-dropdown-holder-menu-list-item-link <?php echo esc_attr(Irantheme_Color_Static::get_class_color_mode('color-hover', 'primary')) ?>"><i class="iconsax" icon-name="person-card"></i><?php _e('پروفایل'); ?></a>
                                </li>

                                <?php
                                // If woocommerce is activated
                                if (is_woocommerce_activated()) :
                                ?>
                                    <!-- Menu item -->
                                    <li class="irantheme-mini-profile-dropdown-holder-menu-list-item">
                                        <a href="<?php echo esc_url(wc_get_account_endpoint_url('downloads')); ?>" class="irantheme-mini-profile-dropdown-holder-menu-list-item-link <?php echo esc_attr(Irantheme_Color_Static::get_class_color_mode('color-hover', 'primary')) ?>">
                                            <i class="iconsax" icon-name="download-2"></i>
                                            <?php _e('دانلود ها'); ?>
                                        </a>
                                    </li>
                                    <?php
                                    $tickets_support_checkbox = get_option('irantheme_wc_tickets_checkbox');
                                    if ($tickets_support_checkbox == 'on'):
                                    ?>
                                        <!-- Menu item -->
                                        <li class="irantheme-mini-profile-dropdown-holder-menu-list-item">
                                            <a href="<?php echo esc_url(wc_get_account_endpoint_url('support-tickets')); ?>" class="irantheme-mini-profile-dropdown-holder-menu-list-item-link <?php echo esc_attr(Irantheme_Color_Static::get_class_color_mode('color-hover', 'primary')) ?>"><i class="iconsax" icon-name="ticket-1"></i><?php _e('تیکت ها'); ?> (<?php echo Irantheme_Woocommerce_Support_Ticket::count_unread_tickets(); ?>)</a>
                                        </li>
                                    <?php endif; ?>
                                    <!-- Menu item -->
                                    <li class="irantheme-mini-profile-dropdown-holder-menu-list-item irantheme-menu-item-separator">
                                        <a href="<?php echo esc_url(wc_get_account_endpoint_url('edit-account')); ?>" class="irantheme-mini-profile-dropdown-holder-menu-list-item-link <?php echo esc_attr(Irantheme_Color_Static::get_class_color_mode('color-hover', 'primary')) ?>"><i class="iconsax" icon-name="setting-3"></i><?php _e('ویرایش حساب'); ?></a>
                                    </li>
                                <?php endif; ?>
                                <!-- Menu item -->
                                <?php
                                // Assign default wp logout url
                                $logout_url = wp_logout_url();
                                // Check is woocommerce activated
                                if (is_woocommerce_activated()) {
                                    $logout_url = wc_logout_url();
                                }
                                ?>
                                <li class="irantheme-mini-profile-dropdown-holder-menu-list-item">
                                    <a href="<?php echo esc_url($logout_url); ?>" class="irantheme-mini-profile-dropdown-holder-menu-list-item-link <?php echo esc_attr(Irantheme_Color_Static::get_class_color_mode('color-hover', 'primary')) ?>"><i class="iconsax" icon-name="logout-2"></i><?php _e('خروج'); ?></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                <?php

                } else {
                ?>
                    <?php
                    $login_url = null;

                    // Check if WooCommerce is activated
                    if (is_woocommerce_activated()) {
                        // Get the myaccount page ID
                        $myaccount_page_id = get_option('woocommerce_myaccount_page_id');

                        // Check if the page exists and is published
                        if ($myaccount_page_id && 'publish' === get_post_status($myaccount_page_id)) {
                            // Get woocommerce url login
                            $login_url = wc_get_page_permalink('myaccount');
                        } else {
                            // WooCommerce is active but myaccount page is missing/unpublished
                            // Fallback to WordPress login with current URL
                            $current_url = home_url($_SERVER['REQUEST_URI']);
                            $login_url = wp_login_url($current_url);
                        }
                    } else {
                        // WooCommerce not activated - use WordPress login
                        $current_url = home_url($_SERVER['REQUEST_URI']);
                        $login_url = wp_login_url($current_url);
                    }
                    ?>
                    <!-- Button toggle -->
                    <a href="<?php echo esc_url($login_url); ?>" class="irantheme-btn-void irantheme-mini-profile-holder not-logged-in bg-dark-trans">
                        <div class="irantheme-mini-profile-holder-avatar">
                            <i class="iconsax" icon-name="login"></i>
                        </div>
                        <!-- User info -->
                        <div class="irantheme-mini-profile-holder-info">
                            <strong class="irantheme-mini-profile-holder-info-heading">
                                <?php _e('ورود', IRANTHEME_TEXTDOMAIN); ?><span>/</span><?php _e('ثبت نام', IRANTHEME_TEXTDOMAIN); ?>
                            </strong>
                        </div>
                    </a>
                <?php
                }
                ?>
            </div>
            <?php
        }


        /**
         * Return login url for users (Contains woocommerce login)
         * @param void
         * @return string
         * @see v1.0
         */
        public static function irantheme_get_login_url_user_account()
        {
            if (is_user_logged_in()) {
                // Get user id
                $user_id = get_current_user_id();
                $login_url = get_dashboard_url($user_id);
                // If woocommerce is activated add dashboard wc to dashboard_url
                if (is_woocommerce_activated()) {
                    $login_url = wc_get_account_endpoint_url('dashboard');
                }
            } else {
                $login_url = null;
                // Is woocommerce activated
                if (is_woocommerce_activated()) {
                    // Get woocommerce url login
                    $login_url = wc_get_page_permalink('myaccount');
                } else {
                    // Get wp url login for return to current url
                    $current_url = home_url(add_query_arg([], $GLOBALS['wp']->request));
                    $login_url = wp_login_url($current_url);
                }
            }
            return $login_url;
        }


        /**
         * Get navbar topbar
         * @param int
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function navbar_topbar($page_id = null)
        {
            // Get option for check & assign header custom value
            $topbar_option_name = irantheme_get_header_option('irantheme_topbar_checkbox', $page_id);
            if ($topbar_option_name == 'on') :
            ?>
                <!-- Top bar -->
                <div class="irantheme-header-topbar <?php echo esc_attr(self::irantheme_get_topbar_base_color_class($page_id)); ?> <?php echo esc_attr(self::irantheme_get_topbar_transparent_class_option($page_id)); ?> <?php echo esc_attr(self::irantheme_get_header_class_fixed($page_id)); ?>" style="<?php echo esc_attr(self::irantheme_get_topbar_bg_color($page_id)); ?><?php echo self::irantheme_get_topbar_blur_css_option(); ?>">

                    <div class="irantheme-navbar-topbar-alignment <?php echo esc_attr(self::irantheme_get_header_class_container($page_id)); ?> <?php echo esc_attr(self::irantheme_get_topbar_class_alignment($page_id)); ?> <?php echo esc_attr(self::irantheme_get_topbar_border_bottom_class($page_id)); ?>" style="<?php echo esc_attr(self::irantheme_get_topbar_height_css($page_id)); ?> <?php echo esc_attr(self::irantheme_get_topbar_font_size_css($page_id)); ?>">
                        <?php
                        if (self::irantheme_is_topbar_menus_checkbox($page_id)) {
                            // Secondary menu
                            self::irantheme_topbar_menu(2, $page_id);
                        }
                        ?>

                        <?php if (self::irantheme_is_topbar_note_checkbox($page_id)) : ?>
                            <div class="irantheme-cell-note">
                                <!-- Note one -->
                                <div class="irantheme-topbar-note" style="<?php echo esc_attr(self::irantheme_get_topbar_font_size_css($page_id)); ?>">
                                    <?php if (self::irantheme_get_topbar_first_note_icon($page_id)) : ?>
                                        <i class="<?php echo esc_attr(self::irantheme_get_topbar_first_note_icon($page_id)); ?>"></i>
                                    <?php endif; ?>
                                    <?php if (self::irantheme_get_topbar_first_note($page_id)) : ?>
                                        <p><?php _e(self::irantheme_get_topbar_first_note($page_id)); ?></p>
                                    <?php endif; ?>
                                </div>

                                <!-- Note Two -->
                                <div class="irantheme-topbar-note" style="<?php echo esc_attr(self::irantheme_get_topbar_font_size_css($page_id)); ?>">
                                    <?php if (self::irantheme_get_topbar_second_note_icon($page_id)) : ?>
                                        <i class="<?php echo esc_attr(self::irantheme_get_topbar_second_note_icon($page_id)); ?>"></i>
                                    <?php endif; ?>
                                    <?php if (self::irantheme_get_topbar_second_note($page_id)) : ?>
                                        <p><?php _e(self::irantheme_get_topbar_second_note($page_id)); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php
                        if (self::irantheme_is_topbar_social_media_checkbox($page_id)) {
                            // Get social media
                            Irantheme_Common_Static::get_social_media('', '', self::irantheme_get_topbar_font_size_svg_css($page_id));
                        }
                        ?>
                    </div>
                </div>
            <?php endif;
        }


        /**
         * Get navbar fullscreen
         * @param int
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function navbar_fullscreen($page_id = null)
        {
            ?>
            <!-- Navbar full screen -->
            <div class="irantheme-navbar-full-screen <?php echo esc_attr(self::irantheme_get_fullscreen_bg_color_class($page_id)); ?>">

                <!-- Close nav -->
                <button class="irantheme-navbar-full-screen-close irantheme-btn-void"><i class="iconsax" icon-name="x"></i></button>

                <!-- Holder elements -->
                <div class="irantheme-navbar-full-screen-holder">

                    <?php if (irantheme_get_header_option('irantheme_fullscreen_nav_menu_accessories_checkbox', $page_id) == 'on') : ?>
                        <!-- Options -->
                        <div class="irantheme-navbar-full-screen-options">
                            <ul class="irantheme-navbar-full-screen-options-ul">
                                <li class="irantheme-navbar-full-screen-options-ul-item">
                                    <button class="irantheme-btn-void irantheme-search-box-toggle irantheme-navbar-full-screen-options-ul-item-link">
                                        <i class="iconsax" icon-name="search-normal-2"></i>
                                    </button>
                                </li>
                                <li class="irantheme-navbar-full-screen-options-ul-item">
                                    <button class="irantheme-btn-void irantheme-woocommerce-wishlist-navigation-open irantheme-navbar-full-screen-options-ul-item-link">
                                        <i class="iconsax" icon-name="heart"></i>
                                        <?php
                                        // Get wishlist count
                                        irantheme_woocommerce_wishlist_count();
                                        ?>
                                    </button>
                                </li>
                                <li class="irantheme-navbar-full-screen-options-ul-item">
                                    <button class="irantheme-btn-void irantheme-woocommerce-cart-navigation-open irantheme-navbar-full-screen-options-ul-item-link">
                                        <i class="iconsax" icon-name="basket-1"></i>
                                        <?php
                                        // Get shopping number cart count
                                        irantheme_woocommerce_shopping_cart_count();
                                        ?>
                                    </button>
                                </li>

                                <!-- Login url -->
                                <li class="irantheme-navbar-full-screen-options-ul-item">
                                    <a href="<?php echo esc_url(self::irantheme_get_login_url_user_account($page_id)); ?>" class="irantheme-navbar-full-screen-options-ul-item-link">
                                        <i class="iconsax" icon-name="user-2"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <!-- Menu -->
                    <?php
                    if (irantheme_get_header_option('irantheme_fullscreen_nav_menu_menus_checkbox', $page_id) == 'on') {
                        self::irantheme_full_screen_main_menu($page_id);
                    }

                    // Check social media
                    if (irantheme_get_header_option('irantheme_fullscreen_nav_menu_social_media_checkbox', $page_id) == 'on') {
                        // Get social media
                        Irantheme_Common_Static::get_social_media();
                    }
                    ?>

                </div>
            </div>
        <?php
        }


        /**
         * Get navbar search
         * @param int
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function navbar_search_box($page_id = null)
        {
        ?>
            <!-- Search box -->
            <div id="irantheme-search-box">
                <!-- Alignment & container -->
                <div class="irantheme-search-box-alignment">
                    <div class="<?php echo esc_attr(self::irantheme_get_search_box_class_container($page_id)); ?>">
                        <?php self::navbar_search_box_search_form($page_id); ?>
                    </div>
                </div>
            </div>
        <?php
        }


        /**
         * Get navbar search form
         * @param int
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function navbar_search_box_search_form($page_id = null, $display_close_button = true, $display_icon_options = false)
        {
        ?>
            <?php
            $ajax_checkbox = irantheme_get_header_option('irantheme_ajax_search_checkbox', $page_id);
            // Get suggestion display checkbox
            $suggestion_checkbox = irantheme_get_header_option('irantheme_search_suggestions_checkbox', $page_id);
            ?>
            <form class="irantheme-search-form" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" data-ajax="<?php echo $ajax_checkbox ?>">
                <!-- Search icon & spinner -->
                <span class="irantheme-search-case irantheme-search-case-alt">
                    <i id="irantheme-search-alt" class="iconsax" icon-name="search-normal-2"></i>
                    <i id="irantheme-spinner-alt" class="irantheme-animate-rotate"></i>
                </span>
                <!-- Search input -->
                <input type="text" class="irantheme-search-input" value="<?php echo get_search_query(); ?>" autocomplete="off" name="s" id="s" placeholder="<?php _e('به دنبال چیزی هستید؟'); ?>">
                <?php if ($display_close_button == true): ?>
                    <!-- Search close -->
                    <span class="irantheme-search-case" id="irantheme-search-box-close" data-type="close" data-search="0"><i class="iconsax" icon-name="x"></i></span>
                <?php endif; ?>
                <?php if ($display_icon_options == true && $ajax_checkbox == 'on'): ?>
                    <!-- Search close -->
                    <span class="irantheme-search-case"><i class="iconsax" icon-name="document-filter"></i></span>
                <?php endif; ?>
                <?php if ($ajax_checkbox == 'on') : ?>
                    <!-- Search results -->
                    <div class="irantheme-search-results">
                        <?php
                        // Check suggestions checkbox is on for show
                        if (is_woocommerce_activated() && 'on' == $suggestion_checkbox) :
                        ?>
                            <div class="irantheme-search-results-prev">
                                <!-- Search categories -->
                                <div class="irantheme-search-categories">
                                    <!-- Label -->
                                    <span class="irantheme-search-results-label"><i class="iconsax <?php echo esc_attr(Irantheme_Color_Static::get_class_color_mode('color', 'secondary')) ?>" icon-name="grid-apps-2"></i><?php _e('جستجو در دسته بندی‌ : '); ?></span>
                                    <div class="irantheme-search-categories-holder">
                                        <!-- Active all categories (Default) -->
                                        <button class="irantheme-search-category-item active" data-cate="<?php echo esc_attr(-1); ?>">
                                            <i class="iconsax" icon-name="hierarchy-1"></i>
                                            <?php _e('همه دسته بندی ها'); ?>
                                        </button>
                                        <?php if (class_exists('WooCommerce')) : ?>
                                            <?php
                                            // Products categories
                                            $categories_product = get_terms(array(
                                                'taxonomy' => 'product_cat',
                                                'hide_empty' => true,
                                                // 'parent' => 0,
                                            ));
                                            ?>
                                            <?php
                                            // Apply categories loop
                                            foreach ($categories_product as $category) : ?>
                                                <!-- Button category -->
                                                <button class="irantheme-search-category-item" data-cate="<?php echo esc_attr($category->term_id); ?>">
                                                    <i class="iconsax" icon-name="folder-1"></i>
                                                    <?php echo esc_html($category->name); ?>
                                                </button>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <!-- Suggestions -->
                                <div class="irantheme-search-suggestions">
                                    <!-- Label -->
                                    <span class="irantheme-search-results-label"><i class="iconsax <?php echo esc_attr(Irantheme_Color_Static::get_class_color_mode('color', 'secondary')) ?>" icon-name="star-speed"></i><?php _e('جستجو های پر طرفدار : '); ?></span>
                                    <!-- Categories suggestions -->
                                    <div class="irantheme-search-suggestions-categories">
                                        <?php
                                        $top_categories = Irantheme_Ajax_Search_Box::get_top_categories(5, false, true);
                                        if ($top_categories) {
                                            foreach ($top_categories as $category) {
                                        ?>
                                                <a class="irantheme-search-suggestions-categories-item" href="<?php echo esc_url($category['url']); ?>"><i class="iconsax <?php echo esc_attr(Irantheme_Color_Static::get_class_color_mode('color', 'primary')) ?>" icon-name="hashtag"></i><?php echo esc_html($category['name']); ?></a><br>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                    <div class="irantheme-search-suggestions-banner">
                                        <?php
                                        // Banner search suggestions
                                        ?>
                                        <?php
                                        $search_suggestions_banner_id = get_option('irantheme_search_suggestions_image');
                                        echo wp_get_attachment_image(
                                            $search_suggestions_banner_id,
                                            'medium_large',
                                            false,
                                            [
                                                'class' => 'irantheme-search-suggestions-banner-img',
                                                'alt' => __('بنر جستجو', IRANTHEME_TEXTDOMAIN),
                                                'loading' => 'lazy',
                                            ]
                                        ); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <!-- Holder of results -->
                        <div class="irantheme-search-results-holder">
                            <div class="irantheme-search-results-products"></div>
                            <div class="irantheme-search-results-posts"></div>
                        </div>
                        <div class="irantheme-search-results-not-found" style="display:none">
                            <i class="iconsax <?php echo esc_attr(Irantheme_Color_Static::get_class_color_mode('color-fill', 'primary')) ?> <?php echo esc_attr(Irantheme_Color_Static::get_class_color_mode('bg-glass', 'primary')) ?>" icon-name="search-normal-2"></i>
                            <h3 class="irantheme-search-results-not-found-title">نتیجه ای یافت نشد <?php _e(':('); ?></h3>
                            <p class="irantheme-search-results-not-found-desc">نتیجه ای برای جستجوی شما یافت نشد!
                                </br>
                                لطفا کلمه ی دیگری را جستجو و یا فیلتر های دیگر را امتحان کنید
                            </p>
                        </div>
                    </div>
                <?php endif; ?>
            </form>
        <?php
        }


        /**
         * Get navbar dropdown
         * @param int
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function navbar_dropdown($page_id = null)
        {
        ?>
            <!-- Navbar dropdown (menu) -->
            <div class="irantheme-navbar-dropdown">

                <?php if (irantheme_get_header_option('irantheme_dropdown_nav_menu_accessories_checkbox', $page_id) == 'on') : ?>
                    <!-- Accessories -->
                    <div class="irantheme-navbar-dropdown-accessories">
                        <ul class="irantheme-navbar-dropdown-accessories-ul">
                            <li class="irantheme-navbar-dropdown-accessories-ul-item irantheme-search-box-toggle">
                                <button class="irantheme-btn-void irantheme-navbar-dropdown-accessories-ul-item-link">
                                    <i class=" iconsax" icon-name="search-normal-2"></i>
                                </button>
                            </li>
                            <li class="irantheme-navbar-dropdown-accessories-ul-item">
                                <button class="irantheme-btn-void irantheme-navbar-dropdown-accessories-ul-item-link irantheme-woocommerce-wishlist-navigation-open">
                                    <i class="iconsax" icon-name="heart"></i>
                                    <?php
                                    // Get wishlist count
                                    irantheme_woocommerce_wishlist_count();
                                    ?>
                                </button>
                            </li>
                            <li class="irantheme-navbar-dropdown-accessories-ul-item">
                                <button class="irantheme-btn-void irantheme-navbar-dropdown-accessories-ul-item-link irantheme-woocommerce-cart-navigation-open">
                                    <i class="iconsax" icon-name="basket-1"></i>
                                    <?php
                                    // Get shopping number cart count
                                    irantheme_woocommerce_shopping_cart_count();
                                    ?>
                                </button>
                            </li>

                            <!-- Login url -->
                            <li class="irantheme-navbar-dropdown-accessories-ul-item">
                                <a href="<?php echo esc_url(self::irantheme_get_login_url_user_account($page_id)); ?>" class="irantheme-navbar-dropdown-accessories-ul-item-link">
                                    <i class="iconsax" icon-name="user-2"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="irantheme-navbar-dropdown-alignment <?php echo esc_attr(self::irantheme_get_header_class_container($page_id)); ?>">
                    <?php
                    // Check navbar dropdown
                    if (irantheme_get_header_option('irantheme_dropdown_nav_menu_menus_checkbox', $page_id) == 'on') {
                        self::irantheme_dropdown_main_menu($page_id);
                    }

                    // Check social media
                    if (irantheme_get_header_option('irantheme_dropdown_nav_menu_social_media_checkbox', $page_id) == 'on') {
                        // Get social media
                        Irantheme_Common_Static::get_social_media();
                    }
                    ?>
                </div>
            </div>
        <?php
        }


        /**
         * Get navbar navigation
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function navbar_navigation($page_id = null)
        {
        ?>
            <!-- Navigation -->
            <nav class="irantheme-navigation <?php echo esc_attr(self::irantheme_get_navigation_bg_color_class($page_id)); ?> <?php echo esc_attr(self::irantheme_get_navigation_class($page_id)); ?>" <?php echo esc_attr(Irantheme_Page_Builder::get_attr_data_header($page_id)); ?> style="width:<?php echo esc_attr(self::irantheme_get_navigation_width($page_id)); ?>px;<?php echo esc_attr(self::irantheme_get_navigation_transform_css($page_id)); ?><?php echo esc_attr(self::irantheme_get_navigation_bg_image_css($page_id)); ?><?php echo esc_attr(self::irantheme_get_navigation_position_css($page_id)); ?>" data-position="<?php echo esc_attr(self::irantheme_get_navigation_position_direction($page_id)); ?>" data-width="<?php echo esc_attr(self::irantheme_get_navigation_width($page_id)); ?>">

                <!-- Navigation mask white -->
                <div class="irantheme-navigation-mask <?php echo esc_attr(self::irantheme_get_navigation_mask_class($page_id)); ?>">
                    <!-- Brand -->
                    <div class="irantheme-navigation-brand">
                        <!-- Logo -->
                        <?php
                        // Check navigation logo checkbox for show
                        if (irantheme_get_header_option('irantheme_navigation_logo_checkbox', $page_id) == 'on') {
                            echo self::irantheme_get_logo(self::irantheme_get_navigation_logo_color($page_id), irantheme_get_header_option('irantheme_navigation_logo_height_number'), $page_id);
                        }
                        ?>
                        <!-- Close -->
                        <div class="irantheme-navigation-close" data-position="<?php echo esc_attr(irantheme_get_header_option('irantheme_header_alignment_radio', $page_id)); ?>">
                            <button id="irantheme-navigation-close" class="irantheme-btn-void irantheme-navigation-close-button"><i class="iconsax" icon-name="x"></i></button>
                        </div>
                    </div>

                    <?php
                    // Check navigation search box checkbox for show
                    if (irantheme_get_header_option('irantheme_navigation_search_checkbox', $page_id) == 'on') : ?>
                        <!-- Search form -->
                        <div class="irantheme-navigation-search">
                            <?php get_search_form(); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (irantheme_get_header_option('irantheme_navigation_accessories_checkbox', $page_id) == 'on') : ?>
                        <!-- Options -->
                        <div class="irantheme-navigation-options">
                            <ul class="irantheme-navigation-options-ul">
                                <li class="irantheme-navigation-options-ul-item">
                                    <a href="#" class="irantheme-woocommerce-wishlist-navigation-open">
                                        <i class="iconsax" icon-name="heart"></i>
                                        <?php
                                        // Get wishlist count
                                        irantheme_woocommerce_wishlist_count();
                                        ?>
                                    </a>
                                </li>
                                <li class="irantheme-navigation-options-ul-item">
                                    <a href="<?php echo irantheme_get_woocommerce_get_cart_url(); ?>">
                                        <i class="iconsax" icon-name="basket-1"></i>
                                        <?php
                                        // Get shopping number cart count
                                        irantheme_woocommerce_shopping_cart_count();
                                        ?>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php
                    // Get navigation biography avatar
                    $navigation_biography_avatar = irantheme_get_header_option('irantheme_navigation_biography_avatar', $page_id);
                    // Get navigation biography title
                    $navigation_biography_title = irantheme_get_header_option('irantheme_navigation_biography_title', $page_id);
                    // Get navigation biography text
                    $navigation_biography_textarea = irantheme_get_header_option('irantheme_navigation_biography_textarea', $page_id);

                    // Check navigation biography section
                    if (irantheme_get_header_option('irantheme_navigation_biography_checkbox', $page_id) == 'on') :
                    ?>
                        <div class="irantheme-navigation-biography">
                            <?php
                            if ($navigation_biography_avatar) :
                                // Get navigation biography avatar
                                $navigation_biography_avatar_url = wp_get_attachment_image_src(irantheme_get_header_option('irantheme_navigation_biography_avatar', $page_id), 'full')[0];
                            ?>
                                <div class="irantheme-navigation-biography-avatar">
                                    <img src="<?php echo esc_attr($navigation_biography_avatar_url); ?>" alt="آواتار پروفایل">
                                </div>
                            <?php
                            endif;
                            ?>
                            <?php
                            if ($navigation_biography_title) :
                            ?>
                                <div class="irantheme-navigation-biography-title">
                                    <h3><?php echo esc_html($navigation_biography_title); ?></h3>
                                </div>
                            <?php
                            endif;
                            ?>
                            <?php
                            if ($navigation_biography_textarea) :
                            ?>
                                <div class="irantheme-navigation-biography-text">
                                    <p><?php echo esc_html($navigation_biography_textarea); ?></p>
                                </div>
                            <?php
                            endif;
                            ?>
                        </div>
                    <?php
                    endif;
                    ?>

                    <!-- Menu -->
                    <?php
                    // Check navigation menu checkbox for show
                    if (irantheme_get_header_option('irantheme_navigation_menu_checkbox', $page_id) == 'on') {
                        self::irantheme_navigation_main_menu($page_id);
                    }
                    ?>

                    <div class="irantheme-navigation-bottom-position">
                        <?php
                        if (irantheme_get_header_option('irantheme_navigation_social_media_checkbox', $page_id) == 'on') {
                            // Get social media
                            Irantheme_Common_Static::get_social_media();
                        }
                        // Check navigation social checkbox for show
                        if (irantheme_get_header_option('irantheme_navigation_profile_checkbox', $page_id) == 'on') {
                            self::irantheme_display_mini_profile('medium', 'bottom', self::irantheme_get_navigation_bg_color($page_id), 'center', false, true, true, true, '', $page_id);
                        }

                        ?>
                    </div>

                </div>

                <?php
                // Get page builder hidden require
                Irantheme_Page_Builder::get_hidden_require_header_buttons($page_id);
                ?>
            </nav>
        <?php
        }


        /**
         * Return fullscreen bg color class
         * @param void
         * @return string
         * @see v1.0
         */
        public static function irantheme_get_fullscreen_bg_color_class($page_id = null)
        {
            // Get header bg color for using bg full screen
            $header_bg = irantheme_get_header_option('irantheme_fullscreen_bg_color_select', $page_id);

            if ($header_bg == 'white') {
                $full_screen_bg_class = 'irantheme-navbar-full-screen-white';
            } else if ($header_bg == 'dark') {
                $full_screen_bg_class = 'irantheme-navbar-full-screen-dark';
            }
            return $full_screen_bg_class;
        }


        /**
         * Get full screen menu
         * @param void
         * @return string
         * @see v1.0
         */
        public static function irantheme_full_screen_main_menu($page_id = null)
        {
            $fullscreen_nav_menu_id = irantheme_get_header_option('irantheme_fullscreen_nav_menu_select', $page_id);
            if ($fullscreen_nav_menu_id == 'none') {
                return;
            }
            wp_nav_menu(array(
                'menu' => $fullscreen_nav_menu_id,
                'menu_class' => 'irantheme-navbar-full-screen-menu-ul',
                'container_class' => 'irantheme-navbar-full-screen-menu',
                // 'depth' => 4,
                'fallback_cb' => false,
                'theme_location' => 'iranthemeFullScreenMainMenu'
            ));
        }


        /**
         * Return navigation position
         * @param void
         * @return string
         * @see v1.0
         */
        public static function irantheme_get_navigation_position_direction($page_id = null)
        {
            // Get navigation position
            $navigation_position = irantheme_get_header_option('irantheme_navigation_position', $page_id);
            return $navigation_position;
        }


        /**
         * Return navigation background color
         * @param void
         * @return string
         * @see v1.0
         */
        public static function irantheme_get_navigation_bg_color($page_id = null)
        {
            // Get navigation background color
            $navigation_bg_color = irantheme_get_header_option('irantheme_navigation_bg_color_select', $page_id);
            return $navigation_bg_color;
        }


        /**
         * Return navigation width size
         * @param void
         * @return string
         * @see v1.0
         */
        public static function irantheme_get_navigation_width($page_id = null)
        {
            // Get navigation width
            $navigation_width = irantheme_get_header_option('irantheme_navigation_width', $page_id);
            return $navigation_width;
        }


        /**
         * Return logo navigation color
         * @param void
         * @return string
         * @see v1.0
         */
        public static function irantheme_get_navigation_logo_color($page_id = null)
        {
            if (self::irantheme_get_navigation_bg_color($page_id) == 'dark') {
                $logo_force_color = 'white';
            } else if (self::irantheme_get_navigation_bg_color($page_id) == 'white') {
                $logo_force_color = 'dark';
            }
            return $logo_force_color;
        }


        /**
         * Return navigation bg image array
         * @param void
         * @return array
         * @see v1.0
         */
        public static function irantheme_get_navigation_bg_image($page_id = null)
        {
            // Get navigation bg image
            $navigation_bg_image = irantheme_get_header_option('irantheme_navigation_bg_image', $page_id);
            return $navigation_bg_image;
        }


        /**
         * Return navigation classes
         * @param void
         * @return string
         * @see v1.0
         */
        public static function irantheme_get_navigation_class($page_id = null)
        {
            if (self::irantheme_get_navigation_position_direction($page_id) == 'right') {
                $navigation_class = 'irantheme-navigation-right';
            } else if (self::irantheme_get_navigation_position_direction($page_id) == 'left') {
                $navigation_class = 'irantheme-navigation-left';
            }
            return $navigation_class;
        }


        /**
         * Return navigation mask classes
         * @param void
         * @return string
         * @see v1.0
         */
        public static function irantheme_get_navigation_mask_class($page_id = null)
        {
            $navigation_mask_class = '';
            // Check value navigation bg image
            if (self::irantheme_get_navigation_bg_image($page_id)) {
                // Check navigation bg color
                if (self::irantheme_get_navigation_bg_color($page_id) == 'white') {
                    $navigation_mask_class = 'irantheme-navigation-mask-white';
                } else if (self::irantheme_get_navigation_bg_color($page_id) == 'dark') {
                    $navigation_mask_class = 'irantheme-navigation-mask-dark';
                }
            }
            return $navigation_mask_class;
        }


        /**
         * Return navigation position class
         * @param string
         * @return string
         * @see v1.0
         */
        public static function irantheme_get_navigation_position_css($page_id = null)
        {
            $navigation_position_css = '';
            // Get navigation position
            $navigation_position = irantheme_get_header_option('irantheme_navigation_position', $page_id);
            // Check position side of navigation
            if ($navigation_position == 'left') {
                $navigation_position_css = 'left:0;';
            } elseif ($navigation_position == 'right') {
                $navigation_position_css = 'right:0;';
            }
            return $navigation_position_css;
        }


        /**
         * Return navigation background color class
         * @param string
         * @return string
         * @see v1.0
         */
        public static function irantheme_get_navigation_bg_color_class($page_id = null)
        {
            // Get navigation background color
            $navigation_bg_color = irantheme_get_header_option('irantheme_navigation_bg_color_select', $page_id);
            if ($navigation_bg_color == 'dark') {
                $navigation_class_color = 'irantheme-navigation-dark';
            } elseif ($navigation_bg_color == 'white') {
                $navigation_class_color = 'irantheme-navigation-white';
            }
            return $navigation_class_color;
        }


        /**
         * Return navigation transform css
         * @param void
         * @return string
         * @see v1.0
         */
        public static function irantheme_get_navigation_transform_css($page_id = null)
        {
            if (irantheme_get_header_option('irantheme_header_alignment_radio', $page_id) != 'navigation') {
                // Get navigation position
                $navigation_position = irantheme_get_header_option('irantheme_navigation_position', $page_id);
                // Check navigation position
                if ($navigation_position == 'left') {
                    $navigation_css = 'transform:translateX(-' . self::irantheme_get_navigation_width($page_id) . 'px);';
                } else if ($navigation_position == 'right') {
                    $navigation_css = 'transform:translateX(' . self::irantheme_get_navigation_width($page_id) . 'px);';
                }
                return $navigation_css;
            }
        }


        /**
         * Return navigation css background image
         * @param void
         * @return string
         * @see v1.0
         */
        public static function irantheme_get_navigation_bg_image_css($page_id = null)
        {
            // Get navigation bg image
            $navigation_bg_image = irantheme_get_header_option('irantheme_navigation_bg_image', $page_id);
            $navigation_css_background = '';
            // Get header bg
            if ($navigation_bg_image) {
                $navigation_css_background = 'background-image:url(' . wp_get_attachment_image_src($navigation_bg_image, 'full')[0] . ');background-repeat:no-repeat;background-size:cover;background-position:center;';
            }
            return $navigation_css_background;
        }


        /**
         * Get navigation menu
         * @return string
         * @see v1.0
         */
        public static function irantheme_navigation_main_menu($page_id = null)
        {
            $navigation_menu_id = irantheme_get_header_option('irantheme_navigation_menu_select', $page_id);
            if ($navigation_menu_id == 'none') {
                return;
            }
            wp_nav_menu(array(
                'menu' => $navigation_menu_id,
                'menu_class' => 'irantheme-navigation-menu-ul',
                'container_class' => 'irantheme-navigation-menu',
                // 'depth' => 4,
                'fallback_cb' => false,
                'theme_location' => 'iranthemeNavigationMainMenu'
            ));
        }


        /**
         * Get navbar secondary menu
         * @return void
         * @param void
         * @see v1.0
         */
        public static function irantheme_topbar_menu(int $depth = 3, $page_id = null)
        {
            $topbar_menu_id = irantheme_get_header_option('irantheme_topbar_menu_select', $page_id);
            if ($topbar_menu_id == 'none') {
                return;
            }
            wp_nav_menu(array(
                'menu' => $topbar_menu_id,
                'menu_class' => 'irantheme-navbar-menu-ul',
                'container_class' => 'irantheme-container-navbar-menu irantheme-secondary-navbar-menu',
                'depth' => $depth,
                'fallback_cb' => false,
                'theme_location' => 'iranthemeTopbarMenu'
            ));
        }


        /**
         * Return topbar base color
         * @param void
         * @return string
         * @see v1.0
         */
        public static function irantheme_get_topbar_base_color_class($page_id = null)
        {
            // Get topbar base color
            $topbar_option = irantheme_get_header_option('irantheme_topbar_base_color_select', $page_id);
            if ($topbar_option == 'white') {
                return 'irantheme-header-topbar-white';
            } else if ($topbar_option == 'dark') {
                return 'irantheme-header-topbar-dark';
            }
        }

        /**
         * Return topbar background color
         * @param void
         * @return string
         * @author M-MALEKZADEH77
         * @see v1.0
         */
        public static function irantheme_get_topbar_bg_color($page_id = null)
        {
            // Custom color
            $topbar_css_color = '';
            // Get topbar background color
            $topbar_bg_option = irantheme_get_header_option('irantheme_topbar_bg_color_select', $page_id);
            if ($topbar_bg_option != 'off') {
                $topbar_css_color = 'background: #' . esc_attr(Irantheme_Color_Static::get_hex_color($topbar_bg_option)) . ';';
            }
            return $topbar_css_color;
        }

        /**
         * Return topbar transparent class, if option enabled
         * @param void
         * @return string
         * @author M-MALEKZADEH77
         * @see v1.0
         * @since v1.0
         */
        public static function irantheme_get_topbar_transparent_class_option($page_id = null)
        {
            if (irantheme_get_header_option('irantheme_topbar_bg_transparent_checkbox', $page_id) == 'on') {
                return 'irantheme-topbar-transparency';
            }
        }


        /**
         * Get topbar transparency options from settings options
         * @param void
         * @return string
         * @author M-MALEKZADEH77
         * @see v1.0
         * @since v1.0
         */
        public static function irantheme_get_topbar_transparency_css_option($page_id = null)
        {
            // Holder of range transparency
            $topbar_transparent_attr = '';
            // Check is enable transparent option field
            if (irantheme_get_header_option('irantheme_topbar_bg_transparent_checkbox', $page_id) == 'on') {
                $topbar_transparency_range = irantheme_get_header_option('irantheme_topbar_bg_transparency_range', $page_id);
                $topbar_transparency_range = (100 - $topbar_transparency_range) / 100;
                $hex_color = Irantheme_Color_Static::get_hex_color(irantheme_get_header_option('irantheme_topbar_bg_color_select', $page_id));
                $base_color = irantheme_get_header_option('irantheme_topbar_base_color_select', $page_id);
                // Check header color class
                if ($hex_color == 'off') {
                    if ('white' == $base_color) {
                        // Add white transparency
                        $topbar_transparent_attr = 'background: rgba(255, 255, 255, ' . $topbar_transparency_range . ') !important;';
                    } else if ('dark' == $base_color) {
                        // Add dark transparency
                        $topbar_transparent_attr = 'background: rgba(31, 34, 41, ' . $topbar_transparency_range . ') !important;';
                    }
                } else {
                    $rgb_color = Irantheme_Color_Static::hex_to_rgb($hex_color);
                    $topbar_transparent_attr = 'background: rgba(' . $rgb_color[0] . ', ' . $rgb_color[1] . ', ' . $rgb_color[2] . ', ' . $topbar_transparency_range . ') !important;';
                }
            }
            return $topbar_transparent_attr;
        }


        /**
         * Get topbar blur options from settings options
         * @param void
         * @return string
         * @see v1.0
         */
        public static function irantheme_get_topbar_blur_css_option($page_id = null)
        {
            // Holder of range blur
            $topbar_blur_attr = '';
            // Check is enable transparent option field
            if (irantheme_get_header_option('irantheme_topbar_bg_transparent_checkbox', $page_id) == 'on') {
                $topbar_blur_range = irantheme_get_header_option('irantheme_topbar_bg_blur_range', $page_id);
                // Add css blur
                $topbar_blur_attr = 'backdrop-filter:blur(' . $topbar_blur_range . 'px);-webkit-backdrop-filter:blur(' . $topbar_blur_range . 'px);';
            }
            return $topbar_blur_attr;
        }


        /**
         * Return topbar height css
         * @param void
         * @return string
         * @author iranianclever
         * @see v1.0
         * @since v1.0
         */
        public static function irantheme_get_topbar_height_css($page_id = null)
        {
            // Custom css
            $topbar_css = '';
            // Get topbar background color
            $topbar_height_option = irantheme_get_header_option('irantheme_topbar_height_range', $page_id);
            $topbar_css = 'min-height:' . $topbar_height_option . 'rem;';
            return $topbar_css;
        }

        /**
         * Return topbar height css
         * @param void
         * @return string
         * @author iranianclever
         * @see v1.0
         * @since v1.0
         */
        public static function irantheme_get_topbar_font_size_css($page_id = null)
        {
            // Custom css
            $topbar_css = '';
            // Get topbar font size option
            $topbar_font_size_option = irantheme_get_header_option('irantheme_topbar_font_size_range', $page_id);
            $topbar_css = 'font-size:' . $topbar_font_size_option . 'rem;';
            return $topbar_css;
        }


        /**
         * Return topbar height css for svg icon
         * @param void
         * @return string
         * @author iranianclever
         * @see v1.0
         * @since v1.0
         */
        public static function irantheme_get_topbar_font_size_svg_css($page_id = null)
        {
            // Custom css
            $topbar_css = '';
            // Get topbar font size option
            $topbar_font_size_option = irantheme_get_header_option('irantheme_topbar_font_size_range', $page_id);
            $topbar_css = 'width:' . $topbar_font_size_option . 'rem;height:' . $topbar_font_size_option . 'rem;';
            return $topbar_css;
        }


        /**
         * Return topbar border bottom class
         * @param void
         * @return string
         * @author M-MALEKZADEH77
         * @see v1.0
         */
        public static function irantheme_get_topbar_border_bottom_class($page_id = null)
        {
            // Custom class
            $topbar_border_class = '';
            // Get topbar background color
            $topbar_border_option = irantheme_get_header_option('irantheme_topbar_border_bottom_checkbox', $page_id);
            if ($topbar_border_option == 'on') {
                $topbar_border_class = 'irantheme-topbar-border-bottom';
            }
            return $topbar_border_class;
        }


        /**
         * Return status menus checkbox
         * @param void
         * @return bool
         * @see v1.0
         */
        public static function irantheme_is_topbar_menus_checkbox($page_id = null)
        {
            if (irantheme_get_header_option('irantheme_topbar_menus_checkbox', $page_id) == 'on') {
                return true;
            }
            return false;
        }


        /**
         * Return status note checkbox
         * @param void
         * @return bool
         * @see v1.0
         */
        public static function irantheme_is_topbar_note_checkbox($page_id = null)
        {
            if (irantheme_get_header_option('irantheme_topbar_note_checkbox', $page_id) == 'on') {
                return true;
            }
            return false;
        }


        /**
         * Return status social media checkbox
         * @param void
         * @return bool
         * @see v1.0
         */
        public static function irantheme_is_topbar_social_media_checkbox($page_id = null)
        {
            if (irantheme_get_header_option('irantheme_topbar_social_media_checkbox', $page_id) == 'on') {
                return true;
            }
            return false;
        }


        /**
         * Get topbar class alignment
         * @param void
         * @return string
         * @see v1.0
         */
        public static function irantheme_get_topbar_class_alignment($page_id = null)
        {
            // Get option
            $option = irantheme_get_header_option('irantheme_topbar_alignment_radio', $page_id);
            // Check value option
            if ($option) {
                // Evolution option value and return specific class
                switch ($option) {
                    // Right alignment
                    case 'right':
                        return 'irantheme-topbar-alignment-right';
                        break;
                    // Right menu alignment
                    case 'menu-right':
                        return 'irantheme-topbar-alignment-menu-right';
                        break;
                    // Left menu alignment
                    case 'menu-left':
                        return 'irantheme-topbar-alignment-menu-left';
                        break;

                    default:
                        return '';
                        break;
                }
            }
        }


        /**
         * Return get topbar first note icon
         * @param void
         * @return string,bool
         * @see v1.0
         */
        public static function irantheme_get_topbar_first_note_icon($page_id = null)
        {
            // Get value
            $note_value = irantheme_get_header_option('irantheme_topbar_first_note_icon', $page_id);
            // Check value
            if ($note_value) {
                return $note_value;
            }
            return false;
        }


        /**
         * Return get topbar first note text
         * @param void
         * @return string,bool
         * @see v1.0
         */
        public static function irantheme_get_topbar_first_note($page_id = null)
        {
            // Get value
            $note_value = irantheme_get_header_option('irantheme_topbar_first_note', $page_id);
            // Check value
            if ($note_value) {
                return $note_value;
            }
            return false;
        }


        /**
         * Return get topbar second note icon
         * @param void
         * @return string,bool
         * @see v1.0
         */
        public static function irantheme_get_topbar_second_note_icon($page_id = null)
        {
            // Get value
            $note_value = irantheme_get_header_option('irantheme_topbar_second_note_icon', $page_id);
            // Check value
            if ($note_value) {
                return $note_value;
            }
            return false;
        }


        /**
         * Return get topbar second note icon
         * @param void
         * @return string,bool
         * @see v1.0
         */
        public static function irantheme_get_topbar_second_note($page_id = null)
        {
            // Get value
            $note_value = irantheme_get_header_option('irantheme_topbar_second_note', $page_id);
            // Check value
            if ($note_value) {
                return $note_value;
            }
            return false;
        }


        /**
         * Get header elements
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_header($page_id = null)
        {
            $header_mode = irantheme_get_header_option('irantheme_header_alignment_radio', $page_id);
            // Get header head
            Irantheme_Header_Main::header_head($page_id);
        ?>

            <?php
            if (!self::irantheme_is_header_full_width($page_id) && self::irantheme_is_header_width_limit($page_id)) {
            ?>
                <div class="irantheme-container-inner">
                <?php
            }

            // Get navbar topbar
            self::navbar_topbar();

            if (!self::irantheme_is_header_full_width($page_id) && self::irantheme_is_header_width_limit($page_id)) {
                ?>
                </div>
            <?php
            }

            // Navbar fullscreen
            self::navbar_fullscreen();

            if (!self::irantheme_is_header_full_width($page_id) && self::irantheme_is_header_width_limit($page_id)) {
            ?>
                <div class="irantheme-container-inner">
                <?php
            }
                ?>
                <!-- Header primary -->
                <header id="irantheme-header-main" class="irantheme-header-main <?php echo esc_attr(self::irantheme_get_header_base_color_class_option($page_id)); ?> <?php echo esc_attr(self::irantheme_get_header_transparent_class_option($page_id)); ?> <?php echo esc_attr(self::irantheme_get_header_border_bottom_class_option($page_id)) ?> <?php echo esc_attr(self::irantheme_get_header_class_fixed($page_id)); ?> <?php echo esc_attr(self::irantheme_get_header_class_sticky($page_id)); ?>" data-class-color="<?php echo esc_attr(self::irantheme_get_header_base_color_class_option($page_id)); ?>" data-class-scroll="<?php echo esc_attr(self::irantheme_get_header_bg_class_color_on_scroll($page_id)); ?>" data-class-color-default="<?php echo esc_attr(self::irantheme_get_header_base_color_class_option($page_id)); ?>" data-class-transparent="<?php echo esc_attr(self::irantheme_get_header_transparent_class_option($page_id)); ?>" style="<?php echo self::irantheme_get_header_bg_color($page_id); ?><?php echo esc_attr(self::irantheme_get_header_necessary_css($page_id)); ?><?php echo self::irantheme_get_header_radius_css($page_id); ?><?php echo self::irantheme_get_header_blur_css_option($page_id); ?><?php echo self::irantheme_get_header_alignment_necessary_css($page_id); ?>" <?php echo esc_attr(Irantheme_Page_Builder::get_attr_data_header()); ?>>

                    <?php
                    // Get navbar search box
                    self::navbar_search_box();
                    ?>

                    <div class="irantheme-header-alignment <?php echo esc_attr(self::irantheme_get_header_class_alignment($page_id)); ?> <?php echo esc_attr(self::irantheme_get_header_class_shrink($page_id)); ?>">
                        <div class="irantheme-header-wrapper-main" style="<?php echo esc_attr(self::irantheme_get_header_height_option($page_id)); ?>">
                            <div class="<?php echo esc_attr(self::irantheme_get_header_class_container($page_id)); ?>">
                                <!-- Header row -->
                                <div class="irantheme-header-row">
                                    <?php
                                    // Get header logo
                                    Irantheme_Header_Main::get_header_columns()
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?php if ($header_mode == 'column-three'): ?>
                            <div class="irantheme-header-wrapper-menu">
                                <div class="<?php echo esc_attr(self::irantheme_get_header_class_container($page_id)); ?>">
                                    <!-- Header row -->
                                    <div class="irantheme-header-row">
                                        <div class="irantheme-header-col">
                                            <?php
                                            // Get main menu
                                            self::get_navbar_main_menu();
                                            ?>
                                        </div>
                                        <div class="irantheme-header-col">
                                            <?php
                                            // Get secondary menu
                                            self::get_navbar_secondary_menu();
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php
                    // Get navbar dropdown
                    self::navbar_dropdown($page_id);

                    // Get page builder hidden require
                    Irantheme_Page_Builder::get_hidden_require_header_buttons($page_id);
                    ?>

                    <script>
                        // Check class center split in header alignment
                        if (
                            jQuery('.irantheme-header-alignment').hasClass(
                                'irantheme-header-alignment-center-split'
                            ) &&
                            jQuery('.irantheme-header-main').find(
                                '.irantheme-navbar-trigger-holder'
                            ).attr('data-trigger-desktop') == ''
                        ) {
                            // Get header logo brand
                            let logo = jQuery('.irantheme-header-main .irantheme-header-col-menu .irantheme-logo-brand');
                            // Create nav item
                            let navItem = jQuery(
                                '<li class="menu-item irantheme-normal-menu-item"></li>'
                            );
                            // Append logo to nav item
                            jQuery(navItem).append(logo);
                            // Get length navbar primary items
                            let lengthNavItems = jQuery(
                                '.irantheme-header-main .irantheme-primary-navbar-menu .irantheme-navbar-menu-ul'
                            ).children().length;
                            // Append nav item to primary navbar menu
                            jQuery(
                                    '.irantheme-header-main .irantheme-primary-navbar-menu .irantheme-navbar-menu-ul'
                                )
                                .children()
                                .eq(Math.floor(lengthNavItems / 2))
                                .before(navItem);
                        }
                    </script>
                </header><!-- End header primary -->

                <?php
                if (!self::irantheme_is_header_full_width($page_id) && self::irantheme_is_header_width_limit($page_id)) {
                ?>
                </div>
            <?php
                }
            }


            /**
             * Get header head
             * @param void
             * @return void
             * @author iranianclever
             * @since v1.0
             * @see v1.0
             */
            public static function header_head($page_id = null)
            {
            ?>
            <!DOCTYPE html>
            <html <?php language_attributes(); ?>>

            <head>
                <meta charset="<?php bloginfo('charset'); ?>" />
                <meta http-equiv="X-UA-Compatible" content="IE=edge" />
                <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                <?php wp_head(); ?>
                <?php irantheme_preload_styles($page_id); ?>
            </head>

            <body id="top" <?php body_class(); ?>>

                <?php wp_body_open(); ?>

                <!-- Outer container -->
                <div class="irantheme-container-outer">

                    <?php
                    $irantheme_main_css = '';
                    $header_side_css = '';
                    $wrapper_side_css = '';
                    if (irantheme_get_header_option('irantheme_header_alignment_radio', $page_id) == 'navigation') {
                        $irantheme_main_css = 'display:flex;';
                        $header_side_css = 'width:' . self::irantheme_get_navigation_width($page_id) . 'px;';
                        $wrapper_side_css = 'width: calc(100% - ' . self::irantheme_get_navigation_width($page_id) . 'px)';
                    }

                    // Get page meta data json
                    $page_config_fields = Irantheme_Page_Builder_Ajax::get_page_settings($page_id, 'page_config', 'page_config_fields');

                    // Background options ------------------------------------------------------------
                    $background_image_data = $page_config_fields['bg_image'];
                    $background_color_data = $page_config_fields['bg_color'];
                    $background_parallax_checkbox = $page_config_fields['bg_parallax_checkbox'];
                    $background_color_css = Irantheme_Section_Utils::get_background_color_css($background_color_data);
                    $background_image_css = Irantheme_Section_Utils::get_background_image_css($background_image_data);
                    $background_parallax_attr = Irantheme_Section_Utils::get_background_parallax_attr($background_parallax_checkbox, $background_image_data);

                    // Get navbar mobile checkbox
                    $navbar_mobile_checkbox = get_option('irantheme_navbar_mobile_checkbox');
                    $navbar_mobile_class = '';
                    if ($navbar_mobile_checkbox == 'on') {
                        $navbar_mobile_full_width_checkbox = get_option('irantheme_navbar_mobile_full_width_checkbox');
                        $navbar_mobile_auto_hide_checkbox = get_option('irantheme_navbar_mobile_auto_hide_checkbox');
                        if ($navbar_mobile_auto_hide_checkbox == 'off') {
                            if ($navbar_mobile_full_width_checkbox == 'on') {
                                $navbar_mobile_class = 'irantheme-navbar-mobile-full-width';
                            } else {
                                $navbar_mobile_class = 'irantheme-navbar-mobile-space-width';
                            }
                        }
                    }

                    // // Nav menu filtered ----------------------------------------------------
                    // $navMenuFiltered = new Irantheme_Nav_Menu($page_id);
                    // add_filter('nav_menu_item_title', array($navMenuFiltered, 'generate_nav_menu_icon_output'), 10, 2);
                    // add_filter('nav_menu_submenu_css_class', array($navMenuFiltered, 'generate_nav_menu_submenu_css_class_output'), 10, 3);

                    $navigation_position_css = '';
                    if (irantheme_get_header_option('irantheme_navigation_position', $page_id) == 'right') {
                        $navigation_position_css = 'flex-direction:row;';
                    } else if (irantheme_get_header_option('irantheme_navigation_position', $page_id) == 'left') {
                        $navigation_position_css = 'flex-direction:row-reverse';
                    }
                    ?>

                    <!-- Main wrapper -->
                    <div id="irantheme-main" class="<?php echo esc_attr($navbar_mobile_class); ?> irantheme-background-jarallax" style="<?php echo esc_attr($irantheme_main_css); ?><?php echo esc_attr($background_color_css); ?><?php echo esc_attr($background_image_css); ?><?php echo esc_attr($navigation_position_css); ?>" <?php echo $background_parallax_attr; ?>>

                        <?php
                        // Get background mask checkbox
                        $mask_checkbox = $page_config_fields['bg_mask_checkbox'];
                        // Get background mask color
                        $mask_color = $page_config_fields['bg_mask_color'];
                        // Get background mask range
                        $mask_range = $page_config_fields['bg_mask_range'];
                        // Get showcase mask --------------------------------------------------------------
                        Irantheme_Section_Utils::get_background_mask_tag_html($mask_checkbox, $mask_color, $mask_range);
                        ?>

                        <?php if (irantheme_get_header_option('irantheme_header_alignment_radio', $page_id) == 'toggle-menu' || irantheme_get_header_option('irantheme_mobile_trigger_menu_mode', $page_id) == 'navigation' || irantheme_get_header_option('irantheme_header_alignment_radio', $page_id) == 'navigation') :
                        ?>
                            <div id="irantheme-header-sidebar" style="<?php echo esc_attr($header_side_css); ?>">
                                <?php
                                // navbar navigation
                                Irantheme_Header_Main::navbar_navigation($page_id);
                                ?>
                            </div>
                        <?php endif; ?>

                        <!-- Wrapper -->
                        <div id="irantheme-wrapper" style="<?php echo esc_attr($wrapper_side_css); ?>">
                            <!-- Wrapper mask -->
                            <div id="irantheme-wrapper-mask"></div>
                <?php
            }
        }
    }
