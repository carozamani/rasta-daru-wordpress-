<?php

/**
 * WooCommerce Delivery Date System
 * 
 * @package WordPress
 * @subpackage Romak
 * @author iranianclever
 * @since v1.0.4
 * 
 * This class provides delivery date selection for WooCommerce orders
 * with support for both Classic Checkout and Checkout Block.
 */

namespace Irantheme_Dev;

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('Irantheme_Woocommerce_Delivery_Date')) {

    class Irantheme_Woocommerce_Delivery_Date
    {
        private static $meta_key = '_delivery_date';
        private static $meta_display_key = '_delivery_date_display';
        private static $field_id = 'irantheme/shipping-date';

        public function __construct()
        {
            $delivery_date_checkbox = get_option('irantheme_wc_shopping_cart_delivery_date_checkbox');

            if (is_woocommerce_activated() && 'on' == $delivery_date_checkbox) {
                add_action('woocommerce_init', array($this, 'register_checkout_block_field'));
                add_action('woocommerce_set_additional_field_value', array($this, 'save_block_checkout_field_value'), 10, 4);
                add_action('woocommerce_after_order_notes', array($this, 'display_classic_checkout_field'));
                add_action('woocommerce_checkout_update_order_review', array($this, 'save_to_session'));
                add_action('woocommerce_checkout_process', array($this, 'validate_classic_checkout_field'));
                add_action('woocommerce_checkout_create_order', array($this, 'save_order_delivery_date'), 10, 2);
                add_action('woocommerce_checkout_update_order_meta', array($this, 'save_classic_checkout_delivery_date'), 10, 1);
                add_action('woocommerce_order_details_after_order_table', array($this, 'display_in_order_details'));
                add_action('woocommerce_email_order_details', array($this, 'display_in_order_emails'), 10, 4);
                add_filter('manage_edit-shop_order_columns', array($this, 'add_admin_column'));
                add_action('manage_shop_order_posts_custom_column', array($this, 'display_admin_column'), 10, 2);
                add_filter('woocommerce_shop_order_search_fields', array($this, 'add_search_fields'));
                add_action('wp_footer', array($this, 'add_block_checkout_card_ui'));
            }
        }


        /**
         * Register the delivery date field for WooCommerce Block Checkout.
         */
        public static function register_checkout_block_field()
        {
            if (!function_exists('woocommerce_register_additional_checkout_field')) {
                return;
            }

            $options = self::get_delivery_date_options();

            woocommerce_register_additional_checkout_field(array(
                'id'          => self::$field_id,
                'label'       => __('زمان تحویل مرسوله', IRANTHEME_TEXTDOMAIN),
                'location'    => 'order',
                'type'        => 'select',
                'required'    => true,
                'placeholder' => __('یک بازه زمانی را انتخاب کنید', IRANTHEME_TEXTDOMAIN),
                'options'     => $options,
                'sanitize_callback' => function ($value) {
                    return sanitize_text_field($value);
                },
                'validate_callback' => function ($value) {
                    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $value)) {
                        return new \WP_Error(
                            'invalid_delivery_date',
                            __('زمان تحویل انتخاب‌شده معتبر نیست.', IRANTHEME_TEXTDOMAIN)
                        );
                    }
                },
            ));
        }


        /**
         * Get delivery date options for the next 7 working days.
         */
        public static function get_delivery_date_options()
        {
            $today = current_time('Y-m-d');
            $holiday_weekdays = apply_filters('irantheme_delivery_date_holiday_weekdays', array(5));
            $holidays = apply_filters('irantheme_delivery_date_holidays', array());
            $holidays = array_map('strval', (array) $holidays);

            $options = array();
            $working_day = 0;
            $calendar_offset = 0;

            while ($working_day < 7 && $calendar_offset < 30) {
                $calendar_offset++;
                $timestamp = strtotime("+{$calendar_offset} days", strtotime($today));
                if (!$timestamp) continue;

                $weekday = (int) wp_date('w', $timestamp);
                $date    = wp_date('Y-m-d', $timestamp);

                if (in_array($weekday, $holiday_weekdays, true)) continue;
                if (in_array($date, $holidays, true)) continue;

                $working_day++;

                if ($working_day >= 3 && $working_day <= 7) {
                    $weekday_fa = self::delivery_date_weekday_fa($timestamp);
                    $options[] = array(
                        'value'   => $date,
                        'label'   => $working_day . ' روز ' . $weekday_fa . ' ' . self::delivery_date_jalali($timestamp),
                        'days'    => $working_day,
                        'weekday' => $weekday_fa,
                    );
                }
            }

            return $options;
        }


        public static function delivery_date_jalali($timestamp)
        {
            if (function_exists('jdate')) {
                return jdate('Y/m/d', $timestamp, '', 'Asia/Tehran', 'fa');
            }
            return wp_date('Y/m/d', $timestamp, wp_timezone());
        }


        public static function delivery_date_weekday_fa($timestamp)
        {
            $weekdays = array(
                '0' => 'یکشنبه',
                '1' => 'دوشنبه',
                '2' => 'سه‌شنبه',
                '3' => 'چهارشنبه',
                '4' => 'پنجشنبه',
                '5' => 'جمعه',
                '6' => 'شنبه',
            );
            $index = wp_date('w', $timestamp);
            return isset($weekdays[$index]) ? $weekdays[$index] : '';
        }


        public static function save_block_checkout_field_value($key, $value, $group, $wc_object)
        {
            if (self::$field_id !== $key || 'other' !== $group || !is_a($wc_object, 'WC_Order')) {
                return;
            }

            $delivery_date = sanitize_text_field($value);
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $delivery_date)) return;

            $wc_object->update_meta_data(self::$meta_key, $delivery_date, true);

            $date_obj = \DateTime::createFromFormat('Y-m-d', $delivery_date);
            if ($date_obj) {
                $wc_object->update_meta_data(self::$meta_display_key, $date_obj->format('Y/m/d'), true);
            }
        }


        /**
         * Display the delivery date selection field in Classic Checkout.
         */
        public static function display_classic_checkout_field($checkout)
        {
            echo '<div id="delivery_date_checkout_field" class="irantheme-checkout-box irantheme-checkout-box--date">';

            echo '<h3 class="irantheme-checkout-box__title">';
            echo '<i class="iconsax" icon-name="truck-time"></i>';
            echo '<span>زمان تحویل مرسوله</span>';
            echo '</h3>';

            echo '<p class="irantheme-checkout-box__desc">';
            echo 'زمان مورد نظر خود را برای دریافت مرسوله انتخاب کنید:';
            echo '</p>';

            echo '<div class="delivery-date-options" role="radiogroup" aria-label="زمان تحویل مرسوله">';

            $options = self::get_delivery_date_options();
            $saved = WC()->session ? WC()->session->get('delivery_date') : '';

            foreach ($options as $index => $option) {
                $date = $option['value'];
                $days = $option['days'];
                $display = self::delivery_date_jalali(strtotime($date));
                $weekday = isset($option['weekday']) ? $option['weekday'] : self::delivery_date_weekday_fa(strtotime($date));
                $checked = ($saved === $date) || (empty($saved) && $index === 0) ? ' checked="checked"' : '';

                echo '<label class="delivery-date-card">';
                echo '<input type="radio" name="delivery_date" value="' . esc_attr($date) . '"' . $checked . '>';
                echo '<span class="delivery-date-card__content">';
                echo '<span class="delivery-date-card__days">';
                echo '<i class="iconsax" icon-name="truck-tick"></i>';
                echo '<span>' . esc_html($days . ' روز') . '</span>';
                echo '</span>';
                echo '<span class="delivery-date-card__weekday">' . esc_html($weekday) . '</span>';
                echo '<span class="delivery-date-card__date">' . esc_html($display) . '</span>';
                echo '</span>';
                echo '</label>';
            }

            echo '</div>';

            echo '<p class="irantheme-checkout-box__hint">';
            echo '<i class="iconsax" icon-name="info-circle"></i>';
            echo '<span>دریافت بین ۳ تا ۷ روز کاری آینده</span>';
            echo '</p>';

            echo '</div>';
        }


        public static function save_to_session($post_data)
        {
            parse_str($post_data, $data);
            if (isset($data['delivery_date']) && !empty($data['delivery_date']) && WC()->session) {
                WC()->session->set('delivery_date', sanitize_text_field(wp_unslash($data['delivery_date'])));
            }
        }


        public static function validate_classic_checkout_field()
        {
            if (empty($_POST['delivery_date'])) {
                wc_add_notice(__('لطفاً زمان تحویل مرسوله را انتخاب کنید.', IRANTHEME_TEXTDOMAIN), 'error');
            }
        }


        public static function save_classic_checkout_delivery_date($order_id)
        {
            $delivery_date = '';

            if (isset($_POST['delivery_date']) && !empty($_POST['delivery_date'])) {
                $delivery_date = sanitize_text_field(wp_unslash($_POST['delivery_date']));
            } elseif (WC()->session) {
                $delivery_date = WC()->session->get('delivery_date');
            }

            if (!$delivery_date || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $delivery_date)) return;

            $order = wc_get_order($order_id);
            if (!$order) return;

            $order->update_meta_data(self::$meta_key, $delivery_date, true);

            $date_obj = \DateTime::createFromFormat('Y-m-d', $delivery_date);
            if ($date_obj) {
                $order->update_meta_data(self::$meta_display_key, $date_obj->format('Y/m/d'), true);
            }

            $order->save();

            if (WC()->session) {
                WC()->session->__unset('delivery_date');
            }
        }


        public static function save_order_delivery_date($order, $data)
        {
            $delivery_date = '';

            if (isset($_POST['delivery_date']) && !empty($_POST['delivery_date'])) {
                $delivery_date = sanitize_text_field(wp_unslash($_POST['delivery_date']));
            } elseif (WC()->session) {
                $delivery_date = WC()->session->get('delivery_date');
            }

            if (!$delivery_date || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $delivery_date)) return;

            $order->update_meta_data(self::$meta_key, $delivery_date, true);

            $date_obj = \DateTime::createFromFormat('Y-m-d', $delivery_date);
            if ($date_obj) {
                $order->update_meta_data(self::$meta_display_key, $date_obj->format('Y/m/d'), true);
            }
        }


        public static function display_in_order_details($order)
        {
            $delivery_date = $order->get_meta(self::$meta_display_key);
            if (!$delivery_date) $delivery_date = $order->get_meta(self::$meta_key);

            if ($delivery_date) {
                echo '<p class="irantheme-order-meta-line">';
                echo '<i class="iconsax" icon-name="truck-time"></i> ';
                echo '<strong>زمان تحویل:</strong> ' . esc_html($delivery_date);
                echo '</p>';
            }
        }


        public static function display_in_order_emails($order, $sent_to_admin, $plain_text, $email)
        {
            $delivery_date = $order->get_meta(self::$meta_display_key);
            if (!$delivery_date) $delivery_date = $order->get_meta(self::$meta_key);
            if (!$delivery_date) return;

            if ($plain_text) {
                echo "زمان تحویل: " . $delivery_date . "\n\n";
            } else {
                echo '<p><strong>زمان تحویل:</strong> ' . esc_html($delivery_date) . '</p>';
            }
        }


        public function add_admin_column($columns)
        {
            $new_columns = array();
            foreach ($columns as $key => $value) {
                $new_columns[$key] = $value;
                if ('order_status' === $key) {
                    $new_columns['delivery_date'] = 'زمان تحویل';
                }
            }
            return $new_columns;
        }


        public function display_admin_column($column, $post_id)
        {
            if ('delivery_date' !== $column) return;

            $order = wc_get_order($post_id);
            if (!$order) {
                echo '—';
                return;
            }

            $delivery_date = $order->get_meta(self::$meta_display_key);
            if (!$delivery_date) $delivery_date = $order->get_meta(self::$meta_key);

            if ($delivery_date) {
                echo '<span class="irantheme-admin-badge">';
                echo '<i class="iconsax" icon-name="truck-tick"></i> ';
                echo esc_html($delivery_date);
                echo '</span>';
            } else {
                echo '—';
            }
        }


        public function add_search_fields($search_fields)
        {
            $search_fields[] = self::$meta_key;
            $search_fields[] = self::$meta_display_key;
            return $search_fields;
        }


        /**
         * Add visual card UI for delivery date selection in Block Checkout.
         */
        function add_block_checkout_card_ui()
        {
            if (!is_checkout()) return;
?>
            <script>
                (function($) {
                    'use strict';

                    const SELECTOR = '#order-irantheme-shipping-date';
                    const BOX_ID = 'irantheme_delivery_date_block_box';

                    function setSelectValue($select, value) {
                        const select = $select[0];
                        if (!select) return;
                        const setter = Object.getOwnPropertyDescriptor(HTMLSelectElement.prototype, 'value')?.set;
                        setter ? setter.call(select, value) : select.value = value;
                        select.dispatchEvent(new Event('input', {
                            bubbles: true
                        }));
                        select.dispatchEvent(new Event('change', {
                            bubbles: true
                        }));
                    }

                    function buildBox($select) {
                        // Prevent duplicate box
                        if (document.getElementById(BOX_ID)) return;

                        const $options = $select.find('option').filter(function() {
                            return $(this).val() !== '';
                        });
                        if (!$options.length) return;

                        // Create the box container
                        const $box = $('<div>', {
                            class: 'irantheme-checkout-box irantheme-checkout-box--date',
                            id: BOX_ID
                        });

                        // Title
                        $box.append(
                            '<h3 class="irantheme-checkout-box__title">' +
                            '<i class="iconsax" icon-name="truck-time"></i>' +
                            '<span>زمان تحویل مرسوله</span>' +
                            '</h3>'
                        );

                        // Description
                        $box.append(
                            '<p class="irantheme-checkout-box__desc">' +
                            'زمان مورد نظر خود را برای دریافت مرسوله انتخاب کنید:' +
                            '</p>'
                        );

                        // Create the cards container
                        const $cards = $('<div>', {
                            class: 'delivery-date-options',
                            role: 'radiogroup',
                            'aria-label': 'زمان تحویل مرسوله'
                        });

                        const currentValue = String($select.val() || '');

                        $options.each(function() {
                            const $opt = $(this);
                            const value = $opt.val();
                            const text = $.trim($opt.text());
                            const match = text.match(/(\d+)\s*روز\s*(\S+)/);
                            const daysText = match ? match[1] + ' روز' : text;
                            const weekdayText = match ? match[2] : '';
                            const dateText = text.replace(daysText, '').replace(weekdayText, '').replace(/^-\s*/, '').trim();

                            const $label = $('<label>', {
                                class: 'delivery-date-card'
                            });
                            if (String(value) === currentValue) $label.addClass('selected');

                            const $radio = $('<input>', {
                                type: 'radio',
                                name: 'delivery_date_visual',
                                value: value
                            });
                            if (String(value) === currentValue) $radio.prop('checked', true);

                            const $content = $('<span>', {
                                class: 'delivery-date-card__content'
                            });
                            $content.append(
                                '<span class="delivery-date-card__days">' +
                                '<i class="iconsax" icon-name="truck-tick"></i>' +
                                '<span>' + daysText + '</span>' +
                                '</span>'
                            );
                            $content.append('<span class="delivery-date-card__weekday">' + weekdayText + '</span>');
                            $content.append('<span class="delivery-date-card__date">' + (dateText || value) + '</span>');

                            $label.append($radio, $content);

                            $radio.on('change', function() {
                                if (!this.checked) return;
                                setSelectValue($select, value);
                                $cards.find('.delivery-date-card').removeClass('selected');
                                $label.addClass('selected');
                            });

                            $cards.append($label);
                        });

                        $box.append($cards);

                        // Hint
                        $box.append(
                            '<p class="irantheme-checkout-box__hint">' +
                            '<i class="iconsax" icon-name="info-circle"></i>' +
                            '<span>دریافت بین ۳ تا ۷ روز کاری آینده</span>' +
                            '</p>'
                        );

                        // Insert the box after the select and hide the original select
                        const wrapper = $select.closest('.wc-blocks-components-select, .wc-block-components-select').get(0) ||
                            $select.parent().get(0);
                        if (!wrapper) return;

                        $(wrapper).after($box);
                        $(wrapper).hide();

                        $select.addClass('delivery-date-native-select').attr('aria-hidden', 'true');
                    }

                    function init() {
                        const $select = $(SELECTOR).first();
                        if (!$select.length) return;
                        buildBox($select);
                    }

                    $(function() {
                        // Initial build
                        init();

                        // Retry mechanism in case the select is not yet rendered
                        let tries = 0;
                        const timer = setInterval(function() {
                            tries++;
                            init();
                            if (tries > 60) clearInterval(timer);
                        }, 500);

                        // Observe changes in the checkout block to rebuild the box if necessary
                        const checkout = document.querySelector('.wc-block-checkout');
                        if (checkout) {
                            new MutationObserver(function() {
                                window.requestAnimationFrame(init);
                            }).observe(checkout, {
                                childList: true,
                                subtree: true
                            });
                        }
                    });
                })(jQuery);
            </script>
<?php
        }
    }

    new Irantheme_Woocommerce_Delivery_Date();
}
