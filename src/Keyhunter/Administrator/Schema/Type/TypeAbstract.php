<?php namespace Keyhunter\Administrator\Schema\Type;

/**
 * Global type definition class
 *
 * Class TypeAbstract
 * @package Keyhunter\Administrator\Schema\Type
 */
abstract class TypeAbstract
{
    /**
     * Field name
     *
     * @var string
     */
    protected $name;

    /**
     * Field type
     *
     * @var string
     */
    protected $type;

    /**
     * Allow/deny NULL values
     *
     * @var bool
     */
    protected $nullable = false;

    /**
     * Default field value
     *
     * @var null
     */
    protected $defaultValue = null;

    /**
     * Is field a primary key or part of it?
     *
     * @var bool
     */
    protected $primary = false;

    /**
     * Is field is an unique key
     *
     * @var bool
     */
    protected $unique = false;

    protected $fieldsMap = [
        'default' => 'defaultValue'
    ];

    public function __construct($name, $options)
    {
        $this->name = $name;

        $this->_expandOptions($options);
    }

    /**
     * initialize class using array of options in the way that each option key corresponds to class property,
     *
     * @param $options
     */
    protected function _expandOptions($options)
    {
        $classVars = get_class_vars(get_class($this));

        foreach($options as $key => $value) {
            if (array_key_exists($key, $classVars))
                $this->{$key} = $value;
            else if (isset($this->fieldsMap[$key])) {
                $key = $this->fieldsMap[$key];
                $this->{$key} = $value;
            }

            if ('extra' == $key && ! empty($value)) {
                $this->_setExtra($value);
            }
        }
    }

    /**
     * Define the way to handle extra attributes
     *
     * @ex.: enum('admin', 'member', 'guest') => 'admin', 'member', 'guest' goes to the $values array
     * @ex.: int(11) unsigned zerofill => 'unsigned', 'zerofill' goes to the $unsigned, $zerofill properties
     *
     * @param $value
     */
    protected function _setExtra($value)
    {
        if (is_string($value)) {
            $value = explode(" ", $value);
        }

        foreach ($value as $property) {
            $this->{$property} = true;
        }
    }

    /**
     * @return string
     */
    final public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    final public function getType()
    {
        return $this->type;
    }

    /**
     * @return boolean
     */
    final public function isNullable()
    {
        return $this->nullable;
    }

    /**
     * @return string
     */
    final public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * @return boolean
     */
    final public function isPrimary()
    {
        return $this->primary;
    }

    /**
     * @return boolean
     */
    final public function isUnique()
    {
        return $this->unique;
    }
}