@extends('admin.layouts.app')

@section('title', 'إضافة بيانات حساس')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-white">إضافة بيانات حساس جديدة</h1>
</div>

<form action="{{ route('admin.sensor_data.store') }}" method="POST" class="bg-gray-800 p-6 rounded-lg space-y-6">
    @csrf

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- الحساس -->
        <div>
            <label for="sensor_id" class="block text-sm text-gray-300 mb-1">اختر الحساس</label>
            <select name="sensor_id" id="sensor_id" class="w-full rounded border-gray-600 bg-gray-700 text-white">
                <option value="">-- اختر --</option>
                @foreach ($sensors as $sensor)
                    <option value="{{ $sensor->id }}" {{ old('sensor_id') == $sensor->id ? 'selected' : '' }}>
                        {{ $sensor->name ?? 'حساس #' . $sensor->id }}
                    </option>
                @endforeach
            </select>
            @error('sensor_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- التاريخ والوقت -->
        <div>
            <label for="timestamp" class="block text-sm text-gray-300 mb-1">التاريخ والوقت</label>
            <input type="datetime-local" name="timestamp" id="timestamp" value="{{ old('timestamp') }}" class="w-full rounded border-gray-600 bg-gray-700 text-white">
            @error('timestamp')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- القيمة -->
        <div class="md:col-span-2">
            <label for="value" class="block text-sm text-gray-300 mb-1">القيمة (JSON أو نص)</label>
            <textarea name="value" id="value" rows="3" class="w-full rounded border-gray-600 bg-gray-700 text-white">{{ old('value') }}</textarea>
            @error('value')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- الموقع -->
        <div>
            <label for="location" class="block text-sm text-gray-300 mb-1">الموقع (اختياري)</label>
            <input type="text" name="location" id="location" value="{{ old('location') }}" class="w-full rounded border-gray-600 bg-gray-700 text-white">
            @error('location')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- نوع الطقس -->
        <div>
            <label for="weather_type" class="block text-sm text-gray-300 mb-1">نوع الطقس</label>
            <input type="text" name="weather_type" id="weather_type" value="{{ old('weather_type') }}" class="w-full rounded border-gray-600 bg-gray-700 text-white">
            @error('weather_type')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- حالة التنبيه -->
        <div>
            <label for="is_alerted" class="block text-sm text-gray-300 mb-1">حالة التنبيه</label>
            <select name="is_alerted" id="is_alerted" class="w-full rounded border-gray-600 bg-gray-700 text-white">
                <option value="0" {{ old('is_alerted') == '0' ? 'selected' : '' }}>لا</option>
                <option value="1" {{ old('is_alerted') == '1' ? 'selected' : '' }}>نعم</option>
            </select>
            @error('is_alerted')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- زر الإرسال -->
    <div class="flex justify-end">
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
            حفظ البيانات
        </button>
    </div>
</form>
@endsection
