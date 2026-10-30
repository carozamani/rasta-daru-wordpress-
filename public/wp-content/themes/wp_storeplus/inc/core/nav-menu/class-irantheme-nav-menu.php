<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;


if (!class_exists('Irantheme_Nav_Menu')) {

    /**
     * Nav menu wordpress configuration
     *
     * @package Wordpress
     * @subpackage irantheme
     * @since irantheme - custom fields
     * @author iranianclever
     * @see v1.0
     *
     */

    class Irantheme_Nav_Menu
    {
        private static $textdomain = IRANTHEME_TEXTDOMAIN;
        private $page_id;

        /**
         * Constructor
         * @return void
         * @param void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        function __construct($page_id = null)
        {
            $this->page_id = $page_id;

            // Running in admin
            add_action('wp_nav_menu_item_custom_fields', array($this, 'admin_add_elements_nav_menus'), 10, 2);
            add_action('wp_update_nav_menu_item', array($this, 'admin_save_nav_menus'), 10, 2);
            // Running in client
            add_filter('nav_menu_item_title', array($this, 'generate_nav_menu_icon_output'), 10, 2);
            add_filter('nav_menu_css_class', array($this, 'generate_nav_menu_items_classes_output'), 10, 3);
            add_filter('nav_menu_link_attributes', array($this, 'generate_nav_menu_submenu_image_style_output'), 10, 3);
            add_filter('nav_menu_submenu_css_class', array($this, 'generate_nav_menu_submenu_css_class_output'), 10, 3);
            add_filter('nav_menu_link_attributes', array($this, 'generate_nav_menu_items_link_classes_output'), 10, 3);
        }


        /**
         * Generate post meta name
         * @return string
         * @param string,string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_name_post_meta_mixed($name, $id)
        {
            return $name . '_' . $id;
        }


        /**
         * Get all fields elements to generate settings
         * @return array
         * @param void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function admin_fields_elements_nav_menus()
        {
            $fields_settings = array(
                array(
                    'id'             => 'irantheme_menu_icon_mode_image',
                    'label'            => __('تصویر آیکون', self::$textdomain),
                    'description'    => __('با استفاده از این گزینه می توانید تصویر آیکونی برای منوی خود تعیین کنید.', self::$textdomain),
                    'note' => __('در صورتی که علاقمند به استفاده از آیکون هستید، تصویری انتخاب نکنید. اولویت با "تصویر آیکون" می باشد.', self::$textdomain),
                    'type'            => 'image',
                    'default'        => '',
                    'placeholder'    => '',
                ),
                array(
                    'id'             => 'irantheme_menu_icon_select',
                    'label'            => __(
                        'آیکون منو',
                        self::$textdomain
                    ),
                    'description'    => __('با استفاده از این گزینه می توانید آیکونی برای این منو برگزینید.', self::$textdomain),
                    'note' => __('در صورتی که علاقمند به استفاده از تصویر آیکون نیستند، می توانید از آیکون های آماده استفاده کنید.', self::$textdomain),
                    'type'            => 'icon',
                    'default'        => '',
                    'placeholder'    => __('', self::$textdomain),
                ),
                array(
                    'id'             => 'irantheme_menu_image_select',
                    'label'            => __('تصویر پس زمینه', self::$textdomain),
                    'description'    => __('با استفاده از این گزینه می توانید تصویر پس زمینه ای برای منوی خود تعیین کنید.', self::$textdomain),
                    'type'            => 'image',
                    'default'        => '',
                    'placeholder'    => '',
                ),
                array(
                    'id'             => 'irantheme_menu_badge_input',
                    'label'            => __(
                        'متن نشان',
                        self::$textdomain

                    ),
                    'description'    => __('با استفاده از این گزینه می توانید یک نشان (متن کوتاه) جهت برجسته کردن این آیتم نسبت به بقیه آیتم ها اضافه کنید.', self::$textdomain),
                    'type'            => 'text',
                    'default'        => '',
                    'max_length' => 30,
                    'placeholder'    => __('مثال: جدید، داغ، پربازدید و...', self::$textdomain),
                ),
                array(
                    'id'             => 'irantheme_menu_badge_color_checkbox',
                    'label'            => __(
                        'رنگ نشان',
                        self::$textdomain
                    ),
                    'description'    => __('با استفاده از این ویژگی می توانید رنگی برای متن نشان خود انتخاب کنید.', self::$textdomain),
                    'type'            => 'select',
                    'color_preview' => true,
                    'options'        => self::get_badge_colors(),
                    'default'        => '346DF9',
                ),
                array(
                    'id'             => 'irantheme_menu_full_width',
                    'label'            => __(
                        'منوی وسیع (مگامنو)',
                        self::$textdomain
                    ),
                    'description'    => __('با استفاده از این گزینه می توانید منوی خود را وسعت ببخشید.', self::$textdomain),
                    'type'            => 'checkbox',
                    'default'        => 'off',
                ),
                array(
                    'id'             => 'irantheme_button_style_checkbox',
                    'label'            => __(
                        'تبدیل به دکمه',
                        self::$textdomain
                    ),
                    'description'    => __('با استفاده ار این گزینه می توانید این آیتم از منو را تبدیل به دکمه کنید.', self::$textdomain),
                    'type'            => 'select',
                    'color_preview' => true,
                    'options'        => Irantheme_Color_Static::get_all_colors_options(false, true),
                    'default'        => 'off',
                ),
            );

            return $fields_settings;
        }


        /**
         * Get all happy colors for badge
         * @return void
         * @param void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_badge_colors()
        {
            $colors = array(
                '346DF9' => 'آبی',
                'e67e22' => 'نارنجی',
                'e74c3c' => 'قرمز',
                '2ecc71' => 'سبز',
                'f1c430' => 'زرد',
                '9b59b6' => 'بنفش'
            );
            return $colors;
        }


        /**
         * Modify elements on nav menus
         * @return void
         * @param mixed,mixed
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        function admin_add_elements_nav_menus($item_id, $item)
        {
?>
            <div class="irantheme-settings" data-settings="customFields">
                <!-- Custom nav menus -->
                <div class="irantheme-custom-nav-menus irantheme-font">
                    <?php
                    $all_fields = self::admin_fields_elements_nav_menus();
                    foreach ($all_fields as $fields) {
                        // Combination prefix and custom field name to generate register name
                        $register_name = self::get_name_post_meta_mixed($fields['id'], $item_id);
                        // Get post meta value
                        $post_meta_value = get_post_meta($item_id, $register_name, true);
                        if (strpos($register_name, 'irantheme_menu_icon_mode_image') !== false) :
                    ?>
                            <div class="irantheme-custom-option-container" style="clear:both;">
                                <b class="irantheme-heading-option"><?php echo esc_html($fields['label']); ?></b>
                                <?php echo Irantheme_Settings::display_fields($fields, $register_name, $post_meta_value); ?>
                            </div>
                        <?php
                        elseif (strpos($register_name, 'irantheme_menu_icon_select') !== false) :
                        ?>
                            <div class="irantheme-custom-option-container" style="clear:both;">
                                <b class="irantheme-heading-option"><?php echo esc_html($fields['label']); ?></b>
                                <?php echo Irantheme_Settings::display_fields($fields, $register_name, $post_meta_value); ?>
                            </div>
                        <?php
                        elseif (strpos($register_name, 'irantheme_menu_image_select') !== false) :
                        ?>
                            <div class="irantheme-custom-option-container" style="clear:both;">
                                <b class="irantheme-heading-option"><?php echo esc_html($fields['label']); ?></b>
                                <?php echo Irantheme_Settings::display_fields($fields, $register_name, $post_meta_value); ?>
                            </div>
                        <?php
                        elseif (!$item->menu_item_parent == 0 && strpos($register_name, 'irantheme_menu_badge') !== false) :
                        ?>
                            <div class="irantheme-custom-option-container" style="clear:both;">
                                <b class="irantheme-heading-option"><?php echo esc_html($fields['label']); ?></b>
                                <?php echo Irantheme_Settings::display_fields($fields, $register_name, $post_meta_value); ?>
                            </div>
                        <?php
                        elseif ($item->menu_item_parent == 0 && strpos($register_name, 'irantheme_menu_badge') === false) :
                        ?>
                            <div class="irantheme-custom-option-container" style="clear:both;">
                                <b class="irantheme-heading-option"><?php echo esc_html($fields['label']); ?></b>
                                <?php echo Irantheme_Settings::display_fields($fields, $register_name, $post_meta_value); ?>
                            </div>
                    <?php
                        endif;
                    }
                    ?>
                </div>
            </div>
<?php
        }


        /**
         * Operation saving nav menus
         * @return void
         * @param string,string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        function admin_save_nav_menus($menu_id, $item_id)
        {
            $all_fields = self::admin_fields_elements_nav_menus();
            foreach ($all_fields as $fields) {
                // Check icon field value and update or delete from database
                if (isset($_POST[self::get_name_post_meta_mixed($fields['id'], $item_id)])) {
                    $sanitized_data = sanitize_text_field($_POST[self::get_name_post_meta_mixed($fields['id'], $item_id)]);
                    update_post_meta($item_id, self::get_name_post_meta_mixed($fields['id'], $item_id), $sanitized_data);
                } else {
                    delete_post_meta($item_id, self::get_name_post_meta_mixed($fields['id'], $item_id));
                }
            }
        }


        /**
         * Generate icons for nav menu in client
         * @return string
         * @param string,object
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        function generate_nav_menu_icon_output($title, $item)
        {
            // If set item menu
            if (is_object($item) and isset($item->ID)) {
                // Get from post meta
                $image = get_post_meta($item->ID, self::get_name_post_meta_mixed('irantheme_menu_icon_mode_image', $item->ID), true);
                // Get from post meta
                $icon = get_post_meta($item->ID, self::get_name_post_meta_mixed('irantheme_menu_icon_select', $item->ID), true);
                // Get badge mode item text
                $menu_badge_text = get_post_meta($item->ID, self::get_name_post_meta_mixed('irantheme_menu_badge_input', $item->ID), true);
                // Get badge mode item color
                $menu_badge_color = get_post_meta($item->ID, self::get_name_post_meta_mixed('irantheme_menu_badge_color_checkbox', $item->ID), true);
                // Holder tag of badge
                $menu_badge_output = null;
                // Holder color class of badge
                $menu_badge_color_class = null;
                // Check color of badge & add color class
                switch ($menu_badge_color) {
                    case '346DF9':
                        $menu_badge_color_class = 'irantheme-menu-item-link-mode-blue';
                        break;
                    case 'e67e22':
                        $menu_badge_color_class = 'irantheme-menu-item-link-mode-orange';
                        break;
                    case 'e74c3c':
                        $menu_badge_color_class = 'irantheme-menu-item-link-mode-red';
                        break;
                    case '2ecc71':
                        $menu_badge_color_class = 'irantheme-menu-item-link-mode-green';
                        break;
                    case 'f1c430':
                        $menu_badge_color_class = 'irantheme-menu-item-link-mode-yellow';
                        break;
                    case '9b59b6':
                        $menu_badge_color_class = 'irantheme-menu-item-link-mode-purple';
                        break;

                    default:
                        $menu_badge_color_class = '';
                        break;
                }
                if ($menu_badge_text != '') {
                    // Add badge text & color
                    $menu_badge_output = '<b class="irantheme-menu-item-link-mode ' . $menu_badge_color_class . '">' . $menu_badge_text . '</b>';
                }
                // If is set image with 30x30 size
                if (!empty($image)) {
                    $title = '<span class="irantheme-menu-item-link-title"><cite><img class="irantheme-menu-item-link-image" src="' . wp_get_attachment_image_src($image, array(30, 30))[0] . '" alt="' . esc_attr__('Menu Icon', self::$textdomain) . '">' . $title . $menu_badge_output . '</cite></span>';
                }
                // If is set icon
                elseif (!empty($icon)) {
                    $title = '<span class="irantheme-menu-item-link-title"><cite><i class="irantheme-menu-item-link-icon ' . $icon . '"></i>' . $title . $menu_badge_output . '</cite></span>';
                } else {
                    $title = '<span class="irantheme-menu-item-link-title"><cite>' . $title . $menu_badge_output . '</cite></span>';
                }
                // Check item has children
                if (in_array('menu-item-has-children', $item->classes) && irantheme_get_header_option('irantheme_menu_angle_icon_checkbox', $this->page_id) == 'on') {
                    $title = $title . '<i class="irantheme-menu-item-link-icon-chevron fa-light fa-chevron-down"></i>';
                }
            }
            return $title;
        }


        /**
         * Generate nav menu classes for items in client
         * @return array
         * @param array,object,array
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        function generate_nav_menu_items_classes_output($classes, $item, $args)
        {
            // Get mega menu from post meta
            $mega_menu = get_post_meta($item->ID, self::get_name_post_meta_mixed('irantheme_menu_full_width', $item->ID), true);
            // Get bg image from post meta
            $image_menu = get_post_meta($item->ID, self::get_name_post_meta_mixed('irantheme_menu_image_select', $item->ID), true);

            // Check this is parent and image options
            if (
                $image_menu
            ) {
                array_push($classes, 'irantheme-image-menu-item');
            }

            // Check this is parent and mega menu?
            if ($item->menu_item_parent == 0 && $mega_menu == 'on') {
                array_push($classes, 'irantheme-mega-menu-item');
            } else if ($item->menu_item_parent == 0 && $mega_menu == 'off') {
                array_push($classes, 'irantheme-normal-menu-item');
            }

            // Get button style from post meta
            $button_style = get_post_meta($item->ID, self::get_name_post_meta_mixed('irantheme_button_style_checkbox', $item->ID), true);
            // Check this is parent?
            if ($item->menu_item_parent == 0 && $button_style != 'off') {
                array_push($classes, 'irantheme-button-holder');
            }

            return $classes;
        }


        /**
         * Generate nav menu image submenu styles
         * @return array
         * @param array,object,array
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        function generate_nav_menu_submenu_image_style_output($attr, $item, $args)
        {
            // Get bg image from post meta
            $image_menu = get_post_meta($item->ID, self::get_name_post_meta_mixed('irantheme_menu_image_select', $item->ID), true);

            // Check this is parent and image options
            if (
                $image_menu
            ) {
                $attr['data-image'] =
                    'background-image:url(' . wp_get_attachment_image_src($image_menu, 'full')[0] . ');background-repeat:no-repeat;background-size:cover;background-position:center;';
            }

            return $attr;
        }


        /**
         * Generate nav menu submenu classes css
         * @return array
         * @param array
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        function generate_nav_menu_submenu_css_class_output($classes)
        {
            if (irantheme_get_header_option('irantheme_submenu_bg_color_checkbox', $this->page_id) == 'on') {
                // Check bg color submenu option
                if ('white' == irantheme_get_header_option('irantheme_submenu_bg_color_select', $this->page_id)) {
                    // Assign white class
                    $classes[] = 'irantheme-navbar-sub-menu-white';
                } else if ('dark' == irantheme_get_header_option('irantheme_submenu_bg_color_select', $this->page_id)) {
                    // Assign dark class
                    $classes[] = 'irantheme-navbar-sub-menu-dark';
                }
            } else {
                // Get header option color
                $header_option_color = irantheme_get_header_option('irantheme_header_base_color_select', $this->page_id);
                if ($header_option_color == 'white') {
                    // Assign white class
                    $classes[] = 'irantheme-navbar-sub-menu-white';
                } else if ($header_option_color == 'dark') {
                    // Assign dark class
                    $classes[] = 'irantheme-navbar-sub-menu-dark';
                }
            }
            return $classes;
        }


        /**
         * Generate nav menu item link style classes
         * @return array
         * @param array,object,array
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        function generate_nav_menu_items_link_classes_output($attr, $item, $args)
        {
            // Get button style from post meta
            $button_style = get_post_meta($item->ID, self::get_name_post_meta_mixed('irantheme_button_style_checkbox', $item->ID), true);
            $attr['class'] = 'menu-item-link';
            if ($item->menu_item_parent == 0) {
                $attr['class'] .= ' menu-item-parent-link';
            } else {
                $attr['class'] .= ' menu-item-child-link ' . Irantheme_Color_Static::get_class_color_mode('bg-before', 'primary');
            }
            // Create star elements --------------------------------------------------------------
            $button_style_class = '';
            if ($button_style != 'off' && $button_style != 'default') {
                $button_style_class = 'irantheme-button-style ' . Irantheme_Color_Static::get_class_color_mode('btn', $button_style);
            } elseif ($button_style == 'default') {
                $button_style_class = 'irantheme-button-style irantheme-button-style-default';
            }
            // Check this is parent?
            if ($item->menu_item_parent == 0 && $button_style != 'off') {
                $attr['class'] .= ' menu-item-parent-link ' . $button_style_class;
            }

            return $attr;
        }
    } // End class
    new Irantheme_Nav_Menu();
}
