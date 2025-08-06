@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-6">إنشاء طلب شركة جديد</h1>

    <form action="{{ route('admin.company-orders.store') }}" method="POST">
        @csrf
        
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">اختيار الطلب الأصلي</h3>
            
            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">الطلبات المتاحة</label>
                <select name="order_id" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                    <option value="">اختر طلباً</option>
                    @foreach($orders as $order)
                    <option value="{{ $order->order_id }}">
                        #{{ $order->order_id }} - {{ $order->customer->customer_name }} 
                        ({{ $order->order_date->format('Y-m-d') }})
                    </option>
                    @endforeach
                </select>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">الشركة</label>
                <select name="company_id" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                    <option value="">اختر شركة</option>
                    @foreach($companies as $company)
                    <option value="{{ $company->company_id }}">
                        {{ $company->company_name }} ({{ $company->sector }})
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg">
                <i class="fas fa-save mr-2"></i> إنشاء طلب الشركة
            </button>
        </div>
    </form>
</div>
@endsection