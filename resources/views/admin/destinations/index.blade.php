@extends('admin.layouts.app')

@section('title', 'إدارة الوجهات')

@section('content')
<div class="container mx-auto p-6">
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">الوجهات</h1>
        <a href="{{ route('admin.destinations.create') }}"
           class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow transition">
            إضافة وجهة جديدة
        </a>
    </div>

    {{-- رسائل النجاح --}}
    @if(session('success'))
        <div
            class="mb-6 p-4 rounded text-green-800 bg-green-100 dark:bg-green-900 dark:text-green-300"
            role="alert"
        >
            {{ session('success') }}
        </div>
    @endif

    {{-- نموذج الفلترة --}}
    <form method="GET" action="{{ route('admin.destinations.index') }}" class="mb-6 bg-gray-100 dark:bg-gray-800 p-4 rounded shadow">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

            {{-- فلترة حسب السائق --}}
            <div>
                <label for="driver_id" class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">السائق</label>
                <select name="driver_id" id="driver_id" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">جميع السائقين</option>
                    @foreach($drivers as $driver)
                        <option value="{{ $driver->id }}" @selected(request('driver_id') == $driver->id)>{{ $driver->driver_name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- فلترة حسب المهمة --}}
            <div>
                <label for="task_id" class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">المهمة</label>
                <select name="task_id" id="task_id" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">جميع المهام</option>
                    @foreach($tasks as $task)
                        <option value="{{ $task->id }}" @selected(request('task_id') == $task->id)>#{{ $task->id }} - {{ $task->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- فلترة حسب طلب الشركة --}}
            <div>
                <label for="company_order_id" class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">طلب الشركة</label>
                <select name="company_order_id" id="company_order_id" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">جميع الطلبات</option>
                    @foreach($companyOrders as $co)
                        <option value="{{ $co->id }}" @selected(request('company_order_id') == $co->id)>#{{ $co->id }} - {{ $co->company->name ?? 'غير معروف' }}</option>
                    @endforeach
                </select>
            </div>

            {{-- زر البحث --}}
            <div class="flex items-end">
                <button type="submit"
                        class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded shadow transition">
                    بحث
                </button>
            </div>
        </div>
    </form>

    {{-- جدول عرض الوجهات --}}
    <div class="overflow-x-auto bg-white dark:bg-gray-900 rounded shadow">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">#</th>
                    <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">نقطة البداية</th>
                    <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">نقطة النهاية</th>
                    <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">التاريخ</th>
                    <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">المهمة</th>
                    <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">السائق</th>
                    <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">العمليات</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($destinations as $destination)
                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                        <td class="px-4 py-3 whitespace-nowrap text-right text-sm text-gray-900 dark:text-gray-100">{{ $destination->id }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-right text-sm text-gray-900 dark:text-gray-100">{{ $destination->start_point }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-right text-sm text-gray-900 dark:text-gray-100">{{ $destination->end_point }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-right text-sm text-gray-900 dark:text-gray-100">{{ $destination->date->format('Y-m-d H:i') }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-right text-sm text-gray-900 dark:text-gray-100">
                            @if($destination->task)
                                #{{ $destination->task->id }} - {{ $destination->task->name }}
                            @else
                                ---
                            @endif
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-right text-sm text-gray-900 dark:text-gray-100">
                            {{ $destination->task->driver->driver_name ?? '---' }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-center text-sm space-x-2">
                            <a href="{{ route('admin.destinations.show', $destination->id) }}"
                               class="text-blue-600 hover:text-blue-800" title="عرض التفاصيل" aria-label="عرض التفاصيل">
                                <svg xmlns="http://www.w3.org/2000/svg" class="inline h-5 w-5" fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </a>
                            <a href="{{ route('admin.destinations.edit', $destination->id) }}"
                               class="text-yellow-500 hover:text-yellow-700" title="تعديل" aria-label="تعديل">
                                <svg xmlns="http://www.w3.org/2000/svg" class="inline h-5 w-5" fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M11 5h6m2 2v6m-6 6H5a2 2 0 01-2-2v-6a2 2 0 012-2h6"/>
                                </svg>
                            </a>
                            <form action="{{ route('admin.destinations.destroy', $destination->id) }}" method="POST" class="inline"
                                  onsubmit="return confirm('هل أنت متأكد من حذف هذه الوجهة؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800" title="حذف" aria-label="حذف">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="inline h-5 w-5" fill="none" viewBox="0 0 24 24"
                                         stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-6 text-gray-500 dark:text-gray-400">لا توجد وجهات.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- روابط الصفحات --}}
    <div class="mt-6">
        {{ $destinations->withQueryString()->links() }}
    </div>
</div>
@endsection
