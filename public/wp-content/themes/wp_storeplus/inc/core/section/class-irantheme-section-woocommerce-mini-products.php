<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;

if (!class_exists('Irantheme_Section_Woocommerce_Mini_Products')) {
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
    class Irantheme_Section_Woocommerce_Mini_Products extends Irantheme_Section
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
            $settings = array(
                array(
                    'title' => 'عنوان',
                    'id' => 'heading',
                    'description'            => __('تنظیمات بصری مانند : تصویر زمینه، ارتفاع، رنگ ها و...', self::$static_textdomain),
                    'icon' => 'fa-heading',
                    'fields' => array(
                        array(
                            'id'             => 'heading_text',
                            'label'            => __('عنوان مجموعه', self::$static_textdomain),
                            'description'    => __('عنوان مناسبی برای مجموعه محصولات انتخابی خود برگزینید.'),
                            'type'            => 'text',
                            'default'        => 'پر فروش ترین محصولات',
                            'placeholder'    => __('', self::$static_textdomain),
                            'keywords' => array('عنوان مجموعه', 'title of products')
                        ),
                        array(
                            'id'             => 'heading_link',
                            'label'            => __('لینک مجموعه', self::$static_textdomain),
                            'description'    => __('لینک مشاهده بیشتر برای مجموعه محصولات انتخابی خود برگزینید.'),
                            'type'            => 'text',
                            'default'        => '#',
                            'placeholder'    => __('', self::$static_textdomain),
                            'keywords' => array('لینک مجموعه', 'link of products')
                        ),
                    )
                ),
                Irantheme_Section_Utils::get_product_content_type_fields(true, 'sales', 6),
                array(
                    'title' => 'تنظیم ویژگی ها',
                    'id' => 'products',
                    'description'            => __('تنظیمات بصری مانند : تصویر زمینه، ارتفاع، رنگ ها و...', self::$static_textdomain),
                    'icon' => 'fa-filter-list',
                    'fields' => array(
                        array(
                            'id'             => 'features_checkbox_multi',
                            'label'            => __(
                                'ویژگی های محصول',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید نوع جزئیات قابل نمایش برای هر محصول را مشخص کنید.مانند عنوان، دسته بندی و غیره... ', self::$static_textdomain),
                            'type'            => 'checkbox_multi',
                            'options'        => array('image' => 'تصویر', 'category' => 'دسته بندی', 'title' => 'عنوان', 'rating' => 'امتیاز', 'price' => 'قیمت', 'sale' => 'تعداد فروش'),
                            'default'        => $status_default ? 'image,category,title,rating,price,sale' : '',
                            'keywords' => array('محصولات', 'محصولات ووکامرس', 'woocommerce products')
                        ),
                        array(
                            'id'             => 'col_count_radio',
                            'label'            => __('تعداد ستون محصولات', self::$static_textdomain),
                            'description'    => __('با استفاده از این گزینه شما می توانید تعداد ستون های قابل نمایش برای محصولات را مشخص کنید', self::$static_textdomain),
                            'type'            => 'radio',
                            'thumbnail_status' => true,
                            'options'        => array(
                                'col-3' => array('سه ستون', 'col-3.png'),
                                'col-4' => array('چهار ستون', 'col-4.png'),
                                'col-5' => array('پنج ستون', 'col-5.png'),
                                'col-6' => array('شش ستون', 'col-6.png'),
                            ),
                            'default'        => $status_default ? 'col-4' : '',
                            'keywords' => array('box', 'col', 'author', 'تعداد', 'سرویس', 'تعداد در هر ردیف'),
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
                            'id'             => 'image_height_range',
                            'label'            => __(
                                'ارتفاع تصویر محصولات',
                                self::$static_textdomain
                            ),
                            'description'    => __('با اسفاده از این گزینه می توانید ارتفاع تصویر محصولات را تنظیم نمایید.(واحد px می باشد)', self::$static_textdomain),
                            'type'            => 'range',
                            'min' => 40,
                            'max' => 140,
                            'step' => 1,
                            'default'        => $status_default ? 100 : '',
                            'placeholder'    => __('Placeholder text', self::$static_textdomain),
                            'keywords' => array('ارتفاع تصویر', 'ارتفاع تصویر محصولات', 'ارتفاع',  'height', 'height of product')
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
                            'id'             => 'image_hover_mode_radio',
                            'label'            => __(
                                'سبک واکنش به تصویر (hover)',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید نوع واکنش به عبور نشانگر از روی تصویر محصول (hover) را مشخص کنید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'options'        => array('normal' => 'ساده', 'shine' => 'رهگذر', 'flashing' => 'فلش', 'circle' => 'گرد'),
                            'default'        => $status_default ? 'normal' : '',
                            'keywords' => array('واکنش نشانگر محصولات', 'استایل محصولات', 'ویژگی های محصولات', 'product hover', 'woocommerce product style', 'product style', 'hover product', 'hover', 'هاور')
                        ),
                        array(
                            'id'             => 'image_changer_checkbox',
                            'label'            => __(
                                'تغییر تصویر در زمان هاور',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت تمایل به تغییر تصویر با تصاویر گالری در زمان واکنش ماوس (Hover) می توانید از این گزینه استفاده کنید.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'off' : '',
                            'keywords' => array('hover product image', 'واکنش ماوس', 'هاور محصول', 'استایل محصول', 'product style', 'woocommerce product style'),
                        ),
                        array(
                            'id'             => 'transparent_checkbox',
                            'label'            => __(
                                'پس زمینه شیشه ای محصول',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت فعال سازی این گزینه پس زمینه محصول به صورت شیشه ای و بدون رنگ نمایش داده خواهد شد.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'off' : '',
                            'keywords' => array('پس زمینه شیشه ای محصول', 'product transparent'),
                        ),
                        array(
                            'id'             => 'padding_status_checkbox',
                            'label'            => __(
                                'حاشیه دور پست',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید حاشیه ای اضافی به دور هر محصول اعمال نمایید.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'off' : '',
                            'keywords' => array('حاشیه دور پست', 'product padding'),
                        ),
                        array(
                            'id'             => 'border_checkbox',
                            'label'            => __(
                                'خط دور محصول',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید وضعیت نمایش یا عدم نمایش خط دور هر نوشته (بوردر) را مشخص نمایید. در صوررت فعال نمودن این گزینه (محصول) ها به شکل باکسی در می آیند.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'off' : '',
                            'keywords' => array('بوردر محصول', 'خط دور محصول', 'prodcut border', 'border style product'),
                        ),
                        array(
                            'id'             => 'box_shadow_checkbox',
                            'label'            => __(
                                'استایل سایه',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید سایه ای به دور محصولات قرار دهید.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'off' : '',
                            'keywords' => array('سایه محصول', 'استایل سایه', 'product shadow', 'shadow style'),
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
                // Get slider fields
                Irantheme_Section_Utils::slider_fields_generator($status_default, array('arrow', 'pagination', 'autoplay', 'delay', 'overflow', 'direction'), 'on', 'off', 'off', 'off', 5000, 'off', 'off'),
                // Get layout scale fields
                Irantheme_Section_Utils::layout_scale_fields_generator($status_default, array('radius', 'inner_padding', 'outer_padding'), 'on', 2, 2, 'off', 'off', 0, 0, 1),
                // Get background fields
                Irantheme_Section_Utils::background_fields_generator($status_default, array('image', 'color', 'mask', 'parallax'), '', 'off', true, 'off', 'dark', 0.6, 'off'),
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
            if (is_woocommerce_activated()) {
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

                $aos_delay_Incremental = $aos_delay;

                // Get border radius --------------------------------------------------------------
                $border_radius_range = $fields_values['border_radius_range'];
                $border_radius_style = Irantheme_Section_Utils::get_border_radius_css($border_radius_range);

                // Base color --------------------------------------------------------------
                $base_color = $fields_values['base_color_select'];
                // Set base color class
                $base_color_class = Irantheme_Common_Static::get_base_color_class($base_color);

                // Details color --------------------------------------------------------------
                $details_color_select = $fields_values['details_color_select'];
                $details_color_class = '';
                $details_bg_class = '';
                if ($details_color_select != 'off') {
                    $details_color_class = Irantheme_Color_Static::get_class_color_mode('color', null, Irantheme_Color_Static::get_hex_color($details_color_select));
                    $details_bg_class = Irantheme_Color_Static::get_class_color_mode('bg', null, Irantheme_Color_Static::get_hex_color($details_color_select));
                }

                // Section transform to up and down --------------------------------------------------------------
                $section_transfrom_values = Irantheme_Section_Utils::get_transform_values_arrays($data_page, $fields_values, true, false, false);
                $section_transform_extra_css = $section_transfrom_values['overflow'];
                $section_transform_translate_css = $section_transfrom_values['css'];

                // Count columns of products --------------------------------------------------------------
                $col_count_products = $fields_values['col_count_radio'];

                // Get image product height --------------------------------------------------------------
                $image_product_height = $fields_values['image_height_range'];

                // Get product features --------------------------------------------------------------
                $product_features = $fields_values['features_checkbox_multi'];
                $product_features = explode(',', $product_features);


                // Get image switchable thumbnail with gallery image --------------------------------------------------------------
                $product_image_hover_changer = $fields_values['image_changer_checkbox'];

                $product_image_hover_changer_class = '';
                if ($product_image_hover_changer == 'on') {
                    $product_image_hover_changer_class = 'irantheme-woocommerce-mini-products-item-image-changer';
                }

                // Get product options checkbox --------------------------------------------------------------
                $product_border_checkbox = $fields_values['border_checkbox'];

                $product_border_class = '';
                if ($product_border_checkbox == 'on') {
                    $product_border_class = 'irantheme-woocommerce-mini-products-item-border';
                }

                // Get product box shadow checkbox --------------------------------------------------------------
                $product_box_shadow_checkbox = $fields_values['box_shadow_checkbox'];

                $product_box_shadow_class = '';
                if ($product_box_shadow_checkbox == 'on') {
                    $product_box_shadow_class = 'irantheme-woocommerce-mini-products-item-shadow';
                }

                // Get product options checkbox --------------------------------------------------------------
                $product_padding_checkbox = $fields_values['padding_status_checkbox'];

                $product_padding_class = '';
                if ($product_padding_checkbox == 'on') {
                    $product_padding_class = 'irantheme-woocommerce-mini-products-item-padding';
                }

                // Get product options checkbox --------------------------------------------------------------
                $product_transparent_checkbox = $fields_values['transparent_checkbox'];

                $product_transparent_class = '';
                if ($product_transparent_checkbox == 'on') {
                    $product_transparent_class = 'irantheme-woocommerce-mini-products-item-transparent';
                }

                // Get product image hover mode --------------------------------------------------------------
                $product_image_hover_mode = $fields_values['image_hover_mode_radio'];

                $product_image_hover_mode_class = '';
                if ($product_image_hover_mode == 'flashing') {
                    $product_image_hover_mode_class = 'irantheme-image-hover-flashing';
                } elseif ($product_image_hover_mode == 'shine') {
                    $product_image_hover_mode_class = 'irantheme-image-hover-shine';
                } elseif ($product_image_hover_mode == 'circle') {
                    $product_image_hover_mode_class = 'irantheme-image-hover-circle';
                }

                // Count columns of products --------------------------------------------------------------
                $col_count_products = $fields_values['col_count_radio'];

                // If slider is true, assign new swiper classes to wrapper, row and item
                $slider_mode_wrapper_class = 'swiper irantheme-woocommerce-products-post-swiper';
                $slider_mode_row  = 'swiper-wrapper';
                $slider_mode_column = 'swiper-slide';

                // Set slider direction --------------------------------------------------------------
                $slider_direction_radio = $fields_values['slider_direction_radio'];
                $slider_direction_style = '';
                if ($slider_direction_radio == 'ltr') {
                    $slider_direction_style = 'direction:ltr;';
                }
            }

            // Get section id --------------------------------------------------------------
            $section_id = $fields_values['element_id'];
            $section_id_attribute = '';
            if ($section_id) {
                $section_id_attribute = sprintf('id=%s', $section_id);
            }
?>
            <!-- ============================= Section Woocommerce mini products ============================= -->
            <section <?php echo esc_attr($section_id_attribute); ?> class="irantheme-section irantheme-woocommerce-mini-products-section" <?php echo esc_attr(Irantheme_Page_Builder::get_attr_data_section($data_type, $data_value, $data_page, $data_ajax)); ?> style="<?php echo esc_attr($section_transform_extra_css); ?><?php echo esc_attr(Irantheme_Section_Service::get_custom_outer_padding_css($fields_values)); ?>">
                <?php
                if (is_woocommerce_activated()) :
                    // Set wrapper (Container) --------------------------------------------------------------
                    $container_checkbox = $fields_values['wrapper_mode_checkbox'];
                ?>
                    <div class="irantheme-woocommerce-mini-products <?php echo esc_attr($base_color_class); ?> irantheme-background-jarallax" style="<?php echo esc_attr($background_color_css); ?><?php echo esc_attr($background_image_css); ?><?php echo esc_attr(Irantheme_Section_Service::get_custom_inner_padding_css($fields_values)); ?><?php echo esc_attr($section_transform_translate_css); ?>" <?php echo $background_parallax_attr; ?>>

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
                            <div class="irantheme-woocommerce-mini-products-wrapper">
                                <!-- Row columns -->
                                <div class="irantheme-object-group row" data-json="<?php echo esc_attr('objects_json'); ?>">
                                    <?php
                                    // Get latest product temporary for check
                                    $product_posts_temp = Irantheme_Common_Static::get_latest_posts(array('product', 'product_variation'));

                                    // Get post meta --------------------------------------------------------------
                                    // Heading
                                    $product_posts_heading = $fields_values['heading_text'];
                                    // Link
                                    $product_posts_link = $fields_values['heading_link'];
                                    // Product posts content type
                                    $product_posts_content = Irantheme_Section_Utils::get_product_content($fields_values);
                                    ?>
                                    <?php if ($product_posts_content) : ?>
                                        <!-- Latest products -->
                                        <div class="irantheme-woocommerce-product-swiper irantheme-woocommerce-ajax-categories-products-swiper <?php echo esc_attr($slider_mode_wrapper_class); ?>" style="<?php echo esc_attr(Irantheme_Section_Utils::get_slider_overflow_style($fields_values, true)); ?><?php echo esc_attr($slider_direction_style); ?>" <?php echo (Irantheme_Section_Service::get_slider_data_column($col_count_products)); ?> <?php echo esc_attr(Irantheme_Section_Utils::get_auto_slider_data_attribute($fields_values, true)); ?> <?php echo esc_attr(Irantheme_Section_Utils::get_auto_slider_delay_data_attribute($fields_values)); ?>>
                                            <?php
                                            // Slider pagination option
                                            $slider_pagination_checkbox = $fields_values['slider_pagination_checkbox'];
                                            // Slider arrow option
                                            $slider_arrow_checkbox = $fields_values['slider_arrow_checkbox'];
                                            Irantheme_Section_Utils::get_slider_arrow_pagination('on', $slider_arrow_checkbox, $slider_pagination_checkbox, $base_color_class, $border_radius_range, 'bottom', 'center');
                                            ?>

                                            <?php
                                            if ($product_posts_content->have_posts() && $product_posts_heading != '') :
                                            ?>
                                                <!-- Heading -->
                                                <div class="irantheme-woocommerce-mini-products-heading">
                                                    <!-- Title -->
                                                    <h3 class="irantheme-woocommerce-mini-products-heading-title">
                                                        <span class="irantheme-woocommerce-mini-products-heading-title-dot <?php echo esc_attr($details_bg_class); ?>"></span>
                                                        <?php echo esc_html($product_posts_heading); ?>
                                                    </h3>
                                                    <?php
                                                    // Arrow url --------------------------------------------------------------
                                                    $details_color_rgba_css = '';
                                                    $details_color_rgb_array = Irantheme_Color_Static::hex_to_rgb(Irantheme_Color_Static::get_hex_color($details_color_select));
                                                    if ($details_color_select != 'off') {
                                                        $details_color_rgba_css = 'background-color:rgba(' . $details_color_rgb_array[0] . ',' . $details_color_rgb_array[1] . ',' . $details_color_rgb_array[2] . ',' . '0.1' . ');';
                                                    }
                                                    ?>
                                                    <!-- Link -->
                                                    <a href="<?php echo esc_url($product_posts_link); ?>" class="irantheme-woocommerce-mini-products-heading-icon <?php echo esc_attr($details_color_class); ?>" style="<?php echo esc_attr($details_color_rgba_css); ?>">
                                                        <i class="fas fa-angle-left"></i>
                                                    </a>
                                                </div>
                                            <?php
                                            endif;
                                            ?>
                                            <!-- Row columns -->
                                            <div class="<?php echo esc_attr($slider_mode_row); ?>">
                                                <?php
                                                while ($product_posts_content->have_posts()) :
                                                    $product_posts_content->the_post();
                                                    global $product;
                                                ?>
                                                    <div class="irantheme-woocommerce-mini-products-col <?php echo esc_attr($slider_mode_column); ?>" <?php echo ($data_load_attr); ?> data-aos-delay="<?php echo ($aos_delay_Incremental); ?>" data-aos-duration="<?php echo ($data_load_duration); ?>">
                                                        <div class="irantheme-woocommerce-mini-products-item <?php echo esc_attr($product_border_class); ?> <?php echo esc_attr($product_box_shadow_class); ?> <?php echo esc_attr($product_padding_class); ?> <?php echo esc_attr($product_transparent_class); ?>" style="<?php echo esc_attr($border_radius_style); ?>" <?php echo ($data_load_attr); ?> data-aos-delay="<?php echo ($aos_delay); ?>" data-aos-duration="<?php echo ($data_load_duration); ?>">
                                                            <?php if (in_array('image', $product_features)) : ?>
                                                                <!-- Thumbnail -->
                                                                <div class="irantheme-woocommerce-mini-products-item-image <?php echo esc_attr($product_image_hover_changer_class); ?>" style="--irantheme-thumb-size:<?php echo esc_attr($image_product_height); ?>px;">
                                                                    <a href="<?php the_permalink(); ?>" class="irantheme-woocommerce-mini-products-item-image-link <?php echo esc_attr($product_image_hover_mode_class); ?>">
                                                                        <?php
                                                                        echo wp_get_attachment_image(
                                                                            get_post_thumbnail_id($product->get_id()),
                                                                            'thumbnail',
                                                                            false,
                                                                            [
                                                                                'class' => 'irantheme-woocommerce-mini-products-item-img',
                                                                                'alt'   => __('تصویر محصول', self::$static_textdomain),
                                                                                'loading' => 'lazy',
                                                                            ]
                                                                        );
                                                                        ?>
                                                                        <?php
                                                                        $attachment_ids = $product->get_gallery_image_ids();
                                                                        if (!empty($attachment_ids)) {
                                                                            echo wp_get_attachment_image(
                                                                                $attachment_ids[0],
                                                                                'thumbnail',
                                                                                false,
                                                                                [
                                                                                    'class' => 'irantheme-woocommerce-mini-products-item-gallery-img',
                                                                                    'alt'   => __('تصویر محصول', self::$static_textdomain),
                                                                                    'loading' => 'lazy',
                                                                                ]
                                                                            );
                                                                        }
                                                                        ?>
                                                                    </a>
                                                                    <?php if (in_array('sale', $product_features)) :
                                                                        $total_sales = (int) get_post_meta($product->get_id(), 'total_sales', true);
                                                                    ?>
                                                                        <!-- Sale badge overlay -->
                                                                        <span class="irantheme-woocommerce-mini-products-item-image-sale <?php echo esc_attr($details_bg_class); ?>">
                                                                            <i class="fa-solid fa-fire"></i>
                                                                            <?php echo esc_html(number_format_i18n($total_sales)); ?>
                                                                        </span>
                                                                    <?php endif; ?>
                                                                    <?php
                                                                    // Child theme: brand pill on the photo (bottom-right).
                                                                    if (function_exists('storeplus_child_mini_brand_badge')) {
                                                                        storeplus_child_mini_brand_badge($product);
                                                                    }
                                                                    ?>
                                                                </div>
                                                            <?php endif; ?>
                                                            <!-- Content -->
                                                            <div class="irantheme-woocommerce-mini-products-item-content">
                                                                <?php if (in_array('category', $product_features) || in_array('rating', $product_features)) : ?>
                                                                    <div class="irantheme-woocommerce-mini-products-item-content-head">
                                                                        <!-- Category -->
                                                                        <div class="irantheme-woocommerce-mini-products-item-content-category">
                                                                            <?php
                                                                            $categories = get_the_terms($product->get_id(), 'product_cat');
                                                                            if (!empty($categories)) {
                                                                                foreach ($categories as $category) {
                                                                                    echo '<a href="' . get_term_link($category) . '">' . $category->name . '</a>';
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </div>
                                                                        <?php if (in_array('rating', $product_features)) : ?>
                                                                            <?php
                                                                            if (wc_review_ratings_enabled()) :
                                                                            ?>
                                                                                <!-- Rating -->
                                                                                <div class="irantheme-woocommerce-mini-products-item-rating">
                                                                                    <span><?php echo esc_attr($product->get_average_rating()); ?></span>
                                                                                    <i class="fa-solid fa-star"></i>
                                                                                </div>
                                                                            <?php endif; ?>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                <?php endif; ?>
                                                                <?php if (in_array('title', $product_features)) : ?>
                                                                    <!-- Heading -->
                                                                    <h2 class="irantheme-woocommerce-mini-products-item-content-heading">
                                                                        <a href="<?php the_permalink(); ?>">
                                                                            <?php the_title(); ?>
                                                                        </a>
                                                                    </h2>
                                                                <?php endif; ?>
                                                                <?php if (in_array('price', $product_features)) : ?>
                                                                    <div class="irantheme-woocommerce-mini-products-item-details">
                                                                        <?php
                                                                        // Child theme: real add-to-cart control (no post-render HTML scan).
                                                                        if (function_exists('storeplus_child_mini_cart_button')) {
                                                                            storeplus_child_mini_cart_button($product);
                                                                        }
                                                                        ?>
                                                                        <?php if (in_array('price', $product_features)): ?>
                                                                            <!-- Price -->
                                                                            <div class=" irantheme-woocommerce-mini-products-item-price">
                                                                                <?php
                                                                                Irantheme_Section_Woocommerce::display_product_price($product, true, $details_color_select);
                                                                                ?>
                                                                            </div>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php
                                                    // Increase aos delay for next item
                                                    $aos_delay_Incremental += 200;
                                                    ?>
                                                <?php
                                                endwhile;
                                                ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <?php
                                    if (!$product_posts_temp->have_posts()) {
                                        // Empty status
                                        irantheme_display_empty_status('cart-xmark', 'محصولی جهت نمایش یافت نشد.', 'با رفتن به محیط ادمین و تب "محصولات" -> "افزودن جدید" محصولات مورد نظر خود را بسازید.');
                                    }
                                    ?>
                                </div>
                            </div>
                            <?php
                            if ($container_checkbox != 'on') :
                            ?>
                            </div>
                        <?php endif;
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
