@props([
    'fullScreen' => false,
    'text' => 'Đang tải...',
    'size' => 'md', // sm, md, lg
    'color' => 'primary' // primary, white
])

@php
    $sizes = [
        'sm' => 'w-6 h-6 border-2',
        'md' => 'w-10 h-10 border-3',
        'lg' => 'w-16 h-16 border-4'
    ];
    
    $colors = [
        'primary' => 'border-blue-500',
        'white' => 'border-white'
    ];
@endphp

@if($fullScreen)
    <div class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 flex flex-col items-center shadow-xl">
            <div class="{{ $sizes[$size] }} {{ $colors[$color] }} border-t-transparent rounded-full animate-spin"></div>
            @if($text)
                <p class="mt-4 text-gray-600">{{ $text }}</p>
            @endif
        </div>
    </div>
@else
    <div class="flex flex-col items-center justify-center py-8">
        <div class="{{ $sizes[$size] }} {{ $colors[$color] }} border-t-transparent rounded-full animate-spin"></div>
        @if($text)
            <p class="mt-3 text-gray-500 text-sm">{{ $text }}</p>
        @endif
    </div>
@endif