@extends('layouts.app')

@section('title', 'V5 - Government Edition | Giải pháp cho Chính phủ')

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
                <span class="text-red-600 font-medium">Government Edition</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="space-y-6">
    
    <!-- Version Banner -->
    <div class="bg-gradient-to-r from-red-800 to-red-900 rounded-2xl p-8 text-white shadow-xl">
        <div class="flex items-center justify-between">
            <div>
                <div class="inline-block bg-white/20 backdrop-blur-sm rounded-lg px-3 py-1 text-sm mb-3">
                    <i class="fas fa-landmark mr-1"></i> Phiên bản 5.0 - Government
                </div>
                <h1 class="text-3xl font-bold mb-2">Giải pháp cho Khu vực công & Chính phủ</h1>
                <p class="text-red-100 max-w-2xl">Bảo mật tuyệt đối, tuân thủ quy định nhà nước, quản lý hồ sơ công dân tập trung</p>
            </div>
            <div class="hidden lg:block">
                <i class="fas fa-gavel text-7xl text-white/20"></i>
            </div>
        </div>
    </div>
    
    <!-- Government Security Levels -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow p-6 text-center border-t-4 border-red-600">
            <i class="fas fa-shield-haltered text-red-600 text-3xl mb-2"></i>
            <h3 class="font-bold">Mật độ: Tối mật</h3>
            <p class="text-xs text-gray-500">Top Secret Clearance</p>
        </div>
        <div class="bg-white rounded-xl shadow p-6 text-center border-t-4 border-blue-600">
            <i class="fas fa-database text-blue-600 text-3xl mb-2"></i>
            <h3 class="font-bold">Data Localization</h3>
            <p class="text-xs text-gray-500">Dữ liệu lưu trữ trong nước</p>
        </div>
        <div class="bg-white rounded-xl shadow p-6 text-center border-t-4 border-green-600">
            <i class="fas fa-clipboard-check text-green-600 text-3xl mb-2"></i>
            <h3 class="font-bold">Gov Cloud</h3>
            <p class="text-xs text-gray-500">TCVN 11930:2018</p>
        </div>
    </div>
    
    <!-- Government Modules -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b">
            <h3 class="font-semibold">Modules đặc thù</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded">
                    <i class="fas fa-id-card text-red-600 text-xl"></i>
                    <div>
                        <p class="font-medium">Quản lý hộ tịch</p>
                        <p class="text-xs text-gray-500">Đăng ký khai sinh, kết hôn, khai tử</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded">
                    <i class="fas fa-file-signature text-red-600 text-xl"></i>
                    <div>
                        <p class="font-medium">Chữ ký số tập trung</p>
                        <p class="text-xs text-gray-500">PKI Integration</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded">
                    <i class="fas fa-chalkboard text-red-600 text-xl"></i>
                    <div>
                        <p class="font-medium">Văn bản điều hành</p>
                        <p class="text-xs text-gray-500">Quản lý công văn, quyết định</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded">
                    <i class="fas fa-hand-holding-usd text-red-600 text-xl"></i>
                    <div>
                        <p class="font-medium">Ngân sách nhà nước</p>
                        <p class="text-xs text-gray-500">Theo dõi thu chi ngân sách</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Certification -->
    <div class="bg-gradient-to-r from-red-50 to-red-100 rounded-xl p-6 border border-red-200">
        <div class="flex items-center gap-4">
            <i class="fas fa-certificate text-red-600 text-4xl"></i>
            <div>
                <h3 class="font-bold text-lg">Chứng nhận an toàn thông tin cấp độ 5</h3>
                <p class="text-sm text-gray-600">Đã được Bộ Thông tin và Truyền thông cấp phép</p>
            </div>
        </div>
    </div>
</div>
@endsection