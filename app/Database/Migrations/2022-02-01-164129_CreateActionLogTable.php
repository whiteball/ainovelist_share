<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateActionLogTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 9,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 9,
                'unsigned'   => true,
            ],
            'message' => [
                'type'       => 'TEXT',
            ],
            'registered_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('prompt_id');
        $this->forge->addKey('tag_name');
        $this->forge->createTable('action_logs');
    }

    public function down()
    {
        $this->forge->dropTable('action_logs');
    }
}
