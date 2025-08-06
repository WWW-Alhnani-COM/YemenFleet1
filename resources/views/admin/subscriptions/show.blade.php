@extends('admin.layouts.app')

@section('title', 'تفاصيل الاشتراك')
@section('header', 'تفاصيل الاشتراك')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                <i class="fas fa-file-contract mr-2"></i> تفاصيل الاشتراك
            </h3>
            <div class="flex space-x-3 space-x-reverse">
                <a href="{{ route('admin.subscriptions.edit', $subscription->id) }}" 
                   class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-200 dark:bg-indigo-700 dark:hover:bg-indigo-600 flex items-center">
                    <i class="fas fa-edit mr-2"></i> تعديل
                </a>
                <a href="{{ route('admin.subscriptions.index') }}" 
                   class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition duration-200 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500 flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> رجوع
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- معلومات الاشتراك -->
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <h4 class="font-semibold text-lg text-indigo-600 dark:text-indigo-400 mb-4 border-b pb-2 border-gray-200 dark:border-gray-600">
                    <i class="fas fa-info-circle mr-2"></i> معلومات الاشتراك
                </h4>
                
                <div class="space-y-4">
                    <div class="flex justify-between border-b border-gray-200 dark:border-gray-600 pb-2">
                        <span class="font-medium text-gray-700 dark:text-gray-300">نوع الاشتراك:</span>
                        <span class="text-gray-900 dark:text-gray-100">
                            @if($subscription->type == 'monthly')
                                شهري
                            @elseif($subscription->type == 'quarterly')
                                ربع سنوي
                            @elseif($subscription->type == 'semi-annual')
                                نصف سنوي
                            @elseif($subscription->type == 'annual')
                                سنوي
                            @else
                                {{ $subscription->type }}
                            @endif
                        </span>
                    </div>
                    
                    <div class="flex justify-between border-b border-gray-200 dark:border-gray-600 pb-2">
                        <span class="font-medium text-gray-700 dark:text-gray-300">تاريخ البدء:</span>
                        <span class="text-gray-900 dark:text-gray-100">{{ $subscription->start_date->format('Y-m-d') }}</span>
                    </div>
                    
                    <div class="flex justify-between border-b border-gray-200 dark:border-gray-600 pb-2">
                        <span class="font-medium text-gray-700 dark:text-gray-300">تاريخ الانتهاء:</span>
                        <span class="text-gray-900 dark:text-gray-100">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $subscription->end_date->isPast() ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 
                                   ($subscription->end_date->diffInDays(now()) <= 7 ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 
                                   'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200') }}">
                                {{ $subscription->end_date->format('Y-m-d') }}
                                @if($subscription->end_date->isPast())
                                    (منتهي)
                                @elseif($subscription->end_date->diffInDays(now()) <= 7)
                                    (تنتهي قريباً)
                                @endif
                            </span>
                        </span>
                    </div>
                    
                    <div class="flex justify-between border-b border-gray-200 dark:border-gray-600 pb-2">
                        <span class="font-medium text-gray-700 dark:text-gray-300">السعر:</span>
                        <span class="text-gray-900 dark:text-gray-100">{{ number_format($subscription->price, 2) }} ر.س</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="font-medium text-gray-700 dark:text-gray-300">الحالة:</span>
                        <span class="text-gray-900 dark:text-gray-100">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $subscription->status == 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                                   ($subscription->status == 'inactive' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 
                                   'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200') }}">
                                @if($subscription->status == 'active')
                                    نشط
                                @elseif($subscription->status == 'inactive')
                                    غير نشط
                                @elseif($subscription->status == 'pending')
                                    معلق
                                @else
                                    {{ $subscription->status }}
                                @endif
                            </span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- معلومات الشركة -->
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <h4 class="font-semibold text-lg text-indigo-600 dark:text-indigo-400 mb-4 border-b pb-2 border-gray-200 dark:border-gray-600">
                    <i class="fas fa-building mr-2"></i> معلومات الشركة
                </h4>
                
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0 h-12 w-12 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                        <i class="fas fa-building text-indigo-600 dark:text-indigo-300 text-xl"></i>
                    </div>
                    <div class="mr-4">
                        <h5 class="font-medium text-gray-900 dark:text-gray-100">{{ $subscription->company->company_name }}</h5>
                        <p class="text-sm text-gray-500 dark:text-gray-400">السجل التجاري: {{ $subscription->company->Commercial_RegistrationNumber }}</p>
                    </div>
                </div>
                
                <div class="space-y-3">
                    <div class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                        <i class="fas fa-envelope mr-2 text-gray-500 dark:text-gray-400"></i>
                        {{ $subscription->company->email_company }}
                    </div>
                    
                    <div class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                        <i class="fas fa-phone-alt mr-2 text-gray-500 dark:text-gray-400"></i>
                        {{ $subscription->company->phone_company }}
                    </div>
                    
                    <div class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                        <i class="fas fa-map-marker-alt mr-2 text-gray-500 dark:text-gray-400"></i>
                        {{ $subscription->company->address_company }}
                    </div>
                </div>
            </div>
        </div>

        <!-- معلومات الدفع -->
        @if($subscription->payment)
        <div class="mt-6 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
            <h4 class="font-semibold text-lg text-indigo-600 dark:text-indigo-400 mb-4 border-b pb-2 border-gray-200 dark:border-gray-600">
                <i class="fas fa-receipt mr-2"></i> معلومات الدفع
            </h4>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">رقم الدفع</p>
                    <p class="font-medium text-gray-700 dark:text-gray-300">{{ $subscription->payment->payment_number }}</p>
                </div>
                
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">المبلغ</p>
                    <p class="font-medium text-gray-700 dark:text-gray-300">{{ number_format($subscription->payment->amount, 2) }} ر.س</p>
                </div>
                
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">تاريخ الدفع</p>
                    <p class="font-medium text-gray-700 dark:text-gray-300">{{ $subscription->payment->payment_date->format('Y-m-d') }}</p>
                </div>
                
                <div class="md:col-span-3">
                    <p class="text-sm text-gray-500 dark:text-gray-400">الطريقة</p>
                    <p class="font-medium text-gray-700 dark:text-gray-300">{{ $subscription->payment->payment_method }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- زر التجديد -->
        @if(!$subscription->end_date->isPast())
        <div class="mt-6 text-right">
            <button type="button" onclick="document.getElementById('renew-modal').classList.remove('hidden')"
                    class="bg-green-600 text-white px-6 py-2 rounded-md shadow-sm hover:bg-green-700 transition duration-200 dark:bg-green-700 dark:hover:bg-green-600">
                <i class="fas fa-sync-alt mr-2"></i> تجديد الاشتراك
            </button>
        </div>

        <!-- نافذة التجديد -->
        <div id="renew-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
                <div class="mt-3 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-200 mb-4">تجديد الاشتراك</h3>
                    
                    <form action="{{ route('admin.subscriptions.renew', $subscription->id) }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="period" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">المدة</label>
                            <select name="period" id="period" required
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200">
                                <option value="1">شهر واحد</option>
                                <option value="3">3 أشهر</option>
                                <option value="6">6 أشهر</option>
                                <option value="12">سنة واحدة</option>
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label for="payment_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">رقم الدفع</label>
                            <input type="text" name="payment_id" id="payment_id" required
                                   class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200">
                        </div>
                        
                        <div class="items-center px-4 py-3">
                            <button type="submit"
                                    class="px-4 py-2 bg-green-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-300 dark:bg-green-700 dark:hover:bg-green-600">
                                تأكيد التجديد
                            </button>
                            <button type="button" onclick="document.getElementById('renew-modal').classList.add('hidden')"
                                    class="px-4 py-2 ml-3 bg-gray-300 text-gray-700 text-base font-medium rounded-md shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500">
                                إلغاء
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection