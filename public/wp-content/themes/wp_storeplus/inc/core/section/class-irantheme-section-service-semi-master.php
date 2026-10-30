<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;

if (!class_exists('Irantheme_Section_Service_Semi_Master')) {
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
    class Irantheme_Section_Service_Semi_Master extends Irantheme_Section
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
            $heading_content = '<span style="font-size: 1em;">ــــــــــــــــــــــــ در کجای مسیر هستیم</span>
<h2 style="font-size: 2em;"><span style="font-size: 1.2em;"><strong>لبه تکنولوژی دنیا</strong></span></h2><p font-size:1em;>لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است. چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است و برای شرایط فعلی تکنولوژی مورد نیاز و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد.</p><p font-size:1em;>لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است. چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است و برای شرایط فعلی تکنولوژی مورد نیاز و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد.</p><p font-size:1em;>لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است. چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است و برای شرایط فعلی تکنولوژی مورد نیاز و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد.</p>';
            $settings = array(
                Irantheme_Section_Utils::heading_title_settings_generator($status_default, 'none', false, 'right', $heading_content, 'on', 'شروع ماجراجویی', '#', 'fa-light fa-rocket-vertical', 'primary', 'off', '', '', '', 'off', 'rectangle', 'right', 'off', '', 3, 3, 'large'),
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
                            $status_default ? Irantheme_Section_Default::get_default_ids_objects(4, 'service_semi_master', $status_default) : '',
                        ),
                        array(
                            'id'             => 'service_alignment_content_radio',
                            'label'            => __('موقعیت محتوای سرویس ها', self::$static_textdomain),
                            'description'    => __('با استفاده از این گزینه می توانید موقعیت محتوای داخل سرویس ها را تعیین کنید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'thumbnail_status' => true,
                            'options'        => array('right' => array('راست چین', 'text-align-right.png'), 'center' => array('وسط چین', 'text-align-center.png'), 'left' => array('چپ چین', 'text-align-left.png')),
                            'default'        => $status_default ? 'center' : '',
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
                            'default'        => $status_default ? 'left' : '',
                            'keywords' => array('semi', 'mode', 'سبک', 'سرویس', 'چیدمان', 'alignment')
                        ),
                        array(
                            'id'             => 'col_count_radio',
                            'label'            => __('تعداد ستون ها', self::$static_textdomain),
                            'description'    => __('با استفاده از این گزینه شما می توانید تعداد ستون های قابل نمایش در بخش سرویس را مشخص کنید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'thumbnail_status' => true,
                            'options'        => array(
                                'col-2' => array('دو ستون', 'col-2.png'),
                                'col-3' => array('سه ستون', 'col-3.png'),
                            ),
                            'default'        => $status_default ? 'col-2' : '',
                            'keywords' => array('service', 'col', 'num', 'تعداد', 'سرویس', 'تعداد در هر ردیف'),
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
                            'id'             => 'content_space_range',
                            'label'            => __(
                                'اندازه حاشیه محتوا',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه میتوانید میزان حاشیه محتوا را مشخص کنید.', self::$static_textdomain),
                            'type'            => 'range',
                            'min' => 0.5,
                            'max' => 6,
                            'step' => 0.5,
                            'default'        => $status_default ? 3 : '',
                            'placeholder'    => __('Placeholder text', self::$static_textdomain),
                            'keywords' => array('space', 'icon', 'آیکون', 'فاصله'),
                        ),
                        array(
                            'id'             => 'item_box_shadow_checkbox',
                            'label'            => __(
                                'استایل سایه',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت فعال شدن این گزینه می توانید برای باکس سرویس ها یک سایه ایجاد کنید.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'on' : '',
                            'keywords' => array('double', 'دوتایی', 'icon', 'چنیش', 'آیکون', 'عناصر'),
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
                Irantheme_Section_Utils::background_fields_generator($status_default, array('color'), '', 'off', true),
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
            $icons_default = '';
            $content_default = '';

            // Check status is deafult or no
            if (is_numeric($index)) {
                // List of default titles
                $titles = array(
                    'بهترین قطعات',
                    'حمل و نقل پیشتاز',
                    'اینترنت اشیاء',
                    'گارانتی طلایی',
                );
                $icons = array(
                    'fa-light fa-trophy-star',
                    'fa-light fa-truck-fast',
                    'fa-light fa-globe-pointer',
                    'fa-light fa-award',
                );

                // Set default value
                $titles_default = $titles[$index];
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
                            'id'             => 'service_title',
                            'label'            => __(
                                'عنوان',
                                self::$static_textdomain

                            ),
                            'description'    => __('عنوان دلخواه خود را اینجا وارد کنید.'),
                            'type'            => 'text',
                            'default'        => $titles_default,
                            'placeholder'    => __('عنوان', self::$static_textdomain),
                        ),
                        array(
                            'id'             => 'service_icon',
                            'label'            => __(
                                'آیکون سرویس',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت نیاز به استفاده از آیکون (نماد) خاصی در کنار متن می توانید از این گزینه استفاده نمایید.', self::$static_textdomain),
                            'type'            => 'icon',
                            'default'        => $icons_default,
                            'placeholder'    => __('', self::$static_textdomain),
                        ),
                        array(
                            'id'             => 'service_btn_link',
                            'label'            => __(
                                'لینک دکمه',
                                self::$static_textdomain

                            ),
                            'description'    => __('لینک دلخواه دکمه خود را اینجا وارد کنید.'),
                            'type'            => 'text',
                            'default'        => '',
                            'placeholder'    => __('لینک دکمه', self::$static_textdomain),
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
            // Background options ------------------------------------------------------------
            $background_color_data = $fields_values['bg_color'];
            $background_color_css = Irantheme_Section_Utils::get_background_color_css($background_color_data);

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

            // Service semi master --------------------------------------------------------------
            $json_post_array = Irantheme_JSON_Helper::normalize_objects($fields_values['objects_json']);

            // Base color --------------------------------------------------------------
            $base_color = $fields_values['base_color_select'];
            // Set base color class
            $base_color_class = Irantheme_Common_Static::get_base_color_class($base_color);

            // Get border radius --------------------------------------------------------------
            $border_radius_range = $fields_values['border_radius_range'];
            $border_radius_style = Irantheme_Section_Utils::get_border_radius_css($border_radius_range);

            // Service box shadow --------------------------------------------------------------
            $service_box_shadow_checkbox = $fields_values['item_box_shadow_checkbox'];
            $service_box_shadow_class = '';
            if ($service_box_shadow_checkbox == 'on') {
                $service_box_shadow_class = 'irantheme-service-box-shadow';
            }

            // Get section id --------------------------------------------------------------
            $section_id = $fields_values['element_id'];
            $section_id_attribute = '';
            if ($section_id) {
                $section_id_attribute = sprintf('id=%s', $section_id);
            }

            // Get col count---------------------------------------------------------------
            $col_count = $fields_values['col_count_radio'];
            if ($col_count == '' || $col_count == null) {
                $col_count = 'col-1';
            }

            // Get content space
            $content_space = $fields_values['content_space_range'];
?>
            <!-- ============================= Section service semi master ============================= -->
            <section <?php echo esc_attr($section_id_attribute); ?> class="irantheme-section irantheme-service-semi-master-section" <?php echo esc_attr(Irantheme_Page_Builder::get_attr_data_section($data_type, $data_value, $data_page, $data_ajax)); ?> style="<?php echo esc_attr(Irantheme_Section_Service::get_custom_outer_padding_css($fields_values)); ?>">
                <?php
                // Set wrapper (Container) --------------------------------------------------------------
                $container_checkbox = $fields_values['wrapper_mode_checkbox'];
                ?>
                <div class="irantheme-service-semi-master <?php echo esc_attr($base_color_class); ?>" style="<?php echo esc_attr($background_color_css); ?><?php echo esc_attr(Irantheme_Section_Service::get_custom_inner_padding_css($fields_values)); ?>">
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
                        <div class="row justify-content-center <?php echo esc_attr($display_mode_class); ?>" style="align-items: center;">
                            <div class="col-lg-6">
                                <?php
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
                                    // Details color --------------------------------------------------------------
                                    $section_details_color = $fields_values['details_color_select'];
                                    $section_details_color_mode_class = '';
                                    if ($section_details_color != 'off') {
                                        $section_details_color_mode_class = 'irantheme-color-details-active';
                                    }
                                    // Set details colors --------------------------------------------------------------
                                    $color_details_bg_color_class = Irantheme_Color_Static::get_class_color_mode('bg-hover', null, Irantheme_Color_Static::get_hex_color($section_details_color));
                                    // Service custom fields --------------------------------------------------------------
                                    foreach ($json_post_array as $object_post) {
                                        // Get title options
                                        $service_title = $object_post['fields']['service_title'];
                                        // Get icon option
                                        $service_icon = $object_post['fields']['service_icon'];
                                        // Get button link option
                                        $service_btn_link = $object_post['fields']['service_btn_link'];
                                        $service_btn_link_attr = '';
                                        if ($service_btn_link) {
                                            $service_btn_link_attr = sprintf('href=%s', esc_url($service_btn_link));
                                        }
                                        // Alignment --------------------------------------------------------------
                                        $service_item_content_css = 'padding:' . $content_space . 'rem;';
                                        $service_item_icon_css = 'row-gap:' . $content_space / 2 . 'rem;';
                                    ?>
                                        <!-- Service item -->
                                        <div class="irantheme-object <?php echo esc_attr(Irantheme_Section_Service::get_bootstrap_class($col_count, 12)); ?>" style="margin-bottom: 0;" data-object="<?php echo esc_attr($object_post['object_id']); ?>">
                                            <div class="irantheme-service-item <?php echo esc_attr($section_details_color_mode_class); ?> <?php echo esc_attr($service_box_shadow_class); ?> <?php echo esc_attr($color_details_bg_color_class); ?>" style="<?php echo esc_attr($service_item_content_css); ?>" <?php echo ($data_load_attr); ?> data-aos-delay="<?php echo ($aos_delay); ?>" data-aos-duration="<?php echo ($data_load_duration); ?>">
                                                <!-- Wrapper -->
                                                <div class="irantheme-service-item-wrapper <?php echo esc_attr(Irantheme_Section_Service::get_alignment_class($service_content_alignment)); ?>" style="<?php echo esc_attr($service_item_icon_css); ?>">
                                                    <!-- Content -->
                                                    <div class="irantheme-service-item-content">
                                                        <!-- Title -->
                                                        <?php if ($service_title) : ?>
                                                            <h3><?php echo esc_html($service_title); ?></h3>
                                                        <?php endif; ?>
                                                    </div>
                                                    <!-- Icon -->
                                                    <?php if ($service_icon) : ?>
                                                        <div class="irantheme-service-item-icon <?php echo esc_attr($alignment_row_class); ?>">
                                                            <a <?php echo $service_btn_link_attr; ?>>
                                                                <i class=" <?php echo esc_attr($service_icon); ?>"></i>
                                                            </a>
                                                        </div>
                                                    <?php endif; ?>
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
                            <div class="col-lg-6" style="margin-bottom:0;padding:0;">
                                <div class="irantheme-holder-padding <?php echo esc_attr($padding_holder_class); ?>">
                                    <!-- Content -->
                                    <?php
                                    $heading_extra_css = 'height:auto;';
                                    // Get service heading --------------------------------------------------------------
                                    $service_heading = $fields_values['text_editor_heading'];

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
                                    ?>
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
