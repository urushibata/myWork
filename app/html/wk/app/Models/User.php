<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Foundation\Auth\Access\Authorizable;

class User implements AuthenticatableContract
{
    use Authenticatable, Authorizable;

    /**
     * Get the name of the unique identifier for the user.
     *
     * @see \Illuminate\Contracts\Auth\Authenticatable:: getAuthIdentifierName
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'name';
    }
}
