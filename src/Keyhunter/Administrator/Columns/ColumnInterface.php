<?php namespace Keyhunter\Administrator\Columns;

interface ColumnInterface
{
    public function getName();

    public function getTitle();

    public function getValue($scaffoldRow);

    public function getFormatted($scaffoldRow);
}