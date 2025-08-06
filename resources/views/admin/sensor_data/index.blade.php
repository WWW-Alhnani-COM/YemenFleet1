@extends('admin.layouts.app')

@section('title', 'إدارة بيانات الحساسات')
@section('content')

@if(session('success'))
<div id="alert-message" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6 dark:bg-green-900 dark:border-green-700 dark:text-green-200" role="alert">
    <span class="block sm:inline">{{ session('success') }}</span>
    <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" onclick="document.getElementById('alert-message').remove()">
        <svg class="fill-current h-6 w-6 text-green-500 dark:text-green-400" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
            <title>إغلاق</title>
            <path d="M14.348 14.849a1.2 1.2 0 01-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 11-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 111.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 111.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 010 1.698z"/>
        </svg>
    </span>
</div>
@endif

<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
    <div>
        <a href="{{ route('admin.sensor_data.create') }}" 
           class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-200 dark:bg-indigo-700 dark:hover:bg-indigo-600 flex items-center justify-center">
            <i class="fas fa-plus mr-2"></i> إضافة بيانات حساس جديدة
        </a>
    </div>
</div>

<!-- فلترة بيانات الحساسات -->
<div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow mb-6">
    <form action="{{ route('admin.sensor_data.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <!-- فلترة حسب اسم الحساس -->
        <div>
            <label for="sensor_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">الحساس</label>
            <select name="sensor_id" id="sensor_id" class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200">
                <option value="">الكل</option>
                @foreach($sensors as $sensor)
                    <option value="{{ $sensor->id }}" {{ request('sensor_id') == $sensor->id ? 'selected' : '' }}>{{ $sensor->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- فلترة حسب الموقع -->
        <div>
            <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">الموقع</label>
            <input type="text" name="location" id="location" value="{{ request('location') }}"
                   class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200"
                   placeholder="ابحث بالموقع">
        </div>

        <!-- فلترة حسب نوع الطقس -->
        <div>
            <label for="weather_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">نوع الطقس</label>
            <select name="weather_type" id="weather_type" class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200">
                <option value="">الكل</option>
                <option value="normal" {{ request('weather_type') == 'normal' ? 'selected' : '' }}>عادي</option>
                <option value="rainy" {{ request('weather_type') == 'rainy' ? 'selected' : '' }}>ممطر</option>
                <option value="extreme" {{ request('weather_type') == 'extreme' ? 'selected' : '' }}>قاسي</option>
            </select>
        </div>

        <!-- فلترة حسب حالة التنبيه -->
        <div>
            <label for="is_alerted" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">حالة التنبيه</label>
            <select name="is_alerted" id="is_alerted" class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200">
                <option value="">الكل</option>
                <option value="1" {{ request('is_alerted') === '1' ? 'selected' : '' }}>تم التنبيه</option>
                <option value="0" {{ request('is_alerted') === '0' ? 'selected' : '' }}>بدون تنبيه</option>
            </select>
        </div>

        <!-- أزرار البحث -->
        <div class="flex items-end gap-2">
            <button type="submit"
                    class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-200 dark:bg-indigo-700 dark:hover:bg-indigo-600 h-[42px]">
                <i class="fas fa-search mr-1"></i> بحث
            </button>
            <a href="{{ route('admin.sensor_data.index') }}"
               class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition duration-200 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500 h-[42px] flex items-center">
                <i class="fas fa-redo mr-1"></i> إعادة تعيين
            </a>
        </div>
    </form>
</div>

<!-- جدول بيانات الحساسات -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-right">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">الحساس</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">التاريخ والوقت</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">الموقع</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">نوع الطقس</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">حالة التنبيه</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($sensorData as $data)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">{{ $data->sensor->name ?? 'غير محدد' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">{{ $data->timestamp->format('Y-m-d H:i') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">{{ $data->location ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">{{ $data->weather_type ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold {{ $data->is_alerted ? 'text-red-500' : 'text-green-600' }}">
                        {{ $data->is_alerted ? 'تم التنبيه' : 'بدون تنبيه' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center space-x-2 space-x-reverse">
                            <a href="{{ route('admin.sensor_data.show', $data->id) }}"
                               class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 transition-colors duration-200"
                               title="عرض">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.sensor_data.edit', $data->id) }}"
                               class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors duration-200"
                               title="تعديل">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.sensor_data.destroy', $data->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا العنصر؟');" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-200" title="حذف">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-6 text-gray-500 dark:text-gray-400">لا توجد بيانات</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-4 bg-gray-100 dark:bg-gray-900">
        {{ $sensorData->appends(request()->query())->links() }}
    </div>
</div>

@endsection
