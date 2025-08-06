@extends('admin.layouts.app')

@section('title', 'تعديل الحادث')
@section('header', 'تعديل الحادث')

@section('content')

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">

        <form action="{{ route('admin.accidents.update', $accident->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- الشاحنة -->
                <div>
                    <label for="truck_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">الشاحنة</label>
                    <select name="truck_id" id="truck_id"
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                        <option value="">-- اختر شاحنة --</option>
                        @foreach($trucks as $truck)
                            <option value="{{ $truck->id }}" {{ old('truck_id', $accident->truck_id) == $truck->id ? 'selected' : '' }}>
                                {{ $truck->truck_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('truck_id')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- موقع الحادث -->
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">موقع الحادث</label>
                    <input type="text" name="location" id="location" value="{{ old('location', $accident->location) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                    @error('location')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- نوع الحادث -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">نوع الحادث</label>
                    <input type="text" name="type" id="type" value="{{ old('type', $accident->type) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                    @error('type')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- تاريخ الحادث -->
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">تاريخ الحادث</label>
                    <input type="date" name="date" id="date" value="{{ old('date', $accident->date ? $accident->date->format('Y-m-d') : '') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                    @error('date')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- وصف الحادث -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">وصف الحادث</label>
                    <textarea name="description" id="description" rows="4"
                              class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">{{ old('description', $accident->description) }}</textarea>
                    @error('description')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-6 rounded-md shadow-md transition dark:bg-indigo-700 dark:hover:bg-indigo-600">
                    <i class="fas fa-save mr-2"></i> حفظ التعديلات
                </button>
            </div>
        </form>
    </div>

@endsection
