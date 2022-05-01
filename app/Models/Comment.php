<?php

namespace App\Models;

use CodeIgniter\Model;

class Comment extends Model
{
    protected $table            = 'comments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $allowedFields    = [
        'comment',
        'prompt_id',
        'reply_comment_id',
        'registered_by',
        'registered_at',
    ];
}
