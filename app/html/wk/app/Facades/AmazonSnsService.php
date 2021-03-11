<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class AmazonSnsService extends Facade
{
    protected static function getFacadeAccessor() {
        return 'AmazonSnsService';
    }
}

