{{-- Breadcrumb Component --}}
<nav class="flex" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 text-sm text-gray-700">
        @foreach($items as $index => $item)
            @if($index > 0)
                <li>
                    <span class="mx-2">&gt;</span>
                </li>
            @endif
            <li>
                @if(isset($item['url']) && !$loop->last)
                    <a href="{{ $item['url'] }}" class="text-2xl font text-gray-800 hover:text-[#5BC6BC]">
                        {{ $item['label'] }}
                    </a>
                @else
                    <span class="text-2xl {{ $loop->last ? 'font-bold' : 'font' }} text-gray-800">
                        {{ $item['label'] }}
                    </span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
