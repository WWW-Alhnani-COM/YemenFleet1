@extends('admin.layouts.app')

@section('title', 'تعديل الحساس')
@section('header', 'تعديل الحساس')

@section('content')
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="p-6">
            <form action="{{ route('admin.sensors.update', $sensor->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- نوع الحساس -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">نوع الحساس <span class="text-red-500">*</span></label>
                        <select name="name" id="name" required
                               class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200">
                            <option value="">اختر نوع الحساس</option>
                            <option value="heart_rate" {{ $sensor->name == 'heart_rate' ? 'selected' : '' }}>معدل ضربات القلب</option>
                            <option value="blood_pressure" {{ $sensor->name == 'blood_pressure' ? 'selected' : '' }}>ضغط الدم</option>
                            <option value="gps" {{ $sensor->name == 'gps' ? 'selected' : '' }}>GPS</option>
                            <option value="obd" {{ $sensor->name == 'obd' ? 'selected' : '' }}>OBD</option>
                            <option value="weather" {{ $sensor->name == 'weather' ? 'selected' : '' }}>الطقس</option>
                        </select>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- نوع الحساس (تفاصيل) -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">النوع (تفاصيل) <span class="text-red-500">*</span></label>
                        <input type="text" name="type" id="type" value="{{ old('type', $sensor->type) }}" required
                               class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200"
                               placeholder="أدخل نوع الحساس التفصيلي">
                        @error('type')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- الشركة -->
                    <div>
                        <label for="company_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">الشركة <span class="text-red-500">*</span></label>
                        <select name="company_id" id="company_id" required
                               class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200">
                            <option value="">اختر الشركة</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ $sensor->company_id == $company->id ? 'selected' : '' }}>{{ $company->company_name }}</option>
                            @endforeach
                        </select>
                        @error('company_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- الشاحنة -->
                    <div>
                        <label for="truck_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">الشاحنة <span class="text-red-500">*</span></label>
                        <select name="truck_id" id="truck_id" required
                               class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200">
                            <option value="">اختر الشاحنة</option>
                            @foreach($trucks as $truck)
                                <option value="{{ $truck->id }}" {{ $sensor->truck_id == $truck->id ? 'selected' : '' }}>{{ $truck->plate_number }}</option>
                            @endforeach
                        </select>
                        @error('truck_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- أزرار الحفظ والإلغاء -->
                <div class="flex items-center justify-end gap-3">
                    <button type="button" onclick="window.history.back()"
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition duration-200 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500">
                        إلغاء
                    </button>
                    <button type="submit"
                            class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-200 dark:bg-indigo-700 dark:hover:bg-indigo-600 flex items-center">
                        <i class="fas fa-save mr-2"></i> حفظ التغييرات
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        // تحديث قائمة الشاحنات عند تغيير الشركة
        document.getElementById('company_id').addEventListener('change', function() {
            const companyId = this.value;
            const truckSelect = document.getElementById('truck_id');
            
            if (companyId) {
                fetch(`/admin/trucks/by-company/${companyId}`)
                    .then(response => response.json())
                    .then(data => {
                        truckSelect.innerHTML = '<option value="">اختر الشاحنة</option>';
                        data.forEach(truck => {
                            truckSelect.innerHTML += `<option value="${truck.id}">${truck.plate_number}</option>`;
                        });
                    });
            } else {
                truckSelect.innerHTML = '<option value="">اختر الشاحنة</option>';
            }
        });
    </script>
    @endpush
@endsection