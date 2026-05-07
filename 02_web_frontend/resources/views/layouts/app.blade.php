<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', config('app.name', 'WebApp'))</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Custom Styles -->
    <style>
        [x-cloak] { display: none !important; }
        .transition-sidebar {
            transition: all 0.3s ease;
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50 font-['Inter']">
    
    <!-- Include Header -->
    @include('layouts.header')
    
    <!-- Main Container with Flex -->
    <div class="flex pt-14">
        <!-- Include Sidebar -->
        @include('layouts.sidebar')
        
        <!-- Main Content Area -->
        <div id="mainContent" class="flex-1 transition-all duration-300 ml-64">
            <main class="p-6 min-h-screen">
                <!-- Breadcrumb -->
                @if(View::hasSection('breadcrumb'))
                    <div class="mb-4">
                        @yield('breadcrumb')
                    </div>
                @endif
                
                <!-- Page Content -->
                @yield('content')
            </main>
            
            <!-- Include Footer -->
            @include('layouts.footer')
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        // Sidebar Toggle Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const sidebarToggle = document.getElementById('sidebarToggle');
            
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('-translate-x-full');
                    mainContent.classList.toggle('ml-64');
                    mainContent.classList.toggle('ml-0');
                    
                    // Save state to localStorage
                    const isCollapsed = sidebar.classList.contains('-translate-x-full');
                    localStorage.setItem('sidebarCollapsed', isCollapsed);
                });
            }
            
            // Restore sidebar state
            const collapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (collapsed && sidebar && mainContent) {
                sidebar.classList.add('-translate-x-full');
                mainContent.classList.remove('ml-64');
                mainContent.classList.add('ml-0');
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>