@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-6">ربط دفعة لطلب الشركة #{{ $companyOrder->company_order_id }}</h1>

    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h4 class="text-gray-700 dark:text-gray-300 font-bold mb-2">معلومات الطلب:</h4>
                <p class="text-gray-600 dark:text-gray-400">الشركة: {{ $companyOrder->company->company_name }}</p>
                <p class="text-gray-600 dark:text-gray-400">المبلغ: {{ number_format($companyOrder->total_amount, 2) }} ر.س</p>
            </div>
            <div>
                <h4 class="text-gray-700 dark:text-gray-300 font-bold mb-2">الدفعات المتاحة:</h4>
                <form action="{{ route('admin.company-orders.link-payment', $companyOrder->company_order_id) }}" method="POST">
                    @csrf
                    <select name="payment_id" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                        <option value="">اختر دفعة</option>
                        @foreach($payments as $payment)
                            <option value="{{ $payment->payment_id }}">
                                #{{ $payment->payment_id }} - {{ number_format($payment->amount, 2) }} ر.س
                                ({{ $payment->payment_date->format('Y-m-d') }})
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="mt-4 bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg">
                        <i class="fas fa-link mr-2"></i> ربط الدفعة
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection