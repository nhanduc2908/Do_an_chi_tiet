@extends('layouts.app')

@section('title', 'Dashboard - Tổng quan')

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
                <span class="text-gray-500">Dashboard</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="space-y-6">
    
    <!-- Role-based Dashboard Widgets -->
    @php
        $userRole = Auth::user()->role ?? session('user_role', 'admin');
    @endphp
    
    @switch($userRole)
        @case('admin')
        @case('administrator')
            @include('pages.dashboard.partials.admin-widgets')
            @break
            
        @case('dev')
        @case('developer')
            @include('pages.dashboard.partials.dev-widgets')
            @break
            
        @case('ba')
        @case('business_analyst')
            @include('pages.dashboard.partials.ba-widgets')
            @break
            
        @case('da')
        @case('data_analyst')
            @include('pages.dashboard.partials.da-widgets')
            @break
            
        @case('hr')
        @case('human_resources')
            @include('pages.dashboard.partials.hr-widgets')
            @break
            
        @case('qa')
        @case('quality_assurance')
            @include('pages.dashboard.partials.qa-widgets')
            @break
            
        @case('secops')
        @case('security_operations')
            @include('pages.dashboard.partials.secops-widgets')
            @break
            
        @case('auditor')
            @include('pages.dashboard.partials.auditor-widgets')
            @break
            
        @case('manager')
            @include('pages.dashboard.partials.manager-widgets')
            @break
            
        @case('ciso')
            @include('pages.dashboard.partials.ciso-widgets')
            @break
            
        @default
            @include('pages.dashboard.partials.admin-widgets')
    @endswitch
    
</div>
@endsection

@section('scripts')
<script>
    // Auto-refresh data every 30 seconds
    setInterval(function() {
        $.ajax({
            url: '{{ url("/dashboard/refresh") }}',
            type: 'GET',
            success: function(data) {
                updateDashboardData(data);
            }
        });
    }, 30000);
    
    function updateDashboardData(data) {
        // Update widgets with new data
        Object.keys(data).forEach(key => {
            $(`#${key}`).text(data[key]);
        });
    }
</script>
@endsection