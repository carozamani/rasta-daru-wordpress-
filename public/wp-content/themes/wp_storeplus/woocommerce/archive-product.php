<?php

use Irantheme_Dev\Irantheme_Color_Static;
use Irantheme_Dev\Irantheme_Settings_Options_Ajax;

/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @wc-version 8.6.0
 * @author 	M-MALEKZADEH77
 */

defined('ABSPATH') || exit;

get_header('shop');
$attachment_id = get_option('irantheme_wc_irregular_banner_bg');
$background_url = wp_get_attachment_image_url($attachment_id, 'full');
$banner_type = get_option('irantheme_wc_irregular_banner_type');
$heading_checkbox = get_option('irantheme_wc_irregular_banner_custom_heading_checkbox');
$title = get_option('irantheme_wc_irregular_banner_custom_title');
$description = get_option('irantheme_wc_irregular_banner_custom_description');
if ($banner_type == 'technological') {
	Irantheme_Archives::get_header_technological($background_url, $heading_checkbox, $title, $description);
} elseif ($banner_type == 'luxurious') {
	Irantheme_Archives::get_header_luxurious($background_url, $heading_checkbox, $title, $description);
} elseif ($banner_type == 'advanced') {
	Irantheme_Archives::get_header_advanced($background_url, $heading_checkbox, $title, $description);
}

global $product;
// Get base color --------------------------------------------------------------
$base_color = get_option('irantheme_wc_archive_base_color_select');
// Get product feature --------------------------------------------------------------
$product_features = get_option('irantheme_wc_archive_features_checkbox_multi');
$product_features = explode(',', $product_features);
// Get product discount checkbox --------------------------------------------------------------
$product_discount_checkbox = get_option('irantheme_wc_archive_discount_checkbox');
// Get product total sale checkbox --------------------------------------------------------------
$product_total_sale_checkbox = get_option('irantheme_wc_archive_total_sales_checkbox');
// Get product options checkbox --------------------------------------------------------------
$product_extra_options_checkbox = get_option('irantheme_wc_archive_extra_options_checkbox');
// Get display filter options --------------------------------------------------------------
$display_filter_checkbox = get_option('irantheme_wc_archive_display_filter_checkbox');
$display_filter_item = get_option('irantheme_wc_archive_filter_widget_checkbox_multi');
$display_filter_item = explode(',', $display_filter_item);

$product_col_style = '';
if ($display_filter_checkbox == 'off') {
	$product_col_style = 'width:100%;';
}

$product_image_options_class = '';
$product_content_options_class = '';
if ($product_extra_options_checkbox == 'on') {
	$product_image_options_class = 'active';
}

// Count columns of products --------------------------------------------------------------
$col_count_products = get_option('irantheme_wc_archive_col_count_radio');
$col_count_num = '4';
if ($col_count_products == 'col-3') {
	$col_count_num = '3';
} elseif ($col_count_products == 'col-4') {
	$col_count_num = '4';
} elseif ($col_count_products == 'col-5') {
	$col_count_num = '5';
} elseif ($col_count_products == 'col-6') {
	$col_count_num = '6';
} elseif ($col_count_products == 'col-7') {
	$col_count_num = '7';
} elseif ($col_count_products == 'col-8') {
	$col_count_num = '8';
}

// Get product content alignment --------------------------------------------------------------
$post_content_align = get_option('irantheme_wc_archive_align_content_radio');

// Get product count of word excerpt --------------------------------------------------------------
$product_excerpt_count = get_option('irantheme_wc_archive_excerpt_count_number');

// Get product hover mode --------------------------------------------------------------
$product_hover_mode = get_option('irantheme_wc_archive_hover_mode_radio');

$product_hover_mode_class = '';
if ($product_hover_mode == 'shadow') {
	$product_hover_mode_class = 'irantheme-woocommerce-products-hover-shadow';
} elseif ($product_hover_mode == 'scale') {
	$product_hover_mode_class = 'irantheme-woocommerce-products-hover-scale';
}

// Get image switchable thumbnail with gallery image --------------------------------------------------------------
$product_image_hover_changer = get_option('irantheme_wc_archive_image_changer_checkbox');

$product_image_hover_changer_class = '';
if ($product_image_hover_changer == 'on') {
	$product_image_hover_changer_class = 'irantheme-woocommerce-products-item-image-changer';
}

// Get product image hover mode --------------------------------------------------------------
$product_image_hover_mode = get_option('irantheme_wc_archive_image_hover_mode_radio');

$product_image_hover_mode_class = '';
if ($product_image_hover_mode == 'flashing') {
	$product_image_hover_mode_class = 'irantheme-image-hover-flashing';
} elseif ($product_image_hover_mode == 'shine') {
	$product_image_hover_mode_class = 'irantheme-image-hover-shine';
} elseif ($product_image_hover_mode == 'circle') {
	$product_image_hover_mode_class = 'irantheme-image-hover-circle';
}

// Get product border checkbox --------------------------------------------------------------
$product_border_checkbox = get_option('irantheme_wc_archive_border_checkbox');

$product_border_class = '';
if ($product_border_checkbox == 'on') {
	$product_border_class = 'irantheme-woocommerce-products-item-border';
}

// Get product shadow class --------------------------------------------------------------
$product_shadow_checkbox = get_option('irantheme_wc_archive_shadow_checkbox');

$product_shadow_class = '';
if ($product_shadow_checkbox == 'on') {
	$product_shadow_class = 'irantheme-woocommerce-products-item-shadow';
}

// Get ordering default value
$ordering_default = get_option('irantheme_wc_archive_default_ordering_select');

$product_flag = false;
// Get all woocommerce products
$product_args = array(
	'posts_per_page'   => -1,
	'post_type'        => 'product',
);

$wc_all_products = new WP_Query($product_args);
// Check woocommerce have product
if ($wc_all_products->have_posts()) {
	$product_flag = true;
} else {
	$product_col_style = 'width:100%;';
}
?>
<div class="irantheme-wc-archive irantheme-wc-archive-show-filtering <?php echo esc_attr($base_color); ?>">
	<?php
	// wc_get_template_part('product', 'searchform');
	?>
	<div class="irantheme-container-inner">
		<?php
		if ($product_flag):
		?>
			<div class="irantheme-wc-archive-top-bar">
				<div class="irantheme-wc-archive-top-bar-item irantheme-woocommerce-filter-clear">
					<p class="irantheme-wc-archive-top-bar-item-title">
						<i class="fa-light fa-xmark-large"></i>
						<span>حذف فیلترهای اعمال شده</span>
					</p>
				</div>
				<div class="irantheme-wc-archive-top-bar-item irantheme-woocommerce-result-count-wrapper">
					<p class="irantheme-woocommerce-result-count irantheme-wc-archive-top-bar-item-title">
						نمایش
						<span class="result-count-presentable">
							<em class="result-count-presentable-from">0</em>
							<em class="result-count-presentable-separator">-</em>
							<em class="result-count-presentable-to">0</em>
						</span>
						از
						<span class="result-count-total">0</span>
						نتیجه
					</p>
				</div>
				<div class="irantheme-wc-archive-top-bar-item irantheme-woocommerce-ordering-wrapper irantheme-wc-ajax-ordering">
					<a class="irantheme-woocommerce-ordering-title irantheme-wc-archive-top-bar-item-title"><i class="fa-light fa-arrow-up-arrow-down"></i><span>
							<?php
							switch ($ordering_default) {
								case 'menu_order':
									echo 'مرتب سازی پیش فرض';
									break;

								case 'popularity':
									echo 'مرتب سازی براساس محبوبیت';
									break;

								case 'rating':
									echo 'مرتب سازی براساس امتیاز';
									break;

								case 'date':
									echo 'مرتب سازی براساس جدیدترین';
									break;

								case 'price':
									echo 'مرتب سازی براساس ارزانترین';
									break;

								case 'price-desc':
									echo 'مرتب سازی براساس گرانترین';
									break;

								default:
									echo 'مرتب سازی پیش فرض';
									break;
							}
							?>
						</span></a>
					<ul class="irantheme-woocommerce-ordering-list" data-ordering-default="<?php echo esc_attr($ordering_default); ?>">
						<li class="irantheme-woocommerce-ordering-list-item <?php echo $ordering_default == "menu_order" ? "active" : ""; ?>" data-value="menu_order">مرتب سازی پیش فرض</li>
						<li class="irantheme-woocommerce-ordering-list-item <?php echo $ordering_default == "popularity" ? "active" : ""; ?>" data-value="popularity">مرتب سازی براساس محبوبیت</li>
						<li class="irantheme-woocommerce-ordering-list-item <?php echo $ordering_default == "rating" ? "active" : ""; ?>" data-value="rating">مرتب سازی براساس امتیاز</li>
						<li class="irantheme-woocommerce-ordering-list-item <?php echo $ordering_default == "date" ? "active" : ""; ?>" data-value="date">مرتب سازی براساس جدیدترین</li>
						<li class="irantheme-woocommerce-ordering-list-item <?php echo $ordering_default == "price" ? "active" : ""; ?>" data-value="price">مرتب سازی براساس ارزانترین</li>
						<li class="irantheme-woocommerce-ordering-list-item <?php echo $ordering_default == "price-desc" ? "active" : ""; ?>" data-value="price-desc">مرتب سازی براساس گرانترین</li>
					</ul>
				</div>
				<?php if ($display_filter_checkbox == 'on'): ?>
					<div class="irantheme-wc-archive-top-bar-item irantheme-woocommerce-filtering-status">
						<p class="irantheme-wc-archive-top-bar-item-title irantheme-wc-archive-hide-filter ps-0">
							<i class="fa-light fa-filter-slash"></i>
							<span>مخفی کردن فیلترها</span>
						</p>
						<p class="irantheme-wc-archive-top-bar-item-title irantheme-wc-archive-show-filter ps-0">
							<i class="fa-light fa-filter"></i>
							<span>نمایش فیلترها</span>
						</p>
					</div>
				<?php endif; ?>
			</div>
		<?php
		endif;
		?>
		<div class="row">
			<?php if ($product_flag): ?>
				<?php if ($display_filter_checkbox == 'on'): ?>
					<div class="col-12 col-md-4 col-lg-3 irantheme-wc-archive-filter-col">
						<div class="irantheme-wc-archive-filter irantheme-wc-archive-main-filter irantheme-sidebar-holder <?php echo esc_attr($base_color); ?>">
							<?php if (in_array('search', $display_filter_item)): ?>
								<aside class="irantheme-wc-archive-filter-search irantheme-sidebar-box-holder irantheme-border-radius-pages">
									<div class="irantheme-wc-archive-search-wrapper">
										<input type="search" id="irantheme_wc_shop_search" class="irantheme-wc-archive-search-field m-0" placeholder="<?php esc_attr_e('Search products&hellip;', 'woocommerce'); ?>" value="<?php echo get_search_query(); ?>" name="search" />
										<span><i class="fa-light fa-magnifying-glass"></i></span>
									</div>
								</aside>
							<?php endif; ?>
							<?php if (in_array('category', $display_filter_item)): ?>
								<aside class="irantheme-wc-archive-filter-category irantheme-wc-archive-filter-box irantheme-sidebar-box-holder irantheme-border-radius-pages">
									<label class="irantheme-wc-archive-filter-category-label irantheme-wc-archive-filter-box-label irantheme-sidebar-box-title irantheme-sidebar-box-title-custom-icon">دسته بندی<i class="fa-light fa-list"></i></label>
									<ul class="irantheme-wc-archive-filter-box-content irantheme-wc-archive-filter-box-content-checkbox">
										<?php
										$empty_category_checkbox = get_option('irantheme_wc_archive_delete_empty_category_checkbox');
										$category_count_checkbox = get_option('irantheme_wc_archive_display_category_count_checkbox');
										// Get all product cat ordered by priority
										$terms = get_terms('product_cat', array(
											'orderby' => 'menu_order',
											'order' => 'ASC',
											'hide_empty' => $empty_category_checkbox == 'on' ? true : false,
										));
										// Add parent category to array
										$parent_cats = array();
										if (!is_wp_error($terms)) {
											foreach ($terms as $terms_item) {
												if ($terms_item->parent == 0) {
													array_push($parent_cats, $terms_item);
												}
											}
										}

										$term_link = '';
										$cat_custom_class = '';
										$input_cat_custom_class = '';
										$parent_custom_class = '';
										$parent_index = 0;
										foreach ($parent_cats as $term) {
											$count_of_child = count(get_term_children($term->term_id, 'product_cat'));
											if ($count_of_child > 0) {
												$parent_custom_class = 'irantheme-wc-cat-has-parent';
											}
											if (is_product_category() || is_product_tag() || is_tax('product_cat')) {
												$term_link = 'href=' . get_term_link($term) . '';
												$cat_custom_class = 'has-link';
												$input_cat_custom_class = 'input-has-link';
											}
										?>
											<div class="irantheme-wc-archive-filter-category-wrapper">
												<label class="irantheme-wc-archive-filter-category-content-label irantheme-wc-archive-filter-content-label <?php echo esc_attr($parent_custom_class); ?>">
													<input type="checkbox" id="irantheme_wc_archive_filter_category_<?php echo esc_attr($term->slug); ?>" class="<?php echo esc_attr($input_cat_custom_class); ?>" name="product_category_item[]" value="<?php echo ($term->slug); ?>">
													<a <?php echo esc_attr($term_link); ?> class="irantheme-wc-archive-filter-content-label-text <?php echo esc_attr($cat_custom_class); ?>"><?php echo esc_html($term->name); ?>
														<?php if ($count_of_child > 0): ?>
															<em class="irantheme-wc-archive-filter-content-label-toggler"><i class="fa-light fa-square-plus"></i></em>
														<?php else: ?>
															<?php if ($category_count_checkbox == 'on'): ?>
																<em class="irantheme-wc-archive-filter-content-label-count"><?php echo esc_html($term->count); ?></em>
															<?php endif; ?>
														<?php endif; ?>
													</a>
												</label>
												<?php

												// Get children of parents
												$child_cats = get_terms('product_cat', array(
													'parent' => $term->term_id,
													'orderby' => 'menu_order',
													'order' => 'ASC',
													'hide_empty' => $empty_category_checkbox == 'on' ? true : false,
												));

												if (!is_wp_error($child_cats)) {
													// Add grandChild to child array
													foreach ($child_cats as $child_key => $child_value) {
														$grand_child_of_current = array();
														// Get grandchild ids
														$grand_child = get_term_children($child_value->term_id, 'product_cat');
														if (count($grand_child) != 0) {
															foreach ($grand_child as $grand_child_id) {
																// Get wp_object of curren grandchild and add to array of grandchild
																$grand_child_of_current[] = get_term_by('id', $grand_child_id, 'product_cat');
															}
															// Add array of grandchild after the current children (after parent of grandchild)
															array_splice($child_cats, $child_key, 0, $grand_child_of_current);
														}
													}
												}

												if (!is_wp_error($child_cats) && count($child_cats) != 0) :
													$parent_index++;
												?>
													<div class="irantheme-wc-archive-filter-category-children-wrapper <?php echo $parent_index == 1 ? "active" : ""; ?>" style="<?php echo $parent_index == 1 ? "display: block;" : ""; ?>">
														<?php
													endif;

													$term_link = '';
													$cat_custom_class = '';
													$input_cat_custom_class = '';
													$children_custom_class = 'irantheme-wc-cat-has-children';
													if (!is_wp_error($child_cats)) {
														foreach ($child_cats as $child_item) {
															if (is_product_category() || is_product_tag() || is_tax('product_cat')) {
																$term_link = 'href=' . get_term_link($child_item) . '';
																$cat_custom_class = 'has-link';
																$input_cat_custom_class = 'input-has-link';
															}
														?>
															<label class="irantheme-wc-archive-filter-category-content-label irantheme-wc-archive-filter-content-label <?php echo esc_attr($children_custom_class); ?>">
																<input type="checkbox" id="irantheme_wc_archive_filter_category_<?php echo esc_attr($child_item->slug); ?>" class="<?php echo esc_attr($input_cat_custom_class); ?>" name="product_category_item[]" value="<?php echo ($child_item->slug); ?>">
																<a <?php echo esc_attr($term_link); ?> class="irantheme-wc-archive-filter-content-label-text <?php echo esc_attr($cat_custom_class); ?>"><?php echo esc_html($child_item->name); ?>
																	<?php if ($category_count_checkbox == 'on'): ?>
																		<em class="irantheme-wc-archive-filter-content-label-count"><?php echo esc_html($child_item->count); ?></em>
																	<?php endif; ?>
																</a>
															</label>
														<?php
														}
													}
													if (!is_wp_error($child_cats) && count($child_cats) != 0) {
														?>
													</div>
												<?php
													}
												?>
											</div>
										<?php
										}
										?>
									</ul>
								</aside>
							<?php endif; ?>
							<?php
							// Child theme: WooCommerce brand filter box (inc/brand-filter.php). No HTML buffer.
							do_action( 'storeplus_child_after_archive_category_filter' );
							?>
							<?php
							// Dynamic filter ===============================================================
							// Get all used taxonomies
							$used_attributes = wc_get_attribute_taxonomies();

							// Add position index to array
							foreach ($used_attributes as $attr) {
								// Create taxonomy name
								$taxonomy_name = 'pa_' . $attr->attribute_name;
								// Assign position index value
								$attr->position = irantheme_get_attribute_custom_option_value($taxonomy_name, 'filter_position');
							}

							// Sort array of object by position property
							usort($used_attributes, fn($a, $b) => strcmp($a->position, $b->position));

							foreach ($used_attributes as $attr) :
								// Get attribute name
								$attribute_name = $attr->attribute_name;

								// Create taxonomy name
								$taxonomy_name = 'pa_' . $attr->attribute_name;

								// Get taxonomy filter checkbox value
								$taxonomy_filter_checkbox = irantheme_get_attribute_custom_option_value($taxonomy_name, 'add_to_filter');

								// Check filter checkbox is on
								if ($taxonomy_filter_checkbox == 'on'):
									// Get taxonomy type value
									$taxonomy_type = irantheme_get_attribute_custom_option_value($taxonomy_name, 'type');
									// Get taxonomy type value
									$taxonomy_icon = irantheme_get_attribute_custom_option_value($taxonomy_name, 'filter_icon');

									// Get all tags of custom taxonomy
									$tags = get_terms([
										'taxonomy' => $taxonomy_name,
										'hide_empty' => true
									]);

									if ($taxonomy_name != "" && $tags) :
							?>
										<aside class="irantheme-wc-archive-filter-box irantheme-sidebar-box-holder irantheme-border-radius-pages">
											<label class="irantheme-wc-archive-filter-box-label irantheme-sidebar-box-title irantheme-sidebar-box-title-custom-icon"><?php echo esc_html($attr->attribute_label); ?><i class="<?php echo esc_attr($taxonomy_icon); ?>"></i></label>
											<?php

											// Check taxonomy type
											switch ($taxonomy_type):

													// If taxonomy type is color
												case 'color':
											?>
													<ul class="irantheme-wc-archive-filter-box-content irantheme-wc-archive-filter-box-content-checkbox irantheme-wc-archive-dynamic-filter irantheme-wc-archive-filter-<?php echo esc_attr($attribute_name); ?>" data-tax="<?php echo esc_attr($attribute_name); ?>">
														<?php

														// Get name of tag
														foreach ($tags as $tag_key => $tag_value) :
															$color_val = get_option("_term_type_{$taxonomy_name}_{$tag_value->term_id}");
														?>
															<label class="irantheme-wc-archive-filter-color-content-label irantheme-wc-archive-filter-content-label">
																<input type="checkbox" id="irantheme_wc_archive_filter_color_<?php echo esc_attr($tag_value->slug); ?>" name="product_filter_item[]" value="<?php echo esc_html($tag_value->slug); ?>">
																<a class="irantheme-wc-archive-filter-content-label-text"><?php echo esc_html($tag_value->name); ?></a>
																<em class="irantheme-wc-archive-filter-content-label-color" style="background: <?php echo esc_attr($color_val); ?>;"></em>
															</label>
														<?php
														endforeach;
														?>
													</ul>
												<?php
													break;
												default:
												?>
													<ul class="irantheme-wc-archive-filter-box-content irantheme-wc-archive-filter-box-content-checkbox irantheme-wc-archive-dynamic-filter irantheme-wc-archive-filter-<?php echo esc_attr($attribute_name); ?>" data-tax="<?php echo esc_attr($attribute_name); ?>">
														<?php
														// Get name of tag
														foreach ($tags as $tag_key => $tag_value) :
														?>
															<label class="irantheme-wc-archive-filter-<?php echo esc_html($taxonomy_type); ?>-content-label irantheme-wc-archive-filter-content-label">
																<input type="checkbox" id="irantheme_wc_archive_filter_<?php echo esc_html($taxonomy_type); ?>_<?php echo esc_html($tag_value->slug); ?>" name="product_filter_item[]" value="<?php echo esc_html($tag_value->slug); ?>">
																<a class="irantheme-wc-archive-filter-content-label-text"><?php echo esc_html($tag_value->name); ?><em class="irantheme-wc-archive-filter-content-label-count"><?php echo esc_html($tag_value->count); ?></em></a>
															</label>
														<?php
														endforeach;
														?>
													</ul>
											<?php
													break;
											endswitch;
											?>
										</aside>
							<?php
									endif;
								endif;
							endforeach;
							?>
							<?php if (in_array('price', $display_filter_item)): ?>
								<?php
								// $args = array(
								// 	'posts_per_page' => -1,
								// 	'post_type' => 'product',
								// 	'orderby' => 'meta_value_num',
								// 	'meta_key' => '_price',
								// 	'order' => 'des'
								// );
								// $query = new WP_Query($args);
								// while ($query->have_posts()) {
								// 	$query->the_post();
								// 	$product = wc_get_product(get_the_ID());
								// 	break;
								// }
								// Get max price of products ===================================================
								// Get the global WordPress database object.
								global $wpdb;
								// Build the WooCommerce product lookup table name.
								$table = $wpdb->prefix . 'wc_product_meta_lookup';
								// Check if the lookup table exists for compatibility.
								if ($wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $table)) === $table) {
									// Get the highest published product price from the optimized lookup table.
									$product_max_price = (float) $wpdb->get_var(
										"SELECT MAX(lookup.max_price)
        								FROM {$table} AS lookup
        								INNER JOIN {$wpdb->posts} AS posts
            								ON lookup.product_id = posts.ID
        								WHERE posts.post_status = 'publish'
            								AND posts.post_type = 'product'"
									);
								} else {
									// Fallback for older WooCommerce versions.
									$product_max_price = (float) $wpdb->get_var(
										"SELECT MAX(CAST(pm.meta_value AS DECIMAL(20,2)))
        								FROM {$wpdb->postmeta} AS pm
        								INNER JOIN {$wpdb->posts} AS posts
            								ON pm.post_id = posts.ID
        								WHERE pm.meta_key = '_price'
            								AND posts.post_status = 'publish'
            								AND posts.post_type IN ('product', 'product_variation')"
									);
								}
								// Round the value up for the price slider.
								$product_max_price = ceil($product_max_price);

								// if ($query->have_posts()):
								?>
								<aside class="irantheme-wc-archive-filter-price irantheme-wc-archive-filter-box irantheme-sidebar-box-holder irantheme-border-radius-pages">
									<label class="irantheme-wc-archive-filter-price-label irantheme-wc-archive-filter-box-label irantheme-sidebar-box-title irantheme-sidebar-box-title-custom-icon">محدوده قیمت<i class="fa-light fa-square-dollar"></i></label>
									<div class="irantheme-wc-archive-filter-price-content irantheme-wc-archive-filter-box-content">
										<?php
										// $product_max_price = '';

										// if ($product->is_type('simple')) {
										// 	$product_max_price = $product->get_price();
										// } elseif ($product->is_type('variable')) {
										// 	$product_max_price = $product->get_variation_price('max');
										// }
										?>
										<div class="irantheme-price-input-holder">
											<div class="irantheme-price-filter-input-holder">
												<label for="irantheme_price_filter_input_from">از</label>
												<input type="number" name="irantheme_price_filter_input_from" id="irantheme_price_filter_input_from" class=" irantheme-price-filter-input irantheme-price-filter-input-from" value="0" min="0" max="<?php echo esc_attr($product_max_price); ?>">
												<label for="irantheme_price_filter_input_from"><?php echo get_woocommerce_currency_symbol(); ?></label>
											</div>
											<div class="irantheme-price-filter-input-holder">
												<label for="irantheme_price_filter_input_to">تا</label>
												<input type="number" name="irantheme_price_filter_input_to" id="irantheme_price_filter_input_to" class="irantheme-price-filter-input irantheme-price-filter-input-to" value="<?php echo esc_attr($product_max_price); ?>" min="1" max="<?php echo esc_attr($product_max_price); ?>">
												<label for="irantheme_price_filter_input_to"><?php echo get_woocommerce_currency_symbol(); ?></label>
											</div>
										</div>
										<div class="irantheme-price-range-holder">
											<div class="irantheme-price-range">
												<div class="irantheme-price-range-sliders-control">
													<input id="fromSliderRangeInput" type="range" value="0" min="0" max="<?php echo esc_attr($product_max_price); ?>" steps="10" />
													<input id="toSliderRangeInput" type="range" value="<?php echo esc_attr($product_max_price); ?>" min="0" max="<?php echo esc_attr($product_max_price); ?>" steps="10" />
												</div>
												<!--scale steps defined by data-steps value -->
												<div class="scale">

													<div id="scaleEnd" class="scale-end">گرانترین قیمت</div>
													<div id="scaleStart" class="scale-start">ارزانترین قیمت</div>
												</div>
											</div>
										</div>
									</div>
								</aside>
								<?php //endif; 
								?>
							<?php endif; ?>
							<?php if (in_array('status', $display_filter_item)): ?>
								<aside class="irantheme-wc-archive-filter-status irantheme-wc-archive-filter-box irantheme-sidebar-box-holder irantheme-border-radius-pages">
									<label class="irantheme-wc-archive-filter-status-label irantheme-wc-archive-filter-box-label irantheme-sidebar-box-title irantheme-sidebar-box-title-custom-icon">وضعیت<i class="fa-light fa-toggle-on"></i></label>
									<ul class="irantheme-wc-archive-filter-status-content irantheme-wc-archive-filter-box-content irantheme-wc-archive-filter-box-content-checkbox">
										<label class="irantheme-wc-archive-filter-status-content-label irantheme-wc-archive-filter-content-label">
											<input type="checkbox" id="irantheme_wc_archive_filter_status_in_stock" name="product_status_in_stock" value="in-stock">
											<a class="irantheme-wc-archive-filter-content-label-text">فقط کالا های موجود</a>
										</label>
										<label class="irantheme-wc-archive-filter-status-content-label irantheme-wc-archive-filter-content-label">
											<input type="checkbox" id="irantheme_wc_archive_filter_status_on_sale" name="product_status_on_sale" value="on-sale">
											<a class="irantheme-wc-archive-filter-content-label-text">فروش ویژه</a>
										</label>
									</ul>
								</aside>
							<?php endif; ?>
						</div>
					</div>
				<?php endif; ?>
			<?php endif; ?>
			<div class="col-12 col-md-8 col-lg-9 irantheme-wc-archive-product-col" style="<?php echo esc_attr($product_col_style); ?>">
				<?php
				// Child theme: brand filter chips above product cards (inc/brand-filter.php).
				do_action( 'storeplus_child_before_archive_products' );
				?>
				<!-- Preloader -->
				<div class="irantheme-preloader-abs <?php echo esc_attr($base_color); ?>">
					<i class="irantheme-animate-rotate irantheme-preloader-loader thin"></i>
				</div>
				<div class="irantheme-wc-archive-ajax-error">
					<i class="fa-light fa-cloud-exclamation <?php echo esc_attr(Irantheme_Color_Static::get_class_color_mode('color-fill', 'primary')) ?> <?php echo esc_attr(Irantheme_Color_Static::get_class_color_mode('bg-glass', 'primary')) ?>"></i>
					<h3 class="irantheme-wc-archive-ajax-error-title">در واکشی اطلاعات خطایی رخ داده است!</h3>
					<p class="irantheme-wc-archive-ajax-error-desc"></p>
				</div>
				<div class="irantheme-wc-archive-products" data-page="<?php echo esc_attr(irantheme_get_page_type()); ?>">
					<?php
					if (is_product_category() || is_product_tag() || is_tax('product_cat')) :
						// if (woocommerce_product_loop()) {
						// woocommerce_product_loop_start();
						Irantheme_Settings_Options_Ajax::apply_filter_to_archive_product();
						/**
						 * Hook: woocommerce_after_shop_loop.
						 *
						 * @hooked woocommerce_pagination - 10
						 */
						remove_action('woocommerce_after_shop_loop', 'woocommerce_pagination', 10);
						do_action('woocommerce_after_shop_loop');
					endif;

					/**
					 * Hook: woocommerce_after_main_content.
					 *
					 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
					 */
					do_action('woocommerce_after_main_content');
					?>
				</div>
			</div>
		</div>
		<?php if ($product_flag): ?>
			<!-- Filter navigation -->
			<div class="irantheme-woocommerce-filter-navigation">
				<!-- Header -->
				<div class="irantheme-woocommerce-filter-navigation-header">
					<button type="button" class="irantheme-btn-void irantheme-woocommerce-filter-navigation-header-close">
						<i class="fa-light fa-arrow-left-long"></i>
					</button>
					<hr>
				</div>

				<!-- Wrapper filter -->
				<div class="irantheme-woocommerce-filter-navigation-wrapper">
					<div class="irantheme-wc-archive-filter irantheme-wc-archive-filter-navigation irantheme-sidebar-holder <?php echo esc_attr($base_color); ?>">
					</div>
					<div class="irantheme-woocommerce-filter-navigation-footer">
						<!-- Clear all filter -->
						<div class="irantheme-woocommerce-filter-navigation-footer-clear">
							<a>پاک کردن همه</a>
						</div>
						<?php
						$apply_btn_class = '';
						if ($base_color == 'dark') {
							$apply_btn_class = 'irantheme-btn-white';
						} elseif ($base_color == 'white') {
							$apply_btn_class = 'irantheme-btn-dark';
						}
						?>
						<!-- Apply filter -->
						<div class="irantheme-woocommerce-filter-navigation-footer-apply">
							<a class="irantheme-btn <?php echo esc_attr($apply_btn_class); ?> irantheme-btn-large irantheme-btn-block">اعمال فیلترها</a>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</div>
	<?php
	// Footer
	Irantheme_Footer_Generator::display_footer();
	get_footer('shop');
	?>
</div>