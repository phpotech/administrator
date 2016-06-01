<?php namespace Keyhunter\Administrator\Form\Contracts;

interface QueryString
{
    /**
     * Convert QueryString args to string
     *
     * @return string
     */
    public function toString();

    /**
     * Convert QueryString args to array
     *
     * @return string
     */
    public function toArray();
}