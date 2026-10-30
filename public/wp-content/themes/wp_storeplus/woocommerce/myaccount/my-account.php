<?php

/**
 * My Account page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-account.php.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @wc-version 3.5.0
 * @author M-MALEKZADEH77
 */

defined('ABSPATH') || exit; ?>

<div class="irantheme-myaccount">
	<div class="irantheme-container-inner">
		<div class="irantheme-myaccount-holder row">
			<div class="irantheme-myaccount-sidebar-col col-xxl-3 col-xl-3 col-lg-4 col-md-4 col-sm-12">
				<div class="irantheme-myaccount-sidebar">
					<?php
					/**
					 * My Account navigation.
					 *
					 * @since 2.6.0
					 */
					do_action('woocommerce_account_navigation');
					?>
				</div>
			</div>
			<div class="irantheme-myaccount-content-col col-xxl-9 col-xl-9 col-lg-8 col-md-8 col-sm-12">
				<div class="woocommerce-MyAccount-content irantheme-myaccount-content">
					<?php
					/**
					 * My Account content.
					 *
					 * @since 2.6.0
					 */
					do_action('woocommerce_account_content');
					?>
				</div>
			</div>
		</div>
	</div>
</div>