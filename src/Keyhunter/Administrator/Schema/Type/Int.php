<?php namespace Keyhunter\Administrator\Schema\Type;

class Int extends TypeAbstract
{
    protected $unsigned = false;

    protected $zerofill = false;

    protected $length   = 11;

    /**
     * @return boolean
     */
    public function isUnsigned()
    {
        return $this->unsigned;
    }

    /**
     * @return boolean
     */
    public function isZerofill()
    {
        return $this->zerofill;
    }

    /**
     * @return int
     */
    public function getLength()
    {
        return $this->length;
    }
}