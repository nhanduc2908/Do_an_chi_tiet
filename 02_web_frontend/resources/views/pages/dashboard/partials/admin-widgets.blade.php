<!-- Admin Dashboard - Full System Control -->
<div class="space-y-6">
    
    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold mb-2">
                    <i class="fas fa-crown mr-2"></i>
                    Chào mừng Admin trở lại!
                </h1>
                <p class="text-blue-100">Quản lý toàn bộ hệ thống, người dùng và bảo mật</p>
            </div>
            <div class="text-right">
                <div class="text-3xl font-bold">{{ date('d/m/Y') }}</div>
                <div class="text-blue-100">{{ date('H:i:s') }}</div>
            </div>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Tổng người dùng</p>
                    <p class="text-2xl font-bold text-gray-800" id="totalUsers">1,234</p>
                    <span class="text-green-500 text-xs">↑ 12%</span>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Hoạt động hôm nay</p>
                    <p class="text-2xl font-bold text-gray-800" id="todayActivity">456</p>
                    <span class="text-green-500 text-xs">↑ 8%</span>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-line text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Hệ thống đang chạy</p>
                    <p class="text-2xl font-bold text-gray-800" id="systemUptime">99.9%</p>
                    <span class="text-yellow-500 text-xs">Uptime 30d</span>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-server text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Cảnh báo bảo mật</p>
                    <p class="text-2xl font-bold text-gray-800" id="securityAlerts">3</p>
                    <span class="text-red-500 text-xs">⚠️ Cần xử lý</span>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-shield-alt text-red-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Admin Specific Widgets -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- System Health -->
        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-heartbeat text-red-500 mr-2"></i>
                Sức khỏe hệ thống
            </h3>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span>CPU Usage</span>
                        <span>45%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: 45%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span>Memory Usage</span>
                        <span>3.2/8 GB</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full" style="width: 40%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span>Disk Usage</span>
                        <span>245/500 GB</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-yellow-600 h-2 rounded-full" style="width: 49%"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Activities -->
        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-history text-blue-500 mr-2"></i>
                Hoạt động gần đây
            </h3>
            <div class="space-y-3">
                <div class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded">
                    <i class="fas fa-user-plus text-green-500"></i>
                    <div class="flex-1">
                        <p class="text-sm">Người dùng mới: <strong>Nguyễn Văn A</strong></p>
                        <p class="text-xs text-gray-500">5 phút trước</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded">
                    <i class="fas fa-database text-blue-500"></i>
                    <div class="flex-1">
                        <p class="text-sm">Backup database hoàn tất</p>
                        <p class="text-xs text-gray-500">1 giờ trước</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded">
                    <i class="fas fa-exclamation-triangle text-yellow-500"></i>
                    <div class="flex-1">
                        <p class="text-sm">Phát hiện đăng nhập bất thường</p>
                        <p class="text-xs text-gray-500">3 giờ trước</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">
            <i class="fas fa-bolt text-yellow-500 mr-2"></i>
            Thao tác nhanh
        </h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <button onclick="window.location.href='{{ url('/users/create') }}'" class="p-4 bg-blue-50 rounded-xl hover:bg-blue-100 transition text-center">
                <i class="fas fa-user-plus text-blue-600 text-2xl mb-2 block"></i>
                <span class="text-sm">Thêm người dùng</span>
            </button>
            <button onclick="window.location.href='{{ url('/settings/backup') }}'" class="p-4 bg-green-50 rounded-xl hover:bg-green-100 transition text-center">
                <i class="fas fa-database text-green-600 text-2xl mb-2 block"></i>
                <span class="text-sm">Sao lưu dữ liệu</span>
            </button>
            <button onclick="window.location.href='{{ url('/settings/security') }}'" class="p-4 bg-red-50 rounded-xl hover:bg-red-100 transition text-center">
                <i class="fas fa-shield-alt text-red-600 text-2xl mb-2 block"></i>
                <span class="text-sm">Bảo mật hệ thống</span>
            </button>
            <button onclick="window.location.href='{{ url('/logs') }}'" class="p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition text-center">
                <i class="fas fa-file-alt text-gray-600 text-2xl mb-2 block"></i>
                <span class="text-sm">Xem log hệ thống</span>
            </button>
        </div>
    </div>
</div>