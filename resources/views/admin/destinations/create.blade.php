@extends('admin.layouts.app')

@section('title', 'إضافة وجهة جديدة')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6 text-gray-900 dark:text-white">إضافة وجهة جديدة</h1>

    {{-- عرض الأخطاء --}}
    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-100 text-red-700 rounded dark:bg-red-900 dark:text-red-400">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.destinations.store') }}" method="POST" class="bg-gray-100 dark:bg-gray-800 p-6 rounded shadow max-w-3xl mx-auto" >
        @csrf

        {{-- اختيار المهمة --}}
        <div class="mb-4">
            <label for="task_id" class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">اختر المهمة</label>
            <select name="task_id" id="task_id" required
                class="w-full rounded border border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">-- اختر المهمة --</option>
                @foreach($tasks as $task)
                    <option 
                        value="{{ $task->id }}" 
                        data-driver-id="{{ $task->driver_id }}"
                        @selected(old('task_id') == $task->id)>
                        مهمة #{{ $task->id }} - {{ $task->driver->driver_name ?? 'غير معروف' }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- اختيار طلب الشركة المرتبط بالمهمة (عن طريق السائق) --}}
        <div class="mb-4">
            <label for="company_order_id" class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">طلب الشركة</label>
            <select name="company_order_id" id="company_order_id" required
                class="w-full rounded border border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" disabled>
                <option value="">اختر المهمة أولاً</option>
            </select>
        </div>

        {{-- نقطة البداية --}}
        <div class="mb-4">
            <label for="start_point" class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">نقطة البداية</label>
            <input type="text" name="start_point" id="start_point" value="{{ old('start_point') }}" required
                class="w-full rounded border border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="عنوان نقطة البداية">
        </div>

        {{-- خطوط الطول والعرض للبداية --}}
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label for="start_latitude" class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">خط العرض (البداية)</label>
                <input type="number" name="start_latitude" id="start_latitude" value="{{ old('start_latitude') }}" step="0.000001" required
                    class="w-full rounded border border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="مثال: 24.7136">
            </div>
            <div>
                <label for="start_longitude" class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">خط الطول (البداية)</label>
                <input type="number" name="start_longitude" id="start_longitude" value="{{ old('start_longitude') }}" step="0.000001" required
                    class="w-full rounded border border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="مثال: 46.6753">
            </div>
        </div>

        {{-- نقطة النهاية --}}
        <div class="mb-4">
            <label for="end_point" class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">نقطة النهاية</label>
            <input type="text" name="end_point" id="end_point" value="{{ old('end_point') }}" required
                class="w-full rounded border border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="عنوان نقطة النهاية">
        </div>

        {{-- عنوان النهاية التفصيلي --}}
        <div class="mb-4">
            <label for="end_address" class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">عنوان النهاية</label>
            <textarea name="end_address" id="end_address" rows="3" required
                class="w-full rounded border border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="العنوان التفصيلي لنقطة النهاية">{{ old('end_address') }}</textarea>
        </div>

        {{-- تاريخ ووقت الوجهة --}}
        <div class="mb-6">
            <label for="date" class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">التاريخ والوقت</label>
            <input type="datetime-local" name="date" id="date" value="{{ old('date') }}" required
                class="w-full rounded border border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        {{-- زر الإرسال --}}
        <div class="flex justify-end">
            <button type="submit"
                class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded shadow transition">
                إضافة الوجهة
            </button>
        </div>
    </form>
</div>

{{-- جافاسكريبت لجلب طلبات الشركة بناء على السائق المختار في المهمة --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const taskSelect = document.getElementById('task_id');
        const companyOrderSelect = document.getElementById('company_order_id');

        taskSelect.addEventListener('change', function() {
            const selectedOption = taskSelect.options[taskSelect.selectedIndex];
            const driverId = selectedOption.getAttribute('data-driver-id');

            companyOrderSelect.innerHTML = '<option>جارٍ التحميل...</option>';
            companyOrderSelect.disabled = true;

            if (!driverId) {
                companyOrderSelect.innerHTML = '<option value="">لا يوجد سائق مرتبط بالمهمة</option>';
                companyOrderSelect.disabled = true;
                return;
            }

            fetch(`/admin/api/company-orders-by-driver/${driverId}`)
                .then(response => {
                    if (!response.ok) throw new Error('خطأ في استجابة الشبكة');
                    return response.json();
                })
                .then(data => {
                    companyOrderSelect.innerHTML = '';
                    if (data.length > 0) {
                        companyOrderSelect.disabled = false;
                        companyOrderSelect.insertAdjacentHTML('beforeend', '<option value="">اختر طلب الشركة</option>');
                        data.forEach(order => {
                            companyOrderSelect.insertAdjacentHTML('beforeend',
                                `<option value="${order.id}">#${order.id} - ${order.company_name}</option>`
                            );
                        });
                    } else {
                        companyOrderSelect.innerHTML = '<option value="">لا توجد طلبات مرتبطة بهذا السائق</option>';
                        companyOrderSelect.disabled = true;
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    companyOrderSelect.innerHTML = '<option value="">خطأ في تحميل الطلبات</option>';
                    companyOrderSelect.disabled = true;
                });
        });

        // إذا كان هناك اختيار مسبق (عند إعادة تحميل الصفحة بسبب خطأ)، نفذ نفس الحدث تلقائياً
        if (taskSelect.value) {
            taskSelect.dispatchEvent(new Event('change'));
        }
    });
</script>
@endsection
