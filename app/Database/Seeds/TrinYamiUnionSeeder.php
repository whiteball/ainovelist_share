<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TrinYamiUnionSeeder extends Seeder
{
    public function run()
    {
        $fp = fopen(__DIR__ . DIRECTORY_SEPARATOR . 'TrinYamiUnionTokenList.txt', 'rb');
        if ($fp) {
            $this->db->transStart();

            while (($token = fgets($fp)) !== false) {
                $token = trim($token);
                $this->db->table('trin_yami_union_tokens')->insert([
                    'token' => $token,
                    'tail'  => mb_substr($token, -1, 1),
                ]);
            }

            $this->db->transComplete();

            fclose($fp);
        }
    }
}
