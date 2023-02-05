<?php

namespace OEngine\Platform\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Livewire\Livewire;

class PlatformController extends BaseController
{
    public function LivewireComponent(Request $request)
    {
        $data = platform_decode($request->get('key'));
        if ($data) {
            try {
                $component = $data['component'];
                $params = $data['params'];
                return [
                    'html' => Livewire::mount($component, $params)->html()
                ];
            } catch (\Exception $ex) {
                printf($ex);
                return ['html' => '<div>not found' . json_encode($data) . '</div>', 'error' => $ex];
            }
        }

        return ['html' => '<div>not found' . json_encode($data) . '</div>', 'error' => 'not found'];
    }
    public function doEvents()
    {
    }
}
