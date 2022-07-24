<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TrinsamaSeeder extends Seeder
{
    public function run()
    {
        $fp = fopen(__DIR__ . DIRECTORY_SEPARATOR . 'TrinsamaTokenList.txt', 'rb');
        if ($fp) {
            $this->db->transStart();

            while (($token = fgets($fp)) !== false) {
                $token = trim($token);
                $this->db->table('trinsama_tokens')->insert([
                    'token' => $token,
                    'tail'  => mb_substr($token, -1, 1),
                ]);
            }

            $this->db->transComplete();

            fclose($fp);
        }
    }
}
