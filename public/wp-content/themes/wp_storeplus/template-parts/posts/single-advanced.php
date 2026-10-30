<?php

use Irantheme_Dev\Irantheme_Color_Static;
use Irantheme_Dev\Irantheme_Common_Static;
use Irantheme_Dev\Irantheme_Section_Utils;

// Get base color
$base_color = get_option('irantheme_blog_base_color_select');
// Set base color class
$base_color_class = Irantheme_Common_Static::get_base_color_class($base_color);

// Get post post details
$post_details_checkbox_multi = get_option('irantheme_single_content_post_details_checkbox_multi');
$post_details_checkbox_multi = explode(',', $post_details_checkbox_multi);

// Get details color
$details_color = get_option('irantheme_blog_visual_color_select');

// Set details color
$details_color_css = '';
$details_color_rgba_css = '';
$details_color_rgb_array = Irantheme_Color_Static::hex_to_rgb(Irantheme_Color_Static::get_hex_color($details_color));
if ($details_color && $details_color != 'off') {
    $details_color_rgba_css = 'rgba(' . $details_color_rgb_array[0] . ',' . $details_color_rgb_array[1] . ',' . $details_color_rgb_array[2] . ',' . '0.3' . ')';
    $details_color_css = '#' . Irantheme_Color_Static::get_hex_color($details_color);
}

// Get border radius range
$border_radius_range = get_option('irantheme_blog_visual_border_radius_range');
$border_radius_css = Irantheme_Section_Utils::get_border_radius_css($border_radius_range);
?>

<main class="post-single-advanced <?php echo esc_attr($base_color_class); ?>">
    <?php
    while (have_posts()) : the_post(); ?>
        <!-- Single post -->
        <article class="single-post">
            <!-- Post info -->
            <div class="post-box" style="<?php echo esc_attr($border_radius_css); ?>">
                <?php
                // Get all category of posts
                $categories = get_the_category();
                $categories_output = '';

                if (!is_page()) :
                ?>
                    <!-- Post topbar -->
                    <div class="post-topbar">
                        <?php if (in_array('author', $post_details_checkbox_multi)): ?>
                            <span class="gradient" style="background-image:linear-gradient(to left, <?php echo $details_color_rgba_css; ?>, white);"><em style="background-color:<?php echo esc_attr($details_color_css); ?>;"></em><i class="fa-light fa-user"></i><?php echo get_the_author_posts_link(); ?></span>
                        <?php endif; ?>
                        <!-- Read more -->
                        <span class="post-topbar-more">
                            <a href="<?php echo get_the_permalink(); ?>"><?php echo esc_html('مشاهده بیشتر'); ?><i class="fa-light fa-arrow-left"></i></a>
                        </span>
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

                    <!-- Holder -->
                    <div class="post-content">
                        <!-- Heading -->
                        <div class="post-heading">
                            <h2><?php the_title(); ?></h2>
                        </div>

                        <?php if (in_array('thumbnail', $post_details_checkbox_multi)): ?>
                            <?php if (has_post_thumbnail()) : ?>
                                <!-- Post thumbnail -->
                                <div class="post-thumbnail">
                                    <img src="<?php the_post_thumbnail_url('large'); ?>" alt="تصویر مطلب" style="<?php echo esc_attr($border_radius_css); ?>">
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>

                        <div class="irantheme-single-text pt-3">
                            <?php the_content(); ?>
                        </div>

                        <?php if (in_array('category', $post_details_checkbox_multi)): ?>
                            <?php
                            if (!is_page()) :
                                if (!empty($categories)) : ?>
                                    <!-- Post category -->
                                    <div class="post-category-no-thumbnail">
                                        <?php
                                        foreach ($categories as $category) {
                                            $categories_output .= '<a href=" ' . esc_attr(get_category_link($category->term_id)) . '" class="' . Irantheme_Color_Static::get_class_color_mode('color', null, Irantheme_Color_Static::get_hex_color($details_color)) . '">' . esc_html__($category->name) . '</a>';
                                        }
                                        echo trim($categories_output, '');
                                        ?>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php
                        if (!is_page()) :
                        ?>

                            <?php if (in_array('tag', $post_details_checkbox_multi)): ?>
                                <!-- Tags -->
                                <div class="post-tags">
                                    <?php
                                    $tags = get_the_tags();
                                    if ($tags) {
                                        foreach ($tags as $tag) {
                                            echo '<h3><a href="' . get_tag_link($tag->term_id) . '">' . $tag->name . '</a></h3>';
                                        }
                                    }
                                    ?>
                                </div>
                            <?php endif; ?>

                            <div class="d-flex flex-row pt-3">
                                <?php if (!is_page()) : ?>
                                    <?php if (in_array('like', $post_details_checkbox_multi)): ?>
                                        <!-- Post like -->
                                        <div class="post-like">
                                            <?php
                                            // Get post like html
                                            irantheme_get_post_like(get_the_ID());
                                            ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endif;
                                wp_reset_postdata(); ?>

                                <?php if (in_array('share', $post_details_checkbox_multi)): ?>
                                    <!-- Post share -->
                                    <div class="post-share">
                                        <a target="_blank" href="http://www.facebook.com/share.php?u=<?php echo get_permalink(); ?>" title="اشتراک در فیس بوک"><i class="fa-brands fa-facebook-f"></i></a>
                                        <a target="_blank" href="http://x.com/share?text=<?php echo get_the_title(); ?>&url=<?php echo get_permalink(); ?>" title="اشتراک در ایکس"><i class="fa-brands fa-x-twitter"></i></a>
                                        <a target="_blank" href="http://www.linkedin.com/shareArticle?mini=true&url=<?php echo get_permalink(); ?>&title=<?php get_the_title(); ?>" title="اشتراک در لینکدین"><i class="fa-brands fa-linkedin"></i></a>
                                        <a target="_blank" href="https://api.whatsapp.com/send?text=<?php the_title(); ?>:<?php the_permalink(); ?>" title="اشتراک در واتس آپ"><i class="fa-brands fa-whatsapp"></i></a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </article>

        <?php
        if (!is_page()) :
        ?>
            <?php if (in_array('author', $post_details_checkbox_multi)): ?>
                <!-- Author post single -->
                <div class="post-box single-post" style="<?php echo esc_attr($border_radius_css); ?>">
                    <div class="post-content">
                        <div class="author-post-single">
                            <div class="author-post-single-avatar">
                                <?php echo get_avatar(get_the_author_meta('ID'), '120'); ?>
                            </div>
                            <div class="author-post-single-info">
                                <b>
                                    <?php echo get_the_author_posts_link(); ?>
                                    <?php if (get_the_author_meta('nickname')) : ?>
                                        <span><?php echo get_the_author_meta('nickname'); ?></span>
                                    <?php endif; ?>
                                </b>
                                <p>
                                    <?php echo get_the_author_meta('description'); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if (in_array('related-post', $post_details_checkbox_multi)): ?>
            <?php
            if (!is_page()) :
            ?>
                <?php
                $orig_post = $post;
                global $post;
                $related_category = get_the_category($post->ID);
                if ($related_category) :
                    $category_ids = array();
                    foreach ($related_category as $individual_category) {
                        $category_ids[] = $individual_category->term_id;
                    }

                    $args = array(
                        'post_type' => array('post'),
                        'category__in' => $category_ids,
                        'post__not_in' => array($post->ID),
                        'posts_per_page' => 4,
                        'ignore_sticky_posts' => 1
                    );
                    $my_query = new WP_Query($args);

                    if ($my_query->have_posts()) : ?>
                        <!-- Related posts -->
                        <div class="related-post">
                            <!-- Swiper -->
                            <div class="swiper related-post-slider">
                                <div class="swiper-wrapper">
                                    <?php
                                    while ($my_query->have_posts()) : $my_query->the_post(); ?>
                                        <!-- Item -->
                                        <div class="swiper-slide">
                                            <div class="post-box-space" style="<?php echo esc_attr($border_radius_css); ?>">
                                                <?php if (has_post_thumbnail()) : ?>
                                                    <!-- Post image -->
                                                    <div class="post-image-space">
                                                        <a href="<?php echo get_the_permalink(); ?>" class="post-image-link">
                                                            <!-- Post thumbnail -->
                                                            <div class="post-thumbnail-space">
                                                                <img src="<?php the_post_thumbnail_url('large'); ?>" alt="تصویر مطلب" style="<?php echo esc_attr($border_radius_css); ?>">
                                                            </div>
                                                        </a>
                                                    </div>
                                                <?php endif; ?>
                                                <!-- Holder -->
                                                <div class="post-content-space text-right">
                                                    <!-- Heading -->
                                                    <div class="post-heading-space">
                                                        <!-- Date -->
                                                        <span><i class="fa-light fa-calendar-days"></i><?php echo get_the_date('j F Y'); ?></span>
                                                        <h2><a href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title(); ?></a></h2>
                                                    </div>
                                                    <!-- Comments & Likes -->
                                                    <div class="post-list-options">
                                                        <span><i class="fa-light fa-comments"></i><?php echo get_comments_number(); ?> دیدگاه</span>
                                                        <span><i class="fa-light fa-heart"></i><?php echo esc_html(irantheme_get_post_like_count(get_the_ID())); ?> پسندشده</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                                <?php
                                // Slider pagination & arrow
                                Irantheme_Section_Utils::get_slider_arrow_pagination(true, true, true, 'white', $border_radius_range, 'bottom', 'center');
                                ?>
                            </div>
                        </div>
                <?php
                    endif;
                endif;
                $post = $orig_post;
                wp_reset_query();
                ?>
            <?php endif; ?>
        <?php endif; ?>

        <?php if (in_array('comment', $post_details_checkbox_multi)): ?>
            <div class="irantheme-comments <?php echo esc_attr($base_color_class); ?>">
                <?php
                // If comments are open or we have at least one comment, load up the comment template.
                if (comments_open() || get_comments_number()) :
                    comments_template();
                endif;
                ?>
            </div>
        <?php endif; ?>
    <?php endwhile; ?>
</main>