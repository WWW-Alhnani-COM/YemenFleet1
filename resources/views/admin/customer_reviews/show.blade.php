@extends('admin.layouts.app')

@section('content')
<div class="p-6 text-gray-900 dark:text-white space-y-6">

    {{-- العنوان وأزرار --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">تفاصيل تعليق العميل</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.customer_reviews.edit', $customerReview->id) }}"
               class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                تعديل
            </a>
            <a href="{{ route('admin.customer_reviews.index') }}"
               class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                رجوع
            </a>
        </div>
    </div>

    {{-- الكارد الرئيسي --}}
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 space-y-4">

        {{-- بيانات العميل والمنتج --}}
        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <h4 class="text-sm text-gray-500 dark:text-gray-400">اسم العميل</h4>
                <p class="text-lg font-semibold">{{ $customerReview->customer->customer_name ?? 'غير معروف' }}</p>
            </div>
            <div>
                <h4 class="text-sm text-gray-500 dark:text-gray-400">اسم المنتج</h4>
                <p class="text-lg font-semibold">{{ $customerReview->product->name ?? 'غير معروف' }}</p>
            </div>
        </div>

        {{-- التقييم --}}
        <div>
            <h4 class="text-sm text-gray-500 dark:text-gray-400 mb-1">التقييم</h4>
            <div class="flex items-center space-x-1 rtl:space-x-reverse">
                @for ($i = 1; $i <= 5; $i++)
                    @if ($i <= $customerReview->rating)
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927C9.432 2.013 10.568 2.013 10.951 2.927L12.188 5.856L15.361 6.291C16.325 6.424 16.71 7.636 15.97 8.277L13.52 10.38L14.09 13.525C14.252 14.472 13.222 15.148 12.388 14.673L9.999 13.347L7.611 14.673C6.777 15.148 5.748 14.472 5.909 13.525L6.479 10.38L4.03 8.277C3.29 7.636 3.675 6.424 4.639 6.291L7.812 5.856L9.049 2.927Z" />
                        </svg>
                    @else
                        <svg class="w-5 h-5 text-gray-300 dark:text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927C9.432 2.013 10.568 2.013 10.951 2.927L12.188 5.856L15.361 6.291C16.325 6.424 16.71 7.636 15.97 8.277L13.52 10.38L14.09 13.525C14.252 14.472 13.222 15.148 12.388 14.673L9.999 13.347L7.611 14.673C6.777 15.148 5.748 14.472 5.909 13.525L6.479 10.38L4.03 8.277C3.29 7.636 3.675 6.424 4.639 6.291L7.812 5.856L9.049 2.927Z" />
                        </svg>
                    @endif
                @endfor
                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">({{ $customerReview->rating }}/5)</span>
            </div>
        </div>

        {{-- التاريخ --}}
        <div>
            <h4 class="text-sm text-gray-500 dark:text-gray-400">تاريخ المراجعة</h4>
            <p class="text-md">{{ $customerReview->review_date->format('Y-m-d H:i') }}</p>
        </div>

        {{-- نص التعليق --}}
        <div>
            <h4 class="text-sm text-gray-500 dark:text-gray-400 mb-1">التعليق</h4>
            <div class="p-4 bg-gray-100 dark:bg-gray-700 rounded text-gray-800 dark:text-white whitespace-pre-wrap">
                {{ $customerReview->comment ?: 'لا يوجد تعليق.' }}
            </div>
        </div>

    </div>
</div>
@endsection
