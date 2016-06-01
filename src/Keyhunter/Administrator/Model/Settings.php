<?php namespace Keyhunter\Administrator\Model;

use Keyhunter\Administrator\Repository;

class Settings extends Repository {

    protected $table    = 'options';

    public $timestamps  = false;

    protected $fillable = ['*'];

    static protected $options = null;

    /**
     * Fetch value by key
     *
     * @param $key
     * @return null
     */
    static public function getOption($key)
    {
        if (null === self::$options)
        {
            self::$options = self::listOptions();
        }

        return self::$options[$key] ? : null;
    }

    /**
     * Fetch all settings
     *
     * @return mixed
     */
    static public function listOptions()
    {
        return (new static)->lists('value', 'key');
    }
}