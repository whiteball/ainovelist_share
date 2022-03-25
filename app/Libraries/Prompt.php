<?php

namespace App\Libraries;

use App\Models\Action_log;
use App\Models\Prompt as PromptModel;
use App\Models\Prompt_deleted;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\Database\Exceptions\DataException;
use InvalidArgumentException;
use ReflectionException;
use Throwable;

class Prompt
{
    /**
     * プロンプトを削除する。
     *
     * @param int|object $prompt_id プロンプトIDまたはプロンプトのレコード
     * @param int        $by        削除を行うユーザーID
     *
     * @throws DatabaseException
     * @throws DataException
     * @throws InvalidArgumentException
     * @throws ReflectionException
     *
     * @return bool
     */
    public function delete($prompt_id, $by)
    {
        /** @var PromptModel */
        $prompt = model(PromptModel::class);
        if (is_int($prompt_id)) {
            $data = $prompt->find($prompt_id);
        } else {
            $data = $prompt_id;
        }

        if (empty($data)) {
            return false;
        }

        /** @var Prompt_deleted */
        $prompt_deleted = model(Prompt_deleted::class);

        /** @var Tag */
        $tag = model(Tag::class);

        /** @var Action_log */
        $action_log = model(Action_log::class);

        $tag_ids   = [];
        $tag_names = [];

        foreach ($tag->where('prompt_id', $data->id)->findAll() as $row) {
            $tag_ids[]   = $row->id;
            $tag_names[] = $row->tag_name;
        }

        $db = \Config\Database::connect();
        $db->transStart();
        $prompt_deleted->save($data);
        $prompt->delete($data->id);
        if (! empty($tag_ids)) {
            $tag->delete($tag_ids);
        }

        $action_log->write($by, 'prompt delete ' . $data->id . ' tag delete [' . implode(' ', $tag_names) . ']');
        $db->transComplete();

        return $db->transStatus();
    }

    public function createImage($id)
    {
        /** @var PromptModel */
        $prompt = model(PromptModel::class);
        if (null === $id) {
            $list = $prompt->where('draft', 0)->findAll();

            foreach ($list as $item) {
                $this->createImage($item);
            }

            return true;
        }
        if (is_object($id)) {
            $prompt_data = $id;
            $id          = $prompt_data->id;
        } elseif ((int) $id > 0) {
            $prompt_data = $prompt->find($id);
            if (! $prompt_data) {
                return false;
            }
        } else {
            return false;
        }

        try {
            $title = strip_tags($prompt_data->title);
            $font  = realpath(APPPATH . '/ThirdParty/GenShinGothic-Medium.ttf');
            $base  = realpath(APPPATH . '/ThirdParty/ogp_base.png');
            $size  = 60;

            $im    = imagecreatefrompng($base);
            $width = imagesx($im);

            do {
                $size -= 4;
                $b_box = imagettfbbox($size, 0, $font, $title);
            } while ($width < ($b_box[4] - $b_box[0] + 20) && $size > 10);

            $black = imagecolorallocate($im, 0, 0, 0);
            $x     = (imagesx($im) / 2) - (($b_box[4] - $b_box[0]) / 2);
            $y     = (imagesy($im) / 2) - (($b_box[5] - $b_box[1]) / 2) + 150;
            imagettftext($im, $size, 0, $x, $y, $black, $font, $title);
            $fp = fopen(FCPATH . '/img/ogp_' . $id . '.png', 'wb');
            imagepng($im, $fp);
            fclose($fp);
        } catch (Throwable $th) {
            log_message('error', __METHOD__ . ': ' . $th->getMessage());

            return false;
        }

        return true;
    }

    public function deleteImage($id)
    {
        $path = realpath(FCPATH . '/img/ogp_' . $id . '.png');
        if ($path) {
            return unlink($path);
        }

        return false;
    }

    public function getImageUrl($id)
    {
        $path = realpath(FCPATH . '/img/ogp_' . $id . '.png');
        if ($path) {
            return base_url('img/ogp_' . $id . '.png');
        }

        return base_url('img/ogp.png');
    }
}
