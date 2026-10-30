<?php
/**
 * Brand <-> product category assignment.
 *
 * Each WooCommerce brand (taxonomy product_brand) can be assigned to one or more product categories
 * (taxonomy product_cat). Edited on Products > Brands (add form, edit form, and a column in the list).
 *
 * Storage: term meta "_sp_brand_cats" on the brand = array of product_cat term IDs.
 * Helpers:
 *   storeplus_child_brand_category_ids( $brand_id )   -> int[] category IDs of a brand
 *   storeplus_child_brand_ids_by_category( $cat_id )  -> int[] brand IDs assigned to a category
 * The public brands REST route (storeplus/v1/brands) also returns "categories" (IDs) when that API is hit.
 * The homepage carousel uses the cached inline payload from inc/product-brands.php (no REST on page view).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const SP_BRAND_CATS_META = '_sp_brand_cats';

/* ---------------------------------------------------------------------------
 * Helpers
 * ------------------------------------------------------------------------- */
function storeplus_child_brand_category_ids( $brand_id ) {
	$ids = get_term_meta( (int) $brand_id, SP_BRAND_CATS_META, true );
	if ( ! is_array( $ids ) ) {
		return array();
	}
	return array_values( array_unique( array_filter( array_map( 'absint', $ids ) ) ) );
}

function storeplus_child_brand_ids_by_category( $cat_id ) {
	$cat_id = absint( $cat_id );
	if ( ! $cat_id || ! taxonomy_exists( 'product_brand' ) ) {
		return array();
	}
	$brands = get_terms(
		array(
			'taxonomy'   => 'product_brand',
			'hide_empty' => false,
			'fields'     => 'ids',
		)
	);
	if ( is_wp_error( $brands ) ) {
		return array();
	}
	$out = array();
	foreach ( $brands as $brand_id ) {
		if ( in_array( $cat_id, storeplus_child_brand_category_ids( $brand_id ), true ) ) {
			$out[] = (int) $brand_id;
		}
	}
	return $out;
}

/* ---------------------------------------------------------------------------
 * Admin fields
 * ------------------------------------------------------------------------- */
function storeplus_child_brand_cats_checklist( $selected ) {
	$cats = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => false,
			'orderby'    => 'name',
		)
	);
	if ( is_wp_error( $cats ) || empty( $cats ) ) {
		echo '<p class="description">هنوز دسته‌بندی محصولی ساخته نشده است.</p>';
		return;
	}

	// Parents first, children indented under them.
	$by_parent = array();
	foreach ( $cats as $cat ) {
		$by_parent[ (int) $cat->parent ][] = $cat;
	}

	echo '<div style="max-height:220px;overflow:auto;border:1px solid #dcdcde;border-radius:6px;padding:8px 12px;background:#fff">';
	$render = function ( $parent, $depth ) use ( &$render, $by_parent, $selected ) {
		if ( empty( $by_parent[ $parent ] ) ) {
			return;
		}
		foreach ( $by_parent[ $parent ] as $cat ) {
			printf(
				'<label style="display:block;margin:3px 0;padding-inline-start:%dpx"><input type="checkbox" name="sp_brand_cats[]" value="%d" %s> %s</label>',
				(int) $depth * 18,
				(int) $cat->term_id,
				checked( in_array( (int) $cat->term_id, $selected, true ), true, false ),
				esc_html( $cat->name )
			);
			$render( (int) $cat->term_id, $depth + 1 );
		}
	};
	$render( 0, 0 );
	echo '</div>';
}

function storeplus_child_brand_cats_add_field() {
	wp_nonce_field( 'sp_brand_cats_save', 'sp_brand_cats_nonce' );
	?>
	<div class="form-field">
		<label>دسته‌بندی‌های برند</label>
		<?php storeplus_child_brand_cats_checklist( array() ); ?>
		<p class="description">این برند به چه دسته‌بندی‌هایی تعلق دارد؟ می‌توانید چند دسته انتخاب کنید.</p>
	</div>
	<?php
}
add_action( 'product_brand_add_form_fields', 'storeplus_child_brand_cats_add_field' );

function storeplus_child_brand_cats_edit_field( $term ) {
	wp_nonce_field( 'sp_brand_cats_save', 'sp_brand_cats_nonce' );
	?>
	<tr class="form-field">
		<th scope="row"><label>دسته‌بندی‌های برند</label></th>
		<td>
			<?php storeplus_child_brand_cats_checklist( storeplus_child_brand_category_ids( $term->term_id ) ); ?>
			<p class="description">این برند به چه دسته‌بندی‌هایی تعلق دارد؟ می‌توانید چند دسته انتخاب کنید.</p>
		</td>
	</tr>
	<?php
}
add_action( 'product_brand_edit_form_fields', 'storeplus_child_brand_cats_edit_field' );

function storeplus_child_brand_cats_save( $term_id ) {
	if ( ! isset( $_POST['sp_brand_cats_nonce'] ) || ! wp_verify_nonce( $_POST['sp_brand_cats_nonce'], 'sp_brand_cats_save' ) ) {
		return;
	}
	if ( ! current_user_can( 'manage_product_terms' ) && ! current_user_can( 'manage_categories' ) ) {
		return;
	}

	$ids = isset( $_POST['sp_brand_cats'] ) && is_array( $_POST['sp_brand_cats'] ) ? array_map( 'absint', wp_unslash( $_POST['sp_brand_cats'] ) ) : array();
	$ids = array_values( array_unique( array_filter( $ids, function ( $id ) {
		return $id && term_exists( $id, 'product_cat' );
	} ) ) );

	if ( $ids ) {
		update_term_meta( $term_id, SP_BRAND_CATS_META, $ids );
	} else {
		delete_term_meta( $term_id, SP_BRAND_CATS_META );
	}
}
add_action( 'created_product_brand', 'storeplus_child_brand_cats_save' );
add_action( 'edited_product_brand', 'storeplus_child_brand_cats_save' );

/* ---------------------------------------------------------------------------
 * Brands list: "دسته‌بندی‌ها" column
 * ------------------------------------------------------------------------- */
function storeplus_child_brand_cats_columns( $columns ) {
	$columns['sp_brand_cats'] = 'دسته‌بندی‌ها';
	return $columns;
}
add_filter( 'manage_edit-product_brand_columns', 'storeplus_child_brand_cats_columns' );

function storeplus_child_brand_cats_column( $content, $column, $term_id ) {
	if ( 'sp_brand_cats' !== $column ) {
		return $content;
	}
	$names = array();
	foreach ( storeplus_child_brand_category_ids( $term_id ) as $cat_id ) {
		$cat = get_term( $cat_id, 'product_cat' );
		if ( $cat && ! is_wp_error( $cat ) ) {
			$names[] = esc_html( $cat->name );
		}
	}
	return $names ? implode( '، ', $names ) : '—';
}
add_filter( 'manage_product_brand_custom_column', 'storeplus_child_brand_cats_column', 10, 3 );

/* ---------------------------------------------------------------------------
 * REST: add "categories" (IDs) to each brand of storeplus/v1/brands
 * ------------------------------------------------------------------------- */
function storeplus_child_brand_cats_rest( $result, $server, $request ) {
	if ( '/storeplus/v1/brands' !== $request->get_route() || ! ( $result instanceof WP_REST_Response ) ) {
		return $result;
	}
	$data = $result->get_data();
	if ( ! is_array( $data ) ) {
		return $result;
	}
	foreach ( $data as $i => $brand ) {
		if ( is_array( $brand ) && isset( $brand['id'] ) ) {
			$data[ $i ]['categories'] = storeplus_child_brand_category_ids( $brand['id'] );
		}
	}
	$result->set_data( $data );
	return $result;
}
add_filter( 'rest_post_dispatch', 'storeplus_child_brand_cats_rest', 10, 3 );
