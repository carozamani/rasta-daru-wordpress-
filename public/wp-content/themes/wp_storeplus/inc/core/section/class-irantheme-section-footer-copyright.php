<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;

if (!class_exists('Irantheme_Section_Footer_Copyright')) {
    /**
     * A class for create section
     *
     * @package Wordpress
     * @subpackage irantheme
     * @since irantheme - custom fields
     * @author M-MALEKZADEH77
     * @see v1.0
     *
     */
    class Irantheme_Section_Footer_Copyright extends Irantheme_Section
    {
        /**
         * Constructor
         * @return void
         * @param void
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        function __construct() {}

        /**
         * Fields of section
         * @return void
         * @param void
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        public static function fields($status_default)
        {
            $settings = array(
                array(
                    'title' => 'محتوا اصلی',
                    'id' => 'middle',
                    'description'            => __('محتوای وسط فوتر', self::$static_textdomain),
                    'icon' => 'fa-pencil',
                    'fields' => array(
                        // Social Meida
                        array(
                            'id'             => 'social_media_checkbox',
                            'label'            => __(
                                'نمایش شبکه های اجتماعی',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت فعال بودن؛ شبکه های اجتماعی شما در پاورقی نمایش داده خواهد شد.
                                ', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'on' : '',
                            'keywords' => array('footer', 'socialmedia', 'شبکه های اجتماعی ما',  'شبکه های اجتماعی'),
                        ),
                        // Copyright
                        array(
                            'id'             => 'copyright_text_editor',
                            'label'            => __(
                                'متن کپی رایت خود را تنظیم کنید:',
                                self::$static_textdomain
                            ),
                            'type'            => 'text_editor',
                            'default'        => $status_default ? __('<span style="font-size: 1em;">کلیه حقوق مادی و معنوی این سایت محفوظ است.</span><span style="font-size: 1em;">تمامی خدمات، حسب مورد دارای مجوز های لازم از مراجع مربوطه می باشند و فعالیت های این سایت تابع قوانین و مقررات جمهوری اسلامی ایران است.</span>') : '',
                            'placeholder'    => __('', self::$static_textdomain),
                            'keywords' => array('copyright', 'copy', 'کپی رایت',  'کپی'),
                        ),
                    )
                ),
                array(
                    'title' => 'تنظیمات بصری',
                    'id' => 'layout',
                    'description'            => __('تنظیمات متحوا : تنظیم تیتر، تنظیم بند، رنگ متن و...', self::$static_textdomain),
                    'icon' => 'fa-paintbrush-pencil',
                    'fields' => array(
                        array(
                            'id'             => 'base_color_select',
                            'label'            => __(
                                'رنگ پایه',
                                self::$static_textdomain
                            ),
                            'description'    => __('برای انتخاب رنگ پایه از لیست رنگ دلخواه خود را انتخاب کنید.رنگ محتواو پس زمینه بر اساس این رنگ تنظیم خواهد شد.', self::$static_textdomain),
                            'type'            => 'select',
                            'options'        => array('white' => 'سفید', 'dark' => 'مشکی'),
                            'default'        => $status_default ? 'dark' : '',
                            'keywords' => array('color', 'background', 'پس زمینه', 'رنگ'),
                        ),
                        array(
                            'id'             => 'alignment_radio',
                            'label'            => __('موقعیت محتوا', self::$static_textdomain),
                            'description'    => __('با استفاده از این گزینه می توانید موقعیت قرارگیری محتوای پست ها را تعیین کنید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'thumbnail_status' => true,
                            'options'        => array('right' => array('راست چین', 'text-align-right.png'), 'center' => array('وسط چین', 'text-align-center.png'), 'left' => array('چپ چین', 'text-align-left.png')),
                            'default'        => $status_default ? 'right' : '',
                            'keywords' => array('محتوا', 'تنظیم', 'تراز', 'محور', 'alignment', 'float', 'position')
                        ),
                    )
                ),
                // Get layout scale fields
                Irantheme_Section_Utils::layout_scale_fields_generator($status_default, array('inner_padding', 'outer_padding', 'radius'), 'on', 3, 3, 'off', 'off', 0, 0, 0),
                // Get background fields
                Irantheme_Section_Utils::background_fields_generator($status_default, array('color', 'image', 'mask', 'parallax'), '', 'off', true, 'off', 'dark', '0.6', 'off'),
                // Get custom html & css fields
                Irantheme_Section_Utils::html_css_fields_generator($status_default, array('element_id', 'css')),
            );

            $settings = apply_filters('irantheme_store_visual_demo_settings', $settings);

            return $settings;
        }


        /**
         * Render elements of section
         * @param string,string,string,string,mixed
         * @return void
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        public static function render($fields_values, $data_type, $data_value, $data_page, $data_ajax)
        {
            // Background options ------------------------------------------------------------
            $background_image_data = $fields_values['bg_image'];
            $background_parallax_checkbox = $fields_values['bg_parallax_checkbox'];

            $background_image_css = Irantheme_Section_Utils::get_background_image_css($background_image_data);
            $background_parallax_attr = Irantheme_Section_Utils::get_background_parallax_attr($background_parallax_checkbox, $background_image_data);
            $background_color_data = $fields_values['bg_color'];
            $background_color_css = Irantheme_Section_Utils::get_background_color_css($background_color_data);

            // Base color --------------------------------------------------------------
            $base_color = $fields_values['base_color_select'];
            // Set base color class
            $base_color_class = Irantheme_Common_Static::get_base_color_class($base_color);

            // Set item alignment --------------------------------------------------------------
            $item_alignment = $fields_values['alignment_radio'];

            // Social Media
            $social_media_checkbox = $fields_values["social_media_checkbox"];

            // Copyright
            $copyright_content = $fields_values["copyright_text_editor"];

            // Get border radius --------------------------------------------------------------
            $border_radius_range = $fields_values['border_radius_range'];
            $border_radius_style = Irantheme_Section_Utils::get_border_radius_css($border_radius_range);

            // Get section id --------------------------------------------------------------
            $section_id = $fields_values['element_id'];
            $section_id_attribute = '';
            if ($section_id) {
                $section_id_attribute = sprintf('id=%s', $section_id);
            }
?>
            <!-- ============================= Section Footer Copyright ============================= -->
            <footer <?php echo esc_attr($section_id_attribute); ?> class="irantheme-section irantheme-footer-copyright-section <?php echo esc_attr($base_color_class); ?>" <?php echo esc_attr(Irantheme_Page_Builder::get_attr_data_section($data_type, $data_value, $data_page, $data_ajax)); ?> style="<?php echo esc_attr(Irantheme_Section_Service::get_custom_outer_padding_css($fields_values)); ?>">
                <!-- Copyright -->
                <div class="irantheme-footer-copyright irantheme-background-jarallax" style="<?php echo esc_attr(Irantheme_Section_Service::get_custom_inner_padding_css($fields_values)); ?><?php echo esc_attr($background_color_css); ?><?php echo esc_attr($background_image_css); ?><?php echo esc_attr($border_radius_style); ?>" <?php echo $background_parallax_attr; ?>>
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
                    <div class="irantheme-container-inner">
                        <div class="irantheme-footer-copyright-wrapper <?php echo esc_attr(Irantheme_Section_Service::get_alignment_class($item_alignment)); ?>">
                            <div class="irantheme-footer-copyright-text">
                                <?php echo $copyright_content; ?>
                            </div>
                            <div class="irantheme-footer-copyright-social-media <?php echo esc_attr(Irantheme_Section_Service::get_flex_alignment_class($item_alignment)); ?>">
                                <!-- Social -->
                                <?php if ($social_media_checkbox == "on") : ?>
                                    <?php Irantheme_Common_Static::get_social_media('', '', '', true); ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                Irantheme_Page_Builder::get_hidden_require_section_buttons($data_page);
                ?>
                <?php
                // Style scoped
                Irantheme_Section_Utils::display_custom_css_code_style($fields_values);
                ?>
            </footer>
<?php
        }
    }
}
