<?php

namespace App\Controllers;

use App\Models\Trinsama_token;
use App\Models\Yamiotome_token;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Controller;

class Api extends Controller
{
    use ResponseTrait;

    public function get_tokens($mode, $string = '')
    {
        $payload = [];
        if (empty($string)) {
            $string = $this->request->getGet('q');
            if (empty($string)) {
                return $this->respond(['result' => $payload], 200);
            }
        }

        /** @var Trinsama_token|Yamiotome_token */
        $tokens = (int) $mode === 1 ? model(Yamiotome_token::class) : model(Trinsama_token::class);
        // escapeLikeString()がダブルクオート等をエスケープするが、like()でもエスケープされるので二重エスケープになってしまう。
        // そのため、ここでエスケープを外す。like()の$escapeをfalseにすると、%...%で囲った後にダブルクオートで囲ってくれなくなる。
        $string = str_replace('\\\\', '\\', str_replace("\\'", "'", str_replace('\\"', '"', $tokens->db->escapeLikeString(mb_convert_kana($string, 'asKV')))));

        $result = $tokens->select('token')
            ->like('token', $string)
            ->orderBy('id')
            ->get();

        if ($result) {
            foreach ($result->getResult() as $row) {
                $payload[] = $row->token;
            }
        }

        return $this->respond(['result' => $payload], 200);
    }

    public function count_tokens($mode)
    {
        $payload = [];
        $string  = $this->request->getGet('q');
        if (empty($string)) {
            return $this->respond(['result' => $payload], 200);
        }

        /** @var Trinsama_token|Yamiotome_token */
        $tokens = (int) $mode === 1 ? model(Yamiotome_token::class) : model(Trinsama_token::class);

        $counter = 0;

        $string = mb_convert_kana(preg_replace('/\r\n|\r|\n/', '\n', $string), 'asKV');

        while (mb_strlen($string) > 0 && $counter < 500) {
            $str    = mb_substr($string, 0, 1);
            $result = $tokens->select('token')
                ->like('token', $str, 'after')
                ->orderBy('id')
                ->get();
            $hit     = '';
            $hit_len = 0;
            if ($result) {
                foreach ($result->getResult() as $row) {
                    $row_len = mb_strlen($row->token);
                    if ($hit_len < $row_len && mb_substr($string, 0, $row_len) === $row->token) {
                        $hit     = $row->token;
                        $hit_len = $row_len;
                    }
                }
            }

            if ($hit_len > 0) {
                $payload[] = $hit;
                $string    = mb_substr($string, $hit_len);
            } else {
                $payload[] = '<unk>';
                $string    = mb_substr($string, 1);
            }
            $counter++;
        }

        return $this->respond(['result' => $payload], 200);
    }
}
