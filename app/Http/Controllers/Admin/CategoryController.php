<?php

namespace App\Http\Controllers\Admin;

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

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * عرض نموذج إنشاء فئة جديدة
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * حفظ فئة جديدة
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
        ]);

        Category::create($validated);

        return redirect()->route('admin.categories.index')->with('success', 'تمت إضافة الفئة بنجاح');
    }

    /**
     * عرض تفاصيل فئة (اختياري)
     */
    public function show(Category $category)
    {
        return view('admin.categories.show', compact('category'));
    }

    /**
     * عرض نموذج تعديل الفئة
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * تحديث بيانات الفئة
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $category->id,
        ]);

        $category->update($validated);

        return redirect()->route('admin.categories.index')->with('success', 'تم تحديث الفئة بنجاح');
    }

    /**
     * حذف الفئة
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'تم حذف الفئة بنجاح');
    }
}
