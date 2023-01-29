<?php

namespace App\Controllers;

use App\Models\Prompt;
use App\Models\Tag;
use App\Models\User as UserModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class User extends BaseController
{
    public const ITEM_PER_PAGE = 12;

    /**
     * @var UserModel
     */
    private $user;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->user = model(UserModel::class);
    }

    public function index($user_id)
    {
        $page = (int) ($this->request->getGet('p') ?? 1);

        $user = $this->user->find($user_id);
        if (empty($user)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        /** @var Prompt */
        $prompt  = model(Prompt::class);
        $count   = $prompt->where('user_id', $user_id)->countAllResultsSafe(false);
        $prompts = $prompt->findAllSafe(self::ITEM_PER_PAGE, self::ITEM_PER_PAGE * ($page - 1));

        $tags = [];
        if (! empty($prompts)) {
            /** @var Tag */
            $tag  = model(Tag::class);
            $tags = $tag->findByPrompt($prompts);
        }

        return view('user/index', [
            'user_id'       => $user->id,
            'user_name'     => $user->screen_name,
            'prompts'       => $prompts,
            'tags'          => $tags,
            'count'         => $count,
            'page'          => $page,
            'last_page'     => (int) ceil($count / self::ITEM_PER_PAGE),
            'page_base_url' => 'user/' . $user->id,
        ]);
    }
}
