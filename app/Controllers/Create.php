<?php

namespace App\Controllers;

use App\Models\Prompt;
use App\Models\Prompt_deleted;
use App\Models\Tag;

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
            /** @var Tag */
            $tag = model(Tag::class);

            $db = \Config\Database::connect();
            $db->transStart();
            $prompt_id = $prompt->insert([
                'user_id'        => $this->loginUserId,
                'title'          => $post_data['title'],
                'description'    => $post_data['description'],
                'prompt'         => $post_data['prompt'],
                'memory'         => $post_data['memory'],
                'authors_note'   => $post_data['authors_note'],
                'ng_words'       => $post_data['ng_words'],
                'r18'            => (! empty($post_data['r18']) && $post_data['r18'] === '1') ? 1 : 0,
                'scripts'        => json_encode(empty($post_data['script']) ? [] : $post_data['script'], JSON_UNESCAPED_UNICODE),
                'character_book' => json_encode(empty($post_data['char_book']) ? [] : $post_data['char_book'], JSON_UNESCAPED_UNICODE),
            ]);

            foreach ($post_data['tags'] as $tag_name) {
                $tag->insert(['prompt_id' => $prompt_id, 'tag_name' => $tag_name]);
            }

            $db->transComplete();

            if (! $db->transStatus()) {
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
            'tags' => ['label' => 'タグ', 'rules' => ['required', static fn ($value) => ! empty(array_filter(explode(' ', preg_replace('/\s+/u', ' ', $value)), static fn ($val) => $val !== ''))]],
            'description' => ['label' => '説明', 'rules' => ['required', 'max_length[2000]']],
            'prompt' => ['label' => 'プロンプト', 'rules' => ['required', 'max_length[16777215]']],
            'memory' => ['label' => 'メモリ', 'rules' => ['max_length[2000]']],
            'authors_note' => ['label' => '脚注', 'rules' => ['max_length[2000]']],
            'ng_words' => ['label' => 'NGワード', 'rules' => ['max_length[2000]']],
            'r18' => ['label' => 'R-18設定', 'rules' => ['permit_empty']],
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
            $post_data = $this->request->getPost(['title', 'tags', 'description', 'prompt', 'memory', 'authors_note', 'ng_words', 'r18', 'script', 'char_book']);
            if (isset($post_data['char_book'])) {
                $post_data['char_book'] = array_filter($post_data['char_book'], static fn ($char_book) => ! empty($char_book['tag']));
            }

            if (isset($post_data['script'])) {
                $post_data['script'] = array_filter($post_data['script'], static fn ($script) => ! empty($script['in']));
            }

            $post_data['tags'] = array_unique(array_map(static fn ($val) => mb_substr($val, 0, 128), explode(' ', preg_replace('/\s+/u', ' ', $post_data['tags']))));

            $_SESSION['prompt_data'] = $post_data;
            $this->session->markAsTempdata('prompt_data', 3600);

            return view('create/confirm', ['post_data' => $post_data]);
        }

        return view('create/index', ['validation' => service('validation')]);
    }

    public function edit($prompt_id)
    {
        if ($this->_isNotLoggedIn()) {
            return redirect('/');
        }

        /** @var Prompt */
        $prompt = model(Prompt::class);
        $data   = $prompt->asArray()->find($prompt_id);
        if (empty($data) || (int) $data['user_id'] !== (int) $this->loginUserId) {
            return redirect('config');
        }

        /** @var Tag */
        $tag       = model(Tag::class);
        $tags      = [];
        $tagResult = $tag->findByPrompt($prompt_id);
        if (! empty($tagResult)) {
            foreach ($tagResult as $row) {
                $tags[$row->id] = $row->tag_name;
            }
        }

        $data['tags'] = $tags;

        if ($this->request->getPost('send') === '1' && isset($_SESSION['prompt_edit_data'])) {
            $post_data = $_SESSION['prompt_edit_data'];

            $db = \Config\Database::connect();
            $db->transStart();
            $prompt->save([
                'id'             => $prompt_id,
                'user_id'        => $this->loginUserId,
                'title'          => $post_data['title'],
                'description'    => $post_data['description'],
                'prompt'         => $post_data['prompt'],
                'memory'         => $post_data['memory'],
                'authors_note'   => $post_data['authors_note'],
                'ng_words'       => $post_data['ng_words'],
                'r18'            => empty($post_data['r18']) ? 0 : 1,
                'scripts'        => json_encode(empty($post_data['script']) ? [] : $post_data['script'], JSON_UNESCAPED_UNICODE),
                'character_book' => json_encode(empty($post_data['char_book']) ? [] : $post_data['char_book'], JSON_UNESCAPED_UNICODE),
            ]);

            $diff = array_diff($tags, $post_data['tags']);

            foreach ($diff as $tag_id => $tag_name) {
                $tag->delete($tag_id);
            }

            $diff2 = array_diff($post_data['tags'], $tags);

            foreach ($diff2 as $tag_name) {
                $tag->insert(['prompt_id' => $prompt_id, 'tag_name' => $tag_name]);
            }

            $db->transComplete();

            if (! $db->transStatus()) {
                return view('create/edit', [
                    'prompt_id'     => $prompt_id,
                    'validation'    => service('validation'),
                    'post_data'     => $post_data,
                    'error_message' => "データ登録時にエラーが発生しました。\n申し訳ありませんが、再度登録をお願いします。",
                ]);
            }

            $this->session->unmarkTempdata('prompt_edit_data');
            unset($_SESSION['prompt_edit_data']);

            return view('create/complete_edit');
        }

        if ($this->isPost() && $this->validate([
            'title' => ['label' => 'タイトル', 'rules' => ['required', 'max_length[255]']],
            'description' => ['label' => '説明', 'rules' => ['required', 'max_length[2000]']],
            'prompt' => ['label' => 'プロンプト', 'rules' => ['required', 'max_length[16777215]']],
            'memory' => ['label' => 'メモリ', 'rules' => ['max_length[2000]']],
            'authors_note' => ['label' => '脚注', 'rules' => ['max_length[2000]']],
            'ng_words' => ['label' => 'NGワード', 'rules' => ['max_length[2000]']],
            'r18' => ['label' => 'R-18設定', 'rules' => ['permit_empty']],
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
            $post_data = $this->request->getPost(['title', 'tags', 'description', 'prompt', 'memory', 'authors_note', 'ng_words', 'r18', 'script', 'char_book']);
            if (isset($post_data['char_book'])) {
                $post_data['char_book'] = array_filter($post_data['char_book'], static fn ($char_book) => ! empty($char_book['tag']));
            }

            if (isset($post_data['script'])) {
                $post_data['script'] = array_filter($post_data['script'], static fn ($script) => ! empty($script['in']));
            }

            $post_data['tags'] = array_unique(array_map(static fn ($val) => mb_substr($val, 0, 128), explode(' ', preg_replace('/\s+/u', ' ', $post_data['tags']))));

            $_SESSION['prompt_edit_data'] = $post_data;
            $this->session->markAsTempdata('prompt_edit_data', 3600);

            return view('create/confirm', ['post_data' => $post_data]);
        }

        if ($this->isPost()) {
            $data = null;
        } else {
            $data['script']    = json_decode($data['scripts'], JSON_OBJECT_AS_ARRAY);
            $data['char_book'] = json_decode($data['character_book'], JSON_OBJECT_AS_ARRAY);
        }

        return view('create/edit', ['prompt_id' => $prompt_id, 'post_data' => $data, 'validation' => service('validation')]);
    }

    public function delete($prompt_id)
    {
        if ($this->_isNotLoggedIn()) {
            return redirect('/');
        }

        /** @var Prompt */
        $prompt = model(Prompt::class);
        $data   = $prompt->asArray()->find($prompt_id);
        if (empty($data) || (int) $data['user_id'] !== (int) $this->loginUserId) {
            return redirect('config');
        }

        /** @var Prompt_deleted */
        $prompt_deleted = model(Prompt_deleted::class);

        $db = \Config\Database::connect();
        $db->transStart();
        $prompt_deleted->save($data);
        $prompt->delete($prompt_id);
        $db->transComplete();

        if ($db->transStatus()) {
            return view('create/complete_delete');
        }

        return redirect('edit/' . $prompt_id);
    }
}
