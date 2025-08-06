@extends('admin.layouts.app')

@section('title', 'تفاصيل الطلب #' . $order->id)
@section('header', 'تفاصيل الطلب #' . $order->id)

@section('content')
    @if(session('success'))
    <div id="alert-message" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6 dark:bg-green-900 dark:border-green-700 dark:text-green-200" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
            <svg class="fill-current h-6 w-6 text-green-500 dark:text-green-400" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <title>Close</title>
                <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
            </svg>
        </span>
    </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden mb-6">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- معلومات الطلب الأساسية -->
                <div class="col-span-1">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200 mb-4">معلومات الطلب</h3>
                    
                    <div class="space-y-3">
                        <div>
                            <span class="text-sm text-gray-500 dark:text-gray-400">رقم الطلب:</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-200">#{{ $order->id }}</span>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500 dark:text-gray-400">تاريخ الطلب:</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-200">{{ $order->order_date->format('Y-m-d H:i') }}</span>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500 dark:text-gray-400">حالة الطلب:</span>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $order->status == 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 
                                   ($order->status == 'processing' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 
                                   ($order->status == 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                                   'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200')) }}">
                                {{ __('orders.status.' . $order->status) }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- معلومات العميل -->
                <div class="col-span-1">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200 mb-4">معلومات العميل</h3>
                    
                    <div class="space-y-3">
                        <div>
                            <span class="text-sm text-gray-500 dark:text-gray-400">اسم العميل:</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-200">{{ $order->customer->customer_name }}</span>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500 dark:text-gray-400">البريد الإلكتروني:</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-200">{{ $order->customer->email }}</span>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500 dark:text-gray-400">رقم الهاتف:</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-200">{{ $order->customer->phone }}</span>
                        </div>
                    </div>
                </div>
                
                <!-- ملخص الطلب -->
                <div class="col-span-1">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200 mb-4">ملخص الطلب</h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500 dark:text-gray-400">عدد المنتجات:</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-200">{{ $order->items->sum('quantity') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500 dark:text-gray-400">المبلغ الإجمالي:</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-200">{{ number_format($order->total_price, 2) }} ر.س</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- تفاصيل عناصر الطلب -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden mb-6">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200 mb-4">عناصر الطلب</h3>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">المنتج</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">السعر</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">الكمية</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">المجموع</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($order->items as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full" src="{{ $item->product->getImageUrl() }}" alt="{{ $item->product->name }}">
                                    </div>
                                    <div class="mr-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-200">{{ $item->product->name }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">كود: {{ $item->product->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                {{ number_format($item->price, 2) }} ر.س
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                {{ $item->quantity }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200">
                                {{ number_format($item->calculateItemTotal(), 2) }} ر.س
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-right text-sm font-medium text-gray-900 dark:text-gray-200">
                                المجموع الإجمالي
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200">
                                {{ number_format($order->total_price, 2) }} ر.س
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    
    <!-- تحديث حالة الطلب -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200 mb-4">تحديث حالة الطلب</h3>
            
            <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST" class="flex items-center gap-4">
                @csrf
                
                <select name="status" id="status" class="border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200">
                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>قيد المعالجة</option>
                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>مكتمل</option>
                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>ملغى</option>
                </select>
                
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-200 dark:bg-indigo-700 dark:hover:bg-indigo-600">
                    <i class="fas fa-save mr-1"></i> تحديث الحالة
                </button>
            </form>
        </div>
    </div>
@endsection