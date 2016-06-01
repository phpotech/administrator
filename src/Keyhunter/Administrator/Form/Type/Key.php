<?php namespace Keyhunter\Administrator\Form\Type;

use Keyhunter\Administrator\Form\Element;

class Key extends Element
{
    protected $value = null;

    public function renderInput()
    {
        return $this->getValue();
    }
}