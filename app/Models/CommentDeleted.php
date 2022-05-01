<?php

namespace App\Models;

use CodeIgniter\Model;

class CommentDeleted extends Model
{
    protected $table            = 'comments_deleted';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $allowedFields    = [
        'id',
        'comment',
        'prompt_id',
        'reply_comment_id',
        'registered_by',
        'registered_at',
        'deleted_by',
        'deleted_at',
    ];
}
