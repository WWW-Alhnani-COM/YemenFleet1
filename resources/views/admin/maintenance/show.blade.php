@extends('admin.layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-gray-800 dark:text-gray-100 font-bold text-xl">تفاصيل الصيانة</h1>

    <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl mt-6 p-6 space-y-6">
        {{-- الإجراءات --}}
        <div class="flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-100">
                <i class="fas fa-tools mr-2 text-indigo-500"></i>معلومات الصيانة
            </h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.maintenance.edit', $maintenance->id) }}" 
                   class="px-3 py-1.5 rounded-md bg-blue-600 hover:bg-blue-700 text-white text-sm">
                    <i class="fas fa-edit mr-1"></i>تعديل
                </a>
                @if(!$maintenance->invoice)
                <form action="{{ route('admin.maintenance.generate-invoice', $maintenance->id) }}" method="POST" class="inline-block">
                    @csrf
                    <button type="submit" class="px-3 py-1.5 rounded-md bg-green-600 hover:bg-green-700 text-white text-sm">
                        <i class="fas fa-file-invoice-dollar mr-1"></i>إنشاء فاتورة
                    </button>
                </form>
                @endif
            </div>
        </div>

        {{-- المعلومات --}}
        <div class="grid md:grid-cols-2 gap-6 text-sm text-gray-700 dark:text-gray-200">
            <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg shadow-inner space-y-2">
                <div class="flex justify-between border-b pb-2">
                    <span class="font-medium">رقم الصيانة:</span>
                    <span>#{{ $maintenance->id }}</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="font-medium">الشاحنة:</span>
                    <span>
                        {{ $maintenance->truck->plate_number }} - {{ $maintenance->truck->truck_name }}
                        <span class="ml-2 px-2 py-0.5 text-xs rounded bg-blue-200 dark:bg-blue-700 text-blue-900 dark:text-blue-100">
                            {{ $maintenance->truck->vehicle_status }}
                        </span>
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium">نوع الصيانة:</span>
                    <span>
                        @php
                            $typeColors = [
                                'preventive' => 'bg-sky-600',
                                'corrective' => 'bg-yellow-500',
                                'emergency'  => 'bg-red-600'
                            ];
                        @endphp
                        <span class="px-2 py-0.5 text-xs rounded text-white {{ $typeColors[$maintenance->type] ?? 'bg-gray-400' }}">
                            {{ $maintenance->type === 'preventive' ? 'وقائية' : ($maintenance->type === 'corrective' ? 'تصحيحية' : 'طارئة') }}
                        </span>
                    </span>
                </div>
            </div>

            <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg shadow-inner space-y-2">
                <div class="flex justify-between border-b pb-2">
                    <span class="font-medium">التكلفة:</span>
                    <span>{{ number_format($maintenance->cost, 2) }} <span class="text-xs text-gray-500">ر.س</span></span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="font-medium">تاريخ الصيانة:</span>
                    <span><i class="far fa-calendar-alt mr-1 text-indigo-400"></i>{{ $maintenance->date->format('Y-m-d H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium">حالة الفاتورة:</span>
                    <span>
                        @if($maintenance->invoice)
                            <span class="text-green-600 dark:text-green-400 font-medium">
                                <i class="fas fa-check-circle mr-1"></i>تم الفوترة
                            </span>
                            <a href="{{ route('admin.invoices.show', $maintenance->invoice->id) }}"
                               class="ml-2 text-blue-500 hover:underline text-sm">
                               عرض الفاتورة
                            </a>
                        @else
                            <span class="text-gray-500 dark:text-gray-300">
                                <i class="fas fa-clock mr-1"></i>قيد المعالجة
                            </span>
                        @endif
                    </span>
                </div>
            </div>
        </div>

        {{-- وصف الصيانة --}}
        <div class="bg-gray-100 dark:bg-gray-900 p-4 rounded-lg shadow-sm">
            <h3 class="font-semibold text-gray-800 dark:text-gray-100 mb-2">
                <i class="fas fa-align-left mr-2 text-purple-400"></i>وصف الصيانة
            </h3>
            <blockquote class="border-r-4 border-purple-500 pr-4 text-gray-700 dark:text-gray-200 text-sm leading-relaxed">
                {!! $maintenance->description ? nl2br(e($maintenance->description)) : '<span class="text-gray-400">لا يوجد وصف</span>' !!}
            </blockquote>
        </div>
    </div>
</div>
@endsection
