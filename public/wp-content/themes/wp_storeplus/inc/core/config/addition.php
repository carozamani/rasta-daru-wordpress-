<?php

if (!function_exists('irantheme_custom_upload_size_limit')) {
    /**
     * Set size upload file
     * @return int
     */
    function irantheme_custom_upload_size_limit($size)
    {
        // Set limit to 1000MB (100 * 1024 * 1024 bytes)
        return 1000 * 1024 * 1024;
    }
    add_filter('upload_size_limit', 'irantheme_custom_upload_size_limit');
}

if (!function_exists('irantheme_include_features')) {
    /**
     * Add features
     * @return void
     */
    function irantheme_include_features()
    {
        // Add title tag
        add_theme_support('title-tag');
        // Add thumbnail image to posts
        add_theme_support('post-thumbnails');
        // Search form support
        add_theme_support('html5', array('search-form'));
        // Custom logo
        add_theme_support('custom-logo');
        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');
        // Add post format features
        add_theme_support(
            'post-formats',
            array(
                // 'link',
                // 'aside',
                'gallery',
                // 'image',
                'quote',
                // 'status',
                'video',
                'audio',
                // 'chat',
            )
        );
        // Add image size
        // add_image_size('professorLandscape', 400, 260, true);
        // add_image_size('professorPortraite', 480, 650, true);
        // add_image_size('pageBanner', 1500, 350, true);

        @ini_set('upload_max_size', '64M');
        @ini_set('post_max_size', '64M');
        @ini_set('max_execution_time', '300');

        // Load theme text domain
        load_theme_textdomain(
            IRANTHEME_TEXTDOMAIN,
            IRANTHEME_DIR . '/languages'
        );
    }
    add_action('after_setup_theme', 'irantheme_include_features');
}


if (!function_exists('irantheme_custom_body_class')) {
    /**
     * Add custom class to body_class
     * @return void
     */
    function irantheme_custom_body_class($classes)
    {
        $classes[] = 'irantheme-page-builder-inactive';

        return $classes;
    }
    add_filter('body_class', 'irantheme_custom_body_class');
}


if (!function_exists('irantheme_mime_types')) {
    /**
     * Allow to use svg files
     * @return array
     * @see v1.0
     */
    function irantheme_mime_types($mimes)
    {
        $mimes['svg'] = 'image/svg+xml';
        $mimes['xml'] = 'application/xml';
        return $mimes;
    }
    add_filter('upload_mimes', 'irantheme_mime_types');
}


if (!function_exists('irantheme_add_custom_meta_description')) {
    /**
     * Add meta description to website head tag
     * @return void
     */
    function irantheme_add_custom_meta_description()
    {
        // If it's the front page or blog home page
        if (is_front_page() || is_home()) {
            $description = get_bloginfo('description'); // Site tagline
        }
        // If it's a single post, page, or product
        elseif (is_singular()) {
            global $post;

            // 1. First priority: manual excerpt (if it exists)
            if (! empty($post->post_excerpt)) {
                $description = $post->post_excerpt;
            }
            // 2. Second priority: WooCommerce product short description
            elseif (function_exists('wc_get_product') && is_product()) {
                $product = wc_get_product($post->ID);
                $description = $product->get_short_description();
                if (empty($description)) {
                    $description = $product->get_description(); // Fallback to full description if short is empty
                }
            }
            // 3. Third priority: first 30 words of content
            else {
                $description = wp_strip_all_tags($post->post_content);
                $description = wp_trim_words($description, 30, '...');
            }
        }
        // For archives, categories, and all other pages
        else {
            $description = get_bloginfo('description');
        }

        // Final cleanup
        $description = wp_strip_all_tags($description);
        $description = trim($description);

        // Only output the tag if a description exists
        if (! empty($description)) {
            echo '<meta name="description" content="' . esc_attr($description) . '">' . "\n";
        }
    }
    add_action('wp_head', 'irantheme_add_custom_meta_description');
}


if (!function_exists('irantheme_get_sections_html')) {
    /**
     * Get sections html for page builder
     * @return void
     */
    function irantheme_get_sections_html()
    {
        check_ajax_referer('irantheme_builder', '_nonce');

        $page_id = isset($_POST['page_id']) ? absint($_POST['page_id']) : 0;
        if (!$page_id) {
            wp_send_json_error('missing page_id');
        }

        ob_start();
        \Irantheme_Dev\Irantheme_Page_Builder::display_section($page_id);
        $html = ob_get_clean();

        wp_send_json_success(['html' => $html]);
    }
    add_action('wp_ajax_irantheme_get_sections_html', 'irantheme_get_sections_html');
}


if (!function_exists('irantheme_modify_gallery_image_size')) {
    /**
     * Modifying gallery image sizes
     * @return array
     * @param output, pairs attrs
     */
    function irantheme_modify_gallery_image_size($output, $pairs, $atts)
    {
        $atts = shortcode_atts(array(
            'columns' => '2',
            'size' => 'large',
        ), $atts);

        $output['columns'] = $atts['columns'];
        $output['size'] = $atts['size'];

        return $output;
    }
    add_filter('shortcode_atts_gallery', 'irantheme_modify_gallery_image_size', 10, 3);
}


if (!function_exists('irantheme_better_comments')) {
    /**
     * Better comments (Related to comments list)
     * @return void
     * @param string, args, depth
     */
    function irantheme_better_comments($comment, $args, $depth)
    { ?>
        <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
            <div class="comment-avatar">
                <?php echo get_avatar($comment); ?>
            </div>
            <div class="comment-content">
                <?php if ($comment->comment_approved == '0') : ?>
                    <em><?php esc_html_e('نظر شما در صف بررسی است.', '5balloons_theme') ?></em>
                    <br />
                <?php endif; ?>
                <div class="comment-heading">
                    <b><?php echo get_comment_author() ?></b>
                </div>
                <div class="comment-options">
                    <span class="date"><?php printf(/* translators: 1: date and time(s). */esc_html__('%1$s at %2$s', '5balloons_theme'), get_comment_date(),  get_comment_time()) ?></span>
                </div>
                <div class="comment-body">
                    <?php comment_text() ?>
                </div>
                <div class="comment-reply">
                    <?php comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
                    <?php edit_comment_link(__('ویرایش', 'textdomain'), '  ', ''); ?>
                </div>
            </div>
        </li>
<?php
    }
}


if (!function_exists('irantheme_redirect_subs_to_fronted')) {
    /**
     * Redirect subscriber user to home after login
     * @return void
     */
    function irantheme_redirect_subs_to_fronted()
    {
        $currentUser = wp_get_current_user();
        if (count($currentUser->roles) == 1 and $currentUser->roles[0] == 'subscriber') {
            wp_redirect(site_url('/'));
            exit;
        }
    }
    add_action('admin_init', 'irantheme_redirect_subs_to_fronted');
}


if (!function_exists('irantheme_no_subs_admin_bar')) {
    /**
     * Hide admin bar in subscriber user
     * @return void
     */
    function irantheme_no_subs_admin_bar()
    {
        $currentUser = wp_get_current_user();
        if (count($currentUser->roles) == 1 and $currentUser->roles[0] == 'subscriber') {
            show_admin_bar(false);
        }
    }
    add_action('wp_loaded', 'irantheme_no_subs_admin_bar');
}


if (!function_exists('irantheme_our_header_url')) {
    /**
     * Customize login screen
     * @return string (url)
     */
    function irantheme_our_header_url()
    {
        return esc_url(site_url('/'));
    }
    add_filter('login_headerurl', 'irantheme_our_header_url');
}


if (!function_exists('irantheme_our_login_css')) {
    /**
     * Manually css to login screen
     * @return void
     */
    function irantheme_our_login_css()
    {
        wp_enqueue_style('irantheme_admin_main_styles', get_stylesheet_uri());
    }
    add_action('login_enqueue_scripts', 'irantheme_our_login_css');
}


if (!function_exists('irantheme_our_login_title')) {
    /**
     * Manually title login screen
     * @return string (website title)
     */
    function irantheme_our_login_title()
    {
        return get_bloginfo('name');
    }
    add_filter('login_headertext', 'irantheme_our_login_title');
}


/* Check yoast is active */
if (in_array('wordpress-seo/wp-seo.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    // Remove yoast plugin conflict with reply comment link
    add_filter('wpseo_remove_reply_to_com', '__return_false');
}


add_filter('get_pagenum_link', 'user_trailingslashit');


// Add notification bubbles to contact form
add_action('admin_menu', function () {
    global $menu;
    $count_posts = wp_count_posts('contact');
    $count = 0;
    if ($count_posts) {
        $count = $count_posts->publish;
    }
    $menu_item = wp_list_filter(
        $menu,
        array(2 => 'edit.php?post_type=contact') // 2 is the position of an array item which contains URL, it will always be 2!
    );
    if (!empty($menu_item)) {
        $menu_item_position = key($menu_item); // get the array key (position) of the element
        $menu[$menu_item_position][0] .= ' <span class="awaiting-mod">' . $count . '</span>';
    }
});


function irantheme_adjust_the_wp_menu()
{
    $page = remove_submenu_page('edit.php?post_type=contact', 'post-new.php?post_type=contact');
}
add_action('admin_menu', 'irantheme_adjust_the_wp_menu', 999);


// Remove scheduler notification (Woocommerce, wp-corn, etc...)
add_filter('action_scheduler_pastdue_actions_check_pre', '__return_false');


/**
 * Check honeypot field for spam detection in comments
 * @return string
 * @param string $commentdata comment data
 * @author iranianclever
 * @since v1.0.4
 * @version v1.0.4
 */
function irantheme_check_honeypot_spam($commentdata)
{
    if (isset($_POST['irantheme_hp']) && !empty($_POST['irantheme_hp'])) {
        wp_die(
            __('خطا: دیدگاه شما به عنوان اسپم شناسایی شد.', IRANTHEME_TEXTDOMAIN),
            __('دیدگاه اسپم', IRANTHEME_TEXTDOMAIN),
            array('response' => 403)
        );
    }
    return $commentdata;
}
add_filter('preprocess_comment', 'irantheme_check_honeypot_spam', 99);


/**
 * Check time trap for spam detection in comments
 * @return string
 * @param string $commentdata comment data
 * @author iranianclever
 * @since v1.0.4
 * @version v1.0.4
 */
function irantheme_check_time_trap($commentdata)
{
    if (isset($_POST['irantheme_time'])) {
        $time_diff = time() - intval($_POST['irantheme_time']);
        if ($time_diff < 5 && !is_admin() && !wp_doing_ajax() && !wp_is_json_request()) {
            wp_die(
                __('خطا: فرم بسیار سریع ارسال شد. لطفاً دوباره تلاش کنید.', IRANTHEME_TEXTDOMAIN),
                __('دیدگاه اسپم', IRANTHEME_TEXTDOMAIN),
                array('response' => 403)
            );
        }
    }
    return $commentdata;
}
add_filter('preprocess_comment', 'irantheme_check_time_trap', 99);


/**
 * Check comment content for spam keywords and excessive links
 * @return string
 * @param string $commentdata comment data
 * @author iranianclever
 * @since v1.0.4
 * @version v1.0.4
 */
function irantheme_check_comment_content($commentdata)
{
    if (empty($commentdata['comment_content'])) {
        return $commentdata;
    }

    $comment_content = $commentdata['comment_content'];
    $spam_words = array('viagra', 'casino', 'porn', 'loan', 'bitcoin', 'فروش فالوور', 'خرید لایک');

    foreach ($spam_words as $word) {
        if (stripos($comment_content, $word) !== false) {
            wp_die(
                __('خطا: دیدگاه شما حاوی کلمات غیرمجاز است.', IRANTHEME_TEXTDOMAIN),
                __('دیدگاه اسپم', IRANTHEME_TEXTDOMAIN),
                array('response' => 403)
            );
        }
    }

    $link_count = preg_match_all('/<a\s[^>]*href/i', $comment_content);
    if ($link_count > 2) {
        wp_die(
            __('خطا: تعداد لینک‌های دیدگاه شما بیش از حد مجاز است.', IRANTHEME_TEXTDOMAIN),
            __('دیدگاه اسپم', IRANTHEME_TEXTDOMAIN),
            array('response' => 403)
        );
    }

    return $commentdata;
}
add_filter('preprocess_comment', 'irantheme_check_comment_content', 99);


/**
 * Force all avatars to local user.webp — covers all code paths
 * @return string
 * @param string $avatar avatar HTML
 * @param mixed $id_or_email user ID or email
 * @param int $size avatar size
 * @param string $default default avatar URL
 * @param string $alt alt text for the avatar
 * @author iranianclever
 * @since v1.0.4
 * @version v1.0.4
 */
add_filter('get_avatar', function ($avatar, $id_or_email, $size, $default, $alt) {
    // $is_local = strpos(home_url(), 'localhost') !== false
    //     || (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] === '127.0.0.1');

    // if (!$is_local) {
    //     return $avatar;
    // }

    $url = IRANTHEME_URI . '/assets/images/user.webp';
    return '<img alt="' . esc_attr($alt ?: 'avatar') . '" src="' . esc_url($url) . '" class="avatar avatar-' . (int) $size . ' photo" height="' . (int) $size . '" width="' . (int) $size . '" />';
}, 10, 5);

add_filter('get_avatar_url', function ($url, $id_or_email, $args) {
    return IRANTHEME_URI . '/assets/images/user.webp';
}, 10, 3);

add_filter('pre_get_avatar_data', function ($args, $id_or_email) {
    $args['url']          = IRANTHEME_URI . '/assets/images/user.webp';
    $args['found_avatar'] = true;
    return $args;
}, 10, 2);
