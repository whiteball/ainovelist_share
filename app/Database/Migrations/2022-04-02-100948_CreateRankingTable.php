<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRankingTable extends Migration
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
            'date' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'rank' => [
                'type'     => 'TINYINT',
                'unsigned' => true,
            ],
            'type' => [
                'type'     => 'TINYINT',
                'unsigned' => true,
            ],
            'prompt_id' => [
                'type'       => 'INT',
                'constraint' => 9,
                'unsigned'   => true,
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
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['date', 'rank', 'type']);
        $this->forge->createTable('ranking');
    }

    public function down()
    {
        $this->forge->dropTable('ranking');
    }
}
