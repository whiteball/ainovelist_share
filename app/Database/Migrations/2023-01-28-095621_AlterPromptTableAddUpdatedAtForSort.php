<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterPromptTableAddUpdatedAtForSort extends Migration
{
    public function up()
    {
        $this->forge->addColumn('prompts', 'updated_at_for_sort DATETIME NULL after updated_at');
        $this->forge->addColumn('prompts_deleted', 'updated_at_for_sort DATETIME NULL after updated_at');
    }

    public function down()
    {
        $this->forge->dropColumn('prompts', 'updated_at_for_sort');
        $this->forge->dropColumn('prompts_deleted', 'updated_at_for_sort');
    }
}
