/* global YoastSEO, iranthemeYoast */
(function () {
    'use strict';

    /**
     * Page-builder integration for Yoast SEO content analysis.
     *
     * Yoast requires the data to be preloaded before the analysis runs.
     * We fetch the rendered sections once, cache them, and return them
     * synchronously to the modification callback.
     */
    class IranthemeYoastPlugin {
        constructor() {
            // Ensure YoastSEO.js is loaded and ready.
            if (
                typeof YoastSEO === 'undefined' ||
                typeof YoastSEO.app === 'undefined'
            ) {
                return;
            }

            this.sectionsHtml = '';
            this.loaded = false;

            // Register the plugin with Yoast as "loading" — it will wait
            // for us to call pluginReady().
            YoastSEO.app.registerPlugin('IranthemeYoastPlugin', {
                status: 'loading',
            });

            this.registerModifications();
            this.preloadContent();
        }

        /**
         * Register the "content" modification callback.
         */
        registerModifications() {
            const callback = this.addContent.bind(this);
            YoastSEO.app.registerModification(
                'content',
                callback,
                'IranthemeYoastPlugin',
                10,
            );
        }

        /**
         * Preload the page-builder content via AJAX.
         * Once loaded, tell Yoast the plugin is ready so the analysis
         * can run.
         */
        preloadContent() {
            const pageId = this.getPageId();
            if (!pageId) {
                YoastSEO.app.pluginReady('IranthemeYoastPlugin');
                this.loaded = true;
                return;
            }

            jQuery
                .post(iranthemeYoast.ajax_url, {
                    action: 'irantheme_get_sections_html',
                    page_id: pageId,
                    _nonce: iranthemeYoast.nonce,
                })
                .done((response) => {
                    if (
                        response &&
                        response.success &&
                        response.data &&
                        response.data.html
                    ) {
                        this.sectionsHtml = response.data.html;
                    }
                    this.loaded = true;

                    // Tell Yoast we're ready — this triggers a fresh analysis.
                    YoastSEO.app.pluginReady('IranthemeYoastPlugin');

                    // Force a refresh so the new content is picked up.
                    if (typeof YoastSEO.app.refresh === 'function') {
                        YoastSEO.app.refresh();
                    }
                })
                .fail(() => {
                    this.loaded = true;
                    YoastSEO.app.pluginReady('IranthemeYoastPlugin');
                });
        }

        /**
         * The modification callback. Yoast calls this synchronously
         * during analysis, so we can only return what we already have.
         *
         * @param {string} data The current content string.
         * @returns {string} The content with page-builder HTML appended.
         */
        addContent(data) {
            if (!this.loaded || !this.sectionsHtml) {
                return data;
            }

            // Prevent double-appending on repeated calls.
            if (data.indexOf('irantheme-section') !== -1) {
                return data;
            }

            return data + this.sectionsHtml;
        }

        /**
         * Extract the current post ID from the editor.
         *
         * @returns {string|null}
         */
        getPageId() {
            // Classic Editor.
            const classicInput = document.getElementById('post_ID');
            if (classicInput && classicInput.value) {
                return classicInput.value;
            }

            // Gutenberg.
            if (
                typeof wp !== 'undefined' &&
                wp.data &&
                wp.data.select('core/editor')
            ) {
                const id = wp.data.select('core/editor').getCurrentPostId();
                if (id) {
                    return String(id);
                }
            }

            // Fallback: query string.
            const match = window.location.search.match(/[?&]post=(\d+)/);
            if (match) {
                return match[1];
            }

            return null;
        }
    }

    /**
     * Initialize after YoastSEO.js is ready.
     */
    if (
        typeof YoastSEO !== 'undefined' &&
        typeof YoastSEO.app !== 'undefined'
    ) {
        new IranthemeYoastPlugin();
    } else {
        jQuery(window).on('YoastSEO:ready', function () {
            new IranthemeYoastPlugin();
        });
    }
})();
