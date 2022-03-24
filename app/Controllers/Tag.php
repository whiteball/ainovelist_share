<?php

namespace App\Controllers;

use App\Models\Prompt;
use App\Models\Tag as TagModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Tag extends BaseController
{
    public const ITEM_PER_PAGE = 12;

    /**
     * @var TagModel
     */
    private $tag;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->tag = model(TagModel::class);
    }

    public function index($tag_name)
    {
        $page = (int) ($this->request->getGet('p') ?? 1);

        /** @var Prompt */
        $prompt = model(Prompt::class);
        if ($tag_name === 'R-18') {
            $prompt->where('r18', 1);
        } else {
            $prompt_ids = $this->tag->select('prompt_id')->where('tag_name', $tag_name)->findAll();
            array_walk($prompt_ids, static function (&$val) {
                $val = $val->prompt_id;
            });

            $prompt->whereIn('id', $prompt_ids);
        }

        $count   = 0;
        $prompts = [];
        $tags    = [];
        if (! empty($prompt_ids) || $tag_name === 'R-18') {
            $count   = $prompt->countAllResultsSafe(false);
            $prompts = $prompt->findAllSafe(self::ITEM_PER_PAGE, self::ITEM_PER_PAGE * ($page - 1));
            if (empty($prompts)) {
                $count   = 0;
                $prompts = [];
            } else {
                $tags = $this->tag->findByPrompt($prompts);
            }
        }

        return view('tag/index', [
            'tag_name'      => $tag_name,
            'prompts'       => $prompts,
            'tags'          => $tags,
            'count'         => $count,
            'page'          => $page,
            'last_page'     => (int) ceil($count / self::ITEM_PER_PAGE),
            'page_base_url' => 'tag/' . $tag_name,
        ]);
    }

    public function list()
    {
        $tag_count = $this->tag->select('tag_name')->selectCount('*', 'count')->groupBy('tag_name')->orderBy('tag_name', 'asc')->findAll();

        return view('tag/list', ['tag_count' => $tag_count]);
    }

    public function search()
    {
        $query = trim(preg_replace('/\s+/u', ' ', $this->request->getGet('q') ?? ''));
        if (empty($query)) {
            return redirect('/');
        }

        $page = (int) ($this->request->getGet('p') ?? 1);

        if (isset($_SESSION['tag_search_cache_query']) && $_SESSION['tag_search_cache_query'] === $query) {
            $prompt_ids = $_SESSION['tag_search_cache_ids'];
        } else {
            $keyword_list = explode(' ', $query);
            $prompt_ids   = [];

            foreach ($keyword_list as $keyword) {
                $result = $this->tag->select('prompt_id')->like('tag_name', $keyword)->findAll();
                if (empty($result)) {
                    $prompt_ids = [];
                    break;
                }

                $prompt_ids = array_map(static fn ($val) => $val->prompt_id, $result);
                $this->tag->whereIn('prompt_id', $prompt_ids);
            }

            $this->tag->resetQuery();
        }

        /** @var Prompt */
        $prompt = model(Prompt::class);
        $prompt->whereIn('id', $prompt_ids);

        $count   = 0;
        $prompts = [];
        $tags    = [];
        if (! empty($prompt_ids)) {
            $_SESSION['tag_search_cache_query'] = $query;
            $_SESSION['tag_search_cache_ids']   = $prompt_ids;
            $this->session->markAsTempdata(['tag_search_cache_query', 'tag_search_cache_ids'], 120);
            $count   = $prompt->countAllResultsSafe(false);
            $prompts = $prompt->findAllSafe(self::ITEM_PER_PAGE, self::ITEM_PER_PAGE * ($page - 1));
            if (empty($prompts)) {
                $count   = 0;
                $prompts = [];
            } else {
                $tags = $this->tag->findByPrompt($prompts);
            }
        }

        return view('tag/search', [
            'query'         => $query,
            'search_mode'   => 'tag',
            'prompts'       => $prompts,
            'tags'          => $tags,
            'count'         => $count,
            'page'          => $page,
            'last_page'     => (int) ceil($count / self::ITEM_PER_PAGE),
            'page_base_url' => 'search/tag/?q=' . $query,
        ]);
    }
}
