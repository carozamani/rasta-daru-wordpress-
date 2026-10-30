<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;

if (!class_exists('Irantheme_Section_Woocommerce_Category_Products')) {
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
    class Irantheme_Section_Woocommerce_Category_Products extends Irantheme_Section
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
         * @param string,string,string,string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function fields($status_default)
        {
            $heading_content = '';
            $settings = array(
                Irantheme_Section_Utils::heading_title_settings_generator($status_default, 'none', false, false, $heading_content, 'off', '', '', '', 'off', 'off', '', '', '', 'off', 'rectangle', 'right', 'off', '', 0, 1),
                Irantheme_Section_Utils::get_product_content_type_fields($status_default, 'discount', 8, array('once_category', 'count_number')),
                array(
                    'title' => 'ویژگی محصولات',
                    'id' => 'products',
                    'description'            => __('تنظیمات بصری مانند : تصویر زمینه، ارتفاع، رنگ ها و...', self::$static_textdomain),
                    'icon' => 'fa-bag-shopping-plus',
                    'fields' => array(
                        array(
                            'id'             => 'features_checkbox_multi',
                            'label'            => __(
                                'ویژگی های محصول',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید نوع جزئیات قابل نمایش برای هر محصول را مشخص کنید.مانند عنوان، دسته بندی و غیره... ', self::$static_textdomain),
                            'type'            => 'checkbox_multi',
                            'options'        => array('image' => 'تصویر', 'category' => 'دسته بندی', 'title' => 'عنوان',  'excerpt' => 'متن توضیحات', 'rating' => 'امتیاز', 'price' => 'قیمت', 'cart' => 'افزودن به سبد'),
                            'default'        => $status_default ? 'image,category,title,rating,price,cart' : '',
                            'keywords' => array('محصولات', 'محصولات ووکامرس', 'woocommerce products')
                        ),
                        array(
                            'id'             => 'extra_options_checkbox',
                            'label'            => __(
                                'نمایش امکانات اضافی',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید امکانات اضافی از قبیل افزودن به سبد خرید، افزودن به علاقه مندی و مشاهده سریع محصول را بر روی تصویر محصول نمایش دهید.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'on' : '',
                            'keywords' => array('product options', 'ویژگی های اضافی', 'نمایش ویژگی های اضافی', 'افزودن به سبد خرید', 'product add to cart', 'woocommerce product style'),
                            'id_dependents_on' => array('extra_option_on_image_style_radio', 'extra_options_hover_checkbox'),
                            'id_dependents_off' => array(),
                            'base_priority' => 'checkbox_base',
                            'selector_priority' => 'extra_options',
                            'z_index' => 0
                        ),
                        array(
                            'id'             => 'extra_option_on_image_style_radio',
                            'label'            => __('استایل نمایش امکانات اضافی روی تصویر', self::$static_textdomain),
                            'description'    => __('در صورت انتخاب "نمایش روی تصویر" می توانید با استفاده از این گزینه استایل امکانات اضافی را انتخاب کنید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'thumbnail_status' => true,
                            'options'        => array(
                                'vertical-left' => array('', 'product-extra-option-vertical-left.png'),
                                'horizontal-center' => array('', 'product-extra-option-horizontal-center.png'),
                                'horizontal-bottom' => array('', 'product-exrat-option-horizontal-bottom.png'),
                            ),
                            'default'        => $status_default ? 'vertical-left' : '',
                            'keywords' => array('امکانات اضافی', 'امکانات اضافی محصول', 'استایل امکانات اضافی محصول', 'products options', 'product extra options'),
                        ),
                        array(
                            'id'             => 'extra_options_hover_checkbox',
                            'label'            => __(
                                'انیمیشن امکانات اضافی',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت فعال بودن این گزینه و انتخاب "نمایش روی تصویر"، امکانات اضافی روی تصویر هنگام عبور نشانگر از روی آنها همراه با انیمیشن ورود و خروج نمایش داده می شوند.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'on' : '',
                            'keywords' => array('امکانات اضافی', 'امکانات اضافی محصول', 'انیمیشن امکانات اضافی محصول', 'products options', 'product extra options'),
                        ),
                        array(
                            'id'             => 'excerpt_count_number',
                            'label'            => __(
                                'تعداد کلمه متن توضیحات',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید تعداد کلمه های توضیح محصول را مشخص کنید.', self::$static_textdomain),
                            'type'            => 'number',
                            'step' => 1,
                            'default'        => $status_default ? 18 : '',
                            'min' => 4,
                            'max' => 40,
                            'placeholder'    => __('', self::$static_textdomain),
                            'keywords' => array('تعداد کلمه', 'تعداد کلمه محصول', 'اندازه توضیحات محصول', 'count description of products', 'number of excerpt', 'excerpt product'),
                        ),
                        array(
                            'id'             => 'col_count_radio',
                            'label'            => __('تعداد ستون محصولات', self::$static_textdomain),
                            'description'    => __('با استفاده از این گزینه شما می توانید تعداد ستون های قابل نمایش برای محصولات را مشخص کنید', self::$static_textdomain),
                            'type'            => 'radio',
                            'thumbnail_status' => true,
                            'options'        => array(
                                'col-3' => array('سه ستون', 'col-3.png'),
                                'col-4' => array('چهار ستون', 'col-4.png'),
                                'col-5' => array('پنج ستون', 'col-5.png'),
                                'col-6' => array('شش ستون', 'col-6.png')
                            ),
                            'default'        => $status_default ? 'col-5' : '',
                            'keywords' => array('box', 'col', 'author', 'تعداد', 'سرویس', 'تعداد در هر ردیف'),
                        ),
                        array(
                            'id'             => 'discount_checkbox',
                            'label'            => __(
                                'نمایش درصد تخفیف',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید درصد تخفیف را به صورت یک کادر بر روی تصویر محصول مشاهده نمایید.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'on' : '',
                            'keywords' => array('درصد تخفیف محصول', 'تخفیف محصول', 'product discount', 'discount'),
                        ),
                        array(
                            'id'             => 'total_sales_checkbox',
                            'label'            => __(
                                'نمایش تعداد فروش',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید درصد تخفیف را به صورت یک کادر بر روی تصویر محصول مشاهده نمایید.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'off' : '',
                            'keywords' => array('نمایش تعداد فروش', 'تعداد فروش محصول', 'product total sales', 'count of sales', 'number of sales'),
                        ),
                        array(
                            'id'             => 'discount_schedule_checkbox',
                            'label'            => __(
                                'نمایش شمارنده تخفیف',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید شمارنده تخفیفی که بر روی محصول اعمال کرده اید را نمایش یا مخفی نمایید.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'off' : '',
                            'keywords' => array('نمایش شمارنده تخفیف', 'number of sales'),
                        ),
                        array(
                            'id'             => 'color_attribute_checkbox',
                            'label'            => __(
                                'نمایش رنگ محصول',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید رنگ های موجود برای هر محصول را مشاهده نمایید.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'on' : '',
                            'keywords' => array('نمایش رنگ محصول', 'color of product'),
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
                            'keywords' => array('رنگ جزئیات', 'detail colors', 'جزئیات', 'کلید', 'رنگ'),
                        ),
                        array(
                            'id'             => 'details_color_select2',
                            'label'            => __(
                                'رنگ جزئيات دوم',
                                self::$static_textdomain
                            ),
                            'description'    => __('برای انتخاب رنگ جزئيات دوم بخش از لیست رنگ دلخواه خود را انتخاب کنید. این رنگ تاثیر مستقیمی در زیبایی جزئيات دارد.', self::$static_textdomain),
                            'type'            => 'select',
                            'options'        => Irantheme_Color_Static::get_all_colors_options(),
                            'color_preview' => true,
                            'default'        => $status_default ? 'secondary' : '',
                            'keywords' => array('رنگ جزئیات', 'detail colors', 'جزئیات', 'کلید', 'رنگ'),
                        ),
                        array(
                            'id'             => 'hover_mode_radio',
                            'label'            => __(
                                'سبک واکنش به محصول (hover)',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید نوع واکنش به عبور نشانگر از روی محصولات (hover) را مشخص کنید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'options'        => array('normal' => 'ساده', 'shadow' => 'سایه', 'scale' => 'بزرگنمایی'),
                            'default'        => $status_default ? 'normal' : '',
                            'keywords' => array('واکنش نشانگر محصولات', 'استایل محصولات', 'ویژگی های محصولات', 'product hover', 'woocommerce product style', 'product style', 'hover product', 'hover', 'هاور')
                        ),
                        array(
                            'id'             => 'image_hover_mode_radio',
                            'label'            => __(
                                'سبک واکنش به تصویر (hover)',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید نوع واکنش به عبور نشانگر از روی تصویر محصول (hover) را مشخص کنید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'options'        => array('normal' => 'ساده', 'shine' => 'رهگذر', 'flashing' => 'فلش', 'circle' => 'گرد'),
                            'default'        => $status_default ? 'circle' : '',
                            'keywords' => array('واکنش نشانگر محصولات', 'استایل محصولات', 'ویژگی های محصولات', 'product hover', 'woocommerce product style', 'product style', 'hover product', 'hover', 'هاور')
                        ),
                        array(
                            'id'             => 'image_changer_checkbox',
                            'label'            => __(
                                'تغییر تصویر در زمان هاور',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت تمایل به تغییر تصویر با تصاویر گالری در زمان واکنش ماوس (Hover) می توانید از این گزینه استفاده کنید.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'on' : '',
                            'keywords' => array('hover product image', 'واکنش ماوس', 'هاور محصول', 'استایل محصول', 'product style', 'woocommerce product style'),
                        ),
                        array(
                            'id'             => 'transparent_checkbox',
                            'label'            => __(
                                'پس زمینه شیشه ای محصول',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت فعال سازی این گزینه پس زمینه محصول به صورت شیشه ای و بدون رنگ نمایش داده خواهد شد.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'off' : '',
                            'keywords' => array('پس زمینه شیشه ای محصول', 'product transparent'),
                        ),
                        array(
                            'id'             => 'border_checkbox',
                            'label'            => __(
                                'خط دور محصول',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید وضعیت نمایش یا عدم نمایش خط دور هر نوشته (بوردر) را مشخص نمایید. در صوررت فعال نمودن این گزینه (محصول) ها به شکل باکسی در می آیند.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'on' : '',
                            'keywords' => array('بوردر محصول', 'خط دور محصول', 'prodcut border', 'border style product'),
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
                            'id'             => 'padding_status_checkbox',
                            'label'            => __(
                                'حاشیه دور پست',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید حاشیه ای اضافی به دور هر محصول اعمال نمایید.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'on' : '',
                            'keywords' => array('حاشیه دور پست', 'product padding'),
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
                Irantheme_Section_Utils::slider_fields_generator($status_default, array('arrow', 'pagination', 'autoplay', 'delay', 'direction'), 'on', 'off', 'on'),
                // Get layout scale fields
                Irantheme_Section_Utils::layout_scale_fields_generator($status_default, array('radius', 'inner_padding', 'outer_padding'), 'on', 2, 2, 'off', 'off', 0, 0, 1, 0, 0, 0, 0),
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
         * Render elements of section
         * @param string,string,string,string,mixed
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function render($fields_values, $data_type, $data_value, $data_page, $data_ajax)
        {
            if (is_woocommerce_activated()) :
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

                // Get border radius --------------------------------------------------------------
                $border_radius_range = $fields_values['border_radius_range'];
                $border_radius_style = Irantheme_Section_Utils::get_border_radius_css($border_radius_range);

                // Base color --------------------------------------------------------------
                $base_color = $fields_values['base_color_select'];
                // Set base color class
                $base_color_class = Irantheme_Common_Static::get_base_color_class($base_color);

                // Get products post query --------------------------------------------------------------
                $products_post = Irantheme_Section_Utils::get_product_content($fields_values, 'once_category');

                // Details color --------------------------------------------------------------
                $details_color_select = $fields_values['details_color_select'];
                $details_color_select2 = $fields_values['details_color_select2'];
                $details_color_class = '';
                if ($details_color_select != 'off') {
                    $details_color_class = Irantheme_Color_Static::get_class_color_mode('bg', null, Irantheme_Color_Static::get_hex_color($details_color_select));
                }

                // Section transform to up and down --------------------------------------------------------------
                $section_transfrom_values = Irantheme_Section_Utils::get_transform_values_arrays($data_page, $fields_values, true, false, false);
                $section_transform_extra_css = $section_transfrom_values['overflow'];
                $section_transform_translate_css = $section_transfrom_values['css'];

                // Count columns of products --------------------------------------------------------------
                $col_count_products = $fields_values['col_count_radio'];

                // Get product content alignment --------------------------------------------------------------
                $post_content_align = $fields_values['align_content_radio'];

                // Get product features --------------------------------------------------------------
                $product_features = $fields_values['features_checkbox_multi'];
                $product_features = explode(',', $product_features);

                // Get product count of word excerpt --------------------------------------------------------------
                $product_excerpt_count = $fields_values['excerpt_count_number'];

                // Get product count of word excerpt --------------------------------------------------------------
                $product_hover_mode = $fields_values['hover_mode_radio'];

                $product_hover_mode_class = '';
                if ($product_hover_mode == 'shadow') {
                    $product_hover_mode_class = 'irantheme-woocommerce-products-hover-shadow';
                } elseif ($product_hover_mode == 'scale') {
                    $product_hover_mode_class = 'irantheme-woocommerce-products-hover-scale';
                }

                // Get image switchable thumbnail with gallery image --------------------------------------------------------------
                $product_image_hover_changer = $fields_values['image_changer_checkbox'];

                $product_image_hover_changer_class = '';
                if ($product_image_hover_changer == 'on') {
                    $product_image_hover_changer_class = 'irantheme-woocommerce-products-item-image-changer';
                }

                // Get product options checkbox --------------------------------------------------------------
                $product_border_checkbox = $fields_values['border_checkbox'];

                $product_border_class = '';
                if ($product_border_checkbox == 'on') {
                    $product_border_class = 'irantheme-woocommerce-products-item-border';
                }

                // Get product discount schedule checkbox --------------------------------------------------------------
                $product_discount_schedule_checkbox = $fields_values['discount_schedule_checkbox'];

                // Get product discount checkbox --------------------------------------------------------------
                $product_discount_checkbox = $fields_values['discount_checkbox'];

                // Get product total sales checkbox --------------------------------------------------------------
                $product_total_sales_checkbox = $fields_values['total_sales_checkbox'];

                // Get product color attribute checkbox --------------------------------------------------------------
                $product_color_attribute_checkbox = $fields_values['color_attribute_checkbox'];

                // Get product options checkbox --------------------------------------------------------------
                $product_shadow_checkbox = $fields_values['shadow_checkbox'];

                $product_shadow_class = '';
                if ($product_shadow_checkbox == 'on') {
                    $product_shadow_class = 'irantheme-woocommerce-products-item-shadow';
                }

                // Get product options checkbox --------------------------------------------------------------
                $product_padding_checkbox = $fields_values['padding_status_checkbox'];

                $product_padding_class = '';
                if ($product_padding_checkbox == 'on') {
                    $product_padding_class = 'irantheme-woocommerce-products-item-padding';
                }

                // Get product options checkbox --------------------------------------------------------------
                $product_transparent_checkbox = $fields_values['transparent_checkbox'];

                $product_transparent_class = '';
                if ($product_transparent_checkbox == 'on') {
                    $product_transparent_class = 'irantheme-woocommerce-products-item-transparent';
                }

                // Get product options checkbox --------------------------------------------------------------
                $product_extra_options_checkbox = $fields_values['extra_options_checkbox'];
                $product_extra_options_style_radio = $fields_values['extra_option_on_image_style_radio'];
                $extra_options_hover_checkbox = $fields_values['extra_options_hover_checkbox'];
                $product_image_options_class = '';
                $product_image_extra_options_style_class = '';
                if ($product_extra_options_checkbox == 'on') {
                    $product_image_options_class = 'active';
                    switch ($product_extra_options_style_radio) {
                        case 'vertical-left':
                            $product_image_extra_options_style_class = 'irantheme-extra-option-vertical-left';
                            break;
                        case 'horizontal-center':
                            $product_image_extra_options_style_class = 'irantheme-extra-option-horizontal-center';
                            break;
                        case 'horizontal-bottom':
                            $product_image_extra_options_style_class = 'irantheme-extra-option-horizontal-bottom';
                            break;
                    }
                    if ($extra_options_hover_checkbox == 'on') {
                        $product_image_extra_options_style_class .= ' hover-active';
                    }
                }

                // Get product image hover mode --------------------------------------------------------------
                $product_image_hover_mode = $fields_values['image_hover_mode_radio'];

                $product_image_hover_mode_class = '';
                if ($product_image_hover_mode == 'flashing') {
                    $product_image_hover_mode_class = 'irantheme-image-hover-flashing';
                } elseif ($product_image_hover_mode == 'shine') {
                    $product_image_hover_mode_class = 'irantheme-image-hover-shine';
                } elseif ($product_image_hover_mode == 'circle') {
                    $product_image_hover_mode_class = 'irantheme-image-hover-circle';
                }

                // If slider is true, assign new swiper classes to wrapper, row and item
                $slider_mode_wrapper_class = 'swiper irantheme-woocommerce-products-post-swiper';
                $slider_mode_row  = 'swiper-wrapper';
                $slider_mode_column = 'swiper-slide';

                // Set slider direction --------------------------------------------------------------
                $slider_direction_radio = $fields_values['slider_direction_radio'];
                $slider_direction_style = '';
                $direction_default_style = '';
                if ($slider_direction_radio == 'ltr') {
                    $slider_direction_style = 'direction:ltr;';
                    $direction_default_style = 'direction:rtl;';
                }
            endif;

            // Get section id --------------------------------------------------------------
            $section_id = $fields_values['element_id'];
            $section_id_attribute = '';
            if ($section_id) {
                $section_id_attribute = sprintf('id=%s', $section_id);
            }
?>
            <!-- ============================= Section Woocommerce category products ============================= -->
            <section <?php echo esc_attr($section_id_attribute); ?> class="irantheme-section irantheme-woocommerce-category-products-section irantheme-woocommerce-products-section <?php echo esc_attr($skeleton_section_class); ?>" <?php echo esc_attr(Irantheme_Page_Builder::get_attr_data_section($data_type, $data_value, $data_page, $data_ajax)); ?> style="<?php echo esc_attr($section_transform_extra_css); ?><?php echo esc_attr(Irantheme_Section_Service::get_custom_outer_padding_css($fields_values)); ?>">
                <?php
                if (is_woocommerce_activated()) :
                    // Set wrapper (Container) --------------------------------------------------------------
                    $container_checkbox = $fields_values['wrapper_mode_checkbox'];
                ?>
                    <div class="irantheme-woocommerce-products <?php echo esc_attr($base_color_class); ?> irantheme-background-jarallax" style="<?php echo esc_attr($background_color_css); ?><?php echo esc_attr($background_image_css); ?><?php echo esc_attr(Irantheme_Section_Service::get_custom_inner_padding_css($fields_values)); ?><?php echo esc_attr($section_transform_translate_css); ?>" <?php echo $background_parallax_attr; ?>>

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
                        if ($container_checkbox == 'off') :
                        ?>
                            <div class="irantheme-container-inner">
                            <?php endif;
                            ?>

                            <?php
                            $skeleton_load_class = '';
                            if ($data_load_checkbox != 'on' && $heading_data_load_checkbox != 'on') {
                                // Skeleton html --------------------------------------------------------------
                                irantheme_get_skeleton_html($col_count_products, 'card', $base_color_class);
                                $skeleton_load_class = 'irantheme-side-load-skeleton';
                            }
                            ?>

                            <!-- News letter post -->
                            <div class="irantheme-woocommerce-products-wrapper <?php echo esc_attr($skeleton_load_class); ?>">
                                <?php
                                // Get section heading --------------------------------------------------------------
                                $tinymce_heading = $fields_values['text_editor_heading'];
                                if ($tinymce_heading != '' && $heading_data_load_checkbox == 'on'):
                                ?>
                                    <div class="irantheme-tinymce-heading-aos-holder" <?php echo ($heading_data_load_attr); ?> data-aos-delay="<?php echo ($heading_aos_delay); ?>" data-aos-duration="<?php echo ($heading_data_load_duration); ?>">
                                    <?php
                                endif;
                                // Get showcase content --------------------------------------------------------------
                                Irantheme_Common_Static::tinymce_heading($fields_values, '', true, true, '');
                                if ($tinymce_heading != '' && $heading_data_load_checkbox == 'on'):
                                    ?>
                                    </div>
                                <?php
                                endif;
                                ?>

                                <div class="irantheme-woocommerce-product-swiper <?php echo esc_attr($slider_mode_wrapper_class); ?>" style="<?php echo esc_attr($slider_direction_style); ?>" <?php echo (Irantheme_Section_Service::get_slider_data_column($col_count_products)); ?> <?php echo esc_attr(Irantheme_Section_Utils::get_auto_slider_data_attribute($fields_values, true)); ?> <?php echo esc_attr(Irantheme_Section_Utils::get_auto_slider_delay_data_attribute($fields_values)); ?>>
                                    <div class="irantheme-woocommerce-slider-arrow-btn">
                                        <?php
                                        // Slider pagination option
                                        $slider_pagination_checkbox = $fields_values['slider_pagination_checkbox'];
                                        // Slider arrow option
                                        $slider_arrow_checkbox = $fields_values['slider_arrow_checkbox'];
                                        Irantheme_Section_Utils::get_slider_arrow_pagination('on', $slider_arrow_checkbox, $slider_pagination_checkbox, $base_color_class, $border_radius_range, 'bottom', 'center');
                                        ?>
                                    </div>

                                    <!-- Row columns -->
                                    <div class="<?php echo esc_attr($slider_mode_row); ?>">
                                        <?php
                                        $swiper_transfer_class = 'swiper-slide d-flex flex-column justify-content-center';

                                        // Get the category ID from your fields
                                        $category_id = isset($fields_values['product_once_category_selection']) ? $fields_values['product_once_category_selection'] : 0;

                                        // Check if we have a valid category ID
                                        if ($category_id) {
                                            // Get the category term object
                                            $category = get_term($category_id, 'product_cat');

                                            // Verify the category exists and there are no errors
                                            if ($category && !is_wp_error($category)) {
                                                // Get category link - with error checking
                                                $category_link = get_term_link($category_id, 'product_cat');
                                                if (is_wp_error($category_link)) {
                                                    // Fallback to shop page with category parameter
                                                    $shop_url = wc_get_page_permalink('shop');
                                                    $category_link = add_query_arg('product_cat', $category->slug, $shop_url);
                                                }

                                                // Get category image
                                                $thumbnail_id = get_term_meta($category_id, 'thumbnail_id', true);

                                                // Sanitize category data
                                                $category_title = isset($category->name) ? sanitize_text_field($category->name) : '';
                                                $category_description = isset($category->description) ? sanitize_textarea_field($category->description) : '';
                                        ?>
                                                <div class="irantheme-woocommerce-products-col irantheme-heading-desktop-mode irantheme-woocommerce-category-products-cat <?php echo esc_attr($swiper_transfer_class); ?>">
                                                    <div class="irantheme-woocommerce-category-products-cat-item <?php echo esc_attr($details_color_class); ?>" style="<?php echo esc_attr($border_radius_style); ?>">
                                                        <?php if ($category_link): ?>
                                                            <a href="<?php echo esc_url($category_link); ?>" class="irantheme-woocommerce-category-products-cat-item-link" target="_blank"><?php esc_html_e('مشاهده دسته بندی'); ?><i class="fa-light fa-angle-left"></i></a>
                                                        <?php endif; ?>
                                                        <?php if ($thumbnail_id) : ?>
                                                            <?php
                                                            echo wp_get_attachment_image(
                                                                $thumbnail_id,
                                                                'medium',
                                                                false,
                                                                [
                                                                    'class' => 'irantheme-woocommerce-category-products-cat-item-image',
                                                                    'alt' => esc_attr($category_title ?: __('تصویر دسته‌بندی', self::$static_textdomain)),
                                                                    'loading' => 'lazy',
                                                                    'style' => esc_attr($border_radius_style),
                                                                ]
                                                            );
                                                            ?>
                                                        <?php endif; ?>
                                                        <?php if ($category_title) : ?>
                                                            <h3 class="irantheme-woocommerce-category-products-cat-item-heading"><?php echo esc_html($category_title); ?></h3>
                                                        <?php endif; ?>
                                                        <?php if ($category_description) : ?>
                                                            <p class="irantheme-woocommerce-category-products-cat-item-description"><?php echo esc_html($category_description); ?></p>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                        <?php
                                            } else {
                                                // Debug output if category not found
                                                // error_log('Category not found or error: ' . print_r($category, true));
                                            }
                                        } else {
                                            // Debug output if no category ID
                                            // error_log('No category ID provided');
                                        }
                                        ?>
                                        <?php
                                        if ($products_post):
                                            while ($products_post->have_posts()) :
                                                $products_post->the_post();
                                                global $product;

                                                // Get product item
                                                Irantheme_Section_Woocommerce::get_product_item($product, $product_features, $base_color, $details_color_select, $details_color_select2, $product_discount_checkbox, $product_total_sales_checkbox, $product_discount_schedule_checkbox, $product_color_attribute_checkbox, $product_image_options_class, $product_image_extra_options_style_class, $product_border_class, $product_image_hover_changer_class, $product_image_hover_mode_class, $product_hover_mode_class, $post_content_align, $product_shadow_class, $slider_mode_column, $product_excerpt_count, $product_extra_options_checkbox, $aos_delay, $data_load_attr, $data_load_duration, $border_radius_range, $product_padding_class, $product_transparent_class, $direction_default_style);

                                                // Increase aos delay for next item
                                                $aos_delay += 200;
                                            endwhile;

                                            if (!$products_post->have_posts()) {
                                                // Empty status
                                                irantheme_display_empty_status('cart-xmark', 'محصولی جهت نمایش یافت نشد.', 'با رفتن به محیط ادمین و تب "محصولات" -> "افزودن جدید" محصولات مورد نظر خود را بسازید.');
                                            }
                                        endif;
                                        ?>
                                    </div>
                                    <?php
                                    if ($container_checkbox != 'on') :
                                    ?>
                                </div>
                            </div>
                        <?php endif;
                        ?>
                            </div>
                        <?php
                    else :
                        // Empty status
                        irantheme_display_empty_status('empty-set', 'متاسفانه افزونه ووکامرس فعال یا نصب نمی باشد.', 'با رفتن به محیط ادمین و تب "افزونه ها" ووکامرس را نصب یا فعال نمایید.');
                    endif;
                        ?>
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
