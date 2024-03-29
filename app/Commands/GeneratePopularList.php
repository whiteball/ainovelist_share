<?php

namespace App\Commands;

use App\Models\Prompt;
use App\Models\Prompt_access;
use App\Models\Prompt_access_snapshot;
use App\Models\Prompt_ignored;
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
        /** @var Prompt */
        $prompt = model(Prompt::class);
        /** @var Prompt_access_snapshot */
        $access = model(Prompt_access_snapshot::class);
        /** @var Prompt_ignored */
        $prompt_ignored = model(Prompt_ignored::class);
        /** @var Ranking */
        $ranking = model(Ranking::class);

        $type   = Prompt_access::COUNT_TYPE_DOWNLOAD_IMPORT;
        $result = $access->diff($type);

        $prompts = [];

        foreach ($prompt->whereIn('id', array_map(static fn ($item) => $item->prompt_id, $result))->join($prompt_ignored->getTable(), 'id = prompt_id', 'LEFT')->findAll() as $item) {
            $prompts[$item->id] = $item;
        }

        $db = \Config\Database::connect();
        $db->transStart();
        $date        = date_format(date_create(), 'Y-m-d');
        $counter     = 1;
        $counter_r18 = 1;
        $rank_ignore = -1;

        foreach ($result as $row) {
            if (! isset($prompts[$row->prompt_id]) || $prompts[$row->prompt_id]->draft === '1') {
                // 削除された項目や書きかけ状態は結果に含めない
                continue;
            }

            $rank   = $rank_ignore;
            $is_r18 = (int) $prompts[$row->prompt_id]->r18 === 1;
            // 無視テーブルに含まれていないものだけ順位を付ける
            if (empty($prompts[$row->prompt_id]->prompt_id)) {
                if ($is_r18) {
                    $rank = $counter_r18;
                    $counter_r18++;
                } else {
                    $rank = $counter;
                    $counter++;
                }
            } else {
                $rank_ignore--;
            }

            $ranking->replace([
                'date'      => $date,
                'rank'      => $rank,
                'type'      => $type,
                'r18'       => $prompts[$row->prompt_id]->r18,
                'prompt_id' => $row->prompt_id,
                'view'      => $row->view,
                'download'  => $row->download,
                'import'    => $row->import,
            ]);
        }

        $db->transComplete();
    }
}
