<?php

namespace App\Providers;

use Illuminate\Support\Str;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

/**
 * Class AppServiceProvider.
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    public function register()
    {
        $loader = AliasLoader::getInstance();

        if (Str::startsWith($this->app->request->getRequestUri(), '/back') || app()->runningInConsole()) {
            $this->app->register('Collective\Html\HtmlServiceProvider');
            $this->app->register('Cviebrock\EloquentSluggable\ServiceProvider');
            $this->app->register('Maatwebsite\Excel\ExcelServiceProvider');
            $this->app->register('Yajra\DataTables\ButtonsServiceProvider');
            $this->app->register('Yajra\DataTables\DataTablesServiceProvider');
            $this->app->register('Yajra\DataTables\FractalServiceProvider');
            $this->app->register('Yajra\DataTables\HtmlServiceProvider');

            $loader->alias('DataTables', 'Yajra\DataTables\Facades\DataTables');
            $loader->alias('Excel', 'Maatwebsite\Excel\Facades\Excel');
            $loader->alias('Form', 'Collective\Html\FormFacade');
            $loader->alias('Html', 'Collective\Html\HtmlFacade');
        }

        if (config('app.debug') || app()->runningInConsole()) {
            $this->app->register('Barryvdh\Debugbar\ServiceProvider');
            $loader->alias('Debugbar', 'Barryvdh\Debugbar\Facade');
        }
    }
}
