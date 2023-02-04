<?php

namespace OEngine\Platform\Support\Theme;

use Closure;

trait WithAsset
{
    private $arrScript = [];

    private $arrStyle = [];

    private $arrDataPage = [];

    public function setAsset($key, $value)
    {
        $this->arrDataPage[$key] = $value;
    }

    public function getAsset($key, $default = '')
    {
        return $this->arrDataPage[$key] ?? $default;
    }

    public function AddScript($local, $contentOrPath, $cdn = '', $priority = 20, $isLink = true)
    {
        if (!isset($this->arrScript[$local])) {
            $this->arrScript[$local] = [];
        }
        while (isset($this->arrScript[$local][$priority])) {
            $priority += 1;
        }
        $this->arrScript[$local][$priority] = compact('contentOrPath', 'cdn', 'isLink');
    }

    public function AddStyle($local, $contentOrPath, $cdn = '', $priority = 20, $isLink = true)
    {
        if (!isset($this->arrStyle[$local])) {
            $this->arrStyle[$local] = [];
        }
        while (isset($this->arrStyle[$local][$priority])) {
            $priority += 1;
        }
        $this->arrStyle[$local][$priority] = compact('contentOrPath', 'cdn', 'isLink');
    }

    public function loadAsset($local)
    {
        // Style
        if (isset($this->arrStyle[$local]) && count($this->arrStyle[$local]) > 0) {
            $styles = $this->arrStyle[$local];
            ksort($styles, SORT_NUMERIC);
            foreach ($styles as $key => $item) {
                if (isset($item['isLink']) && $item['isLink'] == true) {
                    echo '<link rel="stylesheet" priority="' . $key . '" href="' . $item['contentOrPath'] . '"/>';
                }
            }
            foreach ($styles as $key => $item) {
                if (!isset($item['isLink']) || $item['isLink'] == false) {
                    echo '<style type="text/css" priority="' . $key . '">';
                    if ($item['contentOrPath'] instanceof Closure) {
                        call_user_func_array($item['contentOrPath'], []);
                    } else {
                        echo $item['contentOrPath'];
                    }
                    echo '</style>';
                }
            }
        }
        //script
        if (isset($this->arrScript[$local]) && count($this->arrScript[$local]) > 0) {
            $scripts = $this->arrScript[$local];
            ksort($scripts, SORT_NUMERIC);
            foreach ($scripts as $key => $item) {
                if (isset($item['isLink']) && $item['isLink'] == true) {
                    echo '<script type="text/javascript" priority="' . $key . '" src="' . $item['contentOrPath'] . '"></script>';
                }
            }
            foreach ($scripts as $key => $item) {
                if (!isset($item['isLink']) || $item['isLink'] == false) {
                    echo '<script priority="' . $key . '">';
                    if ($item['contentOrPath'] instanceof Closure) {
                        call_user_func_array($item['contentOrPath'], []);
                    } else {
                        echo $item['contentOrPath'];
                    }
                    echo '</script>';
                }
            }
        }
        if (isset($this->arrScript[$local])) {
        }
    }
}
