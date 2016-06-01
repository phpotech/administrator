<?php namespace Keyhunter\Administrator\Schema\Type;

class Enum extends TypeAbstract
{
    protected $multiple = false;

    protected $values = [];

    /**
     * @return array
     */
    public function getValues($addEmpty = true)
    {
        $values = array_combine($this->values, array_map('ucwords', $this->values));

        // allow empty value only if can be nullable
        if ($this->isNullable() && $addEmpty) {
            $values = ['' => '-- Select --'] + $values;
        }
        return $values;
    }

    /**
     * Extra parameters (enumerated by type definition), enum('e1', 'e2', 'e3') goes to $values
     *
     * @param $value
     */
    protected function _setExtra($value)
    {
        if (is_string($value)) {
            $value = explode(" ", $value);
        }

        $this->values = $value;
    }

    /**
     * @return boolean
     */
    public function isMultiple()
    {
        return $this->multiple;
    }
}