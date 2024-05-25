<?php

namespace App\Controllers;

use App\Models\Prompt;
use Suin\RSSWriter\Channel;
use Suin\RSSWriter\Feed;
use Suin\RSSWriter\Item;

class Rss extends BaseController
{
    public const ITEM_LIMIT = 24;

    public function index($mode = '')
    {
        $suffix = '';

        switch ($_SESSION['list_mode'] ?? 's') {
            case 'n':
                $suffix = '-r18';
                break;

            case 'a':
                $suffix = '-all';
                break;

            case 's':
            default:
                $suffix = '';
                break;
        }

        $feed    = new Feed();
        $channel = new Channel();
        $channel
            ->title('AIのべりすと プロンプト共有')
            ->description('AIのべりすとのプロンプトを投稿・共有するためのサイト。投稿されたプロンプトは直接AIのべりすとに読み込み可能。')
            ->url(base_url())
            ->feedUrl(base_url('rss' . $suffix . '.xml'))
            ->language('ja-JP')
            // ->pubDate(strtotime('Tue, 21 Aug 2012 19:50:37 +0900'))
            // ->lastBuildDate(strtotime('Tue, 21 Aug 2012 19:50:37 +0900'))
            ->ttl(300)
            ->appendTo($feed);

        $last_timestamp = 0;

        /** @var Prompt */
        $prompt  = model(Prompt::class);
        $prompts = $prompt->findForRss(self::ITEM_LIMIT, $mode);

        foreach ($prompts as $prompt_data) {
            $timestamp = date_timestamp_get(date_create($prompt_data->updated_at_for_sort));
            if ($timestamp > $last_timestamp) {
                $last_timestamp = $timestamp;
            }

            $item = new Item();
            $item
                ->title($prompt_data->title)
                ->description(str_replace(' ', '&nbsp;', esc(mb_strimwidth(preg_replace('#(' . site_url('prompt') . '/(\d+))#u', 'prompt/$2', preg_replace('/[\r\n]/u', ' ', trim($prompt_data->description))), 0, 128, '...'))))
                ->contentEncoded($prompt_data->description)
                ->url(site_url('prompt/' . $prompt_data->id))
                ->guid('prompt/' . $prompt_data->id, false)
                // ->creator()
                ->pubDate($timestamp)
                ->preferCdata(true)
                ->appendTo($channel);
        }

        $channel->pubDate($last_timestamp)->lastBuildDate($last_timestamp);

        $this->response->setContentType('application/rss+xml');

        return $feed->render();
    }
}
