# 🖥️ Yemen Fleet - لوحة تحكم الأدمن

<p align="center">
  <img src="images/logo.png" width="400" alt="Yemen Fleet Admin Logo">
  <br>
  <img src="https://img.shields.io/badge/Version-1.0.0-brightgreen" alt="Version">
  <img src="https://img.shields.io/badge/Laravel-10.x-orange" alt="Laravel">
  <img src="https://img.shields.io/badge/License-MIT-blue" alt="License">
</p>

## 🌟 نظرة عامة

لوحة تحكم إدارة نظام **Yemen Fleet** تمكن المسؤولين من:
- 🔑 إدارة اشتراكات الشركات
- 👨‍💼 التحكم في حسابات الشركات
- 📊 مراقبة إحصائيات النظام
- ⚙️ ضبط إعدادات النظام العامة

## 📸 لقطات من لوحة الأدمن

<div align="center">
  <h3>لوحة التحكم الرئيسية للأدمن</h3>
  <img src="images/admin_dashboard.png" width="800" style="border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
  
  <h3 style="margin-top: 30px;">إدارة الشركات</h3>
  <table>
    <tr>
      <td><img src="images/companies_list.png" width="350" alt="قائمة الشركات"></td>
      <td><img src="images/company_details.png" width="350" alt="تفاصيل الشركة"></td>
    </tr>
    <tr>
      <td align="center">عرض جميع الشركات</td>
      <td align="center">تفاصيل الشركة</td>
    </tr>
  </table>
  
  <h3 style="margin-top: 30px;">إدارة الاشتراكات</h3>
  <img src="images/subscriptions.png" width="700" style="border-radius: 8px;">
</div>

## 🛠️ التقنيات المستخدمة

mermaid
graph TD
  A[Laravel 10] --> B[Livewire]
  A --> C[MySQL]
  D[AdminLTE 3] --> A
  A --> E[Chart.js]
  A --> F[SPATIE Permissions]
🔧 ميزات لوحة الأدمن
الميزة	الوصف
إدارة الشركات	إنشاء/تعديل/حذف حسابات الشركات
الاشتراكات	إدارة باقات الاشتراك وتجديدها
الإحصائيات	عرض إحصائيات النظام الكلية
إعدادات النظام	تعديل الإعدادات العامة للنظام
سجل الأحداث	تتبع جميع أنشطة المستخدمين
🚀 كيفية التنصيب
bash
# استنساخ المستودع
git clone https://github.com/WWW-Alhnani-COM/YemenFleet1.git
cd YemenFleet1

# تثبيت الاعتمادات
composer install
npm install
cp .env.example .env
php artisan key:generate

# تنفيذ الهجرة
php artisan migrate --seed

# تشغيل الخادم
php artisan serve
📊 هيكل قاعدة البيانات
Diagram
Code









📞 الدعم الفني
<p align="center"> <a href="mailto:muhammadalhnani2004@gmail.com"> <img src="https://img.shields.io/badge/Email-support%40yemenfleet.com-blue?style=for-the-badge&logo=gmail"> </a> <a href="tel:+967711447801"> <img src="https://img.shields.io/badge/Phone-%2B967711447801-green?style=for-the-badge&logo=whatsapp"> </a> </p><div align="center" style="margin-top: 40px;"> <sub>تم تطوير نظام Yemen Fleet بواسطة <a href="https://github.com/WWW-Alhnani-COM" style="color: #2b7df8;">Mohammad Alhnani</a> © 2024</sub> </div> ```
