<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Company;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $customers = Customer::query()
        // ->filter($request->only(['customer_name', 'company_id', 'status'])) // حسب الفلاتر المستخدمة
        ->paginate(15);

    $companies = Company::all(); // أو حسب الحاجة

    return view('admin.customers.index', compact('customers', 'companies'));
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $companies = Company::all();
        $destinations = Destination::all();
        return view('admin.customers.create', compact('companies', 'destinations'));
    }

    /**
     * Store a newly created resource in storage.
     */


public function store(Request $request)
{
    $validated = $request->validate([
        'customer_name' => 'required|string|max:255',
        'email'         => 'required|email|unique:customers,email',
        'phone'         => 'required|string|unique:customers,phone',
        'address'       => 'required|string',
        'password'      => 'required|string|min:6',
    ]);

    $validated['password'] = Hash::make($validated['password']); // مهم جداً

    Customer::create($validated);

    return redirect()->route('admin.customers.index')->with('success', 'تم إنشاء العميل بنجاح');
}



    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        return view('admin.customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        $companies = Company::all();
        $destinations = Destination::all();
        return view('admin.customers.edit', compact('customer', 'companies', 'destinations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:100',
            'address' => 'required|string',
            'phone' => 'required|string|max:15',
            'email' => 'required|email|max:50|unique:customers,email,' . $customer->id,
            'password' => 'nullable|string|min:8',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $customer->update($validated);

        return redirect()->route('admin.customers.index')->with('success', 'تم تحديث بيانات العميل بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('admin.customers.index')->with('success', 'تم حذف العميل بنجاح');
    }

    /**
     * Toggle customer status
     */
    public function toggleStatus(Customer $customer)
    {
        $customer->update([
            'status' => $customer->status == 'active' ? 'inactive' : 'active'
        ]);

        return back()->with('success', 'تم تغيير حالة العميل بنجاح');
    }

    /**
     * Display customer orders
     */
    public function orders(Customer $customer)
    {
        $orders = $customer->orders()->latest()->paginate(10);
        return view('admin.customers.orders', compact('customer', 'orders'));
    }
}