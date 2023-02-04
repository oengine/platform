<?php

namespace OEngine\Platform\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Livewire\Livewire;

class PlatformController extends BaseController
{
    public function LivewireComponent(Request $request)
    {
        $data = $request->get('data');
        if ($data) return ['html' => '', 'error' => 'not found'];
        $slug = $data['slug'];
        $param = $data['param'];
        return [
            'html' => Livewire::mount($slug, $param)->html(),
            'slug' => $slug,
            'param' => $param,
        ];
    }
    public function doEvents()
    {
    }
}
