@extends('admin.layouts.app')

@section('title', 'إضافة حساس')

@section('content')
<div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-md">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-white">إضافة حساس جديد</h2>

    <form action="{{ route('admin.sensors.store') }}" method="POST">
        @csrf

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- حقل نوع الحساس -->
    <div class="space-y-2">
        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            اسم الحساس <span class="text-red-500">*</span>
        </label>
        <select name="name" id="name" required
            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm py-2.5 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-200 transition duration-200">
            <option value="">-- اختر الحساس --</option>
            @foreach(['heart_rate', 'blood_pressure', 'gps', 'obd', 'weather'] as $name)
                <option value="{{ $name }}" {{ old('name') == $name ? 'selected' : '' }}>
                    {{ ucfirst(str_replace('_', ' ', $name)) }}
                </option>
            @endforeach
        </select>
        @error('name')
            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
        @enderror
    </div>

    <!-- حقل النوع التفصيلي -->
    <div class="space-y-2">
        <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            النوع <span class="text-red-500">*</span>
        </label>
        <input type="text" name="type" id="type" value="{{ old('type') }}" required
            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm py-2.5 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-200 transition duration-200"
            placeholder="مثلًا: analog أو digital">
        @error('type')
            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
        @enderror
    </div>

    <!-- حقل الشركة -->
    <div class="space-y-2">
    <label for="company_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
        الشركة <span class="text-red-500">*</span>
    </label>
    <select name="company_id" id="company-select" required
        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm py-2.5 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-200 transition duration-200">
        <option value="" class="text-gray-900 dark:text-gray-200">-- اختر الشركة --</option>
        @foreach($companies as $company)
            <option value="{{ $company->id }}" 
                {{ old('company_id') == $company->id ? 'selected' : '' }}
                class="text-gray-900 dark:text-gray-200">
                {{ $company->company_name }}
            </option>
        @endforeach
    </select>
    @error('company_id')
        <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
    @enderror
</div>

    <!-- حقل الشاحنة -->
    <div class="space-y-2">
        <label for="truck_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            الشاحنة <span class="text-red-500">*</span>
        </label>
        <select name="truck_id" id="truck-select" required
            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm py-2.5 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-200 transition duration-200">
            <option value="">-- اختر الشاحنة --</option>
            @if(old('company_id'))
                @foreach(\App\Models\Truck::where('company_id', old('company_id'))->get() as $truck)
                    <option value="{{ $truck->id }}" {{ old('truck_id') == $truck->id ? 'selected' : '' }}>
                        {{ $truck->truck_name }}
                    </option>
                @endforeach
            @endif
        </select>
        @error('truck_id')
            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
        @enderror
    </div>
</div>

<!-- أزرار الحفظ والعودة -->
<div class="flex items-center justify-end space-x-4 pt-6">
    <a href="{{ route('admin.sensors.index') }}" class="flex items-center px-5 py-2.5 text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white transition duration-200">
        <i class="fas fa-arrow-left mr-2"></i> العودة
    </a>
    <button type="submit" class="flex items-center px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-200">
        <i class="fas fa-save mr-2"></i> حفظ الحساس
    </button>
</div>
    </form>
</div>

{{-- سكربت جلب الشاحنات --}}
<script>
document.getElementById('company-select').addEventListener('change', function () {
    let companyId = this.value;
    fetch(`/admin/companies/${companyId}/trucks`)
        .then(res => res.json())
        .then(data => {
            const truckSelect = document.getElementById('truck-select');
            truckSelect.innerHTML = '<option value="">-- اختر الشاحنة --</option>';
            data.forEach(truck => {
                truckSelect.innerHTML += `<option value="${truck.id}">${truck.truck_name}</option>`;
            });
        });
});
</script>
@endsection
