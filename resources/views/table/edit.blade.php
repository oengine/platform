<form wire:submit.prevent="SaveForm" class="edit-{{ $module }} edit-form">
    @if (isset($option[\OEngine\Core\Support\Config\FormConfig::FORM_INCLUDE]) &&
        $option[\OEngine\Core\Support\Config\FormConfig::FORM_INCLUDE] != '')
        @include($option[\OEngine\Core\Support\Config\FormConfig::FORM_INCLUDE])
    @else
        <div class="p-1">
            {!! FormRender($option, $this, ['isNew' => $isFormNew, 'errors' => $errors]) !!}
            <div class="text-center pt-3">
                <button class="btn btn-primary btn-sm" type="submit">
                    <i class="bi bi-download"></i> <span class="p-1">{{ __('core::table.button.save') }}</span>
                </button>
                {!! apply_filters('module_edit_footer', '', $this) !!}
                {!! apply_filters('module_edit_' . $module . '_footer', '', $this) !!}
            </div>
        </div>
    @endif
</form>
