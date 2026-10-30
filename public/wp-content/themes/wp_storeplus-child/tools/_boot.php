<?php
/**
 * Hard gate for every file in tools/: CLI (or phpdbg) only.
 *
 * Theme tools must never be reachable over HTTP — even if nginx misroutes
 * or WordPress never boots. Require this as the first line after <?php.
 *
 * @package StorePlus_Child
 */

if ( PHP_SAPI !== 'cli' && PHP_SAPI !== 'phpdbg' ) {
	if ( ! headers_sent() ) {
		header_remove( 'X-Powered-By' );
		http_response_code( 404 );
		header( 'Content-Type: text/plain; charset=UTF-8' );
		header( 'Cache-Control: no-store' );
	}
	exit( 'Not found' );
}
