@extends('admin.layouts.app')

@section('title', 'تفاصيل المهمة #' . $task->id)

@section('content')
<div class="container mx-auto p-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white transition-colors duration-300">
            تفاصيل المهمة #{{ $task->id }}
        </h1>
    </div>

    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg space-y-8 transition-colors duration-300 text-gray-900 dark:text-gray-300">
        {{-- معلومات المهمة --}}
        <section>
            <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200 transition-colors duration-300">معلومات المهمة</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                <div class="space-y-2">
                    <p><span class="font-semibold text-gray-900 dark:text-white">الاسم:</span> {{ $task->name }}</p>
                    <p><span class="font-semibold text-gray-900 dark:text-white">الوصف:</span> {{ $task->description ?? '---' }}</p>
                    <p><span class="font-semibold text-gray-900 dark:text-white">الموعد النهائي:</span> {{ $task->deadline ? $task->deadline->format('Y-m-d H:i') : '---' }}</p>
                </div>
                <div class="space-y-2">
                    <p>
                        <span class="font-semibold text-gray-900 dark:text-white">الحالة:</span>
                        <span class="@if($task->status === 'completed') text-green-500 
                                     @elseif($task->status === 'in_progress') text-yellow-500 
                                     @else text-gray-500 dark:text-gray-400 @endif font-semibold">
                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                        </span>
                    </p>
                    <p><span class="font-semibold text-gray-900 dark:text-white">تاريخ الإنشاء:</span> {{ $task->created_at->format('Y-m-d H:i') }}</p>
                    <p><span class="font-semibold text-gray-900 dark:text-white">آخر تعديل:</span> {{ $task->updated_at->format('Y-m-d H:i') }}</p>
                </div>
            </div>
        </section>

        {{-- معلومات السائق --}}
        <section>
            <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200 transition-colors duration-300">معلومات السائق</h2>
            @if ($task->driver)
                <div class="text-sm space-y-2">
                    <p><span class="font-semibold text-gray-900 dark:text-white">الاسم:</span> {{ $task->driver->driver_name }}</p>
                    <p><span class="font-semibold text-gray-900 dark:text-white">البريد الإلكتروني:</span> {{ $task->driver->email ?? '---' }}</p>
                    <p><span class="font-semibold text-gray-900 dark:text-white">رقم الهاتف:</span> {{ $task->driver->phone ?? '---' }}</p>
                </div>
            @else
                <p class="text-red-500 dark:text-red-400 font-semibold">لا يوجد سائق مرتبط بهذه المهمة.</p>
            @endif
        </section>

        {{-- معلومات الوجهة --}}
        <section>
            <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200 transition-colors duration-300">معلومات الوجهة</h2>
            @if ($task->destination)
                <div class="text-sm space-y-2">
                    <p><span class="font-semibold text-gray-900 dark:text-white">المدينة:</span> {{ $task->destination->start_point }}</p>
                    <p><span class="font-semibold text-gray-900 dark:text-white">العنوان:</span> {{ $task->destination->end_point }}</p>
                    <p><span class="font-semibold text-gray-900 dark:text-white">الإحداثيات:</span> {{ $task->destination->start_latitude }}, {{ $task->destination->start_longitude }}</p>
                </div>
            @else
                <p class="text-yellow-500 dark:text-yellow-400 font-semibold">لا توجد وجهة مرتبطة بهذه المهمة.</p>
            @endif
        </section>

        {{-- الأزرار --}}
        <div class="flex justify-end space-x-2 rtl:space-x-reverse">
            <a href="{{ route('admin.tasks.edit', $task->id) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded transition-colors duration-300">
                تعديل
            </a>
            <a href="{{ route('admin.tasks.index') }}" 
               class="bg-gray-300 dark:bg-gray-700 hover:bg-gray-400 dark:hover:bg-gray-600 
                      text-gray-900 dark:text-gray-300 font-semibold py-2 px-4 rounded transition-colors duration-300">
                العودة
            </a>
        </div>
    </div>
</div>
@endsection
