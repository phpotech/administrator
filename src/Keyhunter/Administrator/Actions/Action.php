<?php namespace Keyhunter\Administrator\Actions;

use Keyhunter\Administrator\Traits\CallableTrait;

class Action
{
    use CallableTrait;

    /**
     * Action name
     *
     * @var
     */
    protected $name;

    /**
     * Action label
     *
     * @var string
     */
    protected $title;

    protected $confirmation = null;

    protected $callback = null;

    public function __construct($name, $title = '', $confirmation = 'Are you sure?', $callback = null)
    {
        $this->name = $name;

        if (empty($title))
        {
            $title = $name;
        }
        $this->title = ucwords(join(" ", explode("_", $title)));

        $this->confirmation = $confirmation;

        $this->callback = $callback;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return null|string
     */
    public function getConfirmation()
    {
        return ($this->confirmation ? 'onclick="return window.confirm(\'' . $this->confirmation . '\')"' : '');
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    public function isReservedUrl()
    {
        return in_array($this->name, ['create', 'edit', 'delete']);
    }

    public function getUrl($id, $action = null)
    {
        if ($this->callback instanceof Urlable)
        {
            return $this->callback->getUrl();
        }

        $model = \Route::input('page');

        if ($this->isReservedUrl())
        {
            return route("admin_model_{$this->name}", ['page' => $model, 'id' => $id]);
        }
        else
        {
            return route("admin_model_custom_action", ['page' => $model, 'id' => $id, 'action' => $action]);
        }
    }

    /**
     * Evaluate callback
     *
     * @return bool|mixed
     */
    public function executeCallback()
    {
        if (! is_callable($this->callback))
        {
            return false;
        }

        $args = func_get_args();

        array_unshift($args, $this->callback);

        return call_user_func_array([$this, 'callback'], $args);
    }
}