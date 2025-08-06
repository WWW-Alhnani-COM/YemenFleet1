@extends('admin.layouts.app')

@section('title', 'إضافة شحنة جديدة')
@section('header', 'إضافة شحنة جديدة')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
    <form action="{{ route('admin.shipments.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- نوع الشحنة -->
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">نوع الشحنة *</label>
                <input type="text" name="type" id="type" required
                       class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200"
                       placeholder="مثال: أجهزة إلكترونية">
            </div>

            <!-- تاريخ الشحن -->
            <div>
                <label for="shipping_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">تاريخ الشحن *</label>
                <input type="datetime-local" name="shipping_date" id="shipping_date" required
                       class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200">
            </div>

            <!-- الشاحنة -->
            <div>
                <label for="truck_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">الشاحنة *</label>
                <select name="truck_id" id="truck_id" required
                       class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200">
                    <option value="">اختر الشاحنة</option>
                    @foreach($trucks as $truck)
                        <option value="{{ $truck->id }}">{{ $truck->truck_name }} ({{ $truck->plate_number }})</option>
                    @endforeach
                </select>
            </div>

            <!-- الوجهة -->
            <div>
                <label for="destination_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">الوجهة *</label>
                <select name="destination_id" id="destination_id" required
                       class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200">
                    <option value="">اختر الوجهة</option>
                    @foreach($destinations as $destination)
                        <option value="{{ $destination->id }}">{{ $destination->start_point }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <button type="submit"
                    class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 transition duration-200 dark:bg-indigo-700 dark:hover:bg-indigo-600">
                <i class="fas fa-save mr-2"></i> حفظ الشحنة
            </button>
        </div>
    </form>
</div>
@endsection