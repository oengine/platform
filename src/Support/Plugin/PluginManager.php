<?php

namespace OEngine\Platform\Support\Plugin;

use OEngine\Platform\Traits\WithSystemExtend;

class PluginManager
{
    use WithSystemExtend;
    public function getName()
    {
        return "plugin";
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
