<?php

namespace App\Commands;

use App\Libraries\Prompt;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CreateImage extends BaseCommand
{
    protected $group     = 'Admin';
    protected $name      = 'admin:create_image';
    protected $arguments = [
        'id' => 'prompt id',
    ];
    protected $options = [
        '-a' => 'create all',
    ];

    public function run(array $params)
    {
        if (CLI::getOption('a')) {
            $id = null;
        } elseif (! empty($params[0])) {
            $id = (int) $params[0];
        } else {
            echo 'invalid argument.';

            return;
        }

        /** @var Prompt */
        $prompt = new Prompt();
        $result = $prompt->createImage($id < 0 ? null : $id);
        if (! $result) {
            echo 'not exist.';
        }
    }
}
