@props([
    'name' => '',
    'id' => null,
    'label' => null,
    'options' => [],
    'value' => '',
    'placeholder' => '-- Chọn --',
    'required' => false,
    'disabled' => false,
    'error' => null,
    'multiple' => false,
    'class' => ''
])

@php
    $id = $id ?? $name;
    $hasError = $errors->has($name) || $error;
    $oldValue = old($name, $value);
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
    
    <select name="{{ $name }}" 
            id="{{ $id }}" 
            class="w-full rounded-lg border {{ $hasError ? 'border-red-500' : 'border-gray-300' }} py-2 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white {{ $class }}"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $multiple ? 'multiple' : '' }}
            {{ $attributes }}>
        
        @if($placeholder && !$multiple)
            <option value="">{{ $placeholder }}</option>
        @endif
        
        @foreach($options as $optionValue => $optionLabel)
            @if(is_array($optionLabel))
                <optgroup label="{{ $optionValue }}">
                    @foreach($optionLabel as $subValue => $subLabel)
                        <option value="{{ $subValue }}" {{ $oldValue == $subValue ? 'selected' : '' }}>
                            {{ $subLabel }}
                        </option>
                    @endforeach
                </optgroup>
            @else
                <option value="{{ $optionValue }}" {{ $oldValue == $optionValue ? 'selected' : '' }}>
                    {{ $optionLabel }}
                </option>
            @endif
        @endforeach
    </select>
    
    @if($hasError)
        <p class="mt-1 text-xs text-red-600">{{ $error ?? $errors->first($name) }}</p>
    @endif
</div>