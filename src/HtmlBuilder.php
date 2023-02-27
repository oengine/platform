<?php

namespace OEngine\Platform;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Traits\Macroable;

abstract class HtmlBuilder implements Htmlable
{
    use Macroable;

    protected abstract function render();
    public function toHtml()
    {
        ob_start();
        $this->render();
        return ob_get_clean();
    }
}
