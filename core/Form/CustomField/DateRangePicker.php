<?php

namespace Core\Form\CustomField;

use Kris\LaravelFormBuilder\Fields\FormField;

class DateRangePicker extends FormField
{

    protected function getTemplate(): string
    {
        return 'date-range-picker';
    }

}
