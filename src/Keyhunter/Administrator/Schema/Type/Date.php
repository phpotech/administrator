<?php namespace Keyhunter\Administrator\Schema\Type;

class Date extends DateTime
{
    protected $format = 'Y-m-d';

    protected $defaultValue = '0000-00-00';
}