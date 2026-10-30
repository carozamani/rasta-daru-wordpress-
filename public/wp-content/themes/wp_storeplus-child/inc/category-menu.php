<?php
/**
 * Header dropdown menu ("دسته بندی محصولات") built automatically from the WooCommerce product categories.
 *
 * - The menu is NOT a hand-made WordPress menu any more: it is generated from
 *   Products > Categories (names, links, order, sub-categories, category image).
 * - Order: drag & drop on Products > Categories (WooCommerce's own order).
 * - Hide one category: edit the category and untick "نمایش در منوی هدر".
 * - Settings (mode / button title / empty categories): Products > منوی دسته‌بندی هدر.
 * - The markup is exactly the one wp_nav_menu() printed before, so the theme CSS / JS (hover panel,
 *   columns, icons) keep working untouched. Hooked on the menu location "منوی کشویی"
 *   (iranthemeDropdownMainMenu) with the core filter pre_wp_nav_menu.
 * - Switch back to the old hand-made menu: Products > منوی دسته‌بندی هدر > "فهرست دستی".
 * - To disable the whole module: remove 'category-menu' from the 'inc' list in functions.php.
 *
 * Layout rules (3 levels under the button):
 *   level 1 (right list)   = main categories (with their image)
 *   level 2 (column title) = sub-categories that have their own children; sub-categories without
 *                            children are collected in a column titled with the main category name
 *   level 3 (links)        = the children of those columns
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'SP_CATMENU_OPTION' ) ) {
	define( 'SP_CATMENU_OPTION', 'sp_catmenu_settings' );
}
if ( ! defined( 'SP_CATMENU_META_HIDE' ) ) {
	define( 'SP_CATMENU_META_HIDE', '_sp_catmenu_hidden' );
}
if ( ! defined( 'SP_CATMENU_CACHE' ) ) {
	/* v2: URLs resolved per-request (relative) — old v1 cached absolute ngrok hosts. */
	define( 'SP_CATMENU_CACHE', 'sp_catmenu_blueprint_v2' );
}

/**
 * Drop the cached mega-menu blueprint (rebuilt on next header render).
 */
function sp_catmenu_flush_cache() {
	delete_transient( SP_CATMENU_CACHE );
	delete_transient( 'sp_catmenu_blueprint_v1' ); /* legacy absolute-URL cache */
}

/**
 * Prefer same-origin relative paths so Local / ngrok / production hosts never bake into HTML.
 *
 * @param string $url Absolute or relative URL.
 * @return string
 */
function sp_catmenu_relativize_url( $url ) {
	if ( function_exists( 'storeplus_child_critical_url_to_path' ) ) {
		return storeplus_child_critical_url_to_path( $url );
	}
	if ( ! is_string( $url ) || '' === $url || '#' === $url ) {
		return $url;
	}
	if ( 0 === strpos( $url, '/' ) && 0 !== strpos( $url, '//' ) ) {
		return $url;
	}
	$parts = wp_parse_url( $url );
	if ( ! is_array( $parts ) || empty( $parts['path'] ) ) {
		return $url;
	}
	$out = $parts['path'];
	if ( ! empty( $parts['query'] ) ) {
		$out .= '?' . $parts['query'];
	}
	return $out;
}

// Fake (virtual) menu item IDs: far away from real post IDs, one range per role.
if ( ! defined( 'SP_CATMENU_ID_ROOT' ) ) {
	define( 'SP_CATMENU_ID_ROOT', 1099999999 );
	define( 'SP_CATMENU_ID_TOP', 1100000000 );
	define( 'SP_CATMENU_ID_HEAD_OWN', 1200000000 );
	define( 'SP_CATMENU_ID_HEAD_ROOT', 1300000000 );
	define( 'SP_CATMENU_ID_LINK', 1400000000 );
}


/* ==========================================================================
   Settings
   ========================================================================== */

function sp_catmenu_defaults() {
	return array(
		'mode'       => 'auto', // auto = from product categories, manual = the old hand-made menu
		'title'      => 'دسته بندی محصولات',
		'hide_empty' => '0',    // 1 = categories without products are not shown
	);
}

function sp_catmenu_settings() {
	$saved = get_option( SP_CATMENU_OPTION, array() );
	$settings = wp_parse_args( is_array( $saved ) ? $saved : array(), sp_catmenu_defaults() );

	// The header menu must always reflect the live WooCommerce product categories.
	$settings['mode']       = 'auto';
	$settings['hide_empty'] = '0';

	return $settings;
}


/* ==========================================================================
   Data: visible categories as a tree
   ========================================================================== */

function sp_catmenu_sort( $a, $b ) {
	$oa = (int) get_term_meta( $a->term_id, 'order', true ); // WooCommerce drag & drop order
	$ob = (int) get_term_meta( $b->term_id, 'order', true );
	if ( $oa !== $ob ) {
		return $oa < $ob ? -1 : 1;
	}
	return strnatcasecmp( $a->name, $b->name );
}

/**
 * @return array parent term id => list of visible child terms (sorted). Key 0 = main categories.
 */
function sp_catmenu_get_tree() {
	if ( ! taxonomy_exists( 'product_cat' ) ) {
		return array();
	}

	$settings = sp_catmenu_settings();
	$hide_empty = ( '1' === $settings['hide_empty'] );
	$args       = array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => $hide_empty,
		// pad_counts is expensive; only needed when empty categories are hidden.
		'pad_counts' => $hide_empty,
		'orderby'    => 'name',
		'order'      => 'ASC',
	);
	$default_cat = (int) get_option( 'default_product_cat', 0 );
	if ( $default_cat && 'uncategorized' === get_term_field( 'slug', $default_cat, 'product_cat' ) ) {
		$args['exclude'] = array( $default_cat ); // "دسته بندی نشده"
	}

	$terms = get_terms( $args );
	if ( is_wp_error( $terms ) || empty( $terms ) ) {
		return array();
	}

	$by_parent = array();
	foreach ( $terms as $term ) {
		if ( '1' === get_term_meta( $term->term_id, SP_CATMENU_META_HIDE, true ) ) {
			continue; // preserve categories the store owner has explicitly hidden in the category editor
		}
		$by_parent[ (int) $term->parent ][] = $term;
	}
	foreach ( $by_parent as $parent_id => $list ) {
		usort( $list, 'sp_catmenu_sort' );
		$by_parent[ $parent_id ] = $list;
	}

	return $by_parent;
}

function sp_catmenu_image_url( $term ) {
	$thumb_id = (int) get_term_meta( $term->term_id, 'thumbnail_id', true );
	if ( $thumb_id ) {
		$url = wp_get_attachment_image_url( $thumb_id, 'thumbnail' );
		if ( $url ) {
			return sp_catmenu_relativize_url( $url );
		}
	}
	if ( function_exists( 'wc_placeholder_img_src' ) ) {
		return sp_catmenu_relativize_url( wc_placeholder_img_src( 'thumbnail' ) );
	}
	return '';
}


/* ==========================================================================
   Virtual menu items (the same fields wp_nav_menu() items have)
   ========================================================================== */

function sp_catmenu_node( $id, $parent_id, $title, $term, $has_children, $ctx ) {
	$o                         = new stdClass();
	$o->ID                     = $id;
	$o->db_id                  = $id;
	$o->menu_order             = 0;
	$o->menu_item_parent       = (string) $parent_id;
	$o->title                  = $title;
	$o->target                 = '';
	$o->attr_title             = '';
	$o->description            = '';
	$o->xfn                    = '';
	$o->current                = false;
	$o->current_item_ancestor  = false;
	$o->current_item_parent    = false;

	$classes = array( 'menu-item' );

	if ( $term ) {
		$url = get_term_link( $term );
		$o->url       = is_wp_error( $url ) ? '#' : sp_catmenu_relativize_url( $url );
		$o->type      = 'taxonomy';
		$o->object    = 'product_cat';
		$o->object_id = (int) $term->term_id;
		$classes[]    = 'menu-item-type-taxonomy';
		$classes[]    = 'menu-item-object-product_cat';

		if ( $ctx['current'] && (int) $term->term_id === $ctx['current'] ) {
			$o->current = true;
			$classes[]  = 'current-menu-item';
		} elseif ( in_array( (int) $term->term_id, $ctx['ancestors'], true ) ) {
			$o->current_item_ancestor = true;
			$classes[]                = 'current-menu-ancestor';
		}
	} else {
		$o->url       = '#';
		$o->type      = 'custom';
		$o->object    = 'custom';
		$o->object_id = $id;
		$classes[]    = 'menu-item-type-custom';
		$classes[]    = 'menu-item-object-custom';
	}

	if ( $has_children ) {
		$classes[] = 'menu-item-has-children';
	}
	$o->classes = $classes;

	return $o;
}

function sp_catmenu_name( $term ) {
	return esc_html( wp_specialchars_decode( $term->name, ENT_QUOTES ) );
}

/**
 * Build a serializable menu blueprint (no per-request current-category state).
 *
 * @return array<int, array<string, mixed>>
 */
function sp_catmenu_build_blueprint() {
	$tree = sp_catmenu_get_tree();
	if ( empty( $tree[0] ) ) {
		return array();
	}

	$settings = sp_catmenu_settings();
	$ctx      = array(
		'current'   => 0,
		'ancestors' => array(),
	);
	$rows = array();

	$root           = sp_catmenu_node( SP_CATMENU_ID_ROOT, 0, esc_html( $settings['title'] ), null, true, $ctx );
	$root->sp_root  = true;
	$rows[]         = sp_catmenu_blueprint_row( $root );

	foreach ( $tree[0] as $top ) {
		$kids   = isset( $tree[ $top->term_id ] ) ? $tree[ $top->term_id ] : array();
		$top_id = SP_CATMENU_ID_TOP + $top->term_id;

		$top_item           = sp_catmenu_node( $top_id, SP_CATMENU_ID_ROOT, sp_catmenu_name( $top ), $top, ! empty( $kids ), $ctx );
		$top_item->sp_image = sp_catmenu_image_url( $top );
		$rows[]             = sp_catmenu_blueprint_row( $top_item );

		if ( empty( $kids ) ) {
			continue;
		}

		$groups = array();
		$flat   = array();
		foreach ( $kids as $kid ) {
			$grand = isset( $tree[ $kid->term_id ] ) ? $tree[ $kid->term_id ] : array();
			if ( $grand ) {
				$groups[] = array(
					'term'     => $kid,
					'children' => $grand,
					'own'      => true,
				);
			} else {
				$flat[] = $kid;
			}
		}
		if ( $flat ) {
			array_unshift(
				$groups,
				array(
					'term'     => $top,
					'children' => $flat,
					'own'      => false,
				)
			);
		}

		foreach ( $groups as $group ) {
			$head_id = ( $group['own'] ? SP_CATMENU_ID_HEAD_OWN : SP_CATMENU_ID_HEAD_ROOT ) + $group['term']->term_id;
			$rows[]  = sp_catmenu_blueprint_row( sp_catmenu_node( $head_id, $top_id, sp_catmenu_name( $group['term'] ), $group['term'], true, $ctx ) );

			foreach ( $group['children'] as $child ) {
				$rows[] = sp_catmenu_blueprint_row( sp_catmenu_node( SP_CATMENU_ID_LINK + $child->term_id, $head_id, sp_catmenu_name( $child ), $child, false, $ctx ) );
			}
		}
	}

	return $rows;
}

/**
 * @param object $item Virtual menu item.
 * @return array<string, mixed>
 */
function sp_catmenu_blueprint_row( $item ) {
	/* Do not persist absolute hosts — taxonomy URLs are rebuilt on hydrate. */
	$image = isset( $item->sp_image ) ? sp_catmenu_relativize_url( (string) $item->sp_image ) : '';
	return array(
		'ID'               => (int) $item->ID,
		'menu_item_parent' => (string) $item->menu_item_parent,
		'title'            => (string) $item->title,
		'url'              => ( 'taxonomy' === (string) $item->type ) ? '' : sp_catmenu_relativize_url( (string) $item->url ),
		'type'             => (string) $item->type,
		'object'           => (string) $item->object,
		'object_id'        => (int) $item->object_id,
		'classes'          => array_values( array_filter( (array) $item->classes ) ),
		'sp_root'          => ! empty( $item->sp_root ),
		'sp_image'         => $image,
		'has_children'     => in_array( 'menu-item-has-children', (array) $item->classes, true ),
	);
}

/**
 * Cached blueprint for the header mega menu.
 *
 * @return array<int, array<string, mixed>>
 */
function sp_catmenu_get_blueprint() {
	static $blueprint = null;
	if ( null !== $blueprint ) {
		return $blueprint;
	}

	$cached = get_transient( SP_CATMENU_CACHE );
	if ( is_array( $cached ) ) {
		$blueprint = $cached;
		return $blueprint;
	}

	$blueprint = sp_catmenu_build_blueprint();
	set_transient( SP_CATMENU_CACHE, $blueprint, WEEK_IN_SECONDS );

	return $blueprint;
}

/**
 * @return array flat list of virtual menu items (empty when there is nothing to show)
 */
function sp_catmenu_items() {
	$blueprint = sp_catmenu_get_blueprint();
	if ( empty( $blueprint ) ) {
		return array();
	}

	$ctx = array(
		'current'   => 0,
		'ancestors' => array(),
	);
	if ( function_exists( 'is_product_category' ) && is_product_category() ) {
		$ctx['current']   = (int) get_queried_object_id();
		$ctx['ancestors'] = array_map( 'intval', get_ancestors( $ctx['current'], 'product_cat', 'taxonomy' ) );
	}

	$items = array();
	foreach ( $blueprint as $row ) {
		$o                        = new stdClass();
		$o->ID                    = (int) $row['ID'];
		$o->db_id                 = (int) $row['ID'];
		$o->menu_order            = 0;
		$o->menu_item_parent      = (string) $row['menu_item_parent'];
		$o->title                 = (string) $row['title'];
		$o->url                   = (string) $row['url'];
		$o->target                = '';
		$o->attr_title            = '';
		$o->description           = '';
		$o->xfn                   = '';
		$o->type                  = (string) $row['type'];
		$o->object                = (string) $row['object'];
		$o->object_id             = (int) $row['object_id'];
		$o->current               = false;
		$o->current_item_ancestor = false;
		$o->current_item_parent   = false;
		$o->classes               = array_values( (array) $row['classes'] );

		if ( ! empty( $row['sp_root'] ) ) {
			$o->sp_root = true;
		}
		if ( ! empty( $row['sp_image'] ) ) {
			$o->sp_image = sp_catmenu_relativize_url( (string) $row['sp_image'] );
		}

		if ( 'taxonomy' === $o->type && $o->object_id ) {
			/* Always resolve against the current host — never trust a cached absolute URL. */
			$link = get_term_link( (int) $o->object_id, 'product_cat' );
			$o->url = is_wp_error( $link ) ? '#' : sp_catmenu_relativize_url( $link );

			if ( $ctx['current'] && $o->object_id === $ctx['current'] ) {
				$o->current   = true;
				$o->classes[] = 'current-menu-item';
			} elseif ( in_array( $o->object_id, $ctx['ancestors'], true ) ) {
				$o->current_item_ancestor = true;
				$o->classes[]             = 'current-menu-ancestor';
			}
		} else {
			$o->url = sp_catmenu_relativize_url( $o->url );
		}

		$items[] = $o;
	}

	return $items;
}


/* ==========================================================================
   Output: replaces wp_nav_menu() for the "منوی کشویی" location only
   ========================================================================== */

function sp_catmenu_pre_nav_menu( $output, $args ) {
	if ( ! is_object( $args ) ) {
		return $output;
	}
	if ( empty( $args->theme_location ) || 'iranthemeDropdownMainMenu' !== $args->theme_location ) {
		return $output;
	}

	$items = sp_catmenu_items();
	if ( empty( $items ) ) {
		return ''; // never fall back to the separate, manually written menu
	}

	// root + 3 levels
	$list = walk_nav_menu_tree( $items, 4, $args );

	$wrap = isset( $args->items_wrap ) && $args->items_wrap ? $args->items_wrap : '<ul id="%1$s" class="%2$s">%3$s</ul>';
	$html = sprintf( $wrap, esc_attr( 'menu-sp-product-categories' ), esc_attr( $args->menu_class ), $list );

	if ( ! empty( $args->container ) ) {
		$html = sprintf(
			'<%1$s class="%2$s">%3$s</%1$s>',
			tag_escape( $args->container ),
			esc_attr( $args->container_class ),
			$html
		);
	}

	return $html;
}
// Run last and replace any earlier cached/demo-menu output for the actual header location.
add_filter( 'pre_wp_nav_menu', 'sp_catmenu_pre_nav_menu', 99999, 2 );

/**
 * The theme stores per-item options (mega menu on/off, "turn into button") as post meta of the REAL
 * menu items. Our items are virtual, so we answer those meta reads ourselves:
 * the button is a mega menu, no item is turned into a button.
 */
function sp_catmenu_virtual_meta( $value, $object_id, $meta_key, $single ) {
	if ( (int) $object_id < SP_CATMENU_ID_ROOT || ! is_string( $meta_key ) || '' === $meta_key ) {
		return $value;
	}
	if ( 0 === strpos( $meta_key, 'irantheme_button_style_checkbox_' ) ) {
		return $single ? 'off' : array( 'off' );
	}
	if ( SP_CATMENU_ID_ROOT === (int) $object_id && 0 === strpos( $meta_key, 'irantheme_menu_full_width_' ) ) {
		return $single ? 'on' : array( 'on' );
	}
	return $value;
}
add_filter( 'get_post_metadata', 'sp_catmenu_virtual_meta', 10, 4 );

/** Icon on the button and the category image on the main categories. */
function sp_catmenu_item_title( $title, $item ) {
	if ( ! is_object( $item ) ) {
		return $title;
	}
	if ( ! empty( $item->sp_root ) ) {
		return preg_replace( '/<cite>/', '<cite><i class="irantheme-menu-item-link-icon fa-light fa-grid-2"></i>', $title, 1 );
	}
	if ( ! empty( $item->sp_image ) ) {
		return preg_replace(
			'/<cite>/',
			'<cite><img class="irantheme-menu-item-link-image" src="' . esc_url( $item->sp_image ) . '" alt="">',
			$title,
			1
		);
	}
	return $title;
}
add_filter( 'nav_menu_item_title', 'sp_catmenu_item_title', 20, 2 );


/* ==========================================================================
   Admin: "show in header menu" checkbox on every category
   ========================================================================== */

function sp_catmenu_term_add_field() {
	?>
	<div class="form-field">
		<?php wp_nonce_field( 'sp_catmenu_term_save', 'sp_catmenu_term_nonce' ); ?>
		<input type="hidden" name="sp_catmenu_field" value="1">
		<label for="sp_catmenu_show">
			<input type="checkbox" name="sp_catmenu_show" id="sp_catmenu_show" value="1" checked="checked">
			نمایش در منوی هدر
		</label>
		<p>اگر این تیک را بردارید، این دسته (و زیردسته‌هایش) در منوی «دسته بندی محصولات» بالای سایت نمایش داده نمی‌شود.</p>
	</div>
	<?php
}
add_action( 'product_cat_add_form_fields', 'sp_catmenu_term_add_field', 20 );

function sp_catmenu_term_edit_field( $term ) {
	$hidden = '1' === get_term_meta( $term->term_id, SP_CATMENU_META_HIDE, true );
	?>
	<tr class="form-field">
		<th scope="row"><label for="sp_catmenu_show">منوی هدر</label></th>
		<td>
			<?php wp_nonce_field( 'sp_catmenu_term_save', 'sp_catmenu_term_nonce' ); ?>
			<input type="hidden" name="sp_catmenu_field" value="1">
			<label for="sp_catmenu_show">
				<input type="checkbox" name="sp_catmenu_show" id="sp_catmenu_show" value="1" <?php checked( ! $hidden ); ?>>
				نمایش در منوی هدر
			</label>
			<p class="description">اگر این تیک را بردارید، این دسته (و زیردسته‌هایش) در منوی «دسته بندی محصولات» بالای سایت نمایش داده نمی‌شود.</p>
		</td>
	</tr>
	<?php
}
add_action( 'product_cat_edit_form_fields', 'sp_catmenu_term_edit_field', 20, 1 );

function sp_catmenu_term_save( $term_id ) {
	if ( ! isset( $_POST['sp_catmenu_field'], $_POST['sp_catmenu_term_nonce'] ) ) {
		return; // quick edit / API calls: leave the value as it is
	}
	$nonce = sanitize_text_field( wp_unslash( $_POST['sp_catmenu_term_nonce'] ) );
	if ( ! wp_verify_nonce( $nonce, 'sp_catmenu_term_save' ) ) {
		return;
	}
	if ( ! current_user_can( 'manage_product_terms' ) ) {
		return;
	}
	if ( isset( $_POST['sp_catmenu_show'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		delete_term_meta( $term_id, SP_CATMENU_META_HIDE );
	} else {
		update_term_meta( $term_id, SP_CATMENU_META_HIDE, '1' );
	}
	sp_catmenu_flush_cache();
}
add_action( 'created_product_cat', 'sp_catmenu_term_save' );
add_action( 'edited_product_cat', 'sp_catmenu_term_save' );

/**
 * Flush menu cache when product categories change outside the custom checkbox save.
 *
 * @param int    $term_id  Term ID.
 * @param int    $tt_id    Term taxonomy ID.
 * @param string $taxonomy Taxonomy slug.
 */
function sp_catmenu_flush_cache_on_term( $term_id, $tt_id = 0, $taxonomy = '' ) {
	if ( 'product_cat' === $taxonomy ) {
		sp_catmenu_flush_cache();
	}
}
add_action( 'created_term', 'sp_catmenu_flush_cache_on_term', 20, 3 );
add_action( 'edited_term', 'sp_catmenu_flush_cache_on_term', 20, 3 );
add_action( 'delete_term', 'sp_catmenu_flush_cache_on_term', 20, 3 );

/**
 * Flush when category thumbnail / WooCommerce order meta changes.
 *
 * @param int    $meta_id    Meta ID.
 * @param int    $object_id  Term ID.
 * @param string $meta_key   Meta key.
 */
function sp_catmenu_flush_cache_on_termmeta( $meta_id, $object_id, $meta_key ) {
	if ( ! in_array( $meta_key, array( 'thumbnail_id', 'order', SP_CATMENU_META_HIDE ), true ) ) {
		return;
	}
	$term = get_term( (int) $object_id );
	if ( $term && ! is_wp_error( $term ) && 'product_cat' === $term->taxonomy ) {
		sp_catmenu_flush_cache();
	}
}
add_action( 'added_term_meta', 'sp_catmenu_flush_cache_on_termmeta', 10, 3 );
add_action( 'updated_term_meta', 'sp_catmenu_flush_cache_on_termmeta', 10, 3 );
add_action( 'deleted_term_meta', 'sp_catmenu_flush_cache_on_termmeta', 10, 3 );

/** Small column in the categories list: shows which categories are in the menu. */
function sp_catmenu_term_column( $columns ) {
	$columns['sp_catmenu'] = 'منوی هدر';
	return $columns;
}
add_filter( 'manage_edit-product_cat_columns', 'sp_catmenu_term_column', 20 );

function sp_catmenu_term_column_content( $content, $column, $term_id ) {
	if ( 'sp_catmenu' === $column ) {
		$hidden  = '1' === get_term_meta( $term_id, SP_CATMENU_META_HIDE, true );
		$content = $hidden ? '<span style="color:#8a8f87">مخفی</span>' : '<span style="color:#1b7a3a;font-weight:600">نمایش</span>';
	}
	return $content;
}
add_filter( 'manage_product_cat_custom_column', 'sp_catmenu_term_column_content', 20, 3 );


/* ==========================================================================
   Admin: settings page (Products > منوی دسته‌بندی هدر)
   ========================================================================== */

function sp_catmenu_admin_menu() {
	add_submenu_page(
		'edit.php?post_type=product',
		'منوی دسته‌بندی هدر',
		'منوی دسته‌بندی هدر',
		'manage_woocommerce',
		'sp-category-menu',
		'sp_catmenu_settings_page'
	);
}
add_action( 'admin_menu', 'sp_catmenu_admin_menu', 99 );

function sp_catmenu_settings_page() {
	if ( ! current_user_can( 'manage_woocommerce' ) ) {
		return;
	}
	$s        = sp_catmenu_settings();
	$cats_url = admin_url( 'edit-tags.php?taxonomy=product_cat&post_type=product' );
	?>
	<div class="wrap">
		<h1>منوی دسته‌بندی هدر</h1>

		<?php if ( isset( $_GET['updated'] ) ) : // phpcs:ignore WordPress.Security.NonceVerification ?>
			<div class="notice notice-success is-dismissible"><p>تنظیمات ذخیره شد.</p></div>
		<?php endif; ?>

		<p style="max-width:760px">
			منوی بازشونده‌ی «دسته بندی محصولات» بالای سایت، به‌صورت خودکار از
			<a href="<?php echo esc_url( $cats_url ); ?>">محصولات ← دسته‌بندی‌ها</a>
			ساخته می‌شود. دسته جدید بسازید یا نام و ترتیب را عوض کنید، منو همان لحظه به‌روز می‌شود.
		</p>

		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="sp_catmenu_save">
			<?php wp_nonce_field( 'sp_catmenu_save' ); ?>

			<table class="form-table" role="presentation">
				<tr>
					<th scope="row">منبع منو</th>
					<td>
						<label style="display:block;margin-bottom:6px">
							<input type="radio" name="mode" value="auto" <?php checked( 'auto', $s['mode'] ); ?>>
							خودکار از دسته‌بندی‌های محصولات <strong>(پیشنهادی)</strong>
						</label>
						<label style="display:block">
							<input type="radio" name="mode" value="manual" disabled <?php checked( 'manual', $s['mode'] ); ?>>
							فهرست دستی (همان فهرستی که در «نمایش ← فهرست‌ها» ساخته شده)
						</label>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="sp_catmenu_title">عنوان دکمه</label></th>
					<td>
						<input type="text" class="regular-text" id="sp_catmenu_title" name="title" value="<?php echo esc_attr( $s['title'] ); ?>">
						<p class="description">متنی که روی دکمه‌ی منو در هدر نوشته می‌شود.</p>
					</td>
				</tr>
				<tr>
					<th scope="row">دسته‌های خالی</th>
					<td>
						<label>
							<input type="checkbox" name="hide_empty" value="1" disabled <?php checked( '1', $s['hide_empty'] ); ?>>
							دسته‌هایی که هنوز محصولی ندارند در منو نمایش داده نشوند
						</label>
					</td>
				</tr>
			</table>

			<?php submit_button( 'ذخیره تغییرات' ); ?>
		</form>

		<hr>
		<h2>راهنمای سریع</h2>
		<ul style="list-style:disc;margin-right:20px;max-width:760px;line-height:2">
			<li><strong>ترتیب دسته‌ها:</strong> در صفحه دسته‌بندی‌ها، ردیف‌ها را بکشید و رها کنید.</li>
			<li><strong>مخفی کردن یک دسته:</strong> دسته را ویرایش کنید و تیک «نمایش در منوی هدر» را بردارید.</li>
			<li><strong>تصویر کنار هر دسته:</strong> همان «تصویر دسته‌بندی» است که در ویرایش دسته انتخاب می‌شود.</li>
			<li><strong>چیدمان:</strong> دسته‌های اصلی در ستون راست، و زیردسته‌ها به‌صورت ستون‌های تیتردار در پنل کناری نمایش داده می‌شوند.</li>
			<li>اگر منوی کشویی در «تنظیمات قالب ← هدر ← منو کشویی» روی «بدون منو» باشد، در سایت دیده نمی‌شود.</li>
		</ul>
	</div>
	<?php
}

function sp_catmenu_save_settings() {
	if ( ! current_user_can( 'manage_woocommerce' ) ) {
		wp_die( 'دسترسی مجاز نیست.' );
	}
	check_admin_referer( 'sp_catmenu_save' );

	$defaults = sp_catmenu_defaults();
	$mode     = ( isset( $_POST['mode'] ) && 'manual' === $_POST['mode'] ) ? 'manual' : 'auto';
	$title    = isset( $_POST['title'] ) ? sanitize_text_field( wp_unslash( $_POST['title'] ) ) : '';
	if ( '' === $title ) {
		$title = $defaults['title'];
	}

	update_option(
		SP_CATMENU_OPTION,
		array(
			'mode'       => $mode,
			'title'      => $title,
			'hide_empty' => ! empty( $_POST['hide_empty'] ) ? '1' : '0',
		)
	);
	sp_catmenu_flush_cache();

	wp_safe_redirect(
		add_query_arg(
			array(
				'page'    => 'sp-category-menu',
				'updated' => '1',
			),
			admin_url( 'edit.php?post_type=product' )
		)
	);
	exit;
}
add_action( 'admin_post_sp_catmenu_save', 'sp_catmenu_save_settings' );


/* ==========================================================================
   Header main navbar menu: replace the hand-made demo children of the
   category button with the live product categories.
   The theme prints the menu by ID (not by location), so pre_wp_nav_menu above
   never sees it; here we swap the items of the real menu item instead.
   ========================================================================== */

function sp_catmenu_filter_menu_items( $items, $menu = null, $args = array() ) {
	if ( is_admin() || empty( $items ) || ! is_array( $items ) ) {
		return $items;
	}

	$settings = sp_catmenu_settings();
	$title    = trim( $settings['title'] );
	$root_id  = 0;
	foreach ( $items as $it ) {
		if ( 0 === (int) $it->menu_item_parent && 'custom' === $it->type && trim( wp_strip_all_tags( $it->title ) ) === $title ) {
			$root_id = (int) $it->ID;
			break;
		}
	}
	if ( ! $root_id ) {
		return $items;
	}

	// All hand-made descendants of the button.
	$remove  = array( $root_id => true );
	$changed = true;
	while ( $changed ) {
		$changed = false;
		foreach ( $items as $it ) {
			$id = (int) $it->ID;
			if ( ! isset( $remove[ $id ] ) && isset( $remove[ (int) $it->menu_item_parent ] ) ) {
				$remove[ $id ] = true;
				$changed       = true;
			}
		}
	}
	unset( $remove[ $root_id ] );

	// Live categories (virtual items); their top level hangs under the real button.
	$children = array();
	foreach ( sp_catmenu_items() as $v ) {
		if ( ! empty( $v->sp_root ) ) {
			continue;
		}
		if ( (int) $v->menu_item_parent === SP_CATMENU_ID_ROOT ) {
			$v->menu_item_parent = (string) $root_id;
		}
		$children[] = $v;
	}

	$out = array();
	foreach ( $items as $it ) {
		$id = (int) $it->ID;
		if ( isset( $remove[ $id ] ) ) {
			continue;
		}
		if ( $id === $root_id && $children ) {
			$it->classes = array_values( array_unique( array_merge( (array) $it->classes, array( 'menu-item-has-children' ) ) ) );
		}
		$out[] = $it;
		if ( $id === $root_id ) {
			foreach ( $children as $c ) {
				$out[] = $c;
			}
		}
	}

	// wp_nav_menu() indexes items by menu_order: every item needs a unique one.
	$n = 0;
	foreach ( $out as $o ) {
		$o->menu_order = ++$n;
	}
	return $out;
}
add_filter( 'wp_get_nav_menu_items', 'sp_catmenu_filter_menu_items', 20, 3 );
