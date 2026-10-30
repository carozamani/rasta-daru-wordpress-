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
 */
if (!class_exists('Irantheme_Settings_Cleaner')) {
    class Irantheme_Settings_Cleaner
    {
        // Parent slug
        private $parent;
        // Submenu title
        public $menu_title;
        // Page title
        private $page_title;
        // Description of main menu
        private $description;

        // Constructor initialize
        public function __construct(Irantheme_Settings $parent, $menu_title, $page_title, $description)
        {
            // Get parent slug
            $this->parent = $parent;
            // Menu title
            $this->menu_title = $menu_title;
            // Page title 
            $this->page_title = $page_title;
            // Description page
            $this->description = $description;
        }


        /**
         * Get menu slug of current class
         * @param void
         * @return string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_menu_slug()
        {
            return 'irantheme_settings_cleaner';
        }


        /**
         * Get permission url
         * @param void
         * @return string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_permission_url()
        {
            // Get WordPress site URL
            $permission_url = site_url();

            // Check if this is localhost
            if (strpos($permission_url, 'localhost') !== false || strpos($permission_url, '127.0.0.1') !== false) {
                // For localhost, return just 'localhost' without anything else
                $permission_url = 'localhost';
            } else {
                // For other domains, clean the URL normally
                $permission_url = preg_replace('#^https?://#', '', $permission_url);
                $permission_url = preg_replace('#^//+#', '', $permission_url);
                $permission_url = rtrim($permission_url, '/');
            }
            return $permission_url;
        }


        /**
         * Optimization of settings fields and sections
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public function optimize_action()
        {
            // Get current target
            $current_target = $_POST['current_target'];
            // Run current target
            if ($current_target == 'section_fields') {
            } elseif ($current_target == 'header_fields') {
            } elseif ($current_target == 'page_config') {
            } elseif ($current_target == 'sections_holders') {
            }
            echo 'Optimized successfully!';
            die();
        }


        /**
         * Cleaning of settings fields and sections (Warning delete all storeplus data)
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public function clean_action()
        {
            // Get current target
            $current_target = $_POST['current_target'];
            // Run current target
            if ($current_target == 'pages_dumps') {
                self::clean_all_pages_sections();
                self::clean_all_pages_headers_fields();
                self::clean_all_pages_configs_options();
            } elseif ($current_target == 'settings_options') {
                self::clean_all_settings_options();
            } elseif ($current_target == 'nav_menus') {
                self::clean_all_nav_menus();
            } elseif ($current_target == 'blocks_widgets') {
                self::clean_all_blocks_and_widgets();
            } elseif ($current_target == 'posts') {
                self::clean_all_posts();
                self::clean_all_categories();
            } elseif ($current_target == 'products') {
                self::clean_all_products();
                self::clean_all_categories_product();
                self::clean_all_wc_attributes();
            } elseif ($current_target == 'pages') {
                self::clean_all_pages();
            }
            echo 'Cleaned successfully!';
            die();
        }


        /**
         * Delete all blocks of widgets
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function clean_all_blocks_and_widgets()
        {
            // Clean sidebar widgets option
            update_option('sidebars_widgets', array());
            // Clean widget block option
            update_option('widget_block', array());
            // Reset sidebars_widgets
            update_option('sidebars_widgets', array(
                'wp_inactive_widgets' => array(),
                'irantheme_sidebar_template' => array(),
                'array_version' => 3,
            ));

            // Reset widget_block
            update_option('widget_block', array());
        }


        /**
         * Delete all nav menus and items
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function clean_all_nav_menus()
        {
            // Get all nav menus
            $nav_menus = wp_get_nav_menus();
            foreach ($nav_menus as $nav_menu) {
                // Delete nav menu
                wp_delete_nav_menu($nav_menu->term_id);
            }
        }


        /**
         * Delete all settings options
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function clean_all_settings_options()
        {
            $all_fields_settings = Irantheme_Fields_Options::fields();
            foreach ($all_fields_settings as $section => $data) {
                foreach ($data['submenu'] as $key => $value) {
                    // If fields not found, go to next section
                    if (!$value['fields']) {
                        continue;
                    }
                    foreach ($value['fields'] as $fields) {
                        $option_name = $fields['id'];
                        update_option($option_name, $fields['default']);
                    }
                }
            }
        }


        /**
         * Delete all headers fields of pages
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function clean_all_pages_headers_fields()
        {
            // Args of wp query
            $args = array(
                'post_type'        => 'page',
                'post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash'),
            );
            // Get all published pages
            $pages = get_pages($args);
            foreach ($pages as $page) {
                // Get page meta data json
                $page_meta_data_name = irantheme_get_meta_data_header_by_page_id();
                // Update page
                update_post_meta($page->ID, $page_meta_data_name, '');
            }
        }


        /**
         * Delete all options sections of pages
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function clean_all_pages_sections()
        {
            // Args of wp query
            $args = array(
                'post_type'        => 'page',
                'post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash'),
            );
            // Get all published pages
            $pages = get_pages($args);
            foreach ($pages as $page) {
                // Get page meta data json
                $page_meta_data_name = irantheme_get_meta_data_sections_by_page_id();
                // Update page
                update_post_meta($page->ID, $page_meta_data_name, '');
            }
        }


        /**
         * Delete all page config options
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function clean_all_pages_configs_options()
        {
            // Args of wp query
            $args = array(
                'post_type'        => 'page',
                'post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash'),
            );
            // Get all published pages
            $pages = get_pages($args);
            foreach ($pages as $page) {
                // Get page meta data json
                $page_meta_data_name = irantheme_get_meta_data_page_config_by_page_id();
                // Update page
                update_post_meta($page->ID, $page_meta_data_name, '');
            }
        }


        /**
         * Delete all pages
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function clean_all_pages()
        {
            foreach (get_all_page_ids() as $page_id) {
                wp_delete_post($page_id, true);
            }
        }


        /**
         * Delete all posts
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function clean_all_posts()
        {
            $args = array(
                'post_type'   => 'post',
                'post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash'),
                'posts_per_page' => -1,
            );
            foreach (get_posts($args) as $post) {
                wp_delete_post($post->ID, true);
            }
        }


        /**
         * Delete all products
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function clean_all_products()
        {
            $args = array(
                'post_type' => 'product',
                'post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash'),
                'posts_per_page' => -1,
            );
            foreach (get_posts($args) as $product) {
                wp_delete_post($product->get_id(), true);
            }
        }


        /**
         * Delete all categories
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function clean_all_categories()
        {
            $args = array(
                'taxonomy' => 'category',
                'hide_empty' => false
            );
            foreach (get_terms($args) as $category) {
                wp_delete_category($category->term_id);
            }
        }


        /**
         * Delete all product categories
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function clean_all_categories_product()
        {
            $args = array(
                'taxonomy' => 'product_cat',
                'hide_empty' => false
            );
            foreach (get_terms($args) as $category) {
                wp_delete_term($category->term_id, 'product_cat');
            }
        }


        /**
         * Delete all WooCommerce attributes
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function clean_all_wc_attributes()
        {
            // Check if WooCommerce is active
            if (!is_woocommerce_activated()) {
                return;
            }

            // Get all product attributes
            $attributes = wc_get_attribute_taxonomies();

            if (!empty($attributes)) {
                foreach ($attributes as $attribute) {
                    // Delete attribute terms
                    $taxonomy = wc_attribute_taxonomy_name($attribute->attribute_name);
                    $terms = get_terms(array(
                        'taxonomy' => $taxonomy,
                        'hide_empty' => false,
                    ));

                    if (!empty($terms) && !is_wp_error($terms)) {
                        foreach ($terms as $term) {
                            wp_delete_term($term->term_id, $taxonomy);
                        }
                    }

                    // Delete the attribute taxonomy
                    wc_delete_attribute($attribute->attribute_id);
                }
            }

            // Clear transients
            delete_transient('wc_attribute_taxonomies');
            wc_delete_product_transients();
        }


        /**
         * Add submenu to parent menu
         * @return void
         */
        public function add_submenu_page()
        {
            add_submenu_page($this->parent->menu_slug, __($this->page_title, $this->parent::$text_domain), __($this->menu_title, $this->parent::$text_domain), $this->parent->capability, self::get_menu_slug(), array($this, 'callback_panel'));
        }


        /**
         * Add details settings and options settings to custom wp admin menu
         * @return void
         */
        public function callback_panel()
        {
            // Get header parent
            $this->parent->get_header($this->page_title, $this->description, self::get_menu_slug(), 'cleaner');
?>
            <div class="irantheme-settings-cleaner">
                <div class="irantheme-settings-cleaner-wrapper">
                    <div class="row">
                        <div class="col-lg-6 mb-4 d-none">
                            <div class="irantheme-settings-cleaner-box">
                                <!-- Icon -->
                                <span class="irantheme-settings-cleaner-box-icon optimize">
                                    <i class="fa-light fa-wrench"></i>
                                </span>
                                <!-- Heading -->
                                <h2 class="irantheme-settings-cleaner-box-heading"><?php _e('بهینه سازی و ارتقای عملکرد'); ?></h2>
                                <!-- Description -->
                                <p class="irantheme-settings-cleaner-box-desc"><?php _e('سایت ها و سرویس هایی که قابلیت ذخیره سازی دارند طی بازه های زمانی مختلف نیازمند بهینه سازی سیستم ذخیره سازی خود می باشند. این سیستم ها در طولانی مدت داده های بی هدفی تولید می کنند و نیازمند یک بازنگری و حذف آنها می باشد.') ?></p>
                                <!-- List report -->
                                <div class="irantheme-settings-cleaner-box-list-report optimize">
                                    <?php
                                    $optimize_fields = array(
                                        array(
                                            'title' => 'حذف داده های اضافی بخش های حذف شده',
                                            'icon' => 'fa-light fa-badge',
                                            'target' => 'section_fields',
                                        ),
                                        array(
                                            'title' => 'حذف داده های اضافی هدر برگه های حذف شده',
                                            'icon' => 'fa-light fa-badge',
                                            'target' => 'header_fields',
                                        ),
                                        array(
                                            'title' => 'حذف داده های ریز برگه های حذف شده',
                                            'icon' => 'fa-light fa-badge',
                                            'target' => 'page_config',
                                        ),
                                        array(
                                            'title' => 'حذف نگهدارنده بخش برگه های حذف شده',
                                            'icon' => 'fa-light fa-badge',
                                            'target' => 'sections_holders',
                                        ),
                                    )
                                    ?>
                                    <ul class="irantheme-settings-cleaner-box-list-report-ul">
                                        <?php foreach ($optimize_fields as $optimize_item) : ?>
                                            <li class="irantheme-settings-cleaner-box-list-report-ul-li" data-target="<?php echo esc_attr($optimize_item['target']); ?>">
                                                <i class="<?php echo esc_attr($optimize_item['icon']); ?>"></i>
                                                <cite><?php echo esc_html($optimize_item['title']); ?></cite>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                                <?php Irantheme_Settings::get_notification('info', 'نکته : پیشنهاد می کنیم هر دو ماه یکبار از بهینه ساز استفاده کنید.', 'm-0 mb-4 d-flex') ?>
                                <!-- Button trigger action -->
                                <button class="irantheme-button-default irantheme-button-dark irantheme-button-medium irantheme-settings-cleaner-box-trigger irantheme-settings-cleaner-optimize">
                                    <i id="irantheme-spinner-alt" class="irantheme-animate-rotate" style="display: none;"></i>
                                    <cite><?php _e('شروع بهینه سازی'); ?></cite>
                                    <i class="fa-light fa-wrench"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="irantheme-settings-cleaner-box">
                                <!-- Icon -->
                                <span class="irantheme-settings-cleaner-box-icon clean">
                                    <i class="fa-light fa-trash-can-list"></i>
                                </span>
                                <!-- Heading -->
                                <h2 class="irantheme-settings-cleaner-box-heading"><?php _e('پاک سازی و بازگردانی به تنظیمات اولیه'); ?></h2>
                                <!-- Description -->
                                <p class="irantheme-settings-cleaner-box-desc"><?php _e('در صورتی که می خواهید قالب و صفحه ساز استورپلاس تمامی داده های ذخیره شما را پاک نماید می توانید از این بخش استفاده کنید. توجه داشته باشید که در صورت اجرای این مرحله، ممکن است دقایقی از وقت شما را بگیرد پس شکیبا باشید.') ?></p>
                                <!-- List report -->
                                <div class="irantheme-settings-cleaner-box-list-report clean">
                                    <?php
                                    $clean_fields = array(
                                        array(
                                            'title' => 'پاک سازی تمامی داده های برگه ها (شامل بخش ها، هدر صفحه، تنظیمات صفحه)',
                                            'icon' => 'fa-light fa-badge',
                                            'target' => 'pages_dumps',
                                        ),
                                        array(
                                            'title' => 'پاک سازی تمامی داده های تنظیمات قالب سایت',
                                            'icon' => 'fa-light fa-badge',
                                            'target' => 'settings_options',
                                        ),
                                        array(
                                            'title' => 'پاک سازی تمامی فهرست های سایت',
                                            'icon' => 'fa-light fa-badge',
                                            'target' => 'nav_menus',
                                        ),
                                        array(
                                            'title' => 'پاک سازی تمامی ابزارک ها و سایدبار های سایت',
                                            'icon' => 'fa-light fa-badge',
                                            'target' => 'blocks_widgets',
                                        ),
                                        array(
                                            'title' => 'پاک سازی تمامی نوشته ها و دسته بندی',
                                            'icon' => 'fa-light fa-badge',
                                            'target' => 'posts',
                                        ),
                                        array(
                                            'title' => 'پاک سازی تمامی محصولات، دسته بندی و ویژگی‌های محصولات',
                                            'icon' => 'fa-light fa-badge',
                                            'target' => 'products',
                                        ),
                                        array(
                                            'title' => 'پاک سازی تمامی صفحات و بخش ها',
                                            'icon' => 'fa-light fa-badge',
                                            'target' => 'pages',
                                        ),
                                    )
                                    ?>
                                    <ul class="irantheme-settings-cleaner-box-list-report-ul">
                                        <?php foreach ($clean_fields as $clean_item) : ?>
                                            <li class="irantheme-settings-cleaner-box-list-report-ul-li" data-target="<?php echo esc_attr($clean_item['target']); ?>">
                                                <i class="<?php echo esc_attr($clean_item['icon']); ?>"></i>
                                                <cite><?php echo esc_html($clean_item['title']); ?></cite>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                                <?php Irantheme_Settings::get_notification('error', 'توجه : با اجرای پاک سازی، تمامی داده های ذکر شده حذف خواهند شد و قابل برگشت نخواهند بود!', 'm-0 mb-4 d-flex') ?>
                                <!-- Button trigger action -->
                                <button class="irantheme-button-default irantheme-button-dark irantheme-button-medium irantheme-settings-cleaner-box-trigger irantheme-settings-cleaner-clean" data-permission="<?php echo esc_attr(self::get_permission_url()); ?>">
                                    <i id="irantheme-spinner-alt" class="irantheme-animate-rotate" style="display: none;"></i>
                                    <cite><?php _e('شروع پاک سازی'); ?></cite>
                                    <i class="fa-light fa-trash-can-list"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<?php
            // Get footer parent
            $this->parent->get_footer('cleaner');
        }
    }
}
