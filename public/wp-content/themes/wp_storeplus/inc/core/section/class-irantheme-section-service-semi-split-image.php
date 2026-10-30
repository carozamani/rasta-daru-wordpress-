<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;

if (!class_exists('Irantheme_Section_Service_Semi_Split_Image')) {
    /**
     * A class for create section
     *
     * @package Wordpress
     * @subpackage irantheme
     * @since irantheme - custom fields
     * @author iranianclever
     * @see v1.0
     *
     */
    class Irantheme_Section_Service_Semi_Split_Image extends Irantheme_Section
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
         * Constructor
         * @return void
         * @param void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function fields($status_default)
        {
            $heading_content = '<span style="font-size: 1em;">ــــــــــــــــــــــــ حتی نیمه شب هستیم</span>
<h2 style="font-size: 2em;"><span style="font-size: 1.2em;"><strong>تفاوت در یک لبخند رویایی</strong></span></h2><p font-size:1em;>لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک است، چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است، و برای شرایط فعلی تکنولوژی مورد نیاز، و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد.</p>';
            $settings = array(
                Irantheme_Section_Utils::heading_title_settings_generator($status_default, 'none', false, 'right', $heading_content, 'on', 'نوبت گیری', '#', '', 'primary', 'off', 'سفارش کار', '#', 'fa-light fa-pen', 'off', 'rectangle', 'right', 'off', '', 1, 1, 'large', 'normal', 'primary'),
                array(
                    'title' => 'تنظیمات بصری',
                    'id' => 'layout',
                    'description'            => __('تنظیمات بصری مانند : تصویر زمینه، ارتفاع، رنگ ها و...', self::$static_textdomain),
                    'icon' => 'fa-paintbrush-pencil',
                    'fields' => array(
                        array(
                            'id'             => 'display_mode',
                            'label'            => __(
                                'سبک محتوا',
                                self::$static_textdomain
                            ),
                            'description'    => __('با استفاده از این گزینه می توانید سبک نمایش محتوای سرویس خود را مشخص کنید.', self::$static_textdomain),
                            'type'            => 'radio',
                            'thumbnail_status' => true,
                            'options'        => array('right' => array('راست', 'showcase-split-right.png'), 'left' => array('چپ', 'showcase-split-left.png')),
                            'default'        => $status_default ? 'left' : '',
                            'keywords' => array('semi', 'mode', 'سبک', 'سرویس', 'چیدمان', 'alignment')
                        ),
                        array(
                            'id'             => 'base_color_select',
                            'label'            => __(
                                'رنگ پایه',
                                self::$static_textdomain
                            ),
                            'description'    => __('برای انتخاب رنگ پایه سرویس از لیست رنگ دلخواه خود را انتخاب کنید.رنگ محتواو پس زمینه سرویس بر اساس این رنگ تنظیم خواهد شد.', self::$static_textdomain),
                            'type'            => 'select',
                            'options'        => array('white' => 'سفید', 'dark' => 'مشکی'),
                            'default'        => $status_default ? 'white' : '',
                            'keywords' => array('color', 'background', 'پس زمینه', 'رنگ'),
                        ),
                        array(
                            'id'             => 'wrapper_mode_checkbox',
                            'label'            => __(
                                'اتصال به نگهدارنده خارجی سایت',
                                self::$static_textdomain
                            ),
                            'description'    => __('در صورت غیر فعال بودن این گزینه بخش های (سکشن ها) سایت از نگهدارنده داخلی سایت استفاده می کنند، شما با فعال کردن این گزینه می توانید نگهدارنده این بخش را به نگهدارنده خارجی سایت تغییر دهید.در این صورت، این بخش از کناره ها به اندازه نگهدارنده خارجی سایت بزرگ شده وبه آن می چسبد.', self::$static_textdomain),
                            'type'            => 'checkbox',
                            'default'        => $status_default ? 'off' : '',
                            'keywords' => array('hieght', 'ارتفاع', 'fullscreen', 'تمام صفحه', 'سرویس'),
                        ),
                    )
                ),
                // Get layout scale fields
                Irantheme_Section_Utils::layout_scale_fields_generator($status_default, array('radius', 'inner_padding', 'outer_padding'), 'on', 2, 2, 'off', 'off', 0, 0, 1),
                // Get background fields
                Irantheme_Section_Utils::background_fields_generator($status_default, array('image', 'image2', 'color'), $status_default === 'media' ? Irantheme_Common_Static::get_file_url_media_library('https://irantheme.com/wp-content/uploads/2026/02/4y02r76y.webp') : '', 'off', true, 'on', 'dark', 0.6, 'on', 'off', $status_default === 'media' ? Irantheme_Common_Static::get_file_url_media_library('https://irantheme.com/wp-content/uploads/2026/02/86exk3mr.webp') : ''),
                // Get custom html & css fields
                Irantheme_Section_Utils::html_css_fields_generator($status_default, array('element_id', 'css')),
            );

            $settings = apply_filters('irantheme_store_visual_demo_settings', $settings);

            return $settings;
        }


        /**
         * Render elements
         * @param string,string,string,string,mixed
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function render($fields_values, $data_type, $data_value, $data_page, $data_ajax)
        {
            // Background options ------------------------------------------------------------
            $background_image_data = $fields_values['bg_image'];
            $background_image_data2 = $fields_values['bg_image2'];
            $background_color_data = $fields_values['bg_color'];
            $background_color_css = Irantheme_Section_Utils::get_background_color_css($background_color_data);
            $background_image_url = Irantheme_Section_Utils::get_background_image_css($background_image_data, true, 'large');

            // Get heading data load style --------------------------------------------------------------
            $heading_data_load_attr = Irantheme_Section_Utils::get_heading_data_load_attr_type($fields_values);
            // Get data load duration
            $heading_data_load_duration = Irantheme_Section_Utils::get_heading_data_load_duration($fields_values);
            // Get aos delay time
            $heading_aos_delay =
                Irantheme_Section_Utils::get_heading_data_load_delay($fields_values);

            // Base color --------------------------------------------------------------
            $base_color = $fields_values['base_color_select'];
            // Set base color class
            $base_color_class = Irantheme_Common_Static::get_base_color_class($base_color);

            // Get border radius --------------------------------------------------------------
            $border_radius_range = $fields_values['border_radius_range'];
            $border_radius_style = Irantheme_Section_Utils::get_border_radius_css($border_radius_range);

            // Get section id --------------------------------------------------------------
            $section_id = $fields_values['element_id'];
            $section_id_attribute = '';
            if ($section_id) {
                $section_id_attribute = sprintf('id=%s', $section_id);
            }
?>
            <!-- ============================= Section service semi split image ============================= -->
            <section <?php echo esc_attr($section_id_attribute); ?> class="irantheme-section irantheme-service-semi-split-image-section" <?php echo esc_attr(Irantheme_Page_Builder::get_attr_data_section($data_type, $data_value, $data_page, $data_ajax)); ?> data-json="<?php echo esc_attr('objects_json'); ?>" style="<?php echo esc_attr(Irantheme_Section_Service::get_custom_outer_padding_css($fields_values)); ?>">
                <?php
                // Set wrapper (Container) --------------------------------------------------------------
                $container_checkbox = $fields_values['wrapper_mode_checkbox'];
                ?>
                <div class="irantheme-service irantheme-service-semi-split-image <?php echo esc_attr($base_color_class); ?>" style="<?php echo esc_attr($background_color_css); ?><?php echo esc_attr(Irantheme_Section_Service::get_custom_inner_padding_css($fields_values)); ?>">
                    <?php
                    if ($container_checkbox != 'on') :
                    ?>
                        <div class=" irantheme-container-inner">
                        <?php endif; ?>
                        <?php
                        // Get alignment columns --------------------------------------------------------------
                        $display_mode = $fields_values['display_mode'];
                        $display_mode_class = '';
                        $padding_holder_class = '';
                        if ($display_mode == 'right') {
                            $display_mode_class = 'flex-row-reverse';
                            $padding_holder_class = 'padding-left';
                        } else {
                            $padding_holder_class = 'padding-right';
                        }
                        // Set padding column with wrapper mode and connect to outer container
                        if ($container_checkbox == 'on') {
                            $padding_holder_class = 'padding-right padding-left';
                        }
                        ?>
                        <div class="row justify-content-center <?php echo esc_attr($display_mode_class); ?>" style="align-items: center;margin-right:0;margin-left:0;">
                            <div class="col-lg-6" style="margin-bottom: 0;padding:0;display:flex;align-items:center;justify-content:center;height:100%;">

                                <?php if ($background_image_data || $background_image_data2) : ?>
                                    <div class="irantheme-service-semi-split-image-images irantheme-service-semi-split-image-images-<?php echo esc_attr($data_ajax); ?>" style="<?php echo esc_attr($border_radius_style); ?>">
                                        <?php
                                        $slider_images = [
                                            [
                                                'data' => $background_image_data,
                                                'num' => '1'
                                            ],
                                            [
                                                'data' => $background_image_data2,
                                                'num' => '2'
                                            ],
                                        ];

                                        foreach ($slider_images as $image) {
                                            if (!empty($image['data'])) {
                                                echo wp_get_attachment_image(
                                                    $image['data'],
                                                    'large',
                                                    false,
                                                    [
                                                        'class' => 'irantheme-service-semi-split-image-images-img irantheme-service-semi-split-image-images-img-' . $image['num'] . ' irantheme-service-semi-split-image-images-img-' . $image['num'] . '-' . esc_attr($data_ajax),
                                                        'alt' => __('خطا در بارگذاری تصویر', self::$static_textdomain),
                                                        'loading' => 'lazy',
                                                    ]
                                                );
                                            }
                                        }
                                        ?>
                                        <!-- Trigger -->
                                        <div class="irantheme-service-semi-split-image-trigger">
                                            <span id="irantheme-service-semi-split-image-trigger-toggle-<?php echo esc_attr($data_ajax); ?>" class="irantheme-service-semi-split-image-trigger-toggle">
                                                <i class="fa-light fa-arrows-left-right-to-line"></i>
                                            </span>
                                        </div>
                                        <div id="irantheme-service-semi-split-image-url-<?php echo esc_attr($data_ajax); ?>" class="d-none" data-url="<?php echo $background_image_url; ?>"></div>
                                        <script type="text/javascript">
                                            jQuery(function() {
                                                let container = jQuery('.irantheme-service-semi-split-image-images-<?php echo esc_attr($data_ajax); ?>');
                                                let imageUrl = jQuery('#irantheme-service-semi-split-image-url-<?php echo esc_attr($data_ajax); ?>').attr('data-url');

                                                if (!imageUrl) {
                                                    console.error('Image URL not found');
                                                    return;
                                                }

                                                // Wait for the image to load completely
                                                let imageObject = new Image();
                                                imageObject.onload = function() {
                                                    initializeSlider();
                                                };
                                                imageObject.onerror = function() {
                                                    console.error('Failed to load image:', imageUrl);
                                                    // Fallback: Try to get dimensions from existing image
                                                    setTimeout(() => {
                                                        let existingImg = jQuery('.irantheme-service-semi-split-image-images-<?php echo esc_attr($data_ajax); ?> img').first();
                                                        if (existingImg.length) {
                                                            imageObject = existingImg[0];
                                                            initializeSlider();
                                                        }
                                                    }, 500);
                                                };
                                                imageObject.src = imageUrl;

                                                function initializeSlider() {
                                                    // Get image elements
                                                    let image1 = jQuery('.irantheme-service-semi-split-image-images-img-1-<?php echo esc_attr($data_ajax); ?>');
                                                    let image2 = jQuery('.irantheme-service-semi-split-image-images-img-2-<?php echo esc_attr($data_ajax); ?>');

                                                    if (image1.length === 0 || image2.length === 0) {
                                                        console.error('Image elements not found');
                                                        return;
                                                    }

                                                    // Get the actual displayed dimensions
                                                    let displayedWidth = image1.width();
                                                    let displayedHeight = image1.height();

                                                    if (displayedWidth === 0 || displayedHeight === 0) {
                                                        console.warn('Displayed dimensions are 0, retrying...');
                                                        setTimeout(initializeSlider, 100);
                                                        return;
                                                    }

                                                    let flag = false;
                                                    let trigger = jQuery('#irantheme-service-semi-split-image-trigger-toggle-<?php echo esc_attr($data_ajax); ?>');
                                                    let triggerParent = trigger.parent('.irantheme-service-semi-split-image-trigger');

                                                    // Store the current split percentage instead of pixel position
                                                    let splitPercentage = 0.5; // Start at 50% (center)

                                                    trigger.on('mousedown touchstart pointerdown', function(e) {
                                                        e.preventDefault();
                                                        flag = true;
                                                        trigger.addClass('active');
                                                    });

                                                    jQuery(document).on('mouseup touchend pointerup', function(e) {
                                                        if (flag) {
                                                            e.preventDefault();
                                                            flag = false;
                                                            trigger.removeClass('active');
                                                        }
                                                    });

                                                    // Function to update slider based on percentage
                                                    function updateSliderFromPercentage(percentage) {
                                                        // Constrain percentage
                                                        percentage = Math.max(0, Math.min(1, percentage));

                                                        // Calculate pixel position
                                                        let pixelPosition = displayedWidth * percentage;

                                                        // Update clips
                                                        image1.css('clip', 'rect(0px, ' + pixelPosition + 'px, ' + displayedHeight + 'px, 0px)');
                                                        image2.css('clip', 'rect(0px, ' + displayedWidth + 'px, ' + displayedHeight + 'px, ' + pixelPosition + 'px)');

                                                        // Update trigger position
                                                        triggerParent.css({
                                                            'left': pixelPosition + 'px',
                                                            'height': displayedHeight + 'px'
                                                        });

                                                        // Store the percentage
                                                        splitPercentage = percentage;
                                                    }

                                                    // Initial setup at center
                                                    updateSliderFromPercentage(0.5);

                                                    // Mouse/touch move handler
                                                    let moveHandler = function(e) {
                                                        if (!flag) return;

                                                        e.stopPropagation();
                                                        e.preventDefault();

                                                        let clientX;
                                                        if (e.type.includes('touch')) {
                                                            clientX = e.originalEvent.touches[0].clientX;
                                                        } else {
                                                            clientX = e.clientX;
                                                        }

                                                        // Get container offset
                                                        let containerOffset = container.offset();
                                                        if (!containerOffset) return;

                                                        let offsetLeft = containerOffset.left;
                                                        let pixelPosition = clientX - offsetLeft;

                                                        // Calculate percentage based on current displayed width
                                                        let percentage = pixelPosition / displayedWidth;

                                                        // Update slider
                                                        updateSliderFromPercentage(percentage);
                                                    };

                                                    // Attach move handler to the slider container
                                                    container.on('mousemove touchmove pointermove', moveHandler);

                                                    // Handle window resize - FIXED VERSION
                                                    let resizeTimer;
                                                    let handleResize = function() {
                                                        clearTimeout(resizeTimer);
                                                        resizeTimer = setTimeout(function() {
                                                            // Recalculate displayed dimensions
                                                            let newDisplayedWidth = image1.width();
                                                            let newDisplayedHeight = image1.height();

                                                            if (newDisplayedWidth === 0 || newDisplayedHeight === 0) {
                                                                console.warn('Resize: Displayed dimensions are 0');
                                                                return;
                                                            }

                                                            // Update dimensions
                                                            displayedWidth = newDisplayedWidth;
                                                            displayedHeight = newDisplayedHeight;

                                                            // Reapply the same percentage with new dimensions
                                                            updateSliderFromPercentage(splitPercentage);

                                                            console.log('Resize handled - Width:', displayedWidth, 'Percentage:', splitPercentage);
                                                        }, 250);
                                                    };

                                                    jQuery(window).on('resize', handleResize);

                                                    // Also handle when images load inside the container
                                                    container.imagesLoaded(function() {
                                                        // Recalculate after all images are loaded
                                                        displayedWidth = image1.width();
                                                        displayedHeight = image1.height();
                                                        updateSliderFromPercentage(splitPercentage);
                                                    });

                                                    // Initial resize check
                                                    setTimeout(handleResize, 100);

                                                    // Optional: Log for debugging
                                                    console.log('Slider initialized - Width:', displayedWidth, 'Height:', displayedHeight);
                                                }

                                                // If imagesLoaded plugin is not available, add a fallback
                                                if (!jQuery.fn.imagesLoaded) {
                                                    jQuery.fn.imagesLoaded = function(callback) {
                                                        var $images = this.find('img');
                                                        var loaded = 0;
                                                        var checkCallback = function() {
                                                            loaded++;
                                                            if (loaded === $images.length) {
                                                                callback.call(this);
                                                            }
                                                        };

                                                        $images.each(function() {
                                                            if (this.complete) {
                                                                checkCallback();
                                                            } else {
                                                                jQuery(this).on('load', checkCallback);
                                                            }
                                                        });

                                                        // If no images, call immediately
                                                        if ($images.length === 0) {
                                                            callback.call(this);
                                                        }

                                                        return this;
                                                    };
                                                }
                                            });
                                        </script>
                                    </div>
                                <?php else : ?>
                                    <?php
                                    // Empty thumbnail
                                    Irantheme_Section_Service::empty_thumbnail($base_color);
                                    ?>
                                <?php endif; ?>
                            </div>

                            <?php
                            $heading_aos_delay += 200;
                            ?>

                            <div class="col-lg-6" style="margin-bottom:0;padding:0;" <?php echo ($heading_data_load_attr); ?> data-aos-delay="<?php echo ($heading_aos_delay); ?>" data-aos-duration="<?php echo ($heading_data_load_duration); ?>">
                                <div class="irantheme-holder-padding <?php echo esc_attr($padding_holder_class); ?>">
                                    <!-- Content -->
                                    <?php
                                    $heading_extra_css = 'height:auto;';
                                    // Get service heading --------------------------------------------------------------
                                    Irantheme_Common_Static::tinymce_heading($fields_values, $heading_extra_css);
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?php
                        if ($container_checkbox != 'on') :
                        ?>
                        </div>
                    <?php
                        endif;
                    ?>
                </div>
                <?php
                Irantheme_Page_Builder::get_hidden_require_section_buttons($data_page);
                ?>
                <?php
                // Style scoped
                Irantheme_Section_Utils::display_custom_css_code_style($fields_values);
                ?>
            </section>
<?php
        }
    }
}
