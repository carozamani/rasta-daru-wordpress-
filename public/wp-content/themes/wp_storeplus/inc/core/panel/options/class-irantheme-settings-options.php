<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;

/**
 * The Storeplus Theme Settings class
 * A Customizable Class and Settings
 * 
 * @package Wordpress
 * @subpackage Storeplus
 * @since v1.0
 * 
 * This class is a custom settings theme for admin page.
 */
if (!class_exists('Irantheme_Settings_Options')) {
	class Irantheme_Settings_Options
	{
		// Parent slug
		private $parent;
		// Submenu title
		public $menu_title;
		// Page title
		private $page_title;
		// File path and file name
		private $file_path;
		// Description of main menu
		private $description;

		// Constructor initialize
		public function __construct(Irantheme_Settings $parent, $menu_title, $page_title, $description)
		{
			// Get parent slug
			$this->parent = $parent;
			// Menu title
			$this->menu_title = $menu_title;
			// Page title
			$this->page_title = $page_title;
			// Description page
			$this->description = $description;
			// Init file path with current path file
			$this->file_path = __FILE__;
			add_action('admin_init', array($this, 'check_init_update_settings_options'));
		}


		/**
		 * Get menu slug of current class
		 * @param void
		 * @return string
		 * @author iranianclever
		 * @since v1.0
		 * @see v1.0
		 */
		public static function get_menu_slug()
		{
			return 'irantheme_settings_options';
		}


		/**
		 * Update settings options
		 * @param void
		 * @return string
		 * @author iranianclever
		 * @since v1.0
		 * @see v1.0
		 */
		public static function settings_options()
		{
			// Die if not a POST request
			if ('POST' !== $_SERVER['REQUEST_METHOD']) {
				die();
			}

			// Get form data ajax
			$data_form = isset($_POST['data_form']) ? wp_unslash($_POST['data_form']) : '';

			// Parse form data properly (handles HTML entities, = and & inside values)
			$parsed_fields = array();
			wp_parse_str($data_form, $parsed_fields);

			// Loop from parsed data fields
			foreach ($parsed_fields as $option_name => $option_value) {
				update_option(
					sanitize_text_field($option_name),
					$option_value
				);
			}

			// Return a success message (optional)
			echo 'Options updated successfully.';
			die();
		}


		/**
		 * Check init update fields of settings options
		 * @return string
		 * @param void
		 * @author iranianclever
		 * @since v1.0
		 * @see v1.0
		 */
		public function check_init_update_settings_options()
		{
			// Create name of register init
			$register_name_init = 'irantheme_init_theme_settings_options';
			// Get option value init
			$option_value = get_option($register_name_init);
			// Check is settings options initialized?
			if ($option_value == 'initialized') {
				return;
			} else {
				// Start of initialize
				if (is_array(Irantheme_Fields_Options::fields())) {
					foreach (Irantheme_Fields_Options::fields() as $tab_item) {
						foreach ($tab_item['submenu'] as $key => $value) {
							// If fields not found, go to next section
							if (!$value['fields']) {
								continue;
							}
							foreach ($value['fields'] as $fields) {
								// Validation callback for field
								$validation = '';
								if (isset($fields['callback'])) {
									$validation = $fields['callback'];
								}

								// Register field and update
								$register_name = $fields['id'];
								register_setting(self::get_menu_slug(), $register_name, $validation);
								update_option($register_name, $fields['default']);
							}
						}
					}
				}
				// Register and update fields in initialization status
				register_setting(self::get_menu_slug(), $register_name_init);
				update_option($register_name_init, 'initialized');
			}
		}

		/**
		 * Add submenu to parent menu
		 * @return void
		 */
		public function add_submenu_page()
		{
			add_submenu_page($this->parent->menu_slug, __($this->page_title, $this->parent::$text_domain), __($this->menu_title, $this->parent::$text_domain), $this->parent->capability, self::get_menu_slug(), array($this, 'callback_panel'));
		}

		/**
		 * Register plugin settings
		 * @return void
		 */
		public function register_settings()
		{
			if (is_array(Irantheme_Fields_Options::fields())) {
				foreach (Irantheme_Fields_Options::fields() as $tab_item) {

					foreach ($tab_item['submenu'] as $key => $value) {

						// If fields not found, go to next section
						if (!$value['fields']) {
							continue;
						}
						// Add section to page
						add_settings_section(
							$value['id'],
							$value['title'],
							function () use ($value) {
								$description = '<p class="irantheme-settings-section-description"> ' . $value['description'] . '</p>' . "\n";
								echo $description;
							},
							self::get_menu_slug()
						);

						foreach ($value['fields'] as $fields) {
							// Validation callback for field
							$validation = '';
							if (isset($fields['callback'])) {
								$validation = $fields['callback'];
							}

							// Register field
							$register_name = $fields['id'];
							register_setting(self::get_menu_slug(), $register_name, $validation);

							// Add field to page
							add_settings_field($fields['id'], $fields['label'], array($this, 'display_fields'), self::get_menu_slug(), $value['id'], array('field' => $fields));
						}
					}
				}
			}
		}

		/**
		 * Generate HTML for displaying fields
		 * @param  array,string,string
		 * @return void
		 */
		public function display_fields($args)
		{
			$field = $args['field'];
			$field_name = $args['field']['id'];
			$field_value = get_option($field_name);
			// Display fields
			echo Irantheme_Settings::display_fields($field, $field_name, $field_value);
		}

		/**
		 * Add details settings and options settings to custom wp admin menu
		 * @return void
		 */
		public function callback_panel()
		{
			// Get header parent
			$this->parent->get_header($this->page_title, $this->description, self::get_menu_slug(), Irantheme_Fields_Options::get_type());
?>
			<div class="container-fluid">
				<div class="row">
					<!-- Navigation -->
					<div class="col-xxl-2 col-xl-3 col-lg-3 col-md-3 p-0 irantheme-settings-navigation-holder">
						<div class="irantheme-settings-navigation">
							<!-- Search -->
							<div class="irantheme-settings-navigation-search">
								<div class="irantheme-settings-navigation-search-icon">
									<i class="fa-light fa-magnifying-glass"></i>
								</div>
								<input class="irantheme-settings-navigation-search-input" type="text" placeholder="جستجو..." />
							</div>
							<!-- Menubar list -->
							<ul id="settings-sections" class="irantheme-settings-navigation-list hide-if-no-js">
								<?php
								$cookie_tab_id = $_COOKIE['iranthemeTabMenubarId'] ?? null;
								$ul_cookie_title = '';
								// Check cookie
								if ($cookie_tab_id) {
									foreach (Irantheme_Fields_Options::fields() as $tab_item) {
										foreach ($tab_item['submenu'] as $key => $value) {
											if ($value['fields']) {
												if ($value['id'] == $cookie_tab_id) {
													$ul_cookie_title = $tab_item['title'];
													break;
												}
											}
										}
										if ($ul_cookie_title) {
											break;
										}
									}
								}
								$index_parent = 0;
								$index_child = 0;
								$a_class = '';
								$cite_class = '';
								$ul_attr = '';
								foreach (Irantheme_Fields_Options::fields() as $tab_item) {
									if ($cookie_tab_id) {
										if ($ul_cookie_title == $tab_item['title']) {
											$cite_class = 'active';
										} else {
											$cite_class = '';
										}
									}
									if (!$cookie_tab_id) {
										if ($index_parent == 0) {
											$cite_class = 'active';
										} else {
											$cite_class = '';
										}
									}
								?>
									<li><cite class="tab <?php echo esc_attr($cite_class); ?>"><span class="fa-light <?php echo esc_attr($tab_item['icon']); ?> icon-logo"></span><?php _e($tab_item['title']); ?><span class="dashicons dashicons-arrow-left-alt2 icon-arrow"></span></cite>
										<?php
										if ($tab_item['submenu']) {
											if ($cookie_tab_id) {
												if ($ul_cookie_title == $tab_item['title']) {
													$ul_attr = 'style="display:block;"';
												} else {
													$ul_attr = '';
												}
											}
											if (!$cookie_tab_id) {
												if ($index_parent == 0) {
													$ul_attr = 'style="display:block;"';
												} else {
													$ul_attr = '';
												}
											}
										?>
											<ul class="irantheme-settings-navigation-submenu" <?php echo $ul_attr; ?>>
												<?php
												foreach ($tab_item['submenu'] as $key => $value) {
													if ($value['fields']) {
														if ($cookie_tab_id) {
															if ($cookie_tab_id == $value['id']) {
																$a_class = 'current"';
															} else {
																$a_class = '';
															}
														}
														if (!$cookie_tab_id) {
															if ($index_parent == 0 && $index_child == 0) {
																$a_class = 'current"';
															} else {
																$a_class = '';
															}
														}
												?>
														<li><a class="tab <?php echo $a_class; ?>" href="#<?php echo $value['id']; ?>"><?php _e($value['title']); ?><span class="dashicons dashicons-saved"></span></a></li>
												<?php
													}
													$index_child++;
												}
												?>
											</ul>
										<?php
										}
										?>

									</li>
								<?php
									$index_parent++;
								}
								?>
							</ul>
						</div>
					</div>

					<div class="col-xxl-10 col-xl-9 col-lg-9 col-md-9 p-0 irantheme-settings-options-holder">
						<!-- Form action -->
						<form id="irantheme-form-settings-options-ajax" method="post" action="options.php" enctype="multipart/form-data">
							<div class="container-fluid p-3">
								<!-- Button actions -->
								<div class="irantheme-settings-action-buttons">
									<div class="button-inline-group">
										<?php Irantheme_Settings::get_notification('warning', 'جهت جایگزینی مقادیر پیش فرض این بخش، تنظیمات را ثبت کنید.', 'irantheme-notification-reset-section', 'display:none;'); ?>
										<?php Irantheme_Settings::get_notification('error', 'جهت جایگزینی مقادیر پیش فرض در همه بخش ها، تنظیمات را ثبت کنید.', 'irantheme-notification-reset-all', 'display:none;'); ?>
										<?php Irantheme_Settings::get_notification('success', 'تنظیمات با موفقیت ذخیره شد.', 'irantheme-notification-save-all', 'display:none;'); ?>
										<button type="submit" class="irantheme-settings-options-panel-submit irantheme-button-default irantheme-button-primary">
											<i class="fa-light fa-cog"></i>
											<i id="irantheme-spinner-alt" class="irantheme-animate-rotate"></i>
											<i class="fa-light fa-check irantheme-settings-options-panel-submit-check"></i>
											<cite>ثبت تنظیمات</cite>
										</button>
										<span class="irantheme-reset-section irantheme-button-default irantheme-button-dark"><span class="fa-light fa-undo"></span>بازنشانی بخش</span>
										<span class="irantheme-reset-all irantheme-button-default irantheme-button-dark"><span class="fa-light fa-refresh"></span>بازنشانی همه</span>
									</div>
								</div>
							</div>
							<!-- Options -->
							<div class="irantheme-settings-options">
								<?php
								$index_wrapper_parent = 0;
								$index_wrapper_child = 0;
								$wrapper_attr = '';
								// Get section fields
								$fields_settings_all = Irantheme_Fields_Options::fields();
								foreach ($fields_settings_all as $fields_settings) :
								?>
									<!-- Wrapper options -->
									<div class="irantheme-settings-options-wrapper">
										<?php foreach ($fields_settings['submenu'] as $fields) :
											if ($cookie_tab_id) {
												if ($cookie_tab_id == $fields['id']) {
													$wrapper_attr = 'style="display:block;"';
												} else {
													$wrapper_attr = 'style="display:none;"';
												}
											}
											if (!$cookie_tab_id) {
												if ($index_wrapper_parent == 0 && $index_wrapper_child == 0) {
													$wrapper_attr = 'style="display:block;"';
												} else {
													$wrapper_attr = 'style="display:none;"';
												}
											}
										?>
											<!-- Holder options -->
											<div class="irantheme-settings-options-wrapper-holder" id="<?php echo $fields['id']; ?>" <?php echo $wrapper_attr; ?>>
												<h2><?php echo esc_html($fields['title']); ?></h2>
												<p class="irantheme-settings-section-description"><?php echo esc_html($fields['description']); ?></p>
												<table class="irantheme-settings-visual-panel-ajax-table form-table">
													<tbody>
														<?php
														foreach ($fields['fields'] as $field_item) :
															# code...
															if ($field_item['type'] == 'hidden_json') {
																continue;
															}
															$value = get_option($field_item['id']);
														?>
															<tr>
																<th scope="row"><?php _e($field_item['label']); ?></th>
																<td><?php echo Irantheme_Settings::display_fields($field_item, $field_item['id'], $value); ?></td>
															</tr>
														<?php endforeach; ?>
													</tbody>
												</table>
											</div>
										<?php
											$index_wrapper_child++;
										endforeach;
										?>
									</div>
								<?php
									$index_wrapper_parent++;
								endforeach;
								?>
							</div>
						</form>
					</div>
				</div>
			</div>
<?php
			// Get footer parent
			$this->parent->get_footer();
		}
	}
}
