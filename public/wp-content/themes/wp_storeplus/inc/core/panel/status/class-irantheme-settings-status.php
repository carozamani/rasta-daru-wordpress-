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
if (!class_exists('Irantheme_Settings_Status')) {
    class Irantheme_Settings_Status
    {
        // Parent slug
        private $parent;
        // Submenu title
        public $menu_title;
        // Page title
        private $page_title;
        // Menu slug
        public $menu_slug;
        // File path and file name
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

            // Define main menu in wp admin
            add_action('admin_menu', array($this, 'add_submenu_page'));
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
         * Get menu slug of current class
         * @param void
         * @return string
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_menu_slug()
        {
            return 'irantheme_settings_status';
        }


        /**
         * Add details settings and options settings to custom wp admin menu
         * @return void
         */
        public function callback_panel()
        {
            // Get header parent
            $this->parent->get_header($this->page_title, $this->description, self::get_menu_slug(), 'status');
?>
            <div class="irantheme-settings-status">
                <div class="row">
                    <!-- Side -->
                    <div class="col-lg-4 col-sm-12 irantheme-settings-status-side-holder">
                        <div class="irantheme-settings-status-side">
                            <?php if ($this->parent->verify) : ?>
                                <div class="irantheme-settings-status-side-bold mb-5">
                                    <?php _e('محصول فعال است.') ?>
                                </div>
                                <div class="irantheme-settings-status-side-icon mb-5">
                                    <i class="fa-light fa-badge-check success"></i>
                                </div>
                                <div class="irantheme-settings-status-side-heading text-center mb-4">
                                    <?php _e('محصول شما با موفقیت فعال شده') ?>
                                </div>
                                <div class="irantheme-settings-status-side-description text-center mb-3">
                                    <?php _e('دسترسی شما به تمامی امکانات و ویژگی های محصول باز است.') ?>
                                </div>
                                <div class="irantheme-settings-status-side-description text-center mb-3">
                                    <?php _e('از اینکه به تلاش توسعه دهنده احترام گذاشته و این محصول را به صورت قانونی خریداری کرده اید بسیار سپاسگزاریم!') ?>
                                </div>
                            <?php else: ?>
                                <div class="irantheme-settings-status-side-bold mb-5">
                                    <?php _e('محصول قفل است!') ?>
                                </div>
                                <div class="irantheme-settings-status-side-icon mb-5">
                                    <i class="fa-light fa-seal-exclamation failure"></i>
                                </div>
                                <div class="irantheme-settings-status-side-heading text-center mb-4">
                                    <?php _e('متاسفانه محصول شما فعال نیست!') ?>
                                </div>
                                <div class="irantheme-settings-status-side-description text-center mb-3">
                                    <?php _e('دسترسی شما به امکانات محصول به طور قابل توجهی محدود شده.') ?>
                                </div>
                                <div class="irantheme-settings-status-side-description text-center mb-3">
                                    <?php _e('جهت دسترسی به تمامی امکانات محصول، لطفا محصول را به صورت قانونی خریداری نمایید.') ?>
                                </div>
                                <div class="irantheme-settings-status-side-description mb-3">
                                    <a href="https://www.rtl-theme.com/storeplus-wordpress-theme/" class="irantheme-button-default irantheme-button-medium irantheme-button-secondary" target="_blank">
                                        <i class="fa-light fa-check check"></i>
                                        <cite><?php _e('خرید قانونی محصول'); ?></cite>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <!-- Content -->
                    <div class="col-lg-8 col-sm-12 irantheme-settings-status-holder">
                        <div class="irantheme-settings-status-main">
                            <!-- Titlebar -->
                            <div class="irantheme-settings-status-titlebar">
                                <h4 class="irantheme-settings-status-titlebar-heading mb-4"><?php _e('وضعیت سیستم'); ?></h4>
                                <p class="irantheme-settings-status-titlebar-description"><?php _e('در این قسمت شما یک اطلاعات کلی از سیستم مانند:  نسخه محصول، وضعیت فعال سازی محصول و داده های کلی سرور را مشاهده می کنید. در صورت مشاهده هر گونه مشکل می توانید با پشتیبانی خود تماس حاصل فرمایید.'); ?></p>
                            </div>
                            <!-- List chart -->
                            <div class="irantheme-settings-status-main-chart-list">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <!-- Item -->
                                        <div class="irantheme-settings-status-main-chart-list-item">
                                            <!-- Icon -->
                                            <div class="irantheme-settings-status-main-chart-list-item-icon">
                                                <i class="iconsax" icon-name="code-1"></i>
                                            </div>
                                            <!-- Content -->
                                            <div class="irantheme-settings-status-main-chart-list-item-content">
                                                <h3 class="irantheme-settings-status-main-chart-list-item-content-heading">
                                                    <?php _e('نسخه محصول'); ?>
                                                </h3>
                                                <p class="irantheme-settings-status-main-chart-list-item-content-desc">
                                                    <?php _e('ورژن فعلی محصول : ') . esc_html(IRANTHEME_VERSION); ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <!-- Item -->
                                        <div class="irantheme-settings-status-main-chart-list-item">
                                            <!-- Icon -->
                                            <div class="irantheme-settings-status-main-chart-list-item-icon">
                                                <i class="iconsax" icon-name="shield-lock"></i>
                                            </div>
                                            <!-- Content -->
                                            <div class="irantheme-settings-status-main-chart-list-item-content">
                                                <h3 class="irantheme-settings-status-main-chart-list-item-content-heading">
                                                    <?php _e('وضعیت محصول'); ?>
                                                </h3>
                                                <p class="irantheme-settings-status-main-chart-list-item-content-desc">
                                                    <?php if ($this->parent->verify) : ?>
                                                        <span class="irantheme-color-primary"><?php _e('فعال شده'); ?></span>
                                                    <?php else : ?>
                                                        <span class="irantheme-color-secondary"><?php _e('غیر فعال'); ?></span>
                                                    <?php endif; ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <!-- Item -->
                                        <div class="irantheme-settings-status-main-chart-list-item">
                                            <!-- Icon -->
                                            <div class="irantheme-settings-status-main-chart-list-item-icon">
                                                <i class="iconsax" icon-name="3d-cube"></i>
                                            </div>
                                            <!-- Content -->
                                            <div class="irantheme-settings-status-main-chart-list-item-content">
                                                <h3 class="irantheme-settings-status-main-chart-list-item-content-heading">
                                                    <?php _e('وضعیت هسته'); ?>
                                                </h3>
                                                <p class="irantheme-settings-status-main-chart-list-item-content-desc">
                                                    <?php if ($this->parent->verify) : ?>
                                                        <span class="irantheme-color-primary"><?php _e('هسته نصب شده'); ?></span>
                                                    <?php else : ?>
                                                        <span class="irantheme-color-secondary"><?php _e('عدم نصب هسته'); ?></span>
                                                    <?php endif; ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <!-- Item -->
                                        <div class="irantheme-settings-status-main-chart-list-item">
                                            <!-- Icon -->
                                            <div class="irantheme-settings-status-main-chart-list-item-icon">
                                                <i class="iconsax" icon-name="card-edit"></i>
                                            </div>
                                            <!-- Content -->
                                            <div class="irantheme-settings-status-main-chart-list-item-content">
                                                <h3 class="irantheme-settings-status-main-chart-list-item-content-heading">
                                                    <?php _e('مسیر استایل ادمین'); ?>
                                                </h3>
                                                <p class="irantheme-settings-status-main-chart-list-item-content-desc">
                                                    ../wp-content/themes/wp_storeplus/assets/css/
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <!-- Item -->
                                        <div class="irantheme-settings-status-main-chart-list-item">
                                            <!-- Icon -->
                                            <div class="irantheme-settings-status-main-chart-list-item-icon">
                                                <i class="iconsax" icon-name="picture-edit"></i>
                                            </div>
                                            <!-- Content -->
                                            <div class="irantheme-settings-status-main-chart-list-item-content">
                                                <h3 class="irantheme-settings-status-main-chart-list-item-content-heading">
                                                    <?php _e('مسیر استایل سایت'); ?>
                                                </h3>
                                                <p class="irantheme-settings-status-main-chart-list-item-content-desc">
                                                    ../wp-content/themes/wp_storeplus/build/
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <!-- Item -->
                                        <div class="irantheme-settings-status-main-chart-list-item">
                                            <!-- Icon -->
                                            <div class="irantheme-settings-status-main-chart-list-item-icon">
                                                <i class="fa-brands fa-php"></i>
                                            </div>
                                            <!-- Content -->
                                            <div class="irantheme-settings-status-main-chart-list-item-content">
                                                <h3 class="irantheme-settings-status-main-chart-list-item-content-heading">
                                                    <?php _e('نسخه PHP'); ?>
                                                </h3>
                                                <p class="irantheme-settings-status-main-chart-list-item-content-desc">
                                                    <?php echo phpversion(); ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <!-- Item -->
                                        <div class="irantheme-settings-status-main-chart-list-item">
                                            <!-- Icon -->
                                            <div class="irantheme-settings-status-main-chart-list-item-icon">
                                                <i class="iconsax" icon-name="driver-2"></i>
                                            </div>
                                            <!-- Content -->
                                            <div class="irantheme-settings-status-main-chart-list-item-content">
                                                <h3 class="irantheme-settings-status-main-chart-list-item-content-heading">
                                                    <?php _e('میزان محدودیت مموری'); ?>
                                                </h3>
                                                <p class="irantheme-settings-status-main-chart-list-item-content-desc">
                                                    <?php echo WP_MAX_MEMORY_LIMIT; ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <!-- Item -->
                                        <div class="irantheme-settings-status-main-chart-list-item">
                                            <!-- Icon -->
                                            <div class="irantheme-settings-status-main-chart-list-item-icon">
                                                <i class="iconsax" icon-name="document-code-2"></i>
                                            </div>
                                            <!-- Content -->
                                            <div class="irantheme-settings-status-main-chart-list-item-content">
                                                <h3 class="irantheme-settings-status-main-chart-list-item-content-heading">
                                                    <?php _e('محدودیت متغیر های ورودی'); ?>
                                                </h3>
                                                <p class="irantheme-settings-status-main-chart-list-item-content-desc">
                                                    <?php echo ini_get("max_input_vars"); ?>
                                                </p>
                                            </div>
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
            $this->parent->get_footer('status');
        }


        /**
         * Get html side verifier for status page
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0.3
         * @version v1.0.3
         */
        public function get_html_side_verifier()
        {
        ?>
            <!-- Side -->
            <div class="col-lg-4 col-sm-12 irantheme-settings-status-side-holder">
                <div class="irantheme-settings-status-side">
                    <div class="mb-4">
                        <h4 class="irantheme-settings-status-titlebar-heading mb-4"><?php _e('فعال سازی'); ?></h4>
                        <p class="irantheme-settings-status-titlebar-description"><?php _e('جهت فعال سازی قالب و استفاده از تمامی امکانات می بایست محصول را خریداری کرده و سپس لایسنس (کد) فعال سازی را در کادر زیر وارد نمایید.'); ?></p>
                        <p class="irantheme-settings-status-titlebar-description"><?php _e('جهت حفظ سلامت محصول و جلوگیری از اخلال در فعال سازی سایت و بروزرسانی ها، کد فعال سازی محصول را هرگز با دیگران به اشتراک نگذارید.'); ?></p>
                    </div>
                    <form action="" method="post" id="irantheme-settings-status-verification-form">
                        <div class="mb-3">
                            <h4 class="irantheme-settings-status-titlebar-heading mb-2"><?php _e('کد لایسنس'); ?></h4>
                            <p class="irantheme-settings-status-titlebar-description mb-2"><?php _e('کد فعال سازی محصول خریداری شده را در کادر زیر وارد نمایید.'); ?></p>
                            <?php if ($this->parent->verify) : ?>
                                <?php Irantheme_Settings::get_notification('success', 'محصول شما با موفقیت فعال شده.', 'm-0 my-3 align-items-center text-align-center irantheme-settings-status-verification-success active') ?>
                            <?php else: ?>
                                <?php Irantheme_Settings::get_notification('warning', 'محصول شما هنوز فعال نیست.', 'm-0 my-3 irantheme-settings-status-verification-success', 'display:none;') ?>
                            <?php endif; ?>
                            <!-- Input key -->
                            <input type="text" class="form-control irantheme-settings-status-verification-input" id="exampleInputText1" aria-describedby="textHelp" value="<?php echo irantheme_get_key(); ?>" required>
                            <div class="mt-3 mb-3 d-flex">
                                <!-- Checkbox privacy -->
                                <input type="checkbox" class="irantheme-settings-status-verification-confirm-check" id="irantheme-settings-status-verification-confirm-check" required>
                                <label class="irantheme-settings-status-verification-confirm-desc" for="irantheme-settings-status-verification-confirm-check"><?php _e('تمامی شرایط و ضوابط استفاده از این محصول را با دقت کامل خوانده و نسبت به آنها آگاهی کامل دارم.'); ?><a href="#" target="_blank"><?php _e('خواندن شرایط'); ?></a></label>
                            </div>
                            <!-- Submit -->
                            <button type="submit" class="irantheme-button-default irantheme-button-medium irantheme-button-primary irantheme-button-block disabled irantheme-settings-status-verification-button">
                                <i class="fa-light fa-check check"></i>
                                <i id="irantheme-spinner-alt" class="irantheme-animate-rotate"></i>
                                <cite><?php _e('فعال سازی محصول'); ?></cite>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
<?php
        }
    }
}
