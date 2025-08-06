@extends('admin.layouts.app')

@section('title', 'إضافة منتج جديد')
@section('header', 'إضافة منتج جديد')

@section('content')
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="p-6">
            <form action="{{ route('admin.inventory.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- اسم المنتج -->
                    <div>
                        <label for="product_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">اسم المنتج <span class="text-red-500">*</span></label>
                        <input type="text" name="product_name" id="product_name" value="{{ old('product_name') }}"
                               class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200"
                               required>
                        @error('product_name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- الشركة -->
                    <div>
                        <label for="company_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">الشركة <span class="text-red-500">*</span></label>
                        <select name="company_id" id="company_id"
                               class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200"
                               required>
                            <option value="">اختر الشركة</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>{{ $company->company_name }}</option>
                            @endforeach
                        </select>
                        @error('company_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- الكمية -->
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">الكمية <span class="text-red-500">*</span></label>
                        <input type="number" name="quantity" id="quantity" value="{{ old('quantity', 0) }}"
                               class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200"
                               min="0" required>
                        @error('quantity')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- السعر -->
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">السعر <span class="text-red-500">*</span></label>
                        <input type="number" name="price" id="price" value="{{ old('price', 0) }}"
                               class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200"
                               min="0" step="0.01" required>
                        @error('price')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- أزرار الحفظ والإلغاء -->
                <div class="flex items-center justify-end gap-3">
                   <a href="{{ route('admin.customers.index') }}"
               class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition duration-200 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500 flex items-center">
                <i class="fas fa-times mr-2"></i> إلغاء
            </a>
                    <button type="submit"
                            class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-200 dark:bg-indigo-700 dark:hover:bg-indigo-600">
                        حفظ المنتج
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection