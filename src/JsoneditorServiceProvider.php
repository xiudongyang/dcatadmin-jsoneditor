<?php

namespace Dcat\Admin\Extension\JsonEditor;

use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Illuminate\Support\ServiceProvider;

class JsoneditorServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        $extension = Jsoneditor::make();

        if ($views = $extension->views()) {
            $this->loadViewsFrom($views, Jsoneditor::NAME);
        }

        if ($lang = $extension->lang()) {
            $this->loadTranslationsFrom($lang, Jsoneditor::NAME);
        }

        if ($migrations = $extension->migrations()) {
            $this->loadMigrationsFrom($migrations);
        }

        $this->app->booted(function () use ($extension) {
            $extension->routes(__DIR__.'/../routes/web.php');
        });
        Form::extend('jsoneditor', \Dcat\Admin\Extension\JsonEditor\Form\Jsoneditor::class);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
    }
}
