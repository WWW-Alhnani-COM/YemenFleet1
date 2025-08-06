<!DOCTYPE html>
<html dir="rtl" lang="ar" x-data="{ darkMode: false, sidebarOpen: true }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - نظام إدارة الأسطول</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            light: '#3B82F6',
                            dark: '#1E40AF'
                        },
                        secondary: {
                            light: '#10B981',
                            dark: '#047857'
                        },
                        fleet: {
                            light: '#6366F1',
                            dark: '#4F46E5'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        /* إضافة هذا الكود في قسم الـ head */
    .sidebar-container {
        display: flex;
        flex-direction: column;
        height: 100vh;
    }
    .sidebar-nav {
        flex: 1;
        overflow-y: auto;
    }
    .sidebar-footer {
        flex-shrink: 0;
    }
        .sidebar-collapsed {
            width: 5rem;
        }
        .sidebar-collapsed .sidebar-item-text {
            opacity: 0;
            transition: opacity 0.3s;
        }
        .sidebar-collapsed .sidebar-header {
            justify-content: center;
            padding: 1rem 0;
        }
        .sidebar-collapsed .sidebar-header h1 {
            display: none;
        }
        .sidebar-collapsed .sidebar-header p {
            display: none;
        }
        .sidebar-collapsed .user-info {
            display: none;
        }
        .sidebar-collapsed .nav-item {
            justify-content: center;
        }
        .sidebar-collapsed .nav-item svg {
            margin-left: 0;
        }
        .sidebar-collapsed .toggle-btn {
            left: 50%;
            transform: translateX(-50%);
        }
        .fleet-badge {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(99, 102, 241, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(99, 102, 241, 0); }
            100% { box-shadow: 0 0 0 0 rgba(99, 102, 241, 0); }
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
    <div class="flex h-screen">
        <!-- القائمة الجانبية -->
        <aside class="bg-gradient-to-b from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 shadow-xl overflow-hidden transition-all duration-300 flex flex-col"
      :class="sidebarOpen ? 'w-72' : 'sidebar-collapsed'">
    
    <!-- Header -->
    <div class="p-4 bg-gradient-to-r from-fleet-light to-fleet-dark dark:from-fleet-dark dark:to-gray-800 relative">
        <div class="flex items-center justify-between sidebar-header">
            <div class="flex items-center">
                <div class="fleet-badge w-10 h-10 rounded-full bg-white dark:bg-gray-800 flex items-center justify-center mr-3">
                    <i class="fas fa-truck text-fleet-light dark:text-fleet-light text-xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-white">إدارة الأسطول</h1>
                    <p class="text-blue-100 text-xs mt-1">الإصدار 3.2.1</p>
                </div>
            </div>
        </div>
        <button 
            @click="sidebarOpen = !sidebarOpen"
            class="toggle-btn absolute -right-3 top-6 bg-white dark:bg-gray-700 rounded-full p-1 shadow-md border border-gray-200 dark:border-gray-600"
        >
            <i class="fas fa-chevron-right text-gray-600 dark:text-gray-300 text-sm" :class="{'transform rotate-180': !sidebarOpen}"></i>
        </button>
    </div>
    
    <!-- Navigation Menu -->
    <nav class="p-4 sidebar-nav overflow-y-auto">
        <ul class="space-y-2">
            <!-- لوحة التحكم -->
            <li>
                <a href="{{ route('dashboard') }}" class="flex items-center p-3 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-300 group nav-item">
                    <div class="relative">
                        <i class="fas fa-tachometer-alt text-gray-500 dark:text-gray-400 group-hover:text-fleet-light text-lg w-5"></i>
                        <span class="absolute -top-1 -right-1 w-2 h-2 rounded-full bg-green-400"></span>
                    </div>
                    <span class="mr-3 sidebar-item-text">لوحة التحكم</span>
                </a>
            </li>

            <li>
                <a href="{{ route('dashboard') }}" class="flex items-center p-3 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-300 group nav-item">
                    <div class="relative">
                        <i class="fas fa-tachometer-alt text-gray-500 dark:text-gray-400 group-hover:text-fleet-light text-lg w-5"></i>
                        <span class="absolute -top-1 -right-1 w-2 h-2 rounded-full bg-green-400"></span>
                    </div>
                    <span class="mr-3 sidebar-item-text">الشركات</span>
                </a>
            </li>
            
            <!-- إدارة الأسطول -->
            <li x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center justify-between w-full p-3 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-300 group nav-item">
                    <div class="flex items-center">
                        <i class="fas fa-truck-moving text-gray-500 dark:text-gray-400 group-hover:text-fleet-light text-lg w-5"></i>
                        <span class="mr-3 sidebar-item-text">إدارة الأسطول</span>
                    </div>
                    <i class="fas fa-chevron-down text-xs text-gray-400 transition-transform duration-200" :class="{ 'transform rotate-180': open }"></i>
                </button>
                
                <ul x-show="open" x-transition class="mt-1 space-y-1 pl-8">
                    <li>
                        <a href="#" class="flex items-center p-2 text-sm text-gray-600 dark:text-gray-400 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
                            <i class="fas fa-car text-gray-400 mr-2 text-xs"></i>
                            <span class="sidebar-item-text">المركبات</span>
                            <span class="mr-auto bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs px-2 py-0.5 rounded-full">25</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center p-2 text-sm text-gray-600 dark:text-gray-400 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
                            <i class="fas fa-route text-gray-400 mr-2 text-xs"></i>
                            <span class="sidebar-item-text">المسارات</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center p-2 text-sm text-gray-600 dark:text-gray-400 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
                            <i class="fas fa-gas-pump text-gray-400 mr-2 text-xs"></i>
                            <span class="sidebar-item-text">إستهلاك الوقود</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center p-2 text-sm text-gray-600 dark:text-gray-400 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
                            <i class="fas fa-clipboard-list text-gray-400 mr-2 text-xs"></i>
                            <span class="sidebar-item-text">سجلات الرحلات</span>
                        </a>
                    </li>
                </ul>
            </li>
            
            <!-- الصيانة -->
            <li x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center justify-between w-full p-3 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-300 group nav-item">
                    <div class="flex items-center">
                        <div class="relative">
                            <i class="fas fa-tools text-gray-500 dark:text-gray-400 group-hover:text-fleet-light text-lg w-5"></i>
                            <span class="absolute -top-1 -right-1 w-2 h-2 rounded-full bg-red-500"></span>
                        </div>
                        <span class="mr-3 sidebar-item-text">الصيانة</span>
                    </div>
                    <i class="fas fa-chevron-down text-xs text-gray-400 transition-transform duration-200" :class="{ 'transform rotate-180': open }"></i>
                </button>
                
                <ul x-show="open" x-transition class="mt-1 space-y-1 pl-8">
                    <li>
                        <a href="#" class="flex items-center p-2 text-sm text-gray-600 dark:text-gray-400 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
                            <i class="fas fa-calendar-check text-gray-400 mr-2 text-xs"></i>
                            <span class="sidebar-item-text">جدول الصيانة</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center p-2 text-sm text-gray-600 dark:text-gray-400 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
                            <i class="fas fa-exclamation-triangle text-gray-400 mr-2 text-xs"></i>
                            <span class="sidebar-item-text">الإصلاحات العاجلة</span>
                            <span class="mr-auto bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 text-xs px-2 py-0.5 rounded-full">3</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center p-2 text-sm text-gray-600 dark:text-gray-400 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
                            <i class="fas fa-file-invoice-dollar text-gray-400 mr-2 text-xs"></i>
                            <span class="sidebar-item-text">تكاليف الصيانة</span>
                        </a>
                    </li>
                </ul>
            </li>
            
            <!-- التتبع المباشر -->
            <li>
                <a href="#" class="flex items-center p-3 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-300 group nav-item">
                    <i class="fas fa-map-marked-alt text-gray-500 dark:text-gray-400 group-hover:text-fleet-light text-lg w-5"></i>
                    <span class="mr-3 sidebar-item-text">التتبع المباشر</span>
                    <span class="ml-auto bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 text-xs px-2 py-0.5 rounded-full">12 نشط</span>
                </a>
            </li>
            
            <!-- التقارير -->
            <li x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center justify-between w-full p-3 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-300 group nav-item">
                    <div class="flex items-center">
                        <i class="fas fa-chart-bar text-gray-500 dark:text-gray-400 group-hover:text-fleet-light text-lg w-5"></i>
                        <span class="mr-3 sidebar-item-text">التقارير</span>
                    </div>
                    <i class="fas fa-chevron-down text-xs text-gray-400 transition-transform duration-200" :class="{ 'transform rotate-180': open }"></i>
                </button>
                
                <ul x-show="open" x-transition class="mt-1 space-y-1 pl-8">
                    <li>
                        <a href="#" class="flex items-center p-2 text-sm text-gray-600 dark:text-gray-400 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
                            <i class="fas fa-file-alt text-gray-400 mr-2 text-xs"></i>
                            <span class="sidebar-item-text">التقارير اليومية</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center p-2 text-sm text-gray-600 dark:text-gray-400 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
                            <i class="fas fa-chart-pie text-gray-400 mr-2 text-xs"></i>
                            <span class="sidebar-item-text">تقارير التحليل</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center p-2 text-sm text-gray-600 dark:text-gray-400 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
                            <i class="fas fa-file-excel text-gray-400 mr-2 text-xs"></i>
                            <span class="sidebar-item-text">تصدير البيانات</span>
                        </a>
                    </li>
                </ul>
            </li>
            
            <!-- الإدارة -->
            <li x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center justify-between w-full p-3 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-300 group nav-item">
                    <div class="flex items-center">
                        <i class="fas fa-cogs text-gray-500 dark:text-gray-400 group-hover:text-fleet-light text-lg w-5"></i>
                        <span class="mr-3 sidebar-item-text">الإدارة</span>
                    </div>
                    <i class="fas fa-chevron-down text-xs text-gray-400 transition-transform duration-200" :class="{ 'transform rotate-180': open }"></i>
                </button>
                
                <ul x-show="open" x-transition class="mt-1 space-y-1 pl-8">
                    <li>
                        <a href="#" class="flex items-center p-2 text-sm text-gray-600 dark:text-gray-400 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
                            <i class="fas fa-users text-gray-400 mr-2 text-xs"></i>
                            <span class="sidebar-item-text">المستخدمون</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center p-2 text-sm text-gray-600 dark:text-gray-400 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
                            <i class="fas fa-user-shield text-gray-400 mr-2 text-xs"></i>
                            <span class="sidebar-item-text">الصلاحيات</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center p-2 text-sm text-gray-600 dark:text-gray-400 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
                            <i class="fas fa-building text-gray-400 mr-2 text-xs"></i>
                            <span class="sidebar-item-text">الفروع</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
    
    <!-- User Profile Section -->
    <div class="p-4 border-t border-gray-200 dark:border-gray-700 sidebar-footer">
        <div class="flex items-center p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer transition-all duration-300">
            <div class="relative">
                <div class="h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                    <span class="text-blue-600 dark:text-blue-300 font-semibold">أد</span>
                </div>
                <span class="absolute bottom-0 right-0 block h-2 w-2 rounded-full ring-2 ring-white dark:ring-gray-800 bg-green-400"></span>
            </div>
            <div class="mr-3 user-info" x-show="sidebarOpen">
                <p class="text-sm font-medium text-gray-800 dark:text-gray-200">مدير النظام</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">fleet@example.com</p>
            </div>
            <button @click="darkMode = !darkMode" class="ml-auto text-gray-500 dark:text-gray-400 hover:text-fleet-light transition-colors">
                <i class="fas" :class="darkMode ? 'fa-sun' : 'fa-moon'"></i>
            </button>
        </div>
    </div>
</aside>
        
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- الهيدر -->
            <header class="bg-white dark:bg-gray-800 shadow-sm">
                <div class="flex justify-between items-center p-4">
                    <div class="flex items-center">
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">@yield('title')</h2>
                        <span class="ml-3 px-2 py-1 bg-fleet-light dark:bg-fleet-dark text-white text-xs rounded-full">v3.2.1</span>
                    </div>
                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <div class="relative">
                            <button class="p-1 rounded-full text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none">
                                <i class="fas fa-bell text-lg"></i>
                                <span class="absolute top-0 right-0 h-2 w-2 rounded-full bg-red-500"></span>
                            </button>
                        </div>
                        
                        <!-- Quick Actions -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="p-1 rounded-full text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none">
                                <i class="fas fa-bolt text-lg"></i>
                            </button>
                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-md shadow-lg z-50 border border-gray-200 dark:border-gray-700">
                                <div class="p-2">
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md">
                                        <i class="fas fa-plus-circle mr-2 text-green-500"></i> إضافة مركبة
                                    </a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md">
                                        <i class="fas fa-exclamation-triangle mr-2 text-yellow-500"></i> بلاغ عاجل
                                    </a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md">
                                        <i class="fas fa-file-export mr-2 text-blue-500"></i> تصدير تقرير
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- User Menu -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center focus:outline-none">
                                <div class="h-8 w-8 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                    <span class="text-blue-600 dark:text-blue-300 text-sm font-semibold">أد</span>
                                </div>
                                <i class="fas fa-chevron-down text-xs text-gray-500 dark:text-gray-400 mr-2"></i>
                            </button>
                            <div x-show="open" @click.away="open = false" class="absolute left-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg z-50 border border-gray-200 dark:border-gray-700">
                                <div class="py-1">
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <i class="fas fa-user-cog mr-2"></i> الملف الشخصي
                                    </a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <i class="fas fa-cog mr-2"></i> الإعدادات
                                    </a>
                                    <div class="border-t border-gray-200 dark:border-gray-700"></div>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <i class="fas fa-sign-out-alt mr-2"></i> تسجيل الخروج
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- المحتوى الرئيسي -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 dark:bg-gray-900 p-4 transition-colors duration-300">
                @yield('content')
            </main>
            
            <!-- Footer -->
            <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 py-3 px-6">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        نظام إدارة الأسطول &copy; 2025 - جميع الحقوق محفوظة
                    </p>
                    <div class="flex space-x-4 mt-2 md:mt-0">
                        <a href="#" class="text-sm text-gray-600 dark:text-gray-400 hover:text-fleet-light">الشروط</a>
                        <a href="#" class="text-sm text-gray-600 dark:text-gray-400 hover:text-fleet-light">الخصوصية</a>
                        <a href="#" class="text-sm text-gray-600 dark:text-gray-400 hover:text-fleet-light">الدعم</a>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    @yield('scripts')
</body>
</html>