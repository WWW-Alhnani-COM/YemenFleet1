@extends('admin.layouts.app')

@section('content')
<h1 class="text-3xl font-extrabold mb-6 text-gray-900 dark:text-gray-100">تقرير أداء السائقين</h1>

<form method="GET" class="mb-6 flex flex-wrap gap-4 items-end">
    <div class="flex flex-col w-64">
        <label for="company_id" class="mb-2 font-semibold text-gray-700 dark:text-gray-300">الشركة</label>
        <select name="company_id" id="company_id" class="border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
            <option value="">-- اختر شركة --</option>
            @foreach(\App\Models\Company::all() as $company)
                <option value="{{ $company->id }}" @selected(request('company_id') == $company->id)>{{ $company->company_name }}</option>
            @endforeach
        </select>
    </div>

    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded-md transition duration-300">تصفية</button>

    <button type="button" onclick="window.print()" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-5 py-2 rounded-md transition duration-300 ml-2">طباعة التقرير</button>
</form>

<div class="overflow-x-auto border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm">
    <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700 table-auto">
        <thead class="bg-gray-100 dark:bg-gray-800">
            <tr>
                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">اسم السائق</th>
                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">الشركة</th>
                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">عدد الرحلات المكتملة</th>
                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">عدد الحوادث</th>
                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">عدد التأخيرات</th>
            </tr>
        </thead>
        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
            @forelse ($drivers as $driver)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                <td class="px-6 py-4 whitespace-nowrap text-right text-gray-900 dark:text-gray-100 font-medium">{{ $driver->driver_name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-gray-700 dark:text-gray-300">{{ $driver->company->company_name ?? '-' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-center text-gray-700 dark:text-gray-300">{{ $driver->trips_count }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-center text-gray-700 dark:text-gray-300">{{ $accidentsCount[$driver->id] ?? 0 }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-center text-gray-700 dark:text-gray-300">{{ $driver->delays_count }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">لا توجد بيانات</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<style>
@media print {
  form, button { display: none !important; }
  table { page-break-after: auto; }
  tr { page-break-inside: avoid; page-break-after: auto; }
  th, td { page-break-inside: avoid; page-break-after: auto; }
}
</style>
@endsection
