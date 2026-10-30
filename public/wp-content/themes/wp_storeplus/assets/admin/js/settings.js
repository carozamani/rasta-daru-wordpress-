jQuery(function (jQuery) {
    var iFrameDetection = window === window.parent ? false : true;
    if (iFrameDetection) {
        jQuery('#wpadminbar').remove();
    }
    /* ===============================================================
    Init system status
    =============================================================== */
    initSystemStatus();

    /* ===============================================================
    Init plugin installations
    =============================================================== */
    initPluginsActions();

    /* ===============================================================
    Init demo installations
    =============================================================== */
    initDemoActions();

    /* ===============================================================
    Init cleaner installations
    =============================================================== */
    initCleanerActions();

    /* ===============================================================
    Init color picker
    =============================================================== */
    initColorPicker();

    /* ===============================================================
    Init upload image media wp admin
    =============================================================== */
    initMediaUploader();

    /* ===============================================================
    Init  feature thumb holder on click (magnification)
    =============================================================== */
    magnificationThumbClickable();

    /*===============================================================
    Delete Gallery media mono item
    =============================================================== */
    mediaGalleryMonoDelete();

    /* ===============================================================
    Extra settings
    =============================================================== */
    extraSettings();

    /* ===============================================================
    Type settings panel
    =============================================================== */
    // Get type settings data
    let typeSettings = jQuery('.irantheme-settings[data-settings]').attr(
        'data-settings',
    );
    // Check type settings
    if (typeSettings == 'options' || typeSettings == 'customFields') {
        /* ===============================================================
        Init navigation & menubar section
        =============================================================== */
        initNavigationMenubarSection();

        /* ===============================================================
        Init inputs range
        =============================================================== */
        initRangeInputs();

        /* ===============================================================
        Init multi checkbox buttons
        =============================================================== */
        initMultiCheckboxButtons();

        /* ===============================================================
        Init checkbox buttons
        =============================================================== */
        initCheckboxButtons();

        /* ===============================================================
        Init save settings options
        =============================================================== */
        initSaveSettingsOptions();

        /* ===============================================================
        Init restore to default (Section)
        =============================================================== */
        initRestoreToDefaultOptions();

        /* ===============================================================
        Init search settings fields
        =============================================================== */
        initSearchSettingsFields();

        /* ===============================================================
        Init select box inputs
        =============================================================== */
        initSelectBoxInputs();

        /* ===============================================================
        Init multi select box inputs
        =============================================================== */
        initMultiSelectBox();

        /* ===============================================================
        Init checkbox dependencies
        =============================================================== */
        initCheckboxDependencies();

        /* ===============================================================
        Init radio buttons
        =============================================================== */
        initRadioButtons();

        /* ===============================================================
        Init checkbox dependencies
        =============================================================== */
        initNewsletterAction();

        /* ===============================================================
        Init test sms sender
        =============================================================== */
        initSmsTestAction();

        /* ===============================================================
        Init Attributes Summary MultiSelect
        =============================================================== */
        initAttributesSummaryMultiSelect();

        /* ===============================================================
        Init display status of custom tab for product downloadable
        =============================================================== */
        downloadableCheckboxInit();
    }
});

/* ===============================================================
Init display status of custom tab for product downloadable
=============================================================== */
function downloadableCheckboxInit() {
    // Whenever the "Downloaded" checkbox status changes
    jQuery('input#_downloadable').on('change', function () {
        if (!jQuery(this).is(':checked')) {
            var myTabPanel = jQuery('#irantheme_download_product_data');

            // Check if our content panel was showing?
            if (myTabPanel.is(':visible')) {
                // If yes, then our tab was active.
                // Automatically click on the "General" tab to prevent bugs.
                jQuery('.general_tab a').trigger('click');
            }
        }
    });
}

/* ===============================================================
Init system status
=============================================================== */
function initSystemStatus() {
    // Get status submit button
    let button = jQuery('.irantheme-settings-status-verification-button');
    let spinner = jQuery(button).find('.irantheme-animate-rotate');
    let check = jQuery(button).find('.check');

    // Submit event
    jQuery('#irantheme-settings-status-verification-form').on(
        'submit',
        function (e) {
            e.preventDefault();
            if (!jQuery(this).hasClass('disabled')) {
                // Get status input license
                let input = jQuery(
                    '.irantheme-settings-status-verification-input',
                ).val();
                // Get admin url ajax from demo
                var ajax_url = iranthemeWpData.adminAjax;
                // Send license key
                jQuery.ajax({
                    url: ajax_url,
                    type: 'post',
                    data: {
                        action: 'irantheme_verify_license',
                        license_key: input,
                    },
                    dataType: 'html',
                    beforeSend: (xhr) => {
                        // Show spinner
                        jQuery(spinner).show();
                    },
                    success: (response) => {
                        console.log(response);
                        // Convert json response to object
                        responseObjectFields = JSON.parse(response);
                        setTimeout(() => {
                            // Display message
                            jQuery(button)
                                .parent()
                                .find(
                                    '.irantheme-settings-status-verification-success',
                                )
                                .addClass(
                                    `irantheme-notification-${responseObjectFields.type} active`,
                                )
                                .find('.irantheme-notification-message')
                                .text(responseObjectFields.message);
                            // Check response status type
                            if (responseObjectFields.type == 'success') {
                                // Add success icon
                                jQuery(button)
                                    .parent()
                                    .find(
                                        '.irantheme-settings-status-verification-success',
                                    )
                                    .find('.irantheme-notification-icon')
                                    .attr(
                                        'class',
                                        'irantheme-notification-icon fa-light fa-badge-check',
                                    );
                            } else if (responseObjectFields.type == 'error') {
                                // Add error icon
                                jQuery(button)
                                    .parent()
                                    .find(
                                        '.irantheme-settings-status-verification-success',
                                    )
                                    .find('.irantheme-notification-icon')
                                    .attr(
                                        'class',
                                        'irantheme-notification-icon fa-light fa-seal-exclamation',
                                    );
                            } else if (responseObjectFields.type == 'info') {
                                // Add info icon
                                jQuery(button)
                                    .parent()
                                    .find(
                                        '.irantheme-settings-status-verification-success',
                                    )
                                    .find('.irantheme-notification-icon')
                                    .attr(
                                        'class',
                                        'irantheme-notification-icon fa-light fa-badge-percent',
                                    );
                            } else if (responseObjectFields.type == 'warning') {
                                // Add warning icon
                                jQuery(button)
                                    .parent()
                                    .find(
                                        '.irantheme-settings-status-verification-success',
                                    )
                                    .find('.irantheme-notification-icon')
                                    .attr(
                                        'class',
                                        'irantheme-notification-icon fa-light fa-seal-question',
                                    );
                            }
                            jQuery(spinner).hide();
                            jQuery(check).fadeIn();
                        }, 2000);
                        setTimeout(() => {
                            // Return to default button value
                            jQuery(check).hide();
                        }, 4000);
                        if (responseObjectFields.type == 'success') {
                            setTimeout(() => {
                                // Reload page
                                location.reload();
                            }, 6000);
                        }
                    },
                    complete: (response) => {},
                    error: (e) => {
                        console.log('Error: ' + e.responseText);
                    },
                });
            }
        },
    );

    // Checkbox event
    jQuery('.irantheme-settings-status-verification-confirm-check').on(
        'change',
        function () {
            if (jQuery(this).is(':checked')) {
                jQuery(button).removeClass('disabled');
            } else {
                jQuery(button).addClass('disabled');
            }
        },
    );
}

/* ===============================================================
Init plugin installations
=============================================================== */
function initPluginsActions() {
    // Get button install action
    let buttonInstall = jQuery(
        '.irantheme-settings-plugins-box-triggers-button-install',
    );
    // Get button delete action
    let buttonDelete = jQuery(
        '.irantheme-settings-plugins-box-triggers-button-delete',
    );
    // Get button activate action
    let buttonActivate = jQuery(
        '.irantheme-settings-plugins-box-triggers-button-activate',
    );
    // Get button inactivate action
    let buttonInactivate = jQuery(
        '.irantheme-settings-plugins-box-triggers-button-inactivate',
    );

    // Install action
    jQuery(buttonInstall).on('click', function (e) {
        e.preventDefault();
        pluginActionAjax(jQuery(this), 'plugin_installations');
    });

    // Delete action
    jQuery(buttonDelete).on('click', function (e) {
        e.preventDefault();
        pluginActionAjax(jQuery(this), 'plugin_delete');
    });

    // Activate action
    jQuery(buttonActivate).on('click', function (e) {
        e.preventDefault();
        pluginActionAjax(jQuery(this), 'plugin_activate');
    });

    // Inactivate action
    jQuery(buttonInactivate).on('click', function (e) {
        e.preventDefault();
        pluginActionAjax(jQuery(this), 'plugin_deactivate');
    });
}

/* ===============================================================
Init demo
=============================================================== */
function initDemoActions() {
    let buttonTriggerExport = jQuery('.irantheme-settings-demo-export');
    let buttonTriggerImport = jQuery(
        '.irantheme-settings-demo-items-item-content-triggers-trigger-install',
    );
    let buttonTriggerSidebarDefault = jQuery(
        '.irantheme-settings-demo-sidebar-default',
    );
    let buttonTriggerPostDefault = jQuery(
        '.irantheme-settings-demo-post-default',
    );

    jQuery(buttonTriggerPostDefault).on('click', function (e) {
        e.preventDefault();

        // Get admin url ajax from demo
        var ajax_url = iranthemeWpData.adminAjax;
        // Set post default
        jQuery.ajax({
            url: ajax_url,
            type: 'post',
            data: {
                action: 'post_default',
            },
            dataType: 'html',
            beforeSend: (xhr) => {},
            success: (response) => {
                console.log(response);

                console.log('Data default of post appended to post type.');
            },
            complete: (response) => {},
            error: (e) => {
                console.log('Error: ' + e.responseText);
            },
        });
    });

    jQuery(buttonTriggerSidebarDefault).on('click', function (e) {
        e.preventDefault();

        // Get admin url ajax from demo
        var ajax_url = iranthemeWpData.adminAjax;
        // Set sidebar default
        jQuery.ajax({
            url: ajax_url,
            type: 'post',
            data: {
                action: 'sidebar_default',
            },
            dataType: 'html',
            beforeSend: (xhr) => {},
            success: (response) => {
                console.log(response);

                console.log(
                    'Data default of sidebar appended to irantheme sidebar.',
                );
            },
            complete: (response) => {},
            error: (e) => {
                console.log('Error: ' + e.responseText);
            },
        });
    });

    jQuery(buttonTriggerExport).on('click', function (e) {
        e.preventDefault();

        // Get admin url ajax from demo
        var ajax_url = iranthemeWpData.adminAjax;
        // Send license key
        jQuery.ajax({
            url: ajax_url,
            type: 'post',
            data: {
                action: 'demo_export',
            },
            dataType: 'html',
            beforeSend: (xhr) => {},
            success: (response) => {
                window.open(response, '_blank').focus();
            },
            complete: (response) => {},
            error: (e) => {
                console.log('Error: ' + e.responseText);
            },
        });
    });

    jQuery(buttonTriggerImport).on('click', function (e) {
        e.preventDefault();

        // Get current button (This)
        let currentButton = jQuery(this);

        // Get permission url text
        let demoPermissionText = jQuery(this).data('permission');

        // Check current button to prevent to action
        if (
            jQuery(currentButton).hasClass('active') ||
            jQuery(currentButton).hasClass('inactive')
        ) {
            return;
        }

        // Confirm installing
        if (!confirm('آیا از نصب دمو اطمینان دارید؟')) {
            return;
        }

        const userInput = prompt(
            'جهت اطمینان از هوشیاری شما، لطفا عبارت ' +
                '"' +
                demoPermissionText +
                '"' +
                'را بنویسید',
        );

        // Prompt
        if (userInput !== demoPermissionText) {
            alert('عبارت نادرست است.');
            return;
        }

        let dataXml = jQuery(this).data('xml');
        // Get admin url ajax from demo
        var ajax_url = iranthemeWpData.adminAjax;
        // Send license key
        jQuery.ajax({
            url: ajax_url,
            type: 'post',
            data: {
                action: 'demo_import',
                data_xml: dataXml,
            },
            dataType: 'html',
            beforeSend: (xhr) => {
                // Hide success notification
                jQuery(currentButton)
                    .parents('.irantheme-settings-demo-items-item')
                    .find('.irantheme-settings-demo-items-item-success')
                    .hide();
                // Add inactive class to other installers
                jQuery(buttonTriggerImport).addClass('inactive');
                // Preparing front
                jQuery(currentButton).addClass('active');
                // Hide installer icon
                jQuery(currentButton).find('.irantheme-normal-icon').hide();
                // Display spinner
                jQuery(currentButton).find('.irantheme-animate-rotate').show();
                // Preparing installer text
                jQuery(currentButton).find('cite').text('در حال نصب');
                // Display notification
                jQuery(currentButton)
                    .parents('.irantheme-settings-demo-items-item')
                    .find('.irantheme-settings-demo-items-item-pending')
                    .fadeIn();
            },
            success: (response) => {
                console.log(response);
            },
            complete: (response) => {
                // Display notification
                jQuery(currentButton)
                    .parents('.irantheme-settings-demo-items-item')
                    .find('.irantheme-settings-demo-items-item-pending')
                    .hide();
                // Display notification
                jQuery(currentButton)
                    .parents('.irantheme-settings-demo-items-item')
                    .find('.irantheme-settings-demo-items-item-success')
                    .fadeIn();
                // Return icon installer to normal status
                jQuery(currentButton).find('.irantheme-normal-icon').show();
                // Hide spinner
                jQuery(currentButton).find('.irantheme-animate-rotate').hide();
                // Add success class to installer
                jQuery(currentButton).addClass('success');
                // Return installer text
                jQuery(currentButton).find('cite').text('دمو نصب شد!');

                setTimeout(() => {
                    // Remove success class to installer
                    jQuery(currentButton).removeClass('success');
                    // Remove active installer
                    jQuery(currentButton).removeClass('active');
                    // Return installer text
                    jQuery(currentButton).find('cite').text('نصب دمو');
                    // Remove inactive class from all installers
                    jQuery(buttonTriggerImport).removeClass('inactive');
                }, 2000);
            },
            error: (e) => {
                console.log('Error: ' + e.responseText);
            },
        });
    });

    // Categories
    let categories = jQuery(
        '.irantheme-settings-demo-categories .irantheme-settings-demo-categories-list-item-link',
    );
    jQuery(categories).on('click', function () {
        // Check active current category
        if (jQuery(this).hasClass('active')) {
            return;
        }
        jQuery(categories).removeClass('active');
        jQuery(this).addClass('active');
        let dataCate = jQuery(this).data('cate');
        // Check all category trigger
        if (dataCate == 'all') {
            jQuery(
                '.irantheme-settings-demo-items .irantheme-settings-demo-items-item-holder',
            ).fadeIn();
            return;
        }
        // Hide all demo items
        jQuery(
            '.irantheme-settings-demo-items .irantheme-settings-demo-items-item-holder',
        ).hide();
        // Loop from all demo items
        jQuery(
            '.irantheme-settings-demo-items .irantheme-settings-demo-items-item-holder',
        ).each(function (index, element) {
            // Get current cate demo item
            let demoDataCateArray = jQuery(element).data('cate').split(',');
            // Check current cate demo item with data category selected
            if (jQuery.inArray(dataCate, demoDataCateArray) != -1) {
                jQuery(element).fadeIn();
            }
        });
    });

    // Cheat of export! (Display export for developers)
    jQuery('.irantheme-settings-header-brand-logo').on(
        'dblclick',
        function (e) {
            e.preventDefault();
            if (e.ctrlKey) {
                jQuery('.irantheme-settings-demo-export-holder').toggle(250);
            }
        },
    );
}

/* ===============================================================
Init cleaner
=============================================================== */
function initCleanerActions() {
    let buttonTriggerOptimize = jQuery('.irantheme-settings-cleaner-optimize');
    let buttonTriggerClean = jQuery('.irantheme-settings-cleaner-clean');
    let cleanPermissionText = jQuery(buttonTriggerClean).data('permission');

    jQuery(buttonTriggerOptimize).on('click', function (e) {
        e.preventDefault();

        // Prevent to running ajax if has active class
        if (
            jQuery(buttonTriggerOptimize).hasClass('active') ||
            jQuery(buttonTriggerOptimize).hasClass('inactive')
        ) {
            return;
        }

        // Get types of targets
        let targets = jQuery(
            '.irantheme-settings-cleaner-box-list-report.optimize .irantheme-settings-cleaner-box-list-report-ul-li',
        );
        jQuery(buttonTriggerClean).addClass('inactive');
        jQuery(targets).removeClass('active');
        jQuery(targets)
            .find('i')
            .removeClass('fa-badge-check')
            .addClass('fa-badge');
        jQuery(buttonTriggerOptimize).addClass('active');
        // Show spinner
        jQuery(buttonTriggerOptimize)
            .find('.irantheme-animate-rotate')
            .css('display', 'flex');

        // Start sequential processing
        processTargetsSequentially(
            targets,
            0,
            'optimize_action',
            buttonTriggerOptimize,
            buttonTriggerClean,
        );
    });

    jQuery(buttonTriggerClean).on('click', function (e) {
        e.preventDefault();

        // Confirm cleaning
        if (!confirm('آیا از پاک سازی مطمئن هستید؟')) {
            return;
        }

        const userInput = prompt(
            'جهت اطمینان از هوشیاری شما، لطفا عبارت ' +
                '"' +
                cleanPermissionText +
                '"' +
                'را بنویسید',
        );

        // Prompt
        if (userInput !== cleanPermissionText) {
            alert('عبارت نادرست است.');
            return;
        }

        if (
            jQuery(buttonTriggerClean).hasClass('active') ||
            jQuery(buttonTriggerClean).hasClass('inactive')
        ) {
            // Prevent to running ajax if has active class
            return;
        }

        // Get types of targets
        let targets = jQuery(
            '.irantheme-settings-cleaner-box-list-report.clean .irantheme-settings-cleaner-box-list-report-ul-li',
        );
        jQuery(buttonTriggerOptimize).addClass('inactive');
        jQuery(targets).removeClass('active');
        jQuery(targets)
            .find('i')
            .removeClass('fa-badge-check')
            .addClass('fa-badge');
        jQuery(buttonTriggerClean).addClass('active');
        // Show spinner
        jQuery(buttonTriggerClean)
            .find('.irantheme-animate-rotate')
            .css('display', 'flex');

        // Start sequential processing
        processTargetsSequentially(
            targets,
            0,
            'clean_action',
            buttonTriggerClean,
            buttonTriggerOptimize,
        );
    });

    // Function to process targets sequentially
    function processTargetsSequentially(
        targets,
        index,
        action,
        currentButton,
        otherButton,
    ) {
        // If all targets are processed
        if (index >= targets.length) {
            // All AJAX calls completed
            setTimeout(() => {
                jQuery(currentButton)
                    .find('.irantheme-animate-rotate')
                    .css('display', 'none');
                jQuery(currentButton)
                    .find('cite')
                    .text(
                        action === 'optimize_action'
                            ? 'با موفقیت بهینه سازی شد.'
                            : 'با موفقیت پاک سازی شد.',
                    );
                jQuery(currentButton).find('.fa-light').hide();
            }, 1000);

            setTimeout(
                () => {
                    jQuery(currentButton).removeClass('active');
                    jQuery(currentButton)
                        .find('cite')
                        .text(
                            action === 'optimize_action'
                                ? 'شروع بهینه سازی'
                                : 'شروع پاک سازی',
                        );
                    jQuery(currentButton).find('.fa-light').show();
                    jQuery(otherButton).removeClass('inactive');
                },
                action === 'optimize_action' ? 3000 : 5000,
            );

            return;
        }

        let element = targets.eq(index);
        let currentTarget = element.data('target');
        var ajax_url = iranthemeWpData.adminAjax;

        // Send AJAX request
        jQuery.ajax({
            url: ajax_url,
            type: 'post',
            data: {
                action: action,
                current_target: currentTarget,
            },
            dataType: 'html',
            beforeSend: (xhr) => {},
            success: (response) => {
                // Process successful response if needed
            },
            complete: (response) => {
                // Calculate percent of current part
                let currentPercent = Math.floor(
                    ((index + 1) * 100) / targets.length,
                );
                console.log(currentTarget, currentPercent);

                // Add class active to current item
                element.addClass('active');

                // Modify current item icon to check icon
                element
                    .find('i')
                    .removeClass('fa-badge')
                    .addClass('fa-badge-check');

                // Process next target
                processTargetsSequentially(
                    targets,
                    index + 1,
                    action,
                    currentButton,
                    otherButton,
                );
            },
            error: (e) => {
                console.log('Error: ' + e.responseText);
                // Even if error occurs, continue with next target
                element.addClass('active');
                element
                    .find('i')
                    .removeClass('fa-badge')
                    .addClass('fa-badge-check');

                // Process next target
                processTargetsSequentially(
                    targets,
                    index + 1,
                    action,
                    currentButton,
                    otherButton,
                );
            },
        });
    }
}

/* ===============================================================
Plugin action ajax
=============================================================== */
function pluginActionAjax(buttonElement, action) {
    if (!jQuery(buttonElement).hasClass('disabled')) {
        // Get all closest buttons actions
        let allButtons = jQuery(buttonElement)
            .parent()
            .find('.irantheme-settings-plugins-box-triggers-button');
        // Get status input license
        let dataPluginName = jQuery(buttonElement).attr('data-plugin');
        // Get button text success
        let textSuccess = jQuery(buttonElement).attr('data-success');
        // Get button text installation
        let textInstallation = jQuery(buttonElement).attr('data-installation');
        // Get button text
        let buttonText = jQuery(buttonElement).find('cite');
        // Get spinner
        let spinner = jQuery(buttonElement).find('.irantheme-animate-rotate');
        // Get admin url ajax from demo
        var ajax_url = iranthemeWpData.adminAjax;
        // Send license key
        jQuery.ajax({
            url: ajax_url,
            type: 'post',
            data: {
                action: action,
                plugin_name: dataPluginName,
            },
            dataType: 'html',
            beforeSend: (xhr) => {
                // Show spinner
                jQuery(spinner).removeClass('hide');
                jQuery(buttonText).text(textInstallation);
                jQuery(allButtons).addClass('disabled');
            },
            success: (response) => {
                setTimeout(() => {
                    jQuery(spinner).addClass('hide');
                    jQuery(buttonText).text(textSuccess);
                }, 1000);
                setTimeout(() => {
                    // Reload page
                    location.reload();
                }, 2000);
            },
            complete: (response) => {
                jQuery(allButtons).removeClass('disabled');
            },
            error: (e) => {
                console.log('Error: ' + e.responseText);
            },
        });
    }
}

/* ===============================================================
Init checkbox dependencies
=============================================================== */
function initCheckboxDependencies() {
    var checkboxInput = jQuery(
        '.irantheme-settings .checkbox-input[data-base-priority]:not(.irantheme-initialized-dependent)',
    );
    // Check checkboxes dependencies ids show/hide
    jQuery(checkboxInput).each(function (index, element) {
        // Set checkboxes dependencies (Checkboxes and elements -> Nested)
        setCheckboxDependencies(element);
        jQuery(element).addClass('irantheme-initialized-dependent');
    });

    var buttonSide = jQuery(
        '.irantheme-settings .button-side-checkbox:not(.irantheme-initialized-dependent, .irantheme-not-dependent)',
    );
    jQuery(buttonSide).addClass('irantheme-initialized-dependent');

    // Check checkboxes click
    jQuery(buttonSide).on('click', function () {
        let element = jQuery(this)
            .parents('tr, .form-field, .irantheme-custom-option-container')
            .find('.checkbox-input[data-selector-priority]');
        if (element.length > 0) {
            setCheckboxDependencies(element, true);
        }
    });
}

/* ===============================================================
Init search settings fields
=============================================================== */
function initSearchSettingsFields() {
    // Add class active on focus input
    jQuery('.irantheme-settings-navigation-search-input').on(
        'focus',
        function () {
            jQuery(this)
                .parents('.irantheme-settings-navigation-search')
                .addClass('active');
        },
    );
    // Remove class active on focus
    jQuery('.irantheme-settings-navigation-search-input').on(
        'blur',
        function () {
            jQuery(this)
                .parents('.irantheme-settings-navigation-search')
                .removeClass('active');
        },
    );
    // Get all elements key (Options)
    var elementKeys = jQuery(
        '.irantheme-settings-options .irantheme-keywords-field',
    );
    // Prevent submit to input search when key enter hit
    jQuery('.irantheme-settings-navigation-search-input').on(
        'keydown',
        function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                return false;
            }
        },
    );
    // Run event text in input search
    jQuery('.irantheme-settings-navigation-search-input').on(
        'input',
        function () {
            // Hide all options
            jQuery('.irantheme-settings-options table tr').hide();
            // Check input is empty
            if (!jQuery(this).val()) {
                // Get section id from navigation
                let sectionId = jQuery(
                    '.irantheme-settings-navigation-list li a.current',
                ).attr('href');
                jQuery('.irantheme-settings-options-wrapper-holder').hide();
                // Restore all tr table to show
                jQuery('.irantheme-settings-options table tr').show();
                // Show current heading & description table tab
                jQuery('.irantheme-settings-options ' + sectionId).show();
                // Show current section table tab with tr(s)
                jQuery(
                    '.irantheme-settings-options ' + sectionId + ' tr',
                ).show();
                // Set checkbox dependencies
                jQuery(
                    '.irantheme-settings .checkbox-input[data-base-priority]',
                ).each(function (index, element) {
                    // Set checkboxes dependencies (Checkboxes and elements -> Nested)
                    setCheckboxDependencies(element);
                });
            } else {
                // Get search value
                let word = jQuery.trim(jQuery(this).val());
                jQuery('.irantheme-settings-options-wrapper-holder').hide();
                // Each from all elements (Options)
                jQuery(elementKeys).each(function (index, element) {
                    // Get keywords from field
                    let keywords = jQuery(element).attr('data-keywords');
                    // Check value keywords
                    if (keywords) {
                        // Split keys
                        keywords = keywords.split('-');
                        // Loop from keys
                        for (let i = 0; i < keywords.length; i++) {
                            // Check keys with word (Input value)
                            if (keywords[i].indexOf(word) >= 0) {
                                jQuery(element)
                                    .parents(
                                        '.irantheme-settings-options-wrapper-holder',
                                    )
                                    .show(100);
                                jQuery(element)
                                    .parents(
                                        'tr, .form-field, .irantheme-custom-option-container',
                                    )
                                    .show(100);
                                break;
                            }
                        }
                    }
                });
            }
        },
    );
}

/* ===============================================================
Init navigation & menubar section
=============================================================== */
function initNavigationMenubarSection() {
    // Create nav links for settings page
    jQuery('.irantheme-settings-navigation-list li a.tab').on(
        'click',
        function (e) {
            // Give current tab clicked
            tabSetSection(this);
        },
    );

    /* ===============================================================
        Menubar dropdown
        =============================================================== */
    jQuery('.irantheme-settings-navigation-list > li cite').on(
        'click',
        function () {
            setDropdownMenubar(this);
        },
    );
}

/* ===============================================================
Init restore to default
=============================================================== */
function initRestoreToDefaultOptions() {
    jQuery('.irantheme-reset-section').on('click', function () {
        // Get section id from navigation
        let sectionId = jQuery(
            '.irantheme-settings-navigation-list li a.current',
        ).attr('href');
        // Get table elements with section id
        let elementsSection = jQuery(
            '.irantheme-settings-options ' + sectionId + ' td',
        );
        // Restore to default
        restoreToDefault(elementsSection);
        // Display notification
        jQuery('.irantheme-reset-section')
            .parents('.button-inline-group')
            .find('.irantheme-notification-reset-all')
            .hide();
        jQuery('.irantheme-reset-section')
            .parents('.button-inline-group')
            .find('.irantheme-notification-reset-section')
            .fadeIn();
    });

    jQuery('.irantheme-reset-all').on('click', function () {
        // Get table elements (All)
        let elementsAll = jQuery('.irantheme-settings-options td');
        // Restore to default
        restoreToDefault(elementsAll);
        // Display notification
        jQuery('.irantheme-reset-all')
            .parents('.button-inline-group')
            .find('.irantheme-notification-reset-section')
            .hide();
        jQuery('.irantheme-reset-all')
            .parents('.button-inline-group')
            .find('.irantheme-notification-reset-all')
            .fadeIn();
    });
}

/* ===============================================================
Init radio buttons
=============================================================== */
function initRadioButtons() {
    // Attach event listeners to radio buttons
    jQuery(document).on('change', '.radio-input', function () {
        setRadioDependencies(this, true); // Apply dependencies with background highlight
    });
    // Initialize checked radio buttons
    const radioInput = jQuery(
        '.irantheme-settings .radio-input[checked="checked"]:not(.irantheme-initialized)',
    );
    radioInput.addClass('irantheme-initialized');
    radioInput.parent().addClass('active');

    // Initialize button-side radio buttons
    const buttonSide = jQuery(
        '.irantheme-settings .button-side-radio:not(.irantheme-initialized)',
    );
    buttonSide.addClass('irantheme-initialized');
    buttonSide.on('click', function () {
        const $this = jQuery(this);
        $this.parent().find('.button-side-radio').removeClass('active');
        $this.addClass('active');
        $this.find('.radio-input').prop('checked', true).trigger('change'); // Trigger change event
    });

    // Initialize radio thumbnails
    const radioThumbnail = jQuery(
        '.irantheme-settings .irantheme-settings-radio-thumbnail:not(.irantheme-initialized)',
    );
    radioThumbnail.addClass('irantheme-initialized');
    radioThumbnail.on('click', function () {
        const $this = jQuery(this);
        $this
            .parent()
            .find('.irantheme-settings-radio-thumbnail')
            .removeClass('active');
        $this.addClass('active');
        $this.find('.radio-input').prop('checked', true).trigger('change'); // Trigger change event
    });

    // Apply dependencies for initially checked radio buttons
    radioInput.each(function () {
        setRadioDependencies(this, false); // Apply dependencies without background highlight
    });
}

/* ===============================================================
Set radio button dependencies
=============================================================== */
function setRadioDependencies(element, bgClickChange = false) {
    const $element = jQuery(element);

    // Get dependencies from the selected radio button's data attributes
    const dependentsOnIds = $element.data('dependents-on')
        ? $element.data('dependents-on').split(',')
        : [];
    const dependentsOffIds = $element.data('dependents-off')
        ? $element.data('dependents-off').split(',')
        : [];

    // Show dependents for the selected radio button
    dependentsOnIds.forEach((id) => {
        const $field = jQuery(`#${id}`).parents(
            'tr, .form-field, .irantheme-custom-option-container',
        );
        $field.show();

        // Handle nested checkbox dependencies
        const $nestedCheckboxes = $field.find('.checkbox-input');
        $nestedCheckboxes.each(function () {
            setCheckboxDependencies(this, bgClickChange); // Apply checkbox dependencies
        });

        if (bgClickChange) {
            $field.addClass('irantheme-option-active');
            setTimeout(
                () => $field.removeClass('irantheme-option-active'),
                5000,
            );
        }
    });

    // Hide dependents for the unselected radio buttons
    dependentsOffIds.forEach((id) => {
        console.log(id);

        jQuery(`#${id}`)
            .parents('tr, .form-field, .irantheme-custom-option-container')
            .hide();
    });
}

/* ===============================================================
Init checkbox buttons
=============================================================== */
function initCheckboxButtons() {
    // Holder initialized
    var checkboxes = jQuery(
        '.irantheme-settings .checkbox-input:not(.irantheme-initialized)',
    );
    jQuery(checkboxes).addClass('irantheme-initialized');
    jQuery(checkboxes).each((index, element) => {
        if (jQuery(element).val() == 'on') {
            jQuery(element)
                .siblings()
                .find('.button-side-checkbox-on')
                .addClass('active');
        } else {
            jQuery(element)
                .siblings()
                .find('.button-side-checkbox-off')
                .addClass('active');
        }
    });
    // Holder initialized
    var buttonSide = jQuery(
        '.irantheme-settings .button-side-checkbox:not(.irantheme-initialized)',
    );

    jQuery(buttonSide).on('click', function () {
        jQuery(this)
            .parent()
            .find('.button-side-checkbox')
            .removeClass('active');
        jQuery(this).addClass('active');
        if (jQuery(this).hasClass('button-side-checkbox-on')) {
            jQuery(this).parent().parent().find('.checkbox-input').val('on');
            jQuery(this).parent().parent().find('.checkbox-input');
        } else if (jQuery(this).hasClass('button-side-checkbox-off')) {
            jQuery(this).parent().parent().find('.checkbox-input').val('off');
        }
    });
    jQuery(buttonSide).addClass('irantheme-initialized');
}

/* ===============================================================
Init multi checkbox buttons
=============================================================== */
function initMultiCheckboxButtons() {
    // Holder initialized
    var buttonSide = jQuery(
        '.irantheme-settings .button-side-checkbox-multi:not(.irantheme-initialized)',
    );

    jQuery(buttonSide).on('click', function () {
        let item_checked = '';
        let dataValues = '';
        if (jQuery(this).hasClass('active')) {
            jQuery(this).removeClass('active');
        } else {
            jQuery(this).addClass('active');
        }
        item_checked = jQuery(this)
            .parent('.button-side-group')
            .find('.button-side-checkbox-multi.active');
        item_checked.each(function () {
            dataValues += jQuery(this).attr('data-value') + ',';
        });
        // remove last kama
        dataValues = dataValues.slice(0, -1);
        jQuery(this).siblings('.multi-checkbox-input').val(dataValues);
    });
    jQuery(buttonSide).addClass('irantheme-initialized');
}

/* ===============================================================
Init color picker fields
=============================================================== */
function initColorPicker() {
    // Holder initialized
    var colorPallet = jQuery(
        '.irantheme-settings .irantheme-color-picker-pallet:not(.irantheme-initialized)',
    );
    jQuery(colorPallet).hide();
    jQuery(colorPallet).each(function () {
        jQuery(this).farbtastic(
            jQuery(this)
                .closest('.irantheme-color-picker')
                .find('.irantheme-color-picker-input'),
        );
        let hash = jQuery(this)
            .parents('.irantheme-color-picker')
            .find('.irantheme-color-picker-input')
            .val();
        jQuery(this)
            .parents('.irantheme-color-picker')
            .find('.irantheme-color-picker-preview')
            .css('background', hash);
    });

    jQuery(colorPallet)
        .parents('.irantheme-color-picker')
        .find('.irantheme-color-picker-pallet')
        .on('mousemove', function () {
            let hash = jQuery(this)
                .parents('.irantheme-color-picker')
                .find('.irantheme-color-picker-input')
                .val();
            jQuery(this)
                .parents('.irantheme-color-picker')
                .find('.irantheme-color-picker-preview')
                .css('background', hash);
        });
    jQuery(colorPallet).addClass('irantheme-initialized');

    // Holder initialized
    var colorInput = jQuery(
        '.irantheme-settings .irantheme-color-picker-input:not(.irantheme-initialized)',
    );
    jQuery(colorInput).addClass('irantheme-initialized');

    var colorPreview = jQuery(colorInput)
        .parents('.irantheme-color-picker')
        .find('.irantheme-color-picker-preview');

    jQuery(colorPreview).on('click', function () {
        jQuery(this)
            .parents('.irantheme-color-picker')
            .find('.irantheme-color-picker-pallet')
            .fadeIn();
    });

    jQuery(document).on('mousedown', function () {
        jQuery(colorPallet).each(function () {
            var display = jQuery(this).css('display');
            if (display == 'block') jQuery(this).fadeOut();
        });
    });
}

/* ===============================================================
Init media uploader
=============================================================== */
function initMediaUploader() {
    jQuery.fn.uploadMediaFile = function (button, preview_media) {
        let button_id = button.attr('id');
        let field_value = jQuery(button)
            .parent()
            .parent()
            .find(
                '.irantheme_image_data_field, .irantheme_video_data_field, .irantheme_media_gallery_data_field',
            );
        let preview_id = button_id.replace('_button', '_preview');
        let multipleSelect = false;
        // Check multiple select
        if (button.data('select-type') == 'multiple') {
            multipleSelect = true;
        }
        // Create the media frame.
        let file_frame = (wp.media.frames.file_frame = wp.media({
            title: jQuery(this).data('uploader_title'),
            button: {
                text: jQuery(this).data('uploader_button_text'),
            },
            multiple: multipleSelect,
        }));

        // When an image is selected, run a callback.
        file_frame.on('select', function () {
            // Mono select
            if (!multipleSelect) {
                attachment = file_frame
                    .state()
                    .get('selection')
                    .first()
                    .toJSON();
                jQuery(field_value).val(attachment.id);
                if (preview_media) {
                    if (
                        jQuery('#' + preview_id).children('source').length > 0
                    ) {
                        jQuery('#' + preview_id)
                            .children('source')
                            .attr('src', attachment.url);
                        jQuery('#' + preview_id)[0].load();
                    } else {
                        jQuery('#' + preview_id).attr(
                            'src',
                            attachment.sizes.full.url,
                        );
                    }

                    // Show container image preview
                    jQuery('#' + preview_id)
                        .parent()
                        .parent()
                        .find('.image_preview, .video_preview')
                        .parent()
                        .removeClass('d-none');
                    jQuery('#' + preview_id)
                        .parent()
                        .parent()
                        .find('.image_preview, .video_preview')
                        .parent()
                        .show();

                    // Set basename on disabled input
                    let pathSrc;
                    if (
                        jQuery('#' + preview_id).children('source').length > 0
                    ) {
                        pathSrc = jQuery('#' + preview_id)
                            .parent()
                            .parent()
                            .find('.video_preview')
                            .children('source')
                            .attr('src');
                    } else {
                        pathSrc = jQuery('#' + preview_id)
                            .parent()
                            .parent()
                            .find('.image_preview')
                            .attr('src');
                    }
                    let basename = pathSrc.replace(/^.*[\\\/]/, '');
                    jQuery('#' + preview_id)
                        .parent()
                        .parent()
                        .find('.image_basename, .video_basename')
                        .val(basename);
                }
                // Multiple select
            } else {
                attachment = file_frame.state().get('selection').toJSON();
                let rawId = button_id.replace('_button', '');
                let mediaListId = button_id.replace('_button', '_media_list');
                let mediaList = jQuery('#' + mediaListId);
                // Show container image preview
                jQuery('#' + mediaListId)
                    .parent()
                    .show();
                attachment.forEach((element, index) => {
                    if (element != '') {
                        // Get template element
                        let templateTag = jQuery(mediaList)
                            .find(
                                '.irantheme-media-list-item-%s-template'.replace(
                                    /%s/g,
                                    element.type,
                                ),
                            )
                            .clone();
                        // Remove template class
                        templateTag.removeClass(
                            'irantheme-media-list-item-%s-template'.replace(
                                /%s/g,
                                element.type,
                            ),
                        );
                        if (jQuery(field_value).val()) {
                            jQuery(field_value).val(
                                jQuery(field_value).val() + ',',
                            );
                        }
                        jQuery(field_value).val(
                            jQuery(field_value).val() + element['url'],
                        );
                        // Get element count for use in id
                        let indexElement =
                            jQuery(mediaList).children().length - 2;
                        if (preview_media) {
                            if (element.type == 'video') {
                                templateTag
                                    .children(
                                        '.irantheme-media-list-item-thumb',
                                    )
                                    .attr('data-media-id', element['id']);
                                templateTag
                                    .children(
                                        '.irantheme-media-list-item-thumb',
                                    )
                                    .attr('data-file-url', element['url']);
                                templateTag
                                    .find('video')
                                    .attr('id', rawId + '_' + indexElement);
                                templateTag
                                    .find('video')
                                    .children('source')
                                    .attr('src', element.url);
                                mediaList.append(templateTag);
                                jQuery(
                                    '#' + rawId + '_' + indexElement,
                                )[0].load();
                            } else if (element.type == 'image') {
                                templateTag
                                    .children(
                                        '.irantheme-media-list-item-thumb',
                                    )
                                    .attr('data-media-id', element['id']);
                                templateTag
                                    .children(
                                        '.irantheme-media-list-item-thumb',
                                    )
                                    .attr('data-file-url', element['url']);
                                templateTag
                                    .find('img')
                                    .attr('id', rawId + '_' + indexElement);
                                templateTag
                                    .find('img')
                                    .attr('src', element.sizes.full.url);
                                mediaList.append(templateTag);
                            }
                            mediaList
                                .children('.irantheme-media-list-label')
                                .addClass('irantheme-media-selected');
                        }
                    }
                });
                magnificationThumbClickable();
            }
        });

        // Finally, open the modal
        file_frame.open();
    };
    // Holder initialized
    var uploader = jQuery(
        '.image_upload_button:not(.irantheme-initialized), .video_upload_button:not(.irantheme-initialized)',
    );
    jQuery(uploader).addClass('irantheme-initialized');

    jQuery(uploader).on('click', function () {
        jQuery(this).addClass('irantheme-initialized');
        jQuery.fn.uploadMediaFile(jQuery(this), true);
    });
    // Holder initialized
    var unloader = jQuery(
        '.image_delete_button:not(.irantheme-initialized), .video_delete_button:not(.irantheme-initialized)',
    );
    jQuery(unloader).addClass('irantheme-initialized');

    jQuery(unloader).on('click', function () {
        jQuery(this)
            .parent()
            .parent()
            .find(
                '.irantheme_image_data_field, .irantheme_video_data_field, .irantheme_media_gallery_data_field',
            )
            .val('');
        jQuery(this)
            .parent()
            .parent()
            .find('.image_basename, .video_basename')
            .val('');
        jQuery(this)
            .parent()
            .parent()
            .find('.container_image_preview, .container_video_preview')
            .hide();
        let previewItem = jQuery(this)
            .parent()
            .parent()
            .find('.container-media-preview .irantheme-media-list');
        previewItem
            .children('.irantheme-media-list-item')
            .find('img,video')
            .each(function (index, element) {
                if (jQuery(element).attr('id') != '') {
                    jQuery(element).parent().parent().remove();
                }
            });
        previewItem
            .children('.irantheme-media-list-label')
            .removeClass('irantheme-media-selected');
        return false;
    });
}

/* ===============================================================
Set checkboxes dependencies
=============================================================== */
function setCheckboxDependencies(element, bgClickChange = false) {
    // Cache the jQuery-wrapped element for better performance
    const $element = jQuery(element);

    // Get the starting z-index and selector priority from the element's data attributes
    const startZIndex = $element.data('z-index');
    const selectorPriority = $element.data('selector-priority');

    // Find all checkboxes with the same selector priority
    const primeCheckboxes = jQuery(
        `.irantheme-settings .checkbox-input[data-selector-priority=${selectorPriority}]`,
    );

    // Array to hold checkboxes for processing
    const checkboxes = [];

    // Loop through checkboxes starting from the current z-index
    for (let i = startZIndex; i < primeCheckboxes.length; i++) {
        // Push the checkbox with the current z-index into the array
        checkboxes.push(
            jQuery(
                `.irantheme-settings .checkbox-input[data-selector-priority=${selectorPriority}][data-z-index=${i}]`,
            ),
        );
    }

    // Apply dependencies for the current element
    setDependenciesFieldOptions($element, bgClickChange);

    // Loop through all checkboxes to handle their dependencies
    checkboxes.forEach(($checkbox) => {
        // Arrays to hold IDs of dependent checkboxes for "on" and "off" states
        const allCheckboxesOffIds = [];
        const allCheckboxesOnIds = [];

        // Get the dependent IDs for "off" and "on" states from the current checkbox
        const currentElementsOff = $checkbox.data('dependents-off');
        const currentElementsOn = $checkbox.data('dependents-on');

        // Loop through all checkboxes to find dependencies
        checkboxes.forEach(($cb) => {
            const cbId = $cb.attr('id');
            // If the current checkbox ID is in the "off" dependencies, add it to the array
            if (currentElementsOff.includes(cbId))
                allCheckboxesOffIds.push(cbId);
            // If the current checkbox ID is in the "on" dependencies, add it to the array
            if (currentElementsOn.includes(cbId)) allCheckboxesOnIds.push(cbId);
        });

        // Handle dependencies based on the checkbox's value
        if ($checkbox.val() === 'on') {
            // Hide all dependent checkboxes for the "off" state
            allCheckboxesOffIds.forEach((id) => {
                const $tempCheckbox = jQuery(`#${id}`);
                $tempCheckbox
                    .parents(
                        'tr, .form-field, .irantheme-custom-option-container',
                    )
                    .hide();
                hideCheckboxDependents($tempCheckbox); // Hide nested dependencies
            });

            // Show or hide dependent checkboxes for the "on" state
            allCheckboxesOnIds.forEach((id) => {
                const $tempCheckbox = jQuery(`#${id}`);
                if (
                    $tempCheckbox
                        .parents(
                            'tr, .form-field, .irantheme-custom-option-container',
                        )
                        .is(':hidden')
                ) {
                    hideCheckboxDependents($tempCheckbox); // Hide nested dependencies
                } else {
                    setDependenciesFieldOptions($tempCheckbox, bgClickChange); // Apply dependencies
                }
            });
        } else if ($checkbox.val() === 'off') {
            // Hide all dependent checkboxes for the "on" state
            allCheckboxesOnIds.forEach((id) => {
                const $tempCheckbox = jQuery(`#${id}`);
                $tempCheckbox
                    .parents(
                        'tr, .form-field, .irantheme-custom-option-container',
                    )
                    .hide();
                hideCheckboxDependents($tempCheckbox); // Hide nested dependencies
            });

            // Show or hide dependent checkboxes for the "off" state
            allCheckboxesOffIds.forEach((id) => {
                const $tempCheckbox = jQuery(`#${id}`);
                if (
                    $tempCheckbox
                        .parents(
                            'tr, .form-field, .irantheme-custom-option-container',
                        )
                        .is(':hidden')
                ) {
                    hideCheckboxDependents($tempCheckbox); // Hide nested dependencies
                } else {
                    setDependenciesFieldOptions($tempCheckbox, bgClickChange); // Apply dependencies
                }
            });
        }
    });
}

/**
 * Hide all dependent checkboxes for the given element.
 * @param {jQuery} $element - The jQuery-wrapped checkbox element.
 */
function hideCheckboxDependents($element) {
    // Get the dependent IDs for "off" and "on" states
    const dependenciesOffIds = $element.data('dependents-off').split(',');
    const dependenciesOnIds = $element.data('dependents-on').split(',');

    // Combine both sets of IDs and hide their parent rows or fields
    dependenciesOffIds.concat(dependenciesOnIds).forEach((id) => {
        jQuery(`#${id}`)
            .parents('tr, .form-field, .irantheme-custom-option-container')
            .hide();
    });
}

/**
 * Set field options based on the checkbox's state.
 * @param {jQuery} $element - The jQuery-wrapped checkbox element.
 * @param {boolean} bgClickChange - Whether to apply a background highlight effect on change.
 */
function setDependenciesFieldOptions($element, bgClickChange = false) {
    // Get the dependent IDs for "on" and "off" states
    const dependenciesOnIds = $element.data('dependents-on').split(',');
    const dependenciesOffIds = $element.data('dependents-off').split(',');

    // Handle dependencies based on the checkbox's value
    if ($element.val() === 'on') {
        // Show all dependent fields for the "on" state
        dependenciesOnIds.forEach((id) => {
            const $field = jQuery(`#${id}`).parents(
                'tr, .form-field, .irantheme-custom-option-container',
            );
            $field.show();
            // Apply a temporary background highlight if enabled
            if (bgClickChange) {
                $field.addClass('irantheme-option-active');
                setTimeout(
                    () => $field.removeClass('irantheme-option-active'),
                    5000,
                );
            }
        });
        // Hide all dependent fields for the "off" state
        dependenciesOffIds.forEach((id) => {
            jQuery(`#${id}`)
                .parents('tr, .form-field, .irantheme-custom-option-container')
                .hide();
        });
    } else if ($element.val() === 'off') {
        // Hide all dependent fields for the "on" state
        dependenciesOnIds.forEach((id) => {
            jQuery(`#${id}`)
                .parents('tr, .form-field, .irantheme-custom-option-container')
                .hide();
        });
        // Show all dependent fields for the "off" state
        dependenciesOffIds.forEach((id) => {
            const $field = jQuery(`#${id}`).parents(
                'tr, .form-field, .irantheme-custom-option-container',
            );
            $field.show();
            // Apply a temporary background highlight if enabled
            if (bgClickChange) {
                $field.addClass('irantheme-option-active');
                setTimeout(
                    () => $field.removeClass('irantheme-option-active'),
                    5000,
                );
            }
        });
    }
}

/*===============================================================
Save settings options
=============================================================== */
function initSaveSettingsOptions() {
    jQuery('#irantheme-form-settings-options-ajax').on('submit', function (e) {
        // Prevent to submit
        e.preventDefault();

        // Get save button
        let saveBtn = jQuery('.irantheme-settings-options-panel-submit');
        // Check save btn disabled class
        if (jQuery(saveBtn).hasClass('irantheme-button-disabled')) {
            return;
        }
        // Get spinner icon alt
        let spinnerAlt = saveBtn.find('#irantheme-spinner-alt');
        // Get check icon
        let check = saveBtn.find(
            '.irantheme-settings-options-panel-submit-check',
        );
        // Get notification save
        let notificationSaving = saveBtn.siblings(
            '.irantheme-notification-save-all',
        );
        // Get notification reset section
        let notificationResetSection = saveBtn.siblings(
            '.irantheme-notification-reset-section',
        );
        // Get notification reset all
        let notificationResetAll = saveBtn.siblings(
            '.irantheme-notification-reset-all',
        );
        // Get cog icon
        let cogBtn = saveBtn.find('.fa-light.fa-cog');
        // Check tinymce is loaded in page or no
        if (typeof tinymce != 'undefined') {
            // Save tinymce before send (This is require for prevent twice saving!)
            tinymce.triggerSave();
        }
        // Create form data for panel
        let dataFormPanel = jQuery(this).serialize();

        // Get admin url action
        let adminUrl = iranthemeWpData.adminAjax;

        // Send data to admin url with ajax
        jQuery.ajax({
            url: adminUrl,
            type: 'post',
            data: {
                action: 'settings_options',
                data_form: dataFormPanel,
            },
            dataType: 'html',
            // processData: false,
            // contentType: false,
            beforeSend: (xhr) => {
                // xhr.setRequestHeader('X-WP-Nonce', iranthemeWpData.nonce);
                cogBtn.hide();
                spinnerAlt.show();
            },
            success: (response) => {
                console.log(response);

                setTimeout(() => {
                    // Notification mode
                    check.fadeIn(250);
                    spinnerAlt.hide();
                    notificationSaving.fadeIn(250);
                }, 250);
            },
            complete: (response) => {
                setTimeout(() => {
                    cogBtn.fadeIn(250);
                    check.hide();
                    notificationSaving.fadeOut(250);
                    notificationResetSection.fadeOut(250);
                    notificationResetAll.fadeOut(250);
                }, 3000);
            },
            error: (e) => {
                console.log('Error' + e.responseText);
            },
        });
    });
}

/* ===============================================================
Default restore functions
=============================================================== */
// All default functions
function restoreToDefault(objects) {
    // Each of all objects section
    jQuery(objects).each((index, element) => {
        // Get data default from element
        let dataDefault = jQuery(element)
            .find('[data-default]')
            .attr('data-default');

        // Set default text input
        let inputText = jQuery(element).find('.default-input-value');
        defaultInputText(inputText, dataDefault);

        // Set default range input
        let inputRange = jQuery(element).find('.default-input-range');
        defaultInputRange(inputRange, dataDefault);

        // Set default textarea
        let textarea = jQuery(element).find('.default-textarea');
        defaultTextarea(textarea, dataDefault);

        // Set default checkbox
        let checkbox = jQuery(element).find('.default-input-checkbox');
        defaultInputCheckbox(checkbox, dataDefault);

        // Set default checkbox multi
        let checkboxMulti = jQuery(element).find(
            '.default-input-checkbox-multi',
        );
        defaultInputMultiCheckbox(checkboxMulti);

        // Set default radio
        let radio = jQuery(element).find('.default-input-radio');
        defaultInputRadio(radio);

        // Set default select option
        let option = jQuery(element).find('.default-select-option');
        defaultSelectOption(element, option);

        // Set default multiselect option
        let multiSelectOption = jQuery(element).find(
            '.default-select-multi-option',
        );
        defaultSelectMultiOption(multiSelectOption, dataDefault);

        // Set default image
        let image = jQuery(element).find('.default-input-image');
        defaultInputImage(image, dataDefault);

        // Set default video
        let video = jQuery(element).find('.default-input-video');
        defaultInputVideo(video, dataDefault);

        // Set default video
        let mediaGallery = jQuery(element).find('.default-input-media-gallery');
        defaultInputMediaGallery(mediaGallery, dataDefault);

        // Set default input color picker
        let colorPicker = jQuery(element).find('.default-input-color-picker');
        defaultInputColorPicker(colorPicker, dataDefault);

        // Set default input icon
        let inputIcon = jQuery(element).find('.default-input-icon');
        defaultInputIcon(inputIcon, dataDefault);
    });
}

// Default text input
function defaultInputText(element, dataDefault) {
    jQuery(element).val(dataDefault);
}

// Default range input
function defaultInputRange(element, dataDefault) {
    jQuery(element).val(dataDefault);
    jQuery(element).siblings('output').val(dataDefault);
    const min = jQuery(element).attr('min');
    const max = jQuery(element).attr('max');
    const val = jQuery(element).attr('data-default');
    jQuery(element).css(
        'background-size',
        ((val - min) * 100) / (max - min) + '% 100%',
    );
}

// Default textarea
function defaultTextarea(element, dataDefault) {
    jQuery(element).empty();
    jQuery(element).text(dataDefault);
    jQuery(element).val(dataDefault);
}

// Default input checkbox
function defaultInputCheckbox(element, dataDefault) {
    if (dataDefault == 'on') {
        jQuery(element).val('on');
        jQuery(element)
            .siblings()
            .find('.button-side-checkbox-on')
            .addClass('active');
        jQuery(element)
            .siblings()
            .find('.button-side-checkbox-off')
            .removeClass('active');
    } else {
        jQuery(element).val('off');
        jQuery(element)
            .siblings()
            .find('.button-side-checkbox-on')
            .removeClass('active');
        jQuery(element)
            .siblings()
            .find('.button-side-checkbox-off')
            .addClass('active');
    }
}

// Default input checkbox multi
function defaultInputMultiCheckbox(elements) {
    jQuery(elements).each((index, element) => {
        let defaultItem = jQuery(element).attr('data-default').split(',');
        let checkboxMultiSpans = jQuery(element).siblings(
            '.button-side-checkbox-multi',
        );
        checkboxMultiSpans.removeClass('active');
        checkboxMultiSpans.each(function (index, checkboxElem) {
            defaultItem.forEach((valueElem) => {
                if (jQuery(checkboxElem).attr('data-value') == valueElem) {
                    jQuery(checkboxElem).addClass('active');
                }
            });
        });
        jQuery(element).val(defaultItem.join(','));
    });
}

// Default input radio
function defaultInputRadio(element) {
    jQuery(element).each((index) => {
        if (jQuery(element[index]).attr('data-default') == 'on') {
            jQuery(element[index]).prop('checked', true);
            jQuery(element[index])
                .parent('.button-side-radio')
                .addClass('active');
            jQuery(element[index])
                .parent('.irantheme-settings-radio-thumbnail')
                .addClass('active');
        } else {
            jQuery(element[index]).prop('checked', false);
            jQuery(element[index])
                .parent('.button-side-radio')
                .removeClass('active');
            jQuery(element[index])
                .parent('.irantheme-settings-radio-thumbnail')
                .removeClass('active');
        }
    });
}

// Default select option
function defaultSelectOption(element, option) {
    jQuery(element)
        .find('.irantheme-select-list-options .irantheme-select-option-item')
        .each(function (index, item) {
            jQuery(item).removeClass('irantheme-select-option-active');
            for (let i = 0; i < option.length; i++) {
                if (
                    jQuery(option[i]).data('default') == 'on' &&
                    jQuery(option[i]).val() ==
                        jQuery(item)
                            .find('.irantheme-select-option-item-value')
                            .attr('data-value')
                ) {
                    jQuery(item).addClass('irantheme-select-option-active');
                    jQuery(item)
                        .parent()
                        .siblings('.irantheme-holder-selected-option')
                        .html(jQuery(item).html());
                }
            }
        });
    jQuery(option).each((index, currentOption) => {
        if (jQuery(currentOption).attr('data-default') == 'on') {
            jQuery(currentOption).prop('selected', true);
        } else {
            jQuery(currentOption).prop('selected', false);
        }
    });
}

// Default select multi option
function defaultSelectMultiOption(element, stringIds) {
    if (!stringIds) {
        return;
    }
    var defaultIds = stringIds.split(',');
    var parent = jQuery(element).parents('.irantheme-multiselect');
    let multiSelectDropdown = jQuery(parent).find(
        '.irantheme-multiselect-dropdown',
    );

    jQuery(multiSelectDropdown).removeClass('irantheme-dropdown-hasvalue');
    jQuery(multiSelectDropdown)
        .find('.irantheme-multiselect-choice')
        .addClass('d-none');

    jQuery(parent)
        .find('.irantheme-multiselect-list li')
        .each(function (index, listOption) {
            jQuery(listOption).removeClass(
                'irantheme-multiselect-option-selected',
            );
            // Loop from array default ids
            for (let i = 0; i < defaultIds.length; i++) {
                if (defaultIds[i] == jQuery(listOption).attr('data-value')) {
                    jQuery('.irantheme-multiselect-dropdown').addClass(
                        'irantheme-dropdown-hasvalue',
                    );
                    jQuery(listOption).addClass(
                        'irantheme-multiselect-option-selected',
                    );

                    jQuery(multiSelectDropdown)
                        .find('.irantheme-multiselect-choice')
                        .each(function (index, dropdownItem) {
                            if (
                                jQuery(dropdownItem).attr('data-value') ==
                                jQuery(listOption).attr('data-value')
                            ) {
                                jQuery(dropdownItem).removeClass('d-none');
                            }
                        });
                }
            }
        });
    initMultiSelectBox();
    // Replace main multi select value with default string ids
    jQuery(element).val(stringIds);
}

// Default input image
function defaultInputImage(element, dataDefault) {
    jQuery(element).val(dataDefault);
    let dataDefaultUrl = jQuery(element).attr('data-default-url');
    if (!dataDefault) {
        jQuery(element).siblings('.container_image_preview').addClass('d-none');
        jQuery(element).siblings('.image_basename').val('فاقد تصویر');
    } else {
        jQuery(element)
            .siblings('.container_image_preview')
            .find('.image_preview')
            .attr('src', dataDefaultUrl);
        jQuery(element)
            .siblings('.container_image_preview')
            .removeClass('d-none');
        jQuery(element)
            .siblings('.image_basename')
            .val(jQuery(element).data('default-text'));
    }
}

// Default input video
function defaultInputVideo(element, dataDefault) {
    jQuery(element).val(dataDefault);
    if (!dataDefault) {
        jQuery(element).siblings('.container_video_preview').hide();
        jQuery(element).siblings('.video_basename').val('فاقد ویدیو');
    } else {
        jQuery(element)
            .siblings('.video_preview')
            .children('source')
            .attr('src', dataDefault);
        jQuery(element).siblings('.container_video_preview').show();
        jQuery(element)
            .siblings('.video_basename')
            .val(jQuery(element).data('default-text'));
    }
}

// Default input media
function defaultInputMediaGallery(element, dataDefault) {
    jQuery(element).val(dataDefault);
    if (!dataDefault) {
        jQuery(element)
            .siblings('.container-media-preview')
            .find('.irantheme-media-list-item')
            .not(
                '.irantheme-media-list-item-image-template,.irantheme-media-list-item-video-template',
            )
            .remove();
        jQuery(element)
            .siblings('.container-media-preview')
            .find('.irantheme-media-list-label')
            .removeClass('irantheme-media-selected');
    } else {
        // jQuery(element)
        //     .siblings('.video_preview')
        //     .children('source')
        //     .attr('src', dataDefault);
        // jQuery(element).siblings('.container_video_preview').show();
        // jQuery(element)
        //     .siblings('.video_basename')
        //     .val(jQuery(element).data('default-text'));
    }
}

// Default input color picker
function defaultInputColorPicker(element, dataDefault) {
    jQuery(element).val(dataDefault);
    jQuery(element).css('background-color', dataDefault);
}

// Default input icon
function defaultInputIcon(element, dataDefault) {
    // Add data default to input value
    jQuery(element).val(dataDefault);
    // Get parent element
    let parent = jQuery(element).parents('.irantheme-icons-selector-fields');
    // Check data default to assignment
    if (dataDefault == '') {
        // Remove preview icon on empty data default
        jQuery(parent)
            .find('.irantheme-icons-selector-preview')
            .addClass('d-none');
    } else {
        // Add value to preview icon and display preview icon
        jQuery(parent)
            .find('.irantheme-icons-selector-preview')
            .removeClass('d-none');
        jQuery(parent)
            .find('.irantheme-icons-selector-preview i')
            .attr('class', '');
        jQuery(parent)
            .find('.irantheme-icons-selector-preview i')
            .addClass(dataDefault);
    }
}

/* ===============================================================
Tabs & Navigation
=============================================================== */

// Combination tab and ul menubar triggered
function combinationTabUlMenubar(element) {
    // Show intended tab section
    tabSetSection(element, true);
    // Add class active to intended element
    jQuery(element).parent().parent().parent().find('cite').addClass('active');
    // Show intended ul element
    jQuery(element).parent().parent().show();
}

// Set dropdown menubar
function setDropdownMenubar(element) {
    let hasClass = false;
    if (jQuery(element).hasClass('active')) {
        hasClass = true;
    }
    // Remove all active class elements
    jQuery('.irantheme-settings-navigation-list > li cite')
        .removeClass('active')
        .parent()
        .children('ul')
        .slideUp(250);
    // Check has active class
    if (!hasClass) {
        // Add class active to intended element
        jQuery(element).addClass('active');
        jQuery(element).parent().children('ul').slideDown(250);
    }
}

// Remove element and show id
function tabSetSection(element, isPrimary = false) {
    // Move the "current" CSS class.
    jQuery('.irantheme-settings-navigation-list li a.tab').removeClass(
        'current',
    );
    jQuery(element).addClass('current');

    // If the link is a tab, show only the specified tab.
    var tabId = jQuery(element).attr('href');

    // Remove the first occurrence of # from the selected string (will be added manually below).
    tabId = tabId.replace('#', '', tabId);

    // Set cookie for tab
    setCookie('iranthemeTabMenubarId', tabId, 0.25);

    // Hide all elements panel options
    jQuery(
        '.irantheme-settings-options .irantheme-settings-options-wrapper-holder',
    ).hide();

    // Check for cookie primary (on load)
    if (isPrimary) {
        jQuery('.irantheme-settings-options-wrapper-holder#' + tabId).show();
    } else {
        jQuery('.irantheme-settings-options-wrapper-holder#' + tabId).fadeIn(
            200,
        );
    }
}

/* ===============================================================
Handling input range
=============================================================== */
function handleInputRangeTrigger(e) {
    let target = e.target;
    if (e.target.type !== 'range') {
        target = document.getElementById('range');
    }
    const min = target.min;
    const max = target.max;
    const val = target.value;

    target.style.backgroundSize = ((val - min) * 100) / (max - min) + '% 100%';
}

function handleInputRangeInit(element) {
    const min = jQuery(element).attr('min');
    const max = jQuery(element).attr('max');
    const val = jQuery(element).attr('value');

    jQuery(element).css(
        'background-size',
        ((val - min) * 100) / (max - min) + '% 100%',
    );
}

// Init range input
function initRangeInputs() {
    // Holder initialized
    let rangeInputs = jQuery(
        '.range-input-holder .input-range-field:not(.irantheme-initialized)',
    );
    jQuery(rangeInputs).addClass('irantheme-initialized');
    // Loop from inputs range
    jQuery(rangeInputs).each((index, element) => {
        handleInputRangeInit(element);
        jQuery(element).on('input', handleInputRangeTrigger);
    });
}

/* ===============================================================
Cookies
=============================================================== */

// Set cookie
function setCookie(cname, cvalue, exdays) {
    const d = new Date();
    d.setTime(d.getTime() + exdays * 24 * 60 * 60 * 1000);
    let expires = 'expires=' + d.toUTCString();
    document.cookie = cname + '=' + cvalue + ';' + expires + ';path=/;';
}

// Get cookie
function getCookie(cname) {
    let name = cname + '=';
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return '';
}

// Init select box
function initSelectBoxInputs() {
    // Holder initialized
    var holder = jQuery(
        '.irantheme-holder-selected-option:not(.irantheme-initialized)',
    );
    // Toggle holder options
    jQuery(holder).on('click', function (e) {
        // Check current list selected and hide all lists (This structure is require to true working)
        if (
            jQuery(this)
                .siblings('.irantheme-select-list-options')
                .is(':hidden')
        ) {
            jQuery('.irantheme-select-list-options').hide();
            jQuery(this).siblings('.irantheme-select-list-options').show();
        } else {
            jQuery(this).siblings('.irantheme-select-list-options').toggle();
        }
    });
    jQuery(holder).addClass('irantheme-initialized');

    // Holder initialized
    var selectOptions = jQuery(
        '.irantheme-select-list-options:not(.irantheme-initialized)',
    );
    // Trigger click option and assign option to main select and add current option to preview
    jQuery(selectOptions)
        .find('.irantheme-select-option-item')
        .on('click', function () {
            // Remove all select option item active class
            jQuery(this)
                .parent()
                .find('.irantheme-select-option-item')
                .removeClass('irantheme-select-option-active');
            // Active current option
            jQuery(this).addClass('irantheme-select-option-active');
            // Get value current option
            let valueOption = jQuery(this)
                .find('.irantheme-select-option-item-value')
                .attr('data-value');
            // Get current active option elements
            let elementsSelected = jQuery(this).html();
            // Assign active elements to preview option
            jQuery(this)
                .parent()
                .siblings('.irantheme-holder-selected-option')
                .html(elementsSelected);
            // Select main option
            jQuery(this)
                .parent()
                .siblings('.irantheme-select-main')
                .find(`.irantheme-select-main-option[value=${valueOption}]`)
                .prop('selected', true);
            // Hide all lists
            jQuery('.irantheme-select-list-options').hide();
        });
    jQuery(selectOptions).addClass('irantheme-initialized');
    // Close all lists
    jQuery(document).on('click', function (e) {
        let current = jQuery(e.target).parents('.irantheme-custom-select');
        if (!jQuery(current).hasClass('irantheme-custom-select')) {
            jQuery('.irantheme-select-list-options').hide();
        }
    });
}

// Init multi select box
function initMultiSelectBox() {
    jQuery('.irantheme-multiselect').each((index, element) => {
        // Holder initialized
        var selectBox = jQuery(element).find(
            '.irantheme-multiselect-field:not(.irantheme-initialized)',
        );
        jQuery(selectBox).addClass('irantheme-initialized');

        // Get require elements
        var dropdown = jQuery(element).find('.irantheme-multiselect-dropdown');
        var list = jQuery(element).find('.irantheme-multiselect-list');
        var option = jQuery(element).find('.irantheme-multiselect-option');

        // Hide notation empty list
        if (
            jQuery(dropdown)
                .children('.irantheme-multiselect-choice')
                .is(':visible').length > 0
        ) {
            jQuery(dropdown).addClass('irantheme-dropdown-hasvalue');
        }

        jQuery(option).on('click', function (e) {
            e.stopPropagation();

            // Get current clicked option
            var optionClicked = jQuery(this);

            // Add selected class to current option clicked
            optionClicked.addClass('irantheme-multiselect-option-selected');

            // Get data values and update select box value
            let newValue = optionClicked.attr('data-value');
            let prevValues = jQuery(selectBox).val() || '';
            let valuesArray = [];

            // Split existing values into array (if any)
            if (prevValues) {
                valuesArray = prevValues.split(',').filter(Boolean); // Remove empty values
            }

            // Add new value if not already present
            if (valuesArray.indexOf(newValue) === -1) {
                valuesArray.push(newValue);
            }

            // Update select box with comma-separated values
            jQuery(selectBox).val(valuesArray.join(','));

            // Show option clicked in dropdown holder
            jQuery(dropdown)
                .find('.irantheme-multiselect-choice')
                .each(function (index, elem) {
                    if (
                        jQuery(elem).text() === optionClicked.children().text()
                    ) {
                        jQuery(elem).removeClass('d-none');
                        jQuery(dropdown).append(elem);
                    }
                });

            // Hide notation empty list
            jQuery(dropdown).addClass('irantheme-dropdown-hasvalue');

            // Show notation empty list if all options are selected
            if (
                jQuery(element).find('.irantheme-multiselect-option-selected')
                    .length === jQuery(option).length
            ) {
                jQuery(list)
                    .find('.irantheme-multiselect-noselections')
                    .removeClass('d-none');
            }

            initMultiSelectRemovable();
        });

        initMultiSelectRemovable();

        // Show dropdown and list holder
        jQuery(dropdown).on('click', function (e) {
            e.stopPropagation();
            e.preventDefault();
            // Active dropdown for angle rotation
            jQuery(this).addClass('irantheme-multiselect-dropdown-active'); // Change toggle class to add class for testing
            jQuery(list).addClass('irantheme-multiselect-list-active'); // Change toggle class to add class for testing
        });

        // Hide dropdown and list holder
        jQuery(document).on('click touch', function (e) {
            if (
                jQuery(dropdown).hasClass(
                    'irantheme-multiselect-dropdown-active',
                )
            ) {
                // Change toggle class to remove class for testing
                jQuery(dropdown).removeClass(
                    'irantheme-multiselect-dropdown-active',
                );
                jQuery(list).removeClass('irantheme-multiselect-list-active');
            }
        });

        function initMultiSelectRemovable() {
            // Holder initialized
            var choices = jQuery(element).find(
                '.irantheme-multiselect-choice:not(.irantheme-initialized)',
            );
            jQuery(choices).addClass('irantheme-initialized');
            jQuery(choices).on('click', function (e) {
                e.stopPropagation();
                // Hide current choice
                jQuery(this).addClass('d-none');
                let dataValue = jQuery(this).attr('data-value');
                // Remove choice from dropdown and shown list
                jQuery(list)
                    .find(
                        '.irantheme-multiselect-option[data-value=' +
                            dataValue +
                            ']',
                    )
                    .removeClass('irantheme-multiselect-option-selected');
                jQuery(element)
                    .find('.irantheme-multiselect-noselections')
                    .addClass('d-none');

                // Remove data value removed option from input hidden value
                let valueSplitted = jQuery(choices)
                    .parents(
                        'tr, .form-field, .irantheme-custom-option-container',
                    )
                    .find('.irantheme-multiselect-field')
                    .val()
                    .split(',');
                for (let i = 0; i < valueSplitted.length; i++) {
                    if (valueSplitted[i] == dataValue) {
                        valueSplitted.splice(i, 1);
                    }
                }
                let strValues = valueSplitted.join(',');
                jQuery(selectBox).val(strValues);

                // Show notation empty dropdown
                if (jQuery(dropdown).children(':visible').length === 0) {
                    jQuery(dropdown).removeClass('irantheme-dropdown-hasvalue');
                }
            });
        }
    });
}

/* ===============================================================
Extra settings
=============================================================== */
function extraSettings() {
    // Holder initialized
    var border = jQuery(
        '.irantheme-parent-tr-border-bottom-none:not(.irantheme-initialized)',
    );
    jQuery(border).addClass('irantheme-initialized');
    // Remove all border bottom from tr none border bottom
    jQuery(border).parents('tr').css('border-bottom', 'none');

    // Header sub menu toggle
    jQuery('.irantheme-settings-header-addition-list-item-toggle').on(
        'click',
        function (e) {
            e.preventDefault();
            let toggle = e.target;
            if (
                !jQuery(toggle).hasClass(
                    'irantheme-settings-header-addition-list-item-toggle',
                )
            ) {
                toggle = jQuery(toggle).parents(
                    '.irantheme-settings-header-addition-list-item-toggle',
                );
            }

            if (jQuery(toggle).hasClass('active')) {
                jQuery(toggle).removeClass('active');
                let hash = jQuery(toggle).attr('href');
                jQuery(hash).removeClass('active');
            } else {
                jQuery(toggle)
                    .parents('.irantheme-settings-header-addition-list')
                    .find(
                        '.irantheme-settings-header-addition-list-item-toggle, .irantheme-settings-header-addition-sub-box',
                    )
                    .removeClass('active');
                jQuery(toggle).toggleClass('active');

                let hash = jQuery(toggle).attr('href');
                jQuery(hash).toggleClass('active');
            }
        },
    );
}

/*===============================================================
Init newsletter actions (Send emails)
=============================================================== */
function initNewsletterAction() {
    jQuery('.irantheme-newsletter-send-mail-submit').on('click', function (e) {
        e.preventDefault();
        if (confirm('آیا از ارسال ایمیل به اعضای خبرنامه مطمئن هستید؟')) {
            let current = jQuery(this);
            let mainTitleButton = jQuery(this).text();
            // Get subject value
            let subject = jQuery(
                '#irantheme_newsletter_send_mail_subject',
            ).val();
            // Get content value
            let content = jQuery(
                '#irantheme_newsletter_send_mail_content',
            ).val();
            // Get admin url ajax from demo
            var ajax_url = iranthemeWpData.adminAjax;
            jQuery.ajax({
                url: ajax_url,
                type: 'post',
                data: {
                    action: 'newsletter_send_mail',
                    subject: subject,
                    content: content,
                },
                dataType: 'html',
                beforeSend: (response) => {
                    jQuery(current).text('در حال ارسال ... (کمی صبر کنید)');
                },
                success: (response) => {
                    jQuery(current).text('با موفقیت ارسال شد');
                },
                complete: (response) => {
                    let parent = jQuery(current).parents('td');
                    // Remove report holder if is exist
                    if (
                        jQuery(parent).find('.irantheme-report-holder').length >
                        0
                    ) {
                        jQuery(parent)
                            .find('.irantheme-report-holder')
                            .remove();
                    }
                    // Create textarea for report mails sended
                    let reportTags = jQuery(
                        '<div class="irantheme-report-holder"><textarea class="default-textarea" rows="5" cols="50" style="margin-top:1rem;" readonly>' +
                            response.responseText +
                            '</textarea><label class="irantheme-settings-options-description">گزارش لیست ایمیل های ارسال شده</label></div>',
                    );
                    // Show report elements tags
                    jQuery(reportTags)
                        .appendTo(jQuery(current).parents('td'))
                        .fadeIn();
                    // Back to default button title
                    setTimeout(() => {
                        jQuery(current).text(mainTitleButton);
                    }, 5000);
                },
                error: (e) => {
                    console.log('Error: ' + e);
                },
            });
        }
    });
}

/*===============================================================
Init test sms actions (Send test sms)
=============================================================== */
function initSmsTestAction() {
    jQuery('.irantheme-sms-send-otp-code-submit').on('click', function (e) {
        e.preventDefault();
        if (confirm('آیا از ارسال پیامک مطمئن هستید؟')) {
            let current = jQuery(this);
            // Get content value
            let phoneNumber = jQuery('#irantheme_sms_test_phone').val();
            // Get admin url ajax from demo
            var ajax_url = iranthemeWpData.adminAjax;
            jQuery.ajax({
                url: ajax_url,
                type: 'post',
                data: {
                    action: 'irantheme_ajax_send_test_sms',
                    phone_number: phoneNumber,
                },
                dataType: 'html',
                beforeSend: (response) => {
                    jQuery(current).text('در حال ارسال ... (کمی صبر کنید)');
                },
                success: (response) => {
                    let jsonResponse = JSON.parse(response);
                    console.log('success: ' + jsonResponse.data.message);
                    jQuery(current).text('با موفقیت ارسال شد');
                },
                complete: (response) => {},
                error: (e) => {
                    console.log('Error: ' + e);
                },
            });
        }
    });
}

/*===============================================================
Init Attributes Summary MultiSelect
=============================================================== */
function initAttributesSummaryMultiSelect() {
    let isSaving = false;

    jQuery('.save_attributes').on('click', function (e) {
        if (isSaving) return;
        isSaving = true;

        const productId = jQuery('#post_ID').val();

        // Staircase delay to ensure full storage
        const checkInterval = setInterval(function () {
            jQuery.ajax({
                url: iranthemeWpData.adminAjax,
                type: 'POST',
                data: {
                    action: 'get_all_product_attributes',
                    product_id: productId,
                },
                dataType: 'json',
                success: function (response) {
                    clearInterval(checkInterval);
                    isSaving = false;
                    jQuery('#irantheme_custom_field_product_attr').removeClass(
                        'irantheme-initialized',
                    );
                    let multiSelectList = jQuery(
                        '#irantheme_custom_field_product_attr',
                    ).siblings('.irantheme-multiselect-list');
                    let multiSelectDropDown = jQuery(
                        '#irantheme_custom_field_product_attr',
                    ).siblings('.irantheme-multiselect-dropdown');
                    console.log(response.data);
                    response.data.forEach(function (element, index) {
                        let existingListItem = jQuery(multiSelectList).find(
                            '[data-value="' + element['decoded_value'] + '"]',
                        );
                        let existingDropDownItem = jQuery(
                            multiSelectDropDown,
                        ).find(
                            '[data-value="' + element['decoded_value'] + '"]',
                        );
                        if (existingListItem.length === 0) {
                            let listItem =
                                '<li class="irantheme-multiselect-option" data-value="' +
                                element['decoded_value'] +
                                '">' +
                                '<a class="irantheme-multiselect-text">' +
                                element['decoded_label'] +
                                '</a></li>';
                            multiSelectList.append(listItem);
                            if (
                                !multiSelectList
                                    .find('.irantheme-multiselect-noselections')
                                    .hasClass('d-none')
                            ) {
                                multiSelectList
                                    .find('.irantheme-multiselect-noselections')
                                    .addClass('d-none');
                            }
                        }
                        if (existingListItem.length === 0) {
                            let dropDownItem =
                                '<span class="irantheme-multiselect-choice d-none" data-value="' +
                                element['decoded_value'] +
                                '">' +
                                element['decoded_label'] +
                                '<i class="dashicons dashicons-no-alt"></i></span>';
                            multiSelectDropDown.append(dropDownItem);
                        }
                    });
                    initMultiSelectBox();
                },
            });
        }, 1000);

        // timeout to prevent infinite check
        setTimeout(function () {
            clearInterval(checkInterval);
            isSaving = false;
        }, 10000);
    });
}

/*===============================================================
Gallery media thumb image magnification
=============================================================== */

function magnificationThumbClickable() {
    jQuery('.irantheme-settings').on(
        'click',
        '.irantheme-media-list-item .irantheme-media-list-item-thumb-toolbox .irantheme-media-list-item-thumb-toolbox-zoom-in',
        function (e) {
            e.preventDefault();
            // Get image thumb magnified holder
            let thumbMagnifiedHolder = jQuery(
                '.irantheme-media-gallery-thumb-magnification',
            );
            let thumbSelected = jQuery(this)
                .parent()
                .parent('.irantheme-media-list-item-thumb');
            if (thumbSelected.attr('data-file-type') == 'image') {
                thumbMagnifiedHolder.find('img').show();
                thumbMagnifiedHolder
                    .find('img')
                    .attr('src', thumbSelected.find('img').attr('src'));
                thumbMagnifiedHolder.find('video').hide();
                thumbMagnifiedHolder.fadeIn('fast');
            } else if (thumbSelected.attr('data-file-type') == 'video') {
                thumbMagnifiedHolder.find('video').show();
                thumbMagnifiedHolder
                    .find('video')
                    .children('source')
                    .attr(
                        'src',
                        thumbSelected
                            .find('video')
                            .children('source')
                            .attr('src'),
                    );
                thumbMagnifiedHolder.find('img').hide();
                jQuery(thumbMagnifiedHolder.find('video'))[0].load();
                thumbMagnifiedHolder.fadeIn('fast');
            }
        },
    );
    // Close button
    jQuery('.irantheme-settings').on(
        'click',
        '.irantheme-media-gallery-thumb-magnification .irantheme-media-gallery-thumb-magnification-close-btn',
        function (e) {
            e.preventDefault();
            // Get image thumb magnified holder
            let thumbMagnifiedHolder = jQuery(
                '.irantheme-media-gallery-thumb-magnification',
            );
            thumbMagnifiedHolder.find('img').attr('src', '');
            jQuery(thumbMagnifiedHolder.find('video'))[0].pause();
            thumbMagnifiedHolder
                .find('video')
                .children('source')
                .attr('src', '');
            thumbMagnifiedHolder.fadeOut('fast');
        },
    );
}

/*===============================================================
Delete Gallery media mono item
=============================================================== */

function mediaGalleryMonoDelete() {
    jQuery('.irantheme-settings').on(
        'click',
        '.irantheme-media-list-item-thumb .irantheme-media-list-item-thumb-toolbox .irantheme-media-list-item-thumb-toolbox-trash',
        function (e) {
            e.preventDefault();
            // Get element selected for delete
            let mediaItemSelected = jQuery(this).parents(
                '.irantheme-media-list-item',
            );
            // Get all media item
            let allMediaItem = jQuery(
                '.irantheme-settings .irantheme-media-list-item:not(.irantheme-media-list-item-video-template,.irantheme-media-list-item-image-template)',
            );

            let resultArrayAfterRemove = allMediaItem;
            // Checking the presence of the selected item in the items array
            allMediaItem.each(function (index, elem) {
                // Checks if the current item of the array is the same as the selected item
                let isEqual = jQuery(elem).is(mediaItemSelected);
                if (isEqual) {
                    // Delete the same item from array
                    resultArrayAfterRemove.splice(index, 1);
                }
            });
            // New value after delete media from media gallery
            let newDataFieldValue = '';
            // Assign new data value after remove item selected
            jQuery(resultArrayAfterRemove).each(function (index, element) {
                newDataFieldValue +=
                    jQuery(element)
                        .children('.irantheme-media-list-item-thumb')
                        .attr('data-file-url') + ',';
            });
            // Get data_field input
            let dataField = jQuery(this)
                .parents('.container-media-preview')
                .siblings('.irantheme_media_gallery_data_field');
            // Remove last character ','
            newDataFieldValue = newDataFieldValue.replace(/,{1}$/, '');
            // Hide deleted media
            mediaItemSelected.fadeOut(250);
            // Assign new value after delete media to data_field input
            dataField.val(newDataFieldValue);
            // Show label
            setTimeout(() => {
                if (newDataFieldValue == '') {
                    mediaItemSelected
                        .siblings('.irantheme-media-list-label')
                        .removeClass('irantheme-media-selected');
                }
            }, 251);
            // Remove element of media deleted
            setTimeout(() => {
                mediaItemSelected.remove();
            }, 350);
        },
    );
}

/* ===============================================================
Active first toggle holder options
=============================================================== */
function activeFirstToggleHolderOptions() {
    let firstSection = jQuery(
        '.irantheme-settings-visual-panel-ajax .irantheme-settings-options .irantheme-settings-options-wrapper .irantheme-settings-options-wrapper-toggle',
    ).eq(0);
    jQuery(firstSection).addClass('active');
    jQuery(firstSection)
        .siblings('.irantheme-settings-options-wrapper-holder')
        .show();
}

/* ===============================================================
Toggle holder options
=============================================================== */
function toggleHolderOptions() {
    jQuery(
        '.irantheme-settings-visual-panel-ajax .irantheme-settings-options .irantheme-settings-options-wrapper .irantheme-settings-options-wrapper-toggle',
    ).on('click', function (e) {
        // Organization event target
        let target = null;
        if (
            jQuery(e.target).hasClass(
                'irantheme-settings-options-wrapper-toggle',
            )
        ) {
            target = jQuery(e.target);
        } else {
            target = jQuery(e.target).parents(
                '.irantheme-settings-options-wrapper-toggle',
            );
        }
        if (!jQuery(target).hasClass('active')) {
            // Close all holders
            jQuery(
                '.irantheme-settings-visual-panel-ajax .irantheme-settings-options .irantheme-settings-options-wrapper .irantheme-settings-options-wrapper-toggle',
            ).each(function (index, element) {
                jQuery(this).removeClass('active');
                jQuery(this)
                    .siblings('.irantheme-settings-options-wrapper-holder')
                    .hide();
            });
        }
        // Toggle current holder
        jQuery(this).toggleClass('active');
        if (jQuery(this).hasClass('active')) {
            jQuery(this)
                .siblings('.irantheme-settings-options-wrapper-holder')
                .fadeIn();

            // Temporary fix radio base dependencies with checkbox dependencies in ajax mode
            let holderWrapper = jQuery(this).siblings(
                '.irantheme-settings-options-wrapper-holder',
            );
            // Check checkboxes dependencies ids show/hide
            jQuery(holderWrapper)
                .find(
                    '.checkbox-input[data-base-priority]:not(.base-radio-child)',
                )
                .each(function (index, element) {
                    // Set checkboxes dependencies (Checkboxes and elements -> Nested)
                    setCheckboxDependencies(element);
                });
        } else {
            jQuery(this)
                .siblings('.irantheme-settings-options-wrapper-holder')
                .hide();
        }
    });
}

// Create a new tip
function createTip(type, message, millisecond = 5000) {
    let classType = '';
    // Holder of icon tag
    let iconTag = '';
    // Check type of tip
    switch (type) {
        // Success type
        case 'success':
            classType = 'irantheme-tip-success';
            iconTag =
                '<i class="fa-light fa-badge-check irantheme-tip-icon"></i>';
            break;
        // Info type
        case 'info':
            classType = 'irantheme-tip-info';
            iconTag =
                '<i class="fa-light fa-seal-question irantheme-tip-icon"></i>';
            break;
        // Warning type
        case 'warning':
            classType = 'irantheme-tip-warning';
            iconTag =
                '<i class="fa-light fa-badge-percent irantheme-tip-icon"></i>';
            break;
        // Error type
        case 'error':
            classType = 'irantheme-tip-error';
            iconTag =
                '<i class="fa-light fa-seal-exclamation irantheme-tip-icon"></i>';
            break;
    }
    // Holder of new tip element
    let newTipElement = `
        <div class="irantheme-tip ${classType}" style="display:none;">
            <!-- Icon -->
            ${iconTag}
            <!-- Note -->
            <p class="irantheme-tip-note">${message}</p>
            <div class="irantheme-tip-timer">
                <!-- Timer -->
                <div class="irantheme-tip-timer-counter" data-minute="0.08"></div>
            </div>
        </div>
        `;

    // Append new tip created to holder of tips
    jQuery(newTipElement)
        .appendTo(jQuery('.irantheme-tips-holder'))
        .slideDown(500);
    // Init timer of counter added
    initTimer(millisecond);
    // Hide tip after delay
    hideTipsAndDestroy(millisecond);
}

// Init timer
function initTimer(millisecond) {
    // Get all new created tips counters
    let allTips = jQuery('.irantheme-tips-holder').find(
        '.irantheme-tip .irantheme-tip-timer .irantheme-tip-timer-counter:not(.irantheme-tip-init)',
    );
    // add init class to tip for prevent to twice initialization
    allTips.addClass('irantheme-tip-init');

    // Animate each timer counter
    allTips.each((index, element) => {
        const $counter = jQuery(element);
        const startTime = Date.now();
        const endTime = startTime + millisecond;

        // Set initial width to 100%
        $counter.css('width', '100%');

        // Function to update width based on remaining time
        const updateWidth = () => {
            const now = Date.now();
            const remaining = Math.max(0, endTime - now);
            const percentage = (remaining / millisecond) * 100;

            $counter.css('width', `${percentage}%`);

            if (remaining > 0) {
                requestAnimationFrame(updateWidth);
            }
        };

        // Start the animation
        requestAnimationFrame(updateWidth);
    });
}

// Hide tips and destroy (Remove tag)
function hideTipsAndDestroy(millisecond) {
    // Get all new created tips counters
    let allTips = jQuery('.irantheme-tips-holder').find(
        '.irantheme-tip:not(.irantheme-tip-init-hide)',
    );
    allTips.addClass('.irantheme-tip-init-hide');
    jQuery(allTips).each(function (index, element) {
        setTimeout(() => {
            jQuery(element).slideUp(250);
        }, millisecond);
        setTimeout(() => {
            jQuery(element).remove();
        }, millisecond + 500);
    });
}

/*===============================================================
Init settings options
=============================================================== */
function initSettingsOptions() {
    // Init color picker
    initColorPicker();
    // Init media uploader
    initMediaUploader();
    // Init range inputs
    initRangeInputs();
    // Init multi checkboxes
    initMultiCheckboxButtons();
    // Init checkboxes
    initCheckboxButtons();
    // Init select box inputs
    initSelectBoxInputs();
    // Init multi select box inputs
    initMultiSelectBox();
    // Init checkbox dependencies
    initCheckboxDependencies();
    // Init radio buttons
    initRadioButtons();
    // Init extra settings
    extraSettings();
}
