<?php

namespace Dcat\Admin\Extension\JsonEditor\Form;

use Dcat\Admin\Form\Field;

class Jsoneditor extends Field
{
    protected static $js = [
        '@extension/jsoneditor/jsoneditor.min.js',
    ];

    protected static $css = [
        '@extension/jsoneditor/jsoneditor.min.css',
    ];


    protected $view = 'jsoneditor::jsoneditor';


    public function __construct($column, $arguments = [])
    {
        parent::__construct($column, $arguments);
    }


    public function render()
    {

        $json = old($this->column, $this->value());
        $diffJson = json_decode($this->placeholder());
        if (empty($json)) {
            $json = '{}';
        }
        if (!is_string($json)) {
            $json = json_encode($json);
        } else {
            $json = json_encode(json_decode($json));   //兼容json里有类似</p>格式，首次初始化显示会丢失的问题
        }
        $diff = false;
        $this->value = $json;
        if (!empty($diffJson)) {
            $this->diff($json, json_encode($diffJson));
            $diff = true;
        }else{
            $this->standard($json);
        }
        $this->addVariables(['diff' => $diff]);
        return parent::render();
    }

    public function standard($json){
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
    }

    public function diff($json, $diff){
        $this->script = <<<EOT
  const containerLeft = document.getElementById('standard')
  const containerRight = document.getElementById('{$this->id}')

  function findNodeInJson(json, path){
    if(!json || path.length ===0) {
      return {field: undefined, value: undefined}
    }
    const first = path[0]
    const remainingPath = path.slice(1)

    if(remainingPath.length === 0) {
      return {field: (typeof json[first] !== 'undefined' ? first : undefined), value: json[first]}
    } else {
      return findNodeInJson(json[first], remainingPath)
    }
  }

  function onClassName({ path, field, value }) {
    const thisNode = findNodeInJson(jsonRight, path)
    const oppositeNode = findNodeInJson(jsonLeft, path)
    let isValueEqual = JSON.stringify(thisNode.value) === JSON.stringify(oppositeNode.value)

    if(Array.isArray(thisNode.value) && Array.isArray(oppositeNode.value)) {
      isValueEqual = thisNode.value.every(function (e) {
        return oppositeNode.value.includes(e)
      })
    }

    if (thisNode.field === oppositeNode.field && isValueEqual) {
      return 'the_same_element'
    } else {
      return 'different_element'
    }
  }

  const optionsLeft = {
    mode: 'tree',
    onError: function (err) {
      alert(err.toString())
    },
    onClassName: onClassName,
    onChangeJSON: function (j) {
      jsonLeft = j
      console.log(j)
      window.editorRight.refresh()
    }
  }

  const optionsRight = {
    mode: 'tree',
    onError: function (err) {
      alert(err.toString())
    },
    onClassName: onClassName,
    onChangeJSON: function (j) {
      jsonRight = j
      console.log(j);
      window.editorLeft.refresh()
    }
  }

  let jsonLeft = {$json}

  let jsonRight = {$diff}

  window.editorLeft = new JSONEditor(containerLeft, optionsLeft, jsonLeft)
  window.editorRight = new JSONEditor(containerRight, optionsRight, jsonRight)
EOT;

    }
}
