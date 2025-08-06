<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductApiController extends Controller
{
    /**
     * عرض قائمة المنتجات مع إمكانية الفلترة
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
            ->paginate($request->per_page ?? 10);

        return response()->json([
            'status' => true,
            'data' => $products
        ]);
    }

    /**
     * عرض تفاصيل منتج معين
     */
    public function show($id)
    {
        $product = Product::with(['company', 'categories'])->find($id);

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'المنتج غير موجود'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $product
        ]);
    }

    /**
     * إنشاء منتج جديد
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

        return response()->json([
            'status' => true,
            'message' => 'تم إضافة المنتج بنجاح',
            'data' => $product
        ], 201);
    }

    /**
     * تحديث بيانات المنتج
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'المنتج غير موجود'
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'sometimes|integer|min:0',
            'price' => 'sometimes|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'company_id' => 'sometimes|exists:companies,id',
            'categories' => 'sometimes|array',
            'categories.*' => 'exists:categories,id'
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        if ($request->has('categories')) {
            $product->categories()->sync($request->categories);
        }

        return response()->json([
            'status' => true,
            'message' => 'تم تحديث المنتج بنجاح',
            'data' => $product
        ]);
    }

    /**
     * حذف المنتج
     */
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'المنتج غير موجود'
            ], 404);
        }

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->categories()->detach();
        $product->delete();

        return response()->json([
            'status' => true,
            'message' => 'تم حذف المنتج بنجاح'
        ]);
    }
}
