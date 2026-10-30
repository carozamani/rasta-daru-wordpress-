<?php

/**
 * My Account - Favorites Tab Template using WP_Query
 *
 * This template displays the user's wishlist items using a standard WordPress loop.
 *
 * @author M-MALEKZADEH77
 * @since 1.0.2
 * @version 1.0.0
 */

use Irantheme_Dev\Irantheme_Color_Static;
use Irantheme_Dev\Irantheme_Section_Woocommerce;

defined('ABSPATH') || exit;

// Get current user and wishlist IDs from meta
$current_user = wp_get_current_user();
$wishlist_ids = get_user_meta($current_user->ID, 'wishlist', true);
// Initialize as empty array if wishlist is empty
$wishlist_array = !empty($wishlist_ids) ? explode(',', $wishlist_ids) : array();

$empty_class = 'd-none';
if (empty($wishlist_array) || !is_array($wishlist_array)) {
    $empty_class = '';
}
?>
<div class="irantheme-myaccount-favorites">
    <?php


    /**
     * Define WP_Query Arguments
     * @author M-MALEKZADEH77
     */
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => -1, // You can change this for pagination (e.g. 12)
        'post__in'       => $wishlist_array,
        'orderby'        => 'post__in', // Maintain the order of IDs in meta
    );

    $products_post = new WP_Query($args);

    if ($products_post->have_posts()) : ?>

        <div class="irantheme-myaccount-favorites-wrapper">
            <div class="irantheme-myaccount-favorites-heading">
                <h5 class="title irantheme-myaccount-content-title"><i class="iconsax" icon-name="heart"></i><?php esc_html_e('علاقه مندی ها', IRANTHEME_TEXTDOMAIN); ?></h5>
            </div>
            <div class="irantheme-myaccount-favorites-content">
                <div class="irantheme-myaccount-content-empty <?php echo $empty_class; ?>">
                    <i class="fa-light fa-clipboard-list irantheme-myaccount-content-empty-icon"></i>
                    <h5 class="irantheme-myaccount-content-empty-title"><?php esc_html_e('لیست علاقه مندی های شما خالی می باشد.', IRANTHEME_TEXTDOMAIN); ?></h5>

                    <a class="irantheme-btn-void irantheme-myaccount-content-empty-btn" href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>">
                        <?php esc_html_e('مشاهده فروشگاه', IRANTHEME_TEXTDOMAIN); ?>
                        <i class="fa-light fa-angle-left icon"></i>
                    </a>
                </div>
                <div class="irantheme-myaccount-favorites-content-list row">

                </div>
            </div>
        </div>

        <!-- Preloader -->
        <div class="irantheme-preloader-abs white">
            <i class="irantheme-animate-rotate irantheme-preloader-loader thin"></i>
        </div>
    <?php endif; ?>
</div>