@extends('layouts.app')

@section('title', 'V1 - SME Edition | Giải pháp cho doanh nghiệp vừa và nhỏ')

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
                <span class="text-gray-500">Phiên bản</span>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <i class="fas fa-chevron-right text-gray-400 text-sm mx-2"></i>
                <span class="text-blue-600 font-medium">SME Edition</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="space-y-6">
    
    <!-- Version Banner -->
    <div class="bg-gradient-to-r from-blue-500 to-cyan-600 rounded-2xl p-8 text-white shadow-xl">
        <div class="flex items-center justify-between">
            <div>
                <div class="inline-block bg-white/20 backdrop-blur-sm rounded-lg px-3 py-1 text-sm mb-3">
                    <i class="fas fa-rocket mr-1"></i> Phiên bản 1.0 - SME
                </div>
                <h1 class="text-3xl font-bold mb-2">Giải pháp cho Doanh nghiệp vừa và nhỏ</h1>
                <p class="text-cyan-100 max-w-2xl">Quản lý toàn diện, chi phí tối ưu, dễ dàng triển khai cho doanh nghiệp từ 10-200 nhân sự</p>
            </div>
            <div class="hidden lg:block">
                <i class="fas fa-building text-7xl text-white/20"></i>
            </div>
        </div>
    </div>
    
    <!-- Key Features -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                <i class="fas fa-chart-line text-blue-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-semibold mb-2">Quản lý bán hàng</h3>
            <p class="text-gray-600 text-sm">Theo dõi đơn hàng, quản lý khách hàng, báo cáo doanh thu tự động</p>
            <div class="mt-3">
                <span class="text-xs bg-green-100 text-green-600 px-2 py-1 rounded">Included</span>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                <i class="fas fa-boxes text-green-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-semibold mb-2">Quản lý kho</h3>
            <p class="text-gray-600 text-sm">Theo dõi tồn kho, nhập xuất, cảnh báo hàng hết</p>
            <div class="mt-3">
                <span class="text-xs bg-green-100 text-green-600 px-2 py-1 rounded">Included</span>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                <i class="fas fa-users text-purple-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-semibold mb-2">Quản lý nhân sự</h3>
            <p class="text-gray-600 text-sm">Chấm công, tính lương, quản lý hồ sơ nhân viên</p>
            <div class="mt-3">
                <span class="text-xs bg-green-100 text-green-600 px-2 py-1 rounded">Included</span>
            </div>
        </div>
    </div>
    
    <!-- Pricing Section -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Bảng giá SME Edition</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="border rounded-xl p-4 text-center hover:border-blue-500 transition">
                <h4 class="font-bold text-lg">Khởi nghiệp</h4>
                <div class="text-3xl font-bold text-blue-600 my-2">$49<span class="text-sm text-gray-500">/tháng</span></div>
                <p class="text-sm text-gray-500">Tối đa 10 người dùng</p>
                <button class="mt-4 w-full py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Chọn gói</button>
            </div>
            <div class="border-2 border-blue-500 rounded-xl p-4 text-center relative">
                <div class="absolute -top-3 left-1/2 transform -translate-x-1/2 bg-blue-500 text-white px-3 py-0.5 rounded-full text-xs">Phổ biến</div>
                <h4 class="font-bold text-lg">Doanh nghiệp</h4>
                <div class="text-3xl font-bold text-blue-600 my-2">$99<span class="text-sm text-gray-500">/tháng</span></div>
                <p class="text-sm text-gray-500">Tối đa 50 người dùng</p>
                <button class="mt-4 w-full py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Chọn gói</button>
            </div>
            <div class="border rounded-xl p-4 text-center hover:border-blue-500 transition">
                <h4 class="font-bold text-lg">Chuyên nghiệp</h4>
                <div class="text-3xl font-bold text-blue-600 my-2">$199<span class="text-sm text-gray-500">/tháng</span></div>
                <p class="text-sm text-gray-500">Không giới hạn người dùng</p>
                <button class="mt-4 w-full py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Chọn gói</button>
            </div>
        </div>
    </div>
    
    <!-- Modules -->
    <div class="bg-gray-50 rounded-xl p-6">
        <h3 class="text-lg font-semibold mb-4">Các module có sẵn</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <div class="flex items-center gap-2">
                <i class="fas fa-check-circle text-green-500"></i>
                <span>Đơn hàng</span>
            </div>
            <div class="flex items-center gap-2">
                <i class="fas fa-check-circle text-green-500"></i>
                <span>Khách hàng</span>
            </div>
            <div class="flex items-center gap-2">
                <i class="fas fa-check-circle text-green-500"></i>
                <span>Sản phẩm</span>
            </div>
            <div class="flex items-center gap-2">
                <i class="fas fa-check-circle text-green-500"></i>
                <span>Báo cáo</span>
            </div>
        </div>
    </div>
</div>
@endsection