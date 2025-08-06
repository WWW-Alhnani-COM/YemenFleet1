<?php

use App\Http\Controllers\Admin\AccidentController;
use App\Http\Controllers\Admin\AlertController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\CompanyOrderController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\CustomerReviewController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DestinationController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\DriverReportController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\OfferController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\OrdersReportController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SensorDataController;
use App\Http\Controllers\Admin\SubscriptionController;
// use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\ProfileController;
use App\Models\Truck;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Models\Driver;
Route::get('/', function () {
    return view('welcome');
});


Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    // Route::resource('companies', \App\Http\Controllers\Admin\CompanyController::class);
    // يمكنك إضافة مسارات أخرى هنا

     Route::get('/drivers', [DriverController::class, 'index'])->name('admin.drivers.index');
    Route::get('/drivers/create', [DriverController::class, 'create'])->name('admin.drivers.create');
     Route::post('/drivers', [DriverController::class, 'store'])->name('admin.drivers.store');

    // عرض تفاصيل سائق معين
    Route::get('/drivers/{driver}', [DriverController::class, 'show'])->name('admin.drivers.show');

    // عرض نموذج تعديل السائق
    Route::get('/drivers/{driver}/edit', [DriverController::class, 'edit'])->name('admin.drivers.edit');

    // تحديث بيانات السائق
    Route::put('/drivers/{driver}', [DriverController::class, 'update'])->name('admin.drivers.update');

    // حذف السائق (تعطيل الحساب)
    Route::delete('/drivers/{driver}', [DriverController::class, 'destroy'])->name('admin.drivers.destroy');

    // استعادة السائق المحذوف
    Route::patch('/drivers/{driver}/restore', [DriverController::class, 'restore'])->name('admin.drivers.restore');

  });

Route::resource('companies', CompanyController::class)->names('admin.companies');


// الطرق الصحيحة لروتات الاشتراك
Route::prefix('admin/subscriptions')->group(function () {
    // عرض جميع الاشتراكات
    Route::get('/', [SubscriptionController::class, 'index'])->name('admin.subscriptions.index');

    // عرض نموذج إنشاء اشتراك جديد
    Route::get('/create', [SubscriptionController::class, 'create'])->name('admin.subscriptions.create');

    // حفظ الاشتراك الجديد
    Route::post('/', [SubscriptionController::class, 'store'])->name('admin.subscriptions.store');

    // عرض تفاصيل اشتراك معين
    Route::get('/{subscription}', [SubscriptionController::class, 'show'])->name('admin.subscriptions.show');

    // عرض نموذج تعديل اشتراك
    Route::get('/{subscription}/edit', [SubscriptionController::class, 'edit'])->name('admin.subscriptions.edit');

    // تحديث بيانات الاشتراك
    Route::put('/{subscription}', [SubscriptionController::class, 'update'])->name('admin.subscriptions.update');

    // حذف اشتراك
    Route::delete('/{subscription}', [SubscriptionController::class, 'destroy'])->name('admin.subscriptions.destroy');

    // تجديد الاشتراك
    Route::post('/{subscription}/renew', [SubscriptionController::class, 'renew'])->name('admin.subscriptions.renew');
});


Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified'])->group(function () {
    Route::resource('payments', \App\Http\Controllers\Admin\PaymentController::class);
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified'])->group(function () {
    // ... طرق أخرى

    // طرق إدارة الشاحنات
    Route::resource('trucks', \App\Http\Controllers\Admin\TruckController::class);
});

// use App\Http\Controllers\Admin\CustomerController;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified'])->group(function () {
    // روات إدارة العملاء
    Route::resource('customers', CustomerController::class);

    // الروات الإضافية
    Route::post('customers/{customer}/toggle-status', [CustomerController::class, 'toggleStatus'])
         ->name('customers.toggle-status');

    Route::get('customers/{customer}/orders', [CustomerController::class, 'orders'])
         ->name('customers.orders');
});

// routes/web.php
Route::prefix('admin/products')->name('admin.products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/create', [ProductController::class, 'create'])->name('create');
    Route::post('/', [ProductController::class, 'store'])->name('store');

    // ✅ عدّل هنا إلى المفرد: {product}
    Route::get('/{product}', [ProductController::class, 'show'])->name('show');
    Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit');
    Route::put('/{product}', [ProductController::class, 'update'])->name('update');
    Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');
});



Route::prefix('admin/orders')->group(function() {
    Route::get('/', [OrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/create', [OrderController::class, 'create'])->name('admin.orders.create');
    Route::post('/', [OrderController::class, 'store'])->name('admin.orders.store');
    Route::get('/{order}', [OrderController::class, 'show'])->name('admin.orders.show');
    Route::get('/{order}/edit', [OrderController::class, 'edit'])->name('admin.orders.edit');
    Route::put('/{order}', [OrderController::class, 'update'])->name('admin.orders.update');
    Route::delete('/{order}', [OrderController::class, 'destroy'])->name('admin.orders.destroy');
    Route::post('/{order}/update-status', [OrderController::class, 'updateStatus'])->name('admin.orders.update-status');
});

Route::prefix('admin/categories')->name('admin.categories.')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('index');
    Route::get('/create', [CategoryController::class, 'create'])->name('create');
    Route::post('/', [CategoryController::class, 'store'])->name('store');
    Route::get('/{category}', [CategoryController::class, 'show'])->name('show');
    Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
    Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
    Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
});


Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified'])->group(function () {
    // ... روابط أخرى
//      Route::get('company/{company}/trucks', function (\App\Models\Company $company) {
//         return response()->json($company->trucks()->select('id', 'truck_name')->get());
//     });
//    Route::get('company/{company}/trucks', function (\App\Models\Company $company) {
//         return response()->json($company->trucks()->select('id', 'truck_name')->get());
//     });
    // روابط إدارة الحساسات
    Route::get('sensors', [\App\Http\Controllers\Admin\SensorController::class, 'index'])->name('sensors.index');
    Route::get('sensors/create', [\App\Http\Controllers\Admin\SensorController::class, 'create'])->name('sensors.create');
    Route::post('sensors', [\App\Http\Controllers\Admin\SensorController::class, 'store'])->name('sensors.store');
    Route::get('sensors/{sensor}/edit', [\App\Http\Controllers\Admin\SensorController::class, 'edit'])->name('sensors.edit');
    Route::put('sensors/{sensor}', [\App\Http\Controllers\Admin\SensorController::class, 'update'])->name('sensors.update');
    Route::delete('sensors/{sensor}', [\App\Http\Controllers\Admin\SensorController::class, 'destroy'])->name('sensors.destroy');
    Route::get('sensors/{sensor}', [\App\Http\Controllers\Admin\SensorController::class, 'show'])->name('sensors.show');
});

 Route::get('/admin/companies/{company}/trucks', [\App\Http\Controllers\Admin\SensorController::class, 'getCompanyTrucks']);

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::resource('sensor_data', \App\Http\Controllers\Admin\SensorDataController::class);
    Route::resource('notifications', \App\Http\Controllers\Admin\NotificationController::class);
});

Route::post('admin/notifications', [NotificationController::class, 'store'])->name('admin.notifications.store');


Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('company-orders', \App\Http\Controllers\Admin\CompanyOrderController::class)
        ->except(['edit', 'update', 'destroy']);

    Route::post('company-orders/{companyOrder}/link-payment',
        [\App\Http\Controllers\Admin\CompanyOrderController::class, 'linkPayment'])
        ->name('company-orders.link-payment');

    Route::post('company-orders/{companyOrder}/update-status',
        [\App\Http\Controllers\Admin\CompanyOrderController::class, 'updateStatus'])
        ->name('company-orders.update-status');

    Route::get('company-orders/report',
        [\App\Http\Controllers\Admin\CompanyOrderController::class, 'report'])
        ->name('company-orders.report');


});
Route::get('admin/company-orders/{id}', [CompanyOrderController::class, 'show'])->name('admin.company-orders.show');



// مجموعة روتات لوحة التحكم - الأدمن
Route::prefix('admin')->name('admin.')->group(function () {



    // روتات إدارة عروض المنتجات
    Route::prefix('offers')->name('offers.')->group(function () {
        Route::get('/', [OfferController::class, 'index'])->name('index');            // عرض العروض
        Route::get('/create', [OfferController::class, 'create'])->name('create');    // إنشاء عرض جديد
        Route::post('/', [OfferController::class, 'store'])->name('store');           // حفظ العرض
        Route::get('/{offer}/edit', [OfferController::class, 'edit'])->name('edit');  // تعديل العرض
        Route::put('/{offer}', [OfferController::class, 'update'])->name('update');   // تحديث العرض
        Route::delete('/{offer}', [OfferController::class, 'destroy'])->name('destroy'); // حذف العرض
        Route::get('/{offer}', [OfferController::class, 'show'])->name('show');       // عرض تفاصيل العرض
    });

});

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::resource('tasks', \App\Http\Controllers\Admin\TaskController::class);
});


Route::resource('destinations', \App\Http\Controllers\Admin\DestinationController::class)->names('admin.destinations');



Route::get('/admin/api/company-orders-by-driver/{driver}', [DestinationController::class, 'getCompanyOrdersByDriver']);


Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::resource('accidents', AccidentController::class);

     Route::resource('shipments', \App\Http\Controllers\Admin\ShipmentController::class);
    Route::post('shipments/{shipment}/update-status', [\App\Http\Controllers\Admin\ShipmentController::class, 'updateStatus'])
         ->name('shipments.update-status');


    Route::resource('maintenance', \App\Http\Controllers\Admin\MaintenanceController::class);

    // إذا أردت إضافة روت منفصل لإنشاء الفاتورة

});
Route::post('maintenance/{maintenance}/invoice',
        [\App\Http\Controllers\Admin\MaintenanceController::class, 'generateInvoice'])
        ->name('admin.maintenance.generate-invoice');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('invoices', InvoiceController::class);


    Route::resource('customer_reviews', CustomerReviewController::class);

     Route::resource('alerts', \App\Http\Controllers\Admin\AlertController::class)->except(['create', 'store', 'edit', 'update']);

       Route::post('alerts/{alert}/resolve', [AlertController::class, 'resolve'])->name('alerts.resolve');


});




Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('orders-report', [OrdersReportController::class, 'index'])->name('orders-report.index');

     Route::get('reports/drivers', [App\Http\Controllers\Admin\DriverReportController::class, 'index'])->name('reports.drivers.index');
     Route::get('reports/maintenance', [DriverReportController::class, 'maintenanceReport'])->name('reports.maintenance');

});

// Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function() {

// });

// Route::post('/gps/fetch', [SensorDataController::class, 'fetchFromGpsAndStore']);

Route::get('/gps/fetch', [SensorDataController::class, 'fetchFromGpsAndStore']);
require __DIR__.'/auth.php';
