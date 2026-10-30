<?php

/**
 * Custom search template that redirects to shop search when appropriate
 */

if (is_woocommerce_activated() && get_option('woocommerce_shop_page_id')) {
    // Check if this is a product search
    if (isset($_GET['post_type']) && $_GET['post_type'] === 'product') {
        // Already a product search, let WordPress handle it
        get_template_part('search');
        return;
    }

    // Check if the search query contains product-related terms
    $search_query = get_search_query();
    if (!empty($search_query)) {
        // Redirect to shop search
        wp_redirect(get_permalink(wc_get_page_id('shop')) . '?search=' . urlencode($search_query) . '&ordering_type=date');
        exit;
    }
}

// Get blog template (Index)
Irantheme_Archives::get_template_index();
