<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Task;
use App\Models\CompanyOrder;
use App\Models\Driver;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    // عرض قائمة الوجهات مع دعم فلترة بالبحث عن طريق السائق، المهمة، أو طلبات الشركة
    public function index(Request $request)
    {
        $query = Destination::query()->with(['task.driver', 'companyOrder.company']);

        // فلترة حسب السائق
        if ($request->filled('driver_id')) {
            $driverId = $request->driver_id;
            $query->whereHas('task', function ($q) use ($driverId) {
                $q->where('driver_id', $driverId);
            });
        }

        // فلترة حسب المهمة
        if ($request->filled('task_id')) {
            $query->where('task_id', $request->task_id);
        }

        // فلترة حسب طلبات الشركة
        if ($request->filled('company_order_id')) {
            $query->where('company_order_id', $request->company_order_id);
        }

        $destinations = $query->orderBy('date', 'desc')->paginate(15);

        // لجلب بيانات السائقين والمهام وطلبات الشركات للفلترة في الواجهة
        $drivers = Driver::all();
        $tasks = Task::all();
        $companyOrders = CompanyOrder::all();

        return view('admin.destinations.index', compact('destinations', 'drivers', 'tasks', 'companyOrders'));
    }

    // صفحة إنشاء وجهة جديدة
   public function create()
{
    $tasks = Task::with('driver.company')->get(); // تحميل السائق والشركة المرتبطة بكل مهمة
    $drivers = Driver::with('company')->get(); // لو أردت استخدام السائقين بشكل مستقل
    $companyOrders = collect(); // سنملأها ديناميكياً عبر JavaScript بعد اختيار المهمة

    return view('admin.destinations.create', compact('tasks', 'drivers', 'companyOrders'));
}


    // حفظ وجهة جديدة
   public function store(Request $request)
{
    $validated = $request->validate([
        'start_point' => 'required|string|max:255',
        'start_latitude' => 'required|numeric',
        'start_longitude' => 'required|numeric',
        'end_point' => 'required|string|max:255',
        'end_address' => 'required|string|max:255',
        'date' => 'required|date',
        'task_id' => 'required|exists:tasks,id',
        'company_order_id' => 'required|exists:company_orders,id',
    ]);

    Destination::create($validated);

    return redirect()->route('admin.destinations.index')
        ->with('success', 'تم إضافة الوجهة بنجاح.');
}


    // صفحة تعديل وجهة موجودة
public function edit(Destination $destination)
{
    $tasks = Task::with('driver.company')->get();

    // هنا نمرر كل طلبات الشركة (أو ممكن نمرر فقط طلبات الشركة الخاصة بالسائق المرتبط بالوجهة لو موجود)
    $companyOrders = CompanyOrder::all();

    // تمرير company_order_id المختارة (اختياري، فقط لتسهيل الوصول في Blade)
    $selectedCompanyOrderId = old('company_order_id', $destination->company_order_id ?? null);

    return view('admin.destinations.edit', compact('destination', 'tasks', 'companyOrders', 'selectedCompanyOrderId'));
}


// تحديث وجهة موجودة بنفس قواعد التحقق مثل دالة store
public function update(Request $request, Destination $destination)
{
    $validated = $request->validate([
        'start_point' => 'required|string|max:255',
        'start_latitude' => 'required|numeric',
        'start_longitude' => 'required|numeric',
        'end_point' => 'required|string|max:255',
        'end_address' => 'required|string|max:255',
        'date' => 'required|date',
        'task_id' => 'required|exists:tasks,id',
        'company_order_id' => 'required|exists:company_orders,id',
    ]);

    $destination->update($validated);

    return redirect()->route('admin.destinations.index')
        ->with('success', 'تم تحديث الوجهة بنجاح.');
}


    // عرض تفاصيل وجهة معينة
    public function show(Destination $destination)
    {
        $destination->load(['task.driver', 'companyOrder.company']);
        return view('admin.destinations.show', compact('destination'));
    }

    // حذف وجهة
    public function destroy(Destination $destination)
    {
        $destination->delete();
        return redirect()->route('admin.destinations.index')
            ->with('success', 'تم حذف الوجهة بنجاح.');
    }

    public function getCompanyOrdersByDriver($driverId)
{
    // جلب السائق مع الشركة المرتبطة به
    $driver = Driver::with('company')->findOrFail($driverId);

    if (!$driver->company) {
        return response()->json([]);
    }

    // جلب طلبات الشركة المرتبطة بالسائق مع الطلب والمنتجات
    $companyOrders = CompanyOrder::with(['order.items.product'])
        ->where('company_id', $driver->company->id)
        ->get();

    // بناء مصفوفة للرد بحيث تحتوي فقط على طلبات الشركة والمنتجات الخاصة بها
    $result = $companyOrders->map(function ($companyOrder) {
        // فلترة المنتجات الخاصة بالشركة فقط
        $filteredItems = $companyOrder->order->items->filter(function ($item) use ($companyOrder) {
            return $item->product && $item->product->company_id == $companyOrder->company_id;
        });

        return [
            'id' => $companyOrder->id,
            'order_id' => $companyOrder->order_id,
            'company_name' => $companyOrder->company->company_name,
            'status' => $companyOrder->status,
            'total_amount' => $companyOrder->total_amount,
            'products_count' => $filteredItems->count(),
            // يمكنك إضافة حقول أخرى حسب الحاجة
        ];
    });

    return response()->json($result);
}

}
