<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;

if (!class_exists('Irantheme_Section_Business_Brands')) {
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
    class Irantheme_Section_Business_Brands extends Irantheme_Section
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
                Irantheme_Section_Utils::heading_title_settings_generator($status_default, 'none', false, false, ''),
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
                            'default'        => $status_default ? Irantheme_Section_Default::get_default_ids_objects(11, 'business_brands', $status_default) : '',
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
                            'label'            => __('تعداد ستون محتوا', self::$static_textdomain),
                            'description'    => __('با استفاده از این گزینه شما می توانید تعداد ستون برای نمایش لوگو ها را مشخص نمایید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'thumbnail_status' => true,
                            'options'        => array(
                                'col-6' => array('شش ستون', 'col-6.png'),
                                'col-8' => array('هشت ستون', 'col-8.png'),
                                'col-10' => array('ده ستون', 'col-10.png'),
                            ),
                            'default'        => $status_default ? 'col-8' : '',
                            'keywords' => array('business', 'col', 'brands', 'تعداد', 'کسب و کار', 'تعداد در هر ردیف'),
                        ),
                        array(
                            'id'             => 'post_slider_mode_checkbox',
                            'label'            => __(
                                'تبدیل به اسلایدر',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید تعیین کنید که پست ها بصورت اسلایدر و به تعداد ستون های انتخابی در بالا در هر برگه اسلایدر سازماندهی شده و نمایش داده شوند و یا بصورت سطر و ستون در کنار هم نمایش داده شوند.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'on' : '',
                            'keywords' => array('business', 'لوگو', 'brands', 'اسلایدر', 'نشان'),
                        ),
                        array(
                            'id'             => 'cell_height_range',
                            'label'            => __(
                                'حداکثر ارتفاع برند ها',
                                self::$static_textdomain
                            ),
                            'description'    => __('با اسفاده از این گزینه می توانید ارتفاع برند ها را تعیین کنید.(واحد px می باشد)', self::$static_textdomain),
                            'note'    => __('در صورت تمایل می توانید ارتفاع برند های خود را تنظیم نمایید.', self::$static_textdomain),
                            'type'            => 'range',
                            'min' => 10,
                            'max' => 100,
                            'step' => 1,
                            'default'        => $status_default ? 40 : '',
                            'placeholder'    => __('Placeholder text', self::$static_textdomain),
                            'keywords' => array('communication', 'contact', 'ارتباط با ما',  'ارتباطات', 'اندازه', 'ارتفاع')
                        ),
                        array(
                            'id'             => 'wrapper_mode_checkbox',
                            'label'            => __(
                                'اتصال به نگهدارنده خارجی سایت',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت غیر فعال بودن این گزینه بخش های (سکشن ها) سایت از نگهدارنده داخلی سایت استفاده می کنند، شما با فعال کردن این گزینه می توانید نگهدارنده این بخش را به نگهدارنده خارجی سایت تغییر دهید.در این صورت، این بخش از کناره ها به اندازه نگهدارنده خارجی سایت بزرگ شده وبه آن می چسبد.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'on' : '',
                            'keywords' => array('نگهدارنده خارجی', 'اتصال', 'fullscreen', 'تمام صفحه', 'نگه دارنده', 'نگهدارنده', 'container', 'wrapper'),
                            'id_dependents_on' => array(),
                            'id_dependents_off' => array('wrapper_mode_bg_checkbox'),
                            'base_priority' => 'checkbox_base',
                            'selector_priority' => 'checkbox_wrapper_mode',
                            'z_index' => 0
                        ),
                        array(
                            'id'             => 'wrapper_mode_bg_checkbox',
                            'label'            => __(
                                'اتصال پس زمینه به نگهدارنده',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت فعال بودن این گزینه، تمامی عناصر این بخش (سکشن) از جمله پس زمینه، به نگهدارنده خواهند چسبید.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'on' : '',
                            'keywords' => array('اتصال پس زمینه به نگهدارنده', 'connect background to inner container'),
                        ),
                    )
                ),
                // Get layout scale fields
                Irantheme_Section_Utils::layout_scale_fields_generator($status_default, array('radius', 'inner_padding', 'outer_padding'), 'on', 2, 2, 'off', 'off', 0, 0, 0),
                // Get background fields
                Irantheme_Section_Utils::background_fields_generator($status_default, array('image', 'color', 'mask', 'parallax', 'gradient'), '', 'off', true, 'off', 'dark', 0.6, 'on'),
                array(
                    'title' => 'امکانات',
                    'id' => 'additional',
                    'description'            => __('فعال یا غیر فعال کردن امکانات', self::$static_textdomain),
                    'icon' => 'fa-puzzle-piece',
                    'fields' => array(
                        array(
                            'id'             => 'bg_animation_checkbox',
                            'label'            => __(
                                'پویانمایی گرادینت',
                                self::$static_textdomain
                            ),
                            'description'    => __('با فعال کردن این گزینه یک پویانمایی (انیمیشن) به گرادینت پس زمینه اضافه می شود.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'off' : '',
                            'keywords' => array('business', 'نویسنده', 'author', 'لینک', 'نویسندگان'),
                            'id_dependents_on' => array('bg_animation_speed_range', 'bg_animation_zoom_range', 'bg_animation_zoom_range'),
                            'id_dependents_off' => array(),
                            'base_priority' => 'checkbox_base',
                            'selector_priority' => 'checkbox_bg_animation',
                            'z_index' => 0
                        ),
                        array(
                            'id'             => 'bg_animation_speed_range',
                            'label'            => __(
                                'سرعت پویانمایی',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید سرعت پویانمایی (انیمیشن) را مشخص نمایید.', self::$static_textdomain),
                            'type'            => 'range',
                            'min' => 1,
                            'max' => 10,
                            'step' => 1,
                            'default'        => $status_default ? 6 : '',
                            'placeholder'    => __('Placeholder text', self::$static_textdomain),
                            'keywords' => array('transparency', 'opacity', 'شفافیت', 'شفاف', 'پس زمینه', 'تاپ بار'),
                        ),
                        array(
                            'id'             => 'bg_animation_zoom_range',
                            'label'            => __(
                                'بزرگنمایی پس زمینه',
                                self::$static_textdomain
                            ),
                            'description'    => __('برای ایجاد انیمیشن روی گرادینت نیاز است تا پس زمینه بزرگتر از اندازه کل بخش باشد تا انیمیشن قابلیت اجرا داشته باشد.به همین منظور می توانید با استفاده از این گزینه میزان این بزرگنمایی را در محدوده از پیش مشخص شده تنظیم نمایید.', self::$static_textdomain),
                            'note'    => __('واحد اندازه گیری این گزینه ٪ می باشد.', self::$static_textdomain),
                            'type'            => 'range',
                            'min' => 110,
                            'max' => 500,
                            'step' => 10,
                            'default'        => $status_default ? 300 : '',
                            'placeholder'    => __('Placeholder text', self::$static_textdomain),
                            'keywords' => array('transparency', 'opacity', 'شفافیت', 'شفاف', 'پس زمینه', 'تاپ بار'),
                        ),
                    )
                ),
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
            $links_default = '';
            $images_default = '';

            // Check status is deafult or no
            if (is_numeric($index)) {
                // List of default links
                $links = '#';

                // List of default images
                $images = array(
                    'https://irantheme.com/wp-content/uploads/2026/02/of7akryp.png',
                    'https://irantheme.com/wp-content/uploads/2026/02/hget7yrx.png',
                    'https://irantheme.com/wp-content/uploads/2026/02/fq3ai7wf.webp',
                    'https://irantheme.com/wp-content/uploads/2026/02/6nb0lu2b.png',
                    'https://irantheme.com/wp-content/uploads/2026/02/lrhzm2kl.png',
                    'https://irantheme.com/wp-content/uploads/2026/02/jsunnbkv.png',
                    'https://irantheme.com/wp-content/uploads/2026/02/llithf29.png',
                    'https://irantheme.com/wp-content/uploads/2026/02/x4ublx9r.png',
                    'https://irantheme.com/wp-content/uploads/2026/02/iiqok2lp.png',
                    'https://irantheme.com/wp-content/uploads/2026/02/vv8h1392.png',
                    'https://irantheme.com/wp-content/uploads/2026/02/qweoqvwy.png',
                );

                // Set default value
                $links_default = $links;
                $images_default = $status_default === 'media' ?  Irantheme_Common_Static::get_file_url_media_library($images[$index]) : '';
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
                            'id'             => 'brand_url',
                            'label'            => __(
                                'لینک برند',
                                self::$static_textdomain

                            ),
                            'description'    => __('در صورت تمایل اگر می خواهید برند مورد نظر لینک داشته باشد می توانید لینک مورد نظر خود را وارد کنید.'),
                            'type'            => 'text',
                            'default'        => $links_default,
                            'placeholder'    => __('برای مثال :‌ https://irantheme.com', self::$static_textdomain),
                            'keywords' => array('لینک', 'لینک برند', 'برند', 'لوگو', 'همکاران', 'business brands', 'brand', 'link'),
                        ),
                        array(
                            'id'             => 'brand_bg',
                            'label'            => __('تصویر برند یا لوگو', self::$static_textdomain),
                            'description'    => __('لطفا تصویر خود را بارگذاری نمایید.', self::$static_textdomain),
                            'type'            => 'image',
                            'default'        => $images_default,
                            'placeholder'    => '',
                            'keywords' => array('تصویر', 'تصویر برند', 'برند', 'لوگو', 'آپلود', 'business brands', 'brand', 'image', 'upload', 'logo'),
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


            // Background options ------------------------------------------------------------
            $background_image_data = $fields_values['bg_image'];
            $background_color_data = $fields_values['bg_color'];

            $background_color_one_data = $fields_values['bg_gradient_first_color'];
            $background_color_two_data = $fields_values['bg_gradient_second_color'];
            $background_color_three_data = $fields_values['bg_gradient_third_color'];

            // Gradient option
            $gradient_checkbox
                = $fields_values['bg_gradient_checkbox'];
            $gradient_type
                = $fields_values['bg_gradient_type_radio'];
            $gradient_direction
                = $fields_values['bg_gradient_direction_radio'];
            // Check gradient status
            if ($gradient_checkbox != 'on') {
                // Get mono background color css
                $background_color_css = Irantheme_Section_Utils::get_background_color_css($background_color_data);
            } else if ($gradient_checkbox == 'on') {
                // Get gradient background color css
                $background_color_css = Irantheme_Common_Static::get_bg_gradient_color_css($background_color_one_data, $background_color_two_data, $background_color_three_data, $gradient_type, $gradient_direction);
            }


            $background_parallax_checkbox = $fields_values['bg_parallax_checkbox'];
            $background_image_css = Irantheme_Section_Utils::get_background_image_css($background_image_data);
            $background_parallax_attr = Irantheme_Section_Utils::get_background_parallax_attr($background_parallax_checkbox, $background_image_data);

            // Base color --------------------------------------------------------------
            $base_color = $fields_values['base_color_select'];
            // Set base color class
            $base_color_class = Irantheme_Common_Static::get_base_color_class($base_color);

            // Get columns count --------------------------------------------------------------
            $count_columns = $fields_values['col_count_radio'];
            $bootstrap_column_class = Irantheme_Section_Service::get_bootstrap_class($count_columns);
            $slider_mode_columns_data_attr = Irantheme_Section_Service::get_slider_data_column($count_columns);

            // Gradient animation ----------------------------------------------------------------------------
            $gradient_anim_checkbox
                = $fields_values['bg_animation_checkbox'];
            $gradient_anim_speed
                = $fields_values['bg_animation_speed_range'];
            $gradient_anim_zoom
                = $fields_values['bg_animation_zoom_range'];

            $gradient_animation_class = '';
            $gradient_animation_speed_css = '';
            $gradient_animation_bg_size_css = '';
            // Set gradient animation duration 
            switch ($gradient_anim_speed) {
                case '1':
                    $gradient_anim_speed = 30;
                    break;
                case '2':
                    $gradient_anim_speed = 27;
                    break;
                case '3':
                    $gradient_anim_speed = 24;
                    break;
                case '4':
                    $gradient_anim_speed = 21;
                    break;
                case '5':
                    $gradient_anim_speed = 18;
                    break;
                case '6':
                    $gradient_anim_speed = 15;
                    break;
                case '7':
                    $gradient_anim_speed = 12;
                    break;
                case '8':
                    $gradient_anim_speed = 9;
                    break;
                case '9':
                    $gradient_anim_speed = 6;
                    break;
                case '10':
                    $gradient_anim_speed = 3;
                    break;

                default:
                    $gradient_anim_speed = 15;
                    break;
            }

            if ($gradient_anim_checkbox == 'on') {
                $gradient_animation_class = 'irantheme-gradient-animation';
                $gradient_animation_speed_css = 'animation-duration:' . esc_attr($gradient_anim_speed) . 's;';
                $gradient_animation_bg_size_css = ' background-size:' . esc_attr($gradient_anim_zoom) . '% ' . esc_attr($gradient_anim_zoom) . '% !important;';
            }

            // Get product options checkbox --------------------------------------------------------------
            $radius_range = $fields_values['border_radius_range'];
            $radius_css = 'border-radius:' . $radius_range . 'rem;';

            // Get section id --------------------------------------------------------------
            $section_id = $fields_values['element_id'];
            $section_id_attribute = '';
            if ($section_id) {
                $section_id_attribute = sprintf('id=%s', $section_id);
            }

            // Set wrapper (Container) --------------------------------------------------------------
            $container_checkbox = $fields_values['wrapper_mode_checkbox'];

            // Set wrapper (Container inner) --------------------------------------------------------------
            $container_bg_checkbox = $fields_values['wrapper_mode_bg_checkbox'];

            // Get cell height --------------------------------------------------------------
            $cell_height = $fields_values['cell_height_range'];
            $cell_height_css = 'max-height:' . $cell_height . 'px;';
?>
            <!-- ============================= Section business brands post ============================= -->
            <section <?php echo esc_attr($section_id_attribute); ?> class="irantheme-section irantheme-business-brands-section" <?php echo esc_attr(Irantheme_Page_Builder::get_attr_data_section($data_type, $data_value, $data_page, $data_ajax)); ?> style="<?php echo esc_attr(Irantheme_Section_Service::get_custom_outer_padding_css($fields_values)); ?>">
                <?php
                if ($container_checkbox == 'off' && $container_bg_checkbox == 'on') :
                ?>
                    <!-- Inner container -->
                    <div class=" irantheme-container-inner">
                    <?php endif; ?>
                    <div class="irantheme-business irantheme-business-brands <?php echo esc_attr($base_color_class); ?> <?php echo esc_attr($gradient_animation_class); ?> irantheme-background-jarallax" style="<?php echo esc_attr($gradient_animation_speed_css); ?><?php echo esc_attr($gradient_animation_bg_size_css); ?><?php echo esc_attr($background_color_css); ?><?php echo esc_attr($background_image_css); ?><?php echo esc_attr(Irantheme_Section_Service::get_custom_inner_padding_css($fields_values)); ?><?php echo esc_attr($radius_css); ?>" <?php echo $background_parallax_attr; ?>>
                        <?php
                        if ($container_checkbox != 'on' && $container_bg_checkbox != 'on') :
                        ?>
                            <!-- Inner container -->
                            <div class=" irantheme-container-inner">
                            <?php endif; ?>

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
                            if ($tinymce_heading != '' && $heading_data_load_checkbox == 'on'):
                            ?>
                                <div class="irantheme-tinymce-heading-aos-holder" <?php echo ($heading_data_load_attr); ?> data-aos-delay="<?php echo ($heading_aos_delay); ?>" data-aos-duration="<?php echo ($heading_data_load_duration); ?>">
                                <?php
                            endif;
                                ?>
                                <?php
                                // Get tinymce content --------------------------------------------------------------
                                Irantheme_Common_Static::tinymce_heading($fields_values);
                                if ($tinymce_heading != '' && $heading_data_load_checkbox == 'on'):
                                ?>
                                </div>
                            <?php
                                endif;
                            ?>

                            <?php
                            // Set slider mode --------------------------------------------------------------
                            $slider_mode_checkbox = $fields_values['post_slider_mode_checkbox'];
                            $slider_mode_wrapper_class = '';
                            $slider_mode_row  = 'row justify-content-center align-items-center';
                            $slider_mode_column = $bootstrap_column_class;
                            if ($slider_mode_checkbox == 'on') {
                                // Init wrapper and row and item with default classes
                                $slider_mode_wrapper_class = 'swiper irantheme-business-brands-post-swiper';
                                $slider_mode_row  = 'swiper-wrapper align-items-center';
                                $slider_mode_column = 'swiper-slide';
                            }
                            ?>
                            <!-- Authors post -->
                            <div class="irantheme-business-brands-post-wrapper <?php echo esc_attr($slider_mode_wrapper_class); ?>" <?php echo ($slider_mode_columns_data_attr); ?>>
                                <div class="irantheme-object-group <?php echo esc_attr($slider_mode_row); ?>" data-json="<?php echo esc_attr('objects_json'); ?>">
                                    <?php
                                    // Loop form all services names
                                    foreach ($json_post_array as $object_post) :
                                        // Generate brand name & value
                                        $brand_url_value = $object_post['fields']['brand_url'];
                                        // Generate_brand_bg name & value
                                        $brand_bg_value = $object_post['fields']['brand_bg'];
                                    ?>
                                        <div class="irantheme-object <?php echo esc_attr($slider_mode_column); ?>" data-object="<?php echo esc_attr($object_post['object_id']); ?>">
                                            <!-- Post item -->
                                            <div class="irantheme-business-brands-post-item">
                                                <?php
                                                // Check brand image
                                                if ($brand_url_value) :
                                                ?>
                                                    <!-- Author image -->
                                                    <a href="<?php echo esc_attr($brand_url_value); ?>" class="irantheme-business-brands-post-item-anchor">
                                                    <?php endif; ?>
                                                    <?php
                                                    echo wp_get_attachment_image(
                                                        $brand_bg_value,
                                                        'medium',
                                                        false,
                                                        [
                                                            'class' => 'irantheme-business-brands-post-item-image-src',
                                                            'alt' => __('تصویر برند', self::$static_textdomain),
                                                            'loading' => 'lazy',
                                                            'style' => esc_attr($cell_height_css),
                                                        ]
                                                    );
                                                    ?>
                                                    <?php
                                                    // Check brand image
                                                    if ($brand_url_value) :
                                                    ?>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                            <?php
                                            Irantheme_Page_Builder::get_hidden_require_object_buttons($data_page);
                                            ?>
                                        </div>
                                    <?php
                                    endforeach;
                                    ?>
                                    <?php
                                    Irantheme_Page_Builder::get_hidden_require_object_group_buttons($data_page);
                                    ?>
                                </div>
                                <?php
                                if ($container_checkbox != 'on' && $container_bg_checkbox != 'on') :
                                ?>
                            </div>
                        <?php
                                endif;
                        ?>
                            </div>
                    </div>
                    <?php
                    if ($container_checkbox == 'off' && $container_bg_checkbox == 'on') :
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
