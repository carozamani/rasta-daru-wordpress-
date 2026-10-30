<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;

if (!class_exists('Irantheme_Section_Service_Semi_Grid_Image')) {
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
    class Irantheme_Section_Service_Semi_Grid_Image extends Irantheme_Section
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
            $heading_content = '<span style="font-size: 1em;">ــــــــــــــــــــــــ هر جزئیات، یک داستان تازه</span>
<h2 style="font-size: 2em;"><span style="font-size: 1.2em;"><strong>طراحی مینیمال، تجربه ماکزیمال</strong></span></h2><p font-size:1em;>لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است. چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است و برای شرایط فعلی تکنولوژی مورد نیاز و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد.</p><p font-size:1em;>لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است. چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است و برای شرایط فعلی تکنولوژی مورد نیاز و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد.</p>';
            $settings = array(
                Irantheme_Section_Utils::heading_title_settings_generator($status_default, 'none', false, 'right', $heading_content, 'on', 'مشاهده جزئیات', '#', '', 'primary', 'on', 'سفارش محصول', '#', '', 'off', 'rectangle', 'right', 'off', '', 1, 1, 'large', 'normal', 'primary'),
                array(
                    'title' => 'تنظیمات بصری',
                    'id' => 'layout',
                    'description'            => __('تنظیمات بصری مانند : تصویر زمینه، ارتفاع، رنگ ها و...', self::$static_textdomain),
                    'icon' => 'fa-paintbrush-pencil',
                    'fields' => array(
                        array(
                            'id'             => 'display_mode',
                            'label'            => __(
                                'سبک محتوا',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید سبک نمایش محتوای سرویس خود را مشخص کنید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'thumbnail_status' => true,
                            'options'        => array('right' => array('راست', 'showcase-split-right.png'), 'left' => array('چپ', 'showcase-split-left.png')),
                            'default'        => $status_default ? 'right' : '',
                            'keywords' => array('semi', 'mode', 'سبک', 'سرویس', 'چیدمان', 'alignment')
                        ),
                        array(
                            'id'             => 'base_color_select',
                            'label'            => __(
                                'رنگ پایه',
                                self::$static_textdomain
                            ),
                            'description'    => __('برای انتخاب رنگ پایه سرویس از لیست رنگ دلخواه خود را انتخاب کنید.رنگ محتواو پس زمینه سرویس بر اساس این رنگ تنظیم خواهد شد.', self::$static_textdomain),
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
                            'keywords' => array('hieght', 'ارتفاع', 'fullscreen', 'تمام صفحه', 'سرویس'),
                        ),
                    )
                ),
                // Get layout scale fields
                Irantheme_Section_Utils::layout_scale_fields_generator($status_default, array('radius', 'inner_padding', 'outer_padding'), 'on', 2, 2, 'off', 'off', 0, 0, 1),
                // Get background fields
                Irantheme_Section_Utils::background_fields_generator($status_default, array('image', 'image2', 'image3', 'image4', 'color'), $status_default === 'media' ? Irantheme_Common_Static::get_file_url_media_library('https://irantheme.com/wp-content/uploads/2026/02/hosemln0.webp') : '', 'off', true, 'on', 'dark', 0.6, 'on', 'off', $status_default === 'media' ? Irantheme_Common_Static::get_file_url_media_library('https://irantheme.com/wp-content/uploads/2026/02/jsoc19ci.webp') : '', $status_default === 'media' ? Irantheme_Common_Static::get_file_url_media_library('https://irantheme.com/wp-content/uploads/2026/02/qt5nnqzq.webp') : '', $status_default === 'media' ? Irantheme_Common_Static::get_file_url_media_library('https://irantheme.com/wp-content/uploads/2026/02/n4nkrayj.webp') : ''),
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
            $background_image_data2 = $fields_values['bg_image2'];
            $background_image_data3 = $fields_values['bg_image3'];
            $background_image_data4 = $fields_values['bg_image4'];
            $background_color_data = $fields_values['bg_color'];
            $background_color_css = Irantheme_Section_Utils::get_background_color_css($background_color_data);
            $background_image_css = Irantheme_Section_Utils::get_background_image_css($background_image_data, true);
            $background_image_css2 = Irantheme_Section_Utils::get_background_image_css($background_image_data2, true);
            $background_image_css3 = Irantheme_Section_Utils::get_background_image_css($background_image_data3, true);
            $background_image_css4 = Irantheme_Section_Utils::get_background_image_css($background_image_data4, true);

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
            $heading_aos_delay =
                Irantheme_Section_Utils::get_heading_data_load_delay($fields_values);

            // Base color --------------------------------------------------------------
            $base_color = $fields_values['base_color_select'];
            // Set base color class
            $base_color_class = Irantheme_Common_Static::get_base_color_class($base_color);

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
            <!-- ============================= Section service semi grid image ============================= -->
            <section <?php echo esc_attr($section_id_attribute); ?> class="irantheme-section irantheme-service-semi-grid-image-section" <?php echo esc_attr(Irantheme_Page_Builder::get_attr_data_section($data_type, $data_value, $data_page, $data_ajax)); ?> data-json="<?php echo esc_attr('objects_json'); ?>" style="<?php echo esc_attr(Irantheme_Section_Service::get_custom_outer_padding_css($fields_values)); ?>">
                <?php
                // Set wrapper (Container) --------------------------------------------------------------
                $container_checkbox = $fields_values['wrapper_mode_checkbox'];
                ?>
                <div class="irantheme-service-semi-grid-image <?php echo esc_attr($base_color_class); ?>" style="<?php echo esc_attr($background_color_css); ?><?php echo esc_attr(Irantheme_Section_Service::get_custom_inner_padding_css($fields_values)); ?>">
                    <?php
                    if ($container_checkbox != 'on') :
                    ?>
                        <div class=" irantheme-container-inner">
                        <?php endif; ?>
                        <?php
                        // Get alignment columns --------------------------------------------------------------
                        $display_mode = $fields_values['display_mode'];
                        $display_mode_class = '';
                        $padding_holder_class = '';
                        if ($display_mode == 'right') {
                            $display_mode_class = 'flex-row-reverse';
                            $padding_holder_class = 'padding-left';
                        } else {
                            $padding_holder_class = 'padding-right';
                        }
                        // Set padding column with wrapper mode and connect to outer container
                        if ($container_checkbox == 'on') {
                            $padding_holder_class = 'padding-right padding-left';
                        }
                        ?>
                        <div class="row justify-content-center <?php echo esc_attr($display_mode_class); ?>" style="align-items: center;margin-right:0;margin-left:0;">
                            <div class="col-lg-6" style="margin-bottom: 0;padding:0;display:flex;justify-content:center;" <?php echo ($data_load_attr); ?> data-aos-delay="<?php echo ($aos_delay); ?>" data-aos-duration="<?php echo ($data_load_duration); ?>">
                                <?php if ($background_image_css || $background_image_css2 || $background_image_css3) : ?>
                                    <div class="irantheme-service-semi-grid-image-images">
                                        <?php
                                        $images = [
                                            [
                                                'css' => $background_image_css,
                                                'data' => $background_image_data,
                                                'class' => 'irantheme-service-semi-grid-image-images-img1'
                                            ],
                                            [
                                                'css' => $background_image_css2,
                                                'data' => $background_image_data2,
                                                'class' => 'irantheme-service-semi-grid-image-images-img2'
                                            ],
                                            [
                                                'css' => $background_image_css3,
                                                'data' => $background_image_data3,
                                                'class' => 'irantheme-service-semi-grid-image-images-img3'
                                            ],
                                            [
                                                'css' => $background_image_css4,
                                                'data' => $background_image_data4,
                                                'class' => 'irantheme-service-semi-grid-image-images-img4'
                                            ],
                                        ];

                                        foreach ($images as $image) {
                                            if (!empty($image['css']) && !empty($image['data'])) {
                                                echo wp_get_attachment_image(
                                                    $image['data'],
                                                    'medium_large',
                                                    false,
                                                    [
                                                        'class' => $image['class'],
                                                        'alt' => __('تصویر سرویس', self::$static_textdomain),
                                                        'loading' => 'lazy',
                                                        'style' => esc_attr($border_radius_style),
                                                    ]
                                                );
                                            }
                                        }
                                        ?>
                                    </div>
                                <?php else : ?>
                                    <?php
                                    // Empty thumbnail
                                    Irantheme_Section_Service::empty_thumbnail($base_color);
                                    ?>
                                <?php endif; ?>
                            </div>

                            <?php
                            $aos_delay += 200;
                            ?>

                            <div class="col-lg-6" style="margin-bottom:0;padding:0;">
                                <div class="irantheme-holder-padding <?php echo esc_attr($padding_holder_class); ?>">
                                    <!-- Content -->
                                    <?php
                                    $heading_extra_css = 'height:auto;';
                                    // Get service heading --------------------------------------------------------------
                                    $service_heading = $fields_values['text_editor_heading'];
                                    if ($service_heading != '' && $heading_data_load_checkbox == 'on'):
                                    ?>
                                        <div class="irantheme-tinymce-heading-aos-holder" <?php echo ($heading_data_load_attr); ?> data-aos-delay="<?php echo ($heading_aos_delay); ?>" data-aos-duration="<?php echo ($heading_data_load_duration); ?>">
                                        <?php
                                    endif;
                                    Irantheme_Common_Static::tinymce_heading($fields_values, $heading_extra_css);
                                    if ($service_heading != '' && $heading_data_load_checkbox == 'on'):
                                        ?>
                                        </div>
                                    <?php
                                    endif;
                                    ?>
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
