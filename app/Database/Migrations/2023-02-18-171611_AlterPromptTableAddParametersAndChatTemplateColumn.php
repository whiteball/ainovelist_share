<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterPromptTableAddParametersAndChatTemplateColumn extends Migration
{
    public function up()
    {
        $this->forge->addColumn('prompts', 'parameters TEXT DEFAULT \'\' NOT NULL AFTER character_book');
        $this->forge->addColumn('prompts_deleted', 'parameters TEXT DEFAULT \'\' NOT NULL AFTER character_book');
        $this->forge->addColumn('prompts', 'chat_template TEXT DEFAULT \'\' NOT NULL AFTER parameters');
        $this->forge->addColumn('prompts_deleted', 'chat_template TEXT DEFAULT \'\' NOT NULL AFTER parameters');
    }

    public function down()
    {
        $this->forge->dropColumn('prompts', 'chat_template');
        $this->forge->dropColumn('prompts_deleted', 'chat_template');
        $this->forge->dropColumn('prompts', 'parameters');
        $this->forge->dropColumn('prompts_deleted', 'parameters');
    }
}
