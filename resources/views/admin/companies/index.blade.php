@extends('admin.layouts.app')

@section('title', 'إدارة الشركات')
@section('header', 'إدارة الشركات')

@section('content')
    @if(session('success'))
    <div id="alert-message" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6 dark:bg-green-900 dark:border-green-700 dark:text-green-200" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
            <svg class="fill-current h-6 w-6 text-green-500 dark:text-green-400" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <title>Close</title>
                <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
            </svg>
        </span>
    </div>
    @endif

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
            <a href="{{ route('admin.companies.create') }}"
               class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-200 dark:bg-indigo-700 dark:hover:bg-indigo-600 flex items-center justify-center">
                <i class="fas fa-plus mr-2"></i> إضافة شركة جديدة
            </a>
        </div>
    </div>

    <!-- فلترة الشركات -->
    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow mb-6">
        <form action="{{ route('admin.companies.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- فلترة حسب اسم الشركة -->
            <div>
                <label for="company_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">اسم الشركة</label>
                <input type="text" name="company_name" id="company_name" value="{{ request('company_name') }}"
                       class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200"
                       placeholder="ابحث باسم الشركة">
            </div>

            <!-- فلترة حسب النشاط الاقتصادي -->
            <div>
                <label for="economic_activity" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">النشاط الاقتصادي</label>
                <select name="economic_activity" id="economic_activity"
                       class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200">
                    <option value="">الكل</option>
                    <option value="نقل بضائع" {{ request('economic_activity') == 'نقل بضائع' ? 'selected' : '' }}>نقل بضائع</option>
                    <option value="نقل ركاب" {{ request('economic_activity') == 'نقل ركاب' ? 'selected' : '' }}>نقل ركاب</option>
                    <option value="خدمات لوجستية" {{ request('economic_activity') == 'خدمات لوجستية' ? 'selected' : '' }}>خدمات لوجستية</option>
                </select>
            </div>

            <!-- فلترة حسب نوع الأسطول -->
            <div>
                <label for="fleet_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">نوع الأسطول</label>
                <select name="fleet_type" id="fleet_type"
                       class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200">
                    <option value="">الكل</option>
                    <option value="صغيرة" {{ request('fleet_type') == 'صغيرة' ? 'selected' : '' }}>صغيرة (أقل من 10)</option>
                    <option value="متوسطة" {{ request('fleet_type') == 'متوسطة' ? 'selected' : '' }}>متوسطة (10-50)</option>
                    <option value="كبيرة" {{ request('fleet_type') == 'كبيرة' ? 'selected' : '' }}>كبيرة (أكثر من 50)</option>
                </select>
            </div>

            <!-- أزرار الفلترة -->
            <div class="flex items-end gap-2">
                <button type="submit"
                        class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-200 dark:bg-indigo-700 dark:hover:bg-indigo-600 h-[42px]">
                    <i class="fas fa-search mr-1"></i> بحث
                </button>
                <a href="{{ route('admin.companies.index') }}"
                   class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition duration-200 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500 h-[42px] flex items-center">
                    <i class="fas fa-redo mr-1"></i> إعادة تعيين
                </a>
            </div>
        </form>
    </div>

    <!-- جدول الشركات -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">اسم الشركة</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">البريد الإلكتروني</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">الهاتف</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">النشاط الاقتصادي</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">نوع الأسطول</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($companies as $company)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                                    <i class="fas fa-building text-indigo-600 dark:text-indigo-300"></i>
                                </div>
                                <div class="mr-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-200">{{ $company->company_name }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">السجل: {{ $company->commercial_reg_number }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $company->email_company }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $company->phone_company }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $company->economic_activity == 'نقل بضائع' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 
                                   ($company->economic_activity == 'نقل ركاب' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                                   'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200') }}">
                                {{ $company->economic_activity }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $company->fleet_type == 'صغيرة' ? 'bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-200' : 
                                   ($company->fleet_type == 'متوسطة' ? 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200' : 
                                   'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200') }}">
                                {{ $company->fleet_type }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2 space-x-reverse">
                                <a href="{{ route('admin.companies.show', $company->id) }}"
                                   class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 transition-colors duration-200"
                                   title="عرض">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.companies.edit', $company->id) }}"
                                   class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors duration-200"
                                   title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.companies.destroy', $company->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-200"
                                            title="حذف"
                                            onclick="return confirm('هل أنت متأكد من حذف هذه الشركة؟')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                            @if(request()->anyFilled(['company_name', 'economic_activity', 'fleet_type']))
                                لا توجد نتائج مطابقة لمعايير البحث
                            @else
                                لا توجد شركات مسجلة بعد
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- الترقيم -->
        @if($companies->hasPages())
        <div class="bg-white dark:bg-gray-800 px-4 py-3 border-t border-gray-200 dark:border-gray-700 sm:px-6">
            {{ $companies->appends(request()->query())->links() }}
        </div>
        @endif
    </div>

    @push('scripts')
    <script>
        // إخفاء رسالة النجاح بعد 5 ثواني
        setTimeout(() => {
            const alert = document.getElementById('alert-message');
            if (alert) {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }
        }, 5000);

        // إغلاق الرسالة عند النقر على زر الإغلاق
        document.querySelectorAll('[title="Close"]').forEach(button => {
            button.addEventListener('click', function() {
                const alert = this.closest('[role="alert"]');
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        });
    </script>
    @endpush
@endsection