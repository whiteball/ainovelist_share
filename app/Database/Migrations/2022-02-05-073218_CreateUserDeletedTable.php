<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserDeletedTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 9,
                'unsigned' => true,
            ],
            'login_name' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'screen_name' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'registered_at DATETIME NOT NULL',
            'deleted_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('users_deleted');
    }

    public function down()
    {
        $this->forge->dropTable('users_deleted');
    }
}
