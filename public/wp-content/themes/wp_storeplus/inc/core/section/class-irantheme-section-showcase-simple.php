<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;

use Irantheme_Dev\Irantheme_Common_Static;
use Irantheme_Dev\Irantheme_Page_Builder;
use Irantheme_Dev\Irantheme_Section_Showcase;



if (!class_exists('Irantheme_Section_Showcase_Simple')) {
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
    class Irantheme_Section_Showcase_Simple extends Irantheme_Section
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
            $heading_content = '<span style="font-size:0.8em;background-color:#0f0f0f;color:white;">TSCO Gamepad Series 2025</span><h2 style="font-size:4em;font-weight:bold;">دسته بازی تسکو ۲۰۲۵</h2><p font-size:1em;>شروع قیمت از ۱۰ میلیون تومان</p>';
            $settings = array(
                Irantheme_Section_Utils::heading_title_settings_generator($status_default, false, false, false, $heading_content, 'on', 'مشاهده و خرید', '#', 'fa-light fa-arrow-left-long', 'off', 'off', 'مشاوره رایگان', '#', 'fa-light fa-headphones', 'transparent', 'rectangle', 'right', 'off', '', 5, 5, 'large'),
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
                    ),
                ),
                // Get layout scale fields
                Irantheme_Section_Utils::layout_scale_fields_generator($status_default, array('radius', 'inner_padding', 'outer_padding', 'fullscreen'), 'off', 0, 0, 'on', 'off', 0, 0, 0),
                // Get background fields
                Irantheme_Section_Utils::background_fields_generator($status_default, array('image', 'color', 'mask', 'parallax', 'outer_container_color'), $status_default === 'media' ? Irantheme_Common_Static::get_file_url_media_library('https://irantheme.com/wp-content/uploads/2026/02/p3y07ifp.webp') : '', 'off', true, 'on', 'dark', 0.5, 'on'),
                array(
                    'title' => 'امکانات',
                    'id' => 'additional',
                    'description'            => __('فعال یا غیر فعال کردن امکانات ویترین مانند: شبکه های اجتمایی، دکمه پیمایش به پایین و ...', self::$static_textdomain),
                    'icon' => 'fa-puzzle-piece',
                    'fields' => array(
                        array(
                            'id'             => 'scroll_down_checkbox',
                            'label'            => __(
                                'دکمه پیمایش به پایین',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت فعال بودن این گزینه دکمه پیمایش به پایین فعال شده که کاربر با کلیک روی این دکمه به راحتی به قسمت بعدی هدایت می شود.
								', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'on' : '',
                            'keywords' => array('showcase', 'blog', 'simple', 'hero', 'ویترین', 'هیرو', 'پیمایش'),
                        ),
                        array(
                            'id'             => 'social_media_checkbox',
                            'label'            => __(
                                'نمایش شبکه های اجتماعی',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید نمایش یا عدم نمایش شناسه کاربری شبکه های اجتماعی در ویترین را تعیین نمایید.
								</br>
								*جهت ثبت شناسه کاربری خود در هر یک از شبکه های اجتماعی به بخش "دیگر تنظیمات => شبکه های اجتماعی" مراجعه نمایید.
								', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'on' : '',
                            'keywords' => array('showcase', 'blog', 'simple', 'hero', 'ویترین', 'هیرو'),
                        ),
                        array(
                            'id'             => 'custom_text_link_checkbox',
                            'label'            => __(
                                'نمایش لینک دستی',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت فعال بودن این گزینه شما می توانید یک لینک کوتاه دستی در سمت راست پایین ویترین اضافه نمایید. این لینک حالت مدرن و طراحی شده دارد.
								', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'on' : '',
                            'keywords' => array('button', 'link', 'text', 'دکمه', 'لینک دکمه', 'لینک دستی', 'لینک'),
                            'id_dependents_on' => array('custom_text_link_heading', 'custom_text_link_url'),
                            'base_priority' => 'checkbox_base',
                            'selector_priority' => 'checkbox_custom_text_link',
                            'z_index' => 0
                        ),
                        array(
                            'id'             => 'custom_text_link_heading',
                            'label'            => __(
                                'متن لینک دستی',
                                self::$static_textdomain

                            ),
                            'description'    => __('عنوان لینک دستی خود را وارد کنید.'),
                            'type'            => 'text',
                            'default'        => $status_default ? 'جزئیات بیشتر' : '',
                            'placeholder'    => __('عنوان خود را وارد کنید', self::$static_textdomain),
                            'keywords' => array('button', 'link', 'text', 'دکمه', 'لینک دکمه', 'لینک دستی', 'لینک'),
                        ),
                        array(
                            'id'             => 'custom_text_link_url',
                            'label'            => __(
                                'آدرس لینک دستی',
                                self::$static_textdomain

                            ),
                            'description'    => __('آدرس لینک دستی خود را وارد کنید.'),
                            'type'            => 'text',
                            'default'        => $status_default ? '#' : '',
                            'placeholder'    => __('برای مثال: https://www.mywebsite.com/page', self::$static_textdomain),
                            'keywords' => array('button', 'link', 'text', 'دکمه', 'لینک دکمه', 'لینک دستی', 'لینک'),
                        ),
                    ),
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
         * Render elements
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
            $background_color_container_checkbox = $fields_values['outer_container_color_checkbox'];
            $background_parallax_checkbox = $fields_values['bg_parallax_checkbox'];
            $background_color_css = Irantheme_Section_Utils::get_background_color_css($background_color_data);
            $background_image_css = Irantheme_Section_Utils::get_background_image_css($background_image_data);
            $background_parallax_attr = Irantheme_Section_Utils::get_background_parallax_attr($background_parallax_checkbox, $background_image_data);

            // Get heading data load style --------------------------------------------------------------
            $heading_data_load_attr = Irantheme_Section_Utils::get_data_load_attr_type($fields_values);
            // Get data load duration
            $heading_data_load_duration = Irantheme_Section_Utils::get_data_load_duration($fields_values);
            // Get aos delay time
            $heading_aos_delay =
                Irantheme_Section_Utils::get_data_load_delay($fields_values);

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
            $edge_alignment_radio = '';
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
            <!-- Showcase simple -->
            <section <?php echo esc_attr($section_id_attribute); ?> class="irantheme-section irantheme-showcase-simple-section irantheme-showcase-common-section" <?php echo esc_attr(Irantheme_Page_Builder::get_attr_data_section($data_type, $data_value, $data_page, $data_ajax)); ?> style="<?php echo esc_attr(Irantheme_Section_Service::get_custom_outer_padding_css($fields_values)); ?><?php echo esc_attr(Irantheme_Section_Utils::get_background_outer_container_color_css($background_color_data, $background_color_container_checkbox, $base_color)) ?><?php echo esc_attr($section_transform_extra_css); ?>">
                <?php
                // Set wrapper (Container) --------------------------------------------------------------
                $container_checkbox = $fields_values['wrapper_mode_checkbox'];
                if ($container_checkbox != 'on') :
                ?>
                    <div class="irantheme-container-inner">
                    <?php endif; ?>
                    <div class="irantheme-showcase-simple <?php echo esc_attr($base_color); ?> <?php echo esc_attr(Irantheme_Section_Service::get_fullscreen_height_class($fields_values)); ?> <?php echo esc_attr($section_bottom_edge_class) ?> irantheme-background-jarallax" style="<?php echo esc_attr(Irantheme_Section_Service::get_custom_inner_padding_css($fields_values)); ?><?php echo esc_attr($background_image_css); ?><?php echo esc_attr($background_color_css); ?><?php echo esc_attr($border_radius_style); ?><?php echo esc_attr($section_transform_translate_css); ?>" <?php echo $background_parallax_attr; ?>>

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
                                <div <?php echo ($heading_data_load_attr); ?> data-aos-delay="<?php echo ($heading_aos_delay); ?>" data-aos-duration="<?php echo ($heading_data_load_duration); ?>">
                                    <?php
                                    // Get showcase content --------------------------------------------------------------
                                    Irantheme_Common_Static::tinymce_heading($fields_values, '');
                                    ?>
                                </div>
                            </div>
                        </div>

                        <?php
                        // Get social media status checkbox --------------------------------------------------------
                        $social_status_checkbox = $fields_values['social_media_checkbox'];
                        // Get showcase social media --------------------------------------------------------------
                        Irantheme_Section_Showcase::showcase_social_media($social_status_checkbox, $edge_checkbox, $edge_alignment_radio);
                        ?>

                        <?php
                        // Get button trigger scroll down checkbox --------------------------------------------------------
                        $button_trigger_checkbox =
                            $fields_values['scroll_down_checkbox'];
                        // Get showcase trigger (Go to down) --------------------------------------------------------------
                        Irantheme_Section_Showcase::showcase_button_trigger($button_trigger_checkbox, $edge_checkbox);
                        ?>

                        <?php
                        // Get custom text link checkbox --------------------------------------------------------
                        $custom_text_link_checkbox = $fields_values['custom_text_link_checkbox'];
                        // Get heading
                        $custom_text_link_heading = $fields_values['custom_text_link_heading'];
                        // Get url
                        $custom_text_link_url = $fields_values['custom_text_link_url'];
                        // Get showcase trigger (Go to down) --------------------------------------------------------------
                        Irantheme_Section_Showcase::showcase_custom_text_link($custom_text_link_checkbox, $custom_text_link_heading, $custom_text_link_url, $edge_checkbox, $edge_alignment_radio);
                        ?>
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
