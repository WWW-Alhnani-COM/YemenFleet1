@extends('admin.layouts.app')

@section('title', 'تفاصيل السائق')
@section('header', 'تفاصيل السائق')

@section('content')
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- معلومات السائق -->
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200 mb-4 border-b pb-2">المعلومات الشخصية</h3>
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <span class="text-gray-600 dark:text-gray-400 font-medium w-32">الاسم:</span>
                            <span class="text-gray-900 dark:text-gray-200 flex-1">{{ $driver->driver_name }}</span>
                        </div>
                        <div class="flex items-start">
                            <span class="text-gray-600 dark:text-gray-400 font-medium w-32">البريد الإلكتروني:</span>
                            <span class="text-gray-900 dark:text-gray-200 flex-1">{{ $driver->email }}</span>
                        </div>
                        <div class="flex items-start">
                            <span class="text-gray-600 dark:text-gray-400 font-medium w-32">الهاتف:</span>
                            <span class="text-gray-900 dark:text-gray-200 flex-1">{{ $driver->phone }}</span>
                        </div>
                        <div class="flex items-start">
                            <span class="text-gray-600 dark:text-gray-400 font-medium w-32">العنوان:</span>
                            <span class="text-gray-900 dark:text-gray-200 flex-1">{{ $driver->address }}</span>
                        </div>
                    </div>
                </div>

                <!-- معلومات الشاحنة والشركة -->
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200 mb-4 border-b pb-2">معلومات العمل</h3>
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <span class="text-gray-600 dark:text-gray-400 font-medium w-32">الشركة:</span>
                            <span class="text-gray-900 dark:text-gray-200 flex-1">
                                {{ $driver->truck->company->company_name ?? 'غير محدد' }}
                            </span>
                        </div>
                        <div class="flex items-start">
                            <span class="text-gray-600 dark:text-gray-400 font-medium w-32">الشاحنة:</span>
                            <span class="text-gray-900 dark:text-gray-200 flex-1">
                                {{ $driver->truck->truck_name ?? 'غير محدد' }}
                            </span>
                        </div>
                        <div class="flex items-start">
                            <span class="text-gray-600 dark:text-gray-400 font-medium w-32">حالة الحساب:</span>
                            <span class="flex-1">
                                 @if($driver->status === 'inactive')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                        معطل
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        نشط
                                    </span>
                                @endif
                            </span>
                        </div>
                        <div class="flex items-start">
                            <span class="text-gray-600 dark:text-gray-400 font-medium w-32">تاريخ التسجيل:</span>
                            <span class="text-gray-900 dark:text-gray-200 flex-1">
                                {{ $driver->created_at->format('Y-m-d H:i') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <a href="{{ route('admin.drivers.index') }}"
                   class="bg-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-400 transition duration-200 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500">
                    <i class="fas fa-arrow-left mr-2"></i> رجوع
                </a>
            </div>
        </div>
    </div>
@endsection