<?php

namespace Irantheme_Dev;

use WP_Query;

if (!defined('ABSPATH')) exit;


if (!class_exists('Irantheme_Common_Static')) {

    /**
     * Create custom fields for post types
     *
     * @package Wordpress
     * @subpackage irantheme
     * @since irantheme - custom fields
     * @author iranianclever
     * @see v1.0
     *
     */

    class Irantheme_Common_Static
    {
        /**
         * Constructor
         * @return void
         * @param void
         * @author M_MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        function __construct() {}

        /**
         * Generate gallery magnification holder
         * @return void
         * @param string $magnification_id
         * @param array $magnification_tools
         * @param string $magnification_bg_color
         * @param array $media_element_array
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        public static function gallery_magnification($magnification_id, $magnification_tools, $magnification_bg_color, $media_element_array)
        {
            $item_index = count($media_element_array);
            $bg_color_class = '';
            if ($magnification_bg_color == 'dark') {
                $bg_color_class = 'irantheme-magnification-bg-dark';
            } elseif ($magnification_bg_color == 'white') {
                $bg_color_class = 'irantheme-magnification-bg-white';
            }
?>
            <div id="<?php echo esc_attr($magnification_id); ?>" class="irantheme-gallery-magnification-slider <?php echo esc_attr($bg_color_class); ?>">
                <div class="irantheme-gallery-magnification-slider-wrapper">
                    <div class="irantheme-gallery-magnification-slider-topbar">
                        <div class="irantheme-gallery-magnification-slider-topbar-action">
                            <button class="irantheme-gallery-magnification-slider-topbar-action-btn irantheme-gallery-magnification-slider-action-close" type="button"><i class="fa-light fa-xmark"></i></span></button>
                            <?php foreach ($magnification_tools as $value) {
                                if ($value == 'download') {
                            ?>
                                    <a href="" class="irantheme-gallery-magnification-slider-topbar-action-btn irantheme-gallery-magnification-slider-action-download" target="_blank" download><i class="fa-light fa-arrow-down-to-bracket"></i></a>
                                <?php
                                } elseif ($value == 'fullscreen-toggle') {
                                ?>
                                    <button class="irantheme-gallery-magnification-slider-topbar-action-btn irantheme-gallery-magnification-slider-action-full-screen-toggle" type="button"><i class="fa-light fa-expand"></i></button>
                                <?php
                                } elseif ($value == 'share') {
                                ?>
                                    <div class="irantheme-gallery-magnification-slider-action-share-holder">
                                        <button class="irantheme-gallery-magnification-slider-topbar-action-btn irantheme-gallery-magnification-slider-action-share" type="button"><i class="fa-light fa-share-nodes"></i>
                                        </button>
                                        <ul class="irantheme-gallery-magnification-slider-share-dropdown">
                                            <li class="irantheme-share-telegram"><a rel="noopener" target="_blank" href="#"><i class="fa-brands fa-telegram"></i><span>تلگرام</span></a></li>
                                            <li class="irantheme-share-x"><a rel="noopener" target="_blank" href="#"><i class="fa-brands fa-x-twitter"></i><span>ایکس (توئیتر)</span></a></li>
                                            <li class="irantheme-share-whatsapp"><a rel="noopener" target="_blank" href="#"><i class="fa-brands fa-whatsapp"></i><span>واتس آپ</span></a></li>
                                            <li class="irantheme-share-pinterest"><a rel="noopener" target="_blank" href="#"><i class="fa-brands fa-pinterest"></i><span>پینترست</span></a></li>
                                        </ul>
                                    </div>
                            <?php
                                }
                            } ?>
                        </div>
                        <div class="irantheme-gallery-magnification-slider-topbar-page">
                            <span class="irantheme-gallery-magnification-slider-topbar-page-all"><?php echo esc_attr($item_index); ?></span>
                            <span class="irantheme-gallery-magnification-slider-topbar-page-separator">/</span>
                            <span class="irantheme-gallery-magnification-slider-topbar-page-current">Nan</span>
                        </div>
                    </div>
                    <div class="irantheme-gallery-magnification-slider-figure swiper">
                        <div class="swiper-wrapper">
                            <?php
                            $slide_index = 1;
                            foreach ($media_element_array as $slider_item) :
                                if (empty($slider_item)) {
                                    continue;
                                }

                                // Detect attachment file type
                                $file_info = wp_check_filetype($slider_item);
                                $file_type = !empty($file_info['type']) ? explode('/', $file_info['type'])[0] : 'image';
                            ?>
                                <div class="irantheme-gallery-magnification-slider-figure-item swiper-slide"
                                    data-slide-id="<?php echo esc_attr($slide_index); ?>"
                                    data-url="<?php echo esc_url($slider_item); ?>">

                                    <?php if ($file_type == 'video') : ?>
                                        <video class="irantheme-gallery-magnification-slider-figure-item-video" controls playsinline>
                                            <source src="<?php echo esc_url($slider_item); ?>" type="<?php echo esc_attr($file_info['type']); ?>">
                                            <?php _e('مرورگر شما ویدیو را پشتیبانی نمی‌کند.', IRANTHEME_TEXTDOMAIN); ?>
                                        </video>

                                    <?php else : // Default to image 
                                    ?>
                                        <?php
                                        // Try to get image ID from URL for better handling
                                        $media_id = Irantheme_Section_Utils::get_image_id_from_url($slider_item);

                                        if ($media_id) {
                                            echo wp_get_attachment_image(
                                                $media_id,
                                                'large',
                                                false,
                                                [
                                                    'class' => 'irantheme-gallery-magnification-slider-figure-item-img',
                                                    'alt' => sprintf(__('تصویر گالری - اسلاید %d', IRANTHEME_TEXTDOMAIN), $slide_index),
                                                    'loading' => 'lazy',
                                                ]
                                            );
                                        }
                                        ?>

                                    <?php endif; ?>
                                </div>
                            <?php
                                $slide_index++;
                            endforeach;
                            ?>
                        </div>

                        <?php if (count($media_element_array) > 1) : ?>
                            <?php
                            Irantheme_Section_Utils::get_slider_arrow_pagination('on', 'on', 'off', $magnification_bg_color, 1, 'bottom', 'center');
                            ?>
                        <?php endif; ?>
                    </div>
                    <div dir="rtl" class="irantheme-gallery-magnification-slider-thumb swiper" thumbsSlider="">
                        <div class="irantheme-gallery-magnification-slider-thumb-wrapper swiper-wrapper">
                            <?php
                            $thumb_index = 1;
                            foreach ($media_element_array as $thumb_item) :
                                if (empty($thumb_item)) {
                                    continue;
                                }

                                $file_info = wp_check_filetype($thumb_item);
                                $file_type = !empty($file_info['type']) ? explode('/', $file_info['type'])[0] : 'image';
                            ?>
                                <div class="irantheme-gallery-magnification-slider-thumb-item swiper-slide"
                                    data-slide-id="<?php echo esc_attr($thumb_index); ?>"
                                    role="button"
                                    tabindex="0"
                                    aria-label="<?php echo esc_attr(sprintf(__('نمایش اسلاید %d', IRANTHEME_TEXTDOMAIN), $thumb_index)); ?>">

                                    <?php if ($file_type === 'video') : ?>
                                        <div class="irantheme-gallery-magnification-thumb-video-wrapper">
                                            <video class="irantheme-gallery-magnification-slider-thumb-video" muted playsinline>
                                                <source src="<?php echo esc_url($thumb_item); ?>" type="<?php echo esc_attr($file_info['type']); ?>">
                                            </video>
                                            <div class="irantheme-gallery-video-play-icon">
                                                <i class="fas fa-play" aria-hidden="true"></i>
                                            </div>
                                        </div>

                                    <?php else : ?>
                                        <?php
                                        // Try to get image ID from URL for better handling
                                        $media_id = Irantheme_Section_Utils::get_image_id_from_url($thumb_item);

                                        if ($media_id) {
                                            echo wp_get_attachment_image(
                                                $media_id,
                                                'thumbnail',
                                                false,
                                                [
                                                    'class' => 'irantheme-gallery-magnification-slider-thumb-img',
                                                    'alt' => sprintf(__('تصویر بندانگشتی %d', IRANTHEME_TEXTDOMAIN), $thumb_index),
                                                    'loading' => 'lazy',
                                                ]
                                            );
                                        }
                                        ?>

                                    <?php endif; ?>
                                </div>
                            <?php
                                $thumb_index++;
                            endforeach;
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        }

        /**
         * Add gallery post with grid style
         * @return void
         * @param void
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        public static function gallery_grid_post($post_content, $post_type, $column_count, $cell_gap, $aos_delay, $data_load_attr, $data_load_duration, $data_type, $data_value, $data_page, $data_ajax, $grid_type = 'grid', $hover_style_mode = '', $post_info_type = '', $post_info_icon = '', $post_content_align = '', $post_content_vertical_align = '', $extra_styles = '', $border_radius_css = '', $have_object = true, $fields_values = array(''))
        {
            $col_count_class = 'irantheme-grid-four-col';
            $col_count_num = 4;
            if ($column_count == 'col-1') {
                $col_count_class = 'irantheme-grid-one-col';
                $col_count_num = 1;
            } elseif ($column_count == 'col-2') {
                $col_count_class = 'irantheme-grid-two-col';
                $col_count_num = 2;
            } elseif ($column_count == 'col-3') {
                $col_count_class = 'irantheme-grid-three-col';
                $col_count_num = 3;
            } elseif ($column_count == 'col-4') {
                $col_count_class = 'irantheme-grid-four-col';
                $col_count_num = 4;
            } elseif ($column_count == 'col-6') {
                $col_count_class = 'irantheme-grid-six-col';
                $col_count_num = 6;
            }

            $extra_grid_type_class = '';
            if ($grid_type == 'metro-rtl') {
                $extra_grid_type_class = 'irantheme-grid-metro-rtl-type';
            } elseif ($grid_type == 'metro-ltr') {
                $extra_grid_type_class = 'irantheme-grid-metro-ltr-type';
            }
            // Count of post
            $post_count = 0;

            if ($post_type == 'media_gallery') {
                $element_array = explode(',', $post_content);
            } else {
                $element_array = $post_content;
            }
            foreach ($element_array as $value) {
                if ($value)
                    $post_count++;
            }

            // unique class generator
            $grid_style_unique_class = $data_type . '-' . $data_value . '-' . $data_page . '-' . $data_ajax;

            $extra_class = 'irantheme-post-gallery-grid-item';
            $class_name = ".irantheme-post-gallery-holder-" . $grid_style_unique_class . "." . $col_count_class . ".irantheme-grid-" . $grid_type . "-type .irantheme-post-gallery-item";

        ?>
            <style id="irantheme-gallery-grid-layout-style-<?php echo esc_attr($data_ajax); ?>" type="text/css">
                .irantheme-post-gallery-holder-<?php echo esc_attr($grid_style_unique_class) ?>.irantheme-post-gallery-grid {
                    gap: <?php echo esc_attr($cell_gap . 'px'); ?>;
                }

                .irantheme-post-gallery-holder-<?php echo esc_attr($grid_style_unique_class) ?>.irantheme-post-gallery-grid.irantheme-grid-metro-ltr-type {
                    direction: ltr;
                }

                <?php
                // Metro layout style
                if ($grid_type == 'metro-rtl' || $grid_type == 'metro-ltr') :
                    // For even 2 rows
                    $row_num = 1;
                    $child_num = 1;
                    while ($child_num <= $post_count) :

                ?>.irantheme-post-gallery-holder-<?php echo esc_attr($grid_style_unique_class) ?>.irantheme-post-gallery-grid.irantheme-grid-<?php echo esc_attr($grid_type); ?>-type .irantheme-post-gallery-item:nth-child(<?php echo esc_attr($child_num); ?>) {
                    <?php if ($col_count_num == 3 || $col_count_num == 4) : ?>grid-column: 1 / span 2;
                    grid-row: <?php echo esc_attr($row_num); ?> / span 2;

                    <?php elseif ($col_count_num == 2) : ?>grid-column: 1 / span 1;
                    grid-row: <?php echo esc_attr($row_num); ?> / span 2;

                    <?php elseif ($col_count_num == 6) : ?>grid-column: 1 / span 3;
                    grid-row: <?php echo esc_attr($row_num); ?> / span 2;

                    <?php endif; ?>
                }

                <?php
                        $row_num += 4;
                        if ($col_count_num == 3) {
                            $child_num += 6;
                        } elseif ($col_count_num == 4) {
                            $child_num += 10;
                        } elseif ($col_count_num == 2) {
                            $child_num += 6;
                        } elseif ($col_count_num == 6) {
                            $child_num += 14;
                        }
                    endwhile;
                    // For odd 2 rows
                    $row_num = 3;
                    if ($col_count_num == 3) {
                        $child_num = 5;
                    } elseif ($col_count_num == 4) {
                        $child_num = 8;
                    } elseif ($col_count_num == 2) {
                        $child_num = 5;
                    } elseif ($col_count_num == 6) {
                        $child_num = 11;
                    }
                    while ($child_num <= $post_count) :
                ?>.irantheme-post-gallery-holder-<?php echo esc_attr($grid_style_unique_class) ?>.irantheme-post-gallery-grid.irantheme-grid-<?php echo esc_attr($grid_type); ?>-type .irantheme-post-gallery-item:nth-child(<?php echo esc_attr($child_num); ?>) {
                    <?php if ($col_count_num == 3 || $col_count_num == 4) : ?>grid-column: <?php echo esc_attr($col_count_num - 1) ?> / span 2;
                    grid-row: <?php echo esc_attr($row_num); ?> / span 2;

                    <?php elseif ($col_count_num == 2) : ?>grid-column: 2 / span 1;
                    grid-row: <?php echo esc_attr($row_num); ?> / span 2;

                    <?php elseif ($col_count_num == 6) : ?>grid-column: 4 / span 3;
                    grid-row: <?php echo esc_attr($row_num); ?> / span 2;

                    <?php endif; ?>
                }

                <?php $row_num += 4;

                        if ($col_count_num == 3) {
                            $child_num += 6;
                        } elseif ($col_count_num == 4) {
                            $child_num += 10;
                        } elseif ($col_count_num == 2) {
                            $child_num += 6;
                        } elseif ($col_count_num == 6) {
                            $child_num += 14;
                        }

                    endwhile;
                ?>
                /* Metro layout style media query*/

                @media (max-width: 767.98px) {
                    <?php
                    for ($i = 1; $i <= $post_count; $i++) :
                    ?>.irantheme-post-gallery-holder-<?php echo esc_attr($grid_style_unique_class) ?>.irantheme-post-gallery-grid.irantheme-grid-<?php echo esc_attr($grid_type); ?>-type .irantheme-post-gallery-item:nth-child(<?php echo esc_attr($i); ?>) {
                        grid-column: unset !important;
                        grid-row: unset !important;
                    }

                    <?php
                    endfor; ?>
                }

                @media (max-width: 575.98px) {
                    <?php
                    for ($i = 1; $i <= $post_count; $i++) :
                    ?>.irantheme-post-gallery-holder-<?php echo esc_attr($grid_style_unique_class) ?>.irantheme-post-gallery-grid.irantheme-grid-<?php echo esc_attr($grid_type); ?>-type .irantheme-post-gallery-item:nth-child(<?php echo esc_attr($i); ?>) {
                        grid-column: unset !important;
                        grid-row: unset !important;
                    }

                    <?php
                    endfor; ?>
                }

                <?php
                endif;
                ?>
            </style>
            <div class="irantheme-object-group irantheme-post-gallery-holder-<?php echo esc_attr($grid_style_unique_class) ?> irantheme-post-gallery-grid <?php echo esc_attr($col_count_class); ?> <?php echo esc_attr($extra_grid_type_class); ?>" data-grid-gap="<?php echo esc_attr($cell_gap); ?>" data-json="<?php echo esc_attr('objects_json'); ?>">
                <?php
                if ($post_type == 'media_gallery') {
                    self::media_gallery_item_generator($post_content, $aos_delay, $data_load_attr, $data_load_duration, $col_count_num, $hover_style_mode, $grid_type, $extra_class, $border_radius_css);
                } elseif ($post_type == 'post_gallery') {
                    self::post_gallery_item_generator($post_content, $aos_delay, $data_load_attr, $data_load_duration, $col_count_num, $hover_style_mode, $grid_type, $post_info_type, $extra_class, $post_info_icon, $post_content_align, $post_content_vertical_align, $border_radius_css);
                } elseif ($post_type == 'banner_gallery') {
                    self::banner_gallery_item_generator($data_page, $post_content, $aos_delay, $data_load_attr, $data_load_duration, $col_count_num, $hover_style_mode, $grid_type, $extra_class, $post_info_icon, $post_content_align, $post_content_vertical_align, $extra_styles, $border_radius_css);
                } elseif ($post_type == 'slider_gallery') {
                    self::slider_gallery_item_generator($data_page, $post_content, $aos_delay, $data_load_attr, $data_load_duration, $col_count_num, $hover_style_mode, $grid_type, $extra_class, $post_info_icon, $post_content_align, $post_content_vertical_align, $extra_styles, $border_radius_css);
                } elseif ($post_type == 'type_gallery') {
                    self::type_gallery_item_generator($data_page, $post_content, $aos_delay, $data_load_attr, $data_load_duration, $col_count_num, $hover_style_mode, $grid_type, $extra_class, $post_info_icon, $post_content_align, $post_content_vertical_align, $extra_styles, $border_radius_css);
                } elseif ($post_type == 'business_portfolio') {
                    self::business_portfolio_item_generator($data_page, $fields_values, $extra_styles, $extra_class, $col_count_num);
                }
                ?>
                <?php
                // Check post-type for objective section or no
                if ($have_object) {
                    Irantheme_Page_Builder::get_hidden_require_object_group_buttons($data_page);
                }
                ?>
            </div>
        <?php
        }


        /**
         * Add gallery post with justified style
         * @return void
         * @param void
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        public static function gallery_justified_post($post_content, $post_type, $column_count, $cell_gap, $aos_delay, $data_load_attr, $data_load_duration, $data_type, $data_value, $data_page, $data_ajax, $gallery_style, $hover_style_mode = '', $post_info_type = '', $post_info_icon = '', $post_content_align = '', $post_content_vertical_align = '', $extra_styles = '', $border_radius_css = '', $have_object = true, $fields_values = array(''))
        {
            // Set column count
            $col_count_class = '';
            $col_count_num = 4;
            if ($column_count == 'col-1') {
                $col_count_class = 'irantheme-grid-one-col';
                $col_count_num = 1;
            } elseif ($column_count == 'col-2') {
                $col_count_class = 'irantheme-grid-two-col';
                $col_count_num = 2;
            } elseif ($column_count == 'col-3') {
                $col_count_class = 'irantheme-grid-three-col';
                $col_count_num = 3;
            } elseif ($column_count == 'col-4') {
                $col_count_class = 'irantheme-grid-four-col';
                $col_count_num = 4;
            } elseif ($column_count == 'col-6') {
                $col_count_class = 'irantheme-grid-six-col';
                $col_count_num = 6;
            }

            // unique class generator
            $justified_style_unique_class = $data_type . '-' . $data_value . '-' . $data_page . '-' . $data_ajax;

            // Calculate width for justified layout
            $gap_percentage = ($cell_gap / 12); // Convert to percentage based on 12-column grid
            $col_width = (100 - ($col_count_num - 1) * $gap_percentage) / $col_count_num;
            $col_width_with_gap = $col_width + $gap_percentage;
            $first_col_width = $col_width;
            $last_col_width = $col_width;

            // For more dynamic justified layout, we need to use flexbox or CSS grid with specific widths
        ?>
            <style id="irantheme-gallery-justified-layout-style-<?php echo esc_attr($data_ajax); ?>" type="text/css">
                .irantheme-post-gallery-holder-<?php echo esc_attr($justified_style_unique_class) ?>.irantheme-post-gallery-justified {
                    display: flex;
                    flex-wrap: wrap;
                    gap: <?php echo esc_attr($cell_gap . 'px'); ?>;
                    justify-content: flex-start;
                    width: 100%;
                }

                .irantheme-post-gallery-holder-<?php echo esc_attr($justified_style_unique_class) ?>.irantheme-post-gallery-justified .irantheme-post-gallery-item {
                    flex: 0 0 auto;
                    margin: 0;
                    <?php if ($col_count_num > 1): ?>width: calc((100% - <?php echo ($col_count_num - 1) * $cell_gap; ?>px) / <?php echo $col_count_num; ?>);
                    <?php else: ?>width: 100%;
                    <?php endif; ?>box-sizing: border-box;
                    position: relative;
                }

                /* Make sure images fill their containers */
                .irantheme-post-gallery-holder-<?php echo esc_attr($justified_style_unique_class) ?>.irantheme-post-gallery-justified .irantheme-post-gallery-item img,
                .irantheme-post-gallery-holder-<?php echo esc_attr($justified_style_unique_class) ?>.irantheme-post-gallery-justified .irantheme-post-gallery-item video {
                    width: 100%;
                    height: auto;
                    display: block;
                    object-fit: cover;
                }

                <?php if ($col_count_num > 1): ?>
                /* Responsive breakpoints */
                @media (max-width: 991.98px) {
                    .irantheme-post-gallery-holder-<?php echo esc_attr($justified_style_unique_class) ?>.irantheme-post-gallery-justified .irantheme-post-gallery-item {
                        <?php if ($col_count_num > 3): ?>width: calc((100% - <?php echo ($col_count_num > 3 ? 2 : ($col_count_num - 1)); ?> * <?php echo esc_attr($cell_gap); ?>px) / <?php echo ($col_count_num > 3 ? 3 : $col_count_num); ?>);
                        <?php endif; ?>
                    }
                }

                @media (max-width: 767.98px) {
                    .irantheme-post-gallery-holder-<?php echo esc_attr($justified_style_unique_class) ?>.irantheme-post-gallery-justified .irantheme-post-gallery-item {
                        width: calc((100% - <?php echo (min($col_count_num, 2) - 1); ?> * <?php echo esc_attr($cell_gap); ?>px) / <?php echo min($col_count_num, 2); ?>) !important;
                    }
                }

                @media (max-width: 575.98px) {
                    .irantheme-post-gallery-holder-<?php echo esc_attr($justified_style_unique_class) ?>.irantheme-post-gallery-justified .irantheme-post-gallery-item {
                        width: 100% !important;
                    }
                }

                <?php endif; ?>
            </style>

            <?php
            $extra_class = 'irantheme-post-gallery-justified-item';
            ?>
            <div class="irantheme-object-group irantheme-post-gallery-holder-<?php echo esc_attr($justified_style_unique_class); ?> irantheme-post-gallery-justified <?php echo esc_attr($col_count_class); ?>" data-grid-gap="<?php echo esc_attr($cell_gap); ?>" data-json="<?php echo esc_attr('objects_json'); ?>">
                <?php
                if ($post_type == 'media_gallery') {
                    self::media_gallery_item_generator($post_content, $aos_delay, $data_load_attr, $data_load_duration, $col_count_num, $hover_style_mode, $gallery_style, $extra_class, $border_radius_css);
                } elseif ($post_type == 'post_gallery') {
                    self::post_gallery_item_generator($post_content, $aos_delay, $data_load_attr, $data_load_duration, $col_count_num, $hover_style_mode, $gallery_style, $post_info_type, $extra_class, $post_info_icon, $post_content_align, $post_content_vertical_align, $border_radius_css);
                } elseif ($post_type == 'banner_gallery') {
                    self::banner_gallery_item_generator($data_page, $post_content, $aos_delay, $data_load_attr, $data_load_duration, $col_count_num, $hover_style_mode, $gallery_style, $extra_class, $post_info_icon, $post_content_align, $post_content_vertical_align, $extra_styles, $border_radius_css);
                } elseif ($post_type == 'slider_gallery') {
                    self::slider_gallery_item_generator($data_page, $post_content, $aos_delay, $data_load_attr, $data_load_duration, $col_count_num, $hover_style_mode, $gallery_style, $extra_class, $post_info_icon, $post_content_align, $post_content_vertical_align, $extra_styles, $border_radius_css);
                } elseif ($post_type == 'type_gallery') {
                    self::type_gallery_item_generator($data_page, $post_content, $aos_delay, $data_load_attr, $data_load_duration, $col_count_num, $hover_style_mode, $gallery_style, $extra_class, $post_info_icon, $post_content_align, $post_content_vertical_align, $extra_styles, $border_radius_css);
                } elseif ($post_type == 'business_portfolio') {
                    self::business_portfolio_item_generator($data_page, $fields_values, $extra_styles, $extra_class, $col_count_num);
                }
                ?>
                <?php
                if ($have_object) {
                    Irantheme_Page_Builder::get_hidden_require_object_group_buttons($data_page);
                }
                ?>
            </div>
        <?php
        }

        /**
         * Add gallery post with masonry style
         * @return void
         * @param void
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        public static function gallery_masonry_post($post_content, $post_type, $column_count, $cell_gap, $aos_delay, $data_load_attr, $data_load_duration, $data_type, $data_value, $data_page, $data_ajax, $gallery_style, $hover_style_mode = '', $post_info_type = '', $post_info_icon = '', $post_content_align = '', $post_content_vertical_align = '', $extra_styles = '', $border_radius_css = '', $have_object = true, $fields_values = array(''))
        {
            // Set col classes
            $col_count_class = '';
            // Set count of col
            $col_count_num = '';
            if ($column_count == 'col-1') {
                $col_count_class = 'irantheme-grid-one-col';
                $col_count_num = 1;
            } elseif ($column_count == 'col-2') {
                $col_count_class = 'irantheme-grid-two-col';
                $col_count_num = 2;
            } elseif ($column_count == 'col-3') {
                $col_count_class = 'irantheme-grid-three-col';
                $col_count_num = 3;
            } elseif ($column_count == 'col-4') {
                $col_count_class = 'irantheme-grid-four-col';
                $col_count_num = 4;
            } elseif ($column_count == 'col-6') {
                $col_count_class = 'irantheme-grid-six-col';
                $col_count_num = 6;
            }
            // unique class generator for masonry grid sizer and gutter sizer
            $masonry_style_unique_class = $data_type . '-' . $data_value . '-' . $data_page . '-' . $data_ajax;

            // Calculate the width column
            $result_col_width = (($col_count_num - 1) * $cell_gap);

            $extra_class = 'irantheme-grid-masonry-item';

        ?>

            <div class="irantheme-post-gallery-holder-<?php echo esc_attr($masonry_style_unique_class); ?> irantheme-post-gallery-masonry irantheme-grid-masonry <?php echo esc_attr($col_count_class); ?> irantheme-object-group" data-grid-gap="<?php echo esc_attr($cell_gap); ?>" data-json="<?php echo esc_attr('objects_json'); ?>">

                <style id="irantheme-gallery-masonry-layout-style-<?php echo esc_attr($data_ajax); ?>" type="text/css">
                    .irantheme-post-gallery-holder-<?php echo esc_attr($masonry_style_unique_class . ' '); ?>.irantheme-grid-masonry-gutter-sizer {
                        width: <?php echo esc_attr($cell_gap . 'px'); ?>;
                    }

                    .irantheme-post-gallery-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-grid-six-col .irantheme-grid-masonry-sizer,
                    .irantheme-post-gallery-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-grid-six-col .irantheme-grid-masonry-item {
                        width: calc((100% - <?php echo esc_attr($result_col_width . 'px'); ?>) / 6) !important;
                        margin-bottom: <?php echo esc_attr($cell_gap . 'px'); ?> !important;
                    }

                    .irantheme-post-gallery-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-grid-four-col .irantheme-grid-masonry-sizer,
                    .irantheme-post-gallery-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-grid-four-col .irantheme-grid-masonry-item {
                        width: calc((100% - <?php echo esc_attr($result_col_width . 'px'); ?>) / 4) !important;
                        margin-bottom: <?php echo esc_attr($cell_gap . 'px'); ?> !important;
                    }

                    .irantheme-post-gallery-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-grid-three-col .irantheme-grid-masonry-sizer,
                    .irantheme-post-gallery-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-grid-three-col .irantheme-grid-masonry-item {
                        width: calc((100% - <?php echo esc_attr($result_col_width . 'px'); ?>) / 3) !important;
                        margin-bottom: <?php echo esc_attr($cell_gap . 'px'); ?> !important;
                    }

                    .irantheme-post-gallery-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-grid-two-col .irantheme-grid-masonry-sizer,
                    .irantheme-post-gallery-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-grid-two-col .irantheme-grid-masonry-item {
                        width: calc((100% - <?php echo esc_attr($result_col_width . 'px'); ?>) / 2) !important;
                        margin-bottom: <?php echo esc_attr($cell_gap . 'px'); ?> !important;
                    }

                    .irantheme-post-gallery-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-grid-one-col .irantheme-grid-masonry-sizer,
                    .irantheme-post-gallery-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-grid-one-col .irantheme-grid-masonry-item {
                        width: calc((100% - <?php echo esc_attr($result_col_width . 'px'); ?>) / 1) !important;
                        margin-bottom: <?php echo esc_attr($cell_gap . 'px'); ?> !important;
                    }

                    .irantheme-media-gallery-masonry .irantheme-grid-masonry-sizer {
                        display: none !important;
                    }

                    /* Media (Responsive) */
                    @media (max-width: 1199.98px) {

                        .irantheme-post-gallery-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-grid-six-col .irantheme-grid-masonry-sizer,
                        .irantheme-post-gallery-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-grid-six-col .irantheme-grid-masonry-item {
                            width: calc((100% - <?php echo esc_attr($result_col_width - ($cell_gap * 2) . 'px'); ?>) / 4) !important;
                        }
                    }

                    @media (max-width: 991.98px) {

                        .irantheme-post-gallery-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-grid-four-col .irantheme-grid-masonry-sizer,
                        .irantheme-post-gallery-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-grid-four-col .irantheme-grid-masonry-item {
                            width: calc((100% - <?php echo esc_attr($result_col_width - $cell_gap . 'px'); ?>) / 3) !important;
                        }

                        .irantheme-post-gallery-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-grid-six-col .irantheme-grid-masonry-sizer,
                        .irantheme-post-gallery-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-grid-six-col .irantheme-grid-masonry-item {
                            width: calc((100% - <?php echo esc_attr($result_col_width - ($cell_gap * 3) . 'px'); ?>) / 3) !important;
                        }
                    }

                    @media (max-width: 767.98px) {

                        .irantheme-post-gallery-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-grid-three-col .irantheme-grid-masonry-sizer,
                        .irantheme-post-gallery-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-grid-three-col .irantheme-grid-masonry-item {
                            width: calc((100% - <?php echo esc_attr($result_col_width - $cell_gap . 'px'); ?>) / 2) !important;
                        }

                        .irantheme-post-gallery-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-grid-four-col .irantheme-grid-masonry-sizer,
                        .irantheme-post-gallery-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-grid-four-col .irantheme-grid-masonry-item {
                            width: calc((100% - <?php echo esc_attr($result_col_width - ($cell_gap * 2) . 'px'); ?>) / 2) !important;
                        }

                        .irantheme-post-gallery-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-grid-six-col .irantheme-grid-masonry-sizer,
                        .irantheme-post-gallery-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-grid-six-col .irantheme-grid-masonry-item {
                            width: calc((100% - <?php echo esc_attr($result_col_width - ($cell_gap * 4) . 'px'); ?>) / 2) !important;
                        }
                    }

                    @media (max-width: 575.98px) {

                        .irantheme-post-gallery-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-grid-two-col .irantheme-grid-masonry-sizer,
                        .irantheme-post-gallery-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-grid-two-col .irantheme-grid-masonry-item {
                            width: calc((100% - <?php echo esc_attr($result_col_width - $cell_gap . 'px'); ?>) / 1) !important;
                        }

                        .irantheme-post-gallery-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-grid-three-col .irantheme-grid-masonry-sizer,
                        .irantheme-post-gallery-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-grid-three-col .irantheme-grid-masonry-item {
                            width: calc((100% - <?php echo esc_attr($result_col_width - ($cell_gap * 2) . 'px'); ?>) / 1) !important;
                        }

                        .irantheme-post-gallery-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-grid-four-col .irantheme-grid-masonry-sizer,
                        .irantheme-post-gallery-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-grid-four-col .irantheme-grid-masonry-item {
                            width: calc((100% - <?php echo esc_attr($result_col_width - ($cell_gap * 3) . 'px'); ?>) / 1) !important;
                        }

                        .irantheme-post-gallery-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-grid-six-col .irantheme-grid-masonry-sizer,
                        .irantheme-post-gallery-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-grid-six-col .irantheme-grid-masonry-item {
                            width: calc((100% - <?php echo esc_attr($result_col_width - ($cell_gap * 5) . 'px'); ?>) / 1) !important;
                        }
                    }
                </style>

                <div class="irantheme-grid-masonry-sizer"></div>
                <div class="irantheme-grid-masonry-gutter-sizer"></div>
                <?php
                if ($post_type == 'media_gallery') {
                    self::media_gallery_item_generator($post_content, $aos_delay, $data_load_attr, $data_load_duration, $col_count_num, $hover_style_mode, $gallery_style, $extra_class, $border_radius_css);
                } elseif ($post_type == 'post_gallery') {
                    self::post_gallery_item_generator($post_content, $aos_delay, $data_load_attr, $data_load_duration, $col_count_num, $hover_style_mode, $gallery_style, $post_info_type, $extra_class, $post_info_icon, $post_content_align, $post_content_vertical_align, $border_radius_css);
                } elseif ($post_type == 'banner_gallery') {
                    self::banner_gallery_item_generator($data_page, $post_content, $aos_delay, $data_load_attr, $data_load_duration, $col_count_num, $hover_style_mode, $gallery_style, $extra_class, $post_info_icon, $post_content_align, $post_content_vertical_align, $extra_styles, $border_radius_css);
                } elseif ($post_type == 'slider_gallery') {
                    self::slider_gallery_item_generator($data_page, $post_content, $aos_delay, $data_load_attr, $data_load_duration, $col_count_num, $hover_style_mode, $gallery_style, $extra_class, $post_info_icon, $post_content_align, $post_content_vertical_align, $extra_styles, $border_radius_css);
                } elseif ($post_type == 'type_gallery') {
                    self::type_gallery_item_generator($data_page, $post_content, $aos_delay, $data_load_attr, $data_load_duration, $col_count_num, $hover_style_mode, $gallery_style, $extra_class, $post_info_icon, $post_content_align, $post_content_vertical_align, $extra_styles, $border_radius_css);
                } elseif ($post_type == 'business_portfolio') {
                    self::business_portfolio_item_generator($data_page, $fields_values, $extra_styles, $extra_class, $col_count_num);
                }
                ?>
                <?php
                // Check post-type for objective section or no
                if ($have_object) {
                    Irantheme_Page_Builder::get_hidden_require_object_group_buttons($data_page);
                }
                ?>
            </div>
        <?php
        }


        /**
         * Add gallery post with carousel style
         * @return void
         * @param void
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        public static function gallery_carousel_post($post_content, $post_type, $column_count, $cell_gap, $aos_delay, $data_load_attr, $data_load_duration, $data_type, $data_value, $data_page, $data_ajax, $gallery_style, $hover_style_mode = '', $post_info_type = '', $post_info_icon = '', $post_content_align = '', $post_content_vertical_align = '', $extra_styles = '', $border_radius_range = '', $have_object = true, $arrow_slider_visibility = true, $pagination_slider_visibility = true, $fields_values = array(''))
        {
            // Set col classes
            $col_count_class = '';
            // Set count of col
            $col_count_num = '';
            if ($column_count == 'col-1') {
                $col_count_class = 'irantheme-grid-one-col';
                $col_count_num = 1;
            } elseif ($column_count == 'col-2') {
                $col_count_class = 'irantheme-grid-two-col';
                $col_count_num = 2;
            } elseif ($column_count == 'col-3') {
                $col_count_class = 'irantheme-grid-three-col';
                $col_count_num = 3;
            } elseif ($column_count == 'col-4') {
                $col_count_class = 'irantheme-grid-four-col';
                $col_count_num = 4;
            } elseif ($column_count == 'col-6') {
                $col_count_class = 'irantheme-grid-six-col';
                $col_count_num = 6;
            }

            // Get border radius --------------------------------------------------------------
            $border_radius_style = Irantheme_Section_Utils::get_border_radius_css($border_radius_range);

            // unique class generator for masonry grid sizer and gutter sizer
            $carousel_unique_id = 'irantheme_media_gallery_carousel_' . $data_type . '_' . $data_value . '_' . $data_page . '_' . $data_ajax;

            // unique class generator
            $carousel_style_unique_class = $data_type . '-' . $data_value . '-' . $data_page . '-' . $data_ajax;

            $extra_class = 'swiper-slide';

        ?>
            <div id="<?php echo esc_attr($carousel_unique_id) ?>" class="irantheme-object-group irantheme-post-gallery-holder-<?php echo esc_attr($carousel_style_unique_class) ?> irantheme-post-gallery-carousel swiper irantheme-post-gallery-carousel-swiper <?php echo esc_attr($col_count_class); ?>" data-cell-gap="<?php echo esc_attr($cell_gap); ?>" data-columns="<?php echo esc_attr($col_count_num) ?>" data-json="<?php echo esc_attr('objects_json'); ?>">
                <div class="swiper-wrapper">
                    <?php
                    if ($post_type == 'media_gallery') {
                        self::media_gallery_item_generator($post_content, $aos_delay, $data_load_attr, $data_load_duration, $col_count_num, $hover_style_mode, $gallery_style, $extra_class, $border_radius_style);
                    } elseif ($post_type == 'post_gallery') {
                        self::post_gallery_item_generator($post_content, $aos_delay, $data_load_attr, $data_load_duration, $col_count_num, $hover_style_mode, $gallery_style, $post_info_type, $extra_class, $post_info_icon, $post_content_align, $post_content_vertical_align, $border_radius_style);
                    } elseif ($post_type == 'banner_gallery') {
                        self::banner_gallery_item_generator($data_page, $post_content, $aos_delay, $data_load_attr, $data_load_duration, $col_count_num, $hover_style_mode, $gallery_style, $extra_class, $post_info_icon, $post_content_align, $post_content_vertical_align, $extra_styles, $border_radius_style);
                    } elseif ($post_type == 'slider_gallery') {
                        self::slider_gallery_item_generator($data_page, $post_content, $aos_delay, $data_load_attr, $data_load_duration, $col_count_num, $hover_style_mode, $gallery_style, $extra_class, $post_info_icon, $post_content_align, $post_content_vertical_align, $extra_styles, $border_radius_style);
                    } elseif ($post_type == 'type_gallery') {
                        self::type_gallery_item_generator($data_page, $post_content, $aos_delay, $data_load_attr, $data_load_duration, $col_count_num, $hover_style_mode, $gallery_style, $extra_class, $post_info_icon, $post_content_align, $post_content_vertical_align, $extra_styles, $border_radius_style);
                    } elseif ($post_type == 'business_portfolio') {
                        self::business_portfolio_item_generator($data_page, $fields_values, $extra_styles, $extra_class, $col_count_num);
                    }
                    ?>
                </div>
                <?php
                Irantheme_Section_Utils::get_slider_arrow_pagination('on', $arrow_slider_visibility, $pagination_slider_visibility, 'white', $border_radius_range, 'bottom', 'center');
                ?>
                <?php
                // Check post-type for objective section or no
                if ($have_object) {
                    Irantheme_Page_Builder::get_hidden_require_object_group_buttons($data_page);
                }
                ?>
            </div>
            <?php
        }


        /**
         * Builder of media gallery items
         * @return void
         * @param void
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        public static function media_gallery_item_generator($media_elements, $aos_delay, $data_load_attr, $data_load_duration, $col_count_num, $hover_style_mode, $gallery_style, $extra_media_gallery_item_class = '', $border_radius_css = '')
        {
            // Assign hover class to item
            $hover_style_class = 'irantheme-media-gallery-item-hover-normal';
            if ($hover_style_mode == 'zoom-in') {
                $hover_style_class = 'irantheme-media-gallery-item-hover-zoom-in';
            } elseif ($hover_style_mode == 'mask') {
                $hover_style_class = 'irantheme-media-gallery-item-hover-mask';
            }
            // Set aos delay for gallery item
            $start_delay = $aos_delay;
            $increase_value = 150;
            $aos_delay = $start_delay;
            $max_delay = $start_delay + ($increase_value * ($col_count_num - 1));
            // Convert to array media id string
            $media_element_array = explode(',', $media_elements);
            $item_index = 0;
            foreach ($media_element_array as $media_url) :
                if ($media_url == '') {
                    continue;
                }
                // Detect attachment file type
                $media_ext
                    = explode('/', wp_check_filetype($media_url)['type'])[0];
            ?>
                <article class="irantheme-media-gallery-item irantheme-common-media-gallery-item irantheme-post-gallery-item <?php echo esc_attr($extra_media_gallery_item_class) ?> irantheme-set-height" style="<?php echo esc_attr($border_radius_css); ?>" data-item-id="<?php echo esc_attr($item_index); ?>">
                    <div class="irantheme-media-gallery-animation" <?php echo ($data_load_attr); ?> data-aos-delay="<?php echo ($aos_delay); ?>" data-aos-duration="<?php echo ($data_load_duration); ?>">
                        <div class="irantheme-media-gallery-item-inner-wrapper <?php echo esc_attr($hover_style_class) ?>">
                            <a href="<?php echo esc_attr($media_url); ?>">
                                <?php
                                // File type is video?
                                if ($media_ext == 'video') {
                                ?>
                                    <video class="irantheme-media-gallery-item-video">
                                        <source src="<?php echo esc_attr($media_url); ?>">
                                    </video>
                                    <div class="irantheme-media-gallery-item-video-button">
                                        <button class="irantheme-btn-void irantheme-media-gallery-item-video-button-trigger"><i class="iconsax" icon-name="play"></i></button>
                                    </div>
                                <?php
                                    // File type is image?
                                } elseif ($media_ext == 'image') {
                                ?>
                                    <?php
                                    $media_id = Irantheme_Section_Utils::get_image_id_from_url($media_url);
                                    echo wp_get_attachment_image(
                                        $media_id,
                                        'medium_large',
                                        false,
                                        [
                                            'class' => 'irantheme-media-gallery-item-image',
                                            'alt' => __('تصویر گالری', IRANTHEME_TEXTDOMAIN),
                                            'loading' => 'lazy',
                                        ]
                                    );
                                    ?>
                                <?php
                                }
                                if ($hover_style_mode == 'mask') {
                                ?>
                                    <div class="irantheme-gallery-item-hover-mask"><i class="fa-light fa-expand"></i></div>
                                <?php
                                }
                                ?>
                            </a>
                        </div>
                    </div>
                </article>
                <?php
                $item_index++;
                // Set aos delay
                if ($gallery_style == 'masonry' || $gallery_style == 'carousel') {
                    $aos_delay += $increase_value;
                } else {
                    if ($aos_delay < $max_delay) {
                        $aos_delay += $increase_value;
                    } else {
                        $aos_delay = $start_delay;
                    }
                }
            endforeach;
        }


        /**
         * Builder of gallery slider
         * @return void
         * @param mixed,string,string,string,string,string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function slider_gallery_item_generator($data_page, $array_post, $aos_delay, $data_load_attr, $data_load_duration, $col_count_num, $hover_style_mode, $gallery_style, $extra_media_gallery_item_class, $post_info_icon, $post_content_align, $post_content_vertical_align, $extra_styles = '', $border_radius_css = '')
        {
            // Get alignment class
            $content_alignment_class = Irantheme_Section_Service::get_alignment_class($post_content_align);

            // Set aos delay for gallery item
            $start_delay = $aos_delay;
            $increase_value = 150;
            $max_delay = $start_delay + ($increase_value * ($col_count_num - 1));

            $item_index = 0;
            foreach ($array_post as $object_post) :
                // Generate banner heading & value
                $banner_heading_value = $object_post['fields']['gallery_slider_heading'];
                // Generate banner description & value
                $banner_desc_value = $object_post['fields']['gallery_slider_desc'];
                // Generate banner background image
                $banner_bg_value = $object_post['fields']['gallery_slider_bg'];
                // Generate banner url
                $banner_url_value = $object_post['fields']['gallery_slider_url'];
                $attribute_style = '';
                if (!$banner_url_value) {
                    $attribute_style = 'cursor:default;';
                }
                ?>
                <!-- Banner item -->
                <article class="irantheme-object irantheme-gallery-slider-item irantheme-common-gallery-slider-item irantheme-post-gallery-item <?php echo esc_attr($extra_media_gallery_item_class) ?> irantheme-set-height" data-item-id="<?php echo esc_attr($item_index); ?>" data-object="<?php echo esc_attr($object_post['object_id']); ?>">
                    <div class="irantheme-gallery-slider-animation" <?php echo ($data_load_attr); ?> data-aos-delay="<?php echo ($aos_delay); ?>" data-aos-duration="<?php echo ($data_load_duration); ?>">
                        <!-- Link holder with bg -->
                        <a class="irantheme-gallery-slider-item-link" target="_blank" href="<?php echo esc_url_raw($banner_url_value); ?>" style="<?php echo esc_attr($extra_styles); ?><?php echo esc_attr($attribute_style); ?><?php echo esc_attr($border_radius_css); ?>">
                            <?php if ($banner_bg_value) : ?>
                                <!-- Banner image -->
                                <?php
                                echo wp_get_attachment_image(
                                    $banner_bg_value,
                                    'medium_large',
                                    false,
                                    [
                                        'class' => 'irantheme-gallery-slider-item-link-img',
                                        'alt' => esc_attr($banner_heading_value ?: __('بنر', IRANTHEME_TEXTDOMAIN)),
                                        'loading' => 'lazy',
                                    ]
                                );
                                ?>
                            <?php endif; ?>
                        </a>
                        <div class="irantheme-gallery-slider-item-title">
                            <?php if ($banner_heading_value) : ?>
                                <h3 class="irantheme-gallery-slider-item-content-heading <?php echo esc_attr($content_alignment_class); ?>">
                                    <!-- Banner heading -->
                                    <a href="<?php echo esc_url_raw($banner_url_value); ?>" class="irantheme-gallery-slider-item-content-heading-link" style="<?php echo esc_attr($attribute_style); ?>">
                                        <?php echo esc_html($banner_heading_value); ?>
                                    </a>
                                </h3>
                            <?php endif; ?>
                            <?php if ($banner_desc_value) : ?>
                                <p class="irantheme-gallery-slider-item-content-desc <?php echo esc_attr($content_alignment_class); ?>">
                                    <?php echo esc_html($banner_desc_value); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php
                    Irantheme_Page_Builder::get_hidden_require_object_buttons($data_page);
                    ?>
                </article>
                <?php
                $item_index++;
                // Set aos delay
                if ($gallery_style == 'masonry' || $gallery_style == 'carousel') {
                    $aos_delay += $increase_value;
                } else {
                    if ($aos_delay < $max_delay) {
                        $aos_delay += $increase_value;
                    } else {
                        $aos_delay = $start_delay;
                    }
                }
            endforeach;
        }

        /**
         * Builder of business portfolio
         * @return void
         * @param string,array,string,string,string
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        public static function business_portfolio_item_generator($data_page, $fields_values = array(), $extra_styles = '', $extra_class = '', $col_count_num = '')
        {
            // Json post data for post generation ------------------------------------------------------------
            $json_post_array = Irantheme_JSON_Helper::normalize_objects($fields_values['objects_json']);

            // Get gallery style --------------------------------------------------------------
            $gallery_style = $fields_values['gallery_type_style_radio'];

            // Get data load style --------------------------------------------------------------
            $data_load_attr = Irantheme_Section_Utils::get_data_load_attr_type($fields_values);
            // Get data load duration
            $data_load_duration = Irantheme_Section_Utils::get_data_load_duration($fields_values);
            // Get aos delay time
            $aos_delay = Irantheme_Section_Utils::get_data_load_delay($fields_values);

            // Set aos delay for gallery item
            $start_delay = $aos_delay;
            $increase_value = 150;
            $max_delay = $start_delay + ($increase_value * ($col_count_num - 1));

            // Set wrapper post card
            $wrapper_post_card_radio = $fields_values['wrapper_mode_radio'];
            $wrapper_post_card_range = $fields_values['post_wrapper_size_range'];
            $wrapper_post_content_range = $fields_values['post_content_wrapper_size_range'];
            $wrapper_post_card_css = '';
            $wrapper_post_content_css = '';
            $wrapper_post_class = '';
            if ($wrapper_post_card_radio == 'card-wrapper') {
                $wrapper_post_class = 'irantheme-portfolio-card-wrapper';
                $wrapper_post_card_css = 'padding:' . esc_attr($wrapper_post_card_range) . 'rem;';
            } elseif ($wrapper_post_card_radio == 'content-wrapper') {
                $wrapper_post_class = 'irantheme-portfolio-content-wrapper';
                $wrapper_post_content_css = 'padding:' . esc_attr($wrapper_post_content_range) . 'rem;';
            }

            // Set badge position
            $badge_mode = $fields_values['badge_mode_radio'];
            $badge_radius = $fields_values['badge_radius_size_range'];
            $badge_position = $fields_values['badge_position_radio'];
            $badge_radius_style = Irantheme_Section_Utils::get_border_radius_css($badge_radius);

            // Set button position
            $button_mode = $fields_values['button_mode_radio'];

            // Get button style and create class for him
            $button_style = $fields_values['btn_style_radio'];
            $button_style_class = '';
            if ($button_style == 'rectangle') {
                $button_style_class = '';
            } elseif ($button_style == 'circle') {
                $button_style_class = 'irantheme-btn-radius';
            }

            // Get shadow mode --------------------------------------------------------------
            $shadow_mode = $fields_values['shadow_checkbox'];
            // Set shadow class
            $shadow_class = '';
            if ($shadow_mode == 'on') {
                $shadow_class = 'irantheme-portfolio-item-shadow-little';
            }

            // Get border radius --------------------------------------------------------------
            $border_radius_range = $fields_values['border_radius_range'];
            $border_radius_style = Irantheme_Section_Utils::get_border_radius_css($border_radius_range);
            $image_radius_checkbox = $fields_values['apply_radius_to_post_image_checkbox'];
            $image_radius_style = '';
            if ($image_radius_checkbox == 'on' && $wrapper_post_card_radio == 'card-wrapper') {
                $image_radius_style = $border_radius_style;
            }

            $item_index = 0;
            foreach ($json_post_array as $object_post) :
                $base_color = $object_post['fields']['base_color_select'];
                // Generate post card name & value
                $post_card_heading = $object_post['fields']['heading'];
                // Generate post card name & value
                $post_card_desc = $object_post['fields']['description'];
                // Generate post card background image and color
                $post_card_image = $object_post['fields']['bg_image'];
                $post_card_bg_color = $object_post['fields']['bg_color'];
                $post_card_bg_color_css = Irantheme_Section_Utils::get_background_color_css($post_card_bg_color);
                $extra_content_class = '';
                if ($post_card_image == '') {
                    $extra_content_class = 'justify-content-center';
                }
                // Generate post card btns
                $post_card_btn_checkbox = $object_post['fields']['btn_checkbox'];
                $post_card_first_btn_title = $object_post['fields']['btn_primary_title'];
                $post_card_first_btn_link = $object_post['fields']['btn_primary_link'];
                $post_card_first_btn_bg_color = $object_post['fields']['btn_primary_color_select'];
                $button_primary_bg_class = self::get_button_color_class($base_color, $post_card_first_btn_bg_color);

                $post_card_second_btn_checkbox = $object_post['fields']['btn_secondary_checkbox'];
                $post_card_second_btn_title = $object_post['fields']['btn_secondary_title'];
                $post_card_second_btn_link = $object_post['fields']['btn_secondary_link'];
                $post_card_second_btn_bg_color = $object_post['fields']['btn_secondary_color_select'];
                $button_second_bg_class = self::get_button_color_class($base_color, $post_card_second_btn_bg_color);

                $post_card_btn_mask_bg_color = $object_post['fields']['hover_mask_button_select'];

                // Generate post card badge
                $post_card_badge_title = $object_post['fields']['badge_title'];
                // Set badge text color
                $badge_text_color = $object_post['fields']['badge_text_color_select'];

                // Get detail color --------------------------------------------------------------
                $detail_color_data = $object_post['fields']['badge_bg_color_select'];
                $badge_checkbox = $object_post['fields']['badge_checkbox'];
                $detail_bg_color_css = Irantheme_Section_Utils::get_background_color_css($detail_color_data);

                // Get post content align --------------------------------------------------------------
                $post_content_align = $object_post['fields']['align_content_radio'];
                $content_alignment_class = Irantheme_Section_Service::get_flex_column_alignment_class($post_content_align);
                $content_justify_class = Irantheme_Section_Service::get_flex_alignment_class($post_content_align);
                $content_alignment_class_none_flex = Irantheme_Section_Service::get_alignment_class($post_content_align);
                ?>
                <!-- post card item -->
                <article class="irantheme-object irantheme-business-portfolio-item irantheme-common-gallery-slider-item irantheme-post-gallery-item <?php echo esc_attr($base_color); ?> <?php echo esc_attr($extra_class) ?>" data-item-id="<?php echo esc_attr($item_index); ?>" data-object="<?php echo esc_attr($object_post['object_id']); ?>">
                    <div class="irantheme-business-portfolio-animation" <?php echo ($data_load_attr); ?> data-aos-delay="<?php echo ($aos_delay); ?>" data-aos-duration="<?php echo ($data_load_duration); ?>">
                        <!-- post card item holder -->
                        <div class="irantheme-business-portfolio-item-holder <?php echo $shadow_class; ?> <?php echo esc_attr($wrapper_post_class); ?>" style="<?php echo esc_attr($post_card_bg_color_css); ?><?php echo esc_attr($wrapper_post_card_css); ?><?php echo esc_attr($border_radius_style); ?>">
                            <!-- hero -->
                            <?php if ($post_card_image) : ?>
                                <div class="irantheme-business-portfolio-item-hero">
                                    <div class="irantheme-business-portfolio-item-hero-image-wrap irantheme-gallery-image-holder" style="<?php echo esc_attr($extra_styles); ?>">
                                        <!-- post card image -->
                                        <?php
                                        echo wp_get_attachment_image(
                                            $post_card_image,
                                            'medium_large',
                                            false,
                                            [
                                                'class' => 'irantheme-business-portfolio-item-hero-image irantheme-gallery-image irantheme-set-height',
                                                'alt' => esc_attr($post_card_heading ?: __('تصویر نمونه کار', IRANTHEME_TEXTDOMAIN)),
                                                'loading' => 'lazy',
                                                'style' => esc_attr($image_radius_style),
                                            ]
                                        );
                                        ?>
                                    </div>
                                    <?php if ($button_mode == 'image'): ?>
                                        <?php if ($post_card_btn_checkbox == 'on'): ?>
                                            <?php if ($post_card_first_btn_title || ($post_card_second_btn_checkbox == 'on' && $post_card_second_btn_title)): ?>
                                                <div class="irantheme-business-portfolio-item-hero-hover-button-wrap <?php echo esc_attr($post_card_btn_mask_bg_color); ?>" style="<?php echo $wrapper_post_card_radio == 'card-wrapper' ? $border_radius_style : ''; ?>">
                                                    <div class="irantheme-business-portfolio-item-hero-button-wrap">
                                                        <?php if ($post_card_first_btn_title): ?>
                                                            <a href="<?php echo esc_url_raw($post_card_first_btn_link); ?>" class="irantheme-business-portfolio-item-hero-button irantheme-btn <?php echo esc_attr($button_primary_bg_class); ?> <?php echo esc_attr($button_style_class); ?>" target="_blank"><?php echo esc_attr($post_card_first_btn_title); ?></a>
                                                        <?php endif; ?>
                                                        <?php if ($post_card_second_btn_checkbox == 'on' && $post_card_second_btn_title): ?>
                                                            <a href="<?php echo esc_url_raw($post_card_second_btn_link); ?>" class="irantheme-business-portfolio-item-hero-button irantheme-btn <?php echo esc_attr($button_second_bg_class); ?> <?php echo esc_attr($button_style_class); ?>" target="_blank"><?php echo esc_attr($post_card_second_btn_title); ?></a>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <?php if ($badge_checkbox == 'on'): ?>
                                        <?php if ($post_card_badge_title != '' && $badge_mode == 'image'): ?>
                                            <!-- post card badge in image -->
                                            <div class="irantheme-business-portfolio-item-hero-badge-wrap position-<?php echo esc_attr($badge_position); ?>" style="<?php echo esc_attr($detail_bg_color_css); ?><?php echo esc_attr($badge_radius_style); ?>">
                                                <span class="<?php echo esc_attr($badge_text_color); ?>"><?php echo esc_attr($post_card_badge_title); ?></span>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            <!-- content -->
                            <div class="irantheme-business-portfolio-item-content-wrap <?php echo esc_attr($content_alignment_class); ?> <?php echo esc_attr($content_alignment_class_none_flex); ?> <?php echo esc_attr($extra_content_class); ?>" style="<?php echo esc_attr($wrapper_post_content_css); ?>">
                                <?php if ($post_card_heading) : ?>
                                    <div class="irantheme-business-portfolio-item-content-heading-wrap <?php echo esc_attr($content_justify_class); ?> <?php echo esc_attr($content_alignment_class_none_flex); ?>">
                                        <!-- post card heading -->
                                        <h3 class="irantheme-business-portfolio-item-content-heading <?php echo esc_attr($content_justify_class); ?> <?php echo esc_attr($content_alignment_class_none_flex); ?>">
                                            <?php echo esc_attr($post_card_heading); ?>
                                        </h3>
                                        <?php if ($badge_checkbox == 'on'): ?>
                                            <?php if ($post_card_badge_title != ''): ?>
                                                <?php if ($badge_mode == 'heading' || !$post_card_image): ?>
                                                    <!-- post card badge in image -->
                                                    <div class="irantheme-business-portfolio-item-content-badge-wrap " style="<?php echo esc_attr($detail_bg_color_css); ?><?php echo esc_attr($badge_radius_style); ?>">
                                                        <span class="<?php echo esc_attr($badge_text_color); ?>"><?php echo esc_attr($post_card_badge_title); ?></span>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                <?php if ($post_card_desc) : ?>
                                    <!-- post card description -->
                                    <p class="irantheme-business-portfolio-item-content-description <?php echo esc_attr($content_alignment_class); ?> <?php echo esc_attr($content_alignment_class_none_flex); ?>">
                                        <?php echo esc_attr($post_card_desc); ?>
                                    </p>
                                <?php endif; ?>
                                <?php if (!$post_card_image || $button_mode == 'card'): ?>
                                    <?php if ($post_card_btn_checkbox == 'on'): ?>
                                        <?php if ($post_card_first_btn_title || ($post_card_second_btn_checkbox == 'on' && $post_card_second_btn_title)): ?>
                                            <div class="irantheme-business-portfolio-item-content-button-wrap <?php echo esc_attr($content_alignment_class); ?> <?php echo esc_attr($content_alignment_class_none_flex); ?>">
                                                <?php if ($post_card_first_btn_title): ?>
                                                    <a href="<?php echo esc_url_raw($post_card_first_btn_link); ?>" class="irantheme-business-portfolio-item-content-button irantheme-btn <?php echo esc_attr($button_primary_bg_class); ?> <?php echo esc_attr($button_style_class); ?>" target="_blank"><?php echo esc_attr($post_card_first_btn_title); ?></a>
                                                <?php endif; ?>
                                                <?php if ($post_card_second_btn_checkbox == 'on' && $post_card_second_btn_title): ?>
                                                    <a href="<?php echo esc_url_raw($post_card_second_btn_link); ?>" class="irantheme-business-portfolio-item-content-button irantheme-btn <?php echo esc_attr($button_second_bg_class); ?> <?php echo esc_attr($button_style_class); ?>" target="_blank"><?php echo esc_attr($post_card_second_btn_title); ?></a>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php
                    Irantheme_Page_Builder::get_hidden_require_object_buttons($data_page);
                    ?>
                </article>
                <?php
                $item_index++;
                // Set aos delay
                if ($gallery_style == 'masonry' || $gallery_style == 'carousel') {
                    $aos_delay += $increase_value;
                } else {
                    if ($aos_delay < $max_delay) {
                        $aos_delay += $increase_value;
                    } else {
                        $aos_delay = $start_delay;
                    }
                }
            endforeach;
        }


        /**
         * Builder of gallery type
         * @return void
         * @param mixed,string,string,string,string,string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function type_gallery_item_generator($data_page, $array_post, $aos_delay, $data_load_attr, $data_load_duration, $col_count_num, $hover_style_mode, $gallery_style, $extra_media_gallery_item_class, $post_info_icon, $post_content_align, $post_content_vertical_align, $extra_styles = '', $border_radius_css = '')
        {
            // Get alignment class
            $content_alignment_class = Irantheme_Section_Service::get_alignment_class($post_content_align);

            // Set aos delay for gallery item
            $start_delay = $aos_delay;
            $increase_value = 150;
            $max_delay = $start_delay + ($increase_value * ($col_count_num - 1));

            $item_index = 0;
            foreach ($array_post as $object_post) :
                // Generate banner gallery mode
                $banner_gallery_mode = $object_post['fields']['gallery_type_mode'];
                // Generate banner name & value
                $banner_heading_value = $object_post['fields']['gallery_type_heading'];
                // Generate banner desc
                $banner_desc_value = $object_post['fields']['gallery_type_desc'];
                // Generate banner url
                $banner_text_url_value = $object_post['fields']['gallery_type_text_url'];
                // Generate banner url
                $banner_url_value = $object_post['fields']['gallery_type_url'];
                $attribute_style = '';
                if (!$banner_url_value) {
                    $attribute_style = 'cursor:default;';
                }

                // Background options ------------------------------------------------------------
                $background_image_data = $object_post['fields']['bg_image'];
                $background_color_data = $object_post['fields']['bg_color'];
                $background_color_css = Irantheme_Section_Utils::get_background_color_css($background_color_data);

                // Get alignment class
                $post_content_align = $object_post['fields']['align_content_radio'];
                $content_alignment_class = Irantheme_Section_Service::get_flex_column_alignment_class($post_content_align);
                $content_alignment_class_none_flex = Irantheme_Section_Service::get_alignment_class($post_content_align);

                // Base color --------------------------------------------------------------
                $base_color = $object_post['fields']['base_color_select'];
                // Set base color class
                $base_color_class = Irantheme_Common_Static::get_base_color_class($base_color);
                ?>
                <!-- Banner item -->
                <article class="irantheme-object irantheme-gallery-type-item irantheme-common-gallery-type-item irantheme-post-gallery-item <?php echo esc_attr($extra_media_gallery_item_class) ?> <?php echo esc_attr($base_color_class) ?> irantheme-set-height" data-item-id="<?php echo esc_attr($item_index); ?>" data-object="<?php echo esc_attr($object_post['object_id']); ?>">
                    <div class="irantheme-gallery-type-animation" <?php echo ($data_load_attr); ?> data-aos-delay="<?php echo ($aos_delay); ?>" data-aos-duration="<?php echo ($data_load_duration); ?>" style="<?php echo esc_attr($background_color_css); ?><?php echo esc_attr($extra_styles); ?><?php echo esc_attr($border_radius_css); ?>">
                        <?php if ($banner_gallery_mode == 'image'): ?>
                            <!-- Image mode -->
                            <a class="irantheme-gallery-type-item-link" href="<?php echo esc_url_raw($banner_url_value); ?>" style="<?php echo esc_attr($attribute_style); ?>">
                                <?php if ($background_image_data) : ?>
                                    <!-- Banner image -->
                                    <?php
                                    echo wp_get_attachment_image(
                                        $background_image_data,
                                        'medium_large',
                                        false,
                                        [
                                            'class' => 'irantheme-gallery-type-item-link-img',
                                            'alt' => esc_attr($banner_heading_value ?: __('تصویر گالری', IRANTHEME_TEXTDOMAIN)),
                                            'loading' => 'lazy',
                                        ]
                                    );
                                    ?>
                                <?php endif; ?>
                            </a>
                        <?php elseif ($banner_gallery_mode == 'text'): ?>
                            <!-- Text mode -->
                            <div class="irantheme-gallery-type-item-content <?php echo esc_attr($content_alignment_class); ?> <?php echo esc_attr($content_alignment_class_none_flex); ?>">
                                <!-- Heading -->
                                <h3 class="irantheme-gallery-type-item-content-heading">
                                    <?php echo esc_attr($banner_heading_value); ?>
                                </h3>
                                <!-- Description -->
                                <p class="irantheme-gallery-type-item-content-desc">
                                    <?php echo esc_attr($banner_desc_value); ?>
                                </p>
                                <!-- Link -->
                                <a href="<?php echo esc_url_raw($banner_url_value); ?>" class="irantheme-gallery-type-item-content-link" style="<?php echo esc_attr($attribute_style); ?>">
                                    <?php echo esc_attr($banner_text_url_value); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php
                    Irantheme_Page_Builder::get_hidden_require_object_buttons($data_page);
                    ?>
                </article>
                <?php
                $item_index++;
                // Set aos delay
                if ($gallery_style == 'masonry' || $gallery_style == 'carousel') {
                    $aos_delay += $increase_value;
                } else {
                    if ($aos_delay < $max_delay) {
                        $aos_delay += $increase_value;
                    } else {
                        $aos_delay = $start_delay;
                    }
                }
            endforeach;
        }


        /**
         * Builder of media gallery items
         * @return void
         * @param mixed,string,string,string,string,string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function banner_gallery_item_generator($data_page, $array_post, $aos_delay, $data_load_attr, $data_load_duration, $col_count_num, $hover_style_mode, $gallery_style, $extra_media_gallery_item_class, $post_info_icon, $post_content_align, $post_content_vertical_align, $extra_styles = '', $border_radius_css = '')
        {
            // Get alignment class
            $content_alignment_class = Irantheme_Section_Service::get_alignment_class($post_content_align);
            // Set alignment style
            $content_alignment_style = '';
            if ($post_content_align == 'center') {
                $content_alignment_style = 'align-items:center;';
            } elseif ($post_content_align == 'right') {
                $content_alignment_style = 'align-items:flex-start;';
            } elseif ($post_content_align == 'left') {
                $content_alignment_style = 'align-items:flex-end;';
            }
            $content_vertical_alignment_style = '';
            if ($post_content_vertical_align == 'top') {
                $content_vertical_alignment_style = 'justify-content:flex-start;';
            } elseif ($post_content_vertical_align == 'middle') {
                $content_vertical_alignment_style = 'justify-content:center;';
            } elseif ($post_content_vertical_align == 'bottom') {
                $content_vertical_alignment_style = 'justify-content:flex-end;';
            }

            // Set aos delay for gallery item
            $start_delay = $aos_delay;
            $increase_value = 150;
            $max_delay = $start_delay + ($increase_value * ($col_count_num - 1));

            $item_index = 0;
            foreach ($array_post as $object_post) :
                // Generate banner name & value
                $banner_heading_value = $object_post['fields']['heading'];
                // Background options ------------------------------------------------------------
                $background_image_data = $object_post['fields']['bg_image'];
                $background_color_data = $object_post['fields']['bg_color'];
                $background_color_css = Irantheme_Section_Utils::get_background_color_css($background_color_data);
                // Generate banner url
                $banner_url_value = $object_post['fields']['url'];
                // Generate read more title
                $banner_read_more_title_value = $object_post['fields']['read_more_title'];
                ?>
                <!-- Banner item -->
                <article class="irantheme-object irantheme-gallery-banner-item irantheme-common-gallery-banner-item irantheme-post-gallery-item <?php echo esc_attr($extra_media_gallery_item_class) ?> irantheme-set-height" data-item-id="<?php echo esc_attr($item_index); ?>" style="<?php echo esc_attr($extra_styles); ?><?php echo esc_attr($border_radius_css); ?>" data-object="<?php echo esc_attr($object_post['object_id']); ?>">
                    <div class="irantheme-gallery-banner-animation" <?php echo ($data_load_attr); ?> data-aos-delay="<?php echo ($aos_delay); ?>" data-aos-duration="<?php echo ($data_load_duration); ?>">
                        <?php
                        // Get background mask checkbox
                        $mask_checkbox = $object_post['fields']['bg_mask_checkbox'];
                        // Get background mask color
                        $mask_color = $object_post['fields']['bg_mask_color'];
                        // Get background mask range
                        $mask_range = $object_post['fields']['bg_mask_range'];
                        // Get showcase mask --------------------------------------------------------------
                        Irantheme_Section_Utils::get_background_mask_tag_html($mask_checkbox, $mask_color, $mask_range);
                        ?>
                        <!-- Link holder with bg -->
                        <a class="irantheme-gallery-banner-item-link" href="<?php echo esc_attr($banner_url_value); ?>" style="<?php echo esc_attr($background_color_css); ?>">
                            <?php if ($background_image_data) : ?>
                                <!-- Banner image -->
                                <?php
                                echo wp_get_attachment_image(
                                    $background_image_data,
                                    'medium_large',
                                    false,
                                    [
                                        'class' => 'irantheme-gallery-banner-item-link-img',
                                        'alt' => esc_attr($banner_heading_value ?: __('بنر گالری', IRANTHEME_TEXTDOMAIN)),
                                        'loading' => 'lazy',
                                    ]
                                );
                                ?>
                            <?php endif; ?>
                            <div class="irantheme-gallery-banner-item-link-content <?php echo esc_attr($content_alignment_class); ?>" style="<?php echo esc_attr($content_alignment_style . $content_vertical_alignment_style); ?>">
                                <?php if ($banner_heading_value) : ?>
                                    <!-- Banner heading -->
                                    <h4 class="irantheme-gallery-banner-item-link-content-heading"><?php echo esc_attr($banner_heading_value); ?></h4>
                                <?php endif; ?>
                                <?php if ($banner_read_more_title_value) : ?>
                                    <!-- Banner read more text -->
                                    <p class="irantheme-gallery-banner-item-link-content-read-more"><?php echo esc_html($banner_read_more_title_value); ?></p>
                                <?php endif; ?>
                            </div>
                        </a>
                    </div>
                    <?php
                    Irantheme_Page_Builder::get_hidden_require_object_buttons($data_page);
                    ?>
                </article>
                <?php
                $item_index++;
                // Set aos delay
                if ($gallery_style == 'masonry' || $gallery_style == 'carousel') {
                    $aos_delay += $increase_value;
                } else {
                    if ($aos_delay < $max_delay) {
                        $aos_delay += $increase_value;
                    } else {
                        $aos_delay = $start_delay;
                    }
                }
            endforeach;
        }


        /**
         * Builder of post gallery items
         * @return void
         * @param void
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        public static function post_gallery_item_generator($post_elements, $aos_delay, $data_load_attr, $data_load_duration, $col_count_num, $hover_style_mode, $gallery_style,  $post_info_type, $extra_post_gallery_item_class, $post_info_icon, $post_content_align, $post_content_vertical_align, $border_radius_css)
        {
            // Check posts content
            if (!$post_elements) {
                return;
            }

            // Get alignment class
            $content_alignment_class = Irantheme_Section_Service::get_alignment_class($post_content_align);
            // Set alignment style
            $content_alignment_style = '';
            if ($post_content_align == 'center') {
                $content_alignment_style = 'center';
            } elseif ($post_content_align == 'right') {
                $content_alignment_style = 'flex-start';
            } elseif ($post_content_align == 'left') {
                $content_alignment_style = 'flex-end';
            }
            $content_vertical_alignment_style = '';
            if ($post_content_vertical_align == 'middle') {
                $content_vertical_alignment_style = 'center';
            } elseif ($post_content_vertical_align == 'bottom') {
                $content_vertical_alignment_style = 'flex-end';
            }
            // Assign hover class to item
            $hover_style_class = 'irantheme-post-gallery-item-hover-normal';
            if ($hover_style_mode == 'transform') {
                $hover_style_class = 'irantheme-post-gallery-item-hover-transform';
            } elseif ($hover_style_mode == 'mask') {
                $hover_style_class = 'irantheme-post-gallery-item-hover-mask';
            }

            // Set aos delay for gallery item
            $start_delay = $aos_delay;
            $increase_value = 150;
            $max_delay = $start_delay + ($increase_value * ($col_count_num - 1));
            // Convert to array media id string
            $item_index = 0;
            // While on wp_query posts
            if ($post_elements->have_posts()) {
                while ($post_elements->have_posts()) {
                    $post_elements->the_post();
                    // Gallery post item
                ?>
                    <article class="irantheme-post-gallery-post-item irantheme-post-gallery-item <?php echo esc_attr($extra_post_gallery_item_class) ?>" data-item-id="<?php echo esc_attr($item_index); ?>" style="<?php echo esc_attr($border_radius_css); ?>">
                        <div class="irantheme-post-gallery-animation" <?php echo ($data_load_attr); ?> data-aos-delay="<?php echo ($aos_delay); ?>" data-aos-duration="<?php echo ($data_load_duration); ?>">
                            <div class="irantheme-post-gallery-post-item-inner-wrapper  <?php echo esc_attr($hover_style_class); ?>">
                                <a href="<?php echo get_permalink(); ?>">
                                    <!-- Post item overlay -->
                                    <div class="irantheme-post-gallery-post-item-overlay"></div>
                                    <!-- Category of post -->
                                    <div class="irantheme-post-gallery-post-item-category">
                                        <?php
                                        // check on selected post info type for generate category
                                        foreach ($post_info_type as $type) :
                                            if ($type == 'category') :
                                        ?>
                                                <ul>
                                                    <?php
                                                    foreach (get_the_category() as $category) {
                                                    ?>
                                                        <li>
                                                            <?php
                                                            echo $category->name . ' ';
                                                            ?>
                                                        </li>
                                                    <?php
                                                    }
                                                    ?>
                                                </ul>
                                        <?php
                                            endif;
                                        endforeach;
                                        ?>
                                    </div>
                                    <!-- Post item content -->
                                    <div class="irantheme-post-gallery-post-item-content <?php echo esc_attr($content_alignment_class); ?>" style="justify-content: <?php echo esc_attr($content_vertical_alignment_style); ?>;">
                                        <!-- Title of post -->
                                        <h3 class="irantheme-post-gallery-post-item-content-title"><?php the_title(); ?></h3>
                                        <!-- Info of post -->
                                        <div class="irantheme-post-gallery-post-item-content-info" style="justify-content: <?php echo esc_attr($content_alignment_style); ?>;">
                                            <?php
                                            // check on selected post info type for more info
                                            foreach ($post_info_type as $type) :
                                                if ($type == 'date') :
                                            ?>
                                                    <div class="irantheme-post-gallery-post-item-content-info-date irantheme-post-gallery-post-item-content-info-item">
                                                        <?php
                                                        echo $post_info_icon == 'on' ? '<i class="fa-light fa-calendar-days"></i>' : '';
                                                        echo get_the_date('j F Y');
                                                        ?>
                                                    </div>
                                                <?php
                                                elseif ($type == 'author') :
                                                ?>
                                                    <div class="irantheme-post-gallery-post-item-content-info-author irantheme-post-gallery-post-item-content-info-item"><?php
                                                                                                                                                                            echo $post_info_icon == 'on' ? '<i class="fa-light fa-pen-to-square"></i>' : '';
                                                                                                                                                                            the_author();
                                                                                                                                                                            ?></div>
                                                <?php
                                                elseif ($type == 'comment') :
                                                ?>
                                                    <div class="irantheme-post-gallery-post-item-content-info-comment irantheme-post-gallery-post-item-content-info-item"><?php
                                                                                                                                                                            echo $post_info_icon == 'on' ? '<i class="fa-light fa-comments"></i>' : '';
                                                                                                                                                                            echo get_comments_number();
                                                                                                                                                                            ?><span>دیدگاه</span></div>
                                            <?php
                                                endif;
                                            endforeach;
                                            ?>
                                        </div>
                                    </div>
                                    <?php
                                    // Check selected thumb for post
                                    if (has_post_thumbnail()) :
                                    ?>
                                        <div class="irantheme-post-gallery-post-item-image">
                                            <?php
                                            echo wp_get_attachment_image(
                                                get_post_thumbnail_id(),
                                                'medium_large',
                                                false,
                                                [
                                                    'alt' => get_the_title() ?: __('تصویر پست', IRANTHEME_TEXTDOMAIN),
                                                    'loading' => 'lazy',
                                                ]
                                            );
                                            ?>
                                        </div>
                                    <?php
                                    endif;
                                    ?>
                                </a>
                            </div>
                        </div>
                    </article>
                <?php
                    $item_index++;
                    // Set aos delay
                    if ($gallery_style == 'masonry' || $gallery_style == 'carousel') {
                        $aos_delay += $increase_value;
                    } else {
                        if ($aos_delay < $max_delay) {
                            $aos_delay += $increase_value;
                        } else {
                            $aos_delay = $start_delay;
                        }
                    }
                }
            }
        }


        /**
         * Add post box with masonry style
         * @return void
         * @param void
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        public static function blog_post_masonry($post_type, $post_elements, $fields_values, $data_type, $data_value, $data_page, $data_ajax)
        {
            // Get col count box --------------------------------------------------------------
            $col_count = $fields_values['col_count_radio'];
            // Post gap
            $post_gap = 25;

            // Set col classes
            $col_count_class = '';
            // Set count of col
            $col_count_num = '';
            if ($col_count == 'col-2') {
                $col_count_class = 'irantheme-blog-post-masonry-two-col';
                $col_count_num = 2;
            } elseif ($col_count == 'col-3') {
                $col_count_class = 'irantheme-blog-post-masonry-three-col';
                $col_count_num = 3;
            } elseif ($col_count == 'col-4') {
                $col_count_class = 'irantheme-blog-post-masonry-four-col';
                $col_count_num = 4;
            } elseif ($col_count == 'col-6') {
                $col_count_class = 'irantheme-blog-post-masonry-six-col';
                $col_count_num = 6;
            }
            // unique class generator for masonry grid sizer and gutter sizer
            $masonry_style_unique_class = $data_type . '-' . $data_value . '-' . $data_page . '-' . $data_ajax;

            // Calculate the width column
            $result_col_width = (($col_count_num - 1) * $post_gap);

            $extra_class = 'irantheme-blog-post-masonry-item';

            if ($post_elements) :
                ?>
                <div class="irantheme-blog-post-masonry-holder-<?php echo esc_attr($masonry_style_unique_class); ?> irantheme-blog-post-masonry <?php echo esc_attr($col_count_class); ?>" data-grid-gap="<?php echo esc_attr($post_gap); ?>">

                    <style id="irantheme-gallery-masonry-layout-style-<?php echo esc_attr($data_ajax); ?>" type="text/css">
                        .irantheme-blog-post-masonry-holder-<?php echo esc_attr($masonry_style_unique_class . ' '); ?>.irantheme-blog-post-masonry-gutter-sizer {
                            width: <?php echo esc_attr($post_gap . 'px'); ?>;
                        }

                        .irantheme-blog-post-masonry-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-blog-post-masonry-six-col .irantheme-blog-post-masonry-sizer,
                        .irantheme-blog-post-masonry-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-blog-post-masonry-six-col .irantheme-blog-post-masonry-item {
                            width: calc((100% - <?php echo esc_attr($result_col_width . 'px'); ?>) / 6) !important;
                            margin-bottom: <?php echo esc_attr($post_gap . 'px'); ?> !important;
                        }

                        .irantheme-blog-post-masonry-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-blog-post-masonry-four-col .irantheme-blog-post-masonry-sizer,
                        .irantheme-blog-post-masonry-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-blog-post-masonry-four-col .irantheme-blog-post-masonry-item {
                            width: calc((100% - <?php echo esc_attr($result_col_width . 'px'); ?>) / 4) !important;
                            margin-bottom: <?php echo esc_attr($post_gap . 'px'); ?> !important;
                        }

                        .irantheme-blog-post-masonry-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-blog-post-masonry-three-col .irantheme-blog-post-masonry-sizer,
                        .irantheme-blog-post-masonry-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-blog-post-masonry-three-col .irantheme-blog-post-masonry-item {
                            width: calc((100% - <?php echo esc_attr($result_col_width . 'px'); ?>) / 3) !important;
                            margin-bottom: <?php echo esc_attr($post_gap . 'px'); ?> !important;
                        }

                        .irantheme-blog-post-masonry-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-blog-post-masonry-two-col .irantheme-blog-post-masonry-sizer,
                        .irantheme-blog-post-masonry-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-blog-post-masonry-two-col .irantheme-blog-post-masonry-item {
                            width: calc((100% - <?php echo esc_attr($result_col_width . 'px'); ?>) / 2) !important;
                            margin-bottom: <?php echo esc_attr($post_gap . 'px'); ?> !important;
                        }

                        .irantheme-blog-post-masonry .irantheme-blog-post-masonry-sizer {
                            display: none !important;
                        }

                        /* Media (Responsive) */
                        @media (max-width: 1199.98px) {

                            .irantheme-blog-post-masonry-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-blog-post-masonry-six-col .irantheme-blog-post-masonry-sizer,
                            .irantheme-blog-post-masonry-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-blog-post-masonry-six-col .irantheme-blog-post-masonry-item {
                                width: calc((100% - <?php echo esc_attr($result_col_width - ($post_gap * 2) . 'px'); ?>) / 4) !important;
                            }
                        }

                        @media (max-width: 991.98px) {

                            .irantheme-blog-post-masonry-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-blog-post-masonry-four-col .irantheme-blog-post-masonry-sizer,
                            .irantheme-blog-post-masonry-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-blog-post-masonry-four-col .irantheme-blog-post-masonry-item {
                                width: calc((100% - <?php echo esc_attr($result_col_width - $post_gap . 'px'); ?>) / 3) !important;
                            }

                            .irantheme-blog-post-masonry-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-blog-post-masonry-six-col .irantheme-blog-post-masonry-sizer,
                            .irantheme-blog-post-masonry-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-blog-post-masonry-six-col .irantheme-blog-post-masonry-item {
                                width: calc((100% - <?php echo esc_attr($result_col_width - ($post_gap * 3) . 'px'); ?>) / 3) !important;
                            }
                        }

                        @media (max-width: 767.98px) {

                            .irantheme-blog-post-masonry-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-blog-post-masonry-three-col .irantheme-blog-post-masonry-sizer,
                            .irantheme-blog-post-masonry-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-blog-post-masonry-three-col .irantheme-blog-post-masonry-item {
                                width: calc((100% - <?php echo esc_attr($result_col_width - ($post_gap) . 'px'); ?>) / 2) !important;
                            }

                            .irantheme-blog-post-masonry-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-blog-post-masonry-four-col .irantheme-blog-post-masonry-sizer,
                            .irantheme-blog-post-masonry-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-blog-post-masonry-four-col .irantheme-blog-post-masonry-item {
                                width: calc((100% - <?php echo esc_attr($result_col_width - ($post_gap * 2) . 'px'); ?>) / 2) !important;
                            }

                            .irantheme-blog-post-masonry-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-blog-post-masonry-six-col .irantheme-blog-post-masonry-sizer,
                            .irantheme-blog-post-masonry-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-blog-post-masonry-six-col .irantheme-blog-post-masonry-item {
                                width: calc((100% - <?php echo esc_attr($result_col_width - ($post_gap * 4) . 'px'); ?>) / 2) !important;
                            }
                        }

                        @media (max-width: 575.98px) {

                            .irantheme-blog-post-masonry-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-blog-post-masonry-two-col .irantheme-blog-post-masonry-sizer,
                            .irantheme-blog-post-masonry-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-blog-post-masonry-two-col .irantheme-blog-post-masonry-item {
                                width: calc((100% - <?php echo esc_attr($result_col_width - ($post_gap) . 'px'); ?>) / 1) !important;
                            }

                            .irantheme-blog-post-masonry-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-blog-post-masonry-three-col .irantheme-blog-post-masonry-sizer,
                            .irantheme-blog-post-masonry-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-blog-post-masonry-three-col .irantheme-blog-post-masonry-item {
                                width: calc((100% - <?php echo esc_attr($result_col_width - ($post_gap * 2) . 'px'); ?>) / 1) !important;
                            }

                            .irantheme-blog-post-masonry-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-blog-post-masonry-four-col .irantheme-blog-post-masonry-sizer,
                            .irantheme-blog-post-masonry-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-blog-post-masonry-four-col .irantheme-blog-post-masonry-item {
                                width: calc((100% - <?php echo esc_attr($result_col_width - ($post_gap * 3) . 'px'); ?>) / 1) !important;
                            }

                            .irantheme-blog-post-masonry-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-blog-post-masonry-six-col .irantheme-blog-post-masonry-sizer,
                            .irantheme-blog-post-masonry-holder-<?php echo esc_attr($masonry_style_unique_class); ?>.irantheme-blog-post-masonry-six-col .irantheme-blog-post-masonry-item {
                                width: calc((100% - <?php echo esc_attr($result_col_width - ($post_gap * 5) . 'px'); ?>) / 1) !important;
                            }
                        }
                    </style>

                    <div class="irantheme-blog-post-masonry-sizer"></div>
                    <div class="irantheme-blog-post-masonry-gutter-sizer"></div>
                    <?php
                    if ($post_type == 'post-box') {
                        self::post_box_item_generator($post_elements, $fields_values, $col_count_num, '', 'masonry', '', $extra_class);
                    } elseif ($post_type == 'post-simple') {
                        self::post_simple_item_generator($post_elements, $fields_values, $col_count_num, '', 'masonry', '', $extra_class);
                    }
                    ?>
                </div>
            <?php
            endif;
        }
        /**
         * Add post box with flex style
         * @return void
         * @param void
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        public static function blog_post_flex($post_type, $post_elements, $fields_values)
        {
            $col_count = $fields_values['col_count_radio'];
            // Post gap
            $post_gap = 25;

            // Set column class
            $col_count_class = '';
            $col_count_num = 0;
            if ($col_count == 'col-2') {
                $col_count_class = 'col-lg-6 col-6';
                $col_count_num = 2;
            } else if ($col_count == 'col-3') {
                $col_count_class = 'col-lg-4 col-sm-6 col-6';
                $col_count_num = 3;
            } else if ($col_count == 'col-4') {
                $col_count_class = 'col-lg-3 col-md-4 col-sm-6 col-6';
                $col_count_num = 4;
            } else if ($col_count == 'col-6') {
                $col_count_class = 'col-xl-2 col-lg-3 col-md-4 col-sm-6 col-6';
                $col_count_num = 6;
            }
            ?>
            <?php
            if ($post_elements) :
            ?>
                <div class="irantheme-blog-post-post-item-holder irantheme-blog-post-flex">
                    <div class="row">
                        <?php
                        if ($post_type == 'post-box') {
                            self::post_box_item_generator($post_elements, $fields_values, $col_count_num, $col_count_class, 'flex', $post_gap);
                        } elseif ($post_type == 'post-simple') {
                            self::post_simple_item_generator($post_elements, $fields_values, $col_count_num, $col_count_class, 'flex', $post_gap);
                        }
                        ?>
                    </div>
                </div>
            <?php
            endif;
        }

        /**
         * Add post box with carousel style
         * @return void
         * @param void
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        public static function blog_post_carousel($post_type, $post_elements, $fields_values, $data_type, $data_value, $data_page, $data_ajax)
        {
            // Get col count box --------------------------------------------------------------
            $col_count = $fields_values['col_count_radio'];

            // Post gap
            $post_gap = 25;
            $arrow_slider_visibility = $fields_values['slider_arrow_checkbox'] == 'on' ? true : false;
            $pagination_slider_visibility = $fields_values['slider_pagination_checkbox'] == 'on' ? true : false;

            // Set col classes
            $col_count_class = '';
            // Set count of col
            $col_count_num = '';
            if ($col_count == 'col-2') {
                $col_count_class = 'irantheme-grid-two-col';
                $col_count_num = 2;
            } elseif ($col_count == 'col-3') {
                $col_count_class = 'irantheme-grid-three-col';
                $col_count_num = 3;
            } elseif ($col_count == 'col-4') {
                $col_count_class = 'irantheme-grid-four-col';
                $col_count_num = 4;
            } elseif ($col_count == 'col-6') {
                $col_count_class = 'irantheme-grid-six-col';
                $col_count_num = 6;
            }

            // unique class generator for masonry grid sizer and gutter sizer
            $carousel_unique_id = 'irantheme_blog_post_carousel_' . $data_type . '_' . $data_value . '_' . $data_page . '_' . $data_ajax;

            // unique class generator
            $carousel_style_unique_class = $data_type . '-' . $data_value . '-' . $data_page . '-' . $data_ajax;

            $extra_class = 'swiper-slide';

            if ($post_elements) :
            ?>
                <div id="<?php echo esc_attr($carousel_unique_id) ?>" class="irantheme-blog-post-holder-<?php echo esc_attr($carousel_style_unique_class) ?> irantheme-blog-post-carousel swiper irantheme-blog-post-carousel-swiper <?php echo esc_attr($col_count_class); ?>" data-cell-gap="<?php echo esc_attr($post_gap); ?>" data-columns="<?php echo esc_attr($col_count_num) ?>">
                    <div class="swiper-wrapper">
                        <?php
                        if ($post_type == 'post-box') {
                            self::post_box_item_generator($post_elements, $fields_values, $col_count_num, '', 'carousel', '', $extra_class);
                        } elseif ($post_type == 'post-simple') {
                            self::post_simple_item_generator($post_elements, $fields_values, $col_count_num, '', 'carousel', '', $extra_class);
                        }
                        ?>
                    </div>
                    <?php
                    // Get border radius --------------------------------------------------------------
                    $border_radius_range = $fields_values['border_radius_range'];
                    Irantheme_Section_Utils::get_slider_arrow_pagination('on', $arrow_slider_visibility, $pagination_slider_visibility, 'white', $border_radius_range, 'bottom', 'center');
                    ?>
                </div>
                <?php
            endif;
        }


        /**
         * Add post box items
         * @return void
         * @param void
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        public static function post_box_item_generator($post_elements, $fields_values, $col_count_num, $col_class, $layout_type, $post_gap = '', $extra_class = '')
        {
            // Check posts content
            if (!$post_elements) {
                return;
            }

            // Get image thumb visibility checkbox --------------------------------------------------------------
            $image_thumb_checkbox = $fields_values['image_visibility_checkbox'];
            // Get image thumb height --------------------------------------------------------------
            $image_thumb_height = $fields_values['image_height_range'];
            // Get hover mode --------------------------------------------------------------
            $hover_mode = $fields_values['hover_mode_radio'];
            // Get post info type --------------------------------------------------------------
            $post_info_type = $fields_values['info_type_checkbox_multi'];
            $post_info_type = explode(',', $post_info_type);
            // Get post info icon display mode --------------------------------------------------------------
            $post_info_icon = $fields_values['info_icons_checkbox'];
            // Get post content align --------------------------------------------------------------
            $post_content_align = $fields_values['align_content_radio'];
            // Get border mode --------------------------------------------------------------
            $border_mode = $fields_values['border_checkbox'];
            // Get border mode --------------------------------------------------------------
            $shadow_mode = $fields_values['shadow_checkbox'];
            // Get data load style --------------------------------------------------------------
            $data_load_attr = Irantheme_Section_Utils::get_data_load_attr_type($fields_values);
            // Get data load duration
            $data_load_duration = Irantheme_Section_Utils::get_data_load_duration($fields_values);
            // Get aos delay time
            $aos_delay = Irantheme_Section_Utils::get_data_load_delay($fields_values);
            // Get border radius --------------------------------------------------------------
            $border_radius_range = $fields_values['border_radius_range'];
            $border_radius_style = Irantheme_Section_Utils::get_border_radius_css($border_radius_range);
            // Get slider post excerpt number
            $post_excerpt_number = $fields_values['post_excerpt_number'];

            // Get alignment class
            $content_alignment_class = Irantheme_Section_Service::get_alignment_class($post_content_align);
            // Set alignment style
            $content_alignment_style = '';
            if ($post_content_align == 'center') {
                $content_alignment_style = 'center';
            } elseif ($post_content_align == 'right') {
                $content_alignment_style = 'flex-start';
            } elseif ($post_content_align == 'left') {
                $content_alignment_style = 'flex-end';
            }

            // Set border class
            $border_class = '';
            if ($border_mode == 'on') {
                $border_class = 'irantheme-post-box-item-border-little';
            }

            // Set shadow class
            $shadow_class = '';
            if ($shadow_mode == 'on') {
                $shadow_class = 'irantheme-post-box-item-shadow-little';
            }

            // Set hover mode class
            $hover_mode_class = 'irantheme-post-box-item-normal-hover';
            if ($hover_mode == 'transform') {
                $hover_mode_class = 'irantheme-post-box-item-transform-hover';
            } elseif ($hover_mode == 'mask') {
                $hover_mode_class = 'irantheme-post-box-item-mask-hover';
            } elseif ($hover_mode == 'zoom-image') {
                $hover_mode_class = 'irantheme-post-box-item-zoom-image-hover';
            } elseif ($hover_mode == 'shadow') {
                $hover_mode_class = 'irantheme-post-box-item-shadow-hover';
            } elseif ($hover_mode == 'zoom-post') {
                $hover_mode_class = 'irantheme-post-box-item-zoom-post-hover';
            }

            // Set aos delay for gallery item
            $start_delay = $aos_delay;
            $increase_value = 200;
            $max_delay = $start_delay + ($increase_value * ($col_count_num - 1));
            // While on wp_query posts
            if ($post_elements->have_posts()) :
                while ($post_elements->have_posts()) :
                    $post_elements->the_post();
                    // Get thumb url of post
                    $category_extra_class = '';
                    // Check has image thumb and image thumb visibility mode for category change style
                    if (!has_post_thumbnail() || $image_thumb_checkbox != 'on') {
                        $category_extra_class = 'irantheme-post-box-without-image';
                    }

                    // Get parent id category of post
                    $category = get_the_category();
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
                ?>
                    <article class="irantheme-post-box-item <?php echo esc_attr($extra_class); ?> <?php echo esc_attr($col_class); ?> post-holder" style="margin-bottom: <?php echo esc_attr($post_gap); ?>px;" data-cate="<?php echo esc_attr($category_parents_id); ?>">
                        <div class="irantheme-post-box-animation" <?php echo ($data_load_attr); ?> data-aos-delay="<?php echo ($aos_delay); ?>" data-aos-duration="<?php echo ($data_load_duration); ?>">
                            <div class="irantheme-post-box-item-inner-wrapper <?php echo esc_attr($border_class); ?> <?php echo esc_attr($shadow_class); ?> <?php echo esc_attr($category_extra_class); ?> <?php echo esc_attr($hover_mode_class); ?>" style="<?php echo esc_attr($border_radius_style); ?>">
                                <?php
                                // Check image thumb visibility mode
                                if ($image_thumb_checkbox == 'on') :
                                    // Check selected thumb for post
                                    if (has_post_thumbnail()) :
                                ?>
                                        <div class="irantheme-post-box-item-img">
                                            <a href="<?php echo get_permalink(); ?>">
                                                <div class="irantheme-post-box-item-img-wrapper" style="height: <?php echo esc_attr($layout_type == 'flex' || $layout_type == 'carousel' ? $image_thumb_height : ''); ?>px;">
                                                    <?php
                                                    echo wp_get_attachment_image(
                                                        get_post_thumbnail_id(),
                                                        'medium_large',
                                                        false,
                                                        [
                                                            'alt' => get_the_title() ?: __('تصویر شاخص', IRANTHEME_TEXTDOMAIN),
                                                            'loading' => 'lazy',
                                                        ]
                                                    );
                                                    ?>
                                                </div>
                                            </a>
                                        </div>
                                <?php endif;
                                endif; ?>
                                <div class="irantheme-post-box-item-category">
                                    <?php
                                    // check on selected post info type for generate category
                                    foreach ($post_info_type as $type) :
                                        if ($type == 'category') :
                                    ?>
                                            <ul>
                                                <?php
                                                foreach (get_the_category() as $category) {
                                                ?>
                                                    <li>
                                                        <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" style="<?php echo esc_attr($border_radius_style); ?>"><?php echo esc_html($category->name); ?></a>
                                                    </li>
                                                <?php
                                                }
                                                ?>
                                            </ul>
                                    <?php
                                        endif;
                                    endforeach;
                                    ?>
                                </div>
                                <div class="irantheme-post-box-item-content <?php echo esc_attr($content_alignment_class); ?>">
                                    <div class="irantheme-post-box-item-content-details" style="justify-content: <?php echo esc_attr($content_alignment_style); ?>;">
                                        <?php
                                        foreach ($post_info_type as $type) :
                                            if ($type == 'date') :
                                        ?>
                                                <div class="irantheme-post-box-item-content-details-date">
                                                    <?php
                                                    echo $post_info_icon == 'on' ? '<i class="fa-light fa-calendar-days"></i>' : '';
                                                    echo get_the_date('j F Y'); ?></div>
                                            <?php
                                            elseif ($type == 'author') :
                                            ?>
                                                <div class="irantheme-post-box-item-content-details-author">
                                                    <?php
                                                    echo $post_info_icon == 'on' ? '<i class="fa-light fa-user"></i>' : '';
                                                    the_author(); ?></div>
                                            <?php
                                            elseif ($type == 'comment') :
                                            ?>
                                                <div class="irantheme-post-box-item-content-details-comment">
                                                    <?php
                                                    echo $post_info_icon == 'on' ? '<i class="fa-light fa-comments"></i>' : '';
                                                    echo get_comments_number(); ?>
                                                    <span>دیدگاه</span>
                                                </div>
                                        <?php
                                            endif;
                                        endforeach;
                                        ?>
                                    </div>
                                    <h3 class="irantheme-post-box-item-content-title"><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h3>
                                    <?php
                                    foreach ($post_info_type as $type) :
                                        if ($type == 'excerpt') :
                                    ?>
                                            <p class="irantheme-post-box-item-content-description"><?php echo wp_trim_words(strip_shortcodes(get_the_excerpt()), $post_excerpt_number); ?></p>
                                        <?php
                                        elseif ($type == 'read-more') :
                                        ?>
                                            <a href="<?php echo get_permalink(); ?>" class="irantheme-post-box-item-content-read-more">ادامه مطلب...</a>
                                    <?php
                                        endif;
                                    endforeach;
                                    ?>
                                </div>
                            </div>
                        </div>
                    </article>
                    <?php
                    // Set aos delay
                    if ($layout_type == 'masonry' || $layout_type == 'carousel') {
                        $aos_delay += $increase_value;
                    } else {
                        if ($aos_delay < $max_delay) {
                            $aos_delay += $increase_value;
                        } else {
                            $aos_delay = $start_delay;
                        }
                    }
                endwhile;
            else:
                // Empty status
                irantheme_display_empty_status('file-lines', 'نوشته ای جهت نمایش یافت نشد.', 'با رفتن به محیط ادمین و تب "نوشته ها" -> "افزودن نوشته" نوشته مورد نظر خود را بسازید.');
            endif;
        }

        /**
         * Add post simple items
         * @return void
         * @param void
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        public static function post_simple_item_generator($post_elements, $fields_values, $col_count_num, $col_class, $layout_type, $post_gap = '', $extra_class = '')
        {
            // Check posts content
            if (!$post_elements) {
                return;
            }

            // Get detail color --------------------------------------------------------------
            $detail_color_data = $fields_values['post_details_color_select'];
            $detail_bg_color_css = Irantheme_Section_Utils::get_background_color_css($detail_color_data);

            // Get image thumb visibility checkbox --------------------------------------------------------------
            $image_thumb_checkbox = $fields_values['image_visibility_checkbox'];
            // Get image thumb height --------------------------------------------------------------
            $image_thumb_height = $fields_values['image_height_range'];
            // Get hover mode --------------------------------------------------------------
            $hover_mode = $fields_values['hover_mode_radio'];
            // Get post info type --------------------------------------------------------------
            $post_info_type = $fields_values['info_type_checkbox_multi'];
            $post_info_type = explode(',', $post_info_type);
            // Get post info icon display mode --------------------------------------------------------------
            $date_position = $fields_values['date_position_radio'];
            // Get post content align --------------------------------------------------------------
            $post_content_align = $fields_values['align_content_radio'];
            // Get border mode --------------------------------------------------------------
            $border_mode = $fields_values['border_checkbox'];
            // Get border mode --------------------------------------------------------------
            $shadow_mode = $fields_values['shadow_checkbox'];
            // Get data load style --------------------------------------------------------------
            $data_load_attr = Irantheme_Section_Utils::get_data_load_attr_type($fields_values);
            // Get data load duration
            $data_load_duration = Irantheme_Section_Utils::get_data_load_duration($fields_values);
            // Get aos delay time
            $aos_delay = Irantheme_Section_Utils::get_data_load_delay($fields_values);
            // Get border radius --------------------------------------------------------------
            $border_radius_range = $fields_values['border_radius_range'];
            $border_radius_style = Irantheme_Section_Utils::get_border_radius_css($border_radius_range);
            // Get slider post excerpt number
            $post_excerpt_number = $fields_values['post_excerpt_number'];

            // Get alignment class
            $content_alignment_class = Irantheme_Section_Service::get_alignment_class($post_content_align);
            // Set alignment style
            $content_alignment_style = '';
            if ($post_content_align == 'center') {
                $content_alignment_style = 'center';
            } elseif ($post_content_align == 'right') {
                $content_alignment_style = 'flex-start';
            } elseif ($post_content_align == 'left') {
                $content_alignment_style = 'flex-end';
            }

            // Set card background class
            $card_bg_color = $fields_values['post_bg_color_select'];
            $card_bg_color_class = '';
            if ($card_bg_color == 'default') {
                $card_bg_color_class = 'bg-color-default';
            } elseif ($card_bg_color == 'transparent') {
                $card_bg_color_class = 'bg-color-transparent';
            }

            // Set post date forColor
            $post_date_text_color = $fields_values['date_text_color_select'];

            // Set border class
            $border_class = '';
            if ($border_mode == 'on') {
                $border_class = 'irantheme-post-simple-item-border-little';
            }

            // Set shadow class
            $shadow_class = '';
            if ($shadow_mode == 'on') {
                $shadow_class = 'irantheme-post-simple-item-shadow-little';
            }

            // Set hover mode class
            $hover_mode_class = 'irantheme-post-simple-item-normal-hover';
            if ($hover_mode == 'transform') {
                $hover_mode_class = 'irantheme-post-simple-item-transform-hover';
            } elseif ($hover_mode == 'mask') {
                $hover_mode_class = 'irantheme-post-simple-item-mask-hover';
            } elseif ($hover_mode == 'zoom-image') {
                $hover_mode_class = 'irantheme-post-simple-item-zoom-image-hover';
            } elseif ($hover_mode == 'shadow') {
                $hover_mode_class = 'irantheme-post-simple-item-shadow-hover';
            } elseif ($hover_mode == 'zoom-post') {
                $hover_mode_class = 'irantheme-post-simple-item-zoom-post-hover';
            }

            // Get post wrapper mode
            $post_wrapper_mode = $fields_values['post_content_wrapper_mode_checkbox'];
            $post_wrapper_class = '';
            if ($post_wrapper_mode == 'on') {
                $post_wrapper_class = 'post-card-has-padding';
            }
            // Set aos delay for gallery item
            $start_delay = $aos_delay;
            $increase_value = 200;
            $max_delay = $start_delay + ($increase_value * ($col_count_num - 1));
            // While on wp_query posts
            if ($post_elements->have_posts()) :
                while ($post_elements->have_posts()) :
                    $post_elements->the_post();
                    // Get thumb url of post
                    $category_extra_class = '';
                    // Check has image thumb and image thumb visibility mode for category change style
                    if (!has_post_thumbnail() || $image_thumb_checkbox != 'on') {
                        $category_extra_class = 'irantheme-post-simple-without-image';
                    }

                    // Get parent id category of post
                    $category = get_the_category();
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
                    ?>
                    <article class="irantheme-post-simple-item <?php echo esc_attr($extra_class); ?> <?php echo esc_attr($col_class); ?> post-holder" style="margin-bottom: <?php echo esc_attr($post_gap); ?>px;" data-cate="<?php echo esc_attr($category_parents_id); ?>">
                        <div class="irantheme-post-simple-animation" <?php echo ($data_load_attr); ?> data-aos-delay="<?php echo ($aos_delay); ?>" data-aos-duration="<?php echo ($data_load_duration); ?>">
                            <div class="irantheme-post-simple-item-inner-wrapper <?php echo esc_attr($border_class); ?> <?php echo esc_attr($shadow_class); ?> <?php echo esc_attr($category_extra_class); ?> <?php echo esc_attr($hover_mode_class); ?> <?php echo esc_attr($card_bg_color_class); ?>" style="<?php echo $post_wrapper_mode == 'on' ? esc_attr($border_radius_style) : ''; ?>">
                                <?php
                                // Check image thumb visibility mode
                                if ($image_thumb_checkbox == 'on') :
                                    // Check selected thumb for post
                                    if (has_post_thumbnail()) :
                                ?>
                                        <div class="irantheme-post-simple-item-img">
                                            <a href="<?php echo get_permalink(); ?>">
                                                <div class="irantheme-post-simple-item-img-wrapper" style="height: <?php echo esc_attr($layout_type == 'flex' || $layout_type == 'carousel' ? $image_thumb_height : ''); ?>px;<?php echo $post_wrapper_mode == 'off' ? esc_attr($border_radius_style) : ''; ?>">
                                                    <?php
                                                    echo wp_get_attachment_image(
                                                        get_post_thumbnail_id(),
                                                        'medium_large',
                                                        false,
                                                        [
                                                            'alt' => get_the_title() ?: __('تصویر پست', IRANTHEME_TEXTDOMAIN),
                                                            'loading' => 'lazy',
                                                        ]
                                                    );
                                                    ?>
                                                    <?php if (in_array('date', $post_info_type)): ?>
                                                        <div class="irantheme-post-simple-item-date <?php echo esc_attr($post_date_text_color); ?> position-<?php echo esc_attr($date_position); ?>" style="<?php echo esc_attr($detail_bg_color_css); ?><?php echo esc_attr(Irantheme_Section_Utils::get_border_radius_css($border_radius_range)); ?>">
                                                            <span class="irantheme-post-simple-item-date-day">
                                                                <?php echo get_the_date('j'); ?>
                                                            </span>
                                                            <span class="irantheme-post-simple-item-date-month">
                                                                <?php echo get_the_date('F'); ?>
                                                            </span>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </a>
                                        </div>
                                <?php endif;
                                endif; ?>
                                <div class="irantheme-post-simple-item-content <?php echo esc_attr($content_alignment_class); ?> <?php echo esc_attr($post_wrapper_class); ?>" style="<?php echo $post_wrapper_mode == 'on' ? 'padding:' . esc_attr($fields_values['post_content_wrapper_size_range']) . 'rem;' : ''; ?>">
                                    <?php if (in_array('date', $post_info_type) && $image_thumb_checkbox == 'off'): ?>
                                        <div class="irantheme-post-simple-item-date whiteout-thumbnail <?php echo esc_attr($post_date_text_color); ?> position-<?php echo esc_attr($date_position); ?>" style="<?php echo esc_attr($detail_bg_color_css); ?><?php echo esc_attr(Irantheme_Section_Utils::get_border_radius_css($border_radius_range)); ?>">
                                            <span class="irantheme-post-simple-item-date-day">
                                                <?php echo get_the_date('j'); ?>
                                            </span>
                                            <span class="irantheme-post-simple-item-date-month">
                                                <?php echo get_the_date('F'); ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                    <div class="irantheme-post-simple-item-content-details" style="justify-content: <?php echo esc_attr($content_alignment_style); ?>;">
                                        <?php if (in_array('category', $post_info_type)) : ?>
                                            <ul class="irantheme-post-simple-item-content-details-category">
                                                <?php
                                                foreach (get_the_category() as $category) {
                                                ?>
                                                    <li class="irantheme-post-simple-item-content-details-category-item">
                                                        <?php
                                                        $categories_output = '<a href="' . get_category_link($category->term_id) . '">' . esc_html__($category->name) . '</a>';
                                                        echo trim($categories_output, '');
                                                        ?>
                                                    </li>
                                                <?php
                                                }
                                                ?>
                                            </ul>
                                        <?php endif; ?>
                                    </div>
                                    <h3 class="irantheme-post-simple-item-content-title"><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h3>
                                    <?php if (in_array('excerpt', $post_info_type)) : ?>
                                        <p class="irantheme-post-simple-item-content-description"><?php echo wp_trim_words(strip_shortcodes(get_the_excerpt()), $post_excerpt_number); ?></p>
                                    <?php endif; ?>
                                    <?php if (in_array('read-more', $post_info_type)) : ?>
                                        <a href="<?php echo get_permalink(); ?>" class="irantheme-post-simple-item-content-read-more" style="color:#<?php echo esc_attr(Irantheme_Color_Static::get_hex_color($detail_color_data)); ?>">مشاهده بیشتر...</a>
                                    <?php endif; ?>
                                    <?php if (in_array('comment', $post_info_type) || in_array('author', $post_info_type)): ?>
                                        <div class="irantheme-post-simple-item-content-more-details">
                                            <?php if (in_array('author', $post_info_type)): ?>
                                                <!-- Post info author -->
                                                <div class="irantheme-post-simple-item-content-more-details-author">
                                                    <div class="irantheme-post-simple-item-content-more-details-author-img">
                                                        <?php echo get_avatar(get_the_author_meta('ID'), 100); ?>
                                                    </div>
                                                    <div class="irantheme-post-simple-item-content-more-details-author-info">
                                                        <h4><?php echo get_the_author_posts_link(); ?></h4>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            <?php if (in_array('comment', $post_info_type)): ?>
                                                <div class="irantheme-post-simple-item-content-more-details-comment">
                                                    <?php
                                                    echo get_comments_number(); ?>
                                                    <span>دیدگاه</span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </article>
            <?php
                    // Set aos delay
                    if ($layout_type == 'masonry' || $layout_type == 'carousel') {
                        $aos_delay += $increase_value;
                    } else {
                        if ($aos_delay < $max_delay) {
                            $aos_delay += $increase_value;
                        } else {
                            $aos_delay = $start_delay;
                        }
                    }
                endwhile;
            else:
                // Empty status
                irantheme_display_empty_status('file-lines', 'نوشته ای جهت نمایش یافت نشد.', 'با رفتن به محیط ادمین و تب "نوشته ها" -> "افزودن نوشته" نوشته مورد نظر خود را بسازید.');
            endif;
        }

        /**
         * Return post_query by array of ids
         * @return array
         * @param string,array
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        public static function get_post_by_ids($post_type, $array_post_ids = array(), $posts_per_page = -1, $cate_id = null)
        {
            $args = array(
                'post_type' => $post_type,
                'post_status' => 'publish',
                'orderby' => 'post__in',
                'post__in' => $array_post_ids,
                'posts_per_page' => $posts_per_page,
                'cat' => $cate_id
            );
            // Query post
            $post_query = new WP_Query($args);
            return $post_query;
        }


        /**
         * Return latest post
         * @return object
         * @param string,int
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_latest_posts($post_type, $posts_per_page = -1, $extra_args = null)
        {
            $args = array(
                'post_type'      => $post_type,
                'post_status'    => 'publish',
                'posts_per_page' => $posts_per_page,
                'no_found_rows'  => true,
            );

            if ($extra_args !== null) {
                $args = array_merge($args, $extra_args);
            }

            return new WP_Query($args);
        }


        /**
         * Return discussed post
         * @return object
         * @param string,int,array
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_discussed_posts($post_type, $posts_per_page = -1, $extra_args = null)
        {
            $args = array(
                'post_type' => $post_type,
                'post_status' => 'publish',
                'posts_per_page' => $posts_per_page,
                'orderby'        => 'comment_count',
                'order'          => 'DESC',
                'date_query'     => array(
                    array(
                        'after' => '1 month ago'
                    )
                ),
            );
            if ($extra_args != null) {
                $args = array_merge($args, $extra_args);
            }
            // Query post
            $post_query = new WP_Query($args);
            return $post_query;
        }


        /**
         * Return liked post
         * @return object
         * @param string,int,array
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_liked_posts($post_type, $posts_per_page = -1, $extra_args = null)
        {
            // First get all liked post IDs with their counts
            $like_counts = array();
            $likes_query = new WP_Query(array(
                'post_type'      => 'like',
                'posts_per_page' => -1,
                'meta_key'       => 'irantheme_liked_meta_value_key',
                'fields'         => 'ids',
            ));

            // Count likes for each post
            foreach ($likes_query->posts as $like_post_id) {
                $liked_post_id = get_post_meta($like_post_id, 'irantheme_liked_meta_value_key', true);
                if (is_numeric($liked_post_id)) {
                    if (!isset($like_counts[$liked_post_id])) {
                        $like_counts[$liked_post_id] = 0;
                    }
                    $like_counts[$liked_post_id]++;
                }
            }

            // Get ALL posts (both liked and not liked)
            $all_posts = new WP_Query(array(
                'post_type'      => 'post',
                'posts_per_page' => -1,
                'fields'         => 'ids',
            ));

            // Create an array with all post IDs and their like counts
            $posts_with_likes = array();
            foreach ($all_posts->posts as $post_id) {
                $posts_with_likes[$post_id] = isset($like_counts[$post_id]) ? $like_counts[$post_id] : 0;
            }

            // Sort by like count (highest first), then by post date
            arsort($posts_with_likes);

            $args = array(
                'post_type' => $post_type,
                'post_status' => 'publish',
                'post__in'       => array_keys($posts_with_likes),
                'orderby'        => 'post__in', // Maintains our custom order
                'posts_per_page' => $posts_per_page,
            );
            if ($extra_args != null) {
                $args = array_merge($args, $extra_args);
            }
            // Query post
            $post_query = new WP_Query($args);
            return $post_query;
        }


        /**
         * Add tinymce content for heading
         * @return void
         * @param void
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        public static function tinymce_heading($fields_values, $extra_css = '', $show_btn = true, $show_heading = true, $extra_class = '')
        {
            $tinymce_text        = $fields_values['text_editor_heading'];
            $heading_alignment   = $fields_values['alignment_heading_radio'];
            $base_color          = $fields_values['base_color_select'];
            $srt_result = '';
            $heading_content_padding_css = '';

            if ($show_btn) :
                $button_primary_checkbox   = $fields_values['btn_primary_checkbox'];
                $button_secondary_checkbox = $fields_values['btn_secondary_checkbox'];
                $button_style              = $fields_values['btn_style_radio'];
                $button_size               = $fields_values['btn_size_radio'];

                $button_primary_text       = $fields_values['btn_primary_title'];
                $button_primary_link       = $fields_values['btn_primary_link'];
                $button_primary_icon       = $fields_values['btn_primary_icon'];

                $button_bg_primary         = $fields_values['btn_primary_color_select'];
                $button_bg_secondary       = $fields_values['btn_secondary_color_select'];
                $button_secondary_text     = $fields_values['btn_secondary_title'];
                $button_secondary_link     = $fields_values['btn_secondary_link'];
                $button_secondary_icon     = $fields_values['btn_secondary_icon'];
            endif;

            if ($show_heading) :
                $text_slider_checkbox      = $fields_values['slider_text_checkbox'];
                $content_text_slider       = $fields_values['slider_multi_text'];
                $slider_text_write_speed   = $fields_values['slider_speed_write'];
                $slider_text_remove_speed  = $fields_values['slider_speed_remove'];
                $slider_text_delay         = $fields_values['slider_delay'];
                $padding_top               = $fields_values['heading_content_padding_top_range'];
                $padding_bottom            = $fields_values['heading_content_padding_bottom_range'];
                $heading_style             = $fields_values['heading_style_radio'];
                $heading_icon              = $fields_values['heading_style_icon'];
                $icon_size                 = $fields_values['heading_style_icon_size'];
                $heading_style_color       = $fields_values['heading_style_color_select'];

                // Set base color class
                $base_color_class = self::get_base_color_class($base_color);
                // Get alignment class
                $content_alignment_class = Irantheme_Section_Service::get_alignment_class($heading_alignment);
                $heading_hex_color = Irantheme_Color_Static::get_hex_color($heading_style_color);
                $heading_style_color_class = Irantheme_Color_Static::get_class_color_mode('bg', null, $heading_hex_color);
                // Generate padding css code
                $heading_content_padding_css = 'padding-top:' . $padding_top . 'rem;padding-bottom:' . $padding_bottom . 'rem;';
                // Split slider text content by '###'
                $section_slider_text_array = explode('###', $content_text_slider);
                // Create a variable for hold the search typed string result
                $srt_target = array();

                // Warning: This code is necessary on add section demo and init string animation typed!
                // Check two ### char in tinymce text
                if (substr_count($tinymce_text, '###') == 2) {
                    // Get target string between two string
                    $full_string = $tinymce_text;
                    $ini = strpos($full_string, '###');
                    if ($ini == 0) {
                        $result = '';
                    } else {
                        $ini += strlen('###');
                        $len = strpos($full_string, '###', $ini) - $ini;
                        $result = substr($full_string, $ini, $len);
                    }
                    $srt_target[0] = array($result);
                    $tinymce_text = str_replace('###', '', $tinymce_text);
                } else {
                    // Search by regex in text editor content
                    preg_match_all('/(<.+>)*#{3}.+?#{3}(<.+?>)*/imu', $tinymce_text, $srt_target);
                }

                // Set a text editor content to final result variable
                $srt_result = $tinymce_text;
                // Class container
                $classes = '';
                // Set index of typed strings in showcases
                $typed_index = 0;
                // Create a variable for hold the id new typed span tag
                if (count($srt_target[0]) > 0 && $text_slider_checkbox == 'on') {
                    foreach ($srt_target[0] as $value) {
                        // Remove '###' from search result
                        $target_sign_removed = str_replace('###', '', $value);
                        // Set id for new typed span tag
                        // Replace the tag with the desired phrase
                        $srt_result = str_replace($value, '<span class="irantheme-typed-strings" data-slider-text="' . $section_slider_text_array[$typed_index] . '" data-write-speed="' . $slider_text_write_speed . '" data-remove-speed="' . $slider_text_remove_speed . '" data-slider-delay="' . $slider_text_delay . '">' . $target_sign_removed . '</span>', $srt_result);
                        // Increase typed index
                        $typed_index++;
                    }
                    // Set specific class to parent of typed tag
                    $classes = 'irantheme-content-typed';
                }
            endif;

            // Calculation of padding
            $heading_padding_css = '';
            if ($srt_result || $button_primary_checkbox == 'on' || $button_secondary_checkbox == 'on') {
                $heading_padding_css = $heading_content_padding_css;
            }

            ?>
            <!-- Main tinymce holder -->
            <div class="irantheme-tinymce-holder <?php echo esc_attr($extra_class); ?>" style="<?php echo esc_attr($heading_padding_css); ?>">
                <?php
                if ($srt_result && $show_heading) :
                    // Get tinymce simple text
                    self::get_snippet_tinymce_simple($srt_result, $base_color_class, '', $content_alignment_class, $classes, $extra_css, $heading_style, $heading_style_color_class, $heading_hex_color, $heading_icon, $icon_size);
                endif;

                // Check buttons
                if ($show_btn) :
                    // Get content buttons --------------------------------------------------------------
                    self::section_buttons($heading_alignment, $base_color, $button_primary_checkbox, $button_secondary_checkbox, $button_style, $button_size, $button_primary_text, $button_primary_link, $button_primary_icon, $button_bg_primary, $button_bg_secondary, $button_secondary_text, $button_secondary_link, $button_secondary_icon);
                endif;
                ?>
            </div>
        <?php
        }


        /**
         * Display a tinymce simple text
         * @return void
         * @param string,string,string,string,string,string,string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_snippet_tinymce_simple($text_content = '', $base_color_class = '', $attr_extra = '', $alignment_class = '', $extra_classes = '', $extra_css = '', $heading_style = '', $heading_style_color_class = '', $heading_hex_color = '', $heading_icon = '', $icon_size = 2)
        {
        ?>
            <?php if ($heading_style && $heading_style == 'dotted' || $heading_style == 'parallel' || $heading_style == 'icon'): ?>
                <div style="display: flex;column-gap: 1rem;">
                    <?php
                    if ($heading_style == 'dotted') {
                    ?>
                        <div class="irantheme-tinymce-style-dotted">
                            <?php for ($i = 0; $i < 18; $i++): ?>
                                <span class="irantheme-tinymce-style-dotted-dot" style="color: #<?php echo esc_attr($heading_hex_color); ?>;">.</span>
                            <?php endfor; ?>
                        </div>
                    <?php
                    } else if ($heading_style == 'parallel') {
                        // Add white transparency
                        $rgb_color = Irantheme_Color_Static::hex_to_rgb($heading_hex_color);
                        $line_rgb_css = 'background: rgba(' . $rgb_color[0] . ', ' . $rgb_color[1] . ', ' . $rgb_color[2] . ', 0.5) !important;';
                    ?>
                        <div class="irantheme-tinymce-style-parallel">
                            <span class="irantheme-tinymce-style-parallel-line" style="<?php echo esc_attr($line_rgb_css); ?>"></span>
                            <span class="irantheme-tinymce-style-parallel-line" style="background: #<?php echo esc_attr($heading_hex_color); ?>;"></span>
                        </div>
                    <?php
                    } else if ($heading_style == 'icon') {
                        // Add white transparency
                        $rgb_color = Irantheme_Color_Static::hex_to_rgb($heading_hex_color);
                        $line_rgb_css = 'background: rgba(' . $rgb_color[0] . ', ' . $rgb_color[1] . ', ' . $rgb_color[2] . ', 0.1) !important;';
                        $icon_size_css = 'font-size: ' . esc_attr($icon_size) . 'rem;width: ' . esc_attr(($icon_size + 1)) . 'rem;height: ' . esc_attr(($icon_size + 1)) . 'rem;';
                    ?>
                        <div class="irantheme-tinymce-style-icon">
                            <i class="<?php echo esc_attr($heading_icon); ?>" style="<?php echo esc_attr($line_rgb_css); ?>color: #<?php echo esc_attr($heading_hex_color); ?>;<?php echo esc_attr($icon_size_css); ?>"></i>
                        </div>
                    <?php
                    }
                    ?>
                <?php endif; ?>

                <!-- Main content -->
                <div class="irantheme-tinymce-content <?php echo esc_attr($base_color_class); ?> <?php echo esc_attr($alignment_class); ?> <?php echo $extra_classes; ?>" <?php echo $attr_extra; ?> style="<?php echo esc_attr($extra_css); ?>">
                    <?php
                    echo $text_content;
                    ?>
                </div>
                <?php if ($heading_style && $heading_style == 'dotted' || $heading_style == 'parallel' || $heading_style == 'icon'): ?>
                </div>
            <?php endif; ?>

            <?php if ($heading_style != 'normal' && $heading_style && $heading_style != 'dotted' && $heading_style != 'parallel' && $heading_style != 'icon'): ?>
                <div class="irantheme-tinymce-style <?php echo esc_attr($alignment_class); ?>">
                    <?php
                    if ($heading_style == 'underline') {
                    ?>
                        <span class="irantheme-tinymce-style-underline <?php echo esc_attr($heading_style_color_class); ?>"></span>
                    <?php
                    } else if ($heading_style == 'hashur') {
                    ?>
                        <span class="irantheme-tinymce-style-hashur" style="background: repeating-linear-gradient(-45deg, #<?php echo esc_attr($heading_hex_color); ?>, #<?php echo esc_attr($heading_hex_color); ?> 2.5px, transparent 2.5px, transparent 10px);"></span>
                    <?php
                    } else if ($heading_style == 'dotted') {
                    ?>
                        <div class="irantheme-tinymce-style-dotted">
                            <?php for ($i = 0; $i < 24; $i++): ?>
                                <span class="irantheme-tinymce-style-dotted-dot" style="background: #<?php echo esc_attr($heading_hex_color); ?>;"></span>
                            <?php endfor; ?>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            <?php endif; ?>
        <?php
        }


        /**
         * Display common buttons in sections
         * @return void
         * @param string,string,string,string,string,bool
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function section_buttons($heading_alignment, $base_color, $button_primary_checkbox, $button_secondary_checkbox, $button_style, $button_size, $button_primary_text, $button_primary_link, $button_primary_icon, $button_bg_primary, $button_bg_secondary, $button_secondary_text, $button_secondary_link, $button_secondary_icon)
        {
            if ($button_primary_checkbox == 'on') {

                // Init button values
                $button_primary_icon_tag     = '';
                $button_secondary_icon_tag   = '';
                $button_secondary_bg_class   = '';

                // Check primary button icon
                if ($button_primary_icon) {
                    $button_primary_icon_tag = '<i class="' . $button_primary_icon . ' ms-0"></i>';
                }

                // Get button style and create class for him
                $button_style_class = '';
                if ($button_style == 'rectangle') {
                    $button_style_class = '';
                } elseif ($button_style == 'circle') {
                    $button_style_class = 'irantheme-btn-radius';
                }

                // Get button size and create class for him
                $button_size_class = '';
                if ($button_size == 'small') {
                    $button_size_class = 'irantheme-btn-small';
                } elseif ($button_size == 'medium') {
                    $button_size_class = 'irantheme-btn-medium';
                } elseif ($button_size == 'large') {
                    $button_size_class = 'irantheme-btn-large';
                }

                // Check base color option
                $button_primary_bg_class = self::get_button_color_class($base_color, $button_bg_primary);

                // Check secondary button
                if ($button_secondary_checkbox == 'on') {
                    if ($button_secondary_icon) {
                        $button_secondary_icon_tag = '<i class="' . $button_secondary_icon . ' ms-0"></i>';
                    }
                    $button_secondary_bg_class = self::get_button_color_class($base_color, $button_bg_secondary);
                }

                // Set content alignment
                $content_alignment_class = Irantheme_Section_Service::get_flex_alignment_class($heading_alignment);

                // Get double buttons snippet code
                self::get_snippet_double_buttons(
                    $button_primary_checkbox,
                    $button_primary_text,
                    $button_primary_link,
                    $button_primary_bg_class,
                    $button_primary_icon_tag,
                    $button_secondary_checkbox,
                    $button_secondary_text,
                    $button_secondary_link,
                    $button_secondary_bg_class,
                    $button_secondary_icon_tag,
                    $content_alignment_class,
                    $button_style_class,
                    $button_size_class
                );
            }
        }


        /**
         * Return a compatible background color class with base color
         * @return string
         * @param string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_snippet_double_buttons($button_primary_checkbox, $button_primary_text, $button_primary_link, $button_primary_bg_class, $button_primary_icon_tag, $button_secondary_checkbox, $button_secondary_text, $button_secondary_link, $button_secondary_bg_class, $button_secondary_icon_tag, $buttons_alignment_class, $buttons_style_class, $button_size_class)
        {
        ?>
            <!-- Buttons -->
            <div class=" irantheme-btn-group irantheme-btn-group-inline irantheme-btn-group-padding-y <?php echo esc_attr($buttons_alignment_class); ?>">
                <?php
                if ($button_primary_checkbox == 'on') :
                    if ($button_primary_text) :
                ?>
                        <!-- Button primary -->
                        <a href="<?php echo esc_url($button_primary_link); ?>" target="_blank" class="irantheme-btn <?php echo esc_attr($button_primary_bg_class); ?> <?php echo esc_attr($buttons_style_class); ?> <?php echo esc_attr($button_size_class); ?>"><?php _e($button_primary_text); ?><?php echo $button_primary_icon_tag; ?></a>
                    <?php
                    endif;
                endif;
                if ($button_secondary_checkbox == 'on') :
                    if ($button_secondary_text) :
                    ?>
                        <!-- Button secondary -->
                        <a href="<?php echo esc_url($button_secondary_link); ?>" target="_blank" class="irantheme-btn <?php echo esc_attr($button_secondary_bg_class); ?> <?php echo esc_attr($buttons_style_class); ?> <?php echo esc_attr($button_size_class); ?>"><?php _e($button_secondary_text); ?><?php echo $button_secondary_icon_tag; ?></a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <?php
        }


        /**
         * Return a compatible background color class with base color
         * @return string
         * @param string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_button_color_class($base_color, $button_color)
        {
            $button_bg_class = '';
            // Check base color option
            if ($base_color == 'white' && $button_color == 'off') {
                $button_bg_class = 'irantheme-btn-dark';
            } elseif (
                $base_color == 'dark' && $button_color == 'off'
            ) {
                $button_bg_class = 'irantheme-btn-white';
            } elseif ($base_color == 'white' && $button_color == 'transparent') {
                $button_bg_class = 'irantheme-btn-dark-outline';
            } elseif (
                $base_color == 'dark' && $button_color == 'transparent'
            ) {
                $button_bg_class = 'irantheme-btn-white-outline';
            } else {
                $button_bg_class = Irantheme_Color_Static::get_class_color_mode('btn', null, Irantheme_Color_Static::get_hex_color($button_color));
            }
            return $button_bg_class;
        }


        /**
         * Return a compatible background color class with base color
         * @return string
         * @param string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_base_color_class($base_color)
        {
            // Class holder
            $base_color_class = '';
            // Check base color value
            if ($base_color == 'white') {
                $base_color_class = 'white';
            } else if ($base_color == 'dark') {
                $base_color_class = 'dark';
            }
            return $base_color_class;
        }


        /**
         * Return a compatible background reverse color class with base color
         * @return string
         * @param string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_base_reverse_color_class($base_color)
        {
            // Class holder
            $base_color_class = '';
            // Check base color value
            if ($base_color == 'white') {
                $base_color_class = 'dark';
            } else if ($base_color == 'dark') {
                $base_color_class = 'white';
            }
            return $base_color_class;
        }


        /**
         * Display categories of a taxonomy type
         * @return string
         * @param void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_categories_trigger($taxonomy = 'category', $details_color = '', $base_color_class = 'white')
        {
            $categories = get_categories(array('parent' => 0, 'hide_empty' => 0));
            if (count($categories)) : ?>
                <!-- Categories -->
                <div class="irantheme-categories-trigger <?php echo esc_attr($base_color_class); ?>">
                    <ul>
                        <?php
                        if (is_category() || is_tag()) :
                            $current_category = get_queried_object();
                            echo '<li><span class="' . esc_attr(Irantheme_Color_Static::get_class_color_mode('active', null, Irantheme_Color_Static::get_hex_color($details_color))) . ' active" data-cate="' . esc_attr($current_category->term_id) . '"><i class="fa-light fa-folders"></i>' . esc_html__($current_category->name) . '</span></li>';
                        else:
                        ?>
                            <li>
                                <span class="irantheme-all-categories <?php echo esc_attr(Irantheme_Color_Static::get_class_color_mode('active', null, Irantheme_Color_Static::get_hex_color($details_color))); ?> active">همه</span>
                            </li>
                        <?php
                            $categories = get_categories(array(
                                'orderby' => 'name',
                                'parent' => 0,
                                'taxonomy' => $taxonomy
                            ));
                            foreach ($categories as $category) {
                                echo '<li><span class="' . esc_attr(Irantheme_Color_Static::get_class_color_mode('active', null, Irantheme_Color_Static::get_hex_color($details_color))) . '" data-cate="' . esc_attr($category->term_id) . '"><i class="fa-light fa-folders"></i>' . esc_html__($category->name) . '</span></li>';
                            }
                        endif;
                        ?>
                    </ul>
                </div>
            <?php
            endif;
        }


        /**
         * Display badges with option name
         * @return void
         * @param string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_badges($data_page, $json_post_array)
        {
            ?>
            <div class="irantheme-object-group irantheme-holder-badges" data-json="<?php echo esc_attr('objects_json'); ?>">
                <?php
                // Service custom fields --------------------------------------------------------------
                foreach ($json_post_array as $object_post) :
                    // Get title options
                    $badge_code_checkbox = $object_post['fields']['badge_enable_code_checkbox'];
                    $badge_code = '';
                    $badge_image_bg = '';
                    $badge_image_link = '';
                    if ($badge_code_checkbox == 'on') {
                        // Get description option
                        $badge_code =
                            $object_post['fields']['badge_code'];
                        // Sanitize HTML output for trust badges
                        $badge_code = stripslashes($badge_code);
                        $badge_code = html_entity_decode($badge_code);
                    } else {
                        // Get badge image bg
                        $badge_image_bg =
                            $object_post['fields']['badge_image_bg'];
                        // Get badge image link
                        $badge_image_link =
                            $object_post['fields']['badge_image_link'];
                    }
                ?>
                    <div class="irantheme-object" data-object="<?php echo esc_attr($object_post['object_id']); ?>">
                        <!-- Badge item -->
                        <div class="irantheme-badge-item">
                            <?php if ($badge_code_checkbox == 'on') : ?>
                                <?php echo $badge_code; ?>
                            <?php else : ?>
                                <a href="<?php echo esc_url_raw($badge_image_link); ?>">
                                    <?php
                                    echo wp_get_attachment_image(
                                        $badge_image_bg,
                                        'medium_large',
                                        false,
                                        [
                                            'alt' => __('تصویر نشان', IRANTHEME_TEXTDOMAIN),
                                            'loading' => 'lazy',
                                        ]
                                    );
                                    ?>
                                </a>
                            <?php endif; ?>
                        </div>
                        <?php
                        Irantheme_Page_Builder::get_hidden_require_object_buttons($data_page);
                        ?>
                    </div>
                <?php
                endforeach;
                ?>
                <?php
                Irantheme_Page_Builder::get_hidden_require_object_group_buttons($data_page);
                ?>
            </div>
        <?php
        }


        /**
         * Return a background color css with background color select
         * @return string
         * @param string
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        public static function get_bg_color_css($selected_color)
        {
            // Class holder
            $bg_color_css = '';
            // Check bg color value
            if ($selected_color != 'off') {
                if ($selected_color == 'transparent') {
                    $bg_color_css = 'background-color:' . esc_attr(Irantheme_Color_Static::get_hex_color($selected_color)) . ';';
                } else {
                    $bg_color_css = 'background-color:#' . esc_attr(Irantheme_Color_Static::get_hex_color($selected_color)) . ';';
                }
            }
            return $bg_color_css;
        }

        /**
         * Return a background gradient color css with background color select
         * @param string $selected_color_one Hex code of first color
         * @param string $selected_color_two Hex code of second color
         * @param string $selected_color_three Hex code of third color
         * @param string $gradient_type The gradient type such as linear or radial
         * @param string $direction The gradient direction
         * @return string inline css code for gradient background
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        public static function get_bg_gradient_color_css($selected_color_one, $selected_color_two, $selected_color_three = '', $gradient_type = 'linear', $direction = '')
        {
            $direction_css = '';
            if ($gradient_type == 'linear') {
                switch ($direction) {
                    case 'dtt':
                        $direction_css = '0deg,';
                        break;
                    case 'dtr':
                        $direction_css = '45deg,';
                        break;
                    case 'dcr':
                        $direction_css = '90deg,';
                        break;
                    case 'dbr':
                        $direction_css = '135deg,';
                        break;
                    case 'dbb':
                        $direction_css = '180deg,';
                        break;
                    case 'dbl':
                        $direction_css = '225deg,';
                        break;
                    case 'dcl':
                        $direction_css = '270deg,';
                        break;
                    case 'dtl':
                        $direction_css = '315deg,';
                        break;

                    default:
                        $direction_css = '';
                        break;
                }
            } elseif ($gradient_type == 'radial') {
                switch ($direction) {
                    case 'dtt':
                        $direction_css = 'at center top,';
                        break;
                    case 'dtr':
                        $direction_css = 'at right top,';
                        break;
                    case 'dcr':
                        $direction_css = 'at right center,';
                        break;
                    case 'dbr':
                        $direction_css = 'at right bottom,';
                        break;
                    case 'dbb':
                        $direction_css = 'at center bottom,';
                        break;
                    case 'dbl':
                        $direction_css = 'at left bottom,';
                        break;
                    case 'dcl':
                        $direction_css = 'at left center,';
                        break;
                    case 'dtl':
                        $direction_css = 'at left top,';
                        break;

                    default:
                        $direction_css = '';
                        break;
                }
            }

            $color_on = '';
            if ($selected_color_one != '' && $selected_color_one != 'off') {
                $color_on = '#' . esc_attr(Irantheme_Color_Static::get_hex_color($selected_color_one)) . ',';
            }

            $color_two = '';
            if ($selected_color_two != '' && $selected_color_two != 'off') {
                $color_two = '#' . esc_attr(Irantheme_Color_Static::get_hex_color($selected_color_two)) . '';
            }

            $color_three = '';
            if ($selected_color_three != '' && $selected_color_three != 'off') {
                $color_three = ',#' . esc_attr(Irantheme_Color_Static::get_hex_color($selected_color_three)) . '';
            }


            // Class holder
            $bg_color_css = '';
            // Check bg color value
            if ($selected_color_one != 'off' && $selected_color_two != 'off') {
                if ($gradient_type == 'linear') {
                    $bg_color_css = 'background:linear-gradient(' . esc_attr($direction_css) . esc_attr($color_on) . esc_attr($color_two) . esc_attr($color_three) . ');';
                } elseif ($gradient_type == 'radial') {
                    $bg_color_css = 'background:radial-gradient(' . esc_attr($direction_css) . esc_attr($color_on) . esc_attr($color_two) . esc_attr($color_three) . ');';
                }
            }
            return $bg_color_css;
        }

        /**
         * Get file name (Basename) from url
         * @return string
         * @param string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_file_name_by_url($file_url)
        {
            $path_parts = pathinfo($file_url);
            return $path_parts['filename'];
        }


        /**
         * Check image existing in media library
         * @return string
         * @param string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_file_url_media_library($file_url)
        {
            $args = array(
                'post_type' => 'attachment',
                'numberposts' => -1,
                'post_status' => null,
                'post_parent' => null, // any parent
            );
            $attachments = get_posts($args);
            if ($attachments) {
                foreach ($attachments as $post) {
                    if (self::get_file_name_by_url($post->post_title) ==  self::get_file_name_by_url($file_url)) {
                        return $post->ID;
                    }
                }
            }
            return self::upload_file_by_url($file_url);
        }


        /**
         * Uploading file by a url and return media id
         * @return mixed
         * @param string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function upload_file_by_url($image_url)
        {
            // it allows us to use download_url() and wp_handle_sideload() functions
            require_once(ABSPATH . 'wp-admin/includes/file.php');

            // download to temp dir
            $temp_file = download_url($image_url);

            if (is_wp_error($temp_file)) {
                return false;
            }

            // move the temp file into the uploads directory
            $file = array(
                'name'     => basename($image_url),
                'type'     => mime_content_type($temp_file),
                'tmp_name' => $temp_file,
                'size'     => filesize($temp_file),
            );
            $sideload = wp_handle_sideload(
                $file,
                array(
                    'test_form'   => false // no needs to check 'action' parameter
                )
            );

            if (!empty($sideload['error'])) {
                // you may return error message if you want
                return false;
            }

            // it is time to add our uploaded image into WordPress media library
            $attachment_id = wp_insert_attachment(
                array(
                    'guid'           => $sideload['url'],
                    'post_mime_type' => $sideload['type'],
                    'post_title'     => basename($sideload['file']),
                    'post_content'   => '',
                    'post_status'    => 'inherit',
                ),
                $sideload['file']
            );

            if (is_wp_error($attachment_id) || !$attachment_id) {
                return false;
            }

            // update medatata, regenerate image sizes
            require_once(ABSPATH . 'wp-admin/includes/image.php');

            wp_update_attachment_metadata(
                $attachment_id,
                wp_generate_attachment_metadata($attachment_id, $sideload['file'])
            );

            return $attachment_id;
        }


        /**
         * Return general social media
         * @return string
         * @param string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_social_media($custom_class = '', $custom_css = '', $custom_css_svg = '', $title_status = false)
        {
        ?>
            <!-- Social media(s) -->
            <ul class="irantheme-social-media">
                <?php if (get_option('irantheme_instagram_social_url')) : ?>
                    <!-- Social instagram -->
                    <li>
                        <a target="_blank" class="<?php echo esc_attr($custom_class); ?>" style="<?php echo esc_attr($custom_css); ?>" href="<?php echo esc_url(get_option('irantheme_instagram_social_url')); ?>">
                            <?php if ($title_status): ?>
                                <span><?php _e('اینستاگرام'); ?></span>
                            <?php endif; ?>
                            <i class="fa-brands fa-instagram"></i>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (get_option('irantheme_facebook_social_url')) : ?>
                    <!-- Social facebook -->
                    <li>
                        <a target="_blank" class="<?php echo esc_attr($custom_class); ?>" style="<?php echo esc_attr($custom_css); ?>" href="<?php echo esc_url(get_option('irantheme_facebook_social_url')); ?>">
                            <?php if ($title_status): ?>
                                <span><?php _e('فیس بوک'); ?></span>
                            <?php endif; ?>
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (get_option('irantheme_twitter_social_url')) : ?>
                    <!-- Social twitter -->
                    <li>
                        <a target="_blank" class="<?php echo esc_attr($custom_class); ?>" style="<?php echo esc_attr($custom_css); ?>" href="<?php echo esc_url(get_option('irantheme_twitter_social_url')); ?>">
                            <?php if ($title_status): ?>
                                <span><?php _e('ایکس'); ?></span>
                            <?php endif; ?>
                            <i class="fa-brands fa-x-twitter"></i>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (get_option('irantheme_linkedin_social_url')) : ?>
                    <!-- Social linkedin -->
                    <li>
                        <a target="_blank" class="<?php echo esc_attr($custom_class); ?>" style="<?php echo esc_attr($custom_css); ?>" href="<?php echo esc_url(get_option('irantheme_linkedin_social_url')); ?>">
                            <?php if ($title_status): ?>
                                <span><?php _e('لینکدین'); ?></span>
                            <?php endif; ?>
                            <i class="fa-brands fa-linkedin"></i>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (get_option('irantheme_github_social_url')) : ?>
                    <!-- Social github -->
                    <li>
                        <a target="_blank" class="<?php echo esc_attr($custom_class); ?>" style="<?php echo esc_attr($custom_css); ?>" href="<?php echo esc_url(get_option('irantheme_github_social_url')); ?>">
                            <?php if ($title_status): ?>
                                <span><?php _e('گیت هاب'); ?></span>
                            <?php endif; ?>
                            <i class="fa-brands fa-github"></i>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (get_option('irantheme_telegram_social_url')) : ?>
                    <!-- Social telegram -->
                    <li>
                        <a target="_blank" class="<?php echo esc_attr($custom_class); ?>" style="<?php echo esc_attr($custom_css); ?>" href="<?php echo esc_url(get_option('irantheme_telegram_social_url')); ?>">
                            <?php if ($title_status): ?>
                                <span><?php _e('تلگرام'); ?></span>
                            <?php endif; ?>
                            <i class="fa-brands fa-telegram"></i>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (get_option('irantheme_youtube_social_url')) : ?>
                    <!-- Social youtube -->
                    <li>
                        <a target="_blank" class="<?php echo esc_attr($custom_class); ?>" style="<?php echo esc_attr($custom_css); ?>" href="<?php echo esc_url(get_option('irantheme_youtube_social_url')); ?>">
                            <?php if ($title_status): ?>
                                <span><?php _e('یوتیوب'); ?></span>
                            <?php endif; ?>
                            <i class="fa-brands fa-youtube"></i>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (get_option('irantheme_twitch_social_url')) : ?>
                    <!-- Social twitch -->
                    <li>
                        <a target="_blank" class="<?php echo esc_attr($custom_class); ?>" style="<?php echo esc_attr($custom_css); ?>" href="<?php echo esc_url(get_option('irantheme_twitch_social_url')); ?>">
                            <?php if ($title_status): ?>
                                <span><?php _e('توئیچ'); ?></span>
                            <?php endif; ?>
                            <i class="fa-brands fa-twitch"></i>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (get_option('irantheme_pinterest_social_url')) : ?>
                    <!-- Social pinterest -->
                    <li>
                        <a target="_blank" class="<?php echo esc_attr($custom_class); ?>" style="<?php echo esc_attr($custom_css); ?>" href="<?php echo esc_url(get_option('irantheme_pinterest_social_url')); ?>">
                            <?php if ($title_status): ?>
                                <span><?php _e('پینترست'); ?></span>
                            <?php endif; ?>
                            <i class="fa-brands fa-pinterest"></i>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (get_option('irantheme_eitaa_social_url')) : ?>
                    <!-- Social eitaa -->
                    <li>
                        <a target="_blank" class="<?php echo esc_attr($custom_class); ?>" style="<?php echo esc_attr($custom_css); ?>" href="<?php echo esc_url(get_option('irantheme_eitaa_social_url')); ?>">
                            <?php if ($title_status): ?>
                                <span><?php _e('ایتا'); ?></span>
                            <?php endif; ?>
                            <svg style="<?php echo esc_attr($custom_css_svg); ?>" viewBox="0 0 24 24" role="img" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5.968 23.942a6.624 6.624 0 0 1-2.332-.83c-1.62-.929-2.829-2.593-3.217-4.426-.151-.717-.17-1.623-.15-7.207C.288 5.47.274 5.78.56 4.79c.142-.493.537-1.34.823-1.767C2.438 1.453 3.99.445 5.913.08c.384-.073.94-.08 6.056-.08 6.251 0 6.045-.009 7.066.314a6.807 6.807 0 0 1 4.314 4.184c.33.937.346 1.087.369 3.555l.02 2.23-.391.268c-.558.381-1.29 1.06-2.316 2.15-1.182 1.256-2.376 2.42-2.982 2.907-1.309 1.051-2.508 1.651-3.726 1.864-.634.11-1.682.067-2.302-.095-.553-.144-.517-.168-.726.464a6.355 6.355 0 0 0-.318 1.546l-.031.407-.146-.03c-1.215-.241-2.419-1.285-2.884-2.5a3.583 3.583 0 0 1-.26-1.219l-.016-.34-.309-.284c-.644-.59-1.063-1.312-1.195-2.061-.212-1.193.34-2.542 1.538-3.756 1.264-1.283 3.127-2.29 4.953-2.68.658-.14 1.818-.177 2.403-.075 1.138.198 2.067.773 2.645 1.639.182.271.195.31.177.555a.812.812 0 0 1-.183.493c-.465.651-1.848 1.348-3.336 1.68-2.625.585-4.294-.142-4.033-1.759.026-.163.04-.304.031-.313-.032-.032-.293.104-.575.3-.479.334-.903.984-1.05 1.607-.036.156-.05.406-.034.65.02.331.053.454.192.736.092.186.275.45.408.589l.24.251-.096.122a4.845 4.845 0 0 0-.677 1.217 3.635 3.635 0 0 0-.105 1.815c.103.461.421 1.095.739 1.468.242.285.797.764.886.764.024 0 .044-.048.044-.106.001-.23.184-.973.326-1.327.423-1.058 1.351-1.96 2.82-2.74.245-.13.952-.47 1.572-.757 1.36-.63 2.103-1.015 2.511-1.305 1.176-.833 1.903-2.065 2.14-3.625.086-.57.086-1.634 0-2.207-.368-2.438-2.195-4.096-4.818-4.37-2.925-.307-6.648 1.953-8.942 5.427-1.116 1.69-1.87 3.565-2.187 5.443-.123.728-.169 2.08-.093 2.75.193 1.704.822 3.078 1.903 4.156a6.531 6.531 0 0 0 1.87 1.313c2.368 1.13 4.99 1.155 7.295.071.996-.469 1.974-1.196 3.023-2.25 1.02-1.025 1.71-1.88 3.592-4.458 1.04-1.423 1.864-2.368 2.272-2.605l.15-.086-.019 3.091c-.018 2.993-.022 3.107-.123 3.561-.6 2.678-2.54 4.636-5.195 5.242l-.468.107-5.775.01c-4.734.008-5.85-.002-6.19-.056z" />
                            </svg>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (get_option('irantheme_soroush_social_url')) : ?>
                    <!-- Social soroush -->
                    <li>
                        <a target="_blank" class="<?php echo esc_attr($custom_class); ?>" style="<?php echo esc_attr($custom_css); ?>" href="<?php echo esc_url(get_option('irantheme_soroush_social_url')); ?>">
                            <?php if ($title_status): ?>
                                <span><?php _e('سروش'); ?></span>
                            <?php endif; ?>
                            <svg style="<?php echo esc_attr($custom_css_svg); ?>" fill="#000000" viewBox="0 0 24 24" role="img" xmlns="http://www.w3.org/2000/svg">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <path d="m19.099 23.95-.524-.282a791.437 791.437 0 0 1-1.992-1.079l-.12-.063-.083.035c-.222.093-.754.272-1.118.378a11.717 11.717 0 0 1-3.404.459 11.492 11.492 0 0 1-3.212-.485 11.786 11.786 0 0 1-5.284-3.299A11.578 11.578 0 0 1 1.4 16.701 10.57 10.57 0 0 1 .751 15c-.31-1.06-.45-2.005-.467-3.14-.012-.785.031-1.377.154-2.107a11.68 11.68 0 0 1 3.005-6.056 11.812 11.812 0 0 1 3.777-2.69 11.694 11.694 0 0 1 3.665-.96c.123-.013.26-.028.304-.034.046-.007.377-.012.816-.012h.737l.382.045c.72.086.948.122 1.414.224.938.207 1.803.512 2.67.94.499.247.74.386 1.215.697a11.412 11.412 0 0 1 2.16 1.818c.435.466.721.822 1.08 1.345a11.628 11.628 0 0 1 1.992 5.345c.101.91.08 2.055-.056 2.998-.136.94-.441 2.02-.819 2.898a11.855 11.855 0 0 1-2.016 3.161 13.9 13.9 0 0 1-1.176 1.158l-.05.042-.002 1.414c0 .778-.006 1.488-.011 1.578l-.01.163-.05.087-.051.087H19.3c-.111 0-.113-.001-.202-.05zM12.2 19.366a6.88 6.88 0 0 0 3.573-1.429c.39-.307.797-.72 1.09-1.107.291-.387.467-.709.647-1.185.376-.994.43-2.024.17-3.205a5.5 5.5 0 0 0-.347-1.058 10.294 10.294 0 0 0-.522-1.038c-.01-.014-.015-.029-.011-.032.017-.017-.307-.477-.763-1.085-.628-.837-.883-1.212-1.092-1.608-.19-.36-.298-.64-.348-.9a2.445 2.445 0 0 1-.018-.56 2.27 2.27 0 0 1 .826-1.482l.043-.033-.023-.023c-.031-.03-.106-.039-.384-.04a4.494 4.494 0 0 0-2.053.483c-.389.191-.7.396-1.035.682-.153.13-.473.447-.473.468a.718.718 0 0 1-.085.122 5.035 5.035 0 0 0-.983 2.132c-.051.27-.066.47-.067.894 0 .76.047.997.448 2.288.23.74.316 1.092.374 1.527a5.19 5.19 0 0 1-.005 1.007 3.349 3.349 0 0 1-.944 1.868 3.293 3.293 0 0 1-2.035.972 3.768 3.768 0 0 1-1.198-.082 2.278 2.278 0 0 1-.495-.173c-.15-.067-.392-.155-.4-.146-.007.006.123.202.2.304.188.25.468.533.841.852.285.244.3.256.467.373a6.994 6.994 0 0 0 3.674 1.24c.212.01.71-.005.928-.026z"></path>
                                </g>
                            </svg>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (get_option('irantheme_rubika_social_url')) : ?>
                    <!-- Social rubika -->
                    <li>
                        <a target="_blank" class="<?php echo esc_attr($custom_class); ?>" style="<?php echo esc_attr($custom_css); ?>" href="<?php echo esc_url(get_option('irantheme_rubika_social_url')); ?>">
                            <?php if ($title_status): ?>
                                <span><?php _e('روبیکا'); ?></span>
                            <?php endif; ?>
                            <svg style="<?php echo esc_attr($custom_css_svg); ?>" id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 495.37 543.04">
                                <path d="M246.07,0h1.76c.48,42.73-.25,128.44-.25,128.44-40.86-22.92-82-45.3-122.84-68.29q40.13-22.81,80.32-45.51c12.69-7.16,26.33-13.31,41-14.64Z" fill="#b8ce01" />
                                <path d="M247.73,0h3C266.62,1.43,281,9,294.67,16.68Q332.91,38.25,371.05,60c-41,22.73-82.45,45.82-123.51,68.48-.35-42.73.63-85.77.15-128.5Z" fill="#7db425" />
                                <path d="M124.77,60.11,247.61,128.4,123.92,200.66s-.06-45.85.08-68.71C123.89,108.21,124.77,60.11,124.77,60.11Z" fill="#f6a925" />
                                <path d="M371.19,60l.35.19c-.1,42.59,0,85.18,0,127.77-.07,4.17-.11,12.77-.11,12.77L247.62,128.45S330.2,82.76,371.23,60Z" fill="#35ac9d" />
                                <path d="M371.44,60.21c27.28,15.51,54.37,31.36,81.47,47.17,13.11,7.36,27,15.52,34.16,29.39-38.38,21.25-115.68,64-115.68,64s-.06-8.61,0-12.78q0-63.89,0-127.77Z" fill="#59d6bd" />
                                <path d="M46.5,105c25.65-15,78.19-44.88,78.19-44.88L124,200.66S47,158.21,8.71,137C17.05,122.13,32.11,113.12,46.5,105Z" fill="#ef7414" />
                                <path d="M247.5,128.44l123.81,72.3s-39.84,22-59.62,32.64c-21.14,12-64.09,35.82-64.09,35.82L123.94,200.66Z" fill="#fff" />
                                <path d="M0,193C.11,174.17-.23,154.15,8.66,137,47,158.21,123.92,200.66,123.92,200.66,82.55,224.58,41.38,248.86,0,272.81,0,246.21,0,219.62,0,193Z" fill="#e74b50" />
                                <path d="M487.07,136.77c7.54,14.1,8.64,30.5,8.22,46.19v90.11L371.41,200.76S448.69,158,487.07,136.77Z" fill="#794387" />
                                <path d="M123.92,200.66c.19-.08,123.66,68.54,123.66,68.54s0,71.69.07,106.76c.2,11.29-.07,34.11-.07,34.11-41.27-22.78-82.32-45.76-123.6-68.54l-.07-.31V200.66Z" fill="#e4e4e4" />
                                <path d="M0,272.81c41.35-24,82.52-48.23,123.89-72.15q-.06,70.29,0,140.56c-6.35-2.65-12.13-6.43-18.19-9.64C70.5,312.21,35.38,292.39,0,273.21v-.4Z" fill="#794387" />
                                <path d="M371.39,200.76v86.18c-.13,18.27.46,36.47,0,54.73-41.21,22.4-123.82,68.4-123.82,68.4s.27-22.82.07-34.11c0-35.07-.07-106.76-.07-106.76Z" fill="#f1f1f1" />
                                <path d="M371.39,200.76s123.88,72.14,123.88,72.31c-41.17,22.82-123.87,68.6-123.87,68.6h0c.46-18.26-.13-36.46,0-54.73V200.76Z" fill="#4c3683" />
                                <path d="M0,273.21c35.36,19.21,70.43,39,105.7,58.4,6.06,3.21,11.84,7,18.19,9.64l.07.31Q67,374.79,10,408.05C6,402,4,394.86,2.53,387.8.14,376,0,364,0,352v-78.8Z" fill="#4c3683" />
                                <path d="M495.27,273.07c.06,27.65,0,55.3,0,82.95.4,17.73-.12,36.62-9.57,52.25-34.2-20-114.33-66.6-114.33-66.6S454.1,295.89,495.27,273.07Z" fill="#e74b50" />
                                <path d="M10,408Q67,374.72,124,341.53c-.25,46.83.08,93.67-.16,140.49q-40.35-22.75-80.49-45.86C30.82,428.76,18,420.53,10,408Z" fill="#0f68a0" />
                                <path d="M124,341.53c41.28,22.78,82.33,45.76,123.6,68.54-41.2,24-123.7,72-123.76,71.95.24-46.82-.09-93.66.16-140.49Z" fill="#49bdca" />
                                <path d="M371.2,341.76l.2-.09c.12,46.84-.25,93.69.19,140.53l-.31.15c-32.66-19.51-65.71-38.4-98.52-57.68-8.26-4.7-25.18-14.6-25.18-14.6S330,364.21,371.2,341.76Z" fill="#f6a925" />
                                <path d="M371.4,341.67h0c3.65,2.49,80.13,46.62,114.33,66.6-4.86,8.3-12.56,14.39-20.32,19.84-12.52,8.42-25.88,15.47-38.9,23.05-18.31,10.35-36.5,20.91-54.92,31-.44-46.84-.07-93.69-.19-140.53Z" fill="#ef7414" />
                                <path d="M247.58,410.07V543c-10.25-1.51-21.42-3.87-30.5-9.08C186,516.66,154.81,499.5,123.82,482,165,458,206.38,434.12,247.58,410.07Z" fill="#7db425" />
                                <path d="M247.58,410.07s16.92,9.9,25.18,14.6c32.81,19.28,65.86,38.17,98.52,57.68q-47.79,26.44-95.57,53A62.47,62.47,0,0,1,249.84,543h-2.26Z" fill="#b8ce01" />
                                <path d="M602.83,233.66" fill="#f1f1f1" />
                            </svg>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (get_option('irantheme_gap_social_url')) : ?>
                    <!-- Social gap -->
                    <li>
                        <a target="_blank" class="<?php echo esc_attr($custom_class); ?>" style="<?php echo esc_attr($custom_css); ?>" href="<?php echo esc_url(get_option('irantheme_gap_social_url')); ?>">
                            <?php if ($title_status): ?>
                                <span><?php _e('گپ'); ?></span>
                            <?php endif; ?>
                            <svg style="<?php echo esc_attr($custom_css_svg); ?>" viewBox="0 0 24 24" role="img" xmlns="http://www.w3.org/2000/svg">
                                <path d="M11.461 23.836a12.105 12.105 0 0 1-4.288-.994 12.052 12.052 0 0 1-5.379-4.64c-.243-.39-.409-.69-.591-1.069a12.998 12.998 0 0 1-.852-2.357 14.211 14.211 0 0 1-.327-2.08 10.448 10.448 0 0 1 .005-1.492c.077-.881.193-1.588.356-2.177C.67 8 1.085 6.99 1.569 6.142a11.836 11.836 0 0 1 3.107-3.535A12.138 12.138 0 0 1 10.471.24c.502-.063.777-.08 1.384-.087.484-.005.593-.003.9.018.647.044 1.25.13 1.845.261a12.061 12.061 0 0 1 5.36 2.703c.272.238.838.801 1.07 1.065a12.192 12.192 0 0 1 1.646 2.385c.263.501.557 1.207.754 1.812.312.962.46 1.716.553 2.829.012.148.017.367.017.818 0 .577-.002.636-.03.918a14.146 14.146 0 0 1-.332 1.983c-.186.73-.322 1.111-.674 1.895-.466 1.04-.58 1.34-.685 1.795a3.45 3.45 0 0 0-.098.804c-.006.261-.004.312.018.467.04.288.123.582.227.805.117.252.365.615.603.883.08.09.278.292.44.45.307.298.406.406.484.528l.047.072v.244l-.054.039a1.495 1.495 0 0 1-.514.22c-.219.049-1.047.07-1.502.04a8.495 8.495 0 0 1-2.994-.761c-.306-.145-.357-.161-.522-.162a.76.76 0 0 0-.253.032 4.578 4.578 0 0 0-.602.237c-.72.343-1.223.548-1.784.73a12.3 12.3 0 0 1-4.314.573zm1.412-1.669a12.51 12.51 0 0 0 1.457-.186c.368-.078 1.037-.298 1.564-.516.296-.123.706-.305.886-.396.342-.171.807-.317 1.34-.42.201-.038.282-.041.469-.018.377.048.647.133 1.355.429.174.072.396.16.495.197.19.069.456.15.464.141a.519.519 0 0 0-.029-.11c-.114-.35-.25-.907-.31-1.264a3.001 3.001 0 0 1 0-1.051c.036-.242.15-.771.172-.811.006-.01.032-.101.057-.203a8.135 8.135 0 0 1 .436-1.265.2.2 0 0 0 .021-.06c.002-.013.069-.168.15-.342.373-.81.55-1.291.694-1.887.202-.84.286-1.821.246-2.847a11.668 11.668 0 0 0-.153-1.485 53.968 53.968 0 0 1-.068-.4c-.021-.137-.166-.635-.195-.669a.225.225 0 0 1-.028-.062 15.257 15.257 0 0 0-.348-.872 13.75 13.75 0 0 0-.75-1.446.442.442 0 0 1-.05-.096c0-.02-.216-.347-.23-.348a1.29 1.29 0 0 1-.126-.156 11.594 11.594 0 0 0-2.102-2.132 2.213 2.213 0 0 1-.178-.15 2.13 2.13 0 0 0-.208-.147 11.798 11.798 0 0 0-2.792-1.35c-.523-.173-.683-.215-1.058-.278a12.297 12.297 0 0 0-4.056-.016c-.414.068-.558.105-1.063.27-.842.277-1.627.634-2.374 1.079-.28.166-.617.384-.621.4a1.9 1.9 0 0 1-.183.152 11.62 11.62 0 0 0-2.16 2.179 1.004 1.004 0 0 1-.113.138 1.54 1.54 0 0 0-.177.27.25.25 0 0 1-.039.076 13.204 13.204 0 0 0-1.17 2.483.553.553 0 0 1-.049.12c-.007.003-.012.019-.013.034 0 .014-.02.092-.045.172-.179.596-.26 1.129-.318 2.1-.056.916.045 2.098.253 2.953.124.508.418 1.302.717 1.935.058.123.107.235.109.248.007.058.528.965.585 1.02.015.014.092.117.172.228.65.909 1.445 1.714 2.388 2.418.266.199.464.326.86.551.55.313 1.151.6 1.698.807.34.13.902.31 1.158.37.293.07.97.165 1.437.204.575.047 1.229.05 1.823.01zM7.707 17.1c-.699-.128-1.122-.905-.891-1.639.123-.393.417-.693.788-.803.095-.028.117-.03.487-.04.214-.006.946-.01 1.626-.006 1.091.004 1.247.006 1.317.022.331.077.585.25.764.521.137.207.205.529.176.836-.019.211-.059.392-.103.47-.186.33-.425.524-.77.627-.078.023-.094.023-1.693.025-1.34.002-1.63 0-1.7-.013zm.086-3.838a1.193 1.193 0 0 1-.89-.618c-.098-.189-.128-.346-.128-.663 0-.226.01-.29.062-.435.102-.284.314-.522.583-.658.186-.094.325-.115.79-.123a837.07 837.07 0 0 1 4.19.004l3.831.01.096.03a1.224 1.224 0 0 1 .832.852c.036.134.044.41.019.573-.093.582-.464.955-1.02 1.024-.09.01-8.279.015-8.365.004zm5.232-3.86a1.59 1.59 0 0 1-.266-.065 1.32 1.32 0 0 1-.592-.469c-.015-.023-.032-.04-.037-.037-.014.009-.078-.181-.109-.32-.056-.262-.044-.624.026-.832.079-.23.252-.454.46-.594a1.27 1.27 0 0 1 .36-.159l.113-.03h3.24l.113.03c.388.105.693.383.82.747.082.235.085.666.005.933-.044.15-.172.388-.207.388a.032.032 0 0 0-.024.014 1.468 1.468 0 0 1-.217.188 1.45 1.45 0 0 1-.316.157c-.182.058-.15.057-1.8.056-.844-.001-1.55-.004-1.57-.006z" />
                            </svg>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (get_option('irantheme_bale_social_url')) : ?>
                    <!-- Social bale -->
                    <li>
                        <a target="_blank" class="<?php echo esc_attr($custom_class); ?>" style="<?php echo esc_attr($custom_css); ?>" href="<?php echo esc_url(get_option('irantheme_bale_social_url')); ?>">
                            <?php if ($title_status): ?>
                                <span><?php _e('بله'); ?></span>
                            <?php endif; ?>
                            <svg style="<?php echo esc_attr($custom_css_svg); ?>" fill="#000000" viewBox="0 0 24 24" role="img" xmlns="http://www.w3.org/2000/svg">
                                <path d="M11.425 23.987a12.218 12.218 0 0 1-2.95-.514 6.578 6.578 0 0 0-.336-.116C4.936 22.303 2.22 19.763.913 16.599a11.92 11.92 0 0 1-.9-4.063C.005 12.377.001 10.246 0 6.74 0 .71-.005 1.137.07.903.23.394.673.05 1.224.005c.421-.034.7.088 1.603.699.562.38 1.119.78 1.796 1.289.315.237.353.261.376.247l.35-.23c.58-.381 1.11-.677 1.7-.945A11.913 11.913 0 0 1 9.766.21a11.19 11.19 0 0 1 2.041-.2c1.14-.016 2.077.091 3.152.36 3.55.888 6.538 3.411 8.028 6.78.492 1.113.845 2.43.945 3.522.033.366.039.43.053.611.008.105.015.406.015.669 0 .783-.065 1.57-.169 2.064a5.474 5.474 0 0 0-.046.26c-.056.378-.214.987-.399 1.535-.205.613-.367.999-.684 1.633a11.95 11.95 0 0 1-2.623 3.436c-.44.396-.829.705-1.26 1.003-.647.445-1.307.812-2.039 1.134-.6.265-1.44.539-2.101.686a11.165 11.165 0 0 1-1.178.202 12.28 12.28 0 0 1-2.076.082zm-.61-5.92c.294-.06.678-.209.864-.337.144-.099.428-.376 2.064-2.013a161.8 161.8 0 0 1 1.764-1.753c.017 0 1.687-1.67 1.687-1.689 0-.02 1.64-1.648 1.661-1.648.01 0 .063-.047.118-.106.467-.495.682-.957.716-1.547.026-.433-.06-.909-.217-1.196a2.552 2.552 0 0 0-.983-1.024c-.281-.163-.512-.233-.888-.27-.306-.031-.688 0-.948.075-.243.07-.603.274-.853.481-.042.035-1.279 1.265-2.748 2.733l-2.671 2.67-1.093-1.09c-.6-.6-1.12-1.114-1.155-1.142a2.419 2.419 0 0 0-1.338-.51c-.404-.013-.91.09-1.224.25a2.89 2.89 0 0 0-.659.526c-.108.12-.287.357-.29.385-.003.03-.009.044-.065.16a2.312 2.312 0 0 0-.224.91c-.011.229-.01.265.019.491.045.353.24.781.51 1.115.05.063.97.992 2.044 2.064 1.507 1.505 1.98 1.97 2.074 2.039.327.24.683.388 1.101.456.182.03.5.016.734-.03z" />
                            </svg>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (get_option('irantheme_aparat_social_url')) : ?>
                    <!-- Social aparat -->
                    <li>
                        <a target="_blank" class="<?php echo esc_attr($custom_class); ?>" style="<?php echo esc_attr($custom_css); ?>" href="<?php echo esc_url(get_option('irantheme_aparat_social_url')); ?>">
                            <?php if ($title_status): ?>
                                <span><?php _e('آپارات'); ?></span>
                            <?php endif; ?>
                            <svg style="<?php echo esc_attr($custom_css_svg); ?>" fill="#000000" viewBox="0 0 24 24" role="img" xmlns="http://www.w3.org/2000/svg">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <path d="M12.001 1.594c-9.27-.003-13.913 11.203-7.36 17.758a10.403 10.403 0 0 0 17.76-7.355c0-5.744-4.655-10.401-10.4-10.403zM6.11 6.783c.501-2.598 3.893-3.294 5.376-1.103 1.483 2.19-.422 5.082-3.02 4.582A2.97 2.97 0 0 1 6.11 6.783zm4.322 8.988c-.504 2.597-3.897 3.288-5.377 1.096-1.48-2.192.427-5.08 3.025-4.579a2.97 2.97 0 0 1 2.352 3.483zm1.26-2.405c-1.152-.223-1.462-1.727-.491-2.387.97-.66 2.256.18 2.04 1.334a1.32 1.32 0 0 1-1.548 1.053zm6.198 3.838c-.501 2.598-3.893 3.293-5.376 1.103-1.484-2.191.421-5.082 3.02-4.583a2.97 2.97 0 0 1 2.356 3.48zm-1.967-5.502c-2.598-.501-3.293-3.896-1.102-5.38 2.19-1.483 5.081.422 4.582 3.02a2.97 2.97 0 0 1-3.48 2.36zM13.59 23.264l2.264.61a3.715 3.715 0 0 0 4.543-2.636l.64-2.402a11.383 11.383 0 0 1-7.448 4.428zm7.643-19.665L18.87 2.97a11.376 11.376 0 0 1 4.354 7.62l.65-2.459A3.715 3.715 0 0 0 21.231 3.6zM.672 13.809l-.541 2.04a3.715 3.715 0 0 0 2.636 4.543l2.107.562a11.38 11.38 0 0 1-4.203-7.145zM10.357.702 8.15.126a3.715 3.715 0 0 0-4.547 2.637l-.551 2.082A11.376 11.376 0 0 1 10.358.702z"></path>
                                </g>
                            </svg>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (get_option('irantheme_igap_social_url')) : ?>
                    <!-- Social igap -->
                    <li>
                        <a target="_blank" class="<?php echo esc_attr($custom_class); ?>" style="<?php echo esc_attr($custom_css); ?>" href="<?php echo esc_url(get_option('irantheme_igap_social_url')); ?>">
                            <?php if ($title_status): ?>
                                <span><?php _e('آی گپ'); ?></span>
                            <?php endif; ?>
                            <svg style="<?php echo esc_attr($custom_css_svg); ?>" version="1.0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 2480.000000 2480.000000" preserveAspectRatio="xMidYMid meet">
                                <g transform="translate(0.000000,2480.000000) scale(0.100000,-0.100000)" fill="#000000" stroke="none">
                                    <path d="M11945 23939 c-331 -8 -404 -12 -480 -29 -102 -22 -296 -41 -338 -33
  -16 3 -51 -3 -85 -15 -56 -21 -202 -42 -272 -41 -19 1 -55 -8 -81 -20 -28 -12
  -77 -23 -130 -26 -46 -4 -105 -15 -131 -26 -26 -11 -82 -22 -125 -25 -46 -4
  -90 -14 -108 -24 -36 -22 -128 -42 -150 -34 -9 4 -40 -5 -69 -20 -30 -15 -79
  -30 -112 -33 -32 -4 -75 -16 -98 -29 -26 -15 -57 -24 -85 -24 -25 0 -57 -8
  -75 -19 -17 -10 -53 -21 -80 -25 -27 -3 -63 -15 -81 -26 -17 -11 -56 -25 -86
  -31 -30 -6 -67 -19 -84 -29 -16 -10 -48 -20 -70 -22 -22 -3 -58 -17 -79 -32
  -23 -15 -53 -26 -72 -26 -19 0 -49 -11 -70 -25 -20 -14 -51 -25 -69 -25 -20 0
  -42 -9 -59 -25 -18 -17 -39 -25 -63 -25 -23 0 -45 -9 -64 -25 -16 -13 -46 -27
  -66 -31 -20 -4 -52 -17 -71 -30 -19 -13 -47 -24 -62 -24 -15 0 -36 -10 -50
  -25 -15 -16 -34 -25 -55 -25 -19 0 -39 -8 -50 -20 -10 -11 -30 -20 -45 -20
  -15 0 -44 -13 -65 -30 -21 -16 -46 -30 -55 -30 -9 0 -33 -11 -52 -24 -20 -13
  -51 -29 -71 -35 -19 -7 -40 -18 -47 -26 -6 -7 -23 -16 -37 -20 -15 -3 -38 -15
  -51 -26 -14 -10 -34 -19 -46 -19 -12 0 -35 -13 -51 -30 -17 -18 -39 -30 -55
  -30 -16 0 -35 -11 -52 -30 -16 -18 -36 -30 -51 -30 -13 0 -31 -7 -38 -16 -7
  -9 -32 -26 -55 -38 -86 -44 -121 -65 -133 -80 -8 -9 -23 -16 -35 -16 -13 0
  -33 -11 -45 -24 -13 -13 -51 -38 -87 -56 -35 -17 -70 -40 -77 -50 -7 -11 -27
  -25 -43 -32 -39 -18 -111 -64 -119 -79 -4 -6 -22 -17 -39 -25 -17 -8 -51 -30
  -76 -49 -25 -19 -61 -46 -82 -60 -20 -13 -39 -29 -43 -35 -3 -5 -13 -10 -22
  -10 -8 0 -42 -23 -74 -51 -69 -59 -119 -94 -148 -104 -12 -3 -21 -10 -21 -14
  0 -12 -163 -148 -188 -155 -12 -4 -51 -36 -88 -70 -36 -35 -117 -106 -181
  -157 -122 -99 -319 -287 -588 -559 -258 -261 -315 -321 -315 -332 0 -5 -42
  -57 -92 -114 -117 -133 -169 -195 -237 -284 -30 -39 -66 -84 -80 -100 -51 -58
  -71 -83 -71 -91 0 -8 -65 -98 -79 -109 -15 -12 -71 -90 -71 -98 0 -11 -55 -82
  -89 -118 -12 -12 -21 -28 -21 -35 0 -14 -62 -102 -94 -135 -9 -8 -16 -22 -16
  -31 0 -8 -9 -27 -21 -41 -66 -86 -79 -106 -79 -122 0 -9 -9 -25 -21 -36 -19
  -17 -34 -41 -78 -123 -8 -14 -25 -39 -38 -54 -12 -16 -23 -39 -23 -52 0 -13
  -9 -30 -19 -37 -19 -13 -83 -125 -135 -234 -13 -28 -29 -55 -35 -58 -6 -4 -11
  -15 -11 -25 0 -10 -13 -35 -30 -56 -16 -21 -30 -47 -30 -58 0 -11 -11 -35 -25
  -53 -14 -18 -25 -42 -25 -53 0 -11 -11 -33 -25 -49 -14 -17 -25 -38 -25 -49 0
  -10 -11 -34 -24 -53 -13 -19 -26 -49 -30 -67 -4 -17 -13 -37 -21 -43 -8 -6
  -22 -35 -31 -63 -9 -29 -25 -63 -35 -76 -11 -13 -19 -37 -19 -53 0 -18 -11
  -43 -25 -60 -14 -16 -25 -39 -25 -51 0 -12 -14 -49 -30 -82 -17 -33 -30 -66
  -30 -75 0 -8 -12 -33 -25 -55 -14 -22 -25 -54 -25 -71 0 -16 -11 -45 -25 -64
  -15 -20 -25 -47 -25 -67 0 -18 -13 -59 -30 -90 -17 -31 -30 -65 -30 -77 0 -12
  -9 -39 -20 -60 -10 -21 -24 -62 -30 -91 -6 -29 -20 -70 -30 -91 -11 -21 -20
  -54 -20 -74 0 -19 -11 -55 -24 -80 -13 -25 -26 -69 -29 -99 -4 -29 -16 -71
  -27 -93 -11 -21 -20 -58 -20 -81 0 -23 -12 -69 -25 -102 -14 -33 -25 -76 -25
  -95 0 -39 -21 -125 -39 -157 -6 -12 -13 -44 -16 -70 -8 -82 -23 -156 -38 -193
  -8 -19 -18 -75 -21 -125 -4 -49 -16 -115 -26 -145 -10 -31 -21 -104 -25 -170
  -4 -63 -16 -149 -27 -190 -12 -48 -21 -119 -23 -198 -5 -156 -22 -346 -40
  -441 -20 -104 -20 -1030 0 -1135 17 -87 34 -281 39 -441 2 -71 11 -141 20
  -171 9 -28 22 -118 30 -200 7 -82 21 -168 30 -191 8 -23 18 -86 22 -140 4 -54
  13 -110 20 -124 18 -36 33 -104 38 -176 2 -33 10 -73 19 -89 8 -16 20 -65 27
  -109 7 -44 21 -101 31 -126 10 -26 21 -73 25 -105 3 -33 14 -73 24 -89 9 -17
  20 -55 24 -85 4 -29 16 -74 27 -98 11 -24 26 -72 34 -107 7 -34 20 -73 28 -86
  9 -13 19 -44 23 -69 3 -25 17 -67 30 -94 13 -28 24 -61 24 -75 0 -15 11 -48
  25 -75 14 -27 25 -59 25 -70 0 -12 12 -43 25 -71 14 -27 25 -59 25 -71 0 -11
  11 -35 25 -54 14 -18 27 -50 31 -69 3 -20 14 -52 25 -72 10 -20 19 -46 19 -56
  0 -11 11 -36 25 -56 13 -20 29 -56 36 -79 6 -23 19 -53 30 -66 10 -14 19 -35
  19 -48 0 -12 11 -36 25 -52 14 -17 25 -41 25 -55 0 -14 11 -39 25 -55 14 -17
  25 -40 25 -51 0 -12 11 -39 25 -59 14 -20 25 -43 25 -50 0 -7 11 -30 25 -50
  14 -20 25 -44 25 -54 0 -10 14 -33 30 -51 17 -19 30 -44 30 -54 0 -11 13 -37
  30 -58 17 -21 30 -45 30 -55 0 -9 11 -29 25 -44 14 -15 25 -37 25 -50 0 -13 7
  -30 16 -37 9 -7 26 -32 38 -55 44 -86 65 -121 80 -133 9 -8 16 -23 16 -35 0
  -13 11 -33 24 -45 13 -12 38 -51 56 -86 18 -35 43 -74 56 -86 13 -12 24 -30
  24 -40 0 -10 25 -48 55 -84 30 -37 55 -74 55 -81 0 -13 53 -89 84 -120 8 -8
  18 -24 22 -37 4 -12 25 -44 48 -71 53 -64 106 -139 106 -150 0 -4 9 -18 20
  -30 48 -52 140 -168 140 -176 0 -5 10 -20 22 -33 13 -14 32 -35 43 -48 11 -13
  48 -55 81 -94 33 -38 72 -88 87 -110 15 -22 46 -60 69 -85 339 -364 542 -570
  772 -785 77 -72 167 -156 200 -187 34 -32 65 -58 70 -58 5 0 16 -7 25 -15 9
  -8 52 -44 96 -81 44 -37 91 -79 105 -94 14 -15 41 -36 60 -46 45 -24 151 -106
  195 -151 11 -12 33 -25 48 -28 15 -4 31 -12 35 -19 13 -21 170 -136 186 -136
  8 0 49 -33 102 -81 12 -10 29 -19 38 -19 8 0 30 -13 48 -28 46 -38 109 -79
  136 -87 12 -3 27 -15 32 -26 6 -10 40 -32 75 -48 35 -16 74 -41 86 -55 14 -17
  33 -26 51 -26 17 0 31 -7 35 -15 5 -15 19 -23 134 -81 25 -13 52 -30 59 -39 7
  -8 21 -15 31 -15 10 0 34 -14 53 -30 21 -19 40 -28 50 -24 10 4 26 -5 45 -25
  20 -22 38 -31 60 -31 22 0 35 -7 47 -25 12 -18 25 -25 48 -25 23 0 41 -9 61
  -30 20 -21 35 -28 48 -24 13 4 29 -4 52 -26 24 -23 40 -31 55 -27 14 4 32 -4
  54 -24 19 -16 45 -29 59 -29 14 0 39 -11 55 -25 17 -14 41 -25 55 -25 13 0 35
  -9 49 -19 13 -11 42 -24 65 -30 22 -6 53 -21 68 -33 16 -13 45 -23 67 -24 23
  -1 49 -10 61 -22 12 -11 38 -22 58 -26 21 -4 53 -18 73 -31 21 -14 51 -25 70
  -25 18 0 45 -9 58 -20 14 -10 47 -24 74 -30 27 -6 63 -19 80 -29 17 -10 50
  -21 75 -25 25 -4 58 -15 74 -25 17 -10 50 -21 75 -25 25 -4 63 -15 85 -26 22
  -10 67 -24 100 -30 33 -7 74 -21 91 -31 18 -11 50 -19 76 -19 28 0 63 -9 94
  -25 30 -16 78 -29 122 -34 40 -5 84 -16 98 -26 14 -9 61 -20 109 -25 46 -5
  100 -17 121 -28 24 -12 71 -22 121 -25 46 -4 104 -15 130 -26 29 -12 85 -22
  143 -26 52 -4 122 -16 155 -27 33 -11 71 -20 85 -20 72 2 257 -21 316 -39 37
  -11 81 -19 98 -16 51 7 363 -12 471 -30 60 -9 191 -18 327 -20 165 -4 230 -9
  238 -18 7 -8 10 -594 10 -1857 0 -1015 3 -1859 6 -1876 4 -19 13 -32 21 -32 9
  0 101 85 207 188 105 104 219 215 253 248 397 379 541 520 1513 1480 610 603
  827 816 1059 1040 85 82 192 185 237 229 45 44 149 144 230 222 82 78 298 289
  479 469 666 659 1455 1436 1635 1610 102 98 213 206 247 239 34 33 137 132
  229 220 297 284 947 940 1012 1021 34 43 84 102 110 129 108 116 194 213 205
  232 29 49 57 86 98 131 52 57 109 129 109 136 0 7 58 84 111 147 22 26 39 51
  39 57 0 10 54 81 90 117 11 12 20 28 20 36 0 15 55 92 93 131 9 10 17 25 17
  34 0 15 16 40 73 108 15 19 27 41 27 49 0 9 10 28 23 44 38 47 77 108 77 121
  0 6 14 27 30 45 17 19 30 44 30 55 0 11 7 25 16 33 15 12 36 47 80 133 12 23
  29 48 38 55 9 7 16 24 16 37 0 13 14 39 30 57 17 19 30 44 30 54 0 11 11 32
  25 47 14 15 25 39 25 55 0 16 10 37 25 51 14 13 25 34 25 46 0 12 12 36 26 53
  14 17 27 42 29 56 1 14 12 36 24 48 11 13 24 38 27 57 4 19 18 48 31 63 12 16
  23 39 23 51 0 13 9 34 19 48 11 13 24 43 30 66 7 23 23 59 36 79 14 20 25 45
  25 57 0 12 9 35 20 51 11 17 20 39 20 50 0 11 14 47 30 80 16 33 30 67 30 75
  0 8 10 31 22 50 12 19 25 57 29 84 5 27 16 57 25 67 9 10 21 42 25 71 5 29 18
  65 30 81 12 17 23 48 24 73 2 23 15 62 29 87 16 26 26 59 26 82 0 22 10 56 24
  79 13 23 26 65 29 95 4 29 16 71 27 93 11 21 20 57 20 80 0 22 11 66 24 97 14
  31 30 89 36 129 6 41 17 87 26 103 8 17 19 68 25 115 5 47 16 99 24 115 9 16
  22 80 30 142 7 61 21 129 29 149 9 21 16 59 16 85 0 67 25 225 40 253 7 13 13
  71 15 134 4 123 19 252 40 336 16 63 20 245 20 875 0 562 -8 831 -29 920 -8
  39 -20 142 -26 230 -6 88 -18 181 -26 206 -8 25 -21 106 -29 180 -7 74 -21
  154 -30 178 -9 25 -19 83 -22 130 -3 50 -13 101 -23 121 -17 35 -30 97 -36
  178 -1 24 -8 50 -14 57 -15 18 -40 112 -40 151 0 17 -9 52 -20 78 -12 25 -24
  70 -27 99 -3 28 -14 68 -23 87 -10 19 -21 58 -25 85 -4 28 -15 69 -26 92 -10
  23 -25 68 -33 100 -7 32 -20 73 -29 90 -9 17 -19 50 -23 74 -4 24 -15 58 -24
  74 -10 17 -22 48 -26 70 -3 22 -15 56 -24 75 -10 19 -23 55 -30 80 -7 25 -21
  57 -31 71 -10 15 -19 40 -19 56 0 15 -12 46 -25 68 -14 22 -25 47 -25 56 0 9
  -13 41 -30 72 -16 31 -30 66 -30 78 0 12 -11 34 -25 51 -14 16 -25 42 -25 57
  0 16 -8 39 -19 52 -10 13 -24 42 -31 64 -7 22 -21 52 -31 66 -10 14 -21 38
  -25 55 -4 16 -18 45 -31 64 -12 19 -23 42 -23 50 0 9 -11 31 -25 50 -14 19
  -25 41 -25 50 0 9 -11 31 -24 49 -14 19 -28 46 -31 62 -4 16 -11 31 -16 35 -5
  3 -22 31 -38 62 -64 131 -108 209 -129 230 -12 12 -22 33 -22 45 0 13 -10 32
  -21 43 -12 10 -30 37 -40 59 -23 50 -37 72 -71 113 -16 18 -28 40 -28 49 0 9
  -9 26 -19 37 -10 12 -33 48 -50 81 -18 33 -44 74 -60 90 -15 17 -37 51 -49 76
  -12 25 -31 54 -43 65 -22 20 -89 116 -89 128 0 4 -25 37 -55 72 -30 36 -55 70
  -55 76 0 12 -54 82 -129 166 -12 15 -31 43 -42 63 -10 20 -35 54 -56 76 -61
  63 -178 205 -201 243 -11 19 -36 49 -54 66 -18 16 -60 59 -92 95 -240 262
  -390 416 -596 609 -79 74 -157 149 -174 165 -78 74 -197 180 -209 185 -7 3
  -20 11 -28 17 -8 7 -52 45 -99 84 -47 39 -94 82 -105 94 -11 13 -41 35 -67 49
  -26 14 -56 33 -66 43 -21 20 -70 60 -118 98 -18 14 -36 25 -42 25 -6 0 -43 27
  -82 60 -39 33 -76 60 -80 60 -14 0 -98 59 -112 79 -7 9 -38 30 -68 45 -30 15
  -68 40 -85 55 -16 15 -55 40 -85 55 -30 16 -65 39 -78 52 -13 13 -33 24 -46
  24 -12 0 -33 11 -46 25 -13 14 -29 25 -35 25 -7 0 -20 9 -29 19 -10 11 -31 23
  -46 26 -15 3 -38 17 -50 31 -13 13 -33 24 -46 24 -12 0 -28 7 -35 16 -13 15
  -50 37 -145 84 -23 11 -47 27 -54 35 -7 8 -20 15 -30 15 -9 0 -37 13 -61 30
  -24 16 -52 30 -61 30 -9 0 -30 11 -46 25 -17 14 -39 25 -50 25 -11 0 -34 11
  -50 25 -17 14 -39 25 -50 25 -11 0 -33 11 -49 24 -15 13 -44 26 -64 30 -21 4
  -42 13 -49 21 -6 8 -33 21 -59 30 -26 8 -63 27 -81 40 -19 14 -46 25 -61 25
  -15 0 -41 11 -57 24 -17 15 -36 23 -48 20 -11 -3 -35 5 -56 20 -21 14 -53 28
  -72 31 -19 4 -49 18 -67 31 -19 14 -47 24 -66 24 -20 0 -50 11 -71 25 -20 14
  -46 25 -58 25 -11 0 -41 11 -65 23 -24 13 -63 26 -86 29 -23 3 -57 16 -74 29
  -20 14 -50 24 -77 25 -28 2 -59 13 -83 29 -25 16 -53 25 -77 25 -22 0 -59 10
  -83 23 -24 13 -68 27 -98 30 -29 3 -69 16 -89 28 -22 13 -57 22 -87 23 -30 1
  -73 13 -109 30 -36 17 -68 26 -84 23 -27 -5 -112 17 -146 38 -12 7 -55 15 -97
  18 -49 3 -91 13 -117 26 -29 15 -69 23 -134 27 -59 4 -109 14 -138 26 -25 11
  -63 20 -85 20 -71 -1 -198 18 -260 37 -42 13 -104 21 -205 25 -96 4 -172 13
  -225 26 -49 13 -106 20 -145 19 -36 -1 -173 1 -305 4 -465 14 -574 14 -960 6z
  m472 -2664 c3 -1291 -8 -1159 102 -1149 112 10 456 -13 540 -37 48 -14 88 -19
  112 -15 49 8 180 -11 238 -34 27 -10 72 -18 106 -18 37 0 83 -9 120 -22 33
  -12 92 -25 130 -29 42 -5 92 -18 127 -35 39 -18 65 -25 82 -20 18 4 41 -2 76
  -20 27 -14 65 -26 83 -26 19 0 54 -11 78 -23 24 -13 64 -27 89 -31 25 -4 62
  -18 82 -31 22 -15 52 -25 72 -25 23 0 45 -9 66 -26 19 -17 47 -28 75 -32 25
  -2 51 -13 61 -23 9 -11 27 -19 40 -19 27 0 74 -20 74 -31 0 -4 24 -15 54 -23
  29 -9 59 -22 65 -30 6 -8 27 -17 45 -20 18 -4 41 -15 51 -26 9 -10 30 -22 45
  -25 15 -3 42 -17 61 -31 18 -13 41 -24 50 -24 10 0 31 -11 47 -25 17 -14 37
  -25 46 -25 8 0 31 -13 49 -30 19 -16 43 -30 54 -30 11 0 30 -11 43 -25 13 -14
  51 -39 85 -55 34 -17 70 -39 79 -49 10 -11 46 -34 82 -52 36 -18 71 -44 80
  -57 8 -12 25 -27 37 -33 59 -27 136 -80 181 -125 17 -18 51 -44 76 -59 50 -31
  138 -104 215 -180 28 -27 73 -68 100 -90 81 -65 292 -274 368 -365 40 -47 83
  -96 97 -110 77 -79 151 -167 192 -231 9 -14 42 -55 73 -90 30 -35 69 -89 86
  -119 17 -30 45 -69 61 -87 31 -32 47 -56 72 -108 8 -17 24 -39 35 -49 11 -10
  25 -34 31 -52 7 -19 16 -34 21 -34 5 0 20 -22 34 -49 13 -27 29 -52 35 -56 5
  -3 10 -14 10 -23 0 -9 14 -33 30 -52 16 -19 30 -42 30 -51 0 -9 8 -27 18 -40
  17 -23 36 -56 76 -139 10 -19 22 -42 27 -50 5 -8 16 -31 24 -50 8 -19 26 -56
  40 -81 14 -26 25 -54 25 -64 0 -9 11 -31 25 -49 14 -18 25 -43 25 -55 0 -12
  11 -41 24 -64 13 -23 27 -58 31 -77 4 -19 16 -46 26 -59 11 -13 19 -36 19 -51
  0 -15 11 -48 25 -74 14 -26 28 -66 32 -89 3 -23 17 -62 30 -86 12 -24 23 -58
  23 -75 0 -17 12 -53 25 -81 15 -29 25 -68 25 -93 0 -24 9 -61 22 -85 12 -24
  24 -72 28 -112 4 -38 17 -96 29 -128 12 -31 24 -92 27 -135 4 -42 15 -104 26
  -137 14 -42 21 -95 23 -175 2 -63 8 -133 14 -155 47 -178 51 -984 6 -1190 -8
  -38 -17 -119 -20 -180 -2 -60 -12 -135 -20 -165 -9 -30 -20 -89 -25 -131 -11
  -93 -28 -169 -41 -185 -5 -6 -14 -48 -19 -92 -5 -45 -18 -101 -29 -126 -12
  -25 -21 -59 -21 -76 0 -17 -11 -57 -25 -90 -14 -33 -25 -70 -25 -82 0 -13 -11
  -46 -24 -74 -13 -28 -27 -71 -31 -95 -4 -24 -14 -53 -21 -65 -8 -11 -21 -47
  -29 -79 -9 -31 -24 -69 -35 -82 -11 -14 -20 -37 -20 -52 0 -14 -11 -44 -25
  -66 -13 -22 -25 -50 -25 -61 0 -12 -8 -32 -18 -45 -11 -13 -26 -45 -35 -72 -9
  -26 -25 -59 -36 -74 -12 -14 -21 -36 -21 -49 0 -12 -11 -35 -23 -51 -13 -15
  -27 -41 -31 -57 -4 -16 -18 -43 -32 -59 -13 -16 -24 -36 -24 -45 0 -9 -9 -28
  -20 -42 -11 -14 -20 -30 -20 -37 0 -6 -11 -25 -25 -41 -14 -17 -25 -37 -25
  -46 0 -8 -13 -31 -30 -49 -16 -19 -30 -42 -30 -50 0 -9 -11 -29 -25 -45 -14
  -17 -25 -34 -25 -39 0 -5 -11 -20 -25 -33 -14 -13 -25 -31 -25 -41 0 -10 -18
  -39 -40 -64 -22 -26 -48 -61 -57 -78 -17 -33 -37 -62 -88 -125 -16 -20 -36
  -46 -43 -57 -37 -58 -55 -83 -105 -139 -30 -33 -91 -107 -135 -162 -130 -162
  -550 -581 -643 -639 -19 -13 -54 -40 -77 -61 -81 -74 -150 -129 -212 -168 -25
  -16 -56 -39 -70 -52 -30 -29 -97 -74 -110 -74 -5 0 -25 -15 -44 -33 -39 -37
  -101 -77 -120 -77 -7 0 -25 -13 -41 -30 -16 -16 -36 -30 -46 -30 -9 0 -22 -7
  -30 -16 -7 -8 -34 -26 -60 -40 -26 -13 -50 -28 -54 -34 -3 -5 -17 -10 -30 -10
  -13 0 -30 -9 -38 -20 -8 -11 -29 -25 -48 -32 -19 -6 -47 -22 -62 -35 -16 -12
  -38 -23 -49 -23 -11 0 -33 -11 -50 -25 -16 -14 -39 -25 -50 -25 -11 0 -33 -11
  -50 -25 -16 -14 -42 -25 -57 -25 -16 0 -37 -10 -51 -25 -15 -16 -34 -25 -53
  -25 -16 0 -42 -11 -59 -25 -19 -16 -42 -25 -63 -25 -21 0 -45 -9 -64 -26 -18
  -15 -49 -28 -73 -32 -23 -3 -56 -15 -74 -27 -18 -12 -51 -25 -74 -29 -23 -4
  -59 -15 -79 -25 -20 -11 -55 -23 -76 -27 -22 -4 -62 -18 -90 -30 -27 -13 -67
  -24 -88 -24 -21 0 -59 -11 -85 -25 -34 -18 -56 -23 -78 -19 -22 4 -52 -3 -99
  -21 -37 -15 -101 -31 -143 -35 -43 -4 -97 -15 -122 -25 -25 -9 -94 -21 -155
  -25 -60 -5 -131 -15 -156 -23 -65 -21 -144 -30 -294 -32 -71 -1 -211 -7 -310
  -13 -133 -9 -225 -9 -355 0 -96 6 -240 12 -320 14 -104 2 -173 9 -245 25 -55
  12 -127 23 -160 24 -79 3 -137 13 -204 36 -30 11 -77 19 -105 19 -31 0 -75 10
  -111 25 -33 14 -80 25 -105 25 -30 0 -66 10 -105 30 -38 19 -76 30 -105 30
  -29 0 -59 9 -85 25 -24 15 -55 25 -78 25 -24 0 -52 9 -75 25 -22 15 -51 25
  -73 25 -22 0 -51 10 -74 26 -21 14 -57 29 -82 33 -24 5 -51 15 -61 23 -9 9
  -35 18 -56 21 -21 4 -48 15 -60 25 -12 11 -36 22 -54 26 -19 3 -47 17 -64 31
  -16 14 -42 25 -57 25 -16 0 -44 13 -66 30 -21 17 -48 30 -60 30 -12 0 -32 11
  -45 25 -14 14 -35 25 -50 25 -15 0 -36 11 -50 25 -13 14 -29 25 -36 25 -18 0
  -125 58 -150 81 -11 10 -28 19 -37 19 -10 0 -31 13 -49 30 -17 17 -37 30 -44
  30 -18 0 -82 41 -114 72 -16 16 -36 28 -45 28 -20 0 -64 31 -109 78 -18 17
  -38 32 -44 32 -20 0 -155 101 -185 137 -10 13 -23 23 -28 23 -17 0 -111 74
  -179 140 -36 35 -103 96 -150 135 -110 92 -300 282 -388 387 -39 46 -101 116
  -140 156 -38 40 -87 99 -108 130 -21 31 -61 84 -89 117 -63 76 -90 114 -99
  143 -4 12 -11 22 -15 22 -12 0 -91 108 -91 124 0 7 -9 21 -21 32 -19 17 -38
  46 -78 123 -7 14 -24 37 -36 51 -13 14 -26 35 -30 48 -6 22 -19 44 -72 128
  -13 19 -23 41 -23 49 0 7 -11 26 -24 42 -13 15 -28 44 -35 64 -6 20 -21 45
  -31 54 -11 10 -20 28 -20 41 0 12 -11 36 -25 52 -14 17 -25 41 -25 55 0 14
  -11 38 -24 54 -13 15 -26 44 -30 64 -3 20 -15 47 -26 59 -11 12 -22 39 -26 59
  -3 20 -16 52 -29 71 -12 19 -25 52 -28 72 -3 20 -14 49 -25 65 -11 15 -23 48
  -26 73 -4 24 -15 58 -26 75 -11 17 -22 51 -26 75 -4 25 -15 59 -26 77 -10 17
  -21 56 -25 85 -3 29 -14 70 -24 90 -11 20 -23 69 -29 108 -6 39 -17 84 -25
  101 -9 16 -20 66 -26 111 -5 45 -14 88 -18 97 -13 25 -33 127 -42 221 -5 47
  -15 114 -24 150 -9 38 -17 128 -19 215 -2 83 -10 197 -16 255 -17 140 -16 480
  1 605 7 55 13 132 12 170 -2 91 14 264 32 340 8 33 19 105 25 161 6 55 19 124
  30 152 11 29 20 76 20 105 0 32 10 75 25 112 14 33 25 78 25 100 0 22 11 64
  25 93 14 30 25 66 25 80 0 38 20 104 36 123 8 8 17 38 20 65 3 27 15 62 26 78
  11 15 23 47 25 70 3 22 14 55 23 71 10 17 24 50 30 75 7 24 20 55 31 68 10 14
  19 37 19 52 0 15 11 44 25 64 14 20 25 45 25 55 0 10 11 34 24 54 13 19 27 49
  31 65 3 17 14 41 24 54 10 13 23 40 30 60 7 20 18 41 25 47 7 6 19 29 26 51 7
  22 21 47 32 56 10 10 18 25 18 35 0 10 11 31 25 47 14 17 25 37 25 45 0 9 11
  28 24 44 13 15 32 44 42 64 42 82 63 116 78 128 9 8 16 19 16 26 0 6 25 45 55
  86 30 41 55 78 55 82 0 9 25 44 76 105 17 20 41 55 54 77 13 22 55 75 92 118
  38 43 80 96 93 117 33 53 225 258 457 490 260 259 1403 1383 1593 1565 403
  388 645 624 975 951 496 491 1095 1082 1265 1247 104 101 135 126 155 124 l25
  -2 2 -1130z" />
                                </g>
                            </svg>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
<?php
        }
    }
}
