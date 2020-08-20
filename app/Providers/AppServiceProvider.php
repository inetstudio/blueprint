<?php

namespace App\Providers;

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

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
        Relation::morphMap(
            [
                'meta' => 'InetStudio\MetaPackage\Meta\Models\MetaModel',
                'pages' => 'InetStudio\PagesPackage\Pages\Models\PageModel',
                'widgets' => 'InetStudio\WidgetsPackage\Widgets\Models\WidgetModel',
            ]
        );
    }

    public function register()
    {
        $loader = AliasLoader::getInstance();

        $uri = trim($this->app->request->getRequestUri(), '/');
        $isConsole = $this->app->runningInConsole();
        $monitoringStop = Carbon::createFromTimestamp(config('app.release_time', time()), config('app.timezone'))->addDays(7);
        $now = Carbon::now();

        if ($monitoringStop->greaterThan($now) || config('app.debug')) {
            $this->app->register('Sentry\Laravel\ServiceProvider');
            $loader->alias('Sentry', 'Sentry\Laravel\Facade');
        }

        if (Str::startsWith($uri, trim(config('app.url_prefix').'/back', '/')) || $isConsole) {
            $this->app->register('Collective\Html\HtmlServiceProvider');
            $this->app->register('Cviebrock\EloquentSluggable\ServiceProvider');
            $this->app->register('Tightenco\Ziggy\ZiggyServiceProvider');
            $this->app->register('Yajra\DataTables\ButtonsServiceProvider');
            $this->app->register('Yajra\DataTables\DataTablesServiceProvider');
            $this->app->register('Yajra\DataTables\FractalServiceProvider');
            $this->app->register('Yajra\DataTables\HtmlServiceProvider');

            $loader->alias('DataTables', 'Yajra\DataTables\Facades\DataTables');
            $loader->alias('Form', 'Collective\Html\FormFacade');
            $loader->alias('Html', 'Collective\Html\HtmlFacade');
        }

        if (Str::contains($uri, '/export') || $isConsole) {
            $this->app->register('Maatwebsite\Excel\ExcelServiceProvider');

            $loader->alias('Excel', 'Maatwebsite\Excel\Facades\Excel');
        }

        if ($isConsole) {
            $this->app->register('Laravelium\Sitemap\SitemapServiceProvider');
        }

        if ($this->app->isLocal()) {
            if ($isConsole) {
                $this->app->register('Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider');
            }

            if (config('app.debug') || $isConsole) {
                $this->app->register('BeyondCode\DumpServer\DumpServerServiceProvider');

                $this->app->register('Barryvdh\Debugbar\ServiceProvider');
                $loader->alias('Debugbar', 'Barryvdh\Debugbar\Facade');
            }
        }
    }
}
