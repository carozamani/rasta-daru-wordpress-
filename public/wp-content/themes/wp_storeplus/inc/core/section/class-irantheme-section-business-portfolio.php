<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;

if (!class_exists('Irantheme_Section_Business_Portfolio')) {
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
    class Irantheme_Section_Business_Portfolio extends Irantheme_Section
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
         * Fields of section
         * @return void
         * @param void
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        public static function fields($status_default)
        {
            $heading_content = '';
            $settings = array(
                Irantheme_Section_Utils::heading_title_settings_generator($status_default, 'none', false, false, $heading_content),
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
                            $status_default ? Irantheme_Section_Default::get_default_ids_objects(8, 'business_portfolio', $status_default) : '',
                        ),
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
                            'id'             => 'media_col_count_radio',
                            'label'            => __('تعداد ستون ها', self::$static_textdomain),
                            'description'    => __('با استفاده از این گزینه شما می توانید تعداد ستون قابل نمایش برای گالری را مشخص کنید', self::$static_textdomain),
                            'note'    => __('در سبک هم تراز تعداد ستون ها بصورت خودکار تنظیم خواهد شد.', self::$static_textdomain),
                            'type'            => 'radio',
                            'thumbnail_status' => true,
                            'options'        => array(
                                'col-1' => array('تک ستون', 'col-1.png'),
                                'col-2' => array('دو ستون', 'col-2.png'),
                                'col-3' => array('سه ستون', 'col-3.png'),
                                'col-4' => array('چهار ستون', 'col-4.png'),
                                'col-6' => array('شش ستون', 'col-6.png')
                            ),
                            'default'        => $status_default ? 'col-4' : '',
                            'keywords' => array('gallery', 'col', 'author', 'تعداد', 'سرویس', 'تعداد در هر ردیف'),
                        ),
                        array(
                            'id'             => 'gallery_type_style_radio',
                            'label'            => __(
                                'سبک نمایش گالری',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید سبک نمایش گالری را مشخص نمایید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'thumbnail_status' => true,
                            'options'        => array(
                                'grid' => array('کاشی', 'grid.png'),
                                'masonry' => array('آجری', 'masonry.png'),
                                'carousel' => array('اسلایدر', 'carousel.png'),
                            ),
                            'default'        => $status_default ? 'grid' : '',
                            'keywords' => array('دکمه', 'استایل', 'گوشه', 'منحنی', 'style', 'button', 'circle', 'rectangle', 'edge'),
                            'id_dependents_on' => array(
                                'carousel' => array('slider_arrow_checkbox,slider_pagination_checkbox'),
                            ),
                            'id_dependents_off' => array(
                                'grid' => array('slider_arrow_checkbox,slider_pagination_checkbox'),
                                'masonry' => array('slider_arrow_checkbox,slider_pagination_checkbox'),
                                'metro-rtl' => array('slider_arrow_checkbox,slider_pagination_checkbox'),
                                'metro-ltr' => array('slider_arrow_checkbox,slider_pagination_checkbox'),
                                'justify' => array('slider_arrow_checkbox,slider_pagination_checkbox'),
                            ),
                            'base_priority' => 'radio_base',
                            'selector_priority' => 'radio_gallery_style',
                            'z_index' => 0
                        ),
                        array(
                            'id'             => 'cell_gap_range',
                            'label'            => __(
                                'فاصله بین سلول ها',
                                self::$static_textdomain
                            ),
                            'description'    => __('با اسفاده از این گزینه می توانید فاصله بین سلول ها(رسانه ها) گالری را تعیین کنید.(واحد px می باشد)', self::$static_textdomain),
                            'type'            => 'range',
                            'min' => 0,
                            'max' => 50,
                            'step' => 1,
                            'default'        => $status_default ? 30 : '',
                            'placeholder'    => __('Placeholder text', self::$static_textdomain),
                            'keywords' => array('communication', 'contact', 'ارتباط با ما',  'ارتباطات', 'اندازه', 'ارتفاع')
                        ),
                        array(
                            'id'             => 'wrapper_mode_radio',
                            'label'            => __(
                                'نوع نگهدارنده پست',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید نوع نگهدارنده کارت پست ها را مشخص نمایید.', self::$static_textdomain),
                            'note'    => __('با استفاده از این گزینه شما قادر خواهید بود بین دو حالت فاصله دور پست و فاصله دور محتوا متنی پست یکی را انتخاب کنید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'options'        => array(
                                'card-wrapper' => 'نگهدارنده پست',
                                'content-wrapper' => 'نگهدارنده محتوا پست',
                            ),
                            'default'        => $status_default ? 'content-wrapper' : '',
                            'keywords' => array('دکمه', 'استایل', 'گوشه', 'منحنی', 'style', 'button', 'circle', 'rectangle', 'edge'),
                            'id_dependents_on' => array(
                                'card-wrapper' => array('post_wrapper_size_range,apply_radius_to_post_image_checkbox'),
                                'content-wrapper' => array('post_content_wrapper_size_range'),
                            ),
                            'id_dependents_off' => array(
                                'card-wrapper' => array('post_content_wrapper_size_range'),
                                'content-wrapper' => array('post_wrapper_size_range,apply_radius_to_post_image_checkbox'),
                            ),
                            'base_priority' => 'radio_base',
                            'selector_priority' => 'radio_wrapper_mode',
                            'z_index' => 0
                        ),
                        array(
                            'id'             => 'post_wrapper_size_range',
                            'label'            => __(
                                'اندازه نگهدارنده پست',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه میتوانید اندازه نگهدارنده پست را تنظیم کنید.منظور از نگهدارنده پست فاصله بین تصویر و محتوای متنی پست تا کنار کارت پست از هر چهار طرف می باشد.(واحد rem می باشد)', self::$static_textdomain),
                            'type'            => 'range',
                            'min' => 0.5,
                            'max' => 3,
                            'step' => 0.1,
                            'default'        => $status_default ? 1 : '',
                            'placeholder'    => __('Placeholder text', self::$static_textdomain),
                            'keywords' => array('اندازه نگهدارنده پست', 'post content wrapper size'),
                        ),
                        array(
                            'id'             => 'apply_radius_to_post_image_checkbox',
                            'label'            => __(
                                'اعمال خمیدگی حاشیه روی تصویر پست',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت فعال بودن این گزینه، خمیدگی تعیین شده در بخش "تنظیم حاشیه و اندازه -> خمیدگی حاشیه" روی تصویر هم اعمال می گردد.در غیر این صورت فقط روی نگهدارنده پست اعمال خواهد شد.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'off' : '',
                            'keywords' => array('hieght', 'ارتفاع', 'fullscreen', 'تمام صفحه', 'ارتباطات'),
                        ),
                        array(
                            'id'             => 'post_content_wrapper_size_range',
                            'label'            => __(
                                'اندازه نگهدارنده محتوا متنی پست',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه میتوانید اندازه نگهدارنده محتوای کارت پست را تنظیم کنید.منظور از نگهدارنده محتوا پست، صرفا فاصله محتوای متنی پست از اطراف می باشد.(واحد rem می باشد)', self::$static_textdomain),
                            'type'            => 'range',
                            'min' => 0.5,
                            'max' => 3,
                            'step' => 0.1,
                            'default'        => $status_default ? 1.5 : '',
                            'placeholder'    => __('Placeholder text', self::$static_textdomain),
                            'keywords' => array('اندازه نگهدارنده پست', 'post content wrapper size'),
                        ),
                        array(
                            'id'             => 'badge_mode_radio',
                            'label'            => __(
                                'مکان نمایش نشان',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید مکان قرارگیری نشان روی پست ها را مشخص کنید.', self::$static_textdomain),
                            'note'    => __('خصوصیات نشان هر پست در بخش تنظیمات هر المان مشخص می گردد.', self::$static_textdomain),
                            'type'            => 'radio',
                            'options'        => array(
                                'image' => 'روی تصویر پست',
                                'heading' => 'کنار عنوان پست',
                            ),
                            'default'        => $status_default ? 'image' : '',
                            'keywords' => array('دکمه', 'استایل', 'گوشه', 'منحنی', 'style', 'button', 'circle', 'rectangle', 'edge'),
                            'id_dependents_on' => array(
                                'image' => array('badge_position_radio'),
                                'heading' => array(),
                            ),
                            'id_dependents_off' => array(
                                'image' => array(),
                                'heading' => array('badge_position_radio'),
                            ),
                            'base_priority' => 'radio_base',
                            'selector_priority' => 'radio_badge_mode',
                            'z_index' => 0
                        ),
                        array(
                            'id'             => 'button_mode_radio',
                            'label'            => __(
                                'مکان نمایش دکمه ها',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید مکان قرارگیری دکمه ها روی پست ها را مشخص کنید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'options'        => array(
                                'image' => 'روی تصویر پست',
                                'card' => 'پایین پست',
                            ),
                            'default'        => $status_default ? 'image' : '',
                            'keywords' => array('دکمه', 'استایل', 'گوشه', 'منحنی', 'style', 'button', 'circle', 'rectangle', 'edge'),
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
                            'default'        => $status_default ? 'rectangle' : '',
                            'keywords' => array('button', 'title', 'btn', 'text', 'دکمه', 'عنوان', 'تیتر'),
                        ),
                        array(
                            'id'             => 'badge_radius_size_range',
                            'label'            => __(
                                'اندازه خمیدگی حاشیه نشان',
                                self::$static_textdomain
                            ),
                            'description'    => __('با اسفاده از این گزینه می توانید خمیدگی حاشیه (Border Radius) را تعیین کنید.(واحد rem می باشد)', self::$static_textdomain),
                            'type'            => 'range',
                            'min' => 0,
                            'max' => 2.5,
                            'step' => 0.1,
                            'default'        => $status_default ? 1 : '',
                            'placeholder'    => __('Placeholder text', self::$static_textdomain),
                            'keywords' => array('میزان خمیدگی نشان', 'badge radius size range'),
                        ),
                        array(
                            'id'             => 'badge_position_radio',
                            'label'            => __(
                                'مکان قرارگیری نشان روی تصویر',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید مکان قرارگیری نشان روی تصویر را مشخص نمایید.', self::$static_textdomain),
                            'note'    => __('در صورت وارد کردن مقدار معتبر برای "نشان"', self::$static_textdomain),
                            'type'            => 'radio',
                            'options'        => array('up-right' => 'بالا - راست', 'up-left' => 'بالا - چپ', 'down-right' => 'پایین - راست', 'down-left' => 'پایین - چپ'),
                            'default'        => $status_default ? 'up-left' : '',
                            'keywords' => array('دکمه', 'استایل', 'گوشه', 'منحنی', 'style', 'button', 'circle', 'rectangle', 'edge')
                        ),
                        array(
                            'id'             => 'shadow_checkbox',
                            'label'            => __(
                                'سایه دور پست',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید وضعیت نمایش یا عدم نمایش سایه دور هر نوشته(پست) را مشخص نمایید.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'on' : '',
                            'keywords' => array('hieght', 'ارتفاع', 'fullscreen', 'تمام صفحه', 'ارتباطات'),
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
                            'keywords' => array('hieght', 'ارتفاع', 'fullscreen', 'تمام صفحه'),
                        ),
                    )
                ),
                // Get layout scale fields
                Irantheme_Section_Utils::layout_scale_fields_generator($status_default, array('radius', 'inner_padding', 'outer_padding'), 'on', 2, 2, 'off', 'off', 0, 0, 1),
                // Get background fields
                Irantheme_Section_Utils::background_fields_generator($status_default, array('image', 'color', 'mask', 'parallax'), '', 'off', true, 'off', 'dark', 0.6, 'off'),
                // Get transform fields
                Irantheme_Section_Utils::transform_fields_generator($status_default),
                // Get slider fields
                Irantheme_Section_Utils::slider_fields_generator($status_default, array('arrow', 'pagination'), 'on', 'off', 'on'),
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
            $badge_default = '';
            $badge_color_default = '';
            $primary_button_default = '';
            $secondary_button_default = '';
            $description_default = '';
            $heading_default = '';
            $images_default = '';
            $base_color_default = '';

            // Check status is deafult or no
            if (is_numeric($index)) {

                $heading = array(
                    'فضای کاری مدرن و مینیمال',
                    'قهوه و خلاقیت در کنار لپ‌تاپ',
                    'ابزار دیجیتال در محیطی آرام',
                    'چیدمان حرفه‌ای میز کار خانگی',
                    'جزئیات یک روز کاری پربازده',
                    'لپ‌تاپی با نمایی از طبیعت',
                    'استایل فکری و ابزار کار',
                    'حال وهوای کافه‌کاری در خانه',
                );

                $description = 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است.';

                $badge = array(
                    'جدید',
                    '',
                    '',
                    'پرطرفدار',
                    '',
                    '',
                    'کلاسیک',
                    ''
                );

                $primary = 'جزئیات';

                $secondary = 'دریافت نوبت';

                $badge_color = array(
                    '2ecc71',
                    'off',
                    'off',
                    'e74c3c',
                    'off',
                    'off',
                    'e67e22',
                    'off'
                );

                $base_color = array(
                    'white',
                    'white',
                    'dark',
                    'white',
                    'white',
                    'dark',
                    'white',
                    'white'
                );

                // List of default images
                $images = array(
                    'https://irantheme.com/wp-content/uploads/2026/02/duglnlvy.webp',
                    'https://irantheme.com/wp-content/uploads/2026/02/e67lt4wq.webp',
                    '',
                    'https://irantheme.com/wp-content/uploads/2026/02/ex7ny7ub.webp',
                    'https://irantheme.com/wp-content/uploads/2026/02/om12seej.webp',
                    '',
                    'https://irantheme.com/wp-content/uploads/2026/02/qbebm9kn.webp',
                    'https://irantheme.com/wp-content/uploads/2026/02/z3tsccwu.webp',
                );

                // Set default value
                $badge_default = $badge[$index];
                $badge_color_default = $badge_color[$index];
                $base_color_default = $base_color[$index];
                $description_default = $description;
                $primary_button_default = $primary;
                $secondary_button_default = $secondary;
                $heading_default = $heading[$index];
                $images_default =  $status_default === 'media' ? Irantheme_Common_Static::get_file_url_media_library($images[$index]) : '';
            }


            // Custom fields
            $object_fields = array(
                array(
                    'title' => 'تنظیم محتوا',
                    'id' => 'edit_object',
                    'description'            => __('تنظیم مقادیر عنصر', self::$static_textdomain),
                    'icon' => 'fa-pen-to-square',
                    'fields' => array(
                        array(
                            'id'             => 'heading',
                            'label'            => __(
                                'عنوان تصویر',
                                self::$static_textdomain

                            ),
                            'description'    => __('در صورت تمایل عنوان تصویر خود را وارد نمایید.'),
                            'type'            => 'text',
                            'default'        => $heading_default,
                            'placeholder'    => __('برای مثال: نمونه کار شماره یک', self::$static_textdomain),
                            'keywords' => array('عنوان', 'title'),
                        ),
                        array(
                            'id'             => 'description',
                            'label'            => __(
                                'توضیحات تصویر',
                                self::$static_textdomain

                            ),
                            'description'    => __('در صورت تمایل توضیحات تصویر خود را وارد نمایید.'),
                            'type'            => 'textarea',
                            'default'        => $description_default,
                            'placeholder'    => __('توضیحات کوتاه', self::$static_textdomain),
                            'keywords' => array('عنوان', 'title'),
                        ),
                        array(
                            'id'             => 'badge_checkbox',
                            'label'            => __(
                                'نمایش نشان',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت نیاز به نمایش نشان این گزینه را فعال کنید.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => 'on',
                            'keywords' => array('badge', 'title', 'text', 'نشان', 'عنوان'),
                            'id_dependents_on' => array('badge_title', 'badge_bg_color_select', 'badge_text_color_select'),
                            'selector_priority' => 'checkbox_badge',
                            'base_priority' => 'checkbox_base',
                            'z_index' => 0
                        ),
                        array(
                            'id'             => 'badge_title',
                            'label'            => __(
                                'عنوان نشان(بج)',
                                self::$static_textdomain

                            ),
                            'description'    => __('در صورت تمایل عنوان نشان خود را وارد نمایید.'),
                            'type'            => 'text',
                            'default'        => $badge_default,
                            'placeholder'    => __('برای مثال: جدید، اختصاصی، پرطرفدار و ...', self::$static_textdomain),
                            'keywords' => array('نشان عنوان', 'title'),
                        ),
                        array(
                            'id'             => 'badge_bg_color_select',
                            'label'            => __(
                                'رنگ پس زمینه نشان',
                                self::$static_textdomain
                            ),
                            'description'    => __('برای انتخاب رنگ پس زمینه نشان از لیست رنگ دلخواه خود را انتخاب کنید.', self::$static_textdomain),
                            'type'            => 'select',
                            'options'        => Irantheme_Color_Static::get_all_colors_options(false, false, false, true),
                            'color_preview' => true,
                            'default'        => $badge_color_default,
                            'keywords' => array('رنگ جزئيات', 'detail color'),
                        ),
                        array(
                            'id'             => 'badge_text_color_select',
                            'label'            => __(
                                'رنگ متن نشان',
                                self::$static_textdomain
                            ),
                            'description'    => __('از طریق این گزینه می توانید رنگ مناسب برای متن نشان را انتخاب کنید.', self::$static_textdomain),
                            'note'    => __('در صورت وارد کردن مقدار معتبر برای "نشان"', self::$static_textdomain),
                            'type'            => 'select',
                            'options'        => array('white' => 'سفید', 'dark' => 'مشکی'),
                            'color_preview' => true,
                            'default'        => 'white',
                            'keywords' => array('رنگ متن تاریخ انتشار', 'badge text color'),
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
                            'id_dependents_on' => array('btn_primary_title', 'btn_primary_link', 'btn_primary_color_select', 'btn_secondary_checkbox'),
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
                            'default'        => $primary_button_default,
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
                            'default'        => '#',
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
                            'default'        => 'off',
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
                            'id_dependents_on' => array('btn_secondary_title', 'btn_secondary_link', 'btn_secondary_color_select'),
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
                            'default'        => $secondary_button_default,
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
                            'default'        => '#',
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
                            'id'             => 'hover_mask_button_select',
                            'label'            => __(
                                'رنگ لایه پوشاننده',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید رنگ لایه پوشاننده تصویر در هنگام نمایش دکمه ها را تغییر دهید.', self::$static_textdomain),
                            'type'            => 'select',
                            'options'        => array('white' => 'سفید', 'dark' => 'مشکی'),
                            'default'        => 'white',
                            'keywords' => array('color', 'background', 'پس زمینه', 'رنگ'),
                        ),
                    )
                ),
                array(
                    'title' => 'تنظیمات بصری',
                    'id' => 'layout_object',
                    'description'            => __('تنظیمات بصری مانند :ارتفاع، ستون بندی و...', self::$static_textdomain),
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
                            'default'        => $base_color_default,
                            'keywords' => array('color', 'background', 'پس زمینه', 'رنگ'),
                        ),
                        array(
                            'id'             => 'align_content_radio',
                            'label'            => __('تراز افقی محتوا', self::$static_textdomain),
                            'description'    => __('با استفاده از این گزینه می توانید تراز افقی جزئیات محتوا را مشخص کنید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'thumbnail_status' => true,
                            'options'        => array('right' => array('راست چین', 'text-align-right.png'), 'center' => array('وسط چین', 'text-align-center.png'), 'left' => array('چپ چین', 'text-align-left.png')),
                            'default'        => 'center',
                        ),
                    )
                ),
                // Get background fields
                Irantheme_Section_Utils::background_fields_generator(true, array('image', 'color'), $images_default, 'off', true),
            );
            return $object_fields;
        }


        /**
         * Render elements of section
         * @param string,string,string,string,mixed
         * @return void
         * @author M-MALEKZADEH77
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
            $aos_delay = Irantheme_Section_Utils::get_data_load_delay($fields_values);

            // Get data load style --------------------------------------------------------------
            $heading_data_load_checkbox = $fields_values['heading_data_load_checkbox'];
            $heading_data_load_attr = Irantheme_Section_Utils::get_heading_data_load_attr_type($fields_values);
            // Get data load duration
            $heading_data_load_duration = Irantheme_Section_Utils::get_heading_data_load_duration($fields_values);
            // Get aos delay time
            $heading_aos_delay = Irantheme_Section_Utils::get_heading_data_load_delay($fields_values);

            // Fix conflict skeleton with data load (Aos) --------------------------------------------------------------
            $data_load_checkbox = $fields_values['data_load_checkbox'];
            $skeleton_section_class = 'irantheme-skeleton-section';
            if ($data_load_checkbox == 'on' || $heading_data_load_checkbox == 'on') {
                $skeleton_section_class = '';
            }

            // Base color --------------------------------------------------------------
            $base_color = $fields_values['base_color_select'];
            // Set base color class
            $base_color_class = Irantheme_Common_Static::get_base_color_class($base_color);

            // Get section heading --------------------------------------------------------------
            $tinymce_heading = $fields_values['text_editor_heading'];

            // Get gallery style --------------------------------------------------------------
            $gallery_style = $fields_values['gallery_type_style_radio'];

            // Get col count gallery --------------------------------------------------------------
            $col_count = $fields_values['media_col_count_radio'];

            // Slider status
            $slider_arrow = $fields_values['slider_arrow_checkbox'] == 'on' ? true : false;
            $slider_pagination = $fields_values['slider_pagination_checkbox'] == 'on' ? true : false;

            // Get border radius --------------------------------------------------------------
            $border_radius_range = $fields_values['border_radius_range'];
            $border_radius_style = Irantheme_Section_Utils::get_border_radius_css($border_radius_range);

            // Get grid gap --------------------------------------------------------------
            $cells_gap = $fields_values['cell_gap_range'];

            // Section transform to up and down --------------------------------------------------------------
            $section_transfrom_values = Irantheme_Section_Utils::get_transform_values_arrays($data_page, $fields_values, true, false, false);
            $section_transform_extra_css = $section_transfrom_values['overflow'];
            $section_transform_translate_css = $section_transfrom_values['css'];

            // Get section id --------------------------------------------------------------
            $section_id = $fields_values['element_id'];
            $section_id_attribute = '';
            if ($section_id) {
                $section_id_attribute = sprintf('id=%s', $section_id);
            }
?>
            <!-- ============================= Section business portfolio ============================= -->
            <section <?php echo esc_attr($section_id_attribute); ?> class="irantheme-section irantheme-business-portfolio-section <?php echo esc_attr($skeleton_section_class); ?>" <?php echo esc_attr(Irantheme_Page_Builder::get_attr_data_section($data_type, $data_value, $data_page, $data_ajax)); ?> style="<?php echo esc_attr($section_transform_extra_css); ?><?php echo esc_attr(Irantheme_Section_Service::get_custom_outer_padding_css($fields_values)); ?>">
                <?php
                // Set wrapper (Container) --------------------------------------------------------------
                $container_checkbox = $fields_values['wrapper_mode_checkbox'];
                ?>
                <div class="irantheme-business-portfolio <?php echo esc_attr($base_color_class); ?> irantheme-background-jarallax" style="<?php echo esc_attr($background_color_css); ?><?php echo esc_attr($background_image_css); ?><?php echo esc_attr(Irantheme_Section_Service::get_custom_inner_padding_css($fields_values)); ?>" <?php echo $background_parallax_attr; ?>>

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
                        ?>
                        <!-- Main wrapper -->
                        <div class="irantheme-business-portfolio-wrapper" style="<?php echo esc_attr($section_transform_translate_css); ?>">
                            <?php
                            if ($tinymce_heading != '' && $heading_data_load_checkbox == 'on'):
                            ?>
                                <div class="irantheme-tinymce-heading-aos-holder" <?php echo ($heading_data_load_attr); ?> data-aos-delay="<?php echo ($heading_aos_delay); ?>" data-aos-duration="<?php echo ($heading_data_load_duration); ?>">
                                <?php
                            endif;
                            // Get showcase content --------------------------------------------------------------
                            Irantheme_Common_Static::tinymce_heading($fields_values);
                            if ($tinymce_heading != '' && $heading_data_load_checkbox == 'on'):
                                ?>
                                </div>
                            <?php endif; ?>

                            <?php
                            $skeleton_load_class = '';
                            if ($gallery_style == 'carousel' && $data_load_checkbox != 'on' && $heading_data_load_checkbox != 'on') {
                                // Skeleton html --------------------------------------------------------------
                                irantheme_get_skeleton_html($col_count, 'card', $base_color_class);
                                $skeleton_load_class = 'irantheme-side-load-skeleton';
                            }
                            ?>

                            <div class="irantheme-business-portfolio-media <?php echo esc_attr($skeleton_load_class); ?>">
                                <?php
                                if ($gallery_style == 'grid') {
                                    // Get media gallery content --------------------------------------------------------------
                                    Irantheme_Common_Static::gallery_grid_post($json_post_array, 'business_portfolio', $col_count, $cells_gap, $aos_delay, $data_load_attr, $data_load_duration, $data_type, $data_value, $data_page, $data_ajax, $gallery_style, '', '', '', '', '', '', $border_radius_style, true, $fields_values);
                                } elseif ($gallery_style == 'masonry') {
                                    // Get media gallery content --------------------------------------------------------------
                                    Irantheme_Common_Static::gallery_masonry_post($json_post_array, 'business_portfolio', $col_count, $cells_gap, $aos_delay, $data_load_attr, $data_load_duration, $data_type, $data_value, $data_page, $data_ajax, $gallery_style, '', '', '', '', '', '', $border_radius_style, true, $fields_values);
                                } elseif ($gallery_style == 'carousel') {
                                    // Get media gallery content --------------------------------------------------------------
                                    Irantheme_Common_Static::gallery_carousel_post($json_post_array, 'business_portfolio', $col_count, $cells_gap, $aos_delay, $data_load_attr, $data_load_duration, $data_type, $data_value, $data_page, $data_ajax, $gallery_style, '', '', '', '', '', '', $border_radius_range, true, $slider_arrow, $slider_pagination, $fields_values);
                                }
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
