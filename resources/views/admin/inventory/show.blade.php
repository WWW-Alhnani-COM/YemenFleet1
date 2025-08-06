@extends('admin.layouts.app')

@section('title', 'تفاصيل المنتج')
@section('header', 'تفاصيل المنتج')

@section('content')
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- معلومات المنتج -->
                <div class="space-y-4">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200 mb-2">المعلومات الأساسية</h3>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <div class="flex items-center mb-4">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                                    <i class="fas fa-box text-indigo-600 dark:text-indigo-300"></i>
                                </div>
                                <div class="mr-4">
                                    <h4 class="text-lg font-medium text-gray-900 dark:text-gray-200">{{ $inventory->product_name }}</h4>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <span class="text-gray-500 dark:text-gray-400 w-32">الشركة:</span>
                                    <span class="text-gray-900 dark:text-gray-200">{{ $inventory->company->company_name }}</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="text-gray-500 dark:text-gray-400 w-32">الكمية:</span>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $inventory->quantity > 10 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                                           ($inventory->quantity > 0 ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 
                                           'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200') }}">
                                        {{ $inventory->quantity }}
                                    </span>
                                </div>
                                <div class="flex items-center">
                                    <span class="text-gray-500 dark:text-gray-400 w-32">السعر:</span>
                                    <span class="text-gray-900 dark:text-gray-200">{{ number_format($inventory->price, 2) }} ر.س</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="text-gray-500 dark:text-gray-400 w-32">تاريخ الإضافة:</span>
                                    <span class="text-gray-900 dark:text-gray-200">{{ $inventory->created_at->format('Y-m-d') }}</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="text-gray-500 dark:text-gray-400 w-32">آخر تحديث:</span>
                                    <span class="text-gray-900 dark:text-gray-200">{{ $inventory->updated_at->format('Y-m-d') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- معلومات الشركة -->
                <div class="space-y-4">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200 mb-2">معلومات الشركة</h3>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <div class="flex items-center mb-4">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                                    <i class="fas fa-building text-indigo-600 dark:text-indigo-300"></i>
                                </div>
                                <div class="mr-4">
                                    <h4 class="text-lg font-medium text-gray-900 dark:text-gray-200">{{ $inventory->company->company_name }}</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">السجل: {{ $inventory->company->Commercial_RegistrationNumber }}</p>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <span class="text-gray-500 dark:text-gray-400 w-32">البريد الإلكتروني:</span>
                                    <span class="text-gray-900 dark:text-gray-200">{{ $inventory->company->email_company }}</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="text-gray-500 dark:text-gray-400 w-32">الهاتف:</span>
                                    <span class="text-gray-900 dark:text-gray-200">{{ $inventory->company->phone_company }}</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="text-gray-500 dark:text-gray-400 w-32">النشاط الاقتصادي:</span>
                                    <span class="text-gray-900 dark:text-gray-200">{{ $inventory->company->Economic_activity }}</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="text-gray-500 dark:text-gray-400 w-32">نوع الأسطول:</span>
                                    <span class="text-gray-900 dark:text-gray-200">{{ $inventory->company->Fleet_Type }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- أزرار العودة والتعديل -->
            <div class="flex items-center justify-end gap-3 mt-6">
                <a href="{{ route('admin.inventory.index') }}"
                   class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition duration-200 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500">
                    العودة للقائمة
                </a>
                <a href="{{ route('admin.inventory.edit', $inventory->id) }}"
                   class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-200 dark:bg-indigo-700 dark:hover:bg-indigo-600">
                    تعديل المنتج
                </a>
            </div>
        </div>
    </div>
@endsection