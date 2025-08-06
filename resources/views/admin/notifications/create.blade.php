@extends('admin.layouts.app')

@section('title', 'إرسال إشعار جديد')
@section('header', 'إرسال إشعار جديد')

@section('content')

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6 dark:bg-green-900 dark:border-green-700 dark:text-green-200" role="alert">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6 dark:bg-red-900 dark:border-red-700 dark:text-red-200" role="alert">
        {{ session('error') }}
    </div>
@endif

@if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6 dark:bg-red-900 dark:border-red-700 dark:text-red-200">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
    <form action="{{ route('admin.notifications.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 gap-6">

            <!-- نص الرسالة -->
            <div>
                <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">نص الرسالة *</label>
                <textarea name="message" id="message" rows="5"
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200"
                    placeholder="أدخل نص الرسالة هنا..." required>{{ old('message') }}</textarea>
                @error('message')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- نوع المستقبل -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">نوع المستقبل *</label>
                <div class="space-y-2">
                    @php
                        $types = [
                            'all_companies' => 'جميع الشركات',
                            'specific_company' => 'شركة محددة',
                            'all_users' => 'جميع العملاء',
                            'specific_user' => 'عميل محدد',
                        ];
                        $oldReceiver = old('receiver_type', 'all_companies');
                    @endphp
                    @foreach($types as $value => $label)
                        <div class="flex items-center">
                            <input id="{{ $value }}" name="receiver_type" type="radio" value="{{ $value }}"
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 dark:border-gray-600 dark:bg-gray-700"
                                   {{ $oldReceiver === $value ? 'checked' : '' }}>
                            <label for="{{ $value }}" class="mr-3 block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $label }}</label>
                        </div>
                    @endforeach
                </div>
                @error('receiver_type')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- اختيار الشركة -->
            <div id="company_select_container" class="{{ $oldReceiver === 'specific_company' ? '' : 'hidden' }}">
                <label for="company_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">اختر الشركة *</label>
                <select name="company_id" id="company_id"
                       class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200">
                    <option value="">اختر الشركة</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                            {{ $company->company_name }}
                        </option>
                    @endforeach
                </select>
                @error('company_id')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- اختيار العميل -->
            <div id="user_select_container" class="{{ old('receiver_type') == 'specific_user' ? '' : 'hidden' }}">
                <label for="user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">اختر العميل *</label>
                <select name="user_id" id="user_id"
                       class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200">
                    <option value="">اختر العميل</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} - {{ $user->email }}
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- زر الإرسال -->
            <div class="flex justify-end">
                <button type="submit"
                        class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 transition duration-200 dark:bg-indigo-700 dark:hover:bg-indigo-600">
                    <i class="fas fa-paper-plane mr-2"></i> إرسال الإشعار
                </button>
            </div>

        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const receiverTypeRadios = document.querySelectorAll('input[name="receiver_type"]');
        const companySelect = document.getElementById('company_select_container');
        const userSelect = document.getElementById('user_select_container');

        function updateSelectVisibility() {
            const selectedValue = document.querySelector('input[name="receiver_type"]:checked').value;
            
            companySelect.classList.add('hidden');
            userSelect.classList.add('hidden');
            
            if (selectedValue === 'specific_company') {
                companySelect.classList.remove('hidden');
            } else if (selectedValue === 'specific_user') {
                userSelect.classList.remove('hidden');
            }
        }

        // تهيئة أولية
        updateSelectVisibility();

        // مستمع تغيرات الراديو
        receiverTypeRadios.forEach(radio => {
            radio.addEventListener('change', updateSelectVisibility);
        });
    });
</script>
@endpush

@endsection
