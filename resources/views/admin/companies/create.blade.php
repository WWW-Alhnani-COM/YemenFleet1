@extends('admin.layouts.app')

@section('title', 'إضافة شركة جديدة')
@section('header', 'إضافة شركة جديدة')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <div class="p-6">
        <form action="{{ route('admin.companies.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- حقل اسم الشركة -->
                <div>
                    <label for="company_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">اسم الشركة *</label>
                    <input type="text" name="company_name" id="company_name" value="{{ old('company_name') }}" required
                           class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200">
                    @error('company_name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- حقل البريد الإلكتروني -->
                <div>
                    <label for="email_company" class="block text-sm font-medium text-gray-700 dark:text-gray-300">البريد الإلكتروني *</label>
                    <input type="email" name="email_company" id="email_company" value="{{ old('email_company') }}" required
                           class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200">
                    @error('email_company')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- حقل كلمة المرور -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">كلمة المرور *</label>
                    <input type="password" name="password" id="password" required
                           class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- حقل عنوان الشركة -->
                <div>
                    <label for="address_company" class="block text-sm font-medium text-gray-700 dark:text-gray-300">عنوان الشركة *</label>
                    <input type="text" name="address_company" id="address_company" value="{{ old('address_company') }}" required
                           class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200">
                    @error('address_company')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- حقل هاتف الشركة -->
                <div>
                    <label for="phone_company" class="block text-sm font-medium text-gray-700 dark:text-gray-300">هاتف الشركة *</label>
                    <input type="text" name="phone_company" id="phone_company" value="{{ old('phone_company') }}" required
                           pattern="[0-9]+" title="يجب إدخال أرقام فقط"
                           oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                           class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200">
                    @error('phone_company')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- حقل اسم المالك -->
                <div>
                    <label for="owner_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">اسم المالك *</label>
                    <input type="text" name="owner_name" id="Owner_Name" value="{{ old('owner_name') }}" required
                           class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200">
                    @error('owner_name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- حقل هاتف المالك -->
                <div>
                    <label for="phone_owner" class="block text-sm font-medium text-gray-700 dark:text-gray-300">هاتف المالك *</label>
                    <input type="text" name="phone_owner" id="phone_owner" value="{{ old('phone_owner') }}" required
                           pattern="[0-9]+" title="يجب إدخال أرقام فقط"
                           oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                           class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200">
                    @error('phone_owner')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- حقل السجل التجاري -->
                <div>
                    <label for="commercial_reg_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">رقم السجل التجاري *</label>
                    <input type="text" name="commercial_reg_number" id="commercial_reg_number" value="{{ old('commercial_reg_number') }}" required
                           pattern="[0-9]+" title="يجب إدخال أرقام فقط"
                           oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                           class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200">
                    @error('commercial_reg_number')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- حقل النشاط الاقتصادي -->
                <div>
                    <label for="economic_activity" class="block text-sm font-medium text-gray-700 dark:text-gray-300">النشاط الاقتصادي *</label>
                    <select name="economic_activity" id="economic_activity" required
                            class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200">
                        <option value="">اختر النشاط الاقتصادي</option>
                        <option value="نقل بضائع" {{ old('economic_activity') == 'نقل بضائع' ? 'selected' : '' }}>نقل بضائع</option>
                        <option value="نقل ركاب" {{ old('economic_activity') == 'نقل ركاب' ? 'selected' : '' }}>نقل ركاب</option>
                        <option value="خدمات لوجستية" {{ old('economic_activity') == 'خدمات لوجستية' ? 'selected' : '' }}>خدمات لوجستية</option>
                    </select>
                    @error('economic_activity')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- حقل نوع الأسطول -->
                <div>
                    <label for="fleet_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">نوع الأسطول *</label>
                    <select name="fleet_type" id="fleet_type" required
                            class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200">
                        <option value="">اختر نوع الأسطول</option>
                        <option value="صغيرة" {{ old('fleet_type') == 'صغيرة' ? 'selected' : '' }}>صغيرة (أقل من 10)</option>
                        <option value="متوسطة" {{ old('fleet_type') == 'متوسطة' ? 'selected' : '' }}>متوسطة (10-50)</option>
                        <option value="كبيرة" {{ old('fleet_type') == 'كبيرة' ? 'selected' : '' }}>كبيرة (أكثر من 50)</option>
                    </select>
                    @error('fleet_type')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end mt-6 space-x-3">
                <a href="{{ route('admin.companies.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition duration-200 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500">
                    إلغاء
                </a>
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md shadow-sm hover:bg-indigo-700 transition duration-200 dark:bg-indigo-700 dark:hover:bg-indigo-600">
                    حفظ الشركة
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // التحقق من صحة رقم الهاتف عند الإرسال
    document.querySelector('form').addEventListener('submit', function(e) {
        const phoneCompany = document.getElementById('phone_company');
        const phoneOwner = document.getElementById('phone_Owner');
        const commercialNumber = document.getElementById('Commercial_RegistrationNumber');
        
        if(!/^\d+$/.test(phoneCompany.value)) {
            e.preventDefault();
            alert('يجب أن يحتوي هاتف الشركة على أرقام فقط');
            phoneCompany.focus();
            return false;
        }
        
        if(!/^\d+$/.test(phoneOwner.value)) {
            e.preventDefault();
            alert('يجب أن يحتوي هاتف المالك على أرقام فقط');
            phoneOwner.focus();
            return false;
        }
        
        if(!/^\d+$/.test(commercialNumber.value)) {
            e.preventDefault();
            alert('يجب أن يحتوي السجل التجاري على أرقام فقط');
            commercialNumber.focus();
            return false;
        }
    });
</script>
@endpush
@endsection