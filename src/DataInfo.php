<?php

namespace OEngine\Platform;

use Illuminate\Support\Facades\File;
use OEngine\LaravelPackage\JsonData;

use Illuminate\Support\Str;
use OEngine\Platform\Facades\Platform;

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
        $this->loadAll($path);
        $this->ReLoad();
    }
    public function loadAll($path)
    {
        // if ($this->isVendor()) return;
        Platform::Load($path);
    }
    public function ReLoad()
    {
        $temp = $this->CloneData();
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
    public function getId()
    {
        return $this['id'];
    }
    public function getName()
    {
        return $this['name'];
    }
    protected function getKeyOption($key)
    {
        return trim(Str::lower("option_datainfo_" . $this['base_type'] . '_' . $this->getId() . '_' . $key . '_value'));
    }
    public function getOption($key, $default = null)
    {
        return  get_option($this->getKeyOption($key), $default);
    }
    public function setOption($key, $value)
    {
        return set_option($this->getKeyOption($key), $value);
    }
    public function getStatusData()
    {
        return $this->getOption('status');
    }

    public function setStatusData($value)
    {
        if ($value == self::Active && !$this->checkDump()) {
            $this->Dump();
        }
        $this->setOption('status', $value);
    }
    public function isVendor()
    {
        return !str_starts_with($this->getPath(), config('platform.appdir.root', 'platform'));
    }
    public function isActive()
    {
        return $this->status == self::Active;
    }
    public function Active()
    {
        $this->status = self::Active;
    }
    public function UnActive()
    {
        $this->status = self::UnActive;
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
    }
    public function update()
    {
    }
    public function CheckName($name)
    {
        return Str::lower($this->getId())  ==  Str::lower($name) || Str::lower($this->name) == Str::lower($name);
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
    public function delete()
    {
        File::deleteDirectory($this->getPath());
    }
    public function loadRoute()
    {
        RouteEx::Load($this->getPath('routes'));
    }
    public function Autoload()
    {
        if ($this->checkComposer() && !$this->checkDump()) {
            $this->Dump();
        }
        if ($this->checkDump()) {
            include_once $this->getPath('vendor/autoload.php');
            return true;
        }
        return false;
    }
    public function DoRegister()
    {
       // if ($this->isVendor()) return;
        if ($this->Autoload()) {
            $composer = $this->getJsonFromFile($this->getPath('composer.json'));
            $providers = self::getValueByKey($composer, 'extra.laravel.providers', []);
            $this->providers =  collect($providers)->map(function ($item) {
                return app()->register($item);
            });
        }
    }
    public function DoBoot()
    {
    }
}
