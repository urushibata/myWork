<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class DynamodbService extends Facade
{
    protected static function getFacadeAccessor() {
        return 'DynamodbService';
    }
}
