<?php

use Irantheme_Dev\Irantheme_Common_Static;
use Irantheme_Dev\Irantheme_JSON_Helper;
use Irantheme_Dev\Irantheme_Section_Footer_Classic;
use Irantheme_Dev\Irantheme_Section_Footer_Copyright;
use Irantheme_Dev\Irantheme_Section_Footer_Master;
use Irantheme_Dev\Irantheme_Section_Footer_Nav;

if (!defined('ABSPATH')) exit;

if (!class_exists('Irantheme_Footer_Generator')) {

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
    class Irantheme_Footer_Generator
    {
        /**
         * Constructor
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        function __construct() {}


        /**
         * Get footer
         * @param string,string
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function display_footer()
        {
            // Get footer template checkbox
            $footer_template_checkbox = get_option('irantheme_footer_template_checkbox');
            if ($footer_template_checkbox == 'off') {
                // Get footer type
                $footer_type = get_option('irantheme_footer_type');
                if ($footer_type == 'technological') {
                    self::show_technological();
                } elseif ($footer_type == 'advanced') {
                    self::show_advanced();
                } elseif ($footer_type == 'luxurious') {
                    self::show_luxurious();
                }
            } else {
                // Get footer template data-ajax
                $footer_template_data_ajax = get_option('irantheme_footer_template_select');
                // Get footer template elements
                self::display_footer_template($footer_template_data_ajax);
            }
        }


        /**
         * Get footer template
         * @param string
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function display_footer_template($footer_template_data_ajax)
        {
            if ($footer_template_data_ajax) {
                // Fetch holder templates
                $templates_holder = get_option('irantheme_page_builder_templates');
                $templates_array = Irantheme_JSON_Helper::decode($templates_holder);
                if (!$templates_array) {
                    return;
                }
                foreach ($templates_array as $template) {
                    if ($template['data_ajax'] == $footer_template_data_ajax) {
                        $data_type = $template['data_type'];
                        $data_value = $template['data_value'];
                        $data_page = $template['data_page'];
                        $data_ajax = $template['data_ajax'];
                        switch ($data_value) {
                            case 'footer_classic':
                                Irantheme_Section_Footer_Classic::render($template['section_fields'], $data_type, $data_value, $data_page, $data_ajax);
                                break;

                            case 'footer_master':
                                Irantheme_Section_Footer_Master::render($template['section_fields'], $data_type, $data_value, $data_page, $data_ajax);
                                break;

                            case 'footer_nav':
                                Irantheme_Section_Footer_Nav::render($template['section_fields'], $data_type, $data_value, $data_page, $data_ajax);
                                break;

                            case 'footer_copyright':
                                Irantheme_Section_Footer_Copyright::render($template['section_fields'], $data_type, $data_value, $data_page, $data_ajax);
                                break;
                        }
                        return;
                    }
                }
            }
        }


        /**
         * Get footer technological
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        private static function show_technological()
        {
?>
            <!-- Footer -->
            <footer id="footer-technological">
                <!-- Headline -->
                <div class="headline">
                    <div class="irantheme-container-inner">
                        <div class="row">
                            <div class="col-md-3 py-4">
                                <!-- About -->
                                <div class="footer-heading">درباره</div>
                                <div class="footer-about">
                                    <p><?php echo esc_html(get_option('irantheme_footer_text_about')); ?></p>
                                    <a href="<?php echo esc_url(get_option('irantheme_footer_text_about_link')); ?>">مشاهده بیشتر<i class="fa-light fa-chevron-left"></i></a>
                                </div>
                            </div>
                            <div class="col-md-3 py-4">
                                <!-- Latest posts -->
                                <div class="footer-heading">آخرین مطالب</div>
                                <?php
                                $args = array(
                                    'posts_per_page' => 2,
                                    'post_type' => 'post'
                                );
                                $latest_blog_post = new WP_Query($args);
                                while ($latest_blog_post->have_posts()) : $latest_blog_post->the_post();
                                ?>
                                    <!-- List post item -->
                                    <div class="list-post-style">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <div class="list-post-style-img">
                                                <a href="<?php echo get_the_permalink(); ?>">
                                                    <img src="<?php the_post_thumbnail_url(); ?>" alt="تصویر پست">
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                        <div class="list-post-style-content">
                                            <h3><a href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title(); ?></a></h3>
                                            <span><?php echo get_the_date('j F Y'); ?></span>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                            <div class="col-md-3 py-4">
                                <!-- Latest comments -->
                                <div class="footer-heading">آخرین دیدگاه ها</div>
                                <?php
                                $latestCommentsQuery = new WP_Comment_Query();
                                $latestComments = $latestCommentsQuery->query(array(
                                    'number' => 3
                                ));
                                if ($latestComments) :
                                ?>
                                    <div class="footer-latest-comments">
                                        <ul>
                                            <?php
                                            foreach ($latestComments as $comment) :
                                            ?>
                                                <!-- Item -->
                                                <li>
                                                    <i class="fa-light fa-chevron-left"></i><span><?php echo get_comment_author($comment->comment_ID); ?></span> در <a href="<?php echo get_permalink($comment->comment_post_ID); ?>"><?php echo get_the_title($comment->comment_post_ID); ?></a>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-3 py-4">
                                <!-- Tags -->
                                <div class="footer-heading">برچسب ها</div>
                                <div class="list-tags">
                                    <?php
                                    // Get number of tags
                                    // $number_tags = 0;
                                    // if ( get_option( 'footer_number_tags' ) ) {
                                    //   $number_tags = get_option( 'footer_number_tags' );
                                    // } else {
                                    //   $number_tags = 30;
                                    // }
                                    $tags = get_tags(array(
                                        'smallest'                  => 10,
                                        'largest'                   => 10,
                                        'unit'                      => 'px',
                                        'number'                    => 20,
                                        'format'                    => 'flat',
                                        'separator'                 => " ",
                                        'orderby'                   => 'count',
                                        'order'                     => 'DESC',
                                        'show_count'                => 1,
                                        'echo'                      => false
                                    ));
                                    foreach ($tags as $tag) {
                                        echo '<a href="' . get_term_link($tag) . '">' . esc_html($tag->name) . '</a>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Copyright -->
                <div class="copyright-technological">
                    <div class="irantheme-container-inner">
                        <!-- Social -->
                        <?php Irantheme_Common_Static::get_social_media(); ?>
                        <p>
                            <?php echo get_option('irantheme_footer_text_copyright'); ?>
                        </p>
                    </div>
                </div>
            </footer>
        <?php
        }


        /**
         * Get footer advanced
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        private static function show_advanced()
        {
        ?>
            <!-- Footer -->
            <footer id="footer-advanced">
                <div class="irantheme-container-inner">
                    <!-- Social media -->
                    <?php Irantheme_Common_Static::get_social_media(); ?>
                    <!-- Copyright -->
                    <div class="copyright-advanced">
                        <?php echo get_option('irantheme_footer_text_copyright'); ?>
                    </div>
                </div>
            </footer>
            <?php
        }


        /**
         * Get footer luxurious
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        private static function show_luxurious()
        {
            $latestCommentsQuery = new WP_Comment_Query();
            $latestComments = $latestCommentsQuery->query(array(
                'number' => 6
            ));

            if ($latestComments) :
            ?>
                <?php $latest_comments_bg = wp_get_attachment_image_src(get_option('irantheme_footer_latest_comments_bg'), 'full'); ?>
                <!-- Latest comments ################################### -->
                <section id="latest-comments" style="background-image: url('<?php echo esc_url($latest_comments_bg[0]); ?>');">
                    <div class="irantheme-container-inner">
                        <!-- Comments list -->
                        <div class="latest-comments-list">
                            <!-- Lable of comments -->
                            <small id="label-of-comments">آخرین دیدگاه ها</small>
                            <div class="comments-swiper-unique">
                                <!-- Swiper -->
                                <div class="swiper comments-swiper">
                                    <div class="swiper-wrapper">
                                        <?php
                                        foreach ($latestComments as $comment) :
                                        ?>
                                            <!-- Comment item -->
                                            <div class="swiper-slide">
                                                <div class="comment-item">
                                                    <?php echo get_avatar($comment->comment_author_email, 80); ?>
                                                    <div>
                                                        <cite><?php echo get_comment_author($comment->comment_ID); ?></cite>
                                                        <p><?php echo wp_trim_words(apply_filters('get_comment_text', $comment->comment_content), 30); ?></p>
                                                        <a href="<?php echo get_permalink($comment->comment_post_ID); ?>">مشاهده</a>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="swiper-pagination"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            <?php endif; ?>

            <!-- Footer ###################################### -->
            <footer id="footer-luxurious">
                <div class="irantheme-container-inner">
                    <div class="row">
                        <div class="col-lg-6 offset-lg-6">
                            <!-- Heading footer -->
                            <div class="heading-footer">
                                <b><?php bloginfo('name'); ?></b>
                                <em><?php bloginfo('description'); ?></em>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <?php if (get_option('irantheme_footer_text_copyright')) : ?>
                                <!-- Copy right -->
                                <div class="copyright">
                                    <?php echo get_option('irantheme_footer_text_copyright'); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-lg-6">
                            <!-- Social -->
                            <?php Irantheme_Common_Static::get_social_media(); ?>
                        </div>
                    </div>
                </div>
            </footer>
<?php
        }
    }
}
