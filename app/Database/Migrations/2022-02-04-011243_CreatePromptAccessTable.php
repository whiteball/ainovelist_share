<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePromptAccessTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'prompt_id' => [
                'type'           => 'INT',
                'constraint'     => 9,
                'unsigned'       => true,
            ],
            'view' => [
                'type'       => 'INT',
                'constraint' => 9,
                'unsigned'   => true,
                'default'    => 0,
            ],
            'download' => [
                'type'       => 'INT',
                'constraint' => 9,
                'unsigned'   => true,
                'default'    => 0,
            ],
            'import' => [
                'type'       => 'INT',
                'constraint' => 9,
                'unsigned'   => true,
                'default'    => 0,
            ],
        ]);
        $this->forge->addKey('prompt_id', true);
        $this->forge->createTable('prompts_access');

        $this->db->query('INSERT INTO `prompts_access` (`prompt_id`) SELECT `id` as `prompt_id` FROM `prompts`;');
    }

    public function down()
    {
        $this->forge->dropTable('prompts_access');
    }
}
