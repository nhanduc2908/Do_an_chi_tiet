@props([
    'type' => 'button',
    'variant' => 'primary', // primary, secondary, outline, danger, success, warning, info
    'size' => 'md', // sm, md, lg
    'icon' => null,
    'iconPosition' => 'left',
    'loading' => false,
    'disabled' => false,
    'fullWidth' => false,
    'href' => null,
    'target' => null,
    'class' => ''
])

@php
    $variants = [
        'primary' => 'bg-blue-600 hover:bg-blue-700 text-white focus:ring-blue-500',
        'secondary' => 'bg-gray-600 hover:bg-gray-700 text-white focus:ring-gray-500',
        'outline' => 'border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 focus:ring-blue-500',
        'danger' => 'bg-red-600 hover:bg-red-700 text-white focus:ring-red-500',
        'success' => 'bg-green-600 hover:bg-green-700 text-white focus:ring-green-500',
        'warning' => 'bg-yellow-500 hover:bg-yellow-600 text-white focus:ring-yellow-500',
        'info' => 'bg-cyan-500 hover:bg-cyan-600 text-white focus:ring-cyan-500',
        'ghost' => 'bg-transparent hover:bg-gray-100 text-gray-700 focus:ring-gray-500',
    ];
    
    $sizes = [
        'sm' => 'px-3 py-1.5 text-xs',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-6 py-3 text-base',
    ];
    
    $classes = [
        $variants[$variant] ?? $variants['primary'],
        $sizes[$size] ?? $sizes['md'],
        $fullWidth ? 'w-full' : '',
        'inline-flex items-center justify-center gap-2 font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed',
        $class
    ];
    
    $buttonClass = implode(' ', array_filter($classes));
@endphp

@if($href)
    <a href="{{ $href }}" target="{{ $target }}" class="{{ $buttonClass }}" {{ $attributes->except(['type', 'variant', 'size', 'icon', 'iconPosition', 'loading', 'disabled', 'fullWidth', 'href', 'target', 'class']) }}>
        @if($loading)
            <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>{{ $slot ?? 'Đang xử lý...' }}</span>
        @else
            @if($icon && $iconPosition === 'left')
                <i class="fas fa-{{ $icon }}"></i>
            @endif
            <span>{{ $slot }}</span>
            @if($icon && $iconPosition === 'right')
                <i class="fas fa-{{ $icon }}"></i>
            @endif
        @endif
    </a>
@else
    <button type="{{ $type }}" class="{{ $buttonClass }}" {{ $disabled || $loading ? 'disabled' : '' }} {{ $attributes->except(['type', 'variant', 'size', 'icon', 'iconPosition', 'loading', 'disabled', 'fullWidth', 'href', 'target', 'class']) }}>
        @if($loading)
            <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>{{ $slot ?? 'Đang xử lý...' }}</span>
        @else
            @if($icon && $iconPosition === 'left')
                <i class="fas fa-{{ $icon }}"></i>
            @endif
            <span>{{ $slot }}</span>
            @if($icon && $iconPosition === 'right')
                <i class="fas fa-{{ $icon }}"></i>
            @endif
        @endif
    </button>
@endif