<?php namespace Keyhunter\Administrator\Filters\Element;

use Form;
use Keyhunter\Administrator\Filters\QueryableInterface;
use Keyhunter\Administrator\Filters\QueryableTrait;
use Keyhunter\Administrator\Form\Element;
use Keyhunter\Administrator\Traits\CallableTrait;

class Select extends Element implements QueryableInterface
{
    use CallableTrait, QueryableTrait;

    protected $multiple = false;

    public function getOptions()
    {
        $options = $this->options;

        if (empty($options))
            return [];

        if (is_callable($options))
        {

            return $this->callback($options);
        }

        return (array) $options;
    }

    public function renderInput()
    {
        $name = $this->getName();

        if ($this->multiple) {
            $name = "{$name}[]";
            $this->attributes["multiple"] = "multiple";
        }

        return '<!-- Scaffold: '.$this->getName().' -->'
            . Form::label($this->getName(), $this->getLabel())
            . Form::select($name, $this->getOptions(), $this->getValue(), $this->attributes);
    }
}