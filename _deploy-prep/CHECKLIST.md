# چک‌لیست انتشار روی هاست

## پوشه‌ها
- `public\` : خود سایت (فقط همین منتقل می‌شود)
- `_dev-scraps\` : فایل‌های موقت/لاگ که از public بیرون آوردم (قابل برگشت، آپلود نکن)
- `_deploy-prep\` : فایل‌های آماده برای سرور

## قبل از انتقال
1. در wp-admin → تنظیمات → خواندن: تیک «جلوگیری از ایندکس موتورهای جستجو» را بردار (بعد از انتقال).
2. یک بسته‌ی **جدید** با Duplicator بساز (بسته‌ی قبلی مربوط به 25 مهر است و تغییرات بعدی را ندارد).
3. از یک سرور با PHP 8.1+ و MySQL/MariaDB با utf8mb4 مطمئن شو.

## روی هاست
1. دیتابیس + کاربر بساز.
2. `archive.zip` و `installer.php` را آپلود و installer را اجرا کن (آدرس دامنه را جدید وارد کن).
3. بعد از نصب: `wp-config.production.php` را کپی کن، مقدار DB را پر کن، نامش را `wp-config.php` بگذار (کلیدهای امنیتی جدید داخلش هست).
   (اگر Duplicator خودش wp-config ساخت، فقط `WP_ENVIRONMENT_TYPE = production` و `FORCE_SSL_ADMIN` را مطمئن شو.)
4. `htaccess.production` را به‌جای `.htaccess` بگذار (اگر هاست LiteSpeed/DirectAdmin است و قوانین خودش را اضافه کرده، فقط بخش Hardening را اضافه کن).
5. SSL فعال کن؛ بعد Settings → Permalinks → ذخیره.
6. بعد از اتمام: فایل‌های installer.php، installer-backup و پوشه `wp-content/duplicator-backups` را از سرور پاک کن.

## بعد از انتقال (تست)
- صفحه اصلی، فروشگاه، سبد خرید، پرداخت (درگاه), پنل پیامک/OTP، ایمیل‌ها، پنل ادمین
- Gateland و درگاه‌ها: کلید/آدرس بازگشت را روی دامنه جدید چک کن
- وضعیت ووکامرس → ابزارها → پاک کردن ترنزینت‌ها؛ LiteSpeed Cache → Purge All
- Yoast SEO → نقشه سایت و robots.txt
- تغییر رمز ادمین و حذف کاربرهای تستی

## نکته
- `mu-plugins/local-by-flywheel-live-link-helper.php` روی هاست بی‌اثر است؛ می‌توانی نبری.
- تنظیمات `WP_HTTP_BLOCK_EXTERNAL` فقط در حالت local فعال است و روی production خاموش می‌شود (پیامک/درگاه کار می‌کند).
