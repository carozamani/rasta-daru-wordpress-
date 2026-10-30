<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;


if (!class_exists('Irantheme_Color_Static')) {

    /**
     * Create custom fields for post types
     *
     * @package Wordpress
     * @subpackage irantheme
     * @since irantheme - custom fields
     * @author iranianclever
     * @see v1.0
     *
     */

    class Irantheme_Color_Static
    {
        /**
         * Constructor
         * @return void
         * @param void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        function __construct() {}

        /**
         * Return color flat array
         * @return void
         * @param void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_flat_colors()
        {
            $flat_colors = array(
                '346DF9' => 'آبی استورپلاس',
                '1F2229' => 'مشکی استورپلاس',
                'hr',
                '32127A' => 'نیلی ایرانی',
                '1C39BB' => 'آبی ایرانی',
                '0067A5' => 'آبی ایرانی (میانه)',
                '00A693' => 'سبز ایرانی',
                'F77FBE' => 'صورتی ایرانی',
                'FE28A2' => 'رز ایرانی (گل سرخی)',
                '701C1C' => 'عنابی ایرانی',
                'CC3333' => 'قرمز ایرانی',
                'D99058' => 'نارنجی ایرانی',
                'hr',
                '000000' => 'مشکی',
                '1E1E24' => 'مشکی رایزن',
                '373E40' => 'انیکس',
                '3E3D3E' => 'خاکستری الترا',
                '4B5358' => 'متال اسپیس',
                '727072' => 'خاکستری کم نور',
                '817EA0' => 'خاکستری سرد',
                '858599' => 'خاکستری سرد',
                '949BA0' => 'خاکستری کادت',
                'AEADAE' => 'نقره ای',
                'B1B1BE' => 'خاکستری فرانسوی',
                'DEDEE3' => 'پلاتینیوم',
                '231123' => 'بنفش دارک',
                '310A31' => 'بنفش تیره',
                '3C1642' => 'بنفشه روسی',
                '372554' => 'بنفش روسی',
                '6B6FF1' => 'بنفش آلفا',
                '8E44AD' => 'بنفش ویستریا',
                '9B59B6' => 'بنفش آمتیست',
                'F26DF9' => 'صورتی جیغ',
                'D2D6EF' => 'سنبل',
                'EFECFD' => 'بنفش کپچر',
                '52528C' => 'فرا بنفش',
                '5158BB' => 'آبی ساوی',
                '111D4A' => 'آبی فضایی',
                '282C67' => 'آبی دلفت',
                '043565' => 'یال آبی',
                '053DF5' => 'آبی جیغ',
                '2D51C8' => 'آبی بیزانس',
                '0052CC' => 'آبی پرتو',
                '4A5ABF' => 'آبی الترا',
                '086375' => 'آبی کارائیب',
                '628395' => 'آبی نیرو هوایی',
                '2980B9' => 'آبی بلیز',
                '3B99F7' => 'آبی طفره زن',
                '3498DB' => 'آبی پیتر ریور',
                '10C3F9' => 'آبی آسمانی',
                '1DD3B0' => 'فیروزه ای',
                '1FEAEA' => 'فیروزه ای فلورسنت',
                '9CF6F6' => 'آبی یخی',
                'A5CCD1' => 'آبی طوسی',
                'BBE5ED' => 'آبی کم رنگ',
                'F4F9FD' => 'آبی آبرنگ',
                '16271D' => 'ماشی',
                '02394A' => 'سبز نیمه شب',
                '2B4F36' => 'سبز جلبکی',
                '305252' => 'تخته سنگ سبز',
                '488286' => 'سبز رنگ',
                '16A085' => 'سبز دریا',
                '43A6A3' => 'سبز پرتو',
                '049A5B' => 'سبز چمنی',
                '00A335' => 'سبز درختی',
                '05bE70' => 'سبز نچرال',
                '08D57F' => 'سبز مدیوم',
                '12eb90' => 'سبز اینترمدیت',
                '32fCA7' => 'سبز فلت',
                '569F72' => 'یشمی',
                '79B791' => 'سبز کمبریج',
                '7FC29B' => 'سبز کمبریج',
                'ABD1B5' => 'سلادون',
                'AFFC41' => 'سبز زرد',
                '510A32' => 'قرمز پن دارک',
                '801336' => 'قرمز خونی',
                '92140C' => 'قرمز پن',
                'A72C41' => 'قرمز تیره',
                'C0392B' => 'قرمز اناری',
                'EE3C2F' => 'قرمز جیغ',
                'E74C3C' => 'قرمز آلیزارین',
                'F4544C' => 'قرمز پرتو',
                'B81463' => 'سرخابی',
                'EB4B98' => 'آب نبات صورتی',
                'F37168' => 'تلخ و شیرین',
                'FF7477' => 'قرمز روشن',
                'F18280' => 'قرمز آبنوس',
                'F5BBB7' => 'ملون',
                'D35400' => 'نارنجی پامپکین',
                'FF6716' => 'نارنجی پرتقالی سیر',
                'E67E22' => 'نارنجی کروت',
                'FF961F' => 'نارنجی پرتقالی',
                'F39C12' => 'پرتغالی',
                'DBAD6A' => 'زرد زمینی',
                'FFB900' => 'زرد اسپرت',
                'F1C40F' => 'گل خورشید',
                'FFD700' => 'طلایی',
                'FFD500' => 'زرد سانرایس',
                'FFDC2E' => 'زرد لیمویی کدر',
                'FFE761' => 'زرد لیمویی',
                'FFEA76' => 'زرد لیمویی روشن',
                'F9EA9A' => 'کتان',
                'FFF394' => 'زرد شاین',
                'FFF9E2' => 'زرد روشن',
                'BD966F' => 'رز گلد لایت',
                'AD9263' => 'رز گلد',
                'AB8B65' => 'رز گلد دارک',
                '301014' => 'لوبیا تیره',
                '684E32' => 'کافی',
                'F6F3EF' => 'کافی روشن',
                '784F41' => 'قهوه ای',
                '986C6A' => 'گل رز تاپ',
                '96897B' => 'سگ آبی',
                'B399A2' => 'رز کوارتز',
                'D2AB99' => 'شن صحرا',
                'FFCF99' => 'سانست',
                'F7D4BC' => 'شن بیابان',
                'F4ECD6' => 'پوستی',
                'EDF4ED' => 'سفید نعنایی',
                'FFF8F0' => 'سفید فلورال',
                'FFFFFF' => 'سفید',
                'hr',
                '424852' => 'استاندارد طراحی فلت',
                '636C77' => 'استاندارد طراحی فلت',
                'A9B0BB' => 'استاندارد طراحی فلت',
                'CAD0D7' => 'استاندارد طراحی فلت',
                'E4E8EB' => 'استاندارد طراحی فلت',
                'F4F6F8' => 'استاندارد طراحی فلت',
                'E3B692' => 'استاندارد طراحی فلت',
                'EFC7A4' => 'استاندارد طراحی فلت',
                'F4D0B5' => 'استاندارد طراحی فلت',
                'D56FAC' => 'استاندارد طراحی فلت',
                'EB86BE' => 'استاندارد طراحی فلت',
                'F199CE' => 'استاندارد طراحی فلت',
                '9579DA' => 'استاندارد طراحی فلت',
                'AC92EA' => 'استاندارد طراحی فلت',
                'B3A4EE' => 'استاندارد طراحی فلت',
                '4A88DA' => 'استاندارد طراحی فلت',
                '5E9CEA' => 'استاندارد طراحی فلت',
                '73B0F4' => 'استاندارد طراحی فلت',
                '3AADD9' => 'استاندارد طراحی فلت',
                '4FC0E8' => 'استاندارد طراحی فلت',
                '66D4F1' => 'استاندارد طراحی فلت',
                '35BA9B' => 'استاندارد طراحی فلت',
                '46CEAC' => 'استاندارد طراحی فلت',
                '61DDBC' => 'استاندارد طراحی فلت',
                '89C053' => 'استاندارد طراحی فلت',
                '9ED26A' => 'استاندارد طراحی فلت',
                'B4DF80' => 'استاندارد طراحی فلت',
                'F5B945' => 'استاندارد طراحی فلت',
                'FDCD56' => 'استاندارد طراحی فلت',
                'FBD277' => 'استاندارد طراحی فلت',
                'E8553E' => 'استاندارد طراحی فلت',
                'FA6C51' => 'استاندارد طراحی فلت',
                'FB836F' => 'استاندارد طراحی فلت',
                'D94452' => 'استاندارد طراحی فلت',
                'EB5463' => 'استاندارد طراحی فلت',
                'F76C82' => 'استاندارد طراحی فلت',

            );

            return $flat_colors;
        }

        /**
         * Return list of colors types, the function generate colors types
         * @return array
         * @param void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_colors_options_list($option_name, $fill = 5, $trans = 0.2)
        {
            $color = '';

            // Note: This format condition is require for two mode $option_name
            if ($option_name == 'main_color' || strpos($option_name, 'main_color') !== false) {
                if (get_option('irantheme_main_color_custom_checkbox') == 'on') {
                    $color = str_replace('#', '', get_option('irantheme_main_color_picker'));
                } else {
                    $color = get_option('irantheme_main_color_select');
                }
            } else if ($option_name == 'sub_color' || strpos($option_name, 'sub_color') !== false) {
                if (get_option('irantheme_sub_color_custom_checkbox') == 'on') {
                    $color = str_replace('#', '', get_option('irantheme_sub_color_picker'));
                } else {
                    $color = get_option('irantheme_sub_color_select');
                }
            } else if ($option_name == 'primary_color' || strpos($option_name, 'primary_color') !== false) {
                if (get_option('irantheme_primary_color_custom_checkbox') == 'on') {
                    $color = str_replace('#', '', get_option('irantheme_primary_color_picker'));
                } else {
                    $color = get_option('irantheme_primary_color_select');
                }
            } else if ($option_name == 'secondary_color' || strpos($option_name, 'secondary_color') !== false) {
                if (get_option('irantheme_secondary_color_custom_checkbox') == 'on') {
                    $color = str_replace('#', '', get_option('irantheme_secondary_color_picker'));
                } else {
                    $color = get_option('irantheme_secondary_color_select');
                }
            } else if ($option_name == 'tertiary_color' || strpos($option_name, 'tertiary_color') !== false) {
                if (get_option('irantheme_tertiary_color_custom_checkbox') == 'on') {
                    $color = str_replace('#', '', get_option('irantheme_tertiary_color_picker'));
                } else {
                    $color = get_option('irantheme_tertiary_color_select');
                }
            }

            $color_rgb_raw = Irantheme_Color_Static::hex_to_rgb($color);
            $color_r = $color_rgb_raw[0];
            $color_g = $color_rgb_raw[1];
            $color_b = $color_rgb_raw[2];
            // color fill
            $color_fill = Irantheme_Color_Static::rgb_to_hex(array($color_r - $fill, $color_g - $fill, $color_b - $fill));
            // color transparent 0.2
            $color_trans_2 = 'rgba(' . $color_r . ', ' . $color_g . ', ' . $color_b . ', ' . $trans . ')';
            $color_result = array(
                'hex' => $color,
                'hex_fill' => $color_fill,
                'rgb_trans' => $color_trans_2
            );
            return $color_result;
        }

        /**
         * Return class mode color (Background, color, border, hover)
         * @return string
         * @param void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_class_color_mode($mode, $type_prefixer = null, $color_hex = null)
        {
            // A prefixer of storage type color
            $class_prefixer = $type_prefixer ? $type_prefixer : '';

            // Check prefixer init
            if ($color_hex) {
                // Get all colors options
                $main = self::get_colors_options_list('main_color');
                $sub = self::get_colors_options_list('sub_color');
                $primary = self::get_colors_options_list('primary_color');
                $secondary = self::get_colors_options_list('secondary_color');
                $tertiary = self::get_colors_options_list('tertiary_color');

                // Set class_prefixer color type
                switch ($color_hex) {
                    case $main['hex']:
                        $class_prefixer = 'main';
                        break;
                    case $sub['hex']:
                        $class_prefixer = 'sub';
                        break;
                    case $primary['hex']:
                        $class_prefixer = 'primary';
                        break;
                    case $secondary['hex']:
                        $class_prefixer = 'secondary';
                        break;
                    case $tertiary['hex']:
                        $class_prefixer = 'tertiary';
                        break;
                }
            }

            // Create class mode and return
            switch ($mode) {
                case 'color':
                    return sprintf('irantheme-color-%s', $class_prefixer);
                    break;
                case 'color-before':
                    return sprintf('irantheme-color-%s-before', $class_prefixer);
                    break;
                case 'color-after':
                    return sprintf('irantheme-color-%s-after', $class_prefixer);
                    break;
                case 'bg-before':
                    return sprintf('irantheme-bg-%s-before', $class_prefixer);
                    break;
                case 'bg-after':
                    return sprintf('irantheme-bg-%s-after', $class_prefixer);
                    break;
                case 'border':
                    return sprintf('irantheme-color-%s-border', $class_prefixer);
                    break;
                case 'color-fill':
                    return sprintf('irantheme-color-%s-fill', $class_prefixer);
                    break;
                case 'bg-fill':
                    return sprintf('irantheme-color-%s-bg-fill', $class_prefixer);
                    break;
                case 'bg-glass':
                    return sprintf('irantheme-color-%s-bg-glass', $class_prefixer);
                    break;
                case 'bg':
                    return sprintf('irantheme-color-%s-bg', $class_prefixer);
                    break;
                case 'color-hover':
                    return sprintf('irantheme-color-%s-hover', $class_prefixer);
                    break;
                case 'color-hover-i':
                    return sprintf('irantheme-color-%s-hover-i', $class_prefixer);
                    break;
                case 'bg-hover':
                    return sprintf('irantheme-color-%s-bg-hover', $class_prefixer);
                    break;
                case 'bg-hover-i':
                    return sprintf('irantheme-color-%s-bg-hover-i', $class_prefixer);
                    break;
                case 'btn':
                    return sprintf('irantheme-btn-bg-%s', $class_prefixer);
                    break;
                case 'active':
                    return sprintf('irantheme-active-bg-%s', $class_prefixer);
                    break;
                case 'bg-active-i':
                    return sprintf('irantheme-active-%s-bg-i', $class_prefixer);
                    break;
                case 'color-active':
                    return sprintf('irantheme-active-color-%s', $class_prefixer);
                    break;
                case 'color-active-i':
                    return sprintf('irantheme-active-color-%s-i', $class_prefixer);
                    break;
                case 'color-active-before':
                    return sprintf('irantheme-active-bg-%s-before', $class_prefixer);
                    break;
                case 'color-hover-before':
                    return sprintf('irantheme-bg-%s-hover-before', $class_prefixer);
                    break;
                case 'color-active-after':
                    return sprintf('irantheme-active-bg-%s-after', $class_prefixer);
                    break;
                case 'color-hover-after':
                    return sprintf('irantheme-bg-%s-hover-after', $class_prefixer);
                    break;
                // Without cases
                default:
                    return '';
                    break;
            }
        }


        /**
         * Return all colors option
         * @param void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_hex_color($color_mode)
        {
            // Get main color option
            if (get_option('irantheme_main_color_custom_checkbox') == 'on') {
                $color_main = str_replace('#', '', get_option('irantheme_main_color_picker'));
            } else {
                $color_main = get_option('irantheme_main_color_select');
            }
            // Get sub color option
            if (get_option('irantheme_sub_color_custom_checkbox') == 'on') {
                $color_sub = str_replace('#', '', get_option('irantheme_sub_color_picker'));
            } else {
                $color_sub = get_option('irantheme_sub_color_select');
            }
            // Get primary color option
            if (get_option('irantheme_primary_color_custom_checkbox') == 'on') {
                $color_primary = str_replace('#', '', get_option('irantheme_primary_color_picker'));
            } else {
                $color_primary = get_option('irantheme_primary_color_select');
            }
            // Get secondary color option
            if (get_option('irantheme_secondary_color_custom_checkbox') == 'on') {
                $color_secondary = str_replace('#', '', get_option('irantheme_secondary_color_picker'));
            } else {
                $color_secondary = get_option('irantheme_secondary_color_select');
            }
            // Get tertiary color option
            if (get_option('irantheme_tertiary_color_custom_checkbox') == 'on') {
                $color_tertiary = str_replace('#', '', get_option('irantheme_tertiary_color_picker'));
            } else {
                $color_tertiary = get_option('irantheme_tertiary_color_select');
            }

            // Check color mode and return color with hex format
            switch ($color_mode) {
                case 'main':
                    return $color_main;
                    break;
                case 'sub':
                    return $color_sub;
                    break;
                case 'primary':
                    return $color_primary;
                    break;
                case 'secondary':
                    return $color_secondary;
                    break;
                case 'tertiary':
                    return $color_tertiary;
                    break;

                default:
                    return $color_mode;
                    break;
            }
        }


        /**
         * Return all colors option
         * @return array
         * @param void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function get_all_colors_options($with_trans = false, $nav_menu_status = false, $mask_layer_status = false, $badge_status = false)
        {
            if ($mask_layer_status) {
                // Combination colors
                $colors = array(
                    'dark' => 'مشکی',
                    'white' => 'سفید',
                    'main' => 'رنگ اصلی',
                    'sub' => 'رنگ فرعی',
                    'primary' => 'رنگ اولیه',
                    'secondary' => 'رنگ ثانویه',
                    'tertiary' => 'رنگ اضافی'
                );
                return $colors;
            }
            if ($nav_menu_status) {
                // Combination colors
                $colors = array(
                    'off' => 'خاموش',
                    'default' => 'پیش فرض',
                    'main' => 'رنگ اصلی',
                    'sub' => 'رنگ فرعی',
                    'primary' => 'رنگ اولیه',
                    'secondary' => 'رنگ ثانویه',
                    'tertiary' => 'رنگ اضافی'
                );
                return $colors;
            }
            // Check for trans default color
            if ($with_trans) {
                // Combination colors
                $colors = array(
                    'off' => 'پیش فرض',
                    'transparent' => 'شیشه ای',
                    'main' => 'رنگ اصلی',
                    'sub' => 'رنگ فرعی',
                    'primary' => 'رنگ اولیه',
                    'secondary' => 'رنگ ثانویه',
                    'tertiary' => 'رنگ اضافی'
                );
            } else {
                // Combination colors
                $colors = array(
                    'off' => 'پیش فرض',
                    'main' => 'رنگ اصلی',
                    'sub' => 'رنگ فرعی',
                    'primary' => 'رنگ اولیه',
                    'secondary' => 'رنگ ثانویه',
                    'tertiary' => 'رنگ اضافی'
                );
            }
            if ($badge_status) {
                // Combination colors
                $colors = array(
                    'off' => 'پیش فرض',
                    'hr',
                    '346DF9' => 'آبی',
                    'e67e22' => 'نارنجی',
                    'e74c3c' => 'قرمز',
                    '2ecc71' => 'سبز',
                    'f1c430' => 'زرد',
                    '9b59b6' => 'بنفش',
                    'hr',
                    'main' => 'رنگ اصلی',
                    'sub' => 'رنگ فرعی',
                    'primary' => 'رنگ اولیه',
                    'secondary' => 'رنگ ثانویه',
                    'tertiary' => 'رنگ اضافی'
                );
                return $colors;
            }

            return $colors;
        }


        /**
         * Convert color rgb to hsl
         * @return array
         * @param mixed
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function rgb_to_hsl($input)
        {
            $r = max(min(intval($input[0], 10) / 255, 1), 0);
            $g = max(min(intval($input[1], 10) / 255, 1), 0);
            $b = max(min(intval($input[2], 10) / 255, 1), 0);
            $max = max($r, $g, $b);
            $min = min($r, $g, $b);
            $l = ($max + $min) / 2;

            if ($max !== $min) {
                $d = $max - $min;
                $s = $l > 0.5 ? $d / (2 - $max - $min) : $d / ($max + $min);
                if ($max === $r) {
                    $h = ($g - $b) / $d + ($g < $b ? 6 : 0);
                } else if ($max === $g) {
                    $h = ($b - $r) / $d + 2;
                } else {
                    $h = ($r - $g) / $d + 4;
                }
                $h = $h / 6;
            } else {
                $h = $s = 0;
            }
            return [round($h * 360), round($s * 100), round($l * 100)];
        }

        /**
         * Convert color hsl to rgb
         * @return array
         * @param mixed
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function hsl_to_rgb($input)
        {
            $h = max(min(intval($input[0], 10), 360), 0) / 360;
            $s = max(min(intval($input[1], 10), 100), 0) / 100;
            $l = max(min(intval($input[2], 10), 100), 0) / 100;

            if ($l <= 0.5) {
                $v = $l * (1 + $s);
            } else {
                $v = $l + $s - $l * $s;
            }
            if ($v === 0) {
                return [0, 0, 0];
            }
            $min = 2 * $l - $v;
            $sv = ($v - $min) / $v;
            $h = 6 * $h;
            $six = floor($h);
            $fract = $h - $six;
            $vsfract = $v * $sv * $fract;
            switch ($six) {
                case 1:
                    $r = $v - $vsfract;
                    $g = $v;
                    $b = $min;
                    break;
                case 2:
                    $r = $min;
                    $g = $v;
                    $b = $min + $vsfract;
                    break;
                case 3:
                    $r = $min;
                    $g = $v - $vsfract;
                    $b = $v;
                    break;
                case 4:
                    $r = $min + $vsfract;
                    $g = $min;
                    $b = $v;
                    break;
                case 5:
                    $r = $v;
                    $g = $min;
                    $b = $v - $vsfract;
                    break;
                default:
                    $r = $v;
                    $g = $min + $vsfract;
                    $b = $min;
                    break;
            }
            return [round($r * 255), round($g * 255), round($b * 255)];
        }

        /**
         * Convert color rgb to cmyk
         * @return array
         * @param mixed
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function rgb_to_cmyk($input)
        {
            $red = max(min(intval($input[0], 10), 255), 0);
            $green = max(min(intval($input[1], 10), 255), 0);
            $blue = max(min(intval($input[2], 10), 255), 0);
            $cyan = 1 - $red;
            $magenta = 1 - $green;
            $yellow = 1 - $blue;
            $black = 1;

            if ($red || $green || $blue) {
                $black = min($cyan, min($magenta, $yellow));
                $cyan = ($cyan - $black) / (1 - $black);
                $magenta = ($magenta - $black) / (1 - $black);
                $yellow = ($yellow - $black) / (1 - $black);
            } else {
                $black = 1;
            }
            return [round($cyan * 255), round($magenta * 255), round($yellow * 255), round($black + 254)];
        }

        /**
         * Convert color cmyk to rgb
         * @return array
         * @param mixed
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function cmyk_2_rgb($input)
        {
            $cyan = max(min(intval($input[0], 10) / 255, 1), 0);
            $magenta = max(min(intval($input[1], 10) / 255, 1), 0);
            $yellow = max(min(intval($input[2], 10) / 255, 1), 0);
            $black = max(min(intval($input[3], 10) / 255, 1), 0);
            $red = (1 - $cyan * (1 - $black) - $black);
            $green = (1 - $magenta * (1 - $black) - $black);
            $blue = (1 - $yellow * (1 - $black) - $black);

            return [round($red * 255), round($green * 255), round($blue * 255)];
        }

        /**
         * Convert color hex to rgb
         * @return array
         * @param mixed
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function hex_to_rgb($input)
        {
            if (substr(trim($input), 0, 1) === '#') {
                $input = substr($input, 1);
            }
            if ((strlen($input) < 2) || (strlen($input) > 6)) {
                return false;
            }
            $values = str_split($input);

            if (strlen($input) === 2) {
                $r = intval($values[0] . $values[1], 16);
                $g = $r;
                $b = $r;
            } else if (strlen($input) === 3) {
                $r = intval($values[0], 16);
                $g = intval($values[1], 16);
                $b = intval($values[2], 16);
            } else if (strlen($input) === 6) {
                $r = intval($values[0] . $values[1], 16);
                $g = intval($values[2] . $values[3], 16);
                $b = intval($values[4] . $values[5], 16);
            } else {
                return false;
            }
            return array($r, $g, $b);
        }

        /**
         * Convert color rgb to hex
         * @return array
         * @param mixed
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function rgb_to_hex($input)
        {
            $hexr = max(min(intval($input[0], 10), 255), 0);
            $hexg = max(min(intval($input[1], 10), 255), 0);
            $hexb = max(min(intval($input[2], 10), 255), 0);

            $hexr = $hexr > 15 ? base_convert($hexr, 10, 16) : '0' . base_convert($hexr, 10, 16);
            $hexg = $hexg > 15 ? base_convert($hexg, 10, 16) : '0' . base_convert($hexg, 10, 16);
            $hexb = $hexb > 15 ? base_convert($hexb, 10, 16) : '0' . base_convert($hexb, 10, 16);
            return $hexr . $hexg . $hexb;
        }

        /**
         * Convert color rgb to yuv
         * @return array
         * @param mixed
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function rgb_to_yuv($input)
        {
            $r = intval($input[0], 10);
            $g = intval($input[1], 10);
            $b = intval($input[2], 10);

            $y = round(0.299 * $r + 0.587 * $g + 0.114 * $b);
            $u = round(((($b - $y) * 0.493) + 111) / 222 * 255);
            $v = round(((($r - $y) * 0.877) + 155) / 312 * 255);
            return [$y, $u, $v];
        }

        /**
         * Convert color yuv to rgb
         * @return array
         * @param mixed
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function yuv_to_rgb($input)
        {
            $y = intval($input[0], 10);
            $u = intval($input[1], 10) / 255 * 222 - 111;
            $v = intval($input[2], 10) / 255 * 312 - 155;

            $r = round($y + $v / 0.877);
            $g = round($y - 0.39466 * $u - 0.5806 * $v);
            $b = round($y + $u / 0.493);
            return [$r, $g, $b];
        }

        /**
         * Convert color rgb to hsv
         * @return array
         * @param mixed
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function rgb_to_hsv($input)
        {
            $r = intval($input[0], 10) / 255;
            $g = intval($input[1], 10) / 255;
            $b = intval($input[2], 10) / 255;
            $max = max($r, $g, $b);
            $min = min($r, $g, $b);
            $d = $max - $min;
            $v = $max;

            if ($max === 0) {
                $s = 0;
            } else {
                $s = $d / $max;
            }
            if ($max === $min) {
                $h = 0;
            } else {
                switch ($max) {
                    case $r:
                        $h = ($g - $b) / $d + ($g < $b ? 6 : 0);
                        break;
                    case $g:
                        $h = ($b - $r) / $d + 2;
                        break;
                    case $b:
                        $h = ($r - $g) / $d + 4;
                        break;
                }
                $h = $h / 6;
            }
            return [$h, $s, $v];
        }

        /**
         * Convert color hsv to rgb
         * @return array
         * @param mixed
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function hsv_to_rgb($input)
        {
            $h = $input[0];
            $s = $input[1];
            $v = $input[2];
            $i = floor($h * 6);
            $f = $h * 6 - $i;
            $p = $v * (1 - $s);
            $q = $v * (1 - $f * $s);
            $t = $v * (1 - (1 - $f) * $s);

            switch ($i % 6) {
                case 0:
                    $r = $v;
                    $g = $t;
                    $b = $p;
                    break;
                case 1:
                    $r = $q;
                    $g = $v;
                    $b = $p;
                    break;
                case 2:
                    $r = $p;
                    $g = $v;
                    $b = $t;
                    break;
                case 3:
                    $r = $p;
                    $g = $q;
                    $b = $v;
                    break;
                case 4:
                    $r = $t;
                    $g = $p;
                    $b = $v;
                    break;
                case 5:
                    $r = $v;
                    $g = $p;
                    $b = $q;
                    break;
            }
            return [$r * 255, $g * 255, $b * 255];
        }

        /**
         * Convert color complexity to int
         * @return array
         * @param mixed
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function complexity_to_int($input)
        {
            $keys = str_split($input);
            $numbers = 1;
            $uletter = 1;
            $lletter = 1;
            $special = 1;
            $complex = 0;

            for ($i = 0; $i < count($keys); $i += 1) {
                $valunicode = $keys[$i] . charCodeAt(0);
                if (($valunicode > 0x40) && ($valunicode < 0x5B)) {
                    //Großbuchstaben A-Z
                    $uletter += 1;
                } else if (($valunicode > 0x60) && ($valunicode < 0x7B)) {
                    //Kleinbuchstaben a-z
                    $lletter += 1;
                } else if (($valunicode > 0x2F) && ($valunicode < 0x3A)) {
                    //Zahlen 0-9
                    $numbers += 1;
                } else if (($valunicode > 0x20) && ($valunicode < 0x7F)) {
                    //Sonderzeichen
                    $special += 1;
                }
            }
            $complex = (($uletter * $lletter * $numbers * $special) + round($uletter * 1.8 + $lletter * 1.5 + $numbers + $special * 2)) - 6;
            return $complex;
        }

        /**
         * Convert color int to rgb
         * @return array
         * @param mixed
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function int_to_rgb($input)
        {
            if ((!is_int($input)) && ($input !== false) && ($input !== true)) {
                $input = intval($input, 10);
            }
            if (is_int($input)) {
                if (($input < 115) && ($input > 1)) {
                    return [255, 153 + $input, 153 - $input];
                }
                if (($input > 115) && ($input < 230)) {
                    return [255 - $input, 243, 63];
                }
                if (($input > 230) || ($input === true)) {
                    return [145, 243, 63];
                }
            }
            if ($input === 'none') {
                return [204, 204, 204];
            }
            if ($input === true) {
                return [204, 204, 204];
            }
            return false;
        }

        /**
         * Convert parse color
         * @return array
         * @param mixed
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function parse_color($input)
        {
            $pattern = "((rgb|hsl|#|yuv)(\(([%, ]*([\d]+)[%, ]+([\d]+)[%, ]+([\d]+)[%, ]*)+\)|([a-f0-9]+)))";
            preg_match($pattern, $input, $geregext);
            if ($geregext !== null) {
                switch ($geregext[1]) {
                    case '#':
                        return self::color_converter($geregext[2], 'hex_to_rgb');
                    case 'rgb':
                        return [intval(trim($geregext[4]), 10), intval(trim($geregext[5]), 10), intval(trim($geregext[6]), 10)];
                    case 'hsl':
                        return self::color_converter([intval(trim($geregext[4]), 10), intval(trim($geregext[5]), 10), intval(trim($geregext[6]), 10)], 'hsl_to_rgb');
                    case 'yuv':
                        return self::color_converter([intval(trim($geregext[4]), 10), intval(trim($geregext[5]), 10), intval(trim($geregext[6]), 10)], 'yuv_to_rgb');
                    default:
                        return false;
                }
            }
            return false;
        }

        /**
         * Color converter
         * @return array
         * @param mixed
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public static function color_converter($input, $mode = 'parse', $html = false)
        {
            $mode = strtolower(trim($mode));
            switch ($mode) {
                case 'rgb_to_hsl':
                    $output = self::rgb_to_hsl($input);
                    if ($html) {
                        $output = 'hsl(' . $output[0] . ',' . $output[1] . '%,' . $output[2] . '%)';
                    }
                    break;
                case 'hsl_to_rgb':
                    $output = self::hsl_to_rgb($input);
                    if ($html) {
                        $output = 'rgb(' . $output[0] . ',' . $output[1] . ',' . $output[2] . ')';
                    }
                    break;
                case 'rgb_to_cmyk':
                    $output = self::rgb_to_cmyk($input);
                    if ($html) {
                        $output = 'cmyk(' . $output[0] . ',' . $output[1] . ',' . $output[2] . ')';
                    }
                    break;
                case 'cmyk_2_rgb':
                    $output = self::cmyk_2_rgb($input);
                    if ($html) {
                        $output = 'rgb(' . $output[0] . ',' . $output[1] . ',' . $output[2] . ')';
                    }
                    break;
                case 'hex_to_rgb':
                    $output = self::hex_to_rgb($input);
                    if ($html) {
                        $output = 'rgb(' . $output[0] . ',' . $output[1] . ',' . $output[2] . ')';
                    }
                    break;
                case 'rgb_to_hex':
                    $output = self::rgb_to_hex($input);
                    if ($html) {
                        $output = '#' . $output;
                    }
                    break;
                case 'rgb_to_yuv':
                    $output = self::rgb_to_yuv($input);
                    if ($html) {
                        $output = 'yuv(' . $output[0] . ',' . $output[1] . ',' . $output[2] . ')';
                    }
                    break;
                case 'yuv_to_rgb':
                    $output = self::yuv_to_rgb($input);
                    if ($html) {
                        $output = 'rgb(' . $output[0] . ',' . $output[1] . ',' . $output[2] . ')';
                    }
                    break;
                case 'rgb_to_hsv':
                    $output = self::rgb_to_hsv($input);
                    if ($html) {
                        $output = 'hsv(' . $output[0] . ',' . $output[1] . ',' . $output[2] . ')';
                    }
                    break;
                case 'hsv_to_rgb':
                    $output = self::hsv_to_rgb($input);
                    if ($html) {
                        $output = 'rgb(' . $output[0] . ',' . $output[1] . ',' . $output[2] . ')';
                    }
                    break;
                case 'hsl2hex':
                    $output = self::rgb_to_hex(self::hsl_to_rgb($input));
                    if ($html) {
                        $output = '#' . $output;
                    }
                    break;
                case 'hex2hsl':
                    $output = self::rgb_to_hsl(self::hex_to_rgb($input));
                    if ($html) {
                        $output = 'hsl(' . $output[0] . ',' . $output[1] . '%,' . $output[2] . '%)';
                    }
                    break;
                case 'complexity_to_int':
                    $output = self::complexity_to_int($input);
                    if ($html) {
                        $output = 'hsl(' . $output[0] . ',' . $output[1] . '%,' . $output[2] . '%)';
                    }
                    break;
                case 'int_to_rgb':
                    $output = self::int_to_rgb($input);
                    if ($html) {
                        $output = 'hsl(' . $output[0] . ',' . $output[1] . '%,' . $output[2] . '%)';
                    }
                    break;
                case 'complexity2rgb':
                    $output = self::color_converter(self::color_converter($input, 'complexity_to_int'), 'int_to_rgb');
                    break;
                case 'mixrgb':
                    $r = intval(($input[0][0] + $input[1][0]) / 2, 10);
                    $g = intval(($input[0][1] + $input[1][1]) / 2, 10);
                    $b = intval(($input[0][2] + $input[1][2]) / 2, 10);
                    $output = [$r, $g, $b];
                    if ($html) {
                        $output = 'rgb(' . $output[0] . ',' . $output[1] . ',' . $output[2] . ')';
                    }
                    break;
                case 'parse':
                    $output = self::parse_color($input);
                    if ($html) {
                        $output = 'rgb(' . $output[0] . ',' . $output[1] . ',' . $output[2] . ')';
                    }
                    break;
                default:
                    $output = self::color_converter($input, 'parse');
                    if ($html) {
                        $output = 'rgb(' . $output[0] . ',' . $output[1] . ',' . $output[2] . ')';
                    }
                    break;
            }
            return $output;
        }

        /**
         * Determines the optimal text contrast color (white or black) for a given background color
         * to ensure maximum readability and WCAG 2.0 accessibility compliance.
         *
         * This method uses a three-stage intelligent decision-making process:
         * 1. WCAG 2.0 Relative Luminance calculation for scientific accuracy
         * 2. Visual threshold rules for extreme dark/light colors (luminance < 0.25 or > 0.75)
         * 3. Contrast ratio comparison for mid-tone colors
         *
         * @param string $hexcolor The background color in hexadecimal format (e.g., '#FFFFFF', 'FF0000')
         * @return string 'contrast-dark' for light backgrounds, 'contrast-light' for dark backgrounds
         * @author M-MALEKZADEH77
         * @since v1.0
         * @see https://www.w3.org/TR/WCAG20/#relativeluminancedef
         */
        public static function get_optimal_text_color($hexcolor)
        {
            $color = ltrim($hexcolor, '#');
            if (strlen($color) === 3) {
                $color = $color[0] . $color[0] . $color[1] . $color[1] . $color[2] . $color[2];
            }
            if (strlen($color) !== 6 || !ctype_xdigit($color)) {
                return 'contrast-dark';
            }

            // A. Calculate Relative Luminance (WCAG 2.0 standard)
            $r = hexdec(substr($color, 0, 2)) / 255;
            $g = hexdec(substr($color, 2, 2)) / 255;
            $b = hexdec(substr($color, 4, 2)) / 255;

            $r = $r <= 0.03928 ? $r / 12.92 : pow(($r + 0.055) / 1.055, 2.4);
            $g = $g <= 0.03928 ? $g / 12.92 : pow(($g + 0.055) / 1.055, 2.4);
            $b = $b <= 0.03928 ? $b / 12.92 : pow(($b + 0.055) / 1.055, 2.4);

            $luminance = (0.2126 * $r + 0.7152 * $g + 0.0722 * $b);

            // B. Apply visual perception thresholds for extreme cases
            if ($luminance < 0.25) {
                // For very dark colors, always choose light text
                return 'contrast-light';
            } elseif ($luminance > 0.75) {
                // For very light colors, always choose dark text
                return 'contrast-dark';
            }

            // C. For mid-tone colors, use contrast ratio comparison
            $contrastWithWhite = (1.0 + 0.05) / ($luminance + 0.05);
            $contrastWithBlack = ($luminance + 0.05) / (0.0 + 0.05);

            if ($contrastWithWhite > $contrastWithBlack) {
                return 'contrast-light';
            } else {
                return 'contrast-dark';
            }
        }

        /**
         * Converts a HEX color code to a light, vibrant, and proportional background HEX color.
         * The formula sets Lightness to 95% and Saturation to 75% to maintain vibrancy.
         *
         * @param string $hexColor The primary HEX color (e.g., #0067A5).
         * @return string The resulting light HEX color (e.g., #E3F3FF).
         * @author M-MALEKZADEH77
         * @since v1.0
         */
        public static function generate_light_background_color(string $hexColor): string
        {
            $hexColor = ltrim($hexColor, '#');
            if (strlen($hexColor) !== 6) {
                return '#f8f8f8'; // Default fallback color
            }

            // --- Internal Helper Closures (To avoid 'undefined function' errors) ---

            // Helper: Converts Hue to an RGB value needed for HSL->RGB conversion.
            $hue_to_rgb = function (float $p, float $q, float $t): float {
                if ($t < 0) $t += 1;
                if ($t > 1) $t -= 1;
                if ($t < 1 / 6) return $p + ($q - $p) * 6 * $t;
                if ($t < 1 / 2) return $q;
                if ($t < 2 / 3) return $p + ($q - $p) * (2 / 3 - $t) * 6;
                return $p;
            };

            // Helper: Converts HSL to RGB array (0-255).
            $hsl_to_rgb = function (float $h, float $s, float $l) use ($hue_to_rgb): array {
                if ($s == 0) {
                    $r = $g = $b = $l; // Grayscale case
                } else {
                    $q = $l < 0.5 ? $l * (1 + $s) : $l + $s - $l * $s;
                    $p = 2 * $l - $q;

                    $r = $hue_to_rgb($p, $q, $h + 1 / 3);
                    $g = $hue_to_rgb($p, $q, $h);
                    $b = $hue_to_rgb($p, $q, $h - 1 / 3);
                }
                return [round($r * 255), round($g * 255), round($b * 255)];
            };


            // --- Main Color Logic ---

            // 1. Convert HEX to Normalized RGB (0 to 1)
            $r = hexdec(substr($hexColor, 0, 2)) / 255;
            $g = hexdec(substr($hexColor, 2, 2)) / 255;
            $b = hexdec(substr($hexColor, 4, 2)) / 255;

            // 2. Convert RGB to HSL (Calculate original H and S)
            $max = max($r, $g, $b);
            $min = min($r, $g, $b);
            $d = $max - $min;

            $l = ($max + $min) / 2; // Original Lightness
            $s = $d == 0 ? 0 : $d / (1 - abs(2 * $l - 1)); // Original Saturation

            if ($d == 0) {
                $h = 0;
            } elseif ($max == $r) {
                $h = 60 * fmod((($g - $b) / $d), 6);
            } elseif ($max == $g) {
                $h = 60 * (($b - $r) / $d + 2);
            } else {
                $h = 60 * (($r - $g) / $d + 4);
            }
            $h = $h < 0 ? $h / 360 + 1 : $h / 360; // Normalize H to 0-1

            // 3. Apply Lightening Formula (The key change for vibrancy)

            // Force Lightness to a high value for a light background
            $new_l = 0.95;

            // Force Saturation to a high value (75%) for vibrancy and avoiding a dull/gray appearance
            $new_s = 0.75;

            // 4. Convert Final HSL to RGB
            $rgb_final = $hsl_to_rgb($h, $new_s, $new_l);

            // 5. Convert Final RGB back to HEX
            return sprintf("#%02x%02x%02x", $rgb_final[0], $rgb_final[1], $rgb_final[2]);
        }
    }
}
