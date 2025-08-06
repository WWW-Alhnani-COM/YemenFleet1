@extends('admin.layouts.app')

@section('title', 'لوحة التحكم')

@section('content')
<div class="space-y-6 dark:space-y-8">
    <!-- بطاقات الإحصائيات -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        <!-- بطاقة المستخدمين -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden border border-gray-100 dark:border-gray-700 hover:shadow-md transition-all duration-300 group">
            <div class="p-4 md:p-5 flex items-start justify-between">
                <div>
                    <p class="text-xs md:text-sm font-medium text-gray-500 dark:text-gray-400">إجمالي المستخدمين</p>
                    <h3 class="text-xl md:text-2xl font-bold mt-1 text-gray-800 dark:text-white">{{number_format($customerCount)}}</h3>
                    <p class="text-xs mt-2">
                        <span class="text-green-500 dark:text-green-400 font-medium">↑ 12.5%</span>
                        <span class="text-gray-500 dark:text-gray-400"> عن الشهر الماضي</span>
                    </p>
                </div>
                <div class="bg-blue-50 dark:bg-blue-900/30 p-2 md:p-3 rounded-lg group-hover:scale-110 transition-transform duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 md:h-8 md:w-8 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a3 3 0 11-6 0 3 3 0 016 0zM7 10a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
            </div>
            <div class="h-1 md:h-2 bg-gradient-to-r from-blue-500 to-blue-300 dark:from-blue-600 dark:to-blue-400"></div>
        </div>

        <!-- بطاقة الشركات -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden border border-gray-100 dark:border-gray-700 hover:shadow-md transition-all duration-300 group">
            <div class="p-4 md:p-5 flex items-start justify-between">
                <div>
                    <p class="text-xs md:text-sm font-medium text-gray-500 dark:text-gray-400">إجمالي الشركات</p>
                    <h3 class="text-xl md:text-2xl font-bold mt-1 text-gray-800 dark:text-white">{{number_format($compnyCount)}}</h3>
                    <p class="text-xs mt-2">
                        <span class="text-green-500 dark:text-green-400 font-medium">↑ 8.3%</span>
                        <span class="text-gray-500 dark:text-gray-400"> عن الشهر الماضي</span>
                    </p>
                </div>
                <div class="bg-green-50 dark:bg-green-900/30 p-2 md:p-3 rounded-lg group-hover:scale-110 transition-transform duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 md:h-8 md:w-8 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            </div>
            <div class="h-1 md:h-2 bg-gradient-to-r from-green-500 to-green-300 dark:from-green-600 dark:to-green-400"></div>
        </div>

        <!-- بطاقة الاشتراكات -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden border border-gray-100 dark:border-gray-700 hover:shadow-md transition-all duration-300 group">
            <div class="p-4 md:p-5 flex items-start justify-between">
                <div>
                    <p class="text-xs md:text-sm font-medium text-gray-500 dark:text-gray-400">الاشتراكات النشطة</p>
                    <h3 class="text-xl md:text-2xl font-bold mt-1 text-gray-800 dark:text-white">{{number_format($activeCount)}}</h3>
                    <p class="text-xs mt-2">
                        <span class="text-green-500 dark:text-green-400 font-medium">↑ 5.7%</span>
                        <span class="text-gray-500 dark:text-gray-400"> عن الشهر الماضي</span>
                    </p>
                </div>
                <div class="bg-purple-50 dark:bg-purple-900/30 p-2 md:p-3 rounded-lg group-hover:scale-110 transition-transform duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 md:h-8 md:w-8 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </div>
            </div>
            <div class="h-1 md:h-2 bg-gradient-to-r from-purple-500 to-purple-300 dark:from-purple-600 dark:to-purple-400"></div>
        </div>

        <!-- بطاقة الإيرادات -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden border border-gray-100 dark:border-gray-700 hover:shadow-md transition-all duration-300 group">
    <div class="p-4 md:p-5 flex items-start justify-between">
        <div>
            <p class="text-xs md:text-sm font-medium text-gray-500 dark:text-gray-400">إجمالي الحساسات</p>
            <h3 class="text-xl md:text-2xl font-bold mt-1 text-gray-800 dark:text-white">{{ $totalSensors }}</h3>
            <p class="text-xs mt-2">
                <span class="text-green-500 dark:text-green-400 font-medium">↑ 5.7%</span>
                <span class="text-gray-500 dark:text-gray-400"> عن الشهر الماضي</span>
            </p>
        </div>
        <div class="bg-blue-50 dark:bg-blue-900/30 p-2 md:p-3 rounded-lg group-hover:scale-110 transition-transform duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 md:h-8 md:w-8 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <!-- أيقونة مستشعر (sensor) -->
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none"/>
            </svg>
        </div>
    </div>
    <div class="h-1 md:h-2 bg-gradient-to-r from-blue-500 to-blue-300 dark:from-blue-600 dark:to-blue-400"></div>
</div>

    </div>

    <!-- الرسوم البيانية -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6">
        <!-- مخطط المستخدمين الجدد -->
        <div class="bg-white dark:bg-gray-800 p-4 md:p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 transition-colors duration-300">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-3 md:mb-4 gap-2">
                <h3 class="font-semibold text-base md:text-lg text-gray-800 dark:text-white">المستخدمين الجدد</h3>
                <select id="usersChartPeriod" class="text-xs md:text-sm border-gray-200 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 px-2 py-1 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                    <option value="7">آخر 7 أيام</option>
                    <option value="30">آخر 30 يوم</option>
                </select>
            </div>
            <div class="h-48 md:h-64">
                <canvas id="usersChart" class="dark:invert-[90%]"></canvas>
            </div>
        </div>

        <!-- توزيع الاشتراكات -->
        <div class="bg-white dark:bg-gray-800 p-4 md:p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 transition-colors duration-300">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-3 md:mb-4 gap-2">
                <h3 class="font-semibold text-base md:text-lg text-gray-800 dark:text-white">توزيع الاشتراكات</h3>
                <select id="subscriptionsChartPeriod" class="text-xs md:text-sm border-gray-200 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 px-2 py-1 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                    <option value="current">هذا الشهر</option>
                    <option value="previous">الشهر الماضي</option>
                </select>
            </div>
            <div class="h-48 md:h-64">
                <canvas id="subscriptionsChart" class="dark:invert-[90%]"></canvas>
            </div>
        </div>
    </div>

    <!-- خريطة مواقع الشركات -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden transition-colors duration-300">
    <div class="p-4 md:p-6 border-b border-gray-200 dark:border-gray-700">
        <h3 class="font-semibold text-base md:text-lg text-gray-800 dark:text-white">مواقع الشركات</h3>
    </div>
    <div class="h-80 md:h-96 w-full" id="sanaaMap"></div>
</div>

    <!-- آخر النشاطات (إضافة جديدة) -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden transition-colors duration-300">
        <div class="p-4 md:p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="font-semibold text-base md:text-lg text-gray-800 dark:text-white">آخر النشاطات</h3>
        </div>
        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            <!-- نشاط 1 -->
            <div class="p-4 md:p-5 flex items-start hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                <div class="bg-blue-100 dark:bg-blue-900/50 p-2 rounded-lg mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-800 dark:text-gray-200">تم تسجيل مستخدم جديد</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">محمد أحمد - 10 دقائق مضت</p>
                </div>
                <span class="text-xs px-2 py-1 bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200 rounded-full">مستخدم</span>
            </div>
            
            <!-- نشاط 2 -->
            <div class="p-4 md:p-5 flex items-start hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                <div class="bg-green-100 dark:bg-green-900/50 p-2 rounded-lg mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-800 dark:text-gray-200">تم تفعيل اشتراك جديد</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">شركة التقنية - اشتراك ذهبي - 45 دقيقة مضت</p>
                </div>
                <span class="text-xs px-2 py-1 bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200 rounded-full">اشتراك</span>
            </div>
            
            <!-- نشاط 3 -->
            <div class="p-4 md:p-5 flex items-start hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                <div class="bg-purple-100 dark:bg-purple-900/50 p-2 rounded-lg mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-800 dark:text-gray-200">تم تحديث بيانات الشركة</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">شركة التجارة - ساعتين مضت</p>
                </div>
                <span class="text-xs px-2 py-1 bg-purple-100 dark:bg-purple-900/50 text-purple-800 dark:text-purple-200 rounded-full">تحديث</span>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />

<script>
document.addEventListener('DOMContentLoaded', function() {
    // بيانات وهمية للمخططات
    const mockData = {
        users: {
            '7': [12, 19, 15, 27, 22, 31, 25],
            '30': Array.from({length: 30}, (_, i) => Math.floor(Math.random() * 30) + 10)
        },
        subscriptions: {
            'current': [45, 30, 25],
            'previous': [35, 40, 25]
        },
        companies: [
            { name: "شركة التقنية", lat: 15.3543, lng: 44.2066 },
            { name: "شركة التجارة", lat: 15.3776, lng: 44.2197 },
            { name: "مؤسسة السعيدة", lat: 15.3921, lng: 44.1832 }
        ]
    };

    // دالة لإنشاء مخطط مع الوضع الداكن
    function createChart(elementId, type, data, options) {
        const ctx = document.getElementById(elementId).getContext('2d');
        return new Chart(ctx, {
            type: type,
            data: data,
            options: options
        });
    }

    // إعدادات المخططات للوضع الداكن
    const chartOptions = {
        line: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: 'rgba(255, 255, 255, 0.1)',
                    borderWidth: 1
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)',
                        borderColor: 'rgba(255, 255, 255, 0.1)'
                    },
                    ticks: {
                        color: '#9CA3AF'
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)',
                        borderColor: 'rgba(255, 255, 255, 0.1)'
                    },
                    ticks: {
                        color: '#9CA3AF'
                    }
                }
            }
        },
        doughnut: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: window.innerWidth < 768 ? 'top' : 'bottom',
                    rtl: true,
                    labels: {
                        color: '#9CA3AF',
                        boxWidth: 10,
                        font: {
                            size: window.innerWidth < 768 ? 10 : 12
                        },
                        padding: 15
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: 'rgba(255, 255, 255, 0.1)',
                    borderWidth: 1
                }
            },
            cutout: window.innerWidth < 768 ? '50%' : '65%'
        }
    };

    // إنشاء مخطط المستخدمين
    const usersChart = createChart('usersChart', 'line', {
        labels: generateLabels(7),
        datasets: [{
            label: 'المستخدمين الجدد',
            data: mockData.users['7'],
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            borderColor: 'rgba(59, 130, 246, 1)',
            borderWidth: 2,
            tension: 0.3,
            fill: true,
            pointBackgroundColor: 'rgba(59, 130, 246, 1)',
            pointBorderColor: '#fff',
            pointHoverRadius: 5
        }]
    }, {
        ...chartOptions.line,
        plugins: {
            ...chartOptions.line.plugins,
            title: {
                display: true,
                text: 'المستخدمين الجدد',
                color: '#1F2937',
                font: { size: window.innerWidth < 768 ? 14 : 16 }
            }
        }
    });

    // إنشاء مخطط الاشتراكات
    const subscriptionsChart = createChart('subscriptionsChart', 'doughnut', {
        labels: ['الاشتراك الأساسي', 'الاشتراك المميز', 'الاشتراك الذهبي'],
        datasets: [{
            data: mockData.subscriptions['current'],
            backgroundColor: [
                'rgba(59, 130, 246, 0.7)',
                'rgba(16, 185, 129, 0.7)',
                'rgba(245, 158, 11, 0.7)'
            ],
            borderColor: [
                'rgba(59, 130, 246, 1)',
                'rgba(16, 185, 129, 1)',
                'rgba(245, 158, 11, 1)'
            ],
            borderWidth: 1
        }]
    }, chartOptions.doughnut);

    // إنشاء الخريطة
     // إنشاء الخريطة
    const map = L.map('sanaaMap').setView([15.3694, 44.1910], 12);
    
    // طبقة الخريطة الأساسية (الوضع الفاتح)
    const lightLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 18
    });
    
    // طبقة الخريطة للوضع الداكن
    const darkLayer = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
        maxZoom: 18
    });
    
    // إضافة الطبقة المناسبة حسب الوضع الحالي
    if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
        darkLayer.addTo(map);
    } else {
        lightLayer.addTo(map);
    }
    
    // ألوان مختلفة لأنواع الشركات
    const typeColors = {
        "تقنية": "#3b82f6", // أزرق
        "تجارية": "#10b981", // أخضر
        "خدمية": "#f59e0b"  // برتقالي
    };
    
    // بيانات الشركات
    const companies = [
        { name: "الشركة اليمنية للتقنية", location: [15.3543, 44.2066], type: "تقنية" },
        { name: "شركة صنعاء التجارية", location: [15.3776, 44.2197], type: "تجارية" },
        { name: "مؤسسة السعيدة للخدمات", location: [15.3921, 44.1832], type: "خدمية" }
    ];
    
    // إضافة علامات للشركات مع تأثيرات
    companies.forEach(company => {
        const marker = L.marker(company.location, {
            icon: L.divIcon({
                html: `<div class="w-8 h-8 rounded-full flex items-center justify-center text-white shadow-lg" 
                      style="background-color: ${typeColors[company.type]}; border: 2px solid white">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                      </svg>
                      </div>`,
                className: 'leaflet-custom-icon',
                iconSize: [32, 32]
            })
        }).addTo(map);
        
        marker.bindPopup(`
            <div class="font-sans">
                <h4 class="font-bold text-gray-800">${company.name}</h4>
                <p class="text-sm text-gray-600">نوع النشاط: <span class="font-medium" style="color: ${typeColors[company.type]}">${company.type}</span></p>
                <div class="mt-2 flex justify-between items-center">
                    <a href="#" class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded hover:bg-blue-200 transition-colors">تفاصيل</a>
                    <span class="text-xs text-gray-500">${company.location.join(', ')}</span>
                </div>
            </div>
        `);
    });
    
    // إضافة عناصر التحكم في الطبقات
    const baseLayers = {
        "الوضع الفاتح": lightLayer,
        "الوضع الداكن": darkLayer
    };
    
    L.control.layers(baseLayers, null, {position: 'topright'}).addTo(map);
    
    // إضافة عنصر تحكم لتحديد الموقع
    L.control.locate({
        position: 'topright',
        strings: {
            title: "حدد موقعي الحالي"
        }
    }).addTo(map);
    
    // كشف تغيير الوضع الداكن وتحديث الخريطة
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', event => {
        if (event.matches) {
            map.removeLayer(lightLayer);
            map.addLayer(darkLayer);
        } else {
            map.removeLayer(darkLayer);
            map.addLayer(lightLayer);
        }
    });
    
    // ضبط الخريطة عند تغيير حجم الشاشة
    window.addEventListener('resize', function() {
        map.invalidateSize();
    });

    // كشف تغيير الوضع الداكن وتحديث المخططات
    const darkModeMediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
    
    function handleDarkModeChange(e) {
        const isDarkMode = e.matches;
        
        // تحديث ألوان المخططات
        usersChart.options.scales.x.ticks.color = isDarkMode ? '#9CA3AF' : '#6B7280';
        usersChart.options.scales.y.ticks.color = isDarkMode ? '#9CA3AF' : '#6B7280';
        usersChart.options.plugins.title.color = isDarkMode ? '#F3F4F6' : '#1F2937';
        usersChart.update();
        
        subscriptionsChart.options.plugins.legend.labels.color = isDarkMode ? '#9CA3AF' : '#6B7280';
        subscriptionsChart.update();
        
        // تبديل طبقة الخريطة
        if (isDarkMode) {
            map.removeLayer(baseLayers["الوضع الفاتح"]);
            map.addLayer(baseLayers["الوضع الداكن"]);
        } else {
            map.removeLayer(baseLayers["الوضع الداكن"]);
            map.addLayer(baseLayers["الوضع الفاتح"]);
        }
    }
    
    darkModeMediaQuery.addListener(handleDarkModeChange);
    
    // تحديث المخططات عند تغيير حجم الشاشة
    window.addEventListener('resize', function() {
        usersChart.resize();
        subscriptionsChart.resize();
        subscriptionsChart.options.plugins.legend.position = window.innerWidth < 768 ? 'top' : 'bottom';
        subscriptionsChart.options.cutout = window.innerWidth < 768 ? '50%' : '65%';
        subscriptionsChart.update();
        map.invalidateSize();
    });

    // تحديث المخططات عند تغيير الفترة
    document.getElementById('usersChartPeriod').addEventListener('change', function() {
        const days = this.value;
        usersChart.data.labels = generateLabels(days);
        usersChart.data.datasets[0].data = mockData.users[days];
        usersChart.update();
    });

    document.getElementById('subscriptionsChartPeriod').addEventListener('change', function() {
        const period = this.value;
        subscriptionsChart.data.datasets[0].data = mockData.subscriptions[period];
        subscriptionsChart.update();
    });

    // دوال مساعدة
    function generateLabels(days) {
        return Array.from({length: days}, (_, i) => `اليوم ${i+1}`);
    }
});

// تهيئة خريطة صنعاء مع تأثيرات خاصة
function initSanaaMap() {
    const sanaaCenter = [15.3694, 44.1910];
    const map = L.map('sanaaMap').setView(sanaaCenter, 12);
    
    // طبقة الخريطة مع نمط مخصص
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 18
    }).addTo(map);

    // إضافة حدود صنعاء (بيانات وهمية)
    const sanaaBounds = [
        [15.25, 44.10],
        [15.45, 44.25]
    ];
    
    L.rectangle(sanaaBounds, {
        color: "#3b82f6",
        weight: 2,
        fillOpacity: 0.1,
        dashArray: "5, 5"
    }).addTo(map).bindPopup("حدود محافظة صنعاء");

    // إضافة علامات مع تأثيرات حركية
    const companies = [
        { name: "الشركة اليمنية للتقنية", location: [15.3543, 44.2066], type: "تقنية" },
        { name: "شركة صنعاء التجارية", location: [15.3776, 44.2197], type: "تجارية" },
        { name: "مؤسسة السعيدة للخدمات", location: [15.3921, 44.1832], type: "خدمية" }
    ];

    companies.forEach(company => {
        const marker = L.marker(company.location, {
            icon: L.divIcon({
                html: `<div class="w-8 h-8 rounded-full flex items-center justify-center text-white shadow-lg transform transition-all duration-300 hover:scale-125" 
                      style="background-color: ${getColorByType(company.type)}; border: 2px solid white">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                      </svg>
                      </div>`,
                className: 'leaflet-custom-icon',
                iconSize: [32, 32]
            })
        }).addTo(map);
        
        marker.bindPopup(`
            <div class="font-sans">
                <h4 class="font-bold text-gray-800">${company.name}</h4>
                <p class="text-sm text-gray-600">نوع النشاط: <span class="font-medium" style="color: ${getColorByType(company.type)}">${company.type}</span></p>
                <div class="mt-2 flex justify-between items-center">
                    <a href="#" class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded hover:bg-blue-200 transition-colors">تفاصيل</a>
                    <span class="text-xs text-gray-500">${company.location.join(', ')}</span>
                </div>
            </div>
        `);
        
        // تأثير عند النقر
        marker.on('click', function() {
            this._icon.classList.add('animate-bounce');
            setTimeout(() => {
                this._icon.classList.remove('animate-bounce');
            }, 1000);
        });
    });

    // إضافة دائرة للمنطقة المركزية
    L.circle([15.3694, 44.1910], {
        color: '#f59e0b',
        fillColor: '#f59e0b',
        fillOpacity: 0.2,
        radius: 1500
    }).addTo(map).bindPopup("المنطقة المركزية لصنعاء");

    // دالة مساعدة للحصول على اللون حسب النوع
    function getColorByType(type) {
        const colors = {
            "تقنية": "#3b82f6",
            "تجارية": "#10b981",
            "خدمية": "#f59e0b"
        };
        return colors[type] || "#6b7280";
    }
}

// تهيئة الخريطة عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', initSanaaMap);
</script>

<!-- <style>
/* تخصيصات إضافية للوضع الداكن */
.dark .leaflet-control-layers,
.dark .leaflet-bar {
    background-color: #1f2937 !important;
    color: #f3f4f6 !important;
}

.dark .leaflet-control-layers label {
    color: #f3f4f6 !important;
}

.dark .leaflet-control-layers-selector:checked + span {
    color: #3b82f6 !important;
}

.dark .leaflet-popup-content {
    color: #1f2937 !important;
}

/* تأثيرات للبطاقات */
.group:hover .group-hover\:scale-110 {
    transform: scale(1.1);
}

/* تأثيرات للخريطة */
.leaflet-custom-icon {
    transition: all 0.3s ease;
}

.leaflet-custom-icon:hover {
    transform: scale(1.2);
    z-index: 1000 !important;
}
</style> -->