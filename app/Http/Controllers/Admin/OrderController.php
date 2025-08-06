<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyOrder;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * عرض قائمة الطلبات مع إمكانية الفلترة
     */
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'items.product'])
            ->orderBy('order_date', 'desc');

        // فلترة حسب حالة الطلب
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // فلترة حسب العميل
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        // فلترة حسب التاريخ من
        if ($request->filled('from_date')) {
            $query->whereDate('order_date', '>=', $request->from_date);
        }

        // فلترة حسب التاريخ إلى
        if ($request->filled('to_date')) {
            $query->whereDate('order_date', '<=', $request->to_date);
        }

        // فلترة حسب المبلغ الأدنى
        if ($request->filled('min_amount')) {
            $query->where('total_price', '>=', $request->min_amount);
        }

        // فلترة حسب المبلغ الأعلى
        if ($request->filled('max_amount')) {
            $query->where('total_price', '<=', $request->max_amount);
        }

        $orders = $query->paginate(15);
        $customers = Customer::all();

        return view('admin.orders.index', compact('orders', 'customers'));
    }

    /**
     * عرض نموذج إنشاء طلب جديد
     */
   public function create()
{
    $customers = Customer::all();
    $products = Product::where('quantity', '>', 0)->get();
    $initialProductCount = count(old('products', [])) ?: 1;
    
    return view('admin.orders.create', compact('customers', 'products', 'initialProductCount'));
}

    /**
     * حفظ طلب جديد في قاعدة البيانات
     */
    
public function store(Request $request)
{
    if(empty($request->products)) {
        return back()->with('error', 'يجب إضافة منتج واحد على الأقل');
    }

    $validated = $request->validate([
        'customer_id' => 'required|exists:customers,id',
        'orderDate' => 'required|date',
        'shippingAddress' => 'required|string|max:255',
        'status' => 'required|in:قيد الانتظار,قيد المعالجة,مكتمل,ملغى',
        'products' => 'required|array|min:1',
        'products.*.product_id' => 'required|exists:products,id',
        'products.*.quantity' => 'required|integer|min:1',
        'products.*.price' => 'required|numeric|min:0'
    ]);

    DB::beginTransaction();

    try {
        // إنشاء الطلب الأساسي
        $order = Order::create([
            'customer_id' => $validated['customer_id'],
            'order_date' => $validated['orderDate'],
            'customer_location' => $validated['shippingAddress'],
            'status' => $validated['status'],
            'total_price' => 0
        ]);

        $totalAmount = 0;

        // إنشاء العناصر المرتبطة بالطلب
        foreach ($validated['products'] as $product) {
            $order->items()->create([
                'product_id' => $product['product_id'],
                'quantity' => $product['quantity'],
                'price' => $product['price']
            ]);

            // تحديث كمية المنتج في المخزون
            Product::find($product['product_id'])->decrement('quantity', $product['quantity']);

            // حساب المبلغ الكلي للطلب الأساسي
            $totalAmount += $product['quantity'] * $product['price'];
        }

        // تحديث المبلغ الكلي للطلب الأساسي
        $order->update(['total_price' => $totalAmount]);

        // --- هنا نبدأ تقسيم الطلب حسب الشركات ---

        // 1. جلب معلومات المنتجات مع الشركة لكل منتج في الطلب
        $productsWithCompanies = Product::whereIn('id', collect($validated['products'])->pluck('product_id'))->get()->keyBy('id');

        // 2. تجميع المبلغ والكمية لكل شركة
        $companyOrdersData = [];

        foreach ($validated['products'] as $product) {
            $prod = $productsWithCompanies->get($product['product_id']);
            if (!$prod) {
                throw new \Exception("منتج برقم {$product['product_id']} غير موجود");
            }

            $companyId = $prod->company_id;
            $amount = $product['quantity'] * $product['price'];

            if (!isset($companyOrdersData[$companyId])) {
                $companyOrdersData[$companyId] = [
                    'total_amount' => 0,
                    'items' => [],
                ];
            }

            $companyOrdersData[$companyId]['total_amount'] += $amount;
            $companyOrdersData[$companyId]['items'][] = [
                'product_id' => $product['product_id'],
                'quantity' => $product['quantity'],
                'price' => $product['price']
            ];
        }

        // 3. إنشاء CompanyOrder لكل شركة
        foreach ($companyOrdersData as $companyId => $data) {
            $companyOrder = CompanyOrder::create([
                'company_id' => $companyId,
                'order_id' => $order->id,
                'status' => $validated['status'], // يمكنك تعديل الحالة إذا أردت
                'total_amount' => $data['total_amount'],
                'payment_id' => null, // لم يتم الدفع بعد
            ]);

            // إذا كان لديك جدول تفاصيل مثل order_items مرتبط بـ CompanyOrder، يمكن إنشاء هذه التفاصيل هنا أيضاً
            // لكن حسب نماذجك السابقة، غالباً تفاصيل الطلب مرتبطة بـ Order وليس CompanyOrder
            // لذلك لا نحتاج لإضافة عناصر هنا إلا إذا أردت بناء هيكل خاص
        }

        DB::commit();

        return redirect()->route('admin.orders.index')->with('success', 'تم إنشاء الطلب بنجاح');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withInput()->with('error', 'حدث خطأ أثناء إنشاء الطلب: ' . $e->getMessage());
    }
}

    /**
     * عرض تفاصيل طلب معين
     */
    public function show(Order $order)
    {
        $order->load(['customer', 'items.product', 'payment']);
        
        return view('admin.orders.show', compact('order'));
    }

    /**
     * عرض نموذج تعديل طلب
     */
    public function edit(Order $order)
    {
        // if ($order->status !== 'pending') {
        //     return back()->with('error', 'لا يمكن تعديل الطلب بعد تأكيده');
        // }

        $customers = Customer::all();
        $products = Product::where('quantity', '>', 0)->get();
        $order->load('items');
        
        return view('admin.orders.edit', compact('order', 'customers', 'products'));
    }

    /**
     * تحديث الطلب في قاعدة البيانات
     */
    public function update(Request $request, Order $order)
{
    if ($order->status !== 'قيد الانتظار') {
        return back()->with('error', 'لا يمكن تعديل الطلب بعد تأكيده');
    }

    $validated = $request->validate([
        'customer_id' => 'required|exists:customers,id',
        'orderDate' => 'required|date',
        'shippingAddress' => 'required|string|max:255',
        'status' => 'required|in:قيد الانتظار,قيد المعالجة,مكتمل,ملغى',
        'products' => 'required|array|min:1',
        'products.*.product_id' => 'required|exists:products,id',
        'products.*.quantity' => 'required|integer|min:1',
        'products.*.price' => 'required|numeric|min:0',
        'products.*.id' => 'nullable|exists:order_items,id' // للعناصر الموجودة
    ]);

    DB::beginTransaction();

    try {
        // 1. تحديث بيانات الطلب الأساسية
        $order->update([
            'customer_id' => $validated['customer_id'],
            'order_date' => $validated['orderDate'],
            'customer_location' => $validated['shippingAddress'],
            'status' => $validated['status']
        ]);

        // 2. الحصول على IDs للعناصر الحالية
        $currentItemIds = $order->items->pluck('id')->toArray();
        $updatedItemIds = [];
        $totalAmount = 0;

        // 3. معالجة المنتجات المحدثة/المضافة
        foreach ($validated['products'] as $productData) {
            if (isset($productData['id'])) {
                // تحديث عنصر موجود
                $orderItem = OrderItem::find($productData['id']);
                $product = Product::find($productData['product_id']);
                
                // استعادة الكمية القديمة
                $product->increment('quantity', $orderItem->quantity);
                
                // تحديث العنصر
                $orderItem->update([
                    'product_id' => $productData['product_id'],
                    'quantity' => $productData['quantity'],
                    'price' => $productData['price']
                ]);
                
                // خصم الكمية الجديدة
                $product->decrement('quantity', $productData['quantity']);
                
                $updatedItemIds[] = $productData['id'];
            } else {
                // إضافة عنصر جديد
                $product = Product::find($productData['product_id']);
                
                $order->items()->create([
                    'product_id' => $productData['product_id'],
                    'quantity' => $productData['quantity'],
                    'price' => $productData['price']
                ]);
                
                // خصم الكمية من المخزون
                $product->decrement('quantity', $productData['quantity']);
            }
            
            $totalAmount += $productData['quantity'] * $productData['price'];
        }

        // 4. حذف العناصر التي تم إزالتها
        $itemsToDelete = array_diff($currentItemIds, $updatedItemIds);
        if (!empty($itemsToDelete)) {
            foreach ($itemsToDelete as $itemId) {
                $item = OrderItem::find($itemId);
                // استعادة كمية المنتج
                $item->product->increment('quantity', $item->quantity);
                $item->delete();
            }
        }

        // 5. تحديث السعر الإجمالي
        $order->update(['total_price' => $totalAmount]);

        DB::commit();

        return redirect()->route('admin.orders.index')
            ->with('success', 'تم تحديث الطلب بنجاح');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withInput()
            ->with('error', 'حدث خطأ أثناء تحديث الطلب: ' . $e->getMessage());
    }
}


    /**
     * حذف طلب معين
     */
    public function destroy(Order $order)
    {
        if (!in_array($order->status, ['pending', 'cancelled'])) {
            return redirect()->route('admin.orders.index')
                ->with('error', 'لا يمكن حذف الطلب في حالته الحالية');
            // return back()->with('error', 'لا يمكن حذف الطلب في حالته الحالية');
        }

        DB::beginTransaction();

        try {
            // استعادة كميات المنتجات
            foreach ($order->items as $item) {
                $item->product->increment('quantity', $item->quantity);
            }

            // حذف الطلب (سيحذف العناصر تلقائياً بسبب onDelete('cascade'))
            $order->delete();

            DB::commit();

            return redirect()->route('admin.orders.index')
                ->with('success', 'تم حذف الطلب بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء حذف الطلب: ' . $e->getMessage());
        }
    }

    /**
     * تحديث حالة الطلب
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $order->updateStatus($validated['status']);

        return back()->with('success', 'تم تحديث حالة الطلب بنجاح');
    }
}