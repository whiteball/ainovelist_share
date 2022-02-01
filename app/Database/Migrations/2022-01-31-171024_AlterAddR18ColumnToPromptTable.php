<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterAddR18ColumnToPromptTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('prompts', 'r18 TINYINT DEFAULT 0 NOT NULL AFTER character_book');
        $this->forge->addColumn('prompts_deleted', 'r18 TINYINT DEFAULT 0 NOT NULL AFTER character_book');
    }

    public function down()
    {
        $this->forge->dropColumn('prompts', 'r18');
        $this->forge->dropColumn('prompts_deleted', 'r18');
    }
}
