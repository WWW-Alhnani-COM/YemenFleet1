@extends('admin.layouts.app')

@section('title', 'إدارة الاشتراكات')
@section('header', 'إدارة الاشتراكات')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <div class="p-6">
        <!-- Header with Create Button -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                    <i class="fas fa-file-contract mr-2"></i> قائمة الاشتراكات
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">إدارة جميع اشتراكات الشركات في النظام</p>
            </div>
            
            <a href="{{ route('admin.subscriptions.create') }}" 
               class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-200 dark:bg-indigo-700 dark:hover:bg-indigo-600 flex items-center">
                <i class="fas fa-plus mr-2"></i> إنشاء اشتراك جديد
            </a>
        </div>

        <!-- فلترة البحث -->
        <div class="mb-6 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
            <form action="{{ route('admin.subscriptions.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- فلترة حسب نوع الاشتراك -->
                <div>
                    <label for="subscription_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">نوع الاشتراك</label>
                    <select name="subscription_type" id="subscription_type"
                           class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200">
                        <option value="">الكل</option>
                        <option value="monthly" {{ request('subscription_type') == 'monthly' ? 'selected' : '' }}>شهري</option>
                        <option value="quarterly" {{ request('subscription_type') == 'quarterly' ? 'selected' : '' }}>ربع سنوي</option>
                        <option value="semi-annual" {{ request('subscription_type') == 'semi-annual' ? 'selected' : '' }}>نصف سنوي</option>
                        <option value="annual" {{ request('subscription_type') == 'annual' ? 'selected' : '' }}>سنوي</option>
                    </select>
                </div>

                <!-- فلترة حسب الحالة -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">الحالة</label>
                    <select name="status" id="status"
                           class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200">
                        <option value="">الكل</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>معلق</option>
                    </select>
                </div>

                <!-- فلترة حسب الشركة -->
                <div>
                    <label for="company_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">الشركة</label>
                    <select name="company_id" id="company_id"
                           class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200">
                        <option value="">الكل</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>{{ $company->company_name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- فلترة حسب تاريخ الانتهاء -->
                <div>
                    <label for="expiry_filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">حالة الانتهاء</label>
                    <select name="expiry_filter" id="expiry_filter"
                           class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200">
                        <option value="">الكل</option>
                        <option value="active" {{ request('expiry_filter') == 'active' ? 'selected' : '' }}>نشطة</option>
                        <option value="expired" {{ request('expiry_filter') == 'expired' ? 'selected' : '' }}>منتهية</option>
                        <option value="expiring_soon" {{ request('expiry_filter') == 'expiring_soon' ? 'selected' : '' }}>تنتهي قريباً</option>
                    </select>
                </div>

                <!-- أزرار الفلترة -->
                <div class="md:col-span-4 flex justify-end gap-2">
                    <button type="submit"
                            class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-200 dark:bg-indigo-700 dark:hover:bg-indigo-600">
                        <i class="fas fa-search mr-1"></i> بحث
                    </button>
                    <a href="{{ route('admin.subscriptions.index') }}"
                       class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition duration-200 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500 flex items-center">
                        <i class="fas fa-redo mr-1"></i> إعادة تعيين
                    </a>
                </div>
            </form>
        </div>

        <!-- جدول الاشتراكات -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">الشركة</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">النوع</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">تاريخ البدء</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">تاريخ الانتهاء</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">السعر</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">الحالة</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($subscriptions as $subscription)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                                    <i class="fas fa-building text-indigo-600 dark:text-indigo-300"></i>
                                </div>
                                <div class="mr-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-200">{{ $subscription->company->company_name }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $subscription->company->Commercial_RegistrationNumber }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                            @switch($subscription->type)
                                @case('monthly') شهري @break
                                @case('quarterly') ربع سنوي @break
                                @case('semi-annual') نصف سنوي @break
                                @case('annual') سنوي @break
                                @default {{ $subscription->type }}
                            @endswitch
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $subscription->start_date->format('Y-m-d') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $subscription->end_date->isPast() ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 
                                   ($subscription->end_date->diffInDays(now()) <= 7 ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 
                                   'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200') }}">
                                {{ $subscription->end_date->format('Y-m-d') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ number_format($subscription->price, 2) }} ر.س</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $subscription->status == 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                                   ($subscription->status == 'inactive' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 
                                   'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200') }}">
                                @switch($subscription->status)
                                    @case('active') نشط @break
                                    @case('inactive') غير نشط @break
                                    @case('pending') معلق @break
                                    @default {{ $subscription->status }}
                                @endswitch
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2 space-x-reverse">
                                <a href="{{ route('admin.subscriptions.show', $subscription->id) }}"
                                   class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 transition-colors duration-200"
                                   title="عرض">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.subscriptions.edit', $subscription->id) }}"
                                   class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors duration-200"
                                   title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.subscriptions.destroy', $subscription->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-200"
                                            title="حذف"
                                            onclick="return confirm('هل أنت متأكد من حذف هذا الاشتراك؟')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                            لا توجد اشتراكات مسجلة
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- الترقيم -->
        @if($subscriptions->hasPages())
        <div class="bg-white dark:bg-gray-800 px-4 py-3 border-t border-gray-200 dark:border-gray-700 sm:px-6">
            {{ $subscriptions->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

