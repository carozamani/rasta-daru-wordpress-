<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;

if (!class_exists('Irantheme_Section_Service_Master')) {
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
    class Irantheme_Section_Service_Master extends Irantheme_Section
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
            $heading_content = '<span style="font-size: 1em;">ــــــــــــــــــــــــ چه چیزی ارائه می کنیم؟</span>
<h2 style="font-size: 2em;"><span style="font-size: 1.2em;"><strong>خدمات ما به مشتریان</strong></span></h2>';
            $settings = array(
                Irantheme_Section_Utils::heading_title_settings_generator($status_default, 'none', 'off', false, $heading_content, 'off', '', '', '', 'off', 'off', '', '', '', 'off', 'rectangle', 'right', 'off', '', 0, 1),
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
                            $status_default ? Irantheme_Section_Default::get_default_ids_objects(8, 'service_master', $status_default) : '',
                        ),
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
                            'id'             => 'item_box_style_checkbox',
                            'label'            => __('باکس استایل', self::$static_textdomain),
                            'description'    => __('در صورت فعال بودن این گزینه سرویس ها استایل جعبه ای پیدا می کنند.به این معنا که رنگ پس زمینه ای براساس رنگ پایه انتخابی شما روی آن اعمال می گردد.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'off' : '',
                            'keywords' => array('محتوا', 'تنظیم', 'رنگ', 'پس زمینه', 'color', 'service'),
                            'id_dependents_on' => array('item_double_icon_checkbox'),
                            'id_dependents_off' => array(),
                            'base_priority' => 'checkbox_base',
                            'selector_priority' => 'checkbox_service_master_box_style',
                            'z_index' => 0
                        ),
                        array(
                            'id'             => 'item_double_icon_checkbox',
                            'label'            => __(
                                'آیکون خلاقانه',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت فعال بودن این گزینه یک رونوشت از آیکون انتخابی شما برای هر سرویس بصورت کمرنگ و بزرگ تر در پس زمینه هر سرویس نمایش داده می شود.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'off' : '',
                            'keywords' => array('double', 'دوتایی', 'icon', 'چنیش', 'آیکون', 'عناصر'),
                            'selector_priority' => 'checkbox_service_master_box_style',
                            'z_index' => 1
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
                            'id'             => 'padding_y',
                            'label'            => __(
                                'ارتفاع سرویس',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه میتوانید ارتفاع سرویس خود را افزایش دهید.', self::$static_textdomain),
                            'type'            => 'range',
                            'min' => 0,
                            'max' => 10,
                            'step' => 1,
                            'default'        => $status_default ? 0 : '',
                            'placeholder'    => __('Placeholder text', self::$static_textdomain),
                            'keywords' => array('ارتفاع سرویس', 'padding of service', 'service height'),
                        ),
                        array(
                            'id'             => 'bg_color_icon_select',
                            'label'            => __(
                                'رنگ آیکون سرویس',
                                self::$static_textdomain
                            ),
                            'description'    => __('برای انتخاب رنگ آیکون سرویس از لیست، رنگ مورد نظر خود را انتخاب کنید.در صورت انتخاب حالت پیش فرض، رنگ آیکون ها بر اساس رنگ پایه انتخاب شده در بالا تنظیم می شود.(تذکر: برای تنظیم هریک از رنگ های اصلی،فرعی،اولیه،ثانویه و اضافی به بخش " تنظیمات قالب -> رنگ های سایت " مراجعه فرمایید.) ', self::$static_textdomain),
                            'type'            => 'select',
                            'color_preview' => true,
                            'options'        => Irantheme_Color_Static::get_all_colors_options(),
                            'default'        => $status_default ? 'primary' : '',
                            'keywords' => array('color', 'background', 'پس زمینه', 'رنگ'),
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
                            'default'        => $status_default ? 2 : '',
                            'placeholder'    => __('Placeholder text', self::$static_textdomain),
                            'keywords' => array('size', 'icon', 'آیکون', 'اندازه'),
                        ),
                        array(
                            'id'             => 'item_box_icon_checkbox',
                            'label'            => __(
                                'باکس آیکون',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت فعال بودن این گزینه یک باکس دور آیکون سرویس نمایش داده می شود.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'on' : '',
                            'keywords' => array('icon box', 'باکس آیکون', 'icon', 'آیکون'),
                            'id_dependents_on' => array('item_box_icon_style_radio', 'icon_box_icon_radius_range'),
                            'id_dependents_off' => array(),
                            'base_priority' => 'checkbox_base',
                            'selector_priority' => 'checkbox_box_icon',
                            'z_index' => 0
                        ),
                        array(
                            'id'             => 'item_box_icon_style_radio',
                            'label'            => __('نوع باکس آیکون', self::$static_textdomain),
                            'description'    => __('با استفاده از این گزینه می توانید استایل باکس آیکن را انتخاب کنید.این گزینه شامل دو حالت خط دور و باکس رنگی می باشد.', self::$static_textdomain),
                            'note'    => __('خط دور: فقط یک خط دور (border) دور آیکون نمایش داده می شود که رنگ آن با رنگ آیکون یکسان است.</br> باکس رنگی: در این حالت یک باکس رنگی دور آیکون ایجاد می گردد که رنگ پس زمینه آن بر اساس رنگ آیکون بصورت خودکار تنظیم می گردد.', self::$static_textdomain),
                            'type'            => 'radio',
                            'options'        => array('border' => 'خط دور', 'box' => 'باکس رنگی'),
                            'default'        => $status_default ? 'box' : '',
                            'keywords' => array('محتوا', 'تنظیم', 'تراز', 'محور', 'alignment', 'float', 'position')
                        ),
                        array(
                            'id'             => 'icon_box_icon_radius_range',
                            'label'            => __(
                                'خمیدگی حاشیه باکس آیکون',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه میتوانید میزان خمیدگی حاشیه باکس آیکون را مشخص کنید.', self::$static_textdomain),
                            'type'            => 'range',
                            'min' => 0,
                            'max' => 6,
                            'step' => 0.1,
                            'default'        => $status_default ? 1 : '',
                            'placeholder'    => __('Placeholder text', self::$static_textdomain),
                            'keywords' => array('radius', 'icon', 'آیکون', 'خمیدگی'),
                        ),
                        array(
                            'id'             => 'icon_space_range',
                            'label'            => __(
                                'فاصله آیکون با محتوا',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه میتوانید میزان فاصله آیکون تا محتوای سرویس را مشخص کنید.', self::$static_textdomain),
                            'type'            => 'range',
                            'min' => 0.5,
                            'max' => 6,
                            'step' => 0.5,
                            'default'        => $status_default ? 2 : '',
                            'placeholder'    => __('Placeholder text', self::$static_textdomain),
                            'keywords' => array('space', 'icon', 'آیکون', 'فاصله'),
                        ),
                        array(
                            'id'             => 'border_radio',
                            'label'            => __('جداکننده سرویس ها', self::$static_textdomain),
                            'description'    => __('با استفاده از این گزینه می توانید سبک جدا کننده سرویس ها را تعیین کنید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'thumbnail_status' => true,
                            'options'        => array('none' => array('بدون جدا کننده', 'separator-none.png'), 'border-side' => array('جدا کننده بین سرویس ها', 'separator-between-sparator.png'), 'border-box' => array('جدا کننده دور سرویس ها', 'separator-border.png')),
                            'default'        => $status_default ? 'none' : '',
                            'keywords' => array('محتوا', 'تنظیم', 'تراز', 'محور', 'alignment', 'float', 'position')
                        ),
                        array(
                            'id'             => 'icon_side_checkbox',
                            'label'            => __(
                                'چینش افقی آیکون',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید آیکون سرویس و محتوای آن را بصورت افقی در کنار یکدیگر قرار دهید.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'off' : '',
                            'keywords' => array('horizonal', 'افقی', 'icon', 'چنیش', 'آیکون', 'عناصر'),
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
                            'id'             => 'space_checkbox',
                            'label'            => __(
                                'عناصر بهم چسبیده',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت فعال بودن این گزینه، فاصله بین سرویس ها از بین رفته و بشکل بهم چسبیده نمایش داده می شوند.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'off' : '',
                            'keywords' => array('side', 'چسبیده', 'icon', 'عناصر بهم چسبیده', 'سرویس', 'عناصر'),
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
            $content_default = '';

            // Check status is deafult or no
            if (is_numeric($index)) {
                // List of default titles
                $titles = array(
                    'سیستم امنیتی پیشرفته',
                    'استفاده از بهترین سخت افزار ها',
                    'حمل و نقل پیشتاز',
                    'اتصال به جامعه جهانی',
                    'کیفیت بالای قطعات',
                    'بازنویسی نرم افزار قطعات',
                    'استفاده از دوربین حرارتی',
                    'بازنشانی در صورت نیاز',
                );
                $icons = array(
                    'fa-light fa-shield-halved',
                    'fa-light fa-trophy-star',
                    'fa-light fa-truck-fast',
                    'fa-light fa-globe-pointer',
                    'fa-light fa-sunglasses',
                    'fa-light fa-code',
                    'fa-light fa-camera',
                    'fa-light fa-rotate-left',
                );
                // Default content
                $content = 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ.';

                // Set default value
                $titles_default = $titles[$index];
                $icons_default = $icons[$index];
                $content_default = $content;
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
                            'default'        => $content_default,
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
                            'id'             => 'service_btn_style_radio',
                            'label'            => __(
                                'نمایش دکمه',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید استایل دکمه (نوع نمایش دکمه) را تعیین کنید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'options'        => array('off' => 'خاموش', 'text' => 'متن ساده', 'rectangle' => 'دکمه مستطیلی', 'circle' => 'دکمه منحنی'),
                            'default'        => 'text',
                            'keywords' => array('button', 'title', 'btn', 'text', 'دکمه', 'عنوان', 'تیتر'),
                            'id_dependents_on' => array('text' => array('service_btn_title', 'service_btn_link'), 'rectangle' => array('service_btn_title', 'service_btn_link', 'service_btn_color_select'), 'circle' => array('service_btn_title', 'service_btn_link', 'service_btn_color_select')),
                            'id_dependents_off' => array('off' => array('service_btn_title', 'service_btn_link', 'service_btn_color_select'), 'text' => array('service_btn_color_select')),
                        ),
                        array(
                            'id'             => 'service_btn_title',
                            'label'            => __(
                                'عنوان دکمه',
                                self::$static_textdomain

                            ),
                            'description'    => __('عنوان دلخواه خود را اینجا وارد کنید.'),
                            'type'            => 'text',
                            'default'        => __('', self::$static_textdomain),
                            'placeholder'    => __('عنوان', self::$static_textdomain),
                            'keywords' => array('button', 'title', 'btn', 'text', 'دکمه', 'عنوان', 'تیتر'),
                        ),
                        array(
                            'id'             => 'service_btn_link',
                            'label'            => __(
                                'لینک دکمه',
                                self::$static_textdomain

                            ),
                            'description'    => __('لینک دکمه را اینجا وارد کنید.'),
                            'type'            => 'text',
                            'default'        => '',
                            'placeholder'    => __('برای مثال: https://www.mywebsite.com/page', self::$static_textdomain),
                            'keywords' => array('button', 'title', 'btn', 'text', 'دکمه', 'عنوان', 'تیتر'),
                        ),
                        array(
                            'id'             => 'service_btn_color_select',
                            'label'            => __(
                                'رنگ دکمه',
                                self::$static_textdomain
                            ),
                            'description'    => __('برای انتخاب رنگ دکمه از لیست رنگ دلخواه خود را انتخاب کنید.', self::$static_textdomain),
                            'type'            => 'select',
                            'options'        => Irantheme_Color_Static::get_all_colors_options(true),
                            'color_preview' => true,
                            'default'        => 'off',
                            'keywords' => array('button', 'title', 'btn', 'text', 'دکمه', 'عنوان', 'تیتر'),
                        ),
                    )
                ),
                // Get background fields
                Irantheme_Section_Utils::background_fields_generator(true, array('image', 'color'), '', 'off', true),
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

            // Service count selection and number of columns --------------------------------------------------------------
            // Get column post count
            $service_posts_count = $fields_values['col_count_radio'];

            // Base color --------------------------------------------------------------
            $base_color = $fields_values['base_color_select'];
            // Set base color class
            $base_color_class = Irantheme_Common_Static::get_base_color_class($base_color);

            // Service space --------------------------------------------------------------
            $service_space_checkbox = $fields_values['space_checkbox'];
            $service_space_class = '';
            if ($service_space_checkbox == 'on') {
                $service_space_class = 'irantheme-service-no-space';
            }

            // Service item border style --------------------------------------------------------------
            $service_border_style = $fields_values['border_radio'];
            $service_border_style_class = '';
            if ($service_border_style == 'border-side') {
                $service_border_style_class = 'irantheme-service-border-side';
            } else if ($service_border_style == 'border-box') {
                $service_border_style_class = 'irantheme-service-border-box';
            }

            // Section transform to up and down --------------------------------------------------------------
            $section_transfrom_values = Irantheme_Section_Utils::get_transform_values_arrays($data_page, $fields_values, true, true, false);
            $section_transform_extra_css = $section_transfrom_values['overflow'];
            $section_transform_translate_css = $section_transfrom_values['css'];
            $business_transform_bg_color_class = $section_transfrom_values['background'];

            // Get heading data load style --------------------------------------------------------------
            $heading_data_load_checkbox = $fields_values['heading_data_load_checkbox'];
            $heading_data_load_attr = Irantheme_Section_Utils::get_heading_data_load_attr_type($fields_values);
            // Get data load duration
            $heading_data_load_duration = Irantheme_Section_Utils::get_heading_data_load_duration($fields_values);
            // Get aos delay time
            $heading_aos_delay =
                Irantheme_Section_Utils::get_heading_data_load_delay($fields_values);

            // Get section id --------------------------------------------------------------
            $section_id = $fields_values['element_id'];
            $section_id_attribute = '';
            if ($section_id) {
                $section_id_attribute = sprintf('id=%s', $section_id);
            }
?>
            <!-- ============================= Section service master ============================= -->
            <section <?php echo esc_attr($section_id_attribute); ?> class="irantheme-section irantheme-service-master-section" <?php echo esc_attr(Irantheme_Page_Builder::get_attr_data_section($data_type, $data_value, $data_page, $data_ajax)); ?> style="<?php echo esc_attr($section_transform_translate_css) ?><?php echo esc_attr(Irantheme_Section_Service::get_custom_outer_padding_css($fields_values)); ?>">
                <?php
                // Set wrapper (Container) --------------------------------------------------------------
                $container_checkbox = $fields_values['wrapper_mode_checkbox'];
                ?>
                <div class="irantheme-service irantheme-service-master <?php echo esc_attr($base_color_class); ?> <?php echo esc_attr($service_space_class); ?> <?php echo esc_attr($service_border_style_class); ?> irantheme-background-jarallax" style="<?php echo esc_attr($background_color_css); ?> <?php echo esc_attr($background_image_css); ?><?php echo esc_attr(Irantheme_Section_Service::get_custom_inner_padding_css($fields_values)); ?>" <?php echo $background_parallax_attr; ?>>

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
                        <?php endif;
                    $service_heading_transfer_checkbox = $fields_values['heading_transfer_checkbox'];
                    // Service heading --------------------------------------------------------------
                    $service_heading = $fields_values['text_editor_heading'];
                        ?>
                        <?php if ($service_heading && $service_heading_transfer_checkbox != 'on') : ?>
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
                                    <?php if ($service_heading != '' && $heading_data_load_checkbox == 'on'):
                                    ?>
                                    </div>
                                <?php
                                    endif;
                                ?>
                                <?php if ($container_checkbox == 'on') : ?>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                        <div class="irantheme-object-group row irantheme-service-edge justify-content-center" style="<?php echo esc_attr($section_transform_extra_css); ?>" data-json="<?php echo esc_attr('objects_json'); ?>">
                            <?php
                            // Service heading transfer --------------------------------------------------------------
                            ?>
                            <?php if ($service_heading && $service_heading_transfer_checkbox == 'on') : ?>
                                <!-- Heading item -->
                                <div class="<?php echo esc_attr(Irantheme_Section_Service::get_bootstrap_class($service_posts_count, 'col-sm-12')); ?><?php echo esc_attr($business_transform_bg_color_class) ?>">
                                    <div class="irantheme-service-heading-col" style='display:flex;flex-direction:column;'>
                                        <?php
                                        if ($service_heading != '' && $heading_data_load_checkbox == 'on'):
                                        ?>
                                            <div class="irantheme-tinymce-heading-aos-holder" <?php echo ($heading_data_load_attr); ?> data-aos-delay="<?php echo ($heading_aos_delay); ?>" data-aos-duration="<?php echo ($heading_data_load_duration); ?>">
                                            <?php
                                        endif;
                                            ?>
                                            <?php Irantheme_Common_Static::tinymce_heading($fields_values); ?>
                                            <?php if ($service_heading != '' && $heading_data_load_checkbox == 'on'):
                                            ?>
                                            </div>
                                        <?php
                                            endif;
                                        ?>
                                    </div>
                                </div>
                            <?php endif; ?>
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

                            // Service custom fields --------------------------------------------------------------
                            foreach ($json_post_array as $object_post) {
                                // Get title options
                                $service_title = $object_post['fields']['service_title'];
                                // Get description option
                                $service_desc = $object_post['fields']['service_desc'];
                                // Get icon option
                                $service_icon = $object_post['fields']['service_icon'];
                                // Get image option
                                $service_image = $object_post['fields']['bg_image'];
                                // Get background color option
                                $service_bg_color = $object_post['fields']['bg_color'];
                                // Get box style
                                $service_item_box_style_checkbox = $fields_values['item_box_style_checkbox'];
                                // Get double icon style
                                $service_double_icon_checkbox = $fields_values['item_double_icon_checkbox'];

                                // Service base color --------------------------------------------------------------
                                $base_color = $fields_values['base_color_select'];
                                $base_color_class = '';
                                if ($service_bg_color == 'off') {
                                    // Set base color class
                                    $base_color_class = Irantheme_Common_Static::get_base_color_class($base_color);
                                }

                                // Get button options
                                $service_btn_style_radio = $object_post['fields']['service_btn_style_radio'];
                                $service_button_style_class = '';
                                if ($service_btn_style_radio == 'rectangle') {
                                    $service_button_style_class = '';
                                } elseif ($service_btn_style_radio == 'circle') {
                                    $service_button_style_class = 'irantheme-btn-radius';
                                }
                                $service_btn_title = $object_post['fields']['service_btn_title'];
                                $service_btn_link = $object_post['fields']['service_btn_link'];
                                $service_btn_bg_color = $object_post['fields']['service_btn_color_select'];
                                $service_btn_bg_class = Irantheme_Common_Static::get_button_color_class($base_color, $service_btn_bg_color);

                                // Service box shadow --------------------------------------------------------------
                                $service_box_shadow_checkbox = $fields_values['item_box_shadow_checkbox'];
                                $service_box_shadow_class = '';
                                if ($service_box_shadow_checkbox == 'on') {
                                    $service_box_shadow_class = 'irantheme-service-box-shadow';
                                }

                                // Service height --------------------------------------------------------------
                                $height_service = $fields_values['padding_y'];
                                $height_service_css = '';
                                if ($height_service > 0) {
                                    $height_service_css = sprintf('padding-top:%srem;padding-bottom:%srem;', $height_service, $height_service);
                                }
                            ?>
                                <!-- Service item -->
                                <div class="irantheme-object <?php echo esc_attr(Irantheme_Section_Service::get_bootstrap_class($service_posts_count)); ?>" data-object="<?php echo esc_attr($object_post['object_id']); ?>" <?php echo ($data_load_attr); ?> data-aos-delay="<?php echo ($aos_delay); ?>" data-aos-duration="<?php echo ($data_load_duration); ?>">
                                    <div class="irantheme-service-item">
                                        <?php
                                        // Service content alignment --------------------------------------------------------------
                                        $service_content_alignment = $fields_values['alignment_content_radio'];
                                        // Icon side -------------------------------------------------------------
                                        $service_icon_side_checkbox = $fields_values['icon_side_checkbox'];
                                        $service_icon_side_class = '';
                                        if ($service_icon_side_checkbox == 'on') {
                                            $service_icon_side_class = 'irantheme-service-item-icon-side';
                                        }

                                        // Background image --------------------------------------------------------------
                                        $service_css_image = '';
                                        $service_class_image = '';
                                        if ($service_image) {
                                            $service_css_image = 'background-image: url("' . wp_get_attachment_image_src($service_image, 'medium_large')[0] . '");';
                                            $service_class_image = 'irantheme-service-item-image';
                                        }

                                        // Background color --------------------------------------------------------------
                                        $background_color_css = Irantheme_Section_Utils::get_background_color_css($service_bg_color);

                                        // Box style --------------------------------------------------------------
                                        $service_item_box_style_class = '';
                                        if ($service_item_box_style_checkbox == 'on') {
                                            $service_item_box_style_class = 'irantheme-service-item-box-style';
                                        }
                                        ?>
                                        <!-- Wrapper -->
                                        <div class="irantheme-service-item-wrapper <?php echo esc_attr($base_color_class); ?> <?php echo esc_attr(Irantheme_Section_Service::get_alignment_class($service_content_alignment)); ?> <?php echo esc_attr($service_class_image); ?> <?php echo esc_attr($service_item_box_style_class); ?> <?php echo esc_attr($service_box_shadow_class); ?>" style="<?php echo esc_attr($background_color_css); ?> <?php echo esc_attr($service_css_image); ?><?php echo esc_attr($border_radius_style); ?><?php echo $height_service_css; ?>">
                                            <div class="irantheme-service-item-wrapper-content <?php echo esc_attr(Irantheme_Section_Service::get_flex_alignment_class($service_content_alignment)); ?> <?php echo esc_attr($service_icon_side_class); ?>">
                                                <!-- Icon -->
                                                <?php if ($service_icon) : ?>
                                                    <?php
                                                    // Icon color --------------------------------------------------------------
                                                    $service_icon_color = $fields_values['bg_color_icon_select'];
                                                    $service_icon_color_class = '';
                                                    if ($service_icon_color == 'off') {
                                                        $service_icon_color_class = '';
                                                    } else {
                                                        $service_icon_color_class = Irantheme_Color_Static::get_class_color_mode('color', null, Irantheme_Color_Static::get_hex_color($service_icon_color));
                                                    }
                                                    // Icon size --------------------------------------------------------------
                                                    $service_icon_size = $fields_values['icon_size_range'];
                                                    // Box icon style ---------------------------------------------------------
                                                    $service_box_icon_checkbox = $fields_values['item_box_icon_checkbox'];
                                                    $service_box_icon_style = $fields_values['item_box_icon_style_radio'];
                                                    $service_icon_box_width = '';
                                                    if ($service_box_icon_checkbox == 'on') {
                                                        if ($service_icon_side_checkbox == 'on') {
                                                            $service_icon_box_width = 'height:calc(' . esc_attr($service_icon_size) . 'rem + 3em);';
                                                        } else {
                                                            $service_icon_box_width = 'width:calc(' . esc_attr($service_icon_size) . 'rem + 3em);';
                                                        }
                                                    }
                                                    $service_box_icon_border_color_class = '';
                                                    $service_box_icon_bg_color_style = '';
                                                    if ($service_box_icon_checkbox == 'on') {
                                                        $service_box_icon_border_color_class = Irantheme_Color_Static::get_class_color_mode('border', null, Irantheme_Color_Static::get_hex_color($service_icon_color));
                                                        if ($service_box_icon_style == 'box') {
                                                            $service_box_icon_bg_color_style = 'background-color:' . Irantheme_Color_Static::generate_light_background_color(Irantheme_Color_Static::get_hex_color($service_icon_color)) . ';';
                                                        }
                                                    }
                                                    // Space between icon and content --------------------------------------------------------------
                                                    $service_icon_space = $fields_values['icon_space_range'];
                                                    $service_icon_space_style = 'margin-bottom:' . $service_icon_space .  'rem;';
                                                    if ($service_icon_side_checkbox == 'on') {
                                                        if ($service_box_icon_checkbox == 'on') {
                                                            $service_icon_space_style = 'margin-left:' . $service_icon_space . 'rem;';
                                                        } else {
                                                            $service_icon_space_style = 'padding-left:' . $service_icon_space . 'rem;';
                                                        }
                                                    }
                                                    // Get icon border radius --------------------------------------------------------------
                                                    $service_box_icon_radius = $fields_values['icon_box_icon_radius_range'];
                                                    $service_box_icon_radius_style = Irantheme_Section_Utils::get_border_radius_css($service_box_icon_radius);

                                                    $icon_box_class = '';
                                                    if ($service_box_icon_checkbox == 'on') {
                                                        $icon_box_class = 'icon-box-style';
                                                    }
                                                    if ($service_box_icon_style == 'border') {
                                                        $icon_box_class .= ' border-style';
                                                    } elseif ($service_box_icon_style == 'box') {
                                                        $icon_box_class .= ' color-box-style';
                                                    }
                                                    ?>
                                                    <div class="irantheme-service-item-icon <?php echo $icon_box_class; ?> <?php echo esc_attr($service_box_icon_border_color_class); ?>" style="<?php echo esc_attr($service_icon_box_width); ?><?php echo esc_attr($service_icon_space_style); ?><?php echo esc_attr($service_box_icon_radius_style); ?><?php echo esc_attr($service_box_icon_bg_color_style); ?>">
                                                        <i class="<?php echo esc_attr($service_icon); ?> <?php echo esc_attr($service_icon_color_class); ?>" style="font-size:<?php echo esc_attr($service_icon_size); ?>rem;"></i>
                                                    </div>
                                                    <?php
                                                    // Double icon --------------------------------------------------------------
                                                    if ($service_double_icon_checkbox == 'on' && $service_item_box_style_checkbox == 'on') :
                                                    ?>
                                                        <!-- Double icon -->
                                                        <i class="irantheme-service-item-double-icon <?php echo esc_attr($service_icon); ?>"></i>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                                <!-- Content -->
                                                <div class="irantheme-service-item-content">
                                                    <!-- Title -->
                                                    <?php if ($service_title) : ?>
                                                        <h3><?php echo esc_html($service_title); ?></h3>
                                                    <?php endif; ?>
                                                    <!-- Description -->
                                                    <?php if ($service_desc) : ?>
                                                        <p><?php echo esc_html($service_desc); ?></p>
                                                    <?php endif; ?>
                                                    <!-- Button -->
                                                    <?php if ($service_btn_style_radio != 'off' && ($service_btn_title || $service_btn_link)): ?>
                                                        <?php if ($service_btn_style_radio == 'text') : ?>
                                                            <div class="irantheme-service-item-content-button">
                                                                <a href="<?php echo esc_attr($service_btn_link ? $service_btn_link : get_home_url()); ?>" class="irantheme-btn-void" target="_blank"><?php echo esc_html($service_btn_title ? $service_btn_title : 'جزئیات بیشتر'); ?></a>
                                                            </div>
                                                        <?php elseif ($service_btn_style_radio == 'rectangle' || $service_btn_style_radio == 'circle'): ?>
                                                            <div class="irantheme-service-item-content-button irantheme-btn-group">
                                                                <a href="<?php echo esc_url_raw($service_btn_link); ?>" class="irantheme-business-portfolio-item-content-button irantheme-btn <?php echo esc_attr($service_btn_bg_class); ?> <?php echo esc_attr($service_button_style_class); ?>"><?php echo esc_attr($service_btn_title); ?></a>
                                                            </div>
                                                        <?php endif; ?>
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
                                // Increase aos delay for next item
                                $aos_delay += 200;
                            }
                            ?>
                            <?php
                            Irantheme_Page_Builder::get_hidden_require_object_group_buttons($data_page);
                            ?>
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
