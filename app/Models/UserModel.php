<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $allowedFields = ['username', 'email', 'password'];

    public function getUserByUsername($username)
    {
        return $this->where('username', $username)->first();
    }
}
