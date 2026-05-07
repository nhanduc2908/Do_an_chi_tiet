@extends('layouts.app')

@section('title', 'Báo cáo - Hệ thống báo cáo tổng hợp')

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
                <span class="text-gray-500">Báo cáo</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="space-y-6">
    
    <!-- Header -->
    <div class="bg-gradient-to-r from-emerald-600 to-teal-700 rounded-2xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold mb-2">
                    <i class="fas fa-chart-pie mr-2"></i>
                    Trung tâm Báo cáo
                </h1>
                <p class="text-emerald-100">Báo cáo thống kê, phân tích dữ liệu theo thời gian thực</p>
            </div>
            <div>
                <button onclick="exportReport()" class="px-4 py-2 bg-white/20 backdrop-blur rounded-lg hover:bg-white/30">
                    <i class="fas fa-download mr-2"></i> Xuất báo cáo
                </button>
            </div>
        </div>
    </div>

    <!-- Bộ lọc báo cáo -->
    <div class="bg-white rounded-xl shadow p-4">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
            <select class="border rounded-lg px-3 py-2">
                <option>Chọn loại báo cáo</option>
                <option>Báo cáo doanh thu</option>
                <option>Báo cáo nhân sự</option>
                <option>Báo cáo dự án</option>
                <option>Báo cáo hệ thống</option>
            </select>
            <input type="date" class="border rounded-lg px-3 py-2" value="{{ date('Y-m-01') }}">
            <input type="date" class="border rounded-lg px-3 py-2" value="{{ date('Y-m-d') }}">
            <select class="border rounded-lg px-3 py-2">
                <option>Định dạng</option>
                <option>PDF</option>
                <option>Excel</option>
                <option>CSV</option>
            </select>
            <button class="bg-blue-600 text-white rounded-lg px-4 py-2 hover:bg-blue-700">
                <i class="fas fa-search mr-2"></i> Tạo báo cáo
            </button>
        </div>
    </div>

    <!-- Danh sách báo cáo mẫu -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow p-4 hover:shadow-lg transition cursor-pointer" onclick="loadReport('revenue')">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-3">
                <i class="fas fa-dollar-sign text-blue-600 text-xl"></i>
            </div>
            <h3 class="font-semibold">Báo cáo Doanh thu</h3>
            <p class="text-xs text-gray-500 mt-1">Tổng quan doanh thu theo tháng/quý/năm</p>
            <div class="mt-2 text-sm text-green-600">Cập nhật: {{ date('d/m/Y') }}</div>
        </div>

        <div class="bg-white rounded-xl shadow p-4 hover:shadow-lg transition cursor-pointer" onclick="loadReport('hr')">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-3">
                <i class="fas fa-users text-green-600 text-xl"></i>
            </div>
            <h3 class="font-semibold">Báo cáo Nhân sự</h3>
            <p class="text-xs text-gray-500 mt-1">Thống kê nhân viên, tuyển dụng, nghỉ phép</p>
        </div>

        <div class="bg-white rounded-xl shadow p-4 hover:shadow-lg transition cursor-pointer" onclick="loadReport('project')">
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-3">
                <i class="fas fa-project-diagram text-purple-600 text-xl"></i>
            </div>
            <h3 class="font-semibold">Báo cáo Dự án</h3>
            <p class="text-xs text-gray-500 mt-1">Tiến độ, nguồn lực, chi phí dự án</p>
        </div>

        <div class="bg-white rounded-xl shadow p-4 hover:shadow-lg transition cursor-pointer" onclick="loadReport('system')">
            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mb-3">
                <i class="fas fa-server text-orange-600 text-xl"></i>
            </div>
            <h3 class="font-semibold">Báo cáo Hệ thống</h3>
            <p class="text-xs text-gray-500 mt-1">Hiệu năng, uptime, bảo mật hệ thống</p>
        </div>
    </div>

    <!-- Báo cáo chi tiết -->
    <div class="bg-white rounded-xl shadow overflow-hidden" id="reportDetail">
        <div class="bg-gray-50 px-6 py-3 border-b">
            <h3 class="font-semibold">Chi tiết báo cáo</h3>
        </div>
        <div class="p-6">
            <div class="text-center text-gray-500 py-8">
                <i class="fas fa-chart-line text-4xl mb-2 block"></i>
                <p>Chọn một loại báo cáo để xem chi tiết</p>
            </div>
        </div>
    </div>

    <!-- Báo cáo đã lưu -->
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="text-lg font-semibold mb-4">
            <i class="fas fa-history text-blue-500 mr-2"></i>
            Báo cáo đã lưu gần đây
        </h3>
        <div class="space-y-2">
            <div class="flex items-center justify-between p-3 border-b">
                <div>
                    <p class="font-medium">BC_DoanhThu_Q4_2024.pdf</p>
                    <p class="text-xs text-gray-500">Tạo lúc: 15/12/2024 14:30</p>
                </div>
                <div class="flex gap-2">
                    <button class="text-blue-600 hover:text-blue-800"><i class="fas fa-download"></i></button>
                    <button class="text-red-600 hover:text-red-800"><i class="fas fa-trash"></i></button>
                </div>
            </div>
            <div class="flex items-center justify-between p-3 border-b">
                <div>
                    <p class="font-medium">BC_NhanSu_Thang11.xlsx</p>
                    <p class="text-xs text-gray-500">Tạo lúc: 01/12/2024 09:15</p>
                </div>
                <div class="flex gap-2">
                    <button class="text-blue-600 hover:text-blue-800"><i class="fas fa-download"></i></button>
                    <button class="text-red-600 hover:text-red-800"><i class="fas fa-trash"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function exportReport() {
        alert('Đang xuất báo cáo...');
    }

    function loadReport(type) {
        const detailDiv = document.getElementById('reportDetail');
        const reports = {
            revenue: `
                <div class="p-6">
                    <h3 class="font-bold text-lg mb-4">Báo cáo Doanh thu Quý 4/2024</h3>
                    <canvas id="revenueChart" height="200"></canvas>
                    <div class="mt-4 grid grid-cols-3 gap-4 text-center">
                        <div class="p-3 bg-green-50 rounded"><div class="text-2xl font-bold text-green-600">$245K</div><div class="text-xs">Tháng 10</div></div>
                        <div class="p-3 bg-blue-50 rounded"><div class="text-2xl font-bold text-blue-600">$268K</div><div class="text-xs">Tháng 11</div></div>
                        <div class="p-3 bg-purple-50 rounded"><div class="text-2xl font-bold text-purple-600">$312K</div><div class="text-xs">Tháng 12</div></div>
                    </div>
                </div>
            `,
            hr: `<div class="p-6"><h3 class="font-bold">Báo cáo Nhân sự</h3><p class="text-gray-600">Tổng nhân viên: 156<br>Mới tuyển: 12<br>Nghỉ việc: 3</p></div>`,
            project: `<div class="p-6"><h3 class="font-bold">Báo cáo Dự án</h3><p class="text-gray-600">Đang thực hiện: 8 dự án<br>Hoàn thành: 15 dự án<br>Quá hạn: 2 dự án</p></div>`,
            system: `<div class="p-6"><h3 class="font-bold">Báo cáo Hệ thống</h3><p class="text-gray-600">Uptime: 99.95%<br>Response time: 245ms<br>Error rate: 0.02%</p></div>`
        };
        detailDiv.innerHTML = reports[type] || '<div class="p-6"><p>Không có dữ liệu</p></div>';
        
        if (type === 'revenue') {
            setTimeout(() => {
                const ctx = document.getElementById('revenueChart')?.getContext('2d');
                if (ctx) {
                    new Chart(ctx, {
                        type: 'bar',
                        data: { labels: ['T10', 'T11', 'T12'], datasets: [{ label: 'Doanh thu ($K)', data: [245, 268, 312], backgroundColor: '#3b82f6' }] }
                    });
                }
            }, 100);
        }
    }
</script>
@endsection