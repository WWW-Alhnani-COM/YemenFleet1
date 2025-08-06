@extends('admin.layouts.app')

@section('title', 'إضافة فئة جديدة')
@section('header', 'إضافة فئة جديدة')

@section('content')
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">اسم الفئة *</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                       class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3
                       focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200"
                       required>
                @error('name')
                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Slug (اختياري)</label>
                <input type="text" name="slug" id="slug" value="{{ old('slug') }}"
                       class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3
                       focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200">
                @error('slug')
                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit"
                        class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 transition duration-200 dark:bg-indigo-700 dark:hover:bg-indigo-600">
                    حفظ الفئة
                </button>
            </div>
        </form>
    </div>
@endsection
