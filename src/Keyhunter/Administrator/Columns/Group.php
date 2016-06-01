<?php namespace Keyhunter\Administrator\Columns;

class Group extends ColumnAbstract implements ColumnInterface
{
    /**
     * List of elements included in group
     *
     * @var array
     */
    protected $elements = [];

    /**
     * Sort by field
     *
     * @var null
     */
    protected $sortField;

    protected $standalone = false;

    public function __construct($name, $title, array $elements, $sortField = null)
    {
        $this->name = $name;

        if (empty ($title)) {
            $title = $name;
        }
        $this->title = ucwords(join(" ", explode("_", $title)));

        $this->setElements($elements);

        if ($sortField) {
            $this->sortable  = true;
            $this->sortField = $sortField;
        }
    }

    /**
     * @param array $elements
     */
    public function setElements($elements)
    {
        $this->elements = [];

        foreach($elements as $column => $options) {
            $element = new Column($column, $options);

            $this->elements[] = $element;
        }

        return $this;
    }

    public function getElements()
    {
        return $this->elements;
    }

    public function getValue($scaffoldRow)
    {
        $out = [];
        foreach ($this->getElements() as $element) {
            $out[$element->getName()] = $element->getRaw($scaffoldRow);
        }
        return $out;
    }

    public function getFormatted($scaffoldRow)
    {
        $out = [];
        foreach ($this->getElements() as $element) {
            $out[$element->getName()] = $element->getFormatted($scaffoldRow);
        }
        return $out;
    }
}