<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterRankingTable extends Migration
{
    public function up()
    {
        $this->forge->dropKey('ranking', 'date_rank_type');
        $this->forge->addColumn('ranking', 'r18 TINYINT unsigned NOT NULL after type');
        $this->db->query('ALTER TABLE `ranking` ADD UNIQUE `date_rank_type_r18` (`date`,`rank`,`type`,`r18`)');
    }

    public function down()
    {
        $this->forge->dropColumn('ranking', 'r18');
        $this->forge->dropKey('ranking', 'date_rank_type_r18');
        $this->db->query('ALTER TABLE `ranking` ADD UNIQUE `date_rank_type` (`date`,`rank`,`type`)');
    }
}
