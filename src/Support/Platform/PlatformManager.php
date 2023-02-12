<?php

namespace OEngine\Platform\Support\Platform;


class PlatformManager
{
    private $platformCurrent = '';
    public function Current()
    {
        return $this->platformCurrent;
    }
    public function SwitchTo($curent)
    {
        return $this->platformCurrent = $curent;
    }
}
