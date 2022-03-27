<?php

namespace App\Commands;

use App\Models\Prompt_access_snapshot;
use CodeIgniter\CLI\BaseCommand;

class CopyAccessCounter extends BaseCommand
{
    protected $group     = 'Admin';
    protected $name      = 'admin:copy_access_counter';
    protected $arguments = [
    ];
    protected $options = [
    ];

    public function run(array $params)
    {
        /** @var Prompt_access_snapshot */
        $access = model(Prompt_access_snapshot::class);
        $access->copy();
    }
}
