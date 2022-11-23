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
}
