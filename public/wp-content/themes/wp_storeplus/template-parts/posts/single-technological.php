<?php

use Irantheme_Dev\Irantheme_Common_Static;
use Irantheme_Dev\Irantheme_Page_Builder;
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

// Get border radius range
$border_radius_range = get_option('irantheme_blog_visual_border_radius_range');
$border_radius_css = Irantheme_Section_Utils::get_border_radius_css($border_radius_range);
?>

<main class="post-single-technological <?php echo esc_attr($base_color_class); ?>" style="<?php echo esc_attr($border_radius_css); ?>">
    <!-- Single post -->
    <section id="single-post-technological">
        <?php while (have_posts()) : the_post(); ?>
            <?php if (in_array('thumbnail', $post_details_checkbox_multi)): ?>
                <div class="irantheme-single-technological-thumbnail">
                    <?php if (has_post_thumbnail()) : ?>
                        <!-- Single thumbnail -->
                        <div class="irantheme-single-technological-thumbnail-img">
                            <img src="<?php the_post_thumbnail_url('full'); ?>" alt="تصویر مطلب" style="<?php echo esc_attr($border_radius_css); ?>">
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <div class="irantheme-single-technological-content" style="<?php echo esc_attr($border_radius_css); ?>">
                <!-- Single heading -->
                <div class="irantheme-single-technological-heading">
                    <h2><?php the_title(); ?></h2>
                </div>
                <div class="irantheme-single-technological-top">
                    <!-- Single author -->
                    <?php if (is_single()) : ?>
                        <?php if (in_array('author', $post_details_checkbox_multi)): ?>
                            <!-- Single author -->
                            <div class="irantheme-single-technological-top-author">
                                <div class="irantheme-single-technological-top-author-name">
                                    <?php echo get_avatar(get_the_author_meta('ID'), 100); ?>
                                    <?php esc_html_e('منتشر شده توسط : ') . the_author_posts_link(); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if (in_array('date', $post_details_checkbox_multi)): ?>
                        <!-- Date -->
                        <div class="irantheme-single-technological-top-date">
                            <span><?php echo get_the_date('j F Y'); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if (in_array('category', $post_details_checkbox_multi)): ?>
                        <!-- Category -->
                        <?php
                        $categories = get_the_category(Irantheme_Page_Builder::get_current_page_id());
                        $separator = ' - ';
                        $output = '';
                        if (!empty($categories)) : ?>
                            <div class="irantheme-single-technological-top-category">
                                <!-- Category -->
                                <?php
                                foreach ($categories as $category) {
                                    $output .= '<a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a>' . $separator;
                                }
                                echo trim($output, $separator);
                                ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <!-- Single text -->
                <div class="irantheme-single-text <?php echo esc_attr($base_color_class); ?>">
                    <?php the_content(); ?>
                </div>
                <?php
                if (!is_page()) :
                ?>
                    <?php if (in_array('tag', $post_details_checkbox_multi)): ?>
                        <?php if (has_tag()) : ?>
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

                    <!-- Single like share -->
                    <div class="single-like-share">
                        <?php if (is_single()) : ?>
                            <?php if (in_array('like', $post_details_checkbox_multi)): ?>
                                <?php
                                // Get post like html
                                irantheme_get_post_like(get_the_ID());
                                ?>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php if (in_array('share', $post_details_checkbox_multi)): ?>
                            <!-- Single share button -->
                            <button id="button-share-toggle-list" class="irantheme-btn-void single-share-button"><i class="fa-light fa-share"></i>اشتراک</button>
                            <!-- Single share list -->
                            <div class="single-share-list">
                                <a target="_blank" href="http://www.facebook.com/share.php?u=<?php echo get_permalink(); ?>"><i class="fa-brands fa-facebook-f"></i></a>
                                <a target="_blank" href="http://x.com/share?text=<?php echo get_the_title(); ?>&url=<?php echo get_permalink(); ?>"><i class="fa-brands fa-x-twitter"></i></a>
                                <a target="_blank" href="https://t.me/share/url?url=<?php echo get_permalink(); ?>&title=<?php echo get_the_title(); ?>"><i class="fa-brands fa-telegram"></i></a>
                                <a target="_blank" href="https://api.whatsapp.com/send?text=<?php echo get_the_title(); ?>:<?php the_permalink(); ?>"><i class="fa-brands fa-whatsapp"></i></a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Next & prev post -->
                    <div class="next-prev-post">
                        <!-- Next post button -->
                        <?php $next_post = get_next_post();
                        if ($next_post) :
                        ?>
                            <a href="<?php if (!empty($next_post)) : echo get_permalink($next_post->ID);
                                        else : echo '#';
                                        endif; ?>" class="next-post <?php if (empty($next_post)) echo 'disable'; ?>">
                                <div>
                                    <cite>مطلب بعدی</cite>
                                    <b><?php echo get_the_title($next_post->ID); ?></b>
                                </div>
                                <i class="fa-light fa-arrow-left"></i>
                            </a>
                        <?php endif; ?>
                        <!-- Prev post button -->
                        <?php $prev_post = get_previous_post();
                        if ($prev_post) :
                        ?>
                            <a href="<?php if (!empty($prev_post)) : echo get_permalink($prev_post->ID);
                                        else : echo '#';
                                        endif; ?>" class="prev-post <?php if (empty($prev_post)) echo 'disable'; ?>">
                                <i class="fa-light fa-arrow-right"></i>
                                <div>
                                    <cite>مطلب قبلی</cite>
                                    <b><?php echo get_the_title($prev_post->ID); ?></b>
                                </div>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php
                if (in_array('related-post', $post_details_checkbox_multi)):
                    // Get related posts
                    Irantheme_Archives::display_post_related($base_color_class, $border_radius_css);
                endif;
                ?>

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
        <?php endwhile; ?>
    </section>
</main>