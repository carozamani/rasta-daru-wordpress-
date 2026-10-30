<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;

if (!class_exists('Irantheme_Section_Woocommerce_Hotspot')) {
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
    class Irantheme_Section_Woocommerce_Hotspot extends Irantheme_Section
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
                Irantheme_Section_Utils::heading_title_settings_generator($status_default, 'none', false, 'center', $heading_content, 'off', '', '', '', 'off', 'off', '', '', '', 'off', 'rectangle', 'right', 'off', '', 0, 1),
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
                            $status_default ? Irantheme_Section_Default::get_default_ids_objects(3, 'woocommerce_hotspot', $status_default) : '',
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
                Irantheme_Section_Utils::background_fields_generator($status_default, array('image', 'color'), $status_default === 'media' ? Irantheme_Common_Static::get_file_url_media_library('https://irantheme.com/wp-content/uploads/2026/02/42h5ifqj.webp') : '', 'off', true),
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
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        public static function object_fields($index, $status_default = false)
        {
            $products_default = '';
            $position_top_default = '';
            $position_left_default = '';

            // Check status is deafult or no
            if (is_numeric($index)) {
                // List of default products
                $products = array(
                    array_keys(Irantheme_Custom_Fields::get_all_posts('product'))[0],
                    array_keys(Irantheme_Custom_Fields::get_all_posts('product'))[1],
                    array_keys(Irantheme_Custom_Fields::get_all_posts('product'))[2],
                );
                $position_top = array(
                    25,
                    59,
                    76,
                );
                // Default position_left
                $position_left = array(
                    72,
                    57,
                    65,
                );

                // Set default value
                $products_default = $products[$index];
                $position_top_default = $position_top[$index];
                $position_left_default = $position_left[$index];
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
                            'id'             => 'product_selection',
                            'label'            => __(
                                'انتخاب دستی محصول',
                                self::$static_textdomain
                            ),
                            'description'    => __('شما می توانید محصول مورد نظر خود را به صورت دستی انتخاب کنید. این محصول در نقطه کانون داغ به نمایش در خواهد آمد.', self::$static_textdomain),
                            'type'            => 'select',
                            'options'        => Irantheme_Custom_Fields::get_all_posts('product'),
                            'default' => $products_default,
                            'placeholder' => __('محتوا خالی می باشد', self::$static_textdomain)
                        ),
                        array(
                            'id'             => 'woocommerce_hotspot_position_top',
                            'label'            => __(
                                'فاصله از بالا',
                                self::$static_textdomain
                            ),
                            'description'    => __('فاصله کانون داغ را از بالا به پایین با واحد درصد مشخص کنید.', self::$static_textdomain),
                            'type'            => 'range',
                            'step' => 1,
                            'default'        => $position_top_default,
                            'min' => 0,
                            'max' => 100,
                            'placeholder'    => __('', self::$static_textdomain),
                        ),
                        array(
                            'id'             => 'woocommerce_hotspot_position_left',
                            'label'            => __(
                                'فاصله از چپ',
                                self::$static_textdomain
                            ),
                            'description'    => __('فاصله کانون داغ را از چپ به راست با واحد درصد مشخص کنید.', self::$static_textdomain),
                            'type'            => 'range',
                            'step' => 1,
                            'default'        => $position_left_default,
                            'min' => 0,
                            'max' => 100,
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
            // Background options ------------------------------------------------------------
            $background_image_data = $fields_values['bg_image'];
            $background_color_data = $fields_values['bg_color'];
            $background_color_css = Irantheme_Section_Utils::get_background_color_css($background_color_data);
            // Get data load style --------------------------------------------------------------
            $data_load_attr = Irantheme_Section_Utils::get_data_load_attr_type($fields_values);
            // Get data load duration
            $data_load_duration = Irantheme_Section_Utils::get_data_load_duration($fields_values);
            // Get aos delay time
            $aos_delay = Irantheme_Section_Utils::get_data_load_delay($fields_values);

            // Get data load style --------------------------------------------------------------
            $heading_data_load_checkbox = $fields_values['heading_data_load_checkbox'];
            $heading_data_load_attr = Irantheme_Section_Utils::get_heading_data_load_attr_type($fields_values);
            // Get data load duration
            $heading_data_load_duration = Irantheme_Section_Utils::get_heading_data_load_duration($fields_values);
            // Get aos delay time
            $heading_aos_delay = Irantheme_Section_Utils::get_heading_data_load_delay($fields_values);

            // Json post data --------------------------------------------------------------
            $json_post_array = Irantheme_JSON_Helper::normalize_objects($fields_values['objects_json']);

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
            <!-- ============================= Section woocommerce hotspot ============================= -->
            <section <?php echo esc_attr($section_id_attribute); ?> class="irantheme-section irantheme-woocommerce-products-section irantheme-woocommerce-hotspot-section" <?php echo esc_attr(Irantheme_Page_Builder::get_attr_data_section($data_type, $data_value, $data_page, $data_ajax)); ?> style="<?php echo esc_attr(Irantheme_Section_Service::get_custom_outer_padding_css($fields_values)); ?>">
                <?php
                if (is_woocommerce_activated()) :
                    // Set wrapper (Container) --------------------------------------------------------------
                    $container_checkbox = $fields_values['wrapper_mode_checkbox'];
                ?>
                    <div class="irantheme-woocommerce-hotspot irantheme-woocommerce-products <?php echo esc_attr($base_color_class); ?>" style="<?php echo esc_attr($background_color_css); ?><?php echo esc_attr(Irantheme_Section_Service::get_custom_inner_padding_css($fields_values)); ?>">
                        <?php
                        if ($container_checkbox != 'on') :
                        ?>
                            <div class="irantheme-container-inner">
                            <?php endif; ?>
                            <?php
                            // Get woocommerce hotspot heading --------------------------------------------------------------
                            $woocommerce_hotspot_heading = $fields_values['text_editor_heading'];
                            ?>
                            <?php
                            if ($woocommerce_hotspot_heading != '' && $heading_data_load_checkbox == 'on'):
                            ?>
                                <div class="irantheme-tinymce-heading-aos-holder" <?php echo ($heading_data_load_attr); ?> data-aos-delay="<?php echo ($heading_aos_delay); ?>" data-aos-duration="<?php echo ($heading_data_load_duration); ?>">
                                <?php
                            endif;
                                ?>
                                <?php
                                Irantheme_Common_Static::tinymce_heading($fields_values);
                                if ($woocommerce_hotspot_heading != '' && $heading_data_load_checkbox == 'on'):
                                ?>
                                </div>
                            <?php
                                endif;
                            ?> <!-- Image -->
                            <div class="irantheme-woocommerce-hotspot-image">
                                <?php if ($background_image_data) : ?>
                                    <!-- Image -->
                                    <?php
                                    echo wp_get_attachment_image(
                                        $background_image_data,
                                        'large',
                                        false,
                                        [
                                            'class' => 'irantheme-woocommerce-hotspot-image-img',
                                            'alt' => __('خطا در بارگذاری تصویر', self::$static_textdomain),
                                            'loading' => 'lazy',
                                            'style' => esc_attr($border_radius_style),
                                        ]
                                    );
                                    ?>
                                <?php else : ?>
                                    <?php
                                    // Empty thumbnail
                                    Irantheme_Section_Service::empty_thumbnail($base_color);
                                    ?>
                                <?php endif; ?>
                                <!-- Dots -->
                                <div class="irantheme-woocommerce-hotspot-image-dots">
                                    <!-- Group objects -->
                                    <div class="irantheme-object-group irantheme-no-sort" data-json="<?php echo esc_attr('objects_json'); ?>">
                                        <?php
                                        // Loop form all services names
                                        foreach ($json_post_array as $object_post) :
                                            $woocommerce_hotspot_dot_product_id = $object_post['fields']['product_selection'];
                                            $woocommerce_hotspot_dot_position_top = $object_post['fields']['woocommerce_hotspot_position_top'];
                                            $woocommerce_hotspot_dot_position_left = $object_post['fields']['woocommerce_hotspot_position_left'];
                                            $hotspot_style = 'top:' . $woocommerce_hotspot_dot_position_top . '%;left:' . $woocommerce_hotspot_dot_position_left . '%;';
                                        ?>
                                            <!-- Hotspot product -->
                                            <div class="irantheme-object irantheme-woocommerce-hotspot-image-dots-hot" data-object="<?php echo esc_attr($object_post['object_id']); ?>" style="<?php echo esc_attr($hotspot_style); ?>" <?php echo ($data_load_attr); ?> data-aos-delay="<?php echo ($aos_delay); ?>" data-aos-duration="<?php echo ($data_load_duration); ?>">
                                                <?php
                                                // Product info
                                                $product = wc_get_product($woocommerce_hotspot_dot_product_id);

                                                // Get product item
                                                Irantheme_Section_Woocommerce::get_product_item($product, array('image', 'title', 'price'), $base_color, 'primary', 'secondary', 'on', 'off', 'off', 'off', '', '', '', '', '', '', 'center', 'irantheme-woocommerce-products-item-shadow', 'col-12', 12, 'off', '', '', '', $border_radius_range, 'irantheme-woocommerce-products-item-padding', '');
                                                ?>

                                                <?php
                                                Irantheme_Page_Builder::get_hidden_require_object_buttons($data_page);
                                                ?>
                                            </div>
                                        <?php
                                            $aos_delay += 150;
                                        endforeach;
                                        ?>
                                        <?php
                                        Irantheme_Page_Builder::get_hidden_require_object_group_buttons($data_page);
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
                else :
                    // Empty status
                    irantheme_display_empty_status('empty-set', 'متاسفانه افزونه ووکامرس فعال یا نصب نمی باشد.', 'با رفتن به محیط ادمین و تب "افزونه ها" ووکامرس را نصب یا فعال نمایید.');
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
