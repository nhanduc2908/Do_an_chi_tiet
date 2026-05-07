<!-- Developer Dashboard - Code & Deployment -->
<div class="space-y-6">
    
    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-gray-800 to-gray-900 rounded-2xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold mb-2">
                    <i class="fab fa-github mr-2"></i>
                    Developer Workspace
                </h1>
                <p class="text-gray-300">Theo dõi code, deployment và performance</p>
            </div>
            <div class="text-right">
                <div class="text-3xl font-mono">{{ date('H:i:s') }}</div>
                <div class="text-gray-300">Sprint 24</div>
            </div>
        </div>
    </div>
    
    <!-- Dev Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow p-6 border-t-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Commits hôm nay</p>
                    <p class="text-2xl font-bold text-gray-800">24</p>
                    <span class="text-green-500 text-xs">↑ 8</span>
                </div>
                <i class="fab fa-git-alt text-purple-500 text-3xl"></i>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow p-6 border-t-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Pull Requests</p>
                    <p class="text-2xl font-bold text-gray-800">7</p>
                    <span class="text-yellow-500 text-xs">3 đang review</span>
                </div>
                <i class="fab fa-github text-blue-500 text-3xl"></i>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow p-6 border-t-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Deployments</p>
                    <p class="text-2xl font-bold text-gray-800">12</p>
                    <span class="text-green-500 text-xs">Thành công</span>
                </div>
                <i class="fas fa-rocket text-green-500 text-3xl"></i>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow p-6 border-t-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Bugs</p>
                    <p class="text-2xl font-bold text-gray-800">5</p>
                    <span class="text-red-500 text-xs">2 critical</span>
                </div>
                <i class="fas fa-bug text-red-500 text-3xl"></i>
            </div>
        </div>
    </div>
    
    <!-- Development Metrics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- CI/CD Pipeline -->
        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-code-branch text-blue-500 mr-2"></i>
                CI/CD Pipeline
            </h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-check-circle text-green-500"></i>
                        <span class="text-sm">Build #2456</span>
                    </div>
                    <span class="text-xs text-green-500">Success</span>
                </div>
                <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-spinner fa-pulse text-blue-500"></i>
                        <span class="text-sm">Test Running</span>
                    </div>
                    <span class="text-xs text-blue-500">In Progress</span>
                </div>
                <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-cloud-upload-alt text-purple-500"></i>
                        <span class="text-sm">Deploy to Staging</span>
                    </div>
                    <span class="text-xs text-purple-500">Pending</span>
                </div>
            </div>
        </div>
        
        <!-- Code Coverage -->
        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-chart-pie text-green-500 mr-2"></i>
                Code Coverage
            </h3>
            <div class="text-center">
                <div class="relative inline-block">
                    <svg class="w-32 h-32">
                        <circle cx="64" cy="64" r="56" stroke="#e5e7eb" stroke-width="12" fill="none"/>
                        <circle cx="64" cy="64" r="56" stroke="#10b981" stroke-width="12" fill="none" 
                                stroke-dasharray="351.86" stroke-dashoffset="105.56" transform="rotate(-90 64 64)"/>
                    </svg>
                    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-center">
                        <div class="text-2xl font-bold">85%</div>
                        <div class="text-xs text-gray-500">Coverage</div>
                    </div>
                </div>
            </div>
            <div class="mt-4 grid grid-cols-2 gap-2 text-center">
                <div class="p-2 bg-green-50 rounded">
                    <div class="text-sm font-bold">250</div>
                    <div class="text-xs text-gray-500">Tests Passed</div>
                </div>
                <div class="p-2 bg-red-50 rounded">
                    <div class="text-sm font-bold">15</div>
                    <div class="text-xs text-gray-500">Tests Failed</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Commits -->
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">
            <i class="fab fa-git-alt text-orange-500 mr-2"></i>
            Recent Commits
        </h3>
        <div class="space-y-3">
            <div class="flex items-center gap-3 p-2 border-b">
                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fab fa-github text-purple-600 text-sm"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium">Fix: Authentication middleware issue</p>
                    <p class="text-xs text-gray-500">dev/namnguyen • 2 giờ trước</p>
                </div>
                <span class="text-xs bg-green-100 text-green-600 px-2 py-1 rounded">merged</span>
            </div>
            <div class="flex items-center gap-3 p-2 border-b">
                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fab fa-github text-blue-600 text-sm"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium">Feature: Add API rate limiting</p>
                    <p class="text-xs text-gray-500">dev/minhtran • 5 giờ trước</p>
                </div>
                <span class="text-xs bg-yellow-100 text-yellow-600 px-2 py-1 rounded">review</span>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="bg-gray-800 rounded-xl shadow p-6 text-white">
        <h3 class="text-lg font-semibold mb-4">
            <i class="fas fa-terminal mr-2"></i>
            Quick Commands
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <button onclick="runCommand('deploy')" class="bg-gray-700 hover:bg-gray-600 rounded-lg p-3 text-sm font-mono">
                <i class="fas fa-rocket mr-2"></i> Deploy Staging
            </button>
            <button onclick="runCommand('test')" class="bg-gray-700 hover:bg-gray-600 rounded-lg p-3 text-sm font-mono">
                <i class="fas fa-vial mr-2"></i> Run Tests
            </button>
            <button onclick="runCommand('build')" class="bg-gray-700 hover:bg-gray-600 rounded-lg p-3 text-sm font-mono">
                <i class="fas fa-cogs mr-2"></i> Build Assets
            </button>
        </div>
    </div>
</div>

<script>
    function runCommand(cmd) {
        $.ajax({
            url: '{{ url("/api/run-command") }}',
            type: 'POST',
            data: { command: cmd },
            success: function(response) {
                alert(`Command ${cmd} executed successfully`);
            }
        });
    }
</script>