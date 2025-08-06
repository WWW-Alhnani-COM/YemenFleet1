<aside class="w-72 bg-gradient-to-b from-white to-gray-50 shadow-xl rounded-r-lg overflow-hidden">
    <!-- Header with subtle gradient -->
    <div class="p-6 bg-gradient-to-r from-blue-600 to-blue-500">
        <h1 class="text-2xl font-bold text-white">لوحة التحكم</h1>
        <p class="text-blue-100 text-sm mt-1">إدارة الاسطول</p>
    </div>
    
    <!-- Navigation Menu -->
    <nav class="p-4">
        <ul class="space-y-1">
            <!-- Active Menu Item -->
            <li>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center p-3 text-blue-600 rounded-lg bg-blue-50 hover:bg-blue-100 transition-all duration-300 group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                    </svg>
                    <span class="mr-3 font-medium">الرئيسية</span>
                    <span class="ml-auto bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-0.5 rounded-full">جديد</span>
                </a>
            </li>
            
            <!-- Regular Menu Item -->
            <li>
                <a href="#" class="flex items-center p-3 text-gray-600 rounded-lg hover:bg-gray-100 transition-all duration-300 group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 group-hover:text-gray-600" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                    </svg>
                    <span class="mr-3">المستخدمين</span>
                    <span class="ml-auto inline-block w-2 h-2 bg-green-400 rounded-full"></span>
                </a>
            </li>
            
            <!-- Menu Item with Dropdown -->
            <li x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center justify-between w-full p-3 text-gray-600 rounded-lg hover:bg-gray-100 transition-all duration-300">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12z" clip-rule="evenodd" />
                        </svg>
                        <span class="mr-3">الإعدادات</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 transition-transform duration-200" :class="{ 'transform rotate-180': open }" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
                
                <!-- Dropdown Menu -->
                <ul x-show="open" x-transition class="mt-1 space-y-1 pl-8">
                    <li>
                        <a href="#" class="flex items-center p-2 text-sm text-gray-600 rounded hover:bg-gray-100">
                            <span class="mr-2">الإعدادات العامة</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center p-2 text-sm text-gray-600 rounded hover:bg-gray-100">
                            <span class="mr-2">الأمان</span>
                        </a>
                    </li>
                </ul>
            </li>
            
            <!-- Menu Item with Badge -->
            <li>
                <a href="#" class="flex items-center p-3 text-gray-600 rounded-lg hover:bg-gray-100 transition-all duration-300 group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 group-hover:text-gray-600" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
                    </svg>
                    <span class="mr-3">الإشعارات</span>
                    <span class="ml-auto bg-red-500 text-white text-xs font-semibold px-2 py-0.5 rounded-full">5</span>
                </a>
            </li>
        </ul>
        
        <!-- User Profile Section -->
        <div class="mt-8 pt-4 border-t border-gray-200">
            <div class="flex items-center p-2 rounded-lg hover:bg-gray-100 cursor-pointer transition-all duration-300">
                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                    <span class="text-blue-600 font-semibold">أد</span>
                </div>
                <div class="mr-3">
                    <p class="text-sm font-medium text-gray-800">المدير العام</p>
                    <p class="text-xs text-gray-500">admin@example.com</p>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 ml-auto" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                </svg>
            </div>
        </div>
    </nav>
</aside>