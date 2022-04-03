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
    public function index($date_str, $r18)
    {
        if (! preg_match('/\d{4}-\d{2}-\d{2}/', $date_str)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        /** @var RankingModel */
        $ranking = model(RankingModel::class);
        /** @var Prompt */
        $prompt = model(Prompt::class);

        $r18 = (int) $r18 === 1;

        $result = $ranking->join($prompt->getTable(), $prompt->getTable() . '.id = ' . $ranking->getTable() . '.prompt_id')
            ->where('date', $date_str)
            ->where('type', Prompt_access::COUNT_TYPE_DOWNLOAD_IMPORT)
            ->where('rank <=', 10)
            ->where($ranking->getTable() . '.r18', $r18 ? 1 : 0)
            ->orderBy('rank')
            ->findAll();

        if (! $result || count($result) === 0) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        /** @var Prompt */
        $tag = model(Tag::class);

        $tags = $tag->findByPrompt($result);
        $date = new DateTime($date_str);

        return view('ranking/index', [
            'ranking'    => $result,
            'tags'       => $tags,
            'date'       => $date_str,
            'start_date' => $date->sub(new DateInterval('P8D'))->format('Y/m/d'),
            'end_date'   => $date->add(new DateInterval('P7D'))->format('Y/m/d'),
            'r18'        => $r18,
        ]);
    }
}
