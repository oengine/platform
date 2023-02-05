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
    private $arrLink = [];
    public function addLink($source, $target, $relative = false)
    {
        $this->arrLink[$source . $target] = [
            'source' => $source,
            'target' => $target,
            'relative' => $relative
        ];
    }
    public function getLinks()
    {
        return $this->arrLink;
    }
}
