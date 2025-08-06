<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $query = Invoice::query();

    // فلترة بالعنوان (بحث نصي)
    if ($request->filled('title')) {
        $query->where('title', 'like', '%' . $request->title . '%');
    }

    // فلترة حسب نطاق المبلغ
    if ($request->filled('amount_min')) {
        $query->where('amount', '>=', $request->amount_min);
    }
    if ($request->filled('amount_max')) {
        $query->where('amount', '<=', $request->amount_max);
    }

    // فلترة حسب تاريخ الإصدار (من - إلى)
    if ($request->filled('issued_date_from')) {
        $query->whereDate('issued_date', '>=', $request->issued_date_from);
    }
    if ($request->filled('issued_date_to')) {
        $query->whereDate('issued_date', '<=', $request->issued_date_to);
    }

    // فلترة حسب تاريخ الاستحقاق (من - إلى)
    if ($request->filled('due_date_from')) {
        $query->whereDate('due_date', '>=', $request->due_date_from);
    }
    if ($request->filled('due_date_to')) {
        $query->whereDate('due_date', '<=', $request->due_date_to);
    }

    // ترتيب حسب تاريخ الإصدار تنازلي (الأحدث أولاً)
    $invoices = $query->orderBy('issued_date', 'desc')->paginate(15)->withQueryString();

    return view('admin.invoices.index', compact('invoices'));
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
    public function show($id)
{
    $invoice = Invoice::with('maintenance.truck.company')->findOrFail($id);

    return view('admin.invoices.show', compact('invoice'));
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
