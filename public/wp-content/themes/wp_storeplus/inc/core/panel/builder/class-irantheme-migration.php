<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;

if (!class_exists('Irantheme_Migration')) {

    /**
     * Irantheme Meta Migration
     * 
     * انتقال meta data از الگوی per-page به meta key ثابت
     * 
     * این migration دقیقاً از استاندارد Irantheme_JSON_Helper::encode استفاده می‌کنه
     * تا داده در دیتابیس به فرمت string JSON تمیز ذخیره بشه.
     * 
     * @package Wordpress
     * @subpackage Storeplus
     * @since v2.0
     * @author iranianclever
     */
    class Irantheme_Migration
    {
        const BATCH_SIZE   = 50;
        const OPTION_DONE  = 'irantheme_meta_migration_v2_done';
        const OPTION_PROGRESS = 'irantheme_meta_migration_v2_progress';

        /**
         * راه‌اندازی
         */
        public static function init()
        {
            if (!is_admin()) {
                return;
            }

            add_action('admin_notices', array(__CLASS__, 'render_notice'));
            add_action('wp_ajax_irantheme_run_migration', array(__CLASS__, 'handle_ajax'));
            add_action('wp_ajax_irantheme_reset_migration', array(__CLASS__, 'handle_reset'));
        }


        /**
         * نمایش notice در ادمین
         */
        public static function render_notice()
        {
            $progress = get_option(self::OPTION_PROGRESS, array('offset' => 0));
            $offset   = isset($progress['offset']) ? absint($progress['offset']) : 0;
?>
            <div class="notice notice-warning">
                <p>
                    <strong>Irantheme Migration v2:</strong>
                    به‌روزرسانی ساختار meta data نیاز به اجرا داره.
                </p>
                <p>
                    <button type="button" class="button button-primary" id="irantheme-run-migration"
                        data-offset="<?php echo esc_attr($offset); ?>">
                        اجرای Migration
                    </button>
                    <button type="button" class="button" id="irantheme-reset-migration" style="margin-right:8px;">
                        ریست progress
                    </button>
                    <span id="irantheme-migration-status" style="margin-right:12px;line-height:30px;"></span>
                </p>
            </div>
            <script>
                (function() {
                    var runBtn = document.getElementById('irantheme-run-migration');
                    var resetBtn = document.getElementById('irantheme-reset-migration');
                    var status = document.getElementById('irantheme-migration-status');
                    if (!runBtn) return;

                    function setStatus(text, color) {
                        status.textContent = text;
                        status.style.color = color || '#2271b1';
                    }

                    function runBatch() {
                        var offset = parseInt(runBtn.dataset.offset, 10) || 0;

                        var data = new FormData();
                        data.append('action', 'irantheme_run_migration');
                        data.append('offset', offset);

                        fetch(ajaxurl, {
                                method: 'POST',
                                body: data
                            })
                            .then(function(r) {
                                return r.json();
                            })
                            .then(function(res) {
                                if (!res.success) {
                                    setStatus('خطا: ' + (res.data || 'unknown'), '#d63638');
                                    runBtn.disabled = false;
                                    return;
                                }
                                var d = res.data;
                                runBtn.dataset.offset = d.next_offset;
                                setStatus(d.migrated + ' / ' + d.total + ' صفحه', '#2271b1');
                                if (d.done) {
                                    setStatus('✅ انجام شد! مجموع: ' + d.total, '#00a32a');
                                    runBtn.remove();
                                    resetBtn.remove();
                                } else {
                                    runBatch();
                                }
                            })
                            .catch(function(err) {
                                setStatus('خطای شبکه: ' + err.message, '#d63638');
                                runBtn.disabled = false;
                            });
                    }

                    runBtn.addEventListener('click', function() {
                        runBtn.disabled = true;
                        setStatus('در حال اجرا...');
                        runBatch();
                    });

                    resetBtn.addEventListener('click', function() {
                        if (!confirm('progress پاک بشه و از ابتدا شروع بشه؟')) return;
                        var data = new FormData();
                        data.append('action', 'irantheme_reset_migration');
                        fetch(ajaxurl, {
                                method: 'POST',
                                body: data
                            })
                            .then(function(r) {
                                return r.json();
                            })
                            .then(function(res) {
                                if (res.success) {
                                    runBtn.dataset.offset = 0;
                                    setStatus('progress پاک شد.', '#2271b1');
                                }
                            });
                    });
                })();
            </script>
<?php
        }


        /**
         * هندلر AJAX
         */
        public static function handle_ajax()
        {
            if (!current_user_can('manage_options')) {
                wp_send_json_error('Permission denied');
            }

            $offset = isset($_POST['offset']) ? absint($_POST['offset']) : 0;

            // شمارش کل
            $total_query = new \WP_Query(array(
                'post_type'      => 'page',
                'post_status'    => 'any',
                'posts_per_page' => 1,
                'fields'         => 'ids',
                'no_found_rows'  => false,
            ));
            $total = (int) $total_query->found_posts;

            // بچ فعلی
            $page_ids = get_posts(array(
                'post_type'        => 'page',
                'post_status'      => 'any',
                'posts_per_page'   => self::BATCH_SIZE,
                'offset'           => $offset,
                'fields'           => 'ids',
                'no_found_rows'    => true,
                'suppress_filters' => true,
                'orderby'          => 'ID',
                'order'            => 'ASC',
            ));

            if (empty($page_ids)) {
                update_option(self::OPTION_DONE, time(), false);
                delete_option(self::OPTION_PROGRESS);

                wp_send_json_success(array(
                    'migrated'    => 0,
                    'total'       => $total,
                    'next_offset' => $offset,
                    'done'        => true,
                ));
            }

            $migrated = 0;
            foreach ($page_ids as $page_id) {
                if (self::migrate_single_page($page_id)) {
                    $migrated++;
                }
            }

            $next_offset = $offset + self::BATCH_SIZE;
            $done        = $next_offset >= $total;

            if ($done) {
                update_option(self::OPTION_DONE, time(), false);
                delete_option(self::OPTION_PROGRESS);
            } else {
                update_option(self::OPTION_PROGRESS, array('offset' => $next_offset), false);
            }

            wp_send_json_success(array(
                'migrated'    => $migrated,
                'total'       => $total,
                'next_offset' => $next_offset,
                'done'        => $done,
            ));
        }


        /**
         * هندلر AJAX برای ریست
         */
        public static function handle_reset()
        {
            if (!current_user_can('manage_options')) {
                wp_send_json_error('Permission denied');
            }

            delete_option(self::OPTION_DONE);
            delete_option(self::OPTION_PROGRESS);

            wp_send_json_success('Progress reset');
        }


        /**
         * Migration یک صفحه
         * 
         * @param int $page_id
         * @return bool
         */
        public static function migrate_single_page($page_id)
        {
            $page_id = absint($page_id);
            if (!$page_id) {
                return false;
            }

            $metas = array(
                array(
                    'old' => 'irantheme_meta_data_page_config_page_' . $page_id,
                    'new' => 'irantheme_page_config',
                ),
                array(
                    'old' => 'irantheme_meta_data_header_page_' . $page_id,
                    'new' => 'irantheme_page_header',
                ),
                array(
                    'old' => 'irantheme_meta_data_sections_page_' . $page_id,
                    'new' => 'irantheme_page_sections',
                ),
            );

            $migrated = false;

            foreach ($metas as $meta) {
                // مقدار خام (وردپرس ممکنه unserialize کرده باشه)
                $raw = get_post_meta($page_id, $meta['old'], true);

                if ($raw === '' || $raw === false || $raw === null) {
                    continue;
                }

                // 🔑 تبدیل به array با استاندارد Irantheme_JSON_Helper::decode
                // این تابع خودش همه‌ی حالت‌ها (string JSON, slashed, double-slashed) رو می‌خونه
                $data_array = Irantheme_JSON_Helper::decode($raw);

                // اگه decode موفق نبود ولی داده آرایه بود، مستقیم استفاده کن
                if (empty($data_array) && (is_array($raw) || is_object($raw))) {
                    $data_array = (array) $raw;
                }

                // اگه هنوز خالیه، skip
                if (empty($data_array)) {
                    continue;
                }

                // چک کلید جدید
                $existing = get_post_meta($page_id, $meta['new'], true);
                if ($existing === '' || $existing === false) {
                    // 🔑 ذخیره دقیقاً با استاندارد قالب:
                    // wp_slash(Irantheme_JSON_Helper::encode($array))
                    // - encode → JSON string تمیز
                    // - wp_slash → update_post_meta خودش unslash می‌کنه
                    // - نتیجه: string JSON تمیز در دیتابیس
                    $encoded = Irantheme_JSON_Helper::encode($data_array);

                    update_post_meta(
                        $page_id,
                        $meta['new'],
                        wp_slash($encoded)
                    );

                    $migrated = true;
                }

                // پاک کردن کلید قدیمی
                delete_post_meta($page_id, $meta['old']);
            }

            // HTML deprecated
            delete_post_meta($page_id, 'irantheme_meta_data_html_page_' . $page_id);

            return $migrated;
        }
    }

    add_action('admin_init', array('Irantheme_Dev\\Irantheme_Migration', 'init'));
}
