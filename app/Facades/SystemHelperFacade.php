<?php

namespace App\Facades;
use Illuminate\Support\Facades\Facade;

class SystemHelperFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'system-helper';
    }
}
