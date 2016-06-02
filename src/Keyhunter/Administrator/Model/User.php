<?php

namespace Keyhunter\Administrator\Model;

use App\User as BaseUser;

class User extends BaseUser
{
    /**
     * @var array
     */
    protected $fillable = ['name', 'email', 'password', 'role_id', 'active'];
}