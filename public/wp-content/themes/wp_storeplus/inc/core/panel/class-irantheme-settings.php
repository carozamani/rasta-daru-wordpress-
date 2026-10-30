<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;


/**
 * Them Storeplus theme settings class
 * A customizable class and settings
 * 
 * @package Wordpress
 * @subpackage Storeplus
 * @since v1.0
 * 
 * This class is a parent custom settings theme for admin page.
 */

if (!class_exists('Irantheme_Settings')) {
    class Irantheme_Settings
    {
        // Page title
        public $page_title;
        // Menu title
        public $menu_title;
        // Capability
        public $capability;
        // Menu slug
        public $menu_slug;
        // Text domain
        public static $text_domain = IRANTHEME_TEXTDOMAIN;
        // Icon menu item
        public $icon;
        // Position of menu
        private $position;
        // Verification
        public $verify;

        // Constructor Initialization
        public function __construct($menu_title, $capability, $icon, $position, $verify)
        {
            // Assign page title settings
            $this->page_title = $menu_title;
            // Assign title to main menu settings page
            $this->menu_title = $menu_title;
            // Assign capability
            $this->capability = $capability;
            // Assign menu slug (Settings status slug is default)
            $this->menu_slug = Irantheme_Settings_Status::get_menu_slug();
            // Assign icon menu
            $this->icon = $icon;
            // Assign position menu
            $this->position = $position;
            // Assign verification
            $this->verify = $verify;

            // Define main menu in wp admin
            add_action('admin_menu', array($this, 'add_menu_page'));
        }

        // Add parent menu
        public function add_menu_page()
        {
            add_menu_page(__($this->page_title, self::$text_domain), __($this->menu_title, self::$text_domain), __($this->capability, self::$text_domain), __($this->menu_slug, self::$text_domain), false, $this->icon, $this->position);
        }

        /**
         * Update default option
         * @return string
         * @param void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function init_update_option($register_name, $default_value)
        {
            // Set default option value
            $option_value = get_option($register_name);
            if ((!$option_value || $option_value == null) && $option_value !== '' && $option_value != '0' && isset($default_value)) {
                update_option($register_name, $default_value);
            }
        }

        /**
         * Generate HTML for displaying fields
         * @param array field array
         * @param string field name
         * @param string field value
         * @return string
         */
        public static function display_fields($field_array, $field_name, $field_value)
        {
            // Guard: ensure the field definition is a valid array
            if (!is_array($field_array) || empty($field_array['id'])) {
                return '';
            }

            // Holder html elements
            $html = '';

            // Initialize $option to empty string so it's always defined.
            // Later switch cases may override this with $field_array['default'] if needed.
            $option = '';

            if ($field_value || $field_value === '0') {
                $option = $field_value;
            }

            // Get option value raw
            $data_raw = $option;
            // Holder of data
            $data = $option;

            // Get keywords
            $keywords = '';
            // Check is set keywords
            if (!empty($field_array['keywords']) && is_array($field_array['keywords'])) {
                // Concat keys with separator
                foreach ($field_array['keywords'] as $key) {
                    $keywords .= $key . '-';
                }
            }

            switch ($field_array['type']) {

                case 'text':
                case 'password':
                case 'date':
                case 'time':
                    $max_length_attr = '';
                    if (isset($field_array['max_length']) && $field_array['max_length']) {
                        $max_length_attr = sprintf('maxlength="%s"', $field_array['max_length']);
                    }

                    // Add custom class if provided
                    $custom_class = $field_array['custom_class'] ?? '';
                    $placeholder = $field_array['placeholder'] ?? '';

                    $html .= '<div class="irantheme-keywords-field ' . $custom_class . '" data-keywords="' . $keywords . '">';
                    $html .= '<input id="' . esc_attr($field_name) . '" class="default-input-value" type="' . $field_array['type'] . '" name="' . esc_attr($field_name) . '" data-default="' . $field_array['default'] . '" placeholder="' . esc_attr($placeholder) . '" value="' . $data . '" ' . $max_length_attr . ' />' . "\n";
                    $html .= '</div>';
                    break;

                case 'number':
                    // Add default value to data result if option have not value
                    if (isset($field_array['default']) && $option == false) {
                        // Assign default value
                        $data = $field_array['default'];
                    }
                    // Fix bug in zero range comparison
                    if ($option === '0') {
                        $data = $option;
                    }

                    // Add custom class if provided
                    $custom_class = $field_array['custom_class'] ?? '';

                    // Set max, min, and step attributes with defaults if not provided
                    $field_max = $field_array['max'] ?? '';
                    $field_min = $field_array['min'] ?? '';
                    $field_step = $field_array['step'] ?? '';

                    $placeholder = $field_array['placeholder'] ?? '';

                    $html .= '<div class="irantheme-keywords-field ' . $custom_class . '" data-keywords="' . $keywords . '">';
                    $html .= '<input id="' . esc_attr($field_name) . '" class="default-input-value" type="' . $field_array['type'] . '" name="' . esc_attr($field_name) . '" max="' . esc_attr($field_max) . '"  min="' . esc_attr($field_min) . '" step="' . esc_attr($field_step) . '" data-default="' . $field_array['default'] . '" placeholder="' . esc_attr($placeholder) . '" value="' . $data . '"/>' . "\n";
                    $html .= '</div>';
                    break;

                case 'text_secret':
                    $placeholder = $field_array['placeholder'] ?? '';
                    $html .= '<div class="irantheme-keywords-field" data-keywords="' . $keywords . '">';
                    $html .= '<input id="' . esc_attr($field_name) . '" class="default-input-value" type="text" name="' . esc_attr($field_name) . '" data-default="' . $field_array['default'] . '" placeholder="' . esc_attr($placeholder) . '" value=""/>' . "\n";
                    $html .= '</div>';
                    break;

                case 'textarea':
                    // Add custom class if provided
                    $custom_class = $field_array['custom_class'] ?? '';
                    $placeholder = $field_array['placeholder'] ?? '';

                    // Direction css
                    $direction_css = '';
                    if (isset($field_array['direction']) && $field_array['direction'] == 'ltr') {
                        $direction_css = 'direction:ltr;';
                    }
                    $html .= '<div class="irantheme-keywords-field ' . $custom_class . '" data-keywords="' . $keywords . '">';
                    $html .= '<textarea id="' . esc_attr($field_name) . '" class="default-textarea" rows="5" cols="50" name="' . esc_attr($field_name) . '" data-default="' . $field_array['default'] . '" placeholder="' . esc_attr($placeholder) . '" style="' . $direction_css . '">' . $data . '</textarea><br/>' . "\n";
                    $html .= '</div>';
                    break;

                case 'range':
                    // Add default value to data result if option have not value
                    if (isset($field_array['default']) && $option == false) {
                        // Assign default value
                        $data = $field_array['default'];
                    }
                    // Fix bug in zero range comparison
                    if ($option === '0') {
                        $data = $option;
                    }

                    // Add custom class if provided
                    $custom_class = $field_array['custom_class'] ?? '';

                    $html .= '<div class="irantheme-keywords-field ' . $custom_class . '" data-keywords="' . $keywords . '">';
                    $html .= '<div class="range-input-holder">';
                    $html .= '<input style="background-size: ' . $data . '% 100%;" id="' . esc_attr($field_name) . '" class="input-range-field default-input-range" type="' . $field_array['type'] . '" name="' . esc_attr($field_name) . '" min="' . esc_attr($field_array['min']) . '" max="' . esc_attr($field_array['max']) . '" data-default="' . $field_array['default'] . '" value="' . $data . '" step="' . esc_attr($field_array['step']) . '" oninput="output_range_' . esc_attr($field_name) . '.value=this.value" />' . "\n";
                    $html .= '<output id="output_range_' . esc_attr($field_name) . '">' . $data . '</output>';
                    $html .= '</div>';
                    $html .= '</div>';
                    break;

                case 'checkbox':
                    // Add custom class if provided
                    $custom_class = $field_array['custom_class'] ?? '';

                    // Determine if the checkbox is checked
                    $checked = ($option === false || $option === null) ? ($field_array['default'] === 'on') : ($option === 'on');

                    // Prepare dependencies and attributes
                    $id_dependents_on = !empty($field_array['id_dependents_on']) ? implode(',', $field_array['id_dependents_on']) : '';
                    $id_dependents_off = !empty($field_array['id_dependents_off']) ? implode(',', $field_array['id_dependents_off']) : '';

                    $data_base_priority_attr = '';
                    if (isset($field_array['base_priority']) && $field_array['base_priority']) {
                        $data_base_priority_attr = $field_array['base_priority'] ? 'data-base-priority="' . esc_attr($field_array['base_priority']) . '"' : '';
                    }

                    $data_selector_priority_attr = '';
                    if (isset($field_array['selector_priority'])) {
                        $data_selector_priority_attr = $field_array['selector_priority'] ? 'data-selector-priority="' . esc_attr($field_array['selector_priority']) . '"' : '';
                    }

                    $data_z_index_attr = isset($field_array['z_index']) ? 'data-z-index="' . esc_attr($field_array['z_index']) . '"' : '';
                    $button_is_dependent_class = (!$id_dependents_on && !$id_dependents_off) ? 'irantheme-not-dependent' : '';
                    $fix_data = $checked ? 'on' : 'off';

                    // Start building the HTML
                    $html = '<div class="irantheme-keywords-field ' . esc_attr($custom_class) . '" data-keywords="' . esc_attr($keywords) . '">';

                    // Add button group
                    $html .= '<div class="button-side-group">';
                    $html .= '<span class="button-side button-side-checkbox button-side-checkbox-on ' . esc_attr($button_is_dependent_class) . '">روشن</span>';
                    $html .= '<span class="button-side button-side-checkbox button-side-checkbox-off ' . esc_attr($button_is_dependent_class) . '">خاموش</span>';
                    $html .= '</div>';

                    // Temporary fix radio base dependencies with checkbox dependencies in ajax mode
                    $base_radio_class = '';
                    if (isset($field_array['child_of_radio']) && $field_array['child_of_radio'] == true) {
                        $base_radio_class = 'base-radio-child';
                    }

                    // Add hidden input field
                    $html .= '<input id="' . esc_attr($field_name) . '" class="checkbox-input default-input-checkbox ' . $base_radio_class . '" type="hidden" name="' . esc_attr($field_name) . '"';
                    $html .= ' data-default="' . esc_attr($field_array['default']) . '"';
                    $html .= ' data-dependents-on="' . esc_attr($id_dependents_on) . '"';
                    $html .= ' data-dependents-off="' . esc_attr($id_dependents_off) . '"';
                    $html .= ' ' . $data_base_priority_attr;
                    $html .= ' ' . $data_selector_priority_attr;
                    $html .= ' ' . $data_z_index_attr;
                    $html .= ' value="' . esc_attr($fix_data) . '" />';

                    // Close the container div
                    $html .= '</div>';
                    break;

                case 'checkbox_multi':
                    // Add custom class if provided
                    $custom_class = $field_array['custom_class'] ?? '';

                    $html .= '<div  id="' . esc_attr($field_name) . '" class="irantheme-keywords-field ' . $custom_class . '" data-keywords="' . $keywords . '">';
                    $html .= '<div class="button-side-group">';

                    // Evolution field value option, have value or default assignment
                    $elements = null;
                    if ($option === '') {
                        $elements = $option;
                    } else {
                        $elements = $data;
                    }

                    $elements = explode(',', $elements);
                    $item_checked = array();
                    // Generation fields options
                    foreach ($field_array['options'] as $key => $value) {
                        $checked = false;
                        if (is_array($elements) && $elements != array('')) {
                            if (in_array($key, $elements)) {
                                $checked = true;
                                array_push($item_checked, $key);
                            }
                        }

                        $multicheckbox_class = '';
                        if ($checked == true) {
                            $multicheckbox_class = 'active';
                        }
                        $html .= '<span class="button-side button-side-checkbox-multi ' . $multicheckbox_class . '" data-value="' . $key . '">' . $value . '</span> ';
                    }
                    $html .= '<input id="' . esc_attr($field_name) . '" class="multi-checkbox-input default-input-checkbox-multi" type="hidden" name="' . esc_attr($field_name) . '" data-default="' . $field_array['default'] . '" value="' . implode(',', $item_checked) . '" /> ';
                    $html .= '</div>';
                    $html .= '</div>';
                    break;

                case 'radio':
                    // Add default value to data result if option has no value
                    if (isset($field_array['default']) && ($option === false || $option === null)) {
                        $data = $field_array['default']; // Assign default value
                    } else {
                        $data = $option; // Use the saved option value
                    }

                    // Add custom class if provided
                    $custom_class = $field_array['custom_class'] ?? '';

                    // Start building the HTML
                    $html .= '<div class="irantheme-keywords-field ' . esc_attr($custom_class) . '" data-keywords="' . esc_attr($keywords) . '">';
                    $html .= '<div class="button-side-group">';

                    $base_radio_class = '';
                    if (isset($field_array['base_priority']) && $field_array['base_priority'] == 'radio_base') {
                        $base_radio_class = 'radio-input-base';
                    }

                    // Loop through radio options
                    foreach ($field_array['options'] as $key => $value) {
                        $checked = ($key == $data); // Determine if the radio button is checked
                        $data_default = ($key == $field_array['default']) ? 'on' : 'off'; // Set default state

                        // Prepare dependencies for this specific radio option
                        $dependents_on = !empty($field_array['id_dependents_on'][$key]) ? implode(',', $field_array['id_dependents_on'][$key]) : '';
                        $dependents_off = !empty($field_array['id_dependents_off'][$key]) ? implode(',', $field_array['id_dependents_off'][$key]) : '';

                        if (isset($field_array['thumbnail_status']) && $field_array['thumbnail_status'] === true) {
                            $html .= '<span class="irantheme-settings-radio-thumbnail" id="' . esc_attr($field_name) . '">';
                            $html .= '<input class="radio-input ' . $base_radio_class . ' default-input-radio" type="radio" ' . checked($checked, true, false) . ' name="' . esc_attr($field_name) . '" data-default="' . esc_attr($data_default) . '" value="' . esc_attr($key) . '" id="' . esc_attr($field_name . '_' . $key) . '" data-dependents-on="' . esc_attr($dependents_on) . '" data-dependents-off="' . esc_attr($dependents_off) . '" />';
                            $html .= '<img src="' . IRANTHEME_URI . '/assets/images/options/' . esc_attr($value[1]) . '" width="80" height="80" alt="تصویر تنظیمات" />';
                            $html .= '<cite>' . esc_html($value[0]) . '</cite>';
                            $html .= '<i class="dashicons dashicons-yes irantheme-checked-icon"></i>';
                            $html .= '</span>';
                        } else {
                            $html .= '<span class="button-side button-side-radio" id="' . esc_attr($field_name) . '">';
                            $html .= '<input class="radio-input ' . $base_radio_class . ' default-input-radio" type="radio" ' . checked($checked, true, false) . ' name="' . esc_attr($field_name) . '" data-default="' . esc_attr($data_default) . '" value="' . esc_attr($key) . '" id="' . esc_attr($field_name . '_' . $key) . '" data-dependents-on="' . esc_attr($dependents_on) . '" data-dependents-off="' . esc_attr($dependents_off) . '" />';
                            $html .= esc_html($value);
                            $html .= '</span>';
                        }
                    }

                    // Close the container div
                    $html .= '</div>';
                    $html .= '</div>';
                    break;

                case 'select':
                    // Add default value to data result if option have not value
                    if (isset($field_array['default']) && $option == false) {
                        // Assign default value
                        $data = $field_array['default'];
                    }
                    // Fix bug in zero range comparison
                    if ($option === '0') {
                        $data = $option;
                    }
                    // Get default value (Text)
                    $selected_option_text = $field_array['default'];

                    // Get status of color preview
                    $color_preview = '';
                    if (isset($field_array['color_preview']) && $field_array['color_preview'] === true) {
                        $color_preview = $field_array['color_preview'];
                    }
                    // Holder color preview box
                    $color_preview_box = '';
                    // Holder color preview text
                    $color_preview_text = '';
                    $html .= '<div class="irantheme-keywords-field" data-keywords="' . $keywords . '">';
                    $html .= '<div class="select-box-holder irantheme-custom-select">' . "\n";
                    $html .= '<select name="' . esc_attr($field_name) . '" id="' . esc_attr($field_name) . '" class="irantheme-select-main">';
                    foreach ($field_array['options'] as $key => $value) {
                        // Check hr separator key
                        if ($value != 'hr') {
                            $selected = false;
                            if ($key == $data) {
                                $selected = true;
                                // Set selected text option
                                $selected_option_text = $value;
                                if ($color_preview && $key != 'off' && $key != 'default' && $key != 'transparent' && $key != 'dark' && $key != 'white') {
                                    $color_preview_box = '<span style="background:#' . Irantheme_Color_Static::get_hex_color($key) . ';float:left;width:1.5rem;height:1rem;border-radius:0.25rem;box-shadow:1px 1px 2px #ccc;"></span>';
                                    $color_preview_text = '<q style="font-size:0.8rem;color:#666;">( کد رنگ : ' . Irantheme_Color_Static::get_hex_color($key) . ' )</q>';
                                }
                            }
                            // Check data default
                            $data_default = 'off';
                            if ($key == $field_array['default']) {
                                $data_default = 'on';
                            }
                            $html .= '<option class="irantheme-select-main-option default-select-option" ' . selected($selected, true, false) . ' data-default="' . $data_default . '" value="' . esc_attr($key) . '">' . $value . '</option>';
                        }
                    }
                    $html .= '</select>' . "\n";
                    $html .= '<div class="irantheme-holder-selected-option"><strong class="irantheme-select-option-item-value">' . $selected_option_text . '</strong>' . $color_preview_text . ' ' . $color_preview_box . '</div>';
                    $html .= '<div class="irantheme-select-list-options" style="display: none;">';
                    // Generate div review of above options
                    foreach ($field_array['options'] as $key => $value) {
                        // Check hr separator key
                        if ($value != 'hr') {
                            // Check color preview status
                            if ($color_preview && $key != 'off' && $key != 'default' && $key != 'transparent' && $key != 'dark' && $key != 'white') {
                                $color_preview_box = '<span style="background:#' . Irantheme_Color_Static::get_hex_color($key) . ';float:left;width:1.5rem;height:1rem;border-radius:0.25rem;box-shadow:1px 1px 2px #ccc;"></span>';
                                $color_preview_text = '<q style="font-size:0.8rem;color:#666;">( کد رنگ : ' . Irantheme_Color_Static::get_hex_color($key) . ' )</q>';
                            }
                            // Option active class
                            $option_active_class = '';
                            if ($key == $data) {
                                // Set options active class
                                $option_active_class = 'irantheme-select-option-active';
                            }
                            if ($key != 'off' && $key != 'default' && $key != 'transparent' && $key != 'dark' && $key != 'white') {
                                $html .= '<div class="irantheme-select-option-item ' . $option_active_class . '"><strong class="irantheme-select-option-item-value" data-value="' . $key . '">' . $value . '</strong>' . $color_preview_text . ' ' . $color_preview_box . '</div>';
                            } else {
                                $html .= '<div class="irantheme-select-option-item ' . $option_active_class . '"><strong class="irantheme-select-option-item-value" data-value="' . $key . '">' . $value . '</strong></div>';
                            }
                        } else {
                            $html .= '<hr class="irantheme-select-hr" />';
                        }
                    }
                    $html .= '</div>';
                    $html .= '</div>';
                    $html .= '</div>';
                    break;

                case 'select_multi':
                    // Add default value to data result if option have not value
                    if (isset($field_array['default']) && $option == false) {
                        // Assign default value
                        $data = $field_array['default'];
                    }
                    // Fix bug in zero range comparison
                    if ($option === '0') {
                        $data = $option;
                    }

                    // Multi select ==========================================================================
                    $html .= '<div class="irantheme-keywords-field" data-keywords="' . $keywords . '">';
                    $html .= '<div class="irantheme-multiselect">';
                    // Data default holder
                    $data_default = '';
                    $data_ids_db = array();
                    $data_ids_raw = array();
                    // Prevent to empty array
                    if ($data) {
                        $data_ids_raw = explode(',', $data);
                    }
                    foreach ($data_ids_raw as $id) {
                        if ($id || $id === '0') {
                            array_push($data_ids_db, $id);
                        }
                    }

                    $placeholder = $field_array['placeholder'] ?? '';

                    // Input hidden (Holder of data from db)
                    $html .= '<input name="' . esc_attr($field_name) . '" id="' . esc_attr($field_name) . '" class="irantheme-multiselect-field default-select-multi-option" type="hidden" value="' . $data . '" data-default="' . $field_array['default'] . '" />';

                    $html .= '<ul class="irantheme-multiselect-list">';
                    // Counter
                    $counter_nothing_options = 0;
                    foreach ($field_array['options'] as $key => $value) {
                        $hideclass = "";
                        if (in_array($key, $data_ids_db)) {
                            $hideclass = " irantheme-multiselect-option-selected";
                            $counter_nothing_options++;
                        }
                        $html .= '<li class="irantheme-multiselect-option' . $hideclass . '" data-value="' . $key . '"><a class="irantheme-multiselect-text">' . $value . '</a></li>';
                    }
                    // Define status class for nothing options
                    $class_nothing_options = 'd-none';
                    if ($counter_nothing_options == count($field_array['options'])) {
                        $class_nothing_options = '';
                    }
                    $html .= '<h5 class="irantheme-multiselect-noselections ' . $class_nothing_options . '">موردی برای انتخاب موجود نیست !</h5>';
                    $html .= '</ul>';
                    $dropdown_class = '';
                    foreach ($field_array['options'] as $key => $value) {
                        if (in_array($key, $data_ids_db)) {
                            $dropdown_class = 'irantheme-dropdown-hasvalue';
                        }
                    }
                    $html .= '<div class="irantheme-multiselect-dropdown ' . $dropdown_class . '" data-multiple="true">';
                    // Holder multiselect temp options
                    $multiselect_temp_options = $field_array['options'];
                    // $data = array_unique($data);
                    foreach ($data_ids_db as $id) {
                        $class_option_selected = '';
                        $html .= '<span class="irantheme-multiselect-choice ' . $class_option_selected . '" data-value="' . $id . '">' . $multiselect_temp_options[$id] . '<i class="dashicons dashicons-no-alt"></i></span>';
                        unset($multiselect_temp_options[$id]);
                    }
                    foreach ($multiselect_temp_options as $key => $value) {
                        $class_option_selected = 'd-none';
                        $html .= '<span class="irantheme-multiselect-choice ' . $class_option_selected . '" data-value="' . $key . '">' . $value . '<i class="dashicons dashicons-no-alt"></i></span>';
                    }

                    $html .= '</div>';
                    $html .= '<span class="irantheme-multiselect-placeholder">' . esc_attr($placeholder) . '</span>';
                    $html .= '</div>';
                    $html .= '</div>';
                    break;

                case 'image':
                    // Image type ==========================================================================
                    $html .= '<div class="irantheme-keywords-field" data-keywords="' . $keywords . '">';
                    $data = $data_raw;
                    $image_thumb = '';
                    if (isset($field_array['default']) && $data !== '' && !$data) {
                        $data = $field_array['default'];
                        $data_raw = $field_array['default'];
                        $image_thumb = wp_get_attachment_image_src($data_raw, 'full')[0];
                    } else {
                        if ($data != $field_array['default']) {
                            $image_thumb = wp_get_attachment_image_src($data_raw, 'full')[0];
                        } else {
                            // $image_thumb = $field_array['default'];
                            $data = $field_array['default'];
                            $image_src = wp_get_attachment_image_src($data, 'full');
                            $image_thumb = !empty($image_src[0]) ? $image_src[0] : '';
                        }
                    }

                    $data_default_url = '';
                    if ($field_array['default']) {
                        $data_default_url = wp_get_attachment_image_src($field_array['default'], 'full')[0];
                    }

                    // Get name of image
                    $image_basename = 'فاقد تصویر';
                    if ($image_thumb) {
                        $image_info = pathinfo($image_thumb);
                        $image_basename = $image_info['basename'];
                    }

                    $display = ($image_thumb) ? '' : 'd-none';

                    // Add custom class if provided
                    $custom_class = $field_array['custom_class'] ?? '';

                    // Output
                    $html .= '<div class="container_image_preview ' . $display . ' ' . $custom_class . '">';
                    $html .= '<img id="' . $field_name . '_preview" class="image_preview" src="' . $image_thumb . '" /></div>';
                    $html .= '<div class="button-expand-group">';
                    $html .= '<button id="' . $field_name . '_button" type="button" data-uploader_title="' . __('Upload an image', self::$text_domain) . '" data-uploader_button_text="' . __('Use image', self::$text_domain) . '" class="image_upload_button irantheme-button-default irantheme-button-primary button-expand-inline irantheme-button-small">' . __('گزینش تصویر', self::$text_domain) . '</button>' . "\n";
                    $html .= '<button id="' . $field_name . '_delete" type="button" class="image_delete_button irantheme-button-default irantheme-button-dark button-expand-inline irantheme-button-small">' . __('حذف تصویر', self::$text_domain) . '</button>' . "\n";
                    $html .= '</div>';
                    $html .= '<input class="image_basename" type="text" value="' . $image_basename . '" disabled/>' . "\n";
                    $html .= '<input id="' . esc_attr($field_name) . '" class="irantheme_image_data_field default-input-image" type="hidden" name="' . $field_name . '" data-default="' . $field_array['default'] . '" data-default-text="' . $image_basename . '" data-default-url="' . $data_default_url . '" value="' . $data . '"/>' . "\n";
                    $html .= '<input class="holder_data_default" type="hidden" /><br/>' . "\n";
                    $html .= '</div>';
                    break;

                case 'media_gallery':
                    // Image type ==========================================================================
                    $html .= '<div class="irantheme-keywords-field" data-keywords="' . $keywords . '">';
                    $data = $data_raw;
                    if (isset($field_array['default']) && $data !== '' && !$data) {
                        $data = $field_array['default'];
                        $data_raw = $field_array['default'];
                    } else {
                        if ($data == $field_array['default']) {
                            $data = $field_array['default'];
                        }
                    }
                    $media_url = '';
                    $media_ext = '';

                    // Add custom class if provided
                    $custom_class = $field_array['custom_class'] ?? '';

                    $label_visibility_class = '';
                    if ($data != '') {
                        $label_visibility_class = 'irantheme-media-selected';
                    }
                    // Output
                    $html .= '<div class="container-media-preview ' . $custom_class . '">';
                    $html .= '<div id="' . $field_name . '_media_list" class="irantheme-media-list">';
                    $html .= '<div class="irantheme-media-list-label ' . $label_visibility_class . '"><cite>رسانه ای برای نمایش یافت نشد!</cite></div>';
                    $html .= '<div class="irantheme-media-list-item-image-template irantheme-media-list-item">';
                    $html .= '<div class="irantheme-media-list-item-thumb" data-file-type="image">';
                    $html .= '<img id="" class="irantheme-media-list-item-thumb-image" src="" />';
                    $html .= '<div class="irantheme-media-list-item-thumb-toolbox">';
                    $html .= '<span class="dashicons dashicons-fullscreen-alt irantheme-media-list-item-thumb-toolbox-zoom-in"></span>';
                    $html .= '<span class="dashicons dashicons-trash irantheme-media-list-item-thumb-toolbox-trash"></span>';
                    $html .= '</div>';
                    $html .= '</div>';
                    $html .= '</div>';
                    $html .= '<div class="irantheme-media-list-item-video-template irantheme-media-list-item">';
                    $html .= '<div class="irantheme-media-list-item-thumb"  data-file-type="video">';
                    $html .= '<video id="" class="irantheme-media-list-item-thumb-video" controls><source src="" /></video>';
                    $html .= '<div class="irantheme-media-list-item-thumb-toolbox">';
                    $html .= '<span class="dashicons dashicons-fullscreen-alt irantheme-media-list-item-thumb-toolbox-zoom-in"></span>';
                    $html .= '<span class="dashicons dashicons-trash irantheme-media-list-item-thumb-toolbox-trash"></span>';
                    $html .= '</div>';
                    $html .= '</div>';
                    $html .= '</div>';
                    // Get name of media
                    if ($data_raw) {
                        $array_media_url = explode(',', $data_raw);
                        if (!empty($array_media_url)) {
                            $index_id = 0;
                            foreach ($array_media_url as $media_url) {
                                if (!$media_url) {
                                    continue;
                                }
                                // Detect attachment file type
                                $media_ext
                                    = explode('/', wp_check_filetype($media_url)['type'])[0];
                                $html .= '<div class="irantheme-media-list-item">';
                                $html .= '<div class="irantheme-media-list-item-thumb"  data-file-type="' . $media_ext . '" data-media-id="' . $index_id . '" data-file-url="' . $media_url . '">';
                                if ($media_ext == 'image') {
                                    $html .= '<img id="' . $field_name . '_' . $index_id . '" class="irantheme-media-list-item-thumb-image" src="' . $media_url . '" />';
                                } elseif ($media_ext == 'video') {
                                    $html .= '<video id="' . $field_name . '_' . $index_id . '" class="irantheme-media-list-item-thumb-video" controls><source src="' . $media_url . '" /></video>';
                                }
                                $html .= '<div class="irantheme-media-list-item-thumb-toolbox">';
                                $html .= '<span class="dashicons dashicons-fullscreen-alt irantheme-media-list-item-thumb-toolbox-zoom-in"></span>';
                                $html .= '<span class="dashicons dashicons-trash irantheme-media-list-item-thumb-toolbox-trash"></span>';
                                $html .= '</div>';
                                $html .= '</div>';
                                $html .= '</div>';
                                $index_id++;
                            }
                        }
                    }
                    $html .= '</div>';
                    $html .= '</div>';
                    $html .= '<div class="button-expand-group">';
                    $html .= '<button id="' . $field_name . '_button" type="button" data-uploader_title="' . __('Upload an image', self::$text_domain) . '" data-uploader_button_text="' . __('Use image', self::$text_domain) . '" data-select-type="multiple" class="image_upload_button irantheme-button-default irantheme-button-primary button-expand-inline irantheme-button-small">' . __('گزینش رسانه', self::$text_domain) . '</button>' . "\n";
                    $html .= '<button id="' . $field_name . '_delete" type="button" class="image_delete_button irantheme-button-default irantheme-button-dark button-expand-inline irantheme-button-small">' . __('حذف همه', self::$text_domain) . '</button>' . "\n";
                    $html .= '</div>';
                    $html .= '<div class="irantheme-media-gallery-thumb-magnification">';
                    $html .= ' <button class="irantheme-media-gallery-thumb-magnification-close-btn" type="button"><span class="dashicons dashicons-no-alt"></span></button>';
                    $html .= '<figure class="irantheme-media-gallery-thumb-magnification-figure">';
                    $html .= '<img src="" alt="" class="irantheme-media-gallery-thumb-magnification-figure-img">';
                    $html .= '<video id="" class="irantheme-media-gallery-thumb-magnification-figure-video" controls><source src="" /></video>';
                    $html .= '</figure></div>';

                    $html .= '<input id="' . esc_attr($field_name) . '" class="irantheme_media_gallery_data_field default-input-media-gallery" type="hidden" name="' . $field_name . '" data-default="' . $field_array['default'] . '" value="' . $data . '"/>' . "\n";
                    $html .= '<input class="holder_data_default" type="hidden" /><br/>' . "\n";
                    break;

                case 'video':
                    $html .= '<div class="irantheme-keywords-field" data-keywords="' . $keywords . '">';
                    $data = $data_raw;
                    $video_thumb = '';
                    if (isset($field_array['default']) && $data !== '' && !$data) {
                        $video_thumb = wp_get_attachment_url($field_array['default']);
                        $data = $field_array['default'];
                    } else {
                        $video_thumb =
                            wp_get_attachment_url($data_raw);
                    }
                    // Get name of video
                    $video_info = pathinfo($video_thumb);
                    $video_basename = 'فاقد ویدئو';
                    if ($video_thumb) {
                        $video_info = pathinfo($video_thumb);
                        $video_basename = $video_info['basename'];
                    }
                    $display = ($video_thumb) ? '' : 'd-none';
                    // Output
                    $html .= '<div class="container_video_preview ' . $display . '">';
                    $html .= '<video id="' . $field_name . '_preview" class="video_preview" controls><source src="' . $video_thumb . '" /></video></div>';
                    $html .= '<br/>' . "\n";
                    $html .= '<div class="button-expand-group">';
                    $html .= '<button id="' . $field_name . '_button" type="button" data-uploader_title="' . __('Upload an video', self::$text_domain) . '" data-uploader_button_text="' . __('Use video', self::$text_domain) . '" class="video_upload_button irantheme-button-default irantheme-button-secondary button-expand-inline irantheme-button-small">' . __('گزینش ویدئو', self::$text_domain) . '</button>' . "\n";
                    $html .= '<button id="' . $field_name . '_delete" type="button" class="video_delete_button irantheme-button-default irantheme-button-dark button-expand-inline irantheme-button-small">' . __('حذف ویدئو', self::$text_domain) . '</button>' . "\n";
                    $html .= '</div>';
                    $html .= '<input class="video_basename" type="text" value="' . $video_basename . '" disabled/>' . "\n";
                    $html .= '<input id="' . esc_attr($field_name) . '" class="irantheme_video_data_field default-input-video" type="hidden" name="' . $field_name . '" data-default="' . $field_array['default'] . '" data-default-text="' . $video_basename . '" value="' . $data . '"/>' . "\n";
                    $html .= '<input class="holder_data_default" type="hidden" /><br/>' . "\n";
                    $html .= '</div>';
                    break;

                case 'color': ?>
                    <?php
                    // Add default value to data result if option have not value
                    if (isset($field_array['default']) && $option == false) {
                        // Assign default value
                        $data = $field_array['default'];
                    }
                    ?>
                    <div class="irantheme-keywords-field" data-keywords="<?php echo $keywords; ?>">
                        <!-- Color picker -->
                        <div class="irantheme-color-picker" style="position:relative;">
                            <!-- Input color -->
                            <input id="<?php echo esc_attr($field_name); ?>" class="irantheme-color-picker-input default-input-color-picker" type="text" name="<?php echo esc_attr($field_name); ?>" data-default="<?php echo $field_array['default']; ?>" value="<?php echo $data; ?>" />
                            <!-- Angle info -->
                            <span class="irantheme-color-picker-info"><i class="fa-light fa-angle-down"></i>Hex</span>
                            <!-- Color preview -->
                            <div class="irantheme-color-picker-preview">
                                <!-- Pallet -->
                                <span style="position:absolute;background:#FFF;z-index:99;border-radius:100%;" class="irantheme-color-picker-pallet"></span>
                            </div>
                        </div>
                    </div>
                <?php
                    break;

                case 'icon':
                ?>
                    <!-- Custom nav menus -->
                    <div class="irantheme-custom-nav-menus irantheme-font irantheme-keywords-field" data-keywords="<?php echo esc_attr($keywords); ?>">
                        <!-- Upload icon -->
                        <div class="irantheme-icons-selector-fields" style="clear:both;">
                            <!-- Preview icon -->
                            <div class="irantheme-icons-selector-preview <?php echo $data ? 'active' : '' ?>">
                                <i class="<?php echo esc_attr($data); ?>"></i>
                            </div>
                            <!-- ‌Button icon selector -->
                            <div class="irantheme-font irantheme-group-icon-selector">
                                <div class="button-expand-group">
                                    <!-- Open icons selector -->
                                    <input class="irantheme-button-default irantheme-button-tertiary button-expand-inline irantheme-button-small irantheme-icons-selector-add" type="button" value="انتخاب آیکون" data-icon="<?php echo esc_attr($data); ?>" />
                                    <button class="irantheme-button-default irantheme-button-dark button-expand-inline irantheme-button-small irantheme-icons-selector-delete">حذف آیکن</button>
                                </div>
                                <!-- Text input icon -->
                                <input type="hidden" name="<?php echo esc_attr($field_name); ?>" id="<?php echo esc_attr($field_name); ?>" class="irantheme-icons-selector-field default-input-icon" value="<?php echo esc_attr($data); ?>" style="width: 100%;" data-default="<?php echo esc_attr($field_array['default']); ?>">
                            </div>
                        </div>
                    </div>
                <?php
                    break;

                case 'text_editor':
                    // Add custom class if provided
                    $custom_class = $field_array['custom_class'] ?? '';
                ?>

                    <div class="irantheme-keywords-field <?php echo esc_attr($custom_class); ?>" data-keywords="<?php echo $keywords; ?>">
                        <?php
                        // Get data from option name, if this field have'nt value, data default assignment.
                        // Holder of data
                        $data = '';
                        // Check default value
                        if (isset($field_array['default'])) {
                            // Assign default value
                            $data = $field_array['default'];
                            // Check having value? if yes, replace default with value
                            if ($option || $option === '0' || $option === '') {
                                $data = $option;
                            }
                        }
                        // Check have media (Media buttons)
                        $media_status = false;
                        if (isset($field_array['media_status']) && $field_array['media_status'] == true) {
                            $media_status = true;
                        }
                        // Assign content
                        $content = $data;
                        $settings = array(
                            'wpautop' => true, // enable auto paragraph?
                            'media_buttons' => $media_status, // show media buttons?
                            'textarea_name' => $field_name, // id of the target textarea
                            'textarea_rows' => get_option('default_post_edit_rows', 10), // This is equivalent to rows="" in HTML
                            'tabindex' => '',
                            'editor_css' => '', //  additional styles for Visual and Text editor,
                            'editor_class' => 'irantheme-keywords-field default-textarea', // additional classes to be added to the editor
                            'teeny' => false, // show minimal editor
                            'dfw' => true, // replace the default fullscreen with DFW
                            'tinymce' => array(
                                // Items for the Visual Tab
                                'toolbar1' => 'formatselect,fontsizeselect,backcolor,forecolor,bold,italic,underline,strikethrough,blockquote,link,unlink,bullist,numlist,hr,alignleft,aligncenter,alignright,alignjustify,undo,redo',
                                'fontsize_formats' => '0.6em 0.8em 1em 1.2em 1.4em 1.6em 1.8em 2em 2.4em 2.8em 3.4em 4em 5em 6em 8em 10em',
                                'content_css' => IRANTHEME_URI . "/assets/fonts/tinymce-fonts.css",
                                'content_style' => "body { font-family: IRANSansX !important; }"
                            ),
                            // 'quicktags' => array(`
                            //     // Items for the Text Tab
                            //     'buttons' => 'strong,em,link,code,del,block,ins'
                            // ),
                            'quicktags' => true,
                            'editor_type' => 'tinymce',
                        );
                        ob_start();
                        wp_editor($content, $field_name, $settings);
                        $temp = ob_get_clean();
                        // print_head_scripts();
                        $temp .= \_WP_Editors::enqueue_scripts();
                        print_footer_scripts();
                        $temp .= \_WP_Editors::editor_js();
                        echo $temp;
                        ?>
                        <!-- Scripts -->
                        <script type="text/javascript">
                            jQuery(function($) {
                                var editorId = <?php echo wp_json_encode($field_name); ?>;
                                var defaultContent = <?php echo wp_json_encode($field_array['default'] ?? ''); ?>;

                                var $textarea = $('#' + editorId);

                                if (!$textarea.length) {
                                    return;
                                }

                                // Store the default content safely.
                                $textarea.attr('data-default', defaultContent);

                                // Restore the default content when resetting the section.
                                $(document)
                                    .off('click.iranthemeResetSection', '.irantheme-reset-section')
                                    .on('click.iranthemeResetSection', '.irantheme-reset-section', function() {
                                        var editor = window.tinymce ?
                                            tinymce.get(editorId) :
                                            null;

                                        if (editor && !editor.isHidden()) {
                                            editor.setContent(defaultContent);
                                        } else {
                                            $textarea.val(defaultContent);
                                        }
                                    });
                            });
                        </script>
                    </div>
                <?php
                    break;

                case 'custom_button':
                    // A custom button =======================================================
                    // Add custom class if provided
                    $custom_class = $field_array['custom_class'] ?? '';
                ?>
                    <div class="irantheme-keywords-field" data-keywords="<?php echo $keywords; ?>">
                        <div class="button-block-group">
                            <?php
                            // Get icons
                            $icon_tag = '';
                            if (isset($field_array['icon']) && $field_array['icon']) {
                                $icon_tag = '<span class="dashicons dashicons-' . $field_array['icon'] . '"></span>';
                            }
                            ?>
                            <!-- Button -->
                            <button class="irantheme-button-default irantheme-button-small irantheme-button-primary <?php echo esc_attr($custom_class); ?>" type="button"><?php _e($icon_tag); ?><?php echo esc_html($field_array['label']); ?></button>
                            <!-- Input hidden -->
                            <input id="<?php echo esc_attr($field_name); ?>" class="irantheme-data-hidden default-input-value" type="hidden" name="<?php echo esc_attr($field_name); ?>" data-default="<?php echo esc_attr($field_array['default']); ?>" value="<?php echo $data; ?>" />
                        </div>
                    </div>
            <?php
                    break;
            }

            $html .= '<span class="irantheme-settings-options-description">' . $field_array['description'] . '</span>' . "\n";
            if (isset($field_array['note']) && $field_array['note'] != '') {
                $html .= '<span class="irantheme-d-inline-block irantheme-settings-tip small">' . esc_html('نکته :‌ ' . $field_array['note']) . '</span>';
            }
            return $html;
        }


        // Get logo html (Svg)
        public static function get_logo_html()
        {
            ?>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1095 1095" width="1095" height="1095" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd; width: 2.65rem; height: auto;">
                <path d="M446.84 0.17C513.95 0.17 581.05 0.17 648.16 0.17C654.08 1.59 661.32 0.84 667.5 1.08C675.83 1.39 684.22 2.23 692.5 3.16C722.95 6.58 753.11 12.82 782.26 22.31C908.28 63.32 1011.73 160.1 1061.8 282.68C1075.22 315.55 1084.68 350.39 1089.78 385.51C1091.57 397.78 1092.88 410.14 1093.75 422.5C1094.12 427.84 1093.37 434.12 1094.83 439.12C1094.83 511.38 1094.83 583.64 1094.83 655.91C1093.27 661.2 1094.13 667.86 1093.69 673.5C1092.73 685.53 1091.56 697.55 1089.81 709.5C1084.66 744.62 1075.35 779.52 1061.85 812.39C1011.45 935.07 908.65 1031.6 782.4 1072.83C756.79 1081.19 730.2 1087.33 703.48 1090.76C693.85 1092 684.18 1092.98 674.5 1093.73C669.45 1094.12 663.65 1093.47 658.89 1094.83C584.61 1094.83 510.33 1094.83 436.05 1094.83C431.01 1093.37 424.88 1094.11 419.56 1093.64C407.85 1092.61 396.16 1091.5 384.52 1089.76C349.44 1084.52 314.69 1075.08 281.85 1061.6C159.78 1011.46 62.86 908.15 22.22 782.36C13.74 756.11 7.4 728.9 4.1 701.5C2.94 691.86 1.89 682.19 1.23 672.5C0.84 666.82 1.5 660.54 0.17 655.07C0.17 583.66 0.17 512.25 0.17 440.84C1.48 435.43 0.85 429.13 1.19 423.5C1.78 413.48 2.92 403.46 4.13 393.5C7.48 366.13 13.73 338.9 22.26 312.69C63.44 186.18 164.21 76.59 289.9 30.45C323.51 18.11 357.92 8.69 393.51 4.2C404.44 2.83 415.49 1.61 426.5 1.12C433 0.83 440.59 1.66 446.84 0.17ZM441.5 203.54C417.57 202.66 393.45 203.47 369.5 203.47C362.5 203.47 355.5 203.47 348.5 203.47C345.86 203.47 341.65 202.65 339.19 203.66C337.11 204.51 335.45 207.94 334.18 209.7C330.69 214.53 327.31 219.46 323.83 224.3C311.29 241.78 298.93 259.38 286.31 276.8C271.05 297.88 256.26 319.32 240.92 340.35C229.56 355.91 214.16 374.42 206.13 391.63C191.17 423.66 194.67 462.92 216.46 491.08C223.18 499.76 230.98 507.27 240.09 513.44C284.09 543.22 347.05 529.64 375.18 484.68C388.69 463.09 391.57 436.9 396.86 412.5C405.79 371.3 414.79 330.18 423.51 288.95C427.68 269.16 431.97 249.42 436.35 229.66C438.07 221.93 441.93 211.16 441.5 203.54ZM653.5 203.61C652.99 211.53 657.74 225.47 659.57 233.78C663.69 252.39 667.61 271.02 671.66 289.63C680.58 330.58 689.57 371.48 698.16 412.5C702.82 434.75 706.12 461.09 717.59 480.91C744.32 527.08 806.09 543.86 851.87 515.39C861.71 509.27 870.6 501.39 877.67 492.19C899.83 463.34 904.17 424.68 888.88 391.62C881.03 374.63 867.23 358.44 856.18 343.3C840.12 321.3 824.62 298.88 808.68 276.8C796.09 259.36 783.64 241.82 771.17 224.3C767.71 219.44 764.29 214.54 760.82 209.69C759.55 207.93 757.9 204.5 755.81 203.65C753.34 202.65 749.15 203.47 746.5 203.47C739.5 203.47 732.5 203.47 725.5 203.47C702.05 203.47 676.72 202.04 653.5 203.61ZM486.5 243.33C478.8 285.45 470.57 327.55 462 369.49C460.05 379.03 458.46 388.66 456.58 398.24C453.51 413.93 449.86 429.3 452.12 445.47C458.43 490.56 497.38 528.02 543.52 529.59C556.59 530.03 569.65 528.03 581.87 523.41C591.88 519.63 600.88 514.35 609.16 507.64C630.22 490.58 643.5 464.59 644.91 437.5C645.7 422.31 641.43 407.77 638.51 393.05C633.7 368.82 628.83 344.7 623.95 320.48C620.84 305.04 617.55 289.69 614.42 274.26C612.66 265.6 611.66 250.87 607.81 243.5C588.04 262.67 568.27 281.84 548.5 301.01C527.83 281.79 507.17 262.56 486.5 243.33ZM289.74 595.33C266.48 599.54 246.23 617.39 242.84 641.5C241.37 651.94 243.19 661.28 245.11 671.5C255.03 724.45 282.43 776.72 321.58 813.93C379.51 868.97 451.52 887.85 529.5 891.33C633.28 895.97 735.17 865.43 798.37 778.85C811.25 761.22 821.69 741.47 829.64 721.17C835.88 705.27 840.66 689.27 844.25 672.58C845.55 666.58 847.23 660.64 847.77 654.5C852.07 605.34 791.52 576.56 754.95 608.47C745.43 616.77 738.15 628.96 736.25 641.55C735.2 648.51 735.23 655.56 734.03 662.5C731.71 676 727.45 688.76 721.56 701.12C696.54 753.62 638.03 781.77 583.87 793.49C571.39 796.19 558.26 799.29 545.46 799.73C541.2 799.88 536.68 798.76 532.47 798.2C522.15 796.83 511.92 794.98 501.75 792.6C452.6 781.09 401.55 756.74 374.31 712.22C365.57 697.92 359.31 681.91 356.09 665.47C353.69 653.19 354.48 640.57 349.65 628.78C340.39 606.15 313.99 590.94 289.74 595.33Z" fill="#005bfc" fill-rule="evenodd" stroke="#005bfc" stroke-width="0.25" stroke-linejoin="round" />
                <path d="M441.5 203.54C441.93 211.16 438.07 221.93 436.35 229.66C431.97 249.42 427.68 269.16 423.51 288.95C414.79 330.18 405.79 371.3 396.86 412.5C391.57 436.9 388.69 463.09 375.18 484.68C347.05 529.64 284.09 543.22 240.09 513.44C230.98 507.27 223.18 499.76 216.46 491.08C194.67 462.92 191.17 423.66 206.13 391.63C214.16 374.42 229.56 355.91 240.92 340.35C256.26 319.32 271.05 297.88 286.31 276.8C298.93 259.38 311.29 241.78 323.83 224.3C327.31 219.46 330.69 214.53 334.18 209.7C335.45 207.94 337.11 204.51 339.19 203.66C341.65 202.65 345.86 203.47 348.5 203.47C355.5 203.47 362.5 203.47 369.5 203.47C393.45 203.47 417.57 202.66 441.5 203.54ZM653.5 203.61C676.72 202.04 702.05 203.47 725.5 203.47C732.5 203.47 739.5 203.47 746.5 203.47C749.15 203.47 753.34 202.65 755.81 203.65C757.9 204.5 759.55 207.93 760.82 209.69C764.29 214.54 767.71 219.44 771.17 224.3C783.64 241.82 796.09 259.36 808.68 276.8C824.62 298.88 840.12 321.3 856.18 343.3C867.23 358.44 881.03 374.63 888.88 391.62C904.17 424.68 899.83 463.34 877.67 492.19C870.6 501.39 861.71 509.27 851.87 515.39C806.09 543.86 744.32 527.08 717.59 480.91C706.12 461.09 702.82 434.75 698.16 412.5C689.57 371.48 680.58 330.58 671.66 289.63C667.61 271.02 663.69 252.39 659.57 233.78C657.74 225.47 652.99 211.53 653.5 203.61ZM486.5 243.33C507.17 262.56 527.83 281.79 548.5 301.01C568.27 281.84 588.04 262.67 607.81 243.5C611.66 250.87 612.66 265.6 614.42 274.26C617.55 289.69 620.84 305.04 623.95 320.48C628.83 344.7 633.7 368.82 638.51 393.05C641.43 407.77 645.7 422.31 644.91 437.5C643.5 464.59 630.22 490.58 609.16 507.64C600.88 514.35 591.88 519.63 581.87 523.41C569.65 528.03 556.59 530.03 543.52 529.59C497.38 528.02 458.43 490.56 452.12 445.47C449.86 429.3 453.51 413.93 456.58 398.24C458.46 388.66 460.05 379.03 462 369.49C470.57 327.55 478.8 285.45 486.5 243.33ZM289.74 595.33C313.99 590.94 340.39 606.15 349.65 628.78C354.48 640.57 353.69 653.19 356.09 665.47C359.31 681.91 365.57 697.92 374.31 712.22C401.55 756.74 452.6 781.09 501.75 792.6C511.92 794.98 522.15 796.83 532.47 798.2C536.68 798.76 541.2 799.88 545.46 799.73C558.26 799.29 571.39 796.19 583.87 793.49C638.03 781.77 696.54 753.62 721.56 701.12C727.45 688.76 731.71 676 734.03 662.5C735.23 655.56 735.2 648.51 736.25 641.55C738.15 628.96 745.43 616.77 754.95 608.47C791.52 576.56 852.07 605.34 847.77 654.5C847.23 660.64 845.55 666.58 844.25 672.58C840.66 689.27 835.88 705.27 829.64 721.17C821.69 741.47 811.25 761.22 798.37 778.85C735.17 865.43 633.28 895.97 529.5 891.33C451.52 887.85 379.51 868.97 321.58 813.93C282.43 776.72 255.03 724.45 245.11 671.5C243.19 661.28 241.37 651.94 242.84 641.5C246.23 617.39 266.48 599.54 289.74 595.33Z" fill="#ffffff" fill-rule="evenodd" stroke="#ffffff" stroke-width="0.25" stroke-linejoin="round" />
            </svg>
        <?php
        }


        /**
         * Generate text notification
         * @param string,string,string,string
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_notification($type = 'info', $text = '', $extra_class = 'm-0 mb-3', $extra_style = '')
        {
            $type_class = '';
            $icon = 'fa-light fa-badge-percent';
            if ($type) {
                $type_class = 'irantheme-notification-' . $type;
            }
            if ($type == 'success') {
                $icon = 'fa-light fa-badge-check';
            } else if ($type == 'info') {
                $icon = 'fa-light fa-badge-percent';
            } else if ($type == 'warning') {
                $icon = 'fa-light fa-seal-question';
            } else if ($type == 'error') {
                $icon = 'fa-light fa-seal-exclamation';
            }
        ?>
            <!-- Note -->
            <div class="irantheme-notification <?php echo esc_attr($type_class); ?> <?php echo esc_attr($extra_class); ?>" style="<?php echo esc_attr($extra_style); ?>">
                <div class="d-flex align-items-center">
                    <?php if ($icon): ?>
                        <i class="irantheme-notification-icon <?php echo esc_attr($icon); ?>"></i>
                    <?php endif; ?>
                    <span class="irantheme-notification-message"><?php _e($text); ?></span>
                </div>
            </div>
        <?php
        }


        /**
         * Generate header settings
         * @param void
         * @return void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public function get_header($page_title, $description, $menu_slug, $settings_type)
        {
            // Check user capabilities
            if (!current_user_can($this->capability)) {
                return;
            }

            // check if the user have submitted the settings
            // WordPress will add the "settings-updated" $_GET parameter to the url
            if (isset($_GET['settings-updated'])) {
                // add settings saved message with the class of "updated"
                add_settings_error($menu_slug, $menu_slug . '_code', __('با موفقیت ذخیره شده', self::$text_domain), 'updated');
            }
            // show error/update messages
            settings_errors($menu_slug);

            /*
			=====================================
			Irantheme settings & Heading
			=====================================
			*/
        ?>
            <div class="irantheme-settings wrap" data-settings="<?php echo esc_attr($settings_type); ?>">
                <!-- Heading title -->
                <h1 class="irantheme-settings-heading-title"><?php _e($page_title, self::$text_domain); ?></h1>
                <!-- Heading description -->
                <p class="irantheme-settings-heading-description"><?php _e($description, self::$text_domain); ?></p>

                <!-- Wrap holder -->
                <div class="irantheme-settings-panel">

                    <!-- Main wrapper -->
                    <div class="irantheme-settings-panel-main">

                        <!-- Header -->
                        <div class="irantheme-settings-header">
                            <div class="irantheme-settings-header-holder">
                                <div class="row align-items-center">
                                    <!-- Column brand -->
                                    <div class="col-xl-2">
                                        <div class="irantheme-settings-header-brand">
                                            <!-- Logo -->
                                            <div class="irantheme-settings-header-brand-logo">
                                                <?php self::get_logo_html(); ?>
                                            </div>
                                            <!-- Heading -->
                                            <div class="irantheme-settings-header-brand-heading">
                                                <h1 class="irantheme-settings-header-brand-heading-title"><?php echo esc_html($this->menu_title); ?></h1>
                                                <span class="irantheme-settings-header-brand-heading-version">نسخه :‌ <?php echo esc_html(IRANTHEME_VERSION); ?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Main menu -->
                                    <div class="col-xl-8">
                                        <!-- Main menu -->
                                        <div class="irantheme-settings-main-menu">
                                            <ul class="irantheme-settings-main-menu-list">
                                                <?php
                                                // Get global settings child
                                                $settings = array(
                                                    array(
                                                        'menu_slug' => Irantheme_Settings_Status::get_menu_slug(),
                                                        'menu_title' => 'وضعیت سیستم',
                                                        'menu_icon' => 'fa-light fa-chart-line'
                                                    )
                                                );
                                                if ($this->verify) :
                                                    $plugins_settings = array(
                                                        'menu_slug' => Irantheme_Settings_Plugins::get_menu_slug(),
                                                        'menu_title' => 'نصب پلاگین ها',
                                                        'menu_icon' => 'fa-light fa-plug-circle-plus'
                                                    );
                                                    $status_settings = array(
                                                        'menu_slug' => Irantheme_Settings_Options::get_menu_slug(),
                                                        'menu_title' => 'تنظیمات قالب',
                                                        'menu_icon' => 'fa-light fa-gear'
                                                    );
                                                    $demo_settings = array(
                                                        'menu_slug' => Irantheme_Settings_Demo::get_menu_slug(),
                                                        'menu_title' => 'دمو های قالب',
                                                        'menu_icon' => 'fa-light fa-laptop-code'
                                                    );
                                                    $cleaner_settings = array(
                                                        'menu_slug' => Irantheme_Settings_Cleaner::get_menu_slug(),
                                                        'menu_title' => 'بهینه و پاکسازی',
                                                        'menu_icon' => 'fa-light fa-wrench-simple'
                                                    );
                                                    array_push($settings, $plugins_settings);
                                                    array_push($settings, $status_settings);
                                                    array_push($settings, $demo_settings);
                                                    array_push($settings, $cleaner_settings);
                                                endif;
                                                // Loop from settings child
                                                foreach ($settings as $setting) :
                                                    // Get current url
                                                    $current_url = $_SERVER['REQUEST_URI'];
                                                    // Active class
                                                    $active = '';
                                                    // Check current url with menu slug
                                                    if (str_contains($current_url, $setting['menu_slug'])) {
                                                        $active = 'active';
                                                    }
                                                ?>
                                                    <li>
                                                        <a class="<?php echo esc_attr($active); ?>" href="<?php echo esc_url_raw(get_admin_url()); ?>admin.php?page=<?php echo esc_attr($setting['menu_slug']); ?>">
                                                            <i class="<?php echo esc_attr($setting['menu_icon']); ?>"></i>
                                                            <?php _e($setting['menu_title']); ?>
                                                        </a>
                                                    </li>
                                                <?php
                                                endforeach;
                                                ?>
                                            </ul>
                                        </div>
                                    </div>

                                    <!-- Column search -->
                                    <div class="col-xl-2">
                                        <div class="irantheme-settings-header-addition">
                                            <ul class="irantheme-settings-header-addition-list">
                                                <?php
                                                // Loop from settings child
                                                foreach ($settings as $setting) :
                                                    // Get current url
                                                    $current_url = $_SERVER['REQUEST_URI'];
                                                    // Active class
                                                    $active = '';
                                                    // Check current url with menu slug
                                                    if (str_contains($current_url, $setting['menu_slug'])) {
                                                        $active = 'active';
                                                    }
                                                ?>
                                                    <li class="irantheme-settings-header-addition-list-item irantheme-settings-header-addition-list-item-media">
                                                        <a href="<?php echo esc_url_raw(get_admin_url()); ?>admin.php?page=<?php echo esc_attr($setting['menu_slug']); ?>" class="irantheme-button-default irantheme-settings-header-addition-list-item-link <?php echo esc_attr($active); ?>">
                                                            <i class="<?php echo esc_attr($setting['menu_icon']); ?>"></i>
                                                        </a>
                                                    </li>
                                                <?php
                                                endforeach;
                                                ?>
                                                <!-- Notification toggle -->
                                                <li class="irantheme-settings-header-addition-list-item">
                                                    <a href="#irantheme-settings-header-memo-sub" class="irantheme-button-default irantheme-settings-header-addition-list-item-link irantheme-settings-header-addition-list-item-toggle">
                                                        <i class="fa-light fa-circle-info"></i>
                                                    </a>
                                                    <!-- Sub notification -->
                                                    <ul id="irantheme-settings-header-memo-sub" class="irantheme-settings-header-addition-sub-box">
                                                        <!-- Notification box -->
                                                        <div class="irantheme-settings-notification-box">
                                                            <!-- Heading -->
                                                            <div class="irantheme-settings-notification-box-heading">
                                                                <cite><?php _e('اطلاعات'); ?></cite>
                                                                <i class="fa-light fa-circle-info"></i>
                                                            </div>
                                                            <!-- Parent content -->
                                                            <div class="irantheme-settings-notification-box-list">
                                                                <!-- Notification item -->
                                                                <div class="irantheme-settings-notification-box-list-item">
                                                                    <i class="fa-light fa-book info"></i>
                                                                    <div class="irantheme-settings-notification-box-list-item-content">
                                                                        <strong><?php _e('آموزش ها'); ?></strong>
                                                                        <p><?php _e('جهت مشاهده مستندات و آموزش های ویدئویی به لینک زیر مراجعه کنید.'); ?></p>
                                                                        <span><a href="https://irantheme.com/documents/" target="_blank"><?php _e('جزئیات بیشتر', IRANTHEME_TEXTDOMAIN); ?></a></span>
                                                                    </div>
                                                                </div>
                                                                <!-- Notification item -->
                                                                <div class="irantheme-settings-notification-box-list-item">
                                                                    <i class="fa-light fa-bullhorn error"></i>
                                                                    <div class="irantheme-settings-notification-box-list-item-content">
                                                                        <strong><?php _e('آخرین تغییرات', IRANTHEME_TEXTDOMAIN); ?></strong>
                                                                        <p><?php _e('آخرین تغییرات بروزرسانی مربوط به استورپلاس را مطالعه کنید.', IRANTHEME_TEXTDOMAIN); ?></p>
                                                                        <span><a href="https://irantheme.com/changelog/" target="_blank"><?php _e('جزئیات بیشتر', IRANTHEME_TEXTDOMAIN); ?></a></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </ul>
                                                </li>
                                                <!-- Notification toggle -->
                                                <li class="irantheme-settings-header-addition-list-item">
                                                    <?php
                                                    // Check update is available
                                                    $update_is_available_attr = '';
                                                    // if (irantheme_theme_has_update_available()) {
                                                    if (false) {
                                                        $update_is_available_attr = 'has-update';
                                                    }
                                                    ?>
                                                    <a href="#irantheme-settings-header-notification-sub" class="irantheme-button-default irantheme-settings-header-addition-list-item-link irantheme-settings-header-addition-list-item-toggle <?php echo esc_attr($update_is_available_attr); ?>">
                                                        <i class="fa-light fa-bell"></i>
                                                    </a>
                                                    <!-- Sub notification -->
                                                    <ul id="irantheme-settings-header-notification-sub" class="irantheme-settings-header-addition-sub-box">
                                                        <!-- Notification box -->
                                                        <div class="irantheme-settings-notification-box">
                                                            <!-- Heading -->
                                                            <div class="irantheme-settings-notification-box-heading">
                                                                <cite><?php _e('اعلانات'); ?></cite>
                                                                <i class="fa-light fa-bell"></i>
                                                            </div>
                                                            <!-- Parent content -->
                                                            <div class="irantheme-settings-notification-box-list">
                                                                <?php if ($update_is_available_attr): ?>
                                                                    <!-- Notification item -->
                                                                    <div class="irantheme-settings-notification-box-list-item has-update">
                                                                        <i class="fa-light fa-rotate info"></i>
                                                                        <?php
                                                                        // WordPress core updates page
                                                                        $core_updates_url = admin_url('update-core.php');
                                                                        ?>
                                                                        <div class="irantheme-settings-notification-box-list-item-content">
                                                                            <strong><?php _e('بروزرسانی جدید'); ?></strong>
                                                                            <p><?php _e('بروزرسانی جدیدی در دسترس است، جهت دریافت امکانات بیشتر و افرایش امنیت، محصول را بروز کنید.'); ?></p>
                                                                            <span><a href="<?php echo esc_url_raw($core_updates_url); ?>"><?php _e('شروع بروزرسانی'); ?></a></span>
                                                                        </div>
                                                                    </div>
                                                                <?php else: ?>
                                                                    <!-- Notification empty -->
                                                                    <div class="irantheme-settings-notification-null">
                                                                        <i class="fa-light fa-bell-slash"></i>
                                                                        <p><?php _e('هیچ اعلانی ندارید!'); ?></p>
                                                                        <p><?php _e('وقتی اتفاق جدید یا بروزرسانی منتشر شود، از اینجا با خبر می شوید.'); ?></p>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Holder -->
                        <div class="irantheme-settings-panel-holder">
                        <?php
                    }

                    // Implement get footer
                    public function get_footer($settings_type = 'options')
                    {
                        ?>
                        </div>
                    </div>
                </div>

            </div>
<?php
                    }
                }
            }
