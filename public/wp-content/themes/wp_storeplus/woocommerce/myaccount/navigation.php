<?php

/**
 * My Account navigation
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/navigation.php.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @wc-version 9.3.0
 * @author M-MALEKZADEH77
 */

if (! defined('ABSPATH')) {
	exit;
}

do_action('woocommerce_before_account_navigation');
?>
<div class="irantheme-woocommerce-myaccount-head-navigation-holder">
	<!-- Profile -->
	<div class="irantheme-woocommerce-profile">
		<?php
		// Get user id
		$user_id = get_current_user_id();
		// Get avatar url user
		$avatar_image_url = irantheme_get_user_avatar_url($user_id);

		// Current user
		$current_user = get_user_by('id', $user_id);
		?>
		<!-- Avatar -->
		<div class="irantheme-woocommerce-profile-avatar">
			<img src="<?php echo esc_url_raw($avatar_image_url); ?>" alt="<?php echo esc_attr('آواتار کاربر'); ?>">
		</div>
		<!-- User info -->
		<div class="irantheme-woocommerce-profile-content">
			<div class="irantheme-woocommerce-profile-content-title">
				<h4 class="irantheme-woocommerce-profile-content-title-heading"><?php echo esc_html($current_user->display_name); ?></h4>
				<span class="irantheme-woocommerce-profile-content-title-role"><?php _e(irantheme_get_user_role($user_id)); ?></span>
			</div>
			<p class="irantheme-woocommerce-profile-content-email"><?php echo esc_html($current_user->user_email); ?></p>
		</div>
	</div>
	<button class="irantheme-woocommerce-myaccount-navigation-mobile-trigger" title="منوی پیشخوان">
		<span class="label">منوی پیشخوان</span>
		<i class="fa-regular fa-bars-staggered"></i>
	</button>
</div>
<!-- WC navigation dashboard -->
<nav class="woocommerce-MyAccount-navigation irantheme-woocommerce-myaccount-navigation-holder" aria-label="<?php esc_html_e('Account pages', 'woocommerce'); ?>">
	<ul class="irantheme-woocommerce-myaccount-navigation">
		<?php foreach (wc_get_account_menu_items() as $endpoint => $label) : ?>
			<li class="<?php echo wc_get_account_menu_item_classes($endpoint); ?> irantheme-woocommerce-myaccount-navigation-item">
				<a class="irantheme-woocommerce-myaccount-navigation-item-link" href="<?php echo esc_url(wc_get_account_endpoint_url($endpoint)); ?>" <?php echo wc_is_current_account_menu_item($endpoint) ? 'aria-current="page"' : ''; ?>>
					<span class="irantheme-woocommerce-myaccount-navigation-item-link-text"><?php echo esc_html($label); ?></span>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</nav>
<?php do_action('woocommerce_after_account_navigation'); ?>