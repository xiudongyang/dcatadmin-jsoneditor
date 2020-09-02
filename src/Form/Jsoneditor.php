<?php

namespace Dcat\Admin\Extension\JsonEditor\Form;

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

    public function __construct($column, $arguments = [])
    {
        parent::__construct($column, $arguments);
    }


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
                                       'mode' => 'code',
                                       'modes' => ['code', 'form', 'text', 'tree', 'view'], // allowed modes
                                   ]
                               ]);

        $this->script = <<<EOT
// create the editor
var container = document.getElementById("{$this->id}");
 const options = {
    mode: 'code',
    language:'en',
    modes: ['code', 'tree', 'form', 'text',  'view', 'preview'], // allowed modes
    onError: function (err) {
      alert(err.toString())
    },
    onModeChange: function (newMode, oldMode) {
      console.log('Mode switched from', oldMode, 'to', newMode)
    },
    onChangeText: function(jsonString){
        $('input[id={$this->id}_input]').val(JSON.stringify(window['editor_{$this->id}'].get()));
    }
  }
window['editor_{$this->id}'] = new JSONEditor(container, options, {$json});
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
