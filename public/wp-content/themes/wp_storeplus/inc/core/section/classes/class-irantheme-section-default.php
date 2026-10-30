<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;


if (!class_exists('Irantheme_Section_Default')) {

    /**
     * A class for create default sections demo values
     *
     * @package Wordpress
     * @subpackage irantheme
     * @since irantheme - custom fields
     * @author iranianclever
     * @see v1.0
     *
     */

    class Irantheme_Section_Default
    {
        /**
         * Constructor
         * @return void
         * @param void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        function __construct() {}


        /**
         * Return default ids object created
         * @return string
         * @param string,object,string,string,string,string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_default_ids_objects($objectCount, $data_value, $status_default)
        {
            // Json data holder (Array structure)
            $array_data = array();

            // Counter post for post creation needed
            $index = $objectCount;

            if ($index > 0) {

                // Loop from index
                for ($i = 0; $i < $index; $i++) {
                    // Get fields of object from section
                    $fields_tab = Irantheme_Page_Builder_Ajax::get_object_fields($data_value, $i, $status_default);
                    $array_item = array();
                    $array_item['object_id'] = Irantheme_Section_Default::get_generate_data_ajax(8);
                    $array_item['fields'] = array();

                    foreach ($fields_tab as $fields) {
                        foreach ($fields['fields'] as $field) {
                            $key = $field['id'];
                            $value = urldecode($field['default']);

                            $data_field = array(
                                $key => $value
                            );
                            $array_item['fields'] = array_merge($array_item['fields'], $data_field);
                        }
                    }
                    array_push($array_data, $array_item);
                }
            }

            $json_data = json_encode($array_data);
            return $json_data;
        }


        /**
         * Return default ids of post box
         * @return string
         * @param int
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_default_ids_post_box($index = 6)
        {
            // Holder of ids created
            $ids = '';

            if ($index > 0) {
                // List of default titles
                $title = array(
                    'نقش اینترنت اشیاء در وسایل الکترونیکی آینده',
                    'هوش مصنوعی دنیا را چه شکلی خواهد کرد؟',
                    'چوب راش بهترین انتخاب جهت استفاده در مبلمان',
                    'اثر معماری در زندگی و روان بشریت',
                    'تقریبا راهی برای جایگزینی کتاب وجود ندارد!',
                    'زبان هنر و اثر آن در روح و کالبد اجتماع',
                    'صنعت و نقش بسزای آن در تولید اشتغال',
                    'ورزش، قسمتی فراموش شده در زندگی انسان ها',
                    'رتبه اول ایران در صنعت پزشکی منطقه و اهمیت آن',
                    'گیاهان آپارتمانی و روش نگهداری از آنها',
                    'رایحه های طبیعی تا چه میزان سود آورند؟',
                    'نقش گفتگو در شکل گیری رابطه ای مستحکم برای زوجین',
                );

                // Default content
                $content = 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است. چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است و برای شرایط فعلی تکنولوژی مورد نیاز و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد. کتابهای زیادی در شصت و سه درصد گذشته، حال و آینده شناخت فراوان جامعه و متخصصان را می طلبد تا با نرم افزارها شناخت بیشتری را برای طراحان رایانه ای علی الخصوص طراحان خلاقی و فرهنگ پیشرو در زبان فارسی ایجاد کرد. در این صورت می توان امید داشت که تمام و دشواری موجود در ارائه راهکارها و شرایط سخت تایپ به پایان رسد وزمان مورد نیاز شامل حروفچینی دستاوردهای اصلی و جوابگوی سوالات پیوسته اهل دنیای موجود طراحی اساسا مورد استفاده قرار گیرد.';

                $excerpt = 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است. چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است و برای شرایط فعلی تکنولوژی مورد نیاز و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد.';

                // List of categories ids from post-type
                $list_category_ids = self::get_categories_ids();

                // List of post images
                $images = array(
                    'https://irantheme.com/wp-content/uploads/2025/01/r4daftzq.jpg',
                    'https://irantheme.com/wp-content/uploads/2025/01/radomfim.jpg',
                    'https://irantheme.com/wp-content/uploads/2025/01/4ls1ykyl.jpg',
                    'https://irantheme.com/wp-content/uploads/2025/01/ndrr1rzy.jpg',
                    'https://irantheme.com/wp-content/uploads/2025/01/z97fhyo0.jpg',
                    'https://irantheme.com/wp-content/uploads/2025/01/z7nqh22d.jpg',
                    'https://irantheme.com/wp-content/uploads/2025/01/wcvhco7g.jpg',
                    'https://irantheme.com/wp-content/uploads/2025/01/j3pvr8j9.jpg',
                    'https://irantheme.com/wp-content/uploads/2025/01/53ef2gu8.jpg',
                    'https://irantheme.com/wp-content/uploads/2025/01/6hfrwgzz.jpg',
                    'https://irantheme.com/wp-content/uploads/2025/01/rzhkvt0k.jpg',
                    'https://irantheme.com/wp-content/uploads/2025/01/texi4n2r.jpg',
                );

                $categoreis = array(
                    array($list_category_ids[1]),
                    array($list_category_ids[1], $list_category_ids[2]),
                    array($list_category_ids[3]),
                    array($list_category_ids[0], $list_category_ids[6]),
                    array($list_category_ids[7]),
                    array($list_category_ids[5]),
                    array($list_category_ids[3]),
                    array($list_category_ids[4]),
                    array($list_category_ids[4]),
                    array($list_category_ids[7]),
                    array($list_category_ids[3]),
                    array($list_category_ids[6]),
                );

                // Loop from index
                for ($i = 0; $i < $index; $i++) {
                    // Create default post
                    $post_id = wp_insert_post(array(
                        'post_type' => 'post',
                        'post_title' => __($title[$i]),
                        'post_content' => __($content),
                        'post_excerpt' => __($excerpt),
                        'post_status' => 'publish',
                        'post_category' => $categoreis[$i],
                    ));
                    // Check post is created?
                    if ($post_id) {
                        $image_id = Irantheme_Common_Static::get_file_url_media_library($images[$i]);
                        set_post_thumbnail($post_id, $image_id);
                        $ids .= $post_id . ',';
                    }
                }
            }

            return $ids;
        }


        /**
         * Create default services for section gallery simple
         * @return string
         * @param string,string,string,string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_default_ids_gallery_master($index = 9)
        {
            // Holder of ids created
            $ids = '';

            if ($index > 0) {
                // List of default titles
                $images = array(
                    'https://irantheme.com/wp-content/uploads/2026/02/0rr70mxr.webp',
                    'https://irantheme.com/wp-content/uploads/2026/02/7o9uz2om.webp',
                    'https://irantheme.com/wp-content/uploads/2026/02/8b9s8wy0.webp',
                    'https://irantheme.com/wp-content/uploads/2026/02/cwftkucn.webp',
                    'https://irantheme.com/wp-content/uploads/2026/02/mifriyrg.webp',
                    'https://irantheme.com/wp-content/uploads/2026/02/p5rhheii.webp',
                    'https://irantheme.com/wp-content/uploads/2026/02/qbs2hf29.webp',
                    'https://irantheme.com/wp-content/uploads/2026/02/rkixr97d.webp',
                    'https://irantheme.com/wp-content/uploads/2026/02/z21wxte8.webp',
                );

                // Loop from index
                for ($i = 0; $i < $index; $i++) {
                    $image_id = wp_get_attachment_url(Irantheme_Common_Static::get_file_url_media_library($images[$i]));
                    $ids .= $image_id . ',';
                }
            }

            return $ids;
        }


        /**
         * Return a number of data ajax generated
         * @return string
         * @param string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_generate_data_ajax($num)
        {
            // All generation code
            $characters = '1234567890abcdefghijklmnopqrsvutwxyz';
            // Code holder
            $codes = '';
            // Loop from chars
            for ($i = 0; $i < $num; $i++) {
                // Create random number
                $rand_number = rand(0, (strlen($characters) - 1));
                // Concatenation char random
                $codes .= $characters[$rand_number];
            }
            return $codes;
        }


        /**
         * Create and return categories ids from post type
         * @return array
         * @param void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_categories_ids()
        {
            $index = 8;
            // List of default titles
            $title = array(
                'معماری',
                'فناوری اطلاعات',
                'هوش مصنوعی',
                'صنعت و تجارت',
                'علمی پژوهشی',
                'هنر و تجربه',
                'روانشناسی',
                'عمومی',
            );
            // List of default descriptions
            $description = array(
                'معماری و طراحی داخلی و خارجی',
                'مباحث مربوط به کامپیوتر و اینترنت اشیا',
                'ای آی و اثر هوش مصنوعی در زندگی',
                'مباحث مربوط به صنعت چوب',
                'مقالات علمی و مربوط به لبه فناوری',
                'مطالب هنری و مربوط به هنرمندان',
                'روانشناسی و سبک زندگی انسان ها',
                'مقالات عمومی و همه جانبه',
            );
            // Holder of categories ids
            $list_category_ids = array();
            // Loop fron index
            for ($i = 0; $i < $index; $i++) {
                // Check if the category already exists
                if (!term_exists($title[$i], 'category')) {
                    // Insert the category
                    $cate_array = wp_insert_term(
                        $title[$i], // The name of the category
                        'category',       // The taxonomy (category, post_tag, or custom taxonomy)
                        array(
                            'description' => $description[$i], // Description of the category
                        )
                    );
                    // Check if the term was successfully inserted
                    if (!is_wp_error($cate_array)) {
                        $term_id = $cate_array['term_id']; // Get the term_id
                        array_push($list_category_ids, $term_id);
                    }
                } else {
                    // Get category id from prev category
                    $category_title = $title[$i]; // Replace with your category title
                    $category = get_term_by('name', $category_title, 'category');

                    if ($category) {
                        $term_id = $category->term_id; // Get the term_id
                        array_push($list_category_ids, $term_id);
                    }
                }
            }
            return $list_category_ids;
        }
    } // End Class
} // End if class exists statement
