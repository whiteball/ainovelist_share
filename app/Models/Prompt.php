<?php

namespace App\Models;

use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\Database\Exceptions\DataException;
use CodeIgniter\Exceptions\ModelException;
use CodeIgniter\Model;

class Prompt extends Model
{
    protected $table            = 'prompts';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $allowedFields    = ['user_id', 'title', 'description', 'prompt', 'memory', 'authors_note', 'ng_words', 'scripts', 'character_book', 'r18', 'draft', 'registered_at', 'updated_at'];

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

    /**
     * R-18/全年齢の判定付きで取得する。
     * 
     * @param int $limit Limit
     * @param int $offset Offset
     * @return array 
     * @throws DataException 
     */
    public function findAllSafe(int $limit = 0, int $offset = 0)
    {
        $this->_withSafe();
        return $this->where('draft', 0)->findAll($limit, $offset);
    }

    /**
     * * R-18/全年齢の判定付きでカウントする。
     * @param bool $reset Reset
     * @param bool $test Test
     * @return mixed 
     * @throws ModelException 
     * @throws DatabaseException 
     */
    public function countAllResultsSafe(bool $reset = true, bool $test = false)
    {
        $this->_withSafe();
        return $this->where('draft', 0)->countAllResults($reset, $test);
    }

    /**
     * 
     * @param string $query 検索クエリ
     * @param int $limit Limit
     * @param int $offset Offset
     * @param string $mode 検索モード。andかor
     * @return void|(int|array)[] 
     * @throws DatabaseException 
     */
    public function captionSearch(string $query, int $limit, int $offset, string $mode = 'and')
    {
        $operator = '+'; // デフォルトはAND検索
        if (mb_strtolower($mode) === 'or') {
            $operator = '';
        }

        $keywords = explode(' ', preg_replace('/\s+/u', ' ', trim($query)));
        if (empty($keywords)) {
            return;
        }

        $search_text = $operator . implode(' ' . $operator, $keywords);
        // 全年齢
        $where = ' AND `r18` = 0';
        switch ($_SESSION['list_mode'] ?? 's') {
            case 'a':
                // なにもしない(どっちも)
                $where = '';
                break;
            case 'n':
                // R-18のみ
                $where = ' AND `r18` = 1';
                break;
        }

        $where .= ' AND `draft` = 0';
        $table_name = $this->db->protectIdentifiers($this->table);
        $count_result = $this->db->query("SELECT count(*) AS `count` FROM {$table_name} WHERE MATCH (`title`, `description`) AGAINST (? IN BOOLEAN MODE){$where};", [$search_text]);
        if (! $count_result || $count_result->getNumRows() === 0) {
            return ['count' => 0, 'result' => []];
        }

        $count = (int) $count_result->getRow()->count;
        if ($count === 0) {
            return ['count' => 0, 'result' => []];
        }
        

        $search_result = $this->db->query("SELECT {$table_name}.* FROM {$table_name} WHERE MATCH (`title`, `description`) AGAINST (? IN BOOLEAN MODE){$where} ORDER BY `updated_at` desc LIMIT ? OFFSET ?;", [$search_text, $limit, $offset]);
        if (! $search_result || $search_result->getNumRows() === 0) {
            return ['count' => $count, 'result' => []];
        }

        return ['count' => $count, 'result' => $search_result->getResult($this->returnType)];
    }
}
