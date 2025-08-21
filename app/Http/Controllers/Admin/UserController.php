<?php

namespace App\Http\Controllers\Admin;

use App\Admin\User\UserAdmin;
use Core\Controllers\AbstractController;

class UserController extends AbstractController
{
    public function __construct(UserAdmin $admin)
    {
        parent::__construct($admin);
    }
}
