<?php
/**
 * PDP admin: product data tab, panel markup, save handlers.
 * Loaded from product-pdp-content.php so helpers stay shared.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register product data tab.
 *
 * @param array $tabs Tabs.
 * @return array
 */
function storeplus_child_pdp_admin_tab( $tabs ) {
	$tabs['sp_pdp'] = array(
		'label'    => 'صفحه محصول',
		'target'   => 'sp_pdp_product_data',
		'class'    => array( 'show_if_simple', 'show_if_variable', 'show_if_external' ),
		'priority' => 75,
	);
	return $tabs;
}
add_filter( 'woocommerce_product_data_tabs', 'storeplus_child_pdp_admin_tab' );

/**
 * Tab panel markup.
 */
function storeplus_child_pdp_admin_panel() {
	$product_id = get_the_ID();
	$vis        = storeplus_child_pdp_visibility( $product_id );
	$health     = storeplus_child_pdp_health_data( $product_id );
	$defs       = storeplus_child_pdp_section_defs();
	$groups     = array();
	foreach ( $defs as $key => $def ) {
		$groups[ $def['group'] ][ $key ] = $def;
	}
	?>
	<div id="sp_pdp_product_data" class="panel woocommerce_options_panel hidden">
		<input type="hidden" name="sp_pdp_panel" value="1" />
		<?php wp_nonce_field( 'sp_pdp_save', 'sp_pdp_nonce' ); ?>
		<div class="options_group">
			<p class="form-field" style="padding-inline:12px;">
				<strong>نمایش بخش‌های صفحه محصول</strong><br>
				<span class="description">برای هر محصول مشخص کنید چه چیزی به مشتری نشان داده شود. اگر تیک نباشد، آن بخش روی فرانت مخفی می‌شود (حتی اگر محتوا داشته باشد).</span>
			</p>
			<?php foreach ( $groups as $group_label => $items ) : ?>
				<p class="form-field" style="padding-inline:12px;margin-bottom:4px;">
					<strong style="color:#0f8a3c;"><?php echo esc_html( $group_label ); ?></strong>
				</p>
				<div class="sp-pdp-vis-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:4px 16px;padding:0 12px 12px;">
					<?php foreach ( $items as $key => $def ) : ?>
						<label style="display:flex;align-items:flex-start;gap:8px;margin:0;padding:6px 0;cursor:pointer;">
							<input type="hidden" name="sp_pdp_vis[<?php echo esc_attr( $key ); ?>]" value="0" />
							<input type="checkbox"
								name="sp_pdp_vis[<?php echo esc_attr( $key ); ?>]"
								value="1"
								<?php checked( ! empty( $vis[ $key ] ) ); ?>
							/>
							<span><?php echo esc_html( $def['label'] ); ?></span>
						</label>
					<?php endforeach; ?>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="options_group">
			<p class="form-field" style="padding-inline:12px;">
				<strong>محتوای سلامت (رستا دارو)</strong><br>
				<span class="description">این فیلدها مخصوص مکمل/دارو/بهداشت هستند. فقط وقتی پر شوند و تیک نمایش مربوطه فعال باشد، روی صفحه محصول دیده می‌شوند.</span>
			</p>
			<?php
			woocommerce_wp_text_input(
				array(
					'id'          => SP_PDP_NAME_EN_KEY,
					'label'       => 'نام انگلیسی',
					'description' => 'زیر عنوان فارسی نمایش داده می‌شود (مثل ژن‌سبز)',
					'desc_tip'    => true,
					'placeholder' => 'Creatine Monohydrate Powder',
					'value'       => $health['name_en'],
				)
			);
			woocommerce_wp_textarea_input(
				array(
					'id'          => SP_PDP_ABOUT_KEY,
					'label'       => 'درباره محصول / توضیحات',
					'description' => 'باکس اصلی «درباره محصول» زیر صفحه. اگر خالی باشد اصلاً نمایش داده نمی‌شود.',
					'desc_tip'    => true,
					'placeholder' => 'توضیحات کامل محصول برای کاربر…',
					'rows'        => 8,
					'value'       => $health['about'],
				)
			);
			woocommerce_wp_text_input(
				array(
					'id'          => SP_PDP_FORM_KEY,
					'label'       => 'شکل مصرف / نوع محصول',
					'description' => 'مثلاً کپسول، قرص، شربت، ساشه، کرم، پودر…',
					'desc_tip'    => true,
					'placeholder' => 'قرص',
					'value'       => $health['form'],
				)
			);
			woocommerce_wp_text_input(
				array(
					'id'          => SP_PDP_PACK_KEY,
					'label'       => 'تعداد / وزن بسته',
					'description' => 'مثلاً ۶۰ عدد، ۳۳۰ گرم',
					'desc_tip'    => true,
					'placeholder' => '۶۰ عدد',
					'value'       => $health['pack'],
				)
			);
			woocommerce_wp_text_input(
				array(
					'id'          => SP_PDP_DOSE_KEY,
					'label'       => 'دوز / ماده مؤثر شاخص',
					'description' => 'مثلاً منیزیم ۴۰۰ میلی‌گرم در هر وعده',
					'desc_tip'    => true,
					'value'       => $health['dose'],
				)
			);
			woocommerce_wp_text_input(
				array(
					'id'          => SP_PDP_AGE_KEY,
					'label'       => 'رده سنی',
					'description' => 'مثلاً بالای ۱۲ سال، بزرگسالان',
					'desc_tip'    => true,
					'value'       => $health['age'],
				)
			);
			woocommerce_wp_text_input(
				array(
					'id'          => SP_PDP_GENDER_KEY,
					'label'       => 'جنسیت مصرف',
					'description' => 'عمومی، بانوان، آقایان…',
					'desc_tip'    => true,
					'placeholder' => 'عمومی',
					'value'       => $health['gender'],
				)
			);
			woocommerce_wp_text_input(
				array(
					'id'          => SP_PDP_ORIGIN_KEY,
					'label'       => 'کشور سازنده',
					'desc_tip'    => true,
					'value'       => $health['origin'],
				)
			);
			woocommerce_wp_text_input(
				array(
					'id'          => SP_PDP_MANUFACTURER_KEY,
					'label'       => 'شرکت سازنده',
					'desc_tip'    => true,
					'placeholder' => 'لابراتور رازک',
					'value'       => $health['manufacturer'],
				)
			);
			woocommerce_wp_text_input(
				array(
					'id'          => SP_PDP_EXPIRY_KEY,
					'label'       => 'تاریخ انقضا',
					'description' => 'مثلاً ۰۵/۲۰۲۹ یا بیشتر از یکسال — در باکس خرید هم نشان داده می‌شود',
					'desc_tip'    => true,
					'placeholder' => 'بیشتر از یکسال',
					'value'       => $health['expiry'],
				)
			);
			woocommerce_wp_text_input(
				array(
					'id'          => SP_PDP_LICENSE_KEY,
					'label'       => 'کد مجوز / IRC',
					'description' => 'در صورت وجود؛ روی چیپ‌ها و نشان اعتماد نشان داده می‌شود',
					'desc_tip'    => true,
					'value'       => $health['license'],
				)
			);
			woocommerce_wp_textarea_input(
				array(
					'id'          => SP_PDP_INGREDIENTS_KEY,
					'label'       => 'ترکیبات',
					'description' => 'هر سطر یک ترکیب. فرمت پیشنهادی: نام | مقدار  — مثال: منیزیم | ۴۰۰ mg',
					'desc_tip'    => true,
					'placeholder' => "منیزیم | ۴۰۰ mg\nویتامین D3 | ۱۰۰۰ IU\nویتامین K2 | ۴۵ mcg",
					'rows'        => 6,
					'value'       => $health['ingredients'],
				)
			);
			woocommerce_wp_textarea_input(
				array(
					'id'          => SP_PDP_USAGE_KEY,
					'label'       => 'نحوه مصرف',
					'description' => 'دوز روزانه، زمان مصرف، با غذا یا بدون غذا…',
					'desc_tip'    => true,
					'rows'        => 4,
					'value'       => $health['usage'],
				)
			);
			woocommerce_wp_textarea_input(
				array(
					'id'          => SP_PDP_WARNINGS_KEY,
					'label'       => 'هشدارها و موارد احتیاط',
					'description' => 'بارداری، تداخل دارویی، منع مصرف و…',
					'desc_tip'    => true,
					'rows'        => 4,
					'value'       => $health['warnings'],
				)
			);
			woocommerce_wp_textarea_input(
				array(
					'id'          => SP_PDP_SUITABLE_KEY,
					'label'       => 'مناسب برای',
					'description' => 'هر مورد در یک خط',
					'desc_tip'    => true,
					'placeholder' => "کمبود منیزیم\nسلامت استخوان\nورزشکاران",
					'rows'        => 4,
					'value'       => $health['suitable'],
				)
			);
			woocommerce_wp_textarea_input(
				array(
					'id'          => SP_PDP_NOT_SUITABLE_KEY,
					'label'       => 'مناسب نیست برای / منع مصرف',
					'description' => 'هر مورد در یک خط',
					'desc_tip'    => true,
					'placeholder' => "کودکان زیر ۴ سال\nبدون مشورت پزشک در بارداری",
					'rows'        => 3,
					'value'       => $health['not_suitable'],
				)
			);
			woocommerce_wp_textarea_input(
				array(
					'id'          => SP_PDP_STORAGE_KEY,
					'label'       => 'شرایط نگهداری',
					'description' => 'هر نکته در یک خط',
					'desc_tip'    => true,
					'placeholder' => "در جای خشک و خنک نگهداری شود\nدور از دسترس کودکان",
					'rows'        => 3,
					'value'       => $health['storage'],
				)
			);
			woocommerce_wp_textarea_input(
				array(
					'id'          => SP_PDP_FAQ_KEY,
					'label'       => 'سوالات متداول',
					'description' => 'هر سطر: سوال :: پاسخ',
					'desc_tip'    => true,
					'placeholder' => "بهترین زمان مصرف؟ :: همراه وعده غذایی\nبرای بارداری مناسب است؟ :: فقط با نظر پزشک",
					'rows'        => 6,
					'value'       => $health['faq'],
				)
			);
			?>
		</div>
	</div>
	<?php
}
add_action( 'woocommerce_product_data_panels', 'storeplus_child_pdp_admin_panel' );

/**
 * Save product PDP meta.
 *
 * @param int $product_id Product ID.
 */
function storeplus_child_pdp_save_product( $product_id ) {
	// Only save when our panel was part of the request (avoids wiping settings on unrelated saves).
	if ( empty( $_POST['sp_pdp_panel'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
		return;
	}
	if ( empty( $_POST['sp_pdp_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['sp_pdp_nonce'] ) ), 'sp_pdp_save' ) ) {
		return;
	}

	$defs   = storeplus_child_pdp_section_defs();
	$vis    = array();
	$posted = isset( $_POST['sp_pdp_vis'] ) && is_array( $_POST['sp_pdp_vis'] )
		? wp_unslash( $_POST['sp_pdp_vis'] )
		: array();

	foreach ( $defs as $key => $def ) {
		// Hidden+checkbox: last value wins; "1" = show, anything else = hide.
		$vis[ $key ] = ( isset( $posted[ $key ] ) && '1' === (string) $posted[ $key ] ) ? '1' : '0';
	}
	update_post_meta( $product_id, SP_PDP_VIS_KEY, wp_json_encode( $vis ) );

	$text_keys = array(
		SP_PDP_FORM_KEY,
		SP_PDP_PACK_KEY,
		SP_PDP_DOSE_KEY,
		SP_PDP_AGE_KEY,
		SP_PDP_ORIGIN_KEY,
		SP_PDP_LICENSE_KEY,
		SP_PDP_NAME_EN_KEY,
		SP_PDP_MANUFACTURER_KEY,
		SP_PDP_EXPIRY_KEY,
		SP_PDP_GENDER_KEY,
	);
	foreach ( $text_keys as $key ) {
		$val = isset( $_POST[ $key ] ) ? sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) : '';
		update_post_meta( $product_id, $key, $val );
	}

	$area_keys = array(
		SP_PDP_ABOUT_KEY,
		SP_PDP_INGREDIENTS_KEY,
		SP_PDP_USAGE_KEY,
		SP_PDP_WARNINGS_KEY,
		SP_PDP_SUITABLE_KEY,
		SP_PDP_NOT_SUITABLE_KEY,
		SP_PDP_STORAGE_KEY,
		SP_PDP_FAQ_KEY,
	);
	foreach ( $area_keys as $key ) {
		$val = isset( $_POST[ $key ] ) ? sanitize_textarea_field( wp_unslash( $_POST[ $key ] ) ) : '';
		update_post_meta( $product_id, $key, $val );
	}
}
add_action( 'woocommerce_process_product_meta', 'storeplus_child_pdp_save_product' );
