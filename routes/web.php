<?php

use Dcat\Admin\Extension\JsonEditor\Http\Controllers;

Route::any('jsoneditor', Controllers\JsoneditorController::class.'@index');
