<?php

namespace App\Controllers;

use App\Libraries\Prompt as PromptLib;
use App\Models\Prompt;
use App\Models\Prompt_access;
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
            /** @var Prompt_access */
            $prompt_access = model(Prompt_access::class);

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
                'draft'          => (! empty($post_data['draft']) && $post_data['draft'] === '1') ? 1 : 0,
                'comment'        => (! empty($post_data['comment']) && $post_data['comment'] === '1') ? 1 : 0,
                'license'        => $post_data['license'],
                'scripts'        => json_encode(empty($post_data['script']) ? [] : $post_data['script'], JSON_UNESCAPED_UNICODE),
                'character_book' => json_encode(empty($post_data['char_book']) ? [] : $post_data['char_book'], JSON_UNESCAPED_UNICODE),
            ]);

            foreach ($post_data['tags'] as $tag_name) {
                $tag->insert(['prompt_id' => $prompt_id, 'tag_name' => $tag_name]);
            }

            $prompt_access->new($prompt_id);
            $this->action_log->write($this->loginUserId, 'prompt create ' . $prompt_id . ' tag add [' . implode(' ', $post_data['tags']) . '] license ' . $post_data['license']);

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

            $draft = (! empty($post_data['draft']) && $post_data['draft'] === '1');

            if (! $draft) {
                $promptLib = new PromptLib();
                $promptLib->createImage($prompt_id);
            }

            return view('create/complete', ['prompt_id' => $prompt_id, 'draft' => $draft]);
        }

        $validation_rule = [
            'title'        => ['label' => 'タイトル', 'rules' => ['required', 'max_length[255]']],
            'tags'         => ['label' => 'タグ', 'rules' => ['required', static fn ($value) => ! empty(array_filter(explode(' ', preg_replace('/\s+/u', ' ', $value)), static fn ($val) => $val !== ''))]],
            'description'  => ['label' => '説明', 'rules' => ['required', 'min_length[20]', 'max_length[2000]']],
            'prompt'       => ['label' => 'プロンプト', 'rules' => ['required', 'max_length[16777215]']],
            'memory'       => ['label' => 'メモリ', 'rules' => ['max_length[2000]']],
            'authors_note' => ['label' => '脚注', 'rules' => ['max_length[2000]']],
            'ng_words'     => ['label' => 'NGワード', 'rules' => ['max_length[2000]']],
            'r18'          => ['label' => 'R-18設定', 'rules' => ['permit_empty']],
            'draft'        => ['label' => '公開設定', 'rules' => ['permit_empty']],
            'comment'      => ['label' => 'コメント設定', 'rules' => ['permit_empty']],
            'license'      => ['label' => 'プロンプトの改変可否', 'rules' => ['required', 'in_list[0,1,2]']],
            'script.*.type' => ['label' => 'スクリプト', 'rules' => ['permit_empty', 'in_list[script_in,script_out,script_in_pin,script_in_pin_all,script_rephrase,script_in_regex,script_out_regex,script_in_pin_regex,script_in_pin_all_regex,script_rephrase_regex,script_none]']],
            'script.*.in' => ['label' => 'スクリプト', 'rules' => ['max_length[1000]']],
            'script.*.out' => ['label' => 'スクリプト', 'rules' => ['max_length[1000]']],
            'char_book.*.tag' => ['label' => 'スクリプト', 'rules' => ['max_length[500]']],
            'char_book.*.content' => ['label' => 'スクリプト', 'rules' => ['max_length[1000]']],
        ];

        $default_pane = '';
        if ($form_type === 'file') {
            $default_pane = 'file';

            if ($this->isPost() && $this->validate([
                'novel_file' => ['label' => 'ファイル', 'rules' => ['uploaded[novel_file]', 'max_size[novel_file,10240]']],
                'tags-file' => ['label' => 'タグ', 'rules' => ['required', static fn ($value) => ! empty(array_filter(explode(' ', preg_replace('/\s+/u', ' ', $value)), static fn ($val) => $val !== ''))]],
                'description-file' => ['label' => '説明', 'rules' => ['required', 'max_length[2000]']],
                'r18-file' => ['label' => 'R-18設定', 'rules' => ['permit_empty']],
                'draft-file' => ['label' => '公開設定', 'rules' => ['permit_empty']],
                'comment-file' => ['label' => 'コメント設定', 'rules' => ['permit_empty']],
                'license-file' => ['label' => 'プロンプトの改変可否', 'rules' => ['required', 'in_list[0,1,2]']],
            ])) {
                $post_data = [];

                $post_data['description'] = $this->request->getPost('description-file');
                $post_data['r18']         = $this->request->getPost('r18-file');
                $post_data['draft']       = $this->request->getPost('draft-file');
                $post_data['comment']     = $this->request->getPost('comment-file');
                $post_data['license']     = $this->request->getPost('license-file');

                $file         = $this->request->getFile('novel_file');
                $novel_format = file_get_contents($file->getTempName());
                $novel_items  = explode('<|endofsection|>', $novel_format);

                $post_data['prompt']       = str_replace('&nbsp;', ' ', strip_tags(preg_replace('/\<br\/?\>/u', "\n", $novel_items[0])));
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

                        $script_item           = explode('<|sp|>', $line);
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
                    $post_data['tags'] = array_filter(array_unique(array_map(static fn ($val) => mb_substr($val, 0, 128), explode(' ', preg_replace('/\s+/u', ' ', $this->request->getPost('tags-file'))))), static fn ($value) => $value !== '');

                    $_SESSION['prompt_data'] = $post_data;
                    $this->session->markAsTempdata('prompt_data', 3600);

                    return view('create/confirm', ['post_data' => $post_data, 'return_url' => 'create/file']);
                }

                $file_verify_error = true;
            }
        } elseif ($this->isPost() && $this->validate($validation_rule)) {
            $post_data = $this->request->getPost(['title', 'tags', 'description', 'prompt', 'memory', 'authors_note', 'ng_words', 'script', 'char_book', 'r18', 'draft', 'comment', 'license']);
            if (isset($post_data['char_book'])) {
                $post_data['char_book'] = array_filter($post_data['char_book'], static fn ($char_book) => ! empty($char_book['tag']));
            }

            if (isset($post_data['script'])) {
                $post_data['script'] = array_filter($post_data['script'], static fn ($script) => ! empty($script['in']));
            }

            $post_data['tags'] = array_filter(array_unique(array_map(static fn ($val) => mb_substr($val, 0, 128), explode(' ', preg_replace('/\s+/u', ' ', $post_data['tags'])))), static fn ($value) => $value !== '');

            $_SESSION['prompt_data'] = $post_data;
            $this->session->markAsTempdata('prompt_data', 3600);

            return view('create/confirm', ['post_data' => $post_data, 'return_url' => 'create']);
        }

        $data = null;
        if ($this->request->getGet('back') === '1') {
            if ($default_pane === 'file') {
                $data = [
                    'tags-file'        => $_SESSION['prompt_data']['tags'],
                    'description-file' => $_SESSION['prompt_data']['description'],
                    'r18-file'         => $_SESSION['prompt_data']['r18'],
                    'draft-file'       => $_SESSION['prompt_data']['draft'],
                    'comment-file'     => $_SESSION['prompt_data']['comment'],
                    'license-file'     => $_SESSION['prompt_data']['license'],
                ];
            } else {
                $data = $_SESSION['prompt_data'];
            }
        }

        return view('create/index', [
            'default_pane'      => $default_pane,
            'validation'        => service('validation'),
            'file_verify_error' => $file_verify_error ?? false,
            'post_data'         => $data,
        ]);
    }

    public function edit($prompt_id, string $form_type = '')
    {
        if ($this->_isNotLoggedIn()) {
            return redirect('/');
        }

        /** @var Prompt */
        $prompt = model(Prompt::class);
        $data   = $prompt->asArray()->find($prompt_id);
        if (empty($data) || (int) $data['user_id'] !== (int) $this->loginUserId) {
            return redirect('mypage');
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

        // ファイルインポート用のinput初期値
        $data['tags-file']        = $data['tags'];
        $data['description-file'] = $data['description'];
        $data['r18-file']         = $data['r18'];
        $data['draft-file']       = $data['draft'];
        $data['comment-file']     = $data['comment'];
        $data['license-file']     = $data['license'];
        $data['updated_at_for_sort-file'] = '0';

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
                'draft'          => empty($post_data['draft']) ? 0 : 1,
                'comment'        => empty($post_data['comment']) ? 0 : 1,
                'license'        => $post_data['license'],
                'scripts'        => json_encode(empty($post_data['script']) ? [] : $post_data['script'], JSON_UNESCAPED_UNICODE),
                'character_book' => json_encode(empty($post_data['char_book']) ? [] : $post_data['char_book'], JSON_UNESCAPED_UNICODE),
                // 更新順ソートに使うカラムを更新するかどうか
                'updated_at_for_sort' => ! empty($post_data['updated_at_for_sort']),
            ]);

            $diff = array_diff($tags, $post_data['tags']);

            foreach ($diff as $tag_id => $tag_name) {
                $tag->delete($tag_id);
            }

            $diff2 = array_diff($post_data['tags'], $tags);

            foreach ($diff2 as $tag_name) {
                $tag->insert(['prompt_id' => $prompt_id, 'tag_name' => $tag_name]);
            }

            $this->action_log->write($this->loginUserId, 'prompt edit ' . $prompt_id . ' tag delete [' . implode(' ', $diff) . '] tag add [' . implode(' ', $diff2) . '] license ' . $post_data['license']);

            $db->transComplete();

            if (! $db->transStatus()) {
                return view('create/edit', [
                    'default_pane'  => '',
                    'prompt_id'     => $prompt_id,
                    'validation'    => service('validation'),
                    'post_data'     => $post_data,
                    'error_message' => "データ登録時にエラーが発生しました。\n申し訳ありませんが、再度登録をお願いします。",
                ]);
            }

            $this->session->unmarkTempdata('prompt_edit_data');
            unset($_SESSION['prompt_edit_data']);

            $draft = (! empty($post_data['draft']));

            $promptLib = new PromptLib();
            if ($draft) {
                $promptLib->deleteImage($prompt_id);
            } else {
                $promptLib->createImage($prompt_id);
            }

            return view('create/complete_edit', ['prompt_id' => $prompt_id, 'draft' => $draft]);
        }

        $validation_rule = [
            'title'        => ['label' => 'タイトル', 'rules' => ['required', 'max_length[255]']],
            'tags'         => ['label' => 'タグ', 'rules' => ['required', static fn ($value) => ! empty(array_filter(explode(' ', preg_replace('/\s+/u', ' ', $value)), static fn ($val) => $val !== ''))]],
            'description'  => ['label' => '説明', 'rules' => ['required', 'min_length[20]', 'max_length[2000]']],
            'prompt'       => ['label' => 'プロンプト', 'rules' => ['required', 'max_length[16777215]']],
            'memory'       => ['label' => 'メモリ', 'rules' => ['max_length[2000]']],
            'authors_note' => ['label' => '脚注', 'rules' => ['max_length[2000]']],
            'ng_words'     => ['label' => 'NGワード', 'rules' => ['max_length[2000]']],
            'r18'          => ['label' => 'R-18設定', 'rules' => ['permit_empty']],
            'draft'        => ['label' => '公開設定', 'rules' => ['permit_empty']],
            'comment'      => ['label' => 'コメント設定', 'rules' => ['permit_empty']],
            'license'      => ['label' => 'プロンプトの改変可否', 'rules' => ['required', 'in_list[0,1,2]']],
            'updated_at_for_sort' => ['label' => '更新日順ソート設定', 'rules' => ['permit_empty']],
            'script.*.type' => ['label' => 'スクリプト', 'rules' => ['permit_empty', 'in_list[script_in,script_out,script_in_pin,script_in_pin_all,script_rephrase,script_in_regex,script_out_regex,script_in_pin_regex,script_in_pin_all_regex,script_rephrase_regex,script_none]']],
            'script.*.in' => ['label' => 'スクリプト', 'rules' => ['max_length[1000]']],
            'script.*.out' => ['label' => 'スクリプト', 'rules' => ['max_length[1000]']],
            'char_book.*.tag' => ['label' => 'キャラクターブック', 'rules' => ['max_length[500]']],
            'char_book.*.content' => ['label' => 'キャラクターブック', 'rules' => ['max_length[1000]']],
        ];

        $default_pane = '';
        if ($form_type === 'file') {
            $default_pane = 'file';

            if ($this->isPost() && $this->validate([
                'novel_file' => ['label' => 'ファイル', 'rules' => ['uploaded[novel_file]', 'max_size[novel_file,10240]']],
                'tags-file' => ['label' => 'タグ', 'rules' => ['required', static fn ($value) => ! empty(array_filter(explode(' ', preg_replace('/\s+/u', ' ', $value)), static fn ($val) => $val !== ''))]],
                'description-file' => ['label' => '説明', 'rules' => ['required', 'max_length[2000]']],
                'r18-file' => ['label' => 'R-18設定', 'rules' => ['permit_empty']],
                'draft-file' => ['label' => '公開設定', 'rules' => ['permit_empty']],
                'comment-file' => ['label' => 'コメント設定', 'rules' => ['permit_empty']],
                'license-file' => ['label' => 'プロンプトの改変可否', 'rules' => ['required', 'in_list[0,1,2]']],
                'updated_at_for_sort-file' => ['label' => '更新日順ソート設定', 'rules' => ['permit_empty']],
            ])) {
                $post_data = [];

                $post_data['description'] = $this->request->getPost('description-file');
                $post_data['r18']         = $this->request->getPost('r18-file');
                $post_data['draft']       = $this->request->getPost('draft-file');
                $post_data['comment']     = $this->request->getPost('comment-file');
                $post_data['license']     = $this->request->getPost('license-file');
                $post_data['updated_at_for_sort'] = $this->request->getPost('updated_at_for_sort-file');

                $file         = $this->request->getFile('novel_file');
                $novel_format = file_get_contents($file->getTempName());
                $novel_items  = explode('<|endofsection|>', $novel_format);

                $post_data['prompt']       = str_replace('&nbsp;', ' ', strip_tags(preg_replace('/\<br\/?\>/u', "\n", $novel_items[0])));
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

                        $script_item           = explode('<|sp|>', $line);
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
                    $post_data['tags'] = array_filter(array_unique(array_map(static fn ($val) => mb_substr($val, 0, 128), explode(' ', preg_replace('/\s+/u', ' ', $this->request->getPost('tags-file'))))), static fn ($value) => $value !== '');

                    $_SESSION['prompt_edit_data'] = $post_data;
                    $this->session->markAsTempdata('prompt_data', 3600);

                    return view('create/confirm', ['post_data' => $post_data, 'return_url' => 'edit/' . $prompt_id . '/file']);
                }

                $file_verify_error = true;
            }
        } elseif ($this->isPost() && $this->validate($validation_rule)) {
            $post_data = $this->request->getPost(['title', 'tags', 'description', 'prompt', 'memory', 'authors_note', 'ng_words', 'script', 'char_book', 'r18', 'draft', 'comment', 'license', 'updated_at_for_sort']);
            if (isset($post_data['char_book'])) {
                $post_data['char_book'] = array_filter($post_data['char_book'], static fn ($char_book) => ! empty($char_book['tag']));
            }

            if (isset($post_data['script'])) {
                $post_data['script'] = array_filter($post_data['script'], static fn ($script) => ! empty($script['in']));
            }

            $post_data['tags'] = array_filter(array_unique(array_map(static fn ($val) => mb_substr($val, 0, 128), explode(' ', preg_replace('/\s+/u', ' ', $post_data['tags'])))), static fn ($value) => $value !== '');

            $_SESSION['prompt_edit_data'] = $post_data;
            $this->session->markAsTempdata('prompt_edit_data', 3600);

            return view('create/confirm', ['post_data' => $post_data, 'return_url' => 'edit/' . $prompt_id]);
        }

        if ($this->isPost()) {
            if ($default_pane === 'file') {
                unset($data['tags-file'], $data['description-file'], $data['r18-file'], $data['draft-file'], $data['comment-file'], $data['license-file'], $data['updated_at_for_sort-file']);

                $data['script']    = json_decode($data['scripts'], JSON_OBJECT_AS_ARRAY);
                $data['char_book'] = json_decode($data['character_book'], JSON_OBJECT_AS_ARRAY);
            } else {
                $data_temp = $data;
                $data      = [
                    'tags-file'        => $data_temp['tags-file'],
                    'description-file' => $data_temp['description-file'],
                    'r18-file'         => $data_temp['r18-file'],
                    'draft-file'       => $data_temp['draft-file'],
                    'comment-file'     => $data_temp['comment-file'],
                    'license-file'     => $data_temp['license-file'],
                    'updated_at_for_sort-file' => $data_temp['updated_at_for_sort-file'],
                ];
            }
        } elseif ($this->request->getGet('back') === '1') {
            if ($default_pane === 'file') {
                $data['tags-file']        = $_SESSION['prompt_edit_data']['tags'];
                $data['description-file'] = $_SESSION['prompt_edit_data']['description'];
                $data['r18-file']         = $_SESSION['prompt_edit_data']['r18'];
                $data['draft-file']       = $_SESSION['prompt_edit_data']['draft'];
                $data['comment-file']     = $_SESSION['prompt_edit_data']['comment'];
                $data['license-file']     = $_SESSION['prompt_edit_data']['license'];
                $data['updated_at_for_sort-file'] = $_SESSION['prompt_edit_data']['updated_at_for_sort'];

                $data['script']    = json_decode($data['scripts'], JSON_OBJECT_AS_ARRAY);
                $data['char_book'] = json_decode($data['character_book'], JSON_OBJECT_AS_ARRAY);
            } else {
                $data_temp = $data;
                $data      = $_SESSION['prompt_edit_data'];

                $data['tags-file']        = $data_temp['tags-file'];
                $data['description-file'] = $data_temp['description-file'];
                $data['r18-file']         = $data_temp['r18-file'];
                $data['draft-file']       = $data_temp['draft-file'];
                $data['comment-file']     = $data_temp['comment-file'];
                $data['license-file']     = $data_temp['license-file'];
                $data['updated_at_for_sort-file'] = $data_temp['updated_at_for_sort-file'];
            }
        } else {
            $data['script']    = json_decode($data['scripts'], JSON_OBJECT_AS_ARRAY);
            $data['char_book'] = json_decode($data['character_book'], JSON_OBJECT_AS_ARRAY);
        }

        return view('create/edit', [
            'prompt_id'         => $prompt_id,
            'default_pane'      => $default_pane,
            'validation'        => service('validation'),
            'file_verify_error' => $file_verify_error ?? false,
            'post_data'         => $data,
        ]);
    }

    public function delete($prompt_id)
    {
        if ($this->_isNotLoggedIn()) {
            return redirect('/');
        }

        /** @var Prompt */
        $prompt = model(Prompt::class);
        $data   = $prompt->find($prompt_id);
        if (empty($data) || (int) $data->user_id !== (int) $this->loginUserId) {
            return redirect('mypage');
        }

        $prompt_lib = new PromptLib();
        if ($prompt_lib->delete($data, $this->loginUserId)) {
            return view('create/complete_delete');
        }

        return redirect('edit/' . $prompt_id);
    }
}
