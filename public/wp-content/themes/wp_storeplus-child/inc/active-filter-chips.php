<?php
/**
 * Active filter chips on shop archives — compact row above the sidebar «وضعیت» filter box.
 *
 * When category, brand, status, price, or search filters are applied, each one
 * appears as a removable chip (label + ×). Removing a chip clears that control
 * and re-runs the parent theme archive AJAX.
 *
 * @package StorePlus_Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @return bool
 */
function storeplus_child_afc_enabled() {
	return (bool) apply_filters( 'storeplus_child_afc_enabled', true );
}

/**
 * @return bool
 */
function storeplus_child_afc_is_shop() {
	return function_exists( 'is_shop' )
		&& ( is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy() );
}

/**
 * Empty bar; JS fills chips when filters are active.
 */
function storeplus_child_afc_render() {
	if ( ! storeplus_child_afc_enabled() || ! storeplus_child_afc_is_shop() ) {
		return;
	}
	?>
	<div
		class="sp-active-filters"
		data-sp-afc="1"
		hidden
		role="region"
		aria-label="فیلترهای اعمال‌شده"
	>
		<div class="sp-active-filters__track" data-sp-afc-track></div>
		<button type="button" class="sp-active-filters__clear" data-sp-afc-clear-all hidden>
			پاک کردن همه
		</button>
	</div>
	<?php
}
add_action( 'storeplus_child_before_archive_products', 'storeplus_child_afc_render', 20 );

/**
 * Enqueue active-filter chips script on shop archives.
 */
function storeplus_child_afc_enqueue() {
	if ( ! storeplus_child_afc_enabled() || ! storeplus_child_afc_is_shop() ) {
		return;
	}

	$js = STOREPLUS_CHILD_DIR . '/assets/js/active-filter-chips.js';
	if ( ! file_exists( $js ) ) {
		return;
	}

	$deps = array();
	if ( wp_script_is( 'jquery', 'registered' ) ) {
		$deps[] = 'jquery';
	}

	wp_enqueue_script(
		'storeplus-child-active-filter-chips',
		STOREPLUS_CHILD_URI . '/assets/js/active-filter-chips.js',
		$deps,
		filemtime( $js ),
		true
	);

	wp_localize_script(
		'storeplus-child-active-filter-chips',
		'spActiveFilterChips',
		array(
			'labels' => array(
				'in_stock'  => 'فقط کالا های موجود',
				'on_sale'   => 'فروش ویژه',
				'price'     => 'قیمت',
				'search'    => 'جستجو',
				'clear_all' => 'پاک کردن همه',
				'remove'    => 'حذف فیلتر',
			),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'storeplus_child_afc_enqueue', 36 );
