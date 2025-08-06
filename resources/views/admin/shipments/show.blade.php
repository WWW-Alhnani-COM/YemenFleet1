@extends('admin.layouts.app')

@section('title', 'تفاصيل الشحنة')
@section('header', 'تفاصيل الشحنة #' . $shipment->id)

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- المعلومات الأساسية -->
            <div class="space-y-4">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200 mb-2">معلومات الشحنة</h3>
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">النوع:</p>
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-200">{{ $shipment->type }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">تاريخ الشحن:</p>
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-200">{{ $shipment->shipping_date->format('Y-m-d H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">الحالة:</p>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $shipment->status == 'delivered' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                                       ($shipment->status == 'in_transit' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 
                                       'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200') }}">
                                    @if($shipment->status == 'pending')
                                        قيد الانتظار
                                    @elseif($shipment->status == 'in_transit')
                                        في الطريق
                                    @else
                                        تم التسليم
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- معلومات الشاحنة -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200 mb-2">معلومات الشاحنة</h3>
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <div class="flex items-center space-x-4 space-x-reverse">
                            <div class="flex-shrink-0 h-12 w-12 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                                <i class="fas fa-truck text-indigo-600 dark:text-indigo-300"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-200">{{ $shipment->truck->truck_name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">رقم اللوحة: {{ $shipment->truck->plate_number }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">الشركة: {{ $shipment->truck->company->company_name }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- معلومات الوجهة -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200 mb-2">معلومات الوجهة</h3>
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg h-full">
                    <div class="flex items-center space-x-4 space-x-reverse">
                        <div class="flex-shrink-0 h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                            <i class="fas fa-map-marker-alt text-blue-600 dark:text-blue-300"></i>
                        </div>
                        <div>
                             @foreach($destinations as $destination)
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-200">{{$destination->start_point }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">العنوان: {{ $destination->end_address }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">المدينة: {{ $destination->end_point }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- تحديث الحالة -->
        <div class="mt-6 bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200 mb-3">تحديث حالة الشحنة</h3>
            <form action="{{ route('admin.shipments.update-status', $shipment) }}" method="POST" class="flex items-end gap-3">
                @csrf
                <div class="flex-1">
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">الحالة الجديدة</label>
                    <select name="status" id="status" required
                           class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200">
                        <option value="pending" {{ $shipment->status == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                        <option value="in_transit" {{ $shipment->status == 'in_transit' ? 'selected' : '' }}>في الطريق</option>
                        <option value="delivered" {{ $shipment->status == 'delivered' ? 'selected' : '' }}>تم التسليم</option>
                    </select>
                </div>
                <button type="submit"
                        class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 transition duration-200 dark:bg-indigo-700 dark:hover:bg-indigo-600 h-[42px]">
                    <i class="fas fa-sync-alt mr-1"></i> تحديث
                </button>
            </form>
        </div>
    </div>

    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 border-t border-gray-200 dark:border-gray-600 flex justify-end">
        <a href="{{ route('admin.shipments.index') }}"
           class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition duration-200 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500">
            العودة للقائمة
        </a>
    </div>
</div>
@endsection