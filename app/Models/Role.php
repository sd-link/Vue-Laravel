<?php

namespace App\Models;

use Laratrust\LaratrustRole;
use Laratrust\Traits\LaratrustRoleTrait;

class Role extends LaratrustRole
{
    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user');
    }
}
