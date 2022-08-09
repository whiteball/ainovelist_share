<?php

namespace App\Controllers;

use App\Models\Prompt;
use App\Models\Prompt_access;
use App\Models\Ranking as RankingModel;
use App\Models\Tag;
use DateInterval;
use DateTime;

class Ranking extends BaseController
{
    public function index($r18, $date_str = '')
    {
        /** @var RankingModel */
        $ranking = model(RankingModel::class);

        $base_date_str = $date_str;
        if ($date_str === '') {
            $latest = $ranking->select('date')->orderBy('date', 'desc')->findAll(1);
            if (! empty($latest)) {
                $date_str = $latest[0]->date;
            }
        }

        if (! preg_match('/\d{4}-\d{2}-\d{2}/', $date_str)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        /** @var Prompt */
        $prompt = model(Prompt::class);

        $r18 = (int) $r18 === 1;

        $result = $ranking->join($prompt->getTable(), $prompt->getTable() . '.id = ' . $ranking->getTable() . '.prompt_id')
            ->where('date', $date_str)
            ->where('type', Prompt_access::COUNT_TYPE_DOWNLOAD_IMPORT)
            ->where('rank >=', 1)
            ->where('rank <=', 10)
            ->where($ranking->getTable() . '.r18', $r18 ? 1 : 0)
            ->orderBy('rank')
            ->findAll();

        if (count($result) === 0) {
            $tags = [];
        } else {
            /** @var Prompt */
            $tag = model(Tag::class);

            $tags = $tag->findByPrompt($result);
        }

        $date = new DateTime($date_str);

        return view('ranking/index', [
            'ranking'    => $result,
            'tags'       => $tags,
            'date'       => $base_date_str,
            'start_date' => $date->sub(new DateInterval('P7D'))->format('Y/m/d'),
            'end_date'   => $date->add(new DateInterval('P6D'))->format('Y/m/d'),
            'r18'        => $r18,
        ]);
    }

    public function history($r18)
    {
        /** @var RankingModel */
        $ranking = model(RankingModel::class);

        $r18 = (int) $r18 === 1;

        $date_list = $ranking->select('date')->distinct()->where('r18', $r18 ? 1 : 0)->orderBy('date', 'desc')->findAll();

        return view('ranking/history', [
            'date_list' => $date_list,
            'r18'       => $r18,
        ]);
    }
}
