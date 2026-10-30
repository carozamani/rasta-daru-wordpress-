<?php

use Irantheme_Dev\Irantheme_Color_Static;
use Irantheme_Dev\Irantheme_Header_Main;
use Irantheme_Dev\Irantheme_Page_Builder;
use Irantheme_Dev\Irantheme_Page_Builder_Ajax;


if (!function_exists('irantheme_get_meta_data_page_config_by_page_id')) {
    /**
     * Return meta key for page config
     * 
     * @return string
     * @see v1.0.4
     */
    function irantheme_get_meta_data_page_config_by_page_id()
    {
        return 'irantheme_page_config';
    }
}


if (!function_exists('irantheme_get_meta_data_header_by_page_id')) {
    /**
     * Return meta key for page header
     * 
     * @return string
     * @see v1.0.4
     */
    function irantheme_get_meta_data_header_by_page_id()
    {
        return 'irantheme_page_header';
    }
}


if (!function_exists('irantheme_get_meta_data_sections_by_page_id')) {
    /**
     * Return meta key for page sections
     * 
     * @return string
     * @see v1.0.4
     */
    function irantheme_get_meta_data_sections_by_page_id()
    {
        return 'irantheme_page_sections';
    }
}


if (!function_exists('irantheme_get_meta_data_html_by_page_id')) {
    /**
     * Return meta key for page html (deprecated)
     * 
     * @return string
     * @see v1.0.4
     */
    function irantheme_get_meta_data_html_by_page_id()
    {
        return 'irantheme_page_html';
    }
}


if (!function_exists('irantheme_get_header_option')) {
    /**
     * Return visual option name
     * @param string,string
     * @return string
     * @see v1.0
     */
    function irantheme_get_header_option($format_name, $page_id = null)
    {
        $data_page = $page_id;
        if ($page_id == null) {
            // Get global query info
            global $wp_query;
            if (is_front_page()) {
                $post_id = get_option('page_on_front');
            } else if (is_home()) {
                $post_id = get_option('page_for_posts');
            } else {
                // Get current page id
                $post_id = $wp_query->get_queried_object_id();
            }
            // Get section page
            $data_page = $post_id;
        }

        $page_header_fields = Irantheme_Page_Builder_Ajax::get_page_settings($data_page, 'header', 'header_fields');

        // Get page is customizable (safe when header_fields is empty)
        $header_customizable = $page_header_fields['irantheme_header_customizable_checkbox'] ?? 'off';

        $option_value = '';

        // Check header customizable
        if ($header_customizable == 'on') {
            // Get customize header option name (safe access with empty-string fallback)
            $option_value = $page_header_fields[$format_name] ?? '';
        } else {
            // Get option from theme settings
            $option_value = get_option($format_name);
        }
        return $option_value;
    }
}


if (!function_exists('irantheme_get_user_role')) {
    /**
     * User role
     * @return string
     * @param string $user_id
     */
    function irantheme_get_user_role($user_id)
    {
        $user = get_userdata($user_id);
        // Check user exists and has roles
        if (!$user || empty($user->roles) || !isset($user->roles[0])) {
            return '<span class="user-role-text">کاربر عادی</span><i class="iconsax irantheme-color-green" icon-name="user-1"></i>';
        }
        $user_roles = $user->roles[0];
        switch ($user_roles) {
            case 'administrator':
            case 'admin':
                return '<span class="user-role-text">مدیر</span><i class="iconsax irantheme-color-yellow" icon-name="crown-1"></i>';
                break;
            case 'editor':
                return '<span class="user-role-text">ویرایشگر</span><i class="iconsax irantheme-color-red" icon-name="teacher"></i>';
                break;
            case 'author':
                return '<span class="user-role-text">نویسنده</span><i class="iconsax irantheme-color-red" icon-name="edit-1"></i>';
                break;
            case 'contributor':
                return '<span class="user-role-text">مشارکت کننده</span><i class="iconsax irantheme-color-orange" icon-name="group"></i>';
                break;
            case 'subscriber':
                return '<span class="user-role-text">مشترک</span><i class="iconsax irantheme-color-blue" icon-name="user-1-square"></i>';
                break;
            case 'customer':
                return '<span class="user-role-text">خریدار</span><i class="iconsax irantheme-color-blue" icon-name="user-2-add"></i>';
                break;
            case 'shop_manager':
                return '<span class="user-role-text">مدیر فروشگاه</span><i class="iconsax irantheme-color-orange" icon-name="user-octagon"></i>';
                break;
            default:
                return '<span class="user-role-text">کاربر عادی</span><i class="iconsax irantheme-color-green" icon-name="user-1"></i>';
                break;
        }
    }
}


if (!function_exists('irantheme_preload_styles')) {
    /**
     * Add preloaded tag style to wp head (Head tag)
     * @param void
     * @return void
     * @see v1.0
     */
    function irantheme_preload_styles($page_id = null)
    {
        $page_id_temp = $page_id;
        if ($page_id_temp == null) {
            $page_id_temp = Irantheme_Page_Builder::get_current_page_id();
        }
        // Get radius option compatible with convenient page --------------------------------------------------------
        $border_radius_range = '0';
        if (is_home() || is_archive() || is_single()) {
            $border_radius_range = get_option('irantheme_blog_visual_border_radius_range');
        } else if (is_woocommerce_activated() && is_shop()) {
            $border_radius_range = get_option('irantheme_wc_archive_border_radius_range');
        }

        // Check page
        if (is_page() || is_front_page()) {
            // Get page meta data json
            $page_config_fields = Irantheme_Page_Builder_Ajax::get_page_settings($page_id_temp, 'page_config', 'page_config_fields');
            // Get page is customizable
            $page_customizable = $page_config_fields['irantheme_page_customizable_checkbox'] ?? 'off';
            if ($page_customizable == 'on') {
                // Get font family --------------------------------------------------------
                $font_main_select = $page_config_fields['irantheme_font_main_select'];
                // Get containers options ranges --------------------------------------------------------
                $container_outer_width = $page_config_fields['irantheme_outer_container'];
                $container_inner_width = $page_config_fields['irantheme_inner_container'];
            } else {
                // Get font family --------------------------------------------------------
                $font_main_select = get_option('irantheme_font_main_select');
                // Get containers options ranges --------------------------------------------------------
                $container_outer_width = get_option('irantheme_outer_container');
                $container_inner_width = get_option('irantheme_inner_container');
            }
        } elseif (is_woocommerce_activated() && is_product()) {
            // Get font family --------------------------------------------------------
            $font_main_select = get_option('irantheme_font_main_select');
            // Get containers options ranges of single product page --------------------------------------------------------
            $container_outer_width = get_option('irantheme_single_product_outer_container');
            $container_inner_width = get_option('irantheme_single_product_inner_container');
        } else {
            // Get font family --------------------------------------------------------
            $font_main_select = get_option('irantheme_font_main_select');
            // Get containers options ranges --------------------------------------------------------
            $container_outer_width = get_option('irantheme_outer_container');
            $container_inner_width = get_option('irantheme_inner_container');
        }

        $font_main_text = '';
        if ($font_main_select == 'iransansx') {
            $font_main_text = 'IRANSansX';
        } else if ($font_main_select == 'iranyekanx') {
            $font_main_text = 'IRANYekanX';
        } else if ($font_main_select == 'vazirmatn') {
            $font_main_text = 'Vazirmatn';
        } else if ($font_main_select == 'sahel') {
            $font_main_text = 'Sahel';
        } else if ($font_main_select == 'shabnam') {
            $font_main_text = 'Shabnam';
        } else if ($font_main_select == 'tanha') {
            $font_main_text = 'Tanha';
        }

?>
        <style id="irantheme-internal-style" type="text/css">
            /* Set fonts -------------------------------------------------------- */
            body {
                font-family: <?php echo esc_attr($font_main_text); ?>;
            }

            .tooltip {
                font-family: <?php echo esc_attr($font_main_text); ?>;
            }

            .irantheme-input-form-fields input[type='submit'],
            .irantheme-input-form-fields input[type='reset'],
            .irantheme-input-form-fields input[type='button'],
            .irantheme-input-form-fields button {
                font-family: <?php echo esc_attr($font_main_text); ?>;
            }

            input[type='text'],
            input[type='password'],
            input[type='email'],
            input[type='tel'],
            input[type='search'],
            input[type='url'],
            select,
            textarea,
            .irantheme-input-text,
            .irantheme-input-url,
            .irantheme-input-email,
            .irantheme-input-textarea,
            .irantheme-input-form-fields input[type='text'],
            .irantheme-input-form-fields input[type='password'],
            .irantheme-input-form-fields input[type='email'],
            .irantheme-input-form-fields input[type='tel'],
            .irantheme-input-form-fields input[type='search'],
            .irantheme-input-form-fields input[type='url'],
            .irantheme-input-form-fields select,
            .irantheme-input-form-fields textarea,
            .irantheme-input-form-fields .irantheme-input-text,
            .irantheme-input-form-fields .irantheme-input-url,
            .irantheme-input-form-fields .irantheme-input-email,
            .irantheme-input-form-fields .irantheme-input-textarea {
                font-family: <?php echo esc_attr($font_main_text); ?>;
            }

            label,
            .irantheme-input-label,
            .irantheme-input-form-fields label,
            .irantheme-input-form-fields .irantheme-input-label {
                font-family: <?php echo esc_attr($font_main_text); ?>;
            }

            /* Containers -------------------------------------------------------- */
            .irantheme-container-outer {
                width: <?php echo $container_outer_width . '%'; ?>;
            }

            .irantheme-container-inner {
                width: <?php echo $container_inner_width . '%'; ?>;
            }

            /* Header transparency -------------------------------------------------------- */
            .irantheme-header-transparency {
                <?php echo Irantheme_Header_Main::irantheme_get_header_transparency_css_option(); ?>
            }

            /* Topbar transparency -------------------------------------------------------- */
            .irantheme-topbar-transparency {
                <?php echo Irantheme_Header_Main::irantheme_get_topbar_transparency_css_option(); ?>
            }

            /* Set border radius page -------------------------------------------------------- */
            .irantheme-border-radius-pages {
                border-radius: <?php echo $border_radius_range . 'rem !important;' ?>;
            }

            <?php
            $colors_inputs = array(
                'main' => Irantheme_Color_Static::get_colors_options_list('irantheme_main_color_select'),
                'sub' => Irantheme_Color_Static::get_colors_options_list('irantheme_sub_color_select'),
                'primary' => Irantheme_Color_Static::get_colors_options_list('irantheme_primary_color_select'),
                'secondary' => Irantheme_Color_Static::get_colors_options_list('irantheme_secondary_color_select'),
                'tertiary' => Irantheme_Color_Static::get_colors_options_list('irantheme_tertiary_color_select'),
            );
            foreach ($colors_inputs as $key => $value) :
            ?>

            /* Color <?php echo $key; ?> -------------------------------------------------------- */
            .irantheme-color-<?php echo $key; ?> {
                color: <?php echo '#' . $value['hex'];  ?> !important;
            }

            /* Color <?php echo $key; ?> Before -------------------------------------------------------- */
            .irantheme-color-<?php echo $key; ?>-before::before {
                color: <?php echo '#' . $value['hex'];  ?> !important;
            }

            /* Color <?php echo $key; ?> After -------------------------------------------------------- */
            .irantheme-color-<?php echo $key; ?>-after::after {
                color: <?php echo '#' . $value['hex'];  ?> !important;
            }

            /* Background <?php echo $key; ?> Before -------------------------------------------------------- */
            .irantheme-bg-<?php echo $key; ?>-before::before {
                background-color: <?php echo '#' . $value['hex'];  ?> !important;
            }

            /* Background <?php echo $key; ?> After -------------------------------------------------------- */
            .irantheme-bg-<?php echo $key; ?>-after::after {
                background-color: <?php echo '#' . $value['hex'];  ?> !important;
            }

            /* Color border */
            .irantheme-color-<?php echo $key; ?>-border {
                border-color: <?php echo '#' . $value['hex'];  ?> !important;
            }

            /* Color fill */
            .irantheme-color-<?php echo $key; ?>-fill {
                color: <?php echo esc_attr('#' . $value['hex_fill']); ?> !important;
            }

            /* Background fill */
            .irantheme-color-<?php echo $key; ?>-bg-fill {
                background-color: <?php echo esc_attr('#' . $value['hex_fill']); ?> !important;
            }

            /* Background trans */
            .irantheme-color-<?php echo $key; ?>-bg-glass {
                background-color: <?php echo esc_attr($value['rgb_trans']); ?> !important;
            }

            /* Background color */
            .irantheme-color-<?php echo $key; ?>-bg {
                background-color: <?php echo '#' . $value['hex'];  ?> !important;
            }

            /* Color hover */
            .irantheme-color-<?php echo $key; ?>-hover:hover {
                color: <?php echo '#' . $value['hex'];  ?> !important;
            }

            /* Color i hover */
            .irantheme-color-<?php echo $key; ?>-hover-i:hover i {
                color: <?php echo '#' . $value['hex'];  ?> !important;
            }

            /* Color i hover */
            .irantheme-color-<?php echo $key; ?>-bg-hover-i:hover i {
                background: <?php echo '#' . $value['hex'];  ?> !important;
            }

            /* Color i hover */
            .irantheme-active-<?php echo $key; ?>-bg-i.active i {
                background: <?php echo '#' . $value['hex'];  ?> !important;
            }

            /* Color active class */
            .irantheme-active-color-<?php echo $key; ?>.active {
                color: <?php echo '#' . $value['hex'];  ?> !important;
            }

            /* Color active class */
            .irantheme-active-color-<?php echo $key; ?>-i.active i {
                color: <?php echo '#' . $value['hex'];  ?> !important;
            }

            /* Background active class */
            .irantheme-active-bg-<?php echo $key; ?>-before.active::before {
                background: <?php echo '#' . $value['hex'];  ?> !important;
            }

            /* Background class */
            .irantheme-bg-<?php echo $key; ?>-hover-before:hover::before {
                background: <?php echo '#' . $value['hex'];  ?> !important;
            }

            /* Background active class */
            .irantheme-active-bg-<?php echo $key; ?>-after.active::after {
                background: <?php echo '#' . $value['hex'];  ?> !important;
            }

            /* Background class */
            .irantheme-bg-<?php echo $key; ?>-hover-after:hover::after {
                background: <?php echo '#' . $value['hex'];  ?> !important;
            }

            /* Color background hover */
            .irantheme-color-<?php echo $key; ?>-bg-hover:hover {
                background-color: <?php echo '#' . $value['hex'];  ?> !important;
            }

            .irantheme-active-bg-<?php echo $key; ?>.active {
                background-color: <?php echo '#' . $value['hex'];  ?> !important;
            }

            /* Button styles -------------------------------------------------------- */

            .irantheme-btn-bg-<?php echo $key; ?> {
                background-color: <?php echo '#' . $value['hex'];  ?> !important;
                color: white !important;
            }

            .irantheme-btn-bg-<?php echo $key; ?>:hover {
                background-color: <?php echo '#' . $value['hex_fill'];  ?> !important;
                color: white !important;
            }

            <?php
            endforeach;
            ?>
            /* Colors -------------------------------------------------------- */

            /* Blue color */
            .irantheme-color-blue {
                color: #346DF9;
            }

            /* Red color */
            .irantheme-color-red {
                color: #e74c3c;
            }

            /* Orange color */
            .irantheme-color-orange {
                color: #e67e22;
            }

            /* Green color */
            .irantheme-color-green {
                color: #2ecc71;
            }

            /* Yellow color */
            .irantheme-color-yellow {
                color: #f1c430;
            }

            /* Yellow color background */
            .irantheme-bg-color-yellow {
                background-color: #f1c430;
            }

            /* Purple color */
            .irantheme-color-purple {
                color: #9b59b6;
            }

            /* White color */
            .irantheme-color-white {
                color: #ffffff;
            }

            /* Gray color background */
            .irantheme-bg-color-gray {
                background-color: #adb5bd;
            }

            /* Bg white color */
            .irantheme-bg-white {
                background-color: #ffffff;
            }

            /* Dark color */
            .irantheme-color-dark {
                color: #292a2b;
            }

            /* Bg dark color */
            .irantheme-bg-dark {
                background-color: #1F2229;
            }

            /* White fill -------------------------------------------------------- */
            .irantheme-fill-white,
            .irantheme-fill-white svg,
            .irantheme-fill-white path {
                fill: #ffffff;
                color: #ffffff;
            }

            /* Dark fill -------------------------------------------------------- */
            .irantheme-fill-dark,
            .irantheme-fill-dark svg,
            .irantheme-fill-dark path {
                fill: #292a2b;
                color: #292a2b;
            }

            /* Color primary showcase content (Text editor) -------------------------------------------------------- */
            <?php
            $main_option_colors = Irantheme_Color_Static::get_colors_options_list('irantheme_main_color_select');
            ?>.irantheme-tinymce-content a:not(.irantheme-btn-void) {
                color: <?php echo '#' . $main_option_colors['hex'];  ?>;
            }

            .irantheme-tinymce-content a:not(.irantheme-btn-void):hover {
                color: <?php echo '#' . $main_option_colors['hex_fill'];  ?>;
            }

            .irantheme-tinymce-content blockquote {
                border-right-color: <?php echo '#' . $main_option_colors['hex'];  ?>;
            }
        </style>
    <?php
    }
}


if (!function_exists('irantheme_custom_breadcrumbs')) {
    /**
     * @return void
     * Custom breadcrumbs
     */
    // Breadcrumbs
    function irantheme_custom_breadcrumbs()
    {

        // Settings
        $breadcrums_id = 'breadcrumbs';
        $breadcrums_class = 'breadcrumbs';
        $home_title = 'خانه';

        // If you have any custom post types with custom taxonomies, put the taxonomy name below (e.g. product_cat)
        $custom_taxonomy = 'product_cat';

        // Get the query & post information
        global $post, $wp_query;

        // Do not display on the homepage
        if (!is_front_page()) {

            // Build the breadcrums
            echo '<div id="' . $breadcrums_id . '" class="' . $breadcrums_class . '">';
            echo '<ul>';

            // Home page
            echo '<li class="item-home"><a class="bread-link bread-home" href="' . get_home_url() . '" title="' . $home_title . '">' . $home_title . '</a></li>';

            if (is_archive() && !is_tax() && !is_category() && !is_tag()) {

                if (is_author()) {

                    // Auhor archive

                    // Get the author information
                    global $author;
                    $userdata = get_userdata($author);

                    // Display author name
                    echo '<li class="item-current item-current-' . $userdata->user_nicename . '"><span class="bread-current bread-current-' . $userdata->user_nicename . '" title="' . $userdata->display_name . '">' . 'نویسنده: ' . $userdata->display_name . '</span></li>';
                } else {
                    echo '<li class="item-current item-archive"><span class="bread-current bread-archive">' . post_type_archive_title('', false) . '</span></li>';
                }
            } else if (is_archive() && is_tax() && !is_category() && !is_tag()) {

                // If post is a custom post type
                $post_type = get_post_type();

                // If it is a custom post type display name and link
                if ($post_type != 'post') {

                    $post_type_object = get_post_type_object($post_type);
                    $post_type_archive = get_post_type_archive_link($post_type);

                    echo '<li class="item-cat item-custom-post-type-' . $post_type . '"><a class="bread-cat bread-custom-post-type-' . $post_type . '" href="' . $post_type_archive . '" title="' . $post_type_object->labels->name . '">' . $post_type_object->labels->name . '</a></li>';
                }

                $custom_tax_name = get_queried_object()->name;
                echo '<li class="item-current item-archive"><span class="bread-current bread-archive">' . $custom_tax_name . '</span></li>';
            } else if (is_single()) {

                // If post is a custom post type
                $post_type = get_post_type(get_queried_object_id());

                // If it is a custom post type display name and link
                if ($post_type != 'post') {

                    $post_type_object = get_post_type_object($post_type);
                    $post_type_archive = get_post_type_archive_link($post_type);

                    echo '<li class="item-cat item-custom-post-type-' . $post_type . '"><a class="bread-cat bread-custom-post-type-' . $post_type . '" href="' . $post_type_archive . '" title="' . $post_type_object->labels->name . '">' . $post_type_object->labels->name . '</a></li>';
                }

                // Get post category info
                $category = get_the_category(get_queried_object_id());

                if (!empty($category)) {

                    // Get last category post is in
                    $temp_category = array_values($category);
                    $last_category = end($temp_category);

                    // Get parent any categories and create array
                    $get_cat_parents = rtrim(get_category_parents($last_category->term_id, true, ','), ',');
                    $cat_parents = explode(',', $get_cat_parents);

                    // Loop through parent categories and store in variable $cat_display
                    $cat_display = '';
                    foreach ($cat_parents as $parents) {
                        $cat_display .= '<li class="item-cat">' . $parents . '</li>';
                    }
                }

                // If it's a custom post type within a custom taxonomy
                $taxonomy_exists = taxonomy_exists($custom_taxonomy);
                if (empty($last_category) && !empty($custom_taxonomy) && $taxonomy_exists) {

                    $taxonomy_terms = get_the_terms($post->ID, $custom_taxonomy);
                    $cat_id = $taxonomy_terms[0]->term_id;
                    $cat_nicename = $taxonomy_terms[0]->slug;
                    $cat_link = get_term_link($taxonomy_terms[0]->term_id, $custom_taxonomy);
                    $cat_name = $taxonomy_terms[0]->name;
                }

                // Check if the post is in a category
                if (!empty($last_category)) {
                    echo $cat_display;
                    echo '<li class="item-current item-' . $post->ID . '"><span class="bread-current bread-' . $post->ID . '" title="' . get_the_title(get_queried_object_id()) . '">' . get_the_title(get_queried_object_id()) . '</span></li>';

                    // Else if post is in a custom taxonomy
                } else if (!empty($cat_id)) {

                    echo '<li class="item-cat item-cat-' . $cat_id . ' item-cat-' . $cat_nicename . '"><a class="bread-cat bread-cat-' . $cat_id . ' bread-cat-' . $cat_nicename . '" href="' . $cat_link . '" title="' . $cat_name . '">' . $cat_name . '</a></li>';
                    echo '<li class="item-current item-' . $post->ID . '"><span class="bread-current bread-' . $post->ID . '" title="' . get_the_title(get_queried_object_id()) . '">' . get_the_title(get_queried_object_id()) . '</span></li>';
                } else {

                    echo '<li class="item-current item-' . $post->ID . '"><span class="bread-current bread-' . $post->ID . '" title="' . get_the_title(get_queried_object_id()) . '">' . get_the_title(get_queried_object_id()) . '</span></li>';
                }
            } else if (is_category()) {

                // Category page
                echo '<li class="item-current item-cat"><span class="bread-current bread-cat">' . single_cat_title('', false) . '</span></li>';
            } else if (is_page()) {

                // Standard page
                if ($post->post_parent) {

                    // If child page, get parents
                    $anc = get_post_ancestors($post->ID);

                    // Get parents in the right order
                    $anc = array_reverse($anc);

                    // Parent page loop
                    if (!isset($parents)) {
                        $parents = null;
                    }

                    foreach ($anc as $ancestor) {
                        $parents .= '<li class="item-parent item-parent-' . $ancestor . '"><a class="bread-parent bread-parent-' . $ancestor . '" href="' . get_permalink($ancestor) . '" title="' . get_the_title($ancestor) . '">' . get_the_title($ancestor) . '</a></li>';
                    }

                    // Display parent pages
                    echo $parents;

                    // Current page
                    echo '<li class="item-current item-' . $post->ID . '"><span title="' . get_the_title(get_queried_object_id()) . '"> ' . get_the_title(get_queried_object_id()) . '</span></li>';
                } else {

                    // Just display current page if not parents
                    echo '<li class="item-current item-' . $post->ID . '"><span class="bread-current bread-' . $post->ID . '"> ' . get_the_title(get_queried_object_id()) . '</span></li>';
                }
            } else if (is_tag()) {

                // Tag page

                // Get tag information
                $term_id = get_query_var('tag_id');
                $taxonomy = 'post_tag';
                $args = 'include=' . $term_id;
                $terms = get_terms($taxonomy, $args);
                $get_term_id = $terms[0]->term_id;
                $get_term_slug = $terms[0]->slug;
                $get_term_name = $terms[0]->name;

                // Display the tag name
                echo '<li class="item-current item-tag-' . $get_term_id . ' item-tag-' . $get_term_slug . '"><span class="bread-current bread-tag-' . $get_term_id . ' bread-tag-' . $get_term_slug . '">' . $get_term_name . '</span></li>';
            } elseif (is_day()) {

                // Day archive

                // Year link
                echo '<li class="item-year item-year-' . get_the_time('Y') . '"><a class="bread-year bread-year-' . get_the_time('Y') . '" href="' . get_year_link(get_the_time('Y')) . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</a></li>';

                // Month link
                echo '<li class="item-month item-month-' . get_the_time('m') . '"><a class="bread-month bread-month-' . get_the_time('m') . '" href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '" title="' . get_the_time('M') . '">' . get_the_time('M') . ' Archives</a></li>';

                // Day display
                echo '<li class="item-current item-' . get_the_time('j') . '"><span class="bread-current bread-' . get_the_time('j') . '"> ' . get_the_time('jS') . ' ' . get_the_time('M') . ' Archives</span></li>';
            } else if (is_month()) {

                // Month Archive

                // Year link
                echo '<li class="item-year item-year-' . get_the_time('Y') . '"><a class="bread-year bread-year-' . get_the_time('Y') . '" href="' . get_year_link(get_the_time('Y')) . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</a></li>';

                // Month display
                echo '<li class="item-month item-month-' . get_the_time('m') . '"><span class="bread-month bread-month-' . get_the_time('m') . '" title="' . get_the_time('M') . '">' . get_the_time('M') . ' Archives</span></li>';
            } else if (is_year()) {

                // Display year archive
                echo '<li class="item-current item-current-' . get_the_time('Y') . '"><span class="bread-current bread-current-' . get_the_time('Y') . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</span></li>';
            } else if (is_author()) {

                // Auhor archive

                // Get the author information
                global $author;
                $userdata = get_userdata($author);

                // Display author name
                echo '<li class="item-current item-current-' . $userdata->user_nicename . '"><span class="bread-current bread-current-' . $userdata->user_nicename . '" title="' . $userdata->display_name . '">' . 'نویسنده: ' . $userdata->display_name . '</span></li>';
            } else if (get_query_var('paged')) {

                // Paginated archives
                echo '<li class="item-current item-current-' . get_query_var('paged') . '"><span class="bread-current bread-current-' . get_query_var('paged') . '" title="Page ' . get_query_var('paged') . '">' . 'Page ' . get_query_var('paged') . '</span></li>';
            } else if (is_search()) {

                // Search results page
                echo '<li class="item-current item-current-' . get_search_query() . '"><span class="bread-current bread-current-' . get_search_query() . '" title="جستجو برای: ' . get_search_query() . '">جستجو برای: ' . get_search_query() . '</span></li>';
            } elseif (is_404()) {

                // 404 page
                echo '<li><span>' . 'صفحه مورد نظر پیدا نشد' . '</span></li>';
            }

            echo '</ul>';
            echo '</div>';
        }
    }
}




if (!function_exists('irantheme_display_empty_status')) {
    /**
     * Return the values of checkboxes in the settings panel as true/false
     * @param string
     * @return bool
     * @see v1.0
     */
    function irantheme_display_empty_status($icon_class, $heading, $description, $multiple_size = 1)
    {
        $icon_size = 5 * $multiple_size;
        $heading_size = 1.1 * $multiple_size;
        $description_size = 0.9 * $multiple_size;
    ?>
        <!-- Empty status -->
        <div class="irantheme-container-inner">
            <div class="irantheme-woocommerce-status-empty">
                <div class="irantheme-woocommerce-status-empty-icon">
                    <i class="fa-light fa-<?php echo esc_attr($icon_class); ?>" style="font-size:<?php echo esc_attr($icon_size); ?>rem;"></i>
                </div>
                <div class="irantheme-woocommerce-status-empty-content">
                    <h5 style="font-size:<?php echo esc_attr($heading_size); ?>rem;"><?php _e($heading); ?></h5>
                    <p style="font-size:<?php echo esc_attr($description_size); ?>rem;"><?php _e($description); ?></p>
                </div>
            </div>
        </div>
    <?php
    }
}


/**
 * Check current page is blog page
 * @return void
 * @param int
 * @author iranianclever
 * @since v1.0
 * @see v1.0
 */
function irantheme_is_blog($page_id = null)
{
    global $wp_query;
    // Get blog id page (home page)
    $blog_id = get_option('page_for_posts');
    // Check page_id parameter and validation
    if ($page_id != null && is_numeric($page_id)) {
        if ($page_id == $blog_id) {
            return true;
        }
    } else {
        $current_id = $wp_query->get_queried_object_id();
        // Check current page id without parameter
        if ($current_id == $blog_id) {
            return true;
        }
    }
    return false;
}

/**
 * Get user avatar URL with smart fallback for Blocked network conditions.
 *
 * Priority:
 * 1. Local user uploaded avatar (always shown regardless of WordPress setting)
 * 2. Admin theme setting for upload
 * 3. WordPress show_avatars setting (only for Gravatar)
 * 4. Gravatar with caching
 * 5. Fallback to local webp image
 *
 * @param int $user_id User ID.
 * @return string Avatar URL or fallback image.
 * @author M-MALEKZADEH77
 * @since 1.0.0
 * @version 2.6.0 - Local avatar now independent from show_avatars
 */
function irantheme_get_user_avatar_url($user_id)
{
    // STEP 1: Always check local avatar first (regardless of show_avatars)
    $upload_enabled = get_option('irantheme_wc_dashboard_user_avatar_checkbox', 'off');

    if ($upload_enabled === 'on') {
        $user_meta = get_user_meta($user_id, 'user_avatar', true);
        if ($user_meta && $user_meta !== 'defaultAvatar') {
            $image_url = wp_get_attachment_url($user_meta);
            if ($image_url) {
                return $image_url;
            }
        }
    }

    // STEP 2: If local upload is disabled or user has no local avatar,
    // then check WordPress global avatar setting
    if (!get_option('show_avatars', true)) {
        return IRANTHEME_URI . '/assets/images/user.webp';
    }

    // STEP 3: Use Gravatar with caching (only if upload is disabled)
    if ($upload_enabled !== 'on') {
        $gravatar_url   = get_avatar_url($user_id, ['size' => 150]);
        $fallback_image = IRANTHEME_URI . '/assets/images/user.webp';
        $cache_key      = 'gravatar_status_' . $user_id;

        // Check cached URL
        $cached = get_transient($cache_key);
        if (false !== $cached) {
            // Auto-migrate old cached default png to webp
            if ($cached === IRANTHEME_URI . '/assets/images/default-avatar.png') {
                set_transient($cache_key, $fallback_image, HOUR_IN_SECONDS);
                return $fallback_image;
            }
            return $cached;
        }

        // Test Gravatar availability with short timeout
        $response = wp_remote_get($gravatar_url, [
            'timeout'    => 0.5,
            'sslverify'  => false,
            'user-agent' => 'WordPress/' . get_bloginfo('version'),
        ]);

        if (!is_wp_error($response) && 200 === wp_remote_retrieve_response_code($response)) {
            set_transient($cache_key, $gravatar_url, WEEK_IN_SECONDS);
            return $gravatar_url;
        } else {
            set_transient($cache_key, $fallback_image, HOUR_IN_SECONDS);
            return $fallback_image;
        }
    }

    // Final fallback
    return IRANTHEME_URI . '/assets/images/user.webp';
}


/**
 * Return post-like html
 * @return void
 * @param int $post_id id of post type
 * @author iranianclever
 * @since v1.0
 * @version v1.0.3
 */
function irantheme_get_post_like($post_id)
{
    $user_id = get_current_user_id();

    $likeQuery = new WP_Query(array(
        'post_type' => 'like',
        'author' => $user_id,
        'meta_query' => array(
            array(
                'key' => 'irantheme_liked_meta_value_key',
                'compare' => '=',
                'value' => $post_id
            )
        )
    ));

    $totalLikesQuery = new WP_Query(array(
        'post_type' => 'like',
        'meta_query' => array(
            array(
                'key' => 'irantheme_liked_meta_value_key',
                'compare' => '=',
                'value' => $post_id
            )
        )
    ));
    ?>
    <!-- Single like button -->
    <button class="like-button" data-like="<?php if ($likeQuery->posts) {
                                                echo esc_attr($likeQuery->posts[0]->ID);
                                            } ?>" data-post="<?php echo $post_id; ?>" data-exists="<?php echo $likeQuery->posts ? 'yes' : 'no'; ?>">
        <i class="fa-light fa-heart like-heart-no"></i>
        <i class="fa-solid fa-heart like-heart-yes"></i>
        <span>
            <cite class="text-heart-yes">پسند شده</cite><cite class="text-heart-no">می پسندید ؟</cite><em class="like-count"><?php echo esc_html($totalLikesQuery->found_posts); ?></em>
        </span>
    </button>
<?php
}


/**
 * Return count of post liked
 * @return int
 * @param int $post_id id of post type
 * @author iranianclever
 * @since v1.0.3
 * @version v1.0.3
 */
function irantheme_get_post_like_count($post_id)
{
    $likeQuery = new WP_Query(array(
        'post_type' => 'like',
        'meta_query' => array(
            array(
                'key' => 'irantheme_liked_meta_value_key',
                'compare' => '=',
                'value' => $post_id
            )
        )
    ));
    return $likeQuery->found_posts;
}


/**
 * Allow custom icon-name attribute in wp_kses_post
 * 
 * This filter adds support for custom 'icon-name' attribute in <i> tags
 * when using wp_kses_post() function. Useful for displaying icons with
 * custom attributes in sale percentage badges and similar elements.
 * 
 * @since 1.0.2
 * @param array  $allowed_tags Array of allowed HTML tags and their attributes
 * @param string $context      The context for which to retrieve tags
 * @return array Modified array of allowed tags
 * @author M-MALEKZADEH77
 */
function irantheme_allow_icon_attribute($allowed_tags, $context)
{
    // Only apply to post context
    if ($context === 'post') {
        // Initialize i tag if not exists
        if (!isset($allowed_tags['i'])) {
            $allowed_tags['i'] = array();
        }

        // Add custom icon-name attribute for iconsax
        $allowed_tags['i']['icon-name'] = true;

        // Add common attributes for better compatibility
        $allowed_tags['i']['class'] = true;
        // $allowed_tags['i']['style'] = true;
        // $allowed_tags['i']['data-*'] = true;
        // $allowed_tags['i']['aria-hidden'] = true;
    }

    return $allowed_tags;
}
add_filter('wp_kses_allowed_html', 'irantheme_allow_icon_attribute', 10, 2);

/**
 * Return skeleton loader html
 * @return void
 * @param int $col_count تعداد ستون‌ها
 * @param string $type نوع اسکلتون (card, slider, product)
 * @author iranianclever
 * @since v1.0.3
 * @version v1.0.3
 */
function irantheme_get_skeleton_html($col_count, $type, $base_color_class)
{
    /* Child theme may replace the shell (StorePlus landing skeleton). */
    if (function_exists('storeplus_child_get_skeleton_html') && storeplus_child_get_skeleton_html($col_count, $type, $base_color_class)) {
        return;
    }

    if ($col_count == 'col-12') {
        $skeleton_count = 12;
    } else {
        switch ($col_count) {
            case 'col-8':
                $skeleton_count = 8;
                break;
            case 'col-6':
                $skeleton_count = 6;
                break;
            case 'col-5':
                $skeleton_count = 5;
                break;
            case 'col-4':
                $skeleton_count = 4;
                break;
            case 'col-3':
                $skeleton_count = 3;
                break;
            default:
                $skeleton_count = 4;
                break;
        }
    }

    if ($col_count == 'col-12') {
        $data_columns = 12;
    } else {
        switch ($col_count) {
            case 'col-8':
                $data_columns = 8;
                break;
            case 'col-6':
                $data_columns = 6;
                break;
            case 'col-5':
                $data_columns = 5;
                break;
            case 'col-4':
                $data_columns = 4;
                break;
            case 'col-3':
                $data_columns = 3;
                break;
            default:
                $data_columns = 4;
                break;
        }
    }

    // Check slider
    if ($type == 'slider') {
        $skeleton_count = 1;
    }
?>
    <div class="irantheme-skeleton-loader <?php echo $type == 'slider' ? 'skeleton-slider-mode' : ''; ?> <?php echo esc_attr($base_color_class); ?>">
        <div class="irantheme-skeleton-grid" data-columns="<?php echo $data_columns; ?>" data-type="<?php echo esc_attr($type); ?>">
            <?php if ($type == 'card' || $type == 'product'): ?>
                <?php for ($i = 0; $i < $skeleton_count; $i++): ?>
                    <div class="irantheme-skeleton-card">
                        <div class="irantheme-skeleton-image"></div>
                        <div class="irantheme-skeleton-line short"></div>
                        <div class="irantheme-skeleton-line medium"></div>
                        <div class="irantheme-skeleton-line long"></div>
                    </div>
                <?php endfor; ?>
            <?php elseif ($type == 'slider'): ?>
                <div class="irantheme-skeleton-slider">
                    <div class="irantheme-skeleton-image large"></div>
                    <div class="irantheme-skeleton-line long"></div>
                    <div class="irantheme-skeleton-line medium"></div>
                </div>
            <?php elseif ($type == 'text'): ?>
                <?php for ($i = 0; $i < $skeleton_count; $i++): ?>
                    <div class="irantheme-skeleton-card">
                        <div class="irantheme-skeleton-line short"></div>
                        <div class="irantheme-skeleton-line long"></div>
                    </div>
                <?php endfor; ?>
            <?php elseif ($type == 'image_medium'): ?>
                <?php for ($i = 0; $i < $skeleton_count; $i++): ?>
                    <div class="irantheme-skeleton-card no-shadow no-padding">
                        <div class="irantheme-skeleton-thumbnail medium radius"></div>
                        <div class="irantheme-skeleton-line short"></div>
                    </div>
                <?php endfor; ?>
            <?php elseif ($type == 'thumbnail'): ?>
                <?php for ($i = 0; $i < $skeleton_count; $i++): ?>
                    <div class="irantheme-skeleton-card no-shadow no-padding">
                        <div class="irantheme-skeleton-thumbnail thumbnail radius"></div>
                        <div class="irantheme-skeleton-line short"></div>
                    </div>
                <?php endfor; ?>
            <?php endif; ?>
        </div>
    </div>
<?php
}

/**
 * Function to detect media type based on file extension
 * @return string
 * @param string $url media url
 * @author iranianclever
 * @since v1.0.3
 * @version v1.0.3
 */
function irantheme_get_media_type($url)
{
    $path_info = pathinfo($url);
    $extension = isset($path_info['extension']) ? strtolower($path_info['extension']) : '';

    // Image extensions
    $image_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp', 'ico'];

    // Video extensions
    $video_extensions = ['mp4', 'webm', 'ogg', 'mov', 'avi', 'mkv', 'flv', 'wmv'];

    if (in_array($extension, $image_extensions)) {
        return 'image';
    } elseif (in_array($extension, $video_extensions)) {
        return 'video';
    } else {
        // Fallback: check if URL contains typical video/image patterns
        if (strpos($url, '.mp4') !== false || strpos($url, '.webm') !== false) {
            return 'video';
        }
        return 'image'; // default to image
    }
}
