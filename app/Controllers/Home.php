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
        $prompts = $prompt->orderBy('updated_at', 'desc')->findAllSafe(self::ITEM_PER_PAGE, self::ITEM_PER_PAGE * ($page - 1));
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
        if ($this->_isNotLoggedIn()) {
            return redirect('/');
        }

        /** @var User */
        $user      = model(User::class);
        $loginUser = $user->find($_SESSION['login']);

        $success_message = '';
        $success_message2 = '';
        $error_message2 = '';
        if ($this->isPost()) {
            if ($this->request->getPost('type') === 'change_name' && $this->validate([
                'screen_name' => ['label' => 'ユーザー名', 'rules' => ['required', 'max_length[100]']],
            ])) {
                $old_name = $loginUser->screen_name;
                $loginUser->screen_name = $this->request->getPost('screen_name');
                $user->save($loginUser);
                $this->action_log->write($loginUser->id, 'user change screen_name ' . $old_name . ' to ' . $loginUser->screen_name);
                $success_message = 'ユーザー名変更しました';
            }
            
            if ($this->request->getPost('type') === 'change_password' && $this->validate([
                'current_password' => ['label' => '現在のパスワード', 'rules' => ['required']],
                'new_password' => ['label' => '新しいパスワード', 'rules' => ['required', 'min_length[12]']],
                'new_password_confirm' => ['label' => '新しいパスワード(再入力)', 'rules' => ['required_with[new_password]', 'matches[new_password]']],
            ])) {
                if (password_verify($this->request->getPost('current_password') , $loginUser->password)) {
                    $loginUser->password = password_hash($this->request->getPost('new_password'), PASSWORD_DEFAULT);
                    $user->save($loginUser);
                    $this->action_log->write($loginUser->id, 'user change password');
                    $success_message2 = 'パスワード変更しました';
                } else {
                    $error_message2 = '現在のパスワードが違います';
                }
            }
        }

        /** @var Prompt */
        $prompt  = model(Prompt::class);
        $prompts = $prompt->where('user_id', $this->loginUserId)->findAll();

        return view('config', [
            'validation'       => service('validation'),
            'screen_name'      => $loginUser->screen_name,
            'success_message'  => $success_message,
            'success_message2' => $success_message2,
            'error_message2'   => $error_message2,
            'prompts'          => $prompts,
        ]);
    }

    public function prompt($prompt_id, $asFile = false)
    {
        /** @var Prompt */
        $prompt     = model(Prompt::class);
        $promptData = $prompt->where('draft', 0)->find($prompt_id);

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

            $novel = preg_replace('/\r\n|\r/u', "\n", "{$main}<|endofsection|>{$promptData->memory}<|endofsection|>{$promptData->authors_note}<|endofsection|>{$param}<|endofsection|>{$char_book}<|endofsection|>{$promptData->ng_words}<|endofsection|>{$promptData->title}<|endofsection|><|endofsection|>{$scripts}");

            return $this->response->download(strip_tags($promptData->title) . '.novel', $novel);
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
}
