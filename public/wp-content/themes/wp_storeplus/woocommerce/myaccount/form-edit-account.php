<?php

/**
 * Edit account form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @wc-version 9.6.0
 * @author 	M-MALEKZADEH77
 */

defined('ABSPATH') || exit;
?>
<div class="irantheme-myaccount-edit-account">
	<?php do_action('woocommerce_before_edit_account_form'); ?>

	<form class="woocommerce-EditAccountForm edit-account irantheme-myaccount-edit-account-form" action="" method="post" <?php do_action('woocommerce_edit_account_form_tag'); ?>>
		<h2 class="irantheme-myaccount-content-title"><i class="fa-light fa-square-user"></i>ویرایش اطلاعات حساب</h2>
		<?php do_action('woocommerce_edit_account_form_start'); ?>
		<div class="irantheme-myaccount-edit-account-form-account-inputs irantheme-myaccount-input">
			<p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
				<label for="account_first_name"><?php esc_html_e('First name', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
				<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_first_name" id="account_first_name" autocomplete="given-name" value="<?php echo esc_attr($user->first_name); ?>" />
			</p>
			<p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last">
				<label for="account_last_name"><?php esc_html_e('Last name', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
				<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_last_name" id="account_last_name" autocomplete="family-name" value="<?php echo esc_attr($user->last_name); ?>" />
			</p>


			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="account_display_name"><?php esc_html_e('Display name', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
				<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_display_name" id="account_display_name" value="<?php echo esc_attr($user->display_name); ?>" aria-describedby="account_display_name_description" />
			</p>
			<p class="form-row">
				<em class="irantheme-myaccount-address-note">
					<i class="fa-light fa-seal-question irantheme-tip-icon"></i>
					<?php esc_html_e('This will be how your name will be displayed in the account section and in reviews', 'woocommerce'); ?>
				</em>
			</p>


			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="account_email"><?php esc_html_e('Email address', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
				<input type="email" class="woocommerce-Input woocommerce-Input--email input-text" name="account_email" id="account_email" autocomplete="email" value="<?php echo esc_attr($user->user_email); ?>" />
			</p>
			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="mobile_number"><?php esc_html_e('Mobile', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
				<input type="text" class="woocommerce-Input woocommerce-Input--mobile input-text" name="mobile_number" id="mobile_number" autocomplete="mobile" value="<?php echo esc_attr($user->mobile_number); ?>">
			</p>

			<?php
			/**
			 * Hook where additional fields should be rendered.
			 *
			 * @since 8.7.0
			 */
			do_action('woocommerce_edit_account_form_fields');
			?>
		</div>
		<fieldset class="irantheme-myaccount-fieldset">
			<legend class="irantheme-myaccount-content-title"><?php esc_html_e('Password change', 'woocommerce'); ?></legend>
			<div class="irantheme-myaccount-edit-account-form-password-inputs irantheme-myaccount-input">

				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide flex-basis-full">
					<label for="password_current"><?php esc_html_e('Current password (leave blank to leave unchanged)', 'woocommerce'); ?></label>
					<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_current" id="password_current" autocomplete="off" />
				</p>
				<p class="form-row-new-password woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="password_1"><?php esc_html_e('New password (leave blank to leave unchanged)', 'woocommerce'); ?></label>
					<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_1" id="password_1" autocomplete="off" />
				</p>
				<p class="form-row-repeat-password woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="password_2"><?php esc_html_e('Confirm new password', 'woocommerce'); ?></label>
					<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_2" id="password_2" autocomplete="off" />
				</p>
			</div>
		</fieldset>

		<?php
		/**
		 * My Account edit account form.
		 *
		 * @since 2.6.0
		 */
		do_action('woocommerce_edit_account_form');
		?>

		<p>
			<?php wp_nonce_field('save_account_details', 'save-account-details-nonce'); ?>
			<button type="submit" class="woocommerce-Button irantheme-myaccount-edit-form-submit irantheme-btn irantheme-btn-medium  button<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?>" name="save_account_details" value="<?php esc_attr_e('Save changes', 'woocommerce'); ?>"><?php esc_html_e('Save changes', 'woocommerce'); ?></button>
			<input type="hidden" name="action" value="save_account_details" />
		</p>

		<?php do_action('woocommerce_edit_account_form_end'); ?>
	</form>

	<?php do_action('woocommerce_after_edit_account_form'); ?>
</div>