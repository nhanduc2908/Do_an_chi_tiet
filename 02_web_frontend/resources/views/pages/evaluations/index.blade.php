@extends('layouts.app')

@section('title', 'Đánh giá & Thẩm định - Hệ thống đánh giá toàn diện')

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
                <span class="text-gray-500">Đánh giá</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="space-y-6">
    
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold mb-2">
                    <i class="fas fa-star-of-life mr-2"></i>
                    Hệ thống Đánh giá & Thẩm định
                </h1>
                <p class="text-blue-100">Đánh giá năng lực, hiệu suất và chất lượng công việc</p>
            </div>
            <div class="text-right">
                <div class="text-2xl font-bold">{{ date('m/Y') }}</div>
                <div class="text-blue-100">Kỳ đánh giá Q{{ ceil(date('n')/3) }}</div>
            </div>
        </div>
    </div>

    <!-- Thống kê đánh giá -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Tổng số đánh giá</p>
                    <p class="text-2xl font-bold">156</p>
                </div>
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-chart-simple text-blue-600"></i>
                </div>
            </div>
            <div class="mt-2 text-xs text-green-500">↑ 12% so với tháng trước</div>
        </div>

        <div class="bg-white rounded-xl shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Hoàn thành</p>
                    <p class="text-2xl font-bold">128</p>
                </div>
                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
            </div>
            <div class="mt-2 text-xs text-green-500">82% hoàn thành</div>
        </div>

        <div class="bg-white rounded-xl shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Đang chờ</p>
                    <p class="text-2xl font-bold">23</p>
                </div>
                <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600"></i>
                </div>
            </div>
            <div class="mt-2 text-xs text-yellow-500">Cần duyệt</div>
        </div>

        <div class="bg-white rounded-xl shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Điểm TB</p>
                    <p class="text-2xl font-bold">8.4</p>
                </div>
                <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-medal text-purple-600"></i>
                </div>
            </div>
            <div class="mt-2 text-xs text-purple-500">/10 điểm</div>
        </div>
    </div>

    <!-- Các loại đánh giá -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Đánh giá nhân viên -->
        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="text-lg font-semibold mb-4">
                <i class="fas fa-user-check text-blue-500 mr-2"></i>
                Đánh giá nhân viên (KPI)
            </h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                    <div>
                        <p class="font-medium">Nguyễn Văn A</p>
                        <p class="text-xs text-gray-500">Nhân viên kinh doanh</p>
                    </div>
                    <div class="text-right">
                        <div class="font-bold text-green-600">9.2/10</div>
                        <div class="text-xs text-gray-500">Xuất sắc</div>
                    </div>
                    <button class="px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                    <div>
                        <p class="font-medium">Trần Thị B</p>
                        <p class="text-xs text-gray-500">Kế toán</p>
                    </div>
                    <div class="text-right">
                        <div class="font-bold text-blue-600">8.5/10</div>
                        <div class="text-xs text-gray-500">Tốt</div>
                    </div>
                    <button class="px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
            <button class="mt-4 w-full py-2 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50">
                <i class="fas fa-plus mr-2"></i> Tạo đánh giá mới
            </button>
        </div>

        <!-- Đánh giá dự án -->
        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="text-lg font-semibold mb-4">
                <i class="fas fa-project-diagram text-green-500 mr-2"></i>
                Đánh giá dự án
            </h3>
            <div class="space-y-4">
                <div class="p-3 bg-gray-50 rounded">
                    <div class="flex justify-between mb-2">
                        <span class="font-medium">Dự án ERP - Phase 1</span>
                        <span class="text-sm text-green-600">Hoàn thành 95%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full" style="width: 95%"></div>
                    </div>
                    <div class="flex justify-between mt-2 text-xs text-gray-500">
                        <span>Đúng tiến độ</span>
                        <span>Đánh giá: A</span>
                    </div>
                </div>
                <div class="p-3 bg-gray-50 rounded">
                    <div class="flex justify-between mb-2">
                        <span class="font-medium">App Mobile Banking</span>
                        <span class="text-sm text-yellow-600">Chậm 15%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-yellow-600 h-2 rounded-full" style="width: 70%"></div>
                    </div>
                    <div class="flex justify-between mt-2 text-xs text-gray-500">
                        <span>Cần đẩy nhanh</span>
                        <span>Đánh giá: B</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Biểu đồ đánh giá -->
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="text-lg font-semibold mb-4">
            <i class="fas fa-chart-line text-purple-500 mr-2"></i>
            Xu hướng đánh giá theo tháng
        </h3>
        <canvas id="evaluationChart" height="200"></canvas>
    </div>

    <!-- Form tạo đánh giá nhanh -->
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="text-lg font-semibold mb-4">
            <i class="fas fa-pen-alt text-blue-500 mr-2"></i>
            Tạo đánh giá nhanh
        </h3>
        <form action="{{ url('/evaluations/store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Đối tượng đánh giá</label>
                    <select name="target" class="w-full border rounded-lg px-3 py-2">
                        <option>Chọn nhân viên</option>
                        <option>Nguyễn Văn A</option>
                        <option>Trần Thị B</option>
                        <option>Lê Văn C</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Loại đánh giá</label>
                    <select name="type" class="w-full border rounded-lg px-3 py-2">
                        <option>Đánh giá định kỳ</option>
                        <option>Đánh giá đột xuất</option>
                        <option>Đánh giá 360 độ</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Điểm số (1-10)</label>
                    <input type="number" min="1" max="10" step="0.1" name="score" class="w-full border rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Kỳ đánh giá</label>
                    <input type="text" value="Quý {{ ceil(date('n')/3) }}/{{ date('Y') }}" class="w-full border rounded-lg px-3 py-2 bg-gray-50" readonly>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Nhận xét</label>
                <textarea rows="3" name="comment" class="w-full border rounded-lg px-3 py-2" placeholder="Nhập nhận xét chi tiết..."></textarea>
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <i class="fas fa-save mr-2"></i> Lưu đánh giá
            </button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('evaluationChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Thg 1', 'Thg 2', 'Thg 3', 'Thg 4', 'Thg 5', 'Thg 6', 'Thg 7', 'Thg 8', 'Thg 9', 'Thg 10', 'Thg 11', 'Thg 12'],
            datasets: [{
                label: 'Điểm đánh giá TB',
                data: [7.2, 7.5, 7.8, 8.0, 8.2, 8.1, 8.3, 8.5, 8.4, 8.6, 8.7, 8.8],
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.3,
                fill: true
            }]
        }
    });
</script>
@endsection