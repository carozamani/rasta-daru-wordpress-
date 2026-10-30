<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;

if (!class_exists('Irantheme_Section_Post_Sidebar')) {
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
    class Irantheme_Section_Post_Sidebar extends Irantheme_Section
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
            $heading_content = '';
            $slider_last_array_extra = array(
                array(
                    'id'             => 'info_type_checkbox_multi',
                    'label'            => __(
                        'جزئیات نوشته ها',
                        self::$static_textdomain
                    ),
                    'description'    => __('با استفاده از این گزینه می توانید نوع جزئیات قابل نمایش برای هر نوشته(پست) را مشخص کنید.مانند تاریخ انتشار، دسته بندی و غیره... ', self::$static_textdomain),
                    'type'            => 'checkbox_multi',
                    'options'        => array('date' => 'تاریخ انتشار', 'author' => 'نویسنده', 'comment' => 'تعداد دیدگاه ها', 'excerpt' => 'گزیده مطلب', 'category' => 'دسته بندی'),
                    'default'        => $status_default ? 'category,excerpt' : '',
                    'keywords' => array('جزئیات نوشته ها', 'posts details'),
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
                    'default'        => $status_default ? 60 : '',
                    'min' => 5,
                    'max' => 75,
                    'placeholder'    => __('', self::$static_textdomain),
                    'keywords' => array('تعداد کلمات گزیده مطلب (چکیده)', 'excerpt words number', 'excerpt count'),
                ),
                array(
                    'id'             => 'height_range',
                    'label'            => __(
                        'ارتفاع اسلایدر',
                        self::$static_textdomain
                    ),
                    'description'    => __('با اسفاده از این گزینه می توانید ارتفاع اسلایدر مربوط به نوشته ها را تنظیم نمایید.(واحد px می باشد)', self::$static_textdomain),
                    'type'            => 'range',
                    'min' => 180,
                    'max' => 700,
                    'step' => 1,
                    'default'        => $status_default ? 550 : '',
                    'placeholder'    => __('Placeholder text', self::$static_textdomain),
                    'keywords' => array('communication', 'contact', 'ارتباط با ما',  'ارتباطات', 'اندازه', 'ارتفاع')
                ),
            );
            $settings = array(
                Irantheme_Section_Utils::heading_title_settings_generator($status_default, 'none', false, false, $heading_content, 'off', '', '', '', 'off', 'off', '', '', '', '', 'rectangle', 'right', 'off', '', '0', 2),
                Irantheme_Section_Utils::get_post_content_type_fields($status_default, 'slider', 'تنظیم محتوای اسلایدر', 'تنظیم محتوا شامل چیدمان محتوا، سایدبار، نوع محتوا و مطالب، امکانات و ... می باشد.', 'fa-images', array(), $slider_last_array_extra),
                array(
                    'title' => 'تنظیم سایدبار',
                    'id' => 'sidebar',
                    'description'            => __('تنظیم محتوا شامل چیدمان محتوا، سایدبار، نوع محتوا و مطالب، امکانات و ... می باشد.', self::$static_textdomain),
                    'icon' => 'fa-sidebar',
                    'fields' => array(
                        array(
                            'id'             => 'sidebar_checkbox',
                            'label'            => __(
                                'فعال سازی سایدبار',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت فعال کردن سایدبار نمایش داده می شود.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'on' : '',
                            'keywords' => array('فعال سازی سایدبار', 'نمایش سایدبار', 'display sidebar', 'show sidebar'),
                            'id_dependents_on' => array('sidebar_position', 'sidebar_select'),
                            'id_dependents_off' => array(),
                            'base_priority' => 'checkbox_base',
                            'selector_priority' => 'checkbox_sidebar_priority',
                            'z_index' => 0
                        ),
                        array(
                            'id'             => 'sidebar_position',
                            'label'            => __(
                                'چیدمان سایدبار',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید سبک نمایش محتوای سایدبار خود را مشخص کنید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'thumbnail_status' => true,
                            'options'        => array('left' => array('سایدبار چپ', 'align-section-right.png'), 'right' => array('سایدبار راست', 'align-section-left.png')),
                            'default'        => $status_default ? 'left' : '',
                            'keywords' => array('چیدمان سایدبار', 'sidebar position')
                        ),
                        array(
                            'id'             => 'sidebar_select',
                            'label'            => __(
                                'انتخاب سایدبار',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه شما می توانید سایدبار های تولید شده در قسمت "نمایش -> ابزارک ها" را انتخاب نمایید.', self::$static_textdomain),
                            'type'            => 'select',
                            'options'        => Irantheme_Custom_Fields::get_all_sidebars(),
                            'default'        => $status_default ? 'irantheme_sidebar_template' : '',
                            'keywords' => array('انتخاب سایدبار', 'sidebar select'),
                        ),
                    )
                ),
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
                            'default'        => $status_default ? Irantheme_Section_Default::get_default_ids_objects(1, 'post_sidebar', $status_default) : '',
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
                            'id'             => 'col_count_radio',
                            'label'            => __('تعداد ستون ها', self::$static_textdomain),
                            'description'    => __('با استفاده از این گزینه شما می توانید تعداد ستون قابل نمایش برای گالری را مشخص کنید', self::$static_textdomain),
                            'type'            => 'radio',
                            'thumbnail_status' => true,
                            'options'        => array(
                                'col-1' => array('یک ستون', 'col-1.png'),
                                'col-2' => array('دو ستون', 'col-2.png'),
                                'col-3' => array('سه ستون', 'col-3.png'),
                                'col-4' => array('چهار ستون', 'col-4.png'),
                                'col-6' => array('شش ستون', 'col-6.png')
                            ),
                            'default'        => $status_default ? 'col-2' : '',
                            'keywords' => array('box', 'col', 'author', 'تعداد', 'سرویس', 'تعداد در هر ردیف'),
                        ),
                        array(
                            'id'             => 'align_content_radio',
                            'label'            => __('تراز محتوا', self::$static_textdomain),
                            'description'    => __('با استفاده از این گزینه می توانید تراز جزئیات پست ها را مشخص کنید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'thumbnail_status' => true,
                            'options'        => array('right' => array('راست چین', 'text-align-right.png'), 'center' => array('وسط چین', 'text-align-center.png'), 'left' => array('چپ چین', 'text-align-left.png')),
                            'default'        => $status_default ? 'right' : '',
                            'keywords' => array('box', 'col', 'author', 'تعداد', 'سرویس', 'تعداد در هر ردیف'),
                        ),
                        array(
                            'id'             => 'horizontal_checkbox',
                            'label'            => __(
                                'چیش افقی محتوا',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت فعال سازی این گزینه محتوای عکس و نوشته به صورت افقی در کنار هم نمایش داده خواهند شد.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'on' : '',
                            'keywords' => array('چیش افقی محتوا', 'content horizontal alignment'),
                        ),
                        array(
                            'id'             => 'info_icons_checkbox',
                            'label'            => __(
                                'نمایش نماد جزئیات نوشته ها',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت تمایل به تغییر وضعیت نمایش نمادهای (آیکون) بخش جزئیات نوشته ها مانند نویسنده، تاریخ انتشار و... می توانید از این گزینه استفاده کنید.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'off' : '',
                            'keywords' => array('post', 'post box', 'box', 'نوشته ها', 'نوشته', 'پست'),
                        ),
                        array(
                            'id'             => 'image_visibility_checkbox',
                            'label'            => __(
                                'نمایش تصویر شاخص نوشته',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید وضعیت نمایش یا عدم نمایش تصویر شاخص مربوط به نوشته ها را مشخص کنید.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'on' : '',
                            'keywords' => array('hieght', 'ارتفاع', 'fullscreen', 'تمام صفحه', 'ارتباطات'),
                            'id_dependents_on' => array('image_height_range', 'article_padding_checkbox'),
                            'id_dependents_off' => array(),
                            'base_priority' => 'checkbox_base',
                            'selector_priority' => 'checkbox_image',
                        ),
                        array(
                            'id'             => 'image_height_range',
                            'label'            => __(
                                'ارتفاع تصویر شاخص',
                                self::$static_textdomain
                            ),
                            'description'    => __('با اسفاده از این گزینه می توانید ارتفاع تصویر شاخص مربوط به نوشته ها را تنظیم نمایید.(واحد px می باشد)', self::$static_textdomain),
                            'type'            => 'range',
                            'min' => 50,
                            'max' => 450,
                            'step' => 1,
                            'default'        => $status_default ? 110 : '',
                            'placeholder'    => __('Placeholder text', self::$static_textdomain),
                            'keywords' => array('communication', 'contact', 'ارتباط با ما',  'ارتباطات', 'اندازه', 'ارتفاع')
                        ),
                        array(
                            'id'             => 'article_padding_checkbox',
                            'label'            => __(
                                'حاشیه دور پست',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید حاشیه ای به دور تصاویر پست ایجاد کنید، این گزینه می تواند تاثیر به سزایی در زیبایی داشته باشد.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'on' : '',
                            'keywords' => array('حاشیه دور پست', 'image padding checkbox'),
                        ),
                        array(
                            'id'             => 'border_checkbox',
                            'label'            => __(
                                'خط دور پست',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید وضعیت نمایش یا عدم نمایش خط دور هر نوشته (بوردر) را مشخص نمایید.در صوررت فعال نمودن این گزینه نوشته (پست) ها به شکل باکسی در می آیند.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'off' : '',
                            'keywords' => array('hieght', 'ارتفاع', 'fullscreen', 'تمام صفحه', 'ارتباطات'),
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
                            'id'             => 'transparent_checkbox',
                            'label'            => __(
                                'پست شیشه ای',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید رنگ پس زمینه پست ها را شیشه ای کنید.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'off' : '',
                            'keywords' => array('پست شیشه ای', 'transparent post'),
                        ),
                        array(
                            'id'             => 'hover_mode_radio',
                            'label'            => __(
                                'سبک واکنش به نشانگر (hover)',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید نوع واکنش رسانه های موجود در گالری به عبور نشانگر از روی آن ها (hover) را مشخص کنید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'options'        => array('normal' => 'ساده', 'shadow' => 'تغییر وضعیت سایه', 'transform' => 'انتقال پست به بالا', 'mask' => 'لایه پوشاننده تصویر', 'zoom-image' => 'بزرگنمایی تصویر', 'zoom-post' => 'بزرگنمایی پست'),
                            'default'        => $status_default ? 'shadow' : '',
                            'keywords' => array('دکمه', 'استایل', 'گوشه', 'منحنی', 'style', 'button', 'circle', 'rectangle', 'edge')
                        ),
                        array(
                            'id'             => 'details_color_select',
                            'label'            => __(
                                'رنگ جزئیات',
                                self::$static_textdomain
                            ),
                            'description'    => __('برای انتخاب رنگ جزئیات از لیست رنگ دلخواه خود را انتخاب کنید. این رنگ تاثیر مستقیمی در زیبایی جزئيات دارد.', self::$static_textdomain),
                            'type'            => 'select',
                            'options'        => Irantheme_Color_Static::get_all_colors_options(),
                            'color_preview' => true,
                            'default'        => $status_default ? 'primary' : '',
                            'keywords' => array('رنگ جزئیات', 'detail color'),
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
                // Get slider fields
                Irantheme_Section_Utils::slider_fields_generator($status_default, array('slider_status', 'arrow', 'pagination', 'autoplay', 'delay'), 'on', 'off', 'on'),
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
            // Custom fields
            $object_fields = array(
                array(
                    'title' => 'تنظیمات عنوان',
                    'id' => 'heading_object',
                    'description'            => __('تنظیم عنوان و دکمه نوشته ها', self::$static_textdomain),
                    'icon' => 'fa-heading',
                    'fields' => array(
                        array(
                            'id'             => 'heading',
                            'label'            => __(
                                'عنوان نوشته ها',
                                self::$static_textdomain
                            ),
                            'description'    => __('عنوان نوشته خود را وارد کنید.'),
                            'type'            => 'text',
                            'default'        => __('آخرین مطالب سایت'),
                            'placeholder'    => __('عنوان خود را وارد کنید', self::$static_textdomain),
                        ),
                        array(
                            'id'             => 'heading_link',
                            'label'            => __(
                                'لینک عنوان نوشته ها',
                                self::$static_textdomain
                            ),
                            'description'    => __('عنوان لینک نوشته خود را وارد کنید.'),
                            'type'            => 'text',
                            'default'        => '#',
                            'placeholder'    => __('عنوان خود را وارد کنید', self::$static_textdomain),
                        ),
                    )
                ),
                Irantheme_Section_Utils::get_post_content_type_fields(true, 'post_box_part', 'نوشته ها', 'تنظیمات مربوط به محتوای نوشته ها', 'fa-newspaper', array(), array(), false, 10, 8),
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

            // Get section heading --------------------------------------------------------------
            $tinymce_heading = $fields_values['text_editor_heading'];
            // Base color --------------------------------------------------------------
            $base_color = $fields_values['base_color_select'];
            // Set base color class
            $base_color_class = Irantheme_Common_Static::get_base_color_class($base_color);

            // Get post info type --------------------------------------------------------------
            $post_info_type = $fields_values['info_type_checkbox_multi'];
            $post_info_type = explode(',', $post_info_type);

            // Get post info icon display mode --------------------------------------------------------------
            $post_info_icon = $fields_values['info_icons_checkbox'];

            // Get post content align --------------------------------------------------------------
            $content_alighment = $fields_values['align_content_radio'];

            // Get col count box --------------------------------------------------------------
            $col_count_box = $fields_values['col_count_radio'];
            $col_bootstrap_class = Irantheme_Section_Service::get_bootstrap_class($col_count_box, 12);

            // Get image thumb visibility checkbox --------------------------------------------------------------
            $image_thumb_checkbox = $fields_values['image_visibility_checkbox'];

            // Get image thumb height --------------------------------------------------------------
            $image_thumb_height = $fields_values['image_height_range'];

            // Get post horizontal checkbox --------------------------------------------------------------
            $post_horizontal_checkbox = $fields_values['horizontal_checkbox'];
            $post_horizontal_class = '';
            $post_image_width_height_css = 'height:' . $image_thumb_height . 'px;';
            if ($post_horizontal_checkbox == 'on') {
                $post_horizontal_class = 'irantheme-post-sidebar-item-horizontal';
                $post_image_width_height_css = 'width:' . $image_thumb_height . 'px;height:' . $image_thumb_height . 'px;';
            }

            // Get border radius --------------------------------------------------------------
            $border_radius_range = $fields_values['border_radius_range'];
            $border_radius_style = Irantheme_Section_Utils::get_border_radius_css($border_radius_range);

            // Get image padding checkbox --------------------------------------------------------------
            $article_padding_checkbox = $fields_values['article_padding_checkbox'];
            $article_padding_css = '';
            $content_padding_css = 'padding: 0 1rem 1rem;';
            $image_border_radius_css = '';
            if ($article_padding_checkbox == 'on') {
                $article_padding_css = 'padding: 1rem;';
                $content_padding_css = 'padding:0;';
                $image_border_radius_css = $border_radius_style;
            }

            // Get hover mode --------------------------------------------------------------
            $hover_mode = $fields_values['hover_mode_radio'];

            // Get border mode --------------------------------------------------------------
            $border_mode = $fields_values['border_checkbox'];

            // Get border mode --------------------------------------------------------------
            $transparent_mode = $fields_values['transparent_checkbox'];

            // Get border mode --------------------------------------------------------------
            $shadow_mode = $fields_values['shadow_checkbox'];

            // Section transform to up and down --------------------------------------------------------------
            $section_transfrom_values = Irantheme_Section_Utils::get_transform_values_arrays($data_page, $fields_values, true, false, false);
            $section_transform_extra_css = $section_transfrom_values['overflow'];
            $section_transform_translate_css = $section_transfrom_values['css'];

            // Section transform to up and down --------------------------------------------------------------
            $section_transfrom_values = Irantheme_Section_Utils::get_transform_values_arrays($data_page, $fields_values, true, false, false);
            $section_transform_extra_css = $section_transfrom_values['overflow'];
            $section_transform_translate_css = $section_transfrom_values['css'];

            // Get sidebar options --------------------------------------------------------------
            $sidebar_select = $fields_values['sidebar_select'];

            $sidebar_checkbox = $fields_values['sidebar_checkbox'];

            // Get sidebar position
            $sidebar_position = $fields_values['sidebar_position'];

            $content_class = 'col-lg-12';
            $sidebar_class = '';
            if ($sidebar_checkbox == 'on') {
                $content_class = 'col-lg-9';
                $sidebar_class = 'col-lg-3';
            }

            $row_class = '';
            if ($sidebar_position == 'left') {
                $row_class = 'flex-row-reverse';
            } else if ($sidebar_position == 'right') {
                $row_class = 'flex-row';
            }

            // Details color --------------------------------------------------------------
            $details_color = $fields_values['details_color_select'];
            $details_color_class = '';
            if ($details_color == 'off') {
                $details_color_class = '';
            } else {
                $details_color_class = Irantheme_Color_Static::get_class_color_mode('color', null, Irantheme_Color_Static::get_hex_color($details_color));
            }

            // Details of post
            $slider_post_info_type = $fields_values['info_type_checkbox_multi'];
            $slider_post_info_type = explode(',', $slider_post_info_type);

            // Get slider height
            $slider_post_height = $fields_values['height_range'];
            if ($slider_post_height) {
                $slider_post_height_css = 'height:' . $slider_post_height . 'px;';
            }
            // Get slider post excerpt number
            $post_excerpt_number = $fields_values['post_excerpt_number'];

            // Slider --------------------------------------------------------------
            $slider_checkbox = $fields_values['slider_checkbox'];
            // Get slider autoplay
            $slider_autoplay_checkbox = $fields_values['slider_autoplay_checkbox'];
            // Get slider delay
            $slider_delay_number = $fields_values['slider_autoplay_delay_number'];
            $slider_autoplay_activation = 0;
            $slider_autoplay_delay = '';
            if ($slider_autoplay_checkbox == 'on') {
                $slider_autoplay_activation = 1;
                $slider_autoplay_delay = $slider_delay_number;
            }

            // Get section id --------------------------------------------------------------
            $section_id = $fields_values['element_id'];
            $section_id_attribute = '';
            if ($section_id) {
                $section_id_attribute = sprintf('id=%s', $section_id);
            }

            // Get post content with sending post-content-type --------------------------------------------------------------
            $slider_post_content = Irantheme_Section_Utils::get_post_content($fields_values);
?>
            <!-- ============================= Section post sidebar ============================= -->
            <section <?php echo esc_attr($section_id_attribute); ?> class="irantheme-section irantheme-post-sidebar-section irantheme-wrapper-categories-trigger" <?php echo esc_attr(Irantheme_Page_Builder::get_attr_data_section($data_type, $data_value, $data_page, $data_ajax)); ?> style="<?php echo esc_attr($section_transform_extra_css); ?><?php echo esc_attr(Irantheme_Section_Service::get_custom_outer_padding_css($fields_values)); ?>">
                <!-- <div class="irantheme-post-sidebar-animation" <?php echo ($data_load_attr); ?> data-aos-delay="<?php echo ($aos_delay); ?>" data-aos-duration="<?php echo ($data_load_duration); ?>"> -->
                <?php
                // Set wrapper (Container) --------------------------------------------------------------
                $container_checkbox = $fields_values['wrapper_mode_checkbox'];
                ?>
                <div class="irantheme-post-sidebar <?php echo esc_attr($base_color_class); ?> irantheme-background-jarallax" style="<?php echo esc_attr($background_color_css); ?><?php echo esc_attr($background_image_css); ?><?php echo esc_attr(Irantheme_Section_Service::get_custom_inner_padding_css($fields_values)); ?>" <?php echo $background_parallax_attr; ?>>

                    <?php if (!$slider_post_content->have_posts()):
                        // Empty status
                        irantheme_display_empty_status('file-lines', 'نوشته ای جهت نمایش یافت نشد.', 'با رفتن به محیط ادمین و تب "نوشته ها" -> "افزودن نوشته" نوشته مورد نظر خود را بسازید.');
                    endif; ?>

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
                        <!-- News letter post -->
                        <div class="irantheme-post-sidebar-post-wrapper irantheme-categories-posts" style="<?php echo esc_attr($section_transform_translate_css); ?>">
                            <?php
                            if ($tinymce_heading != '' && $heading_data_load_checkbox == 'on'):
                            ?>
                                <div class="irantheme-tinymce-heading-aos-holder" <?php echo ($heading_data_load_attr); ?> data-aos-delay="<?php echo ($heading_aos_delay); ?>" data-aos-duration="<?php echo ($heading_data_load_duration); ?>">
                                <?php
                            endif;
                                ?>
                                <?php
                                // Get showcase content --------------------------------------------------------------
                                Irantheme_Common_Static::tinymce_heading($fields_values);
                                if ($tinymce_heading != '' && $heading_data_load_checkbox == 'on'):
                                ?>
                                </div>
                            <?php
                                endif;
                            ?>
                            <div class="irantheme-post-sidebar-post-item-holder">
                                <div class="row <?php echo esc_attr($row_class); ?>">
                                    <?php if ($sidebar_checkbox == 'on') : ?>
                                        <style scoped>
                                            /* Set border radius page -------------------------------------------------------- */
                                            .irantheme-border-radius-pages-<?php echo esc_attr($data_ajax) . ' '; ?>.irantheme-sidebar-box-holder {
                                                border-radius: <?php echo $border_radius_range . 'rem !important;' ?>;
                                            }
                                        </style>
                                        <!-- Sidebar -->
                                        <div class="<?php echo esc_attr($sidebar_class); ?>">
                                            <div class="irantheme-sidebar-holder <?php echo esc_attr($base_color_class); ?> irantheme-border-radius-pages-<?php echo esc_attr($data_ajax); ?>">
                                                <?php dynamic_sidebar($sidebar_select); ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Content -->
                                    <div class="<?php echo esc_attr($content_class); ?>">
                                        <?php if ($slider_checkbox == 'on'): ?>
                                            <div class="irantheme-post-sidebar-animation" <?php echo ($data_load_attr); ?> data-aos-delay="<?php echo ($aos_delay); ?>" data-aos-duration="<?php echo ($data_load_duration); ?>">
                                                <div class="swiper irantheme-post-sidebar-slider-swiper" data-autoplay-status="<?php echo esc_attr($slider_autoplay_activation); ?>" data-autoplay-delay="<?php echo esc_attr($slider_autoplay_delay); ?>">
                                                    <div class="swiper-wrapper">
                                                        <?php
                                                        // While on wp_query posts
                                                        if ($slider_post_content->have_posts()) :
                                                        ?>
                                                            <?php
                                                            while ($slider_post_content->have_posts()) :
                                                                $slider_post_content->the_post();
                                                                // // Get thumb url of post
                                                                $slider_thumb_url = get_the_post_thumbnail_url();
                                                            ?>
                                                                <article class="irantheme-post-sidebar-slider-item swiper-slide mb-4" style="<?php echo esc_attr($border_radius_style); ?>">
                                                                    <div class="irantheme-post-sidebar-slider-item-inner-wrapper">
                                                                        <?php
                                                                        // Check selected thumb for post
                                                                        if ($slider_thumb_url != '') :
                                                                        ?>
                                                                            <div class="irantheme-post-sidebar-slider-item-img">
                                                                                <a href="<?php echo get_permalink(); ?>">
                                                                                    <div class="irantheme-post-sidebar-slider-item-img-wrapper" style="<?php echo esc_attr($slider_post_height_css); ?><?php echo esc_attr($border_radius_style); ?>">
                                                                                        <!-- Overlay -->
                                                                                        <div class="irantheme-post-sidebar-slider-item-img-wrapper-overlay"></div>
                                                                                        <?php
                                                                                        if (has_post_thumbnail()) {
                                                                                            echo wp_get_attachment_image(
                                                                                                get_post_thumbnail_id(),
                                                                                                'large', // یا 'medium_large' برای performance بهتر
                                                                                                false,
                                                                                                [
                                                                                                    'alt' => get_the_title() ?: __('تصویر شاخص', self::$static_textdomain),
                                                                                                    'loading' => 'lazy',
                                                                                                ]
                                                                                            );
                                                                                        } else {
                                                                                            // اگر thumbnail ندارد، یک placeholder یا fallback نمایش دهید
                                                                                            echo '<img src="' . esc_url(IRANTHEME_URI . '/assets/images/placeholder.jpg') . '" alt="' . esc_attr__('بدون تصویر شاخص', self::$static_textdomain) . '" loading="lazy">';
                                                                                        }
                                                                                        ?>
                                                                                    </div>
                                                                                </a>
                                                                            </div>
                                                                        <?php endif; ?>
                                                                        <div class="irantheme-post-sidebar-slider-item-content">
                                                                            <div class="irantheme-post-sidebar-slider-item-content-details">
                                                                                <?php
                                                                                // Date
                                                                                if (in_array('date', $slider_post_info_type)) :
                                                                                ?>
                                                                                    <div class="irantheme-post-sidebar-slider-item-content-details-date">
                                                                                        <?php
                                                                                        echo $post_info_icon == 'on' ? '<i class="fa-light fa-calendar-days"></i>' : '';
                                                                                        echo get_the_date('j F Y'); ?></div>
                                                                                    <!-- Separator -->
                                                                                    <div class="irantheme-post-sidebar-slider-item-content-details-separator <?php echo esc_attr($details_color_class); ?>">.</div>
                                                                                <?php
                                                                                endif;
                                                                                ?>
                                                                                <?php
                                                                                // Author
                                                                                if (in_array('author', $slider_post_info_type)) :
                                                                                ?>
                                                                                    <div class="irantheme-post-sidebar-slider-item-content-details-author">
                                                                                        <?php
                                                                                        echo $post_info_icon == 'on' ? '<i class="fa-light fa-user"></i>' : '';
                                                                                        echo get_the_author_posts_link(); ?></div>
                                                                                    <!-- Separator -->
                                                                                    <div class="irantheme-post-sidebar-slider-item-content-details-separator <?php echo esc_attr($details_color_class); ?>">.</div>
                                                                                <?php
                                                                                endif;
                                                                                ?>
                                                                                <?php
                                                                                // Comment
                                                                                if (in_array('comment', $slider_post_info_type)) :
                                                                                ?>
                                                                                    <div class="irantheme-post-sidebar-slider-item-content-details-comment">
                                                                                        <?php
                                                                                        echo $post_info_icon == 'on' ? '<i class="fa-light fa-comments"></i>' : '';
                                                                                        ?>
                                                                                        <em>
                                                                                            <?php
                                                                                            echo get_comments_number(); ?></em>
                                                                                        <span>دیدگاه</span>
                                                                                    </div>
                                                                                <?php
                                                                                endif;
                                                                                ?>
                                                                            </div>
                                                                            <h3 class="irantheme-post-sidebar-slider-item-content-title"><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h3>

                                                                            <?php
                                                                            // Excerpt
                                                                            if (in_array('excerpt', $slider_post_info_type)) :
                                                                            ?>
                                                                                <p class="irantheme-post-sidebar-slider-item-content-description"><?php echo wp_trim_words(strip_shortcodes(get_the_excerpt()), $post_excerpt_number); ?></p>
                                                                            <?php endif; ?>

                                                                            <?php
                                                                            // Category
                                                                            if (in_array('category', $slider_post_info_type)) :
                                                                            ?>
                                                                                <div class="irantheme-post-sidebar-slider-item-content-category">
                                                                                    <?php
                                                                                    foreach (get_the_category() as $category) {
                                                                                    ?>
                                                                                        <a href="<?php echo esc_url_raw(get_category_link($category->term_id)); ?>"><?php echo esc_html('#' . $category->name); ?></a>
                                                                                    <?php
                                                                                    }
                                                                                    ?>
                                                                                </div>
                                                                            <?php
                                                                            endif;
                                                                            ?>
                                                                        </div>
                                                                    </div>
                                                                </article>
                                                            <?php
                                                            endwhile;
                                                            ?>
                                                        <?php
                                                        endif;
                                                        ?>
                                                    </div>
                                                    <?php
                                                    // Slider pagination option
                                                    $slider_pagination_checkbox = $fields_values['slider_pagination_checkbox'];
                                                    // Slider arrow option
                                                    $slider_arrow_checkbox = $fields_values['slider_arrow_checkbox'];
                                                    Irantheme_Section_Utils::get_slider_arrow_pagination($slider_checkbox, $slider_arrow_checkbox, $slider_pagination_checkbox, $base_color_class, $border_radius_range, 'top', 'left');
                                                    ?>
                                                </div>
                                            </div>
                                        <?php
                                            $aos_delay += 200;
                                        endif;
                                        ?>

                                        <div class="irantheme-object-group" data-json="<?php echo esc_attr('objects_json'); ?>">
                                            <?php
                                            foreach ($json_post_array as $object_post) :
                                                // Get post meta --------------------------------------------------------------
                                                // Heading of posts
                                                $heading_posts = $object_post['fields']['heading'];
                                                // Heading of posts
                                                $heading_link_posts = $object_post['fields']['heading_link'];

                                                // Heading link of posts
                                                // Details of post
                                                $post_info_type = $object_post['fields']['info_type_checkbox_multi'];
                                                $post_info_type = explode(',', $post_info_type);

                                                // Get post excerpt
                                                $post_excerpt_number = $object_post['fields']['post_excerpt_number'];

                                                // Get post content with sending post-content-type --------------------------------------------------------------
                                                $post_content = Irantheme_Section_Utils::get_post_content($object_post['fields']);
                                            ?>
                                                <div class="irantheme-post-sidebar-animation" <?php echo ($data_load_attr); ?> data-aos-delay="<?php echo ($aos_delay); ?>" data-aos-duration="<?php echo ($data_load_duration); ?>">
                                                    <div class="irantheme-object irantheme-post-sidebar-posts mb-4" data-object="<?php echo esc_attr($object_post['object_id']); ?>">
                                                        <?php
                                                        // Get alignment class
                                                        $content_alignment_class = Irantheme_Section_Service::get_alignment_class($content_alighment);
                                                        // Set alignment style
                                                        $content_alignment_flex_class = Irantheme_Section_Service::get_flex_alignment_class($content_alighment);

                                                        // Set border class
                                                        $border_class = '';
                                                        if ($border_mode == 'on') {
                                                            $border_class = 'irantheme-post-sidebar-item-border-little';
                                                        }

                                                        // Set transparent class
                                                        $transparent_class = '';
                                                        if ($transparent_mode == 'on') {
                                                            $transparent_class = 'irantheme-post-sidebar-item-transparent';
                                                        }

                                                        // Set shadow class
                                                        $shadow_class = '';
                                                        if ($shadow_mode == 'on') {
                                                            $shadow_class = 'irantheme-post-sidebar-item-shadow-little';
                                                        }

                                                        // Set hover mode class
                                                        $hover_mode_class = 'irantheme-post-sidebar-item-normal-hover';
                                                        if ($hover_mode == 'transform') {
                                                            $hover_mode_class = 'irantheme-post-sidebar-item-transform-hover';
                                                        } elseif ($hover_mode == 'mask') {
                                                            $hover_mode_class = 'irantheme-post-sidebar-item-mask-hover';
                                                        } elseif ($hover_mode == 'zoom-image') {
                                                            $hover_mode_class = 'irantheme-post-sidebar-item-zoom-image-hover';
                                                        } elseif ($hover_mode == 'shadow') {
                                                            $hover_mode_class = 'irantheme-post-sidebar-item-shadow-hover';
                                                        } elseif ($hover_mode == 'zoom-post') {
                                                            $hover_mode_class = 'irantheme-post-sidebar-item-zoom-post-hover';
                                                        }

                                                        // While on wp_query posts
                                                        if ($post_content->have_posts()) :
                                                        ?>
                                                            <?php if ($heading_posts): ?>
                                                                <div class="irantheme-post-sidebar-heading-posts">
                                                                    <h4 class="irantheme-post-sidebar-heading-posts-title"><?php echo esc_html($heading_posts); ?></h4>
                                                                    <a href="<?php echo esc_url_raw($heading_link_posts); ?>" class="irantheme-post-sidebar-heading-posts-link"><?php _e('مشاهده همه', self::$static_textdomain); ?><i class="fa-light fa-angle-left"></i></a>
                                                                </div>
                                                            <?php endif; ?>
                                                            <div class="row">
                                                                <?php
                                                                while ($post_content->have_posts()) :
                                                                    $post_content->the_post();
                                                                    // Get thumb url of post
                                                                    $thumb_url = get_the_post_thumbnail_url();

                                                                    // Get parent id category of post
                                                                    $category = get_the_category();
                                                                    // $category_parent_list = array();
                                                                    $category_parents_id = '';
                                                                    foreach ($category as $cate) {
                                                                        if ($cate->parent) {
                                                                            // From your child category, grab parent ID
                                                                            $parent = $cate->parent;

                                                                            // Load object for parent category
                                                                            $parent_id = get_category($parent);

                                                                            // Grab a category name
                                                                            $parent_id = $parent_id->term_id;
                                                                            $category_parents_id .= strval($parent_id) . ',';
                                                                        } else {
                                                                            $category_parents_id .= strval($cate->term_id) . ',';
                                                                        }
                                                                    }
                                                                ?>
                                                                    <div class="<?php echo esc_attr($col_bootstrap_class); ?> post-holder" data-cate="<?php echo esc_attr($category_parents_id); ?>">
                                                                        <article class="irantheme-post-sidebar-item <?php echo esc_attr($border_class); ?> <?php echo esc_attr($transparent_class); ?> <?php echo esc_attr($shadow_class); ?> <?php echo esc_attr($hover_mode_class); ?>" style="<?php echo esc_attr($border_radius_style); ?><?php echo esc_attr($article_padding_css); ?>">

                                                                            <div class="irantheme-post-sidebar-item-inner-wrapper <?php echo esc_attr($post_horizontal_class); ?>">
                                                                                <?php
                                                                                // Check image thumb visibility mode
                                                                                if ($image_thumb_checkbox == 'on') :
                                                                                    // Check selected thumb for post
                                                                                    if ($thumb_url != '') :
                                                                                ?>
                                                                                        <div class="irantheme-post-sidebar-item-img">
                                                                                            <a href="<?php echo get_permalink(); ?>">
                                                                                                <div class="irantheme-post-sidebar-item-img-wrapper" style="<?php echo esc_attr($post_image_width_height_css); ?>">
                                                                                                    <?php
                                                                                                    echo wp_get_attachment_image(
                                                                                                        get_post_thumbnail_id(),
                                                                                                        'large',
                                                                                                        false,
                                                                                                        [
                                                                                                            'alt' => get_the_title() ?: __('تصویر شاخص', self::$static_textdomain),
                                                                                                            'loading' => 'lazy',
                                                                                                            'style' => esc_attr($image_border_radius_css),
                                                                                                        ]
                                                                                                    );
                                                                                                    ?>
                                                                                                </div>
                                                                                            </a>
                                                                                        </div>
                                                                                <?php endif;
                                                                                endif; ?>
                                                                                <div class="irantheme-post-sidebar-item-content <?php echo esc_attr($content_alignment_class); ?>" style="<?php echo esc_attr($content_padding_css); ?>">
                                                                                    <div class="irantheme-post-sidebar-item-content-details <?php echo esc_attr($content_alignment_flex_class); ?>">
                                                                                        <?php
                                                                                        // Date
                                                                                        if (in_array('date', $post_info_type)) :
                                                                                        ?>
                                                                                            <div class="irantheme-post-sidebar-item-content-details-date">
                                                                                                <?php
                                                                                                echo $post_info_icon == 'on' ? '<i class="fa-light fa-calendar-days"></i>' : '';
                                                                                                echo get_the_date('j F Y'); ?></div>
                                                                                            <!-- Separator -->
                                                                                            <div class="irantheme-post-sidebar-item-content-details-separator <?php echo esc_attr($details_color_class); ?>">.</div>
                                                                                        <?php
                                                                                        endif;
                                                                                        ?>
                                                                                        <?php
                                                                                        // Author
                                                                                        if (in_array('author', $post_info_type)) :
                                                                                        ?>
                                                                                            <div class="irantheme-post-sidebar-item-content-details-author">
                                                                                                <?php
                                                                                                echo $post_info_icon == 'on' ? '<i class="fa-light fa-user"></i>' : '';
                                                                                                echo get_the_author_posts_link(); ?></div>
                                                                                            <!-- Separator -->
                                                                                            <div class="irantheme-post-sidebar-item-content-details-separator <?php echo esc_attr($details_color_class); ?>">.</div>
                                                                                        <?php
                                                                                        endif;
                                                                                        ?>
                                                                                        <?php
                                                                                        // Comment
                                                                                        if (in_array('comment', $post_info_type)) :
                                                                                        ?>
                                                                                            <div class="irantheme-post-sidebar-item-content-details-comment">
                                                                                                <?php
                                                                                                echo $post_info_icon == 'on' ? '<i class="fa-light fa-comments"></i>' : '';
                                                                                                ?>
                                                                                                <em>
                                                                                                    <?php
                                                                                                    echo get_comments_number(); ?></em>
                                                                                                <span>دیدگاه</span>
                                                                                            </div>
                                                                                        <?php
                                                                                        endif;
                                                                                        ?>
                                                                                    </div>
                                                                                    <h3 class="irantheme-post-sidebar-item-content-title"><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h3>

                                                                                    <?php
                                                                                    // Excerpt
                                                                                    if (in_array('excerpt', $post_info_type)) :
                                                                                    ?>
                                                                                        <p class="irantheme-post-sidebar-item-content-description"><?php echo wp_trim_words(strip_shortcodes(get_the_excerpt()), $post_excerpt_number); ?></p>
                                                                                    <?php endif; ?>

                                                                                    <?php
                                                                                    // Category
                                                                                    if (in_array('category', $post_info_type)) :
                                                                                    ?>
                                                                                        <div class="irantheme-post-sidebar-item-content-category <?php echo esc_attr($content_alignment_flex_class); ?>">
                                                                                            <?php
                                                                                            foreach (get_the_category() as $category) {
                                                                                            ?>
                                                                                                <a href="<?php echo esc_url_raw(get_category_link($category->term_id)); ?>"><?php echo esc_html('#' . $category->name); ?></a>
                                                                                            <?php
                                                                                            }
                                                                                            ?>
                                                                                        </div>
                                                                                    <?php
                                                                                    endif;
                                                                                    ?>
                                                                                </div>
                                                                            </div>
                                                                        </article>
                                                                    </div>
                                                                <?php
                                                                endwhile;
                                                                ?>
                                                            </div>
                                                        <?php
                                                        endif;
                                                        ?>
                                                        <?php
                                                        Irantheme_Page_Builder::get_hidden_require_object_buttons($data_page);
                                                        ?>
                                                    </div>
                                                </div>
                                            <?php
                                                $aos_delay += 200;
                                            endforeach; ?>
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
                    <?php endif; ?>
                </div>
                <?php
                Irantheme_Page_Builder::get_hidden_require_section_buttons($data_page);
                ?>
                <!-- </div> -->
                <?php
                // Style scoped
                Irantheme_Section_Utils::display_custom_css_code_style($fields_values);
                ?>
            </section>
<?php
        }
    }
}
