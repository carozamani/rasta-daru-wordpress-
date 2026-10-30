<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;

if (!class_exists('Irantheme_Section_Service_Semi_Number')) {
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
    class Irantheme_Section_Service_Semi_Number extends Irantheme_Section
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
            $heading_content = '<span style="font-size: 1em;">ــــــــــــــــــــــــ چقدر موفق هستیم؟</span>
<h2 style="font-size: 2em;"><span style="font-size: 1.2em;"><strong>اندک آماری از خدمات</strong></span></h2>';
            $settings = array(
                Irantheme_Section_Utils::heading_title_settings_generator($status_default, 'none', false, 'right', $heading_content, 'off', '', '', '', 'off', 'off', '', '', '', 'off', 'rectangle', 'right', 'off', '', 1, 1),
                array(
                    'title' => 'تنظیمات بصری',
                    'id' => 'layout',
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
                            $status_default ? Irantheme_Section_Default::get_default_ids_objects(4, 'service_semi_number', $status_default) : '',
                        ),
                        array(
                            'id'             => 'service_alignment_content_radio',
                            'label'            => __('موقعیت محتوای سرویس ها', self::$static_textdomain),
                            'description'    => __('با استفاده از این گزینه می توانید موقعیت محتوای داخل سرویس ها را تعیین کنید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'thumbnail_status' => true,
                            'options'        => array('right' => array('راست چین', 'text-align-right.png'), 'center' => array('وسط چین', 'text-align-center.png'), 'left' => array('چپ چین', 'text-align-left.png')),
                            'default'        => $status_default ? 'right' : '',
                            'keywords' => array('محتوا', 'تنظیم', 'تراز', 'محور', 'alignment', 'float', 'position')
                        ),
                        array(
                            'id'             => 'display_mode',
                            'label'            => __(
                                'سبک محتوا',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید سبک نمایش محتوای سرویس خود را مشخص کنید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'thumbnail_status' => true,
                            'options'        => array('right' => array('راست', 'showcase-split-right.png'), 'left' => array('چپ', 'showcase-split-left.png')),
                            'default'        => $status_default ? 'right' : '',
                            'keywords' => array('semi', 'mode', 'سبک', 'سرویس', 'چیدمان', 'alignment')
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
                            'id'             => 'service_title_color_select',
                            'label'            => __(
                                'رنگ عنوان سرویس ها',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید در حالت استفاده از سرویس های عددی، رنگ اعداد و در حالت استفاده از سرویس های عادی، رنگ آیکون های آن را تنظیم نمایید.', self::$static_textdomain),
                            'type'            => 'select',
                            'color_preview' => true,
                            'options'        => Irantheme_Color_Static::get_all_colors_options(),
                            'default'        => $status_default ? 'primary' : '',
                            'keywords' => array('رنگ جداکننده', 'رنگ', 'مرز', 'separator', 'جداکننده'),
                        ),
                        array(
                            'id'             => 'box_style_radio',
                            'label'            => __(
                                'استایل نمایش',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید نحوه نمایش در سبک و استایل های مختلف را مشخص کنید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'options'        => array('none' => 'ساده', 'border' => 'خط دور', 'shadow' => 'سایه'),
                            'default'        => 'shadow',
                            'keywords' => array('استایل نمایش', 'box style')
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
                Irantheme_Section_Utils::background_fields_generator($status_default, array('image', 'color'), $status_default === 'media' ? Irantheme_Common_Static::get_file_url_media_library('https://irantheme.com/wp-content/uploads/2026/02/br6mum12.webp') : '', 'off', true, 'on', 'dark', 0.6, 'on'),
                array(
                    'title' => 'امکانات',
                    'id' => 'additional',
                    'description'            => __('فعال یا غیر فعال کردن امکانات مانند: شبکه های اجتمایی، دکمه پیمایش به پایین و ...', self::$static_textdomain),
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
            $desc_small_default = '';

            // Check status is deafult or no
            if (is_numeric($index)) {
                // List of default titles
                $titles = array(
                    'تعداد نصب اپ',
                    'تنوع محصولات ما',
                    'مشتریان در روز',
                    'اعضای خدمات دهنده',
                );

                // List of default numbers
                $numbers = array(
                    1460000,
                    886,
                    365000,
                    225,
                );

                // List of default chars
                $chars = array(
                    'K',
                    '',
                    '',
                    '',
                );

                // List of default small desc
                $desc_small = array(
                    'با بیش از 1 میلیون نصب',
                    'انواع محصولات با برندینگ جهانی',
                    'منتظر حضور شما هستیم',
                    'کادری حرفه ای با مدارک بین المللی',
                );

                // Set default value
                $titles_default = $titles[$index];
                $numbers_default = $numbers[$index];
                $chars_default = $chars[$index];
                $desc_small_default = $desc_small[$index];
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
                        array(
                            'id'             => 'number_desc_small',
                            'label'            => __(
                                'توضیحات ریز',
                                self::$static_textdomain
                            ),
                            'description'    => __('توضیحات ریز را وارد کنید.', self::$static_textdomain),
                            'type'            => 'text',
                            'default'        => $desc_small_default,
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
            $background_color_data = $fields_values['bg_color'];
            $background_color_css = Irantheme_Section_Utils::get_background_color_css($background_color_data);
            $background_image_css = Irantheme_Section_Utils::get_background_image_css($background_image_data, true);

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

            // Base color --------------------------------------------------------------
            $base_color = $fields_values['base_color_select'];
            // Set base color class
            $base_color_class = Irantheme_Common_Static::get_base_color_class($base_color);

            // Get border radius --------------------------------------------------------------
            $border_radius_range = $fields_values['border_radius_range'];
            $border_radius_style = Irantheme_Section_Utils::get_border_radius_css($border_radius_range);

            // Custom color
            $box_style_class = '';
            // Get header background color
            $header_style_option = $fields_values['box_style_radio'];
            if ($header_style_option == 'none') {
                $box_style_class = 'irantheme-box-style-none';
            } else if ($header_style_option == 'border') {
                $box_style_class = 'irantheme-box-style-border';
            } else if ($header_style_option == 'shadow') {
                $box_style_class = 'irantheme-box-style-shadow';
            }

            // Get section id --------------------------------------------------------------
            $section_id = $fields_values['element_id'];
            $section_id_attribute = '';
            if ($section_id) {
                $section_id_attribute = sprintf('id=%s', $section_id);
            }
?>
            <!-- ============================= Section service semi number ============================= -->
            <section <?php echo esc_attr($section_id_attribute); ?> class="irantheme-section irantheme-service-semi-number-section" <?php echo esc_attr(Irantheme_Page_Builder::get_attr_data_section($data_type, $data_value, $data_page, $data_ajax)); ?> style="<?php echo esc_attr(Irantheme_Section_Service::get_custom_outer_padding_css($fields_values)); ?>">
                <?php
                // Set wrapper (Container) --------------------------------------------------------------
                $container_checkbox = $fields_values['wrapper_mode_checkbox'];
                ?>
                <div class="irantheme-service-semi-number irantheme-section-counter-number-on-scroll <?php echo esc_attr($base_color_class); ?>" style="<?php echo esc_attr($background_color_css); ?><?php echo esc_attr(Irantheme_Section_Service::get_custom_inner_padding_css($fields_values)); ?>">
                    <?php
                    if ($container_checkbox != 'on') :
                    ?>
                        <div class=" irantheme-container-inner">
                        <?php endif; ?>
                        <?php
                        // Get alignment columns --------------------------------------------------------------
                        $display_mode = $fields_values['display_mode'];
                        $display_mode_class = '';
                        $padding_holder_class = '';
                        if ($display_mode == 'right') {
                            $display_mode_class = 'flex-row-reverse';
                            $padding_holder_class = 'padding-left';
                        } else {
                            $padding_holder_class = 'padding-right';
                        }
                        // Set padding column with wrapper mode and connect to outer container
                        if ($container_checkbox == 'on') {
                            $padding_holder_class = 'padding-right padding-left';
                        }
                        ?>
                        <div class="row justify-content-center <?php echo esc_attr($display_mode_class); ?>" style="align-items: center;margin-right:0;margin-left:0;">
                            <div class="col-lg-6 irantheme-semi-image-holder" style="margin-bottom:0;padding:0;display:flex;justify-content:center;">
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
                            <div class="col-lg-6" style="margin-bottom:0;padding:0;">
                                <div class="irantheme-holder-padding <?php echo esc_attr($padding_holder_class); ?>">
                                    <!-- Content -->
                                    <?php
                                    $heading_extra_css = 'height:auto;';
                                    // Get service heading --------------------------------------------------------------
                                    $service_heading = $fields_values['text_editor_heading'];
                                    // Set heading alignment --------------------------------------------------------------
                                    $service_heading_alignment = $fields_values['alignment_heading_radio'];
                                    if ($service_heading != '' && $heading_data_load_checkbox == 'on'):
                                    ?>
                                        <div class="irantheme-tinymce-heading-aos-holder" <?php echo ($heading_data_load_attr); ?> data-aos-delay="<?php echo ($heading_aos_delay); ?>" data-aos-duration="<?php echo ($heading_data_load_duration); ?>">
                                        <?php
                                    endif;
                                    Irantheme_Common_Static::tinymce_heading($fields_values, $heading_extra_css);
                                    if ($service_heading != '' && $heading_data_load_checkbox == 'on'):
                                        ?>
                                        </div>
                                    <?php
                                    endif;
                                    // Service content alignment --------------------------------------------------------------
                                    $service_content_alignment = $fields_values['service_alignment_content_radio'];
                                    $alignment_row_class = '';
                                    if ($service_content_alignment == 'right') {
                                        $alignment_row_class = 'justify-content-start';
                                    } elseif ($service_content_alignment == 'left') {
                                        $alignment_row_class = 'justify-content-end';
                                    } elseif ($service_content_alignment == 'center') {
                                        $alignment_row_class = 'justify-content-center';
                                    }
                                    ?>
                                    <div class="irantheme-object-group row <?php echo esc_attr($alignment_row_class); ?>" data-json="<?php echo esc_attr('objects_json'); ?>">
                                        <?php
                                        // Get title color --------------------------------------------------------------
                                        $title_color_mode = $fields_values['service_title_color_select'];
                                        $title_color_css = '';
                                        if ($title_color_mode && $title_color_mode != 'off') {
                                            $title_color_css = 'color:#' . Irantheme_Color_Static::get_hex_color($title_color_mode) . ';';
                                        }
                                        // Service custom fields --------------------------------------------------------------
                                        foreach ($json_post_array as $object_post) {
                                            // Get title options
                                            $service_title = $object_post['fields']['number_title'];
                                            // Get title char option
                                            $service_title_char = $object_post['fields']['number_title_char'];
                                            $service_title_char_tag = '<span class="irantheme-service-item-content-title-char">' . $service_title_char . '</span>';
                                            // Get description option
                                            $service_desc = $object_post['fields']['number_desc'];
                                            // Get small description option
                                            $service_desc_small = $object_post['fields']['number_desc_small'];
                                        ?>
                                            <!-- Service item -->
                                            <div class="irantheme-object <?php echo esc_attr(Irantheme_Section_Service::get_bootstrap_class('col-2')); ?>" data-object="<?php echo esc_attr($object_post['object_id']); ?>">
                                                <div class="irantheme-service-item <?php echo esc_attr($box_style_class); ?>" style="<?php echo esc_attr($border_radius_style); ?>" <?php echo ($data_load_attr); ?> data-aos-delay="<?php echo ($aos_delay); ?>" data-aos-duration="<?php echo ($data_load_duration); ?>">
                                                    <!-- Wrapper -->
                                                    <div class="irantheme-service-item-wrapper" style="padding:0;">
                                                        <div class="irantheme-service-item-wrapper-content">
                                                            <!-- Content -->
                                                            <div class="irantheme-service-item-content <?php echo esc_attr(Irantheme_Section_Service::get_alignment_class($service_content_alignment)); ?> <?php echo esc_attr(Irantheme_Section_Service::get_flex_column_alignment_class($service_content_alignment)); ?>">
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
                                                                    <h3 class="irantheme-service-item-content-number" style="font-size:1.8rem;font-weight:normal;<?php echo esc_attr($title_color_css); ?>">
                                                                        <span class="irantheme-service-item-content-number-char"><?php echo $service_title_char; ?></span>
                                                                        <?php echo $service_title_holder; ?>
                                                                    </h3>
                                                                <?php endif; ?>
                                                                <div class="irantheme-service-item-content-desc">
                                                                    <?php if ($service_desc) : ?>
                                                                        <!-- Description -->
                                                                        <p class="irantheme-service-item-content-desc-heading" style="font-size:0.9rem;"><?php echo esc_html($service_desc); ?></p>
                                                                    <?php endif; ?>
                                                                    <?php if ($service_desc_small) : ?>
                                                                        <!-- Small description -->
                                                                        <p class="irantheme-service-item-content-desc-small" style="font-size:0.7rem;"><?php echo esc_html($service_desc_small); ?></p>
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
