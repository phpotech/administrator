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
     * @param $default
     * @return null
     */
    static public function getOption($key, $default = null)
    {
        if (null === self::$options)
        {
            self::$options = self::listOptions();
        }

		return isset(self::$options[$key]) ? self::$options[$key] : $default;
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