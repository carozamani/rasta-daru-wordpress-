<?php

use Irantheme_Dev\Irantheme_Common_Static;
use Irantheme_Dev\Irantheme_Header_Main;
use Irantheme_Dev\Irantheme_Page_Builder;
use Irantheme_Dev\Irantheme_Page_Builder_Ajax;

// Get page meta data json
$page_config_fields = Irantheme_Page_Builder_Ajax::get_page_settings(Irantheme_Page_Builder::get_current_page_id(), 'page_config', 'page_config_fields');
// Get page is customizable
$page_customizable = $page_config_fields['irantheme_page_customizable_checkbox'] ?? 'off';

// Check page builder is enabled
if ($page_customizable == 'on') :
    // Add main header and storeplus page builder
    get_header();
    Irantheme_Page_Builder::display_section();
    get_footer();
else:
    // Check current page is login page
    if (is_woocommerce_activated() && is_account_page() && !is_user_logged_in()):
        // Get header head
        Irantheme_Header_Main::header_head();
        // Get content
        if (have_posts()) {
            while (have_posts()) {
                the_post();
                the_content();
            }
        }
        get_footer();
    // Check current page is dashboard
    elseif (is_woocommerce_activated() && is_account_page() && is_user_logged_in()):
        get_header();
        // Get content
        if (have_posts()) {
            while (have_posts()) {
                the_post();
                the_content();
            }
        }
        // Footer
        Irantheme_Footer_Generator::display_footer();
        get_footer();
    // Check current page is checkout
    elseif (is_woocommerce_activated() && is_checkout()):
        get_header();
        // Get content
        if (have_posts()) {
            while (have_posts()) {
                the_post();
?>
                <div class="irantheme-wc-checkout">
                    <div class="irantheme-wc-checkout-wrapper">
                        <div class="irantheme-wc-checkout-title">
                            <ul>
                                <li class="shopping-cart done">
                                    <span href="<?php wc_get_cart_url(); ?>">
                                        <i class="iconsax" icon-name="tick-circle"></i>
                                        <strong>سبد خرید</strong>
                                    </span>
                                </li>
                                <li class="checkout <?php echo is_checkout() && is_wc_endpoint_url('order-received') ? 'done' : 'active'; ?>">
                                    <span href="<?php wc_get_checkout_url(); ?>">
                                        <?php if (is_checkout() && is_wc_endpoint_url('order-received')): ?>
                                            <i class="iconsax" icon-name="tick-circle"></i>
                                        <?php else: ?>
                                            <i class="fa-light fa-money-check-dollar-pen"></i>
                                        <?php endif; ?>
                                        <strong>جزییات صورتحساب</strong>
                                    </span>
                                </li>
                                <li class="success <?php echo is_checkout() && is_wc_endpoint_url('order-received') ? 'active' : ''; ?>">
                                    <span>
                                        <i class="fa-light fa-box-check"></i>
                                        <strong>ثبت سفارش</strong>
                                    </span>
                                </li>
                            </ul>
                        </div>
                        <div class="irantheme-wc-checkout-holder">
                            <?php the_content(); ?>
                        </div>
                    </div>
                </div>
            <?php
            }
        }
        // Footer
        Irantheme_Footer_Generator::display_footer();
        get_footer();
    // Check current page is cart
    elseif (is_woocommerce_activated() && is_cart()):
        get_header();
        // Get content
        if (have_posts()) {
            while (have_posts()) {
                the_post();
            ?>
                <div class="irantheme-wc-cart">
                    <div class="irantheme-wc-cart-wrapper">
                        <div class="irantheme-wc-cart-title">
                            <ul>
                                <li class="shopping-cart active">
                                    <span href="<?php wc_get_cart_url(); ?>">
                                        <i class="fa-light fa-basket-shopping"></i>
                                        <strong>سبد خرید</strong>
                                    </span>
                                </li>
                                <li class="checkout">
                                    <span href="<?php wc_get_checkout_url(); ?>">
                                        <i class="fa-light fa-money-check-dollar-pen"></i>
                                        <strong>جزییات صورتحساب</strong>
                                    </span>
                                </li>
                                <li class="success">
                                    <span>
                                        <i class="fa-light fa-box-check"></i>
                                        <strong>ثبت سفارش</strong>
                                    </span>
                                </li>
                            </ul>
                        </div>
                        <div class="irantheme-wc-cart-holder">
                            <?php the_content(); ?>
                        </div>
                    </div>
                </div>
        <?php
            }
        }
        // Footer
        Irantheme_Footer_Generator::display_footer();
        get_footer();
    else:
        get_header();
        $banner_type = get_option('irantheme_page_post_irregular_banner_type');
        $attachment_id = get_option('irantheme_page_irregular_banner_bg');
        $background_url = wp_get_attachment_image_url($attachment_id, 'full');
        if ($banner_type != 'none') {
            // Get template part of footer
            if ($banner_type == 'technological') {
                Irantheme_Archives::get_header_technological($background_url);
            } elseif ($banner_type == 'luxurious') {
                Irantheme_Archives::get_header_luxurious($background_url);
            } elseif ($banner_type == 'advanced') {
                Irantheme_Archives::get_header_advanced($background_url);
            }
        }

        // Get sidebar checkbox
        $sidebar_checkbox = get_option('irantheme_page_content_sidebar_checkbox');

        // Get sidebar position
        $sidebar_position = get_option('irantheme_page_content_sidebar_position');

        // Get sidebar select
        $sidebar_select = get_option('irantheme_page_content_sidebar_select');

        $content_class = 'col-lg-12';
        $sidebar_class = '';
        if ($sidebar_checkbox == 'on') {
            $content_class = 'col-lg-9';
            $sidebar_class = 'col-lg-3';
        }

        $row_class = '';
        if ($sidebar_position == 'left') {
            $row_class = 'flex-row-reverse';
        } else if ($sidebar_position == 'right') {
            $row_class = 'flex-row';
        }

        // Get base color
        $base_color = get_option('irantheme_blog_base_color_select');
        // Set base color class
        $base_color_class = Irantheme_Common_Static::get_base_color_class($base_color);
        ?>

        <!-- Blog content -->
        <section id="irantheme-single" class="irantheme-single">
            <div class="irantheme-container-inner">
                <div class="row <?php echo esc_attr($row_class); ?>">
                    <?php if ($sidebar_checkbox == 'on') : ?>
                        <!-- Sidebar -->
                        <div class="<?php echo esc_attr($sidebar_class); ?> irantheme-row-sidebar">
                            <div class="irantheme-sidebar-holder <?php echo esc_attr($base_color_class); ?>">
                                <?php dynamic_sidebar($sidebar_select); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <!-- Content -->
                    <div class="<?php echo esc_attr($content_class); ?> irantheme-row-content">
                        <?php
                        // Get template part of single
                        get_template_part('/template-parts/posts/single', get_option('irantheme_page_content_template'));
                        ?>
                    </div>
                </div>
            </div>
        </section>

<?php
        // Footer
        Irantheme_Footer_Generator::display_footer();
        get_footer();
    endif;
endif;
