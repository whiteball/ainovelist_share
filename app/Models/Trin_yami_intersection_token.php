<?php

namespace App\Models;

use CodeIgniter\Model;

class Trin_yami_intersection_token extends Model
{
    protected $table            = 'trin_yami_intersection_tokens';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $allowedFields    = ['id', 'token', 'tail'];
}
