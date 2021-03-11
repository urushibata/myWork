<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class CognitoService extends Facade
{
    protected static function getFacadeAccessor() {
        return 'CognitoService';
    }
}

