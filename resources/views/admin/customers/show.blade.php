@extends('admin.layouts.app')

@section('title', 'عرض العميل')
@section('header', 'عرض تفاصيل العميل')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <div class="p-6">
        <div class="flex flex-col md:flex-row gap-8">
            <!-- الصورة والمعلومات الأساسية -->
            <div class="md:w-1/3">
                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-6 text-center">
                    <div class="h-32 w-32 mx-auto rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center mb-4">
                        <i class="fas fa-user text-5xl text-indigo-600 dark:text-indigo-300"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ $customer->customer_name }}</h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-4">{{ $customer->company->company_name ?? 'بدون شركة' }}</p>

                    <div class="flex justify-center space-x-2">
                        <span class="px-3 py-1 text-sm rounded-full
                            {{ $customer->status != 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                            {{ $customer->status != 'active' ? 'نشط' : 'غير نشط' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- التفاصيل -->
            <div class="md:w-2/3">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- المعلومات الشخصية -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <h4 class="font-bold text-lg text-gray-800 dark:text-gray-200 mb-4 border-b pb-2">
                            <i class="fas fa-id-card mr-2"></i> المعلومات الشخصية
                        </h4>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">البريد الإلكتروني</p>
                                <p class="text-gray-800 dark:text-gray-200">{{ $customer->email }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">رقم الهاتف</p>
                                <p class="text-gray-800 dark:text-gray-200">{{ $customer->phone }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">العنوان</p>
                                <p class="text-gray-800 dark:text-gray-200">{{ $customer->address }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- معلومات الشركة -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <h4 class="font-bold text-lg text-gray-800 dark:text-gray-200 mb-4 border-b pb-2">
                            <i class="fas fa-building mr-2"></i> معلومات الشركة
                        </h4>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">اسم الشركة</p>
                                <p class="text-gray-800 dark:text-gray-200">
                                    {{ $customer->company->company_name ?? 'غير محدد' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">هاتف الشركة</p>
                                <p class="text-gray-800 dark:text-gray-200">
                                    {{ $customer->company->phone_company ?? 'غير محدد' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">الوجهة المفضلة</p>
                                <p class="text-gray-800 dark:text-gray-200">
                                    @if($customer->destination)
                                        {{ $customer->destination->start_point }} → {{ $customer->destination->end_point }}
                                    @else
                                        غير محدد
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- إحصائيات سريعة -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg md:col-span-2">
                        <h4 class="font-bold text-lg text-gray-800 dark:text-gray-200 mb-4 border-b pb-2">
                            <i class="fas fa-chart-bar mr-2"></i> إحصائيات العميل
                        </h4>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="bg-white dark:bg-gray-600 p-3 rounded-lg shadow text-center">
                                <p class="text-sm text-gray-500 dark:text-gray-300">عدد الطلبات</p>
                                <p class="text-xl font-bold text-indigo-600 dark:text-indigo-300">{{ $customer->orders->count() }}</p>
                            </div>
                            <div class="bg-white dark:bg-gray-600 p-3 rounded-lg shadow text-center">
                                <p class="text-sm text-gray-500 dark:text-gray-300">آخر طلب</p>
                                <p class="text-xl font-bold text-green-600 dark:text-green-300">
                                    @if($customer->orders->count() > 0)
                                        {{ $customer->orders->last()->created_at->diffForHumans() }}
                                    @else
                                        لا يوجد
                                    @endif
                                </p>
                            </div>
                            <div class="bg-white dark:bg-gray-600 p-3 rounded-lg shadow text-center">
                                <p class="text-sm text-gray-500 dark:text-gray-300">تاريخ التسجيل</p>
                                <p class="text-xl font-bold text-blue-600 dark:text-blue-300">{{ $customer->created_at->format('Y-m-d') }}</p>
                            </div>
                            <div class="bg-white dark:bg-gray-600 p-3 rounded-lg shadow text-center">
                                <p class="text-sm text-gray-500 dark:text-gray-300">التقييمات</p>
                                <p class="text-xl font-bold text-yellow-600 dark:text-yellow-300">{{ $customer->reviews->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- أزرار التحكم -->
        <div class="mt-6 flex justify-end space-x-3 space-x-reverse">
            <a href="{{ route('admin.customers.orders', $customer->id) }}"
               class="bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700 transition duration-200 dark:bg-purple-700 dark:hover:bg-purple-600 flex items-center">
                <i class="fas fa-shopping-cart mr-2"></i> عرض الطلبات
            </a>
            <a href="{{ route('admin.customers.edit', $customer->id) }}"
               class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-200 dark:bg-indigo-700 dark:hover:bg-indigo-600 flex items-center">
                <i class="fas fa-edit mr-2"></i> تعديل البيانات
            </a>
            <a href="{{ route('admin.customers.index') }}"
               class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition duration-200 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500 flex items-center">
                <i class="fas fa-arrow-right mr-2"></i> رجوع للقائمة
            </a>
        </div>
    </div>
</div>
@endsection
