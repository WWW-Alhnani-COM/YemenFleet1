@extends('admin.layouts.app')

@section('title', 'تفاصيل الوجهة')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6 text-gray-900 dark:text-white">تفاصيل الوجهة #{{ $destination->id }}</h1>

    <div class="bg-gray-100 dark:bg-gray-800 p-6 rounded shadow-md max-w-3xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- نقطة البداية --}}
            <div>
                <p class="text-gray-500 dark:text-gray-400 text-sm">نقطة البداية</p>
                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $destination->start_point }}</p>
            </div>

            {{-- خطوط الطول والعرض --}}
            <div>
                <p class="text-gray-500 dark:text-gray-400 text-sm">إحداثيات البداية</p>
                <p class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ $destination->start_latitude }}, {{ $destination->start_longitude }}
                </p>
            </div>

            {{-- نقطة النهاية --}}
            <div>
                <p class="text-gray-500 dark:text-gray-400 text-sm">نقطة النهاية</p>
                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $destination->end_point }}</p>
            </div>

            {{-- العنوان التفصيلي للنهاية --}}
            <div>
                <p class="text-gray-500 dark:text-gray-400 text-sm">عنوان النهاية</p>
                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $destination->end_address }}</p>
            </div>

            {{-- التاريخ والوقت --}}
            <div>
                <p class="text-gray-500 dark:text-gray-400 text-sm">التاريخ والوقت</p>
                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $destination->date->format('Y-m-d H:i') }}</p>
            </div>

            {{-- السائق --}}
            <div>
                <p class="text-gray-500 dark:text-gray-400 text-sm">السائق</p>
                <p class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ $destination->task?->driver?->driver_name ?? 'غير محدد' }}
                </p>
            </div>

            {{-- طلب الشركة --}}
            <div>
                <p class="text-gray-500 dark:text-gray-400 text-sm">طلب الشركة</p>
                <p class="text-lg font-semibold text-gray-900 dark:text-white">
                    #{{ $destination->companyOrder?->id ?? '-' }} - {{ $destination->companyOrder?->company?->company_name ?? '-' }}
                </p>
            </div>
        </div>

        <div class="mt-6 flex justify-between">
            <a href="{{ route('admin.destinations.edit', $destination->id) }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">
                تعديل
            </a>
            <a href="{{ route('admin.destinations.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded shadow">
                عودة للقائمة
            </a>
        </div>
    </div>
</div>
@endsection
