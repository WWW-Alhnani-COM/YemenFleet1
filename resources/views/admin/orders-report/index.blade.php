@extends('admin.layouts.app')

@section('content')
<h1 class="text-3xl font-bold mb-6 text-gray-900 dark:text-gray-100">تقرير الطلبات حسب الشركة والحالة</h1>

<form method="GET" class="mb-6 flex flex-wrap gap-4 items-end">

    <div>
        <label for="company_id" class="block mb-1 text-gray-700 dark:text-gray-300 font-medium">اختر الشركة</label>
        <select name="company_id" id="company_id" class="w-48 border border-gray-300 rounded px-3 py-2 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200">
            <option value="">كل الشركات</option>
            @foreach($companies as $company)
                <option value="{{ $company->id }}" @selected(request('company_id') == $company->id)>{{ $company->company_name }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="from_date" class="block mb-1 text-gray-700 dark:text-gray-300 font-medium">من تاريخ</label>
        <input type="date" id="from_date" name="from_date" value="{{ request('from_date') }}" class="border border-gray-300 rounded px-3 py-2 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200">
    </div>

    <div>
        <label for="to_date" class="block mb-1 text-gray-700 dark:text-gray-300 font-medium">إلى تاريخ</label>
        <input type="date" id="to_date" name="to_date" value="{{ request('to_date') }}" class="border border-gray-300 rounded px-3 py-2 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200">
    </div>

    <div class="flex gap-2">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white rounded px-5 py-2 transition">تصفية</button>
        <button type="button" onclick="window.print()" class="bg-green-600 hover:bg-green-700 text-white rounded px-5 py-2 transition">طباعة التقرير</button>
    </div>
</form>

<div class="overflow-x-auto rounded shadow-lg border border-gray-300 dark:border-gray-700">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-100 dark:bg-gray-900">
            <tr>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">الشركة</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">حالة الطلب</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">عدد الطلبات</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">إجمالي المبلغ</th>
            </tr>
        </thead>
        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
            @forelse ($report as $row)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-6 py-4 whitespace-nowrap text-right text-gray-900 dark:text-gray-100">{{ $row->company_name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-gray-900 dark:text-gray-100">{{ $row->status }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-gray-900 dark:text-gray-100">{{ $row->total_orders }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-green-600 dark:text-green-400 font-semibold">{{ number_format($row->total_amount, 2) }} ر.س</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">لا توجد بيانات لعرضها</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection

@push('styles')
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #app, #app * {
            visibility: visible;
        }
        #app {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        form, button {
            display: none !important;
        }
    }
</style>
@endpush
