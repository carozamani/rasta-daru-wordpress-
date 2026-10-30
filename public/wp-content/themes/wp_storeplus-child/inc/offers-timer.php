<?php
/**
 * Offers box timer ("پیشنهاد شگفت انگیز"):
 *  - ONE countdown, shown in the red title column of the offers box
 *    (assets/js/offers-frame.js draws it from window.storeplusOffersTimer = { end: <ms> }).
 *  - Which time is counted down:
 *      1) the end time set by the admin in Settings > تایمر شگفت‌انگیز (if set), else
 *      2) automatically: the nearest sale end date among the products that are on sale.
 *  - Optionally the admin end time is written into the WooCommerce sale schedule
 *    ("تا تاریخ") of every product that is on sale, so all discounts end at that moment.
 *
 * Options:
 *   storeplus_offers_timer_enabled  '1' | '0'   show the countdown in the box (default '1')
 *   storeplus_offers_timer_end      'Y-m-d\TH:i' in the site's timezone, '' = automatic
 *   storeplus_offers_timer_apply    '1' | '0'   write the admin end time into the products' sale schedule
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ---------------------------------------------------------------------------
 * Helpers
 * ------------------------------------------------------------------------ */

function storeplus_offers_timer_sanitize_end( $value ) {
	$value = sanitize_text_field( (string) $value );
	if ( ! preg_match( '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/', $value ) ) {
		return '';
	}

	$date   = DateTimeImmutable::createFromFormat( '!Y-m-d\\TH:i', $value, wp_timezone() );
	$errors = DateTimeImmutable::getLastErrors();
	if ( ! $date || ( is_array( $errors ) && ( $errors['warning_count'] > 0 || $errors['error_count'] > 0 ) ) ) {
		return '';
	}

	return $date->format( 'Y-m-d\\TH:i' ) === $value ? $value : '';
}

function storeplus_offers_timer_enabled() {
	return '1' === get_option( 'storeplus_offers_timer_enabled', '1' );
}

function storeplus_offers_timer_apply_enabled() {
	return '1' === get_option( 'storeplus_offers_timer_apply', '1' );
}

/**
 * Timestamp (seconds) of the end time set by the admin, 0 when not set / already finished.
 */
function storeplus_offers_timer_manual_end_ts() {
	$end = (string) get_option( 'storeplus_offers_timer_end', '' );
	if ( '' === $end ) {
		return 0;
	}
	$dt = date_create_immutable_from_format( 'Y-m-d\TH:i', $end, wp_timezone() );
	if ( ! $dt ) {
		return 0;
	}
	$ts = $dt->getTimestamp();
	return $ts > time() ? $ts : 0;
}

/**
 * Nearest future `_sale_price_dates_to` among published products/variations that still have a sale price.
 * One SQL aggregate — no per-product object hydration.
 *
 * @return int Timestamp or 0.
 */
function storeplus_offers_timer_query_nearest_sale_end() {
	global $wpdb;

	$now = time();
	$sql = "
		SELECT MIN( CAST( to_meta.meta_value AS UNSIGNED ) )
		FROM {$wpdb->postmeta} AS to_meta
		INNER JOIN {$wpdb->posts} AS p ON p.ID = to_meta.post_id
		INNER JOIN {$wpdb->postmeta} AS sale_meta
			ON sale_meta.post_id = to_meta.post_id
			AND sale_meta.meta_key = '_sale_price'
			AND sale_meta.meta_value <> ''
		LEFT JOIN {$wpdb->postmeta} AS from_meta
			ON from_meta.post_id = to_meta.post_id
			AND from_meta.meta_key = '_sale_price_dates_from'
		WHERE to_meta.meta_key = '_sale_price_dates_to'
			AND to_meta.meta_value <> ''
			AND CAST( to_meta.meta_value AS UNSIGNED ) > %d
			AND p.post_status = 'publish'
			AND p.post_type IN ( 'product', 'product_variation' )
			AND (
				from_meta.meta_value IS NULL
				OR from_meta.meta_value = ''
				OR CAST( from_meta.meta_value AS UNSIGNED ) <= %d
			)
	";

	$min = (int) $wpdb->get_var( $wpdb->prepare( $sql, $now, $now ) );
	return $min > $now ? $min : 0;
}

/**
 * Nearest sale end date (timestamp) among products on sale right now,
 * 0 when none has an end date. Cached with a short rebuild lock (no stampede).
 */
function storeplus_offers_timer_auto_end_ts() {
	$cached = get_transient( 'storeplus_offers_timer_auto' );
	if ( is_array( $cached ) && isset( $cached['ts'] ) ) {
		$ts = (int) $cached['ts'];
		if ( 0 === $ts || $ts > time() ) {
			return $ts;
		}
	}

	// Another request is already rebuilding — avoid N parallel full queries.
	if ( get_transient( 'storeplus_offers_timer_auto_lock' ) ) {
		return is_array( $cached ) && isset( $cached['ts'] ) ? (int) $cached['ts'] : 0;
	}
	set_transient( 'storeplus_offers_timer_auto_lock', 1, 30 );

	$min = storeplus_offers_timer_query_nearest_sale_end();

	// Prefer caching until the sale ends (capped at 1 hour) so cold rebuilds stay rare.
	$ttl = HOUR_IN_SECONDS;
	if ( $min > time() ) {
		$ttl = (int) min( HOUR_IN_SECONDS, max( MINUTE_IN_SECONDS, $min - time() ) );
	}
	set_transient( 'storeplus_offers_timer_auto', array( 'ts' => $min ), $ttl );
	delete_transient( 'storeplus_offers_timer_auto_lock' );

	return $min;
}

function storeplus_offers_timer_clear_auto_cache() {
	delete_transient( 'storeplus_offers_timer_auto' );
	delete_transient( 'storeplus_offers_timer_auto_lock' );
}
add_action( 'woocommerce_new_product', 'storeplus_offers_timer_clear_auto_cache' );
add_action( 'woocommerce_update_product', 'storeplus_offers_timer_clear_auto_cache' );
add_action( 'woocommerce_new_product_variation', 'storeplus_offers_timer_clear_auto_cache' );
add_action( 'woocommerce_update_product_variation', 'storeplus_offers_timer_clear_auto_cache' );

/**
 * End timestamp (seconds) shown by the countdown in the box, or 0 for no countdown.
 */
function storeplus_offers_timer_end_ts() {
	if ( ! storeplus_offers_timer_enabled() ) {
		return 0;
	}
	$manual = storeplus_offers_timer_manual_end_ts();
	if ( $manual ) {
		return $manual;
	}
	return storeplus_offers_timer_auto_end_ts();
}

/* ---------------------------------------------------------------------------
 * Apply the admin end time to the discounted products
 * ------------------------------------------------------------------------ */

/**
 * Put the end time on one product / variation that has a sale price.
 * Returns true when the product was changed (and needs saving).
 */
function storeplus_offers_timer_apply_to( $product, $end_ts ) {
	if ( '' === (string) $product->get_sale_price( 'edit' ) ) {
		return false;
	}
	$product->set_date_on_sale_to( $end_ts );
	if ( ! $product->get_date_on_sale_from( 'edit' ) ) {
		$product->set_date_on_sale_from( time() - 60 ); // sale already running
	}
	return true;
}

/**
 * Every product that is on sale right now gets the same sale end date.
 * Returns the number of products that were updated.
 */
function storeplus_offers_timer_sync_products( $end_ts ) {
	if ( ! function_exists( 'wc_get_product_ids_on_sale' ) ) {
		return 0;
	}

	$count   = 0;
	$parents = array();
	$ids     = array_unique( array_map( 'intval', (array) wc_get_product_ids_on_sale() ) );

	foreach ( $ids as $id ) {
		$product = wc_get_product( $id );
		if ( ! $product ) {
			continue;
		}

		if ( $product->is_type( 'variable' ) ) {
			$changed = false;
			foreach ( $product->get_children() as $variation_id ) {
				$variation = wc_get_product( $variation_id );
				if ( $variation && storeplus_offers_timer_apply_to( $variation, $end_ts ) ) {
					$variation->save();
					$changed = true;
				}
			}
			if ( $changed ) {
				$parents[ $id ] = true;
				$count++;
			}
		} elseif ( $product->is_type( 'variation' ) ) {
			if ( storeplus_offers_timer_apply_to( $product, $end_ts ) ) {
				$product->save();
				$parents[ $product->get_parent_id() ] = true;
			}
		} elseif ( storeplus_offers_timer_apply_to( $product, $end_ts ) ) {
			$product->save();
			$count++;
		}
	}

	if ( class_exists( 'WC_Product_Variable' ) ) {
		foreach ( array_keys( $parents ) as $parent_id ) {
			if ( $parent_id ) {
				WC_Product_Variable::sync( $parent_id );
			}
		}
	}
	if ( function_exists( 'wc_delete_product_transients' ) ) {
		wc_delete_product_transients( 0 );
	}
	storeplus_offers_timer_clear_auto_cache();

	return $count;
}

/**
 * Products that get a sale price later (edited in the admin) take the admin end date too,
 * unless the shop manager already chose an end date for them.
 */
function storeplus_offers_timer_on_product_save( $product ) {
	$end = storeplus_offers_timer_manual_end_ts();
	if ( ! $end || ! storeplus_offers_timer_enabled() || ! storeplus_offers_timer_apply_enabled() ) {
		return;
	}
	if ( $product->is_type( 'variable' ) || $product->get_date_on_sale_to( 'edit' ) ) {
		return;
	}
	storeplus_offers_timer_apply_to( $product, $end );
}
add_action( 'woocommerce_admin_process_product_object', 'storeplus_offers_timer_on_product_save' );

function storeplus_offers_timer_on_variation_save( $variation ) {
	storeplus_offers_timer_on_product_save( $variation );
}
add_action( 'woocommerce_admin_process_variation_object', 'storeplus_offers_timer_on_variation_save' );

/**
 * WooCommerce reverts the prices only in its daily job. This runs it right after the end
 * time, so the discounts stop at the moment the timer reaches zero.
 */
function storeplus_offers_timer_expire() {
	if ( function_exists( 'wc_scheduled_sales' ) ) {
		wc_scheduled_sales();
	}
}
add_action( 'storeplus_offers_timer_expire', 'storeplus_offers_timer_expire' );

/* ---------------------------------------------------------------------------
 * Admin page
 * ------------------------------------------------------------------------ */

function storeplus_offers_timer_menu() {
	add_options_page(
		'تایمر پیشنهاد شگفت‌انگیز',
		'تایمر شگفت‌انگیز',
		'manage_options',
		'storeplus-offers-timer',
		'storeplus_offers_timer_render_page'
	);
}
add_action( 'admin_menu', 'storeplus_offers_timer_menu' );

function storeplus_offers_timer_handle_save() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'دسترسی ندارید.', 403 );
	}
	check_admin_referer( 'storeplus_offers_timer_save' );

	$enabled = ( isset( $_POST['enabled'] ) && '1' === wp_unslash( $_POST['enabled'] ) ) ? '1' : '0';
	$apply   = ( isset( $_POST['apply'] ) && '1' === wp_unslash( $_POST['apply'] ) ) ? '1' : '0';
	$end     = storeplus_offers_timer_sanitize_end( isset( $_POST['end'] ) ? wp_unslash( $_POST['end'] ) : '' );

	update_option( 'storeplus_offers_timer_enabled', $enabled );
	update_option( 'storeplus_offers_timer_apply', $apply );
	update_option( 'storeplus_offers_timer_end', $end );

	$synced = 0;
	wp_clear_scheduled_hook( 'storeplus_offers_timer_expire' );

	$end_ts = storeplus_offers_timer_manual_end_ts();
	if ( $end_ts ) {
		wp_schedule_single_event( $end_ts + 5, 'storeplus_offers_timer_expire' );
		if ( '1' === $enabled && '1' === $apply ) {
			$synced = storeplus_offers_timer_sync_products( $end_ts );
		}
	}
	storeplus_offers_timer_clear_auto_cache();

	wp_safe_redirect(
		add_query_arg(
			array(
				'page'   => 'storeplus-offers-timer',
				'saved'  => 1,
				'synced' => $synced,
			),
			admin_url( 'options-general.php' )
		)
	);
	exit;
}
add_action( 'admin_post_storeplus_offers_timer_save', 'storeplus_offers_timer_handle_save' );

function storeplus_offers_timer_render_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$enabled   = storeplus_offers_timer_enabled();
	$apply     = storeplus_offers_timer_apply_enabled();
	$end       = (string) get_option( 'storeplus_offers_timer_end', '' );
	$now       = wp_date( 'Y-m-d\TH:i' );
	$manual_ts = storeplus_offers_timer_manual_end_ts();
	$auto_ts   = storeplus_offers_timer_auto_end_ts();
	$shown_ts  = storeplus_offers_timer_end_ts();
	$saved     = isset( $_GET['saved'] );
	$synced    = isset( $_GET['synced'] ) ? absint( $_GET['synced'] ) : 0;
	?>
	<div class="wrap" dir="rtl">
		<h1>تایمر پیشنهاد شگفت‌انگیز</h1>
		<p>یک شمارندهٔ زنده (ساعت : دقیقه : ثانیه) داخل باکس قرمز «پیشنهاد شگفت‌انگیز» در صفحه اصلی نمایش داده می‌شود و نشان می‌دهد همهٔ محصولات آن باکس چقدر فرصت دارند. اگر زمان پایان را خودتان تعیین کنید همان زمان شمرده می‌شود؛ اگر خالی بگذارید، نزدیک‌ترین زمان پایان تخفیفِ خودِ محصولات شمرده می‌شود.</p>

		<?php if ( $saved ) : ?>
			<div class="notice notice-success is-dismissible"><p>
				تنظیمات ذخیره شد.
				<?php if ( $synced ) : ?>
					تاریخ پایان تخفیف روی <strong><?php echo esc_html( number_format_i18n( $synced ) ); ?></strong> محصول تخفیف‌دار اعمال شد.
				<?php endif; ?>
			</p></div>
		<?php endif; ?>

		<?php if ( $enabled && $shown_ts ) : ?>
			<div class="notice notice-info inline"><p>
				شمارنده در باکس نمایش داده می‌شود و در
				<strong><?php echo esc_html( wp_date( 'Y/m/d - H:i', $shown_ts ) ); ?></strong>
				(به وقت سایت) تمام می‌شود<?php echo $manual_ts ? '.' : ' (خودکار، از روی نزدیک‌ترین پایان تخفیف محصولات).'; ?>
			</p></div>
		<?php elseif ( $enabled ) : ?>
			<div class="notice notice-warning inline"><p>الان شمارنده‌ای نمایش داده نمی‌شود: زمان پایانی تعیین نشده و هیچ محصول تخفیف‌داری هم تاریخ پایان حراج ندارد.</p></div>
		<?php endif; ?>

		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="storeplus_offers_timer_save">
			<?php wp_nonce_field( 'storeplus_offers_timer_save' ); ?>

			<table class="form-table" role="presentation">
				<tr>
					<th scope="row">نمایش تایمر</th>
					<td>
						<label>
							<input type="checkbox" name="enabled" value="1" <?php checked( $enabled ); ?>>
							شمارنده در باکس پیشنهاد شگفت‌انگیز نمایش داده شود
						</label>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="storeplus_offers_timer_end">پایان تخفیف‌ها</label></th>
					<td>
						<input type="datetime-local" id="storeplus_offers_timer_end" name="end"
							value="<?php echo esc_attr( $end ); ?>" style="direction:ltr;text-align:left;">
						<button type="button" class="button" id="storeplus_offers_timer_clear">خودکار (پاک کردن)</button>
						<p class="description">
							تاریخ و ساعت پایان به وقت سایت (<?php echo esc_html( wp_timezone_string() ); ?>) است.
							الان: <span dir="ltr"><?php echo esc_html( str_replace( 'T', ' ', $now ) ); ?></span>
							<?php if ( $auto_ts ) : ?>
								<br>نزدیک‌ترین پایان تخفیف محصولات: <span dir="ltr"><?php echo esc_html( wp_date( 'Y/m/d H:i', $auto_ts ) ); ?></span>
							<?php endif; ?>
						</p>
						<p style="margin-top:10px">
							میان‌بر:
							<button type="button" class="button" data-hours="6">۶ ساعت دیگر</button>
							<button type="button" class="button" data-hours="24">۲۴ ساعت دیگر</button>
							<button type="button" class="button" data-hours="72">۳ روز دیگر</button>
							<button type="button" class="button" data-hours="168">۷ روز دیگر</button>
						</p>
					</td>
				</tr>
				<tr>
					<th scope="row">اعمال روی محصولات</th>
					<td>
						<label>
							<input type="checkbox" name="apply" value="1" <?php checked( $apply ); ?>>
							زمانی که خودتان تعیین کرده‌اید به‌عنوان «تاریخ پایان حراج» روی همهٔ محصولات تخفیف‌دار ثبت شود
						</label>
						<p class="description">
							فقط وقتی کار می‌کند که بالا یک زمان پایان تعیین کرده باشید. با ذخیره، تاریخ پایان همهٔ محصولاتی که الان قیمت حراج دارند همین زمان می‌شود، و محصولاتی که بعداً حراج می‌خورند هم (اگر برایشان تاریخ پایان جداگانه نگذاشته‌اید) همین زمان را می‌گیرند.
							<strong>بعد از رسیدن به این زمان، تخفیف‌ها تمام می‌شود و قیمت محصولات به قیمت اصلی برمی‌گردد.</strong>
							اگر فقط می‌خواهید شمارنده نمایش داده شود و قیمت‌ها را خودتان مدیریت کنید، این گزینه را خاموش کنید.
						</p>
					</td>
				</tr>
			</table>
			<?php submit_button( 'ذخیره و اعمال' ); ?>
		</form>
	</div>

	<script>
	(function () {
		var input = document.getElementById('storeplus_offers_timer_end');
		var now = <?php echo wp_json_encode( $now ); ?>; // site time, "YYYY-MM-DDTHH:mm"
		function pad(n) { return (n < 10 ? '0' : '') + n; }
		function plusHours(h) {
			var m = now.match(/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2})$/);
			if (!m) return '';
			var d = new Date(+m[1], +m[2] - 1, +m[3], +m[4], +m[5]);
			d.setHours(d.getHours() + h);
			return d.getFullYear() + '-' + pad(d.getMonth() + 1) + '-' + pad(d.getDate()) +
				'T' + pad(d.getHours()) + ':' + pad(d.getMinutes());
		}
		document.querySelectorAll('button[data-hours]').forEach(function (btn) {
			btn.addEventListener('click', function () {
				input.value = plusHours(parseInt(btn.getAttribute('data-hours'), 10));
			});
		});
		document.getElementById('storeplus_offers_timer_clear').addEventListener('click', function () {
			input.value = '';
		});
	})();
	</script>
	<?php
}

/* ---------------------------------------------------------------------------
 * Front end: hand the end time to offers-frame.js
 * ------------------------------------------------------------------------ */

function storeplus_offers_timer_front_data() {
	// Offers countdown only appears on the home offers box and PDP discovery rails.
	$need = is_front_page() || ( function_exists( 'is_product' ) && is_product() );
	if ( ! $need || ! wp_script_is( 'storeplus-child-offers-frame', 'enqueued' ) ) {
		return;
	}
	$end_ts = storeplus_offers_timer_end_ts();
	if ( ! $end_ts ) {
		return;
	}
	wp_add_inline_script(
		'storeplus-child-offers-frame',
		'window.storeplusOffersTimer = ' . wp_json_encode( array( 'end' => $end_ts * 1000 ) ) . ';',
		'before'
	);
}
add_action( 'wp_enqueue_scripts', 'storeplus_offers_timer_front_data', 21 );
