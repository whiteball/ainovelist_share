<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterDraftColumnForPromptTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('prompts', 'draft TINYINT DEFAULT 0 NOT NULL AFTER r18');
        $this->forge->addColumn('prompts_deleted', 'draft TINYINT DEFAULT 0 NOT NULL AFTER r18');
    }

    public function down()
    {
        $this->forge->dropColumn('prompts', 'draft');
        $this->forge->dropColumn('prompts_deleted', 'draft');
    }
}
