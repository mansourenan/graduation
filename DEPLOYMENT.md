# دليل النشر - Arrive Alive Backend

## متطلبات النظام
- PHP 8.2 أو أحدث
- Composer
- MySQL/MariaDB
- Node.js 18+ (للـ assets)
- Web Server (Apache/Nginx)

## خطوات النشر

### 1. تحضير الملفات
```bash
# نسخ الملفات إلى الخادم
git clone https://github.com/mansourenan/graduation.git
cd graduation

# تثبيت dependencies
composer install --optimize-autoloader --no-dev
npm install
npm run build
```

### 2. إعداد البيئة
```bash
# نسخ ملف البيئة
cp .env.example .env

# توليد مفتاح التطبيق
php artisan key:generate

# توليد مفتاح JWT
php artisan jwt:secret
```

### 3. إعداد قاعدة البيانات
```bash
# تشغيل الهجرات
php artisan migrate

# ملء قاعدة البيانات (اختياري)
php artisan db:seed
```

### 4. تحسين الأداء
```bash
# مسح الكاش
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# تحسين الأداء
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 5. إعداد الأذونات
```bash
# إعطاء أذونات الكتابة
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

### 6. إعداد Web Server

#### Apache (.htaccess موجود)
```apache
DocumentRoot /path/to/your/project/public
```

#### Nginx
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/your/project/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## متغيرات البيئة المطلوبة

### أساسية
- `APP_NAME` - اسم التطبيق
- `APP_ENV` - بيئة التشغيل (production)
- `APP_KEY` - مفتاح التشفير
- `APP_URL` - رابط التطبيق
- `APP_DEBUG` - وضع التصحيح (false)

### قاعدة البيانات
- `DB_CONNECTION` - نوع قاعدة البيانات (mysql)
- `DB_HOST` - عنوان الخادم
- `DB_PORT` - المنفذ (3306)
- `DB_DATABASE` - اسم قاعدة البيانات
- `DB_USERNAME` - اسم المستخدم
- `DB_PASSWORD` - كلمة المرور

### JWT
- `JWT_SECRET` - مفتاح JWT
- `JWT_TTL` - مدة صلاحية التوكن (60 دقيقة)
- `JWT_REFRESH_TTL` - مدة تجديد التوكن (20160 دقيقة)

### البريد الإلكتروني
- `MAIL_MAILER` - نوع البريد (smtp)
- `MAIL_HOST` - خادم البريد
- `MAIL_PORT` - منفذ البريد
- `MAIL_USERNAME` - اسم المستخدم
- `MAIL_PASSWORD` - كلمة المرور
- `MAIL_ENCRYPTION` - نوع التشفير
- `MAIL_FROM_ADDRESS` - عنوان المرسل
- `MAIL_FROM_NAME` - اسم المرسل

## اختبار النشر

### 1. اختبار الاتصال
```bash
curl -X GET https://your-domain.com/api/health
```

### 2. اختبار التسجيل
```bash
curl -X POST https://your-domain.com/api/register \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com","password":"password","password_confirmation":"password"}'
```

### 3. اختبار تسجيل الدخول
```bash
curl -X POST https://your-domain.com/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com","password":"password"}'
```

## استكشاف الأخطاء

### مشاكل شائعة
1. **خطأ 500**: تحقق من أذونات الملفات
2. **خطأ قاعدة البيانات**: تحقق من إعدادات الاتصال
3. **خطأ CORS**: تحقق من إعدادات CORS
4. **خطأ JWT**: تحقق من JWT_SECRET

### سجلات الأخطاء
```bash
# عرض سجلات Laravel
tail -f storage/logs/laravel.log

# عرض سجلات Apache
tail -f /var/log/apache2/error.log

# عرض سجلات Nginx
tail -f /var/log/nginx/error.log
```

## الأمان

### إعدادات مهمة
- تأكد من أن `APP_DEBUG=false` في الإنتاج
- استخدم HTTPS
- قم بتحديث كلمات المرور القوية
- احتفظ بنسخ احتياطية لقاعدة البيانات
- راقب سجلات الأخطاء بانتظام

### تحديثات
```bash
# تحديث التطبيق
git pull origin main
composer install --optimize-autoloader --no-dev
php artisan migrate
php artisan config:cache
php artisan route:cache
php artisan view:cache
``` 