<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;

if (!class_exists('Irantheme_Section_Post_Simple')) {
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
    class Irantheme_Section_Post_Simple extends Irantheme_Section
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
            $heading_content = '<span style="font-size: 0.7em; background-color: #0070FF; color: white;">Latest Posts</span>
<h2 style="font-size: 1.2em; font-weight: bold;">مطالب وبلاگ</h2>';
            $settings = array(
                Irantheme_Section_Utils::heading_title_settings_generator($status_default, 'none', false, 'right', $heading_content, 'off', '', '', '', 'off', 'off', '', '', '', 'off', 'rectangle', 'right', 'off', '', 0, 1, 'medium', 'icon', 'primary', 'fa-light fa-book'),
                Irantheme_Section_Utils::get_post_content_type_fields($status_default, 'post_box_part', 'نوشته ها', 'تنظیمات مربوط به محتوای نوشته ها', 'fa-newspaper', array(), array(), true, 6, 12, 'date,author,category,comment,excerpt'),
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
                            'id'             => 'col_count_radio',
                            'label'            => __('تعداد ستون ها', self::$static_textdomain),
                            'description'    => __('با استفاده از این گزینه شما می توانید تعداد ستون قابل نمایش برای گالری را مشخص کنید', self::$static_textdomain),
                            'type'            => 'radio',
                            'thumbnail_status' => true,
                            'options'        => array(
                                'col-2' => array('دو ستون', 'col-2.png'),
                                'col-3' => array('سه ستون', 'col-3.png'),
                                'col-4' => array('چهار ستون', 'col-4.png'),
                                'col-6' => array('شش ستون', 'col-6.png')
                            ),
                            'default'        => $status_default ? 'col-4' : '',
                            'keywords' => array('box', 'col', 'author', 'تعداد', 'سرویس', 'تعداد در هر ردیف'),
                        ),
                        array(
                            'id'             => 'layout_style_radio',
                            'label'            => __(
                                'سبک نمایش پست ها',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید سبک نمایش پست ها را مشخص نمایید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'thumbnail_status' => true,
                            'options'        => array(
                                'grid' => array('کاشی', 'grid.png'),
                                'masonry' => array('آجری', 'masonry.png'),
                                'carousel' => array('اسلایدر', 'carousel.png'),
                            ),
                            'default'        => $status_default ? 'carousel' : '',
                            'keywords' => array('سبک نمایش پست ها', 'posts layout style'),
                            'id_dependents_on' => array(
                                'carousel' => array('slider_arrow_checkbox,slider_pagination_checkbox'),
                            ),
                            'id_dependents_off' => array(
                                'grid' => array('slider_arrow_checkbox,slider_pagination_checkbox'),
                                'masonry' => array('slider_arrow_checkbox,slider_pagination_checkbox'),
                            ),
                            'base_priority' => 'radio_base',
                            'selector_priority' => 'radio_gallery_style',
                            'z_index' => 0
                        ),
                        array(
                            'id'             => 'slider_arrow_checkbox',
                            'label'            => __(
                                'نمایش فلش تغییر اسلاید',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استغاده از این گزینه می توانید فلش های تغییر اسلاید که برای رفتن به اسلاید قبل و بعد استفاده می شود را فعال یا غیر فعال نمایید.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'on' : '',
                            'keywords' => array('slider', 'arrow', 'slide', 'فلش', 'اسلایدر', 'جهت نما'),
                        ),
                        array(
                            'id'             => 'slider_pagination_checkbox',
                            'label'            => __(
                                'نمایش آیکون صفحه بندی',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استغاده از این گزینه می توانید وضعیت نمایش آیکون صفحه بندی اسلایدر که به شکل دایره های کوچک به تعداد اسلاید های اسلایدر در پایین اسلایدر نمایش داده می شود را کنترل نمایید.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'on' : '',
                            'keywords' => array('slider', 'arrow', 'slide', 'فلش', 'اسلایدر', 'جهت نما'),

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
                            'id'             => 'post_content_wrapper_mode_checkbox',
                            'label'            => __(
                                'نگهدارنده محتوا پست',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت روشن بودن این گزینه محتوای کارت پست شامل: دسته بندی،عنوان، توضیحات و... داخل یک نگهدارنده می روند که باعث ایجاد فاصله درونی از کناره های کارد می شود. در غیر این صورت نگهدارنده حذف شده و محتوا بصورت کامل عرض کارت را شامل می گردد.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'on' : '',
                            'keywords' => array('wrraper', 'نگهدارنده محتوا', 'post'),
                            'id_dependents_on' => array('post_content_wrapper_size_range'),
                            'id_dependents_off' => array(),
                            'base_priority' => 'checkbox_base',
                            'selector_priority' => 'checkbox_post_content_wrapper',
                        ),
                        array(
                            'id'             => 'post_content_wrapper_size_range',
                            'label'            => __(
                                'اندازه نگهدارنده محتوا پست',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه میتوانید اندازه نگهدارنده محتوای کارت پست را تنظیم کنید.(واحد rem می باشد)', self::$static_textdomain),
                            'type'            => 'range',
                            'min' => 0.5,
                            'max' => 3,
                            'step' => 0.1,
                            'default'        => $status_default ? 2 : '',
                            'placeholder'    => __('Placeholder text', self::$static_textdomain),
                            'keywords' => array('اندازه نگهدارنده پست', 'post content wrapper size'),
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
                            'id_dependents_on' => array('image_height_range', 'date_position_radio'),
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
                            'note' => __('در صورت استفاده از سبک آجری ارتفاع تصاویر بر اساس اندازه تصویر تنظیم خواهد شد و مقدار این گزینه اعمال نخواهد شد.', self::$static_textdomain),
                            'type'            => 'range',
                            'min' => 120,
                            'max' => 450,
                            'step' => 1,
                            'default'        => $status_default ? 180 : '',
                            'placeholder'    => __('Placeholder text', self::$static_textdomain),
                            'keywords' => array('communication', 'contact', 'ارتباط با ما',  'ارتباطات', 'اندازه', 'ارتفاع')
                        ),
                        array(
                            'id'             => 'date_position_radio',
                            'label'            => __(
                                'مکان قرارگیری تاریخ انتشار روی تصویر',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید مکان قرارگیری تاریخ انتشار نوشته روی تصویر شاخص را مشخص نمایید.', self::$static_textdomain),
                            'note'    => __('در صورت فعال بودن "تاریخ انتشار" در بخش جزئیات نوشته ها', self::$static_textdomain),
                            'type'            => 'radio',
                            'options'        => array('up-right' => 'بالا - راست', 'up-left' => 'بالا - چپ', 'down-right' => 'پایین - راست', 'down-left' => 'پایین - چپ'),
                            'default'        => $status_default ? 'up-right' : '',
                            'keywords' => array('دکمه', 'استایل', 'گوشه', 'منحنی', 'style', 'button', 'circle', 'rectangle', 'edge')
                        ),
                        array(
                            'id'             => 'date_text_color_select',
                            'label'            => __(
                                'رنگ متن تاریخ انتشار',
                                self::$static_textdomain
                            ),
                            'description'    => __('از طریق این گزینه می توانید رنگ مناسب برای متن تاریخ انتشار را انتخاب کنید.', self::$static_textdomain),
                            'note'    => __('در صورت فعال بودن "تاریخ انتشار" در بخش جزئیات نوشته ها', self::$static_textdomain),
                            'type'            => 'select',
                            'options'        => array('white' => 'سفید', 'dark' => 'مشکی'),
                            'color_preview' => true,
                            'default'        => 'white',
                            'keywords' => array('رنگ متن تاریخ انتشار', 'date text color'),
                        ),
                        array(
                            'id'             => 'post_bg_color_select',
                            'label'            => __(
                                'رنگ پس زمینه پست',
                                self::$static_textdomain
                            ),
                            'description'    => __('برای انتخاب رنگ پس زمینه از لیست، رنگ مورد نظر خود را انتخاب کنید.در صورت انتخاب حالت پیش فرض، رنگ پس زمینه بر اساس رنگ پایه تنظیم می شود.', self::$static_textdomain),
                            'type'            => 'select',
                            'options'        => array('default' => 'پیش فرض', 'transparent' => 'شیشه ای'),
                            'default'        => $status_default ? 'default' : '',
                            'keywords' => array('رنگ پس زمینه', 'رنگ بک گراند', 'background color', 'color'),
                        ),
                        array(
                            'id'             => 'post_details_color_select',
                            'label'            => __(
                                'رنگ جزئیات پست',
                                self::$static_textdomain
                            ),
                            'description'    => __('برای انتخاب رنگ جزئیات پست از لیست رنگ دلخواه خود را انتخاب کنید. این رنگ تاثیر مستقیمی در زیبایی جزئيات دارد.', self::$static_textdomain),
                            'type'            => 'select',
                            'options'        => Irantheme_Color_Static::get_all_colors_options(),
                            'color_preview' => true,
                            'default'        => 'off',
                            'keywords' => array('رنگ جزئيات', 'detail color'),
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
                            'id'             => 'hover_mode_radio',
                            'label'            => __(
                                'سبک واکنش به نشانگر (hover)',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید نوع واکنش رسانه های موجود در گالری به عبور نشانگر از روی آن ها (hover) را مشخص کنید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'options'        => array('normal' => 'ساده', 'shadow' => 'تغییر وضعیت سایه', 'transform' => 'انتقال پست به بالا', 'mask' => 'لایه پوشاننده تصویر', 'zoom-image' => 'بزرگنمایی تصویر', 'zoom-post' => 'بزرگنمایی پست'),
                            'default'        => $status_default ? 'mask' : '',
                            'keywords' => array('دکمه', 'استایل', 'گوشه', 'منحنی', 'style', 'button', 'circle', 'rectangle', 'edge')
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
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        public static function render($fields_values, $data_type, $data_value, $data_page, $data_ajax)
        {
            // Background options ------------------------------------------------------------
            $background_image_data = $fields_values['bg_image'];
            $background_color_data = $fields_values['bg_color'];
            $background_parallax_checkbox = $fields_values['bg_parallax_checkbox'];
            $background_color_css = Irantheme_Section_Utils::get_background_color_css($background_color_data);
            $background_image_css = Irantheme_Section_Utils::get_background_image_css($background_image_data);
            $background_parallax_attr = Irantheme_Section_Utils::get_background_parallax_attr($background_parallax_checkbox, $background_image_data);

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

            // Get section heading --------------------------------------------------------------
            $tinymce_heading = $fields_values['text_editor_heading'];

            // Base color --------------------------------------------------------------
            $base_color = $fields_values['base_color_select'];
            // Set base color class
            $base_color_class = Irantheme_Common_Static::get_base_color_class($base_color);

            // Get col count --------------------------------------------------------------
            $col_count = $fields_values['col_count_radio'];

            // Get layout style --------------------------------------------------------------
            $layout_style = $fields_values['layout_style_radio'];

            // Get post content with sending post-content-type --------------------------------------------------------------
            $post_content = Irantheme_Section_Utils::get_post_content($fields_values);

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
            <!-- ============================= Section post simple ============================= -->
            <section <?php echo esc_attr($section_id_attribute); ?> class="irantheme-section irantheme-post-simple-section irantheme-wrapper-categories-trigger <?php echo esc_attr($skeleton_section_class); ?>" <?php echo esc_attr(Irantheme_Page_Builder::get_attr_data_section($data_type, $data_value, $data_page, $data_ajax)); ?> style="<?php echo esc_attr($section_transform_extra_css); ?><?php echo esc_attr(Irantheme_Section_Service::get_custom_outer_padding_css($fields_values)); ?>">
                <?php
                // Set wrapper (Container) --------------------------------------------------------------
                $container_checkbox = $fields_values['wrapper_mode_checkbox'];
                ?>
                <div class="irantheme-post-simple <?php echo esc_attr($base_color_class); ?> irantheme-background-jarallax" style="<?php echo esc_attr($background_color_css); ?><?php echo esc_attr($background_image_css); ?><?php echo esc_attr(Irantheme_Section_Service::get_custom_inner_padding_css($fields_values)); ?>" <?php echo $background_parallax_attr; ?>>

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
                        <div class="irantheme-post-simple-post-wrapper irantheme-categories-posts" style="<?php echo esc_attr($section_transform_translate_css); ?>">
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
                            <?php
                            endif;
                            // Get categories trigger --------------------------------------------------------------
                            $categories_trigger_checkbox = $fields_values['categories_trigger_checkbox'];
                            if ($categories_trigger_checkbox == 'on') {
                                // Get categories trigger
                                Irantheme_Common_Static::get_categories_trigger('category', '', $base_color_class);
                            }
                            ?>

                            <?php
                            $skeleton_load_class = '';
                            if ($layout_style == 'carousel' && $data_load_checkbox != 'on' && $heading_data_load_checkbox != 'on') {
                                // Skeleton html --------------------------------------------------------------
                                irantheme_get_skeleton_html($col_count, 'card', $base_color_class);
                                $skeleton_load_class = 'irantheme-side-load-skeleton';
                            }
                            ?>

                            <div class="irantheme-post-simple-post-item-holder <?php echo esc_attr($skeleton_load_class); ?>">
                                <?php
                                if ($layout_style == 'grid') {
                                    Irantheme_Common_Static::blog_post_flex('post-simple', $post_content, $fields_values);
                                } elseif ($layout_style == 'masonry') {
                                    Irantheme_Common_Static::blog_post_masonry('post-simple', $post_content, $fields_values, $data_type, $data_value, $data_page, $data_ajax);
                                } elseif ($layout_style == 'carousel') {
                                    Irantheme_Common_Static::blog_post_carousel('post-simple', $post_content, $fields_values, $data_type, $data_value, $data_page, $data_ajax);
                                }
                                ?>
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
                <?php
                // Style scoped
                Irantheme_Section_Utils::display_custom_css_code_style($fields_values);
                ?>
            </section>
<?php
        }
    }
}
