<?php

namespace App\Controllers;

use App\Models\Prompt;
use App\Models\Trin_yami_intersection_token;
use App\Models\Trin_yami_union_token;
use App\Models\Trinsama_token;
use App\Models\Yamiotome_token;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Controller;
use Normalizer;

class Api extends Controller
{
    use ResponseTrait;

    public const MODE_TRINSAMA               = 0;
    public const MODE_YAMIOTOME              = 1;
    public const MODE_TRIN_YAMI_INTERSECTION = 2;
    public const MODE_TRIN_YAMI_UNION        = 3;

    public function get_tokens($mode, $string = '')
    {
        $payload = [];
        if (empty($string)) {
            $string = $this->request->getGet('q');
            if (empty($string)) {
                return $this->respond(['result' => $payload], 200);
            }
        }

        $match_type = $this->request->getGet('m');
        $side       = 'both';
        if ($match_type === 'b') {
            $side = 'after';
        } elseif ($match_type === 'e') {
            $side = 'before';
        }

        // Unicode正規化(NFKC)
        $normalizer = new Normalizer();
        $string     = $normalizer->normalize($string, Normalizer::FORM_KC);

        /** @var Trinsama_token|Yamiotome_token */
        $token_class_list = [
            self::MODE_TRINSAMA               => Trinsama_token::class,
            self::MODE_YAMIOTOME              => Yamiotome_token::class,
            self::MODE_TRIN_YAMI_INTERSECTION => Trin_yami_intersection_token::class,
            self::MODE_TRIN_YAMI_UNION        => Trin_yami_union_token::class,
        ];
        $tokens = model($token_class_list[(int) $mode] ?? $token_class_list[self::MODE_TRINSAMA]);

        // escapeLikeString()がダブルクオート等をエスケープするが、like()でもエスケープされるので二重エスケープになってしまう。
        // そのため、ここでエスケープを外す。like()の$escapeをfalseにすると、%...%で囲った後にダブルクオートで囲ってくれなくなる。
        $string = str_replace('\\\\', '\\', str_replace("\\'", "'", str_replace('\\"', '"', $tokens->db->escapeLikeString($string))));

        $result = $tokens->select('token')
            ->like('token', $string, $side)
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

        // Unicode正規化(NFKC)
        $normalizer = new Normalizer();
        $string     = $normalizer->normalize(preg_replace('/\r\n|\r|\n/', '\n', $string), Normalizer::FORM_KC);

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

    public function get_description($prompt_id)
    {
        /** @var Prompt */
        $prompt     = model(Prompt::class);
        $promptData = $prompt->where('draft', 0)->find($prompt_id);

        if (empty($promptData)) {
            return $this->respond(['result' => 'error'], 404);
        }

        $payload = ['type' => 'plain', 'description' => $promptData->description];

        return $this->respond(['result' => $payload], 200);
    }
}
