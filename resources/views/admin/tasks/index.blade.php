@extends('admin.layouts.app')

@section('title', 'إدارة المهام')

@section('content')
<div class="container mx-auto p-6">
     {{-- رسالة النجاح --}}
    @if (session('success'))
        <div 
            class="mb-6 p-4 rounded bg-green-600 text-white text-center text-lg font-semibold"
            role="alert"
        >
            {{ session('success') }}
        </div>
    @endif
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-gray-100">قائمة المهام</h1>
        <a href="{{ route('admin.tasks.create') }}" 
           class="inline-block bg-green-600 hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-800 
                  text-white font-semibold py-2 px-6 rounded shadow-md transition duration-300">
            + مهمة جديدة
        </a>
    </div>

    {{-- فلترة --}}
    <form method="GET" class="bg-gray-100 dark:bg-gray-800 p-5 rounded-lg mb-8 shadow-md transition-colors duration-300">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-gray-800 dark:text-gray-200">
            <div>
                <label for="status" class="block text-sm font-semibold mb-2">حالة المهمة</label>
                <select name="status" id="status" 
                    class="w-full rounded border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 
                           text-gray-900 dark:text-gray-200 p-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">الكل</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>قيد التنفيذ</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>مكتملة</option>
                </select>
            </div>

            <div>
                <label for="driver_id" class="block text-sm font-semibold mb-2">السائق</label>
                <select name="driver_id" id="driver_id" 
                    class="w-full rounded border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 
                           text-gray-900 dark:text-gray-200 p-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">الكل</option>
                    @foreach($drivers as $driver)
                        <option value="{{ $driver->id }}" {{ request('driver_id') == $driver->id ? 'selected' : '' }}>
                            {{ $driver->driver_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end">
                <button type="submit" 
                    class="w-full bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 
                           text-white py-3 rounded font-semibold shadow transition duration-300">
                    تطبيق الفلترة
                </button>
            </div>
        </div>
    </form>

    {{-- جدول المهام --}}
    <div class="overflow-x-auto rounded-lg shadow-lg bg-white dark:bg-gray-900 transition-colors duration-300">
        @if($tasks->count())
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">#</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">اسم المهمة</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">السائق</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">الموعد النهائي</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">الحالة</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">التحكم</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                @foreach ($tasks as $task)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $task->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">{{ $task->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">{{ $task->driver->driver_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">{{ $task->deadline->format('Y-m-d H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $colors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'in_progress' => 'bg-blue-100 text-blue-800',
                                    'completed' => 'bg-green-100 text-green-800',
                                ];
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $colors[$task->status] ?? 'bg-gray-200 text-gray-800' }}">
                                {{ __('حالة: ' . str_replace('_', ' ', $task->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap flex justify-center gap-2">
                            <a href="{{ route('admin.tasks.show', $task) }}" 
                               class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded text-xs shadow-sm transition duration-200">
                               عرض
                            </a>
                            <a href="{{ route('admin.tasks.edit', $task) }}" 
                               class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-xs shadow-sm transition duration-200">
                               تعديل
                            </a>
                            <form action="{{ route('admin.tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs shadow-sm transition duration-200">
                                    حذف
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- ترقيم الصفحات --}}
        <div class="p-4 bg-gray-100 dark:bg-gray-800 rounded-b-lg">
            {{ $tasks->withQueryString()->links('pagination::tailwind') }}
        </div>
        @else
            <p class="p-6 text-center text-gray-500 dark:text-gray-400">لا توجد مهام حالياً.</p>
        @endif
    </div>
</div>
@endsection
