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

    private function _withSafe()
    {
        switch ($_SESSION['list_mode'] ?? 's') {
            case 's':
                // 全年齢
                $this->where('r18', 0);
                break;
            case 'a':
                // なにもしない(どっちも)
                break;
            case 'n':
                // R-18のみ
                $this->where('r18', 1);
                break;
            default:
                // 全年齢
                $this->where('r18', 0);
                break;
        }
    }

    public function findAllSafe(int $limit = 0, int $offset = 0)
    {
        $this->_withSafe();
        return $this->findAll($limit, $offset);
    }

    public function countAllResultsSafe(bool $reset = true)
    {
        $this->_withSafe();
        return $this->countAllResults($reset);
    }
}
