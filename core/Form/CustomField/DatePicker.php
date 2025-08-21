<?php

namespace Core\Form\CustomField;

use Kris\LaravelFormBuilder\Fields\FormField;

class DatePicker extends FormField
{

    protected function getTemplate(): string
    {
        return 'datepicker';
    }

}
