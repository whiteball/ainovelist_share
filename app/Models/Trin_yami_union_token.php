<?php

namespace App\Models;

use CodeIgniter\Model;

class Trin_yami_union_token extends Model
{
    protected $table            = 'trin_yami_union_tokens';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $allowedFields    = ['id', 'token', 'tail'];
}
