<?php namespace Keyhunter\Administrator\Form;

use Illuminate\Support\Collection;
use Keyhunter\Administrator\Exception;
use Keyhunter\Administrator\Exceptions\UnknownFieldTypeException;

class Builder
{
    /**
     * @var array
     */
    private $fields = null;

    private $cleanFields = [];

    /**
     * The valid field types and their associated classes
     *
     * @var array
     */
    private $fieldTypes = array(
        'key'       => 'Keyhunter\\Administrator\\Form\\Type\\Key',
        'text'      => 'Keyhunter\\Administrator\\Form\\Type\\Text',
        'hidden'    => 'Keyhunter\\Administrator\\Form\\Type\\Hidden',
        'email'     => 'Keyhunter\\Administrator\\Form\\Type\\Email',
        'select'    => 'Keyhunter\\Administrator\\Form\\Type\\Select',
        'textarea'  => 'Keyhunter\\Administrator\\Form\\Type\\Textarea',
        'ckeditor'  => 'Keyhunter\\Administrator\\Form\\Type\\Ckeditor',
        'tinymce'   => 'Keyhunter\\Administrator\\Form\\Type\\Tinymce',
        'wysihtml5' => 'Keyhunter\\Administrator\\Form\\Type\\WysiHtml5',
        'markdown'  => 'Keyhunter\\Administrator\\Form\\Type\\Markdown',
        'password'  => 'Keyhunter\\Administrator\\Form\\Type\\Password',
        'date'      => 'Keyhunter\\Administrator\\Form\\Type\\Date',
        'time'      => 'Keyhunter\\Administrator\\Form\\Type\\Time',
        'datetime'  => 'Keyhunter\\Administrator\\Form\\Type\\Datetime',
        'number'    => 'Keyhunter\\Administrator\\Form\\Type\\Number',
        'tel'       => 'Keyhunter\\Administrator\\Form\\Type\\Tel',
        'bool'      => 'Keyhunter\\Administrator\\Form\\Type\\Bool',
        'image'     => 'Keyhunter\\Administrator\\Form\\Type\\Image',
        'file'      => 'Keyhunter\\Administrator\\Form\\Type\\File',
        'color'     => 'Keyhunter\\Administrator\\Form\\Type\\Color',
    );

    /**
     * Fields that should be translated
     *
     * @var array
     */
    protected $translatable = [];

    public function __construct(array $fields = [])
    {
        $this->cleanFields = $fields;
    }

    public function getFields()
    {
        if (null == $this->fields)
        {
            $fields = [];

            foreach($this->cleanFields as $name => $options)
            {
                if (is_a($options, '\\Keyhunter\Administrator\\Form\\Element'))
                {
                    $element = $options;
                }
                else if ((is_string($name) && is_array($options)))
                {
                    $type    = $options['type'];
                    $element = $this->createElement($type, $options, $name);

                    if (isset($options['translatable']) && (bool) $options['translatable'])
                    {
                        $element = new TranslatableElement($element);
                    }
                }
                else
                {
                    throw new Exception(sprintf('Can not initializa element [%s]', $name));
                }

                $fields[]  = $element;
            }

            $this->fields = Collection::make($fields);
        }

        return $this->fields;
    }

    /**
     * @param $type
     * @param $options
     * @param $name
     * @return mixed
     * @throws UnknownFieldTypeException
     */
    private function createElement($type, $options, $name)
    {
        $className = $this->getFieldTypes()[$type];
        if (!$className) {
            throw new UnknownFieldTypeException(sprintf("Unknown field of type '%s'", $options['type']));
        }

        $element = (new $className($name))->initFromArray($options);

        return $element;
    }

    /**
     * Get all field types
     *
     * @return array
     */
    public function getFieldTypes()
    {
        if($custom = $this->loadCustomFieldTypes())
            return array_merge($custom, $this->fieldTypes);

        return $this->fieldTypes;
    }

    /**
     * Check if type is valid.
     *
     * @param $class
     * @return bool
     */
    public function validateFieldType($class)
    {
        return (bool) $class instanceof Element;
    }

    /**
     * Get the list of custom fields.
     *
     * @return array
     */
    public function loadCustomFieldTypes()
    {
        //todo: change this stuff to provider.
        return config('administrator.custom_field_types');
    }

    public function getEditors()
    {
        if (! $this->fields)
        {
            $this->getFields();
        }

        $editors = [];

        foreach ($this->fields as $field)
        {
            if ($field->getType() == 'tinymce' && ! in_array('tinymce', $editors))
            {
                $editors[] = 'tinymce';
            }
            else if ($field->getType() == 'ckeditor' && ! in_array('tinymce', $editors))
            {
                $editors[] = 'ckeditor';
            }
            else if ($field->getType() == 'wysihtml5' && ! in_array('tinymce', $editors))
            {
                $editors[] = 'wysihtml5';
            }
        }

        return $editors;
    }
}