<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;

if (!class_exists('Irantheme_Section_Business_Box_Reviews')) {
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
    class Irantheme_Section_Business_Box_Reviews extends Irantheme_Section
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
            $heading_content = '<span style="font-size: 1em;">ــــــــــــ چقدر محبوب هستیم ــــــــــــ</span>
<h2 style="font-size: 2em;"><span style="font-size: 1.2em;"><strong>لطف مشتریان ما</strong></span></h2>';
            $settings = array(
                Irantheme_Section_Utils::heading_title_settings_generator($status_default, 'none', false, false, $heading_content, 'off', '', '', '', 'off', 'off', '', '', '', 'off', 'rectangle', 'center', 'off', '', 0, 1),
                array(
                    'title' => 'تنظیم محتوا',
                    'id' => 'posts',
                    'description'            => __('تنظیمات مربوط به محتوای نویسندگان', self::$static_textdomain),
                    'icon' => 'fa-folder-grid',
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
                            'default'        => $status_default ? Irantheme_Section_Default::get_default_ids_objects(6, 'business_box_reviews', $status_default) : '',
                        ),
                        array(
                            'id'             => 'col_count_radio',
                            'label'            => __('تعداد ستون ها', self::$static_textdomain),
                            'description'    => __('با استفاده از این گزینه شما می توانید تعداد ستون های قابل نمایش در این بخش را مشخص کنید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'thumbnail_status' => true,
                            'options'        => array(
                                'col-3' => array('سه ستون', 'col-3.png'),
                                'col-4' => array('چهار ستون', 'col-4.png'),
                                'col-6' => array('شش ستون', 'col-6.png'),
                            ),
                            'default'        => $status_default ? 'col-4' : '',
                            'keywords' => array('تعداد ستون ها', 'تعداد در هر ردیف', 'columns number', 'column number'),
                        ),
                    )
                ),
                array(
                    'title' => 'تنظیمات بصری',
                    'id' => 'layout',
                    'description'            => __('تنظیمات بصری مانند :ارتفاع، رنگ ها و...', self::$static_textdomain),
                    'icon' => 'fa-paintbrush-pencil',
                    'fields' => array(
                        array(
                            'id'             => 'base_color_select',
                            'label'            => __(
                                'رنگ پایه',
                                self::$static_textdomain
                            ),
                            'description'    => __('برای انتخاب رنگ پایه از لیست، رنگ دلخواه خود را انتخاب کنید.رنگ محتواو پس زمینه این بخش بر اساس این رنگ تنظیم خواهد شد.', self::$static_textdomain),
                            'type'            => 'select',
                            'options'        => array('white' => 'سفید', 'dark' => 'مشکی'),
                            'default'        => $status_default ? 'white' : '',
                            'keywords' => array('color', 'background', 'پس زمینه', 'رنگ'),
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
                            'keywords' => array('نگهدارنده خارجی', 'اتصال', 'fullscreen', 'تمام صفحه', 'نگه دارنده', 'نگهدارنده', 'container', 'wrapper'),
                        ),
                        array(
                            'id'             => 'alignment_content_radio',
                            'label'            => __('موقعیت محتوا دیدگاه ها', self::$static_textdomain),
                            'description'    => __('با استفاده از این گزینه می توانید موقعیت محتوای داخل دیدگاه ها را تعیین کنید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'thumbnail_status' => true,
                            'options'        => array('right' => array('راست چین', 'text-align-right.png'), 'center' => array('وسط چین', 'text-align-center.png'), 'left' => array('چپ چین', 'text-align-left.png')),
                            'default'        => $status_default ? 'right' : '',
                            'keywords' => array('موقعیت محتوا دیدگاه ها', 'business box reviews alignment content', 'review alignment')
                        ),
                        array(
                            'id'             => 'box_style_radio',
                            'label'            => __(
                                'استایل نمایش',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید نحوه نمایش در سبک و استایل های مختلف را مشخص کنید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'options'        => array('none' => 'ساده', 'border' => 'خط دور', 'shadow' => 'سایه'),
                            'default'        => 'shadow',
                            'keywords' => array('استایل نمایش', 'box style')
                        ),
                        array(
                            'id'             => 'star_mode_checkbox',
                            'label'            => __(
                                'نمایش امتیاز',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید نمایش و یا عدم نمایش امتیاز وارد شده برای هر دیدگاه در بخش دیدگاه مشتریان را تعیین کنید.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'on' : '',
                            'keywords' => array('نمایش امتیاز', 'display star', 'show star'),
                        ),
                    )
                ),
                // Get layout scale fields
                Irantheme_Section_Utils::layout_scale_fields_generator($status_default, array('radius', 'inner_padding', 'outer_padding'), 'on', 2, 2, 'off', 'off', 0, 0, 1),
                // Get background fields
                Irantheme_Section_Utils::background_fields_generator($status_default, array('image', 'color', 'mask', 'parallax'), '', 'off', true, 'off', 'dark', 0.6, 'off'),
                // Get slider fields
                Irantheme_Section_Utils::slider_fields_generator($status_default, array('slider_status', 'arrow', 'pagination'), 'on', 'off', 'on'),
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
            $names_default = '';
            $small_title_default = '';
            $images_default = '';
            $content_default = '';

            // Check status is deafult or no
            if (is_numeric($index)) {
                // List of default titles
                $names = array(
                    'علی وثوقی',
                    'داوود حسینی',
                    'بردیا احمدی',
                    'حسین سنایی',
                    'کوروش کمیلی',
                    'ابراهیم موسوی',
                );

                // List of small titles of box reviews
                $small_title = array(
                    'روزنامه نگار',
                    'بلاگر فناوری',
                    'مدیر سایت افرا',
                    'طراح لباس',
                    'مدیر عامل لاکسر',
                    'طراح گرافیست',
                );

                // List of box reviews images
                $images = array(
                    'https://irantheme.com/wp-content/uploads/2026/02/0jhs4j07.webp',
                    'https://irantheme.com/wp-content/uploads/2026/02/55b59eqg.webp',
                    'https://irantheme.com/wp-content/uploads/2026/02/e0mtukq3.webp',
                    'https://irantheme.com/wp-content/uploads/2026/02/iby33ip2.webp',
                    'https://irantheme.com/wp-content/uploads/2026/02/wobmvrr8.webp',
                    'https://irantheme.com/wp-content/uploads/2026/02/aeafa51v.webp',
                );

                // Default content
                $content = 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک است، چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است، و برای شرایط فعلی تکنولوژی مورد نیاز، و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد، کتابهای زیادی در شصت و سه درصد گذشته حال و آینده، شناخت فراوان جامعه و متخصصان را می طلبد.';

                // Set default value
                $names_default = $names[$index];
                $small_title_default = $small_title[$index];
                $images_default = $status_default === 'media' ? Irantheme_Common_Static::get_file_url_media_library($images[$index]) : '';
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
                            'id'             => 'image',
                            'label'            => __('تصویر شخص', self::$static_textdomain),
                            'description'    => __('در صورت تمایل می توانید تصویر شخص مورد نظر را بارگزاری نمایید.', self::$static_textdomain),
                            'type'            => 'image',
                            'default'        => $images_default,
                            'placeholder'    => '',
                        ),
                        array(
                            'id'             => 'title',
                            'label'            => __(
                                'عنوان',
                                self::$static_textdomain

                            ),
                            'description'    => __('نام یا لقب شخص مورد نظر را وارد کنید.'),
                            'type'            => 'text',
                            'default'        => $names_default,
                            'placeholder'    => __('عنوان', self::$static_textdomain),
                        ),
                        array(
                            'id'             => 'small_title',
                            'label'            => __(
                                'عنوان کوچک',
                                self::$static_textdomain

                            ),
                            'description'    => __('می توانید در این قسمت تخصص یا جایگاه و یا سایت فرد مورد نظر را عنوان کنید.'),
                            'type'            => 'text',
                            'default'        => $small_title_default,
                            'placeholder'    => __('عنوان کوچک', self::$static_textdomain),
                        ),
                        array(
                            'id'             => 'desc',
                            'label'            => __(
                                'توضیحات',
                                self::$static_textdomain
                            ),
                            'description'    => __('نقطه نظر فرد در مورد محصول یا کسب و کارتان را بنویسید.', self::$static_textdomain),
                            'type'            => 'textarea',
                            'default'        => $content_default,
                            'placeholder'    => __('توضیحات را وارد کنید', self::$static_textdomain),
                        ),
                        array(
                            'id'             => 'star',
                            'label'            => __(
                                'مقدار امتیاز فرد به شما',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه شما می توانید امتیاز شخص مورد نظر به کسب و کار یا محصول خودتان از ۱ تا ۵ را مشخص نمایید.', self::$static_textdomain),
                            'type'            => 'range',
                            'min' => 1,
                            'max' => 5,
                            'step' => 1,
                            'default'        => 5,
                            'placeholder'    => __('', self::$static_textdomain),
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
            $aos_delay =
                Irantheme_Section_Utils::get_data_load_delay($fields_values);

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

            // Get border radius --------------------------------------------------------------
            $border_radius_range = $fields_values['border_radius_range'];
            $border_radius_style = Irantheme_Section_Utils::get_border_radius_css($border_radius_range);

            // Base color --------------------------------------------------------------
            $base_color = $fields_values['base_color_select'];
            // Set base color class
            $base_color_class = Irantheme_Common_Static::get_base_color_class($base_color);

            // Get columns count --------------------------------------------------------------
            $count_columns = $fields_values['col_count_radio'];
            $bootstrap_column_class = Irantheme_Section_Service::get_bootstrap_class($count_columns);
            $slider_mode_columns_data_attr = Irantheme_Section_Service::get_slider_data_column($count_columns);

            // Custom color
            $box_style_class = '';
            // Get header background color
            $header_style_option = $fields_values['box_style_radio'];
            if ($header_style_option == 'none') {
                $box_style_class = 'irantheme-box-style-none';
            } else if ($header_style_option == 'border') {
                $box_style_class = 'irantheme-box-style-border';
            } else if ($header_style_option == 'shadow') {
                $box_style_class = 'irantheme-box-style-shadow';
            }

            // Get content alignment --------------------------------------------------------------
            $alignment_content = $fields_values['alignment_content_radio'];

            // Get section id --------------------------------------------------------------
            $section_id = $fields_values['element_id'];
            $section_id_attribute = '';
            if ($section_id) {
                $section_id_attribute = sprintf('id=%s', $section_id);
            }
?>
            <!-- ============================= Section business authors post ============================= -->
            <section <?php echo esc_attr($section_id_attribute); ?> class="irantheme-section irantheme-business-box-reviews-section irantheme-skeleton-section" <?php echo esc_attr(Irantheme_Page_Builder::get_attr_data_section($data_type, $data_value, $data_page, $data_ajax)); ?> style="<?php echo esc_attr(Irantheme_Section_Service::get_custom_outer_padding_css($fields_values)); ?>">
                <div class="irantheme-business irantheme-business-box-reviews <?php echo esc_attr($base_color_class); ?> irantheme-background-jarallax" style="<?php echo esc_attr($background_color_css); ?><?php echo esc_attr($background_image_css); ?><?php echo esc_attr(Irantheme_Section_Service::get_custom_inner_padding_css($fields_values)); ?>" <?php echo $background_parallax_attr; ?>>

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
                        <!-- Inner container -->
                        <div class=" irantheme-container-inner">
                        <?php endif; ?>
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
                        $slider_mode_checkbox = $fields_values['slider_checkbox'];
                        $slider_mode_wrapper_class = '';
                        $slider_mode_row  = 'row justify-content-center';
                        $slider_mode_column = $bootstrap_column_class;
                        if ($slider_mode_checkbox == 'on') {
                            // Init wrapper and row and item with default classes
                            $slider_mode_wrapper_class = 'swiper irantheme-business-box-reviews-post-swiper';
                            $slider_mode_row  = 'swiper-wrapper';
                            $slider_mode_column = 'swiper-slide';
                        }
                        ?>

                        <?php
                        $skeleton_load_class = '';
                        if ($slider_mode_checkbox == 'on') {
                            // Skeleton html --------------------------------------------------------------
                            irantheme_get_skeleton_html($count_columns, 'card', $base_color_class);
                            $skeleton_load_class = 'irantheme-side-load-skeleton';
                        }
                        ?>

                        <!-- Authors post -->
                        <div class="irantheme-business-box-reviews-post-wrapper <?php echo esc_attr($slider_mode_wrapper_class); ?> <?php echo esc_attr($skeleton_load_class); ?>" <?php echo ($slider_mode_columns_data_attr); ?>>
                            <div class="irantheme-object-group <?php echo esc_attr($slider_mode_row); ?>" data-json="<?php echo esc_attr('objects_json'); ?>">
                                <?php
                                foreach ($json_post_array as $object_post) :
                                    // Get post meta --------------------------------------------------------------
                                    // Author image
                                    $viewpoint_image = $object_post['fields']['image'];
                                    // Author title
                                    $viewpoint_title =
                                        $object_post['fields']['title'];
                                    // Author skills
                                    $viewpoint_skills =
                                        $object_post['fields']['small_title'];
                                    // Author description
                                    $viewpoint_description =
                                        $object_post['fields']['desc'];
                                    // Author details url
                                    $count_of_stars =
                                        $object_post['fields']['star'];
                                ?>
                                    <div class="irantheme-object <?php echo esc_attr($slider_mode_column); ?> mb-0" data-object="<?php echo esc_attr($object_post['object_id']); ?>">
                                        <!-- Post item -->
                                        <div class="irantheme-business-box-reviews-post-item <?php echo esc_attr($box_style_class); ?> <?php echo esc_attr(Irantheme_Section_Service::get_flex_column_alignment_class($alignment_content)); ?>" style="<?php echo esc_attr($border_radius_style); ?>" <?php echo ($data_load_attr); ?> data-aos-delay="<?php echo ($aos_delay); ?>" data-aos-duration="<?php echo ($data_load_duration); ?>">
                                            <!-- Content -->
                                            <div class="irantheme-business-box-reviews-post-item-content <?php echo esc_attr(Irantheme_Section_Service::get_alignment_class($alignment_content)); ?>">
                                                <?php
                                                // Description display --------------------------------------------------------------
                                                if ($viewpoint_description) :
                                                ?>
                                                    <!-- Description -->
                                                    <p class="irantheme-business-box-reviews-post-item-content-desc"><?php echo esc_html($viewpoint_description); ?></p>
                                                <?php endif; ?>
                                            </div>
                                            <!-- Author image -->
                                            <div class="irantheme-business-box-reviews-post-item-image">
                                                <?php
                                                // check author image
                                                if ($viewpoint_image) :
                                                ?>
                                                    <?php echo wp_get_attachment_image(
                                                        $viewpoint_image,
                                                        'thumbnail',
                                                        false,
                                                        [
                                                            'class' => 'irantheme-business-box-reviews-post-item-image-src',
                                                            'alt' => __('تصویر کاربر', self::$static_textdomain),
                                                            'loading' => 'lazy',
                                                        ]
                                                    ); ?>
                                                <?php endif; ?>
                                                <?php if ($viewpoint_title || $viewpoint_skills) : ?>
                                                    <!-- Heading -->
                                                    <div class="irantheme-business-box-reviews-post-item-image-heading">
                                                        <!-- Title -->
                                                        <h4 class="irantheme-business-box-reviews-post-item-image-heading-title"><?php echo esc_html($viewpoint_title); ?></h4>
                                                        <?php if ($viewpoint_skills) : ?>
                                                            <!-- Skills -->
                                                            <p class="irantheme-business-box-reviews-post-item-image-heading-skills"><?php echo esc_html($viewpoint_skills); ?></p>
                                                        <?php endif; ?>
                                                        <?php
                                                        $star_checkbox = $fields_values['star_mode_checkbox'];
                                                        // Link mode display --------------------------------------------------------------
                                                        if ($star_checkbox == 'on') :
                                                        ?>
                                                            <!-- Details -->
                                                            <div class="irantheme-business-box-reviews-post-item-image-heading-star">
                                                                <div class="irantheme-business-box-reviews-post-item-image-heading-star-holder">
                                                                    <?php
                                                                    // Counter star
                                                                    for ($i = 0; $i < $count_of_stars; $i++) :
                                                                    ?>
                                                                        <i class="irantheme-business-box-reviews-post-item-image-heading-star-icon fa-solid fa-star"></i>
                                                                    <?php
                                                                    endfor; ?>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
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
                            <?php
                            // Slider pagination option
                            $slider_pagination_checkbox = $fields_values['slider_pagination_checkbox'];
                            // Slider arrow option
                            $slider_arrow_checkbox = $fields_values['slider_arrow_checkbox'];
                            Irantheme_Section_Utils::get_slider_arrow_pagination($slider_mode_checkbox, $slider_arrow_checkbox, $slider_pagination_checkbox, 'white', $border_radius_range, 'bottom', 'center');
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
