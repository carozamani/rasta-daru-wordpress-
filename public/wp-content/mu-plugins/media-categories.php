<?php
/**
 * Plugin Name: دسته‌بندی رسانه (Media Categories)
 * Description: افزودن دسته‌بندی سلسله‌مراتبی به کتابخانه رسانه، همراه با ستون، فیلتر (لیست و گرید) و انتساب گروهی.
 * Version: 1.0.0
 */

defined( 'ABSPATH' ) || exit;

const MC_TAX = 'media_category';

/* ------------------------------------------------------------------
 * 1) ثبت تاکسونومی
 * ---------------------------------------------------------------- */
add_action( 'init', function () {
	register_taxonomy( MC_TAX, 'attachment', [
		'labels'            => [
			'name'              => 'دسته‌بندی رسانه',
			'singular_name'     => 'دسته رسانه',
			'menu_name'         => 'دسته‌بندی رسانه',
			'all_items'         => 'همه دسته‌ها',
			'edit_item'         => 'ویرایش دسته',
			'view_item'         => 'نمایش دسته',
			'update_item'       => 'به‌روزرسانی دسته',
			'add_new_item'      => 'افزودن دسته جدید',
			'new_item_name'     => 'نام دسته جدید',
			'parent_item'       => 'دسته مادر',
			'parent_item_colon' => 'دسته مادر:',
			'search_items'      => 'جستجوی دسته‌ها',
			'not_found'         => 'دسته‌ای پیدا نشد',
		],
		'hierarchical'      => true,
		'public'            => false,
		'show_ui'           => true,
		'show_in_menu'      => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'show_in_nav_menus' => false,
		'query_var'         => true,
		'rewrite'           => false,
		'update_count_callback' => '_update_generic_term_count',
	] );
} );

/* ------------------------------------------------------------------
 * 2) فیلتر دسته در حالت «لیست» کتابخانه رسانه
 * ---------------------------------------------------------------- */
add_action( 'restrict_manage_posts', function ( $post_type ) {
	$screen = get_current_screen();
	if ( ! $screen || 'upload' !== $screen->id ) {
		return;
	}
	$selected = isset( $_GET[ MC_TAX ] ) ? sanitize_text_field( wp_unslash( $_GET[ MC_TAX ] ) ) : '';
	wp_dropdown_categories( [
		'taxonomy'        => MC_TAX,
		'name'            => MC_TAX,
		'value_field'     => 'slug',
		'selected'        => $selected,
		'show_option_all' => 'همه دسته‌ها',
		'show_count'      => true,
		'hide_empty'      => false,
		'hierarchical'    => true,
		'orderby'         => 'name',
	] );
} );

/* ------------------------------------------------------------------
 * 3) فیلتر دسته در حالت «گرید» و پنجره انتخاب رسانه
 * ---------------------------------------------------------------- */
add_filter( 'ajax_query_attachments_args', function ( $args ) {
	$slug = isset( $_REQUEST['query'][ MC_TAX ] ) ? sanitize_text_field( wp_unslash( $_REQUEST['query'][ MC_TAX ] ) ) : '';
	if ( '' !== $slug ) {
		$args['tax_query'] = [ [
			'taxonomy' => MC_TAX,
			'field'    => 'slug',
			'terms'    => $slug,
		] ];
	}
	return $args;
} );

add_action( 'wp_enqueue_media', function () {
	$terms = get_terms( [ 'taxonomy' => MC_TAX, 'hide_empty' => false ] );
	if ( is_wp_error( $terms ) ) {
		$terms = [];
	}
	$data = array_map( function ( $t ) {
		return [ 'id' => $t->term_id, 'slug' => $t->slug, 'name' => $t->name ];
	}, $terms );

	$js = 'window.mcTerms=' . wp_json_encode( $data ) . ';' . <<<'JS'
(function () {
  if (!window.wp || !wp.media || !wp.media.view || !wp.media.view.AttachmentsBrowser) { return; }
  var Orig = wp.media.view.AttachmentsBrowser;
  wp.media.view.AttachmentsBrowser = Orig.extend({
    createToolbar: function () {
      Orig.prototype.createToolbar.call(this);
      if (!window.mcTerms || !window.mcTerms.length) { return; }
      var Filter = wp.media.view.AttachmentFilters.extend({
        id: 'media-category-filter',
        createFilters: function () {
          var filters = {};
          filters.all = { text: 'همه دسته‌ها', props: { media_category: '' }, priority: 10 };
          _.each(window.mcTerms, function (t, i) {
            filters['mc' + t.id] = { text: t.name, props: { media_category: t.slug }, priority: 20 + i };
          });
          this.filters = filters;
        }
      });
      this.toolbar.set('mcFilter', new Filter({
        controller: this.controller,
        model: this.collection.props,
        priority: -75
      }).render());
    }
  });
})();
JS;
	wp_add_inline_script( 'media-views', $js, 'after' );
} );

/* ------------------------------------------------------------------
 * 4) عملیات گروهی در حالت لیست: افزودن به دسته / حذف از همه دسته‌ها
 * ---------------------------------------------------------------- */
add_filter( 'bulk_actions-upload', function ( $actions ) {
	$terms = get_terms( [ 'taxonomy' => MC_TAX, 'hide_empty' => false ] );
	if ( is_wp_error( $terms ) ) {
		return $actions;
	}
	foreach ( $terms as $t ) {
		$actions[ 'mc_add_' . $t->term_id ] = 'افزودن به دسته: ' . $t->name;
	}
	if ( $terms ) {
		$actions['mc_clear'] = 'حذف از همه دسته‌ها';
	}
	return $actions;
} );

add_filter( 'handle_bulk_actions-upload', function ( $redirect, $action, $ids ) {
	if ( 0 !== strpos( $action, 'mc_' ) ) {
		return $redirect;
	}
	$done = 0;
	foreach ( (array) $ids as $id ) {
		$id = (int) $id;
		if ( ! current_user_can( 'edit_post', $id ) ) {
			continue;
		}
		if ( 'mc_clear' === $action ) {
			wp_set_object_terms( $id, [], MC_TAX );
			$done++;
		} elseif ( preg_match( '/^mc_add_(\d+)$/', $action, $m ) ) {
			wp_set_object_terms( $id, [ (int) $m[1] ], MC_TAX, true );
			$done++;
		}
	}
	return add_query_arg( 'mc_done', $done, $redirect );
}, 10, 3 );

add_action( 'admin_notices', function () {
	if ( isset( $_GET['mc_done'] ) ) {
		printf(
			'<div class="notice notice-success is-dismissible"><p>دسته‌بندی روی %d رسانه اعمال شد.</p></div>',
			(int) $_GET['mc_done']
		);
	}
} );
