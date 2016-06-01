<?php namespace Keyhunter\Administrator;

class QueryString implements \Keyhunter\Administrator\Form\Contracts\QueryString
{
    protected $args;

    /**
     * QueryString constructor.
     */
    public function __construct($args)
    {
        $this->args = $args;
    }

    /**
     * Convert QueryString args to string
     *
     * @return string
     */
    public function toString()
    {
        return empty($this->args) ? "" : "?" . http_build_query($this->args);
    }

    /**
     * Convert QueryString args to array
     *
     * @return string
     */
    public function toArray()
    {
        return $this->args;
    }
}