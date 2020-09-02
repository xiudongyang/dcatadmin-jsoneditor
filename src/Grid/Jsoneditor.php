<?php

namespace Dcat\Admin\Extension\JsonEditor\Grid;

use Dcat\Admin\Form\Field;

class Jsoneditor extends Field
{
    protected static $js = [
        '@extension/jsoneditor/jsoneditor.js',
    ];

    protected static $css = [
        '@extension/jsoneditor/jsoneditor.css',
    ];


    protected $view = 'jsoneditor::jsoneditor';


    public function render()
    {
        $json = old($this->column, $this->value());
        if (empty($json)) {
            $json = '{}';
        }

        if (!is_string($json)) {
            $json = json_encode($json);
        } else {
            $json = json_encode(json_decode($json));   //兼容json里有类似</p>格式，首次初始化显示会丢失的问题
        }

        $this->value = $json;

        $options = json_encode([
                                   [
                                       'mode' => 'tree',
                                       'modes' => ['code', 'form', 'text', 'tree', 'view'], // allowed modes
                                   ]
                               ]);

//        if (empty($options)) {
//            $options = "{}";
//        }
        $this->script = <<<EOT
// create the editor
var container = document.getElementById("{$this->id}");
var options = {$options};
window['editor_{$this->id}'] = new JSONEditor(container, options);
// set json
var json = {$json};
window['editor_{$this->id}'].set(json);
// get json
$('button[type="submit"]').click(function() {
var json = window['editor_{$this->id}'].get()
$('input[id={$this->id}_input]').val(JSON.stringify(json))
})
EOT;

        return parent::render();
    }
}
