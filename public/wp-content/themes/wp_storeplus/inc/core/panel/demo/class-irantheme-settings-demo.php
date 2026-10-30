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
if (!class_exists('Irantheme_Settings_Demo')) {
    class Irantheme_Settings_Demo
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
            return 'irantheme_settings_demo';
        }


        /**
         * Get data plugin name and install plugin from wordpress repository
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public function demo_export()
        {
            echo Irantheme_Settings_Demo_Export::convert_data_to_xml();
            die();
        }


        /**
         * Get data plugin name and install plugin from wordpress repository
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public function demo_import()
        {
            // Get xml url for importing demo (sanitize_text_field خودش unslash می‌زنه)
            $xml_url = isset($_POST['data_xml']) ? esc_url_raw($_POST['data_xml']) : '';

            // Import demo
            Irantheme_Settings_Demo_Import::import_demo_from_xml_url($xml_url);
            echo 'Imported successfully!';
            die();
        }


        /**
         * Get data plugin name and install plugin from wordpress repository
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public function demo_page_export()
        {
            // Get xml url for importing demo
            $page_id = isset($_POST['page_id']) ? absint($_POST['page_id']) : 0;
            $extra_data = isset($_POST['extra_data']) && $_POST['extra_data'] ? true : false;
            echo Irantheme_Settings_Demo_Export::convert_data_to_xml($page_id, $extra_data);
            die();
        }


        /**
         * Get data plugin name and install plugin from wordpress repository
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public function demo_page_import()
        {
            // Verify nonce (wp_verify_nonce خودش slashes رو مدیریت می‌کنه)
            if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'irantheme_nonce')) {
                wp_send_json_error('Nonce verification failed');
            }

            // Check capabilities
            if (!current_user_can('edit_posts')) {
                wp_send_json_error('Permission denied');
            }

            // Check if file was uploaded
            if (!isset($_FILES['xml_file']) || $_FILES['xml_file']['error'] !== UPLOAD_ERR_OK) {
                wp_send_json_error('No file uploaded or upload error');
            }

            $file = $_FILES['xml_file'];

            // Validate file type
            $filetype = wp_check_filetype($file['name']);
            if ($filetype['ext'] !== 'xml') {
                wp_send_json_error('Invalid file type. Please upload an XML file.');
            }

            // Process the XML file
            try {
                // Read file from tmp_name (local file, no unslash needed)
                $xml_content = file_get_contents($file['tmp_name']);

                // Basic XML validation
                if (strpos($xml_content, '<?xml') === false) {
                    wp_send_json_error('File is not a valid XML document');
                }

                // Disable external entity loading for security
                $previous_use_errors = libxml_use_internal_errors(true);
                $xml = simplexml_load_string($xml_content, 'SimpleXMLElement', LIBXML_NONET);
                libxml_clear_errors();
                libxml_use_internal_errors($previous_use_errors);

                if ($xml === false) {
                    wp_send_json_error('Invalid XML format');
                }

                // Get page ID if provided (absint خودش مدیریت می‌کنه)
                $page_id = isset($_POST['page_id']) ? absint($_POST['page_id']) : 0;

                // Import page
                Irantheme_Settings_Demo_Import::import_page_from_xml($xml, $page_id);

                wp_send_json_success([
                    'message' => 'XML processed successfully',
                    'page_id' => $page_id,
                ]);
            } catch (\Exception $e) {
                wp_send_json_error('Error processing XML: ' . $e->getMessage());
            }
            die();
        }


        /**
         * Create default posts
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public function post_default()
        {
            Irantheme_Section_Default::get_default_ids_post_box(12);
            echo 'Default post is added!';
            die();
        }


        /**
         * Set irantheme sidebar default values with initial data from specific url
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public function sidebar_default()
        {
            $xml_url = 'https://irantheme.com/demo/wp-admin/irantheme_xml_demo_export.xml';
            // Get xml file
            $xml = simplexml_load_file($xml_url);
            Irantheme_Settings_Demo_Import::import_sidebar_from_xml($xml);
            echo 'Default sidebar is installed!';
            die();
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
         * Get categories of demos
         * @param void
         * @return array
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_categories_demo_items()
        {
            $categories = array(
                array(
                    'title' => 'همه',
                    'icon' => 'fa-light fa-ball-pile',
                    'data_cate' => 'all',
                    'status' => true,
                ),
                array(
                    'title' => 'فروشگاهی',
                    'icon' => 'fa-light fa-cart-shopping',
                    'data_cate' => 'shop',
                    'status' => false,
                ),
                array(
                    'title' => 'کسب و کار',
                    'icon' => 'fa-light fa-sack-dollar',
                    'data_cate' => 'business',
                    'status' => false,
                ),
                array(
                    'title' => 'شرکتی',
                    'icon' => 'fa-light fa-car-building',
                    'data_cate' => 'corporate',
                    'status' => false,
                ),
                array(
                    'title' => 'وبلاگ',
                    'icon' => 'fa-light fa-file-lines',
                    'data_cate' => 'blog',
                    'status' => false,
                ),
                array(
                    'title' => 'شخصی',
                    'icon' => 'fa-light fa-person',
                    'data_cate' => 'personal',
                    'status' => false,
                ),
                array(
                    'title' => 'پزشکی',
                    'icon' => 'fa-light fa-truck-medical',
                    'data_cate' => 'medical',
                    'status' => false,
                ),
                array(
                    'title' => 'رستوران و کافه',
                    'icon' => 'fa-light fa-mug-saucer',
                    'data_cate' => 'restaurant',
                    'status' => false,
                ),
                array(
                    'title' => 'تک صفحه ای',
                    'icon' => 'fa-light fa-cabinet-filing',
                    'data_cate' => 'landing',
                    'status' => false,
                ),
                array(
                    'title' => 'نمونه کار',
                    'icon' => 'fa-light fa-address-card',
                    'data_cate' => 'portfolio',
                    'status' => false,
                ),
                array(
                    'title' => 'مجله و خبری',
                    'icon' => 'fa-light fa-newspaper',
                    'data_cate' => 'news',
                    'status' => false,
                ),
            );
            return $categories;
        }


        /**
         * Get demo items
         * @param void
         * @return array
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_demo_items()
        {
            $demos = array(
                array(
                    'title' => 'فروشگاهی وسایل دیجیتال',
                    'description' => 'مناسب برای فروشگاه های دیجیتال شامل موبایل، لوازم جانبی، لپ تاپ، لوازم خانگی و ...',
                    'image_url' => 'https://irantheme.com/wp-content/uploads/2026/06/demo-shop-digital.webp',
                    'categories' => 'فروشگاهی,کسب و کار',
                    'data_cate' => 'shop,business',
                    'xml_url' => 'https://irantheme.com/demo/shop-digital/wp-admin/irantheme_xml_demo_export.xml',
                    'preview_url' => 'https://irantheme.com/demo/shop-digital/'
                ),
            );
            return $demos;
        }


        /**
         * Add details settings and options settings to custom wp admin menu
         * @return void
         */
        public function callback_panel()
        {
            // Get header parent
            $this->parent->get_header($this->page_title, $this->description, self::get_menu_slug(), 'demo');
?>
            <div class="irantheme-settings-demo">
                <div class="irantheme-settings-demo-wrapper">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-xl-2 col-lg-3 p-0">
                                <!-- Categories aside -->
                                <aside class="irantheme-settings-demo-categories">
                                    <!-- Export -->
                                    <div class="irantheme-settings-demo-export-holder mb-2" style="display:none;">
                                        <div class="row">
                                            <div class="col-lg-12 mb-1">
                                                <button class="irantheme-button-default irantheme-button-dark irantheme-button-block irantheme-settings-demo-export"><?php _e('برون ریزی'); ?></button>
                                            </div>
                                            <div class="col-lg-12 mb-1">
                                                <button class="irantheme-button-default irantheme-button-primary irantheme-button-block irantheme-settings-demo-sidebar-default"><?php _e('سایدبار پیش فرض'); ?></button>
                                            </div>
                                            <div class="col-lg-12 mb-1">
                                                <button class="irantheme-button-default irantheme-button-secondary irantheme-button-block irantheme-settings-demo-post-default"><?php _e('مطالب پیش فرض'); ?></button>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Heading -->
                                    <div class="irantheme-settings-demo-categories-heading">
                                        <h2><?php esc_html_e('دسته بندی'); ?></h2>
                                        <i class="fa-light fa-sidebar"></i>
                                    </div>
                                    <!-- List categories -->
                                    <ul class="irantheme-settings-demo-categories-list">
                                        <?php foreach (self::get_categories_demo_items() as $cate_item) : ?>
                                            <?php
                                            $active_class = '';
                                            if ($cate_item['status']) {
                                                $active_class = 'active';
                                            }
                                            ?>
                                            <li class="irantheme-settings-demo-categories-list-item">
                                                <a href="#" class="irantheme-button-default irantheme-settings-demo-categories-list-item-link <?php echo esc_attr($active_class); ?>" data-cate="<?php echo esc_attr($cate_item['data_cate']); ?>">
                                                    <i class="<?php echo esc_attr($cate_item['icon']); ?>"></i>
                                                    <cite><?php echo esc_html($cate_item['title']); ?></cite>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </aside>
                            </div>
                            <div class="col-xl-10 col-lg-9 p-0">
                                <!-- Demo items -->
                                <div class="irantheme-settings-demo-items">
                                    <div class="container-fluid">
                                        <?php Irantheme_Settings::get_notification('info', 'نکته : قبل از نصب دموی جدید، اطمینان حاصل کنید که از آخرین نسخه محصول استفاده می کنید؛ در غیر اینصورت نسبت به بروزرسانی اقدام نمایید.', 'm-0 mb-3 d-flex') ?>
                                        <div class="row">
                                            <?php foreach (self::get_demo_items() as $demo_item): ?>
                                                <div class="col-xxl-3 col-xl-4 col-lg-6 col-sm-6 mb-4 irantheme-settings-demo-items-item-holder" data-cate="<?php echo esc_attr($demo_item['data_cate']); ?>">
                                                    <!-- Demo item -->
                                                    <article class="irantheme-settings-demo-items-item">
                                                        <!-- Image -->
                                                        <div class="irantheme-settings-demo-items-item-image">
                                                            <img src="<?php echo esc_url_raw($demo_item['image_url']); ?>" alt="<?php echo esc_attr($demo_item['title']); ?>">
                                                            <!-- Note -->
                                                            <?php Irantheme_Settings::get_notification('info', 'ممکن است دقایقی طول بکشد، لطفا شکیبا باشید ...', 'm-0 irantheme-settings-demo-items-item-pending', 'display:none;') ?>
                                                            <!-- Note -->
                                                            <?php Irantheme_Settings::get_notification('success', 'دمو با موفقیت نصب شد.', 'm-0 irantheme-settings-demo-items-item-success', 'display:none;') ?>
                                                        </div>
                                                        <!-- Content -->
                                                        <div class="irantheme-settings-demo-items-item-content">
                                                            <?php
                                                            $categories = explode(',', $demo_item['categories']);
                                                            ?>
                                                            <?php if ($categories): ?>
                                                                <!-- Categories -->
                                                                <div class="irantheme-settings-demo-items-item-content-categories">
                                                                    <?php foreach ($categories as $category): ?>
                                                                        <span><?php _e($category); ?></span>
                                                                    <?php endforeach; ?>
                                                                </div>
                                                            <?php endif; ?>
                                                            <!-- Heading -->
                                                            <div class="irantheme-settings-demo-items-item-content-heading"><?php _e($demo_item['title']); ?></div>
                                                            <!-- Description -->
                                                            <p class="irantheme-settings-demo-items-item-content-description"><?php _e($demo_item['description']); ?></p>
                                                            <!-- Triggers -->
                                                            <div class="irantheme-settings-demo-items-item-content-triggers">
                                                                <!-- Install demo -->
                                                                <button type="button" class="irantheme-button-default irantheme-settings-demo-items-item-content-triggers-trigger irantheme-settings-demo-items-item-content-triggers-trigger-install" data-xml="<?php echo esc_url_raw($demo_item['xml_url']) ?>" data-permission="<?php echo esc_attr(Irantheme_Settings_Cleaner::get_permission_url()); ?>">
                                                                    <cite><?php esc_html_e('نصب دمو'); ?></cite>
                                                                    <i class="fa-light fa-upload irantheme-normal-icon"></i>
                                                                    <i id="irantheme-spinner-alt" class="irantheme-animate-rotate" style="display: none;"></i>
                                                                </button>
                                                                <!-- Preview demo -->
                                                                <a href="<?php echo esc_url_raw($demo_item['preview_url']); ?>" class="irantheme-button-default irantheme-settings-demo-items-item-content-triggers-trigger irantheme-settings-demo-items-item-content-triggers-trigger-preview" target="_blank">
                                                                    <cite><?php esc_html_e('پیش نمایش'); ?></cite>
                                                                    <i class="fa-light fa-tv irantheme-normal-icon"></i>
                                                                    <i id="irantheme-spinner-alt" class="irantheme-animate-rotate" style="display: none;"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </article>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<?php
            // Get footer parent
            $this->parent->get_footer('demo');
        }
    }
}
