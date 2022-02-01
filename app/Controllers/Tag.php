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

        $count   = $prompt->orderBy('updated_at', 'desc')->countAllResults(false);
        $prompts = $prompt->findAll(self::ITEM_PER_PAGE, self::ITEM_PER_PAGE * ($page - 1));

        if (empty($prompts)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $tags = $this->tag->findByPrompt($prompts);

        return view('tag/index', [
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
}
