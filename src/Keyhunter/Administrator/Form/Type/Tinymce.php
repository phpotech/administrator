<?php namespace Keyhunter\Administrator\Form\Type;

use Form;
use Keyhunter\Administrator\Form\Element;

class Tinymce extends Textarea
{
	public function renderInput()
	{
		$attributes = $this->attributes + ['data-editor' => 'tinymce'];

		return Form::textarea($this->name, $this->value, $attributes);
	}
}