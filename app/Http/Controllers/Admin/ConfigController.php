<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Config\ConfigAdmin;
use Core\Controllers\AbstractController;

class ConfigController extends AbstractController
{
    public function __construct(ConfigAdmin $admin)
    {
        parent::__construct($admin);
    }
}
