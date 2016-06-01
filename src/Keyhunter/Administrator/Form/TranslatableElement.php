<?php namespace Keyhunter\Administrator\Form;

use Lang;
use Keyhunter\Administrator\Exception;
use Keyhunter\Administrator\Form\Contracts\FormElement;
use Keyhunter\Administrator\Form\Contracts\Validator;

class TranslatableElement implements FormElement, Validator
{
	use Validable, Boilerplate;

	/**
	 *
	 *
	 * @var Element
	 */
	protected $element;

	public function __construct(Element $element)
	{
		$this->element = $element;
	}

	/**
	 * @return mixed
	 */
	public function html()
	{
		$publicLocales = Lang::getPublic();

		$inputs = [];

		$name	 	  = $this->element->getName();

		//
		/**
		 * to be able using translations we have to get element's Eloquent model
		 *
		 * @var $repository \Keyhunter\Translatable\Translatable;
		 */
		$repository   = $this->element->getRepository();

		/**
		 * @var $locale = \Keyhunter\Multilingual\Language
		 */
		foreach ($publicLocales as $locale)
		{
			// clone original element for modification
			$element = clone $this->element;

			// set translated value
			if ($repository->hasTranslation($locale->id))
				$element->setValue($repository->translate($locale->id)->$name);

			// set element belongs to locale
			$element->setName("{$locale->id}[{$name}]");

			$input = $element->html();
			$input = '<div class="translatable '.($locale->id == Lang::id() ? '' : 'hidden').'" data-locale="'.($locale->id).'">'.$input.'</div>';

			$inputs[] = $input;
		}

		$inputs = join("", $inputs);

		$html =
<<<HTML
<div class="translatable-block">
	<div class="translatable-items pull-left" style="margin-right: 20px;">
		{$inputs}
	</div>
	{$this->localeSwitcher()}
</div>
HTML;

		return $html;
	}

	/**
	 * @return mixed
	 */
	public function getLabel()
	{
		return $this->element->getLabel();
	}

	public function getName()
	{
		return $this->element->getName();
	}

	public function getDescription()
	{
		return $this->element->getDescription();
	}

	public function getType()
	{
		return $this->element->getType();
	}

	private function localeSwitcher()
	{
		$buttons = [];
		foreach(Lang::getPublic() as $locale)
		{
			$buttons[] = '<button type="button" class="btn btn-default btn-flat btn-sm '.($locale->id == Lang::id() ? 'active' : '').'" data-locale="'.$locale->id.'">'.$locale->slug.'</button>';
		}

		$buttons = join("", $buttons);

		return
<<<SWITCHER
<div class="box-tools pull-left locale-switcher" data-toggle="tooltip" data-original-title="Switch locale">
	<div class="btn-group" data-toggle="btn-toggle">
		{$buttons}
	</div>
</div>
SWITCHER;

	}

	/**
	 * Types that can be translated
	 *
	 * @param $element
	 * @return bool
	 * @throws Exception
	 */
	private function checkForTranslatableType($element)
	{
		if (! in_array($element->getType(), ['text', 'textarea', 'markdown']))
		{
			throw new Exception(sprintf('Unfortunately field of type [%s] can not be translated', $element->getType()));
		}
	}
}