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
     * @return bool
     *
     * @throws DatabaseException
     * @throws DataException
     * @throws InvalidArgumentException
     * @throws ReflectionException
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

        if ($db->transStatus()) {
            $this->deleteImage($data->id);

            return true;
        }

        return false;
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
            $x     = (imagesx($im) / 2) - (($b_box[4] - $b_box[0]) / 2) - $b_box[0];
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

    /**
     * 詳細パラメータを配列から単一文字列に変換する。
     * 不足したパラメータはデフォルト値を使う。
     *
     * @param array $parameters パラメータの配列
     *
     * @return string
     */
    public function serializeParameters($parameters)
    {
        return implode('<>', [
            $parameters['temperature'] ?? '31',
            $parameters['top_p'] ?? '29',
            $parameters['freq_p'] ?? '93',
            $parameters['length'] ?? '60',
            $parameters['contextwindow'] ?? '256',
            $parameters['anplacement'] ?? '3',
            $parameters['wiscanrange'] ?? '128',
            'NaN',
            '0',
            $parameters['long_term_memory'] ?? '0',
            $parameters['tfs'] ?? '40',
            $parameters['freq_p_range'] ?? '128',
            $parameters['freq_p_slope'] ?? '37',
            $parameters['wiplacement'] ?? '30',
            $parameters['dialogue_density'] ?? '20',
            $parameters['br_density'] ?? '20',
            $parameters['comma_density'] ?? '20',
            $parameters['typical_p'] ?? '100',
            $parameters['parenthesis_density'] ?? '20',
            $parameters['periods_density'] ?? '20',
            (! empty($parameters['gui_mode']) && $parameters['gui_mode'] === '1') ? 'chat' : 'novel',
            (! empty($parameters['chat_auto_enter']) && $parameters['chat_auto_enter'] === '1') ? 'true' : 'false',
            (! empty($parameters['chat_auto_brackets']) && $parameters['chat_auto_brackets'] === '1') ? 'true' : 'false',
            $parameters['chat_enter_key'] ?? '1',
            // デフォルトが有効なので、UIとの兼ね合いで値は反転して持っている
            ((! empty($parameters['chat_change_enter_key']) && $parameters['chat_change_enter_key'] === '1')) ? 'false' : 'true',
        ]);
    }

    /**
     * 詳細パラメータの文字列を配列に変換する。
     * 不足したパラメータはデフォルト値を使う。
     *
     * @param string $parameter_str <>で区切られたパラメータの文字列
     *
     * @return array
     */
    public function deserializeParameters($parameter_str)
    {
        $temp = explode('<>', $parameter_str);

        return [
            'temperature'           => empty($temp[0]) ? '31' : $temp[0],
            'top_p'                 => empty($temp[1]) ? '29' : $temp[1],
            'freq_p'                => empty($temp[2]) ? '93' : $temp[2],
            'length'                => empty($temp[3]) ? '60' : $temp[3],
            'contextwindow'         => empty($temp[4]) ? '256' : $temp[4],
            'anplacement'           => empty($temp[5]) ? '3' : $temp[5],
            'wiscanrange'           => empty($temp[6]) ? '128' : $temp[6],
            'long_term_memory'      => empty($temp[9]) ? '0' : $temp[9],
            'tfs'                   => empty($temp[10]) ? '40' : $temp[10],
            'freq_p_range'          => empty($temp[11]) ? '128' : $temp[11],
            'freq_p_slope'          => empty($temp[12]) ? '37' : $temp[12],
            'wiplacement'           => empty($temp[13]) ? '30' : $temp[13],
            'dialogue_density'      => empty($temp[14]) ? '20' : $temp[14],
            'br_density'            => empty($temp[15]) ? '20' : $temp[15],
            'comma_density'         => empty($temp[16]) ? '20' : $temp[16],
            'typical_p'             => empty($temp[17]) ? '100' : $temp[17],
            'parenthesis_density'   => empty($temp[18]) ? '20' : $temp[18],
            'periods_density'       => empty($temp[19]) ? '20' : $temp[19],
            'gui_mode'              => empty($temp[20]) ? '0' : ($temp[20] === 'chat' ? '1' : '0'),
            'chat_auto_enter'       => empty($temp[21]) ? '0' : ($temp[21] === 'true' ? '1' : '0'),
            'chat_auto_brackets'    => empty($temp[22]) ? '0' : ($temp[22] === 'true' ? '1' : '0'),
            'chat_enter_key'        => ! isset($temp[23]) ? '1' : $temp[23],
            'chat_change_enter_key' => empty($temp[24]) ? '0' : ($temp[24] === 'true' ? '0' : '1'),
        ];
    }
}
