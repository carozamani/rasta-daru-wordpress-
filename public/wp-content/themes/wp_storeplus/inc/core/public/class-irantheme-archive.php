<?php

use Irantheme_Dev\Irantheme_Color_Static;
use Irantheme_Dev\Irantheme_Common_Static;
use Irantheme_Dev\Irantheme_Page_Builder;
use Irantheme_Dev\Irantheme_Section_Service;
use Irantheme_Dev\Irantheme_Section_Utils;

if (!defined('ABSPATH')) exit;


if (!class_exists('Irantheme_Archives')) {

    /**
     * Holder of archives
     *
     * @package Wordpress
     * @subpackage irantheme
     * @since v1.0
     * @author iranianclever
     * @see v1.0
     *
     */

    class Irantheme_Archives
    {
        /**
         * Constructor
         * @return void
         * @param void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        function __construct() {}


        /**
         * Get blog template (Index)
         * @return void
         * @param void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_template_index()
        {
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

            // Get sidebar position
            $sidebar_position = get_option('irantheme_blog_content_sidebar_position');

            // Get sidebar select
            $sidebar_select = get_option('irantheme_blog_content_sidebar_select');

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

            // Get post category toggle
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

            <section id="irantheme-blog" class="irantheme-blog irantheme-background-jarallax <?php echo esc_attr($base_color_class); ?>" style="<?php echo esc_attr($background_color_css); ?><?php echo esc_attr($background_image_css); ?>" <?php echo $background_parallax_attr; ?>>
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
            // Footer
            Irantheme_Footer_Generator::display_footer();
            get_footer();
        }


        /**
         * Get WP_Query for archive and search pages
         * @return WP_Query
         * @param int $posts_per_page Number of posts per page
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_wp_query_archive($posts_per_page)
        {
            global $wp_query;

            // Use the main query for homepage and standard archives
            if (is_home() || is_front_page() || is_post_type_archive()) {
                return $wp_query;
            }

            $paged = get_query_var('paged') ? absint(get_query_var('paged')) : 1;

            $args = array(
                'paged' => $paged,
                'posts_per_page' => $posts_per_page,
                'ignore_sticky_posts' => true,
                'post_status' => 'publish'
            );

            // Handle search queries
            if (is_search()) {
                $args['s'] = sanitize_text_field(get_search_query());
                $args['posts_per_page'] = -1;
            }

            // Handle category/tag archives
            if (is_category() || is_tag() || is_tax()) {
                $term = get_queried_object();
                if ($term) {
                    $args['tax_query'] = array(
                        array(
                            'taxonomy' => $term->taxonomy,
                            'field' => 'term_id',
                            'terms' => $term->term_id,
                        )
                    );
                }
            }

            // Handle author archives
            if (is_author()) {
                $args['author'] = get_queried_object_id();
            }

            // Handle date archives
            if (is_date()) {
                if (is_year()) {
                    $args['year'] = get_query_var('year');
                } elseif (is_month()) {
                    $args['year'] = get_query_var('year');
                    $args['monthnum'] = get_query_var('monthnum');
                } elseif (is_day()) {
                    $args['year'] = get_query_var('year');
                    $args['monthnum'] = get_query_var('monthnum');
                    $args['day'] = get_query_var('day');
                }
            }

            return new WP_Query($args);
        }


        /**
         * Get wp query archive
         * @return void
         * @param void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_contemporary_archive($base_color_class, $wrapper, $holder_item, $masonry_sizer = '', $posts_per_page = 12, $post_height = 280, $post_category_toggle_checkbox = 'on', $post_details_checkbox_multi = array(), $details_color = 'off', $border_radius = '')
        {
            // Get wp query archive
            $query_posts = self::get_wp_query_archive($posts_per_page);

            // Set height post image
            $height_image_css = 'height:' . $post_height . 'px';
        ?>
            <?php if ($query_posts->have_posts()) : ?>
                <!-- Archive -->
                <div class="irantheme-archive-contemporary irantheme-wrapper-categories-trigger <?php echo esc_attr($base_color_class); ?>">
                    <?php if ($post_category_toggle_checkbox == 'on'): ?>
                        <?php Irantheme_Common_Static::get_categories_trigger('category', $details_color, $base_color_class); ?>
                    <?php endif; ?>
                    <div class="irantheme-categories-posts">
                        <!-- Wrapper post -->
                        <div class="<?php echo esc_attr($wrapper); ?>">
                            <div class="irantheme-grid-masonry-layout-sizer" style="<?php echo esc_attr($masonry_sizer); ?>"></div>
                            <?php while ($query_posts->have_posts()) : $query_posts->the_post();
                                // Get all category of posts
                                $categories = get_the_category();
                                $categories_output = '';

                                $categories_status_class = '';

                                // Get post format
                                $post_format = get_post_format();
                            ?>
                                <!-- Post -->
                                <div class="<?php echo esc_attr($holder_item); ?> post-holder" data-cate="<?php echo esc_attr(self::get_categories_ids_by_post(get_the_ID())); ?>" data-id="<?php echo get_the_ID(); ?>" style="<?php echo esc_attr($masonry_sizer); ?>">
                                    <article class="post" style="<?php echo esc_attr($border_radius); ?>">
                                        <?php if (in_array('thumbnail', $post_details_checkbox_multi)): ?>
                                            <!-- Post image -->
                                            <div class="post-image">
                                                <?php if (has_post_thumbnail()) : ?>
                                                    <img src="<?php echo get_the_post_thumbnail_url(); ?>" style="<?php echo esc_attr($height_image_css); ?>" alt="تصویر پروژه" />
                                                    <?php $categories_status_class = 'post-category-fixed'; ?>
                                                <?php endif; ?>
                                                <?php
                                                // Get post format icon
                                                echo self::get_class_icon_post_type($post_format);
                                                ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if (in_array('category', $post_details_checkbox_multi) && count($categories)) : ?>
                                            <!-- Post categories -->
                                            <ul class="post-categories <?php echo esc_attr($categories_status_class); ?>">
                                                <?php
                                                foreach ($categories as $category) {
                                                    $categories_output .= '<li><a href="' . get_category_link($category->term_id) . '">' . esc_html__($category->name) . '</a></li>';
                                                }
                                                echo trim($categories_output, '');
                                                ?>
                                            </ul>
                                        <?php endif; ?>
                                        <!-- Post content -->
                                        <div class="post-content">
                                            <!-- Post heading -->
                                            <div class="post-heading">
                                                <h2><a href="<?php echo get_permalink() ?>"><?php echo get_the_title(); ?></a></h2>
                                            </div>
                                            <?php if (in_array('excerpt', $post_details_checkbox_multi)): ?>
                                                <p><?php echo wp_trim_words(strip_shortcodes(get_the_excerpt()), 43); ?></p>
                                            <?php endif; ?>
                                            <?php if (in_array('read-more', $post_details_checkbox_multi)): ?>
                                                <!-- Read more -->
                                                <div class="post-read-more">
                                                    <a href="<?php echo get_the_permalink(); ?>" class="<?php echo esc_attr(Irantheme_Color_Static::get_class_color_mode('color', null, Irantheme_Color_Static::get_hex_color($details_color))) ?>"><?php echo esc_html('مشاهده بیشتر'); ?></a>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <!-- Post info -->
                                        <div class="post-info">
                                            <?php if (in_array('author', $post_details_checkbox_multi)): ?>
                                                <!-- Post info author -->
                                                <div class="post-info-author">
                                                    <div class="post-info-author-img">
                                                        <?php echo get_avatar(get_the_author_meta('ID'), 100); ?>
                                                    </div>
                                                    <div class="post-info-author-info">
                                                        <h4><?php echo get_the_author_posts_link(); ?></h4>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            <?php if (in_array('date', $post_details_checkbox_multi)): ?>
                                                <!-- Post info date -->
                                                <div class="post-info-date"><?php echo get_the_date('j F Y'); ?></div>
                                            <?php endif; ?>
                                            <?php if (in_array('like', $post_details_checkbox_multi)): ?>
                                                <?php
                                                // Counter of like
                                                $likeCount = new WP_Query(array(
                                                    'post_type' => 'like',
                                                    'meta_query' => array(
                                                        array(
                                                            'key' => 'liked_meta_value_key',
                                                            'compare' => '=',
                                                            'value' => get_the_ID()
                                                        )
                                                    )
                                                ));
                                                ?>
                                                <!-- Post info like -->
                                                <div class="post-info-like"><?php echo esc_html('پسند شده'); ?><span><?php echo esc_html($likeCount->found_posts); ?></span></div>
                                            <?php endif; ?>
                                            <?php if (in_array('comment', $post_details_checkbox_multi)): ?>
                                                <!-- Post info comment -->
                                                <div class="post-info-comment"><?php echo esc_html('دیدگاه'); ?><span><?php echo esc_html(get_comments_number()); ?></span></div>
                                            <?php endif; ?>
                                        </div>
                                        <?php if (has_post_thumbnail()) : ?>
                                            <!-- Post icons -->
                                            <div class="post-icons fullscreen-trigger">
                                                <i class="fa-light fa-expand"></i>
                                            </div>
                                        <?php endif; ?>
                                    </article>
                                </div>
                            <?php endwhile; ?>
                            <?php
                            // Restore original post data
                            wp_reset_postdata();
                            ?>
                        </div>

                        <?php if ($query_posts->found_posts > $query_posts->post_count) : ?>
                            <!-- Load more -->
                            <!-- <div class="load-more">
                                <button id="loading-projects" class="button-load">
                                    پروژه های بیشتر<i class="fa-light fa-spinner-scale"></i>
                                </button>
                            </div> -->
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif;
            // Get pagination links
            self::get_pagination($query_posts->max_num_pages, $base_color_class);
            wp_reset_query();
        }


        /**
         * Blog technological archive
         * @return void
         * @param string,string,string,int,string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_technological_archive($base_color_class, $wrapper, $holder_item, $masonry_sizer = '', $posts_per_page = 12, $post_height = 280, $post_category_toggle_checkbox = 'on', $post_details_checkbox_multi = array(), $details_color = 'off', $border_radius = '')
        {
            // Get wp query archive
            $query_posts = self::get_wp_query_archive($posts_per_page);

            // Set height post image
            $height_image_css = 'height:' . $post_height . 'px';
            ?>
            <!-- Archive -->
            <div class="irantheme-archive-technological irantheme-wrapper-categories-trigger <?php echo esc_attr($base_color_class); ?>">
                <?php
                if ($query_posts->have_posts()) : ?>
                    <?php if ($post_category_toggle_checkbox == 'on'): ?>
                        <?php Irantheme_Common_Static::get_categories_trigger('category', $details_color, $base_color_class); ?>
                    <?php endif; ?>
                    <div class="irantheme-categories-posts">
                        <!-- Grid masonry -->
                        <div class="<?php echo esc_attr($wrapper); ?>">
                            <div class="irantheme-grid-masonry-layout-sizer" style="<?php echo esc_attr($masonry_sizer); ?>"></div>
                            <?php while ($query_posts->have_posts()) : $query_posts->the_post();
                                // Get all category of posts
                                $categories = get_the_category();
                                $categories_output = '';
                                $categories_status_class = '';
                            ?>
                                <!-- Post -->
                                <div class="<?php echo esc_attr($holder_item); ?> post-holder" data-cate="<?php echo esc_attr(self::get_categories_ids_by_post(get_the_ID())); ?>" data-id="<?php echo get_the_ID(); ?>" style="<?php echo esc_attr($masonry_sizer); ?>">
                                    <?php
                                    // Get post format (type)
                                    $post_format = get_post_format() ?: 'standard';
                                    ?>
                                    <article class="post <?php
                                                            if ($post_format == 'quote') {
                                                                echo 'post-quote';
                                                            } elseif ($post_format == 'video') {
                                                                echo 'post-video';
                                                            } elseif ($post_format == 'audio') {
                                                                echo 'post-audio';
                                                            } elseif ($post_format == 'gallery') {
                                                                echo 'post-gallery';
                                                            }
                                                            ?>" <?php
                                                                if (has_post_thumbnail() && ($post_format == 'video' || $post_format == 'audio' || $post_format == 'gallery')) {
                                                                    echo 'style="' . $border_radius . 'background-image: url(' . get_the_post_thumbnail_url(get_the_ID(), 'large') . '"';
                                                                }
                                                                ?> style="<?php echo esc_attr($border_radius); ?>">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <!-- Post image -->
                                            <div class="post-image">
                                                <?php if ($post_format == 'standard') : ?>
                                                    <?php if (in_array('thumbnail', $post_details_checkbox_multi)): ?>
                                                        <a href="<?php echo get_permalink(); ?>">
                                                            <?php if (has_post_thumbnail()) : ?>
                                                                <img style="<?php echo esc_attr($height_image_css); ?>" src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'large'); ?>" alt="تصویر پروژه" />
                                                            <?php endif; ?>
                                                        </a>
                                                    <?php endif; ?>
                                                <?php elseif ($post_format == 'quote') : ?>
                                                    <div class="post-image__quote">
                                                        ”
                                                    </div>
                                                <?php elseif ($post_format == 'video') : ?>
                                                    <div class="post-image__video">
                                                        <a href="<?php echo get_permalink(); ?>">
                                                            <i class="fa-light fa-play"></i>
                                                        </a>
                                                    </div>
                                                <?php elseif ($post_format == 'audio') : ?>
                                                    <div class="post-image__audio">
                                                        <a href="<?php echo get_permalink(); ?>">
                                                            <i class="fa-light fa-volume"></i>
                                                        </a>
                                                    </div>
                                                <?php elseif ($post_format == 'gallery') : ?>
                                                    <div class="post-image__gallery">
                                                        <a href="<?php echo get_permalink(); ?>">
                                                            <i class="fa-light fa-rectangle-vertical-history"></i>
                                                        </a>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                        <!-- Post content -->
                                        <div class="post-content">
                                            <?php if (count($categories)) : ?>
                                                <?php if (in_array('category', $post_details_checkbox_multi)): ?>
                                                    <!-- Post categories -->
                                                    <ul class="post-content__categories <?php echo esc_attr($categories_status_class); ?>">
                                                        <?php
                                                        foreach ($categories as $category) {
                                                            $categories_output .= '<li><a href="' . esc_attr(get_category_link($category->term_id)) . '" class="' . esc_attr(Irantheme_Color_Static::get_class_color_mode('color', null, Irantheme_Color_Static::get_hex_color($details_color))) . '">' . esc_html__($category->name) . '</a></li>';
                                                        }
                                                        echo trim($categories_output, '');
                                                        ?>
                                                    </ul>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                            <!-- Post heading -->
                                            <div class="post-content__heading">
                                                <h2><a href="<?php echo get_permalink() ?>"><?php echo get_the_title(); ?></a></h2>
                                            </div>
                                            <?php if (in_array('excerpt', $post_details_checkbox_multi)): ?>
                                                <!-- Post text -->
                                                <div class="post-content__text">
                                                    <p><?php echo wp_trim_words(strip_shortcodes(get_the_excerpt()), 43); ?></p>
                                                </div>
                                            <?php endif; ?>
                                            <?php if (in_array('read-more', $post_details_checkbox_multi)): ?>
                                                <!-- Read more -->
                                                <div class="post-read-more">
                                                    <a href="<?php echo get_the_permalink(); ?>" class="<?php echo esc_attr(Irantheme_Color_Static::get_class_color_mode('color', null, Irantheme_Color_Static::get_hex_color($details_color))) ?>"><?php echo esc_html('مشاهده بیشتر'); ?></a>
                                                </div>
                                            <?php endif; ?>
                                            <!-- Post info -->
                                            <div class="post-content__info">
                                                <?php if (in_array('date', $post_details_checkbox_multi)): ?>
                                                    <div class="post-content__info-item">
                                                        <?php echo get_the_date('j F Y'); ?>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if (in_array('author', $post_details_checkbox_multi)): ?>
                                                    <div class="post-content__info-item">
                                                        <?php echo get_the_author_posts_link(); ?>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if (in_array('like', $post_details_checkbox_multi)): ?>
                                                    <?php
                                                    // Counter of like
                                                    $likeCount = new WP_Query(array(
                                                        'post_type' => 'like',
                                                        'meta_query' => array(
                                                            array(
                                                                'key' => 'liked_meta_value_key',
                                                                'compare' => '=',
                                                                'value' => get_the_ID()
                                                            )
                                                        )
                                                    ));
                                                    ?>
                                                    <div class="post-content__info-item">
                                                        <?php echo esc_html('پسند شده'); ?>
                                                        <span><?php echo esc_html($likeCount->found_posts); ?></span>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if (in_array('comment', $post_details_checkbox_multi)): ?>
                                                    <div class="post-content__info-item">
                                                        <?php echo esc_html('دیدگاه'); ?><span><?php echo esc_html(get_comments_number()); ?></span>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </article>
                                </div>
                            <?php endwhile; ?>
                            <?php
                            // Restore original post data
                            wp_reset_postdata();
                            ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php
            // Get pagination links
            self::get_pagination($query_posts->max_num_pages, $base_color_class);
            wp_reset_query();
        }


        /**
         * Blog advanced archive
         * @return void
         * @param string,string,string,int,string,string,array,string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_advanced_archive($base_color_class, $wrapper, $holder_item, $masonry_sizer = '', $posts_per_page = 12, $post_height = 280, $post_category_toggle_checkbox = 'on', $post_details_checkbox_multi = array(), $details_color = 'off', $border_radius = '')
        {
            // Get wp query archive
            $query_posts = self::get_wp_query_archive($posts_per_page);

            // Set height post image
            $height_image_css = 'height:' . $post_height . 'px';

            // Set details color
            $details_color_css = '';
            $details_color_rgba_css = '';
            $details_color_rgb_array = Irantheme_Color_Static::hex_to_rgb(Irantheme_Color_Static::get_hex_color($details_color));
            if ($details_color && $details_color != 'off') {
                $details_color_rgba_css = 'rgba(' . $details_color_rgb_array[0] . ',' . $details_color_rgb_array[1] . ',' . $details_color_rgb_array[2] . ',' . '0.3' . ')';
                $details_color_css = '#' . Irantheme_Color_Static::get_hex_color($details_color);
            }
        ?>
            <!-- Blog content -->
            <div class="irantheme-archive-advanced irantheme-wrapper-categories-trigger <?php echo esc_attr($base_color_class); ?>">
                <?php if ($query_posts->have_posts()) : ?>
                    <?php if ($post_category_toggle_checkbox == 'on'): ?>
                        <?php Irantheme_Common_Static::get_categories_trigger('category', $details_color, $base_color_class); ?>
                    <?php endif; ?>
                    <div class="irantheme-categories-posts">
                        <!-- All posts -->
                        <main class="<?php echo esc_attr($wrapper); ?>">
                            <div class="irantheme-grid-masonry-layout-sizer" style="<?php echo esc_attr($masonry_sizer); ?>"></div>
                            <?php while ($query_posts->have_posts()) : $query_posts->the_post(); ?>
                                <!-- Blog post -->
                                <div class="post-holder <?php echo esc_attr($holder_item); ?>" style="<?php echo esc_attr($masonry_sizer); ?>" data-cate="<?php echo esc_attr(self::get_categories_ids_by_post(get_the_ID())); ?>">
                                    <article class="post-box" style="<?php echo esc_attr($border_radius); ?>">
                                        <?php
                                        // Get all category of posts
                                        $categories = get_the_category();
                                        $categories_output = '';

                                        // Get post format
                                        $post_format = get_post_format();

                                        // Get gallery post, (true, false) -> related to html output or data
                                        $gallery_post = get_post_gallery(get_the_ID(), false);

                                        // Init url video
                                        $video_url =  wp_get_attachment_url(get_the_ID());
                                        $videoElement = wp_video_shortcode(array('src' => esc_url($video_url)));
                                        ?>

                                        <!-- Post content -->
                                        <div class="post-content">
                                            <!-- Post topbar -->
                                            <div class="post-topbar">
                                                <?php if (in_array('author', $post_details_checkbox_multi)): ?>
                                                    <span class="gradient" style="background-image:linear-gradient(to left, <?php echo $details_color_rgba_css; ?>, white);"><em style="background-color:<?php echo esc_attr($details_color_css); ?>;"></em><i class="fa-light fa-user"></i><?php echo get_the_author_posts_link(); ?></span>
                                                <?php endif; ?>
                                                <?php if (in_array('read-more', $post_details_checkbox_multi)): ?>
                                                    <!-- Read more -->
                                                    <span class="post-topbar-more">
                                                        <a href="<?php echo get_the_permalink(); ?>"><?php echo esc_html('مشاهده بیشتر'); ?><i class="fa-light fa-arrow-left"></i></a>
                                                    </span>
                                                <?php endif; ?>
                                            </div>

                                            <!-- Comments & Likes -->
                                            <div class="post-options">
                                                <?php if (in_array('date', $post_details_checkbox_multi)): ?>
                                                    <span><?php echo get_the_date('j F Y'); ?></span>
                                                <?php endif; ?>
                                                <?php if (in_array('comment', $post_details_checkbox_multi)): ?>
                                                    <span><?php esc_html_e('دیدگاه'); ?><em><?php echo get_comments_number(); ?></em></span>
                                                <?php endif; ?>
                                                <?php if (in_array('like', $post_details_checkbox_multi)): ?>
                                                    <?php
                                                    // Counter of like
                                                    $likeCount = new WP_Query(array(
                                                        'post_type' => 'like',
                                                        'meta_query' => array(
                                                            array(
                                                                'key' => 'liked_meta_value_key',
                                                                'compare' => '=',
                                                                'value' => get_the_ID()
                                                            )
                                                        )
                                                    ));
                                                    ?>
                                                    <span><?php esc_html_e('پسندشده'); ?><em><?php echo esc_html($likeCount->found_posts); ?></em></span>
                                                <?php endif; ?>
                                            </div>


                                            <!-- Post heading -->
                                            <div class="post-heading">
                                                <h2><a href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title(); ?></a></h2>
                                            </div>

                                            <?php if (in_array('excerpt', $post_details_checkbox_multi)): ?>
                                                <!-- Post text -->
                                                <div class="post-text">
                                                    <p><?php echo wp_trim_words(strip_shortcodes(get_the_excerpt()), 43); ?></p>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <?php if (!empty($categories)) : ?>
                                            <?php if (in_array('category', $post_details_checkbox_multi)): ?>
                                                <!-- Post category without thumbnail -->
                                                <div class="post-category-no-thumbnail">
                                                    <?php
                                                    foreach ($categories as $category) {
                                                        $categories_output .= '<a href=" ' . get_category_link($category->term_id) . '" class="' . Irantheme_Color_Static::get_class_color_mode('color', null, Irantheme_Color_Static::get_hex_color($details_color)) . '">' . esc_html__($category->name) . '</a>';
                                                    }
                                                    echo trim($categories_output, '');
                                                    ?>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>



                                        <?php if ($post_format == 'video' && $videoElement) : ?>
                                            <!-- Post video -->
                                            <div class="post-video">
                                                <?php echo $videoElement; ?>
                                            </div>

                                        <?php elseif ($post_format == 'gallery' && $gallery_post) : ?>
                                            <!-- Post gallery -->
                                            <div class="post-slider">
                                                <a href="<?php echo get_the_permalink(); ?>" class="post-slider-link">
                                                    <!-- Swiper slider -->
                                                    <div class="swiper gallery-swiper">
                                                        <div class="swiper-wrapper">
                                                            <?php if (has_post_thumbnail()) : ?>
                                                                <div class="swiper-slide"><img src="<?php the_post_thumbnail_url('large'); ?>" alt="تصویر اصلی"></div>
                                                            <?php endif; ?>
                                                            <?php foreach ($gallery_post['src'] as $gallery_src) : ?>
                                                                <div class="swiper-slide"><img src="<?php echo esc_url($gallery_src); ?>" alt="گالری"></div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                        <!-- Slider buttons -->
                                                        <div class="post-image-slider-buttons">
                                                            <div class="post-image-next"><i class="fa-light fa-chevron-right"></i>
                                                            </div>
                                                            <div class="post-image-prev"><i class="fa-light fa-chevron-left"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        <?php elseif (has_post_thumbnail()) : ?>
                                            <?php if (in_array('thumbnail', $post_details_checkbox_multi)): ?>
                                                <!-- Post image -->
                                                <div class="post-image">
                                                    <a href="<?php echo get_the_permalink(); ?>" class="post-image-link">
                                                        <!-- Post thumbnail -->
                                                        <div class="post-thumbnail" style="<?php echo esc_attr($border_radius); ?>">
                                                            <img src="<?php the_post_thumbnail_url('large'); ?>" style="<?php echo esc_attr($height_image_css); ?>" alt="تصویر مطلب">
                                                            <?php
                                                            // Get post format icon
                                                            echo self::get_class_icon_post_type($post_format);
                                                            ?>
                                                        </div>
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </article>
                                </div>
                            <?php endwhile; ?>
                            <?php
                            // Restore original post data
                            wp_reset_postdata();
                            ?>
                        </main>
                    </div>
                <?php endif; ?>
            </div>
        <?php
            // Get pagination links
            self::get_pagination($query_posts->max_num_pages, $base_color_class);
            wp_reset_query();
        }


        /**
         * Blog luxurious archive
         * @return void
         * @param string,string,string,int,string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_luxurious_archive($base_color_class, $wrapper, $holder_item, $masonry_sizer = '', $posts_per_page = 12, $post_height = 280, $post_category_toggle_checkbox = 'on', $post_details_checkbox_multi = array(), $details_color = 'off', $border_radius = '')
        {
            // Get wp query archive
            $query_posts = self::get_wp_query_archive($posts_per_page);

            // Set height post image
            $height_image_css = 'height:' . $post_height . 'px';
        ?>
            <div class="irantheme-archive-luxurious irantheme-wrapper-categories-trigger <?php echo esc_attr($base_color_class); ?>">
                <?php if ($post_category_toggle_checkbox == 'on'): ?>
                    <?php Irantheme_Common_Static::get_categories_trigger('category', $details_color, $base_color_class); ?>
                <?php endif; ?>
                <div class="irantheme-categories-posts">
                    <!-- Masonry style post -->
                    <div class="<?php echo esc_attr($wrapper); ?>">
                        <div class="irantheme-grid-masonry-layout-sizer" style="<?php echo esc_attr($masonry_sizer); ?>"></div>
                        <?php while ($query_posts->have_posts()) : $query_posts->the_post(); ?>
                            <!-- Post -->
                            <div class="post-holder <?php echo esc_attr($holder_item); ?>" data-cate="<?php echo esc_attr(self::get_categories_ids_by_post(get_the_ID())); ?>" style="<?php echo esc_attr($masonry_sizer); ?>">
                                <article class="post" style="<?php echo esc_attr($border_radius); ?>">
                                    <?php
                                    // Get all category of posts
                                    $categories = get_the_category();
                                    $categories_output = '';

                                    // Get post format
                                    $post_format = get_post_format();

                                    // Get gallery post, (true, false) -> related to html output or data
                                    $gallery_post = get_post_gallery(get_the_ID(), false);

                                    // Init url video
                                    $video_url =  wp_get_attachment_url(get_the_ID());
                                    $videoElement = wp_video_shortcode(array('src' => esc_url($video_url)));

                                    // Content margin
                                    $margin_content_css = '';

                                    // Check video format
                                    if ($post_format == 'video' && $videoElement) :
                                        $margin_content_css = 'margin-top: -2rem;';
                                    ?>
                                        <!-- Post video -->
                                        <div class="post-video">
                                            <?php
                                            echo $videoElement;
                                            ?>
                                        </div>

                                    <?php

                                    elseif ($post_format == 'gallery' && $gallery_post) :
                                        $margin_content_css = 'margin-top: -2rem;'; ?>
                                        <!-- Post gallery -->
                                        <div class="post-slider">
                                            <a href="<?php echo get_the_permalink(); ?>" class="post-slider-link">
                                                <!-- Swiper slider -->
                                                <div class="swiper gallery-swiper">
                                                    <div class="swiper-wrapper">
                                                        <?php if (has_post_thumbnail()) : ?>
                                                            <div class="swiper-slide"><img style="<?php echo esc_attr($height_image_css); ?>" src="<?php the_post_thumbnail_url('large'); ?>" alt="تصویر اصلی"></div>
                                                        <?php endif; ?>
                                                        <?php foreach ($gallery_post['src'] as $gallery_src) : ?>
                                                            <div class="swiper-slide"><img style="<?php echo esc_attr($height_image_css); ?>" src="<?php echo esc_url($gallery_src); ?>" alt="گالری"></div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                    <!-- Slider buttons -->
                                                    <div class="post-image-slider-buttons">
                                                        <div class="post-image-next"><i class="fa-light fa-chevron-right"></i>
                                                        </div>
                                                        <div class="post-image-prev"><i class="fa-light fa-chevron-left"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>

                                    <?php elseif (has_post_thumbnail()) :
                                        $margin_content_css = 'margin-top: -2rem;'; ?>
                                        <?php if (in_array('thumbnail', $post_details_checkbox_multi)): ?>
                                            <!-- Post image -->
                                            <div class="post-image">
                                                <a href="<?php echo get_the_permalink(); ?>" class="post-image-link">
                                                    <img style="<?php echo esc_attr($height_image_css); ?>" src="<?php the_post_thumbnail_url('large'); ?>" alt="Image post">
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    <?php
                                    endif; ?>

                                    <!-- Post content -->
                                    <div class="post-content" style="<?php echo esc_attr($border_radius); ?><?php echo esc_attr($margin_content_css); ?>">
                                        <div class="post-content-top">
                                            <?php if (!empty($categories)) : ?>
                                                <?php if (in_array('category', $post_details_checkbox_multi)): ?>
                                                    <!-- Post category -->
                                                    <div class="post-category">
                                                        <?php
                                                        foreach ($categories as $category) {
                                                            $categories_output .= '<a href=" ' . esc_url_raw(get_category_link($category->term_id)) . '" class="' . esc_attr(Irantheme_Color_Static::get_class_color_mode('color', null, Irantheme_Color_Static::get_hex_color($details_color))) . '">' . __($category->name) . '</a>';
                                                        }
                                                        echo trim($categories_output, '');
                                                        ?>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                            <?php if (in_array('date', $post_details_checkbox_multi)): ?>
                                                <!-- Post date -->
                                                <div class="post-date">
                                                    <span><?php echo get_the_date('j F Y'); ?></span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <!-- Post heading -->
                                        <div class="post-heading">
                                            <h2><a href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title(); ?></a></h2>
                                        </div>
                                        <?php if (in_array('excerpt', $post_details_checkbox_multi)): ?>
                                            <!-- Post text -->
                                            <div class="post-text">
                                                <p><?php echo wp_trim_words(strip_shortcodes(get_the_excerpt()), 50); ?></p>
                                            </div>
                                        <?php endif; ?>
                                        <?php if (in_array('read-more', $post_details_checkbox_multi)): ?>
                                            <!-- Read more -->
                                            <div class="post-read-more">
                                                <a href="<?php echo get_the_permalink(); ?>" class="<?php echo esc_attr(Irantheme_Color_Static::get_class_color_mode('color', null, Irantheme_Color_Static::get_hex_color($details_color))) ?>"><?php echo esc_html('مشاهده بیشتر'); ?></a>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Post info -->
                                    <div class="post-info">
                                        <?php if (in_array('author', $post_details_checkbox_multi)): ?>
                                            <!-- Post author -->
                                            <div class="post-info-option">
                                                <i class="iconsax" icon-name="user-2-circle"></i>
                                                <div class="post-info-option-text">
                                                    <?php echo get_the_author_posts_link(); ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <?php if (in_array('comment', $post_details_checkbox_multi)): ?>
                                            <!-- Post Comment -->
                                            <div class="post-info-option">
                                                <div class="post-info-option-text">
                                                    <i class="fa-light fa-comments"></i>
                                                    <span><?php echo get_comments_number(); ?></span>
                                                    <cite><?php esc_html_e('دیدگاه'); ?></cite>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <?php if (in_array('like', $post_details_checkbox_multi)): ?>
                                            <?php
                                            // Counter of like
                                            $likeCount = new WP_Query(array(
                                                'post_type' => 'like',
                                                'meta_query' => array(
                                                    array(
                                                        'key' => 'liked_meta_value_key',
                                                        'compare' => '=',
                                                        'value' => get_the_ID()
                                                    )
                                                )
                                            ));
                                            ?>
                                            <!-- Post like -->
                                            <div class="post-info-option">
                                                <div class="post-info-option-text">
                                                    <i class="fa-light fa-heart"></i>
                                                    <span><?php echo $likeCount->found_posts; ?></span>
                                                    <cite><?php esc_html_e('پسندشده'); ?></cite>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </article>
                            </div>
                        <?php endwhile; ?>
                        <?php
                        // Restore original post data
                        wp_reset_postdata();
                        ?>
                    </div>
                </div>
            </div>
        <?php
            // Get pagination links
            self::get_pagination($query_posts->max_num_pages, $base_color_class);
            wp_reset_query();
        }


        /**
         * Blog technological little archive
         * @return void
         * @param string,string,string,int,string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_traditional_archive($base_color_class, $wrapper, $holder_item, $masonry_sizer = '', $posts_per_page = 12, $post_height = 280, $post_category_toggle_checkbox = 'on', $post_details_checkbox_multi = array(), $details_color = 'off', $border_radius = '')
        {
            // Get wp query archive
            $query_posts = self::get_wp_query_archive($posts_per_page);

            // Set height post image
            $height_image_css = 'height:' . $post_height . 'px';
        ?>
            <!-- Archive -->
            <div class="irantheme-archive-traditional irantheme-wrapper-categories-trigger <?php echo esc_attr($base_color_class); ?>">
                <?php
                if ($query_posts->have_posts()) : ?>
                    <?php if ($post_category_toggle_checkbox == 'on'): ?>
                        <?php Irantheme_Common_Static::get_categories_trigger('category', $details_color, $base_color_class); ?>
                    <?php endif; ?>
                    <div class="irantheme-categories-posts">
                        <!-- Box style style -->
                        <div class="<?php echo esc_attr($wrapper); ?>">
                            <div class="irantheme-grid-masonry-layout-sizer" style="<?php echo esc_attr($masonry_sizer); ?>"></div>
                            <?php
                            while ($query_posts->have_posts()) : $query_posts->the_post();
                                // Get all category of posts
                                $categories = get_the_category();
                                $categories_output = '';

                                // Get post format
                                $post_format = get_post_format();
                            ?>
                                <!-- Item -->
                                <div class="<?php echo esc_attr($holder_item); ?> post-holder" style="<?php echo esc_attr($masonry_sizer); ?>" data-cate="<?php echo esc_attr(self::get_categories_ids_by_post(get_the_ID())); ?>">
                                    <div class="post-box-little" style="<?php echo esc_attr($border_radius); ?>">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <?php if (in_array('thumbnail', $post_details_checkbox_multi)): ?>
                                                <!-- Image -->
                                                <div class="post-thumbnail-little">
                                                    <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'medium_large'); ?>" style="<?php echo esc_attr($height_image_css); ?>" alt="تصویر">
                                                    <?php
                                                    // Get post format icon
                                                    echo self::get_class_icon_post_type($post_format);
                                                    ?>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <!-- Post content -->
                                        <div class="post-content">
                                            <?php if (in_array('category', $post_details_checkbox_multi)): ?>
                                                <?php if (!empty($categories)) : ?>
                                                    <!-- Post category -->
                                                    <div class="post-category">
                                                        <?php
                                                        foreach ($categories as $category) {
                                                            $categories_output .= '<a href=" ' . esc_url_raw(get_category_link($category->term_id)) . '" class="' . esc_attr(Irantheme_Color_Static::get_class_color_mode('color', null, Irantheme_Color_Static::get_hex_color($details_color))) . '">' . __($category->name) . '</a>';
                                                        }
                                                        echo trim($categories_output, '');
                                                        ?>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                            <!-- Heading -->
                                            <div class="post-heading">
                                                <h2><a href="<?php echo get_the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                            </div>
                                            <!-- Options -->
                                            <div class="post-options-little">
                                                <!-- Comments & Likes -->
                                                <?php if (in_array('author', $post_details_checkbox_multi)): ?>
                                                    <span><?php echo esc_html('توسط') ?><em><?php echo get_the_author_posts_link(); ?></em></span>
                                                <?php endif; ?>
                                                <?php if (in_array('date', $post_details_checkbox_multi)): ?>
                                                    <span><?php echo get_the_date('j F Y - H:i'); ?></span>
                                                <?php endif; ?>
                                                <?php if (in_array('comment', $post_details_checkbox_multi)): ?>
                                                    <span><?php echo esc_html('دیدگاه') ?><em><?php echo get_comments_number(); ?></em></span>
                                                <?php endif; ?>
                                                <?php if (in_array('like', $post_details_checkbox_multi)): ?>
                                                    <span><?php echo esc_html('پسندشده') ?><em><?php echo esc_html(irantheme_get_post_like_count(get_the_ID())); ?></em></span>
                                                <?php endif; ?>
                                            </div>
                                            <?php if (in_array('excerpt', $post_details_checkbox_multi)): ?>
                                                <!-- Post text -->
                                                <div class="post-text">
                                                    <p><?php echo wp_trim_words(strip_shortcodes(get_the_excerpt()), 30); ?></p>
                                                </div>
                                            <?php endif; ?>
                                            <?php if (in_array('read-more', $post_details_checkbox_multi)): ?>
                                                <!-- Read more -->
                                                <div class="post-read-more">
                                                    <a href="<?php echo get_the_permalink(); ?>" class="<?php echo esc_attr(Irantheme_Color_Static::get_class_color_mode('color', null, Irantheme_Color_Static::get_hex_color($details_color))) ?>"><?php echo esc_html('مشاهده بیشتر'); ?></a>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                            <?php
                            // Restore original post data
                            wp_reset_postdata();
                            ?>
                        </div>
                    </div>
                <?php
                endif;
                ?>
            </div>
            <?php
            // Get pagination links
            self::get_pagination($query_posts->max_num_pages, $base_color_class);
            wp_reset_query();
        }


        /**
         * Return pagination links
         * @return void
         * @param string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_pagination($max_num_page, $base_color_class)
        {
            if (paginate_links()) :
                $big = 999999999; // need an unlikely integer
            ?>
                <!-- Pagination -->
                <div class="irantheme-pagination <?php echo esc_attr($base_color_class); ?>">
                    <?php echo paginate_links(array(
                        'prev_text' => 'صفحه قبل',
                        'next_text' => 'صفحه بعد',
                        'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                        'format' => '?paged=%#%',
                        'current' => max(1, get_query_var('paged')),
                        'total' => $max_num_page
                    )); ?>
                </div>
            <?php endif;
        }


        /**
         * Return categories of a post by id (String comma separator)
         * @return string
         * @param string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_categories_ids_by_post($post_id)
        {
            // Get parent id category of post
            $category = get_the_category($post_id);
            // $category_parent_list = array();
            $category_parents_id = '';
            foreach ($category as $cate) {
                if ($cate->parent) {
                    // From your child category, grab parent ID
                    $parent = $cate->parent;

                    // Load object for parent category
                    $parent_id = get_category($parent);

                    // Grab a category name
                    $parent_id = $parent_id->term_id;
                    $category_parents_id .= strval($parent_id) . ',';
                } else {
                    $category_parents_id .= strval($cate->term_id) . ',';
                }
            }
            return $category_parents_id;
        }


        /**
         * Return array of header heading
         * @return array
         * @param string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_header_heading($custom_heading, $heading, $description)
        {
            $args = array();
            // Check custom heading checkbox
            if ($custom_heading == 'on') {
                $args['title'] = $heading;
                $args['subtitle'] = $description;
                return $args;
            } else {
                // Check title, subtitle
                if (is_home()) {
                    $args['title'] = 'وبلاگ';
                    $args['subtitle'] = 'آخرین مطالب ما را در این صفحه مشاهده می کنید.';
                } else if (is_author()) {
                    $author = get_user_by('slug', get_query_var('author_name'));
                    $args['title'] = get_the_archive_title();
                    $args['subtitle'] = esc_html__($author->nickname);
                } else if (is_archive()) {
                    $args['title'] = get_the_archive_title();
                    $args['subtitle'] = get_the_archive_description();
                } else if (is_single() || is_page()) {
                    $args['title'] = get_the_title(get_queried_object_id());
                    $args['subtitle'] = wp_trim_words(strip_shortcodes(get_the_excerpt(get_queried_object_id())), 25);
                } else if (is_404()) {
                    $args['title'] = 'ارور ۴۰۴';
                    $args['subtitle'] = 'صفحه مورد نظر پیدا نشد.';
                } else {
                    $args['title'] = 'ناشناخته';
                    $args['subtitle'] = 'ناشناخته';
                }
                return $args;
            }
        }


        /**
         * Return header technological elements
         * @return void
         * @param string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_header_technological($background_url, $custom_heading = 'off', $heading = null, $description = null)
        {
            ?>
            <!-- Header -->
            <header id="header-technological" style="background-image: url('<?php echo esc_url_raw($background_url); ?>');">
                <!-- Mask dark -->
                <div class="irantheme-mask-dark"></div>
                <!-- Wrapper header -->
                <div id="wrapper-header">

                    <!-- Banner universal -->
                    <div class="banner-universal">
                        <div class="irantheme-container-inner">
                            <!-- Heading -->
                            <div class="banner-universal-heading">
                                <?php
                                // Get header heading info
                                $args = self::get_header_heading($custom_heading, $heading, $description);
                                ?>
                                <h1><?php echo $args['title']; ?></h1>
                                <p><?php echo $args['subtitle']; ?></p>
                            </div>

                            <?php if (is_single()) : ?>
                                <ul class="banner-universal__info">
                                    <!-- Date -->
                                    <li class="banner-universal__info-date"><?php echo get_the_date('j F Y', Irantheme_Page_Builder::get_current_page_id()); ?></li>
                                    <!-- Comments number -->
                                    <li class="banner-universal__info-comments-number"><span><?php echo get_comments_number(Irantheme_Page_Builder::get_current_page_id()); ?></span>دیدگاه</li>
                                    <!-- Category -->
                                    <?php
                                    $categories = get_the_category(Irantheme_Page_Builder::get_current_page_id());
                                    $separator = ' - ';
                                    $output = '';
                                    if (!empty($categories)) : ?>
                                        <li class="banner-universal__info-category">
                                            <!-- Category -->
                                            <?php
                                            foreach ($categories as $category) {
                                                $output .= '<a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a>' . $separator;
                                            }
                                            echo trim($output, $separator);
                                            ?>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if (is_single()) : ?>
                        <!-- Info universal -->
                        <div class="info-universal">
                            <div class="container-fluid">
                                <!-- Author -->
                                <div class="info-universal-author">
                                    <div class="info-universal-author-img">
                                        <?php echo get_avatar(get_the_author_meta('ID'), 100); ?>
                                    </div>
                                    <div class="info-universal-author-content">
                                        <strong><?php echo get_the_author_posts_link(); ?></strong>
                                        <span><?php echo get_the_author_meta('nickname'); ?></span>
                                    </div>
                                </div>
                                <!-- Mouse toggle -->
                                <div class="mouse-toggle">
                                    <a class="irantheme-mouse-down-toggle"></a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </header>
        <?php
        }


        /**
         * Return header luxurious elements
         * @return void
         * @param string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_header_luxurious($background_url, $custom_heading = 'off', $heading = null, $description = null)
        {
        ?>
            <!-- Header ############################### -->
            <header id="header-luxurious" style="background-image: url('<?php echo esc_url_raw($background_url); ?>');">
                <!-- Mask dark -->
                <div class="irantheme-mask-dark"></div>
                <div class="container-fluid">
                    <!-- Hero -->
                    <div class="hero">
                        <div class="irantheme-container-inner">
                            <!-- Showcase -->
                            <div class="showcase">
                                <!-- Heading -->
                                <div class="heading">
                                    <?php
                                    // Get header heading info
                                    $args = self::get_header_heading($custom_heading, $heading, $description);
                                    ?>
                                    <h1><?php echo $args['title']; ?></h1>
                                    <p><?php echo $args['subtitle']; ?></p>
                                </div>
                                <!-- Social networks -->
                                <?php Irantheme_Common_Static::get_social_media(); ?>
                                <!-- Mouse down -->
                                <div class="mouse-down">
                                    <a class="irantheme-mouse-down-toggle"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
        <?php
        }


        /**
         * Return header advanced elements
         * @return void
         * @param string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_header_advanced($background_url, $custom_heading = 'off', $heading = null, $description = null)
        {
        ?>
            <!-- Header blog -->
            <header id="header-advanced" style="background-image: url('<?php echo esc_url_raw($background_url); ?>');">
                <!-- Mask dark -->
                <div class="irantheme-mask-dark"></div>
                <div class="irantheme-container-inner">
                    <!-- Heading info -->
                    <div class="heading-info">
                        <!-- Breadcrumbs -->
                        <?php irantheme_custom_breadcrumbs(); ?>
                        <!-- Options -->
                        <div class="options">
                            <div class="options-layer">
                                <div class="irantheme-holder-options">
                                    <!-- Social networks -->
                                    <?php Irantheme_Common_Static::get_social_media(); ?>
                                </div>
                                <?php
                                $today = null;
                                if (function_exists('parsidate')) {
                                    $today = parsidate('j F Y', date('F j Y'), 'per');
                                } else {
                                    $today = date('F j Y');
                                }
                                ?>
                                <span class="px-2">امروز‌ : <?php echo esc_html($today); ?></span>
                            </div>
                        </div>
                        <!-- Main info -->
                        <div class="heading-main-info">
                            <?php
                            // Get header heading info
                            $args = self::get_header_heading($custom_heading, $heading, $description);
                            ?>
                            <h1><?php echo $args['title']; ?></h1>
                            <p><?php echo $args['subtitle']; ?></p>
                        </div>
                    </div>
                </div>
            </header>
            <?php
        }


        /**
         * Return class icon compatible with post type
         * @return string|boolean
         * @param string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_class_icon_post_type($post_type)
        {
            if ($post_type == 'aside') {
                return sprintf('<div class="irantheme-post-format-icon">%s</div>', '<i class="fa-light fa-sidebar-flip"></i>');
            } else if ($post_type == 'gallery') {
                return sprintf('<div class="irantheme-post-format-icon">%s</div>', '<i class="fa-light fa-images"></i>');
            } else if ($post_type == 'link') {
                return sprintf('<div class="irantheme-post-format-icon">%s</div>', '<i class="fa-light fa-link"></i>');
            } else if ($post_type == 'image') {
                return sprintf('<div class="irantheme-post-format-icon">%s</div>', '<i class="fa-light fa-image"></i>');
            } else if ($post_type == 'quote') {
                return sprintf('<div class="irantheme-post-format-icon">%s</div>', '<i class="fa-light fa-quotes"></i>');
            } else if ($post_type == 'status') {
                return sprintf('<div class="irantheme-post-format-icon">%s</div>', '<i class="fa-light fa-signal-strong"></i>');
            } else if ($post_type == 'video') {
                return sprintf('<div class="irantheme-post-format-icon">%s</div>', '<i class="fa-light fa-video"></i>');
            } else if ($post_type == 'audio') {
                return sprintf('<div class="irantheme-post-format-icon">%s</div>', '<i class="fa-light fa-volume"></i>');
            } else if ($post_type == 'chat') {
                return sprintf('<div class="irantheme-post-format-icon">%s</div>', '<i class="fa-light fa-comment-lines"></i>');
            }
            return false;
        }


        /**
         * Display related posts of single
         * @return void
         * @param string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function display_post_related($base_color_class, $border_radius_css)
        {
            global $post;
            $related_category = get_the_category($post->ID);
            if ($related_category) :
                $category_ids = array();
                foreach ($related_category as $individual_category) {
                    $category_ids[] = $individual_category->term_id;
                }
                $args = array(
                    'post_type' => 'post',
                    'category__in' => $category_ids,
                    'post__not_in' => array($post->ID),
                    'posts_per_page' => 3,
                    'ignore_sticky_posts' => 1
                );
                $related_query = new WP_Query($args);

                $apply_base_color_class = '';
                if ($base_color_class == 'dark') {
                    $apply_base_color_class = 'light';
                } elseif ($base_color_class == 'white') {
                    $apply_base_color_class = 'dark';
                }

                if ($related_query->have_posts()) : ?>

                    <!-- Single post box -->
                    <div class="post-box-style <?php echo esc_attr($base_color_class); ?>">
                        <!-- Heading mode -->
                        <div class="heading-mode heading-mode-<?php echo esc_attr($apply_base_color_class); ?>">
                            <h2>مطالب مرتبط</h2>
                        </div>
                        <div class="row">
                            <?php
                            while ($related_query->have_posts()) : $related_query->the_post(); ?>
                                <!-- Item -->
                                <div class="col-lg-4">

                                    <div class="post-box-item">
                                        <?php

                                        if (has_post_thumbnail()) : ?>
                                            <!-- Post image -->
                                            <div class="post-box-image-space">
                                                <a href="<?php echo get_the_permalink(); ?>" class="post-box-image-link">
                                                    <!-- Post thumbnail -->
                                                    <div class="post-box-thumbnail-space" style="<?php echo esc_attr($border_radius_css); ?>">
                                                        <img src="<?php the_post_thumbnail_url('large'); ?>" alt="تصویر مطلب" style="<?php echo esc_attr($border_radius_css); ?>">
                                                    </div>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                        <!-- Holder -->
                                        <div class="post-box-content-space text-right">
                                            <!-- Heading -->
                                            <div class="post-box-heading-space">
                                                <!-- Date -->
                                                <span><i class="fa-light fa-calendar-days"></i><?php echo get_the_date('j F Y'); ?></span>
                                                <h2><a href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title(); ?></a></h2>
                                            </div>
                                            <?php
                                            // Counter of like
                                            $like_count = irantheme_get_post_like_count(get_the_ID());
                                            ?>
                                            <!-- Comments & Likes -->
                                            <div class="post-box-list-options">
                                                <span><i class="fa-light fa-comments <?php echo esc_attr(Irantheme_Color_Static::get_class_color_mode('color', 'primary')) ?>"></i><?php echo get_comments_number(); ?> دیدگاه</span>
                                                <span><i class="<?php if ($like_count) {
                                                                    echo 'fa-solid fa-heart';
                                                                } else {
                                                                    echo 'fa-light fa-heart';
                                                                } ?> <?php echo esc_attr(Irantheme_Color_Static::get_class_color_mode('color', 'primary')) ?>"></i><?php echo esc_html($like_count); ?> پسندشده</span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
<?php
                endif;
            endif;
            wp_reset_query();
        }
    }
}
