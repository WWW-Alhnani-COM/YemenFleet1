@extends('admin.layouts.app') {{-- تأكد أن هذا الملف يحتوي على الـ layout الأساسي --}}

@section('content')
<div class="p-6 space-y-6 text-gray-900 dark:text-white">
    {{-- عنوان الصفحة --}}
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold">إدارة تعليقات العملاء</h2>
        <a href="{{ route('admin.customer_reviews.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            + إضافة تعليق جديد
        </a>
    </div>

   {{-- رسائل الجلسة --}}
@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded dark:bg-green-900 dark:text-white">
        {{ session('success') }}
    </div>
@endif

@if($errors->has('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded dark:bg-red-900 dark:text-white mt-2">
        {{ $errors->first('error') }}
    </div>
@endif


    {{-- الفلترة --}}
    <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end bg-gray-100 dark:bg-gray-800 p-4 rounded">
        <div>
            <label class="block text-sm mb-1">المنتج</label>
            <select name="product_id" class="w-full rounded p-2 border dark:bg-gray-700 dark:text-white">
                <option value="">-- الكل --</option>
                @foreach(\App\Models\Product::all() as $product)
                    <option value="{{ $product->id }}" @selected(request('product_id') == $product->id)>
                        {{ $product->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm mb-1">التقييم الأدنى</label>
            <select name="min_rating" class="w-full rounded p-2 border dark:bg-gray-700 dark:text-white">
                <option value="">-- الكل --</option>
                @for($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}" @selected(request('min_rating') == $i)>{{ $i }}+</option>
                @endfor
            </select>
        </div>

        <div>
            <button type="submit"
                    class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 mt-1 w-full">
                تطبيق الفلاتر
            </button>
        </div>
    </form>

    {{-- جدول التعليقات --}}
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-left border dark:border-gray-700">
            <thead class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-white">
                <tr>
                    <th class="px-4 py-2">#</th>
                    <th class="px-4 py-2">العميل</th>
                    <th class="px-4 py-2">المنتج</th>
                    <th class="px-4 py-2">التقييم</th>
                    <th class="px-4 py-2">التعليق</th>
                    <th class="px-4 py-2">تاريخ المراجعة</th>
                    <th class="px-4 py-2 text-center">الخيارات</th>
                </tr>
            </thead>
            <tbody class="divide-y dark:divide-gray-700">
                @forelse($reviews as $review)
                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-800">
                        <td class="px-4 py-2">{{ $review->id }}</td>
                        <td class="px-4 py-2">{{ $review->customer->customer_name ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $review->product->name ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $review->rating }}/5</td>
                        <td class="px-4 py-2">{{ Str::limit($review->comment, 50) }}</td>
                        <td class="px-4 py-2">{{ $review->review_date->format('Y-m-d') }}</td>
                        <td class="px-4 py-2 text-center space-x-2 flex justify-center items-center">
    <a href="{{ route('admin.customer_reviews.show', $review->id) }}"
       class="text-blue-600 hover:text-blue-800" title="عرض">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z" />
            <circle cx="12" cy="12" r="3" />
        </svg>
    </a>
    <a href="{{ route('admin.customer_reviews.edit', $review->id) }}"
       class="text-yellow-600 hover:text-yellow-800" title="تعديل">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path d="M12 20h9" />
            <path d="M16.5 3.5a2.121 2.121 0 1 1 3 3L7 19l-4 1 1-4L16.5 3.5z" />
        </svg>
    </a>
    <form action="{{ route('admin.customer_reviews.destroy', $review->id) }}" method="POST" class="inline"
          onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
        @csrf
        @method('DELETE')
        <button type="submit" class="text-red-600 hover:text-red-800" title="حذف">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path d="M3 6h18" />
                <path d="M8 6V4h8v2" />
                <path d="M10 11v6" />
                <path d="M14 11v6" />
                <path d="M5 6h14l-1.5 14h-11L5 6z" />
            </svg>
        </button>
    </form>
</td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-gray-500 dark:text-gray-400">
                            لا توجد تعليقات حتى الآن.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- روابط التصفح --}}
    <div>
        {{ $reviews->withQueryString()->links() }}
    </div>
</div>
@endsection
