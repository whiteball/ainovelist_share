<?php

namespace App\Models;

use CodeIgniter\Model;

class Prompt_deleted extends Model
{
    protected $table            = 'prompts_deleted';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $allowedFields    = ['id', 'user_id', 'title', 'description', 'prompt', 'memory', 'authors_note', 'ng_words', 'scripts', 'character_book', 'r18', 'draft', 'comment', 'registered_at', 'updated_at', 'updated_at_for_sort', 'deleted_at'];
}
