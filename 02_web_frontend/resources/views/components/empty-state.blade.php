@props([
    'icon' => 'fa-inbox',
    'title' => 'Không có dữ liệu',
    'message' => 'Chưa có dữ liệu nào được tìm thấy.',
    'actionText' => null,
    'actionUrl' => null,
    'actionIcon' => null
])

<div class="text-center py-12">
    <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-4">
        <i class="fas {{ $icon }} text-3xl text-gray-400"></i>
    </div>
    <h3 class="text-lg font-medium text-gray-900 mb-2">{{ $title }}</h3>
    <p class="text-gray-500 max-w-sm mx-auto">{{ $message }}</p>
    
    @if($actionText && $actionUrl)
        <div class="mt-6">
            <a href="{{ $actionUrl }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                @if($actionIcon)
                    <i class="fas fa-{{ $actionIcon }}"></i>
                @endif
                {{ $actionText }}
            </a>
        </div>
    @endif
    
    {{ $slot }}
</div>