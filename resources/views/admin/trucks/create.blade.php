@extends('admin.layouts.app')

@section('title', 'إضافة شاحنة جديدة')
@section('header', 'إضافة شاحنة جديدة')

@section('content')
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="p-6">
            <form action="{{ route('admin.trucks.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- اسم الشاحنة -->
                    <div>
                        <label for="truck_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">اسم الشاحنة</label>
                        <input type="text" name="truck_name" id="truck_name" value="{{ old('truck_name') }}"
                               class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200"
                               required>
                        @error('truck_name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- رقم اللوحة -->
                    <div>
                        <label for="plate_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">رقم اللوحة</label>
                        <input type="text" name="plate_number" id="plate_number" value="{{ old('plate_number') }}"
                               class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200"
                               required>
                        @error('plate_number')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- رقم الشاصي -->
                    <div>
                        <label for="chassis_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">رقم الشاصي</label>
                        <input type="text" name="chassis_number" id="chassis_number" value="{{ old('chassis_number') }}"
                               class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200"
                               required>
                        @error('chassis_number')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- الشركة -->
                    <div>
                        <label for="company_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">الشركة</label>
                        <select name="company_id" id="company_id"
                               class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200"
                               required>
                            <option value="">اختر الشركة</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>{{ $company->company_name }}</option>
                            @endforeach
                        </select>
                        @error('company_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- السائق -->
                    <div>
                        <label for="driver_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">السائق (اختياري)</label>
                        <select name="driver_id" id="driver_id"
                               class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200">
                            <option value="">اختر السائق</option>
                            @foreach($drivers as $driver)
                                <option value="{{ $driver->id }}" {{ old('driver_id') == $driver->id ? 'selected' : '' }}>{{ $driver->driver_name }}</option>
                            @endforeach
                        </select>
                        @error('driver_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- خط الطول -->
                    <div>
                        <label for="longitude" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">خط الطول</label>
                        <input type="number" step="0.000001" name="longitude" id="longitude" value="{{ old('longitude') }}"
                               class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200">
                        @error('longitude')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- دائرة العرض -->
                    <div>
                        <label for="latitude" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">دائرة العرض</label>
                        <input type="number" step="0.000001" name="latitude" id="latitude" value="{{ old('latitude') }}"
                               class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200">
                        @error('latitude')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- حالة الشاحنة -->
                    <div>
                        <label for="vehicle_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">حالة الشاحنة</label>
                        <select name="vehicle_status" id="vehicle_status"
                               class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200">
                            <option value="">اختر الحالة</option>
                            <option value="نشطة" {{ old('vehicle_status') == 'نشطة' ? 'selected' : '' }}>نشطة</option>
                            <option value="متوقفة" {{ old('vehicle_status') == 'متوقفة' ? 'selected' : '' }}>متوقفة</option>
                            <option value="تحت الصيانة" {{ old('vehicle_status') == 'تحت الصيانة' ? 'selected' : '' }}>تحت الصيانة</option>
                        </select>
                        @error('vehicle_status')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit"
                            class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-200 dark:bg-indigo-700 dark:hover:bg-indigo-600">
                        حفظ البيانات
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
