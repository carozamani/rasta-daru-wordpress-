<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;


if (!class_exists('Irantheme_Section_Showcase')) {

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

    class Irantheme_Section_Showcase
    {
        /**
         * Display button trigger (Go to down)
         * @return void
         * @param void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function showcase_button_trigger($button_trigger_checkbox, $edge_checkbox = null)
        {
            if ($button_trigger_checkbox == 'on') :
?>
                <!-- Button scroll down -->
                <div class="irantheme-showcase-button-scroll-down" style="<?php echo $edge_checkbox == 'on' ? 'bottom:8%' : ''; ?>">
                    <button class="irantheme-btn-void irantheme-showcase-button-scroll-down-trigger"></button>
                </div>
            <?php
            endif;
        }


        /**
         * Display social media
         * @return void
         * @param void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function showcase_social_media($social_status_checkbox, $edge_checkbox = null, $edge_alignment_radio = null)
        {
            if ($social_status_checkbox == 'on') :
            ?>
                <!-- Social media -->
                <div class="irantheme-showcase-social-media" style="<?php echo $edge_checkbox == 'on' && $edge_alignment_radio == 'left' ? 'bottom:8%' : ''; ?>">
                    <?php
                    // Get social media
                    Irantheme_Common_Static::get_social_media();
                    ?>
                </div>
            <?php
            endif;
        }


        /**
         * Display small custom text link to showcase edge
         * @return void
         * @param void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function showcase_custom_text_link($custom_text_link_checkbox, $custom_text_link_heading, $custom_text_link_url, $edge_checkbox = null, $edge_alignment_radio = null)
        {
            if ($custom_text_link_checkbox == 'on') :
            ?>
                <!-- Custom text link -->
                <div class="irantheme-showcase-custom-text-link" style="<?php echo ($edge_checkbox == 'on' && $edge_alignment_radio == 'right') ? 'bottom:8%' : '' ?>">
                    <a href="<?php echo esc_url($custom_text_link_url); ?>" class="irantheme-showcase-custom-text-link-item"><?php echo esc_html($custom_text_link_heading); ?></a>
                </div>
<?php
            endif;
        }
    } // End Class
} // End if class exists statement
