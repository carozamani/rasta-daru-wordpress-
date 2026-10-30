<?php

use Irantheme_Dev\Irantheme_Color_Static;
use Irantheme_Dev\Irantheme_Common_Static;

global $product;
$product_id = get_the_ID();
$product = wc_get_product($product_id);
$_product = wc_get_product($product_id);
$attachment_ids = $_product->get_gallery_image_ids();
$attachment_urls = array();
$product_thumbnail_id = get_post_thumbnail_id($product_id);
$product_thumbnail = wp_get_attachment_url($product_thumbnail_id);
$product_status = $product->get_stock_status();
$full_width_class = '';
$auto_hide_class = '';
$base_color_class = '';
$glass_bg_class = '';
if (is_product()):
    // Get navbar mobile options
    $mobile_options = get_option('irantheme_wc_navbar_mobile_options_checkbox_multi');
    $mobile_options_array = explode(',', $mobile_options);

    // Full width checkbox
    $full_width_checkbox = get_option('irantheme_wc_navbar_mobile_full_width_checkbox');
    if ($full_width_checkbox == 'on') {
        $full_width_class = 'irantheme-navbar-mobile-full-width';
    }

    // Glass style checkbox
    $glass_bg_checkbox = get_option('irantheme_wc_navbar_mobile_glass_style_checkbox', 'off');
    if ($glass_bg_checkbox == 'on') {
        $glass_bg_class = 'irantheme-navbar-mobile-glass-bg';
    }

    // Auto hide checkbox
    $auto_hide_checkbox = get_option('irantheme_wc_navbar_mobile_auto_hide_checkbox');
    if ($auto_hide_checkbox == 'on') {
        $auto_hide_class = 'irantheme-navbar-mobile-auto-hide';
    }

    // Auto hide checkbox
    $base_color = get_option('irantheme_wc_navbar_mobile_base_color_select');
    // Set base color class
    $base_color_class = Irantheme_Common_Static::get_base_color_class($base_color);

    $variable_selected_checkbox = get_option('irantheme_single_product_variable_selected_checkbox', 'on');
    $seller_name_checkbox = get_option('irantheme_single_product_seller_name_checkbox');
    $seller_name_text = get_option('irantheme_single_product_seller_name');
    $shipping_time_checkbox = get_option('irantheme_single_product_shipping_time_checkbox', 'on');
    $shipping_time_text = get_post_meta($product_id, 'irantheme_custom_field_shipping_time', true);

    $add_to_card_btn_color_customization_checkbox = get_option('irantheme_single_product_add_to_card_btn_color_customization_checkbox');
    $add_to_card_btn_bg_color = get_option('irantheme_single_product_add_to_card_btn_bg_color');
    $add_to_card_btn_for_color = get_option('irantheme_single_product_add_to_card_btn_color');
    $add_to_card_btn_bg_color_class = '';
    $add_to_card_btn_color_class = '';
    $add_to_card_btn_hover_color_class = '';
    $add_to_card_btn_border_color_class = '';
    if ($add_to_card_btn_color_customization_checkbox == 'on') {
        if ($add_to_card_btn_bg_color != 'off') {
            $add_to_card_btn_bg_color_class = esc_attr(Irantheme_Color_Static::get_class_color_mode('bg', null, Irantheme_Color_Static::get_hex_color($add_to_card_btn_bg_color)));
            $add_to_card_btn_hover_color_class = esc_attr(Irantheme_Color_Static::get_class_color_mode('color-hover', null, Irantheme_Color_Static::get_hex_color($add_to_card_btn_bg_color)));
            $add_to_card_btn_border_color_class = esc_attr(Irantheme_Color_Static::get_class_color_mode('border', null, Irantheme_Color_Static::get_hex_color($add_to_card_btn_bg_color)));
        }
        if ($add_to_card_btn_for_color == 'off') {
            $add_to_card_btn_color_class = Irantheme_Color_Static::get_optimal_text_color(Irantheme_Color_Static::get_hex_color($add_to_card_btn_bg_color));
        } else {
            $add_to_card_btn_color_class = 'text-color-' . $add_to_card_btn_for_color;
        }
    }
?>

    <!-- Navbar mobile -->
    <div class="irantheme-single-product-navbar-mobile <?php echo esc_attr($base_color_class); ?> <?php echo esc_attr($full_width_class); ?> <?php echo esc_attr($auto_hide_class); ?> <?php echo esc_attr($glass_bg_class); ?>">
        <!-- Navbar list -->
        <div class="irantheme-single-product-navbar-mobile-content">
            <div class="product-add-to-card">
                <?php
                $is_call_for_price = get_post_meta($product->get_id(), 'irantheme_product_meta_is_call_for_price', true);
                if ($is_call_for_price != 'yes'):
                ?>
                    <?php if (($product_status == 'instock' || $product_status == 'onbackorder') && $product->get_price() != ''): ?>
                        <?php if ($variable_selected_checkbox != 'off' && (($seller_name_checkbox != 'off' && $seller_name_text != '') || ($shipping_time_checkbox != 'off' && $shipping_time_text != ''))): ?>
                            <div class="irantheme-single-product-divider zero-m"></div>
                        <?php endif; ?>
                        <div class="product-add-to-card-price">
                            <?php woocommerce_template_single_price(); ?>
                        </div>
                        <?php
                        // Product type is external
                        if ($product->is_type('external')) :
                        ?>
                            <div class="product-add-to-card-button">
                                <?php woocommerce_template_single_add_to_cart(); ?>
                            </div>
                        <?php
                        // Product type is simple or variable
                        elseif ($product->is_type('simple') || $product->is_type('variable')): ?>
                            <div class="product-add-to-card-button">
                                <?php do_action('woocommerce_single_variation'); ?>
                            </div>
                        <?php endif; ?>
                    <?php elseif ($product_status == 'outofstock' || $product->get_price() == ''): ?>
                        <div class="product-add-to-card-button outofstock">
                            <button class="irantheme-single-add-to-cart-button single_add_to_cart_button button outofstock <?php echo $add_to_card_btn_color_class; ?> <?php echo $add_to_card_btn_bg_color_class; ?> <?php echo $add_to_card_btn_border_color_class; ?>"><?php _e('ناموجود'); ?></button>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <?php
                    $support_phone_number = get_option('irantheme_single_product_support_phone_number', '0913XXXXXXX');
                    ?>
                    <div class="product-add-to-card-button call-for-price-info">
                        <a href="tel:<?php echo esc_attr($support_phone_number) ?>" class="irantheme-single-add-to-cart-button single_add_to_cart_button button <?php echo $product_status; ?> <?php echo $add_to_card_btn_color_class; ?> <?php echo $add_to_card_btn_bg_color_class; ?> <?php echo $add_to_card_btn_hover_color_class; ?> <?php echo $add_to_card_btn_border_color_class; ?>"><?php _e('تماس بگیرید'); ?></a>
                        <span class="product-add-to-card-call-info">
                            <span class="product-add-to-card-call-info-label"><?php _e('شماره تماس پشتیبانی'); ?></span>
                            <span class="product-add-to-card-call-info-number"><?php echo esc_html($support_phone_number); ?></span>
                        </span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

<?php endif; ?>