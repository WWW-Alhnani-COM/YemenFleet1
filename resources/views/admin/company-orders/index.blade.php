@extends('admin.layouts.app')

@section('title', 'إدارة طلبات الشركات')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold text-white mb-4">طلبات الشركات</h1>

    {{-- إحصائيات --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
        <div class="bg-gray-800 text-white rounded-xl p-4 shadow">
            <div class="text-sm">إجمالي الطلبات</div>
            <div class="text-xl font-bold">{{ $totalOrders }}</div>
        </div>
        <div class="bg-green-800 text-white rounded-xl p-4 shadow">
            <div class="text-sm">مدفوعة</div>
            <div class="text-xl font-bold">{{ $paidOrders }}</div>
        </div>
        <div class="bg-red-700 text-white rounded-xl p-4 shadow">
            <div class="text-sm">غير مدفوعة</div>
            <div class="text-xl font-bold">{{ $unpaidOrders }}</div>
        </div>
        <div class="bg-blue-700 text-white rounded-xl p-4 shadow">
            <div class="text-sm">مكتملة</div>
            <div class="text-xl font-bold">{{ $completedOrders }}</div>
        </div>
        <div class="bg-yellow-600 text-white rounded-xl p-4 shadow">
            <div class="text-sm">قيد التنفيذ</div>
            <div class="text-xl font-bold">{{ $processingOrders }}</div>
        </div>
        <div class="bg-gray-600 text-white rounded-xl p-4 shadow">
            <div class="text-sm">ملغاة</div>
            <div class="text-xl font-bold">{{ $canceledOrders }}</div>
        </div>
    </div>

    {{-- الفلاتر --}}
    <form method="GET" class="bg-gray-900 p-4 rounded-xl mb-6 shadow text-white grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <div>
            <label class="block mb-1">الشركة</label>
            <select name="company_id" class="w-full rounded p-2 bg-gray-800 border border-gray-700">
                <option value="">الكل</option>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                        {{ $company->company_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block mb-1">حالة الطلب</label>
            <select name="status" class="w-full rounded p-2 bg-gray-800 border border-gray-700">
                <option value="">الكل</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>قيد التنفيذ</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>مكتمل</option>
                <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>ملغى</option>
            </select>
        </div>

        <div>
            <label class="block mb-1">حالة الدفع</label>
            <select name="payment_status" class="w-full rounded p-2 bg-gray-800 border border-gray-700">
                <option value="">الكل</option>
                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>مدفوع</option>
                <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>غير مدفوع</option>
                <option value="partial" {{ request('payment_status') == 'partial' ? 'selected' : '' }}>مدفوع جزئياً</option>
            </select>
        </div>

        <div class="flex items-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded w-full">
                تطبيق الفلاتر
            </button>
        </div>

        <div>
            <label class="block mb-1">من تاريخ</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full rounded p-2 bg-gray-800 border border-gray-700 text-white">
        </div>

        <div>
            <label class="block mb-1">إلى تاريخ</label>
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full rounded p-2 bg-gray-800 border border-gray-700 text-white">
        </div>

        <div>
            <label class="block mb-1">أقل مبلغ</label>
            <input type="number" step="0.01" name="min_amount" value="{{ request('min_amount') }}" class="w-full rounded p-2 bg-gray-800 border border-gray-700 text-white">
        </div>

        <div>
            <label class="block mb-1">أعلى مبلغ</label>
            <input type="number" step="0.01" name="max_amount" value="{{ request('max_amount') }}" class="w-full rounded p-2 bg-gray-800 border border-gray-700 text-white">
        </div>
    </form>

    {{-- جدول الطلبات --}}
    <div class="bg-gray-900 rounded-xl overflow-x-auto shadow">
        <table class="w-full text-sm text-left text-gray-300">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="p-3">#</th>
                    <th class="p-3">الشركة</th>
                    <th class="p-3">العميل</th>
                    <th class="p-3">الحالة</th>
                    <th class="p-3">الدفع</th>
                    <th class="p-3">المجموع</th>
                    <th class="p-3">التاريخ</th>
                    <th class="p-3">التحكم</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($companyOrders as $order)
                    <tr class="border-t border-gray-700 hover:bg-gray-800">
                        <td class="p-3">{{ $order->id }}</td>
                        <td class="p-3">{{ $order->company->company_name ?? '-' }}</td>
                        <td class="p-3">{{ $order->order->customer->customer_name ?? '-' }}</td>
                        <td class="p-3">
                            <span class="px-2 py-1 rounded text-xs
                                @if($order->status == 'completed') bg-green-700 text-white
                                @elseif($order->status == 'processing') bg-blue-700 text-white
                                @elseif($order->status == 'canceled') bg-red-700 text-white
                                @else bg-yellow-700 text-white @endif">
                                {{ $order->status }}
                            </span>
                        </td>
                        <td class="p-3">
                            {{ $order->payment->status ?? 'غير مدفوع' }}
                        </td>
                        <td class="p-3">{{ number_format($order->total_amount, 2) }} ر.س</td>
                        <td class="p-3">{{ $order->created_at }}</td>
                        <td class="px-4 py-2 whitespace-nowrap text-right text-sm font-medium">
    <a href="{{ route('admin.company-orders.show', $order->id) }}" 
       class="text-indigo-600 hover:text-indigo-900 font-semibold">
       عرض التفاصيل
    </a>
</td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-4 text-center text-gray-400">لا توجد طلبات مطابقة للفلترة.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- روابط الصفحات --}}
    <div class="mt-6">
        {{ $companyOrders->withQueryString()->links('pagination::tailwind') }}
    </div>
</div>
@endsection
