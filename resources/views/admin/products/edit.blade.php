@extends('admin.layouts.app')

@section('title', 'تعديل المنتج')
@section('header', 'تعديل المنتج')

@section('content')
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- الاسم -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">اسم المنتج *</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}"
                           class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 dark:bg-gray-700 dark:text-gray-200"
                           required>
                    @error('name') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- الشركة -->
                <div>
                    <label for="company_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">الشركة *</label>
                    <select name="company_id" id="company_id"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 dark:bg-gray-700 dark:text-gray-200"
                            required>
                        <option value="">اختر الشركة</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" {{ old('company_id', $product->company_id) == $company->id ? 'selected' : '' }}>
                                {{ $company->company_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('company_id') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- السعر -->
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">السعر *</label>
                    <input type="number" step="0.01" name="price" id="price" value="{{ old('price', $product->price) }}"
                           class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 dark:bg-gray-700 dark:text-gray-200"
                           required>
                    @error('price') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- الكمية -->
                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">الكمية *</label>
                    <input type="number" name="quantity" id="quantity" value="{{ old('quantity', $product->quantity) }}"
                           class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 dark:bg-gray-700 dark:text-gray-200"
                           required>
                    @error('quantity') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- الفئات -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">الفئات *</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                        @foreach($categories as $category)
                            <div class="flex items-center">
                                <input type="checkbox" name="categories[]" id="category-{{ $category->id }}" value="{{ $category->id }}"
                                       {{ in_array($category->id, old('categories', $product->categories->pluck('id')->toArray())) ? 'checked' : '' }}
                                       class="h-4 w-4 text-indigo-600 border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600">
                                <label for="category-{{ $category->id }}" class="mr-2 text-sm text-gray-700 dark:text-gray-300">
                                    {{ $category->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    @error('categories') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- الصورة -->
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">صورة المنتج</label>
                    <input type="file" name="image" id="image"
                           class="w-full border border-gray-300 dark:border-gray-600 rounded-md py-2 px-3 dark:bg-gray-700 dark:text-gray-200">
                    @error('image') <p class="text-sm text-red-600">{{ $message }}</p> @enderror

                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="mt-3 max-w-xs rounded-lg" alt="صورة حالية">
                    @endif
                </div>

                <!-- الوصف -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">الوصف</label>
                    <textarea name="description" id="description" rows="3"
                              class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 dark:bg-gray-700 dark:text-gray-200">{{ old('description', $product->description) }}</textarea>
                    @error('description') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <button type="submit"
                        class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 dark:bg-indigo-700 dark:hover:bg-indigo-600">
                    حفظ التعديلات
                </button>
            </div>
        </form>
    </div>
@endsection
