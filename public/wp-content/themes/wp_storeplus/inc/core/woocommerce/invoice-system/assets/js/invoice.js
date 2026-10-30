/**
 * IranTheme Invoice System
 * Smart & Production Ready Print Manager
 */

jQuery(function ($) {
    ('use strict');

    const urlParams = new URLSearchParams(window.location.search);
    const isBulk = urlParams.has('bulk');
    const loader = $('#invoice-loader');

    let printTriggered = false;

    /**
     * Safely triggers the browser print dialog only once.
     * Uses different delays for bulk (1000ms) vs single (300ms) printing.
     * Fades out the loader overlay before calling window.print() if present.
     *
     * @function triggerPrint
     * @returns {void}
     */
    function triggerPrint() {
        if (printTriggered) return;
        printTriggered = true;

        const delay = isBulk ? 1000 : 300;

        setTimeout(function () {
            if (loader.length) {
                loader.fadeOut(250, function () {
                    window.print();
                });
            } else {
                window.print();
            }
        }, delay);
    }

    /**
     * Safely triggers the browser print dialog only once.
     * Uses different delays for bulk (1000ms) vs single (300ms) printing.
     * Fades out the loader overlay before calling window.print() if present.
     *
     * @function triggerPrint
     * @returns {void}
     */
    function waitForImages() {
        const images = $('img').toArray();

        if (!images.length) {
            return Promise.resolve();
        }

        return Promise.all(
            images.map((img) => {
                return new Promise((resolve) => {
                    if (img.complete) {
                        resolve();
                    } else {
                        img.onload = resolve;
                        img.onerror = resolve;
                    }
                });
            }),
        );
    }

    /**
     * Waits for all web fonts to be ready using the Font Loading API.
     * If the API is not available, resolves immediately.
     *
     * @function waitForFonts
     * @returns {Promise<void>} Resolves when fonts are ready or immediately if not supported.
     */
    function waitForFonts() {
        if (document.fonts && document.fonts.ready) {
            return document.fonts.ready;
        }

        return Promise.resolve();
    }

    /**
     * Triggered when the window has fully loaded.
     * Waits for images and fonts, then triggers printing.
     * Includes a 5 second fallback timeout to ensure print always happens.
     *
     * @event window#load
     * @returns {void}
     */
    $(window).on('load', function () {
        Promise.all([waitForImages(), waitForFonts()])
            .then(triggerPrint)
            .catch(triggerPrint);

        // fallback نهایی اگر Promise fail شد
        setTimeout(triggerPrint, 5000);
    });

    /**
     * Click handler for the back button.
     * Navigates back in browser history if possible, otherwise closes the window.
     *
     * @event click#invoice-back-btn
     * @param {Event} e - The click event.
     * @returns {void}
     */
    $(document).on('click', '#invoice-back-btn', function (e) {
        e.preventDefault();

        if (window.history.length > 1) {
            window.history.back();
        } else {
            window.close();
        }
    });

    /**
     * Listens for the print dialog closing event.
     * Logs a message to the console when the print dialog is dismissed.
     *
     * @event MediaQueryList#change
     * @param {MediaQueryListEvent} e - The media query change event.
     * @returns {void}
     */
    if (window.matchMedia) {
        const mediaQueryList = window.matchMedia('print');

        mediaQueryList.addEventListener('change', function (e) {
            if (!e.matches) {
                console.log('Print dialog closed');
            }
        });
    }
});
