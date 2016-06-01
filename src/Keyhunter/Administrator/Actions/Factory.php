<?php namespace Keyhunter\Administrator\Actions;

use Illuminate\Support\Collection;
use Keyhunter\Administrator\Traits\CallableTrait;

class Factory
{
    use CallableTrait;

    protected $_cleanActions = null;

    protected $_cleanGlobal = null;

    protected $actions = null;

    protected $global = null;

    protected $defaults = [
        'actions' => ['edit' => true, 'delete' => true],
        'global'  => [
            'create' => [
                'title' => 'Create new'
            ]
        ]
    ];

    public function __construct(array $actions = [], array $global = [])
    {
        $this->setActions($actions);

        $this->setGlobalActions($global);
    }

    /**
     * Global rules
     *
     * @param $actions
     */
    public function setActions($actions)
    {
        $actions = array_merge($this->defaults['actions'], $actions);

        $this->_cleanActions = $actions;

        $actions = $this->_build($actions);

        $this->actions = $actions;
    }

    public function setGlobalActions($actions)
    {
        $global = array_merge($this->defaults['global'], $actions);

        $this->_cleanGlobal = $global;

        $global = $this->_build($global);

        $this->global = $global;
    }

    /**
     * Evaluate actions permission callbacks
     *
     * @param $actions
     * @return mixed
     */
    protected function _build($actions, $row = null)
    {
        $list = [];

        foreach ($actions as $action => $options)
        {
            if (is_array($options))
            {
                $permission = isset($options['permission']) ? $options['permission'] : true;

                $permission = is_callable($permission) ? (bool) $this->callback($permission, $row) : (bool) $permission;

                unset($options['permission']);

                if ($permission)
                {
                    $title = (isset($options['title']) ? $options['title'] : '');

                    $confirmation = (isset($options['confirmation']) ? $options['confirmation'] : null);

                    $callback = $this->prepareCallback($row, $options);

                    $list[$action] = new Action($action, $title, $confirmation, $callback);
                }
            }
            /**
             * Global actions, like edit, delete can be configured
             * just using Closure in order to define permission rule
             */
            else if (in_array($action, array_merge(array_keys($this->defaults['actions']), array_keys($this->defaults['global']))))
            {
                $permission = is_callable($options) ? (bool) $this->callback($options, $row) : (bool) $options;

                if ($permission)
                {
                    $confirmation = ('delete' == $action ? 'Are you sure?' : null);
                    $list[$action] = new Action($action, '', $confirmation, null);
                }
            }
        }

        return Collection::make($list);
    }

    /**
     * Get list of available actions
     *
     * @param null $row - Current
     * @return mixed|null
     */
    public function getActions($row = null)
    {
        return $row ? $this->_build($this->_cleanActions, $row) : $this->actions;
    }

    public function getGlobalActions($row = null)
    {
        return $row ? $this->_build($this->_cleanGlobal, $row) : $this->global;
    }

    /**
     * @param $callback
     * @return bool
     */
    protected function isActionCallback($callback)
    {
        return is_a($callback, '\Closure');
    }

    /**
     * @param $callback
     * @return array
     */
    protected function prepareUrlableCallback($callback)
    {
        if (is_string($callback))
        {
            $callback = [$callback, null];
        }

        list($url, $callback) = array_values($callback);

        return [$callback, $url];
    }

    /**
     * Detect callback
     *
     * @param $row
     * @param $options
     * @return array
     */
    protected function prepareCallback($row, $options)
    {
        if (isset($options['callback']) && $callback = $options['callback'])
        {
            return $callback;
        }

        if (isset($options['url']) && $callback = $options['url'])
        {
            list($callback, $url) = $this->prepareUrlableCallback($callback);

            $callback = new ActionUrl($url, $callback, $row);

            return $callback;
        }

        if (isset($options['route']) && $callback = $options['route'])
        {
            list($callback, $url) = $this->prepareUrlableCallback($callback);

            $callback = new ActionRoute($url, $callback, $row);

            return $callback;
        }

        return null;
    }
}