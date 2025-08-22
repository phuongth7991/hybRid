<?php

namespace App\Admin\User;

use Core\Form\Field;
use Core\Form\Form;

class UserForm extends Form
{
    public function buildForm(): void
    {
        $this
            ->add('name', Field::TEXT, [
                'rules' => 'required|max:255',
                'label' => __('Tên')
            ])
            ->add('email', Field::EMAIL, [
                'rules' => 'required|email',
                'label' => __('Email')
            ])
            ->add('password', Field::PASSWORD, [
                'label' => __('Mật khẩu'),
                'value' => '',
                'attr'  => [
                    'placeholder'  => __('*******'),
                ],
            ])
            ->add('phone', Field::TEXT, [
                'rules' => 'required|max:255',
                'label' => __('SĐT')
            ])
            ->add('avatar', Field::IMAGE, [
                'label' => __('Ảnh đại điện'),
            ])
            ->add('submit', Field::BUTTON_SUBMIT, [
                'attr'  => [
                    'class' => 'btn btn-success'
                ],
                'label' => __('Lưu')
            ]);
    }
}
