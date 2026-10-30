# راهنمای نگهداری پروژه (WordPress + WooCommerce)

## ساختار کد
فقط این بخش‌ها «کد ما» هستند و داخل git قرار دارند:

| مسیر | نقش |
|---|---|
| `public/wp-content/themes/wp_storeplus-child/` | قالب فرزند: تقریباً همه‌ی سفارشی‌سازی‌ها |
| `public/wp-content/themes/wp_storeplus/` | قالب اصلی (خریداری‌شده). **۴ فایل آن دستکاری شده** (پایین را ببین) |
| `public/wp-content/plugins/custom-admin-ui/` | ظاهر پنل ادمین |
| `public/wp-content/plugins/irantheme-demo-seo-control/` | افزونه‌ی کوچک کنترل سئو |
| `public/wp-content/mu-plugins/` | امنیت، پیشنهادهای شگفت‌انگیز، دسته‌بندی رسانه، 404 سریع (بر اساس نام فایل‌ها) |
| `_deploy-prep/` | قالب wp-config/.htaccess production و چک‌لیست انتشار |

وردپرس، ووکامرس و سایر افزونه‌های آماده داخل git نیستند (با Duplicator/ابزار خودشان آپدیت می‌شوند).

## قالب فرزند
- `functions.php` فقط **مانیفست** است: لیست `inc/*.php` و CSS/JS با scope (`site`, `commerce`, `home`, `blog`…) در `storeplus_child_files()`. فایلی که در لیست نیست بارگذاری نمی‌شود.
- `inc/` : هر قابلیت یک فایل (مثلاً `seo-ai.php`, `offers-timer.php`, `category-menu.php`). قابلیت جدید = فایل جدید + یک خط در مانیفست.
- `assets/css/parts/` : **منبع** CSSها. شیت‌های `assets/css/*.css` با `tools/build-css-sheets.php` ساخته می‌شوند؛ به‌جای ویرایش مستقیم شیت‌ها، parts را ویرایش و دوباره build کن.
- `tools/` : اسکریپت‌های تک‌منظوره، فقط CLI (گارد `_boot.php`). هرگز روی سرور اجرا نکن مگر لازم باشد.
- `woocommerce/` : override قالب‌های ووکامرس.

## قاعده‌ها
1. تغییر جدید → فقط در قالب فرزند یا mu-plugin، نه در قالب اصلی.
2. قبل از تغییر بزرگ: `git commit`. دیگر لازم نیست فایل `.bak` بسازی؛ git نسخه‌ی قبلی را دارد.
3. فایل موقت/تست داخل `public` نساز (`_tmp_*` و غیره). برای آن‌ها از `_dev-scraps/` بیرون از public استفاده کن.
4. فقط `WP_ENVIRONMENT_TYPE` تعیین‌کننده‌ی local/production است.

## ⚠ دستکاری‌های قالب اصلی (با آپدیت قالب از بین می‌روند)
- `inc/core/global.php`
- `inc/core/section/class-irantheme-section-woocommerce-mini-products.php`
- `inc/core/section/classes/class-irantheme-section-woocommerce.php` (نسخه‌ی اصلی: `...php.bak-spcard`)
- `woocommerce/archive-product.php`

پیشنهاد: به‌تدریج به hook/override در قالب فرزند منتقل شوند (`archive-product.php` را می‌شود مستقیم در `wp_storeplus-child/woocommerce/` override کرد). قبل از آپدیت قالب اصلی، diff این فایل‌ها را با `git diff` بگیر.

## مدیریت فایل‌های موقت
`_dev-scraps/` (بیرون از git و public) شامل لاگ‌ها، بکاپ‌های CSS و اسکریپت‌های ترمیم قدیمی است. بعد از اطمینان از انتشار می‌توانی پاکش کنی.
