@extends('admin.layouts.app')

@section('title', 'تفاصيل الحادث')
@section('header', 'تفاصيل الحادث')

@section('content')

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- الشاحنة -->
           <!-- الشاحنة -->
<div class="mb-4">
    <h4 class="text-sm text-gray-500 dark:text-gray-300 mb-1">الشاحنة</h4>
    <p class="text-lg font-semibold text-gray-800 dark:text-white">
        {{ $accident->truck->truck_name ?? 'غير محددة' }}
    </p>
    <p class="text-sm text-gray-400 dark:text-gray-400 mt-1">
        {{ $accident->truck && $accident->truck->company ? $accident->truck->company->company_name : 'شركة غير محددة' }}
    </p>
</div>


            <!-- موقع الحادث -->
            <div>
                <h4 class="text-sm text-gray-500 dark:text-gray-300 mb-1">موقع الحادث</h4>
                <p class="text-lg font-semibold text-gray-800 dark:text-white">
                    {{ $accident->location }}
                </p>
            </div>

            <!-- نوع الحادث -->
            <div>
                <h4 class="text-sm text-gray-500 dark:text-gray-300 mb-1">نوع الحادث</h4>
                <p class="text-lg font-semibold text-gray-800 dark:text-white">
                    {{ $accident->type }}
                </p>
            </div>

            <!-- التاريخ -->
            <div>
                <h4 class="text-sm text-gray-500 dark:text-gray-300 mb-1">تاريخ الحادث</h4>
                <p class="text-lg font-semibold text-gray-800 dark:text-white">
                    {{ $accident->date ? $accident->date->format('Y-m-d') : 'غير محدد' }}
                </p>
            </div>

            <!-- الوصف -->
            <div class="md:col-span-2">
                <h4 class="text-sm text-gray-500 dark:text-gray-300 mb-1">وصف الحادث</h4>
                <p class="text-lg font-semibold text-gray-800 dark:text-white">
                    {{ $accident->description }}
                </p>
            </div>

        </div>

        <div class="mt-6 flex justify-end space-x-2 rtl:space-x-reverse">
            <a href="{{ route('admin.accidents.edit', $accident->id) }}"
               class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-4 rounded-md transition">
                <i class="fas fa-edit mr-1"></i> تعديل
            </a>

            <a href="{{ route('admin.accidents.index') }}"
               class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">
               العودة للقائمة
            </a>
        </div>
    </div>

@endsection
