<?php

use Irantheme_Dev\Irantheme_Color_Static;
use Irantheme_Dev\Irantheme_Common_Static;
use Irantheme_Dev\Irantheme_Section_Utils;

// Get base color
$base_color = get_option('irantheme_blog_base_color_select');
// Set base color class
$base_color_class = Irantheme_Common_Static::get_base_color_class($base_color);
$base_color_btn = '';
if ($base_color == 'dark') {
    $base_color_btn = 'white';
} else if ($base_color == 'white') {
    $base_color_btn = 'dark';
}

// Get post post details
$post_details_checkbox_multi = get_option('irantheme_single_content_post_details_checkbox_multi');
$post_details_checkbox_multi = explode(',', $post_details_checkbox_multi);

// Get details color
$details_color = get_option('irantheme_blog_visual_color_select');

// Get border radius range
$border_radius_range = get_option('irantheme_blog_visual_border_radius_range');
$border_radius_css = Irantheme_Section_Utils::get_border_radius_css($border_radius_range);
?>

<main class="post-single-contemporary <?php echo esc_attr($base_color_class); ?>" style="<?php echo esc_attr($border_radius_css); ?>">
    <?php
    while (have_posts()) : the_post(); ?>
        <div class="post-box p-3">
            <div class="row">
                <?php
                $bootstrap_col = 'col-lg-5';
                if (is_page()) {
                    $bootstrap_col = 'col-lg-12';
                }
                if (!in_array('thumbnail', $post_details_checkbox_multi)) {
                    $bootstrap_col = 'col-lg-12';
                }
                ?>
                <?php
                if (!is_page()) :
                    if (in_array('thumbnail', $post_details_checkbox_multi)):
                ?>
                        <!-- Image of single -->
                        <div class="col-lg-7">
                            <?php if (has_post_thumbnail()) : ?>
                                <!-- Single thumbnail -->
                                <div class="single-thumbnail">
                                    <img src="<?php the_post_thumbnail_url('full'); ?>" alt="تصویر مطلب" style="<?php echo esc_attr($border_radius_css); ?>">
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <?php
                if (!is_page()) :
                ?>
                    <!-- Content of single -->
                    <div class="<?php echo esc_attr($bootstrap_col); ?>">
                        <!-- Next & prev post -->
                        <div class="next-prev-post">
                            <!-- Prev post button -->
                            <?php $prev_post = get_previous_post(); ?>
                            <a href="<?php if (!empty($prev_post)) : echo get_permalink($prev_post->ID);
                                        else : echo '#';
                                        endif; ?>" class="prev-post <?php if (empty($prev_post)) echo 'disable'; ?>">
                                <i class="fa-light fa-arrow-left"></i>
                            </a>
                            <!-- Next post button -->
                            <?php $next_post = get_next_post(); ?>
                            <a href="<?php if (!empty($next_post)) : echo get_permalink($next_post->ID);
                                        else : echo '#';
                                        endif; ?>" class="next-post <?php if (empty($next_post)) echo 'disable'; ?>">
                                <i class="fa-light fa-arrow-right"></i>
                            </a>
                        </div>
                    <?php endif; ?>

                    <?php
                    if (!is_page()) :
                    ?>
                        <!-- Single heading -->
                        <div class="single-heading">
                            <?php if (in_array('date', $post_details_checkbox_multi)): ?>
                                <span><i class="fa-light fa-calendar-days <?php echo esc_attr(Irantheme_Color_Static::get_class_color_mode('color', null, Irantheme_Color_Static::get_hex_color($details_color))) ?>"></i><?php echo get_the_date('j F Y'); ?></span>
                            <?php endif; ?>
                            <h2><?php the_title(); ?></h2>
                        </div>
                    <?php endif; ?>

                    <?php
                    if (!is_page()) :
                    ?>
                        <?php if (in_array('author', $post_details_checkbox_multi)): ?>
                            <!-- Single author -->
                            <div class="single-author">
                                <div class="single-author-img">
                                    <?php echo get_avatar(get_the_author_meta('ID'), 100); ?>
                                </div>
                                <div class="single-author-info">
                                    <h4><?php echo get_the_author_posts_link(); ?></h4>
                                    <p><?php the_author_meta('nickname'); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <!-- Single text -->
                    <div class="irantheme-single-text <?php echo esc_attr($base_color_class); ?>">
                        <?php the_content(); ?>
                    </div>

                    <?php
                    if (!is_page()) :
                    ?>

                        <!-- Single like share -->
                        <div class="single-like-share">
                            <?php if (in_array('like', $post_details_checkbox_multi)): ?>
                                <?php
                                // Get post like html
                                irantheme_get_post_like(get_the_ID());
                                ?>
                            <?php endif; ?>
                            <?php if (in_array('share', $post_details_checkbox_multi)): ?>
                                <!-- Single share button -->
                                <button id="button-share-toggle-list" class="button-no-style single-share-button"><i class="fa-light fa-share"></i>اشتراک</button>
                                <!-- Single share list -->
                                <div class="single-share-list">
                                    <a target="_blank" href="http://www.facebook.com/share.php?u=<?php echo get_permalink(); ?>"><i class="fa-brands fa-facebook-f"></i></a>
                                    <a target="_blank" href="http://x.com/share?text=<?php echo get_the_title(); ?>&url=<?php echo get_permalink(); ?>"><i class="fa-brands fa-x-twitter"></i></a>
                                    <a target="_blank" href="https://t.me/share/url?url=<?php echo get_permalink(); ?>&title=<?php echo get_the_title(); ?>"><i class="fa-brands fa-telegram"></i></a>
                                    <a target="_blank" href="https://api.whatsapp.com/send?text=<?php echo get_the_title(); ?>:<?php the_permalink(); ?>"><i class="fa-brands fa-whatsapp"></i></a>
                                </div>
                            <?php endif; ?>
                        </div>

                        <?php if (in_array('category', $post_details_checkbox_multi)): ?>
                            <?php
                            $categories = get_the_category();
                            $separator = ' ';
                            $output = '';
                            if (!empty($categories)) : ?>
                                <!-- Single categories -->
                                <div class="single-categories">
                                    <!-- Single categories icon -->
                                    <div class="single-categories-icon"><i class="fa-light fa-folder <?php echo esc_attr(Irantheme_Color_Static::get_class_color_mode('color', null, Irantheme_Color_Static::get_hex_color($details_color))) ?>"></i></div>
                                    <!-- Single categories content -->
                                    <div class="single-categories-content">
                                        <strong>دسته بندی</strong>
                                        <div class="single-categories-list">
                                            <?php
                                            foreach ($categories as $category) {
                                                $output .= '<a href="' . esc_url(get_category_link($category->term_id)) . '" title="' . esc_attr(sprintf(__('View all posts in %s', 'textdomain'), $category->name)) . '">' . esc_html($category->name) . '</a>' . $separator;
                                            }
                                            echo trim($output, $separator);
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php if (has_tag()) : ?>
                            <?php if (in_array('tag', $post_details_checkbox_multi)): ?>
                                <!-- Single tags -->
                                <div class="single-tags">
                                    <div class="single-tags-list">
                                        <?php
                                        $tags = get_the_tags();
                                        if ($tags) {
                                            foreach ($tags as $tag) {
                                                echo '<h3><a href="' . get_tag_link($tag->term_id) . '"><i class="fa-light fa-tag"></i>' . $tag->name . '</a></h3>';
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php if (in_array('comment', $post_details_checkbox_multi)): ?>
                            <!-- Toggle of comments -->
                            <div class="toggle-comments">
                                <button id="button-comments-show-list" class="irantheme-btn irantheme-btn-<?php echo esc_attr($base_color_btn); ?>-outline irantheme-btn-block"><i class="fa-light fa-comments"></i>مشاهده نظرات<span> (<?php echo get_comments_number(); ?> نظر) </span></button>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php
        if (in_array('related-post', $post_details_checkbox_multi)):
            // Get related posts
            Irantheme_Archives::display_post_related($base_color_class, $border_radius_css);
        endif;
        ?>
    <?php endwhile; ?>

    <?php if (in_array('comment', $post_details_checkbox_multi)): ?>
        <!-- Comments fixed -->
        <section id="comments-fixed-container">
            <div id="comments-fixed">
                <!-- Title of comments -->
                <div class="comments-title">
                    <h4>بخش نظرات (<?php echo get_comments_number(); ?>)</h4>
                    <!-- Close comments fixed -->
                    <button id="close-comments-fixed" class="button-no-style"><i class="fa-light fa-times"></i></button>
                </div>

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
            </div>
        </section>
    <?php endif; ?>
</main>