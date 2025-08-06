@extends('admin.layouts.app')

@section('title', 'تعديل سائق')
@section('header', 'تعديل سائق')

@section('content')
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="p-6">
            <form action="{{ route('admin.drivers.update', $driver->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- اسم السائق -->
                    <div>
                        <label for="driver_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">اسم السائق <span class="text-red-500">*</span></label>
                        <input type="text" name="driver_name" id="driver_name" value="{{ old('driver_name', $driver->driver_name) }}"
                               class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200"
                               required>
                        @error('driver_name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- البريد الإلكتروني -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">البريد الإلكتروني <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email', $driver->email) }}"
                               class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200"
                               required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- الهاتف -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">رقم الهاتف <span class="text-red-500">*</span></label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $driver->phone) }}"
                               class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200"
                               required>
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- العنوان -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">العنوان <span class="text-red-500">*</span></label>
                        <textarea name="address" id="address" rows="2"
                                  class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200"
                                  required>{{ old('address', $driver->address) }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- الشركة -->
                    <div>
                        <label for="company_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">الشركة <span class="text-red-500">*</span></label>
                        <select name="company_id" id="company_id"
                               class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200"
                               required>
                            <option value="">اختر الشركة</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ old('company_id', $driver->company_id) == $company->id ? 'selected' : '' }}>
                                    {{ $company->company_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('company_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- الشاحنات (متعددة) -->
                 <div>
    <label for="truck_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">الشاحنة المخصصة</label>
    <select name="truck_id" id="truck_id"
        class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200">
        <option value="">-- اختر الشاحنة --</option>
        @foreach($trucks as $truck)
            <option value="{{ $truck->id }}"
                {{ old('truck_id') == $truck->id ? 'selected' : '' }}
                {{ $truck->driver_id ? 'disabled' : '' }}>
                {{ $truck->plate_number }}
                @if($truck->driver_id)
                    (محجوزة للسائق: {{ $truck->driver->driver_name ?? 'غير معروف' }})
                @endif
            </option>
        @endforeach
    </select>
    @error('truck_id')
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>

                <div class="mt-6 flex justify-end">
                    <button type="submit"
                            class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 transition duration-200 dark:bg-indigo-700 dark:hover:bg-indigo-600">
                        <i class="fas fa-save mr-2"></i> حفظ التغييرات
                    </button>
                    <a href="{{ route('admin.drivers.index') }}"
                       class="bg-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-400 transition duration-200 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500 mr-3">
                        <i class="fas fa-times mr-2"></i> إلغاء
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // تهيئة select2 للشاحنات المتعددة
    $(document).ready(function() {
        $('#truck_ids').select2({
            placeholder: "اختر الشاحنات",
            allowClear: true
        });
    });
</script>
@endpush