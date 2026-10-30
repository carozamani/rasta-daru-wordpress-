<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;

if (!class_exists('Irantheme_Section_Communication_Contact')) {
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
    class Irantheme_Section_Communication_Contact extends Irantheme_Section
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
            $heading_content = '<span style="font-size: 1em;">ــــــــــــ تماس بگیرید ــــــــــــ</span>
<h2 style="font-size: 2em;"><span style="font-size: 1.2em;"><strong>راه های ارتباطی</strong></span></h2>';
            $settings = array(
                Irantheme_Section_Utils::heading_title_settings_generator($status_default, 'none', 'off', 'right', $heading_content, 'off', '', '', '', 'off', 'off', '', '', '', 'off', 'rectangle', 'center', 'off', '', 0, 1),
                array(
                    'title' => 'تنظیمات فرم ارتباطی',
                    'id' => 'form',
                    'description'            => __('تنظیمات مربوط به فرم ارتباطی مانند : نوع فرم، نوع ورودی های فرم و...', self::$static_textdomain),
                    'icon' => 'fa-tty',
                    'fields' => array(
                        array(
                            'id'             => 'form_heading_checkbox',
                            'label'            => __(
                                'تنظیم عنوان اختصاصی',
                                self::$static_textdomain
                            ),
                            'description'    => __('با فعال کردن این گزینه می توانید یک عنوان اختصاصی برای ستون فرم ارتباطی قرار دهید.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'off' : '',
                            'keywords' => array('communication', 'contact', 'ارتباط با ما',  'ارتباطات'),
                            'id_dependents_on' => array('form_text_editor_heading'),
                            'id_dependents_off' => array(),
                            'base_priority' => 'checkbox_base',
                            'selector_priority' => 'checkbox_communication_form_heading_service',
                            'z_index' => 0
                        ),
                        array(
                            'id'             => 'form_text_editor_heading',
                            'label'            => __('عنوان فرم ارتباطی', self::$static_textdomain),
                            'description'    => __('محتوای مورد نظر خود را وارد کنید.این محتوا در بالای فرم ارتباطی نمایش داده می شود.'),
                            'type'            => 'text_editor',
                            'default'        => $status_default ? '' : '',
                            'placeholder'    => __('', self::$static_textdomain),
                            'keywords' => array('متن', 'محتوا',  'عنوان', 'تیتر', 'نوشته', 'title', 'heading', 'text', 'content'),
                        ),
                        array(
                            'id'             => 'plugin_form_checkbox',
                            'label'            => __(
                                'استفاده از پلاگین های فرم ساز',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت غیر فعال بودن این گزینه شما می توانید از فرم ارتباطی پیش فرض قالب استفاده کنید.در صورتی که می خواهید از پلاگین ها فرم ساز استفاده کنید این گزینه را روشن کنید.
                                ', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'off' : '',
                            'keywords' => array('communication', 'contact', 'ارتباط با ما',  'ارتباطات'),
                            'id_dependents_on' => array('plugin_form_shortcode'),
                            'id_dependents_off' => array('form_input_name', 'form_input_email', 'form_input_website', 'form_input_desc', 'form_input_subject'),
                            'base_priority' => 'checkbox_base',
                            'selector_priority' => 'checkbox_communication_form_service',
                            'z_index' => 0
                        ),
                        array(
                            'id'             => 'plugin_form_shortcode',
                            'label'            => __(
                                'شورت کد',
                                self::$static_textdomain

                            ),
                            'description'    => __('شورت کد تولید شده توسط پلاگین فرم ساز را در اینجا قرار دهید.'),
                            'type'            => 'textarea',
                            'default'        => $status_default ? '' : '',
                            'placeholder'    => __('', self::$static_textdomain),
                            'keywords' => array('communication', 'contact', 'ارتباط با ما',  'ارتباطات'),
                        ),
                        array(
                            'id'             => 'form_input_name',
                            'label'            => __(
                                'نمایش فیلد نام',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت غیر فعال بودن این گزینه این فیلد در فرم ارتباطی نمایش داده نخواهد شد.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'off' : '',
                            'keywords' => array('communication', 'contact', 'ارتباط با ما',  'ارتباطات',  'border'),
                        ),
                        array(
                            'id'             => 'form_input_email',
                            'label'            => __(
                                'نمایش فیلد ایمیل',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت غیر فعال بودن این گزینه این فیلد در فرم ارتباطی نمایش داده نخواهد شد.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'on' : '',
                            'keywords' => array('communication', 'contact', 'ارتباط با ما',  'ارتباطات',  'border'),
                        ),
                        array(
                            'id'             => 'form_input_website',
                            'label'            => __(
                                'نمایش فیلد آدرس سایت',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت غیر فعال بودن این گزینه این فیلد در فرم ارتباطی نمایش داده نخواهد شد.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'off' : '',
                            'keywords' => array('communication', 'contact', 'ارتباط با ما',  'ارتباطات',  'border'),
                        ),
                        array(
                            'id'             => 'form_input_subject',
                            'label'            => __(
                                'نمایش فیلد موضوع',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت غیر فعال بودن این گزینه این فیلد در فرم ارتباطی نمایش داده نخواهد شد.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'on' : '',
                            'keywords' => array('communication', 'contact', 'ارتباط با ما',  'ارتباطات',  'border'),
                        ),
                        array(
                            'id'             => 'form_input_desc',
                            'label'            => __(
                                'نمایش فیلد توضیحات',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت غیر فعال بودن این گزینه این فیلد در فرم ارتباطی نمایش داده نخواهد شد.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'on' : '',
                            'keywords' => array('communication', 'contact', 'ارتباط با ما',  'ارتباطات',  'border'),
                        ),
                    )
                ),
                array(
                    'title' => 'سرویس ها',
                    'id' => 'service',
                    'description'            => __('تنظیمات مربوط به محتوای سرویس', self::$static_textdomain),
                    'icon' => 'fa-grid-2-plus',
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
                            $status_default ? Irantheme_Section_Default::get_default_ids_objects(5, 'communication_contact', $status_default) : '',
                        ),
                        array(
                            'id'             => 'service_checkbox',
                            'label'            => __(
                                'نمایش سرویس در کنار فرم',
                                self::$static_textdomain
                            ),
                            'description'    => __('با فعال کردن این گزینه می توانید سرویس های مربوطه را که قبلا در محیط ادمین در بخش سرویس ها ساخته اید به این بخش اضافه کنید.
                                ', self::$static_textdomain),
                            'note' => __('این قابلیت زمانی نمایش داده می شود که گزینه "انتقال به ستون" در سربرگ "عنوان" غیر فعال باشد.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'on' : '',
                            'keywords' => array('communication', 'contact', 'ارتباط با ما',  'ارتباطات'),
                            'id_dependents_on' => array('service_display_mode', 'service_alignment_content_radio', 'service_heading_checkbox'),
                            'id_dependents_off' => array(),
                            'base_priority' => 'checkbox_base',
                            'selector_priority' => 'checkbox_service',
                            'z_index' => 0
                        ),
                        array(
                            'id'             => 'service_heading_checkbox',
                            'label'            => __(
                                'تنظیم عنوان اختصاصی',
                                self::$static_textdomain
                            ),
                            'description'    => __('با فعال کردن این گزینه می توانید یک عنوان اختصاصی برای ستون فرم ارتباطی قرار دهید.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'off' : '',
                            'keywords' => array('communication', 'contact', 'ارتباط با ما',  'ارتباطات'),
                            'id_dependents_on' => array('service_text_editor_heading'),
                            'id_dependents_off' => array(),
                            'selector_priority' => 'checkbox_service',
                            'z_index' => 1
                        ),
                        array(
                            'id'             => 'service_text_editor_heading',
                            'label'            => __('عنوان ستون سرویس ها', self::$static_textdomain),
                            'description'    => __('محتوای مورد نظر خود را وارد کنید.این عنوان در بالای سرویس های انتخاب شده برای نمایش در کنار فرم ارتباطی نمایش داده می شود.'),
                            'type'            => 'text_editor',
                            'default'        => $status_default ? '' : '',
                            'placeholder'    => __('', self::$static_textdomain),
                            'keywords' => array('متن', 'محتوا',  'عنوان', 'تیتر', 'نوشته', 'title', 'heading', 'text', 'content'),
                        ),
                        array(
                            'id'             => 'service_alignment_content_radio',
                            'label'            => __('موقعیت محتوا سرویس', self::$static_textdomain),
                            'description'    => __('با استفاده از این گزینه می توانید موقعیت قرارگیری محتوای سرویس ها را تعیین کنید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'thumbnail_status' => true,
                            'options'        => array('right' => array('راست چین', 'text-align-right.png'), 'left' => array('چپ چین', 'text-align-left.png')),
                            'default'        => $status_default ? 'right' : '',
                            'keywords' => array('communication', 'contact', 'ارتباط با ما',  'ارتباطات')
                        ),
                        array(
                            'id'             => 'service_display_mode',
                            'label'            => __(
                                'سبک چینش',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید نحوه قرار گیری سرویس ها در کنار فرم را مشخص کنید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'thumbnail_status' => true,
                            'options'        => array('right' => array('راست', 'align-section-right.png'), 'left' => array('چپ', 'align-section-left.png')),
                            'default'        => $status_default ? 'right' : '',
                            'keywords' => array('communication', 'contact', 'ارتباط با ما',  'ارتباطات')
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
                            'id'             => 'details_color_select',
                            'label'            => __(
                                'رنگ جزئیات',
                                self::$static_textdomain
                            ),
                            'description'    => __('برای انتخاب رنگ جزئیات پلن از لیست رنگ دلخواه خود را انتخاب کنید. این رنگ تاثیر مستقیمی در زیبایی جزئيات دارد.', self::$static_textdomain),
                            'type'            => 'select',
                            'options'        => Irantheme_Color_Static::get_all_colors_options(),
                            'color_preview' => true,
                            'default'        => $status_default ? 'primary' : '',
                            'keywords' => array('communication contact', 'contact', 'جزئیات', 'کلید', 'رنگ'),
                        ),
                    )
                ),
                // Get layout scale fields
                Irantheme_Section_Utils::layout_scale_fields_generator($status_default, array('radius', 'inner_padding', 'outer_padding'), 'on', 2, 2, 'off', 'off', 0, 0, 1),
                // Get background fields
                Irantheme_Section_Utils::background_fields_generator($status_default, array('image', 'color', 'mask', 'parallax'), '', 'off', true, 'off', 'dark', 0.6, 'off'),
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
            $icons_default = '';
            $descriptions_default = '';

            // Check status is deafult or no
            if (is_numeric($index)) {
                // List of default titles
                $titles = array(
                    'آدرس شرکت',
                    'تلفن تماس',
                    'ایمیل',
                    'ساعات کاری',
                    'اطلاعات اضافی',
                );

                // List of default icons
                $icons = array(
                    'fa-light fa-map-location-dot',
                    'fa-light fa-phone',
                    'fa-light fa-envelope',
                    'fa-light fa-clock',
                    'fa-light fa-circle-info',
                );
                // Default descriptions
                $descriptions = array(
                    'ایران - اصفهان - خیابان مارنان - پل مارنان',
                    '۰۹۱۲۱۲۳۴۵۶۷',
                    'پشتیبانی : example.mail@sample.com',
                    'صبح ها : ۱۳:۰۰ عصرها : ۱۹:۰۰ تا ۱۵:۰۰',
                    'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است.',
                );

                // Set default value
                $titles_default = $titles[$index];
                $icons_default = $icons[$index];
                $descriptions_default = $descriptions[$index];
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
                            'id'             => 'service_desc',
                            'label'            => __(
                                'توضیحات',
                                self::$static_textdomain
                            ),
                            'description'    => __('توضیحات مربوط به سرویس را وارد کنید.', self::$static_textdomain),
                            'type'            => 'textarea',
                            'default'        => $descriptions_default,
                            'placeholder'    => __('توضیحات را وارد کنید', self::$static_textdomain),
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
                            'id'             => 'service_btn_title',
                            'label'            => __(
                                'عنوان دکمه',
                                self::$static_textdomain

                            ),
                            'description'    => __('عنوان دلخواه دکمه خود را اینجا وارد کنید.'),
                            'type'            => 'text',
                            'default'        => '',
                            'placeholder'    => __('عنوان دکمه', self::$static_textdomain),
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
         * Render elements of section
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

            // Get data load style --------------------------------------------------------------
            $heading_data_load_checkbox = $fields_values['heading_data_load_checkbox'];
            $heading_data_load_attr = Irantheme_Section_Utils::get_heading_data_load_attr_type($fields_values);
            // Get data load duration
            $heading_data_load_duration = Irantheme_Section_Utils::get_heading_data_load_duration($fields_values);
            // Get aos delay time
            $heading_aos_delay = Irantheme_Section_Utils::get_heading_data_load_delay($fields_values);

            // Base color --------------------------------------------------------------
            $base_color = $fields_values['base_color_select'];
            // Set base color class
            $base_color_class = Irantheme_Common_Static::get_base_color_class($base_color);

            // Get service heading --------------------------------------------------------------
            $tinymce_heading = $fields_values['text_editor_heading'];

            // Set heading alignment --------------------------------------------------------------
            $tinymce_heading_alignment = $fields_values['alignment_heading_radio'];

            // Get service checkbox shown --------------------------------------------------------------
            $service_checkbox = $fields_values['service_checkbox'];

            // Set item alignment --------------------------------------------------------------
            $service_checkbox = $fields_values['service_checkbox'];

            // Details color --------------------------------------------------------------
            $section_details_color = $fields_values['details_color_select'];

            $extra_css = '';

            // Section transform to up and down --------------------------------------------------------------
            $section_transfrom_values = Irantheme_Section_Utils::get_transform_values_arrays($data_page, $fields_values, true, true, false);
            $section_transform_extra_css = $section_transfrom_values['overflow'];
            $section_transform_translate_css = $section_transfrom_values['css'];
            $business_transform_bg_color_class = $section_transfrom_values['background'];

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
            <!-- ============================ Section communication contact ============================ -->
            <section <?php echo esc_attr($section_id_attribute); ?> class="irantheme-section irantheme-communication-contact-section" <?php echo esc_attr(Irantheme_Page_Builder::get_attr_data_section($data_type, $data_value, $data_page, $data_ajax)); ?> style="<?php echo esc_attr($section_transform_translate_css); ?><?php echo esc_attr(Irantheme_Section_Service::get_custom_outer_padding_css($fields_values)); ?>">
                <div class="irantheme-communication-contact <?php echo esc_attr($base_color_class); ?> irantheme-background-jarallax" style="<?php echo esc_attr(Irantheme_Section_Service::get_custom_inner_padding_css($fields_values)); ?><?php echo esc_attr($background_color_css) ?><?php echo esc_attr($background_image_css) ?><?php echo esc_attr($section_transform_extra_css); ?>" <?php echo $background_parallax_attr; ?>>

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

                    <!-- Inner container -->
                    <div class="irantheme-container-inner">
                        <!-- Wrapper -->
                        <div class="irantheme-communication-contact-wrapper <?php echo esc_attr($business_transform_bg_color_class); ?>" style="<?php echo esc_attr($border_radius_style); ?>">
                            <?php
                            // Heading transfer to col --------------------------------------------------------------
                            $communication_heading_transfer = $fields_values['heading_transfer_checkbox'];
                            if ($communication_heading_transfer != 'on') {
                                if ($tinymce_heading != '' && $heading_data_load_checkbox == 'on'):
                            ?>
                                    <div class="irantheme-tinymce-heading-aos-holder" <?php echo ($heading_data_load_attr); ?> data-aos-delay="<?php echo ($heading_aos_delay); ?>" data-aos-duration="<?php echo ($heading_data_load_duration); ?>">
                                    <?php
                                endif;
                                // Get tinymce content --------------------------------------------------------------
                                Irantheme_Common_Static::tinymce_heading($fields_values, $extra_css);
                                if ($tinymce_heading != '' && $heading_data_load_checkbox == 'on'):
                                    ?>
                                    </div>
                            <?php
                                endif;
                            }
                            ?>
                            <?php
                            // Heading Display mode --------------------------------------------------------------
                            $heading_display_mode_radio = $fields_values['display_mode'];
                            $heading_display_mode_class = '';
                            if ($heading_display_mode_radio == 'right') {
                                $heading_display_mode_class = 'flex-row';
                            } elseif ($heading_display_mode_radio == 'left') {
                                $heading_display_mode_class = 'flex-row-reverse';
                            }
                            // Service col Display mode --------------------------------------------------------------
                            $service_col_display_mode_radio = $fields_values['service_display_mode'];
                            $service_col_display_mode_class = '';
                            if ($service_checkbox == 'on') {
                                if ($service_col_display_mode_radio == 'right') {
                                    $service_col_display_mode_class = 'flex-row';
                                } elseif ($service_col_display_mode_radio == 'left') {
                                    $service_col_display_mode_class = 'flex-row-reverse';
                                }
                            }
                            // Service bg color --------------------------------------------------------------
                            $service_color_bg_css = sprintf('background:%s;', Irantheme_Color_Static::get_colors_options_list('primary_color', 5, 0.075)['rgb_trans']);
                            ?>
                            <div class="row justify-content-center <?php echo esc_attr($communication_heading_transfer == 'on' ? $heading_display_mode_class : $service_col_display_mode_class); ?>">
                                <?php
                                // Set column of contact in service status or nothing
                                $contact_bootstrap_class = 'col-lg-12';
                                // Check transform heading to columns or Show services
                                if ($service_checkbox == 'on' || $communication_heading_transfer == 'on') :
                                    $contact_bootstrap_class = 'col-lg-7';
                                ?>
                                    <div class="col-lg-5" <?php echo ($data_load_attr); ?> data-aos-delay="<?php echo ($aos_delay); ?>" data-aos-duration="<?php echo ($data_load_duration); ?>">
                                        <!-- Service holder -->
                                        <div class="irantheme-communication-contact-service-holder" style="<?php echo esc_attr($service_color_bg_css); ?><?php echo esc_attr($border_radius_style); ?>">
                                            <?php
                                            // Check transform heading
                                            if ($communication_heading_transfer == 'on') {
                                            ?>
                                                <?php
                                                // Get tinymce simple text
                                                Irantheme_Common_Static::get_snippet_tinymce_simple($tinymce_heading, $base_color_class, '', Irantheme_Section_Service::get_alignment_class($tinymce_heading_alignment));
                                                ?>
                                            <?php
                                            } elseif ($service_checkbox == 'on' && $communication_heading_transfer != 'on') {
                                                // Get service heading checkbox --------------------------------------------------------------
                                                $tinymce_service_heading_checkbox = $fields_values['service_heading_checkbox'];
                                                // Get service heading --------------------------------------------------------------
                                                $tinymce_service_heading = $fields_values['service_text_editor_heading'];
                                            ?>
                                                <div class="irantheme-object-group m-0" data-json="<?php echo esc_attr('objects_json'); ?>">
                                                    <?php
                                                    if ($tinymce_service_heading_checkbox == 'on') :
                                                    ?>
                                                        <?php
                                                        // Get tinymce simple text
                                                        Irantheme_Common_Static::get_snippet_tinymce_simple($tinymce_service_heading, $base_color_class, '', Irantheme_Section_Service::get_alignment_class('right'));
                                                        ?>
                                                    <?php
                                                    endif;
                                                    // Service content alignment --------------------------------------------------------------
                                                    $service_content_alignment = $fields_values['service_alignment_content_radio'];
                                                    // Get details color
                                                    $title_color_css = '';
                                                    if ($section_details_color && $section_details_color != 'off') {
                                                        $title_color_css = 'color:#' . Irantheme_Color_Static::get_hex_color($section_details_color) . ';';
                                                    }
                                                    // Service custom fields --------------------------------------------------------------
                                                    foreach ($json_post_array as $object_post) {
                                                        // Get title options
                                                        $service_title = $object_post['fields']['service_title'];
                                                        // Get description option
                                                        $service_desc = $object_post['fields']['service_desc'];
                                                        // Get icon option
                                                        $service_icon = $object_post['fields']['service_icon'];
                                                        // Get button title option
                                                        $service_btn_title = $object_post['fields']['service_btn_title'];
                                                        // Get button link option
                                                        $service_btn_link = $object_post['fields']['service_btn_link'];
                                                        // Alignment --------------------------------------------------------------
                                                        $alignment_class = '';
                                                        $service_item_wrapper_css = '';
                                                        if ($service_content_alignment != 'center') {
                                                            $alignment_class = 'irantheme-service-item-icon-side';
                                                            if ($service_content_alignment == 'right') {
                                                                $service_item_wrapper_css = 'flex-direction: row;';
                                                            } elseif ($service_content_alignment == 'left') {
                                                                $service_item_wrapper_css = 'flex-direction: row-reverse;';
                                                            }
                                                        }
                                                    ?>
                                                        <!-- Service item -->
                                                        <div class="irantheme-object" style="margin-bottom: 0;" data-object="<?php echo esc_attr($object_post['object_id']); ?>">
                                                            <div class="irantheme-service-item" style="overflow:unset;" <?php echo ($data_load_attr); ?> data-aos-delay="<?php echo ($aos_delay); ?>" data-aos-duration="<?php echo ($data_load_duration); ?>">
                                                                <!-- Wrapper -->
                                                                <div class="irantheme-service-item-wrapper <?php echo esc_attr(Irantheme_Section_Service::get_alignment_class($service_content_alignment)); ?>">
                                                                    <div class="irantheme-service-item-wrapper-content <?php echo esc_attr($alignment_class); ?>" style="<?php echo esc_attr($service_item_wrapper_css); ?>">
                                                                        <!-- Icon -->
                                                                        <?php if ($service_icon) : ?>
                                                                            <div class="irantheme-service-item-icon" style="<?php echo (!$service_btn_title && !$service_btn_link && !$service_desc) ? 'display:flex;margin-bottom:unset;' : ''; ?>">
                                                                                <i class="<?php echo esc_attr($service_icon); ?>" style="<?php echo ($title_color_css); ?>"></i>
                                                                            </div>
                                                                        <?php endif; ?>
                                                                        <!-- Content -->
                                                                        <div class="irantheme-service-item-content <?php echo (!$service_btn_title && !$service_btn_link && !$service_desc) ? 'justify-content-center' : ''; ?>">
                                                                            <!-- Title -->
                                                                            <?php if ($service_title) : ?>
                                                                                <h3><?php echo esc_html($service_title); ?></h3>
                                                                            <?php endif; ?>
                                                                            <!-- Description -->
                                                                            <?php if ($service_desc) : ?>
                                                                                <p style="font-size: 0.8rem;margin-bottom:0.5rem;"><?php echo esc_html($service_desc); ?></p>
                                                                            <?php endif; ?>
                                                                            <!-- Button -->
                                                                            <?php if ($service_btn_title || $service_btn_link) : ?>
                                                                                <div class="irantheme-service-item-content-button">
                                                                                    <a href="<?php echo esc_attr($service_btn_link ? $service_btn_link : get_home_url()); ?>" class="irantheme-btn-void"><?php echo esc_html($service_btn_title ? $service_btn_title : 'جزئیات بیشتر'); ?></a>
                                                                                </div>
                                                                            <?php endif; ?>
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
                                            <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                <?php
                                    // Increase aos delay for next item
                                    $aos_delay += 200;
                                endif;
                                ?>

                                <div class="<?php echo esc_attr($contact_bootstrap_class); ?>" <?php echo ($data_load_attr); ?> data-aos-delay="<?php echo ($aos_delay); ?>" data-aos-duration="<?php echo ($data_load_duration); ?>">
                                    <?php
                                    // Get form heading checkbox --------------------------------------------------------------
                                    $tinymce_form_heading_checkbox = $fields_values['form_heading_checkbox'];
                                    if ($tinymce_form_heading_checkbox == 'on') :
                                    ?>
                                        <div class="col-lg-12" style="margin:0;">
                                            <?php
                                            // Get form heading --------------------------------------------------------------
                                            $tinymce_form_heading = $fields_values['form_text_editor_heading'];
                                            // Get tinymce simple text
                                            Irantheme_Common_Static::get_snippet_tinymce_simple($tinymce_form_heading, $base_color_class, '', Irantheme_Section_Service::get_alignment_class('right'));
                                            ?>
                                        </div>
                                    <?php
                                    endif;
                                    // Shortcode checkbox --------------------------------------------------------------
                                    $shortcode_checkbox = $fields_values['plugin_form_checkbox'];
                                    // Shortcode content --------------------------------------------------------------
                                    $shortcode_content = $fields_values['plugin_form_shortcode'];
                                    if ($shortcode_checkbox == 'on') :
                                    ?>
                                        <div class="irantheme-input-form-fields <?php echo esc_attr(Irantheme_Common_Static::get_base_color_class($base_color)); ?>">
                                            <?php
                                            echo do_shortcode($shortcode_content);
                                            ?>
                                        </div>
                                    <?php

                                    // Irantheme custom form --------------------------------------------------------------
                                    elseif ($shortcode_checkbox == 'off') :
                                        // Set details colors --------------------------------------------------------------
                                        $color_details_class = Irantheme_Color_Static::get_class_color_mode('color', null, Irantheme_Color_Static::get_hex_color($section_details_color));
                                        $color_details_class_btn = '';
                                        if ($section_details_color != 'off') {
                                            $color_details_class_btn = Irantheme_Color_Static::get_class_color_mode('btn', null, Irantheme_Color_Static::get_hex_color($section_details_color));
                                        } else {
                                            if ($base_color == 'white') {
                                                $color_details_class_btn = 'irantheme-btn-dark-outline';
                                            } elseif ($base_color == 'dark') {
                                                $color_details_class_btn = 'irantheme-btn-white-outline';
                                            }
                                        }
                                    ?>
                                        <div class="irantheme-contact-form irantheme-input-form-fields <?php echo esc_attr(Irantheme_Common_Static::get_base_color_class($base_color)); ?>">
                                            <?php
                                            // Name field
                                            $form_name_checkbox = $fields_values['form_input_name'];
                                            if ($form_name_checkbox == 'on') :
                                            ?>
                                                <!-- Name input -->
                                                <label class="irantheme-input-label <?php echo esc_attr(Irantheme_Common_Static::get_base_color_class($base_color)); ?>">نام</label>
                                                <input type="text" class="irantheme-contact-form-name irantheme-input-text <?php echo esc_attr(Irantheme_Common_Static::get_base_color_class($base_color)); ?>" style="<?php echo esc_attr($border_radius_style); ?>">
                                            <?php endif; ?>
                                            <?php
                                            // Email field
                                            $form_email_checkbox = $fields_values['form_input_email'];
                                            if ($form_email_checkbox == 'on') :
                                            ?>
                                                <!-- Email input -->
                                                <label class="irantheme-input-label <?php echo esc_attr(Irantheme_Common_Static::get_base_color_class($base_color)); ?>">ایمیل<i class="fa-light fa-circle-info <?php echo esc_attr($color_details_class); ?>" title="ضروری"></i></label>
                                                <input type="email" class="irantheme-contact-form-email irantheme-input-email <?php echo esc_attr(Irantheme_Common_Static::get_base_color_class($base_color)); ?>" style="<?php echo esc_attr($border_radius_style); ?>" required>
                                            <?php endif; ?>
                                            <?php
                                            // Website field
                                            $form_website_checkbox = $fields_values['form_input_website'];
                                            if ($form_website_checkbox == 'on') :
                                            ?>
                                                <!-- Website input -->
                                                <label class="irantheme-input-label <?php echo esc_attr(Irantheme_Common_Static::get_base_color_class($base_color)); ?>">وب سایت</label>
                                                <input type="url" class="irantheme-contact-form-website irantheme-input-url <?php echo esc_attr(Irantheme_Common_Static::get_base_color_class($base_color)); ?>" style="<?php echo esc_attr($border_radius_style); ?>">
                                            <?php endif; ?>
                                            <?php
                                            // Subject field
                                            $form_subject_checkbox = $fields_values['form_input_subject'];
                                            if ($form_subject_checkbox == 'on') :
                                            ?>
                                                <!-- Subject input -->
                                                <label class="irantheme-input-label <?php echo esc_attr(Irantheme_Common_Static::get_base_color_class($base_color)); ?>">موضوع<i class="fa-light fa-circle-info <?php echo esc_attr($color_details_class); ?>" title="ضروری"></i></label>
                                                <input type="text" class="irantheme-contact-form-subject irantheme-input-text <?php echo esc_attr(Irantheme_Common_Static::get_base_color_class($base_color)); ?>" style="<?php echo esc_attr($border_radius_style); ?>" required>
                                            <?php endif; ?>
                                            <?php
                                            // Description field
                                            $form_desc_checkbox = $fields_values['form_input_desc'];
                                            if ($form_desc_checkbox == 'on') :
                                            ?>
                                                <!-- Description input -->
                                                <label class="irantheme-input-label <?php echo esc_attr(Irantheme_Common_Static::get_base_color_class($base_color)); ?>">توضیحات<i class="fa-light fa-circle-info <?php echo esc_attr($color_details_class); ?>" title="ضروری"></i></label>
                                                <textarea cols="30" rows="10" class="irantheme-contact-form-desc irantheme-input-textarea <?php echo esc_attr(Irantheme_Common_Static::get_base_color_class($base_color)); ?>" style="<?php echo esc_attr($border_radius_style); ?>" required></textarea>
                                            <?php endif; ?>
                                            <!-- Button submit -->
                                            <button type="button" class="irantheme-contact-form-submit irantheme-btn irantheme-btn-large <?php echo esc_attr($color_details_class_btn); ?>" style="<?php echo esc_attr($border_radius_style); ?>">ارسال کردن</button>
                                        </div>
                                    <?php endif; ?>
                                </div>
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
            </section>
<?php
        }
    }
}
