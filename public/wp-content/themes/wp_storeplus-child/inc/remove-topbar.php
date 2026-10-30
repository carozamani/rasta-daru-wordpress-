<?php
/**
 * Topbar removal (free-shipping note, top menu, social icons).
 *
 * The parent only renders the topbar when the option 'irantheme_topbar_checkbox' is
 * 'on' (read through get_option()). Forcing it to 'off' on the front end stops the
 * markup from being printed at all, so the header offsets are calculated without it
 * too. The admin settings screen still shows the stored value.
 * Pages with their own custom header settings are covered by assets/css/remove-topbar.css.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter(
	'pre_option_irantheme_topbar_checkbox',
	function ( $pre ) {
		return is_admin() ? $pre : 'off';
	}
);
