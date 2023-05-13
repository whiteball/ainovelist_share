<?php

namespace App\Models;

use CodeIgniter\Model;

class User_password_reset extends Model
{
    protected $table            = 'users_password_reset';
    protected $primaryKey       = 'user_id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $allowedFields    = ['user_id', 'digest', 'updated_at'];
}
