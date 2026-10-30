<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;

if (!class_exists('Irantheme_Section_Gallery_Master')) {
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
    class Irantheme_Section_Gallery_Master extends Irantheme_Section
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
            $heading_content = '<span style="font-size: 1em;">ــــــــــــ نگارخانه ما ــــــــــــ</span>
<h2 style="font-size: 2em;"><span style="font-size: 1.2em;"><strong>عکاسی مینیمال</strong></span></h2>';
            $settings = array(
                Irantheme_Section_Utils::heading_title_settings_generator($status_default, 'none', 'off', false, $heading_content, 'off', '', '', '', 'off', 'off', '', '', '', 'off', 'rectangle', 'center', 'off', '', 0, 1),
                array(
                    'title' => 'گالری',
                    'id' => 'media',
                    'description'            => __('تنظیمات بصری مانند : تصویر زمینه، ارتفاع، رنگ ها و...', self::$static_textdomain),
                    'icon' => 'fa-images',
                    'fields' => array(
                        array(
                            'id'             => 'media_gallery_uploader',
                            'label'            => __('نگارخانه', self::$static_textdomain),
                            'description'    => __('لطفا رسانه های مد نظر خود را بارگذاری نمایید.', self::$static_textdomain),
                            'type'            => 'media_gallery',
                            'default'        => $status_default === 'media' ? Irantheme_Section_Default::get_default_ids_gallery_master() : '',
                            'keywords' => array('showcase', 'blog', 'master', 'hero', 'ویترین', 'هیرو', 'تصویر زمینه'),
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
                            'id'             => 'media_col_count_radio',
                            'label'            => __('تعداد ستون ها', self::$static_textdomain),
                            'description'    => __('با استفاده از این گزینه شما می توانید تعداد ستون قابل نمایش برای گالری را مشخص کنید', self::$static_textdomain),
                            'note'    => __('در سبک هم تراز تعداد ستون ها بصورت خودکار تنظیم خواهد شد.', self::$static_textdomain),
                            'type'            => 'radio',
                            'thumbnail_status' => true,
                            'options'        => array(
                                'col-2' => array('دو ستون', 'col-2.png'),
                                'col-3' => array('سه ستون', 'col-3.png'),
                                'col-4' => array('چهار ستون', 'col-4.png'),
                                'col-6' => array('شش ستون', 'col-6.png')
                            ),
                            'default'        => $status_default ? 'col-3' : '',
                            'keywords' => array('gallery', 'col', 'author', 'تعداد', 'سرویس', 'تعداد در هر ردیف'),
                        ),
                        array(
                            'id'             => 'gallery_type_style_radio',
                            'label'            => __(
                                'سبک نمایش گالری',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید سبک نمایش گالری را مشخص نمایید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'thumbnail_status' => true,
                            'options'        => array(
                                'grid' => array('کاشی', 'grid.png'),
                                'masonry' => array('آجری', 'masonry.png'),
                                'metro-rtl' => array('مترو (راست)', 'metro-rtl.png'),
                                'metro-ltr' => array('مترو (چپ)', 'metro-ltr.png'),
                                'justify' => array('هم تراز', 'justified.png'),
                                'carousel' => array('اسلایدر', 'carousel.png'),
                            ),
                            'default'        => $status_default ? 'masonry' : '',
                            'keywords' => array('دکمه', 'استایل', 'گوشه', 'منحنی', 'style', 'button', 'circle', 'rectangle', 'edge')
                        ),
                        array(
                            'id'             => 'hover_mode_radio',
                            'label'            => __(
                                'سبک واکنش به نشانگر (hover)',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید نوع واکنش رسانه های موجود در گالری به عبور نشانگر از روی آن ها (hover) را مشخص کنید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'options'        => array('normal' => 'ساده', 'zoom-in' => 'بزرگنمایی', 'mask' => 'لایه پوشاننده (mask)'),
                            'default'        => $status_default ? 'mask' : '',
                            'keywords' => array('دکمه', 'استایل', 'گوشه', 'منحنی', 'style', 'button', 'circle', 'rectangle', 'edge')
                        ),
                        array(
                            'id'             => 'cell_gap_range',
                            'label'            => __(
                                'فاصله بین سلول ها',
                                self::$static_textdomain
                            ),
                            'description'    => __('با اسفاده از این گزینه می توانید فاصله بین سلول ها(رسانه ها) گالری را تعیین کنید.(واحد px می باشد)', self::$static_textdomain),
                            'type'            => 'range',
                            'min' => 0,
                            'max' => 50,
                            'step' => 1,
                            'default'        => $status_default ? 30 : '',
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
                            'default'        => $status_default ? 'off' : '',
                            'keywords' => array('hieght', 'ارتفاع', 'fullscreen', 'تمام صفحه'),
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
                    'description'            => __('فعال یا غیر فعال کردن امکانات مربوط به این بخش', self::$static_textdomain),
                    'icon' => 'fa-puzzle-piece',
                    'fields' => array(
                        array(
                            'id'             => 'magnification_bg_color_select',
                            'label'            => __(
                                'رنگ پس زمینه بخش بزرگنمایی',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید رنگ پس زمینه بخش بزرگنمایی را مشخص کنید.', self::$static_textdomain),
                            'type'            => 'select',
                            'options'        => array('white' => 'سفید', 'dark' => 'مشکی'),
                            'default'        => $status_default ? 'dark' : '',
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
                    ),
                ),
                // Get transform fields
                Irantheme_Section_Utils::transform_fields_generator($status_default),
                // Get slider fields
                Irantheme_Section_Utils::slider_fields_generator($status_default, array('arrow', 'pagination'), 'on', 'off', 'on'),
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

            // Get heading data load style --------------------------------------------------------------
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

            // Base color --------------------------------------------------------------
            $base_color = $fields_values['base_color_select'];
            // Set base color class
            $base_color_class = Irantheme_Common_Static::get_base_color_class($base_color);

            // Get section heading --------------------------------------------------------------
            $tinymce_heading = $fields_values['text_editor_heading'];

            // Get media attachment selected --------------------------------------------------------------
            $media_element = $fields_values['media_gallery_uploader'];

            // Get gallery style --------------------------------------------------------------
            $gallery_style = $fields_values['gallery_type_style_radio'];

            // Get col count gallery --------------------------------------------------------------
            $col_count = $fields_values['media_col_count_radio'];

            // Get grid gap --------------------------------------------------------------
            $cells_gap = $fields_values['cell_gap_range'];

            // Get hover mode --------------------------------------------------------------
            $hover_mode = $fields_values['hover_mode_radio'];

            // Get magnification background color --------------------------------------------------------------
            $magnification_bg_color = $fields_values['magnification_bg_color_select'];

            // Get magnification tools --------------------------------------------------------------
            $magnification_tool = $fields_values['magnification_tool_multiple_checkboxes'];
            $magnification_tool = explode(',', $magnification_tool);

            // Section transform to up and down --------------------------------------------------------------
            $section_transfrom_values = Irantheme_Section_Utils::get_transform_values_arrays($data_page, $fields_values, true, false, false);
            $section_transform_extra_css = $section_transfrom_values['overflow'];
            $section_transform_translate_css = $section_transfrom_values['css'];

            // Slider status
            $slider_arrow = $fields_values['slider_arrow_checkbox'] == 'on' ? true : false;
            $slider_pagination = $fields_values['slider_pagination_checkbox'] == 'on' ? true : false;

            // Get border radius --------------------------------------------------------------
            $border_radius_range = $fields_values['border_radius_range'];
            $border_radius_style = Irantheme_Section_Utils::get_border_radius_css($border_radius_range);

            // Get section id --------------------------------------------------------------
            $section_id = $fields_values['element_id'];
            $section_id_attribute = '';
            if ($section_id) {
                $section_id_attribute = sprintf('id=%s', $section_id);
            }
?>
            <!-- ============================= Section gallery master ============================= -->
            <section <?php echo esc_attr($section_id_attribute); ?> class="irantheme-section irantheme-common-magnifiable-images irantheme-gallery-master-section <?php echo esc_attr($skeleton_section_class); ?>" <?php echo esc_attr(Irantheme_Page_Builder::get_attr_data_section($data_type, $data_value, $data_page, $data_ajax)); ?> style="<?php echo esc_attr($section_transform_extra_css); ?><?php echo esc_attr(Irantheme_Section_Service::get_custom_outer_padding_css($fields_values)); ?>">
                <?php
                // Set wrapper (Container) --------------------------------------------------------------
                $container_checkbox = $fields_values['wrapper_mode_checkbox'];
                ?>
                <div class="irantheme-gallery-master <?php echo esc_attr($base_color_class); ?> irantheme-background-jarallax" style="<?php echo esc_attr($background_color_css); ?><?php echo esc_attr($background_image_css); ?><?php echo esc_attr(Irantheme_Section_Service::get_custom_inner_padding_css($fields_values)); ?>" <?php echo $background_parallax_attr; ?>>

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
                        <?php endif;
                        ?>
                        <!-- News letter post -->
                        <div class="irantheme-gallery-master-post-wrapper" style="<?php echo esc_attr($section_transform_translate_css); ?>">
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
                            ?>

                            <?php
                            $skeleton_load_class = '';
                            if ($gallery_style == 'carousel' && $data_load_checkbox != 'on' && $heading_data_load_checkbox != 'on') {
                                // Skeleton html --------------------------------------------------------------
                                irantheme_get_skeleton_html($col_count, 'card', $base_color_class);
                                $skeleton_load_class = 'irantheme-side-load-skeleton';
                            }
                            ?>

                            <div class="irantheme-gallery-master-media-wrapper <?php echo esc_attr($skeleton_load_class); ?>">
                                <?php
                                if ($gallery_style == 'grid' || $gallery_style == 'metro-rtl' || $gallery_style == 'metro-ltr') {
                                    // Get media gallery content --------------------------------------------------------------
                                    Irantheme_Common_Static::gallery_grid_post($media_element, 'media_gallery', $col_count, $cells_gap, $aos_delay, $data_load_attr, $data_load_duration, $data_type, $data_value, $data_page, $data_ajax, $gallery_style, $hover_mode, '', '', '', '', '', $border_radius_style, false);
                                } elseif ($gallery_style == 'masonry') {
                                    // Get media gallery content --------------------------------------------------------------
                                    Irantheme_Common_Static::gallery_masonry_post($media_element, 'media_gallery', $col_count, $cells_gap, $aos_delay, $data_load_attr, $data_load_duration, $data_type, $data_value, $data_page, $data_ajax, $gallery_style, $hover_mode, '', '', '', '', '', $border_radius_style, false);
                                } elseif ($gallery_style == 'justify') {
                                    // Get media gallery content --------------------------------------------------------------
                                    Irantheme_Common_Static::gallery_justified_post($media_element, 'media_gallery', $col_count, $cells_gap, $aos_delay, $data_load_attr, $data_load_duration, $data_type, $data_value, $data_page, $data_ajax, $gallery_style, $hover_mode, '', '', '', '', '', $border_radius_style, false);
                                } elseif ($gallery_style == 'carousel') {
                                    // Get media gallery content --------------------------------------------------------------
                                    Irantheme_Common_Static::gallery_carousel_post($media_element, 'media_gallery', $col_count, $cells_gap, $aos_delay, $data_load_attr, $data_load_duration, $data_type, $data_value, $data_page, $data_ajax, $gallery_style, $hover_mode, '', '', '', '', '', $border_radius_range, false, $slider_arrow, $slider_pagination);
                                }
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
                // Magnification tag id generator
                $magnification_id = 'irantheme_magnification_' . $data_type . '_' . $data_value . '_' . $data_page . '_' . $data_ajax;
                // Convert to array media id string
                $media_element_array = explode(',', $media_element);
                // Get media gallery magnification --------------------------------------------------------------
                Irantheme_Common_Static::gallery_magnification($magnification_id, $magnification_tool, $magnification_bg_color, $media_element_array);
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
