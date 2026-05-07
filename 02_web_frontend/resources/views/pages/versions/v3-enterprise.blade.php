@extends('layouts.app')

@section('title', 'V3 - Enterprise Edition | Giải pháp cho tập đoàn')

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
                <span class="text-orange-600 font-medium">Enterprise Edition</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="space-y-6">
    
    <!-- Version Banner -->
    <div class="bg-gradient-to-r from-orange-600 to-red-700 rounded-2xl p-8 text-white shadow-xl">
        <div class="flex items-center justify-between">
            <div>
                <div class="inline-block bg-white/20 backdrop-blur-sm rounded-lg px-3 py-1 text-sm mb-3">
                    <i class="fas fa-building mr-1"></i> Phiên bản 3.0 - Enterprise
                </div>
                <h1 class="text-3xl font-bold mb-2">Giải pháp cho Tập đoàn & Doanh nghiệp lớn</h1>
                <p class="text-orange-100 max-w-2xl">Quản lịch tập trung, bảo mật cao cấp, SLA 99.99% cho doanh nghiệp 1000+ nhân sự</p>
            </div>
            <div class="hidden lg:block">
                <i class="fas fa-city text-7xl text-white/20"></i>
            </div>
        </div>
    </div>
    
    <!-- Enterprise Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow p-6 text-center">
            <div class="text-3xl font-bold text-orange-600">99.99%</div>
            <div class="text-sm text-gray-500">Uptime SLA</div>
        </div>
        <div class="bg-white rounded-xl shadow p-6 text-center">
            <div class="text-3xl font-bold text-orange-600">24/7</div>
            <div class="text-sm text-gray-500">Support Priority</div>
        </div>
        <div class="bg-white rounded-xl shadow p-6 text-center">
            <div class="text-3xl font-bold text-orange-600">ISO 27001</div>
            <div class="text-sm text-gray-500">Certified</div>
        </div>
        <div class="bg-white rounded-xl shadow p-6 text-center">
            <div class="text-3xl font-bold text-orange-600">On-Premise</div>
            <div class="text-sm text-gray-500">Deployment</div>
        </div>
    </div>
    
    <!-- Enterprise Features -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="text-lg font-bold mb-4"><i class="fas fa-shield-alt text-orange-500 mr-2"></i> Bảo mật cao cấp</h3>
            <ul class="space-y-2">
                <li class="flex items-center gap-2"><i class="fas fa-check-circle text-green-500"></i> Mã hóa dữ liệu AES-256</li>
                <li class="flex items-center gap-2"><i class="fas fa-check-circle text-green-500"></i> Multi-factor Authentication</li>
                <li class="flex items-center gap-2"><i class="fas fa-check-circle text-green-500"></i> RBAC nâng cao</li>
                <li class="flex items-center gap-2"><i class="fas fa-check-circle text-green-500"></i> Audit logs chi tiết</li>
            </ul>
        </div>
        
        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="text-lg font-bold mb-4"><i class="fas fa-cloud-upload-alt text-orange-500 mr-2"></i> Triển khai linh hoạt</h3>
            <ul class="space-y-2">
                <li class="flex items-center gap-2"><i class="fas fa-check-circle text-green-500"></i> On-premise / Cloud Hybrid</li>
                <li class="flex items-center gap-2"><i class="fas fa-check-circle text-green-500"></i> Kubernetes support</li>
                <li class="flex items-center gap-2"><i class="fas fa-check-circle text-green-500"></i> Multi-region backup</li>
                <li class="flex items-center gap-2"><i class="fas fa-check-circle text-green-500"></i> Disaster Recovery</li>
            </ul>
        </div>
    </div>
    
    <!-- Contact Sales -->
    <div class="bg-gray-900 rounded-xl p-8 text-white text-center">
        <h3 class="text-2xl font-bold mb-2">Enterprise Solution</h3>
        <p class="text-gray-300 mb-6">Giải pháp tùy chỉnh theo nhu cầu doanh nghiệp</p>
        <button class="px-8 py-3 bg-orange-600 rounded-lg hover:bg-orange-700 font-semibold">
            <i class="fas fa-envelope mr-2"></i> Liên hệ bộ phận kinh doanh
        </button>
    </div>
</div>
@endsection