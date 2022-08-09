<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterRankingTableModifyRank extends Migration
{
    public function up()
    {
        $this->db->query('ALTER TABLE `ranking` MODIFY rank INT UNSIGNED NOT NULL');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE `ranking` MODIFY rank TINYINT UNSIGNED NOT NULL');
    }
}
