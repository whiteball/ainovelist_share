<?php

namespace App\Commands;

use App\Libraries\Prompt as PromptLib;
use App\Models\Action_log;
use App\Models\Prompt;
use App\Models\User;
use App\Models\User_deleted;
use CodeIgniter\CLI\BaseCommand;

class DeleteUser extends BaseCommand
{
    protected $group     = 'Admin';
    protected $name      = 'admin:delete_user';
    protected $arguments = [
        'id' => 'user id',
    ];
    protected $options = [
    ];

    public function run(array $params)
    {
        if (! empty($params[0])) {
            $user_id = (int) $params[0];
        } else {
            echo 'invalid argument.';

            return;
        }
        /** @var Action_log */
        $action_log = model(Action_log::class);
        /** @var User */
        $user = model(User::class);
        /** @var User_deleted */
        $user_deleted = model(User_deleted::class);
        /** @var Prompt */
        $prompt     = model(Prompt::class);
        $prompt_lib = new PromptLib();

        $db = \Config\Database::connect();
        $db->transStart();

        $prompt_list = $prompt->where('user_id', $user_id)->findAll();

        foreach ($prompt_list as $prompt_data) {
            $prompt_lib->delete($prompt_data, $user_id);
        }

        $userData = $user->find($user_id);
        $user_deleted->save($userData);
        $user->delete($user_id);

        $action_log->write(0, 'user delete by admin');

        $db->transComplete();

        if (! $db->transStatus()) {
            echo 'db error.';

            return;
        }
    }
}
