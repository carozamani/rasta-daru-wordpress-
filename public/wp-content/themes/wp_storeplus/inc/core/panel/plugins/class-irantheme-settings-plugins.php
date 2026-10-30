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
if (!class_exists('Irantheme_Settings_Plugins')) {
    class Irantheme_Settings_Plugins
    {
        // Parent slug
        private $parent;
        // Submenu title
        public $menu_title;
        // Page title
        private $page_title;
        // Menu slug
        public $menu_slug;
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
            return 'irantheme_settings_plugins';
        }


        /**
         * Get data plugin name and install plugin from wordpress repository
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public function plugin_installations()
        {
            // Get plugin name
            $plugin_name = $_POST['plugin_name'];
            self::install_plugin($plugin_name);
            die();
        }


        /**
         * Install plugin
         * @param string
         * @return bool
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function install_plugin($plugin_name)
        {
            // Include required libs for installation
            require_once(ABSPATH . 'wp-admin/includes/plugin-install.php');
            require_once(ABSPATH . 'wp-admin/includes/class-wp-upgrader.php');
            require_once(ABSPATH . 'wp-admin/includes/class-wp-ajax-upgrader-skin.php');
            require_once(ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php');

            // Get Plugin Info
            $api = plugins_api(
                'plugin_information',
                array(
                    'slug' => $plugin_name,
                    'fields' => array(
                        'short_description' => false,
                        'sections' => false,
                        'requires' => false,
                        'rating' => false,
                        'ratings' => false,
                        'downloaded' => false,
                        'last_updated' => false,
                        'added' => false,
                        'tags' => false,
                        'compatibility' => false,
                        'homepage' => false,
                        'donate_link' => false,
                    ),
                )
            );
            $skin     = new \WP_Ajax_Upgrader_Skin();
            $upgrader = new \Plugin_Upgrader($skin);
            return $upgrader->install($api->download_link);
        }


        /**
         * Delete plugin with plugin path
         * @param string
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public function plugin_delete()
        {
            // Get plugin name
            $plugin_name = $_POST['plugin_name'];
            $plugins = self::get_plugins_attributes();
            delete_plugins(array($plugins[$plugin_name])); // Array is necessary
            die();
        }


        /**
         * Activate plugin with plugin path
         * @param string
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public function plugin_activate()
        {
            // Get plugin name
            $plugin_name = $_POST['plugin_name'];
            $plugins = self::get_plugins_attributes();
            activate_plugin($plugins[$plugin_name]);
            die();
        }


        /**
         * Inactivate plugin with plugin path
         * @param string
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public function plugin_deactivate()
        {
            // Get plugin name
            $plugin_name = $_POST['plugin_name'];
            $plugins = self::get_plugins_attributes();
            deactivate_plugins($plugins[$plugin_name]);
            die();
        }


        /**
         * Activate plugin with plugin path
         * @param string
         * @return bool
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function is_plugin_installed($slug)
        {
            $plugins = get_plugins();
            foreach ($plugins as $plugin_file => $plugin_data) {
                $plugin_slug = dirname($plugin_file);
                if ($plugin_slug == $slug) {
                    return true;
                }
            }
            return false;
        }


        /**
         * Check plugin is activate or no
         * @param string
         * @return bool
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function is_plugin_activate($plugin_name)
        {
            include_once(ABSPATH . 'wp-admin/includes/plugin.php');
            $plugins = self::get_plugins_attributes();
            return is_plugin_active($plugins[$plugin_name]);
        }


        /**
         * Return plugins attributes
         * @param void
         * @return array
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_plugins_attributes()
        {
            return array(
                'woocommerce' => 'woocommerce/woocommerce.php',
                'contact-form-7' => 'contact-form-7/wp-contact-form-7.php',
                'gtranslate' => 'gtranslate/gtranslate.php',
                'wordpress-seo' => 'wordpress-seo/wp-seo.php',
                'classic-editor' => 'classic-editor/classic-editor.php',
                'really-simple-ssl' => 'really-simple-ssl/rlrsssl-really-simple-ssl.php',
                'wp-mail-smtp' => 'wp-mail-smtp/wp_mail_smtp.php',
                'wordfence' => 'wordfence/wordfence.php',
                'litespeed-cache' => 'litespeed-cache/litespeed-cache.php',
                'duplicator' => 'duplicator/duplicator.php',
                'persian-woocommerce' => 'persian-woocommerce/woocommerce-persian.php',
                'seo-by-rank-math' => 'seo-by-rank-math/rank-math.php',
            );
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
         * Return plugins information (Fields)
         * @param void
         * @return array
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_plugins_fields()
        {
            // Fields of plugins
            $plugins_fields = array(
                // Woocommerce
                array(
                    'category' => __('فروشگاهی'),
                    'detail-url' => esc_url_raw('https://wordpress.org/plugins/woocommerce/'),
                    'title' => __('ووکامرس (Woocommerce)'),
                    'name' => __('WooCommerce'),
                    'description' => __('در صورت استفاده از سیستم، امکانات و بخش های فروشگاهی استورپلاس، شما باید افزونه ووکامرس را نصب کرده باشید. در غیر این صورت نیازی به نصب این افزونه نیست.'),
                    'slug' => esc_attr('woocommerce'),
                    'img' => esc_url_raw('https://ps.w.org/woocommerce/assets/banner-1544x500.png?rev=3000842'),
                ),
                // Persian Woocommerce
                array(
                    'category' => __('فروشگاهی'),
                    'detail-url' => esc_url_raw('https://wordpress.org/plugins/persian-woocommerce/'),
                    'title' => __('ووکامرس فارسی (Persian Woocommerce)'),
                    'name' => __('Persian Woocommerce'),
                    'description' => __('ووکامرس فارسی قادر است بروزترین ترجمه فارسی ووکامرس را همراه با جعبه ابزار حرفه ای و بومی (مثل واحدهای پولی ایران، لیست شهرها و درگاه‌های پرداخت و…) به شما ارائه کند. همچنین بدون نیاز به نصب افزونه‌های اضافی قادر هستید تاریخ وردپرس و ووکامرس خود را به بهترین نحو ممکن و کاملا سازگار با آخرین اصول سئو، شمسی کنید.'),
                    'slug' => esc_attr('persian-woocommerce'),
                    'img' => esc_url_raw('https://ps.w.org/persian-woocommerce/assets/banner-772x250.png?rev=2639497'),
                ),
                // Yoast SEO
                array(
                    'category' => __('سئو'),
                    'detail-url' => esc_url_raw('https://wordpress.org/plugins/wordpress-seo/'),
                    'title' => __('یوآست سئو (Yoast SEO)'),
                    'name' => __('Yoast SEO'),
                    'description' => __('با استفاده از این افزونه می توانید سئوی محتوای خود را بهینه کنید، این افزونه با کنترل نوشته ها راهنمای مناسبی برای سئوی بهتر ارائه می کند.'),
                    'slug' => esc_attr('wordpress-seo'),
                    'img' => esc_url_raw('https://ps.w.org/wordpress-seo/assets/banner-1544x500.png?rev=2643727'),
                ),
                // Rank Math SEO
                array(
                    'category' => __('سئو'),
                    'detail-url' => esc_url_raw('https://wordpress.org/plugins/seo-by-rank-math/'),
                    'title' => __('رنک مث سئو (Rank Math SEO)'),
                    'name' => __('WP Mail SMTP'),
                    'description' => __('افزونه رنک مث یکی از پلاگین های موفق در حوزه سئو و کیفیت محتوای وب شماست که قدرت کمک جهت بهره وری و افزایش بازدید سایت شما را دارا می باشد.'),
                    'slug' => esc_attr('seo-by-rank-math'),
                    'img' => esc_url_raw('https://ps.w.org/seo-by-rank-math/assets/banner-1544x500.png?rev=2639678'),
                ),
                // Contact form 7
                array(
                    'category' => __('ارتباطات'),
                    'detail-url' => esc_url_raw('https://wordpress.org/plugins/contact-form-7/'),
                    'title' => __('فرم تماس ۷ (Contact Form 7)'),
                    'name' => __('Contact Form 7'),
                    'description' => __('در صورتی که علاقمند به استفاده فرم های تماس ۷ و راه ارتباطی بین خودتان و کاربر هستید می توانید این افزونه را نصب نمایید.'),
                    'slug' => esc_attr('contact-form-7'),
                    'img' => esc_url_raw('https://ps.w.org/contact-form-7/assets/banner-1544x500.png?rev=860901'),
                ),
                // Classic editor
                array(
                    'category' => __('محتوا'),
                    'detail-url' => esc_url_raw('https://wordpress.org/plugins/classic-editor/'),
                    'title' => __('ویرایشگر کلاسیک (Classic Editor)'),
                    'name' => __('Classic Editor'),
                    'description' => __('بسیاری از کاربران وردپرس به دلیل پیچیدگی ویرایشگر مدرن و جدید وردپرس احساس راحتی در تولید محتوا ندارند. با استفاده از این افزونه می توانید تولید محتوا را ساده تر کنید.'),
                    'slug' => esc_attr('classic-editor'),
                    'img' => esc_url_raw('https://ps.w.org/classic-editor/assets/banner-1544x500.png?rev=1998671'),
                ),
                // Realy simple ssl
                array(
                    'category' => __('امنیت'),
                    'detail-url' => esc_url_raw('https://wordpress.org/plugins/really-simple-ssl/'),
                    'title' => __('اس اس ال واقعا ساده (Really Simple SSL)'),
                    'name' => __('Really Simple SSL'),
                    'description' => __('اگر دامنه شما هنوز تبدیل به یک دامنه امن نشده به راحتی می توانید با استفاده از این افزونه و راهنمایی های آن دامنه خود را به یک اس اس ال امن تجهیز کنید.'),
                    'slug' => esc_attr('really-simple-ssl'),
                    'img' => esc_url_raw('https://ps.w.org/really-simple-ssl/assets/banner-1544x500.png?rev=3100992'),
                ),
                // WP mail smtp
                array(
                    'category' => __('ارتباطات'),
                    'detail-url' => esc_url_raw('https://wordpress.org/plugins/wp-mail-smtp/'),
                    'title' => __('ایمیل وردپرس اس ام تی پی (WP Mail SMTP)'),
                    'name' => __('WP Mail SMTP'),
                    'description' => __('یکی از افزونه های کلیدی جهت پیکربندی ارسال ایمیل از طریق وردپرس این افزونه می باشد. در صورتی که نیازمند یک راه حل ساده جهت ارسال ایمیل از طریق وردپرس به خود و کاربرانتان می باشید باید تنظیمات SMTP را تنظیم کنید.'),
                    'slug' => esc_attr('wp-mail-smtp'),
                    'img' => esc_url_raw('https://ps.w.org/wp-mail-smtp/assets/banner-1544x500.png?rev=3206423'),
                ),
                // Wordfence
                array(
                    'category' => __('امنیت'),
                    'detail-url' => esc_url_raw('https://wordpress.org/plugins/wordfence/'),
                    'title' => __('امنیت وردفنس (Wordfence Security)'),
                    'name' => __('wordfence'),
                    'description' => __('وردفنس یک پلاگین امنیتی محبوب وردپرس است که از سایت شما در برابر هک، بد افزار، حملات Brute Force و سایر تهدیدات امنیتی محافظت می کند. این افزونه دارای فایروال قدرتمند، اسکنر بدافزار، و سیستم لاگین امن است.'),
                    'slug' => esc_attr('wordfence'),
                    'img' => esc_url_raw('https://ps.w.org/wordfence/assets/banner-1544x500.jpg?rev=2124102'),
                ),
                // Duplicator
                array(
                    'category' => __('پشتیبان'),
                    'detail-url' => esc_url_raw('https://wordpress.org/plugins/duplicator/'),
                    'title' => __('داپلیکیتور (Duplicator)'),
                    'name' => __('Duplicator'),
                    'description' => __('داپلیکیتور روشی ساده برای انتقال سایت های وردپرس، ایجاد پشتیبان های قابل اعتماد یا کلون کردن یک سایت برای اسقرار محتوای شما ارائه می دهد.'),
                    'slug' => esc_attr('duplicator'),
                    'img' => esc_url_raw('https://ps.w.org/duplicator/assets/banner-1544x500.png?rev=2906985'),
                ),
                // LiteSpeed Cache
                array(
                    'category' => __('کارایی'),
                    'detail-url' => esc_url_raw('https://wordpress.org/plugins/litespeed-cache/'),
                    'title' => __('کش لایت اسپید (LiteSpeed Cache)'),
                    'name' => __('LiteSpeed Cache'),
                    'description' => __('LiteSpeed ​​Cache برای وردپرس (LSCWP) یک افزونه جامع برای افزایش سرعت سایت است که دارای یک حافظه پنهان اختصاصی در سطح سرور و مجموعه‌ای از ویژگی‌های بهینه‌سازی است.'),
                    'slug' => esc_attr('litespeed-cache'),
                    'img' => esc_url_raw('https://ps.w.org/litespeed-cache/assets/banner-1544x500.png?rev=2554181'),
                ),
                // Gtranslator
                array(
                    'category' => __('ارتباطات'),
                    'detail-url' => esc_url_raw('https://wordpress.org/plugins/gtranslate/'),
                    'title' => __('ترجمه وردپرس با جی ترنسلیت (Translate WordPress with GTranslate)'),
                    'name' => __('GTranslate'),
                    'description' => __('با استفاده از این پلاگین می توانید به راحتی وردپرس خود را برای کاربرانتان چند زبانه کنید. قالب استورپلاس به راحتی با این افزونه هماهنگ خواهد شد.'),
                    'slug' => esc_attr('gtranslate'),
                    'img' => esc_url_raw('https://ps.w.org/gtranslate/assets/banner-1544x500.png?rev=2034820'),
                ),
            );
            return $plugins_fields;
        }


        /**
         * Add details settings and options settings to custom wp admin menu
         * @return void
         */
        public function callback_panel()
        {
            // Get header parent
            $this->parent->get_header($this->page_title, $this->description, self::get_menu_slug(), 'plugins');
?>
            <div class="irantheme-settings-plugins">
                <div class="irantheme-settings-plugins-wrapper">
                    <div class="row">
                        <?php foreach (self::get_plugins_fields() as $plugin_fields) :
                        ?>
                            <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
                                <!-- Plugin box -->
                                <div class="irantheme-settings-plugins-box">
                                    <!-- Title bar -->
                                    <div class="irantheme-settings-plugins-box-titlebar">
                                        <!-- Category -->
                                        <div class="irantheme-settings-plugins-box-titlebar-cate"><?php echo $plugin_fields['category']; ?></div>
                                        <!-- Page source -->
                                        <div class="irantheme-settings-plugins-box-titlebar-source">
                                            <a href="<?php echo $plugin_fields['detail-url']; ?>" target="_blank">
                                                <?php _e('جزئیات بیشتر', IRANTHEME_TEXTDOMAIN); ?>
                                                <i class="iconsax" icon-name="arrow-left"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <!-- Heading -->
                                    <div class="irantheme-settings-plugins-box-heading"><?php echo $plugin_fields['title']; ?></div>
                                    <!-- Description -->
                                    <div class="irantheme-settings-plugins-box-desc"><?php echo $plugin_fields['description']; ?></div>
                                    <!-- Triggers -->
                                    <div class="irantheme-settings-plugins-box-triggers">
                                        <?php if (self::is_plugin_installed($plugin_fields['slug'])): ?>
                                            <?php if (self::is_plugin_activate($plugin_fields['slug'])) : ?>
                                                <!-- Button inactivation -->
                                                <button type="button" class="irantheme-button-default irantheme-settings-plugins-box-triggers-button irantheme-settings-plugins-box-triggers-button-inactivate" data-plugin="<?php echo $plugin_fields['slug']; ?>" data-success="<?php echo esc_attr('با موفقیت غیر فعال شد!'); ?>" data-installation="<?php echo esc_attr('در حال غیر فعال کردن'); ?>">
                                                    <i class="fa-light fa-plug-circle-xmark"></i>
                                                    <cite><?php esc_html_e('غیر فعال کردن'); ?></cite>
                                                    <span id="irantheme-spinner-alt" class="irantheme-animate-rotate hide"></span>
                                                </button>
                                            <?php else: ?>
                                                <!-- Button activation -->
                                                <button type="button" class="irantheme-button-default irantheme-settings-plugins-box-triggers-button irantheme-settings-plugins-box-triggers-button-activate" data-plugin="<?php echo $plugin_fields['slug']; ?>" data-success="<?php echo esc_attr('با موفقیت فعال شد!'); ?>" data-installation="<?php echo esc_attr('در حال فعال سازی'); ?>">
                                                    <i class="fa-light fa-plug"></i>
                                                    <cite><?php esc_html_e('فعال سازی'); ?></cite>
                                                    <span id="irantheme-spinner-alt" class="irantheme-animate-rotate hide"></span>
                                                </button>
                                                <!-- Button delete -->
                                                <button type="button" class="irantheme-button-default irantheme-settings-plugins-box-triggers-button irantheme-settings-plugins-box-triggers-button-delete" data-plugin="<?php echo $plugin_fields['slug']; ?>" data-success="<?php echo esc_attr('با موفقیت حذف شد!'); ?>" data-installation="<?php echo esc_attr('در حال حذف کردن'); ?>">
                                                    <i class="fa-light fa-trash-xmark"></i>
                                                    <cite><?php esc_html_e('حذف کردن'); ?></cite>
                                                    <span id="irantheme-spinner-alt" class="irantheme-animate-rotate hide"></span>
                                                </button>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <!-- Button installation -->
                                            <button type="button" class="irantheme-button-default irantheme-settings-plugins-box-triggers-button irantheme-settings-plugins-box-triggers-button-install" data-plugin="<?php echo $plugin_fields['slug']; ?>" data-success="<?php echo esc_attr('با موفقیت نصب شد!'); ?>" data-installation="<?php echo esc_attr('در حال نصب'); ?>">
                                                <i class="fa-light fa-grid-2-plus"></i>
                                                <cite><?php esc_html_e('نصب پلاگین'); ?></cite>
                                                <span id="irantheme-spinner-alt" class="irantheme-animate-rotate hide"></span>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                    <!-- Thumbnail -->
                                    <div class="irantheme-settings-plugins-box-thumbnail">
                                        <img src="<?php echo $plugin_fields['img']; ?>" alt="<?php echo $plugin_fields['title']; ?>">
                                    </div>
                                </div>
                            </div>
                        <?php
                        endforeach; ?>
                    </div>
                </div>
            </div>
<?php
            // Get footer parent
            $this->parent->get_footer('plugins');
        }
    }
}
