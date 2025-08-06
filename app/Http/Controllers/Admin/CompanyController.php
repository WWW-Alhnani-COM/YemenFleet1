<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Company::query();

        // فلترة حسب اسم الشركة
        if ($request->has('company_name')) {
            $query->where('company_name', 'like', '%'.$request->company_name.'%');
        }

        // فلترة حسب النشاط الاقتصادي
        if ($request->has('economic_activity')) {
            $query->where('economic_activity', $request->economic_activity);
        }

        // فلترة حسب نوع الأسطول
        if ($request->has('fleet_type')) {
            $query->where('fleet_type', $request->fleet_type);
        }

        $companies = $query->paginate(10);

        return view('admin.companies.index', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.companies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
{
    $validated = $request->validate([
        'company_name' => 'required|string|max:255',
        'address_company' => 'required|string|max:255',
        'phone_company' => 'required|string|max:20',
        'email_company' => 'required|email|unique:companies,email_company',
        'password' => 'required|string|min:8', // تم إزالة التحقق confirmed
        'owner_name' => 'required|string|max:255',
        'phone_owner' => 'required|string|max:20',
        'commercial_reg_number' => 'required|string|unique:companies,commercial_reg_number',
        'economic_activity' => 'required|string|max:255',
        'fleet_type' => 'required|string|max:255',
    ]);

    $validated['password'] = Hash::make($validated['password']);

    Company::create($validated);

    return redirect()->route('admin.companies.index')
        ->with('success', 'تم إنشاء الشركة بنجاح');
}

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        return view('admin.companies.show', compact('company'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        return view('admin.companies.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, Company $company)
{
    $validated = $request->validate([
        'company_name' => 'required|string|max:255',
        'address_company' => 'required|string|max:255',
        'phone_company' => 'required|string|max:20',
        'email_company' => [
            'required',
            'email',
            Rule::unique('companies')->ignore($company->id, 'id'),
        ],
        'password' => 'nullable|string|min:8', // تم إزالة التحقق confirmed
        'owner_name' => 'required|string|max:255',
        'phone_owner' => 'required|string|max:20',
        'commercial_reg_number' => [
            'required',
            'string',
            Rule::unique('companies')->ignore($company->id, 'id'),
        ],
        'economic_activity' => 'required|string|max:255',
        'fleet_type' => 'required|string|max:255',
    ]);

    if ($request->filled('password')) {
        $validated['password'] = Hash::make($validated['password']);
    } else {
        unset($validated['password']);
    }

    $company->update($validated);

    return redirect()->route('admin.companies.index')
        ->with('success', 'تم تحديث بيانات الشركة بنجاح');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        $company->delete();

        return redirect()->route('admin.companies.index')
            ->with('success', 'تم حذف الشركة بنجاح');
    }
}