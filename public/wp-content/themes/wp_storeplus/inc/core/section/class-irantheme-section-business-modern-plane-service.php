<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;

if (!class_exists('Irantheme_Section_Business_Modern_Plane_Service')) {
    /**
     * A class for create section
     *
     * @package Wordpress
     * @subpackage irantheme
     * @since irantheme - custom fields
     * @author M-MALEKZADEH77
     * @see v1.0
     *
     */
    class Irantheme_Section_Business_Modern_Plane_Service extends Irantheme_Section
    {
        /**
         * Constructor
         * @return void
         * @param void
         * @author M-MALEKZADEH77
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
            $heading_content = '<span style="font-size: 1em;">ــــــــــــ مستر پلن های ما ــــــــــــ</span><h2 style="font-size:2.4em;font-weight:bold;">به دنبال میزبانی حرفه ای و مطمئن هستید؟</h2><p font-size:1em;>لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است.</p>';
            $settings = array(
                Irantheme_Section_Utils::heading_title_settings_generator($status_default, 'none', 'on', 'right', $heading_content, 'off', '', '', '', 'off', 'off', '', '', '', '', 'rectangle', 'right', 'off', '', 0, 1),
                array(
                    'title' => 'پلن ها',
                    'id' => 'plane',
                    'description'            => __('تنظیمات مربوط به محتوای پلن سرویس', self::$static_textdomain),
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
                            $status_default ? Irantheme_Section_Default::get_default_ids_objects(3, 'business_modern_plane_service', $status_default) : '',
                        ),
                        array(
                            'id'             => 'col_count_radio',
                            'label'            => __('تعداد ستون محتوا', self::$static_textdomain),
                            'description'    => __('با استفاده از این گزینه شما می توانید تعداد ستون برای نمایش پلن ها را مشخص نمایید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'thumbnail_status' => true,
                            'options'        => array(
                                'col-3' => array('سه ستون', 'col-3.png'),
                                'col-4' => array('چهار ستون', 'col-4.png'),
                                'col-6' => array('شش ستون', 'col-6.png')
                            ),
                            'default'        => $status_default ? 'col-4' : '',
                            'keywords' => array('business', 'col', 'brands', 'تعداد', 'کسب و کار', 'تعداد در هر ردیف'),
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
                            'id'             => 'post_alignment_radio',
                            'label'            => __('موقعیت محتوا', self::$static_textdomain),
                            'description'    => __('با استفاده از این گزینه می توانید موقعیت قرارگیری محتوای پست ها را تعیین کنید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'thumbnail_status' => true,
                            'options'        => array('right' => array('راست چین', 'text-align-right.png'), 'center' => array('وسط چین', 'text-align-center.png'), 'left' => array('چپ چین', 'text-align-left.png')),
                            'default'        => $status_default ? 'right' : '',
                            'keywords' => array('محتوا', 'تنظیم', 'تراز', 'محور', 'alignment', 'float', 'position')
                        ),
                        array(
                            'id'             => 'details_color_select',
                            'label'            => __(
                                'رنگ جزئیات پلن',
                                self::$static_textdomain
                            ),
                            'description'    => __('برای انتخاب رنگ جزئیات پلن از لیست رنگ دلخواه خود را انتخاب کنید. این رنگ تاثیر مستقیمی در زیبایی جزئيات دارد.', self::$static_textdomain),
                            'type'            => 'select',
                            'options'        => Irantheme_Color_Static::get_all_colors_options(),
                            'color_preview' => true,
                            'default'        => $status_default ? 'primary' : '',
                            'keywords' => array('plane service', 'plane', 'جزئیات پلن', 'کلید', 'رنگ'),
                        ),
                        array(
                            'id'             => 'box_special_mode',
                            'label'            => __('استایل برجسته‌سازی پلن', self::$static_textdomain),
                            'description'    => __('با استفاده از این گزینه می توانید استایل برجسته‌سازی پلن را تعیین کنید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'options'        => array('highlight-box' => 'باکس', 'highlight-badge' => 'نشان'),
                            'default'        => 'highlight-badge',
                        ),
                        array(
                            'id'             => 'item_box_icon_checkbox',
                            'label'            => __(
                                'باکس آیکون',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت فعال بودن این گزینه یک باکس دور آیکون پلن نمایش داده می شود.', self::$static_textdomain),
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
                            'id'             => 'post_border_checkbox',
                            'label'            => __(
                                'خط دور عناصر',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت فعال بودن این گزینه، یک خط (بوردر) دور هر پلن نمایش داده می شود.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'off' : '',
                            'keywords' => array('business', 'plane', 'کسب و کار',  'border'),
                        ),
                    )
                ),
                // Get layout scale fields
                Irantheme_Section_Utils::layout_scale_fields_generator($status_default, array('radius', 'inner_padding', 'outer_padding'), 'on', 2, 2, 'off', 'off', 0, 0, 1),
                // Get background fields
                Irantheme_Section_Utils::background_fields_generator($status_default, array('image', 'color', 'mask', 'parallax'), '', 'off', true, 'off', 'dark', 0.6, 'on'),
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
            $prices_default = '';
            $discounts_default = '';
            $description_default = '';
            $features_default = '';
            $button_text_default = '';
            $button_link_default = '';
            $modes_checkbox_default = '';
            $modes_texts_default = '';
            $box_style_default = '';
            $shadow_style_default = '';

            // Check status is deafult or no
            if (is_numeric($index)) {
                // List of default titles
                $titles = array(
                    'هاست لینوکس',
                    'هاست وردپرس',
                    'هاست ویندوز',
                );

                // List of default prices
                $prices = array(
                    '۱۹۵ هزار تومان',
                    '۲۷۵ هزار تومان',
                    '۱۴۵ هزار تومان',
                );

                // List of default discounts
                $discounts = array(
                    '۱۷۰ هزار تومان',
                    '۲۵۰ هزار تومان',
                    '۱۲۰ هزار تومان',
                );

                // Default description
                $description = __('لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است.');

                // List of features
                $features = array(
                    '<ul>
                            <li>فضا : <strong>۱۲۰</strong>GB</li>
                            <li>ترافیک : <strong>نامحدود</strong></li>
                            <li>سهم پردازنده :‌ <strong>۷۳۰۰</strong> مگاهرتز</li>
                            <li>میزبانی در دیتاسنتر های مطرح کشور</li>
                            <li>نوع میزبانی : <strong>تمام ابری</strong></li>
                        </ul>',
                    '<ul>
                            <li>فضا : <strong>۲۴۰</strong>GB</li>
                            <li>ترافیک : <strong>نامحدود</strong></li>
                            <li>سهم پردازنده :‌ <strong>۸۳۰۰</strong> مگاهرتز</li>
                            <li>میزبانی در دیتاسنتر های مطرح کشور</li>
                            <li>نوع میزبانی : <strong>تمام ابری</strong></li>
                        </ul>',
                    '<ul>
                            <li>فضا : <strong>۳۰</strong>GB</li>
                            <li>ترافیک : <strong>نامحدود</strong></li>
                            <li>سهم پردازنده :‌ <strong>۵۳۰۰</strong> مگاهرتز</li>
                            <li>میزبانی در دیتاسنتر های مطرح کشور</li>
                            <li>نوع میزبانی : <strong>تمام ابری</strong></li>
                        </ul>',
                );

                // Default button
                $button_text = __('ثبت سفارش');
                $button_link = __('#');
                $button_secondary_text = __('جزئیات بیشتر');
                $button_secondary_link = __('#');

                // Default checkbox mode
                $modes_checkbox = array(
                    'off',
                    'on',
                    'off'
                );

                // Default mode text
                $modes_texts = array(
                    '',
                    'طرح طلایی',
                    '',
                );

                // Default box style
                $box_style = 'on';

                // Default shadow style
                $shadow_style = 'on';

                // Default checkbox mode
                $scale_checkbox = array(
                    'off',
                    'on',
                    'off'
                );

                // Set default value
                $titles_default = $titles[$index];
                $prices_default = $prices[$index];
                $discounts_default = $discounts[$index];
                $description_default = $description;
                $features_default = $features[$index];
                $button_text_default = $button_text;
                $button_link_default = $button_link;
                $button_secondary_text_default = $button_secondary_text;
                $button_secondary_link_default = $button_secondary_link;
                $modes_checkbox_default = $modes_checkbox[$index];
                $modes_texts_default = $modes_texts[$index];
                $box_style_default = $box_style;
                $shadow_style_default = $shadow_style;
                $scale_style_default = $scale_checkbox[$index];
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
                            'id'             => 'icon',
                            'label'            => __(
                                'آیکون',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت نیاز به نمایش آیکون خاص در بالای هرپلن می توانید با استفاده از این گزینه آیکون مورد نظر خود را انتخاب کنید.', self::$static_textdomain),
                            'note'    => __('رنگ آیکون بر اساس رنگ جزئیات تنظیم می گردد.', self::$static_textdomain),
                            'type'            => 'icon',
                            'default'        => 'fa-light fa-grid-2',
                            'placeholder'    => __('', self::$static_textdomain),
                        ),
                        array(
                            'id'             => 'tag_title',
                            'label'            => __(
                                'برچسب',
                                self::$static_textdomain

                            ),
                            'description'    => __('در صورت تمایل به نمایش برچسب روی پلن عنوان مورد نظر خود برای نمایش برچسب را وارد نمایید.برای مثال: پرطرفدار'),
                            'type'            => 'text',
                            'default'        => '',
                            'placeholder'    => __('برای مثال: پرطرفدار', self::$static_textdomain),
                        ),
                        array(
                            'id'             => 'title',
                            'label'            => __(
                                'عنوان',
                                self::$static_textdomain

                            ),
                            'description'    => __('عنوان پلن تجاری خود را وارد کنید.'),
                            'type'            => 'text',
                            'default'        => $titles_default,
                            'placeholder'    => __('برای مثال: طرح پایه', self::$static_textdomain),
                        ),
                        array(
                            'id'             => 'desc',
                            'label'            => __(
                                'توضیحات',
                                self::$static_textdomain
                            ),
                            'description'    => __('توضیحات مربوط به پلن را وارد کنید.', self::$static_textdomain),
                            'type'            => 'textarea',
                            'default'        => $description_default,
                            'placeholder'    => __('توضیحات را وارد کنید', self::$static_textdomain),
                        ),
                        array(
                            'id'             => 'price',
                            'label'            => __(
                                'قیمت اصلی پلن',
                                self::$static_textdomain

                            ),
                            'description'    => __('قیمت پلن تجاری خود را وارد کنید.'),
                            'type'            => 'text',
                            'default'        => $prices_default,
                            'placeholder'    => __('مثال: ۱۲۰ هزار تومان', self::$static_textdomain),
                        ),
                        array(
                            'id'             => 'price_discount',
                            'label'            => __(
                                'قیمت پلن با احتساب تخفیف',
                                self::$static_textdomain

                            ),
                            'description'    => __('در صورت تمایل می توانید قیمت تخفیف خورده را وارد کنید.'),
                            'type'            => 'text',
                            'default'        => $discounts_default,
                            'placeholder'    => __('مثال: ۱۴۵ هزار تومان', self::$static_textdomain),
                        ),
                        array(
                            'id'             => 'features_list',
                            'label'            => __('لیست ویژگی ها', self::$static_textdomain),
                            'description'    => __('عناوین یا لیستی از ویژگی های سرویس را عنوان کنید. برای مثال: عدم محدودیت ماهانه در دانلود, دسترسی به تمامی امکانات سایت, امکان اشتراک گذاری فایل و ...'),
                            'note'    => __('در صورت تمایل به نمایش به صورت لیستی، موارد را به صورت لیست نشانه دار یا لیست عددی بنویسید.'),
                            'type'            => 'text_editor',
                            'default'        => $features_default,
                            'placeholder'    => __('', self::$static_textdomain),
                        ),
                        array(
                            'id'             => 'btn_checkbox',
                            'label'            => __(
                                'نمایش دکمه',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت نیاز به دکمه این گزینه را فعال کنید.
								', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => 'on',
                            'keywords' => array('button', 'title', 'btn', 'text', 'دکمه', 'عنوان', 'تیتر'),
                            'id_dependents_on' => array('btn_primary_title', 'btn_primary_link', 'btn_primary_color_select', 'btn_primary_style_radio', 'btn_secondary_checkbox'),
                            'selector_priority' => 'checkbox_buttons',
                            'base_priority' => 'checkbox_base',
                            'z_index' => 0
                        ),
                        array(
                            'id'             => 'btn_primary_title',
                            'label'            => __(
                                'عنوان دکمه اول',
                                self::$static_textdomain

                            ),
                            'description'    => __('عنوان دلخواه خود را اینجا وارد کنید.'),
                            'type'            => 'text',
                            'default'        => $button_text_default,
                            'placeholder'    => __('مثال: ثبت سفارش', self::$static_textdomain),
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
                            'default'        => $button_link_default,
                            'placeholder'    => __('برای مثال: https://www.mywebsite.com/page', self::$static_textdomain),
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
                            'default'        => 'primary',
                            'keywords' => array('button', 'title', 'btn', 'text', 'دکمه', 'عنوان', 'تیتر'),
                        ),
                        array(
                            'id'             => 'btn_primary_style_radio',
                            'label'            => __(
                                'استایل دکمه',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید استایل دکمه (نوع نمایش دکمه) را تعیین کنید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'options'        => array('rectangle' => 'مستطیلی', 'circle' => 'منحنی'),
                            'default'        => 'circle',
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
                            'default'        => 'on',
                            'keywords' => array('button', 'title', 'btn', 'text', 'دکمه', 'عنوان', 'تیتر'),
                            'id_dependents_on' => array('btn_secondary_title', 'btn_secondary_link', 'btn_secondary_color_select', 'btn_secondary_style_radio'),
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
                            'default'        => $button_secondary_text_default,
                            'placeholder'    => __('مثال: ثبت سفارش', self::$static_textdomain),
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
                            'default'        => $button_secondary_link_default,
                            'placeholder'    => __('برای مثال: https://www.mywebsite.com/page', self::$static_textdomain),
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
                            'default'        => 'transparent',
                            'keywords' => array('button', 'title', 'btn', 'text', 'دکمه', 'عنوان', 'تیتر'),
                        ),
                        array(
                            'id'             => 'btn_secondary_style_radio',
                            'label'            => __(
                                'استایل دکمه',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید استایل دکمه (نوع نمایش دکمه) را تعیین کنید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'options'        => array('rectangle' => 'مستطیلی', 'circle' => 'منحنی'),
                            'default'        => 'circle',
                            'keywords' => array('button', 'title', 'btn', 'text', 'دکمه', 'عنوان', 'تیتر'),
                        ),
                        array(
                            'id'             => 'box_special_checkbox',
                            'label'            => __(
                                'فعال کردن برجسته‌سازی پلن',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت فعال شدن این گزینه این پلن نسبت به پلن های دیگر در اولویت تجاری قرار می گیرد و تمایز بیشتری نسبت به دیگر پلن ها دارد.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $modes_checkbox_default,
                            'id_dependents_on' => array('box_special_icon', 'box_special_text', 'box_color_select'),
                            'id_dependents_off' => array(),
                            'base_priority' => 'checkbox_base',
                            'selector_priority' => 'checkbox_box_special',
                            'z_index' => 0
                        ),
                        array(
                            'id'             => 'box_special_icon',
                            'label'            => __(
                                'آیکون',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت نیاز به نمایش آیکون خاص در بالای هرپلن می توانید با استفاده از این گزینه آیکون مورد نظر خود را انتخاب کنید.', self::$static_textdomain),
                            'note'    => __('رنگ آیکون بر اساس رنگ جزئیات تنظیم می گردد.', self::$static_textdomain),
                            'type'            => 'icon',
                            'default'        => 'fa-light fa-crown',
                            'placeholder'    => __('', self::$static_textdomain),
                        ),
                        array(
                            'id'             => 'box_special_text',
                            'label'            => __(
                                'متن کادر طرح',
                                self::$static_textdomain

                            ),
                            'description'    => __('متن طرح خود را وارد کنید.'),
                            'type'            => 'text',
                            'default'        => $modes_texts_default,
                            'placeholder'    => __('مثال: طرح ویژه', self::$static_textdomain),
                        ),
                        array(
                            'id'             => 'box_special_color_select',
                            'label'            => __(
                                'رنگ طرح',
                                self::$static_textdomain
                            ),
                            'description'    => __('برای انتخاب رنگ جزئیات پلن از لیست رنگ دلخواه خود را انتخاب کنید. این رنگ تاثیر مستقیمی در زیبایی جزئيات دارد.', self::$static_textdomain),
                            'type'            => 'select',
                            'options'        => Irantheme_Color_Static::get_all_colors_options(),
                            'color_preview' => true,
                            'default'        => 'off',
                            'keywords' => array('plane service', 'plane', 'جزئیات پلن', 'کلید', 'رنگ'),
                        ),
                        array(
                            'id'             => 'box_style',
                            'label'            => __(
                                'باکس استایل',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت فعال بودن این گزینه پلن ها استایل جعبه ای پیدا می کنند.به این معنا که رنگ پس زمینه ای براساس رنگ پایه انتخابی شما روی آن اعمال می گردد.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $box_style_default,
                        ),
                        array(
                            'id'             => 'box_shadow',
                            'label'            => __(
                                'استایل سایه',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت فعال شدن این گزینه می توانید برای باکس پلن ها یک سایه ایجاد کنید.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $shadow_style_default,
                        ),
                        array(
                            'id'             => 'box_scale',
                            'label'            => __(
                                'استایل بزرگنمایی',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت فعال شدن این گزینه اندازه این پلن نسبت به دیگر پلن ها بزرگتر نمایش داده می شود.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $scale_style_default,
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
            $background_parallax_checkbox = $fields_values['bg_parallax_checkbox'];
            $background_color_css = Irantheme_Section_Utils::get_background_color_css($background_color_data);
            $background_image_css = Irantheme_Section_Utils::get_background_image_css($background_image_data);
            $background_parallax_attr = Irantheme_Section_Utils::get_background_parallax_attr($background_parallax_checkbox, $background_image_data);

            // Get data load style --------------------------------------------------------------
            $data_load_attr = Irantheme_Section_Utils::get_data_load_attr_type($fields_values);
            // Get data load duration
            $data_load_duration = Irantheme_Section_Utils::get_data_load_duration($fields_values);
            // Get aos delay time
            $aos_delay
                = Irantheme_Section_Utils::get_data_load_delay($fields_values);

            // Get heading data load style --------------------------------------------------------------
            $heading_data_load_checkbox = $fields_values['heading_data_load_checkbox'];
            $heading_data_load_attr = Irantheme_Section_Utils::get_heading_data_load_attr_type($fields_values);
            // Get data load duration
            $heading_data_load_duration = Irantheme_Section_Utils::get_heading_data_load_duration($fields_values);
            // Get aos delay time
            $heading_aos_delay =
                Irantheme_Section_Utils::get_heading_data_load_delay($fields_values);

            // Get section heading --------------------------------------------------------------
            $tinymce_heading = $fields_values['text_editor_heading'];

            // Base color --------------------------------------------------------------
            $base_color = $fields_values['base_color_select'];
            // Set base color class
            $base_color_class = Irantheme_Common_Static::get_base_color_class($base_color);

            // Get columns count --------------------------------------------------------------
            $count_columns = $fields_values['col_count_radio'];
            $bootstrap_column_class = Irantheme_Section_Service::get_bootstrap_class($count_columns, 12);

            // Set item alignment --------------------------------------------------------------
            $business_item_alignment = $fields_values['post_alignment_radio'];

            // Details color --------------------------------------------------------------
            $service_details_color = $fields_values['details_color_select'];
            $service_details_color_class = '';
            $service_details_light_bg_color_style = '';
            if ($service_details_color == 'off') {
                $service_details_color_class = '';
            } else {
                $service_details_light_bg_color_style = 'background-color:' . Irantheme_Color_Static::generate_light_background_color(Irantheme_Color_Static::get_hex_color($service_details_color)) . ' !important;';
                $service_details_color_class = Irantheme_Color_Static::get_class_color_mode('color', null, Irantheme_Color_Static::get_hex_color($service_details_color));
            }

            // Transform heading to columns --------------------------------------------------------------
            $transform_heading_checkbox = $fields_values['heading_transfer_checkbox'];

            // Display mode --------------------------------------------------------------
            $display_mode_radio = $fields_values['display_mode'];
            $display_mode_class = '';
            if ($display_mode_radio == 'right') {
                $display_mode_class = 'flex-row';
            } elseif ($display_mode_radio == 'left') {
                $display_mode_class = 'flex-row-reverse';
            }

            // Get border radius --------------------------------------------------------------
            $border_radius_range = $fields_values['border_radius_range'];
            $border_radius_style = Irantheme_Section_Utils::get_border_radius_css($border_radius_range);

            // Get border --------------------------------------------------------------
            $plane_border_checkbox = $fields_values['post_border_checkbox'];
            $plane_border_class = '';
            if ($plane_border_checkbox == 'on') {
                $plane_border_class = 'plane-box-border';
            }

            // Get section id --------------------------------------------------------------
            $section_id = $fields_values['element_id'];
            $section_id_attribute = '';
            if ($section_id) {
                $section_id_attribute = sprintf('id=%s', $section_id);
            }

            // Get special plane mode
            $plane_box_special_mode = $fields_values['box_special_mode'];
?>
            <!-- ============================= Section business plane service ============================= -->
            <section <?php echo esc_attr($section_id_attribute); ?> class="irantheme-section irantheme-business-modern-plane-service-section" <?php echo esc_attr(Irantheme_Page_Builder::get_attr_data_section($data_type, $data_value, $data_page, $data_ajax)); ?> style="<?php echo esc_attr(Irantheme_Section_Service::get_custom_outer_padding_css($fields_values)); ?>">
                <div class="irantheme-business irantheme-business-modern-plane-service <?php echo esc_attr($base_color_class); ?> irantheme-background-jarallax" style="<?php echo esc_attr(Irantheme_Section_Service::get_custom_inner_padding_css($fields_values)); ?><?php echo esc_attr($background_color_css); ?><?php echo esc_attr($background_image_css); ?>" <?php echo $background_parallax_attr; ?>>

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
                        <?php
                        if ($transform_heading_checkbox == 'off') :
                            if ($tinymce_heading != '' && $heading_data_load_checkbox == 'on'):
                        ?>
                                <div class="irantheme-tinymce-heading-aos-holder" <?php echo ($heading_data_load_attr); ?> data-aos-delay="<?php echo ($heading_aos_delay); ?>" data-aos-duration="<?php echo ($heading_data_load_duration); ?>">
                                <?php
                            endif;
                            // Get tinymce content --------------------------------------------------------------
                            Irantheme_Common_Static::tinymce_heading($fields_values);
                            if ($tinymce_heading != '' && $heading_data_load_checkbox == 'on'):
                                ?>
                                </div>
                        <?php
                            endif;
                        endif;
                        ?>

                        <?php
                        $plane_box_special_flag = false;
                        foreach ($json_post_array as $object_post) {
                            // Get plane special box for add extra margin to wrapper of item
                            $_plane_box_special = $object_post['fields']['box_special_checkbox'];
                            if ($_plane_box_special == 'on') {
                                $plane_box_special_flag = true;
                            }
                        }
                        ?>
                        <!-- Plane service -->
                        <div class="irantheme-business-modern-plane-service-post-wrapper <?php echo $plane_box_special_flag ? 'special-mode special-mode-style-' . $plane_box_special_mode : ''; ?>">
                            <div class="irantheme-object-group irantheme-business-modern-plane-service-post-row row justify-content-center <?php echo esc_attr($transform_heading_checkbox == 'on' ? $display_mode_class : ''); ?>" data-json="<?php echo esc_attr('objects_json'); ?>">
                                <?php
                                // Check transform heading to columns
                                if ($transform_heading_checkbox == 'on') :
                                ?>
                                    <div class="<?php echo esc_attr($bootstrap_column_class); ?> d-flex flex-column justify-content-center">
                                        <?php
                                        if ($tinymce_heading != '' && $heading_data_load_checkbox == 'on'):
                                        ?>
                                            <div class="irantheme-tinymce-heading-aos-holder" <?php echo ($heading_data_load_attr); ?> data-aos-delay="<?php echo ($heading_aos_delay); ?>" data-aos-duration="<?php echo ($heading_data_load_duration); ?>">
                                            <?php
                                        endif;
                                            ?>
                                            <?php
                                            // Get tinymce content --------------------------------------------------------------
                                            Irantheme_Common_Static::tinymce_heading($fields_values, 'width:100%;');
                                            if ($tinymce_heading != '' && $heading_data_load_checkbox == 'on'):
                                            ?>
                                            </div>
                                        <?php
                                            endif;
                                        ?>
                                    </div>
                                <?php
                                endif;

                                // Box icon style ---------------------------------------------------------
                                $plane_box_icon_checkbox = $fields_values['item_box_icon_checkbox'];
                                $plane_box_icon_style = $fields_values['item_box_icon_style_radio'];
                                $plane_box_icon_border_color_class = '';
                                $plane_box_icon_bg_color_style = '';
                                if ($plane_box_icon_checkbox == 'on') {
                                    $plane_box_icon_border_color_class = Irantheme_Color_Static::get_class_color_mode('border', null, Irantheme_Color_Static::get_hex_color($service_details_color));
                                    if ($plane_box_icon_style == 'box') {
                                        $plane_box_icon_bg_color_style = $service_details_light_bg_color_style;
                                    }
                                }
                                $icon_box_class = '';
                                if ($plane_box_icon_checkbox == 'on') {
                                    $icon_box_class = 'icon-box-style';
                                    if ($plane_box_icon_style == 'border') {
                                        $icon_box_class .= ' border-style';
                                    } elseif ($plane_box_icon_style == 'box') {
                                        $icon_box_class .= ' color-box-style';
                                    }
                                }
                                // Get icon border radius --------------------------------------------------------------
                                $plane_box_icon_radius = $fields_values['icon_box_icon_radius_range'];
                                $plane_box_icon_radius_style = Irantheme_Section_Utils::get_border_radius_css($plane_box_icon_radius);

                                // Loop form all services names
                                foreach ($json_post_array as $object_post) :
                                    // Get plane badge
                                    $plane_badge = $object_post['fields']['tag_title'];
                                    // Get plane icon
                                    $plane_icon = $object_post['fields']['icon'];
                                    // Get plane title
                                    $plane_title = $object_post['fields']['title'];
                                    // Get plane price
                                    $plane_price = $object_post['fields']['price'];
                                    // Get plane discount price
                                    $plane_discount = $object_post['fields']['price_discount'];
                                    // Get plane description
                                    $plane_desc = $object_post['fields']['desc'];
                                    // Get plane list of features
                                    $plane_features_list = $object_post['fields']['features_list'];
                                    // Get plane box style
                                    $plane_box_style = $object_post['fields']['box_style'];
                                    // Get plane box shadow
                                    $plane_box_shadow = $object_post['fields']['box_shadow'];
                                    // Get plane box scale
                                    $plane_box_scale = $object_post['fields']['box_scale'];
                                    // Get plane special box
                                    $plane_box_special = $object_post['fields']['box_special_checkbox'];
                                    // Get plane special box mode
                                    $plane_box_special_icon = $object_post['fields']['box_special_icon'];
                                    // Get plane special box text
                                    $plane_box_special_text = $object_post['fields']['box_special_text'];

                                    // Generate post card btns
                                    $plane_btn_checkbox = $object_post['fields']['btn_checkbox'];
                                    $plane_first_btn_title = $object_post['fields']['btn_primary_title'];
                                    $plane_first_btn_link = $object_post['fields']['btn_primary_link'];
                                    $plane_first_btn_bg_color = $object_post['fields']['btn_primary_color_select'];
                                    $button_primary_bg_class = Irantheme_Common_Static::get_button_color_class($base_color, $plane_first_btn_bg_color);
                                    // Get button style and create class for him
                                    $plane_first_button_style = $object_post['fields']['btn_primary_style_radio'];
                                    $plane_first_button_style_class = '';
                                    if ($plane_first_button_style == 'rectangle') {
                                        $plane_first_button_style_class = '';
                                    } elseif ($plane_first_button_style == 'circle') {
                                        $plane_first_button_style_class = 'irantheme-btn-radius';
                                    }


                                    $plane_second_btn_checkbox = $object_post['fields']['btn_secondary_checkbox'];
                                    $plane_second_btn_title = $object_post['fields']['btn_secondary_title'];
                                    $plane_second_btn_link = $object_post['fields']['btn_secondary_link'];
                                    $plane_second_btn_bg_color = $object_post['fields']['btn_secondary_color_select'];
                                    $button_second_bg_class = Irantheme_Common_Static::get_button_color_class($base_color, $plane_second_btn_bg_color);
                                    // Get button style and create class for him
                                    $plane_second_button_style = $object_post['fields']['btn_secondary_style_radio'];
                                    $plane_second_button_style_class = '';
                                    if ($plane_second_button_style == 'rectangle') {
                                        $plane_second_button_style_class = '';
                                    } elseif ($plane_second_button_style == 'circle') {
                                        $plane_second_button_style_class = 'irantheme-btn-radius';
                                    }

                                    // Classes of box style
                                    $plane_box_style_class = '';
                                    if ($plane_box_style == 'on') {
                                        $plane_box_style_class = 'irantheme-business-modern-plane-service-post-item-box-style';
                                    }

                                    // Classes of box shadow
                                    $plane_box_shadow_class = '';
                                    if ($plane_box_shadow == 'on') {
                                        $plane_box_shadow_class = 'irantheme-business-modern-plane-service-post-item-box-shadow';
                                    }

                                    // Classes of box scale
                                    $plane_box_scale_class = '';
                                    if ($plane_box_scale == 'on') {
                                        $plane_box_scale_class = 'irantheme-business-modern-plane-service-post-item-box-scale';
                                    }

                                    // Get special mode color ------------------------------------------------------------
                                    $box_special_color = $object_post['fields']['box_special_color_select'];
                                    $box_special_color_class = '';
                                    $box_special_bg_color_style = '';
                                    $box_special_border_color_class = '';
                                    if ($box_special_color != 'off') {
                                        $box_special_color_class = Irantheme_Color_Static::get_class_color_mode('color', null, Irantheme_Color_Static::get_hex_color($box_special_color));
                                        $box_special_bg_color_style = 'background-color:' . Irantheme_Color_Static::generate_light_background_color(Irantheme_Color_Static::get_hex_color($box_special_color)) . ';';
                                        if ($plane_box_special_mode == 'highlight-box') {
                                            $box_special_border_color_class = Irantheme_Color_Static::get_class_color_mode('border', null, Irantheme_Color_Static::get_hex_color($box_special_color));
                                        }
                                    }
                                ?>
                                    <div class="irantheme-object align-items-stretch <?php echo esc_attr($bootstrap_column_class); ?> <?php echo esc_attr($plane_box_scale_class); ?> <?php echo esc_attr($plane_box_special == 'on' ? $plane_box_special_mode : ''); ?>" <?php echo ($data_load_attr); ?> data-aos-delay="<?php echo ($aos_delay); ?>" data-aos-duration="<?php echo ($data_load_duration); ?>" data-object="<?php echo esc_attr($object_post['object_id']); ?>">
                                        <!-- Post item -->
                                        <?php if ($plane_box_special == 'on'): ?>
                                            <div class="irantheme-business-modern-plane-service-post-item-special-mode <?php echo esc_attr($plane_box_special_mode); ?> <?php echo esc_attr($box_special_border_color_class); ?>" style="<?php echo esc_attr($box_special_bg_color_style); ?><?php echo esc_attr($border_radius_style); ?>">
                                                <div class="irantheme-business-modern-plane-service-post-item-special-mode-content">
                                                    <i class="<?php echo esc_attr($plane_box_special_icon); ?> <?php echo esc_attr($box_special_color_class); ?>"></i>
                                                    <span class="<?php echo esc_attr($box_special_color_class); ?>"><?php echo esc_html($plane_box_special_text); ?></span>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <div class="irantheme-business-modern-plane-service-post-item <?php echo esc_attr($plane_border_class); ?> <?php echo esc_attr(Irantheme_Section_Service::get_alignment_class($business_item_alignment)); ?> <?php echo esc_attr($plane_box_style_class); ?> <?php echo esc_attr($plane_box_shadow_class); ?>" style="<?php echo esc_attr($border_radius_style); ?>">
                                            <?php if ($plane_icon || $plane_badge) : ?>
                                                <!-- Plane title -->
                                                <div class="irantheme-business-modern-plane-service-post-item-topbar">
                                                    <?php if ($plane_icon): ?>
                                                        <div class="irantheme-business-modern-plane-service-post-item-topbar-icon <?php echo $icon_box_class; ?> <?php echo esc_attr($plane_box_icon_border_color_class); ?>" style="<?php echo esc_attr($plane_box_icon_radius_style); ?><?php echo esc_attr($plane_box_icon_bg_color_style); ?>">
                                                            <i class="<?php _e($plane_icon); ?> <?php echo esc_attr($service_details_color_class); ?>"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php if ($plane_badge): ?>
                                                        <div class="irantheme-business-modern-plane-service-post-item-topbar-badge">
                                                            <span class="<?php echo esc_attr($service_details_color_class); ?>" style="<?php echo esc_attr($service_details_light_bg_color_style); ?>"><?php echo esc_attr($plane_badge); ?></span>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                            <?php if ($plane_title || $plane_desc) : ?>
                                                <div class="irantheme-business-modern-plane-service-post-item-content">
                                                    <?php if ($plane_title) : ?>
                                                        <!-- Plane title -->
                                                        <h3 class="irantheme-business-modern-plane-service-post-item-content-title"><?php echo esc_html($plane_title); ?></h3>
                                                    <?php endif; ?>
                                                    <?php if ($plane_desc) : ?>
                                                        <!-- Plane description -->
                                                        <p class="irantheme-business-modern-plane-service-post-item-content-desc"><?php echo esc_html($plane_desc); ?></p>
                                                    <?php endif; ?>

                                                </div>
                                            <?php endif; ?>
                                            <?php if ($plane_price || $plane_discount) : ?>
                                                <?php
                                                $price_alignment_css = '';
                                                if ($business_item_alignment == 'right') {
                                                    $price_alignment_css = 'align-items:flex-start';
                                                } elseif ($business_item_alignment == 'left') {
                                                    $price_alignment_css = 'align-items:flex-end';
                                                } elseif ($business_item_alignment == 'center') {
                                                    $price_alignment_css = 'align-items:center';
                                                }
                                                ?>
                                                <!-- Plane price -->
                                                <div class="irantheme-business-modern-plane-service-post-item-price" style="<?php echo esc_attr($price_alignment_css); ?>">
                                                    <?php if ($plane_discount) : ?>
                                                        <span class="irantheme-business-modern-plane-service-post-item-price-main"><?php _e($plane_discount); ?></span>
                                                    <?php endif; ?>
                                                    <?php if ($plane_price) : ?>
                                                        <span class="irantheme-business-modern-plane-service-post-item-price-discount"><?php _e($plane_price); ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                            <?php if ($plane_features_list) : ?>
                                                <style>
                                                    #irantheme-business-modern-plane-service-post-item-features-list-<?php echo esc_attr($data_ajax) . ' ' ?>ul li::before {
                                                        <?php echo esc_attr($service_details_light_bg_color_style); ?>color: #<?php echo esc_attr(Irantheme_Color_Static::get_hex_color($service_details_color)); ?> !important;
                                                    }

                                                    #irantheme-business-modern-plane-service-post-item-features-list-<?php echo esc_attr($data_ajax) . ' ' ?>ol li::before {
                                                        color: #<?php echo esc_attr(Irantheme_Color_Static::get_hex_color($service_details_color)); ?> !important;
                                                    }
                                                </style>
                                                <!-- Plane features list -->
                                                <div id="irantheme-business-modern-plane-service-post-item-features-list-<?php echo esc_attr($data_ajax) ?>" class="irantheme-business-modern-plane-service-post-item-features-list">
                                                    <?php
                                                    // Get tinymce simple text
                                                    Irantheme_Common_Static::get_snippet_tinymce_simple($plane_features_list, $base_color_class, '', Irantheme_Section_Service::get_alignment_class($business_item_alignment));
                                                    ?>
                                                </div>
                                            <?php endif; ?>
                                            <?php if ($plane_btn_checkbox == "on"): ?>
                                                <div class="irantheme-business-modern-plane-service-post-item-button">
                                                    <?php if ($plane_first_btn_title): ?>
                                                        <a href="<?php echo esc_url_raw($plane_first_btn_link); ?>" class="irantheme-business-portfolio-item-hero-button irantheme-btn irantheme-btn-medium <?php echo esc_attr($button_primary_bg_class); ?> <?php echo esc_attr($plane_first_button_style_class); ?>" target="_blank"><?php echo esc_attr($plane_first_btn_title); ?></a>
                                                    <?php endif; ?>
                                                    <?php if ($plane_second_btn_checkbox == 'on' && $plane_second_btn_title): ?>
                                                        <a href="<?php echo esc_url_raw($plane_second_btn_link); ?>" class="irantheme-business-portfolio-item-hero-button irantheme-btn irantheme-btn-medium <?php echo esc_attr($button_second_bg_class); ?> <?php echo esc_attr($plane_second_button_style_class); ?>" target="_blank"><?php echo esc_attr($plane_second_btn_title); ?></a>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <?php
                                        Irantheme_Page_Builder::get_hidden_require_object_buttons($data_page);
                                        ?>
                                    </div>
                                <?php
                                    // Increase aos delay for next item
                                    $aos_delay += 200;
                                endforeach;
                                ?>
                                <?php
                                Irantheme_Page_Builder::get_hidden_require_object_group_buttons($data_page);
                                ?>
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
