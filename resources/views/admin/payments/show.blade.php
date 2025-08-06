@extends('admin.layouts.app')

@section('title', 'تفاصيل الدفعة')
@section('header', 'تفاصيل الدفعة')

@section('content')
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- المبلغ -->
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">المبلغ</h3>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ number_format($payment->amount, 2) }} ر.س</p>
                </div>

                <!-- طريقة الدفع -->
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">طريقة الدفع</h3>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $payment->method }}</p>
                </div>

                <!-- حالة الدفع -->
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">الحالة</h3>
                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                        {{ $payment->status == 'مكتمل' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                           ($payment->status == 'معلق' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 
                           'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200') }}">
                        {{ $payment->status }}
                    </span>
                </div>

                <!-- تاريخ الدفع -->
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">تاريخ الدفع</h3>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $payment->date->format('Y-m-d H:i') }}</p>
                </div>
            </div>

            <!-- معلومات إضافية -->
            <div class="mt-6 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">معلومات إضافية</h3>
                
                <div class="space-y-2">
                    <div class="flex justify-between border-b border-gray-200 dark:border-gray-600 py-2">
                        <span class="text-gray-600 dark:text-gray-300">تاريخ الإنشاء:</span>
                        <span class="text-gray-900 dark:text-white">{{ $payment->created_at->format('Y-m-d H:i') }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-200 dark:border-gray-600 py-2">
                        <span class="text-gray-600 dark:text-gray-300">آخر تحديث:</span>
                        <span class="text-gray-900 dark:text-white">{{ $payment->updated_at->format('Y-m-d H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- أزرار الإجراءات -->
            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('admin.payments.index') }}"
                   class="bg-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-400 transition duration-200 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500">
                    <i class="fas fa-arrow-left mr-2"></i> رجوع
                </a>
                <a href="{{ route('admin.payments.edit', $payment->id) }}"
                   class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 transition duration-200 dark:bg-indigo-700 dark:hover:bg-indigo-600">
                    <i class="fas fa-edit mr-2"></i> تعديل
                </a>
            </div>
        </div>
    </div>
@endsection