# دليل إعدادات رموز العملة SVG

## نظرة عامة

تم تطوير نظام العملة ليدعم رموز SVG مخصصة بدلاً من النصوص العادية، مما يوفر مظهراً أكثر احترافية ومرونة أكبر في التصميم.

## المميزات الجديدة

### 1. دعم رموز SVG

-   إمكانية إضافة رموز SVG مخصصة للعملات
-   المعاينة المباشرة قبل الحفظ
-   التبديل بين النص العادي و SVG

### 2. دوال جديدة

#### في ملف `Currency.php`:

-   `getCurrencySvgSymbol()` - الحصول على رمز SVG للعملة
-   `getCurrencySymbol()` - إرجاع رمز العملة (SVG أو نص)
-   `formatWithSvg()` - تنسيق السعر مع دعم SVG

#### في ملف `AppHelper.php`:

-   `format_money_with_svg()` - تنسيق المال مع SVG
-   `currency_svg_symbol()` - الحصول على رمز SVG
-   `currency_symbol($preferSvg)` - رمز العملة مع خيار SVG

### 3. صفحة إدارة العملة

-   مسار: `/admin/currency`
-   معاينة مباشرة للرموز
-   أمثلة جاهزة لرموز مختلفة
-   إمكانية مسح الرموز

## كيفية الاستخدام

### 1. الوصول لصفحة الإعدادات

انتقل إلى: `لوحة التحكم > Tools > Currency Settings`

### 2. إضافة رمز SVG

1. الصق كود SVG في النص المخصص
2. اضغط "Preview" للمعاينة
3. اضغط "Save SVG Symbol" للحفظ

### 3. استخدام في الكود

#### في Blade Templates:

```blade
{{-- للعرض مع SVG --}}
{!! format_money_with_svg($amount) !!}

{{-- للعرض بدون SVG --}}
{{ format_money_main($amount) }}

{{-- رمز العملة فقط --}}
{!! currency_svg_symbol() !!}
```

#### في PHP:

```php
// تنسيق مع SVG
$formatted = Currency::formatWithSvg(100.50);

// الحصول على رمز SVG
$svgSymbol = Currency::getCurrencySvgSymbol();

// رمز العملة (SVG أو نص)
$symbol = Currency::getCurrencySymbol(true); // true للـ SVG
```

## أمثلة رموز SVG

### الدرهم الإماراتي (AED):

```svg
<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
  <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm3 8h-2v2h2v2h-6v-2h2v-2H9v-2h6v2zm-3-3h-2v2h2V7z"/>
</svg>
```

### الدولار الأمريكي (USD):

```svg
<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
  <path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/>
</svg>
```

### اليورو (EUR):

```svg
<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
  <path d="M15 18.5c-2.51 0-4.68-.81-6.5-2.4h5.5v-2H6.31C6.11 13.46 6 12.74 6 12s.11-1.46.31-2.1H14v-2H8.5C10.32 6.31 12.49 5.5 15 5.5c1.61 0 3.09.59 4.23 1.57L21 5.3C19.41 3.87 17.3 3 15 3c-3.92 0-7.24 2.51-8.48 6H3v2h3.06c-.04.33-.06.66-.06 1s.02.67.06 1H3v2h3.52c1.24 3.49 4.56 6 8.48 6 2.31 0 4.41-.87 6-2.3l-1.77-1.77c-1.13.98-2.6 1.57-4.23 1.57z"/>
</svg>
```

## نصائح للتصميم

### 1. حجم الرمز:

-   استخدم `width="16" height="16"` للاستخدام العادي
-   استخدم `width="20" height="20"` للعناوين
-   أضف `style="vertical-align: middle;"` للمحاذاة

### 2. الألوان:

-   استخدم `fill="currentColor"` لوراثة لون النص
-   يمكنك استخدام ألوان محددة مثل `fill="#007bff"`

### 3. الاستجابة:

```svg
<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" style="vertical-align: middle;">
  <!-- المحتوى -->
</svg>
```

## استكشاف الأخطاء

### 1. الرمز لا يظهر:

-   تأكد من صحة كود SVG
-   تحقق من وجود `viewBox` في SVG
-   استخدم المعاينة أولاً

### 2. حجم خاطئ:

-   اضبط `width` و `height`
-   استخدم `style="vertical-align: middle;"`

### 3. اللون خاطئ:

-   استخدم `fill="currentColor"`
-   تأكد من عدم وجود ألوان ثابتة في المسارات

## التحديثات في قاعدة البيانات

تم إضافة إعداد جديد في جدول `core_settings`:

-   الاسم: `currency_symbol_svg`
-   القيمة: كود SVG الكامل للعملة

## الملفات المحدثة

1. `app/Currency.php` - دعم SVG
2. `app/Helpers/AppHelper.php` - دوال مساعدة جديدة
3. `modules/Core/Admin/CurrencyController.php` - تحكم الإدارة
4. `modules/Core/Views/admin/currency/index.blade.php` - واجهة الإدارة
5. `routes/admin.php` - مسارات جديدة
6. `modules/Layout/admin/parts/sidebar.blade.php` - قائمة الإدارة
7. `modules/Dashboard/Views/index.blade.php` - استخدام SVG

---

هذا النظام يوفر مرونة كاملة في تخصيص رموز العملات مع الحفاظ على التوافق مع النصوص العادية.
