@extends('layouts.app')

@section('title', 'V2 - MidMarket Edition | Giải pháp cho doanh nghiệp tầm trung')

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
                <span class="text-purple-600 font-medium">MidMarket Edition</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="space-y-6">
    
    <!-- Version Banner -->
    <div class="bg-gradient-to-r from-purple-600 to-indigo-700 rounded-2xl p-8 text-white shadow-xl">
        <div class="flex items-center justify-between">
            <div>
                <div class="inline-block bg-white/20 backdrop-blur-sm rounded-lg px-3 py-1 text-sm mb-3">
                    <i class="fas fa-chart-line mr-1"></i> Phiên bản 2.0 - MidMarket
                </div>
                <h1 class="text-3xl font-bold mb-2">Giải pháp cho Doanh nghiệp tầm trung</h1>
                <p class="text-purple-100 max-w-2xl">Quản lý đa chi nhánh, tích hợp đa kênh, phân tích nâng cao cho doanh nghiệp 200-1000 nhân sự</p>
            </div>
            <div class="hidden lg:block">
                <i class="fas fa-chart-pie text-7xl text-white/20"></i>
            </div>
        </div>
    </div>
    
    <!-- Key Features -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                <i class="fas fa-store text-purple-600 text-xl"></i>
            </div>
            <h3 class="font-semibold mb-2">Đa chi nhánh</h3>
            <p class="text-sm text-gray-600">Quản lý nhiều chi nhánh, kho hàng</p>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
                <i class="fas fa-chart-simple text-indigo-600 text-xl"></i>
            </div>
            <h3 class="font-semibold mb-2">BI & Analytics</h3>
            <p class="text-sm text-gray-600">Dashboard thông minh, dự báo</p>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="w-12 h-12 bg-pink-100 rounded-lg flex items-center justify-center mb-4">
                <i class="fas fa-plug text-pink-600 text-xl"></i>
            </div>
            <h3 class="font-semibold mb-2">API Integration</h3>
            <p class="text-sm text-gray-600">Kết nối với hệ thống khác</p>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center mb-4">
                <i class="fas fa-clock text-teal-600 text-xl"></i>
            </div>
            <h3 class="font-semibold mb-2">Real-time Sync</h3>
            <p class="text-sm text-gray-600">Đồng bộ thời gian thực</p>
        </div>
    </div>
    
    <!-- Advanced Features Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b">
            <h3 class="font-semibold text-lg">So sánh tính năng nâng cao</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm">Tính năng</th>
                        <th class="px-6 py-3 text-center text-sm">SME</th>
                        <th class="px-6 py-3 text-center text-sm bg-purple-50">MidMarket</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    <tr>
                        <td class="px-6 py-3 text-sm">Quản lý đa chi nhánh</td>
                        <td class="px-6 py-3 text-center"><i class="fas fa-times text-red-500"></i></td>
                        <td class="px-6 py-3 text-center bg-purple-50"><i class="fas fa-check text-green-500"></i></td>
                    </tr>
                    <tr>
                        <td class="px-6 py-3 text-sm">API Public</td>
                        <td class="px-6 py-3 text-center"><i class="fas fa-times text-red-500"></i></td>
                        <td class="px-6 py-3 text-center bg-purple-50"><i class="fas fa-check text-green-500"></i></td>
                    </tr>
                    <tr>
                        <td class="px-6 py-3 text-sm">SSO Integration</td>
                        <td class="px-6 py-3 text-center"><i class="fas fa-times text-red-500"></i></td>
                        <td class="px-6 py-3 text-center bg-purple-50"><i class="fas fa-check text-green-500"></i></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Pricing -->
    <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-xl p-6">
        <div class="text-center mb-6">
            <h3 class="text-2xl font-bold text-gray-800">Pricing MidMarket</h3>
            <p class="text-gray-600">Liên hệ để được tư vấn giải pháp phù hợp</p>
        </div>
        <div class="flex justify-center">
            <button class="px-8 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 font-semibold">
                <i class="fas fa-phone-alt mr-2"></i> Liên hệ ngay
            </button>
        </div>
    </div>
</div>
@endsection