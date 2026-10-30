<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;


if (!class_exists('Irantheme_Custom_Fields_Product')) {

    /**
     * Create custom fields for post types author
     *
     * @package Wordpress
     * @subpackage irantheme
     * @since irantheme - custom fields
     * @author M-MALEKZADEH77
     * @see v1.0
     *
     */

    class Irantheme_Custom_Fields_Product extends Irantheme_Custom_Fields
    {
        /**
         * Constructor
         * @return void
         * @param string,string
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        function __construct($name, $title, $context, $priority)
        {
            parent::__construct($name, $title, $context, $priority);
        }


        /**
         * Return custom fields (Fields)
         * @return array
         * @param void
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see v1.0
         */
        function get_fields()
        {
            // Custom fields
            $custom_fields = array(
                array(
                    'id'             => 'irantheme_custom_field_product_new_badge_checkbox',
                    'label'            => __(
                        'برچسب محصول جدید',
                        self::$textdomain
                    ),
                    'description'    => __('در صورت فعال کردن این گزینه یک برچسب با عنوان "جدید" روی تصویر محصول نمایش داده می شود.', self::$textdomain),
                    'type'            => 'checkbox',
                    'default'        => 'off',
                    'id_dependents_on' => array('irantheme_custom_field_product_new_badge_date'),
                    'id_dependents_off' => array(),
                    'base_priority' => 'checkbox_base',
                    'selector_priority' => 'checkbox_new_badge_date',
                    'z_index' => 0
                ),
                array(
                    'id'             => 'irantheme_custom_field_product_new_badge_date',
                    'label'            => __(
                        'تاریخ پایان وضعیت "محصول جدید"',
                        self::$textdomain

                    ),
                    'description'    => __('درصورت تمایل می توانید تاریخ پایان وضعیت "محصول جدید" را برای این کالا مشخص کنید.', self::$textdomain),
                    'note'    => __('در صورت تعیین نکردن تاریخ پایان، این برچسب تا زمان خاموش کردن بصورت دستی نمایش داده خواهد شد.', self::$textdomain),
                    'type'            => 'date',
                    'default'        => '',
                    'placeholder'    => __('', self::$textdomain),
                ),
                array(
                    'id'             => 'irantheme_custom_field_product_detail_badge',
                    'label'            => __(
                        'نشان های محصول',
                        self::$textdomain
                    ),
                    'description'    => __('با استفاده از این گزینه می توانید از لیست نشان ها، نشان های دلخواه خود را برای این محصول انتخاب کنید.', self::$textdomain),
                    'type'            => 'select_multi',
                    'color_preview' => true,
                    'options'        => array('has_original' => 'کالای اصل', 'has_unoriginal' => 'کالای غیراصل', 'has_warranty' => 'دارای گارانتی', 'has_installments' => 'قابل خرید بصورت اقساطی'),
                    'default'        => '',
                ),
                array(
                    'id'             => 'irantheme_custom_field_product_attr',
                    'label'            => __(
                        'خلاصه ویژگی ها محصول',
                        self::$textdomain
                    ),
                    'description'    => __('درصورت نیاز می توانید تعداد 6 ویژگی را برای نمایش در بخش خلاصه ویژگی ها در قسمت معرفی محصول انتخاب کنید.این قابلیت در محصولات دانلودی کار نخواهد کرد.', self::$textdomain),
                    'note'    => __('توجه داشته باشید که ویژگی های انتخاب شده در این فیلد باید حتما در بخش "اطلاعات محصول" -> "ویژگی ها" نیز انتخاب شده باشند تا این ویژگی ها به همراه مقادیر آن در صفحه معرفی محصول به درستی نمایش داده شود.', self::$textdomain),
                    'type'            => 'select_multi',
                    'options'        => self::get_all_product_attributes(self::get_product_id_from_url()),
                    'default'        => '',
                ),
                array(
                    'id'             => 'irantheme_custom_field_product_color_var_image_checkbox',
                    'label'            => __(
                        'نمایش تصویر مرتبط با متغیر رنگ',
                        self::$textdomain
                    ),
                    'description'    => __('در صورت استفاده از ویژگی رنگ برای متغییر محصول، اگر این گزینه فعال باشد بجای رنگ انتخاب شده تصویر مرتبط که در صفحه افزودن محصول و بخش متغییر ها به این رنگ اختصاص داده اید نمایش می یابد.در غیر اینصورت رنگ انتخابی نمایش داده می شود.', self::$textdomain),
                    'type'            => 'checkbox',
                    'default'        => 'off',
                ),
                array(
                    'id'             => 'irantheme_custom_field_product_tip',
                    'label'            => __('نکات فروش', self::$textdomain),
                    'description'    => __('در صورت نیاز به نمایش نکته در صفحه معرفی محصول می توانید متن مورد نظر خود را در این باکس وارد کنید.', self::$textdomain),
                    'type'            => 'textarea',
                    'default'        => '',
                    'placeholder'    => __('', self::$textdomain),
                ),
                array(
                    'id'             => 'irantheme_custom_field_seller_name',
                    'label'            => __('نام فروشنده محصول', self::$textdomain),
                    'description'    => __('در صورت نیاز به نمایش نام فروشنده متفاوت برای این محصول این فیلد را کامل کنید در غیر اینصورت آن را خالی بگذارید.', self::$textdomain),
                    'note'    => __('در صورت خالی گذاشتن این فیلد نام فروشنده از نام تنظیم شده در تنظیمات قالب -> ووکامرس -> صفحه معرفی محصول گرفته می شود.', self::$textdomain),
                    'type'            => 'text',
                    'default'        => '',
                    'placeholder'    => __('', self::$textdomain),
                ),
            );
            if ('on' == get_option('irantheme_single_product_shipping_time_checkbox', 'on')) {
                $shipping_time_option = array(
                    'id'             => 'irantheme_custom_field_shipping_time',
                    'label'            => __('حداکثر زمان ارسال محصول', self::$textdomain),
                    'description'    => __('در صورت نیاز به نمایش حداکثر زمان ارسال برای این محصول این فیلد را کامل کنید در غیر اینصورت آن را خالی بگذارید.', self::$textdomain),
                    'type'            => 'text',
                    'default'        => '',
                    'placeholder'    => __('', self::$textdomain),
                );
                array_push($custom_fields, $shipping_time_option);
            }
            return $custom_fields;
        }

        private static function get_product_id_from_url()
        {
            if (isset($_GET['post'])) {
                $product_id = intval($_GET['post']);
                if ($product_id > 0) {
                    return $product_id;
                } else {
                    return null;
                }
            }
        }

        /**
         * Return array of name and label from product attributes
         * @return array
         * @author M-MALEKZADEH
         * @since v1.0
         * @see v1.0
         */
        private static function get_all_product_attributes($product_id)
        {
            $product = wc_get_product($product_id);
            if (!$product) {
                return array();
            }

            $attributes_results = array();
            $attributes = $product->get_attributes();

            foreach ($attributes as $attribute_name => $attribute_object) {
                $label = '';
                $slug = '';

                /// For taxonomy-based features (global)
                if ($attribute_object->is_taxonomy()) {
                    $taxonomy = $attribute_object->get_taxonomy_object();
                    // Get displayable label
                    $label = $taxonomy ? $taxonomy->attribute_label : $attribute_name;
                    $slug = $attribute_name;
                } else {
                    // For custom properties
                    $label = $attribute_object->get_name();
                    $slug = sanitize_title($label);
                }
                if ($slug != '' && $label != '') {
                    $temp = array(
                        rawurldecode($slug) => rawurldecode($label),
                    );
                    $attributes_results = array_replace($attributes_results, $temp);
                }
            }
            return $attributes_results;
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


    $irantheme_custom_fields_object = new Irantheme_Custom_Fields_Product('product', 'محصول (تنظیمات سفارشی قالب)', 'advanced', 'high');
} // End if class exists statement
