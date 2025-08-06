<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Company;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // فلترة المنتجات
        $products = Inventory::query()
            ->with('company')
            ->when($request->filled('product_name'), function ($query) use ($request) {
                $query->where('product_name', 'like', '%' . $request->product_name . '%');
            })
            ->when($request->filled('company_id'), function ($query) use ($request) {
                $query->where('company_id', $request->company_id);
            })
            ->when($request->filled('min_price'), function ($query) use ($request) {
                $query->where('price', '>=', $request->min_price);
            })
            ->when($request->filled('max_price'), function ($query) use ($request) {
                $query->where('price', '<=', $request->max_price);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $companies = Company::all();

        return view('admin.inventory.index', compact('products', 'companies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $companies = Company::all();
        return view('admin.inventory.create', compact('companies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:100',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'company_id' => 'required|exists:companies,id',
        ]);

        Inventory::create($request->only(['product_name', 'quantity', 'price', 'company_id']));

        return redirect()->route('admin.inventory.index')
            ->with('success', 'تم إضافة المنتج بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Inventory $inventory)
    {
        return view('admin.inventory.show', compact('inventory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inventory $inventory)
    {
        $companies = Company::all();
        return view('admin.inventory.edit', compact('inventory', 'companies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Inventory $inventory)
    {
        $request->validate([
            'product_name' => 'required|string|max:100',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'company_id' => 'required|exists:companies,id',
        ]);

        $inventory->update($request->only(['product_name', 'quantity', 'price', 'company_id']));

        return redirect()->route('admin.inventory.index')
            ->with('success', 'تم تحديث المنتج بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inventory $inventory)
    {
        $inventory->delete();

        return redirect()->route('admin.inventory.index')
            ->with('success', 'تم حذف المنتج بنجاح');
    }
}