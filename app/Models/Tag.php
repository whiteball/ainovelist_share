<?php

namespace App\Models;

use CodeIgniter\Database\Exceptions\DataException;
use CodeIgniter\Model;
use InvalidArgumentException;

class Tag extends Model
{
    protected $table            = 'tags';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $allowedFields    = ['prompt_id', 'tag_name'];

    /**
     * プロンプトに紐付いたタグのリストを返す。
     *
     * @param array|int $prompt_id プロンプトIDかその配列
     *
     * @throws DataException
     * @throws InvalidArgumentException
     *
     * @return array
     */
    public function findByPrompt($prompt_id)
    {
        if (is_array($prompt_id)) {
            if (isset($prompt_id[0]->id)) {
                $prompt_id = array_map(static fn ($val) => $val->id, $prompt_id);
            }

            $list   = $this->whereIn('prompt_id', $prompt_id)->orderBy('prompt_id', 'asc')->orderBy('id', 'asc')->findAll();
            $result = [];

            foreach ($prompt_id as $id) {
                $result[$id] = [];
            }

            foreach ($list as $item) {
                $result[$item->prompt_id][] = $item;
            }

            return $result;
        }

        return $this->where('prompt_id', $prompt_id)->orderBy('id', 'asc')->findAll();
    }

    /**
     * Cookieに登録されたNGタグを含んでいるプロンプトIDのリストを取得する。
     * 
     * @return string[] プロンプトIDのリスト
     */
    public function findPromptIdsByNgTags()
    {
        helper('ng');
        $tag_list = clean_up_ng_tags();

        if (empty($tag_list)) {
            return [];
        }

        return array_map(function ($val) {
            return $val->prompt_id;
        }, $this->select('prompt_id')->whereIn('tag_name', $tag_list)->groupBy('prompt_id')->findAll());
    }
}
