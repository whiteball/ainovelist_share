<?php

namespace App\Controllers;

use App\Models\Prompt;
use App\Models\User;

class Home extends BaseController
{
    public function index()
    {
        return view('index');
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
