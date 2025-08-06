@extends('admin.layouts.app')

@section('title', 'إضافة صيانة جديدة')
@section('header', 'إضافة صيانة جديدة')

@section('content')
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden p-4">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                <i class="fas fa-tools mr-2"></i> بيانات الصيانة الجديدة
            </h2>
        </div>

        <form action="{{ route('admin.maintenance.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- حقل الشاحنة -->
                <div>
                    <label for="truck_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        الشاحنة <span class="text-red-500">*</span>
                    </label>
                    <select name="truck_id" id="truck_id" 
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200" required>
                        <option value="">اختر الشاحنة</option>
                        @foreach($trucks as $truck)
                        <option value="{{ $truck->id }}" @if(old('truck_id')==$truck->id) selected @endif>
                            {{ $truck->plate_number }} - {{ $truck->truck_name }} ({{ $truck->vehicle_status }})
                        </option>
                        @endforeach
                    </select>
                    @error('truck_id')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- حقل نوع الصيانة -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        نوع الصيانة <span class="text-red-500">*</span>
                    </label>
                    <select name="type" id="type" 
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200" required>
                        <option value="">اختر النوع</option>
                        <option value="preventive" @if(old('type')=='preventive') selected @endif>صيانة وقائية</option>
                        <option value="corrective" @if(old('type')=='corrective') selected @endif>صيانة تصحيحية</option>
                        <option value="emergency" @if(old('type')=='emergency') selected @endif>صيانة طارئة</option>
                    </select>
                    @error('type')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- حقل التكلفة -->
                <div>
                    <label for="cost" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        التكلفة (ر.س) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" step="0.01" min="0" name="cost" id="cost" 
                           class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200" 
                           value="{{ old('cost') }}" required>
                    @error('cost')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- حقل تاريخ الصيانة -->
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        تاريخ الصيانة <span class="text-red-500">*</span>
                    </label>
                    <input type="datetime-local" name="date" id="date" 
                           class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200" 
                           value="{{ old('date') }}" required>
                    @error('date')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- حقل وصف الصيانة -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        وصف الصيانة
                    </label>
                    <textarea name="description" id="description" rows="3"
                              class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200">{{ old('description') }}</textarea>
                    @error('description')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- أزرار الحفظ والإلغاء -->
            <div class="mt-6 flex justify-end space-x-3 space-x-reverse">
                <a href="{{ route('admin.maintenance.index') }}" 
                   class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition duration-200 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500 inline-flex items-center">
                    <i class="fas fa-times mr-2"></i> إلغاء
                </a>
                <button type="submit" 
                        class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-200 dark:bg-indigo-700 dark:hover:bg-indigo-600 inline-flex items-center">
                    <i class="fas fa-save mr-2"></i> حفظ الصيانة
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // تهيئة Select2
        $('#truck_id').select2({
            placeholder: 'اختر الشاحنة',
            allowClear: true,
            width: '100%'
        });
        
        // تهيئة Select2 لنوع الصيانة
        $('#type').select2({
            placeholder: 'اختر نوع الصيانة',
            allowClear: true,
            width: '100%',
            minimumResultsForSearch: Infinity // إخفاء مربع البحث لأن الخيارات قليلة
        });
    });
</script>
@endpush