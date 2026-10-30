/* ===============================================================
Init preloader
=============================================================== */
document.onreadystatechange = function () {
    if (document.readyState !== 'complete') {
        jQuery('.irantheme-preloader').show();
    }
};

/* ===============================================================
Page builder on load
=============================================================== */
jQuery(function (jQuery) {
    pageBuilderInit();
});

/* ===============================================================
Init page builder on load
=============================================================== */
function pageBuilderInit() {
    // Reload iframe url
    pageBuilderReloadIframe();
    // Init iframe on load
    pageBuilderIframeInit();
    // Init masonry
    pageBuilderInitSectionMasonry();
    // Init panel ajax close button
    initClosePanelAjax();
    // Init panel ajax save button
    initSavePanelAjax();
    // Init default settings
    initRestoreToDefaultVisual();
    // Init all settings options
    initSettingsOptionsPanelAjax();
    // Init add section to iframe
    addSectionToDemo();
    // Init add template to iframe
    createTemplateForDemo();
    // Init magnification zoom section thumbnail
    iranthemeMagnificationSearchSection();
    // Init current page config settings
    iranthemeEditPageConfig();
    // Prevent to target of links disabled
    pageBuilderPreventTargetButtonDisabled();
    // Toggle fullscreen for panel and preview
    pageBuilderToggleFullscreenPanelAndPreview();
    // Init side toggle of panel
    pageBuilderPanelScreenToggle();
    // Init swiper
    pageBuilderInitSwiper();
}

/* ===============================================================
Init side toggle of panel
=============================================================== */
function pageBuilderPanelScreenToggle() {
    // Panel side toggle
    jQuery('.irantheme-page-builder-panel-side-toggle').on(
        'click',
        function () {
            jQuery('.irantheme-page-builder-holder-panel').toggleClass(
                'active',
            );
            jQuery('.irantheme-page-builder-holder-preview').toggleClass(
                'active',
            );
        },
    );

    // Different screen toggle
    jQuery('.irantheme-settings-visual-panel-ajax-header-media-toggle').on(
        'click',
        function (e) {
            e.preventDefault();
            jQuery(
                '.irantheme-settings-visual-panel-ajax-header-media-toggle',
            ).removeClass('active');
            jQuery(this).addClass('active');
            if (
                jQuery(this).hasClass(
                    'irantheme-settings-visual-panel-ajax-header-media-toggle-mobile',
                )
            ) {
                // Mobile screen toggle
                jQuery('.irantheme-page-builder-preview-iframe').animate({
                    width: '360px',
                });
            } else if (
                jQuery(this).hasClass(
                    'irantheme-settings-visual-panel-ajax-header-media-toggle-tablet',
                )
            ) {
                // Tablet screen toggle
                jQuery('.irantheme-page-builder-preview-iframe').animate({
                    width: '600px',
                });
            } else if (
                jQuery(this).hasClass(
                    'irantheme-settings-visual-panel-ajax-header-media-toggle-desktop',
                )
            ) {
                // Desktop screen toggle
                jQuery('.irantheme-page-builder-preview-iframe').animate({
                    width: '100%',
                });
            }
        },
    );
}

/* ===============================================================
Init swiper
=============================================================== */
function pageBuilderInitSwiper() {
    if (getUrlParameter('action') == 'storeplus_page_builder') {
        var swiper = new Swiper('.irantheme-page-builder-panel-guide-swiper', {
            pagination: {
                el: '.swiper-pagination',
                dynamicBullets: true,
            },
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
        });
    }
}

/* ===============================================================
Init iframe on load
=============================================================== */
function pageBuilderIframeInit() {
    // Remove wp admin bar in iframe
    jQuery('.irantheme-page-builder-preview-iframe').on('load', function () {
        if (
            jQuery('.irantheme-page-builder-preview-iframe')[0].contentWindow
                .iranthemeWpData.pageBuilderStoreplusEditable != 1
        ) {
            let iframeUrl = jQuery('.irantheme-page-builder-preview-iframe')[0]
                .contentWindow.location.href;
            window.location = iframeUrl;
        }
        // Check url change in iframe and validation of page builder
        if (
            jQuery('.irantheme-page-builder-preview-iframe')[0].contentWindow
                .iranthemeWpData.currentPageId != iranthemeWpData.postId
        ) {
            window.location = jQuery(
                '.irantheme-page-builder-preview-iframe',
            )[0].contentWindow.iranthemeWpData.currentUrl;
        }
        // Set iframe dynamic height
        iframeDynamicLoaded();
        // Add sortable to objects in sections
        jQuery('.irantheme-page-builder-preview-iframe')
            .contents()
            .find('body')
            .append(
                jQuery(
                    `<script type='text/javascript'>jQuery('.irantheme-object-group:not(.irantheme-no-sort)').sortable({opacity:0.8});</script>`,
                ),
            );
        // Remove wpadminbar from iframe with style tag injection
        jQuery('.irantheme-page-builder-preview-iframe')
            .contents()
            .find('head')
            .append(
                jQuery(
                    "<style type='text/css'>html{margin-top:0 !important;}#wpadminbar{height:0 !important;display:none !important;visibility:hidden;opacity:0;}</style><script type='text/javascript'>let wpAdminBarHeight = jQuery('#wpadminbar').height();let heightResult = jQuery(window).height() - wpAdminBarHeight;jQuery('.irantheme-navigation').css('top', wpAdminBarHeight);jQuery('.irantheme-navigation').height(heightResult);</script>",
                ),
            );
        // Init hidden required
        pageBuilderInitHiddenRequired();
        // Init actions of sections buttons
        pageBuilderAddActionsToButtonsSections();
        // Init actions of objects buttons
        pageBuilderAddActionsToButtonsObjects();
        // Init section adder
        pageBuilderInitSectionAdder();
        // Init template adder
        pageBuilderInitTemplateAdder();
        // Init export import page
        pageBuilderInitExportImportPage();
        // Hide main preloader
        jQuery('.irantheme-preloader').fadeOut();
        jQuery('.irantheme-page-builder-preview-iframe')
            .contents()
            .find('body')
            .on('click', 'a', function (e) {
                // Disable all links in builder editing
                e.preventDefault();
            });
        console.log('End init iframe');
    });
}

/* ===============================================================
Reload iframe
=============================================================== */
function pageBuilderReloadIframe() {
    jQuery('.irantheme-page-builder-preview-iframe').attr(
        'src',
        function (i, val) {
            return val;
        },
    );
}

/* ===============================================================
Add action buttons to iframe sections
=============================================================== */
function pageBuilderInitHiddenRequired() {
    // Get iframe body
    let body = jQuery('.irantheme-page-builder-preview-iframe')
        .contents()
        .find('body');
    // Check element existing
    if (
        jQuery(body).length > 0 &&
        jQuery(body).hasClass('irantheme-page-builder-inactive')
    ) {
        jQuery(body).removeClass('irantheme-page-builder-inactive');
    }
}

/* ===============================================================
Init section adder
=============================================================== */
function pageBuilderInitSectionMasonry() {
    /* ===============================================================
    Init masonry sections
    =============================================================== */
    var grid = document.querySelector('.irantheme-sections-masonry');
    // Initialize imagesLoaded
    imagesLoaded(grid, function () {
        var masonrySections = new Masonry(grid, {
            itemSelector: '.irantheme-sections-masonry-item',
            columnWidth: '.irantheme-sections-masonry-sizer',
            percentPosition: true,
            gutter: 10,
            // horizontalOrder: true,
            // fitWidth: true,
            originLeft: false,
            transitionDuration: '0',
            // initLayout: false,
            // disable window resize behavior
            // resize: false,
            stagger: 30,
            // containerStyle: null,
            // originTop: false,
            // stamp: '.stamp',
        });
        // Layout Masonry after each image loads
        // masonrySections.imagesLoaded().progress(function () {
        //     masonrySections.masonry('layout');
        // });
    });
    // masonrySections.masonry('reloadItems');
    // masonrySections.masonry('layout');
}

/* ===============================================================
Init section adder
=============================================================== */
function pageBuilderInitSectionAdder() {
    /* ===============================================================
    Open panel sections
    =============================================================== */
    let sectionAdderButton = jQuery('.irantheme-page-builder-preview-iframe')
        .contents()
        .children()
        .find(
            '.irantheme-page-builder-holder-adders .irantheme-page-builder-section-adder-open',
        );
    jQuery(sectionAdderButton).on('click', function () {
        jQuery('.irantheme-settings-visual-sections').addClass('active');
    });

    /* ===============================================================
    Close panel sections
    =============================================================== */
    jQuery(
        '.irantheme-settings-visual-sections .irantheme-settings-visual-sections-header-close-panel',
    ).on('click', function () {
        jQuery(this)
            .parents('.irantheme-settings-visual-sections')
            .removeClass('active');
    });

    /* ===============================================================
    Search sections
    =============================================================== */
    jQuery('.irantheme-settings-visual-sections-search-input').on(
        'input',
        function () {
            // Get search box input current text
            let TextInput = jQuery(this).val();
            // All sections list
            let sections = jQuery(
                '.irantheme-settings-visual-sections .irantheme-sections-thumb-list .irantheme-sections-thumb-item',
            );
            // Get category sections list
            let categoryActiveType = jQuery(
                '.irantheme-settings-visual-sections .irantheme-settings-visual-sections-categories .irantheme-settings-visual-sections-categories-item',
            ).attr('data-value');
            // Check input length
            if (TextInput.length > 0) {
                // Hide all sections
                jQuery(sections)
                    .parents('.irantheme-sections-thumb-item-holder')
                    .hide();
                jQuery(sections).each(function (index, element) {
                    // Get current section text
                    let targetText = jQuery(element).data('keywords');
                    // Check current section text with search input text
                    if (targetText.indexOf(TextInput) != -1) {
                        // Check section type with category types
                        if (
                            jQuery(element).attr('data-type') ==
                                categoryActiveType ||
                            categoryActiveType == 'all'
                        ) {
                            jQuery(element)
                                .parents(
                                    '.irantheme-sections-thumb-item-holder',
                                )
                                .show();
                        }
                    }
                });
            } else {
                // Return to last mode after clear search input
                // Hide all sections
                jQuery(sections)
                    .parents('.irantheme-sections-thumb-item-holder')
                    .hide();
                jQuery(sections).each(function (index, element) {
                    // Check section type with category types
                    if (
                        jQuery(element).attr('data-type') ==
                            categoryActiveType ||
                        categoryActiveType == 'all'
                    ) {
                        jQuery(element)
                            .parents('.irantheme-sections-thumb-item-holder')
                            .show();
                    }
                });
            }
            // Init masonry
            pageBuilderInitSectionMasonry();
        },
    );

    /* ===============================================================
    Filter selected
    =============================================================== */
    // Toggle section with categories =======================================
    jQuery(
        '.irantheme-settings-visual-sections .irantheme-settings-visual-sections-categories .irantheme-settings-visual-sections-categories-item',
    ).on('click', function () {
        // Check current item has class or no
        if (jQuery(this).hasClass('active')) {
            return;
        }
        // Remove class active of all items
        jQuery(
            '.irantheme-settings-visual-sections .irantheme-settings-visual-sections-categories .irantheme-settings-visual-sections-categories-item',
        ).removeClass('active');
        // Active current item
        jQuery(this).addClass('active');
        // Filtering
        filterSelected(this);
    });
}

/* ===============================================================
Init template adder
=============================================================== */
function pageBuilderInitTemplateAdder() {
    /* ===============================================================
    Open panel templates
    =============================================================== */
    let templateAdderButton = jQuery('.irantheme-page-builder-preview-iframe')
        .contents()
        .children()
        .find(
            '.irantheme-page-builder-holder-adders .irantheme-page-builder-template-adder-open',
        );

    jQuery(templateAdderButton).on('click', function () {
        jQuery('.irantheme-settings-visual-templates').addClass('active');
        // Fetch templates data from table with ajax
        fetchTemplatesDataTable();
    });

    /* ===============================================================
    Close panel templates
    =============================================================== */
    jQuery(
        '.irantheme-settings-visual-templates .irantheme-settings-visual-templates-header-close-panel',
    ).on('click', function () {
        jQuery(this)
            .parents('.irantheme-settings-visual-templates')
            .removeClass('active');
    });

    /* ===============================================================
    Search templates
    =============================================================== */
    jQuery('.irantheme-settings-visual-templates-search-input').on(
        'input',
        function () {
            // Get search box input current text
            let TextInput = jQuery(this).val();
            // All templates list
            let templates = jQuery(
                '.irantheme-settings-visual-templates .irantheme-templates-table .irantheme-templates-table-tr',
            );
            // Check input length
            if (TextInput.length > 0) {
                // Hide all templates
                jQuery(templates).hide();
                jQuery(templates).each(function (index, template) {
                    // Get current template text
                    let targetText = jQuery(template).data('keywords');
                    // Check current template text with search input text
                    if (targetText.indexOf(TextInput) != -1) {
                        jQuery(template).show();
                    }
                });
            } else {
                // Show all templates
                jQuery(templates).show();
            }
        },
    );
}

/* ===============================================================
Init export import page
=============================================================== */
function pageBuilderInitExportImportPage() {
    // Open panel export import
    let exportImportOpenButton = jQuery(
        '.irantheme-page-builder-preview-iframe',
    )
        .contents()
        .children()
        .find(
            '.irantheme-page-builder-holder-adders .irantheme-page-builder-export-import-open',
        );

    jQuery(exportImportOpenButton).on('click', function () {
        jQuery('.irantheme-settings-visual-export-import').addClass('active');
    });

    // Close panel export import
    jQuery(
        '.irantheme-settings-visual-export-import .irantheme-settings-visual-export-import-header-close-panel',
    ).on('click', function () {
        jQuery(this)
            .parents('.irantheme-settings-visual-export-import')
            .removeClass('active');
    });

    const fileInput = jQuery('#irantheme-fileInput');
    const uploadArea = jQuery('#irantheme-uploadArea');
    const fileInfo = jQuery('#irantheme-fileInfo');
    const fileName = jQuery('#irantheme-fileName');
    const fileSize = jQuery('#irantheme-fileSize');
    const uploadButton = jQuery('#irantheme-importPageButton');
    const progressBarImport = jQuery('#irantheme-progressBarImport');
    const progressImport = jQuery('#irantheme-progressImport');
    const importContainer = jQuery('.irantheme-upload-container').first();

    // Flag to prevent recursive triggering
    let isHandlingClick = false;

    // Click on upload area triggers file input
    uploadArea.on('click', function (e) {
        if (isHandlingClick) return;
        isHandlingClick = true;

        e.stopPropagation();
        fileInput.trigger('click');

        // Reset the flag after a short delay
        setTimeout(() => {
            isHandlingClick = false;
        }, 100);
    });

    // Handle drag and drop events
    uploadArea.on('dragover', function (e) {
        e.preventDefault();
        uploadArea.addClass('active');
    });

    uploadArea.on('dragleave', function () {
        uploadArea.removeClass('active');
    });

    uploadArea.on('drop', function (e) {
        e.preventDefault();
        uploadArea.removeClass('active');

        if (e.originalEvent.dataTransfer.files.length) {
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(e.originalEvent.dataTransfer.files[0]);
            fileInput[0].files = dataTransfer.files;
            handleFileSelection();
        }
    });

    // Handle file selection
    fileInput.on('change', handleFileSelection);

    function handleFileSelection() {
        const file = fileInput[0].files[0]; // Access DOM element directly

        if (file) {
            // Display file info
            fileInfo.show();
            fileName.text(file.name);
            fileSize.text(formatFileSize(file.size));

            // Enable upload button
            uploadButton.prop('disabled', false);

            // Check file size (10MB limit)
            if (file.size > 10 * 1024 * 1024) {
                alert('حجم فایل انتخابی بیشتر از ۱۰ مگابایت است.');
                resetFileInput();
            }
        } else {
            resetFileInput();
        }
    }

    // Upload button click handler
    uploadButton.on('click', function () {
        if (!fileInput[0].files.length) return;

        const file = fileInput[0].files[0];

        // Validate file type
        if (!file.name.endsWith('.xml')) {
            alert('لطفاً یک فایل XML انتخاب کنید.');
            resetFileInput();
            return;
        }

        // Disable button and show progress
        uploadButton.prop('disabled', true);
        uploadButton.text('در حال درون ریزی...');
        progressBarImport.show();
        progressImport.css('width', '0%');

        // Add loading class to container
        importContainer.addClass('loading');

        const formData = new FormData();
        formData.append('action', 'demo_page_import');
        formData.append('page_id', getUrlParameter('post_id'));
        formData.append('xml_file', file);
        formData.append('security', iranthemeWpData.nonce); // Add nonce for security

        // Get admin ajax URL
        const ajax_url = iranthemeWpData.adminAjax;

        // Send request
        jQuery.ajax({
            url: ajax_url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json', // Expect JSON response
            xhr: function () {
                const xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener('progress', function (e) {
                    if (e.lengthComputable) {
                        const percent = Math.round((e.loaded / e.total) * 100);
                        progressImport.css('width', percent + '%');
                    }
                });

                // Track download progress too
                xhr.addEventListener('progress', function (e) {
                    if (e.lengthComputable && e.total) {
                        const percent = Math.round((e.loaded / e.total) * 100);
                        progressImport.css(
                            'width',
                            Math.min(99, percent) + '%',
                        );
                    }
                });

                return xhr;
            },
            beforeSend: function () {
                // Show progress bar at 5% to indicate start
                progressImport.css('width', '5%');
            },
            success: function (response) {
                console.log(response);

                if (response && response.success) {
                    progressImport.css('width', '100%');
                    setTimeout(function () {
                        progressBarImport.hide();
                        alert(response.data.message);
                        resetFileInput();

                        // Reset button state
                        uploadButton.prop('disabled', false);
                        uploadButton.text('شروع درون ریزی');
                        importContainer.removeClass('loading');

                        // Optionally refresh the page or update content
                        if (response.data.refresh || response.data.reload) {
                            setTimeout(function () {
                                location.reload();
                            }, 1000);
                        }
                    }, 500);
                } else {
                    alert((response && response.data) || 'خطای نامشخص از سرور');
                    progressBarImport.hide();

                    // Reset button state on error
                    uploadButton.prop('disabled', false);
                    uploadButton.text('شروع درون ریزی');
                    importContainer.removeClass('loading');
                }
            },
            error: function (xhr, status, error) {
                let errorMessage = 'خطا در ارسال فایل. لطفاً دوباره تلاش کنید.';

                try {
                    const serverResponse = JSON.parse(xhr.responseText);
                    errorMessage = serverResponse.data || errorMessage;
                } catch (e) {
                    console.error(
                        'Failed to parse error response:',
                        xhr.responseText,
                    );
                }

                alert(errorMessage);
                progressBarImport.hide();

                // Reset button state on error
                uploadButton.prop('disabled', false);
                uploadButton.text('شروع درون ریزی');
                importContainer.removeClass('loading');

                console.error('AJAX Error:', error, xhr.responseText);
            },
            complete: function () {
                // Remove loading class if not already removed
                importContainer.removeClass('loading');
            },
        });
    });

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';

        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));

        return parseFloat((bytes / Math.pow(k, i)).toFixed(2) + ' ' + sizes[i]);
    }

    function resetFileInput() {
        fileInput.val('');
        fileInfo.hide();
        uploadButton.prop('disabled', true);
        uploadButton.text('شروع درون ریزی');
        progressBarImport.hide();
        progressImport.css('width', '0%');
    }

    // Export mode ==============================================================
    const exportButton = jQuery('#irantheme-exportPageButton');
    const progressBarExport = jQuery('#irantheme-progressBarExport');
    const progressExport = jQuery('#irantheme-progressExport');
    const successMessage = jQuery('#irantheme-successMessage');
    const extraOption = jQuery('#irantheme-includeMetadata');
    const exportContainer = jQuery('.irantheme-upload-container').last();

    // Export button click handler
    exportButton.on('click', function () {
        // Show progress bar
        progressBarExport.show();
        progressExport.css('width', '0%');
        successMessage.hide();
        exportButton.prop('disabled', true);
        exportButton.text('در حال برون ریزی...');

        // Add loading class to container
        exportContainer.addClass('loading');

        // Simulate export processing (replace with actual export logic)
        simulateExportProcessing(function () {
            // On completion
            progressExport.css('width', '100%');

            setTimeout(function () {
                progressBarExport.hide();
                successMessage.show();
                exportButton.prop('disabled', false);
                exportButton.text('شروع برون ریزی');
                exportContainer.removeClass('loading');

                // Create and trigger download
                triggerFileDownload();
            }, 500);
        });
    });

    // Function to trigger XML file download
    function triggerFileDownload() {
        // Get admin url ajax from demo
        var ajax_url = iranthemeWpData.adminAjax;
        // Check extra option export
        let extraData = 0;
        if (jQuery(extraOption).is(':checked')) extraData = 1;
        // Get current page id
        let pageId = getUrlParameter('post_id');
        // Send request
        jQuery.ajax({
            url: ajax_url,
            type: 'post',
            data: {
                action: 'demo_page_export',
                page_id: pageId,
                extra_data: extraData,
                security: iranthemeWpData.nonce, // Add security nonce
            },
            dataType: 'text',
            beforeSend: (xhr) => {
                // Optional: Add loading indicator for download
            },
            success: (response) => {
                jQuery(successMessage)
                    .find('.irantheme-success-export-download')
                    .attr('href', response)
                    .removeClass('d-none');

                // Auto-click download link
                jQuery(successMessage)
                    .find('.irantheme-success-export-download')[0]
                    .click();
            },
            complete: (response) => {
                // Reset progress bar
                progressExport.css('width', '0%');
            },
            error: (e) => {
                console.log('Error: ' + e.responseText);
                alert('خطا در ایجاد فایل برون ریزی. لطفاً دوباره تلاش کنید.');

                // Reset button state on error
                exportButton.prop('disabled', false);
                exportButton.text('شروع برون ریزی');
                exportContainer.removeClass('loading');
            },
        });
    }

    // Simulate export processing (for demo purposes)
    function simulateExportProcessing(callback) {
        let progressValue = 0;
        const interval = setInterval(function () {
            progressValue += Math.random() * 20;
            if (progressValue >= 100) {
                progressValue = 100;
                clearInterval(interval);
                callback();
            }
            progressExport.css('width', progressValue + '%');
        }, 150);
    }
}

/* ===============================================================
Prevent to target of links disabled
=============================================================== */
function pageBuilderPreventTargetButtonDisabled() {
    // Prevent to link target in next and prev page button
    jQuery(
        '.irantheme-visual-panel-ajax-next.irantheme-button-disabled, .irantheme-visual-panel-ajax-prev.irantheme-button-disabled',
    ).on('click', function (e) {
        e.preventDefault();
    });
}

/* ===============================================================
Toggle fullscreen for panel and preview
=============================================================== */
function pageBuilderToggleFullscreenPanelAndPreview() {
    // Toggle full screen panel and preview
    jQuery('.irantheme-visual-panel-ajax-increase').on('click', function () {
        jQuery('.irantheme-page-builder-holder-preview').toggleClass(
            'fullscreen',
        );
        jQuery('.irantheme-page-builder-holder-panel').toggleClass(
            'fullscreen',
        );
        // Notification mode
        if (
            jQuery('.irantheme-page-builder-holder-panel').hasClass(
                'fullscreen',
            )
        ) {
            jQuery('.irantheme-page-builder-panel-addons-note').addClass(
                'active',
            );
            jQuery('.irantheme-page-builder-panel-addons-note').text(
                'حالت بزرگ نمایی پنل',
            );
        } else {
            jQuery('.irantheme-page-builder-panel-addons-note').text(
                'حالت معمولی پنل',
            );
            jQuery('.irantheme-page-builder-panel-addons-note').removeClass(
                'active',
            );
        }
    });
}

/*===============================================================
Section feature add in holder sections on click (add)
=============================================================== */
function addSectionToDemo() {
    jQuery('.irantheme-settings-visual-sections').on(
        'click',
        '.irantheme-sections-thumb-item .irantheme-sections-thumb-feature-item',
        function (e) {
            e.preventDefault();
            // Check action running or no
            if (jQuery(this).hasClass('irantheme-button-disable')) {
                return;
            }
            // Add disable button for prevent twice action
            jQuery(jQuery(this)).addClass('irantheme-button-disable');
            // Disable add buttons actions in section adder
            jQuery(
                '.irantheme-sections-thumb-item .irantheme-sections-thumb-feature-item',
            )
                .not(jQuery(this))
                .prop('disabled', true);
            // Add class cursor wait to section holder
            jQuery('.irantheme-settings-visual-sections').addClass(
                'cursor-wait',
            );
            // Get spinner icon alt
            let spinnerAlt = jQuery(this).find('.irantheme-animate-rotate');
            // Get add icon
            let addBtn = jQuery(this).find('.fa-light.fa-download');
            jQuery(this).find('em').text('لطفا منتظر بمانید...');
            addBtn.hide();
            spinnerAlt.show();
            // Get target click
            let target = jQuery(e.target);

            // Check target tag name
            if (
                jQuery(e.target).prop('tagName') == 'I' ||
                jQuery(e.target).prop('tagName') == 'EM'
            ) {
                // Return to main parent button action
                target = jQuery(target).parents(
                    '.irantheme-sections-thumb-feature-item',
                );
            }

            // Get current section from target
            let section = jQuery(target).parents(
                '.irantheme-sections-thumb-item',
            );

            // Check data button action
            if (jQuery(target).data('action') == 'add') {
                // Get required data from section tab
                let dataType = jQuery(section).attr('data-type');
                let dataValue = jQuery(section).attr('data-value');
                // Get page id from holder sections demo
                let dataPage = getUrlParameter('post_id');
                // Create and check ajax code with another ajax codes
                let dataAjax;
                do {
                    dataAjax = getAjaxCode(16);
                } while (iranthemeCheckAjaxCode(dataAjax));
                {
                    dataAjax = getAjaxCode(16);
                }
                // Set current item (Section) settings
                createPanelFieldsAjaxOnAddSection(
                    dataType,
                    dataValue,
                    dataPage,
                    dataAjax,
                    jQuery(this),
                );
            }
        },
    );
}

/*===============================================================
Create fields section ajax on Add section +++++++++++++++++++++++
=============================================================== */
function createPanelFieldsAjaxOnAddSection(
    dataType,
    dataValue,
    dataPage,
    dataAjax,
    spinner,
) {
    // Get admin ajax url
    var ajax_url = iranthemeWpData.adminAjax;
    // Get spinner icon alt
    let spinnerAlt = jQuery(spinner).find('.irantheme-animate-rotate');
    // Get checkmark icon
    let checkMark = jQuery(spinner).find('.irantheme-success-checkmark');
    // Get add icon
    let addBtn = jQuery(spinner).find('.fa-light.fa-download');
    // Post fetch =============================================
    jQuery.ajax({
        url: ajax_url,
        type: 'post',
        data: {
            action: 'add_section',
            data_type: dataType,
            data_value: dataValue,
            data_page: dataPage,
            data_ajax: dataAjax,
        },
        dataType: 'html',
        beforeSend: (response) => {},
        success: (response) => {
            console.log('Success! create options on Add...');
            // Spinner hide and success section adder
            spinnerAlt.hide();
            checkMark.show();
            jQuery(spinner).find('em').text('با موفقیت اضافه شد');
            jQuery(spinner).addClass(
                'irantheme-sections-thumb-feature-item-success',
            );
            setTimeout(() => {
                pageBuilderReloadIframe();
            }, 500);
            // Return button section adder to default mode after 3000ms
            setTimeout(() => {
                checkMark.hide();
                jQuery(spinner).find('em').text('بارگذاری');
                addBtn.show();
                jQuery(spinner).removeClass(
                    'irantheme-sections-thumb-feature-item-success',
                );
                // Enable action (Add section)
                jQuery(spinner).removeClass('irantheme-button-disable');
                // Enable all buttons actions in section adder
                jQuery(
                    '.irantheme-sections-thumb-item .irantheme-sections-thumb-feature-item',
                ).prop('disabled', false);
                // Remove class cursor wait to section holder
                jQuery('.irantheme-settings-visual-sections').removeClass(
                    'cursor-wait',
                );
            }, 1500);
        },
        complete: (response) => {},
        error: (e) => {
            console.log('Error: ' + e.responseText);
        },
    });
}

/* ===============================================================
Request ajax for fetch templates
=============================================================== */
function fetchTemplatesDataTable() {
    // Get admin url action
    let adminUrl = iranthemeWpData.adminAjax;
    // Send data to admin url with ajax
    jQuery.ajax({
        url: adminUrl,
        type: 'post',
        data: {
            action: 'fetch_templates',
        },
        dataType: 'html',
        // processData: false,
        // contentType: false,
        beforeSend: (xhr) => {
            // Enable preloader in preview
            jQuery(
                '.irantheme-settings-visual-templates .irantheme-preloader-abs',
            ).addClass('active');
            // Remove all prev data
            jQuery(
                '.irantheme-settings-visual-templates .irantheme-templates-holder .irantheme-templates-table',
            ).remove();
        },
        success: (response) => {
            jQuery(
                '.irantheme-settings-visual-templates .irantheme-templates-holder',
            ).append(response);
        },
        complete: (response) => {
            console.log('Successfully added to templates!');
            setTimeout(() => {
                // Disable preloader in panel
                jQuery(
                    '.irantheme-settings-visual-templates .irantheme-preloader-abs',
                ).removeClass('active');
            }, 250);
        },
        error: (e) => {
            console.log('Error' + e.responseText);
        },
    });
}

/*===============================================================
Template feature add in holder templates on click (add)
=============================================================== */
function createTemplateForDemo() {
    jQuery('.irantheme-settings-visual-templates').on(
        'click',
        '.irantheme-templates-table-tr .irantheme-template-table-tr-action',
        function (e) {
            e.preventDefault();

            // Check action running or no
            if (jQuery(this).hasClass('irantheme-button-disable')) {
                return;
            }
            // Add disable button for prevent twice action
            jQuery(jQuery(this)).addClass('irantheme-button-disable');
            // Disable add buttons actions in template adder
            jQuery(
                '.irantheme-templates-table-tr .irantheme-template-table-tr-action',
            )
                .not(jQuery(this))
                .prop('disabled', true);
            // Add class cursor wait to template holder
            jQuery('.irantheme-settings-visual-templates').addClass(
                'cursor-wait',
            );
            // Get spinner icon alt
            let spinnerAlt = jQuery(this).find('.irantheme-animate-rotate');
            // Get add icon
            spinnerAlt.show();
            // Get target click
            let target = jQuery(e.target);

            // Check target tag name
            if (
                jQuery(e.target).prop('tagName') == 'I' ||
                jQuery(e.target).prop('tagName') == 'EM'
            ) {
                // Return to main parent button action
                target = jQuery(target).parents(
                    '.irantheme-template-table-tr-action',
                );
            }

            // Get current template from target
            let template = jQuery(target).parents(
                '.irantheme-templates-table-tr',
            );

            // Check data button action
            if (jQuery(target).data('action') == 'add') {
                let addBtn = jQuery(this).find('.fa-light.fa-download');
                addBtn.hide();
                // Get required data from template tab
                let dataType = jQuery(template).attr('data-type');
                let dataValue = jQuery(template).attr('data-value');
                // Get page id from holder templates demo
                let dataPage = getUrlParameter('post_id');
                // Create and check ajax code with another ajax codes
                let dataAjax = jQuery(template).attr('data-ajax');
                // Set current item (Template) settings
                addTemplateToDemo(
                    dataType,
                    dataValue,
                    dataPage,
                    dataAjax,
                    jQuery(this),
                );
            } else if (jQuery(target).data('action') == 'remove') {
                let addBtn = jQuery(this).find('.fa-light.fa-trash-xmark');
                addBtn.hide();
                // Get required data from template tab
                let dataType = jQuery(template).attr('data-type');
                let dataValue = jQuery(template).attr('data-value');
                // Get page id from holder templates demo
                let dataPage = getUrlParameter('post_id');
                // Create and check ajax code with another ajax codes
                let dataAjax = jQuery(template).attr('data-ajax');
                // Set current item (Template) settings
                removeTemplateFromTemplates(
                    dataType,
                    dataValue,
                    dataPage,
                    dataAjax,
                    jQuery(this),
                );
            }
        },
    );
}

/*===============================================================
Create fields section ajax on Add template +++++++++++++++++++++++
=============================================================== */
function addTemplateToDemo(dataType, dataValue, dataPage, dataAjax, spinner) {
    // Get admin ajax url
    var ajax_url = iranthemeWpData.adminAjax;
    // Get spinner icon alt
    let spinnerAlt = jQuery(spinner).find('.irantheme-animate-rotate');
    // Get checkmark icon
    let checkMark = jQuery(spinner).find('.irantheme-success-checkmark');
    // Get add icon
    let addBtn = jQuery(spinner).find('.fa-light.fa-download');
    // Post fetch =============================================
    jQuery.ajax({
        url: ajax_url,
        type: 'post',
        data: {
            action: 'add_template',
            data_type: dataType,
            data_value: dataValue,
            data_page: dataPage,
            data_ajax: dataAjax,
        },
        dataType: 'html',
        beforeSend: (response) => {},
        success: (response) => {
            console.log('Template added successfully!');
            // Spinner hide and success template adder
            spinnerAlt.hide();
            checkMark.show();
            // Return button template adder to default mode after 3000ms
            setTimeout(() => {
                checkMark.hide();
                addBtn.show();
                jQuery(spinner).removeClass(
                    'irantheme-template-table-tr-action-success',
                );
                // Enable action (Add template)
                jQuery(spinner).removeClass('irantheme-button-disable');
                // Enable all buttons actions in template adder
                jQuery(
                    '.irantheme-templates-table-tr .irantheme-template-table-tr-action',
                ).prop('disabled', false);
                // Remove class cursor wait to template holder
                jQuery('.irantheme-settings-visual-templates').removeClass(
                    'cursor-wait',
                );
                // Tip to success
                createTip('info', 'الگو با موفقیت اضافه شد.');
                // Reload iframe
                pageBuilderReloadIframe();
            }, 1500);
        },
        complete: (response) => {},
        error: (e) => {
            console.log('Error: ' + e.responseText);
        },
    });
}

/*===============================================================
Create fields section ajax on remove template +++++++++++++++++++++++
=============================================================== */
function removeTemplateFromTemplates(
    dataType,
    dataValue,
    dataPage,
    dataAjax,
    spinner,
) {
    // Get admin ajax url
    var ajax_url = iranthemeWpData.adminAjax;
    // Get spinner icon alt
    let spinnerAlt = jQuery(spinner).find('.irantheme-animate-rotate');
    // Get checkmark icon
    let checkMark = jQuery(spinner).find('.irantheme-success-checkmark');

    // Confirm template removing
    if (!confirm('آیا از حذف الگو مطمئن هستید؟')) {
        spinnerAlt.hide();
        checkMark.show();
        jQuery(spinner).find('em').text('حذف کردن');
        return;
    }
    // Post fetch =============================================
    jQuery.ajax({
        url: ajax_url,
        type: 'post',
        data: {
            action: 'remove_template',
            data_type: dataType,
            data_value: dataValue,
            data_page: dataPage,
            data_ajax: dataAjax,
        },
        dataType: 'html',
        beforeSend: (response) => {},
        success: (response) => {
            console.log('Template removed successfully!');
            // Spinner hide and success template adder
            spinnerAlt.hide();
            checkMark.show();
            // Fetch templates data from table with ajax
            fetchTemplatesDataTable();
        },
        complete: (response) => {
            // Tip to success
            createTip('success', 'الگو با موفقیت حذف شد.');
            // Fade template and remove
            setTimeout(() => {
                // Remove class cursor wait to template holder
                jQuery('.irantheme-settings-visual-templates').removeClass(
                    'cursor-wait',
                );
            }, 1000);
        },
        error: (e) => {
            console.log('Error: ' + e.responseText);
        },
    });
}

/* ===============================================================
Filter selected
=============================================================== */
function filterSelected(current) {
    // Hide all sections
    jQuery(
        '.irantheme-settings-visual-sections .irantheme-sections-thumb-item-holder',
    ).hide();
    // Get current data type from option selected
    let currentDataType = jQuery(current).data('value');
    // Check current data type with all type
    if (currentDataType == 'all') {
        jQuery(
            '.irantheme-settings-visual-sections .irantheme-sections-thumb-item-holder',
        ).fadeIn(100);
    } else {
        // Loop from all sections
        jQuery(
            '.irantheme-settings-visual-sections .irantheme-sections-thumb-item',
        ).each(function (index, element) {
            if (jQuery(element).data('type').indexOf(currentDataType) != -1) {
                // Current section show
                jQuery(element)
                    .parents('.irantheme-sections-thumb-item-holder')
                    .fadeIn(100);
            }
        });
    }
    pageBuilderInitSectionMasonry();
}

/* ===============================================================
Add actions to buttons in preview sections
=============================================================== */
function pageBuilderAddActionsToButtonsSections() {
    // Get sections again to receive new elements
    let sections = jQuery('.irantheme-page-builder-preview-iframe')
        .contents()
        .children()
        .find(
            '.irantheme-section, .irantheme-header-main, .irantheme-navigation',
        );
    // Loop from all sections in iframe to action buttons
    jQuery(sections).each(function (index, section) {
        jQuery(section)
            .find('.irantheme-page-builder-action-section-button-item')
            .on('click', function () {
                // Get action of button
                let action = jQuery(this).attr('data-action');

                if (action == 'trash') {
                    if (confirm('آیا از حذف مطمئن هستید؟')) {
                        // Delete section fields registered
                        iranthemeDeleteSection(section);
                    }
                } else if (action == 'edit') {
                    // Show panel if is in side of screen
                    jQuery('.irantheme-page-builder-holder-panel').removeClass(
                        'active',
                    );
                    jQuery(
                        '.irantheme-page-builder-holder-preview',
                    ).removeClass('active');
                    // Check current data ajax with section edit data ajax clicked
                    if (
                        jQuery(section).attr('data-ajax') !=
                            jQuery(
                                '.irantheme-settings-visual-panel-ajax',
                            ).attr('data-ajax') ||
                        jQuery('.irantheme-settings-visual-panel-ajax').attr(
                            'data-object',
                        ).length != 0
                    ) {
                        // Create settings fields of current section
                        pageBuilderCreatePanelFieldsAjax(section);
                    }
                } else if (action == 'move-down') {
                    // Move section to after element
                    jQuery(section).insertAfter(
                        jQuery(section).next('.irantheme-section'),
                    );
                    // Save ajax preview
                    pageBuilderSaveAjaxPreview();
                } else if (action == 'move-up') {
                    // Move section to before element
                    jQuery(section).insertBefore(
                        jQuery(section).prev('.irantheme-section'),
                    );
                    // Save ajax preview
                    pageBuilderSaveAjaxPreview();
                } else if (action == 'copy') {
                    let dataType = jQuery(section).data('type');
                    let dataValue = jQuery(section).data('value');
                    let dataPage = jQuery(section).data('page');
                    let dataAjax = jQuery(section).data('ajax');
                    // Sending data to copy section ajax
                    pageBuilderCopySection(
                        dataType,
                        dataValue,
                        dataPage,
                        dataAjax,
                    );
                } else if (action == 'template') {
                    let dataType = jQuery(section).data('type');
                    let dataValue = jQuery(section).data('value');
                    let dataPage = jQuery(section).data('page');
                    let dataAjax = jQuery(section).data('ajax');
                    // Sending data to copy section ajax
                    pageBuilderTemplateSection(
                        dataType,
                        dataValue,
                        dataPage,
                        dataAjax,
                    );
                } else if (action == 'header_recover') {
                    let dataType = jQuery(section).data('type');
                    let dataValue = jQuery(section).data('value');
                    let dataPage = jQuery(section).data('page');
                    let dataAjax = jQuery(section).data('ajax');

                    // Sending data to copy section ajax
                    pageBuilderHeaderRecover(
                        dataType,
                        dataValue,
                        dataPage,
                        dataAjax,
                    );
                }
            });
    });
}

/* ===============================================================
Request ajax for copy section
=============================================================== */
function pageBuilderCopySection(dataType, dataValue, dataPage, dataAjax) {
    // Get admin url action
    let adminUrl = iranthemeWpData.adminAjax;
    // Send data to admin url with ajax
    jQuery.ajax({
        url: adminUrl,
        type: 'post',
        data: {
            action: 'copy_section',
            data_type: dataType,
            data_value: dataValue,
            data_page: dataPage,
            data_ajax: dataAjax,
        },
        dataType: 'html',
        // processData: false,
        // contentType: false,
        beforeSend: (xhr) => {
            // xhr.setRequestHeader('X-WP-Nonce', iranthemeWpData.nonce);
            // Enable preloader in preview
            jQuery(
                '.irantheme-page-builder-preview .irantheme-preloader-abs',
            ).addClass('active');
        },
        success: (response) => {
            // Reload iframe
            pageBuilderReloadIframe();
            jQuery('.irantheme-page-builder-preview-iframe').on(
                'load',
                function () {
                    // Disable preloader in panel
                    jQuery(
                        '.irantheme-page-builder-preview .irantheme-preloader-abs',
                    ).removeClass('active');
                },
            );
        },
        complete: (response) => {},
        error: (e) => {
            console.log('Error' + e.responseText);
        },
    });
}
/* ===============================================================
Request ajax for header recovery (Replace fields with settings options values)
=============================================================== */
function pageBuilderHeaderRecover(dataType, dataValue, dataPage, dataAjax) {
    // Check confirm header fields recovery
    if (
        !confirm(
            'آیا از جایگزینی مقادیر هدر "تنظیمات قالب" به جای هدر همین صفحه، اطمینان دارید؟',
        )
    ) {
        return;
    }
    // Get admin url action
    let adminUrl = iranthemeWpData.adminAjax;
    // Send data to admin url with ajax
    jQuery.ajax({
        url: adminUrl,
        type: 'post',
        data: {
            action: 'header_recovery',
            data_type: dataType,
            data_value: dataValue,
            data_page: dataPage,
            data_ajax: dataAjax,
        },
        dataType: 'html',
        // processData: false,
        // contentType: false,
        beforeSend: (xhr) => {
            // Check header data-ajax and panel fields data-ajax for remove fields
            if (
                dataAjax ==
                jQuery('.irantheme-settings-visual-panel-ajax').attr(
                    'data-ajax',
                )
            ) {
                // Close all existed settings from panel
                initClosePanelSettings();
            }
            // xhr.setRequestHeader('X-WP-Nonce', iranthemeWpData.nonce);
            // Enable preloader in preview
            jQuery(
                '.irantheme-page-builder-preview .irantheme-preloader-abs',
            ).addClass('active');
        },
        success: (response) => {
            // Reload iframe
            pageBuilderReloadIframe();
            jQuery('.irantheme-page-builder-preview-iframe').on(
                'load',
                function () {
                    // Disable preloader in panel
                    jQuery(
                        '.irantheme-page-builder-preview .irantheme-preloader-abs',
                    ).removeClass('active');
                },
            );
        },
        complete: (response) => {},
        error: (e) => {
            console.log('Error' + e.responseText);
        },
    });
}

/* ===============================================================
Request ajax for template section
=============================================================== */
function pageBuilderTemplateSection(dataType, dataValue, dataPage, dataAjax) {
    let templateName = prompt('عنوان الگو را وارد کنید : ');
    if (!templateName) {
        return;
    }
    // Get admin url action
    let adminUrl = iranthemeWpData.adminAjax;
    // Send data to admin url with ajax
    jQuery.ajax({
        url: adminUrl,
        type: 'post',
        data: {
            action: 'create_template',
            data_type: dataType,
            data_value: dataValue,
            data_page: dataPage,
            data_ajax: dataAjax,
            template_name: templateName,
        },
        dataType: 'html',
        // processData: false,
        // contentType: false,
        beforeSend: (xhr) => {
            // xhr.setRequestHeader('X-WP-Nonce', iranthemeWpData.nonce);
            // Enable preloader in preview
            jQuery(
                '.irantheme-page-builder-preview .irantheme-preloader-abs',
            ).addClass('active');
        },
        success: (response) => {},
        complete: (response) => {
            console.log('Successfully added to templates!');
            // Tip to success
            createTip('success', 'الگو با موفقیت ایجاد شد.');
            // Disable preloader in panel
            jQuery(
                '.irantheme-page-builder-preview .irantheme-preloader-abs',
            ).removeClass('active');
        },
        error: (e) => {
            console.log('Error' + e.responseText);
        },
    });
}

/* ===============================================================
Add actions to buttons in preview sections
=============================================================== */
function pageBuilderAddActionsToButtonsObjects() {
    // Get sections again to receive new elements
    let sections = jQuery('.irantheme-page-builder-preview-iframe')
        .contents()
        .children()
        .find('.irantheme-section');
    let afterBeforeMoveStatus = false;
    // Loop from all sections in iframe to action buttons
    jQuery(sections).each(function (index, section) {
        jQuery(section).on(
            'click',
            '.irantheme-page-builder-action-object-button-item',
            function () {
                afterBeforeMoveStatus = false;
                // Get action of button
                let action = jQuery(this).attr('data-action');
                // Get current object
                let object = jQuery(this).parents('.irantheme-object');
                // Get object id
                let objectId = jQuery(this)
                    .parents('.irantheme-object')
                    .attr('data-object');
                // Get json data name
                let jsonDataName = jQuery(object)
                    .parents('.irantheme-object-group')
                    .data('json');
                // Get section data type
                let dataType = jQuery(section).data('type');
                // Get section data value
                let dataValue = jQuery(section).data('value');
                // Get section data page
                let dataPage = jQuery(section).data('page');
                // Get section data ajax
                let dataAjax = jQuery(section).data('ajax');

                if (action == 'copy') {
                    // Copy object
                    pageBuilderCopyObject(
                        objectId,
                        jsonDataName,
                        dataType,
                        dataValue,
                        dataPage,
                        dataAjax,
                        section,
                    );
                } else if (action == 'trash') {
                    if (confirm('آیا از حذف عنصر مطمئن هستید؟')) {
                        // Delete object
                        pageBuilderDeleteObject(
                            objectId,
                            jsonDataName,
                            dataType,
                            dataValue,
                            dataPage,
                            dataAjax,
                            section,
                        );
                    }
                } else if (action == 'edit') {
                    // Show panel if is in side of screen
                    jQuery('.irantheme-page-builder-holder-panel').removeClass(
                        'active',
                    );
                    jQuery(
                        '.irantheme-page-builder-holder-preview',
                    ).removeClass('active');
                    // Check current data ajax with section edit data ajax clicked
                    if (
                        objectId !=
                        jQuery('.irantheme-settings-visual-panel-ajax').attr(
                            'data-object',
                        )
                    ) {
                        // Create settings fields of current section
                        pageBuilderEditObject(
                            objectId,
                            jsonDataName,
                            dataType,
                            dataValue,
                            dataPage,
                            dataAjax,
                            section,
                        );
                    }
                } else if (action == 'before') {
                    // Move object to before element
                    jQuery(object).insertBefore(jQuery(object).prev());
                    afterBeforeMoveStatus = true;
                } else if (action == 'after') {
                    // Move object to after element
                    jQuery(object).insertAfter(jQuery(object).next());
                    afterBeforeMoveStatus = true;
                }

                // Check after or before movement status
                if (afterBeforeMoveStatus) {
                    setTimeout(() => {
                        let objects = jQuery(this)
                            .parents('.irantheme-section')
                            .find('.irantheme-object');
                        jQuery(objects).each(function (index, element) {
                            objectDataArrayAfter.push(
                                jQuery(element).data('object'),
                            );
                        });
                        objectOrderAfter = objectDataArrayAfter.join('_');
                        objectDataArrayAfter = [];

                        // Check order object id
                        if (objectOrderAfter) {
                            // Send request object ordering
                            pageBuilderMoveObject(
                                objectOrderAfter,
                                jsonDataName,
                                dataType,
                                dataValue,
                                dataPage,
                                dataAjax,
                                section,
                            );
                        }
                    }, 100);
                }
            },
        );
    });

    var objectOrderBefore = '';
    var objectOrderAfter = '';
    var objectDataArrayBefore = [];
    var objectDataArrayAfter = [];
    // Move object event mousedown and generate order object id string
    jQuery('.irantheme-page-builder-preview-iframe')
        .contents()
        .find('body')
        .on(
            'mousedown',
            '.irantheme-object:not(.irantheme-page-builder-action-object-button)',
            function () {
                if (!afterBeforeMoveStatus) {
                    let objects = jQuery(this)
                        .parents('.irantheme-section')
                        .find('.irantheme-object');
                    jQuery(objects).each(function (index, element) {
                        objectDataArrayBefore.push(
                            jQuery(element).data('object'),
                        );
                    });
                    objectOrderBefore = objectDataArrayBefore.join('_');
                    objectDataArrayBefore = [];
                }
            },
        );
    // Move object event mouseup and generate order object id string
    jQuery('.irantheme-page-builder-preview-iframe')
        .contents()
        .find('body')
        .on(
            'mouseup',
            '.irantheme-object:not(.irantheme-page-builder-action-object-button)',
            function () {
                if (!afterBeforeMoveStatus) {
                    setTimeout(() => {
                        let objects = jQuery(this)
                            .parents('.irantheme-section')
                            .find('.irantheme-object');
                        jQuery(objects).each(function (index, element) {
                            objectDataArrayAfter.push(
                                jQuery(element).data('object'),
                            );
                        });
                        objectOrderAfter = objectDataArrayAfter.join('_');
                        objectDataArrayAfter = [];

                        // Check order object id
                        if (objectOrderBefore != objectOrderAfter) {
                            let section =
                                jQuery(this).parents('.irantheme-section');
                            let jsonDataName = jQuery(this)
                                .parents('.irantheme-object-group')
                                .data('json');
                            let dataType = jQuery(section).data('type');
                            let dataValue = jQuery(section).data('value');
                            let dataPage = jQuery(section).data('page');
                            let dataAjax = jQuery(section).data('ajax');

                            // Send request object ordering
                            pageBuilderMoveObject(
                                objectOrderAfter,
                                jsonDataName,
                                dataType,
                                dataValue,
                                dataPage,
                                dataAjax,
                                section,
                            );
                        }
                    }, 100);
                }
            },
        );

    // Loop from all sections in iframe to add object
    jQuery(sections).each(function (index, section) {
        jQuery(section).on(
            'click',
            '.irantheme-page-builder-add-object-button-item',
            function () {
                // Get json data name
                let jsonDataName = jQuery(this)
                    .parents('.irantheme-object-group')
                    .data('json');
                // Get section data type
                let dataType = jQuery(section).data('type');
                // Get section data value
                let dataValue = jQuery(section).data('value');
                // Get section data page
                let dataPage = jQuery(section).data('page');
                // Get section data ajax
                let dataAjax = jQuery(section).data('ajax');
                pageBuilderAddObject(
                    jsonDataName,
                    dataType,
                    dataValue,
                    dataPage,
                    dataAjax,
                    section,
                );
            },
        );
    });
}

/* ===============================================================
Request ajax for add object in section
=============================================================== */
function pageBuilderAddObject(
    jsonDataName,
    dataType,
    dataValue,
    dataPage,
    dataAjax,
) {
    // Get admin url action
    let adminUrl = iranthemeWpData.adminAjax;
    // Send data to admin url with ajax
    jQuery.ajax({
        url: adminUrl,
        type: 'post',
        data: {
            action: 'add_object',
            json_data_name: jsonDataName,
            data_type: dataType,
            data_value: dataValue,
            data_page: dataPage,
            data_ajax: dataAjax,
        },
        dataType: 'html',
        // processData: false,
        // contentType: false,
        beforeSend: (xhr) => {
            // xhr.setRequestHeader('X-WP-Nonce', iranthemeWpData.nonce);
            // Enable preloader in preview
            jQuery(
                '.irantheme-page-builder-preview .irantheme-preloader-abs',
            ).addClass('active');
            // Remove settings fields of section
            jQuery('.irantheme-settings-visual-panel-ajax').remove();
        },
        success: (response) => {
            // Reload iframe
            pageBuilderReloadIframe();
            jQuery('.irantheme-page-builder-preview-iframe').on(
                'load',
                function () {
                    // Disable preloader in panel
                    jQuery(
                        '.irantheme-page-builder-preview .irantheme-preloader-abs',
                    ).removeClass('active');
                },
            );
        },
        complete: (response) => {},
        error: (e) => {
            console.log('Error' + e.responseText);
        },
    });
}

/* ===============================================================
Request ajax for move object in section
=============================================================== */
function pageBuilderMoveObject(
    objectOrder,
    jsonDataName,
    dataType,
    dataValue,
    dataPage,
    dataAjax,
    section,
) {
    // Get admin url action
    let adminUrl = iranthemeWpData.adminAjax;
    // Send data to admin url with ajax
    jQuery.ajax({
        url: adminUrl,
        type: 'post',
        data: {
            action: 'move_object',
            object_order: objectOrder,
            json_data_name: jsonDataName,
            data_type: dataType,
            data_value: dataValue,
            data_page: dataPage,
            data_ajax: dataAjax,
        },
        dataType: 'html',
        // processData: false,
        // contentType: false,
        beforeSend: (xhr) => {
            // xhr.setRequestHeader('X-WP-Nonce', iranthemeWpData.nonce);
        },
        success: (response) => {
            console.log('Reordering object!');
        },
        complete: (response) => {},
        error: (e) => {
            console.log('Error' + e.responseText);
        },
    });
}

/*===============================================================
Create fields section ajax
=============================================================== */
function pageBuilderEditObject(
    objectId,
    jsonDataName,
    dataType,
    dataValue,
    dataPage,
    dataAjax,
    section,
) {
    let object = jQuery(section).find(
        '.irantheme-object[data-object=' + objectId + ']',
    );
    // Get admin ajax url
    var ajax_url = iranthemeWpData.adminAjax;
    // Post fetch =============================================
    jQuery.ajax({
        url: ajax_url,
        type: 'post',
        data: {
            action: 'edit_object',
            object_id: objectId,
            json_data_name: jsonDataName,
            data_type: dataType,
            data_value: dataValue,
            data_page: dataPage,
            data_ajax: dataAjax,
        },
        dataType: 'html',
        beforeSend: (response) => {
            // Enable preloader in panel
            jQuery(
                '.irantheme-page-builder-panel .irantheme-preloader-abs',
            ).addClass('active');
        },
        success: (response) => {
            // Close all existed settings from panel
            initClosePanelSettings();
            // Append generated settings to panel ajax
            jQuery('.irantheme-page-builder-panel-fields').append(response);
            // Enable action buttons
            jQuery('.irantheme-button-action').removeClass(
                'irantheme-button-disabled',
            );

            // Add active class to current section
            jQuery(object).addClass(
                'irantheme-object-page-builder-edit-active',
            );
            jQuery('.irantheme-page-builder-panel-addons-note').addClass(
                'active',
            );
            jQuery('.irantheme-page-builder-panel-addons-note').text(
                'حالت تنظیمات',
            );
        },
        complete: (response) => {
            // Display panel ajax
            jQuery('.irantheme-settings-visual-panel-ajax').fadeIn();
            // Add active class to current section
            jQuery(object).addClass('active');
            // Init settings options elements
            initSettingsOptionsPanelAjax();
            // Disable preloader in panel
            jQuery(
                '.irantheme-page-builder-panel .irantheme-preloader-abs',
            ).removeClass('active');
        },
        error: (e) => {
            console.log('Error: ' + e.responseText);
        },
    });
}

/* ===============================================================
Request ajax for copy object in section
=============================================================== */
function pageBuilderCopyObject(
    objectId,
    jsonDataName,
    dataType,
    dataValue,
    dataPage,
    dataAjax,
    section,
) {
    // Get admin url action
    let adminUrl = iranthemeWpData.adminAjax;
    // Send data to admin url with ajax
    jQuery.ajax({
        url: adminUrl,
        type: 'post',
        data: {
            action: 'copy_object',
            object_id: objectId,
            json_data_name: jsonDataName,
            data_type: dataType,
            data_value: dataValue,
            data_page: dataPage,
            data_ajax: dataAjax,
        },
        dataType: 'html',
        // processData: false,
        // contentType: false,
        beforeSend: (xhr) => {
            // xhr.setRequestHeader('X-WP-Nonce', iranthemeWpData.nonce);
            // Enable preloader in preview
            jQuery(
                '.irantheme-page-builder-preview .irantheme-preloader-abs',
            ).addClass('active');
        },
        success: (response) => {
            // Reload iframe
            pageBuilderReloadIframe();
            jQuery('.irantheme-page-builder-preview-iframe').on(
                'load',
                function () {
                    // Disable preloader in panel
                    jQuery(
                        '.irantheme-page-builder-preview .irantheme-preloader-abs',
                    ).removeClass('active');
                },
            );
        },
        complete: (response) => {},
        error: (e) => {
            console.log('Error' + e.responseText);
        },
    });
}

/* ===============================================================
Request ajax for delete object in section
=============================================================== */
function pageBuilderDeleteObject(
    objectId,
    jsonDataName,
    dataType,
    dataValue,
    dataPage,
    dataAjax,
    section,
) {
    // Get admin url action
    let adminUrl = iranthemeWpData.adminAjax;
    // Send data to admin url with ajax
    jQuery.ajax({
        url: adminUrl,
        type: 'post',
        data: {
            action: 'delete_object',
            object_id: objectId,
            json_data_name: jsonDataName,
            data_type: dataType,
            data_value: dataValue,
            data_page: dataPage,
            data_ajax: dataAjax,
        },
        dataType: 'html',
        // processData: false,
        // contentType: false,
        beforeSend: (xhr) => {
            // xhr.setRequestHeader('X-WP-Nonce', iranthemeWpData.nonce);
            // Enable preloader in preview
            jQuery(
                '.irantheme-page-builder-preview .irantheme-preloader-abs',
            ).addClass('active');
        },
        success: (response) => {
            // Reload iframe
            pageBuilderReloadIframe();
            jQuery('.irantheme-page-builder-preview-iframe').on(
                'load',
                function () {
                    // Disable preloader in panel
                    jQuery(
                        '.irantheme-page-builder-preview .irantheme-preloader-abs',
                    ).removeClass('active');
                },
            );
        },
        complete: (response) => {
            // Remove panel fields (Ajax fields data)
            if (
                jQuery('.irantheme-settings-visual-panel-ajax').attr(
                    'data-ajax',
                ) == objectId
            ) {
                jQuery('.irantheme-settings-visual-panel-ajax').remove();
            }
        },
        error: (e) => {
            console.log('Error' + e.responseText);
        },
    });
}

/* ===============================================================
Set dynamic height of iframe preview
=============================================================== */
function iframeDynamicLoaded() {
    var iFrameID = document.querySelector(
        '.irantheme-page-builder-preview-iframe',
    );
    if (iFrameID) {
        iFrameID.height = '';
        iFrameID.height =
            iFrameID.contentWindow.document.body.scrollHeight + 'px';
    }
}

/* ===============================================================
Save section options with ajax
=============================================================== */
function pageBuilderSaveAjaxPreview() {
    // Holder section objects
    var sectionObjects = [];
    // Loop from all sections on demo
    jQuery('.irantheme-page-builder-preview-iframe')
        .contents()
        .find('.irantheme-section')
        .each(function (index, section) {
            // Get ajax code
            let ajaxCode = jQuery(section).data('ajax');
            sectionObjects.push(ajaxCode);
        });
    let sectionsOrderRaw = sectionObjects.join('_');
    let pageId = getUrlParameter('post_id');
    // Get admin url action
    let adminUrl = iranthemeWpData.adminAjax;
    // Send data to admin url with ajax
    jQuery.ajax({
        url: adminUrl,
        type: 'post',
        data: {
            action: 'move_section',
            page_id: pageId,
            sections_order_raw: sectionsOrderRaw,
        },
        dataType: 'html',
        // processData: false,
        // contentType: false,
        beforeSend: (xhr) => {
            // xhr.setRequestHeader('X-WP-Nonce', iranthemeWpData.nonce);
        },
        success: (response) => {
            console.log('Page builder preview saved!');
        },
        complete: (response) => {},
        error: (e) => {
            console.log('Error' + e.responseText);
        },
    });
}

/* ===============================================================
Get parameter from url
=============================================================== */
function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined
                ? true
                : decodeURIComponent(sParameterName[1]);
        }
    }
    return false;
}

/*===============================================================
Create fields section ajax
=============================================================== */
function pageBuilderCreatePanelFieldsAjax(section) {
    // Get admin ajax url
    var ajax_url = iranthemeWpData.adminAjax;
    let dataType = jQuery(section).attr('data-type');
    let dataValue = jQuery(section).attr('data-value');
    let dataPage = jQuery(section).attr('data-page');
    let dataAjax = jQuery(section).attr('data-ajax');
    // Post fetch =============================================
    jQuery.ajax({
        url: ajax_url,
        type: 'post',
        data: {
            action: 'edit_section',
            data_type: dataType,
            data_value: dataValue,
            data_page: dataPage,
            data_ajax: dataAjax,
        },
        dataType: 'html',
        beforeSend: (response) => {
            // Enable preloader in panel
            jQuery(
                '.irantheme-page-builder-panel .irantheme-preloader-abs',
            ).addClass('active');
        },
        success: (response) => {
            console.log('Success! create options...');
            // Close all existed settings from panel
            initClosePanelSettings();
            // Append generated settings to panel ajax
            jQuery('.irantheme-page-builder-panel-fields').append(response);
            // Enable action buttons
            jQuery('.irantheme-button-action').removeClass(
                'irantheme-button-disabled',
            );
            // Add active class to current section
            jQuery(section).addClass(
                'irantheme-section-page-builder-edit-active',
            );
            jQuery('.irantheme-page-builder-panel-addons-note').addClass(
                'active',
            );
            jQuery('.irantheme-page-builder-panel-addons-note').text(
                'حالت تنظیمات',
            );
        },
        complete: (response) => {
            // Display panel ajax
            jQuery('.irantheme-settings-visual-panel-ajax').fadeIn();
            // Add active class to current section
            jQuery(section).addClass('active');
            // Init settings options elements
            initSettingsOptionsPanelAjax();
            // Disable preloader in panel
            jQuery(
                '.irantheme-page-builder-panel .irantheme-preloader-abs',
            ).removeClass('active');
        },
        error: (e) => {
            console.log('Error: ' + e.responseText);
        },
    });
}

/* ===============================================================
Init close panel ajax
=============================================================== */
function initClosePanelAjax() {
    jQuery('.irantheme-visual-panel-ajax-close').on('click', function () {
        jQuery('.irantheme-settings-visual-panel-ajax').fadeOut();
        // Disable action buttons
        jQuery('.irantheme-button-action').addClass(
            'irantheme-button-disabled',
        );
        // Loop from all visual settings fields
        jQuery('.irantheme-page-builder-preview-iframe')
            .contents()
            .children()
            .find(
                '.irantheme-section, .irantheme-header-main, .irantheme-navigation',
            )
            .removeClass('irantheme-section-page-builder-edit-active');
        // Loop from all objects
        jQuery('.irantheme-page-builder-preview-iframe')
            .contents()
            .children()
            .find('.irantheme-object')
            .removeClass('irantheme-object-page-builder-edit-active');
        setTimeout(() => {
            jQuery('.irantheme-settings-visual-panel-ajax').remove();
            // Remove mce panel
            jQuery('.mce-panel').remove();
        }, 500);
        jQuery('.irantheme-page-builder-panel-addons-note').removeClass(
            'active',
        );
        jQuery('.irantheme-page-builder-panel-addons-note').text(
            'مقادیری موجود نمی باشد',
        );
    });
}

/*===============================================================
Init panel ajax save button
=============================================================== */
function initSavePanelAjax() {
    jQuery('#irantheme-form-visual-panel-ajax').on('submit', function (e) {
        // Prevent to submit
        e.preventDefault();
        // Get save button
        let saveBtn = jQuery('.irantheme-visual-panel-ajax-save');
        // Check save btn disabled class
        if (jQuery(saveBtn).hasClass('irantheme-button-disabled')) {
            return;
        }
        // Get spinner icon alt
        let spinnerAlt = saveBtn.find('#irantheme-spinner-alt');
        // Get checkmark icon
        let checkMark = saveBtn.find('.irantheme-success-checkmark');
        // Get cog icon
        let cogBtn = saveBtn.find('.icon.icon-settings');
        // Check tinymce is loaded in page or no
        if (typeof tinymce != 'undefined') {
            // Save tinymce before send (This is require for prevent twice saving!)
            tinymce.triggerSave();
        }
        // Create form data for panel
        let dataFormPanel = jQuery(this).serialize();
        // Get admin url action
        let adminUrl = iranthemeWpData.adminAjax;
        // Get action type for sections or objects or etc
        let actionAjax = jQuery('.irantheme-settings-visual-panel-ajax').data(
            'action',
        );
        // Get data ajax or object id
        let dataAjax = jQuery('.irantheme-settings-visual-panel-ajax').data(
            'ajax',
        );
        // Get data ajax or object id
        let dataPage = jQuery('.irantheme-settings-visual-panel-ajax').data(
            'page',
        );
        // Get data ajax or object id
        let dataObjectId = jQuery('.irantheme-settings-visual-panel-ajax').data(
            'object',
        );
        let jsonObjectName = '';
        // Check object fields save or update options
        if (actionAjax == 'save_object') {
            // Get field name(Id) of json data
            jsonObjectName = jQuery(
                '.irantheme-settings-visual-panel-ajax',
            ).data('json-object');
        }

        // Send data to admin url with ajax
        jQuery.ajax({
            url: adminUrl,
            type: 'post',
            data: {
                action: 'update_options',
                action_type: actionAjax,
                data_page: dataPage,
                data_form: dataFormPanel,
                json_object_name: jsonObjectName,
                data_ajax: dataAjax,
                object_id: dataObjectId,
            },
            dataType: 'html',
            // processData: false,
            // contentType: false,
            beforeSend: (xhr) => {
                // xhr.setRequestHeader('X-WP-Nonce', iranthemeWpData.nonce);
                cogBtn.hide();
                checkMark.hide();
                spinnerAlt.show();
                jQuery(saveBtn).addClass('irantheme-button-disabled');
                // Enable preloader in preview
                jQuery(
                    '.irantheme-page-builder-preview .irantheme-preloader-abs',
                ).addClass('active');
            },
            success: (response) => {
                setTimeout(() => {
                    // Notification mode
                    jQuery(
                        '.irantheme-page-builder-panel-addons-note',
                    ).addClass('active');
                    jQuery(
                        '.irantheme-page-builder-panel-addons-note',
                    ).addClass('saved');
                    jQuery('.irantheme-page-builder-panel-addons-note').text(
                        'تنظیمات ذخیره شد!',
                    );
                    cogBtn.hide();
                    spinnerAlt.hide();
                    checkMark.show();
                    // Reload iframe
                    pageBuilderReloadIframe();
                    jQuery('.irantheme-page-builder-preview-iframe').on(
                        'load',
                        function () {
                            // Disable preloader in panel
                            jQuery(
                                '.irantheme-page-builder-preview .irantheme-preloader-abs',
                            ).removeClass('active');
                        },
                    );
                }, 250);
            },
            complete: (response) => {
                setTimeout(() => {
                    jQuery(
                        '.irantheme-page-builder-panel-addons-note',
                    ).removeClass('saved');
                    jQuery('.irantheme-page-builder-panel-addons-note').text(
                        'حالت تنظیمات',
                    );
                    checkMark.hide();
                    cogBtn.show();
                    jQuery(saveBtn).removeClass('irantheme-button-disabled');
                }, 1500);
            },
            error: (e) => {
                console.log('Error' + e.responseText);
                jQuery('.irantheme-page-builder-panel-addons-note').text(
                    'عدم ذخیره سازی!',
                );
            },
        });
    });
}

/* ===============================================================
Init restore to default panel section ajax
=============================================================== */
function initRestoreToDefaultVisual() {
    jQuery(
        '.irantheme-page-builder-panel-addons .irantheme-reset-section:not(.irantheme-button-disabled)',
    ).on('click', function () {
        // Get action type for sections or objects or etc
        let actionAjax = jQuery('.irantheme-settings-visual-panel-ajax').data(
            'action',
        );
        // Get data ajax or object id
        let dataAjax = jQuery('.irantheme-settings-visual-panel-ajax').data(
            'ajax',
        );
        // Get data value or object id
        let dataValue = jQuery('.irantheme-settings-visual-panel-ajax').data(
            'value',
        );
        // Get data ajax or object id
        let dataPage = jQuery('.irantheme-settings-visual-panel-ajax').data(
            'page',
        );
        // Get data ajax or object id
        let dataObjectId = jQuery('.irantheme-settings-visual-panel-ajax').data(
            'object',
        );
        let jsonObjectName = '';
        // Check object fields save or update options
        if (actionAjax == 'save_object') {
            // Get field name(Id) of json data
            jsonObjectName = jQuery(
                '.irantheme-settings-visual-panel-ajax',
            ).data('json-object');
        }
        // Get admin ajax url
        var ajax_url = iranthemeWpData.adminAjax;
        // Post fetch =============================================
        jQuery.ajax({
            url: ajax_url,
            type: 'post',
            data: {
                action: 'restore_default',
                action_type: actionAjax,
                data_value: dataValue,
                data_page: dataPage,
                data_ajax: dataAjax,
                json_object_name: jsonObjectName,
                object_id: dataObjectId,
            },
            dataType: 'html',
            beforeSend: (response) => {
                // Enable preloader in panel
                jQuery(
                    '.irantheme-page-builder-panel .irantheme-preloader-abs',
                ).addClass('active');
            },
            success: (response) => {
                console.log('Success! create options...');
                // Close all existed settings from panel
                initClosePanelSettings();
                // Append generated settings to panel ajax
                jQuery('.irantheme-page-builder-panel-fields').append(response);
                // Enable action buttons
                jQuery('.irantheme-button-action').removeClass(
                    'irantheme-button-disabled',
                );
                // Show note save
                jQuery('.irantheme-page-builder-panel-addons-note').text(
                    'مقادیر بازنشانی را ذخیره کنید',
                );
                jQuery('.irantheme-page-builder-panel-addons-note').addClass(
                    'active',
                );
            },
            complete: (response) => {
                // Display panel ajax
                jQuery('.irantheme-settings-visual-panel-ajax').fadeIn();
                // Init settings options elements
                initSettingsOptionsPanelAjax();
                // Disable preloader in panel
                jQuery(
                    '.irantheme-page-builder-panel .irantheme-preloader-abs',
                ).removeClass('active');
            },
            error: (e) => {
                console.log('Error: ' + e.responseText);
            },
        });
    });
}

/* ===============================================================
Generating ajax code for sections (Access to database)
=============================================================== */
function getAjaxCode(num) {
    // All generation code
    let characters = '1234567890abcdefghijklmnopqrsvutwxyz';
    // Code holder
    let code = '';
    // Loop from chars
    for (let i = 0; i < num; i++) {
        // Create random number
        let num = Math.floor(Math.random() * (characters.length - 0) + 0);
        // Concatenation char random
        code += characters[num];
    }
    return code;
}

/* ===============================================================
Init close all existed settings options in panel
=============================================================== */
function initClosePanelSettings() {
    jQuery('.irantheme-settings-visual-panel-ajax').remove();
    // Loop from all visual settings fields
    jQuery('.irantheme-page-builder-preview-iframe')
        .contents()
        .children()
        .find(
            '.irantheme-section, .irantheme-header-main, .irantheme-navigation',
        )
        .removeClass('irantheme-section-page-builder-edit-active');
    // Loop from all objects
    jQuery('.irantheme-page-builder-preview-iframe')
        .contents()
        .children()
        .find('.irantheme-object')
        .removeClass('irantheme-object-page-builder-edit-active');
    // Hide builder note
    jQuery('.irantheme-page-builder-panel-addons-note').removeClass('active');
}

/* ===============================================================
Check ajax codes from sections
=============================================================== */
function iranthemeCheckAjaxCode(currentAjaxCode) {
    // Loop from all visual settings fields
    jQuery('.irantheme-page-builder-preview-iframe')
        .contents()
        .children()
        .find('.irantheme-section')
        .each(function (index, section) {
            if (jQuery(section).data('ajax') == currentAjaxCode) {
                return true;
            }
        });
    return false;
}

/*===============================================================
Section thumb image magnification
=============================================================== */
function iranthemeMagnificationSearchSection() {
    jQuery('.irantheme-settings-visual-sections').on(
        'click',
        '.irantheme-sections-thumb-item .irantheme-sections-thumb-item-image-zoom-in',
        function (e) {
            e.preventDefault();
            // Get image thumb magnified holder
            let thumbMagnifiedHolder = jQuery(
                '.irantheme-section-thumb-magnification',
            );
            let thumbSelected = jQuery(this).siblings(
                '.irantheme-sections-thumb-item-image-img',
            );
            thumbMagnifiedHolder
                .find('img')
                .attr('src', thumbSelected.attr('src'));
            thumbMagnifiedHolder.fadeIn('fast');
        },
    );

    jQuery('.irantheme-settings-visual-sections').on(
        'click',
        '.irantheme-section-thumb-magnification-close-btn',
        function (e) {
            e.preventDefault();
            // Get image thumb magnified holder
            let thumbMagnifiedHolder = jQuery(
                '.irantheme-section-thumb-magnification',
            );
            thumbMagnifiedHolder.find('img').attr('src', '');
            thumbMagnifiedHolder.fadeOut('fast');
        },
    );
}

/*===============================================================
Init panel ajax save button
=============================================================== */
function iranthemeDeleteSection(section) {
    // Get data type from current section
    let dataType = jQuery(section).data('type');
    // Get data value from current section
    let dataValue = jQuery(section).data('value');
    // Get data page from current section
    let dataPage = jQuery(section).data('page');
    // Get data ajax from current section
    let dataAjax = jQuery(section).data('ajax');
    // Get admin url action
    let adminUrl = iranthemeWpData.adminAjax;
    // Send data to admin url with ajax
    jQuery.ajax({
        url: adminUrl,
        type: 'post',
        data: {
            action: 'delete_section',
            data_type: dataType,
            data_value: dataValue,
            data_page: dataPage,
            data_ajax: dataAjax,
        },
        dataType: 'html',
        // processData: false,
        // contentType: false,
        beforeSend: (xhr) => {
            // Enable preloader in preview
            jQuery(
                '.irantheme-page-builder-preview .irantheme-preloader-abs',
            ).addClass('active');
        },
        success: (response) => {
            console.log('Success! section deleted...');
            // Disable preloader in panel
            jQuery(
                '.irantheme-page-builder-preview .irantheme-preloader-abs',
            ).removeClass('active');
            // Remove section
            jQuery(section).fadeOut(250, 'linear', function () {
                jQuery(section).remove();
                let panelAjax = jQuery(
                    '.irantheme-settings-visual-panel-ajax',
                ).attr('data-ajax');
                // Remove panel fields (Ajax fields data)
                if (panelAjax == jQuery(section).attr('data-ajax')) {
                    jQuery('.irantheme-settings-visual-panel-ajax').remove();
                } else if (
                    panelAjax &&
                    jQuery(section).find('.irantheme-object').length
                ) {
                    // Remove panel fields if panel ajax connected to current section object
                    if (
                        jQuery(section).find(
                            '.irantheme-object[data-object=' + panelAjax + ']',
                        ).length
                    ) {
                        jQuery(
                            '.irantheme-settings-visual-panel-ajax',
                        ).remove();
                    }
                }
            });
        },
        complete: (response) => {},
        error: (e) => {
            console.log('Error' + e.responseText);
        },
    });
}

/*===============================================================
Current page config settings
=============================================================== */
function iranthemeEditPageConfig() {
    jQuery('.irantheme-visual-panel-ajax-settings').on('click', function () {
        // Get admin ajax url
        var ajax_url = iranthemeWpData.adminAjax;
        let pageId = getUrlParameter('post_id');
        let pageType = jQuery(this).attr('data-type');

        // Check current fields is relative to page config settings or no
        if (
            jQuery('.irantheme-settings-visual-panel-ajax').data('action') ==
            'page_config_fields'
        ) {
            return;
        }

        // Post fetch =============================================
        jQuery.ajax({
            url: ajax_url,
            type: 'post',
            data: {
                action: 'create_options_page',
                page_id: pageId,
                page_type: pageType,
            },
            dataType: 'html',
            beforeSend: (response) => {
                // Enable preloader in panel
                jQuery(
                    '.irantheme-page-builder-panel .irantheme-preloader-abs',
                ).addClass('active');
            },
            success: (response) => {
                console.log('Success! create options...');
                // Remove settings fields of section
                jQuery('.irantheme-settings-visual-panel-ajax').remove();
                // Append generated settings to panel ajax
                jQuery('.irantheme-page-builder-panel-fields').append(response);
                // Enable buttons action
                jQuery('.irantheme-button-action').removeClass(
                    'irantheme-button-disabled',
                );
                // Notification mode
                jQuery('.irantheme-page-builder-panel-addons-note').addClass(
                    'active',
                );
                jQuery('.irantheme-page-builder-panel-addons-note').text(
                    'تنظیمات صفحه',
                );
            },
            complete: (response) => {
                // Display panel ajax
                jQuery('.irantheme-settings-visual-panel-ajax').fadeIn();
                // Init settings options elements
                initSettingsOptionsPanelAjax();
                // Disable preloader in panel
                jQuery(
                    '.irantheme-page-builder-panel .irantheme-preloader-abs',
                ).removeClass('active');
            },
            error: (e) => {
                console.log('Error: ' + e.responseText);
            },
        });
    });
}

/*===============================================================
Init settings options
=============================================================== */
function initSettingsOptionsPanelAjax() {
    // Init toggle holder options
    toggleHolderOptions();
    // Init active first toggle holder options
    activeFirstToggleHolderOptions();
    // Init settings options
    initSettingsOptions();
}
