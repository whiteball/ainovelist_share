<?php

namespace App\Controllers;

use App\Models\User;

class Home extends BaseController
{
    public function index()
    {
        return view('index');
    }

    public function register()
    {
        helper('form');
        if ($this->isPost() && $this->validate([
            'login_name' => ['label' => 'ログインID', 'rules' => ['required', 'alpha_numeric', 'max_length[255]', 'is_unique[users.login_name]']],
            'screen_name' => ['label' => 'ユーザー名', 'rules' => ['required', 'max_length[100]']],
            'password' => ['label' => 'パスワード', 'rules' => ['required', 'min_length[12]']],
            'password_confirm' => ['label' => 'パスワード(再入力)', 'rules' => ['required_with[password]', 'matches[password]']],
        ])) {
            /** @var User */
            $user = model(User::class);
            $user->save([
                'login_name' => $this->request->getPost('login_name'),
                'screen_name' => $this->request->getPost('screen_name'),
                'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            ]);
            return view('register/complete');
        }

        return view('register/index', ['validation' => service('validation')]);
    }
}
