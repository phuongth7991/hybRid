<?php

namespace Core\Form\CustomField;

use Kris\LaravelFormBuilder\Fields\FormField;

class Gallery extends FormField
{

    protected function getTemplate(): string
    {
        return 'gallery';
    }
}
