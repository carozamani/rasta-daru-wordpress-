(function () {
    'use strict';

    // Wait for Rank Math's JS API to be ready
    var checkRankMath = setInterval(function () {
        if (typeof wp === 'undefined' || !wp.hooks) {
            return;
        }
        if (typeof rankMathEditor === 'undefined') {
            return;
        }
        clearInterval(checkRankMath);

        // Register the filter
        wp.hooks.addFilter(
            'rank_math_content',
            'irantheme-page-builder',
            getIranthemeContent,
        );

        // Force Rank Math to re-run the analysis
        if (typeof rankMathEditor !== 'undefined' && rankMathEditor.refresh) {
            rankMathEditor.refresh('content');
        }
    }, 500);

    /**
     * Fetch the page-builder content via AJAX and return it.
     */
    function getIranthemeContent(content) {
        // Prevent infinite loop: if we already appended our content, bail.
        if (content.indexOf('irantheme-section') !== -1) {
            return content;
        }

        var pageId = getPageId();
        if (!pageId) {
            return content;
        }

        // Synchronous-ish fetch using a cached promise
        if (!window._iranthemeContentPromise) {
            window._iranthemeContentPromise = jQuery.post(ajaxurl, {
                action: 'irantheme_get_sections_html',
                page_id: pageId,
                _nonce: iranthemeAjax.nonce,
            });
        }

        // If the promise is already resolved, append immediately
        if (window._iranthemeContentResolved) {
            return content + window._iranthemeContentResolved;
        }

        // Otherwise, kick off async load and return current content.
        // Rank Math will be refreshed again once we have the data.
        window._iranthemeContentPromise.done(function (response) {
            if (response && response.success && response.data.html) {
                window._iranthemeContentResolved = response.data.html;
                if (
                    typeof rankMathEditor !== 'undefined' &&
                    rankMathEditor.refresh
                ) {
                    rankMathEditor.refresh('content');
                }
            }
        });

        return content;
    }

    /**
     * Extract the current post ID from the editor.
     */
    function getPageId() {
        // Gutenberg
        if (
            typeof wp !== 'undefined' &&
            wp.data &&
            wp.data.select('core/editor')
        ) {
            var postId = wp.data.select('core/editor').getCurrentPostId();
            if (postId) {
                return postId;
            }
        }

        // Classic Editor
        var postIdInput = document.getElementById('post_ID');
        if (postIdInput) {
            return postIdInput.value;
        }

        // URL fallback
        var match = window.location.search.match(/[?&]post=(\d+)/);
        if (match) {
            return match[1];
        }

        return null;
    }
})();
