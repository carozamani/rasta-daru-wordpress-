<?php
// Get post post details

use Irantheme_Dev\Irantheme_Common_Static;
use Irantheme_Dev\Irantheme_Section_Utils;

// Get base color
$base_color = get_option('irantheme_blog_base_color_select');
// Set base color class
$base_color_class = Irantheme_Common_Static::get_base_color_class($base_color);

$post_details_checkbox_multi = get_option('irantheme_single_content_post_details_checkbox_multi');
$post_details_checkbox_multi = explode(',', $post_details_checkbox_multi);

// Get details color
$details_color = get_option('irantheme_blog_visual_color_select');

// Get border radius range
$border_radius_range = get_option('irantheme_blog_visual_border_radius_range');
$border_radius_css = Irantheme_Section_Utils::get_border_radius_css($border_radius_range);
?>
<main class="post-single-luxurious <?php echo esc_attr($base_color_class); ?>">
    <?php while (have_posts()) : the_post(); ?>
        <?php
        // Get all category of posts
        $categories = get_the_category();
        // Get post format
        $post_format = get_post_format();

        // Get gallery post, (true, false) -> related to html output or data
        $gallery_post = get_post_gallery(get_the_ID(), false);

        // Init url video
        $video_url =  wp_get_attachment_url(get_the_ID());
        $videoElement = wp_video_shortcode(array('src' => $video_url));

        if ($post_format == 'gallery' && $gallery_post) :
        ?>

            <!-- Single gallery overlay -->
            <div class="single-gallery-overlay">
                <div class="irantheme-container-inner">
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-8 col-sm-12 mx-auto">
                            <!-- Swiper -->
                            <div class="swiper swiper-gallery-overlay">
                                <div class="swiper-wrapper">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="swiper-slide"><img src="<?php the_post_thumbnail_url('large'); ?>" alt="Image gallery"></div>
                                    <?php endif;
                                    foreach ($gallery_post['src'] as $gallery_src) : ?>
                                        ?>
                                        <div class="swiper-slide"><img src="<?php echo esc_url($gallery_src); ?>" alt="Image gallery"></div>
                                    <?php endforeach; ?>
                                </div>
                                <?php
                                // Slider pagination & arrow
                                Irantheme_Section_Utils::get_slider_arrow_pagination(true, true, true, 'white', $border_radius_range, 'bottom', 'center');
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Close gallery overlay -->
                <span id="close-gallery-overlay"><i class="fa-light fa-times"></i></span>
            </div>

        <?php endif; ?>

        <!-- Content #####################3 -->
        <section id="content">
            <!-- Single ############################### -->
            <main id="single">
                <!-- Single post -->
                <article class="single-post" style="<?php echo esc_attr($border_radius_css); ?>">
                    <div class="row">
                        <?php
                        $bootstrap_col = 'col-lg-6';
                        if (is_page() || !in_array('thumbnail', $post_details_checkbox_multi)) {
                            $bootstrap_col = 'col-lg-12';
                        }
                        ?>
                        <?php
                        if (!is_page()) :
                        ?>
                            <div class="<?php echo esc_attr($bootstrap_col); ?>">
                                <!-- Single gallery -->
                                <div class="single-gallery <?php if ($post_format == 'gallery' && $gallery_post) {
                                                                echo esc_attr('active');
                                                            } ?>">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <?php if (in_array('thumbnail', $post_details_checkbox_multi)): ?>
                                            <!-- Single gallery main img -->
                                            <div class="single-gallery-img">
                                                <img src="<?php the_post_thumbnail_url('large'); ?>" alt="Image gallery" style="<?php echo esc_attr($border_radius_css); ?>">
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <?php
                                    if ($post_format == 'gallery' && $gallery_post) :
                                    ?>
                                        <!-- Single gallery list img -->
                                        <ul class="single-gallery-list">
                                            <?php
                                            $counterDataGallery = 1;
                                            foreach ($gallery_post['src'] as $gallery_src) : ?>
                                                <li><img src="<?php echo esc_url($gallery_src); ?>" data-gallery="<?php echo esc_attr($counterDataGallery); ?>" alt="Image gallery"></li>
                                            <?php
                                                $counterDataGallery++;
                                            endforeach;
                                            ?>
                                        </ul>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="<?php echo esc_attr($bootstrap_col); ?>">
                            <!-- Single content -->
                            <div class="single-content">
                                <?php
                                if (!is_page()) :
                                ?>
                                    <!-- Single heading -->
                                    <div class="single-heading">
                                        <h2><?php the_title(); ?></h2>
                                    </div>
                                <?php endif; ?>
                                <!-- Single text -->
                                <div class="irantheme-single-text <?php echo esc_attr($base_color_class); ?>"><?php the_content(); ?></div>
                                <?php if (in_array('like', $post_details_checkbox_multi)): ?>
                                    <!-- Post like -->
                                    <div class="post-like">
                                        <?php
                                        // Get post like html
                                        irantheme_get_post_like(get_the_ID());
                                        ?>
                                    </div>
                                <?php endif; ?>
                                <?php
                                if (!is_page()) :
                                ?>
                                    <!-- Single info -->
                                    <div class="single-info">
                                        <ul>
                                            <?php if (in_array('category', $post_details_checkbox_multi)): ?>
                                                <?php if (has_category()) : ?>
                                                    <!-- Category & date -->
                                                    <li>
                                                        <b>دسته بندی</b>
                                                        <span>
                                                            <?php
                                                            foreach ($categories as $category) {
                                                                echo '<a href="' . esc_url(get_category_link($category->term_id)) . '" alt="' . esc_attr(sprintf(__('View all posts in %s'), $category->name)) . '">' . __($category->name) . '</a>';
                                                            }
                                                            ?>
                                                        </span>
                                                    </li>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                            <?php if (in_array('date', $post_details_checkbox_multi)): ?>
                                                <li>
                                                    <b>تاریخ انتشار</b>
                                                    <span><?php echo get_the_date('j F Y'); ?></span>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                        <!-- Author & Share -->
                                        <ul>
                                            <?php if (in_array('author', $post_details_checkbox_multi)): ?>
                                                <li>
                                                    <b>نویسنده</b>
                                                    <span>
                                                        <?php echo get_the_author_posts_link(); ?>
                                                    </span>
                                                </li>
                                            <?php endif; ?>
                                            <?php if (in_array('share', $post_details_checkbox_multi)): ?>
                                                <li>
                                                    <b>اشتراک گذاری</b>
                                                    <span class="single-share">
                                                        <!-- Facebook -->
                                                        <a href="https://www.facebook.com/sharer?u=<?php the_permalink(); ?>;?t=<?php the_title(); ?>;" title="اشتراک گذاری در فیس بوک" target="_blank"><i class="fa-brands fa-facebook-f"></i></a>
                                                        <!-- Twitter -->
                                                        <a href="http://x.com/share?url=<?php the_title(); ?><?php the_permalink(); ?>" title="اشتراک گذاری در ایکس" target="_blank"><i class="fa-brands fa-x-twitter"></i></a>
                                                        <!-- Linkedin -->
                                                        <a href="http://www.linkedin.com/shareArticle?mini=true?url=<?php the_permalink() ?>?title=<?php the_title(); ?>?summary=?source=<?php bloginfo('name'); ?>" title="اشتراک گذاری در لینکدین" target="_blank"><i class="fa-brands fa-linkedin"></i></a>
                                                        <!-- Whatsapp -->
                                                        <a href="whatsapp://send?text=<?php the_title(); ?><?php the_permalink(); ?>" title="اشتراک گذاری در واتس آپ" target="_blank"><i class="fa-brands fa-whatsapp"></i></a>
                                                        <!-- Telegram -->
                                                        <a href="https://t.me/share/url?url=<?php the_title(); ?><?php the_permalink(); ?>" title="اشتراک گذاری در تلگرام" target="_blank"><i class="fa-brands fa-telegram"></i></a>
                                                    </span>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                        <?php if (in_array('tag', $post_details_checkbox_multi)): ?>
                                            <?php if (has_tag()) : ?>
                                                <!-- Labels -->
                                                <ul class="single-labels">
                                                    <li>
                                                        <b>برچسب ها</b>
                                                        <span>
                                                            <?php
                                                            $tags = get_the_tags();
                                                            if ($tags) {
                                                                foreach ($tags as $tag) {
                                                                    echo '<h3><a href="' . get_tag_link($tag->term_id) . '">' . $tag->name . '</a></h3>';
                                                                }
                                                            }
                                                            ?>
                                                        </span>
                                                    </li>
                                                </ul>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </article>
            </main>
        </section>

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

    <?php endwhile; ?>
</main>