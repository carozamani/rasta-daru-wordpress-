<?php
/**
 * Check that the child-theme hook points still exist inside the PARENT theme.
 *
 * Run after every parent (wp_storeplus) update:
 *   php tools/check-parent-hooks.php
 *
 * CLI only. Read-only: it never modifies any file.
 * Exit code 0 = all hook points present, 1 = something was lost by the update.
 *
 * Snapshots of the patched parent files (as they were when the hooks were
 * added) live in tools/parent-patches/*.snapshot — diff the updated parent
 * file against its snapshot, then re-apply the hook lines.
 *
 * @package StorePlus_Child
 */

if ( PHP_SAPI !== 'cli' ) {
	http_response_code( 404 );
	exit;
}

$child  = dirname( __DIR__ );
$parent = dirname( $child ) . '/wp_storeplus';

/* parent-relative file => markers that must all be present */
$checks = array(
	'inc/core/global.php'                                                  => array(
		"function_exists('storeplus_child_get_skeleton_html')",
	),
	'inc/core/section/class-irantheme-section-woocommerce-mini-products.php' => array(
		"function_exists('storeplus_child_mini_brand_badge')",
		"function_exists('storeplus_child_mini_cart_button')",
	),
	'inc/core/section/classes/class-irantheme-section-woocommerce.php'    => array(
		"function_exists('storeplus_child_product_card')",
	),
);

$bad = 0;
foreach ( $checks as $rel => $markers ) {
	$path = $parent . '/' . $rel;
	if ( ! is_file( $path ) ) {
		echo "MISSING FILE  {$rel}\n";
		++$bad;
		continue;
	}
	$src = (string) file_get_contents( $path );
	foreach ( $markers as $m ) {
		if ( false === strpos( $src, $m ) ) {
			echo "HOOK LOST     {$rel}  ->  {$m}\n";
			++$bad;
		} else {
			echo "ok            {$rel}  ->  {$m}\n";
		}
	}
}

/* archive-product.php now lives in the child theme (WooCommerce template override). */
$arch = $child . '/woocommerce/archive-product.php';
if ( ! is_file( $arch ) ) {
	echo "MISSING FILE  child woocommerce/archive-product.php\n";
	++$bad;
} else {
	$src = (string) file_get_contents( $arch );
	foreach ( array( 'storeplus_child_after_archive_category_filter', 'storeplus_child_before_archive_products' ) as $m ) {
		if ( false === strpos( $src, $m ) ) {
			echo "HOOK LOST     child archive-product.php -> {$m}\n";
			++$bad;
		} else {
			echo "ok            child archive-product.php -> {$m}\n";
		}
	}
}

echo $bad ? "\n{$bad} problem(s). Re-apply the hooks (see tools/parent-patches/).\n" : "\nAll hook points present.\n";
exit( $bad ? 1 : 0 );
