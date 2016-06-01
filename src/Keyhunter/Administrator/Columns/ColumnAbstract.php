<?php namespace Keyhunter\Administrator\Columns;

class ColumnAbstract
{
    protected $name;

    protected $title;

    protected $sortable = false;

    public function getName()
    {
        return $this->name;
    }

    public function getTitle()
    {
        return $this->title;
    }
}