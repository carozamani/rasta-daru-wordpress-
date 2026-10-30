<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;


if (!class_exists('Irantheme_Custom_Fields_Contact')) {

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

    class Irantheme_Custom_Fields_Contact extends Irantheme_Custom_Fields
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
            // Register like post type
            register_post_type('contact', array(
                'supports' => array('title'),
                'public' => false,
                'show_ui' => true,
                'labels' => array(
                    'name' => 'تماس ها',
                    'add_new_item' => 'فرم جدید',
                    'edit_item' => 'ویرایش فرم',
                    'all_items' => 'همه فرم ها',
                    'singular_name' => 'فرم',
                    'not_found'             => __('هیچ تماسی یافت نشد!', self::$textdomain)
                ),
                'capabilities' => array(
                    'create_posts' => false,
                    // 'edit_posts' => true
                ),
                'map_meta_cap' => true,
                'menu_position' => __(5, self::$textdomain),
                'menu_icon' => 'dashicons-id',
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
                    'id'             => 'irantheme_custom_field_contact_name',
                    'label'            => __(
                        'نام فرستنده',
                        self::$textdomain

                    ),
                    'description'    => __('نام فرستنده پیام', self::$textdomain),
                    'type'            => 'text',
                    'default'        => '',
                    'placeholder'    => __('مقداری در این فیلد وارد نشده.', self::$textdomain),
                ),
                array(
                    'id'             => 'irantheme_custom_field_contact_email',
                    'label'            => __(
                        'ایمیل فرستنده',
                        self::$textdomain

                    ),
                    'description'    => __('ایمیل فرستنده پیام', self::$textdomain),
                    'type'            => 'text',
                    'default'        => '',
                    'placeholder'    => __('مقداری در این فیلد وارد نشده.', self::$textdomain),
                ),
                array(
                    'id'             => 'irantheme_custom_field_contact_website',
                    'label'            => __(
                        'وب سایت فرستنده',
                        self::$textdomain

                    ),
                    'description'    => __('وب سایت فرستنده پیام', self::$textdomain),
                    'type'            => 'text',
                    'default'        => '',
                    'placeholder'    => __('مقداری در این فیلد وارد نشده.', self::$textdomain),
                ),
                array(
                    'id'             => 'irantheme_custom_field_contact_subject',
                    'label'            => __(
                        'موضوع پیام',
                        self::$textdomain

                    ),
                    'description'    => __('موضوع پیام ارسالی', self::$textdomain),
                    'type'            => 'text',
                    'default'        => '',
                    'placeholder'    => __('مقداری در این فیلد وارد نشده.', self::$textdomain),
                ),
                array(
                    'id'             => 'irantheme_custom_field_contact_desc',
                    'label'            => __(
                        'توضیحات پیام',
                        self::$textdomain
                    ),
                    'description'    => __('توضیحات تکمیلی پیام ارسالی توسط فرستنده', self::$textdomain),
                    'type'            => 'textarea',
                    'default'        => '',
                    'placeholder'    => __('مقداری در این فیلد وارد نشده.', self::$textdomain),
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


    $irantheme_custom_fields_object = new Irantheme_Custom_Fields_Contact('contact', 'نویسنده');
} // End if class exists statement
