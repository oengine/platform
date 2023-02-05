<?php

namespace OEngine\Platform;

use OEngine\LaravelPackage\JsonData;

use Illuminate\Support\Str;

class DataInfo extends JsonData
{
    const Active = 1;
    const UnActive = 0;
    public function __construct($path, $parent)
    {
        parent::__construct([], $parent);
        $this['path'] = $path;
        $this['fileInfo'] =  $parent->FileInfoJson();
        $this['public'] =  $parent->PublicFolder();
        $this['base_type'] = $parent->getName();
        $this->ReLoad();
    }
    public function ReLoad()
    {
        $temp = $this;
        $this->loadJsonFromFile($temp['path'] . '/' .  $temp['fileInfo']);
        $this['path'] = $temp['path'];
        $this['fileInfo'] = $temp['fileInfo'];
        $this['public'] = $temp['public'];
        $this['base_type'] = $temp['base_type'];
        $this['key'] = basename($temp['path'], ".php");
    }
    public function __toString()
    {
        return $this->getName();
    }
    public function checkKeyValue($key, $value)
    {
        return $this[$key] == $value;
    }


    public function getPath($_path = '')
    {
        return $this->path . ($_path ? ('/' . $_path) : '');
    }
    public function getPublic($_path = '')
    {
        return $this->public . ($_path ? ('/' . $_path) : '');
    }

    public function getFiles()
    {
        return $this['files'] ?? [];
    }
    public function getProviders()
    {
        return $this['providers'] ?? [];
    }
    public function getKey()
    {
        return $this['key'];
    }
    public function getName()
    {
        return $this['name'];
    }
    protected function getKeyOption($key)
    {
        return trim(Str::lower("option_datainfo_" . $this['base_type'] . '_' . $this->getKey() . '_' . $key . '_value'));
    }
    public function getStatusData()
    {
        return get_option($this->getKeyOption('status'));
    }
    public function isVendor()
    {
        return !str_starts_with($this->getPath(), config('platform.appdir.root', 'platform'));
    }
    public function setStatusData($value)
    {
        if ($value == self::Active && !$this->checkDump()) {
            $this->Dump();
        }
        $flg = set_option($this->getKeyOption('status'), $value);
        return $flg;
    }
    public function isActive()
    {
        return $this['status'] == self::Active;
    }
    public function Active()
    {
        $this['status'] = self::Active;
    }
    public function UnActive()
    {
        $this['status'] = self::UnActive;
    }
    public function checkComposer()
    {
        return file_exists($this->getPath('composer.json'));
    }
    public function checkDump()
    {
        return file_exists($this->getPath('vendor/autoload.php'));
    }
    public function Dump()
    {
        run_cmd($this->getPath(), 'composer dump -o -n -q');
        // chdir($this->getPath());
        // passthru('composer dump -o -n -q');
    }
    public function update()
    {
    }
    public function CheckName($name)
    {
        return Str::lower($this->getKey())  ==  Str::lower($name) || Str::lower($this->name) == Str::lower($name);
    }
    public function getStudlyName()
    {
        return Str::studly($this->getName());
    }
    public function getLowerName()
    {
        return Str::lower($this->getName());
    }
    public function getNamespaceInfo()
    {
        return $this['namespace'];
    }
    private $providers;
    public function DoRegister()
    {
        if ($this->checkComposer() && !$this->checkDump()) {
            $this->Dump();
        }

        if ($this->checkDump()) {
            include_once $this->getPath('vendor/autoload.php');
            $composer = $this->getJsonFromFile($this->getPath('composer.json'));
            $providers = self::getValueByKey($composer, 'extra.laravel.providers', []);
            $this->providers =  collect($providers)->map(function ($item) {
                return app()->register($item, true);
            });
        }
    }
    public function DoBoot()
    {
        if (isset($this->providers) && $this->providers != null && is_array($this->providers) && count($this->providers) > 0) {
            foreach ($this->providers as $item) {
                if (method_exists($item, 'boot'))
                    $item->boot();
            }
        }
    }
}
