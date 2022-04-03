<?php

namespace App\Models;

use CodeIgniter\Model;

class Ranking extends Model
{
    protected $table            = 'ranking';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $allowedFields    = ['date', 'rank', 'type', 'prompt_id', 'view', 'download', 'import'];
}
