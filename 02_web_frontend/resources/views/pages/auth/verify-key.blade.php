@extends('layouts.app')

@section('title', 'Xác thực bảo mật - WebApp')

@section('styles')
<style>
    .auth-container {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .key-input {
        font-family: monospace;
        letter-spacing: 2px;
    }
</style>
@endsection

@section('content')
<div class="auth-container min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white rounded-2xl shadow-2xl p-8">
        
        <!-- Header -->
        <div class="text-center">
            <div class="mx-auto w-16 h-16 bg-gradient-to-r from-red-500 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg">
                <i class="fas fa-fingerprint text-white text-2xl"></i>
            </div>
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                Xác thực bảo mật
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Nhập mã xác thực bảo mật 2 lớp (2FA)
            </p>
        </div>
        
        <!-- Key Form -->
        <form class="mt-8 space-y-6" action="{{ url('/verify-key') }}" method="POST">
            @csrf
            
            <!-- Security Key Input -->
            <div>
                <label for="security_key" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-key mr-2 text-gray-400"></i>
                    Mã bảo mật
                </label>
                <input id="security_key" name="security_key" type="text" required
                       class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm key-input @error('security_key') border-red-500 @enderror"
                       placeholder="••••• ••••• ••••• •••••"
                       autocomplete="off">
                @error('security_key')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Recovery Options -->
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <a href="#" onclick="useRecoveryCode()" class="text-sm text-red-600 hover:text-red-500 transition">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        Sử dụng mã khôi phục
                    </a>
                    <a href="#" onclick="scanQRCode()" class="text-sm text-gray-600 hover:text-gray-900 transition">
                        <i class="fas fa-qrcode mr-1"></i>
                        Quét mã QR
                    </a>
                </div>
            </div>
            
            <!-- Remember Device -->
            <div class="flex items-center">
                <input id="remember_device" name="remember_device" type="checkbox"
                       class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                <label for="remember_device" class="ml-2 block text-sm text-gray-900">
                    Ghi nhớ thiết bị này trong 30 ngày
                </label>
            </div>
            
            <!-- Submit Button -->
            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all transform hover:scale-[1.02]">
                    <i class="fas fa-shield-alt mr-2"></i>
                    Xác thực
                </button>
            </div>
        </form>
        
        <!-- Setup Instructions -->
        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
            <div class="flex items-start gap-3">
                <i class="fas fa-mobile-alt text-gray-400 text-lg"></i>
                <div class="text-xs text-gray-600">
                    <p class="font-medium mb-1">Hướng dẫn xác thực 2 lớp:</p>
                    <p>• Mở ứng dụng xác thực (Google Authenticator, Microsoft Authenticator)</p>
                    <p>• Nhập mã 6 số hiển thị trên ứng dụng</p>
                    <p>• Mỗi mã có hiệu lực trong 30 giây</p>
                </div>
            </div>
        </div>
        
        <!-- Alternative Methods -->
        <div class="border-t border-gray-200 pt-4">
            <div class="text-center">
                <p class="text-xs text-gray-500 mb-2">Phương thức xác thực khác:</p>
                <div class="flex justify-center gap-4">
                    <button onclick="sendSMSCode()" class="text-xs text-gray-600 hover:text-gray-900 transition">
                        <i class="fas fa-sms mr-1"></i>
                        SMS
                    </button>
                    <button onclick="sendEmailCode()" class="text-xs text-gray-600 hover:text-gray-900 transition">
                        <i class="fas fa-envelope mr-1"></i>
                        Email
                    </button>
                    <button onclick="useBackupCodes()" class="text-xs text-gray-600 hover:text-gray-900 transition">
                        <i class="fas fa-code-branch mr-1"></i>
                        Mã dự phòng
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function useRecoveryCode() {
        window.location.href = "{{ url('/recovery-code') }}";
    }
    
    function scanQRCode() {
        // Open QR scanner modal
        alert('Vui lòng sử dụng ứng dụng xác thực để quét mã QR');
    }
    
    function sendSMSCode() {
        fetch('{{ url("/send-sms-code") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Mã xác thực đã được gửi qua SMS');
            } else {
                alert('Không thể gửi mã SMS, vui lòng thử lại sau');
            }
        });
    }
    
    function sendEmailCode() {
        fetch('{{ url("/send-email-code") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Mã xác thực đã được gửi qua email');
            } else {
                alert('Không thể gửi mã email, vui lòng thử lại sau');
            }
        });
    }
    
    function useBackupCodes() {
        window.location.href = "{{ url('/backup-codes') }}";
    }
    
    // Format security key input
    const keyInput = document.getElementById('security_key');
    if (keyInput) {
        keyInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s/g, '');
            if (value.length > 0) {
                value = value.match(/.{1,5}/g).join(' ');
                e.target.value = value;
            }
        });
    }
</script>
@endsection