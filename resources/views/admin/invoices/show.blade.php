@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6 text-gray-800 dark:text-gray-100">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">عرض الفاتورة #{{ $invoice->id }}</h1>
        <a href="{{ route('admin.invoices.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left ml-1"></i> عودة
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- معلومات الفاتورة -->
            <div>
                <h2 class="text-lg font-semibold mb-3 text-primary">معلومات الفاتورة</h2>
                <ul class="space-y-2 text-sm">
                    <li><span class="font-medium">العنوان:</span> {{ $invoice->title }}</li>
                    <li><span class="font-medium">المبلغ:</span> <span class="text-green-500 font-semibold">{{ number_format($invoice->amount, 2) }} ر.س</span></li>
                    <li><span class="font-medium">تاريخ الإصدار:</span> {{ $invoice->issued_date->format('Y-m-d') }}</li>
                    <li><span class="font-medium">تاريخ الاستحقاق:</span> {{ $invoice->due_date->format('Y-m-d') }}</li>
                    <li>
                        <span class="font-medium">الحالة:</span>
                        @if($invoice->status === 'paid')
                            <span class="badge bg-yellow-500 text-white">قيد الانتظار</span>
                        @else
                            <span class="badge bg-green-500 text-white">مدفوعة</span>
                            
                        @endif
                    </li>
                </ul>
            </div>

            <!-- معلومات الصيانة -->
            @if($invoice->maintenance)
            <div>
                <h2 class="text-lg font-semibold mb-3 text-primary">معلومات الصيانة المرتبطة</h2>
                <ul class="space-y-2 text-sm">
                    <li><span class="font-medium">رقم الصيانة:</span> #{{ $invoice->maintenance->id }}</li>
                    <li><span class="font-medium">نوع الصيانة:</span>
                        @php
                            $types = ['preventive' => 'وقائية', 'corrective' => 'تصحيحية', 'emergency' => 'طارئة'];
                            $colors = ['preventive' => 'blue', 'corrective' => 'orange', 'emergency' => 'red'];
                        @endphp
                        <span class="badge bg-{{ $colors[$invoice->maintenance->type] }}-500 text-white">
                            {{ $types[$invoice->maintenance->type] }}
                        </span>
                    </li>
                    <li><span class="font-medium">التكلفة:</span> {{ number_format($invoice->maintenance->cost, 2) }} ر.س</li>
                    <li><span class="font-medium">تاريخ الصيانة:</span> {{ $invoice->maintenance->date->format('Y-m-d H:i') }}</li>
                    <li><span class="font-medium">الشاحنة:</span> 
                        {{ $invoice->maintenance->truck->plate_number }} - {{ $invoice->maintenance->truck->model }}
                        <span class="badge bg-indigo-500 text-white ml-1">{{ $invoice->maintenance->truck->vehicle_status }}</span>
                    </li>
                </ul>
            </div>
            @endif
        </div>

        <div class="border-t pt-4">
            <h3 class="text-md font-semibold mb-2">الوصف:</h3>
            <div class="text-sm leading-relaxed">
                {!! $invoice->maintenance && $invoice->maintenance->description
                    ? nl2br(e($invoice->maintenance->description))
                    : 'لا يوجد وصف' !!}
            </div>
        </div>
    </div>
</div>
@endsection
