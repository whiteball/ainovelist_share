<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCommentTable extends Migration
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
            'comment' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'prompt_id' => [
                'type'       => 'INT',
                'constraint' => 9,
                'unsigned'   => true,
                'null'       => false,
            ],
            'reply_comment_id' => [
                'type'       => 'INT',
                'constraint' => 9,
                'unsigned'   => true,
                'null'       => true,
            ],
            'registered_by' => [
                'type'       => 'INT',
                'constraint' => 9,
                'unsigned'   => true,
                'null'       => false,
            ],
            'registered_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('prompt_id');
        $this->forge->createTable('comments');

        $this->forge->addField([
            'id' => [
                'type'       => 'INT',
                'constraint' => 9,
                'unsigned'   => true,
            ],
            'comment' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'prompt_id' => [
                'type'       => 'INT',
                'constraint' => 9,
                'unsigned'   => true,
                'null'       => false,
            ],
            'reply_comment_id' => [
                'type'       => 'INT',
                'constraint' => 9,
                'unsigned'   => true,
                'null'       => true,
            ],
            'registered_by' => [
                'type'       => 'INT',
                'constraint' => 9,
                'unsigned'   => true,
                'null'       => false,
            ],
            'registered_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'deleted_by' => [
                'type'       => 'INT',
                'constraint' => 9,
                'unsigned'   => true,
                'null'       => false,
            ],
            'deleted_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('prompt_id');
        $this->forge->createTable('comments_deleted');

        $this->forge->addColumn('prompts', 'comment TINYINT DEFAULT 0 NOT NULL AFTER draft');
        $this->forge->addColumn('prompts_deleted', 'comment TINYINT DEFAULT 0 NOT NULL AFTER draft');
    }

    public function down()
    {
        $this->forge->dropTable('comments');
        $this->forge->dropTable('comments_deleted');

        $this->forge->dropColumn('prompts', 'comment');
        $this->forge->dropColumn('prompts_deleted', 'comment');
    }
}
