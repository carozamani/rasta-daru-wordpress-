<?php

/**
 * The Template for displaying downloadable products
 * @author M-MALEKZADEH77
 * @version 1.0.0
 */

use Irantheme_Dev\Irantheme_Common_Static;
use Irantheme_Dev\Irantheme_Section_Service;
use Irantheme_Dev\Irantheme_Section_Woocommerce;
use Irantheme_Dev\Irantheme_Color_Static;

?>
<div id="product-<?php the_ID(); ?>" class="irantheme-single-product-wrapper irantheme-downloadable-product">
    <div class="irantheme-single-product-main-content-wrapper row">
        <div class="irantheme-single-product-main-content col-lg-9">
            <div class="row">
                <div class="product-downloadable-image col-lg-12 irantheme-common-magnifiable-images">
                    <div class="product-downloadable-heading">
                        <?php the_title('<h1 class="product-title">', '</h1>'); ?>
                        <?php
                        $product_badges = get_post_meta($product_id, 'irantheme_custom_field_product_detail_badge', true);
                        $product_badge_array = explode(',', $product_badges);
                        $product_badge_list = array(
                            'has_original' => 'کالای اصل',
                            'has_unoriginal' => 'کالای غیراصل',
                            'has_warranty' => 'دارای گارانتی',
                            'has_installments' => 'قابل خرید بصورت اقساطی'
                        );
                        ?>
                        <?php if ($product_badges != ''): ?>
                            <ul class="product-details-badge">
                                <?php foreach ($product_badge_array as $badge_key): ?>
                                    <?php if (array_key_exists($badge_key, $product_badge_list)): ?>
                                        <li class="product-details-badge-item <?php echo esc_attr($badge_key); ?>"><?php _e($product_badge_list[$badge_key]); ?></li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                    <div class="product-downloadable-image-wrapper">
                        <?php if ($product_thumbnail): ?>
                            <?php
                            // Get new badge option
                            $new_badge_checkbox = get_post_meta($product_id, 'irantheme_custom_field_product_new_badge_checkbox', true);
                            $new_badge_end_date = get_post_meta($product_id, 'irantheme_custom_field_product_new_badge_date', true);
                            $current_date = current_time('Y-m-d');

                            $input_timestamp = strtotime($new_badge_end_date);
                            $current_timestamp = strtotime($current_date);

                            $is_new_badge_valid = false;

                            if (!empty($new_badge_end_date)) {
                                if ($current_timestamp <= $input_timestamp) {
                                    $is_new_badge_valid = true;
                                }
                            } else {
                                $is_new_badge_valid = true;
                            }
                            ?>
                            <div class="product-downloadable-image-hero">
                                <?php if (($new_badge_checkbox == 'on' && $is_new_badge_valid)): ?>
                                    <span class="new-product-ribbon">جدید</span>
                                <?php endif; ?>
                                <div class="product-downloadable-image-hero-wrapper">
                                    <div class="product-downloadable-image-hero-item wc-thumbnail-image irantheme-common-media-gallery-item" data-item-id="<?php echo esc_attr($item_index); ?>" data-src="<?php echo esc_attr(wp_get_attachment_url(get_post_thumbnail_id($product_id), 'medium')); ?>" data-srcset="<?php echo esc_attr(wp_get_attachment_image_srcset(get_post_thumbnail_id($product_id), 'full')); ?>" data-sizes="<?php echo wp_get_attachment_image_sizes(get_post_thumbnail_id($product_id), 'full') ?>">
                                        <img class="irantheme-zoomImg-main-img" src="<?php echo esc_attr(wp_get_attachment_url(get_post_thumbnail_id($product_id), 'medium')); ?>" width="1050" height="520" alt="<?php _e('تصویر محصول'); ?>" srcset="<?php echo esc_attr(wp_get_attachment_image_srcset(get_post_thumbnail_id($product_id), 'full')); ?>" sizes="<?php echo wp_get_attachment_image_sizes(get_post_thumbnail_id($product_id), 'full') ?>">
                                    </div>
                                </div>
                            </div>
                        <?php else :
                            Irantheme_Section_Service::empty_thumbnail('white', '', '', 10); ?>
                        <?php endif; ?>
                    </div>
                    <?php
                    $magnifier_checkbox = get_option('irantheme_single_product_magnification_image_checkbox');
                    $magnifier_bg_color = get_option('irantheme_single_product_magnification_bg_color_select');
                    $magnifier_tools = get_option('irantheme_single_product_magnification_tool_multiple_checkboxes');
                    $magnifier_tools = explode(',', $magnifier_tools);
                    ?>
                    <?php echo $magnifier_checkbox == 'on' ? Irantheme_Common_Static::gallery_magnification('irantheme_magnification_swiper_product', $magnifier_tools, $magnifier_bg_color, $attachment_urls) : ''; ?>
                </div>
            </div>
        </div>
        <div class="irantheme-single-product-shopping-summary col-lg-3">
            <div class="irantheme-single-product-shopping-summary-tools">
                <?php
                // Get main SKU visibility option
                $sku_checkbox = get_option('irantheme_single_product_sku_visibility_checkbox', 'on');

                if ($sku_checkbox === 'on') :
                    // Get empty SKU visibility option (only applies when main option is enabled)
                    // 'off' = hide empty SKU, 'on' = show empty SKU
                    $sku_empty_visibility_checkbox = get_option('irantheme_single_product_sku_empty_visibility_checkbox', 'off');
                    $sku = $product->get_sku();
                    if ($sku !== '' || $sku_empty_visibility_checkbox === 'on') :
                ?>
                        <div class="product-sku">
                            <span class="label"><?php echo esc_html('شناسه کالا:'); ?></span>
                            <span class="value">
                                <?php
                                echo $sku !== ''
                                    ? esc_html($sku)
                                    : esc_html__('N/A', 'woocommerce');
                                ?>
                            </span>
                        </div>
                <?php
                    endif;
                endif;
                ?>
                <ul class="product-tools">
                    <li class="product-tools-item product-wishlist-tools">
                        <button type="button" class="irantheme-btn-void wishlist-toggle" data-product="<?php esc_attr(the_ID()); ?>"><i class="fa-light fa-heart"></i>
                        </button>
                    </li>
                    <?php if ($share_checkbox == 'on'): ?>
                        <li id="irantheme_product_share_tools_toggler" class="product-tools-item product-share-tools">
                            <button type="button" class="irantheme-btn-void share-link"><i class="fa-light fa-share-nodes"></i></button>
                        </li>
                        <?php
                        include IRANTHEME_DIR . '/woocommerce/single-product/share.php';
                        ?>
                    <?php endif; ?>
                </ul>
            </div>
            <?php if ($product->is_in_stock()): ?>
                <?php
                $sales_price_to   = get_post_meta($product->get_id(), '_sale_price_dates_to', true);
                ?>
                <?php if ($product->is_on_sale() && $sales_price_to != ""): ?>
                    <div class="product-offer-schedule">
                        <div class="product-offer-schedule-title title-svg">
                            <svg width="108" height="13" viewBox="0 0 108 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M47.9052 0.128687C47.965 0.047491 48.057 0 48.1545 0H48.4702C48.5359 0 48.5729 0.0797569 48.5325 0.134495L46.5159 2.87132C46.4561 2.95251 46.3641 3 46.2666 3H45.9509C45.8852 3 45.8483 2.92024 45.8886 2.86551L47.9052 0.128687Z" fill="#E6123D"></path>
                                <path d="M43.7366 0.333334C43.9073 0.333334 44.0332 0.45282 44.0503 0.624605L44.0523 0.665145V1.66058C44.0523 1.84008 43.9386 1.97233 43.7752 1.9903L43.7366 1.99239H42.6316C42.4608 1.99239 42.3349 1.87296 42.3178 1.70113L42.3158 1.66058V0.665145C42.3158 0.485712 42.4295 0.353409 42.593 0.335424L42.6316 0.333334H43.7366Z" fill="#E6123D"></path>
                                <path d="M58.1319 2.71664C57.9554 2.71664 57.8101 2.57622 57.7915 2.39607L57.7895 2.35689L57.7896 0.693083C57.7896 0.507632 57.9231 0.35498 58.0946 0.335451L58.1319 0.333341H59.0911C59.2677 0.333341 59.413 0.473628 59.4316 0.653882L59.4336 0.693083L59.4339 1.16267L60.537 1.16259C60.7135 1.16259 60.8588 1.30288 60.8774 1.48314L60.8794 1.52234V2.35689C60.8794 2.54221 60.7459 2.69498 60.5744 2.71452L60.537 2.71664H58.1319Z" fill="#E6123D"></path>
                                <path d="M105.757 10.6167C105.934 10.6167 106.079 10.7571 106.098 10.9373L106.1 10.9764L106.099 12.6403C106.099 12.8257 105.966 12.9784 105.794 12.9979L105.757 13H104.798C104.621 13 104.476 12.8597 104.457 12.6795L104.455 12.6403L104.455 12.1707L103.352 12.1707C103.175 12.1707 103.03 12.0305 103.012 11.8502L103.01 11.811V10.9764C103.01 10.7911 103.143 10.6384 103.315 10.6188L103.352 10.6167H105.757Z" fill="#E6123D"></path>
                                <path d="M93.4757 2.7294C93.4943 2.90956 93.6396 3.04997 93.8161 3.04997H96.2213L96.2586 3.04786C96.4301 3.02831 96.5637 2.87555 96.5637 2.69023V1.85567L96.5616 1.81647C96.5431 1.63622 96.3978 1.49593 96.2213 1.49593L95.1182 1.49601L95.1179 1.02642L95.1159 0.987215C95.0973 0.806962 94.952 0.666674 94.7754 0.666674H93.8162L93.7788 0.668785C93.6073 0.688314 93.4738 0.840965 93.4738 1.02642L93.4737 2.69023L93.4757 2.7294Z" fill="#E6123D"></path>
                                <path d="M34.4205 3C34.5913 3 34.7172 3.11949 34.7343 3.29127L34.7363 3.33181V4.32725C34.7363 4.50674 34.6226 4.63899 34.4591 4.65697L34.4205 4.65906H31.8947C31.7239 4.65906 31.5981 4.53963 31.581 4.3678L31.579 4.32725V3.33181C31.579 3.15238 31.6926 3.02008 31.8561 3.00209L31.8947 3H34.4205Z" fill="#E6123D"></path>
                                <path d="M101.366 10.9168C101.349 10.7451 101.223 10.6256 101.052 10.6256H98.5263L98.4877 10.6277C98.3242 10.6457 98.2106 10.778 98.2106 10.9574V11.9528L98.2126 11.9934C98.2297 12.1652 98.3555 12.2846 98.5263 12.2846H101.052L101.091 12.2825C101.254 12.2646 101.368 12.1323 101.368 11.9528V10.9574L101.366 10.9168Z" fill="#E6123D"></path>
                                <path d="M9.15737 10.6256C9.32816 10.6256 9.454 10.7451 9.47111 10.9168L9.4731 10.9574V11.9528C9.4731 12.1323 9.35946 12.2646 9.19596 12.2825L9.15737 12.2846H6.63154C6.46074 12.2846 6.3349 12.1652 6.3178 11.9934L6.31581 11.9528V10.9574C6.31581 10.778 6.42945 10.6457 6.59295 10.6277L6.63154 10.6256H9.15737Z" fill="#E6123D"></path>
                                <path d="M86.3662 1.62461C86.349 1.45282 86.2232 1.33333 86.0524 1.33333H84.9474L84.9088 1.33542C84.7453 1.35341 84.6316 1.48571 84.6316 1.66515V2.66058L84.6336 2.70113C84.6507 2.87296 84.7766 2.99239 84.9474 2.99239H86.0524L86.091 2.9903C86.2545 2.97233 86.3681 2.84008 86.3681 2.66058V1.66515L86.3662 1.62461Z" fill="#E6123D"></path>
                                <path d="M4.42113 1.33333C4.59187 1.33333 4.71776 1.45282 4.73487 1.62461L4.73686 1.66515V2.66058C4.73686 2.84008 4.62317 2.97233 4.45971 2.9903L4.42113 2.99239H3.47364C3.30291 2.99239 3.17701 2.87296 3.1599 2.70113L3.15791 2.66058V1.66515C3.15791 1.48571 3.27161 1.35341 3.43507 1.33542L3.47364 1.33333H4.42113Z" fill="#E6123D"></path>
                                <path d="M21.6293 1.62461C21.6122 1.45282 21.4863 1.33333 21.3155 1.33333H20.2105L20.1719 1.33542C20.0085 1.35341 19.8948 1.48571 19.8948 1.66515V2.66058L19.8968 2.70113C19.9139 2.87296 20.0398 2.99239 20.2105 2.99239H21.3155L21.3541 2.9903C21.5176 2.97233 21.6313 2.84008 21.6313 2.66058V1.66515L21.6293 1.62461Z" fill="#E6123D"></path>
                                <path d="M71.3663 5.22941C71.3309 3.87346 70.4667 2.99392 69.1573 2.99392H66.3158L66.2772 2.99601C66.1139 3.01401 66 3.14642 66 3.32573V4.32117L66.002 4.36167C66.0192 4.53332 66.1452 4.65298 66.3158 4.65298H69.1573L69.2122 4.65418C69.6049 4.67181 69.7888 4.884 69.7888 5.3166V7.30747L69.7877 7.36509C69.7709 7.77766 69.569 7.9711 69.1573 7.9711H66.3158L66.2772 7.97319C66.1139 7.99118 66 8.12354 66 8.30291V9.29834L66.002 9.33886C66.0192 9.51056 66.1452 9.63016 66.3158 9.63016H69.1573L69.2403 9.62897C70.5305 9.59172 71.3674 8.68351 71.3674 7.30747V5.3166L71.3663 5.22941Z" fill="#E6123D"></path>
                                <path d="M13.7334 0C13.636 0 13.544 0.047491 13.4841 0.128687L11.4675 2.86551C11.4272 2.92024 11.4642 3 11.5299 3H11.8456C11.943 3 12.035 2.95251 12.0948 2.87132L14.1114 0.134495C14.1518 0.0797569 14.1148 0 14.0491 0H13.7334Z" fill="#E6123D"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M75.4737 6V8H74.8422C74.3189 8 73.8948 7.55229 73.8948 7V1.33333C73.8948 1.14924 73.7534 1 73.579 1H72.6316C72.4572 1 72.3158 1.14924 72.3158 1.33333V7C72.3158 8.47276 73.4469 9.66667 74.8422 9.66667H81.6316C82.2044 9.66667 82.7297 9.45207 83.1392 9.09489L83.4201 9.52308C83.479 9.61301 83.5761 9.66667 83.6797 9.66667H85.5669C86.2855 9.66667 86.9341 9.34994 87.3941 8.84151L87.8411 9.52308C87.9001 9.61301 87.9971 9.66667 88.1008 9.66667H89.9879C90.6121 9.66667 91.1834 9.42775 91.6242 9.03183L91.9464 9.52308C92.0054 9.61301 92.1024 9.66667 92.206 9.66667H93.4571C94.0839 9.66667 94.6575 9.42566 95.0991 9.02664L95.4247 9.52308C95.4837 9.61301 95.5807 9.66667 95.6843 9.66667H95.988C96.6462 9.66667 97.2455 9.40101 97.6951 8.96573L97.9633 9.49137C98.0184 9.59934 98.1251 9.66667 98.2413 9.66667H100.421C101.044 9.66667 101.607 9.39474 102.008 8.95722L102.38 9.52308C102.439 9.61301 102.536 9.66667 102.639 9.66667H104.526C105.922 9.66667 107.053 8.47276 107.053 7V5C107.053 4.81591 106.911 4.66667 106.737 4.66667H105.79C105.615 4.66667 105.474 4.81591 105.474 5V7C105.474 7.55229 105.05 8 104.526 8H102.54C102.6 7.78877 102.632 7.565 102.632 7.33334V5C102.632 4.81591 102.49 4.66667 102.316 4.66667H101.368C101.194 4.66667 101.053 4.81591 101.053 5V7.33334C101.053 7.70153 100.77 8 100.421 8H98.5264V5C98.5264 4.81591 98.385 4.66667 98.2106 4.66667H97.2512C97.0767 4.66667 96.9354 4.81591 96.9354 5V7C96.9354 7.55229 96.5112 8 95.988 8H95.7997C95.9182 7.69119 95.9834 7.35364 95.9834 7V5C95.9834 4.81591 95.842 4.66667 95.6676 4.66667H94.7202C94.5458 4.66667 94.4044 4.81591 94.4044 5V7C94.4044 7.55229 93.9803 8 93.4571 8H92.3306C92.449 7.69119 92.5142 7.35364 92.5142 7V5C92.5142 4.81591 92.3729 4.66667 92.1984 4.66667H91.2511C91.0767 4.66667 90.9353 4.81591 90.9353 5V7C90.9353 7.55229 90.5111 8 89.9879 8H87.9096C88.028 7.69119 88.0932 7.35364 88.0932 7V5C88.0932 4.81591 87.9518 4.66667 87.7774 4.66667H86.83C86.6556 4.66667 86.5142 4.81591 86.5142 5V7C86.5142 7.55229 86.0901 8 85.5669 8H83.8653C83.9526 7.73936 84.0001 7.45887 84.0001 7.16667C84.0001 5.78596 82.9397 4.66667 81.6316 4.66667L80.1884 4.66667C79.7516 3.8696 78.9352 3.33334 78.0001 3.33334C76.6048 3.33334 75.4737 4.52724 75.4737 6ZM78.0001 5C77.4768 5 77.0527 5.44772 77.0527 6V8H78.9474V6C78.9474 5.44772 78.5233 5 78.0001 5ZM80.5264 8V6.33334H81.6316C82.0677 6.33334 82.4211 6.70643 82.4211 7.16667C82.4211 7.62691 82.0677 8 81.6316 8H80.5264Z" fill="#E6123D"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M45.7895 7C45.7895 7.35364 45.7243 7.69119 45.6059 8H50.2092C51.1128 8 51.7879 7.2873 51.7879 6.33334V6C51.7879 5.04604 51.1128 4.33334 50.2092 4.33334H46.5783C46.2942 4.33334 46.2394 4.06452 46.4205 3.83334L49.0795 0.248221C49.2026 0.0910277 49.3858 0 49.579 0H50.7919C51.0359 0 51.1696 0.3 51.0141 0.498498L49.4199 2.66667H50.2092C52.0197 2.66667 53.3665 4.08855 53.3665 6V6.33334C53.3665 6.95036 53.2262 7.51637 52.9752 8H54.6195C55.1427 8 55.5668 7.55229 55.5668 7V5C55.5668 4.81591 55.7082 4.66667 55.8826 4.66667H56.83C57.0044 4.66667 57.1458 4.81591 57.1458 5V7C57.1458 7.35364 57.0806 7.69119 56.9622 8H58.0886C58.6118 8 59.036 7.55229 59.036 7V5C59.036 4.81591 59.1774 4.66667 59.3518 4.66667H60.2991C60.4735 4.66667 60.6149 4.81591 60.6149 5V7C60.6149 7.35364 60.5497 7.69119 60.4313 8H60.6195C61.1428 8 61.5669 7.55229 61.5669 7V5C61.5669 4.81591 61.7083 4.66667 61.8827 4.66667H62.8301C63.0045 4.66667 63.1459 4.81591 63.1459 5V7C63.1459 8.47276 62.0148 9.66667 60.6195 9.66667H60.3159C60.2122 9.66667 60.1152 9.61301 60.0562 9.52308L59.7306 9.02664C59.289 9.42566 58.7155 9.66667 58.0886 9.66667H56.8376C56.7339 9.66667 56.6369 9.61301 56.5779 9.52308L56.2557 9.03183C55.8149 9.42775 55.2436 9.66667 54.6195 9.66667H52.7323C52.6287 9.66667 52.5316 9.61301 52.4727 9.52308L52.1396 9.01528C51.6143 9.42819 50.9507 9.66667 50.2092 9.66667H38.5218C38.4181 9.66667 38.3211 9.61301 38.2621 9.52308L37.9399 9.03181C37.4991 9.42774 36.9278 9.66667 36.3036 9.66667H30.0001C28.6048 9.66667 27.4738 8.47276 27.4738 7V5C27.4738 4.81591 27.6151 4.66667 27.7895 4.66667H28.7369C28.9113 4.66667 29.0527 4.81591 29.0527 5V7C29.0527 7.55229 29.4769 8 30.0001 8H36.3036C36.8269 8 37.251 7.55229 37.251 7V5C37.251 4.81591 37.3924 4.66667 37.5668 4.66667H38.5142C38.6886 4.66667 38.83 4.81591 38.83 5V7C38.83 7.35364 38.7647 7.69119 38.6463 8H40.9205C40.8021 7.69119 40.7369 7.35364 40.7369 7V6C40.7369 4.52724 41.868 3.33334 43.2632 3.33334C44.6584 3.33334 45.7895 4.52724 45.7895 6V7ZM44.2106 6V7C44.2106 7.55229 43.7864 8 43.2632 8C42.74 8 42.3158 7.55229 42.3158 7V6C42.3158 5.44772 42.74 5 43.2632 5C43.7864 5 44.2106 5.44772 44.2106 6Z" fill="#E6123D"></path>
                                <path d="M18.9454 6.33334C18.9454 6.95036 18.8051 7.51637 18.5541 8H21.1458C21.669 8 22.0931 7.55229 22.0931 7V5C22.0931 4.81591 22.2345 4.66667 22.4089 4.66667H23.3563C23.5307 4.66667 23.6721 4.81591 23.6721 5V7C23.6721 8.47276 22.541 9.66667 21.1458 9.66667H18.9428C18.8392 9.66667 18.7422 9.61301 18.6832 9.52308L18.1091 8.64779C17.5427 9.28358 16.7279 9.66667 15.7882 9.66667H10.3384L9.85431 9.014C9.41088 9.42057 8.83187 9.66667 8.1984 9.66667H5.36386C5.26023 9.66667 5.1632 9.61301 5.10422 9.52308L4.75745 8.99435V9C4.75745 10.9115 3.41059 12.3333 1.60015 12.3333H1.27757C1.08338 12.3333 0.899977 12.239 0.780267 12.0776L0.104618 11.1667C-0.0764254 10.9355 -0.0216732 10.6667 0.262483 10.6667H1.60015C2.50374 10.6667 3.1788 9.95397 3.1788 9L3.1785 5C3.1785 4.81591 3.31988 4.66667 3.49429 4.66667H4.44166C4.61606 4.66667 4.75745 4.81591 4.75745 5V8H8.1984C8.72162 8 9.14577 7.55229 9.14577 7V5C9.14577 4.81591 9.28715 4.66667 9.46156 4.66667H10.4089C10.5833 4.66667 10.7247 4.81591 10.7247 5V7C10.7247 7.35364 10.6595 7.69119 10.5411 8H15.7882C16.6917 8 17.3668 7.2873 17.3668 6.33334V6C17.3668 5.04604 16.6917 4.33334 15.7882 4.33334H12.1573C11.8731 4.33334 11.8184 4.06452 11.9994 3.83334L14.6584 0.248221C14.7816 0.0910277 14.9648 0 15.1579 0H16.3708C16.6149 0 16.7486 0.3 16.5931 0.498498L14.9988 2.66667H15.7882C17.5986 2.66667 18.9454 4.08855 18.9454 6V6.33334Z" fill="#E6123D"></path>
                                <path d="M24.6316 1.33333C24.6316 1.14924 24.773 1 24.9474 1H25.8948C26.0692 1 26.2106 1.14924 26.2106 1.33333V9.33334C26.2106 9.51743 26.0692 9.66667 25.8948 9.66667H24.9474C24.773 9.66667 24.6316 9.51743 24.6316 9.33334V1.33333Z" fill="#E6123D"></path>
                            </svg>
                        </div>
                        <div class="product-offer-schedule-time">
                            <?php Irantheme_Section_Woocommerce::display_schedule_product($product, 'white', 'flex-left', '', '', true); ?>
                            <i class="fa-light fa-alarm-clock"></i>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            <div class="irantheme-single-product-shopping-summary-wrapper main-shopping-summary">
                <?php
                $short_description = $product->get_short_description();
                if ($short_description): ?>
                    <div class="product-file-desc">
                        <div class="description irantheme-tinymce-content">
                            <?php echo $short_description; ?>
                        </div>
                    </div>
                    <div class="irantheme-single-product-divider"></div>
                <?php endif; ?>
                <?php
                $variable_selected_checkbox = get_option('irantheme_single_product_variable_selected_checkbox', 'on');
                $seller_name_checkbox = get_option('irantheme_single_product_seller_name_checkbox');
                $seller_global_name_text = get_option('irantheme_single_product_seller_name');
                $seller_meta_name_text = get_post_meta($product_id, 'irantheme_custom_field_seller_name', true);
                $seller_name_text = $seller_global_name_text;
                if ($seller_meta_name_text) {
                    $seller_name_text = $seller_meta_name_text;
                }
                $shipping_time_text = get_post_meta($product_id, 'irantheme_custom_field_shipping_time', true);
                $is_call_for_price = get_post_meta($product->get_id(), 'irantheme_product_meta_is_call_for_price', true);
                $add_to_card_btn_color_customization_checkbox = get_option('irantheme_single_product_add_to_card_btn_color_customization_checkbox');
                $add_to_card_btn_bg_color = get_option('irantheme_single_product_add_to_card_btn_bg_color');
                $add_to_card_btn_for_color = get_option('irantheme_single_product_add_to_card_btn_color');
                $add_to_card_btn_bg_color_class = '';
                $add_to_card_btn_color_class = '';
                $add_to_card_btn_hover_color_class = '';
                $add_to_card_btn_border_color_class = '';
                if ($add_to_card_btn_color_customization_checkbox == 'on') {
                    if ($add_to_card_btn_bg_color != 'off') {
                        $add_to_card_btn_bg_color_class = esc_attr(Irantheme_Color_Static::get_class_color_mode('bg', null, Irantheme_Color_Static::get_hex_color($add_to_card_btn_bg_color)));
                        $add_to_card_btn_hover_color_class = esc_attr(Irantheme_Color_Static::get_class_color_mode('color-hover', null, Irantheme_Color_Static::get_hex_color($add_to_card_btn_bg_color)));
                        $add_to_card_btn_border_color_class = esc_attr(Irantheme_Color_Static::get_class_color_mode('border', null, Irantheme_Color_Static::get_hex_color($add_to_card_btn_bg_color)));
                    }
                    if ($add_to_card_btn_for_color == 'off') {
                        $add_to_card_btn_color_class = Irantheme_Color_Static::get_optimal_text_color(Irantheme_Color_Static::get_hex_color($add_to_card_btn_bg_color));
                    } else {
                        $add_to_card_btn_color_class = 'text-color-' . $add_to_card_btn_for_color;
                    }
                }
                ?>
                <?php
                if ($product->is_downloadable() || irantheme_are_all_variations_downloadable($product)) {
                    do_action('woocommerce_before_add_to_cart_form');
                    woocommerce_template_single_add_to_cart();
                }
                ?>
                <div class="product-add-to-card">
                    <?php
                    $is_call_for_price = get_post_meta($product->get_id(), 'irantheme_product_meta_is_call_for_price', true);
                    if ($is_call_for_price != 'yes'):
                    ?>
                        <?php if (($product_status == 'instock' || $product_status == 'onbackorder') && $product->get_price() != ''): ?>
                            <?php if ($variable_selected_checkbox != 'off' && (($seller_name_checkbox != 'off' && $seller_name_text != '') || ($shipping_time_checkbox != 'off' && $shipping_time_text != ''))): ?>
                                <div class="irantheme-single-product-divider little-m"></div>
                            <?php endif; ?>
                            <div class="product-add-to-card-price">
                                <?php woocommerce_template_single_price(); ?>
                            </div>
                            <?php if (($product->is_type('simple') && $product->get_manage_stock()) || $product->is_type('variable')) : ?>
                                <div class="product-add-to-card-stock-status">
                                    <?php
                                    if ($product->is_type('simple')) {
                                        $low_stock_threshold = 5;
                                        $stock_quantity = $product->get_stock_quantity();
                                        if ($product_status == 'instock') {
                                            if ($stock_quantity <= $low_stock_threshold) {
                                                printf(
                                                    '<p class="product-add-to-card-stock-status-item low-stock">فقط %s عدد در انبار باقی مانده!</p>',
                                                    esc_html($stock_quantity)
                                                );
                                            } else {
                                                printf(
                                                    '<p class="product-add-to-card-stock-status-item in-stock">موجود در انبار: %s عدد</p>',
                                                    esc_html($stock_quantity)
                                                );
                                            }
                                        } elseif ($product_status == 'onbackorder') {
                                            $backorder_status = $product->get_backorders();
                                            if ($backorder_status == 'notify') {
                                                printf(
                                                    '<p class="product-add-to-card-stock-status-item on-backorder">موجود در انبار: قابل پیش خرید</p>',
                                                    esc_html($stock_quantity)
                                                );
                                            }
                                        }
                                    }
                                    ?>
                                </div>
                            <?php endif; ?>
                            <div class="product-add-to-card-button">
                                <?php do_action('woocommerce_single_variation'); ?>
                            </div>
                        <?php elseif ($product_status == 'outofstock' || $product->get_price() == ''): ?>
                            <span class="product-add-to-card-tip outofstock">
                                <span class="product-add-to-card-tip-icon">
                                    <i class="fa-light fa-cart-circle-exclamation"></i>
                                </span>
                                <span class="product-add-to-card-tip-text">متاسفانه این کالا فعلا موجود نیست.</span>
                            </span>
                            <div class="product-add-to-card-button outofstock">
                                <button class="irantheme-single-add-to-cart-button single_add_to_cart_button button outofstock <?php echo $add_to_card_btn_color_class; ?> <?php echo $add_to_card_btn_bg_color_class; ?> <?php echo $add_to_card_btn_border_color_class; ?>"><?php _e('ناموجود'); ?></button>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <?php
                        $last_price = get_post_meta($product_id, 'irantheme_product_meta_last_price_amount', true);
                        $last_price_date = get_post_meta($product_id, 'irantheme_product_meta_last_price_date', true);
                        $support_phone_number = get_option('irantheme_single_product_support_phone_number', '0913XXXXXXX');
                        ?>
                        <?php if ($last_price != '' && $last_price_date != ''): ?>
                            <div class="product-add-to-card-last-price">
                                <div class="product-add-to-card-last-price-label">
                                    <span>آخرین قیمت</br>ثــــبت شــده</span>
                                </div>
                                <div class="product-add-to-card-last-price-value">
                                    <span class="product-add-to-card-last-price-value-date"><?php echo esc_attr($last_price_date); ?></span>
                                    <span class="product-add-to-card-last-price-value-price"><?php echo wc_price(esc_attr($last_price)); ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                        <span class="product-add-to-card-tip call-for-price-info">
                            <span class="product-add-to-card-tip-icon">
                                <i class="fa-light fa-phone-volume"></i>
                            </span>
                            <span class="product-add-to-card-tip-text">برای استعلام قیمت و موجودی تماس بگیرید.</span>
                        </span>
                        <span class="product-add-to-card-call-info">
                            <span class="product-add-to-card-call-info-label"><?php _e('شماره تماس پشتیبانی'); ?></span>
                            <span class="product-add-to-card-call-info-number"><?php echo esc_html($support_phone_number); ?></span>
                        </span>
                        <div class="product-add-to-card-button call-for-price-info">
                            <a href="tel:<?php echo esc_attr($support_phone_number) ?>" class="irantheme-single-add-to-cart-button single_add_to_cart_button button <?php echo $product_status; ?> <?php echo $add_to_card_btn_color_class; ?> <?php echo $add_to_card_btn_bg_color_class; ?> <?php echo $add_to_card_btn_hover_color_class; ?> <?php echo $add_to_card_btn_border_color_class; ?>"><?php _e('تماس بگیرید'); ?></a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php
    $additional_info_checkbox = get_option('irantheme_single_product_additional_info_checkbox');
    $additional_info_title_1 = get_option('irantheme_single_product_additional_info_title_1');
    $additional_info_desc_1 = get_option('irantheme_single_product_additional_info_desc_1');
    $additional_info_icon_1 = get_option('irantheme_single_product_additional_info_icon_1');
    $additional_info_title_2 = get_option('irantheme_single_product_additional_info_title_2');
    $additional_info_desc_2 = get_option('irantheme_single_product_additional_info_desc_2');
    $additional_info_icon_2 = get_option('irantheme_single_product_additional_info_icon_2');
    $additional_info_title_3 = get_option('irantheme_single_product_additional_info_title_3');
    $additional_info_desc_3 = get_option('irantheme_single_product_additional_info_desc_3');
    $additional_info_icon_3 = get_option('irantheme_single_product_additional_info_icon_3');
    $additional_info_title_4 = get_option('irantheme_single_product_additional_info_title_4');
    $additional_info_desc_4 = get_option('irantheme_single_product_additional_info_desc_4');
    $additional_info_icon_4 = get_option('irantheme_single_product_additional_info_icon_4');
    $additional_info_title_5 = get_option('irantheme_single_product_additional_info_title_5');
    $additional_info_desc_5 = get_option('irantheme_single_product_additional_info_desc_5');
    $additional_info_icon_5 = get_option('irantheme_single_product_additional_info_icon_5');
    ?>
    <?php if ($additional_info_checkbox == 'on'): ?>
        <div class="irantheme-single-product-additional-info-wrapper row">
            <div class="irantheme-single-product-additional-info col-12">
                <ul class="irantheme-single-product-additional-info-list">
                    <?php if ($additional_info_title_1 != '' || $additional_info_desc_1 != '' || $additional_info_icon_1 != ''): ?>
                        <li class="irantheme-single-product-additional-info-list-item">
                            <div class="icon">
                                <i class="<?php echo esc_attr($additional_info_icon_1); ?>"></i>
                            </div>
                            <div class="label">
                                <span class="title"><?php echo esc_attr($additional_info_title_1); ?></span>
                                <span class="desc"><?php echo esc_attr($additional_info_desc_1); ?></span>
                            </div>
                        </li>
                    <?php endif; ?>
                    <?php if ($additional_info_title_2 != '' || $additional_info_desc_2 != '' || $additional_info_icon_2 != ''): ?>
                        <li class="irantheme-single-product-additional-info-list-item">
                            <div class="icon">
                                <i class="<?php echo esc_attr($additional_info_icon_2); ?>"></i>
                            </div>
                            <div class="label">
                                <span class="title"><?php echo esc_attr($additional_info_title_2); ?></span>
                                <span class="desc"><?php echo esc_attr($additional_info_desc_2); ?></span>
                            </div>
                        </li>
                    <?php endif; ?>
                    <?php if ($additional_info_title_3 != '' || $additional_info_desc_3 != '' || $additional_info_icon_3 != ''): ?>
                        <li class="irantheme-single-product-additional-info-list-item">
                            <div class="icon">
                                <i class="<?php echo esc_attr($additional_info_icon_3); ?>"></i>
                            </div>
                            <div class="label">
                                <span class="title"><?php echo esc_attr($additional_info_title_3); ?></span>
                                <span class="desc"><?php echo esc_attr($additional_info_desc_3); ?></span>
                            </div>
                        </li>
                    <?php endif; ?>
                    <?php if ($additional_info_title_4 != '' || $additional_info_desc_4 != '' || $additional_info_icon_4 != ''): ?>
                        <li class="irantheme-single-product-additional-info-list-item">
                            <div class="icon">
                                <i class="<?php echo esc_attr($additional_info_icon_4); ?>"></i>
                            </div>
                            <div class="label">
                                <span class="title"><?php echo esc_attr($additional_info_title_4); ?></span>
                                <span class="desc"><?php echo esc_attr($additional_info_desc_4); ?></span>
                            </div>
                        </li>
                    <?php endif; ?>
                    <?php if ($additional_info_title_5 != '' || $additional_info_desc_5 != '' || $additional_info_icon_5 != ''): ?>
                        <li class="irantheme-single-product-additional-info-list-item">
                            <div class="icon">
                                <i class="<?php echo esc_attr($additional_info_icon_5); ?>"></i>
                            </div>
                            <div class="label">
                                <span class="title"><?php echo esc_attr($additional_info_title_5); ?></span>
                                <span class="desc"><?php echo esc_attr($additional_info_desc_5); ?></span>
                            </div>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>
    <?php if ($product->get_upsell_ids()): ?>
        <div class="irantheme-single-product-upsell-wrapper row">
            <?php woocommerce_upsell_display(); ?>
        </div>
    <?php endif; ?>
    <?php
    $second_summary_checkbox = get_option('irantheme_single_product_second_summary_box', true);
    $second_summary_col_class = 'col-lg-9';
    if ($second_summary_checkbox == 'off') {
        $second_summary_col_class = 'col-lg-12';
    }
    ?>
    <div class="irantheme-single-product-content row">
        <div class="irantheme-single-product-content-tabs <?php echo esc_attr($second_summary_col_class); ?>">
            <?php
            /**
             * Hook: woocommerce_after_single_product_summary.
             *
             * @hooked woocommerce_output_product_data_tabs - 10
             * @hooked woocommerce_upsell_display - 15
             * @hooked woocommerce_output_related_products - 20
             */
            remove_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15);
            remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);

            do_action('woocommerce_after_single_product_summary');
            ?>
            <?php if (wc_get_related_products($product->get_id())): ?>
                <div class="irantheme-single-product-related-wrapper">
                    <?php woocommerce_output_related_products(); ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
        if ($second_summary_checkbox == 'on' || $product->is_downloadable() || irantheme_are_all_variations_downloadable($product)):
        ?>
            <div class="irantheme-single-product-shopping-summary irantheme-single-product-content-shopping-summary col-lg-3">
                <div class="irantheme-single-product-shopping-summary-wrapper irantheme-single-product-content-shopping-summary-file-wrapper">
                    <?php
                    $file_last_update = get_post_meta($product_id, '_irantheme_file_update_date', true);
                    $file_version = get_post_meta($product_id, '_irantheme_file_version_number', true);
                    $file_published_date = get_post_meta($product_id, '_irantheme_file_release_date', true);
                    ?>
                    <ul id="irantheme_download_info" class="product-file-details">
                        <?php if ($file_version): ?>
                            <li class="product-file-details-item irantheme-file-details">
                                <span class="label">نسخه</span>
                                <span id="file_version_number" class="value"><?php echo esc_html($file_version); ?></span>
                            </li>
                        <?php endif; ?>
                        <?php if ($file_last_update): ?>
                            <li class="product-file-details-item irantheme-file-details">
                                <span class="label">تاریخ بروزرسانی</span>
                                <span id="file_update_date" class="value"><?php echo esc_html($file_last_update); ?></span>
                            </li>
                        <?php endif; ?>
                        <?php if ($file_published_date): ?>
                            <li class="product-file-details-item irantheme-file-details">
                                <span class="label">تاریخ انتشار</span>
                                <span id="file_release_date" class="value"><?php echo esc_html($file_published_date); ?></span>
                            </li>
                        <?php endif; ?>
                        <?php echo wc_get_product_category_list($product->get_id(), ',', '<li class="product-file-details-item"><span class="label">' . _n('دسته بندی', 'دسته بندی ها', count($product->get_category_ids()), IRANTHEME_TEXTDOMAIN) . '</span><span class="value category">', '</span></li>'); ?>
                        <?php if (wc_review_ratings_enabled()): ?>
                            <?php
                            $rating_count = $product->get_rating_count();
                            $review_count = $product->get_review_count();
                            $average      = $product->get_average_rating();
                            ?>
                            <li class="product-file-details-item">
                                <span class="label"><?php echo esc_html(round($average, 2)); ?> امتیاز از <?php echo $review_count; ?> رای</span>
                                <span class="value"><?php echo wc_get_rating_html($average, $rating_count); ?></span>
                            </li>
                        <?php endif; ?>
                    </ul>
                    <?php
                    $current_tags = get_the_terms(get_the_ID(), 'product_tag');
                    ?>
                    <?php if ($current_tags && !is_wp_error($current_tags)): ?>
                        <div class="irantheme-single-product-divider"></div>
                        <div class="product-file-tags">
                            <span class="product-file-tags-label">برچسب:</span>
                            <ul class="product-file-tags-list">
                                <?php foreach ($current_tags as $tag): ?>
                                    <?php
                                    $tag_title = $tag->name; // tag name
                                    // $tag_link = get_term_link($tag); // tag archive link
                                    ?>
                                    <li class="product-file-tags-list-item">
                                        <h3><i class="fa-light fa-tag"></i><?php echo esc_html($tag_title); ?></h3>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
                <?php
                $product_tip = get_post_meta($product_id, 'irantheme_custom_field_product_tip', true);
                if ($product_tip != ''):
                ?>
                    <div class="irantheme-single-product-shopping-summary-wrapper irantheme-single-product-content-shopping-summary-file-tip-wrapper">
                        <div class="product-file-tip">
                            <div class="product-file-tip-title">
                                <i class="icon fa-light fa-triangle-exclamation <?php echo $details_color_class; ?>" style="background-color:<?php echo Irantheme_Color_Static::generate_light_background_color(Irantheme_Color_Static::get_hex_color($details_color)); ?>;"></i>
                                <p class="title">قبل از خرید بخوانید!</p>
                            </div>
                            <p class="product-file-tip-text"><?php echo $product_tip; ?></p>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if ($second_summary_checkbox == 'on'): ?>
                    <div class="irantheme-single-product-shopping-summary-wrapper irantheme-single-product-content-shopping-summary-wrapper">
                        <div class="product-details">
                            <div class="product-details-image">
                                <img src="<?php echo esc_attr(wp_get_attachment_url(get_post_thumbnail_id($product->get_id()), 'medium')); ?>" alt="<?php _e('تصویر محصول'); ?>" srcset="<?php echo esc_attr(wp_get_attachment_image_srcset(get_post_thumbnail_id($product->get_id()), 'full')); ?>" sizes="<?php echo wp_get_attachment_image_sizes(get_post_thumbnail_id($product->get_id()), 'full') ?>">
                            </div>
                            <div class="product-details-name">
                                <?php the_title('<p class="product-details-name-title">', '</p>'); ?>
                                <?php if ($product->get_short_description()): ?>
                                    <div class="product-details-name-desc">
                                        <?php echo $product->get_short_description(); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="irantheme-single-product-divider"></div>
                        <?php
                        $is_call_for_price = get_post_meta($product->get_id(), 'irantheme_product_meta_is_call_for_price', true);
                        if ($product->is_in_stock() && $product->get_price() != '' && $is_call_for_price != 'yes'):
                            if ($variable_selected_checkbox != 'off' && (($seller_name_checkbox != 'off' && $seller_name_text != '') || ($shipping_time_checkbox != 'off' && $shipping_time_text != ''))): ?>
                                <div class="product-selected-features">
                                    <ul class="product-selected-features-list">
                                        <?php if ($product->is_type('variable') && 'on' == get_option('irantheme_single_product_variable_selected_checkbox', 'on')): ?>
                                            <p class="product-selected-features-list-title">مشخصات انتخاب شده:</p>
                                        <?php endif; ?>
                                        <?php
                                        if ($seller_name_checkbox == 'on'):
                                        ?>
                                            <li class="product-selected-features-list-item no-change">
                                                <span class="product-selected-features-list-item-label">
                                                    <i class="product-selected-features-list-item-label-icon iconsax" icon-name="shop"></i>
                                                    <span class="product-selected-features-list-item-label-text"><?php _e('فروشنده:'); ?></span>
                                                </span>
                                                <span class="product-selected-features-list-item-value"><?php echo esc_html($seller_meta_name_text); ?></span>
                                            </li>
                                        <?php endif; ?>
                                        <?php
                                        if (isset($product) && $product->is_type('variable') && 'on' == get_option('irantheme_single_product_variable_selected_checkbox', 'on')):
                                            $variation_attributes = $product->get_variation_attributes();
                                            $default_attributes = $product->get_default_attributes();
                                            $decoded_default_attributes = [];

                                            foreach ($default_attributes as $key => $value) {
                                                // Decode the attribute name and its value
                                                $decoded_key = urldecode($key);
                                                $decoded_value = urldecode($value);
                                                $decoded_default_attributes[$decoded_key] = $decoded_value;
                                            }
                                        ?>
                                            <?php foreach ($variation_attributes as $attribute_name => $options) : ?>
                                                <?php
                                                $default_value_name = '';
                                                $default_value_slug = '';
                                                // Get taxonomy type value
                                                $attribute_icon = irantheme_get_attribute_custom_option_value($attribute_name, 'filter_icon');
                                                // Check for the presence of a default value in the decoded array
                                                if (isset($decoded_default_attributes[$attribute_name])) {
                                                    $default_value_slug = $decoded_default_attributes[$attribute_name];
                                                }

                                                if ($default_value_slug) {
                                                    // Finding the term object corresponding to the default value
                                                    $term = get_term_by('slug', $default_value_slug, $attribute_name);
                                                    if ($term && !is_wp_error($term)) {
                                                        $default_value_name = $term->name;
                                                    }
                                                }

                                                $display_value = empty($default_value_name) ? 'انتخاب نشده' : $default_value_name;
                                                ?>
                                                <li id="product_selected_features_item_<?php echo esc_attr(sanitize_title($attribute_name)); ?>"
                                                    data-attribute-name="<?php echo esc_attr(sanitize_title($attribute_name)); ?>"
                                                    class="product-selected-features-list-item">
                                                    <span class="product-selected-features-list-item-label">
                                                        <i class="product-selected-features-list-item-label-icon <?php echo esc_attr($attribute_icon == '' ? 'fa-light fa-cube' : $attribute_icon); ?>"></i>
                                                        <span class="product-selected-features-list-item-label-text">
                                                            <?php echo wc_attribute_label($attribute_name) . ':'; ?>
                                                        </span>
                                                    </span>
                                                    <span class="product-selected-features-list-item-value">
                                                        <?php echo esc_html($display_value); ?>
                                                    </span>
                                                </li>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                        <?php
                                        if ('on' == $shipping_time_checkbox && "" != $shipping_time_text):
                                        ?>
                                            <li class="product-selected-features-list-item no-change">
                                                <span class="product-selected-features-list-item-label">
                                                    <i class="product-selected-features-list-item-label-icon fa-light iconsax" icon-name="truck-speed"></i>
                                                    <span class="product-selected-features-list-item-label-text">
                                                        <?php _e('زمان ارسال:'); ?>
                                                    </span>
                                                </span>
                                                <span class="product-selected-features-list-item-value"><?php echo esc_html($shipping_time_text); ?></span>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                        <?php
                            endif;
                        endif;
                        ?>
                        <div class="product-add-to-card">
                            <?php
                            $is_call_for_price = get_post_meta($product->get_id(), 'irantheme_product_meta_is_call_for_price', true);
                            if ($is_call_for_price != 'yes'):
                            ?>
                                <?php if (($product_status == 'instock' || $product_status == 'onbackorder') && $product->get_price() != ''): ?>
                                    <?php if ($variable_selected_checkbox != 'off' && (($seller_name_checkbox != 'off' && $seller_name_text != '') || ($shipping_time_checkbox != 'off' && $shipping_time_text != ''))): ?>
                                        <div class="irantheme-single-product-divider"></div>
                                    <?php endif; ?>
                                    <div class="product-add-to-card-price">
                                        <?php woocommerce_template_single_price(); ?>
                                    </div>
                                    <?php if (($product->is_type('simple') && $product->get_manage_stock()) || $product->is_type('variable')) : ?>
                                        <div class="product-add-to-card-stock-status">
                                            <?php
                                            if ($product->is_type('simple')) {
                                                $low_stock_threshold = 5;
                                                $stock_quantity = $product->get_stock_quantity();
                                                if ($product_status == 'instock') {
                                                    if ($stock_quantity <= $low_stock_threshold) {
                                                        printf(
                                                            '<p class="product-add-to-card-stock-status-item low-stock">فقط %s عدد در انبار باقی مانده!</p>',
                                                            esc_html($stock_quantity)
                                                        );
                                                    } else {
                                                        printf(
                                                            '<p class="product-add-to-card-stock-status-item in-stock">موجود در انبار: %s عدد</p>',
                                                            esc_html($stock_quantity)
                                                        );
                                                    }
                                                } elseif ($product_status == 'onbackorder') {
                                                    $backorder_status = $product->get_backorders();
                                                    if ($backorder_status == 'notify') {
                                                        printf(
                                                            '<p class="product-add-to-card-stock-status-item on-backorder">موجود در انبار: قابل پیش خرید</p>',
                                                            esc_html($stock_quantity)
                                                        );
                                                    }
                                                }
                                            }
                                            ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="product-add-to-card-button">
                                        <?php do_action('woocommerce_single_variation'); ?>
                                    </div>
                                <?php elseif ($product_status == 'outofstock' || $product->get_price() == ''): ?>
                                    <span class="product-add-to-card-tip outofstock">
                                        <span class="product-add-to-card-tip-icon">
                                            <i class="fa-light fa-cart-circle-exclamation"></i>
                                        </span>
                                        <span class="product-add-to-card-tip-text">متاسفانه این کالا فعلا موجود نیست.</span>
                                    </span>
                                    <div class="product-add-to-card-button outofstock">
                                        <button class="irantheme-single-add-to-cart-button single_add_to_cart_button button outofstock <?php echo $add_to_card_btn_color_class; ?> <?php echo $add_to_card_btn_bg_color_class; ?> <?php echo $add_to_card_btn_border_color_class; ?>"><?php _e('ناموجود'); ?></button>
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <?php
                                $last_price = get_post_meta($product_id, 'irantheme_product_meta_last_price_amount', true);
                                $last_price_date = get_post_meta($product_id, 'irantheme_product_meta_last_price_date', true);
                                $support_phone_number = get_option('irantheme_single_product_support_phone_number', '0913XXXXXXX');
                                ?>
                                <?php if ($last_price != '' && $last_price_date != ''): ?>
                                    <div class="product-add-to-card-last-price">
                                        <div class="product-add-to-card-last-price-label">
                                            <span>آخرین قیمت</br>ثــــبت شــده</span>
                                        </div>
                                        <div class="product-add-to-card-last-price-value">
                                            <span class="product-add-to-card-last-price-value-date"><?php echo esc_attr($last_price_date); ?></span>
                                            <span class="product-add-to-card-last-price-value-price"><?php echo wc_price(esc_attr($last_price)); ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <span class="product-add-to-card-tip call-for-price-info">
                                    <span class="product-add-to-card-tip-icon">
                                        <i class="fa-light fa-phone-volume"></i>
                                    </span>
                                    <span class="product-add-to-card-tip-text">برای استعلام قیمت و موجودی تماس بگیرید.</span>
                                </span>
                                <span class="product-add-to-card-call-info">
                                    <span class="product-add-to-card-call-info-label"><?php _e('شماره تماس پشتیبانی'); ?></span>
                                    <span class="product-add-to-card-call-info-number"><?php echo esc_html($support_phone_number); ?></span>
                                </span>
                                <div class="product-add-to-card-button call-for-price-info">
                                    <a href="tel:<?php echo esc_attr($support_phone_number) ?>" class="irantheme-single-add-to-cart-button single_add_to_cart_button button <?php echo $product_status; ?> <?php echo $add_to_card_btn_color_class; ?> <?php echo $add_to_card_btn_bg_color_class; ?> <?php echo $add_to_card_btn_hover_color_class; ?> <?php echo $add_to_card_btn_border_color_class; ?>"><?php _e('تماس بگیرید'); ?></a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>