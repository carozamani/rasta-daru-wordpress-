<?php
/**
 * Site logo + favicon.
 *
 * - Logo:    assets/logo/jabedee-logo.png    -> used everywhere the parent theme prints its logo.
 * - Favicon: assets/logo/jabedee-favicon.png -> WordPress "site icon" (browser tab, bookmarks, Apple touch icon).
 *
 * The parent reads its logo settings through get_option() and, in "upload_mono" mode, expects a
 * media-library attachment ID; WordPress core also stores the site icon as an attachment ID. So each PNG is
 * imported into the media library once (re-imported if the file changes) and the options are overridden with
 * pre_option filters, only while a valid import exists.
 * To go back to the theme's own settings, remove 'site-logo' from the 'inc' list in functions.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'STOREPLUS_CHILD_LOGO_OPTION', 'storeplus_child_logo_attachment' );
define( 'STOREPLUS_CHILD_FAVICON_OPTION', 'storeplus_child_favicon_attachment' );

/**
 * Import a PNG from the child theme into the media library (once per file version).
 *
 * @param string $file       Absolute path.
 * @param string $option     Option that remembers array( hash, id ).
 * @param string $media_name File name in the media library.
 * @param string $title      Description / title of the attachment.
 */
function storeplus_child_import_image( $file, $option, $media_name, $title ) {
	if ( ! file_exists( $file ) ) {
		return;
	}

	$hash  = md5_file( $file );
	$saved = get_option( $option );
	if ( is_array( $saved ) && isset( $saved['hash'], $saved['id'] ) && $saved['hash'] === $hash && get_post( (int) $saved['id'] ) ) {
		return;
	}
	// A failed import is retried at most once an hour.
	$fail_key = $option . '_failed';
	if ( get_transient( $fail_key ) ) {
		return;
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$tmp = wp_tempnam( $file );
	if ( ! $tmp || ! copy( $file, $tmp ) ) {
		set_transient( $fail_key, 1, HOUR_IN_SECONDS );
		return;
	}

	$id = media_handle_sideload(
		array(
			'name'     => $media_name,
			'tmp_name' => $tmp,
		),
		0,
		$title
	);

	if ( is_wp_error( $id ) ) {
		if ( file_exists( $tmp ) ) {
			@unlink( $tmp );
		}
		set_transient( $fail_key, 1, HOUR_IN_SECONDS );
		return;
	}

	update_option( $option, array( 'hash' => $hash, 'id' => (int) $id ), false );
}

add_action(
	'init',
	function () {
		storeplus_child_import_image( STOREPLUS_CHILD_DIR . '/assets/logo/jabedee-logo.png', STOREPLUS_CHILD_LOGO_OPTION, 'jabedee-logo.png', 'لوگوی سایت' );
		storeplus_child_import_image( STOREPLUS_CHILD_DIR . '/assets/logo/jabedee-favicon.png', STOREPLUS_CHILD_FAVICON_OPTION, 'jabedee-favicon.png', 'فاویکون سایت' );
	},
	20
);

/**
 * Attachment ID saved under $option, or 0 when there is none.
 */
function storeplus_child_saved_image_id( $option ) {
	$saved = get_option( $option );
	if ( is_array( $saved ) && ! empty( $saved['id'] ) && get_post( (int) $saved['id'] ) ) {
		return (int) $saved['id'];
	}
	return 0;
}

/**
 * Logo option overrides (read by the parent theme through get_option()) + WordPress site icon.
 */
add_action(
	'after_setup_theme',
	function () {
		$overrides = array(
			'irantheme_logo_mode_radio'           => 'upload_mono',
			'irantheme_logo_mobile_mono_checkbox' => 'off', // same logo on mobile
		);
		foreach ( $overrides as $name => $value ) {
			add_filter(
				'pre_option_' . $name,
				function ( $pre ) use ( $value ) {
					return storeplus_child_saved_image_id( STOREPLUS_CHILD_LOGO_OPTION ) ? $value : $pre;
				}
			);
		}
		add_filter(
			'pre_option_irantheme_mono_logo_image',
			function ( $pre ) {
				$id = storeplus_child_saved_image_id( STOREPLUS_CHILD_LOGO_OPTION );
				return $id ? $id : $pre;
			}
		);
		add_filter(
			'pre_option_site_icon',
			function ( $pre ) {
				$id = storeplus_child_saved_image_id( STOREPLUS_CHILD_FAVICON_OPTION );
				return $id ? $id : $pre;
			}
		);
	},
	20
);
