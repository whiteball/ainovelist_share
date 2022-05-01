<?php

namespace App\Libraries;

use App\Models\Action_log;
use App\Models\Comment as CommentModel;
use App\Models\CommentDeleted;
use App\Models\Prompt;
use App\Models\User;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\Database\Exceptions\DataException;
use InvalidArgumentException;
use ReflectionException;

class Comment
{
    /**
     * @var CommentModel
     */
    private $comment;

    /**
     * @var Action_log
     */
    private $action_log;

    public function __construct()
    {
        $this->comment    = model(CommentModel::class);
        $this->action_log = model(Action_log::class);
    }

    /**
     * コメントを追加する。
     *
     * @param int    $prompt_id プロンプトID
     * @param string $comment   コメント文
     * @param int    $by        コメント投稿者のユーザーID
     * @param int    $reply_to  リプライ先のコメントID
     *
     * @throws DatabaseException
     * @throws InvalidArgumentException
     * @throws ReflectionException
     *
     * @return bool
     */
    public function add($prompt_id, $comment, $by, $reply_to = 0)
    {
        if (empty($prompt_id) || empty($comment) || empty($by)) {
            return false;
        }

        if ($reply_to !== 0) {
            $count = $this->comment->where('prompt_id', $prompt_id)
                ->where('reply_comment_id', $reply_to)
                ->countAll();

            if ($count < 0) {
                return false;
            }
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $comment_id = $this->comment->insert([
            'comment'          => $comment,
            'prompt_id'        => $prompt_id,
            'reply_comment_id' => $reply_to,
            'registered_by'    => $by,
        ]);
        $this->action_log->write($by, 'comment create ' . $comment_id . ' in ' . $prompt_id);
        $db->transComplete();

        return $db->transStatus();
    }

    /**
     * コメントツリーを取得する。
     *
     * @param int $prompt_id プロンプトID
     *
     * @throws DataException
     *
     * @return object[]
     */
    public function get($prompt_id)
    {
        /** @var User */
        $user          = model(User::class);
        $user_table    = $user->getTable();
        $comment_table = $this->comment->getTable();
        $comments      = $this->comment->select($comment_table . '.*')
            ->select('IFNULL(`' . $user_table . '`.`screen_name`, "-") AS user_name', false)
            ->where('prompt_id', $prompt_id)
            ->orderBy($comment_table . '.id', 'desc')
            ->join($user_table, $user_table . '.id = registered_by', 'left')
            ->findAll();

        $result    = [];
        $temp_list = [];

        foreach ($comments as $comment) {
            $temp_list[$comment->id] = $comment;
        }

        foreach ($comments as $comment) {
            if ((int) $comment->reply_comment_id === 0) {
                array_unshift($result, $temp_list[$comment->id]);
            } else {
                if (empty($temp_list[$comment->reply_comment_id]->children)) {
                    $temp_list[$comment->reply_comment_id]->children = [$comment];
                } else {
                    array_unshift($temp_list[$comment->reply_comment_id]->children, $comment);
                }
            }
        }

        return $result;
    }

    /**
     * コメントを削除する。
     * コメントの投稿者かコメントが属するプロンプトの投稿者でないと削除出来ない。
     *
     * @param int $comment_id コメントID
     * @param int $by         削除を実行するユーザーID
     *
     * @throws DatabaseException
     * @throws DataException
     * @throws InvalidArgumentException
     * @throws ReflectionException
     *
     * @return bool
     */
    public function delete($comment_id, $by)
    {
        /** @var CommentDeleted */
        $commentDeleted = model(CommentDeleted::class);
        /** @var Prompt */
        $prompt = model(Prompt::class);

        $comment_table = $this->comment->getTable();
        $prompt_table  = $prompt->getTable();
        $comment       = $this->comment->select($comment_table . '.*')
            ->select($prompt_table . '.user_id')
            ->join($prompt_table, $prompt_table . '.id = ' . $comment_table . '.prompt_id')
            ->groupStart()
            ->where($prompt_table . '.user_id', $by)
            ->orWhere($comment_table . '.registered_by', $by)
            ->groupEnd()
            ->find($comment_id);
        if (! $comment) {
            return false;
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $comment->deleted_by = $by;
        $commentDeleted->insert($comment);
        if ((int) $comment->registered_by === (int) $by) {
            $message = '###このコメントは投稿者によって削除されました###';
        } else {
            $message = '###このコメントはプロンプト投稿者によって削除されました###';
        }

        $this->comment->update($comment->id, ['comment' => $message, 'registered_by' => 0]);
        $this->action_log->write($by, 'comment delete ' . $comment->id . ' in ' . $comment->prompt_id);
        $db->transComplete();

        return $db->transStatus();
    }

    /**
     * 投稿したコメント一覧を取得する。
     *
     * @param int $user_id ユーザーID
     *
     * @throws DataException
     * @throws InvalidArgumentException
     *
     * @return object[]
     */
    public function get_posted($user_id)
    {
        /** @var Prompt */
        $prompt = model(Prompt::class);

        $comment_table = $this->comment->getTable();
        $prompt_table  = $prompt->getTable();

        $db = \Config\Database::connect();

        return $this->comment->select($comment_table . '.*')
            ->select($prompt_table . '.title AS prompt_title')
            ->select($prompt_table . '.draft')
            ->select('IF(`' . $prompt_table . '`.`user_id` = ' . $db->escape($user_id) . ',1,0) AS own_prompt', false)
            ->join($prompt_table, $prompt_table . '.id = ' . $comment_table . '.prompt_id', 'left')
            ->where('registered_by', $user_id)
            ->orderBy($comment_table . '.id', 'desc')
            ->findAll();
    }

    /**
     * 受け取ったコメント一覧を取得する。
     *
     * @param int $user_id ユーザーID
     *
     * @throws DataException
     *
     * @return object[]
     */
    public function get_received($user_id)
    {
        /** @var Prompt */
        $prompt = model(Prompt::class);

        $comment_table = $this->comment->getTable();
        $prompt_table  = $prompt->getTable();

        return $this->comment->select($comment_table . '.*')
            ->select($prompt_table . '.title AS prompt_title')
            ->select($prompt_table . '.draft')
            ->join($prompt_table, $prompt_table . '.id = ' . $comment_table . '.prompt_id', 'left')
            ->where('user_id', $user_id)
            ->where('registered_by !=', 0)
            ->orderBy($comment_table . '.id', 'desc')
            ->findAll();
    }
}
