<aside id="sidebar" class="fixed left-0 top-14 h-full w-64 bg-white border-r border-gray-200 shadow-lg transition-all duration-300 z-40 overflow-y-auto">
    <nav class="flex flex-col h-full">
        
        <!-- User Profile Summary -->
        <div class="p-4 border-b border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold">
                    {{ substr(Auth::user()->name ?? 'AD', 0, 2) }}
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name ?? 'Admin User' }}</p>
                    <p class="text-xs text-gray-500">{{ Auth::user()->email ?? 'admin@example.com' }}</p>
                </div>
            </div>
        </div>
        
        <!-- Navigation Menu -->
        <div class="flex-1 py-4">
            <ul class="space-y-1 px-3">
                
                <!-- Dashboard -->
                <li>
                    <a href="{{ url('/dashboard') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition group {{ request()->is('dashboard') ? 'bg-blue-50 text-blue-600' : '' }}">
                        <i class="fas fa-tachometer-alt w-5"></i>
                        <span>Tổng quan</span>
                        @if(request()->is('dashboard'))
                            <span class="ml-auto w-1.5 h-5 bg-blue-600 rounded-full"></span>
                        @endif
                    </a>
                </li>
                
                <!-- Users Section with Dropdown -->
                <li x-data="{ open: {{ request()->is('users*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" 
                            class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition group">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-users w-5"></i>
                            <span>Quản lý người dùng</span>
                        </div>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                    </button>
                    
                    <ul x-show="open" x-collapse class="ml-8 mt-1 space-y-1">
                        <li>
                            <a href="{{ url('/users') }}" 
                               class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition {{ request()->is('users') ? 'text-blue-600 bg-blue-50' : '' }}">
                                <i class="fas fa-list w-4"></i>
                                <span>Danh sách người dùng</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/users/create') }}" 
                               class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition">
                                <i class="fas fa-user-plus w-4"></i>
                                <span>Thêm người dùng mới</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/users/roles') }}" 
                               class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition">
                                <i class="fas fa-tag w-4"></i>
                                <span>Phân quyền</span>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <!-- Products -->
                <li>
                    <a href="{{ url('/products') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition {{ request()->is('products*') ? 'bg-blue-50 text-blue-600' : '' }}">
                        <i class="fas fa-box w-5"></i>
                        <span>Sản phẩm</span>
                    </a>
                </li>
                
                <!-- Orders -->
                <li>
                    <a href="{{ url('/orders') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                        <i class="fas fa-shopping-cart w-5"></i>
                        <span>Đơn hàng</span>
                        <span class="ml-auto bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">12</span>
                    </a>
                </li>
                
                <!-- Categories -->
                <li>
                    <a href="{{ url('/categories') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                        <i class="fas fa-folder w-5"></i>
                        <span>Danh mục</span>
                    </a>
                </li>
                
                <!-- Reports -->
                <li x-data="{ open: false }">
                    <button @click="open = !open" 
                            class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-chart-line w-5"></i>
                            <span>Báo cáo & Thống kê</span>
                        </div>
                        <i class="fas fa-chevron-down text-xs transition-transform" :class="{ 'rotate-180': open }"></i>
                    </button>
                    
                    <ul x-show="open" x-collapse class="ml-8 mt-1 space-y-1">
                        <li>
                            <a href="{{ url('/reports/sales') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-600 hover:bg-blue-50">
                                <i class="fas fa-chart-simple w-4"></i>
                                <span>Báo cáo doanh thu</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/reports/users') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-600 hover:bg-blue-50">
                                <i class="fas fa-users w-4"></i>
                                <span>Báo cáo người dùng</span>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <!-- Settings -->
                <li x-data="{ open: false }">
                    <button @click="open = !open" 
                            class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-cog w-5"></i>
                            <span>Cài đặt hệ thống</span>
                        </div>
                        <i class="fas fa-chevron-down text-xs transition-transform" :class="{ 'rotate-180': open }"></i>
                    </button>
                    
                    <ul x-show="open" x-collapse class="ml-8 mt-1 space-y-1">
                        <li>
                            <a href="{{ url('/settings/general') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-600 hover:bg-blue-50">
                                <i class="fas fa-globe w-4"></i>
                                <span>Cài đặt chung</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/settings/security') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-600 hover:bg-blue-50">
                                <i class="fas fa-shield w-4"></i>
                                <span>Bảo mật</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/settings/backup') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-600 hover:bg-blue-50">
                                <i class="fas fa-database w-4"></i>
                                <span>Sao lưu dữ liệu</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        
        <!-- Sidebar Footer / System Info -->
        <div class="border-t border-gray-200 p-4">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-3">
                <div class="flex items-center gap-2 mb-2">
                    <i class="fas fa-circle-info text-blue-500 text-sm"></i>
                    <span class="text-xs font-semibold text-gray-700">Hệ thống</span>
                </div>
                <div class="space-y-1 text-xs text-gray-600">
                    <p>📦 Phiên bản: 2.0.0</p>
                    <p>💾 Storage: 245/500 GB</p>
                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                        <div class="bg-blue-600 h-1.5 rounded-full" style="width: 49%"></div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</aside>