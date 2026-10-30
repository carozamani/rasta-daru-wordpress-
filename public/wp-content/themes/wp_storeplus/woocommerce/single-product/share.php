<?php

/**
 * Single Product Share
 *
 * @see     https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @wc-version 3.5.0
 * @author	M-MALEKZADEH77
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}
function irantheme_custom_share_product()
{
	global $product;
	if ($product) {
		$product_permalink = $product->get_permalink();
		$product_permalink = urldecode($product_permalink);
		$product_permalink = esc_url($product_permalink);
	}
	$product_title = $product->get_title();
	$product_desc = $product->get_short_description();
	$site_title = get_bloginfo('name');
	$safe_product_permalink = rawurlencode($product_permalink);
	$safe_product_title     = rawurlencode($product_title);
	$safe_site_title        = rawurlencode($site_title);
	$safe_product_desc = rawurlencode(strip_tags($product_desc));

	$email_body =
		"سلام! این محصول رو بهت پیشنهاد می‌کنم:\n\n" .
		"لینک محصول: " . $product_permalink . "\n\n" .
		"توضیحات: " . strip_tags($product_desc);

	$safe_email_body = rawurlencode($email_body);
	$linkedin_share_url = 'https://www.linkedin.com/shareArticle?mini=true'
		. '&url='     . $safe_product_permalink
		. '&title='   . $safe_product_title
		. '&summary=' . $safe_product_desc
		. '&source='  . $safe_site_title;

	$telegram_share_url = 'https://t.me/share/url?url=' . $safe_product_permalink . '&text=' . $safe_product_title;

	$x_share_url = 'https://x.com/intent/tweet?url=' . $safe_product_permalink . '&text=' . $safe_product_title;

	$whatsapp_share_url = 'https://api.whatsapp.com/send?text=' . $safe_product_permalink . '%20' . $safe_product_title;

	$pinterest_share_url = 'http://pinterest.com/pin/create/button/?url=' . $safe_product_permalink . '&description=' . $safe_product_title;

	$facebook_share_url = 'https://www.facebook.com/sharer/sharer.php?u=' . $safe_product_permalink;

	$email_share_url = 'mailto:?subject=' . $safe_product_title . '&body=' . $safe_email_body;
	$share_checkbox = get_option('irantheme_single_product_share_checkbox');
	$share_tools = explode(',', get_option('irantheme_single_product_share_tools'));
?>
	<?php if ($share_checkbox == 'on') : ?>
		<div class="product-share-tools-modal irantheme-popup-modal" style="display: none;">
			<div class="product-share-tools-modal-wrapper irantheme-popup-modal-wrapper">
				<div class="topbar">
					<h4 class="heading"><?php _e('اشتراک گذاری'); ?></h4>
					<span class="close"><i class="fa-light fa-close"></i></span>
				</div>
				<div class="irantheme-single-product-divider"></div>
				<div class="share-content">
					<p class="share-content-product-desc"><?php _e('این کالا را با دوستان خود به اشتراک بگذارید!'); ?></php>
					</p>
					<div class="share-content-product-details">
						<div class="share-content-product-details-image">
							<img src="<?php echo esc_attr(wp_get_attachment_url(get_post_thumbnail_id($product->get_id()), 'medium')); ?>" alt="<?php _e('تصویر محصول'); ?>" srcset="<?php echo esc_attr(wp_get_attachment_image_srcset(get_post_thumbnail_id($product->get_id()), 'full')); ?>" sizes="<?php echo wp_get_attachment_image_sizes(get_post_thumbnail_id($product->get_id()), 'full') ?>">
						</div>
						<div class="share-content-product-details-name">
							<?php the_title('<p class="share-content-product-details-name-title">', '</p>'); ?>
							<?php if ($product_desc): ?>
								<div class="share-content-product-details-name-desc">
									<?php echo $product_desc; ?>
								</div>
							<?php endif; ?>
						</div>
					</div>
					<?php if ($share_tools != array('')): ?>
						<div class="share-content-product-link">
							<ul class="share-content-product-link-social">
								<?php if (in_array('telegram', $share_tools)) : ?>
									<li class="share-content-product-link-social-item irantheme-product-share-telegram" title="اشتراک گذاری در تلگرام"><a rel="noopener" onClick="MyWindow=window.open('<?php echo esc_attr($telegram_share_url); ?>','MyWindow','width=500,height=400'); return false;"><i class="fa-brands fa-telegram"></i><span class='share-content-product-link-social-item-title'>تلگرام</span></a></li>
								<?php endif; ?>
								<?php if (in_array('x-twitter', $share_tools)) : ?>
									<li class="share-content-product-link-social-item irantheme-product-share-x" title="اشتراک گذاری در ایکس (توئیتر)"><a rel="noopener" onClick="MyWindow=window.open('<?php echo esc_attr($x_share_url); ?>','MyWindow','width=500,height=400'); return false;"><i class="fa-brands fa-x-twitter"></i><span class='share-content-product-link-social-item-title'>ایکس (توئیتر)</span></a></li>
								<?php endif; ?>
								<?php if (in_array('whatsapp', $share_tools)) : ?>
									<li class="share-content-product-link-social-item irantheme-product-share-whatsapp" title="اشتراک گذاری در واتس اپ"><a rel="noopener" onClick="MyWindow=window.open('<?php echo esc_attr($whatsapp_share_url); ?>','MyWindow','width=500,height=400'); return false;"><i class="fa-brands fa-whatsapp"></i><span class='share-content-product-link-social-item-title'>واتس آپ</span></a></li>
								<?php endif; ?>
								<?php if (in_array('pinterest', $share_tools)) : ?>
									<li class="share-content-product-link-social-item irantheme-product-share-pinterest" title="اشتراک گذاری در پینترست"><a rel="noopener" onClick="MyWindow=window.open('<?php echo esc_attr($pinterest_share_url); ?>','MyWindow','width=500,height=400'); return false;"><i class="fa-brands fa-pinterest"></i><span class='share-content-product-link-social-item-title'>پینترست</span></a></li>
								<?php endif; ?>
								<?php if (in_array('facebook', $share_tools)) : ?>
									<li class="share-content-product-link-social-item irantheme-product-share-facebook" title="اشتراک گذاری در فیس بوک"><a rel="noopener" onClick="MyWindow=window.open('<?php echo esc_attr($facebook_share_url); ?>','MyWindow','width=500,height=400'); return false;"><i class="fa-brands fa-facebook-f"></i><span class='share-content-product-link-social-item-title'>فیس بوک</span></a></li>
								<?php endif; ?>
								<?php if (in_array('linkedin', $share_tools)) : ?>
									<li class="share-content-product-link-social-item irantheme-product-share-linkedin" title="اشتراک گذاری در لینکدین"><a rel="noopener" onClick="MyWindow=window.open('<?php echo esc_attr($linkedin_share_url); ?>','MyWindow','width=500,height=400'); return false;"><i class="fa-brands fa-linkedin-in"></i><span class='share-content-product-link-social-item-title'>لینکدین</span></a></li>
								<?php endif; ?>
								<?php if (in_array('email', $share_tools)) : ?>
									<li class="share-content-product-link-social-item irantheme-product-share-email" title="اشتراک گذاری با ایمیل"><a rel="noopener" onClick="MyWindow=window.open('<?php echo esc_attr($email_share_url); ?>','MyWindow','width=500,height=400'); return false;"><i class="fa-light fa-envelope"></i><span class='share-content-product-link-social-item-title'>ایمیل</span></a></li>
								<?php endif; ?>
							</ul>
							<?php if (in_array('link', $share_tools)) : ?>
								<div class="share-content-product-link-copy">
									<input id="product_share_link_input" class="share-content-product-link-copy-input" value="<?php echo $product_permalink; ?>" readonly>
									<div class="share-content-product-link-copy-item irantheme-product-share-link" title="کپی لینک">
										<a rel="noopener" data-product-permalink="<?php echo $product_permalink; ?>">
											<i class="fa-light fa-copy"></i>
											<span class='share-content-product-link-social-item-title'>اشتراک گذاری</span>
										</a>
									</div>
								</div>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>
<?php
}
add_action('woocommerce_share', 'irantheme_custom_share_product');
do_action('woocommerce_share'); // Sharing plugins can hook into here.

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
