<?php namespace Keyhunter\Administrator\Form;

use Keyhunter\Administrator\Form\Contracts\FormElement;
use Keyhunter\Administrator\Form\Contracts\Relationship;
use Keyhunter\Administrator\Form\Contracts\Validator;
use Keyhunter\Administrator\Form\Contracts\Element AS ElementInterface;

abstract class Element implements FormElement, ElementInterface, Validator, Relationship
{
    use Boilerplate, Validable, HasRepository, HasRelation;

    protected $translatable = false;

    public function __construct($name)
    {
        $this->name = $name;

        // by default set label equal to name
        $this->setLabel($name);
    }

    public function initFromArray(array $options = null)
    {
        $this->attributes = array_merge($this->attributes, array_except($options, 'type'));

        $this->validateAttributes();

        $this->decoupleOptionsFromAttributes();

        $this->setDefaultValue();

        return $this;
    }

    /**
     * Each subclass should have this method realized
     *
     * @return mixed
     */
    abstract public function renderInput();

    final public function html()
    {
        $this->setDefaultValue();

        return $this->renderInput() . $this->renderErrors();
    }

    protected function setDefaultValue()
    {
        if ((! $this->hasValue() || is_callable($this->getValue())) && $this->_hasEloquentModel()) {
            if (is_callable($this->getValue()) && $closure = $this->getValue()) {
                $this->setValue(
                    call_user_func($closure, $this->getRepository())
                );
            } elseif ($this->hasRelation()) {
                $repository = $this->getRepository();

                $this->extractValueFromEloquentRelation($repository);
            } else {
                $this->extractValueFromEloquentModel();
            }
        }
    }
}