<?php

namespace App\Admin\Config;

use Core\Form\Field;
use Core\Form\Form;

class ConfigForm extends Form
{
    public function buildForm(): void
    {
        $this
            ->add('key', Field::TEXT, [
                'label' => __('Tên'),
                'attr'  => [
                    'disabled' => true,
                ],
            ])
            ->add('value', Field::TEXT, [
                'label' => __('Giá trị'),
                'rules' => 'required|max:255',
            ])
            ->add('submit', Field::BUTTON_SUBMIT, [
                'attr'  => [
                    'class' => 'btn btn-success'
                ],
                'label' => __('Lưu')
            ]);
    }
}
