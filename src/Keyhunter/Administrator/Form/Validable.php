<?php namespace Keyhunter\Administrator\Form;

use Keyhunter\Administrator\Exceptions\WrongFieldAttributeException;
use Validator;

trait Validable {

    protected $errors = [];

    protected $rules 	  = [];

    /**
     * @param array $rules
     * @return $this
     */
    public function setRules(array $rules = [])
    {
        $this->rules = $rules;

        return $this;
    }

    protected function validateAttributes()
    {
        $validator = Validator::make($this->attributes, $this->rules);

        if ($validator->fails()) {
            throw new WrongFieldAttributeException(sprintf("Field \"{$this->name}\" fails with messages: %s", join("; ", $validator->getMessageBag()->all())));
        }
    }

    /**
     * Check if
     *
     * @return bool
     */
    public function hasErrors()
    {
        return ! empty($this->errors);
    }

    protected function renderErrors()
    {
        if (empty($this->errors)) {
            return "";
        }

        return "<ul class=\"errors\"><li>".join("</li><li>", $this->errors)."</li></ul>";
    }
}