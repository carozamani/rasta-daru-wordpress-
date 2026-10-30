<?php

/**
 * The template for displaying Comments.
 *
 * The area of the page that contains comments and the comment form.
 *
 * @package Wordpress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */

use Irantheme_Dev\Irantheme_Section_Utils;

/*
 * If the current post is protected by a password and the visitor has not yet
 * entered the password we will return early without loading the comments.
 */

if (post_password_required())
    return;

// Get border radius range
$border_radius_range = get_option('irantheme_blog_visual_border_radius_range');
$border_radius_css = Irantheme_Section_Utils::get_border_radius_css($border_radius_range);
?>

<div id="comments-holder">
    <!-- Comment form -->
    <div class="post-box" style="<?php echo esc_attr($border_radius_css); ?>">
        <div class="post-content irantheme-input-form-fields white mt-4">
            <?php
            //Declare Vars
            $comment_send = __('ثبت دیدگاه', IRANTHEME_TEXTDOMAIN);
            $comment_reply = __('ارسال دیدگاه', IRANTHEME_TEXTDOMAIN);
            $comment_reply_to = __('پاسخ به', IRANTHEME_TEXTDOMAIN);

            $comment_author = __('نام *', IRANTHEME_TEXTDOMAIN);
            $comment_email = __('ایمیل *', IRANTHEME_TEXTDOMAIN);
            $comment_body = __('متن دیدگاه *', IRANTHEME_TEXTDOMAIN);
            $comment_url = __('سایت شما', IRANTHEME_TEXTDOMAIN);
            $comment_cookies_1 = __(' نظر شما در دست بررسی است.', IRANTHEME_TEXTDOMAIN);
            $comment_cookies_2 = __(' سیاست حفظ حریم خصوصی', IRANTHEME_TEXTDOMAIN);
            $comment_before_text = __('ایمیل شما منتشر نخواهد شد، ورودی های ضروری با (*) علامت گذاری شده اند.', IRANTHEME_TEXTDOMAIN);

            $comment_before = '<p class="text-description">' . $comment_before_text . '</p>';

            $comment_cancel = __('لغو پاسخ', IRANTHEME_TEXTDOMAIN);

            //Array
            $comments_args = array(
                //Define Fields
                'fields' => array(
                    // Author field
                    'author' => '
  <label for="author">' . esc_html($comment_author) . '</label>
  <input type="text" id="author" name="author" aria-required="true"></input>',
                    // Email Field
                    'email' => '
  <label for="email">' . esc_html($comment_email) . '</label>
  <input type="email" id="email" name="email"></input>',
                    // URL Field
                    'url' => '
  <label for="url">' . esc_html($comment_url) . '</label>
  <input type="url" id="url" name="url"></input>',
                    // Honeypot Field (hidden)
                    'irantheme_hp' => '
  <input type="text" name="irantheme_hp" id="irantheme_hp" value="" style="display:none !important; visibility:hidden !important;" autocomplete="off" tabindex="-1">',
                    'irantheme_time' => '
  <input type="hidden" name="irantheme_time" value="' . time() . '">',
                    // Cookies
                    'cookies' => '',
                ),
                // Change the title of send button
                'label_submit' => esc_html($comment_send),
                // Change the title of the reply section
                'title_reply' => esc_html($comment_reply),
                // Change the title of the reply section
                'title_reply_to' => esc_html($comment_reply_to),
                //Cancel Reply Text
                'cancel_reply_link' => esc_html($comment_cancel),
                // Redefine your own textarea (the comment body).
                'comment_field' => '
        <label for="comment">' . esc_html($comment_body) . '</label>
        <textarea id="comment" name="comment" aria-required="true" rows="12"></textarea>',
                //Message Before Comment
                'comment_notes_before' => esc_html($comment_before),
                // Remove "Text or HTML to be displayed after the set of comment fields".
                'comment_notes_after' => '',
                //Submit Button ID
                'id_submit' => esc_html('comment-submit'),
            );
            comment_form($comments_args);
            ?>
        </div>
    </div>

    <!-- Check have comment -->
    <?php if (have_comments()) : ?>

        <div class="post-box" style="<?php echo esc_attr($border_radius_css); ?>">
            <div class="comments-holder">
                <ul class="comments-list">
                    <?php
                    wp_list_comments(array(
                        'avatar_size' => 100,
                        'style'      => 'ul',
                        'short_ping' => true,
                        'callback' => 'irantheme_better_comments',
                        'reverse_top_level' => true,
                        // 'per_page' => 2
                    ));
                    ?>
                </ul><!-- .comment-list -->

                <?php
                // Are there comments to navigate through?
                if (get_comment_pages_count() > 1 && get_option('page_comments')) :
                ?>
                    <!-- Pagination comments list -->
                    <div class="irantheme-pagination">
                        <?php paginate_comments_links(); ?>
                    </div>
                <?php endif; // Check for comment navigation 
                ?>
            </div>
        </div>

    <?php endif; // have_comments() 
    ?>

</div><!-- #comments -->