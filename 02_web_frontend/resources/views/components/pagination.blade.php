@props([
    'currentPage' => 1,
    'totalPages' => 1,
    'totalItems' => 0,
    'perPage' => 20,
    'urlPrefix' => '?page='
])

@if($totalPages > 1)
    <div class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6 mt-4">
        <div class="flex flex-1 justify-between sm:hidden">
            <a href="{{ $urlPrefix . ($currentPage - 1) }}" 
               class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 {{ $currentPage <= 1 ? 'opacity-50 pointer-events-none' : '' }}">
                Trước
            </a>
            <a href="{{ $urlPrefix . ($currentPage + 1) }}" 
               class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 {{ $currentPage >= $totalPages ? 'opacity-50 pointer-events-none' : '' }}">
                Sau
            </a>
        </div>
        
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    Hiển thị
                    <span class="font-medium">{{ ($currentPage - 1) * $perPage + 1 }}</span>
                    đến
                    <span class="font-medium">{{ min($currentPage * $perPage, $totalItems) }}</span>
                    trong số
                    <span class="font-medium">{{ number_format($totalItems) }}</span>
                    kết quả
                </p>
            </div>
            
            <div>
                <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                    <!-- Previous -->
                    <a href="{{ $currentPage > 1 ? $urlPrefix . ($currentPage - 1) : '#' }}" 
                       class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0 {{ $currentPage <= 1 ? 'opacity-50 pointer-events-none' : '' }}">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                    
                    @php
                        $start = max(1, $currentPage - 2);
                        $end = min($totalPages, $currentPage + 2);
                        
                        if ($start > 1) {
                            echo '<a href="' . $urlPrefix . '1" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">1</a>';
                            if ($start > 2) echo '<span class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300">...</span>';
                        }
                        
                        for ($i = $start; $i <= $end; $i++) {
                            $isCurrent = $i == $currentPage;
                    @endphp
                        <a href="{{ $urlPrefix . $i }}" 
                           class="relative inline-flex items-center px-4 py-2 text-sm font-semibold {{ $isCurrent ? 'bg-blue-600 text-white focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600' : 'text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0' }}">
                            {{ $i }}
                        </a>
                    @php
                        }
                        
                        if ($end < $totalPages) {
                            if ($end < $totalPages - 1) echo '<span class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300">...</span>';
                            echo '<a href="' . $urlPrefix . $totalPages . '" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">' . $totalPages . '</a>';
                        }
                    @endphp
                    
                    <!-- Next -->
                    <a href="{{ $currentPage < $totalPages ? $urlPrefix . ($currentPage + 1) : '#' }}" 
                       class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0 {{ $currentPage >= $totalPages ? 'opacity-50 pointer-events-none' : '' }}">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </nav>
            </div>
        </div>
    </div>
@endif