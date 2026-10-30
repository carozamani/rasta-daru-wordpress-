<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;

if (!class_exists('Irantheme_Section_Communication_Goggle_Map')) {
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
    class Irantheme_Section_Communication_Goggle_Map extends Irantheme_Section
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
         * Fields of section
         * @return void
         * @param void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function fields($status_default)
        {
            $iframe_code = '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d6719.795145871162!2d51.640850346969735!3d32.63555102985824!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3fbc367f487e0a65%3A0x2337b1a8923991bd!2sMarnan%2C%20Isfahan%2C%20Isfahan%20Province%2C%20Iran!5e0!3m2!1sen!2s!4v1710344027855!5m2!1sen!2s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>';
            $settings = array(
                array(
                    'title' => 'تنظیمات نقشه',
                    'id' => 'communication_google_map_map',
                    'description'            => __('تنظیمات مربوط به نقشه', self::$static_textdomain),
                    'icon' => 'fa-map-location-dot',
                    'fields' => array(
                        array(
                            'id'             => 'communication_google_map_iframe_code',
                            'label'            => __(
                                'کد iframe',
                                self::$static_textdomain

                            ),
                            'description'    => __('کد تولید شده توسط Google Map را در اینجا قرار دهید.'),
                            'type'            => 'textarea',
                            'default'        => $status_default ? $iframe_code : '',
                            'placeholder'    => __('', self::$static_textdomain),
                            'keywords' => array('communication', 'map', 'نقشه',  'ارتباطات'),
                        ),
                    )
                ),
                array(
                    'title' => 'تنظیمات بصری',
                    'id' => 'communication_google_map_layout',
                    'description'            => __('تنظیمات بصری مانند : تصویر زمینه، ارتفاع، رنگ ها و...', self::$static_textdomain),
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
                            'default'        => $status_default ? 'white' : '',
                            'keywords' => array('color', 'background', 'پس زمینه', 'رنگ'),
                        ),
                        array(
                            'id'             => 'communication_google_map_wrapper_mode_checkbox',
                            'label'            => __(
                                'اتصال به نگهدارنده خارجی سایت',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت غیر فعال بودن این گزینه بخش های (سکشن ها) سایت از نگهدارنده داخلی سایت استفاده می کنند، شما با فعال کردن این گزینه می توانید نگهدارنده این بخش را به نگهدارنده خارجی سایت تغییر دهید.در این صورت، این بخش از کناره ها به اندازه نگهدارنده خارجی سایت بزرگ شده وبه آن می چسبد.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'on' : '',
                            'keywords' => array('hieght', 'ارتفاع', 'fullscreen', 'تمام صفحه'),
                        ),
                        array(
                            'id'             => 'communication_google_map_custom_height_number',
                            'label'            => __(
                                'اندازه ارتفاع',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این فیلد سایز گوگل مپ خود را تعیین کنید. (واحد rem می باشد.)', self::$static_textdomain),
                            'type'            => 'number',
                            'step' => 1,
                            'default'        => $status_default ? 45 : '',
                            'min' => 10,
                            'max' => 75,
                            'placeholder'    => __('', self::$static_textdomain),
                            'keywords' => array('اندازه گوگل مپ', 'سایز', 'اندازه', 'google map', 'map', 'گوگل مپ'),
                        ),
                    )
                ),
                // Get layout scale fields
                Irantheme_Section_Utils::layout_scale_fields_generator($status_default, array('outer_padding'), 'off'),
                // Get background fields
                Irantheme_Section_Utils::background_fields_generator($status_default, array('image', 'color', 'mask', 'parallax', 'outer_container_color'), '', 'off', true, 'off', 'dark', 0.6, 'off'),
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
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function render($fields_values, $data_type, $data_value, $data_page, $data_ajax)
        {
            // Get iframe code --------------------------------------------------------------
            $iframe_code = $fields_values['communication_google_map_iframe_code'];

            // Get custom height --------------------------------------------------------------
            $custom_height_number = $fields_values['communication_google_map_custom_height_number'];
            $custom_height_number_css = sprintf('height:%srem;', $custom_height_number);

            // Background options ------------------------------------------------------------
            $background_image_data = $fields_values['bg_image'];
            $background_color_data = $fields_values['bg_color'];
            $background_color_container_checkbox = $fields_values['outer_container_color_checkbox'];
            $background_parallax_checkbox = $fields_values['bg_parallax_checkbox'];
            $background_color_css = Irantheme_Section_Utils::get_background_color_css($background_color_data);
            $background_image_css = Irantheme_Section_Utils::get_background_image_css($background_image_data);
            $background_parallax_attr = Irantheme_Section_Utils::get_background_parallax_attr($background_parallax_checkbox, $background_image_data);

            // Base color --------------------------------------------------------------
            $base_color = $fields_values['base_color_select'];
            // Set base color class
            $base_color_class = Irantheme_Common_Static::get_base_color_class($base_color);

            // Get section id --------------------------------------------------------------
            $section_id = $fields_values['element_id'];
            $section_id_attribute = '';
            if ($section_id) {
                $section_id_attribute = sprintf('id=%s', $section_id);
            }
?>
            <!-- ============================ Section communication google map ============================ -->
            <section <?php echo esc_attr($section_id_attribute); ?> class="irantheme-section irantheme-communication-google-map-section" <?php echo esc_attr(Irantheme_Page_Builder::get_attr_data_section($data_type, $data_value, $data_page, $data_ajax)); ?> style="<?php echo esc_attr(Irantheme_Section_Service::get_custom_outer_padding_css($fields_values)); ?><?php echo esc_attr(Irantheme_Section_Utils::get_background_outer_container_color_css($background_color_data, $background_color_container_checkbox, $base_color)) ?>">
                <div class="irantheme-communication-google-map <?php echo esc_attr($base_color_class); ?> irantheme-background-jarallax" style="<?php echo esc_attr($background_color_css); ?><?php echo esc_attr($background_image_css); ?>" <?php echo $background_parallax_attr; ?>>
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
                    $container_checkbox = $fields_values['communication_google_map_wrapper_mode_checkbox'];
                    if ($container_checkbox != 'on') :
                    ?>
                        <!-- Inner container -->
                        <div class=" irantheme-container-inner">
                        <?php endif; ?>
                        <!-- Authors post -->
                        <div class="irantheme-communication-google-map-post-wrapper">
                            <div class=" irantheme-communication-google-map-iframe-code" style="<?php echo esc_attr($custom_height_number_css); ?>">
                                <?php echo $iframe_code; ?>
                            </div>
                        </div>
                        <?php
                        if ($container_checkbox != 'on') :
                        ?>
                        </div>
                    <?php
                        endif;
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
