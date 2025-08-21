<?php

return [
    'defaults' => [
        'wrapper_class' => 'form-group fv-row mb-7',
        'wrapper_error_class' => 'has-error',
        'label_class' => 'fs-6 fw-semibold form-label mt-3 control-label',
        'field_class' => 'form-control',
        'field_error_class' => '',
        'help_block_class' => 'help-block',
        'error_class' => 'text-danger',
        'required_class' => 'required',

        'help_block_tag' => 'p',

        // Override a class from a field.
        //'text'                => [
        //    'wrapper_class'   => 'form-field-text',
        //    'label_class'     => 'form-field-text-label',
        //    'field_class'     => 'form-field-text-field',
        //]
        //'radio'               => [
        //    'choice_options'  => [
        //        'wrapper'     => ['class' => 'form-radio'],
        //        'label'       => ['class' => 'form-radio-label'],
        //        'field'       => ['class' => 'form-radio-field'],
        //],
    ],
    // Templates
    'form' => 'laravel-form-builder::form',
    'text' => 'laravel-form-builder::text',
    'textarea' => 'laravel-form-builder::textarea',
    'button' => 'laravel-form-builder::button',
    'buttongroup' => 'laravel-form-builder::buttongroup',
    'radio' => 'laravel-form-builder::radio',
    'checkbox' => 'laravel-form-builder::checkbox',
    'select' => 'laravel-form-builder::select',
    'choice' => 'laravel-form-builder::choice',
    'repeated' => 'laravel-form-builder::repeated',
    'child_form' => 'laravel-form-builder::child_form',
    'collection' => 'laravel-form-builder::collection',
    'static' => 'laravel-form-builder::static',
    'file' => 'core.form.file',
    'image' => 'core.form.image',
    'datepicker' => 'core.form.datepicker',
    'date-range-picker' => 'core.form.date-range-picker',
    'editor' => 'core.form.editor',
    'switch' => 'core.form.switch',
    'gallery' => 'core.form.gallery',

    // Remove the laravel-form-builder:: prefix above when using template_prefix
    'template_prefix' => '',

    'default_namespace' => '',

    'custom_fields' => [
        'file' => Core\Form\CustomField\File::class,
        'datepicker' => Core\Form\CustomField\DatePicker::class,
        'editor' => Core\Form\CustomField\Editor::class,
        'image' => Core\Form\CustomField\Image::class,
        'date-range-picker' => Core\Form\CustomField\DateRangePicker::class,
        'custom-template' => Core\Form\CustomField\CustomTemplate::class,
        'switch' => Core\Form\CustomField\SwitchField::class,
        'gallery' => Core\Form\CustomField\Gallery::class
    ],

    'plain_form_class' => \Kris\LaravelFormBuilder\Form::class,
    'form_builder_class' => \Kris\LaravelFormBuilder\FormBuilder::class,
    'form_helper_class' => \Kris\LaravelFormBuilder\FormHelper::class,
];
