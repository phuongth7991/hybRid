<?php

namespace Core\Form\CustomField;

use Kris\LaravelFormBuilder\Fields\FormField;

class Image extends FormField
{

    protected function getTemplate(): string
    {
        return 'image';
    }

}
