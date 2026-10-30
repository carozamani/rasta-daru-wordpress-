<?php

use Irantheme_Dev\Irantheme_Color_Static;
use Irantheme_Dev\Irantheme_Common_Static;
use Irantheme_Dev\Irantheme_Section_Service;
use Irantheme_Dev\Irantheme_Section_Utils;

// Header ------------------------------------------------------------

get_header();
$attachment_id = get_option('irantheme_blog_irregular_banner_bg');
$background_url = wp_get_attachment_image_url($attachment_id, 'full');
$banner_type = get_option('irantheme_blog_irregular_banner_type');
$heading_checkbox = get_option('irantheme_blog_irregular_banner_custom_heading_checkbox');
$title = get_option('irantheme_blog_irregular_banner_custom_title');
$description = get_option('irantheme_blog_irregular_banner_custom_description');
if ($banner_type == 'technological') {
    Irantheme_Archives::get_header_technological($background_url, $heading_checkbox, $title, $description);
} elseif ($banner_type == 'luxurious') {
    Irantheme_Archives::get_header_luxurious($background_url, $heading_checkbox, $title, $description);
} elseif ($banner_type == 'advanced') {
    Irantheme_Archives::get_header_advanced($background_url, $heading_checkbox, $title, $description);
}

// Get base color
$base_color = get_option('irantheme_blog_base_color_select');
// Set base color class
$base_color_class = Irantheme_Common_Static::get_base_color_class($base_color);

// Content ------------------------------------------------------------
// Get mode style layout
$mode_style = get_option('irantheme_blog_content_style');
// Get col column
$col_column = get_option('irantheme_blog_content_column');
// Get post height
$post_height = get_option('irantheme_blog_content_post_height');

// Check style mode
$wrapper = '';
$holder_item = '';
$masonry_sizer = '';
if ($mode_style == 'grid') {
    $wrapper = 'row';
    $holder_item = Irantheme_Section_Service::get_bootstrap_class($col_column, '12');
} elseif ($mode_style == 'masonry') {
    $wrapper = 'irantheme-grid-masonry-layout';
    $holder_item = 'irantheme-grid-masonry-layout-item';
    $masonry_sizer = 'width:' . Irantheme_Section_Service::get_column_width_percentage($col_column) . '%;';
    $post_height = 'auto';
}

// Get posts per page
$posts_per_page = get_option('irantheme_blog_content_post_number');

// Get sidebar checkbox
$sidebar_checkbox = get_option('irantheme_blog_content_sidebar_checkbox');

// Get sidebar select
$sidebar_select = get_option('irantheme_blog_content_sidebar_select');

// Get sidebar position
$sidebar_position = get_option('irantheme_blog_content_sidebar_position');

$content_class = 'col-lg-12';
$sidebar_class = '';
if ($sidebar_checkbox == 'on') {
    $content_class = 'col-lg-9';
    $sidebar_class = 'col-lg-3';
}

$row_class = '';
if ($sidebar_position == 'left') {
    $row_class = 'flex-row-reverse';
} else if ($sidebar_position == 'right') {
    $row_class = 'flex-row';
}

// Get post category_toggle
$post_category_toggle_checkbox = get_option('irantheme_blog_content_post_category_toggle_checkbox');

// Get post post details
$post_details_checkbox_multi = get_option('irantheme_blog_content_post_details_checkbox_multi');
$post_details_checkbox_multi = explode(',', $post_details_checkbox_multi);
// Get details color
$details_color = get_option('irantheme_blog_visual_color_select');

// Get border radius range
$border_radius_range = get_option('irantheme_blog_visual_border_radius_range');
$border_radius_css = Irantheme_Section_Utils::get_border_radius_css($border_radius_range);

// Get background options
$background_image_data = get_option('irantheme_blog_bg_image');
$background_color_data = get_option('irantheme_blog_bg_color');
$background_parallax_checkbox = get_option('irantheme_blog_bg_parallax_checkbox');
$background_color_css = Irantheme_Section_Utils::get_background_color_css($background_color_data);
$background_image_css = Irantheme_Section_Utils::get_background_image_css($background_image_data);
$background_parallax_attr = Irantheme_Section_Utils::get_background_parallax_attr($background_parallax_checkbox, $background_image_data);
?>

<section id="irantheme-archive" class="irantheme-archive irantheme-background-jarallax <?php echo esc_attr($base_color_class); ?>" style="<?php echo esc_attr($background_color_css); ?><?php echo esc_attr($background_image_css); ?>" <?php echo $background_parallax_attr; ?>>
    <?php
    // Get background mask checkbox
    $mask_checkbox = get_option('irantheme_blog_bg_mask_checkbox');
    // Get background mask color
    $mask_color = get_option('irantheme_blog_bg_mask_color');
    // Get background mask range
    $mask_range = get_option('irantheme_blog_bg_mask_range');
    // Get showcase mask --------------------------------------------------------------
    Irantheme_Section_Utils::get_background_mask_tag_html($mask_checkbox, $mask_color, $mask_range);
    ?>
    <!-- Container -->
    <div class="irantheme-container-inner irantheme-content-on-mask">
        <div class="row <?php echo esc_attr($row_class); ?>">

            <?php if ($sidebar_checkbox == 'on') : ?>
                <!-- Sidebar -->
                <div class="<?php echo esc_attr($sidebar_class); ?> irantheme-row-sidebar">
                    <div class="irantheme-sidebar-holder <?php echo esc_attr($base_color_class); ?>">
                        <?php dynamic_sidebar($sidebar_select); ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Content -->
            <div class="<?php echo esc_attr($content_class); ?> irantheme-row-content">
                <?php
                if (is_author()) {
                ?>
                    <!-- Author profile -->
                    <div class="irantheme-author-profile <?php echo esc_attr($base_color_class); ?>" style="<?php echo esc_attr($border_radius_css); ?>">
                        <div class="irantheme-author-profile-box">
                            <div class="irantheme-author-profile-box-content">
                                <?php
                                $author = get_user_by('slug', get_query_var('author_name'));
                                $date_raw = get_the_author_meta('user_registered', $author->ID);
                                $date_str = explode(' ', $date_raw);
                                $date_split = explode('-', $date_str[0]);
                                $date_year = $date_split[0];
                                $date = date('Y') - $date_year;
                                $posts_number = count_user_posts($author->ID);
                                ?>
                                <div class="irantheme-author-profile-box-holder">
                                    <!-- Author profile avatar -->
                                    <div class="irantheme-author-profile-box-avatar">
                                        <?php echo get_avatar($author->ID, '150'); ?>
                                    </div>
                                    <!-- Author profile info -->
                                    <div class="irantheme-author-profile-box-info">
                                        <h3><?php echo esc_html($author->display_name); ?><span class="<?php echo esc_attr(Irantheme_Color_Static::get_class_color_mode('color', null, Irantheme_Color_Static::get_hex_color($details_color))) ?>">(<?php echo esc_html($posts_number); ?>)</span></h3>
                                        <cite><?php echo esc_html($author->nickname); ?><span>
                                                <?php
                                                if ($date < 1) {
                                                    echo esc_html('کم تر از 1 سال عضویت در ' . get_bloginfo('name'));
                                                } else {
                                                    echo esc_html('بیشتر از ' . $date . 'سال عضویت در ' . get_bloginfo('name'));
                                                }
                                                ?>
                                            </span></cite>
                                        <p><?php echo esc_html($author->description); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
                <?php
                $template_layout = get_option('irantheme_blog_content_template');
                if ($template_layout == 'contemporary') {
                    Irantheme_Archives::get_contemporary_archive($base_color, $wrapper, $holder_item, $masonry_sizer, $posts_per_page, $post_height, $post_category_toggle_checkbox, $post_details_checkbox_multi, $details_color, $border_radius_css);
                } elseif ($template_layout == 'technological') {
                    Irantheme_Archives::get_technological_archive($base_color, $wrapper, $holder_item, $masonry_sizer, $posts_per_page, $post_height, $post_category_toggle_checkbox, $post_details_checkbox_multi, $details_color, $border_radius_css);
                } elseif ($template_layout == 'advanced') {
                    Irantheme_Archives::get_advanced_archive($base_color, $wrapper, $holder_item, $masonry_sizer, $posts_per_page, $post_height, $post_category_toggle_checkbox, $post_details_checkbox_multi, $details_color, $border_radius_css);
                } elseif ($template_layout == 'luxurious') {
                    Irantheme_Archives::get_luxurious_archive($base_color, $wrapper, $holder_item, $masonry_sizer, $posts_per_page, $post_height, $post_category_toggle_checkbox, $post_details_checkbox_multi, $details_color, $border_radius_css);
                } elseif ($template_layout == 'traditional') {
                    Irantheme_Archives::get_traditional_archive($base_color, $wrapper, $holder_item, $masonry_sizer, $posts_per_page, $post_height, $post_category_toggle_checkbox, $post_details_checkbox_multi, $details_color, $border_radius_css);
                }
                ?>
            </div>
        </div>
    </div>
</section>

<?php
// Footer ------------------------------------------------------------
Irantheme_Footer_Generator::display_footer();
get_footer();
