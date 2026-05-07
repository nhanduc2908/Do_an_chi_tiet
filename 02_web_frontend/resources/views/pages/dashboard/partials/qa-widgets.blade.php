<!-- QA Dashboard - Testing & Quality -->
<div class="space-y-6">
    
    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-cyan-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold mb-2">
                    <i class="fas fa-vial mr-2"></i>
                    Quality Assurance Dashboard
                </h1>
                <p class="text-cyan-100">Testing metrics, bug tracking và quality gates</p>
            </div>
            <div class="text-right">
                <div class="text-3xl font-bold">96%</div>
                <div class="text-cyan-100">Pass Rate</div>
            </div>
        </div>
    </div>
    
    <!-- Test Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow p-4 text-center">
            <div class="text-2xl font-bold text-green-600">245</div>
            <div class="text-xs text-gray-500">Tests Passed</div>
        </div>
        <div class="bg-white rounded-xl shadow p-4 text-center">
            <div class="text-2xl font-bold text-red-600">8</div>
            <div class="text-xs text-gray-500">Tests Failed</div>
        </div>
        <div class="bg-white rounded-xl shadow p-4 text-center">
            <div class="text-2xl font-bold text-yellow-600">12</div>
            <div class="text-xs text-gray-500">In Progress</div>
        </div>
        <div class="bg-white rounded-xl shadow p-4 text-center">
            <div class="text-2xl font-bold text-blue-600">95%</div>
            <div class="text-xs text-gray-500">Coverage</div>
        </div>
    </div>
    
    <!-- Bug Tracking -->
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">
            <i class="fas fa-bug text-red-500 mr-2"></i>
            Bug Tracking
        </h3>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs">ID</th>
                        <th class="px-4 py-2 text-left text-xs">Title</th>
                        <th class="px-4 py-2 text-left text-xs">Severity</th>
                        <th class="px-4 py-2 text-left text-xs">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b">
                        <td class="px-4 py-2 text-sm">BUG-101</td>
                        <td class="px-4 py-2 text-sm">Login page crash</td>
                        <td class="px-4 py-2"><span class="bg-red-100 text-red-600 px-2 py-1 rounded text-xs">Critical</span></td>
                        <td class="px-4 py-2"><span class="bg-yellow-100 text-yellow-600 px-2 py-1 rounded text-xs">In Progress</span></td>
                    </tr>
                    <tr class="border-b">
                        <td class="px-4 py-2 text-sm">BUG-102</td>
                        <td class="px-4 py-2 text-sm">UI alignment issue</td>
                        <td class="px-4 py-2"><span class="bg-yellow-100 text-yellow-600 px-2 py-1 rounded text-xs">Minor</span></td>
                        <td class="px-4 py-2"><span class="bg-green-100 text-green-600 px-2 py-1 rounded text-xs">Fixed</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Test Suite -->
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">
            <i class="fas fa-play-circle text-green-500 mr-2"></i>
            Test Suite
        </h3>
        <button onclick="runTests()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
            <i class="fas fa-play mr-2"></i> Run All Tests
        </button>
    </div>
</div>

<script>
    function runTests() {
        alert('Running all test suites...');
    }
</script>