<?php
/**
 * Plugin Name: فروش شگفت‌انگیز (Amazing Offers)
 * Description: یک جعبه «فروش شگفت‌انگیز» کنار تصویر شاخص صفحه ویرایش محصول (و ستون/عملیات گروهی در لیست محصولات). فهرست باکس «پیشنهاد شگفت‌انگیز» صفحه اصلی از روی همین تیک خودکار ساخته می‌شود.
 * Version: 1.0.0
 */

defined( 'ABSPATH' ) || exit;

const AO_META    = '_amazing_offer';
const AO_SECTION = 'woocommerce_offers_products'; // data_value of the theme's offers section

/* ------------------------------------------------------------------
 * 1) جعبه کنار تصویر محصول
 * ---------------------------------------------------------------- */
add_action( 'add_meta_boxes_product', function () {
	add_meta_box( 'ao_box', '🔥 فروش شگفت‌انگیز', 'ao_render_box', 'product', 'side', 'low' );
} );

function ao_render_box( $post ) {
	$product = wc_get_product( $post->ID );
	if ( ! $product ) {
		return;
	}
	$enabled   = 'yes' === $product->get_meta( AO_META );
	$regular   = (float) $product->get_regular_price();
	$sale      = $product->get_sale_price( 'edit' );
	$percent   = ( $regular > 0 && '' !== $sale && (float) $sale < $regular ) ? round( ( 1 - (float) $sale / $regular ) * 100 ) : '';
	$end_obj   = $product->get_date_on_sale_to( 'edit' );
	$end       = $end_obj ? $end_obj->date( 'Y-m-d' ) : '';
	$is_varied = $product->is_type( 'variable' ) || $product->is_type( 'grouped' );

	wp_nonce_field( 'ao_save', 'ao_nonce' );
	?>
	<p>
		<label>
			<input type="checkbox" name="ao_enabled" id="ao_enabled" value="1" <?php checked( $enabled ); ?>>
			<strong>این محصول در «فروش شگفت‌انگیز» نمایش داده شود</strong>
		</label>
	</p>
	<div id="ao_fields" style="<?php echo $enabled ? '' : 'display:none'; ?>">
		<?php if ( $is_varied ) : ?>
			<p class="description">این محصول متغیر/گروهی است؛ قیمت حراج را در هر تنوع (یا خود محصولات زیرمجموعه) وارد کنید. درصد تخفیف خودکار برای آن اعمال نمی‌شود.</p>
		<?php else : ?>
			<p>
				<label for="ao_percent">درصد تخفیف</label><br>
				<input type="number" name="ao_percent" id="ao_percent" min="1" max="95" step="1" style="width:90px" value="<?php echo esc_attr( $percent ); ?>" placeholder="مثلاً 20">
				<span class="description">٪ — قیمت حراج از روی قیمت اصلی محاسبه می‌شود. خالی بماند، قیمت حراجِ فعلی حفظ می‌شود.</span>
			</p>
		<?php endif; ?>
		<p>
			<label for="ao_end">پایان تخفیف (اختیاری)</label><br>
			<input type="date" name="ao_end" id="ao_end" value="<?php echo esc_attr( $end ); ?>">
			<input type="hidden" name="ao_end_orig" value="<?php echo esc_attr( $end ); ?>">
		</p>
	</div>
	<script>
	(function () {
		var c = document.getElementById('ao_enabled'), f = document.getElementById('ao_fields');
		if (c && f) { c.addEventListener('change', function () { f.style.display = c.checked ? '' : 'none'; }); }
	})();
	</script>
	<?php
}

/* ------------------------------------------------------------------
 * 2) ذخیره
 * ---------------------------------------------------------------- */
add_action( 'woocommerce_process_product_meta', 'ao_save', 99 );

function ao_save( $post_id ) {
	if ( ! isset( $_POST['ao_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ao_nonce'] ) ), 'ao_save' ) ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$product = wc_get_product( $post_id );
	if ( ! $product ) {
		return;
	}

	if ( empty( $_POST['ao_enabled'] ) ) {
		$product->delete_meta_data( AO_META );
		$product->save();
		ao_sync();
		return;
	}

	$product->update_meta_data( AO_META, 'yes' );

	// درصد تخفیف → قیمت حراج
	if ( ! $product->is_type( 'variable' ) && ! $product->is_type( 'grouped' ) && isset( $_POST['ao_percent'] ) && '' !== $_POST['ao_percent'] ) {
		$pct     = (float) wc_format_decimal( wp_unslash( $_POST['ao_percent'] ) );
		$regular = (float) $product->get_regular_price();
		if ( $pct > 0 && $pct < 100 && $regular > 0 ) {
			$product->set_sale_price( wc_format_decimal( round( $regular * ( 1 - $pct / 100 ), wc_get_price_decimals() ) ) );
		}
	}

	// تاریخ پایان: فقط اگر کاربر همین فیلد را تغییر داده باشد (تا با تب «عمومی» ووکامرس تداخل نکند)
	$end      = isset( $_POST['ao_end'] ) ? sanitize_text_field( wp_unslash( $_POST['ao_end'] ) ) : '';
	$end_orig = isset( $_POST['ao_end_orig'] ) ? sanitize_text_field( wp_unslash( $_POST['ao_end_orig'] ) ) : '';
	if ( $end !== $end_orig ) {
		if ( $end && preg_match( '/^\d{4}-\d{2}-\d{2}$/', $end ) ) {
			$product->set_date_on_sale_to( $end . ' 23:59:59' );
		} else {
			$product->set_date_on_sale_to( null );
		}
	}

	$product->save();

	if ( ! $product->is_on_sale() && ! $product->is_type( 'variable' ) && ! $product->is_type( 'grouped' ) ) {
		$GLOBALS['ao_warn'] = true;
	}
	ao_sync();
}

add_filter( 'redirect_post_location', function ( $location ) {
	return ! empty( $GLOBALS['ao_warn'] ) ? add_query_arg( 'ao_warn', 1, $location ) : $location;
} );

add_action( 'admin_notices', function () {
	if ( isset( $_GET['ao_warn'] ) ) {
		echo '<div class="notice notice-warning is-dismissible"><p>این محصول در «فروش شگفت‌انگیز» علامت خورد، اما هنوز قیمت حراج فعال ندارد. «درصد تخفیف» یا «قیمت حراج» را وارد کنید تا با درصد تخفیف نمایش داده شود.</p></div>';
	}
	if ( isset( $_GET['ao_bulk'] ) ) {
		printf( '<div class="notice notice-success is-dismissible"><p>فروش شگفت‌انگیز روی %d محصول به‌روزرسانی شد.</p></div>', (int) $_GET['ao_bulk'] );
	}
} );

/* ------------------------------------------------------------------
 * 3) ستون و عملیات گروهی در لیست محصولات
 * ---------------------------------------------------------------- */
add_filter( 'manage_edit-product_columns', function ( $cols ) {
	$out = [];
	foreach ( $cols as $k => $v ) {
		$out[ $k ] = $v;
		if ( 'name' === $k ) {
			$out['ao'] = 'شگفت‌انگیز';
		}
	}
	return $out;
} );

add_action( 'manage_product_posts_custom_column', function ( $col, $post_id ) {
	if ( 'ao' === $col ) {
		echo 'yes' === get_post_meta( $post_id, AO_META, true ) ? '<span style="color:#d63638;font-weight:700">🔥 فعال</span>' : '<span aria-hidden="true">—</span>';
	}
}, 10, 2 );

add_filter( 'bulk_actions-edit-product', function ( $a ) {
	$a['ao_add']    = 'افزودن به فروش شگفت‌انگیز';
	$a['ao_remove'] = 'حذف از فروش شگفت‌انگیز';
	return $a;
} );

add_filter( 'handle_bulk_actions-edit-product', function ( $redirect, $action, $ids ) {
	if ( 'ao_add' !== $action && 'ao_remove' !== $action ) {
		return $redirect;
	}
	$n = 0;
	foreach ( (array) $ids as $id ) {
		if ( ! current_user_can( 'edit_post', $id ) ) {
			continue;
		}
		$p = wc_get_product( $id );
		if ( ! $p ) {
			continue;
		}
		if ( 'ao_add' === $action ) {
			$p->update_meta_data( AO_META, 'yes' );
		} else {
			$p->delete_meta_data( AO_META );
		}
		$p->save();
		$n++;
	}
	ao_sync();
	return add_query_arg( 'ao_bulk', $n, $redirect );
}, 10, 3 );

/* ------------------------------------------------------------------
 * 4) همگام‌سازی فهرست باکس صفحه اصلی با محصولات علامت‌خورده
 * ---------------------------------------------------------------- */
function ao_flagged_ids() {
	return array_map( 'intval', get_posts( [
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'fields'         => 'ids',
		'posts_per_page' => -1,
		'no_found_rows'  => true,
		'meta_key'       => AO_META,
		'meta_value'     => 'yes',
		'orderby'        => 'modified',
		'order'          => 'DESC',
	] ) );
}

function ao_sync() {
	static $busy = false;
	if ( $busy ) {
		return;
	}
	$busy = true;

	$page = (int) get_option( 'page_on_front' );
	$raw  = $page ? get_post_meta( $page, 'irantheme_page_sections', true ) : '';
	$data = ( is_string( $raw ) && '' !== $raw ) ? json_decode( $raw ) : null;

	if ( is_object( $data ) && ! empty( $data->sections_items ) && is_array( $data->sections_items ) ) {
		$ids     = ao_flagged_ids();
		$sel     = implode( ',', $ids );
		$changed = false;

		foreach ( $data->sections_items as $item ) {
			if ( ! is_object( $item ) || ( $item->data_value ?? '' ) !== AO_SECTION || empty( $item->section_fields ) || ! is_object( $item->section_fields ) ) {
				continue;
			}
			$f = $item->section_fields;
			if ( ( $f->product_content_type_radio ?? '' ) !== 'custom' ) {
				$f->product_content_type_radio = 'custom';
				$changed = true;
			}
			if ( ( $f->product_selection ?? '' ) !== $sel ) {
				$f->product_selection = $sel;
				$changed = true;
			}
			$count = max( (int) ( $f->product_count_number ?? 0 ), count( $ids ) );
			if ( (string) $count !== (string) ( $f->product_count_number ?? '' ) ) {
				$f->product_count_number = (string) $count;
				$changed = true;
			}
		}

		if ( $changed ) {
			update_post_meta( $page, 'irantheme_page_sections', wp_slash( wp_json_encode( $data ) ) );
		}
	}

	$busy = false;
}

add_action( 'transition_post_status', function ( $new, $old, $post ) {
	if ( 'product' === $post->post_type && $new !== $old ) {
		ao_sync();
	}
}, 10, 3 );
add_action( 'woocommerce_update_product', 'ao_sync' );
add_action( 'woocommerce_new_product', 'ao_sync' );
add_action( 'deleted_post', function ( $id ) {
	if ( 'product' === get_post_type( $id ) ) {
		ao_sync();
	}
} );
