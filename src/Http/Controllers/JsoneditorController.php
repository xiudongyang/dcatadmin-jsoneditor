<?php

namespace Dcat\Admin\Extension\JsonEditor\Http\Controllers;

use Dcat\Admin\Layout\Content;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class JsoneditorController extends Controller
{
    public function index(Request $request,Content $content)
    {
        return $content
            ->title('Title')
            ->description('Description123')
            ->body(view('jsoneditor::jsoneditor'));
    }
}
