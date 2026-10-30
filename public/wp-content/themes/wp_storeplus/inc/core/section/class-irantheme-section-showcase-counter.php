<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;

use Irantheme_Dev\Irantheme_Common_Static;
use Irantheme_Dev\Irantheme_Color_Static;
use Irantheme_Dev\Irantheme_Page_Builder;



if (!class_exists('Irantheme_Section_Showcase_Counter')) {
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
    class Irantheme_Section_Showcase_Counter extends Irantheme_Section
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
            $heading_content = '<span style="font-size:0.8em;background-color:#0f0f0f;color:white;">Toyota Land Cruiser 2024</span><h2 style="font-size:4em;font-weight:bold;">تویوتا لندکروزر ۲۰۲۴</h2><p font-size:1em;>شروع قیمت از چهار میلیارد تومان</p>';
            $settings = array(
                Irantheme_Section_Utils::heading_title_settings_generator($status_default, false, false, false, $heading_content, 'on', 'شرکت در جشنواره', '#', 'fa-light fa-bag-shopping', 'off', 'off', 'مشاوره رایگان', '#', 'fa-light fa-headphones', 'transparent', 'rectangle', 'center', 'off', '', 3, 3, 'large'),
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
                            'default'        => $status_default ? 'on' : '',
                            'keywords' => array('hieght', 'ارتفاع', 'fullscreen', 'تمام صفحه', 'سرویس'),
                        ),
                        array(
                            'id'             => 'edge_checkbox',
                            'label'            => __(
                                'مورب کردن پایین ویترین',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت تمایل به کج شدن پایین ویترین می توانید این گزیته را فعال کنید.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'off' : '',
                            'keywords' => array('showcase', 'edge', 'simple', 'hero', 'ویترین', 'هیرو', 'کج'),
                            'id_dependents_on' => array('edge_alignment_radio'),
                            'base_priority' => 'checkbox_base',
                            'selector_priority' => 'checkbox_edge',
                            'z_index' => 0
                        ),
                        array(
                            'id'             => 'edge_alignment_radio',
                            'label'            => __(
                                'تراز حالت مورب',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید تراز حالت مورب را تعیین کنید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'thumbnail_status' => true,
                            'options'        => array('right' => array('از راست به چپ', 'diagonal-mode-right.png'), 'left' => array('از چپ به راست', 'diagonal-mode-left.png')),
                            'default'        => $status_default ? 'right' : '',
                            'keywords' => array('مورب', 'تنظیم', 'تراز', 'محور', 'alignment', 'edge', 'position')
                        ),
                        array(
                            'id'             => 'count_mode_radio',
                            'label'            => __(
                                'سبک تغییر اسلاید',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استغاده از این گزینه می توانید نوع سبک تغییر شمارشگر را تعیین کنید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'options'        => array('normal' => 'عادی', 'fade' => 'محو شونده', 'slide' => 'آبشاری'),
                            'default'        => $status_default ? 'normal' : '',
                            'keywords' => array('slider', 'count', 'counter', 'showcase counter', 'slider mode', 'سبک نمایش'),
                        ),
                        array(
                            'id'             => 'second_color_select',
                            'label'            => __(
                                'رنگ شمارنده ثانیه',
                                self::$static_textdomain
                            ),
                            'description'    => __('برای انتخاب رنگ شمارنده ثانیه از لیست رنگ دلخواه خود را انتخاب کنید.', self::$static_textdomain),
                            'type'            => 'select',
                            'options'        => Irantheme_Color_Static::get_all_colors_options(),
                            'color_preview' => true,
                            'default'        => $status_default ? 'secondary' : '',
                            'keywords' => array('showcase counter', 'counter', 'شمارنده ثانیه', 'کلید', 'رنگ'),
                        ),
                        array(
                            'id'             => 'size_numbers',
                            'label'            => __(
                                'اندازه شمارنده ها',
                                self::$static_textdomain
                            ),
                            'description'    => __('شما می توانید با استفاده از این فیلد زمان تاخیر بین تایپ هر کاراکتر از متن اسلایدر متنی را تنظیم کنید. (واحد ms می باشد)', self::$static_textdomain),
                            'type'            => 'number',
                            'step' => 0.5,
                            'default'        => $status_default ? 2.5 : '',
                            'min' => 2,
                            'max' => 8,
                            'placeholder'    => __('', self::$static_textdomain),
                            'keywords' => array('size', 'number', 'size number', 'اندازه عدد', 'اندازه شمارنده', 'سایز عدد'),
                        ),
                    ),
                ),

                // Get layout scale fields
                Irantheme_Section_Utils::layout_scale_fields_generator($status_default, array('radius', 'inner_padding', 'outer_padding', 'fullscreen'), 'on', 10, 13, 'on', 'off', 0, 0, 0),
                // Get background fields
                Irantheme_Section_Utils::background_fields_generator($status_default, array('image', 'color', 'mask', 'parallax', 'outer_container_color'), $status_default === 'media' ? Irantheme_Common_Static::get_file_url_media_library('https://irantheme.com/wp-content/uploads/2026/02/hxp1tq1w.webp') : '', 'off', true, 'on', 'dark', 0.6, 'on'),
                // Get transform fields
                Irantheme_Section_Utils::transform_fields_generator($status_default),
                // Get data load animation fields
                Irantheme_Section_Utils::data_load_fields_generator($status_default),
                array(
                    'title' => 'تنظیم شمارنده',
                    'id' => 'count',
                    'description'            => __('تنظیم مقدار های شمارنده', self::$static_textdomain),
                    'icon' => 'fa-calendar-clock',
                    'fields' => array(
                        array(
                            'id'             => 'count_date',
                            'label'            => __(
                                'تاریخ رویداد شمارنده',
                                self::$static_textdomain

                            ),
                            'description'    => __('در صورت تمایل می توانید تاریخ رویداد شمارنده سایت را تنظیم نمایید. جهت تنظیم، کافیست تاریخ آینده را پیش بینی کنید.'),
                            'type'            => 'date',
                            'default'        => $status_default ? date('Y-m-d', strtotime("+15 days")) : '',
                            'placeholder'    => __('', self::$static_textdomain),
                            'keywords' => array('تاریخ', 'تاریخ شمارنده', 'date', 'counter', 'count date', 'count'),
                        ),
                        array(
                            'id'             => 'count_time',
                            'label'            => __(
                                'زمان رویداد شمارنده',
                                self::$static_textdomain

                            ),
                            'description'    => __('در صورت تمایل می توانید زمان رویداد شمارنده سایت را تنظیم نمایید. تفاوت زمان با تاریخ در جزئیات ساعت و دقیقه می باشد. جهت تنظیم، کافیست زمان آینده را پیش بینی کنید.'),
                            'type'            => 'time',
                            'default'        => $status_default ? '' : '',
                            'placeholder'    => __('', self::$static_textdomain),
                            'keywords' => array('زمان', 'زمان شمارنده', 'time', 'counter', 'count time', 'count'),
                        ),
                        array(
                            'id'             => 'end_count_message',
                            'label'            => __(
                                'پیام پایان شمارنده',
                                self::$static_textdomain

                            ),
                            'description'    => __('در صورت نیاز می توانید پیامی را که می خواهید پس از پایان شمارنده به کاربر نمایش داده شود را اینجا وارد کنید.'),
                            'type'            => 'text',
                            'default'        => $status_default ? 'جشنواره به پایان رسیده است!' : '',
                            'placeholder'    => __('متن دلخواه', self::$static_textdomain),
                            'keywords' => array('counter', 'text', 'message', 'پیام', 'متن', 'شمارنده'),
                        ),
                    ),
                ),
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

            // Background options ------------------------------------------------------------
            $background_image_data = $fields_values['bg_image'];
            $background_color_data = $fields_values['bg_color'];
            $background_color_container_checkbox = $fields_values['outer_container_color_checkbox'];
            $background_parallax_checkbox = $fields_values['bg_parallax_checkbox'];
            $background_color_css = Irantheme_Section_Utils::get_background_color_css($background_color_data);
            $background_image_css = Irantheme_Section_Utils::get_background_image_css($background_image_data);
            $background_parallax_attr = Irantheme_Section_Utils::get_background_parallax_attr($background_parallax_checkbox, $background_image_data);

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

            // Set edge bottom of showcase --------------------------------------------------------------
            $edge_checkbox = $fields_values['edge_checkbox'];
            $section_bottom_edge_class = '';
            if ($edge_checkbox == 'on') {
                $edge_alignment_radio =
                    $fields_values['edge_alignment_radio'];
                if ($edge_alignment_radio == 'right') {
                    $section_bottom_edge_class = 'irantheme-bottom-edge-right';
                } elseif ($edge_alignment_radio == 'left') {
                    $section_bottom_edge_class = 'irantheme-bottom-edge-left';
                }
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
            <!-- Showcase counter -->
            <section <?php echo esc_attr($section_id_attribute); ?> class="irantheme-section irantheme-showcase-counter-section irantheme-showcase-common-section" <?php echo esc_attr(Irantheme_Page_Builder::get_attr_data_section($data_type, $data_value, $data_page, $data_ajax)); ?> style="<?php echo esc_attr(Irantheme_Section_Service::get_custom_outer_padding_css($fields_values)); ?><?php echo esc_attr(Irantheme_Section_Utils::get_background_outer_container_color_css($background_color_data, $background_color_container_checkbox, $base_color)) ?><?php echo esc_attr($section_transform_extra_css); ?>">
                <?php
                // Set wrapper (Container) --------------------------------------------------------------
                $container_checkbox = $fields_values['wrapper_mode_checkbox'];
                if ($container_checkbox != 'on') :
                ?>
                    <div class="irantheme-container-inner">
                    <?php endif; ?>
                    <div class="irantheme-showcase-counter <?php echo esc_attr($base_color); ?> <?php echo esc_attr(Irantheme_Section_Service::get_fullscreen_height_class($fields_values)); ?> <?php echo esc_attr($section_bottom_edge_class) ?> irantheme-background-jarallax" style="<?php echo esc_attr($background_color_css); ?><?php echo esc_attr($background_image_css); ?><?php echo esc_attr(Irantheme_Section_Service::get_custom_inner_padding_css($fields_values)); ?><?php echo esc_attr($border_radius_style); ?><?php echo esc_attr($section_transform_translate_css); ?>" <?php echo $background_parallax_attr; ?>>

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

                        <!-- Wrapper center content -->
                        <div class="irantheme-container-inner">
                            <div class="<?php echo esc_attr($content_alignment_class); ?>" style="height:100%;">
                                <div <?php echo ($data_load_attr); ?> data-aos-delay="<?php echo ($aos_delay); ?>" data-aos-duration="<?php echo ($data_load_duration); ?>">
                                    <?php
                                    // Get showcase content --------------------------------------------------------------
                                    Irantheme_Common_Static::tinymce_heading($fields_values, '');

                                    // Get date counter --------------------------------------------------------------
                                    $counter_date = $fields_values['count_date'];

                                    // Get time counter --------------------------------------------------------------
                                    $counter_time = $fields_values['count_time'];
                                    $date_value_end = new \DateTime($counter_date . ' ' . $counter_time);

                                    // Get mode of count --------------------------------------------------------------
                                    $count_mode = $fields_values['count_mode_radio'];
                                    $date_current = new \DateTime();

                                    // Second color --------------------------------------------------------------
                                    $second_color = $fields_values['second_color_select'];
                                    // Get details color
                                    $second_color_css = '';
                                    if ($second_color && $second_color != 'off') {
                                        $second_color_css = 'color:#' . Irantheme_Color_Static::get_hex_color($second_color) . ';';
                                    }

                                    // Size of counters --------------------------------------------------------------
                                    $size_counters = $fields_values['size_numbers'];
                                    $size_counters_caption = '';
                                    $size_counters_caption_css = '';
                                    $size_counters_css = '';
                                    $size_counters_title_css = '';
                                    $size_counters_item_css = '';
                                    if ($size_counters) {
                                        $size_counters_title = $size_counters * 0.35;
                                        $size_counters_caption = $size_counters * 0.5;
                                        $size_counters_css = 'font-size:' . $size_counters . 'rem;line-height:' . $size_counters . 'rem;';
                                        $size_counters_title_css = 'font-size:' . $size_counters_title . 'rem;line-height:' . $size_counters_title . 'rem;';
                                        $size_counters_caption_css = 'font-size:' . $size_counters_caption . 'rem;line-height:' . $size_counters_caption . 'rem;';
                                        $size_counters_item_css = 'height:' . $size_counters * 2.5 . 'rem;';
                                    }
                                    ?>
                                    <div class="irantheme-showcase-counter-countdown" data-time-end="<?php echo esc_attr($date_value_end->format('Y-m-d H:i:s')); ?>" data-time-now="<?php echo esc_attr($date_current->format('Y-m-d H:i:s')); ?>" data-mode="<?php echo esc_attr($count_mode); ?>">
                                        <?php
                                        // Second color --------------------------------------------------------------
                                        $end_count_message = $fields_values['end_count_message'];
                                        if ($end_count_message) :
                                        ?>
                                            <!-- End count message -->
                                            <div class="irantheme-showcase-counter-countdown-caption" style="<?php echo esc_attr($size_counters_caption_css); ?>"><?php _e($end_count_message); ?></div>
                                        <?php endif; ?>
                                        <ul class="irantheme-showcase-counter-countdown-list <?php echo esc_attr($content_alignment_class); ?>">
                                            <!-- Second -->
                                            <li class="irantheme-showcase-counter-countdown-list-item" style="<?php echo esc_attr($size_counters_item_css); ?>">
                                                <span class="irantheme-showcase-counter-countdown-list-item-num irantheme-showcase-counter-countdown-list-item-second" style="<?php echo esc_attr($second_color_css); ?><?php echo esc_attr($size_counters_css); ?>">00</span>
                                                <cite class="irantheme-showcase-counter-countdown-list-item-title" style="<?php echo esc_attr($size_counters_title_css); ?>">ثانیه</cite>
                                            </li>
                                            <!-- Minute -->
                                            <li class="irantheme-showcase-counter-countdown-list-item" style="<?php echo esc_attr($size_counters_item_css); ?>">
                                                <span class="irantheme-showcase-counter-countdown-list-item-num irantheme-showcase-counter-countdown-list-item-minute" style="<?php echo esc_attr($size_counters_css); ?>">00</span>
                                                <cite class="irantheme-showcase-counter-countdown-list-item-title" style="<?php echo esc_attr($size_counters_title_css); ?>">دقیقه</cite>
                                            </li>
                                            <!-- Hour -->
                                            <li class="irantheme-showcase-counter-countdown-list-item" style="<?php echo esc_attr($size_counters_item_css); ?>">
                                                <span class="irantheme-showcase-counter-countdown-list-item-num irantheme-showcase-counter-countdown-list-item-hour" style="<?php echo esc_attr($size_counters_css); ?>">00</span>
                                                <cite class="irantheme-showcase-counter-countdown-list-item-title" style="<?php echo esc_attr($size_counters_title_css); ?>">ساعت</cite>
                                            </li>
                                            <!-- Day -->
                                            <li class="irantheme-showcase-counter-countdown-list-item" style="<?php echo esc_attr($size_counters_item_css); ?>">
                                                <span class="irantheme-showcase-counter-countdown-list-item-num irantheme-showcase-counter-countdown-list-item-day" style="<?php echo esc_attr($size_counters_css); ?>">0</span>
                                                <cite class="irantheme-showcase-counter-countdown-list-item-title" style="<?php echo esc_attr($size_counters_title_css); ?>">روز</cite>
                                            </li>
                                        </ul>
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
