<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateYamiotomeTokenTable extends Migration
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
            'token' => [
                'type' => 'VARCHAR',
                'constraint' => '24',
                'null' => false,
            ],
            'tail' => [
                'type' => 'VARCHAR',
                'constraint' => '1',
                'null' => false,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('tail');
        $this->forge->createTable('yamiotome_tokens');
    }

    public function down()
    {
        $this->forge->dropTable('yamiotome_tokens');
    }
}
