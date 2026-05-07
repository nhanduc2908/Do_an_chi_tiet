@extends('layouts.app')

@section('title', 'Truy vết - Nhật ký hệ thống')

@section('breadcrumb')
<nav class="flex" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ url('/dashboard') }}" class="text-gray-700 hover:text-blue-600">
                <i class="fas fa-home mr-2"></i> Trang chủ
            </a>
        </li>
        <li>
            <div class="flex items-center">
                <i class="fas fa-chevron-right text-gray-400 text-sm mx-2"></i>
                <span class="text-gray-500">Truy vết</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="space-y-6">
    
    <!-- Header -->
    <div class="bg-gradient-to-r from-gray-800 to-gray-900 rounded-2xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold mb-2">
                    <i class="fas fa-history mr-2"></i>
                    Nhật ký hệ thống & Truy vết
                </h1>
                <p class="text-gray-300">Theo dõi toàn bộ hoạt động trong hệ thống</p>
            </div>
            <div class="text-right">
                <div class="text-sm text-gray-300">Tổng số logs</div>
                <div class="text-2xl font-bold">124,583</div>
            </div>
        </div>
    </div>

    <!-- Bộ lọc -->
    <div class="bg-white rounded-xl shadow p-4">
        <div class="grid grid-cols-1 md:grid-cols-6 gap-3">
            <input type="text" placeholder="Tìm kiếm..." class="border rounded-lg px-3 py-2 col-span-2">
            <select class="border rounded-lg px-3 py-2">
                <option>Hành động</option>
                <option>Đăng nhập</option>
                <option>Thêm mới</option>
                <option>Cập nhật</option>
                <option>Xóa</option>
                <option>Xuất dữ liệu</option>
            </select>
            <select class="border rounded-lg px-3 py-2">
                <option>Mức độ</option>
                <option>Thông tin</option>
                <option>Cảnh báo</option>
                <option>Lỗi</option>
                <option>Nguy hiểm</option>
            </select>
            <input type="date" class="border rounded-lg px-3 py-2" value="{{ date('Y-m-d') }}">
            <button class="bg-blue-600 text-white rounded-lg px-4 py-2 hover:bg-blue-700">
                <i class="fas fa-filter mr-2"></i> Lọc
            </button>
        </div>
    </div>

    <!-- Bảng nhật ký -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Thời gian</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Người dùng</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Hành động</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Đối tượng</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">IP</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Mức độ</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm">10:30:25 15/12/2024</td>
                        <td class="px-4 py-3 text-sm">admin@system.com</td>
                        <td class="px-4 py-3 text-sm">Đăng nhập</td>
                        <td class="px-4 py-3 text-sm">Hệ thống</td>
                        <td class="px-4 py-3 text-sm">192.168.1.100</td>
                        <td class="px-4 py-3"><span class="bg-green-100 text-green-600 px-2 py-1 rounded text-xs">Thông tin</span></td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm">10:15:02 15/12/2024</td>
                        <td class="px-4 py-3 text-sm">user01@example.com</td>
                        <td class="px-4 py-3 text-sm">Xóa dữ liệu</td>
                        <td class="px-4 py-3 text-sm">Bảng users (ID: 1234)</td>
                        <td class="px-4 py-3 text-sm">192.168.1.105</td>
                        <td class="px-4 py-3"><span class="bg-red-100 text-red-600 px-2 py-1 rounded text-xs">Cảnh báo</span></td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm">09:45:33 15/12/2024</td>
                        <td class="px-4 py-3 text-sm">system</td>
                        <td class="px-4 py-3 text-sm">Backup DB</td>
                        <td class="px-4 py-3 text-sm">Cơ sở dữ liệu</td>
                        <td class="px-4 py-3 text-sm">localhost</td>
                        <td class="px-4 py-3"><span class="bg-blue-100 text-blue-600 px-2 py-1 rounded text-xs">Thành công</span></td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm">08:20:15 15/12/2024</td>
                        <td class="px-4 py-3 text-sm">unknown</td>
                        <td class="px-4 py-3 text-sm">Đăng nhập thất bại</td>
                        <td class="px-4 py-3 text-sm">Hệ thống</td>
                        <td class="px-4 py-3 text-sm">45.33.22.11</td>
                        <td class="px-4 py-3"><span class="bg-orange-100 text-orange-600 px-2 py-1 rounded text-xs">Nguy hiểm</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="bg-gray-50 px-4 py-3 border-t flex justify-between items-center">
            <div class="text-sm text-gray-500">Hiển thị 1-4 của 124,583 logs</div>
            <div class="flex gap-2">
                <button class="px-3 py-1 border rounded hover:bg-gray-100"><i class="fas fa-chevron-left"></i></button>
                <button class="px-3 py-1 bg-blue-600 text-white rounded">1</button>
                <button class="px-3 py-1 border rounded hover:bg-gray-100">2</button>
                <button class="px-3 py-1 border rounded hover:bg-gray-100">3</button>
                <button class="px-3 py-1 border rounded hover:bg-gray-100"><i class="fas fa-chevron-right"></i></button>
            </div>
        </div>
    </div>

    <!-- Thống kê audit -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow p-4">
            <h4 class="font-medium mb-2">Hoạt động theo giờ</h4>
            <canvas id="hourlyChart" height="150"></canvas>
        </div>
        <div class="bg-white rounded-xl shadow p-4">
            <h4 class="font-medium mb-2">Top hành động</h4>
            <div class="space-y-2">
                <div class="flex justify-between text-sm">
                    <span>Đăng nhập</span>
                    <span>45%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-1.5"><div class="bg-blue-600 h-1.5 rounded-full" style="width:45%"></div></div>
                <div class="flex justify-between text-sm">
                    <span>Cập nhật</span>
                    <span>28%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-1.5"><div class="bg-green-600 h-1.5 rounded-full" style="width:28%"></div></div>
                <div class="flex justify-between text-sm">
                    <span>Truy xuất</span>
                    <span>18%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-1.5"><div class="bg-purple-600 h-1.5 rounded-full" style="width:18%"></div></div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow p-4">
            <h4 class="font-medium mb-2">Lưu trữ logs</h4>
            <div class="text-center">
                <div class="text-3xl font-bold text-blue-600">2.4 GB</div>
                <div class="text-sm text-gray-500">Dung lượng logs</div>
                <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-yellow-600 h-2 rounded-full" style="width: 48%"></div>
                </div>
                <div class="text-xs text-gray-500 mt-1">Đã dùng 48%</div>
            </div>
        </div>
    </div>
</div>

<script>
    const hourlyCtx = document.getElementById('hourlyChart')?.getContext('2d');
    if (hourlyCtx) {
        new Chart(hourlyCtx, {
            type: 'line',
            data: {
                labels: ['0h', '3h', '6h', '9h', '12h', '15h', '18h', '21h'],
                datasets: [{ label: 'Số lượng hoạt động', data: [120, 45, 89, 456, 789, 654, 432, 234], borderColor: '#3b82f6', fill: false }]
            }
        });
    }
</script>
@endsection