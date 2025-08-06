@extends('admin.layouts.app')

@section('content')
<div class="p-6 text-gray-900 dark:text-white space-y-6">
    {{-- العنوان --}}
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold">تعديل تعليق العميل</h2>
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

    {{-- نموذج التعديل --}}
    <form action="{{ route('admin.customer_reviews.update', $customerReview->id) }}" method="POST"
          class="bg-white dark:bg-gray-800 rounded p-6 shadow space-y-4">
        @csrf
        @method('PUT')

        {{-- العميل (غير قابل للتعديل) --}}
        <div>
            <label class="block mb-1">العميل</label>
            <input type="text" value="{{ $customerReview->customer->customer_name ?? 'غير معروف' }}" disabled
                   class="w-full p-2 rounded border bg-gray-100 dark:bg-gray-700 dark:text-white cursor-not-allowed">
        </div>

        {{-- المنتج (غير قابل للتعديل) --}}
        <div>
            <label class="block mb-1">المنتج</label>
            <input type="text" value="{{ $customerReview->product->name ?? 'غير معروف' }}" disabled
                   class="w-full p-2 rounded border bg-gray-100 dark:bg-gray-700 dark:text-white cursor-not-allowed">
        </div>

        {{-- التقييم --}}
        <div>
            <label for="rating" class="block mb-1">التقييم</label>
            <select name="rating" id="rating"
                    class="w-full p-2 rounded border dark:bg-gray-700 dark:text-white">
                @for ($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}" @selected(old('rating', $customerReview->rating) == $i)>{{ $i }}</option>
                @endfor
            </select>
        </div>

        {{-- التعليق --}}
        <div>
            <label for="comment" class="block mb-1">التعليق</label>
            <textarea name="comment" id="comment" rows="4"
                      class="w-full p-2 rounded border dark:bg-gray-700 dark:text-white">{{ old('comment', $customerReview->comment) }}</textarea>
        </div>

        {{-- زر الحفظ --}}
        <div>
            <button type="submit"
                    class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700 w-full md:w-auto">
                تحديث التعليق
            </button>
        </div>
    </form>
</div>
@endsection
