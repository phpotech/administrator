<?php namespace Keyhunter\Administrator;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Session\Session;
use Keyhunter\Administrator\Form\Element;

class Composer {

    /**
     * @var \Illuminate\Config\Repository
     */
    protected $config;

    /**
     * @var Application
     */
    private $application;

    /**
     * @var Route
     */
    private $route;

    /**
     * @var Session
     */
    private $session;
    /**
     * @var File
     */
    private $files;
    /**
     * @var Request
     */
    private $request;

    public function __construct(Application $application, Router $route, Session $session, Filesystem $files, Request $request)
    {
        $this->application = $application;
        $this->route       = $route;
        $this->session     = $session;
        $this->files       = $files;

        $this->config      = $this->application->make('scaffold.config');
        $this->request     = $request;
    }

    /**
     * Fire when administrator::layout called
     *
     * @param View $view
     */
    public function layout(View $view)
    {
        $settingsPages   = $this->collectSettingsPages();

        $this->publishBreadcrumbs($view);

        $view->with([
            'mainConfig'    => $this->config,
            'navigation'    => $this->application->make('scaffold.navigation'),
            'assets'        => $this->config->get('assets_path'),
            'settingsPages' => $settingsPages
        ]);
    }

    public function login(View $view)
    {
        $view->with([
            'assets'        => $this->config->get('assets_path'),
        ]);
    }

    /**
     * @param View $view
     */
    public function index(View $view)
    {
        /*
        |-------------------------------------------------------
        | Initialize all the stuff we need to render the page
        |-------------------------------------------------------
        */
        $columns        = $this->application->make('scaffold.columns');
        $actionFactory  = $this->application->make('scaffold.actions');

        $columns        = $columns->getColumns();
        $globalActions  = $actionFactory->getGlobalActions();
        $actions        = $actionFactory->getActions();

        $this->publishQueryString($view);

        $view->description      = $this->module()->get('description');

        /*
        |-------------------------------------------------------
        | Variables
        |-------------------------------------------------------
        */
        $view->actionFactory    = $actionFactory;

        $view->modelName        = $this->route->input('page');

        $view->columns          = $columns;
        $view->actions          = $actions;
        $view->globalActions    = $globalActions;

        $view->hasActions       = $actions->count();

        /*
        |-------------------------------------------------------
        | Messages
        |-------------------------------------------------------
        |
        | Show success|error messages during some operations
        |
        */
        $view->message = $this->session->get('message');
//        $view->errof   = $this->session->get('error');
        $view->errors   = $this->session->get('error');
    }

    public function filters(View $view)
    {
        $view->filter  = $this->application->make('scaffold.filter')->getElements();

        $this->publishQueryString($view);
    }

    public function edit(View $view)
    {
        $this->publishQueryString($view);

        /*
        |-------------------------------------------------------
        | Factories
        |-------------------------------------------------------
        */
        $view->fieldFactory = $this->application['scaffold.fields'];

        /*
        |-------------------------------------------------------
        | View Variables
        |-------------------------------------------------------
        */
        $view->pageTitle    = $this->module()->get('title');
        $view->assets       = $this->config->get('assets_path');
        $view->modelName    = $this->route->input('page');
    }

    /**
     * @return array
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function collectSettingsPages()
    {
        $settingPages = [];
        foreach ($this->files->files($this->config['settings_path']) as $page)
        {
            $config = $this->files->getRequire($page);

            $page = str_replace('.php', '', basename($page));

            $settingPages[$page] = $config['title'];
        };

        return $settingPages;
    }

    private function module()
    {
        return $this->application->make('scaffold.module');
    }

    /**
     * @param View $view
     */
    protected function publishQueryString(View $view)
    {
        if (! empty($queryString = $this->module()->get('append_query_string', [])))
        {
            $queryString = $this->request->only($queryString);
        }
        $queryString = new QueryString($queryString);

        $view->queryString = $queryString;
    }

    /**
     * Publish breadcrumbs
     *
     * @return array
     */
    protected function publishBreadcrumbs($view)
    {
        $breadcrumbs = $this->fetchBreadcrumbs();

        $page  = last(array_keys($breadcrumbs));
        $title = head($breadcrumbs[$page]);

        $this->registerBreadcrumbs($breadcrumbs);

        $view->with([
            'title'         => $title,
            'breadcrumbs'   => \Breadcrumbs::render($page)
        ]);
    }

    /**
     * Fetch breadcrumbs from model config file
     * or generate default pages
     *
     * @return string
     */
    protected function fetchBreadcrumbs()
    {
        $action = last(explode('@', $this->route->getCurrentRoute()->getActionName()));
        $page   = $this->module()->get('page');
        $title  = $this->module()->get('title');

        if (! $breadcrumbs = $this->module()->get("breadcrumbs.{$action}"))
        {
            $breadcrumbs[$page] = [$title, route('admin_model_index', ['page' => $page])];

            switch ($action)
            {
                case 'edit':
                case 'create':
                    $breadcrumbs["{$page}.{$action}"] = [ucfirst($action), null];
                    break;
            }
        }

        if (is_callable($breadcrumbs))
        {
            return call_user_func($breadcrumbs);
        }

        return $breadcrumbs;
    }

    /**
     * Register breadcrumbs pages
     *
     * @param $breadcrumbs
     */
    protected function registerBreadcrumbs($breadcrumbs)
    {
        $parent = null;

        foreach($breadcrumbs as $page => $settings)
        {
            \Breadcrumbs::register($page, function($b) use ($settings, $parent)
            {
                list($title, $url) = array_values($settings);

                if ($parent)
                    $b->parent($parent);

                $b->push($title, $url);
            });

            $parent = $page;
        }
    }
}