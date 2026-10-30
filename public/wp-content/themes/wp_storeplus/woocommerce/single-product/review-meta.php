<?php

/**
 * The template to display the reviewers meta data (name, verified owner, review date)
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/review-meta.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @wc-version 3.4.0
 * @author 	M-MALEKZADEH77
 */

defined('ABSPATH') || exit;

global $comment;
$verified = wc_review_is_from_verified_owner($comment->comment_ID);

if ('0' === $comment->comment_approved) { ?>

	<p class="irantheme-woocommerce-review-awaiting-approval-meta meta">
		<em class="irantheme-woocommerce-review-awaiting-approval">
			<?php esc_html_e('Your review is awaiting approval', 'woocommerce'); ?>
		</em>
	</p>

<?php } else { ?>
	<div class="irantheme-woocommerce-review-item-head">
		<div class="irantheme-woocommerce-review-item-head-info">
			<figure class="irantheme-woocommerce-review-item-head-info-gravatar gravatar">
				<?php

				$comment_id = $commentID; //$commentID your var

				$comment = get_comment($comment_id);

				$comment_author_id = $comment->user_id;

				// Get avatar url user
				$avatar_image_url = irantheme_get_user_avatar_url($comment_author_id);

				?>
				<!-- Avatar -->
				<div class="irantheme-woocommerce-profile-avatar">
					<img src="<?php echo esc_url_raw($avatar_image_url); ?>" alt="<?php echo esc_attr('آواتار کاربر'); ?>">
				</div>
				<?php
				/**
				 * The woocommerce_review_before hook
				 *
				 * @hooked woocommerce_review_display_gravatar - 10
				 */
				// do_action('woocommerce_review_before', $comment);
				?>
			</figure>
			<div class="irantheme-woocommerce-review-item-head-info-holder">
				<div class="irantheme-woocommerce-review-item-head-info-author">
					<strong class="irantheme-woocommerce-review-item-head-info-author-name"><?php comment_author(); ?> </strong>
					<?php
					if ('yes' === get_option('woocommerce_review_rating_verification_label') && $verified) {
						echo '<em class="irantheme-woocommerce-review-item-head-info-author-verified">' . esc_attr__('خریدار') . '</em> ';
					}
					?>
				</div>
				<div class="irantheme-woocommerce-review-item-head-info-rate">
					<?php
					/**
					 * The woocommerce_review_before_comment_meta hook.
					 *
					 * @hooked woocommerce_review_display_rating - 10
					 */
					do_action('woocommerce_review_before_comment_meta', $comment);
					?>
				</div>
			</div>
		</div>
		<div class="irantheme-woocommerce-review-item-head-meta">
			<time class="irantheme-woocommerce-review-item-head-meta-published-date" datetime="<?php echo esc_attr(get_comment_date('Y-m-d , H:i')); ?>"><span class="published-date"><?php echo esc_html(get_comment_date('Y-m-d')); ?></span></time>
		</div>
	</div>
<?php
}
