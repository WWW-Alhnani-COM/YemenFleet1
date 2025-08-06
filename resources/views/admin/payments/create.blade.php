@extends('admin.layouts.app')

@section('title', 'إضافة دفعة جديدة')
@section('header', 'إضافة دفعة جديدة')

@section('content')
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="p-6">
            <form action="{{ route('admin.payments.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- حقل المبلغ -->
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">المبلغ *</label>
                        <input type="number" name="amount" id="amount" value="{{ old('amount') }}" required
                               class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200"
                               step="0.01" min="0" placeholder="0.00">
                        @error('amount')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- حقل طريقة الدفع -->
                    <div>
                        <label for="method" class="block text-sm font-medium text-gray-700 dark:text-gray-300">طريقة الدفع *</label>
                        <select name="method" id="method" required
                                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200">
                            <option value="">اختر طريقة الدفع</option>
                            <option value="credit_card" {{ old('method') == 'credit_card' ? 'selected' : '' }}>بطاقة الائتمان</option>
                            <option value="نقدي" {{ old('method') == 'نقدي' ? 'selected' : '' }}>نقدي</option>
                            <option value="bank_transfer" {{ old('method') == 'bank_transfer' ? 'selected' : '' }}>تحويل بنكي</option>
                            <option value="آجل" {{ old('method') == 'آجل' ? 'selected' : '' }}>آجل</option>
                        </select>
                        @error('method')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- حقل حالة الدفع -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">حالة الدفع *</label>
                        <select name="status" id="status" required
                                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200">
                            <option value="">اختر حالة الدفع</option>
                            <option value="معلق" {{ old('status') == 'معلق' ? 'selected' : '' }}>معلق</option>
                            <option value="مكتمل" {{ old('status') == 'مكتمل' ? 'selected' : '' }}>مكتمل</option>
                            <option value="فشل" {{ old('status') == 'فشل' ? 'selected' : '' }}>فشل</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- حقل تاريخ الدفع -->
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">تاريخ الدفع *</label>
                        <input type="datetime-local" name="date" id="date" value="{{ old('date') }}" required
                               class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200">
                        @error('date')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- حقل رقم الاشتراك (اختياري) -->
                    <!-- <div>
                        <label for="subscription_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">رقم الاشتراك</label>
                        <input type="number" name="subscription_id" id="subscription_id" value="{{ old('subscription_id') }}"
                               class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200"
                               placeholder="اختياري">
                        @error('subscription_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div> -->

                    <!-- حقل رقم الطلب (اختياري) -->
                    <!-- <div>
                        <label for="order_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">رقم الطلب</label>
                        <input type="number" name="order_id" id="order_id" value="{{ old('order_id') }}"
                               class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200"
                               placeholder="اختياري">
                        @error('order_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div> -->

                    <!-- حقل طلب الشركة (اختياري) -->
                    <!-- <div>
                        <label for="company_order_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">طلب الشركة</label>
                        <input type="number" name="company_order_id" id="company_order_id" value="{{ old('company_order_id') }}"
                               class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200"
                               placeholder="اختياري">
                        @error('company_order_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div> -->
                </div>

                <div class="flex justify-end mt-6 space-x-3">
                    <a href="{{ route('admin.payments.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition duration-200 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500">
                        إلغاء
                    </a>
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md shadow-sm hover:bg-indigo-700 transition duration-200 dark:bg-indigo-700 dark:hover:bg-indigo-600">
                        <i class="fas fa-save mr-2"></i> حفظ الدفعة
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection