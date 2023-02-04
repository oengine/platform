<?php

namespace OEngine\Core\Livewire;

use OEngine\Core\Builder\Form\FieldBuilder;
use OEngine\Core\Loader\DashboardLoader;
use OEngine\Core\Support\Config\WidgetConfig;

class Widget extends Component
{
    public $key_widget = '';
    public $widget_data = [];
    public $widget_title = "";
    public $widget_type = "";
    public $widget_icon = "";
    public $widget_class = "";
    public $widget_view = "";
    public $widget_column = "";
    public $widget_include = "";
    public $widget_poll = "";

    protected WidgetConfig $widget_config;
    public function process_data()
    {
        if (!$this->key_widget) return;
        $this->widget_config = DashboardLoader::getDataByKey($this->key_widget);
        $this->widget_title =  $this->widget_config->getTitle();
        $this->widget_icon =  $this->widget_config->getIcon();
        $this->widget_type =  $this->widget_config->getType('index');
        $this->widget_class =  $this->widget_config->getClass('border-primary');
        $this->widget_column =  $this->widget_config->getColumn(FieldBuilder::Col6);
        $func_data =  $this->widget_config->getFuncData();
        $this->widget_data = is_callable($func_data) ? $func_data() : $func_data;
        $this->widget_poll =  $this->widget_config->getPoll();
        $this->widget_include =  $this->widget_config->getInclude();
        // $this->widget_column =  $this->widget_config->getColumn();
    }
    public function mount($key_widget = '')
    {
        $this->key_widget = $key_widget;
        if (method_exists($this, 'process_data')) {
            $this->process_data();
        }
    }
}
