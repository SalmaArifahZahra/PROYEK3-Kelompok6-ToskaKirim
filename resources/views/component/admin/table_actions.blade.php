<div class="flex items-center space-x-2">
    {{-- Tombol Detail/Lihat Varian --}}
    @if(isset($detailUrl))
        <a href="{{ $detailUrl }}" class="text-[#5BC6BC] hover:text-[#4aa89e] transition-colors" title="Lihat Varian">
            <i class="fas fa-eye"></i>
        </a>
    @endif

    {{-- Tombol Edit --}}
    @if(isset($editUrl))
        <a href="{{ $editUrl }}" class="text-blue-500 hover:text-blue-700 transition-colors" title="Edit">
            <i class="fas fa-edit"></i>
        </a>
    @endif

    {{-- Tombol Delete --}}
    @if(isset($deleteUrl))
        <form action="{{ $deleteUrl }}" method="POST" class="swal-delete inline-block" data-nama="kategori ini">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-red-600 hover:text-red-800 transition-colors">
                <i class="fas fa-trash"></i>
            </button>
        </form>
    @endif
    
    {{-- Tombol Ellipsis --}}
    @if(isset($showEllipsis) && $showEllipsis)
        <a href="{{ $showEllipsis }}" class="text-gray-500 hover:text-gray-700 transition-colors" title="{{ $ellipsisTitle ?? 'Detail' }}">
            <i class="fas fa-ellipsis-v"></i>
        </a>
    @endif
</div>