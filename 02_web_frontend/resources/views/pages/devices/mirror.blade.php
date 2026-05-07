@extends('layouts.app')

@section('title', 'Màn hình nhân bản')

@section('content')
<div class="min-h-screen bg-gray-900 py-12">
    <div class="container mx-auto px-4">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-white mb-2">
                <i class="fas fa-desktop mr-2"></i> Màn hình nhân bản
            </h1>
            <p class="text-gray-400">Chia sẻ màn hình với thiết bị khác trong cùng mạng</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Source Screen -->
            <div class="bg-gray-800 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4">
                    <i class="fas fa-play-circle text-green-500 mr-2"></i> Màn hình nguồn
                </h3>
                <div class="bg-gray-900 rounded-lg aspect-video flex items-center justify-center">
                    <i class="fas fa-desktop text-6xl text-gray-700"></i>
                </div>
                <button id="startMirror" class="mt-4 w-full py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    <i class="fas fa-play mr-2"></i> Bắt đầu chia sẻ
                </button>
            </div>

            <!-- Target Devices -->
            <div class="bg-gray-800 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4">
                    <i class="fas fa-tv text-blue-500 mr-2"></i> Thiết bị đích
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-gray-700 rounded">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-tv text-blue-400"></i>
                            <span>Phòng họp A - TV 65"</span>
                        </div>
                        <button class="px-3 py-1 bg-blue-600 rounded text-sm">Kết nối</button>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-700 rounded">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-laptop text-purple-400"></i>
                            <span>Laptop - VP Marketing</span>
                        </div>
                        <button class="px-3 py-1 bg-blue-600 rounded text-sm">Kết nối</button>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-700 rounded">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-mobile-alt text-green-400"></i>
                            <span>iPhone - Nguyễn Văn A</span>
                        </div>
                        <button class="px-3 py-1 bg-blue-600 rounded text-sm">Kết nối</button>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-700">
                    <div class="flex gap-2">
                        <input type="text" placeholder="Nhập mã thiết bị để kết nối" class="flex-1 bg-gray-700 border-0 rounded px-3 py-2 text-white">
                        <button class="px-4 py-2 bg-blue-600 rounded">Kết nối</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 bg-yellow-900/30 border border-yellow-700 rounded-lg p-4 text-yellow-300 text-sm">
            <i class="fas fa-info-circle mr-2"></i>
            Lưu ý: Các thiết bị cần kết nối cùng mạng Wi-Fi để sử dụng tính năng nhân bản màn hình.
        </div>
    </div>
</div>

<script>
    document.getElementById('startMirror')?.addEventListener('click', function() {
        alert('Đang bắt đầu chia sẻ màn hình...');
    });
</script>
@endsection