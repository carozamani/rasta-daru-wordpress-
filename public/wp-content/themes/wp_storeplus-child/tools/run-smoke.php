<?php
require __DIR__ . '/_boot.php';

/**
 * Smoke + harden runner.
 *
 * Prefer WordPress bootstrap when mysqli is available (Local web SAPI / full PHP).
 * Fallback: standalone HTTP probes against the live site + wp-config text checks
 * (Local CLI often ships without mysqli).
 *
 * Usage:
 *   php tools/run-smoke.php
 *   php tools/run-smoke.php http://rastadarou.local
 *
 * Exit 0 = smoke pass + no blocking harden items.
 *
 * @package StorePlus_Child
 */

$is_cli = ( PHP_SAPI === 'cli' || PHP_SAPI === 'phpdbg' );
$base   = isset( $argv[1] ) ? rtrim( (string) $argv[1], '/' ) : '';

$public = dirname( __DIR__, 4 ); /* .../public */
$config = $public . '/wp-config.php';

/**
 * @param string $url URL.
 * @return array{ok:bool,code:int,final:string,body:string,detail:string}
 */
function sp_smoke_curl( $url ) {
	$t0 = microtime( true );

	if ( function_exists( 'curl_init' ) ) {
		$ch = curl_init( $url );
		curl_setopt_array(
			$ch,
			array(
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_MAXREDIRS      => 5,
				CURLOPT_CONNECTTIMEOUT => 8,
				CURLOPT_TIMEOUT        => 25,
				CURLOPT_SSL_VERIFYPEER => false,
				CURLOPT_SSL_VERIFYHOST => 0,
				CURLOPT_USERAGENT      => 'StorePlus-Smoke/1.0',
			)
		);
		$body = curl_exec( $ch );
		$err  = curl_error( $ch );
		$code = (int) curl_getinfo( $ch, CURLINFO_RESPONSE_CODE );
		$fin  = (string) curl_getinfo( $ch, CURLINFO_EFFECTIVE_URL );
		curl_close( $ch );
		$ms = (int) round( ( microtime( true ) - $t0 ) * 1000 );
		$ok = ( false !== $body && '' === $err && $code >= 200 && $code < 400 );
		return array(
			'ok'     => $ok,
			'code'   => $code,
			'final'  => $fin,
			'body'   => is_string( $body ) ? $body : '',
			'detail' => $err ? $err : ( 'HTTP ' . $code . ' · ' . $ms . 'ms · ' . $fin ),
		);
	}

	/* Local lightning CLI often has neither curl nor mysqli — use fopen wrappers. */
	$ctx = stream_context_create(
		array(
			'http' => array(
				'timeout'         => 25,
				'follow_location' => 1,
				'max_redirects'   => 5,
				'header'          => "User-Agent: StorePlus-Smoke/1.0\r\n",
				'ignore_errors'   => true,
			),
			'ssl'  => array(
				'verify_peer'      => false,
				'verify_peer_name' => false,
			),
		)
	);
	$body = @file_get_contents( $url, false, $ctx ); // phpcs:ignore
	$code = 0;
	$fin  = $url;
	if ( ! empty( $http_response_header ) && is_array( $http_response_header ) ) {
		foreach ( $http_response_header as $h ) {
			if ( preg_match( '/^HTTP\/\S+\s+(\d{3})/', $h, $m ) ) {
				$code = (int) $m[1];
			}
			if ( preg_match( '/^Location:\s*(.+)$/i', $h, $m ) ) {
				$fin = trim( $m[1] );
			}
		}
	}
	$ms = (int) round( ( microtime( true ) - $t0 ) * 1000 );
	$ok = ( is_string( $body ) && $code >= 200 && $code < 400 );
	if ( 0 === $code && is_string( $body ) && '' !== $body ) {
		$code = 200;
		$ok   = true;
	}
	return array(
		'ok'     => $ok,
		'code'   => $code,
		'final'  => $fin,
		'body'   => is_string( $body ) ? $body : '',
		'detail' => false === $body
			? 'file_get_contents failed (allow_url_fopen / network)'
			: ( 'HTTP ' . $code . ' · ' . $ms . 'ms · ' . $fin ),
	);
}

/**
 * @param string $body HTML.
 * @param array  $needles needle => label.
 * @return string[] missing labels
 */
function sp_smoke_missing( $body, array $needles ) {
	$missing = array();
	foreach ( $needles as $n => $label ) {
		if ( false === strpos( $body, $n ) ) {
			$missing[] = $label;
		}
	}
	return $missing;
}

/**
 * Discover a product URL from homepage HTML.
 *
 * @param string $html Home HTML.
 * @return string
 */
function sp_smoke_find_product_url( $html, $base = '' ) {
	if ( preg_match( '#href=["\'](https?://[^"\']+/product/[^"\'?#]+/?)["\']#u', $html, $m ) ) {
		return html_entity_decode( $m[1], ENT_QUOTES, 'UTF-8' );
	}
	if ( preg_match( '#href=["\'](/product/[^"\'?#]+/?)["\']#u', $html, $m ) && $base ) {
		return rtrim( $base, '/' ) . html_entity_decode( $m[1], ENT_QUOTES, 'UTF-8' );
	}
	return '';
}

/**
 * Parse wp-config.php for harden signals (no WP bootstrap).
 *
 * @param string $config_path Path.
 * @param bool   $is_local    Whether env looks local.
 * @return array{rows:array,blocking:int,env:string}
 */
function sp_smoke_harden_from_config( $config_path, $is_local ) {
	$raw = is_readable( $config_path ) ? (string) file_get_contents( $config_path ) : '';
	$env = 'production';
	if ( preg_match( "/define\s*\(\s*['\"]WP_ENVIRONMENT_TYPE['\"]\s*,\s*['\"]([^'\"]+)['\"]/i", $raw, $m ) ) {
		$env = $m[1];
	}
	$is_local = in_array( $env, array( 'local', 'development' ), true );

	$has = static function ( $name, $truthy = true ) use ( $raw ) {
		if ( ! preg_match( '/define\s*\(\s*[\'"]' . preg_quote( $name, '/' ) . '[\'"]\s*,\s*([^)]+)\)/i', $raw, $m ) ) {
			return null;
		}
		$val = strtolower( trim( $m[1] ) );
		$is_true = ( 'true' === $val || '1' === $val );
		return $truthy ? $is_true : ! $is_true;
	};

	$prefix = 'wp_';
	if ( preg_match( '/\$table_prefix\s*=\s*[\'"]([^\'"]+)[\'"]/', $raw, $m ) ) {
		$prefix = $m[1];
	}

	$rows = array();
	$block = 0;

	$add = static function ( $id, $ok, $detail, $blocking = false ) use ( &$rows, &$block ) {
		$rows[] = array(
			'id'       => $id,
			'ok'       => $ok,
			'detail'   => $detail,
			'blocking' => $blocking && ! $ok,
		);
		if ( $blocking && ! $ok ) {
			++$block;
		}
	};

	$add( 'env', true, $env . ( $is_local ? ' (Local soft-harden)' : ' (production harden expected)' ) );

	$file_edit = $has( 'DISALLOW_FILE_EDIT' );
	$add( 'disallow_file_edit', true === $file_edit, true === $file_edit ? 'true' : 'missing/false', true );

	$file_mods = $has( 'DISALLOW_FILE_MODS' );
	if ( $is_local ) {
		$add( 'disallow_file_mods', true, 'Local may keep false for installs; production forces true via env switch' );
	} else {
		$add( 'disallow_file_mods', true === $file_mods, true === $file_mods ? 'true' : 'should be true', true );
	}

	$force_ssl = $has( 'FORCE_SSL_ADMIN' );
	if ( $is_local ) {
		$add( 'force_ssl_admin', true, 'Local soft — production sets true when WP_ENVIRONMENT_TYPE=production' );
	} else {
		$add( 'force_ssl_admin', true === $force_ssl, true === $force_ssl ? 'true' : 'should be true', true );
	}

	$prefix_ok = ( 'wp_' !== $prefix && '' !== $prefix );
	if ( $is_local ) {
		$add( 'db_prefix', true, '`' . $prefix . '` — change during Duplicator migration, not on live Local DB' );
	} else {
		$add( 'db_prefix', $prefix_ok, '`' . $prefix . '`' . ( $prefix_ok ? '' : ' — change at migrate time' ), true );
	}

	$debug = $has( 'WP_DEBUG' );
	if ( $is_local ) {
		$add( 'wp_debug', true, null === $debug ? 'unset' : ( $debug ? 'true' : 'false' ) );
	} else {
		$add( 'wp_debug', false === $debug || null === $debug, $debug ? 'true (bad on prod)' : 'false/unset', true );
	}

	$mu = is_readable( dirname( $config_path ) . '/wp-content/mu-plugins/harden-site-security.php' );
	$add( 'mu_harden', $mu, $mu ? 'harden-site-security.php present' : 'MU harden missing', true );

	$http_block = $has( 'WP_HTTP_BLOCK_EXTERNAL' );
	if ( $is_local ) {
		$add( 'http_block', true === $http_block || null === $http_block, 'Local blocks outbound HTTP (expected)' );
	} else {
		$add( 'http_block', false === $http_block || null === $http_block, $http_block ? 'true may break payments/SMS' : 'open/allowlisted', true );
	}

	return array(
		'rows'     => $rows,
		'blocking' => $block,
		'env'      => $env,
	);
}

/* ---------- try WP bootstrap when mysqli exists ---------- */
$use_wp = extension_loaded( 'mysqli' ) || extension_loaded( 'mysql' );
$report = null;

if ( $use_wp ) {
	if ( ! $is_cli ) {
		require $public . '/wp-load.php';
		require __DIR__ . '/_security.php';
		storeplus_child_tools_assert_allowed();
	} else {
		require $public . '/wp-load.php';
	}
	if ( function_exists( 'storeplus_child_smoke_full_report' ) ) {
		$report = storeplus_child_smoke_full_report();
	}
}

/* ---------- standalone fallback ---------- */
if ( null === $report ) {
	if ( '' === $base ) {
		/* Guess from Local sites.json domain or default. */
		$base = 'http://rastadarou.local';
		if ( is_readable( $config ) && preg_match( "/define\s*\(\s*['\"]WP_HOME['\"]\s*,\s*['\"]([^'\"]+)['\"]/", (string) file_get_contents( $config ), $hm ) ) {
			$base = rtrim( $hm[1], '/' );
		}
	}

	$harden = sp_smoke_harden_from_config( $config, true );
	$smoke  = array();

	$home = sp_smoke_curl( $base . '/' );
	$miss = sp_smoke_missing(
		$home['body'],
		array(
			'irantheme-footer-nav-section' => 'footer',
			'data-sp-php-footer'           => 'php-footer',
			'sp-php-home-order'            => 'home-order',
		)
	);
	$smoke[] = array(
		'ok'     => $home['ok'] && ! $miss,
		'id'     => 'home',
		'detail' => $home['ok'] ? ( $miss ? ( 'missing: ' . implode( ',', $miss ) ) : $home['detail'] ) : $home['detail'],
	);

	$purl = sp_smoke_find_product_url( $home['body'], $base );
	if ( ! $purl ) {
		$shop = sp_smoke_curl( $base . '/shop/' );
		$purl = sp_smoke_find_product_url( $shop['body'], $base );
	}
	if ( ! $purl ) {
		$smoke[] = array( 'ok' => false, 'id' => 'pdp', 'detail' => 'no product URL on homepage/shop' );
	} else {
		$pdp  = sp_smoke_curl( $purl );
		/* One retry — Local fopen can flake on long UTF-8 product paths. */
		if ( ! $pdp['ok'] ) {
			usleep( 250000 );
			$pdp = sp_smoke_curl( $purl );
		}
		$miss = sp_smoke_missing( $pdp['body'], array( 'woocommerce' => 'wc', 'product' => 'product', 'data-sp-php-footer' => 'php-footer' ) );
		$smoke[] = array(
			'ok'     => $pdp['ok'] && ! $miss,
			'id'     => 'pdp',
			'detail' => $pdp['ok']
				? ( $miss ? ( 'missing: ' . implode( ',', $miss ) ) : $pdp['detail'] )
				: ( $pdp['detail'] . ' · url=' . $purl ),
		);
	}

	$cart = sp_smoke_curl( $base . '/cart/' );
	$miss = sp_smoke_missing( $cart['body'], array( 'woocommerce' => 'wc', 'data-sp-php-footer' => 'php-footer' ) );
	$smoke[] = array(
		'ok'     => $cart['ok'] && ! $miss,
		'id'     => 'cart',
		'detail' => $cart['ok'] ? ( $miss ? ( 'missing: ' . implode( ',', $miss ) ) : $cart['detail'] ) : $cart['detail'],
	);

	$chk = sp_smoke_curl( $base . '/checkout/' );
	$fin = $chk['final'];
	$auth_gate = ( false !== strpos( $fin, 'my-account' ) )
		|| ( false !== strpos( $fin, 'login' ) )
		|| ( false !== strpos( $fin, 'sp_need_auth' ) )
		|| ( false !== strpos( $chk['body'], 'sp_need_auth' ) );
	$is_checkout = ( false !== strpos( $fin, 'checkout' ) );
	$smoke[]     = array(
		'ok'     => $chk['ok'] && ( $is_checkout || $auth_gate ),
		'id'     => 'checkout',
		'detail' => $chk['ok']
			? ( $auth_gate ? ( 'auth gate · ' . $chk['detail'] ) : $chk['detail'] )
			: $chk['detail'],
	);

	$ok_s = 0;
	foreach ( $smoke as $r ) {
		if ( ! empty( $r['ok'] ) ) {
			++$ok_s;
		}
	}
	$ok_h = 0;
	foreach ( $harden['rows'] as $r ) {
		if ( ! empty( $r['ok'] ) ) {
			++$ok_h;
		}
	}

	$report = array(
		'smoke'        => $smoke,
		'harden'       => $harden['rows'],
		'ok_smoke'     => $ok_s,
		'total_smoke'  => count( $smoke ),
		'ok_harden'    => $ok_h,
		'total_harden' => count( $harden['rows'] ),
		'blocking'     => $harden['blocking'],
		'_mode'        => 'standalone',
		'_base'        => $base,
		'_env'         => $harden['env'],
	);
}

/* ---------- print ---------- */
$line = static function ( $s ) use ( $is_cli ) {
	if ( $is_cli ) {
		echo $s . PHP_EOL;
	} else {
		echo htmlspecialchars( $s, ENT_QUOTES, 'UTF-8' ) . "<br>\n";
	}
};

if ( ! $is_cli ) {
	header( 'Content-Type: text/html; charset=utf-8' );
	echo '<pre dir="ltr" style="font:14px/1.5 Consolas,monospace;padding:16px">';
}

$line( '=== StorePlus smoke + harden ===' );
$line( 'mode: ' . ( isset( $report['_mode'] ) ? $report['_mode'] : 'wordpress' ) );
if ( ! empty( $report['_base'] ) ) {
	$line( 'base: ' . $report['_base'] );
}
if ( ! empty( $report['_env'] ) ) {
	$line( 'env:  ' . $report['_env'] );
}
$line( '' );
$line( '-- SMOKE --' );
foreach ( $report['smoke'] as $row ) {
	$line( sprintf( '[%s] %s — %s', ! empty( $row['ok'] ) ? 'PASS' : 'FAIL', $row['id'], $row['detail'] ) );
}
$line( sprintf( 'smoke: %d/%d', $report['ok_smoke'], $report['total_smoke'] ) );
$line( '' );
$line( '-- HARDEN --' );
foreach ( $report['harden'] as $row ) {
	$id = isset( $row['id'] ) ? $row['id'] : '';
	if ( ! empty( $row['blocking'] ) ) {
		$mark = 'BLOCK';
	} elseif ( ! empty( $row['ok'] ) ) {
		$mark = 'OK   ';
	} else {
		$mark = 'WARN ';
	}
	$line( sprintf( '[%s] %s — %s', $mark, $id, $row['detail'] ) );
}
$line( sprintf( 'harden: %d/%d  blocking: %d', $report['ok_harden'], $report['total_harden'], $report['blocking'] ) );

$pass = ( (int) $report['ok_smoke'] === (int) $report['total_smoke'] ) && ( 0 === (int) $report['blocking'] );
$line( '' );
$line( $pass ? 'RESULT: PASS' : 'RESULT: FAIL' );

if ( ! $is_cli ) {
	echo '</pre>';
}

exit( $pass ? 0 : 1 );
