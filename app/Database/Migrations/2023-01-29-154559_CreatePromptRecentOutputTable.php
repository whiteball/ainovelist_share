<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePromptRecentOutputTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'prompt_id' => [
                'type'       => 'INT',
                'constraint' => 9,
                'unsigned'   => true,
            ],
            'outputted_at DATETIME NOT NULL',
        ]);
        $this->forge->addKey('prompt_id', true);
        $this->forge->addKey('outputted_at');
        $this->forge->createTable('prompts_recent_output');
    }

    public function down()
    {
        $this->forge->dropTable('prompts_recent_output');
    }
}
