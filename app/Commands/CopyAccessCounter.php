<?php

namespace App\Commands;

use App\Models\Prompt_access_snapshot;
use App\Models\Ranking;
use CodeIgniter\CLI\BaseCommand;
use DateTime;

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
        /** @var Ranking */
        $ranking = model(Ranking::class);
        $date    = new DateTime();
        $result  = $ranking->where('date', $date->format('Y-m-d'))->selectCount('*', 'count')->get();
        if (! ($result && $result->getRow()->count > 0)) {
            echo 'error: admin:copy_access_counter: ranking not exist.';
            log_message('error', __METHOD__ . ': 本日付のランキングが存在しません');

            return;
        }

        /** @var Prompt_access_snapshot */
        $access = model(Prompt_access_snapshot::class);
        $access->copy();
    }
}
