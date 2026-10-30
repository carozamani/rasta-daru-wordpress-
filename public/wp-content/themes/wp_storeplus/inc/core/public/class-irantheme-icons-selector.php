<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;

/**
 * The Storeplus Theme Settings class
 * A Customizable Class for admin nav menu page (Appearance > Menus)
 * 
 * @package Wordpress
 * @subpackage Storeplus
 * @since v1.0
 * 
 * This class is a custom settings theme for admin page.
 */
if (!class_exists('Irantheme_Icons_Selector')) {
    class Irantheme_Icons_Selector
    {
        // Initialization
        public function __construct()
        {
            // Check admin page and accessory
            if (is_admin()) {
                add_action('in_admin_footer', array($this, 'is_nav_menu'));
            }
        }

        /**
         * Check page is nav menu
         * @return bool
         * @param void
         */
        public function is_nav_menu()
        {
            // Get current url
            $current_url = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
            // Get current post type
            $post_type = isset($_GET['post']) ? get_post_type($_GET['post']) : '';
            // Check nav-menus.php or other page (In admin)
            if (str_contains($current_url, 'nav-menus')) {
                self::nav_menu_icons_elements(true);
            } else if (str_contains($current_url, Irantheme_Settings_Options::get_menu_slug()) || str_contains($current_url, Irantheme_Page_Builder::get_menu_slug()) || $post_type == 'service' || $post_type == 'irantheme_author') {
                self::nav_menu_icons_elements(false);
            }
        }

        /**
         * Nav menu icons elements
         * @return array
         * @param void
         */
        public static function icons_info()
        {
            return array(
                array(
                    "icon" => "fa-light fa-0",
                    "keywords" => "0"
                ),
                array(
                    "icon" => "fa-light fa-1",
                    "keywords" => "1"
                ),
                array(
                    "icon" => "fa-light fa-2",
                    "keywords" => "2"
                ),
                array(
                    "icon" => "fa-light fa-3",
                    "keywords" => "3"
                ),
                array(
                    "icon" => "fa-light fa-4",
                    "keywords" => "4"
                ),
                array(
                    "icon" => "fa-light fa-5",
                    "keywords" => "5"
                ),
                array(
                    "icon" => "fa-light fa-6",
                    "keywords" => "6"
                ),
                array(
                    "icon" => "fa-light fa-7",
                    "keywords" => "7"
                ),
                array(
                    "icon" => "fa-light fa-8",
                    "keywords" => "8"
                ),
                array(
                    "icon" => "fa-light fa-9",
                    "keywords" => "9"
                ),
                array(
                    "icon" => "fa-light fa-text-size",
                    "keywords" => "text,size,متن,نوشته,فونت,font"
                ),
                array(
                    "icon" => "fa-light fa-text",
                    "keywords" => "text,size,متن,نوشته,فونت,font"
                ),
                array(
                    "icon" => "fa-light fa-font",
                    "keywords" => "text,size,متن,نوشته,فونت,font"
                ),
                array(
                    "icon" => "fa-light fa-font-case",
                    "keywords" => "text,size,متن,نوشته,فونت,font"
                ),
                array(
                    "icon" => "fa-light fa-input-text",
                    "keywords" => "text,size,متن,نوشته,فونت,font"
                ),
                array(
                    "icon" => "fa-light fa-align-center",
                    "keywords" => "text,size,متن,نوشته,فونت,font"
                ),
                array(
                    "icon" => "fa-light fa-align-left",
                    "keywords" => "text,size,متن,نوشته,فونت,font"
                ),
                array(
                    "icon" => "fa-light fa-align-right",
                    "keywords" => "text,size,متن,نوشته,فونت,font"
                ),
                array(
                    "icon" => "fa-light fa-align-justify",
                    "keywords" => "text,size,متن,نوشته,فونت,font"
                ),
                array(
                    "icon" => "fa-light fa-infinity",
                    "keywords" => "infinity,بی نهایت"
                ),
                array(
                    'keywords' => "خانه,house,home",
                    'icon' => 'fa-light fa-house',
                ),
                array(
                    'keywords' => "ساختمان,فروشگاه,shop,building",
                    'icon' => 'fa-light fa-shop',
                ),
                array(
                    'keywords' => "قانون,دادگاه,خانه,ساختمان,نقشه,building,court,law,landmark",
                    'icon' => 'fa-light fa-landmark',
                ),
                array(
                    'keywords' => "جستجو,اسکن,بررسی,search,zoom,magnifying,magnifying-glass,magnifying glass,glass",
                    'icon' => 'fa-light fa-magnifying-glass',
                ),
                array(
                    'keywords' => "شخص,انسان,person",
                    'icon' => 'fa-light fa-person',
                ),
                array(
                    'keywords' => "کاربر,یوزر,user",
                    'icon' => 'fa-light fa-user',
                ),
                array(
                    'keywords' => "کاربر,یوزر,user",
                    'icon' => 'fa-light fa-users',
                ),
                array(
                    'keywords' => "آهنگ,هدفون,موزیک,پشتیبانی,headphones,headset,music,sound,support,کاربر,یوزر,user",
                    'icon' => 'fa-light fa-user-headset',
                ),
                array(
                    'keywords' => "کاربر,یوزر,user",
                    'icon' => 'fa-light fa-user-tie',
                ),
                array(
                    'keywords' => "کاربر,یوزر,user,chef,آشپز,رستوران",
                    'icon' => 'fa-light fa-user-chef',
                ),
                array(
                    'keywords' => "کاربر,یوزر,user,tag",
                    'icon' => 'fa-light fa-user-tag',
                ),
                array(
                    'keywords' => "کاربر,یوزر,user,shield,سپر,محافظ",
                    'icon' => 'fa-light fa-user-shield',
                ),
                array(
                    'keywords' => "خاموش,power,off",
                    'icon' => 'fa-light fa-power-off',
                ),
                array(
                    'keywords' => "نقطه,سه نقطه,more,ellipsis",
                    'icon' => 'fa-light fa-ellipsis-vertical',
                ),
                array(
                    'keywords' => "نقطه,سه نقطه,more,ellipsis",
                    'icon' => 'fa-light fa-ellipsis',
                ),
                array(
                    'keywords' => "تیک,تایید,قبول,check,agree,apply",
                    'icon' => 'fa-light fa-check',
                ),
                array(
                    'keywords' => "تیک,تایید,قبول,check,agree,apply",
                    'icon' => 'fa-light fa-circle-check',
                ),
                array(
                    'keywords' => "تیک,تایید,قبول,check,agree,apply",
                    'icon' => 'fa-light fa-square-check',
                ),
                array(
                    'keywords' => "تیک,تایید,قبول,check,agree,apply",
                    'icon' => 'fa-light fa-check-to-slot',
                ),
                array(
                    'keywords' => "دانلود,بارگیری,دریافت,download",
                    'icon' => 'fa-light fa-download',
                ),
                array(
                    'keywords' => "عکس,تصویر,image,picture,thumb,photo",
                    'icon' => 'fa-light fa-image',
                ),
                array(
                    'keywords' => "فیلم,ویدیو,film,video",
                    'icon' => 'fa-light fa-film',
                ),
                array(
                    'keywords' => "فیلم,ویدیو,film,video,play,clapperboard",
                    'icon' => 'fa-light fa-clapperboard-play',
                ),
                array(
                    'keywords' => "فیلم,ویدیو,film,video,عکس,تصویر,image,picture,thumb,photo",
                    'icon' => 'fa-light fa-photo-film',
                ),
                array(
                    'keywords' => "فیلم,ویدیو,film,video,عکس,تصویر,image,picture,thumb,photo,music,موزیک,آهتگ",
                    'icon' => 'fa-light fa-photo-film-music',
                ),
                array(
                    'keywords' => "تلفن,تماس,ارتباط,phone,call,calling",
                    'icon' => 'fa-light fa-phone',
                ),
                array(
                    'keywords' => "منو,نوار,menu,bar,bars",
                    'icon' => 'fa-light fa-bars',
                ),
                array(
                    'keywords' => "پاکت,نامه,letter,envelope,pocket,email,ایمیل,mail",
                    'icon' => 'fa-light fa-envelope',
                ),
                array(
                    'keywords' => "at sign,اتساین,mail,ایمیل,email",
                    'icon' => 'fa-light fa-at',
                ),
                array(
                    'keywords' => "امتیاز,ستاره,star,rank,rate,favorite",
                    'icon' => 'fa-light fa-star',
                ),
                array(
                    'keywords' => "امتیاز,ستاره,star,rank,rate,favorite",
                    'icon' => 'fa-light fa-star-sharp',
                ),
                array(
                    'keywords' => "امتیاز,ستاره,star,rank,rate,favorite",
                    'icon' => 'fa-light fa-stars',
                ),
                array(
                    'keywords' => "امتیاز,ستاره,star,rank,rate,favorite",
                    'icon' => 'fa-light fa-star-christmas',
                ),
                array(
                    'keywords' => "امتیاز,ستاره,star,rank,rate,favorite",
                    'icon' => 'fa-light fa-circle-star',
                ),
                array(
                    'keywords' => "امتیاز,ستاره,star,rank,rate,favorite",
                    'icon' => 'fa-light fa-square-star',
                ),
                array(
                    'keywords' => "امتیاز,ستاره,star,rank,rate,favorite,animation,motion,انیمیشن",
                    'icon' => 'fa-light fa-star-shooting',
                ),
                array(
                    'keywords' => "امتیاز,ستاره,star,rank,rate,favorite,animation,motion,انیمیشن",
                    'icon' => 'fa-light fa-sparkles',
                ),
                array(
                    'keywords' => "امتیاز,ستاره,star,rank,rate,favorite,animation,motion,انیمیشن",
                    'icon' => 'fa-light fa-sparkle',
                ),
                array(
                    'keywords' => "برق,سریع,رعد,bolt,Lightning,fast",
                    'icon' => 'fa-light fa-bolt-lightning',
                ),
                array(
                    'keywords' => "برق,سریع,رعد,bolt,Lightning,fast",
                    'icon' => 'fa-light fa-bolt',
                ),
                array(
                    'keywords' => "برق,سریع,رعد,bolt,Lightning,fast",
                    'icon' => 'fa-light fa-square-bolt',
                ),
                array(
                    'keywords' => "برق,سریع,رعد,bolt,Lightning,fast",
                    'icon' => 'fa-light fa-circle-bolt',
                ),
                array(
                    'keywords' => "رتبه,نمودار,rank,star,rate",
                    'icon' => 'fa-light fa-ranking-star',
                ),
                array(
                    'keywords' => "مکان,موقعیت,مکان نما,لوکیشن,نقشه,location,map,position,dot",
                    'icon' => 'fa-light fa-location-dot',
                ),
                array(
                    'keywords' => "مکان,موقعیت,مکان نما,لوکیشن,نقشه,location,map,position,dot",
                    'icon' => 'fa-light fa-map-location-dot',
                ),
                array(
                    'keywords' => "مکان,موقعیت,مکان نما,لوکیشن,نقشه,location,map,position,dot",
                    'icon' => 'fa-light fa-map',
                ),
                array(
                    'keywords' => "مکان,موقعیت,مکان نما,لوکیشن,نقشه,location,map,position,dot,pin",
                    'icon' => 'fa-light fa-map-pin',
                ),
                array(
                    'keywords' => "مکان,موقعیت,مکان نما,لوکیشن,نقشه,location,map,position,dot,pin,gps",
                    'icon' => 'fa-light fa-location-crosshairs',
                ),
                array(
                    'keywords' => "خط کش,سایز,اندازه,size,ruler",
                    'icon' => 'fa-light fa-ruler',
                ),
                array(
                    'keywords' => "خط کش,سایز,اندازه,size,ruler",
                    'icon' => 'fa-light fa-ruler-vertical',
                ),
                array(
                    'keywords' => "خط کش,سایز,اندازه,size,ruler",
                    'icon' => 'fa-light fa-ruler-triangle',
                ),
                array(
                    'keywords' => "موسیقی,موزیک,آهنگ,music,play,sound",
                    'icon' => 'fa-light fa-music',
                ),
                array(
                    'keywords' => "موسیقی,موزیک,آهنگ,music,play,sound,volume,ولووم",
                    'icon' => 'fa-light fa-volume-xmark',
                ),
                array(
                    'keywords' => "موسیقی,موزیک,آهنگ,music,play,sound,volume,ولووم",
                    'icon' => 'fa-light fa-volume-slash',
                ),
                array(
                    'keywords' => "موسیقی,موزیک,آهنگ,music,play,sound,volume,ولووم",
                    'icon' => 'fa-light fa-volume-off',
                ),
                array(
                    'keywords' => "موسیقی,موزیک,آهنگ,music,play,sound,volume,ولووم",
                    'icon' => 'fa-light fa-volume-low',
                ),
                array(
                    'keywords' => "موسیقی,موزیک,آهنگ,music,play,sound,volume,ولووم",
                    'icon' => 'fa-light fa-volume',
                ),
                array(
                    'keywords' => "موسیقی,موزیک,آهنگ,music,play,sound,volume,ولووم",
                    'icon' => 'fa-light fa-volume-high',
                ),
                array(
                    'keywords' => "خاص,فانتزی,جادو,جادویی,sparkles,fantastic,magic,magical,wand",
                    'icon' => 'fa-light fa-wand-magic-sparkles',
                ),
                array(
                    'keywords' => "امتیاز,پسندیدن,سلامت,قلب,لایک,heart,like,favorite,rate,health",
                    'icon' => 'fa-light fa-heart',
                ),
                array(
                    'keywords' => "امتیاز,پسندیدن,سلامت,قلب,لایک,heart,medical,health",
                    'icon' => 'fa-light fa-heart-pulse',
                ),
                array(
                    'keywords' => "دکتر,سلامت,پزشکی,گوشی پزشکی,stethoscope,doctor,Health,medical",
                    'icon' => 'fa-light fa-stethoscope',
                ),
                array(
                    'keywords' => "دکتر,سلامت,پزشکی,گوشی پزشکی,waveform,doctor,Health,medical",
                    'icon' => 'fa-light fa-monitor-waveform',
                ),
                array(
                    'keywords' => "دکتر,سلامت,پزشکی,گوشی پزشکی,suitcase,doctor,Health,medical",
                    'icon' => 'fa-light fa-suitcase-medical',
                ),
                array(
                    'keywords' => "جهت,جهت نما,فلش,نشانه,pointer,arrow,angle",
                    'icon' => 'fa-light fa-arrow-pointer',
                ),
                array(
                    'keywords' => "جهت,جهت نما,فلش,راست,arrow,angle,right",
                    'icon' => 'fa-light fa-arrow-right',
                ),
                array(
                    'keywords' => "جهت,جهت نما,فلش,چپ,arrow,angle,left",
                    'icon' => 'fa-light fa-arrow-left',
                ),
                array(
                    'keywords' => "جهت,جهت نما,فلش,پایین,arrow,angle,down",
                    'icon' => 'fa-light fa-arrow-down',
                ),
                array(
                    'keywords' => "جهت,جهت نما,فلش,بالا,arrow,angle,up",
                    'icon' => 'fa-light fa-arrow-up',
                ),
                array(
                    'keywords' => "جهت,جهت نما,فلش,move,انتقال,arrow,angle,up",
                    'icon' => 'fa-light fa-arrows-up-down-left-right',
                ),
                array(
                    'keywords' => "جهت,جهت نما,فلش,پایین,arrow,angle,right",
                    'icon' => 'fa-light fa-arrow-down-right',
                ),
                array(
                    'keywords' => "جهت,جهت نما,فلش,پایین,arrow,angle,left",
                    'icon' => 'fa-light fa-arrow-down-left',
                ),
                array(
                    'keywords' => "جهت,جهت نما,فلش,بالا,arrow,angle,down",
                    'icon' => 'fa-light fa-arrow-up-right',
                ),
                array(
                    'keywords' => "جهت,جهت نما,فلش,بالا,arrow,angle,up",
                    'icon' => 'fa-light fa-arrow-up-left',
                ),
                array(
                    'keywords' => "جهت,جهت نما,فلش,راست,arrow,angle,right",
                    'icon' => 'fa-light fa-angle-right',
                ),
                array(
                    'keywords' => "جهت,جهت نما,فلش,چپ,arrow,angle,left",
                    'icon' => 'fa-light fa-angle-left',
                ),
                array(
                    'keywords' => "جهت,جهت نما,فلش,پایین,arrow,angle,down",
                    'icon' => 'fa-light fa-angle-down',
                ),
                array(
                    'keywords' => "جهت,جهت نما,فلش,بالا,arrow,angle,up",
                    'icon' => 'fa-light fa-angle-up',
                ),
                array(
                    'keywords' => "جهت,جهت نما,فلش,پایین,دانلود,arrow,angle,download,down",
                    'icon' => 'fa-light fa-arrow-down-to-line',
                ),
                array(
                    'keywords' => "جهت,جهت نما,فلش,پایین,دانلود,arrow,angle,download,down",
                    'icon' => 'fa-light fa-arrow-down-to-bracket',
                ),
                array(
                    'keywords' => "جهت,چرخش,بازگشت,راست,arrow,rotate,right",
                    'icon' => 'fa-light fa-rotate-right',
                ),
                array(
                    'keywords' => "جهت,چرخش,بازگشت,چپ,arrow,rotate,left",
                    'icon' => 'fa-light fa-rotate-left',
                ),
                array(
                    'keywords' => "جهت,چرخش,بازگشت,arrow,rotate",
                    'icon' => 'fa-light fa-rotate',
                ),
                array(
                    'keywords' => "دستشویی,wc",
                    'icon' => 'fa-light fa-poo',
                ),
                array(
                    'keywords' => "براکت,آکولاد,curly,brackets",
                    'icon' => 'fa-light fa-brackets-curly',
                ),
                array(
                    'keywords' => "حذف,بستن,ایکس,ضرب,ضربدر,x,xmark,close,delete",
                    'icon' => 'fa-light fa-xmark',
                ),
                array(
                    'keywords' => "فضای ابری,ابر,ابری,cloud,space,storage,sky",
                    'icon' => 'fa-light fa-cloud',
                ),
                array(
                    'keywords' => "حباب,نقل قول,کامنت,گفتگو,نظر,comment,conversation,bubble",
                    'icon' => 'fa-light fa-comment',
                ),
                array(
                    'keywords' => "حباب,نقل قول,اجتماعی,شبکه اجتماعی,کامنت,گفتگو,نظر,comment,conversation,bubble,social,social media",
                    'icon' => 'fa-light fa-comment-lines',
                ),
                array(
                    'keywords' => "حباب,نقل قول,اجتماعی,شبکه اجتماعی,کامنت,گفتگو,نظر,comment,conversation,bubble,social,social media",
                    'icon' => 'fa-light fa-comment-lines',
                ),
                array(
                    'keywords' => "حباب,نقل قول,اجتماعی,شبکه اجتماعی,کامنت,گفتگو,نظر,comment,conversation,bubble,social,social media,message,پیام",
                    'icon' => 'fa-light fa-message',
                ),
                array(
                    'keywords' => "حباب,نقل قول,اجتماعی,شبکه اجتماعی,کامنت,گفتگو,نظر,comment,conversation,bubble,social,social media,message,پیام",
                    'icon' => 'fa-light fa-messages',
                ),
                array(
                    'keywords' => "حباب,نقل قول,اجتماعی,شبکه اجتماعی,کامنت,گفتگو,نظر,comment,conversation,bubble,social,social media,message,پیام",
                    'icon' => 'fa-light fa-messages-question',
                ),
                array(
                    'keywords' => "حباب,نقل قول,اجتماعی,شبکه اجتماعی,کامنت,گفتگو,نظر,comment,conversation,bubble,social,social media,message,پیام",
                    'icon' => 'fa-light fa-message-lines',
                ),
                array(
                    'keywords' => "حباب,نقل قول,اجتماعی,شبکه اجتماعی,کامنت,گفتگو,نظر,comment,conversation,bubble,social,social media,message,پیام,text",
                    'icon' => 'fa-light fa-message-text',
                ),
                array(
                    'keywords' => "نویسنده,مداد,روان نویس,خودکار,نوشته,نوشتن,مطلب,متن,pen,text,post,pencil,author",
                    'icon' => 'fa-light fa-pen-nib',
                ),
                array(
                    'keywords' => "نویسنده,مداد,خودکار,نوشته,نوشتن,مطلب,متن,pen,text,post,pencil,author",
                    'icon' => 'fa-light fa-pen',
                ),
                array(
                    'keywords' => "نویسنده,مداد,خودکار,نوشته,نوشتن,مطلب,متن,pen,text,post,pencil,author",
                    'icon' => 'fa-light fa-pencil',
                ),
                array(
                    'keywords' => "نویسنده,قلمو,مداد,روان نویس,خودکار,نوشته,نوشتن,مطلب,متن,pen,text,post,pencil,author,brush",
                    'icon' => 'fa-light fa-paintbrush-pencil',
                ),
                array(
                    'keywords' => "نویسنده,خط کش,مداد,روان نویس,خودکار,نوشته,نوشتن,مطلب,متن,pen,text,post,pencil,author,ruler",
                    'icon' => 'fa-light fa-pen-ruler',
                ),
                array(
                    'keywords' => "زمان,روز,تقویم,تاریخ,calender,day,date,time",
                    'icon' => 'fa-light fa-calendar-days',
                ),
                array(
                    'keywords' => "زمان,روز,تقویم,تاریخ,calender,day,date,time,clock",
                    'icon' => 'fa-light fa-calendar-clock',
                ),
                array(
                    'keywords' => "زمان,روز,تقویم,تاریخ,calender,day,date,time",
                    'icon' => 'fa-light fa-calendar',
                ),
                array(
                    'keywords' => "زمان,روز,تقویم,تاریخ,calender,day,date,time",
                    'icon' => 'fa-light fa-calendar-lines',
                ),
                array(
                    'keywords' => "زمان,روز,تقویم,تاریخ,calender,day,date,time,heart",
                    'icon' => 'fa-light fa-calendar-heart',
                ),
                array(
                    'keywords' => "زمان,روز,تقویم,تاریخ,calender,day,date,time",
                    'icon' => 'fa-light fa-calendars',
                ),
                array(
                    'keywords' => "محافظت,امنیت,حفاظت,محافظ,سپر,shield,security,halved",
                    'icon' => 'fa-light fa-shield-halved',
                ),
                array(
                    'keywords' => "محافظت,امنیت,حفاظت,محافظ,سپر,shield,security,halved",
                    'icon' => 'fa-light fa-shield-exclamation',
                ),
                array(
                    'keywords' => "محافظت,امنیت,حفاظت,محافظ,سپر,shield,security,keyhole",
                    'icon' => 'fa-light fa-shield-keyhole',
                ),
                array(
                    'keywords' => "محافظت,امنیت,حفاظت,محافظ,سپر,shield,security,check",
                    'icon' => 'fa-light fa-shield-check',
                ),
                array(
                    'keywords' => "کاغذ,پرونده,صفحه,فایل,برگه,file,page,paper,case,scroll",
                    'icon' => 'fa-light fa-scroll',
                ),
                array(
                    'keywords' => "کاغذ,پرونده,صفحه,فایل,برگه,file,page,paper,case",
                    'icon' => 'fa-light fa-file',
                ),
                array(
                    'keywords' => "کاغذ,پرونده,صفحه,فایل,برگه,file,page,paper,case,import",
                    'icon' => 'fa-light fa-file-import',
                ),
                array(
                    'keywords' => "کاغذ,پرونده,صفحه,فایل,برگه,file,page,paper,case,export",
                    'icon' => 'fa-light fa-file-export',
                ),
                array(
                    'keywords' => "کاغذ,پرونده,صفحه,فایل,برگه,file,page,paper,case,certificate,import,مجوز",
                    'icon' => 'fa-light fa-file-certificate',
                ),
                array(
                    'keywords' => "پی دی اف,فایل,file,pdf",
                    'icon' => 'fa-light fa-file-pdf',
                ),
                array(
                    'keywords' => "ورد,آفیس,فایل,file,office,doc",
                    'icon' => 'fa-light fa-file-doc',
                ),
                array(
                    'keywords' => "ورد,آفیس,فایل,file,office,doc",
                    'icon' => 'fa-light fa-file-word',
                ),
                array(
                    'keywords' => "زیپ,فشرده,فایل,file,zip",
                    'icon' => 'fa-light fa-file-zip',
                ),
                array(
                    'keywords' => "اکسل,آفیس,فایل,file,office,csv",
                    'icon' => 'fa-light fa-file-csv',
                ),
                array(
                    'keywords' => "اکسل,آفیس,فایل,file,office,csv",
                    'icon' => 'fa-light fa-file-excel',
                ),
                array(
                    'keywords' => "محتوا,فایل,file,contract",
                    'icon' => 'fa-light fa-file-contract',
                ),
                array(
                    'keywords' => "عکس,تصویر,فایل,file,image",
                    'icon' => 'fa-light fa-file-image',
                ),
                array(
                    'keywords' => "کد,فایل,file,code",
                    'icon' => 'fa-light fa-file-code',
                ),
                array(
                    'keywords' => "فیلم,فایل,file,video",
                    'icon' => 'fa-light fa-file-video',
                ),
                array(
                    'keywords' => "موزیک,فایل,file,music",
                    'icon' => 'fa-light fa-file-music',
                ),
                array(
                    'keywords' => "خطا,فایل,file,exclamation",
                    'icon' => 'fa-light fa-file-exclamation',
                ),
                array(
                    'keywords' => "زنگ,صدا,bell,sound",
                    'icon' => 'fa-light fa-bell',
                ),
                array(
                    'keywords' => "فروشگاه,سبد خرید,خرید,سبد,shopping,basket,buy,cart,shop",
                    'icon' => 'fa-light fa-cart-shopping',
                ),
                array(
                    'keywords' => "فروشگاه,سبد خرید,خرید,سبد,shopping,basket,buy,cart,shop",
                    'icon' => 'fa-light fa-basket-shopping',
                ),
                array(
                    'keywords' => "خرید,سبد,کیف,shopping,bag,buy",
                    'icon' => 'fa-light fa-bag-shopping',
                ),
                array(
                    'keywords' => "فروشگاه,سبد خرید,خرید,سبد,shopping,basket,buy,cart,shop,add",
                    'icon' => 'fa-light fa-cart-plus',
                ),
                array(
                    'keywords' => "متن,نوشتن,نوشته,تخته,کاغذ,حافظه,clipboard,board,storage,paper,page,text",
                    'icon' => 'fa-light fa-clipboard',
                ),
                array(
                    'keywords' => "قیف,فیلتر,دسته بندی,sort,filter",
                    'icon' => 'fa-light fa-filter',
                ),
                array(
                    'keywords' => "دوشاخ,پلاگ,پلاگین,plug",
                    'icon' => 'fa-light fa-plug',
                ),
                array(
                    'keywords' => "مکان,راهنما,اینفو,دایره,اطلاعات,info,data,Guide,circle",
                    'icon' => 'fa-light fa-circle-info',
                ),
                array(
                    'keywords' => "جرقه,برق,رعد,bolt",
                    'icon' => 'fa-light fa-bolt',
                ),
                array(
                    'keywords' => "چتر,umbrella",
                    'icon' => 'fa-light fa-umbrella',
                ),
                array(
                    'keywords' => "وضعیت,روشن,toggle,on,switch",
                    'icon' => 'fa-light fa-toggle-on',
                ),
                array(
                    'keywords' => "وضعیت,خاموش,toggle,off,switch",
                    'icon' => 'fa-light fa-toggle-off',
                ),
                array(
                    'keywords' => "حیریه,خیر,هدیه,کمک,جعبه,box,gift,help",
                    'icon' => 'fa-light fa-gift',
                ),
                array(
                    'keywords' => "جعبه,box",
                    'icon' => 'fa-light fa-box',
                ),
                array(
                    'keywords' => "جعبه,box",
                    'icon' => 'fa-light fa-box-open',
                ),
                array(
                    'keywords' => "لیست,فهرست,list,index",
                    'icon' => 'fa-light fa-list',
                ),
                array(
                    'keywords' => "کانفیگ,تنظیمات,چرخ دنده,gear,settings,config",
                    'icon' => 'fa-light fa-gear',
                ),
                array(
                    'keywords' => "کانفیگ,تنظیمات,چرخ دنده,gear,settings,config",
                    'icon' => 'fa-light fa-gear-complex',
                ),
                array(
                    'keywords' => "کانفیگ,تنظیمات,چرخ دنده,gear,settings,config",
                    'icon' => 'fa-light fa-gears',
                ),
                array(
                    'keywords' => "زباله,اشغال,حذف,سطل,trash,delete,recycle",
                    'icon' => 'fa-light fa-trash',
                ),
                array(
                    'keywords' => "رمز عبور,قفل,امنیت,lock,security,password",
                    'icon' => 'fa-light fa-lock',
                ),
                array(
                    'keywords' => "رمز عبور,قفل,امنیت,lock,security,password",
                    'icon' => 'fa-light fa-lock-open',
                ),
                array(
                    'keywords' => "آهنگ,هدفون,موزیک,headphones,music,sound",
                    'icon' => 'fa-light fa-headphones',
                ),
                array(
                    'keywords' => "آهنگ,هدفون,موزیک,پشتیبانی,headphones,headset,music,sound,support",
                    'icon' => 'fa-light fa-headset',
                ),
                array(
                    'keywords' => "بارکد,barcode",
                    'icon' => 'fa-light fa-barcode',
                ),
                array(
                    'keywords' => "بارکد,barcode,scan,اسکن",
                    'icon' => 'fa-light fa-barcode-scan',
                ),
                array(
                    'keywords' => "برچسب,تگ,نشان,tag",
                    'icon' => 'fa-light fa-tag',
                ),
                array(
                    'keywords' => "کتاب,دفتر,book",
                    'icon' => 'fa-light fa-book',
                ),
                array(
                    'keywords' => "کتابخانه,کتاب,books,book",
                    'icon' => 'fa-light fa-books',
                ),
                array(
                    'keywords' => "نشان,برگزیده,bookmark,select",
                    'icon' => 'fa-light fa-bookmark',
                ),
                array(
                    'keywords' => "چاپ,پرینت,print",
                    'icon' => 'fa-light fa-print',
                ),
                array(
                    'keywords' => "عکاس,تصویر برداری,تصویر,عکاسی,عکس,دوربین,لنز,camera,image,picture,photo,Photography",
                    'icon' => 'fa-light fa-camera',
                ),
                array(
                    'keywords' => "عکاس,تصویر برداری,تصویر,عکاسی,عکس,دوربین,لنز,camera,image,picture,photo,Photography",
                    'icon' => 'fa-light fa-camera-retro',
                ),
                array(
                    'keywords' => "دوربین,ویدیو,فیلم,video,film,camera",
                    'icon' => 'fa-light fa-video',
                ),
                array(
                    'keywords' => "فونت,حروف,متن,حرف,font",
                    'icon' => 'fa-light fa-font',
                ),
                array(
                    'keywords' => "خودکار,مطلب,نویسنده,نوشتن,مربع,مداد,pen,square,post,author",
                    'icon' => 'fa-light fa-pen-to-square',
                ),
                array(
                    'keywords' => "هدایت,ارسال,اشتراک گذاری,مربع,square,اشتراک,share,send,forward",
                    'icon' => 'fa-light fa-share-from-square',
                ),
                array(
                    'keywords' => "افزودن,به اضافه,جمع,مثبت,علاوه,plus,add",
                    'icon' => 'fa-light fa-plus',
                ),
                array(
                    'keywords' => "حذف,کم,کم کردن,منها,منفی,بستن,minus,negative,close,delete",
                    'icon' => 'fa-light fa-minus',
                ),
                array(
                    'keywords' => "هدایت,ارسال,اشتراک گذاری,بازگشت,اشتراک,share,send,forward",
                    'icon' => 'fa-light fa-share',
                ),
                array(
                    'keywords' => "هدایت,ارسال,اشتراک گذاری,بازگشت,اشتراک,share,send,forward",
                    'icon' => 'fa-light fa-share-all',
                ),
                array(
                    'keywords' => "ارسال,اشتراک گذاری,اشتراک,share,send",
                    'icon' => 'fa-light fa-share-nodes',
                ),
                array(
                    'keywords' => "تذکر,خطر,اخطار,دایره,خطا,circle,circle,exclamation",
                    'icon' => 'fa-light fa-circle-exclamation',
                ),
                array(
                    'keywords' => "نمایش,چشم,مشاهده,eye,show",
                    'icon' => 'fa-light fa-eye',
                ),
                array(
                    'keywords' => "نمایش,چشم,مشاهده,eye,show",
                    'icon' => 'fa-light fa-eye-slash',
                ),
                array(
                    'keywords' => "عینک,چشم,eye,glasses",
                    'icon' => 'fa-light fa-glasses',
                ),
                array(
                    'keywords' => "عینک,چشم,eye,glasses",
                    'icon' => 'fa-light fa-sunglasses',
                ),
                array(
                    'keywords' => "هواپیما,plane",
                    'icon' => 'fa-light fa-plane',
                ),
                array(
                    'keywords' => "هواپیما,plane",
                    'icon' => 'fa-light fa-plane-departure',
                ),
                array(
                    'keywords' => "مسافرت,حمل و نقل,قطار,transfer,train",
                    'icon' => 'fa-light fa-train',
                ),
                array(
                    'keywords' => "مسافرت,حمل و نقل,اتوبوس,transfer,bus",
                    'icon' => 'fa-light fa-bus',
                ),
                array(
                    'keywords' => "مسافرت,حمل و نقل,کشتی,قایق,transfer,ship",
                    'icon' => 'fa-light fa-ship',
                ),
                array(
                    'keywords' => "تایر,چرخه,چرخ,doughnut,tire,cycle,wheel",
                    'icon' => 'fa-light fa-tire',
                ),
                array(
                    'keywords' => "تایر,چرخه,چرخ,doughnut,tire,cycle,wheel",
                    'icon' => 'fa-light fa-coin-vertical',
                ),
                array(
                    'keywords' => "تایر,خالی,پنچر,flat,tire",
                    'icon' => 'fa-light fa-tire-flat',
                ),
                array(
                    'keywords' => "تاکسی,حمل و نقل,مسافرت,ماشین,خودرو,taxi,transfer",
                    'icon' => 'fa-light fa-taxi',
                ),
                array(
                    'keywords' => "حمل و نقل,حمل,مسافرت,ماشین,خودرو,car,transfer",
                    'icon' => 'fa-light fa-car',
                ),
                array(
                    'keywords' => "حمل و نقل,حمل,ماشین,خودرو,car,transfer",
                    'icon' => 'fa-light fa-car-side',
                ),
                array(
                    'keywords' => "ارسال,بار,حمل,ماشین,کامیون,حمل و نقل,پشتیبانی,truck,fast,send,logistic,support,car,delivery",
                    'icon' => 'fa-light fa-truck-fast',
                ),
                array(
                    'keywords' => "ارسال,بار,حمل,ماشین,کامیون,حمل و نقل,پشتیبانی,truck,fast,send,logistic,support,car,delivery",
                    'icon' => 'fa-light fa-truck',
                ),
                array(
                    'keywords' => "آمبولانس,اورژانس,پزشکی,سلامت,truck,medical,emergency,health",
                    'icon' => 'fa-light fa-truck-medical',
                ),
                array(
                    'keywords' => "اورژانس,اضطراری,emergency,on",
                    'icon' => 'fa-light fa-light-emergency-on',
                ),
                array(
                    'keywords' => "نقاشی,رنگ,طراحی,paint,brush",
                    'icon' => 'fa-light fa-paintbrush',
                ),
                array(
                    'keywords' => "نقاشی,رنگ,طراحی,paint,brush,hammer",
                    'icon' => 'fa-light fa-hammer-brush',
                ),
                array(
                    'keywords' => "جاروب,تمیزکاری,نظافت,broom,cleaning",
                    'icon' => 'fa-light fa-broom-wide',
                ),
                array(
                    'keywords' => "جاروب,تمیزکاری,نظافت,broom,cleaning",
                    'icon' => 'fa-light fa-broom',
                ),
                array(
                    'keywords' => "ظرف پلاستیکی,زباله,اشغال,حذف,سطل,trash,delete,recycle",
                    'icon' => 'fa-light fa-bin-bottles',
                ),
                array(
                    'keywords' => "ظرف پلاستیکی,زباله,اشغال,حذف,سطل,trash,delete,recycle",
                    'icon' => 'fa-light fa-plate-utensils',
                ),
                array(
                    'keywords' => "ظرف بازیافت,زباله,اشغال,حذف,سطل,trash,delete,recycle",
                    'icon' => 'fa-light fa-bin-recycle',
                ),
                array(
                    'keywords' => "بطری آب,آب,نوشیدنی,bottle,water",
                    'icon' => 'fa-light fa-bottle-water',
                ),
                array(
                    'keywords' => "ورق پلاستیک,صفحه,برگ,plastic,sheet",
                    'icon' => 'fa-light fa-sheet-plastic',
                ),
                array(
                    'keywords' => "دست,hand",
                    'icon' => 'fa-light fa-hand',
                ),
                array(
                    'keywords' => "غلتک,رول,رنگ,نقاشی,paint,roller",
                    'icon' => 'fa-light fa-paint-roller',
                ),
                array(
                    'keywords' => "پوشه,فولدر,پرونده,folder",
                    'icon' => 'fa-light fa-folder',
                ),
                array(
                    'keywords' => "پوشه,فولدر,پرونده,folder,open",
                    'icon' => 'fa-light fa-folder-open',
                ),
                array(
                    'keywords' => "دست,لایک,پسندیدن,thumbs,up,hand,like",
                    'icon' => 'fa-light fa-thumbs-up',
                ),
                array(
                    'keywords' => "دست,لایک,دیس,دیس لایک,نپسندیدن,thumbs,down,hand,dislike",
                    'icon' => 'fa-light fa-thumbs-down',
                ),
                array(
                    'keywords' => "حباب,نقل قول,کامنت,گفتگو,نظر,comment,conversation,bubble",
                    'icon' => 'fa-light fa-comments',
                ),
                array(
                    'keywords' => "کلید,رمز,key",
                    'icon' => 'fa-light fa-key',
                ),
                array(
                    'keywords' => "میخ,پونز,نشان,thumbtack",
                    'icon' => 'fa-light fa-thumbtack',
                ),
                array(
                    'keywords' => "اشتراک گذاری,موشک,ارسال,plane,paper,send,share",
                    'icon' => 'fa-light fa-paper-plane',
                ),
                array(
                    'keywords' => "کد,تگ,code,tag",
                    'icon' => 'fa-light fa-code',
                ),
                array(
                    'keywords' => "دنیا,جهان,کره زمین,نقشه,زمین,کره,global,internet,map,earth",
                    'icon' => 'fa-light fa-globe',
                ),
                array(
                    'keywords' => "دنیا,جهان,کره زمین,نقشه,زمین,کره,global,internet,map,earth",
                    'icon' => 'fa-light fa-globe-pointer',
                ),
                array(
                    'keywords' => "درخت,گیاه,tree",
                    'icon' => 'fa-light fa-tree',
                ),
                array(
                    'keywords' => "وایرلس,وای فای,ارتباط,wifi,wireless",
                    'icon' => 'fa-light fa-wifi',
                ),
                array(
                    'keywords' => "وایرلس,وای فای,ارتباط,wifi,wireless,exclamation,خطا",
                    'icon' => 'fa-light fa-wifi-exclamation',
                ),
                array(
                    'keywords' => "وایرلس,وای فای,ارتباط,wifi,wireless,slash",
                    'icon' => 'fa-light fa-wifi-slash',
                ),
                array(
                    'keywords' => "موتور,دوچرخه,bicycle",
                    'icon' => 'fa-light fa-bicycle',
                ),
                array(
                    'keywords' => "موتور سیکلت,موتور,motorcycle",
                    'icon' => 'fa-light fa-motorcycle',
                ),
                array(
                    'keywords' => "موتور سیکلت,موتور,moped",
                    'icon' => 'fa-light fa-moped',
                ),
                array(
                    'keywords' => "تنظیمات,sliders,setting,config",
                    'icon' => 'fa-light fa-sliders',
                ),
                array(
                    'keywords' => "تنظیمات,sliders,setting,config",
                    'icon' => 'fa-light fa-sliders-up',
                ),
                array(
                    'keywords' => "تنظیمات,sliders,setting,config",
                    'icon' => 'fa-light fa-square-sliders',
                ),
                array(
                    'keywords' => "تنظیمات,sliders,setting,config",
                    'icon' => 'fa-light fa-square-sliders-vertical',
                ),
                array(
                    'keywords' => "تنظیمات,wrench,setting,config",
                    'icon' => 'fa-light fa-wrench-simple',
                ),
                array(
                    'keywords' => "تنظیمات,screwdriver,setting,config",
                    'icon' => 'fa-light fa-screwdriver-wrench',
                ),
                array(
                    'keywords' => "شارپ,مربع,هشتک,hashtag,sharp",
                    'icon' => 'fa-light fa-hashtag',
                ),
                array(
                    'keywords' => "ظرف,فلاسک,flask",
                    'icon' => 'fa-light fa-flask',
                ),
                array(
                    'keywords' => "کیف,چمدان,briefcase,bag,case",
                    'icon' => 'fa-light fa-briefcase',
                ),
                array(
                    'keywords' => "یوزر,کاربر,ادرس,دفترچه,location,address,book,user",
                    'icon' => 'fa-light fa-address-book',
                ),
                array(
                    'keywords' => "حمام,وان,bath",
                    'icon' => 'fa-light fa-bath',
                ),
                array(
                    'keywords' => "دستشویی,toilet",
                    'icon' => 'fa-light fa-toilet',
                ),
                array(
                    'keywords' => "دست,همکاری,توافق,handshake,hand",
                    'icon' => 'fa-light fa-handshake',
                ),
                array(
                    'keywords' => "راست,فلش,ورود,bracket,right,enter",
                    'icon' => 'fa-light fa-right-to-bracket',
                ),
                array(
                    'keywords' => "راست,فلش,خروج,bracket,right,exit,quit",
                    'icon' => 'fa-light fa-right-from-bracket',
                ),
                array(
                    'keywords' => "لایه,layer",
                    'icon' => 'fa-light fa-layer-group',
                ),
                array(
                    'keywords' => "لایه,layer",
                    'icon' => 'fa-light fa-layer-plus',
                ),
                array(
                    'keywords' => ",گروه,کاربر,کاربران,users,group,user",
                    'icon' => 'fa-light fa-users',
                ),
                array(
                    'keywords' => "بازی,سرگرمی,gamepad,game,entertainment",
                    'icon' => 'fa-light fa-gamepad-modern',
                ),
                array(
                    'keywords' => "دسته بازی,بازی,سرگرمی,gamepad,game,entertainment",
                    'icon' => 'fa-light fa-gamepad',
                ),
                array(
                    'keywords' => "تخته بازی,بازی,سرگرمی,board,game,entertainment",
                    'icon' => 'fa-light fa-game-board',
                ),
                array(
                    'keywords' => "شطرنج,بازی,سرگرمی,chess,game,entertainment",
                    'icon' => 'fa-light fa-chess',
                ),
                array(
                    'keywords' => "تخته شطرنج,شطرنج,بازی,سرگرمی,board,chess,game,entertainment",
                    'icon' => 'fa-light fa-chess-board',
                ),
                array(
                    'keywords' => "بدمینتون,ورزش,badminton,sport",
                    'icon' => 'fa-light fa-badminton',
                ),
                array(
                    'keywords' => "دارت,هدف,هدف گیری,نشانه,نشانه گیری,target,aim,bullseye",
                    'icon' => 'fa-light fa-bullseye-arrow',
                ),
                array(
                    'keywords' => "تاس,تاس,بازی,game,dice",
                    'icon' => 'fa-light fa-dice',
                ),
                array(
                    'keywords' => "فوتبال,ورزش,football,soccer,sport",
                    'icon' => 'fa-light fa-futbol',
                ),
                array(
                    'keywords' => "زمین بازی,ورزش,court,sport",
                    'icon' => 'fa-light fa-court-sport',
                ),
                array(
                    'keywords' => "باطری,battery,energy",
                    'icon' => 'fa-light fa-battery-bolt',
                ),
                array(
                    'keywords' => "الماس,diamond",
                    'icon' => 'fa-light fa-diamond',
                ),
                array(
                    'keywords' => "الماس,gem",
                    'icon' => 'fa-light fa-gem',
                ),
                array(
                    'keywords' => "لینک,ارتباط,link",
                    'icon' => 'fa-light fa-link',
                ),
                array(
                    'keywords' => "لینک,ارتباط,link",
                    'icon' => 'fa-light fa-link-simple',
                ),
                array(
                    'keywords' => "ماهی,fish",
                    'icon' => 'fa-light fa-fish',
                ),
                array(
                    'keywords' => "باگ,حشره,bug",
                    'icon' => 'fa-light fa-bug',
                ),
                array(
                    'keywords' => "نمودار,chart",
                    'icon' => 'fa-light fa-chart-simple',
                ),
                array(
                    'keywords' => "نمودار,chart",
                    'icon' => 'fa-light fa-chart-line-up',
                ),
                array(
                    'keywords' => "نمودار,chart",
                    'icon' => 'fa-light fa-chart-line-down',
                ),
                array(
                    'keywords' => "نمودار,chart",
                    'icon' => 'fa-light fa-chart-line',
                ),
                array(
                    'keywords' => "نمودار,chart",
                    'icon' => 'fa-light fa-chart-pie-simple',
                ),
                array(
                    'keywords' => "نمودار,chart",
                    'icon' => 'fa-light fa-chart-pie',
                ),
                array(
                    'keywords' => "نمودار,chart,dollar,money,currency,price,پول",
                    'icon' => 'fa-light fa-chart-mixed-up-circle-dollar',
                ),
                array(
                    'keywords' => "نمودار,chart,dollar,money,currency,price,پول",
                    'icon' => 'fa-light fa-chart-mixed-up-circle-currency',
                ),
                array(
                    'keywords' => "نمودار,chart",
                    'icon' => 'fa-light fa-chart-column',
                ),
                array(
                    'keywords' => "نمودار,chart,user,کاربر",
                    'icon' => 'fa-light fa-chart-user',
                ),
                array(
                    'keywords' => "لباس,تیشرت,shirt,tshirt",
                    'icon' => 'fa-light fa-shirt',
                ),
                array(
                    'keywords' => "لباس بلند,پیراهن,shirt,long sleeve",
                    'icon' => 'fa-light fa-shirt-long-sleeve',
                ),
                array(
                    'keywords' => "زیرپوش,لباس,تیشرت,undershirt,tshirt",
                    'icon' => 'fa-light fa-shirt-running',
                ),
                array(
                    'keywords' => "زیرپوش,لباس,تیشرت,undershirt,tshirt",
                    'icon' => 'fa-light fa-shirt-tank-top',
                ),
                array(
                    'keywords' => "جلیقه,لباس,vest",
                    'icon' => 'fa-light fa-vest',
                ),
                array(
                    'keywords' => "چکمه,کفش,boot",
                    'icon' => 'fa-light fa-boot',
                ),
                array(
                    'keywords' => "رخت آویز,لباس,hanger,clothes",
                    'icon' => 'fa-light fa-clothes-hanger',
                ),
                array(
                    'keywords' => "کلاه,hat",
                    'icon' => 'fa-light fa-hat-winter',
                ),
                array(
                    'keywords' => "کت شلوار,مرد,آقایان",
                    'icon' => 'fa-light fa-user-tie',
                ),
                array(
                    'keywords' => "جوراب,کالج,پا,stocking,sock",
                    'icon' => 'fa-light fa-stocking',
                ),
                array(
                    'keywords' => "شال,گردن,scarf",
                    'icon' => 'fa-light fa-scarf',
                ),
                array(
                    'keywords' => "پازل,puzzle,plugin,افزونه",
                    'icon' => 'fa-light fa-puzzle-piece',
                ),
                array(
                    'keywords' => "پازل,puzzle,plugin,افزونه",
                    'icon' => 'fa-light fa-puzzle',
                ),
                array(
                    'keywords' => "لنگر,anchor",
                    'icon' => 'fa-light fa-anchor',
                ),
                array(
                    'keywords' => "کاما,نقل قول,نوشته,quote,left",
                    'icon' => 'fa-light fa-quote-left',
                ),
                array(
                    'keywords' => "کاما,نقل قول,نوشته,quote,right",
                    'icon' => 'fa-light fa-quote-right',
                ),
                array(
                    'keywords' => "مکان,راهنما,اینفو,اطلاعات,info,data,Guide",
                    'icon' => 'fa-light fa-info',
                ),
                array(
                    'keywords' => "ساعت,clock,timer,تایمر,کرنومتر",
                    'icon' => 'fa-light fa-timer',
                ),
                array(
                    'keywords' => "ساعت,clock",
                    'icon' => 'fa-light fa-clock',
                ),
                array(
                    'keywords' => "ساعت,هشدار,clock,alarm",
                    'icon' => 'fa-light fa-alarm-clock',
                ),
                array(
                    'keywords' => "اجرا,شروع,play",
                    'icon' => 'fa-light fa-play',
                ),
                array(
                    'keywords' => "اجرا,شروع,play",
                    'icon' => 'fa-light fa-circle-play',
                ),
                array(
                    'keywords' => "توقف,استاپ,pause",
                    'icon' => 'fa-light fa-pause',
                ),
                array(
                    'keywords' => "مجدد,تکرار,reply",
                    'icon' => 'fa-light fa-reply',
                ),
                array(
                    'keywords' => "عقب,backward",
                    'icon' => 'fa-light fa-backward',
                ),
                array(
                    'keywords' => "جلو,forward",
                    'icon' => 'fa-light fa-forward',
                ),
                array(
                    'keywords' => "پرسش,سوال,question",
                    'icon' => 'fa-light fa-question',
                ),
                array(
                    'keywords' => "آپلود,بارگذاری,upload",
                    'icon' => 'fa-light fa-upload',
                ),
                array(
                    'keywords' => "ذخیره,پایگاه داده,حافظه,داده,database",
                    'icon' => 'fa-light fa-database',
                ),
                array(
                    'keywords' => "کاغذ,برگه,کپی,رونوشت,copy,paper,page,duplicate",
                    'icon' => 'fa-light fa-copy',
                ),
                array(
                    'keywords' => "dash,plus,add,اضافه کردن,جمع,مربع,افزودن,square",
                    'icon' => 'fa-light fa-square-dashed-circle-plus',
                ),
                array(
                    'keywords' => "page,ویندوز,پنجره,صفحه,window",
                    'icon' => 'fa-light fa-window',
                ),
                array(
                    'keywords' => "page,ویندوز,پنجره,صفحه,window,browser",
                    'icon' => 'fa-light fa-browser',
                ),
                array(
                    'keywords' => "page,ویندوز,پنجره,صفحه,window",
                    'icon' => 'fa-light fa-window-restore',
                ),
                array(
                    'keywords' => "page,ویندوز,پنجره,صفحه,window,sidebar,سایدبار",
                    'icon' => 'fa-light fa-sidebar-flip',
                ),
                array(
                    'keywords' => "page,ویندوز,پنجره,صفحه,window,backward",
                    'icon' => 'fa-light fa-send-backward',
                ),
                array(
                    'keywords' => "page,ویندوز,پنجره,صفحه,window,forward",
                    'icon' => 'fa-light fa-bring-forward',
                ),
                array(
                    'keywords' => "page,ویندوز,پنجره,صفحه,window,forward,backward,bring",
                    'icon' => 'fa-light fa-bring-front',
                ),
                array(
                    'keywords' => "page,ویندوز,پنجره,صفحه,window,clone,copy,کپی",
                    'icon' => 'fa-light fa-clone',
                ),
                array(
                    'keywords' => "جدا شدن,منفک,تفریق,منهای,subtract,deduct,remove",
                    'icon' => 'fa-light fa-object-subtract',
                ),
                array(
                    'keywords' => "تلفن,گوشی,تبلت,موبایل,نقشه,همراه,تلفن همراه,mobile,tablet",
                    'icon' => 'fa-light fa-mobile',
                ),
                array(
                    'keywords' => "کامپیوتر,لپ تلپ,computer,laptop",
                    'icon' => 'fa-light fa-laptop',
                ),
                array(
                    'keywords' => "کامپیوتر,لپ تلپ,computer,laptop,code",
                    'icon' => 'fa-light fa-laptop-code',
                ),
                array(
                    'keywords' => "کامپیوتر,لپ تلپ,computer,laptop,arrow,down,download,فلش,دانلود",
                    'icon' => 'fa-light fa-laptop-arrow-down',
                ),
                array(
                    'keywords' => "کامپیوتر,گوشی,موبایل,لپ تلپ,computer,laptop,mobile",
                    'icon' => 'fa-light fa-laptop-mobile',
                ),
                array(
                    'keywords' => "مانیتور,صفحه نمایش,دسکتاپ,صفحه,desktop,monitor",
                    'icon' => 'fa-light fa-desktop',
                ),
                array(
                    'keywords' => "ماشین لباسشویی,لباسشویی,washing,machine,laundry",
                    'icon' => 'fa-light fa-washing-machine',
                ),
                array(
                    'keywords' => "مخلوط کن,آشپزخانه,blender,kitchen",
                    'icon' => 'fa-light fa-blender',
                ),
                array(
                    'keywords' => "لوازم آشپزخانه,آشپزخانه,kitchen,set",
                    'icon' => 'fa-light fa-kitchen-set',
                ),
                array(
                    'keywords' => "چاقو,کارد,آشپزخانه,knife,kitchen",
                    'icon' => 'fa-light fa-knife-kitchen',
                ),
                array(
                    'keywords' => "ماکروویو,فر,آشپزخانه,microwave,kitchen",
                    'icon' => 'fa-light fa-microwave',
                ),
                array(
                    'keywords' => "اجاق,فر,آشپزخانه,oven,kitchen",
                    'icon' => 'fa-light fa-oven',
                ),
                array(
                    'keywords' => "یخچال,آشپزخانه,refrigerator,kitchen",
                    'icon' => 'fa-light fa-refrigerator',
                ),
                array(
                    'keywords' => "سینک,ظرفشویی,sink",
                    'icon' => 'fa-light fa-sink',
                ),
                array(
                    'keywords' => "فندک,آتش,شعله,داغ,lighter,fire",
                    'icon' => 'fa-light fa-fire-burner',
                ),
                array(
                    'keywords' => "شیرآب,آب,فاضلاب,لوله,آشپزخانه,حمام,لوله کشی,water,faucet,plumbing",
                    'icon' => 'fa-light fa-faucet',
                ),
                array(
                    'keywords' => "حمام,وان,bath,tub",
                    'icon' => 'fa-light fa-bath',
                ),
                array(
                    'keywords' => "سنجاق,پیوست,paperclip",
                    'icon' => 'fa-light fa-paperclip',
                ),
                array(
                    'keywords' => "قهوه,نوشیدنی,داغ,لیوان,mug,hot,coffe",
                    'icon' => 'fa-light fa-mug-hot',
                ),
                array(
                    'keywords' => "قهوه,نوشیدنی,داغ,لیوان,mug,hot,coffe",
                    'icon' => 'fa-light fa-mug-saucer',
                ),
                array(
                    'keywords' => "قهوه,نوشیدنی,داغ,لیوان,mug,hot,coffe,bean",
                    'icon' => 'fa-light fa-coffee-bean',
                ),
                array(
                    'keywords' => "آتش,شعله,داغ,fire",
                    'icon' => 'fa-light fa-fire',
                ),
                array(
                    'keywords' => "سریع,ارسال,شروع,موشک,rocket,launch,send",
                    'icon' => 'fa-light fa-rocket-launch',
                ),
                array(
                    'keywords' => "افزونه,آپشن,گزینه,جدول,grid,option,additional",
                    'icon' => 'fa-light fa-grid-2',
                ),
                array(
                    'keywords' => "صندوق,ایمیل,پرونده,دریافت,file,case,inbox",
                    'icon' => 'fa-light fa-inbox',
                ),
                array(
                    'keywords' => "پشتیبانی,تیکت,ticket",
                    'icon' => 'fa-light fa-ticket',
                ),
                array(
                    'keywords' => "جدول,table,layout",
                    'icon' => 'fa-light fa-table-layout',
                ),
                array(
                    'keywords' => "جدول,table",
                    'icon' => 'fa-light fa-table-pivot',
                ),
                array(
                    'keywords' => "جدول,table",
                    'icon' => 'fa-light fa-table',
                ),
                array(
                    'keywords' => "جدول,table",
                    'icon' => 'fa-light fa-table-cells',
                ),
                array(
                    'keywords' => "جدول,table",
                    'icon' => 'fa-light fa-table-cells-large',
                ),
                array(
                    'keywords' => "جدول,table,column,ستون",
                    'icon' => 'fa-light fa-columns-3',
                ),
                array(
                    'keywords' => "فست فود,رستوران,کافی شاپ,کافه,غذا,burger,soda,fastfood,Restaurant,cafe",
                    'icon' => 'fa-light fa-burger-soda',
                ),
                array(
                    'keywords' => "فست فود,رستوران,کافی شاپ,کافه,غذا,burger,soda,fastfood,Restaurant,cafe",
                    'icon' => 'fa-light fa-fork-knife',
                ),
                array(
                    'keywords' => "فست فود,رستوران,کافی شاپ,کافه,غذا,burger,soda,fastfood,Restaurant,cafe",
                    'icon' => 'fa-light fa-plate-utensils',
                ),
                array(
                    'keywords' => "فست فود,رستوران,کافی شاپ,کافه,غذا,burger,soda,fastfood,Restaurant,cafe",
                    'icon' => 'fa-light fa-croissant',
                ),
                array(
                    'keywords' => "فست فود,رستوران,کافی شاپ,کافه,غذا,burger,soda,fastfood,Restaurant,cafe",
                    'icon' => 'fa-light fa-cup-straw-swoosh',
                ),
                array(
                    'keywords' => "فست فود,رستوران,کافی شاپ,کافه,غذا,burger,soda,fastfood,Restaurant,cafe",
                    'icon' => 'fa-light fa-burger-cheese',
                ),
                array(
                    'keywords' => "فست فود,رستوران,کافی شاپ,کافه,غذا,burger,soda,fastfood,Restaurant,cafe",
                    'icon' => 'fa-light fa-burger-fries',
                ),
                array(
                    'keywords' => "فست فود,رستوران,کافی شاپ,کافه,غذا,burger,soda,fastfood,Restaurant,cafe",
                    'icon' => 'fa-light fa-pizza-slice',
                ),
                array(
                    'keywords' => "غذا,خوراک,ظرف,پخت,پز,آشپزی,food,cooking,pot",
                    'icon' => 'fa-light fa-pot-food',
                ),
                array(
                    'keywords' => "تخم مرغ,egg",
                    'icon' => 'fa-light fa-egg',
                ),
                array(
                    'keywords' => "کیوی,میوه,kiwi,fruit",
                    'icon' => 'fa-light fa-kiwi-fruit',
                ),
                array(
                    'keywords' => "سیب,میوه,apple,fruit",
                    'icon' => 'fa-light fa-apple-whole',
                ),
                array(
                    'keywords' => "موز,میوه,banana,fruit",
                    'icon' => 'fa-light fa-banana',
                ),
                array(
                    'keywords' => "گیلاس,میوه,cherry,fruit",
                    'icon' => 'fa-light fa-cherries',
                ),
                array(
                    'keywords' => "انگور,میوه,grape,fruit",
                    'icon' => 'fa-light fa-grapes',
                ),
                array(
                    'keywords' => "لیمو,میوه,lemon,fruit",
                    'icon' => 'fa-light fa-lemon',
                ),
                array(
                    'keywords' => "توت فرنگی,میوه,strawberry,fruit",
                    'icon' => 'fa-light fa-strawberry',
                ),
                array(
                    'keywords' => "آناناس,میوه,pineapple,fruit",
                    'icon' => 'fa-light fa-pineapple',
                ),
                array(
                    'keywords' => "هندوانه,میوه,watermelon,fruit",
                    'icon' => 'fa-light fa-watermelon-slice',
                ),
                array(
                    'keywords' => "هویج,سبزی,carrot,vegetable",
                    'icon' => 'fa-light fa-carrot',
                ),
                array(
                    'keywords' => "پرتقال,میوه,orange,fruit",
                    'icon' => 'fa-light fa-citrus-slice',
                ),
                array(
                    'keywords' => "برگ,گیاه,leaf",
                    'icon' => 'fa-light fa-leaf',
                ),
                array(
                    'keywords' => "برگ,گیاه,نقشه,leaf,maple",
                    'icon' => 'fa-light fa-leaf-maple',
                ),
                array(
                    'keywords' => "شبدر,چهار برگ,شانس,clover,luck,leaf",
                    'icon' => 'fa-light fa-clover',
                ),
                array(
                    'keywords' => "موقعیت,قطب نما,جهت نما,nav,location,compass",
                    'icon' => 'fa-light fa-compass',
                ),
                array(
                    'keywords' => "سریع,سرعت,speed,fast,gauge",
                    'icon' => 'fa-light fa-gauge-max',
                ),
                array(
                    'keywords' => "سرعت,speed,gauge",
                    'icon' => 'fa-light fa-gauge',
                ),
                array(
                    'keywords' => "کند,آهسته,سرعت,speed,slow,gauge",
                    'icon' => 'fa-light fa-gauge-min',
                ),
                array(
                    'keywords' => "اطلاعیه,بلندگو,بوق,megaphone,sound",
                    'icon' => 'fa-light fa-megaphone',
                ),
                array(
                    'keywords' => "نقاشی,رنگ,پلت,palette,color",
                    'icon' => 'fa-light fa-palette',
                ),
                array(
                    'keywords' => "نقاشی,رنگ,پلت,palette,color",
                    'icon' => 'fa-light fa-eye-dropper',
                ),
                array(
                    'keywords' => "نقاشی,رنگ,پلت,palette,color",
                    'icon' => 'fa-light fa-swatchbook',
                ),
                array(
                    'keywords' => "نقاشی,رنگ,قلمو,brush,color",
                    'icon' => 'fa-light fa-brush',
                ),
                array(
                    'keywords' => "نقاشی,رنگ,قلمو,brush,color",
                    'icon' => 'fa-light fa-paintbrush',
                ),
                array(
                    'keywords' => "برند,لوگو,پرچم,logo,brand,flag",
                    'icon' => 'fa-light fa-font-awesome',
                ),
                array(
                    'keywords' => "تایید,تیک,badge,check",
                    'icon' => 'fa-light fa-badge-check',
                ),
                array(
                    'keywords' => "سلامتی,قلب,کمک,heart,help",
                    'icon' => 'fa-light fa-hand-holding-heart',
                ),
                array(
                    'keywords' => "مپ,سایت,شبکه,سایت مپ,network,sitemap",
                    'icon' => 'fa-light fa-sitemap',
                ),
                array(
                    'keywords' => "ترجمه,زبان,translate,language",
                    'icon' => 'fa-light fa-language',
                ),
                array(
                    'keywords' => "آچار,تنظیمات,config,setting,wrench",
                    'icon' => 'fa-light fa-wrench',
                ),
                array(
                    'keywords' => "جام,افتخار,win,trophy",
                    'icon' => 'fa-light fa-trophy-star',
                ),
                array(
                    'keywords' => "مدال,افتخار,win,medal",
                    'icon' => 'fa-light fa-medal',
                ),
                array(
                    'keywords' => "مدال,افتخار,win,medal",
                    'icon' => 'fa-light fa-award',
                ),
                array(
                    'keywords' => "مدال,افتخار,win,medal",
                    'icon' => 'fa-light fa-award-simple',
                ),
                array(
                    'keywords' => "مدال,افتخار,win,medal,trophy",
                    'icon' => 'fa-light fa-wreath-laurel',
                ),
                array(
                    'keywords' => "پادشاه,تاج,مدیر,king,admin,crown",
                    'icon' => 'fa-light fa-crown',
                ),
                array(
                    'keywords' => "کپی رایت,رایت,کپی,c,copyright",
                    'icon' => 'fa-light fa-copyright',
                ),
                array(
                    'keywords' => "رادیو,radio",
                    'icon' => 'fa-light fa-radio',
                ),
                array(
                    'keywords' => "تلوزیون,مانیتور,tv",
                    'icon' => 'fa-light fa-tv',
                ),
                array(
                    'keywords' => "تلوزیون,مانیتور,tv",
                    'icon' => 'fa-light fa-tv-retro',
                ),
                array(
                    'keywords' => "اثر انگشت,fingerprint",
                    'icon' => 'fa-light fa-fingerprint',
                ),
                array(
                    'keywords' => "ماشین حساب,calculator",
                    'icon' => 'fa-light fa-calculator',
                ),
                array(
                    'keywords' => "میکروفن,microphone",
                    'icon' => 'fa-light fa-microphone-lines',
                ),
                array(
                    'keywords' => "میکروفن,microphone",
                    'icon' => 'fa-light fa-circle-microphone-lines',
                ),
                array(
                    'keywords' => "خبر خوان,rss",
                    'icon' => 'fa-light fa-square-rss',
                ),
                array(
                    'keywords' => "خبر خوان,rss",
                    'icon' => 'fa-light fa-rss',
                ),
                array(
                    'keywords' => "فن آوری,تکنولوژی,atom,technology",
                    'icon' => 'fa-light fa-atom-simple',
                ),
                array(
                    'keywords' => "قانون,دادگاه,court,scale,law",
                    'icon' => 'fa-light fa-scale-unbalanced',
                ),
                array(
                    'keywords' => "قانون,دادگاه,court,scale,law",
                    'icon' => 'fa-light fa-scale-balanced',
                ),
                array(
                    'keywords' => "دلار,پول,ریال,money",
                    'icon' => 'fa-light fa-money-bill-wave',
                ),
                array(
                    'keywords' => "دلار,پول,ریال,money",
                    'icon' => 'fa-light fa-hands-holding-dollar',
                ),
                array(
                    'keywords' => "دلار,پول,ریال,money",
                    'icon' => 'fa-light fa-hand-holding-dollar',
                ),
                array(
                    'keywords' => "دلار,پول,ریال,money",
                    'icon' => 'fa-light fa-money-bill',
                ),
                array(
                    'keywords' => "دلار,پول,ریال,money",
                    'icon' => 'fa-light fa-sack-dollar',
                ),
                array(
                    'keywords' => "دلار,پول,ریال,money,dollar",
                    'icon' => 'fa-light fa-dollar-sign',
                ),
                array(
                    'keywords' => "دلار,پول,ریال,money,dollar",
                    'icon' => 'fa-light fa-circle-dollar',
                ),
                array(
                    'keywords' => "دلار,پول,ریال,money,dollar",
                    'icon' => 'fa-light fa-badge-dollar',
                ),
                array(
                    'keywords' => "دلار,پول,ریال,money,dollar",
                    'icon' => 'fa-light fa-square-dollar',
                ),
                array(
                    'keywords' => "money,currency,کیف پول,پول,ریال,wallet",
                    'icon' => 'fa-light fa-wallet',
                ),
                array(
                    'keywords' => "money,currency,percent,درصد,تخفیف,sale,offer",
                    'icon' => 'fa-light fa-percent',
                ),
                array(
                    'keywords' => "money,currency,percent,درصد,تخفیف,sale,offer",
                    'icon' => 'fa-light fa-badge-percent',
                ),
                array(
                    'keywords' => "فیس بوک,اجتماعی,شبکه اجتماعی,facebook,social,social media",
                    'icon' => 'fa-brands fa-square-facebook',
                ),
                array(
                    'keywords' => "فیس بوک,اجتماعی,شبکه اجتماعی,facebook,social,social media",
                    'icon' => 'fa-brands fa-facebook-f',
                ),
                array(
                    'keywords' => "فیس بوک,اجتماعی,شبکه اجتماعی,facebook,social,social media",
                    'icon' => 'fa-brands fa-facebook',
                ),
                array(
                    'keywords' => "اینستاگرام,اجتماعی,شبکه اجتماعی,instagram,social,social media",
                    'icon' => 'fa-brands fa-instagram',
                ),
                array(
                    'keywords' => "اینستاگرام,اجتماعی,شبکه اجتماعی,instagram,social,social media",
                    'icon' => 'fa-brands fa-square-instagram',
                ),
                array(
                    'keywords' => "تو‌ئیتر,ایکس,اجتماعی,شبکه اجتماعی,twitter,social,social media,x",
                    'icon' => 'fa-brands fa-twitter',
                ),
                array(
                    'keywords' => "تو‌ئیتر,اجتماعی,ایکس,شبکه اجتماعی,twitter,social,social media,x",
                    'icon' => 'fa-brands fa-square-twitter',
                ),
                array(
                    'keywords' => "تو‌ئیتر,اجتماعی,ایکس,شبکه اجتماعی,twitter,social,social media,x",
                    'icon' => 'fa-brands fa-x-twitter',
                ),
                array(
                    'keywords' => "تو‌ئیتر,اجتماعی,ایکس,شبکه اجتماعی,twitter,social,social media,x",
                    'icon' => 'fa-brands fa-square-x-twitter',
                ),
                array(
                    'keywords' => "واتس آپ,واتساپ,اجتماعی,شبکه اجتماعی,whatsapp,social,social media",
                    'icon' => 'fa-brands fa-whatsapp',
                ),
                array(
                    'keywords' => "واتس آپ,واتساپ,اجتماعی,شبکه اجتماعی,whatsapp,social,social media",
                    'icon' => 'fa-brands fa-square-whatsapp',
                ),
                array(
                    'keywords' => "لینکدین,اجتماعی,شبکه اجتماعی,linkedin,social,social media",
                    'icon' => 'fa-brands fa-linkedin',
                ),
                array(
                    'keywords' => "لینکدین,اجتماعی,شبکه اجتماعی,linkedin,social,social media",
                    'icon' => 'fa-brands fa-linkedin-in',
                ),
                array(
                    'keywords' => "گیت هاب,اجتماعی,شبکه اجتماعی,github,social,social media",
                    'icon' => 'fa-brands fa-github',
                ),
                array(
                    'keywords' => "گیت هاب,اجتماعی,شبکه اجتماعی,github,social,social media",
                    'icon' => 'fa-brands fa-square-github',
                ),
                array(
                    'keywords' => "گیت لب,اجتماعی,شبکه اجتماعی,gitlab,social,social media",
                    'icon' => 'fa-brands fa-gitlab',
                ),
                array(
                    'keywords' => "گیت لب,اجتماعی,شبکه اجتماعی,gitlab,social,social media",
                    'icon' => 'fa-brands fa-square-gitlab',
                ),
                array(
                    'keywords' => "پینترست,اجتماعی,شبکه اجتماعی,pinterest,social,social media",
                    'icon' => 'fa-brands fa-square-pinterest',
                ),
                array(
                    'keywords' => "پینترست,اجتماعی,شبکه اجتماعی,pinterest,social,social media",
                    'icon' => 'fa-brands fa-pinterest-p',
                ),
                array(
                    'keywords' => "پینترست,اجتماعی,شبکه اجتماعی,pinterest,social,social media",
                    'icon' => 'fa-brands fa-pinterest',
                ),
                array(
                    'keywords' => "تلگرام,اجتماعی,شبکه اجتماعی,telegram,social,social media",
                    'icon' => 'fa-brands fa-telegram',
                ),
                array(
                    'keywords' => "اسکایپ,اجتماعی,شبکه اجتماعی,skype,social,social media",
                    'icon' => 'fa-brands fa-skype',
                ),
                array(
                    'keywords' => "توویچ,اجتماعی,شبکه اجتماعی,twitch,social,social media",
                    'icon' => 'fa-brands fa-twitch',
                ),
                array(
                    'keywords' => "یوتیوب,اجتماعی,شبکه اجتماعی,youtube,social,social media",
                    'icon' => 'fa-brands fa-youtube',
                ),
                array(
                    'keywords' => "یوتیوب,اجتماعی,شبکه اجتماعی,youtube,social,social media",
                    'icon' => 'fa-brands fa-square-youtube',
                ),
                array(
                    'keywords' => "دیسکورد,اجتماعی,شبکه اجتماعی,discord,social,social media",
                    'icon' => 'fa-brands fa-discord',
                ),
            );
        }

        /**
         * Nav menu icons elements
         * @return void
         * @param void
         */
        public static function nav_menu_icons_elements($isNavMenu = false)
        {
?>
            <!-- Irantheme nav menu icons (Holder all) -->
            <div class="irantheme-settings irantheme-icons-selector" <?php if ($isNavMenu) {
                                                                            echo 'data-settings="options"';
                                                                        } ?>>
                <!-- Wrapper -->
                <div class="irantheme-icons-selector-wrapper">
                    <!-- Toolbar -->
                    <div class="irantheme-icons-selector-toolbar">
                        <!-- Title -->
                        <div class="irantheme-icons-selector-toolbar-title">آیکن خود را انتخاب کنید</div>
                        <!-- Close -->
                        <button class="irantheme-btn-void irantheme-icons-selector-close">
                            <span class="dashicons dashicons-no-alt"></span>
                        </button>
                    </div>
                    <!-- Elements -->
                    <div class="irantheme-icons-selector-elements">
                        <!-- Search icons -->
                        <div class="irantheme-icons-selector-search-form-wrapper">
                            <div class="irantheme-icons-selector-search-form">
                                <div class="irantheme-icons-selector-search-form-icon">
                                    <i class="fa-light fa-magnifying-glass"></i>
                                </div>
                                <input class="irantheme-icons-selector-search-form-input" type="text" placeholder="جستجوی آیکون (فارسی یا انگلیسی تایپ کنید) ..." />
                            </div>
                        </div>
                        <ul class="irantheme-icons-selector-elements-list">
                            <?php
                            foreach (self::icons_info() as $icon_info_item) :
                            ?>
                                <!-- Box icon -->
                                <li class="irantheme-icons-selector-elements-list-box" data-keywords="<?php echo esc_attr($icon_info_item['keywords']); ?>">
                                    <i class="<?php echo esc_attr($icon_info_item['icon']); ?>"></i>
                                    <span class="irantheme-icons-selector-elements-list-box-name"></span>
                                </li>
                            <?php
                            endforeach;
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
<?php
        }
    }
    new Irantheme_Icons_Selector();
}
