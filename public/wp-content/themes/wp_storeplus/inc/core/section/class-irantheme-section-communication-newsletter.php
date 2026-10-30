<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;

if (!class_exists('Irantheme_Section_Communication_Newsletter')) {
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
    class Irantheme_Section_Communication_Newsletter extends Irantheme_Section
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
            $heading_content = '<span style="font-size: 1em;">ــــــــــــ از رویداد ها با خبر شوید ــــــــــــ</span>
<h2 style="font-size: 2em;"><span style="font-size: 1.2em;"><strong>عضویت در خبرنامه</strong></span></h2>';
            $settings = array(
                Irantheme_Section_Utils::heading_title_settings_generator($status_default, 'none', 'off', 'right', $heading_content, 'off', '', '', '', 'off', 'off', '', '', '', 'off', 'rectangle', 'center', 'off', '', 0, 1),
                array(
                    'title' => 'تنظیمات فرم ارتباطی',
                    'id' => 'form',
                    'description'            => __('تنظیمات مربوط به فرم ارتباطی مانند : نوع فرم، نوع ورودی های فرم و...', self::$static_textdomain),
                    'icon' => 'fa-tty',
                    'fields' => array(
                        array(
                            'id'             => 'plugin_form_checkbox',
                            'label'            => __(
                                'استفاده از پلاگین ها',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت غیر فعال بودن این گزینه شما می توانید از خبرنامه پیش فرض قالب استفاده کنید.در صورتی که می خواهید از پلاگین ها استفاده کنید این گزینه را روشن کنید.
                                ', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'off' : '',
                            'keywords' => array('communication', 'contact', 'ارتباط با ما',  'ارتباطات'),
                            'id_dependents_on' => array('plugin_form_shortcode'),
                            'id_dependents_off' => array('input_color_select', 'no_space_checkbox'),
                            'base_priority' => 'checkbox_base',
                            'selector_priority' => 'checkbox_communication_form_newsletter',
                            'z_index' => 0
                        ),
                        array(
                            'id'             => 'plugin_form_shortcode',
                            'label'            => __(
                                'شورت کد',
                                self::$static_textdomain

                            ),
                            'description'    => __('شورت کد تولید شده توسط پلاگین خبرنامه را در اینجا قرار دهید.'),
                            'type'            => 'textarea',
                            'default'        => $status_default ? '' : '',
                            'placeholder'    => __('', self::$static_textdomain),
                            'keywords' => array('communication', 'contact', 'ارتباط با ما',  'ارتباطات'),
                        ),
                        array(
                            'id'             => 'no_space_checkbox',
                            'label'            => __(
                                'عناصر بهم چسبیده',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت فعال بودن این گزینه دکمه عضویت و فیلد ایمیل بهم می چسبد٫
							', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'off' : '',
                            'keywords' => array('business', 'brands', 'کسب و کار',  'تصویر زمینه'),
                        ),
                        array(
                            'id'             => 'input_style_radio',
                            'label'            => __(
                                'فعال سازی خط دور',
                                self::$static_textdomain
                            ),
                            'description'    => __('می توانید خط دوری برای فرم ارتباطی نمایان کنید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'options'        => array('normal' => 'بدون خط', 'border' => 'با خط دور', 'shadow' => 'سایه'),
                            'default'        => $status_default ? 'shadow' : '',
                            'keywords' => array('فعال سازی خط دور', 'enable input border')
                        ),
                        array(
                            'id'             => 'input_color_select',
                            'label'            => __(
                                'رنگ فیلد ایمیل',
                                self::$static_textdomain
                            ),
                            'description'    => __('برای انتخاب رنگ فیلد از لیست رنگ دلخواه خود را انتخاب کنید.', self::$static_textdomain),
                            'type'            => 'select',
                            'options'        => array('white' => 'سفید', 'dark' => 'مشکی'),
                            'default'        => $status_default ? 'white' : '',
                            'keywords' => array('color', 'background', 'پس زمینه', 'رنگ'),
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
                            'id'             => 'alignment_content_radio',
                            'label'            => __('موقعیت محتوا', self::$static_textdomain),
                            'description'    => __('با استفاده از این گزینه می توانید موقعیت قرارگیری محتوا را تعیین کنید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'thumbnail_status' => true,
                            'options'        => array('right' => array('راست چین', 'text-align-right.png'), 'center' => array('وسط چین', 'text-align-center.png'), 'left' => array('چپ چین', 'text-align-left.png')),
                            'default'        => $status_default ? 'center' : '',
                            'keywords' => array('محتوا', 'تنظیم', 'تراز', 'محور', 'alignment', 'float', 'position')
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
                            'id'             => 'details_color_select',
                            'label'            => __(
                                'رنگ جزئیات',
                                self::$static_textdomain
                            ),
                            'description'    => __('برای انتخاب رنگ جزئیات بخش از لیست رنگ دلخواه خود را انتخاب کنید. این رنگ تاثیر مستقیمی در زیبایی جزئيات دارد.', self::$static_textdomain),
                            'type'            => 'select',
                            'options'        => Irantheme_Color_Static::get_all_colors_options(),
                            'color_preview' => true,
                            'default'        => $status_default ? 'primary' : '',
                            'keywords' => array('communication', 'contact', 'جزئیات', 'کلید', 'رنگ'),
                        ),
                        array(
                            'id'             => 'input_border_radius_range',
                            'label'            => __(
                                'خمیدگی فرم ارتباطی',
                                self::$static_textdomain
                            ),
                            'description'    => __('با اسفاده از این گزینه می توانید خمیدگی حاشیه (Border Radius) را تعیین کنید.(واحد rem می باشد)', self::$static_textdomain),
                            'type'            => 'range',
                            'min' => 0,
                            'max' => 2.5,
                            'step' => 0.1,
                            'default'        => $status_default ? 1 : 0,
                            'placeholder'    => __('Placeholder text', self::$static_textdomain),
                            'keywords' => array('خمیدگی حاشیه', 'banner border radius',)
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
                            'keywords' => array('hieght', 'ارتفاع', 'fullscreen', 'تمام صفحه'),
                        ),
                    )
                ),
                // Get layout scale fields
                Irantheme_Section_Utils::layout_scale_fields_generator($status_default, array('radius', 'inner_padding', 'outer_padding'), 'on', 2, 2, 'off', 'off', 0, 0, 0),
                // Get background fields
                Irantheme_Section_Utils::background_fields_generator($status_default, array('image', 'color', 'mask', 'parallax', 'outer_container_color'), '', 'off', true, 'off', 'dark', 0.6, 'off'),
                array(
                    'title' => 'امکانات',
                    'id' => 'additional',
                    'description'            => __('فعال یا غیر فعال کردن امکانات ویترین مانند: شبکه های اجتمایی و ...', self::$static_textdomain),
                    'icon' => 'fa-puzzle-piece',
                    'fields' => array(
                        array(
                            'id'             => 'social_media_checkbox',
                            'label'            => __(
                                'نمایش شبکه های اجتماعی',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید نمایش یا عدم نمایش شناسه کاربری شبکه های اجتماعی را تعیین نمایید.
								</br>
								*جهت ثبت شناسه کاربری خود در هر یک از شبکه های اجتماعی به بخش "دیگر تنظیمات => شبکه های اجتماعی" مراجعه نمایید.
								', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'on' : '',
                            'keywords' => array('showcase', 'blog', 'simple', 'hero', 'ویترین', 'هیرو'),
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
            $background_color_container_checkbox = $fields_values['outer_container_color_checkbox'];
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

            // Base color --------------------------------------------------------------
            $base_color = $fields_values['base_color_select'];
            // Set base color class
            $base_color_class = Irantheme_Common_Static::get_base_color_class($base_color);

            // Details color --------------------------------------------------------------
            $section_details_color = $fields_values['details_color_select'];

            // Get space between button and email field checkbox --------------------------------------------------------------
            $space_style_checkbox = $fields_values['no_space_checkbox'];
            $space_style_class = 'irantheme-communication-newsletter-yes-space';
            if ($space_style_checkbox == 'on') {
                $space_style_class = 'irantheme-communication-newsletter-no-space';
            }

            $extra_css = '';

            // Section transform to up and down --------------------------------------------------------------
            $section_transfrom_values = Irantheme_Section_Utils::get_transform_values_arrays($data_page, $fields_values);
            $section_transform_translate_css = $section_transfrom_values['css'];

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
            <!-- ============================= Section communication newsletter ============================= -->
            <section <?php echo esc_attr($section_id_attribute); ?> class="irantheme-section irantheme-communication-newsletter-section" <?php echo esc_attr(Irantheme_Page_Builder::get_attr_data_section($data_type, $data_value, $data_page, $data_ajax)); ?> style="<?php echo esc_attr($section_transform_translate_css); ?><?php echo esc_attr(Irantheme_Section_Service::get_custom_outer_padding_css($fields_values)); ?><?php echo esc_attr(Irantheme_Section_Utils::get_background_outer_container_color_css($background_color_data, $background_color_container_checkbox, $base_color)) ?>">
                <?php
                // Set wrapper (Container) --------------------------------------------------------------
                $container_checkbox = $fields_values['wrapper_mode_checkbox'];
                if ($container_checkbox != 'on') :
                ?>
                    <div class="irantheme-container-inner">
                    <?php endif;
                    ?>
                    <div class="irantheme-communication-newsletter <?php echo esc_attr($base_color_class); ?> irantheme-background-jarallax" style="<?php echo esc_attr($background_color_css); ?><?php echo esc_attr($background_image_css); ?><?php echo esc_attr(Irantheme_Section_Service::get_custom_inner_padding_css($fields_values)); ?><?php echo esc_attr($border_radius_style); ?>" <?php echo ($background_parallax_attr); ?>>

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

                        <div class="irantheme-wrapper-medium">
                            <!-- News letter post -->
                            <div class="irantheme-communication-newsletter-wrapper">
                                <?php
                                // Communication tinymce heading --------------------------------------------------------------
                                $communication_transfer_heading_title = $fields_values['heading_transfer_checkbox'];
                                if ($communication_transfer_heading_title != 'on') {
                                    if ($tinymce_heading != '' && $heading_data_load_checkbox == 'on'):
                                ?>
                                        <div class="irantheme-tinymce-heading-aos-holder" <?php echo ($heading_data_load_attr); ?> data-aos-delay="<?php echo ($heading_aos_delay); ?>" data-aos-duration="<?php echo ($heading_data_load_duration); ?>">
                                        <?php
                                    endif;
                                    // Get tinymce content --------------------------------------------------------------
                                    Irantheme_Common_Static::tinymce_heading($fields_values, $extra_css);
                                    if ($tinymce_heading != '' && $heading_data_load_checkbox == 'on'):
                                        ?>
                                        </div>
                                <?php
                                    endif;
                                }
                                ?>

                                <div class="irantheme-newsletter-animation" <?php echo ($data_load_attr); ?> data-aos-delay="<?php echo ($aos_delay); ?>" data-aos-duration="<?php echo ($data_load_duration); ?>">
                                    <div class="row justify-content-center align-items-center">
                                        <?php
                                        // Set column of contact in service status or nothing
                                        $contact_bootstrap_class = 'col-lg-12';
                                        // Check transform heading to columns or Show services
                                        if ($communication_transfer_heading_title == 'on') :
                                            $contact_bootstrap_class = 'col-lg-6';
                                        ?>
                                            <div class="col-lg-6">
                                                <?php
                                                // Check transform heading
                                                if ($communication_transfer_heading_title == 'on') {
                                                ?>
                                                    <div class="col-lg-12" style="margin:0;display:flex;flex-direction:column;justify-content:center;height:100%;">
                                                        <?php
                                                        // Get tinymce service content --------------------------------------------------------------
                                                        Irantheme_Common_Static::tinymce_heading($fields_values);
                                                        ?>
                                                    </div>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                        <?php
                                            // Increase aos delay for next item
                                            $aos_delay += 200;
                                        endif;
                                        ?>

                                        <div class="<?php echo esc_attr($contact_bootstrap_class); ?>">
                                            <?php
                                            // Shortcode checkbox --------------------------------------------------------------
                                            $shortcode_checkbox = $fields_values['plugin_form_checkbox'];
                                            // Shortcode content --------------------------------------------------------------
                                            $shortcode_content = $fields_values['plugin_form_shortcode'];
                                            if ($shortcode_checkbox == 'on') :
                                            ?>
                                                <div class="irantheme-input-form-fields <?php echo esc_attr(Irantheme_Common_Static::get_base_color_class($base_color)); ?>">
                                                    <?php
                                                    echo do_shortcode($shortcode_content);
                                                    ?>
                                                </div>
                                            <?php

                                            // Irantheme custom form --------------------------------------------------------------
                                            elseif ($shortcode_checkbox == 'off') :
                                                // Set details colors --------------------------------------------------------------
                                                $color_details_class_btn = '';
                                                if ($section_details_color != 'off') {
                                                    $color_details_class_btn = Irantheme_Color_Static::get_class_color_mode('btn', null, Irantheme_Color_Static::get_hex_color($section_details_color));
                                                } else {
                                                    if ($base_color == 'white') {
                                                        $color_details_class_btn = 'irantheme-btn-dark';
                                                    } elseif ($base_color == 'dark') {
                                                        $color_details_class_btn = 'irantheme-btn-white';
                                                    }
                                                }
                                                // Get inputs radius style --------------------------------------------------------------
                                                $input_border_radius = $fields_values['input_border_radius_range'];
                                                $input_border_radius_style = Irantheme_Section_Utils::get_border_radius_css($input_border_radius);
                                                $intended_input_border_radius_style = '';
                                                if ($space_style_checkbox == 'off') {
                                                    $intended_input_border_radius_style = Irantheme_Section_Utils::get_border_radius_css($input_border_radius);
                                                }
                                                // Get inputs radius style --------------------------------------------------------------
                                                $input_color = $fields_values['input_color_select'];
                                                $input_color_css = '';
                                                if ($input_color == 'dark') {
                                                    $input_color_css = 'irantheme-communication-newsletter-input-bg-dark';
                                                } elseif ($input_color == 'white') {
                                                    $input_color_css = 'irantheme-communication-newsletter-input-bg-white';
                                                }
                                                // Get inputs radius style --------------------------------------------------------------
                                                $input_style_mode = $fields_values['input_style_radio'];
                                                $input_style_mode_class = '';
                                                if ($input_style_mode == 'normal') {
                                                    $input_style_mode_class = 'irantheme-communication-newsletter-input-normal';
                                                } elseif ($input_style_mode == 'border') {
                                                    $input_style_mode_class = 'irantheme-communication-newsletter-input-border';
                                                } elseif ($input_style_mode == 'shadow') {
                                                    $input_style_mode_class = 'irantheme-communication-newsletter-input-shadow';
                                                }
                                                // Get content alignment --------------------------------------------------------------
                                                $alignment_content = $fields_values['alignment_content_radio'];
                                            ?>
                                                <div class="irantheme-communication-newsletter-form <?php echo esc_attr(Irantheme_Section_Service::get_flex_alignment_class($alignment_content)); ?>">
                                                    <div class="irantheme-communication-newsletter-form-input-holder <?php echo esc_attr($input_color_css); ?> <?php echo esc_attr($space_style_class); ?> <?php echo esc_attr($input_style_mode_class); ?>" style="<?php echo esc_attr($input_border_radius_style); ?>">
                                                        <!-- Email input -->
                                                        <input type="email" class="irantheme-communication-newsletter-form-email irantheme-input-email <?php echo esc_attr(Irantheme_Common_Static::get_base_color_class($base_color)); ?>" style="border-radius:0;<?php echo esc_attr($intended_input_border_radius_style); ?>" placeholder="ایمیل خود را وارد کنید..." required>
                                                        <!-- Button submit -->
                                                        <button type="button" class="irantheme-communication-newsletter-form-submit irantheme-btn irantheme-btn-large <?php echo esc_attr($color_details_class_btn); ?>" style="<?php echo esc_attr($intended_input_border_radius_style); ?>"><?php echo esc_html('عضویت'); ?></button>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                // Get social media
                                $social_media_checkbox = $fields_values['social_media_checkbox'];
                                if ($social_media_checkbox == 'on') {
                                    $aos_delay += 250;
                                }
                                ?>
                                <div class="irantheme-post-sidebar-animation" <?php echo ($data_load_attr); ?> data-aos-delay="<?php echo ($aos_delay); ?>" data-aos-duration="<?php echo ($data_load_duration); ?>">
                                    <?php
                                    if ($social_media_checkbox == 'on') :
                                        // Set details colors --------------------------------------------------------------
                                        $color_details_bg_class = Irantheme_Color_Static::get_class_color_mode('bg-hover', null, Irantheme_Color_Static::get_hex_color($section_details_color));
                                    ?>
                                        <!-- Social media -->
                                        <div class="irantheme-communication-newsletter-social-media <?php echo esc_attr(Irantheme_Section_Service::get_flex_alignment_class($alignment_content)); ?>">
                                            <?php
                                            // Get social media
                                            Irantheme_Common_Static::get_social_media($color_details_bg_class);
                                            ?>
                                        </div>
                                    <?php
                                    endif;
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
