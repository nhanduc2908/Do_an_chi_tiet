<!-- Business Analyst Dashboard - KPI & Analytics -->
<div class="space-y-6">
    
    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-teal-500 to-green-600 rounded-2xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold mb-2">
                    <i class="fas fa-chart-bar mr-2"></i>
                    Business Analytics
                </h1>
                <p class="text-teal-100">Theo dõi KPI, doanh thu và hiệu suất kinh doanh</p>
            </div>
            <div class="text-right">
                <div class="text-2xl font-bold">{{ date('F Y') }}</div>
                <div class="text-teal-100">Q4 Report</div>
            </div>
        </div>
    </div>
    
    <!-- KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-teal-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Doanh thu tháng</p>
                    <p class="text-2xl font-bold text-gray-800">$45.2M</p>
                    <span class="text-green-500 text-xs">↑ 15.3%</span>
                </div>
                <i class="fas fa-dollar-sign text-teal-500 text-3xl"></i>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Lợi nhuận ròng</p>
                    <p class="text-2xl font-bold text-gray-800">$12.8M</p>
                    <span class="text-green-500 text-xs">↑ 8.2%</span>
                </div>
                <i class="fas fa-chart-line text-blue-500 text-3xl"></i>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Khách hàng mới</p>
                    <p class="text-2xl font-bold text-gray-800">2,456</p>
                    <span class="text-green-500 text-xs">↑ 23%</span>
                </div>
                <i class="fas fa-user-plus text-purple-500 text-3xl"></i>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Tỷ lệ chuyển đổi</p>
                    <p class="text-2xl font-bold text-gray-800">3.24%</p>
                    <span class="text-green-500 text-xs">↑ 0.5%</span>
                </div>
                <i class="fas fa-percent text-orange-500 text-3xl"></i>
            </div>
        </div>
    </div>
    
    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Revenue Chart -->
        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-chart-line text-blue-500 mr-2"></i>
                Doanh thu theo tháng
            </h3>
            <canvas id="revenueChart" height="250"></canvas>
        </div>
        
        <!-- Top Products -->
        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-trophy text-yellow-500 mr-2"></i>
                Sản phẩm bán chạy
            </h3>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span>Product A - Premium Package</span>
                        <span>$45,200</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: 85%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span>Product B - Standard Plan</span>
                        <span>$32,100</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full" style="width: 65%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span>Product C - Enterprise</span>
                        <span>$28,500</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-purple-600 h-2 rounded-full" style="width: 55%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Forecast -->
    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl shadow p-6 border border-yellow-200">
        <h3 class="text-lg font-semibold text-gray-800 mb-3">
            <i class="fas fa-chart-simple text-orange-500 mr-2"></i>
            Dự báo Q1 2025
        </h3>
        <div class="grid grid-cols-3 gap-4 text-center">
            <div>
                <p class="text-gray-500 text-sm">Dự kiến doanh thu</p>
                <p class="text-xl font-bold text-green-600">$52.5M</p>
                <span class="text-xs text-green-500">↑ 16%</span>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Tăng trưởng thị phần</p>
                <p class="text-xl font-bold text-blue-600">+2.8%</p>
                <span class="text-xs text-blue-500">Tích cực</span>
            </div>
            <div>
                <p class="text-gray-500 text-sm">CAPEX</p>
                <p class="text-xl font-bold text-purple-600">$8.2M</p>
                <span class="text-xs text-purple-500">Đã phê duyệt</span>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Revenue Chart
    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Doanh thu 2024',
                data: [32, 35, 38, 42, 45, 48, 52, 55, 58, 62, 65, 68],
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
</script>