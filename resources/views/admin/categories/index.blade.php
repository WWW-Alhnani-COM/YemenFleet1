@extends('admin.layouts.app')

@section('title', 'إدارة الفئات')
@section('header', 'قائمة الفئات')

@section('content')
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">

        <!-- فلترة حسب الاسم -->
        <form method="GET" action="{{ route('admin.categories.index') }}" class="mb-6 flex flex-col md:flex-row gap-4">
            <input type="text" name="name" value="{{ request('name') }}"
                   placeholder="ابحث باسم الفئة..."
                   class="w-full md:w-1/3 border border-gray-300 dark:border-gray-600 rounded-md py-2 px-3
                   dark:bg-gray-700 dark:text-gray-200 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            <button type="submit"
                    class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition">
                بحث
            </button>
            <a href="{{ route('admin.categories.create') }}"
               class="ml-auto bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition">
                إضافة فئة جديدة
            </a>
        </form>

        <!-- جدول الفئات -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">الاسم</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">الـ slug</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($categories as $category)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200 text-right">{{ $loop->iteration + ($categories->currentPage() - 1) * $categories->perPage() }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200 text-right">{{ $category->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-right">{{ $category->slug ?? '-' }}</td>
                           <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900 dark:text-white flex gap-3">
    <!-- زر العرض -->
    <a href="{{ route('admin.categories.show', $category) }}" title="عرض" class="text-blue-600 hover:text-blue-800">
        <i class="fas fa-eye"></i>
    </a>

    <!-- زر التعديل -->
    <a href="{{ route('admin.categories.edit', $category) }}" title="تعديل" class="text-green-600 hover:text-green-800">
        <i class="fas fa-edit"></i>
    </a>

    <!-- زر الحذف -->
    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟');" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit" title="حذف" class="text-red-600 hover:text-red-800">
            <i class="fas fa-trash-alt"></i>
        </button>
    </form>
</td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">لا توجد فئات</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $categories->withQueryString()->links() }}
        </div>

    </div>
@endsection
