<header class="fixed top-0 left-0 right-0 bg-white shadow-md border-b border-gray-200 z-50">
    <div class="flex items-center justify-between px-6 py-3">
        
        <!-- Left Section: Logo & Toggle Button -->
        <div class="flex items-center gap-4">
            <button id="sidebarToggle" class="text-gray-600 hover:text-blue-600 focus:outline-none transition-colors">
                <i class="fas fa-bars text-xl"></i>
            </button>
            
            <a href="{{ url('/dashboard') }}" class="flex items-center gap-2 group">
                <div class="w-9 h-9 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center shadow-md group-hover:shadow-lg transition">
                    <i class="fas fa-code text-white text-sm"></i>
                </div>
                <span class="font-bold text-xl bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent hidden sm:inline">
                    WebApp
                </span>
            </a>
        </div>
        
        <!-- Center Section: Search Bar -->
        <div class="hidden md:flex flex-1 max-w-md mx-8">
            <div class="relative w-full">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" 
                       placeholder="Tìm kiếm..." 
                       id="searchInput"
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
            </div>
        </div>
        
        <!-- Right Section: Notifications & User Menu -->
        <div class="flex items-center gap-3">
            
            <!-- Notifications Dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="relative text-gray-600 hover:text-blue-600 transition-colors">
                    <i class="fas fa-bell text-xl"></i>
                    <span class="absolute -top-1 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold">
                        3
                    </span>
                </button>
                
                <div x-show="open" @click.away="open = false" x-cloak
                     class="absolute right-0 mt-3 w-80 bg-white rounded-lg shadow-xl border border-gray-200 z-50">
                    <div class="p-3 border-b border-gray-200">
                        <h3 class="font-semibold text-gray-800">Thông báo</h3>
                    </div>
                    <div class="max-h-96 overflow-y-auto">
                        <a href="#" class="flex items-center gap-3 p-3 hover:bg-gray-50 transition">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user-plus text-blue-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-800">Người dùng mới đăng ký</p>
                                <p class="text-xs text-gray-500">5 phút trước</p>
                            </div>
                        </a>
                        <a href="#" class="flex items-center gap-3 p-3 hover:bg-gray-50 transition">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-chart-line text-green-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-800">Báo cáo hàng tháng đã sẵn sàng</p>
                                <p class="text-xs text-gray-500">1 giờ trước</p>
                            </div>
                        </a>
                    </div>
                    <div class="p-2 border-t border-gray-200 text-center">
                        <a href="#" class="text-sm text-blue-600 hover:text-blue-700">Xem tất cả</a>
                    </div>
                </div>
            </div>
            
            <!-- User Dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center gap-2 focus:outline-none group">
                    <div class="w-9 h-9 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white shadow-md group-hover:shadow-lg transition">
                        <i class="fas fa-user text-sm"></i>
                    </div>
                    <span class="hidden md:inline text-gray-700 font-medium">{{ Auth::user()->name ?? 'Administrator' }}</span>
                    <i class="fas fa-chevron-down text-gray-500 text-xs transition-transform" :class="{ 'rotate-180': open }"></i>
                </button>
                
                <div x-show="open" @click.away="open = false" x-cloak
                     class="absolute right-0 mt-3 w-56 bg-white rounded-lg shadow-xl border border-gray-200 py-2 z-50">
                    <a href="{{ url('/profile') }}" class="flex items-center gap-3 px-4 py-2 text-gray-700 hover:bg-gray-50 transition">
                        <i class="fas fa-user-circle w-5 text-gray-500"></i>
                        <span>Hồ sơ cá nhân</span>
                    </a>
                    <a href="{{ url('/settings') }}" class="flex items-center gap-3 px-4 py-2 text-gray-700 hover:bg-gray-50 transition">
                        <i class="fas fa-cog w-5 text-gray-500"></i>
                        <span>Cài đặt tài khoản</span>
                    </a>
                    <hr class="my-1 border-gray-200">
                    <a href="{{ url('/logout') }}" class="flex items-center gap-3 px-4 py-2 text-red-600 hover:bg-red-50 transition">
                        <i class="fas fa-sign-out-alt w-5"></i>
                        <span>Đăng xuất</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>