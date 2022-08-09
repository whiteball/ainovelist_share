<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePromptIgnoredTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'prompt_id' => [
                'type'           => 'INT',
                'constraint'     => 9,
                'unsigned'       => true,
            ],
        ]);
        $this->forge->addKey('prompt_id', true);
        $this->forge->createTable('prompts_ignored');
    }

    public function down()
    {
        $this->forge->dropTable('prompts_ignored');
    }
}
