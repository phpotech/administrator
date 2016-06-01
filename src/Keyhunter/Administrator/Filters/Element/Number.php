<?php namespace Keyhunter\Administrator\Filters\Element;

use Form;
use Keyhunter\Administrator\Filters\QueryableInterface;
use Keyhunter\Administrator\Filters\QueryableTrait;
use Keyhunter\Administrator\Form\Element;

class Number extends Element implements QueryableInterface
{
    use QueryableTrait;

    public function renderInput()
    {
        return '<!-- Scaffold: '.$this->getName().' -->'
            . Form::label($this->getName(), $this->getLabel())
            . Form::text($this->getName(), $this->getValue(), $this->attributes);
    }
}