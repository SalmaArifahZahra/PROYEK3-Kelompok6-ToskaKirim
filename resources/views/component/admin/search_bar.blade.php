{{-- Search Bar Component --}}
<div class="bg-white rounded-lg shadow-md p-4 w-1/2">
    <div class="relative">
        <input type="text"
               placeholder="{{ $placeholder ?? 'Search' }}"
               class="w-full pl-4 pr-12 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5BC6BC] focus:border-transparent">
        <button class="absolute right-2 top-1/2 -translate-y-1/2 p-2 bg-[#5BC6BC] text-white rounded-lg hover:bg-[#4aa89e] transition-colors">
            <i class="fas fa-search"></i>
        </button>
    </div>
</div>
