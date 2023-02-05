<?php

namespace OEngine\Platform\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Livewire\Livewire;

class PlatformController extends BaseController
{
    public function LivewireComponent(Request $request)
    {
        $data = platform_decode($request->get('key'));
        if ($data) {
            try {
                $params = isset($data['params']) ? $data['params'] : [];
                if (isset($data['view']) && $view = $data['view']) {
                    return [
                        'html' => view($view, $params)->render()
                    ];
                }
                if (isset($data['component']) && $component = $data['component']) {
                    return [
                        'html' => Livewire::mount($component, $params)->html()
                    ];
                }
            } catch (\Exception $ex) {
                return ['html' => '<div>not found</div>', 'data' => $data, 'error' => $ex];
            }
        }

        return ['html' => '<div>not found</div>', 'data' => $data, 'error' => 'not found'];
    }
    public function doEvents()
    {
    }
}
