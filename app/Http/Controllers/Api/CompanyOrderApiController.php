<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CompanyOrder;
use Illuminate\Http\Request;

class CompanyOrderApiController extends Controller
{
    public function index($companyId)
    {
        $orders = CompanyOrder::with([
            'order.customer',
            'items.product'
        ])
        ->where('company_id', $companyId)
        ->latest()
        ->get();

        return response()->json([
            'success' => true,
            'message' => 'Company orders fetched successfully',
            'data' => $orders
        ]);
    }

    public function show($id)
    {
        $order = CompanyOrder::with([
            'order.customer',
            'items.product'
        ])->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Company order details',
            'data' => $order
        ]);
    }


    public function statusSummary($companyId)
{
    $statuses = CompanyOrder::where('company_id', $companyId)
        ->selectRaw("status, COUNT(*) as count")
        ->groupBy('status')
        ->pluck('count', 'status');

    // تحويل الحقول إلى تنسيق ثابت
    return response()->json([
        'pending' => $statuses['قيد الانتظار'] ?? 0,
        'completed' => $statuses['مكتمل'] ?? 0,
        'cancelled' => $statuses['ملغى'] ?? 0,
    ]);
}

}
