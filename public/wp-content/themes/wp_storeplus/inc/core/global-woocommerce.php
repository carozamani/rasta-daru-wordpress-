<?php

use Irantheme_Dev\Irantheme_Color_Static;
use Irantheme_Dev\Irantheme_Common_Static;
use Irantheme_Dev\Irantheme_Icons_Selector;
use Irantheme_Dev\Irantheme_Section_Utils;
use Irantheme_Dev\Irantheme_Settings_Options_Ajax;

if (!function_exists('irantheme_woocommerce_theme_support')) {
    /**
     * Woocommerce theme support
     * @return void
     * @param null
     * @see v1.0
     */
    function irantheme_woocommerce_theme_support()
    {
        add_theme_support('woocommerce', array(
            // . . .
            // thumbnail_image_width, single_image_width, etc.

            // Product grid theme settings
            'product_grid'          => array(
                'default_rows'    => 3,
                'min_rows'        => 2,
                'max_rows'        => 8,

                'default_columns' => 4,
                'min_columns'     => 2,
                'max_columns'     => 5,
            ),
        ));
    }

    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');


    add_action('after_setup_theme', 'irantheme_woocommerce_theme_support');
}


if (!function_exists('is_woocommerce_activated')) {
    /**
     * Check if WooCommerce is activated
     * Safe version: avoids triggering gettext loops.
     */
    function is_woocommerce_activated()
    {
        if (did_action('woocommerce_loaded')) {
            return true;
        }
        return class_exists('woocommerce');
    }
}


if (!function_exists('is_woocommerce_page')) {
    /**
     * Check is current woocommerce page
     * @return bool
     * @param void
     * @author iranianclever
     * @since v1.0
     * @see v1.0
     */
    function is_woocommerce_page()
    {
        if (is_woocommerce_activated() && is_checkout() || is_cart() || is_account_page() || is_wc_endpoint_url()) {
            return true;
        }
        return false;
    }
}


if (!function_exists('irantheme_refresh_cart_count')) {
    /**
     * Refresh hook cart count in shopping
     * @return array
     * @param array
     * @see v1.0
     */
    function irantheme_refresh_cart_count($fragments)
    {
        ob_start();
?>
        <span class="irantheme-shopping-cart-count">
            <?php echo WC()->cart->get_cart_contents_count(); ?>
        </span>
        <?php
        $fragments['.irantheme-shopping-cart-count'] = ob_get_clean();
        return $fragments;
    }
    add_filter('woocommerce_add_to_cart_fragments', 'irantheme_refresh_cart_count', 50, 1);
}



if (!function_exists('irantheme_woocommerce_shopping_cart_count')) {
    /**
     * Display woocommerce shopping cart count number
     * @param void
     * @return void
     * @see v1.0
     */
    function irantheme_woocommerce_shopping_cart_count()
    {
        if (is_woocommerce_activated()) {
            $mini_number_count_css = '';
        ?>
            <!-- Mini shopping cart count -->
            <div class="irantheme-mini-number-count <?php echo esc_attr(Irantheme_Color_Static::get_class_color_mode('bg', 'primary')) ?>" style="<?php echo esc_attr($mini_number_count_css); ?>">
                <span class="irantheme-shopping-cart-count">
                    <?php echo WC()->cart->get_cart_contents_count(); ?>
                </span>
            </div>
        <?php
        }
    }
}


if (!function_exists('irantheme_woocommerce_wishlist_count')) {
    /**
     * Display woocommerce wishlist count number
     * @param void
     * @return void
     * @see v1.0
     */
    function irantheme_woocommerce_wishlist_count()
    {
        ?>
        <!-- Mini wishlist count -->
        <div class="irantheme-mini-number-count <?php echo esc_attr(Irantheme_Color_Static::get_class_color_mode('bg', 'primary')) ?>" style="display:none;">
            <span class="irantheme-wishlist-count"></span>
        </div>
    <?php
    }
}


if (!function_exists('irantheme_get_woocommerce_get_cart_url')) {
    /**
     * Display woocommerce shopping cart count number
     * @param void
     * @return string
     * @see v1.0
     */
    function irantheme_get_woocommerce_get_cart_url()
    {
        if (is_woocommerce_activated()) {
            return wc_get_cart_url();
        } else {
            return '#';
        }
    }
}

if (!function_exists('irantheme_woocommerce_breadcrumbs')) {
    /**
     * Change several of the breadcrumb defaults
     * @param void
     * @return array
     * @see v1.0
     */
    function irantheme_woocommerce_breadcrumbs()
    {
        // Get details color for hover mode
        $details_color = get_option('irantheme_single_product_details_color');
        $extra_color_class = '';
        if ($details_color !== 'off') {
            $extra_color_class = Irantheme_Color_Static::get_class_color_mode('color-hover', $details_color);
        }
        return array(
            'delimiter'   => '<span class="irantheme-woocommerce-breadcrumb-delimiter">&#47;</span>',
            'wrap_before' => '<nav class="woocommerce-breadcrumb irantheme-woocommerce-breadcrumb" itemprop="breadcrumb" data-hover-class="' . $extra_color_class . '">',
            'wrap_after'  => '</nav>',
            'before'      => '',
            'after'       => '',
            'home'        => _x('Home', 'breadcrumb', 'woocommerce'),
        );
    }
    add_filter('woocommerce_breadcrumb_defaults', 'irantheme_woocommerce_breadcrumbs');
}

if (!function_exists('irantheme_dequeue_styles')) {
    /**
     * Set WooCommerce image dimensions upon theme activation
     */
    // Remove each style one by one
    function irantheme_dequeue_styles($enqueue_styles)
    {
        unset($enqueue_styles['woocommerce-general']);    // Remove the gloss
        unset($enqueue_styles['woocommerce-layout']);        // Remove the layout
        unset($enqueue_styles['woocommerce-smallscreen']);    // Remove the smallscreen optimisation
        return $enqueue_styles;
    }
    add_filter('woocommerce_enqueue_styles', 'irantheme_dequeue_styles');
}

// remove_theme_support('wc-product-gallery-zoom');
// remove_theme_support('wc-product-gallery-lightbox');
// remove_theme_support('wc-product-gallery-slider');

if (!function_exists('irantheme_wp_enqueue_woocommerce_style')) {
    /**
     * Enqueue your own stylesheet
     */
    function irantheme_wp_enqueue_woocommerce_style()
    {
        // wp_register_style('irantheme-woocommerce-single', IRANTHEME_URI . '/build/index.css');

        if (class_exists('woocommerce')) {
            wp_enqueue_style('irantheme-woocommerce-single');
        }
    }
    add_action('wp_enqueue_scripts', 'irantheme_wp_enqueue_woocommerce_style');
}

if (!function_exists('irantheme_get_sale_percentage')) {
    /**
     * Get product discount percentage
     */
    function irantheme_get_sale_percentage($product)
    {
        if (!$product || !is_a($product, 'WC_Product')) {
            return false;
        }

        $percentages = array();

        if ($product->is_type('variable')) {
            // Get all variation prices
            $prices = $product->get_variation_prices();

            // Ensure all required price arrays are available
            if (
                empty($prices['price']) ||
                empty($prices['regular_price'])
            ) {
                return false;
            }

            // Loop through variation prices
            foreach ($prices['price'] as $key => $price) {
                if (!isset($prices['regular_price'][$key])) {
                    continue;
                }

                $regular_price = (float) $prices['regular_price'][$key];
                $sale_price    = (float) $price;

                // Only on sale variations
                if (
                    $regular_price > 0 &&
                    $sale_price >= 0 &&
                    $sale_price < $regular_price
                ) {
                    // Calculate and set in the array the percentage for each variation on sale
                    $percentages[] = round(
                        100 - ($sale_price / $regular_price * 100),
                        1
                    );
                }
            }
        } elseif ($product->is_type('grouped')) {
            // Get grouped product children
            $children_ids = $product->get_children();

            foreach ($children_ids as $child_id) {
                $child_product = wc_get_product($child_id);

                if (!$child_product) {
                    continue;
                }

                $regular_price = (float) $child_product->get_regular_price();
                $sale_price    = (float) $child_product->get_price();

                // Only calculate valid active discounts
                if (
                    $regular_price > 0 &&
                    $sale_price >= 0 &&
                    $sale_price < $regular_price
                ) {
                    // Calculate and set in the array the percentage for each product on sale
                    $percentages[] = round(
                        100 - ($sale_price / $regular_price * 100),
                        1
                    );
                }
            }
        } else {
            // Get product regular and current prices
            $regular_price = (float) $product->get_regular_price();
            $sale_price    = (float) $product->get_price();

            if (
                $regular_price > 0 &&
                $sale_price >= 0 &&
                $sale_price < $regular_price
            ) {
                $percentage = round(
                    100 - ($sale_price / $regular_price * 100),
                    1
                );

                return '<i class="fa-light fa-percentage"></i>' .
                    '<span>' . $percentage . '</span>';
            }

            return false;
        }

        if (empty($percentages)) {
            return false;
        }

        // We keep the highest value
        $percentage = max($percentages);

        return '<i class="fa-light fa-percentage"></i>' .
            '<span>' . $percentage . '</span>';
    }
}


// =============================================================
// =============================================================
// =============================================================
// =============================================================


// Get current user data 
function fetch_user_data()
{
    if (!is_user_logged_in()) {
        wp_send_json_error('User not logged in', 401);
        die();
    }

    $current_user = wp_get_current_user();
    $current_user_wishlist = get_user_meta($current_user->ID, 'wishlist', true);

    // Initialize as empty array if wishlist is empty
    $wishlist_array = !empty($current_user_wishlist) ? explode(',', $current_user_wishlist) : array();
    $wishlist_sanitized = array();

    if (is_woocommerce_activated()) {
        foreach ($wishlist_array as $product_id) {
            $product_id = absint($product_id); // Sanitize product ID
            $product = wc_get_product($product_id);
            if ($product && $product->get_status() === 'publish') {
                $wishlist_sanitized[] = $product_id;
            }
        }

        // Only update if there were changes
        $new_wishlist = implode(',', $wishlist_sanitized);
        if ($new_wishlist !== $current_user_wishlist) {
            update_user_meta($current_user->ID, 'wishlist', $new_wishlist);
            $current_user_wishlist = $new_wishlist;
        }
    }

    echo json_encode(array('user_id' => $current_user->ID, 'wishlist' => $current_user_wishlist));
    die();
}
add_action('wp_ajax_fetch_user_data', 'fetch_user_data');
add_action('wp_ajax_nopriv_fetch_user_data', 'fetch_user_data');



// =============================================================
// =============================================================
// =============================================================
// =============================================================



function update_wishlist_ajax()
{
    if (isset($_POST["user_id"]) && !empty($_POST["user_id"])) {
        $user_id   = $_POST["user_id"];
        $user_obj = get_user_by('id', $user_id);
        if (!is_wp_error($user_obj) && is_object($user_obj)) {
            update_user_meta($user_id, 'wishlist', $_POST["wishlist"]);
        }
    }
    die();
}
add_action('admin_post_nopriv_user_wishlist_update', 'update_wishlist_ajax');
add_action('admin_post_user_wishlist_update', 'update_wishlist_ajax');


// =============================================================
// =============================================================
// =============================================================
// =============================================================


add_action('init', 'process_post');

function process_post()
{
    // Add wishlist to product 
    // add_action('woocommerce_after_shop_loop_item_title', 'wishlist_toggle', 15);
    // add_action('woocommerce_after_add_to_cart_button', 'wishlist_toggle', 10);
    // function wishlist_toggle()
    // {
    //     global $product;
    //     // Get details color for tabs active mode (Unused)
    //     $details_color = get_option('irantheme_single_product_details_color');
    //     $extra_hover_color_class = '';
    //     if ($details_color !== 'off' && is_product()) {
    //         $extra_hover_color_class = Irantheme_Color_Static::get_class_color_mode('color-hover', $details_color);
    //     }
    //     echo '<div class="irantheme-wishlist-wrapper"><button type="button" class="irantheme-btn-void wishlist-toggle" data-product="' . esc_attr($product->get_id()) . '"><i class="fa-light fa-heart"></i><span class="wishlist-title">' . esc_attr__("افزودن به علاقه مندی", "text-domain") . '</span></button></div>';
    // }


    // =============================================================
    // =============================================================,' . esc_attr($product->get_id()) . '
    add_action('edit_user_profile', 'wishlist_user_profile_field');
    function wishlist_user_profile_field($user)
    { ?>
        <table class="form-table wishlist-data d-none">
            <tr>
                <th><?php echo esc_attr__("Wishlist", "text-domain"); ?></th>
                <td>
                    <input type="text" name="wishlist" id="wishlist" value="<?php echo esc_attr(get_the_author_meta('wishlist', $user->ID)); ?>" class="regular-text" />
                </td>
            </tr>
        </table>
    <?php }
    add_action('personal_options_update', 'save_wishlist_user_profile_field');
    add_action('edit_user_profile_update', 'save_wishlist_user_profile_field');
    function save_wishlist_user_profile_field($user_id)
    {
        if (!current_user_can('edit_user', $user_id)) {
            return false;
        }
        update_user_meta($user_id, 'wishlist', $_POST['wishlist']);
    }
}


// =============================================================
// =============================================================
// =============================================================
// =============================================================


// Add wishlist tab to myAccount dashboard
/**
 * Register new endpoint for user favorites (Wishlist)
 *
 * @author M-MALEKZADEH77
 * @since 1.0.2
 */
function irantheme_add_favorites_endpoint()
{
    add_rewrite_endpoint('favorites', EP_ROOT | EP_PAGES);
}
add_action('init', 'irantheme_add_favorites_endpoint');

/**
 * Add new query var for favorites
 *
 * @param array $vars Existing query vars.
 * @return array
 * @author M-MALEKZADEH77
 * @since 1.0.2
 */
function irantheme_favorites_query_vars($vars)
{
    $vars[] = 'favorites';
    return $vars;
}
add_filter('query_vars', 'irantheme_favorites_query_vars', 0);

/**
 * Insert "Favorites" tab into My Account menu
 * PLACEMENT: After 'Orders'
 *
 * @param array $items My Account menu items.
 * @return array
 * @author M-MALEKZADEH77
 * @since 1.0.2
 */
function irantheme_add_favorites_link_my_account($items)
{
    $new_items = array();

    foreach ($items as $key => $value) {
        $new_items[$key] = $value;
        // Insert after 'orders'
        if ('orders' === $key) {
            $new_items['favorites'] = __('علاقه مندی ها', IRANTHEME_TEXTDOMAIN);
        }
    }

    return $new_items;
}
add_filter('woocommerce_account_menu_items', 'irantheme_add_favorites_link_my_account');

/**
 * Render content for the "Favorites" tab
 * Loads the template file from theme folder.
 *
 * @author M-MALEKZADEH77
 * @since 1.0.2
 */
function irantheme_favorites_content()
{
    wc_get_template('myaccount/favorites.php');
}
add_action('woocommerce_account_favorites_endpoint', 'irantheme_favorites_content');


// =============================================================
// =============================================================
// =============================================================
// =============================================================


// Extend REST API 
function rest_register_fields()
{
    register_rest_field(
        'product',
        'price',
        array(
            'get_callback'    => 'rest_price',
            'update_callback' => null,
            'schema'          => null
        )
    );
    register_rest_field(
        'product',
        'stock',
        array(
            'get_callback'    => 'rest_stock',
            'update_callback' => null,
            'schema'          => null
        )
    );
    register_rest_field(
        'product',
        'image',
        array(
            'get_callback'    => 'rest_img',
            'update_callback' => null,
            'schema'          => null
        )
    );
}
add_action('rest_api_init', 'rest_register_fields');
function rest_price($object, $field_name, $request)
{
    global $product;
    $id = $product->get_id();
    if ($id == $object['id']) {
        return $product->get_price();
    }
}
function rest_stock($object, $field_name, $request)
{
    global $product;
    $id = $product->get_id();
    if ($id == $object['id']) {
        return $product->get_stock_status();
    }
}
function rest_img($object, $field_name, $request)
{
    global $product;
    $id = $product->get_id();
    if ($id == $object['id']) {
        return $product->get_image();
    }
}
function maximum_api_filter($query_params)
{
    $query_params['per_page']["maximum"] = 100;
    return $query_params;
}
add_filter('rest_product_collection_params', 'maximum_api_filter');

// ======================================================================
// ================= Add custom field for product attr =================
// ======================================================================
// These are helper functions you can use elsewhere to access this info
function get_taxonomy_term_type($taxonomy, $term)
{
    return get_option("_term_type_{$taxonomy}_{$term->term_id}");
}
function update_taxonomy_term_type($taxonomy, $term_id, $value)
{
    update_option("_term_type_{$taxonomy}_{$term_id}", $value);
}

add_action('admin_init', function () {
    if (
        is_woocommerce_activated() &&
        isset($_GET['taxonomy'], $_GET['post_type']) &&
        $_GET['post_type'] === 'product'
    ) {
        $taxonomy_name = sanitize_key($_GET['taxonomy']);
        $taxonomy_type = irantheme_get_attribute_custom_option_value($taxonomy_name);

        TaxonomyTermTypes::on_load();
        TaxonomyTermTypes::register_taxonomy($taxonomy_name, $taxonomy_type);
    }
});


class TaxonomyTermTypes
{
    //This initializes the hooks to allow saving of the
    static function on_load()
    {
        add_action('created_term', array(__CLASS__, 'term_type_update'), 10, 3);
        add_action('edit_term', array(__CLASS__, 'term_type_update'), 10, 3);
    }
    //This initializes the hooks to allow adding the custom field to the form fields
    static function register_taxonomy($taxonomy, $taxonomy_type)
    {
        if ($taxonomy_type == 'color') {
            add_action("{$taxonomy}_add_form_fields", array(__CLASS__, "add_form_fields"));
            add_action("{$taxonomy}_edit_form_fields", array(__CLASS__, "edit_form_fields"), 10, 2);
        } elseif ($taxonomy_type == 'brand') {
            add_action("{$taxonomy}_add_form_fields", array(__CLASS__, "add_form_fields"));
            add_action("{$taxonomy}_edit_form_fields", array(__CLASS__, "edit_form_fields"), 10, 2);
        }
    }
    // This displays the custom field in add new item to taxonomy screen
    static function add_form_fields($taxonomy)
    {
        // Get taxonomy type value
        $taxonomy_type =
            irantheme_get_attribute_custom_option_value($taxonomy);
        if ($taxonomy_type == 'color') {
            $input = "رنگ " . self::get_color_picker_html('#000000');
            $html = <<<HTML
        <div class="form-field form-required">$input</div>
        HTML;
            echo $html;
        } elseif ($taxonomy_type == 'brand') {
            $input = "برند " . self::get_media_uploader_html('');
            $html = <<<HTML
        <div class="form-field">$input</div>
        HTML;
            echo $html;
        }
    }
    // This generate a color picker html for display
    static function get_color_picker_html($color_selected)
    {
        $html = <<<HTML
        <input name="irantheme-wc-product-color" id="irantheme-wc-product-color" type="color" class="wpColorChoose" value="$color_selected" />
        HTML;
        return $html;
    }
    // This generate a media uploader html for display
    static function get_media_uploader_html($image_id)
    {
        $image_src = wp_get_attachment_image_src($image_id, 'full');
        $image_thumb = !empty($image_src[0]) ? $image_src[0] : '';
        $basename = 'فاقد تصویر';
        if ($image_id != '') {
            $basename = $image_thumb;
        }
        $html = '<div class="irantheme-settings" data-settings="options">
        <div class="container_image_preview  "><img id="irantheme_wc_product_brand_logo_preview" class="image_preview" src="' . $image_thumb . '"></div><div class="button-expand-group"><button id="irantheme_wc_product_brand_logo_button" type="button" data-uploader_title="Upload an image" data-uploader_button_text="Use image" class="image_upload_button irantheme-button-default irantheme-button-primary button-expand-inline irantheme-button-small">گزینش تصویر</button><button id="irantheme_wc_product_brand_logo_delete" type="button" class="image_delete_button irantheme-button-default irantheme-button-dark button-expand-inline irantheme-button-small">حذف تصویر</button>
        </div><input class="image_basename" type="text" value="' . $basename . '" disabled="">
        <input id="irantheme_wc_product_brand_logo" class="irantheme_image_data_field default-input-image" type="hidden" name="irantheme_wc_product_brand_logo" data-default="" data-default-text="فاقد تصویر"" data-default-url="" value="' . $image_id . '"></div>';
        return $html;
    }
    // This a table row with the custom field for an edit screen
    static function edit_form_fields($term, $taxonomy)
    {
        // Get taxonomy type value
        $taxonomy_type =
            irantheme_get_attribute_custom_option_value($taxonomy);
        if ($taxonomy_type == 'color') {
            $color_selected = get_option("_term_type_{$taxonomy}_{$term->term_id}");
            $select = self::get_color_picker_html($color_selected);
            $html = <<<HTML
        <tr class="form-field form-required">
            <th scope="row" valign="top"><label for="irantheme-wc-product-color">رنگ</label></th>
            <td>$select</td>
            </tr>
        HTML;
            echo $html;
        } elseif ($taxonomy_type == 'brand') {
            $color_selected = get_option("_term_type_{$taxonomy}_{$term->term_id}");
            $select = self::get_media_uploader_html($color_selected);
            $html = <<<HTML
        <tr class="form-field">
            <th scope="row" valign="top"><label for="irantheme-wc-product-color">برند</label></th>
            <td>$select</td>
            </tr>
        HTML;
            echo $html;
        }
    }
    // These hooks are called after adding and editing to save $_POST['tag-term']
    static function term_type_update($term_id, $tt_id, $taxonomy)
    {
        if (isset($_POST['irantheme-wc-product-color'])) {
            update_taxonomy_term_type($taxonomy, $term_id, $_POST['irantheme-wc-product-color']);
        }
        if (isset($_POST['irantheme_wc_product_brand_logo'])) {
            update_taxonomy_term_type($taxonomy, $term_id, $_POST['irantheme_wc_product_brand_logo']);
        }
    }
}

// ======================================================================
// ========== Add shop filtering options for attribute product ==========
// ======================================================================

// Add table row with the add to shop filter options for an edit screen
function edit_wc_attribute_add_to_shop_filter_options()
{
    $id = isset($_GET['edit']) ? absint($_GET['edit']) : 0;
    $add_to_filter_value = $id ? get_option("wc_attribute_irantheme_taxonomy_add_to_filter-$id") : '';
    $index_value = $id ? get_option("wc_attribute_irantheme_taxonomy_index_filter-$id") : '';
    if (!$index_value) {
        $index_value = 1;
    }
    $icon_value =
        $id ? get_option("wc_attribute_irantheme_taxonomy_icon_filter-$id") : '';
    ?>
    <tr class="form-field">
        <th scope="row" valign="top">
            <label for="irantheme_taxonomy_add_to_filter">افزودن به فیلتر های فروشگاه</label>
        </th>
        <td>
            <input type="checkbox" name="irantheme_taxonomy_add_to_filter" id="irantheme-taxonomy-add-to-filter" value="on" <?php checked($add_to_filter_value, 'on'); ?>></input>
            <p class="description">اگر می خواهید این ویژگی در فیلتر های فروشگاه نمایش داده شود این گزینه را فعال کنید.</p>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row" valign="top">
            <label for="irantheme_taxonomy_index_filter">اولویت نمایش در فروشگاه</label>
        </th>
        <td>
            <input type="number" name="irantheme_taxonomy_index_filter" id="irantheme-taxonomy-index-filter" value="<?php echo esc_attr($index_value); ?>" min="1"></input>
            <p class="description">در صورت فعال سازی گزینه افزودن به فیلتر های فروشگاه، با استفاده از این گزینه می توانید ترتیب نمایش فیلتر را تغییر دهید.</p>
        </td>
    </tr>
    <tr class="form-field irantheme-settings" data-settings="options" style="font-size: inherit;">
        <th scope="row" valign="top">
            <label for="irantheme_taxonomy_icon_filter">آیکون باکس فیلتر در فروشگاه</label>
        </th>
        <td>
            <!-- Custom nav menus -->
            <div class="irantheme-custom-nav-menus irantheme-font irantheme-keywords-field" style="max-width: 600px;">
                <!-- Upload icon -->
                <div class="irantheme-icons-selector-fields" style="clear:both;">
                    <!-- Preview icon -->
                    <div class="irantheme-icons-selector-preview <?php echo $icon_value ? 'active' : '' ?>">
                        <i class="<?php echo esc_attr($icon_value); ?>"></i>
                    </div>
                    <!-- ‌Button icon selector -->
                    <div class="irantheme-font irantheme-group-icon-selector">
                        <div class="button-expand-group">
                            <!-- Open icons selector -->
                            <input class="irantheme-button-default irantheme-button-tertiary button-expand-inline irantheme-button-small irantheme-icons-selector-add" type="button" value="انتخاب آیکون" data-icon="<?php echo esc_attr($icon_value); ?>" />
                            <button class="irantheme-button-default irantheme-button-dark button-expand-inline irantheme-button-small irantheme-icons-selector-delete">حذف آیکن</button>
                        </div>
                        <!-- Text input icon -->
                        <input type="hidden" name="irantheme_taxonomy_icon_filter" id="irantheme-taxonomy-icon-filter" class="irantheme-icons-selector-field default-input-icon" value="<?php echo esc_attr($icon_value); ?>" style="width: 100%;">
                    </div>
                </div>
            </div>
            <p class="description">در صورت فعال سازی گزینه افزودن به فیلتر های فروشگاه، با استفاده از این گزینه می توانید آیکون کنار نام در بالای باکس فیلتر را تغییر دهید.</p>
        </td>
        <?php
        Irantheme_Icons_Selector::nav_menu_icons_elements(false);
        ?>
    </tr>
<?php
}
// Add filter checkbox to add screen
function add_wc_attribute_add_to_shop_filter_options()
{
    $id = isset($_GET['edit']) ? absint($_GET['edit']) : 0;
    $add_to_filter_value = $id ? get_option("wc_attribute_irantheme_taxonomy_add_to_filter-$id") : '';
    $index_value = $id ? get_option("wc_attribute_irantheme_taxonomy_index_filter-$id") : '';
    if (!$index_value) {
        $index_value = 1;
    }
    $icon_value =
        $id ? get_option("wc_attribute_irantheme_taxonomy_icon_filter-$id") : '';
?>
    <div class="form-field">
        <label for="irantheme_taxonomy_add_to_filter"><input type="checkbox" name="irantheme_taxonomy_add_to_filter" id="irantheme-taxonomy-add-to-filter" <?php checked($add_to_filter_value); ?>>افزودن به فیلتر های فروشگاه</input></label>
        <p class="description">اگر می خواهید این ویژگی در فیلتر های فروشگاه نمایش داده شود این گزینه را فعال کنید.</p>
    </div>
    <div class="form-field">
        <label for="irantheme_taxonomy_index_filter">اولویت نمایش در فیلتر های فروشگاه<input type="number" name="irantheme_taxonomy_index_filter" id="irantheme-taxonomy-index-filter" value="<?php echo esc_attr($index_value); ?>" min="1"></input>
        </label>
        <p class="description">در صورت فعال سازی گزینه افزودن به فیلتر های فروشگاه، با استفاده از این گزینه می توانید ترتیب نمایش فیلتر را تغییر دهید.</p>
    </div>
    <div class="form-field irantheme-settings" data-settings="options" style="font-size: inherit;">
        <label for="irantheme_taxonomy_icon_filter">آیکون باکس فیلتر در فروشگاه
            <!-- Custom nav menus -->
            <div class="irantheme-custom-nav-menus irantheme-font irantheme-keywords-field" style="max-width: 600px;">
                <!-- Upload icon -->
                <div class="irantheme-icons-selector-fields" style="clear:both;">
                    <!-- Preview icon -->
                    <div class="irantheme-icons-selector-preview <?php echo $icon_value ? 'active' : '' ?>">
                        <i class="<?php echo esc_attr($icon_value); ?>"></i>
                    </div>
                    <!-- ‌Button icon selector -->
                    <div class="irantheme-font irantheme-group-icon-selector">
                        <div class="button-expand-group">
                            <!-- Open icons selector -->
                            <input class="irantheme-button-default irantheme-button-tertiary button-expand-inline irantheme-button-small irantheme-icons-selector-add" type="button" value="انتخاب آیکون" data-icon="<?php echo esc_attr($icon_value); ?>" />
                            <button class="irantheme-button-default irantheme-button-dark button-expand-inline irantheme-button-small irantheme-icons-selector-delete">حذف آیکن</button>
                        </div>
                        <!-- Text input icon -->
                        <input type="hidden" name="irantheme_taxonomy_icon_filter" id="irantheme-taxonomy-icon-filter" class="irantheme-icons-selector-field default-input-icon" value="<?php echo esc_attr($icon_value); ?>" style="width: 100%;">
                    </div>
                </div>
            </div>
        </label>
        <p class="description">در صورت فعال سازی گزینه افزودن به فیلتر های فروشگاه، با استفاده از این گزینه می توانید آیکون کنار نام در بالای باکس فیلتر را تغییر دهید.</p>
        <?php
        Irantheme_Icons_Selector::nav_menu_icons_elements(false);
        ?>
    </div>
<?php
}
add_action('woocommerce_after_add_attribute_fields', 'add_wc_attribute_add_to_shop_filter_options');
add_action('woocommerce_after_edit_attribute_fields', 'edit_wc_attribute_add_to_shop_filter_options');
// Save filter checkbox value in add or edit
function save_wc_attribute_add_to_shop_filter_options($id)
{
    if (is_admin() && isset($_POST['irantheme_taxonomy_add_to_filter'])) {
        $option = "wc_attribute_irantheme_taxonomy_add_to_filter-$id";
        update_option($option, $_POST['irantheme_taxonomy_add_to_filter']);
    } elseif (is_admin()) {
        $option = "wc_attribute_irantheme_taxonomy_add_to_filter-$id";
        update_option($option, false);
    }
    if (is_admin() && isset($_POST['irantheme_taxonomy_index_filter'])) {
        $option = "wc_attribute_irantheme_taxonomy_index_filter-$id";
        update_option($option, $_POST['irantheme_taxonomy_index_filter']);
    }
    if (is_admin() && isset($_POST['irantheme_taxonomy_icon_filter'])) {
        $option = "wc_attribute_irantheme_taxonomy_icon_filter-$id";
        update_option($option, $_POST['irantheme_taxonomy_icon_filter']);
    }
}
add_action('woocommerce_attribute_added', 'save_wc_attribute_add_to_shop_filter_options');
add_action('woocommerce_attribute_updated', 'save_wc_attribute_add_to_shop_filter_options');
// Delete filter checkbox value
add_action('woocommerce_attribute_deleted', function ($id) {
    delete_option("wc_attribute_irantheme_taxonomy_add_to_filter-$id");
    delete_option("wc_attribute_irantheme_taxonomy_index_filter-$id");
    delete_option("wc_attribute_irantheme_taxonomy_icon_filter-$id");
});

// ======================================================================
// ============== Add type drop down for attribute product ==============
// ======================================================================
// Add table row with the drop down for an edit screen
function edit_wc_attribute_taxonomy_type()
{
    $id = isset($_GET['edit']) ? absint($_GET['edit']) : 0;
    $value = $id ? get_option("wc_attribute_irantheme_taxonomy_type-$id") : '';
?>
    <tr class="form-field">
        <th scope="row" valign="top">
            <label for="irantheme-taxonomy-type">نوع ویژگی</label>
        </th>
        <td>
            <select name="irantheme_taxonomy_type" id="irantheme-taxonomy-type">
                <option value="normal" <?php selected($value, 'normal'); ?>>عادی</option>
                <option value="color" <?php selected($value, 'color'); ?>>رنگ</option>
                <option value="size" <?php selected($value, 'size'); ?>>سایز (اندازه)</option>
                <option value="brand" <?php selected($value, 'brand'); ?>>برند</option>
            </select>
            <p class="description">با تغییر نوع ویژگی، خصوصیات مربوط به آن نوع به ویژگی ها اضافه خواهد شد.</p>
        </td>
    </tr>
<?php
}
// Add drop down to add screen
function add_wc_attribute_taxonomy_type()
{
    $id = isset($_GET['edit']) ? absint($_GET['edit']) : 0;
    $value = $id ? get_option("wc_attribute_irantheme_taxonomy_type-$id") : '';
?>
    <div class="form-field">
        <label for="irantheme-taxonomy-type">نوع ویژگی</label>
        <select name="irantheme_taxonomy_type" id="irantheme-taxonomy-type">
            <option value="normal" <?php selected($value, 'normal'); ?>>عادی</option>
            <option value="color" <?php selected($value, 'color'); ?>>رنگ</option>
            <option value="size" <?php selected($value, 'size'); ?>>سایز (اندازه)</option>
            <option value="brand" <?php selected($value, 'brand'); ?>>برند</option>
        </select>
        <p class="description">با تغییر نوع ویژگی، خصوصیات مربوط به آن نوع به ویژگی ها اضافه خواهد شد.</p>
    </div>
<?php
}
add_action('woocommerce_after_add_attribute_fields', 'add_wc_attribute_taxonomy_type');
add_action('woocommerce_after_edit_attribute_fields', 'edit_wc_attribute_taxonomy_type');
// Save input value in add or edit
function save_wc_attribute_taxonomy_type($id)
{
    if (is_admin() && isset($_POST['irantheme_taxonomy_type'])) {
        $option = "wc_attribute_irantheme_taxonomy_type-$id";
        update_option($option, sanitize_text_field($_POST['irantheme_taxonomy_type']));
    }
}
add_action('woocommerce_attribute_added', 'save_wc_attribute_taxonomy_type');
add_action('woocommerce_attribute_updated', 'save_wc_attribute_taxonomy_type');
// Delete input value
add_action('woocommerce_attribute_deleted', function ($id) {
    delete_option("wc_attribute_irantheme_taxonomy_type-$id");
});

// ==============================================================================================
// =================== Display new variation switch in single product ===========================
// ==============================================================================================
function variation_radio_buttons($html, $args)
{

    $args = wp_parse_args(apply_filters('woocommerce_dropdown_variation_attribute_options_args', $args), array(
        'options'          => false,
        'attribute'        => false,
        'product'          => false,
        'selected'         => false,
        'name'             => '',
        'id'               => '',
        'class'            => '',
        'show_option_none' => __('Choose an option', 'woocommerce'),
    ));

    if (false === $args['selected'] && $args['attribute'] && $args['product'] instanceof \WP_product) {
        $selected_key     = 'attribute_' . sanitize_title($args['attribute']);
        $args['selected'] = isset($_REQUEST[$selected_key]) ? wc_clean(wp_unslash($_REQUEST[$selected_key])) : $args['product']->get_variation_default_attribute($args['attribute']);
    }

    // Get replace image option 
    $replace_image = get_post_meta($args['product']->get_id(), 'irantheme_custom_field_product_color_var_image_checkbox', true);

    $options               = $args['options'];
    $product               = $args['product'];
    $attribute             = $args['attribute'];
    $name                  = $args['name'] ? $args['name'] : 'attribute_' . sanitize_title($attribute);
    $id                    = $args['id'] ? $args['id'] : sanitize_title($attribute);
    $class                 = $args['class'];
    $show_option_none      = (bool)$args['show_option_none'];
    $show_option_none_text = $args['show_option_none'] ? $args['show_option_none'] : __('Choose an option', 'woocommerce');
    $_attr_id = wc_attribute_taxonomy_id_by_name($attribute);
    $_taxonomy_type = get_option("wc_attribute_irantheme_taxonomy_type-{$_attr_id}");
    if (empty($options) && !empty($product) && !empty($attr_id)) {
        $attributes = $product->get_variation_attributes();
        $options    = $attributes[$attribute];
    }
    // NEW: Get all available variations once for efficiency
    $available_variations = $product->get_available_variations();
    $variation_data_map = [];
    foreach ($available_variations as $var_data) {
        $variation_data_map[$var_data['attributes'][$name]] = $var_data;
    }

    if ($_taxonomy_type == 'color') {
        $variation_switch = '<div class="irantheme-variation-custom-switch irantheme-variation-switch-' . $_taxonomy_type . '">';

        if (!empty($options)) {
            if ($product && taxonomy_exists($attribute)) {
                $terms = wc_get_product_terms($product->get_id(), $attribute, array(
                    'fields' => 'all',
                ));
                foreach ($terms as $term) {
                    if (in_array($term->slug, $options, true)) {
                        // If the version is not on the map (no price or disabled) -> ignore
                        if (!isset($variation_data_map[$term->slug])) {
                            continue;
                        }

                        $id = $name . '-' . $term->slug;
                        $color_hash = get_option("_term_type_{$attribute}_{$term->term_id}");

                        // NEW: Check stock status
                        $is_in_stock = isset($variation_data_map[$term->slug]) ? $variation_data_map[$term->slug]['is_in_stock'] : false;
                        $stock_class = $is_in_stock ? '' : ' outofstock-variation disable';
                        $attr_name = apply_filters('woocommerce_variation_option_name', $term->name);
                        $attr_value = $term->slug;
                        // Replace image of color variation
                        if ($replace_image == 'on') {
                            $thumb_url = isset($variation_data_map[$term->slug]['image']['thumb_src']) ? $variation_data_map[$term->slug]['image']['thumb_src'] : '';
                            // Create element
                            $variation_switch .= '<div id="' . esc_attr($id) . '" class="irantheme-custom-switch-var irantheme-switch-color-image-var' . $stock_class . '" data-switch-value="' . esc_attr($term->slug) . '" data-switch-title="' . esc_html(apply_filters('woocommerce_variation_option_name', $term->name)) . '" data-attr="' . esc_attr($name) . '" style="background-color:' . esc_attr($color_hash) . '"><img src="' . esc_attr($thumb_url) . '"></div>';
                        } else {
                            // Create element without image (Only display color)
                            $variation_switch .= '<div id="' . esc_attr($id) . '" class="irantheme-custom-switch-var irantheme-switch-color-var' . $stock_class . '" data-switch-value="' . esc_attr($attr_value) . '" data-switch-title="' . esc_html($attr_name) . '" data-attr="' . esc_attr($name) . '"><span class="attr-color ' . Irantheme_Color_Static::get_optimal_text_color(esc_attr($color_hash)) . '" style="background-color:' . esc_attr($color_hash) . ';"></span><span class="attr-label">' . esc_html($attr_name) . '</span></div>';
                        }
                    }
                }
            } else {
                foreach ($options as $option) {
                    $id = $name . '-' . $option;
                    $is_in_stock = isset($variation_data_map[$option]) ? $variation_data_map[$option]['is_in_stock'] : false;
                    $stock_class = $is_in_stock ? '' : ' outofstock-variation';
                    $variation_switch .= '<div id="' . esc_attr($id) . '" class="irantheme-custom-switch-var irantheme-switch-color-var' . $stock_class . '" data-switch-value="' . esc_attr($option) . '" data-switch-title="' . esc_html(apply_filters('woocommerce_variation_option_name', $option)) . '" style="background-color:' . esc_attr($option) . '"><span></span></div>';
                }
            }
        }
        $variation_switch .= '</div>';
    } elseif ($_taxonomy_type == 'size') {
        $variation_switch = '<div class="irantheme-variation-custom-switch irantheme-variation-switch-' . $_taxonomy_type . '">';
        if (!empty($options)) {
            if ($product && taxonomy_exists($attribute)) {
                $terms = wc_get_product_terms($product->get_id(), $attribute, array(
                    'fields' => 'all',
                ));
                foreach ($terms as $term) {
                    if (in_array($term->slug, $options, true)) {
                        $id = $name . '-' . $term->slug;
                        $title = apply_filters('woocommerce_variation_option_name', $term->name);

                        // NEW: Check stock status
                        $is_in_stock = isset($variation_data_map[$term->slug]) ? $variation_data_map[$term->slug]['is_in_stock'] : false;
                        $stock_class = $is_in_stock ? '' : ' outofstock-variation';

                        $variation_switch .= '<div id="' . esc_attr($id) . '" class="irantheme-custom-switch-var irantheme-switch-size-var' . $stock_class . '" data-switch-value="' . esc_attr($term->slug) . '" data-switch-title="' . esc_html($title) . '">' . esc_html($title) . '</div>';
                    }
                }
            } else {
                foreach ($options as $option) {
                    $id = $name . '-' . $option;
                    $is_in_stock = isset($variation_data_map[$option]) ? $variation_data_map[$option]['is_in_stock'] : false;
                    $stock_class = $is_in_stock ? '' : ' outofstock-variation';
                    $variation_switch .= '<div id="' . esc_attr($id) . '" class="irantheme-switch-size-var' . $stock_class . '" data-switch-value="' . esc_attr($option) . '" data-switch-title="' . esc_html(apply_filters('woocommerce_variation_option_name', $option)) . '" >' . esc_html(apply_filters('woocommerce_variation_option_name', $option)) . '</div>';
                }
            }
        }
        $variation_switch .= '</div>';
    }
    return $html . $variation_switch;
}
add_filter('woocommerce_dropdown_variation_attribute_options_html', 'variation_radio_buttons', 20, 2);

function variation_check($active, $variation)
{
    if (!$variation->is_in_stock() && !$variation->backorders_allowed()) {
        return false;
    }
    return $active;
}
add_filter('woocommerce_variation_is_active', 'variation_check', 10, 2);


// Add ajax add to card
add_action('wc_ajax_irantheme_ajax_add_to_cart', 'irantheme_ajax_add_to_cart_handler');
add_action('wc_ajax_nopriv_irantheme_ajax_add_to_cart', 'irantheme_ajax_add_to_cart_handler');
function irantheme_ajax_add_to_cart_handler()
{
    if (isset($_POST['product_id']) && isset($_POST['form_data'])) {
        $product_id = $_POST['product_id'];

        $variation = $cart_item_data = $custom_data = array(); // Initializing
        $variation_id = 0; // Initializing

        foreach ($_POST['form_data'] as $values) {
            if (strpos($values['name'], 'attribute') !== false) {
                $variation[$values['name']] = $values['value'];
            } elseif ($values['name'] === 'quantity') {
                $quantity = ceil($values['value']);
            } elseif ($values['name'] === 'variation_id') {
                $variation_id = $values['value'];
            } elseif ($values['name'] !== 'add_to_cart') {
                $custom_data[$values['name']] = esc_attr($values['value']);
            }
        }

        $product = wc_get_product($variation_id ? $variation_id : $product_id);
        // Allow product custom fields to be added as custom cart item data from $custom_data additional array variable
        $cart_item_data = (array) apply_filters('woocommerce_add_cart_item_data', $cart_item_data, $product_id, $variation_id, $quantity, $custom_data);

        // Add to cart
        $cart_item_key = WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variation, $cart_item_data);

        if ($cart_item_key) {
            // Add to cart successful notice
            wc_add_notice(sprintf(
                '<a href="%s" class="button wc-forward">%s</a> %d &times; "%s" %s',
                wc_get_cart_url(),
                __("View cart", "woocommerce"),
                $quantity,
                $product->get_name(),
                __("has been added to your cart", "woocommerce")
            ));
        }

        wc_print_notices(); // Return printed notices to jQuery response.
        wp_die();
    }
}
// Set products per page in archive product page
add_action('pre_get_posts', 'ps_pre_get_products_query');
function ps_pre_get_products_query($query)
{
    $per_page = get_option('irantheme_wc_archive_product_num_select');
    if ($query->is_main_query() && !is_admin() && is_post_type_archive('product')) {
        $query->set('posts_per_page', $per_page);
    }
}

// Get taxonomy type
function irantheme_get_attribute_custom_option_value($taxonomy_name, $attr_option_name = 'type')
{
    // Get attribute id for check attribute type
    $attr_id = wc_attribute_taxonomy_id_by_name($taxonomy_name);
    $taxonomy_value = false;
    switch ($attr_option_name) {
        case 'type':
            $taxonomy_value = get_option("wc_attribute_irantheme_taxonomy_type-{$attr_id}");
            break;
        case 'add_to_filter':
            $taxonomy_value = get_option("wc_attribute_irantheme_taxonomy_add_to_filter-{$attr_id}");
            break;
        case 'filter_position':
            $taxonomy_value = get_option("wc_attribute_irantheme_taxonomy_index_filter-{$attr_id}");
            break;
        case 'filter_icon':
            $taxonomy_value = get_option("wc_attribute_irantheme_taxonomy_icon_filter-{$attr_id}");
            break;
    }
    // Get taxonomy type value
    return $taxonomy_value;
}



// Convert html price to persian digits
add_filter('woocommerce_get_price_html', 'price_array', 100, 2);
function price_array($price)
{
    return $price;
}

function irantheme_get_page_type()
{
    // global $wp_query;
    $loop = 'notfound';

    if (is_page()) {
        $loop = is_front_page() ? 'front' : 'page';
    } elseif (is_home()) {
        $loop = 'home';
    } elseif (is_single()) {
        $loop = (is_attachment()) ? 'attachment' : 'single';
    } elseif (is_product_category() || is_category()) {
        $loop = 'category';
    } elseif (is_product_tag() || is_tag()) {
        $loop = 'tag';
    } elseif (is_tax('product_cat') || is_tax()) {
        $loop = 'tax';
    } elseif (is_archive()) {
        if (is_day()) {
            $loop = 'day';
        } elseif (is_month()) {
            $loop = 'month';
        } elseif (is_year()) {
            $loop = 'year';
        } elseif (is_author()) {
            $loop = 'author';
        } else {
            $loop = 'archive';
        }
    } elseif (is_search()) {
        $loop = 'search';
    } elseif (is_404()) {
        $loop = 'notfound';
    }

    return $loop;
}

function shop_page_url_condition()
{
    global $wp;
    $paged = get_query_var('paged');

    $shop_page_id = get_option('woocommerce_shop_page_id');
    if (empty($shop_page_id)) {
        return;
    }
    $shop_page_url = get_permalink($shop_page_id);

    if (!empty($paged)) {
        $shop_page_url = get_permalink($shop_page_id) . 'page/' . $paged . '/';
    }
    $current_url = home_url(add_query_arg(array(), $wp->request . '/'));
    if ($shop_page_url == $current_url) {
        return true;
    } else {
        return false;
    }
}

/**
 * Remove wordpress/woocommerce password strength checker
 * @return void
 * @param void
 * @author M-MALEKZADEH77
 */
function irantheme_wc_remove_password_strength()
{
    if (wp_script_is('wc-password-strength-meter', 'enqueued')) {
        wp_dequeue_script('wc-password-strength-meter');
    }
}
add_action('wp_print_scripts', 'irantheme_wc_remove_password_strength', 100);



/**
 * Add avatar field to user account wc
 * @return void
 * @param void
 * @author M-MALEKZADEH77
 */
function action_woocommerce_edit_account_form_start()
{
    $customer_id = get_current_user_id();
    $customer = wp_get_current_user();
    // Avatar
    $avatar_customizable = get_option('irantheme_wc_dashboard_user_avatar_checkbox');
    $avatar_url = irantheme_get_user_avatar_url($customer_id);
    $default_avatar
        = get_avatar_url($customer_id, array(
            'size' => '150',
        ));
?>
    <?php if ($avatar_customizable == 'on'): ?>
        <div class="irantheme-myaccount-edit-account-avatar-col">
            <div class="irantheme-myaccount-edit-account-avatar">
                <div class="irantheme-myaccount-edit-account-avatar-holder">
                    <div class="irantheme-myaccount-edit-account-avatar-img">
                        <img src="<?php echo esc_url_raw($avatar_url); ?>" alt="تصویر پروفایل">
                    </div>
                    <div class="irantheme-myaccount-edit-account-avatar-content">
                        <div class="irantheme-myaccount-edit-account-avatar-content-actions">
                            <a class="irantheme-myaccount-edit-account-avatar-content-actions-change" style="cursor:pointer;"><i class="fa-light fa-pen"></i>تغییر تصویر</a>
                            <a class="irantheme-myaccount-edit-account-avatar-content-actions-delete" data-toggle="tooltip" data-placement="top" title="حذف تصویر" style="cursor:pointer;"><i class="fa-light fa-trash-can-xmark"></i></a>
                            <input class="irantheme-myaccount-edit-account-avatar-input d-none" type="file" class="woocommerce-Input" id="irantheme_input_avatar" name="user_avatar" accept="image/x-png,image/gif,image/jpeg" data-default="<?php echo esc_url_raw($default_avatar); ?>">
                            <input class="irantheme-myaccount-edit-account-avatar-input d-none" type="hidden" class="woocommerce-Input" id="irantheme_input_avatar_hidden" name="user_avatar" disabled>
                        </div>
                        <p class="irantheme-myaccount-edit-account-avatar-desc"><i class="fa-light fa-square-info"></i>برای بهترین نتیجه، از یک تصویر حداقل 256 پیکسل در 256 پیکسل در قالب jpg. یا png. استفاده کنید.</p>
                    </div>
                </div>
            </div>
        </div>
    <?php
    endif;
}
add_action('woocommerce_edit_account_form_start', 'action_woocommerce_edit_account_form_start');

/**
 * Validate data uploaded
 * @return void
 * @param void
 * @author M-MALEKZADEH77
 */
function action_woocommerce_save_account_details_errors($args)
{
    if (isset($_POST['user_avatar']) && empty($_POST['user_avatar'])) {
        $args->add('image_error', __('Please provide a valid image', 'woocommerce'));
    }
    if (isset($_POST['user_banner']) && empty($_POST['user_banner'])) {
        $args->add('image_error', __('Please provide a valid image', 'woocommerce'));
    }
}
add_action('woocommerce_save_account_details_errors', 'action_woocommerce_save_account_details_errors', 10, 1);

/**
 * Save image field
 * @return void
 * @param void
 * @author M-MALEKZADEH77
 */
function action_woocommerce_save_account_details($user_id)
{
    if ((isset($_FILES['user_avatar']) && $_FILES['user_avatar']['name'] != "") || $_POST['user_avatar'] == 'defaultAvatar') {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');

        $attachment_id = media_handle_upload('user_avatar', 0);

        if (is_wp_error($attachment_id) && $_POST['user_avatar'] != 'defaultAvatar') {
            update_user_meta($user_id, 'user_avatar', $_FILES['user_avatar'] . ": " . $attachment_id->get_error_message());
        } else {
            if ($_POST['user_avatar'] == 'defaultAvatar') {
                $user_meta = get_user_meta($user_id, 'user_avatar', true);
                wp_delete_attachment($user_meta, true);
                update_user_meta($user_id, 'user_avatar', 'defaultAvatar');
            } else {
                update_user_meta($user_id, 'user_avatar', $attachment_id);
            }
        }
    }
    if ((isset($_FILES['user_banner']) && $_FILES['user_banner']['name'] != "") || $_POST['user_banner'] == 'defaultBanner') {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');

        $attachment_id = media_handle_upload('user_banner', 0);

        if (is_wp_error($attachment_id) && $_POST['user_banner'] != 'defaultBanner') {
            update_user_meta($user_id, 'user_banner', $_FILES['user_banner'] . ": " . $attachment_id->get_error_message());
        } else {
            if ($_POST['user_banner'] == 'defaultBanner') {
                $user_meta = get_user_meta($user_id, 'user_banner', true);
                wp_delete_attachment($user_meta, true);
                update_user_meta($user_id, 'user_banner', 'defaultBanner');
            } else {
                update_user_meta($user_id, 'user_banner', $attachment_id);
            }
        }
    }
}
add_action('woocommerce_save_account_details', 'action_woocommerce_save_account_details', 10, 1);

/**
 * Add enctype to form to allow image upload
 * @return void
 * @param void
 * @author M-MALEKZADEH77
 */
function action_woocommerce_edit_account_form_tag()
{
    echo 'enctype="multipart/form-data"';
}
add_action('woocommerce_edit_account_form_tag', 'action_woocommerce_edit_account_form_tag');


/**
 * User role
 * @return string
 * @param string $user_id
 * @author M-MALEKZADEH77
 */
function irantheme_user_past($user_id)
{
    $user = get_userdata($user_id);
    $user_registered = date('Y', strtotime($user->user_registered)) - date('Y');
    switch ($user_registered) {
        case 0:
            return 'کم تر از یک سال عضویت';
            break;
        default:
            return 'بیشتر از ' . $user_registered . ' سال عضویت';
            break;
    }
}


// WooCommerce remove brand from meta of single product page
add_action('init', 'irantheme_remove_brand_name_actions');
function irantheme_remove_brand_name_actions()
{
    if (isset($GLOBALS['WC_Brands']) && is_object($GLOBALS['WC_Brands'])) {
        remove_action(
            'woocommerce_product_meta_end',
            array($GLOBALS['WC_Brands'], 'show_brand')
        );
    }
}

// Remove brand from WooCommerce
add_action('init', function () {
    update_option('wc_feature_woocommerce_brands_enabled', 'no');
});

/**
 * Return order count by type of order
 * @param string $order_type total, pending, processing, on-hold, completed, cancelled, failed, refunded
 * @return int
 * @author M-MALEKZADEH77
 */
function irantheme_get_customer_order_count($order_type = 'total')
{
    $status_map = [
        'total'      => ['wc-pending', 'wc-processing', 'wc-on-hold', 'wc-completed', 'wc-cancelled', 'wc-refunded', 'wc-failed'],
        'pending'    => 'wc-pending',
        'processing' => 'wc-processing',
        'on-hold'    => 'wc-on-hold',
        'completed'  => 'wc-completed',
        'cancelled'  => 'wc-cancelled',
        'failed'     => 'wc-failed',
        'refunded'   => 'wc-refunded',
    ];

    if (!isset($status_map[$order_type])) {
        return 0;
    }

    $args = [
        'customer_id' => get_current_user_id(),
        'return'      => 'ids',
        'limit'       => -1,
        'type'        => 'shop_order',
        'status'      => $status_map[$order_type],
    ];

    return count(wc_get_orders($args));
}


/**
 * Add phone number fields to edit user profilein admin page
 * @param WP_User $user
 * @author M-MALEKZADEH77
 */
function add_mobile_number_to_admin_user_profile_form($user)
{
    ?>
    <h3><?php esc_html_e('اطلاعات تماس اضافی'); ?></h3>
    <table class="form-table">
        <tr>
            <th><label for="mobile_number_admin"><?php esc_html_e('شماره موبایل(لازم)'); ?></label></th>
            <td>
                <input type="text" name="mobile_number" id="mobile_number_admin" value="<?php echo esc_attr(get_user_meta($user->ID, 'mobile_number', true)); ?>" class="regular-text" placeholder="مثال: 09123456789" required /><br />
                <span class="description"><?php esc_html_e('شماره موبایل کاربر برای ورود با کد یکبارمصرف.'); ?></span>
            </td>
        </tr>
    </table>
    <?php
}
// profile.php
add_action('show_user_profile', 'add_mobile_number_to_admin_user_profile_form');
// user-edit.php
add_action('edit_user_profile', 'add_mobile_number_to_admin_user_profile_form');
// user-new.php
add_action('user_new_form', 'add_mobile_number_to_admin_user_profile_form');

/**
 * Validate mobile number value before save
 * @param WP_Error $errors error object
 * @param bool $update update flag
 * @param WP_User $user user object
 * @author M-MALEKZADEH77
 */
function validate_mobile_number_admin_user_on_save($errors, $update, $user)
{
    // Get mobile number by $_POST
    $mobile_number = '';
    if (isset($_POST['mobile_number'])) {
        $mobile_number = sanitize_text_field($_POST['mobile_number']);
    }

    $current_user_id = ($user instanceof WP_User) ? $user->ID : 0; // For new user -> 0

    // Validate mobile number -> has empty
    if (empty($mobile_number)) {
        $errors->add('mobile_number_empty', esc_html__('خطا: شماره موبایل الزامی است و نمی‌تواند خالی باشد.', IRANTHEME_TEXTDOMAIN));
    }
    // Validate mobile number -> format number
    elseif (! preg_match('/^09[0-9]{9}$/', $mobile_number)) {
        $errors->add('mobile_number_invalid_format', esc_html__('خطا: شماره موبایل وارد شده معتبر نیست. لطفا با فرمت 09xxxxxxxxx وارد کنید.', IRANTHEME_TEXTDOMAIN));
    }
    // Validate mobile number -> has duplicate
    else {
        $exclude_ids = array();
        if ($current_user_id !== 0) { // If user existing
            // Get old mobile number
            $old_mobile_number = get_user_meta($current_user_id, 'mobile_number', true);
            if ($mobile_number === $old_mobile_number) {
                // If mobile number no changing, return
                return;
            }
            $exclude_ids[] = $current_user_id;
        }

        $existing_user_query = new WP_User_Query(array(
            'meta_key'     => 'mobile_number',
            'meta_value'   => $mobile_number,
            'number'       => 1,
            'fields'       => 'ID',
            'exclude'      => $exclude_ids, // Remove current user from search list
        ));
        if (! empty($existing_user_query->get_results())) {
            $errors->add('mobile_number_duplicate', esc_html__('خطا: این شماره موبایل قبلاً توسط کاربر دیگری ثبت شده است. لطفاً شماره دیگری وارد کنید.', IRANTHEME_TEXTDOMAIN));
        }
    }
}
// Used in add or edit user
add_action('user_profile_update_errors', 'validate_mobile_number_admin_user_on_save', 10, 3);


/**
 * Save mobile number
 * @param int $user_id user id
 * @param array $old_user_data old data of user
 * @author M-MALEKZADEH77
 */
function save_mobile_number_after_validation($user_id, $old_user_data)
{
    if (! current_user_can('edit_user', $user_id)) {
        return false;
    }

    if (! isset($_POST['mobile_number'])) {
        return;
    }

    $mobile_number = sanitize_text_field($_POST['mobile_number']);

    // If mobile number has empty 
    if (empty($mobile_number)) {
        // Delete and return becuse empty status has check in validate function
        delete_user_meta($user_id, 'mobile_number');
        return;
    }

    $old_mobile_number = get_user_meta($user_id, 'mobile_number', true);

    // If the new number is different from the previous one, or if it did not have a number before
    if ($mobile_number !== $old_mobile_number) {
        update_user_meta($user_id, 'mobile_number', $mobile_number);
    }

    // Manage success message (only for profile edit pages, not adding new user)
    // On adding a new user, WordPress itself displays the success message "User added".
    global $pagenow;
    if ($pagenow !== 'user-new.php') {
        if ($mobile_number !== $old_mobile_number || ($mobile_number === $old_mobile_number && get_user_meta($user_id, 'mobile_number', true))) {
            set_transient('mobile_number_profile_success_' . $user_id, esc_html__('شماره موبایل با موفقیت به‌روزرسانی شد.'), 60);
        }
    }
}
add_action('profile_update', 'save_mobile_number_after_validation', 10, 2);
add_action('user_register', 'save_mobile_number_after_validation', 10, 2);

/**
 * Display error or success messages stored in transit.
 * @author M-MALEKZADEH77
 */
function display_mobile_number_profile_messages()
{
    global $pagenow;
    $user_id = get_current_user_id(); // For the user's own profile

    // If we are on another user's edit page
    if ($pagenow === 'user-edit.php' && isset($_GET['user_id'])) {
        $user_id = intval($_GET['user_id']);
    }

    // Trying to get errors from transit
    $errors = get_transient('mobile_number_profile_errors_' . $user_id);
    if ($errors) {
        foreach ($errors as $error_message) {
            echo '<div id="message" class="error"><p>' . $error_message . '</p></div>';
        }
        delete_transient('mobile_number_profile_errors_' . $user_id); // We delete messages after viewing.
    }

    // Trying to get a success message from Transit
    $success_message = get_transient('mobile_number_profile_success_' . $user_id);
    if ($success_message) {
        echo '<div id="message" class="updated notice is-dismissible"><p>' . $success_message . '</p></div>';
        delete_transient('mobile_number_profile_success_' . $user_id); // We delete messages after viewing.
    }
}
add_action('admin_notices', 'display_mobile_number_profile_messages');

/**
 * Validate mobile number In woocommerce register form (login page)
 * @param object $errors error
 * @param string $username username
 * @param string $email email
 * @return object errors object
 * @author M-MALEKZADEH77
 */
function validate_mobile_number_registration($errors, $username, $email)
{
    if (isset($_POST['mobile_number'])) {
        $mobile_number = sanitize_text_field($_POST['mobile_number']);

        if (empty($mobile_number)) {
            $errors->add('mobile_number_empty', esc_html__('لطفاً شماره موبایل خود را وارد کنید.'));
        } elseif (!preg_match('/^(0?9[0-9]{9})$/', $mobile_number)) {
            $errors->add('mobile_number_invalid', esc_html__('شماره موبایل وارد شده معتبر نیست. لطفاً با فرمت 09xxxxxxxxx یا 9xxxxxxxxx وارد کنید.'));
        } else {
            // Check for duplicate mobile numbers

            // Standardize mobile numbers before searching
            // This section should be the same as your standardization logic in save_mobile_number_registration_field and request_otp_for_login.
            $standardized_mobile_number = preg_replace('/[^0-9]/', '', $mobile_number);
            if (strlen($standardized_mobile_number) === 10 && substr($standardized_mobile_number, 0, 1) === '9') {
                $standardized_mobile_number = '0' . $standardized_mobile_number;
            }

            // Search the database to find users with this mobile number
            $user_query = new \WP_User_Query(array(
                'meta_key'     => 'mobile_number',
                'meta_value'   => $standardized_mobile_number,
                'number'       => 1,
                'fields'       => 'ID',
            ));

            if (!empty($user_query->get_results())) {
                // If a user with this mobile number is found, we add an error.
                $errors->add('mobile_number_exists', esc_html__('این شماره موبایل قبلاً ثبت شده است. لطفاً وارد شوید یا از شماره دیگری استفاده کنید.'));
            }
        }
    } else {
        // If the field was not submitted at all (e.g. form manipulation)
        $errors->add('mobile_number_missing', esc_html__('فیلد شماره موبایل الزامی است.'));
    }

    return $errors;
}
add_filter('woocommerce_registration_errors', 'validate_mobile_number_registration', 10, 3);
/**
 * Save mobile number In woocommerce register form (login page)
 * @param int $customer_id user_id or customer_id
 * @author M-MALEKZADEH77
 */
function save_mobile_number_registration_field($customer_id)
{
    if (isset($_POST['mobile_number'])) {
        $mobile_number = sanitize_text_field($_POST['mobile_number']);

        // **Standardize mobile number to 09xxxxxxxxx format**
        // First, we remove all non-numeric characters.
        $mobile_number = preg_replace('/[^0-9]/', '', $mobile_number);

        // If the number was 10 digits and started with '9' (i.e. 912...), we add a '0' to the beginning.
        if (strlen($mobile_number) === 10 && substr($mobile_number, 0, 1) === '9') {
            $mobile_number = '0' . $mobile_number;
        }

        // At this point, $mobile_number must be exactly 11 digits long and start with '09'.
        // If it doesn't format for some reason, it's best not to save it or log it.
        if (strlen($mobile_number) === 11 && substr($mobile_number, 0, 2) === '09') {
            update_user_meta($customer_id, 'mobile_number', $mobile_number);
        }
    }
}
add_action('woocommerce_created_customer', 'save_mobile_number_registration_field');

/**
 * Add mobile field to Block-based checkout
 */
function add_mobile_field_to_block_checkout($checkout_fields)
{
    if (!is_user_logged_in()) {
        $checkout_fields['account']['mobile_number'] = array(
            'label' => __('شماره موبایل', IRANTHEME_TEXTDOMAIN),
            'required' => true,
            'type' => 'tel',
            'class' => array('form-row-wide'),
            'placeholder' => __('09123456789', IRANTHEME_TEXTDOMAIN),
            'priority' => 25, // بعد از ایمیل و قبل از رمز عبور
        );
    }
    return $checkout_fields;
}
add_filter('woocommerce_checkout_fields', 'add_mobile_field_to_block_checkout');

/**
 * Validate mobile number in checkout
 */
function validate_mobile_number_checkout($errors, $data)
{
    if (!is_user_logged_in() && isset($_POST['createaccount']) && $_POST['createaccount'] == 1) {

        if (isset($_POST['mobile_number'])) {
            $mobile_number = sanitize_text_field($_POST['mobile_number']);

            if (empty($mobile_number)) {
                $errors->add('mobile_number_empty', __('لطفاً شماره موبایل خود را وارد کنید.', IRANTHEME_TEXTDOMAIN));
            } elseif (!preg_match('/^(0?9[0-9]{9})$/', $mobile_number)) {
                $errors->add('mobile_number_invalid', __('شماره موبایل معتبر نیست. لطفاً با فرمت 09xxxxxxxxx وارد کنید.', IRANTHEME_TEXTDOMAIN));
            } else {
                // Check for duplicates
                $standardized_mobile_number = preg_replace('/[^0-9]/', '', $mobile_number);
                if (strlen($standardized_mobile_number) === 10 && substr($standardized_mobile_number, 0, 1) === '9') {
                    $standardized_mobile_number = '0' . $standardized_mobile_number;
                }

                $user_query = new WP_User_Query(array(
                    'meta_key'     => 'mobile_number',
                    'meta_value'   => $standardized_mobile_number,
                    'number'       => 1,
                    'fields'       => 'ID',
                ));

                if (!empty($user_query->get_results())) {
                    $errors->add('mobile_number_duplicate', __('این شماره موبایل قبلاً ثبت شده است.', IRANTHEME_TEXTDOMAIN));
                }
            }
        } else {
            $errors->add('mobile_number_missing', __('فیلد شماره موبایل الزامی است.', IRANTHEME_TEXTDOMAIN));
        }
    }

    return $errors;
}
add_filter('woocommerce_after_checkout_validation', 'validate_mobile_number_checkout', 10, 2);

/**
 * Save mobile number in checkout
 */
function save_mobile_number_checkout($order_id, $data)
{
    if (isset($_POST['createaccount']) && $_POST['createaccount'] == 1) {

        if (isset($_POST['mobile_number']) && !empty($_POST['mobile_number'])) {
            $mobile_number = sanitize_text_field($_POST['mobile_number']);

            // Standardize
            $mobile_number = preg_replace('/[^0-9]/', '', $mobile_number);
            if (strlen($mobile_number) === 10 && substr($mobile_number, 0, 1) === '9') {
                $mobile_number = '0' . $mobile_number;
            }

            if (strlen($mobile_number) === 11 && substr($mobile_number, 0, 2) === '09') {
                // Save to order
                update_post_meta($order_id, '_billing_mobile', $mobile_number);

                // Save to user
                $order = wc_get_order($order_id);
                $user_id = $order->get_customer_id();
                if ($user_id) {
                    update_user_meta($user_id, 'mobile_number', $mobile_number);
                    update_user_meta($user_id, 'billing_phone', $mobile_number);
                }
            }
        }
    }
}
add_action('woocommerce_checkout_update_order_meta', 'save_mobile_number_checkout', 10, 2);

/**
 * Additional save when creating user
 */
function save_mobile_number_created_customer($customer_id)
{
    if (isset($_POST['mobile_number']) && !empty($_POST['mobile_number'])) {
        $mobile_number = sanitize_text_field($_POST['mobile_number']);

        // Standardize
        $mobile_number = preg_replace('/[^0-9]/', '', $mobile_number);
        if (strlen($mobile_number) === 10 && substr($mobile_number, 0, 1) === '9') {
            $mobile_number = '0' . $mobile_number;
        }

        if (strlen($mobile_number) === 11 && substr($mobile_number, 0, 2) === '09') {
            update_user_meta($customer_id, 'mobile_number', $mobile_number);
            update_user_meta($customer_id, 'billing_phone', $mobile_number);
        }
    }
}
add_action('woocommerce_created_customer', 'save_mobile_number_created_customer');


/**
 * Purge OTP information (code, expiration time, and number of attempts) from user metadata
 * When a user successfully logs in to the site, regardless of the login method.
 * This helps prevent expired or unused information from remaining in the database.
 *
 * @param string $user_login Username of the logged in user
 * @param WP_User $user_obj User object entered
 * @author M-MALEKZADEH77
 */
function clear_otp_meta_on_user_login($user_login, $user_obj)
{
    $user_id = $user_obj->ID;

    // Delete saved OTP code
    delete_user_meta($user_id, '_otp_code');

    // Delete saved expiration time
    delete_user_meta($user_id, '_otp_expiration');

    // Delete saved attempts
    delete_user_meta($user_id, '_otp_attempts');
}
add_action('wp_login', 'clear_otp_meta_on_user_login', 10, 2);


/**
 * Filters the authentication process to check if a user or their IP is currently locked out
 * due to excessive failed OTP attempts or too many OTP resend requests.
 * If advanced security is enabled and the user/IP is locked, it prevents login
 * via traditional username/password forms for a specified duration.
 *
 * @param WP_User|WP_Error|null $user The WP_User object, WP_Error object, or null.
 * @param string $username The username (or email address) attempting to log in.
 * @param string $password The password attempting to log in.
 * @return WP_User|WP_Error The WP_User object if authentication should proceed, or a WP_Error object if login is blocked.
 * @author M-MALEKZADEH77
 */
function irantheme_otp_check_user_lockout_on_login($user, $username, $password)
{
    // Return if the $user input is a WP_Error (i.e. an error has already occurred) or the user is not found.
    if (is_wp_error($user) || empty($user) || !($user instanceof WP_User)) {
        return $user;
    }

    // Check the status of your advanced security settings
    // Convert to boolean for reliable checks. Default to false if option is not set.
    $advanced_security_enabled = get_option('irantheme_otp_login_restrictions_login_checkbox');

    // Get excluded IPs from settings
    $excluded_ips_raw = get_option('irantheme_otp_excluded_ips', '');
    $excluded_ips_array = array_map('trim', explode(',', $excluded_ips_raw));
    $excluded_ips_array = array_filter($excluded_ips_array); // Remove empty entries

    // Get the user's IP address.
    // Ensure Irantheme_Settings_Options_Ajax::get_user_ip_address() is accessible and returns the correct IP.
    $current_ip = Irantheme_Settings_Options_Ajax::get_user_ip_address();

    // Get time limit from setting (ensure consistency with the request_otp_for_login function)
    $lockout_duration_time = get_option('irantheme_otp_login_restrictions_login_time', 15); // Default to 15 minutes
    $lockout_duration = (int)$lockout_duration_time * MINUTE_IN_SECONDS;

    // --- Start: New/Modified Logic for IP and User Lockout Checks ---

    // If advanced security is NOT enabled OR the current IP is in the exclusion list,
    // skip all lockout checks and allow login.
    if ($advanced_security_enabled != 'on' || in_array($current_ip, $excluded_ips_array)) {
        // Clear any existing user lockout meta for this user, in case they were previously locked
        delete_user_meta($user->ID, '_otp_lockout_until');
        // Also clear any IP lockout for this specific IP, if it's excluded.
        delete_transient('otp_ip_lockout_' . $current_ip);
        return $user;
    }

    // 1. Check for **User-specific lockout**
    $lockout_until = get_user_meta($user->ID, '_otp_lockout_until', true);
    if ($lockout_until && time() < $lockout_until) {
        $remaining_lockout = $lockout_until - time();
        return new \WP_Error(
            'locked_out_by_otp_system',
            sprintf(esc_html__('حساب کاربری شما به دلیل تلاش‌های مشکوک به مدت %s دقیقه محدود شده است. لطفاً پس از %s ثانیه دیگر تلاش کنید.', 'your-textdomain'), round($lockout_duration / MINUTE_IN_SECONDS), $remaining_lockout)
        );
    }
    // If the user's lockout time has passed, clear the meta.
    else if ($lockout_until && time() >= $lockout_until) {
        delete_user_meta($user->ID, '_otp_lockout_until');
    }

    // 2. Check for **IP-specific lockout** (NEW addition)
    $ip_lockout_until = get_transient('otp_ip_lockout_' . $current_ip);
    if ($ip_lockout_until && time() < $ip_lockout_until) {
        $remaining_lockout = $ip_lockout_until - time();
        return new \WP_Error(
            'ip_locked_out_by_otp_system', // New error code for clarity
            sprintf(esc_html__('این IP به دلیل تلاش‌های مشکوک به مدت %s دقیقه محدود شده است. لطفاً پس از %s ثانیه دیگر تلاش کنید.', 'your-textdomain'), round($lockout_duration / MINUTE_IN_SECONDS), $remaining_lockout)
        );
    }
    // If the IP's lockout time has passed, clear the transient.
    else if ($ip_lockout_until && time() >= $ip_lockout_until) {
        delete_transient('otp_ip_lockout_' . $current_ip);
    }

    // --- End: New/Modified Logic ---

    // If the user and their IP are not locked out, allow the normal WordPress login process to continue
    return $user;
}

// Ensure this filter is added only once and in a place that executes on every page load.
add_filter('authenticate', 'irantheme_otp_check_user_lockout_on_login', 20, 3);

    // ============================================================================================================

/**
 * Replace the "تومان" and "ریال" currency symbol with an SVG on the front-end.
 *
 * @param string $currency_symbol The original currency symbol.
 * @param string $currency The three-letter currency code (e.g., 'IRT', 'IRR').
 * @return string The new SVG symbol or the original currency symbol.
 * @author M-MALEKZADEH77
 * @since 1.0
 */
// function irantheme_ir_svg_currency_symbol($currency_symbol, $currency)
// {
//     // Only apply this change on the front-end, not in the WordPress admin dashboard.
//     if (! is_admin()) {
//         if (is_cart() || is_checkout() || is_wc_endpoint_url('order-received')) {
//             return $currency_symbol;
//         } else {
//             if ('IRT' === $currency) {
//                 // Replace the currency symbol with a valid and functional SVG.
//                 $svg_symbol = '<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" id="currency-toman"><g class="absolute-path"><path d="M12.229 21.556c3.132-.514 3.715-2.368 3.739-2.45l.095-.32h-.335c-2.18 0-3.53-.385-3.53-2.3 0-2.396 1.7-3.39 2.61-3.39 2.575 0 2.68 3.895 2.68 3.934l.005.245h1.925c.613 0 1.35-.06 1.61-.528.524-.926.106-2.8-.072-3.476l1.34-.361c.199.723.738 2.992.096 4.338-.532 1.12-1.486 1.536-3.523 1.536-.388 0-.808-.015-1.258-.038l-.247-.013-.016.247c-.008.126-.244 3.038-4.594 3.882zm2.5-6.92c-.625 0-1.121.64-1.18 1.523a1.028 1.028 0 00.236.81 1.179 1.179 0 00.892.326h1.47v-.25c.001-1.419-.583-2.41-1.419-2.41zM9.108 18.925a3.55 3.55 0 01-2.694-1.098l-.216-.19-.16.241a2.423 2.423 0 01-2.023.936 3.807 3.807 0 01-.888-.112c-2.089-.508-2.089-2.296-2.089-3.257V9.89l1.39 1.397v4.337a1.423 1.423 0 001.183 1.665 1.494 1.494 0 00.284.027 2.186 2.186 0 001.833-1.444c.76-1.798 1.598-2.599 2.72-2.599a2.637 2.637 0 012.079.843 3.718 3.718 0 01.733 2.826 2.032 2.032 0 01-2.152 1.982zm-.42-4.13c-1.214 0-1.633 1.4-1.65 1.46l-.04.137.097.103a2.527 2.527 0 001.753 1.016 1.779 1.779 0 00.537-.088c.427-.146.631-.654.524-1.294-.107-.642-.534-1.334-1.22-1.334zM22.858 10.753l-.97.97-.97-.97.97-.97zM20.317 10.753l-.97.97-.97-.97.97-.97zM7.286 2.179l-.97.97-.971-.97.97-.97z" class="absolute-path"></path><path d="M6.264 10.966c-2.75 0-4.404-1.305-4.657-3.674a8.86 8.86 0 0 1 .692-4.198l1.253.527c-.276.752-1.026 3.125-.114 4.627a2.728 2.728 0 0 0 2.264 1.23c.662.055.943.058 1.008.058A3.002 3.002 0 0 0 9.54 7.91c.728-1.561.043-3.789-.385-4.655l1.441-.495c.338.732 1.27 3.796.245 5.93-.686 1.43-2.119 2.193-4.258 2.27a8.782 8.782 0 0 1-.318.006z" class="absolute-path"></path></g></svg>';
//                 return $svg_symbol;
//             } elseif ('IRR' === $currency) {
//                 $svg_symbol = '<svg xmlns="http://www.w3.org/2000/svg" id="currency-rial" width="25" height="25" viewBox="0 0 25 25"><path d="M11,17H13V19H11V17M14,17H16V19H14V17M9,4H11V15A4,4 0 0,1 7,19H5A3,3 0 0,1 2,16V12H4V16A1,1 0 0,0 5,17H7C8.11,17 9,16.11 9,15V4M12,4H14V13H17V8H19V13C19,14.11 18.11,15 17,15H14C12.89,15 12,14.11 12,13V4M20,10H22V17A3,3 0 0,1 19,20H17V18H19A1,1 0 0,0 20,17V10Z"/></svg>';
//                 return $svg_symbol;
//             }
//         }
//     }

//     return $currency_symbol;
// }

// add_filter('woocommerce_currency_symbol', 'irantheme_ir_svg_currency_symbol', 10, 2);
    // ============================================================================================================
/**
 * Add custom sale percentage HTML to the variation price HTML.
 *
 * @param array $variation_data The variation data array.
 * @param WC_Product_Variable $product The parent variable product object.
 * @param WC_Product_Variation $variation The variation product object.
 * @return array The modified variation data array.
 * @author M-MALEKZADEH77
 * @since 1.0
 */
function irantheme_add_sale_percentage_to_variation_price($variation_data, $product, $variation)
{
    // Check if the variation is on sale and has a price.
    if ($variation->is_on_sale()) {
        if (empty($variation_data['price_html'])) {
            $price_html_generated = $variation->get_price_html();
            if (!empty($price_html_generated)) {
                $variation_data['price_html'] = '<span class="price">' . $price_html_generated . '</span>';
            }
        }
        // Generate the custom sale percentage HTML.
        $sale_percentage_html = '<span class="price-offer-percentage">' . irantheme_get_sale_percentage($variation) . '</span>';

        // Find the start of the price HTML and insert our custom code right after it.
        $price_html_modified = str_replace('<span class="price">', '<span class="price offer-percentage">' . $sale_percentage_html, $variation_data['price_html']);

        // Update the price HTML in the variation data.
        $variation_data['price_html'] = $price_html_modified;
    } else {
        // Force display of the price for non-sale products.
        $price_content = wc_price($variation->get_price());
        $variation_data['price_html'] = '<span class="price">' . $price_content . '</span>';
    }
    return $variation_data;
}
add_filter('woocommerce_available_variation', 'irantheme_add_sale_percentage_to_variation_price', 10, 3);

/**
 * This filter is added to the data for the product variable in JavaScript.
 * This allows JavaScript to get the price and other information for the "any color" variables.
 * @author M-MALEKZADEH77
 * @since 1.0
 */
function irantheme_add_all_attributes_to_variation_data($variation_data, $product, $variation)
{
    // If the properties are not set correctly, there is no need to continue.
    if (empty($variation->get_attributes())) {
        return $variation_data;
    }

    // Check if the "anycolor" property exists.
    foreach ($variation->get_attributes() as $attribute_name => $attribute_value) {
        if ($attribute_value === '') {
            $terms = get_terms($attribute_name, array('hide_empty' => false));
            $term_names = wp_list_pluck($terms, 'slug');

            // If the "Any Color" feature is selected
            if (in_array('', $variation->get_variation_attributes())) {
                foreach ($term_names as $term_name) {
                    $new_variation_data = $variation_data;
                    $new_variation_data['attributes'][$attribute_name] = $term_name;
                    $variation_data['irantheme_variations'][] = $new_variation_data;
                }
            }
        }
    }

    return $variation_data;
}
add_filter('woocommerce_available_variation', 'irantheme_add_all_attributes_to_variation_data', 10, 3);

/**
 * Hide price and add 'free' label for free product (price of product is 0)
 * @author M-MALEKZADEH77
 * @since 1.0
 */
function irantheme_free_product_price_html($price, $product)
{
    if ($product->get_price() == 0) {
        return '<span class="free-product-price">رایگان!</span>';
    }
    return $price;
}
add_filter('woocommerce_get_price_html', 'irantheme_free_product_price_html', 99, 2);

/**
 * Add meta filed in to product edit page for call for price status
 * @author M-MALEKZADEH77
 * @since 1.0
 */
function add_call_for_price_option()
{
    woocommerce_wp_checkbox(array(
        'id' => 'irantheme_product_meta_is_call_for_price',
        'label' => 'نمایش "تماس بگیرید"',
        'description' => 'به جای قیمت، دکمه تماس بگیرید نمایش داده شود.</br> شماره تماس مورد نظر خود را می توانید در بخش "تنظیمات قالب" -> "ووکامرس" -> "صفحه معرفی محصول" تنظیم نمایید.'
    ));

    woocommerce_wp_text_input(array(
        'id' => 'irantheme_product_meta_last_price_amount',
        'label' => 'آخرین قیمت',
        'placeholder' => '۵,۰۰۰,۰۰۰',
        'data_type' => 'price',
        'desc_tip' => true,
        'description' => 'قیمت قبلی محصول (در صورت عدم نیاز به نمایش، این فیلد را خالی بگذارید.)'
    ));

    woocommerce_wp_text_input(array(
        'id' => 'irantheme_product_meta_last_price_date',
        'label' => 'تاریخ آخرین قیمت',
        'placeholder' => '۱۴۰۳-۰۱-۱۵',
        'class' => 'short',
        'desc_tip' => true,
        'description' => 'تاریخ به صورت شمسی یا میلادی (در صورت عدم نیاز به نمایش، این فیلد را خالی بگذارید.)'
    ));
    // Add datepicker script
    echo '<script>jQuery(document).ready(function($) {$("input#irantheme_product_meta_last_price_date").datepicker({dateFormat: "yy-mm-dd",changeYear: true,yearRange: "-2:+1"});});</script>';
}
add_action('woocommerce_product_options_general_product_data', 'add_call_for_price_option');

/**
 * Save value of meta filed call for price status
 * @author M-MALEKZADEH77
 * @since 1.0
 */
function save_call_for_price_option($product_id)
{
    $is_call_for_price = isset($_POST['irantheme_product_meta_is_call_for_price']) ? 'yes' : 'no';
    update_post_meta($product_id, 'irantheme_product_meta_is_call_for_price', $is_call_for_price);

    if (isset($_POST['irantheme_product_meta_contact_number_call'])) {
        update_post_meta($product_id, 'irantheme_product_meta_contact_number_call', sanitize_text_field($_POST['irantheme_product_meta_contact_number_call']));
    }
    if (isset($_POST['irantheme_product_meta_last_price_amount'])) {
        update_post_meta($product_id, 'irantheme_product_meta_last_price_amount', sanitize_text_field($_POST['irantheme_product_meta_last_price_amount']));
    }
    if (isset($_POST['irantheme_product_meta_last_price_date'])) {
        update_post_meta($product_id, 'irantheme_product_meta_last_price_date', sanitize_text_field($_POST['irantheme_product_meta_last_price_date']));
    }
}
add_action('woocommerce_process_product_meta', 'save_call_for_price_option');

// =========================================================================
// ==================== Add product quetions and answeres ==================
// =========================================================================
if (class_exists('WooCommerce')) {
    /**
     * Register a custom post type for product Q&A
     * @author M-MALEKZADEH77
     * @since 1.0
     */
    function register_product_qa_post_type()
    {
        $labels = array(
            'name'                  => 'پرسش و پاسخ محصولات',
            'singular_name'         => 'پرسش و پاسخ',
            'menu_name'             => 'پرسش و پاسخ محصولات',
            'name_admin_bar'        => 'پرسش و پاسخ',
            'add_new'               => 'افزودن پرسش و پاسخ',
            'add_new_item'          => 'افزودن پرسش و پاسخ جدید',
            'new_item'              => 'پرسش و پاسخ جدید',
            'edit_item'             => 'ویرایش پرسش و پاسخ',
            'view_item'             => 'مشاهده پرسش و پاسخ',
            'search_items'          => 'جستجوی پرسش و پاسخ',
            'parent_item_colon'     => 'پرسش و پاسخ والد:',
            'not_found'             => 'هیچ پرسش و پاسخی یافت نشد.',
            'not_found_in_trash'    => 'هیچ پرسش و پاسخی در زباله‌دان یافت نشد.'
        );

        $args = array(
            'labels'                => $labels,
            'public'                => false,
            'show_ui'               => true,
            'show_in_menu'          => 'edit.php?post_type=product',
            'menu_icon'             => 'dashicons-format-chat',
            'query_var'             => true,
            'rewrite'               => array('slug' => 'product-qa'),
            'capability_type'       => 'post',
            'capabilities'          => array(
                'create_posts' => 'do_not_allow'
            ),
            'map_meta_cap'          => true,
            'has_archive'           => false,
            'hierarchical'          => false,
            'supports'              => array('title', 'editor')
        );
        register_post_type('product_qa', $args);
    }
    add_action('init', 'register_product_qa_post_type');


    /**
     * Remove "Add New" link from admin menu
     * @author M-MALEKZADEH77
     * @since 1.0
     */
    function remove_add_new_product_qa_link()
    {
        global $submenu;
        if (isset($submenu['edit.php?post_type=product'])) {
            foreach ($submenu['edit.php?post_type=product'] as $key => $menu_item) {
                if ($menu_item[2] == 'post-new.php?post_type=product_qa') {
                    unset($submenu['edit.php?post_type=product'][$key]);
                }
            }
        }
    }
    add_action('admin_menu', 'remove_add_new_product_qa_link');

    /**
     * Add a meta box for Q&A information
     * @author M-MALEKZADEH77
     * @since 1.0
     */
    function add_product_qa_meta_boxes()
    {
        add_meta_box(
            'product_qa_details',
            'جزئیات پرسش و پاسخ',
            'render_product_qa_meta_box',
            'product_qa',
            'normal',
            'high'
        );
        add_meta_box(
            'user_replies',
            'پاسخ‌های کاربران',
            'render_user_replies_meta_box',
            'product_qa',
            'normal',
            'default'
        );
    }
    add_action('add_meta_boxes', 'add_product_qa_meta_boxes');

    /**
     * Render Q&A details metabox option
     * @author M-MALEKZADEH77
     * @since 1.0
     */
    function render_product_qa_meta_box($post)
    {
        wp_nonce_field('product_qa_meta_box', 'product_qa_meta_box_nonce');

        $user_name = get_post_meta($post->ID, '_qa_user_name', true);
        $user_email = get_post_meta($post->ID, '_qa_user_email', true);
        $product_id = get_post_meta($post->ID, '_qa_product_id', true);
        $status = get_post_meta($post->ID, '_qa_status', true);
        // $is_public = get_post_meta($post->ID, '_qa_is_public', true);

        $product_name = '';
        if ($product_id) {
            $product = wc_get_product($product_id);
            if ($product) {
                $product_name = $product->get_name();
            }
        }

    ?>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
            <div>
                <h3 style="margin-top: 0; color: #2271b1;">اطلاعات پرسش‌</h3>

                <div style="margin-bottom: 15px;">
                    <label for="qa_user_name" style="display: block; margin-bottom: 5px; font-weight: bold;">نام کاربر (پرسش‌کننده):</label>
                    <input type="text" id="qa_user_name" name="qa_user_name" value="<?php echo esc_attr($user_name); ?>" style="width: 100%; padding: 8px;" readonly>
                    <p class="description">نام کاربری که این پرسش را مطرح کرده است.</p>
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="qa_user_email" style="display: block; margin-bottom: 5px; font-weight: bold;">ایمیل کاربر (پرسش‌کننده):</label>
                    <input type="email" id="qa_user_email" name="qa_user_email" value="<?php echo esc_attr($user_email); ?>" style="width: 100%; padding: 8px;" readonly>
                    <p class="description">آدرس ایمیل کاربر.</p>
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="qa_product_id" style="display: block; margin-bottom: 5px; font-weight: bold;">شناسه محصول مرتبط:</label>
                    <input type="number" id="qa_product_id" name="qa_product_id" value="<?php echo esc_attr($product_id); ?>" style="width: 100%; padding: 8px;" placeholder="شناسه عددی محصول" readonly>
                    <?php if ($product_name) : ?>
                        <p class="description" style="margin: 5px 0 0; color: #666; font-size: 0.9em;">نام محصول: <strong><?php echo esc_html($product_name); ?></strong></p>
                    <?php else : ?>
                        <p class="description">شناسه محصولی که این پرسش برای آن ثبت شده است.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div>
                <h3 style="margin-top: 0; color: #2271b1;">تنظیمات و وضعیت پرسش</h3>

                <div style="margin-bottom: 15px;">
                    <label for="qa_status" style="display: block; margin-bottom: 5px; font-weight: bold;">وضعیت پرسش:</label>
                    <select id="qa_status" name="qa_status" style="width: 100%; padding: 8px;">
                        <option value="pending" <?php selected($status, 'pending'); ?>>در انتظار بررسی (کاربر پرسش را مطرح کرده)</option>
                        <option value="confirmed" <?php selected($status, 'confirmed'); ?>>تایید شده (شما پرسش را تایید کره اید)</option>
                        <option value="rejected" <?php selected($status, 'rejected'); ?>>رد شده (پرسش نمایش داده نخواهد شد)</option>
                    </select>
                    <p class="description">وضعیت فعلی این پرسش.</p>
                </div>

                <!-- <div>
                    <label for="qa_is_public" style="font-weight: bold;">
                        <input type="checkbox" id="qa_is_public" name="qa_is_public" value="1" <?php checked($is_public, '1'); ?>>
                        نمایش عمومی پرسش در صفحه محصول
                    </label>
                    <p class="description">با فعال کردن این گزینه، پرسش و پاسخ‌های آن در تب پرسش و پاسخ محصول نمایش داده می‌شود.</p>
                </div> -->
            </div>
        </div>
    <?php
    }
    /**
     * Render Q&A user replies metabox option
     * @author M-MALEKZADEH77
     * @since 1.0
     */
    function render_user_replies_meta_box($post)
    {
        $replies = get_post_meta($post->ID, '_user_replies', true);
        $replies = !empty($replies) ? $replies : array();

        echo '<div class="user-replies-container">';

        if (!empty($replies)) {
            echo '<p class="description">پاسخ‌هایی که کاربران دیگر برای این پرسش ثبت کرده‌اند.</p>';
            foreach ($replies as $index => $reply) {
                echo '<div class="user-reply">';
                echo '<div class="reply-header">';
                echo '<strong>' . esc_html($reply['user_name']) . '</strong>';
                echo '<span class="reply-date">' . esc_html($reply['date']) . '</span>';
                echo '</div>';
                echo '<div class="reply-content">' . wp_kses_post($reply['content']) . '</div>';
                echo '<button class="button button-small delete-user-reply" data-index="' . $index . '" style="margin-top: 5px;">حذف پاسخ</button>';
                echo '</div>';
            }
        } else {
            echo '<p>هنوز پاسخی از کاربران برای این پرسش ثبت نشده است.</p>';
        }

        echo '</div>';
    }

    /**
     * Save Q&A metabox values
     * @author M-MALEKZADEH77
     * @since 1.0
     */
    function save_product_qa_meta_data($post_id)
    {
        if (
            !isset($_POST['product_qa_meta_box_nonce']) ||
            !wp_verify_nonce($_POST['product_qa_meta_box_nonce'], 'product_qa_meta_box')
        ) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        $fields = array(
            'qa_user_name',
            'qa_user_email',
            'qa_product_id',
            'qa_status'
        );

        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
            }
        }

        // $is_public = isset($_POST['qa_is_public']) ? '1' : '0';
        // update_post_meta($post_id, '_qa_is_public', $is_public);
    }
    add_action('save_post', 'save_product_qa_meta_data');

    /**
     * Adding custom columns to the question list
     * @author M-MALEKZADEH77
     * @since 1.0
     */
    function add_product_qa_columns($columns)
    {
        $new_columns = array(
            'cb' => $columns['cb'],
            'title' => 'پرسش',
            'product' => 'محصول مرتبط',
            'user' => 'پرسش‌کننده',
            'status' => 'وضعیت نمایش',
            'date' => 'تاریخ دریافت'
        );
        return $new_columns;
    }
    add_filter('manage_product_qa_posts_columns', 'add_product_qa_columns');

    /**
     * Displaying the content of custom columns
     * @author M-MALEKZADEH77
     * @since 1.0
     */
    function display_product_qa_columns($column, $post_id)
    {
        switch ($column) {
            case 'product':
                $product_id = get_post_meta($post_id, '_qa_product_id', true);
                if ($product_id) {
                    $product = wc_get_product($product_id);
                    if ($product) {
                        echo '<a href="' . get_permalink($product_id) . '">' . $product->get_name() . '</a>';
                    } else {
                        echo '<em>محصول یافت نشد</em>';
                    }
                } else {
                    echo '<em>نامشخص</em>';
                }
                break;

            case 'user':
                $user_name = get_post_meta($post_id, '_qa_user_name', true);
                $user_email = get_post_meta($post_id, '_qa_user_email', true);
                echo esc_html($user_name) . '<br><small>' . esc_html($user_email) . '</small>';
                break;

            case 'status':
                $status = get_post_meta($post_id, '_qa_status', true);
                // $is_public = get_post_meta($post_id, '_qa_is_public', true); // Get public status

                $status_labels = array(
                    'pending' => '<span style="color: #ffb900;">در انتظار بررسی</span>',
                    'confirmed' => '<span style="color: #46b450;">تایید شده</span>',
                    'rejected' => '<span style="color: #dc3232;">رد شده</span>'
                );

                // Display the main status
                echo isset($status_labels[$status]) ? $status_labels[$status] : $status;

                // Display public status or not
                // if ($is_public == '1') {
                //     echo '<br><span style="font-size: 0.8em; color: green;">(نمایش عمومی)</span>';
                // } else {
                //     echo '<br><span style="font-size: 0.8em; color: orange;">(نمایش خصوصی)</span>';
                // }
                break;
        }
    }
    add_action('manage_product_qa_posts_custom_column', 'display_product_qa_columns', 10, 2);

    /**
     * Make product Q&A columns sortable
     * @author M-MALEKZADEH77
     * @since 1.0
     */
    function make_product_qa_columns_sortable($columns)
    {
        $columns['status'] = 'status';
        $columns['product'] = 'product';
        return $columns;
    }
    add_filter('manage_edit-product_qa_sortable_columns', 'make_product_qa_columns_sortable');

    /**
     * Add product Q&A filters to the admin list.
     *
     * Only products that have at least one Q&A are included
     * in the product filter dropdown.
     * @author M-MALEKZADEH77
     * @since 1.0
     */
    function add_product_qa_admin_filters()
    {
        global $typenow;

        if ($typenow !== 'product_qa') {
            return;
        }

        $current_status = isset($_GET['qa_status'])
            ? sanitize_key($_GET['qa_status'])
            : '';

        $current_product = isset($_GET['qa_product'])
            ? absint($_GET['qa_product'])
            : 0;

        /*
     * Get only product IDs that are referenced by at least one
     * product_qa post.
     */
        global $wpdb;

        $product_ids = $wpdb->get_col(
            $wpdb->prepare(
                "
            SELECT DISTINCT pm.meta_value
            FROM {$wpdb->postmeta} AS pm
            INNER JOIN {$wpdb->posts} AS qa
                ON qa.ID = pm.post_id
            WHERE pm.meta_key = %s
              AND qa.post_type = %s
              AND qa.post_status != %s
              AND pm.meta_value != ''
            ORDER BY pm.meta_value + 0 ASC
            ",
                '_qa_product_id',
                'product_qa',
                'trash'
            )
        );

        /*
     * Convert values to valid integer product IDs.
     */
        $product_ids = array_values(
            array_filter(
                array_map('absint', $product_ids)
            )
        );

    ?>
        <select name="qa_status">
            <option value="">همه وضعیت‌ها</option>

            <option value="pending" <?php selected($current_status, 'pending'); ?>>
                در انتظار تایید مدیر
            </option>

            <option value="confirmed" <?php selected($current_status, 'confirmed'); ?>>
                تایید شده
            </option>

            <option value="rejected" <?php selected($current_status, 'rejected'); ?>>
                رد شده
            </option>
        </select>

        <select name="qa_product">
            <option value="">همه محصولات</option>

            <?php
            /*
         * Load only products that are actually referenced by Q&A posts.
         */
            if (!empty($product_ids)) {
                $products = wc_get_products(
                    array(
                        'include' => $product_ids,
                        'limit'   => -1,
                        'orderby' => 'title',
                        'order'   => 'ASC',
                        'status'  => 'publish',
                    )
                );

                foreach ($products as $product) {
            ?>
                    <option
                        value="<?php echo esc_attr($product->get_id()); ?>"
                        <?php selected($current_product, $product->get_id()); ?>>
                        <?php echo esc_html($product->get_name()); ?>
                    </option>
            <?php
                }
            }
            ?>
        </select>

    <?php
    }

    add_action(
        'restrict_manage_posts',
        'add_product_qa_admin_filters'
    );

    /**
     * Filter product Q&A admin list by status and product.
     *
     * This filter only modifies the main admin query for the product_qa CPT.
     * @author M-MALEKZADEH77
     * @since 1.0
     */
    function filter_product_qa_posts($query)
    {
        global $pagenow;

        // Only run in the admin list screen.
        if (!is_admin() || $pagenow !== 'edit.php') {
            return;
        }

        // Only modify the main query.
        if (!$query->is_main_query()) {
            return;
        }

        // Only target the product_qa post type.
        if ($query->get('post_type') !== 'product_qa') {
            return;
        }

        $meta_query = array(
            'relation' => 'AND',
        );

        // Allow only known Q&A statuses.
        $allowed_statuses = array(
            'pending',
            'confirmed',
            'rejected',
        );

        if (!empty($_GET['qa_status'])) {
            $status = sanitize_key($_GET['qa_status']);

            if (in_array($status, $allowed_statuses, true)) {
                $meta_query[] = array(
                    'key'   => '_qa_status',
                    'value' => $status,
                );
            }
        }

        // Filter by product ID.
        if (!empty($_GET['qa_product'])) {
            $product_id = absint($_GET['qa_product']);

            if ($product_id > 0) {
                $meta_query[] = array(
                    'key'   => '_qa_product_id',
                    'value' => $product_id,
                );
            }
        }

        // Apply the meta query only when at least one filter exists.
        if (count($meta_query) > 1) {
            $query->set('meta_query', $meta_query);
        }
    }

    add_action('pre_get_posts', 'filter_product_qa_posts');

    /**
     * Remove unnecessary taxonomy
     * @author M-MALEKZADEH77
     * @since 1.0
     */
    function unregister_product_qa_taxonomy()
    {
        unregister_taxonomy('product_qa_product');
    }
    add_action('init', 'unregister_product_qa_taxonomy', 100);

    /**
     * Add a Q&A tab to the product page
     * @author M-MALEKZADEH77
     * @since 1.0
     */
    function add_product_qa_tab($tabs)
    {
        global $product;
        // Calculate the total number of approved Q&A posts
        $total_posts = get_product_qa_count($product->get_id());
        $qa_tab_status = get_option('irantheme_single_product_qa_status_checkbox');
        if ($qa_tab_status == 'on') {
            $tabs['product_qa'] = array(
                'title'    => 'پرسش ها<span class="review-count">' . $total_posts . '</span>',
                'priority' => 50,
                'callback' => 'display_product_qa_tab_content'
            );
        }
        return $tabs;
    }
    add_filter('woocommerce_product_tabs', 'add_product_qa_tab');


    /**
     * Displays the product Q&A tab content with an initial larger load and subsequent smaller loads.
     * @author M-MALEKZADEH77
     * @since 1.0
     */
    function display_product_qa_tab_content()
    {
        global $product;
        $product_id = $product->get_id();

        // Number of Q&A posts to display on the first page
        $first_page_posts = 10;

        // Calculate the total number of approved Q&A posts
        $total_posts = get_product_qa_count($product_id);

        // Retrieve the initial set of Q&A posts
        $qa_posts = get_posts(array(
            'post_type'   => 'product_qa',
            'meta_query'  => array(
                'relation' => 'AND',
                array(
                    'key'     => '_qa_product_id',
                    'value'   => $product_id,
                ),
                // array(
                //     'key'     => '_qa_is_public',
                //     'value'   => '1',
                // ),
                array(
                    'key' => '_qa_status',
                    'value' => 'rejected',
                    'compare' => '!='
                ),
                array(
                    'key' => '_qa_status',
                    'value' => 'pending',
                    'compare' => '!='
                )
            ),
            'numberposts' => $first_page_posts,
            'orderby'     => 'date',
            'order'       => 'DESC'
        ));

        echo '<h2 class="irantheme-single-product-content-tabs-panel-title">پرسش‌ها</h2>';
        echo '<div id="product_qa" class="irantheme-single-product-tab-product_qa row gx-5">';
        // Display the new question submission button
        display_qa_submission_button($product_id);
        if ($qa_posts) {
            // Pass the correct number of posts to load next
            echo '<div class="irantheme-single-product-tab-product_qa-list col-lg-9" data-product-id="' . $product_id . '" data-page="1" data-total="' . $total_posts . '">';

            foreach ($qa_posts as $qa) {
                display_qa_item($qa);
            }

            // Show the load more button if more questions exist
            if ($total_posts > $first_page_posts) {
                echo '<div class="irantheme-single-product-tab-product_qa-load-more">';
                echo '<button class="irantheme-single-product-tab-product_qa-load-more-btn"><span class="qa-count">' . ($total_posts - $first_page_posts) . '</span>پرسش دیگر<i class="fa-light fa-angle-left"></i></button>';
                echo '</div>';
            }

            echo '</div>';
        } else {
            echo '<div class="irantheme-single-product-tab-product_qa-no-qa col-lg-12">';
            echo '<i class="icon fa-light fa-messages-question"></i>';
            echo '<p class="title">هیچ پرسشی برای این محصول ثبت نشده است.</p>';
            echo '<p class="desc">اولین نفری باشید که برای این محصول پرسش ارسال می کند.</p>';
            echo '<button class="btn open-question-modal-btn ' . get_single_product_button_styles() . '" data-product-id="' . $product_id . '">ثــبت پرسش جدید</button>';
            echo '</div>';
        }

        echo '</div>';

        // Add necessary scripts and styles
        product_qa_load_more_scripts();
    }

    /**
     * Displays a single Q&A item.
     * @param WP_Post $qa The Q&A post object.
     */
    function display_qa_item($qa)
    {
        global $product;
        $product_id = $product->get_id();

        $user_name = get_post_meta($qa->ID, '_qa_user_name', true);
        $user_replies = get_post_meta($qa->ID, '_user_replies', true);

        // Item
        echo '<div class="irantheme-single-product-tab-product_qa-list-item" data-qa-id="' . $qa->ID . '">';
        // Question
        echo '<div class="user-question">';
        // Start question head >>>>
        echo '<div class="user-question-head">';
        echo '<div class="user-question-head-icon"><i class="fa-light fa-question"></i></div>';
        echo '<p class="user-question-head-title">' . esc_html(get_the_title($qa->ID)) . '</p>';
        echo '</div>';
        // End question head >>>>

        if (!empty($user_replies) || !empty($qa->post_content)) {
            // Show admin response (if any)
            if (!empty($qa->post_content)) {
                echo '<div class="user-question-reply">';
                echo '<div class="user-question-reply-admin user-question-reply-item">';
                echo '<div class="user-question-reply-item-icon"><span>پاسخ</span></div>';
                echo '<div class="user-question-reply-item-content">' . apply_filters('the_content', $qa->post_content) . '<div class="user-question-reply-item-content-author">' . esc_attr(get_option('irantheme_single_product_seller_name')) . '<span class="admin-badge">مدیر</span></div></div>';
                echo '</div>';
                echo '</div>';
            }

            // Show user responses (if any)
            if (!empty($user_replies)) {
                foreach ($user_replies as $reply) {
                    $reply_user_id = isset($reply['user_id']) ? $reply['user_id'] : 0;
                    $user_info = get_userdata($reply_user_id);
                    $user_name = $reply['user_name'];
                    if ($user_info) {
                        // Return the display name if it exists
                        if (!empty($user_info->display_name)) {
                            $user_name = $user_info->display_name;
                        }
                    }
                    $customer_badge = '';
                    if (function_exists('is_product_customer') && is_product_customer($reply_user_id, $qa->ID)) {
                        $customer_badge = '<span class="customer-badge">خریدار</span>';
                    }

                    echo '<div class="user-question-reply">';
                    echo '<div class="user-question-reply-admin user-question-reply-item">';
                    echo '<div class="user-question-reply-item-icon"><span>پاسخ</span></div>';
                    echo '<div class="user-question-reply-item-content">' . esc_html($reply['content']) . '<div class="user-question-reply-item-content-author">' . esc_html($user_name) . $customer_badge . '</div></div>';
                    echo '</div>';
                    echo '</div>';
                }
            }
        }
        $user_login_status_checkbox = get_option('irantheme_single_product_qa_submission_guest_checkbox', 'on');
        if ($user_login_status_checkbox == 'on' || is_user_logged_in()) {
            // Display the answer form button for the main question
            echo '<button class="open-reply-modal-btn button" data-qa-id="' . $qa->ID . '" data-product-id="' . $product_id . '" data-question="' . esc_html(get_the_title($qa->ID)) . '"><i class="fa-light fa-pen-line"></i>ثبــت پاسخ جدید</button>';
        }
        echo '</div>';
        echo '</div>';
    }

    /**
     * Adds scripts and styles for the "Load More" functionality.
     */
    function product_qa_load_more_scripts()
    {
    ?>
        <script type="text/javascript">
            jQuery(function($) {
                // The number of posts initially loaded on the first page.
                const first_page_posts = 10;
                // Load more questions
                $(document).on('click', '.irantheme-single-product-tab-product_qa-load-more-btn', function() {
                    let $button = $(this);
                    let $list_container = $button.closest('.irantheme-single-product-tab-product_qa-list');
                    let $button_container = $button.closest('.irantheme-single-product-tab-product_qa-load-more');

                    // Number of Q&A posts to display on subsequent pages
                    let per_page = 5;
                    // Data attributes from the list container
                    let product_id = $list_container.data('product-id');
                    let page = parseInt($list_container.data('page')) + 1;
                    let total = $list_container.data('total');

                    // Get the span that holds the question count
                    let $qa_count_span = $button.find('.qa-count');

                    // Show a loading state
                    $button.text('در حال بارگزاری...').prop('disabled', true);

                    $.ajax({
                        url: '<?php echo admin_url('admin-ajax.php'); ?>',
                        type: 'POST',
                        data: {
                            action: 'load_more_qa',
                            product_id: product_id,
                            page: page,
                            per_page: per_page
                        },
                        success: function(response) {
                            if (response.success && response.data.posts) {
                                // Append new posts before the button container
                                $.each(response.data.posts, function(index, post) {
                                    let html = '<div class="irantheme-single-product-tab-product_qa-list-item" data-qa-id="' + post.id + '">' +
                                        '<div class="user-question">' +
                                        '<div class="user-question-head">' +
                                        '<div class="user-question-head-icon"><i class="fa-light fa-question"></i></div>' +
                                        '<p class="user-question-head-title">' + post.title + '</p>' +
                                        '</div>';

                                    if (post.answer) {
                                        html += '<div class="user-question-reply">' +
                                            '<div class="user-question-reply-admin user-question-reply-item">' +
                                            '<div class="user-question-reply-item-icon"><span>پاسخ</span></div>' +
                                            '<div class="user-question-reply-item-content">' + post.answer + '<div class="user-question-reply-item-content-author">' + post.admin_name + '<span class="admin-badge">مدیر</span></div></div>' +
                                            '</div>' +
                                            '</div>';
                                    }

                                    if (post.replies && post.replies.length > 0) {
                                        $.each(post.replies, function(i, reply) {
                                            html += '<div class="user-question-reply">' +
                                                '<div class="user-question-reply-admin user-question-reply-item">' +
                                                '<div class="user-question-reply-item-icon"><span>پاسخ</span></div>' +
                                                '<div class="user-question-reply-item-content">' + reply.content + '<div class="user-question-reply-item-content-author">' + reply.user_name + reply.customer_badge + '</div></div>' +
                                                '</div>' +
                                                '</div>';
                                        });
                                    }

                                    html += '<button class="open-reply-modal-btn button" data-qa-id="' + post.id + '" data-question="' + post.title + '"><i class="fa-light fa-pen-line"></i>ثبــت پاسخ جدید</button>' +
                                        '</div>' +
                                        '</div>';

                                    $button_container.before(html);
                                });

                                // Update the page data attribute for the next request
                                $list_container.data('page', page);

                                // Check if there are any remaining posts
                                let loaded_posts = first_page_posts + ((page - 1) * per_page);
                                let remaining = total - loaded_posts;

                                // If no more posts, remove the load more button
                                if (remaining <= 0) {
                                    $button_container.remove();
                                } else {
                                    // Otherwise, update the button text and re-enable it
                                    $button.html('<span class="qa-count">' + remaining + '</span> پرسش دیگر<i class="fa-light fa-angle-left"></i>');
                                    $button.prop('disabled', false);
                                }
                            } else {
                                // Handle error
                                $button.text('Error loading').prop('disabled', false);
                                console.error('Error retrieving data:', response);
                            }
                        },
                        error: function(xhr, status, error) {
                            // Handle AJAX request error
                            $button.text('Error loading').prop('disabled', false);
                            console.error('AJAX error:', error);
                        }
                    });
                });
            });
        </script>
        <?php
    }

    /**
     * AJAX handler for loading more Q&A posts.
     */
    function load_more_qa_ajax()
    {
        // Check for required parameters
        if (!isset($_POST['product_id']) || !isset($_POST['page']) || !isset($_POST['per_page'])) {
            wp_send_json_error(array('message' => 'Required parameters not sent.'));
            return;
        }

        $product_id = intval($_POST['product_id']);
        $page = intval($_POST['page']);
        $per_page = intval($_POST['per_page']);
        $first_page_posts = 10; // This must match the PHP variable in the main function

        $offset = ($first_page_posts) + ($page - 2) * $per_page;

        $qa_posts = get_posts(array(
            'post_type'   => 'product_qa',
            'meta_query'  => array(
                'relation' => 'AND',
                array(
                    'key'     => '_qa_product_id',
                    'value'   => $product_id,
                ),
                // array(
                //     'key'     => '_qa_is_public',
                //     'value'   => '1',
                // ),
                array(
                    'key' => '_qa_status',
                    'value' => 'rejected',
                    'compare' => '!='
                ),
                array(
                    'key' => '_qa_status',
                    'value' => 'pending',
                    'compare' => '!='
                )
            ),
            'numberposts' => $per_page,
            'offset'      => $offset,
            'orderby'     => 'date',
            'order'       => 'DESC'
        ));

        $formatted_posts = array();
        $admin_name = esc_attr(get_option('irantheme_single_product_seller_name', 'Admin'));

        foreach ($qa_posts as $post) {
            $user_name = get_post_meta($post->ID, '_qa_user_name', true);
            $user_replies = get_post_meta($post->ID, '_user_replies', true);

            $formatted_post = array(
                'id' => $post->ID,
                'title' => get_the_title($post->ID),
                'user_name' => $user_name,
                'answer' => apply_filters('the_content', $post->post_content),
                'admin_name' => $admin_name,
                'replies' => array()
            );

            if (!empty($user_replies) && is_array($user_replies)) {
                foreach ($user_replies as $reply) {
                    $reply_user_id = isset($reply['user_id']) ? $reply['user_id'] : 0;
                    $user_info = get_userdata($reply_user_id);
                    $user_name = $reply['user_name'];

                    if ($user_info && !empty($user_info->display_name)) {
                        $user_name = $user_info->display_name;
                    }

                    $customer_badge = '';
                    if (function_exists('is_product_customer') && is_product_customer($reply_user_id, $post->ID)) {
                        $customer_badge = '<span class="customer-badge">خریدار</span>';
                    }

                    $formatted_post['replies'][] = array(
                        'user_name' => $user_name,
                        'content' => $reply['content'],
                        'customer_badge' => $customer_badge
                    );
                }
            }

            $formatted_posts[] = $formatted_post;
        }

        wp_send_json_success(array('posts' => $formatted_posts));
    }
    add_action('wp_ajax_load_more_qa', 'load_more_qa_ajax');
    add_action('wp_ajax_nopriv_load_more_qa', 'load_more_qa_ajax');

    /**
     * Checks if a user has purchased a specific product.
     *
     * @param int $user_id The user's ID.
     * @param int $post_id The question's ID.
     * @return bool True if the user is a purchaser, otherwise false.
     */
    function is_product_customer($user_id, $post_id)
    {
        // Get product ID from query metafield
        $product_id = get_post_meta($post_id, '_qa_product_id', true);

        if (!$user_id || !$product_id) {
            return false;
        }

        // Get all completed orders from the user
        $customer_orders = wc_get_orders(array(
            'customer' => $user_id,
            'status'   => 'completed',
            'return'   => 'ids',
        ));

        if (empty($customer_orders)) {
            return false;
        }

        // Check the products in each order
        foreach ($customer_orders as $order_id) {
            $order = wc_get_order($order_id);
            if ($order) {
                foreach ($order->get_items() as $item_id => $item) {
                    if ($item->get_product_id() == $product_id) {
                        return true; // Product found in this order
                    }
                }
            }
        }

        return false;
    }

    function display_qa_submission_button($product_id)
    {
        // Calculate the total number of approved Q&A posts
        $total_posts = get_product_qa_count($product_id);
        if ($total_posts > 0):
        ?>
            <div class="irantheme-single-product-tab-product_qa-question-submit col-lg-3">
                <button name="submit_qa" class="open-question-modal-btn <?php echo get_single_product_button_styles(); ?>" data-product-id="<?php echo $product_id; ?>">ثبــت پرسش<i class="fa-light fa-money-check-pen"></i></button>
                <h4 class="question-submit-desc">شما هم درباره این کالا پرسش ثبت کنید.</h4>
            </div>
        <?php
        endif;
    }

    /**
     * New question submission form (only for logged in users)l
     * @author M-MALEKZADEH77
     * @since 1.0
     */
    function display_qa_submission_form()
    {
        $user_login_status_checkbox = get_option('irantheme_single_product_qa_submission_guest_checkbox', 'on');
        if ($user_login_status_checkbox == 'off' && !is_user_logged_in()) {
        ?>
            <div class="product-qa-question-modal irantheme-popup-modal" id="irantheme_qa_question_modal" style="display: none;">
                <div class="product-qa-question-modal-wrapper irantheme-popup-modal-wrapper">
                    <div class="topbar">
                        <h4 class="heading"><?php _e('ثبت پرسش جدید'); ?></h4>
                        <span class="close"><i class="fa-light fa-close"></i></span>
                    </div>
                    <div class="irantheme-divider"></div>
                    <div class="qa-content">
                        <div class="qa-content-attention">
                            <p class="qa-content-attention-label">برای ثبت پرسش و پاسخ باید وارد حساب کاربری خود شوید.</p>
                            <a href="<?php echo wc_get_page_permalink('myaccount'); ?>" name="login_user_btn" class="qa-content-attention-login-btn <?php echo get_single_product_button_styles(); ?>">ورود به حساب کاربری</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        } else {
            // If the user is logged in, we pre-fill their information.
            $current_user = wp_get_current_user();
        ?>
            <div class="product-qa-question-modal irantheme-popup-modal" id="irantheme_qa_question_modal" style="display: none;">
                <div class="product-qa-question-modal-wrapper irantheme-popup-modal-wrapper">
                    <div class="topbar">
                        <h4 class="heading"><?php _e('ثبت پرسش جدید'); ?></h4>
                        <span class="close"><i class="fa-light fa-close"></i></span>
                    </div>
                    <div class="irantheme-divider"></div>
                    <div class="qa-content">
                        <div class="qa-content-question-wrapper">
                            <p class="qa-content-question-form-label">پرسش خود را در مورد محصول مطرح کنید:</p>
                            <form method="post" class="qa-content-question-form">
                                <?php wp_nonce_field('submit_qa', 'qa_nonce'); ?>
                                <input type="hidden" name="product_id" id="question_product_id">

                                <?php if (!is_user_logged_in()) : ?>
                                    <input type="hidden" name="qa_name" value="کاربر مهمان">
                                    <input type="hidden" name="qa_email" value="guest-user@example.com">
                                <?php else:
                                    $current_user = wp_get_current_user();
                                ?>
                                    <input type="hidden" name="qa_name" value="<?php echo esc_attr($current_user->display_name); ?>">
                                    <input type="hidden" name="qa_email" value="<?php echo esc_attr($current_user->user_email); ?>">
                                <?php endif; ?>

                                <div class="qa-content-question-form-textarea-holder">
                                    <textarea name="qa_question" rows="3" maxlength="500" placeholder="پرسش خود را بنویسید..." required></textarea>
                                </div>

                                <div class="form-row">
                                    <button type="submit" name="submit_qa" class="qa-content-question-form-submit <?php echo get_single_product_button_styles(); ?>">ثبت پرسش</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        }
    }
    // This line calls the function in the footer of the page
    add_action('woocommerce_after_single_product_summary', 'display_qa_submission_form', 5);

    /**
     * Process product Q&A submission.
     *
     * This function only runs its validation and save logic when the
     * question form has actually been submitted.
     * @author M-MALEKZADEH77
     * @since 1.0
     */
    function process_qa_submission()
    {
        // Exit immediately when this request is not a Q&A submission.
        if (!isset($_POST['submit_qa'])) {
            return;
        }

        // Make sure the nonce field exists before verifying it.
        if (
            !isset($_POST['qa_nonce']) ||
            !wp_verify_nonce($_POST['qa_nonce'], 'submit_qa')
        ) {
            wc_add_notice('درخواست نامعتبر است. لطفاً دوباره تلاش کنید.', 'error');
            return;
        }

        // Enforce the guest submission policy on the server side.
        $allow_guests = get_option(
            'irantheme_single_product_qa_submission_guest_checkbox',
            'on'
        );

        if ($allow_guests !== 'on' && !is_user_logged_in()) {
            wc_add_notice(
                'برای ثبت پرسش باید وارد حساب کاربری شوید.',
                'error'
            );
            return;
        }

        // Sanitize and validate the submitted question.
        $question = isset($_POST['qa_question'])
            ? sanitize_textarea_field($_POST['qa_question'])
            : '';

        if ($question === '') {
            wc_add_notice('لطفاً متن پرسش را وارد کنید.', 'error');
            return;
        }

        // Make sure the question does not exceed the frontend limit.
        if (mb_strlen($question) > 500) {
            wc_add_notice(
                'متن پرسش نمی‌تواند بیشتر از ۵۰۰ کاراکتر باشد.',
                'error'
            );
            return;
        }

        // Validate the related product ID.
        $product_id = isset($_POST['product_id'])
            ? absint($_POST['product_id'])
            : 0;

        $product = $product_id ? wc_get_product($product_id) : false;

        if (!$product) {
            wc_add_notice('محصول انتخاب‌شده معتبر نیست.', 'error');
            return;
        }

        /*
     * Determine user information on the server.
     * Never trust hidden form fields for authenticated users.
     */
        if (is_user_logged_in()) {
            $current_user = wp_get_current_user();

            $user_name  = $current_user->display_name;
            $user_email = $current_user->user_email;
        } else {
            $user_name  = 'کاربر مهمان';
            $user_email = 'guest-user@example.com';
        }

        // Create the Q&A post.
        $question_data = array(
            'post_title'   => $question,
            'post_content' => '',
            'post_type'    => 'product_qa',
            'post_status'  => 'pending',
        );

        $qa_id = wp_insert_post($question_data, true);

        if (is_wp_error($qa_id)) {
            wc_add_notice(
                'متاسفانه مشکلی در ثبت پرسش به وجود آمد.',
                'error'
            );
            return;
        }

        // Store the question metadata.
        update_post_meta(
            $qa_id,
            '_qa_user_name',
            sanitize_text_field($user_name)
        );

        update_post_meta(
            $qa_id,
            '_qa_user_email',
            sanitize_email($user_email)
        );

        update_post_meta(
            $qa_id,
            '_qa_product_id',
            $product_id
        );

        update_post_meta(
            $qa_id,
            '_qa_status',
            'pending'
        );

        wc_add_notice(
            'پرسش شما با موفقیت ثبت شد و پس از تایید نمایش داده خواهد شد.',
            'success'
        );

        /*
     * Use Post/Redirect/Get to prevent duplicate submissions
     * when the user refreshes the page.
     */
        $redirect_url = wp_get_referer();

        if (!$redirect_url) {
            $redirect_url = get_permalink($product_id);
        }

        wp_safe_redirect($redirect_url);
        exit;
    }
    add_action('template_redirect', 'process_qa_submission');

    /**
     * Display the response form in the Q&A tabl
     * @author M-MALEKZADEH77
     * @since 1.0
     */
    function display_user_reply_form()
    {
        ?>
        <div class="product-qa-reply-modal irantheme-popup-modal" id="irantheme_qa_reply_modal" style="display: none;">
            <div class="product-qa-reply-modal-wrapper irantheme-popup-modal-wrapper">
                <div class="topbar">
                    <h4 class="heading"><?php _e('به این پرسش پاسخ دهید'); ?></h4>
                    <span class="close"><i class="fa-light fa-close"></i></span>
                </div>
                <div class="irantheme-divider"></div>
                <div class="qa-content">
                    <div class="qa-content-question-holder"></div>
                    <div class="qa-content-reply">
                        <p class="qa-content-reply-form-label">پاسخ:</p>
                        <form method="post" class="qa-content-reply-form">
                            <?php wp_nonce_field('submit_user_reply', 'user_reply_nonce'); ?>
                            <input type="hidden" name="qa_id" id="reply-qa-id">
                            <input type="hidden" name="qa_product_id" id="reply_qa_product_id">
                            <div class="qa-content-reply-form-textarea-holder">
                                <textarea name="user_reply_content" rows="3" maxlength="500" placeholder="پاسخ خود را بنویسید..." required></textarea>
                            </div>

                            <div class="form-row">
                                <button type="submit" name="submit_user_reply" class="qa-content-reply-form-submit <?php echo get_single_product_button_styles(); ?>">ثبت پاسخ</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    // This line calls the function in the footer of the page
    add_action('woocommerce_after_single_product_summary', 'display_user_reply_form', 5);

    /**
     * Process a user reply submission.
     *
     * The server validates the request, target Q&A, guest policy,
     * current user identity and reply content before saving.
     * @author M-MALEKZADEH77
     * @since 1.0
     */
    function process_user_reply()
    {
        // Do nothing unless the reply form was actually submitted.
        if (!isset($_POST['submit_user_reply'])) {
            return;
        }

        // Verify the form nonce.
        if (
            !isset($_POST['user_reply_nonce']) ||
            !wp_verify_nonce($_POST['user_reply_nonce'], 'submit_user_reply')
        ) {
            wc_add_notice(
                'درخواست نامعتبر است. لطفاً دوباره تلاش کنید.',
                'error'
            );
            return;
        }

        // Enforce the guest submission policy.
        $allow_guests = get_option(
            'irantheme_single_product_qa_submission_guest_checkbox',
            'on'
        );

        if ($allow_guests !== 'on' && !is_user_logged_in()) {
            wc_add_notice(
                'برای ثبت پاسخ ابتدا باید وارد حساب کاربری شوید.',
                'error'
            );
            return;
        }

        // Validate the target Q&A ID.
        $qa_id = isset($_POST['qa_id'])
            ? absint($_POST['qa_id'])
            : 0;

        if (!$qa_id) {
            wc_add_notice('پرسش موردنظر معتبر نیست.', 'error');
            return;
        }

        // Load the target Q&A post.
        $qa = get_post($qa_id);

        // Make sure the target is actually a product Q&A.
        if (!$qa || $qa->post_type !== 'product_qa') {
            wc_add_notice('پرسش موردنظر معتبر نیست.', 'error');
            return;
        }

        /*
     * Only allow replies to questions that are already visible.
     * Pending and rejected questions must not accept public replies.
     */
        $qa_status = get_post_meta($qa_id, '_qa_status', true);

        if ($qa_status !== 'confirmed') {
            wc_add_notice(
                'امکان ثبت پاسخ برای این پرسش وجود ندارد.',
                'error'
            );
            return;
        }

        // Sanitize and validate reply content.
        $content = isset($_POST['user_reply_content'])
            ? sanitize_textarea_field($_POST['user_reply_content'])
            : '';

        if ($content === '') {
            wc_add_notice(
                'لطفاً پاسخ خود را وارد کنید.',
                'error'
            );
            return;
        }

        // Match the frontend character limit.
        if (mb_strlen($content) > 500) {
            wc_add_notice(
                'متن پاسخ نمی‌تواند بیشتر از ۵۰۰ کاراکتر باشد.',
                'error'
            );
            return;
        }

        /*
     * Determine the responder identity server-side.
     * Do not trust user_id or user_name values from POST.
     */
        if (is_user_logged_in()) {
            $current_user = wp_get_current_user();

            $user_id   = (int) $current_user->ID;
            $user_name = $current_user->display_name;
        } else {
            $user_id   = 'guest';
            $user_name = 'کاربر مهمان';
        }

        // Load the current reply list.
        $replies = get_post_meta($qa_id, '_user_replies', true);

        if (!is_array($replies)) {
            $replies = array();
        }

        // Create the new reply.
        $new_reply = array(
            'reply_id' => wp_generate_uuid4(),
            'user_id'  => $user_id,
            'user_name' => sanitize_text_field($user_name),
            'content'  => $content,
            'date'     => current_time('j F Y ساعت H:i'),
            'timestamp' => current_time('timestamp'),
        );

        // Add the newest reply to the beginning of the list.
        array_unshift($replies, $new_reply);

        update_post_meta(
            $qa_id,
            '_user_replies',
            $replies
        );

        // Prepare the admin notification email.
        $admin_email = get_option('admin_email');
        $qa_title   = get_the_title($qa_id);
        $subject    = 'پاسخ جدید به پرسش: ' . $qa_title;

        $message  = "یک پاسخ جدید به پرسش زیر ثبت شده است:\n\n";
        $message .= "پرسش: " . $qa_title . "\n";
        $message .= "پاسخ‌دهنده: " . $user_name . "\n";
        $message .= "پاسخ: " . $content . "\n\n";
        $message .= 'برای مشاهده: ' . admin_url(
            'post.php?post=' . $qa_id . '&action=edit'
        );

        wp_mail(
            $admin_email,
            $subject,
            $message
        );

        wc_add_notice(
            'پاسخ شما با موفقیت ثبت شد.',
            'success'
        );

        // Validate the related product ID.
        $product_id = isset($_POST['qa_product_id'])
            ? absint($_POST['qa_product_id'])
            : 0;

        /*
     * Use Post/Redirect/Get to prevent duplicate submissions
     * when the user refreshes the page.
     */
        $redirect_url = wp_get_referer();

        if (!$redirect_url) {
            $redirect_url = get_permalink($product_id);
        }

        wp_safe_redirect($redirect_url);
        exit;
    }
    add_action('template_redirect', 'process_user_reply');

    /**
     * Adding the ability to delete user responses in the admin panelnel
     * @author M-MALEKZADEH77
     * @since 1.0
     */
    function add_user_reply_actions()
    {
        add_action('admin_footer', 'user_reply_actions_script');
    }
    add_action('admin_init', 'add_user_reply_actions');

    function user_reply_actions_script()
    {
        global $post_type;

        if ($post_type == 'product_qa') {
        ?>
            <script>
                jQuery(document).ready(function($) {
                    $(document).on('click', '.delete-user-reply', function(e) {
                        e.preventDefault();

                        if (!confirm('آیا از حذف این پاسخ اطمینان دارید؟')) {
                            return;
                        }

                        var button = $(this);
                        var replyIndex = button.data('index');
                        var postId = $('#post_ID').val();

                        $.ajax({
                            type: 'POST',
                            url: ajaxurl,
                            data: {
                                action: 'delete_user_reply',
                                post_id: postId,
                                reply_index: replyIndex,
                                nonce: '<?php echo wp_create_nonce('delete_user_reply'); ?>'
                            },
                            success: function(response) {
                                if (response.success) {
                                    button.closest('.user-reply').remove();
                                    alert('پاسخ با موفقیت حذف شد.');
                                } else {
                                    alert('خطا در حذف پاسخ.');
                                }
                            }
                        });
                    });
                });
            </script>
        <?php
        }
    }

    /**
     * Delete a user reply through AJAX
     * The request is protected by both a nonce and a capability check.
     * @author M-MALEKZADEH77
     * @since 1.0
     */
    function ajax_delete_user_reply()
    {
        // Verify that the nonce field exists and is valid.
        if (
            !isset($_POST['nonce']) ||
            !wp_verify_nonce($_POST['nonce'], 'delete_user_reply')
        ) {
            wp_send_json_error(
                array('message' => 'Invalid security token.'),
                403
            );
        }

        // Only authenticated users should be able to delete replies.
        if (!is_user_logged_in()) {
            wp_send_json_error(
                array('message' => 'Unauthorized.'),
                403
            );
        }

        // Validate the target post ID.
        $post_id = isset($_POST['post_id'])
            ? absint($_POST['post_id'])
            : 0;

        if (!$post_id || get_post_type($post_id) !== 'product_qa') {
            wp_send_json_error(
                array('message' => 'Invalid Q&A post.'),
                400
            );
        }

        // Check whether the current user can edit this Q&A post.
        if (!current_user_can('edit_post', $post_id)) {
            wp_send_json_error(
                array('message' => 'You do not have permission to delete replies.'),
                403
            );
        }

        // Validate the reply index.
        $reply_index = isset($_POST['reply_index'])
            ? absint($_POST['reply_index'])
            : -1;

        $replies = get_post_meta(
            $post_id,
            '_user_replies',
            true
        );

        if (!is_array($replies)) {
            wp_send_json_error(
                array('message' => 'Reply list is invalid.'),
                400
            );
        }

        if (!isset($replies[$reply_index])) {
            wp_send_json_error(
                array('message' => 'Reply not found.'),
                404
            );
        }

        // Remove the requested reply.
        unset($replies[$reply_index]);

        // Re-index the array after deletion.
        $replies = array_values($replies);

        update_post_meta(
            $post_id,
            '_user_replies',
            $replies
        );

        wp_send_json_success(
            array('message' => 'Reply deleted successfully.')
        );
    }
    add_action('wp_ajax_delete_user_reply', 'ajax_delete_user_reply');

    /**
     * Add quick edit fields
     * @author M-MALEKZADEH77
     * @since 1.0
     */
    function add_qa_quick_edit_fields($column_name, $post_type)
    {
        if ($post_type == 'product_qa' && $column_name == 'status') {
            static $printNonce = true;
            if ($printNonce) {
                $printNonce = false;
                wp_nonce_field('qa_quick_edit_nonce', 'qa_quick_edit_nonce');
            }
        ?>
            <fieldset class="inline-edit-col-right">
                <div class="inline-edit-col">
                    <div class="inline-edit-group wp-clearfix">
                        <label class="alignleft">
                            <span class="title">وضعیت</span>
                            <select name="_qa_status">
                                <option value="pending">در انتظار بررسی</option>
                                <option value="confirmed">تایید شده</option>
                                <option value="rejected">رد شده</option>
                            </select>
                        </label>
                    </div>
                    <!-- <div class="inline-edit-group wp-clearfix">
                        <label class="alignleft">
                            <span class="title">نمایش عمومی</span>
                            <input type="checkbox" name="_qa_is_public" value="1">
                            <span class="checkbox-title">فعال</span>
                        </label>
                    </div> -->
                </div>
            </fieldset>
        <?php
        }
    }
    add_action('quick_edit_custom_box', 'add_qa_quick_edit_fields', 10, 2);

    /**
     * Save quick edit data
     * @author M-MALEKZADEH77
     * @since 1.0
     */
    function save_qa_quick_edit_data($post_id)
    {
        // Check the nonce
        if (
            !isset($_POST['qa_quick_edit_nonce']) ||
            !wp_verify_nonce($_POST['qa_quick_edit_nonce'], 'qa_quick_edit_nonce')
        ) {
            return;
        }

        // Access permission check
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Save status
        if (isset($_POST['_qa_status'])) {
            update_post_meta($post_id, '_qa_status', sanitize_text_field($_POST['_qa_status']));
        }

        // Save public view
        // $is_public = isset($_POST['_qa_is_public']) ? '1' : '0';
        // update_post_meta($post_id, '_qa_is_public', $is_public);
    }
    add_action('save_post', 'save_qa_quick_edit_data');

    /**
     * Add data needed for quick editing to the status column
     * @author M-MALEKZADEH77
     * @since 1.0
     */
    function add_qa_status_data($column, $post_id)
    {
        if ($column == 'status') {
            $status = get_post_meta($post_id, '_qa_status', true);
            // $is_public = get_post_meta($post_id, '_qa_is_public', true);
            // echo '<div class="qa-status-data" data-status="' . esc_attr($status) . '" data-public="' . esc_attr($is_public) . '" style="display:none;"></div>';
            echo '<div class="qa-status-data" data-status="' . esc_attr($status) . '" style="display:none;"></div>';
        }
    }
    add_action('manage_product_qa_posts_custom_column', 'add_qa_status_data', 20, 2);

    /**
     * Add script to auto-fill quick edit fields
     * @author M-MALEKZADEH77
     * @since 1.0
     */    function enqueue_qa_quick_edit_script()
    {
        global $post_type;

        if ($post_type == 'product_qa') {
            wp_enqueue_script('qa-quick-edit', IRANTHEME_URI . '/js/qa-quick-edit.js', array('jquery'), '1.0', true);
        }
    }
    add_action('admin_print_scripts-edit.php', 'enqueue_qa_quick_edit_script');

    /**
     * Create JS file for quick editing (if file does not exist)
     * @author M-MALEKZADEH77
     * @since 1.0
     */
    function generate_qa_quick_edit_js()
    {
        if (is_admin() && get_current_screen()->id == 'edit-product_qa') {
        ?>
            <script type="text/javascript">
                jQuery(function($) {
                    // Save the main quick edit function
                    var $wp_inline_edit = inlineEditPost.edit;

                    // Rewrite the quick edit function
                    inlineEditPost.edit = function(id) {
                        // Call the main function
                        $wp_inline_edit.apply(this, arguments);

                        var post_id = 0;
                        if (typeof(id) == 'object') {
                            post_id = parseInt(this.getId(id));
                        }

                        if (post_id > 0) {
                            var $edit_row = $('#edit-' + post_id);
                            var $post_row = $('#post-' + post_id);

                            // A little delay to ensure the form is fully loaded
                            setTimeout(function() {
                                // Fill in the status field
                                var status = $post_row.find('.qa-status-data').data('status');
                                if (status) {
                                    $edit_row.find('select[name="_qa_status"]').val(status);
                                }

                                // Fill in the public display field
                                // var is_public = $post_row.find('.qa-status-data').data('public');
                                // $edit_row.find('input[name="_qa_is_public"]').prop('checked', is_public == '1');
                            }, 100);
                        }
                    };
                });
            </script>
        <?php
        }
    }
    add_action('admin_footer', 'generate_qa_quick_edit_js');

    // Save post status as published forever
    add_filter('wp_insert_post_data', function ($data, $postarr) {

        if (
            $data['post_type'] === 'product_qa' &&
            $data['post_status'] !== 'trash'
        ) {
            $data['post_status'] = 'publish';
        }

        return $data;
    }, 99, 2);

    // Hide post status select from edit page
    add_action('admin_head', function () {

        $screen = get_current_screen();

        if ($screen && $screen->post_type === 'product_qa') {
        ?>
            <style>
                /* Status */
                .misc-pub-post-status {
                    display: none !important;
                }

                /* Visibility */
                .misc-pub-visibility {
                    display: none !important;
                }

                /* Publish date */
                .misc-pub-curtime {
                    display: none !important;
                }

                /* Edit buttons next to each */
                #visibility .edit-visibility,
                #timestamp .edit-timestamp,
                #post-status-display+a {
                    display: none !important;
                }
            </style>
        <?php
        }
    });
    // Hide post status select from quick edit
    add_action('admin_head-edit.php', function () {

        $screen = get_current_screen();

        if ($screen && $screen->post_type === 'product_qa') {
        ?>
            <style>
                .inline-edit-status {
                    display: none !important;
                }

                .inline-edit-private {
                    display: none !important;
                }
            </style>
    <?php
        }
    });
}

/**
 * Calculate the total number of approved Q&A post
 * @author M-MALEKZADEH77
 * @since 1.0
 */
function get_product_qa_count($product_id)
{
    $total_posts_query = new WP_Query(array(
        'post_type'      => 'product_qa',
        'fields'         => 'ids',
        'meta_query'     => array(
            'relation' => 'AND',
            array(
                'key'     => '_qa_product_id',
                'value'   => $product_id,
            ),
            // array(
            //     'key'     => '_qa_is_public',
            //     'value'   => '1',
            // ),
            array(
                'key' => '_qa_status',
                'value' => 'rejected',
                'compare' => '!='
            ),
            array(
                'key' => '_qa_status',
                'value' => 'pending',
                'compare' => '!='
            )
        ),
        'posts_per_page' => -1
    ));
    $total_posts = $total_posts_query->found_posts;
    return $total_posts;
}

/**
 * Generate dingle product page button style from add_to_card_btn_color_customization options
 * @author M-MALEKZADEH77
 * @since 1.0
 */
function get_single_product_button_styles()
{
    $btn_color_customization_checkbox = get_option('irantheme_single_product_add_to_card_btn_color_customization_checkbox');
    $bg_color = get_option('irantheme_single_product_add_to_card_btn_bg_color');
    $for_color = get_option('irantheme_single_product_add_to_card_btn_color');
    $btn_bg_color_class = '';
    $btn_color_class = '';
    $btn_hover_color_class = '';
    $btn_border_color_class = '';
    if ($btn_color_customization_checkbox == 'on') {
        if ($bg_color != 'off') {
            $btn_bg_color_class = esc_attr(Irantheme_Color_Static::get_class_color_mode('bg', null, Irantheme_Color_Static::get_hex_color($bg_color)));
            $btn_hover_color_class = esc_attr(Irantheme_Color_Static::get_class_color_mode('color-hover', null, Irantheme_Color_Static::get_hex_color($bg_color)));
            $btn_border_color_class = esc_attr(Irantheme_Color_Static::get_class_color_mode('border', null, Irantheme_Color_Static::get_hex_color($bg_color)));
        }
        if ($for_color == 'off') {
            $btn_color_class = Irantheme_Color_Static::get_optimal_text_color(Irantheme_Color_Static::get_hex_color($bg_color));
        } else {
            $btn_color_class = 'text-color-' . $for_color;
        }
    };
    $result_class = $btn_color_class . ' ' . $btn_bg_color_class . ' ' . $btn_hover_color_class . ' ' . $btn_border_color_class;
    return $result_class;
}
    // =========================================================================
    // ============= End Add product quetions and answeres Section =============
    // =========================================================================
/**
 * Create a new "File Specifications" tab in the product information section
 * @author M-MALEKZADEH77
 * @since 1.0
 */
function irantheme_add_download_tab($product_data_tabs)
{
    $product_data_tabs['irantheme_download_tab'] = array(
        'label'    => __('مشخصات فایل', IRANTHEME_TEXTDOMAIN),
        'target'   => 'irantheme_download_product_data',
        'class'    => array('show_if_downloadable'), // Only for downloadable product
        'priority' => 70,
    );
    return $product_data_tabs;
}
add_filter('woocommerce_product_data_tabs', 'irantheme_add_download_tab');

/**
 * Defining content inside the new tab (custom fields)
 * @author M-MALEKZADEH77
 * @since 1.0
 */
function irantheme_download_product_data_fields()
{
    ?>
    <div id='irantheme_download_product_data' class='panel woocommerce_options_panel'>
        <div class="options_group">

            <?php
            woocommerce_wp_text_input(
                array(
                    // The identifier must start with an underscore.
                    'id'          => '_irantheme_file_version_number',
                    'label'       => __('نسخه فایل', IRANTHEME_TEXTDOMAIN),
                    'placeholder' => '1.0.0',
                    'desc_tip'    => 'true',
                    'description' => __('در صورت نیاز به نمایش نسخه فایل در صفحه محصول، محتوای این فیلد را کامل نمایید.', IRANTHEME_TEXTDOMAIN),
                    'data_type'   => 'text'
                )
            );
            woocommerce_wp_text_input(
                array(
                    'id'          => '_irantheme_file_release_date',
                    'label'       => __('تاریخ انتشار', IRANTHEME_TEXTDOMAIN),
                    'placeholder' => 'YYYY-MM-DD',
                    'desc_tip'    => 'true',
                    'description' => __('تاریخ انتشار فایل', IRANTHEME_TEXTDOMAIN),
                    'class'       => 'date-picker',
                )
            );

            woocommerce_wp_text_input(
                array(
                    'id'          => '_irantheme_file_update_date',
                    'label'       => __('تاریخ آخرین بروزرسانی', IRANTHEME_TEXTDOMAIN),
                    'placeholder' => 'YYYY-MM-DD',
                    'desc_tip'    => 'true',
                    'description' => __('تاریخ آخرین بروزرسانی فایل', IRANTHEME_TEXTDOMAIN),
                    'class'       => 'date-picker',
                )
            );
            ?>
        </div>
    </div>
    <?php
}
add_action('woocommerce_product_data_panels', 'irantheme_download_product_data_fields');

/**
 * Init date picker options
 * @author M-MALEKZADEH77
 * @since 1.0
 */
function irantheme_activate_download_datepicker()
{
    global $post;
    // Only load on product edit page
    if ($post && 'product' === $post->post_type) {
    ?>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                // Make sure the datepicker library is loaded.
                if ($.datepicker) {
                    // Enabling datepicker on our field
                    $('#_irantheme_file_release_date').datepicker({
                        dateFormat: 'yy-mm-dd',
                        numberOfMonths: 1,
                        showButtonPanel: true
                    });

                    $('#_irantheme_file_update_date').datepicker({
                        dateFormat: 'yy-mm-dd',
                        numberOfMonths: 1,
                        showButtonPanel: true
                    });
                }
            });
        </script>
    <?php
    }
}
add_action('admin_footer', 'irantheme_activate_download_datepicker');

/**
 * Save custom field information
 * @author M-MALEKZADEH77
 * @since 1.0
 */
function irantheme_save_custom_download_options($post_id)
{
    $custom_limit = isset($_POST['_irantheme_file_version_number']) ? sanitize_text_field($_POST['_irantheme_file_version_number']) : '';
    update_post_meta($post_id, '_irantheme_file_version_number', $custom_limit);

    $custom_limit = isset($_POST['_irantheme_file_update_date']) ? sanitize_text_field($_POST['_irantheme_file_update_date']) : '';
    update_post_meta($post_id, '_irantheme_file_update_date', $custom_limit);

    $custom_limit = isset($_POST['_irantheme_file_release_date']) ? sanitize_text_field($_POST['_irantheme_file_release_date']) : '';
    update_post_meta($post_id, '_irantheme_file_release_date', $custom_limit);
}
add_action('woocommerce_process_product_meta', 'irantheme_save_custom_download_options');

/**
 * Add file details option
 * @author M-MALEKZADEH77
 * @since 1.0
 */
function irantheme_add_fields_to_variations($loop, $variation_data, $variation)
{

    // 1. File Version Field (Text Input)
    woocommerce_wp_text_input(
        array(
            'id'            => '_file_version_number[' . $loop . ']',
            'name'          => '_file_version_number[' . $loop . ']',
            'value'         => get_post_meta($variation->ID, '_file_version_number', true),
            'label'         => __('نسخه فایل', IRANTHEME_TEXTDOMAIN),
            'placeholder'   => 'مثلاً 1.0.0',
            'wrapper_class' => 'form-row form-row-full',
        )
    );

    // 2. File release date field (Date Picker))
    woocommerce_wp_text_input(
        array(
            'id'            => '_file_release_date[' . $loop . ']',
            'name'          => '_file_release_date[' . $loop . ']',
            'value'         => get_post_meta($variation->ID, '_file_release_date', true),
            'label'         => __('تاریخ انتشار فایل', IRANTHEME_TEXTDOMAIN),
            'placeholder'   => 'YYYY-MM-DD',
            'class'         => 'date-picker', // Key class for activating the calendar
            'wrapper_class' => 'form-row form-row-full',
        )
    );

    // 3. File Update Date Field (Date Picker)
    woocommerce_wp_text_input(
        array(
            'id'            => '_file_update_date[' . $loop . ']',
            'name'          => '_file_update_date[' . $loop . ']',
            'value'         => get_post_meta($variation->ID, '_file_update_date', true),
            'label'         => __('تاریخ به‌روزرسانی فایل', IRANTHEME_TEXTDOMAIN),
            'placeholder'   => 'YYYY-MM-DD',
            'class'         => 'date-picker', // Key class for activating the calendar
            'wrapper_class' => 'form-row form-row-full',
        )
    );
}
// Hook: Add fields to the download settings section of each variation
add_action('woocommerce_variation_options_download', 'irantheme_add_fields_to_variations', 10, 3);

/**
 * Save file details option value in variation product
 * @author M-MALEKZADEH77
 * @since 1.0
 */
function irantheme_save_variation_fields($variation_id, $i)
{

    // 1. Save a copy of the file
    if (isset($_POST['_file_version_number'][$i])) {
        $version = sanitize_text_field($_POST['_file_version_number'][$i]);
        update_post_meta($variation_id, '_file_version_number', $version);
    }

    // 2. Save the publication date
    if (isset($_POST['_file_release_date'][$i])) {
        $release_date = sanitize_text_field($_POST['_file_release_date'][$i]);
        update_post_meta($variation_id, '_file_release_date', $release_date);
    }

    // 3. Save the update date
    if (isset($_POST['_file_update_date'][$i])) {
        $update_date = sanitize_text_field($_POST['_file_update_date'][$i]);
        update_post_meta($variation_id, '_file_update_date', $update_date);
    }
}
// Hook: Storing fields defined in variations
add_action('woocommerce_save_product_variation', 'irantheme_save_variation_fields', 10, 2);

/**
 * Add custom variation data to single product form
 * @author M-MALEKZADEH77
 * @since 1.0
 */
function irantheme_add_custom_variation_data($variation_data, $product, $variation)
{

    // 1. Read the file version
    $file_version = get_post_meta($variation->get_id(), '_file_version_number', true);
    $variation_data['file_version_number'] = $file_version ? $file_version : '';

    // 2. Read the publication date
    $release_date = get_post_meta($variation->get_id(), '_file_release_date', true);
    $variation_data['file_release_date'] = $release_date ? $release_date : '';

    // 3. Read the update date
    $update_date = get_post_meta($variation->get_id(), '_file_update_date', true);
    $variation_data['file_update_date'] = $update_date ? $update_date : '';

    return $variation_data;
}
add_filter('woocommerce_available_variation', 'irantheme_add_custom_variation_data', 10, 3);

/**
 * Checks whether "all" active variations of a variable product are downloadable.
 * @param WC_Product $product
 * @return bool
 * @author M-MALEKZADEH77
 * @since 1.0
 */
function irantheme_are_all_variations_downloadable($product)
{
    // Step 1: Check the product type
    if (! $product->is_type('variable')) {
        // If the product is not variable, use the regular method (for simple product)
        return $product->is_downloadable();
    }

    // Step 2: Get active variations
    $available_variations = $product->get_available_variations();

    // If the product is variable but does not have an active variation, we assume it is not a download.
    if (empty($available_variations)) {
        return false;
    }

    // Step 3: Review each variation
    foreach ($available_variations as $variation_data) {

        // If at least one active variation is not downloadable, immediately return False.
        if (! (isset($variation_data['is_downloadable']) && $variation_data['is_downloadable'])) {
            return false;
        }
    }

    // If we got out of the loop, it means everyone was downloading.
    return true;
}

/**
 * Add read more functionality to WooCommerce product description tab
 * 
 * This function adds a "Read More" toggle button to product descriptions
 * that exceed a certain height limit. It includes CSS for styling and
 * JavaScript for the toggle functionality.
 * 
 * @return void
 * @author M-MALEKZADEH77
 * @since 1.0
 */
function add_read_more_to_description()
{
    // Only apply to product pages to avoid unnecessary code execution
    if (!is_woocommerce_activated() || !function_exists('is_product') || !is_product()) return;

    ?>
    <script>
        // Initialize when DOM is fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Find product description tab panel
            const descPanel = document.querySelector('.irantheme-single-product-content-tabs-panel-description');

            // Exit if description panel doesn't exist
            if (!descPanel) return;

            // Wrap the content in a new container
            const contentWrapper = document.createElement('div');
            contentWrapper.className = 'description-tab-content-wrapper';

            // Move all content to the wrapper
            while (descPanel.firstChild) {
                contentWrapper.appendChild(descPanel.firstChild);
            }

            // Add wrapper back to panel
            descPanel.appendChild(contentWrapper);

            // Check if content is longer than 450px
            if (contentWrapper.scrollHeight <= 450) return;

            // Add collapsed class to wrapper
            contentWrapper.classList.add('description-collapsed');

            // Create gradient fade overlay
            const fade = document.createElement('div');
            fade.className = 'description-tab-read-more-fade';
            contentWrapper.appendChild(fade);

            // Create toggle button - add AFTER the wrapper
            const btn = document.createElement('button');
            btn.className = 'description-tab-read-more-toggle <?php echo get_single_product_button_styles(); ?> description-collapsed';
            btn.innerHTML = 'مشاهده بیشتر';

            // Add button after the content wrapper
            contentWrapper.parentNode.insertBefore(btn, contentWrapper.nextSibling);

            // Toggle functionality
            btn.addEventListener('click', function() {
                if (contentWrapper.classList.contains('description-collapsed')) {
                    // Expand content
                    contentWrapper.classList.remove('description-collapsed');
                    contentWrapper.classList.add('description-expanded');
                    btn.classList.remove('description-collapsed');
                    btn.classList.add('description-expanded');
                    btn.innerHTML = 'بستن توضیحات';
                } else {
                    // Collapse content
                    contentWrapper.classList.remove('description-expanded');
                    contentWrapper.classList.add('description-collapsed');
                    btn.classList.remove('description-expanded');
                    btn.classList.add('description-collapsed');
                    btn.innerHTML = 'مشاهده بیشتر';
                }
            });
        });
    </script>
<?php
}

// Hook the function to wp_footer to output the code
add_action('wp_footer', 'add_read_more_to_description');

    // =============================================================================

/**
 * This function save last login date and time
 * 
 * @return void
 * @author M-MALEKZADEH77
 * @since 1.0.2
 */
function irantheme_save_last_login($user_login, $user)
{


    $last_login = get_user_meta($user->ID, 'last_login', true);

    if ($last_login) {
        update_user_meta($user->ID, 'previous_login', $last_login);
    }

    update_user_meta(
        $user->ID,
        'last_login',
        current_datetime()->getTimestamp()
    );
}
add_action('wp_login', 'irantheme_save_last_login', 10, 2);


// =============================================================================

/**
 * This function save recently viewed products to cookies
 * 
 * @return void
 * @author M-MALEKZADEH77
 * @since 1.0.2
 */
function irantheme_track_recently_viewed_products()
{
    if (!is_woocommerce_activated()) {
        return;
    }

    if (!is_product()) {
        return;
    }

    global $post;

    if (empty($post->ID)) {
        return;
    }

    $viewed_products = [];

    if (isset($_COOKIE['recently_viewed_products'])) {
        $viewed_products = explode('|', $_COOKIE['recently_viewed_products']);
    }

    // Remove duplicates
    $viewed_products = array_diff($viewed_products, [$post->ID]);

    // Add to the beginning of the list
    array_unshift($viewed_products, $post->ID);

    // Limit the number (e.g., 10)
    $viewed_products = array_slice($viewed_products, 0, 10);

    setcookie(
        'recently_viewed_products',
        implode('|', $viewed_products),
        time() + MONTH_IN_SECONDS,
        COOKIEPATH,
        COOKIE_DOMAIN
    );
}
add_action('template_redirect', 'irantheme_track_recently_viewed_products');

/**
 * Returen array of recently viewed product ids
 * 
 * @return array recently viewed product ids
 * @author M-MALEKZADEH77
 * @since 1.0.2
 */
function irantheme_get_recently_viewed_products($limit = 3)
{

    if (empty($_COOKIE['recently_viewed_products'])) {
        return [];
    }

    $product_ids = explode('|', $_COOKIE['recently_viewed_products']);
    $product_ids = array_map('absint', $product_ids);

    return array_slice($product_ids, 0, $limit);
}

/**
 * Add custom unit field to product general tab
 * @author M-MALEKZADEH77
 * @since 1.0.2
 */
add_action('woocommerce_product_options_general_product_data', 'irantheme_add_product_unit_field');
function irantheme_add_product_unit_field()
{
    woocommerce_wp_text_input(array(
        'id'          => '_product_unit',
        'label'       => 'واحد اندازه‌گیری',
        'placeholder' => 'مثال: عدد، کیلوگرم، متر، بسته',
        'desc_tip'    => 'true',
        'description' => 'واحد اندازه‌گیری محصول را وارد کنید'
    ));
}

/**
 * Save custom unit field
 * @author M-MALEKZADEH77
 * @since 1.0.2
 */
add_action('woocommerce_process_product_meta', 'irantheme_save_product_unit_field');
function irantheme_save_product_unit_field($post_id)
{
    $unit = isset($_POST['_product_unit']) ? sanitize_text_field($_POST['_product_unit']) : '';
    update_post_meta($post_id, '_product_unit', $unit);
}

/**
 * Add unit field to variation options
 * @author M-MALEKZADEH77
 * @since 1.0.2
 */
add_action('woocommerce_variation_options_pricing', 'irantheme_add_variation_unit_field', 10, 3);
function irantheme_add_variation_unit_field($loop, $variation_data, $variation)
{
    woocommerce_wp_text_input(array(
        'id'          => '_variation_unit[' . $loop . ']',
        'label'       => 'واحد اندازه‌گیری',
        'placeholder' => 'مثال: عدد، کیلوگرم، متر',
        'value'       => get_post_meta($variation->ID, '_variation_unit', true)
    ));
}

/**
 * Save variation unit field
 * @author M-MALEKZADEH77
 * @since 1.0.2
 */
add_action('woocommerce_save_product_variation', 'irantheme_save_variation_unit_field', 10, 2);
function irantheme_save_variation_unit_field($variation_id, $loop)
{
    $unit = isset($_POST['_variation_unit'][$loop]) ? sanitize_text_field($_POST['_variation_unit'][$loop]) : '';
    update_post_meta($variation_id, '_variation_unit', $unit);
}

/**
 * Set default unit if not specified
 * @param string $key 
 * @param string $value 
 * @return string $valu default product unit 
 * @author M-MALEKZADEH77
 * @since 1.0.2
 */
function irantheme_set_default_product_unit($value, $key)
{
    if ($key === '_unit' && empty($value)) {
        return 'عدد'; // Default unit
    }
    return $value;
}
add_filter('woocommerce_product_get_meta', 'irantheme_set_default_product_unit', 10, 2);


/**
 * Get brand logo from term meta
 * @param int $term_id
 * @return string|false
 * @author iranianclever
 */
function irantheme_get_brand_logo($term_id, $taxonomy)
{
    // First try to get the brand logo from the custom field (set in TaxonomyTermTypes class)
    $brand_logo_id = get_option("_term_type_{$taxonomy}_{$term_id}");

    if ($brand_logo_id && is_numeric($brand_logo_id)) {
        return wp_get_attachment_image($brand_logo_id, 'medium', false, [
            'class' => 'irantheme-woocommerce-box-brands-item-heading-image-img',
            'alt' => esc_attr(get_term_field('name', $term_id)),
            'loading' => 'lazy',
        ]);
    }

    // Fallback to WooCommerce brand thumbnail if available
    $thumbnail_id = get_term_meta($term_id, 'thumbnail_id', true);
    if ($thumbnail_id) {
        return wp_get_attachment_image($thumbnail_id, 'medium', false, [
            'class' => 'irantheme-woocommerce-box-brands-item-heading-image-img',
            'alt' => esc_attr(get_term_field('name', $term_id)),
            'loading' => 'lazy',
        ]);
    }

    return false;
}

/**
 * Rwmove Grouped WC Product Type 
 * @author M-MALEKZADEH77
 * @since 1.0.2
 */
function irantheme_remove_grouped_product_type($types)
{
    // Remove grouped product type
    if (isset($types['grouped'])) {
        unset($types['grouped']);
    }
    return $types;
}
add_filter('product_type_selector', 'irantheme_remove_grouped_product_type');
