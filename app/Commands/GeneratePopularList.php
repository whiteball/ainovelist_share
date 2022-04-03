<?php

namespace App\Commands;

use App\Models\Prompt_access;
use App\Models\Prompt_access_snapshot;
use App\Models\Ranking;
use CodeIgniter\CLI\BaseCommand;

class GeneratePopularList extends BaseCommand
{
    protected $group     = 'Admin';
    protected $name      = 'admin:generate_popular_list';
    protected $arguments = [
    ];
    protected $options = [
    ];

    public function run(array $params)
    {
        /** @var Prompt_access_snapshot */
        $access = model(Prompt_access_snapshot::class);
        /** @var Ranking */
        $ranking = model(Ranking::class);

        $type   = Prompt_access::COUNT_TYPE_DOWNLOAD_IMPORT;
        $result = $access->diff($type);

        $db = \Config\Database::connect();
        $db->transStart();
        $date    = date_format(date_create(), 'Y-m-d');
        $counter = 1;

        foreach ($result as $row) {
            $ranking->replace([
                'date'      => $date,
                'rank'      => $counter,
                'type'      => $type,
                'prompt_id' => $row->prompt_id,
                'view'      => $row->view,
                'download'  => $row->download,
                'import'    => $row->import,
            ]);
            $counter++;
        }

        $db->transComplete();
    }
}
