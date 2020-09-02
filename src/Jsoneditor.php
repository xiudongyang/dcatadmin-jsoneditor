<?php

namespace Dcat\Admin\Extension\JsonEditor;

use Dcat\Admin\Extension;

class Jsoneditor extends Extension
{
    const NAME = 'jsoneditor';

    protected $serviceProvider = JsoneditorServiceProvider::class;

    protected $composer = __DIR__.'/../composer.json';

    protected $assets = __DIR__.'/../resources/assets';

    protected $views = __DIR__.'/../resources/views';

//    protected $lang = __DIR__.'/../resources/lang';

//    protected $menu = [
//        'title' => 'Jsoneditor',
//        'path'  => 'jsoneditor',
//        'icon'  => '',
//    ];

}
