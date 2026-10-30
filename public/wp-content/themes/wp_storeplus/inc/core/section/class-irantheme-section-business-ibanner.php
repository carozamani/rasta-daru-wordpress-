<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;

if (!class_exists('Irantheme_Section_Business_Ibanner')) {
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
    class Irantheme_Section_Business_Ibanner extends Irantheme_Section
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
            $heading_content = '';
            $settings = array(
                Irantheme_Section_Utils::heading_title_settings_generator($status_default, 'nones', false, false, $heading_content),
                array(
                    'title' => 'تنظیمات بصری',
                    'id' => 'layout',
                    'description'            => __('تنظیمات بصری مانند :ارتفاع، رنگ ها و...', self::$static_textdomain),
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
                            'default'        => $status_default ? Irantheme_Section_Default::get_default_ids_objects(3, 'business_ibanner', $status_default) : '',
                        ),
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
                            'id'             => 'box_shadow_checkbox',
                            'label'            => __(
                                'استایل سایه',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید سایه ای به دور دیدگاه ها قرار دهید.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'off' : '',
                            'keywords' => array('استایل سایه', 'product shadow', 'shadow style'),
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
            $content_default = '';
            $btn_title_default = '';
            $btn_link_default = '';
            $base_color_default = '';

            // Check status is deafult or no
            if (is_numeric($index)) {

                // List of ibanner images
                $images = array(
                    'https://irantheme.com/wp-content/uploads/2026/02/pwjaulpr.webp',
                    'https://irantheme.com/wp-content/uploads/2026/02/y71rn0fl.webp',
                    'https://irantheme.com/wp-content/uploads/2026/02/eelrvr91.webp',
                );

                // Default content
                $content = array(
                    '<h3 style="font-size:1.4em;"><b>حرفه‌ای، قدرتمند و منعطف</b></h3><p style="font-size:0.8em;" style="color:#adb5bd;">شروع قیمت از ۴۶ میلیون تومان</p>',
                    '<h3 style="font-size:1.4em;"><b>گرم باش، شیک بپوش</b></h3><p style="font-size:0.8em;" style="color:#adb5bd;">شروع قیمت از ۱۵ میلیون تومان</p>',
                    '<h3 style="font-size:1.4em;"><b>خانه ات را متحول کن</b></h3><p style="font-size:0.8em;" style="color:#adb5bd;">شروع قیمت از ۳۵ میلیون تومان</p>',
                );

                // Button title
                $btn_title = '';
                // Button link
                $btn_link = '';

                // Base color
                $base_color = array(
                    'white',
                    'white',
                    'white',
                );

                // Set default value
                $images_default = $status_default === 'media' ? Irantheme_Common_Static::get_file_url_media_library($images[$index]) : '';
                $content_default = $content[$index];
                $btn_title_default = $btn_title;
                $btn_link_default = $btn_link;
                $base_color_default = $base_color[$index];
            }

            // Custom fields
            $object_fields = array(
                array(
                    'title' => 'تنظیم محتوا',
                    'id' => 'edit_object',
                    'description'            => __('تنظیم مقادیر عنصر', self::$static_textdomain),
                    'icon' => 'fa-folder-grid',
                    'fields' => array(
                        array(
                            'id'             => 'heading',
                            'label'            => __('عنوان بنر', self::$static_textdomain),
                            'description'    => __('در صورت لزوم و نیاز به معرفی بنر یا محصول می توانید توضیحاتی را ارائه دهید.'),
                            'type'            => 'text_editor',
                            'default'        => $content_default,
                            'placeholder'    => __('', self::$static_textdomain),
                        ),
                        array(
                            'id'             => 'btn_color',
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
                            'id'             => 'btn_title',
                            'label'            => __(
                                'عنوان دکمه',
                                self::$static_textdomain

                            ),
                            'description'    => __('عنوان دلخواه دکمه خود را اینجا وارد کنید.'),
                            'type'            => 'text',
                            'default'        => $btn_title_default,
                            'placeholder'    => __('عنوان دکمه', self::$static_textdomain),
                        ),
                        array(
                            'id'             => 'banner_link',
                            'label'            => __(
                                'لینک دکمه یا بنر',
                                self::$static_textdomain

                            ),
                            'description'    => __('لینک دلخواه خود را اینجا وارد کنید.'),
                            'type'            => 'text',
                            'default'        => $btn_link_default,
                            'placeholder'    => __('لینک دکمه', self::$static_textdomain),
                        ),
                    )
                ),
                // Get background fields
                Irantheme_Section_Utils::background_fields_generator(true, array('image', 'color', 'mask'), $images_default, 'off', false, 'off', $base_color_default, 0.3),
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
                            'description'    => __('برای انتخاب رنگ پایه از لیست، رنگ دلخواه خود را انتخاب کنید.رنگ محتواو پس زمینه این بخش بر اساس این رنگ تنظیم خواهد شد.', self::$static_textdomain),
                            'type'            => 'select',
                            'options'        => array('white' => 'سفید', 'dark' => 'مشکی'),
                            'default'        => $base_color_default,
                        ),
                        array(
                            'id'             => 'col_count_radio',
                            'label'            => __('نسبت بنر به ردیف', self::$static_textdomain),
                            'description'    => __('با استفاده از این گزینه ها شما می توانید بزرگی هر بنر در یک ردیف را به نسبت ۱۲ ستون تنظیم نمایید.', self::$static_textdomain),
                            'type'            => 'select',
                            'options'        => array(
                                'col-1' => 'یک ستون',
                                'col-2' => 'دو ستون',
                                'col-3' => 'سه ستون',
                                'col-4' => 'چهار ستون',
                                'col-5' => 'پنج ستون',
                                'col-6' => 'شش ستون',
                                'col-7' => 'هفت ستون',
                                'col-8' => 'هشت ستون',
                                'col-9' => 'نه ستون',
                                'col-10' => 'ده ستون',
                                'col-11' => 'یازده ستون',
                                'col-12' => 'دوازده ستون',
                            ),
                            'default'        => 'col-4',
                        ),
                        array(
                            'id'             => 'vertical_align_content_radio',
                            'label'            => __('تراز عمودی محتوا', self::$static_textdomain),
                            'description'    => __('با استفاده از این گزینه می توانید تراز عمودی جزئیات پست ها را مشخص کنید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'thumbnail_status' => true,
                            'options'        => array(
                                'top' => array('بالا', 'text-vertical-align-top.png'),
                                'middle' => array('وسط', 'text-vertical-align-middle.png'),
                                'bottom' => array('پایین', 'text-vertical-align-bottom.png'),
                            ),
                            'default'        => 'middle',
                        ),
                        array(
                            'id'             => 'align_content_radio',
                            'label'            => __('تراز افقی محتوا', self::$static_textdomain),
                            'description'    => __('با استفاده از این گزینه می توانید تراز افقی جزئیات پست ها را مشخص کنید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'thumbnail_status' => true,
                            'options'        => array('right' => array('راست چین', 'text-align-right.png'), 'center' => array('وسط چین', 'text-align-center.png'), 'left' => array('چپ چین', 'text-align-left.png')),
                            'default'        => 'right',
                        ),
                    )
                )
            );
            return $object_fields;
        }


        /**
         * Render elements
         * @param array,string,string,string,string
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

            // Get product box shadow checkbox --------------------------------------------------------------
            $product_box_shadow_checkbox = $fields_values['box_shadow_checkbox'];

            $box_shadow_class = '';
            if ($product_box_shadow_checkbox == 'on') {
                $box_shadow_class = 'irantheme-business-ibanner-item-shadow';
            }

            // Get section id --------------------------------------------------------------
            $section_id = $fields_values['element_id'];
            $section_id_attribute = '';
            if ($section_id) {
                $section_id_attribute = sprintf('id=%s', $section_id);
            }

            // Section transform to up and down --------------------------------------------------------------
            $section_transfrom_values = Irantheme_Section_Utils::get_transform_values_arrays($data_page, $fields_values, true, false, false);
            $section_transform_extra_css = $section_transfrom_values['overflow'];
            $section_transform_translate_css = $section_transfrom_values['css'];
?>
            <!-- ============================= Section business ibanner ============================= -->
            <section <?php echo esc_attr($section_id_attribute); ?> class="irantheme-section irantheme-business-ibanner-section" <?php echo esc_attr(Irantheme_Page_Builder::get_attr_data_section($data_type, $data_value, $data_page, $data_ajax)); ?> style="<?php echo esc_attr(Irantheme_Section_Service::get_custom_outer_padding_css($fields_values)); ?><?php echo esc_attr($section_transform_extra_css); ?>">
                <div class="irantheme-business irantheme-business-ibanner <?php echo esc_attr($base_color_class); ?> irantheme-background-jarallax" style="<?php echo esc_attr($background_color_css); ?><?php echo esc_attr($background_image_css); ?><?php echo esc_attr(Irantheme_Section_Service::get_custom_inner_padding_css($fields_values)); ?>" <?php echo $background_parallax_attr; ?>>

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
                        <div class="irantheme-container-inner">
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
                        // Set column mode --------------------------------------------------------------
                        $grid_mode_wrapper_class = '';
                        $grid_mode_row  = 'row justify-content-center';
                        ?>
                        <!-- Authors post -->
                        <div class="irantheme-business-ibanner-post-wrapper <?php echo esc_attr($grid_mode_wrapper_class); ?>" style="<?php echo esc_attr($section_transform_translate_css); ?>">
                            <div class="irantheme-object-group irantheme-business-ibanner-post-container <?php echo esc_attr($grid_mode_row); ?>" data-json="<?php echo esc_attr('objects_json'); ?>">
                                <?php
                                foreach ($json_post_array as $object_post) :
                                    // Get post meta --------------------------------------------------------------
                                    // Heading
                                    $ibanner_heading = $object_post['fields']['heading'];

                                    // Button title
                                    $ibanner_btn_title = $object_post['fields']['btn_title'];
                                    // Button link
                                    $ibanner_link = $object_post['fields']['banner_link'];
                                    // Button color
                                    $ibanner_btn_color = $object_post['fields']['btn_color'];

                                    // Base color
                                    $ibanner_base_color = $object_post['fields']['base_color_select'];

                                    // Background options ------------------------------------------------------------
                                    $background_image_data = $object_post['fields']['bg_image'];
                                    $background_color_data = $object_post['fields']['bg_color'];
                                    $background_color_css = Irantheme_Section_Utils::get_background_color_css($background_color_data);

                                    // Get columns count --------------------------------------------------------------
                                    $count_columns = $object_post['fields']['col_count_radio'];
                                    $bootstrap_column_class = Irantheme_Section_Service::get_bootstrap_raw_class($count_columns);

                                    // Get alignment class
                                    $post_content_align = $object_post['fields']['align_content_radio'];
                                    $post_content_vertical_align = $object_post['fields']['vertical_align_content_radio'];

                                    $content_alignment_class = Irantheme_Section_Service::get_alignment_class($post_content_align);
                                    // Set alignment style
                                    $content_alignment_style = '';
                                    if ($post_content_align == 'center') {
                                        $content_alignment_style = 'align-items:center;';
                                    } elseif ($post_content_align == 'right') {
                                        $content_alignment_style = 'align-items:flex-start;';
                                    } elseif ($post_content_align == 'left') {
                                        $content_alignment_style = 'align-items:flex-end;';
                                    }
                                    $content_vertical_alignment_style = '';
                                    if ($post_content_vertical_align == 'top') {
                                        $content_vertical_alignment_style = 'justify-content:flex-start;';
                                    } elseif ($post_content_vertical_align == 'middle') {
                                        $content_vertical_alignment_style = 'justify-content:center;';
                                    } elseif ($post_content_vertical_align == 'bottom') {
                                        $content_vertical_alignment_style = 'justify-content:flex-end;';
                                    }
                                ?>
                                    <div class="irantheme-object <?php echo esc_attr($bootstrap_column_class); ?>" data-object="<?php echo esc_attr($object_post['object_id']); ?>">
                                        <!-- Post item -->
                                        <div class="irantheme-business-ibanner-post-item <?php echo esc_attr($box_shadow_class); ?> <?php echo esc_attr(Irantheme_Section_Service::get_flex_column_alignment_class($post_content_align)); ?> irantheme-set-height" style="<?php echo esc_attr($background_color_css); ?><?php echo esc_attr($border_radius_style); ?>" <?php echo ($data_load_attr); ?> data-aos-delay="<?php echo ($aos_delay); ?>" data-aos-duration="<?php echo ($data_load_duration); ?>">

                                            <!-- Holder thumbnail -->
                                            <div class="irantheme-business-ibanner-post-item-image">
                                                <?php if ($ibanner_link && !$ibanner_heading): ?>
                                                    <a href="<?php echo esc_url($ibanner_link); ?>" class="irantheme-business-ibanner-post-item-image-link">
                                                    <?php endif; ?>
                                                    <?php
                                                    echo wp_get_attachment_image(
                                                        $background_image_data,
                                                        'full',
                                                        false,
                                                        [
                                                            'class' => 'irantheme-business-ibanner-post-item-image-src',
                                                            'alt' => __('تصویر بنر', self::$static_textdomain),
                                                            'loading' => 'lazy',
                                                        ]
                                                    );
                                                    ?>
                                                    <?php if ($ibanner_link && !$ibanner_heading): ?>
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
                                            ?>

                                            <?php if ($ibanner_heading) : ?>
                                                <!-- Content -->
                                                <div class="irantheme-business-ibanner-post-item-content <?php echo esc_attr($content_alignment_class); ?>" style="<?php echo esc_attr($content_alignment_style); ?><?php echo esc_attr($content_vertical_alignment_style); ?>">
                                                    <?php
                                                    // Tinymce content
                                                    Irantheme_Common_Static::get_snippet_tinymce_simple($ibanner_heading, $ibanner_base_color, '', Irantheme_Section_Service::get_alignment_class($post_content_align)); ?>
                                                    <?php
                                                    // Button content
                                                    $button_checkbox = 'off';
                                                    if ($ibanner_btn_title) {
                                                        $button_checkbox = 'on';
                                                    }
                                                    Irantheme_Common_Static::section_buttons($post_content_align, $ibanner_base_color, $button_checkbox, 'off', 'normal', 'medium', $ibanner_btn_title, $ibanner_link, '', $ibanner_btn_color, '', '', '', ''); ?>
                                                </div>
                                            <?php endif; ?>
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
