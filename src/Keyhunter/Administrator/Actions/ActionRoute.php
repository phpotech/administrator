<?php namespace Keyhunter\Administrator\Actions;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Routing\Route;

class ActionRoute extends ActionUrl
{
    /**
     * @param $params
     */
    protected function replacePlaceholders($params)
    {
        //@todo: find a way how to extract only needed attributes from route
        $this->url = route($this->url, $params);
    }
}