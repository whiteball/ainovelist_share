<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Controller;

use App\Models\Trinsama_token;
use App\Models\Yamiotome_token;

class Api extends Controller
{
    use ResponseTrait;

    public function get_tokens($mode, $string)
    {
        $payload = [];
        if (empty($string)) {
            return $this->respond(['result' => $payload], 200);
        }

        /** @var Trinsama_token|Yamiotome_token */
        $tokens = (int) $mode === 1 ? model(Yamiotome_token::class) : model(Trinsama_token::class);

        $result = $tokens->select('token')
            ->like('token', '%' . $tokens->db->escapeLikeString($string) . '%')
            ->orderBy('id')
            ->get();

        if ($result) {
            foreach ($result->getResult() as $row) {
                $payload[] = $row->token;
            }
        }

        return $this->respond(['result' => $payload], 200);
    }
}
