<!-- HR Dashboard - Employee Management -->
<div class="space-y-6">
    
    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-pink-500 to-rose-600 rounded-2xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold mb-2">
                    <i class="fas fa-users mr-2"></i>
                    Human Resources Dashboard
                </h1>
                <p class="text-pink-100">Quản lý nhân sự, tuyển dụng và đào tạo</p>
            </div>
            <div class="text-right">
                <div class="text-2xl font-bold">148</div>
                <div class="text-pink-100">Total Employees</div>
            </div>
        </div>
    </div>
    
    <!-- HR Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-tie text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">New Hires</p>
                    <p class="text-2xl font-bold">12</p>
                    <span class="text-green-500 text-xs">This month</span>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-calendar-check text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">On Leave</p>
                    <p class="text-2xl font-bold">8</p>
                    <span class="text-yellow-500 text-xs">Today</span>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-birthday-cake text-purple-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Birthdays</p>
                    <p class="text-2xl font-bold">3</p>
                    <span class="text-purple-500 text-xs">This week</span>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-chalkboard-user text-orange-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Training Hours</p>
                    <p class="text-2xl font-bold">156</p>
                    <span class="text-green-500 text-xs">↑ 23%</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Employee Lists -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Recent Hires -->
        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-user-plus text-green-500 mr-2"></i>
                Recent Hires
            </h3>
            <div class="space-y-3">
                <div class="flex items-center gap-3 p-2 border-b">
                    <img src="https://randomuser.me/api/portraits/women/1.jpg" class="w-10 h-10 rounded-full">
                    <div class="flex-1">
                        <p class="font-medium">Nguyễn Thị Lan</p>
                        <p class="text-xs text-gray-500">Frontend Developer</p>
                    </div>
                    <span class="text-xs text-green-600">2 days ago</span>
                </div>
                <div class="flex items-center gap-3 p-2 border-b">
                    <img src="https://randomuser.me/api/portraits/men/2.jpg" class="w-10 h-10 rounded-full">
                    <div class="flex-1">
                        <p class="font-medium">Trần Văn Minh</p>
                        <p class="text-xs text-gray-500">Data Analyst</p>
                    </div>
                    <span class="text-xs text-green-600">5 days ago</span>
                </div>
            </div>
        </div>
        
        <!-- Upcoming Events -->
        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-calendar-alt text-blue-500 mr-2"></i>
                Upcoming Events
            </h3>
            <div class="space-y-3">
                <div class="flex items-center gap-3 p-2 bg-yellow-50 rounded">
                    <i class="fas fa-gift text-yellow-600 text-xl"></i>
                    <div class="flex-1">
                        <p class="font-medium">Team Building Q4</p>
                        <p class="text-xs text-gray-500">December 15, 2024</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-2 bg-blue-50 rounded">
                    <i class="fas fa-chalkboard text-blue-600 text-xl"></i>
                    <div class="flex-1">
                        <p class="font-medium">Leadership Training</p>
                        <p class="text-xs text-gray-500">December 20, 2024</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">
            <i class="fas fa-bolt text-yellow-500 mr-2"></i>
            HR Actions
        </h3>
        <div class="flex flex-wrap gap-3">
            <button onclick="location.href='{{ url('/hr/employees/create') }}'" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <i class="fas fa-user-plus mr-2"></i>Add Employee
            </button>
            <button onclick="location.href='{{ url('/hr/leave-requests') }}'" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                <i class="fas fa-calendar-minus mr-2"></i>Leave Requests
            </button>
            <button onclick="location.href='{{ url('/hr/payroll') }}'" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                <i class="fas fa-coins mr-2"></i>Payroll
            </button>
        </div>
    </div>
</div>