@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">تقرير طلبات الشركات</h1>
        <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
            <i class="fas fa-print mr-2"></i> طباعة التقرير
        </button>
    </div>

    <!-- فلترة التقرير -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-6">
        <form id="reportFilter">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">الفترة من</label>
                    <input type="date" name="start_date" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                
                <div>
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">الفترة إلى</label>
                    <input type="date" name="end_date" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                
                <div class="flex items-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg w-full">
                        <i class="fas fa-filter mr-2"></i> تطبيق الفلترة
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- إحصائيات التقرير -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">طلبات حسب الشركة</h3>
            <canvas id="companyChart" height="300"></canvas>
        </div>
        
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">طلبات حسب الحالة</h3>
            <canvas id="statusChart" height="300"></canvas>
        </div>
        
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">إجمالي المبيعات</h3>
            <div class="text-3xl font-bold text-green-600 dark:text-green-400">
                {{ number_format($totalSales, 2) }} ر.س
            </div>
        </div>
    </div>

    <!-- جدول التقرير التفصيلي -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">الشركة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">عدد الطلبات</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">إجمالي المبيعات</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">معدل الطلب</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($report as $item)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900 dark:text-gray-200">
                            {{ $item->company->company_name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-gray-300">
                            {{ $item->total_orders }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-green-600 dark:text-green-400 font-bold">
                            {{ number_format($item->total_sales, 2) }} ر.س
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-gray-300">
                            {{ number_format($item->average_order, 2) }} ر.س
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // رسم مخطط الشركات
    const companyCtx = document.getElementById('companyChart').getContext('2d');
    new Chart(companyCtx, {
        type: 'bar',
        data: {
            // labels: @json($report->pluck('company.company_name')),
            datasets: [{
                label: 'عدد الطلبات',
                // data: @json($report->pluck('total_orders')),
                backgroundColor: '#3B82F6',
            }]
        }
    });

    // رسم مخطط الحالات
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'pie',
        data: {
            // labels: @json($statusReport->pluck('status')),
            datasets: [{
                // data: @json($statusReport->pluck('count')),
                backgroundColor: ['#10B981', '#3B82F6', '#F59E0B', '#EF4444'],
            }]
        }
    });
});
</script>
@endsection