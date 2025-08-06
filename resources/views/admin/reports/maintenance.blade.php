@extends('admin.layouts.app')

@section('content')
<h1 class="text-3xl font-extrabold mb-8 text-gray-900 dark:text-gray-100">تقرير الصيانة</h1>

<form method="GET" class="mb-8 flex flex-wrap gap-6 items-end">
   <div class="w-48">
    <label for="truck_type" class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">نوع الشاحنة</label>
    <select name="truck_type" id="truck_type"
            class="border rounded-md px-3 py-2 w-full bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <option value="">اختر الشاحنة</option>
        @foreach($trucks as $truck)
            <option value="{{ $truck->truck_name }}" @selected(old('truck_type', $typeFilter) === $truck->truck_name)>{{ $truck->truck_name }}</option>
        @endforeach
    </select>
</div>


    <div class="w-48">
        <label for="maintenance_status" class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">حالة الصيانة</label>
        <select name="maintenance_status" id="maintenance_status" 
                class="border rounded-md px-3 py-2 w-full bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">كل الحالات</option>
            <option value="مكتملة" @selected($statusFilter === 'مكتملة')>مكتملة</option>
            <option value="قادمة" @selected($statusFilter === 'قادمة')>قادمة</option>
        </select>
    </div>

    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-md transition">تصفية</button>
    <button type="button" onclick="window.print()" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded-md transition ml-4">طباعة التقرير</button>
</form>

{{-- ملخص الصيانة --}}
<section class="mb-12">
    <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-gray-100">ملخص الصيانة حسب الشاحنة</h2>
    <div class="overflow-x-auto rounded-lg border border-gray-300 dark:border-gray-700 shadow-sm">
        <table class="w-full text-sm border-collapse">
            <thead class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                <tr>
                    <th class="border border-gray-300 dark:border-gray-700 p-4 text-right whitespace-nowrap">اسم الشاحنة</th>
                    <th class="border border-gray-300 dark:border-gray-700 p-4 text-center whitespace-nowrap">عدد مرات الصيانة</th>
                    <th class="border border-gray-300 dark:border-gray-700 p-4 text-center whitespace-nowrap">إجمالي التكلفة (ر.س)</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($summary as $item)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-150">
                        <td class="border border-gray-300 dark:border-gray-700 p-4 text-right text-gray-800 dark:text-gray-200 whitespace-nowrap">{{ $item->truck->truck_name ?? 'غير معروف' }}</td>
                        <td class="border border-gray-300 dark:border-gray-700 p-4 text-center text-gray-800 dark:text-gray-200 whitespace-nowrap">{{ $item->total }}</td>
                        <td class="border border-gray-300 dark:border-gray-700 p-4 text-center text-gray-800 dark:text-gray-200 whitespace-nowrap">{{ number_format($item->total_cost, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center p-6 text-gray-600 dark:text-gray-400">لا توجد بيانات</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

{{-- تفاصيل الصيانة --}}
<section>
    <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-gray-100">تفاصيل الصيانة</h2>
    <div class="overflow-x-auto rounded-lg border border-gray-300 dark:border-gray-700 shadow-sm">
        <table class="w-full text-sm border-collapse">
            <thead class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                <tr>
                    <th class="border border-gray-300 dark:border-gray-700 p-4 text-right whitespace-nowrap">الشاحنة</th>
                    <th class="border border-gray-300 dark:border-gray-700 p-4 text-center whitespace-nowrap">الشركة</th>
                    <th class="border border-gray-300 dark:border-gray-700 p-4 text-center whitespace-nowrap">نوع الصيانة</th>
                    <th class="border border-gray-300 dark:border-gray-700 p-4 text-center whitespace-nowrap">التكلفة (ر.س)</th>
                    <th class="border border-gray-300 dark:border-gray-700 p-4 text-center whitespace-nowrap">تاريخ الصيانة</th>
                    <th class="border border-gray-300 dark:border-gray-700 p-4 text-center whitespace-nowrap">الوصف</th>
                </tr>
            </thead>
            <tbody>
                @php
                    // دالة مساعدة لترجمة نوع الصيانة
                    function translateMaintenanceType($type) {
                        $map = [
                            'oil_change' => 'تغيير زيت',
                            'tire_replacement' => 'تغيير إطارات',
                            'engine_repair' => 'إصلاح المحرك',
                            'brake_service' => 'خدمة الفرامل',
                            'general_maintenance' => 'صيانة عامة',
                            'battery_replacement' => 'تغيير البطارية',
                            // أضف المزيد حسب بياناتك
                        ];
                        return $map[$type] ?? $type;
                    }
                @endphp

                @forelse ($maintenances as $maintenance)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-150">
                        <td class="border border-gray-300 dark:border-gray-700 p-4 text-right text-gray-800 dark:text-gray-200 whitespace-nowrap">{{ $maintenance->truck->truck_name ?? 'غير معروف' }}</td>
                        <td class="border border-gray-300 dark:border-gray-700 p-4 text-center text-gray-800 dark:text-gray-200 whitespace-nowrap">{{ $maintenance->truck->company->company_name ?? 'غير معروف' }}</td>
                        <td class="border border-gray-300 dark:border-gray-700 p-4 text-center text-gray-800 dark:text-gray-200 whitespace-nowrap">
                            {{ translateMaintenanceType($maintenance->type) }}
                        </td>
                        <td class="border border-gray-300 dark:border-gray-700 p-4 text-center text-gray-800 dark:text-gray-200 whitespace-nowrap">{{ number_format($maintenance->cost, 2) }}</td>
                        <td class="border border-gray-300 dark:border-gray-700 p-4 text-center text-gray-800 dark:text-gray-200 whitespace-nowrap">{{ $maintenance->date->format('Y-m-d') }}</td>
                        <td class="border border-gray-300 dark:border-gray-700 p-4 text-center text-gray-800 dark:text-gray-200 whitespace-nowrap">{{ $maintenance->description ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center p-6 text-gray-600 dark:text-gray-400">لا توجد بيانات</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

<style>
    @media print {
        form, button { display: none !important; }
        table { page-break-after: auto; }
        tr { page-break-inside: avoid; page-break-after: auto; }
        th, td { page-break-inside: avoid; page-break-after: auto; }
        body { background: white; color: black; }
    }
</style>

@endsection
