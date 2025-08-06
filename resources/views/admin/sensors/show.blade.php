@extends('admin.layouts.app')

@section('title', 'تفاصيل الحساس')
@section('header', 'تفاصيل الحساس')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <!-- معلومات الحساس الأساسية -->
    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <div class="flex flex-col md:flex-row gap-6">
            <!-- أيقونة الحساس -->
            <div class="flex-shrink-0 h-20 w-20 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                <i class="fas fa-sensor text-indigo-600 dark:text-indigo-300 text-2xl"></i>
            </div>
            
            <!-- معلومات الحساس -->
            <div class="flex-1">
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-200 mb-2">
                    @switch($sensor->name)
                        @case('heart_rate') معدل ضربات القلب @break
                        @case('blood_pressure') ضغط الدم @break
                        @case('gps') GPS @break
                        @case('obd') OBD @break
                        @case('weather') الطقس @break
                    @endswitch
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">النوع التفصيلي:</p>
                        <p class="text-gray-700 dark:text-gray-300">{{ $sensor->type }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">الشركة:</p>
                        <p class="text-gray-700 dark:text-gray-300">{{ $sensor->company->company_name }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">الشاحنة:</p>
                        <p class="text-gray-700 dark:text-gray-300">{{ $sensor->truck->plate_number }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">تاريخ الإضافة:</p>
                        <p class="text-gray-700 dark:text-gray-300">{{ $sensor->created_at->format('Y-m-d') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- آخر قراءات الحساس -->
    <div class="p-6">
        <h4 class="text-md font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
            <i class="fas fa-chart-line mr-2 text-indigo-600 dark:text-indigo-400"></i>
            آخر القراءات
        </h4>
        
        @if($sensor->sensorData->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">القيمة</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">الوقت</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($sensor->sensorData as $data)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                            {{ $data->value }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $data->timestamp->format('Y-m-d H:i') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 text-center">
            <p class="text-gray-500 dark:text-gray-400">لا توجد قراءات مسجلة لهذا الحساس</p>
        </div>
        @endif
    </div>

    <!-- أزرار التحكم -->
    <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 flex justify-end gap-3">
        <a href="{{ route('admin.sensors.edit', $sensor->id) }}"
           class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-200 dark:bg-indigo-700 dark:hover:bg-indigo-600 flex items-center">
            <i class="fas fa-edit mr-2"></i> تعديل
        </a>
        
        <form action="{{ route('admin.sensors.destroy', $sensor->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition duration-200 dark:bg-red-700 dark:hover:bg-red-600 flex items-center"
                    onclick="return confirm('هل أنت متأكد من حذف هذا الحساس؟')">
                <i class="fas fa-trash-alt mr-2"></i> حذف
            </button>
        </form>
        
        <a href="{{ route('admin.sensors.index') }}"
           class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition duration-200 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500">
            رجوع
        </a>
    </div>
</div>
@endsection