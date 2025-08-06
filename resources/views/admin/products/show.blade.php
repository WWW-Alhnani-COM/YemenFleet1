@extends('admin.layouts.app')

@section('title', 'عرض المنتج')
@section('header', 'تفاصيل المنتج')

@section('content')
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">الاسم:</h3>
                <p class="text-lg font-semibold text-gray-800 dark:text-gray-100">{{ $product->name }}</p>
            </div>

            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">الشركة:</h3>   
                <p class="text-lg font-semibold text-gray-800 dark:text-gray-100">{{ $product->company->company_name }}</p>
            </div>

            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">السعر:</h3>
                <p class="text-lg font-semibold text-gray-800 dark:text-gray-100">{{ number_format($product->price, 2) }} ريال</p>
            </div>

            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">الكمية:</h3>
                <p class="text-lg font-semibold text-gray-800 dark:text-gray-100">{{ $product->quantity }}</p>
            </div>

            <div class="md:col-span-2">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">الفئات:</h3>
                <div class="flex flex-wrap gap-2 mt-1">
                    @foreach($product->categories as $category)
                        <span class="bg-indigo-100 dark:bg-indigo-700 text-indigo-800 dark:text-white text-sm px-3 py-1 rounded-full">
                            {{ $category->name }}
                        </span>
                    @endforeach
                </div>
            </div>

            @if($product->image)
                <div class="md:col-span-2">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">صورة المنتج:</h3>
                    <img src="{{ asset('storage/' . $product->image) }}" alt="صورة المنتج" class="max-w-xs rounded-lg">
                </div>
            @endif

            <div class="md:col-span-2">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">الوصف:</h3>
                <p class="text-gray-800 dark:text-gray-100">{{ $product->description ?? 'لا يوجد وصف' }}</p>
            </div>
        </div>

        <div class="flex justify-end mt-6">
            <a href="{{ route('admin.products.edit', $product) }}"
               class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 transition duration-200 dark:bg-indigo-700 dark:hover:bg-indigo-600">
                تعديل المنتج
            </a>
        </div>
    </div>
@endsection
