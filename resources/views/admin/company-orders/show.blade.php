@extends('admin.layouts.app')

@section('title', 'تفاصيل طلب الشركة #' . $companyOrder->id)

@section('content')
<div class="container mx-auto p-6 text-gray-100">
    <h1 class="text-3xl font-bold mb-6 text-white">تفاصيل طلب الشركة #{{ $companyOrder->id }}</h1>

    {{-- معلومات الطلب --}}
    <div class="bg-gray-800 shadow rounded-lg p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4 text-white">معلومات الطلب</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <p><span class="font-semibold">الشركة:</span> {{ $companyOrder->company->company_name }}</p>
                <p><span class="font-semibold">حالة الطلب:</span> 
                    <span class="font-semibold @if($companyOrder->status == 'completed') text-green-400
                                @elseif($companyOrder->status == 'processing') text-yellow-400
                                @elseif($companyOrder->status == 'canceled') text-red-400
                                @else text-gray-400 @endif">
                        {{ ucfirst($companyOrder->status) }}
                    </span>
                </p>
                <p><span class="font-semibold">تاريخ الإنشاء:</span> {{ $companyOrder->created_at->format('Y-m-d H:i') }}</p>
            </div>

            <div>
                <p><span class="font-semibold">رقم الطلب الأصلي:</span> {{ $companyOrder->order->id }}</p>
                <p><span class="font-semibold">تاريخ الطلب:</span> {{ $companyOrder->order->order_date->format('Y-m-d') }}</p>
                <p><span class="font-semibold">عنوان الشحن:</span> {{ $companyOrder->order->customer_location }}</p>
            </div>

            <div>
                <p><span class="font-semibold">اسم العميل:</span> {{ $companyOrder->order->customer->customer_name }}</p>
                <p><span class="font-semibold">البريد الإلكتروني:</span> {{ $companyOrder->order->customer->email }}</p>
                <p><span class="font-semibold">رقم الهاتف:</span> {{ $companyOrder->order->customer->phone ?? '---' }}</p>
            </div>
        </div>
    </div>

    {{-- جدول المنتجات --}}
    <div class="bg-gray-800 shadow rounded-lg p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4 text-white">منتجات طلب الشركة</h2>
       @if($filteredItems->count())
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-700 text-gray-100">
                <thead class="bg-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-right text-sm font-semibold">اسم المنتج</th>
                        <th class="px-4 py-2 text-right text-sm font-semibold">الكمية</th>
                        <th class="px-4 py-2 text-right text-sm font-semibold">سعر الوحدة</th>
                        <th class="px-4 py-2 text-right text-sm font-semibold">الإجمالي</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @foreach ($filteredItems as $item)
                    <tr>
                        <td class="px-4 py-2">{{ $item->product->name ?? 'غير متوفر' }}</td>
                        <td class="px-4 py-2">{{ $item->quantity }}</td>
                        <td class="px-4 py-2">{{ number_format($item->price, 2) }} ر.س</td>
                        <td class="px-4 py-2">{{ number_format($item->quantity * $item->price, 2) }} ر.س</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
            <p class="text-gray-400">لا توجد منتجات لهذا الطلب.</p>
        @endif
    </div>

    {{-- ملخص الدفع --}}
    <div class="bg-gray-800 shadow rounded-lg p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4 text-white">ملخص الدفع</h2>
        <p><span class="font-semibold">المبلغ الكلي:</span> {{ number_format($companyOrder->total_amount, 2) }} ر.س</p>
        <p><span class="font-semibold">حالة الدفع:</span> 
            @if($companyOrder->payment)
                <span class="font-semibold @if($companyOrder->payment->status == 'paid') text-green-400
                            @elseif($companyOrder->payment->status == 'partial') text-yellow-400
                            @elseif($companyOrder->payment->status == 'unpaid') text-red-400
                            @else text-gray-400 @endif">
                    {{ ucfirst($companyOrder->payment->status) }}
                </span>
            @else
                <span class="text-red-400 font-semibold">لم يتم الدفع</span>
            @endif
        </p>

        @if($companyOrder->payment)
        <div class="mt-4">
            <p><span class="font-semibold">تاريخ الدفع:</span> {{ $companyOrder->payment->paid_at ? $companyOrder->payment->paid_at->format('Y-m-d H:i') : '---' }}</p>
            <p><span class="font-semibold">طريقة الدفع:</span> {{ $companyOrder->payment->method ?? '---' }}</p>
            <p><span class="font-semibold">ملاحظات الدفع:</span> {{ $companyOrder->payment->notes ?? '---' }}</p>
        </div>
        @endif
    </div>

    {{-- أزرار التنقل --}}
    <div class="mt-8 flex gap-4">
        <a href="{{ route('admin.company-orders.index') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">
            العودة إلى قائمة الطلبات
        </a>
        <a href="{{ route('admin.orders.show', $companyOrder->order->id) }}" class="inline-block bg-gray-700 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded">
            عرض تفاصيل الطلب الأصلي
        </a>
    </div>
</div>
@endsection
