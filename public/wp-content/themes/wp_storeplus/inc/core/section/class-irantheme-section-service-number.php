<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;

if (!class_exists('Irantheme_Section_Service_Number')) {
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
    class Irantheme_Section_Service_Number extends Irantheme_Section
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
                    'title' => 'سرویس عددی',
                    'id' => 'service',
                    'description'            => __('تنظیمات مربوط به محتوای سرویس عددی', self::$static_textdomain),
                    'icon' => 'fa-grid-2-plus',
                    'fields' => array(
                        array(
                            'id'             => 'col_count_radio',
                            'label'            => __('تعداد ستون ها', self::$static_textdomain),
                            'description'    => __('با استفاده از این گزینه شما می توانید تعداد ستون های قابل نمایش در بخش سرویس مستر را مشخص کنید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'thumbnail_status' => true,
                            'options'        => array(
                                'col-2' => array('دو ستون', 'col-2.png'),
                                'col-3' => array('سه ستون', 'col-3.png'),
                                'col-4' => array('چهار ستون', 'col-4.png'),
                                'col-6' => array('شش ستون', 'col-6.png')
                            ),
                            'default'        => $status_default ? 'col-4' : '',
                            'keywords' => array('service', 'col', 'num', 'تعداد', 'سرویس', 'تعداد در هر ردیف'),
                        ),
                    )
                ),
                array(
                    'title' => 'تنظیمات بصری',
                    'id' => 'layout',
                    'description'            => __('تنظیمات بصری مانند : تصویر زمینه، ارتفاع، رنگ ها و...', self::$static_textdomain),
                    'icon' => 'fa-paintbrush-pencil',
                    'fields' => array(
                        array(
                            'id'             => 'alignment_content_radio',
                            'label'            => __('موقعیت محتوای سرویس ها', self::$static_textdomain),
                            'description'    => __('با استفاده از این گزینه می توانید موقعیت محتوای داخل سرویس ها را تعیین کنید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'thumbnail_status' => true,
                            'options'        => array('right' => array('راست چین', 'text-align-right.png'), 'center' => array('وسط چین', 'text-align-center.png'), 'left' => array('چپ چین', 'text-align-left.png')),
                            'default'        => $status_default ? 'right' : '',
                            'keywords' => array('محتوا', 'تنظیم', 'تراز', 'محور', 'alignment', 'float', 'position')
                        ),
                        array(
                            'id'             => 'base_color_select',
                            'label'            => __(
                                'رنگ پایه',
                                self::$static_textdomain
                            ),
                            'description'    => __('برای انتخاب رنگ پایه سرویس از لیست رنگ دلخواه خود را انتخاب کنید.رنگ محتوای سرویس بر اساس این رنگ تنظیم خواهد شد.', self::$static_textdomain),
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
                        array(
                            'id'             => 'box_style_checkbox',
                            'label'            => __(
                                'حالت باکس استایل',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت فعال شدن این گزینه باکسی به دور محتوای هر شی قرار می گیرد.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'off' : '',
                            'keywords' => array('افقی', 'چینش افقی', 'محتوا', 'استایل', 'horizontal', 'line'),
                        ),
                        array(
                            'id'             => 'icon_size_range',
                            'label'            => __(
                                'اندازه آیکون',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه میتوانید اندازه آیکون ها را تغییر داده و کوچک یا بزرگ کنید.', self::$static_textdomain),
                            'type'            => 'range',
                            'min' => 1,
                            'max' => 6,
                            'step' => 0.5,
                            'default'        => $status_default ? 3.5 : '',
                            'placeholder'    => __('Placeholder text', self::$static_textdomain),
                            'keywords' => array('size', 'icon', 'آیکون', 'اندازه'),
                        ),
                    )
                ),
                // Get layout scale fields
                Irantheme_Section_Utils::layout_scale_fields_generator($status_default, array('inner_padding', 'outer_padding', 'radius'), 'on', 2, 2, 'off', 'off', 0, 0, 1),
                // Get background fields
                Irantheme_Section_Utils::background_fields_generator($status_default, array('image', 'color', 'mask', 'parallax'), '', 'off', true, 'off', 'dark', 0.6, 'off'),
                array(
                    'title' => 'امکانات',
                    'id' => 'additional',
                    'description'            => __('فعال یا غیر فعال کردن امکانات مانند: شبکه های اجتمایی، دکمه پیمایش به پایین و ...', self::$static_textdomain),
                    'icon' => 'fa-puzzle-piece',
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
                            $status_default ? Irantheme_Section_Default::get_default_ids_objects(4, 'service_number', $status_default) : '',
                        ),
                        array(
                            'id'             => 'counter_mode_checkbox',
                            'label'            => __(
                                'حالت شمارش',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت فعال شدن این گزینه زمانی که کاربر نزدیک به این بخش می شود، اعداد و ارقام موجود در این سرویس، به صورت خودکار فعال و شروع به شمارش انیمیشنی می کنند.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'on' : '',
                            'keywords' => array('counter', 'count', 'شمارنده', 'شمارش', 'حالت', 'حالت شمارش'),
                        ),
                    )
                ),
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
            $numbers_default = '';
            $chars_default = '';
            $icons_default = '';

            // Check status is deafult or no
            if (is_numeric($index)) {
                $icons = array(
                    'fa-light fa-shield-halved',
                    'fa-light fa-trophy-star',
                    'fa-light fa-truck-fast',
                    'fa-light fa-globe-pointer',
                );

                // List of default titles
                $titles = array(
                    'تعداد مشتریان ما',
                    'پروژه های تکمیل شده',
                    'پروژه های جاری شرکت',
                    'شرکت های زیر مجموعه',
                );

                // List of default numbers
                $numbers = array(
                    163259,
                    12465,
                    3568,
                    184586,
                );

                // List of default chars
                $chars = array(
                    '+',
                    'K',
                    '',
                    '+',
                );

                // Set default value
                $titles_default = $titles[$index];
                $numbers_default = $numbers[$index];
                $chars_default = $chars[$index];
                $icons_default = $icons[$index];
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
                            'id'             => 'number_icon',
                            'label'            => __(
                                'آیکون سرویس',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت نیاز به استفاده از آیکون (نماد) می توانید از این گزینه استفاده نمایید.', self::$static_textdomain),
                            'type'            => 'icon',
                            'default'        => $icons_default,
                            'placeholder'    => __('', self::$static_textdomain),
                        ),
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
                            'max_length' => 3,
                        ),
                        array(
                            'id'             => 'number_heading',
                            'label'            => __(
                                'عنوان',
                                self::$static_textdomain
                            ),
                            'description'    => __('عنوان ارقام مربوط به ارقام را وارد کنید.', self::$static_textdomain),
                            'type'            => 'text',
                            'default'        => $titles_default,
                            'placeholder'    =>  __('متن خود را وارد کنید', self::$static_textdomain),
                        ),
                    )
                ),
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
            // Background options ------------------------------------------------------------
            $background_image_data = $fields_values['bg_image'];
            $background_color_data = $fields_values['bg_color'];
            $background_parallax_checkbox = $fields_values['bg_parallax_checkbox'];
            $background_color_css = Irantheme_Section_Utils::get_background_color_css($background_color_data);
            $background_image_css = Irantheme_Section_Utils::get_background_image_css($background_image_data);
            $background_parallax_attr = Irantheme_Section_Utils::get_background_parallax_attr($background_parallax_checkbox, $background_image_data);

            // Get heading data load style --------------------------------------------------------------
            $heading_data_load_checkbox =
                $fields_values['heading_data_load_checkbox'];
            $heading_data_load_attr = Irantheme_Section_Utils::get_heading_data_load_attr_type($fields_values);
            // Get data load duration
            $heading_data_load_duration = Irantheme_Section_Utils::get_heading_data_load_duration($fields_values);
            // Get aos delay time
            $heading_aos_delay =
                Irantheme_Section_Utils::get_heading_data_load_delay($fields_values);

            // Json post data for post generation ------------------------------------------------------------
            $json_post_array = Irantheme_JSON_Helper::normalize_objects($fields_values['objects_json']);

            // Service number of columns --------------------------------------------------------------
            // Get column post count
            $service_posts_count = $fields_values['col_count_radio'];

            // Base color --------------------------------------------------------------
            $base_color = $fields_values['base_color_select'];
            // Set base color class
            $base_color_class = Irantheme_Common_Static::get_base_color_class($base_color);

            // Icon size --------------------------------------------------------------
            $service_icon_size = $fields_values['icon_size_range'];

            // Box style --------------------------------------------------------------
            $service_box_style_checkbox = $fields_values['box_style_checkbox'];
            $service_box_style_class = '';
            if ($service_box_style_checkbox == 'on') {
                $service_box_style_class = 'irantheme-service-item-box-style';
            }

            // Get section id --------------------------------------------------------------
            $section_id = $fields_values['element_id'];
            $section_id_attribute = '';
            if ($section_id) {
                $section_id_attribute = sprintf('id=%s', $section_id);
            }
?>
            <!-- ============================= Section service number ============================= -->
            <section <?php echo esc_attr($section_id_attribute); ?> class="irantheme-section irantheme-service-number-section" <?php echo esc_attr(Irantheme_Page_Builder::get_attr_data_section($data_type, $data_value, $data_page, $data_ajax)); ?> style="<?php echo esc_attr(Irantheme_Section_Service::get_custom_outer_padding_css($fields_values)); ?>">
                <?php
                // Set wrapper (Container) --------------------------------------------------------------
                $container_checkbox = $fields_values['wrapper_mode_checkbox'];
                ?>
                <div class="irantheme-service-number <?php echo esc_attr($base_color_class); ?> irantheme-section-counter-number-on-scroll irantheme-background-jarallax" style="<?php echo esc_attr($background_color_css); ?><?php echo esc_attr($background_image_css); ?><?php echo esc_attr(Irantheme_Section_Service::get_custom_inner_padding_css($fields_values)); ?>" <?php echo $background_parallax_attr; ?>>

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
                    if ($container_checkbox != 'on') :
                    ?>
                        <div class="irantheme-container-inner">
                        <?php endif; ?>
                        <?php
                        // Service heading --------------------------------------------------------------
                        $service_heading = $fields_values['text_editor_heading'];
                        // Set heading alignment --------------------------------------------------------------
                        $service_heading_alignment = $fields_values['alignment_heading_radio'];
                        ?>
                        <?php if ($container_checkbox == 'on') : ?>
                            <div class="irantheme-wrapper-padding">
                            <?php endif; ?>
                            <?php
                            if ($service_heading != '' && $heading_data_load_checkbox == 'on'):
                            ?>
                                <div class="irantheme-tinymce-heading-aos-holder" <?php echo ($heading_data_load_attr); ?> data-aos-delay="<?php echo ($heading_aos_delay); ?>" data-aos-duration="<?php echo ($heading_data_load_duration); ?>">
                                <?php
                            endif;
                                ?>
                                <?php Irantheme_Common_Static::tinymce_heading($fields_values); ?>
                                <?php if ($service_heading_alignment != '' && $heading_data_load_checkbox == 'on'):
                                ?>
                                </div>
                            <?php
                                endif;
                            ?>
                            <?php if ($container_checkbox == 'on') : ?>
                            </div>
                        <?php endif; ?>
                        <div class="row justify-content-center irantheme-object-group" data-json="<?php echo esc_attr('objects_json'); ?>">
                            <?php
                            // Get all posts master --------------------------------------------------------------
                            // Get data load style --------------------------------------------------------------
                            $data_load_attr = Irantheme_Section_Utils::get_data_load_attr_type($fields_values);
                            // Get data load duration
                            $data_load_duration = Irantheme_Section_Utils::get_data_load_duration($fields_values);
                            // Get aos delay time
                            $aos_delay =
                                Irantheme_Section_Utils::get_data_load_delay($fields_values);

                            // Get border radius --------------------------------------------------------------
                            $border_radius_range = $fields_values['border_radius_range'];
                            $border_radius_style = Irantheme_Section_Utils::get_border_radius_css($border_radius_range);

                            // Check have post author
                            if (!empty($json_post_array)) :
                                // Service custom fields --------------------------------------------------------------
                                foreach ($json_post_array as $object_post) :
                                    // Get icon option
                                    $service_icon = $object_post['fields']['number_icon'];
                                    // Get title options
                                    $service_title = $object_post['fields']['number_title'];
                                    // Get title char option
                                    $service_title_char = $object_post['fields']['number_title_char'];
                                    // Get description option
                                    $service_heading = $object_post['fields']['number_heading'];
                            ?>
                                    <!-- Service item -->
                                    <div class="irantheme-object <?php echo esc_attr(Irantheme_Section_Service::get_bootstrap_class($service_posts_count)); ?>" data-object="<?php echo esc_attr($object_post['object_id']); ?>">
                                        <div class="irantheme-service-item" <?php echo ($data_load_attr); ?> data-aos-delay="<?php echo ($aos_delay); ?>" data-aos-duration="<?php echo ($data_load_duration); ?>">
                                            <?php
                                            // Service content alignment --------------------------------------------------------------
                                            $service_content_alignment = $fields_values['alignment_content_radio'];
                                            ?>
                                            <!-- Wrapper -->
                                            <div class="irantheme-service-item-wrapper <?php echo esc_attr(Irantheme_Section_Service::get_alignment_class($service_content_alignment)); ?> <?php echo esc_attr($service_box_style_class); ?>" style="<?php echo esc_attr($border_radius_style); ?>">
                                                <div class="irantheme-service-item-wrapper-content">
                                                    <!-- Content -->
                                                    <div class="irantheme-service-item-content">
                                                        <?php if ($service_icon) : ?>
                                                            <div class="irantheme-service-item-icon">
                                                                <i class="<?php echo esc_attr($service_icon); ?>" style="font-size:<?php echo esc_attr($service_icon_size); ?>rem;"></i>
                                                            </div>
                                                        <?php endif; ?>
                                                        <?php if ($service_title) : ?>
                                                            <?php
                                                            $service_title_holder = esc_html($service_title);
                                                            // Counter mode --------------------------------------------------------------
                                                            $service_counter_mode_checkbox = $fields_values['counter_mode_checkbox'];
                                                            if ($service_counter_mode_checkbox == 'on') {
                                                                $service_title_holder = sprintf('<span class="irantheme-number-counter-on-scroll" data-counter="%s"></span>', $service_title_holder);
                                                            }
                                                            ?>
                                                            <!-- Title -->
                                                            <h3 class="irantheme-service-item-content-title-number">
                                                                <span class="irantheme-service-item-content-title-char"><?php echo $service_title_char; ?></span>
                                                                <?php echo $service_title_holder; ?>
                                                            </h3>
                                                        <?php endif; ?>
                                                        <div class="irantheme-service-item-content-desc-horizontal">
                                                            <?php if ($service_heading) : ?>
                                                                <!-- Description -->
                                                                <h4 class="irantheme-service-item-content-desc-number"><?php echo esc_html($service_heading); ?></h4>
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
                                    // Increase aos delay for next item
                                    $aos_delay += 200;
                                endforeach;
                            endif;
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
