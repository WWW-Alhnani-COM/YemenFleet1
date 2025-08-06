<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * عرض قائمة الفئات مع إمكانية الفلترة
     */
    public function index(Request $request)
    {
        $categories = Category::query()
            ->when($request->name, fn($q) => $q->where('name', 'like', '%'.$request->name.'%'))
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * عرض تفاصيل فئة معينة
     */
    public function show($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'الفئة غير موجودة'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $category
        ]);
    }

    /**
     * إنشاء فئة جديدة (للاستخدام من قبل الأدمن فقط)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
        ]);

        $category = Category::create($validated);

        return response()->json([
            'success' => true,
            'data' => $category,
            'message' => 'تمت إضافة الفئة بنجاح'
        ], 201);
    }

    /**
     * تحديث بيانات الفئة (للاستخدام من قبل الأدمن فقط)
     */
    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'الفئة غير موجودة'
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $id,
        ]);

        $category->update($validated);

        return response()->json([
            'success' => true,
            'data' => $category,
            'message' => 'تم تحديث الفئة بنجاح'
        ]);
    }

    /**
     * حذف الفئة (للاستخدام من قبل الأدمن فقط)
     */
    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'الفئة غير موجودة'
            ], 404);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف الفئة بنجاح'
        ]);
    }

    // في App\Http\Controllers\Api\CategoryController.php
public function getCategoryProducts($id)
{
    $category = Category::with('products')->find($id);

    if (!$category) {
        return response()->json([
            'success' => false,
            'message' => 'الفئة غير موجودة'
        ], 404);
    }

    return response()->json([
        'success' => true,
        'data' => [
            'category' => $category,
            'products' => $category->products()->paginate(10)
        ]
    ]);
}
}
