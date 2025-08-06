@extends('admin.layouts.app')

@section('title', 'إضافة اشتراك جديد')
@section('header', 'إضافة اشتراك جديد')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <div class="p-6">
        <form action="{{ route('admin.subscriptions.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- حقل الشركة -->
                <div>
                    <label for="company_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">الشركة *</label>
                    <select name="company_id" id="company_id" required
                            class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200">
                        <option value="">اختر الشركة</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>{{ $company->company_name }}</option>
                        @endforeach
                    </select>
                    @error('company_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- حقل نوع الاشتراك -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">نوع الاشتراك *</label>
                    <select name="type" id="type" required
                            class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200">
                        <option value="">اختر النوع</option>
                        <option value="monthly" {{ old('type') == 'monthly' ? 'selected' : '' }}>شهري</option>
                        <option value="yearly" {{ old('type') == 'yearly' ? 'selected' : '' }}>سنوي</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- حقل تاريخ البدء -->
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">تاريخ البدء *</label>
                    <input type="datetime-local" name="start_date" id="start_date" value="{{ old('start_date') }}" required
                           class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200">
                    @error('start_date')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- حقل تاريخ الانتهاء -->
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">تاريخ الانتهاء *</label>
                    <input type="datetime-local" name="end_date" id="end_date" value="{{ old('end_date') }}" required
                           class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200">
                    @error('end_date')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- حقل السعر -->
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">السعر *</label>
                    <input type="number" name="price" id="price" step="0.01" min="0" value="{{ old('price') }}" required
                           class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200">
                    @error('price')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- حقل الحالة -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">الحالة *</label>
                    <select name="status" id="status" required
                            class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200">
                        <option value="">اختر الحالة</option>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>نشط</option>
                        <option value="expired" {{ old('status') == 'expired' ? 'selected' : '' }}>منتهي الصلاحية</option>
                        <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>ملغى</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- حقل الدفع -->
                <div>
                    <label for="payment_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">طريقة الدفع</label>
                    <select name="payment_id" id="payment_id"
                            class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200">
                        <option value="">اختر طريقة الدفع</option>
                        @foreach($payments as $payment)
                            <option value="{{ $payment->id }}" {{ old('payment_id') == $payment->id ? 'selected' : '' }}>
                                {{ $payment->method }} ({{ $payment->amount }} ريال)
                            </option>
                        @endforeach
                    </select>
                    @error('payment_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="flex justify-end mt-6 space-x-3">
                <a href="{{ route('admin.subscriptions.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition duration-200 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500">
                    إلغاء
                </a>
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md shadow-sm hover:bg-indigo-700 transition duration-200 dark:bg-indigo-700 dark:hover:bg-indigo-600">
                    حفظ الاشتراك
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // عند تغيير نوع الاشتراك، حساب تاريخ الانتهاء تلقائياً
    document.getElementById('type').addEventListener('change', function() {
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        
        if (!startDateInput.value) return;
        
        const startDate = new Date(startDateInput.value);
        let endDate = new Date(startDate);
        
        if (this.value === 'monthly') {
            endDate.setMonth(startDate.getMonth() + 1);
        } else if (this.value === 'yearly') {
            endDate.setFullYear(startDate.getFullYear() + 1);
        } else {
            return;
        }
        
        // تنسيق التاريخ إلى YYYY-MM-DDThh:mm
        const formattedDate = endDate.toISOString().slice(0, 16);
        endDateInput.value = formattedDate;
    });

    // تعيين التاريخ والوقت الحاليين كقيمة افتراضية
    document.addEventListener('DOMContentLoaded', function() {
        const now = new Date();
        const formattedNow = now.toISOString().slice(0, 16);
        document.getElementById('start_date').value = formattedNow;
        
        // حساب تاريخ الانتهاء الافتراضي (شهري)
        const endDate = new Date(now);
        endDate.setMonth(now.getMonth() + 1);
        document.getElementById('end_date').value = endDate.toISOString().slice(0, 16);
    });
</script>
@endpush
@endsection