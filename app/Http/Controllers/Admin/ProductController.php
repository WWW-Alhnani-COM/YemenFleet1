<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * عرض قائمة المنتجات مع الفلترة
     */
    public function index(Request $request)
    {
        $products = Product::query()
            ->with(['company', 'categories'])
            ->when($request->name, fn($q) => $q->where('name', 'like', '%'.$request->name.'%'))
            ->when($request->category, fn($q) => $q->whereHas('categories', fn($q) => $q->where('categories.id', $request->category)))
            ->when($request->company, fn($q) => $q->where('company_id', $request->company))
            ->when($request->min_price, fn($q) => $q->where('price', '>=', $request->min_price))
            ->when($request->max_price, fn($q) => $q->where('price', '<=', $request->max_price))
            ->latest()
            ->paginate(10);

        $categories = Category::all();
        $companies = Company::all();

        return view('admin.products.index', compact('products', 'categories', 'companies'));
    }

    /**
     * عرض نموذج إنشاء منتج جديد
     */
    public function create()
    {
        $categories = Category::all();
        $companies = Company::all();
        return view('admin.products.create', compact('categories', 'companies'));
    }

    /**
     * حفظ المنتج الجديد
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'company_id' => 'required|exists:companies,id',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id'
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product = Product::create($validated);
        $product->categories()->sync($request->categories);

        return redirect()->route('admin.products.index')->with('success', 'تم إضافة المنتج بنجاح');
    }

    /**
     * عرض تفاصيل المنتج
     */
    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    /**
     * عرض نموذج تعديل المنتج
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        $companies = Company::all();
        $selectedCategories = $product->categories->pluck('id')->toArray();
        
        return view('admin.products.edit', compact('product', 'categories', 'companies', 'selectedCategories'));
    }

    /**
     * تحديث بيانات المنتج
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'company_id' => 'required|exists:companies,id',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id'
        ]);

        if ($request->hasFile('image')) {
            // حذف الصورة القديمة إذا وجدت
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);
        $product->categories()->sync($request->categories);

        return redirect()->route('admin.products.index')->with('success', 'تم تحديث المنتج بنجاح');
    }

    /**
     * حذف المنتج
     */
    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        
        $product->categories()->detach();
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'تم حذف المنتج بنجاح');
    }
}