<?php

namespace Irantheme_Dev;


if (!defined('ABSPATH')) exit;

if (!class_exists('Irantheme_Section_Business_Department')) {
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
    class Irantheme_Section_Business_Department extends Irantheme_Section
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
            $heading_content = '<span style="font-size: 1em;">ــــــــــــــــــــــــ رستوران های ما </span>
<h2 style="font-size: 2rem;"><span style="font-size: 1.2em;"><strong>دیگر شعبه ها</strong></span></h2>';
            $settings = array(
                Irantheme_Section_Utils::heading_title_settings_generator($status_default, 'none', 'off', 'right', $heading_content, 'off', 'ارتباط با پشتیبانی', '#', '', 'transparent', 'off', '', '', '', 'off', 'rectangle', 'right', 'off', '', 0, 1),
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
                            'default'        => $status_default ? Irantheme_Section_Default::get_default_ids_objects(5, 'business_department', $status_default) : '',
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
                            'default'        => $status_default ? 'on' : '',
                            'keywords' => array('استایل سایه', 'product shadow', 'shadow style'),
                        ),
                        array(
                            'id'             => 'transparent_checkbox',
                            'label'            => __(
                                'رنگ زمینه شیشه ای',
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
            $title_default = '';
            $small_title_default = '';
            $images_default = '';
            $description_default = '';

            // Check status is deafult or no
            if (is_numeric($index)) {
                // List of default titles
                $title = array(
                    'رستوران ایرانی سنتی',
                    'رستوران ایرانی مدرن',
                    'رستوران دیزی سنگی',
                    'رستوران مدیترانه ای',
                    'رستوران و فست فود',
                );

                // List of small titles of department
                $small_title = array(
                    'کباب کوبیده.کباب چنجه.جوجه کباب',
                    'شیشلیک.کباب قفقازی.ماهی کبابی',
                    'دیزی سنگی.ماهیچه و گردن.کته ماش',
                    'حمص (هوموس).کباب شاورما.بورک',
                    'پیتزا.همبرگر.ساندویچ.اسنک',
                );

                // List of department images
                $images = array(
                    'https://irantheme.com/wp-content/uploads/2026/02/4yzhgos1.webp',
                    'https://irantheme.com/wp-content/uploads/2026/02/7vuq3ucj.webp',
                    'https://irantheme.com/wp-content/uploads/2026/02/vum8t249.webp',
                    'https://irantheme.com/wp-content/uploads/2026/02/pyi6oag7.webp',
                    'https://irantheme.com/wp-content/uploads/2026/02/xa9ybt1c.webp',
                );

                // Default content
                $content = 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است.';

                // Set default value
                $title_default = $title[$index];
                $small_title_default = $small_title[$index];
                $images_default = $status_default === 'media' ? Irantheme_Common_Static::get_file_url_media_library($images[$index]) : '';
                $description_default = $content;
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
                            'label'            => __('تصویر بخش', self::$static_textdomain),
                            'description'    => __('در صورت تمایل می توانید تصویر بخش مورد نظر را بارگزاری نمایید.', self::$static_textdomain),
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
                            'description'    => __('عنوان بخش را وارد کنید.'),
                            'type'            => 'text',
                            'default'        => $title_default,
                            'placeholder'    => __('عنوان', self::$static_textdomain),
                        ),
                        array(
                            'id'             => 'small_title',
                            'label'            => __(
                                'عنوانک ها',
                                self::$static_textdomain

                            ),
                            'description'    => __('با استفاده از عنوانک می توانید کلمات مربوط، مواد مربوط به بخش یا قیمت ها مشخص را وارد نمایید.'),
                            'type'            => 'text',
                            'default'        => $small_title_default,
                            'placeholder'    => __('عنوان کوچک', self::$static_textdomain),
                            'note' => 'نکته: با استفاده از حرف "." نقطه می توانید عنوانک ها را جدا نمایید.'
                        ),
                        array(
                            'id'             => 'desc',
                            'label'            => __(
                                'توضیحات',
                                self::$static_textdomain
                            ),
                            'description'    => __('در اینجا شما می توانید توضیحات، آدرس یا هر نقل قول مورد نیاز را وارد نمایید.', self::$static_textdomain),
                            'type'            => 'textarea',
                            'default'        => $description_default,
                            'placeholder'    => __('توضیحات را وارد کنید', self::$static_textdomain),
                        ),
                        array(
                            'id'             => 'desc_icon',
                            'label'            => __(
                                'آیکون توضیحات',
                                self::$static_textdomain
                            ),
                            'description'    => __('آیکون مربوط به توضیحات را انتخاب نمایید.', self::$static_textdomain),
                            'type'            => 'icon',
                            'default'        => 'fa-light fa-location-dot',
                            'placeholder'    => __('', self::$static_textdomain),
                        ),
                        array(
                            'id'             => 'url',
                            'label'            => __(
                                'لینک بخش',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورتی که علاقمند به استفاده از یک لینک جهت ارجاع کاربران به صفحه مورد نظر هستید لینک خود را وارد کنید.'),
                            'type'            => 'text',
                            'default'        => '#',
                            'placeholder'    => __('لینک دلخواه', self::$static_textdomain),
                        ),
                        array(
                            'id'             => 'details_color_select',
                            'label'            => __(
                                'رنگ جزئیات',
                                self::$static_textdomain
                            ),
                            'description'    => __('برای انتخاب رنگ جزئیات پلن از لیست رنگ دلخواه خود را انتخاب کنید. این رنگ تاثیر مستقیمی در زیبایی جزئيات دارد.', self::$static_textdomain),
                            'type'            => 'select',
                            'options'        => Irantheme_Color_Static::get_all_colors_options(),
                            'color_preview' => true,
                            'default'        => 'primary',
                            'keywords' => array('رنگ جزئيات', 'detail color'),
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
                $box_shadow_class = 'irantheme-business-department-item-shadow';
            }

            // Get product box shadow checkbox --------------------------------------------------------------
            $product_transparent_checkbox = $fields_values['transparent_checkbox'];

            $transparent_class = '';
            if ($product_transparent_checkbox == 'on') {
                $transparent_class = 'irantheme-business-department-post-item-transparent';
            }

            // Get section id --------------------------------------------------------------
            $section_id = $fields_values['element_id'];
            $section_id_attribute = '';
            if ($section_id) {
                $section_id_attribute = sprintf('id=%s', $section_id);
            }
?>
            <!-- ========================== Section business department post ========================== -->
            <section <?php echo esc_attr($section_id_attribute); ?> class="irantheme-section irantheme-business-department-section" <?php echo esc_attr(Irantheme_Page_Builder::get_attr_data_section($data_type, $data_value, $data_page, $data_ajax)); ?> style="<?php echo esc_attr(Irantheme_Section_Service::get_custom_outer_padding_css($fields_values)); ?>">
                <div class="irantheme-business irantheme-business-department <?php echo esc_attr($base_color_class); ?> irantheme-background-jarallax" style="<?php echo esc_attr($background_color_css); ?><?php echo esc_attr($background_image_css); ?><?php echo esc_attr(Irantheme_Section_Service::get_custom_inner_padding_css($fields_values)); ?>" <?php echo $background_parallax_attr; ?>>

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
                        <div class=" irantheme-container-inner">
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
                        // Set slider mode --------------------------------------------------------------
                        $slider_mode_wrapper_class = '';
                        $slider_mode_row  = 'row justify-content-center';
                        ?>
                        <!-- Authors post -->
                        <div class="irantheme-business-department-post-wrapper <?php echo esc_attr($slider_mode_wrapper_class); ?>">
                            <div class="irantheme-object-group <?php echo esc_attr($slider_mode_row); ?>" data-json="<?php echo esc_attr('objects_json'); ?>">
                                <?php
                                foreach ($json_post_array as $object_post) :
                                    // Get post meta --------------------------------------------------------------
                                    // Author image
                                    $department_image = $object_post['fields']['image'];
                                    // Author title
                                    $department_title =
                                        $object_post['fields']['title'];
                                    // Author small title
                                    $department_small_title =
                                        $object_post['fields']['small_title'];
                                    // Author description
                                    $department_description =
                                        $object_post['fields']['desc'];
                                    // Author description icon
                                    $department_description_icon =
                                        $object_post['fields']['desc_icon'];
                                    // Author details url
                                    $department_url =
                                        $object_post['fields']['url'];
                                    // Author details url
                                    $details_color =
                                        $object_post['fields']['details_color_select'];
                                ?>
                                    <div class="irantheme-object" data-object="<?php echo esc_attr($object_post['object_id']); ?>">
                                        <!-- Post item -->
                                        <div class="irantheme-business-department-post-item <?php echo esc_attr($box_shadow_class); ?> <?php echo esc_attr($transparent_class); ?>" style="<?php echo esc_attr($border_radius_style); ?>" <?php echo ($data_load_attr); ?> data-aos-delay="<?php echo ($aos_delay); ?>" data-aos-duration="<?php echo ($data_load_duration); ?>">
                                            <div class="row">
                                                <?php
                                                // check author image
                                                if ($department_image) :
                                                ?>
                                                    <div class="col-lg-2 col-md-3 mb-0">
                                                        <!-- Author image -->
                                                        <div class="irantheme-business-department-post-item-image">
                                                            <?php
                                                            echo wp_get_attachment_image(
                                                                $department_image,
                                                                'medium',
                                                                false,
                                                                [
                                                                    'class' => 'irantheme-business-department-post-item-image-src',
                                                                    'alt' => esc_attr($department_title ?: __('تصویر دپارتمان', self::$static_textdomain)),
                                                                    'loading' => 'lazy',
                                                                    'style' => esc_attr($border_radius_style),
                                                                ]
                                                            );
                                                            ?>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if ($department_title || $department_small_title) : ?>
                                                    <div class="col-lg-6 col-md-5 mb-0">
                                                        <!-- Heading -->
                                                        <div class="irantheme-business-department-post-item-heading">
                                                            <?php if ($department_title) : ?>
                                                                <!-- Title -->
                                                                <h4 class="irantheme-business-department-post-item-heading-title"><?php echo esc_html($department_title); ?></h4>
                                                            <?php endif; ?>
                                                            <?php if ($department_small_title) : ?>
                                                                <!-- Small title -->
                                                                <ul class="irantheme-business-department-post-item-heading-small-title-list">
                                                                    <?php
                                                                    $department_small_title_array = explode('.', $department_small_title);
                                                                    foreach ($department_small_title_array as $department_small_title_item) :
                                                                    ?>
                                                                        <li class="irantheme-business-department-post-item-heading-small-title-list-item"><?php echo esc_html($department_small_title_item); ?></li>
                                                                    <?php
                                                                    endforeach;
                                                                    ?>
                                                                </ul>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="col-lg-4 col-md-4 mr-auto mb-0">
                                                    <!-- Content -->
                                                    <div class="irantheme-business-department-post-item-description">
                                                        <div class="row">
                                                            <?php
                                                            // Description display --------------------------------------------------------------
                                                            if ($department_description) :
                                                            ?>
                                                                <div class="col mb-0">
                                                                    <i class="<?php echo esc_attr($department_description_icon); ?> irantheme-business-department-post-item-description-icon"></i>
                                                                    <!-- Description -->
                                                                    <p class="irantheme-business-department-post-item-description-desc"><?php echo esc_html($department_description); ?></p>
                                                                </div>
                                                            <?php endif; ?>
                                                            <?php
                                                            // Description display --------------------------------------------------------------
                                                            if ($department_url) :
                                                            ?>
                                                                <div class="col-2 mb-0">
                                                                    <!-- Arrow link -->
                                                                    <div class="irantheme-business-department-post-item-description-link">
                                                                        <?php
                                                                        // Arrow url --------------------------------------------------------------
                                                                        $details_color_css = '';
                                                                        $details_color_rgba_css = '';
                                                                        $details_color_rgb_array = Irantheme_Color_Static::hex_to_rgb(Irantheme_Color_Static::get_hex_color($details_color));
                                                                        if ($details_color != 'off') {
                                                                            $details_color_css = 'color:#' . esc_attr(Irantheme_Color_Static::get_hex_color($details_color)) . ';';
                                                                            $details_color_rgba_css = 'rgba(' . $details_color_rgb_array[0] . ',' . $details_color_rgb_array[1] . ',' . $details_color_rgb_array[2] . ',' . '0.1' . ')';
                                                                        }
                                                                        ?>
                                                                        <a href="<?php echo esc_url_raw($department_url); ?>" style="background-color:<?php echo $details_color_rgba_css; ?>;">
                                                                            <i class="fa-light fa-arrow-turn-down-left irantheme-business-department-post-item-star-icon" style="<?php echo esc_attr($details_color_css); ?>"></i>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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
