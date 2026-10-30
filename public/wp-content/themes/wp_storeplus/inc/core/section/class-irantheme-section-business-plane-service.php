<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;

if (!class_exists('Irantheme_Section_Business_Plane_Service')) {
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
    class Irantheme_Section_Business_Plane_Service extends Irantheme_Section
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
            $heading_content = '<span style="font-size: 1em;">ــــــــــــ پلن های پیشنهادی ــــــــــــ</span><h2 style="font-size:2.4em;font-weight:bold;">به دنبال میزبانی حرفه ای و مطمئن هستید؟</h2><p font-size:1em;>لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است.</p>';
            $settings = array(
                Irantheme_Section_Utils::heading_title_settings_generator($status_default, 'none', 'on', 'right', $heading_content, 'off', '', '', '', 'off', 'off', '', '', '', '', 'rectangle', 'right', 'off', '', 0, 1),
                array(
                    'title' => 'پلن ها',
                    'id' => 'plane',
                    'description'            => __('تنظیمات مربوط به محتوای پلن سرویس', self::$static_textdomain),
                    'icon' => 'fa-grid-2-plus',
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
                            $status_default ? Irantheme_Section_Default::get_default_ids_objects(3, 'business_plane_service', $status_default) : '',
                        ),
                        array(
                            'id'             => 'col_count_radio',
                            'label'            => __('تعداد ستون محتوا', self::$static_textdomain),
                            'description'    => __('با استفاده از این گزینه شما می توانید تعداد ستون برای نمایش پلن ها را مشخص نمایید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'thumbnail_status' => true,
                            'options'        => array(
                                'col-3' => array('سه ستون', 'col-3.png'),
                                'col-4' => array('چهار ستون', 'col-4.png'),
                                'col-6' => array('شش ستون', 'col-6.png')
                            ),
                            'default'        => $status_default ? 'col-4' : '',
                            'keywords' => array('business', 'col', 'brands', 'تعداد', 'کسب و کار', 'تعداد در هر ردیف'),
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
                            'id'             => 'post_alignment_radio',
                            'label'            => __('موقعیت محتوا', self::$static_textdomain),
                            'description'    => __('با استفاده از این گزینه می توانید موقعیت قرارگیری محتوای پست ها را تعیین کنید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'thumbnail_status' => true,
                            'options'        => array('right' => array('راست چین', 'text-align-right.png'), 'center' => array('وسط چین', 'text-align-center.png'), 'left' => array('چپ چین', 'text-align-left.png')),
                            'default'        => $status_default ? 'right' : '',
                            'keywords' => array('محتوا', 'تنظیم', 'تراز', 'محور', 'alignment', 'float', 'position')
                        ),
                        array(
                            'id'             => 'details_color_select',
                            'label'            => __(
                                'رنگ جزئیات پلن',
                                self::$static_textdomain
                            ),
                            'description'    => __('برای انتخاب رنگ جزئیات پلن از لیست رنگ دلخواه خود را انتخاب کنید. این رنگ تاثیر مستقیمی در زیبایی جزئيات دارد.', self::$static_textdomain),
                            'type'            => 'select',
                            'options'        => Irantheme_Color_Static::get_all_colors_options(),
                            'color_preview' => true,
                            'default'        => $status_default ? 'primary' : '',
                            'keywords' => array('plane service', 'plane', 'جزئیات پلن', 'کلید', 'رنگ'),
                        ),
                        array(
                            'id'             => 'post_border_checkbox',
                            'label'            => __(
                                'خط دور عناصر',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت فعال بودن این گزینه، یک خط (بوردر) دور هر پلن نمایش داده می شود.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'off' : '',
                            'keywords' => array('business', 'plane', 'کسب و کار',  'border'),
                        ),
                    )
                ),
                // Get layout scale fields
                Irantheme_Section_Utils::layout_scale_fields_generator($status_default, array('radius', 'inner_padding', 'outer_padding'), 'on', 2, 2, 'off', 'off', 0, 0, 1),
                // Get background fields
                Irantheme_Section_Utils::background_fields_generator($status_default, array('image', 'color', 'mask', 'parallax'), '', 'off', true, 'off', 'dark', 0.6, 'on'),
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
            $titles_default = '';
            $prices_default = '';
            $discounts_default = '';
            $description_default = '';
            $features_default = '';
            $button_text_default = '';
            $button_link_default = '';
            $modes_checkbox_default = '';
            $modes_types_default = '';
            $modes_texts_default = '';
            $box_style_default = '';
            $shadow_style_default = '';

            // Check status is deafult or no
            if (is_numeric($index)) {
                // List of default titles
                $titles = array(
                    'هاست وردپرس',
                    'هاست لینوکس',
                    'هاست ویندوز',
                );

                // List of default prices
                $prices = array(
                    '۲۵۰ هزار تومان',
                    '۱۷۰ هزار تومان',
                    '۱۲۰ هزار تومان'
                );

                // List of default discounts
                $discounts = array(
                    '۲۷۵ هزار تومان',
                    '۱۹۵ هزار تومان',
                    '۱۴۵ هزار تومان'
                );

                // Default description
                $description = __('لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است.');

                // List of features
                $features = array(
                    '<ul>
                            <li>فضا : <strong>۲۴۰</strong>GB</li>
                            <li>ترافیک : <strong>نامحدود</strong></li>
                            <li>سهم پردازنده :‌ <strong>۸۳۰۰</strong> مگاهرتز</li>
                            <li>میزبانی در دیتاسنتر های مطرح کشور</li>
                            <li>نوع میزبانی : <strong>تمام ابری</strong></li>
                        </ul>',
                    '<ul>
                            <li>فضا : <strong>۱۲۰</strong>GB</li>
                            <li>ترافیک : <strong>نامحدود</strong></li>
                            <li>سهم پردازنده :‌ <strong>۷۳۰۰</strong> مگاهرتز</li>
                            <li>میزبانی در دیتاسنتر های مطرح کشور</li>
                            <li>نوع میزبانی : <strong>تمام ابری</strong></li>
                        </ul>',
                    '<ul>
                            <li>فضا : <strong>۳۰</strong>GB</li>
                            <li>ترافیک : <strong>نامحدود</strong></li>
                            <li>سهم پردازنده :‌ <strong>۵۳۰۰</strong> مگاهرتز</li>
                            <li>میزبانی در دیتاسنتر های مطرح کشور</li>
                            <li>نوع میزبانی : <strong>تمام ابری</strong></li>
                        </ul>',
                );

                // Default button
                $button_text = __('ثبت سفارش');
                $button_link = __('#');

                // Default checkbox mode
                $modes_checkbox = array(
                    'on',
                    'on',
                    'off'
                );

                // Default mode type
                $modes_types = array(
                    'vip',
                    'special',
                    'special'
                );

                // Default mode text
                $modes_texts = array(
                    'طرح طلایی',
                    'طرح نقره ای',
                    ''
                );

                // Default box style
                $box_style = 'on';

                // Default shadow style
                $shadow_style = 'on';

                // Set default value
                $titles_default = $titles[$index];
                $prices_default = $prices[$index];
                $discounts_default = $discounts[$index];
                $description_default = $description;
                $features_default = $features[$index];
                $button_text_default = $button_text;
                $button_link_default = $button_link;
                $modes_checkbox_default = $modes_checkbox[$index];
                $modes_types_default = $modes_types[$index];
                $modes_texts_default = $modes_texts[$index];
                $box_style_default = $box_style;
                $shadow_style_default = $shadow_style;
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
                            'id'             => 'title',
                            'label'            => __(
                                'عنوان',
                                self::$static_textdomain

                            ),
                            'description'    => __('عنوان پلن تجاری خود را وارد کنید.'),
                            'type'            => 'text',
                            'default'        => $titles_default,
                            'placeholder'    => __('برای مثال: مشترکین VIP', self::$static_textdomain),
                        ),
                        array(
                            'id'             => 'price',
                            'label'            => __(
                                'قیمت پلن',
                                self::$static_textdomain

                            ),
                            'description'    => __('قیمت پلن تجاری خود را وارد کنید.'),
                            'type'            => 'text',
                            'default'        => $prices_default,
                            'placeholder'    => __('مثال: ۱۲۰ هزار تومان', self::$static_textdomain),
                        ),
                        array(
                            'id'             => 'price_discount',
                            'label'            => __(
                                'قیمت تخفیف پلن',
                                self::$static_textdomain

                            ),
                            'description'    => __('در صورت تمایل می توانید قیمت تخفیف خورده را وارد کنید.'),
                            'type'            => 'text',
                            'default'        => $discounts_default,
                            'placeholder'    => __('مثال: ۱۴۵ هزار تومان', self::$static_textdomain),
                        ),
                        array(
                            'id'             => 'desc',
                            'label'            => __(
                                'توضیحات',
                                self::$static_textdomain
                            ),
                            'description'    => __('توضیحات مربوط به پلن را وارد کنید.', self::$static_textdomain),
                            'type'            => 'textarea',
                            'default'        => $description_default,
                            'placeholder'    => __('توضیحات را وارد کنید', self::$static_textdomain),
                        ),
                        array(
                            'id'             => 'features_list',
                            'label'            => __('لیست ویژگی ها', self::$static_textdomain),
                            'description'    => __('عناوین یا لیستی از ویژگی های سرویس را عنوان کنید. برای مثال: عدم محدودیت ماهانه در دانلود, دسترسی به تمامی امکانات سایت, امکان اشتراک گذاری فایل و ...'),
                            'type'            => 'text_editor',
                            'default'        => $features_default,
                            'placeholder'    => __('', self::$static_textdomain),
                        ),
                        array(
                            'id'             => 'button_title',
                            'label'            => __(
                                'عنوان دکمه',
                                self::$static_textdomain

                            ),
                            'description'    => __('در صورت تمایل می توانید عنوان دکمه پلن را مشخص کنید.'),
                            'type'            => 'text',
                            'default'        => $button_text_default,
                            'placeholder'    => __('مثال: ثبت سفارش', self::$static_textdomain),
                        ),
                        array(
                            'id'             => 'button_link',
                            'label'            => __(
                                'لینک دکمه',
                                self::$static_textdomain

                            ),
                            'description'    => __('در صورت تمایل می توانید لینک دکمه پلن را مشخص کنید.'),
                            'type'            => 'text',
                            'default'        => $button_link_default,
                            'placeholder'    => __('مثال: https://irantheme.com/vipuser/payment', self::$static_textdomain),
                        ),
                        array(
                            'id'             => 'box_special_checkbox',
                            'label'            => __(
                                'فعال کردن طرح',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت فعال شدن این گزینه این پلن نسبت به پلن های دیگر در اولویت تجاری قرار می گیرد و تمایز بیشتری نسبت به دیگر پلن ها دارد.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $modes_checkbox_default,
                            'id_dependents_on' => array('box_special_mode', 'box_special_text'),
                            'id_dependents_off' => array(),
                            'base_priority' => 'checkbox_base',
                            'selector_priority' => 'checkbox_box_special',
                            'z_index' => 0
                        ),
                        array(
                            'id'             => 'box_special_mode',
                            'label'            => __('نوع طرح', self::$static_textdomain),
                            'description'    => __('با استفاده از این گزینه می توانید نوع طرح پلن را تعیین کنید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'options'        => array('vip' => 'طلایی', 'special' => 'نقره ای'),
                            'default'        => $modes_types_default,
                        ),
                        array(
                            'id'             => 'box_special_text',
                            'label'            => __(
                                'متن کادر طرح',
                                self::$static_textdomain

                            ),
                            'description'    => __('متن طرح خود را وارد کنید.'),
                            'type'            => 'text',
                            'default'        => $modes_texts_default,
                            'placeholder'    => __('مثال: طرح ویژه', self::$static_textdomain),
                        ),
                        array(
                            'id'             => 'box_style',
                            'label'            => __(
                                'باکس استایل',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت فعال بودن این گزینه پلن ها استایل جعبه ای پیدا می کنند.به این معنا که رنگ پس زمینه ای براساس رنگ پایه انتخابی شما روی آن اعمال می گردد.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $box_style_default,
                        ),
                        array(
                            'id'             => 'box_shadow',
                            'label'            => __(
                                'استایل سایه',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت فعال شدن این گزینه می توانید برای باکس پلن ها یک سایه ایجاد کنید.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $shadow_style_default,
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
            $aos_delay
                = Irantheme_Section_Utils::get_data_load_delay($fields_values);

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

            // Get columns count --------------------------------------------------------------
            $count_columns = $fields_values['col_count_radio'];
            $bootstrap_column_class = Irantheme_Section_Service::get_bootstrap_class($count_columns, 12);

            // Set item alignment --------------------------------------------------------------
            $business_item_alignment = $fields_values['post_alignment_radio'];

            // Details color --------------------------------------------------------------
            $service_details_color = $fields_values['details_color_select'];
            $service_details_color_class = '';
            if ($service_details_color == 'off') {
                $service_details_color_class = '';
            } else {
                $service_details_color_class = Irantheme_Color_Static::get_class_color_mode('color', null, Irantheme_Color_Static::get_hex_color($service_details_color));
            }

            // Transform heading to columns --------------------------------------------------------------
            $transform_heading_checkbox = $fields_values['heading_transfer_checkbox'];

            // Display mode --------------------------------------------------------------
            $display_mode_radio = $fields_values['display_mode'];
            $display_mode_class = '';
            if ($display_mode_radio == 'right') {
                $display_mode_class = 'flex-row';
            } elseif ($display_mode_radio == 'left') {
                $display_mode_class = 'flex-row-reverse';
            }

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
            <!-- ============================= Section business plane service ============================= -->
            <section <?php echo esc_attr($section_id_attribute); ?> class="irantheme-section irantheme-business-plane-service-section" <?php echo esc_attr(Irantheme_Page_Builder::get_attr_data_section($data_type, $data_value, $data_page, $data_ajax)); ?> style="<?php echo esc_attr(Irantheme_Section_Service::get_custom_outer_padding_css($fields_values)); ?>">
                <div class="irantheme-business irantheme-business-plane-service <?php echo esc_attr($base_color_class); ?> irantheme-background-jarallax" style="<?php echo esc_attr(Irantheme_Section_Service::get_custom_inner_padding_css($fields_values)); ?><?php echo esc_attr($background_color_css); ?><?php echo esc_attr($background_image_css); ?>" <?php echo $background_parallax_attr; ?>>

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

                    <!-- Inner container -->
                    <div class="irantheme-container-inner">
                        <?php
                        if ($transform_heading_checkbox == 'off') :
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
                        endif;
                        ?>

                        <?php

                        // Border post item --------------------------------------------------------------
                        $border_post_checkbox = $fields_values['post_border_checkbox'];
                        $border_post_class = '';
                        $border_post_css = '';
                        if ($border_post_checkbox == 'on') {
                            $border_post_class = 'irantheme-business-plane-service-post-item-border';

                            if ($service_details_color != 'off') {
                                $border_post_css = sprintf('border-color:#%s;', Irantheme_Color_Static::get_hex_color($service_details_color));
                            }
                        }

                        ?>
                        <!-- Plane service -->
                        <div class="irantheme-business-plane-service-post-wrapper">
                            <div class="irantheme-object-group row justify-content-center <?php echo esc_attr($transform_heading_checkbox == 'on' ? $display_mode_class : ''); ?>" data-json="<?php echo esc_attr('objects_json'); ?>">
                                <?php
                                // Check transform heading to columns
                                if ($transform_heading_checkbox == 'on') :
                                ?>
                                    <div class="<?php echo esc_attr($bootstrap_column_class); ?> d-flex flex-column justify-content-center">
                                        <?php
                                        if ($tinymce_heading != '' && $heading_data_load_checkbox == 'on'):
                                        ?>
                                            <div class="irantheme-tinymce-heading-aos-holder" <?php echo ($heading_data_load_attr); ?> data-aos-delay="<?php echo ($heading_aos_delay); ?>" data-aos-duration="<?php echo ($heading_data_load_duration); ?>">
                                            <?php
                                        endif;
                                            ?>
                                            <?php
                                            // Get tinymce content --------------------------------------------------------------
                                            Irantheme_Common_Static::tinymce_heading($fields_values, 'width:100%;');
                                            if ($tinymce_heading != '' && $heading_data_load_checkbox == 'on'):
                                            ?>
                                            </div>
                                        <?php
                                            endif;
                                        ?>
                                    </div>
                                <?php
                                endif;
                                // Loop form all services names
                                foreach ($json_post_array as $object_post) :
                                    // Get plane title
                                    $plane_title = $object_post['fields']['title'];
                                    // Get plane price
                                    $plane_price = $object_post['fields']['price'];
                                    // Get plane discount price
                                    $plane_discount = $object_post['fields']['price_discount'];
                                    // Get plane description
                                    $plane_desc = $object_post['fields']['desc'];
                                    // Get plane list of features
                                    $plane_features_list = $object_post['fields']['features_list'];
                                    // Get plane button title
                                    $plane_btn_title = $object_post['fields']['button_title'];
                                    // Get plane button link
                                    $plane_btn_link = $object_post['fields']['button_link'];
                                    // Get plane box style
                                    $plane_box_style = $object_post['fields']['box_style'];
                                    // Get plane box shadow
                                    $plane_box_shadow = $object_post['fields']['box_shadow'];
                                    // Get plane special box
                                    $plane_box_special = $object_post['fields']['box_special_checkbox'];
                                    // Get plane special box mode
                                    $plane_box_special_mode = $object_post['fields']['box_special_mode'];
                                    // Get plane special box text
                                    $plane_box_special_text = $object_post['fields']['box_special_text'];

                                    // Classes of box style
                                    $plane_box_style_class = '';
                                    if ($plane_box_style == 'on') {
                                        $plane_box_style_class = 'irantheme-business-plane-service-post-item-box-style';
                                    }

                                    // Classes of box shadow
                                    $plane_box_shadow_class = '';
                                    if ($plane_box_shadow == 'on') {
                                        $plane_box_shadow_class = 'irantheme-business-plane-service-post-item-box-shadow';
                                    }
                                ?>
                                    <div class="irantheme-object <?php echo esc_attr($bootstrap_column_class); ?>" <?php echo ($data_load_attr); ?> data-aos-delay="<?php echo ($aos_delay); ?>" data-aos-duration="<?php echo ($data_load_duration); ?>" data-object="<?php echo esc_attr($object_post['object_id']); ?>">
                                        <!-- Post item -->
                                        <div class="irantheme-business-plane-service-post-item <?php echo esc_attr(Irantheme_Section_Service::get_alignment_class($business_item_alignment)); ?> <?php echo esc_attr($plane_box_style_class); ?> <?php echo esc_attr($plane_box_shadow_class); ?> <?php echo esc_attr($border_post_class); ?>" style="<?php echo esc_attr($border_post_css); ?><?php echo esc_attr($border_radius_style); ?>">
                                            <?php
                                            // Special box mode --------------------------------------------------------------
                                            if ($plane_box_special == 'on') {
                                                if ($plane_box_special_mode == 'special') {
                                            ?>
                                                    <div class="irantheme-business-plane-service-post-item-mode-special irantheme-bg-color-gray">
                                                        <i class="iconsax" icon-name="crown"></i>
                                                        <?php if ($plane_box_special_text) : ?>
                                                            <cite><?php _e($plane_box_special_text); ?></cite>
                                                        <?php else : ?>
                                                            <cite><?php _e('طرح نقره ای'); ?></cite>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php
                                                } elseif ($plane_box_special_mode == 'vip') {
                                                ?>
                                                    <div class="irantheme-business-plane-service-post-item-mode-vip irantheme-bg-color-yellow">
                                                        <i class="iconsax" icon-name="crown"></i>
                                                        <?php if ($plane_box_special_text) : ?>
                                                            <cite><?php _e($plane_box_special_text); ?></cite>
                                                        <?php else : ?>
                                                            <cite><?php _e('طرح طلایی'); ?></cite>
                                                        <?php endif; ?>
                                                    </div>
                                            <?php
                                                }
                                            }
                                            ?>

                                            <?php if ($plane_title) : ?>
                                                <!-- Plane title -->
                                                <div class="irantheme-business-plane-service-post-item-title"><?php _e($plane_title); ?></div>
                                            <?php endif; ?>
                                            <?php if ($plane_desc) : ?>
                                                <!-- Plane description -->
                                                <div class="irantheme-business-plane-service-post-item-desc"><?php _e($plane_desc); ?></div>
                                            <?php endif; ?>
                                            <?php if ($plane_price || $plane_discount) : ?>
                                                <?php
                                                $price_alignment_css = '';
                                                if ($business_item_alignment == 'right') {
                                                    $price_alignment_css = 'align-items:flex-start';
                                                } elseif ($business_item_alignment == 'left') {
                                                    $price_alignment_css = 'align-items:flex-end';
                                                } elseif ($business_item_alignment == 'center') {
                                                    $price_alignment_css = 'align-items:center';
                                                }
                                                ?>
                                                <!-- Plane price -->
                                                <div class="irantheme-business-plane-service-post-item-price" style="<?php echo esc_attr($price_alignment_css); ?>">
                                                    <?php if ($plane_price) : ?>
                                                        <span class="irantheme-business-plane-service-post-item-price-main <?php echo esc_attr($service_details_color_class); ?>"><?php _e($plane_price); ?></span>
                                                    <?php endif; ?>
                                                    <?php if ($plane_discount) : ?>
                                                        <span class="irantheme-business-plane-service-post-item-price-discount"><?php _e($plane_discount); ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                            <?php if ($plane_features_list) : ?>
                                                <!-- Plane features list -->
                                                <div class="irantheme-business-plane-service-post-item-features-list">
                                                    <?php
                                                    // Get tinymce simple text
                                                    Irantheme_Common_Static::get_snippet_tinymce_simple($plane_features_list, $base_color_class, '', Irantheme_Section_Service::get_alignment_class($business_item_alignment));
                                                    ?>
                                                </div>
                                            <?php endif; ?>
                                            <?php if ($plane_btn_title || $plane_btn_link) : ?>
                                                <?php
                                                $button_class_color = '';
                                                if ($base_color == 'dark') {
                                                    $button_class_color = 'irantheme-btn-white-outline';
                                                } elseif ($base_color == 'white') {
                                                    $button_class_color = 'irantheme-btn-dark-outline';
                                                }
                                                ?>
                                                <!-- Plane button -->
                                                <div class="irantheme-business-plane-service-post-item-button">
                                                    <a href="<?php echo esc_url($plane_btn_link); ?>" class="irantheme-btn irantheme-btn-medium irantheme-btn-inline <?php echo esc_attr($button_class_color); ?>">
                                                        <?php
                                                        if ($plane_btn_title) {
                                                            _e($plane_btn_title);
                                                        } else {
                                                            _e('جزئیات بیشتر', IRANTHEME_TEXTDOMAIN);
                                                        }
                                                        ?>
                                                    </a>
                                                </div>
                                            <?php endif; ?>
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
