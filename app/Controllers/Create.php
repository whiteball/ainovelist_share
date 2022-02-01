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

            $this->action_log->write($this->loginUserId, 'prompt create ' . $prompt_id . ' tag add [' . implode(' ', $post_data['tags']) . ']');

            $db->transComplete();

            if (! $db->transStatus()) {
                return view('create/index', [
                    'default_pane'  => '',
                    'validation'    => service('validation'),
                    'post_data'     => $post_data,
                    'error_message' => "データ登録時にエラーが発生しました。\n申し訳ありませんが、再度登録をお願いします。",
                ]);
            }

            $this->session->unmarkTempdata('prompt_data');
            unset($_SESSION['prompt_data']);

            return view('create/complete');
        }

        $validation_rule = [
            'title'        => ['label' => 'タイトル', 'rules' => ['required', 'max_length[255]']],
            'tags'         => ['label' => 'タグ', 'rules' => ['required', static fn ($value) => ! empty(array_filter(explode(' ', preg_replace('/\s+/u', ' ', $value)), static fn ($val) => $val !== ''))]],
            'description'  => ['label' => '説明', 'rules' => ['required', 'max_length[2000]']],
            'prompt'       => ['label' => 'プロンプト', 'rules' => ['required', 'max_length[16777215]']],
            'memory'       => ['label' => 'メモリ', 'rules' => ['max_length[2000]']],
            'authors_note' => ['label' => '脚注', 'rules' => ['max_length[2000]']],
            'ng_words'     => ['label' => 'NGワード', 'rules' => ['max_length[2000]']],
            'r18'          => ['label' => 'R-18設定', 'rules' => ['permit_empty']],
            'script.*'     => ['label' => 'スクリプト', 'rules' => [function ($item) {
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
        ];

        $default_pane = '';
        if ($form_type === 'file') {
            $default_pane = 'file';

            if ($this->isPost() && $this->validate([
                'novel_file' => ['label' => 'ファイル', 'rules' => ['uploaded[novel_file]', 'max_size[novel_file,10240]']],
                'tags-file' => ['label' => 'タグ', 'rules' => ['required', static fn ($value) => ! empty(array_filter(explode(' ', preg_replace('/\s+/u', ' ', $value)), static fn ($val) => $val !== ''))]],
                'description-file' => ['label' => '説明', 'rules' => ['required', 'max_length[2000]']],
                'r18-file' => ['label' => 'R-18設定', 'rules' => ['permit_empty']],
            ])) {
                $post_data = [];

                $post_data['description'] = $this->request->getPost('description-file');
                $post_data['r18']         = $this->request->getPost('r18-file');

                $file         = $this->request->getFile('novel_file');
                $novel_format = file_get_contents($file->getTempName());
                $novel_items  = explode('<|endofsection|>', $novel_format);

                $post_data['prompt']       = str_replace('&nbsp;', ' ', preg_replace('/<\/?(font|span|script)[^>]*>/u', '', preg_replace('/\<br\/?\>/u', "\n", $novel_items[0])));
                $post_data['memory']       = $novel_items[1];
                $post_data['authors_note'] = $novel_items[2];

                if (! empty($novel_items[4])) {
                    $post_data['char_book'] = [];
                    $is_key                 = true;
                    $pair                   = [];
                    $counter                = 0;

                    foreach (explode('<|entry|>', $novel_items[4]) as $key) {
                        if ($is_key) {
                            $pair['id']  = $counter;
                            $pair['tag'] = $key;
                        } else {
                            $pair['content']          = $key;
                            $post_data['char_book'][] = $pair;
                            $pair                     = [];
                            $counter++;
                        }

                        $is_key = ! $is_key;
                    }
                }

                $post_data['ng_words'] = $novel_items[5];
                $post_data['title']    = $novel_items[6];

                if (! empty($novel_items[8])) {
                    $post_data['script'] = [];
                    $counter             = 0;

                    foreach (explode('<|entry|>', $novel_items[8]) as $line) {
                        if (empty($line)) {
                            continue;
                        }

                        $script_item = explode('<|sp|>', $line);
                        $post_data['script'][] = [
                            'id'   => $counter,
                            'type' => $script_item[0] ?? '',
                            'in'   => $script_item[1] ?? '',
                            'out'  => $script_item[2] ?? '',
                        ];

                        $counter++;
                    }
                }

                unset($validation_rule['tags']);
                if ($this->validator->reset()->setRules($validation_rule)->run($post_data)) {
                    $post_data['tags'] = array_unique(array_map(static fn ($val) => mb_substr($val, 0, 128), explode(' ', preg_replace('/\s+/u', ' ', $this->request->getPost('tags-file')))));

                    $_SESSION['prompt_data'] = $post_data;
                    $this->session->markAsTempdata('prompt_data', 3600);

                    return view('create/confirm', ['post_data' => $post_data]);
                }

                $file_verify_error = true;
            }
        } elseif ($this->isPost() && $this->validate($validation_rule)) {
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

        return view('create/index', ['default_pane' => $default_pane, 'validation' => service('validation'), 'file_verify_error' => $file_verify_error ?? false]);
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

            $this->action_log->write($this->loginUserId, 'prompt edit ' . $prompt_id . ' tag delete [' . implode(' ', $diff) . '] tag add [' . implode(' ', $diff2) . ']');

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

        /** @var Tag */
        $tag = model(Tag::class);

        $tag_ids = [];
        $tag_names = [];
        foreach ($tag->where('prompt_id', $prompt_id)->findAll() as $row) {
            $tag_ids[] = $row->id;
            $tag_names[] = $row->tag_name;
        }

        $db = \Config\Database::connect();
        $db->transStart();
        $prompt_deleted->save($data);
        $prompt->delete($prompt_id);
        if (! empty($tag_ids)) {
            $tag->delete($tag_ids);
        }

        $this->action_log->write($this->loginUserId, 'prompt delete ' . $prompt_id . ' tag delete [' . implode(' ', $tag_names) . ']');
        $db->transComplete();

        if ($db->transStatus()) {
            return view('create/complete_delete');
        }

        return redirect('edit/' . $prompt_id);
    }
}
