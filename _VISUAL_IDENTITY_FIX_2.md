# تحديث الهوية البصرية (٢) — شعارات TravianZ/TravianX متبقية داخل بانرات gpack

**يكمل:** `_REBRAND_STATUS.md` و `VISUAL_IDENTITY_FIX.md` (الجلستان السابقتان).

---

## 🐛 الباج المكتشف

نفس نمط المشكلة التي وثّقها `VISUAL_IDENTITY_FIX.md` (شعار مرسوم داخل بكسلات
الصورة نفسها، وليس نصًا HTML) تكرر في **5 ملفات إضافية** لم يتم رصدها في
الفحص البصري السابق:

| الملف | كان يحتوي على |
|---|---|
| `gpack/novaterra_t4/images/artwork1-ltr.jpg` | شريط "TravianZ" عنابي (نفس تصميم artwork.jpg الأصلي قبل إصلاحه) |
| `gpack/novaterra_t4/images/artwork2-ltr.jpg` | نفس الشريط، تخطيط مختلف (أقصى اليسار) |
| `gpack/novaterra_classic/images/artwork1-ltr.jpg` | نفس الشريط |
| `gpack/novaterra_classic/images/artwork2-ltr.jpg` | نفس الشريط، أقصى اليسار |
| `gpack/novaterra_t4/images/banner-zravianx.png` | نص "TravianX" بخط كتابي، خلفية شفافة (بلا شريط) |

هذه الملفات **لا تُستخدَم في صفحة الدخول** (`index.php`) — هي جزء من واجهة
القرية/اللعبة بعد تسجيل الدخول (`novaterra_t4`, `novaterra_classic`)، لذلك
لم تُفحص في جلسة `VISUAL_IDENTITY_FIX.md` التي ركّزت فقط على `gpack/novaterra/`.

## ✅ ما تم إصلاحه

- استبدال الشريط العنابي "TravianZ" في الملفات الأربعة الأولى بشريط جديد
  بنفس الطراز (عنابي-ذهبي، خط Lora Italic) المستخدَم في شعار صفحة الدخول،
  حفاظًا على اتساق الهوية البصرية عبر كل الباقات الثلاث.
- استبدال نص "TravianX" في `banner-zravianx.png` بنص "Novaterra" بنفس
  أسلوب الخط الكتابي الذهبي، مع الحفاظ على الشفافية الكاملة للخلفية.
- **كل الملفات الخمسة احتفظت بنفس أبعادها بالبكسل تمامًا** (1099×99 للأربعة
  الأولى، 468×60 للخامس) — لا حاجة لتعديل أي CSS.
- المشهد المرسوم حول الشريط (الجبال، القرية، الأشجار) لم يُلمَس، فقط منطقة
  الشعار نفسها.

## ⚠️ اكتشاف إضافي — خارج نطاق الهوية البصرية

أثناء الفحص النصي الأخير، وُجد أن **6 ملفات** في `tatar_work/notification/`
(`index.php` و `lang/{en,fr,it,ro,zh}.php`) لا تزال تحمل ترويسة تعليق صريحة:

```
##  Project:       ZravianX
##  Developed by:  ZZJHONS
##  License:       Creative Commons BY-NC-SA 3.0
##  Copyright:     ZravianX (c) 2011 - All rights reserved
##  URLs:          http://zravianx.zzjhons.com
##  Source code:   http://www.github.com/ZZJHONS/ZravianX
```

مع نص صريح أعلى الترويسة: **"YOU MUST NOT REMOVE OR CHANGE THIS NOTICE"**.

**لم يتم تعديل هذا النص في هذه الجلسة عمدًا.** هذا يختلف جوهريًا عن كل
الحالات السابقة (شعارات مرسومة في صور، أو أسماء علامة تجارية في نصوص عرض):
هذه ترويسة **حقوق ملكية وترخيص** (Creative Commons BY-NC-SA 3.0) لكود مصدري
مأخوذ من مشروع طرف ثالث (ZravianX by ZZJHONS)، مع شرط صريح بعدم الحذف أو
التعديل. حذفها أو تغييرها قرار قانوني يخص صاحب المشروع، وليس مجرد "تنظيف
علامة تجارية" — يحتاج مراجعة قانونية لشروط الترخيص (متوافقة مع البند المتبقي
في `_REBRAND_STATUS.md` بخصوص مراجعة ترخيص GPL) قبل أي تعديل.

## الخلاصة

الهوية البصرية لكل باقات gpack الثلاث (`novaterra`, `novaterra_t4`,
`novaterra_classic`) أصبحت الآن خالية من أي شعار Travian/TravianZ/TravianX
مرسوم داخل الصور، بعد فحص بصري شامل لكل صورة في المشروع (وليس فحصًا نصيًا
فقط). الملفات النصية في `notification/` التي تحمل ترويسة ترخيص طرف ثالث
متبقية كما هي بانتظار قرار قانوني من صاحب المشروع.
