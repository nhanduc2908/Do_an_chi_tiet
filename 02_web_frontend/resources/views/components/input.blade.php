@props([
    'type' => 'text',
    'name' => '',
    'id' => null,
    'label' => null,
    'placeholder' => '',
    'value' => '',
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'error' => null,
    'hint' => null,
    'icon' => null,
    'iconPosition' => 'left',
    'class' => ''
])

@php
    $id = $id ?? $name;
    $hasError = $errors->has($name) || $error;
    $errorMessage = $error ?? ($errors->first($name) ?? null);
    
    $inputClasses = [
        'w-full rounded-lg border transition-all duration-200',
        $hasError ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500',
        $icon && $iconPosition === 'left' ? 'pl-10' : '',
        $icon && $iconPosition === 'right' ? 'pr-10' : '',
        'py-2 px-3 text-sm focus:outline-none focus:ring-2',
        $disabled || $readonly ? 'bg-gray-100 cursor-not-allowed' : 'bg-white',
        $class
    ];
@endphp

<div class="mb-4">
    @if($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-gray-700 mb-1">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <div class="relative">
        @if($icon && $iconPosition === 'left')
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-{{ $icon }} text-gray-400 text-sm"></i>
            </div>
        @endif
        
        <input type="{{ $type }}" 
               name="{{ $name }}" 
               id="{{ $id }}" 
               value="{{ old($name, $value) }}"
               placeholder="{{ $placeholder }}"
               class="{{ implode(' ', $inputClasses) }}"
               {{ $required ? 'required' : '' }}
               {{ $disabled ? 'disabled' : '' }}
               {{ $readonly ? 'readonly' : '' }}
               {{ $attributes }}>
        
        @if($icon && $iconPosition === 'right')
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <i class="fas fa-{{ $icon }} text-gray-400 text-sm"></i>
            </div>
        @endif
    </div>
    
    @if($hint)
        <p class="mt-1 text-xs text-gray-500">{{ $hint }}</p>
    @endif
    
    @if($hasError)
        <p class="mt-1 text-xs text-red-600">{{ $errorMessage }}</p>
    @endif
</div>