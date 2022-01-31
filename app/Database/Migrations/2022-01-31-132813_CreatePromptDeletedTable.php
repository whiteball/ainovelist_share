<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePromptDeletedTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'       => 'INT',
                'constraint' => 9,
                'unsigned'   => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 9,
                'unsigned'   => true,
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => '256',
            ],
            'description' => [
                'type' => 'TEXT',
            ],
            'prompt' => [
                'type' => 'MEDIUMTEXT',
            ],
            'memory' => [
                'type' => 'TEXT',
            ],
            'authors_note' => [
                'type' => 'TEXT',
            ],
            'ng_words' => [
                'type' => 'TEXT',
            ],
            'scripts' => [
                'type' => 'TEXT(300000)',
            ],
            'character_book' => [
                'type' => 'TEXT(500000)',
            ],
            'registered_at DATETIME NOT NULL',
            'updated_at DATETIME NOT NULL',
            'deleted_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('prompts_deleted');
    }

    public function down()
    {
        $this->forge->dropTable('prompts_deleted');
    }
}
