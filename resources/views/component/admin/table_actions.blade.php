{{-- Table Action Buttons Component --}}
<div class="flex items-center gap-3">
    @if(isset($showDetail) && $showDetail)
        <!-- Detail Button -->
        <a href="{{ $detailUrl }}" class="p-2 text-gray-600 hover:text-[#5BC6BC] hover:bg-gray-100 rounded-lg transition-colors" title="Detail">
            <i class="fas fa-eye"></i>
        </a>
    @endif

    @if(isset($showEllipsis) && $showEllipsis)
        <!-- Ellipsis Button (Sub-kategori/More) -->
        <a href="{{ $ellipsisUrl }}" class="text-gray-600 hover:text-[#5BC6BC] transition-colors" title="{{ $ellipsisTitle ?? 'More' }}">
            <i class="fas fa-ellipsis-v"></i>
        </a>
    @endif

    <!-- Edit Button -->
    <a href="{{ $editUrl }}" class="text-gray-600 hover:text-[#5BC6BC] transition-colors" title="Edit">
        <i class="fas fa-edit"></i>
    </a>

    <!-- Delete Button -->
    <form action="{{ $deleteUrl }}" method="POST" class="inline" onsubmit="return confirm('{{ $confirmMessage ?? 'Yakin ingin menghapus?' }}')">
        @csrf
        @method('DELETE')
        <button type="submit" class="text-gray-600 hover:text-red-600 transition-colors" title="Hapus">
            <i class="fas fa-trash"></i>
        </button>
    </form>
</div>
