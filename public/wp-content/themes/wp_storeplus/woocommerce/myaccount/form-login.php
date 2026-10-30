<?php

/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @wc-version 9.2.0
 * @author M-MALEKZADEH77 
 **/

use Irantheme_Dev\Irantheme_Common_Static;
use Irantheme_Dev\Irantheme_Section_Utils;

if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}
// OTP login options ------------------------------------------------------------
$otp_login_status = get_option('irantheme_sms_panel_provider');
$otp_login_checkbox = 'off';
$login_type_class = 'traditional';
if ($otp_login_status != 'off') {
	$login_type_class = 'otp';
	$otp_login_checkbox = 'on';
}

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

// OTP number code digits
$number_code_digits = get_option('irantheme_otp_login_number_code_digits', 6);
do_action('woocommerce_before_customer_login_form'); ?>

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
				<?php if ($otp_login_checkbox == 'on'): ?>
					<div class="irantheme-myaccount-login irantheme-login-otp-content irantheme-login-content-wrapper" style="<?php echo $border_radius_css; ?>">
						<div class="irantheme-myaccount-login-title irantheme-login-otp-content-title">
							<h2 class="irantheme-myaccount-login-title-heading irantheme-login-otp-content-title-heading">ورود</h2>
							<p class="irantheme-myaccount-login-title-desc irantheme-login-otp-content-title-desc"><?php echo esc_attr($signin_text); ?></p>
						</div>
						<form class="irantheme-myaccount-login-form woocommerce-form woocommerce-form-login login" id="irantheme_otp_login_form" method="post">
							<div id="otp_step_phone">
								<p class="irantheme-login-otp-content-form-mobile-number woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
									<label class="irantheme-login-label irantheme-login-otp-content-form-mobile-number-label form-label" for="otp_phone_number_woo"><?php echo esc_html('شماره موبایل'); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e('Required', 'woocommerce'); ?></span></label>
									<span class="irantheme-login-input-icon-holder">
										<span class="irantheme-login-input-icon-mobile-number irantheme-login-input-icon"></span>
										<input type="text" class="irantheme-login-input woocommerce-Input woocommerce-Input--text input-text" name="otp_phone_number_woo" id="otp_phone_number_woo" placeholder="مثال:9123456789" maxlength='11' required aria-required="true" />
										<span class="irantheme-login-otp-input irantheme-login-input-icon">+98</span>
									</span>
								</p>
								<button type="submit" id="request_otp_woo" class="irantheme-myaccount-login-form-submit irantheme-login-otp-content-form-submit irantheme-btn <?php echo esc_attr($btn_submit_color); ?> irantheme-btn-medium woocommerce-button button woocommerce-form-login__submit" name="request_otp_woo" value="<?php echo esc_attr('ورود | ثبت نام'); ?>">
									<?php echo esc_html('ورود | ثبت نام'); ?>
								</button>
								<div id="otp-phone-message-woo" class="woocommerce-message"></div>
							</div>

							<div id="otp_step_verify" style="display: none;">
								<p class="irantheme-login-otp-content-form-verify woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
									<label class="irantheme-login-label irantheme-login-otp-content-form-verify-label form-label" for="otp_phone_number_woo"><?php echo esc_html('لطفا کد اعتبارسنجی ارسال شده به شماره mobile_number را در کادر زیر وارد نمایید.'); ?>&nbsp;</label>
									<span class="irantheme-login-input-icon-holder">
										<input type="text" class="irantheme-login-input woocommerce-Input woocommerce-Input--text input-text" name="otp_code_woo" id="otp_code_woo" maxlength="<?php echo (int)$number_code_digits; ?>" placeholder="<?php echo str_repeat('-', esc_attr((int)$number_code_digits)); ?>" />
										<input type="hidden" name="otp_user_id_woo" id="otp_user_id_woo" value="" />
									</span>
								</p>
								<div class="irantheme-myaccount-login-action-holder irantheme-login-otp-content-action-holder">
									<a id="edit_phone_number" class="irantheme-myaccount-login-edit-phone-number-text irantheme-login-otp-content-edit-phone-number-text"><i class="fa-light fa-pen-to-square"></i>ویرایش شماره تلفن</a>
									<a id="resend_otp_woo" class="irantheme-myaccount-login-resend-code-text irantheme-login-otp-content-resend-code-text" data-value="<?php echo esc_html('ارسال مجدد کد'); ?>"><?php echo esc_html('ارسال مجدد کد'); ?></a>
								</div>
								<button type="submit" id="verify_otp_woo" class="irantheme-myaccount-login-form-submit irantheme-login-otp-content-form-submit irantheme-btn <?php echo esc_attr($btn_submit_color); ?> irantheme-btn-medium woocommerce-button button woocommerce-form-login__submit" name="verify_otp_woo" value="<?php echo esc_attr('ورود'); ?>">
									<?php echo esc_html('ورود'); ?>
								</button>
								<div id="otp-verify-message-woo" class="woocommerce-login-message"></div>
							</div>
							<?php if ('yes' === get_option('woocommerce_enable_myaccount_registration')) : ?>
								<div class="irantheme-myaccount-login-signup irantheme-login-otp-content-signup">
									<p class="irantheme-myaccount-login-signup-text irantheme-login-otp-content-signup-text">حساب کاربری ندارید؟ <a class="irantheme-myaccount-login-signup-text-link irantheme-login-otp-content-signup-text-link" id="irantheme_myaccount_registration_toggle_otp">ثبت نام</a></p>
								</div>
							<?php endif; ?>
							<p id="irantheme_show_traditional_login" class="irantheme-myaccount-login-traditional-login-text irantheme-login-otp-content-traditional-login-text">
								<?php echo esc_html('ورود با رمز عبور'); ?><i class="fa-light fa-arrow-left"></i>
							</p>
							<div class="irantheme-myaccount-login-copyright irantheme-login-content-copyright">
								<div class="irantheme-myaccount-login-copyright-text irantheme-login-content-copyright-text"><?php echo $copyright_text; ?></div>
							</div>
						</form>
					</div>
				<?php endif; ?>
				<div class="irantheme-myaccount-login irantheme-login-content irantheme-login-content-wrapper" style="<?php echo $otp_login_checkbox == 'on' ? 'display:none;' : ''; ?><?php echo $border_radius_css; ?>">
					<div class="irantheme-myaccount-login-title irantheme-login-content-title">
						<h2 class="irantheme-myaccount-login-title-heading irantheme-login-content-title-heading">ورود</h2>
						<p class="irantheme-myaccount-login-title-desc irantheme-login-content-title-desc"><?php echo esc_attr($signin_text); ?></p>
					</div>
					<form class="irantheme-myaccount-login-form irantheme-login-content-form woocommerce-form woocommerce-form-login login" id="irantheme_traditional_login_form" method="post">

						<?php do_action('woocommerce_login_form_start'); ?>

						<p class="irantheme-login-content-form-username woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
							<label class="irantheme-login-label irantheme-login-content-form-username-label form-label" for="username"><?php esc_html_e('Username or email address', 'woocommerce'); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e('Required', 'woocommerce'); ?></span></label>
							<span class="irantheme-login-input-icon-holder">
								<span class="irantheme-login-input-icon-username irantheme-login-input-icon"></span>
								<input type="text" class="irantheme-login-input woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" value="<?php echo (! empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" required aria-required="true" />
							</span>
						</p>
						<p class="irantheme-login-content-form-password woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
							<label class="irantheme-login-label irantheme-login-content-form-password-label form-label" for="password"><?php esc_html_e('Password', 'woocommerce'); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e('Required', 'woocommerce'); ?></span></label>
							<span class="irantheme-login-input-icon-holder">
								<span class="irantheme-login-input-icon-password irantheme-login-input-icon"></span>
								<input class="irantheme-login-input woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" required aria-required="true" />
							</span>
						</p>

						<?php do_action('woocommerce_login_form'); ?>

						<p class="form-row">
						<div class="irantheme-myaccount-login-form-remember-forget-holder">
							<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
								<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span><?php esc_html_e('Remember me', 'woocommerce'); ?></span>
							</label>
							<p class="woocommerce-LostPassword lost_password">
								<a href="<?php echo esc_url(wp_lostpassword_url()); ?>"><?php esc_html_e('Lost your password?', 'woocommerce'); ?></a>
							</p>
						</div>
						<?php wp_nonce_field('woocommerce-login', 'woocommerce-login-nonce'); ?>
						<button type="submit" class="irantheme-myaccount-login-form-submit irantheme-login-content-form-submit irantheme-btn <?php echo esc_attr($btn_submit_color); ?> irantheme-btn-medium woocommerce-button button woocommerce-form-login__submit<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?>" name="login" value="<?php esc_attr_e('Log in', 'woocommerce'); ?>"><?php esc_html_e('Log in', 'woocommerce'); ?></button>
						</p>

						<?php do_action('woocommerce_login_form_end'); ?>

					</form>
					<?php if ('yes' === get_option('woocommerce_enable_myaccount_registration')) : ?>
						<div class="irantheme-myaccount-login-signup irantheme-login-content-signup">
							<p class="irantheme-myaccount-login-signup-text irantheme-login-content-signup-text">حساب کاربری ندارید؟ <a class="irantheme-myaccount-login-signup-text-link irantheme-login-content-signup-text-link" id="irantheme_myaccount_registration_toggle">ثبت نام</a></p>
						</div>
					<?php endif; ?>
					<?php if ($otp_login_checkbox == 'on'): ?>
						<p id="irantheme_show_otp_login" class="irantheme-myaccount-login-otp-login-text irantheme-login-content-otp-login-text">
							<?php echo esc_html('ورود با رمز یکبار مصرف'); ?><i class="fa-light fa-arrow-left"></i>
						</p>
					<?php endif; ?>
					<div class="irantheme-myaccount-login-copyright irantheme-login-content-copyright">
						<div class="irantheme-myaccount-login-copyright-text irantheme-login-content-copyright-text"><?php echo $copyright_text; ?></div>
					</div>
				</div>
				<div class="irantheme-myaccount-login irantheme-register-content irantheme-login-content-wrapper" style="display:none;<?php echo $border_radius_css; ?>">
					<div class="irantheme-myaccount-login-title irantheme-register-content-title">
						<h2 class="irantheme-myaccount-login-title-heading irantheme-register-content-title-heading">ثبت نام</h2>
						<p class="irantheme-myaccount-login-title-desc irantheme-register-content-title-desc"><?php echo esc_attr($signup_text); ?></p>
					</div>
					<form method="post" data-least="<?php echo esc_attr($least_pwd_char); ?>" class="irantheme-myaccount-login-form irantheme-register-content-form woocommerce-form woocommerce-form-register register <?php echo esc_attr($password_checker_class); ?>" <?php do_action('woocommerce_register_form_tag'); ?>>

						<?php do_action('woocommerce_register_form_start'); ?>

						<?php if ('no' === get_option('woocommerce_registration_generate_username')) : ?>
							<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
								<label class="irantheme-login-label irantheme-register-content-form-username-label form-label" for="reg_username"><?php esc_html_e('Username', 'woocommerce'); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e('Required', 'woocommerce'); ?></span></label>
								<span class="irantheme-login-input-icon-holder">
									<span class="irantheme-login-input-icon-username irantheme-login-input-icon"></span>
									<input type="text" class="irantheme-login-input woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo (! empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" required aria-required="true" />
								</span>
							</p>

						<?php endif; ?>

						<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
							<label class="irantheme-login-label irantheme-register-content-form-email-label form-label" for="reg_email"><?php esc_html_e('Email address', 'woocommerce'); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e('Required', 'woocommerce'); ?></span></label>
							<span class="irantheme-login-input-icon-holder">
								<span class="irantheme-login-input-icon-email irantheme-login-input-icon"></span>
								<input type="email" class="irantheme-login-input woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo (! empty($_POST['email'])) ? esc_attr(wp_unslash($_POST['email'])) : ''; ?>" required aria-required="true" placeholder="example@domain.com" />
							</span>
						</p>

						<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
							<label class="irantheme-login-label irantheme-register-content-form-mobile-number-label form-label" for="reg_mobile-number"><?php echo esc_html('شماره تلفن همراه'); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e('Required', 'woocommerce'); ?></span></label>
							<span class="irantheme-login-input-icon-holder">
								<span class="irantheme-login-input-icon-mobile-number irantheme-login-input-icon"></span>
								<input
									type="tel"
									class="irantheme-login-input woocommerce-Input woocommerce-Input--text input-text"
									name="mobile_number"
									id="reg_mobile_number"
									autocomplete="tel"
									value="<?php echo isset($_POST['mobile_number']) ? esc_attr(wp_unslash($_POST['mobile_number'])) : ''; ?>"
									required
									aria-required="true"
									pattern="^09[0-9]{9}$"
									placeholder="09123456789"
									title="09XXXXXXXXX" />
							</span>
						</p>

						<?php if ('no' === get_option('woocommerce_registration_generate_password')) : ?>
							<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
								<label class="irantheme-login-label irantheme-register-content-form-password-label form-label" for="reg_password"><?php esc_html_e('Password', 'woocommerce'); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e('Required', 'woocommerce'); ?></span></label>
								<span class="irantheme-login-input-icon-holder">
									<span class="irantheme-login-input-icon-password irantheme-login-input-icon"></span>
									<input type="password" class="irantheme-registration-password-input irantheme-login-input woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" required aria-required="true" />
								</span>
							</p>
							<ul class="irantheme-register-password-hint">
								<li class="irantheme-register-password-hint-item"><span class="irantheme-register-password-hint-item-text password-contain-least-char"><i class="fa fa-circle"></i>رمز عبور باید حداقل ۸ کاراکتر باشد.</span></li>
								<?php if ($password_checker == 'on'): ?>
									<li class="irantheme-register-password-hint-item"><span class="irantheme-register-password-hint-item-text password-contain-letter-char"><i class="fa fa-circle"></i>رمز عبور باید شامل حروف کوچک و بزرگ انگلیسی باشد.</span></li>
									<li class="irantheme-register-password-hint-item"><span class="irantheme-register-password-hint-item-text password-contain-number-char"><i class="fa fa-circle"></i>رمز عبور باید شامل عدد باشد.</span></li>
									<li class="irantheme-register-password-hint-item"><span class="irantheme-register-password-hint-item-text password-contain-symbol-char"><i class="fa fa-circle"></i>رمز عبور باید شامل نماد هایی مانند $ @ $ ! % * # ? & باشد.</span></li>
								<?php endif; ?>
							</ul>
						<?php else : ?>
							<p class="irantheme-register-generate-password"><?php esc_html_e('A link to set a new password will be sent to your email address.', 'woocommerce'); ?></p>
						<?php endif; ?>

						<?php do_action('woocommerce_register_form');
						?>
						<p class="woocommerce-form-row form-row">
							<?php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce'); ?>
							<button type="submit" class="irantheme-myaccount-login-form-submit irantheme-register-content-form-submit irantheme-btn <?php echo esc_attr($btn_submit_color); ?> irantheme-btn-medium woocommerce-Button woocommerce-button button<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?> woocommerce-form-register__submit" name="register" value="<?php esc_attr_e('Register', 'woocommerce'); ?>"><?php esc_html_e('Register', 'woocommerce'); ?></button>
						</p>
						<?php do_action('woocommerce_register_form_end'); ?>

					</form>
					<div class="irantheme-myaccount-login-signin irantheme-register-content-signin">
						<p class="irantheme-myaccount-login-signin-text irantheme-register-content-signin-text">قبلا ثبت نام کرده اید؟<a class="irantheme-myaccount-login-signin-text-link irantheme-register-content-signin-text-link" id="irantheme_myaccount_login_toggle">وارد شوید</a></p>

					</div>
					<div class="irantheme-myaccount-login-copyright irantheme-register-content-copyright">
						<p class="irantheme-myaccount-login-copyright-text irantheme-register-content-copyright-text"><?php echo $copyright_text; ?></p>
					</div>
				</div>
				<?php if ($display_mode == 'dual-col-box-form'): ?>
					<div class="irantheme-login-image <?php echo $image_col_slider == 'on' ? 'irantheme-login-image-carousel swiper' : ''; ?>" style="<?php echo $semi_border_radius_css; ?>">
						<a href="<?php echo esc_url(home_url()); ?>" class="irantheme-login-return-home-button" title="بازگشت به صفحه اصلی">
							<span>بازگشت به خانه</span>
							<i class="iconsax" icon-name="arrow-left"></i>
						</a>
						<?php if ($image_col_slider == 'off'): ?>
							<img src="<?php echo esc_attr($image_col_mono_img_url); ?>" srcset="<?php echo esc_attr(wp_get_attachment_image_srcset(attachment_url_to_postid($image_col_mono_img_url), 'full')); ?>" sizes="<?php echo wp_get_attachment_image_sizes(attachment_url_to_postid($image_col_mono_img_url), 'full') ?>" alt="<?php echo esc_attr('ستون محتوای تصویری') ?>">
						<?php elseif ($image_col_slider == 'on'): ?>
							<div class="swiper-wrapper">
								<?php foreach ($slider_images_array as $media_url) :
									if ($media_url == '') {
										continue;
									}
								?>
									<div class="swiper-slide">
										<img src="<?php echo esc_attr($media_url) ?>" alt="<?php echo esc_attr('ستون محتوای تصویری') ?>" srcset="<?php echo esc_attr(wp_get_attachment_image_srcset(attachment_url_to_postid($media_url), 'full')); ?>" sizes="<?php echo wp_get_attachment_image_sizes(attachment_url_to_postid($media_url), 'full') ?>">.
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
<?php do_action('woocommerce_after_customer_login_form'); ?>