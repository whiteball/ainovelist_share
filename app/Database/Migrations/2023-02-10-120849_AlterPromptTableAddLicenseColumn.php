<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterPromptTableAddLicenseColumn extends Migration
{
    public function up()
    {
        $this->forge->addColumn('prompts', 'license TINYINT DEFAULT 0 NOT NULL AFTER comment');
        $this->forge->addColumn('prompts_deleted', 'license TINYINT DEFAULT 0 NOT NULL AFTER comment');
    }

    public function down()
    {
        $this->forge->dropColumn('prompts', 'license');
        $this->forge->dropColumn('prompts_deleted', 'license');
    }
}
