<?php

namespace Keyhunter\Administrator\Form\Type;

use Form;
use Keyhunter\Administrator\Form\Element;

class WysiHtml5 extends Textarea
{
    public function renderInput()
    {
        $attributes = $this->attributes + ['data-editor' => 'wysihtml5'];

        /** Fix iframe width:auto bug. */
        $attributes['style'] = $attributes['style'] . ' width: 100%';

        return Form::textarea($this->name, $this->value, $attributes);
    }
}