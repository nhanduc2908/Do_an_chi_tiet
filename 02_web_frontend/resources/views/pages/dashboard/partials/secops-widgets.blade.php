<!-- SecOps Dashboard - Security Monitoring -->
<div class="space-y-6">
    
    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-red-700 to-red-900 rounded-2xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold mb-2">
                    <i class="fas fa-shield-alt mr-2"></i>
                    Security Operations Center
                </h1>
                <p class="text-red-200">Security monitoring, threats và incidents</p>
            </div>
            <div class="text-right">
                <div class="text-green-400 text-sm">System Status</div>
                <div class="text-xl font-bold">SECURE</div>
            </div>
        </div>
    </div>
    
    <!-- Security Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-center">
                <i class="fas fa-shield-haltered text-red-500 text-2xl mb-2 block"></i>
                <div class="text-2xl font-bold">3</div>
                <div class="text-xs text-gray-500">Active Threats</div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-center">
                <i class="fas fa-ban text-yellow-500 text-2xl mb-2 block"></i>
                <div class="text-2xl font-bold">1,234</div>
                <div class="text-xs text-gray-500">Blocked Attempts</div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-center">
                <i class="fas fa-user-secret text-purple-500 text-2xl mb-2 block"></i>
                <div class="text-2xl font-bold">8</div>
                <div class="text-xs text-gray-500">Suspicious IPs</div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-center">
                <i class="fas fa-clock text-blue-500 text-2xl mb-2 block"></i>
                <div class="text-2xl font-bold">99.99%</div>
                <div class="text-xs text-gray-500">Uptime</div>
            </div>
        </div>
    </div>
    
    <!-- Security Alerts -->
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">
            <i class="fas fa-bell text-red-500 mr-2"></i>
            Security Alerts
        </h3>
        <div class="space-y-3">
            <div class="flex items-center justify-between p-3 bg-red-50 rounded border-l-4 border-red-500">
                <div>
                    <p class="font-medium">Multiple failed login attempts</p>
                    <p class="text-xs text-gray-500">IP: 192.168.1.100 - 5 attempts</p>
                </div>
                <button class="px-3 py-1 bg-red-600 text-white rounded text-sm">Investigate</button>
            </div>
            <div class="flex items-center justify-between p-3 bg-yellow-50 rounded border-l-4 border-yellow-500">
                <div>
                    <p class="font-medium">Unusual file access pattern</p>
                    <p class="text-xs text-gray-500">User: admin - /etc/passwd</p>
                </div>
                <button class="px-3 py-1 bg-yellow-600 text-white rounded text-sm">Review</button>
            </div>
        </div>
    </div>
</div>