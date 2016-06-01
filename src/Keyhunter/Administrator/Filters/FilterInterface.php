<?php namespace Keyhunter\Administrator\Filters;

interface FilterInterface
{
    public function addElements(array $elements = []);

    public function getElements();
}