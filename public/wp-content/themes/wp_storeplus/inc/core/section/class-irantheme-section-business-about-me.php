<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;

if (!class_exists('Irantheme_Section_Business_About_Me')) {
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
    class Irantheme_Section_Business_About_Me extends Irantheme_Section
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
                array(
                    'title' => 'تنظیم محتوا',
                    'id' => 'posts',
                    'description'            => __('تنظیمات مربوط درباره من', self::$static_textdomain),
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
                            $status_default ? Irantheme_Section_Default::get_default_ids_objects(1, 'business_about_me', $status_default) : '',
                        ),
                        array(
                            'id'             => 'picture',
                            'label'            => __('تصویر شخص', self::$static_textdomain),
                            'description'    => __('لطفا تصویر شخص مورد نظر را بارگذاری نمایید.', self::$static_textdomain),
                            'type'            => 'image',
                            'default'        => $status_default === 'media' ? Irantheme_Common_Static::get_file_url_media_library('https://irantheme.com/wp-content/uploads/2026/02/4pksm7af.webp') : '',
                            'placeholder'    => '',
                            'keywords' => array('تصویر شخص', 'person image')
                        ),
                        array(
                            'id'             => 'name',
                            'label'            => __(
                                'نام شخص',
                                self::$static_textdomain

                            ),
                            'description'    => __('نام یا لقب شخص مورد نظر را وارد کنید.'),
                            'type'            => 'text',
                            'default'        => $status_default ? 'حمید یقمایی - سرآشپز بین المللی' : '',
                            'placeholder'    => __('عنوان', self::$static_textdomain),
                            'keywords' => array('نام شخص', 'person name')
                        ),
                        array(
                            'id'             => 'about',
                            'label'            => __(
                                'توضیحات درباره من',
                                self::$static_textdomain
                            ),
                            'description'    => __('توضیحات مربوط به تخصص یا فرد مورد نظر را بنویسید.', self::$static_textdomain),
                            'type'            => 'textarea',
                            'default'        => $status_default ? 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک است، چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است، و برای شرایط فعلی تکنولوژی مورد نیاز، و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد، کتابهای زیادی در شصت و سه درصد گذشته حال و آینده، شناخت فراوان جامعه و متخصصان را می طلبد، تا با نرم افزارها شناخت بیشتری را برای طراحان رایانه ای علی الخصوص طراحان خلاقی، و فرهنگ پیشرو در زبان فارسی ایجاد کرد، در این صورت می توان امید داشت که تمام و دشواری موجود در ارائه راهکارها، و شرایط سخت تایپ به پایان رسد و زمان مورد نیاز شامل حروفچینی دستاوردهای اصلی، و جوابگوی سوالات پیوسته اهل دنیای موجود طراحی اساسا مورد استفاده قرار گیرد.' : '',
                            'placeholder'    => __('توضیحات را وارد کنید', self::$static_textdomain),
                            'keywords' => array('توضیحات درباره من', 'about me description')
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
                            'id'             => 'display_mode',
                            'label'            => __(
                                'سبک محتوا',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید سبک نمایش محتوای سرویس خود را مشخص کنید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'thumbnail_status' => true,
                            'options'        => array('right' => array('راست', 'showcase-split-right.png'), 'left' => array('چپ', 'showcase-split-left.png')),
                            'default'        => $status_default ? 'right' : '',
                            'keywords' => array('semi', 'mode', 'سبک', 'سرویس', 'چیدمان', 'alignment')
                        ),
                        array(
                            'id'             => 'base_color_select',
                            'label'            => __(
                                'رنگ پایه',
                                self::$static_textdomain
                            ),
                            'description'    => __('برای انتخاب رنگ پایه سرویس از لیست رنگ دلخواه خود را انتخاب کنید.رنگ محتواو پس زمینه سرویس بر اساس این رنگ تنظیم خواهد شد.', self::$static_textdomain),
                            'type'            => 'select',
                            'options'        => array('white' => 'سفید', 'dark' => 'مشکی'),
                            'default'        => $status_default ? 'white' : '',
                            'keywords' => array('color', 'background', 'پس زمینه', 'رنگ'),
                        ),
                        array(
                            'id'             => 'magnification_gallery_checkbox',
                            'label'            => __(
                                'قابلیت بزرگنمایی تصویر',
                                self::$static_textdomain
                            ),
                            'description'    => __('با فعال کردن این گزینه، قابلیت بزرگنمایی تصویر امضا یا مدرک بارگذاری شده در این بخش فعال خواهد شد.
                                ', self::$static_textdomain),
                            'note'    => __('پیشنهاد می گردد از این قابلیت فقط در زمان بارگذاری تصویری بجز امضا استفاده کنید.
                                ', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'off' : '',
                            'keywords' => array('نمایش گالری', 'gallery footer', 'گالری',  'footer gallery'),
                            'id_dependents_on' => array('magnification_bg_color_select', 'magnification_tool_multiple_checkboxes'),
                            'id_dependents_off' => array(),
                            'base_priority' => 'checkbox_base',
                            'selector_priority' => 'magnification_status_checkbox',
                            'z_index' => 0,
                        ),
                        array(
                            'id'             => 'magnification_bg_color_select',
                            'label'            => __(
                                'رنگ پس زمینه بخش بزرگنمایی',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید رنگ پس زمینه بخش بزرگنمایی را مشخص کنید.', self::$static_textdomain),
                            'type'            => 'select',
                            'options'        => array('white' => 'سفید', 'dark' => 'مشکی'),
                            'default'        => $status_default ? 'white' : '',
                            'keywords' => array('color', 'background', 'پس زمینه', 'رنگ'),
                        ),
                        array(
                            'id'             => 'magnification_tool_multiple_checkboxes',
                            'label'            => __(
                                'ابزارهای بخش بزرگنمایی رسانه',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید نمایش و یا عدم نمایش هرکدام از ابزار های موجود در صفحه بزرگنمایی رسانه ها را مشخص کنید.', self::$static_textdomain),
                            'type'            => 'checkbox_multi',
                            'options'        => array('download' => 'دانلود', 'fullscreen-toggle' => 'تمام صفحه', 'share' => 'اشتراک گذاری'),
                            'default'        => $status_default ? 'download,fullscreen-toggle,share' : '',
                            'keywords' => array('tool', 'magnification', 'media', 'image', 'video', 'نگارخانه', 'gallery', 'download', 'اشتراک', 'رسانه', 'گالری', 'تمام صفحه', 'دانلود', 'share', 'fullscreen')
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
                            'keywords' => array('communication contact', 'contact', 'جزئیات', 'کلید', 'رنگ'),
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
                // Get layout scale fields
                Irantheme_Section_Utils::layout_scale_fields_generator($status_default, array('radius', 'inner_padding', 'outer_padding'), 'on', 2, 2, 'off', 'off', 0, 0, 1),
                // Get background fields
                Irantheme_Section_Utils::background_fields_generator($status_default, array('image', 'color', 'mask', 'parallax', 'outer_container_color'), '', 'off', true, 'off', 'dark', 0.6, 'off'),
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
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        public static function object_fields($index, $status_default = false)
        {
            $images_default = '';

            // Check status is deafult or no
            if (is_numeric($index)) {
                // List of default images
                $images = array(
                    'https://irantheme.com/wp-content/uploads/2024/10/signature.png'
                );

                // Set default value
                $images_default = $status_default === 'media' ? Irantheme_Common_Static::get_file_url_media_library($images[$index]) : '';
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
                            'id'             => 'me_signature',
                            'label'            => __('امضا یا مدرک', self::$static_textdomain),
                            'description'    => __('امضاء یا مدرک شخص را بارگذاری نمایید.', self::$static_textdomain),
                            'type'            => 'image',
                            'default'        => $images_default,
                            'placeholder'    => '',
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
            $background_color_container_checkbox = $fields_values['outer_container_color_checkbox'];
            $background_color_data = $fields_values['bg_color'];
            $background_color_css = Irantheme_Section_Utils::get_background_color_css($background_color_data);
            $background_image_data = $fields_values['bg_image'];
            $background_image_css = Irantheme_Section_Utils::get_background_image_css($background_image_data);
            $background_parallax_checkbox = $fields_values['bg_parallax_checkbox'];
            $background_parallax_attr = Irantheme_Section_Utils::get_background_parallax_attr($background_parallax_checkbox, $background_image_data);

            $image_person_data = $fields_values['picture'];
            $image_person_url = Irantheme_Section_Utils::get_background_image_css($image_person_data, true);

            // Get data load style --------------------------------------------------------------
            $data_load_attr = Irantheme_Section_Utils::get_data_load_attr_type($fields_values);
            // Get data load duration
            $data_load_duration = Irantheme_Section_Utils::get_data_load_duration($fields_values);
            // Get aos delay time
            $aos_delay =
                Irantheme_Section_Utils::get_data_load_delay($fields_values);

            // Base color --------------------------------------------------------------
            $base_color = $fields_values['base_color_select'];
            // Set base color class
            $base_color_class = Irantheme_Common_Static::get_base_color_class($base_color);

            // Details color --------------------------------------------------------------
            $details_color_select = $fields_values['details_color_select'];
            $details_color_class = '';
            if ($details_color_select != 'off') {
                $details_color_class = Irantheme_Color_Static::get_class_color_mode('color', null, Irantheme_Color_Static::get_hex_color($details_color_select));
            }

            // Get magnification checkbox --------------------------------------------------------------
            $magnification_checkbox = $fields_values['magnification_gallery_checkbox'];
            $magnification_class = '';
            if ($magnification_checkbox == 'on') {
                $magnification_class = 'irantheme-common-magnifiable-images';
            }

            // Get section id --------------------------------------------------------------
            $section_id = $fields_values['element_id'];
            $section_id_attribute = '';
            if ($section_id) {
                $section_id_attribute = sprintf('id=%s', $section_id);
            }

            // Section transform to up and down --------------------------------------------------------------
            $section_transfrom_values = Irantheme_Section_Utils::get_transform_values_arrays($data_page, $fields_values, true);
            $section_transform_extra_css = $section_transfrom_values['overflow'];
            $section_transform_translate_css = $section_transfrom_values['css'];

            // Get border radius --------------------------------------------------------------
            $border_radius_range = $fields_values['border_radius_range'];
            $border_radius_style = Irantheme_Section_Utils::get_border_radius_css($border_radius_range);
?>
            <!-- ============================= Section service ============================= -->
            <section <?php echo esc_attr($section_id_attribute); ?> class="irantheme-section irantheme-business-about-me-section <?php echo esc_attr($magnification_class); ?>" <?php echo esc_attr(Irantheme_Page_Builder::get_attr_data_section($data_type, $data_value, $data_page, $data_ajax)); ?> style="<?php echo esc_attr($section_transform_extra_css); ?><?php echo esc_attr(Irantheme_Section_Service::get_custom_outer_padding_css($fields_values)); ?><?php echo esc_attr(Irantheme_Section_Utils::get_background_outer_container_color_css($background_color_data, $background_color_container_checkbox, $base_color)) ?>">
                <div class="irantheme-business irantheme-business-about-me <?php echo esc_attr($base_color_class); ?> irantheme-background-jarallax" style="<?php echo esc_attr($background_color_css); ?><?php echo esc_attr($background_image_css); ?><?php echo esc_attr(Irantheme_Section_Service::get_custom_inner_padding_css($fields_values)); ?><?php echo esc_attr($section_transform_translate_css); ?><?php echo esc_attr($border_radius_style); ?>" <?php echo $background_parallax_attr; ?>>

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
                    ?>
                    <?php
                    if ($container_checkbox != 'on') :
                    ?>
                        <div class=" irantheme-container-inner">
                        <?php endif; ?>

                        <div class="irantheme-business-about-me">
                            <?php
                            // Get alignment columns --------------------------------------------------------------
                            $display_mode = $fields_values['display_mode'];
                            $display_mode_class = '';
                            $padding_holder_class = '';
                            if ($display_mode == 'right') {
                                $display_mode_class = 'flex-row-reverse';
                                $padding_holder_class = 'padding-left';
                            } else {
                                $padding_holder_class = 'padding-right';
                            }
                            // Set padding column with wrapper mode and connect to outer container
                            if ($container_checkbox == 'on') {
                                $padding_holder_class = 'padding-right padding-left';
                            }
                            $col_content_class = 'col-xl-8 col-lg-8 col-md-6 col-sm-12 col-12';
                            $col_image_class  = 'col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12';
                            if (!$image_person_data) {
                                $col_content_class = 'col-lg-12';
                            }
                            ?>
                            <div class="row justify-content-center <?php echo esc_attr($display_mode_class); ?>">
                                <?php if ($image_person_data) : ?>
                                    <div class="<?php echo esc_attr($col_image_class); ?>" style="display:flex;justify-content:center;">
                                        <div class="irantheme-business-about-me-animation" <?php echo ($data_load_attr); ?> data-aos-delay="<?php echo ($aos_delay); ?>" data-aos-duration="<?php echo ($data_load_duration); ?>">
                                            <?php if ($image_person_url) : ?>
                                                <div class="irantheme-business-about-me-image">
                                                    <?php
                                                    echo wp_get_attachment_image(
                                                        $image_person_data,
                                                        'medium_large',
                                                        false,
                                                        [
                                                            'class' => 'irantheme-business-about-me-image-img',
                                                            'alt' => __('تصویر پروفایل', self::$static_textdomain),
                                                            'loading' => 'lazy',
                                                            'style' => $border_radius_style,
                                                        ]
                                                    );
                                                    ?>
                                                </div>
                                            <?php else : ?>
                                                <?php
                                                // Empty thumbnail
                                                Irantheme_Section_Service::empty_thumbnail($base_color);
                                                ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <div class="<?php echo esc_attr($col_content_class); ?>">
                                    <div class="irantheme-business-about-me-holder irantheme-holder-padding <?php echo esc_attr($padding_holder_class); ?>">
                                        <div class="irantheme-business-about-me-social">
                                            <?php
                                            // Get social media
                                            Irantheme_Common_Static::get_social_media($details_color_class, $border_radius_style);
                                            ?>
                                        </div>
                                        <!-- Content -->
                                        <?php
                                        // Person name --------------------------------------------------------------
                                        $person_name = $fields_values['name'];
                                        // Person about --------------------------------------------------------------
                                        $person_about = $fields_values['about'];
                                        ?>
                                        <div class="irantheme-business-about-me-content">
                                            <?php $aos_delay += 500; ?>
                                            <div class="irantheme-business-about-me-animation" <?php echo ($data_load_attr); ?> data-aos-delay="<?php echo ($aos_delay); ?>" data-aos-duration="<?php echo ($data_load_duration); ?>">
                                                <h3 class="irantheme-business-about-me-content-name <?php echo esc_attr($details_color_class); ?>">
                                                    <?php echo esc_html($person_name); ?>
                                                </h3>
                                                <p class=" irantheme-business-about-me-content-about">
                                                    <?php echo esc_html($person_about); ?>
                                                </p>
                                            </div>
                                            <?php $aos_delay += 500; ?>
                                            <div class="irantheme-business-about-me-animation" <?php echo ($data_load_attr); ?> data-aos-delay="<?php echo ($aos_delay); ?>" data-aos-duration="<?php echo ($data_load_duration); ?>">
                                                <div class="irantheme-object-group row irantheme-business-about-me-content-signature" data-json="<?php echo esc_attr('objects_json'); ?>">
                                                    <?php
                                                    // Magnification tag id generator
                                                    $magnification_id = 'irantheme_magnification_' . $data_type . '_' . $data_value . '_' . $data_page . '_' . $data_ajax;
                                                    // Get magnification tools --------------------------------------------------------------
                                                    $magnification_tool = $fields_values['magnification_tool_multiple_checkboxes'];
                                                    $magnification_tool = explode(',', $magnification_tool);
                                                    // Get magnification backgrou color
                                                    $magnification_bg_color = $fields_values['magnification_bg_color_select'];
                                                    // Item count
                                                    $item_index = 0;
                                                    // Media array
                                                    $media_element_array = array();
                                                    // Service custom fields --------------------------------------------------------------
                                                    foreach ($json_post_array as $object_post) :
                                                        // Get signatures url (attachment ID)
                                                        $signature_id = isset($object_post['fields']['me_signature'])
                                                            ? $object_post['fields']['me_signature']
                                                            : 0;

                                                        // Get signature image src (medium) and full size src
                                                        $signature_src = !empty($signature_id)
                                                            ? wp_get_attachment_image_src($signature_id, 'medium_large')
                                                            : false;

                                                        $signature_full = !empty($signature_id)
                                                            ? wp_get_attachment_image_src($signature_id, 'full')
                                                            : false;

                                                        // Pick the best available URL
                                                        $signature_url = '';
                                                        if (!empty($signature_full[0])) {
                                                            $signature_url = $signature_full[0];
                                                        } elseif (!empty($signature_src[0])) {
                                                            $signature_url = $signature_src[0];
                                                        }

                                                        // Only push a valid URL
                                                        if ($signature_url) {
                                                            $media_element_array[] = $signature_url;
                                                        }
                                                    ?>

                                                        <div class="irantheme-object col-lg-4 irantheme-business-about-me-content-signature-image"
                                                            data-object="<?php echo esc_attr($object_post['object_id']); ?>">

                                                            <div class="irantheme-common-media-gallery-item"
                                                                data-item-id="<?php echo esc_attr($item_index); ?>"
                                                                data-signature-id="<?php echo esc_attr($signature_id); ?>">

                                                                <?php echo wp_get_attachment_image(
                                                                    $signature_id,
                                                                    'medium',
                                                                    false,
                                                                    [
                                                                        'class' => 'irantheme-business-about-me-content-signature-image-img',
                                                                        'alt' => sprintf(__('امضاء %s', self::$static_textdomain), $person_name),
                                                                        'loading' => 'lazy',
                                                                    ]
                                                                ); ?>

                                                                <?php if ($magnification_checkbox == 'on') : ?>
                                                                    <div class="irantheme-business-about-me-gallery-item-hover-mask"
                                                                        role="button"
                                                                        tabindex="0"
                                                                        aria-label="<?php _e('بزرگنمایی تصویر امضاء', self::$static_textdomain); ?>"
                                                                        onclick="Irantheme.openMediaGallery(<?php echo $item_index; ?>)">
                                                                        <i class="fa-light fa-expand" aria-hidden="true"></i>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>

                                                            <?php Irantheme_Page_Builder::get_hidden_require_object_buttons($data_page); ?>
                                                        </div>
                                                    <?php
                                                        $item_index++;
                                                    endforeach;
                                                    ?>
                                                    <?php
                                                    Irantheme_Page_Builder::get_hidden_require_object_group_buttons($data_page);
                                                    ?>
                                                </div>
                                            </div>
                                            <?php
                                            if ($magnification_checkbox == 'on') {
                                                // Get media gallery magnification --------------------------------------------------------------
                                                Irantheme_Common_Static::gallery_magnification($magnification_id, $magnification_tool, $magnification_bg_color, $media_element_array);
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
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
