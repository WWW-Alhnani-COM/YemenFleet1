@extends('admin.layouts.app')

@section('content')
<div class="p-6 text-gray-900 dark:text-white space-y-6">
    {{-- العنوان --}}
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold">إضافة تعليق جديد</h2>
        <a href="{{ route('admin.customer_reviews.index') }}"
           class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
            ← رجوع
        </a>
    </div>

    {{-- رسائل الأخطاء --}}
    @if ($errors->any())
        <div class="bg-red-100 text-red-800 border border-red-400 p-4 rounded dark:bg-red-900 dark:text-white">
            <ul class="list-disc ps-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- نموذج الإضافة --}}
    <form action="{{ route('admin.customer_reviews.store') }}" method="POST"
          class="bg-white dark:bg-gray-800 rounded p-6 shadow space-y-4">
        @csrf

        {{-- العميل --}}
        <div>
            <label for="customer_id" class="block mb-1">العميل</label>
            <select name="customer_id" id="customer_id"
                    class="w-full p-2 rounded border dark:bg-gray-700 dark:text-white">
                <option value="">-- اختر العميل --</option>
                @foreach ($customers as $customer)
                    <option value="{{ $customer->id }}" @selected(old('customer_id') == $customer->id)>
                        {{ $customer->customer_name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- المنتج --}}
        <div>
            <label for="product_id" class="block mb-1">المنتج</label>
            <select name="product_id" id="product_id"
                    class="w-full p-2 rounded border dark:bg-gray-700 dark:text-white">
                <option value="">-- اختر المنتج --</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}" @selected(old('product_id') == $product->id)>
                        {{ $product->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- التقييم --}}
        <div>
            <label for="rating" class="block mb-1">التقييم (من 1 إلى 5)</label>
            <select name="rating" id="rating"
                    class="w-full p-2 rounded border dark:bg-gray-700 dark:text-white">
                @for ($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}" @selected(old('rating') == $i)>{{ $i }}</option>
                @endfor
            </select>
        </div>

        {{-- التعليق --}}
        <div>
            <label for="comment" class="block mb-1">التعليق (اختياري)</label>
            <textarea name="comment" id="comment" rows="4"
                      class="w-full p-2 rounded border dark:bg-gray-700 dark:text-white">{{ old('comment') }}</textarea>
        </div>

        {{-- زر الحفظ --}}
        <div>
            <button type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 w-full md:w-auto">
                حفظ التعليق
            </button>
        </div>
    </form>
</div>
@endsection
