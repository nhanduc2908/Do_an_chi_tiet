<footer class="bg-white border-t border-gray-200 mt-8">
    <div class="container mx-auto px-6 py-5">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            
            <!-- Copyright Section -->
            <div class="text-sm text-gray-500">
                © {{ date('Y') }} 
                <a href="{{ url('/') }}" class="text-blue-600 hover:text-blue-700 font-medium transition">WebApp</a>. 
                All rights reserved.
            </div>
            
            <!-- Links Section -->
            <div class="flex flex-wrap justify-center gap-x-6 gap-y-2 text-sm">
                <a href="{{ url('/about') }}" class="text-gray-500 hover:text-blue-600 transition flex items-center gap-1">
                    <i class="fas fa-info-circle text-xs"></i>
                    <span>Giới thiệu</span>
                </a>
                <a href="{{ url('/terms') }}" class="text-gray-500 hover:text-blue-600 transition flex items-center gap-1">
                    <i class="fas fa-file-contract text-xs"></i>
                    <span>Điều khoản</span>
                </a>
                <a href="{{ url('/privacy') }}" class="text-gray-500 hover:text-blue-600 transition flex items-center gap-1">
                    <i class="fas fa-shield-alt text-xs"></i>
                    <span>Chính sách bảo mật</span>
                </a>
                <a href="{{ url('/support') }}" class="text-gray-500 hover:text-blue-600 transition flex items-center gap-1">
                    <i class="fas fa-headset text-xs"></i>
                    <span>Hỗ trợ</span>
                </a>
                <a href="{{ url('/contact') }}" class="text-gray-500 hover:text-blue-600 transition flex items-center gap-1">
                    <i class="fas fa-envelope text-xs"></i>
                    <span>Liên hệ</span>
                </a>
            </div>
            
            <!-- Social Media Section -->
            <div class="flex gap-3">
                <a href="#" class="w-8 h-8 bg-gray-100 hover:bg-blue-100 rounded-full flex items-center justify-center text-gray-600 hover:text-blue-600 transition">
                    <i class="fab fa-facebook-f text-sm"></i>
                </a>
                <a href="#" class="w-8 h-8 bg-gray-100 hover:bg-blue-100 rounded-full flex items-center justify-center text-gray-600 hover:text-blue-600 transition">
                    <i class="fab fa-twitter text-sm"></i>
                </a>
                <a href="#" class="w-8 h-8 bg-gray-100 hover:bg-blue-100 rounded-full flex items-center justify-center text-gray-600 hover:text-blue-600 transition">
                    <i class="fab fa-github text-sm"></i>
                </a>
                <a href="#" class="w-8 h-8 bg-gray-100 hover:bg-blue-100 rounded-full flex items-center justify-center text-gray-600 hover:text-blue-600 transition">
                    <i class="fab fa-linkedin-in text-sm"></i>
                </a>
                <a href="#" class="w-8 h-8 bg-gray-100 hover:bg-blue-100 rounded-full flex items-center justify-center text-gray-600 hover:text-blue-600 transition">
                    <i class="fab fa-youtube text-sm"></i>
                </a>
            </div>
        </div>
        
        <!-- Additional Info Line -->
        <div class="text-center text-xs text-gray-400 mt-4 pt-3 border-t border-gray-100">
            <i class="fas fa-heart text-red-400"></i> 
            Designed with care for the community
        </div>
    </div>
</footer>