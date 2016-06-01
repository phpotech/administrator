<?php namespace Keyhunter\Administrator\Filters\Element;

use Form;
use Keyhunter\Administrator\Filters\QueryableInterface;
use Keyhunter\Administrator\Filters\QueryableTrait;
use Keyhunter\Administrator\Form\Element;

class Date extends Element implements QueryableInterface {

    use QueryableTrait;

    public function renderInput()
    {
        return '<!-- Scaffold: '.$this->getName().' -->'
            . Form::label($this->getName(), $this->getLabel())
            . '<div class="input-group">'
            . '    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>'
            .      Form::text($this->getName(), $this->getValue(), $this->attributes + ['data-filter-type' => $this->getClassName()])
            . '</div>';
    }

    /**
     * @return string
     */
    protected function getClassName()
    {
        $parts = explode("\\", get_class($this));
        return strtolower(array_pop($parts));
    }
}