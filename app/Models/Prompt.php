<?php

namespace App\Models;

use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\Database\Exceptions\DataException;
use CodeIgniter\Exceptions\ModelException;
use CodeIgniter\Files\Exceptions\FileNotFoundException;
use CodeIgniter\Model;
use Exception;

class Prompt extends Model
{
    protected $table            = 'prompts';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $allowedFields    = ['user_id', 'title', 'description', 'prompt', 'memory', 'authors_note', 'ng_words', 'scripts', 'character_book', 'parameters', 'chat_template', 'r18', 'draft', 'comment', 'license', 'registered_at', 'updated_at', 'updated_at_for_sort'];
    protected $beforeUpdate     = ['updateSortColumn'];

    /**
     * r18プロンプトの有無の条件を追加する。
     *
     * @param string $mode 表示条件を示す1文字(s/n/a)
     *
     * @return void
     */
    private function _withSafe($mode)
    {
        switch ($mode) {
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
     * セッション値に見合う、r18プロンプトの有無の条件を追加する。
     *
     * @return void
     */
    private function _withSafeSession()
    {
        $this->_withSafe($_SESSION['list_mode'] ?? 's');
    }

    /**
     * ソートカラムを取得する。
     *
     * @param string $sort_mode ソート順を示す1文字(u/c)
     *
     * @return string
     */
    private function _getSortCol($sort_mode)
    {
        switch ($sort_mode) {
            // 投稿
            case 'u':
                // 更新
                return 'IFNULL(updated_at_for_sort, updated_at)';

            case 'c':
            default:
                return 'registered_at';
        }
    }

    /**
     * セッション値に見合う、ソートカラムを取得する。
     *
     * @return string
     */
    private function _getSortColSession()
    {
        return $this->_getSortCol($_SESSION['sort_mode'] ?? 'c');
    }

    /**
     * CookieにNGユーザーがあればクエリに追加する。
     *
     * @return void
     */
    private function _withoutNgUser()
    {
        helper('ng');
        $user_list = clean_up_ng_users();

        if (! empty($user_list)) {
            $this->whereNotIn('user_id', $user_list);
        }
    }

    /**
     * R-18/全年齢の判定付きで取得する。
     *
     * @param int   $limit         Limit
     * @param int   $offset        Offset
     * @param array $ng_prompt_ids NGプロンプトIDのリスト
     *
     * @return array
     *
     * @throws DataException
     */
    public function findAllSafe(int $limit = 0, int $offset = 0, $ng_prompt_ids = [])
    {
        $this->_withSafeSession();
        $this->_withoutNgUser();
        if (! empty($ng_prompt_ids) && is_array($ng_prompt_ids)) {
            $this->whereNotIn('id', $ng_prompt_ids);
        }

        return $this->orderBy($this->_getSortColSession(), 'desc', false)->where('draft', 0)->findAll($limit, $offset);
    }

    /**
     * * R-18/全年齢の判定付きでカウントする。
     *
     * @param bool  $reset         Reset
     * @param bool  $test          Test
     * @param mixed $ng_prompt_ids
     *
     * @return mixed
     *
     * @throws DatabaseException
     * @throws ModelException
     */
    public function countAllResultsSafe(bool $reset = true, bool $test = false, $ng_prompt_ids = [])
    {
        $this->_withSafeSession();
        $this->_withoutNgUser();
        if (! empty($ng_prompt_ids) && is_array($ng_prompt_ids)) {
            $this->whereNotIn('id', $ng_prompt_ids);
        }

        return $this->where('draft', 0)->countAllResults($reset, $test);
    }

    /**
     * @param string $query         検索クエリ
     * @param int    $limit         Limit
     * @param int    $offset        Offset
     * @param string $mode          検索モード。andかor
     * @param array  $ng_prompt_ids NGプロンプトIDのリスト
     *
     * @return list<array|int>|void
     *
     * @throws DatabaseException
     *
     * @todo サーバー仕様が変わったので検索方法を仮にLIKE検索にしている。FULLTEXTインデックスを使った検索を使えるようにする。
     */
    public function captionSearch(string $query, int $limit, int $offset, string $mode = 'and', $ng_prompt_ids = [])
    {
        $operator = '+'; // デフォルトはAND検索
        if (mb_strtolower($mode) === 'or') {
            $operator = '';
        }

        $keywords = explode(' ', preg_replace('/\s+/u', ' ', trim($query)));
        if (empty($keywords)) {
            return;
        }

        // $search_text = $operator . implode(' ' . $operator, $keywords);
        $conditions = [];

        foreach ($keywords as $keyword) {
            $word         = $this->db->escapeLikeString($keyword);
            $conditions[] = "(title LIKE '%{$word}%' OR description LIKE '%{$word}%')";
        }

        $search_text = implode(' AND ', $conditions);

        // 全年齢
        $where = ' AND `r18` = 0';
        $binds = [];

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

        helper('ng');
        $user_list = clean_up_ng_users();
        if (! empty($user_list)) {
            $where .= ' AND `user_id` NOT IN :user_id:';
            $binds['user_id'] = $user_list;
        }

        if (! empty($ng_prompt_ids) && is_array($ng_prompt_ids)) {
            $where .= ' AND `id` NOT IN :prompt_id:';
            $binds['prompt_id'] = $ng_prompt_ids;
        }

        $table_name = $this->db->protectIdentifiers($this->table);
        // $count_result = $this->db->query("SELECT count(*) AS `count` FROM {$table_name} WHERE MATCH (`title`, `description`) AGAINST (? IN BOOLEAN MODE){$where};", [$search_text]);
        $count_result = $this->db->query("SELECT count(*) AS `count` FROM {$table_name} WHERE ({$search_text}){$where};", $binds);
        if (! $count_result || $count_result->getNumRows() === 0) {
            return ['count' => 0, 'result' => []];
        }

        $count = (int) $count_result->getRow()->count;
        if ($count === 0) {
            return ['count' => 0, 'result' => []];
        }

        $sort            = $this->_getSortColSession();
        $binds['limit']  = $limit;
        $binds['offset'] = $offset;
        // $search_result = $this->db->query("SELECT {$table_name}.* FROM {$table_name} WHERE MATCH (`title`, `description`) AGAINST (? IN BOOLEAN MODE){$where} ORDER BY {$sort} desc LIMIT ? OFFSET ?;", [$search_text, $limit, $offset]);
        $search_result = $this->db->query("SELECT {$table_name}.* FROM {$table_name} WHERE ({$search_text}){$where} ORDER BY {$sort} desc LIMIT :limit: OFFSET :offset:;", $binds);
        if (! $search_result || $search_result->getNumRows() === 0) {
            return ['count' => $count, 'result' => []];
        }

        return ['count' => $count, 'result' => $search_result->getResult($this->returnType)];
    }

    public function updateSortColumn($data)
    {
        log_message('error', print_r($data['data']['updated_at_for_sort'], true));
        if (isset($data['data']['updated_at_for_sort']) && $data['data']['updated_at_for_sort'] === true) {
            $data['data']['updated_at_for_sort'] = 'IFNULL(updated_at_for_sort, updated_at)';
        } else {
            $data['data']['updated_at_for_sort'] = 'NULL';
        }

        $this->escape['updated_at_for_sort'] = false;

        return $data;
    }

    /**
     * チャットモードのプロンプト一覧を取得。
     *
     * キャプション検索のクエリ生成部分だけを変更したもの。
     *
     * @param int   $limit         Limit
     * @param int   $offset        Offset
     * @param array $ng_prompt_ids NGプロンプトIDのリスト
     *
     * @return list<array|int>
     *
     * @throws DatabaseException
     * @throws Exception
     * @throws FileNotFoundException
     */
    public function getChatPrompts(int $limit, int $offset, $ng_prompt_ids = [])
    {
        $conditions = ["(parameters LIKE '%<>chat<>%')"];

        $search_text = implode(' AND ', $conditions);

        // 全年齢
        $where = ' AND `r18` = 0';
        $binds = [];

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

        helper('ng');
        $user_list = clean_up_ng_users();
        if (! empty($user_list)) {
            $where .= ' AND `user_id` NOT IN :user_id:';
            $binds['user_id'] = $user_list;
        }

        if (! empty($ng_prompt_ids) && is_array($ng_prompt_ids)) {
            $where .= ' AND `id` NOT IN :prompt_id:';
            $binds['prompt_id'] = $ng_prompt_ids;
        }

        $table_name   = $this->db->protectIdentifiers($this->table);
        $count_result = $this->db->query("SELECT count(*) AS `count` FROM {$table_name} WHERE ({$search_text}){$where};", $binds);
        if (! $count_result || $count_result->getNumRows() === 0) {
            return ['count' => 0, 'result' => []];
        }

        $count = (int) $count_result->getRow()->count;
        if ($count === 0) {
            return ['count' => 0, 'result' => []];
        }

        $sort            = $this->_getSortColSession();
        $binds['limit']  = $limit;
        $binds['offset'] = $offset;
        $search_result   = $this->db->query("SELECT {$table_name}.* FROM {$table_name} WHERE ({$search_text}){$where} ORDER BY {$sort} desc LIMIT :limit: OFFSET :offset:;", $binds);
        if (! $search_result || $search_result->getNumRows() === 0) {
            return ['count' => $count, 'result' => []];
        }

        return ['count' => $count, 'result' => $search_result->getResult($this->returnType)];
    }

    /**
     * RSS向けの新着プロンプトを取得する。
     *
     * @param int    $limit 取得件数
     * @param string $mode  R18プロンプトの表示モード
     *
     * @return array
     *
     * @throws DataException
     */
    public function findForRss($limit, $mode = 's')
    {
        $this->_withSafe($mode);

        return $this
            ->where('draft', 0)
            ->orderBy($this->_getSortCol('u'), 'desc', false)
            ->findAll($limit);
    }
}
