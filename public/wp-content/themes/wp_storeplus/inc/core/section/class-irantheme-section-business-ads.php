<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;

if (!class_exists('Irantheme_Section_Business_Ads')) {
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
    class Irantheme_Section_Business_Ads extends Irantheme_Section
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
                Irantheme_Section_Utils::heading_title_settings_generator($status_default, 'none', false, false, $heading_content),
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
                            'default'        => Irantheme_Section_Default::get_default_ids_objects(1, 'business_ads', $status_default),
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
                            'default'        => 'white',
                            'keywords' => array('color', 'background', 'پس زمینه', 'رنگ'),
                        ),
                        array(
                            'id'             => 'wrapper_mode_checkbox',
                            'label'            => __(
                                'اتصال به نگهدارنده خارجی سایت',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت غیر فعال بودن این گزینه بخش های (سکشن ها) سایت از نگهدارنده داخلی سایت استفاده می کنند، شما با فعال کردن این گزینه می توانید نگهدارنده این بخش را به نگهدارنده خارجی سایت تغییر دهید.در این صورت، این بخش از کناره ها به اندازه نگهدارنده خارجی سایت بزرگ شده وبه آن می چسبد.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => 'off',
                            'keywords' => array('نگهدارنده خارجی', 'اتصال', 'fullscreen', 'تمام صفحه', 'نگه دارنده', 'نگهدارنده', 'container', 'wrapper'),
                        ),
                    )
                ),
                // Get layout scale fields
                Irantheme_Section_Utils::layout_scale_fields_generator($status_default, array('radius', 'inner_padding', 'outer_padding'), 'on', 2, 2, 'off', 'off', 0, 0, 1),
                // Get background fields
                Irantheme_Section_Utils::background_fields_generator($status_default, array('image', 'color', 'mask', 'parallax'), '', 'off', true, 'off', 'dark', 0.6, 'off'),
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
            $content_default = '';

            // Check status is deafult or no
            if (is_numeric($index)) {
                // Default content
                $content = array(
                    '<a href="#"><img src="https://irantheme.com/wp-content/uploads/2026/02/728x90.png" alt="تبلیغات" /></a>',
                );

                $content_default = $content[$index];
            }

            // Custom fields
            $object_fields = array(
                array(
                    'title' => 'تنظیم بنر',
                    'id' => 'edit_object',
                    'description'            => __('تنظیم مقادیر بنر', self::$static_textdomain),
                    'icon' => 'fa-folder-grid',
                    'fields' => array(
                        array(
                            'id'             => 'code',
                            'label'            => __(
                                'کد تبلیغاتی',
                                self::$static_textdomain
                            ),
                            'description'    => __('کد HTML تبلیغاتی خود را وارد نمایید.', self::$static_textdomain),
                            'type'            => 'textarea',
                            'default'        => $content_default,
                            'placeholder'    => __('', self::$static_textdomain),
                        ),
                    )
                ),
                // Get background fields
                Irantheme_Section_Utils::background_fields_generator(true, array('color'), '', 'off', false),
                array(
                    'title' => 'تنظیمات بصری',
                    'id' => 'layout_object',
                    'description'            => __('تنظیمات بصری مانند :ارتفاع، ستون بندی و...', self::$static_textdomain),
                    'icon' => 'fa-paintbrush-pencil',
                    'fields' => array(
                        array(
                            'id'             => 'col_count_radio',
                            'label'            => __('نسبت بنر به ردیف', self::$static_textdomain),
                            'description'    => __('با استفاده از این گزینه ها شما می توانید بزرگی هر بنر در یک ردیف را به نسبت ۱۲ ستون تنظیم نمایید.', self::$static_textdomain),
                            'type'            => 'select',
                            'options'        => array(
                                'col-1' => 'یک ستون',
                                'col-2' => 'دو ستون',
                                'col-3' => 'سه ستون',
                                'col-4' => 'چهار ستون',
                                'col-5' => 'پنج ستون',
                                'col-6' => 'شش ستون',
                                'col-7' => 'هفت ستون',
                                'col-8' => 'هشت ستون',
                                'col-9' => 'نه ستون',
                                'col-10' => 'ده ستون',
                                'col-11' => 'یازده ستون',
                                'col-12' => 'دوازده ستون',
                            ),
                            'default'        => 'col-12',
                        ),
                    )
                )
            );
            return $object_fields;
        }


        /**
         * Render elements
         * @param string,string,string,string,mixed
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function render($fields_values, $data_type, $data_value, $data_page, $data_ajax)
        {
            // Json post data for post generation ------------------------------------------------------------
            $json_post_array = Irantheme_JSON_Helper::normalize_objects($fields_values['objects_json']);

            // Background options ------------------------------------------------------------
            $background_image_data = $fields_values['bg_image'];
            $background_color_data = $fields_values['bg_color'];
            $background_parallax_checkbox = $fields_values['bg_parallax_checkbox'];
            $background_color_css = Irantheme_Section_Utils::get_background_color_css($background_color_data);
            $background_image_css = Irantheme_Section_Utils::get_background_image_css($background_image_data);
            $background_parallax_attr = Irantheme_Section_Utils::get_background_parallax_attr($background_parallax_checkbox, $background_image_data);

            // Get data load style --------------------------------------------------------------
            $data_load_attr = Irantheme_Section_Utils::get_data_load_attr_type($fields_values);
            // Get data load duration
            $data_load_duration = Irantheme_Section_Utils::get_data_load_duration($fields_values);
            // Get aos delay time
            $aos_delay =
                Irantheme_Section_Utils::get_data_load_delay($fields_values);

            // Get heading data load style --------------------------------------------------------------
            $heading_data_load_checkbox = $fields_values['heading_data_load_checkbox'];
            $heading_data_load_attr = Irantheme_Section_Utils::get_heading_data_load_attr_type($fields_values);
            // Get data load duration
            $heading_data_load_duration = Irantheme_Section_Utils::get_heading_data_load_duration($fields_values);
            // Get aos delay time
            $heading_aos_delay =
                Irantheme_Section_Utils::get_heading_data_load_delay($fields_values);

            // Get section heading --------------------------------------------------------------
            $tinymce_heading = $fields_values['text_editor_heading'];

            // Get border radius --------------------------------------------------------------
            $border_radius_range = $fields_values['border_radius_range'];
            $border_radius_style = Irantheme_Section_Utils::get_border_radius_css($border_radius_range);

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
            <!-- ============================= Section business authors post ============================= -->
            <section <?php echo esc_attr($section_id_attribute); ?> class="irantheme-section irantheme-business-ads-section" <?php echo esc_attr(Irantheme_Page_Builder::get_attr_data_section($data_type, $data_value, $data_page, $data_ajax)); ?> style="<?php echo esc_attr(Irantheme_Section_Service::get_custom_outer_padding_css($fields_values)); ?>">
                <div class="irantheme-business irantheme-business-ads <?php echo esc_attr($base_color_class); ?> irantheme-background-jarallax" style="<?php echo esc_attr($background_color_css); ?><?php echo esc_attr($background_image_css); ?><?php echo esc_attr(Irantheme_Section_Service::get_custom_inner_padding_css($fields_values)); ?>" <?php echo $background_parallax_attr; ?>>

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
                        <?php
                        if ($tinymce_heading != '' && $heading_data_load_checkbox == 'on'):
                        ?>
                            <div class="irantheme-tinymce-heading-aos-holder" <?php echo ($heading_data_load_attr); ?> data-aos-delay="<?php echo ($heading_aos_delay); ?>" data-aos-duration="<?php echo ($heading_data_load_duration); ?>">
                            <?php
                        endif;
                            ?>
                            <?php
                            // Get tinymce content --------------------------------------------------------------
                            Irantheme_Common_Static::tinymce_heading($fields_values);
                            if ($tinymce_heading != '' && $heading_data_load_checkbox == 'on'):
                            ?>
                            </div>
                        <?php
                            endif;
                        ?>

                        <?php
                        // Set column mode --------------------------------------------------------------
                        $grid_mode_wrapper_class = '';
                        $grid_mode_row  = 'row justify-content-center';
                        ?>
                        <!-- Authors post -->
                        <div class="irantheme-business-ads-post-wrapper <?php echo esc_attr($grid_mode_wrapper_class); ?>">
                            <div class="irantheme-object-group <?php echo esc_attr($grid_mode_row); ?>" data-json="<?php echo esc_attr('objects_json'); ?>">
                                <?php
                                foreach ($json_post_array as $object_post) :
                                    // Get post meta --------------------------------------------------------------
                                    // Background options ------------------------------------------------------------
                                    $background_color_data = $object_post['fields']['bg_color'];
                                    $background_color_css = Irantheme_Section_Utils::get_background_color_css($background_color_data);

                                    // Get columns count --------------------------------------------------------------
                                    $count_columns = $object_post['fields']['col_count_radio'];
                                    $bootstrap_column_class = Irantheme_Section_Service::get_bootstrap_raw_class($count_columns);

                                    // Get ads code
                                    $ads_code = $object_post['fields']['code'];
                                ?>
                                    <div class="irantheme-object <?php echo esc_attr($bootstrap_column_class); ?>" data-object="<?php echo esc_attr($object_post['object_id']); ?>">
                                        <!-- Post item -->
                                        <div class=" irantheme-business-ads-post-item" style="<?php echo esc_attr($border_radius_style); ?><?php echo esc_attr($background_color_css); ?>" <?php echo ($data_load_attr); ?> data-aos-delay="<?php echo ($aos_delay); ?>" data-aos-duration="<?php echo ($data_load_duration); ?>">
                                            <?php echo $ads_code; ?>
                                        </div>
                                        <?php
                                        Irantheme_Page_Builder::get_hidden_require_object_buttons($data_page);
                                        ?>
                                    </div>
                                <?php
                                    // Increase aos delay for next item
                                    $aos_delay += 200;
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
