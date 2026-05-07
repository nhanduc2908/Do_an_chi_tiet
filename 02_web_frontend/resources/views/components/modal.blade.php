@props([
    'id' => null,
    'title' => null,
    'size' => 'md', // sm, md, lg, xl
    'closeOnOverlayClick' => true,
    'showFooter' => true,
    'confirmText' => 'Xác nhận',
    'cancelText' => 'Hủy',
    'confirmVariant' => 'primary',
    'cancelVariant' => 'outline'
])

@php
    $sizes = [
        'sm' => 'max-w-md',
        'md' => 'max-w-lg',
        'lg' => 'max-w-2xl',
        'xl' => 'max-w-4xl',
        'full' => 'max-w-full mx-4'
    ];
@endphp

<div x-data="{ 
    open: false, 
    closeOnOverlayClick: {{ $closeOnOverlayClick ? 'true' : 'false' }},
    init() {
        this.$watch('open', value => {
            if (value) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        });
    },
    close() {
        this.open = false;
    }
}" 
x-init="() => { if (window.modalEvents) window.modalEvents.{{ $id ?? 'default' }} = { open: () => open = true, close: () => open = false } }"
x-cloak>

    <!-- Trigger Button (nếu có slot trigger) -->
    @if(isset($trigger))
        <div @click="open = true">
            {{ $trigger }}
        </div>
    @endif
    
    <!-- Modal Overlay -->
    <div x-show="open" 
         x-transition.opacity.duration.300ms
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity"
             @click="closeOnOverlayClick && close()"></div>
        
        <!-- Modal Container -->
        <div class="flex min-h-full items-center justify-center p-4">
            <div x-show="open" 
                 x-transition.duration.300ms
                 x-on:click.away="closeOnOverlayClick && close()"
                 class="relative bg-white rounded-xl shadow-xl {{ $sizes[$size] }} w-full transform transition-all"
                 @click.stop>
                
                <!-- Modal Header -->
                @if($title || isset($header))
                    <div class="flex items-center justify-between p-4 border-b">
                        <div class="flex-1">
                            @if(isset($header))
                                {{ $header }}
                            @else
                                <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
                            @endif
                        </div>
                        <button @click="close()" class="text-gray-400 hover:text-gray-600 transition">
                            <i class="fas fa-times text-lg"></i>
                        </button>
                    </div>
                @endif
                
                <!-- Modal Body -->
                <div class="p-4">
                    {{ $slot }}
                </div>
                
                <!-- Modal Footer -->
                @if($showFooter)
                    <div class="flex justify-end gap-3 p-4 border-t bg-gray-50 rounded-b-xl">
                        @if(isset($footer))
                            {{ $footer }}
                        @else
                            <x-button variant="{{ $cancelVariant }}" @click="close()">
                                {{ $cancelText }}
                            </x-button>
                            <x-button variant="{{ $confirmVariant }}" {{ $attributes->whereStartsWith('wire:click') }}>
                                {{ $confirmText }}
                            </x-button>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    // Global function to open/close modals
    window.modalEvents = window.modalEvents || {};
    window.openModal = (modalId) => window.modalEvents[modalId]?.open();
    window.closeModal = (modalId) => window.modalEvents[modalId]?.close();
</script>