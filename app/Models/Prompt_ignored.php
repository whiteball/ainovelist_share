<?php

namespace App\Models;

use CodeIgniter\Model;

class Prompt_ignored extends Model
{
    protected $table            = 'prompts_ignored';
    protected $primaryKey       = 'prompt_id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $allowedFields    = ['prompt_id'];
}
