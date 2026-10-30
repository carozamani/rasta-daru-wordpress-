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
if (!class_exists('Irantheme_Fields_Options')) {
	class Irantheme_Fields_Options
	{
		private static $static_textdomain  = IRANTHEME_TEXTDOMAIN;

		public function __construct() {}

		public static function get_type()
		{
			return 'options';
		}

		/**
		 * Generate settings fields
		 * @return array Fields to be displayed on settings page
		 */
		public static function fields()
		{
			// Header configuration fields data
			$settings['other'] = array(
				'title'                    => __('تنظیمات سراسری', self::$static_textdomain),
				'icon' => 'fa-square-sliders',
				'submenu'                => array(
					array(
						'title' => 'نگهدارنده سایت',
						'id' => 'irantheme_other_wrapper',
						'description'            => __('نگهدارنده ها عناصر پنهانی هستند که مانند ظرفی محتوای سایت را در خود نگه می دارند تا حداکثر عرض مجاز برای همه بخش های سایت یکسان باشد.شما در این بخش می توانید میزان عرض این نگهدارنده را بر حسب درصد مشخص کنید.', self::$static_textdomain),
						'icon' => 'fa-arrows-left-right-to-line',
						'fields' => array(
							array(
								'id'             => 'irantheme_outer_container',
								'label'            => __(
									'عرض نگهدارنده خارجی',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید عرض نگهدارنده خارجی سایت را مشخص نمایید.(واحد اندازه گیری درصد و نسبت به عرض صفحه نمایش می باشد)', self::$static_textdomain),
								'type'            => 'range',
								'min' => 50,
								'max' => 100,
								'step' => 1,
								'default'        => 100,
								'placeholder'    => __('Placeholder text', self::$static_textdomain),
								'keywords' => array('container', 'width', 'wrapper', 'عرض نهگدارنده', 'اندازه', 'عرض', 'نگهدارنده')
							),
							array(
								'id'             => 'irantheme_inner_container',
								'label'            => __(
									'عرض نگهدارنده داخلی',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید عرض نگهدارنده داخلی سایت را مشخص نمایید.(واحد اندازه گیری درصد و نسبت به عرض نگهدارنده خارجی می باشد)', self::$static_textdomain),
								'type'            => 'range',
								'min' => 40,
								'max' => 100,
								'step' => 1,
								'default'        => 70,
								'placeholder'    => __('Placeholder text', self::$static_textdomain),
								'keywords' => array('container', 'width', 'wrapper', 'عرض نهگدارنده', 'اندازه', 'عرض', 'نگهدارنده')
							),
						)
					),
					// Get background fields
					Irantheme_Section_Utils::background_fields_generator(true, array('image', 'color', 'mask', 'parallax'), '', 'transparent', true, 'off', 'dark', 0.6, 'off'),
					array(
						'title' => 'تنظیم فونت',
						'id' => 'irantheme_fonts_settings',
						'description'            => __('در این بخش می توانید تنظیمات مربوط به فونت قالب را تنظیم نمایید.', self::$static_textdomain),
						'icon' => 'fa-font',
						'fields' => array(
							array(
								'id'             => 'irantheme_font_main_select',
								'label'            => __(
									'فونت سایت',
									self::$static_textdomain
								),
								'description'    => __('لطفا فونت اصلی سایت را انتخاب نمایید.', self::$static_textdomain),
								'type'            => 'select',
								'options'        => array('iransansx' => 'ایران سنس', 'iranyekanx' => 'ایران یکان', 'vazirmatn' => 'وزیر متن', 'sahel' => 'ساحل', 'shabnam' => 'شبنم', 'tanha' => 'تنها'),
								'default'        => 'iransansx',
								'keywords' => array('font', 'font face', 'فونت', 'فونت سایت'),
							),
						)
					),
					array(
						'title' => 'دکمه پرش به بالا',
						'id' => 'irantheme_other_scroll_top',
						'description'            => __('به دکمه ای گفته می شود که معمولا در پایین وب سایت نمایش داده می شود و شما را به بالای صفحه هدایت می کند', self::$static_textdomain),
						'fields' => array(
							array(
								'id'             => 'irantheme_scrollbtn_show_checkbox',
								'label'            => __(
									'نمایش دکمه پرش به بالا',
									self::$static_textdomain
								),
								'description'    => __('در صورت نیاز می توانید دکمه پرش به بالا را در وب سایت خود فعال یا غیرفعال نمایید', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('scroll', 'پرش', 'پرش به بالا', 'اسکرول'),
								'id_dependents_on' => array('irantheme_scrollbtn_position', 'irantheme_scrollbtn_color'),
								'base_priority' => 'checkbox_base',
								'selector_priority' => 'checkbox_go_to_top',
								'z_index' => 0
							),
							array(
								'id'             => 'irantheme_scrollbtn_position',
								'label'            => __(
									'موقعیت دکمه',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید موقعیت قرار گیری دکمه در پایین صفحه را مشخص کنید', self::$static_textdomain),
								'type'            => 'radio',
								'options'        => array('right' => 'راست', 'left' => 'چپ'),
								'default'        => 'left',
								'keywords' => array('scroll', 'پرش', 'پرش به بالا', 'اسکرول')
							),
							array(
								'id'             => 'irantheme_scrollbtn_color',
								'label'            => __(
									'رنگ دکمه',
									self::$static_textdomain
								),
								'description'    => __('برای انتخاب رنگ دکمه پرش به بالا از لیست رنگ مورد نیاز خود را انتخاب کنید.', self::$static_textdomain),
								'type'            => 'select',
								'options'        => array('dark' => 'مشکی', 'white' => 'سفید'),
								'default'        => 'dark',
								'keywords' => array('scroll', 'پرش', 'پرش به بالا', 'اسکرول'),
							)
						)
					),
					array(
						'title' => 'رنگ های سایت',
						'id' => 'irantheme_other_color_palette',
						'description'            => __('تظیمات مربوط به رنگ های قابل استفاده در قالب', self::$static_textdomain),
						'fields' => array(
							array(
								'id'             => 'irantheme_main_color_custom_checkbox',
								'label'            => __(
									'رنگ اصلی دستی',
									self::$static_textdomain
								),
								'description'    => __('در صورتی که علاقمند به استفاده از رنگ های پیشنهادی ما نمی باشید می توانید با فعال کردن این گزینه، رنگ خود را به صورت دستی وارد نمایید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('رنگ دستی', 'custom color',),
								'id_dependents_on' => array('irantheme_main_color_picker'),
								'id_dependents_off' => array('irantheme_main_color_select'),
								'base_priority' => 'checkbox_base',
								'selector_priority' => 'main_color_custom_checkbox',
								'z_index' => 0
							),
							array(
								'id'             => 'irantheme_main_color_picker',
								'label'            => __(
									'رنگ اصلی (دستی)',
									self::$static_textdomain
								),
								'description'    => __('با کلیک بر روی نوار رنگ می توانید رنگ خود انتخاب کنید.', self::$static_textdomain),
								'type'            => 'color',
								'default'        => '#ffff00',
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('کالرپیکر', 'رنگ', 'تغییر')
							),
							array(
								'id'             => 'irantheme_main_color_select',
								'label'            => __(
									'رنگ اصلی',
									self::$static_textdomain
								),
								'description'    => __('برای انتخاب رنگ اصلی سایت از لیست رنگ مورد نظر خود را انتخاب کنید.', self::$static_textdomain),
								'type'            => 'select',
								'color_preview' => true,
								'options'        => Irantheme_Color_Static::get_flat_colors(),
								'default'        => '373E40',
								'keywords' => array('رنگ اصلی دستی', 'main color picker'),
							),
							array(
								'id'             => 'irantheme_sub_color_custom_checkbox',
								'label'            => __(
									'رنگ فرعی دستی',
									self::$static_textdomain
								),
								'description'    => __('در صورتی که علاقمند به استفاده از رنگ های پیشنهادی ما نمی باشید می توانید با فعال کردن این گزینه، رنگ خود را به صورت دستی وارد نمایید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('رنگ دستی', 'custom color',),
								'id_dependents_on' => array('irantheme_sub_color_picker'),
								'id_dependents_off' => array('irantheme_sub_color_select'),
								'base_priority' => 'checkbox_base',
								'selector_priority' => 'sub_color_custom_checkbox',
								'z_index' => 0
							),
							array(
								'id'             => 'irantheme_sub_color_picker',
								'label'            => __(
									'رنگ فرعی (دستی)',
									self::$static_textdomain
								),
								'description'    => __('با کلیک بر روی نوار رنگ می توانید رنگ خود انتخاب کنید.', self::$static_textdomain),
								'type'            => 'color',
								'default'        => '#ffff00',
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('رنگ فرعی دستی', 'sub color picker')
							),
							array(
								'id'             => 'irantheme_sub_color_select',
								'label'            => __(
									'رنگ فرعی',
									self::$static_textdomain
								),
								'description'    => __('برای انتخاب رنگ فرعی سایت از لیست رنگ مورد نظر خود را انتخاب کنید.', self::$static_textdomain),
								'type'            => 'select',
								'color_preview' => true,
								'options'        => Irantheme_Color_Static::get_flat_colors(),
								'default'        => 'DEDEE3',
								'keywords' => array('رنگ فرعی', 'sub color'),
							),
							array(
								'id'             => 'irantheme_primary_color_custom_checkbox',
								'label'            => __(
									'رنگ اولیه دستی',
									self::$static_textdomain
								),
								'description'    => __('در صورتی که علاقمند به استفاده از رنگ های پیشنهادی ما نمی باشید می توانید با فعال کردن این گزینه، رنگ خود را به صورت دستی وارد نمایید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('رنگ دستی', 'custom color',),
								'id_dependents_on' => array('irantheme_primary_color_picker'),
								'id_dependents_off' => array('irantheme_primary_color_select'),
								'base_priority' => 'checkbox_base',
								'selector_priority' => 'primary_color_custom_checkbox',
								'z_index' => 0
							),
							array(
								'id'             => 'irantheme_primary_color_picker',
								'label'            => __(
									'رنگ اولیه (دستی)',
									self::$static_textdomain
								),
								'description'    => __('با کلیک بر روی نوار رنگ می توانید رنگ خود انتخاب کنید.', self::$static_textdomain),
								'type'            => 'color',
								'default'        => '#ffff00',
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('رنگ اولیه دستی', 'sub color picker')
							),
							array(
								'id'             => 'irantheme_primary_color_select',
								'label'            => __(
									'رنگ اولیه',
									self::$static_textdomain
								),
								'description'    => __('برای انتخاب رنگ اصلی سایت از لیست رنگ مورد نظر خود را انتخاب کنید.', self::$static_textdomain),
								'type'            => 'select',
								'color_preview' => true,
								'options'        => Irantheme_Color_Static::get_flat_colors(),
								'default'        => '2980B9',
								'keywords' => array('رنگ اولیه', 'primary color'),
							),
							array(
								'id'             => 'irantheme_secondary_color_custom_checkbox',
								'label'            => __(
									'رنگ ثانویه دستی',
									self::$static_textdomain
								),
								'description'    => __('در صورتی که علاقمند به استفاده از رنگ های پیشنهادی ما نمی باشید می توانید با فعال کردن این گزینه، رنگ خود را به صورت دستی وارد نمایید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('رنگ دستی', 'custom color',),
								'id_dependents_on' => array('irantheme_secondary_color_picker'),
								'id_dependents_off' => array('irantheme_secondary_color_select'),
								'base_priority' => 'checkbox_base',
								'selector_priority' => 'secondary_color_custom_checkbox',
								'z_index' => 0
							),
							array(
								'id'             => 'irantheme_secondary_color_picker',
								'label'            => __(
									'رنگ ثانویه (دستی)',
									self::$static_textdomain
								),
								'description'    => __('با کلیک بر روی نوار رنگ می توانید رنگ خود انتخاب کنید.', self::$static_textdomain),
								'type'            => 'color',
								'default'        => '#ffff00',
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('رنگ ثانویه دستی', 'sub color picker')
							),
							array(
								'id'             => 'irantheme_secondary_color_select',
								'label'            => __(
									'رنگ ثانویه',
									self::$static_textdomain
								),
								'description'    => __('برای انتخاب رنگ ثانویه سایت از لیست رنگ مورد نظر خود را انتخاب کنید.', self::$static_textdomain),
								'type'            => 'select',
								'color_preview' => true,
								'options'        => Irantheme_Color_Static::get_flat_colors(),
								'default'        => 'E67E22',
								'keywords' => array('color', 'Secondary', 'رنگ', 'ثانویه', 'فرعی'),
							),
							array(
								'id'             => 'irantheme_tertiary_color_custom_checkbox',
								'label'            => __(
									'رنگ اضافی دستی',
									self::$static_textdomain
								),
								'description'    => __('در صورتی که علاقمند به استفاده از رنگ های پیشنهادی ما نمی باشید می توانید با فعال کردن این گزینه، رنگ خود را به صورت دستی وارد نمایید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('رنگ دستی', 'custom color',),
								'id_dependents_on' => array('irantheme_tertiary_color_picker'),
								'id_dependents_off' => array('irantheme_tertiary_color_select'),
								'base_priority' => 'checkbox_base',
								'selector_priority' => 'tertiary_color_custom_checkbox',
								'z_index' => 0
							),
							array(
								'id'             => 'irantheme_tertiary_color_picker',
								'label'            => __(
									'رنگ اضافی (دستی)',
									self::$static_textdomain
								),
								'description'    => __('با کلیک بر روی نوار رنگ می توانید رنگ خود انتخاب کنید.', self::$static_textdomain),
								'type'            => 'color',
								'default'        => '#ffff00',
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('رنگ اضافی دستی', 'sub color picker')
							),
							array(
								'id'             => 'irantheme_tertiary_color_select',
								'label'            => __(
									'رنگ اضافی',
									self::$static_textdomain
								),
								'description'    => __('برای انتخاب رنگ اضافی سایت از لیست رنگ مورد نظر خود را انتخاب کنید.', self::$static_textdomain),
								'type'            => 'select',
								'color_preview' => true,
								'options'        => Irantheme_Color_Static::get_flat_colors(),
								'default'        => '32fCA7',
								'keywords' => array('color', 'Secondary', 'رنگ', 'اضافی', 'فرعی'),
							),
						)
					),
					array(
						'title' => 'ناوبری موبایل',
						'id' => 'irantheme_other_navbar_mobile',
						'description'            => __('تظیمات مربوط به منوی ثابتی که در پایین صفحه موبایل نمایش داده می شود.', self::$static_textdomain),
						'fields' => array(
							array(
								'id'             => 'irantheme_navbar_mobile_checkbox',
								'label'            => __(
									'نمایش ناوبری در موبایل',
									self::$static_textdomain
								),
								'description'    => __('در صورتی که می خواهید دسترسی کاربران در موبایل راحت تر از قبل شود می توانید این منو را که به صورت ثابت در پایین دستگاه های کوچک به نمایش در می آید را فعال نمایید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('نمایش ناوبری در موبایل', 'display navbar in mobile'),
								'id_dependents_on' => array('irantheme_navbar_mobile_base_color_select', 'irantheme_navbar_mobile_full_width_checkbox', 'irantheme_navbar_mobile_auto_hide_checkbox', 'irantheme_navbar_mobile_details_color_select', 'irantheme_navbar_mobile_options_checkbox_multi', 'irantheme_navbar_mobile_radius_range', 'irantheme_navbar_mobile_glass_style_checkbox'),
								'base_priority' => 'checkbox_base',
								'selector_priority' => 'navbar_mobile_checkbox',
								'z_index' => 0
							),
							array(
								'id'             => 'irantheme_navbar_mobile_base_color_select',
								'label'            => __(
									'رنگ پایه',
									self::$static_textdomain
								),
								'description'    => __('برای انتخاب رنگ پایه از لیست رنگ دلخواه خود را انتخاب کنید.رنگ محتواو پس زمینه بصورت پیش فرض بر اساس این رنگ تنظیم خواهد شد.', self::$static_textdomain),
								'type'            => 'select',
								'options'        => array('white' => 'سفید', 'dark' => 'مشکی'),
								'default'        => 'white',
								'keywords' => array('رنگ پایه', 'navbar mobile base color'),
							),
							array(
								'id'             => 'irantheme_navbar_mobile_full_width_checkbox',
								'label'            => __(
									'ناوبری تمام عرض',
									self::$static_textdomain
								),
								'description'    => __('با فعال کردن این گزینه ناوبری موبایل به صورت تمام عرض نمایش داده خواهد شد، در صورت عدم فعال بودن این گزینه ناوبری کمی به صورت خمیده و فاصله از صفحه به نمایش در خواهد آمد.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'id_dependents_off' => array('irantheme_navbar_mobile_radius_range'),
								'selector_priority' => 'navbar_mobile_checkbox',
								'z_index' => 1,
								'keywords' => array('ناوبری تمام عرض', 'full width navbar mobile'),
							),
							array(
								'id'             => 'irantheme_navbar_mobile_radius_range',
								'label'            => __(
									'خمیدگی گوشه های نگهدارنده',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این امکان شما می توانید میزان خمیدگی گوشه های نوار ناوبری را تنظیم نمایید.', self::$static_textdomain),
								'type'            => 'range',
								'min' => 0.5,
								'max' => 5,
								'step' => 0.1,
								'default'        => 0,
								'placeholder'    => __('Placeholder text', self::$static_textdomain),
								'keywords' => array('خمیدگی گوشه های نگهدارنده', 'navbar mobile radius'),
							),
							array(
								'id'             => 'irantheme_navbar_mobile_auto_hide_checkbox',
								'label'            => __(
									'مخفی شدن در هنگام اسکرول',
									self::$static_textdomain
								),
								'description'    => __('در صورتی که می خواهید ناوبری موبایل در هنگام اسکرول به پایین مخفی شود این گزینه را فعال نمایید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('مخفی شدن در هنگام اسکرول', 'auto hide navbar mobile'),
							),
							array(
								'id'             => 'irantheme_navbar_mobile_details_color_select',
								'label'            => __(
									'رنگ جزئیات',
									self::$static_textdomain
								),
								'description'    => __('برای انتخاب رنگ جزئیات بخش از لیست رنگ دلخواه خود را انتخاب کنید. این رنگ تاثیر مستقیمی در زیبایی جزئيات دارد.', self::$static_textdomain),
								'type'            => 'select',
								'options'        => Irantheme_Color_Static::get_all_colors_options(),
								'color_preview' => true,
								'default'        => 'primary',
								'keywords' => array('رنگ جزئیات', 'details colors'),
							),
							array(
								'id'             => 'irantheme_navbar_mobile_glass_style_checkbox',
								'label'            => __(
									'استایل شیشه ای',
									self::$static_textdomain
								),
								'description'    => __('با فعال کردن این گزینه ظاهر نوار ناوبری ببصورت شیشه ای خواهد شد.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'on',
								'keywords' => array('استایل شیشه ای', 'navbar mobile glass style'),
							),
							array(
								'id'             => 'irantheme_navbar_mobile_options_checkbox_multi',
								'label'            => __(
									'انتخاب گزینه های ناوبری موبایل',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید نمایش و یا عدم نمایش گزینه های منوی ناوبری موبایل را تنظیم نمایید.', self::$static_textdomain),
								'type'            => 'checkbox_multi',
								'options'        => array('home' => 'خانه', 'shop' => 'فروشگاه', 'search' => 'جستجو', 'cart' => 'سبد خرید', 'favorite' => 'علاقه مندی ها', 'myaccount' => 'حساب کاربری', 'menu' => 'منو'),
								'default'        => 'shop,cart,favorite,myaccount',
								'keywords' => array('انتخاب گزینه های ناوبری موبایل', 'select options of navbar mobile')
							),
						)
					),
					array(
						'title' => 'شبکه های اجتماعی',
						'id' => 'irantheme_other_social_media',
						'description'            => __('تنظیمات مربوط به شبکه های اجتماعی', self::$static_textdomain),
						'fields' => array(
							array(
								'id'             => 'irantheme_instagram_social_url',
								'label'            => __(
									'اینستاگرام',
									self::$static_textdomain
								),
								'description'    => __('نشانی اینترنتی اینستاگرام', self::$static_textdomain),
								'type'            => 'text',
								'default'        => '',
								'placeholder'    => __('برای مثال: https://www.instagram.com/YourProfileName', self::$static_textdomain),
								'keywords' => array('social media', 'instagram', 'شبکه های اجتماعی', 'اینستاگرام'),
							),
							array(
								'id'             => 'irantheme_facebook_social_url',
								'label'            => __(
									'فیس بوک',
									self::$static_textdomain
								),
								'description'    => __('نشانی اینترنتی فیس بوک', self::$static_textdomain),
								'type'            => 'text',
								'default'        => '',
								'placeholder'    => __('برای مثال: https://www.facebook.com/YourProfileName', self::$static_textdomain),
								'keywords' => array('social media', 'facebook', 'شبکه های اجتماعی', 'فیس بوک'),
							),
							array(
								'id'             => 'irantheme_twitter_social_url',
								'label'            => __(
									'ایکس',
									self::$static_textdomain
								),
								'description'    => __('نشانی اینترنتی ایکس', self::$static_textdomain),
								'type'            => 'text',
								'default'        => '',
								'placeholder'    => __('برای مثال: https://www.x.com/YourProfileName', self::$static_textdomain),
								'keywords' => array('social media', 'twitter', 'شبکه های اجتماعی', 'ایکس'),
							),
							array(
								'id'             => 'irantheme_linkedin_social_url',
								'label'            => __(
									'لینکدین',
									self::$static_textdomain
								),
								'description'    => __('نشانی اینترنتی لینکدین', self::$static_textdomain),
								'type'            => 'text',
								'default'        => '',
								'placeholder'    => __('برای مثال: https://www.linkedin.com/in/YourProfileName', self::$static_textdomain),
								'keywords' => array('social media', 'linkedin', 'شبکه های اجتماعی', 'لینکدین'),
							),
							array(
								'id'             => 'irantheme_github_social_url',
								'label'            => __(
									'گیت هاب',
									self::$static_textdomain
								),
								'description'    => __('نشانی اینترنتی گیت هاب', self::$static_textdomain),
								'type'            => 'text',
								'default'        => '',
								'placeholder'    => __('برای مثال: https://github.com/YourProfileName', self::$static_textdomain),
								'keywords' => array('social media', 'github', 'شبکه های اجتماعی', 'گیت هاب'),
							),
							array(
								'id'             => 'irantheme_telegram_social_url',
								'label'            => __(
									'تلگرام',
									self::$static_textdomain
								),
								'description'    => __('نشانی اینترنتی تلگرام', self::$static_textdomain),
								'type'            => 'text',
								'default'        => '',
								'placeholder'    => __('برای مثال: https://www.t.me/YourProfileName', self::$static_textdomain),
								'keywords' => array('social media', 'telegram', 'شبکه های اجتماعی', 'تلگرام'),
							),
							array(
								'id'             => 'irantheme_youtube_social_url',
								'label'            => __(
									'یوتیوب',
									self::$static_textdomain
								),
								'description'    => __('نشانی اینترنتی یوتیوب', self::$static_textdomain),
								'type'            => 'text',
								'default'        => '',
								'placeholder'    => __('برای مثال: https://www.youtube.com/@YourProfileName', self::$static_textdomain),
								'keywords' => array('social media', 'youtube', 'شبکه های اجتماعی', 'یوتیوب'),
							),
							array(
								'id'             => 'irantheme_twitch_social_url',
								'label'            => __(
									'توییچ',
									self::$static_textdomain
								),
								'description'    => __('نشانی اینترنتی توییچ', self::$static_textdomain),
								'type'            => 'text',
								'default'        => '',
								'placeholder'    => __('برای مثال: https://www.twitch.tv/YourProfileName', self::$static_textdomain),
								'keywords' => array('social media', 'twitch', 'شبکه های اجتماعی', 'توییچ'),
							),
							array(
								'id'             => 'irantheme_pinterest_social_url',
								'label'            => __(
									'پینترست',
									self::$static_textdomain
								),
								'description'    => __('نشانی اینترنتی پینترست', self::$static_textdomain),
								'type'            => 'text',
								'default'        => '',
								'placeholder'    => __('برای مثال: https://www.pinterest.com/YourProfileName', self::$static_textdomain),
								'keywords' => array('social media', 'pinterest', 'شبکه های اجتماعی', 'پینترست'),
							),
							array(
								'id'             => 'irantheme_eitaa_social_url',
								'label'            => __(
									'ایتا',
									self::$static_textdomain
								),
								'description'    => __('نشانی اینترنتی ایتا', self::$static_textdomain),
								'type'            => 'text',
								'default'        => '',
								'placeholder'    => __('برای مثال: https://www.eitaa.com/YourProfileName', self::$static_textdomain),
								'keywords' => array('social media', 'eitaa', 'شبکه های اجتماعی', 'ایتا'),
							),
							array(
								'id'             => 'irantheme_soroush_social_url',
								'label'            => __(
									'سروش',
									self::$static_textdomain
								),
								'description'    => __('نشانی اینترنتی سروش', self::$static_textdomain),
								'type'            => 'text',
								'default'        => '',
								'placeholder'    => __('برای مثال: https://www.splus.ir/YourProfileName', self::$static_textdomain),
								'keywords' => array('social media', 'soroush', 'شبکه های اجتماعی', 'سروش')
							),
							array(
								'id'             => 'irantheme_rubika_social_url',
								'label'            => __(
									'روبیکا',
									self::$static_textdomain
								),
								'description'    => __('نشانی اینترنتی روبیکا', self::$static_textdomain),
								'type'            => 'text',
								'default'        => '',
								'placeholder'    => __('برای مثال: https://www.rubika.ir/YourProfileName', self::$static_textdomain),
								'keywords' => array('social media', 'rubika', 'شبکه های اجتماعی', 'روبیکا'),
							),
							array(
								'id'             => 'irantheme_gap_social_url',
								'label'            => __(
									'گپ',
									self::$static_textdomain
								),
								'description'    => __('نشانی اینترنتی گپ', self::$static_textdomain),
								'type'            => 'text',
								'default'        => '',
								'placeholder'    => __('برای مثال: https://www.gap.im/YourProfileName', self::$static_textdomain),
								'keywords' => array('social media', 'gap', 'شبکه های اجتماعی', 'گپ'),
							),
							array(
								'id'             => 'irantheme_bale_social_url',
								'label'            => __(
									'بله',
									self::$static_textdomain
								),
								'description'    => __('نشانی اینترنتی بله', self::$static_textdomain),
								'type'            => 'text',
								'default'        => '',
								'placeholder'    => __('برای مثال: https://www.ble.ir/YourProfileName', self::$static_textdomain),
								'keywords' => array('social media', 'bale', 'شبکه های اجتماعی', 'بله'),
							),
							array(
								'id'             => 'irantheme_aparat_social_url',
								'label'            => __(
									'آپارات',
									self::$static_textdomain
								),
								'description'    => __('نشانی اینترنتی آپارات', self::$static_textdomain),
								'type'            => 'text',
								'default'        => '',
								'placeholder'    => __('برای مثال: https://www.aparat.com/YourProfileName', self::$static_textdomain),
								'keywords' => array('social media', 'aparat', 'شبکه های اجتماعی', 'آپارات'),
							),
							array(
								'id'             => 'irantheme_igap_social_url',
								'label'            => __(
									'آیگپ',
									self::$static_textdomain
								),
								'description'    => __('نشانی اینترنتی آیگپ', self::$static_textdomain),
								'type'            => 'text',
								'default'        => '',
								'placeholder'    => __('برای مثال: https://www.profile.igap.net/YourProfileName', self::$static_textdomain),
								'keywords' => array('social media', 'igap', 'شبکه های اجتماعی', 'آیگپ'),
							),
						)
					),
					array(
						'title' => 'تنظیم SMTP',
						'id' => 'irantheme_smtp',
						'description'            => __('در صورتی که آشنایی کامل با مقادیر و پورت های SMTP دارید اقدام به تنظیم آنها در این زبانه کنید. پیشنهاد ما جهت تنظیم SMTP استفاده از افزونه های وردپرس است. بدون تنظیم SMTP شما قادر به ارسال ایمیل به خودتان یا کاربرانتان از طریق سایت نمی باشید. در صورتی که از افزونه های تنظیم SMTP استفاده می کنید این مقادیر را خالی بگذارید.', self::$static_textdomain),
						'icon' => 'magnifier',
						'fields' => array(
							array(
								'id'             => 'irantheme_smtp_set_host',
								'label'            => __(
									'نام هاست (Host)',
									self::$static_textdomain
								),
								'description'    => __('نام هاست مورد نظر را وارد کنید. برای مثال: smtp.example.com', self::$static_textdomain),
								'type'            => 'text',
								'default'        => '',
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('smtp', 'host', 'هاست')
							),
							array(
								'id'             => 'irantheme_smtp_set_sender',
								'label'            => __(
									'ایمیل ارسال کننده (From)',
									self::$static_textdomain
								),
								'description'    => __('ایمیل مورد نظر جهت ارسال پیام ها را تنظیم نمایید. برای مثال: example@test.com', self::$static_textdomain),
								'type'            => 'text',
								'default'        => '',
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('smtp', 'sender', 'from', 'ایمیل ارسال کننده', 'email', 'mail')
							),
							array(
								'id'             => 'irantheme_smtp_set_secure',
								'label'            => __(
									'پروتکل حفاظتی (SMTPSecure)',
									self::$static_textdomain
								),
								'description'    => __('در صورتی که از پروتکل حافظتی استفاده می کنید نوع آن را جهت اتصال به SMTP تنظیم نمایید. برای مثال: tls یا ssl', self::$static_textdomain),
								'type'            => 'text',
								'default'        => '',
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('smtp', 'protocol', 'secure', 'پروتکل')
							),
							array(
								'id'             => 'irantheme_smtp_set_port',
								'label'            => __(
									'پورت پروتکل (Port)',
									self::$static_textdomain
								),
								'description'    => __('در صورتی که از پروتکل حافظتی استفاده می کنید پورت آن را تنظیم نمایید. برای مثال: 465 برای ssl و 587 برای tls. پورت عادی 25 می باشد.', self::$static_textdomain),
								'type'            => 'text',
								'default'        => '',
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('smtp', 'protocol', 'secure', 'پروتکل')
							),
							array(
								'id'             => 'irantheme_smtp_set_auth',
								'label'            => __(
									'دسترسی حفاظت شده (SMTPAuth)',
									self::$static_textdomain
								),
								'description'    => __('در صورتی که دسترسی شما به ایمیلتان به صورت حفاظت شده می باشد، این مقدار را روشن بگذارید. برای مثال در جمیل ها باید مقدار Verification فعال باشد.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('smtp', 'verification', 'email', 'دسترسی حفاظت شده'),
							),
							array(
								'id'             => 'irantheme_smtp_set_heading',
								'label'            => __(
									'عنوان ایمیل های ارسالی (heading)',
									self::$static_textdomain
								),
								'description'    => __('در صورتی که علاقمند به تنظیم عنوان ایمیل های ارسالی می باشید این ورودی را وارد کنید. برای مثال: شرکت توسعه دهنده ایران تم', self::$static_textdomain),
								'type'            => 'text',
								'default'        => '',
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('smtp', 'title', 'عنوان ایمیل', 'email heading'),
							),
							array(
								'id'             => 'irantheme_smtp_set_username',
								'label'            => __(
									'نام کاربری (Username)',
									self::$static_textdomain
								),
								'description'    => __('جهت دسترسی به ایمیلتان وارد کردن این مقدار الزامی می باشد، معمولا نام کاربری همان نام ایمیل می باشد.', self::$static_textdomain),
								'type'            => 'text',
								'default'        => '',
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('smtp', 'username', 'نام کاربری', 'یوزرنیم'),
							),
							array(
								'id'             => 'irantheme_smtp_set_password',
								'label'            => __(
									'رمز ورود (Password)',
									self::$static_textdomain
								),
								'description'    => __('رمز ورود و دسترسی از طریق نام کاربری را وارد نمایید. برای مثال جهت گرفتن پسورد در ایمیل گوگل کافیست وارد تنظیمات جمیل شوید و پس از آن از طریق زبانه Security -> Step Verification -> App Passwords اقدام نمایید.', self::$static_textdomain),
								'type'            => 'password',
								'default'        => '',
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('smtp', 'password', 'نام کاربری', 'یوزرنیم'),
							),
						)
					),
				)
			);

			$settings['header'] = array(
				'title'                    => __('سربرگ (هدر)', self::$static_textdomain),
				'description'            => __('بالاترین قسمت سایت', self::$static_textdomain),
				'icon' => 'fa-bars-progress',
				'submenu'                => array(
					array(
						'title' => 'لوگو',
						'id' => 'irantheme_submenu_logo',
						'description'            => __('لوگو و نماد سایت', self::$static_textdomain),
						'icon' => 'fa-blog',
						'fields' => array(
							array(
								'id' => 'irantheme_logo_mode_radio',
								'label' => __('تنظیم حالت لوگو', self::$static_textdomain),
								'description' => __('شما می توانید حالت لوگوی خود را تنظیم نمایید.', self::$static_textdomain),
								'type' => 'radio',
								'options' => array(
									'text' => 'متن به جای لوگو',
									'svg' => 'لوگو Svg',
									'upload_mono' => 'بارگذاری لوگو ثابت',
									'upload_multi' => 'بارگذاری لوگو تغییر پذیر'
								),
								'default' => 'text', // Default value
								'keywords' => array('button', 'title', 'btn', 'text', 'دکمه', 'عنوان', 'تیتر'),
								'id_dependents_on' => array(
									'text' => array('irantheme_logo_text_size_number'),
									'svg' => array('irantheme_logo_svg_code_textarea', 'irantheme_logo_mobile_svg_checkbox', 'irantheme_logo_height_number'),
									'upload_mono' => array('irantheme_logo_replacement_text', 'irantheme_logo_height_number', 'irantheme_mono_logo_image', 'irantheme_logo_mobile_mono_checkbox'),
									'upload_multi' => array('irantheme_logo_replacement_text', 'irantheme_logo_height_number', 'irantheme_logo_black_image', 'irantheme_logo_white_image', 'irantheme_logo_mobile_switchable_checkbox'),
								),
								'id_dependents_off' => array(
									'text' => array('irantheme_logo_svg_code_textarea', 'irantheme_logo_mobile_svg_code_textarea', 'irantheme_logo_mobile_svg_checkbox', 'irantheme_logo_mobile_svg_height_number', 'irantheme_logo_mobile_mono_checkbox', 'irantheme_logo_black_image', 'irantheme_logo_white_image', 'irantheme_mono_logo_image', 'irantheme_logo_height_number', 'irantheme_logo_mobile_height_number', 'irantheme_logo_replacement_text', 'irantheme_logo_mobile_mono_image', 'irantheme_logo_mobile_switchable_checkbox', 'irantheme_logo_mobile_white_image', 'irantheme_logo_mobile_black_image'),
									'svg' => array('irantheme_logo_text_size_number', 'irantheme_logo_black_image', 'irantheme_logo_white_image', 'irantheme_logo_mobile_switchable_checkbox', 'irantheme_mono_logo_image', 'irantheme_logo_mobile_mono_checkbox', 'irantheme_logo_mobile_height_number', 'irantheme_logo_replacement_text', 'irantheme_logo_mobile_mono_image', 'irantheme_logo_mobile_white_image', 'irantheme_logo_mobile_black_image'),
									'upload_mono' => array('irantheme_logo_text_size_number', 'irantheme_logo_svg_code_textarea', 'irantheme_logo_mobile_svg_code_textarea', 'irantheme_logo_mobile_svg_checkbox', 'irantheme_logo_mobile_svg_height_number', 'irantheme_logo_black_image', 'irantheme_logo_white_image', 'irantheme_logo_mobile_switchable_checkbox', 'irantheme_logo_mobile_white_image', 'irantheme_logo_mobile_black_image'),
									'upload_multi' => array('irantheme_logo_text_size_number', 'irantheme_logo_svg_code_textarea', 'irantheme_logo_mobile_svg_code_textarea', 'irantheme_logo_mobile_svg_checkbox', 'irantheme_logo_mobile_svg_height_number', 'irantheme_mono_logo_image', 'irantheme_logo_mobile_mono_checkbox', 'irantheme_logo_mobile_mono_image')
								),
								'base_priority' => 'radio_base',
								'selector_priority' => 'radio_logo_mode',
								'z_index' => 0
							),
							array(
								'id'             => 'irantheme_logo_text_size_number',
								'label'            => __(
									'میزان فونت متن لوگو',
									self::$static_textdomain
								),
								'description'    => __('شما می توانید با تنظیم اندازه فونت متن لوگو، ابعاد متن لوگوی خود را تعیین کنید. (واحد px می باشد)', self::$static_textdomain),
								'type'            => 'number',
								'step' => 1,
								'default'        => 24,
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('اندازه لوگو', 'لوگو', 'برند', 'ابعاد لوگو', 'فونت', 'اندازه', 'سایز', 'سایز لوگو')
							),
							array(
								'id'             => 'irantheme_logo_svg_code_textarea',
								'label'            => __(
									'کد های svg',
									self::$static_textdomain
								),
								'description'    => __('لطفا کد های svg که در فایل، با فرمت svg قرار دارند را در این ورودی قرار دهید.', self::$static_textdomain),
								'type'            => 'textarea',
								'default'        => '',
								'placeholder'    => __('کد ها را وارد کنید', self::$static_textdomain),
								'keywords' => array('svg', 'کد', 'اس وی جی', 'svg کد', 'کد اس وی جی', 'code svg')
							),
							array(
								'id'             => 'irantheme_logo_mobile_svg_checkbox',
								'label'            => __(
									'لوگو مخصوص موبایل',
									self::$static_textdomain
								),
								'description'    => __('درصورتی که به لوگو svg متفاوت در حالت موبایل نیاز دارید این گزینه را فعال نمایید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('svg', 'برند', 'لوگو'),
								'id_dependents_on' => array('irantheme_logo_mobile_svg_code_textarea', 'irantheme_logo_mobile_svg_height_number'),
								'selector_priority' => 'checkbox_logo_svg',
								'base_priority' => 'checkbox_base',
								'z_index' => 0,
								'child_of_radio' => true
							),
							array(
								'id'             => 'irantheme_logo_mobile_svg_code_textarea',
								'label'            => __(
									'کد های svg لوگو موبایل',
									self::$static_textdomain
								),
								'description'    => __('لطفا کد های svg که در فایل، با فرمت svg قرار دارند را در این ورودی قرار دهید.', self::$static_textdomain),
								'type'            => 'textarea',
								'default'        => '',
								'placeholder'    => __('کد ها را وارد کنید', self::$static_textdomain),
								'keywords' => array('svg', 'کد', 'اس وی جی', 'svg کد', 'کد اس وی جی', 'code svg')
							),
							array(
								'id'             => 'irantheme_logo_mobile_svg_height_number',
								'label'            => __(
									'ارتفاع لوگو موبایل',
									self::$static_textdomain
								),
								'description'    => __('شما می توانید با تنظیم ارتفاع لوگو، اندازه لوگوی موبایل خود را تعیین کنید. (واحد px می باشد)', self::$static_textdomain),
								'type'            => 'number',
								'step' => 1,
								'default'        => 24,
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('اندازه لوگو', 'لوگو', 'برند', 'ارتفاع لوگو', 'ارتفاع')
							),
							array(
								'id' 			=> 'irantheme_mono_logo_image',
								'label'			=> __('لوگو', self::$static_textdomain),
								'description'	=> __('لطفا لوگوی خود را بارگذاری نمایید.', self::$static_textdomain),
								'type'			=> 'image',
								'default'		=> '',
								'placeholder'	=> '',
								'keywords' => array('لوگو', 'logo', 'برند')
							),
							array(
								'id' 			=> 'irantheme_logo_black_image',
								'label'			=> __('لوگو مشکی', self::$static_textdomain),
								'description'	=> __('لطفا جهت استفاده از لوگو در پس زمینه های سفید، لوگویی با رنگ مشکی آپلود نمایید.', self::$static_textdomain),
								'type'			=> 'image',
								'default'		=> '',
								'placeholder'	=> '',
								'keywords' => array('لوگو', 'مشکی', 'برند')
							),
							array(
								'id' 			=> 'irantheme_logo_white_image',
								'label'			=> __('لوگو سفید', self::$static_textdomain),
								'description'	=> __('لطفا جهت استفاده از لوگو در پس زمینه های مشکی، لوگویی با رنگ سفید آپلود نمایید.', self::$static_textdomain),
								'type'			=> 'image',
								'default'		=> '',
								'placeholder'	=> '',
								'keywords' => array('لوگو', 'سفید', 'برند', 'لوگو سفید')
							),
							array(
								'id'             => 'irantheme_logo_height_number',
								'label'            => __(
									'ارتفاع لوگو',
									self::$static_textdomain
								),
								'description'    => __('شما می توانید با تنظیم ارتفاع لوگو، اندازه لوگوی خود را تعیین کنید. (واحد px می باشد)', self::$static_textdomain),
								'type'            => 'number',
								'step' => 1,
								'default'        => 24,
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('اندازه لوگو', 'لوگو', 'برند', 'ارتفاع لوگو', 'ارتفاع')
							),
							array(
								'id'             => 'irantheme_logo_mobile_mono_checkbox',
								'label'            => __(
									'لوگو مخصوص موبایل',
									self::$static_textdomain
								),
								'description'    => __('درصورتی که به لوگو متفاوت در حالت موبایل نیاز دارید این گزینه را فعال نمایید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('svg', 'برند', 'لوگو'),
								'id_dependents_on' => array('irantheme_logo_mobile_mono_image', 'irantheme_logo_mobile_height_number'),
								'selector_priority' => 'checkbox_logo_upload_mono',
								'base_priority' => 'checkbox_base',
								'z_index' => 0,
								'child_of_radio' => true
							),
							array(
								'id' 			=> 'irantheme_logo_mobile_mono_image',
								'label'			=> __('لوگو موبایل', self::$static_textdomain),
								'description'	=> __('لطفا لوگوی خود را بارگذاری نمایید.', self::$static_textdomain),
								'type'			=> 'image',
								'default'		=> '',
								'placeholder'	=> '',
								'keywords' => array('لوگو', 'logo', 'برند')
							),
							array(
								'id'             => 'irantheme_logo_mobile_switchable_checkbox',
								'label'            => __(
									'لوگو مخصوص موبایل',
									self::$static_textdomain
								),
								'description'    => __('درصورتی که به لوگو متفاوت در حالت موبایل نیاز دارید این گزینه را فعال نمایید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('svg', 'برند', 'لوگو'),
								'id_dependents_on' => array('irantheme_logo_mobile_black_image', 'irantheme_logo_mobile_white_image', 'irantheme_logo_mobile_height_number'),
								'selector_priority' => 'checkbox_logo_upload_multi',
								'base_priority' => 'checkbox_base',
								'z_index' => 0,
								'child_of_radio' => true
							),
							array(
								'id' 			=> 'irantheme_logo_mobile_black_image',
								'label'			=> __('لوگو موبایل مشکی', self::$static_textdomain),
								'description'	=> __('لطفا جهت استفاده از لوگو در پس زمینه های سفید، لوگویی با رنگ مشکی آپلود نمایید.', self::$static_textdomain),
								'type'			=> 'image',
								'default'		=> '',
								'placeholder'	=> '',
								'keywords' => array('لوگو', 'مشکی', 'برند')
							),
							array(
								'id' 			=> 'irantheme_logo_mobile_white_image',
								'label'			=> __('لوگو موبایل سفید', self::$static_textdomain),
								'description'	=> __('لطفا جهت استفاده از لوگو در پس زمینه های مشکی، لوگویی با رنگ سفید آپلود نمایید.', self::$static_textdomain),
								'type'			=> 'image',
								'default'		=> '',
								'placeholder'	=> '',
								'keywords' => array('لوگو', 'سفید', 'برند', 'لوگو سفید')
							),
							array(
								'id'             => 'irantheme_logo_mobile_height_number',
								'label'            => __(
									'ارتفاع لوگو موبایل',
									self::$static_textdomain
								),
								'description'    => __('شما می توانید با تنظیم ارتفاع لوگو، اندازه لوگوی موبایل خود را تعیین کنید. (واحد px می باشد)', self::$static_textdomain),
								'type'            => 'number',
								'step' => 1,
								'default'        => 24,
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('اندازه لوگو', 'لوگو', 'برند', 'ارتفاع لوگو', 'ارتفاع')
							),
							array(
								'id'             => 'irantheme_logo_replacement_text',
								'label'            => __(
									'متن جایگزین',
									self::$static_textdomain
								),
								'description'    => __('در صورتی که هیچ کدام از لوگو های مورد نظر شما در کامپیوتر کاربر به هر علتی بارگذاری نشوند، متن جایگزین به جای لوگو نمایش داده خواهد شد. پیشنهاد می کنیم این ورودی را تکمیل کنید.', self::$static_textdomain),
								'type'            => 'text',
								'default'        => '',
								'placeholder'    => __('مثلا "نام سایت"', self::$static_textdomain)
							),
						)
					),
					array(
						'title' => 'نوار بالا (تاپ بار)',
						'id' => 'irantheme_header_topbar',
						'description'            => __('تنظیمات نوار بالای سایت (تاپ بار)', self::$static_textdomain),
						'icon' => 'fa-arrow-up-to-line',
						'fields' => array(
							array(
								'id'             => 'irantheme_topbar_checkbox',
								'label'            => __(
									'نمایش تاپ بار (نوار بالا)',
									self::$static_textdomain
								),
								'description'    => __('در صورت نیاز به نمایش تاپ بار می توانید این گزینه را فعال کنید.
								', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('menu', 'topbar', 'نوار', 'نوار بالا', 'تاپ بار'),
								'id_dependents_on' => array('irantheme_topbar_note_checkbox', 'irantheme_topbar_menus_checkbox', 'irantheme_topbar_bg_color_select', 'irantheme_topbar_base_color_select', 'irantheme_topbar_social_media_checkbox', 'irantheme_topbar_alignment_radio', 'irantheme_topbar_height_range', 'irantheme_topbar_font_size_range', 'irantheme_topbar_social_media_checkbox', 'irantheme_topbar_border_bottom_checkbox', 'irantheme_topbar_bg_transparent_checkbox'),
								'base_priority' => 'checkbox_base',
								'selector_priority' => 'checkbox_topbar',
								'z_index' => 0
							),
							array(
								'id'             => 'irantheme_topbar_base_color_select',
								'label'            => __(
									'رنگ پایه',
									self::$static_textdomain
								),
								'description'    => __('برای انتخاب رنگ پایه از لیست رنگ دلخواه خود را انتخاب کنید.رنگ محتواو پس زمینه بصورت پیش فرض بر اساس این رنگ تنظیم خواهد شد.', self::$static_textdomain),
								'type'            => 'select',
								'options'        => array('white' => 'سفید', 'dark' => 'مشکی'),
								'default'        => 'white',
								'keywords' => array('color', 'topbar', 'menu', 'category', 'منو', 'رنگ', 'تاپ بار', 'بار'),
							),
							array(
								'id'             => 'irantheme_topbar_bg_color_select',
								'label'            => __(
									'رنگ پس زمینه',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید رنگ پس زمینه مورد نطر خود را انتخاب کنید.در صورت انتخاب گزینه پیش فرض رنگ پس زمینه بر اساس رنگ پایه انتخاب شده در بالا تنظیم خواهد شد.', self::$static_textdomain),
								'type'            => 'select',
								'color_preview' => true,
								'options'        => Irantheme_Color_Static::get_all_colors_options(),
								'default'        => 'off',
								'keywords' => array('color', 'background', 'رنگ', 'پس زمینه'),
							),
							array(
								'id'             => 'irantheme_topbar_bg_transparent_checkbox',
								'label'            => __(
									'تاپ بار شیشه ای',
									self::$static_textdomain
								),
								'description'    => __('در صورت تمایل با فعال کردن این گزینه می توانید تاپ بار خود را شیشه ای کنید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('transparency', 'trancparent', 'topbar', 'opacity', 'شفاف', 'شفافیت', 'تاپ بار', 'رنگ', 'رنگ تاپ بار', 'پس زمینه'),
								'id_dependents_on' => array('irantheme_topbar_bg_transparency_range', 'irantheme_topbar_bg_blur_range'),
								'base_priority' => 'checkbox_base',
								'selector_priority' => 'checkbox_topbar',
								'z_index' => 1
							),
							array(
								'id'             => 'irantheme_topbar_bg_transparency_range',
								'label'            => __(
									'میزان شفافیت',
									self::$static_textdomain
								),
								'description'    => __('میزان شفافیت دلخواه خود را مشخص نمایید.', self::$static_textdomain),
								'type'            => 'range',
								'min' => 0,
								'max' => 100,
								'step' => 1,
								'default'        => 50,
								'placeholder'    => __('Placeholder text', self::$static_textdomain),
								'keywords' => array('transparency', 'opacity', 'شفافیت', 'شفاف', 'پس زمینه', 'تاپ بار'),
							),
							array(
								'id'             => 'irantheme_topbar_bg_blur_range',
								'label'            => __(
									'پس زمینه شیشه ای',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید پس زمینه ای شیشه ای و غبار آلود داشته باشید. (واحد بر اساس px پیکسل می باشد.)', self::$static_textdomain),
								'type'            => 'range',
								'min' => 0,
								'max' => 100,
								'step' => 1,
								'default'        => 50,
								'placeholder'    => __('Placeholder text', self::$static_textdomain),
								'keywords' => array('blur', 'opacity', 'شیشه ای', 'background galss', 'پس زمینه شیشه ای', 'هدر شیشه ای'),
							),
							array(
								'id'             => 'irantheme_topbar_alignment_radio',
								'label'            => __(
									'سبک نمایش',
									self::$static_textdomain
								),
								'description'    => __('شما می توانید از گزینه های بالا سبک  نمایش نوار بالا(تاپ بار) را انتخاب کنید.', self::$static_textdomain),
								'type'            => 'radio',
								'thumbnail_status' => true,
								'options'        => array(
									'right' => array('راست چین', 'topbar-right.png'),
									'menu-left' => array('فاصله بین عنصر(فهرست سمت چپ)', 'topbar-space-between-text-right.png'),
									'menu-right' => array('فاصله بین عنصر(فهرست سمت راست)', 'topbar-space-between-text-left.png')
								),
								'default'        => 'right',
								'keywords' => array('style', 'trigger', 'menu', 'action', 'category', 'فهرست', 'منو', 'دسکتاپ', 'سبک', 'منو بازشونده')
							),
							array(
								'id'             => 'irantheme_topbar_height_range',
								'label'            => __(
									'ارتفاع نوار بالا',
									self::$static_textdomain
								),
								'description'    => __('میزان ارتفاع نوار بالا را تنظیم نمایید.', self::$static_textdomain),
								'type'            => 'range',
								'min' => 2,
								'max' => 4,
								'step' => 0.1,
								'default'        => 2.5,
								'placeholder'    => __('Placeholder text', self::$static_textdomain),
								'keywords' => array('ارتفاع نوار بالا', 'ارتفاع تاپ بار', 'topbar height'),
							),
							array(
								'id'             => 'irantheme_topbar_font_size_range',
								'label'            => __(
									'اندازه فونت نوار بالا',
									self::$static_textdomain
								),
								'description'    => __('میزان اندازه فونت نوار بالا را تنظیم نمایید.', self::$static_textdomain),
								'type'            => 'range',
								'min' => 0.5,
								'max' => 1,
								'step' => 0.1,
								'default'        => 0.8,
								'placeholder'    => __('Placeholder text', self::$static_textdomain),
								'keywords' => array('اندازه فونت نوار بالا', 'اندازه فونت تاپ بار', 'topbar font size'),
							),
							array(
								'id'             => 'irantheme_topbar_note_checkbox',
								'label'            => __(
									'نمایش متن دلخواه',
									self::$static_textdomain
								),
								'description'    => __('در صورت نیاز به یادداشت اطلاعاتی به جز موارد زیر، می توانید این گزینه را فعال نموده و متن دلخواه خود را به تاپ بار اضافه نمایید.
								', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('menu', 'topbar', 'header', 'نوار', 'نوار بالا', 'تاپ بار', 'دلخواه', 'متن', 'توضیخات', 'متن دلخواه'),
								'id_dependents_on' => array('irantheme_topbar_first_note', 'irantheme_topbar_second_note', 'irantheme_topbar_first_note_icon', 'irantheme_topbar_second_note_icon'),
								'selector_priority' => 'checkbox_topbar',
								'z_index' => 2
							),
							array(
								'id'             => 'irantheme_topbar_first_note_icon',
								'label'            => __(
									'آیکون متن اول',
									self::$static_textdomain
								),
								'description'    => __('در صورت نیاز به استفاده از آیکون(نماد) خاصی در کنار متن می توانید از این گزینه استفاده نمایید.', self::$static_textdomain),
								'type'            => 'icon',
								'default'        => '',
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('آیکون', 'نوار بالا', 'تاپ بار', 'icon', 'topbar'),
							),
							array(
								'id'             => 'irantheme_topbar_first_note',
								'label'            => __(
									'متن اول',
									self::$static_textdomain
								),
								'description'    => __('متن دلخواه خود را اینجا وارد کنید.', self::$static_textdomain),
								'type'            => 'text',
								'default'        => '',
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('متن', 'نوار بالا', 'تاپ بار', 'header', 'topbar'),
							),
							array(
								'id'             => 'irantheme_topbar_second_note_icon',
								'label'            => __(
									'آیکون متن دوم',
									self::$static_textdomain
								),
								'description'    => __('در صورت نیاز به استفاده از آیکون(نماد) خاصی در کنار متن می توانید از این گزینه استفاده نمایید.', self::$static_textdomain),
								'type'            => 'icon',
								'default'        => '',
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('آیکون', 'نوار بالا', 'تاپ بار', 'icon', 'topbar'),
							),
							array(
								'id'             => 'irantheme_topbar_second_note',
								'label'            => __(
									'متن دوم',
									self::$static_textdomain
								),
								'description'    => __('متن دلخواه خود را اینجا وارد کنید.در صورت عدم نیاز، آن را خالی بگذارید.', self::$static_textdomain),
								'type'            => 'text',
								'default'        => '',
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('متن', 'نوار بالا', 'تاپ بار', 'header', 'topbar'),
							),
							array(
								'id'             => 'irantheme_topbar_menus_checkbox',
								'label'            => __(
									'نمایش فهرست ها',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید نمایش یا عدم نمایش فهرست انتخاب شده در بخش "نمایش => فهرست ها" را در نوار بالای سایت تعیین نمایید.
								', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'on',
								'keywords' => array('menu', 'topbar', 'header', 'نوار', 'نوار بالا', 'تاپ بار', 'منو', 'فهرست'),
								'id_dependents_on' => array('irantheme_topbar_menu_select'),
								'selector_priority' => 'checkbox_topbar',
								'z_index' => 3
							),
							array(
								'id'             => 'irantheme_topbar_menu_select',
								'label'            => __(
									'انتخاب منوی نوار بالا',
									self::$static_textdomain
								),
								'description'    => __('منوی مورد نظر خود را جهت نمایش انتخاب کنید. در صورتی که منویی مشاهده نمی کنید، می توانید به قسمت "نمایش -> فهرست ها" بروید و فهرست دلخواه خود را ایجاد نمایید.', self::$static_textdomain),
								'type'            => 'select',
								'options'        => Irantheme_Custom_Fields::get_all_menus(true),
								'default' => 'none',
								'keywords' => array('انتخاب منوی نوار بالا', 'topber menu select'),
								'placeholder' => __('محتوا خالی می باشد', self::$static_textdomain)
							),
							array(
								'id'             => 'irantheme_topbar_social_media_checkbox',
								'label'            => __(
									'نمایش شبکه های اجتماعی',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید نمایش یا عدم نمایش شناسه کاربری شبکه های اجتماعی در نوار بالای سایت را تعیین نمایید.
								</br>
								*جهت ثبت شناسه کاربری خود در هر یک از شبکه های اجتماعی به بخش "دیگر تنظیمات => شبکه های اجتماعی" مراجعه نمایید.
								', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'on',
								'keywords' => array('social', 'media', 'topbar', 'header', 'نوار', 'نوار بالا', 'تاپ بار'),
							),
							array(
								'id'             => 'irantheme_topbar_border_bottom_checkbox',
								'label'            => __(
									'مرز پایین تاپ بار',
									self::$static_textdomain
								),
								'description'    => __('با فعال کردن این گزینه، یگ خط باریک در حاشیه پایین تاپ بار نمایش داده می شود تا تمایز بین تاپ بار و هدر بهتر قابل مشاهده باشد.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('topbar', 'نوار', 'نوار بالا', 'تاپ بار'),
							),
						)
					),
					array(
						'title' => 'منو شناور',
						'id' => 'irantheme_side_nav_menu',
						'description'            => __('تنظیمات منو شناور سایت', self::$static_textdomain),
						'icon' => 'fa-sidebar',
						'fields' => array(
							array(
								'id'             => 'irantheme_navigation_position',
								'label'            => __(
									'موقعیت منو شناور ',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید موقعیت قرار گیری منو شناور  را مشخص کنید', self::$static_textdomain),
								'type'            => 'radio',
								'thumbnail_status' => true,
								'options'        => array('right' => array('راست', 'header-floating-right.png'), 'left' => array('چپ', 'header-floating-left.png')),
								'default'        => 'right',
								'keywords' => array('navigation', 'position', 'ناوبری', 'منو شناور', 'موقعیت')
							),
							array(
								'id'             => 'irantheme_navigation_width',
								'label'            => __(
									'عرض منو شناور',
									self::$static_textdomain
								),
								'description'    => __('در صورت تمایل می توانید عرض منو شناور را به میزان دلخواه خود تغییر دهید.(واحد اندازه گیری px می باشد)', self::$static_textdomain),
								'type'            => 'range',
								'min' => 250,
								'max' => 350,
								'step' => 1,
								'default'        => 300,
								'placeholder'    => __('Placeholder text', self::$static_textdomain),
								'keywords' => array('navigation', 'width', 'عرض منو شناور', 'اندازه', 'عرض', 'منوشناور')
							),
							array(
								'id'             => 'irantheme_navigation_bg_color_select',
								'label'            => __(
									'رنگ پس زمینه',
									self::$static_textdomain
								),
								'description'    => __('برای انتخاب رنگ پس زمینه منو شناور از لیست رنگ دلخواه خود را انتخاب کنید.', self::$static_textdomain),
								'type'            => 'select',
								'options'        => array('white' => 'سفید', 'dark' => 'مشکی'),
								'default'        => 'white',
								'keywords' => array('color', 'menu', 'naviagation', 'شناور', 'ناوبری', 'منو', 'رنگ', 'رنگ منو', 'پس زمینه'),
							),
							array(
								'id' 			=> 'irantheme_navigation_bg_image',
								'label'			=> __('تصویر پس زمینه', self::$static_textdomain),
								'description'	=> __('در صورت تمایل تصویر پس زمینه خود را بارگذاری نمایید.', self::$static_textdomain),
								'type'			=> 'image',
								'default'		=> '',
								'placeholder'	=> '',
								'keywords' => array('تصویر', 'navigation', 'menu', 'background', 'پس زمینه')
							),
							array(
								'id'             => 'irantheme_navigation_logo_checkbox',
								'label'            => __(
									'نمایش لوگو',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید، نمایش یا عدم نمایش لوگو در منو شناور را تعیین نمایید.
								</br>
								* نوع لوگو و تنظیمات آن بر اساس مقادیر اعمال شده در بخش "لوگو" اعمال می گردد.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'on',
								'keywords' => array('navigation', 'navigation', 'logo', 'menu', 'منو', 'شناور', 'ناوبری', 'لوگو'),
								'id_dependents_on' => array('irantheme_navigation_logo_height_number'),
								'base_priority' => 'checkbox_base',
								'selector_priority' => 'checkbox_navigation_logo',
								'z_index' => 0
							),
							array(
								'id'             => 'irantheme_navigation_logo_height_number',
								'label'            => __(
									'ارتفاع لوگو',
									self::$static_textdomain
								),
								'description'    => __('شما می توانید با تنظیم ارتفاع لوگو، اندازه لوگوی خود را تعیین کنید. (واحد px می باشد)', self::$static_textdomain),
								'type'            => 'number',
								'default'        => 24,
								'step' => 1,
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('اندازه لوگو', 'لوگو', 'برند', 'ارتفاع لوگو', 'ارتفاع', 'navigation', 'menu', 'ناوبری')
							),
							array(
								'id'             => 'irantheme_navigation_search_checkbox',
								'label'            => __(
									'نمایش جعبه جستجو',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید نمایش یا عدم نمایش جعبه جستجو در منو شناور را تعیین نمایید.
								', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('search', 'navigation', 'menu', 'جستجو', 'شناور', 'ساید بار'),
							),
							array(
								'id'             => 'irantheme_navigation_accessories_checkbox',
								'label'            => __(
									'نمایش امکانات',
									self::$static_textdomain
								),
								'description'    => __('امکانات قابل نمایش در منو شناور شامل: علاقه مندی ها و سبد خرید(در سایت های فروشگاهی) می باشد که با استفاده از این گزینه می توانید نمایش یا عدم نمایش آن را مشخص کنید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('menu', 'header', 'navigation', 'menu', 'منو', 'شناور', 'ساید بار', 'امکانات'),
							),
							array(
								'id'             => 'irantheme_navigation_biography_checkbox',
								'label'            => __(
									'معرفی نامه (بیوگرافی)',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید یک معرفی نامه(بیوگرافی) اجمالی به منو شناور اضافه کنید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('navigation', 'biography', 'menu', 'منو', 'شناور', 'ناوبری', 'معرفی نامه', 'بیوگرافی'),
								'id_dependents_on' => array('irantheme_navigation_biography_textarea', 'irantheme_navigation_biography_title', 'irantheme_navigation_biography_avatar'),
								'base_priority' => 'checkbox_base',
								'selector_priority' => 'checkbox_navigation_biography',
								'z_index' => 0
							),
							array(
								'id' 			=> 'irantheme_navigation_biography_avatar',
								'label'			=> __('آواتار', self::$static_textdomain),
								'description'	=> __('در صورت تمایل آواتار خود را بارگذاری نمایید.', self::$static_textdomain),
								'type'			=> 'image',
								'default'		=> '',
								'placeholder'	=> '',
								'keywords' => array('آواتار', 'navigation', 'menu', 'avatar', 'profile', 'پروفایل')
							),
							array(
								'id'             => 'irantheme_navigation_biography_title',
								'label'            => __(
									'عنوان',
									self::$static_textdomain
								),
								'description'    => __('عنوان انتخابی خود را اینجا وارد کنید.در صورت عدم نیاز، آن را خالی بگذارید.', self::$static_textdomain),
								'type'            => 'text',
								'default'        => '',
								'placeholder'    => __('عنوان انتخابی', self::$static_textdomain),
								'keywords' => array('متن دلخواه', 'بیوگرافی', 'معرفی نامه', 'عنوان', 'ناوبری', 'navigation', 'biography', 'title'),
							),
							array(
								'id'             => 'irantheme_navigation_biography_textarea',
								'label'            => __(
									'متن',
									self::$static_textdomain
								),
								'description'    => __('متن دلخواه خود را اینجا وارد کنید.', self::$static_textdomain),
								'type'            => 'textarea',
								'default'        => '',
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('متن دلخواه', 'بیوگرافی', 'معرفی نامه', 'پاراگراف', 'ناوبری', 'navigation', 'biography', 'paragraph')
							),
							array(
								'id'             => 'irantheme_navigation_menu_checkbox',
								'label'            => __(
									'نمایش فهرست ها',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید نمایش یا عدم نمایش فهرست انتخاب شده در بخش "نمایش => فهرست ها" را در منو شناور تعیین نمایید.
								', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'on',
								'id_dependents_on' => array('irantheme_navigation_menu_select'),
								'base_priority' => 'checkbox_base',
								'selector_priority' => 'checkbox_navigation_menu',
								'z_index' => 0,
								'keywords' => array('search', 'navigation', 'menu', 'منو', 'شناور', 'ساید بار', 'فهرست'),
							),
							array(
								'id'             => 'irantheme_navigation_menu_select',
								'label'            => __(
									'انتخاب منو',
									self::$static_textdomain
								),
								'description'    => __('منوی مورد نظر خود را جهت نمایش انتخاب کنید. در صورتی که منویی مشاهده نمی کنید، می توانید به قسمت "نمایش -> فهرست ها" بروید و فهرست دلخواه خود را ایجاد نمایید.', self::$static_textdomain),
								'type'            => 'select',
								'options'        => Irantheme_Custom_Fields::get_all_menus(true),
								'default' => 'none',
								'keywords' => array('انتخاب منو', 'navigation menu select'),
								'placeholder' => __('محتوا خالی می باشد', self::$static_textdomain)
							),
							array(
								'id'             => 'irantheme_navigation_social_media_checkbox',
								'label'            => __(
									'نمایش شبکه های اجتماعی',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید نمایش یا عدم نمایش شناسه کاربری شبکه های اجتماعی در منو شناور را تعیین نمایید.
								</br>
								*جهت ثبت شناسه کاربری خود در هر یک از شبکه های اجتماعی به بخش "دیگر تنظیمات => شبکه های اجتماعی" مراجعه نمایید.
								', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('social', 'media', 'menu', 'navigation', 'اجتماعی', 'شبکه های اجتماعی', 'ساید بار', 'فضای مجازی'),
							),
							array(
								'id'             => 'irantheme_navigation_profile_checkbox',
								'label'            => __(
									'نمایش حساب کاربری',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید نمایش یا عدم نمایش حساب کاربری در منو شناور را تعیین نمایید.
								', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('profile', 'account', 'menu', 'navigation', 'پروفایل', 'حساب کاربری', 'ساید بار'),
							),
						)
					),
					array(
						'title' => 'منو کشویی',
						'id' => 'irantheme_dropdown_nav_menu',
						'description'            => __('تنظیمات منو کشویی', self::$static_textdomain),
						'icon' => 'fa-list-dropdown',
						'fields' => array(
							array(
								'id'             => 'irantheme_dropdown_nav_menu_accessories_checkbox',
								'label'            => __(
									'نمایش امکانات',
									self::$static_textdomain
								),
								'description'    => __('امکانات قابل نمایش در منو کشویی شامل: جستجو در سایت، علاقه مندی ها، سبد خرید(در سایت های فروشگاهی)، حساب کاربری(در سایت های فروشگاهی) می باشد که با استفاده از این گزینه می توانید نمایش یا عدم نمایش آن را مشخص کنید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('menu', 'header', 'drop', 'down', 'drop down', 'منو', 'هدر', 'منو کشویی', 'امکانات'),
							),
							array(
								'id'             => 'irantheme_dropdown_nav_menu_menus_checkbox',
								'label'            => __(
									'نمایش فهرست ها',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید نمایش یا عدم نمایش فهرست انتخاب شده در بخش "نمایش => فهرست ها" را در منو کشویی تعیین نمایید.
								', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'on',
								'keywords' => array('menu', 'header', 'drop', 'down', 'drop down', 'منو', 'هدر', 'منو کشویی'),
								'id_dependents_on' => array('irantheme_dropdown_nav_menu_select'),
								'base_priority' => 'checkbox_base',
								'selector_priority' => 'checkbox_dropdown_nav_menu',
								'z_index' => 0,
							),
							array(
								'id'             => 'irantheme_dropdown_nav_menu_select',
								'label'            => __(
									'انتخاب منو',
									self::$static_textdomain
								),
								'description'    => __('منوی مورد نظر خود را جهت نمایش انتخاب کنید. در صورتی که منویی مشاهده نمی کنید، می توانید به قسمت "نمایش -> فهرست ها" بروید و فهرست دلخواه خود را ایجاد نمایید.', self::$static_textdomain),
								'type'            => 'select',
								'options'        => Irantheme_Custom_Fields::get_all_menus(true),
								'default' => 'none',
								'keywords' => array('انتخاب منو', 'dropdown nav menu select'),
								'placeholder' => __('محتوا خالی می باشد', self::$static_textdomain)
							),
							array(
								'id'             => 'irantheme_dropdown_nav_menu_social_media_checkbox',
								'label'            => __(
									'نمایش شبکه های اجتماعی',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید نمایش یا عدم نمایش شناسه کاربری شبکه های اجتماعی در منو کشویی را تعیین نمایید.
								</br>
								*جهت ثبت شناسه کاربری خود در هر یک از شبکه های اجتماعی به بخش "دیگر تنظیمات => شبکه های اجتماعی" مراجعه نمایید.
								', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('social', 'media', 'menu', 'header', 'drop', 'down', 'drop down', 'منو', 'هدر', 'منو کشویی', 'اجتماعی', 'شبکه های اجتماعی', 'فضای مجازی'),
							),
						)
					),
					array(
						'title' => 'منو تمام صفحه',
						'id' => 'irantheme_fullscreen_nav_menu',
						'description'            => __('تنظیمات منو تمام صفحه', self::$static_textdomain),
						'icon' => 'fa-square-list',
						'fields' => array(
							array(
								'id'             => 'irantheme_fullscreen_bg_color_select',
								'label'            => __(
									'رنگ پس زمینه',
									self::$static_textdomain
								),
								'description'    => __('برای انتخاب رنگ پس زمینه منو تمام صفحه از لیست رنگ دلخواه خود را انتخاب کنید.', self::$static_textdomain),
								'type'            => 'select',
								'options'        => array('white' => 'سفید', 'dark' => 'مشکی'),
								'default'        => 'white',
								'keywords' => array('color', 'full', 'menu', 'screen', 'fullscreen', 'تمام صفحه', 'منو', 'رنگ', 'پس زمینه'),
							),
							array(
								'id'             => 'irantheme_fullscreen_nav_menu_accessories_checkbox',
								'label'            => __(
									'نمایش امکانات',
									self::$static_textdomain
								),
								'description'    => __('امکانات قابل نمایش در منو تمام صفحه شامل: جستجو در سایت، علاقه مندی ها، سبد خرید(در سایت های فروشگاهی)، حساب کاربری(در سایت های فروشگاهی) می باشد که با استفاده از این گزینه می توانید نمایش یا عدم نمایش آن را مشخص کنید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('menu', 'header', 'drop', 'down', 'fullscreen', 'منو', 'هدر', 'تمام صفحه', 'امکانات'),
							),
							array(
								'id'             => 'irantheme_fullscreen_nav_menu_menus_checkbox',
								'label'            => __(
									'نمایش فهرست ها',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید نمایش یا عدم نمایش فهرست انتخاب شده در بخش "نمایش => فهرست ها" را در منو تمام صفحه تعیین نمایید.
								', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'on',
								'keywords' => array('menu', 'header', 'full', 'screen', 'fullscreen', 'منو', 'هدر', 'منو تمام صفحه'),
								'id_dependents_on' => array('irantheme_fullscreen_nav_menu_select'),
								'base_priority' => 'checkbox_base',
								'selector_priority' => 'checkbox_fullscreen_nav_menu',
								'z_index' => 0,
							),
							array(
								'id'             => 'irantheme_fullscreen_nav_menu_select',
								'label'            => __(
									'انتخاب منو',
									self::$static_textdomain
								),
								'description'    => __('منوی مورد نظر خود را جهت نمایش انتخاب کنید. در صورتی که منویی مشاهده نمی کنید، می توانید به قسمت "نمایش -> فهرست ها" بروید و فهرست دلخواه خود را ایجاد نمایید.', self::$static_textdomain),
								'type'            => 'select',
								'options'        => Irantheme_Custom_Fields::get_all_menus(true),
								'default' => 'none',
								'keywords' => array('انتخاب منو', 'fullscreen_nav menu select'),
								'placeholder' => __('محتوا خالی می باشد', self::$static_textdomain)
							),
							array(
								'id'             => 'irantheme_fullscreen_nav_menu_social_media_checkbox',
								'label'            => __(
									'نمایش شبکه های اجتماعی',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید نمایش یا عدم نمایش شناسه کاربری شبکه های اجتماعی در منو تمام صفحه را تعیین نمایید.
								</br>
								*جهت ثبت شناسه کاربری خود در هر یک از شبکه های اجتماعی به بخش "دیگر تنظیمات => شبکه های اجتماعی" مراجعه نمایید.
								', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('social', 'media', 'menu', 'header', 'full', 'screen', 'fullscreen', 'منو', 'هدر', 'منو تمام صفحه', 'اجتماعی', 'شبکه های اجتماعی', 'فضای مجازی'),
							),
						)
					),
					array(
						'title' => 'صفحه آرایی',
						'id' => 'irantheme_header_style',
						'description'            => __('تنظیمات چیدمان و سبک هدر', self::$static_textdomain),
						'icon' => 'fa-paintbrush-pencil',
						'fields' => array(
							array(
								'id'             => 'irantheme_header_base_color_select',
								'label'            => __(
									'رنگ پایه',
									self::$static_textdomain
								),
								'description'    => __('برای تعیین رنگ پس زمینه هدر، از لیست رنگ دلخواه خود را انتخاب کنید.', self::$static_textdomain),
								'type'            => 'select',
								'options'        => array('white' => 'سفید', 'dark' => 'مشکی'),
								'default'        => 'white',
								'keywords' => array('color', 'header', 'topbar', 'category', 'رنگ', 'رنگ هدر', 'پس زمینه', 'هدر')
							),
							array(
								'id'             => 'irantheme_header_bg_color_select',
								'label'            => __(
									'رنگ پس زمینه',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید رنگ پس زمینه مورد نطر خود را انتخاب کنید.در صورت انتخاب گزینه پیش فرض رنگ پس زمینه بر اساس رنگ پایه انتخاب شده در بالا تنظیم خواهد شد.', self::$static_textdomain),
								'type'            => 'select',
								'color_preview' => true,
								'options'        => Irantheme_Color_Static::get_all_colors_options(),
								'default'        => 'off',
								'keywords' => array('color', 'background', 'رنگ', 'پس زمینه'),
							),
							array(
								'id'             => 'irantheme_header_border_mode',
								'label'            => __(
									'استایل مرز هدر',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از گزینه های سایه (مرز بندی با سایه) و یا خط صاف (مرز بندی با خط های صاف) می توانید استایل مورد نظر خود را برای هدر انتخاب کنید.', self::$static_textdomain),
								'type'            => 'radio',
								'options'        => array('normal' => 'ساده', 'shadow' => 'سایه', 'border' => 'خط صاف'),
								'default'        => 'normal',
								'keywords' => array('استایل مرز هدر', 'header border mode', 'استایل سایه', 'استایل خط صاف')
							),
							array(
								'id'             => 'irantheme_header_bg_transparent_checkbox',
								'label'            => __(
									'هدر شیشه ای',
									self::$static_textdomain
								),
								'description'    => __('در صورت تمایل با فعال کردن این گزینه می توانید هدر خود را شیشه ای کنید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('transparency', 'trancparent', 'header', 'opacity', 'شفاف', 'شفافیت', 'هدر', 'رنگ', 'رنگ هدر', 'پس زمینه'),
								'id_dependents_on' => array('irantheme_header_bg_transparency_range', 'irantheme_header_bg_blur_range'),
								'base_priority' => 'checkbox_base',
								'selector_priority' => 'checkbox_tranparent',
								'z_index' => 0
							),
							array(
								'id'             => 'irantheme_header_bg_transparency_range',
								'label'            => __(
									'میزان شفافیت',
									self::$static_textdomain
								),
								'description'    => __('میزان شفافیت دلخواه خود را مشخص نمایید.', self::$static_textdomain),
								'type'            => 'range',
								'min' => 0,
								'max' => 100,
								'step' => 1,
								'default'        => 50,
								'placeholder'    => __('Placeholder text', self::$static_textdomain),
								'keywords' => array('transparency', 'opacity', 'شفافیت', 'شفاف', 'پس زمینه', 'هدر'),
							),
							array(
								'id'             => 'irantheme_header_bg_blur_range',
								'label'            => __(
									'پس زمینه شیشه ای',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید پس زمینه ای شیشه ای و غبار آلود داشته باشید. (واحد بر اساس px پیکسل می باشد.)', self::$static_textdomain),
								'type'            => 'range',
								'min' => 0,
								'max' => 100,
								'step' => 1,
								'default'        => 50,
								'placeholder'    => __('Placeholder text', self::$static_textdomain),
								'keywords' => array('blur', 'opacity', 'شیشه ای', 'background galss', 'پس زمینه شیشه ای', 'هدر شیشه ای'),
							),
							array(
								'id'             => 'irantheme_header_height',
								'label'            => __(
									' ارتفاع هدر',
									self::$static_textdomain
								),
								'description'    => __('در صورت تمایل می توانید ارتفاع هدر را به میزان دلخواه خود تغییر دهید.', self::$static_textdomain),
								'type'            => 'range',
								'min' => 30,
								'max' => 70,
								'step' => 1,
								'default'        => 50,
								'placeholder'    => __('Placeholder text', self::$static_textdomain),
								'keywords' => array('header', 'heght', 'ارتفاع هدر', 'اندازه', 'ارتفاع', 'هدر')
							),
							array(
								'id'             => 'irantheme_header_full_width',
								'label'            => __(
									'هدر وسیع',
									self::$static_textdomain
								),
								'description'    => __('با فعال شدن این گزینه، عرض هدر محدود به نگه دارنده داخلی سایت خواهد شد.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('menu', 'header', 'width', 'عرض', 'تمام', 'هدر', 'سایز', 'منو', 'اندازه'),
								'id_dependents_off' => array('irantheme_header_width_limit'),
								'base_priority' => 'checkbox_base',
								'selector_priority' => 'checkbox_header_limit',
								'z_index' => 0
							),
							array(
								'id'             => 'irantheme_header_width_limit',
								'label'            => __(
									'محدودیت عرض هدر',
									self::$static_textdomain
								),
								'description'    => __('در صورت تمایل با فعال کردن این گزینه، می توانید عرض عناصر داخل هدر را تمام صفحه یا وسیع کنید، در غیر اینصورت عرض عناصر هدر با کادر نگه دارنده پیش فرض سایت تنظیم می شود.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('محدودیت عرض هدر', 'header width limit container', 'header limit'),
								'note' => __('این قابلیت در هدر چسبان کاربرد ندارد.', self::$static_textdomain),
								'id_dependents_on' => array('irantheme_header_radius_top_range,irantheme_header_radius_bottom_range'),
								'selector_priority' => 'checkbox_header_limit',
								'z_index' => 1
							),
							array(
								'id'             => 'irantheme_header_radius_top_range',
								'label'            => __(
									'گرد کردن زوایای بالای هدر',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این امکان شما می توانید میزان گردی و خمیدگی زاوایای بالای هدر را تنظیم نمایید.', self::$static_textdomain),
								'type'            => 'range',
								'min' => 0,
								'max' => 2.5,
								'step' => 0.1,
								'default'        => 0,
								'placeholder'    => __('Placeholder text', self::$static_textdomain),
								'keywords' => array('transparency', 'opacity', 'شفافیت', 'شفاف', 'پس زمینه', 'تاپ بار'),
							),
							array(
								'id'             => 'irantheme_header_radius_bottom_range',
								'label'            => __(
									'گرد کردن زوایای پایین هدر',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این امکان شما می توانید میزان گردی و خمیدگی زاوایای پایین هدر را تنظیم نمایید.', self::$static_textdomain),
								'type'            => 'range',
								'min' => 0,
								'max' => 2.5,
								'step' => 0.1,
								'default'        => 1,
								'placeholder'    => __('Placeholder text', self::$static_textdomain),
								'keywords' => array('transparency', 'opacity', 'شفافیت', 'شفاف', 'پس زمینه', 'تاپ بار'),
							),
							array(
								'id'             => 'irantheme_header_fixed_checkbox',
								'label'            => __(
									'هدر ثابت',
									self::$static_textdomain
								),
								'description'    => __('با فعال کردن این گزینه، هدر به صورت ثابت در بالای صفحه بر روی عناصر قرار می گیرد.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('sticky', 'topbar', 'header', 'هدر', 'ثابت', 'چسبان'),
								'id_dependents_on' => array('irantheme_header_sticky_checkbox'),
								'base_priority' => 'checkbox_base',
								'selector_priority' => 'checkbox_header_sticky',
								'z_index' => 0
							),
							array(
								'id'             => 'irantheme_header_sticky_checkbox',
								'label'            => __(
									'هدر چسبان',
									self::$static_textdomain
								),
								'description'    => __('با فعال کردن این گزینه، هنگام پیمایش(اسکرول) صفحه، هدر هم به حرکت در می آید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('sticky', 'topbar', 'header', 'هدر', 'ثابت', 'چسبان'),
								'id_dependents_on' => array('irantheme_header_bg_color_on_scroll_checkbox', 'irantheme_header_shrink_on_scroll_checkbox'),
								'selector_priority' => 'checkbox_header_sticky',
								'z_index' => 1
							),
							array(
								'id'             => 'irantheme_header_bg_color_on_scroll_checkbox',
								'label'            => __(
									'تغییر رنگ پس زمینه هدر هنگام پیمایش',
									self::$static_textdomain
								),
								'description'    => __('با فعال کردن این گزینه، رنگ پس زمینه هدر هنگام پیمایش(اسکرول) صفحه به رنگ دلخواه شما تفییر می کند.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('background', 'color', 'trancparent', 'header', 'هدر', 'رنگ', 'رنگ هدر', 'پس زمینه'),
								'id_dependents_on' => array('irantheme_header_bg_color_on_scroll_select'),
								'selector_priority' => 'checkbox_header_sticky',
								'z_index' => 2
							),
							array(
								'id'             => 'irantheme_header_bg_color_on_scroll_select',
								'label'            => __(
									'رنگ پس زمینه هدر در هنگام پیمایش',
									self::$static_textdomain
								),
								'description'    => __('برای تعیین رنگ پس زمینه هدر در هنگام پیمایش(اسکرول)،  رنگ دلخواه خود را انتخاب کنید.', self::$static_textdomain),
								'type'            => 'select',
								'options'        => array('white' => 'سفید', 'dark' => 'مشکی'),
								'default'        => 'white',
								'keywords' => array('color', 'header', 'topbar', 'category', 'رنگ', 'رنگ هدر', 'پس زمینه', 'هدر'),
							),
							array(
								'id'             => 'irantheme_header_shrink_on_scroll_checkbox',
								'label'            => __(
									'کوچک شدن هدر هنگام پیمایش',
									self::$static_textdomain
								),
								'description'    => __('با فعال کردن این گزینه، ارتفاع هدر هنگام پیمایش(اسکرول) صفحه کوچک می شود.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('topbar', 'header', 'shrink', 'height', 'ارتفاع', 'هدر', 'سایز', 'کوچک', 'اندازه'),
							),
							array(
								'id'             => 'irantheme_menu_angle_icon_checkbox',
								'label'            => __(
									'آیکون زیر مجموعه ها',
									self::$static_textdomain
								),
								'description'    => __('با استفاده ار این گزینه می توانید نمایش و یا عدم نمایش آیکون مخصوص فهرست های دارای زیر منو را تعیین کنید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'on',
								'keywords' => array('topbar', 'header', 'icon', 'angle', 'منو', 'زیر منو', 'فهرست'),
							),
							array(
								'id'             => 'irantheme_submenu_bg_color_checkbox',
								'label'            => __(
									'انتخاب دستی رنگ زیر منو',
									self::$static_textdomain
								),
								'description'    => __('با فعال کردن این قابلیت شما می توانید رنگ زیر منو ها را بصورت دستی از بین رنگ های پیش فرض انتخاب نمایید.
								<br>
								*در صورت غیرفعال(خاموش) بودن این گزینه، رنگ پس زمینه منو ها بصورت خودکار متناسب با رنگ هدر سایت تنظیم می گردد.
								', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('color', 'submenu', 'menu', 'topbar', 'category', 'زیر منو',  'منو', 'رنگ', 'رنگ منو', 'پس زمینه'),
								'id_dependents_on' => array('irantheme_submenu_bg_color_select'),
								'base_priority' => 'checkbox_base',
								'selector_priority' => 'checkbox_submenu_color',
								'z_index' => 0
							),
							array(
								'id'             => 'irantheme_submenu_bg_color_select',
								'label'            => __(
									'رنگ پس زمینه زیر منو',
									self::$static_textdomain
								),
								'description'    => __('برای انتخاب رنگ پس زمینه زیر منو ها از لیست رنگ دلخواه خود را انتخاب کنید.', self::$static_textdomain),
								'type'            => 'select',
								'options'        => array('white' => 'سفید', 'dark' => 'مشکی'),
								'default'        => 'white',
								'keywords' => array('color', 'submenu', 'menu', 'topbar', 'category', 'زیر منو', 'منو', 'رنگ', 'رنگ منو', 'پس زمینه'),
							),
						)
					),
					array(
						'title' => 'پیکربندی',
						'id' => 'irantheme_header_config_nav_menu',
						'description'            => __('پیکربندی هدر سایت', self::$static_textdomain),
						'icon' => 'fa-gears',
						'fields' => array(
							array(
								'id'             => 'irantheme_header_alignment_radio',
								'label'            => __(
									'چیدمان هدر',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید چیدمان هدر را مشخص کنید.', self::$static_textdomain),
								'type'            => 'radio',
								'thumbnail_status' => true,
								'options'        => array('column-three' => array('سه ستون', 'header-right.png'), 'right' => array('راست چین', 'header-right.png'), 'left' => array('چپ چین', 'header-left.png'), 'center' => array('تراز وسط', 'header-center.png'), 'center-split' => array('فاصله دور عناصر', 'header-center-split.png'), 'center-double' => array('فاصله بین عناصر', 'header-center-double.png'), 'justify' => array('فاصله یکسان', 'header-justify.png'), 'navigation' => array('شناور', 'header-floating-right.png'), 'toggle-menu' => array('منو بازشونده', 'toggle-menu.png')),
								'default'        => 'right',
								'keywords' => array('header', 'position', 'ناوبری', 'هدر', 'چیدمان', 'alignment'),
								'id_dependents_on' => array(
									'toggle-menu' => array('irantheme_desktop_trigger_menu_mode'),
								),
								'id_dependents_off' => array(
									'column-three' => array('irantheme_desktop_trigger_menu_mode'),
									'right' => array('irantheme_desktop_trigger_menu_mode'),
									'left' => array('irantheme_desktop_trigger_menu_mode'),
									'center' => array('irantheme_desktop_trigger_menu_mode'),
									'center-split' => array('irantheme_desktop_trigger_menu_mode'),
									'justify' => array('irantheme_desktop_trigger_menu_mode'),
									'navigation' => array('irantheme_desktop_trigger_menu_mode'),
								),
								'base_priority' => 'radio_base',
								'selector_priority' => 'radio_header_alignment',
								'z_index' => 0
							),
							array(
								'id'             => 'irantheme_desktop_trigger_menu_mode',
								'label'            => __(
									'سبک نمایش منو دسکتاپ',
									self::$static_textdomain
								),
								'description'    => __('شما می توانید از گزینه های بالا سبک  نمایش منوی باز شونده در حالت دسکتاپ را انتخاب کنید.', self::$static_textdomain),
								'type'            => 'radio',
								'thumbnail_status' => true,
								'options'        => array(
									'navigation' => array('منو شناور', 'header-floating-right.png'),
									'fullscreen' => array('منو تمام صفحه', 'navbar-full-screen.png')
								),
								'default'        => 'navigation',
								'keywords' => array('style', 'trigger', 'menu', 'action', 'category', 'فهرست', 'منو', 'دسکتاپ', 'سبک', 'منو بازشونده')
							),
							array(
								'id'             => 'irantheme_header_main_menu_select',
								'label'            => __(
									'انتخاب منو اصلی هدر',
									self::$static_textdomain
								),
								'description'    => __('منوی مورد نظر خود را جهت نمایش انتخاب کنید. در صورتی که منویی مشاهده نمی کنید، می توانید به قسمت "نمایش -> فهرست ها" بروید و فهرست دلخواه خود را ایجاد نمایید.', self::$static_textdomain),
								'type'            => 'select',
								'options'        => Irantheme_Custom_Fields::get_all_menus(true),
								'default' => 'none',
								'keywords' => array('انتخاب منو اصلی هدر', 'header main menu select'),
								'placeholder' => __('محتوا خالی می باشد', self::$static_textdomain)
							),
							array(
								'id'             => 'irantheme_mobile_trigger_menu_mode',
								'label'            => __(
									'سبک نمایش منو موبایل',
									self::$static_textdomain
								),
								'description'    => __('شما می توانید از گزینه های بالا سبک  نمایش منوی باز شونده در حالت موبایل را انتخاب کنید.', self::$static_textdomain),
								'type'            => 'radio',
								'thumbnail_status' => true,
								'options'        => array(
									'navigation' => array('منو شناور', 'header-floating-right.png'),
									'fullscreen' => array('منو تمام صفحه', 'navbar-full-screen.png'),
									'dropdown' => array('منو کشویی', 'navbar-dropdown.png')
								),
								'default'        => 'navigation',
								'keywords' => array('style', 'trigger', 'menu', 'action', 'category', 'فهرست', 'منو', 'دسکتاپ', 'سبک', 'منو بازشونده')
							),
							array(
								'id'             => 'irantheme_header_secondary_menu_checkbox',
								'label'            => __(
									'نمایش منو ثانویه هدر',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید نمایش یا عدم نمایش فهرست ثانویه انتخاب شده در بخش "نمایش => فهرست ها" را در هدر تعیین نمایید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('نمایش منو ثانویه', 'display secondary menu'),
								'base_priority' => 'checkbox_base',
								'id_dependents_on' => array('irantheme_header_secondary_menu_select'),
								'selector_priority' => 'checkbox_header_secondary',
								'z_index' => 0
							),
							array(
								'id'             => 'irantheme_header_secondary_menu_select',
								'label'            => __(
									'انتخاب منوی ثانویه هدر',
									self::$static_textdomain
								),
								'description'    => __('منوی مورد نظر خود را جهت نمایش انتخاب کنید. در صورتی که منویی مشاهده نمی کنید، می توانید به قسمت "نمایش -> فهرست ها" بروید و فهرست دلخواه خود را ایجاد نمایید.', self::$static_textdomain),
								'type'            => 'select',
								'options'        => Irantheme_Custom_Fields::get_all_menus(true),
								'default' => 'none',
								'keywords' => array('انتخاب منوی ثانویه هدر', 'header secondary select'),
								'placeholder' => __('محتوا خالی می باشد', self::$static_textdomain)
							),
							array(
								'id'             => 'irantheme_desktop_tool_multiple_checkboxes',
								'label'            => __(
									'امکانات در حالت دسکتاپ',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید نمایش و یا عدم نمایش امکانات در هدر (حالت دسکتاپ) را مشخص نمایید.', self::$static_textdomain),
								'type'            => 'checkbox_multi',
								'options'        => array('search' => 'جستجو', 'wishlist' => 'علاقه مندی ها', 'basket' => 'سبد خرید', 'profile' => 'حساب کاربری'),
								'default'        => 'search,profile',
								'keywords' => array('basket', 'card', 'buy', 'menu', 'header', 'خرید', 'سبد', 'سبد خرید', 'wishlist', 'favorite', 'علاقه', 'علاقه مندی ها', 'لایک', 'search', 'find', 'جستجو', 'یافتن', 'account', 'profile', 'user', 'user account', 'حساب کاربری', 'پروفایل', 'کاربر')
							),
							array(
								'id'             => 'irantheme_simple_profile_checkbox',
								'label'            => __(
									'آیکن کاربر ساده',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید آیکن کاربر ساده ای را جایگزین حساب کاربری در هدر کنید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('mini profile', 'simple profile icon', 'آیکن حساب کاربری ساده', 'حساب کاربری', 'پروفایل حساب کاربری', 'حساب کاربری ساده'),
								'id_dependents_on' => array(''),
								'id_dependents_off' => array('irantheme_profile_tool_multiple_checkboxes'),
								'base_priority' => 'checkbox_base',
								'selector_priority' => 'checkbox_simple_profile',
								'z_index' => 0
							),
							array(
								'id'             => 'irantheme_profile_tool_multiple_checkboxes',
								'label'            => __(
									'جزئیات حساب کاربری',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این بخش می توانید نمایش یا عدم نمایش جزئیات حساب کاربری در حالت دسکتاپ را تعیین نمایید.', self::$static_textdomain),
								'type'            => 'checkbox_multi',
								'options'        => array('profile_name' => 'نام نمایشی', 'profile_email' => 'ایمیل', 'profile_more' => 'لنگر'),
								'default'        => 'profile_name,profile_email,profile_more',
								'keywords' => array('account', 'profile', 'user', 'user account', 'حساب کاربری', 'پروفایل', 'کاربر')
							),
							array(
								'id'             => 'irantheme_overwrite_setting_mobile_tool_checkbox',
								'label'            => __(
									'اعمال تغییرات متفاوت در حالت موبایل',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید تنظیمات نمایش و یا عدم نمایش امکانات، که در بالا مشخص کرده اید را روی حالت نمایش در موبایل نیز اعمال نمایید.درصورت نیاز به اعمال تنظیمات متفاوت در حالت نمایش موبایل، این گزینه را "روشن" کرده و از طریق گزینه های نمایش داده شده در زیر، تنظیمات مدنظر خود برای نمایش در موبایل را اعمال نمایید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('mobile', 'menu', 'header', 'موبایل', 'حالت', 'نمایش در موبایل'),
								'id_dependents_on' => array('irantheme_mobile_tool_multiple_checkboxes'),
								'base_priority' => 'checkbox_base',
								'selector_priority' => 'checkbox_mobile_tool',
								'z_index' => 0
							),
							array(
								'id'             => 'irantheme_mobile_tool_multiple_checkboxes',
								'label'            => __(
									'امکانات در حالت موبایل',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید نمایش و یا عدم نمایش امکانات در هدر (حالت موبایل) را مشخص نمایید.', self::$static_textdomain),
								'type'            => 'checkbox_multi',
								'options'        => array('search' => 'جستجو', 'wishlist' => 'علاقه مندی ها', 'basket' => 'سبد خرید', 'profile' => 'حساب کاربری'),
								'default'        => 'search,profile',
								'keywords' => array('basket', 'card', 'buy', 'menu', 'header', 'خرید', 'سبد', 'سبد خرید', 'wishlist', 'favorite', 'علاقه', 'علاقه مندی ها', 'لایک', 'search', 'find', 'جستجو', 'یافتن', 'account', 'profile', 'user', 'user account', 'حساب کاربری', 'پروفایل', 'کاربر'),
							),
							array(
								'id'             => 'irantheme_nav_menu_social_media_checkbox',
								'label'            => __(
									'نمایش شبکه های اجتماعی',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید نمایش یا عدم نمایش شناسه کاربری شبکه های اجتماعی در هدر را تعیین نمایید.
								</br>
								*جهت ثبت شناسه کاربری خود در هر یک از شبکه های اجتماعی به بخش "دیگر تنظیمات => شبکه های اجتماعی" مراجعه نمایید.
								', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('social', 'media', 'menu', 'header', 'منو', 'هدر', 'اجتماعی', 'شبکه های اجتماعی', 'فضای مجازی'),
							),
						)
					),
					array(
						'title' => 'جستجو',
						'id' => 'irantheme_searchbox',
						'description'            => __('تنظیمات جعبه جستجو', self::$static_textdomain),
						'icon' => 'fa-magnifying-glass',
						'fields' => array(
							array(
								'id'             => 'irantheme_ajax_search_checkbox',
								'label'            => __(
									'جستجوی زنده (ایجکس)',
									self::$static_textdomain
								),
								'description'    => __('در صورت فعال بودن این قابلیت جستجو بصورت زنده انجام و نتایج در همان جعبه جستجو نمایش داده می شود.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('جستجوی زنده (ایجکس)', 'search ajax'),
								'id_dependents_on' => array('irantheme_search_suggestions_checkbox', 'irantheme_search_suggestions_image'),
								'base_priority' => 'checkbox_base',
								'selector_priority' => 'checkbox_ajax_search',
								'z_index' => 0
							),
							array(
								'id'             => 'irantheme_search_suggestions_checkbox',
								'label'            => __(
									'نمایش جزئیات جستجو',
									self::$static_textdomain
								),
								'description'    => __('با فعال کردن این قابلیت، می توانید دسته بندی محصولات و جستجو های پرطرفدار را نمایش دهید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('نمایش جزئیات جستجو', 'جستجو های پر طرفدار', 'search suggestions'),
							),
							array(
								'id' 			=> 'irantheme_search_suggestions_image',
								'label'			=> __('بنر جزئیات جستجو', self::$static_textdomain),
								'description'	=> __('در صورت تمایل بنر جزئیات جستجو خود را بارگذاری نمایید. این بنر در زیبایی اثر بسزایی دارد.', self::$static_textdomain),
								'type'			=> 'image',
								'default'		=> '',
								'placeholder'	=> '',
								'keywords' => array('بنر جزئیات جستجو', 'search suggestions image', 'تصویر جزئیات جستجو')
							),
						),
					),
				)
			);
			// Header configuration fields data
			$settings['page'] = array(
				'title'                    => __('تنظیم برگه ها', self::$static_textdomain),
				'icon' => 'fa-memo',
				'submenu'                => array(
					// Get banner configuration fields
					self::get_irregular_banner_fields('irantheme_page', array('type')),
					// Get sidebar configuration
					self::get_blog_archive_content_fields('irantheme_page', array('content', 'sidebar'), true),
					// Get visual configuration fields
					// self::get_blog_archive_visual_fields('irantheme_page', array('details-color', 'radius')),
					// Get background configuration fields
					// self::background_fields_generator('irantheme_page',array('image', 'color', 'mask', 'parallax'), '', 'e9ecef', false, 'off', 'dark', 0.5, 'on'),
				)
			);
			// Header configuration fields data
			$settings['single'] = array(
				'title'                    => __('صفحه ادامه مطلب', self::$static_textdomain),
				'icon' => 'fa-file-lines',
				'submenu'                => array(
					// Get banner configuration fields
					self::get_irregular_banner_fields('irantheme_single', array('type')),
					// Get sidebar configuration
					self::get_blog_archive_content_fields('irantheme_single', array('content', 'post-details', 'sidebar'), true),
				)
			);
			// Archive configuration fields data
			$settings['blog'] = array(
				'title'                    => __('آرشیو و وبلاگ', self::$static_textdomain),
				'icon' => 'fa-folder-grid',
				'submenu'                => array(
					// Get banner configuration fields
					self::get_irregular_banner_fields('irantheme_blog', array('background', 'type', 'title')),
					// Get content configuration fields
					self::get_blog_archive_content_fields('irantheme_blog', array('content', 'post-number', 'post-details', 'sidebar', 'column', 'layout', 'background', 'height', 'category-toggle')),
					// Get visual configuration fields
					self::get_blog_archive_visual_fields('irantheme_blog', array('base-color', 'details-color', 'radius')),
					// Get background configuration fields
					self::background_fields_generator('irantheme_blog', array('image', 'color', 'mask', 'parallax'), '', 'e9ecef', false, 'off', 'dark', 0.5, 'on'),
				)
			);
			// Header configuration fields data
			$settings['newsletter'] = array(
				'title'                    => __('خبرنامه', self::$static_textdomain),
				'icon' => 'fa-envelope-open-text',
				'submenu'                => array(
					array(
						'title' => 'ایمیل اعضا',
						'id' => 'irantheme_newsletter_all_mails',
						'description'            => __('در این بخش می توانید ایمیل های اعضای خبرنامه را مشاهده، اضافه یا حذف نمایید.', self::$static_textdomain),
						'fields' => array(
							array(
								'id'             => 'irantheme_newsletter_all_mails_textarea',
								'label'            => __(
									'لیست اعضای خبرنامه',
									self::$static_textdomain
								),
								'description'    => __('در این ورودی شما می توانید تمام کسانی که تا کنون در خبرنامه ثبت نام کرده اند را مشاهده و در صورت نیاز ایمیل اضافه یا حدف نمایید.', self::$static_textdomain),
								'type'            => 'textarea',
								'default'        => '',
								'placeholder'    => __('ایمیلی موجود نمی باشد', self::$static_textdomain),
								'keywords' => array('all newsletter emails', 'newsletter', 'لیست اعضای خبرنامه', 'خبرنامه'),
								'note' => __('توجه داشته باشید که در هر بار اضافه یا حذف کردن ایمیل، باید هر یک از ایمیل ها در یک خط جداگانه قرار بگیرند.', self::$static_textdomain),
							),
						)
					),
					array(
						'title' => 'ارسال ایمیل',
						'id' => 'irantheme_newsletter_send_mail',
						'description'            => __('در این بخش شما می توانید به اعضای خبرنامه خود ایمیل ارسال کنید.', self::$static_textdomain),
						'fields' => array(
							array(
								'id'             => 'irantheme_newsletter_send_mail_subject',
								'label'            => __(
									'موضوع ایمیل',
									self::$static_textdomain
								),
								'description'    => __('موضوع یا عنوان ایمیل ارسالی به اعضای خبرنامه را مشخص کنید.', self::$static_textdomain),
								'type'            => 'text',
								'default'        => 'بدون عنوان',
								'placeholder'    => __('', self::$static_textdomain),
								'custom_class' => 'irantheme-parent-tr-border-bottom-none',
								'keywords' => array('subject email', 'موضوع ایمیل', 'عنوان ایمیل', 'موضوع ایمیل خبرنامه', 'newsletter send email subject'),
							),
							array(
								'id'             => 'irantheme_newsletter_send_mail_content',
								'label'            => __('محتوا ایمیل', self::$static_textdomain),
								'description'    => __('محتوا یا متن ایمیل ارسالی به اعضای خبرنامه را مشخص کنید.', self::$static_textdomain),
								'type'            => 'text_editor',
								'default'        => 'محتوای ایمیل ارسالی را تنظیم کنید',
								'media_status' => true,
								'placeholder'    => __('', self::$static_textdomain),
								'custom_class' => 'irantheme-parent-tr-border-bottom-none',
								'keywords' => array('متن ارسال', 'محتوا ایمیل خبرنامه',  'متن محتوا', 'ایمیل خبرنامه', 'contect email', 'newsletter content', 'text email', 'text mail', 'mail content'),
							),
							array(
								"id"          => "newsletter_send_mail_submit",
								"label"         => __("ارسال ایمیل", self::$static_textdomain),
								"description"   => 'به محض کلیک بر روی این دکمه، محتوای ایمیل به همراه موضوع ایمیل، برای اعضای خبرنامه واقع در تب "ایمیل اعضا" ارسال خواهد شد.',
								"type"          => "custom_button",
								// 'icon' => 'plus-alt2',
								'default' => '',
								'custom_class' => 'irantheme-newsletter-send-mail-submit',
								'keywords' => array('اضافه کردن', 'سرویس پیشرفت', 'add service'),
								'note' => __('بسته به تعداد ایمیل های ذخیره شده در خبرنامه، ارسال ایمیل زمان بر خواهد بود.', self::$static_textdomain)
							),
						)
					),
				)
			);
			// Header configuration fields data
			$settings['footer'] = array(
				'title'                    => __('فوتر', self::$static_textdomain),
				'icon' => 'fa-keyboard-down',
				'submenu'                => array(
					self::get_footer_fields('irantheme', array('type')),
					array(
						'title' => 'محتوای فوتر',
						'id' => 'irantheme_footer_text',
						'description'            => __('در این بخش شما می توانید محتوا و نوشته های درون فوتر را تنظیم نمایید.', self::$static_textdomain),
						'fields' => array(
							array(
								'id'             => 'irantheme_footer_text_copyright',
								'label'            => __('متن کپی رایت', self::$static_textdomain),
								'description'    => __('با استفاده از این ورودی می توانید متن کپی رایت درون فوتر را تنظیم نمایید.', self::$static_textdomain),
								'type'            => 'text_editor',
								'default'        => 'کلیه حقوق مادی و معنوی سایت محفوظ می باشد.',
								'media_status' => false,
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('متن کپی رایت', 'کپی رایت',  'متن', 'copyright', 'copy', 'rules'),
							),
							array(
								'id'             => 'irantheme_footer_text_about',
								'label'            => __('متن درباره ما', self::$static_textdomain),
								'description'    => __('با استفاده از این ورودی می توانید متن "درباره ما" درون فوتر را تنظیم نمایید.', self::$static_textdomain),
								'type'            => 'text_editor',
								'default'        => 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است. چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است و برای شرایط فعلی تکنولوژی مورد نیاز و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد.',
								'media_status' => false,
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('متن درباره ما', 'درباره ما',  'متن', 'about', 'about site', 'about text'),
							),
							array(
								'id'             => 'irantheme_footer_text_about_link',
								'label'            => __(
									'لینک ادامه درباره ما',
									self::$static_textdomain
								),
								'description'    => __('در صورت تمایل می توانید لینکی جهت راهنمایی کاربر به صفحه درباره ما داشته باشید.', self::$static_textdomain),
								'type'            => 'text',
								'default'        => '#',
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('about link', 'متن درباره ما', 'لینک درباره ما', 'درباره ما', 'about text', 'link'),
							),
						),
					),
				)
			);
			// Header configuration fields data
			$settings['404'] = array(
				'title'                    => __('صفحه ۴۰۴', self::$static_textdomain),
				'icon' => 'fa-do-not-enter',
				'submenu'                => array(
					array(
						'title' => 'محتوا صفحه',
						'id' => 'irantheme_404_content',
						'description'            => __('در این بخش شما می توانید محتوای صفحه ۴۰۴ (صفحه مورد نظر پیدا نشد) را تنظیم نمایید.', self::$static_textdomain),
						'fields' => array(
							array(
								'id'             => 'irantheme_404_content_text',
								'label'            => __('محتوا (متن) صفحه ۴۰۴', self::$static_textdomain),
								'description'    => __('لطفا متن صفحه ۴۰۴ را وارد کنید.', self::$static_textdomain),
								'type'            => 'text_editor',
								'default'        => '<div style="box-sizing: border-box; width: 100%; padding: 60px 20px; text-align: center; background: linear-gradient(180deg,#eff6ff 0%,#ffffff 100%); border-radius: 20px;">
    <div style="display: inline-block; padding: 8px 18px; margin-bottom: 20px; color: #346DF9; background: #dbeafe; border: 1px solid #bfdbfe; border-radius: 30px; font-size: 13px; font-weight: bold;">صفحه موردنظر پیدا نشد!</div>
    <div style="margin: 0 auto 20px; color: #346DF9; font-size: clamp(100px,20vw,170px); font-weight: 900; line-height: 1.1; letter-spacing: 5px; text-shadow: 0 10px 25px rgba(37,99,235,0.15);">۴۰۴</div>
    <h1 style="margin: 0 0 15px; color: #172554; font-size: clamp(24px,4vw,34px); font-weight: 800; line-height: 1.8;">اوه! این صفحه گم شده!</h1>
    <p style="max-width: 520px; margin: 0 auto 30px; color: #64748b; font-size: 15px; line-height: 2.2;">متأسفانه صفحه‌ای که دنبالش هستید پیدا نشد. ممکن است آدرس اشتباه باشد یا صفحه موردنظر تغییر کرده باشد. نگران نباشید؛ می‌توانید از فروشگاه  محصولات موردنیازتان را پیدا کنید.</p>
    <div style="margin: 0 auto 15px; text-align: center;">
        <a style="display: inline-block; box-sizing: border-box; margin: 5px; padding: 14px 25px; color: #ffffff !important; background: #346DF9; border: 1px solid #346DF9; border-radius: 12px; text-decoration: none !important; font-size: 14px; font-weight: bold;" href="/">
			<i class="iconsax" icon-name="home-1" style="margin-left:0.25rem;"></i>
            بازگشت به صفحه اصلی
        </a>
        <a style="display: inline-block; box-sizing: border-box; margin: 5px; padding: 14px 25px; color: #346DF9 !important; background: #ffffff; border: 1px solid #bfdbfe; border-radius: 12px; text-decoration: none !important; font-size: 14px; font-weight: bold;" href="/shop/">
			مشاهده فروشگاه
			<i class="iconsax" icon-name="arrow-left" style="margin-right:0.25rem;"></i>
        </a>
    </div>
</div>
',
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('محتوا متن صفحه صفحه ۴۰۴', '404 text', '404 content'),
							),
							array(
								'id'             => 'irantheme_404_header_checkbox',
								'label'            => __(
									'نمایش هدر سایت',
									self::$static_textdomain
								),
								'description'    => __('در صورت تمایل به به نمایش هدر سایت، می توانید این گزینه را فعال کنید.
								', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('نمایش هدر سایت', 'هدر ۴۰۴', 'هدر 404',  'display header in 404 page'),
							),
							array(
								'id'             => 'irantheme_404_footer_checkbox',
								'label'            => __(
									'نمایش فوتر',
									self::$static_textdomain
								),
								'description'    => __('در صورت تمایل می توانید فوتر را فعال نمایید.
							', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('فعال سازی فوتر', 'فوتر 404', 'footer 404',  'enable footer for 404'),
							),
							array(
								'id'             => 'irantheme_404_social_checkbox',
								'label'            => __(
									'نمایش شبکه های اجتماعی',
									self::$static_textdomain
								),
								'description'    => __('در صورت تمایل به به نمایش شبکه های اجتماعی، می توانید این گزینه را فعال کنید.
								', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'on',
								'keywords' => array('نمایش شبکه های اجتماعی', 'شبکه های اجتماعی ۴۰۴', 'شبکه اجتماعی 404',  'display social media in 404 page'),
							),
						)
					),
					array(
						'title' => 'ظاهر صفحه',
						'id' => 'irantheme_404_layout',
						'description'            => __('در این بخش شما می توانید ظاهر و طرح صفحه ۴۰۴ (صفحه مورد نظر پیدا نشد) را تنظیم نمایید.', self::$static_textdomain),
						'fields' => array(
							array(
								'id'             => 'irantheme_404_base_color_select',
								'label'            => __(
									'رنگ پایه',
									self::$static_textdomain
								),
								'description'    => __('برای انتخاب رنگ پایه از لیست رنگ دلخواه خود را انتخاب کنید.رنگ محتواو پس زمینه بر اساس این رنگ تنظیم خواهد شد.', self::$static_textdomain),
								'type'            => 'select',
								'options'        => array('white' => 'سفید', 'dark' => 'مشکی'),
								'default'        => 'white',
								'keywords' => array('رنگ پایه', 'base color',),
							),
							array(
								'id'             => 'irantheme_404_details_color_select',
								'label'            => __(
									'رنگ جزئیات',
									self::$static_textdomain
								),
								'description'    => __('برای انتخاب رنگ جزئیات بخش از لیست رنگ دلخواه خود را انتخاب کنید. این رنگ تاثیر مستقیمی در زیبایی جزئيات دارد.', self::$static_textdomain),
								'type'            => 'select',
								'options'        => Irantheme_Color_Static::get_all_colors_options(),
								'color_preview' => true,
								'default'        => 'primary',
								'keywords' => array('رنگ جزئیات', 'detail colors', 'جزئیات', 'کلید', 'رنگ'),
							),
							array(
								'id'             => 'irantheme_404_bg_color_select',
								'label'            => __(
									'رنگ پس زمینه',
									self::$static_textdomain
								),
								'description'    => __('برای انتخاب رنگ پس زمینه از لیست، رنگ مورد نظر خود را انتخاب کنید.در صورت انتخاب حالت پیش فرض، رنگ پس زمینه بر اساس رنگ پایه انتخاب شده در بالا تنظیم می شود.(تذکر: برای تنظیم هریک از رنگ های اصلی،فرعی،اولیه،ثانویه و اضافی به بخش " تنظیمات قالب -> رنگ های سایت " مراجعه فرمایید.) ', self::$static_textdomain),
								'type'            => 'select',
								'color_preview' => true,
								'options'        => Irantheme_Color_Static::get_all_colors_options(true),
								'default'        => 'off',
								'keywords' => array('color', 'ارتباط با ما', 'رنگ', 'سرویس', 'پس زمینه'),
							),
							array(
								'id'             => 'irantheme_404_bg_checkbox',
								'label'            => __(
									'تصویر پس زمینه سفارشی',
									self::$static_textdomain
								),
								'description'    => __('در صورت تمایل به تنظیم تصویر زمینه بصورت دلخواه، این گزینه را فعال نمایید.
							', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('business', 'brands', 'کسب و کار',  'تصویر زمینه'),
								'id_dependents_on' => array('irantheme_404_bg', 'irantheme_404_bg_scroll_checkbox', 'irantheme_404_mask_checkbox'),
								'base_priority' => 'checkbox_base',
								'selector_priority' => 'checkbox_404_img',
								'z_index' => 0
							),
							array(
								'id'             => 'irantheme_404_bg',
								'label'            => __('تصویر پس زمینه', self::$static_textdomain),
								'description'    => __('لطفا تصویر خود را بارگذاری نمایید.', self::$static_textdomain),
								'type'            => 'image',
								'default'        => '',
								'placeholder'    => '',
								'keywords' => array('business', 'brands', 'کسب و کار', 'تصویر'),
							),
							array(
								'id'             => 'irantheme_404_bg_scroll_checkbox',
								'label'            => __(
									'تصویر زمینه قابل پیمایش',
									self::$static_textdomain
								),
								'description'    => __('در صورت تمایل به تنظیم تصویر زمینه بصورت متحرک، این گزینه را فعال نمایید.
								', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('business', 'brands', 'برند',  'تصویر زمینه'),
							),
							array(
								'id'             => 'irantheme_404_mask_checkbox',
								'label'            => __(
									'لایه پوشاننده تصویر',
									self::$static_textdomain
								),
								'description'    => __('لایه پوشاننده به عنصر شفافی گفته می شود که روی تصویر انتخابی شما را می پوشاند و محتوا شما روی آن قرار می گیرد.کاربرد این لایه زمانی است که تصویر پس زمینه شما دارای ترکیب رنگی خاصی است که متن ها در هر دو رنگ مشکی یا سفید روی آن بخوبی دیده نمی شود،در نتیجه این لایه شفاف که به رنگ پایه انتخاب شده در بالا می باشد باعث می شود که متن شما واضح تر دیده شود.توصیه می شود در صورتی این گزینه را غیر فعال کنید که ترکیب رنگی تصویر پس زمینه شما به شکلی است که متن های محتوا بصورت واضح روی آن دیده می شود.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'on',
								'keywords' => array('showcase', 'blog', 'simple', 'hero', 'ویترین', 'هیرو', 'تصویر زمینه'),
							),
							array(
								'id'             => 'irantheme_404_align_content_radio',
								'label'            => __('تراز محتوا', self::$static_textdomain),
								'description'    => __('با استفاده از این گزینه می توانید تراز جزئیات پست ها را مشخص کنید.', self::$static_textdomain),
								'type'            => 'radio',
								'thumbnail_status' => true,
								'options'        => array('right' => array('راست چین', 'text-align-right.png'), 'center' => array('وسط چین', 'text-align-center.png'), 'left' => array('چپ چین', 'text-align-left.png')),
								'default'        => 'center',
								'keywords' => array('box', 'col', 'author', 'تعداد', 'سرویس', 'تعداد در هر ردیف'),
							),
							array(
								'id'             => 'irantheme_404_fullscreen_checkbox',
								'label'            => __(
									'ویترین تمام صفحه',
									self::$static_textdomain
								),
								'description'    => __('در صورت تمایل می توانید ویترین خود را به صورت تمام صفحه نمایش دهید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'on',
								'keywords' => array('hieght', 'ارتفاع', 'fullscreen', 'تمام صفحه', 'ویترین', 'هیرو', 'تصویر زمینه'),
								'id_dependents_on' => array(),
								'id_dependents_off' => array('irantheme_404_height_range'),
								'base_priority' => 'checkbox_base',
								'selector_priority' => 'checkbox_404_height',
								'z_index' => 0
							),
							array(
								'id'             => 'irantheme_404_height_range',
								'label'            => __(
									' ارتفاع ویترین',
									self::$static_textdomain
								),
								'description'    => __('در صورت تمایل می توانید ارتفاع ویترین را به میزان دلخواه خود تغییر دهید.', self::$static_textdomain),
								'type'            => 'range',
								'min' => 1,
								'max' => 12,
								'step' => 1,
								'default'        => 6,
								'placeholder'    => __('Placeholder text', self::$static_textdomain),
								'keywords' => array('showcase', 'blog', 'simple', 'hero', 'ویترین', 'هیرو', 'اندازه', 'ارتفاع')
							),
						),
					),
				)
			);
			// Woocommerce options ===================================================
			// Woocommerce configuration fields data
			$settings['woocommerce'] = array(
				'title'                    => __('ووکامرس', self::$static_textdomain),
				'icon' => 'fa-shop',
				'submenu'                => array(
					array(
						'title' => 'صفحه معرفی محصول',
						'id' => 'irantheme_single_product',
						'description'            => __('در این بخش شما می توانید تنظمیات مربوط به محتوای صفحه معرفی محصول را تنظیم نمایید.', self::$static_textdomain),
						'fields' => array(
							array(
								'id'             => 'irantheme_single_product_outer_container',
								'label'            => __(
									'عرض نگهدارنده خارجی',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید عرض نگهدارنده خارجی این صفحه را مشخص نمایید.(واحد اندازه گیری درصد و نسبت به عرض صفحه نمایش می باشد)', self::$static_textdomain),
								'type'            => 'range',
								'min' => 50,
								'max' => 100,
								'step' => 1,
								'default'        => 100,
								'placeholder'    => __('Placeholder text', self::$static_textdomain),
								'keywords' => array('container', 'width', 'wrapper', 'عرض نهگدارنده', 'اندازه', 'عرض', 'نگهدارنده')
							),
							array(
								'id'             => 'irantheme_single_product_inner_container',
								'label'            => __(
									'عرض نگهدارنده داخلی',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید عرض نگهدارنده داخلی این صفحه را مشخص نمایید.(واحد اندازه گیری درصد و نسبت به عرض نگهدارنده خارجی می باشد)', self::$static_textdomain),
								'type'            => 'range',
								'min' => 40,
								'max' => 100,
								'step' => 1,
								'default'        => 60,
								'placeholder'    => __('Placeholder text', self::$static_textdomain),
								'keywords' => array('container', 'width', 'wrapper', 'عرض نهگدارنده', 'اندازه', 'عرض', 'نگهدارنده')
							),
							array(
								'id'             => 'irantheme_single_product_gallery_layout_radio',
								'label'            => __('سبک گالری محصول', self::$static_textdomain),
								'description'    => __('با استفاده از این گزینه می توانید سبک نمایش گالری تصاویر مرتبط با محصول را مشخص نمایید.', self::$static_textdomain),
								'type'            => 'radio',
								'thumbnail_status' => true,
								'options'        => array(
									'thumb-grid' => array('اسلایدر-شبکه ای', 'p-gallery-thumb-grid.png'),
									'thumb-slider' => array('اسلایدر', 'p-gallery-thumb-slider.png'),
									'thumb-slider-vertical' => array('اسلایدر عمودی', 'p-gallery-thumb-slider-vertical.png'),
									'grid' => array('شبکه ای', 'p-gallery-grid.png'),
								),
								'default'        => 'thumb-grid',
								'keywords' => array('thumbnails', 'slider', 'gallery', 'گالری محصول', 'گالری تصاویر محصول'),
							),
							array(
								'id'             => 'irantheme_single_product_sku_visibility_checkbox',
								'label'            => __(
									'نمایش شناسه محصول (SKU)',
									self::$static_textdomain
								),
								'description'    => __('در صورت تمایل به نمایش شناسه محصول (SKU) در صفحه  معرفی محصول این گزینه را فعال کنید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'on',
								'keywords' => array('sku', 'شناسه محصول', 'نمایش شناسه محصول'),
								'id_dependents_on' => array('irantheme_single_product_sku_empty_visibility_checkbox'),
								'id_dependents_off' => array(),
								'base_priority' => 'checkbox_base',
								'selector_priority' => 'checkbox_product_sku',
								'z_index' => 0
							),
							array(
								'id'             => 'irantheme_single_product_sku_empty_visibility_checkbox',
								'label'            => __(
									'نمایش شناسه محصول (SKU) برای محصولات بدون شناسه',
									self::$static_textdomain
								),
								'description'    => __('روشن: شناسه محصول (SKU) برای همه محصولات نمایش داده می شود.</br>خاموش: شناسه محصول (SKU) فقط برای محصولاتی که برای آنها شناسه تعریف شده است نمایش داده می شود.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('sku', 'شناسه محصول', 'نمایش شناسه محصول'),
							),
							array(
								'id'             => 'irantheme_single_product_seller_name_checkbox',
								'label'            => __(
									'نمایش نام فروشنده',
									self::$static_textdomain
								),
								'description'    => __('در صورت تمایل به نمایش نام فروشنده کالا ها در صفحه  معرفی محصول این گزینه را فعال کنید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'on',
								'keywords' => array('seller name', 'نام فروشگاه', 'نام فروشنده جزئیات', 'shop name'),
								'id_dependents_on' => array('irantheme_single_product_seller_name'),
								'id_dependents_off' => array(),
								'base_priority' => 'checkbox_base',
								'selector_priority' => 'checkbox_product_seller_name',
								'z_index' => 0
							),
							array(
								'id'             => 'irantheme_single_product_seller_name',
								'label'            => __(
									'نام فروشنده',
									self::$static_textdomain
								),
								'description'    => __('نام نمایشی فروشنده کالا ها در صفحه معرفی محصول', self::$static_textdomain),
								'note'    => __('مقدار پیش فرض این فیلد از فیلد نام فروشگاه در پیکربندی ووکامرس دریافت می گردد.', self::$static_textdomain),
								'type'            => 'text',
								'default'        => get_option('woocommerce_pos_store_name') != '' ? get_option('woocommerce_pos_store_name') : get_bloginfo('name'),
								'keywords' => array('seller name', 'نام فروشگاه', 'نام فروشنده جزئیات', 'shop name'),
							),
							array(
								'id'             => 'irantheme_single_product_variable_selected_checkbox',
								'label'            => __(
									'نمایش ویژگی های انتخاب شده در باکس افزودن به سبد خرید',
									self::$static_textdomain
								),
								'description'    => __('در صورت تمایل به نمایش ویژگی ها انتخاب شده در محصولات متغیر در باکس افزودن به سبد خرید این گزینه را فعال نمایید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'on',
								'keywords' => array('variable selected', 'ویژگی های انتخاب شده'),
							),
							array(
								'id'             => 'irantheme_single_product_shipping_time_checkbox',
								'label'            => __(
									'نمایش حداکثر زمان ارسال کالا برای محصولات فیزیکی',
									self::$static_textdomain
								),
								'description'    => __('در صورت تمایل به نمایش حداکثر زمان ارسال کالا ها فیزیکی در باکس افزودن به سبد خرید در صفحه محصول این گزینه را فعال کنید.', self::$static_textdomain),
								'note'    => __('در صورت فعالسازی، یک فیلد در بخش تنظیمات محصول در صفحه افزودن یا ویرایش محصول به همین نام نمایان خواهد شد که می توانید مقدار آن را برای هر محصول بصورت جداگانه مشخص کنسد.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'on',
								'keywords' => array('shipping time', 'زمان ارسال'),
							),
							array(
								'id'             => 'irantheme_single_product_support_phone_number',
								'label'            => __(
									'شماره تماس پشتیبانی (واحد فروش)',
									self::$static_textdomain
								),
								'description'    => __('در صورت فعال سازی گزینه "تماس بگیرید" در صفحه ویرایش محصول، این شماره به عنوان شماره تماس با پشتیبانی در صفحه معرفی محصول نمایش داده می شود.', self::$static_textdomain),
								'type'            => 'text',
								'default'        => '',
								'placeholder'        => '0913XXXXXXX',
								'keywords' => array('support phone number', 'شماره تماس پشتیبانی (واحد فروش)'),
							),
							array(
								'id'             => 'irantheme_single_product_update_date',
								'label'            => __(
									'نمایش تاریخ آخرین بروزرسانی',
									self::$static_textdomain
								),
								'description'    => __('نمایش تاریخ آخرین بروزرسانی اطلاعات محصول', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'on',
								'keywords' => array('product update date', 'تاریخ بروزرسانی اطلاعات محصول'),
							),
							array(
								'id'             => 'irantheme_single_product_second_summary_box',
								'label'            => __(
									'نمایش باکس ثانویه چسبان افزودن به سبد خرید',
									self::$static_textdomain
								),
								'description'    => __('در صورت فعالسازی ای گزینه باکس ثانویه چسبان افزودن به سبد خرید در کنار تب های توضیحات فعال می گردد.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'on',
								'keywords' => array('secound summary box', 'باکس ثانویه چسبان افزودن به سبد خرید'),
							),
							array(
								'id'             => 'irantheme_single_product_qa_status_checkbox',
								'label'            => __(
									'افزودن پرسش و پاسخ به محصولات',
									self::$static_textdomain
								),
								'description'    => __('در صورت تمایل به نمایش بخش پرسش و پاسخ برای هر محصول در صفحه معرفی محصول این گزینه را فعال نمایید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'on',
								'keywords' => array('question and answer', 'product', 'پرسش و پاسخ'),
								'id_dependents_on' => array('irantheme_single_product_qa_submission_guest_checkbox'),
								'id_dependents_off' => array(),
								'base_priority' => 'checkbox_base',
								'selector_priority' => 'checkbox_qa_product',
								'z_index' => 0
							),
							array(
								'id'             => 'irantheme_single_product_qa_submission_guest_checkbox',
								'label'            => __(
									'اجازه ثبت پرسش و پاسخ توسط کاربر مهمان',
									self::$static_textdomain
								),
								'description'    => __('در صورت فعال بودن این گزینه کاربرانی که بصورت مهمان وارد سایت شما میشوند و حساب کاربری ندارند نیز می توانند بر روی محصولات پرسش یا پاسخ جدید ثبت کنند.در ضورت خاموش بودن این گزینه فقط اعضای سایت می توانند پرسش و پاسخ جدید ثبت کنند.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'on',
								'keywords' => array('question and answer', 'product', 'پرسش و پاسخ'),
							),
							array(
								'id'             => 'irantheme_single_product_details_color',
								'label'            => __(
									'رنگ جزئیات',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید رنگ جزئیات را تنظیم کنید.رنگ جزيیات در زیبایی تاثیر زیادی دارد.این رنگ در hover استفاده می شود.', self::$static_textdomain),
								'type'            => 'select',
								'color_preview' => true,
								'options'        => Irantheme_Color_Static::get_all_colors_options(false),
								'default'        => 'off',
								'keywords' => array('details', 'رنگ جزئیات محصول', 'رنگ جزئیات', 'details color', 'color', 'product'),
							),
							array(
								'id'             => 'irantheme_single_product_add_to_card_btn_color_customization_checkbox',
								'label'            => __(
									'سفارشی سازی رنگ دکمه افزودن به سبد خرید',
									self::$static_textdomain
								),
								'description'    => __('در صورت تمایل به تغییر رنگ دکمه افزودن به سبد خرید می توانید این گزینه را فعال کرده و تغییرات خود را اعمال نمایید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('add to card button background customization', 'product', 'محصول', 'سفارشی سازی رنگ دکمه افزودن به سبد خرید'),
								'id_dependents_on' => array('irantheme_single_product_add_to_card_btn_bg_color', 'irantheme_single_product_add_to_card_btn_color'),
								'id_dependents_off' => array(),
								'base_priority' => 'checkbox_base',
								'selector_priority' => 'checkbox_add_to_card_btn_color_customization',
								'z_index' => 0
							),
							array(
								'id'             => 'irantheme_single_product_add_to_card_btn_bg_color',
								'label'            => __(
									'رنگ پس زمینه دکمه افزودن به سبد خرید',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید رنگ پس زمینه دکمه افزودن به سبد خرید را تنظیم کنید.', self::$static_textdomain),
								'type'            => 'select',
								'color_preview' => true,
								'options'        => Irantheme_Color_Static::get_all_colors_options(false),
								'default'        => 'off',
								'keywords' => array('add to card button background customization', 'product', 'محصول', 'سفارشی سازی رنگ دکمه افزودن به سبد خرید'),
							),
							array(
								'id'             => 'irantheme_single_product_add_to_card_btn_color',
								'label'            => __(
									'رنگ متن دکمه افزودن به سبد خرید',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید رنگ متن دکمه افزودن به سبد خرید را تنظیم کنید.', self::$static_textdomain),
								'note'    => __(' اگر رنگ پیش‌فرض را انتخاب کنید، به‌طور خودکار بهترین رنگ متن (سفید یا مشکی) بر اساس کنتراست رنگ زمینه انتخاب می‌شود تا خوانایی کاملاً حفظ شود.', self::$static_textdomain),
								'type'            => 'select',
								'options'        => array('off' => 'پیش فرض', 'dark' => 'مشکی', 'white' => 'سفید'),
								'default'        => 'off',
								'keywords' => array('add to card button background customization', 'product', 'محصول', 'سفارشی سازی رنگ دکمه افزودن به سبد خرید'),
							),
							array(
								'id'             => 'irantheme_single_product_share_checkbox',
								'label'            => __(
									'اشتراک گداری محصول',
									self::$static_textdomain
								),
								'description'    => __('در صورت تمایل با استفاده از این گزینه می توانید گزینه های اشتراک گذاری محصول را فعال یا غیر فعال نمایید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'on',
								'keywords' => array('share', 'product', 'send', 'product share', 'محصول', 'اشتراک گذاری', 'اشتراک گذاری محصول'),
								'id_dependents_on' => array('irantheme_single_product_share_tools'),
								'id_dependents_off' => array(),
								'base_priority' => 'checkbox_base',
								'selector_priority' => 'checkbox_share_product',
								'z_index' => 0
							),
							array(
								'id'             => 'irantheme_single_product_share_tools',
								'label'            => __(
									'ابزار های اشتراک گذاری',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید نمایش و یا عدم نمایش ابزار های اشترا گذاری را مشخص نمایید.', self::$static_textdomain),
								'type'            => 'checkbox_multi',
								'options'        => array('telegram' => 'تلگرام', 'x-twitter' => 'ایکس(توئیتر)', 'whatsapp' => 'واتس آپ', 'pinterest' => 'پینترست', 'facebook' => 'فیس بوک', 'linkedin' => 'لینکدین', 'email' => 'ایمیل', 'link' => 'اشتراک گذاری لینک'),
								'default'        => 'telegram,x-twitter,whatsapp,email,link',
								'keywords' => array('share', 'product', 'send', 'product share', 'محصول', 'اشتراک گذاری', 'اشتراک گذاری محصول'),
							),
							array(
								'id'             => 'irantheme_single_product_magnification_image_checkbox',
								'label'            => __(
									'بزرگ نمایی تصویر محصول',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید بخش بزرگنمایی تصاویر محصول را فعال یا غیر فعال کنید. در صورت فعال بودن این بخش با کلیک روی هریک از تصاویر محصول گالری تصاویر نحصول باز شده و امکاناتی از قبیل بزرگنمایی، اشتراک گذاری و ...در اختیار کاربر قرار می گیرد.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('بزرگ نمایی تصویر محصول', 'گالری محصول', 'magnification'),
								'id_dependents_on' => array('irantheme_single_product_magnification_bg_color_select', 'irantheme_single_product_magnification_tool_multiple_checkboxes'),
								'id_dependents_off' => array(),
								'selector_priority' => 'checkbox_magnification_image_product',
								'base_priority' => 'checkbox_base',
								'z_index' => 0,
							),
							array(
								'id'             => 'irantheme_single_product_magnification_bg_color_select',
								'label'            => __(
									'رنگ پس زمینه بخش بزرگنمایی تصویر',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید رنگ پس زمینه بخش بزرگنمایی تصاویر محصول را مشخص کنید.', self::$static_textdomain),
								'type'            => 'select',
								'options'        => array('white' => 'سفید', 'dark' => 'مشکی'),
								'default'        => 'dark',
								'keywords' => array('color', 'background', 'پس زمینه', 'رنگ'),
							),
							array(
								'id'             => 'irantheme_single_product_magnification_tool_multiple_checkboxes',
								'label'            => __(
									'ابزارهای بخش بزرگنمایی تصویر',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید نمایش و یا عدم نمایش هرکدام از ابزار های موجود در صفحه بزرگنمایی تصاویر محصول را مشخص کنید.', self::$static_textdomain),
								'type'            => 'checkbox_multi',
								'options'        => array('download' => 'دانلود', 'fullscreen-toggle' => 'تمام صفحه', 'share' => 'اشتراک گذاری'),
								'default'        => 'fullscreen-toggle,share',
								'keywords' => array('tool', 'magnification', 'media', 'image', 'video', 'نگارخانه', 'gallery', 'download', 'اشتراک', 'رسانه', 'گالری', 'تمام صفحه', 'دانلود', 'share', 'fullscreen', 'ابزارهای بخش بزرگنمایی تصویر')
							),
							array(
								'id'             => 'irantheme_single_product_additional_info_checkbox',
								'label'            => __(
									'اطلاعات اضافی خرید',
									self::$static_textdomain
								),
								'description'    => __('در صورت فعال کردن این گزینه، بخشی با عنوان اطلاعات اضافی خرید شامل ۵ قسمت برای نمایش  عنوان،توضیحات کوتاه و آیکون در صفحه معرفی محصول و در پایین بخش اول معرفی محصول نمایش داده می شود.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('اطلاعات اضافی خرید', 'additional info'),
								'id_dependents_on' => array('irantheme_single_product_additional_info_title_1', 'irantheme_single_product_additional_info_desc_1', 'irantheme_single_product_additional_info_icon_1', 'irantheme_single_product_additional_info_title_2', 'irantheme_single_product_additional_info_desc_2', 'irantheme_single_product_additional_info_icon_2', 'irantheme_single_product_additional_info_title_3', 'irantheme_single_product_additional_info_desc_3', 'irantheme_single_product_additional_info_icon_3', 'irantheme_single_product_additional_info_title_4', 'irantheme_single_product_additional_info_desc_4', 'irantheme_single_product_additional_info_icon_4', 'irantheme_single_product_additional_info_title_5', 'irantheme_single_product_additional_info_desc_5', 'irantheme_single_product_additional_info_icon_5'),
								'id_dependents_off' => array(),
								'selector_priority' => 'checkbox_additional_info',
								'base_priority' => 'checkbox_base',
								'z_index' => 0,
							),
							array(
								'id'             => 'irantheme_single_product_additional_info_title_1',
								'label'            => __(
									'عنوان (۱)',
									self::$static_textdomain
								),
								'description'    => __('عنوان موردنظر', self::$static_textdomain),
								'type'            => 'text',
								'default'        => '',
								'keywords' => array('عنوان اطلاعات تکمیلی', 'توضیحات فروشگاه', 'اطلاعات تکمیلی', 'additional info'),
								'custom_class' => 'irantheme-parent-tr-border-bottom-none',

							),
							array(
								'id'             => 'irantheme_single_product_additional_info_desc_1',
								'label'            => __(
									'توضیح کوتاه (۱)',
									self::$static_textdomain
								),
								'description'    => __('توضیح کوتاه مرتبط با عنوان انتخابی', self::$static_textdomain),
								'type'            => 'text',
								'default'        => '',
								'keywords' => array('توضیح کوتاه اطلاعات تکمیلی', 'توضیحات فروشگاه', 'اطلاعات تکمیلی', 'additional info'),
								'custom_class' => 'irantheme-parent-tr-border-bottom-none',

							),
							array(
								'id'             => 'irantheme_single_product_additional_info_icon_1',
								'label'            => __(
									'آیکون (۱)',
									self::$static_textdomain
								),
								'description'    => __('برای نمایش آیکون مرتبط در کنار متن می توانید از این گزینه استفاده نمایید.', self::$static_textdomain),
								'type'            => 'icon',
								'default'        => '',
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('آیکون اطلاعات تکمیلی', 'توضیحات فروشگاه', 'اطلاعات تکمیلی', 'additional info'),
							),
							array(
								'id'             => 'irantheme_single_product_additional_info_title_2',
								'label'            => __(
									'عنوان (۲)',
									self::$static_textdomain
								),
								'description'    => __('عنوان موردنظر', self::$static_textdomain),
								'type'            => 'text',
								'default'        => '',
								'keywords' => array('عنوان اطلاعات تکمیلی', 'توضیحات فروشگاه', 'اطلاعات تکمیلی', 'additional info'),
								'custom_class' => 'irantheme-parent-tr-border-bottom-none',

							),
							array(
								'id'             => 'irantheme_single_product_additional_info_desc_2',
								'label'            => __(
									'توضیح کوتاه (۲)',
									self::$static_textdomain
								),
								'description'    => __('توضیح کوتاه مرتبط با عنوان انتخابی', self::$static_textdomain),
								'type'            => 'text',
								'default'        => '',
								'keywords' => array('توضیح کوتاه اطلاعات تکمیلی', 'توضیحات فروشگاه', 'اطلاعات تکمیلی', 'additional info'),
								'custom_class' => 'irantheme-parent-tr-border-bottom-none',

							),
							array(
								'id'             => 'irantheme_single_product_additional_info_icon_2',
								'label'            => __(
									'آیکون (۲)',
									self::$static_textdomain
								),
								'description'    => __('برای نمایش آیکون مرتبط در کنار متن می توانید از این گزینه استفاده نمایید.', self::$static_textdomain),
								'type'            => 'icon',
								'default'        => '',
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('آیکون اطلاعات تکمیلی', 'توضیحات فروشگاه', 'اطلاعات تکمیلی', 'additional info'),
							),
							array(
								'id'             => 'irantheme_single_product_additional_info_title_3',
								'label'            => __(
									'عنوان (۳)',
									self::$static_textdomain
								),
								'description'    => __('عنوان موردنظر', self::$static_textdomain),
								'type'            => 'text',
								'default'        => '',
								'keywords' => array('عنوان اطلاعات تکمیلی', 'توضیحات فروشگاه', 'اطلاعات تکمیلی', 'additional info'),
								'custom_class' => 'irantheme-parent-tr-border-bottom-none',

							),
							array(
								'id'             => 'irantheme_single_product_additional_info_desc_3',
								'label'            => __(
									'توضیح کوتاه (۳)',
									self::$static_textdomain
								),
								'description'    => __('توضیح کوتاه مرتبط با عنوان انتخابی', self::$static_textdomain),
								'type'            => 'text',
								'default'        => '',
								'keywords' => array('توضیح کوتاه اطلاعات تکمیلی', 'توضیحات فروشگاه', 'اطلاعات تکمیلی', 'additional info'),
								'custom_class' => 'irantheme-parent-tr-border-bottom-none',

							),
							array(
								'id'             => 'irantheme_single_product_additional_info_icon_3',
								'label'            => __(
									'آیکون (۳)',
									self::$static_textdomain
								),
								'description'    => __('برای نمایش آیکون مرتبط در کنار متن می توانید از این گزینه استفاده نمایید.', self::$static_textdomain),
								'type'            => 'icon',
								'default'        => '',
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('آیکون اطلاعات تکمیلی', 'توضیحات فروشگاه', 'اطلاعات تکمیلی', 'additional info'),
							),
							array(
								'id'             => 'irantheme_single_product_additional_info_title_4',
								'label'            => __(
									'عنوان (۴)',
									self::$static_textdomain
								),
								'description'    => __('عنوان موردنظر', self::$static_textdomain),
								'type'            => 'text',
								'default'        => '',
								'keywords' => array('عنوان اطلاعات تکمیلی', 'توضیحات فروشگاه', 'اطلاعات تکمیلی', 'additional info'),
								'custom_class' => 'irantheme-parent-tr-border-bottom-none',

							),
							array(
								'id'             => 'irantheme_single_product_additional_info_desc_4',
								'label'            => __(
									'توضیح کوتاه (۴)',
									self::$static_textdomain
								),
								'description'    => __('توضیح کوتاه مرتبط با عنوان انتخابی', self::$static_textdomain),
								'type'            => 'text',
								'default'        => '',
								'keywords' => array('توضیح کوتاه اطلاعات تکمیلی', 'توضیحات فروشگاه', 'اطلاعات تکمیلی', 'additional info'),
								'custom_class' => 'irantheme-parent-tr-border-bottom-none',

							),
							array(
								'id'             => 'irantheme_single_product_additional_info_icon_4',
								'label'            => __(
									'آیکون (۴)',
									self::$static_textdomain
								),
								'description'    => __('برای نمایش آیکون مرتبط در کنار متن می توانید از این گزینه استفاده نمایید.', self::$static_textdomain),
								'type'            => 'icon',
								'default'        => '',
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('آیکون اطلاعات تکمیلی', 'توضیحات فروشگاه', 'اطلاعات تکمیلی', 'additional info'),
							),
							array(
								'id'             => 'irantheme_single_product_additional_info_title_5',
								'label'            => __(
									'عنوان (۵)',
									self::$static_textdomain
								),
								'description'    => __('عنوان موردنظر', self::$static_textdomain),
								'type'            => 'text',
								'default'        => '',
								'keywords' => array('عنوان اطلاعات تکمیلی', 'توضیحات فروشگاه', 'اطلاعات تکمیلی', 'additional info'),
								'custom_class' => 'irantheme-parent-tr-border-bottom-none',

							),
							array(
								'id'             => 'irantheme_single_product_additional_info_desc_5',
								'label'            => __(
									'توضیح کوتاه (۵)',
									self::$static_textdomain
								),
								'description'    => __('توضیح کوتاه مرتبط با عنوان انتخابی', self::$static_textdomain),
								'type'            => 'text',
								'default'        => '',
								'keywords' => array('توضیح کوتاه اطلاعات تکمیلی', 'توضیحات فروشگاه', 'اطلاعات تکمیلی', 'additional info'),
								'custom_class' => 'irantheme-parent-tr-border-bottom-none',

							),
							array(
								'id'             => 'irantheme_single_product_additional_info_icon_5',
								'label'            => __(
									'آیکون (۵)',
									self::$static_textdomain
								),
								'description'    => __('برای نمایش آیکون مرتبط در کنار متن می توانید از این گزینه استفاده نمایید.', self::$static_textdomain),
								'type'            => 'icon',
								'default'        => '',
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('آیکون اطلاعات تکمیلی', 'توضیحات فروشگاه', 'اطلاعات تکمیلی', 'additional info'),
							),
						),
					),
					array(
						'title' => 'محصولات مشابه (releted)',
						'id' => 'irantheme_related_products',
						'description'            => __('در این بخش شما می توانید تنظمیات مربوط به ظاهر بخش محصولات مشابه که در صفحه معرفی محصول نمایش داده می شود را تنظیم نمایید.', self::$static_textdomain),
						'fields' => array(
							array(
								'id'             => 'irantheme_related_products_align_content_radio',
								'label'            => __('تراز محتوا', self::$static_textdomain),
								'description'    => __('با استفاده از این گزینه می توانید تراز جزئیات پست ها را مشخص کنید.', self::$static_textdomain),
								'type'            => 'radio',
								'thumbnail_status' => true,
								'options'        => array('right' => array('راست چین', 'text-align-right.png'), 'center' => array('وسط چین', 'text-align-center.png'), 'left' => array('چپ چین', 'text-align-left.png')),
								'default'        => 'right',
								'keywords' => array('box', 'col', 'author', 'تعداد', 'سرویس', 'تعداد در هر ردیف'),
							),
							array(
								'id'             => 'irantheme_related_products_details_color_select',
								'label'            => __(
									'رنگ جزئیات',
									self::$static_textdomain
								),
								'description'    => __('برای انتخاب رنگ جزئیات بخش از لیست رنگ دلخواه خود را انتخاب کنید. این رنگ تاثیر مستقیمی در زیبایی جزئيات دارد.', self::$static_textdomain),
								'type'            => 'select',
								'options'        => Irantheme_Color_Static::get_all_colors_options(),
								'color_preview' => true,
								'default'        => 'primary',
								'keywords' => array('رنگ جزئیات', 'detail colors', 'جزئیات', 'کلید', 'رنگ'),
							),
							array(
								'id'             => 'irantheme_related_products_details_color_select2',
								'label'            => __(
									'رنگ جزئیات دوم',
									self::$static_textdomain
								),
								'description'    => __('برای انتخاب رنگ جزئیات دوم بخش از لیست رنگ دلخواه خود را انتخاب کنید. این رنگ تاثیر مستقیمی در زیبایی جزئيات دارد.', self::$static_textdomain),
								'type'            => 'select',
								'options'        => Irantheme_Color_Static::get_all_colors_options(),
								'color_preview' => true,
								'default'        => 'secondary',
								'keywords' => array('رنگ جزئیات', 'detail colors', 'جزئیات', 'کلید', 'رنگ'),
							),
							array(
								'id'             => 'irantheme_related_products_hover_mode_radio',
								'label'            => __(
									'سبک واکنش به محصول (hover)',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید نوع واکنش به عبور نشانگر از روی محصولات (hover) را مشخص کنید.', self::$static_textdomain),
								'type'            => 'radio',
								'options'        => array('normal' => 'ساده', 'shadow' => 'سایه', 'scale' => 'بزرگنمایی'),
								'default'        => 'normal',
								'keywords' => array('واکنش نشانگر محصولات', 'استایل محصولات', 'ویژگی های محصولات', 'product hover', 'woocommerce product style', 'product style', 'hover product', 'hover', 'هاور')
							),
							array(
								'id'             => 'irantheme_related_products_image_hover_mode_radio',
								'label'            => __(
									'سبک واکنش به تصویر (hover)',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید نوع واکنش به عبور نشانگر از روی تصویر محصول (hover) را مشخص کنید.', self::$static_textdomain),
								'type'            => 'radio',
								'options'        => array('normal' => 'ساده', 'shine' => 'رهگذر', 'flashing' => 'فلش', 'circle' => 'گرد'),
								'default'        => 'normal',
								'keywords' => array('واکنش نشانگر محصولات', 'استایل محصولات', 'ویژگی های محصولات', 'product hover', 'woocommerce product style', 'product style', 'hover product', 'hover', 'هاور')
							),
							array(
								'id'             => 'irantheme_related_products_image_changer_checkbox',
								'label'            => __(
									'تغییر تصویر در زمان هاور',
									self::$static_textdomain
								),
								'description'    => __('در صورت تمایل به تغییر تصویر با تصاویر گالری در زمان واکنش ماوس (Hover) می توانید از این گزینه استفاده کنید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'on',
								'keywords' => array('hover product image', 'واکنش ماوس', 'هاور محصول', 'استایل محصول', 'product style', 'woocommerce product style'),
							),
							array(
								'id'             => 'irantheme_related_products_transparent_checkbox',
								'label'            => __(
									'پس زمینه شیشه ای محصول',
									self::$static_textdomain
								),
								'description'    => __('در صورت فعال سازی این گزینه پس زمینه محصول به صورت شیشه ای و بدون رنگ نمایش داده خواهد شد.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('پس زمینه شیشه ای محصول', 'product transparent'),
							),
							array(
								'id'             => 'irantheme_related_products_border_checkbox',
								'label'            => __(
									'خط دور محصول',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید وضعیت نمایش یا عدم نمایش خط دور هر نوشته (بوردر) را مشخص نمایید. در صوررت فعال نمودن این گزینه (محصول) ها به شکل باکسی در می آیند.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('بوردر محصول', 'خط دور محصول', 'prodcut border', 'border style product'),
							),
							array(
								'id'             => 'irantheme_related_products_shadow_checkbox',
								'label'            => __(
									'سایه دور پست',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید وضعیت نمایش یا عدم نمایش سایه دور هر نوشته(پست) را مشخص نمایید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('hieght', 'ارتفاع', 'fullscreen', 'تمام صفحه', 'ارتباطات'),
							),
							array(
								'id'             => 'irantheme_related_products_padding_status_checkbox',
								'label'            => __(
									'حاشیه دور پست',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید حاشیه ای اضافی به دور هر محصول اعمال نمایید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'on',
								'keywords' => array('حاشیه دور پست', 'product padding'),
							),
							array(
								'id'             => 'irantheme_related_products_border_radius_range',
								'label'            => __(
									'خمیدگی حاشیه',
									self::$static_textdomain
								),
								'description'    => __('با اسفاده از این گزینه می توانید خمیدگی حاشیه (Border Radius) را تعیین کنید.(واحد rem می باشد)', self::$static_textdomain),
								'type'            => 'range',
								'min' => 0,
								'max' => 2.5,
								'step' => 0.1,
								'default'        => 0.5,
								'placeholder'    => __('Placeholder text', self::$static_textdomain),
								'keywords' => array('خمیدگی حاشیه', 'related product border radius',),
							),
							array(
								'id'             => 'irantheme_related_products_features_checkbox_multi',
								'label'            => __(
									'ویژگی های محصول',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید نوع جزئیات قابل نمایش برای هر محصول را مشخص کنید.مانند عنوان، دسته بندی و غیره... ', self::$static_textdomain),
								'type'            => 'checkbox_multi',
								'options'        => array('image' => 'تصویر', 'category' => 'دسته بندی', 'title' => 'عنوان',  'excerpt' => 'متن توضیحات', 'rating' => 'امتیاز', 'price' => 'قیمت', 'cart' => 'افزودن به سبد'),
								'default'        =>  'image,category,title,rating,price,cart',
								'keywords' => array('محصولات', 'محصولات ووکامرس', 'woocommerce products')
							),
							array(
								'id'             => 'irantheme_related_products_extra_options_checkbox',
								'label'            => __(
									'نمایش امکانات اضافی',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید امکانات اضافی از قبیل افزودن به سبد خرید، افزودن به علاقه مندی و مشاهده سریع محصول را بر روی تصویر محصول نمایش دهید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        =>  'on',
								'keywords' => array('product options', 'ویژگی های اضافی', 'نمایش ویژگی های اضافی', 'افزودن به سبد خرید', 'product add to cart', 'woocommerce product style'),
								'id_dependents_on' => array('irantheme_related_products_extra_option_on_image_style_radio', 'irantheme_related_products_extra_options_hover_checkbox'),
								'id_dependents_off' => array(),
								'base_priority' => 'checkbox_base',
								'selector_priority' => 'related_products_extra_options',
								'z_index' => 0
							),
							array(
								'id'             => 'irantheme_related_products_extra_option_on_image_style_radio',
								'label'            => __('استایل نمایش امکانات اضافی روی تصویر', self::$static_textdomain),
								'description'    => __('در صورت انتخاب "نمایش روی تصویر" می توانید با استفاده از این گزینه استایل امکانات اضافی را انتخاب کنید.', self::$static_textdomain),
								'type'            => 'radio',
								'thumbnail_status' => true,
								'options'        => array(
									'vertical-left' => array('', 'product-extra-option-vertical-left.png'),
									'horizontal-center' => array('', 'product-extra-option-horizontal-center.png'),
									'horizontal-bottom' => array('', 'product-exrat-option-horizontal-bottom.png'),
								),
								'default'        =>  'vertical-left',
								'keywords' => array('امکانات اضافی', 'امکانات اضافی محصول', 'استایل امکانات اضافی محصول', 'products options', 'product extra options'),
							),
							array(
								'id'             => 'irantheme_related_products_extra_options_hover_checkbox',
								'label'            => __(
									'انیمیشن امکانات اضافی',
									self::$static_textdomain
								),
								'description'    => __('در صورت فعال بودن این گزینه و انتخاب "نمایش روی تصویر"، امکانات اضافی روی تصویر هنگام عبور نشانگر از روی آنها همراه با انیمیشن ورود و خروج نمایش داده می شوند.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        =>  'on',
								'keywords' => array('امکانات اضافی', 'امکانات اضافی محصول', 'انیمیشن امکانات اضافی محصول', 'products options', 'product extra options'),
							),
							array(
								'id'             => 'irantheme_related_products_excerpt_count_number',
								'label'            => __(
									'تعداد کلمه متن توضیحات',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید تعداد کلمه های توضیح محصول را مشخص کنید.', self::$static_textdomain),
								'type'            => 'number',
								'step' => 1,
								'default'        =>  18,
								'min' => 4,
								'max' => 40,
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('تعداد کلمه', 'تعداد کلمه محصول', 'اندازه توضیحات محصول', 'count description of products', 'number of excerpt', 'excerpt product'),
							),
							array(
								'id'             => 'irantheme_related_products_col_count_radio',
								'label'            => __('تعداد ستون محصولات', self::$static_textdomain),
								'description'    => __('با استفاده از این گزینه شما می توانید تعداد ستون های قابل نمایش برای محصولات را مشخص کنید', self::$static_textdomain),
								'type'            => 'radio',
								'thumbnail_status' => true,
								'options'        => array(
									'col-3' => array('سه ستون', 'col-3.png'),
									'col-4' => array('چهار ستون', 'col-4.png'),
									'col-5' => array('پنج ستون', 'col-5.png'),
									'col-6' => array('شش ستون', 'col-6.png'),
									'col-7' => array('هفت ستون', 'col-7.png'),
									'col-8' => array('هشت ستون', 'col-8.png'),
								),
								'default'        =>  'col-5',
								'keywords' => array('box', 'col', 'author', 'تعداد', 'سرویس', 'تعداد در هر ردیف'),
							),
							array(
								'id'             => 'irantheme_related_products_discount_checkbox',
								'label'            => __(
									'نمایش درصد تخفیف',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید درصد تخفیف را به صورت یک کادر بر روی تصویر محصول مشاهده نمایید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        =>  'on',
								'keywords' => array('درصد تخفیف محصول', 'تخفیف محصول', 'product discount', 'discount'),
							),
							array(
								'id'             => 'irantheme_related_products_total_sales_checkbox',
								'label'            => __(
									'نمایش تعداد فروش',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید درصد تخفیف را به صورت یک کادر بر روی تصویر محصول مشاهده نمایید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        =>  'off',
								'keywords' => array('نمایش تعداد فروش', 'تعداد فروش محصول', 'product total sales', 'count of sales', 'number of sales'),
							),
							array(
								'id'             => 'irantheme_related_products_discount_schedule_checkbox',
								'label'            => __(
									'نمایش شمارنده تخفیف',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید شمارنده تخفیفی که بر روی محصول اعمال کرده اید را نمایش یا مخفی نمایید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        =>  'off',
								'keywords' => array('نمایش شمارنده تخفیف', 'number of sales'),
							),
							array(
								'id'             => 'irantheme_related_products_color_attribute_checkbox',
								'label'            => __(
									'نمایش رنگ محصول',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید رنگ های موجود برای هر محصول را مشاهده نمایید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'on',
								'keywords' => array('نمایش رنگ محصول', 'color of product'),
							),
						)
					),
					array(
						'title' => 'محصولات پیشنهادی (up-sells)',
						'id' => 'irantheme_up_sells_products',
						'description'            => __('در این بخش شما می توانید تنظمیات مربوط به ظاهر بخش محصولات پیشنهادی (up) که در صفحه معرفی محصول نمایش داده می شود را تنظیم نمایید.', self::$static_textdomain),
						'fields' => array(
							array(
								'id'             => 'irantheme_up_sells_products_align_content_radio',
								'label'            => __('تراز محتوا', self::$static_textdomain),
								'description'    => __('با استفاده از این گزینه می توانید تراز جزئیات پست ها را مشخص کنید.', self::$static_textdomain),
								'type'            => 'radio',
								'thumbnail_status' => true,
								'options'        => array('right' => array('راست چین', 'text-align-right.png'), 'center' => array('وسط چین', 'text-align-center.png'), 'left' => array('چپ چین', 'text-align-left.png')),
								'default'        => 'right',
								'keywords' => array('box', 'col', 'author', 'تعداد', 'سرویس', 'تعداد در هر ردیف'),
							),
							array(
								'id'             => 'irantheme_up_sells_products_details_color_select',
								'label'            => __(
									'رنگ جزئیات',
									self::$static_textdomain
								),
								'description'    => __('برای انتخاب رنگ جزئیات بخش از لیست رنگ دلخواه خود را انتخاب کنید. این رنگ تاثیر مستقیمی در زیبایی جزئيات دارد.', self::$static_textdomain),
								'type'            => 'select',
								'options'        => Irantheme_Color_Static::get_all_colors_options(),
								'color_preview' => true,
								'default'        => 'primary',
								'keywords' => array('رنگ جزئیات', 'detail colors', 'جزئیات', 'کلید', 'رنگ'),
							),
							array(
								'id'             => 'irantheme_up_sells_products_details_color_select2',
								'label'            => __(
									'رنگ جزئیات دوم',
									self::$static_textdomain
								),
								'description'    => __('برای انتخاب رنگ جزئیات دوم بخش از لیست رنگ دلخواه خود را انتخاب کنید. این رنگ تاثیر مستقیمی در زیبایی جزئيات دارد.', self::$static_textdomain),
								'type'            => 'select',
								'options'        => Irantheme_Color_Static::get_all_colors_options(),
								'color_preview' => true,
								'default'        => 'secondary',
								'keywords' => array('رنگ جزئیات', 'detail colors', 'جزئیات', 'کلید', 'رنگ'),
							),
							array(
								'id'             => 'irantheme_up_sells_products_hover_mode_radio',
								'label'            => __(
									'سبک واکنش به محصول (hover)',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید نوع واکنش به عبور نشانگر از روی محصولات (hover) را مشخص کنید.', self::$static_textdomain),
								'type'            => 'radio',
								'options'        => array('normal' => 'ساده', 'shadow' => 'سایه', 'scale' => 'بزرگنمایی'),
								'default'        => 'normal',
								'keywords' => array('واکنش نشانگر محصولات', 'استایل محصولات', 'ویژگی های محصولات', 'product hover', 'woocommerce product style', 'product style', 'hover product', 'hover', 'هاور')
							),
							array(
								'id'             => 'irantheme_up_sells_products_image_hover_mode_radio',
								'label'            => __(
									'سبک واکنش به تصویر (hover)',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید نوع واکنش به عبور نشانگر از روی تصویر محصول (hover) را مشخص کنید.', self::$static_textdomain),
								'type'            => 'radio',
								'options'        => array('normal' => 'ساده', 'shine' => 'رهگذر', 'flashing' => 'فلش', 'circle' => 'گرد'),
								'default'        => 'normal',
								'keywords' => array('واکنش نشانگر محصولات', 'استایل محصولات', 'ویژگی های محصولات', 'product hover', 'woocommerce product style', 'product style', 'hover product', 'hover', 'هاور')
							),
							array(
								'id'             => 'irantheme_up_sells_products_image_changer_checkbox',
								'label'            => __(
									'تغییر تصویر در زمان هاور',
									self::$static_textdomain
								),
								'description'    => __('در صورت تمایل به تغییر تصویر با تصاویر گالری در زمان واکنش ماوس (Hover) می توانید از این گزینه استفاده کنید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'on',
								'keywords' => array('hover product image', 'واکنش ماوس', 'هاور محصول', 'استایل محصول', 'product style', 'woocommerce product style'),
							),
							array(
								'id'             => 'irantheme_up_sells_products_transparent_checkbox',
								'label'            => __(
									'پس زمینه شیشه ای محصول',
									self::$static_textdomain
								),
								'description'    => __('در صورت فعال سازی این گزینه پس زمینه محصول به صورت شیشه ای و بدون رنگ نمایش داده خواهد شد.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('پس زمینه شیشه ای محصول', 'product transparent'),
							),
							array(
								'id'             => 'irantheme_up_sells_products_border_checkbox',
								'label'            => __(
									'خط دور محصول',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید وضعیت نمایش یا عدم نمایش خط دور هر نوشته (بوردر) را مشخص نمایید. در صوررت فعال نمودن این گزینه (محصول) ها به شکل باکسی در می آیند.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('بوردر محصول', 'خط دور محصول', 'prodcut border', 'border style product'),
							),
							array(
								'id'             => 'irantheme_up_sells_products_shadow_checkbox',
								'label'            => __(
									'سایه دور پست',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید وضعیت نمایش یا عدم نمایش سایه دور هر نوشته(پست) را مشخص نمایید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('hieght', 'ارتفاع', 'fullscreen', 'تمام صفحه', 'ارتباطات'),
							),
							array(
								'id'             => 'irantheme_up_sells_products_padding_status_checkbox',
								'label'            => __(
									'حاشیه دور پست',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید حاشیه ای اضافی به دور هر محصول اعمال نمایید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('حاشیه دور پست', 'product padding'),
							),
							array(
								'id'             => 'irantheme_up_sells_products_border_radius_range',
								'label'            => __(
									'خمیدگی حاشیه',
									self::$static_textdomain
								),
								'description'    => __('با اسفاده از این گزینه می توانید خمیدگی حاشیه (Border Radius) را تعیین کنید.(واحد rem می باشد)', self::$static_textdomain),
								'type'            => 'range',
								'min' => 0,
								'max' => 2.5,
								'step' => 0.1,
								'default'        => 0.5,
								'placeholder'    => __('Placeholder text', self::$static_textdomain),
								'keywords' => array('خمیدگی حاشیه', 'related product border radius',),
							),
							array(
								'id'             => 'irantheme_up_sells_products_features_checkbox_multi',
								'label'            => __(
									'ویژگی های محصول',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید نوع جزئیات قابل نمایش برای هر محصول را مشخص کنید.مانند عنوان، دسته بندی و غیره... ', self::$static_textdomain),
								'type'            => 'checkbox_multi',
								'options'        => array('image' => 'تصویر', 'category' => 'دسته بندی', 'title' => 'عنوان',  'excerpt' => 'متن توضیحات', 'rating' => 'امتیاز', 'price' => 'قیمت', 'cart' => 'افزودن به سبد'),
								'default'        =>  'image,category,title,rating,price,cart',
								'keywords' => array('محصولات', 'محصولات ووکامرس', 'woocommerce products')
							),
							array(
								'id'             => 'irantheme_up_sells_products_extra_options_checkbox',
								'label'            => __(
									'نمایش امکانات اضافی',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید امکانات اضافی از قبیل افزودن به سبد خرید، افزودن به علاقه مندی و مشاهده سریع محصول را بر روی تصویر محصول نمایش دهید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        =>  'on',
								'keywords' => array('product options', 'ویژگی های اضافی', 'نمایش ویژگی های اضافی', 'افزودن به سبد خرید', 'product add to cart', 'woocommerce product style'),
								'id_dependents_on' => array('irantheme_up_sells_products_extra_option_on_image_style_radio', 'irantheme_up_sells_products_extra_options_hover_checkbox'),
								'id_dependents_off' => array(),
								'base_priority' => 'checkbox_base',
								'selector_priority' => 'up_sells_products_extra_options',
								'z_index' => 0
							),
							array(
								'id'             => 'irantheme_up_sells_products_extra_option_on_image_style_radio',
								'label'            => __('استایل نمایش امکانات اضافی روی تصویر', self::$static_textdomain),
								'description'    => __('در صورت انتخاب "نمایش روی تصویر" می توانید با استفاده از این گزینه استایل امکانات اضافی را انتخاب کنید.', self::$static_textdomain),
								'type'            => 'radio',
								'thumbnail_status' => true,
								'options'        => array(
									'vertical-left' => array('', 'product-extra-option-vertical-left.png'),
									'horizontal-center' => array('', 'product-extra-option-horizontal-center.png'),
									'horizontal-bottom' => array('', 'product-exrat-option-horizontal-bottom.png'),
								),
								'default'        =>  'vertical-left',
								'keywords' => array('امکانات اضافی', 'امکانات اضافی محصول', 'استایل امکانات اضافی محصول', 'products options', 'product extra options'),
							),
							array(
								'id'             => 'irantheme_up_sells_products_extra_options_hover_checkbox',
								'label'            => __(
									'انیمیشن امکانات اضافی',
									self::$static_textdomain
								),
								'description'    => __('در صورت فعال بودن این گزینه و انتخاب "نمایش روی تصویر"، امکانات اضافی روی تصویر هنگام عبور نشانگر از روی آنها همراه با انیمیشن ورود و خروج نمایش داده می شوند.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        =>  'on',
								'keywords' => array('امکانات اضافی', 'امکانات اضافی محصول', 'انیمیشن امکانات اضافی محصول', 'products options', 'product extra options'),
							),
							array(
								'id'             => 'irantheme_up_sells_products_excerpt_count_number',
								'label'            => __(
									'تعداد کلمه متن توضیحات',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید تعداد کلمه های توضیح محصول را مشخص کنید.', self::$static_textdomain),
								'type'            => 'number',
								'step' => 1,
								'default'        =>  18,
								'min' => 4,
								'max' => 40,
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('تعداد کلمه', 'تعداد کلمه محصول', 'اندازه توضیحات محصول', 'count description of products', 'number of excerpt', 'excerpt product'),
							),
							array(
								'id'             => 'irantheme_up_sells_products_col_count_radio',
								'label'            => __('تعداد ستون محصولات', self::$static_textdomain),
								'description'    => __('با استفاده از این گزینه شما می توانید تعداد ستون های قابل نمایش برای محصولات را مشخص کنید', self::$static_textdomain),
								'type'            => 'radio',
								'thumbnail_status' => true,
								'options'        => array(
									'col-3' => array('سه ستون', 'col-3.png'),
									'col-4' => array('چهار ستون', 'col-4.png'),
									'col-5' => array('پنج ستون', 'col-5.png'),
									'col-6' => array('شش ستون', 'col-6.png'),
									'col-7' => array('هفت ستون', 'col-7.png'),
									'col-8' => array('هشت ستون', 'col-8.png'),
								),
								'default'        =>  'col-6',
								'keywords' => array('box', 'col', 'author', 'تعداد', 'سرویس', 'تعداد در هر ردیف'),
							),
							array(
								'id'             => 'irantheme_up_sells_products_discount_checkbox',
								'label'            => __(
									'نمایش درصد تخفیف',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید درصد تخفیف را به صورت یک کادر بر روی تصویر محصول مشاهده نمایید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        =>  'on',
								'keywords' => array('درصد تخفیف محصول', 'تخفیف محصول', 'product discount', 'discount'),
							),
							array(
								'id'             => 'irantheme_up_sells_products_total_sales_checkbox',
								'label'            => __(
									'نمایش تعداد فروش',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید درصد تخفیف را به صورت یک کادر بر روی تصویر محصول مشاهده نمایید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        =>  'off',
								'keywords' => array('نمایش تعداد فروش', 'تعداد فروش محصول', 'product total sales', 'count of sales', 'number of sales'),
							),
							array(
								'id'             => 'irantheme_up_sells_products_discount_schedule_checkbox',
								'label'            => __(
									'نمایش شمارنده تخفیف',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید شمارنده تخفیفی که بر روی محصول اعمال کرده اید را نمایش یا مخفی نمایید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        =>  'off',
								'keywords' => array('نمایش شمارنده تخفیف', 'number of sales'),
							),
							array(
								'id'             => 'irantheme_up_sells_products_color_attribute_checkbox',
								'label'            => __(
									'نمایش رنگ محصول',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید رنگ های موجود برای هر محصول را مشاهده نمایید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'on',
								'keywords' => array('نمایش رنگ محصول', 'color of product'),
							),
						)
					),
					array(
						'title' => 'محصولات مکمل (cross-sells)',
						'id' => 'irantheme_cross_sells_products',
						'description'            => __('در این بخش شما می توانید تنظمیات مربوط به ظاهر بخش محصولات مکمل (cross-sell) که در صفحه سبد خرید نمایش داده می شود را تنظیم نمایید.', self::$static_textdomain),
						'fields' => array(
							array(
								'id'             => 'irantheme_cross_sells_products_align_content_radio',
								'label'            => __('تراز محتوا', self::$static_textdomain),
								'description'    => __('با استفاده از این گزینه می توانید تراز جزئیات پست ها را مشخص کنید.', self::$static_textdomain),
								'type'            => 'radio',
								'thumbnail_status' => true,
								'options'        => array('right' => array('راست چین', 'text-align-right.png'), 'center' => array('وسط چین', 'text-align-center.png'), 'left' => array('چپ چین', 'text-align-left.png')),
								'default'        => 'right',
								'keywords' => array('box', 'col', 'author', 'تعداد', 'سرویس', 'تعداد در هر ردیف'),
							),
							array(
								'id'             => 'irantheme_cross_sells_products_details_color_select',
								'label'            => __(
									'رنگ جزئیات',
									self::$static_textdomain
								),
								'description'    => __('برای انتخاب رنگ جزئیات بخش از لیست رنگ دلخواه خود را انتخاب کنید. این رنگ تاثیر مستقیمی در زیبایی جزئيات دارد.', self::$static_textdomain),
								'type'            => 'select',
								'options'        => Irantheme_Color_Static::get_all_colors_options(),
								'color_preview' => true,
								'default'        => 'primary',
								'keywords' => array('رنگ جزئیات', 'detail colors', 'جزئیات', 'کلید', 'رنگ'),
							),
							array(
								'id'             => 'irantheme_cross_sells_products_hover_mode_radio',
								'label'            => __(
									'سبک واکنش به محصول (hover)',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید نوع واکنش به عبور نشانگر از روی محصولات (hover) را مشخص کنید.', self::$static_textdomain),
								'type'            => 'radio',
								'options'        => array('normal' => 'ساده', 'shadow' => 'سایه', 'scale' => 'بزرگنمایی'),
								'default'        => 'normal',
								'keywords' => array('واکنش نشانگر محصولات', 'استایل محصولات', 'ویژگی های محصولات', 'product hover', 'woocommerce product style', 'product style', 'hover product', 'hover', 'هاور')
							),
							array(
								'id'             => 'irantheme_cross_sells_products_image_hover_mode_radio',
								'label'            => __(
									'سبک واکنش به تصویر (hover)',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید نوع واکنش به عبور نشانگر از روی تصویر محصول (hover) را مشخص کنید.', self::$static_textdomain),
								'type'            => 'radio',
								'options'        => array('normal' => 'ساده', 'shine' => 'رهگذر', 'flashing' => 'فلش', 'circle' => 'گرد'),
								'default'        => 'normal',
								'keywords' => array('واکنش نشانگر محصولات', 'استایل محصولات', 'ویژگی های محصولات', 'product hover', 'woocommerce product style', 'product style', 'hover product', 'hover', 'هاور')
							),
							array(
								'id'             => 'irantheme_cross_sells_products_image_changer_checkbox',
								'label'            => __(
									'تغییر تصویر در زمان هاور',
									self::$static_textdomain
								),
								'description'    => __('در صورت تمایل به تغییر تصویر با تصاویر گالری در زمان واکنش ماوس (Hover) می توانید از این گزینه استفاده کنید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'on',
								'keywords' => array('hover product image', 'واکنش ماوس', 'هاور محصول', 'استایل محصول', 'product style', 'woocommerce product style'),
							),
							array(
								'id'             => 'irantheme_cross_sells_products_transparent_checkbox',
								'label'            => __(
									'پس زمینه شیشه ای محصول',
									self::$static_textdomain
								),
								'description'    => __('در صورت فعال سازی این گزینه پس زمینه محصول به صورت شیشه ای و بدون رنگ نمایش داده خواهد شد.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('پس زمینه شیشه ای محصول', 'product transparent'),
							),
							array(
								'id'             => 'irantheme_cross_sells_products_border_checkbox',
								'label'            => __(
									'خط دور محصول',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید وضعیت نمایش یا عدم نمایش خط دور هر نوشته (بوردر) را مشخص نمایید. در صوررت فعال نمودن این گزینه (محصول) ها به شکل باکسی در می آیند.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('بوردر محصول', 'خط دور محصول', 'prodcut border', 'border style product'),
							),
							array(
								'id'             => 'irantheme_cross_sells_products_shadow_checkbox',
								'label'            => __(
									'سایه دور پست',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید وضعیت نمایش یا عدم نمایش سایه دور هر نوشته(پست) را مشخص نمایید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('hieght', 'ارتفاع', 'fullscreen', 'تمام صفحه', 'ارتباطات'),
							),
							array(
								'id'             => 'irantheme_cross_sells_products_padding_status_checkbox',
								'label'            => __(
									'حاشیه دور پست',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید حاشیه ای اضافی به دور هر محصول اعمال نمایید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('حاشیه دور پست', 'product padding'),
							),
							array(
								'id'             => 'irantheme_cross_sells_products_border_radius_range',
								'label'            => __(
									'خمیدگی حاشیه',
									self::$static_textdomain
								),
								'description'    => __('با اسفاده از این گزینه می توانید خمیدگی حاشیه (Border Radius) را تعیین کنید.(واحد rem می باشد)', self::$static_textdomain),
								'type'            => 'range',
								'min' => 0,
								'max' => 2.5,
								'step' => 0.1,
								'default'        => 0.5,
								'placeholder'    => __('Placeholder text', self::$static_textdomain),
								'keywords' => array('خمیدگی حاشیه', 'related product border radius',),
							),
							array(
								'id'             => 'irantheme_cross_sells_products_features_checkbox_multi',
								'label'            => __(
									'ویژگی های محصول',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید نوع جزئیات قابل نمایش برای هر محصول را مشخص کنید.مانند عنوان، دسته بندی و غیره... ', self::$static_textdomain),
								'type'            => 'checkbox_multi',
								'options'        => array('image' => 'تصویر', 'category' => 'دسته بندی', 'title' => 'عنوان',  'excerpt' => 'متن توضیحات', 'rating' => 'امتیاز', 'price' => 'قیمت', 'cart' => 'افزودن به سبد'),
								'default'        =>  'image,category,title,rating,price,cart',
								'keywords' => array('محصولات', 'محصولات ووکامرس', 'woocommerce products')
							),
							array(
								'id'             => 'irantheme_cross_sells_products_extra_options_checkbox',
								'label'            => __(
									'نمایش امکانات اضافی',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید امکانات اضافی از قبیل افزودن به سبد خرید، افزودن به علاقه مندی و مشاهده سریع محصول را بر روی تصویر محصول نمایش دهید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        =>  'on',
								'keywords' => array('product options', 'ویژگی های اضافی', 'نمایش ویژگی های اضافی', 'افزودن به سبد خرید', 'product add to cart', 'woocommerce product style'),
								'id_dependents_on' => array('irantheme_cross_sells_products_extra_option_on_image_style_radio', 'irantheme_cross_sells_products_extra_options_hover_checkbox'),
								'id_dependents_off' => array(),
								'base_priority' => 'cross_sells_products_checkbox_base',
								'selector_priority' => 'extra_options',
								'z_index' => 0
							),
							array(
								'id'             => 'irantheme_cross_sells_products_extra_option_on_image_style_radio',
								'label'            => __('استایل نمایش امکانات اضافی روی تصویر', self::$static_textdomain),
								'description'    => __('در صورت انتخاب "نمایش روی تصویر" می توانید با استفاده از این گزینه استایل امکانات اضافی را انتخاب کنید.', self::$static_textdomain),
								'type'            => 'radio',
								'thumbnail_status' => true,
								'options'        => array(
									'vertical-left' => array('', 'product-extra-option-vertical-left.png'),
									'horizontal-center' => array('', 'product-extra-option-horizontal-center.png'),
									'horizontal-bottom' => array('', 'product-exrat-option-horizontal-bottom.png'),
								),
								'default'        =>  'vertical-left',
								'keywords' => array('امکانات اضافی', 'امکانات اضافی محصول', 'استایل امکانات اضافی محصول', 'products options', 'product extra options'),
							),
							array(
								'id'             => 'irantheme_cross_sells_products_extra_options_hover_checkbox',
								'label'            => __(
									'انیمیشن امکانات اضافی',
									self::$static_textdomain
								),
								'description'    => __('در صورت فعال بودن این گزینه و انتخاب "نمایش روی تصویر"، امکانات اضافی روی تصویر هنگام عبور نشانگر از روی آنها همراه با انیمیشن ورود و خروج نمایش داده می شوند.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        =>  'on',
								'keywords' => array('امکانات اضافی', 'امکانات اضافی محصول', 'انیمیشن امکانات اضافی محصول', 'products options', 'product extra options'),
							),
							array(
								'id'             => 'irantheme_cross_sells_products_excerpt_count_number',
								'label'            => __(
									'تعداد کلمه متن توضیحات',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید تعداد کلمه های توضیح محصول را مشخص کنید.', self::$static_textdomain),
								'type'            => 'number',
								'step' => 1,
								'default'        =>  18,
								'min' => 4,
								'max' => 40,
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('تعداد کلمه', 'تعداد کلمه محصول', 'اندازه توضیحات محصول', 'count description of products', 'number of excerpt', 'excerpt product'),
							),
							array(
								'id'             => 'irantheme_cross_sells_products_col_count_radio',
								'label'            => __('تعداد ستون محصولات', self::$static_textdomain),
								'description'    => __('با استفاده از این گزینه شما می توانید تعداد ستون های قابل نمایش برای محصولات را مشخص کنید', self::$static_textdomain),
								'type'            => 'radio',
								'thumbnail_status' => true,
								'options'        => array(
									'col-3' => array('سه ستون', 'col-3.png'),
									'col-4' => array('چهار ستون', 'col-4.png'),
									'col-5' => array('پنج ستون', 'col-5.png'),
									'col-6' => array('شش ستون', 'col-6.png'),
									'col-7' => array('هفت ستون', 'col-7.png'),
									'col-8' => array('هشت ستون', 'col-8.png'),
								),
								'default'        =>  'col-6',
								'keywords' => array('box', 'col', 'author', 'تعداد', 'سرویس', 'تعداد در هر ردیف'),
							),
							array(
								'id'             => 'irantheme_cross_sells_products_discount_checkbox',
								'label'            => __(
									'نمایش درصد تخفیف',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید درصد تخفیف را به صورت یک کادر بر روی تصویر محصول مشاهده نمایید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        =>  'on',
								'keywords' => array('درصد تخفیف محصول', 'تخفیف محصول', 'product discount', 'discount'),
							),
							array(
								'id'             => 'irantheme_cross_sells_products_total_sales_checkbox',
								'label'            => __(
									'نمایش تعداد فروش',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید درصد تخفیف را به صورت یک کادر بر روی تصویر محصول مشاهده نمایید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        =>  'off',
								'keywords' => array('نمایش تعداد فروش', 'تعداد فروش محصول', 'product total sales', 'count of sales', 'number of sales'),
							),
							array(
								'id'             => 'irantheme_cross_sells_products_discount_schedule_checkbox',
								'label'            => __(
									'نمایش شمارنده تخفیف',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید شمارنده تخفیفی که بر روی محصول اعمال کرده اید را نمایش یا مخفی نمایید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        =>  'off',
								'keywords' => array('نمایش شمارنده تخفیف', 'number of sales'),
							),
						)
					),
					self::get_irregular_banner_fields('irantheme_wc', array('background', 'type', 'title'), 'تنظیم بنر صفحه فروشگاه'),
					array(
						'title' => 'صفحه فروشگاه',
						'id' => 'irantheme_wc_archive',
						'description'            => __('در این بخش شما می توانید تنظمیات مربوط به محتوای صفحه آرشیو محصولات (فروشگاه) را تنظیم نمایید.', self::$static_textdomain),
						'fields' => array(
							array(
								'id'             => 'irantheme_wc_archive_base_color_select',
								'label'            => __(
									'رنگ پایه',
									self::$static_textdomain
								),
								'description'    => __('برای انتخاب رنگ پایه از لیست رنگ دلخواه خود را انتخاب کنید.رنگ محتواو پس زمینه بر اساس این رنگ تنظیم خواهد شد.', self::$static_textdomain),
								'type'            => 'select',
								'options'        => array('white' => 'سفید', 'dark' => 'مشکی'),
								'default'        => 'white',
								'keywords' => array('color', 'background', 'پس زمینه', 'رنگ'),
							),
							array(
								'id'             => 'irantheme_wc_archive_default_ordering_select',
								'label'            => __(
									'مرتب سازی',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید حالت پیشفرض مرتب سازی محصولات را انتخاب نمایید.', self::$static_textdomain),
								'type'            => 'select',
								'options'        => array('menu_order' => 'پیش فرض', 'popularity' => 'محبوبیت', 'rating' => 'امتیاز', 'date' => 'جدیدترین', 'price' => 'ارزان ترین', 'price-desc' => 'گران ترین'),
								'default'        => 'date',
								'keywords' => array('order', 'ordering', 'محصول', 'مرتب سازی'),
							),
							array(
								'id'             => 'irantheme_wc_archive_col_count_radio',
								'label'            => __('تعداد ستون محصولات', self::$static_textdomain),
								'description'    => __('با استفاده از این گزینه شما می توانید تعداد ستون های قابل نمایش برای محصولات را مشخص کنید', self::$static_textdomain),
								'type'            => 'radio',
								'thumbnail_status' => true,
								'options'        => array(
									'col-3' => array('سه ستون', 'col-3.png'),
									'col-4' => array('چهار ستون', 'col-4.png'),
									'col-5' => array('پنج ستون', 'col-5.png'),
									'col-6' => array('شش ستون', 'col-6.png'),
									'col-7' => array('هفت ستون', 'col-7.png'),
									'col-8' => array('هشت ستون', 'col-8.png')
								),
								'default'        => 'col-4',
								'keywords' => array('box', 'col', 'author', 'تعداد', 'سرویس', 'تعداد در هر ردیف'),

							),
							array(
								'id'             => 'irantheme_wc_archive_product_num',
								'label'            => __(
									'تعداد محصول در هر صفحه',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید تعداد محصول قابل نمایش در هر صفحه از آرشیو را مشخص نمایید.', self::$static_textdomain),
								'type'            => 'number',
								'default'        => '12',
								'keywords' => array('number of product', 'per page', 'count of product', 'product', 'count', 'number', 'page', 'تعداد', 'تعداد محصول در هر صفحه', 'صفحه'),
							),
							array(
								'id'             => 'irantheme_wc_archive_details_color_select',
								'label'            => __(
									'رنگ جزئیات',
									self::$static_textdomain
								),
								'description'    => __('برای انتخاب رنگ جزئیات بخش از لیست رنگ دلخواه خود را انتخاب کنید. این رنگ تاثیر مستقیمی در زیبایی جزئيات دارد.', self::$static_textdomain),
								'type'            => 'select',
								'options'        => Irantheme_Color_Static::get_all_colors_options(),
								'color_preview' => true,
								'default'        => 'primary',
								'keywords' => array('رنگ جزئیات', 'detail colors', 'جزئیات', 'کلید', 'رنگ'),
							),
							array(
								'id'             => 'irantheme_wc_archive_details_color_select2',
								'label'            => __(
									'رنگ جزئیات دوم',
									self::$static_textdomain
								),
								'description'    => __('برای انتخاب رنگ جزئیات دوم بخش از لیست رنگ دلخواه خود را انتخاب کنید. این رنگ تاثیر مستقیمی در زیبایی جزئيات دارد.', self::$static_textdomain),
								'type'            => 'select',
								'options'        => Irantheme_Color_Static::get_all_colors_options(),
								'color_preview' => true,
								'default'        => 'secondary',
								'keywords' => array('رنگ جزئیات', 'detail colors', 'جزئیات', 'کلید', 'رنگ'),
							),
							array(
								'id'             => 'irantheme_wc_archive_border_radius_range',
								'label'            => __(
									'خمیدگی حاشیه',
									self::$static_textdomain
								),
								'description'    => __('با اسفاده از این گزینه می توانید خمیدگی حاشیه (Border Radius) را تعیین کنید.(واحد rem می باشد)', self::$static_textdomain),
								'type'            => 'range',
								'min' => 0,
								'max' => 2.5,
								'step' => 0.1,
								'default'        => 0.5,
								'placeholder'    => __('Placeholder text', self::$static_textdomain),
								'keywords' => array('خمیدگی حاشیه', 'banner border radius',),
							),
							array(
								'id'             => 'irantheme_wc_archive_features_checkbox_multi',
								'label'            => __(
									'ویژگی های محصول',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید نوع جزئیات قابل نمایش برای هر محصول را مشخص کنید.مانند عنوان، دسته بندی و غیره... ', self::$static_textdomain),
								'type'            => 'checkbox_multi',
								'options'        => array('image' => 'تصویر', 'category' => 'دسته بندی', 'title' => 'عنوان',  'excerpt' => 'متن توضیحات', 'rating' => 'امتیاز', 'price' => 'قیمت', 'cart' => 'افزودن به سبد'),
								'default'        => 'image,category,title,rating,price,cart',
								'keywords' => array('محصولات', 'محصولات ووکامرس', 'woocommerce products'),

							),
							array(
								'id'             => 'irantheme_wc_archive_extra_options_checkbox',
								'label'            => __(
									'نمایش امکانات اضافی',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید امکانات اضافی از قبیل افزودن به سبد خرید، افزودن به علاقه مندی و مشاهده سریع محصول را بر روی تصویر محصول نمایش دهید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('product options', 'ویژگی های اضافی', 'نمایش ویژگی های اضافی', 'افزودن به سبد خرید', 'product add to cart', 'woocommerce product style'),
								'id_dependents_on' => array('irantheme_wc_archive_extra_option_on_image_style_radio', 'irantheme_wc_archive_extra_options_hover_checkbox'),
								'id_dependents_off' => array(),
								'base_priority' => 'checkbox_base',
								'selector_priority' => 'wc_archive_extra_options',
								'z_index' => 0,

							),
							array(
								'id'             => 'irantheme_wc_archive_extra_option_on_image_style_radio',
								'label'            => __('استایل نمایش امکانات اضافی روی تصویر', self::$static_textdomain),
								'description'    => __('در صورت انتخاب "نمایش روی تصویر" می توانید با استفاده از این گزینه استایل امکانات اضافی را انتخاب کنید.', self::$static_textdomain),
								'type'            => 'radio',
								'thumbnail_status' => true,
								'options'        => array(
									'vertical-left' => array('', 'product-extra-option-vertical-left.png'),
									'horizontal-center' => array('', 'product-extra-option-horizontal-center.png'),
									'horizontal-bottom' => array('', 'product-exrat-option-horizontal-bottom.png'),
								),
								'default'        => 'vertical-left',
								'keywords' => array('امکانات اضافی', 'امکانات اضافی محصول', 'استایل امکانات اضافی محصول', 'products options', 'product extra options'),
							),
							array(
								'id'             => 'irantheme_wc_archive_extra_options_hover_checkbox',
								'label'            => __(
									'انیمیشن امکانات اضافی',
									self::$static_textdomain
								),
								'description'    => __('در صورت فعال بودن این گزینه و انتخاب "نمایش روی تصویر"، امکانات اضافی روی تصویر هنگام عبور نشانگر از روی آنها همراه با انیمیشن ورود و خروج نمایش داده می شوند.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'on',
								'keywords' => array('امکانات اضافی', 'امکانات اضافی محصول', 'انیمیشن امکانات اضافی محصول', 'products options', 'product extra options'),
							),
							array(
								'id'             => 'irantheme_wc_archive_excerpt_count_number',
								'label'            => __(
									'تعداد کلمه متن توضیحات',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید تعداد کلمه های توضیح محصول را مشخص کنید.', self::$static_textdomain),
								'type'            => 'number',
								'step' => 1,
								'default'        => 18,
								'min' => 4,
								'max' => 40,
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('تعداد کلمه', 'تعداد کلمه محصول', 'اندازه توضیحات محصول', 'count description of products', 'number of excerpt', 'excerpt product'),

							),
							array(
								'id'             => 'irantheme_wc_archive_discount_schedule_checkbox',
								'label'            => __(
									'نمایش شمارنده تخفیف',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید شمارنده تخفیفی که بر روی محصول اعمال کرده اید را نمایش یا مخفی نمایید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'on',
								'keywords' => array('نمایش شمارنده تخفیف', 'number of sales'),
							),
							array(
								'id'             => 'irantheme_wc_archive_discount_checkbox',
								'label'            => __(
									'نمایش درصد تخفیف',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید درصد تخفیف را به صورت یک کادر بر روی تصویر محصول مشاهده نمایید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'on',
								'keywords' => array('درصد تخفیف محصول', 'تخفیف محصول', 'product discount', 'discount'),

							),
							array(
								'id'             => 'irantheme_wc_archive_total_sales_checkbox',
								'label'            => __(
									'نمایش تعداد فروش',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید درصد تخفیف را به صورت یک کادر بر روی تصویر محصول مشاهده نمایید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('نمایش تعداد فروش', 'تعداد فروش محصول', 'product total sales', 'count of sales', 'number of sales'),

							),
							array(
								'id'             => 'irantheme_wc_archive_color_attribute_checkbox',
								'label'            => __(
									'نمایش رنگ محصول',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید رنگ های موجود برای هر محصول را مشاهده نمایید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'on',
								'keywords' => array('نمایش رنگ محصول', 'color of product'),
							),
							array(
								'id'             => 'irantheme_wc_archive_align_content_radio',
								'label'            => __('تراز محتوا', self::$static_textdomain),
								'description'    => __('با استفاده از این گزینه می توانید تراز جزئیات پست ها را مشخص کنید.', self::$static_textdomain),
								'type'            => 'radio',
								'thumbnail_status' => true,
								'options'        => array('right' => array('راست چین', 'text-align-right.png'), 'center' => array('وسط چین', 'text-align-center.png'), 'left' => array('چپ چین', 'text-align-left.png')),
								'default'        => 'center',
								'keywords' => array('box', 'col', 'author', 'تعداد', 'سرویس', 'تعداد در هر ردیف'),

							),
							array(
								'id'             => 'irantheme_wc_archive_hover_mode_radio',
								'label'            => __(
									'سبک واکنش به محصول (hover)',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید نوع واکنش به عبور نشانگر از روی محصولات (hover) را مشخص کنید.', self::$static_textdomain),
								'type'            => 'radio',
								'options'        => array('normal' => 'ساده', 'shadow' => 'سایه', 'scale' => 'بزرگنمایی'),
								'default'        => 'normal',
								'keywords' => array('واکنش نشانگر محصولات', 'استایل محصولات', 'ویژگی های محصولات', 'product hover', 'woocommerce product style', 'product style', 'hover product', 'hover', 'هاور'),

							),
							array(
								'id'             => 'irantheme_wc_archive_image_hover_mode_radio',
								'label'            => __(
									'سبک واکنش به تصویر (hover)',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید نوع واکنش به عبور نشانگر از روی تصویر محصول (hover) را مشخص کنید.', self::$static_textdomain),
								'type'            => 'radio',
								'options'        => array('normal' => 'ساده', 'shine' => 'رهگذر', 'flashing' => 'فلش', 'circle' => 'گرد'),
								'default'        => 'shine',
								'keywords' => array('واکنش نشانگر محصولات', 'استایل محصولات', 'ویژگی های محصولات', 'product hover', 'woocommerce product style', 'product style', 'hover product', 'hover', 'هاور'),

							),
							array(
								'id'             => 'irantheme_wc_archive_image_changer_checkbox',
								'label'            => __(
									'تغییر تصویر در زمان هاور',
									self::$static_textdomain
								),
								'description'    => __('در صورت تمایل به تغییر تصویر با تصاویر گالری در زمان واکنش ماوس (Hover) می توانید از این گزینه استفاده کنید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'on',
								'keywords' => array('hover product image', 'واکنش ماوس', 'هاور محصول', 'استایل محصول', 'product style', 'woocommerce product style'),

							),
							array(
								'id'             => 'irantheme_wc_archive_transparent_checkbox',
								'label'            => __(
									'پس زمینه شیشه ای محصول',
									self::$static_textdomain
								),
								'description'    => __('در صورت فعال سازی این گزینه پس زمینه محصول به صورت شیشه ای و بدون رنگ نمایش داده خواهد شد.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'on',
								'keywords' => array('پس زمینه شیشه ای محصول', 'product transparent'),

							),
							array(
								'id'             => 'irantheme_wc_archive_border_checkbox',
								'label'            => __(
									'خط دور محصول',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید وضعیت نمایش یا عدم نمایش خط دور هر نوشته (بوردر) را مشخص نمایید. در صوررت فعال نمودن این گزینه (محصول) ها به شکل باکسی در می آیند.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('بوردر محصول', 'خط دور محصول', 'prodcut border', 'border style product'),

							),
							array(
								'id'             => 'irantheme_wc_archive_padding_status_checkbox',
								'label'            => __(
									'حاشیه دور پست',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید حاشیه ای اضافی به دور هر محصول اعمال نمایید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'on',
								'keywords' => array('حاشیه دور پست', 'product padding'),

							),
							array(
								'id'             => 'irantheme_wc_archive_shadow_checkbox',
								'label'            => __(
									'سایه دور پست',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید وضعیت نمایش یا عدم نمایش سایه دور هر نوشته(پست) را مشخص نمایید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('hieght', 'ارتفاع', 'fullscreen', 'تمام صفحه', 'ارتباطات'),

							),
							array(
								'id'             => 'irantheme_wc_archive_display_filter_checkbox',
								'label'            => __(
									'نمایش فیلترها',
									self::$static_textdomain
								),
								'description'    => __('برای تعیین وضعیت نمایش فیلتر ها در صفحه فروشگاه این گزینه را فعال یا غیرفعال نمایید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'on',
								'keywords' => array('نمایش فیلترها در صفحه فروشگاه', 'display filter in shop page'),
								'id_dependents_on' => array('irantheme_wc_archive_filter_widget_checkbox_multi'),
								'id_dependents_off' => array(),
								'base_priority' => 'checkbox_base',
								'selector_priority' => 'wc_archive_display_filter',
								'z_index' => 0
							),
							array(
								'id'             => 'irantheme_wc_archive_filter_widget_checkbox_multi',
								'label'            => __(
									'فیلترها پیش فرض',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید نمایش یا عدم نمایش فیلترهای پیش فرض را مشخص کنید. ', self::$static_textdomain),
								'type'            => 'checkbox_multi',
								'options'        => array('search' => 'جستجو', 'category' => 'دسته بندی', 'price' => 'محدوده قیمت',  'status' => 'وضعیت محصول'),
								'default'        => 'search,category,status,price',
								'keywords' => array('filter', 'فیلترهای فروشگاه', 'woocommerce filter')
							),
							array(
								'id'             => 'irantheme_wc_archive_display_category_count_checkbox',
								'label'            => __(
									'نمایش تعداد محصول جلوی نام هر دسته بندی',
									self::$static_textdomain
								),
								'description'    => __('برای تعیین وضعیت نمایش تعداد محصول موجود در هر دسته بندی در بخش دسته بندی در صفحه فروشگاه این گزینه را فعال یا غیرفعال نمایید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'on',
								'keywords' => array('نمایش تعداد محصول در دسته بندی در صفحه فروشگاه', 'display count of product in category in shop page'),
								'note' => __('این گزینه زمانی کار خواهد کرد که دسته بندی در صفحه فروشگاه فعال باشد.', self::$static_textdomain)
							),
							array(
								'id'             => 'irantheme_wc_archive_delete_empty_category_checkbox',
								'label'            => __(
									'حذف دسته بندی های بدون محصول',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید دسته بندی هایی که تعداد محصول آن ها صفر است را از بخش دسته بندی در صفحه فروشگاه حذف کنید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'on',
								'keywords' => array('نمایش تعداد محصول در دسته بندی در صفحه فروشگاه', 'display count of product in category in shop page'),
								'note' => __('این گزینه زمانی کار خواهد کرد که دسته بندی در صفحه فروشگاه فعال باشد.', self::$static_textdomain)
							),

						)
					),
					array(
						'title' => 'صفحه ورود و ثبت نام',
						'id' => 'irantheme_wc_login',
						'description'            => __('در این بخش شما می توانید تنظمیات مربوط به صفحه ورود و ایجاد حساب کاربری ووکامرس را تنظیم نمایید.', self::$static_textdomain),
						'fields' => array(
							array(
								'id'             => 'irantheme_wc_login_base_color_select',
								'label'            => __(
									'رنگ پایه',
									self::$static_textdomain
								),
								'description'    => __('برای انتخاب رنگ پایه از لیست رنگ دلخواه خود را انتخاب کنید.رنگ محتواو پس زمینه بصورت پیش فرض بر اساس این رنگ تنظیم خواهد شد.', self::$static_textdomain),
								'type'            => 'select',
								'options'        => array('white' => 'سفید', 'dark' => 'مشکی'),
								'default'        => 'white',
								'keywords' => array('color', 'base', 'login', 'account', 'حساب کاربری', 'ورود', 'رنگ پایه', 'رنگ'),
							),
							array(
								'id'             => 'irantheme_wc_login_display_mode',
								'label'            => __(
									'سبک صفحه ورود',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید سبک نمایش محتوای بخش معرفی محصول خود را مشخص کنید.', self::$static_textdomain),
								'type'            => 'radio',
								'thumbnail_status' => true,
								'options'        => array('floating-box-form' => array('باکسی شناور', 'login-floating-box-form-center.png'), 'dual-col-box-form' => array('باکسی دو ستونه شناور', 'login-dual-col-box-form-right.png'), 'split-form' => array('تمام صفحه دوستونه', 'login-split-form-right.png')),
								'default'        => 'floating-box-form',
								'id_dependents_on' => array(
									'floating-box-form' => array('irantheme_wc_login_display_floating_box_form_align', 'irantheme_wc_login_border_radius_range', 'irantheme_wc_login_background_color', 'irantheme_wc_login_background_image', 'irantheme_wc_login_background_image_mask_checkbox'),
									'dual-col-box-form' => array('irantheme_wc_login_display_dual_col_box_form_align', 'irantheme_wc_login_image_col_slider_checkbox', 'irantheme_wc_login_border_radius_range', 'irantheme_wc_login_background_color', 'irantheme_wc_login_background_image', 'irantheme_wc_login_background_image_mask_checkbox'),
									'split-form' => array('irantheme_wc_login_display_split_form_align', 'irantheme_wc_login_split_background_image', 'irantheme_wc_login_background_image_mask_checkbox'),
								),
								'id_dependents_off' => array(
									'floating-box-form' => array('irantheme_wc_login_display_dual_col_box_form_align', 'irantheme_wc_login_display_split_form_align', 'irantheme_wc_login_image_col_slider_checkbox', 'irantheme_wc_login_image_col_mono_img', 'irantheme_wc_login_image_col_slider_img', 'irantheme_wc_login_split_background_image'),
									'dual-col-box-form' => array('irantheme_wc_login_display_floating_box_form_align', 'irantheme_wc_login_display_split_form_align', 'irantheme_wc_login_split_background_image'),
									'split-form' => array('irantheme_wc_login_display_floating_box_form_align', 'irantheme_wc_login_display_dual_col_box_form_align', 'irantheme_wc_login_image_col_slider_checkbox', 'irantheme_wc_login_image_col_mono_img', 'irantheme_wc_login_image_col_slider_img', 'irantheme_wc_login_background_color', 'irantheme_wc_login_background_image'),
								),
								'keywords' => array('login', 'mode', 'سبک', 'ورود', 'چیدمان', 'alignment')
							),
							array(
								'id'             => 'irantheme_wc_login_display_floating_box_form_align',
								'label'            => __(
									'تراز بندی سبک باکسی شناور',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید سبک نمایش محتوای بخش معرفی محصول خود را مشخص کنید.', self::$static_textdomain),
								'type'            => 'radio',
								'thumbnail_status' => true,
								'options'        => array('right' => array('باکس - راست', 'login-floating-box-form-right.png'), 'center' => array('باکس - مرکز', 'login-floating-box-form-center.png'), 'left' => array('باکس - چپ', 'login-floating-box-form-left.png')),
								'default'        => 'center',
								'keywords' => array('login', 'mode', 'سبک', 'ورود', 'چیدمان', 'alignment')
							),
							array(
								'id'             => 'irantheme_wc_login_display_dual_col_box_form_align',
								'label'            => __(
									'تراز بندی سبک باکسی دو ستونه شناور',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید سبک نمایش محتوای بخش معرفی محصول خود را مشخص کنید.', self::$static_textdomain),
								'type'            => 'radio',
								'thumbnail_status' => true,
								'options'        => array('right' => array('فرم - راست', 'login-dual-col-box-form-right.png'), 'left' => array('فرم - چپ', 'login-dual-col-box-form-left.png')),
								'default'        => 'left',
								'keywords' => array('login', 'mode', 'سبک', 'ورود', 'چیدمان', 'alignment')
							),
							array(
								'id'             => 'irantheme_wc_login_display_split_form_align',
								'label'            => __(
									'تراز بندی سبک تمام صفحه دو ستونه',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید سبک نمایش محتوای بخش معرفی محصول خود را مشخص کنید.', self::$static_textdomain),
								'type'            => 'radio',
								'thumbnail_status' => true,
								'options'        => array('right' => array('فرم - راست', 'login-split-form-right.png'), 'left' => array('فرم - چپ', 'login-split-form-left.png')),
								'default'        => 'right',
								'keywords' => array('login', 'mode', 'سبک', 'ورود', 'چیدمان', 'alignment')
							),
							array(
								'id'             => 'irantheme_wc_login_image_col_slider_checkbox',
								'label'            => __(
									'نمایش اسلایدر در ستون تصویر',
									self::$static_textdomain
								),
								'description'    => __('انتخاب کنید که پس‌زمینه صفحه ورود و ثبت‌نام با یک تصویر پر شود یا با یک رنگ ثابت. این تنظیم فقط برای حالت نمایش "باکس شناور" اعمال می‌شود.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'id_dependents_on' => array('irantheme_wc_login_image_col_slider_img'),
								'id_dependents_off' => array('irantheme_wc_login_image_col_mono_img'),
								'base_priority' => 'checkbox_base',
								'selector_priority' => 'wc_login_dual_box_form_image_col',
								'z_index' => 0,
								'keywords' => array('login', 'type', 'نوع پس زمینه صفحه ورود/ثبت نام', 'background')
							),
							array(
								'id' 			=> 'irantheme_wc_login_image_col_mono_img',
								'label'			=> __('محتوای ستون تصویر (تصویر تکی)', self::$static_textdomain),
								'description'	=> __('تصویر مورد نظر خود برای ستون تصویر را از اینجا انتخاب کنید.', self::$static_textdomain),
								'type'			=> 'image',
								'default'		=> '',
								'placeholder'	=> '',
								'keywords' => array('account', 'login', 'پس زمینه', 'تصویر زمینه', 'background', 'عکس زمینه', 'bg')
							),
							array(
								'id'             => 'irantheme_wc_login_image_col_slider_img',
								'label'            => __('محتوای ستون تصویر (اسلایدر)', self::$static_textdomain),
								'description'    => __('لطفا رسانه های مد نظر خود را بارگذاری نمایید.', self::$static_textdomain),
								'type'            => 'media_gallery',
								'default'        => '',
								'keywords' => array('showcase', 'blog', 'master', 'hero', 'ویترین', 'هیرو', 'تصویر زمینه'),
							),
							array(
								'id' 			=> 'irantheme_wc_login_background_image',
								'label'			=> __('تصویر پس زمینه صفحه ورود/ثبت نام', self::$static_textdomain),
								'description'	=> __('تصویر زمینه ای برای صفحه ورود انتخاب کنید.', self::$static_textdomain),
								'note'	=> __('نکته :‌ تصویر پس زمینه بر رنگ پس زمینه اولویت دارد.', self::$static_textdomain),
								'type'			=> 'image',
								'default'		=> '',
								'placeholder'	=> '',
								'keywords' => array('account', 'login', 'پس زمینه', 'تصویر زمینه', 'background', 'عکس زمینه', 'bg')
							),
							array(
								'id'             => 'irantheme_wc_login_background_color',
								'label'            => __(
									'رنگ پس زمینه صفحه ورود/ثبت نام',
									self::$static_textdomain
								),
								'description'    => __('برای انتخاب رنگ پس زمینه از لیست، رنگ مورد نظر خود را انتخاب کنید.در صورت انتخاب حالت پیش فرض، رنگ پس زمینه بر اساس رنگ پایه تنظیم می شود.(تذکر: برای تنظیم هریک از رنگ های اصلی،فرعی،اولیه،ثانویه و اضافی به بخش " تنظیمات قالب -> رنگ های سایت " مراجعه نمایید.)', self::$static_textdomain),
								'type'            => 'select',
								'color_preview' => true,
								'options'        => Irantheme_Color_Static::get_all_colors_options(false),
								'default'        => 'off',
								'keywords' => array('price', 'رنگ قیمت', 'رنگ محصول', 'price color', 'color', 'product'),
							),
							array(
								'id' 			=> 'irantheme_wc_login_split_background_image',
								'label'			=> __('تصویر پس زمینه', self::$static_textdomain),
								'description'	=> __('تصویر زمینه ای برای صفحه ورود انتخاب کنید.', self::$static_textdomain),
								'type'			=> 'image',
								'default'		=> '',
								'placeholder'	=> '',
								'keywords' => array('account', 'login', 'پس زمینه', 'تصویر زمینه', 'background', 'عکس زمینه', 'bg')
							),
							array(
								'id'             => 'irantheme_wc_login_background_image_mask_checkbox',
								'label'            => __(
									'لایه پوشاننده پس زمینه',
									self::$static_textdomain
								),
								'description'    => __('لایه پوشاننده به عنصر شفافی گفته می شود که روی تصویر زمینه انتخابی شما را می پوشاند تا پس زمینه کمتر محتوای داخل سایت را تحت تاثیر قرار دهد.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'id_dependents_on' => array('irantheme_wc_login_background_image_mask_range', 'irantheme_wc_login_background_mask_color_select'),
								'base_priority' => 'checkbox_base',
								'selector_priority' => 'checkbox_login_image_mask',
								'z_index' => 0,
								'keywords' => array('background mask', 'mask', 'لایه پوشاننده'),
							),
							array(
								'id'             => 'irantheme_wc_login_background_mask_color_select',
								'label'            => __(
									'رنگ لایه پوشاننده',
									self::$static_textdomain
								),
								'description'    => __('برای انتخاب رنگ لایه پوشاننده از لیست رنگ دلخواه خود را انتخاب کنید.', self::$static_textdomain),
								'type'            => 'select',
								'color_preview' => true,
								'options'        => Irantheme_Color_Static::get_all_colors_options(false, false, true),
								'default'        => 'dark',
								'keywords' => array('color', 'mask', 'رنگ لایه پوشاننده'),
							),
							array(
								'id'             => 'irantheme_wc_login_background_image_mask_range',
								'label'            => __(
									'درجه لایه پوشاننده تصویر',
									self::$static_textdomain
								),
								'description'    => __('درجه لایه پوشاننده تصویر مربوط به میزان شفافیت و کدر بودن است و اثر به سزایی در نمایش تصویر پس زمینه دارد.', self::$static_textdomain),
								'type'            => 'range',
								'min' => 0,
								'max' => 1,
								'step' => 0.1,
								'default'        => 0.2,
								'placeholder'    => __('Placeholder text', self::$static_textdomain),
								'keywords' => array('mask', 'درجه لایه پوشاننده تصویر')
							),
							array(
								'id'             => 'irantheme_wc_login_border_radius_range',
								'label'            => __(
									'خمیدگی حاشیه',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید میزان خمیدگی حاشیه (border radius) کادر فرم را کنترل کنید.(واحد rem می باشد)', self::$static_textdomain),
								'type'            => 'range',
								'min' => 0,
								'max' => 3,
								'step' => 0.1,
								'default'        => 2,
								'placeholder'    => __('Placeholder text', self::$static_textdomain),
								'keywords' => array('radius', 'خمیدگی حاشیه')
							),
							array(
								'id'             => 'irantheme_wc_login_password_strength_checkbox',
								'label'            => __(
									'بررسی قدرت گذرواژه',
									self::$static_textdomain
								),
								'description'    => __('در صورت فعال بودن این گرینه قدرت گذرواژه انتخاب شده توسط کاربر در صفحه ثبت نام بررسی می گردد.با این کار امنیت حساب کاربری افزایش می یابد.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'on',
								'keywords' => array('پسورد', 'رمز عبور', 'password', 'گذرواژه', 'بررسی قدرت گذرواژه'),
							),
							array(
								'id'             => 'irantheme_wc_login_text_signin_desc',
								'label'            => __('متن خوش آمد گویی صفحه ورود', self::$static_textdomain),
								'description'    => __('با استفاده از این ورودی می توانید متن خوش آمد گویی زیر عنوان درون صفحه ورود را تنظیم نمایید.', self::$static_textdomain),
								'type'            => 'text',
								'default'        => 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ',
								'media_status' => false,
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('متن خوش آمد گویی', 'خوش آمد گویی',  'متن', 'copyright', 'copy', 'rules'),
							),
							array(
								'id'             => 'irantheme_wc_login_text_signup_desc',
								'label'            => __('متن خوش آمد گویی صفحه ثبت نام', self::$static_textdomain),
								'description'    => __('با استفاده از این ورودی می توانید متن خوش آمد گویی زیر عنوان درون صفحه ثبت نام را تنظیم نمایید.', self::$static_textdomain),
								'type'            => 'text',
								'default'        => 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ',
								'media_status' => false,
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('متن خوش آمد گویی', 'خوش آمد گویی',  'متن', 'copyright', 'copy', 'rules'),
							),
							array(
								'id'             => 'irantheme_wc_login_text_lost_pwd_desc',
								'label'            => __('متن خوش آمد گویی صفحه فراموشی گذرواژه', self::$static_textdomain),
								'description'    => __('با استفاده از این ورودی می توانید متن خوش آمد گویی زیر عنوان درون صفحه فراموشی گذرواژه را تنظیم نمایید.', self::$static_textdomain),
								'type'            => 'text',
								'default'        => 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ',
								'media_status' => false,
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('متن خوش آمد گویی', 'خوش آمد گویی',  'متن', 'copyright', 'copy', 'rules'),
							),
							array(
								'id'             => 'irantheme_wc_login_text_copyright',
								'label'            => __('متن کپی رایت', self::$static_textdomain),
								'description'    => __('با استفاده از این ورودی می توانید متن کپی رایت درون صفحه ورود را تنظیم نمایید.', self::$static_textdomain),
								'type'            => 'text_editor',
								'default'        => 'کلیه حقوق مادی و معنوی سایت محفوظ می باشد.',
								'media_status' => false,
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('متن کپی رایت', 'کپی رایت',  'متن', 'copyright', 'copy', 'rules'),
							),
						)

					),
					array(
						'title' => 'پیشخوان',
						'id' => 'irantheme_wc_dashboard',
						'description'            => __('در این بخش شما می توانید تنظمیات مربوط به صفحه پیشخوان ووکامرس را تنظیم نمایید.', self::$static_textdomain),
						'fields' => array(
							array(
								'id'             => 'irantheme_wc_dashboard_user_avatar_checkbox',
								'label'            => __(
									'تصویر پروفایل قابل انتخاب',
									self::$static_textdomain
								),
								'description'    => __('در صورت روشن بودن این قابلیت کاربر می تواند عکس پروفایل خود را ویرایش نماید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'note'    => __('در صورت روشن بودن این گزینه تصاویر انتخابی توسط کاربران، در پایگاه داده وردپرس شما (هاست) ذخیره گردیده و از بخش رسانه در پنل ادمین قابل مشاهده و حذف است.', self::$static_textdomain),
								'id_dependents_on' => array('irantheme_wc_dashboard_background_image_mask_range', 'irantheme_wc_dashboard_background_mask_color_select'),
								'keywords' => array('profile', 'پروفایل'),
							),
						)

					),
					array(
						'title' => 'صفحه خرید',
						'id' => 'irantheme_wc_shopping_cart',
						'description'            => __('امکانات مرتبط با صفحه خرید و سبد خرید', self::$static_textdomain),
						'fields' => array(
							array(
								'id'             => 'irantheme_wc_shopping_cart_delivery_date_checkbox',
								'label'            => __(
									'فعال سازی انتخاب تاریخ تحویل',
									self::$static_textdomain
								),
								'description'    => __('در صورت فعال بودن این گزینه کاربران می توانند تاریخ تحویل را انتخاب نمایند.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'on',
								'keywords' => array('فعال سازی انتخاب تاریخ تحویل', 'tickets system'),
							),
							array(
								'id'             => 'irantheme_wc_shopping_cart_delivery_map_checkbox',
								'label'            => __(
									'فعال سازی انتخاب محل تحویل',
									self::$static_textdomain
								),
								'description'    => __('در صورت فعال بودن این گزینه کاربران می توانند محل تحویل را روی نقشه انتخاب نمایند.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'on',
								'keywords' => array('فعال سازی انتخاب محل تحویل', 'tickets system'),
							),
						)
					),
					array(
						'title' => 'تیکت های پشتیبانی',
						'id' => 'irantheme_wc_tickets',
						'description'            => __('در این بخش شما می توانید تنظیمات مربوط به تیکت های پشتیبانی کاربران را مدیریت کنید.', self::$static_textdomain),
						'fields' => array(
							array(
								'id'             => 'irantheme_wc_tickets_checkbox',
								'label'            => __(
									'فعال سازی تیکت های پشتیبانی',
									self::$static_textdomain
								),
								'description'    => __('در صورت فعال بودن این گرینه تیکت های پشتیانی برای کاربران فعال خواهد شد.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'on',
								'keywords' => array('فعال سازی تیکت های پشتیبانی', 'tickets system'),
							),
						)
					),
					array(
						'title' => 'جعبه خرید چسبان',
						'id' => 'irantheme_wc_sticky_cart',
						'description'            => __('در این بخش می تواند تنظیمات مربوط به نوار شناور خرید در صفحه محصول را تنظیم کنید.', self::$static_textdomain),
						'fields' => array(
							array(
								'id'             => 'irantheme_wc_navbar_mobile_base_color_select',
								'label'            => __(
									'رنگ پایه',
									self::$static_textdomain
								),
								'description'    => __('برای انتخاب رنگ پایه از لیست رنگ دلخواه خود را انتخاب کنید.رنگ محتواو پس زمینه بصورت پیش فرض بر اساس این رنگ تنظیم خواهد شد.', self::$static_textdomain),
								'type'            => 'select',
								'options'        => array('white' => 'سفید', 'dark' => 'مشکی'),
								'default'        => 'white',
								'keywords' => array('رنگ پایه', 'navbar mobile base color'),
							),
							array(
								'id'             => 'irantheme_wc_navbar_mobile_full_width_checkbox',
								'label'            => __(
									'ناوبری تمام عرض',
									self::$static_textdomain
								),
								'description'    => __('با فعال کردن این گزینه نوار شناور خرید به صورت تمام عرض نمایش داده خواهد شد، در صورت عدم فعال بودن این گزینه نوار کمی به صورت خمیده و فاصله از صفحه به نمایش در خواهد آمد.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('ناوبری تمام عرض', 'full width navbar mobile'),
							),
							array(
								'id'             => 'irantheme_wc_navbar_mobile_glass_style_checkbox',
								'label'            => __(
									'استایل شیشه ای',
									self::$static_textdomain
								),
								'description'    => __('با فعال کردن این گزینه ظاهر نوار ناوبری ببصورت شیشه ای خواهد شد.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('استایل شیشه ای', 'navbar mobile glass style'),
							),
							array(
								'id'             => 'irantheme_wc_navbar_mobile_auto_hide_checkbox',
								'label'            => __(
									'مخفی شدن در هنگام اسکرول',
									self::$static_textdomain
								),
								'description'    => __('در صورتی که می خواهید نوار شناور خرید در هنگام اسکرول به پایین مخفی شود این گزینه را فعال نمایید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'off',
								'keywords' => array('مخفی شدن در هنگام اسکرول', 'auto hide navbar mobile'),
							),
						)

					),
				)
			);
			// OTP sms configuration fields data
			$settings['invoice_settings'] = array(
				'title'                    => __('چاپ فاکتور و برچسب', self::$static_textdomain),
				'icon' => 'fa-file-invoice-dollar',
				'submenu'                => array(
					array(
						'title' => 'تنظیمات عمومی',
						'id' => 'irantheme_wc_invoice_general',
						'description'            => __('در این بخش شما می توانید تنظیمات مربوط به صدور و چاپ فاکتور و برچسب را مدیریت کنید.', self::$static_textdomain),
						'fields' => array(
							array(
								'id'             => 'irantheme_wc_invoice_shop_logo',
								'label'            => __('لوگوی فروشگاه', self::$static_textdomain),
								'description'    => __('لطفا لوگو فروشگاه خود را بارگذاری نمایید.', self::$static_textdomain),
								'type'            => 'image',
								'default'        => '',
								'placeholder'    => '',
								'keywords' => array('لوگو فروشگاه', 'لوگو فاکتور', 'invoice logo', 'faktor logo'),
							),
							array(
								'id'             => 'irantheme_wc_invoice_shop_name',
								'label'            => __('نام فروشگاه', self::$static_textdomain),
								'description'    => __('نامی که میخواهید در بخش اطلاعات فروشنده در فاکتور و فرستنده در برچسب پستی نمایش داده شود.', self::$static_textdomain),
								'type'            => 'text',
								'default'        => 'عنوان تصادفی',
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('نام فرستنده', 'نام فروشنده', 'sender name'),
							),
							array(
								'id'             => 'irantheme_wc_invoice_shop_economy_code',
								'label'            => __('شماره اقتصادی', self::$static_textdomain),
								'description'    => __('شناسه 12،11،10 یا 14 رقمی سازمان امور مالیاتی برای کسب‌وکارها است که در فاکتورهای رسمی، معاملات دولتی و ... استفاده می شود.', self::$static_textdomain),
								'note'    => __('برای اطلاع از کد اقتصادی و نحوه دریافت آن، به سامانه مودیان مالیاتی مراجعه کنید:
https://tax.gov.ir'),
								'type'            => 'text',
								'default'        => '',
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('شماره اقتصادی'),
							),
							array(
								'id'             => 'irantheme_wc_invoice_shop_melli_code',
								'label'            => __('شناسه ملی', self::$static_textdomain),
								'description'    => __('شناسه ملی شرکت فروشگاه جهت نمایش در بخش اطلاعات فروشنده در فاکتور', self::$static_textdomain),
								'type'            => 'text',
								'default'        => '',
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('شناسه ملی'),
							),
							array(
								'id'             => 'irantheme_wc_invoice_shop_reg_code',
								'label'            => __('شماره ثبت', self::$static_textdomain),
								'description'    => __('شماره ثبت جهت نمایش در بخش اطلاعات فروشنده در فاکتور', self::$static_textdomain),
								'type'            => 'text',
								'default'        => '',
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('شناسه ثبت'),
							),
							array(
								'id'             => 'irantheme_wc_invoice_shop_state',
								'label'            => __('نشانی فروشگاه (استان)', self::$static_textdomain),
								'description'    => __('استان', self::$static_textdomain),
								'type'            => 'text',
								'default'        => 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ',
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('آدرس فرستنده', 'آدرس فروشنده', 'sender name'),
							),
							array(
								'id'             => 'irantheme_wc_invoice_shop_city',
								'label'            => __('نشانی فروشگاه (شهر)', self::$static_textdomain),
								'description'    => __('شهر', self::$static_textdomain),
								'type'            => 'text',
								'default'        => 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ',
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('آدرس فرستنده', 'آدرس فروشنده', 'sender name'),
							),
							array(
								'id'             => 'irantheme_wc_invoice_shop_address',
								'label'            => __('نشانی فروشگاه', self::$static_textdomain),
								'description'    => __('نشانی فروشگاه که میخواهید در بخش اطلاعات فروشنده در فاکتور و فرستنده در برچسب پستی نمایش داده شود.', self::$static_textdomain),
								'type'            => 'textarea',
								'default'        => 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ',
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('آدرس فرستنده', 'آدرس فروشنده', 'sender name'),
							),
							array(
								'id'             => 'irantheme_wc_invoice_shop_pcode',
								'label'            => __('کدپستی فروشگاه', self::$static_textdomain),
								'description'    => __('کدپستی که میخواهید در بخش اطلاعات فروشنده در فاکتور و فرستنده در برچسب پستی نمایش داده شود.', self::$static_textdomain),
								'type'            => 'text',
								'default'        => '1234567890',
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('کدپستی فرستنده', 'کدپستی فروشنده', 'sender postal code'),
							),
							array(
								'id'             => 'irantheme_wc_invoice_shop_phone_number',
								'label'            => __('تلفن فروشگاه', self::$static_textdomain),
								'description'    => __('تلفنی که میخواهید در بخش اطلاعات فروشنده در فاکتور و فرستنده در برچسب پستی نمایش داده شود.', self::$static_textdomain),
								'type'            => 'text',
								'default'        => '09123456789',
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('تلفن فرستنده', 'تلفن فروشنده', 'sender phone number'),
							),
							array(
								'id'             => 'irantheme_wc_invoice_shop_email',
								'label'            => __('ایمیل فروشگاه', self::$static_textdomain),
								'description'    => __('ایمیلی که میخواهید در بخش اطلاعات فروشنده در فاکتور و فرستنده در برچسب پستی نمایش داده شود.', self::$static_textdomain),
								'type'            => 'text',
								'default'        => 'example@gmail.com',
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('ایمیل فرستنده', 'ایمیل فروشنده', 'sender phone number'),
							),
							array(
								'id'             => 'irantheme_wc_invoice_shop_website_url',
								'label'            => __('وب سایت فروشگاه', self::$static_textdomain),
								'description'    => __('آدرس وب سایتی که میخواهید در بخش اطلاعات فروشنده در فاکتور و فرستنده در برچسب پستی نمایش داده شود.', self::$static_textdomain),
								'type'            => 'text',
								'default'        => 'example.com',
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('آدرس وب سایت فرستنده', 'آدرس وب سایت فروشنده', 'sender website url'),
							),
							array(
								'id'             => 'irantheme_wc_invoice_shop_signature',
								'label'            => __('تصویر امضا/مهر فروشگاه', self::$static_textdomain),
								'description'    => __('لطفا تصویر امضا/مهر فروشگاه خود را با بارگذاری نمایید.', self::$static_textdomain),
								'note'    => __('ترجیحا از تصویری با فرمت png و بدون پس زمینه استفاده نمایید.', self::$static_textdomain),
								'type'            => 'image',
								'default'        => '',
								'placeholder'    => '',
								'keywords' => array('تصویر امضا/مهر فروشگاه', 'invoice signature', 'faktor signature'),
							),
						)
					),
					array(
						'title' => 'فاکتور عادی (فروشگاهی)',
						'id' => 'irantheme_wc_invoice_customer',
						'description'            => __('در این بخش شما می توانید تنظیمات مربوط به صدور و چاپ فاکتور عادی (فروشگاهی) را مدیریت کنید.فاکتور عادی (غیررسمی) سندی ساده برای ثبت داخلی فروش یا خرید است، نیازی به ثبت در سامانه مالیاتی ندارد و فاقد کد یکتا و مالیات ارزش افزوده می‌باشد. فقط برای معاملات کوچک و حسابداری معمولی کاربرد دارد.', self::$static_textdomain),
						'fields' => array(
							array(
								'id'             => 'irantheme_wc_invoice_customer_account_btn_visibility_checkbox',
								'label'            => __(
									'نمایش دکمه فاکتور در حساب کاربری مشتری',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید وضعیت نمایش دکمه مشاهده فاکتور (عادی) در صفحه جزییات سفارش در حساب کاربری مشتری را مشخص کنید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'on',
								'keywords' => array('نمایش دکمه فاکتور در حساب کاربری مشتری', 'customer invoice button visibility'),
							),
							array(
								'id'             => 'irantheme_wc_invoice_customer_thankyou_btn_visibility_checkbox',
								'label'            => __(
									'نمایش دکمه فاکتور در صفحه موفقیت خرید',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید وضعیت نمایش دکمه مشاهده فاکتور (عادی) در صفحه موفقیت خرید (صفحه مربوط به نمایش پیام موفقیت پرداخت و ثبت سفارش) را مشخص کنید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'on',
								'keywords' => array('نمایش دکمه فاکتور در صفحه موفقیت خرید', 'customer invoice button visibility'),
							),
							array(
								'id'             => 'irantheme_wc_invoice_customer_theme_radio',
								'label'            => __(
									'قالب فاکتور',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید سبک قالب فاکتور عادی را از بین قالب های موجود انتخاب نمایید.', self::$static_textdomain),
								'note'    => __('قالب های جدید به تدریج در نسخه های آتی اضافه می گردد.', self::$static_textdomain),
								'type'            => 'radio',
								'options'        => array('theme-1' => 'قالب شماره 1'),
								'id_dependents_on' => array(),
								'id_dependents_off' => array(),
								'default'        => 'theme-1',
								'keywords' => array('invoice customer theme', 'قالب فاکتور عادی')
							),
							array(
								'id'             => 'irantheme_wc_invoice_customer_paper_size_orientation_select',
								'label'            => __(
									'اندازه و جهت کاغذ',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید اندازه و جهت کاغذ برای چاپ فاکتور را از بین گزینه های موجود مشخص نمایید.', self::$static_textdomain),
								'note'    => __('کاربر صرفا در اندازه و جهت انتخاب شده توسط شما می تواند اقدام به صدور و جاپ کند.', self::$static_textdomain),
								'type'            => 'select',
								'options'        => array('A4-portrait' => 'A4 - عمودی', 'A5-portrait' => 'A5 - عمودی', 'A5-landscape' => 'A5 - افقی'),
								'default'        => 'A4-portrait',
								'keywords' => array('اندازه و جهت کاغذ', 'invoice paper size and orientation'),
							),
							array(
								'id'             => 'irantheme_wc_invoice_customer_admin_descriptions',
								'label'            => __('توضیحات فروشنده مخصوص فاکتور عادی', self::$static_textdomain),
								'description'    => __('توضیحاتی که میخواهید در فاکتور عادی (غیر رسمی) نمایش داده شود.', self::$static_textdomain),
								'type'            => 'text_editor',
								'default'        => '',
								'media_status' => false,
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('توضیحات فاکتور', 'invoice admin descriptions'),
							),
							array(
								'id'             => 'irantheme_wc_invoice_customer_signature_visibility_checkbox',
								'label'            => __(
									'نمایش بخش مهر/امضا',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید وضعیت نمایش بخش مهر/امضا خریدار و فروشنده در پایین فاکتور عادی (غیر رسمی) را کنترل کنید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'on',
								'keywords' => array('نمایش بخش مهر/امضا', 'customer signature visibility'),
							),
						)
					),
					array(
						'title' => 'فاکتور رسمی',
						'id' => 'irantheme_wc_invoice_official',
						'description'            => __('در این بخش شما می توانید تنظیمات مربوط به صدور و چاپ فاکتور رسمی را مدیریت کنید. فاکتور رسمی مطابق با قوانین سازمان امور مالیاتی صادر می‌شود، حاوی شماره اقتصادی و شناسه یکتاست و از طریق سامانه مودیان ارسال می‌گردد. برای معاملات شرکتی، هزینه‌های قابل قبول مالیاتی، صادرات و واردات اجباری بوده و اعتبار حقوقی کامل دارد.', self::$static_textdomain),
						'fields' => array(
							array(
								'id'             => 'irantheme_wc_invoice_official_issue_mode_select',
								'label'            => __(
									'شیوه صدور فاکتور رسمی',
									self::$static_textdomain
								),
								'description'    => __('نحوه صدور و مدیریت فاکتورهای رسمی برای مشتریان را انتخاب کنید. هر حالت رفتار متفاوتی در ثبت درخواست، بررسی و صدور فاکتور رسمی خواهد داشت.', self::$static_textdomain),
								'note'    => __('</br>• غیرفعال: امکان ثبت درخواست و دریافت فاکتور رسمی برای مشتریان غیرفعال است و تنها مدیر فروشگاه می‌تواند از طریق پنل مدیریت، فاکتور رسمی را مشاهده یا چاپ کند.</br>• صدور با تأیید مدیر: مشتری درخواست فاکتور رسمی ثبت می‌کند و درخواست در انتظار بررسی مدیر قرار می‌گیرد. پس از تأیید مدیر، فاکتور رسمی صادر و برای مشتری قابل دریافت خواهد بود.</br>• صدور نیمه‌ خودکار: مشتری درخواست فاکتور رسمی ثبت می‌کند. اگر اطلاعات حقوقی وی کامل باشد، پس از رسیدن سفارش به وضعیت «در حال انجام» یا «تکمیل شده»، فاکتور رسمی به‌صورت خودکار صادر خواهد شد. در صورت ناقص بودن اطلاعات حقوقی، درخواست برای بررسی و تصمیم‌گیری مدیر ارسال می‌شود.</br>• صدور خودکار: در این حالت مشتری نیازی به ثبت درخواست ندارد. برای سفارش‌هایی که اطلاعات حقوقی کامل داشته باشند، پس از رسیدن سفارش به وضعیت «در حال انجام» یا «تکمیل شده»، فاکتور رسمی به‌صورت خودکار صادر می‌شود. سفارش‌هایی که اطلاعات حقوقی ناقص داشته باشند، مشمول صدور خودکار نخواهند بود و پس از تکمیل اطلاعات حقوقی باید بصورت دستی درخواست صدور ثبت کنند.', self::$static_textdomain),
								'type'            => 'select',
								'options'        => array('disabled' => 'غیرفعال', 'manual' => 'صدور با تأیید مدیر', 'semi_auto' => 'صدور نیمه‌ خودکار', 'full_auto' => 'صدور خودکار'),
								'default'        => 'manual',
								'keywords' => array('شیوه صدور فاکتور رسمی', 'official invocie issue mode'),
							),
							array(
								'id'             => 'irantheme_wc_invoice_official_theme_radio',
								'label'            => __(
									'قالب فاکتور',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید سبک قالب فاکتور رسمی را از بین قالب های موجود انتخاب نمایید.', self::$static_textdomain),
								'note'    => __('قالب های جدید به تدریج در نسخه های آتی اضافه می گردد.', self::$static_textdomain),
								'type'            => 'radio',
								'options'        => array('theme-1' => 'قالب شماره 1'),
								'id_dependents_on' => array(),
								'id_dependents_off' => array(),
								'default'        => 'theme-1',
								'keywords' => array('invoice official theme', 'قالب فاکتور رسمی')
							),
							array(
								'id'             => 'irantheme_wc_invoice_official_paper_size_orientation_select',
								'label'            => __(
									'اندازه و جهت کاغذ',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید اندازه و جهت کاغذ برای چاپ فاکتور را از بین گزینه های موجود مشخص نمایید.', self::$static_textdomain),
								'note'    => __('کاربر صرفا در اندازه و جهت انتخاب شده توسط شما می تواند اقدام به صدور و جاپ کند.', self::$static_textdomain),
								'type'            => 'select',
								'options'        => array('A4-landscape' => 'A4 - افقی'),
								'default'        => 'A4-landscape',
								'keywords' => array('اندازه و جهت کاغذ', 'invoice paper size and orientation'),
							),
							array(
								'id'             => 'irantheme_wc_invoice_official_admin_descriptions',
								'label'            => __('توضیحات فروشنده مخصوص فاکتور رسمی', self::$static_textdomain),
								'description'    => __('توضیحاتی که میخواهید در فاکتور رسمی نمایش داده شود.', self::$static_textdomain),
								'type'            => 'text_editor',
								'default'        => '',
								'media_status' => false,
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('توضیحات فاکتور', 'invoice admin descriptions'),
							),
							array(
								'id'             => 'irantheme_wc_invoice_official_serial_prefix',
								'label'            => __('پیشوند شماره فاکتور رسمی', self::$static_textdomain),
								'description'    => __('پیشوندی که قبل از شماره سریال فاکتور قرار می‌گیرد. شما می‌توانید از تگ {year} برای درج خودکار سال خورشیدی جاری استفاده کنید. (مثال: INV-1405-)', self::$static_textdomain),
								'type'            => 'text',
								'default'        => 'INV-{year}-',
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('پیشوند شماره فاکتور رسمی', 'official invoice serial number prefix'),
							),
							array(
								'id'             => 'irantheme_wc_invoice_official_serial_next_number',
								'label'            => __(
									'شماره فاکتور بعدی (شروع شمارش)',
									self::$static_textdomain
								),
								'description'    => __('برای شروع کار، شماره اولین فاکتور رسمی که می‌ خواهید صادر شود را اینجا بنویسید (مثلاً 10 یا 1001). با تایید هر سفارش، این عدد به صورت خودکار یک واحد بالا می‌رود.', self::$static_textdomain),
								'note'    => __('اگر فاکتوری را در خارج از سایت (مثلاً حضوری) صادر کردید، می‌توانید این عدد را دستی جلو ببرید تا توالی فاکتورهای سایت با دفاتر مالی شما هماهنگ بماند.</br>هشدار: دستکاری دستی این مقدار باعث به‌هم‌خوردن توالی شماره فاکتورها و تکرار احتمالی شماره می‌شود.</strong>
    تنها در مواردی که از عواقب آن آگاهید و نیاز به هماهنگی با دفتر مالی دارید، این مقدار را تغییر دهید.
    در صورت بروز اشتباه، برای بازگشت به توالی صحیح باید یکی یکی شماره‌های اضافه را به صورت دستی ابطال کنید.', self::$static_textdomain),
								'type'            => 'number',
								'default'        => 1001,
								'min' => 1,
								'step' => 1,
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('شماره فاکتور بعدی (شروع شمارش)', 'official invoice serial number next')
							),
							array(
								'id'             => 'irantheme_wc_invoice_official_serial_padding',
								'label'            => __(
									'تعداد ارقام فاکتور',
									self::$static_textdomain
								),
								'description'    => __('دوست دارید شماره فاکتورها چند رقمی دیده شوند؟ اگر این فیلد را روی عدد 4 بگذارید، شماره فاکتور 5 به شکل `0005` چاپ می‌شود. اگر می‌خواهید عدد به صورت ساده و بدون صفرهای پشت سر هم باشد، این فیلد را روی 1 تنظیم کنید.', self::$static_textdomain),
								'type'            => 'number',
								'default'        => 4,
								'min' => 1,
								'step' => 1,
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('تعداد ارقام فاکتور', 'official invoice serial number padding')
							),
							array(
								'id'             => 'irantheme_wc_invoice_official_serial_auto_reset_checkbox',
								'label'            => __(
									'شروع مجدد شمارش در سال جدید',
									self::$static_textdomain
								),
								'description'    => __('با فعال کردن این گزینه، به محض تحویل سال خورشیدی جدید (مثلاً ورود به سال 1406)، شمارش فاکتورها به صورت خودکار ریست شده و دوباره از عدد 1 (یا شماره شروع شما) آغاز می‌شود.', self::$static_textdomain),
								'note'    => __('این گزینه را تنها زمانی روشن کنید که از تگ `{year}` در فیلد «پیشوند» استفاده کرده باشید؛ در غیر این صورت شماره فاکتورهای سال جدید با سال قبل تکرار و تداخل خواهند داشت.', self::$static_textdomain),
								'type'            => 'checkbox',
								'id_dependents_on' => array('irantheme_wc_invoice_official_serial_reset_start_number'),
								'base_priority' => 'checkbox_base',
								'selector_priority' => 'checkbox_official_invoice_reset_start_number',
								'z_index' => 0,
								'default'        => 'off',
								'keywords' => array('شروع مجدد شمارش در سال جدید', 'official invoice serial number auto reset'),
							),
							array(
								'id'             => 'irantheme_wc_invoice_official_serial_reset_start_number',
								'label'            => __(
									'شماره شروع فاکتور در سال جدید',
									self::$static_textdomain
								),
								'description'    => __('مشخص کنید با شروع سال جدید، شماره فاکتور ها از چه عددی شروع شوند.', self::$static_textdomain),
								'type'            => 'number',
								'default'        => 1001,
								'min' => 1,
								'step' => 1,
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('شماره شروع فاکتور در سال جدید', 'official invoice serial reset start number')
							),
						)
					),
					array(
						'title' => 'فاکتور حرارتی',
						'id' => 'irantheme_wc_invoice_thermal',
						'description'            => __('در این بخش شما می توانید تنظیمات مربوط به صدور و چاپ فاکتور حرارتی (POS) را مدیریت کنید.', self::$static_textdomain),
						'fields' => array(
							array(
								'id'             => 'irantheme_wc_invoice_thermal_theme_radio',
								'label'            => __(
									'قالب فاکتور',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید سبک قالب فاکتور حرارتی را از بین قالب های موجود انتخاب نمایید.', self::$static_textdomain),
								'note'    => __('قالب های جدید به تدریج در نسخه های آتی اضافه می گردد.', self::$static_textdomain),
								'type'            => 'radio',
								'options'        => array('theme-1' => 'قالب شماره 1'),
								'id_dependents_on' => array(),
								'id_dependents_off' => array(),
								'default'        => 'theme-1',
								'keywords' => array('invoice thermal theme', 'قالب فاکتور حرارتی')
							),
							array(
								'id'             => 'irantheme_wc_invoice_thermal_paper_size_orientation_select',
								'label'            => __(
									'اندازه کاغذ',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید اندازه و جهت کاغذ برای چاپ فاکتور را از بین گزینه های موجود مشخص نمایید.', self::$static_textdomain),
								'note'    => __('به دلیل ماهیت فیش های حرارتی طول آن ها بر اساس محتوا تنظیم و جهت آنها عمودی میباشد.کاربر صرفا در اندازه انتخاب شده توسط شما می تواند اقدام به صدور و جاپ کند.', self::$static_textdomain),
								'type'            => 'select',
								'options'        => array('80mm-portrait' => 'عرض: 80 میلی متر (8 سانتی متر)'),
								'default'        => '80mm-portrait',
								'keywords' => array('اندازه و جهت کاغذ', 'invoice paper size and orientation'),
							),
							array(
								'id'             => 'irantheme_wc_invoice_thermal_admin_descriptions',
								'label'            => __('توضیحات فروشنده مخصوص فاکتور حرارتی', self::$static_textdomain),
								'description'    => __('توضیحاتی که میخواهید در فاکتور حرارتی نمایش داده شود.', self::$static_textdomain),
								'type'            => 'text_editor',
								'default'        => '',
								'media_status' => false,
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('توضیحات فاکتور', 'invoice admin descriptions'),
							),
						)
					),
					array(
						'title' => 'برچسب پستی',
						'id' => 'irantheme_wc_shipping_label',
						'description'            => __('در این بخش شما می توانید تنظیمات مربوط به صدور و چاپ برچسب پستی را مدیریت کنید. این بخش مختص چاپ با پرینتر های عادی جوهرافشان ، لیزری و... هست.', self::$static_textdomain),
						'fields' => array(
							array(
								'id'             => 'irantheme_wc_shipping_label_theme_radio',
								'label'            => __(
									'قالب برچسب',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید سبک قالب برچسب پستی را از بین قالب های موجود انتخاب نمایید.', self::$static_textdomain),
								'note'    => __('قالب های جدید به تدریج در نسخه های آتی اضافه می گردد.', self::$static_textdomain),
								'type'            => 'radio',
								'options'        => array('theme-1' => 'قالب شماره 1'),
								'id_dependents_on' => array(),
								'id_dependents_off' => array(),
								'default'        => 'theme-1',
								'keywords' => array('shipping label theme', 'قالب برچسب پستی')
							),
							array(
								'id'             => 'irantheme_wc_shipping_label_paper_size_orientation_select',
								'label'            => __(
									'اندازه و جهت کاغذ',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید اندازه و جهت کاغذ برای چاپ برچسب را از بین گزینه های موجود مشخص نمایید.', self::$static_textdomain),
								'note'    => __('کاربر صرفا در اندازه و جهت انتخاب شده توسط شما می تواند اقدام به صدور و جاپ کند.', self::$static_textdomain),
								'type'            => 'select',
								'options'        => array('A5-landscape' => 'A5 - افقی', 'A5-portrait' => 'A5 - عمودی', 'A6-landscape' => 'A6 - افقی', 'A6-portrait' => 'A6 - عمودی'),
								'default'        => 'A6-landscape',
								'keywords' => array('اندازه و جهت کاغذ', 'shipping label paper size and orientation'),
							),
						)
					),
					array(
						'title' => 'برچسب پستی حرارتی',
						'id' => 'irantheme_wc_shipping_thermal_label',
						'description'            => __('در این بخش شما می توانید تنظیمات مربوط به صدور و چاپ برچسب پستی را مدیریت کنید. این بخش مختص چاپ با پرینتر های حرارتی هست.', self::$static_textdomain),
						'fields' => array(
							array(
								'id'             => 'irantheme_wc_shipping_thermal_label_theme_radio',
								'label'            => __(
									'قالب برچسب',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید سبک قالب برچسب پستی را از بین قالب های موجود انتخاب نمایید.', self::$static_textdomain),
								'note'    => __('قالب های جدید به تدریج در نسخه های آتی اضافه می گردد.', self::$static_textdomain),
								'type'            => 'radio',
								'options'        => array('theme-1' => 'قالب شماره 1'),
								'id_dependents_on' => array(),
								'id_dependents_off' => array(),
								'default'        => 'theme-1',
								'keywords' => array('shipping thermal label theme', 'قالب برچسب پستی')
							),
							array(
								'id'             => 'irantheme_wc_shipping_thermal_label_paper_size_orientation_select',
								'label'            => __(
									'اندازه و جهت کاغذ',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه می توانید اندازه و جهت کاغذ برای چاپ برچسب را از بین گزینه های موجود مشخص نمایید.', self::$static_textdomain),
								'note'    => __('کاربر صرفا در اندازه و جهت انتخاب شده توسط شما می تواند اقدام به صدور و جاپ کند.', self::$static_textdomain),
								'type'            => 'select',
								'options'        => array('100x150-portrait' => 'لیبل پستی 100x150 (10x15 سانتی متر) - عمودی', '150x100-landscape' => 'لیبل پستی 150x100 (15x10 سانتی متر) - افقی', '100x100-square' => 'لیبل پستی 100x100 (10x10 سانتی متر) - مربعی', '50x80-portrait' => 'لیبل پستی 50x80 (5x8 سانتی متر) - عمودی', '80x50-landscape' => 'لیبل پستی 80x50 (8x5 سانتی متر) - افقی'),
								'default'        => '150x100-landscape',
								'keywords' => array('اندازه و جهت کاغذ', 'shipping thermal label paper size and orientation'),
							),
						)
					),
				)
			);
			// OTP sms configuration fields data
			$settings['sms_settings'] = array(
				'title'                    => __('ورود پیامکی', self::$static_textdomain),
				'icon' => 'fa-message-sms',
				'submenu'                => array(
					array(
						'title' => 'تنظیمات درگاه پیامکی',
						'id' => 'irantheme_sms_panel_settings',
						'description'            => __('در این بخش شما می توانید تنظیمات مربوط به ورود با رمز یکبارمصرف به حساب کاربری ووکامرس را انجام دهید.توجه داشته باشید که این قابلیت فقط زمانی که ووکامرس فعال باشد قابلیت استفاده دارد.', self::$static_textdomain),
						'icon' => 'fa-arrows-left-right-to-line',
						'fields' => array(
							array(
								'id'             => 'irantheme_sms_panel_provider',
								'label'            => __(
									'درگاه پیامکی',
									self::$static_textdomain
								),
								'description'    => __('با استفاده از این گزینه شما می توانید یکی از سامانه های پیامکی پشتیانی شده را برای ارسال کد تایید یکبار مصرف انتخاب کنید.در صورت عدم نیاز به این قابلیت گزینه "غیرفعال" را انتخاب نمایید.', self::$static_textdomain),
								'note'    => __('جهت ارسال پیامک توسط سامانه انتخاب شده در بالا، ابتدا باید از سامانه مورد نظر پنل پیامکی دارای اعتبار کافی خریداری کرده و سپس اطلاعات زیر را تکمیل نمایید.', self::$static_textdomain),
								'type'            => 'radio',
								'thumbnail_status' => true,
								'options'        => array(
									'off' => array('غیرفعال', 'off.png'),
									'kavenegar' => array('کاوه نگار', 'kavenegar.png'),
									'melipayamak' => array('ملی پیامک', 'melipayamak.png'),
									'farazsms' => array('فراز اس ام اس', 'farazsms.png'),
									'smsir' => array('sms.ir', 'smsir.png')
								),
								'id_dependents_on' => array(
									'kavenegar' => array('irantheme_sms_panel_kavenegar_pattern', 'irantheme_sms_panel_api_key', 'irantheme_sms_panel_kavenegar_web_service_status', 'irantheme_sms_test_phone', 'irantheme_sms_test_submit', 'irantheme_sms_panel_log'),
									'melipayamak' => array('irantheme_sms_panel_melipayamak_username', 'irantheme_sms_panel_melipayamak_password', 'irantheme_sms_panel_melipayamak_web_service_status', 'irantheme_sms_test_phone', 'irantheme_sms_test_submit', 'irantheme_sms_panel_log'),
									'farazsms' => array('irantheme_sms_panel_api_key', 'irantheme_sms_panel_farazsms_sender', 'irantheme_sms_panel_farazsms_web_service_status', 'irantheme_sms_test_phone', 'irantheme_sms_test_submit', 'irantheme_sms_panel_log'),
									'smsir' => array('irantheme_sms_panel_smsir_pattern', 'irantheme_sms_panel_api_key', 'irantheme_sms_panel_smsir_web_service_status', 'irantheme_sms_test_phone', 'irantheme_sms_test_submit', 'irantheme_sms_panel_log'),
								),
								'id_dependents_off' => array(
									'off' => array('irantheme_sms_panel_kavenegar_pattern', 'irantheme_sms_panel_api_key', 'irantheme_sms_panel_kavenegar_web_service_status,irantheme_sms_panel_melipayamak_username', 'irantheme_sms_panel_melipayamak_password', 'irantheme_sms_panel_melipayamak_web_service_status', 'irantheme_sms_panel_farazsms_web_service_status', 'irantheme_sms_panel_smsir_web_service_status', 'irantheme_sms_panel_melipayamak_sender', 'irantheme_sms_panel_farazsms_sender', 'irantheme_sms_panel_smsir_sender', 'irantheme_sms_panel_melipayamak_pattern', 'irantheme_sms_panel_farazsms_pattern', 'irantheme_sms_panel_smsir_pattern', 'irantheme_sms_panel_farazsms_variable_name', 'irantheme_sms_panel_smsir_variable_name', 'irantheme_sms_test_phone', 'irantheme_sms_test_submit', 'irantheme_sms_panel_log'),
									'kavenegar' => array('irantheme_sms_panel_melipayamak_username', 'irantheme_sms_panel_melipayamak_password', 'irantheme_sms_panel_melipayamak_web_service_status', 'irantheme_sms_panel_farazsms_web_service_status', 'irantheme_sms_panel_smsir_web_service_status', 'irantheme_sms_panel_melipayamak_sender', 'irantheme_sms_panel_farazsms_sender', 'irantheme_sms_panel_smsir_sender', 'irantheme_sms_panel_melipayamak_pattern', 'irantheme_sms_panel_farazsms_pattern', 'irantheme_sms_panel_smsir_pattern', 'irantheme_sms_panel_farazsms_variable_name', 'irantheme_sms_panel_smsir_variable_name'),
									'melipayamak' => array('irantheme_sms_panel_api_key', 'irantheme_sms_panel_kavenegar_web_service_status', 'irantheme_sms_panel_farazsms_web_service_status', 'irantheme_sms_panel_smsir_web_service_status', 'irantheme_sms_panel_kavenegar_sender', 'irantheme_sms_panel_farazsms_sender', 'irantheme_sms_panel_smsir_sender', 'irantheme_sms_panel_kavenegar_pattern', 'irantheme_sms_panel_smsir_pattern', 'irantheme_sms_panel_farazsms_pattern', 'irantheme_sms_panel_farazsms_variable_name', 'irantheme_sms_panel_smsir_variable_name'),
									'farazsms' => array('irantheme_sms_panel_melipayamak_username', 'irantheme_sms_panel_melipayamak_password', 'irantheme_sms_panel_kavenegar_web_service_status', 'irantheme_sms_panel_melipayamak_web_service_status', 'irantheme_sms_panel_smsir_web_service_status', 'irantheme_sms_panel_kavenegar_sender', 'irantheme_sms_panel_melipayamak_sender', 'irantheme_sms_panel_smsir_sender', 'irantheme_sms_panel_kavenegar_pattern', 'irantheme_sms_panel_melipayamak_pattern', 'irantheme_sms_panel_smsir_pattern', 'irantheme_sms_panel_smsir_variable_name'),
									'smsir' => array('irantheme_sms_panel_melipayamak_username', 'irantheme_sms_panel_melipayamak_password', 'irantheme_sms_panel_kavenegar_web_service_status', 'irantheme_sms_panel_melipayamak_web_service_status', 'irantheme_sms_panel_farazsms_web_service_status', 'irantheme_sms_panel_kavenegar_sender', 'irantheme_sms_panel_melipayamak_sender', 'irantheme_sms_panel_farazsms_sender', 'irantheme_sms_panel_kavenegar_pattern', 'irantheme_sms_panel_melipayamak_pattern', 'irantheme_sms_panel_farazsms_pattern', 'irantheme_sms_panel_farazsms_variable_name'),
								),
								'default'        => 'off',
								'custom_class' => 'irantheme-parent-tr-border-bottom-none',
								'keywords' => array('درگاه پیامکی', 'sms panel provider')
							),
							array(
								'id'             => 'irantheme_sms_panel_api_key',
								'label'            => __(
									'کلید وب سرویس (API KEY)',
									self::$static_textdomain
								),
								'description'    => __('این کلید امنیتی، شناسه کاربری شما برای اتصال به سامانه پیامکی است. آن را از پنل کاربری خود در ارائه‌دهنده پیامک (مثلاً کاوه نگار، فراز اس‌ام‌اس، SMS.ir) دریافت کرده و در اینجا وارد کنید. این کلید را محرمانه نگه دارید', self::$static_textdomain),
								'type'            => 'text',
								'custom_class' => 'irantheme-parent-tr-border-bottom-none',
								'default'        => '',
								'placeholder'    => __('your_api_key_xxxxxxxxxxxxxxxx', self::$static_textdomain),
								'keywords' => array('کلید وب سرویس', 'api key'),
							),
							array(
								'id'             => 'irantheme_sms_panel_melipayamak_username',
								'label'            => __(
									'نام کاربری',
									self::$static_textdomain
								),
								'description'    => __('نام کاربری (Username) حساب کاربری خود در پنل ملی پیامک را در این فیلد وارد کنید. این نام کاربری برای احراز هویت و اتصال به API ملی پیامک استفاده می‌شود.', self::$static_textdomain),
								'type'            => 'text',
								'custom_class' => 'irantheme-parent-tr-border-bottom-none',
								'default'        => '',
								'placeholder'    => __('yourusername', self::$static_textdomain),
								'keywords' => array('نام کاربری ملی پیامک', 'melipayamak username'),
							),
							array(
								'id'             => 'irantheme_sms_panel_melipayamak_password',
								'label'            => __(
									'رمزعبور',
									self::$static_textdomain
								),
								'description'    => __('رمز عبور (Password) حساب کاربری خود در پنل ملی پیامک را وارد کنید.', self::$static_textdomain),
								'note'    => __('ملی پیامک این امکان را فراهم کرده که برای اتصال API، به جای رمز عبور اصلی حساب کاربری، از یک API Key مخصوص (که از پنل خود دریافت می‌کنید) استفاده شود. این کار امنیت را افزایش می‌دهد؛ در صورت وجود چنین API Key، توصیه می‌شود آن را به جای رمز عبور اصلی در اینجا وارد کنید.', self::$static_textdomain),
								'type'            => 'text',
								'custom_class' => 'irantheme-parent-tr-border-bottom-none',
								'default'        => '',
								'placeholder'    => __('yourpassword یا your_api_key', self::$static_textdomain),
								'keywords' => array('رمز عبور ملی پیامک', 'melipayamak password'),
							),
							array(
								'id'             => 'irantheme_sms_panel_kavenegar_web_service_status',
								'label'            => __(
									'ارسال توسط سرویس ارسال کد اعتبارسنجی',
									self::$static_textdomain
								),
								'description'    => __('فعال کردن این گزینه باعث می‌شود کد تایید از طریق سیستم‌های اختصاصی ارسال کد اعتبارسنجی پنل پیامکی شما ارسال شود.', self::$static_textdomain),
								'note'    => __('استفاده از این سرویس‌ها (الگو/پترن) برای کدهای تایید ضروری است، زیرا پیامک‌های عادی ممکن است به دلیل مسدودسازی دریافت پیامک‌های تبلیغاتی (لیست سیاه مخابرات) به دست کاربر نرسند، در حالی که این سرویس‌ها از این محدودیت عبور می‌کنند.برای استفاده از این قابلیت، باید الگوی پیامک (Pattern) خود را در پنل کاوه نگار ثبت و منتظر تایید آن باشید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'on',
								'id_dependents_on' => array('irantheme_sms_panel_kavenegar_pattern'),
								'id_dependents_off' => array('irantheme_sms_panel_kavenegar_sender', 'irantheme_sms_panel_custom_message'),
								'base_priority' => 'checkbox_base',
								'selector_priority' => 'checkbox_sms_kavenegar_web_service_status',
								'z_index' => 0,
								'keywords' => array('ارسال توسط سرویس ارسال کد اعتبارسنجی', 'otp login', 'ورود پیامکی'),
								'custom_class' => 'irantheme-parent-tr-border-bottom-none',
							),
							array(
								'id'             => 'irantheme_sms_panel_melipayamak_web_service_status',
								'label'            => __(
									'ارسال توسط وبسرویس خدماتی',
									self::$static_textdomain
								),
								'description'    => __('این گزینه پیامک کد تایید را از طریق سرویس‌ های خدماتی ملی پیامک ارسال می‌کند که برای شماره‌های در لیست سیاه مخابرات نیز قابل دریافت است.', self::$static_textdomain),
								'note'    => __(' این روش برای رسیدن پیامک به همه کاربران (حتی مسدودکنندگان پیامک‌های تبلیغاتی) ضروری است و سرعت ارسال بالایی دارد.برای استفاده از این قابلیت، باید الگوی پیامک خود را در پنل ملی پیامک ساخته و منتظر تایید آن باشید.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'on',
								'id_dependents_on' => array('irantheme_sms_panel_melipayamak_pattern'),
								'id_dependents_off' => array('irantheme_sms_panel_melipayamak_sender', 'irantheme_sms_panel_custom_message'),
								'base_priority' => 'checkbox_base',
								'selector_priority' => 'checkbox_sms_melipayamak_web_service_status',
								'z_index' => 0,
								'keywords' => array('فعالسازی ورود با رمز یکبارمصرف'),
								'custom_class' => 'irantheme-parent-tr-border-bottom-none',
							),
							array(
								'id'             => 'irantheme_sms_panel_farazsms_web_service_status',
								'label'            => __(
									'ارسال بر اساس پترن',
									self::$static_textdomain
								),
								'description'    => __('این گزینه پیامک کد تایید را از طریق سرویس ارسال الگو (Pattern Send) فراز اس‌ام‌اس ارسال می‌کند. این روش بهترین راه برای اطمینان از رسیدن پیامک‌های کدهای تایید است.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'on',
								'id_dependents_on' => array('irantheme_sms_panel_farazsms_pattern', 'irantheme_sms_panel_farazsms_variable_name'),
								'id_dependents_off' => array('irantheme_sms_panel_custom_message'),
								'base_priority' => 'checkbox_base',
								'selector_priority' => 'checkbox_sms_farazsms_web_service_status',
								'z_index' => 0,
								'keywords' => array('ارسال بر اساس پترن'),
								'custom_class' => 'irantheme-parent-tr-border-bottom-none',
							),
							array(
								'id'             => 'irantheme_sms_panel_smsir_web_service_status',
								'label'            => __(
									'ارسال verify (وبسرویس خدماتی)',
									self::$static_textdomain
								),
								'description'    => __(' این گزینه ارسال پیامک کد تایید را از طریق وب‌سرویس ارسال Verify (الگو) اس‌ام‌اس.آی‌آر فعال می‌کند. این روش بهترین راه برای اطمینان از رسیدن پیامک‌های کدهای تایید است.', self::$static_textdomain),
								'note'    => __('برای فعال‌سازی این قابلیت، باید الگوی پیامک خود را در پنل SMS.ir (مثلاً کد شما: %CODE%) ثبت و تایید کنید. پیامک‌هایی که با این سرویس ارسال می‌شوند، حتی برای شماره‌هایی که دریافت پیامک‌های تبلیغاتی را مسدود کرده‌اند (لیست سیاه مخابرات) نیز ارسال می‌گردند.', self::$static_textdomain),
								'type'            => 'checkbox',
								'default'        => 'on',
								'id_dependents_on' => array('irantheme_sms_panel_smsir_pattern', 'irantheme_sms_panel_smsir_variable_name'),
								'id_dependents_off' => array('irantheme_sms_panel_smsir_sender', 'irantheme_sms_panel_custom_message'),
								'base_priority' => 'checkbox_base',
								'selector_priority' => 'checkbox_sms_smsir_web_service_status',
								'z_index' => 0,
								'keywords' => array('ارسال verify (وبسرویس خدماتی)'),
								'custom_class' => 'irantheme-parent-tr-border-bottom-none',
							),
							array(
								'id'             => 'irantheme_sms_panel_kavenegar_sender',
								'label'            => __(
									'شماره فرستنده',
									self::$static_textdomain
								),
								'description'    => __('شماره خط اختصاصی (Sender ID) خود را که برای ارسال پیامک از طریق کاوه نگار استفاده می‌کنید، در اینجا وارد کنید.', self::$static_textdomain),
								'type'            => 'text',
								'custom_class' => 'irantheme-parent-tr-border-bottom-none',
								'default'        => '',
								'placeholder'    => __('مثال: 1000xxxx یا مثال: 2000xxxx', self::$static_textdomain),
								'keywords' => array('شماره فرستنده کاوه نگار', 'kavenegar sender number'),
							),
							array(
								'id'             => 'irantheme_sms_panel_melipayamak_sender',
								'label'            => __(
									'شماره فرستنده',
									self::$static_textdomain
								),
								'description'    => __(' شماره خط اختصاصی خود را که برای ارسال پیامک از طریق ملی پیامک استفاده می‌کنید، در اینجا وارد کنید.', self::$static_textdomain),
								'type'            => 'text',
								'custom_class' => 'irantheme-parent-tr-border-bottom-none',
								'default'        => '',
								'placeholder'    => __('مثال: 3000xxxx یا مثال: 5000xxxx', self::$static_textdomain),
								'keywords' => array('شماره فرستنده ملی پیامک', 'melipayamak sender number'),
							),
							array(
								'id'             => 'irantheme_sms_panel_farazsms_sender',
								'label'            => __(
									'شماره فرستنده',
									self::$static_textdomain
								),
								'description'    => __('شماره خط اختصاصی خود را که برای ارسال پیامک از طریق فراز اس‌ام‌اس استفاده می‌کنید، در اینجا وارد کنید.', self::$static_textdomain),
								'type'            => 'text',
								'custom_class' => 'irantheme-parent-tr-border-bottom-none',
								'default'        => '',
								'placeholder'    => __('مثال: 2000xxxx یا مثال: +983000xxxx', self::$static_textdomain),
								'keywords' => array('شماره فرستنده فراز اس ام اس', 'farazsms sender number'),
							),
							array(
								'id'             => 'irantheme_sms_panel_smsir_sender',
								'label'            => __(
									'شماره فرستنده',
									self::$static_textdomain
								),
								'description'    => __('شماره خط اختصاصی خود را که برای ارسال پیامک از طریق SMS.ir استفاده می‌کنید، در اینجا وارد کنید.', self::$static_textdomain),
								'type'            => 'text',
								'custom_class' => 'irantheme-parent-tr-border-bottom-none',
								'default'        => '',
								'placeholder'    => __('مثال: 1000xxxx یا مثال: 3000xxxx', self::$static_textdomain),
								'keywords' => array('شماره فرستنده اس ام اس دات ای آر', 'sms.ir sender number'),
							),
							array(
								'id'             => 'irantheme_sms_panel_custom_message',
								'label'            => __(
									'متن ارسالی',
									self::$static_textdomain
								),
								'description'    => __('شما با استفاده از وردی بالا می توانید قالب پیام ارسالی به شماره موبایل کاربر هنگام ورود به سایت را مشخص کنید.', self::$static_textdomain),
								'note'    => __('در هرکجای پیام خود که نیاز به قرار دادن کد تایید دارید از عبارت {OTP} استفاده نمایید.شما مجاز به یکبار استفاده ار این عبارت در متن خود هستید.', self::$static_textdomain),
								'type'            => 'textarea',
								'default'        => 'کد تایید شما {OTP} می باشد.',
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('متن پیام کد تایید', 'OTP login message'),
							),
							array(
								'id'             => 'irantheme_sms_panel_kavenegar_pattern',
								'label'            => __(
									'نام الگو (Template)',
									self::$static_textdomain
								),
								'description'    => __('نام یا کد الگوی (Template) پیامک خود را که برای ارسال کد تایید در پنل کاوه نگار ساخته و تایید کرده‌اید، در اینجا وارد کنید.', self::$static_textdomain),
								'note'    => __('این الگو باید شامل یک متغیر برای کد تایید با نام %token% باشد.', self::$static_textdomain),
								'type'            => 'text',
								'default'        => '',
								'placeholder'    => __('my_otp_pattern', self::$static_textdomain),
								'keywords' => array('نام الگو کاوه نگار', 'kavenegar template'),
							),
							array(
								'id'             => 'irantheme_sms_panel_melipayamak_pattern',
								'label'            => __(
									'کد متن',
									self::$static_textdomain
								),
								'description'    => __('در اینجا کد متن الگوی ساخته شده در پنل کاربری سامانه ملی پیامک خود را وارد نمایید.', self::$static_textdomain),
								'note'    => __('الگوی شما فقط باید دارای یک متغیر با نام {0} باشد.', self::$static_textdomain),
								'type'            => 'text',
								'default'        => '',
								'placeholder'    => __('123456', self::$static_textdomain),
								'keywords' => array('کد متن ملی پیامک', 'melipayamak pattern code'),
							),
							array(
								'id'             => 'irantheme_sms_panel_smsir_pattern',
								'label'            => __(
									'شناسه قالب',
									self::$static_textdomain
								),
								'description'    => __('شناسه (Template ID) الگوی پیامک خود را که برای ارسال کد تایید در پنل SMS.ir ساخته و تایید کرده‌اید، در اینجا وارد کنید.', self::$static_textdomain),
								'note'    => __('قالب شما صرفا باید دارای یک متغیر باشد.', self::$static_textdomain),
								'type'            => 'text',
								'default'        => '',
								'placeholder'    => __('123456', self::$static_textdomain),
								'keywords' => array('شناسه قالب اس ام اس دات آی آر', 'sms.ir pattern id'),
								'custom_class' => 'irantheme-parent-tr-border-bottom-none'
							),
							array(
								'id'             => 'irantheme_sms_panel_farazsms_pattern',
								'label'            => __(
									'کد پترن',
									self::$static_textdomain
								),
								'description'    => __('کد پترن (Pattern Code) پیامک خود را که برای ارسال کد تایید در پنل فراز اس‌ام‌اس ساخته و تایید کرده‌اید، در اینجا وارد کنید.', self::$static_textdomain),
								'note'    => __('پترن شما صرفا باید دارای یک متغیر باشد.', self::$static_textdomain),
								'type'            => 'text',
								'default'        => '',
								'placeholder'    => __('icioن5yzzh', self::$static_textdomain),
								'keywords' => array('کد پترن فراز اس ام اس', 'farazsms pattern code'),
								'custom_class' => 'irantheme-parent-tr-border-bottom-none'
							),
							array(
								'id'             => 'irantheme_sms_panel_farazsms_variable_name',
								'label'            => __(
									'نام متغیر',
									self::$static_textdomain
								),
								'description'    => __('نام متغیر تعریف شده در پترن را بدون کاراکتر "٪" این وارد نمایید.', self::$static_textdomain),
								'note'    => __('لطفا در پترن خود فقط از یک متغیر برای جایگذاری کد تایید (کد اعتبارسنجی) استفاده نمایید.', self::$static_textdomain),
								'type'            => 'text',
								'default'        => '',
								'placeholder'    => __('code', self::$static_textdomain),
								'keywords' => array('نام متغیر فراز اس ام اس', 'farazsms pattern variable name'),
							),
							array(
								'id'             => 'irantheme_sms_panel_smsir_variable_name',
								'label'            => __(
									'نام متغیر',
									self::$static_textdomain
								),
								'description'    => __('نام متغیر تعریف شده در قالب را بدون کاراکتر "#" این وارد نمایید.', self::$static_textdomain),
								'note'    => __('لطفا در قالب خود فقط از یک متغیر برای جایگذاری کد تایید (کد اعتبارسنجی) استفاده نمایید.', self::$static_textdomain),
								'type'            => 'text',
								'default'        => '',
								'placeholder'    => __('code', self::$static_textdomain),
								'keywords' => array('نام متغیر اس ام اس دات ای آر', 'sms.ir pattern variable name'),
							),
							array(
								'id'             => 'irantheme_sms_test_phone',
								'label'            => __(
									'شماره موبایل تست',
									self::$static_textdomain
								),
								'description'    => __('این قسمت پیامک های تست را بر اساس تنظیمات فعلی وب سرویس پیامکی ارسال می کند.', self::$static_textdomain),
								'type'            => 'text',
								'default'        => '',
								'custom_class' => 'irantheme-parent-tr-border-bottom-none',
								'placeholder'    => __('09XXXXXXXXX', self::$static_textdomain),
								'keywords' => array('شماره موبایل تست', 'mobile number for test'),
							),
							array(
								"id"          => "irantheme_sms_test_submit",
								"label"         => __("ارسال پیامک تست", self::$static_textdomain),
								"description"   => 'برای آزمایش اطلاعات وارد شده در بالا از طریق این دکمه میتوانید یک پیامک تست برای شماره مورد نظر ارسال نمایید.',
								"type"          => "custom_button",
								'icon' => 'share-alt2',
								'default' => '',
								'custom_class' => 'irantheme-sms-send-otp-code-submit  irantheme-parent-tr-border-bottom-none',
								'keywords' => array('ارسال پیامک تست', 'ارسال آزمایشی', 'send test otp code'),
							),
							array(
								'id'             => 'irantheme_sms_panel_log',
								'label'            => __(
									'گزارش ارسال',
									self::$static_textdomain
								),
								'description'    => __('در صورت بروز خطا در روند ارسال پیامک از سمت سرویس دهنده پیامکی شما، گزارش خطا رخ داده در این بخش نمایش داده می شود.', self::$static_textdomain),
								'type'            => 'textarea',
								'default'        => 'خطایی در ارسال پیامک از سمت سرویس دهنده وجود ندارد.',
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('گزارش ارسال', 'log of send sms'),
							),
						)
					),
					array(
						'title' => 'تنظیمات عمومی',
						'id' => 'irantheme_sms_panel_more_settings',
						'description'            => __('در این بخش شما می توانید تنظیمات عمومی ورود پیامکی مانند تعداد ارقام کد، رمان انتظار ارسال مجدد و... را تنظیم کنید.', self::$static_textdomain),
						'icon' => 'fa-arrows-left-right-to-line',
						'fields' => array(
							array(
								'id'             => 'irantheme_otp_login_restrictions_login_checkbox',
								'label'            => __(
									'حالت امنیتی برای محدودیت ورود',
									self::$static_textdomain
								),
								'description'    => __('با روشن بودن این گزینه پس از 3 بار تلاش ناموفق برای ورود به حساب کاربری، حساب برای مدتی که در زیر انتخاب می کنید قفل شده و ارسال کد جدید و ورود به آن تا پایان زمان مشخص شده ممکن نخواهد بود.', self::$static_textdomain),
								'type'            => 'checkbox',
								'id_dependents_on' => array('irantheme_otp_login_restrictions_login_time', 'irantheme_otp_excluded_ips'),
								'base_priority' => 'checkbox_base',
								'selector_priority' => 'checkbox_restrictions_login',
								'z_index' => 0,
								'default'        => 'off',
								'keywords' => array('حالت امنیتی برای محدودیت ورود', 'restrictions login'),
							),
							array(
								'id'             => 'irantheme_otp_login_restrictions_login_time',
								'label'            => __(
									'مدت زمان محدودیت (برحسب دقیقه)',
									self::$static_textdomain
								),
								'description'    => __('مدت زمان محدود شدن حساب کاربری (برحسب دقیقه)', self::$static_textdomain),
								'type'            => 'number',
								'default'        => 15,
								'max' => 120,
								'min' => 5,
								'step' => 1,
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('مدت زمان محدودیت', 'restrictions login time')
							),
							array(
								'id'             => 'irantheme_otp_excluded_ips',
								'label'            => __(
									'IP (های) استثنا شده در محدودیت ارسال',
									self::$static_textdomain
								),
								'description'    => __('IP (هایی) را که میخواهید توسط سیستم امنیت پیشرفته محدود نشوند، در این فیلد اضافه کنید.', self::$static_textdomain),
								'note'    => __('شما می توانید در صورت نیاز به افزودن بیش از یک IP آن ها را با کاما انگلیسی "," از هم جدا کنید.', self::$static_textdomain),
								'type'            => 'textarea',
								'default'        => 'خطایی در ارسال پیامک از سمت سرویس دهنده وجود ندارد.',
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('گزارش ارسال', 'log of send sms'),
							),
							array(
								'id'             => 'irantheme_otp_login_number_code_digits',
								'label'            => __(
									'تعداد ارقام کد تایید',
									self::$static_textdomain
								),
								'description'    => __('تعداد ارقام کد تایید (اعتبارسنجی) را از این بخش تنظیم نمایید.', self::$static_textdomain),
								'type'            => 'number',
								'default'        => 6,
								'max' => 12,
								'min' => 4,
								'step' => 1,
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('تعداد ارقام کد تایید', 'number of code digits')
							),
							array(
								'id'             => 'irantheme_otp_login_code_expiration_time',
								'label'            => __(
									'زمان انقضاء کد تایید (برحسب دقیقه)',
									self::$static_textdomain
								),
								'description'    => __('مدت زمانی که با طی شدن آن کد تایید جاری منقضی خواهد شد (برحسب دقیقه)', self::$static_textdomain),
								'type'            => 'number',
								'default'        => 5,
								'max' => 10,
								'min' => 1,
								'step' => 1,
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('زمان انقضاء کد تایید', 'code expiration time')
							),
							array(
								'id'             => 'irantheme_otp_login_code_resend_time',
								'label'            => __(
									'زمان ارسال مجدد کد تایید (برحسب ثانیه)',
									self::$static_textdomain
								),
								'description'    => __('زمان انتظار برای ارسال مجدد کد تایید (برحسب ثانیه)', self::$static_textdomain),
								'type'            => 'number',
								'default'        => 60,
								'max' => 3600,
								'min' => 20,
								'step' => 1,
								'placeholder'    => __('', self::$static_textdomain),
								'keywords' => array('زمان ارسال مجدد کد تایید', 'code resend time')
							),
						)
					),
				)
			);

			$settings = apply_filters('irantheme_store_theme_options_fields', $settings);

			return $settings;
		}


		/**
		 * Return background fields that includes (background-color, background-image, parallax, mask and ...)
		 * @param string
		 * @return array
		 * @author iranianclever
		 * @since v1.0
		 * @see v1.0
		 */
		public static function background_fields_generator($prefix_name, array $set_fields, $background_image_default_value = '', $background_color_default_value = 'off', bool $background_color_have_transparent_default_value = false, $background_mask_default_value = 'on', $background_mask_color_default_value = 'dark', $background_mask_range_default_value = 0.6, $background_parallax_default_value = 'on', $background_outer_countainer_default_value = 'off', $background_image_2_default_value = '', $background_image_3_default_value = '', $background_image_4_default_value = '')
		{
			$fields = array(
				'title' => 'پس زمینه',
				'id' => $prefix_name . '_background',
				'description'            => __('تنظیمات پس زمینه که شامل عکس پس زمینه، رنگ پس زمینه، تصویر زمینه قابل پیمایش (Parallax)، لایه پوشاننده و ... می باشد.', self::$static_textdomain),
				'icon' => 'fa-image',
				'fields' => array()
			);

			// Set background image
			if (in_array('image', $set_fields)) {
				$background_image_field = array(
					'id'             => $prefix_name . '_bg_image',
					'label'            => __('تصویر پس زمینه', self::$static_textdomain),
					'description'    => __('لطفا تصویر خود را بارگذاری نمایید.', self::$static_textdomain),
					'type'            => 'image',
					'default'        => $background_image_default_value,
					'placeholder'    => '',
					'keywords' => array('تصویر پس زمینه', 'تصویر بکگراند', 'background image'),
				);
				// Push background image
				array_push($fields['fields'], $background_image_field);
			}

			// Set background image
			if (in_array('image2', $set_fields)) {
				$background_image_field = array(
					'id'             => $prefix_name . '_bg_image2',
					'label'            => __('تصویر پس زمینه ۲', self::$static_textdomain),
					'description'    => __('لطفا تصویر خود را بارگذاری نمایید.', self::$static_textdomain),
					'type'            => 'image',
					'default'        => $background_image_2_default_value,
					'placeholder'    => '',
					'keywords' => array('تصویر پس زمینه ۲', 'تصویر بکگراند', 'background image'),
				);
				// Push background image
				array_push($fields['fields'], $background_image_field);
			}

			// Set background image
			if (in_array('image3', $set_fields)) {
				$background_image_field = array(
					'id'             => $prefix_name . '_bg_image3',
					'label'            => __('تصویر پس زمینه ۳', self::$static_textdomain),
					'description'    => __('لطفا تصویر خود را بارگذاری نمایید.', self::$static_textdomain),
					'type'            => 'image',
					'default'        => $background_image_3_default_value,
					'placeholder'    => '',
					'keywords' => array('تصویر پس زمینه ۳', 'تصویر بکگراند', 'background image'),
				);
				// Push background image
				array_push($fields['fields'], $background_image_field);
			}

			// Set background image
			if (in_array('image4', $set_fields)) {
				$background_image_field = array(
					'id'             => $prefix_name . '_bg_image3',
					'label'            => __('تصویر پس زمینه ۴', self::$static_textdomain),
					'description'    => __('لطفا تصویر خود را بارگذاری نمایید.', self::$static_textdomain),
					'type'            => 'image',
					'default'        => $background_image_3_default_value,
					'placeholder'    => '',
					'keywords' => array('تصویر پس زمینه ۴', 'تصویر بکگراند', 'background image'),
				);
				// Push background image
				array_push($fields['fields'], $background_image_field);
			}

			// Set background color
			if (in_array('color', $set_fields)) {
				$background_color_field = array(
					'id'             => $prefix_name . '_bg_color',
					'label'            => __(
						'رنگ پس زمینه',
						self::$static_textdomain
					),
					'description'    => __('برای انتخاب رنگ پس زمینه از لیست، رنگ مورد نظر خود را انتخاب کنید.در صورت انتخاب حالت پیش فرض، رنگ پس زمینه بر اساس رنگ پایه تنظیم می شود.(تذکر: برای تنظیم هریک از رنگ های اصلی،فرعی،اولیه،ثانویه و اضافی به بخش " تنظیمات قالب -> رنگ های سایت " مراجعه نمایید.) ', self::$static_textdomain),
					'type'            => 'select',
					'color_preview' => true,
					'options'        => Irantheme_Color_Static::get_all_colors_options($background_color_have_transparent_default_value),
					'default'        => $background_color_default_value,
					'keywords' => array('رنگ پس زمینه', 'رنگ بک گراند', 'background color', 'color'),
				);
				// Push background color
				array_push($fields['fields'], $background_color_field);
			}

			// Set background color
			if (in_array('color2', $set_fields)) {
				$background_color_field = array(
					'id'             => $prefix_name . '_bg_color2',
					'label'            => __(
						'رنگ پس زمینه ۲',
						self::$static_textdomain
					),
					'description'    => __('برای انتخاب رنگ پس زمینه از لیست، رنگ مورد نظر خود را انتخاب کنید.در صورت انتخاب حالت پیش فرض، رنگ پس زمینه بر اساس رنگ پایه تنظیم می شود.(تذکر: برای تنظیم هریک از رنگ های اصلی،فرعی،اولیه،ثانویه و اضافی به بخش " تنظیمات قالب -> رنگ های سایت " مراجعه نمایید.) ', self::$static_textdomain),
					'type'            => 'select',
					'color_preview' => true,
					'options'        => Irantheme_Color_Static::get_all_colors_options($background_color_have_transparent_default_value),
					'default'        => $background_color_default_value,
					'keywords' => array('رنگ پس زمینه ۲', 'رنگ بک گراند', 'background color', 'color'),
				);
				// Push background color
				array_push($fields['fields'], $background_color_field);
			}

			// Set background color
			if (in_array('color3', $set_fields)) {
				$background_color_field = array(
					'id'             => $prefix_name . '_bg_color3',
					'label'            => __(
						'رنگ پس زمینه ۳',
						self::$static_textdomain
					),
					'description'    => __('برای انتخاب رنگ پس زمینه از لیست، رنگ مورد نظر خود را انتخاب کنید.در صورت انتخاب حالت پیش فرض، رنگ پس زمینه بر اساس رنگ پایه تنظیم می شود.(تذکر: برای تنظیم هریک از رنگ های اصلی،فرعی،اولیه،ثانویه و اضافی به بخش " تنظیمات قالب -> رنگ های سایت " مراجعه نمایید.) ', self::$static_textdomain),
					'type'            => 'select',
					'color_preview' => true,
					'options'        => Irantheme_Color_Static::get_all_colors_options($background_color_have_transparent_default_value),
					'default'        => $background_color_default_value,
					'keywords' => array('رنگ پس زمینه ۳', 'رنگ بک گراند', 'background color', 'color'),
				);
				// Push background color
				array_push($fields['fields'], $background_color_field);
			}

			if (in_array('outer_container_color', $set_fields)) {
				$background_outer_countainer_field = array(
					'id'             => $prefix_name . '_outer_container_color_checkbox',
					'label'            => __(
						'اعمال رنگ پس زمینه روی نگهدارنده خارجی',
						self::$static_textdomain
					),
					'description'    => __('در صورت فعال بودن این گزینه رنگ پس زمینه علاوه بر اعمال روی عناصر داخل نگهدارنده داخلی، روی پس زمینه نگهدارنده خارجی این سکشن(بخش) نیز اعمال می گردد.', self::$static_textdomain),
					'type'            => 'checkbox',
					'default'        => $background_outer_countainer_default_value,
					'keywords' => array('showcase', 'blog', 'simple', 'hero', 'ویترین', 'هیرو', 'تصویر زمینه'),
				);
				// Push outer countainer checkbox
				array_push($fields['fields'], $background_outer_countainer_field);
			}

			// Set background mask
			if (in_array('mask', $set_fields)) {
				$background_mask_field = array(
					'id'             => $prefix_name . '_bg_mask_checkbox',
					'label'            => __(
						'لایه پوشاننده تصویر',
						self::$static_textdomain
					),
					'description'    => __('لایه پوشاننده به عنصر شفافی گفته می شود که روی تصویر انتخابی شما را می پوشاند و محتوا شما روی آن قرار می گیرد.کاربرد این لایه زمانی است که تصویر پس زمینه شما دارای ترکیب رنگی خاصی است که متن ها در هر دو رنگ مشکی یا سفید روی آن بخوبی دیده نمی شود،در نتیجه این لایه شفاف باعث می شود که متن شما واضح تر دیده شود.توصیه می شود در صورتی این گزینه را غیر فعال کنید که ترکیب رنگی تصویر پس زمینه شما به شکلی است که متن های محتوا بصورت واضح روی آن دیده می شود.', self::$static_textdomain),
					'type'            => 'checkbox',
					'default'        => $background_mask_default_value,
					'keywords' => array('showcase', 'blog', 'simple', 'hero', 'ویترین', 'هیرو', 'تصویر زمینه'),
					'id_dependents_on' => array($prefix_name . '_bg_mask_color', $prefix_name . '_bg_mask_range'),
					'base_priority' => 'checkbox_base',
					'selector_priority' => $prefix_name . '_checkbox_' . 'bg_mask_priority',
					'z_index' => 0
				);
				// Push mask checkbox
				array_push($fields['fields'], $background_mask_field);

				$background_mask_color_field = array(
					'id'             =>
					$prefix_name . '_bg_mask_color',
					'label'            => __(
						'رنگ لایه پوشاننده تصویر',
						self::$static_textdomain
					),
					'description'    => __('جهت تنظیم رنگ لایه پوشاننده تصویر یکی از گزینه های مشکی یا سفید را انتخاب نمایید.', self::$static_textdomain),
					'type'            => 'select',
					'options'        => array('white' => 'سفید', 'dark' => 'مشکی'),
					'default'        => $background_mask_color_default_value,
					'keywords' => array('رنگ لایه پوشاننده تصویر', 'background color mask', 'mask color', 'mask'),
				);
				// Push mask color
				array_push($fields['fields'], $background_mask_color_field);

				$background_mask_range_field = array(
					'id'             =>
					$prefix_name . '_bg_mask_range',
					'label'            => __(
						'درجه لایه پوشاننده تصویر',
						self::$static_textdomain
					),
					'description'    => __('درجه لایه پوشاننده تصویر مربوط به میزان شفافیت و کدر بودن است و اثر به سزایی در نمایش تصویر پس زمینه دارد.', self::$static_textdomain),
					'type'            => 'range',
					'min' => 0,
					'max' => 1,
					'step' => 0.1,
					'default'        => $background_mask_range_default_value,
					'keywords' => array('درجه لایه پوشاننده تصویر', 'background color mask', 'mask color', 'mask'),
				);
				// Push mask color
				array_push($fields['fields'], $background_mask_range_field);
			}

			// Set background parallax
			if (in_array('parallax', $set_fields)) {
				$background_parallax_field = array(
					'id'             => $prefix_name . '_bg_parallax_checkbox',
					'label'            => __(
						'تصویر زمینه قابل پیمایش',
						self::$static_textdomain
					),
					'description'    => __('در صورت تمایل به تنظیم تصویر زمینه متحرک در زمان اسکرول (Parallax)، این گزینه را فعال نمایید.
								', self::$static_textdomain),
					'type'            => 'checkbox',
					'default'        => $background_parallax_default_value,
					'keywords' => array('showcase', 'blog', 'simple', 'hero', 'ویترین', 'هیرو', 'تصویر زمینه'),
				);
				// Push mask color
				array_push($fields['fields'], $background_parallax_field);
			}

			return $fields;
		}


		/**
		 * Return irregular banner fields
		 * @return array
		 * @param string,array
		 * @author iranianclever
		 * @since v1.0
		 * @see v1.0
		 */
		public static function get_irregular_banner_fields($prefix_name, array $fields, $title = 'تنظیم بنر')
		{
			$fields_result = array(
				'title' => __($title, self::$static_textdomain),
				'id' => $prefix_name . '_irregular_banner',
				'description'            => __('هر گونه تنظیمات مربوط به بنر مانند تصویر زمینه بنر، ارتفاع بنر، محتوای بنر و ...', self::$static_textdomain),
				'icon' => 'fa-paintbrush-pencil',
				'fields' => array()
			);

			// Check background
			if (in_array('background', $fields)) {
				array_push(
					$fields_result['fields'],
					array(
						'id'             => $prefix_name . '_irregular_banner_bg',
						'label'            => __('تصویر پس زمینه بنر', self::$static_textdomain),
						'description'    => __('لطفا تصویر خود را بارگذاری نمایید.', self::$static_textdomain),
						'type'            => 'image',
						'default'        => '',
						'placeholder'    => '',
						'keywords' => array('تصویر پس زمینه بنر', 'banner background', 'background banner'),
					)
				);
			}

			// Check type
			if (in_array('type', $fields)) {
				array_push(
					$fields_result['fields'],
					array(
						'id'             => $prefix_name . '_irregular_banner_type',
						'label'            => __(
							'نوع بنر',
							self::$static_textdomain
						),
						'description'    => __('با انتخاب هر یک از گزینه های زیر می توانید قالب بنر را تنظیم کنید.', self::$static_textdomain),
						'type'            => 'radio',
						'options'        => array('none' => 'بدون بنر', 'technological' => 'فناورانه', 'advanced' => 'پیشرفته', 'luxurious' => 'فاخر'),
						'default'        => 'advanced',
						'keywords' => array('نوع بنر', 'banner type')
					)
				);
			}

			// Check title
			if (in_array('title', $fields)) {
				array_push(
					$fields_result['fields'],
					array(
						'id'             => $prefix_name . '_irregular_banner_custom_heading_checkbox',
						'label'            => __(
							'فعال سازی عنوان و توضیحات دستی بنر',
							self::$static_textdomain
						),
						'description'    => __('در صورتی که علاقمند به تنظیم عنوان و توضیحات ریز بنر می باشید می توانید این گزینه را فعال نمایید.', self::$static_textdomain),
						'type'            => 'checkbox',
						'default'        => 'off',
						'keywords' => array('فعال سازی عنوان دستی بنر', 'enable banner custom title'),
						'id_dependents_on' => array($prefix_name . '_irregular_banner_custom_title', $prefix_name . '_irregular_banner_custom_description'),
						'base_priority' => 'checkbox_base',
						'selector_priority' => $prefix_name . '_checkbox_' . 'banner_custom_priority',
						'z_index' => 0
					),
					array(
						'id'             => $prefix_name . '_irregular_banner_custom_title',
						'label'            => __(
							'عنوان دستی بنر',
							self::$static_textdomain
						),
						'description'    => __('عنوانی برای بنر خود انتخاب کنید.', self::$static_textdomain),
						'type'            => 'text',
						'default'        => '',
						'placeholder'    => __('', self::$static_textdomain),
						'keywords' => array('عنوان دستی بنر', 'banner custom title'),
					),
					array(
						'id'             => $prefix_name . '_irregular_banner_custom_description',
						'label'            => __(
							'توضیحات دستی بنر',
							self::$static_textdomain
						),
						'description'    => __('توضیحاتی برای بنر خود انتخاب کنید.', self::$static_textdomain),
						'type'            => 'textarea',
						'default'        => '',
						'placeholder'    => __('', self::$static_textdomain),
						'keywords' => array('توضیحات دستی بنر', 'banner custom description'),
					),
				);
			}

			return $fields_result;
		}


		/**
		 * Return irregular footer fields
		 * @return array
		 * @param string,array
		 * @author iranianclever
		 * @since v1.0
		 * @see v1.0
		 */
		public static function get_footer_fields($prefix_name, array $fields)
		{
			$fields_result = array(
				'title' => __('تنظیم فوتر', self::$static_textdomain),
				'id' => $prefix_name . '_footer',
				'description'            => __('هر گونه تنظیمات مربوط به فوتر مانند تصویر زمینه فوتر، ارتفاع فوتر، محتوای فوتر و ...', self::$static_textdomain),
				'icon' => 'fa-paintbrush-pencil',
				'fields' => array()
			);

			// Check type
			if (in_array('type', $fields)) {
				array_push(
					$fields_result['fields'],
					array(
						'id'             => $prefix_name . '_footer_template_checkbox',
						'label'            => __(
							'فعال سازی استفاده از الگو ها',
							self::$static_textdomain
						),
						'description'    => __('در صورتی که در الگو های صفحه ساز، فوتر شخصی سازی شده ای دارید می توانید به جای فوتر های آماده استفاده نمایید.', self::$static_textdomain),
						'type'            => 'checkbox',
						'default'        => 'off',
						'keywords' => array('فعال سازی استفاده از الگو ها', 'enable using templates'),
						'id_dependents_on' => array($prefix_name . '_footer_template_select'),
						'id_dependents_off' => array($prefix_name . '_footer_type'),
						'base_priority' => 'checkbox_base',
						'selector_priority' => $prefix_name . '_checkbox_' . 'footer_template',
						'z_index' => 0
					)
				);
				array_push(
					$fields_result['fields'],
					array(
						'id'             => $prefix_name . '_footer_template_select',
						'label'            => __(
							'انتخاب الگو فوتر',
							self::$static_textdomain
						),
						'description'    => __('در صورتی که الگوی فوتر ذخیره کرده اید می توانید اینجا مشاهده و انتخاب کنید.', self::$static_textdomain),
						'type'            => 'select',
						'options'        => Irantheme_Page_Builder_Ajax::get_all_footer_templates(),
						'default'        => '',
						'keywords' => array('انتخاب الگو فوتر', 'select footer template'),
					)
				);
				array_push(
					$fields_result['fields'],
					array(
						'id'             => $prefix_name . '_footer_type',
						'label'            => __(
							'نوع فوتر',
							self::$static_textdomain
						),
						'description'    => __('با انتخاب هر یک از گزینه های زیر می توانید نوع فوتر را تنظیم کنید.', self::$static_textdomain),
						'type'            => 'radio',
						'options'        => array('none' => 'بدون فوتر', 'technological' => 'فوتر وبلاگی', 'advanced' => 'فوتر ساده', 'luxurious' => 'فوتر نظرات'),
						'default'        => 'advanced',
						'keywords' => array('نوع فوتر', 'footer type')
					)
				);
			}
			return $fields_result;
		}


		/**
		 * Return content of blog and archive fields
		 * @return array
		 * @param string,array,bool
		 * @author iranianclever
		 * @since v1.0
		 * @see v1.0
		 */
		public static function get_blog_archive_content_fields($prefix_name, array $fields, $is_single = false)
		{
			$fields_result = array(
				'title' => 'تنظیم محتوا',
				'id' => $prefix_name . '_content',
				'description'            => __('تنظیم محتوا شامل چیدمان محتوا، جزئیات نمایش پست ها، رنگ بندی، امکانات و ... می باشد.', self::$static_textdomain),
				'icon' => 'fa-paintbrush-pencil',
				'fields' => array()
			);

			// Check content
			if (in_array('content', $fields)) {
				$field_values = array('contemporary' => 'نوین', 'technological' => 'فناورانه', 'advanced' => 'پیشرفته', 'luxurious' => 'فاخر', 'traditional' => 'سنتی');
				// Check single
				if ($is_single) {
					unset($field_values['traditional']);
				}
				array_push($fields_result['fields'], array(
					'id'             => $prefix_name . '_content_template',
					'label'            => __(
						'قالب محتوا',
						self::$static_textdomain
					),
					'description'    => __('با انتخاب هر یک از گزینه های زیر می توانید قالب محتوا را تنظیم کنید.', self::$static_textdomain),
					'type'            => 'radio',
					'options'        => $field_values,
					'default'        => 'luxurious',
					'keywords' => array('قالب محتوا', 'content template')
				));
			}

			// Check post number
			if (in_array('post-number', $fields)) {
				array_push($fields_result['fields'], array(
					'id'             => $prefix_name . '_content_post_number',
					'label'            => __(
						'تعداد پست محتوا',
						self::$static_textdomain
					),
					'description'    => __('با استفاده از این قابلیت می توانید تعداد پست های نمایشی خود در این صفحه را تنظیم نمایید.', self::$static_textdomain),
					'type'            => 'number',
					'default'        => 12,
					'max' => 99,
					'min' => 1,
					'step' => 1,
					'placeholder'    => __('', self::$static_textdomain),
					'keywords' => array('تعداد پست محتوا', 'post content number', 'post count', 'post number')
				));
			}

			// Check page is single or no
			if ($is_single) {
				// Post details
				if (in_array('post-details', $fields)) {
					array_push($fields_result['fields'], array(
						'id'             => $prefix_name . '_content_post_details_checkbox_multi',
						'label'            => __(
							'جزئیات نوشته',
							self::$static_textdomain
						),
						'description'    => __('با استفاده از این گزینه می توانید نوع جزئیات قابل نمایش برای هر مطلب(پست) را مشخص کنید.مانند تاریخ انتشار، دسته بندی و غیره... ', self::$static_textdomain),
						'type'            => 'checkbox_multi',
						'options'        => array('thumbnail' => 'تصویر', 'date' => 'تاریخ انتشار', 'author' => 'نویسنده',  'category' => 'دسته بندی', 'comment' => 'دیدگاه ها', 'tag' => 'برچسب ها', 'like' => 'لایک', 'share' => 'اشتراک گذاری', 'related-post' => 'مطالب مرتبط'),
						'default'        => 'thumbnail,date,author,category,comment,excerpt',
						'keywords' => array('جزئیات نوشته', 'post details')
					));
				}
			} else {
				// Post details
				if (in_array('post-details', $fields)) {
					array_push($fields_result['fields'], array(
						'id'             => $prefix_name . '_content_post_details_checkbox_multi',
						'label'            => __(
							'جزئیات نوشته',
							self::$static_textdomain
						),
						'description'    => __('با استفاده از این گزینه می توانید نوع جزئیات قابل نمایش برای هر مطلب(پست) را مشخص کنید.مانند تاریخ انتشار، دسته بندی و غیره... ', self::$static_textdomain),
						'type'            => 'checkbox_multi',
						'options'        => array('thumbnail' => 'تصویر', 'date' => 'تاریخ انتشار', 'author' => 'نویسنده',  'category' => 'دسته بندی', 'comment' => 'دیدگاه ها', 'excerpt' => 'گزیده مطلب', 'like' => 'لایک', 'read-more' => 'لینک ادامه مطلب'),
						'default'        => 'thumbnail,date,author,category,comment,excerpt',
						'keywords' => array('جزئیات نوشته', 'post details')
					));
				}
			}

			// Check sidebar
			if (in_array('sidebar', $fields)) {
				array_push($fields_result['fields'], array(
					'id'             => $prefix_name . '_content_sidebar_checkbox',
					'label'            => __(
						'فعال سازی سایدبار',
						self::$static_textdomain
					),
					'description'    => __('در صورت فعال کردن سایدبار نمایش داده می شود.', self::$static_textdomain),
					'type'            => 'checkbox',
					'default'        => 'off',
					'keywords' => array('فعال سازی سایدبار', 'نمایش سایدبار', 'display sidebar', 'show sidebar'),
					'id_dependents_on' => array($prefix_name . '_content_sidebar_position', $prefix_name . '_content_sidebar_select'),
					'id_dependents_off' => array(),
					'base_priority' => 'checkbox_base',
					'selector_priority' => $prefix_name . '_sidebar_priority',
					'z_index' => 0
				));
				array_push($fields_result['fields'], array(
					'id'             => $prefix_name . '_content_sidebar_position',
					'label'            => __(
						'چیدمان سایدبار',
						self::$static_textdomain
					),
					'description'    => __('با استفاده از این گزینه می توانید سبک نمایش سایدبار خود را مشخص کنید.', self::$static_textdomain),
					'type'            => 'radio',
					'thumbnail_status' => true,
					'options'        => array('left' => array('سایدبار چپ', 'align-section-right.png'), 'right' => array('سایدبار راست', 'align-section-left.png')),
					'default'        => 'left',
					'keywords' => array('split', 'mode', 'سبک', 'ویترین', 'چیدمان', 'alignment')
				));
				array_push($fields_result['fields'], array(
					'id'             => $prefix_name . '_content_sidebar_select',
					'label'            => __(
						'انتخاب سایدبار',
						self::$static_textdomain
					),
					'description'    => __('با استفاده از این گزینه شما می توانید سایدبار های تولید شده در قسمت "نمایش -> ابزارک ها" را انتخاب نمایید.', self::$static_textdomain),
					'type'            => 'select',
					'options'        => Irantheme_Custom_Fields::get_all_sidebars(),
					'default'        => 'irantheme_sidebar_template',
					'keywords' => array('انتخاب سایدبار', 'sidebar select'),
				),);
			}

			// Check column
			if (in_array('column', $fields)) {
				array_push($fields_result['fields'], array(
					'id'             => $prefix_name . '_content_column',
					'label'            => __('تعداد ستون ها', self::$static_textdomain),
					'description'    => __('با استفاده از این گزینه شما می توانید تعداد ستون قابل نمایش برای محتوا را مشخص کنید', self::$static_textdomain),
					'type'            => 'radio',
					'thumbnail_status' => true,
					'options'        => array(
						'col-1' => array('یک ستون', 'col-1.png'),
						'col-2' => array('دو ستون', 'col-2.png'),
						'col-3' => array('سه ستون', 'col-3.png'),
						'col-4' => array('چهار ستون', 'col-4.png'),
						'col-6' => array('شش ستون', 'col-6.png')
					),
					'default'        => 'col-3',
					'keywords' => array('تعداد ستون ها', 'count of columns'),
				));
			}

			// Check layout
			if (in_array('layout', $fields)) {
				array_push($fields_result['fields'], array(
					'id'             => $prefix_name . '_content_style',
					'label'            => __(
						'سبک نمایش محتوا',
						self::$static_textdomain
					),
					'description'    => __('با استفاده از این گزینه می توانید سبک نمایش محتوا را مشخص نمایید.', self::$static_textdomain),
					'type'            => 'radio',
					'thumbnail_status' => true,
					'options'        => array(
						'grid' => array('کاشی', 'grid.png'),
						'masonry' => array('آجری', 'masonry.png'),
					),
					'default'        => 'grid',
					'note'    => __('تنظیم ارتفاع تصویر در سبک نمایش محتوای "آجری" کاربرد ندارد.', self::$static_textdomain),
					'keywords' => array('سبک نمایش محتوا', 'mode style')
				));
			}

			// Image height
			if (in_array('height', $fields)) {
				array_push($fields_result['fields'], array(
					'id'             => $prefix_name . '_content_post_height',
					'label'            => __(
						'ارتفاع تصویر',
						self::$static_textdomain
					),
					'description'    => __('با اسفاده از این گزینه می توانید ارتفاع تصویر را تنظیم نمایید.(واحد px می باشد)', self::$static_textdomain),
					'type'            => 'range',
					'min' => 180,
					'max' => 450,
					'step' => 1,
					'default'        => 280,
					'placeholder'    => __('Placeholder text', self::$static_textdomain),
					'keywords' => array('ارتفاع تصویر', 'ارتفاع تصویر', 'post height')
				));
			}

			// Image category toggle
			if (in_array('category-toggle', $fields)) {
				array_push($fields_result['fields'], array(
					'id'             => $prefix_name . '_content_post_category_toggle_checkbox',
					'label'            => __(
						'فعال سازی دسته بندی',
						self::$static_textdomain
					),
					'description'    => __('در صورت فعال کردن، دسته بندی نمایش داده می شود.', self::$static_textdomain),
					'type'            => 'checkbox',
					'default'        => 'on',
					'keywords' => array('فعال سازی دسته بندی', 'نمایش دسته بندی', 'display category toggle', 'show category toggle'),
				));
			}

			return $fields_result;
		}


		/**
		 * Return visual of blog and archive fields
		 * @return array
		 * @param string,array,bool
		 * @author iranianclever
		 * @since v1.0
		 * @see v1.0
		 */
		public static function get_blog_archive_visual_fields($prefix_name, array $fields, $is_single = false)
		{
			$fields_result = array(
				'title' => 'تنظیمات بصری',
				'id' => $prefix_name . '_visual',
				'description'            => __('تنظیم بصری شامل جزئیات رنگ و ظاهر ... می باشد.', self::$static_textdomain),
				'icon' => 'fa-paintbrush-pencil',
				'fields' => array()
			);

			// Check details color
			if (in_array('base-color', $fields)) {
				array_push($fields_result['fields'], array(
					'id'             => $prefix_name . '_base_color_select',
					'label'            => __(
						'رنگ پایه',
						self::$static_textdomain
					),
					'description'    => __('برای انتخاب رنگ پایه از لیست، رنگ دلخواه خود را انتخاب کنید.رنگ محتواو پس زمینه این بخش بر اساس این رنگ تنظیم خواهد شد.', self::$static_textdomain),
					'type'            => 'select',
					'options'        => array('white' => 'سفید', 'dark' => 'مشکی'),
					'default'        => 'white',
					'keywords' => array('color', 'background', 'پس زمینه', 'رنگ'),
				),);
			}

			// Check details color
			if (in_array('details-color', $fields)) {
				array_push($fields_result['fields'], array(
					'id'             => $prefix_name . '_visual_color_select',
					'label'            => __(
						'رنگ جزئیات',
						self::$static_textdomain
					),
					'description'    => __('برای انتخاب رنگ جزئیات از لیست رنگ دلخواه خود را انتخاب کنید. این رنگ تاثیر مستقیمی در زیبایی دارد.', self::$static_textdomain),
					'type'            => 'select',
					'options'        => Irantheme_Color_Static::get_all_colors_options(),
					'color_preview' => true,
					'default'        => 'primary',
					'keywords' => array('رنگ جزئیات مطالب', 'details color post'),
				));
			}

			// Check border radius
			if (in_array('radius', $fields)) {
				$custom_radius_range = array(
					'id'             => $prefix_name . '_visual_border_radius_range',
					'label'            => __(
						'خمیدگی حاشیه',
						self::$static_textdomain
					),
					'description'    => __('با اسفاده از این گزینه می توانید خمیدگی حاشیه (Border Radius) را تعیین کنید.(واحد rem می باشد)', self::$static_textdomain),
					'type'            => 'range',
					'min' => 0,
					'max' => 2.5,
					'step' => 0.1,
					'default'        => 0.5,
					'placeholder'    => __('Placeholder text', self::$static_textdomain),
					'keywords' => array('خمیدگی حاشیه', 'banner border radius',)
				);
				// Push radius
				array_push($fields_result['fields'], $custom_radius_range);
			}

			return $fields_result;
		}
	}
}
