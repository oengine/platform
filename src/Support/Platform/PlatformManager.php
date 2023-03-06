<?php

namespace OEngine\Platform\Support\Platform;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use OEngine\Platform\Facades\Module;
use OEngine\Platform\Facades\Plugin;
use OEngine\Platform\Facades\Theme;
use OEngine\Platform\RouteEx;

class PlatformManager
{
    private $platformFileVersion;
    public function FileVersion()
    {
        return $this->platformFileVersion ?? ($this->platformFileVersion = json_decode(file_get_contents(config('platform.updator.url')), true));
    }
    public function unzip($file, $extTo)
    {
        $zipHandle = new  \ZipArchive();
        $zipHandle->open($file);
        File::ensureDirectoryExists($extTo);
        $zipHandle->extractTo($extTo);
        $zipHandle->close();
    }
    public function download($remote_file_url, $local_file, $throw = false)
    {
        try {
            $update = file_get_contents($remote_file_url);
            file_put_contents($local_file, $update);
            return true;
        } catch (\Exception $e) {
            if ($throw) throw $e;
            return false;
        }
    }
    public function findFile($id_or_name)
    {
        $fileVersion = $this->FileVersion();
        if (!$fileVersion || !is_array($fileVersion) || !isset($fileVersion['files'])) return null;
        $rs = array_values(array_filter($fileVersion['files'], function ($item) use ($id_or_name) {
            if (isset($item['id']) && $item['id'] === $id_or_name) return true;
            return false;
        }));
        if (count($rs) > 0) return $rs[0];
        $rs = array_values(array_filter($fileVersion['files'], function ($item) use ($id_or_name) {
            if (isset($item['name']) && $item['name'] === $id_or_name) return true;
            return false;
        }));
        Log::info($rs);
        if (count($rs) > 0) return $rs[0];
        return null;
    }
    public function downloadFile($id_or_name)
    {
        $json = $this->findFile($id_or_name);
        if ($json && isset($json['download'])) {
            $path = base_path(config('platform.appdir.root') . '/' . config('platform.updator.temps') . '/');
            File::ensureDirectoryExists($path);
            $path = $path . $id_or_name . '-' . time() . '.zip';
            if ($this->download($json['download'], $path)) return $path;
        }
        return null;
    }
    public function install($id_or_name)
    {
        $file = $this->downloadFile($id_or_name);
        if ($file) {
            return  $this->installLocal($file);
        }
        return false;
    }
    private function findFolderRoot($path)
    {
        if (File::exists($path . '/theme.json'))
            return [
                'type' => 'theme',
                'path' => $path,
                'info' => json_decode(file_get_contents($path . '/theme.json'), true)
            ];
        if (File::exists($path . '/plugin.json'))
            return [
                'type' => 'plugin',
                'path' => $path,
                'info' => json_decode(file_get_contents($path . '/plugin.json'), true)
            ];
        if (File::exists($path . '/module.json'))
            return [
                'type' => 'module',
                'path' => $path,
                'info' => json_decode(file_get_contents($path . '/module.json'), true)
            ];
        $array = File::directories($path);
        foreach ($array as $item) {
            $rs = $this->findFolderRoot($item);
            if ($rs != null) {
                return $rs;
            }
        }
        return null;
    }
    public function installLocal($file)
    {
        $path_folder = dirname($file) . '/temp-' . time();
        $this->unzip($file, $path_folder);
        $rs = $this->findFolderRoot($path_folder);
        if ($rs != null) {
            File::copyDirectory($rs['path'], path_by($rs['type'], $rs['info']['name']));
        }
        File::deleteDirectories($path_folder);
        File::deleteDirectories($path_folder);
        File::delete($file);
        return  $rs;
    }
    public function Load($path)
    {
        Theme::Load($path . '/themes');
        Plugin::Load($path . '/plugins');
        Module::Load($path . '/modules');
        //RouteEx::Load($path . '/routes/');
    }
}
