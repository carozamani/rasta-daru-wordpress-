<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;

if (!class_exists('Irantheme_Section_Woocommerce_Slider')) {
    /**
     * A class for create section
     *
     * @package Wordpress
     * @subpackage irantheme
     * @author iranianclever
     * @since v1.0
     * @see v1.0
     */
    class Irantheme_Section_Woocommerce_Slider extends Irantheme_Section
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
            $settings = array(
                Irantheme_Section_Utils::get_product_content_type_fields($status_default, 'discount', 4),
                array(
                    'title' => 'محتوای کنار',
                    'id' => 'side',
                    'description'            => __('تنظیم محتوای کنار بلوک که شامل نوشته و تصویر می باشد.', self::$static_textdomain),
                    'icon' => 'fa-sidebar-flip',
                    'fields' => array(
                        array(
                            'id'             => 'base_color_side_select',
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
                    )
                ),
                array(
                    'title' => 'تنظیمات بصری',
                    'id' => 'layout',
                    'description'            => __('تنظیمات بصری ویترین مانند : تصویر زمینه، ارتفاع ویترین، رنگ ها و...', self::$static_textdomain),
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
                            'default'        => $status_default ? Irantheme_Section_Default::get_default_ids_objects(4, 'woocommerce_slider', $status_default) : '',
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
                            'id'             => 'content_position',
                            'label'            => __(
                                'چیدمان محتوا',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید چیدمان محتوا خود را مشخص کنید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'thumbnail_status' => true,
                            'options'        => array('right' => array('اسلایدر راست', 'align-section-right.png'), 'left' => array('اسلایدر چپ', 'align-section-left.png')),
                            'default'        => $status_default ? 'right' : '',
                            'keywords' => array('چیدمان محتوا', 'elements position')
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
                // Get slider fields
                Irantheme_Section_Utils::slider_fields_generator($status_default, array('arrow', 'pagination', 'autoplay', 'delay', 'loop', 'effect'), 'on', 'off', 'on'),
                // Get background fields
                Irantheme_Section_Utils::background_fields_generator($status_default, array('image', 'color', 'color2', 'mask', 'parallax'), '', 'off', true, 'off', 'dark', 0.6, 'off', 'off', '', '', '', 'sub'),
                // Get layout scale fields
                Irantheme_Section_Utils::layout_scale_fields_generator($status_default, array('fullscreen', 'radius', 'inner_padding', 'outer_padding'), 'off', 0, 0, 'off', 'on', 2, 2, 1),
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
            $images_default = '';
            $tinymce_default = '';


            // Check status is deafult or no
            if (is_numeric($index)) {
                // List of tinymce content
                $tinymce_content = array(
                    '<h2 style="font-size:1.4em;font-weight:bold;">قدرت بی‌نهایت در دستان تو</h2><p style="font-size:1em;">سرعت، هوش و خلاقیت بی‌پایان</p>',
                    '<h2 style="font-size:1.4em;font-weight:bold;">تجربه ناب یک قهرمان حرفه‌ای</h2><p style="font-size:1em;">بازی کن، لذت ببر، قهرمان شو</p>',
                    '<h2 style="font-size:1.4em;font-weight:bold;">راحتی و استایل در هر قدم</h2><p style="font-size:1em;">هر وسیله، جای مخصوص خودش</p>',
                    '<h2 style="font-size:1.4em;font-weight:bold;">هر پروژه، یک شاهکار جدید</h2><p style="font-size:1em;">بساز، تعمیر کن، خلاقیت را آزاد کن</p>'
                );

                // List of authors images
                $images = array(
                    'https://irantheme.com/wp-content/uploads/2026/02/1bt6gol5.webp',
                    'https://irantheme.com/wp-content/uploads/2026/02/78ybgnl7.webp',
                    'https://irantheme.com/wp-content/uploads/2026/02/vvhubk41.webp',
                    'https://irantheme.com/wp-content/uploads/2026/02/38xej1gu.webp',
                );

                // Set base color
                $base_color = array(
                    'white',
                    'white',
                    'white',
                    'white',
                );

                // Default content
                $tinymce_default = $tinymce_content[$index];
                // Default image
                $images_default = $status_default === 'media' ? Irantheme_Common_Static::get_file_url_media_library($images[$index]) : '';
                // Default base color
                $base_color_default = $base_color[$index];
            }


            // Custom fields
            $object_fields = array(
                Irantheme_Section_Utils::heading_title_settings_generator(true, false, false, false, $tinymce_default, 'on', 'خرید', '#', 'fa-light fa-arrow-left', 'transparent', 'off', 'مشاوره خرید', '#', 'fa-light fa-phone', 'transparent', 'circle', 'right', 'off', '', 2, 2, 'medium'),
                array(
                    'title' => 'تنظیم محتوا',
                    'id' => 'edit_object',
                    'description'            => __('تنظیم مقادیر عنصر', self::$static_textdomain),
                    'icon' => 'fa-folder-grid',
                    'fields' => array(
                        array(
                            'id'             => 'banner_no_text_checkbox',
                            'label'            => __(
                                'تصویر بنر خالص',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورتی که نمی خواهید هیچ گونه نوشته ای بر روی تصویر بنر شما باشد این گزینه را فعال نمایید.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => 'off',
                        ),
                        array(
                            'id'             => 'slider_link',
                            'label'            => __(
                                'آدرس لینک',
                                self::$static_textdomain

                            ),
                            'description'    => __('در صورتی که نیازمند تنظیم لینک برروی کل تصویر بنر هستید ووردی لینک را پر نمایید.'),
                            'type'            => 'text',
                            'default'        => '',
                            'placeholder'    => __('برای مثال: https://example.com/mylink', self::$static_textdomain),
                        ),
                    )
                ),
                array(
                    'title' => 'تنظیمات بصری',
                    'id' => 'edit_layout_object',
                    'description'            => __('تنظیمات بصری مانند : تصویر زمینه، ارتفاع، رنگ ها و...', self::$static_textdomain),
                    'icon' => 'fa-pen-to-square',
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
                        ),
                    )
                ),
                // Get background fields
                Irantheme_Section_Utils::background_fields_generator(true, array('image', 'color', 'mask'), $images_default, 'off', true, 'off', 'dark', 0.1, 'on', 'off', '', '', ''),
                array(
                    'title' => 'امکانات',
                    'id' => 'additional',
                    'description'            => __('فعال یا غیر فعال کردن امکانات ویترین مانند: شبکه های اجتمایی، دکمه پیمایش به پایین و ...', self::$static_textdomain),
                    'icon' => 'fa-puzzle-piece',
                    'fields' => array(
                        array(
                            'id'             => 'scroll_down_checkbox',
                            'label'            => __(
                                'دکمه پیمایش به پایین',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت فعال بودن این گزینه دکمه پیمایش به پایین فعال شده که کاربر با کلیک روی این دکمه به راحتی به قسمت بعدی هدایت می شود.
								', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => 'off',
                        ),
                        array(
                            'id'             => 'social_media_checkbox',
                            'label'            => __(
                                'نمایش شبکه های اجتماعی',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید نمایش یا عدم نمایش شناسه کاربری شبکه های اجتماعی در ویترین را تعیین نمایید.
								</br>
								*جهت ثبت شناسه کاربری خود در هر یک از شبکه های اجتماعی به بخش "دیگر تنظیمات => شبکه های اجتماعی" مراجعه نمایید.
								', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => 'off',
                        ),
                        array(
                            'id'             => 'custom_text_link_checkbox',
                            'label'            => __(
                                'نمایش لینک دستی',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت فعال بودن این گزینه شما می توانید یک لینک کوتاه دستی در سمت راست پایین ویترین اضافه نمایید. این لینک حالت مدرن و طراحی شده دارد.
								', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => 'off',
                            'id_dependents_on' => array('custom_text_link_heading', 'custom_text_link_url'),
                            'base_priority' => 'checkbox_base',
                            'selector_priority' => 'checkbox_custom_text_link',
                            'z_index' => 0
                        ),
                        array(
                            'id'             => 'custom_text_link_heading',
                            'label'            => __(
                                'متن لینک دستی',
                                self::$static_textdomain

                            ),
                            'description'    => __('عنوان لینک دستی خود را وارد کنید.'),
                            'type'            => 'text',
                            'default'        => 'جزئیات بیشتر',
                            'placeholder'    => __('عنوان خود را وارد کنید', self::$static_textdomain),
                        ),
                        array(
                            'id'             => 'custom_text_link_url',
                            'label'            => __(
                                'آدرس لینک دستی',
                                self::$static_textdomain

                            ),
                            'description'    => __('آدرس لینک دستی خود را وارد کنید.'),
                            'type'            => 'text',
                            'default'        => '#',
                            'placeholder'    => __('برای مثال: https://www.mywebsite.com/page', self::$static_textdomain),
                        ),
                    ),
                ),
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
            $background_color_data2 = $fields_values['bg_color2'];
            $background_parallax_checkbox = $fields_values['bg_parallax_checkbox'];
            $background_color_css = Irantheme_Section_Utils::get_background_color_css($background_color_data);
            $background_color_css2 = Irantheme_Color_Static::get_class_color_mode('bg', null, Irantheme_Color_Static::get_hex_color($background_color_data2));
            $background_image_css = Irantheme_Section_Utils::get_background_image_css($background_image_data);
            $background_parallax_attr = Irantheme_Section_Utils::get_background_parallax_attr($background_parallax_checkbox, $background_image_data);

            // Get border radius --------------------------------------------------------------
            $border_radius_range = $fields_values['border_radius_range'];
            $border_radius_style = Irantheme_Section_Utils::get_border_radius_css($border_radius_range);

            // Get section id --------------------------------------------------------------
            $section_id = $fields_values['element_id'];
            $section_id_attribute = '';
            if ($section_id) {
                $section_id_attribute = sprintf('id=%s', $section_id);
            }

            // Base color --------------------------------------------------------------
            $base_color = $fields_values['base_color_select'];
            // Set base color class
            $base_color_class = Irantheme_Common_Static::get_base_color_class($base_color);

            // Base color --------------------------------------------------------------
            $base_color_side = $fields_values['base_color_side_select'];
            // Set base color class
            $base_color_side_class = Irantheme_Common_Static::get_base_color_class($base_color_side);

            // Details color --------------------------------------------------------------
            $details_color_select = $fields_values['details_color_select'];
            $details_color_class = '';
            if ($details_color_select != 'off') {
                $details_color_class = Irantheme_Color_Static::get_class_color_mode('color', null, Irantheme_Color_Static::get_hex_color($details_color_select));
            }

            // Get slider arrow checkbox
            $auto_slider_checkbox = $fields_values['slider_autoplay_checkbox'];
            // Tag holder
            $slider_attr_holder = 'data-slider-auto=0';
            // Check data load checkbox
            if ($auto_slider_checkbox == 'on') {
                $slider_attr_holder = 'data-slider-auto=1';
            }

            // Get sidebar position
            $content_position = $fields_values['content_position'];

            $row_class = '';
            if ($content_position == 'left') {
                $row_class = 'flex-row-reverse';
            } else if ($content_position == 'right') {
                $row_class = 'flex-row';
            }

            // Get products post query --------------------------------------------------------------
            $products_post = Irantheme_Section_Utils::get_product_content($fields_values);
?>
            <!-- Swiper -->
            <section <?php echo esc_attr($section_id_attribute); ?> class="irantheme-section irantheme-woocommerce-slider-section irantheme-skeleton-section" <?php echo esc_attr(Irantheme_Page_Builder::get_attr_data_section($data_type, $data_value, $data_page, $data_ajax)); ?>>
                <div class="irantheme-woocommerce-slider <?php echo esc_attr($base_color_class); ?> irantheme-background-jarallax" style="<?php echo esc_attr(Irantheme_Section_Service::get_custom_outer_padding_css($fields_values)); ?><?php echo esc_attr($background_color_css); ?><?php echo esc_attr($background_color_css); ?><?php echo esc_attr($background_image_css); ?>" <?php echo $background_parallax_attr; ?>>
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
                    // Set wrapper (Container) --------------------------------------------------------------
                    $container_checkbox = $fields_values['wrapper_mode_checkbox'];
                    if ($container_checkbox != 'on') :
                    ?>
                        <div class="irantheme-container-inner">
                        <?php endif; ?>
                        <div class="row <?php echo esc_attr($row_class); ?>">
                            <div class="col-xxl-9 col-xl-8 col-lg-12 col-md-12 col-sm-12 col-12">

                                <?php
                                // Skeleton html --------------------------------------------------------------
                                irantheme_get_skeleton_html('col-8', 'slider', $base_color_class);
                                ?>

                                <div id="irantheme-swiper-section-woocommerce-slider-<?php echo $data_ajax; ?>" class="swiper irantheme-swiper-section-woocommerce-slider <?php echo esc_attr(Irantheme_Section_Service::get_fullscreen_height_class($fields_values)); ?> irantheme-side-load-skeleton">
                                    <div class="irantheme-object-group swiper-wrapper irantheme-showcase-simple-section" data-json="<?php echo esc_attr('objects_json'); ?>">
                                        <?php foreach ($json_post_array as $object_post) :
                                            // Background options ------------------------------------------------------------
                                            $background_image_data = $object_post['fields']['bg_image'];
                                            $background_color_data = $object_post['fields']['bg_color'];
                                            $background_color_css = Irantheme_Section_Utils::get_background_color_css($background_color_data);
                                            // Get slider base color
                                            $base_color = $object_post['fields']['base_color_select'];
                                            $slider_link = $object_post['fields']['slider_link'];
                                            $banner_no_text_checkbox = $object_post['fields']['banner_no_text_checkbox'];
                                        ?>
                                            <div class="irantheme-object irantheme-showcase-simple <?php echo esc_attr($base_color); ?> swiper-slide" style="<?php echo esc_attr($background_color_css); ?><?php echo esc_attr(Irantheme_Section_Service::get_custom_inner_padding_css($fields_values)); ?><?php echo esc_attr($border_radius_style); ?>" data-object="<?php echo esc_attr($object_post['object_id']); ?>">

                                                <!-- Holder thumbnail -->
                                                <div class="irantheme-swiper-section-slider-simple-image">
                                                    <?php if ($slider_link): ?>
                                                        <a href="<?php echo esc_url($slider_link); ?>" class="irantheme-swiper-section-slider-simple-image-link">
                                                        <?php endif; ?>
                                                        <?php
                                                        echo wp_get_attachment_image(
                                                            $background_image_data,
                                                            'large',
                                                            false,
                                                            [
                                                                'class' => 'irantheme-swiper-section-slider-simple-image-img',
                                                                'alt' => __('تصویر اسلایدر', self::$static_textdomain),
                                                                'loading' => 'lazy',
                                                            ]
                                                        );
                                                        ?>
                                                        <?php if ($slider_link): ?>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>

                                                <?php
                                                // Get background mask checkbox
                                                $mask_checkbox = $object_post['fields']['bg_mask_checkbox'];
                                                // Get background mask color
                                                $mask_color = $object_post['fields']['bg_mask_color'];
                                                // Get background mask range
                                                $mask_range = $object_post['fields']['bg_mask_range'];
                                                // Get showcase mask --------------------------------------------------------------
                                                Irantheme_Section_Utils::get_background_mask_tag_html($mask_checkbox, $mask_color, $mask_range);

                                                // Get section heading --------------------------------------------------------------
                                                $tinymce_heading = $object_post['fields']['text_editor_heading'];

                                                // Set heading alignment --------------------------------------------------------------
                                                $heading_alignment = $object_post['fields']['alignment_heading_radio'];

                                                $content_alignment_class = '';
                                                // Check alignment position
                                                if (
                                                    $heading_alignment == 'center'
                                                ) {
                                                    // Set alignment class center
                                                    $content_alignment_class = 'irantheme-content-center';
                                                } else if ($heading_alignment == 'right') {
                                                    // Set alignment class right
                                                    $content_alignment_class = 'irantheme-content-right';
                                                } else if ($heading_alignment == 'left') {
                                                    // Set alignment class left
                                                    $content_alignment_class = 'irantheme-content-left';
                                                }
                                                ?>

                                                <?php if ($tinymce_heading && $banner_no_text_checkbox == 'off'): ?>
                                                    <!-- Wrapper center content -->
                                                    <div class="irantheme-container-inner h-100 irantheme-swiper-section-slider-simple-content">
                                                        <div class="<?php echo esc_attr($content_alignment_class); ?>" style="height:100%;">
                                                            <div class="d-flex align-items-center justify-content-center justify-content-center irantheme-set-heading">
                                                                <?php
                                                                // Get tinymce content --------------------------------------------------------------
                                                                Irantheme_Common_Static::tinymce_heading($object_post['fields'], '');
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>

                                                <?php
                                                // Get social media status checkbox --------------------------------------------------------
                                                $social_status_checkbox = $object_post['fields']['social_media_checkbox'];
                                                // Get showcase social media --------------------------------------------------------------
                                                Irantheme_Section_Showcase::showcase_social_media($social_status_checkbox);
                                                ?>

                                                <?php
                                                // Get button trigger scroll down checkbox --------------------------------------------------------
                                                $button_trigger_checkbox =
                                                    $object_post['fields']['scroll_down_checkbox'];
                                                // Get showcase trigger (Go to down) --------------------------------------------------------------
                                                Irantheme_Section_Showcase::showcase_button_trigger($button_trigger_checkbox);
                                                ?>

                                                <?php
                                                // Get custom text link checkbox --------------------------------------------------------
                                                $custom_text_link_checkbox = $object_post['fields']['custom_text_link_checkbox'];
                                                // Get heading
                                                $custom_text_link_heading = $object_post['fields']['custom_text_link_heading'];
                                                // Get url
                                                $custom_text_link_url = $object_post['fields']['custom_text_link_url'];
                                                // Get showcase trigger (Go to down) --------------------------------------------------------------
                                                Irantheme_Section_Showcase::showcase_custom_text_link($custom_text_link_checkbox, $custom_text_link_heading, $custom_text_link_url);
                                                ?>
                                                <?php
                                                Irantheme_Page_Builder::get_hidden_require_object_buttons($data_page);
                                                ?>
                                            </div>
                                        <?php endforeach; ?>
                                        <?php
                                        Irantheme_Page_Builder::get_hidden_require_object_group_buttons($data_page);
                                        ?>
                                    </div>

                                    <?php
                                    // Slider pagination option
                                    $slider_pagination_checkbox = $fields_values['slider_pagination_checkbox'];
                                    // Slider arrow option
                                    $slider_arrow_checkbox = $fields_values['slider_arrow_checkbox'];
                                    Irantheme_Section_Utils::get_slider_arrow_pagination('on', $slider_arrow_checkbox, $slider_pagination_checkbox, $base_color_class, $border_radius_range, 'bottom', 'center');
                                    ?>

                                    <?php
                                    // Slider autoplay options
                                    $slider_autoplay_checkbox = $fields_values['slider_autoplay_checkbox'];
                                    // Slider loop option
                                    $loop_checkbox = $fields_values['slider_loop_checkbox'];
                                    ?>
                                    <?php
                                    // Slider display option
                                    $slider_effect_mode = $fields_values['slider_effect_radio'];
                                    if (!$slider_effect_mode) {
                                        $slider_effect_mode == 'normal';
                                    }
                                    if ($slider_effect_mode == 'creative-cover-1' || $slider_effect_mode == 'creative-cover-2') {
                                        $slider_effect_mode == 'creative';
                                    }
                                    ?>

                                    <script>
                                        let autoplayValues<?php echo $data_ajax; ?>;
                                    </script>
                                    <?php
                                    if ($slider_autoplay_checkbox == 'on') {
                                        // Get autoplay timer
                                        $slider_autoplay_timer =
                                            $fields_values['slider_autoplay_delay_number'];
                                    ?>
                                        <!-- Autoplay progress function -->
                                        <script>
                                            autoplayValues<?php echo $data_ajax; ?> = {
                                                delay: <?php echo esc_attr($slider_autoplay_timer); ?>,
                                                disableOnInteraction: false,
                                            }
                                        </script>
                                    <?php
                                    }
                                    ?>

                                    <!-- Get bundle swiper js -->
                                    <script src="<?php echo IRANTHEME_URI ?>/assets/admin/libs/swiper-bundle.min.js"></script>
                                    <script>
                                        // Partners viewpoint slider init
                                        let mySwiper<?php echo $data_ajax; ?> = new Swiper('#irantheme-swiper-section-woocommerce-slider-<?php echo $data_ajax; ?>', {
                                            slidesPerView: 1,
                                            loop: <?php echo $loop_checkbox == 'on' ? 'true' : 'false'; ?>,
                                            centeredSlides: true,
                                            spaceBetween: 0,
                                            roundLengths: true,
                                            lazy: true,
                                            effect: "<?php echo esc_attr($slider_effect_mode); ?>",
                                            autoplay: autoplayValues<?php echo $data_ajax; ?>,
                                            pagination: {
                                                el: '.swiper-pagination',
                                                clickable: true,
                                            },
                                            navigation: {
                                                nextEl: ".swiper-button-next",
                                                prevEl: ".swiper-button-prev",
                                            },
                                            <?php
                                            if ($slider_effect_mode == 'creative-cover-1') {
                                                echo 'grabCursor: true,effect: "creative",creativeEffect: {prev: {shadow: true,translate: ["-20%", 0, -1],},next: {translate: ["100%", 0, 0],},}';
                                            } elseif ($slider_effect_mode == 'creative-cover-2') {
                                                echo ' grabCursor: true,effect: "creative",creativeEffect: {prev: {shadow: true,translate: [0, 0, -400],},next: {translate: ["100%", 0, 0],},},';
                                            }
                                            ?>
                                        });
                                    </script>
                                </div>
                            </div>
                            <div class="col-xxl-3 col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12">

                                <?php
                                // Skeleton html --------------------------------------------------------------
                                irantheme_get_skeleton_html('col-4', 'slider', $base_color_class);
                                ?>

                                <div class="irantheme-woocommerce-slider-product swiper irantheme-woocommerce-slider-product-swiper <?php echo esc_attr($base_color_side_class); ?> <?php echo esc_attr($background_color_css2); ?> irantheme-side-load-skeleton" style="<?php echo esc_attr($border_radius_style); ?>" <?php echo $slider_attr_holder; ?>>
                                    <div class="irantheme-woocommerce-slider-product-holder swiper-wrapper">
                                        <?php
                                        if ($products_post):
                                            while ($products_post->have_posts()) :
                                                $products_post->the_post();
                                                global $product;
                                        ?>
                                                <!-- Products -->
                                                <div class="irantheme-woocommerce-slider-product-item swiper-slide">
                                                    <div class="irantheme-woocommerce-slider-product-item-top">
                                                        <!-- Title -->
                                                        <div class="irantheme-woocommerce-slider-product-item-top-title">
                                                            <i class="fa-light fa-bags-shopping"></i>
                                                            <cite><?php _e('محصولات پیشنهادی'); ?></cite>
                                                        </div>
                                                        <!-- Rating -->
                                                        <div class="irantheme-woocommerce-slider-product-item-top-rating">
                                                            <span><?php echo esc_attr($product->get_average_rating()); ?></span>
                                                            <i class="fa-solid fa-star"></i>
                                                        </div>
                                                        <!-- Discount schedule -->
                                                        <?php Irantheme_Section_Woocommerce::display_schedule_product($product, $base_color, 'flex-center', '', '', '', $details_color_class); ?>
                                                    </div>
                                                    <!-- Thumbnail -->
                                                    <div class="irantheme-woocommerce-slider-product-item-thumbnail">
                                                        <a href="<?php the_permalink(); ?>" class="irantheme-woocommerce-slider-product-item-thumbnail-holder">
                                                            <?php
                                                            echo wp_get_attachment_image(
                                                                get_post_thumbnail_id($product->get_id()),
                                                                'medium_large',
                                                                false,
                                                                [
                                                                    'class' => 'irantheme-woocommerce-slider-product-item-thumbnail-img',
                                                                    'alt' => __('تصویر محصول', self::$static_textdomain),
                                                                    'loading' => 'lazy',
                                                                    'style' => esc_attr($border_radius_style),
                                                                ]
                                                            );
                                                            ?>
                                                        </a>
                                                    </div>
                                                    <!-- Heading -->
                                                    <h2 class="irantheme-woocommerce-slider-product-item-heading">
                                                        <a href="<?php the_permalink(); ?>">
                                                            <?php the_title(); ?>
                                                        </a>
                                                    </h2>
                                                    <?php
                                                    global $product;
                                                    $add_to_cart_ajax_class = 'ajax_add_to_cart';
                                                    if ($product->is_type('variable')) {
                                                        $add_to_cart_ajax_class = '';
                                                    }
                                                    ?>
                                                    <div class="irantheme-woocommerce-products-item-add-to-cart">
                                                        <?php
                                                        // Arrow url --------------------------------------------------------------
                                                        $details_color_rgba_css = '';
                                                        $details_color_rgb_array = Irantheme_Color_Static::hex_to_rgb(Irantheme_Color_Static::get_hex_color($details_color_select));
                                                        if ($details_color_select != 'off') {
                                                            $details_color_rgba_css = 'background-color:rgba(' . $details_color_rgb_array[0] . ',' . $details_color_rgb_array[1] . ',' . $details_color_rgb_array[2] . ',' . '0.1' . ')';
                                                        }
                                                        ?>
                                                        <!-- Add to cart -->
                                                        <a href="<?php echo $product->add_to_cart_url() ?>" value="<?php echo esc_attr($product->get_id()); ?>" class="irantheme-woocommerce-products-item-add-to-cart-button <?php echo esc_attr($add_to_cart_ajax_class); ?> add_to_cart_button" data-product_id="<?php echo $product->get_id(); ?>" style="<?php echo esc_attr($details_color_rgba_css); ?>">
                                                            <i class="iconsax irantheme-woocommerce-products-item-add-to-cart-button-plus <?php echo esc_attr($details_color_class); ?>" icon-name="basket-happy"></i>
                                                            <i class="fa-light fa-check irantheme-woocommerce-products-item-add-to-cart-button-success <?php echo esc_attr($details_color_class); ?>"></i>
                                                            <i class="irantheme-animate-rotate <?php echo esc_attr($details_color_class); ?>" style="display: none;"></i>
                                                        </a>
                                                        <!-- Price -->
                                                        <div class="irantheme-woocommerce-slider-product-item-price">
                                                            <?php Irantheme_Section_Woocommerce::display_product_price($product, true, $details_color_select); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endwhile; ?>
                                        <?php endif; ?>
                                    </div>
                                    <?php
                                    // Slider pagination option
                                    Irantheme_Section_Utils::get_slider_arrow_pagination('on', 'off', 'on', $base_color_class, $border_radius_range, 'bottom', 'center');
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
