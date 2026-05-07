@extends('layouts.app')

@section('title', 'Xác thực OTP - WebApp')

@section('styles')
<style>
    .auth-container {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .otp-input {
        width: 60px;
        height: 60px;
        text-align: center;
        font-size: 24px;
        font-weight: bold;
        border-radius: 12px;
    }
</style>
@endsection

@section('content')
<div class="auth-container min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white rounded-2xl shadow-2xl p-8">
        
        <!-- Header -->
        <div class="text-center">
            <div class="mx-auto w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg">
                <i class="fas fa-shield-alt text-white text-2xl"></i>
            </div>
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                Xác thực OTP
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Nhập mã OTP đã được gửi đến email của bạn
            </p>
            <p class="text-xs text-gray-500 mt-1">
                <i class="fas fa-envelope mr-1"></i>
                {{ session('reset_email') ?? 'user@example.com' }}
            </p>
        </div>
        
        <!-- Timer -->
        <div class="text-center">
            <div class="inline-flex items-center gap-2 bg-gray-100 px-4 py-2 rounded-full">
                <i class="fas fa-clock text-blue-500"></i>
                <span class="text-sm font-medium">Mã OTP có hiệu lực trong:</span>
                <span id="timer" class="text-lg font-bold text-red-500">05:00</span>
            </div>
        </div>
        
        <!-- OTP Form -->
        <form class="mt-8 space-y-6" action="{{ url('/verify-otp') }}" method="POST" id="otpForm">
            @csrf
            
            <!-- OTP Input Fields -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-4 text-center">
                    Nhập mã OTP 6 chữ số
                </label>
                <div class="flex justify-center gap-3">
                    <input type="text" maxlength="1" class="otp-input border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 text-center" id="otp1" name="otp1" required>
                    <input type="text" maxlength="1" class="otp-input border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 text-center" id="otp2" name="otp2" required>
                    <input type="text" maxlength="1" class="otp-input border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 text-center" id="otp3" name="otp3" required>
                    <input type="text" maxlength="1" class="otp-input border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 text-center" id="otp4" name="otp4" required>
                    <input type="text" maxlength="1" class="otp-input border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 text-center" id="otp5" name="otp5" required>
                    <input type="text" maxlength="1" class="otp-input border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 text-center" id="otp6" name="otp6" required>
                </div>
                <input type="hidden" name="otp" id="otp">
                @error('otp')
                    <p class="mt-2 text-center text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Resend OTP -->
            <div class="text-center">
                <button type="button" id="resendBtn" onclick="resendOTP()" 
                        class="text-sm text-purple-600 hover:text-purple-500 transition disabled:opacity-50 disabled:cursor-not-allowed"
                        disabled>
                    <i class="fas fa-redo mr-1"></i>
                    Gửi lại mã OTP
                </button>
            </div>
            
            <!-- Submit Button -->
            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all transform hover:scale-[1.02]">
                    <i class="fas fa-check-circle mr-2"></i>
                    Xác thực OTP
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Auto focus OTP inputs
    const otpInputs = document.querySelectorAll('.otp-input');
    otpInputs.forEach((input, index) => {
        input.addEventListener('keyup', (e) => {
            if (e.target.value.length === 1 && index < otpInputs.length - 1) {
                otpInputs[index + 1].focus();
            }
            if (e.key === 'Backspace' && index > 0 && !e.target.value) {
                otpInputs[index - 1].focus();
            }
            updateOTP();
        });
    });
    
    function updateOTP() {
        let otp = '';
        otpInputs.forEach(input => {
            otp += input.value;
        });
        document.getElementById('otp').value = otp;
    }
    
    // Timer functionality
    let timeLeft = 300; // 5 minutes
    const timerDisplay = document.getElementById('timer');
    const resendBtn = document.getElementById('resendBtn');
    
    const timerInterval = setInterval(() => {
        if (timeLeft <= 0) {
            clearInterval(timerInterval);
            timerDisplay.textContent = '00:00';
            resendBtn.disabled = false;
        } else {
            timeLeft--;
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            timerDisplay.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        }
    }, 1000);
    
    function resendOTP() {
        fetch('{{ url("/resend-otp") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                email: '{{ session("reset_email") }}'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Mã OTP mới đã được gửi đến email của bạn');
                timeLeft = 300;
                resendBtn.disabled = true;
            } else {
                alert('Có lỗi xảy ra, vui lòng thử lại sau');
            }
        });
    }
</script>
@endsection