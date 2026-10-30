<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;

use Irantheme_Dev\Irantheme_Common_Static;
use Irantheme_Dev\Irantheme_Page_Builder;
use Irantheme_Dev\Irantheme_Section_Showcase;


if (!class_exists('Irantheme_Section_Showcase_Video')) {
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
    class Irantheme_Section_Showcase_Video extends Irantheme_Section
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
            $heading_content = '<span style="font-size:0.8em;background-color:#f8f8f8;color:#777;">Who we are and what we do</span><h2 style="font-size:4em;font-weight:bold;">تحول دیجیتال با یک کلیک ساده</h2><p font-size:1em;>در این ویدئو، با ابزاری آشنا می‌شوید که فرآیندهای دیجیتال مارکتینگ شما را متحول کرده و بهره‌وری را افزایش می‌دهد.</p>';
            $settings = array(
                Irantheme_Section_Utils::heading_title_settings_generator($status_default, 'none', false, false, $heading_content, 'on', 'فهرست خدمات ما', '#', 'fa-light fa-grid-2-plus', 'off', 'on', 'درباره شرکت ما', '#', 'fa-light fa-arrow-left-long', 'transparent', 'rectangle', 'center', 'off', '', 4, 0, 'large'),
                array(
                    'title' => 'تنظیمات ویدیو',
                    'id' => 'video',
                    'description'            => __('تنظیمات مربوط به نمایش ویدیو در ویترین', self::$static_textdomain),
                    'icon' => 'fa-films',
                    'fields' => array(
                        array(
                            'id'             => 'video_checkbox',
                            'label'            => __(
                                'نمایش ویدیو در داخل سایت',
                                self::$static_textdomain
                            ),
                            'description'    => __('با روشن بودن این گزینه ویدیو بارگذاری شده شما در داخل سایت و به دو روش پخش در پس زمینه و بازشدن در فرم نمایش داده می شود. در صورتی که می خواهید کاربر به آدرس دیگری برای مشاهده ویدیو منتقل شود این گزینه را خاموش کنید.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'on' : '',
                            'keywords' => array('showcase', 'video',  'hero', 'ویترین', 'هیرو', 'ویدیو'),
                            'id_dependents_on' => array('video_uploader', 'video_display_mode_radio'),
                            'id_dependents_off' => array('video_link_url'),
                            'base_priority' => 'checkbox_base',
                            'selector_priority' => 'checkbox_background',
                            'z_index' => 0
                        ),
                        array(
                            'id'             => 'video_uploader',
                            'label'            => __('ویدیو', self::$static_textdomain),
                            'description'    => __('لطفا ویدیو خود را بارگذاری نمایید.', self::$static_textdomain),
                            'type'            => 'video',
                            'default'        => $status_default ? Irantheme_Common_Static::get_file_url_media_library('https://irantheme.com/wp-content/uploads/2024/05/342ph2lh.mp4') : '',
                            'placeholder'    => '',
                            'keywords' => array('showcase', 'video',  'hero', 'ویترین', 'هیرو', 'ویدیو'),
                        ),
                        array(
                            'id'             => 'video_display_mode_radio',
                            'label'            => __(
                                'سبک نمایش ویدیو',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید سبک نمایش ویدیو در ویترین را تعیین کنید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'options'        => array('background' => 'نمایش در پس زمینه', 'form' => 'نمایش در فرم بازشونده'),
                            'default'        => $status_default ? 'form' : '',
                            'keywords' => array('ودیو', 'فیلم', 'سبک', 'نمایش', 'video', 'play', 'style')
                        ),
                        array(
                            'id'             => 'video_link_url',
                            'label'            => __(
                                'آدرس صفحه ویدیو',
                                self::$static_textdomain

                            ),
                            'description'    => __('آدرس صفحه ای که ویدیو مورد نظر شما در آن است را اینجا وارد کنید تا کاربر شما پس کلیک روی دکمه پخش به آن هدایت شود.'),
                            'type'            => 'text',
                            'default'        => $status_default ? '' : '',
                            'placeholder'    => __('برای مثال: https://www.youtube.com/my-video-url', self::$static_textdomain),
                            'keywords' => array('button', 'link', 'video', 'ویدیو', 'لینک ویدیو', 'لینک دستی', 'لینک'),
                        ),
                    ),
                ),
                array(
                    'title' => 'تنظیمات بصری',
                    'id' => 'layout',
                    'description'            => __('تنظیمات بصری ویترین مانند : تصویر زمینه و...', self::$static_textdomain),
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
                            'default'        => $status_default ? 'dark' : '',
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
                            'keywords' => array('hieght', 'ارتفاع', 'fullscreen', 'تمام صفحه', 'سرویس'),
                        ),
                    ),
                ),
                // Get layout scale fields
                Irantheme_Section_Utils::layout_scale_fields_generator($status_default, array('radius', 'inner_padding', 'outer_padding', 'fullscreen'), 'on', 5, 5, 'off', 'on', 3, 3, 1),
                // Get background fields
                Irantheme_Section_Utils::background_fields_generator($status_default, array('image', 'color', 'mask', 'parallax', 'outer_container_color'), $status_default === 'media' ? Irantheme_Common_Static::get_file_url_media_library('https://irantheme.com/wp-content/uploads/2026/02/dls281ox.webp') : '', 'off', true, 'on', 'dark', 0.4, 'on'),
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
         * Render elements
         * @param string,string,string,string,mixed
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function render($fields_values, $data_type, $data_value, $data_page, $data_ajax)
        {
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

            // Background options ------------------------------------------------------------
            $background_image_data = $fields_values['bg_image'];
            $background_color_data = $fields_values['bg_color'];
            $background_color_container_checkbox = $fields_values['outer_container_color_checkbox'];
            $background_parallax_checkbox = $fields_values['bg_parallax_checkbox'];
            $background_color_css = Irantheme_Section_Utils::get_background_color_css($background_color_data);
            $background_image_css = Irantheme_Section_Utils::get_background_image_css($background_image_data);
            $background_parallax_attr = Irantheme_Section_Utils::get_background_parallax_attr($background_parallax_checkbox, $background_image_data);

            // Get tinymce text editor content
            $tinymce_text_content = $fields_values['text_editor_heading'];


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
            <!-- Showcase video -->
            <section <?php echo esc_attr($section_id_attribute); ?> class="irantheme-section irantheme-showcase-video-section irantheme-showcase-common-section" <?php echo esc_attr(Irantheme_Page_Builder::get_attr_data_section($data_type, $data_value, $data_page, $data_ajax)); ?> style="<?php echo esc_attr(Irantheme_Section_Service::get_custom_outer_padding_css($fields_values)); ?><?php echo esc_attr(Irantheme_Section_Utils::get_background_outer_container_color_css($background_color_data, $background_color_container_checkbox, $base_color)) ?><?php echo esc_attr($section_transform_extra_css); ?>">
                <?php
                // Set wrapper (Container) --------------------------------------------------------------
                $container_checkbox = $fields_values['wrapper_mode_checkbox'];
                if ($container_checkbox != 'on') :
                ?>
                    <div class="irantheme-container-inner">
                    <?php endif; ?>
                    <div class="irantheme-showcase-video <?php echo esc_attr($base_color); ?> <?php echo esc_attr(Irantheme_Section_Service::get_fullscreen_height_class($fields_values)); ?> irantheme-background-jarallax" style="<?php echo esc_attr(Irantheme_Section_Service::get_custom_inner_padding_css($fields_values)); ?> <?php echo esc_attr($background_image_css); ?><?php echo esc_attr($background_color_css); ?><?php echo esc_attr($border_radius_style); ?><?php echo esc_attr($section_transform_translate_css); ?>" <?php echo $background_parallax_attr; ?>>

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
                        // Video part --------------------------------------------------------------
                        $video_checkbox = $fields_values['video_checkbox'];
                        if ($video_checkbox == 'on') {
                            // Get video display mode
                            $video_display_mode =
                                $fields_values['video_display_mode_radio'];
                            // Get video url
                            $video_url =
                                wp_get_attachment_url($fields_values['video_uploader']);

                            if ($video_display_mode == 'background') {
                        ?>
                                <!-- Video mask -->
                                <div class="irantheme-showcase-video-mask">
                                    <!-- Video play -->
                                    <video class="irantheme-showcase-video-mask-play" autoplay muted loop>
                                        <source src="<?php echo esc_url($video_url); ?>" />
                                    </video>
                                </div>
                            <?php
                            } else if ($video_display_mode == 'form') {
                            ?>

                        <?php
                            }
                        } else {
                            // Get video blank page
                            $video_blank_page_url =
                                $fields_values['video_link_url'];
                        }
                        ?>
                        <!-- Wrapper center content -->
                        <div class="irantheme-container-inner">
                            <div class="<?php echo esc_attr($content_alignment_class); ?>" style="height:100%;">
                                <div>
                                    <?php
                                    if ($tinymce_text_content != '' && $heading_data_load_checkbox == 'on'):
                                    ?>
                                        <div class="irantheme-tinymce-heading-aos-holder" <?php echo ($heading_data_load_attr); ?> data-aos-delay="<?php echo ($heading_aos_delay); ?>" data-aos-duration="<?php echo ($heading_data_load_duration); ?>">
                                        <?php
                                    endif;
                                    // Get showcase content --------------------------------------------------------------
                                    Irantheme_Common_Static::tinymce_heading($fields_values, '');
                                    if ($tinymce_text_content != '' && $heading_data_load_checkbox == 'on'):
                                        ?>
                                        </div>
                                    <?php
                                    endif;
                                    // Video button --------------------------------------------------------
                                    if ($video_checkbox == 'on' && $video_display_mode == 'form') :
                                    ?>
                                        <!-- Video button -->
                                        <div class="irantheme-showcase-video-button" <?php echo ($data_load_attr); ?> data-aos-delay="<?php echo ($aos_delay); ?>" data-aos-duration="<?php echo ($data_load_duration); ?>">
                                            <button class="irantheme-btn-void irantheme-showcase-video-button-trigger"><i class="iconsax" icon-name="play"></i></button>
                                        </div>
                                    <?php elseif ($video_checkbox == 'off') : ?>
                                        <!-- Video button -->
                                        <div class="irantheme-showcase-video-button" <?php echo ($data_load_attr); ?> data-aos-delay="<?php echo ($aos_delay); ?>" data-aos-duration="<?php echo ($data_load_duration); ?>">
                                            <a href="<?php echo esc_url($video_blank_page_url) ?>" target="_blank" class="irantheme-btn-void irantheme-showcase-video-button-trigger"><i class="fa-solid fa-play" icon-name="play"></i></a>
                                        </div>
                                    <?php endif; ?>
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
                <?php if ($video_display_mode == 'form'): ?>
                    <!-- Video form -->
                    <div class="irantheme-showcase-video-form">
                        <div class="irantheme-showcase-video-form-wrapper">
                            <div class="irantheme-showcase-video-form-holder">
                                <!-- Close video form -->
                                <button class="irantheme-btn-void irantheme-showcase-video-form-close"><i class="fa-light fa-times"></i></button>
                                <!-- Video play -->
                                <video class="irantheme-showcase-video-form-play" controls>
                                    <source src="<?php echo esc_url($video_url); ?>" />
                                </video>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
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
