<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterRankingTableModifyRankSigned extends Migration
{
    public function up()
    {
        $this->db->query('ALTER TABLE `ranking` MODIFY rank INT NOT NULL');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE `ranking` MODIFY rank INT UNSIGNED NOT NULL');
    }
}
