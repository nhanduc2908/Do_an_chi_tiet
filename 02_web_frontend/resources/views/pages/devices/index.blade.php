@extends('layouts.app')

@section('title', 'Quản lý thiết bị')

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
                <span class="text-gray-500">Thiết bị</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="space-y-6">
    
    <!-- Header -->
    <div class="bg-gradient-to-r from-cyan-600 to-blue-700 rounded-2xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold mb-2">
                    <i class="fas fa-mobile-alt mr-2"></i>
                    Quản lý thiết bị
                </h1>
                <p class="text-cyan-100">Quản lý thiết bị đã đăng nhập và phiên hoạt động</p>
            </div>
            <div class="text-right">
                <div class="text-2xl font-bold">8</div>
                <div class="text-cyan-100">Thiết bị đang hoạt động</div>
            </div>
        </div>
    </div>

    <!-- Danh sách thiết bị -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50">
            <h3 class="font-semibold"><i class="fas fa-laptop text-blue-500 mr-2"></i> Thiết bị đã đăng nhập</h3>
        </div>
        <div class="divide-y">
            <div class="flex items-center justify-between p-4 hover:bg-gray-50">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-laptop text-blue-600"></i>
                    </div>
                    <div>
                        <p class="font-medium">MacBook Pro - Chrome</p>
                        <p class="text-xs text-gray-500">Đăng nhập lần cuối: Hôm nay 10:30</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-xs bg-green-100 text-green-600 px-2 py-1 rounded">Đang hoạt động</span>
                    <button class="ml-3 text-red-500 hover:text-red-700"><i class="fas fa-sign-out-alt"></i></button>
                </div>
            </div>
            <div class="flex items-center justify-between p-4 hover:bg-gray-50">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-mobile-alt text-gray-600"></i>
                    </div>
                    <div>
                        <p class="font-medium">iPhone 15 - Safari</p>
                        <p class="text-xs text-gray-500">Đăng nhập lần cuối: Hôm qua 21:15</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">Không hoạt động</span>
                    <button class="ml-3 text-red-500 hover:text-red-700"><i class="fas fa-sign-out-alt"></i></button>
                </div>
            </div>
            <div class="flex items-center justify-between p-4 hover:bg-gray-50">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fab fa-windows text-purple-600"></i>
                    </div>
                    <div>
                        <p class="font-medium">Dell XPS - Edge</p>
                        <p class="text-xs text-gray-500">Đăng nhập lần cuối: 14/12/2024 08:00</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">Không hoạt động</span>
                    <button class="ml-3 text-red-500 hover:text-red-700"><i class="fas fa-sign-out-alt"></i></button>
                </div>
            </div>
        </div>
    </div>

    <!-- Nút hành động -->
    <div class="flex gap-4 justify-end">
        <a href="{{ url('/devices/link') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            <i class="fas fa-link mr-2"></i> Liên kết thiết bị mới
        </a>
        <a href="{{ url('/devices/mirror') }}" class="px-4 py-2 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50">
            <i class="fas fa-desktop mr-2"></i> Màn hình nhân bản
        </a>
    </div>
</div>
@endsection