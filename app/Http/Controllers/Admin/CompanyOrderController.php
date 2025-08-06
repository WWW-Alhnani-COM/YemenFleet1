<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyOrder;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

// الان العميل لدية اكثر من طلب 
// وكل طلب فية اكثر منتج المنتجات هية خاصة ب عدة شركات يعني الشركة الواحدة لديها
//  اكثر من منتج الغرض من طلبات الشركة هو عرض الطلبات التي فيها منتجات خاصة بها
class CompanyOrderController extends Controller
{
    /**
     * عرض قائمة طلبات الشركات مع إمكانية الفلترة
     */
   public function index(Request $request)
{
    // $query = CompanyOrder::with(['company', 'order.customer', 'payment', 'items.product'])->latest();

    // جلب كل الشركات لأغراض الفلترة في الواجهة
    $companies = Company::all();

    // بناء الاستعلام الأساسي مع العلاقات الضرورية
    $query = CompanyOrder::with([
        'company',
        'order.customer',
        'payment',
        'items.product'
    ])->latest();

    // فلترة حسب حالة الطلب
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // فلترة حسب الشركة (حسب المنتجات الخاصة بها)
 if ($request->filled('company_id')) {
    $query->where('company_id', $request->company_id);
}


    // فلترة بالتاريخ
    if ($request->filled('start_date')) {
        $query->whereDate('created_at', '>=', $request->start_date);
    }
    if ($request->filled('end_date')) {
        $query->whereDate('created_at', '<=', $request->end_date);
    }

    // فلترة حسب حالة الدفع
    if ($request->filled('payment_status')) {
        if ($request->payment_status == 'paid') {
            $query->whereHas('payment', fn($q) => $q->where('status', 'paid'));
        } elseif ($request->payment_status == 'unpaid') {
            $query->where(function ($q) {
                $q->whereDoesntHave('payment')
                  ->orWhereHas('payment', fn($q2) => $q2->where('status', 'unpaid'));
            });
        } elseif ($request->payment_status == 'partial') {
            $query->whereHas('payment', fn($q) => $q->where('status', 'partial'));
        }
    }

    // فلترة حسب المبلغ
    if ($request->filled('min_amount')) {
        $query->where('total_amount', '>=', $request->min_amount);
    }
    if ($request->filled('max_amount')) {
        $query->where('total_amount', '<=', $request->max_amount);
    }

    // تنفيذ الاستعلام مع التصفح
    $companyOrders = $query->paginate(15);

    // احصائيات عامة (لكل الطلبات وليس فقط المفلترة)
    $totalOrders = CompanyOrder::count();
    $paidOrders = CompanyOrder::whereHas('payment', fn($q) => $q->where('status', 'paid'))->count();
    $unpaidOrders = CompanyOrder::where(function ($q) {
        $q->whereDoesntHave('payment')
          ->orWhereHas('payment', fn($q2) => $q2->where('status', 'unpaid'));
    })->count();
    $completedOrders = CompanyOrder::where('status', 'completed')->count();
    $processingOrders = CompanyOrder::where('status', 'processing')->count();
    $canceledOrders = CompanyOrder::where('status', 'canceled')->count();

    // تمرير البيانات إلى الواجهة
    return view('admin.company-orders.index', compact(
        'companyOrders',
        'companies',
        'totalOrders',
        'paidOrders',
        'unpaidOrders',
        'completedOrders',
        'processingOrders',
        'canceledOrders'
    ));
}



    /**
     * عرض نموذج إنشاء طلب شركة جديد
     */
    public function create()
    {
        $orders = Order::with('items.product')->whereDoesntHave('companyOrders')->get();
        $companies = Company::all();
        
        return view('admin.company-orders.create', compact('orders', 'companies'));
    }

    /**
     * حفظ طلب الشركة الجديد
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,order_id',
            'company_id' => 'required|exists:companies,company_id',
            'total_amount' => 'required|numeric|min:0',
        ]);

        // حساب المبلغ الإجمالي بناء على منتجات الشركة في الطلب
        $order = Order::with(['items.product'])->findOrFail($request->order_id);
        $validated['total_amount'] = $order->items
            ->where('product.company_id', $request->company_id)
            ->sum(function($item) {
                return $item->quantity * $item->price;
            });

        $companyOrder = CompanyOrder::create([
            'order_id' => $validated['order_id'],
            'company_id' => $validated['company_id'],
            'total_amount' => $validated['total_amount'],
            'status' => 'pending'
        ]);

        return redirect()->route('admin.company-orders.show', $companyOrder->company_order_id)
            ->with('success', 'تم إنشاء طلب الشركة بنجاح');
    }

    /**
     * عرض تفاصيل طلب الشركة
     */
public function show($id)
{
    $companyOrder = CompanyOrder::with([
        'order.items.product',
        'company',
        'payment',
        'order.customer'
    ])->findOrFail($id);

    // فلترة المنتجات الخاصة بالشركة
    $filteredItems = $companyOrder->order->items->filter(function ($item) use ($companyOrder) {
        return $item->product && $item->product->company_id == $companyOrder->company_id;
    });

    return view('admin.company-orders.show', compact('companyOrder', 'filteredItems'));
}



    /**
     * ربط دفعة بطلب الشركة
     */
    public function linkPayment(Request $request, CompanyOrder $companyOrder)
    {
        $request->validate([
            'payment_id' => 'required|exists:payments,payment_id'
        ]);

        $payment = Payment::findOrFail($request->payment_id);
        
        if ($companyOrder->linkPayment($payment)) {
            return back()->with('success', 'تم ربط الدفعة بنجاح');
        }

        return back()->with('error', 'حدث خطأ أثناء ربط الدفعة');
    }

    /**
     * تحديث حالة طلب الشركة
     */
    public function updateStatus(Request $request, CompanyOrder $companyOrder)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,canceled'
        ]);

        $companyOrder->update(['status' => $request->status]);

        return back()->with('success', 'تم تحديث حالة الطلب بنجاح');
    }

    /**
     * عرض تقرير طلبات الشركة
     */
    public function report(Request $request)
    {
        $query = CompanyOrder::with(['company', 'order'])
            ->selectRaw('company_id, COUNT(*) as total_orders, SUM(total_amount) as total_revenue')
            ->groupBy('company_id');

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $report = $query->get();
        $companies = Company::all();

        return view('admin.company-orders.report', compact('report', 'companies'));
    }
}