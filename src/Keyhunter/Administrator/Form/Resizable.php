<?php

namespace Keyhunter\Administrator\Form;

interface Resizable
{
    public function hasSizes();

    public function resize();

    public function getAliases();

    public function addSize($name, $width, $height);

    public function setSizes($sizes);
}