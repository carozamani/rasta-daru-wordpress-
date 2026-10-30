//  Icons selector
jQuery(function (jQuery) {
    /* ===============================================================
    Type settings panel
    =============================================================== */
    // Get type settings data
    let typeSettings = jQuery('.irantheme-settings[data-settings]').attr(
        'data-settings',
    );

    // Check type settings
    if (
        typeSettings == 'options' ||
        typeSettings == 'visual' ||
        typeSettings == 'customFields'
    ) {
        // Add class name to box icon
        addClassIconToBox();
        // Field icon selector input
        let fieldIconSelector = null;
        /* ===============================================================
        Open icons selectors
        =============================================================== */
        jQuery('body').on(
            'click',
            '.irantheme-icons-selector-add',
            function (e) {
                e.preventDefault();
                // Open icons selector
                jQuery('.irantheme-icons-selector').fadeIn(300);
                // Init field icon selector
                fieldIconSelector = jQuery(this)
                    .parent()
                    .siblings('.irantheme-icons-selector-field');
                // Remove all class from elements
                jQuery(
                    '.irantheme-icons-selector-elements .irantheme-icons-selector-elements-list .irantheme-icons-selector-elements-list-box',
                ).removeClass('active');
                // Get second index of classes array
                let classesField = fieldIconSelector.val();
                classesField = classesField.split(' ')[1];
                // Active current icon on icons selector
                jQuery(
                    '.irantheme-icons-selector-elements .irantheme-icons-selector-elements-list .irantheme-icons-selector-elements-list-box i.' +
                        classesField,
                )
                    .parent()
                    .addClass('active');
            },
        );
        /* ===============================================================
        Delete icons selectors
        =============================================================== */
        jQuery('body').on(
            'click',
            '.irantheme-icons-selector-delete',
            function (e) {
                // Prevent to submit form
                e.preventDefault();
                // Init field icon selector
                fieldIconSelector = jQuery(this)
                    .parent()
                    .siblings('.irantheme-icons-selector-field');
                // Empty icons selector field (That is hidden)
                jQuery(fieldIconSelector).val('');
                // Remove all class from elements
                jQuery(
                    '.irantheme-icons-selector-elements .irantheme-icons-selector-elements-list .irantheme-icons-selector-elements-list-box',
                ).removeClass('active');
                // Remove all classes
                jQuery(fieldIconSelector)
                    .parent()
                    .siblings('.irantheme-icons-selector-preview')
                    .removeClass('active');
            },
        );
        /* ===============================================================
        Close icons selectors
        =============================================================== */
        jQuery('.irantheme-icons-selector-close').on('click', function (e) {
            // Close icons selector
            e.preventDefault();
            jQuery('.irantheme-icons-selector').fadeOut(300);
        });
        /* ===============================================================
        Search form
        =============================================================== */
        jQuery('.irantheme-icons-selector-search-form-input').on(
            'focus',
            function () {
                jQuery(this)
                    .parents('.irantheme-icons-selector-search-form')
                    .addClass('active');
            },
        );
        // Remove class active on focus
        jQuery('.irantheme-icons-selector-search-form-input').on(
            'blur',
            function () {
                jQuery(this)
                    .parents('.irantheme-icons-selector-search-form')
                    .removeClass('active');
            },
        );
        // Search from keywords
        jQuery('.irantheme-icons-selector-search-form-input').on(
            'input',
            function () {
                // Get input value
                let searchInputVal = jQuery.trim(jQuery(this).val());

                // Get all elements icons
                let = elementsIcons = jQuery(
                    '.irantheme-icons-selector-elements .irantheme-icons-selector-elements-list .irantheme-icons-selector-elements-list-box',
                );
                // Hide all box icons
                jQuery(elementsIcons).hide();
                // Each from all li box element icon
                jQuery(elementsIcons).each(function (index, boxIcon) {
                    // Get keyword icon
                    let keyword = jQuery(boxIcon).data('keywords');
                    // Check has keyword attr
                    if (typeof keyword !== 'undefined') {
                        // Check keyword items and convert to array
                        if (String(keyword).indexOf(',') != -1) {
                            // Split keys
                            keyword = keyword.split(',');
                            // Loop from array keyword
                            for (let i = 0; i < keyword.length; i++) {
                                // Check search input match with keyword
                                if (keyword[i].indexOf(searchInputVal) > -1) {
                                    // Show current box icon (element)
                                    jQuery(boxIcon).show();
                                }
                            }
                        } else {
                            // Check search input match with keyword
                            if (String(keyword).indexOf(searchInputVal) > -1) {
                                // Show current box icon (element)
                                jQuery(boxIcon).show();
                            }
                        }
                    }
                });
                // Check input val for display box icon
                if (searchInputVal == '') {
                    // Show all box icons
                    jQuery(elementsIcons).show();
                }
            },
        );
        /* ===============================================================
        Click on icon element (Action)
        =============================================================== */
        jQuery(
            '.irantheme-icons-selector-elements .irantheme-icons-selector-elements-list .irantheme-icons-selector-elements-list-box',
        ).on('click', function () {
            if (jQuery(this).hasClass('active')) {
                // Empty icons selector field (That is hidden)
                jQuery(fieldIconSelector).val('');
                // Remove all class from elements
                jQuery(
                    '.irantheme-icons-selector-elements .irantheme-icons-selector-elements-list .irantheme-icons-selector-elements-list-box',
                ).removeClass('active');
                // Remove all classes
                jQuery(fieldIconSelector)
                    .parent()
                    .siblings('.irantheme-icons-selector-preview')
                    .removeClass('active');
            } else {
                // Empty icons selector field (That is hidden)
                jQuery(fieldIconSelector).val('');
                // Get classes from current elements
                let fieldClasses = jQuery(this).find('i').attr('class');
                // Add classes to icon selector field (That is hidden)
                jQuery(fieldIconSelector).val(fieldClasses);
                // Remove all class from elements
                jQuery(
                    '.irantheme-icons-selector-elements .irantheme-icons-selector-elements-list .irantheme-icons-selector-elements-list-box',
                ).removeClass('active');
                // Add toggle class to current element
                jQuery(this).addClass('active');
                // Add current element classes
                jQuery(fieldIconSelector)
                    .parent()
                    .siblings('.irantheme-icons-selector-preview')
                    .find('i')
                    .attr('class', fieldIconSelector.val());
                // Show preview icon
                jQuery(fieldIconSelector)
                    .parent()
                    .siblings('.irantheme-icons-selector-preview')
                    .addClass('active');
            }
        });

        /* ===============================================================
        Init necessary function on click drop down nav menu for custom elements
        =============================================================== */
        jQuery('.add-to-menu').on('click', function (e) {
            // e.preventDefault();
            setTimeout(() => {
                initMediaUploader();
                initSelectBoxInputs();
                initRadioButtons();
                initCheckboxButtons();
            }, 1000);
        });
    }
});

function removeItemOnce(arr, value) {
    var index = arr.indexOf(value);
    if (index > -1) {
        arr.splice(index, 1);
    }
    return arr;
}

// Add class icon to box icon
function addClassIconToBox() {
    // Get classes
    jQuery(
        '.irantheme-icons-selector-elements .irantheme-icons-selector-elements-list .irantheme-icons-selector-elements-list-box',
    ).each(function (index, element) {
        let classes = jQuery(element).find('i').attr('class');
        // Convert to array with among space
        classes = classes.split(' ');
        classes = removeItemOnce(classes, 'fa-brands');
        classes = removeItemOnce(classes, 'fa-solid');
        classes = removeItemOnce(classes, 'fa-regular');
        classes = removeItemOnce(classes, 'fa-light');
        // Remove extra name
        classes = classes[0].replace('fa-', '');
        // Add to box icon
        jQuery(element)
            .find('.irantheme-icons-selector-elements-list-box-name')
            .text(classes);
    });
}
