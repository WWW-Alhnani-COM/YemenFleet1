@extends('admin.layouts.app')

@section('title', 'تعديل الإشعار')
@section('header', 'تعديل الإشعار')

@section('content')
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
        <form action="{{ route('admin.notifications.update', $notification) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6">
                <!-- معلومات المرسل -->
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200 mb-2">المرسل</h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        {{ $notification->sender->name ?? 'غير معروف' }} ({{ class_basename($notification->sender_type) }})
                    </p>
                </div>

                <!-- معلومات المستقبل -->
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200 mb-2">المستقبل</h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        {{ $notification->notifiable->name ?? $notification->notifiable->company_name ?? 'غير معروف' }} 
                        ({{ class_basename($notification->notifiable_type) }})
                    </p>
                </div>

                <!-- نص الرسالة -->
                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">نص الرسالة</label>
                    <textarea name="message" id="message" rows="5"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200"
                        required>{{ old('message', $notification->message) }}</textarea>
                </div>

                <!-- حالة القراءة -->
                <div>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="is_read" value="1" 
                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600"
                               {{ $notification->is_read ? 'checked' : '' }}>
                        <span class="mr-2 text-sm text-gray-700 dark:text-gray-300">تمت القراءة</span>
                    </label>
                </div>

                <!-- أزرار الحفظ والإلغاء -->
                <div class="flex justify-end space-x-3 space-x-reverse">
                    <a href="{{ route('admin.notifications.show', $notification) }}"
                       class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition duration-200 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500">
                        إلغاء
                    </a>
                    <button type="submit"
                            class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-200 dark:bg-indigo-700 dark:hover:bg-indigo-600">
                        حفظ التغييرات
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection