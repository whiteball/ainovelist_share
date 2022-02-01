<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterDataColumnForSession extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('ci_sessions', '`data` MEDIUMBLOB NOT NULL');
    }

    public function down()
    {
        $this->forge->modifyColumn('ci_sessions', '`data` BLOB NOT NULL');
    }
}
