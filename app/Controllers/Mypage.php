<?php

namespace App\Controllers;

use App\Libraries\Comment;
use App\Libraries\Prompt as PromptLib;
use App\Models\Prompt;
use App\Models\User;
use App\Models\User_deleted;
use App\Models\User_password_reset;
use CodeIgniter\Config\Services;

class Mypage extends BaseController
{
    public function index()
    {
        if ($this->_isNotLoggedIn()) {
            return redirect('/');
        }

        /** @var User */
        $user      = model(User::class);
        $loginUser = $user->find($this->loginUserId);
        if (! $loginUser) {
            $this->_logout();

            return redirect('/');
        }

        /** @var User_password_reset */
        $userPassReset = model(User_password_reset::class);
        $reset         = $userPassReset->selectCount('user_id', 'count')->find($this->loginUserId);

        $success_message  = '';
        $success_message2 = '';
        $error_message2   = '';
        $view_data        = [];
        if ($this->isPost()) {
            if ($this->request->getPost('type') === 'change_name' && $this->validate([
                'screen_name' => ['label' => 'ユーザー名', 'rules' => ['required', 'max_length[100]']],
            ])) {
                $old_name               = $loginUser->screen_name;
                $loginUser->screen_name = $this->request->getPost('screen_name');
                $user->save($loginUser);
                $this->action_log->write($loginUser->id, 'user change screen_name ' . $old_name . ' to ' . $loginUser->screen_name);
                $success_message = 'ユーザー名変更しました';
            }

            if ($this->request->getPost('type') === 'change_password' && $this->validate([
                'current_password'     => ['label' => '現在のパスワード', 'rules' => ['required']],
                'new_password'         => ['label' => '新しいパスワード', 'rules' => ['required', 'min_length[12]']],
                'new_password_confirm' => ['label' => '新しいパスワード(再入力)', 'rules' => ['required_with[new_password]', 'matches[new_password]']],
            ])) {
                /**
                 * @var mixed $password
                 */
                $password = $this->request->getPost('current_password');
                /**
                 * @var mixed $new_password
                 */
                $new_password = $this->request->getPost('new_password');
                if (password_verify($password, $loginUser->password)) {
                    $loginUser->password = password_hash($new_password, PASSWORD_DEFAULT);
                    $user->save($loginUser);
                    $this->action_log->write($loginUser->id, 'user change password');
                    $success_message2 = 'パスワード変更しました';
                } else {
                    $error_message2 = '現在のパスワードが違います';
                }
            }

            if ($this->request->getPost('type') === 'password_reset' && $this->validate([
                'mail_address' => ['label' => '送信先メールアドレス', 'rules' => ['required', 'max_length[255]', 'valid_email', 'regex_match[/@(?!gmail.com).+$/]']],
            ], ['mail_address' => ['regex_match' => '申し訳ありませんが、Gmailは登録できません。']])) {
                /** @var mixed */
                $mailAddr = $this->request->getPost('mail_address');
                $userPassReset->save([
                    'user_id' => $this->loginUserId,
                    'digest'  => password_hash($mailAddr, PASSWORD_ARGON2ID),
                ]);
                $this->action_log->write($this->loginUserId, 'user set password reset');

                $url         = base_url();
                $emailConfig = config('Email');
                $email       = Services::email($emailConfig);
                $email->setTo($mailAddr);
                $email->setSubject('[AIのべりすと プロンプト共有] パスワードリセットを設定しました');
                $email->setMessage(<<<"EOT"
                    {$loginUser->screen_name} 様

                    AIのべりすと プロンプト共有です。
                    ログインID {$loginUser->login_name} にパスワードリセット用のメールアドレスを設定しました。
                    ログインアカウントを忘れた場合は、サイト上のパスワードリセットフォームに
                    ログインIDとこのメールを受け取ったメールアドレスを入力してください。

                    ----
                    AIのべりすと プロンプト共有
                    {$url}
                    EOT);
                $email->send();

                $reset                     = $userPassReset->selectCount('user_id', 'count')->find($this->loginUserId);
                $view_data['mail_address'] = $mailAddr;
                $view_data['from_address'] = $emailConfig->fromEmail;
            }
        }

        return view('mypage/config', array_merge([
            'validation'       => service('validation'),
            'screen_name'      => $loginUser->screen_name,
            'success_message'  => $success_message,
            'success_message2' => $success_message2,
            'error_message2'   => $error_message2,
            'has_reset'        => ($reset->count ?? 0) > 0,
        ], $view_data));
    }

    public function list()
    {
        if ($this->_isNotLoggedIn()) {
            return redirect('/');
        }

        /** @var Prompt */
        $prompt  = model(Prompt::class);
        $prompts = $prompt->where('user_id', $this->loginUserId)->findAll();

        return view('mypage/list', [
            'prompts' => $prompts,
        ]);
    }

    public function comment_posted()
    {
        if ($this->_isNotLoggedIn()) {
            return redirect('/');
        }

        $comment = new Comment();

        return view('mypage/comment_posted', [
            'comments' => $comment->get_posted($this->loginUserId),
        ]);
    }

    public function comment_received()
    {
        if ($this->_isNotLoggedIn()) {
            return redirect('/');
        }

        $comment = new Comment();

        return view('mypage/comment_received', [
            'comments' => $comment->get_received($this->loginUserId),
        ]);
    }

    public function delete()
    {
        if ($this->_isNotLoggedIn()) {
            return redirect('/');
        }

        if ($this->isPost()) {
            /** @var User */
            $user = model(User::class);
            /** @var User_deleted */
            $user_deleted = model(User_deleted::class);
            /** @var Prompt */
            $prompt     = model(Prompt::class);
            $prompt_lib = new PromptLib();

            $db = \Config\Database::connect();
            $db->transStart();

            $prompt_list = $prompt->where('user_id', $this->loginUserId)->findAll();

            foreach ($prompt_list as $prompt_data) {
                $prompt_lib->delete($prompt_data, $this->loginUserId);
            }

            $userData = $user->find($this->loginUserId);
            $user_deleted->save($userData);
            $user->delete($this->loginUserId);

            $this->action_log->write($this->loginUserId, 'user delete');

            $db->transComplete();

            if ($db->transStatus()) {
                $this->action_log->write($this->loginUserId, 'user logout');
                unset($_SESSION['login']);

                return view('mypage/complete_delete');
            }
        }

        return view('mypage/delete');
    }
}
