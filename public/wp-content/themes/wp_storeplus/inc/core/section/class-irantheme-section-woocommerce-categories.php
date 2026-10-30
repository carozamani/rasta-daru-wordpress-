<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;

if (!class_exists('Irantheme_Section_Woocommerce_Categories')) {
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
    class Irantheme_Section_Woocommerce_Categories extends Irantheme_Section
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
            $heading_content = '';
            $settings = array(
                Irantheme_Section_Utils::heading_title_settings_generator($status_default, 'none', false, false, $heading_content, 'off', '', '', '', 'off', 'off', '', '', '', 'off', 'rectangle', 'right', 'off', '', 0, 1),
                array(
                    'title' => 'پیکربندی',
                    'id' => 'products',
                    'description'            => __('تنظیمات بصری مانند : تصویر زمینه، ارتفاع، رنگ ها و...', self::$static_textdomain),
                    'icon' => 'fa-sliders',
                    'fields' => array(
                        array(
                            'id'             => 'all_checkbox',
                            'label'            => __(
                                'نمایش همه دسته بندی ها',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورتی که این فیلد فعال باشد، همه دسته بندی ها نمایش داده می شوند. در غیر اینصورت می توانید با غیر فعال کردن این فیلد پست های مورد نظر خود را اضافه کنید.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'on' : '',
                            'keywords' => array('latest products', 'checkbox latest products', 'نمایش همه دسته بندی ها', 'آخرین پست ها', 'post latest'),
                            'id_dependents_on' => array(),
                            'id_dependents_off' => array('product_category_selection'),
                            'base_priority' => 'checkbox_base',
                            'selector_priority' => 'woocommerce_categories_all',
                            'z_index' => 0
                        ),
                        array(
                            'id'             => 'hide_empty_checkbox',
                            'label'            => __(
                                'حذف دسته بندی های بدون محصول',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید دسته بندی هایی که محصول آن ها صفر است را حذف کنید.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'on' : '',
                            'keywords' => array('حذف دسته بندی های بدون محصول', 'remove empty categories'),
                        ),
                        array(
                            'id'             => 'product_category_selection',
                            'label'            => __(
                                'انتخاب دستی دسته بندی',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه شما می توانید هر یک از دسته بندی های مد نظر خود را جهت نمایش در این بخش اضافه کنید.', self::$static_textdomain),
                            'type'            => 'select_multi',
                            'options'        => Irantheme_Custom_Fields::get_all_categories('product_cat'),
                            'default' => $status_default ? '' : '',
                            'keywords' => array('انتخاب دسته بندی محصول', 'دسته بندی محصولات', 'انتخاب دسته بندی محصولات', 'product category selection', 'category select', 'category product'),
                            'placeholder' => __('محتوا خالی می باشد', self::$static_textdomain)
                        ),
                        array(
                            'id'             => 'product_count_radio',
                            'label'            => __(
                                'نوع نمایش تعداد محصول',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید نوع نمایش تعداد محصول هر دسته بندی را مشخص کنید و در صورت عدم نیاز آن را نمایش ندهید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'options'        => array('hide' => 'مخفی', 'image' => 'بر روی عکس', 'inline' => 'در کنار عنوان', 'bottom' => 'در زیر عنوان'),
                            'default'        => $status_default ? 'image' : '',
                            'keywords' => array('نوع نمایش تعداد محصول', 'ویژگی تعداد محصول', 'categories count products', 'count products', 'number of products')
                        ),
                        array(
                            'id'             => 'col_count_radio',
                            'label'            => __('تعداد ستون دسته بندی', self::$static_textdomain),
                            'description'    => __('با استفاده از این گزینه شما می توانید تعداد ستون های قابل نمایش برای دسته بندی را مشخص کنید', self::$static_textdomain),
                            'type'            => 'radio',
                            'thumbnail_status' => true,
                            'options'        => array(
                                'col-4' => array('چهار ستون', 'col-4.png'),
                                'col-6' => array('شش ستون', 'col-6.png'),
                                'col-8' => array('هشت ستون', 'col-8.png'),
                                'col-10' => array('ده ستون', 'col-10.png')
                            ),
                            'default'        => $status_default ? 'col-8' : '',
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
                            'id'             => 'align_content_radio',
                            'label'            => __('تراز محتوا', self::$static_textdomain),
                            'description'    => __('با استفاده از این گزینه می توانید تراز جزئیات پست ها را مشخص کنید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'thumbnail_status' => true,
                            'options'        => array('right' => array('راست چین', 'text-align-right.png'), 'center' => array('وسط چین', 'text-align-center.png'), 'left' => array('چپ چین', 'text-align-left.png')),
                            'default'        => $status_default ? 'center' : '',
                            'keywords' => array('box', 'col', 'author', 'تعداد', 'سرویس', 'تعداد در هر ردیف'),
                        ),
                        array(
                            'id'             => 'image_hover_mode_radio',
                            'label'            => __(
                                'سبک واکنش به تصویر (hover)',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید نوع واکنش به عبور نشانگر از روی تصویر دسته بندی (hover) را مشخص کنید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'options'        => array('normal' => 'ساده', 'shine' => 'رهگذر', 'flashing' => 'فلش', 'circle' => 'گرد', 'scale' => 'بزرگنمایی'),
                            'default'        => $status_default ? 'normal' : '',
                            'keywords' => array('واکنش نشانگر دسته بندی', 'استایل دسته بندی', 'ویژگی های دسته بندی', 'categories hover', 'woocommerce categories style', 'categories style', 'hover category', 'hover', 'هاور')
                        ),
                        array(
                            'id'             => 'image_radius_checkbox',
                            'label'            => __(
                                'گرد کردن تصویر دسته بندی',
                                self::$static_textdomain
                            ),
                            'description'    => __('با فعال کردن این گزینه می توانید تصویر دسته بندی را به صورت گرد نمایش دهید.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'on' : '',
                            'keywords' => array('radius category image', 'گرد کردن تصویر دسته بندی', 'category radius', 'woocommerce category radius'),
                            'id_dependents_on' => array(),
                            'id_dependents_off' => array('image_radius_range'),
                            'base_priority' => 'checkbox_base',
                            'selector_priority' => 'checkbox_image_radius',
                            'z_index' => 0,
                        ),
                        array(
                            'id'             => 'image_radius_range',
                            'label'            => __(
                                'خمیدگی گوشه های تصویر',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این امکان شما می توانید میزان خمیدگی زاوایای تصویر را تنظیم نمایید.', self::$static_textdomain),
                            'type'            => 'range',
                            'min' => 0,
                            'max' => 2.5,
                            'step' => 0.1,
                            'default'        => 0.5,
                            'placeholder'    => __('Placeholder text', self::$static_textdomain),
                            'keywords' => array('transparency', 'opacity', 'شفافیت', 'شفاف', 'پس زمینه', 'تاپ بار'),
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
                Irantheme_Section_Utils::slider_fields_generator($status_default, array('slider_status', 'arrow', 'pagination', 'autoplay', 'delay'), 'on', 'off', 'on', 'off', 5000, 'off'),
                // Get layout scale fields
                Irantheme_Section_Utils::layout_scale_fields_generator($status_default, array('inner_padding', 'outer_padding'), 'on', 2, 2),
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
                $aos_delay = Irantheme_Section_Utils::get_data_load_delay($fields_values);

                // Get data load style --------------------------------------------------------------
                $heading_data_load_checkbox = $fields_values['heading_data_load_checkbox'];
                $heading_data_load_attr = Irantheme_Section_Utils::get_heading_data_load_attr_type($fields_values);
                // Get data load duration
                $heading_data_load_duration = Irantheme_Section_Utils::get_heading_data_load_duration($fields_values);
                // Get aos delay time
                $heading_aos_delay = Irantheme_Section_Utils::get_heading_data_load_delay($fields_values);

                // Fix conflict skeleton with data load (Aos) --------------------------------------------------------------
                $data_load_checkbox = $fields_values['data_load_checkbox'];
                $skeleton_section_class = 'irantheme-skeleton-section';
                if ($data_load_checkbox == 'on' || $heading_data_load_checkbox == 'on') {
                    $skeleton_section_class = '';
                }

                // Base color --------------------------------------------------------------
                $base_color = $fields_values['base_color_select'];
                // Set base color class
                $base_color_class = Irantheme_Common_Static::get_base_color_class($base_color);

                // Get section heading --------------------------------------------------------------
                $tinymce_heading = $fields_values['text_editor_heading'];

                // Get latest post checkbox
                $latest_post_checkbox = $fields_values['all_checkbox'];

                // Get products category custom selected
                $custom_category_selected = $fields_values['product_category_selection'];

                // Remove empty categories
                $remove_empty_categories_checkbox = $fields_values['hide_empty_checkbox'];
                // Status of empty categories
                $remove_empty_categories_status = false;
                // Check empty categories checkbox
                if ($remove_empty_categories_checkbox == 'on') {
                    $remove_empty_categories_status = true;
                }

                $taxonomy = "product_cat";
                // Get terms based on latest post checkbox and custom selected categories
                $args = array(
                    'orderby'    => 'slug',
                );
                if ($latest_post_checkbox == 'on') {
                    // If latest post checkbox is on, get all categories and hide empty categories based on the status of remove empty categories checkbox
                    $args['hide_empty'] = $remove_empty_categories_status;
                } else {
                    // If latest post checkbox is off, get only the selected categories and hide empty categories based on the status of remove empty categories checkbox
                    $args['hide_empty'] = false;
                    if ($custom_category_selected !== '' && $custom_category_selected !== null) {
                        $args['include'] = array_map('intval', explode(',', $custom_category_selected));
                    } else {
                        $args['include'] = explode(',', $custom_category_selected);
                    }
                }
                $terms = get_terms($taxonomy, $args);

                // Section transform to up and down --------------------------------------------------------------
                $section_transfrom_values = Irantheme_Section_Utils::get_transform_values_arrays($data_page, $fields_values, true, false, false);
                $section_transform_extra_css = $section_transfrom_values['overflow'];
                $section_transform_translate_css = $section_transfrom_values['css'];

                // Count columns of products --------------------------------------------------------------
                $col_count = $fields_values['col_count_radio'];

                // Get product content alignment --------------------------------------------------------------
                $post_content_align = $fields_values['align_content_radio'];

                // Set slider mode --------------------------------------------------------------
                $slider_mode_checkbox = $fields_values['slider_checkbox'];
                // Init wrapper and row and item with default classes
                $wrapper_mode_class = '';
                $row_mode_class  = 'row justify-content-center';
                $column_mode_class = 'irantheme-column-flex-width';
                if ($slider_mode_checkbox == 'on') {
                    // If slider is true, assign new swiper classes to wrapper, row and item
                    $wrapper_mode_class = 'swiper irantheme-woocommerce-categories-post-swiper';
                    $row_mode_class  = 'swiper-wrapper';
                    $column_mode_class = 'swiper-slide';
                }

                $alignment_class = '';
                if ($post_content_align == 'right') {
                    $alignment_class = 'flex-right';
                } elseif ($post_content_align == 'left') {
                    $alignment_class = 'flex-left';
                } elseif ($post_content_align == 'center') {
                    $alignment_class = 'flex-center';
                }

                // Get categories count mode --------------------------------------------------------------
                $categories_count_mode = $fields_values['product_count_radio'];

                // Get product image hover mode --------------------------------------------------------------
                $category_image_hover_mode = $fields_values['image_hover_mode_radio'];

                $category_image_hover_mode_class = '';
                if ($category_image_hover_mode == 'flashing') {
                    $category_image_hover_mode_class = 'irantheme-image-hover-flashing';
                } elseif ($category_image_hover_mode == 'shine') {
                    $category_image_hover_mode_class = 'irantheme-image-hover-shine';
                } elseif ($category_image_hover_mode == 'circle') {
                    $category_image_hover_mode_class = 'irantheme-image-hover-circle';
                } elseif ($category_image_hover_mode == 'scale') {
                    $category_image_hover_mode_class = 'irantheme-image-hover-scale';
                }

                // Get product options checkbox --------------------------------------------------------------
                $category_image_radius_checkbox = $fields_values['image_radius_checkbox'];
                $image_radius_range = $fields_values['image_radius_range'];

                $category_image_radius_class = '';
                $category_image_radius_css = '';
                if (
                    $category_image_radius_checkbox == 'on'
                ) {
                    $category_image_radius_class = 'irantheme-woocommerce-category-image-radius';
                } else {
                    $category_image_radius_css = 'border-radius:' . $image_radius_range . 'rem;';
                }
            }

            // Get section id --------------------------------------------------------------
            $section_id = $fields_values['element_id'];
            $section_id_attribute = '';
            if ($section_id) {
                $section_id_attribute = sprintf('id=%s', $section_id);
            }
?>
            <!-- ============================= Section Woocommerce Categories ============================= -->
            <section <?php echo esc_attr($section_id_attribute); ?> class="irantheme-section irantheme-woocommerce-categories-section <?php echo esc_attr($skeleton_section_class); ?>" <?php echo esc_attr(Irantheme_Page_Builder::get_attr_data_section($data_type, $data_value, $data_page, $data_ajax)); ?> style="<?php echo esc_attr($section_transform_extra_css); ?><?php echo esc_attr(Irantheme_Section_Service::get_custom_outer_padding_css($fields_values)); ?>">
                <?php
                if (is_woocommerce_activated()) :
                    // Set wrapper (Container) --------------------------------------------------------------
                    $container_checkbox = $fields_values['wrapper_mode_checkbox'];
                ?>
                    <div class="irantheme-woocommerce-categories <?php echo esc_attr($base_color_class); ?> irantheme-background-jarallax" style="<?php echo esc_attr($background_color_css); ?><?php echo esc_attr($background_image_css); ?><?php echo esc_attr(Irantheme_Section_Service::get_custom_inner_padding_css($fields_values)); ?><?php echo esc_attr($section_transform_translate_css); ?>" <?php echo $background_parallax_attr; ?>>

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

                            <?php
                            $skeleton_load_class = '';
                            if ($slider_mode_checkbox == 'on' && $data_load_checkbox != 'on' && $heading_data_load_checkbox != 'on') {
                                // Skeleton html --------------------------------------------------------------
                                irantheme_get_skeleton_html($col_count, 'image_medium', $base_color_class);
                                $skeleton_load_class = 'irantheme-side-load-skeleton';
                            }
                            ?>

                            <!-- News letter post -->
                            <div class="irantheme-woocommerce-categories-wrapper <?php echo esc_attr($wrapper_mode_class); ?> <?php echo esc_attr($skeleton_load_class); ?>" <?php echo (Irantheme_Section_Service::get_slider_data_column($col_count)); ?> <?php echo esc_attr(Irantheme_Section_Utils::get_auto_slider_data_attribute($fields_values, true)); ?> <?php echo esc_attr(Irantheme_Section_Utils::get_auto_slider_delay_data_attribute($fields_values)); ?>>
                                <?php
                                if ($tinymce_heading != '' && $heading_data_load_checkbox == 'on'):
                                ?>
                                    <div class="irantheme-tinymce-heading-aos-holder" <?php echo ($heading_data_load_attr); ?> data-aos-delay="<?php echo ($heading_aos_delay); ?>" data-aos-duration="<?php echo ($heading_data_load_duration); ?>">
                                    <?php
                                endif;
                                    ?>
                                    <?php
                                    // Get showcase content --------------------------------------------------------------
                                    Irantheme_Common_Static::tinymce_heading($fields_values);
                                    if ($tinymce_heading != '' && $heading_data_load_checkbox == 'on'):
                                    ?>
                                    </div>
                                <?php
                                    endif;
                                ?>
                                <!-- Row columns -->
                                <div class="<?php echo esc_attr($row_mode_class); ?>">
                                    <?php
                                    foreach ($terms as $term) {
                                        $thumbnail_id = get_term_meta($term->term_id, 'thumbnail_id', true);
                                    ?>
                                        <!-- Column -->
                                        <div class="irantheme-woocommerce-categories-col <?php echo esc_attr($column_mode_class); ?>" style="width:<?php echo esc_attr(Irantheme_Section_Service::get_column_width_percentage($col_count)); ?>%;">
                                            <!-- Category item -->
                                            <div class="irantheme-woocommerce-categories-item <?php echo Irantheme_Section_Service::get_alignment_class($post_content_align); ?> <?php echo esc_attr($alignment_class); ?>" <?php echo ($data_load_attr); ?> data-aos-delay="<?php echo ($aos_delay); ?>" data-aos-duration="<?php echo ($data_load_duration); ?>">
                                                <!-- Image -->
                                                <div class="irantheme-woocommerce-categories-item-image <?php echo esc_attr($category_image_radius_class); ?>" style="<?php echo esc_attr($category_image_radius_css); ?>">
                                                    <a href="<?php echo get_term_link($term->slug, $taxonomy); ?>" class="<?php echo esc_attr($category_image_hover_mode_class); ?>">
                                                        <?php
                                                        if (!empty($thumbnail_id)) {
                                                            echo wp_get_attachment_image(
                                                                $thumbnail_id,
                                                                'medium',
                                                                false,
                                                                [
                                                                    'class' => 'irantheme-woocommerce-categories-item-image-img',
                                                                    'alt' => esc_attr($term->name),
                                                                    'loading' => 'lazy',
                                                                ]
                                                            );
                                                        } else {
                                                        ?>
                                                            <img class="irantheme-woocommerce-box-categories-item-image-img"
                                                                src="<?php echo esc_url(IRANTHEME_URI . '/assets/images/placeholder.png'); ?>"
                                                                alt="<?php echo esc_attr($term->name); ?>"
                                                                loading="lazy">
                                                        <?php
                                                        }
                                                        ?>
                                                    </a>
                                                    <?php if ($categories_count_mode == 'image') : ?>
                                                        <!-- Product count -->
                                                        <span class="irantheme-woocommerce-categories-item-image-product-count"><?php echo esc_html($term->count); ?></span>
                                                    <?php endif; ?>
                                                </div>
                                                <!-- Heading -->
                                                <div class="irantheme-woocommerce-categories-item-heading">
                                                    <!-- Title -->
                                                    <h4 class="irantheme-woocommerce-categories-item-heading-title">
                                                        <a href="<?php echo get_term_link($term->slug, $taxonomy); ?>"><?php echo esc_html($term->name); ?>
                                                            <?php if ($categories_count_mode == 'inline') : ?>
                                                                <span class="irantheme-woocommerce-categories-item-heading-title-product-count">(<?php echo esc_html($term->count); ?>)</span>
                                                            <?php endif; ?>
                                                        </a>
                                                    </h4>
                                                    <?php if ($categories_count_mode == 'bottom') : ?>
                                                        <!-- Product count -->
                                                        <p class="irantheme-woocommerce-categories-item-heading-product-count">
                                                            <span class="irantheme-woocommerce-categories-item-heading-product-count-number"><?php echo esc_html($term->count); ?></span>
                                                            <cite class="irantheme-woocommerce-categories-item-heading-product-count-title"><?php esc_html_e('محصول'); ?></cite>
                                                        </p>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                        // Increase aos delay for next item
                                        $aos_delay += 200;
                                    }
                                    if (!$terms) {
                                        // Empty status
                                        irantheme_display_empty_status('list', 'دسته بندی برای محصولات یافت نشد.', 'با رفتن به محیط ادمین و تب "محصولات" -> "دسته بندی ها" دسته بندی های مورد نظر خود را بسازید.');
                                    }
                                    ?>
                                </div>
                                <?php
                                // Slider pagination option
                                $slider_checkbox = $fields_values['slider_checkbox'];
                                // Slider pagination option
                                $slider_pagination_checkbox = $fields_values['slider_pagination_checkbox'];
                                // Slider arrow option
                                $slider_arrow_checkbox = $fields_values['slider_arrow_checkbox'];
                                Irantheme_Section_Utils::get_slider_arrow_pagination($slider_checkbox, $slider_arrow_checkbox, $slider_pagination_checkbox, $base_color_class, 1, 'bottom', 'center');
                                ?>
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
