<?php

/**
 * Lost password form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-lost-password.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @wc-version 9.2.0
 * @author M-MALEKZADEH77 
 * */

use Irantheme_Dev\Irantheme_Common_Static;
use Irantheme_Dev\Irantheme_Section_Utils;

defined('ABSPATH') || exit;

// Display mode option ------------------------------------------------------------
$display_mode = get_option('irantheme_wc_login_display_mode');
$display_mode_class = '';
if ($display_mode == 'dual-col-box-form') {
	$display_mode_class = 'irantheme-myaccount-content-dual-col-box-form';
} else if ($display_mode == 'split-form') {
	$display_mode_class = 'irantheme-myaccount-content-split-form';
} else if ($display_mode == 'floating-box-form') {
	$display_mode_class = 'irantheme-myaccount-content-floating-box-form align-items-center';
}


// Background options ------------------------------------------------------------
$background_image_data = get_option('irantheme_wc_login_background_image');
$background_color_data = get_option('irantheme_wc_login_background_color');
$background_color_css = '';
$background_image_css = '';
if ($display_mode == 'floating-box-form' || $display_mode == 'dual-col-box-form') {
	$background_color_css = Irantheme_Section_Utils::get_background_color_css($background_color_data);
	$background_image_css = Irantheme_Section_Utils::get_background_image_css($background_image_data);
}

$image_col_slider = get_option('irantheme_wc_login_image_col_slider_checkbox');
$image_col_mono_img = get_option('irantheme_wc_login_image_col_mono_img');
$image_col_mono_img_url = wp_get_attachment_image_url($image_col_mono_img, 'full');
$image_col_slider_img = get_option('irantheme_wc_login_image_col_slider_img');
$slider_images_array = explode(',', $image_col_slider_img);

$background_split_image_data = get_option('irantheme_wc_login_split_background_image');
$background_split_url = wp_get_attachment_image_url($background_split_image_data, 'full');

// Mask background ---------------------------------------------------------------
$mask_checkbox = get_option('irantheme_wc_login_background_image_mask_checkbox');
$mask_range = get_option('irantheme_wc_login_background_image_mask_range');
$mask_color = get_option('irantheme_wc_login_background_mask_color_select');

// Password checker option -------------------------------------------------------
$password_checker = get_option('irantheme_wc_login_password_strength_checkbox');
$password_checker_class = '';
if ($password_checker == 'on') {
	$password_checker_class = 'irantheme-registration-pwd-check';
}

// Copyright option --------------------------------------------------------------
$copyright_text = get_option('irantheme_wc_login_text_copyright');

// Welcome text option --------------------------------------------------------------
$signin_text = get_option('irantheme_wc_login_text_signin_desc');
$signup_text = get_option('irantheme_wc_login_text_signup_desc');

// Base color option -------------------------------------------------------------
$base_color = get_option('irantheme_wc_login_base_color_select');
// Set base color class
$base_color_class = Irantheme_Common_Static::get_base_color_class($base_color);
// Set btn submit class
$btn_submit_color = '';
if ($base_color == 'white') {
	$btn_submit_color = 'irantheme-btn-dark';
} elseif ($base_color == 'dark') {
	$btn_submit_color = 'irantheme-btn-white';
}

// Display mode extra option ------------------------------------------------------------
$floating_box_form_align = get_option('irantheme_wc_login_display_floating_box_form_align');
$dual_col_box_form_align = get_option('irantheme_wc_login_display_dual_col_box_form_align');
$split_form_align = get_option('irantheme_wc_login_display_split_form_align');
$display_mode_align_class = '';
// Get border radius range
$border_radius_range = get_option('irantheme_wc_login_border_radius_range', 2);
$border_radius_css = Irantheme_Section_Utils::get_border_radius_css($border_radius_range);
$semi_border_radius_css = '';
if ($border_radius_range == 0) {
	$semi_border_radius_css = Irantheme_Section_Utils::get_border_radius_css(0);
} else {
	$semi_border_radius_css = Irantheme_Section_Utils::get_border_radius_css($border_radius_range / 2);
}
$col_class = 'col-lg-5 col-md-6 col-sm-12';
switch ($display_mode) {
	case 'floating-box-form':
		if ($floating_box_form_align == 'right') {
			$display_mode_align_class = 'style-align-right';
		} else if ($floating_box_form_align == 'left') {
			$display_mode_align_class = 'style-align-left';
		} else if ($floating_box_form_align == 'center') {
			$display_mode_align_class = 'style-align-center';
		}
		break;
	case 'dual-col-box-form':
		$col_class = 'col-xl-8 col-lg-9 col-md-10 col-sm-12';
		if ($dual_col_box_form_align == 'right') {
			$display_mode_align_class = 'style-align-right';
		} else if ($dual_col_box_form_align == 'left') {
			$display_mode_align_class = 'style-align-left';
		}
		break;
	case 'split-form':
		if ($split_form_align == 'right') {
			$display_mode_align_class = 'style-align-right';
		} else if ($split_form_align == 'left') {
			$display_mode_align_class = 'style-align-left';
		}
		break;

	default:
		# code...
		break;
}

do_action('woocommerce_before_lost_password_form');
?>


<div class="irantheme-myaccount-login-pages <?php echo esc_attr($base_color_class); ?>" data-type="<?php echo esc_attr($login_type_class); ?>">
	<div class="irantheme-login-holder row p-0 m-0 <?php echo esc_attr($display_mode_class); ?> <?php echo esc_attr($display_mode_align_class); ?>" style='<?php echo $background_color_css; ?><?php echo $background_image_css; ?>'>
		<?php if (($display_mode == 'floating-box-form' || $display_mode == 'dual-col-box-form') && $background_image_css) {
			Irantheme_Section_Utils::get_background_mask_tag_html($mask_checkbox, $mask_color, $mask_range);
		} ?>
		<?php if ($display_mode == 'floating-box-form' || $display_mode == 'dual-col-box-form'): ?>
			<a href="<?php echo esc_url(home_url()); ?>" class="irantheme-login-return-home-button <?php echo $display_mode == 'dual-col-box-form' ? 'dual-col-box-form' : ''; ?>" title="بازگشت به صفحه اصلی">
				<span>بازگشت به خانه</span>
				<i class="iconsax" icon-name="arrow-left"></i>
			</a>
		<?php endif; ?>
		<div class="irantheme-login-col p-md-0 <?php echo $col_class; ?> irantheme-content-on-mask">
			<?php if ($display_mode == 'split-form'): ?>
				<a href="<?php echo esc_url(home_url()); ?>" class="irantheme-login-return-home-button" title="بازگشت به صفحه اصلی">
					<span>بازگشت به خانه</span>
					<i class="iconsax" icon-name="arrow-left"></i>
				</a>
			<?php endif; ?>
			<?php if ($display_mode == 'dual-col-box-form'): ?>
				<div class="irantheme-login-content-holder" style="<?php echo $border_radius_css; ?>">
				<?php endif; ?>
				<div class="irantheme-myaccount-login irantheme-lost-password-content irantheme-login-content-wrapper" style="<?php echo $border_radius_css; ?>">
					<div class="irantheme-myaccount-login-title irantheme-login-content-title">
						<h2 class="irantheme-myaccount-login-title-heading irantheme-login-content-title-heading">بازگردانی گذرواژه</h2>
						<p class="irantheme-myaccount-login-title-desc irantheme-login-content-title-desc"><?php echo esc_attr($lost_pwd_text); ?></p>
					</div>
					<form method="post" class="irantheme-myaccount-login-form irantheme-lost-password-content-from woocommerce-ResetPassword lost_reset_password">

						<p class="irantheme-myaccount-login-notice irantheme-lost-password-content-form-notice"><?php echo apply_filters('woocommerce_lost_password_message', esc_html__('Lost your password? Please enter your username or email address. You will receive a link to create a new password via email.', 'woocommerce')); ?></p>

						<p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
							<label class="irantheme-login-label" for="user_login"><?php esc_html_e('Username or email', 'woocommerce'); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e('Required', 'woocommerce'); ?></span></label>
							<span class="irantheme-login-input-icon-holder">
								<input class="irantheme-login-input woocommerce-Input woocommerce-Input--text input-text" type="text" name="user_login" id="user_login" required autocomplete="username" />
								<span class="irantheme-login-input-icon-username irantheme-login-input-icon"></span>
							</span>
						</p>

						<div class="clear"></div>

						<?php do_action('woocommerce_lostpassword_form'); ?>

						<p class="woocommerce-form-row form-row">
							<input type="hidden" name="wc_reset_password" value="true" />
							<button type="submit" class="irantheme-myaccount-login-form-submit irantheme-btn <?php echo esc_attr($btn_submit_color); ?> irantheme-btn-medium woocommerce-Button button<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?>" value="<?php esc_attr_e('Reset password', 'woocommerce'); ?>"><?php esc_html_e('Reset password', 'woocommerce'); ?></button>
						</p>

						<?php wp_nonce_field('lost_password', 'woocommerce-lost-password-nonce'); ?>

					</form>
					<a class="irantheme-myaccount-login-back-to-url" href="<?php echo get_permalink(get_option('woocommerce_myaccount_page_id')); ?>" title="<?php _e('My Account', ''); ?>">بازگشت به صفحه ورود<i class="fa-light fa-arrow-left"></i></a>
					<div class="irantheme-myaccount-login-copyright irantheme-login-content-copyright">
						<div class="irantheme-myaccount-login-copyright-text irantheme-login-content-copyright-text"><?php echo $copyright_text; ?></div>
					</div>
				</div>
				<?php if ($display_mode == 'dual-col-box-form'): ?>
					<div class="irantheme-login-image <?php echo $image_col_slider == 'on' ? 'irantheme-login-image-carousel swiper' : ''; ?>" style="<?php echo $semi_border_radius_css; ?>">
						<a href="<?php echo esc_url(home_url()); ?>" class="irantheme-login-return-home-button" title="بازگشت به صفحه اصلی">
							<span>بازگشت به خانه</span>
							<i class="iconsax" icon-name="arrow-left"></i>
						</a>
						<?php if ($image_col_slider == 'off'): ?>
							<img src="<?php echo esc_attr($image_col_mono_img_url); ?>" srcset="<?php echo esc_attr(wp_get_attachment_image_srcset(attachment_url_to_postid($image_col_mono_img_url), 'full')); ?>" sizes="<?php echo wp_get_attachment_image_sizes(attachment_url_to_postid($image_col_mono_img_url), 'full') ?>" alt="<?php _e('ستون محتوای تصویری') ?>">
						<?php elseif ($image_col_slider == 'on'): ?>
							<div class="swiper-wrapper">
								<?php foreach ($slider_images_array as $media_url) :
									if ($media_url == '') {
										continue;
									}
								?>
									<div class="swiper-slide">
										<img src="<?php echo esc_attr($media_url) ?>" alt="<?php _e('ستون محتوای تصویری') ?>" srcset="<?php echo esc_attr(wp_get_attachment_image_srcset(attachment_url_to_postid($media_url), 'full')); ?>" sizes="<?php echo wp_get_attachment_image_sizes(attachment_url_to_postid($media_url), 'full') ?>">.
									</div>
								<?php endforeach; ?>
							</div>
							<?php
							// Slider pagination & arrow
							Irantheme_Section_Utils::get_slider_arrow_pagination(true, true, true, 'white', $border_radius_range, 'bottom', 'center');
							?>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
		<?php if ($display_mode == 'split-form'): ?>
			<div class="irantheme-image-col p-0 col-lg-7 col-md-6">
				<a href="<?php echo esc_url(home_url()); ?>" class="irantheme-login-return-home-button" title="بازگشت به صفحه اصلی">
					<span>بازگشت به خانه</span>
					<i class="iconsax" icon-name="arrow-left"></i>
				</a>
				<?php if ($background_split_url): ?>
					<?php Irantheme_Section_Utils::get_background_mask_tag_html($mask_checkbox, $mask_color, $mask_range); ?>
					<img class="irantheme-login-img" src="<?php echo esc_attr($background_split_url); ?>">
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</div>
<?php
do_action('woocommerce_after_lost_password_form');
