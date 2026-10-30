<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;


if (!class_exists('Irantheme_Section_Service')) {

    /**
     * Create custom fields for post types service
     *
     * @package Wordpress
     * @subpackage irantheme
     * @since irantheme - custom fields
     * @author iranianclever
     * @see v1.0
     *
     */

    class Irantheme_Section_Service
    {
        /**
         * Add mask service for background image
         * @return void
         * @param string,string,string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function service_mask($service_base_color, $service_bg_checkbox, $service_bg)
        {
            if ($service_base_color == 'white' && $service_bg_checkbox == 'on' && $service_bg) {
?>
                <div class="irantheme-mask-white"></div>
            <?php
            } else if ($service_base_color == 'dark' && $service_bg_checkbox == 'on' && $service_bg) {
            ?>
                <div class="irantheme-mask-dark"></div>
            <?php
            }
        }


        /**
         * Return appropriate bootstrap class
         * @return string
         * @param string
         * @param int
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_bootstrap_class($service_posts_count, $col_mobile = 6)
        {
            // Calculation bootstrap column with post count columns options
            $bootstrap_column_calculation = 'col';
            switch ($service_posts_count) {
                case 'col-1':
                    $bootstrap_column_calculation = 'col-12 col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12';
                    break;
                case 'col-2':
                    $bootstrap_column_calculation = 'col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-6' . ' col-' . $col_mobile;
                    break;
                case 'col-3':
                    $bootstrap_column_calculation = 'col-xxl-4 col-xl-4 col-lg-4 col-md-6 col-sm-6' . ' col-' . $col_mobile;
                    break;
                case 'col-4':
                    $bootstrap_column_calculation = 'col-xxl-3 col-xl-3 col-lg-3 col-md-4 col-sm-6' . ' col-' . $col_mobile;
                    break;
                case 'col-6':
                    $bootstrap_column_calculation = 'col-xxl-2 col-xl-3 col-lg-3 col-md-4 col-sm-6' . ' col-' . $col_mobile;
                    break;
                default:
                    $bootstrap_column_calculation = 'col';
                    break;
            }
            return $bootstrap_column_calculation;
        }


        /**
         * Return appropriate slider class
         * @return string
         * @param string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_slider_data_column($service_posts_count)
        {
            // Calculation slider column with post count columns options
            $slider_column_calculation = sprintf('data-columns="%s"', 4);
            switch ($service_posts_count) {
                case 'col-1':
                    $slider_column_calculation = sprintf('data-columns="%s"', 1);
                    break;
                case 'col-2':
                    $slider_column_calculation = sprintf('data-columns="%s"', 2);
                    break;
                case 'col-3':
                    $slider_column_calculation = sprintf('data-columns="%s"', 3);
                    break;
                case 'col-4':
                    $slider_column_calculation = sprintf('data-columns="%s"', 4);
                    break;
                case 'col-5':
                    $slider_column_calculation = sprintf('data-columns="%s"', 5);
                    break;
                case 'col-6':
                    $slider_column_calculation = sprintf('data-columns="%s"', 6);
                    break;
                case 'col-7':
                    $slider_column_calculation = sprintf('data-columns="%s"', 7);
                    break;
                case 'col-8':
                    $slider_column_calculation = sprintf('data-columns="%s"', 8);
                    break;
                case 'col-10':
                    $slider_column_calculation = sprintf('data-columns="%s"', 10);
                    break;
                case 'col-12':
                    $slider_column_calculation = sprintf('data-columns="%s"', 12);
                    break;
                case 'col-13':
                    $slider_column_calculation = sprintf('data-columns="%s"', 13);
                    break;
                default:
                    $slider_column_calculation = sprintf('data-columns="%s"', 4);
                    break;
            }
            return $slider_column_calculation;
        }


        /**
         * Return bootstrap raw class by col count
         * @return void
         * @param string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_bootstrap_raw_class($count_columns)
        {
            if ($count_columns == 'col-1') {
                return 'col-xxl-1 col-xl-1 col-lg-3 col-md-4 col-sm-6 col-12';
            } else if ($count_columns == 'col-2') {
                return 'col-xxl-2 col-xl-2 col-lg-3 col-md-4 col-sm-6 col-12';
            } else if ($count_columns == 'col-3') {
                return 'col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12';
            } elseif ($count_columns == 'col-4') {
                return 'col-xxl-4 col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12';
            } elseif ($count_columns == 'col-5') {
                return 'col-xxl-5 col-xl-5 col-lg-6 col-md-6 col-sm-6 col-12';
            } elseif ($count_columns == 'col-6') {
                return 'col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12';
            } elseif ($count_columns == 'col-7') {
                return 'col-xxl-7 col-xl-7 col-lg-7 col-md-7 col-sm-7 col-12';
            } elseif ($count_columns == 'col-8') {
                return 'col-xxl-8 col-xl-8 col-lg-8 col-md-8 col-sm-8 col-12';
            } elseif ($count_columns == 'col-9') {
                return 'col-xxl-9 col-xl-9 col-lg-9 col-md-9 col-sm-9 col-12';
            } elseif ($count_columns == 'col-10') {
                return 'col-xxl-10 col-xl-10 col-lg-10 col-md-10 col-sm-10 col-12';
            } elseif ($count_columns == 'col-11') {
                return 'col-xxl-11 col-xl-11 col-lg-11 col-md-11 col-sm-11 col-12';
            } elseif ($count_columns == 'col-12') {
                return 'col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12';
            }
        }


        /**
         * Return percentage width of column
         * @return int
         * @param string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_column_width_percentage($column_type)
        {
            $result = 0;
            // Calculation slider column with post count columns options
            switch ($column_type) {
                case 'col-1':
                    $result = number_format(100 / 1, 6);
                    break;
                case 'col-2':
                    $result = number_format(100 / 2, 6);
                    break;
                case 'col-3':
                    $result = number_format(100 / 3, 6);
                    break;
                case 'col-4':
                    $result = number_format(100 / 4, 6);
                    break;
                case 'col-6':
                    $result = number_format(100 / 6, 6);
                    break;
                case 'col-8':
                    $result = number_format(100 / 8, 6);
                    break;
                case 'col-10':
                    $result = number_format(100 / 10, 6);
                    break;
                case 'col-12':
                    $result = number_format(100 / 12, 6);
                    break;
                default:
                    $result = number_format(100 / 4, 6);
                    break;
            }
            return $result;
        }


        /**
         * Return alignment text
         * @return string
         * @param string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_alignment_class($service_alignment)
        {
            // Set alignment
            $service_alignment_class = '';
            // Check alignment position
            if ($service_alignment == 'center') {
                // Set alignment class center
                $service_alignment_class = 'irantheme-text-center';
            } else if ($service_alignment == 'right') {
                // Set alignment class right
                $service_alignment_class = 'irantheme-text-right';
            } else if ($service_alignment == 'left') {
                // Set alignment class left
                $service_alignment_class = 'irantheme-text-left';
            }
            return $service_alignment_class;
        }


        /**
         * Return flex alignment content
         * @return string
         * @param string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_flex_alignment_class($service_alignment)
        {
            // Set alignment
            $service_alignment_class = '';
            // Check alignment position
            if ($service_alignment == 'center') {
                // Set alignment class center
                $service_alignment_class = 'justify-content-center';
            } else if ($service_alignment == 'right') {
                // Set alignment class right
                $service_alignment_class = 'justify-content-start';
            } else if ($service_alignment == 'left') {
                // Set alignment class left
                $service_alignment_class = 'justify-content-end';
            }
            return $service_alignment_class;
        }


        /**
         * Return flex column alignment content
         * @return string
         * @param string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_flex_column_alignment_class($service_alignment)
        {
            // Set alignment
            $service_alignment_class = '';
            // Check alignment position
            if ($service_alignment == 'center') {
                // Set alignment class center
                $service_alignment_class = 'align-items-center';
            } else if ($service_alignment == 'right') {
                // Set alignment class right
                $service_alignment_class = 'align-items-start';
            } else if ($service_alignment == 'left') {
                // Set alignment class left
                $service_alignment_class = 'align-items-end';
            }
            return $service_alignment_class;
        }


        /**
         * Return custom padding of top and bottom in css format
         * @param array $fields_values
         * @return string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_custom_padding_css($fields_values)
        {
            // Custom padding --------------------------------------------------------------
            $service_custom_padding = $fields_values['custom_height_checkbox'];
            $service_custom_padding_css = '';
            if ($service_custom_padding == 'on') {
                // Get padding top option
                $service_custom_padding_top = $fields_values['top_height_range'];
                // Get padding bottom option
                $service_custom_padding_bottom = $fields_values['bottom_height_range'];
                // Combination paddings with css
                $service_custom_padding_css .= 'padding-top:' . $service_custom_padding_top . 'rem;padding-bottom:' . $service_custom_padding_bottom . 'rem;';
            }
            return $service_custom_padding_css;
        }

        /**
         * Return custom outer padding of top and bottom in css format
         * @param array $fields_values
         * @return string
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        public static function get_custom_outer_padding_css($fields_values)
        {
            // Custom padding --------------------------------------------------------------
            $custom_padding_checkbox = $fields_values['custom_outer_padding_checkbox'];
            $custom_padding_css = '';
            if ($custom_padding_checkbox == 'on') {
                // Get padding top option
                $custom_padding_top = $fields_values['outer_padding_top_range'];
                // Get padding bottom option
                $custom_padding_bottom = $fields_values['outer_padding_bottom_range'];
                // Get padding bottom option
                $custom_padding_left = $fields_values['outer_padding_left_range'];
                // Get padding bottom option
                $custom_padding_right = $fields_values['outer_padding_right_range'];
                // Combination paddings with css
                $custom_padding_css .= 'padding-top:' . $custom_padding_top . 'rem;padding-bottom:' . $custom_padding_bottom . 'rem;padding-left:' . $custom_padding_left . 'rem;padding-right:' . $custom_padding_right . 'rem;';
            }
            return $custom_padding_css;
        }

        /**
         * Return custom inner padding of top and bottom in css format
         * @param array $fields_values
         * @return string
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        public static function get_custom_inner_padding_css($fields_values)
        {
            // Custom padding --------------------------------------------------------------
            $custom_padding_checkbox = $fields_values['custom_inner_padding_checkbox'];
            $custom_padding_css = '';
            if ($custom_padding_checkbox == 'on') {
                // Get padding top option
                $custom_padding_top = $fields_values['inner_padding_top_range'];
                // Get padding bottom option
                $custom_padding_bottom = $fields_values['inner_padding_bottom_range'];
                // Get padding bottom option
                $custom_padding_left = $fields_values['inner_padding_left_range'];
                // Get padding bottom option
                $custom_padding_right = $fields_values['inner_padding_right_range'];
                // Combination paddings with css
                $custom_padding_css .= 'padding-top:' . $custom_padding_top . 'rem;padding-bottom:' . $custom_padding_bottom . 'rem;padding-left:' . $custom_padding_left . 'rem;padding-right:' . $custom_padding_right . 'rem;';
            }
            return $custom_padding_css;
        }

        /**
         * Return fullscreen height class (custom inner padding)
         * @param array $fields_values
         * @return string
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        public static function get_fullscreen_height_class($fields_values)
        {
            // fullscreen height --------------------------------------------------------------
            $custom_padding_checkbox = $fields_values['custom_inner_padding_checkbox'];
            $fullscreen_height_checkbox = $fields_values['fullscreen_height_checkbox'];
            $fullscreen_height_class = '';
            if ($fullscreen_height_checkbox == 'on' && $custom_padding_checkbox == 'off') {
                // Class
                $fullscreen_height_class = 'irantheme-hero-fullscreen';
            }
            return $fullscreen_height_class;
        }


        /**
         * Get all posts of simple service master
         * @return void
         * @param string,string,string,string,string,string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_posts_simple_service_master($data_page, $service_posts_master, $service_posts_count, $aos_delay, $data_load_attr, $data_load_duration, $title_colors_css, $alignment_mode, $have_description = true, $have_description_link = true, $icon_space = 2)
        {
            // Service custom fields --------------------------------------------------------------
            foreach ($service_posts_master as $object_post) {
                // Get title options
                $service_title = $object_post['fields']['service_title'];
                // Get description option
                $service_desc = $object_post['fields']['service_desc'];
                // Get icon option
                $service_icon = $object_post['fields']['service_icon'];
                // Get button title option
                $service_btn_title = $object_post['fields']['service_btn_title'];
                // Get button link option
                $service_btn_link = $object_post['fields']['service_btn_link'];
                // Alignment --------------------------------------------------------------
                $alignment_class = '';
                $service_item_icon_css = '';
                $service_item_wrapper_css = '';
                if ($alignment_mode != 'center') {
                    $alignment_class = 'irantheme-service-item-icon-side';
                    if ($alignment_mode == 'right') {
                        $service_item_wrapper_css = 'flex-direction: row;';
                        $service_item_icon_css = 'padding-left:' . $icon_space . 'rem;';
                    } elseif ($alignment_mode == 'left') {
                        $service_item_wrapper_css = 'flex-direction: row-reverse;';
                        $service_item_icon_css = 'padding-right:' . $icon_space . 'rem;';
                    }
                }
            ?>
                <!-- Service item -->
                <div class="irantheme-object <?php echo esc_attr(Irantheme_Section_Service::get_bootstrap_class($service_posts_count, 12)); ?>" style="margin-bottom: 0;" data-object="<?php echo esc_attr($object_post['object_id']); ?>">
                    <div class="irantheme-service-item" style="overflow:unset;" <?php echo ($data_load_attr); ?> data-aos-delay="<?php echo ($aos_delay); ?>" data-aos-duration="<?php echo ($data_load_duration); ?>">
                        <!-- Wrapper -->
                        <div class="irantheme-service-item-wrapper <?php echo esc_attr(Irantheme_Section_Service::get_alignment_class($alignment_mode)); ?>">
                            <div class="irantheme-service-item-wrapper-content <?php echo esc_attr($alignment_class); ?>" style="<?php echo esc_attr($service_item_wrapper_css); ?>">
                                <!-- Icon -->
                                <?php if ($service_icon) : ?>
                                    <div class="irantheme-service-item-icon" style="<?php echo esc_attr($service_item_icon_css); ?><?php echo (!$service_btn_title && !$service_btn_link && $have_description == true && !$service_desc) ? 'display:flex;margin-bottom:unset;' : ''; ?>">
                                        <i class="<?php echo esc_attr($service_icon); ?>" style="font-size: 1.8rem;<?php echo ($title_colors_css); ?>"></i>
                                    </div>
                                <?php endif; ?>
                                <!-- Content -->
                                <div class="irantheme-service-item-content <?php echo (!$service_btn_title && !$service_btn_link && $have_description == true && !$service_desc) ? 'justify-content-center' : ''; ?>">
                                    <!-- Title -->
                                    <?php if ($service_title) : ?>
                                        <h3 style="font-size: 0.9rem;margin-bottom:0;font-weight:bold;line-height:normal;"><?php echo esc_html($service_title); ?></h3>
                                    <?php endif; ?>
                                    <!-- Description -->
                                    <?php if ($have_description == true && $service_desc) : ?>
                                        <p style="font-size: 0.8rem;margin-bottom:0.5rem;"><?php echo esc_html($service_desc); ?></p>
                                    <?php endif; ?>
                                    <!-- Button -->
                                    <?php if ($service_btn_title || $service_btn_link && $have_description_link == true) : ?>
                                        <div class="irantheme-service-item-content-button">
                                            <a href="<?php echo esc_attr($service_btn_link ? $service_btn_link : get_home_url()); ?>" class="irantheme-btn-void" style="font-size:0.7rem;"><?php echo esc_html($service_btn_title ? $service_btn_title : 'جزئیات بیشتر'); ?></a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    Irantheme_Page_Builder::get_hidden_require_object_buttons($data_page);
                    ?>
                </div>
            <?php
            }
        }


        /**
         * Display empty thumbnail elements
         * @return void
         * @param void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function empty_thumbnail($base_color, $heading = 'تصویری جهت نمایش بارگزاری نشده', $description = 'جهت بارگزاری تصویر به قسمت "تنظیمات استورپلاس -> چیدمان عناصر" مراجعه نمایید.', $icon_size = 5)
        {

            ?>
            <!-- Empty thumbnail -->
            <div class="irantheme-empty-thumbnail <?php echo esc_attr($base_color); ?>">
                <i class="fa-light fa-image-slash irantheme-empty-thumbnail-icon" style="font-size:<?php echo esc_attr($icon_size); ?>rem;"></i>
                <h5 class="irantheme-empty-thumbnail-heading"><?php _e($heading); ?></h5>
                <p class="irantheme-empty-thumbnail-description"><?php _e($description); ?></p>
            </div>
<?php
        }
    } // End Class
} // End if class exists statement
