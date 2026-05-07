@props([
    'title' => null,
    'subtitle' => null,
    'headerAction' => null,
    'padding' => 'md', // none, sm, md, lg
    'class' => '',
    'hover' => false
])

@php
    $paddings = [
        'none' => 'p-0',
        'sm' => 'p-3',
        'md' => 'p-4',
        'lg' => 'p-6'
    ];
    
    $hoverClass = $hover ? 'hover:shadow-lg transition-shadow duration-300' : '';
@endphp

<div class="bg-white rounded-xl shadow-md overflow-hidden {{ $hoverClass }} {{ $class }}">
    @if($title || isset($header))
        <div class="border-b border-gray-100 {{ $paddings[$padding] }}">
            <div class="flex items-center justify-between">
                <div>
                    @if($title)
                        <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
                    @endif
                    @if($subtitle)
                        <p class="text-sm text-gray-500 mt-1">{{ $subtitle }}</p>
                    @endif
                    {{ $header ?? '' }}
                </div>
                @if($headerAction)
                    <div>{{ $headerAction }}</div>
                @endif
            </div>
        </div>
    @endif
    
    <div class="{{ $paddings[$padding] }}">
        {{ $slot }}
    </div>
    
    @if(isset($footer))
        <div class="border-t border-gray-100 {{ $paddings[$padding] }} bg-gray-50">
            {{ $footer }}
        </div>
    @endif
</div>