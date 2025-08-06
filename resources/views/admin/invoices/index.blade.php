@extends('admin.layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-gray-800 dark:text-gray-200">قائمة الفواتير</h1>

    <!-- نموذج الفلترة -->
    <div class="card mb-4 shadow-sm bg-white dark:bg-gray-800 transition-colors duration-300">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">فلترة الفواتير</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.invoices.index') }}" class="space-y-4">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- فلترة بالعنوان -->
                    <div>
                        <label for="title" class="block text-gray-700 dark:text-gray-300 font-semibold mb-1">عنوان الفاتورة</label>
                        <input type="text" id="title" name="title" value="{{ request('title') }}"
                               class="w-full border border-gray-300 rounded-md px-3 py-2
                                      focus:outline-none focus:ring-2 focus:ring-blue-500
                                      dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" 
                               placeholder="بحث بعنوان الفاتورة...">
                    </div>

                    <!-- نطاق المبلغ -->
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-1">المبلغ من</label>
                        <input type="number" step="0.01" min="0" name="amount_min" value="{{ request('amount_min') }}"
                               class="w-full border border-gray-300 rounded-md px-3 py-2
                                      focus:outline-none focus:ring-2 focus:ring-blue-500
                                      dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                               placeholder="0.00">
                    </div>
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-1">المبلغ إلى</label>
                        <input type="number" step="0.01" min="0" name="amount_max" value="{{ request('amount_max') }}"
                               class="w-full border border-gray-300 rounded-md px-3 py-2
                                      focus:outline-none focus:ring-2 focus:ring-blue-500
                                      dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                               placeholder="0.00">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
                    <!-- تاريخ الإصدار من -->
                    <div>
                        <label for="issued_date_from" class="block text-gray-700 dark:text-gray-300 font-semibold mb-1">تاريخ الإصدار من</label>
                        <input type="date" id="issued_date_from" name="issued_date_from" value="{{ request('issued_date_from') }}"
                               class="w-full border border-gray-300 rounded-md px-3 py-2
                                      focus:outline-none focus:ring-2 focus:ring-blue-500
                                      dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                    </div>

                    <!-- تاريخ الإصدار إلى -->
                    <div>
                        <label for="issued_date_to" class="block text-gray-700 dark:text-gray-300 font-semibold mb-1">تاريخ الإصدار إلى</label>
                        <input type="date" id="issued_date_to" name="issued_date_to" value="{{ request('issued_date_to') }}"
                               class="w-full border border-gray-300 rounded-md px-3 py-2
                                      focus:outline-none focus:ring-2 focus:ring-blue-500
                                      dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                    </div>

                    <!-- تاريخ الاستحقاق من -->
                    <div>
                        <label for="due_date_from" class="block text-gray-700 dark:text-gray-300 font-semibold mb-1">تاريخ الاستحقاق من</label>
                        <input type="date" id="due_date_from" name="due_date_from" value="{{ request('due_date_from') }}"
                               class="w-full border border-gray-300 rounded-md px-3 py-2
                                      focus:outline-none focus:ring-2 focus:ring-blue-500
                                      dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                    </div>

                    <!-- تاريخ الاستحقاق إلى -->
                    <div>
                        <label for="due_date_to" class="block text-gray-700 dark:text-gray-300 font-semibold mb-1">تاريخ الاستحقاق إلى</label>
                        <input type="date" id="due_date_to" name="due_date_to" value="{{ request('due_date_to') }}"
                               class="w-full border border-gray-300 rounded-md px-3 py-2
                                      focus:outline-none focus:ring-2 focus:ring-blue-500
                                      dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                    </div>
                </div>

                <div class="mt-4 flex items-center gap-2">
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                        <i class="fas fa-filter"></i> فلترة
                    </button>
                    <a href="{{ route('admin.invoices.index') }}" 
                       class="px-4 py-2 bg-gray-400 text-gray-900 rounded-md hover:bg-gray-500 transition">
                        إعادة تعيين
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- جدول عرض الفواتير -->
    <div class="card shadow mb-4 bg-white dark:bg-gray-800 transition-colors duration-300">
        <div class="card-body p-0 overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
    <thead class="bg-gray-100 dark:bg-gray-800">
        <tr>
            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                رقم الفاتورة
            </th>
            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                عنوان الفاتورة
            </th>
            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                المبلغ (ر.س)
            </th>
            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                تاريخ الإصدار
            </th>
            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                تاريخ الاستحقاق
            </th>
            <th scope="col" class="relative px-6 py-3">
                <span class="sr-only">عرض</span>
            </th>
        </tr>
    </thead>
    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
        @foreach($invoices as $invoice)
        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100 text-right">
                #{{ $invoice->id }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300 text-right">
                {{ $invoice->title }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300 text-right">
                {{ number_format($invoice->amount, 2) }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300 text-right">
                {{ $invoice->issued_date->format('Y-m-d') }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300 text-right">
                {{ $invoice->due_date->format('Y-m-d') }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <a href="{{ route('admin.invoices.show', $invoice->id) }}" 
                   class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 flex items-center justify-end space-x-1 rtl:space-x-reverse" 
                   title="عرض الفاتورة">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>عرض</span>
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

            <div class="p-4">
                {{ $invoices->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
