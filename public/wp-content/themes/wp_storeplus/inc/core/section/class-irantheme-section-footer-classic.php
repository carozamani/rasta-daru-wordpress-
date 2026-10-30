<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;

if (!class_exists('Irantheme_Section_Footer_Classic')) {
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
    class Irantheme_Section_Footer_Classic extends Irantheme_Section
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
            $settings = array(
                array(
                    'title' => 'محتوا اصلی',
                    'id' => 'middle',
                    'description'            => __('محتوای وسط فوتر', self::$static_textdomain),
                    'icon' => 'fa-pencil',
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
                            $status_default ? Irantheme_Section_Default::get_default_ids_objects(3, 'footer_classic', $status_default) : '',
                        ),
                        array(
                            'id'             => 'logo_checkbox',
                            'label'            => __(
                                'نمایش لوگو',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت فعال کردن این گزینه، لوگو سایت شما به صورت خودکار از هدر در این مکان نمایش داده خواهد شد.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'on' : '',
                            'keywords' => array('نمایش لوگو', 'display logo'),
                        ),
                        array(
                            'id'             => 'menu_select',
                            'label'            => __(
                                'انتخاب منو',
                                self::$static_textdomain
                            ),
                            'description'    => __('جهت نمایش لینک ها می توانید منوی مورد نظر خود را انتخاب کنید. راهنما: برای ساخت منو به پنل امین زبانه "نمایش" -> "فهرست ها" مراجعه و منوی مورد نظر خود را تولید نمایید.', self::$static_textdomain),
                            'type'            => 'select',
                            'options'        => Irantheme_Custom_Fields::get_all_menus(true),
                            'default' => $status_default ? array_keys(Irantheme_Custom_Fields::get_all_menus())[0] : '',
                            'keywords' => array('انتخاب محصول', 'انتخاب دستی محصول', 'product select post', 'post selection of product', 'select product'),
                            'placeholder' => __('محتوا خالی می باشد', self::$static_textdomain)
                        ),
                        array(
                            'id'             => 'details_text_editor',
                            'label'            => __(
                                'جزئیات و اطلاعات',
                                self::$static_textdomain

                            ),
                            'type'            => 'text_editor',
                            'default'        => $status_default ? __('<p><span style="font-size:1em;">شماره مرکز تماس : 03133612345 - 09131234567</span></p><p><span style="font-size:1em;">آدرس رایانامه : example.com@info.com</span></p>') : '',
                            'placeholder'    => __('', self::$static_textdomain),
                            'keywords' => array('communication', 'contact', 'درباره ما',  'درباره'),
                        ),
                        array(
                            'id'             => 'social_media_checkbox',
                            'label'            => __(
                                'نمایش شبکه های اجتماعی',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت فعال بودن؛ شبکه های اجتماعی شما در پاورقی نمایش داده خواهد شد.
                                ', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'on' : '',
                            'keywords' => array('footer', 'socialmedia', 'شبکه های اجتماعی ما',  'شبکه های اجتماعی'),
                        ),
                        array(
                            'id'             => 'newsletter_checkbox',
                            'label'            => __(
                                'نمایش خبرنامه',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت فعال بودن؛ خبرنامه شما در پاورقی نمایش داده خواهد شد.
                                ', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'on' : '',
                            'keywords' => array('newsletter', 'خبرنامه'),
                        ),
                        array(
                            'id'             => 'about_text_editor',
                            'label'            => __(
                                'متن درباره ما خود را تنظیم کنید:',
                                self::$static_textdomain

                            ),
                            'type'            => 'text_editor',
                            'default'        => $status_default ? __('<p><span  style="font-size:0.9em;">لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک است، چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است، و برای شرایط فعلی تکنولوژی مورد نیاز، و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد، کتابهای زیادی در شصت و سه درصد گذشته حال و آینده، شناخت فراوان جامعه و متخصصان را می طلبد، تا با نرم افزارها شناخت بیشتری را برای طراحان رایانه ای علی الخصوص طراحان خلاقی، و فرهنگ پیشرو در زبان فارسی ایجاد کرد.</span></p>') : '',
                            'placeholder'    => __('', self::$static_textdomain),
                            'keywords' => array('communication', 'contact', 'درباره ما',  'درباره'),
                        ),
                    )
                ),
                array(
                    'title' => 'برنامه های موبایلی',
                    'id' => 'applications',
                    'description'            => __('تنظیم برنامه های پلتفرم های موبایلی مانند گوگل پلی، اپ استور، بازار و ...', self::$static_textdomain),
                    'icon' => 'fa-brands fa-google-play',
                    'fields' => array(
                        array(
                            'id'             => 'app_googleplay_url',
                            'label'            => __(
                                'آدرس برنامه گوگل پلی',
                                self::$static_textdomain
                            ),
                            'description'    => __('آدرس اینترنتی برنامه گوگل پلی خود را وارد نمایید. در صورت عدم تمایل به نمایش، کافیست آدرس را خالی بگذارید.', self::$static_textdomain),
                            'type'            => 'text',
                            'default'        => $status_default ? '' : '',
                            'placeholder'    => __('برای مثال: example.com/app/myapp_url', self::$static_textdomain),
                            'keywords' => array('آدرس برنامه گوگل پلی', 'google play url'),
                        ),
                        array(
                            'id'             => 'app_appstore_url',
                            'label'            => __(
                                'آدرس برنامه اپ استور',
                                self::$static_textdomain
                            ),
                            'description'    => __('آدرس اینترنتی برنامه اپ استور خود را وارد نمایید. در صورت عدم تمایل به نمایش، کافیست آدرس را خالی بگذارید.', self::$static_textdomain),
                            'type'            => 'text',
                            'default'        => $status_default ? '' : '',
                            'placeholder'    => __('برای مثال: example.com/app/myapp_url', self::$static_textdomain),
                            'keywords' => array('آدرس برنامه اپ استور', 'google play url'),
                        ),
                        array(
                            'id'             => 'app_cafebazaar_url',
                            'label'            => __(
                                'آدرس برنامه کافه بازار',
                                self::$static_textdomain
                            ),
                            'description'    => __('آدرس اینترنتی برنامه کافه بازار خود را وارد نمایید. در صورت عدم تمایل به نمایش، کافیست آدرس را خالی بگذارید.', self::$static_textdomain),
                            'type'            => 'text',
                            'default'        => $status_default ? '#' : '',
                            'placeholder'    => __('برای مثال: example.com/app/myapp_url', self::$static_textdomain),
                            'keywords' => array('آدرس برنامه کافه بازار', 'google play url'),
                        ),
                        array(
                            'id'             => 'app_myket_url',
                            'label'            => __(
                                'آدرس برنامه مایکت',
                                self::$static_textdomain
                            ),
                            'description'    => __('آدرس اینترنتی برنامه مایکت خود را وارد نمایید. در صورت عدم تمایل به نمایش، کافیست آدرس را خالی بگذارید.', self::$static_textdomain),
                            'type'            => 'text',
                            'default'        => $status_default ? '#' : '',
                            'placeholder'    => __('برای مثال: example.com/app/myapp_url', self::$static_textdomain),
                            'keywords' => array('آدرس برنامه مایکت', 'google play url'),
                        ),
                        array(
                            'id'             => 'app_sibapp_url',
                            'label'            => __(
                                'آدرس برنامه سیب اپ',
                                self::$static_textdomain
                            ),
                            'description'    => __('آدرس اینترنتی برنامه سیب اپ خود را وارد نمایید. در صورت عدم تمایل به نمایش، کافیست آدرس را خالی بگذارید.', self::$static_textdomain),
                            'type'            => 'text',
                            'default'        => $status_default ? '' : '',
                            'placeholder'    => __('برای مثال: example.com/app/myapp_url', self::$static_textdomain),
                            'keywords' => array('آدرس برنامه سیب اپ', 'google play url'),
                        ),
                    )
                ),
                array(
                    'title' => 'نشان ها',
                    'id' => 'badge',
                    'description'            => __('تنظیم نشان های سایت مانند نماد الکترونیک، تجارت الکترونیک و ...', self::$static_textdomain),
                    'icon' => 'fa-badge-check',
                    'fields' => array(
                        array(
                            'id'             => 'badge_checkboxes',
                            'label'            => __(
                                'نمایش نماد ها',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه شما می توانید وضعیت نمایش نماد ها در فوتر را مشخص نمایید.برای مثال: نماد اعتماد ای نماد و...', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'on' : '',
                            'keywords' => array('bage', 'نماد'),
                        ),
                    )
                ),
                array(
                    'title' => 'محتوا پایین',
                    'id' => 'bottom',
                    'description'            => __('محتوای پایین فوتر', self::$static_textdomain),
                    'icon' => 'fa-pencil',
                    'fields' => array(
                        array(
                            'id'             => 'copyright_checkbox',
                            'label'            => __(
                                'نمایش محتوای پایین فوتر',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت فعال بودن؛ قسمت پایین فوتر که شامل متن کپی رایت و شبکه های اجتماعی هست را نمایش می دهد', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'on' : '',
                            'keywords' => array('footer newsletter copyright', 'نمایش محتوای بالای فوتر'),
                            'id_dependents_on' => array('copyright_text_editor'),
                            'id_dependents_off' => array(),
                            'base_priority' => 'checkbox_base',
                            'selector_priority' => 'copyright_checkbox',
                            'z_index' => 0
                        ),
                        // Copyright
                        array(
                            'id'             => 'copyright_text_editor',
                            'label'            => __(
                                'متن کپی رایت خود را تنظیم کنید:',
                                self::$static_textdomain
                            ),
                            'type'            => 'text_editor',
                            'default'        => $status_default ? __('<p style="font-size:1em;">کلیه حقوق مادی و معنوی این سایت محفوظ است.</p>') : '',
                            'placeholder'    => __('', self::$static_textdomain),
                            'keywords' => array('copyright', 'copy', 'کپی رایت',  'کپی'),
                        ),
                    )
                ),
                array(
                    'title' => 'تنظیمات بصری',
                    'id' => 'layout',
                    'description'            => __('تنظیمات متحوا : تنظیم تیتر، تنظیم بند، رنگ متن و...', self::$static_textdomain),
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
                    )
                ),
                // Get layout scale fields
                Irantheme_Section_Utils::layout_scale_fields_generator($status_default, array('inner_padding', 'outer_padding', 'radius'), 'on', 4, 4, 'off', 'off', 0, 0, 1),
                // Get background fields
                Irantheme_Section_Utils::background_fields_generator($status_default, array('color', 'image', 'parallax', 'mask'), '', 'off', true, 'off', 'dark', '0.6', 'off'),
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
            $links_default = '';

            // Check status is deafult or no
            if (is_numeric($index)) {

                // List of authors images
                $images = array(
                    'https://irantheme.com/wp-content/uploads/2024/09/aewasnmq.png',
                    'https://irantheme.com/wp-content/uploads/2025/04/rezi.webp',
                    'https://irantheme.com/wp-content/uploads/2026/05/yebgp6zf.png',
                );

                // Default content
                $links = '#';

                $images_default = $status_default === 'media' ? Irantheme_Common_Static::get_file_url_media_library($images[$index]) : '';
                $links_default = $links;
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
                            'id'             => 'badge_enable_code_checkbox',
                            'label'            => __(
                                'کد به جای تصویر',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورتی که نماد شما به صورت کد است نه تصویر این گزینه را فعال نمایید.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => 'off',
                            'id_dependents_on' => array('badge_code'),
                            'id_dependents_off' => array('badge_image_bg', 'badge_image_link'),
                            'base_priority' => 'checkbox_base',
                            'selector_priority' => 'checkbox_badge_enable_code_priority',
                            'z_index' => 0
                        ),
                        array(
                            'id'             => 'badge_code',
                            'label'            => __(
                                'کد',
                                self::$static_textdomain
                            ),
                            'description'    => __('کد مربوط به نماد را وارد کنید.', self::$static_textdomain),
                            'type'            => 'textarea',
                            'default'        => '',
                            'placeholder'    => __('کد را وارد کنید', self::$static_textdomain),
                        ),
                        array(
                            'id'             => 'badge_image_bg',
                            'label'            => __('تصویر نماد', self::$static_textdomain),
                            'description'    => __('در صورت تمایل تصویر نماد خود را بارگذاری نمایید.', self::$static_textdomain),
                            'type'            => 'image',
                            'default'        => $images_default,
                            'placeholder'    => '',
                        ),
                        array(
                            'id'             => 'badge_image_link',
                            'label'            => __(
                                'لینک نماد',
                                self::$static_textdomain

                            ),
                            'description'    => __('در صورت تمایل می توانید لینک نماد نماد را مشخص کنید.'),
                            'type'            => 'text',
                            'default'        => $links_default,
                            'placeholder'    => __('مثال: https://irantheme.com/vipuser/payment', self::$static_textdomain),
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

            // Base color --------------------------------------------------------------
            $base_color = $fields_values['base_color_select'];
            // Set base color class
            $base_color_class = Irantheme_Common_Static::get_base_color_class($base_color);

            // Logo checkbox
            $logo_checkbox = $fields_values['logo_checkbox'];

            // Copyright checkbox
            $copyright_checkbox = $fields_values['copyright_checkbox'];

            // about
            $about_content = $fields_values["about_text_editor"];

            // details
            $details_content = $fields_values["details_text_editor"];

            // Social Media
            $social_media_checkbox = $fields_values["social_media_checkbox"];

            // Newsletter
            $newsletter_checkbox = $fields_values["newsletter_checkbox"];

            // Get badge checkbox --------------------------------------------------------------
            $badge_checkbox = $fields_values['badge_checkboxes'];

            // Copyright
            $copyright_content = $fields_values["copyright_text_editor"];

            // Get nav menu id
            $nav_menu_id = $fields_values["menu_select"];

            // Get border radius --------------------------------------------------------------
            $border_radius_range = $fields_values['border_radius_range'];
            $border_radius_style = Irantheme_Section_Utils::get_border_radius_css($border_radius_range);

            // Get section id --------------------------------------------------------------
            $section_id = $fields_values['element_id'];
            $section_id_attribute = '';
            if ($section_id) {
                $section_id_attribute = sprintf('id=%s', $section_id);
            }

            // Background options ------------------------------------------------------------
            $background_image_data = $fields_values['bg_image'];
            $background_parallax_checkbox = $fields_values['bg_parallax_checkbox'];
            $background_image_css = Irantheme_Section_Utils::get_background_image_css($background_image_data);
            $background_parallax_attr = Irantheme_Section_Utils::get_background_parallax_attr($background_parallax_checkbox, $background_image_data);
            $background_color_data = $fields_values['bg_color'];
            $background_color_css = Irantheme_Section_Utils::get_background_color_css($background_color_data);

            // Platform applications --------------------------------------------------------------
            $app_googleplay_url = $fields_values['app_googleplay_url'];
            $app_appstore_url = $fields_values['app_appstore_url'];
            $app_cafebazaar_url = $fields_values['app_cafebazaar_url'];
            $app_myket_url = $fields_values['app_myket_url'];
            $app_sibapp_url = $fields_values['app_sibapp_url'];
?>
            <!-- ============================= Section Footer Classic ============================= -->
            <footer <?php echo esc_attr($section_id_attribute); ?> class="irantheme-section irantheme-footer-classic-section <?php echo esc_attr($base_color_class); ?>" <?php echo esc_attr(Irantheme_Page_Builder::get_attr_data_section($data_type, $data_value, $data_page, $data_ajax)); ?> style="<?php echo esc_attr(Irantheme_Section_Service::get_custom_outer_padding_css($fields_values)); ?>">
                <div class="irantheme-footer-classic irantheme-background-jarallax" style="<?php echo esc_attr($background_color_css); ?><?php echo esc_attr($background_image_css); ?><?php echo esc_attr($border_radius_style); ?>" <?php echo $background_parallax_attr; ?>>
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

                    <!-- Headline -->
                    <div class="irantheme-footer-classic-headline" style="<?php echo esc_attr(Irantheme_Section_Service::get_custom_inner_padding_css($fields_values)); ?>">
                        <div class="irantheme-container-inner">

                            <div class="irantheme-footer-classic-row">
                                <!-- Useful links -->
                                <div class="irantheme-footer-classic-col w-auto">
                                    <b class="irantheme-footer-classic-title"><?php _e('دسترسی سریع'); ?></b>
                                    <?php if ($nav_menu_id != 'none') : ?>
                                        <div class="irantheme-footer-classic-useful-links">
                                            <ul class="irantheme-footer-classic-useful-links-list">
                                                <?php
                                                $menu_items = wp_get_nav_menu_items($nav_menu_id, array('order' => 'DESC'));
                                                foreach ($menu_items as $item) {
                                                ?>
                                                    <li class="irantheme-footer-classic-useful-links-list-item">
                                                        <a href="<?php echo esc_url($item->url); ?>" class="irantheme-footer-classic-useful-links-list-item-link">
                                                            <?php echo esc_html($item->title); ?>
                                                        </a>
                                                    </li>
                                                <?php
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- contact -->
                                <div class="irantheme-footer-classic-col">
                                    <b class="irantheme-footer-classic-title">
                                        <?php _e('راه های ارتباطی'); ?>
                                    </b>
                                    <div class="irantheme-footer-classic-details">
                                        <?php _e($details_content); ?>
                                    </div>
                                    <div class="irantheme-footer-classic-app">
                                        <?php
                                        $app_links = [
                                            'googleplay' => [
                                                'url' => $app_googleplay_url ?? '',
                                                'icon' => 'googleplay2.png',
                                                'alt' => __('دانلود از Google Play', self::$static_textdomain),
                                            ],
                                            'appstore' => [
                                                'url' => $app_appstore_url ?? '',
                                                'icon' => 'appstore2.png',
                                                'alt' => __('دانلود از App Store', self::$static_textdomain),
                                            ],
                                            'cafebazaar' => [
                                                'url' => $app_cafebazaar_url ?? '',
                                                'icon' => 'cafebazaar2.png',
                                                'alt' => __('دانلود از Cafe Bazaar', self::$static_textdomain),
                                            ],
                                            'myket' => [
                                                'url' => $app_myket_url ?? '',
                                                'icon' => 'myket2.png',
                                                'alt' => __('دانلود از Myket', self::$static_textdomain),
                                            ],
                                            'sibapp' => [
                                                'url' => $app_sibapp_url ?? '',
                                                'icon' => 'sibapp2.png',
                                                'alt' => __('دانلود از Sib App', self::$static_textdomain),
                                            ],
                                        ];

                                        foreach ($app_links as $key => $app) {
                                            if (!empty($app['url'])) {
                                                $icon_path = IRANTHEME_URI . '/assets/images/applications/' . $app['icon'];
                                        ?>
                                                <a href="<?php echo esc_url($app['url']); ?>"
                                                    class="irantheme-footer-classic-app-link"
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    aria-label="<?php echo esc_attr($app['alt']); ?>">
                                                    <img src="<?php echo esc_url($icon_path); ?>"
                                                        alt="<?php echo esc_attr($app['alt']); ?>"
                                                        loading="lazy"
                                                        width="135"
                                                        height="40">
                                                </a>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>

                                <!-- contact -->
                                <div class="irantheme-footer-classic-col">
                                    <?php if ($newsletter_checkbox == "on") : ?>
                                        <b class="irantheme-footer-classic-title"><?php _e('عضویت در خبرنامه'); ?></b>
                                        <!-- Form -->
                                        <div class="irantheme-communication-newsletter-form">
                                            <div class="irantheme-communication-newsletter-form-input-holder">
                                                <!-- Email input -->
                                                <input type="email" class="irantheme-communication-newsletter-form-email irantheme-input-email <?php echo esc_attr(Irantheme_Common_Static::get_base_color_class($base_color)); ?>" placeholder="ایمیل خود را وارد کنید..." style="<?php echo esc_attr($border_radius_style); ?>" required>
                                                <!-- Button submit -->
                                                <button type="button" class="irantheme-communication-newsletter-form-submit irantheme-btn irantheme-btn-medium irantheme-btn-<?php echo esc_attr(Irantheme_Common_Static::get_base_reverse_color_class($base_color)); ?>" style="<?php echo esc_attr($border_radius_style); ?>"><?php _e('ثبت'); ?></button>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($social_media_checkbox == "on") : ?>
                                        <b class="irantheme-footer-classic-title"><?php _e('ما را در شبکه های اجتماعی دنبال کنید'); ?></b>
                                        <?php Irantheme_Common_Static::get_social_media(); ?>
                                    <?php endif; ?>
                                </div>

                                <!-- Badge -->
                                <div class="irantheme-footer-classic-col">
                                    <div class="irantheme-footer-classic-service">
                                        <?php if ($logo_checkbox == 'on'): ?>
                                            <div class="irantheme-footer-classic-service-logo">
                                                <div class="irantheme-logo-brand">
                                                    <?php
                                                    // Get logo html
                                                    echo Irantheme_Header_Main::irantheme_get_logo(Irantheme_Common_Static::get_base_reverse_color_class($base_color));
                                                    ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <?php if (is_woocommerce_activated()):
                                            $questions = wp_count_posts('product_qa');
                                            $total_questions = $questions->publish;
                                            $product_count = wp_count_posts('product');
                                            $total_products = $product_count->publish;
                                            $total_users = count_users();
                                            $total_users_count = $total_users['total_users'];
                                        ?>
                                            <!-- Service list -->
                                            <ul class="irantheme-footer-classic-service-list">
                                                <li class="irantheme-footer-classic-service-list-item">
                                                    <span class="irantheme-footer-classic-service-list-item-value"><?php echo esc_html($total_questions); ?></span>
                                                    <cite class="irantheme-footer-classic-service-list-item-title"><?php _e('پرسش و پاسخ'); ?></cite>
                                                </li>
                                                <li class="irantheme-footer-classic-service-list-item">
                                                    <span class="irantheme-footer-classic-service-list-item-value"><?php echo esc_html($total_users_count); ?></span>
                                                    <cite class="irantheme-footer-classic-service-list-item-title"><?php _e('تعداد کاربران'); ?></cite>
                                                </li>
                                                <li class="irantheme-footer-classic-service-list-item">
                                                    <span class="irantheme-footer-classic-service-list-item-value"><?php echo esc_html($total_products); ?></span>
                                                    <cite class="irantheme-footer-classic-service-list-item-title"><?php _e('محصول'); ?></cite>
                                                </li>
                                            </ul>
                                        <?php endif; ?>
                                        <div class="irantheme-footer-classic-badge">
                                            <?php
                                            if ($badge_checkbox == 'on') {
                                                // Display badges
                                                Irantheme_Common_Static::get_badges($data_page, $json_post_array);
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php if ($about_content) : ?>
                                <!-- About -->
                                <div class="irantheme-footer-classic-text" style="<?php echo esc_attr($border_radius_style); ?>">
                                    <p><?php _e($about_content); ?></p>
                                </div>
                            <?php endif; ?>

                            <?php if ($copyright_checkbox == 'on') : ?>
                                <!-- Copyright -->
                                <div class="irantheme-footer-classic-copyright">
                                    <div class="irantheme-footer-classic-copyright-text">
                                        <?php echo $copyright_content; ?>
                                    </div>
                                </div>
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
            </footer>
<?php
        }
    }
}
