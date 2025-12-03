{{-- Image Upload Preview Component --}}
<div class="flex flex-col items-center">
    <label for="{{ $inputId ?? 'foto' }}" class="block text-sm font-medium text-gray-900 mb-2">
        {{ $label ?? 'Foto' }}
    </label>
    <div class="relative">
        <div id="image-preview-{{ $inputId ?? 'foto' }}" class="w-64 h-64 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center overflow-hidden bg-gray-50">
            @if(isset($existingImage) && $existingImage)
                <img id="preview-image-{{ $inputId ?? 'foto' }}" src="{{ asset($existingImage) }}" class="w-full h-full object-contain" alt="Preview">
                <div id="placeholder-{{ $inputId ?? 'foto' }}" class="hidden text-center">
            @else
                <div id="placeholder-{{ $inputId ?? 'foto' }}" class="text-center">
            @endif
                    <i class="fas fa-image text-gray-400 text-4xl mb-2"></i>
                    <p class="text-sm text-gray-500">Upload foto</p>
                </div>
            @if(!isset($existingImage) || !$existingImage)
                <img id="preview-image-{{ $inputId ?? 'foto' }}" class="hidden w-full h-full object-contain" alt="Preview">
            @endif
        </div>
        <input type="file"
               id="{{ $inputId ?? 'foto' }}"
               name="{{ $name ?? 'foto' }}"
               accept="image/*"
               class="hidden"
               onchange="previewImageComponent('{{ $inputId ?? 'foto' }}', event)">
        <label for="{{ $inputId ?? 'foto' }}" class="absolute bottom-2 right-2 bg-[#5BC6BC] text-white p-2 rounded-lg cursor-pointer hover:bg-[#4aa89e] transition-colors">
            <i class="fas fa-camera text-lg"></i>
        </label>
    </div>
    @error($name ?? 'foto')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<script>
    function previewImageComponent(inputId, event) {
        const input = event.target;
        const preview = document.getElementById('preview-image-' + inputId);
        const placeholder = document.getElementById('placeholder-' + inputId);

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
