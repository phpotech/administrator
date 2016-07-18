<?php

namespace Keyhunter\Administrator\Filters\Element;

use Form;
use Keyhunter\Administrator\Filters\QueryableInterface;
use Keyhunter\Administrator\Filters\QueryableTrait;
use Keyhunter\Administrator\Form\Element;

class NumberRange extends Element implements QueryableInterface
{
    use QueryableTrait;

    /**
     * Render input form.
     *
     * @return string
     */
    public function renderInput()
    {
        $value = $this->renderValue();
        $this->attributes = [
            'class' => 'span2',
            'data-filter-type' => 'number_range',
            'data-slider-min' => isset($this->attributes['params']['min']) ? $this->attributes['params']['min'] : '100',
            'data-slider-max' => isset($this->attributes['params']['max']) ? $this->attributes['params']['max'] : '1000',
            'data-slider-step' => "5",
            'data-slider-value' => $value
        ];

        return '<!-- Scaffold: ' . $this->getName() . ' -->'
        . Form::label($this->getName(), $this->getLabel())
        . Form::text($this->getName(), $value, $this->attributes);
    }

    /**
     * Render input's value
     *
     * @return string
     */
    protected function renderValue()
    {
        $value = $this->getValue();

        $min = $this->attributes['params']['min'];
        $max = $this->attributes['params']['max'];
        $diff = $max - $min;

        if (! isset($value))
            return sprintf('[%s,%s]', $diff * 0.3, $diff * 0.7);

        return sprintf('[%s]', $value);
    }
}