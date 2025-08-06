<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\CompanyOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * عرض قائمة طلبات العميل
     */
   /**
 * عرض جميع طلبات العميل مع إمكانية الفلترة
 * GET /api/customer/orders
 */
public function index(Request $request)
{
    $customerId =  $request->customer_id;

    $orders = Order::with(['items.product', 'companyOrders.company'])
        ->where('customer_id', $customerId)
        ->when($request->status, function($query, $status) {
            return $query->where('status', $status);
        })
        ->when($request->from_date, function($query, $fromDate) {
            return $query->whereDate('order_date', '>=', $fromDate);
        })
        ->when($request->to_date, function($query, $toDate) {
            return $query->whereDate('order_date', '<=', $toDate);
        })
        ->orderBy('order_date', 'desc')
        ->paginate($request->per_page ?? 15);

    return response()->json([
        'success' => true,
        'data' => $orders
    ]);
}

    /**
     * عرض تفاصيل طلب معين للعميل
     */
    public function show(Order $order)
    {
        // التأكد من أن الطلب يخص العميل الحالي
        if ($order->customer_id != Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح بالوصول إلى هذا الطلب'
            ], 403);
        }

        $order->load(['items.product', 'companyOrders.company', 'payment']);

        return response()->json([
            'success' => true,
            'data' => $order
        ]);
    }

    /**
     * إنشاء طلب جديد من التطبيق
     */
    public function store(Request $request)
    {
        if (empty($request->products)) {
        return response()->json([
            'success' => false,
            'message' => 'يجب إضافة منتج واحد على الأقل'
        ], 422);
    }

    $validated = $request->validate([
        'customer_id' => 'required|exists:customers,id', // إضافة هذا الحقل
        'order_date' => 'required|date',
        'customer_location' => 'required|string|max:255',
        'products' => 'required|array|min:1',
        'products.*.product_id' => 'required|exists:products,id',
        'products.*.quantity' => 'required|integer|min:1',
        'products.*.price' => 'required|numeric|min:0'
    ]);

    DB::beginTransaction();

    try {
        // إنشاء الطلب الأساسي
        $order = Order::create([
            'customer_id' => $validated['customer_id'], // استخدام الـ ID المرسل
            'order_date' => $validated['order_date'],
            'customer_location' => $validated['customer_location'],
            'status' => 'قيد الانتظار',
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

            // تقسيم الطلب حسب الشركات
            $productsWithCompanies = Product::whereIn('id', collect($validated['products'])->pluck('product_id'))
                ->get()
                ->keyBy('id');

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
            }

            // إنشاء CompanyOrder لكل شركة
            foreach ($companyOrdersData as $companyId => $data) {
                CompanyOrder::create([
                    'company_id' => $companyId,
                    'order_id' => $order->id,
                    'status' => 'قيد الانتظار',
                    'total_amount' => $data['total_amount'],
                    'payment_id' => null,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء الطلب بنجاح',
                'data' => $order->load(['items.product', 'companyOrders'])
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إنشاء الطلب: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * إلغاء الطلب (للعميل فقط)
     */
    public function cancel(Order $order)
    {
        // التأكد من أن الطلب يخص العميل الحالي
        if ($order->customer_id != Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح بالوصول إلى هذا الطلب'
            ], 403);
        }

        // يمكن إلغاء الطلب فقط إذا كان في حالة "قيد الانتظار"
        if ($order->status !== 'قيد الانتظار') {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن إلغاء الطلب في حالته الحالية'
            ], 422);
        }

        DB::beginTransaction();

        try {
            // استعادة كميات المنتجات
            foreach ($order->items as $item) {
                $item->product->increment('quantity', $item->quantity);
            }

            // تحديث حالة الطلب
            $order->update(['status' => 'ملغى']);

            // تحديث حالة طلبات الشركات المرتبطة
            $order->companyOrders()->update(['status' => 'ملغى']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم إلغاء الطلب بنجاح'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إلغاء الطلب: ' . $e->getMessage()
            ], 500);
        }
    }
}
