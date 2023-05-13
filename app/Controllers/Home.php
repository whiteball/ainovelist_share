<?php

namespace App\Controllers;

use App\Libraries\Comment;
use App\Libraries\Prompt as PromptLib;
use App\Models\Prompt;
use App\Models\Prompt_access;
use App\Models\Prompt_ignored;
use App\Models\Prompt_recent_output;
use App\Models\Tag;
use App\Models\User;
use App\Models\User_password_reset;
use CodeIgniter\Config\Services;

class Home extends BaseController
{
    public const ITEM_PER_PAGE = 12;
    public const RECENT_ITEM   = 8;

    public function index()
    {
        $page = (int) ($this->request->getGet('p') ?? 1);

        /** @var Tag */
        $tag        = model(Tag::class);
        $prompt_ids = $tag->findPromptIdsByNgTags();

        /** @var Prompt */
        $prompt  = model(Prompt::class);
        $prompts = $prompt->findAllSafe(self::ITEM_PER_PAGE, self::ITEM_PER_PAGE * ($page - 1), $prompt_ids);
        $count   = $prompt->countAllResultsSafe(true, false, $prompt_ids);

        $tags = [];
        if (! empty($prompts)) {
            $tags = $tag->findByPrompt($prompts);
        }

        if ($page === 1) {
            $promptTable = $prompt->getTable();
            /** @var Prompt_recent_output */
            $promptRecent      = model(Prompt_recent_output::class);
            $promptRecentTable = $promptRecent->getTable();
            /** @var Prompt_ignored */
            $promptIgnored      = model(Prompt_ignored::class);
            $promptIgnoredTable = $promptIgnored->getTable();
            $prompt->join($promptRecentTable, $promptRecentTable . '.prompt_id = ' . $prompt->getTable() . '.id', 'inner')
                ->orderBy($promptRecentTable . '.outputted_at', 'desc')
                ->join($promptIgnoredTable, $promptIgnoredTable . '.prompt_id = ' . $prompt->getTable() . '.id', 'left')
                ->select($promptTable . '.*')
                ->where($promptIgnoredTable . '.prompt_id IS NULL');

            $recent_prompts = $prompt->findAllSafe(self::RECENT_ITEM, 0, $prompt_ids);
            shuffle($recent_prompts);
            helper('cookie');
            $cookie      = get_cookie('show_recent');
            $recent_show = ! (isset($cookie) && $cookie === '0');
            $recent_tags = [];
            if (! empty($recent_prompts)) {
                $recent_tags = $tag->findByPrompt($recent_prompts);
            }
        } else {
            $recent_prompts = [];
            $recent_tags    = [];
            $recent_show    = false;
        }

        return view('index', [
            'prompts'        => $prompts,
            'tags'           => $tags,
            'count'          => $count,
            'page'           => $page,
            'last_page'      => (int) ceil($count / self::ITEM_PER_PAGE),
            'recent_prompts' => $recent_prompts,
            'recent_tags'    => $recent_tags,
            'recent_show'    => $recent_show,
        ]);
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
            'login_name'       => ['label' => 'ログインID', 'rules' => ['required', 'alpha_numeric', 'max_length[255]', 'is_unique[users.login_name]']],
            'screen_name'      => ['label' => 'ユーザー名', 'rules' => ['required', 'max_length[100]']],
            'password'         => ['label' => 'パスワード', 'rules' => ['required', 'min_length[12]']],
            'password_confirm' => ['label' => 'パスワード(再入力)', 'rules' => ['required_with[password]', 'matches[password]']],
        ])) {
            /** @var User */
            $user = model(User::class);
            /** @var string */
            $password = $this->request->getPost('password');
            $user_id  = $user->insert([
                'login_name'  => $this->request->getPost('login_name'),
                'screen_name' => $this->request->getPost('screen_name'),
                'password'    => password_hash($password, PASSWORD_DEFAULT),
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
            'password'   => ['label' => 'パスワード', 'rules' => ['required']],
        ])) {
            /** @var User */
            $user      = model(User::class);
            $loginUser = $user->where('login_name', $this->request->getPost('login_name'))->first();
            /** @var string */
            $password = $this->request->getPost('password');
            if (empty($loginUser) || ! password_verify($password, $loginUser->password)) {
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

        $promptLib = new PromptLib();

        $promptData->{'char_book'} = json_decode($promptData->character_book, JSON_OBJECT_AS_ARRAY);
        $promptData->{'script'}    = json_decode($promptData->scripts, JSON_OBJECT_AS_ARRAY);

        if ($asFile) {
            $main      = str_replace(' ', '&nbsp;', preg_replace('/[\r\n]/u', '', nl2br($promptData->prompt, false)));
            $param     = $promptData->parameters ?? $promptLib->serializeParameters([]);
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

            $is_count_up = false;
            if (trim($this->request->header('origin')) === 'Origin: https://ai-novel.com') {
                $is_count_up = $prompt_access->countUp($prompt_id, Prompt_access::COUNT_TYPE_IMPORT);
            } else {
                $is_count_up = $prompt_access->countUp($prompt_id, Prompt_access::COUNT_TYPE_DOWNLOAD);
            }

            // ダウンロード/インポートをカウントアップしたときだけ反映する
            if ($is_count_up) {
                /** @var Prompt_recent_output */
                $promptRecent = model(Prompt_recent_output::class);
                $promptRecent->updateDateTime($prompt_id);
            }

            $novel = preg_replace('/\r\n|\r/u', "\n", "{$main}<|endofsection|>{$promptData->memory}<|endofsection|>{$promptData->authors_note}<|endofsection|>{$param}<|endofsection|>{$char_book}<|endofsection|>{$promptData->ng_words}<|endofsection|>{$promptData->title}<|endofsection|><|endofsection|>{$scripts}<|endofsection|>{$promptData->chat_template}");

            return $this->response->download(strip_tags($promptData->title) . '.novel', $novel);
        }

        if ($prompt_access->countUp($prompt_id, Prompt_access::COUNT_TYPE_VIEW)) {
            $promptData->view++;
        }

        $viewData  = [
            'prompt'      => $promptData,
            'author'      => $userData->screen_name,
            'tags'        => $tagResult,
            'ogp'         => $promptLib->getImageUrl($prompt_id),
            'loginUserId' => $this->loginUserId,
            'validation'  => service('validation'),
        ];

        // 詳細パラメータ
        if (! empty($promptData->parameters)) {
            $parameters = $promptLib->deserializeParameters($promptData->parameters);
            // デフォルト値と同じかチェック
            if ($promptLib->serializeParameters($parameters) !== $promptLib->serializeParameters([])) {
                $viewData['parameters'] = $parameters;
            }
        }

        // コメント処理
        if ($promptData->comment !== '0') {
            $comment = new Comment();
            if ($this->request->getPost('type') === 'comment') {
                if ($this->validate([
                    'reply-to' => ['label' => 'リプライ先', 'rules' => ['required', 'numeric']],
                    'comment'  => ['label' => 'コメント', 'rules' => ['required', 'max_length[2048]']],
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

        /** @var Tag */
        $tag        = model(Tag::class);
        $prompt_ids = $tag->findPromptIdsByNgTags();

        /** @var Prompt */
        $prompt = model(Prompt::class);
        $result = $prompt->captionSearch($query, self::ITEM_PER_PAGE, self::ITEM_PER_PAGE * ($page - 1), 'and', $prompt_ids);

        $count   = $result['count'];
        $prompts = $result['result'];
        $tags    = [];
        if (empty($prompts)) {
            $count   = 0;
            $prompts = [];
        } else {
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

    public function hall_of_fame()
    {
        $page = (int) ($this->request->getGet('p') ?? 1);

        /** @var Tag */
        $tag        = model(Tag::class);
        $prompt_ids = $tag->findPromptIdsByNgTags();

        /** @var Prompt */
        $prompt = model(Prompt::class);

        /** @var Prompt_ignored */
        $promptIgnored      = model(Prompt_ignored::class);
        $promptIgnoredTable = $promptIgnored->getTable();
        $prompt->join($promptIgnoredTable, $promptIgnoredTable . '.prompt_id = ' . $prompt->getTable() . '.id', 'inner');

        $prompts = $prompt->findAllSafe(self::ITEM_PER_PAGE, self::ITEM_PER_PAGE * ($page - 1), $prompt_ids);
        $prompt->join($promptIgnoredTable, $promptIgnoredTable . '.prompt_id = ' . $prompt->getTable() . '.id', 'inner');
        $count = $prompt->countAllResultsSafe(true, false, $prompt_ids);

        $tags = [];
        if (! empty($prompts)) {
            $tags = $tag->findByPrompt($prompts);
        }

        return view('hall_of_fame', [
            'prompts'   => $prompts,
            'tags'      => $tags,
            'count'     => $count,
            'page'      => $page,
            'last_page' => (int) ceil($count / self::ITEM_PER_PAGE),
            'page_base_url' => 'hall_of_fame',
        ]);
    }

    public function chat()
    {
        $page = (int) ($this->request->getGet('p') ?? 1);

        /** @var Tag */
        $tag        = model(Tag::class);
        $prompt_ids = $tag->findPromptIdsByNgTags();

        /** @var Prompt */
        $prompt = model(Prompt::class);
        $result = $prompt->getChatPrompts(self::ITEM_PER_PAGE, self::ITEM_PER_PAGE * ($page - 1), $prompt_ids);

        $count   = $result['count'];
        $prompts = $result['result'];
        $tags    = [];
        if (empty($prompts)) {
            $count   = 0;
            $prompts = [];
        } else {
            $tags = $tag->findByPrompt($prompts);
        }

        return view('chat', [
            'prompts'   => $prompts,
            'tags'      => $tags,
            'count'     => $count,
            'page'      => $page,
            'last_page' => (int) ceil($count / self::ITEM_PER_PAGE),
            'page_base_url' => 'search/chat',
        ]);
    }

    public function password_reset()
    {
        if ($this->isPost() && $this->validate([
            'login_name' => ['label' => 'ログインID', 'rules' => ['required', 'alpha_numeric', 'max_length[255]']],
            'mail_address' => ['label' => 'メールアドレス', 'rules' => ['required', 'max_length[255]', 'valid_email']],
        ])) {
            /** @var mixed */
            $loginName = $this->request->getPost('login_name');
            /** @var mixed */
            $mailAddr = $this->request->getPost('mail_address');

            /** @var User */
            $user = model(User::class);
            /** @var User_password_reset */
            $userPassReset = model(User_password_reset::class);
            $reset = $user->where('login_name', $loginName)
                ->join($userPassReset->getTable(), 'user_id = id', 'LEFT')
                ->findAll();

            if (password_verify($mailAddr, $reset[0]->digest ?? '')) {
                helper('text');
                $code = random_string('alnum', 12);
                $_SESSION['password_reset_number'] = password_hash($code, PASSWORD_ARGON2ID);
                $_SESSION['password_reset_user_id'] = $reset[0]->user_id;
                $_SESSION['password_reset_count'] = 3;
                $this->session->markAsTempdata('password_reset_number', 30 * 60);
                $this->session->markAsTempdata('password_reset_user_id', 30 * 60);
                $this->session->markAsTempdata('password_reset_count', 30 * 60);

                $url = base_url();
                $emailConfig = config('Email');
                $email = Services::email($emailConfig);
                $email->setTo($mailAddr);
                $email->setSubject('[AIのべりすと プロンプト共有] パスワードリセット確認');
                $email->setMessage(<<<"EOT"
                {$reset[0]->screen_name} 様
    
                AIのべりすと プロンプト共有です。
                パスワードリセットの確認です。
                パスワードをリセットする場合は、下記の12桁の英数字コードをサイト上で入力してください。
                コードは30分間有効です。

                {$code}

                もしこのメールに心当たりがない場合は、無視してください。
                ログインパスワードは十分な長さのもの使うことをおすすめします。

                ----
                AIのべりすと プロンプト共有
                {$url}
                EOT);
                $email->send();
            } else {
                // IDとメールの組が間違っているのでダミーデータを設定する
                $_SESSION['password_reset_number'] = password_hash("!!!!invalid!!!!\x01", PASSWORD_ARGON2ID);
                $_SESSION['password_reset_user_id'] = $loginName;
                $_SESSION['password_reset_count'] = 3;
                $this->session->markAsTempdata('password_reset_number', 30 * 60);
                $this->session->markAsTempdata('password_reset_user_id', 30 * 60);
                $this->session->markAsTempdata('password_reset_count', 30 * 60);
            }

            // ログインID/パスワードの組が間違っていても検証ページに飛ばす
            return redirect('password_reset_verify');
        }

        return view('password_reset/index', ['validation' => service('validation')]);
    }

    public function password_reset_verify()
    {
        if (! isset($_SESSION['password_reset_number']) || ! isset($_SESSION['password_reset_user_id']) || ! isset($_SESSION['password_reset_count'])) {
            return redirect('password_reset');
        }

        $errorMessage = '';
        if ($this->isPost() && $this->validate([
            'code'                 => ['label' => 'コード', 'rules' => ['required', 'alpha_numeric', 'max_length[64]']],
            'new_password'         => ['label' => '新しいパスワード', 'rules' => ['required', 'min_length[12]']],
            'new_password_confirm' => ['label' => '新しいパスワード(再入力)', 'rules' => ['required_with[new_password]', 'matches[new_password]']],
        ])) {
            $_SESSION['password_reset_count']--;

            /** @var mixed */
            $code = $this->request->getPost('code');
            /** @var mixed */
            $new_password = $this->request->getPost('new_password');
            /** @var User */
            $user = model(User::class);

            $userData = $user->find($_SESSION['password_reset_user_id'] ?? -1);
            if (password_verify($code, $_SESSION['password_reset_number'] ?? '') && $userData) {
                $userData->password = password_hash($new_password, PASSWORD_DEFAULT);
                $user->save($userData);
                $this->action_log->write($userData->id, 'user change password by reset form');
                
                $this->session->unmarkTempdata('password_reset_number');
                $this->session->unmarkTempdata('password_reset_user_id');
                $this->session->unmarkTempdata('password_reset_count');
                unset($_SESSION['password_reset_number']);
                unset($_SESSION['password_reset_user_id']);
                unset($_SESSION['password_reset_count']);
                return view('password_reset/complete');
            }

            $errorMessage = 'コードが一致しません。';
        }

        if ($_SESSION['password_reset_count'] <= 0) {
            // 規定回数以上間違えたので最初から
            $this->session->unmarkTempdata('password_reset_number');
            $this->session->unmarkTempdata('password_reset_user_id');
            $this->session->unmarkTempdata('password_reset_count');
            unset($_SESSION['password_reset_number']);
            unset($_SESSION['password_reset_user_id']);
            unset($_SESSION['password_reset_count']);
            return redirect('password_reset');
        }

        return view('password_reset/verify', ['validation' => service('validation'), 'error_message' => $errorMessage]);
    }
}
