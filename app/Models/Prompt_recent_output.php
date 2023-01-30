<?php

namespace App\Models;

use CodeIgniter\Model;
use DateTime;
use ReflectionException;

class Prompt_recent_output extends Model
{
    protected $table            = 'prompts_recent_output';
    protected $primaryKey       = 'prompt_id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $allowedFields    = ['prompt_id', 'outputted_at'];

    /**
     * 最後にダウンロード/インポートにアクセスがあった日時を記録する。
     *
     * @param mixed  $prompt_id プロンプトID
     * @param string $date      日付文字列
     *
     * @return bool
     *
     * @throws ReflectionException
     */
    public function updateDateTime($prompt_id, $date = 'now')
    {
        if (empty($prompt_id)) {
            return false;
        }

        $dateObj = new DateTime($date);

        return $this->save([
            'prompt_id'    => $prompt_id,
            'outputted_at' => $dateObj->format('Y-m-d H:i:s'),
        ]);
    }
}
