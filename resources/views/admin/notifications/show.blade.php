@extends('admin.layouts.app')

@section('title', 'تفاصيل الإشعار')
@section('header', 'تفاصيل الإشعار')

@section('content')
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- معلومات المرسل -->
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200 mb-4">المرسل</h3>
                <div class="flex items-center space-x-4 space-x-reverse">
                    <div class="flex-shrink-0 h-12 w-12 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                        <i class="fas fa-user text-indigo-600 dark:text-indigo-300"></i>
                    </div>
                    <div>
                        <h4 class="text-md font-medium text-gray-900 dark:text-gray-200">{{ $notification->sender->name ?? 'غير معروف' }}</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ class_basename($notification->sender_type) }}</p>
                    </div>
                </div>
            </div>

            <!-- معلومات المستقبل -->
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200 mb-4">المستقبل</h3>
                <div class="flex items-center space-x-4 space-x-reverse">
                    <div class="flex-shrink-0 h-12 w-12 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center">
                        <i class="fas fa-{{ $notification->notifiable_type == 'App\Models\Company' ? 'building' : 'user' }} text-green-600 dark:text-green-300"></i>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-900 dark:text-gray-200">
    @if($notification->notifiable)
        {{ $notification->notifiable->customer_name ?? $notification->notifiable->company_name ?? '---' }}
    @else
        @if($notification->is_group_message)
            @if(str_contains(strtolower($notification->message), 'شركة') || $notification->notifiable_type === \App\Models\Company::class)
                {{ 'جميع الشركات' }}
            @elseif(str_contains(strtolower($notification->message), 'عميل') || $notification->notifiable_type === \App\Models\Customer::class)
                {{ 'جميع العملاء' }}
            @else
                {{ 'لجميع المستخدمين' }}
            @endif
        @else
            {{ 'غير محدد' }}
        @endif
    @endif
</div>

                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ class_basename($notification->notifiable_type) }}</p>
                    </div>
                </div>
            </div>

            <!-- تفاصيل الإشعار -->
            <div class="md:col-span-2 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200 mb-4">تفاصيل الإشعار</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">حالة القراءة</p>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-200">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $notification->is_read ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' }}">
                                {{ $notification->is_read ? 'مقروء' : 'غير مقروء' }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">نوع الإشعار</p>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-200">
                            {{ $notification->is_group_message ? 'إشعار جماعي' : 'إشعار فردي' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">تاريخ الإرسال</p>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-200">
                            {{ $notification->created_at->format('Y-m-d H:i') }} ({{ $notification->created_at->diffForHumans() }})
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">نص الرسالة</p>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-200 whitespace-pre-line">{{ $notification->message }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- زر الرجوع -->
        <div class="mt-6 flex justify-end">
            <a href="{{ route('admin.notifications.index') }}"
               class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition duration-200 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500">
                <i class="fas fa-arrow-left mr-2"></i> رجوع
            </a>
        </div>
    </div>
@endsection