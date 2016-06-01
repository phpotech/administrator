<?php namespace Keyhunter\Administrator\Form\Type;

use Form;
use Keyhunter\Administrator\Form\Element;

class Date extends Element
{

	/**
	 * The specific defaults for subclasses to override
	 *
	 * @var array
	 */
	protected $attributes = [
		'class' => 'form-control input-sm',
		'style'	=> 'width: 262px;'
	];

	/**
	 * The specific rules for subclasses to override
	 *
	 * @var array
	 */
	protected $rules = [];


	public function renderInput()
	{
		return '<!-- Scaffold: '.$this->getName().' -->'
		. '<div class="input-group">'
		. '    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>'
		.      Form::text($this->name, $this->value, $this->attributes + ['data-filter-type' => 'date'])
		. '</div>';
	}
}