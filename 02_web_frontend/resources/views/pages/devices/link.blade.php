@extends('layouts.app')

@section('title', 'Liên kết thiết bị mới')

@section('content')
<div class="min-h-screen bg-gray-100 flex items-center justify-center py-12">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl p-8">
        <div class="text-center">
            <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-qrcode text-blue-600 text-3xl"></i>
            </div>
            <h2 class="text-2xl font-bold mb-2">Liên kết thiết bị mới</h2>
            <p class="text-gray-600 mb-6">Quét mã QR bằng ứng dụng xác thực để liên kết</p>
        </div>

        <!-- QR Code -->
        <div class="flex justify-center mb-6">
            <div class="w-48 h-48 bg-gray-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-qrcode text-6xl text-gray-400"></i>
                <!-- Thay bằng QR code thực tế -->
            </div>
        </div>

        <!-- Mã thủ công -->
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <p class="text-xs text-gray-500 text-center mb-2">Hoặc nhập mã thủ công</p>
            <div class="text-center font-mono text-lg tracking-wider">ABC1D-EF2GH-IJ3KL-MN4OP</div>
        </div>

        <button onclick="window.location.href='{{ url('/devices') }}'" class="w-full py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            <i class="fas fa-check mr-2"></i> Hoàn tất
        </button>
    </div>
</div>
@endsection