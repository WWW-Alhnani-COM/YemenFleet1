@extends('admin.layouts.app')

@section('title', 'تفاصيل بيانات حساس')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-white">تفاصيل بيانات حساس</h1>
</div>

<div class="bg-gray-800 p-6 rounded-lg space-y-4 text-white">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <h3 class="font-semibold mb-1">الحساس</h3>
            <p>{{ $sensorDatum->sensor->name ?? 'غير محدد' }}</p>
        </div>

        <div>
            <h3 class="font-semibold mb-1">التاريخ والوقت</h3>
            <p>{{ \Carbon\Carbon::parse($sensorDatum->timestamp)->format('Y-m-d H:i') }}</p>
        </div>

        <div class="md:col-span-2">
            <h3 class="font-semibold mb-1">القيمة</h3>
            <pre class="whitespace-pre-wrap bg-gray-700 p-3 rounded">{{ $sensorDatum->value }}</pre>
        </div>

        <div>
            <h3 class="font-semibold mb-1">الموقع</h3>
            <p>{{ $sensorDatum->location ?? 'غير محدد' }}</p>
        </div>

        <div>
            <h3 class="font-semibold mb-1">نوع الطقس</h3>
            <p>{{ $sensorDatum->weather_type ?? 'غير محدد' }}</p>
        </div>

        <div>
            <h3 class="font-semibold mb-1">حالة التنبيه</h3>
            <p>
                @if ($sensorDatum->is_alerted)
                    <span class="text-red-500 font-bold">تم التنبيه</span>
                @else
                    <span class="text-green-400 font-bold">لا يوجد تنبيه</span>
                @endif
            </p>
        </div>
    </div>

    <div class="mt-6 flex space-x-4">
        <a href="{{ route('admin.sensor_data.index') }}" class="bg-gray-600 px-4 py-2 rounded hover:bg-gray-700 transition">العودة للقائمة</a>
        <a href="{{ route('admin.sensor_data.edit', $sensorDatum->id) }}" class="bg-blue-600 px-4 py-2 rounded hover:bg-blue-700 transition">تعديل البيانات</a>
    </div>
</div>
@endsection
