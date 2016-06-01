<?php namespace Keyhunter\Administrator\Form\Type;

use Form;
use Keyhunter\Administrator\Form\Element;

class Markdown extends Textarea
{
	public function renderInput()
	{
		$attributes = $this->attributes + ['data-editor' => 'markdown'];

		return Form::textarea($this->name, $this->value, $attributes);
	}
}