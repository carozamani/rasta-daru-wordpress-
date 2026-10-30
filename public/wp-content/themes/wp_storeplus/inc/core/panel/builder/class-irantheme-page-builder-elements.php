<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;

/**
 * The Storeplus Theme Settings class
 * A Customizable Class and Settings
 * 
 * @package Wordpress
 * @subpackage Storeplus
 * @since v1.0
 * 
 * This class is a custom settings theme for admin page.
 * https://irantheme.com/wp-content/uploads/2024/04/work-scaled.jpg
 */
if (!class_exists('Irantheme_Page_Builder_Elements')) {
    class Irantheme_Page_Builder_Elements
    {
        public function __construct($textdomain) {}


        /**
         * Return array of sections fields (info)
         * @param void
         * @return array
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function fields()
        {
            $sections_info = array(
                array(
                    'element_title' => 'ویترین ساده,showcase simple',
                    'element_thumb' => 'https://irantheme.com/wp-content/uploads/2026/05/showcase_simple.webp',
                    'element_value' => 'showcase_simple',
                    'section_type' => array('showcase'),
                ),
                array(
                    'element_title' => 'ویترین سرویس,showcase service',
                    'element_thumb' => 'https://irantheme.com/wp-content/uploads/2026/05/showcase_service.webp',
                    'element_value' => 'showcase_service',
                    'section_type' => array('showcase'),
                ),
                array(
                    'element_title' => 'ویترین برچسب,showcase tag',
                    'element_thumb' => 'https://irantheme.com/wp-content/uploads/2026/05/showcase_tag.webp',
                    'element_value' => 'showcase_tag',
                    'section_type' => array('showcase'),
                ),
                array(
                    'element_title' => 'ویترین ویدئویی,showcase video',
                    'element_thumb' => 'https://irantheme.com/wp-content/uploads/2026/05/showcase_video.webp',
                    'element_value' => 'showcase_video',
                    'section_type' => array('showcase'),
                ),
                array(
                    'element_title' => 'ویترین مجزا,showcase split',
                    'element_thumb' => 'https://irantheme.com/wp-content/uploads/2026/05/showcase_split.webp',
                    'element_value' => 'showcase_split',
                    'section_type' => array('showcase'),
                ),
                array(
                    'element_title' => 'ویترین نویسنده,showcase author',
                    'element_thumb' => 'https://irantheme.com/wp-content/uploads/2026/05/showcase_author.webp',
                    'element_value' => 'showcase_author',
                    'section_type' => array('showcase'),
                ),
                array(
                    'element_title' => 'ویترین شمارنده,showcase counter',
                    'element_thumb' => 'https://irantheme.com/wp-content/uploads/2026/05/showcase_counter.webp',
                    'element_value' => 'showcase_counter',
                    'section_type' => array('showcase'),
                ),
                array(
                    'element_title' => 'اسلایدر ساده,simple slider',
                    'element_thumb' => 'https://irantheme.com/wp-content/uploads/2026/05/slider_simple.webp',
                    'element_value' => 'slider_simple',
                    'section_type' => array('slider'),
                ),
                array(
                    'element_title' => 'سرویس مستر,service master',
                    'element_thumb' => 'https://irantheme.com/wp-content/uploads/2026/05/service_master.webp',
                    'element_value' => 'service_master',
                    'section_type' => array('service'),
                ),
                array(
                    'element_title' => 'نیم سرویس مستر,service semi master',
                    'element_thumb' => 'https://irantheme.com/wp-content/uploads/2026/05/service_semi_master.webp',
                    'element_value' => 'service_semi_master',
                    'section_type' => array('service'),
                ),
                array(
                    'element_title' => 'نیم سرویس عددی,service semi number',
                    'element_thumb' => 'https://irantheme.com/wp-content/uploads/2026/05/service_semi_number.webp',
                    'element_value' => 'service_semi_number',
                    'section_type' => array('service'),
                ),
                array(
                    'element_title' => 'سرویس نصف صفحه,service semi split image',
                    'element_thumb' => 'https://irantheme.com/wp-content/uploads/2026/05/service_semi_split_image.webp',
                    'element_value' => 'service_semi_split_image',
                    'section_type' => array('service'),
                ),
                array(
                    'element_title' => 'سرویس عددی,service number',
                    'element_thumb' => 'https://irantheme.com/wp-content/uploads/2026/05/service_number.webp',
                    'element_value' => 'service_number',
                    'section_type' => array('service'),
                ),
                array(
                    'element_title' => 'سرویس اسلایدر یادداشت,service note slider',
                    'element_thumb' => 'https://irantheme.com/wp-content/uploads/2026/05/service_note_slider.webp',
                    'element_value' => 'service_note_slider',
                    'section_type' => array('service', 'slider'),
                ),
                array(
                    'element_title' => 'منو پیشرفت,service progress',
                    'element_thumb' => 'https://irantheme.com/wp-content/uploads/2026/05/service_progress.webp',
                    'element_value' => 'service_progress',
                    'section_type' => array('service'),
                ),
                array(
                    'element_title' => 'نیم سرویس شبکه ای,service semi grid image',
                    'element_thumb' => 'https://irantheme.com/wp-content/uploads/2026/05/service_semi_grid_image.webp',
                    'element_value' => 'service_semi_grid_image',
                    'section_type' => array('service'),
                ),
                array(
                    'element_title' => 'همکاران,business authors post',
                    'element_thumb' => 'https://irantheme.com/wp-content/uploads/2026/05/business_authors_post.webp',
                    'element_value' => 'business_authors_post',
                    'section_type' => array('business'),
                ),
                array(
                    'element_title' => 'نظرات مشتریان,business viewpoints',
                    'element_thumb' => 'https://irantheme.com/wp-content/uploads/2026/05/business_viewpoints.webp',
                    'element_value' => 'business_viewpoints',
                    'section_type' => array('business'),
                ),
                array(
                    'element_title' => 'دیدگاه مشتریان (جعبه ای),business box reviews',
                    'element_thumb' => 'https://irantheme.com/wp-content/uploads/2026/05/business_box_reviews.webp',
                    'element_value' => 'business_box_reviews',
                    'section_type' => array('business'),
                ),
                array(
                    'element_title' => 'آی بنر,business ibanner',
                    'element_thumb' => 'https://irantheme.com/wp-content/uploads/2026/05/business_ibanner.webp',
                    'element_value' => 'business_ibanner',
                    'section_type' => array('business', 'gallery'),
                ),
                array(
                    'element_title' => 'تبلیغات,business ads',
                    'element_thumb' => 'https://irantheme.com/wp-content/uploads/2026/05/business_ads.webp',
                    'element_value' => 'business_ads',
                    'section_type' => array('business'),
                ),
                array(
                    'element_title' => 'عناصر فرشیم,business department',
                    'element_thumb' => 'https://irantheme.com/wp-content/uploads/2026/05/business_department.webp',
                    'element_value' => 'business_department',
                    'section_type' => array('business'),
                ),
                array(
                    'element_title' => 'لوگو مشتریان,business brands',
                    'element_thumb' => 'https://irantheme.com/wp-content/uploads/2026/05/business_brands.webp',
                    'element_value' => 'business_brands',
                    'section_type' => array('business'),
                ),
                array(
                    'element_title' => 'نمونه کار,business portfolio',
                    'element_thumb' => 'https://irantheme.com/wp-content/uploads/2026/05/business_portfolio.webp',
                    'element_value' => 'business_portfolio',
                    'section_type' => array('business', 'gallery'),
                ),
                array(
                    'element_title' => 'سرویس پلن تجاری,business plane service',
                    'element_thumb' => 'https://irantheme.com/wp-content/uploads/2026/05/business_plane_service.webp',
                    'element_value' => 'business_plane_service',
                    'section_type' => array('business'),
                ),
                array(
                    'element_title' => 'سرویس پلن تجاری مدرن,business modern plane service',
                    'element_thumb' => 'https://irantheme.com/wp-content/uploads/2026/05/business_modern_plane_service.webp',
                    'element_value' => 'business_modern_plane_service',
                    'section_type' => array('business'),
                ),
                array(
                    'element_title' => 'درباره من,business about me',
                    'element_thumb' => 'https://irantheme.com/wp-content/uploads/2026/05/business_about_me.webp',
                    'element_value' => 'business_about_me',
                    'section_type' => array('business', 'communication'),
                ),
                array(
                    'element_title' => 'فرم تماس با ما,communication contact',
                    'element_thumb' => 'https://irantheme.com/wp-content/uploads/2026/05/communication_contact.webp',
                    'element_value' => 'communication_contact',
                    'section_type' => array('communication'),
                ),
                array(
                    'element_title' => 'فرم تماس خطی,communication line contact',
                    'element_thumb' => 'https://irantheme.com/wp-content/uploads/2026/05/communication_line_contact.webp',
                    'element_value' => 'communication_line_contact',
                    'section_type' => array('communication'),
                ),
                array(
                    'element_title' => 'گوگل مپ,communication google map',
                    'element_thumb' => 'https://irantheme.com/wp-content/uploads/2024/04/communication-google-map.jpg',
                    'element_value' => 'communication_google_map',
                    'section_type' => array('communication'),
                ),
                array(
                    'element_title' => 'خبرنامه,communication newsletter',
                    'element_thumb' => 'https://irantheme.com/wp-content/uploads/2026/05/communication_newsletter.webp',
                    'element_value' => 'communication_newsletter',
                    'section_type' => array('communication'),
                ),
                array(
                    'element_title' => 'سوالات متداول,communication faq',
                    'element_thumb' => 'https://irantheme.com/wp-content/uploads/2026/05/communication_faq.webp',
                    'element_value' => 'communication_faq',
                    'section_type' => array('communication'),
                ),
                array(
                    'element_title' => 'گالری مستر,gallery master',
                    'element_thumb' => 'https://irantheme.com/wp-content/uploads/2026/05/gallery_master.webp',
                    'element_value' => 'gallery_master',
                    'section_type' => array('gallery'),
                ),
                array(
                    'element_title' => 'گالری بنر,gallery banner',
                    'element_thumb' => 'https://irantheme.com/wp-content/uploads/2026/05/gallery_banner.webp',
                    'element_value' => 'gallery_banner',
                    'section_type' => array('gallery'),
                ),
                array(
                    'element_title' => 'گالری اسلایدر,gallery slider',
                    'element_thumb' => 'https://irantheme.com/wp-content/uploads/2026/05/gallery_slider.webp',
                    'element_value' => 'gallery_slider',
                    'section_type' => array('gallery', 'slider'),
                ),
                array(
                    'element_title' => 'گالری تایپ,gallery type',
                    'element_thumb' => 'https://irantheme.com/wp-content/uploads/2026/05/gallery_type.webp',
                    'element_value' => 'gallery_type',
                    'section_type' => array('gallery'),
                ),
                array(
                    'element_title' => 'نوشته صفحه,page text',
                    'element_thumb' => 'https://irantheme.com/wp-content/uploads/2026/05/page_text.webp',
                    'element_value' => 'page_text',
                    'section_type' => array('post'),
                ),
                array(
                    'element_title' => 'گالری نوشته ها,post gallery',
                    'element_thumb' => 'https://irantheme.com/wp-content/uploads/2026/05/post_gallery.webp',
                    'element_value' => 'post_gallery',
                    'section_type' => array('gallery', 'post'),
                ),
                array(
                    'element_title' => 'نوشته جعبه ای,post box',
                    'element_thumb' => 'https://irantheme.com/wp-content/uploads/2026/05/post_box.webp',
                    'element_value' => 'post_box',
                    'section_type' => array('post'),
                ),
                array(
                    'element_title' => 'نوشته ساده,post simple',
                    'element_thumb' => 'https://irantheme.com/wp-content/uploads/2026/06/post_simple.webp',
                    'element_value' => 'post_simple',
                    'section_type' => array('post'),
                ),
                array(
                    'element_title' => 'نوشته سایدبار,post sidebar',
                    'element_thumb' => 'https://irantheme.com/wp-content/uploads/2026/05/post_sidebar.webp',
                    'element_value' => 'post_sidebar',
                    'section_type' => array('post'),
                ),
                array(
                    'element_title' => 'محصولات ووکامرس,woocommerce products',
                    'element_thumb' => 'https://irantheme.com/wp-content/uploads/2026/06/woocommerce_products.webp',
                    'element_value' => 'woocommerce_products',
                    'section_type' => array('woocommerce'),
                ),
                array(
                    'element_title' => 'دسته بندی ووکامرس,woocommerce categories',
                    'element_thumb' => 'https://irantheme.com/wp-content/uploads/2026/06/woocommerce_categories.webp',
                    'element_value' => 'woocommerce_categories',
                    'section_type' => array('woocommerce'),
                ),
                array(
                    'element_title' => 'محصولات بند انگشتی ووکامرس,woocommerce mini products',
                    'element_thumb' => 'https://irantheme.com/wp-content/uploads/2026/10/woocommerce_mini_products.webp',
                    'element_value' => 'woocommerce_mini_products',
                    'section_type' => array('woocommerce'),
                ),
                array(
                    'element_title' => 'محصولات کاشی ووکامرس,woocommerce grid products',
                    'element_thumb' => 'https://irantheme.com/wp-content/uploads/2026/10/woocommerce_grid_products.webp',
                    'element_value' => 'woocommerce_grid_products',
                    'section_type' => array('woocommerce'),
                ),
                array(
                    'element_title' => 'محصولات پیشنهادی ووکامرس,woocommerce offers products',
                    'element_thumb' => 'https://irantheme.com/wp-content/uploads/2026/10/woocommerce_offers_products.webp',
                    'element_value' => 'woocommerce_offers_products',
                    'section_type' => array('woocommerce'),
                ),
                array(
                    'element_title' => 'محصولات کانون داغ ووکامرس,woocommerce hotspot',
                    'element_thumb' => 'https://irantheme.com/wp-content/uploads/2026/06/woocommerce_hotspot.webp',
                    'element_value' => 'woocommerce_hotspot',
                    'section_type' => array('woocommerce'),
                ),
                array(
                    'element_title' => 'کارت دسته بندی محصولات,woocommerce products category',
                    'element_thumb' => 'https://irantheme.com/wp-content/uploads/2026/06/woocommerce_category_products.webp',
                    'element_value' => 'woocommerce_category_products',
                    'section_type' => array('woocommerce'),
                ),
                array(
                    'element_title' => 'اسلایدر ووکامرس,woocommerce slider',
                    'element_thumb' => 'https://irantheme.com/wp-content/uploads/2026/06/woocommerce_slider.webp',
                    'element_value' => 'woocommerce_slider',
                    'section_type' => array('woocommerce', 'slider'),
                ),
                array(
                    'element_title' => 'دسته بندی جعبه ای ووکامرس,woocommerce box categories',
                    'element_thumb' => 'https://irantheme.com/wp-content/uploads/2026/06/woocommerce_box_categories.webp',
                    'element_value' => 'woocommerce_box_categories',
                    'section_type' => array('woocommerce'),
                ),
                array(
                    'element_title' => 'برند های جعبه ای ووکامرس,woocommerce box brands',
                    'element_thumb' => 'https://irantheme.com/wp-content/uploads/2026/06/woocommerce_box_brands.webp',
                    'element_value' => 'woocommerce_box_brands',
                    'section_type' => array('woocommerce'),
                ),
                array(
                    'element_title' => 'محصولات ایجکس ووکامرس,woocommerce ajax categories products',
                    'element_thumb' => 'https://irantheme.com/wp-content/uploads/2026/09/woocommerce_ajax_categories_products.webp',
                    'element_value' => 'woocommerce_ajax_categories_products',
                    'section_type' => array('woocommerce'),
                ),
                array(
                    'element_title' => 'گالری شبکه های اجتماعی,gallery social',
                    'element_thumb' => 'https://irantheme.com/wp-content/uploads/2026/06/woocommerce_gallery_social.webp',
                    'element_value' => 'woocommerce_gallery_social',
                    'section_type' => array('woocommerce'),
                ),
                array(
                    'element_title' => 'پاورقی کلاسیک,footer classic,فوتر کلاسیک',
                    'element_thumb' => 'https://irantheme.com/wp-content/uploads/2026/05/footer_classic.webp',
                    'element_value' => 'footer_classic',
                    'section_type' => array('footer'),
                ),
                array(
                    'element_title' => 'پاورقی مستر,footer master,فوتر مستر',
                    'element_thumb' => 'https://irantheme.com/wp-content/uploads/2026/05/footer_master.webp',
                    'element_value' => 'footer_master',
                    'section_type' => array('footer'),
                ),
                array(
                    'element_title' => 'پاورقی کپی رایت,footer copyright,فوتر حق نشر,فوتر حق تکثیر',
                    'element_thumb' => 'https://irantheme.com/wp-content/uploads/2026/05/footer_copyright.webp',
                    'element_value' => 'footer_copyright',
                    'section_type' => array('footer'),
                ),
                array(
                    'element_title' => 'فوتر ناوبری,footer nav,فوتر منو,فوتر فهرست,فوتر پلتفرم,فوتر اپلیکیشن',
                    'element_thumb' => 'https://irantheme.com/wp-content/uploads/2026/05/footer_nav.webp',
                    'element_value' => 'footer_nav',
                    'section_type' => array('footer'),
                ),
            );
            return $sections_info;
        }


        /**
         * Return array of sections categories
         * @param void
         * @return array
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function categories()
        {
            // Implement of settings visual options
            $categories = array(
                array(
                    // Section fields (Hide)
                    'section_id' => 'showcase',
                    'section_title' => 'ویترین',
                    'section_type' => 'showcase',
                    'section_icon' => 'fa-light fa-gallery-thumbnails',
                ),
                array(
                    // Section fields (Hide)
                    'section_id' => 'slider',
                    'section_title' => 'اسلایدر',
                    'section_type' => 'slider',
                    'section_icon' => 'fa-light fa-images',
                ),
                array(
                    // Section fields (Hide)
                    'section_id' => 'service',
                    'section_title' => 'سرویس ها',
                    'section_type' => 'service',
                    'section_icon' => 'fa-light fa-table-cells-large',
                ),
                array(
                    // Section fields (Hide)
                    'section_id' => 'business',
                    'section_title' => 'کسب و کار',
                    'section_type' => 'business',
                    'section_icon' => 'fa-light fa-sack-dollar',
                ),
                array(
                    // Section fields (Hide)
                    'section_id' => 'communication',
                    'section_title' => 'ارتباطات',
                    'section_type' => 'communication',
                    'section_icon' => 'fa-light fa-tower-cell',
                ),
                array(
                    // Section fields (Hide)
                    'section_id' => 'gallery',
                    'section_title' => 'گالری',
                    'section_type' => 'gallery',
                    'section_icon' => 'fa-light fa-image',
                ),
                array(
                    // Section fields (Hide)
                    'section_id' => 'post',
                    'section_title' => 'نوشته ها',
                    'section_type' => 'post',
                    'section_icon' => 'fa-light fa-file-lines',
                ),
                array(
                    // Section fields (Hide)
                    'section_id' => 'woocommerce',
                    'section_title' => 'ووکامرس',
                    'section_type' => 'woocommerce',
                    'section_icon' => 'fa-light fa-cart-shopping',
                ),
                array(
                    // Section fields (Hide)
                    'section_id' => 'footer',
                    'section_title' => 'پاورقی',
                    'section_type' => 'footer',
                    'section_icon' => 'fa-light fa-keyboard-down',
                ),
            );
            // Return all fields
            return $categories;
        }
    }
}
