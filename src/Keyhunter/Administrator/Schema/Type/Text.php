<?php namespace Keyhunter\Administrator\Schema\Type;

class Text extends TypeAbstract
{
    protected $length;

    /**
     * @return int
     */
    public function getLength()
    {
        return $this->length;
    }
}