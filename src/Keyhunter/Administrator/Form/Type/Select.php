<?php namespace Keyhunter\Administrator\Form\Type;

use Form;
use Illuminate\Support\Facades\App;
use Keyhunter\Administrator\Form\Element;

class Select extends Element
{
	protected $options = [];

	protected $attributes = [
		'class' => 'form-control',
		'style' => 'width: 300px;'
	];

	protected $rules = [
		'options' => 'required'
	];

	public function setOptions($options)
	{
		$this->options = $options;

		$this->resolveOptions();

		return $this;
	}

	public function renderInput()
	{
		$name = $this->name;
		if (isset($this->attributes['multiple']) && $this->attributes['multiple'])
		{
			$this->attributes['id'] = Form::getIdAttribute($name, $this->attributes);
			$name = "{$name}[]";
		}

		return Form::select($name, $this->options, $this->value, $this->attributes);
	}

	/**
	 * When options are provided like "users.role"
	 * values will be retrieved directly from table users, field role
	 *
	 * @return mixed
	 * @throws \Exception
	  */
	private function resolveValuesRelation()
	{
		list($table, $field) = explode(".", $this->options);

		if (! ($table && $field)) {
			throw new \Exception("String definition should have format: table.field");
		}

		$schema = App::make('scaffold.schema');

		return $schema->get($field);
	}

	/**
	 * @return bool
	 */
	private function valuesAsClosure()
	{
		return is_callable($this->options);
	}

	/**
	 * Check if options list is provided in .dot notation style:
	 * @example users.role
	 *
	 * @return bool
	 */
	private function valuesAsRelation()
	{
		return is_string($this->options) && preg_match('~\w\.\w~si', $this->options);
	}

	private function resolveOptions()
	{
		// An anonymous (Closure) function can be defined for options list
		if ($this->valuesAsClosure()) {
			$this->options = call_user_func($this->options);
		} else if ($this->valuesAsRelation()) {
			$field = $this->resolveValuesRelation();
			$this->options = $field->getValues();
		}
	}
}