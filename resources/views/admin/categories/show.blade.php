@extends('admin.layouts.app')

@section('title', 'تفاصيل الفئة')
@section('header', 'تفاصيل الفئة')

@section('content')
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 space-y-4">

        <div>
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">اسم الفئة:</h2>
            <p class="text-gray-700 dark:text-gray-300">{{ $category->name }}</p>
        </div>

        <div>
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Slug:</h2>
            <p class="text-gray-700 dark:text-gray-300">{{ $category->slug ?? '-' }}</p>
        </div>

        <div class="flex space-x-2 justify-end">
            <a href="{{ route('admin.categories.edit', $category) }}"
               class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition">
               تعديل
            </a>
            <a href="{{ route('admin.categories.index') }}"
               class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">
               العودة للقائمة
            </a>
        </div>
    </div>
@endsection
