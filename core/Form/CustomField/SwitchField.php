<?php

namespace Core\Form\CustomField;

use Kris\LaravelFormBuilder\Fields\FormField;

class SwitchField extends FormField
{

    protected function getTemplate(): string
    {
        return 'switch';
    }

}
