<?php

namespace App\Models;

class User extends \Core\Model
{
    static public ?string $tableName = 'users';

    public string|null $email, $password, $token = null, $token_expired_at = null, $created_at;
}
