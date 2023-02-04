<?php

namespace OEngine\Platform\Support\Module;

use OEngine\Platform\Traits\WithSystemExtend;

class ModuleManager
{
    use WithSystemExtend;
    public function getName()
    {
        return "module";
    }
}
