<div @if ($modal_isPage) class="page-manager" @else class="p-2" @endif
    @if (getValueByKey($option, \OEngine\Core\Support\Config\ConfigManager::POLL, '')) wire:poll.{{ getValueByKey($option, \OEngine\Core\Support\Config\ConfigManager::POLL, '') }} @endif>
    <div @if ($modal_isPage) class="manager-table-content" @endif>
        @if ($modal_isPage)
            <div class="mb-2">
                <h4>{{ $modal_title }}</h4>
            </div>
        @endif
        @if (getValueByKey($option, \OEngine\Core\Support\Config\ConfigManager::INCLUDE_BEFORE, ''))
            @includeIf(getValueByKey($option, \OEngine\Core\Support\Config\ConfigManager::INCLUDE_BEFORE, ''))
        @endif
        <div class="mb-2 d-flex flex-row">
            <div style="flex:auto">
                @if ($checkAdd === true)
                    {!! \OEngine\Core\Facades\GateConfig::Button('core::table.button.add')->setClass('btn btn-primary btn-sm')->setDoComponent($viewEdit, '{\'module\':\'' . $module . '\'' . $paraText . '}')->setIcon('<i class="bi bi-plus-square"></i>')->toHtml() !!}
                @endif
                @foreach ($option->getButtonAppend([]) as $button)
                    @if ($button->checkType(\OEngine\Core\Support\Config\ButtonConfig::TYPE_ADD))
                        {!! call_user_func([$button, 'toHtml'], $module, $paraText) !!}
                    @endif
                @endforeach
                {!! apply_filters('module_action_left', '', $this) !!}
                {!! apply_filters('module_' . $module . '_action_left', '', $this) !!}
            </div>
            <div style="flex:none">
                {!! apply_filters('module_action_right', '', $this) !!}
                {!! apply_filters('module_' . $module . '_action_right', '', $this) !!}
            </div>
        </div>
        {!! TableRender($data, $option, ['sort' => $sort]) !!}
        @if (getValueByKey($option, \OEngine\Core\Support\Config\ConfigManager::INCLUDE_AFTER, ''))
            @includeIf(getValueByKey($option, \OEngine\Core\Support\Config\ConfigManager::INCLUDE_AFTER, ''))
        @endif
        @if (isset($data) && $data != null)
            {!! $data->links() !!}
        @endif
        {!! apply_filters('module_footer', '', $this) !!}
        {!! apply_filters('module_' . $module . '_footer', '', $this) !!}
    </div>
</div>
