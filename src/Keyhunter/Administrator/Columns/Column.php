<?php namespace Keyhunter\Administrator\Columns;

use Keyhunter\Administrator\Traits\CallableTrait;

class Column extends ColumnAbstract implements ColumnInterface
{
    use CallableTrait;

    protected $outputCallback;

    protected $standalone = false;

    public function __construct($column, $options = null, $standalone = false)
    {
        if (is_numeric($column) && is_string($options)) {
            $name       = $options;
            $title      = $name;
            $sortable   = true;
            $standalone = false;
            $output     = null;
            // column set using simple style: 'username'
        } else if (is_string($column) && is_array($options)) {
            $name       = $column;
            $title      = isset($options['title']) ? $options['title'] : '';
            $sortable   = isset($options['sortable']) ? $options['sortable'] : true;
            $standalone = isset($options['standalone']) ? $options['standalone'] : false;
            $output     = isset($options['output']) ? $options['output'] : false;
        } else {
            throw new \Keyhunter\Administrator\Exception(sprintf('Invalid column format: %s, $s', $column, $options));
        }

        $this->name = $name;

        if (empty ($title)) {
            $title = $name;
        }
        $this->title = ucwords(join(" ", explode("_", $title)));

        $this->sortable = (bool) $sortable;

        $this->standalone = (bool) $standalone;

        $this->outputCallback = $output;
    }

    public function getValue($scaffoldRow)
    {
        return $scaffoldRow->{$this->getName()};
    }

    public function getFormatted($scaffoldRow)
    {
        if (! $this->outputCallback)
            return $this->getValue($scaffoldRow);

        if (is_callable($this->outputCallback))
            return $this->callback($this->outputCallback, $scaffoldRow);

        return preg_replace_callback('~\(\:([a-z0-9\_]+)\)~si', function($matches) use ($scaffoldRow) {
            $field = $matches[1];
            return $scaffoldRow->$field;
        }, $this->outputCallback);
    }

    public function isStandalone()
    {
        return $this->standalone;
    }
}