<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;

if (!class_exists('Irantheme_Section_Communication_Faq')) {
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
    class Irantheme_Section_Communication_Faq extends Irantheme_Section
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
            $heading_content = '<span style="font-size: 1em;">ــــــــــــــــــــــــ سوالی پیش آمده؟</span>
<h2 style="font-size: 2rem;"><span style="font-size: 1.2em;"><strong>پرسش های متداول</strong></span></h2>';
            $settings = array(
                Irantheme_Section_Utils::heading_title_settings_generator($status_default, 'none', 'off', 'right', $heading_content, 'off', 'ارتباط با پشتیبانی', '#', '', 'transparent', 'off', '', '', '', 'off', 'rectangle', 'right', 'off', '', 0, 1),
                array(
                    'title' => 'تنظیمات بصری',
                    'id' => 'layout',
                    'description'            => __('تنظیمات بصری مانند : تصویر زمینه، ارتفاع، رنگ ها و...', self::$static_textdomain),
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
                            'default'        =>
                            $status_default ? Irantheme_Section_Default::get_default_ids_objects(4, 'communication_faq', $status_default) : '',
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
                            'id'             => 'transparent_mode_checkbox',
                            'label'            => __(
                                'استایل شیشه ای سوالات',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت فعال بودن این گزینه، طرح پس زمینه سوالات به صورت شیشه ای در آمده و یک حائل جدا کننده ما بینشان قرار می گیرد.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'off' : '',
                            'keywords' => array('separator mode', 'transparent mode', 'استایل شیشه ای سوالات'),
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
            $questions_default = '';
            $answer_default = '';

            // Check status is deafult or no
            if (is_numeric($index)) {
                // List of default questions
                $questions = array(
                    'مراحل عقد قرارداد به صورت رسمی انجام می شود؟',
                    'آیا فاکتور رسمی در صورت خرید صادر می شود؟',
                    'مزایای بستن قرارداد به چه صورت است؟',
                    'آیا انتقال فناوری در قرارداد ها مصوب است؟',
                );

                // List of default images
                $answer = 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است. چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است و برای شرایط فعلی تکنولوژی مورد نیاز و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد.';

                // Set default value
                $questions_default = $questions[$index];
                $answer_default = $answer;
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
                            'id'             => 'question',
                            'label'            => __(
                                'متن سوال',
                                self::$static_textdomain
                            ),
                            'description'    => __('متن سوال مورد نظر را در اینجا وارد کنید.', self::$static_textdomain),
                            'type'            => 'text',
                            'default'        => $questions_default,
                            'placeholder'    => __('سوال مورد نظر را وارد کنید', self::$static_textdomain),
                            'keywords' => array('توضیحات', 'سوال', 'سوالات متداول', 'question', 'faq', 'communication'),
                        ),
                        array(
                            'id'             => 'answer',
                            'label'            => __(
                                'متن پاسخ',
                                self::$static_textdomain
                            ),
                            'description'    => __('متن پاسخ به سوال تعریف شده در بالا را اینجا وارد کنید.', self::$static_textdomain),
                            'type'            => 'text_editor',
                            'media_status' => true,
                            'default'        => $answer_default,
                            'placeholder'    => __('پاسخ سوال مورد نظر را وارد کنید', self::$static_textdomain),
                            'keywords' => array('توضیحات', 'پاسخ', 'سوالات متداول', 'answer', 'faq', 'communication'),
                        ),
                    )
                )
            );
            return $object_fields;
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

            // Get data load style --------------------------------------------------------------
            $heading_data_load_checkbox = $fields_values['heading_data_load_checkbox'];
            $heading_data_load_attr = Irantheme_Section_Utils::get_heading_data_load_attr_type($fields_values);
            // Get data load duration
            $heading_data_load_duration = Irantheme_Section_Utils::get_heading_data_load_duration($fields_values);
            // Get aos delay time
            $heading_aos_delay = Irantheme_Section_Utils::get_heading_data_load_delay($fields_values);

            // Get section heading --------------------------------------------------------------
            $tinymce_heading = $fields_values['text_editor_heading'];

            // Base color --------------------------------------------------------------
            $base_color = $fields_values['base_color_select'];
            // Set base color class
            $base_color_class = Irantheme_Common_Static::get_base_color_class($base_color);

            // Details color --------------------------------------------------------------
            $section_details_color = $fields_values['details_color_select'];

            // Get border radius --------------------------------------------------------------
            $border_radius_range = $fields_values['border_radius_range'];
            $border_radius_style = Irantheme_Section_Utils::get_border_radius_css($border_radius_range);

            // Get section id --------------------------------------------------------------
            $section_id = $fields_values['element_id'];
            $section_id_attribute = '';
            if ($section_id) {
                $section_id_attribute = sprintf('id=%s', $section_id);
            }

            // Get border mode --------------------------------------------------------------
            $transparent_mode = $fields_values['transparent_mode_checkbox'];
            $transparent_mode_class = '';
            if ($transparent_mode == 'on') {
                $transparent_mode_class = 'irantheme-communication-faq-transparent-mode';
            }
?>
            <!-- ============================= Section communication faq ============================= -->
            <section <?php echo esc_attr($section_id_attribute); ?> class="irantheme-section irantheme-communication-faq-section" <?php echo esc_attr(Irantheme_Page_Builder::get_attr_data_section($data_type, $data_value, $data_page, $data_ajax)); ?> style="<?php echo esc_attr(Irantheme_Section_Service::get_custom_outer_padding_css($fields_values)); ?>">
                <?php
                // Set wrapper (Container) --------------------------------------------------------------
                $container_checkbox = $fields_values['wrapper_mode_checkbox'];
                ?>
                <div class="irantheme-communication-faq <?php echo esc_attr($base_color_class); ?> irantheme-background-jarallax" style="<?php echo esc_attr(Irantheme_Section_Service::get_custom_inner_padding_css($fields_values)); ?><?php echo esc_attr($background_color_css); ?><?php echo esc_attr($background_image_css); ?>" <?php echo $background_parallax_attr; ?>>

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
                        <!-- Authors post -->
                        <div class="irantheme-communication-faq-post-wrapper">
                            <?php
                            // Service content alignment --------------------------------------------------------------
                            $communication_content_transfer = $fields_values['heading_transfer_checkbox'];
                            if ($communication_content_transfer != 'on') {
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
                            }
                            ?>
                            <?php
                            // Display mode (align of element) --------------------------------------------------------------
                            $display_mode_checkbox = $fields_values['display_mode'];
                            $display_mode_class = '';
                            if ($display_mode_checkbox == 'right') {
                                $display_mode_class = 'flex-row';
                            } elseif ($display_mode_checkbox == 'left') {
                                $display_mode_class = 'flex-row-reverse';
                            }
                            ?>
                            <div class="row justify-content-center <?php echo esc_attr($display_mode_class); ?>">
                                <?php
                                // Set column of faq in service status or nothing
                                $faq_bootstrap_class = 'col-lg-12';
                                // Check transform heading to columns or Show services
                                if ($communication_content_transfer == 'on') :
                                    $faq_bootstrap_class = 'col-lg-6';
                                ?>
                                    <div class="col-lg-6">
                                        <div class="col-lg-12" style="margin:0;display:flex;flex-direction:column;">
                                            <?php
                                            if ($tinymce_heading != '' && $heading_data_load_checkbox == 'on'):
                                            ?>
                                                <div class="irantheme-tinymce-heading-aos-holder" <?php echo ($heading_data_load_attr); ?> data-aos-delay="<?php echo ($heading_aos_delay); ?>" data-aos-duration="<?php echo ($heading_data_load_duration); ?>">
                                                <?php
                                            endif;
                                            // Get tinymce service content --------------------------------------------------------------
                                            Irantheme_Common_Static::tinymce_heading($fields_values);
                                            if ($tinymce_heading != '' && $heading_data_load_checkbox == 'on'):
                                                ?>
                                                </div>
                                            <?php
                                            endif;
                                            ?>
                                        </div>
                                    </div>
                                <?php
                                endif;
                                ?>

                                <div class="<?php echo esc_attr($faq_bootstrap_class); ?>">
                                    <?php
                                    // Set details colors --------------------------------------------------------------
                                    $color_details_text_color_class = Irantheme_Color_Static::get_class_color_mode('color-hover', null, Irantheme_Color_Static::get_hex_color($section_details_color));
                                    $color_details_text_i_color_class = Irantheme_Color_Static::get_class_color_mode('color-active', null, Irantheme_Color_Static::get_hex_color($section_details_color));
                                    ?>
                                    <div class="irantheme-object-group irantheme-communication-faq-list align-items-center" data-json="<?php echo esc_attr('objects_json'); ?>">
                                        <?php
                                        // Loop form all question names
                                        foreach ($json_post_array as $object_post) :
                                            // Generate brand name & value
                                            $faq_question_value = $object_post['fields']['question'];
                                            // Generate_brand_bg name & value
                                            $faq_answer_value = $object_post['fields']['answer'];
                                        ?>
                                            <div class="irantheme-object irantheme-communication-faq-list-item <?php echo esc_attr($transparent_mode_class); ?>" style="<?php echo esc_attr($border_radius_style); ?>" <?php echo ($data_load_attr); ?> data-aos-delay="<?php echo ($aos_delay); ?>" data-aos-duration="<?php echo ($data_load_duration); ?>" data-object="<?php echo esc_attr($object_post['object_id']); ?>">
                                                <!-- Question -->
                                                <h3 class="irantheme-communication-faq-list-item-question <?php echo esc_attr($color_details_text_color_class); ?> <?php echo esc_attr($color_details_text_i_color_class); ?>">
                                                    <cite class="irantheme-communication-faq-list-item-question-cite">
                                                        <i class="fa-light fa-circle-question"></i>
                                                        <?php echo esc_attr($faq_question_value); ?>
                                                    </cite>
                                                    <span class="irantheme-communication-faq-list-item-question-icon">
                                                        <i class="iconsax" icon-name="add"></i>
                                                    </span>
                                                </h3>
                                                <!-- Answer -->
                                                <div class="irantheme-communication-faq-list-item-answer">
                                                    <?php echo $faq_answer_value; ?>
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
