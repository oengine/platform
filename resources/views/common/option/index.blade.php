<div>
    @if ($option_data->getTitle())
        <h4>{{ $option_data->getTitle() }}</h4>
        <div>
            {!! FormRender($option_data) !!}
            <div class="py-2">
                <button class="btn btn-primary" wire:click="doSave()">{{ __('core::table.button.save') }}</button>
            </div>
        </div>
    @else
        <h2>Not found {{ $option_key }}</h2>
    @endif
</div>
