<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;

if (!class_exists('Irantheme_Section_Service_Note_Slider')) {
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
    class Irantheme_Section_Service_Note_Slider extends Irantheme_Section
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
            $settings = array(
                array(
                    'title' => 'تنظیمات بصری',
                    'id' => 'slider_layout',
                    'description'            => __('تنظیمات بصری مانند : تصویر زمینه، ارتفاع، رنگ ها و...', self::$static_textdomain),
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
                            'default'        =>
                            $status_default ? Irantheme_Section_Default::get_default_ids_objects(3, 'service_note_slider', $status_default) : '',
                        ),
                        array(
                            'id'             => 'base_color_select',
                            'label'            => __(
                                'رنگ پایه',
                                self::$static_textdomain
                            ),
                            'description'    => __('برای انتخاب رنگ پایه سرویس از لیست رنگ دلخواه خود را انتخاب کنید.رنگ محتواو پس زمینه سرویس بر اساس این رنگ تنظیم خواهد شد.', self::$static_textdomain),
                            'type'            => 'select',
                            'options'        => array('white' => 'سفید', 'dark' => 'مشکی'),
                            'default'        => $status_default ? 'white' : '',
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
                            'default'        => $status_default ? 'off' : '',
                            'keywords' => array('hieght', 'ارتفاع', 'fullscreen', 'تمام صفحه', 'سرویس'),
                        ),
                    )
                ),
                // Get layout scale fields
                Irantheme_Section_Utils::layout_scale_fields_generator($status_default, array('radius', 'inner_padding', 'outer_padding'), 'on', 2, 2, 'off', 'off', 0, 0, 1),
                // Get background fields
                Irantheme_Section_Utils::background_fields_generator($status_default, array('image', 'color', 'image2'), $status_default === 'media' ? Irantheme_Common_Static::get_file_url_media_library('https://irantheme.com/wp-content/uploads/2026/02/r97khffn.webp') : '', 'off', true, 'on', 'dark', 0.6, 'on', 'off', Irantheme_Common_Static::get_file_url_media_library('https://irantheme.com/wp-content/uploads/2026/02/snivrygg.webp')),
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
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        public static function object_fields($index, $status_default = false)
        {
            $titles_default = '';
            $description_default = '';

            // Check status is deafult or no
            if (is_numeric($index)) {
                // List of default titles
                $titles = array(
                    'بازاریابی دیجیتال',
                    'بایگاهی هوشمند',
                    'توسعه پایدار',
                );

                // List of default small desc
                $description = 'لورم ایپسوم متن ساختگی با تولید سادگی';

                // Set default value
                $titles_default = $titles[$index];
                $description_default = $description;
            }

            // Custom fields
            $object_fields = array(
                array(
                    'title' => 'مقادیر عنصر',
                    'id' => 'edit_object',
                    'description'            => __('تنظیم مقادیر عنصر', self::$static_textdomain),
                    'icon' => 'fa-pen-to-square',
                    'fields' => array(
                        array(
                            'id'             => 'note_heading',
                            'label'            => __(
                                'عنوان',
                                self::$static_textdomain
                            ),
                            'description'    => __('عنوان را وارد کنید.', self::$static_textdomain),
                            'type'            => 'text',
                            'default'        => $titles_default,
                            'placeholder'    =>  __('متن خود را وارد کنید', self::$static_textdomain),
                        ),
                        array(
                            'id'             => 'note_desc',
                            'label'            => __(
                                'توضیحات',
                                self::$static_textdomain
                            ),
                            'description'    => __('توضیحات را وارد کنید.', self::$static_textdomain),
                            'type'            => 'textarea',
                            'default'        => $description_default,
                            'placeholder'    =>  __('متن خود را وارد کنید', self::$static_textdomain),
                        ),
                        array(
                            'id'             => 'note_link',
                            'label'            => __(
                                'لینک',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت نیاز لینک سرویس را وارد کنید.', self::$static_textdomain),
                            'type'            => 'text',
                            'default'        => '#',
                            'placeholder'    =>  __('متن خود را وارد کنید', self::$static_textdomain),
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
            $background_image_data2 = $fields_values['bg_image2'];
            $background_color_data = $fields_values['bg_color'];
            $background_color_css = Irantheme_Section_Utils::get_background_color_css($background_color_data);
            $background_image_css = Irantheme_Section_Utils::get_background_image_css($background_image_data, true);
            $background_image_css2 = Irantheme_Section_Utils::get_background_image_css($background_image_data2, true);
            // Get data load style --------------------------------------------------------------
            $data_load_attr = Irantheme_Section_Utils::get_data_load_attr_type($fields_values);
            // Get data load duration
            $data_load_duration = Irantheme_Section_Utils::get_data_load_duration($fields_values);
            // Get aos delay time
            $aos_delay = Irantheme_Section_Utils::get_data_load_delay($fields_values);

            // Base color --------------------------------------------------------------
            $base_color = $fields_values['base_color_select'];
            // Set base color class
            $base_color_class = Irantheme_Common_Static::get_base_color_class($base_color);

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
            <!-- ============================= Section service note slider ============================= -->
            <section <?php echo esc_attr($section_id_attribute); ?> class="irantheme-section irantheme-service-note-slider-section" <?php echo esc_attr(Irantheme_Page_Builder::get_attr_data_section($data_type, $data_value, $data_page, $data_ajax)); ?> style="<?php echo esc_attr(Irantheme_Section_Service::get_custom_outer_padding_css($fields_values)); ?>">
                <?php
                // Set wrapper (Container) --------------------------------------------------------------
                $container_checkbox = $fields_values['wrapper_mode_checkbox'];
                ?>
                <div class="irantheme-service-note-slider <?php echo esc_attr($base_color_class); ?>" style="<?php echo esc_attr($background_color_css); ?><?php echo esc_attr(Irantheme_Section_Service::get_custom_inner_padding_css($fields_values)); ?>">
                    <?php
                    if ($container_checkbox != 'on') :
                    ?>
                        <div class=" irantheme-container-inner">
                        <?php endif; ?>
                        <div class="row justify-content-center" style="align-items: center;margin-right:0;margin-left:0;">
                            <div class="col-lg-4 irantheme-semi-image-holder" style="margin-bottom:0;padding:0;display:flex;justify-content:center;" <?php echo ($data_load_attr); ?> data-aos-delay="<?php echo ($aos_delay); ?>" data-aos-duration="<?php echo ($data_load_duration); ?>">
                                <?php if ($background_image_css) : ?>
                                    <!-- Image -->
                                    <?php
                                    echo wp_get_attachment_image(
                                        $background_image_data,
                                        'medium_large',
                                        false,
                                        [
                                            'class' => 'irantheme-semi-image',
                                            'alt' => __('تصویر سرویس', self::$static_textdomain),
                                            'loading' => 'lazy',
                                            'style' => esc_attr($border_radius_style),
                                        ]
                                    );
                                    ?>
                                <?php else : ?>
                                    <?php
                                    // Empty thumbnail
                                    Irantheme_Section_Service::empty_thumbnail($base_color);
                                    ?>
                                <?php endif; ?>
                            </div>
                            <?php
                            // Increase aos delay for next item
                            $aos_delay += 200;
                            $counter = 0;
                            ?>
                            <div class="col-lg-4" style="margin-bottom:0;z-index:9;" <?php echo ($data_load_attr); ?> data-aos-delay="<?php echo ($aos_delay); ?>" data-aos-duration="<?php echo ($data_load_duration); ?>">
                                <div class="swiper irantheme-section-service-note-slider-swiper" style="<?php echo esc_attr($border_radius_style); ?>">
                                    <!-- Content -->
                                    <div class="irantheme-object-group swiper-wrapper irantheme-section-service-note-slider-wrapper" data-json="<?php echo esc_attr('objects_json'); ?>">
                                        <?php
                                        // Service custom fields --------------------------------------------------------------
                                        foreach ($json_post_array as $object_post):
                                            // Get title options
                                            $service_title = $object_post['fields']['note_heading'];
                                            // Get description option
                                            $service_desc = $object_post['fields']['note_desc'];
                                            // Get link option
                                            $service_link = $object_post['fields']['note_link'];
                                            // Calculation count of sliders
                                            $counter++;
                                            $counter_string = '';
                                            if ($counter < 10) {
                                                $counter_string = '0' . $counter;
                                            } else {
                                                $counter_string = $counter;
                                            }
                                        ?>
                                            <div class="irantheme-object irantheme-section-service-note-slider-item swiper-slide" data-object="<?php echo esc_attr($object_post['object_id']); ?>" data-json="<?php echo esc_attr('objects_json'); ?>">
                                                <span class="irantheme-section-service-note-slider-item-counter"><?php echo $counter_string; ?></span>
                                                <div class="irantheme-section-service-note-slider-item-content">
                                                    <h3 class="irantheme-section-service-note-slider-item-content-heading"><?php echo esc_html($service_title); ?></h3>
                                                    <p class="irantheme-section-service-note-slider-item-content-description"><?php echo esc_html($service_desc); ?></p>
                                                </div>
                                                <?php if ($service_link): ?>
                                                    <a href="<?php echo esc_url($service_link); ?>" class="irantheme-section-service-note-slider-item-content-link" target="_blank" rel="noopener noreferrer">
                                                        <i class="fa-light fa-arrow-up-left"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <?php
                                                Irantheme_Page_Builder::get_hidden_require_object_buttons($data_page);
                                                ?>
                                            </div>
                                        <?php
                                        endforeach;
                                        ?>
                                        <?php
                                        Irantheme_Page_Builder::get_hidden_require_object_group_buttons($data_page);
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                            // Increase aos delay for next item
                            $aos_delay += 200;
                            ?>
                            <div class="col-lg-4 irantheme-semi-image-holder" style="margin-bottom: 0;padding:0;display:flex;justify-content:center;" <?php echo ($data_load_attr); ?> data-aos-delay="<?php echo ($aos_delay); ?>" data-aos-duration="<?php echo ($data_load_duration); ?>">
                                <?php if ($background_image_css2) : ?>
                                    <!-- Image -->
                                    <?php
                                    echo wp_get_attachment_image(
                                        $background_image_data2,
                                        'medium_large',
                                        false,
                                        [
                                            'class' => 'irantheme-semi-image',
                                            'alt' => __('تصویر سرویس', self::$static_textdomain),
                                            'loading' => 'lazy',
                                            'style' => esc_attr($border_radius_style),
                                        ]
                                    );
                                    ?>
                                <?php else : ?>
                                    <?php
                                    // Empty thumbnail
                                    Irantheme_Section_Service::empty_thumbnail($base_color);
                                    ?>
                                <?php endif; ?>
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
