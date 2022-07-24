<?php

namespace App\Models;

use CodeIgniter\Model;

class Yamiotome_token extends Model
{
    protected $table            = 'yamiotome_tokens';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $allowedFields    = ['id', 'token', 'tail'];
}
