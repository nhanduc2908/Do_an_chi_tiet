@props([
    'type' => 'info', // success, error, warning, info
    'title' => null,
    'dismissible' => true,
    'icon' => true,
    'class' => ''
])

@php
    $types = [
        'success' => [
            'bg' => 'bg-green-50',
            'border' => 'border-green-400',
            'text' => 'text-green-800',
            'icon' => 'fa-check-circle',
            'iconColor' => 'text-green-400'
        ],
        'error' => [
            'bg' => 'bg-red-50',
            'border' => 'border-red-400',
            'text' => 'text-red-800',
            'icon' => 'fa-exclamation-circle',
            'iconColor' => 'text-red-400'
        ],
        'warning' => [
            'bg' => 'bg-yellow-50',
            'border' => 'border-yellow-400',
            'text' => 'text-yellow-800',
            'icon' => 'fa-exclamation-triangle',
            'iconColor' => 'text-yellow-400'
        ],
        'info' => [
            'bg' => 'bg-blue-50',
            'border' => 'border-blue-400',
            'text' => 'text-blue-800',
            'icon' => 'fa-info-circle',
            'iconColor' => 'text-blue-400'
        ]
    ];
    
    $current = $types[$type] ?? $types['info'];
@endphp

<div class="{{ $current['bg'] }} border-l-4 {{ $current['border'] }} p-4 rounded-lg {{ $class }}" 
     x-data="{ show: true }" 
     x-show="show" 
     x-transition.duration.300ms>
    <div class="flex items-start gap-3">
        @if($icon)
            <div class="flex-shrink-0">
                <i class="fas {{ $current['icon'] }} {{ $current['iconColor'] }} text-lg"></i>
            </div>
        @endif
        
        <div class="flex-1">
            @if($title)
                <h3 class="text-sm font-medium {{ $current['text'] }}">{{ $title }}</h3>
            @endif
            <div class="text-sm {{ $current['text'] }} {{ $title ? 'mt-1' : '' }}">
                {{ $slot }}
            </div>
        </div>
        
        @if($dismissible)
            <div class="flex-shrink-0">
                <button @click="show = false" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif
    </div>
</div>