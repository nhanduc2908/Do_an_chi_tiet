@extends('layouts.app')

@section('title', 'V4 - Finance Edition | Giải pháp cho ngành Tài chính')

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
                <span class="text-green-600 font-medium">Finance Edition</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="space-y-6">
    
    <!-- Version Banner -->
    <div class="bg-gradient-to-r from-green-700 to-emerald-800 rounded-2xl p-8 text-white shadow-xl">
        <div class="flex items-center justify-between">
            <div>
                <div class="inline-block bg-white/20 backdrop-blur-sm rounded-lg px-3 py-1 text-sm mb-3">
                    <i class="fas fa-coins mr-1"></i> Phiên bản 4.0 - Finance
                </div>
                <h1 class="text-3xl font-bold mb-2">Giải pháp cho Ngành Tài chính - Ngân hàng</h1>
                <p class="text-emerald-100 max-w-2xl">Tuân thủ các quy định khắt khe, bảo mật ngân hàng, giao dịch an toàn</p>
            </div>
            <div class="hidden lg:block">
                <i class="fas fa-chart-line text-7xl text-white/20"></i>
            </div>
        </div>
    </div>
    
    <!-- Compliance Badges -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-white rounded-lg shadow p-3 text-center">
            <i class="fas fa-check-circle text-green-500 text-2xl mb-1"></i>
            <div class="text-xs font-semibold">PCI DSS</div>
        </div>
        <div class="bg-white rounded-lg shadow p-3 text-center">
            <i class="fas fa-check-circle text-green-500 text-2xl mb-1"></i>
            <div class="text-xs font-semibold">GDPR</div>
        </div>
        <div class="bg-white rounded-lg shadow p-3 text-center">
            <i class="fas fa-check-circle text-green-500 text-2xl mb-1"></i>
            <div class="text-xs font-semibold">SOX</div>
        </div>
        <div class="bg-white rounded-lg shadow p-3 text-center">
            <i class="fas fa-check-circle text-green-500 text-2xl mb-1"></i>
            <div class="text-xs font-semibold">Basel III</div>
        </div>
        <div class="bg-white rounded-lg shadow p-3 text-center">
            <i class="fas fa-check-circle text-green-500 text-2xl mb-1"></i>
            <div class="text-xs font-semibold">IFRS 9</div>
        </div>
    </div>
    
    <!-- Finance Features -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow p-6">
            <i class="fas fa-shield-virus text-green-600 text-3xl mb-3"></i>
            <h3 class="font-bold mb-2">Anti-Fraud System</h3>
            <p class="text-sm text-gray-600">Phát hiện giao dịch bất thường theo thời gian thực</p>
        </div>
        <div class="bg-white rounded-xl shadow p-6">
            <i class="fas fa-file-invoice-dollar text-green-600 text-3xl mb-3"></i>
            <h3 class="font-bold mb-2">Automated Reconciliation</h3>
            <p class="text-sm text-gray-600">Đối chiếu giao dịch tự động</p>
        </div>
        <div class="bg-white rounded-xl shadow p-6">
            <i class="fas fa-chart-line text-green-600 text-3xl mb-3"></i>
            <h3 class="font-bold mb-2">Risk Management</h3>
            <p class="text-sm text-gray-600">Quản lý rủi ro tín dụng & thị trường</p>
        </div>
    </div>
    
    <!-- Transaction Volume -->
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="text-lg font-bold mb-4">Transaction Processing Volume</h3>
        <div class="text-center py-6">
            <div class="text-5xl font-bold text-green-600">1.2M</div>
            <div class="text-gray-500">Giao dịch/ngày</div>
            <div class="mt-4 w-full bg-gray-200 rounded-full h-2">
                <div class="bg-green-600 h-2 rounded-full" style="width: 85%"></div>
            </div>
            <div class="flex justify-between text-xs text-gray-500 mt-2">
                <span>Capacity: 1.4M</span>
                <span>85% utilized</span>
            </div>
        </div>
    </div>
</div>
@endsection