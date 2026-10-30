<?php
/**
 * Keep PHP warnings / notices off the screen.
 *
 * With WP_DEBUG_DISPLAY on, any PHP notice or warning (for example from the parent theme on PHP 8.x)
 * is printed into the page and breaks the layout. This file turns the on-screen display off for the whole
 * site and keeps the messages in the log instead, so they can still be read and fixed.
 *
 * Where to read them: wp-content/debug.log (if WP_DEBUG_LOG is on) or the Local by Flywheel PHP error log
 * (site > Open site shell > logs/php/error.log).
 * To see messages on screen again while debugging, remove 'hide-php-warnings' from the 'inc' list in functions.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

@ini_set( 'display_errors', '0' );
@ini_set( 'display_startup_errors', '0' );
@ini_set( 'log_errors', '1' );
