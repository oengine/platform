<?php

namespace OEngine\Platform\Traits;

use Illuminate\Support\Facades\Cache;
use OEngine\Platform\DataInfo;

trait WithSystemExtend
{
    public function __construct()
    {
        $this->arrData = collect([]);
    }
    private $arrData = [];
    public function getName()
    {
        return 'info';
    }
    public function FileInfoJson()
    {
        return $this->getName() . ".json";
    }
    public function HookFilterPath()
    {
        return $this->getName() . '_path';
    }
    public function PathFolder()
    {
        return $this->getPath('');
    }
    public function getPath($path)
    {
        return path_by($this->getName(), $path);
    }
    public function PublicFolder()
    {
        return public_path($this->getName() . 's');
    }
    public function ResetData()
    {
        $this->arrData = collect($this->arrData)->where(function ($item) {
            return $item->isVendor();
        })->toBase();
    }
    public function LoadApp()
    {
        $this->Load(apply_filters($this->HookFilterPath(), $this->PathFolder()));
    }
    public function RegisterApp()
    {
        foreach ($this->getData() as $item) {
            if ($item->isActive()) {
                $item->DoRegister();
            }
        }
    }
    public function BootApp()
    {
        foreach ($this->getData() as $item) {
            if ($item->isActive()) {
                $item->DoBoot();
            }
        }
    }
    /**
     * Get the data.
     *
     * @return \Illuminate\Support\Collection<string, \OEngine\Core\Support\Core\DataInfo>
     */
    public function getData()
    {
        return $this->arrData;
    }
    /**
     * Find item by name.
     * @param string $name
     *
     * @return  DataInfo
     */
    public function find($name)
    {
        return $this->getData()->where(function (DataInfo $item) use ($name) {
            return $item->CheckName($name);
        })->first();
    }
    public function has($name)
    {
        return $this->find($name) != null;
    }
    public function delete($name)
    {
        $base = $this->find($name);
        // if ($base) {
        //     $base->delete();
        // }
    }
    public function Load($path)
    {
        if ($files =  glob($path . '/*', GLOB_ONLYDIR)) {
            foreach ($files as $item) {
                $this->AddItem($item);
            }
        }
    }
    public function AddItem($path)
    {
        $this->arrData[$path] = new DataInfo($path, $this);
        return $this->arrData[$path];
    }
    public function getUsed()
    {
        return Cache::get($this->getName() . '_used');
    }
    public function setUsed($name)
    {
        Cache::forever($this->getName() . '_used', $name);
    }
    public function forgetUsed()
    {
        Cache::forget($this->getName() . '_used');
    }
    public function update(string $name)
    {
        $base = $this->find($name);
        if ($base) {
            $base->update();
        }
    }
}
