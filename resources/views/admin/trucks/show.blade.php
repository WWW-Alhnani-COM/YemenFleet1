@extends('admin.layouts.app')

@section('title', 'عرض بيانات الشاحنة')
@section('header', 'عرض بيانات الشاحنة')

@section('content')
    @php
        // تحديد الإحداثيات الافتراضية (صنعاء)
        $defaultLat = 15.3694;
        $defaultLng = 44.1910;
        
        // التحقق من وجود إحداثيات صحيحة
        $latitude = is_numeric($truck->latitude) ? $truck->latitude : $defaultLat;
        $longitude = is_numeric($truck->longitude) ? $truck->longitude : $defaultLng;
        $isDefaultLocation = !$truck->latitude || !$truck->longitude;
    @endphp

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- معلومات الشاحنة -->
                <div class="space-y-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-12 w-12 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                            <i class="fas fa-truck text-indigo-600 dark:text-indigo-300 text-xl"></i>
                        </div>
                        <div class="mr-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200">{{ $truck->truck_name }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">رقم اللوحة: {{ $truck->plate_number }}</p>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">رقم الشاصي</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-200">{{ $truck->chassis_number }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">الشركة</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-200">{{ $truck->company->company_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">حالة الشاحنة</dt>
                                <dd class="mt-1">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $truck->vehicle_status == 'نشطة' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                                           ($truck->vehicle_status == 'متوقفة' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 
                                           'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200') }}">
                                        {{ $truck->vehicle_status ?? 'غير محدد' }}
                                    </span>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- الموقع -->
                <div class="space-y-4">
                    <h4 class="text-md font-medium text-gray-900 dark:text-gray-200">الموقع</h4>
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">خط الطول</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-200">{{ $truck->longitude ?? 'غير محدد' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">دائرة العرض</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-200">{{ $truck->latitude ?? 'غير محدد' }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="mt-4 h-48 bg-gray-100 dark:bg-gray-700 rounded-md overflow-hidden">
                        <div id="truckMap" class="w-full h-full"></div>
                        @if($isDefaultLocation)
                        <div class="bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 p-2 text-sm">
                            <i class="fas fa-info-circle mr-1"></i> يتم عرض موقع صنعاء الافتراضي لعدم توفر إحداثيات الشاحنة
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6">
                <div class="flex justify-end">
                    <a href="{{ route('admin.trucks.edit', $truck->id) }}"
                       class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-200 dark:bg-indigo-700 dark:hover:bg-indigo-600">
                        تعديل البيانات
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY&callback=initMap" async defer></script>
<script>
    function initMap() {
        // إحداثيات الخريطة
        // const truckLocation = { 
        //     lat: 123456 , 
        //     lng: 78910, 
        // };
        
        // إنشاء الخريطة
        const map = new google.maps.Map(document.getElementById("truckMap"), {
            zoom: 12,
            center: truckLocation,
            mapTypeId: "roadmap",
            styles: [
                {
                    "featureType": "all",
                    "elementType": "labels.text.fill",
                    "stylers": [
                        {
                            "color": "#ffffff"
                        }
                    ]
                },
                {
                    "featureType": "all",
                    "elementType": "labels.text.stroke",
                    "stylers": [
                        {
                            "color": "#000000"
                        },
                        {
                            "lightness": 13
                        }
                    ]
                },
                {
                    "featureType": "administrative",
                    "elementType": "geometry.fill",
                    "stylers": [
                        {
                            "color": "#000000"
                        }
                    ]
                },
                {
                    "featureType": "administrative",
                    "elementType": "geometry.stroke",
                    "stylers": [
                        {
                            "color": "#144b53"
                        },
                        {
                            "lightness": 14
                        },
                        {
                            "weight": 1.4
                        }
                    ]
                },
                {
                    "featureType": "landscape",
                    "elementType": "all",
                    "stylers": [
                        {
                            "color": "#08304b"
                        }
                    ]
                },
                {
                    "featureType": "poi",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#0c4152"
                        },
                        {
                            "lightness": 5
                        }
                    ]
                },
                {
                    "featureType": "road.highway",
                    "elementType": "geometry.fill",
                    "stylers": [
                        {
                            "color": "#000000"
                        }
                    ]
                },
                {
                    "featureType": "road.highway",
                    "elementType": "geometry.stroke",
                    "stylers": [
                        {
                            "color": "#0b434f"
                        },
                        {
                            "lightness": 25
                        }
                    ]
                },
                {
                    "featureType": "road.arterial",
                    "elementType": "geometry.fill",
                    "stylers": [
                        {
                            "color": "#000000"
                        }
                    ]
                },
                {
                    "featureType": "road.arterial",
                    "elementType": "geometry.stroke",
                    "stylers": [
                        {
                            "color": "#0b3d51"
                        },
                        {
                            "lightness": 16
                        }
                    ]
                },
                {
                    "featureType": "road.local",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#000000"
                        }
                    ]
                },
                {
                    "featureType": "transit",
                    "elementType": "all",
                    "stylers": [
                        {
                            "color": "#146474"
                        }
                    ]
                },
                {
                    "featureType": "water",
                    "elementType": "all",
                    "stylers": [
                        {
                            "color": "#021019"
                        }
                    ]
                }
            ]
        });
        
        // إضافة علامة للموقع
        new google.maps.Marker({
            position: truckLocation,
            map: map,
            title: "{{ $isDefaultLocation ? 'موقع افتراضي (صنعاء)' : 'موقع الشاحنة' }}",
            icon: {
                url: "{{ $isDefaultLocation ? 'https://maps.google.com/mapfiles/ms/icons/red-dot.png' : 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png' }}"
            }
        });
    }
</script>
@endpush