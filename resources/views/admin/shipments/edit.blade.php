@extends('admin.layouts.app')

@section('title', 'تعديل الشحنة')
@section('header', 'تعديل الشحنة')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
    <form action="{{ route('admin.shipments.update', $shipment) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- نوع الشحنة -->
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">نوع الشحنة *</label>
                <input type="text" name="type" id="type" value="{{ old('type', $shipment->type) }}" required
                       class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200">
            </div>

            <!-- تاريخ الشحن -->
            <div>
                <label for="shipping_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">تاريخ الشحن *</label>
                <input type="datetime-local" name="shipping_date" id="shipping_date" 
                       value="{{ old('shipping_date', $shipment->shipping_date->format('Y-m-d\TH:i')) }}" required
                       class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200">
            </div>

            <!-- الشاحنة -->
            <div>
                <label for="truck_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">الشاحنة *</label>
                <select name="truck_id" id="truck_id" required
                       class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200">
                    @foreach($trucks as $truck)
                        <option value="{{ $truck->id }}" {{ $shipment->truck_id == $truck->id ? 'selected' : '' }}>
                            {{ $truck->truck_name }} ({{ $truck->plate_number }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- الوجهة -->
            <div>
                <label for="destination_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">الوجهة *</label>
                <select name="destination_id" id="destination_id" required
                       class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200">
                    @foreach($destinations as $destination)
                        <option value="{{ $destination->id }}" {{ $shipment->destination_id == $destination->id ? 'selected' : '' }}>
                            {{ $destination->start_point }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('admin.shipments.index') }}"
               class="bg-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-400 transition duration-200 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500">
                إلغاء
            </a>
            <button type="submit"
                    class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 transition duration-200 dark:bg-indigo-700 dark:hover:bg-indigo-600">
                <i class="fas fa-save mr-2"></i> حفظ التعديلات
            </button>
        </div>
    </form>
</div>
@endsection