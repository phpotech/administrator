<?php namespace Keyhunter\Administrator\Actions;

use Closure;
use Illuminate\Contracts\Support\Arrayable;

class ActionUrl implements Urlable
{
    /**
     * @var bool
     */
    protected $parsed = false;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var Closure
     */
    protected $callback;

    /**
     * @var null
     */
    protected $model;

    /**
     * ActionUrl constructor.
     * @param $url
     * @param $callback
     * @param Arrayable $model
     */
    public function __construct($url, $callback = null, Arrayable $model = null)
    {
        $this->url = $url;
        $this->callback = $callback;
        $this->model = $model;
    }

    public function __toString()
    {
        return $this->getUrl();
    }

    public function getUrl()
    {
        if (! $this->parsed)
        {
            $params = $this->extractArguments();

            $this->replacePlaceholders($params);

            $this->parsed = true;
        }

        return $this->url;
    }

    /**
     * @return array|mixed
     */
    protected function extractArguments()
    {
        $params = [];

        // run provided callback (if provided) to get extra non-default, non-model arguments
        if (! is_null($this->callback))
        {
            if (! is_callable($this->callback) && ! is_array($this->callback))
            {
                throw new \InvalidArgumentException("Callback must be a Callable instance or Assoc array");
            }

            $params = is_callable($this->callback) ? call_user_func($this->callback, $this->model) : $this->callback;
        }

        return $params;
    }

    /**
     * @param $params
     */
    protected function replacePlaceholders($params)
    {
        $matched = [];
        $this->url = preg_replace_callback('~\{([a-z0-9\_]+)\}~si', function ($matches) use ($params, &$matched)
        {
            $field = $matches[1];
            $value = $params[$field];
            return $matched[$field] = $value;
        }, $this->url);

        if ($diff = array_diff_assoc($params, $matched))
        {
            $qs = [];
            if (array_key_exists('query', $parts = parse_url($this->url)))
            {
                parse_str($parts['query'], $qs);
                $this->url = $parts['path'];
            }
            $qs = array_merge($qs, $diff);

            $this->url .= '?' . http_build_query($qs);
        }
    }
}