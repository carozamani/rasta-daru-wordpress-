<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;

if (!class_exists('Irantheme_Section_Woocommerce_Box_Brands')) {
    /**
     * A class for create section - Modified to display brands instead of categories
     *
     * @package Wordpress
     * @subpackage irantheme
     * @since irantheme - custom fields
     * @author iranianclever
     * @see v1.0.3
     *
     */
    class Irantheme_Section_Woocommerce_Box_Brands extends Irantheme_Section
    {
        /**
         * Constructor
         * @return void
         * @param void
         * @author iranianclever
         * @since v1.0.3
         * @see v1.0.3
         */
        function __construct() {}


        /**
         * Fields of section
         * @return void
         * @param void
         * @author iranianclever
         * @since v1.0.3
         * @see v1.0.3
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
                            'id'             => 'hide_empty_checkbox',
                            'label'            => __(
                                'حذف برند های بدون محصول',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید برند هایی که محصول آن ها صفر است را حذف کنید.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'off' : '',
                            'keywords' => array('حذف برند های بدون محصول', 'remove empty brands'),
                        ),
                        array(
                            'id'             => 'product_count_checkbox',
                            'label'            => __(
                                'نمایش تعداد محصول',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید نمایش تعداد محصول هر برند را نمایش دهید.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'on' : '',
                            'keywords' => array('نمایش تعداد محصول', 'brands count products', 'number of products')
                        ),
                        array(
                            'id'             => 'col_count_radio',
                            'label'            => __('تعداد ستون برند', self::$static_textdomain),
                            'description'    => __('با استفاده از این گزینه شما می توانید تعداد ستون های قابل نمایش برای برند را مشخص کنید', self::$static_textdomain),
                            'type'            => 'radio',
                            'thumbnail_status' => true,
                            'options'        => array(
                                'col-4' => array('چهار ستون', 'col-4.png'),
                                'col-5' => array('پنج ستون', 'col-5.png'),
                                'col-6' => array('شش ستون', 'col-6.png'),
                                'col-8' => array('هشت ستون', 'col-8.png'),
                                'col-10' => array('ده ستون', 'col-10.png')
                            ),
                            'default'        => $status_default ? 'col-5' : '',
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
                            'id'             => 'border_checkbox',
                            'label'            => __(
                                'خط دور برند',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید وضعیت نمایش یا عدم نمایش خط دور هر برند (بوردر) را مشخص نمایید. در صوررت فعال نمودن این گزینه (برند) ها به شکل باکسی در می آیند.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'on' : '',
                            'keywords' => array('بوردر برند', 'خط دور برند', 'brand border', 'border style brand'),
                        ),
                        array(
                            'id'             => 'shadow_checkbox',
                            'label'            => __(
                                'سایه دور برند',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید وضعیت نمایش یا عدم نمایش سایه دور هر برند را مشخص نمایید.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'on' : '',
                            'keywords' => array('hieght', 'ارتفاع', 'fullscreen', 'تمام صفحه', 'ارتباطات'),
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
                Irantheme_Section_Utils::slider_fields_generator($status_default, array('arrow', 'pagination', 'autoplay', 'delay'), 'on', 'off', 'on', 'off', 5000, 'off'),
                // Get layout scale fields
                Irantheme_Section_Utils::layout_scale_fields_generator($status_default, array('inner_padding', 'outer_padding', 'radius'), 'on', 2, 2, 'off', 'off', 0, 0, 1),
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
         * Render elements of section - Modified for brands
         * @param string,string,string,string,mixed
         * @return void
         * @author iranianclever
         * @since v1.0.3
         * @see v1.0.3
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

                // Details color --------------------------------------------------------------
                $details_color = $fields_values['details_color_select'];

                // Get section heading --------------------------------------------------------------
                $tinymce_heading = $fields_values['text_editor_heading'];

                // Remove empty brands
                $remove_empty_brands_checkbox = $fields_values['hide_empty_checkbox'];
                // Status of empty brands
                $remove_empty_brands_status = false;
                // Check empty brands checkbox
                if ($remove_empty_brands_checkbox == 'on') {
                    $remove_empty_brands_status = true;
                }

                // Get all product attribute taxonomies
                $attribute_taxonomies = wc_get_attribute_taxonomies();
                $brand_taxonomy = null;

                // First: Check for irantheme brand option
                foreach ($attribute_taxonomies as $attribute) {
                    $attr_id = $attribute->attribute_id;
                    $taxonomy_type = get_option("wc_attribute_irantheme_taxonomy_type-{$attr_id}");
                    if ($taxonomy_type == 'brand') {
                        $brand_taxonomy = wc_attribute_taxonomy_name($attribute->attribute_name);
                        break;
                    }
                }

                // Second: Check for standard WooCommerce brands
                if (!$brand_taxonomy && taxonomy_exists('product_brand')) {
                    $brand_taxonomy = 'product_brand';
                }

                // Third: If still not found, get the FIRST attribute as brand (temporary fix)
                if (!$brand_taxonomy && !empty($attribute_taxonomies)) {
                    $first_attr = $attribute_taxonomies[0];
                    $brand_taxonomy = wc_attribute_taxonomy_name($first_attr->attribute_name);
                    // Optional: Show admin notice
                    if (current_user_can('administrator')) {
                        echo '<div class="notice notice-warning">⚠️ No brand taxonomy found. Using "' . esc_html($first_attr->attribute_name) . '" as brand.</div>';
                    }
                }

                $terms = get_terms($brand_taxonomy, array(
                    'orderby' => 'name',
                    'order' => 'ASC',
                    'hide_empty' => $remove_empty_brands_status,
                ));

                // Section transform to up and down --------------------------------------------------------------
                $section_transfrom_values = Irantheme_Section_Utils::get_transform_values_arrays($data_page, $fields_values, true, false, false);
                $section_transform_extra_css = $section_transfrom_values['overflow'];
                $section_transform_translate_css = $section_transfrom_values['css'];

                // Count columns of brands --------------------------------------------------------------
                $col_count = $fields_values['col_count_radio'];

                // If slider is true, assign new swiper classes to wrapper, row and item
                $wrapper_mode_class = 'swiper irantheme-woocommerce-box-brands-post-swiper';
                $row_mode_class  = 'swiper-wrapper';
                $column_mode_class = 'swiper-slide';

                // Get brands count mode --------------------------------------------------------------
                $brands_count_checkbox = $fields_values['product_count_checkbox'];

                // Get border radius --------------------------------------------------------------
                $border_radius_range = $fields_values['border_radius_range'];
                $border_radius_style = Irantheme_Section_Utils::get_border_radius_css($border_radius_range);

                // Get brand options checkbox --------------------------------------------------------------
                $brand_shadow_checkbox = $fields_values['shadow_checkbox'];
                $brand_shadow_class = '';
                if ($brand_shadow_checkbox == 'on') {
                    $brand_shadow_class = 'irantheme-woocommerce-box-brands-box-style-shadow';
                }

                // Get brand options checkbox --------------------------------------------------------------
                $brand_border_checkbox = $fields_values['border_checkbox'];
                $brand_border_class = '';
                if ($brand_border_checkbox == 'on') {
                    $brand_border_class = 'irantheme-woocommerce-box-brands-box-style-border';
                }
            }

            // Get section id --------------------------------------------------------------
            $section_id = $fields_values['element_id'];
            $section_id_attribute = '';
            if ($section_id) {
                $section_id_attribute = sprintf('id=%s', $section_id);
            }
?>
            <!-- ============================= Section Woocommerce Brands ============================= -->
            <section <?php echo esc_attr($section_id_attribute); ?> class="irantheme-section irantheme-woocommerce-box-brands-section <?php echo esc_attr($skeleton_section_class); ?>" <?php echo esc_attr(Irantheme_Page_Builder::get_attr_data_section($data_type, $data_value, $data_page, $data_ajax)); ?> style="<?php echo esc_attr($section_transform_extra_css); ?><?php echo esc_attr(Irantheme_Section_Service::get_custom_outer_padding_css($fields_values)); ?>">
                <?php
                if (is_woocommerce_activated() && isset($brand_taxonomy) && $brand_taxonomy) :
                    // Set wrapper (Container) --------------------------------------------------------------
                    $container_checkbox = $fields_values['wrapper_mode_checkbox'];
                ?>
                    <div class="irantheme-woocommerce-box-brands <?php echo esc_attr($base_color_class); ?> irantheme-background-jarallax" style="<?php echo esc_attr($background_color_css); ?><?php echo esc_attr($background_image_css); ?><?php echo esc_attr(Irantheme_Section_Service::get_custom_inner_padding_css($fields_values)); ?><?php echo esc_attr($section_transform_translate_css); ?>" <?php echo $background_parallax_attr; ?>>

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
                            if ($data_load_checkbox != 'on' && $heading_data_load_checkbox != 'on') {
                                // Skeleton html --------------------------------------------------------------
                                irantheme_get_skeleton_html($col_count, 'text', $base_color_class);
                                $skeleton_load_class = 'irantheme-side-load-skeleton';
                            }
                            ?>

                            <!-- Brands post -->
                            <div class="irantheme-woocommerce-box-brands-wrapper <?php echo esc_attr($wrapper_mode_class); ?> <?php echo esc_attr($skeleton_load_class); ?>" <?php echo (Irantheme_Section_Service::get_slider_data_column($col_count)); ?> <?php echo esc_attr(Irantheme_Section_Utils::get_auto_slider_data_attribute($fields_values)); ?> <?php echo esc_attr(Irantheme_Section_Utils::get_auto_slider_delay_data_attribute($fields_values)); ?>>
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
                                    if (!empty($terms) && !is_wp_error($terms)) {
                                        foreach ($terms as $term) {
                                            // Get brand logo image
                                            $brand_logo = irantheme_get_brand_logo($term->term_id, $brand_taxonomy);
                                    ?>
                                            <!-- Column -->
                                            <div class="<?php echo esc_attr($column_mode_class); ?>" style="width:<?php echo esc_attr(Irantheme_Section_Service::get_column_width_percentage($col_count)); ?>%;">
                                                <div class="irantheme-woocommerce-box-brands-col <?php echo esc_attr($brand_border_class); ?> <?php echo esc_attr($brand_shadow_class); ?>" style="<?php echo esc_attr($border_radius_style); ?>" <?php echo ($data_load_attr); ?> data-aos-delay="<?php echo ($aos_delay); ?>" data-aos-duration="<?php echo ($data_load_duration); ?>">
                                                    <!-- Brand item -->
                                                    <div class="irantheme-woocommerce-box-brands-item">
                                                        <!-- Heading -->
                                                        <div class="irantheme-woocommerce-box-brands-item-heading">
                                                            <?php if ($term->name): ?>
                                                                <!-- Title -->
                                                                <h4 class="irantheme-woocommerce-box-brands-item-heading-title">
                                                                    <?php echo esc_html($term->name); ?>
                                                                    <?php if ($brands_count_checkbox == 'on') : ?>
                                                                        <span class="irantheme-woocommerce-box-brands-item-heading-title-product-count">(<?php echo esc_html($term->count); ?>)</span>
                                                                    <?php endif; ?>
                                                                </h4>
                                                            <?php endif; ?>

                                                            <!-- Image -->
                                                            <div class="irantheme-woocommerce-box-brands-item-heading-image">
                                                                <?php if ($brand_logo) : ?>
                                                                    <?php echo $brand_logo; ?>
                                                                <?php else: ?>
                                                                    <img class="irantheme-woocommerce-box-brands-item-heading-image-img" src="<?php echo IRANTHEME_URI . '/assets/images/placeholder.png'; ?>" alt="<?php echo esc_attr($term->name); ?>" loading="lazy">
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                        <div class="irantheme-woocommerce-box-brands-item-footer">
                                                            <?php
                                                            // Set brand description
                                                            $term_description = '';
                                                            if ($term->description):
                                                                $term_description = $term->description;
                                                            else:
                                                                $term_description = __('فاقد توضیحات برند');
                                                            endif; ?>
                                                            <!-- Description -->
                                                            <p class="irantheme-woocommerce-box-brands-item-footer-desc">
                                                                <?php echo esc_html($term_description); ?>
                                                            </p>
                                                            <?php
                                                            // Arrow url --------------------------------------------------------------
                                                            $details_color_css = '';
                                                            $details_color_rgba_css = '';
                                                            $details_color_rgb_array = Irantheme_Color_Static::hex_to_rgb(Irantheme_Color_Static::get_hex_color($details_color));
                                                            if ($details_color != 'off') {
                                                                $details_color_css = 'color:#' . esc_attr(Irantheme_Color_Static::get_hex_color($details_color)) . ';';
                                                                $details_color_rgba_css = 'background-color:rgba(' . $details_color_rgb_array[0] . ',' . $details_color_rgb_array[1] . ',' . $details_color_rgb_array[2] . ',' . '0.1' . ');';
                                                            }
                                                            $encoded_slug = urlencode($term->slug);
                                                            $brand_url = add_query_arg(
                                                                str_replace('pa_', 'filter_', $brand_taxonomy),
                                                                $encoded_slug . '%2c',
                                                                wc_get_page_permalink('shop')
                                                            );
                                                            ?>
                                                            <a href="<?php echo esc_url($brand_url); ?>" class=" irantheme-btn irantheme-btn-inline irantheme-woocommerce-box-brands-item-footer-link" style="<?php echo $details_color_rgba_css; ?>">
                                                                <span style="<?php echo esc_attr($details_color_css); ?>"><?php echo esc_html("مشاهده"); ?></span>
                                                                <i class="fa-light fa-angle-left" style="<?php echo esc_attr($details_color_css); ?>"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    <?php
                                            // Increase aos delay for next item
                                            $aos_delay += 200;
                                        }
                                    } else {
                                        // Empty status
                                        irantheme_display_empty_status('list', 'برندی برای محصولات یافت نشد.', 'با رفتن به محیط ادمین و تب "محصولات" -> "ویژگی‌های محصول" یک ویژگی از نوع "برند" ایجاد کرده و سپس برندهای خود را اضافه کنید.');
                                    }
                                    ?>
                                </div>
                                <?php
                                // Slider pagination option
                                $slider_pagination_checkbox = $fields_values['slider_pagination_checkbox'];
                                // Slider arrow option
                                $slider_arrow_checkbox = $fields_values['slider_arrow_checkbox'];
                                Irantheme_Section_Utils::get_slider_arrow_pagination('on', $slider_arrow_checkbox, $slider_pagination_checkbox, $base_color_class, $border_radius_range, 'bottom', 'center');
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
                elseif (!is_woocommerce_activated()) :
                    // Empty status
                    irantheme_display_empty_status('empty-set', 'متاسفانه افزونه ووکامرس فعال یا نصب نمی باشد.', 'با رفتن به محیط ادمین و تب "افزونه ها" ووکامرس را نصب یا فعال نمایید.');
                elseif (!isset($brand_taxonomy) || !$brand_taxonomy) :
                    // Empty status for no brand taxonomy
                    irantheme_display_empty_status('empty-set', 'هیچ برندی تعریف نشده است.', 'با رفتن به محیط ادمین و تب "محصولات" -> "ویژگی‌های محصول" یک ویژگی از نوع "برند" ایجاد کنید.');
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
