<?php

use Irantheme_Dev\Irantheme_Common_Static;
use Irantheme_Dev\Irantheme_Section_Utils;

get_header();

global $post;
wp_reset_postdata(); // Reset any previous queries
$post_id = $post->ID;
$background_url = get_the_post_thumbnail_url($post_id, 'full');
$banner_type = get_option('irantheme_single_irregular_banner_type');
if ($banner_type == 'technological') {
    Irantheme_Archives::get_header_technological($background_url);
} elseif ($banner_type == 'luxurious') {
    Irantheme_Archives::get_header_luxurious($background_url);
} elseif ($banner_type == 'advanced') {
    Irantheme_Archives::get_header_advanced($background_url);
}

// Get sidebar checkbox
$sidebar_checkbox = get_option('irantheme_single_content_sidebar_checkbox');

// Get sidebar position
$sidebar_position = get_option('irantheme_single_content_sidebar_position');

// Get sidebar select
$sidebar_select = get_option('irantheme_single_content_sidebar_select');

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

// Get base color
$base_color = get_option('irantheme_blog_base_color_select');
// Set base color class
$base_color_class = Irantheme_Common_Static::get_base_color_class($base_color);

// Get background options
$background_image_data = get_option('irantheme_blog_bg_image');
$background_color_data = get_option('irantheme_blog_bg_color');
$background_parallax_checkbox = get_option('irantheme_blog_bg_parallax_checkbox');
$background_color_css = Irantheme_Section_Utils::get_background_color_css($background_color_data);
$background_image_css = Irantheme_Section_Utils::get_background_image_css($background_image_data);
$background_parallax_attr = Irantheme_Section_Utils::get_background_parallax_attr($background_parallax_checkbox, $background_image_data);
?>

<!-- Blog content -->
<section id="irantheme-single" class="irantheme-single irantheme-background-jarallax <?php echo esc_attr($base_color_class); ?>" style="<?php echo esc_attr($background_color_css); ?><?php echo esc_attr($background_image_css); ?>" <?php echo $background_parallax_attr; ?>>
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
                // Get template part of single
                get_template_part('/template-parts/posts/single', get_option('irantheme_single_content_template'));
                ?>
            </div>
        </div>
    </div>
</section>

<?php
// Footer
Irantheme_Footer_Generator::display_footer();
get_footer();
?>