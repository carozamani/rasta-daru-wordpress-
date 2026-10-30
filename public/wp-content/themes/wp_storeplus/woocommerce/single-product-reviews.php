<?php

/**
 * Display single product reviews (comments)
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product-reviews.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @wc-version 9.6.0
 * @author	M-MALEKZADEH77
 */

use Irantheme_Dev\Irantheme_Common_Static;

defined('ABSPATH') || exit;

global $product;

if (!comments_open()) {
	return;
}

?>
<div id="reviews" class="irantheme-woocommerce-single-product-reviews woocommerce-Reviews">
	<div id="comments" class="irantheme-woocommerce-single-product-reviews-wrapper">
		<h2 class="irantheme-single-product-content-tabs-panel-title">امتیاز و دیدگاه کاربران</h2>

		<?php if (have_comments()) : ?>
			<!-- Rate box -->
			<?php
			$rating_count = $product->get_rating_count();
			$review_count = $product->get_review_count();
			$average      = $product->get_average_rating();
			?>
			<div class="row irantheme-woocommerce-single-product-reviews-row gx-5">
				<div class="irantheme-woocommerce-single-product-reviews-SummaryBox-col col-lg-3">
					<div class="irantheme-woocommerce-single-product-reviews-SummaryBox-wrapper">
						<div class="irantheme-woocommerce-single-product-reviews-SummaryBox-average">
							<div class="irantheme-woocommerce-single-product-reviews-SummaryBox-average-rate">
								<div class="irantheme-woocommerce-single-product-reviews-SummaryBox-average-rate-star"> <?php echo wc_get_rating_html($average, $rating_count); ?></div>
								<div class="irantheme-woocommerce-single-product-reviews-SummaryBox-average-rate-value"><?php echo esc_html(round($average, 2)); ?><span>از 5</span></div>
							</div>
							<div class="irantheme-woocommerce-single-product-reviews-SummaryBox-average-desc">بر اساس <?php echo esc_html($review_count); ?> بررسی</div>
						</div>
						<div class="irantheme-woocommerce-single-product-reviews-SummaryBox-details">
							<table class="irantheme-woocommerce-single-product-reviews-SummaryBox-details-table">
								<tbody>
									<tr>
										<td class="star"><?php echo esc_html(5); ?></td>
										<td class="bar">
											<div class="rate-range">
												<div class="rate-range-bar" style="width: <?php echo ($product->get_rating_count(5) / $rating_count) * 100 ?>%;"></div>
											</div>
										</td>
										<td class="percentage">
											<?php echo esc_html(round(($product->get_rating_count(5) / $rating_count) * 100), 1) . '%'; ?>
										</td>
									</tr>
									<tr>
										<td class="star"><?php echo esc_html(4); ?></td>
										<td class="bar">
											<div class="rate-range">
												<div class="rate-range-bar" style="width: <?php echo ($product->get_rating_count(4) / $rating_count) * 100 ?>%;"></div>
											</div>
										</td>
										<td class="percentage"> <?php echo esc_html(round(($product->get_rating_count(4) / $rating_count) * 100), 1) . '%'; ?></td>
									</tr>
									<tr>
										<td class="star"><?php echo esc_html(3); ?></td>
										<td class="bar">
											<div class="rate-range">
												<div class="rate-range-bar" style="width: <?php echo ($product->get_rating_count(3) / $rating_count) * 100 ?>%;"></div>
											</div>
										</td>
										<td class="percentage"> <?php echo esc_html(round(($product->get_rating_count(3) / $rating_count) * 100), 1) . '%'; ?></td>
									</tr>
									<tr>
										<td class="star"><?php echo esc_html(2); ?></td>
										<td class="bar">
											<div class="rate-range">
												<div class="rate-range-bar" style="width: <?php echo ($product->get_rating_count(2) / $rating_count) * 100 ?>%;"></div>
											</div>
										</td>
										<td class="percentage"> <?php echo esc_html(round(($product->get_rating_count(2) / $rating_count) * 100), 1) . '%'; ?></td>
									</tr>
									<tr>
										<td class="star"><?php echo esc_html(1); ?></td>
										<td class="bar">
											<div class="rate-range">
												<div class="rate-range-bar" style="width: <?php echo ($product->get_rating_count(1) / $rating_count) * 100 ?>%;"></div>
											</div>
										</td>
										<td class="percentage"> <?php echo esc_html(round(($product->get_rating_count(1) / $rating_count) * 100), 1) . '%'; ?></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<div class="irantheme-woocommerce-single-product-reviews-add-comment">
						<button name="submit_comment" class="open-add-comment-modal-btn <?php echo get_single_product_button_styles(); ?>" data-product-id="<?php echo $product_id; ?>">ثبــت دیدگاه<i class="fa-light fa-money-check-pen"></i></button>
						<h4 class="add-comment-submit-desc">شما هم درباره این کالا دیدگاه ثبت کنید.</h4>

					</div>
					<div class="product-add-comment-modal irantheme-popup-modal" id="irantheme_add_comment_modal" style="display: none;">
						<div class="product-add-comment-modal-wrapper irantheme-popup-modal-wrapper">
							<div class="topbar">
								<h4 class="heading"><?php _e('ثبت دیدگاه جدید'); ?></h4>
								<span class="close"><i class="fa-light fa-close"></i></span>
							</div>
							<div class="irantheme-divider"></div>
							<div class="add-comment-content">
								<?php if (get_option('woocommerce_review_rating_verification_required') === 'no' || wc_customer_bought_product('', get_current_user_id(), $product->get_id())) : ?>
									<p class="add-comment-content-product-desc"><?php _e('نظر خود را در مورد این کالا با کاربران دیگر به اشتراک بگذارید.'); ?></php>
									</p>
									<div class="add-comment-content-product-details">
										<div class="add-comment-content-product-details-image">
											<img src="<?php echo esc_attr(wp_get_attachment_url(get_post_thumbnail_id($product->get_id()), 'medium')); ?>" alt="<?php _e('تصویر محصول'); ?>" srcset="<?php echo esc_attr(wp_get_attachment_image_srcset(get_post_thumbnail_id($product->get_id()), 'full')); ?>" sizes="<?php echo wp_get_attachment_image_sizes(get_post_thumbnail_id($product->get_id()), 'full') ?>">
										</div>
										<div class="add-comment-content-product-details-name">
											<?php the_title('<p class="add-comment-content-product-details-name-title">', '</p>'); ?>
											<?php if ($product->get_short_description()): ?>
												<div class="add-comment-content-product-details-name-desc">
													<?php echo $product->get_short_description(); ?>
												</div>
											<?php endif; ?>
										</div>
									</div>
									<div class="add-comment-content-wrapper">
										<div id="review_form_wrapper" class="irantheme-woocommerce-single-product-review-form-wrapper">
											<div id="review_form" class="irantheme-review_form">
												<?php
												$commenter    = wp_get_current_commenter();
												$comment_form = array(
													/* translators: %s is product title */
													'title_reply'         => have_comments() ? esc_html__('Add a review', 'woocommerce') : sprintf(esc_html__('Be the first to review &ldquo;%s&rdquo;', 'woocommerce'), get_the_title()),
													/* translators: %s is product title */
													'title_reply_to'      => esc_html__('Leave a Reply to %s', 'woocommerce'),
													'title_reply_before'  => '<span id="reply-title" class="comment-reply-title">',
													'title_reply_after'   => '</span>',
													'comment_notes_after' => '',
													'label_submit'        => esc_html__('Submit', 'woocommerce'),
													'logged_in_as'        => '',
													'comment_field'       => '',
												);

												$name_email_required = (bool) get_option('require_name_email', 1);
												$fields              = array(
													'author' => array(
														'label'    => __('Name', 'woocommerce'),
														'type'     => 'text',
														'value'    => $commenter['comment_author'],
														'required' => $name_email_required,
														'autocomplete' => 'name',
													),
													'email'  => array(
														'label'    => __('Email', 'woocommerce'),
														'type'     => 'email',
														'value'    => $commenter['comment_author_email'],
														'required' => $name_email_required,
														'autocomplete' => 'email',
													),
												);

												$comment_form['fields'] = array();

												foreach ($fields as $key => $field) {
													$field_html  = '<p class="irantheme-comment-field irantheme-comment-form-' . esc_attr($key) . ' comment-form-' . esc_attr($key) . '">';
													$field_html .= '<label for="' . esc_attr($key) . '">' . esc_html($field['label']);

													if ($field['required']) {
														$field_html .= '&nbsp;<span class="required">*</span>';
													}

													$field_html .= '</label><input id="' . esc_attr($key) . '" name="' . esc_attr($key) . '" type="' . esc_attr($field['type']) . '" autocomplete="' . esc_attr($field['autocomplete']) . '" value="' . esc_attr($field['value']) . '" size="30" ' . ($field['required'] ? 'required' : '') . ' /></p>';

													$comment_form['fields'][$key] = $field_html;
												}

												$account_page_url = wc_get_page_permalink('myaccount');
												if ($account_page_url) {
													/* translators: %s opening and closing link tags respectively */
													$comment_form['must_log_in'] = '<p class="irantheme-must-log-in must-log-in">' . sprintf(esc_html__('You must be %1$slogged in%2$s to post a review.', 'woocommerce'), '<a href="' . esc_url($account_page_url) . '">', '</a>') . '</p>';
												}

												if (wc_review_ratings_enabled()) {
													$comment_form['comment_field'] = '<div class="irantheme-comment-form-rating comment-form-rating"><label for="rating">' . esc_html__('Your rating', 'woocommerce') . (wc_review_ratings_required() ? '&nbsp;<span class="required">*</span>' : '') . '</label><select name="rating" id="rating" required>
									<option value="">' . esc_html__('Rate&hellip;', 'woocommerce') . '</option>
									<option value="5">' . esc_html__('Perfect', 'woocommerce') . '</option>
									<option value="4">' . esc_html__('Good', 'woocommerce') . '</option>
									<option value="3">' . esc_html__('Average', 'woocommerce') . '</option>
									<option value="2">' . esc_html__('Not that bad', 'woocommerce') . '</option>
									<option value="1">' . esc_html__('Very poor', 'woocommerce') . '</option>
									</select></div>';
												}

												$comment_form['comment_field'] .= '<p class="irantheme-comment-field  irantheme-comment-form-comment comment-form-comment"><label for="comment">' . esc_html__('Your review', 'woocommerce') . '&nbsp;<span class="required">*</span></label><textarea id="comment" name="comment" cols="45" rows="8" required></textarea></p>';
												$comment_form['class_submit'] = 'submit ' . get_single_product_button_styles();
												$comment_form['label_submit'] = 'ثبت دیدگاه';
												comment_form(apply_filters('woocommerce_product_review_comment_form_args', $comment_form));
												?>
											</div>
										</div>
									</div>
								<?php else : ?>
									<div class="add-comment-content-attention">
										<p class="woocommerce-verification-required add-comment-content-attention-label"><?php esc_html_e('Only logged in customers who have purchased this product may leave a review.', 'woocommerce'); ?></p>
										<a href="<?php echo wc_get_page_permalink('myaccount'); ?>" name="login_user_btn" class="add-comment-content-attention-login-btn <?php echo get_single_product_button_styles(); ?>">ورود به حساب کاربری</a>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
				<div class="irantheme-woocommerce-single-product-reviews-commentlist-col col-lg-9">
					<ol class="irantheme-woocommerce-single-product-commentlist commentlist">
						<?php wp_list_comments(apply_filters('woocommerce_product_review_list_args', array('callback' => 'woocommerce_comments'))); ?>
					</ol>

					<?php
					if (get_comment_pages_count() > 1 && get_option('page_comments')) :
						echo '<nav class="woocommerce-pagination">';
						paginate_comments_links(
							apply_filters(
								'woocommerce_comment_pagination_args',
								array(
									'prev_text' => is_rtl() ? '&rarr;' : '&larr;',
									'next_text' => is_rtl() ? '&larr;' : '&rarr;',
									'type'      => 'list',
								)
							)
						);
						echo '</nav>';
					endif;
					?>
				</div>
			</div>
		<?php else : ?>
			<div class="irantheme-wc-no-reviews">
				<i class="fa-light fa-messages"></i>
				<p class="woocommerce-noreviews title"><?php esc_html_e('There are no reviews yet.', 'woocommerce'); ?></p>
				<p class="desc">اولین نفری باشید که برای این محصول دیدگاه ارسال می کند.</p>
				<button class="btn open-add-comment-modal-btn <?php echo get_single_product_button_styles(); ?>" data-product-id="' . $product_id . '">ثــبت دیدگاه جدید</button>
			</div>
			<div class="product-add-comment-modal irantheme-popup-modal" id="irantheme_add_comment_modal" style="display: none;">
				<div class="product-add-comment-modal-wrapper irantheme-popup-modal-wrapper">
					<div class="topbar">
						<h4 class="heading"><?php _e('ثبت دیدگاه جدید'); ?></h4>
						<span class="close"><i class="fa-light fa-close"></i></span>
					</div>
					<div class="irantheme-divider"></div>
					<div class="add-comment-content">
						<?php if (get_option('woocommerce_review_rating_verification_required') === 'no' || wc_customer_bought_product('', get_current_user_id(), $product->get_id())) : ?>
							<p class="add-comment-content-product-desc"><?php _e('نظر خود را در مورد این کالا با کاربران دیگر به اشتراک بگذارید.'); ?></php>
							</p>
							<div class="add-comment-content-product-details">
								<div class="add-comment-content-product-details-image">
									<img src="<?php echo esc_attr(wp_get_attachment_url(get_post_thumbnail_id($product->get_id()), 'medium')); ?>" alt="<?php _e('تصویر محصول'); ?>" srcset="<?php echo esc_attr(wp_get_attachment_image_srcset(get_post_thumbnail_id($product->get_id()), 'full')); ?>" sizes="<?php echo wp_get_attachment_image_sizes(get_post_thumbnail_id($product->get_id()), 'full') ?>">
								</div>
								<div class="add-comment-content-product-details-name">
									<?php the_title('<p class="add-comment-content-product-details-name-title">', '</p>'); ?>
									<?php if ($product->get_short_description()): ?>
										<div class="add-comment-content-product-details-name-desc">
											<?php echo $product->get_short_description(); ?>
										</div>
									<?php endif; ?>
								</div>
							</div>
							<div class="add-comment-content-wrapper">
								<div id="review_form_wrapper" class="irantheme-woocommerce-single-product-review-form-wrapper">
									<div id="review_form" class="irantheme-review_form">
										<?php
										$commenter    = wp_get_current_commenter();
										$comment_form = array(
											/* translators: %s is product title */
											'title_reply'         => have_comments() ? esc_html__('Add a review', 'woocommerce') : sprintf(esc_html__('Be the first to review &ldquo;%s&rdquo;', 'woocommerce'), get_the_title()),
											/* translators: %s is product title */
											'title_reply_to'      => esc_html__('Leave a Reply to %s', 'woocommerce'),
											'title_reply_before'  => '<span id="reply-title" class="comment-reply-title">',
											'title_reply_after'   => '</span>',
											'comment_notes_after' => '',
											'label_submit'        => esc_html__('Submit', 'woocommerce'),
											'logged_in_as'        => '',
											'comment_field'       => '',
										);

										$name_email_required = (bool) get_option('require_name_email', 1);
										$fields              = array(
											'author' => array(
												'label'    => __('Name', 'woocommerce'),
												'type'     => 'text',
												'value'    => $commenter['comment_author'],
												'required' => $name_email_required,
												'autocomplete' => 'name',
											),
											'email'  => array(
												'label'    => __('Email', 'woocommerce'),
												'type'     => 'email',
												'value'    => $commenter['comment_author_email'],
												'required' => $name_email_required,
												'autocomplete' => 'email',
											),
										);

										$comment_form['fields'] = array();

										foreach ($fields as $key => $field) {
											$field_html  = '<p class="irantheme-comment-field irantheme-comment-form-' . esc_attr($key) . ' comment-form-' . esc_attr($key) . '">';
											$field_html .= '<label for="' . esc_attr($key) . '">' . esc_html($field['label']);

											if ($field['required']) {
												$field_html .= '&nbsp;<span class="required">*</span>';
											}

											$field_html .= '</label><input id="' . esc_attr($key) . '" name="' . esc_attr($key) . '" type="' . esc_attr($field['type']) . '" autocomplete="' . esc_attr($field['autocomplete']) . '" value="' . esc_attr($field['value']) . '" size="30" ' . ($field['required'] ? 'required' : '') . ' /></p>';

											$comment_form['fields'][$key] = $field_html;
										}

										$account_page_url = wc_get_page_permalink('myaccount');
										if ($account_page_url) {
											/* translators: %s opening and closing link tags respectively */
											$comment_form['must_log_in'] = '<p class="irantheme-must-log-in must-log-in">' . sprintf(esc_html__('You must be %1$slogged in%2$s to post a review.', 'woocommerce'), '<a href="' . esc_url($account_page_url) . '">', '</a>') . '</p>';
										}

										if (wc_review_ratings_enabled()) {
											$comment_form['comment_field'] = '<div class="irantheme-comment-form-rating comment-form-rating"><label for="rating">' . esc_html__('Your rating', 'woocommerce') . (wc_review_ratings_required() ? '&nbsp;<span class="required">*</span>' : '') . '</label><select name="rating" id="rating" required>
									<option value="">' . esc_html__('Rate&hellip;', 'woocommerce') . '</option>
									<option value="5">' . esc_html__('Perfect', 'woocommerce') . '</option>
									<option value="4">' . esc_html__('Good', 'woocommerce') . '</option>
									<option value="3">' . esc_html__('Average', 'woocommerce') . '</option>
									<option value="2">' . esc_html__('Not that bad', 'woocommerce') . '</option>
									<option value="1">' . esc_html__('Very poor', 'woocommerce') . '</option>
									</select></div>';
										}

										$comment_form['comment_field'] .= '<p class="irantheme-comment-field  irantheme-comment-form-comment comment-form-comment"><label for="comment">' . esc_html__('Your review', 'woocommerce') . '&nbsp;<span class="required">*</span></label><textarea id="comment" name="comment" cols="45" rows="8" required></textarea></p>';
										$comment_form['class_submit'] = 'submit ' . get_single_product_button_styles();
										$comment_form['label_submit'] = 'ثبت دیدگاه';
										comment_form(apply_filters('woocommerce_product_review_comment_form_args', $comment_form));
										?>
									</div>
								</div>
							</div>
						<?php else : ?>
							<div class="add-comment-content-attention">
								<?php if (!is_user_logged_in()): ?>
									<p class="woocommerce-verification-required add-comment-content-attention-label"><?php esc_html_e('فقط کاربرانی که این محصول را خریداری کرده اند و وارد سیستم شده اند میتوانند برای این محصول دیدگاه ارسال کنند.'); ?></p>
									<a href="<?php echo wc_get_page_permalink('myaccount'); ?>" name="login_user_btn" class="add-comment-content-attention-login-btn <?php echo get_single_product_button_styles(); ?>">ورود به حساب کاربری</a>
								<?php else: ?>
									<p class="woocommerce-verification-required add-comment-content-attention-label"><?php esc_html_e('فقط کاربرانی که این محصول را خریداری کرده اند میتوانند برای این محصول دیدگاه ارسال کنند.'); ?></p>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</div>

	<div class="clear"></div>
</div>