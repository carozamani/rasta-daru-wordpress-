<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;

use Irantheme_Dev\Irantheme_Common_Static;
use Irantheme_Dev\Irantheme_Page_Builder;



if (!class_exists('Irantheme_Section_Showcase_Service')) {
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
    class Irantheme_Section_Showcase_Service extends Irantheme_Section
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
            $heading_content = '<span style="font-size:0.8em;background-color:#0f0f0f;color:white;">Made for smarter decisions</span><h2 style="font-size:4em;font-weight:bold;">رشد کسب و کار شما با استورپلاس</h2><p font-size:1em;>نگران نباشید! با چند سوال کار را شروع می کنیم.</p>';
            $settings = array(
                Irantheme_Section_Utils::heading_title_settings_generator($status_default, 'none', false, false, $heading_content, 'on', 'مشاوره با کارشناسان', '#', 'fa-light fa-phone', 'primary', 'off', 'مشاوره رایگان', '#', 'fa-light fa-headphones', 'transparent', 'rectangle', 'right', 'off', '', 3, 3, 'large'),
                array(
                    'title' => 'تنظیمات بصری',
                    'id' => 'layout',
                    'description'            => __('تنظیمات بصری ویترین مانند : تصویر زمینه، ارتفاع ویترین و...', self::$static_textdomain),
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
                            'default'        => $status_default ? Irantheme_Section_Default::get_default_ids_objects(3, 'showcase_service', $status_default) : '',
                        ),
                        array(
                            'id'             => 'base_color_select',
                            'label'            => __(
                                'رنگ پایه',
                                self::$static_textdomain
                            ),
                            'description'    => __('برای انتخاب رنگ پایه ویترین از لیست رنگ دلخواه خود را انتخاب کنید.رنگ محتوای ویترین بر اساس این رنگ تنظیم خواهد شد.', self::$static_textdomain),
                            'type'            => 'select',
                            'options'        => array('white' => 'سفید', 'dark' => 'مشکی'),
                            'default'        => $status_default ? 'white' : '',
                            'keywords' => array('color', 'background', 'پس زمینه', 'رنگ'),
                        ),
                        array(
                            'id'             => 'service_content_alignment_radio',
                            'label'            => __(
                                'موقعیت سرویس ها',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید موقعیت محتوای سرویس ها در ویترین را تعیین کنید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'thumbnail_status' => true,
                            'options'        => array('right' => array('راست چین', 'text-align-right.png'), 'center' => array('وسط چین', 'text-align-center.png'), 'left' => array('چپ چین', 'text-align-left.png')),
                            'default'        => $status_default ? 'right' : '',
                            'keywords' => array('محتوا', 'تنظیم', 'تراز', 'محور', 'alignment', 'float', 'position')
                        ),
                        array(
                            'id'             => 'wrapper_mode_checkbox',
                            'label'            => __(
                                'اتصال به نگهدارنده خارجی سایت',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت غیر فعال بودن این گزینه بخش های (سکشن ها) سایت از نگهدارنده داخلی سایت استفاده می کنند، شما با فعال کردن این گزینه می توانید نگهدارنده این بخش را به نگهدارنده خارجی سایت تغییر دهید.در این صورت، این بخش از کناره ها به اندازه نگهدارنده خارجی سایت بزرگ شده وبه آن می چسبد.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'on' : '',
                            'keywords' => array('hieght', 'ارتفاع', 'fullscreen', 'تمام صفحه', 'سرویس'),
                        ),
                    ),
                ),
                array(
                    'title' => 'امکانات',
                    'id' => 'additional',
                    'description'            => __('دیگر امکانات اضافی', self::$static_textdomain),
                    'icon' => 'fa-puzzle-piece',
                    'fields' => array(
                        array(
                            'id'             => 'counter_mode_checkbox',
                            'label'            => __(
                                'حالت شمارش',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت فعال شدن این گزینه زمانی که کاربر نزدیک به این بخش می شود، اعداد و ارقام موجود در این سرویس، به صورت خودکار فعال و شروع به شمارش انیمیشنی می کنند.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'off' : '',
                            'keywords' => array('counter', 'count', 'شمارنده', 'شمارش', 'حالت', 'حالت شمارش'),
                        ),
                    )
                ),
                // Get layout scale fields
                Irantheme_Section_Utils::layout_scale_fields_generator($status_default, array('radius', 'inner_padding', 'outer_padding', 'fullscreen'), 'off', 3, 3, 'on', 'off', 0, 0, 0),
                // Get background fields
                Irantheme_Section_Utils::background_fields_generator($status_default, array('image', 'color', 'mask', 'parallax', 'outer_container_color'), $status_default === 'media' ? Irantheme_Common_Static::get_file_url_media_library('https://irantheme.com/wp-content/uploads/2026/02/4vem9r1n.webp') : '', 'off', true, 'on', 'white', 0.3, 'on'),
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
            $titles_default = '';
            $numbers_default = '';
            $chars_default = '';

            // Check status is deafult or no
            if (is_numeric($index)) {
                // List of default titles
                $titles = array(
                    'مشتریان راضی ما',
                    'رشد اولیه کسب',
                    'بازدهی سرمایه گذاری',
                );

                // List of default numbers
                $numbers = array(
                    5,
                    40,
                    3,
                );

                // List of default chars
                $chars = array(
                    'K+',
                    '٪+',
                    'x',
                );

                // Set default value
                $titles_default = $titles[$index];
                $numbers_default = $numbers[$index];
                $chars_default = $chars[$index];
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
                            'id'             => 'number_title',
                            'label'            => __(
                                'اعداد یا ارقام',
                                self::$static_textdomain

                            ),
                            'description'    => __('ارقام دلخواه خود را اینجا وارد کنید.'),
                            'type'            => 'text',
                            'default'        => $numbers_default,
                            'placeholder'    => __('برای مثال : ۱۲۳۴۵', self::$static_textdomain),
                        ),
                        array(
                            'id'             => 'number_title_char',
                            'label'            => __(
                                'یک یا دو کاراکتر اضافی',
                                self::$static_textdomain

                            ),
                            'description'    => __('با استفاده از این ویژگی شما می توانید تنها یک یا دو کاراکتر یا نماد به محتوای عددی خود اضافه کنید. پیشنهاد می کنیم از حروف +, -, K (هزار), M (میلیون), S (ثانیه), cm (سانتی متر), kg (کیلوگرم) یا ... استفاده کنید.'),
                            'type'            => 'text',
                            'default'        => $chars_default,
                            'placeholder'    => __('برای مثال : + یا - یا M یا K', self::$static_textdomain),
                            'max_length' => 2,
                        ),
                        array(
                            'id'             => 'number_desc',
                            'label'            => __(
                                'توضیحات',
                                self::$static_textdomain
                            ),
                            'description'    => __('توضیحات مربوط به ارقام را وارد کنید.', self::$static_textdomain),
                            'type'            => 'text',
                            'default'        => $titles_default,
                            'placeholder'    =>  __('متن خود را وارد کنید', self::$static_textdomain),
                        ),
                    )
                )
            );
            return $object_fields;
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

            // Background options ------------------------------------------------------------
            $background_image_data = $fields_values['bg_image'];
            $background_color_data = $fields_values['bg_color'];
            $background_color_container_checkbox = $fields_values['outer_container_color_checkbox'];
            $background_parallax_checkbox = $fields_values['bg_parallax_checkbox'];
            $background_color_css = Irantheme_Section_Utils::get_background_color_css($background_color_data);
            $background_image_css = Irantheme_Section_Utils::get_background_image_css($background_image_data);
            $background_parallax_attr = Irantheme_Section_Utils::get_background_parallax_attr($background_parallax_checkbox, $background_image_data);

            // Get data load style --------------------------------------------------------------
            $data_load_attr = Irantheme_Section_Utils::get_data_load_attr_type($fields_values);
            // Get data load duration
            $data_load_duration = Irantheme_Section_Utils::get_data_load_duration($fields_values);
            // Get aos delay time
            $aos_delay = Irantheme_Section_Utils::get_data_load_delay($fields_values);

            // Get tinymce text editor content
            $tinymce_text_content = $fields_values['text_editor_heading'];

            // Get heading data load style --------------------------------------------------------------
            $heading_data_load_checkbox = $fields_values['heading_data_load_checkbox'];
            $heading_data_load_attr = Irantheme_Section_Utils::get_heading_data_load_attr_type($fields_values);
            // Get data load duration
            $heading_data_load_duration = Irantheme_Section_Utils::get_heading_data_load_duration($fields_values);
            // Get aos delay time
            $heading_aos_delay = Irantheme_Section_Utils::get_heading_data_load_delay($fields_values);

            // Set content alignment --------------------------------------------------------------
            $content_alignment_radio =
                $fields_values['alignment_heading_radio'];
            $content_alignment_class = '';
            // Check alignment position
            if ($content_alignment_radio == 'center') {
                // Set alignment class center
                $content_alignment_class = 'irantheme-content-center';
            } else if ($content_alignment_radio == 'right') {
                // Set alignment class right
                $content_alignment_class = 'irantheme-content-right';
            } else if ($content_alignment_radio == 'left') {
                // Set alignment class left
                $content_alignment_class = 'irantheme-content-left';
            }

            // Get border radius --------------------------------------------------------------
            $border_radius_range = $fields_values['border_radius_range'];
            $border_radius_style = Irantheme_Section_Utils::get_border_radius_css($border_radius_range);

            // Set base color (Dependents of mask) --------------------------------------------------------------
            $base_color = $fields_values['base_color_select'];

            // Get section id --------------------------------------------------------------
            $section_id = $fields_values['element_id'];
            $section_id_attribute = '';
            if ($section_id) {
                $section_id_attribute = sprintf('id=%s', $section_id);
            }

            // Section transform to up and down --------------------------------------------------------------
            $section_transfrom_values = Irantheme_Section_Utils::get_transform_values_arrays($data_page, $fields_values, true, false, false);
            $section_transform_extra_css = $section_transfrom_values['overflow'];
            $section_transform_translate_css = $section_transfrom_values['css'];
?>
            <!-- Showcase service -->
            <section <?php echo esc_attr($section_id_attribute); ?> class="irantheme-section irantheme-showcase-service-section irantheme-showcase-common-section" <?php echo esc_attr(Irantheme_Page_Builder::get_attr_data_section($data_type, $data_value, $data_page, $data_ajax)); ?> style="<?php echo esc_attr(Irantheme_Section_Service::get_custom_outer_padding_css($fields_values)); ?><?php echo esc_attr(Irantheme_Section_Utils::get_background_outer_container_color_css($background_color_data, $background_color_container_checkbox, $base_color)) ?><?php echo esc_attr($section_transform_extra_css); ?>">
                <?php
                // Set wrapper (Container) --------------------------------------------------------------
                $container_checkbox = $fields_values['wrapper_mode_checkbox'];
                if ($container_checkbox != 'on') :
                ?>
                    <div class="irantheme-container-inner">
                    <?php endif; ?>
                    <div class="irantheme-showcase-service irantheme-section-counter-number-on-scroll <?php echo esc_attr($base_color); ?> <?php echo esc_attr(Irantheme_Section_Service::get_fullscreen_height_class($fields_values)); ?> irantheme-background-jarallax" style="<?php echo esc_attr($background_color_css); ?><?php echo esc_attr($background_image_css); ?><?php echo esc_attr(Irantheme_Section_Service::get_custom_inner_padding_css($fields_values)); ?><?php echo esc_attr($border_radius_style); ?><?php echo esc_attr($section_transform_translate_css); ?>" <?php echo $background_parallax_attr; ?>>

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
                            <div class="irantheme-align-services">
                                <div class="<?php echo esc_attr($content_alignment_class); ?>">
                                    <?php
                                    if ($tinymce_text_content != '' && $heading_data_load_checkbox == 'on'):
                                    ?>
                                        <div class="irantheme-tinymce-heading-aos-holder" <?php echo ($heading_data_load_attr); ?> data-aos-delay="<?php echo ($heading_aos_delay); ?>" data-aos-duration="<?php echo ($heading_data_load_duration); ?>">
                                        <?php
                                    endif;
                                    // Get showcase content --------------------------------------------------------------
                                    Irantheme_Common_Static::tinymce_heading($fields_values, '');
                                    if ($tinymce_text_content != '' && $heading_data_load_checkbox == 'on'):
                                        ?>
                                        </div>
                                    <?php
                                    endif;
                                    ?>
                                </div>
                                <?php
                                // Service content alignment --------------------------------------------------------------
                                $service_content_alignment = $fields_values['service_content_alignment_radio'];
                                $alignment_row_class = '';
                                if ($service_content_alignment == 'right') {
                                    $alignment_row_class = 'justify-content-start';
                                } elseif ($service_content_alignment == 'left') {
                                    $alignment_row_class = 'justify-content-end';
                                } elseif ($service_content_alignment == 'center') {
                                    $alignment_row_class = 'justify-content-center';
                                }
                                ?>
                                <!-- Services -->
                                <div class="irantheme-object-group irantheme-showcase-services <?php echo esc_attr($alignment_row_class); ?>" data-json="<?php echo esc_attr('objects_json'); ?>">
                                    <?php
                                    // Service custom fields --------------------------------------------------------------
                                    foreach ($json_post_array as $object_post) {
                                        // Get title options
                                        $service_title = $object_post['fields']['number_title'];
                                        // Get title char option
                                        $service_title_char = $object_post['fields']['number_title_char'];
                                        $service_title_char_tag = '<span class="irantheme-service-item-content-title-char">' . $service_title_char . '</span>';
                                        // Get description option
                                        $service_desc = $object_post['fields']['number_desc'];
                                    ?>
                                        <!-- Service item -->
                                        <div class="irantheme-object" data-object="<?php echo esc_attr($object_post['object_id']); ?>">
                                            <div class="irantheme-service-item" style="<?php echo esc_attr($border_radius_style); ?>" <?php echo ($data_load_attr); ?> data-aos-delay="<?php echo ($aos_delay); ?>" data-aos-duration="<?php echo ($data_load_duration); ?>">
                                                <!-- Wrapper -->
                                                <div class="irantheme-service-item-wrapper" style="padding:0;">
                                                    <div class="irantheme-service-item-wrapper-content">
                                                        <!-- Content -->
                                                        <div class="irantheme-service-item-content <?php echo esc_attr(Irantheme_Section_Service::get_alignment_class($service_content_alignment)); ?> <?php echo esc_attr(Irantheme_Section_Service::get_flex_column_alignment_class($service_content_alignment)); ?>">
                                                            <?php if ($service_title) : ?>
                                                                <?php
                                                                $service_title_holder = $service_title;
                                                                // Counter mode --------------------------------------------------------------
                                                                $service_counter_mode_checkbox = $fields_values['counter_mode_checkbox'];
                                                                if ($service_counter_mode_checkbox == 'on') {
                                                                    $service_title_holder = sprintf('<span class="irantheme-number-counter-on-scroll" data-counter="%s"></span>', esc_html($service_title_holder));
                                                                }
                                                                ?>
                                                                <!-- Title -->
                                                                <h3 class="irantheme-service-item-content-number">
                                                                    <span class="irantheme-service-item-content-number-char"><?php echo $service_title_char; ?></span>
                                                                    <?php echo $service_title_holder; ?>
                                                                </h3>
                                                            <?php endif; ?>
                                                            <div class="irantheme-service-item-content-desc">
                                                                <?php if ($service_desc) : ?>
                                                                    <!-- Description -->
                                                                    <p class="irantheme-service-item-content-desc-heading"><?php echo esc_html($service_desc); ?></p>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                            Irantheme_Page_Builder::get_hidden_require_object_buttons($data_page);
                                            ?>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                    <?php
                                    Irantheme_Page_Builder::get_hidden_require_object_group_buttons($data_page);
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    if ($container_checkbox != 'on') :
                    ?>
                    </div>
                <?php
                    endif;
                ?>
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
