@extends('admin.layouts.app')

@section('title', 'تفاصيل التنبيه')
@section('header', 'تفاصيل التنبيه')

@section('content')
<div class="container mx-auto px-4 py-6 font-sans">
    <!-- بطاقة التنبيه الرئيسية -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden mb-8 border-l-4 border-{{ $alert->severity_color }}">
        <div class="p-8">
            <!-- رأس البطاقة -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-6">
                <div class="flex-1">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
                        <i class="fas fa-{{ $alert->alert_icon }} mr-3 text-{{ $alert->severity_color }}-500"></i>
                        {{ $alert->message }}
                    </h2>
                    
                    <div class="flex flex-wrap items-center gap-4 mt-4">
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold tracking-wide 
                            bg-{{ $alert->severity_color }}-100 text-{{ $alert->severity_color }}-800 dark:bg-{{ $alert->severity_color }}-900 dark:text-{{ $alert->severity_color }}-200">
                            <i class="fas fa-{{ $alert->severity_icon }} mr-2"></i>
                            {{ $alert->severity_text }}
                        </span>
                        
                        <div class="flex items-center text-gray-600 dark:text-gray-400">
                            <i class="far fa-clock ml-2"></i>
                            <span class="text-sm">{{ $alert->created_at->translatedFormat('l j F Y - H:i') }}</span>
                        </div>
                        
                        @if($alert->is_resolved)
                        <div class="flex items-center text-green-600 dark:text-green-400">
                            <i class="fas fa-check-circle ml-2"></i>
                            <span class="text-sm">تم الحل {{ $alert->resolved_at->diffForHumans() }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                
                <div class="flex gap-3">
                    <button onclick="window.print()" 
                            class="p-3 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition-all"
                            title="طباعة">
                        <i class="fas fa-print text-lg"></i>
                    </button>
                    
                    <form action="{{ route('admin.alerts.destroy', $alert->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="p-3 rounded-lg bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-300 hover:bg-red-200 dark:hover:bg-red-800 transition-all"
                                title="حذف"
                                onclick="return confirm('هل أنت متأكد من حذف هذا التنبيه؟')">
                            <i class="fas fa-trash-alt text-lg"></i>
                        </button>
                    </form>
                </div>
            </div>

            <!-- محتوى البطاقة -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-8">
                <!-- قسم التنبيه -->
                <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-xl border border-gray-200 dark:border-gray-600">
                    <h3 class="font-semibold text-xl mb-4 text-gray-900 dark:text-white pb-3 border-b border-gray-200 dark:border-gray-600 flex items-center">
                        <i class="fas fa-bell mr-3 text-blue-500"></i>
                        <span>معلومات التنبيه</span>
                    </h3>
                    <div class="space-y-4 text-gray-800 dark:text-gray-200">
                        <div class="flex justify-between items-center">
                            <span class="font-medium">النوع:</span>
                            <span class="flex items-center">
                                @if($alert->alert_type == 'driver')
                                    <i class="fas fa-user-tie mr-2 text-blue-500"></i> تنبيه سائق
                                @else
                                    <i class="fas fa-truck mr-2 text-orange-500"></i> تنبيه مركبة
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-medium">الحالة:</span>
                            <span class="font-semibold {{ $alert->is_resolved ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                @if($alert->is_resolved)
                                    <i class="fas fa-check-circle mr-1"></i> تم الحل
                                @else
                                    <i class="fas fa-exclamation-triangle mr-1"></i> قيد المعالجة
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-medium">تم إنشاؤه:</span>
                            <span>{{ $alert->created_at->diffForHumans() }}</span>
                        </div>
                        @if($alert->is_resolved)
                        <div class="flex justify-between items-center">
                            <span class="font-medium">تم الحل بواسطة:</span>
                            <span>{{ $alert->resolvedBy->name ?? 'غير معروف' }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- قسم الحساس -->
                <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-xl border border-gray-200 dark:border-gray-600">
                    <h3 class="font-semibold text-xl mb-4 text-gray-900 dark:text-white pb-3 border-b border-gray-200 dark:border-gray-600 flex items-center">
                        <i class="fas fa-microchip mr-3 text-purple-500"></i>
                        <span>معلومات الحساس</span>
                    </h3>
                    <div class="space-y-4 text-gray-800 dark:text-gray-200">
                        <div class="flex justify-between items-center">
                            <span class="font-medium">النوع:</span>
                            <span>{{ $alert->sensorData->sensor->name ?? 'غير معروف' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-medium">القراءة:</span>
                            <span class="font-mono bg-gray-200 dark:bg-gray-600 px-2 py-1 rounded text-sm">
                                {{ is_array($alert->sensorData->value) ? json_encode($alert->sensorData->value) : $alert->sensorData->value }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-medium">وقت القراءة:</span>
                            <span>{{ $alert->sensorData->timestamp->format('Y-m-d H:i') }}</span>
                        </div>
                    </div>
                </div>

                <!-- قسم المركبة -->
                <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-xl border border-gray-200 dark:border-gray-600">
                    <h3 class="font-semibold text-xl mb-4 text-gray-900 dark:text-white pb-3 border-b border-gray-200 dark:border-gray-600 flex items-center">
                        <i class="fas fa-truck mr-3 text-orange-500"></i>
                        <span>معلومات المركبة</span>
                    </h3>
                    <div class="space-y-4 text-gray-800 dark:text-gray-200">
                        <div class="flex justify-between items-center">
                            <span class="font-medium">رقم اللوحة:</span>
                            <span class="font-bold">{{ $alert->sensorData->sensor->truck->plate_number ?? 'غير معروف' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-medium">السائق:</span>
                            <span>{{ $alert->sensorData->sensor->truck->driver->driver_name ?? 'غير معروف' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-medium">الشركة:</span>
                            <span>{{ $alert->sensorData->sensor->truck->company->company_name ?? 'غير معروف' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- قسم البيانات التفصيلية -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden mb-8">
        <div class="p-8">
            <h3 class="font-semibold text-xl mb-6 text-gray-900 dark:text-white pb-4 border-b border-gray-200 dark:border-gray-600 flex items-center">
                <i class="fas fa-database mr-3 text-indigo-500"></i>
                <span>البيانات التفصيلية</span>
            </h3>
            <div class="overflow-x-auto">
                <pre class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg text-sm text-gray-800 dark:text-gray-200 font-mono border border-gray-200 dark:border-gray-600">{{ json_encode($alert->sensorData->value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            </div>
        </div>
    </div>

    <!-- قسم الإجراءات -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden">
        <div class="p-8">
            <h3 class="font-semibold text-xl mb-6 text-gray-900 dark:text-white pb-4 border-b border-gray-200 dark:border-gray-600 flex items-center">
                <i class="fas fa-cogs mr-3 text-yellow-500"></i>
                <span>الإجراءات</span>
            </h3>
            <div class="flex flex-wrap gap-4">
                @if(!$alert->is_resolved)
                <form action="{{ route('admin.alerts.resolve', $alert->id) }}" method="POST">
                    @csrf
                    <button type="submit" 
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl flex items-center transition-all shadow-md hover:shadow-lg">
                        <i class="fas fa-check-circle mr-3 text-lg"></i>
                        <span class="font-semibold">تم حل المشكلة</span>
                    </button>
                </form>
                @endif
                
                <a href="{{ route('admin.sensor_data.show', $alert->sensorData->id) }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl flex items-center transition-all shadow-md hover:shadow-lg">
                    <i class="fas fa-chart-line mr-3 text-lg"></i>
                    <span class="font-semibold">عرض بيانات الحساس</span>
                </a>
                
                <a href="{{ route('admin.trucks.show', $alert->sensorData->sensor->truck->id) }}" 
                   class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl flex items-center transition-all shadow-md hover:shadow-lg">
                    <i class="fas fa-truck mr-3 text-lg"></i>
                    <span class="font-semibold">تفاصيل المركبة</span>
                </a>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    pre {
        white-space: pre-wrap;
        word-wrap: break-word;
        line-height: 1.6;
        font-family: 'Tajawal', 'Courier New', monospace;
    }
    
    .font-sans {
        font-family: 'Tajawal', sans-serif;
    }
    
    .border-red-500 { border-color: #ef4444; }
    .border-yellow-500 { border-color: #eab308; }
    .border-green-500 { border-color: #22c55e; }
    
    .text-red-600 { color: #dc2626; }
    .text-yellow-600 { color: #ca8a04; }
    .text-green-600 { color: #16a34a; }
    
    .dark .text-red-400 { color: #f87171; }
    .dark .text-yellow-400 { color: #facc15; }
    .dark .text-green-400 { color: #4ade80; }
</style>
@endpush
@endsection