<?php

namespace App\Providers;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Routing\Router;
use Illuminate\Support\Carbon;
use Collective\Html\FormBuilder;
use Laravel\Scout\EngineManager;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Elasticsearch\ClientBuilder as ElasticBuilder;

class InetStudioServiceProvider extends ServiceProvider
{
    protected bool $configIsCached = false;

    public function boot(Router $router): void
    {
        $this->configIsCached = $this->app->configurationIsCached();

        $this->bootAclPackage($router);
        $this->bootAdminPanelPackage();
        $this->bootCachePackage();
        $this->bootCaptchaPackage();
        $this->bootFeedbackPackage();
        $this->bootMainpagePackage();
        $this->bootMetaPackage();
        $this->bootPagesPackage();
        $this->bootSearchPackage();
        $this->bootSimpleCountersPackage();
        $this->bootSitemapPackage();
        $this->bootUploadsPackage();
        $this->bootWidgetsPackage();
    }

    public function register(): void
    {
    }

    protected function bootAclPackage(Router $router): void
    {
        // Activations
        $this->loadRoutesFrom(__DIR__.'/../../vendor/inetstudio/acl/entities/activations/routes/web.php');

        $this->loadViewsFrom(__DIR__.'/../../vendor/inetstudio/acl/entities/activations/resources/views', 'admin.module.acl.activations');

        $this->loadTranslationsFrom(__DIR__.'/../../vendor/inetstudio/acl/entities/activations/resources/lang', 'admin.module.acl.activations');

        Event::listen(
            'InetStudio\ACL\Activations\Contracts\Events\Front\UnactivatedLoginEventContract',
            'InetStudio\ACL\Activations\Contracts\Listeners\Front\SendActivateNotificationListenerContract'
        );

        Event::listen(
            'InetStudio\ACL\Activations\Contracts\Events\Front\SocialActivatedEventContract',
            'InetStudio\ACL\Activations\Contracts\Listeners\Front\SendActivateNotificationListenerContract'
        );

        // Passwords
        $this->loadRoutesFrom(__DIR__.'/../../vendor/inetstudio/acl/entities/passwords/routes/web.php');

        $this->loadViewsFrom(__DIR__.'/../../vendor/inetstudio/acl/entities/passwords/resources/views', 'admin.module.acl.passwords');

        $this->loadTranslationsFrom(__DIR__.'/../../vendor/inetstudio/acl/entities/passwords/resources/lang', 'admin.module.acl.passwords');

        Validator::extend('check_password', function ($attribute, $value, $parameters, $validator) {
            return ($value == '' or Hash::check($value, current($parameters)));
        });

        // Permissions
        $this->loadRoutesFrom(__DIR__.'/../../vendor/inetstudio/acl/entities/permissions/routes/web.php');

        $this->loadViewsFrom(__DIR__.'/../../vendor/inetstudio/acl/entities/permissions/resources/views', 'admin.module.acl.permissions');

        FormBuilder::component('permissions', 'admin.module.acl.permissions::back.forms.fields.permissions', ['name' => null, 'value' => null, 'attributes' => null]);

        // Profiles

        // Roles
        if ($this->app->runningInConsole()) {
            $this->commands([
                'InetStudio\ACL\Roles\Console\Commands\CreateRolesCommand',
            ]);
        }

        $this->loadRoutesFrom(__DIR__.'/../../vendor/inetstudio/acl/entities/roles/routes/web.php');

        $this->loadViewsFrom(__DIR__.'/../../vendor/inetstudio/acl/entities/roles/resources/views', 'admin.module.acl.roles');

        FormBuilder::component('roles', 'admin.module.acl.roles::back.forms.fields.roles', ['name' => null, 'value' => null, 'attributes' => null]);

        Blade::if('withoutRole', function ($role) {
            return ! app('laratrust')->hasRole($role);
        });

        // Users
        if ($this->app->runningInConsole()) {
            $this->commands([
                'InetStudio\ACL\Users\Console\Commands\CreateAdminCommand',
                'InetStudio\ACL\Users\Console\Commands\CreateFoldersCommand',
            ]);
        }

        $this->loadRoutesFrom(__DIR__.'/../../vendor/inetstudio/acl/entities/users/routes/web.php');

        $this->loadViewsFrom(__DIR__.'/../../vendor/inetstudio/acl/entities/users/resources/views', 'admin.module.acl.users');

        $this->loadTranslationsFrom(__DIR__.'/../../vendor/inetstudio/acl/entities/users/resources/lang', 'admin.module.acl.users');

        if (! $this->configIsCached) {
            $this->mergeConfigFrom(
                __DIR__.'/../../vendor/inetstudio/acl/entities/users/config/services.php', 'services'
            );

            $this->mergeConfigFrom(
                __DIR__.'/../../vendor/inetstudio/acl/entities/users/config/filesystems.php', 'filesystems.disks'
            );

            $this->mergeConfigFrom(
                __DIR__.'/../../packages/acl/entities/users/config/acl_users.php',
                'acl_users'
            );
        }

        view()->composer('admin.module.acl.users::back.partials.analytics.statistic', function ($view) {
            $registrations = app()->make('InetStudio\ACL\Users\Contracts\Repositories\UsersRepositoryContract')
                ->getAllItems(true)
                ->select(['activated', \DB::raw('count(*) as total')])
                ->groupBy('activated')
                ->get();

            $view->with('registrations', $registrations);
        });

        FormBuilder::component('user', 'admin.module.acl.users::back.forms.fields.user', ['name' => null, 'value' => null, 'attributes' => null]);

        // Acl
        $this->loadViewsFrom(__DIR__.'/../../vendor/inetstudio/acl/package/resources/views', 'admin.module.acl');

        if (! $this->configIsCached) {
            $this->mergeConfigFrom(
                __DIR__.'/../../packages/acl/package/config/acl.php',
                'acl'
            );
        }

        Config::set('laratrust.models', config('acl.models'));
        Config::set('laratrust.user_models', config('acl.user_models'));
        Config::set('auth.providers.users.model', config('acl.user_models.users'));

        $router->aliasMiddleware('back.auth', 'InetStudio\ACL\Contracts\Http\Middleware\Back\AdminAuthenticateContract');
        $router->aliasMiddleware('back.guest', 'InetStudio\ACL\Contracts\Http\Middleware\Back\RedirectIfAuthenticatedContract');
        $router->aliasMiddleware('acl.users.activated', 'InetStudio\ACL\Contracts\Http\Middleware\Front\CheckActivationContract');

        $router->aliasMiddleware('role', 'Laratrust\Middleware\LaratrustRole');
        $router->aliasMiddleware('permission', 'Laratrust\Middleware\LaratrustPermission');
        $router->aliasMiddleware('ability', 'Laratrust\Middleware\LaratrustAbility');
    }

    protected function bootAdminPanelPackage(): void
    {
        // Base
        if ($this->app->runningInConsole()) {
            $this->commands([
                'InetStudio\AdminPanel\Base\Console\Commands\RoutesCache',
            ]);
        }

        $this->loadRoutesFrom(__DIR__.'/../../vendor/inetstudio/admin-panel/entities/base/routes/web.php');

        if (! $this->configIsCached) {
            foreach (['audit', 'media-library', 'sentry', 'ziggy'] as $config) {
                $this->app['config']->set($config, array_merge(
                    $this->app['config']->get($config, []), require __DIR__.'/../../packages/admin-panel/entities/base/config/'.$config.'.php'
                ));
            }
        }

        // Admin panel
        $this->loadRoutesFrom(__DIR__.'/../../vendor/inetstudio/admin-panel/routes/web.php');

        $this->loadViewsFrom(__DIR__.'/../../vendor/inetstudio/admin-panel/resources/views', 'admin');
        view()->getFinder()->prependNamespace('admin', __DIR__.'/../../packages/admin-panel/resources/views');

        $this->loadTranslationsFrom(__DIR__.'/../../vendor/inetstudio/admin-panel/resources/lang', 'admin');

        FormBuilder::component('info', 'admin::back.forms.blocks.info', ['name' => null, 'value' => null, 'attributes' => null]);
        FormBuilder::component('buttons', 'admin::back.forms.blocks.buttons', ['name', 'value', 'attributes']);

        FormBuilder::component('string', 'admin::back.forms.fields.string', ['name', 'value', 'attributes']);
        FormBuilder::component('passwords', 'admin::back.forms.fields.passwords', ['name', 'value', 'attributes']);
        FormBuilder::component('radios', 'admin::back.forms.fields.radios', ['name', 'value', 'attributes']);
        FormBuilder::component('checks', 'admin::back.forms.fields.checks', ['name', 'value', 'attributes']);
        FormBuilder::component('datepicker', 'admin::back.forms.fields.datepicker', ['name', 'value', 'attributes']);
        FormBuilder::component('wysiwyg', 'admin::back.forms.fields.wysiwyg', ['name', 'value', 'attributes']);
        FormBuilder::component('dropdown', 'admin::back.forms.fields.dropdown', ['name', 'value', 'attributes']);

        Blade::directive('loadFromModules', function ($expression) {
            $namespaces = view()->getFinder()->getHints();

            $result = '';

            foreach ($namespaces as $namespace => $paths) {
                if (strpos($namespace, 'admin.module') !== false) {
                    $fullExpression = $namespace.'::'.$expression;

                    $result .= "<?php
                        if (\$__env->exists('{$fullExpression}')) echo \$__env->make('{$fullExpression}', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render();
                     ?>\r\n";
                }
            }

            return $result;
        });

        Blade::directive('pushonce', function ($expression) {
            $domain = explode(':', trim(substr($expression, 1, -1)));
            $push_name = $domain[0];
            $push_sub = $domain[1];
            $isDisplayed = '__pushonce_'.$push_name.'_'.$push_sub;

            return "<?php if(!isset(\$__env->{$isDisplayed})): \$__env->{$isDisplayed} = true; \$__env->startPush('{$push_name}'); ?>";
        });

        Blade::directive('endpushonce', function ($expression) {
            return '<?php $__env->stopPush(); endif; ?>';
        });

        Blade::if('env', function ($env) {
            return app()->environment($env);
        });

        Blade::if('monitoring', function () {
            $monitoringStop = \Carbon\Carbon::createFromTimestamp(config('app.release_time', time()), config('app.timezone'))->addDays(3);
            $now = Carbon::now();

            return $monitoringStop->greaterThan($now);
        });

        Blade::directive('inline', function ($expression) {
            if (! file_exists(public_path(str_replace("'", '', $expression)))) {
                return '';
            }

            $include =
                "<?php echo str_replace([
                        '(../assets/img',
                        '(../assets/fonts',
                        '(/assets/img',
                        '(/assets/fonts',
                    ], [
                        '('.asset('assets/img'),
                        '('.asset('assets/fonts'),
                        '('.asset('assets/img'),
                        '('.asset('assets/fonts'),
                    ], file_get_contents(public_path().{$expression})); ?>\n";

            if (Str::endsWith($expression, ".html'")) {
                return $include;
            }
            if (Str::endsWith($expression, ".css'")) {
                return "<style>\n".$include.'</style>';
            }
            if (Str::endsWith($expression, ".js'")) {
                return "<script>\n".$include.'</script>';
            }

            return '';
        });

        Arr::macro('changeKeysCase', function (array $arr, int $case = CASE_LOWER) {
            $case = ($case == CASE_LOWER) ? MB_CASE_LOWER : MB_CASE_UPPER;

            $returnArray = [];

            foreach ($arr as $key => $value) {
                $returnArray[mb_convert_case($key, $case, 'UTF-8')] = $value;
            }

            return $returnArray;
        });

        Arr::macro('arraySumIdenticalKeys', function () {
            $arrays = func_get_args();
            $keys = array_keys(array_reduce($arrays, function ($keys, $arr) {
                return $keys + $arr;
            }, []));
            $sums = [];

            foreach ($keys as $key) {
                $sums[$key] = 0;

                foreach ($arrays as $arr) {
                    $sums[$key] = $sums[$key] + ((isset($arr[$key])) ? $arr[$key] : 0);
                }
            }

            return $sums;
        });

        Str::macro('hideEmail', function ($value) {
            $partials = explode("@", $value);
            $service = array_pop($partials);

            $name = implode('@', $partials);
            $nameLen = strlen($name);

            $startHidePos = floor($nameLen*0.33);
            $endHidePos = floor($nameLen*0.66);

            return (($nameLen == 1) ? '*' : (substr($name,0, $startHidePos)
                    .str_repeat('*', ($endHidePos-$startHidePos))
                    .substr($name,$endHidePos, $nameLen)))
                .'@'.$service;
        });

        Carbon::macro('formatTime', function (string $strTime) {
            $monthsNames = [1 => 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'];

            $carbonTime = self::parse($strTime);

            $day = $carbonTime->day;
            $month = (isset($monthsNames[$carbonTime->month])) ? $monthsNames[$carbonTime->month] : '';
            $time = sprintf('%02d', $carbonTime->hour).':'.sprintf('%02d', $carbonTime->minute);

            return trim($day.' '.$month).', '.$time;
        });

        Carbon::macro('formatDateToRus', function (string $strTime) {
            $monthsNames = [1 => 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'];

            $carbonTime = self::parse($strTime);

            $day = $carbonTime->day;
            $month = (isset($monthsNames[$carbonTime->month])) ? $monthsNames[$carbonTime->month] : '';
            $year = $carbonTime->year;

            return trim($day.' '.$month).' '.$year;
        });

        Collection::macro('ksort', function () {
            krsort($this->items);

            return $this;
        });
    }

    protected function bootCachePackage(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands(
            [
                'InetStudio\CachePackage\Cache\Contracts\Console\Commands\GenerateCacheCommandContract',
            ]
        );
    }

    protected function bootCaptchaPackage(): void
    {
        if (! $this->configIsCached) {
            $this->mergeConfigFrom(
                __DIR__.'/../../packages/captcha/entities/captcha/config/captcha.php',
                'captcha'
            );
        }
    }

    protected function bootFeedbackPackage(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../../vendor/inetstudio/feedback/entities/feedback/routes/web.php');

        $this->loadViewsFrom(__DIR__.'/../../vendor/inetstudio/feedback/entities/feedback/resources/views', 'admin.module.feedback');
        view()->getFinder()->prependNamespace('admin.module.feedback', __DIR__.'/../../packages/feedback/entities/feedback/resources/views');
        $this->loadViewsFrom(__DIR__.'/../../packages/feedback/entities/feedback/resources/views', 'packages.feedback.app');

        $this->loadTranslationsFrom(__DIR__.'/../../vendor/inetstudio/feedback/entities/feedback/resources/lang', 'feedback');

        if (! $this->configIsCached) {
            $this->mergeConfigFrom(
                __DIR__.'/../../packages/feedback/entities/feedback/config/feedback.php',
                'feedback'
            );
        }

        Event::listen('InetStudio\ACL\Activations\Contracts\Events\Front\ActivatedEventContract', 'InetStudio\FeedbackPackage\Feedback\Contracts\Listeners\Front\AttachUserToItemsListenerContract');
        Event::listen('InetStudio\ACL\Users\Contracts\Events\Front\SocialRegisteredEventContract', 'InetStudio\FeedbackPackage\Feedback\Contracts\Listeners\Front\AttachUserToItemsListenerContract');
    }

    protected function bootMainpagePackage(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../../packages/mainpage/entities/mainpage/routes/web.php');

        $this->loadViewsFrom(__DIR__.'/../../packages/mainpage/entities/mainpage/resources/views', 'packages.mainpage.app');
    }

    protected function bootMetaPackage(): void
    {
        $this->loadViewsFrom(__DIR__.'/../../vendor/inetstudio/meta/entities/meta/resources/views', 'admin.module.meta');

        if (! $this->configIsCached) {
            $this->mergeConfigFrom(
                __DIR__.'/../../vendor/inetstudio/meta/entities/meta/config/services.php', 'services'
            );

            $this->mergeConfigFrom(
                __DIR__.'/../../packages/meta/entities/meta/config/meta.php',
                'meta'
            );
        }

        FormBuilder::component(
            'meta', 'admin.module.meta::back.forms.groups.meta', ['name' => null, 'value' => null, 'attributes' => null]
        );
        FormBuilder::component(
            'social_meta', 'admin.module.meta::back.forms.groups.social_meta',
            ['name' => null, 'value' => null, 'attributes' => null]
        );
    }

    protected function bootPagesPackage(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../../vendor/inetstudio/pages/entities/pages/routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/../../packages/pages/entities/pages/routes/web.php');

        $this->loadViewsFrom(__DIR__.'/../../vendor/inetstudio/pages/entities/pages/resources/views', 'admin.module.pages');
        $this->loadViewsFrom(__DIR__.'/../../packages/pages/entities/pages/resources/views', 'packages.pages.app');
        view()->getFinder()->prependNamespace('admin.module.pages', __DIR__.'/../../packages/pages/entities/pages/resources/views');

        if (! $this->configIsCached) {
            $this->mergeConfigFrom(
                __DIR__.'/../../vendor/inetstudio/pages/entities/pages/config/filesystems.php',
                'filesystems.disks'
            );

            $this->mergeConfigFrom(
                __DIR__.'/../../packages/pages/entities/pages/config/pages.php',
                'pages'
            );
        }
    }

    protected function bootSearchPackage(): void
    {
        app(EngineManager::class)->extend('elasticsearch', function () {
            return app()->make(
                'InetStudio\SearchPackage\Search\Contracts\Engines\ElasticSearchEngineContract',
                [
                    'elastic' => ElasticBuilder::create()
                        ->setHosts(config('scout.elasticsearch.hosts'))
                        ->build(),
                    'index' => config('scout.elasticsearch.index'),
                ]
            );
        });
    }

    protected function bootSimpleCountersPackage(): void
    {
        if (! $this->configIsCached) {
            $this->mergeConfigFrom(
                __DIR__.'/../../packages/simple-counters/entities/counters/config/counters.php',
                'counters'
            );
        }
    }

    protected function bootSitemapPackage(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                'InetStudio\SitemapPackage\Sitemap\Contracts\Console\Commands\GenerateSitemapCommandContract',
            ]);
        }

        if (! $this->configIsCached) {
            $this->mergeConfigFrom(
                __DIR__.'/../../packages/sitemap/entities/sitemap/config/sitemap.php',
                'sitemap'
            );
        }
    }

    protected function bootUploadsPackage(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../../vendor/inetstudio/uploads/routes/web.php');

        if (! $this->configIsCached) {
            $this->mergeConfigFrom(
                __DIR__.'/../../vendor/inetstudio/uploads/config/filesystems.php', 'filesystems.disks'
            );
        }

        $this->loadViewsFrom(__DIR__.'/../../vendor/inetstudio/uploads/resources/views', 'admin.module.uploads');
        view()->getFinder()->prependNamespace('admin.module.uploads', __DIR__.'/../../packages/uploads/entities/uploads/resources/views');

        FormBuilder::component('crop', 'admin.module.uploads::back.forms.fields.crop', ['name', 'value', 'attributes']);
        FormBuilder::component('files', 'admin.module.uploads::back.forms.fields.files', ['name', 'value', 'attributes']);
        FormBuilder::component('imagesStack', 'admin.module.uploads::back.forms.stacks.images', ['name', 'value', 'attributes']);
    }

    protected function bootWidgetsPackage(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../../vendor/inetstudio/widgets/entities/widgets/routes/web.php');

        $this->loadViewsFrom(__DIR__.'/../../vendor/inetstudio/widgets/entities/widgets/resources/views', 'admin.module.widgets');

        if (! $this->configIsCached) {
            $this->mergeConfigFrom(
                __DIR__.'/../../vendor/inetstudio/widgets/entities/widgets/config/filesystems.php', 'filesystems.disks'
            );
        }

        FormBuilder::component(
            'widgets',
            'admin.module.widgets::back.forms.fields.widgets',
            ['name' => null, 'value' => null, 'attributes' => null]
        );

        Blade::directive('widget', function ($expression) {
            $widgetsService = app()->make('InetStudio\WidgetsPackage\Widgets\Contracts\Services\Front\ItemsServiceContract');

            return $widgetsService->getItemContent($expression);
        });
    }
}
