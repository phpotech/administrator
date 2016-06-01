<?php namespace Keyhunter\Administrator\Schema\Type;

class DateTime extends TypeAbstract
{
    protected $format       = 'Y-m-d H:i:s';

    protected $defaultValue = '0000-00-00 00:00:00';

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }
}