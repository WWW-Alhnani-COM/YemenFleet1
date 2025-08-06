@extends('admin.layouts.app')

@section('title', 'تفاصيل الشركة: ' . $company->company_name)
@section('header', 'تفاصيل الشركة: ' . $company->company_name)

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                <i class="fas fa-building mr-2"></i> بيانات الشركة
            </h3>
            <div class="flex space-x-3 space-x-reverse">
                <a href="{{ route('admin.companies.edit', $company->id) }}" 
                   class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-200 dark:bg-indigo-700 dark:hover:bg-indigo-600 flex items-center">
                    <i class="fas fa-edit mr-2"></i> تعديل
                </a>
                <a href="{{ route('admin.companies.index') }}" 
                   class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition duration-200 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500 flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> رجوع
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- معلومات الشركة الأساسية -->
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <h4 class="font-semibold text-lg text-indigo-600 dark:text-indigo-400 mb-4 border-b pb-2 border-gray-200 dark:border-gray-600">
                    <i class="fas fa-info-circle mr-2"></i> المعلومات الأساسية
                </h4>
                
                <div class="space-y-4">
                    <div class="flex justify-between border-b border-gray-200 dark:border-gray-600 pb-2">
                        <span class="font-medium text-gray-700 dark:text-gray-300">اسم الشركة:</span>
                        <span class="text-gray-900 dark:text-gray-100">{{ $company->company_name }}</span>
                    </div>
                    
                    <div class="flex justify-between border-b border-gray-200 dark:border-gray-600 pb-2">
                        <span class="font-medium text-gray-700 dark:text-gray-300">البريد الإلكتروني:</span>
                        <span class="text-gray-900 dark:text-gray-100">{{ $company->email_company }}</span>
                    </div>
                    
                    <div class="flex justify-between border-b border-gray-200 dark:border-gray-600 pb-2">
                        <span class="font-medium text-gray-700 dark:text-gray-300">هاتف الشركة:</span>
                        <span class="text-gray-900 dark:text-gray-100">{{ $company->phone_company }}</span>
                    </div>
                    
                    <div class="flex justify-between border-b border-gray-200 dark:border-gray-600 pb-2">
                        <span class="font-medium text-gray-700 dark:text-gray-300">العنوان:</span>
                        <span class="text-gray-900 dark:text-gray-100">{{ $company->address_company }}</span>
                    </div>
                    
                    <div class="flex justify-between border-b border-gray-200 dark:border-gray-600 pb-2">
                        <span class="font-medium text-gray-700 dark:text-gray-300">النشاط الاقتصادي:</span>
                        <span class="text-gray-900 dark:text-gray-100">{{ $company->economic_activity }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="font-medium text-gray-700 dark:text-gray-300">نوع الأسطول:</span>
                        <span class="text-gray-900 dark:text-gray-100">{{ $company->fleet_type }}</span>
                    </div>
                </div>
            </div>

            <!-- معلومات المالك -->
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <h4 class="font-semibold text-lg text-indigo-600 dark:text-indigo-400 mb-4 border-b pb-2 border-gray-200 dark:border-gray-600">
                    <i class="fas fa-user-tie mr-2"></i> معلومات المالك
                </h4>
                
                <div class="space-y-4">
                    <div class="flex justify-between border-b border-gray-200 dark:border-gray-600 pb-2">
                        <span class="font-medium text-gray-700 dark:text-gray-300">اسم المالك:</span>
                        <span class="text-gray-900 dark:text-gray-100">{{ $company->owner_name }}</span>
                    </div>
                    
                    <div class="flex justify-between border-b border-gray-200 dark:border-gray-600 pb-2">
                        <span class="font-medium text-gray-700 dark:text-gray-300">هاتف المالك:</span>
                        <span class="text-gray-900 dark:text-gray-100">{{ $company->phone_owner }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="font-medium text-gray-700 dark:text-gray-300">رقم السجل التجاري:</span>
                        <span class="text-gray-900 dark:text-gray-100">{{ $company->commercial_reg_number}}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- الشاحنات التابعة -->
        <div class="mt-6 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
            <h4 class="font-semibold text-lg text-indigo-600 dark:text-indigo-400 mb-4 border-b pb-2 border-gray-200 dark:border-gray-600">
                <i class="fas fa-truck mr-2"></i> الشاحنات التابعة
            </h4>
            
            @if($company->trucks->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($company->trucks as $truck)
                        <a href="{{ route('admin.trucks.show', $truck->id) }}" 
                           class="block p-4 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition duration-200">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                                    <i class="fas fa-truck text-indigo-600 dark:text-indigo-300"></i>
                                </div>
                                <div class="mr-3">
                                    <h5 class="font-medium text-gray-900 dark:text-gray-100">{{ $truck->truck_number }}</h5>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $truck->truck_type }}</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-200 px-4 py-3 rounded">
                    <i class="fas fa-info-circle mr-2"></i> لا توجد شاحنات مسجلة لهذه الشركة
                </div>
            @endif
        </div>
    </div>
</div>
@endsection