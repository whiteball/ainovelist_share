<?php

namespace App\Models;

use CodeIgniter\Model;

class Prompt extends Model
{
    protected $table            = 'prompts';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $allowedFields    = ['user_id', 'title', 'description', 'prompt', 'memory', 'authors_note', 'ng_words', 'r18', 'scripts', 'character_book', 'registered_at', 'updated_at'];
}
