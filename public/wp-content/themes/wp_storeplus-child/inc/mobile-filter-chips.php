<?php
/**
 * Mobile shop filter chips: category / status / brand.
 * (The price-range chip was removed together with the price filter box.)
 * Each chip opens a bottom-sheet modal; Apply runs theme AJAX + marks chip active.
 *
 * @package StorePlus_Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @return bool
 */
function storeplus_child_mfc_enabled() {
	return (bool) apply_filters( 'storeplus_child_mfc_enabled', true );
}

/**
 * @return bool
 */
function storeplus_child_mfc_is_shop() {
	return function_exists( 'is_shop' )
		&& ( is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy() );
}

/**
 * Chip bar + modal shell above product cards (mobile only via CSS).
 */
function storeplus_child_mfc_render() {
	if ( ! storeplus_child_mfc_enabled() || ! storeplus_child_mfc_is_shop() ) {
		return;
	}

	$chips = array(
		'category' => array(
			'label' => 'دسته‌بندی',
			'icon'  => 'fa-light fa-list',
		),
		'status'   => array(
			'label' => 'وضعیت',
			'icon'  => 'fa-light fa-toggle-on',
		),
		'brand'    => array(
			'label' => 'برند',
			'icon'  => 'fa-light fa-tags',
		),
	);
	?>
	<div class="sp-mobile-filter-chips" data-sp-mfc="1" role="toolbar" aria-label="فیلترها">
		<?php foreach ( $chips as $key => $chip ) : ?>
			<button
				type="button"
				class="sp-mfc-chip"
				data-sp-mfc-open="<?php echo esc_attr( $key ); ?>"
				aria-pressed="false"
			>
				<i class="<?php echo esc_attr( $chip['icon'] ); ?>" aria-hidden="true"></i>
				<span class="sp-mfc-chip__label"><?php echo esc_html( $chip['label'] ); ?></span>
			</button>
		<?php endforeach; ?>
	</div>

	<div class="sp-mfc-overlay" data-sp-mfc-overlay hidden>
		<div
			class="sp-mfc-sheet"
			role="dialog"
			aria-modal="true"
			aria-labelledby="sp-mfc-title"
			tabindex="-1"
		>
			<div class="sp-mfc-sheet__header">
				<h2 id="sp-mfc-title" class="sp-mfc-sheet__title">فیلتر</h2>
				<button type="button" class="sp-mfc-sheet__close" data-sp-mfc-close aria-label="بستن">
					<i class="fa-light fa-xmark" aria-hidden="true"></i>
				</button>
			</div>
			<div class="sp-mfc-sheet__body" data-sp-mfc-body></div>
			<div class="sp-mfc-sheet__footer">
				<button type="button" class="sp-mfc-sheet__clear" data-sp-mfc-clear>پاک کردن</button>
				<button type="button" class="sp-mfc-sheet__apply" data-sp-mfc-apply>اعمال فیلتر</button>
			</div>
		</div>
	</div>
	<?php
}
add_action( 'storeplus_child_before_archive_products', 'storeplus_child_mfc_render', 5 );

/**
 * Enqueue mobile filter chips script on shop archives.
 */
function storeplus_child_mfc_enqueue() {
	if ( ! storeplus_child_mfc_enabled() || ! storeplus_child_mfc_is_shop() ) {
		return;
	}

	$js = STOREPLUS_CHILD_DIR . '/assets/js/mobile-filter-chips.js';
	if ( ! file_exists( $js ) ) {
		return;
	}

	$deps = array();
	if ( wp_script_is( 'jquery', 'registered' ) ) {
		$deps[] = 'jquery';
	}

	wp_enqueue_script(
		'storeplus-child-mobile-filter-chips',
		STOREPLUS_CHILD_URI . '/assets/js/mobile-filter-chips.js',
		$deps,
		filemtime( $js ),
		true
	);

	wp_localize_script(
		'storeplus-child-mobile-filter-chips',
		'spMobileFilterChips',
		array(
			'titles' => array(
				'category' => 'دسته‌بندی',
				'status'   => 'وضعیت',
				'brand'    => 'برند',
			),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'storeplus_child_mfc_enqueue', 35 );
