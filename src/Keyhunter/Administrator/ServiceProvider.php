<?php namespace Keyhunter\Administrator;

use DaveJamesMiller\Breadcrumbs\Facade AS BreadcrumbsFacade;
use Illuminate\Foundation\AliasLoader;
use Collective\Html\FormFacade;
use Collective\Html\HtmlFacade;
use Collective\Html\HtmlServiceProvider;
use Intervention\Image\Facades\Image AS ImageFacade;

use DaveJamesMiller\Breadcrumbs\ServiceProvider as BreadcrumbsServiceProvider;
use Intervention\Image\ImageServiceProvider;
use Event;
use Illuminate\Config\Repository As Config;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer; // from 5,2
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider as Provider;
use Request;
use Route;
use Keyhunter\Administrator\Columns\Factory AS Columns;
use Keyhunter\Administrator\Actions\Factory AS Actions;
use Keyhunter\Administrator\Console\AdministratorInit;
use Keyhunter\Administrator\Console\AdministratorPage;
use Keyhunter\Administrator\Console\AdministratorSettings;
use Keyhunter\Administrator\Form\Builder AS FormBuilder;
use Keyhunter\Administrator\Schema\Factory AS Schema;
use Keyhunter\Multilingual\MultilingualServiceProvider;
use Keyhunter\Translatable\TranslatableServiceProvider;

class ServiceProvider extends Provider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../config'     => config_path(),
            __DIR__ . '/../../../public'  => public_path('administrator'),
            __DIR__ . '/../../routes.php' => app_path('Http/administrator/routes.php'),

            //todo: find better way to implement this stuff ...
            __DIR__ . '/../../database/migrations' => database_path('migrations/'),
            __DIR__ . '/../../database/seeds' => database_path('seeds/'),
        ]);

        //include our filters, view composers, and routes
        require_once __DIR__ . '/../../helpers.php';

        $this->loadRoutes();

        $this->initComposers();

        $this->initEvents();

        $this->swapUserProvider($this->app['hash'], $this->app['scaffold.config']->get('auth_model'));
    }

    /**
     * @param Hasher $hash
     * @param        $model
     *
     * @todo: Find better solution instead of just seeing to request uri
     */
    protected function swapUserProvider(Hasher $hash, $model)
    {
        if (preg_match('~^/admin~si', $this->app['request']->getRequestUri())) {
            $provider = new AuthUserProvider($hash, $model);

            $this->app['auth.driver']->setProvider($provider);
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/administrator.php',
            'administrator'
        );

        $this->registerContainers();

        $this->registerViewFinder();

        $this->registerCommands();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['scaffold.config', 'scaffold.navigation', 'scaffold.module'];
    }

    /**
     * Register package related commands
     */
    private function registerCommands()
    {
        $this->app->singleton('administrator:init', function () {
            return new AdministratorInit($filesystem = new Filesystem, new Composer($filesystem));
        });

        $this->app->singleton('administrator:page', function () {
            return new AdministratorPage(new Filesystem);
        });

        $this->app->singleton('administrator:settings', function () {
            return new AdministratorSettings(new Filesystem);
        });

        $this->commands(['administrator:init', 'administrator:page', 'administrator:settings']);
    }

    /**
     * Register package view location
     */
    public function registerViewFinder()
    {
        $this->loadViewsFrom(__DIR__ . '/../../views', 'administrator');

        $this->publishes([
            __DIR__ . '/../../views' => base_path('resources/views/vendor/administrator'),
        ]);
    }

    /**
     * Register package IoContainers
     */
    protected function registerContainers()
    {
        $this->app->singleton('scaffold.config', function ($app) {
            $config = $app['config']['administrator'];

            $settings = new Config($config);

            return $settings;
        });

        $this->app->singleton('scaffold.module', function ($app, array $path = []) {
            $globalConfig = $app['scaffold.config'];

            if (! $path) {
                $path = (array) $globalConfig->get('models_path');
            }

            // resolve module name when a custom controller@action should handle a route
            $page = $globalConfig->get('module_resolver', function () {
                // ensure we are in not on CLI mode
                if (app()->runningInConsole()) {
                    return null;
                }

                if (is_callable($page = \Route::input('page', /*Custom controllers (without named segment)*/Request::segment(2)))) {
                    $page = call_user_func($page);
                };

                return $page;
            });

            /*
            |-------------------------------------------------------
            | Standard Scaffolding pages
            |-------------------------------------------------------
            */
            if ($page) {
                $path = array_shift($path);
                $configFile = $path . '/' . $page . '.php';

                if (file_exists($configFile)) {
                    $config = require $configFile;

                    $module = new Config($config);
                    $module->set('page', $page);

                    return $module;
                }
            }

            return null;
        });

        /**
         * Eloquent model instance
         *
         * @example: App\User
         * @return \Keyhunter\Administrator\Repository
         */
        $this->app->singleton('scaffold.model', function ($app) {
            $module = $app['scaffold.module'];

            if ($module && ($model = $module->get('model'))) {
                $model = new $model();

                if (! $model instanceof RepositoryInterface) {
                    throw new Exception('Scaffolding model "' . $model->getTable() . '" should extend \Keyhunter\Administrator\Repository');
                }

                Form\Element::setRepository($model);

                return $model;
            }

            return null;
        });

        /**
         * Schema generated from main table used in current module
         *
         * @example: users
         * @return : \Keyhunter\Administrator\Schema\Factory
         */
        $this->app->singleton('scaffold.schema', function ($app) {
            return new Schema($app['db'], new Collection());
        });

        $this->app->singleton('scaffold.columns', function () {
            return new Columns(
                $this->getConfigValue('columns', [])
            );
        });

        $this->app->singleton('scaffold.actions', function () {
            return new Actions(
                $this->getConfigValue('actions', []),
                $this->getConfigValue('global_actions', [])
            );
        });

        $this->app->singleton('scaffold.fields', function () {
            return new FormBuilder(
                $this->getConfigValue('edit_fields')
            );
        });

        $this->app->singleton('scaffold.navigation', function ($app) {
            $pages = require($app['scaffold.config']['models_path'] . '/menu.php');

            return new Navigation($app, $app['url'], new PermissionChecker, $pages);
        });

        $this->app->singleton('scaffold.filter', function () {
            $collection = Collection::make([]);

            $filters = (array) $this->getConfigValue('filters');

            $filter = new Filter($collection, $this->app['request']);
            $filter->addElements($filters);

            return $filter;
        });

        $aliases = [
            'scaffold.filter' => [
                'Keyhunter\Administrator\Filters\Factory',
                'Keyhunter\Administrator\Filters\FilterInterface'
            ],
            'scaffold.model'  => ['Keyhunter\Administrator\Repository', 'Keyhunter\Administrator\RepositoryInterface'],
            'scaffold.schema' => [
                'Keyhunter\Administrator\Schema\Factory',
                'Keyhunter\Administrator\Schema\SchemaInterface'
            ],
        ];

        foreach ($aliases as $key => $aliases) {
            foreach ((array) $aliases as $alias) {
                $this->app->alias($key, $alias);
            }
        }

        $dependencies = [
            BreadcrumbsServiceProvider::class => [
                'Breadcrumbs' => BreadcrumbsFacade::class
            ],
            ImageServiceProvider::class       => [
                'Image' => ImageFacade::class
            ],
            HtmlServiceProvider::class => [
                'Html' => HtmlFacade::class,
                'Form' => FormFacade::class
            ],
            MultilingualServiceProvider::class,
            TranslatableServiceProvider::class
        ];

        foreach ($dependencies as $sp => $package) {
            if (is_string($package) && is_numeric($sp)) {
                $sp = $package;
                $package = null;
            }

            if (! $this->app->getProvider($sp)) {
                $this->app->register($sp);

                if (is_array($package)) {
                    foreach ($package as $alias => $facade) {
                        class_alias($facade, $alias);
                    }
                }
            }
        }
    }

    /**
     * Retrieve current model
     *
     * @param string $key
     * @param null   $default
     * @return mixed string|array|\Keyhunter\Administrator\Config\Model
     */
    protected function getConfigValue($key, $default = null)
    {
        return $this->app['scaffold.module']->get($key, $default);
    }

    protected function initComposers()
    {
        $this->app['view']->composers([
            'Keyhunter\Administrator\Composer@login'   => ['administrator::login'],
            'Keyhunter\Administrator\Composer@layout'  => ['administrator::layout'],
            'Keyhunter\Administrator\Composer@index'   => ['administrator::index'],
            'Keyhunter\Administrator\Composer@edit'    => [
                'administrator::edit', 'administrator::settings'
            ],
            'Keyhunter\Administrator\Composer@filters' => ['administrator::partials.filters']
        ]);
    }

    protected function loadRoutes()
    {
        if (($routes = app_path('Http/administrator/routes.php')) && file_exists($routes)) {
            require_once $routes;
        } else {
            require_once __DIR__ . '/../../routes.php';
        }
    }

    protected function initEvents()
    {
        if ($subscriber = $this->app['scaffold.config']->get('subscriber', '\Keyhunter\Administrator\Subscriber\AdminSubscriber')) {
            Event::subscribe($subscriber);
        }
    }
}