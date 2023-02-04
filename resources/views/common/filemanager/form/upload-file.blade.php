<div class="p-2">
    <div class="p-1">
        
        {!! FieldRender(\OEngine\Core\Facades\GateConfig::field('file')->setType(13),['errors'=>$errors]) !!}
        @error('file') <span class="error">{{ $message }}</span> @enderror
    </div>
    <div class="p-1 text-center"><button class="btn btn-primary" wire:click="DoWork">Upload File</button></div>
</div>
