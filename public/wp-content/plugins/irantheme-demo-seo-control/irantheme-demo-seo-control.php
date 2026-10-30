<?php
/**
 * Plugin Name: Irantheme Demo SEO Control
 * Description: Prevent search engines from indexing Irantheme demo websites.
 * Version: 1.0.0
 * Author: Irantheme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Prevent Googlebot from indexing Irantheme demo websites only.
 *
 * @return void
 */
function irantheme_demo_noindex_for_googlebot() {
    // Do not modify admin pages.
    if ( is_admin() ) {
        return;
    }

    // Get the current website URL.
    $site_url = wp_parse_url( home_url( '/' ) );

    $site_host = strtolower( $site_url['host'] ?? '' );
    $site_path = '/' . trim( $site_url['path'] ?? '', '/' ) . '/';

    // Apply only to Irantheme demo websites.
    if (
        ! in_array( $site_host, array( 'irantheme.com', 'www.irantheme.com' ), true )
        || 0 !== strpos( $site_path, '/demo/' )
    ) {
        return;
    }

    // Apply the noindex directive only to Googlebot requests.
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';

    if ( false === stripos( $user_agent, 'Googlebot' ) ) {
        return;
    }

    echo '<meta name="googlebot" content="noindex">' . "\n";
}
add_action( 'wp_head', 'irantheme_demo_noindex_for_googlebot', 1 );
