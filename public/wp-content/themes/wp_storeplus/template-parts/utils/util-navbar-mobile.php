<?php
// Get navbar mobile checkbox

use Irantheme_Dev\Irantheme_Color_Static;
use Irantheme_Dev\Irantheme_Common_Static;

$navbar_mobile_checkbox = get_option('irantheme_navbar_mobile_checkbox');
$full_width_class = '';
$auto_hide_class = '';
$base_color_class = '';
$glass_bg_class = '';
$radius_style = '';
if ($navbar_mobile_checkbox == 'on' && (!is_woocommerce_activated() || !is_product())):
    // Get navbar mobile options
    $mobile_options = get_option('irantheme_navbar_mobile_options_checkbox_multi');
    $mobile_options_array = explode(',', $mobile_options);

    // Full width checkbox & radius
    $full_width_checkbox = get_option('irantheme_navbar_mobile_full_width_checkbox');
    $radius_range = get_option('irantheme_navbar_mobile_radius_range', '0.5');
    if ($full_width_checkbox == 'on') {
        $full_width_class = 'irantheme-navbar-mobile-full-width';
    } elseif ($full_width_checkbox == 'off') {
        $full_width_class = 'irantheme-navbar-mobile-radius';
        $radius_style = sprintf('border-radius:%srem;', $radius_range);
    }

    // Glass style checkbox
    $glass_bg_checkbox = get_option('irantheme_navbar_mobile_glass_style_checkbox', 'on');
    if ($glass_bg_checkbox == 'on') {
        $glass_bg_class = 'irantheme-navbar-mobile-glass-bg';
    }

    // Auto hide checkbox
    $auto_hide_checkbox = get_option('irantheme_navbar_mobile_auto_hide_checkbox');
    if ($auto_hide_checkbox == 'on') {
        $auto_hide_class = 'irantheme-navbar-mobile-auto-hide';
    }

    // Auto hide checkbox
    $base_color = get_option('irantheme_navbar_mobile_base_color_select');
    // Set base color class
    $base_color_class = Irantheme_Common_Static::get_base_color_class($base_color);

    // Auto hide checkbox
    $color_details_select = get_option('irantheme_navbar_mobile_details_color_select');
    if ($color_details_select != 'off') {
        // Set base color class
        $color_details_class = Irantheme_Color_Static::get_class_color_mode('color-hover', null, Irantheme_Color_Static::get_hex_color($color_details_select));
        // Set base color class
        $color_details_class .= ' ' . Irantheme_Color_Static::get_class_color_mode('color-active', null, Irantheme_Color_Static::get_hex_color($color_details_select));
        // Set base color class
        $color_details_class .= ' ' . Irantheme_Color_Static::get_class_color_mode('color-active-before', null, Irantheme_Color_Static::get_hex_color($color_details_select));
        // Set base color class
        $color_details_class .= ' ' . Irantheme_Color_Static::get_class_color_mode('color-hover-before', null, Irantheme_Color_Static::get_hex_color($color_details_select));
    }
?>

    <!-- Navbar mobile -->
    <div class="irantheme-navbar-mobile <?php echo esc_attr($base_color_class); ?> <?php echo esc_attr($full_width_class); ?> <?php echo esc_attr($auto_hide_class); ?> <?php echo esc_attr($glass_bg_class); ?>" style="<?php echo esc_attr($radius_style); ?>">
        <!-- Navbar list -->
        <ul class="irantheme-navbar-mobile-list">
            <?php
            if (in_array('home', $mobile_options_array)):
                // Check current page is home
                $home_status = '';
                if (is_front_page()) {
                    $home_status = 'active';
                }
            ?>
                <!-- Home -->
                <li class="irantheme-navbar-mobile-list-item">
                    <a href="<?php echo site_url(); ?>" class="irantheme-navbar-mobile-list-item-link <?php echo esc_attr($home_status); ?> <?php echo esc_attr($color_details_class); ?>">
                        <i class="irantheme-navbar-mobile-list-item-link-icon fa-light fa-home"></i>
                        <span class="irantheme-navbar-mobile-list-item-link-title"><?php _e('خانه'); ?></span>
                    </a>
                </li>
            <?php endif; ?>
            <?php
            if (in_array('shop', $mobile_options_array)):
                // Check current page is shop
                $shop_status = '';
                $shop_url = '#';
                if (is_woocommerce_activated()) {
                    $shop_url = wc_get_page_permalink('shop');
                    if (is_woocommerce_activated() && is_shop()) {
                        $shop_status = 'active';
                    }
                }
            ?>
                <!-- Shop -->
                <li class="irantheme-navbar-mobile-list-item">
                    <a href="<?php echo esc_url_raw($shop_url); ?>" class="irantheme-navbar-mobile-list-item-link <?php echo esc_attr($shop_status); ?> <?php echo esc_attr($color_details_class); ?>">
                        <i class="irantheme-navbar-mobile-list-item-link-icon fa-light fa-shop"></i>
                        <span class="irantheme-navbar-mobile-list-item-link-title"><?php _e('فروشگاه'); ?></span>
                    </a>
                </li>
            <?php endif; ?>
            <?php
            if (is_woocommerce_activated() && is_shop()):
            ?>
                <!-- Filter -->
                <li class="irantheme-navbar-mobile-list-item">
                    <a href="#" class="irantheme-navbar-mobile-list-item-link irantheme-woocommerce-filtering-status <?php echo esc_attr($color_details_class); ?>">
                        <i class="irantheme-navbar-mobile-list-item-link-icon fa-light fa-filter"></i>
                        <span class="irantheme-navbar-mobile-list-item-link-title"><?php _e('فیلتر'); ?></span>
                    </a>
                </li>
            <?php endif; ?>
            <?php
            if (in_array('search', $mobile_options_array)):
            ?>
                <!-- Search -->
                <li class="irantheme-navbar-mobile-list-item">
                    <a href="#irantheme-search-box" class="irantheme-navbar-mobile-list-item-link irantheme-search-box-toggle <?php echo esc_attr($color_details_class); ?>">
                        <i class="irantheme-navbar-mobile-list-item-link-icon fa-light fa-magnifying-glass"></i>
                        <span class="irantheme-navbar-mobile-list-item-link-title"><?php _e('جستجو'); ?></span>
                    </a>
                </li>
            <?php endif; ?>
            <?php
            if (in_array('cart', $mobile_options_array)):
                // Check current page is cart
                $cart_status = '';
                $cart_url = '#';
                if (is_woocommerce_activated()) {
                    $cart_url = wc_get_page_permalink('cart');
                    if (is_cart()) {
                        $cart_status = 'active';
                    }
                }
            ?>
                <!-- Cart -->
                <li class="irantheme-navbar-mobile-list-item">
                    <a href="#" class="irantheme-navbar-mobile-list-item-link irantheme-woocommerce-cart-navigation-open <?php echo esc_attr($cart_status); ?> <?php echo esc_attr($color_details_class); ?>">
                        <i class="irantheme-navbar-mobile-list-item-link-icon fa-light fa-bag-shopping"></i>
                        <span class="irantheme-navbar-mobile-list-item-link-title"><?php _e('سبد خرید'); ?></span>
                        <?php
                        // Get shopping number cart count
                        irantheme_woocommerce_shopping_cart_count();
                        ?>
                    </a>
                </li>
            <?php endif; ?>
            <?php
            if (in_array('favorite', $mobile_options_array)):
            ?>
                <!-- Favorite -->
                <li class="irantheme-navbar-mobile-list-item">
                    <a href="#" class="irantheme-navbar-mobile-list-item-link irantheme-woocommerce-wishlist-navigation-open <?php echo esc_attr($color_details_class); ?>">
                        <i class="irantheme-navbar-mobile-list-item-link-icon fa-light fa-heart"></i>
                        <span class="irantheme-navbar-mobile-list-item-link-title"><?php _e('علاقه مندی'); ?></span>
                        <?php
                        // Get wishlist count
                        irantheme_woocommerce_wishlist_count();
                        ?>
                    </a>
                </li>
            <?php endif; ?>
            <?php
            if (in_array('myaccount', $mobile_options_array)):
                // Check current page is account
                $account_status = '';
                $account_url = '#';
                if (is_woocommerce_activated()) {
                    $account_url = wc_get_page_permalink('myaccount');
                    if (is_account_page()) {
                        $account_status = 'active';
                    }
                }
            ?>
                <!-- Profile -->
                <li class="irantheme-navbar-mobile-list-item">
                    <a href="<?php echo esc_url_raw($account_url); ?>" class="irantheme-navbar-mobile-list-item-link <?php echo esc_attr($account_status); ?> <?php echo esc_attr($color_details_class); ?>">
                        <i class="irantheme-navbar-mobile-list-item-link-icon fa-light fa-user"></i>
                        <span class="irantheme-navbar-mobile-list-item-link-title"><?php _e('پروفایل'); ?></span>
                    </a>
                </li>
            <?php endif; ?>
            <?php
            if (in_array('menu', $mobile_options_array)):
            ?>
                <!-- Menu -->
                <li class="irantheme-navbar-mobile-list-item">
                    <a href="#" class="irantheme-navbar-mobile-list-item-link irantheme-navbar-trigger <?php echo esc_attr($color_details_class); ?>">
                        <i class="irantheme-navbar-mobile-list-item-link-icon fa-light fa-bars"></i>
                        <span class="irantheme-navbar-mobile-list-item-link-title"><?php _e('منو'); ?></span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>

<?php endif; ?>