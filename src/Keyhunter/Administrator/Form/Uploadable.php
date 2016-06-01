<?php namespace Keyhunter\Administrator\Form;

use Keyhunter\Administrator\Exception;
use Keyhunter\Administrator\Repository;

interface Uploadable
{
    public function setLocation($location);

    /**
     * This static function is used to perform the actual upload and resizing using the Multup class
     * @return array
     */
    public function upload();

    public function destroy();
}