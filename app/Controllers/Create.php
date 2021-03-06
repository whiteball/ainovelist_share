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
                'scripts'        => json_encode(empty($post_data['script']) ? [] : $post_data['script'], JSON_UNESCAPED_UNICODE),
                'character_book' => json_encode(empty($post_data['char_book']) ? [] : $post_data['char_book'], JSON_UNESCAPED_UNICODE),
            ]);

            foreach ($post_data['tags'] as $tag_name) {
                $tag->insert(['prompt_id' => $prompt_id, 'tag_name' => $tag_name]);
            }

            $prompt_access->new($prompt_id);
            $this->action_log->write($this->loginUserId, 'prompt create ' . $prompt_id . ' tag add [' . implode(' ', $post_data['tags']) . ']');

            $db->transComplete();

            if (! $db->transStatus()) {
                return view('create/index', [
                    'default_pane'  => '',
                    'validation'    => service('validation'),
                    'post_data'     => $post_data,
                    'error_message' => "??????????????????????????????????????????????????????\n??????????????????????????????????????????????????????????????????",
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
            'title'        => ['label' => '????????????', 'rules' => ['required', 'max_length[255]']],
            'tags'         => ['label' => '??????', 'rules' => ['required', static fn ($value) => ! empty(array_filter(explode(' ', preg_replace('/\s+/u', ' ', $value)), static fn ($val) => $val !== ''))]],
            'description'  => ['label' => '??????', 'rules' => ['required', 'max_length[2000]']],
            'prompt'       => ['label' => '???????????????', 'rules' => ['required', 'max_length[16777215]']],
            'memory'       => ['label' => '?????????', 'rules' => ['max_length[2000]']],
            'authors_note' => ['label' => '??????', 'rules' => ['max_length[2000]']],
            'ng_words'     => ['label' => 'NG?????????', 'rules' => ['max_length[2000]']],
            'r18'          => ['label' => 'R-18??????', 'rules' => ['permit_empty']],
            'draft'        => ['label' => '????????????', 'rules' => ['permit_empty']],
            'comment'      => ['label' => '??????????????????', 'rules' => ['permit_empty']],
            'script.*'     => ['label' => '???????????????', 'rules' => [function ($item) {
                if (empty($item)) {
                    return true;
                }

                if (! in_array($item['type'], ['script_in', 'script_out', 'script_in_pin', 'script_rephrase', 'script_in_regex', 'script_out_regex', 'script_in_pin_regex', 'script_rephrase_regex', 'script_none'], true)) {
                    $this->validator->setError('script[' . $item['id'] . '][type]', '???????????????????????????????????????????????????');
                }

                if (mb_strlen($item['in']) > 1000) {
                    $this->validator->setError('script[' . $item['id'] . '][in]', 'IN???1000??????????????????????????????????????????');
                }

                if (mb_strlen($item['out']) > 1000) {
                    $this->validator->setError('script[' . $item['id'] . '][out]', 'OUT???1000??????????????????????????????????????????');
                }

                return true;
            }]],
            'char_book.*' => ['label' => '???????????????????????????', 'rules' => [function ($item) {
                if (empty($item)) {
                    return true;
                }

                if (mb_strlen($item['tag']) > 1000) {
                    $this->validator->setError('char_book[' . $item['id'] . '][tag]', '?????????500??????????????????????????????????????????');
                }

                if (mb_strlen($item['content']) > 1000) {
                    $this->validator->setError('char_book[' . $item['id'] . '][content]', '?????????1000??????????????????????????????????????????');
                }

                return true;
            }]],
        ];

        $default_pane = '';
        if ($form_type === 'file') {
            $default_pane = 'file';

            if ($this->isPost() && $this->validate([
                'novel_file' => ['label' => '????????????', 'rules' => ['uploaded[novel_file]', 'max_size[novel_file,10240]']],
                'tags-file' => ['label' => '??????', 'rules' => ['required', static fn ($value) => ! empty(array_filter(explode(' ', preg_replace('/\s+/u', ' ', $value)), static fn ($val) => $val !== ''))]],
                'description-file' => ['label' => '??????', 'rules' => ['required', 'max_length[2000]']],
                'r18-file' => ['label' => 'R-18??????', 'rules' => ['permit_empty']],
                'draft-file' => ['label' => '????????????', 'rules' => ['permit_empty']],
                'comment-file' => ['label' => '??????????????????', 'rules' => ['permit_empty']],
            ])) {
                $post_data = [];

                $post_data['description'] = $this->request->getPost('description-file');
                $post_data['r18']         = $this->request->getPost('r18-file');
                $post_data['draft']       = $this->request->getPost('draft-file');
                $post_data['comment']     = $this->request->getPost('comment-file');

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
            $post_data = $this->request->getPost(['title', 'tags', 'description', 'prompt', 'memory', 'authors_note', 'ng_words', 'script', 'char_book', 'r18', 'draft', 'comment']);
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

        // ?????????????????????????????????input?????????
        $data['tags-file']        = $data['tags'];
        $data['description-file'] = $data['description'];
        $data['r18-file']         = $data['r18'];
        $data['draft-file']       = $data['draft'];
        $data['comment-file']     = $data['comment'];

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
                    'default_pane'  => '',
                    'prompt_id'     => $prompt_id,
                    'validation'    => service('validation'),
                    'post_data'     => $post_data,
                    'error_message' => "??????????????????????????????????????????????????????\n??????????????????????????????????????????????????????????????????",
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
            'title'        => ['label' => '????????????', 'rules' => ['required', 'max_length[255]']],
            'tags'         => ['label' => '??????', 'rules' => ['required', static fn ($value) => ! empty(array_filter(explode(' ', preg_replace('/\s+/u', ' ', $value)), static fn ($val) => $val !== ''))]],
            'description'  => ['label' => '??????', 'rules' => ['required', 'max_length[2000]']],
            'prompt'       => ['label' => '???????????????', 'rules' => ['required', 'max_length[16777215]']],
            'memory'       => ['label' => '?????????', 'rules' => ['max_length[2000]']],
            'authors_note' => ['label' => '??????', 'rules' => ['max_length[2000]']],
            'ng_words'     => ['label' => 'NG?????????', 'rules' => ['max_length[2000]']],
            'r18'          => ['label' => 'R-18??????', 'rules' => ['permit_empty']],
            'draft'        => ['label' => '????????????', 'rules' => ['permit_empty']],
            'comment'      => ['label' => '??????????????????', 'rules' => ['permit_empty']],
            'script.*'     => ['label' => '???????????????', 'rules' => [function ($item) {
                if (empty($item)) {
                    return true;
                }

                if (! in_array($item['type'], ['script_in', 'script_out', 'script_in_pin', 'script_rephrase', 'script_in_regex', 'script_out_regex', 'script_in_pin_regex', 'script_rephrase_regex', 'script_none'], true)) {
                    $this->validator->setError('script[' . $item['id'] . '][type]', '???????????????????????????????????????????????????');
                }

                if (mb_strlen($item['in']) > 1000) {
                    $this->validator->setError('script[' . $item['id'] . '][in]', 'IN???1000??????????????????????????????????????????');
                }

                if (mb_strlen($item['out']) > 1000) {
                    $this->validator->setError('script[' . $item['id'] . '][out]', 'OUT???1000??????????????????????????????????????????');
                }

                return true;
            }]],
            'char_book.*' => ['label' => '???????????????????????????', 'rules' => [function ($item) {
                if (empty($item)) {
                    return true;
                }

                if (mb_strlen($item['tag']) > 1000) {
                    $this->validator->setError('char_book[' . $item['id'] . '][tag]', '?????????500??????????????????????????????????????????');
                }

                if (mb_strlen($item['content']) > 1000) {
                    $this->validator->setError('char_book[' . $item['id'] . '][content]', '?????????1000??????????????????????????????????????????');
                }

                return true;
            }]],
        ];

        $default_pane = '';
        if ($form_type === 'file') {
            $default_pane = 'file';

            if ($this->isPost() && $this->validate([
                'novel_file' => ['label' => '????????????', 'rules' => ['uploaded[novel_file]', 'max_size[novel_file,10240]']],
                'tags-file' => ['label' => '??????', 'rules' => ['required', static fn ($value) => ! empty(array_filter(explode(' ', preg_replace('/\s+/u', ' ', $value)), static fn ($val) => $val !== ''))]],
                'description-file' => ['label' => '??????', 'rules' => ['required', 'max_length[2000]']],
                'r18-file' => ['label' => 'R-18??????', 'rules' => ['permit_empty']],
                'draft-file' => ['label' => '????????????', 'rules' => ['permit_empty']],
                'comment-file' => ['label' => '??????????????????', 'rules' => ['permit_empty']],
            ])) {
                $post_data = [];

                $post_data['description'] = $this->request->getPost('description-file');
                $post_data['r18']         = $this->request->getPost('r18-file');
                $post_data['draft']       = $this->request->getPost('draft-file');
                $post_data['comment']     = $this->request->getPost('comment-file');

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
            $post_data = $this->request->getPost(['title', 'tags', 'description', 'prompt', 'memory', 'authors_note', 'ng_words', 'script', 'char_book', 'r18', 'draft', 'comment']);
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
                unset($data['tags-file'], $data['description-file'], $data['r18-file'], $data['draft-file'], $data['comment-file']);

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
                ];
            }
        } elseif ($this->request->getGet('back') === '1') {
            if ($default_pane === 'file') {
                $data['tags-file']        = $_SESSION['prompt_edit_data']['tags'];
                $data['description-file'] = $_SESSION['prompt_edit_data']['description'];
                $data['r18-file']         = $_SESSION['prompt_edit_data']['r18'];
                $data['draft-file']       = $_SESSION['prompt_edit_data']['draft'];
                $data['comment-file']     = $_SESSION['prompt_edit_data']['comment'];

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
