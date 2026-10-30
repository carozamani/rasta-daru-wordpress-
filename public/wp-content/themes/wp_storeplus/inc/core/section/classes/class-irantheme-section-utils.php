<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;

/**
 * A Tools for Sections
 * The Class is a Utils of Requirements for Sections
 * 
 * @package Wordpress
 * @subpackage Storeplus
 * @since v1.0
 * @author iranianclever
 * @see v1.0
 * 
 * This class is a custom settings theme for admin page.
 */
if (!class_exists('Irantheme_Section_Utils')) {
    class Irantheme_Section_Utils
    {
        private static $static_textdomain = IRANTHEME_TEXTDOMAIN;


        public function __construct() {}


        /**
         * Custom array insertion with position
         * @return void
         * @param array,integer,array
         */
        public static function array_insert(&$array, $position, $insert)
        {
            if (is_int($position)) {
                array_splice($array, $position, 0, $insert);
            } else {
                $pos   = array_search($position, array_keys($array));
                $array = array_merge(
                    array_slice($array, 0, $pos),
                    $insert,
                    array_slice($array, $pos)
                );
            }
        }


        /**
         * Generate page config settings settings
         * @return array Fields to be displayed on settings page
         */
        public static function page_config_settings()
        {
            $page_settings_visual = array(
                array(
                    'title' => 'تنظیمات سفارشی برگه',
                    'id' => 'irantheme_page_customizable',
                    'description'            => __('تنظیمات سفارشی برگه را فعال یا غیر فعال کنید.', self::$static_textdomain),
                    'icon' => 'fa-screwdriver-wrench',
                    'fields' => array(
                        array(
                            'id'             => 'irantheme_page_customizable_checkbox',
                            'label'            => __(
                                'فعال سازی صفحه ساز',
                                self::$static_textdomain
                            ),
                            'description'    => __('با فعال سازی این گزینه، تنظیمات پیش فرض قالب در "تنظیمات قالب" از کار خواهد افتاد و تنظیمات صفحه ساز در این برگه، جایگزین خواهد شد.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => 'off',
                            'keywords' => array('تنظیمات سفارشی'),
                        ),
                    )
                )
            );

            // Get all fields options
            $fields_options = Irantheme_Fields_Options::fields();
            // Header settings options fields
            $page_settings_options = $fields_options['other']['submenu'];
            $page_settings_options_result = array();

            // Get wrapper settings
            foreach ($page_settings_options as $field) {
                if ($field['id'] == 'irantheme_other_wrapper' || $field['id'] == 'background' || $field['id'] == 'irantheme_fonts_settings') {
                    array_push($page_settings_options_result, $field);
                }
            }

            // Insert header visual options fields To header settings options fields
            self::array_insert($page_settings_options_result, 0, $page_settings_visual);

            // Assign all settings fields
            $settings = $page_settings_options_result;

            $settings = apply_filters('irantheme_store_visual_demo_settings', $settings);

            return $settings;
        }


        /**
         * Generate header settings
         * @return array Fields to be displayed on settings page
         */
        public static function header()
        {
            $header_settings_visual = array(
                array(
                    'title' => 'هدر سفارشی',
                    'id' => 'irantheme_header_customizable',
                    'description'            => __('فعال یا غیرفعال سازی هدر سفارشی', self::$static_textdomain),
                    'icon' => 'fa-sliders-simple',
                    'fields' => array(
                        array(
                            'id'             => 'irantheme_header_customizable_checkbox',
                            'label'            => __(
                                'هدر سفارشی',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت نیاز به سفارشی کردن هدر در این برگه این گزینه را فعال کنید.در صورت غیر فعال بودن این گزینه کلیه تنظیمات انجام شده برای هدر این برگه نادیده گرفته شده و از تنظیمات انجام شده برای هدر کل صفحات سایت در بخش تنظیمات قالب استفاده می شود.
                                        ', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => 'off',
                            'keywords' => array('هدر سقارشی'),
                        ),
                    )
                )
            );

            // Header settings options fields
            $header_settings_options = Irantheme_Fields_Options::fields()['header']['submenu'];

            // Insert header visual options fields To header settings options fields
            self::array_insert($header_settings_options, 0, $header_settings_visual);

            // Assign all settings fields
            $settings = $header_settings_options;

            $settings = apply_filters('irantheme_store_visual_demo_settings', $settings);

            return $settings;
        }


        /**
         * Generate heading title settings
         * @return array Fields to be displayed on settings page
         */
        public static function heading_title_settings_generator($status_default, $load_style = 'none', $transfer_to_col = 'off', $col_display_mode = 'right', $content = 'محتوای مورد نظر خود را وارد کنید.', $btn_checkbox = 'off', $first_btn_text = '', $first_btn_link = '', $first_btn_icon = '', $first_btn_color = 'off', $second_btn_checkbox = 'off', $second_btn_text = '', $second_btn_link = '', $second_btn_icon = '', $second_btn_color = 'off', $button_mode = 'rectangle', $alignment = 'center', $text_slider_checkbox = 'off', $texts_of_slider = '', $padding_top = 3, $padding_bottom = 3, $buttons_size = 'medium', $heading_style = 'normal', $heading_style_color = 'primary', $heading_style_icon = 'fa-light fa-grid-2')
        {
            // General settings for heading title
            $setting_content = array(
                'title' => 'تنظیمات عنوان',
                'id' => 'content',
                'description'            => __('تنظیم متن، تراز بندی محتوا و دکمه ها و...', self::$static_textdomain),
                'icon' => 'fa-heading',
                'fields' => array(
                    array(
                        'id'             => 'text_editor_heading',
                        'label'            => __(
                            'عنوان',
                            self::$static_textdomain

                        ),
                        'description'    => __(''),
                        'type'            => 'text_editor',
                        'default'        => $status_default ? $content : '',
                        'placeholder'    => __('', self::$static_textdomain),
                        'keywords' => array(
                            'متن',
                            'text',
                            'title',
                            'تیتر',
                            'عنوان',
                        ),
                        'note' => __('در صورت تمایل به استفاده از اسلایدر متنی (تایپ استایل)، ابتدا گزینه اسلایدر متنی در پایین را فعال کرده سپس متن مورد نظر خود را بین سه کاراکتر "#" قرار دهید. برای مثال: "###جمله مورد نظر###"', self::$static_textdomain),
                    ),
                    array(
                        'id'             => 'slider_text_checkbox',
                        'label'            => __(
                            'اسلایدر متنی',
                            self::$static_textdomain
                        ),
                        'description'    => __('در صورت نیاز به استفاده از اسلایدر متنی، حتما این گزینه را فعال نمایید.', self::$static_textdomain),
                        'type'            => 'checkbox',
                        'default'        => $status_default ? $text_slider_checkbox : '',
                        'keywords' => array(
                            'متن',
                            'text',
                            'title',
                            'تیتر',
                            'عنوان',
                            'اسلایدر'
                        ),
                        'id_dependents_on' => array(
                            'slider_multi_text',
                            'slider_speed_write',
                            'slider_speed_remove',
                            'slider_delay'
                        ),
                        'base_priority' => 'checkbox_base',
                        'selector_priority' => 'checkbox_slider_text',
                        'z_index' => 0
                    ),
                    array(
                        'id'             => 'slider_multi_text',
                        'label'            => __(
                            'اسلایدر چند متنی',
                            self::$static_textdomain

                        ),
                        'description'    => __('در صورت نیاز به داشتن چند متن متفاوت در یک اسلایدر متنی از این فیلد برای تعریف کردن متن های خود در قالب الگویی که در ادامه گفته شده است استفاده کنید.در صورت عدم نیاز به این قابلیت، فیلد را خالی بگذارید.'),
                        'type'            => 'text',
                        'default'        => $status_default ? $texts_of_slider : '',
                        'placeholder'    => __('متن دلخواه', self::$static_textdomain),
                        'note' => __('اولین متن نمایش داده شده در هر اسلایدر متنی همان متنی است که در محتوای ویترین بین علامت های # قرار داده اید.</br>
                            الگو: برای جدا کردن جمله های مربوط به هریک از اسلایدر های متنی از هم، از کاراکتر (کاما انگلیسی) "," استفاده کنید. در صورت استفاده از چند اسلایدر متنی در محتوای ویترین و نیاز به استفاده از جمله های متفاوت در آن ها می توانید متن های مربوط به آن ها را با علامت "###" از هم جدا کنید.</br>
                            مثال(یک اسلایدر متنی): جمله اول,جمله دوم,جمله سوم,... </br>
                            مثال(چند اسلایدر متنی): جمله های اسلایدر اول###جمله های اسلایدر دوم###جمله های اسلایدر سوم### ...', self::$static_textdomain),
                        'keywords' => array(
                            'متن',
                            'text',
                            'title',
                            'تیتر',
                            'عنوان',
                            'اسلایدر'
                        ),
                    ),
                    array(
                        'id'             => 'slider_speed_write',
                        'label'            => __(
                            'تاخیر در تایپ کاراکتر',
                            self::$static_textdomain
                        ),
                        'description'    => __('شما می توانید با استفاده از این فیلد زمان تاخیر بین تایپ هر کاراکتر از متن اسلایدر متنی را تنظیم کنید. (واحد ms می باشد)', self::$static_textdomain),
                        'type'            => 'number',
                        'step' => 1,
                        'default'        => $status_default ? 100 : '',
                        'min' => 1,
                        'max' => 5000,
                        'placeholder'    => __('', self::$static_textdomain),
                        'keywords' => array(
                            'متن',
                            'text',
                            'title',
                            'تیتر',
                            'عنوان',
                            'اسلایدر'
                        ),
                    ),
                    array(
                        'id'             => 'slider_speed_remove',
                        'label'            => __(
                            'تاخیر در پاک کردن کاراکتر',
                            self::$static_textdomain
                        ),
                        'description'    => __('شما می توانید با استفاده از این فیلد زمان تاخیر بین پاک کردن هر کاراکتر از متن اسلایدر متنی را تنظیم کنید. (واحد ms می باشد)', self::$static_textdomain),
                        'type'            => 'number',
                        'step' => 1,
                        'default'        => $status_default ? 100 : '',
                        'min' => 1,
                        'max' => 5000,
                        'placeholder'    => __('', self::$static_textdomain),
                        'keywords' => array(
                            'متن',
                            'text',
                            'title',
                            'تیتر',
                            'عنوان',
                            'اسلایدر'
                        ),
                    ),
                    array(
                        'id'             => 'slider_delay',
                        'label'            => __(
                            'تاخیر بعد از تایپ متن',
                            self::$static_textdomain
                        ),
                        'description'    => __('شما می توانید با استفاده این فیلد میزان انتظار برای شروع عمل پاک کردن متن پس از این که متن بطور کامل تایپ می گردد را در اسلایدر متنی را تنظیم کنید. (واحد ms می باشد)', self::$static_textdomain),
                        'type'            => 'number',
                        'step' => 1,
                        'default'        => $status_default ? 3000 : '',
                        'min' => 10,
                        'max' => 10000,
                        'placeholder'    => __('', self::$static_textdomain),
                        'keywords' => array(
                            'متن',
                            'text',
                            'title',
                            'تیتر',
                            'عنوان',
                            'اسلایدر'
                        ),
                    ),
                    array(
                        'id'             => 'heading_style_radio',
                        'label'            => __(
                            'استایل عنوان',
                            self::$static_textdomain
                        ),
                        'description'    => __('در صورت تمایل می توانید استایل خلاقانه ای برای زیباتر شدن عنوان انتخاب کنید.', self::$static_textdomain),
                        'type'            => 'radio',
                        'options'        => array('normal' => 'عادی', 'dotted' => 'نقطه چین', 'parallel' => 'موازی', 'underline' => 'خط زیر', 'hashur' => 'هاشور', 'icon' => 'آیکون دار'),
                        'default'        => $status_default ? $heading_style : '',
                        'keywords' => array('استایل عنوان', 'heading style'),
                        'id_dependents_on' => array(
                            'icon' => array('heading_style_icon', 'heading_style_icon_size'),
                        ),
                        'id_dependents_off' => array(
                            'normal' => array('heading_style_icon', 'heading_style_icon_size'),
                            'dotted' => array('heading_style_icon', 'heading_style_icon_size'),
                            'parallel' => array('heading_style_icon', 'heading_style_icon_size'),
                            'underline' => array('heading_style_icon', 'heading_style_icon_size'),
                            'hashur' => array('heading_style_icon', 'heading_style_icon_size'),
                        ),
                        'base_priority' => 'radio_base',
                        'selector_priority' => 'radio_heading_style',
                        'z_index' => 0
                    ),
                    array(
                        'id'             => 'heading_style_icon',
                        'label'            => __(
                            'آیکون عنوان',
                            self::$static_textdomain
                        ),
                        'description'    => __('در صورت تمایل می توانید آیکونی جهت زیباتر شدن عنوان خود انتخاب نمایید.', self::$static_textdomain),
                        'type'            => 'icon',
                        'default'        => $heading_style_icon,
                        'placeholder'    => __('', self::$static_textdomain),
                        'keywords' => array('آیکون عنوان', 'heading icon'),
                    ),
                    array(
                        'id'             => 'heading_style_icon_size',
                        'label'            => __(
                            'اندازه آیکون',
                            self::$static_textdomain
                        ),
                        'description'    => __('در صورت تمایل می توانید اندازه آیکون خود را تنظیم نمایید.', self::$static_textdomain),
                        'type'            => 'range',
                        'min' => 0,
                        'max' => 4,
                        'step' => 0.1,
                        'default'        => 2,
                        'placeholder'    => __('', self::$static_textdomain),
                        'keywords' => array('اندازه آیکون', 'icon size'),
                    ),
                    array(
                        'id'             => 'heading_style_color_select',
                        'label'            => __(
                            'رنگ استایل',
                            self::$static_textdomain
                        ),
                        'description'    => __('برای انتخاب رنگ استایل از لیست، رنگ دلخواه خود را انتخاب کنید.', self::$static_textdomain),
                        'type'            => 'select',
                        'options'        => Irantheme_Color_Static::get_all_colors_options(),
                        'color_preview' => true,
                        'default'        => $status_default ? $heading_style_color : '',
                        'keywords' => array('رنگ استایل', 'style color'),
                    ),
                    array(
                        'id'             => 'heading_content_padding_top_range',
                        'label'            => __(
                            'فاصله عنوان از بالا',
                            self::$static_textdomain
                        ),
                        'description'    => __('در صورت تمایل می توانید فاصله از بالا را به میزان دلخواه خود تغییر دهید.', self::$static_textdomain),
                        'type'            => 'range',
                        'min' => 0,
                        'max' => 15,
                        'step' => 1,
                        'default'        => $status_default ? $padding_top : '',
                        'placeholder'    => __('Placeholder text', self::$static_textdomain),
                        'keywords' => array(
                            'متن',
                            'padding',
                            'title',
                            'تیتر',
                            'عنوان',
                            'فاصله'
                        ),
                    ),
                    array(
                        'id'             => 'heading_content_padding_bottom_range',
                        'label'            => __(
                            'فاصله عنوان از پایین',
                            self::$static_textdomain
                        ),
                        'description'    => __('در صورت تمایل می توانید فاصله از پایین را به میزان دلخواه خود تغییر دهید.', self::$static_textdomain),
                        'type'            => 'range',
                        'min' => 0,
                        'max' => 15,
                        'step' => 1,
                        'default'        => $status_default ? $padding_bottom : '',
                        'placeholder'    => __('Placeholder text', self::$static_textdomain),
                        'keywords' => array(
                            'متن',
                            'padding',
                            'title',
                            'تیتر',
                            'عنوان',
                            'فاصله'
                        ),
                    ),
                    array(
                        'id'             => 'btn_primary_checkbox',
                        'label'            => __(
                            'نمایش دکمه',
                            self::$static_textdomain
                        ),
                        'description'    => __('در صورت فعال بودن این گزینه شما می توانید حداکثر تعداد دو دکمه به عتوان خود اضافه نمایید.
								', self::$static_textdomain),
                        'type'            => 'checkbox',
                        'default'        => $status_default ? $btn_checkbox : '',
                        'keywords' => array('button', 'title', 'btn', 'text', 'دکمه', 'عنوان', 'تیتر'),
                        'id_dependents_on' => array('btn_primary_title', 'btn_primary_link', 'btn_primary_icon', 'btn_primary_color_select', 'btn_secondary_checkbox', 'btn_style_radio', 'btn_size_radio'),
                        'base_priority' => 'checkbox_base',
                        'selector_priority' =>
                        'checkbox_buttons',
                        'z_index' => 0
                    ),
                    array(
                        'id'             => 'btn_style_radio',
                        'label'            => __(
                            'استایل دکمه',
                            self::$static_textdomain
                        ),
                        'description'    => __('با استفاده از این گزینه می توانید استایل گوشه های دکمه(ها) را تعیین کنید.', self::$static_textdomain),
                        'type'            => 'radio',
                        'thumbnail_status' => true,
                        'options'        => array('rectangle' => array('مستطیلی', 'rectangle-btn.png'), 'circle' => array('منحنی', 'curve-btn.png')),
                        // 'options'        => array('rectangle' => 'مستطیلی', 'circle' => 'منحنی'),
                        'default'        => $status_default ? $button_mode : '',
                        'keywords' => array('button', 'title', 'btn', 'text', 'دکمه', 'عنوان', 'تیتر'),
                    ),
                    array(
                        'id'             => 'btn_size_radio',
                        'label'            => __(
                            'اندازه دکمه',
                            self::$static_textdomain
                        ),
                        'description'    => __('با استفاده از این گزینه می توانید اندازه دکمه(ها) را تعیین کنید.', self::$static_textdomain),
                        'type'            => 'radio',
                        'options'        => array('small' => 'کوچک', 'medium' => 'متوسط', 'large' => 'بزرگ'),
                        'default'        => $status_default ? $buttons_size : '',
                        'keywords' => array('اندازه دکمه', 'سایز دکمه', 'button size'),
                    ),
                    array(
                        'id'             => 'btn_primary_title',
                        'label'            => __(
                            'عنوان دکمه اول',
                            self::$static_textdomain

                        ),
                        'description'    => __('عنوان دلخواه خود را اینجا وارد کنید.'),
                        'type'            => 'text',
                        'default'        => $status_default ? $first_btn_text : '',
                        'placeholder'    => __('عنوان', self::$static_textdomain),
                        'keywords' => array('button', 'title', 'btn', 'text', 'دکمه', 'عنوان', 'تیتر'),
                    ),
                    array(
                        'id'             => 'btn_primary_link',
                        'label'            => __(
                            'لینک دکمه اول',
                            self::$static_textdomain

                        ),
                        'description'    => __('لینک دکمه را اینجا وارد کنید.'),
                        'type'            => 'text',
                        'default'        => $status_default ? $first_btn_link : '',
                        'placeholder'    => __('برای مثال: https://www.mywebsite.com/page', self::$static_textdomain),
                        'keywords' => array('button', 'title', 'btn', 'text', 'دکمه', 'عنوان', 'تیتر'),
                    ),
                    array(
                        'id'             => 'btn_primary_icon',
                        'label'            => __(
                            'آیکون دکمه اول',
                            self::$static_textdomain
                        ),
                        'description'    => __('در صورت نیاز به استفاده از آیکون(نماد) خاصی در کنار متن می توانید از این گزینه استفاده نمایید.', self::$static_textdomain),
                        'type'            => 'icon',
                        'default'        => $status_default ? $first_btn_icon : '',
                        'placeholder'    => __('', self::$static_textdomain),
                        'keywords' => array('button', 'title', 'btn', 'text', 'دکمه', 'عنوان', 'تیتر'),
                    ),
                    array(
                        'id'             => 'btn_primary_color_select',
                        'label'            => __(
                            'رنگ دکمه اول',
                            self::$static_textdomain
                        ),
                        'description'    => __('برای انتخاب رنگ دکمه از لیست رنگ دلخواه خود را انتخاب کنید.', self::$static_textdomain),
                        'type'            => 'select',
                        'options'        => Irantheme_Color_Static::get_all_colors_options(true),
                        'color_preview' => true,
                        'default'        => $status_default ? $first_btn_color : '',
                        'keywords' => array('button', 'title', 'btn', 'text', 'دکمه', 'عنوان', 'تیتر'),
                    ),
                    array(
                        'id'             => 'btn_secondary_checkbox',
                        'label'            => __(
                            'نمایش دکمه دوم',
                            self::$static_textdomain
                        ),
                        'description'    => __('در صورت نیاز به دکمه دوم این گزینه را فعال کنید.
								', self::$static_textdomain),
                        'type'            => 'checkbox',
                        'default'        => $status_default ? $second_btn_checkbox : '',
                        'keywords' => array('button', 'title', 'btn', 'text', 'دکمه', 'عنوان', 'تیتر'),
                        'id_dependents_on' => array('btn_secondary_title', 'btn_secondary_link', 'btn_secondary_icon', 'btn_secondary_color_select'),
                        'selector_priority' => 'checkbox_buttons',
                        'z_index' => 1
                    ),
                    array(
                        'id'             => 'btn_secondary_title',
                        'label'            => __(
                            'عنوان دکمه دوم',
                            self::$static_textdomain

                        ),
                        'description'    => __('عنوان دلخواه خود را اینجا وارد کنید.'),
                        'type'            => 'text',
                        'default'        => $status_default ? $second_btn_text : '',
                        'placeholder'    => __('عنوان', self::$static_textdomain),
                        'keywords' => array('button', 'title', 'btn', 'text', 'دکمه', 'عنوان', 'تیتر'),
                    ),
                    array(
                        'id'             => 'btn_secondary_link',
                        'label'            => __(
                            'لینک دکمه دوم',
                            self::$static_textdomain

                        ),
                        'description'    => __('لینک دکمه را اینجا وارد کنید.'),
                        'type'            => 'text',
                        'default'        => $status_default ? $second_btn_link : '',
                        'placeholder'    => __('برای مثال: https://www.mywebsite.com/page', self::$static_textdomain),
                        'keywords' => array('button', 'title', 'btn', 'text', 'دکمه', 'عنوان', 'تیتر'),
                    ),
                    array(
                        'id'             => 'btn_secondary_icon',
                        'label'            => __(
                            'آیکون دکمه دوم',
                            self::$static_textdomain
                        ),
                        'description'    => __('در صورت نیاز به استفاده از آیکون(نماد) خاصی در کنار متن می توانید از این گزینه استفاده نمایید.', self::$static_textdomain),
                        'type'            => 'icon',
                        'default'        => $status_default ? $second_btn_icon : '',
                        'placeholder'    => __('', self::$static_textdomain),
                        'keywords' => array('button', 'title', 'btn', 'text', 'دکمه', 'عنوان', 'تیتر'),
                    ),
                    array(
                        'id'             => 'btn_secondary_color_select',
                        'label'            => __(
                            'رنگ دکمه دوم',
                            self::$static_textdomain
                        ),
                        'description'    => __('برای انتخاب رنگ دکمه از لیست رنگ دلخواه خود را انتخاب کنید.', self::$static_textdomain),
                        'type'            => 'select',
                        'options'        => Irantheme_Color_Static::get_all_colors_options(true),
                        'color_preview' => true,
                        'default'        => $status_default ? $second_btn_color : '',
                        'keywords' => array('button', 'title', 'btn', 'text', 'دکمه', 'عنوان', 'تیتر'),
                    ),
                    array(
                        'id'             => 'alignment_heading_radio',
                        'label'            => __(
                            'موقعیت محتوا',
                            self::$static_textdomain
                        ),
                        'description'    => __('با استفاده از این گزینه می توانید موقعیت محتوای ویترین را تعیین کنید.', self::$static_textdomain),
                        'type'            => 'radio',
                        'thumbnail_status' => true,
                        'options'        => array('right' => array('راست چین', 'text-align-right.png'), 'center' => array('وسط چین', 'text-align-center.png'), 'left' => array('چپ چین', 'text-align-left.png')),
                        'default'        => $status_default ? $alignment : '',
                        'keywords' => array('محتوا', 'تنظیم', 'تراز', 'محور', 'alignment', 'float', 'position')
                    ),
                ),
            );

            // Insert heading transfer to col settings to end of general settings array
            if ($transfer_to_col) {
                // Heading transfer to col setting
                $transfer_to_col_settings = array(
                    'id'             => 'heading_transfer_checkbox',
                    'label'            => __(
                        'انتقال عنوان به ستون',
                        self::$static_textdomain
                    ),
                    'description'    => __('با استفاده ار این گزینه می توانید عنوان اصلی را به ستون جدید انتقال دهید.
								', self::$static_textdomain),
                    'type'            => 'checkbox',
                    'default'        => $status_default ? $transfer_to_col : '',
                    'keywords' => array('transfer to col', 'col', 'انتقال به ستون', 'انتقال'),
                    'id_dependents_on' => array(
                        'display_mode'
                    ),
                    'id_dependents_off' => array(),
                    'base_priority' => 'checkbox_base',
                    'selector_priority' => 'checkbox_heading_transfer',
                    'z_index' => 0
                );
                array_push($setting_content['fields'], $transfer_to_col_settings);

                if ($col_display_mode) {
                    // Display mode settings for when heading transfered to col 
                    $display_mode_settings = array(
                        'id'             =>
                        'display_mode',
                        'label'            => __(
                            'سبک چینش',
                            self::$static_textdomain
                        ),
                        'description'    => __('با استفاده از این گزینه می توانید جهت قرار گیری عنوان در کنار ستون دیگر را مشخص کنید.', self::$static_textdomain),
                        'type'            => 'radio',
                        'thumbnail_status' => true,
                        'options'        => array('right' => array('راست', 'align-section-right.png'), 'left' => array('چپ', 'align-section-left.png')),
                        'default'        => $status_default ? $col_display_mode : '',
                        'keywords' => array('disply', 'mode', 'سبک چینش',  'alignment')
                    );
                    array_push($setting_content['fields'], $display_mode_settings);
                }
            }
            // Insert Load style (aos) setting to end of general settings array
            if ($load_style) {
                // Load checkbox setting
                $load_checkbox_settings = array(
                    'id'             => 'heading_data_load_checkbox',

                    'label'            => __(
                        'فعال سازی بارگذاری همراه با پویانمایی',
                        self::$static_textdomain
                    ),
                    'description'    => __('با فعال کردن این گزینه می توانید بارگذاری اولیه "عنوان" همراه با پویانمایی (انیمیشن) را تنظیم کنید.', self::$static_textdomain),
                    'note'    => __('در صورت فعال بودن این گزینه لود اسکلتی عناصر غیرفعال خواهد شد.', self::$static_textdomain),
                    'type'            => 'checkbox',
                    'default'        => $status_default ? 'off' : '',
                    'keywords' => array('بارگذاری انیمیشن', 'animation loading', 'سبک بارگذاری',  'لود انیمیشن'),
                    'id_dependents_on' => array('heading_data_load_duration', 'heading_data_load_type', 'heading_data_load_delay'),
                    'base_priority' => 'checkbox_base',
                    'selector_priority' => 'checkbox_' . 'heading_data_animation_priority',
                    'z_index' => 0
                );
                array_push($setting_content['fields'], $load_checkbox_settings);
                $load_delay_setting =
                    array(
                        'id'             =>
                        'heading_data_load_delay',
                        'label'            => __(
                            'زمان تاخیر در شروع پویانمایی',
                            self::$static_textdomain
                        ),
                        'description'    => __('با استفاده از این گزینه می توانید مدت زمان تاخیر در شروع پویانمایی را تنظیم نمایید.', self::$static_textdomain),
                        'note'    => __('این عدد هرچقدر کوچک تر باشد انیمیشن زودتر و هرچه بزرگتر باشد دیرتر شروع می گردد.', self::$static_textdomain),
                        'type'            => 'number',
                        'step' => 50,
                        'default'        => $status_default ? 200 : '',
                        'min' => 0,
                        'max' => 5000,
                        'placeholder'    => __('', self::$static_textdomain),
                        'keywords' => array('میزان تاخیر بارگذاری', 'animation time', 'time loading', 'زمان انیمیشن', 'تاخیر انیمیشن'),
                    );
                array_push(
                    $setting_content['fields'],
                    $load_delay_setting
                );
                // Load style (aos) setting
                $load_duration_settings = array(
                    'id'             => 'heading_data_load_duration',

                    'label'            => __(
                        'طول زمان انجام پویانمایی',
                        self::$static_textdomain
                    ),
                    'description'    => __('با استفاده از این گزینه می توانید مدت زمان شروع تا پایان انجام پویانمایی(انیمیشن) را تنظیم نمایید.', self::$static_textdomain),
                    'note'    => __('این عدد هرچقدر کوچک تر باشد انیمیشن سریعتر به پایان می رسد و هرچه بزرگتر باشد آرام تر به پایان می رسد.', self::$static_textdomain),
                    'type'            => 'number',
                    'step' => 50,
                    'default'        => $status_default ? 800 : '',
                    'min' => 100,
                    'max' => 3000,
                    'placeholder'    => __('', self::$static_textdomain),
                    'keywords' => array('مدت زمان انجام انیمیشن', 'animation time', 'time loading', 'زمان انیمیشن', 'طول انیمیشن'),
                );
                array_push($setting_content['fields'], $load_duration_settings);
                // Load style (aos) setting
                $load_style_settings = array(
                    'id'             => 'heading_data_load_type',
                    'label'            => __(
                        'سبک بارگذاری',
                        self::$static_textdomain
                    ),
                    'description'    => __('با استفاده از این گزینه می توانید سبک بارگذاری و لود اولیه محتوای خود را مشخص کنید.', self::$static_textdomain),
                    'type'            => 'radio',
                    'thumbnail_status' => true,
                    'options'        => array('none' => array('عادی', 'none-aos.png'), 'fade-up' => array('از پایین', 'fade-up.gif'), 'fade-down' => array('از بالا', 'fade-down.gif'), 'fade-right' => array('از چپ', 'fade-right.gif'), 'fade-left' => array('از راست', 'fade-left.gif'), 'flip-left' => array('چرخش از چپ', 'flip-left.gif'), 'flip-right' => array('چرخش از راست', 'flip-right.gif'), 'flip-up' => array('چرخش از پایین', 'flip-up.gif'), 'flip-down' => array('چرخش از بالا', 'flip-down.gif'), 'zoom-in' => array('زوم از داخل', 'zoom-in.gif'), 'zoom-out' => array('زوم از خارج', 'zoom-out.gif'), 'zoom-out-right' => array('زوم از چپ', 'zoom-out-right.gif'), 'zoom-out-left' => array('زوم از راست', 'zoom-out-left.gif')),
                    'default'        => $status_default ? $load_style : '',
                    'keywords' => array(
                        'animation',
                        'load style',
                        'لود استایل',
                        'انیمیشن',
                        'سبک بارگذاری'
                    )
                );
                array_push($setting_content['fields'], $load_style_settings);
            }

            // Return heading title settings array
            return $setting_content;
        }


        /**
         * Return animation fields of aos js
         * @param mixed $status_default
         * @param array $data_load_options
         * @param string $data_load_checkbox_default_value
         * @param int|string $data_load_delay_default_value
         * @param int|string $data_load_duration_default_value
         * @param string $data_load_type_default_value
         * @return array
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function data_load_fields_generator($status_default, $data_load_options = array('delay', 'duration'), $data_load_checkbox_default_value = 'off', $data_load_delay_default_value = 200, $data_load_duration_default_value = 800, $data_load_type_default_value = 'none')
        {
            $data_load_settings = array(
                'title' => 'پویانمایی بارگذاری',
                'id' => 'data_load',
                'description'            => __('تنظیم سبک بارگذاری و انیمیشن ها', self::$static_textdomain),
                'icon' => 'fa-star-shooting',
                'fields' => array(
                    array(
                        'id'             => 'data_load_checkbox',
                        'label'            => __(
                            'فعال سازی بارگذاری همراه با پویانمایی',
                            self::$static_textdomain
                        ),
                        'description'    => __('با فعال کردن این گزینه می توانید بارگذاری اولیه عناصر همراه با پویانمایی (انیمیشن) را تنظیم کنید.', self::$static_textdomain),
                        'note'    => __('در صورت فعال بودن این گزینه لود اسکلتی عناصر غیرفعال خواهد شد.', self::$static_textdomain),
                        'type'            => 'checkbox',
                        'default'        => $status_default ? $data_load_checkbox_default_value : '',
                        'keywords' => array('بارگذاری انیمیشن', 'animation loading', 'سبک بارگذاری',  'لود انیمیشن'),
                        'id_dependents_on' => array('data_load_duration', 'data_load_type', 'data_load_delay'),
                        'base_priority' => 'checkbox_base',
                        'selector_priority' => 'checkbox_' . 'data_animation_priority',
                        'z_index' => 0
                    ),
                )
            );
            if (in_array('delay', $data_load_options)) {
                $delay_setting =
                    array(
                        'id'             => 'data_load_delay',
                        'label'            => __(
                            'زمان تاخیر در شروع پویانمایی',
                            self::$static_textdomain
                        ),
                        'description'    => __('با استفاده از این گزینه می توانید مدت زمان تاخیر در شروع پویانمایی را تنظیم نمایید.', self::$static_textdomain),
                        'note'    => __('این عدد هرچقدر کوچک تر باشد انیمیشن زودتر و هرچه بزرگتر باشد دیرتر شروع می گردد.', self::$static_textdomain),
                        'type'            => 'number',
                        'step' => 50,
                        'default'        => $status_default ? $data_load_delay_default_value : '',
                        'min' => 0,
                        'max' => 5000,
                        'placeholder'    => __('', self::$static_textdomain),
                        'keywords' => array('میزان تاخیر بارگذاری', 'animation time', 'time loading', 'زمان انیمیشن', 'تاخیر انیمیشن'),
                    );
                array_push($data_load_settings['fields'], $delay_setting);
            }
            if (in_array('duration', $data_load_options)) {
                $duration_setting =
                    array(
                        'id'             => 'data_load_duration',
                        'label'            => __(
                            'طول زمان انجام پویانمایی',
                            self::$static_textdomain
                        ),
                        'description'    => __('با استفاده از این گزینه می توانید مدت زمان شروع تا پایان انجام پویانمایی(انیمیشن) را تنظیم نمایید.', self::$static_textdomain),
                        'note'    => __('این عدد هرچقدر کوچک تر باشد انیمیشن سریعتر به پایان می رسد و هرچه بزرگتر باشد آرام تر به پایان می رسد.', self::$static_textdomain),
                        'type'            => 'number',
                        'step' => 50,
                        'default'        => $status_default ? $data_load_duration_default_value : '',
                        'min' => 100,
                        'max' => 3000,
                        'placeholder'    => __('', self::$static_textdomain),
                        'keywords' => array('میزان تاخیر بارگذاری', 'animation time', 'time loading', 'زمان انیمیشن', 'طول انیمیشن'),
                    );
                array_push($data_load_settings['fields'], $duration_setting);
            }
            $type_setting
                =                    array(
                    'id'             => 'data_load_type',
                    'label'            => __(
                        'سبک بارگذاری',
                        self::$static_textdomain
                    ),
                    'description'    => __('با استفاده از این گزینه می توانید سبک بارگذاری و انیمیشن محتوای خود را مشخص کنید.', self::$static_textdomain),

                    'type'            => 'radio',
                    'thumbnail_status' => true,
                    'options'        => array('none' => array('عادی', 'none-aos.png'), 'fade-up' => array('از پایین', 'fade-up.gif'), 'fade-down' => array('از بالا', 'fade-down.gif'), 'fade-right' => array('از چپ', 'fade-right.gif'), 'fade-left' => array('از راست', 'fade-left.gif'), 'flip-left' => array('چرخش از چپ', 'flip-left.gif'), 'flip-right' => array('چرخش از راست', 'flip-right.gif'), 'flip-up' => array('چرخش از پایین', 'flip-up.gif'), 'flip-down' => array('چرخش از بالا', 'flip-down.gif'), 'zoom-in' => array('زوم از داخل', 'zoom-in.gif'), 'zoom-out' => array('زوم از خارج', 'zoom-out.gif'), 'zoom-out-right' => array('زوم از چپ', 'zoom-out-right.gif'), 'zoom-out-left' => array('زوم از راست', 'zoom-out-left.gif')),
                    'default'        => $status_default ? $data_load_type_default_value : '',
                    'keywords' => array('سبک بارگذاری', 'انیمیشن', 'animation', 'animation load', 'بارگذاری', 'aos')
                );
            array_push($data_load_settings['fields'], $type_setting);

            return $data_load_settings;
        }


        /**
         * Return custom html css fields
         * @param bool,array
         * @return array
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function html_css_fields_generator($status_default, array $fields)
        {
            $result_fields = array(
                'title' => 'HTML & Css دستی',
                'id' => 'data_load',
                'description'            => __('کد های دورنی', self::$static_textdomain),
                'icon' => 'fa-code',
                'fields' => array()
            );

            if (in_array('element_id', $fields)) {
                $element_id = array(
                    'id'             => 'element_id',
                    'label'            => __(
                        'شناسه (آی دی) عنصر',
                        self::$static_textdomain

                    ),
                    'description'    => __('در صورتی که با ساختار HTML آشنایی دارید می توانید شناسه (آی دی) مورد نظر خود را اختصاص دهید.'),
                    'type'            => 'text',
                    'default'        => $status_default ? '' : '',
                    'placeholder'    => __('برای مثال: header-main', self::$static_textdomain),
                    'keywords' => array('شناسه (آی دی) عنصر, element id'),
                );
                array_push($result_fields['fields'], $element_id);
            }

            if (in_array('css', $fields)) {
                $element_id = array(
                    'id'             => 'css_code',
                    'label'            => __(
                        'سی اس اس اضافی (Extra css)',
                        self::$static_textdomain

                    ),
                    'description'    => __('در صورتی که با زبان استایل دهی Css آشنایی دارید، می توانید استایل های دستی خود را وارد کنید.'),
                    'type'            => 'textarea',
                    'default'        => $status_default ? '' : '',
                    'placeholder'    => __('For example: div.my-class-name{display:block;}', self::$static_textdomain),
                    'keywords' => array('سی اس اس های اضافی', 'extra css'),
                    'direction' => 'ltr',
                );
                array_push($result_fields['fields'], $element_id);
            }

            return $result_fields;
        }


        /**
         * Return custom css code
         * @param array $fields_values
         * @return string Auto slider delay data attribute
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function display_custom_css_code_style($fields_values)
        {
            // Get slider mode checkbox
            $css_code = $fields_values['css_code'];
            if ($css_code) {
?>
                <!-- Style (Scoped) -->
                <style>
                    <?php echo $css_code; ?>
                </style>
                <?php
            }
        }


        /**
         * Return slider fields of swiper js
         * @param mixed $status_default
         * @param array $slider_options
         * @param string $slider_checkbox_default_value
         * @param string $arrow_checkbox_default_value
         * @param string $pagination_checkbox_default_value
         * @param string $autoplay_checkbox_default_value
         * @param int|string $autoplay_delay_default_value
         * @param string $autoplay_progress_checkbox_default_value
         * @return array
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        public static function slider_fields_generator($status_default, $slider_options = array('slider_status', 'arrow', 'pagination', 'autoplay', 'delay', 'progress'), $slider_checkbox_default_value = 'off', $arrow_checkbox_default_value = 'on', $pagination_checkbox_default_value = 'on', $autoplay_checkbox_default_value = 'off', $autoplay_delay_default_value = 5000, $autoplay_progress_checkbox_default_value = 'off', $overflow_checkbox_default_value = 'off', $direction_radio_default_value = 'rtl', $loop_checkbox_default_value = 'off', $effect_radio_default_value = 'normal')
        {
            $slider_settings = array(
                'title' => 'تنظیمات اسلایدر',
                'id' => 'slider',
                'description'            => __('تنظمیات ظاهری و کنترل نحوه عملکرد اسلایدر', self::$static_textdomain),
                'icon' => 'fa-presentation-screen',
                'fields' => array()
            );
            if (in_array('slider_status', $slider_options)) {
                $slider_status =
                    array(
                        'id'             => 'slider_checkbox',
                        'label'            => __(
                            'وضعیت اسلایدر',
                            self::$static_textdomain
                        ),
                        'description'    => __('با استفاده از این گزینه می توانید عناصر را به صورت اسلایدر و یا اسلایدر را فعال نمایید.', self::$static_textdomain),
                        'type'            => 'checkbox',
                        'default'        => $status_default ? $slider_checkbox_default_value : '',
                        'keywords' => array('وضعیت اسلایدر', 'status slider'),
                        'id_dependents_on' => array('slider_arrow_checkbox', 'slider_pagination_checkbox', 'slider_autoplay_checkbox'),
                        'id_dependents_off' => array(),
                        'base_priority' => 'checkbox_base',
                        'selector_priority' => 'checkbox_slider_settings',
                        'z_index' => 0,
                    );
                array_push($slider_settings['fields'], $slider_status);
            }
            if (in_array('arrow', $slider_options)) {
                $arrow_settings =
                    array(
                        'id'             => 'slider_arrow_checkbox',
                        'label'            => __(
                            'نمایش فلش تغییر اسلاید',
                            self::$static_textdomain
                        ),
                        'description'    => __('با استغاده از این گزینه می توانید فلش های تغییر اسلاید که برای رفتن به اسلاید قبل و بعد استفاده می شود را فعال یا غیر فعال نمایید.', self::$static_textdomain),
                        'type'            => 'checkbox',
                        'default'        => $status_default ? $arrow_checkbox_default_value : '',
                        'keywords' => array('slider', 'arrow', 'slide', 'فلش', 'اسلایدر', 'جهت نما'),
                    );
                array_push($slider_settings['fields'], $arrow_settings);
            }
            if (in_array('pagination', $slider_options)) {
                $pagination_setting =
                    array(
                        'id'             => 'slider_pagination_checkbox',
                        'label'            => __(
                            'نمایش آیکون صفحه بندی',
                            self::$static_textdomain
                        ),
                        'description'    => __('با استغاده از این گزینه می توانید وضعیت نمایش آیکون صفحه بندی اسلایدر که به شکل دایره های کوچک به تعداد اسلاید های اسلایدر در پایین اسلایدر نمایش داده می شود را کنترل نمایید.', self::$static_textdomain),
                        'type'            => 'checkbox',
                        'default'        => $status_default ? $pagination_checkbox_default_value : '',
                        'keywords' => array('slider', 'arrow', 'slide', 'فلش', 'اسلایدر', 'جهت نما'),

                    );
                array_push($slider_settings['fields'], $pagination_setting);
            }
            if (in_array('loop', $slider_options)) {
                $loop_setting =
                    array(
                        'id'             => 'slider_loop_checkbox',
                        'label'            => __(
                            'حلقه تکرار اسلایدها (loop)',
                            self::$static_textdomain
                        ),
                        'description'    => __('در صورت فعال بودن این گزینه اسلاید ها بصورت یک حلقه نا متناهی بترتیب تکرار خواهند شد بدینصورت که پس از رسیدن به آخرین اسلاید مجددا اسلاید اول نمایش داده خواهد شد.ولی اگر این گزینه خاموش باشد با رسیدن به آخرین اسلاید اسلایدر متوقف شده و فقط به اسلاید های قبلی حرکت می کند.', self::$static_textdomain),
                        'type'            => 'checkbox',
                        'default'        => $status_default ? $loop_checkbox_default_value : '',
                        'keywords' => array('slider', 'loop', 'slide', 'حلقه', 'اسلایدر', 'تکرار'),

                    );
                array_push($slider_settings['fields'], $loop_setting);
            }
            if (in_array('effect', $slider_options)) {
                $effect_setting =
                    array(
                        'id'             => 'slider_effect_radio',
                        'label'            => __(
                            'سبک تغییر اسلاید',
                            self::$static_textdomain
                        ),
                        'description'    => __('با استغاده از این گزینه می توانید سبک تغییر اسلاید ها(انیمیشن رفتن به اسلاید قبل و بعد) را مشخص نمایید.', self::$static_textdomain),
                        'type'            => 'radio',
                        'options'        => array('normal' => 'عادی', 'coverflow' => 'گردان', 'creative-cover-1' => 'پوششی نوع ۱', 'creative-cover-2' => 'پوششی نوع ۲', 'fade' => 'محو شونده'),
                        'default'        => $status_default ? $effect_radio_default_value : '',
                        'keywords' => array('slider', 'arrow', 'slide', 'فلش', 'اسلایدر', 'جهت نما'),
                    );
                array_push($slider_settings['fields'], $effect_setting);
            }
            if (in_array('autoplay', $slider_options)) {
                $autoplay_setting = array(
                    'id'             => 'slider_autoplay_checkbox',
                    'label'            => __(
                        'پخش خودکار',
                        self::$static_textdomain
                    ),
                    'description'    => __('با استغاده از این گزینه می توانید پخش خودکار اسلایدر ها را فعال یا غیر فعال نمایید.در صورت فعال بودن این گزینه اسلاید ها بصورت خودکار براساس زمان مشخص شده تغییر می کنند.', self::$static_textdomain),
                    'type'            => 'checkbox',
                    'default'        => $status_default ? $autoplay_checkbox_default_value : '',
                    'keywords' => array('slider', 'arrow', 'slide', 'فلش', 'اسلایدر', 'جهت نما'),
                );
                array_push($slider_settings['fields'], $autoplay_setting);
                if (in_array('delay', $slider_options)) {
                    $delay_setting = array(
                        'id'             => 'slider_autoplay_delay_number',
                        'label'            => __(
                            'زمان تاخیر تغییر اسلاید',
                            self::$static_textdomain
                        ),
                        'description'    => __('شما می توانید با تنظیم زمان تاخیر تغییر اسلاید، مدت زمانی که طول می کشد تا اسلاید بصورت خودکار تغییر کند را مشخص نمایید. (واحد ms می باشد)', self::$static_textdomain),
                        'type'            => 'number',
                        'max' => 15000,
                        'min' => 1000,
                        'step' => 100,
                        'default'        => $status_default ? $autoplay_delay_default_value : '',
                        'placeholder'    => __('', self::$static_textdomain),
                        'keywords' => array('slider', 'arrow', 'slide', 'فلش', 'اسلایدر', 'جهت نما'),
                    );
                    array_push($slider_settings['fields'], $delay_setting);
                }
                if (in_array('progress', $slider_options)) {
                    $autoplay_progress_setting = array(
                        'id'             => 'slider_autoplay_progress',
                        'label'            => __(
                            'نمایش پیشرفت زمان تاخیر',
                            self::$static_textdomain
                        ),
                        'description'    => __('با استغاده از این گزینه می توانید زمان باقی  مانده تا تغییر اسلاید را نمایش دهید.', self::$static_textdomain),
                        'type'            => 'checkbox',
                        'default'        => $status_default ? $autoplay_progress_checkbox_default_value : '',
                        'keywords' => array('slider', 'arrow', 'slide', 'فلش', 'اسلایدر', 'جهت نما'),
                    );
                    array_push($slider_settings['fields'], $autoplay_progress_setting);
                }
            }

            if (in_array('overflow', $slider_options)) {
                $overflow_settings =
                    array(
                        'id'             => 'slider_overflow_checkbox',
                        'label'            => __(
                            'بیرون زدگی اسلایدر',
                            self::$static_textdomain
                        ),
                        'description'    => __('با استفاده از این گزینه می توانید اضافه عرض اسلایدر خود را خارج از نگهدارنده های سایت مشاهده کنید.', self::$static_textdomain),
                        'type'            => 'checkbox',
                        'default'        => $status_default ? $overflow_checkbox_default_value : '',
                        'keywords' => array('slider', 'overflow', 'slide', 'فلش', 'اسلایدر', 'جهت نما'),
                    );
                array_push($slider_settings['fields'], $overflow_settings);
            }

            if (in_array('direction', $slider_options)) {
                $direction_settings =
                    array(
                        'id'             => 'slider_direction_radio',
                        'label'            => __(
                            'تنظیم دستی جهت اسلایدر',
                            self::$static_textdomain
                        ),
                        'description'    => __('با استفاده از این گزینه می توانید جهت اسلایدر را از "راست به (فارسی)" و "چپ به راست(انگلیسی)" تنظیم نمایید.', self::$static_textdomain),
                        'type'            => 'radio',
                        'options'        => array('rtl' => 'راست به چپ', 'ltr' => 'چپ به راست'),
                        'default'        => $status_default ? $direction_radio_default_value : '',
                        'keywords' => array('slider', 'set slider direction', 'تنظیم دستی جهت اسلایدر', 'اسلایدر', 'جهت نما'),
                    );
                array_push($slider_settings['fields'], $direction_settings);
            }

            return $slider_settings;
        }


        /**
         * Return heading data load duration number
         * @param array $fields_values
         * @return string
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        public static function get_heading_data_load_duration($fields_values)
        {
            // Get data load checkbox
            $data_load_checkbox = $fields_values['heading_data_load_checkbox'];
            // Check data load checkbox
            if ($data_load_checkbox == 'on') {
                // Get data load time
                $data_load_time = $fields_values['heading_data_load_duration'];
                if ($data_load_time) {
                    return $data_load_time;
                }
            }
        }


        /**
         * Return heading data load delay number
         * @param array $fields_values
         * @return string
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        public static function get_heading_data_load_delay($fields_values)
        {
            // Get data load checkbox
            $data_load_checkbox = $fields_values['heading_data_load_checkbox'];
            // Check data load checkbox
            if ($data_load_checkbox == 'on') {
                // Get data load delay time
                $data_load_time = $fields_values['heading_data_load_delay'];
                if ($data_load_time) {
                    return $data_load_time;
                }
            }
        }


        /**
         * Return heading data load type with format html attribute tag
         * @param array $fields_values
         * @return string
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        public static function get_heading_data_load_attr_type($fields_values)
        {
            // Get data load checkbox
            $data_load_checkbox = $fields_values['heading_data_load_checkbox'];
            // Check data load checkbox
            if ($data_load_checkbox == 'on') {
                // Get data load type
                $data_load_type = $fields_values['heading_data_load_type'];
                $data_load_type_attr = '';
                if ($data_load_type != 'none' && $data_load_type) {
                    $data_load_type_attr = sprintf('data-aos="%s"', $data_load_type);
                    return $data_load_type_attr;
                }
            }
        }
        /**
         * Return data load duration number
         * @param array $fields_values
         * @return string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_data_load_duration($fields_values)
        {
            // Get data load checkbox
            $data_load_checkbox = $fields_values['data_load_checkbox'];
            // Check data load checkbox
            if ($data_load_checkbox == 'on') {
                // Get data load time
                $data_load_time = $fields_values['data_load_duration'];
                if ($data_load_time) {
                    return $data_load_time;
                }
            }
        }


        /**
         * Return data load delay number
         * @param array $fields_values
         * @return string
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        public static function get_data_load_delay($fields_values)
        {
            // Get data load checkbox
            $data_load_checkbox = $fields_values['data_load_checkbox'];
            // Check data load checkbox
            if ($data_load_checkbox == 'on') {
                // Get data load delay time
                $data_load_time = $fields_values['data_load_delay'];
                if ($data_load_time) {
                    return $data_load_time;
                }
            }
        }


        /**
         * Return data load type with format html attribute tag
         * @param array $fields_values
         * @return string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_data_load_attr_type($fields_values)
        {
            // Get data load checkbox
            $data_load_checkbox = $fields_values['data_load_checkbox'];
            // Check data load checkbox
            if ($data_load_checkbox == 'on') {
                // Get data load type
                $data_load_type = $fields_values['data_load_type'];
                $data_load_type_attr = '';
                if ($data_load_type != 'none' && $data_load_type) {
                    $data_load_type_attr = sprintf('data-aos="%s"', $data_load_type);
                    return $data_load_type_attr;
                }
            }
        }


        /**
         * Return slider arrow and pagination
         * @param string $slider_arrow_checkbox
         * @param string $slider_pagination_checkbox
         * @param string $base_color_class
         * @param string $border_radius_style
         * @param string $border_radius_style_arrow
         * @return void
         * @author iranianclever
         * @since v1.0.0
         * @version v1.0.3
         */
        public static function get_slider_arrow_pagination($slider_checkbox, $slider_arrow_checkbox, $slider_pagination_checkbox, $base_color_class, $border_radius_range = 1, $vertical_alignment = 'bottom', $horizontal_alignment = 'center')
        {
            if ($slider_checkbox):
                $position_css = '';
                if ($vertical_alignment == 'top') {
                    $position_css .= 'top:1em;';
                } elseif ($vertical_alignment == 'center') {
                    $position_css .= 'top:50%; transform: translateY(-50%);';
                } elseif ($vertical_alignment == 'bottom') {
                    $position_css .= 'bottom:1em;';
                }
                if ($horizontal_alignment == 'left') {
                    $position_css .= 'left:1em;';
                } elseif ($horizontal_alignment == 'center') {
                    $position_css .= 'right:50%; transform: translateX(50%);';
                } elseif ($horizontal_alignment == 'right') {
                    $position_css .= 'right:1em;';
                }
                // Generate border radius style for slider arrows and pagination
                $border_radius_style = Irantheme_Section_Utils::get_border_radius_css($border_radius_range);
                // Check if slider arrow or pagination is on
                if ($slider_pagination_checkbox == 'on' || $slider_arrow_checkbox == 'on'): ?>
                    <div class="irantheme-swiper-button-slider <?php echo esc_attr($base_color_class); ?>" style="<?php echo esc_attr($border_radius_style); ?><?php echo esc_attr($position_css); ?>">
                        <?php
                        // Check if slider arrow is on
                        if ($slider_arrow_checkbox == 'on') :
                        ?>
                            <div class="swiper-button-prev"><i class="iconsax" icon-name="chevron-right"></i></div>
                        <?php
                        endif;
                        ?>
                        <?php
                        // Check if slider pagination is on
                        if ($slider_pagination_checkbox == 'on') :
                        ?>
                            <div class="swiper-pagination"></div>
                        <?php
                        endif;
                        ?>
                        <?php
                        // Check if slider arrow is on
                        if ($slider_arrow_checkbox == 'on') :
                        ?>
                            <div class="swiper-button-next"><i class="iconsax" icon-name="chevron-left"></i></div>
                        <?php
                        endif;
                        ?>
                    </div>
                <?php
                endif;
            endif;
        }


        /**
         * Return auto slider data attribute
         * @param array $fields_values
         * @return string Auto slider data attribute
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_auto_slider_data_attribute($fields_values, $slider_checkbox = false)
        {
            $slider_mode_checkbox = 'off';
            if ($slider_checkbox) {
                $slider_mode_checkbox = 'on';
            }

            if (isset($fields_values['slider_checkbox'])) {
                // Get slider mode checkbox
                $slider_mode_checkbox = $fields_values['slider_checkbox'];
            }

            // Get slider arrow checkbox
            $auto_slider_checkbox = $fields_values['slider_autoplay_checkbox'];
            // Tag holder
            $attr_holder = 'data-slider-auto=0';
            // Check data load checkbox
            if ($auto_slider_checkbox == 'on' && $slider_mode_checkbox == 'on') {
                $attr_holder = 'data-slider-auto=1';
            }
            return $attr_holder;
        }


        /**
         * Return auto slider delay data attribute
         * @param array $fields_values
         * @return string Auto slider delay data attribute
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_auto_slider_delay_data_attribute($fields_values)
        {
            // Get slider arrow checkbox
            $auto_slider_checkbox = $fields_values['slider_autoplay_checkbox'];
            // Get slider arrow checkbox
            $auto_slider_delay = $fields_values['slider_autoplay_delay_number'];
            // Tag holder
            $attr_holder = '';
            // Check data load checkbox
            if ($auto_slider_checkbox == 'on') {
                $attr_holder = 'data-slider-auto-delay=' . esc_attr($auto_slider_delay);
            }
            return $attr_holder;
        }


        /**
         * Return on or off of overflow
         * @param array $fields_values
         * @return string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_slider_overflow_style($fields_values, $slider_checkbox = false)
        {
            $slider_mode_checkbox = 'off';
            if ($slider_checkbox) {
                $slider_mode_checkbox = 'on';
            } else {
                // Get slider mode checkbox
                $slider_mode_checkbox = $fields_values['slider_checkbox'];
            }
            // Get slider arrow checkbox
            $overflow_checkbox = $fields_values['slider_overflow_checkbox'];
            // Tag holder
            $css_style = '';
            // Check data load checkbox
            if ($slider_mode_checkbox == 'on' && $overflow_checkbox == 'on') {
                $css_style = sprintf('overflow:%s;', 'visible');
            }
            return $css_style;
        }


        /**
         * Return background fields that includes (background-color, background-image, parallax, mask and ...)
         * @param string
         * @return array
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function background_fields_generator($status_default, array $set_fields, $background_image_default_value = '', $background_color_default_value = 'off', bool $background_color_have_transparent_default_value = false, $background_mask_default_value = 'on', $background_mask_color_default_value = 'dark', $background_mask_range_default_value = 0.6, $background_parallax_default_value = 'on', $background_outer_container_default_value = 'off', $background_image_2_default_value = '', $background_image_3_default_value = '', $background_image_4_default_value = '', $background_color2_default_value = 'off', $background_color3_default_value = 'off', $gradiant_default_value = 'off', $gradient_type_default_value = 'linear', $gradient_direction_default_value = 'default', $gradient_first_color_default_value = 'off', $gradient_second_color_default_value = 'off', $gradient_third_color_default_value = 'off')
        {
            $fields = array(
                'title' => 'پس زمینه',
                'id' => 'background',
                'description'            => __('تنظیمات پس زمینه که شامل عکس پس زمینه، رنگ پس زمینه، تصویر زمینه قابل پیمایش (Parallax)، لایه پوشاننده و ... می باشد.', self::$static_textdomain),
                'icon' => 'fa-image',
                'fields' => array()
            );

            // Set background image
            if (in_array('image', $set_fields)) {
                $background_image_field = array(
                    'id'             => 'bg_image',
                    'label'            => __('تصویر پس زمینه', self::$static_textdomain),
                    'description'    => __('لطفا تصویر خود را بارگذاری نمایید.', self::$static_textdomain),
                    'note'            => __('تصویر پس زمینه بر رنگ پس زمینه و گرادینت (در صورت وجود) اولویت دارد.', self::$static_textdomain),
                    'type'            => 'image',
                    'default'        => $background_image_default_value,
                    'placeholder'    => '',
                    'keywords' => array('تصویر پس زمینه', 'تصویر بکگراند', 'background image'),
                );
                // Push background image
                array_push($fields['fields'], $background_image_field);
            }

            // Set background image
            if (in_array('image2', $set_fields)) {
                $background_image_field = array(
                    'id'             => 'bg_image2',
                    'label'            => __('تصویر پس زمینه ۲', self::$static_textdomain),
                    'description'    => __('لطفا تصویر خود را بارگذاری نمایید.', self::$static_textdomain),
                    'type'            => 'image',
                    'default'        => $background_image_2_default_value,
                    'placeholder'    => '',
                    'keywords' => array('تصویر پس زمینه ۲', 'تصویر بکگراند', 'background image'),
                );
                // Push background image
                array_push($fields['fields'], $background_image_field);
            }

            // Set background image
            if (in_array('image3', $set_fields)) {
                $background_image_field = array(
                    'id'             => 'bg_image3',
                    'label'            => __('تصویر پس زمینه ۳', self::$static_textdomain),
                    'description'    => __('لطفا تصویر خود را بارگذاری نمایید.', self::$static_textdomain),
                    'type'            => 'image',
                    'default'        => $background_image_3_default_value,
                    'placeholder'    => '',
                    'keywords' => array('تصویر پس زمینه ۳', 'تصویر بکگراند', 'background image'),
                );
                // Push background image
                array_push($fields['fields'], $background_image_field);
            }

            // Set background image
            if (in_array('image4', $set_fields)) {
                $background_image_field = array(
                    'id'             => 'bg_image4',
                    'label'            => __('تصویر پس زمینه ۴', self::$static_textdomain),
                    'description'    => __('لطفا تصویر خود را بارگذاری نمایید.', self::$static_textdomain),
                    'type'            => 'image',
                    'default'        => $background_image_4_default_value,
                    'placeholder'    => '',
                    'keywords' => array('تصویر پس زمینه ۴', 'تصویر بکگراند', 'background image'),
                );
                // Push background image
                array_push($fields['fields'], $background_image_field);
            }

            // Set gradient background color
            if (in_array('gradient', $set_fields)) {
                $gradient_field = array(
                    'id'             => 'bg_gradient_checkbox',
                    'label'            => __(
                        'گرادینت',
                        self::$static_textdomain
                    ),
                    'description'    => __('در صورت فعال بودن این گزینه می توانید بجای یک رنگ ساده برای پس زمینه از پس زمینه گرادینت (چند رنگ) استفاده کنید.', self::$static_textdomain),
                    'note'    => __('با فعال کردن این گزینه ۳ رنگ در اختیار شما گذاشته می شود که پر کردن رنگ اول و دوم اجباری و رنگ سوم اختیاری است.ضمنا گرادینت بر رنگ پس زمینه تکی اولویت دارد.', self::$static_textdomain),
                    'type'            => 'checkbox',
                    'default'        => $status_default ? $gradiant_default_value : '',
                    'keywords' => array('showcase', 'blog', 'simple', 'hero', 'ویترین', 'هیرو', 'تصویر زمینه'),
                    'id_dependents_on' => array('bg_gradient_first_color', 'bg_gradient_second_color', 'bg_gradient_third_color', 'bg_gradient_direction_radio', 'bg_gradient_type_radio'),
                    'base_priority' => 'checkbox_base',
                    'selector_priority' => 'checkbox_' . 'bg_gradient_priority',
                    'z_index' => 0
                );
                // Push gradient checkbox
                array_push($fields['fields'], $gradient_field);

                $gradient_first_color_field = array(
                    'id'             => 'bg_gradient_first_color',
                    'label'            => __(
                        'رنگ اول',
                        self::$static_textdomain
                    ),
                    'description'    => __('برای انتخاب رنگ پس زمینه از لیست، رنگ مورد نظر خود را انتخاب کنید.در صورت انتخاب حالت پیش فرض، رنگ پس زمینه بر اساس رنگ پایه تنظیم می شود.(تذکر: برای تنظیم هریک از رنگ های اصلی،فرعی،اولیه،ثانویه و اضافی به بخش " تنظیمات قالب -> رنگ های سایت " مراجعه نمایید.) ', self::$static_textdomain),
                    'type'            => 'select',
                    'color_preview' => true,
                    'options'        => Irantheme_Color_Static::get_all_colors_options($background_color_have_transparent_default_value),
                    'default'        => $status_default ? $gradient_first_color_default_value : '',
                    'keywords' => array('رنگ پس زمینه ۳', 'رنگ بک گراند', 'background color', 'color'),
                );
                // Push first color
                array_push($fields['fields'], $gradient_first_color_field);

                $gradient_second_color_field = array(
                    'id'             => 'bg_gradient_second_color',
                    'label'            => __(
                        'رنگ دوم',
                        self::$static_textdomain
                    ),
                    'description'    => __('برای انتخاب رنگ پس زمینه از لیست، رنگ مورد نظر خود را انتخاب کنید.در صورت انتخاب حالت پیش فرض، رنگ پس زمینه بر اساس رنگ پایه تنظیم می شود.(تذکر: برای تنظیم هریک از رنگ های اصلی،فرعی،اولیه،ثانویه و اضافی به بخش " تنظیمات قالب -> رنگ های سایت " مراجعه نمایید.) ', self::$static_textdomain),
                    'type'            => 'select',
                    'color_preview' => true,
                    'options'        => Irantheme_Color_Static::get_all_colors_options($background_color_have_transparent_default_value),
                    'default'        => $status_default ? $gradient_second_color_default_value : '',
                    'keywords' => array('رنگ پس زمینه ۳', 'رنگ بک گراند', 'background color', 'color'),
                );
                // Push second color
                array_push($fields['fields'], $gradient_second_color_field);

                $gradient_third_color_field = array(
                    'id'             => 'bg_gradient_third_color',
                    'label'            => __(
                        'رنگ سوم',
                        self::$static_textdomain
                    ),
                    'description'    => __('برای انتخاب رنگ پس زمینه از لیست، رنگ مورد نظر خود را انتخاب کنید.در صورت انتخاب حالت پیش فرض، رنگ پس زمینه بر اساس رنگ پایه تنظیم می شود.(تذکر: برای تنظیم هریک از رنگ های اصلی،فرعی،اولیه،ثانویه و اضافی به بخش " تنظیمات قالب -> رنگ های سایت " مراجعه نمایید.) ', self::$static_textdomain),
                    'type'            => 'select',
                    'color_preview' => true,
                    'options'        => Irantheme_Color_Static::get_all_colors_options($background_color_have_transparent_default_value),
                    'default'        => $status_default ? $gradient_third_color_default_value : '',
                    'keywords' => array('رنگ پس زمینه ۳', 'رنگ بک گراند', 'background color', 'color'),
                );
                // Push third color
                array_push($fields['fields'], $gradient_third_color_field);

                // Gradient style mode 
                $gradient_type_field = array(
                    'id'             =>
                    'bg_gradient_type_radio',
                    'label'            => __(
                        'سبک گرادینت',
                        self::$static_textdomain
                    ),
                    'description'    => __('با استفاده از این گزینه می توانید نوع گرادینت که به دو نوع خطی و دایره ای تقسیم می گردد را انتخاب کنید.', self::$static_textdomain),
                    'type'            => 'radio',
                    'options'        => array('linear' => '<i class="iconsax" icon-name="sort"></i>', 'radial' => '<i class="iconsax" icon-name="record-circle"></i>'),
                    'default'        => $status_default ? $gradient_type_default_value : '',
                    'keywords' => array('disply', 'mode', 'سبک چینش',  'alignment')
                );
                array_push($fields['fields'], $gradient_type_field);

                // Gradient direction 
                $gradient_direction_field = array(
                    'id'             =>
                    'bg_gradient_direction_radio',
                    'label'            => __(
                        'جهت',
                        self::$static_textdomain
                    ),
                    'description'    => __('با استفاده از این گزینه می توانید زاویه جهت گرادینت را تنظیم نمایید.', self::$static_textdomain),
                    'type'            => 'radio',
                    'options'        => array('default' => '<i class="fa-solid fa-empty-set"></i>', 'dcr' => '<i class="fa-solid fa-arrow-right"></i>', 'dtt' => '<i class="fa-solid fa-arrow-up"></i>', 'dcl' => '<i class="fa-solid fa-arrow-left"></i>', 'dbb' => '<i class="fa-solid fa-arrow-down"></i>', 'dtr' => '<i class="fa-solid fa-arrow-up-right"></i>', 'dtl' => '<i class="fa-solid fa-arrow-up-left"></i>', 'dbr' => '<i class="fa-solid fa-arrow-down-right"></i>', 'dbl' => '<i class="fa-solid fa-arrow-down-left"></i>'),
                    'default'        => $status_default ? $gradient_direction_default_value : '',
                    'keywords' => array('disply', 'mode', 'سبک چینش',  'alignment')
                );
                array_push($fields['fields'], $gradient_direction_field);
            }

            // Set background color
            if (in_array('color', $set_fields)) {
                $background_color_field = array(
                    'id'             => 'bg_color',
                    'label'            => __(
                        'رنگ پس زمینه',
                        self::$static_textdomain
                    ),
                    'description'    => __('برای انتخاب رنگ پس زمینه از لیست، رنگ مورد نظر خود را انتخاب کنید.در صورت انتخاب حالت پیش فرض، رنگ پس زمینه بر اساس رنگ پایه تنظیم می شود.(تذکر: برای تنظیم هریک از رنگ های اصلی،فرعی،اولیه،ثانویه و اضافی به بخش " تنظیمات قالب -> رنگ های سایت " مراجعه نمایید.) ', self::$static_textdomain),
                    'type'            => 'select',
                    'color_preview' => true,
                    'options'        => Irantheme_Color_Static::get_all_colors_options($background_color_have_transparent_default_value),
                    'default'        => $status_default ? $background_color_default_value : '',
                    'keywords' => array('رنگ پس زمینه', 'رنگ بک گراند', 'background color', 'color'),
                );
                // Push background color
                array_push($fields['fields'], $background_color_field);
            }

            // Set background color
            if (in_array('color2', $set_fields)) {
                $background_color_field = array(
                    'id'             => 'bg_color2',
                    'label'            => __(
                        'رنگ پس زمینه ۲',
                        self::$static_textdomain
                    ),
                    'description'    => __('برای انتخاب رنگ پس زمینه از لیست، رنگ مورد نظر خود را انتخاب کنید.در صورت انتخاب حالت پیش فرض، رنگ پس زمینه بر اساس رنگ پایه تنظیم می شود.(تذکر: برای تنظیم هریک از رنگ های اصلی،فرعی،اولیه،ثانویه و اضافی به بخش " تنظیمات قالب -> رنگ های سایت " مراجعه نمایید.) ', self::$static_textdomain),
                    'type'            => 'select',
                    'color_preview' => true,
                    'options'        => Irantheme_Color_Static::get_all_colors_options($background_color_have_transparent_default_value),
                    'default'        => $status_default ? $background_color2_default_value : '',
                    'keywords' => array('رنگ پس زمینه ۲', 'رنگ بک گراند', 'background color', 'color'),
                );
                // Push background color
                array_push($fields['fields'], $background_color_field);
            }

            // Set background color
            if (in_array('color3', $set_fields)) {
                $background_color_field = array(
                    'id'             => 'bg_color3',
                    'label'            => __(
                        'رنگ پس زمینه ۳',
                        self::$static_textdomain
                    ),
                    'description'    => __('برای انتخاب رنگ پس زمینه از لیست، رنگ مورد نظر خود را انتخاب کنید.در صورت انتخاب حالت پیش فرض، رنگ پس زمینه بر اساس رنگ پایه تنظیم می شود.(تذکر: برای تنظیم هریک از رنگ های اصلی،فرعی،اولیه،ثانویه و اضافی به بخش " تنظیمات قالب -> رنگ های سایت " مراجعه نمایید.) ', self::$static_textdomain),
                    'type'            => 'select',
                    'color_preview' => true,
                    'options'        => Irantheme_Color_Static::get_all_colors_options($background_color_have_transparent_default_value),
                    'default'        => $status_default ? $background_color3_default_value : '',
                    'keywords' => array('رنگ پس زمینه ۳', 'رنگ بک گراند', 'background color', 'color'),
                );
                // Push background color
                array_push($fields['fields'], $background_color_field);
            }

            if (in_array('outer_container_color', $set_fields)) {
                $background_outer_container_field = array(
                    'id'             => 'outer_container_color_checkbox',
                    'label'            => __(
                        'اعمال رنگ پس زمینه روی نگهدارنده خارجی',
                        self::$static_textdomain
                    ),
                    'description'    => __('در صورت فعال بودن این گزینه رنگ پس زمینه علاوه بر اعمال روی عناصر داخل نگهدارنده داخلی، روی پس زمینه نگهدارنده خارجی این سکشن(بخش) نیز اعمال می گردد.', self::$static_textdomain),
                    'type'            => 'checkbox',
                    'default'        => $status_default ? $background_outer_container_default_value : '',
                    'keywords' => array('showcase', 'blog', 'simple', 'hero', 'ویترین', 'هیرو', 'تصویر زمینه'),
                );
                // Push outer container checkbox
                array_push($fields['fields'], $background_outer_container_field);
            }

            // Set background mask
            if (in_array('mask', $set_fields)) {
                $background_mask_field = array(
                    'id'             => 'bg_mask_checkbox',
                    'label'            => __(
                        'لایه پوشاننده تصویر',
                        self::$static_textdomain
                    ),
                    'description'    => __('لایه پوشاننده به عنصر شفافی گفته می شود که روی تصویر انتخابی شما را می پوشاند و محتوا شما روی آن قرار می گیرد.کاربرد این لایه زمانی است که تصویر پس زمینه شما دارای ترکیب رنگی خاصی است که متن ها در هر دو رنگ مشکی یا سفید روی آن بخوبی دیده نمی شود،در نتیجه این لایه شفاف باعث می شود که متن شما واضح تر دیده شود.توصیه می شود در صورتی این گزینه را غیر فعال کنید که ترکیب رنگی تصویر پس زمینه شما به شکلی است که متن های محتوا بصورت واضح روی آن دیده می شود.', self::$static_textdomain),
                    'note'    => __('این قابلیت فقط زمانی کاربرد دارد که شما یک تصویر را به عنوان پس زمینه انتخاب کنید.', self::$static_textdomain),
                    'type'            => 'checkbox',
                    'default'        => $status_default ? $background_mask_default_value : '',
                    'keywords' => array('showcase', 'blog', 'simple', 'hero', 'ویترین', 'هیرو', 'تصویر زمینه'),
                    'id_dependents_on' => array('bg_mask_color', 'bg_mask_range'),
                    'base_priority' => 'checkbox_base',
                    'selector_priority' => 'checkbox_' . 'bg_mask_priority',
                    'z_index' => 0
                );
                // Push mask checkbox
                array_push($fields['fields'], $background_mask_field);

                $background_mask_color_field = array(
                    'id'             =>
                    'bg_mask_color',
                    'label'            => __(
                        'رنگ لایه پوشاننده تصویر',
                        self::$static_textdomain
                    ),
                    'description'    => __('جهت تنظیم رنگ لایه پوشاننده تصویر یکی از گزینه های مشکی یا سفید را انتخاب نمایید.', self::$static_textdomain),
                    'color_preview' => true,
                    'type'            => 'select',
                    'options'        => Irantheme_Color_Static::get_all_colors_options(false, false, true),
                    'default'        => $status_default ? $background_mask_color_default_value : '',
                    'keywords' => array('رنگ لایه پوشاننده تصویر', 'background color mask', 'mask color', 'mask'),
                );
                // Push mask color
                array_push($fields['fields'], $background_mask_color_field);

                $background_mask_range_field = array(
                    'id'             =>
                    'bg_mask_range',
                    'label'            => __(
                        'درجه لایه پوشاننده تصویر',
                        self::$static_textdomain
                    ),
                    'description'    => __('درجه لایه پوشاننده تصویر مربوط به میزان شفافیت و کدر بودن است و اثر به سزایی در نمایش تصویر پس زمینه دارد.', self::$static_textdomain),
                    'type'            => 'range',
                    'min' => 0,
                    'max' => 1,
                    'step' => 0.1,
                    'default'        => $status_default ? $background_mask_range_default_value : '',
                    'keywords' => array('درجه لایه پوشاننده تصویر', 'background color mask', 'mask color', 'mask'),
                );
                // Push mask color
                array_push($fields['fields'], $background_mask_range_field);
            }

            // Set background parallax
            if (in_array('parallax', $set_fields)) {
                $background_parallax_field = array(
                    'id'             => 'bg_parallax_checkbox',
                    'label'            => __(
                        'تصویر زمینه قابل پیمایش',
                        self::$static_textdomain
                    ),
                    'description'    => __('در صورت تمایل به تنظیم تصویر زمینه متحرک در زمان اسکرول (Parallax)، این گزینه را فعال نمایید.
								', self::$static_textdomain),
                    'note'    => __('این قابلیت فقط زمانی کاربرد دارد که شما یک تصویر را به عنوان پس زمینه انتخاب کنید.', self::$static_textdomain),
                    'type'            => 'checkbox',
                    'default'        => $status_default ? $background_parallax_default_value : '',
                    'keywords' => array('showcase', 'blog', 'simple', 'hero', 'ویترین', 'هیرو', 'تصویر زمینه'),
                );
                // Push mask color
                array_push($fields['fields'], $background_parallax_field);
            }

            return $fields;
        }


        /**
         * Return transform fields
         * @param string
         * @return array
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function transform_fields_generator($status_default)
        {
            return array(
                'title' => 'تنظیم روی هم رفتگی',
                'id' => 'padding',
                'description'            => __('تنظیمات روی هم رفتگی عناصر از بالا و پایین.', self::$static_textdomain),
                'icon' => 'fa-objects-align-center-horizontal',
                'fields' => array(
                    array(
                        'id'             => 'transform_checkbox',
                        'label'            => __(
                            'روی هم رفتگی عناصر',
                            self::$static_textdomain
                        ),
                        'description'    => __('با فعال کردن این گزینه می توانید به میزان تععین شده در پایین محتوای این بخش را به سمت بالا کشیده و روی بخش قبلی بیندازید.', self::$static_textdomain),
                        'type'            => 'checkbox',
                        'default'        => $status_default ? 'off' : '',
                        'id_dependents_on' => array('transform_top_range', 'transform_bottom_range'),
                        'id_dependents_off' => array(),
                        'base_priority' => 'checkbox_base',
                        'selector_priority' => 'checkbox_post_gallery_transform',
                        'z_index' => 0,
                        'keywords' => array('transform', 'up', 'روی هم رفتگی', 'عناصر'),
                    ),
                    array(
                        'id'             => 'transform_top_range',
                        'label'            => __(
                            'روی هم رفتگی از بالا',
                            self::$static_textdomain
                        ),
                        'description'    => __('با استفاده از این گزینه میتوانید میزان روی هم رفتگی بخش از بالا را مشخص کنید.', self::$static_textdomain),
                        'type'            => 'range',
                        'min' => 0,
                        'max' => 25,
                        'step' => 1,
                        'default'        => $status_default ? 5 : '',
                        'placeholder'    => __('Placeholder text', self::$static_textdomain),
                        'keywords' => array('transform', 'up', 'روی هم رفتگی', 'عناصر'),
                        'note' => __('در صورت استفاده همزمان از "روی هم رفتگی از بالا" و "روی هم رفتگی از پایین" این دو مقدار یکدیگر را خنثی خواهند کرد. پیشنهاد می شود یکی از آنها مقدار صفر داشته باشند.', self::$static_textdomain),
                    ),
                    array(
                        'id'             => 'transform_bottom_range',
                        'label'            => __(
                            'روی هم رفتگی از پایین',
                            self::$static_textdomain
                        ),
                        'description'    => __('با استفاده از این گزینه میتوانید میزان روی هم رفتگی بخش از پایین را مشخص کنید.', self::$static_textdomain),
                        'type'            => 'range',
                        'min' => 0,
                        'max' => 25,
                        'step' => 1,
                        'default'        => $status_default ? 0 : '',
                        'placeholder'    => __('Placeholder text', self::$static_textdomain),
                        'keywords' => array('transform', 'up', 'روی هم رفتگی', 'عناصر'),
                        'note' => __('در صورت استفاده همزمان از "روی هم رفتگی از بالا" و "روی هم رفتگی از پایین" این دو مقدار یکدیگر را خنثی خواهند کرد. پیشنهاد می شود یکی از آنها مقدار صفر داشته باشند.', self::$static_textdomain),
                    ),
                )
            );
        }


        /**
         * Return layout scale fields that includes (padding-top, padding-bottom, wrapper, height of section and ...)
         * @param string
         * @return array
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        public static function layout_scale_fields_generator($status_default, array $set_fields, $custom_inner_padding_default_value = 'off', $custom_inner_padding_top_default_value = 0, $custom_inner_padding_bottom_default_value = 5, $fullscreen_height_default_value = 'on', $custom_outer_padding_default_value = 'off', $custom_outer_padding_top_default_value = 0, $custom_outer_padding_bottom_default_value = 0, $custom_border_radius_default_value = 0.5, $custom_outer_padding_left_default_value = 0, $custom_outer_padding_right_default_value = 0, $custom_inner_padding_left_default_value = 0, $custom_inner_padding_right_default_value = 0)
        {
            $fields = array(
                'title' => 'تنظیم حاشیه و اندازه',
                'id' => 'padding',
                'description'            => __('تنظیمات فاصله ها شامل، فاصله از بالا و پایین، ارتفاع، و...', self::$static_textdomain),
                'icon' => 'fa-distribute-spacing-vertical',
                'fields' => array()
            );

            // Set outer padding
            if (in_array('outer_padding', $set_fields)) {
                $custom_outer_padding = array(
                    'id'             => 'custom_outer_padding_checkbox',
                    'label'            => __(
                        'فاصله خارجی سفارشی',
                        self::$static_textdomain
                    ),
                    'description'    => __('فاصلهٔ خارجی سفارشی به شما امکان می‌دهد فاصلهٔ بین این بخش (سکشن) با بخش قبلی و بعدی را بصورت دلخواه تنظیم کنید.', self::$static_textdomain),
                    'type'            => 'checkbox',
                    'default'        => $status_default ? $custom_outer_padding_default_value : '',
                    'keywords' => array('outer padding', 'پدینگ', 'padding', 'فاصله خارجی سفارشی'),
                    'id_dependents_on' => array('outer_padding_top_range', 'outer_padding_bottom_range', 'outer_padding_left_range', 'outer_padding_right_range'),
                    'id_dependents_off' => array(),
                    'base_priority' => 'checkbox_base',
                    'selector_priority' => 'checkbox_outer_padding_y',
                    'z_index' => 0,
                );
                // Push padding
                array_push($fields['fields'], $custom_outer_padding);
                $custom_outer_padding_top =
                    array(
                        'id'             => 'outer_padding_top_range',
                        'label'            => __(
                            'فاصله خارجی از بالا',
                            self::$static_textdomain
                        ),
                        'description'    => __('با استفاده از این گزینه می توانید فاصله این بخش تا بخش قبلی (فاصله خارجی از بالا) را مشخص نمایید.', self::$static_textdomain),
                        'type'            => 'range',
                        'min' => 0,
                        'max' => 20,
                        'step' => 1,
                        'default'        => $status_default ? $custom_outer_padding_top_default_value : '',
                        'placeholder'    => __('Placeholder text', self::$static_textdomain),
                        'keywords' => array('outer padding', 'پدینگ', 'padding', 'فاصله خارجی سفارشی', 'outer padding top', 'فاصله خارجی از بالا'),
                    );
                // Push padding
                array_push($fields['fields'], $custom_outer_padding_top);
                $custom_outer_padding_bottom =
                    array(
                        'id'             => 'outer_padding_bottom_range',
                        'label'            => __(
                            'فاصله خارجی از پایین',
                            self::$static_textdomain
                        ),
                        'description'    => __('با استفاده از این گزینه می توانید فاصله این بخش تا بخش بعدی (فاصله خارجی از پایین) را مشخص نمایید.', self::$static_textdomain),
                        'type' => 'range',
                        'min' => 0,
                        'max' => 20,
                        'step' => 1,
                        'default'        => $status_default ? $custom_outer_padding_bottom_default_value : '',
                        'placeholder'    => __('Placeholder text', self::$static_textdomain),
                        'keywords' => array('outer padding', 'پدینگ', 'padding', 'فاصله خارجی سفارشی', 'outer padding bottom', 'فاصله خارجی از پایین'),
                    );
                // Push padding
                array_push($fields['fields'], $custom_outer_padding_bottom);
                $custom_outer_padding_left =
                    array(
                        'id'             => 'outer_padding_left_range',
                        'label'            => __(
                            'فاصله خارجی از چپ',
                            self::$static_textdomain
                        ),
                        'description'    => __('با استفاده از این گزینه می توانید فاصله این بخش تا سمت چپ صفحه (فاصله خارجی از چپ) را مشخص نمایید.', self::$static_textdomain),
                        'type' => 'range',
                        'min' => 0,
                        'max' => 20,
                        'step' => 1,
                        'default'        => $status_default ? $custom_outer_padding_left_default_value : '',
                        'placeholder'    => __('Placeholder text', self::$static_textdomain),
                        'keywords' => array('outer padding', 'پدینگ', 'padding', 'فاصله خارجی سفارشی', 'outer padding left', 'فاصله خارجی از چپ'),
                    );
                // Push padding
                array_push($fields['fields'], $custom_outer_padding_left);
                $custom_outer_padding_right =
                    array(
                        'id'             => 'outer_padding_right_range',
                        'label'            => __(
                            'فاصله خارجی از راست',
                            self::$static_textdomain
                        ),
                        'description'    => __('با استفاده از این گزینه می توانید فاصله این بخش تا سمت راست صفحه (فاصله خارجی از راست) را مشخص نمایید.', self::$static_textdomain),
                        'type' => 'range',
                        'min' => 0,
                        'max' => 20,
                        'step' => 1,
                        'default'        => $status_default ? $custom_outer_padding_right_default_value : '',
                        'placeholder'    => __('Placeholder text', self::$static_textdomain),
                        'keywords' => array('outer padding', 'پدینگ', 'padding', 'فاصله خارجی سفارشی', 'outer padding right', 'فاصله خارجی از راست'),
                    );
                // Push padding
                array_push($fields['fields'], $custom_outer_padding_right);
            }

            // Set inner padding
            if (in_array('inner_padding', $set_fields)) {
                $custom_inner_padding = array(
                    'id'             => 'custom_inner_padding_checkbox',
                    'label'            => __(
                        'فاصله داخلی سفارشی',
                        self::$static_textdomain
                    ),
                    'description'    => __('فاصلهٔ داخلی سفارشی به شما امکان می‌دهد فاصلهٔ بین محتوای یک بخش (مثل متن) و لبه‌های آن را بصورت دلخواه تنظیم کنید.', self::$static_textdomain),
                    'type'            => 'checkbox',
                    'default'        => $status_default ? $custom_inner_padding_default_value : '',
                    'keywords' => array('inner padding', 'پدینگ', 'padding', 'فاصله داخلی سفارشی'),
                    'id_dependents_on' => array('inner_padding_top_range', 'inner_padding_bottom_range', 'inner_padding_left_range', 'inner_padding_right_range'),
                    'id_dependents_off' => array('fullscreen_height_checkbox'),
                    'base_priority' => 'checkbox_base',
                    'selector_priority' => 'checkbox_inner_padding_y',
                    'z_index' => 0
                );
                // Push padding
                array_push($fields['fields'], $custom_inner_padding);
                $custom_inner_padding_top =
                    array(
                        'id'             => 'inner_padding_top_range',
                        'label'            => __(
                            'فاصله داخلی از بالا',
                            self::$static_textdomain
                        ),
                        'description'    => __('با استفاده از این گزینه می توانید فاصله بین محتوا تا لبه بالای بخش را مشخص نمایید.', self::$static_textdomain),
                        'type'            => 'range',
                        'min' => 0,
                        'max' => 20,
                        'step' => 1,
                        'default'        => $status_default ? $custom_inner_padding_top_default_value : '',
                        'placeholder'    => __('Placeholder text', self::$static_textdomain),
                        'keywords' => array('inner padding', 'پدینگ', 'padding', 'فاصله داخلی سفارشی', 'inner padding top', 'فاصله داخلی از بالا'),
                    );
                // Push padding
                array_push($fields['fields'], $custom_inner_padding_top);
                $custom_inner_padding_bottom =
                    array(
                        'id'             => 'inner_padding_bottom_range',
                        'label'            => __(
                            'فاصله داخلی از پایین',
                            self::$static_textdomain
                        ),
                        'description'    => __('با استفاده از این گزینه می توانید فاصله بین محتوا تا لبه پایین بخش را مشخص نمایید.', self::$static_textdomain),
                        'type' => 'range',
                        'min' => 0,
                        'max' => 20,
                        'step' => 1,
                        'default'        => $status_default ? $custom_inner_padding_bottom_default_value : '',
                        'placeholder'    => __('Placeholder text', self::$static_textdomain),
                        'keywords' => array('inner padding', 'پدینگ', 'padding', 'فاصله داخلی سفارشی', 'inner padding bottom', 'فاصله داخلی از پایین'),
                    );
                // Push padding
                array_push($fields['fields'], $custom_inner_padding_bottom);
                if (in_array('fullscreen', $set_fields)) {
                    $custom_height = array(
                        'id'             => 'fullscreen_height_checkbox',
                        'label'            => __(
                            'تمام صفحه',
                            self::$static_textdomain
                        ),
                        'description'    => __('با فعال کردن این گزینه، ارتفاع این بخش دقیقاً برابر با ارتفاع صفحه نمایش شما می‌شود. یعنی از بالای صفحه تا پایین آن را پر می‌کند (مناسب برای اسلایدرها، هیرو ها یا بخش‌های تبلیغاتی).', self::$static_textdomain),
                        'type'            => 'checkbox',
                        'default'        => $status_default ? $fullscreen_height_default_value : '',
                        'keywords' => array('hieght', 'ارتفاع', 'fullscreen', 'تمام صفحه', 'هیرو'),
                    );
                    // Push height
                    array_push($fields['fields'], $custom_height);
                }
                $custom_inner_padding_left =
                    array(
                        'id'             => 'inner_padding_left_range',
                        'label'            => __(
                            'فاصله داخلی از چپ',
                            self::$static_textdomain
                        ),
                        'description'    => __('با استفاده از این گزینه می توانید فاصله بین محتوا تا لبه سمت چپ (فاصله داخلی از چپ) را مشخص نمایید.', self::$static_textdomain),
                        'type' => 'range',
                        'min' => 0,
                        'max' => 20,
                        'step' => 1,
                        'default'        => $status_default ? $custom_inner_padding_left_default_value : '',
                        'placeholder'    => __('Placeholder text', self::$static_textdomain),
                        'keywords' => array('inner padding', 'پدینگ', 'padding', 'فاصله داخلی سفارشی', 'inner padding left', 'فاصله داخلی از چپ'),
                    );
                // Push padding
                array_push($fields['fields'], $custom_inner_padding_left);
                $custom_inner_padding_right =
                    array(
                        'id'             => 'inner_padding_right_range',
                        'label'            => __(
                            'فاصله داخلی از راست',
                            self::$static_textdomain
                        ),
                        'description'    => __('با استفاده از این گزینه می توانید فاصله بین محتوا تا لبه سمت راست (فاصله داخلی از راست) را مشخص نمایید.', self::$static_textdomain),
                        'type' => 'range',
                        'min' => 0,
                        'max' => 20,
                        'step' => 1,
                        'default'        => $status_default ? $custom_inner_padding_right_default_value : '',
                        'placeholder'    => __('Placeholder text', self::$static_textdomain),
                        'keywords' => array('inner padding', 'پدینگ', 'padding', 'فاصله داخلی سفارشی', 'inner padding right', 'فاصله داخلی از راست'),
                    );
                // Push padding
                array_push($fields['fields'], $custom_inner_padding_right);
            }

            // Set border radius
            if (in_array('radius', $set_fields)) {
                $custom_radius_range = array(
                    'id'             => 'border_radius_range',
                    'label'            => __(
                        'خمیدگی حاشیه',
                        self::$static_textdomain
                    ),
                    'description'    => __('با اسفاده از این گزینه می توانید خمیدگی حاشیه (Border Radius) را تعیین کنید.(واحد rem می باشد)', self::$static_textdomain),
                    'type'            => 'range',
                    'min' => 0,
                    'max' => 2.5,
                    'step' => 0.1,
                    'default'        => $status_default ? $custom_border_radius_default_value : '',
                    'placeholder'    => __('Placeholder text', self::$static_textdomain),
                    'keywords' => array('خمیدگی حاشیه', 'banner border radius',)
                );
                // Push radius
                array_push($fields['fields'], $custom_radius_range);
            }

            return $fields;
        }


        /**
         * Return background image data and generate style css
         * @param string,string,string,string,string,mixed
         * @return string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_background_image_css($background_image_data, $unique_src = false, $image_size = 'full')
        {
            $style_css = '';
            // Check background image data
            if ($background_image_data) {
                // Get src of background image
                $image_src = wp_get_attachment_image_src($background_image_data, $image_size)[0];
                // Check source mode
                if ($unique_src) {
                    return $image_src;
                }
                $style_css = 'background-image:url("' . $image_src . '");';
                return $style_css;
            } else {
                return false;
            }
        }


        /**
         * Return background color data and generate style css
         * @param string,string,string,string,string,mixed
         * @return string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_background_color_css($background_color_data)
        {
            $style_css = '';
            // Check background color data
            if ($background_color_data) {
                $style_css = Irantheme_Common_Static::get_bg_color_css($background_color_data);
            }
            return $style_css;
        }


        /**
         * Return outer countainer background color data and generate style css
         * @param string,string,string,string,string,mixed
         * @return string
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        public static function get_background_outer_container_color_css($background_color_data, $countainer_background_color_checkbox, $base_color)
        {
            $style_css = '';
            // Check background color data
            if ($background_color_data && $countainer_background_color_checkbox == 'on') {
                if ($background_color_data != 'off') {
                    $style_css = Irantheme_Common_Static::get_bg_color_css($background_color_data);
                } else {
                    if ($base_color == 'dark') {
                        $style_css = 'background-color: #1F2229;';
                    } elseif ($base_color == 'white') {
                        $style_css = 'background-color: #fff;';
                    }
                }
            }
            return $style_css;
        }


        /**
         * Return background parallax data and generate data attribute
         * @param string,string,string,string,string,mixed
         * @return string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_background_parallax_attr($background_parallax_checkbox, $background_image_data)
        {
            // Check background parallax data
            if ($background_parallax_checkbox == 'on' && $background_image_data) {
                // Get src of background image
                $image_src = wp_get_attachment_image_src($background_image_data, 'full')[0];
                $parallax_data = 'data-image-url="' . $image_src . '"';
                return $parallax_data;
            } else {
                return false;
            }
        }


        /**
         * Get attachment url and return attachment id
         * @param string $image_url get image url (url from current site)
         * @return string
         * @author iranianclever
         * @since v1.0.3
         * @see v1.0.3
         */
        public static function get_image_id_from_url($image_url)
        {
            // 1. Validate URL
            if (empty($image_url) || !is_string($image_url)) {
                return false;
            }

            // 2. Cache key
            $cache_key = 'img_id_' . md5($image_url);

            // 3. Check cache first (fastest)
            $cached = wp_cache_get($cache_key, 'attachments');
            if ($cached !== false) {
                return $cached ? (int)$cached : false;
            }

            // 4. First try: WordPress native function
            $attachment_id = attachment_url_to_postid($image_url);

            // 5. Second try: Direct database query (more specific)
            if (!$attachment_id) {
                global $wpdb;

                // Extract filename without query strings
                $parsed_url = parse_url($image_url);
                $path = $parsed_url['path'] ?? '';
                $filename = basename($path);

                // Remove size suffix (e.g., -300x200.jpg)
                $clean_filename = preg_replace('/-\d+x\d+(?=\.[a-z]+$)/i', '', $filename);

                // Query for exact match first (faster)
                $attachment_id = $wpdb->get_var($wpdb->prepare(
                    "SELECT post_id FROM {$wpdb->postmeta} 
            WHERE meta_key = '_wp_attached_file' 
            AND meta_value = %s 
            LIMIT 1",
                    $clean_filename
                ));

                // If not found, try LIKE search
                if (!$attachment_id) {
                    $attachment_id = $wpdb->get_var($wpdb->prepare(
                        "SELECT post_id FROM {$wpdb->postmeta} 
                WHERE meta_key = '_wp_attached_file' 
                AND meta_value LIKE %s 
                LIMIT 1",
                        '%' . $wpdb->esc_like($clean_filename)
                    ));
                }
            }

            // 6. Cache the result
            wp_cache_set($cache_key, $attachment_id ?: 0, 'attachments', 24 * HOUR_IN_SECONDS);

            return $attachment_id ? (int)$attachment_id : false;
        }


        /**
         * Return product content fields
         * @param bool
         * @return array
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_product_content_type_fields($status_default, $product_content_type_radio_value, $product_count_number = 8, $product_types = array('radio_type', 'selection', 'category', 'count_number'))
        {
            $result =  array(
                'title' => 'انتخاب محصولات',
                'id' => 'products',
                'description'            => __('تنظیمات بصری مانند : تصویر زمینه، ارتفاع، رنگ ها و...', self::$static_textdomain),
                'icon' => 'fa-basket-shopping-plus',
                'fields' => array()
            );

            // Radio type push
            if (in_array('radio_type', $product_types)) {
                array_push($result['fields'], array(
                    'id'             => 'product_content_type_radio',
                    'label'            => __(
                        'تنظیم محتوا',
                        self::$static_textdomain
                    ),
                    'description'    => __('نوع و طریقه نمایش محتوا را تنظیم نمایید.', self::$static_textdomain),
                    'type'            => 'radio',
                    'options'        => array('latest' => 'آخرین محصولات', 'sales' => 'پر فروش ترین', 'rating' => 'محبوب ترین', 'features' => 'محصولات ویژه', 'discount' => 'با تخفیف ها', 'category' => 'بر اساس دسته بندی', 'custom' => 'انتخاب دستی'),
                    'default'        => $status_default ? $product_content_type_radio_value : '',
                    'keywords' => array('تنظیم محتوای نوشته', 'set product content'),
                    'id_dependents_on' => array(
                        'latest' => array(),
                        'sales' => array(),
                        'rating' => array(),
                        'features' => array(),
                        'discount' => array(),
                        'category' => array('product_category_selection'),
                        'custom' => array('product_selection'),
                    ),
                    'id_dependents_off' => array(
                        'latest' => array('product_selection', 'product_category_selection'),
                        'sales' => array('product_selection', 'product_category_selection'),
                        'rating' => array('product_selection', 'product_category_selection'),
                        'features' => array('product_selection', 'product_category_selection'),
                        'discount' => array('product_selection', 'product_category_selection'),
                        'category' => array('product_selection'),
                        'custom' => array('product_category_selection'),
                    ),
                    'base_priority' => 'radio_base',
                    'selector_priority' => 'radio_product_content',
                    'z_index' => 0
                ));
            }

            // Selection push
            if (in_array('selection', $product_types)) {
                array_push($result['fields'], array(
                    'id'             => 'product_selection',
                    'label'            => __(
                        'انتخاب دستی محصول',
                        self::$static_textdomain
                    ),
                    'description'    => __('شما می توانید محصولات مورد نظر خود را به صورت دستی انتخاب کنید.', self::$static_textdomain),
                    'type'            => 'select_multi',
                    'options'        => Irantheme_Custom_Fields::get_all_posts('product'),
                    'default' => $status_default ? '' : '',
                    'keywords' => array('انتخاب محصول', 'انتخاب دستی محصول', 'product select post', 'post selection of product', 'select product'),
                    'placeholder' => __('محتوا خالی می باشد', self::$static_textdomain)
                ));
            }

            // Category selection push
            if (in_array('category', $product_types)) {
                array_push($result['fields'], array(
                    'id'             => 'product_category_selection',
                    'label'            => __(
                        'انتخاب دسته بندی محصول',
                        self::$static_textdomain
                    ),
                    'description'    => __('با استفاده از این گزینه شما می توانید هر یک از دسته بندی های مد نظر خود را جهت نمایش در این بخش اضافه کنید.', self::$static_textdomain),
                    'type'            => 'select_multi',
                    'options'        => Irantheme_Custom_Fields::get_all_categories('product_cat'),
                    'default' => $status_default ? array_keys(Irantheme_Custom_Fields::get_all_categories('product_cat'))[0] : '',
                    'keywords' => array('انتخاب دسته بندی محصول', 'دسته بندی محصولات', 'انتخاب دسته بندی محصولات', 'product category selection', 'category select', 'category product'),
                    'placeholder' => __('محتوا خالی می باشد', self::$static_textdomain)
                ));
            }

            // Category selection push
            if (in_array('once_category', $product_types)) {
                array_push($result['fields'], array(
                    'id'             => 'product_once_category_selection',
                    'label'            => __(
                        'انتخاب دسته بندی محصول',
                        self::$static_textdomain
                    ),
                    'description'    => __('با استفاده از این گزینه شما می توانید هر یک از دسته بندی های مد نظر خود را جهت نمایش در این بخش اضافه کنید.', self::$static_textdomain),
                    'type'            => 'select',
                    'options'        => Irantheme_Custom_Fields::get_all_categories('product_cat'),
                    'default' => $status_default ? array_keys(Irantheme_Custom_Fields::get_all_categories('product_cat'))[0] : '',
                    'keywords' => array('انتخاب دسته بندی محصول', 'دسته بندی محصولات', 'انتخاب دسته بندی محصولات', 'product category selection', 'category select', 'category product'),
                    'placeholder' => __('محتوا خالی می باشد', self::$static_textdomain)
                ));
            }

            // Count number push
            if (in_array('count_number', $product_types)) {
                array_push($result['fields'], array(
                    'id'             => 'product_count_number',
                    'label'            => __(
                        'تعداد نمایش محصولات',
                        self::$static_textdomain
                    ),
                    'description'    => __('با استفاده از این گزینه می توانید تعداد نوشته ها در هر صفحه از محصولات را مشخص کنید.', self::$static_textdomain),
                    'type'            => 'number',
                    'step' => 1,
                    'default'        => $status_default ? $product_count_number : '',
                    'min' => 1,
                    'max' => 99,
                    'placeholder'    => __('', self::$static_textdomain),
                    'keywords' => array('تعداد محصولات', 'تعداد محصول', 'اندازه محصولات', 'count products', 'number of products', 'تعداد نوشته ها'),
                ));
            }

            return $result;
        }


        /**
         * Return post content options
         * @param bool,string,string,string,string,array,array,bool
         * @return array
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_post_content_type_fields($status_default, $id, $title, $description, $icon, $extra_array_first = array(), $extra_array_last = array(), $category_filter = false, $post_count_number = 6, $post_excerpt_number = 24, $info_type_checkbox_multi = 'date,category,comment,excerpt')
        {
            $result_array = array(
                'title' => __($title, self::$static_textdomain),
                'id' => $id,
                'description'            => __($description, self::$static_textdomain),
                'icon' => $icon,
                'fields' => array(
                    array(
                        'id'             => 'post_content_type_radio',
                        'label'            => __(
                            'تنظیم محتوا',
                            self::$static_textdomain
                        ),
                        'description'    => __('نوع و طریقه نمایش محتوا را تنظیم نمایید.', self::$static_textdomain),
                        'type'            => 'radio',
                        'options'        => array('latest' => 'آخرین نوشته ها', 'comments' => 'پر بحث ترین', 'like' => 'محبوب ترین', 'category' => 'بر اساس دسته بندی', 'custom' => 'انتخاب دستی'),
                        'default'        => $status_default ? 'latest' : '',
                        'keywords' => array('تنظیم محتوای نوشته', 'set post content'),
                        'id_dependents_on' => array(
                            'custom' => array('post_selection'),
                            'category' => array('post_category_selection')
                        ),
                        'id_dependents_off' => array(
                            'latest' => array('post_selection', 'post_category_selection'),
                            'comments' => array('post_selection', 'post_category_selection'),
                            'like' => array('post_selection', 'post_category_selection'),
                            'category' => array('post_selection'),
                            'custom' => array('post_category_selection')
                        ),
                        'base_priority' => 'radio_base',
                        'selector_priority' => 'radio_post_content',
                        'z_index' => 0
                    ),
                    array(
                        'id'             => 'post_category_selection',
                        'label'            => __(
                            'انتخاب دسته بندی نوشته ها',
                            self::$static_textdomain
                        ),
                        'description'    => __('با استفاده از این گزینه شما می توانید هر یک از دسته بندی های مد نظر خود را جهت نمایش نوشته ها اضافه کنید.', self::$static_textdomain),
                        'type'            => 'select_multi',
                        'options'        => Irantheme_Custom_Fields::get_all_categories('category'),
                        'default' => $status_default ? '' : '',
                        'keywords' => array('انتخاب دسته بندی محصول', 'دسته بندی محصولات', 'انتخاب دسته بندی محصولات', 'product category selection', 'category select', 'category product'),
                        'placeholder' => __('محتوا خالی می باشد', self::$static_textdomain)
                    ),
                    array(
                        'id'             => 'post_selection',
                        'label'            => __(
                            'انتخاب دستی مطالب',
                            self::$static_textdomain
                        ),
                        'description'    => __('با استفاده از این گزینه شما می توانید هر یک از نوشته های مد نظر خود را جهت نمایش در این بخش اضافه کنید. برای ساخت هر یک از نوشته ها به منو "نوشته ها" واقع در محیط ادمین مراجعه کنید.', self::$static_textdomain),
                        'type'            => 'select_multi',
                        'options'        => Irantheme_Custom_Fields::get_all_posts('post'),
                        'default'        => $status_default ? '' : '',
                        'keywords' => array('مطالب سرویس', 'سرویس', 'پست'),
                        'placeholder' => __('محتوا خالی می باشد', self::$static_textdomain)
                    ),
                    array(
                        'id'             => 'post_count_number',
                        'label'            => __(
                            'تعداد نوشته',
                            self::$static_textdomain
                        ),
                        'description'    => __('با استفاده از این گزینه می توانید تعداد نوشته ای نمایش داده شده را مشخص کنید.', self::$static_textdomain),
                        'type'            => 'number',
                        'step' => 1,
                        'default'        => $status_default ? $post_count_number : '',
                        'min' => 1,
                        'max' => 25,
                        'placeholder'    => __('', self::$static_textdomain),
                        'keywords' => array('اندازه گوگل مپ', 'سایز', 'اندازه', 'google map', 'map', 'گوگل مپ'),
                    ),
                    array(
                        'id'             => 'info_type_checkbox_multi',
                        'label'            => __(
                            'جزئیات نوشته ها',
                            self::$static_textdomain
                        ),
                        'description'    => __('با استفاده از این گزینه می توانید نوع جزئیات قابل نمایش برای هر نوشته(پست) را مشخص کنید.مانند تاریخ انتشار، دسته بندی و غیره... ', self::$static_textdomain),
                        'type'            => 'checkbox_multi',
                        'options'        => array('date' => 'تاریخ انتشار',  'category' => 'دسته بندی', 'comment' => 'تعداد دیدگاه ها', 'excerpt' => 'گزیده مطلب', 'read-more' => 'لینک ادامه مطلب', 'author' => 'نویسنده'),
                        'default'        => $status_default ? $info_type_checkbox_multi : '',
                        'keywords' => array('دکمه', 'استایل', 'گوشه', 'منحنی', 'style', 'button', 'circle', 'rectangle', 'edge')
                    ),
                    array(
                        'id'             => 'post_excerpt_number',
                        'label'            => __(
                            'تعداد کلمات گزیده مطلب (چکیده)',
                            self::$static_textdomain
                        ),
                        'description'    => __('با استفاده از این گزینه می توانید تعداد کلمات گزیده مطلب (چکیده) را مشخص کنید.', self::$static_textdomain),
                        'type'            => 'number',
                        'step' => 1,
                        'default'        => $status_default ? $post_excerpt_number : '',
                        'min' => 5,
                        'max' => 75,
                        'placeholder'    => __('', self::$static_textdomain),
                        'keywords' => array('تعداد کلمات گزیده مطلب (چکیده)', 'excerpt words number', 'excerpt count'),
                    ),
                )
            );

            // Check first array extra and append to result array
            // Tip: You need to extra array parent for successfully merge
            if (!empty($extra_array_first)) {
                self::array_insert($result_array['fields'], 0, $extra_array_first);
            }

            // Check filter category to append
            if ($category_filter) {
                array_push($result_array['fields'], array(
                    'id'             => 'categories_trigger_checkbox',
                    'label'            => __(
                        'فیلتر دسته بندی',
                        self::$static_textdomain
                    ),
                    'description'    => __('با استفاده از این گزینه می توانید دسته بندی در بالای پست ها را فعال نمایید.', self::$static_textdomain),
                    'type'            => 'checkbox',
                    'default'        => $status_default ? 'off' : '',
                    'keywords' => array('فیلتر دسته بندی', 'دسته بندی مطلب', 'categories trigger', 'categories', 'post category'),
                ));
            }

            // Check last array extra and append to result array
            // Tip: You need to extra array parent for successfully merge
            if (!empty($extra_array_last)) {
                $result_array['fields'] = array_merge($result_array['fields'], $extra_array_last);
            }
            return $result_array;
        }


        /**
         * Return background parallax data and generate data attribute
         * @param array
         * @return object
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_product_content(array $fields_values, $custom_type = '')
        {
            // Get latest post checkbox
            $product_content_type_radio = $fields_values['product_content_type_radio'] ?? '';

            // Get products number of post --------------------------------------------------------------
            $products_post_number = $fields_values['product_count_number'];

            $products_post = null;

            // Common product type tax query to exclude variations
            $product_type_tax_query = array(
                array(
                    'taxonomy' => 'product_type',
                    'field'    => 'slug',
                    'terms'    => array('simple', 'variable', 'external', 'grouped'),
                    'operator' => 'IN'
                )
            );

            // Get products posts with specific content type
            if ($product_content_type_radio == 'latest' || 'latest' == $custom_type) {

                $extra_args = array(
                    'orderby'             => 'date',
                    'order'               => 'DESC',
                    'tax_query'           => $product_type_tax_query,
                    'ignore_sticky_posts' => 1
                );

                $products_post = Irantheme_Common_Static::get_latest_posts(
                    array('product', 'product_variation'),
                    $products_post_number,
                    $extra_args
                );
            } elseif ($product_content_type_radio == 'sales' || 'sales' == $custom_type) {

                $extra_args = array(
                    'meta_key'            => 'total_sales',
                    'orderby'             => 'meta_value_num',
                    'order'               => 'DESC',
                    'tax_query'           => $product_type_tax_query,
                    'ignore_sticky_posts' => 1
                );

                $products_post = Irantheme_Common_Static::get_latest_posts(
                    array('product', 'product_variation'),
                    $products_post_number,
                    $extra_args
                );
            } elseif ($product_content_type_radio == 'rating' || 'rating' == $custom_type) {

                $extra_args = array(
                    'meta_key'            => '_wc_average_rating',
                    'orderby'             => 'meta_value_num',
                    'order'               => 'DESC',
                    'tax_query'           => $product_type_tax_query,
                    'ignore_sticky_posts' => 1
                );

                $products_post = Irantheme_Common_Static::get_latest_posts(
                    array('product', 'product_variation'),
                    $products_post_number,
                    $extra_args
                );
            } elseif ($product_content_type_radio == 'features' || 'features' == $custom_type) {

                $extra_args = array(
                    'tax_query' => array(
                        'relation' => 'AND',
                        $product_type_tax_query[0],
                        array(
                            'taxonomy' => 'product_visibility',
                            'field'    => 'name',
                            'terms'    => 'featured',
                            'operator' => 'IN',
                        )
                    ),
                    'ignore_sticky_posts' => 1
                );

                $products_post = Irantheme_Common_Static::get_latest_posts(
                    array('product', 'product_variation'),
                    $products_post_number,
                    $extra_args
                );
            } elseif ($product_content_type_radio == 'discount' || 'discount' == $custom_type) {

                // Get ALL product types (simple, variable, grouped, external)
                $all_products = wc_get_products(array(
                    'limit'  => -1,
                    'status' => 'publish',
                    'type'   => array('simple', 'variable', 'grouped', 'external'),
                    'return' => 'ids'
                ));

                $discounted_products = array();

                foreach ($all_products as $product_id) {
                    $product = wc_get_product($product_id);

                    // Check if product exists and is on sale
                    if ($product && $product->is_on_sale()) {
                        $discounted_products[] = $product_id;

                        // Stop if we have enough products
                        if (count($discounted_products) >= $products_post_number) {
                            break;
                        }
                    }
                }

                // Get posts by IDs (with the exact required count)
                $products_post = Irantheme_Common_Static::get_post_by_ids(
                    'product',
                    $discounted_products,
                    $products_post_number
                );
            } elseif ($product_content_type_radio == 'category' || 'category' == $custom_type) {

                // Get products category custom selected
                $custom_category_selected = $fields_values['product_category_selection'];
                $custom_category_explode = array_map('intval', explode(',', $custom_category_selected));

                // First get product IDs from the selected categories in order.
                // Only fetch as many IDs as can possibly be needed from each category.
                $product_ids = array();

                foreach ($custom_category_explode as $category_id) {

                    // Skip invalid category IDs without changing valid results.
                    if ($category_id <= 0) {
                        continue;
                    }

                    $args = array(
                        'post_type'              => array('product', 'product_variation'),
                        'posts_per_page'         => $products_post_number,
                        'fields'                 => 'ids',
                        'no_found_rows'          => true,
                        'update_post_meta_cache' => false,
                        'update_post_term_cache' => false,
                        'ignore_sticky_posts'    => true,
                        'tax_query'              => array(
                            array(
                                'taxonomy' => 'product_cat',
                                'field'    => 'id',
                                'terms'    => $category_id,
                            )
                        )
                    );

                    $category_products = get_posts($args);

                    // Remove duplicates while preserving the original category order.
                    foreach ($category_products as $pid) {
                        $product_ids[$pid] = $pid;
                    }

                    // Once we have enough unique IDs, no later category can affect
                    // the first required results.
                    if (count($product_ids) >= $products_post_number) {
                        break;
                    }
                }

                $product_ids = array_values($product_ids);

                $extra_args = array(
                    'post__in'            => $product_ids,
                    'orderby'             => 'post__in',
                    'posts_per_page'      => $products_post_number,
                    'ignore_sticky_posts' => 1,
                    'tax_query'           => $product_type_tax_query
                );

                $products_post = Irantheme_Common_Static::get_latest_posts(
                    array('product', 'product_variation'),
                    $products_post_number,
                    $extra_args
                );
            } elseif ($product_content_type_radio == 'once_category' || 'once_category' == $custom_type) {

                // Get products category custom selected
                $custom_category_selected = $fields_values['product_once_category_selection'];

                $meta_query = array(
                    array(
                        'taxonomy' => 'product_cat',
                        'field'    => 'id',
                        'terms'    => $custom_category_selected,
                    )
                );

                $extra_args = array(
                    'tax_query' => array(
                        'relation' => 'AND',
                        $product_type_tax_query[0],
                        $meta_query[0]
                    ),
                    'orderby'             => 'date',
                    'order'               => 'DESC',
                    'ignore_sticky_posts' => 1
                );

                $products_post = Irantheme_Common_Static::get_latest_posts(
                    array('product', 'product_variation'),
                    $products_post_number,
                    $extra_args
                );
            } elseif ($product_content_type_radio == 'custom' || 'custom' == $custom_type) {

                // Get products custom selected
                $custom_post_selected = $fields_values['product_selection'];

                // Get custom post selected
                $array_of_post_ids = array_map(
                    'intval',
                    explode(',', $custom_post_selected)
                );

                $products_post = Irantheme_Common_Static::get_post_by_ids(
                    'product',
                    $array_of_post_ids,
                    $products_post_number
                );
            }

            return $products_post;
        }


        /**
         * Return background parallax data and generate data attribute
         * @param array
         * @return object
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_post_content(array $fields_values)
        {
            // Get latest post checkbox
            $post_content_type_radio
                = $fields_values['post_content_type_radio'];
            // Get latest post count
            $post_count = $fields_values['post_count_number'];
            if ($post_content_type_radio == 'latest') {
                $post_content = Irantheme_Common_Static::get_latest_posts('post', $post_count);
            } elseif ($post_content_type_radio == 'comments') {
                $post_content = Irantheme_Common_Static::get_discussed_posts('post', $post_count);
            } elseif ($post_content_type_radio == 'like') {
                $post_content = Irantheme_Common_Static::get_liked_posts('post', $post_count);
            } elseif ($post_content_type_radio == 'category') {
                // Get latest post checkbox
                $post_category_selection = $fields_values['post_category_selection'];
                // Get custom categories selected
                $post_content = Irantheme_Common_Static::get_post_by_ids('post', array(), $post_count, $post_category_selection);
            } elseif ($post_content_type_radio == 'custom') {
                // Get custom post selected
                $custom_post_selected = $fields_values['post_selection'];
                if ($custom_post_selected) {
                    // Get custom post selected
                    $array_of_post_ids = explode(',', $custom_post_selected);
                    $post_content = Irantheme_Common_Static::get_post_by_ids('post', $array_of_post_ids);
                } else {
                    $post_content = Irantheme_Common_Static::get_post_by_ids('post', array(null));
                }
            }
            return $post_content;
        }


        /**
         * Return background mask data and generate html tag
         * @param string,string,string,string,string,mixed
         * @return string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_background_mask_tag_html($mask_checkbox, $mask_color, $mask_range)
        {
            // Check mask checkbox
            if ($mask_checkbox == 'on') {
                if ($mask_color == 'dark') {
                    $attr_style = 'background-color:rgba(43, 47, 57, ' . $mask_range . ')';
                ?>
                    <!-- Mask dark -->
                    <div class="irantheme-mask-dark" style="<?php echo esc_attr($attr_style); ?>"></div>
                <?php
                } elseif ($mask_color == 'white') {
                    $attr_style = 'background-color:rgba(240, 240, 240, ' . $mask_range . ')';
                ?>
                    <!-- Mask white -->
                    <div class="irantheme-mask-white" style="<?php echo esc_attr($attr_style); ?>"></div>
                <?php
                } else {
                    $hex_color = Irantheme_Color_Static::get_hex_color($mask_color);
                    $color_rgb_raw = Irantheme_Color_Static::hex_to_rgb($hex_color);
                    $color_r = $color_rgb_raw[0];
                    $color_g = $color_rgb_raw[1];
                    $color_b = $color_rgb_raw[2];
                    $attr_style = 'background-color:rgba(' . $color_r . ', ' . $color_g . ', ' . $color_b . ', ' . $mask_range . ')';
                ?>
                    <!-- Mask custom -->
                    <div class="irantheme-mask-custom" style="<?php echo esc_attr($attr_style); ?>"></div>
<?php
                }
            }
        }


        /**
         * Get transform values
         * @param array,bool,bool
         * @return array
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_transform_values_arrays($data_page, $fields_values, $overflow = false, $have_background_class = false, $container_inner_status = true)
        {
            // Initialize result array
            $result = array(
                'overflow'   => '',
                'background' => '',
                'css'        => '',
            );

            // Section transform to up --------------------------------------------------------------
            $section_transform_checkbox = isset($fields_values['transform_checkbox']) ? $fields_values['transform_checkbox'] : '';
            $section_transform_top_range = isset($fields_values['transform_top_range']) ? $fields_values['transform_top_range'] : 0;
            $section_transform_bottom_range = isset($fields_values['transform_bottom_range']) ? $fields_values['transform_bottom_range'] : 0;
            // Base color --------------------------------------------------------------
            $base_color = isset($fields_values['base_color_select']) ? $fields_values['base_color_select'] : '';

            if ($section_transform_checkbox == 'on') {
                // Check container inner width
                $container_inner_width = 100;
                if ($container_inner_status) {
                    // Get page meta data json
                    $page_config_fields = Irantheme_Page_Builder_Ajax::get_page_settings($data_page, 'page_config', 'page_config_fields');
                    // Get page is customizable
                    $page_customizable = $page_config_fields['irantheme_page_customizable_checkbox'] ?? 'off';
                    if ($page_customizable == 'on') {
                        // Get containers options ranges --------------------------------------------------------
                        $container_inner_width = isset($page_config_fields['irantheme_inner_container']) ? $page_config_fields['irantheme_inner_container'] : 100;
                    } else {
                        // Get containers options ranges --------------------------------------------------------
                        $container_inner_width = get_option('irantheme_inner_container');
                        if ($container_inner_width === false) {
                            $container_inner_width = 100;
                        }
                    }
                }
                // Overflow status
                if ($overflow) {
                    $result['overflow'] = 'overflow:unset;';
                }
                // Background class status
                if ($have_background_class) {
                    if ($base_color == 'white') {
                        $result['background'] = ' irantheme-bg-white';
                    } elseif ($base_color == 'dark') {
                        $result['background'] = ' irantheme-bg-dark';
                    }
                }
                // Main value transform status
                $result['css'] = 'margin-top: -' . $section_transform_top_range . 'rem;margin-bottom: -' . $section_transform_bottom_range . 'rem;z-index:99999;width:' . $container_inner_width . '%;margin-right:auto;margin-left:auto;';
            }

            return $result;
        }


        /**
         * Border radius generator
         * @param string,mixed,mixed,mixed,mixed
         * @return string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_border_radius_css(
            $option_value,
            bool $top_right = false,
            bool $top_left = false,
            bool $bottom_right = false,
            bool $bottom_left = false,
            bool $half_radius = false
        ): string {
            // Validate input: must be numeric or numeric string
            if (!is_numeric($option_value)) {
                return '';
            }

            $value = (float) $option_value;

            // Apply half radius only when value is greater than 0
            if ($half_radius && $value > 0) {
                $value /= 2;
            }

            // Determine which corners were explicitly requested
            $corners = [
                'border-top-right-radius'    => $top_right,
                'border-top-left-radius'     => $top_left,
                'border-bottom-right-radius' => $bottom_right,
                'border-bottom-left-radius'  => $bottom_left,
            ];

            $requested = array_filter($corners);

            // If no specific corners requested, apply to all
            if (empty($requested)) {
                return sprintf('border-radius:%srem;', $value);
            }

            $css = '';
            foreach ($requested as $property => $_) {
                $css .= sprintf('%s:%srem;', $property, $value);
            }

            return $css;
        }
    }
}
