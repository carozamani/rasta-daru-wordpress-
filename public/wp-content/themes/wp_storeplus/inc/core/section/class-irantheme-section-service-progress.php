<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;

if (!class_exists('Irantheme_Section_Service_Progress')) {
    /**
     * A class for create section
     *
     * @package Wordpress
     * @subpackage irantheme
     * @author iranianclever
     * @since v1.0
     * @see v1.0
     *
     */
    class Irantheme_Section_Service_Progress extends Irantheme_Section
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
            $heading_content = '<span style="font-size: 1em;">ــــــــــــــــــــــــ درصدی از توامندی ها</span>
<h2 style="font-size: 2em;"><span style="font-size: 1.2em;"><strong>تخصص و مهارت های من</strong></span></h2><p font-size:1em;>لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است. چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است و برای شرایط فعلی تکنولوژی مورد نیاز و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد.</p>';
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
                            $status_default ? Irantheme_Section_Default::get_default_ids_objects(4, 'service_progress', $status_default) : '',
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
                            'id'             => 'display_mode',
                            'label'            => __(
                                'سبک محتوا',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید سبک نمایش محتوای سرویس خود را مشخص کنید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'thumbnail_status' => true,
                            'options'        => array('right' => array('راست', 'showcase-split-right.png'), 'left' => array('چپ', 'showcase-split-left.png')),
                            'default'        => $status_default ? 'left' : '',
                            'keywords' => array('semi', 'mode', 'سبک', 'سرویس', 'چیدمان', 'alignment')
                        ),
                        array(
                            'id'             => 'col_count_radio',
                            'label'            => __('تعداد ستون نوار پیشرفت', self::$static_textdomain),
                            'description'    => __('با استفاده از این گزینه شما می توانید تعداد ستون برای نمایش نوار پیشرفت ها را مشخص نمایید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'thumbnail_status' => true,
                            'options'        => array(
                                'col-1' => array('یک ستون', 'col-1.png'),
                                'col-2' => array('دو ستون', 'col-2.png'),
                            ),
                            'default'        => $status_default ? 'col-2' : '',
                            'keywords' => array('service', 'col', 'progress', 'تعداد', 'سرویس', 'تعداد در هر ردیف'),
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
                            'id'             => 'details_color_select',
                            'label'            => __(
                                'رنگ جزئیات',
                                self::$static_textdomain
                            ),
                            'description'    => __('برای انتخاب رنگ جزئیات بخش از لیست رنگ دلخواه خود را انتخاب کنید. این رنگ تاثیر مستقیمی در زیبایی جزئيات دارد.', self::$static_textdomain),
                            'type'            => 'select',
                            'options'        => Irantheme_Color_Static::get_all_colors_options(),
                            'color_preview' => true,
                            'default'        => $status_default ? 'primary' : '',
                            'keywords' => array('communication', 'contact', 'جزئیات', 'کلید', 'رنگ'),
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
                Irantheme_Section_Utils::layout_scale_fields_generator($status_default, array('inner_padding', 'outer_padding', 'radius'), 'on', 2, 2, 'off', 'off', 0, 0, 1),
                // Get background fields
                Irantheme_Section_Utils::background_fields_generator($status_default, array('image', 'color'), $status_default === 'media' ? Irantheme_Common_Static::get_file_url_media_library('https://irantheme.com/wp-content/uploads/2026/02/vxzzbofm.webp') : '', 'off', true, 'on', 'dark', 0.6, 'on'),
                array(
                    'title' => 'امکانات',
                    'id' => 'additional',
                    'description'            => __('فعال یا غیر فعال کردن امکانات سرویس', self::$static_textdomain),
                    'icon' => 'fa-puzzle-piece',
                    'fields' => array(
                        array(
                            'id'             => 'scroll_mode_checkbox',
                            'label'            => __(
                                'انیمیشن نوار پیشرفت',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت فعال شدن این گزینه زمانی که کاربر نزدیک به این بخش می شود،نوار پیشرفت بصورت انیمیشنی تکمیل می شود.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'on' : '',
                            'keywords' => array('progress', 'scroll', 'انیمیشن', 'پیشرفت'),
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
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function object_fields($index, $status_default = false)
        {
            $titles_default = '';
            $percents_default = 50;
            $descriptions_default = '';

            // Check status is deafult or no
            if (is_numeric($index)) {
                // List of default titles
                $titles = array(
                    'طراحی گرافیک',
                    'عکاسی در شب',
                    'خلاقیت',
                    'فیزیک بدنی',
                );

                // List of default percents
                $percents = array(
                    68,
                    83,
                    91,
                    65
                );

                // Default descriptions
                $descriptions = __('لورم ایپسوم متن ساختگی با تولید سادگی');

                // Set default value
                $titles_default = $titles[$index];
                $percents_default = $percents[$index];
                $descriptions_default = $descriptions;
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
                            'id'             => 'progress_heading',
                            'label'            => __(
                                'عنوان نوار پیشرفت',
                                self::$static_textdomain

                            ),
                            'description'    => __('عنوان نوار پیشرفت می تواند نام یک پروژه، میزان مهارت یا ... باشد.'),
                            'type'            => 'text',
                            'default'        => $titles_default,
                            'placeholder'    => __('عنوان خود را وارد کنید', self::$static_textdomain),
                            'keywords' => array('نوار', 'پیشرفت', 'نوار پیشرفت', 'progress', 'service', 'percent', 'عنوان نوار پیشرفت'),
                        ),
                        array(
                            'id'             => 'progress_description',
                            'label'            => __(
                                'توضیحات نوار پیشرفت',
                                self::$static_textdomain
                            ),
                            'description'    => __('توضیحات مربوط به عنوان را وارد کنید.', self::$static_textdomain),
                            'type'            => 'textarea',
                            'default'        => $descriptions_default,
                            'placeholder'    => __('متن مورد نظر را وارد کنید', self::$static_textdomain),
                            'keywords' => array('توضیحات', 'پیشرفت', 'توضیحات پیشرفت', 'title', 'service', 'heading', 'توضیحات نوار پیشرفت'),
                        ),
                        array(
                            'id'             => 'progress_percent',
                            'label'            => __(
                                'درصد نوار پیشرفت',
                                self::$static_textdomain
                            ),
                            'description'    => __('میزان درصد نوار پیشرفت را وارد نمایید.', self::$static_textdomain),
                            'type'            => 'range',
                            'min' => 0,
                            'max' => 100,
                            'step' => 1,
                            'default'        => $percents_default,
                            'placeholder'    => __('Placeholder text', self::$static_textdomain),
                            'keywords' => array('نوار', 'پیشرفت', 'نوار پیشرفت', 'progress', 'service', 'percent', 'درصد نوار پیشرفت'),
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

            // Get service heading --------------------------------------------------------------
            $heading_extra_css = 'height:auto;';
            $service_heading = $fields_values['text_editor_heading'];

            // Get section id --------------------------------------------------------------
            $section_id = $fields_values['element_id'];
            $section_id_attribute = '';
            if ($section_id) {
                $section_id_attribute = sprintf('id=%s', $section_id);
            }
?>
            <!-- ============================= Section service progress ============================= -->
            <section <?php echo esc_attr($section_id_attribute); ?> class="irantheme-section irantheme-service-progress-section" <?php echo esc_attr(Irantheme_Page_Builder::get_attr_data_section($data_type, $data_value, $data_page, $data_ajax)); ?> style="<?php echo esc_attr(Irantheme_Section_Service::get_custom_outer_padding_css($fields_values)); ?>">

                <?php
                // Set wrapper (Container) --------------------------------------------------------------
                $container_checkbox = $fields_values['wrapper_mode_checkbox'];
                ?>
                <div class="irantheme-service-progress <?php echo esc_attr($base_color_class); ?>" style="<?php echo esc_attr($background_color_css); ?><?php echo esc_attr(Irantheme_Section_Service::get_custom_inner_padding_css($fields_values)); ?>">
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
                        <div class="row justify-content-center <?php echo esc_attr($display_mode_class); ?>" style="align-items:center;margin-right:0;margin-left:0;">
                            <div class="col-lg-6 irantheme-semi-image-holder" style="margin-bottom:0;padding:0;display:flex;justify-content:center;flex-direction: column;">
                                <?php if ($background_image_css) : ?>
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
                                <?php else : ?>
                                    <?php
                                    if ($service_heading != '' && $heading_data_load_checkbox == 'on'):
                                    ?>
                                        <div class="irantheme-tinymce-heading-aos-holder" <?php echo ($heading_data_load_attr); ?> data-aos-delay="<?php echo ($heading_aos_delay); ?>" data-aos-duration="<?php echo ($heading_data_load_duration); ?>">
                                        <?php
                                    endif;
                                    // Get heading content --------------------------------------------------------------
                                    Irantheme_Common_Static::tinymce_heading($fields_values, $heading_extra_css);
                                        ?>
                                        <?php
                                        if ($service_heading != '' && $heading_data_load_checkbox == 'on'):
                                        ?>
                                        </div>
                                    <?php
                                        endif;
                                    ?>
                                <?php endif; ?>
                            </div>
                            <div class="col-lg-6" style="margin-bottom:0;padding:0;">
                                <div class="irantheme-holder-padding <?php echo esc_attr($padding_holder_class); ?>" style="flex-direction: column;">
                                    <?php if ($background_image_css) : ?>
                                        <?php
                                        if ($service_heading != '' && $heading_data_load_checkbox == 'on'):
                                        ?>
                                            <div class="irantheme-tinymce-heading-aos-holder" <?php echo ($heading_data_load_attr); ?> data-aos-delay="<?php echo ($heading_aos_delay); ?>" data-aos-duration="<?php echo ($heading_data_load_duration); ?>">
                                            <?php
                                        endif;
                                            ?>
                                            <!-- Content -->
                                            <?php
                                            // Get heading content --------------------------------------------------------------
                                            Irantheme_Common_Static::tinymce_heading($fields_values, $heading_extra_css);
                                            ?>
                                            <?php
                                            if ($service_heading != '' && $heading_data_load_checkbox == 'on'):
                                            ?>
                                            </div>
                                        <?php
                                            endif;
                                        ?>
                                    <?php endif; ?>
                                    <?php
                                    // Get title color --------------------------------------------------------------
                                    $details_color_mode = $fields_values['details_color_select'];
                                    ?>
                                    <!-- Holder service progress items -->
                                    <div class="irantheme-service-progress-holder irantheme-section-counter-number-on-scroll">
                                        <div class="irantheme-object-group row" data-json="<?php echo esc_attr('objects_json'); ?>">
                                            <?php
                                            // Loop form all services names
                                            foreach ($json_post_array as $object_post) :
                                                // Generate heading name & value
                                                $service_heading_value = $object_post['fields']['progress_heading'];
                                                // Generate description name & value
                                                $service_description_value = $object_post['fields']['progress_description'];
                                                // Generate percent name & value
                                                $service_percent_value = $object_post['fields']['progress_percent'];

                                                // Animation bar and percent on scroll --------------------------------------------------------------
                                                $animate_filled = $fields_values['scroll_mode_checkbox'];
                                                $animate_filled_class = '';
                                                $percent_counter_class = '';
                                                if ($animate_filled == 'on') {
                                                    $animate_filled_class = 'irantheme-animate-filled';
                                                    $percent_counter_class = 'irantheme-number-counter-on-scroll';
                                                }

                                                // Progress bar styles
                                                $progress_bar_styles = '';
                                                if ($service_percent_value && $animate_filled == 'off') {
                                                    $progress_bar_styles .= 'width:' . $service_percent_value . '%;';
                                                }
                                                if ($details_color_mode && $details_color_mode != 'off') {
                                                    $progress_bar_styles .= 'color:#' . Irantheme_Color_Static::get_hex_color($details_color_mode) . ';';
                                                }

                                                // Set progress service column
                                                $service_columns = $fields_values['col_count_radio'];
                                                $bootstrap_service_columns = 'col-lg-12';
                                                if ($service_columns == 'col-1') {
                                                    $bootstrap_service_columns = 'col-lg-12';
                                                } elseif ($service_columns == 'col-2') {
                                                    $bootstrap_service_columns = 'col-lg-6';
                                                }
                                            ?>
                                                <div class="irantheme-object <?php echo esc_attr($bootstrap_service_columns); ?>" style="margin-bottom:0;" data-object="<?php echo esc_attr($object_post['object_id']); ?>" data-json="<?php echo esc_attr('objects_json'); ?>">
                                                    <!-- Service progress item -->
                                                    <div class="irantheme-service-progress-item <?php echo esc_attr($box_style_class); ?>" style="<?php echo esc_attr($border_radius_style); ?>" <?php echo ($data_load_attr); ?> data-aos-delay="<?php echo ($aos_delay); ?>" data-aos-duration="<?php echo ($data_load_duration); ?>">
                                                        <!-- Heading -->
                                                        <div class="irantheme-service-progress-item-heading">
                                                            <h4 class="irantheme-service-progress-item-heading-title">
                                                                <i class="iconsax" icon-name="magic-wand" style="<?php echo esc_attr($progress_bar_styles); ?>"></i>
                                                                <?php _e($service_heading_value); ?>
                                                            </h4>
                                                            <div class="irantheme-service-progress-item-heading-percent">
                                                                <span class="<?php echo esc_attr($percent_counter_class); ?>" data-counter="<?php echo esc_attr($service_percent_value); ?>">
                                                                    <?php if ($animate_filled == 'off'): ?>
                                                                        <?php echo esc_html($service_percent_value); ?>
                                                                    <?php endif; ?>
                                                                </span>
                                                                <i class="iconsax" icon-name="percentage-square" style="<?php echo esc_attr($progress_bar_styles); ?>"></i>
                                                            </div>
                                                        </div>
                                                        <!-- Percent bar -->
                                                        <div class="irantheme-service-progress-item-bar">
                                                            <!-- Line (Cleared) -->
                                                            <div class="irantheme-service-progress-item-bar-line">

                                                                <!-- Line (Filled) -->
                                                                <span class="irantheme-service-progress-item-bar-line-filled <?php echo esc_attr($animate_filled_class); ?>" data-width="<?php echo esc_attr($service_percent_value); ?>"></span>
                                                            </div>
                                                        </div>
                                                        <?php if ($service_description_value) : ?>
                                                            <!-- Description -->
                                                            <div class="irantheme-service-progress-item-description">
                                                                <i class="iconsax" icon-name="more-square" style="<?php echo esc_attr($progress_bar_styles); ?>"></i>
                                                                <?php echo esc_html($service_description_value); ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <?php
                                                    Irantheme_Page_Builder::get_hidden_require_object_buttons($data_page);
                                                    ?>
                                                </div>
                                            <?php
                                                // Increase aos delay for next item
                                                $aos_delay += 100;
                                            endforeach;
                                            ?>
                                            <?php
                                            Irantheme_Page_Builder::get_hidden_require_object_group_buttons($data_page);
                                            ?>
                                        </div>
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
