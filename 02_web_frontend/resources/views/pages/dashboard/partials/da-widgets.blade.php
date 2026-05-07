<!-- Data Analyst Dashboard - Big Data & Analytics -->
<div class="space-y-6">
    
    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-700 rounded-2xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold mb-2">
                    <i class="fas fa-database mr-2"></i>
                    Data Analytics Hub
                </h1>
                <p class="text-indigo-100">Phân tích dữ liệu, báo cáo và dự báo</p>
            </div>
            <div class="text-right">
                <div class="text-sm">Last ETL Run</div>
                <div class="font-mono">{{ date('H:i:s') }}</div>
            </div>
        </div>
    </div>
    
    <!-- Data Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-chart-pie text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Total Records</p>
                    <p class="text-2xl font-bold">1.24M</p>
                    <span class="text-green-500 text-xs">↑ 45k today</span>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-chart-line text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Data Sources</p>
                    <p class="text-2xl font-bold">12</p>
                    <span class="text-blue-500 text-xs">3 real-time</span>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-chart-simple text-purple-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Active Dashboards</p>
                    <p class="text-2xl font-bold">8</p>
                    <span class="text-purple-500 text-xs">Shared</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Data Quality Metrics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Data Quality -->
        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-check-double text-green-500 mr-2"></i>
                Data Quality Score
            </h3>
            <div class="text-center mb-4">
                <div class="text-5xl font-bold text-green-600">94.5%</div>
                <p class="text-gray-500 text-sm">Overall Quality</p>
            </div>
            <div class="space-y-3">
                <div>
                    <div class="flex justify-between text-sm">
                        <span>Completeness</span>
                        <span>98%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full" style="width: 98%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm">
                        <span>Accuracy</span>
                        <span>96%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: 96%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm">
                        <span>Consistency</span>
                        <span>92%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-yellow-600 h-2 rounded-full" style="width: 92%"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Data Pipeline Status -->
        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-stream text-blue-500 mr-2"></i>
                Data Pipeline Status
            </h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-check-circle text-green-500"></i>
                        <span>ETL - Customer Data</span>
                    </div>
                    <span class="text-xs text-green-600">Completed 09:45</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-spinner fa-pulse text-blue-500"></i>
                        <span>Real-time Analytics</span>
                    </div>
                    <span class="text-xs text-blue-600">Processing</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-clock text-yellow-500"></i>
                        <span>Data Warehouse Sync</span>
                    </div>
                    <span class="text-xs text-yellow-600">Scheduled 10:00</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Data Export -->
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">
            <i class="fas fa-download text-purple-500 mr-2"></i>
            Data Export & Reports
        </h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <button onclick="exportData('csv')" class="p-3 bg-gray-100 rounded-lg hover:bg-gray-200 transition text-center">
                <i class="fas fa-file-csv text-green-600 text-xl mb-1 block"></i>
                <span class="text-sm">Export CSV</span>
            </button>
            <button onclick="exportData('excel')" class="p-3 bg-gray-100 rounded-lg hover:bg-gray-200 transition text-center">
                <i class="fas fa-file-excel text-green-700 text-xl mb-1 block"></i>
                <span class="text-sm">Export Excel</span>
            </button>
            <button onclick="exportData('json')" class="p-3 bg-gray-100 rounded-lg hover:bg-gray-200 transition text-center">
                <i class="fab fa-js text-yellow-600 text-xl mb-1 block"></i>
                <span class="text-sm">Export JSON</span>
            </button>
            <button onclick="scheduleReport()" class="p-3 bg-gray-100 rounded-lg hover:bg-gray-200 transition text-center">
                <i class="fas fa-calendar-alt text-blue-600 text-xl mb-1 block"></i>
                <span class="text-sm">Schedule Report</span>
            </button>
        </div>
    </div>
</div>

<script>
    function exportData(format) {
        window.location.href = `{{ url("/api/export-data") }}?format=${format}`;
    }
    
    function scheduleReport() {
        alert('Mở lịch trình báo cáo tự động');
    }
</script>