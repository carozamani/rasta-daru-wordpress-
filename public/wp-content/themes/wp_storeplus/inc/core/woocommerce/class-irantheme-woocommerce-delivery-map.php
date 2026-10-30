<?php

/**
 * WooCommerce Delivery Map System
 * 
 * @package WordPress
 * @subpackage Romak
 * @author iranianclever
 * @since v1.0.4
 * 
 * This class provides delivery location selection on a map for WooCommerce orders
 * with support for both Classic Checkout and Checkout Block.
 */

namespace Irantheme_Dev;

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('Irantheme_Woocommerce_Delivery_Map')) {

    class Irantheme_Woocommerce_Delivery_Map
    {
        private static $meta_lat_key = '_delivery_latitude';
        private static $meta_lng_key = '_delivery_longitude';
        private static $meta_address_key = '_delivery_map_address';
        private static $field_id_lat = 'irantheme/delivery-latitude';
        private static $field_id_lng = 'irantheme/delivery-longitude';
        private static $field_id_address = 'irantheme/delivery-map-address';

        public function __construct()
        {
            $delivery_map_checkbox = get_option('irantheme_wc_shopping_cart_delivery_map_checkbox');

            if (is_woocommerce_activated() && 'on' == $delivery_map_checkbox) {
                add_action('woocommerce_init', array($this, 'register_checkout_block_fields'));
                add_action('woocommerce_set_additional_field_value', array($this, 'save_block_checkout_field_value'), 10, 4);
                add_action('woocommerce_after_order_notes', array($this, 'display_classic_checkout_map'));
                add_action('woocommerce_checkout_update_order_review', array($this, 'save_to_session'));
                add_action('woocommerce_checkout_process', array($this, 'validate_classic_checkout_map'));
                add_action('woocommerce_checkout_create_order', array($this, 'save_order_delivery_location'), 10, 2);
                add_action('woocommerce_checkout_update_order_meta', array($this, 'save_classic_checkout_location'), 10, 1);
                add_action('woocommerce_order_details_after_order_table', array($this, 'display_in_order_details'));
                add_action('woocommerce_email_order_details', array($this, 'display_in_order_emails'), 10, 4);
                add_action('woocommerce_admin_order_data_after_shipping_address', array($this, 'display_admin_order_map'));
                add_filter('manage_edit-shop_order_columns', array($this, 'add_admin_column'));
                add_action('manage_shop_order_posts_custom_column', array($this, 'display_admin_column'), 10, 2);
                add_filter('woocommerce_shop_order_search_fields', array($this, 'add_search_fields'));
                add_action('wp_enqueue_scripts', array($this, 'enqueue_checkout_assets'));
                add_action('wp_footer', array($this, 'add_block_checkout_map_ui'));
            }
        }


        public static function register_checkout_block_fields()
        {
            if (!function_exists('woocommerce_register_additional_checkout_field')) {
                return;
            }

            woocommerce_register_additional_checkout_field(array(
                'id'       => self::$field_id_lat,
                'label'    => __('عرض جغرافیایی', IRANTHEME_TEXTDOMAIN),
                'location' => 'order',
                'type'     => 'text',
                'required' => true,
                'sanitize_callback' => function ($value) {
                    return sanitize_text_field($value);
                },
                'validate_callback' => function ($value) {
                    if (!is_numeric($value)) {
                        return new \WP_Error('invalid_latitude', __('عرض جغرافیایی معتبر نیست.', IRANTHEME_TEXTDOMAIN));
                    }
                },
            ));

            woocommerce_register_additional_checkout_field(array(
                'id'       => self::$field_id_lng,
                'label'    => __('طول جغرافیایی', IRANTHEME_TEXTDOMAIN),
                'location' => 'order',
                'type'     => 'text',
                'required' => true,
                'sanitize_callback' => function ($value) {
                    return sanitize_text_field($value);
                },
                'validate_callback' => function ($value) {
                    if (!is_numeric($value)) {
                        return new \WP_Error('invalid_longitude', __('طول جغرافیایی معتبر نیست.', IRANTHEME_TEXTDOMAIN));
                    }
                },
            ));

            woocommerce_register_additional_checkout_field(array(
                'id'       => self::$field_id_address,
                'label'    => __('آدرس انتخاب‌شده روی نقشه', IRANTHEME_TEXTDOMAIN),
                'location' => 'order',
                'type'     => 'text',
                'required' => false,
                'sanitize_callback' => function ($value) {
                    return sanitize_text_field($value);
                },
            ));
        }


        public static function enqueue_checkout_assets()
        {
            if (!is_checkout()) return;

            wp_enqueue_style('irantheme-leaflet', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css', array(), '1.9.4');
            wp_enqueue_script('irantheme-leaflet', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', array(), '1.9.4', true);
        }


        /**
         * Display the delivery map selection field in Classic Checkout.
         */
        public static function display_classic_checkout_map($checkout)
        {
            $saved_lat = WC()->session ? WC()->session->get('delivery_latitude') : '';
            $saved_lng = WC()->session ? WC()->session->get('delivery_longitude') : '';
            $saved_addr = WC()->session ? WC()->session->get('delivery_map_address') : '';

            echo '<div id="irantheme_delivery_map_field" class="irantheme-checkout-box irantheme-checkout-box--map">';

            echo '<h3 class="irantheme-checkout-box__title">';
            echo '<i class="iconsax" icon-name="location-tick"></i>';
            echo '<span>موقعیت دقیق تحویل روی نقشه</span>';
            echo '</h3>';

            echo '<p class="irantheme-checkout-box__desc">';
            echo 'روی نقشه کلیک کنید یا نشانگر را جابجا کنید تا محل دقیق تحویل مشخص شود.';
            echo '</p>';

            echo '<div class="irantheme-map-search">';
            echo '<i class="iconsax" icon-name="search-normal-1"></i>';
            echo '<input type="text" id="irantheme_map_search" placeholder="جستجوی آدرس...">';
            echo '</div>';

            echo '<div class="irantheme-map-actions">';
            echo '<button type="button" class="button" id="irantheme_locate_me">';
            echo '<i class="iconsax" icon-name="gps"></i>';
            echo '<span>موقعیت من</span>';
            echo '</button>';
            echo '<button type="button" class="button" id="irantheme_clear_map">';
            echo '<i class="iconsax" icon-name="trash"></i>';
            echo '<span>پاک کردن</span>';
            echo '</button>';
            echo '</div>';

            echo '<div id="irantheme_delivery_map"></div>';

            echo '<p class="irantheme-map-coords">';
            echo '<i class="iconsax" icon-name="location"></i>';
            echo '<span>Lat: </span><span id="irantheme_lat_display">' . esc_html($saved_lat ?: '—') . '</span>';
            echo '<span class="irantheme-map-coords__sep">|</span>';
            echo '<span>Lng: </span><span id="irantheme_lng_display">' . esc_html($saved_lng ?: '—') . '</span>';
            echo '</p>';

            echo '<input type="hidden" name="delivery_latitude" id="irantheme_latitude" value="' . esc_attr($saved_lat) . '">';
            echo '<input type="hidden" name="delivery_longitude" id="irantheme_longitude" value="' . esc_attr($saved_lng) . '">';
            echo '<input type="hidden" name="delivery_map_address" id="irantheme_map_address" value="' . esc_attr($saved_addr) . '">';

            echo '</div>';
        }


        public static function save_to_session($post_data)
        {
            if (!WC()->session) return;
            parse_str($post_data, $data);

            if (isset($data['delivery_latitude']) && is_numeric($data['delivery_latitude'])) {
                WC()->session->set('delivery_latitude', sanitize_text_field(wp_unslash($data['delivery_latitude'])));
            }
            if (isset($data['delivery_longitude']) && is_numeric($data['delivery_longitude'])) {
                WC()->session->set('delivery_longitude', sanitize_text_field(wp_unslash($data['delivery_longitude'])));
            }
            if (isset($data['delivery_map_address'])) {
                WC()->session->set('delivery_map_address', sanitize_text_field(wp_unslash($data['delivery_map_address'])));
            }
        }


        public static function validate_classic_checkout_map()
        {
            $lat = isset($_POST['delivery_latitude']) ? sanitize_text_field(wp_unslash($_POST['delivery_latitude'])) : '';
            $lng = isset($_POST['delivery_longitude']) ? sanitize_text_field(wp_unslash($_POST['delivery_longitude'])) : '';

            if ($lat === '' || $lng === '' || !is_numeric($lat) || !is_numeric($lng)) {
                wc_add_notice(__('لطفاً موقعیت دقیق تحویل را روی نقشه انتخاب کنید.', IRANTHEME_TEXTDOMAIN), 'error');
            }
        }


        public static function save_classic_checkout_location($order_id)
        {
            $order = wc_get_order($order_id);
            if (!$order) return;

            self::persist_location_to_order($order, $_POST);
            $order->save();

            if (WC()->session) {
                WC()->session->__unset('delivery_latitude');
                WC()->session->__unset('delivery_longitude');
                WC()->session->__unset('delivery_map_address');
            }
        }


        public static function save_order_delivery_location($order, $data)
        {
            self::persist_location_to_order($order, $_POST, true);
        }


        private static function persist_location_to_order($order, $source = array(), $from_session_fallback = false)
        {
            $lat = isset($source['delivery_latitude']) ? sanitize_text_field(wp_unslash($source['delivery_latitude'])) : '';
            $lng = isset($source['delivery_longitude']) ? sanitize_text_field(wp_unslash($source['delivery_longitude'])) : '';
            $addr = isset($source['delivery_map_address']) ? sanitize_text_field(wp_unslash($source['delivery_map_address'])) : '';

            if ($from_session_fallback && WC()->session) {
                if ($lat === '') $lat = WC()->session->get('delivery_latitude');
                if ($lng === '') $lng = WC()->session->get('delivery_longitude');
                if ($addr === '') $addr = WC()->session->get('delivery_map_address');
            }

            if ($lat !== '' && is_numeric($lat)) $order->update_meta_data(self::$meta_lat_key, $lat, true);
            if ($lng !== '' && is_numeric($lng)) $order->update_meta_data(self::$meta_lng_key, $lng, true);
            if ($addr !== '') $order->update_meta_data(self::$meta_address_key, $addr, true);
        }


        public static function save_block_checkout_field_value($key, $value, $group, $wc_object)
        {
            if ('other' !== $group || !is_a($wc_object, 'WC_Order')) return;

            $sanitized = sanitize_text_field($value);

            switch ($key) {
                case self::$field_id_lat:
                    if (is_numeric($sanitized)) $wc_object->update_meta_data(self::$meta_lat_key, $sanitized, true);
                    break;
                case self::$field_id_lng:
                    if (is_numeric($sanitized)) $wc_object->update_meta_data(self::$meta_lng_key, $sanitized, true);
                    break;
                case self::$field_id_address:
                    $wc_object->update_meta_data(self::$meta_address_key, $sanitized, true);
                    break;
            }
        }


        public static function get_order_location($order)
        {
            if (!$order) return array('lat' => '', 'lng' => '', 'address' => '');

            return array(
                'lat'     => $order->get_meta(self::$meta_lat_key),
                'lng'     => $order->get_meta(self::$meta_lng_key),
                'address' => $order->get_meta(self::$meta_address_key),
            );
        }


        private static function build_map_link($lat, $lng)
        {
            return 'https://www.openstreetmap.org/?mlat=' . rawurlencode($lat)
                . '&mlon=' . rawurlencode($lng)
                . '#map=17/' . rawurlencode($lat) . '/' . rawurlencode($lng);
        }


        public static function display_in_order_details($order)
        {
            $loc = self::get_order_location($order);
            if ($loc['lat'] === '' || $loc['lng'] === '') return;

            echo '<p class="irantheme-order-meta-line">';
            echo '<i class="iconsax" icon-name="location-tick"></i> ';
            echo '<strong>موقعیت تحویل:</strong> ';
            echo '<a target="_blank" rel="noopener" href="' . esc_url(self::build_map_link($loc['lat'], $loc['lng'])) . '">';
            echo '<i class="iconsax" icon-name="map-1"></i> مشاهده روی نقشه';
            echo '</a>';
            echo '</p>';

            if ($loc['address']) {
                echo '<p class="irantheme-order-meta-line"><strong>آدرس انتخاب‌شده:</strong> ' . esc_html($loc['address']) . '</p>';
            }
        }


        public static function display_in_order_emails($order, $sent_to_admin, $plain_text, $email)
        {
            $loc = self::get_order_location($order);
            if ($loc['lat'] === '' || $loc['lng'] === '') return;

            $link = self::build_map_link($loc['lat'], $loc['lng']);

            if ($plain_text) {
                echo "موقعیت تحویل: " . $link . "\n\n";
                if ($loc['address']) echo "آدرس انتخاب‌شده: " . $loc['address'] . "\n\n";
            } else {
                echo '<p><strong>موقعیت تحویل:</strong> <a target="_blank" rel="noopener" href="' . esc_url($link) . '">مشاهده روی نقشه</a></p>';
                if ($loc['address']) {
                    echo '<p><strong>آدرس انتخاب‌شده:</strong> ' . esc_html($loc['address']) . '</p>';
                }
            }
        }


        public static function display_admin_order_map($order)
        {
            $loc = self::get_order_location($order);

            if ($loc['lat'] === '' || $loc['lng'] === '') {
                echo '<p><em>موقعیتی برای این سفارش ثبت نشده است.</em></p>';
                return;
            }

            echo '<div class="irantheme-admin-order-map">';
            echo '<h4 class="irantheme-admin-order-map__title">';
            echo '<i class="iconsax" icon-name="route-1"></i>';
            echo '<span>موقعیت دقیق تحویل</span>';
            echo '</h4>';
            echo '<div id="irantheme_admin_order_map"></div>';
            echo '<p class="irantheme-map-coords">';
            echo '<i class="iconsax" icon-name="location"></i>';
            echo 'Lat: ' . esc_html($loc['lat']) . ' | Lng: ' . esc_html($loc['lng']);
            echo '</p>';
            echo '<p><a target="_blank" rel="noopener" class="button" href="' . esc_url(self::build_map_link($loc['lat'], $loc['lng'])) . '">';
            echo '<i class="iconsax" icon-name="map-1"></i> مشاهده در نقشه بزرگ';
            echo '</a></p>';
            if ($loc['address']) {
                echo '<p><strong>آدرس:</strong> ' . esc_html($loc['address']) . '</p>';
            }
            echo '</div>';

            wp_enqueue_style('irantheme-leaflet-admin', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css', array(), '1.9.4');
            wp_enqueue_script('irantheme-leaflet-admin', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', array(), '1.9.4', true);

            $lat_js = esc_js($loc['lat']);
            $lng_js = esc_js($loc['lng']);

            wp_add_inline_script('irantheme-leaflet-admin', "
                document.addEventListener('DOMContentLoaded', function() {
                    if (typeof L === 'undefined') return;
                    var el = document.getElementById('irantheme_admin_order_map');
                    if (!el) return;
                    var map = L.map(el).setView([{$lat_js}, {$lng_js}], 16);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '© OpenStreetMap'
                    }).addTo(map);
                    L.marker([{$lat_js}, {$lng_js}]).addTo(map).bindPopup('محل تحویل مشتری').openPopup();
                });
            ");
        }


        public function add_admin_column($columns)
        {
            $new_columns = array();
            foreach ($columns as $key => $value) {
                $new_columns[$key] = $value;
                if ('order_status' === $key) {
                    $new_columns['delivery_map'] = 'موقعیت تحویل';
                }
            }
            return $new_columns;
        }


        public function display_admin_column($column, $post_id)
        {
            if ('delivery_map' !== $column) return;

            $order = wc_get_order($post_id);
            if (!$order) {
                echo '—';
                return;
            }

            $loc = self::get_order_location($order);
            if ($loc['lat'] === '' || $loc['lng'] === '') {
                echo '—';
                return;
            }

            echo '<a target="_blank" rel="noopener" class="irantheme-admin-badge" href="' . esc_url(self::build_map_link($loc['lat'], $loc['lng'])) . '">';
            echo '<i class="iconsax" icon-name="location-tick"></i> مشاهده';
            echo '</a>';
        }


        public function add_search_fields($search_fields)
        {
            $search_fields[] = self::$meta_lat_key;
            $search_fields[] = self::$meta_lng_key;
            $search_fields[] = self::$meta_address_key;
            return $search_fields;
        }


        /**
         * Add map UI (Leaflet) for both Classic and Block Checkout in the footer.
         */
        public function add_block_checkout_map_ui()
        {
            if (!is_checkout()) return;

            $default_lat = apply_filters('irantheme_delivery_map_default_lat', 35.6892);
            $default_lng = apply_filters('irantheme_delivery_map_default_lng', 51.3890);
            $default_zoom = (int) apply_filters('irantheme_delivery_map_default_zoom', 11);

            $default_lat_js = esc_js($default_lat);
            $default_lng_js = esc_js($default_lng);
            $default_zoom_js = (int) $default_zoom;

            $block_lat_selector = '#order-irantheme-delivery-latitude';
            $block_lng_selector = '#order-irantheme-delivery-longitude';
            $block_addr_selector = '#order-irantheme-delivery-map-address';
?>
            <script>
                (function($) {
                    'use strict';

                    var DEFAULT_LAT = <?php echo $default_lat_js; ?>;
                    var DEFAULT_LNG = <?php echo $default_lng_js; ?>;
                    var DEFAULT_ZOOM = <?php echo $default_zoom_js; ?>;

                    var BLOCK_LAT = '<?php echo esc_js($block_lat_selector); ?>';
                    var BLOCK_LNG = '<?php echo esc_js($block_lng_selector); ?>';
                    var BLOCK_ADDR = '<?php echo esc_js($block_addr_selector); ?>';

                    var MAP_ID = 'irantheme_delivery_map';
                    var BLOCK_MAP_ID = 'irantheme_delivery_map_block';
                    var mapInstance = null;
                    var blockMapInstance = null;
                    var blockMarkerInstance = null;

                    function updateHiddenFields(lat, lng, address) {
                        var $lat = $('#irantheme_latitude');
                        var $lng = $('#irantheme_longitude');
                        var $addr = $('#irantheme_map_address');

                        if ($lat.length) $lat.val(lat);
                        if ($lng.length) $lng.val(lng);
                        if ($addr.length && typeof address === 'string') $addr.val(address);

                        $('#irantheme_lat_display').text(lat || '—');
                        $('#irantheme_lng_display').text(lng || '—');

                        if ($('form.checkout').length) {
                            $(document.body).trigger('update_checkout');
                        }
                    }

                    function reverseGeocode(lat, lng, callback) {
                        var url = 'https://nominatim.openstreetmap.org/reverse?format=json&lat=' +
                            encodeURIComponent(lat) + '&lon=' + encodeURIComponent(lng) + '&accept-language=fa';

                        fetch(url, {
                                headers: {
                                    'Accept': 'application/json'
                                }
                            })
                            .then(function(r) {
                                return r.json();
                            })
                            .then(function(data) {
                                var addr = data && data.display_name ? data.display_name : '';
                                if (typeof callback === 'function') callback(addr);
                            })
                            .catch(function() {
                                if (typeof callback === 'function') callback('');
                            });
                    }

                    function setMarker(map, markerRef, lat, lng, draggable) {
                        if (markerRef.value) {
                            markerRef.value.setLatLng([lat, lng]);
                        } else {
                            markerRef.value = L.marker([lat, lng], {
                                draggable: !!draggable
                            }).addTo(map);
                        }
                        return markerRef.value;
                    }

                    function initClassicMap() {
                        var el = document.getElementById(MAP_ID);
                        if (!el || mapInstance) return;
                        if (typeof L === 'undefined') return;

                        mapInstance = L.map(MAP_ID).setView([DEFAULT_LAT, DEFAULT_LNG], DEFAULT_ZOOM);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            maxZoom: 19,
                            attribution: '© OpenStreetMap'
                        }).addTo(mapInstance);

                        var markerRef = {
                            value: null
                        };
                        var startLat = parseFloat($('#irantheme_latitude').val()) || null;
                        var startLng = parseFloat($('#irantheme_longitude').val()) || null;

                        if (startLat && startLng) {
                            setMarker(mapInstance, markerRef, startLat, startLng, true);
                            mapInstance.setView([startLat, startLng], 16);
                        }

                        markerRef.value && markerRef.value.on('dragend', function() {
                            var p = markerRef.value.getLatLng();
                            updateHiddenFields(p.lat.toFixed(7), p.lng.toFixed(7));
                            reverseGeocode(p.lat, p.lng, function(addr) {
                                updateHiddenFields(p.lat.toFixed(7), p.lng.toFixed(7), addr);
                            });
                        });

                        mapInstance.on('click', function(e) {
                            setMarker(mapInstance, markerRef, e.latlng.lat, e.latlng.lng, true);
                            if (markerRef.value) {
                                markerRef.value.off('dragend').on('dragend', function() {
                                    var p = markerRef.value.getLatLng();
                                    updateHiddenFields(p.lat.toFixed(7), p.lng.toFixed(7));
                                    reverseGeocode(p.lat, p.lng, function(addr) {
                                        updateHiddenFields(p.lat.toFixed(7), p.lng.toFixed(7), addr);
                                    });
                                });
                            }
                            updateHiddenFields(e.latlng.lat.toFixed(7), e.latlng.lng.toFixed(7));
                            reverseGeocode(e.latlng.lat, e.latlng.lng, function(addr) {
                                updateHiddenFields(e.latlng.lat.toFixed(7), e.latlng.lng.toFixed(7), addr);
                            });
                        });

                        $('#irantheme_locate_me').on('click', function() {
                            if (!navigator.geolocation) return;
                            navigator.geolocation.getCurrentPosition(function(pos) {
                                var lat = pos.coords.latitude;
                                var lng = pos.coords.longitude;
                                mapInstance.setView([lat, lng], 16);
                                setMarker(mapInstance, markerRef, lat, lng, true);
                                updateHiddenFields(lat.toFixed(7), lng.toFixed(7));
                                reverseGeocode(lat, lng, function(addr) {
                                    updateHiddenFields(lat.toFixed(7), lng.toFixed(7), addr);
                                });
                            });
                        });

                        $('#irantheme_clear_map').on('click', function() {
                            if (markerRef.value) {
                                mapInstance.removeLayer(markerRef.value);
                                markerRef.value = null;
                            }
                            updateHiddenFields('', '', '');
                        });

                        var searchTimer = null;
                        $('#irantheme_map_search').on('input', function() {
                            var q = $(this).val();
                            clearTimeout(searchTimer);
                            if (q.length < 4) return;
                            searchTimer = setTimeout(function() {
                                fetch('https://nominatim.openstreetmap.org/search?format=json&accept-language=fa&limit=1&q=' + encodeURIComponent(q))
                                    .then(function(r) {
                                        return r.json();
                                    })
                                    .then(function(data) {
                                        if (!data || !data[0]) return;
                                        var lat = parseFloat(data[0].lat);
                                        var lng = parseFloat(data[0].lon);
                                        mapInstance.setView([lat, lng], 16);
                                        setMarker(mapInstance, markerRef, lat, lng, true);
                                        updateHiddenFields(lat.toFixed(7), lng.toFixed(7), data[0].display_name || '');
                                    });
                            }, 500);
                        });
                    }

                    function setNativeInputValue(selector, value) {
                        var input = document.querySelector(selector);
                        if (!input) return;

                        var proto = input.tagName === 'TEXTAREA' ? HTMLTextAreaElement.prototype : HTMLInputElement.prototype;
                        var setter = Object.getOwnPropertyDescriptor(proto, 'value') && Object.getOwnPropertyDescriptor(proto, 'value').set;

                        setter ? setter.call(input, value) : input.value = value;

                        input.dispatchEvent(new Event('input', {
                            bubbles: true
                        }));
                        input.dispatchEvent(new Event('change', {
                            bubbles: true
                        }));
                        input.dispatchEvent(new Event('blur', {
                            bubbles: true
                        }));
                    }

                    function initBlockMap() {
                        var latInput = document.querySelector(BLOCK_LAT);
                        var lngInput = document.querySelector(BLOCK_LNG);
                        var addrInput = document.querySelector(BLOCK_ADDR);

                        if (!latInput || !lngInput) return;

                        var wrapper = latInput.closest('.wc-block-components-text-input, .wc-block-components-form-field, .wc-block-components-field');
                        if (!wrapper) return;

                        if (document.getElementById(BLOCK_MAP_ID)) return;

                        var $container = $('<div>', {
                            class: 'irantheme-checkout-box irantheme-checkout-box--map',
                            id: BLOCK_MAP_ID + '_wrapper'
                        });

                        $container.append('<h3 class="irantheme-checkout-box__title"><i class="iconsax" icon-name="location-tick"></i><span>موقعیت دقیق تحویل روی نقشه</span></h3>');
                        $container.append('<p class="irantheme-checkout-box__desc">روی نقشه کلیک کنید یا نشانگر را جابجا کنید تا محل دقیق تحویل مشخص شود.</p>');
                        $container.append('<div class="irantheme-map-search"><i class="iconsax" icon-name="search-normal-1"></i><input type="text" id="irantheme_block_map_search" placeholder="جستجوی آدرس..."></div>');
                        $container.append('<div class="irantheme-map-actions"><button type="button" class="button" id="irantheme_block_locate_me"><i class="iconsax" icon-name="gps"></i><span>موقعیت من</span></button><button type="button" class="button" id="irantheme_block_clear_map"><i class="iconsax" icon-name="trash"></i><span>پاک کردن</span></button></div>');
                        $container.append('<div id="' + BLOCK_MAP_ID + '"></div>');
                        $container.append('<p class="irantheme-map-coords"><i class="iconsax" icon-name="location"></i><span>Lat: </span><span id="irantheme_block_lat_display">—</span><span class="irantheme-map-coords__sep">|</span><span>Lng: </span><span id="irantheme_block_lng_display">—</span></p>');

                        wrapper.style.display = 'none';
                        if (addrInput) {
                            var addrWrapper = addrInput.closest('.wc-block-components-text-input, .wc-block-components-form-field, .wc-block-components-field');
                            if (addrWrapper) addrWrapper.style.display = 'none';
                        }

                        $(wrapper).after($container);

                        if (typeof L === 'undefined') return;

                        blockMapInstance = L.map(BLOCK_MAP_ID).setView([DEFAULT_LAT, DEFAULT_LNG], DEFAULT_ZOOM);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            maxZoom: 19,
                            attribution: '© OpenStreetMap'
                        }).addTo(blockMapInstance);

                        var startLat = parseFloat(latInput.value) || null;
                        var startLng = parseFloat(lngInput.value) || null;

                        blockMarkerInstance = startLat && startLng ?
                            L.marker([startLat, startLng], {
                                draggable: true
                            }).addTo(blockMapInstance) :
                            null;

                        if (blockMarkerInstance) blockMapInstance.setView([startLat, startLng], 16);

                        function updateBlockFields(lat, lng, addr) {
                            setNativeInputValue(BLOCK_LAT, String(lat));
                            setNativeInputValue(BLOCK_LNG, String(lng));
                            if (addrInput && typeof addr === 'string') setNativeInputValue(BLOCK_ADDR, addr);
                            $('#irantheme_block_lat_display').text(lat);
                            $('#irantheme_block_lng_display').text(lng);
                        }

                        function placeMarker(lat, lng) {
                            if (!blockMarkerInstance) {
                                blockMarkerInstance = L.marker([lat, lng], {
                                    draggable: true
                                }).addTo(blockMapInstance);
                            } else {
                                blockMarkerInstance.setLatLng([lat, lng]);
                            }

                            blockMarkerInstance.off('dragend').on('dragend', function() {
                                var p = blockMarkerInstance.getLatLng();
                                var latFixed = p.lat.toFixed(7);
                                var lngFixed = p.lng.toFixed(7);
                                reverseGeocode(p.lat, p.lng, function(a) {
                                    updateBlockFields(latFixed, lngFixed, a);
                                });
                                updateBlockFields(latFixed, lngFixed);
                            });
                        }

                        blockMapInstance.on('click', function(e) {
                            placeMarker(e.latlng.lat, e.latlng.lng);
                            var latFixed = e.latlng.lat.toFixed(7);
                            var lngFixed = e.latlng.lng.toFixed(7);
                            updateBlockFields(latFixed, lngFixed);
                            reverseGeocode(e.latlng.lat, e.latlng.lng, function(a) {
                                updateBlockFields(latFixed, lngFixed, a);
                            });
                        });

                        $('#irantheme_block_locate_me').on('click', function() {
                            if (!navigator.geolocation) return;
                            navigator.geolocation.getCurrentPosition(function(pos) {
                                var lat = pos.coords.latitude;
                                var lng = pos.coords.longitude;
                                blockMapInstance.setView([lat, lng], 16);
                                placeMarker(lat, lng);
                                updateBlockFields(lat.toFixed(7), lng.toFixed(7));
                                reverseGeocode(lat, lng, function(a) {
                                    updateBlockFields(lat.toFixed(7), lng.toFixed(7), a);
                                });
                            });
                        });

                        $('#irantheme_block_clear_map').on('click', function() {
                            if (blockMarkerInstance) {
                                blockMapInstance.removeLayer(blockMarkerInstance);
                                blockMarkerInstance = null;
                            }
                            setNativeInputValue(BLOCK_LAT, '');
                            setNativeInputValue(BLOCK_LNG, '');
                            if (addrInput) setNativeInputValue(BLOCK_ADDR, '');
                            $('#irantheme_block_lat_display').text('—');
                            $('#irantheme_block_lng_display').text('—');
                        });

                        var searchTimer = null;
                        $('#irantheme_block_map_search').on('input', function() {
                            var q = $(this).val();
                            clearTimeout(searchTimer);
                            if (q.length < 4) return;
                            searchTimer = setTimeout(function() {
                                fetch('https://nominatim.openstreetmap.org/search?format=json&accept-language=fa&limit=1&q=' + encodeURIComponent(q))
                                    .then(function(r) {
                                        return r.json();
                                    })
                                    .then(function(data) {
                                        if (!data || !data[0]) return;
                                        var lat = parseFloat(data[0].lat);
                                        var lng = parseFloat(data[0].lon);
                                        blockMapInstance.setView([lat, lng], 16);
                                        placeMarker(lat, lng);
                                        updateBlockFields(lat.toFixed(7), lng.toFixed(7), data[0].display_name || '');
                                    });
                            }, 500);
                        });
                    }

                    function init() {
                        initClassicMap();
                        initBlockMap();
                    }

                    $(function() {
                        init();
                        $(document.body).on('updated_checkout', function() {
                            mapInstance = null;
                            initClassicMap();
                        });

                        var checkoutBlock = document.querySelector('.wc-block-checkout');
                        if (checkoutBlock) {
                            new MutationObserver(function() {
                                window.requestAnimationFrame(initBlockMap);
                            }).observe(checkoutBlock, {
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

    new Irantheme_Woocommerce_Delivery_Map();
}
