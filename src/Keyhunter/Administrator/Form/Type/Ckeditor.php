<?php namespace Keyhunter\Administrator\Form\Type;

use Form;
use Keyhunter\Administrator\Form\Element;

class Ckeditor extends Textarea
{
	public function renderInput()
	{
		$attributes = $this->attributes + ['data-editor' => 'ckeditor'];

		return Form::textarea($this->name, $this->value, $attributes);
	}
}