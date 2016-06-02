<?php

namespace Keyhunter\Administrator\Filters\Element;

use Form;
use Keyhunter\Administrator\Filters\QueryableInterface;
use Keyhunter\Administrator\Filters\QueryableTrait;
use Keyhunter\Administrator\Form\Element;

class NumberRange extends Element implements QueryableInterface
{
    use QueryableTrait;

    public function renderInput()
    {
        $this->attributes = [
            'class' => 'span2',
            'data-filter-type' => 'number_range',
            'data-slider-min' => $this->attributes['params']['min'],
            'data-slider-max' => $this->attributes['params']['max'],
            'data-slider-step' => "5",
            'data-slider-value' => '[250,450]'
        ];
        return '<!-- Scaffold: '.$this->getName().' -->'
        . Form::label($this->getName(), $this->getLabel())
        . Form::text($this->getName(), $this->getValue(), $this->attributes);
    }
}