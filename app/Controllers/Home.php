<?php

namespace App\Controllers;

use App\Libraries\Prompt as PromptLib;
use App\Libraries\Comment;
use App\Models\Prompt;
use App\Models\Prompt_access;
use App\Models\Tag;
use App\Models\User;

class Home extends BaseController
{
    public const ITEM_PER_PAGE = 12;

    public function index()
    {
        $page = (int) ($this->request->getGet('p') ?? 1);

        /** @var Prompt */
        $prompt  = model(Prompt::class);
        $prompts = $prompt->findAllSafe(self::ITEM_PER_PAGE, self::ITEM_PER_PAGE * ($page - 1));
        $count   = $prompt->countAllResultsSafe();

        $tags = [];
        if (! empty($prompts)) {
            /** @var Tag */
            $tag  = model(Tag::class);
            $tags = $tag->findByPrompt($prompts);
        }

        return view('index', ['prompts' => $prompts, 'tags' => $tags, 'count' => $count, 'page' => $page, 'last_page' => (int) ceil($count / self::ITEM_PER_PAGE)]);
    }

    public function about()
    {
        return view('about');
    }

    public function register()
    {
        if ($this->_isLoggedIn()) {
            return redirect('mypage');
        }

        if ($this->isPost() && $this->validate([
            'login_name' => ['label' => 'ログインID', 'rules' => ['required', 'alpha_numeric', 'max_length[255]', 'is_unique[users.login_name]']],
            'screen_name' => ['label' => 'ユーザー名', 'rules' => ['required', 'max_length[100]']],
            'password' => ['label' => 'パスワード', 'rules' => ['required', 'min_length[12]']],
            'password_confirm' => ['label' => 'パスワード(再入力)', 'rules' => ['required_with[password]', 'matches[password]']],
        ])) {
            /** @var User */
            $user    = model(User::class);
            $user_id = $user->insert([
                'login_name'  => $this->request->getPost('login_name'),
                'screen_name' => $this->request->getPost('screen_name'),
                'password'    => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            ]);

            $this->action_log->write($user_id, 'user create');

            return view('register/complete');
        }

        return view('register/index', ['validation' => service('validation')]);
    }

    public function login()
    {
        if ($this->_isLoggedIn()) {
            return redirect('/');
        }

        if ($this->isPost() && $this->validate([
            'login_name' => ['label' => 'ログインID', 'rules' => ['required', 'alpha_numeric', 'max_length[255]']],
            'password' => ['label' => 'パスワード', 'rules' => ['required']],
        ])) {
            /** @var User */
            $user      = model(User::class);
            $loginUser = $user->where('login_name', $this->request->getPost('login_name'))->first();
            if (empty($loginUser) || ! password_verify($this->request->getPost('password'), $loginUser->password)) {
                return view('login', ['validation' => service('validation'), 'error_message' => 'ログインIDかパスワードが違います']);
            }

            $_SESSION['login'] = $loginUser->id;
            $this->action_log->write($loginUser->id, 'user login');

            return redirect('/');
        }

        return view('login', ['validation' => service('validation')]);
    }

    public function config()
    {
        return redirect('mypage');
    }

    public function prompt($prompt_id, $asFile = false)
    {
        /** @var Prompt_access */
        $prompt_access = model(Prompt_access::class);

        /** @var Prompt */
        $prompt     = model(Prompt::class);
        $promptData = $prompt->join($prompt_access->getTable(), 'id = prompt_id')->where('draft', 0)->find($prompt_id);

        if (empty($promptData)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('指定のプロンプトは存在しないか、非公開状態です。');
        }

        /** @var User */
        $user     = model(User::class);
        $userData = $user->find($promptData->user_id);

        /** @var Tag */
        $tag       = model(Tag::class);
        $tagResult = $tag->findByPrompt($prompt_id);

        $promptData->{'char_book'} = json_decode($promptData->character_book, JSON_OBJECT_AS_ARRAY);
        $promptData->{'script'}    = json_decode($promptData->scripts, JSON_OBJECT_AS_ARRAY);

        if ($asFile) {
            $main      = str_replace(' ', '&nbsp;', preg_replace('/[\r\n]/u', '', nl2br($promptData->prompt, false)));
            $param     = '31<>29<>93<>150<>256<>3<>1024<>NaN<>0<>NaN<>40<>128<>37<>30<>20<>20<>20<>';
            $char_book = '';
            $scripts   = '';

            if (! empty($promptData->char_book)) {
                foreach ($promptData->char_book as $char) {
                    $char_book .= $char['tag'] . '<|entry|>' . $char['content'] . '<|entry|>';
                }
            }

            if (! empty($promptData->script)) {
                foreach ($promptData->script as $script) {
                    if (! empty($scripts)) {
                        $scripts .= '<|entry|>';
                    }

                    $scripts .= $script['type'] . '<|sp|>' . $script['in'] . '<|sp|>' . $script['out'];
                }
            }

            if (trim($this->request->header('origin')) === 'Origin: https://ai-novel.com') {
                $prompt_access->countUp($prompt_id, Prompt_access::COUNT_TYPE_IMPORT);
            } else {
                $prompt_access->countUp($prompt_id, Prompt_access::COUNT_TYPE_DOWNLOAD);
            }

            $novel = preg_replace('/\r\n|\r/u', "\n", "{$main}<|endofsection|>{$promptData->memory}<|endofsection|>{$promptData->authors_note}<|endofsection|>{$param}<|endofsection|>{$char_book}<|endofsection|>{$promptData->ng_words}<|endofsection|>{$promptData->title}<|endofsection|><|endofsection|>{$scripts}");

            return $this->response->download(strip_tags($promptData->title) . '.novel', $novel);
        }

        if ($prompt_access->countUp($prompt_id, Prompt_access::COUNT_TYPE_VIEW)) {
            $promptData->view++;
        }

        $promptLib = new PromptLib();
        $viewData = [
            'prompt' => $promptData,
            'author' => $userData->screen_name,
            'tags' => $tagResult,
            'ogp' => $promptLib->getImageUrl($prompt_id),
            'loginUserId' => $this->loginUserId,
            'validation' => service('validation'),
        ];

        // コメント処理
        if ($promptData->comment !== '0') {
            $comment = new Comment();
            if ($this->request->getPost('type') === 'comment') {
                if ($this->validate([
                    'reply-to' => ['label' => 'リプライ先', 'rules' => ['required', 'numeric']],
                    'comment' => ['label' => 'コメント', 'rules' => ['required', 'max_length[2048]']],
                ])) {
                    $postData = $this->request->getPost();
                    if ($comment->add($prompt_id, $postData['comment'], $this->loginUserId, $postData['reply-to'])) {
                        $viewData['successMessage']    = 'コメントを投稿しました';
                        $viewData['clearCommentInput'] = true;
                    } else {
                        $viewData['errorMessage'] = 'コメント投稿に失敗しました';
                    }
                } else {
                    $viewData['errorMessage'] = 'コメント投稿に失敗しました';
                }

                $viewData['openComment'] = true;
            } elseif ($this->request->getPost('type') === 'comment-delete') {
                if ($this->validate([
                    'comment_id' => ['label' => 'コメントID', 'rules' => ['required']],
                ])) {
                    $postData = $this->request->getPost();
                    if ($comment->delete($postData['comment_id'], $this->loginUserId)) {
                        $viewData['successMessage'] = 'コメントを削除しました';
                    } else {
                        $viewData['errorMessage'] = 'コメント削除に失敗しました';
                    }
                } else {
                    $viewData['errorMessage'] = 'コメント削除に失敗しました';
                }

                $viewData['clearCommentInput'] = true;
                $viewData['openComment']       = true;
            }

            // コメント取得
            $viewData['comments'] = $comment->get($prompt_id);
        }

        return view('prompt', $viewData);
    }

    public function logout()
    {
        if ($this->_isNotLoggedIn()) {
            return redirect('/');
        }

        if (! $this->isPost()) {
            return redirect('mypage');
        }

        $this->action_log->write($_SESSION['login'], 'user logout');
        unset($_SESSION['login']);

        return redirect('/');
    }

    public function search()
    {
        $query = trim(preg_replace('/\s+/u', ' ', $this->request->getGet('q') ?? ''));
        if (empty($query)) {
            return redirect('/');
        }

        $page = (int) ($this->request->getGet('p') ?? 1);

        /** @var Prompt */
        $prompt = model(Prompt::class);
        $result = $prompt->captionSearch($query, self::ITEM_PER_PAGE, self::ITEM_PER_PAGE * ($page - 1));

        $count   = $result['count'];
        $prompts = $result['result'];
        $tags    = [];
        if (empty($prompts)) {
            $count   = 0;
            $prompts = [];
        } else {
            /** @var Tag */
            $tag  = model(Tag::class);
            $tags = $tag->findByPrompt($prompts);
        }

        return view('tag/search', [
            'query'         => $query,
            'search_mode'   => 'caption',
            'prompts'       => $prompts,
            'tags'          => $tags,
            'count'         => $count,
            'page'          => $page,
            'last_page'     => (int) ceil($count / self::ITEM_PER_PAGE),
            'page_base_url' => 'search/caption/?q=' . $query,
        ]);
    }

    public function script()
    {
        return view('script');
    }
}
