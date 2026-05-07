@extends('layouts.app')

@section('title', 'Đăng ký - WebApp')

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
            <div class="mx-auto w-16 h-16 bg-gradient-to-r from-green-500 to-teal-600 rounded-2xl flex items-center justify-center shadow-lg">
                <i class="fas fa-user-plus text-white text-2xl"></i>
            </div>
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                Tạo tài khoản mới
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Đăng ký để bắt đầu sử dụng dịch vụ
            </p>
        </div>
        
        <!-- Register Form -->
        <form class="mt-8 space-y-5" action="{{ url('/register') }}" method="POST">
            @csrf
            
            <!-- Full Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-user mr-2 text-gray-400"></i>
                    Họ và tên
                </label>
                <input id="name" name="name" type="text" required 
                       value="{{ old('name') }}"
                       class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm @error('name') border-red-500 @enderror"
                       placeholder="Nguyễn Văn A">
                @error('name')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-envelope mr-2 text-gray-400"></i>
                    Địa chỉ email
                </label>
                <input id="email" name="email" type="email" required 
                       value="{{ old('email') }}"
                       class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm @error('email') border-red-500 @enderror"
                       placeholder="example@email.com">
                @error('email')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Phone Number -->
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-phone mr-2 text-gray-400"></i>
                    Số điện thoại
                </label>
                <input id="phone" name="phone" type="tel" 
                       value="{{ old('phone') }}"
                       class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                       placeholder="0912 345 678">
            </div>
            
            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-lock mr-2 text-gray-400"></i>
                    Mật khẩu
                </label>
                <div class="relative">
                    <input id="password" name="password" type="password" required
                           class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                           placeholder="••••••••">
                    <button type="button" onclick="togglePassword('password', 'toggleIcon1')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <i id="toggleIcon1" class="fas fa-eye"></i>
                    </button>
                </div>
                <p class="mt-1 text-xs text-gray-500">Mật khẩu phải có ít nhất 8 ký tự</p>
                @error('password')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-check-circle mr-2 text-gray-400"></i>
                    Xác nhận mật khẩu
                </label>
                <div class="relative">
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                           class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                           placeholder="••••••••">
                    <button type="button" onclick="togglePassword('password_confirmation', 'toggleIcon2')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <i id="toggleIcon2" class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
            
            <!-- Terms & Conditions -->
            <div class="flex items-center">
                <input id="terms" name="terms" type="checkbox" required
                       class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                <label for="terms" class="ml-2 block text-sm text-gray-900">
                    Tôi đồng ý với 
                    <a href="{{ url('/terms') }}" class="text-green-600 hover:text-green-500">Điều khoản dịch vụ</a>
                    và 
                    <a href="{{ url('/privacy') }}" class="text-green-600 hover:text-green-500">Chính sách bảo mật</a>
                </label>
            </div>
            
            <!-- Submit Button -->
            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-green-600 to-teal-600 hover:from-green-700 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all transform hover:scale-[1.02]">
                    <i class="fas fa-user-plus mr-2"></i>
                    Đăng ký
                </button>
            </div>
        </form>
        
        <!-- Login Link -->
        <div class="text-center mt-4">
            <p class="text-sm text-gray-600">
                Đã có tài khoản?
                <a href="{{ url('/login') }}" class="font-medium text-green-600 hover:text-green-500 transition">
                    Đăng nhập ngay
                </a>
            </p>
        </div>
    </div>
</div>

<script>
    function togglePassword(fieldId, iconId) {
        const passwordInput = document.getElementById(fieldId);
        const toggleIcon = document.getElementById(iconId);
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }
</script>
@endsection