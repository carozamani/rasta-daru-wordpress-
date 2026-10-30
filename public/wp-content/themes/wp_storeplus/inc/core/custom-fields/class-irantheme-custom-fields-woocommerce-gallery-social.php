<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;


if (!class_exists('Irantheme_Custom_Fields_Woocommerce_Gallery_Social')) {

    /**
     * Create custom fields for post types author
     *
     * @package Wordpress
     * @subpackage irantheme
     * @since irantheme - custom fields
     * @author iranianclever
     * @see v1.0
     *
     */

    class Irantheme_Custom_Fields_Woocommerce_Gallery_Social extends Irantheme_Custom_Fields
    {
        /**
         * Constructor
         * @return void
         * @param string,string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        function __construct($name, $title)
        {
            parent::__construct($name, $title);
            add_action('init', array($this, 'register_post_type'));
        }


        /**
         * Register post type of this class
         * @return array
         * @param void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        function register_post_type()
        {
            // Register comment post type
            register_post_type('gallery_comment', array(
                'supports' => array('title', 'editor'),
                'public' => false,
                'show_ui' => true,
                'labels' => array(
                    'name' => 'نظرات استوری',
                    'add_new_item' => 'نظر جدید',
                    'edit_item' => 'ویرایش نظر',
                    'all_items' => 'همه نظرات',
                    'singular_name' => 'نظر'
                ),
                'capabilities' => array(
                    'create_posts' => false,
                    // 'edit_posts' => true
                ),
                'map_meta_cap' => true,
                'menu_icon' => 'dashicons-format-chat'
            ));
        }


        /**
         * Return custom fields (Fields)
         * @return array
         * @param void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        function get_fields()
        {
            // Custom fields
            $custom_fields = array(
                array(
                    'id' => 'irantheme_woocommerce_gallery_social_meta_value_key',
                    'label' => __('شناسه ارتباطی', self::$textdomain),
                    'description' => __('شناسه محصول یا پست ارتباطی که نظر برای آن ثبت شده', self::$textdomain),
                    'type' => 'text',
                    'default' => '',
                    'placeholder' => __('مقداری در این فیلد وارد نشده.', self::$textdomain),
                ),
                array(
                    'id' => 'irantheme_woocommerce_gallery_social_meta_value_user_id',
                    'label' => __('شناسه کاربر', self::$textdomain),
                    'description' => __('شناسه کاربری که نظر داده است', self::$textdomain),
                    'type' => 'text',
                    'default' => '',
                    'placeholder' => __('مقداری در این فیلد وارد نشده.', self::$textdomain),
                ),
                array(
                    'id' => 'irantheme_woocommerce_gallery_social_meta_value_comment',
                    'label' => __('نظر کاربر', self::$textdomain),
                    'description' => __('محتوا نظر کاربر که ارسال شده', self::$textdomain),
                    'type' => 'textarea',
                    'default' => '',
                    'placeholder' => __('مقداری در این فیلد وارد نشده.', self::$textdomain),
                ),
                array(
                    'id' => 'irantheme_woocommerce_gallery_social_meta_value_parent_comment',
                    'label' => __('نظر والد', self::$textdomain),
                    'description' => __('شناسه نظر والد در صورت وجود (برای پاسخ به نظرات)', self::$textdomain),
                    'type' => 'text',
                    'default' => '',
                    'placeholder' => __('مقداری در این فیلد وارد نشده.', self::$textdomain),
                ),
            );
            return $custom_fields;
        }


        /**
         * Check screen type
         */
        function is_screen_action()
        {
            // parent::is_screen_action();
        }


        /**
         * Remove the default Custom Fields meta box
         */
        function remove_default_custom_fields($type, $context, $post)
        {
            parent::remove_default_custom_fields($type, $context, $post);
        }


        /**
         * Create the new Custom Fields meta box
         */
        function create_custom_fields()
        {
            parent::create_custom_fields();
        }


        /**
         * Display the new Custom Fields meta box
         */
        function display_custom_fields()
        {
            parent::display_custom_fields();
        }


        /**
         * Save the new Custom Fields values
         */
        function save_custom_fields($post_id, $post)
        {
            parent::save_custom_fields($post_id, $post);
        }
    } // End Class


    $irantheme_custom_fields_object = new Irantheme_Custom_Fields_Woocommerce_Gallery_Social('woocommerce_gallery_social', 'استوری اجتماعی ووکامرس');
} // End if class exists statement
