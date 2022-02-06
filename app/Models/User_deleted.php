<?php

namespace App\Models;

use CodeIgniter\Model;

class User_deleted extends Model
{
    protected $table      = 'users_deleted';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = false;
    protected $returnType     = 'object';
    protected $allowedFields = ['id', 'login_name', 'screen_name', 'password', 'registered_at', 'deleted_at'];
}