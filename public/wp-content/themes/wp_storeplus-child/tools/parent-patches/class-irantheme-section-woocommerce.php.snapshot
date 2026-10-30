<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;


if (!class_exists('Irantheme_Section_Woocommerce')) {

    /**
     * Create custom fields for post types service
     *
     * @package Wordpress
     * @subpackage irantheme
     * @since irantheme - custom fields
     * @author iranianclever
     * @see v1.0
     *
     */

    class Irantheme_Section_Woocommerce
    {
        private static $text_domain = IRANTHEME_TEXTDOMAIN;

        /**
         * Display product item
         * @return void
         * @param object,array,string,string,string,string,string,string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_product_item($product, $product_features, $base_color, $details_color_select, $details_color_select2, $product_discount_checkbox, $product_total_sales_checkbox, $product_discount_schedule_checkbox, $product_color_attribute_checkbox, $product_image_options_class, $product_image_extra_options_style_class, $product_border_class, $product_image_hover_changer_class, $product_image_hover_mode_class, $product_hover_mode_class, $post_content_align, $product_shadow_class, $slider_mode_column, $product_excerpt_count, $product_extra_options_checkbox, $aos_delay, $data_load_attr, $data_load_duration, $border_radius_range, $product_padding_class, $product_transparent_class, $extra_css = '')
        {
            // Child theme hook: ONE product card component draws every vertical card (wp_storeplus-child/inc/product-card.php).
            // Without the child theme (function missing) the original card below is drawn. Backup: class-irantheme-section-woocommerce.php.bak-spcard
            if (function_exists('storeplus_child_product_card')) {
                storeplus_child_product_card($product, array(
                    'layout'       => 'vertical',
                    'col_class'    => $slider_mode_column,
                    'hover_class'  => $product_hover_mode_class,
                    'extra_css'    => $extra_css,
                    'aos_attr'     => $data_load_attr,
                    'aos_delay'    => $aos_delay,
                    'aos_duration' => $data_load_duration,
                ));
                return;
            }

            // Product ID (avoid multiple calls)
            $product_id = $product->get_id();

            // Check radius style in image
            $border_radius_style = Irantheme_Section_Utils::get_border_radius_css($border_radius_range);
            $product_image_radius = '';
            if ($product_padding_class) {
                // All corners rounded
                $product_image_radius = $border_radius_style;
            } else {
                if (!$product_border_class && !$product_shadow_class) {
                    // All corners rounded
                    $product_image_radius = $border_radius_style;
                } else {
                    // Only top corners rounded — bottom flat
                    $product_image_radius = $border_radius_style
                        . 'border-bottom-right-radius:0;'
                        . 'border-bottom-left-radius:0;';
                }
            }

            $product_out_of_stock_class = '';
            if (!$product->is_in_stock()) {
                $product_out_of_stock_class = 'irantheme-out-of-stock-product';
            }

            $details_color_class = '';
            $details_color_class_color = '';
            if ($details_color_select != 'off') {
                $details_color_class = Irantheme_Color_Static::get_class_color_mode('bg', null, Irantheme_Color_Static::get_hex_color($details_color_select));
                $details_color_class_color = Irantheme_Color_Static::get_class_color_mode('color', null, Irantheme_Color_Static::get_hex_color($details_color_select));
            }
            $details_color_class_bg_2 = '';
            $details_color_class_color_2 = '';
            if ($details_color_select2 != 'off') {
                $details_color_class_bg_2 = Irantheme_Color_Static::get_class_color_mode('bg', null, Irantheme_Color_Static::get_hex_color($details_color_select2));
                $details_color_class_color_2 = Irantheme_Color_Static::get_class_color_mode('color', null, Irantheme_Color_Static::get_hex_color($details_color_select2));
            }

            // Precompute add-to-cart values once (used in two places)
            $add_to_cart_ajax_class = 'ajax_add_to_cart';
            $add_to_cart_link       = esc_url(home_url('/?add-to-cart=' . $product_id));
            if ($product->is_type('variable')) {
                $add_to_cart_ajax_class = '';
                $add_to_cart_link       = get_permalink($product_id);
            }

            // Precompute alignment class
            $alignment_class = Irantheme_Section_Service::get_alignment_class($post_content_align);
?>
            <div class="irantheme-woocommerce-products-col <?php echo esc_attr($slider_mode_column); ?> <?php echo esc_attr($product_hover_mode_class); ?>" style="<?php echo esc_attr($extra_css); ?>" <?php echo ($data_load_attr); ?> data-aos-delay="<?php echo ($aos_delay); ?>" data-aos-duration="<?php echo ($data_load_duration); ?>">
                <div class="irantheme-woocommerce-products-item <?php echo esc_attr($product_out_of_stock_class); ?> <?php echo esc_attr($alignment_class); ?> <?php echo esc_attr($product_border_class); ?> <?php echo esc_attr($product_shadow_class); ?> <?php echo esc_attr($product_padding_class); ?> <?php echo esc_attr($product_transparent_class); ?>" style="<?php echo esc_attr($border_radius_style); ?>">
                    <?php if ($product_discount_schedule_checkbox == 'on'): ?>
                        <!-- Discount schedule -->
                        <?php Irantheme_Section_Woocommerce::display_schedule_product($product, $base_color, 'flex-center', '', '', '', $details_color_class_color); ?>
                    <?php endif; ?>
                    <?php if (in_array('image', $product_features)) : ?>
                        <!-- Thumbnail -->
                        <div class="irantheme-woocommerce-products-item-image <?php echo esc_attr($product_image_hover_changer_class); ?>">
                            <!-- Thumbnail -->
                            <a href="<?php the_permalink(); ?>" class="irantheme-product-image-holder <?php echo esc_attr($product_image_hover_mode_class); ?>">
                                <?php
                                echo wp_get_attachment_image(
                                    get_post_thumbnail_id($product_id),
                                    'medium',
                                    false,
                                    [
                                        'class' => 'irantheme-woocommerce-products-item-img irantheme-product-image',
                                        'alt' => __('تصویر محصول', self::$text_domain),
                                        'loading' => 'lazy',
                                        'style' => esc_attr($product_image_radius),
                                    ]
                                );

                                // Gallery first image — without creating new WC_Product
                                $attachment_ids = $product->get_gallery_image_ids();
                                if (!empty($attachment_ids)) {
                                    echo wp_get_attachment_image(
                                        $attachment_ids[0],
                                        'medium',
                                        false,
                                        [
                                            'class' => 'irantheme-woocommerce-products-item-gallery-img irantheme-product-image',
                                            'alt' => __('تصویر محصول', self::$text_domain),
                                            'loading' => 'lazy',
                                            'style' => esc_attr($product_image_radius),
                                        ]
                                    );
                                }
                                ?>
                            </a>

                            <div class="irantheme-woocommerce-products-item-abs">
                                <?php if (!$product->is_in_stock()) : ?>
                                    <!-- Total sales -->
                                    <div class="irantheme-woocommerce-products-item-abs-item">
                                        <i class="iconsax <?php echo esc_attr($details_color_class_bg_2); ?>" icon-name="not-allowed-3"></i>
                                        <span class="<?php echo esc_attr($details_color_class_color_2); ?>">
                                            <cite><?php _e('ناموجود'); ?></cite>
                                        </span>
                                    </div>
                                <?php elseif ($product_total_sales_checkbox == 'on') : ?>
                                    <!-- Total sales -->
                                    <div class="irantheme-woocommerce-products-item-abs-item">
                                        <i class="fa-solid fa-fire <?php echo esc_attr($details_color_class_bg_2); ?>"></i>
                                        <span class="<?php echo esc_attr($details_color_class_color_2); ?>">
                                            <?php echo $product->get_total_sales(); ?>
                                            <cite><?php _e('فروش') ?></cite>
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Options -->
                            <div class="irantheme-woocommerce-products-item-options <?php echo esc_attr($product_image_options_class); ?> <?php echo esc_attr($product_image_extra_options_style_class); ?>">
                                <ul class="irantheme-woocommerce-products-item-options-list">
                                    <li class="irantheme-woocommerce-products-item-options-list-li shop">
                                        <a href="<?php echo esc_url(home_url('/?add-to-cart=' . $product_id)); ?>" value="<?php echo esc_attr($product_id); ?>" class="<?php echo esc_attr($add_to_cart_ajax_class); ?> add_to_cart_button irantheme-woocommerce-products-item-options-list-li-link" data-product_id="<?php echo $product_id; ?>">
                                            <!-- Shopping bag icon -->
                                            <i class="iconsax irantheme-icon-bag <?php echo esc_attr($details_color_class_color_2); ?>" icon-name="shopping-cart"></i>
                                            <i id="irantheme-spinner-alt" class="irantheme-animate-rotate <?php echo esc_attr($details_color_class_color_2); ?>"></i>
                                            <i class="fa-light fa-check irantheme-icon-check <?php echo esc_attr($details_color_class_color_2); ?>"></i>
                                        </a>
                                    </li>
                                    <li class="irantheme-woocommerce-products-item-options-list-li heart">
                                        <a href="#" class="irantheme-woocommerce-products-item-options-list-li-link wishlist-toggle" data-product="<?php echo esc_attr($product_id); ?>">
                                            <i class="fa-light fa-heart <?php echo esc_attr($details_color_class_color_2); ?>"></i>
                                        </a>
                                    </li>
                                    <li class="irantheme-woocommerce-products-item-options-list-li glass">
                                        <button type="button" class="irantheme-btn-void irantheme-woocommerce-products-item-options-list-li-link irantheme-woocommerce-product-fast-access-toggle" data-product-id="<?php echo $product_id; ?>">
                                            <i class="iconsax <?php echo esc_attr($details_color_class_color_2); ?>" icon-name="search-normal-2"></i>
                                        </button>
                                    </li>
                                </ul>
                            </div>

                            <?php if ($product_color_attribute_checkbox == 'on') : ?>
                                <!-- Color circles -->
                                <?php
                                $color_attributes = $product->get_attributes();
                                if (!empty($color_attributes)) :
                                ?>
                                    <div class="irantheme-woocommerce-products-item-colors">
                                        <ul class="irantheme-woocommerce-products-item-colors-list">
                                            <?php
                                            foreach ($color_attributes as $attribute) {
                                                $attr_name = $attribute->get_name();
                                                if ($attr_name === 'pa_color' || $attr_name === 'color') {
                                                    $terms = $attribute->get_terms();
                                                    if (!empty($terms) && !is_wp_error($terms)) {
                                                        foreach ($terms as $term) {
                                                            $color = get_option("_term_type_{$attr_name}_{$term->term_id}");
                                                            if (!$color) {
                                                                $color = get_term_meta($term->term_id, 'color', true);
                                                            }
                                                            if (!$color) {
                                                                $color = get_term_meta($term->term_id, 'product_attribute_color', true);
                                                            }
                                                            if (!$color) {
                                                                $color = get_term_meta($term->term_id, 'swatch_color', true);
                                                            }
                                                            if (!$color) {
                                                                $color = '#cccccc';
                                                            }
                                            ?>
                                                            <li class="irantheme-woocommerce-products-item-colors-list-item">
                                                                <span class="irantheme-woocommerce-products-item-colors-list-item-circle" style="background-color: <?php echo esc_attr($color); ?>;" title="<?php echo esc_attr($term->name); ?>"></span>
                                                            </li>
                                            <?php
                                                        }
                                                    }
                                                }
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <!-- Content -->
                    <div class="irantheme-woocommerce-products-item-content">
                        <?php if (in_array('category', $product_features) || in_array('rating', $product_features)) : ?>
                            <div class="irantheme-woocommerce-products-item-content-top">
                                <?php if (in_array('category', $product_features)) : ?>
                                    <!-- Category -->
                                    <div class="irantheme-woocommerce-products-item-content-category">
                                        <?php
                                        // Use wc_get_product_category_list (optimized, single call)
                                        echo wc_get_product_category_list($product_id, ' ');
                                        ?>
                                    </div>
                                <?php endif; ?>
                                <?php if (in_array('rating', $product_features)) : ?>
                                    <?php
                                    if (wc_review_ratings_enabled()) :
                                        $rating_alignment_class = '';
                                        if ($post_content_align == 'right') {
                                            $rating_alignment_class = 'flex-right';
                                        } elseif ($post_content_align == 'left') {
                                            $rating_alignment_class = 'flex-left';
                                        } elseif ($post_content_align == 'center') {
                                            $rating_alignment_class = 'flex-center';
                                        }
                                    ?>
                                        <!-- Rating -->
                                        <div class="irantheme-woocommerce-products-item-content-rating <?php echo esc_attr($rating_alignment_class); ?>">
                                            <span><?php echo esc_attr($product->get_average_rating()); ?></span>
                                            <i class="fa-solid fa-star"></i>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <?php if (in_array('title', $product_features)) : ?>
                            <!-- Heading -->
                            <h2 class="irantheme-woocommerce-products-item-content-heading">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </h2>
                        <?php endif; ?>
                        <?php if (in_array('excerpt', $product_features)) : ?>
                            <!-- Excerpt -->
                            <div class="irantheme-woocommerce-products-item-content-excerpt">
                                <p>
                                    <?php
                                    // Use short_description directly (avoids the_content filters)
                                    $short_desc = $product->get_short_description();
                                    if ($short_desc) {
                                        echo wp_kses_post(wp_trim_words($short_desc, $product_excerpt_count));
                                    } else {
                                        echo wp_kses_post(wp_trim_words(get_the_excerpt(), $product_excerpt_count));
                                    }
                                    ?>
                                </p>
                            </div>
                        <?php endif; ?>
                        <div class="irantheme-woocommerce-products-item-content-bottom">
                            <?php if (in_array('cart', $product_features)) : ?>
                                <div class="irantheme-woocommerce-products-item-add-to-cart">
                                    <!-- Add to cart -->
                                    <a href="<?php echo $add_to_cart_link; ?>" value="<?php echo esc_attr($product_id); ?>" class="irantheme-woocommerce-products-item-add-to-cart-button <?php echo esc_attr($add_to_cart_ajax_class); ?> add_to_cart_button <?php echo esc_attr($details_color_class); ?>" data-product_id="<?php echo $product_id; ?>" style="<?php echo esc_attr($border_radius_style); ?>">
                                        <i class="iconsax irantheme-woocommerce-products-item-add-to-cart-button-plus" icon-name="shopping-cart"></i>
                                        <i class="fa-light fa-check irantheme-woocommerce-products-item-add-to-cart-button-success"></i>
                                        <i class="irantheme-animate-rotate" style="display: none;"></i>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <?php if (in_array('price', $product_features)) : ?>
                                <!-- Price -->
                                <div class="irantheme-woocommerce-products-item-content-bottom-price">
                                    <?php
                                    // Check discount status — use is_on_sale() as the reliable check
                                    $discount_status = false;
                                    if ($product_discount_checkbox == 'on' && $product->is_on_sale()) {
                                        $discount_status = true;
                                    }
                                    self::display_product_price($product, $discount_status, $details_color_select);
                                    ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }


        /**
         * Display schedule of product
         * @return void
         * @param object
         * @param bool
         * @param string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function display_product_price($product, $with_discount = false, $details_color_select = '')
        {
            // Precompute color css once
            $details_color_css = '';
            if ($details_color_select != 'off') {
                $hex_color = Irantheme_Color_Static::get_hex_color($details_color_select);
                $rgb       = Irantheme_Color_Static::hex_to_rgb($hex_color);
                $details_color_css = 'color:#' . esc_attr($hex_color) . ';';
                $details_color_css .= 'background-color:rgba(' . $rgb[0] . ',' . $rgb[1] . ',' . $rgb[2] . ',' . '0.1' . ')';
            }

            if ($product->is_in_stock()) {
                if ($product->is_type('variable')) {
                    // Get the price range for variable products (false = excluding tax, matches simple branch)
                    $min_price = $product->get_variation_price('min', false);
                    $max_price = $product->get_variation_price('max', false);
                    $min_regular_price = $product->get_variation_regular_price('min', false);
                    $max_regular_price = $product->get_variation_regular_price('max', false);

                    $discount_html = '';
                    if ($with_discount) {
                        $discount_html = '<div class="irantheme-woocommerce-products-holder">';
                    }

                    if ($min_price < $min_regular_price) {
                        // If a sale exists (the lowest sale price is less than the lowest regular price)
                        $price_html = $discount_html . '<del>' . wc_price($min_regular_price) . '</del>';
                        if ($with_discount) {
                            $price_html .= '<span class="irantheme-woocommerce-products-discount" style="' . $details_color_css . '">' . irantheme_get_sale_percentage($product) . '</span></div>';
                        }
                        $price_html .= '<ins>' . wc_price($min_price) . '</ins>';
                    } else {
                        // If there is no sale or min and max prices are the same
                        if ($min_price !== $max_price) {
                            $price_html = wc_price($min_price) . ' - ' . wc_price($max_price); // Price range
                        } else {
                            $price_html = wc_price($min_price); // Fixed price
                        }
                    }
                } else {
                    // Your logic for Simple Products, which works correctly
                    $regular_price = $product->get_regular_price();
                    $sale_price = $product->get_sale_price();
                    $discount_html = '';
                    if (
                        !isset($regular_price) ||
                        trim($regular_price) === '' ||
                        !is_numeric($regular_price) ||
                        $regular_price < 0
                    ) {
                        $price_html = __('ناموجود');
                    } elseif ($regular_price == 0) {
                        $price_html = __('رایگان!');
                    } else {
                        if ($with_discount) {
                            $discount_html = '<div class="irantheme-woocommerce-products-holder">';
                        }

                        if ($sale_price && $sale_price > 0 && $sale_price < $regular_price) {
                            $price_html = $discount_html . '<del>' . wc_price($regular_price) . '</del>';
                            if ($with_discount) {
                                $price_html .= '<span class="irantheme-woocommerce-products-discount" style="' . $details_color_css . '">' . irantheme_get_sale_percentage($product) . '</span></div>';
                            }
                            $price_html .= '<ins>' . wc_price($sale_price) . '</ins>';
                        } else {
                            $price_html = wc_price($regular_price);
                        }
                    }
                }
            } else {
                $price_html = __('ناموجود');
            }

            if (!empty($price_html)) : ?>
                <span class="price irantheme-woocommerce-products-price"><?php echo $price_html; ?></span>
            <?php endif;
        }


        /**
         * Return wc price without currency
         * @return void
         * @param object
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function wc_price_without_currency($price)
        {
            if (empty($price)) return '';
            $formatted = number_format((float)$price, 0, '.', '.');
            return $formatted;
        }


        /**
         * Display schedule of product
         * @return void
         * @param object
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function display_schedule_product($product, $base_color_class = 'white', $alignment_class = 'flex-right', $icon_name = '', $offer_text = '', $two_points = false, $set_color_class = '')
        {
            $sales_price_from = get_post_meta($product->get_id(), '_sale_price_dates_from', true);
            $sales_price_to   = get_post_meta($product->get_id(), '_sale_price_dates_to', true);
            $alignment_class_caption = '';
            switch ($alignment_class) {
                case 'flex-right':
                    $alignment_class_caption = 'text-end';
                    break;

                case 'flex-center':
                    $alignment_class_caption = 'text-center';
                    break;

                case 'flex-left':
                    $alignment_class_caption = 'text-start';
                    break;

                default:
                    $alignment_class_caption = 'text-end';
                    break;
            }

            if ($product->is_on_sale() && $sales_price_to != "") {
                $now_time = new \DateTime();
                $end_counter = new \DateTime(date('Y-m-d H:i:s', $sales_price_to));

                $interval = $now_time->diff($end_counter);

                $days    = $interval->d;
                $hours   = $interval->h;
                $minutes = $interval->i;
                $seconds = $interval->s;

                // Ensure single-digit numbers are padded with a leading zero
                $hours   = str_pad($hours, 2, '0', STR_PAD_LEFT);
                $minutes = str_pad($minutes, 2, '0', STR_PAD_LEFT);
                $seconds = str_pad($seconds, 2, '0', STR_PAD_LEFT);
            ?>
                <div class="irantheme-woocommerce-products-schedule irantheme-showcase-counter-countdown <?php echo esc_attr($base_color_class); ?>" data-time-end="<?php echo esc_attr($end_counter->format('Y-m-d H:i:s')); ?>" data-time-now="<?php echo esc_attr($now_time->format('Y-m-d H:i:s')); ?>" data-mode="normal" style="color:white;">
                    <div class="irantheme-woocommerce-products-schedule-caption irantheme-showcase-counter-countdown-caption <?php echo esc_attr($alignment_class_caption); ?>" style="display: none;">زمان به پایان رسیده است!</div>
                    <ul class="irantheme-woocommerce-products-schedule-list <?php echo esc_attr($alignment_class); ?>">
                        <?php if ($icon_name != '') : ?>
                            <li class="irantheme-woocommerce-products-schedule-list-item <?php echo esc_attr($alignment_class); ?>">
                                <i class="irantheme-woocommerce-products-schedule-list-item-icon fa-solid fa-<?php echo esc_attr($icon_name); ?>"></i>
                            </li>
                        <?php endif; ?>
                        <?php if ($offer_text != '') : ?>
                            <li class="irantheme-woocommerce-products-schedule-list-item">
                                <p class="irantheme-woocommerce-products-schedule-list-item-title"><?php echo esc_attr($offer_text); ?></p>
                            </li>
                        <?php endif; ?>
                        <li class="irantheme-woocommerce-products-schedule-list-item <?php echo esc_attr($alignment_class); ?>">
                            <span class="irantheme-woocommerce-products-schedule-list-item-count irantheme-showcase-counter-countdown-list-item-second <?php echo esc_attr($set_color_class); ?>"><?php echo esc_html($seconds); ?></span>
                            <?php if ($two_points): ?>
                                <cite>:</cite>
                            <?php else: ?>
                                <cite class="">ثانیه</cite>
                            <?php endif; ?>
                        </li>
                        <li class="irantheme-woocommerce-products-schedule-list-item <?php echo esc_attr($alignment_class); ?>">
                            <span class="irantheme-woocommerce-products-schedule-list-item-count irantheme-showcase-counter-countdown-list-item-minute <?php echo esc_attr($set_color_class); ?>"><?php echo esc_html($minutes); ?></span>
                            <?php if ($two_points): ?>
                                <cite>:</cite>
                            <?php else: ?>
                                <cite class="">دقیقه</cite>
                            <?php endif; ?>
                        </li>
                        <li class="irantheme-woocommerce-products-schedule-list-item <?php echo esc_attr($alignment_class); ?>">
                            <span class="irantheme-woocommerce-products-schedule-list-item-count irantheme-showcase-counter-countdown-list-item-hour <?php echo esc_attr($set_color_class); ?>"><?php echo esc_html($hours); ?></span>
                            <?php if ($two_points): ?>
                                <cite>:</cite>
                            <?php else: ?>
                                <cite class="">ساعت</cite>
                            <?php endif; ?>
                        </li>
                        <li class="irantheme-woocommerce-products-schedule-list-item <?php echo esc_attr($alignment_class); ?>">
                            <span class="irantheme-woocommerce-products-schedule-list-item-count irantheme-showcase-counter-countdown-list-item-day <?php echo esc_attr($set_color_class); ?>"><?php echo esc_html($days); ?></span>
                            <?php if (!$two_points): ?>
                                <cite class="">روز</cite>
                            <?php endif; ?>
                        </li>
                    </ul>
                </div>
<?php
            }
        }
    } // End Class
} // End if class exists statement