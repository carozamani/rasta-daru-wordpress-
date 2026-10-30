<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;

use Irantheme_Dev\Irantheme_Common_Static;
use Irantheme_Dev\Irantheme_Page_Builder;



if (!class_exists('Irantheme_Section_Showcase_Tag')) {
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
    class Irantheme_Section_Showcase_Tag extends Irantheme_Section
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
            $heading_content = '<span style="font-size:0.8em;background-color:#f8f8f8;color:#777;">Artificial Intelligence (AI) from Storeplus Corporation</span><h2 style="font-size:4em;font-weight:bold;">هوش مصنوعی بر پایه یادگیری ماشین</h2><p font-size:1em;>لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک است.</p>';
            $settings = array(
                Irantheme_Section_Utils::heading_title_settings_generator($status_default, 'none', false, false, $heading_content, 'on', 'جزئیات بیشتر محصول', '#', 'fa-light fa-arrow-left-long', 'primary', 'off', 'ثبت سفارش', '#', 'fa-light fa-check', 'transparent', 'rectangle', 'right', 'off', '', 3, 3, 'large'),
                array(
                    'title' => 'تنظیمات برچسب ها',
                    'id' => 'tag_style',
                    'description'            => __('تنظیمات مربوط یه برچسب ها مانند : اندازه، شفافیت رنگ و...', self::$static_textdomain),
                    'icon' => 'fa-tag',
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
                            'default'        => $status_default ? Irantheme_Section_Default::get_default_ids_objects(3, 'showcase_tag', $status_default) : '',
                        ),
                        array(
                            'id'             => 'tag_base_color_select',
                            'label'            => __(
                                'رنگ پایه برچسب',
                                self::$static_textdomain
                            ),
                            'description'    => __('برای انتخاب رنگ پایه برچسب از لیست رنگ دلخواه خود را انتخاب کنید.', self::$static_textdomain),
                            'type'            => 'select',
                            'options'        => array('white' => 'سفید', 'dark' => 'مشکی'),
                            'default'        => $status_default ? 'white' : '',
                            'keywords' => array('tag base color', 'رنگ پایه'),
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
                            'default'        => $status_default ? 'off' : '',
                            'keywords' => array('communication', 'contact', 'جزئیات', 'کلید', 'رنگ'),
                        ),
                        array(
                            'id'             => 'bg_opacity_range',
                            'label'            => __(
                                'درجه شفافیت رنگ پس زمینه',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه شما می توانید شفافیت رنگ پس زمینه برچسب ها را مشخص نمایید.', self::$static_textdomain),
                            'type'            => 'range',
                            'min' => 0.1,
                            'max' => 1,
                            'step' => 0.1,
                            'default'        => $status_default ? 0.2 : '',
                            'placeholder'    => __('Placeholder text', self::$static_textdomain),
                            'keywords' => array('transparency', 'opacity', 'شفافیت', 'شفاف', 'پس زمینه', 'تاپ بار'),
                        ),
                        array(
                            'id'             => 'size_radio',
                            'label'            => __(
                                'انداز برچسب ها',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید اندازه برچسب ها را بر اساس اندازه های تعریف شده تغییر دهید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'options'        => array('small' => 'کوچک', 'medium' => 'متوسط', 'large' => 'بزرگ'),
                            'default'        => $status_default ? 'large' : '',
                            'keywords' => array('قالب فوتر ۴۰۴', 'قالب فوتر 404', 'footer template 404', 'page 404 footer')
                        ),
                    ),
                ),
                array(
                    'title' => 'تنظیمات بصری',
                    'id' => 'layout',
                    'description'            => __('تنظیمات بصری ویترین مانند : تصویر زمینه، ارتفاع ویترین و...', self::$static_textdomain),
                    'icon' => 'fa-paintbrush-pencil',
                    'fields' => array(
                        array(
                            'id'             => 'base_color_select',
                            'label'            => __(
                                'رنگ پایه',
                                self::$static_textdomain
                            ),
                            'description'    => __('برای انتخاب رنگ پایه ویترین از لیست رنگ دلخواه خود را انتخاب کنید.رنگ محتوای ویترین بر اساس این رنگ تنظیم خواهد شد.', self::$static_textdomain),
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
                            'default'        => $status_default ? 'on' : '',
                            'keywords' => array('hieght', 'ارتفاع', 'fullscreen', 'تمام صفحه', 'سرویس'),
                        ),
                    ),
                ),
                // Get layout scale fields
                Irantheme_Section_Utils::layout_scale_fields_generator($status_default, array('radius', 'inner_padding', 'outer_padding', 'fullscreen'), 'off', 5, 5, 'on', 'off', 0, 0, 0),
                // Get background fields
                Irantheme_Section_Utils::background_fields_generator($status_default, array('image', 'color', 'gradient', 'mask', 'parallax', 'outer_container_color'), $status_default === 'media' ? Irantheme_Common_Static::get_file_url_media_library('https://irantheme.com/wp-content/uploads/2026/02/zq2z5qsd.webp') : '', 'off', false, 'off', 'white', 0.3, 'off', 'off', '', '', '', 'off', 'off', 'off', 'linear', 'dbr', 'primary', 'secondary'),
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
                            'default'        => $status_default ? 'on' : '',
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
            $texts_default = '';
            $icons_default = '';

            // Check status is deafult or no
            if (is_numeric($index)) {
                // List of default titles
                $texts = array(
                    'یادگیری ماشین',
                    'پردازش زبان طبیعی',
                    'بینایی کامپیوتر',
                );
                $icons = array(
                    'fa-light fa-brain-circuit',
                    'fa-light fa-microchip',
                    'fa-light fa-head-side-gear',
                );

                // Set default value
                $texts_default = $texts[$index];
                $icons_default = $icons[$index];
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
                            'id'             => 'tag_text',
                            'label'            => __(
                                'متن برچسب',
                                self::$static_textdomain

                            ),
                            'description'    => __('متن دلخواه خود را اینجا وارد کنید.'),
                            'type'            => 'text',
                            'default'        => $texts_default,
                            'placeholder'    => __('عنوان', self::$static_textdomain),
                        ),
                        array(
                            'id'             => 'tag_icon',
                            'label'            => __(
                                'آیکون برچسب',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت نیاز به استفاده از آیکون (نماد) خاصی در کنار متن می توانید از این گزینه استفاده نمایید.', self::$static_textdomain),
                            'type'            => 'icon',
                            'default'        => $icons_default,
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
            $background_color_container_checkbox = $fields_values['outer_container_color_checkbox'];
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

            // Get data load style --------------------------------------------------------------
            $data_load_attr = Irantheme_Section_Utils::get_data_load_attr_type($fields_values);
            // Get data load duration
            $data_load_duration = Irantheme_Section_Utils::get_data_load_duration($fields_values);
            // Get aos delay time
            $aos_delay =
                Irantheme_Section_Utils::get_data_load_delay($fields_values);

            // Get tinymce text editor content --------------------------------------------------------------
            $tinymce_text_content = $fields_values['text_editor_heading'];

            // Get heading data load style --------------------------------------------------------------
            $heading_data_load_checkbox = $fields_values['heading_data_load_checkbox'];
            $heading_data_load_attr = Irantheme_Section_Utils::get_heading_data_load_attr_type($fields_values);
            // Get data load duration
            $heading_data_load_duration = Irantheme_Section_Utils::get_heading_data_load_duration($fields_values);
            // Get aos delay time
            $heading_aos_delay =
                Irantheme_Section_Utils::get_heading_data_load_delay($fields_values);

            // Set content alignment --------------------------------------------------------------
            $content_alignment_radio =
                $fields_values['alignment_heading_radio'];
            $content_alignment_class = '';
            // Check alignment position
            if ($content_alignment_radio == 'center') {
                // Set alignment class center
                $content_alignment_class = 'irantheme-content-center';
            } else if ($content_alignment_radio == 'right') {
                // Set alignment class right
                $content_alignment_class = 'irantheme-content-right';
            } else if ($content_alignment_radio == 'left') {
                // Set alignment class left
                $content_alignment_class = 'irantheme-content-left';
            }

            // Get border radius --------------------------------------------------------------
            $border_radius_range = $fields_values['border_radius_range'];
            $border_radius_style = Irantheme_Section_Utils::get_border_radius_css($border_radius_range);

            // Set base color (Dependents of mask) --------------------------------------------------------------
            $base_color = $fields_values['base_color_select'];

            // Tag options --------------------------------------------------------------
            $tag_size = $fields_values['size_radio'];

            $tag_bg_opacity = $fields_values['bg_opacity_range'];

            $tag_base_color = $fields_values['tag_base_color_select'];
            $tag_base_color_class = '';

            if ('white' == $tag_base_color) {
                $tag_base_color_class = 'white';
            } else if ('dark' == $tag_base_color) {
                $tag_base_color_class = 'dark';
            }

            // Details color --------------------------------------------------------------
            $details_color_select = $fields_values['details_color_select'];
            // Arrow url --------------------------------------------------------------
            $details_color_rgba_css = '';
            if ($details_color_select != 'off') {
                $details_color_rgb_array = Irantheme_Color_Static::hex_to_rgb(Irantheme_Color_Static::get_hex_color($details_color_select));
                if ($details_color_select != 'off') {
                    $details_color_rgba_css = 'background-color:rgba(' . $details_color_rgb_array[0] . ',' . $details_color_rgb_array[1] . ',' . $details_color_rgb_array[2] . ',' . $tag_bg_opacity . ');';
                }
            }

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
            <!-- Showcase tag -->
            <section <?php echo esc_attr($section_id_attribute); ?> class="irantheme-section irantheme-showcase-tag-section irantheme-showcase-common-section" <?php echo esc_attr(Irantheme_Page_Builder::get_attr_data_section($data_type, $data_value, $data_page, $data_ajax)); ?> style="<?php echo esc_attr(Irantheme_Section_Service::get_custom_outer_padding_css($fields_values)); ?><?php echo esc_attr(Irantheme_Section_Utils::get_background_outer_container_color_css($background_color_data, $background_color_container_checkbox, $base_color)) ?><?php echo esc_attr($section_transform_extra_css); ?>">
                <?php
                // Set wrapper (Container) --------------------------------------------------------------
                $container_checkbox = $fields_values['wrapper_mode_checkbox'];
                if ($container_checkbox != 'on') :
                ?>
                    <div class="irantheme-container-inner">
                    <?php endif; ?>
                    <div class="irantheme-showcase-tag <?php echo esc_attr($base_color); ?> <?php echo esc_attr(Irantheme_Section_Service::get_fullscreen_height_class($fields_values)); ?> <?php echo esc_attr($gradient_animation_class); ?> irantheme-background-jarallax" style="<?php echo esc_attr($gradient_animation_speed_css); ?><?php echo esc_attr($gradient_animation_bg_size_css); ?><?php echo esc_attr($background_color_css); ?><?php echo esc_attr($background_image_css); ?><?php echo esc_attr(Irantheme_Section_Service::get_custom_inner_padding_css($fields_values)); ?><?php echo esc_attr($border_radius_style); ?><?php echo esc_attr($section_transform_translate_css); ?>" <?php echo $background_parallax_attr; ?>>

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

                        <!-- Wrapper content -->
                        <div class="irantheme-container-inner">
                            <div class="<?php echo esc_attr($content_alignment_class); ?>" style="height:100%;">
                                <div class="irantheme-showcase-tag-content">
                                    <?php
                                    if ($tinymce_text_content != '' && $heading_data_load_checkbox == 'on'):
                                    ?>
                                        <div class="irantheme-tinymce-heading-aos-holder" <?php echo ($heading_data_load_attr); ?> data-aos-delay="<?php echo ($heading_aos_delay); ?>" data-aos-duration="<?php echo ($heading_data_load_duration); ?>">
                                        <?php
                                    endif;
                                    // Get showcase content --------------------------------------------------------------
                                    Irantheme_Common_Static::tinymce_heading($fields_values, '', false);
                                    if ($tinymce_text_content != '' && $heading_data_load_checkbox == 'on'):
                                        ?>
                                        </div>
                                    <?php
                                    endif;
                                    ?>
                                    <?php
                                    // Set content alignment --------------------------------------------------------------
                                    $alignment_class = Irantheme_Section_Service::get_flex_alignment_class($content_alignment_radio);
                                    $tag_item_size_class = '';
                                    if ($tag_size == 'small') {
                                        $tag_item_size_class = 'tag-size-small';
                                    } else if ($tag_size == 'medium') {
                                        $tag_item_size_class = 'tag-size-medium';
                                    } else if ($tag_size == 'large') {
                                        $tag_item_size_class = 'tag-size-large';
                                    }
                                    ?>
                                    <!-- tags -->
                                    <div class="irantheme-showcase-tags <?php echo esc_attr($tag_base_color_class); ?>">
                                        <ul class="irantheme-object-group irantheme-showcase-tags-wrapper  <?php echo esc_attr($alignment_class); ?> <?php echo esc_attr($tag_item_size_class); ?>" data-json="<?php echo esc_attr('objects_json'); ?>">
                                            <?php
                                            // Service custom fields --------------------------------------------------------------
                                            foreach ($json_post_array as $object_post) {
                                                // Get title options
                                                $tag_text =
                                                    $object_post['fields']['tag_text'];
                                                // Get icon option
                                                $tag_icon =
                                                    $object_post['fields']['tag_icon'];
                                            ?>
                                                <!-- Tag item -->
                                                <li class="irantheme-object irantheme-showcase-tags-item" data-object="<?php echo esc_attr($object_post['object_id']); ?>" <?php echo ($data_load_attr); ?> data-aos-delay="<?php echo ($aos_delay); ?>" data-aos-duration="<?php echo ($data_load_duration); ?>" data-aos-delay="<?php echo ($aos_delay); ?>">
                                                    <div class="irantheme-showcase-tags-item-content" style="<?php echo esc_attr($details_color_rgba_css); ?>">
                                                        <?php if ($tag_icon) : ?>
                                                            <!-- Icon -->
                                                            <div class="irantheme-showcase-tags-item-content-icon">
                                                                <i class="<?php echo esc_attr($tag_icon); ?>"></i>
                                                            </div>
                                                        <?php endif; ?>
                                                        <!-- Text -->
                                                        <div class="irantheme-showcase-tags-item-content-text">
                                                            <cite><?php echo esc_html($tag_text); ?></cite>
                                                        </div>
                                                    </div>
                                                    <?php
                                                    Irantheme_Page_Builder::get_hidden_require_object_buttons($data_page);
                                                    ?>
                                                </li>
                                            <?php
                                                // Increase aos delay for next item
                                                $aos_delay += 200;
                                            }
                                            ?>
                                            <?php
                                            Irantheme_Page_Builder::get_hidden_require_object_group_buttons($data_page);
                                            ?>
                                        </ul>
                                    </div>
                                    <?php
                                    // Get showcase content --------------------------------------------------------------
                                    Irantheme_Common_Static::tinymce_heading($fields_values, '', true, false);
                                    ?>
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
