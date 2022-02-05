<?php

namespace App\Libraries;

use App\Models\Action_log;
use App\Models\Prompt as PromptModel;
use App\Models\Prompt_deleted;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\Database\Exceptions\DataException;
use InvalidArgumentException;
use ReflectionException;

class Prompt
{
    /**
     * プロンプトを削除する。
     *
     * @param int|object $prompt_id プロンプトIDまたはプロンプトのレコード
     * @param int        $by        削除を行うユーザーID
     *
     * @throws DatabaseException
     * @throws DataException
     * @throws InvalidArgumentException
     * @throws ReflectionException
     *
     * @return bool
     */
    public function delete($prompt_id, $by)
    {
        /** @var PromptModel */
        $prompt = model(PromptModel::class);
        if (is_int($prompt_id)) {
            $data = $prompt->find($prompt_id);
        } else {
            $data = $prompt_id;
        }

        if (empty($data)) {
            return false;
        }

        /** @var Prompt_deleted */
        $prompt_deleted = model(Prompt_deleted::class);

        /** @var Tag */
        $tag = model(Tag::class);

        /** @var Action_log */
        $action_log = model(Action_log::class);

        $tag_ids   = [];
        $tag_names = [];

        foreach ($tag->where('prompt_id', $data->id)->findAll() as $row) {
            $tag_ids[]   = $row->id;
            $tag_names[] = $row->tag_name;
        }

        $db = \Config\Database::connect();
        $db->transStart();
        $prompt_deleted->save($data);
        $prompt->delete($data->id);
        if (! empty($tag_ids)) {
            $tag->delete($tag_ids);
        }

        $action_log->write($by, 'prompt delete ' . $data->id . ' tag delete [' . implode(' ', $tag_names) . ']');
        $db->transComplete();

        return $db->transStatus();
    }
}
