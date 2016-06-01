<?php namespace Keyhunter\Administrator\Form\Type;

use Form;
use Keyhunter\Administrator\Form\Element;
use Keyhunter\Administrator\Form\HiddenElement;

class Hidden extends Element implements HiddenElement
{
    /**
	 * The specific defaults for subclasses to override
	 *
	 * @var array
	 */
	protected $attributes = [];

	/**
	 * The specific rules for subclasses to override
	 *
	 * @var array
	 */
	protected $rules = array(
		'maxlength' 	=> 'integer|min:0|max:255'
	);

	public function renderInput()
	{
		return Form::hidden($this->name, $this->value, $this->attributes);
	}
}