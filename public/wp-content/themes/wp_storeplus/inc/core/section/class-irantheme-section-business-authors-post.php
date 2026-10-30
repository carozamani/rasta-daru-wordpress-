<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;

if (!class_exists('Irantheme_Section_Business_Authors_Post')) {
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
    class Irantheme_Section_Business_Authors_Post extends Irantheme_Section
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
            $heading_content = '<span style="font-size: 1em;">ــــــــــــ کادر مدیریتی ما ــــــــــــ</span>
<h2 style="font-size: 2em;"><span style="font-size: 1.2em;"><strong>تیم طراحی و توسعه</strong></span></h2>';
            $settings = array(
                Irantheme_Section_Utils::heading_title_settings_generator($status_default, 'none', 'off', false, $heading_content, 'off', '', '', '', 'off', 'off', '', '', '', 'off', 'rectangle', 'center', 'off', '', 0, 1),
                array(
                    'title' => 'تنظیم محتوا',
                    'id' => 'posts',
                    'description'            => __('تنظیمات مربوط به محتوای نویسندگان', self::$static_textdomain),
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
                            'default'        => $status_default ? Irantheme_Section_Default::get_default_ids_objects(5, 'business_authors_post', $status_default) : '',
                        ),
                        array(
                            'id'             => 'col_count_radio',
                            'label'            => __('تعداد ستون ها', self::$static_textdomain),
                            'description'    => __('با استفاده از این گزینه شما می توانید تعداد ستون قابل نمایش برای محتوای انتخابی در بخش را مشخص کنید', self::$static_textdomain),
                            'type'            => 'radio',
                            'thumbnail_status' => true,
                            'options'        => array(
                                'col-3' => array('سه ستون', 'col-3.png'),
                                'col-4' => array('چهار ستون', 'col-4.png'),
                                'col-6' => array('شش ستون', 'col-6.png')
                            ),
                            'default'        => $status_default ? 'col-3' : '',
                            'keywords' => array('business', 'col', 'author', 'تعداد', 'سرویس', 'تعداد در هر ردیف'),
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
                            'id'             => 'alignment_radio',
                            'label'            => __('موقعیت محتوا', self::$static_textdomain),
                            'description'    => __('با استفاده از این گزینه می توانید موقعیت قرارگیری محتوای پست ها را تعیین کنید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'thumbnail_status' => true,
                            'options'        => array('right' => array('راست چین', 'text-align-right.png'), 'center' => array('وسط چین', 'text-align-center.png'), 'left' => array('چپ چین', 'text-align-left.png')),
                            'default'        => $status_default ? 'center' : '',
                            'keywords' => array('محتوا', 'تنظیم', 'تراز', 'محور', 'alignment', 'float', 'position')
                        ),
                        array(
                            'id'             => 'box_style_checkbox',
                            'label'            => __(
                                'باکس استایل',
                                self::$static_textdomain
                            ),
                            'description'    => __('در زمان روشن بودن این گزینه باکس های نویسنده با یک پس زمینه که بر اساس رنگ پایه تنظیم شده اند نمایش داده خواهند شد.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'on' : '',
                            'keywords' => array('business', 'نویسنده', 'author', 'باکس', 'نویسندگان', 'باکس استایل', 'box style'),
                        ),
                        array(
                            'id'             => 'border_checkbox',
                            'label'            => __(
                                'خط دور عناصر',
                                self::$static_textdomain
                            ),
                            'description'    => __('در زمان روشن بودن این گزینه یه خط (Border) دور پست مربوط به هر نویسنده نمایش داده می شود.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'off' : '',
                            'keywords' => array('business', 'نویسنده', 'author', 'خط', 'نویسندگان', 'بوردر', 'border'),
                        ),
                        array(
                            'id'             => 'shadow_checkbox',
                            'label'            => __(
                                'سایه دور پست',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید وضعیت نمایش یا عدم نمایش سایه دور هر نوشته(پست) را مشخص نمایید.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'off' : '',
                            'keywords' => array('hieght', 'ارتفاع', 'fullscreen', 'تمام صفحه', 'ارتباطات'),
                        ),
                        array(
                            'id'             => 'padding_checkbox',
                            'label'            => __(
                                'فاصله دور عناصر',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید حاشیه ای به دور از تصویر و محتوای پست ایجاد کنید.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'on' : '',
                            'keywords' => array('فاصله دور عناصر', 'padding checkbox'),
                        ),
                    )
                ),
                // Get layout scale fields
                Irantheme_Section_Utils::layout_scale_fields_generator($status_default, array('radius', 'inner_padding', 'outer_padding'), 'on', 2, 2, 'off', 'off', 0, 0, 1),
                // Get background fields
                Irantheme_Section_Utils::background_fields_generator($status_default, array('image', 'color', 'mask', 'parallax'), '', 'off', true, 'off', 'dark', 0.6, 'off'),
                array(
                    'title' => 'امکانات',
                    'id' => 'additional',
                    'description'            => __('فعال یا غیر فعال کردن امکانات سرویس', self::$static_textdomain),
                    'icon' => 'fa-puzzle-piece',
                    'fields' => array(
                        array(
                            'id'             => 'description_mode_checkbox',
                            'label'            => __(
                                'نمایش توضیحات',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید نمایش و یا عدم نمایش توصیحات وارد شده برای هر نویسنده در بخش نویسنگان را تعیین کنید.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => 'on',
                            'keywords' => array('business', 'نویسنده', 'author', 'توضیحات', 'نویسندگان'),
                        ),
                        array(
                            'id'             => 'link_mode_checkbox',
                            'label'            => __(
                                'نمایش لینک',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید نمایش و یا عدم نمایش لینک وارد شده برای هر نویسنده در بخش نویسنگان را تعیین کنید.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => 'on',
                            'keywords' => array('business', 'نویسنده', 'author', 'لینک', 'نویسندگان'),
                            'id_dependents_on' => array('link_color_select'),
                            'id_dependents_off' => array(),
                            'base_priority' => 'checkbox_base',
                            'selector_priority' => 'checkbox_link_color',
                            'z_index' => 0
                        ),
                        array(
                            'id'             => 'link_color_select',
                            'label'            => __(
                                'رنگ لینک',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید رنگ مورد نظر خود برای لینک وارد شده برای نویسندگان را انتخاب کنید.', self::$static_textdomain),
                            'type'            => 'select',
                            'color_preview' => true,
                            'options'        => Irantheme_Color_Static::get_all_colors_options(),
                            'default'        => 'primary',
                            'keywords' => array('رنگ لینک', 'رنگ', 'link', 'لینک'),
                        ),
                    )
                ),
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
                );

                // List of small titles of authors
                $small_title = array(
                    'توسعه دهنده وب',
                    'طراح گرافیک',
                    'مدیر پشتیبانی',
                    'مدیر فروش',
                    'کارشناس امنیت',
                );

                // List of authors images
                $images = array(
                    'https://irantheme.com/wp-content/uploads/2026/02/6arg4gnt.webp',
                    'https://irantheme.com/wp-content/uploads/2026/02/74rtgrzm.webp',
                    'https://irantheme.com/wp-content/uploads/2026/02/anfjiax4.webp',
                    'https://irantheme.com/wp-content/uploads/2026/02/fqsrarte.webp',
                    'https://irantheme.com/wp-content/uploads/2026/02/wv1vkecs.webp'
                );

                // Set default value
                $names_default = $names[$index];
                $small_title_default = $small_title[$index];
                $images_default = $status_default === 'media' ? Irantheme_Common_Static::get_file_url_media_library($images[$index]) : '';
                $content_default = '';
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
                            'description'    => __('می توانید در این قسمت تخصص یا جایگاه فرد مورد نظر را عنوان کنید.'),
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
                            'description'    => __('توضیحات مربوط به تخصص یا فرد مورد نظر را بنویسید.', self::$static_textdomain),
                            'type'            => 'textarea',
                            'default'        => $content_default,
                            'placeholder'    => __('توضیحات را وارد کنید', self::$static_textdomain),
                        ),
                        array(
                            'id'             => 'details_url',
                            'label'            => __(
                                'آدرس جزئیات نویسنده',
                                self::$static_textdomain

                            ),
                            'description'    => __('آدرس دلخواه در مورد جزئیات فرد را در صورت تمایل وارد کنید. برای مثال آدرس پروفایل فرد.'),
                            'type'            => 'text',
                            'default'        => '',
                            'placeholder'    => __('لینک دکمه', self::$static_textdomain),
                        ),
                        array(
                            'id'             => 'social_icon_one',
                            'label'            => __(
                                'آیکون شبکه اجتماعی',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت تمایل به تعریف لینک شبکه های اجتماعی فرد مربوطه می توانید از طریق این گزینه آیکون مربوط به شبکه اجتماعی مورد نظر خود را انتخاب کنید.', self::$static_textdomain),
                            'type'            => 'icon',
                            'default'        => 'fa-brands fa-instagram',
                            'placeholder'    => __('', self::$static_textdomain),
                        ),
                        array(
                            'id'             => 'social_url_one',
                            'label'            => __(
                                'لینک شبکه اجتماعی',
                                self::$static_textdomain

                            ),
                            'description'    => __('آدرس مربوط به پروفایل فرد مورد نظر در شبکه اجتماعی را اینجا وارد کنید.'),
                            'type'            => 'text',
                            'default'        => '#',
                            'placeholder'    => __('لینک دلخواه', self::$static_textdomain),
                        ),
                        array(
                            'id'             => 'social_icon_two',
                            'label'            => __(
                                'آیکون شبکه اجتماعی',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت تمایل به تعریف لینک شبکه های اجتماعی فرد مربوطه می توانید از طریق این گزینه آیکون مربوط به شبکه اجتماعی مورد نظر خود را انتخاب کنید.', self::$static_textdomain),
                            'type'            => 'icon',
                            'default'        => 'fa-brands fa-telegram',
                            'placeholder'    => __('', self::$static_textdomain),
                        ),
                        array(
                            'id'             => 'social_url_two',
                            'label'            => __(
                                'لینک شبکه اجتماعی',
                                self::$static_textdomain
                            ),
                            'description'    => __('آدرس مربوط به پروفایل فرد مورد نظر در شبکه اجتماعی را اینجا وارد کنید.'),
                            'type'            => 'text',
                            'default'        => '#',
                            'placeholder'    => __('لینک دلخواه', self::$static_textdomain),
                        ),
                        array(
                            'id'             => 'social_icon_three',
                            'label'            => __(
                                'آیکون شبکه اجتماعی',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت تمایل به تعریف لینک شبکه های اجتماعی فرد مربوطه می توانید از طریق این گزینه آیکون مربوط به شبکه اجتماعی مورد نظر خود را انتخاب کنید.', self::$static_textdomain),
                            'type'            => 'icon',
                            'default'        => 'fa-brands fa-x-twitter',
                            'placeholder'    => __('', self::$static_textdomain),
                        ),
                        array(
                            'id'             => 'social_url_three',
                            'label'            => __(
                                'لینک شبکه اجتماعی',
                                self::$static_textdomain
                            ),
                            'description'    => __('آدرس مربوط به پروفایل فرد مورد نظر در شبکه اجتماعی را اینجا وارد کنید.'),
                            'type'            => 'text',
                            'default'        => '#',
                            'placeholder'    => __('لینک دلخواه', self::$static_textdomain),
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
            $background_color_css = Irantheme_Section_Utils::get_background_color_css($background_color_data);
            $background_image_css = Irantheme_Section_Utils::get_background_image_css($background_image_data);
            $background_parallax_checkbox = $fields_values['bg_parallax_checkbox'];
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

            // Get columns of authors --------------------------------------------------------------
            $count_columns = $fields_values['col_count_radio'];
            $bootstrap_column_class = Irantheme_Section_Service::get_bootstrap_class($count_columns);
            $slider_mode_columns_data_attr = Irantheme_Section_Service::get_slider_data_column($count_columns);

            // Get border radius --------------------------------------------------------------
            $border_radius_range = $fields_values['border_radius_range'];
            $border_radius_style = Irantheme_Section_Utils::get_border_radius_css($border_radius_range);

            // Transform heading to columns --------------------------------------------------------------
            $transform_heading_checkbox = $fields_values['heading_transfer_checkbox'];

            // Get section id --------------------------------------------------------------
            $section_id = $fields_values['element_id'];
            $section_id_attribute = '';
            if ($section_id) {
                $section_id_attribute = sprintf('id=%s', $section_id);
            }
?>
            <!-- ============================= Section business authors post ============================= -->
            <section <?php echo esc_attr($section_id_attribute); ?> class="irantheme-section irantheme-business-authors-post-section <?php echo esc_attr($skeleton_section_class); ?>" <?php echo esc_attr(Irantheme_Page_Builder::get_attr_data_section($data_type, $data_value, $data_page, $data_ajax)); ?> style="<?php echo esc_attr(Irantheme_Section_Service::get_custom_outer_padding_css($fields_values)); ?>">
                <div class="irantheme-business irantheme-business-authors-post <?php echo esc_attr($base_color_class); ?> irantheme-background-jarallax" style="<?php echo esc_attr($background_color_css); ?><?php echo esc_attr($background_image_css); ?><?php echo esc_attr(Irantheme_Section_Service::get_custom_inner_padding_css($fields_values)); ?>" <?php echo $background_parallax_attr; ?>>

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
                        // Transfer to col
                        if ($transform_heading_checkbox == 'off') :
                        ?>
                            <?php
                            if ($tinymce_heading != '' && $heading_data_load_checkbox == 'on'):
                            ?>
                                <div class="irantheme-tinymce-heading-aos-holder" <?php echo ($heading_data_load_attr); ?> data-aos-delay="<?php echo ($heading_aos_delay); ?>" data-aos-duration="<?php echo ($heading_data_load_duration); ?>">
                                <?php
                            endif;
                                ?>
                                <?php
                                // Get heading content --------------------------------------------------------------
                                Irantheme_Common_Static::tinymce_heading($fields_values);
                                if ($tinymce_heading != '' && $heading_data_load_checkbox == 'on'):
                                ?>
                                </div>
                            <?php
                                endif;
                            ?>
                        <?php
                        endif;
                        ?>

                        <?php
                        // Set slider mode --------------------------------------------------------------
                        $slider_mode_checkbox = $fields_values['slider_checkbox'];
                        // Init wrapper and row and item with default classes
                        $slider_mode_wrapper_class = '';
                        $slider_mode_row  = 'row justify-content-center';
                        $slider_mode_column = $bootstrap_column_class;
                        if ($slider_mode_checkbox == 'on') {
                            // If slider is true, assign new swiper classes to wrapper, row and item
                            $slider_mode_wrapper_class = 'swiper irantheme-business-authors-post-swiper';
                            $slider_mode_row  = 'swiper-wrapper';
                            $slider_mode_column = 'swiper-slide';
                        }

                        // Set item alignment --------------------------------------------------------------
                        $business_item_alignment = $fields_values['alignment_radio'];
                        ?>

                        <?php
                        $skeleton_load_class = '';
                        if ($slider_mode_checkbox == 'on' && $data_load_checkbox != 'on' && $heading_data_load_checkbox != 'on') {
                            // Skeleton html --------------------------------------------------------------
                            irantheme_get_skeleton_html($count_columns, 'card', $base_color_class);
                            $skeleton_load_class = 'irantheme-side-load-skeleton';
                        }
                        ?>

                        <!-- Authors post -->
                        <div class="irantheme-business-authors-post-wrapper <?php echo esc_attr($slider_mode_wrapper_class); ?> <?php echo esc_attr($skeleton_load_class); ?>" <?php echo ($slider_mode_columns_data_attr); ?>>
                            <div class="irantheme-object-group <?php echo esc_attr($slider_mode_row); ?>" data-json="<?php echo esc_attr('objects_json'); ?>">
                                <?php
                                // Transfer to col
                                if ($transform_heading_checkbox == 'on') :
                                ?>
                                    <div class="<?php echo esc_attr($slider_mode_column); ?> d-flex align-items-center">
                                        <?php
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
                                        ?>
                                    </div>
                                <?php
                                endif;
                                ?>

                                <?php
                                // Service custom fields --------------------------------------------------------------
                                foreach ($json_post_array as $object_post) :
                                    // Get post meta --------------------------------------------------------------
                                    // Author image
                                    $author_image = $object_post['fields']['image'];
                                    // Author title
                                    $author_title = $object_post['fields']['title'];
                                    // Author skills
                                    $author_skills = $object_post['fields']['small_title'];
                                    // Author description
                                    $author_description = $object_post['fields']['desc'];
                                    // Author details url
                                    $author_details_url = $object_post['fields']['details_url'];
                                    // Author social one
                                    $author_icon_one = $object_post['fields']['social_icon_one'];
                                    $author_url_one = $object_post['fields']['social_url_one'];
                                    // Author social two
                                    $author_icon_two = $object_post['fields']['social_icon_two'];
                                    $author_url_two = $object_post['fields']['social_url_two'];
                                    // Author social three
                                    $author_icon_three = $object_post['fields']['social_icon_three'];
                                    $author_url_three = $object_post['fields']['social_url_three'];
                                ?>
                                    <div class="irantheme-object <?php echo esc_attr($slider_mode_column); ?>" data-object="<?php echo esc_attr($object_post['object_id']); ?>">
                                        <?php
                                        // Set box style --------------------------------------------------------------
                                        $business_box_style = $fields_values['box_style_checkbox'];
                                        $business_box_style_class = '';
                                        if ($business_box_style == 'on') {
                                            $business_box_style_class = 'irantheme-business-authors-post-item-box-style';
                                        }
                                        // Set border--------------------------------------------------------------
                                        $business_post_border = $fields_values['border_checkbox'];
                                        $business_border_class = '';
                                        if ($business_post_border == 'on') {
                                            $business_border_class = 'irantheme-business-authors-post-item-border';
                                        }
                                        // Set shadow--------------------------------------------------------------
                                        $business_post_shadow = $fields_values['shadow_checkbox'];
                                        $business_shadow_class = '';
                                        if ($business_post_shadow == 'on') {
                                            $business_shadow_class = 'irantheme-business-authors-post-item-shadow';
                                        }
                                        // Set padding--------------------------------------------------------------
                                        $business_post_padding = $fields_values['padding_checkbox'];
                                        $business_padding_class = '';
                                        $image_border_radius_css = '';
                                        if ($business_post_padding == 'on') {
                                            $business_padding_class = 'irantheme-business-authors-post-item-padding';
                                            $image_border_radius_css = $border_radius_style;
                                        }
                                        ?>
                                        <!-- Post item -->
                                        <div class="irantheme-business-authors-post-item <?php echo esc_attr(Irantheme_Section_Service::get_alignment_class($business_item_alignment)); ?> <?php echo esc_attr($business_box_style_class); ?> <?php echo esc_attr($business_shadow_class); ?> <?php echo esc_attr($business_border_class); ?> <?php echo esc_attr($business_padding_class); ?>" style="<?php echo esc_attr($border_radius_style); ?>" <?php echo ($data_load_attr); ?> data-aos-delay="<?php echo ($aos_delay); ?>" data-aos-duration="<?php echo ($data_load_duration); ?>">
                                            <?php
                                            // check author image
                                            if ($author_image) :
                                            ?>
                                                <!-- Author image -->
                                                <div class="irantheme-business-authors-post-item-image">
                                                    <?php if ($author_details_url) : ?>
                                                        <a href="<?php echo esc_attr($author_details_url); ?>" class="irantheme-business-authors-post-item-image-anchor">
                                                        <?php endif; ?>
                                                        <?php echo wp_get_attachment_image(
                                                            $author_image,
                                                            'medium_large',
                                                            false,
                                                            [
                                                                'class' => 'irantheme-business-authors-post-item-image-src',
                                                                'alt' => __('تصویر نویسنده', self::$static_textdomain),
                                                                'loading' => 'lazy',
                                                                'width' => '392',
                                                                'height' => '220',
                                                                'style' => $image_border_radius_css,
                                                            ]
                                                        ); ?>
                                                        <?php if ($author_details_url) : ?>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                            <!-- Content -->
                                            <div class="irantheme-business-authors-post-item-content">
                                                <?php if ($author_title || $author_skills) : ?>
                                                    <!-- Heading -->
                                                    <div class="irantheme-business-authors-post-item-content-heading">
                                                        <?php if ($author_details_url) : ?>
                                                            <a href="<?php echo esc_attr($author_details_url); ?>" class="irantheme-business-authors-post-item-content-heading-anchor">
                                                            <?php endif; ?>
                                                            <!-- Title -->
                                                            <h4 class="irantheme-business-authors-post-item-content-heading-title"><?php echo esc_html($author_title); ?></h4>
                                                            <?php if ($author_details_url) : ?>
                                                            </a>
                                                        <?php endif; ?>
                                                        <?php if ($author_skills) : ?>
                                                            <!-- Skills -->
                                                            <p class="irantheme-business-authors-post-item-content-heading-skills"><?php echo esc_html($author_skills); ?></p>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endif; ?>
                                                <?php
                                                // Description display --------------------------------------------------------------
                                                $description_checkbox = $fields_values['description_mode_checkbox'];
                                                if ($author_description && $description_checkbox == 'on') :
                                                ?>
                                                    <!-- Description -->
                                                    <p class="irantheme-business-authors-post-item-content-desc"><?php echo esc_html($author_description); ?></p>
                                                <?php endif; ?>
                                                <?php
                                                // Link mode display --------------------------------------------------------------
                                                $details_link_checkbox = $fields_values['link_mode_checkbox'];
                                                if ($author_details_url && $details_link_checkbox == 'on') :
                                                ?>
                                                    <!-- Details -->
                                                    <div class="irantheme-business-authors-post-item-content-details">
                                                        <?php
                                                        // Get service bg color --------------------------------------------------------------
                                                        $details_link_color = $fields_values['link_color_select'];
                                                        $details_link_color_css = '';
                                                        if ($details_link_color != 'off') {
                                                            $details_link_color_css = 'color:#' . esc_attr(Irantheme_Color_Static::get_hex_color($details_link_color)) . ';';
                                                        }
                                                        ?>
                                                        <!-- Read more -->
                                                        <a href="<?php echo esc_html($author_details_url); ?>" class="irantheme-business-authors-post-item-content-details-link" style="<?php echo esc_attr($details_link_color_css); ?>"><?php _e('جزئیات بیشتر', IRANTHEME_TEXTDOMAIN); ?></a>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <?php
                                            // Social media --------------------------------------------------------------
                                            if ($author_icon_one || $author_icon_two || $author_icon_three) :
                                            ?>
                                                <!-- Social media -->
                                                <div class="irantheme-business-authors-post-item-social">
                                                    <?php if ($author_icon_one) : ?>
                                                        <!-- One -->
                                                        <a href="<?php echo esc_attr($author_url_one); ?>" class="irantheme-business-authors-post-item-social-item">
                                                            <i class="<?php echo esc_attr($author_icon_one); ?>"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    <?php if ($author_icon_two) : ?>
                                                        <!-- Two -->
                                                        <a href="<?php echo esc_attr($author_url_two); ?>" class="irantheme-business-authors-post-item-social-item">
                                                            <i class="<?php echo esc_attr($author_icon_two); ?>"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    <?php if ($author_icon_three) : ?>
                                                        <!-- Three -->
                                                        <a href="<?php echo esc_attr($author_url_three); ?>" class="irantheme-business-authors-post-item-social-item">
                                                            <i class="<?php echo esc_attr($author_icon_three); ?>"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            <?php
                                            endif;
                                            ?>
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
