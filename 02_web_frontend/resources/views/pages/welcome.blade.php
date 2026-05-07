@extends('layouts.app')

@section('title', 'Chào mừng - Hệ thống quản lý doanh nghiệp')

@section('styles')
<style>
    .welcome-bg {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
</style>
@endsection

@section('content')
<div class="min-h-screen welcome-bg">
    <div class="container mx-auto px-4 py-16">
        <div class="text-center text-white">
            <!-- Logo -->
            <div class="inline-block p-4 bg-white/10 backdrop-blur rounded-2xl mb-8">
                <i class="fas fa-code text-6xl"></i>
            </div>
            
            <h1 class="text-5xl md:text-6xl font-bold mb-4">Chào mừng đến với WebApp</h1>
            <p class="text-xl md:text-2xl text-white/80 max-w-2xl mx-auto mb-8">
                Giải pháp quản lý doanh nghiệp toàn diện, thông minh và hiệu quả
            </p>
            
            <div class="flex gap-4 justify-center">
                <a href="{{ url('/login') }}" class="px-8 py-3 bg-white text-purple-600 rounded-lg font-semibold hover:bg-gray-100 transition">
                    <i class="fas fa-sign-in-alt mr-2"></i> Đăng nhập
                </a>
                <a href="{{ url('/register') }}" class="px-8 py-3 border-2 border-white text-white rounded-lg font-semibold hover:bg-white/10 transition">
                    <i class="fas fa-user-plus mr-2"></i> Đăng ký
                </a>
            </div>
        </div>

        <!-- Tính năng nổi bật -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-20">
            <div class="bg-white/10 backdrop-blur rounded-xl p-6 text-white text-center">
                <i class="fas fa-chart-line text-4xl mb-3"></i>
                <h3 class="text-xl font-semibold mb-2">Phân tích thông minh</h3>
                <p class="text-white/70">Báo cáo trực quan, dự báo chính xác</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-6 text-white text-center">
                <i class="fas fa-shield-alt text-4xl mb-3"></i>
                <h3 class="text-xl font-semibold mb-2">Bảo mật cao cấp</h3>
                <p class="text-white/70">Mã hóa dữ liệu, xác thực 2 lớp</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-6 text-white text-center">
                <i class="fas fa-cloud-upload-alt text-4xl mb-3"></i>
                <h3 class="text-xl font-semibold mb-2">Đa nền tảng</h3>
                <p class="text-white/70">Truy cập mọi lúc, mọi nơi</p>
            </div>
        </div>
    </div>
</div>
@endsection