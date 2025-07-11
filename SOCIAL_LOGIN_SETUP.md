# دليل إعداد تسجيل الدخول بالوسائط الاجتماعية

## 1. إعداد Google OAuth

### الخطوات:
1. اذهب إلى: https://console.cloud.google.com/
2. سجل دخول بحساب Google
3. أنشئ مشروع جديد أو اختر مشروع موجود
4. اذهب إلى "APIs & Services" > "Library"
5. ابحث عن "Google+ API" أو "Google Identity" وفعله
6. اذهب إلى "APIs & Services" > "Credentials"
7. اضغط "Create Credentials" > "OAuth 2.0 Client IDs"
8. اختر "Web application"
9. أضف redirect URIs:
   ```
   http://localhost:8000/auth/google/callback
   http://your-domain.com/auth/google/callback
   ```

### النتيجة:
- Client ID: `123456789-abcdefghijklmnop.apps.googleusercontent.com`
- Client Secret: `GOCSPX-abcdefghijklmnopqrstuvwxyz`

---

## 2. إعداد Facebook OAuth

### الخطوات:
1. اذهب إلى: https://developers.facebook.com/
2. سجل دخول بحساب Facebook
3. اضغط "Create App" > "Consumer"
4. أدخل اسم التطبيق: "Arrive Alive"
5. اذهب إلى "Add Product" > "Facebook Login"
6. اختر "Web" كمنصة
7. اذهب إلى "Facebook Login" > "Settings"
8. أضف Valid OAuth Redirect URIs:
   ```
   http://localhost:8000/auth/facebook/callback
   http://your-domain.com/auth/facebook/callback
   ```

### النتيجة:
- App ID: `123456789012345`
- App Secret: `abcdef123456789abcdef123456789ab`

---

## 3. إعداد Twitter OAuth

### الخطوات:
1. اذهب إلى: https://developer.twitter.com/
2. سجل دخول بحساب Twitter
3. اذهب إلى "Developer Portal"
4. أنشئ App جديد
5. اذهب إلى "App settings" > "Authentication settings"
6. فعّل "OAuth 1.0a"
7. أضف Callback URLs:
   ```
   http://localhost:8000/auth/twitter/callback
   http://your-domain.com/auth/twitter/callback
   ```

### النتيجة:
- API Key: `your_twitter_api_key`
- API Secret: `your_twitter_api_secret`

---

## 4. إعداد ملف .env

أضف هذه المتغيرات في ملف `.env`:

```env
# Google OAuth
GOOGLE_CLIENT_ID=your_google_client_id_here
GOOGLE_CLIENT_SECRET=your_google_client_secret_here
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback

# Facebook OAuth
FACEBOOK_CLIENT_ID=your_facebook_app_id_here
FACEBOOK_CLIENT_SECRET=your_facebook_app_secret_here
FACEBOOK_REDIRECT_URI=http://localhost:8000/auth/facebook/callback

# Twitter OAuth
TWITTER_CLIENT_ID=your_twitter_client_id_here
TWITTER_CLIENT_SECRET=your_twitter_client_secret_here
TWITTER_REDIRECT_URI=http://localhost:8000/auth/twitter/callback
```

---

## 5. اختبار الـ API

### اختبار Google:
```
GET http://localhost:8000/api/auth/google/redirect
```

### اختبار Facebook:
```
GET http://localhost:8000/api/auth/facebook/redirect
```

### اختبار Twitter:
```
GET http://localhost:8000/api/auth/twitter/redirect
```

---

## ملاحظات مهمة:

1. **احتفظ بالمفاتيح سراً** - لا تشارك Client Secret أو App Secret
2. **استخدم HTTPS في الإنتاج** - غير redirect URIs إلى https://
3. **أضف جميع النطاقات** - أضف localhost والإنتاج
4. **راجع إعدادات التطبيق** - قد تحتاج مراجعة من المنصات في الإنتاج

---

## استكشاف الأخطاء:

### مشكلة: "Invalid redirect URI"
- تأكد من إضافة redirect URI بالضبط في إعدادات التطبيق
- تأكد من تطابق النطاق والمسار

### مشكلة: "App not verified"
- في وضع التطوير، أضف بريدك الإلكتروني كـ test user
- في الإنتاج، ستحتاج مراجعة من المنصة

### مشكلة: "Missing required parameter"
- تأكد من إضافة جميع المتغيرات في ملف .env
- أعد تشغيل الخادم بعد تغيير .env 