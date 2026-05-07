@props([
    'variant' => 'primary', // primary, success, danger, warning, info, dark
    'size' => 'md', // sm, md
    'rounded' => 'full', // full, lg, md, sm
    'class' => ''
])

@php
    $variants = [
        'primary' => 'bg-blue-100 text-blue-800',
        'success' => 'bg-green-100 text-green-800',
        'danger' => 'bg-red-100 text-red-800',
        'warning' => 'bg-yellow-100 text-yellow-800',
        'info' => 'bg-cyan-100 text-cyan-800',
        'dark' => 'bg-gray-100 text-gray-800',
    ];
    
    $sizes = [
        'sm' => 'px-2 py-0.5 text-xs',
        'md' => 'px-2.5 py-1 text-sm',
    ];
    
    $roundeds = [
        'full' => 'rounded-full',
        'lg' => 'rounded-lg',
        'md' => 'rounded-md',
        'sm' => 'rounded-sm',
    ];
    
    $classes = implode(' ', array_filter([
        'inline-flex items-center font-medium',
        $variants[$variant],
        $sizes[$size],
        $roundeds[$rounded],
        $class
    ]));
@endphp

<span class="{{ $classes }}">
    {{ $slot }}
</span>