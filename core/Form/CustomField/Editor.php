<?php

namespace Core\Form\CustomField;

use Kris\LaravelFormBuilder\Fields\FormField;
use Kris\LaravelFormBuilder\Form;
use Page;

class Editor extends FormField
{

    public function __construct($name, $type, Form $parent, array $options = [])
    {
        parent::__construct($name, $type, $parent, $options);
//        Page::addScript( 'assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js', 'ckeditor-classic');
    }

    protected function getTemplate(): string
    {
        return 'editor';
    }

}
