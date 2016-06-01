<?php namespace Keyhunter\Administrator;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\UrlGenerator;

class Navigation
{
    protected $pages = null;

    protected $linkIcon = 'fa-angle-double-right';

    protected $folderIcon = 'fa-folder';

    protected $active = null;

    /**
     * @var UrlGenerator
     */
    public $urlGenerator;

    /**
     * @var Application
     */
    protected $application;
    /**
     * @var PermissionChecker
     */
    private $guard;

    public function __construct(Application $application,  UrlGenerator $urlGenerator, Guard $guard, array $pages = [])
    {
        $this->application = $application;

        $this->urlGenerator = $urlGenerator;

        $this->guard = $guard;

        if (! empty($pages))
        {
            $this->setPages($pages);
        }
    }

    public function setPages(array $pages = [])
    {
        $this->pages = $this->parsePages($pages);

        return $this;
    }

    public function getPages()
    {
        return $this->pages;
    }

    /**
     * Get current page.
     * 
     * @return array
     */
    public function getCurrentPage()
    {
        $pages = $this->getPages();

        $current_page = [];
        array_walk($pages, function ($options, $cat) use (&$current_page) {
            if(isset($options['pages'][$this->getCurrentModule()]))
                $current_page = $options['pages'][$this->getCurrentModule()];
        });

        return $current_page;
    }

    protected function parsePages(array $pages = [])
    {
        $list = [];

        foreach($pages as $key => $options)
        {
            $isGroup = is_string($key) && is_array($options) && array_key_exists('pages', $options);

            if ($isGroup)
            {
                $permission = $this->loadPermission($options);

                // check group permission
                if ($this->guard->isPermissionGranted($permission))
                {
                    $children = [
                        'page_header' => $this->arrayGet($options, 'page_header'),
                        'title' => $this->arrayGet($options, 'title', $this->titlefy($key)),
                        'icon' => $this->arrayGet($options, 'icon', $this->folderIcon),
                        'pages' => $this->parsePages($options['pages'])
                    ];

                    // skip empty groups
                    if (! empty($children['pages']))
                    {
                        $list[$key] = $children;
                    }
                }
            }
            else
            {
                if (is_numeric($key) && is_string($options))
                {
                    $key   = $options;
                    $page  = strtolower($options);
                    $title = $this->fetchConfigTitle($key);
                    $link  = $this->route('admin_model_index', ['page' => $options]);
                    $icon  = $this->linkIcon;
                }
                else if (is_string($key) && is_array($options))
                {
                    $page = strtolower($key);

                    $title = $this->arrayGet($options, 'title', $this->fetchConfigTitle($key));

                    if (array_key_exists('link', $options))
                    {
                        $link = $options['link'];
                    }
                    else if (array_key_exists('route', $options))
                    {
                        $link = $this->route($options['route'], $this->arrayGet($options, 'params', []));
                    }
                    else
                    {
                        $link = $this->route('admin_model_index', ['page' => $key]);
                    }
                    $icon  = $this->arrayGet($options, 'icon', $this->linkIcon);
                }

                if ($this->guard->isPermissionGranted($this->getModelPermission($page)))
                {
                    $list[$key] = compact('title', 'page', 'link', 'icon');
                }
            }
        }

        return $list;
    }

    public function getCurrentModule()
    {
        return $this->application->make('scaffold.module')->get('page');
    }

    /**
     * @param $key
     * @return string
     */
    protected function titlefy($key)
    {
        $title = ucwords(str_replace(['-', '_'], ' ', $key));
        return $title;
    }

    protected function arrayGet($array, $key, $default = null)
    {
        return (array_key_exists($key, $array) ? $array[$key] : $default);
    }

    public function route($name, $parameters = [], $absolute = true)
    {
        return $this->urlGenerator->route($name, $parameters, $absolute);
    }

    /**
     * @param $model
     * @return mixed
     */
    protected function getModelPermission($model)
    {
        return $this->loadPermission($this->loadConfig($model));
    }

    /**
     * @param $config
     * @return bool
     */
    protected function loadPermission($config)
    {
        return (isset($config['permission']) ? $config['permission'] : true);
    }

    /**
     * @param $model
     * @return mixed
     */
    protected function loadConfig($model)
    {
        $module = $this->application->make('scaffold.config');

        $path = $module['models_path'] . '/' . $model . '.php';

        $config = require $path;

        return $config;
    }

    protected function fetchConfigTitle($page)
    {
        $config = $this->loadConfig($page);

        return $this->arrayGet($config, 'title', $this->titlefy($page));
    }
}