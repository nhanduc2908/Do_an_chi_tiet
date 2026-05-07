<!-- Auditor Dashboard - Compliance & Audit -->
<div class="space-y-6">
    
    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-gray-700 to-gray-900 rounded-2xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold mb-2">
                    <i class="fas fa-clipboard-list mr-2"></i>
                    Audit & Compliance
                </h1>
                <p class="text-gray-300">Kiểm tra tuân thủ, logs và báo cáo</p>
            </div>
            <div class="text-right">
                <div class="text-green-400">Last Audit: {{ date('d/m/Y') }}</div>
            </div>
        </div>
    </div>
    
    <!-- Compliance Stats -->
    <div class="grid grid-cols-3 gap-6">
        <div class="bg-green-50 rounded-xl p-4 text-center">
            <div class="text-2xl font-bold text-green-600">98%</div>
            <div class="text-xs">Compliance Score</div>
        </div>
        <div class="bg-blue-50 rounded-xl p-4 text-center">
            <div class="text-2xl font-bold text-blue-600">12</div>
            <div class="text-xs">Audit Findings</div>
        </div>
        <div class="bg-yellow-50 rounded-xl p-4 text-center">
            <div class="text-2xl font-bold text-yellow-600">3</div>
            <div class="text-xs">Non-Compliant</div>
        </div>
    </div>
    
    <!-- Audit Logs -->
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Recent Audit Logs</h3>
        <div class="space-y-2">
            <div class="p-2 border-b text-sm">
                <i class="fas fa-user-check text-green-500 mr-2"></i>
                User role changed: admin@system
                <span class="text-gray-400 float-right">10:30 AM</span>
            </div>
            <div class="p-2 border-b text-sm">
                <i class="fas fa-database text-blue-500 mr-2"></i>
                Database backup initiated
                <span class="text-gray-400 float-right">09:15 AM</span>
            </div>
        </div>
    </div>
</div>