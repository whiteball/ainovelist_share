<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserPasswordResetTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 9,
                'unsigned'   => true,
            ],
            'digest' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL',
        ]);
        $this->forge->addKey('user_id', true);
        $this->forge->createTable('users_password_reset');
    }

    public function down()
    {
        $this->forge->dropTable('users_password_reset');
    }
}
