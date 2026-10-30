<?php

if (!defined('ABSPATH')) exit;

use Irantheme_Dev\Irantheme_Color_Static;
use Irantheme_Dev\Irantheme_Section_Woocommerce;

if (!class_exists('Irantheme_Ajax_Search_Box')) {

    /**
     * Irantheme search ajax on search box
     * Add products search actions on ajax
     * 
     * @package Wordpress
     * @subpackage Storeplus
     * @since v1.0
     * 
     * Generation products on ajax search box
     */
    class Irantheme_Ajax_Search_Box
    {
        private static $category_searches_option = 'irantheme_category_searches';
        private static $category_stats_option = 'irantheme_category_stats';

        // Initialization
        function __construct()
        {
            // Products actions
            add_action('wp_ajax_get_wc_title', array($this, 'ajax_products'));
            add_action('wp_ajax_nopriv_get_wc_title', array($this, 'ajax_products'));
            // Posts actions
            add_action('wp_ajax_data_fetch', array($this, 'ajax_posts'));
            add_action('wp_ajax_nopriv_data_fetch', array($this, 'ajax_posts'));

            // Category tracking
            add_action('wp_ajax_track_category_search', array($this, 'track_category_search'));
            add_action('wp_ajax_nopriv_track_category_search', array($this, 'track_category_search'));

            // Admin menu
            add_action('admin_menu', array($this, 'add_admin_menu'));

            // Shortcode
            add_shortcode('most_searched_categories', array($this, 'most_searched_categories_shortcode'));
        }

        /**
         * Track category search
         */
        public static function track_category_search()
        {
            // Verify nonce
            if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'wp_rest')) {
                wp_send_json_error('Security check failed');
            }

            $category_id = intval($_POST['category_id']);

            if (!$category_id || $category_id <= 0) {
                wp_send_json_error('Invalid category ID');
            }

            self::increment_category_search_count($category_id);

            wp_send_json_success('Category tracked successfully');
        }

        /**
         * Increment category search count
         */
        private static function increment_category_search_count($category_id)
        {
            $searches = get_option(self::$category_searches_option, array());

            // Initialize if not exists
            if (!isset($searches[$category_id])) {
                $searches[$category_id] = array(
                    'count' => 0,
                    'last_searched' => current_time('timestamp'),
                    'first_searched' => current_time('timestamp')
                );
            }

            // Increment count and update timestamp
            $searches[$category_id]['count']++;
            $searches[$category_id]['last_searched'] = current_time('timestamp');

            // Update option
            update_option(self::$category_searches_option, $searches, false);

            // Update overall stats
            self::update_category_stats();
        }

        /**
         * Update category statistics
         */
        private static function update_category_stats()
        {
            $searches = get_option(self::$category_searches_option, array());

            $total_searches = 0;
            $unique_categories = count($searches);
            $today_searches = 0;

            $today_start = strtotime('today midnight');

            foreach ($searches as $category_id => $data) {
                $total_searches += $data['count'];

                // Count today's searches
                if ($data['last_searched'] >= $today_start) {
                    $today_searches++;
                }
            }

            $stats = array(
                'total_searches' => $total_searches,
                'unique_categories' => $unique_categories,
                'today_searches' => $today_searches,
                'last_updated' => current_time('timestamp'),
                'top_categories' => self::get_top_categories(5, true) // Get fresh top 5
            );

            update_option(self::$category_stats_option, $stats, false);
        }

        /**
         * Get top searched categories
         */
        public static function get_top_categories($limit = 10, $force_refresh = false, $force_return = false)
        {
            $searches = get_option(self::$category_searches_option, array());
            $results = array();
            $invalid_ids = array(); // Track invalid IDs to clean up later

            // Check force return
            if ($force_return && count($searches) < 3) {
                $categories_extra = get_terms(array(
                    'taxonomy'   => 'product_cat',
                    'orderby'    => 'count',
                    'order'      => 'DESC',
                    'number'     => 5,
                    'hide_empty' => true,
                    'fields'     => 'all' // Ensure we get all fields
                ));

                if (!is_wp_error($categories_extra) && !empty($categories_extra)) {
                    foreach ($categories_extra as $category) {
                        // No need for extra get_term call since we already have the term object
                        if ($category && !is_wp_error($category)) {
                            $results[] = array(
                                'id' => $category->term_id,
                                'name' => $category->name,
                                'slug' => $category->slug,
                                'url' => get_term_link($category),
                            );
                        }
                    }
                }
            } else {
                // Sort by count
                uasort($searches, function ($a, $b) {
                    return $b['count'] - $a['count'];
                });

                // Get limited results
                $limited_searches = array_slice($searches, 0, $limit, true);

                foreach ($limited_searches as $term_id => $data) {
                    // Validate term_id is numeric and positive
                    if (!is_numeric($term_id) || $term_id <= 0) {
                        $invalid_ids[] = $term_id;
                        continue;
                    }

                    // Check if term exists before trying to get it
                    $term_exists = term_exists(intval($term_id), 'product_cat');

                    if (!$term_exists) {
                        $invalid_ids[] = $term_id;
                        continue;
                    }

                    $category = get_term(intval($term_id), 'product_cat');

                    if ($category && !is_wp_error($category) && !empty($category->term_id)) {
                        $results[] = array(
                            'id' => $term_id,
                            'name' => $category->name,
                            'slug' => $category->slug,
                            'count' => $data['count'],
                            'url' => get_term_link($category),
                            'last_searched' => $data['last_searched'],
                            'last_searched_formatted' => date_i18n(get_option('date_format'), $data['last_searched']),
                            'percentage' => self::calculate_percentage($data['count'], $searches)
                        );
                    } else {
                        $invalid_ids[] = $term_id;
                    }
                }

                // Clean up invalid IDs from the searches option
                if (!empty($invalid_ids)) {
                    foreach ($invalid_ids as $invalid_id) {
                        unset($searches[$invalid_id]);
                    }
                    update_option(self::$category_searches_option, $searches);
                }
            }

            return $results;
        }

        /**
         * Calculate percentage of total searches
         */
        private static function calculate_percentage($count, $all_searches)
        {
            $total = 0;
            foreach ($all_searches as $data) {
                $total += $data['count'];
            }

            if ($total == 0) return 0;

            return round(($count / $total) * 100, 2);
        }

        /**
         * Get category statistics
         */
        public static function get_category_stats()
        {
            $stats = get_option(self::$category_stats_option, array());

            if (empty($stats) || !isset($stats['last_updated']) || (current_time('timestamp') - $stats['last_updated']) > 3600) {
                self::update_category_stats();
                $stats = get_option(self::$category_stats_option, array());
            }

            return $stats;
        }

        /**
         * Add admin menu for analytics
         */
        public function add_admin_menu()
        {
            add_submenu_page(
                'woocommerce',
                __('جستجوهای دسته بندی', IRANTHEME_TEXTDOMAIN),
                __('آمار جستجو', IRANTHEME_TEXTDOMAIN),
                'manage_woocommerce',
                'irantheme-category-analytics',
                array($this, 'display_analytics_page')
            );
        }

        /**
         * Display analytics admin page
         */
        public function display_analytics_page()
        {
            $top_categories = self::get_top_categories(50);
            $stats = self::get_category_stats();

?>
            <div class="wrap">
                <h1><?php _e('آمار جستجوهای دسته بندی ووکامرس', IRANTHEME_TEXTDOMAIN); ?></h1>

                <div class="irantheme-analytics-stats">
                    <div class="stat-card">
                        <h3><?php _e('کل جستجوها', IRANTHEME_TEXTDOMAIN); ?></h3>
                        <div class="stat-number"><?php echo number_format_i18n($stats['total_searches']); ?></div>
                    </div>
                    <div class="stat-card">
                        <h3><?php _e('دسته‌بندی‌های جستجو شده', IRANTHEME_TEXTDOMAIN); ?></h3>
                        <div class="stat-number"><?php echo number_format_i18n($stats['unique_categories']); ?></div>
                    </div>
                    <div class="stat-card">
                        <h3><?php _e('جستجوهای امروز', IRANTHEME_TEXTDOMAIN); ?></h3>
                        <div class="stat-number"><?php echo number_format_i18n($stats['today_searches']); ?></div>
                    </div>
                </div>

                <h2><?php _e('پر جستجوترین دسته‌بندی‌ها', IRANTHEME_TEXTDOMAIN); ?></h2>

                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="40%"><?php _e('دسته‌بندی', IRANTHEME_TEXTDOMAIN); ?></th>
                            <th width="15%"><?php _e('تعداد جستجو', IRANTHEME_TEXTDOMAIN); ?></th>
                            <th width="20%"><?php _e('درصد', IRANTHEME_TEXTDOMAIN); ?></th>
                            <th width="20%"><?php _e('آخرین جستجو', IRANTHEME_TEXTDOMAIN); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($top_categories)): ?>
                            <?php $rank = 1; ?>
                            <?php foreach ($top_categories as $category): ?>
                                <tr>
                                    <td><?php echo $rank; ?></td>
                                    <td>
                                        <strong>
                                            <a href="<?php echo esc_url($category['url']); ?>" target="_blank">
                                                <?php echo esc_html($category['name']); ?>
                                            </a>
                                        </strong>
                                        <br>
                                        <small><?php _e('شناسه:', IRANTHEME_TEXTDOMAIN); ?> <?php echo esc_html($category['id']); ?></small>
                                    </td>
                                    <td><?php echo number_format_i18n($category['count']); ?></td>
                                    <td>
                                        <div class="percentage-bar">
                                            <div class="bar-fill" style="width: <?php echo min($category['percentage'], 100); ?>%"></div>
                                            <span class="percentage-text"><?php echo $category['percentage']; ?>%</span>
                                        </div>
                                    </td>
                                    <td><?php echo $category['last_searched_formatted']; ?></td>
                                </tr>
                                <?php $rank++; ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">
                                    <?php _e('هنوز هیچ جستجویی ثبت نشده است.', IRANTHEME_TEXTDOMAIN); ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <style>
                    .irantheme-analytics-stats {
                        display: flex;
                        gap: 20px;
                        margin: 20px 0;
                    }

                    .stat-card {
                        background: #fff;
                        border: 1px solid #ccd0d4;
                        border-radius: 4px;
                        padding: 20px;
                        flex: 1;
                        text-align: center;
                    }

                    .stat-card h3 {
                        margin: 0 0 10px 0;
                        color: #646970;
                        font-size: 14px;
                    }

                    .stat-number {
                        font-size: 32px;
                        font-weight: bold;
                        color: #2271b1;
                    }

                    .percentage-bar {
                        background: #f0f0f0;
                        height: 24px;
                        border-radius: 3px;
                        position: relative;
                        overflow: hidden;
                    }

                    .bar-fill {
                        background: #2271b1;
                        height: 100%;
                        transition: width 0.3s ease;
                    }

                    .percentage-text {
                        position: absolute;
                        top: 50%;
                        left: 50%;
                        transform: translate(-50%, -50%);
                        color: #fff;
                        font-weight: bold;
                        text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.3);
                    }
                </style>
            </div>
        <?php
        }

        /**
         * Shortcode to display most searched categories
         */
        public function most_searched_categories_shortcode($atts)
        {
            $atts = shortcode_atts(array(
                'limit' => 10,
                'title' => __('پر جستجوترین دسته‌بندی‌ها', IRANTHEME_TEXTDOMAIN),
                'show_count' => true,
                'show_percentage' => false
            ), $atts);

            $categories = self::get_top_categories($atts['limit']);

            if (empty($categories)) {
                return '';
            }

            ob_start();
        ?>
            <div class="irantheme-most-searched-categories">
                <?php if ($atts['title']): ?>
                    <h3 class="categories-title"><?php echo esc_html($atts['title']); ?></h3>
                <?php endif; ?>

                <ul class="categories-list">
                    <?php foreach ($categories as $category): ?>
                        <li class="category-item">
                            <a href="<?php echo esc_url($category['url']); ?>" class="category-link">
                                <span class="category-name"><?php echo esc_html($category['name']); ?></span>
                                <?php if ($atts['show_count']): ?>
                                    <span class="category-count">(<?php echo number_format_i18n($category['count']); ?>)</span>
                                <?php endif; ?>
                            </a>
                            <?php if ($atts['show_percentage']): ?>
                                <div class="category-percentage">
                                    <div class="percentage-bar" style="width: <?php echo min($category['percentage'], 100); ?>%"></div>
                                </div>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <style>
                .irantheme-most-searched-categories {
                    background: #fff;
                    border: 1px solid #e0e0e0;
                    border-radius: 5px;
                    padding: 20px;
                }

                .categories-title {
                    margin-top: 0;
                    margin-bottom: 15px;
                    padding-bottom: 10px;
                    border-bottom: 1px solid #eee;
                }

                .categories-list {
                    list-style: none;
                    padding: 0;
                    margin: 0;
                }

                .category-item {
                    margin-bottom: 10px;
                    padding-bottom: 10px;
                    border-bottom: 1px dashed #f0f0f0;
                }

                .category-item:last-child {
                    margin-bottom: 0;
                    padding-bottom: 0;
                    border-bottom: none;
                }

                .category-link {
                    display: flex;
                    justify-content: space-between;
                    text-decoration: none;
                    color: #333;
                    transition: color 0.2s;
                }

                .category-link:hover {
                    color: #2271b1;
                }

                .category-count {
                    color: #666;
                    font-size: 0.9em;
                }

                .category-percentage {
                    margin-top: 5px;
                }

                .category-percentage .percentage-bar {
                    height: 4px;
                    background: #2271b1;
                    border-radius: 2px;
                }
            </style>
            <?php

            return ob_get_clean();
        }

        // Fetch products (your existing code)
        public function ajax_products()
        {
            $search_input = $_POST['search_input'];
            $search_input_lowercase = strtolower($search_input);
            // Holder category filter
            $search_category = $_POST['search_category'];
            $args_filter_category =  array();
            // Check category id (Default or no)
            if ($search_category != -1 && $search_category) {
                $args_filter_category =
                    array(
                        array(
                            'taxonomy' => 'product_cat',
                            'terms' => $search_category,
                            'operator' => 'IN',
                        )
                    );
            }
            // Init args
            $products_query_args = array(
                'post_type' => 'product',
                'posts_per_page' => -1,
                'tax_query' => $args_filter_category,

            );
            $product_query = new WP_Query($products_query_args);

            // Check product is exists
            if ($product_query->have_posts()) {
            ?>
                <div class="row">
                    <div class="irantheme-search-results-label"><?php _e('محصولات سایت : '); ?></div>
                    <?php
                    // Loop from products
                    while ($product_query->have_posts()) {
                        $product_query->the_post();
                        $product = wc_get_product(get_the_ID());
                        $product_title = $product->get_title();
                        $product_title_lowercase = strtolower($product_title);
                        $product_permalink = $product->get_permalink();
                        $product_thumbnail = $product->get_image($size = 'shop_thumbnail', $attr = array(), $placeholder = true);
                        // Get product term category
                        $post_cate_terms = get_the_terms(get_the_ID(), 'product_cat');
                        $post_categories_text = '';

                        if ($post_cate_terms && !is_wp_error($post_cate_terms)) {
                            foreach ($post_cate_terms as $category) {
                                $post_categories_text .= '<span><i class="iconsax ' . Irantheme_Color_Static::get_class_color_mode('color', null, Irantheme_Color_Static::get_hex_color('primary')) . '" icon-name="hashtag"></i>' . esc_html($category->name) . '</span>';
                            }
                        }
                        if (strpos($product_title_lowercase, $search_input_lowercase) !== false) {
                    ?>
                            <!-- Post search result -->
                            <div class="col-12">
                                <div class="irantheme-search-result-post">
                                    <!-- Permalink holder -->
                                    <a href="<?php echo esc_attr($product_permalink); ?>">
                                        <?php if ($product_thumbnail) : ?>
                                            <!-- Thumbnail -->
                                            <div class="irantheme-search-result-post-img"><?php echo $product_thumbnail; ?></div>
                                        <?php endif; ?>
                                        <div class="irantheme-search-result-post-info">
                                            <div class="irantheme-search-result-post-options">
                                                <?php echo $post_categories_text; ?>
                                            </div>
                                            <div class="irantheme-search-result-post-heading">
                                                <h3><?php echo esc_html($product_title); ?></h3>
                                            </div>
                                            <div class="irantheme-search-result-post-woocommerce">
                                                <div class="irantheme-search-result-post-woocommerce-options">
                                                    <div class="irantheme-search-result-post-woocommerce-options-price">
                                                        <?php Irantheme_Section_Woocommerce::display_product_price($product); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                    <?php
                        }
                    }
                    ?>
                </div>
            <?php
            }
            // Rest post data (Clear main query)
            wp_reset_postdata();
            die();
        }

        // Fetch posts (your existing code)
        public function ajax_posts()
        {
            $search_input = $_POST['search_input'];
            $search_input_lowercase = strtolower($search_input);
            // Holder category filter
            $search_category = $_POST['search_category'];
            $args_filter_category =  array();

            // Check category id (Default or no)
            if ($search_category != -1 && $search_category) {
                $args_filter_category =
                    array(
                        array(
                            'taxonomy' => 'category',
                            'terms' => $search_category,
                            'operator' => 'IN',
                        )
                    );
            }
            // Init args
            $posts_query_args = array(
                'post_type' => 'post',
                'posts_per_page' => -1,
                // 's' => $search_input_lowercase,
                'tax_query' => $args_filter_category,

            );
            $post_query = new WP_Query($posts_query_args);

            // Check product is exists
            if ($post_query->have_posts()) {
            ?>
                <div class="row">
                    <div class="irantheme-search-results-label"><?php _e('مطالب سایت : ', IRANTHEME_TEXTDOMAIN); ?></div>
                    <?php
                    // Loop from products
                    while ($post_query->have_posts()) {
                        $post_query->the_post();
                        $post_title = get_the_title();
                        $post_title_lowercase = strtolower($post_title);
                        $post_permalink = get_the_permalink();
                        $post_thumbnail = wp_get_attachment_image(get_post_thumbnail_id(get_the_ID()), 'medium');
                        $post_date = get_the_date('j F Y');
                        // Get product term category
                        $post_cate_terms = get_the_terms(get_the_ID(), 'category');
                        $post_categories_text = '';
                        // Add product category to holder category
                        foreach ($post_cate_terms as $category) {
                            $post_categories_text .= '<span>' . $category->name . '</span>';
                        }
                        if (strpos($post_title_lowercase, $search_input_lowercase) !== false) {
                    ?>
                            <!-- Post search result -->
                            <div class="col-12">
                                <div class="irantheme-search-result-post">
                                    <!-- Permalink holder -->
                                    <a href="<?php echo esc_attr($post_permalink); ?>">
                                        <?php if ($post_thumbnail) : ?>
                                            <!-- Thumbnail -->
                                            <div class="irantheme-search-result-post-img"><?php echo $post_thumbnail; ?></div>
                                        <?php endif; ?>
                                        <div class="irantheme-search-result-post-info">
                                            <div class="irantheme-search-result-post-options">
                                                <?php echo $post_categories_text; ?>
                                            </div>
                                            <div class="irantheme-search-result-post-heading">
                                                <h3><?php echo esc_html($post_title); ?></h3>
                                            </div>
                                            <div class="irantheme-search-result-post-date">
                                                <i class="iconsax <?php echo esc_attr(Irantheme_Color_Static::get_class_color_mode('color', 'secondary')) ?>" icon-name="clock"></i>
                                                <span><?php echo $post_date; ?></span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                    <?php
                        }
                    }
                    ?>
                </div>
<?php
            }
            // Rest post data (Clear main query)
            wp_reset_postdata();
            die();
        }
    }

    new Irantheme_Ajax_Search_Box();
}
