@extends('admin.layouts.app')

@section('title', 'تعديل اشتراك')
@section('header', 'تعديل اشتراك')

@section('content')
<!-- عرض رسائل الجلسة -->
@if(session('success'))
    <div class="mb-4 px-4 py-3 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded relative">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-4 px-4 py-3 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 rounded relative">
        {{ session('error') }}
    </div>
@endif
<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <div class="p-6">
        <!-- فورم التعديل -->
        <form action="{{ route('admin.subscriptions.update', $subscription) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- حقل الشركة -->
                <div>
                    <label for="company_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">الشركة *</label>
                    <select name="company_id" id="company_id" required
                            class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200">
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" {{ $subscription->company_id == $company->id ? 'selected' : '' }}>{{ $company->company_name }}</option>
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
                        <option value="monthly" {{ $subscription->type == 'monthly' ? 'selected' : '' }}>شهري</option>
                        <option value="yearly" {{ $subscription->type == 'yearly' ? 'selected' : '' }}>سنوي</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- حقل تاريخ البدء -->
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">تاريخ البدء *</label>
                    <input type="datetime-local" name="start_date" id="start_date" 
                           value="{{ old('start_date', $subscription->start_date ? $subscription->start_date->format('Y-m-d\TH:i') : '') }}" required
                           class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200">
                    @error('start_date')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- حقل تاريخ الانتهاء -->
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">تاريخ الانتهاء *</label>
                    <input type="datetime-local" name="end_date" id="end_date" 
                           value="{{ old('end_date', $subscription->end_date ? $subscription->end_date->format('Y-m-d\TH:i') : '') }}" required
                           class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200">
                    @error('end_date')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- حقل السعر -->
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">السعر *</label>
                    <input type="number" name="price" id="price" step="0.01" min="0" value="{{ old('price', $subscription->price) }}" required
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
                        <option value="active" {{ $subscription->status == 'active' ? 'selected' : '' }}>نشط</option>
                        <option value="expired" {{ $subscription->status == 'expired' ? 'selected' : '' }}>منتهي الصلاحية</option>
                        <option value="cancelled" {{ $subscription->status == 'cancelled' ? 'selected' : '' }}>ملغى</option>
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
                        <option value="">بدون دفع</option>
                        @foreach($payments as $payment)
                            <option value="{{ $payment->id }}" {{ $subscription->payment_id == $payment->id ? 'selected' : '' }}>
                                {{ $payment->method }} ({{ number_format($payment->amount, 2) }} ر.س)
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
                    حفظ التغييرات
                </button>
            </div>
        </form>

        <!-- فورم الحذف (منفصل) -->
        <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
            <div class="bg-red-50 dark:bg-red-900/10 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-red-800 dark:text-red-200">خطر: منطقة الحذف</h3>
                <p class="text-sm text-red-600 dark:text-red-300 mt-1">سيتم حذف الاشتراك بشكل دائم ولا يمكن استرجاعه</p>
                
                <form action="{{ route('admin.subscriptions.destroy', $subscription) }}" method="POST" class="mt-4">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md shadow-sm hover:bg-red-700 transition duration-200 dark:bg-red-700 dark:hover:bg-red-600 flex items-center" onclick="return confirm('هل أنت متأكد من رغبتك في حذف هذا الاشتراك؟ هذا الإجراء لا يمكن التراجع عنه.')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        حذف الاشتراك
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // حساب تاريخ الانتهاء تلقائياً عند تغيير نوع الاشتراك أو تاريخ البدء
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('type');
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');

        function calculateEndDate() {
            if (!startDateInput.value) return;

            const startDate = new Date(startDateInput.value);
            const endDate = new Date(startDate);

            if (typeSelect.value === 'monthly') {
                endDate.setMonth(startDate.getMonth() + 1);
            } else if (typeSelect.value === 'yearly') {
                endDate.setFullYear(startDate.getFullYear() + 1);
            }

            // تنسيق التاريخ إلى YYYY-MM-DDThh:mm
            const formattedDate = endDate.toISOString().slice(0, 16);
            endDateInput.value = formattedDate;
        }

        typeSelect.addEventListener('change', calculateEndDate);
        startDateInput.addEventListener('change', calculateEndDate);
    });
</script>
@endpush
@endsection