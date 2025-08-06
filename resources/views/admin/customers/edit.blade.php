@extends('admin.layouts.app')

@section('title', 'تعديل العميل')
@section('header', 'تعديل بيانات العميل')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <form action="{{ route('admin.customers.update', $customer->id) }}" method="POST" class="p-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- المعلومات الأساسية -->
            <div class="space-y-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200 border-b pb-2">
                    <i class="fas fa-user-circle mr-2"></i> المعلومات الأساسية
                </h3>

                <!-- اسم العميل -->
                <div>
                    <label for="customer_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">اسم العميل *</label>
                    <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name', $customer->customer_name) }}"
                           class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200"
                           required>
                    @error('customer_name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- البريد الإلكتروني -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">البريد الإلكتروني *</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $customer->email) }}"
                           class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200"
                           required>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- كلمة المرور -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">كلمة المرور</label>
                    <input type="password" name="password" id="password"
                           class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200"
                           placeholder="اتركه فارغاً إذا لم ترغب في التغيير">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- المعلومات الإضافية -->
            <div class="space-y-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200 border-b pb-2">
                    <i class="fas fa-info-circle mr-2"></i> معلومات إضافية
                </h3>

                <!-- الشركة -->
                <!-- <div>
                    <label for="company_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">الشركة *</label>
                    <select name="company_id" id="company_id"
                           class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200"
                           required>
                        <option value="">اختر الشركة</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" {{ old('company_id', $customer->company_id) == $company->id ? 'selected' : '' }}>{{ $company->company_name }}</option>
                        @endforeach
                    </select>
                    @error('company_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div> -->

                <!-- الهاتف -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">رقم الهاتف *</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $customer->phone) }}"
                           class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200"
                           required>
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- الوجهة -->
                <!-- <div>
                    <label for="destination_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">الوجهة المفضلة</label>
                    <select name="destination_id" id="destination_id"
                           class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200">
                        <option value="">اختر وجهة</option>
                        @foreach($destinations as $destination)
                            <option value="{{ $destination->id }}" {{ old('destination_id', $customer->destination_id) == $destination->id ? 'selected' : '' }}>
                                {{ $destination->start_point }} → {{ $destination->end_point }}
                            </option>
                        @endforeach
                    </select>
                    @error('destination_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div> -->
            </div>

            <!-- العنوان -->
            <div class="md:col-span-2">
                <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">العنوان *</label>
                <textarea name="address" id="address" rows="3"
                       class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200"
                       required>{{ old('address', $customer->address) }}</textarea>
                @error('address')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- الحالة -->
            <!-- <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">الحالة *</label>
                <div class="flex items-center space-x-4 space-x-reverse">
                    <label class="inline-flex items-center">
                        <input type="radio" name="status" value="active" 
                               {{ old('status', $customer->status) == 'active' ? 'checked' : '' }}
                               class="text-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:bg-gray-700">
                        <span class="mr-2 text-gray-700 dark:text-gray-300">نشط</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="status" value="inactive"
                               {{ old('status', $customer->status) == 'inactive' ? 'checked' : '' }}
                               class="text-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:bg-gray-700">
                        <span class="mr-2 text-gray-700 dark:text-gray-300">غير نشط</span>
                    </label>
                </div>
                @error('status')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div> -->
        </div>

        <!-- أزرار التحكم -->
        <div class="mt-6 flex justify-end space-x-3 space-x-reverse">
            <button type="submit"
                    class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 transition duration-200 dark:bg-indigo-700 dark:hover:bg-indigo-600 flex items-center">
                <i class="fas fa-save mr-2"></i> حفظ التعديلات
            </button>
            <a href="{{ route('admin.customers.index') }}"
               class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition duration-200 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500 flex items-center">
                <i class="fas fa-times mr-2"></i> إلغاء
            </a>
        </div>
    </form>
</div>
@endsection