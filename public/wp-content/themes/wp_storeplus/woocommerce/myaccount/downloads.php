<?php

/**
 * Downloads
 *
 * Shows downloads on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/downloads.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @wc-version 7.8.0
 * @author M-MALEKZADEH77
 */

if (! defined('ABSPATH')) {
	exit;
}

$downloads     = WC()->customer->get_downloadable_products();
$has_downloads = (bool) $downloads;

do_action('woocommerce_before_account_downloads', $has_downloads); ?>
<h2 class="irantheme-myaccount-content-title"><i class="fa-light fa-cloud-arrow-down"></i>دانلود های من</h2>
<?php if ($has_downloads) : ?>

	<?php do_action('woocommerce_before_available_downloads'); ?>

	<?php do_action('woocommerce_available_downloads', $downloads); ?>

	<?php do_action('woocommerce_after_available_downloads'); ?>

<?php else :
?>
	<div class="irantheme-myaccount-orders-empty">
		<div class="irantheme-myaccount-content-empty">
			<i class="fa-light fa-clipboard-list irantheme-myaccount-content-empty-icon"></i>
			<h5 class="irantheme-myaccount-content-empty-title"><?php echo esc_html__('No downloads available yet.', 'woocommerce'); ?></h5>

			<a class="irantheme-btn-void irantheme-myaccount-content-empty-btn" href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>">
				<?php esc_html_e('مشاهده فروشگاه', IRANTHEME_TEXTDOMAIN); ?>
				<i class="fa-light fa-angle-left icon"></i>
			</a>
		</div>
	</div>

<?php endif; ?>

<?php do_action('woocommerce_after_account_downloads', $has_downloads); ?>