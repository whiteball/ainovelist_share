<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TrinYamiIntersectionSeeder extends Seeder
{
    public function run()
    {
        $fp = fopen(__DIR__ . DIRECTORY_SEPARATOR . 'TrinYamiIntersectionTokenList.txt', 'rb');
        if ($fp) {
            $this->db->transStart();

            while (($token = fgets($fp)) !== false) {
                $token = trim($token);
                $this->db->table('trin_yami_intersection_tokens')->insert([
                    'token' => $token,
                    'tail'  => mb_substr($token, -1, 1),
                ]);
            }

            $this->db->transComplete();

            fclose($fp);
        }
    }
}
