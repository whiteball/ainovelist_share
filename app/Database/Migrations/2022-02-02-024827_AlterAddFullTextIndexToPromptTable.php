<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterAddFullTextIndexToPromptTable extends Migration
{
    public function up()
    {
        $this->db->query('ALTER TABLE `prompts` ADD FULLTEXT INDEX ngram_idx (title, description) WITH PARSER ngram;');
    }

    public function down()
    {
        $this->forge->dropKey('prompts', 'ngram_idx');
    }
}
