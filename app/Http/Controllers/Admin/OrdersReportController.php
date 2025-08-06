<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrdersReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index(Request $request)
{
    $query = DB::table('company_orders')
        ->join('orders', 'company_orders.order_id', '=', 'orders.id')
        ->join('companies', 'company_orders.company_id', '=', 'companies.id')
        ->select(
            'companies.company_name as company_name',
            'company_orders.status',
            DB::raw('COUNT(company_orders.id) as total_orders'),
            DB::raw('SUM(company_orders.total_amount) as total_amount')
        )
        ->groupBy('companies.company_name', 'company_orders.status');

    if ($request->company_id) {
        $query->where('company_orders.company_id', $request->company_id);
    }

    if ($request->from_date) {
        $query->whereDate('orders.order_date', '>=', $request->from_date);
    }

    if ($request->to_date) {
        $query->whereDate('orders.order_date', '<=', $request->to_date);
    }

    $report = $query->get();

    $companies = \App\Models\Company::orderBy('company_name')->get();

    return view('admin.orders-report.index', compact('report', 'companies'));
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
