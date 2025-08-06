<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CompanyController extends Controller
{
    /**
     * Display a listing of the companies with their products.
     */
    public function index()
    {
        $companies = Company::with('products')->paginate(10);
        return response()->json($companies);
    }

    /**
     * Store a newly created company in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'address_company' => 'required|string|max:255',
            'phone_company' => 'required|string|max:20',
            'email_company' => 'required|email|unique:companies,email_company',
            'password' => 'required|string|min:8',
            'owner_name' => 'required|string|max:255',
            'phone_owner' => 'required|string|max:20',
            'commercial_reg_number' => 'required|string|unique:companies,commercial_reg_number',
            'economic_activity' => 'required|string|max:255',
            'fleet_type' => 'required|string|max:255',
        ]);

        $validated['password'] = bcrypt($validated['password']);

        $company = Company::create($validated);

        return response()->json($company, 201);
    }

    /**
     * Display the specified company with its products.
     */
    public function show(Company $company)
    {
        $company->load('products');
        return response()->json($company);
    }

    /**
     * Update the specified company in storage.
     */
    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'company_name' => 'sometimes|string|max:255',
            'address_company' => 'sometimes|string|max:255',
            'phone_company' => 'sometimes|string|max:20',
            'email_company' => [
                'sometimes',
                'email',
                Rule::unique('companies')->ignore($company->id, 'id'),
            ],
            'password' => 'sometimes|string|min:8',
            'owner_name' => 'sometimes|string|max:255',
            'phone_owner' => 'sometimes|string|max:20',
            'commercial_reg_number' => [
                'sometimes',
                'string',
                Rule::unique('companies')->ignore($company->id, 'id'),
            ],
            'economic_activity' => 'sometimes|string|max:255',
            'fleet_type' => 'sometimes|string|max:255',
        ]);

        if ($request->has('password')) {
            $validated['password'] = bcrypt($validated['password']);
        }

        $company->update($validated);

        return response()->json($company);
    }

    /**
     * Remove the specified company from storage.
     */
    public function destroy(Company $company)
    {
        $company->delete();
        return response()->json(null, 204);
    }

    /**
     * Get all products for a specific company.
     */
    public function products(Company $company)
    {
        $products = $company->products()->paginate(10);
        return response()->json($products);
    }
}
