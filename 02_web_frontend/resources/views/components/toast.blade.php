@props([
    'type' => 'info', // success, error, warning, info
    'title' => null,
    'duration' => 3000,
    'position' => 'top-right' // top-right, top-left, bottom-right, bottom-left
])

@aware(['toasts' => []])

@php
    $positions = [
        'top-right' => 'top-5 right-5',
        'top-left' => 'top-5 left-5',
        'bottom-right' => 'bottom-5 right-5',
        'bottom-left' => 'bottom-5 left-5'
    ];
    
    $types = [
        'success' => 'bg-green-500',
        'error' => 'bg-red-500',
        'warning' => 'bg-yellow-500',
        'info' => 'bg-blue-500'
    ];
    
    $icons = [
        'success' => 'fa-check-circle',
        'error' => 'fa-exclamation-circle',
        'warning' => 'fa-exclamation-triangle',
        'info' => 'fa-info-circle'
    ];
@endphp

<div x-data="{ 
    toasts: [], 
    addToast(type, message, title = null) {
        const id = Date.now();
        this.toasts.push({ id, type, message, title });
        setTimeout(() => this.removeToast(id), {{ $duration }});
    },
    removeToast(id) {
        this.toasts = this.toasts.filter(t => t.id !== id);
    }
}"
x-init="window.toast = (type, message, title) => addToast(type, message, title)"
class="fixed {{ $positions[$position] }} z-50 space-y-2">
    <template x-for="toast in toasts" :key="toast.id">
        <div x-show="toast" 
             x-transition.duration.300ms
             class="min-w-[300px] max-w-md rounded-lg shadow-lg overflow-hidden"
             :class="{
                 'bg-green-500': toast.type === 'success',
                 'bg-red-500': toast.type === 'error',
                 'bg-yellow-500': toast.type === 'warning',
                 'bg-blue-500': toast.type === 'info'
             }">
            <div class="p-4 text-white">
                <div class="flex items-start gap-3">
                    <i class="fas text-xl" :class="{
                        'fa-check-circle': toast.type === 'success',
                        'fa-exclamation-circle': toast.type === 'error',
                        'fa-exclamation-triangle': toast.type === 'warning',
                        'fa-info-circle': toast.type === 'info'
                    }"></i>
                    <div class="flex-1">
                        <p class="font-semibold" x-text="toast.title || toast.type.charAt(0).toUpperCase() + toast.type.slice(1)"></p>
                        <p class="text-sm opacity-90" x-text="toast.message"></p>
                    </div>
                    <button @click="removeToast(toast.id)" class="text-white/70 hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>

<script>
    window.toast = window.toast || function(type, message, title) {};
</script>