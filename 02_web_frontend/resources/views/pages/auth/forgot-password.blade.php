@extends('layouts.app')

@section('title', 'Quên mật khẩu - WebApp')

@section('styles')
<style>
    .auth-container {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
</style>
@endsection

@section('content')
<div class="auth-container min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white rounded-2xl shadow-2xl p-8">
        
        <!-- Header -->
        <div class="text-center">
            <div class="mx-auto w-16 h-16 bg-gradient-to-r from-yellow-500 to-orange-600 rounded-2xl flex items-center justify-center shadow-lg">
                <i class="fas fa-key text-white text-2xl"></i>
            </div>
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                Quên mật khẩu?
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Nhập email của bạn để nhận mã xác thực
            </p>
        </div>
        
        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded">
                <div class="flex">
                    <i class="fas fa-check-circle text-green-500 mt-0.5 mr-3"></i>
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded">
                <div class="flex">
                    <i class="fas fa-exclamation-circle text-red-500 mt-0.5 mr-3"></i>
                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                </div>
            </div>
        @endif
        
        <!-- Forgot Password Form -->
        <form class="mt-8 space-y-6" action="{{ url('/forgot-password') }}" method="POST">
            @csrf
            
            <!-- Email Field -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-envelope mr-2 text-gray-400"></i>
                    Địa chỉ email
                </label>
                <input id="email" name="email" type="email" required 
                       value="{{ old('email') }}"
                       class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-yellow-500 focus:border-yellow-500 sm:text-sm @error('email') border-red-500 @enderror"
                       placeholder="admin@example.com">
                @error('email')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Submit Button -->
            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-yellow-600 to-orange-600 hover:from-yellow-700 hover:to-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-all transform hover:scale-[1.02]">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Gửi mã xác thực
                </button>
            </div>
        </form>
        
        <!-- Back to Login -->
        <div class="text-center mt-4">
            <a href="{{ url('/login') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 transition">
                <i class="fas fa-arrow-left mr-2"></i>
                Quay lại đăng nhập
            </a>
        </div>
        
        <!-- Help Info -->
        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
            <div class="flex items-start gap-3">
                <i class="fas fa-question-circle text-gray-400 mt-0.5"></i>
                <div class="text-xs text-gray-600">
                    <p class="font-medium mb-1">Hướng dẫn:</p>
                    <p>1. Nhập email đã đăng ký tài khoản</p>
                    <p>2. Kiểm tra email để nhận mã OTP</p>
                    <p>3. Sử dụng mã OTP để đặt lại mật khẩu</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection