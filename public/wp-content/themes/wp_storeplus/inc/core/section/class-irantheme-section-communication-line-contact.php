<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;

if (!class_exists('Irantheme_Section_Communication_Line_Contact')) {
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
    class Irantheme_Section_Communication_Line_Contact extends Irantheme_Section
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
            $heading_content_service = '<h4 style="font-size:2.4em;">فرصت های شغلی را در کنار ما تجربه کنید.</h4>';
            $heading_content_form = '<h4 style="font-size:1.2em;">فرم ارتباط با ما</h4>';
            $settings = array(
                array(
                    'title' => 'عنوان و سرویس',
                    'id' => 'service',
                    'description'            => __('تنظیمات مربوط به محتوای عنوان و سرویس', self::$static_textdomain),
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
                            $status_default ? Irantheme_Section_Default::get_default_ids_objects(1, 'communication_line_contact', $status_default) : '',
                        ),
                        array(
                            'id'             => 'heading_checkbox',
                            'label'            => __(
                                'نمایش عنوان بالای فرم',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورتی که نیازمند نمایش عنوان در بالای فرم تماس می باشید می توانید این گزینه را فعال کنید.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'on' : '',
                            'keywords' => array('نمایش عنوان بالای فرم', 'display heading in above of form'),
                            'id_dependents_on' => array('service_text_editor_heading'),
                            'id_dependents_off' => array(),
                            'base_priority' => 'checkbox_base',
                            'selector_priority' => 'checkbox_heading',
                            'z_index' => 0
                        ),
                        array(
                            'id'             => 'service_text_editor_heading',
                            'label'            => __('عنوان ستون سرویس ها', self::$static_textdomain),
                            'description'    => __('محتوای مورد نظر خود را وارد کنید.این عنوان در بالای سرویس های انتخاب شده برای نمایش در کنار فرم ارتباطی نمایش داده می شود.'),
                            'type'            => 'text_editor',
                            'default'        => $status_default ? $heading_content_service : '',
                            'placeholder'    => __('', self::$static_textdomain),
                            'keywords' => array('عنوان ستون سرویس ها', 'heading service column'),
                        ),
                        array(
                            'id'             => 'service_checkbox',
                            'label'            => __(
                                'نمایش سرویس در کنار عنوان',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورتی که نیازمند نمایش سرویس در کنار عنوان در بالای فرم تماس می باشید می توانید این گزینه را فعال کنید.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'on' : '',
                            'keywords' => array('نمایش سرویس در کنار عنوان', 'display service alongside of heading'),
                        ),
                        array(
                            'id'             => 'button_checkbox',
                            'label'            => __(
                                'نمایش دکمه در کنار عنوان',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورتی که نیازمند نمایش دکمه در کنار عنوان در بالای فرم تماس می باشید می توانید این گزینه را فعال کنید.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'on' : '',
                            'keywords' => array('نمایش دکمه در کنار عنوان', 'display service alongside of heading'),
                            'id_dependents_on' => array('button_title', 'button_link', 'button_color_select'),
                            'id_dependents_off' => array(),
                            'base_priority' => 'checkbox_base',
                            'selector_priority' => 'checkbox_button',
                            'z_index' => 0
                        ),
                        array(
                            'id'             => sprintf(
                                'button_title'
                            ),
                            'label'            => __(
                                'عنوان دکمه',
                                self::$static_textdomain

                            ),
                            'description'    => __('عنوان دلخواه خود را اینجا وارد کنید.'),
                            'type'            => 'text',
                            'default'        => $status_default ? 'ارتباط با ما' : '',
                            'placeholder'    => __('عنوان', self::$static_textdomain),
                            'keywords' => array('button', 'title', 'btn', 'text', 'دکمه', 'عنوان', 'تیتر'),
                        ),
                        array(
                            'id'             => sprintf(
                                'button_link'
                            ),
                            'label'            => __(
                                'لینک دکمه',
                                self::$static_textdomain

                            ),
                            'description'    => __('لینک دکمه را اینجا وارد کنید.'),
                            'type'            => 'text',
                            'default'        => $status_default ? '' : '',
                            'placeholder'    => __('برای مثال: https://www.mywebsite.com/page', self::$static_textdomain),
                            'keywords' => array('button', 'title', 'btn', 'text', 'دکمه', 'عنوان', 'تیتر'),
                        ),
                        array(
                            'id'             => sprintf(
                                'button_color_select'
                            ),
                            'label'            => __(
                                'رنگ دکمه',
                                self::$static_textdomain
                            ),
                            'description'    => __('برای انتخاب رنگ دکمه از لیست رنگ دلخواه خود را انتخاب کنید.', self::$static_textdomain),
                            'type'            => 'select',
                            'options'        => Irantheme_Color_Static::get_all_colors_options(true),
                            'color_preview' => true,
                            'default'        => $status_default ? 'transparent' : '',
                            'keywords' => array('button', 'title', 'btn', 'text', 'دکمه', 'عنوان', 'تیتر'),
                        ),
                    )
                ),
                array(
                    'title' => 'تنظیمات فرم ارتباطی',
                    'id' => 'form',
                    'description'            => __('تنظیمات مربوط به فرم ارتباطی مانند : نوع فرم، نوع ورودی های فرم و...', self::$static_textdomain),
                    'icon' => 'fa-tty',
                    'fields' => array(
                        array(
                            'id'             => 'form_display_checkbox',
                            'label'            => __(
                                'نمایش فرم ارتباطی',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت تمایل می توانید فرم ارتباطی را نمایش یا مخفی کنید.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'on' : '',
                            'keywords' => array('نمایش دکمه در کنار عنوان', 'display service alongside of heading'),
                            'id_dependents_on' => array('form_text_editor_heading', 'plugin_form_checkbox', 'form_input_desc', 'form_button_color_select'),
                            'id_dependents_off' => array(),
                            'base_priority' => 'checkbox_base',
                            'selector_priority' => 'checkbox_form_contact',
                            'z_index' => 0
                        ),
                        array(
                            'id'             => 'form_text_editor_heading',
                            'label'            => __('عنوان ردیف فرم', self::$static_textdomain),
                            'description'    => __('در صورت تمایل می توانید عنوانی برای ردیف فرم تعیین نمایید.'),
                            'type'            => 'text_editor',
                            'default'        => $status_default ? $heading_content_form : '',
                            'placeholder'    => __('', self::$static_textdomain),
                            'keywords' => array('عنوان ردیف فرم', 'heading form row'),
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
                            'selector_priority' => 'checkbox_form_contact',
                            'z_index' => 1
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
                        array(
                            'id'             => sprintf(
                                'form_button_color_select'
                            ),
                            'label'            => __(
                                'رنگ دکمه فرم',
                                self::$static_textdomain
                            ),
                            'description'    => __('برای انتخاب رنگ دکمه از لیست رنگ دلخواه خود را انتخاب کنید.', self::$static_textdomain),
                            'type'            => 'select',
                            'options'        => Irantheme_Color_Static::get_all_colors_options(true),
                            'color_preview' => true,
                            'default'        => $status_default ? 'primary' : '',
                            'keywords' => array('رنگ دکمه فرم', 'form button color'),
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
                            'default'        => $status_default ? 'off' : '',
                            'keywords' => array('detail color', 'رنگ جزئیات'),
                        ),
                        array(
                            'id'             => 'form_base_color_select',
                            'label'            => __(
                                'رنگ زمینه فرم',
                                self::$static_textdomain
                            ),
                            'description'    => __('برای انتخاب رنگ زمینه فرم تماس می توانید رنگ خود را انتخاب کنید.', self::$static_textdomain),
                            'type'            => 'select',
                            'options'        => array('white' => 'سفید', 'dark' => 'مشکی'),
                            'default'        => $status_default ? 'white' : '',
                            'keywords' => array('color', 'background', 'پس زمینه', 'رنگ'),
                        ),
                        array(
                            'id'             => 'separator_checkbox',
                            'label'            => __(
                                'نمایش خط جدا کننده',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه شما می توانید خط جدا کننده ای در میان عناصر عنوان و فرم ایجاد کنید.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'on' : '',
                            'keywords' => array('نمایش خط جدا کننده', 'display separator line'),
                        ),
                    )
                ),
                // Get layout scale fields
                Irantheme_Section_Utils::layout_scale_fields_generator($status_default, array('inner_padding', 'outer_padding', 'radius'), 'on', 2, 2, 'off', 'off', 0, 0, 1),
                // Get background fields
                Irantheme_Section_Utils::background_fields_generator($status_default, array('image', 'color', 'mask', 'parallax'), $status_default === 'media' ? Irantheme_Common_Static::get_file_url_media_library('') : '', 'off', true, 'off', 'dark', 0.9, 'off'),
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
                    'تلفن تماس',
                );

                // List of default icons
                $icons = array(
                    'fa-light fa-phone',
                );
                // Default descriptions
                $descriptions = array(
                    '+۹۸ - ۰۹۱۲۱۲۳۴۵۶۷',
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

            // Get heading checkbox --------------------------------------------------------------
            $heading_checkbox = $fields_values['heading_checkbox'];

            // Get service checkbox --------------------------------------------------------------
            $service_checkbox = $fields_values['service_checkbox'];

            // Get button checkbox --------------------------------------------------------------
            $button_checkbox = $fields_values['button_checkbox'];

            // Get button title --------------------------------------------------------------
            $button_title = $fields_values['button_title'];

            // Get button link --------------------------------------------------------------
            $button_link = $fields_values['button_link'];

            // Get button color select --------------------------------------------------------------
            $button_color_select = $fields_values['button_color_select'];

            // Get button form color select --------------------------------------------------------------
            $button_form_color_select = $fields_values['form_button_color_select'];

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
            $aos_delay = 200;

            // Base color --------------------------------------------------------------
            $base_color = $fields_values['base_color_select'];
            // Set base color class
            $base_color_class = Irantheme_Common_Static::get_base_color_class($base_color);

            // Base color --------------------------------------------------------------
            $base_color_form = $fields_values['form_base_color_select'];
            // Set base color class
            $base_color_form_class = Irantheme_Common_Static::get_base_color_class($base_color_form);

            // Details color --------------------------------------------------------------
            $section_details_color = $fields_values['details_color_select'];

            // Section transform to up and down --------------------------------------------------------------
            $section_transfrom_values = Irantheme_Section_Utils::get_transform_values_arrays($data_page, $fields_values, true);
            $section_transform_extra_css = $section_transfrom_values['overflow'];
            $section_transform_translate_css = $section_transfrom_values['css'];

            // Get separator line --------------------------------------------------------------
            $separator_checkbox = $fields_values['separator_checkbox'];

            // Get form display --------------------------------------------------------------
            $form_display_checkbox = $fields_values['form_display_checkbox'];

            // Set details colors --------------------------------------------------------------
            $color_details_class = Irantheme_Color_Static::get_class_color_mode('color', null, Irantheme_Color_Static::get_hex_color($section_details_color));
            $color_details_class_btn = '';
            if ($base_color == 'white') {
                $color_details_class_btn = 'irantheme-btn-dark';
                $separator_class = 'irantheme-separator-line dark';
            } elseif ($base_color == 'dark') {
                $color_details_class_btn = 'irantheme-btn-white';
                $separator_class = 'irantheme-separator-line white';
            }


            // Get button color class
            $button_color_bg_class = '';
            // Check base color option
            if ($base_color == 'white' && $button_color_select == 'off') {
                $button_color_bg_class = 'irantheme-btn-dark';
            } elseif (
                $base_color == 'dark' && $button_color_select == 'off'
            ) {
                $button_color_bg_class = 'irantheme-btn-white';
            } elseif ($base_color == 'white' && $button_color_select == 'transparent') {
                $button_color_bg_class = 'irantheme-btn-dark-outline';
            } elseif (
                $base_color == 'dark' && $button_color_select == 'transparent'
            ) {
                $button_color_bg_class = 'irantheme-btn-white-outline';
            } else {
                $button_color_bg_class = Irantheme_Color_Static::get_class_color_mode('btn', null, Irantheme_Color_Static::get_hex_color($button_color_select));
            }

            // Get button color class
            $button_form_color_bg_class = '';
            // Check base color option
            if ($base_color == 'white' && $button_form_color_select == 'off') {
                $button_form_color_bg_class = 'irantheme-btn-dark';
            } elseif (
                $base_color == 'dark' && $button_form_color_select == 'off'
            ) {
                $button_form_color_bg_class = 'irantheme-btn-white';
            } elseif ($base_color == 'white' && $button_form_color_select == 'transparent') {
                $button_form_color_bg_class = 'irantheme-btn-dark-outline';
            } elseif (
                $base_color == 'dark' && $button_form_color_select == 'transparent'
            ) {
                $button_form_color_bg_class = 'irantheme-btn-white-outline';
            } else {
                $button_form_color_bg_class = Irantheme_Color_Static::get_class_color_mode('btn', null, Irantheme_Color_Static::get_hex_color($button_form_color_select));
            }

            // bootstrap columns calculation
            $heading_bootstrap_column = 'col-lg-8';
            $service_bootstrap_column = 'col-lg-2';
            $button_bootstrap_column = 'col-lg-2';
            if ($heading_checkbox == 'on' && $service_checkbox == 'on' && $button_checkbox == 'on') {
                $heading_bootstrap_column = 'col-lg-8';
                $service_bootstrap_column = 'col-lg-2';
                $button_bootstrap_column = 'col-lg-2';
            } else if ($heading_checkbox == 'on' && $service_checkbox == 'off' && $button_checkbox == 'on') {
                $heading_bootstrap_column = 'col-lg-10';
                $service_bootstrap_column = '';
                $button_bootstrap_column = 'col-lg-2';
            } else if ($heading_checkbox == 'on' && $service_checkbox == 'off' && $button_checkbox == 'off') {
                $heading_bootstrap_column = 'col-lg-12';
                $service_bootstrap_column = '';
                $button_bootstrap_column = '';
            } else if ($heading_checkbox == 'off' && $service_checkbox == 'on' && $button_checkbox == 'on') {
                $heading_bootstrap_column = '';
                $service_bootstrap_column = 'col-lg-10';
                $button_bootstrap_column = 'col-lg-2';
            } else if ($heading_checkbox == 'off' && $service_checkbox == 'off' && $button_checkbox == 'on') {
                $heading_bootstrap_column = '';
                $service_bootstrap_column = '';
                $button_bootstrap_column = 'col-lg-2';
            } else if ($heading_checkbox == 'on' && $service_checkbox == 'on' && $button_checkbox == 'off') {
                $heading_bootstrap_column = 'col-lg-8';
                $service_bootstrap_column = 'col-lg-4';
                $button_bootstrap_column = '';
            }

            // Get section id --------------------------------------------------------------
            $section_id = $fields_values['element_id'];
            $section_id_attribute = '';
            if ($section_id) {
                $section_id_attribute = sprintf('id=%s', $section_id);
            }

            // Get border radius --------------------------------------------------------------
            $border_radius_range = $fields_values['border_radius_range'];
            $border_radius_style = Irantheme_Section_Utils::get_border_radius_css($border_radius_range);
?>
            <!-- ============================= Section communication line contact ============================= -->
            <section <?php echo esc_attr($section_id_attribute); ?> class="irantheme-section irantheme-communication-line-contact-section" <?php echo esc_attr(Irantheme_Page_Builder::get_attr_data_section($data_type, $data_value, $data_page, $data_ajax)); ?> style="<?php echo esc_attr($section_transform_translate_css); ?><?php echo esc_attr($border_radius_style) ?><?php echo esc_attr(Irantheme_Section_Service::get_custom_outer_padding_css($fields_values)); ?>">
                <div class="irantheme-communication-line-contact <?php echo esc_attr($base_color_class); ?> irantheme-background-jarallax" style="<?php echo esc_attr(Irantheme_Section_Service::get_custom_inner_padding_css($fields_values)); ?><?php echo esc_attr($background_color_css) ?><?php echo esc_attr($background_image_css) ?><?php echo esc_attr($section_transform_extra_css); ?>" <?php echo $background_parallax_attr; ?>>

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
                        <div class="irantheme-communication-line-contact-wrapper">
                            <div class="irantheme-communication-line-contact-heading" <?php echo ($data_load_attr); ?> data-aos-delay="<?php echo ($aos_delay); ?>" data-aos-duration="<?php echo ($data_load_duration); ?>">
                                <div class="row justify-content-between align-items-center">
                                    <?php if ($heading_checkbox == 'on'): ?>
                                        <div class="<?php echo esc_attr($heading_bootstrap_column); ?>">
                                            <?php
                                            // Get service heading --------------------------------------------------------------
                                            $tinymce_service_heading = $fields_values['service_text_editor_heading'];
                                            // Get tinymce simple text
                                            Irantheme_Common_Static::get_snippet_tinymce_simple($tinymce_service_heading, $base_color_class, '', 'right');
                                            ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($service_checkbox == 'on'): ?>
                                        <div class="<?php echo esc_attr($service_bootstrap_column); ?>">
                                            <div class="irantheme-object-group row m-0" data-json="<?php echo esc_attr('objects_json'); ?>">
                                                <?php
                                                // Get details color
                                                $title_color_css = '';
                                                if ($section_details_color && $section_details_color != 'off') {
                                                    $title_color_css = 'color:#' . Irantheme_Color_Static::get_hex_color($section_details_color) . ';';
                                                }
                                                $count_service_calc = 'col-1';
                                                if ($heading_checkbox == 'off') {
                                                    $count_service_calc = 'col-' . count($json_post_array);
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
                                                    $alignment_mode = 'right';
                                                    $service_item_wrapper_css = '';
                                                    if ($alignment_mode != 'center') {
                                                        $alignment_class = 'irantheme-service-item-icon-side';
                                                        if ($alignment_mode == 'right') {
                                                            $service_item_wrapper_css = 'flex-direction: row;';
                                                        } elseif ($alignment_mode == 'left') {
                                                            $service_item_wrapper_css = 'flex-direction: row-reverse;';
                                                        }
                                                    }
                                                ?>
                                                    <!-- Service item -->
                                                    <div class="irantheme-object <?php echo esc_attr(Irantheme_Section_Service::get_bootstrap_class($count_service_calc, 12)); ?>" style="margin-bottom: 0;" data-object="<?php echo esc_attr($object_post['object_id']); ?>">
                                                        <div class="irantheme-service-item" style="overflow:unset;" <?php echo ($data_load_attr); ?> data-aos-delay="<?php echo ($aos_delay); ?>" data-aos-duration="<?php echo ($data_load_duration); ?>">
                                                            <!-- Wrapper -->
                                                            <div class="irantheme-service-item-wrapper <?php echo esc_attr(Irantheme_Section_Service::get_alignment_class($alignment_mode)); ?>">
                                                                <div class="irantheme-service-item-wrapper-content <?php echo esc_attr($alignment_class); ?>" style="<?php echo esc_attr($service_item_wrapper_css); ?>">
                                                                    <!-- Icon -->
                                                                    <?php if ($service_icon) : ?>
                                                                        <div class="irantheme-service-item-icon" style="<?php echo (!$service_btn_title && !$service_btn_link &&  !$service_desc) ? 'display:flex;margin-bottom:unset;' : ''; ?>">
                                                                            <i class="<?php echo esc_attr($service_icon); ?>" style="font-size: 1.8rem;<?php echo ($title_color_css); ?>"></i>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                    <!-- Content -->
                                                                    <div class="irantheme-service-item-content <?php echo (!$service_btn_title && !$service_btn_link &&  !$service_desc) ? 'justify-content-center' : ''; ?>">
                                                                        <!-- Title -->
                                                                        <?php if ($service_title) : ?>
                                                                            <h3 style="font-size: 0.9rem;margin-bottom:0;font-weight:bold;line-height:normal;"><?php echo esc_html($service_title); ?></h3>
                                                                        <?php endif; ?>
                                                                        <!-- Description -->
                                                                        <?php if ($service_desc) : ?>
                                                                            <p style="font-size: 0.8rem;margin-bottom:0.5rem;"><?php echo esc_html($service_desc); ?></p>
                                                                        <?php endif; ?>
                                                                        <!-- Button -->
                                                                        <?php if ($service_btn_title || $service_btn_link) : ?>
                                                                            <div class="irantheme-service-item-content-button">
                                                                                <a href="<?php echo esc_attr($service_btn_link ? $service_btn_link : get_home_url()); ?>" class="irantheme-btn-void" style="font-size:0.7rem;"><?php echo esc_html($service_btn_title ? $service_btn_title : 'جزئیات بیشتر'); ?></a>
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
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($button_checkbox == 'on'): ?>
                                        <div class="<?php echo esc_attr($button_bootstrap_column); ?>">
                                            <a href="<?php echo esc_url_raw($button_link); ?>" class="irantheme-btn irantheme-btn-large irantheme-btn-block <?php echo esc_attr($button_color_bg_class); ?> w-100 d-block text-center" style="<?php echo esc_attr($border_radius_style) ?>"><?php echo esc_html($button_title); ?></a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <?php
                            // Increase aos delay for next item
                            $aos_delay += 200;
                            ?>

                            <?php if ($separator_checkbox == 'on'): ?>
                                <hr class="<?php echo esc_attr($separator_class); ?>">
                            <?php endif; ?>

                            <?php
                            if ($form_display_checkbox == 'on'):
                                // Shortcode checkbox --------------------------------------------------------------
                                $shortcode_checkbox = $fields_values['plugin_form_checkbox'];
                                // Shortcode content --------------------------------------------------------------
                                $shortcode_content = $fields_values['plugin_form_shortcode'];
                                if ($shortcode_checkbox == 'on') :
                            ?>
                                    <div class="irantheme-input-form-fields <?php echo esc_attr(Irantheme_Common_Static::get_base_reverse_color_class($base_color)); ?>">
                                        <?php
                                        echo do_shortcode($shortcode_content);
                                        ?>
                                    </div>
                                <?php
                                // Irantheme custom form --------------------------------------------------------------
                                elseif ($shortcode_checkbox == 'off') :
                                ?>
                                    <div class="irantheme-contact-form irantheme-communication-line-contact-form irantheme-input-form-fields <?php echo esc_attr($base_color_form_class); ?>" <?php echo ($data_load_attr); ?> data-aos-delay="<?php echo ($aos_delay); ?>" data-aos-duration="<?php echo ($data_load_duration); ?>">
                                        <div class="row align-items-center">
                                            <?php
                                            // Get service heading --------------------------------------------------------------
                                            $tinymce_service_heading = $fields_values['form_text_editor_heading'];
                                            if ($tinymce_service_heading):
                                            ?>
                                                <div class="col">
                                                    <?php
                                                    // Get tinymce simple text
                                                    Irantheme_Common_Static::get_snippet_tinymce_simple($tinymce_service_heading, $base_color_class, '', 'right');
                                                    ?>
                                                </div>
                                            <?php endif; ?>
                                            <?php
                                            // Name field
                                            $form_name_checkbox = $fields_values['form_input_name'];
                                            if ($form_name_checkbox == 'on') :
                                            ?>
                                                <div class="col">
                                                    <input type="text" class="irantheme-contact-form-name irantheme-input-text <?php echo esc_attr(Irantheme_Common_Static::get_base_reverse_color_class($base_color)); ?> mb-0" style="<?php echo esc_attr($border_radius_style) ?>" placeholder="<?php echo esc_attr('نام'); ?>">
                                                </div>
                                            <?php endif; ?>
                                            <?php
                                            // Email field
                                            $form_email_checkbox = $fields_values['form_input_email'];
                                            if ($form_email_checkbox == 'on') :
                                            ?>
                                                <div class="col">
                                                    <input type="email" class="irantheme-contact-form-email irantheme-input-email <?php echo esc_attr(Irantheme_Common_Static::get_base_reverse_color_class($base_color)); ?> mb-0" style="<?php echo esc_attr($border_radius_style) ?>" placeholder="<?php echo esc_attr('ایمیل'); ?>" required>
                                                </div>
                                            <?php endif; ?>
                                            <?php
                                            // Website field
                                            $form_website_checkbox = $fields_values['form_input_website'];
                                            if ($form_website_checkbox == 'on') :
                                            ?>
                                                <div class="col">
                                                    <input type="url" class="irantheme-contact-form-website irantheme-input-url <?php echo esc_attr(Irantheme_Common_Static::get_base_reverse_color_class($base_color)); ?> mb-0" style="<?php echo esc_attr($border_radius_style) ?>" placeholder="<?php echo esc_attr('وب سایت'); ?>">
                                                </div>
                                            <?php endif; ?>
                                            <?php
                                            // Subject field
                                            $form_subject_checkbox = $fields_values['form_input_subject'];
                                            if ($form_subject_checkbox == 'on') :
                                            ?>
                                                <div class="col">
                                                    <input type="text" class="irantheme-contact-form-subject irantheme-input-text <?php echo esc_attr(Irantheme_Common_Static::get_base_reverse_color_class($base_color)); ?> mb-0" style="<?php echo esc_attr($border_radius_style) ?>" placeholder="<?php echo esc_attr('موضوع'); ?>" required>
                                                </div>
                                            <?php endif; ?>
                                            <?php
                                            // Description field
                                            $form_desc_checkbox = $fields_values['form_input_desc'];
                                            if ($form_desc_checkbox == 'on') :
                                            ?>
                                                <div class="col">
                                                    <textarea cols="30" rows="1" class="irantheme-contact-form-desc irantheme-input-textarea <?php echo esc_attr(Irantheme_Common_Static::get_base_reverse_color_class($base_color)); ?> mb-0" style="<?php echo esc_attr($border_radius_style) ?>" placeholder="<?php echo esc_attr('توضیحات'); ?>" required></textarea>
                                                </div>
                                            <?php endif; ?>
                                            <div class="col">
                                                <!-- Button submit -->
                                                <button type="button" class="irantheme-contact-form-submit irantheme-btn irantheme-btn-large w-100 <?php echo esc_attr($color_details_class_btn); ?> mb-0 <?php echo esc_attr($button_form_color_bg_class); ?>" style="<?php echo esc_attr($border_radius_style) ?>"><?php esc_html_e('ارسال کردن'); ?><i class="fa-light fa-arrow-left"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
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
