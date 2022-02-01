<?php

namespace App\Controllers;

use App\Models\Prompt;
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
        $prompts = $prompt->orderBy('updated_at', 'desc')->findAll(self::ITEM_PER_PAGE, self::ITEM_PER_PAGE * ($page - 1));
        $count   = $prompt->countAll();

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
            return redirect('config');
        }

        if ($this->isPost() && $this->validate([
            'login_name' => ['label' => 'ログインID', 'rules' => ['required', 'alpha_numeric', 'max_length[255]', 'is_unique[users.login_name]']],
            'screen_name' => ['label' => 'ユーザー名', 'rules' => ['required', 'max_length[100]']],
            'password' => ['label' => 'パスワード', 'rules' => ['required', 'min_length[12]']],
            'password_confirm' => ['label' => 'パスワード(再入力)', 'rules' => ['required_with[password]', 'matches[password]']],
        ])) {
            /** @var User */
            $user = model(User::class);
            $user->save([
                'login_name'  => $this->request->getPost('login_name'),
                'screen_name' => $this->request->getPost('screen_name'),
                'password'    => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            ]);

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

            return redirect('/');
        }

        return view('login', ['validation' => service('validation')]);
    }

    public function config()
    {
        if ($this->_isNotLoggedIn()) {
            return redirect('/');
        }

        /** @var User */
        $user      = model(User::class);
        $loginUser = $user->find($_SESSION['login']);

        $success_message = '';
        if ($this->isPost() && $this->validate([
            'screen_name' => ['label' => 'ユーザー名', 'rules' => ['required', 'max_length[100]']],
        ])) {
            $loginUser->screen_name = $this->request->getPost('screen_name');
            $user->save($loginUser);
            $success_message = 'ユーザー名変更しました';
        }

        /** @var Prompt */
        $prompt  = model(Prompt::class);
        $prompts = $prompt->where('user_id', $this->loginUserId)->findAll();

        return view('config', [
            'validation'      => service('validation'),
            'screen_name'     => $loginUser->screen_name,
            'success_message' => $success_message,
            'prompts'         => $prompts,
        ]);
    }

    public function prompt($prompt_id, $asFile = false)
    {
        /** @var Prompt */
        $prompt     = model(Prompt::class);
        $promptData = $prompt->find($prompt_id);

        if (empty($promptData)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('指定のプロンプトは存在しません。');
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

            $novel = preg_replace('/\r\n|\r/u', "\n", "{$main}<|endofsection|>{$promptData->memory}<|endofsection|>{$promptData->authors_note}<|endofsection|>{$param}<|endofsection|>{$char_book}<|endofsection|>{$promptData->ng_words}<|endofsection|>{$promptData->title}<|endofsection|><|endofsection|>{$scripts}");

            return $this->response->download($promptData->title . '.novel', $novel);
        }

        return view('prompt', ['prompt' => $promptData, 'author' => $userData->screen_name, 'tags' => $tagResult]);
    }

    public function logout()
    {
        if ($this->_isNotLoggedIn()) {
            return redirect('/');
        }

        if (! $this->isPost()) {
            return redirect('config');
        }

        unset($_SESSION['login']);

        return redirect('/');
    }
}
