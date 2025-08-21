<?php

namespace Core\Form\CustomField;

use Kris\LaravelFormBuilder\Fields\FormField;

class File extends FormField
{

    protected function getTemplate(): string
    {
        return 'file';
    }

}
