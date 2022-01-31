<?php

namespace App\Controllers;

use App\Models\Prompt;

class Create extends BaseController
{
    public function index(string $form_type = '')
    {
        if ($this->_isNotLoggedIn()) {
            return redirect('/');
        }

        if ($this->request->getPost('send') === '1' && isset($_SESSION['prompt_data'])) {
            $post_data = $_SESSION['prompt_data'];
            /** @var Prompt */
            $prompt = model(Prompt::class);
            if (! $prompt->save([
                'user_id' => $this->loginUserId,
                'title' => $post_data['title'],
                'description' => $post_data['description'],
                'prompt' => $post_data['prompt'],
                'memory' => $post_data['memory'],
                'authors_note' => $post_data['authors_note'],
                'ng_words' => $post_data['ng_words'],
                'scripts' => json_encode(empty($post_data['script']) ? [] : $post_data['script'], JSON_UNESCAPED_UNICODE),
                'character_book' => json_encode(empty($post_data['char_book']) ? [] : $post_data['char_book'], JSON_UNESCAPED_UNICODE),
            ])) {
                return view('create/index', [
                    'validation'    => service('validation'),
                    'post_data'     => $post_data,
                    'error_message' => "データ登録時にエラーが発生しました。\n申し訳ありませんが、再度登録をお願いします。",
                ]);
            }

            $this->session->unmarkTempdata('prompt_data');
            unset($_SESSION['prompt_data']);

            return view('create/complete');
        }

        if ($this->isPost() && $this->validate([
            'title' => ['label' => 'タイトル', 'rules' => ['required', 'max_length[255]']],
            'description' => ['label' => '説明', 'rules' => ['required', 'max_length[2000]']],
            'prompt' => ['label' => 'プロンプト', 'rules' => ['required', 'max_length[16777215]']],
            'memory' => ['label' => 'メモリ', 'rules' => ['max_length[2000]']],
            'authors_note' => ['label' => '脚注', 'rules' => ['max_length[2000]']],
            'ng_words' => ['label' => 'NGワード', 'rules' => ['max_length[2000]']],
            'script.*' => ['label' => 'スクリプト', 'rules' => [function ($item) {
                if (empty($item)) {
                    return true;
                }

                if (! in_array($item['type'], ['script_in', 'script_out', 'script_in_pin', 'script_in_regexp', 'script_out_regexp', 'script_in_pin_regexp', 'script_none'], true)) {
                    $this->validator->setError('script[' . $item['id'] . '][type]', '種類の指定が不正です。');
                }

                if (mb_strlen($item['in']) > 1000) {
                    $this->validator->setError('script[' . $item['id'] . '][in]', 'INは1000文字までしか入力できません。');
                }

                if (mb_strlen($item['out']) > 1000) {
                    $this->validator->setError('script[' . $item['id'] . '][out]', 'OUTは1000文字までしか入力できません。');
                }

                return true;
            }]],
            'char_book.*' => ['label' => 'キャラクターブック', 'rules' => [function ($item) {
                if (empty($item)) {
                    return true;
                }

                if (mb_strlen($item['tag']) > 1000) {
                    $this->validator->setError('char_book[' . $item['id'] . '][tag]', 'タグは500文字までしか入力できません。');
                }

                if (mb_strlen($item['content']) > 1000) {
                    $this->validator->setError('char_book[' . $item['id'] . '][content]', '説明は1000文字までしか入力できません。');
                }

                return true;
            }]],
        ])) {
            $post_data = $this->request->getPost(['title', 'description', 'prompt', 'memory', 'authors_note', 'ng_words', 'script', 'char_book']);
            if (isset($post_data['char_book'])) {
                $post_data['char_book'] = array_filter($post_data['char_book'], static fn ($char_book) => ! empty($char_book['tag']));
            }

            if (isset($post_data['script'])) {
                $post_data['script'] = array_filter($post_data['script'], static fn ($script) => ! empty($script['in']));
            }

            $_SESSION['prompt_data'] = $post_data;
            $this->session->markAsTempdata('prompt_data', 3600);

            return view('create/confirm', ['post_data' => $post_data]);
        }

        return view('create/index', ['validation' => service('validation')]);
    }

    public function direct()
    {
        // code...
    }
}
