<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterCharset extends Migration
{
    public function up()
    {
        $db_name = $this->db->getDatabase();
        $this->db->query("ALTER DATABASE `{$db_name}` CHARACTER SET utf8mb4 COLLATE utf8mb4_bin");
        $this->db->query('ALTER TABLE `action_logs` convert to character set utf8mb4 collate utf8mb4_bin');
        $this->db->query('ALTER TABLE `ci_sessions` convert to character set utf8mb4 collate utf8mb4_bin');
        $this->db->query('ALTER TABLE `comments` convert to character set utf8mb4 collate utf8mb4_bin');
        $this->db->query('ALTER TABLE `comments_deleted` convert to character set utf8mb4 collate utf8mb4_bin');
        $this->db->query('ALTER TABLE `migrations` convert to character set utf8mb4 collate utf8mb4_bin');
        $this->db->query('ALTER TABLE `prompts` convert to character set utf8mb4 collate utf8mb4_bin');
        $this->db->query('ALTER TABLE `prompts_access` convert to character set utf8mb4 collate utf8mb4_bin');
        $this->db->query('ALTER TABLE `prompts_access_snapshot` convert to character set utf8mb4 collate utf8mb4_bin');
        $this->db->query('ALTER TABLE `prompts_deleted` convert to character set utf8mb4 collate utf8mb4_bin');
        $this->db->query('ALTER TABLE `ranking` convert to character set utf8mb4 collate utf8mb4_bin');
        $this->db->query('ALTER TABLE `tags` convert to character set utf8mb4 collate utf8mb4_bin');
        $this->db->query('ALTER TABLE `users` convert to character set utf8mb4 collate utf8mb4_bin');
        $this->db->query('ALTER TABLE `users_deleted` convert to character set utf8mb4 collate utf8mb4_bin');
    }

    public function down()
    {
        $db_name = $this->db->getDatabase();
        $this->db->query("ALTER DATABASE `{$db_name}` CHARACTER SET utf8 COLLATE utf8_general_ci");
        $this->db->query('ALTER TABLE `action_logs` convert to character set utf8 collate utf8_general_ci');
        $this->db->query('ALTER TABLE `ci_sessions` convert to character set utf8 collate utf8_general_ci');
        $this->db->query('ALTER TABLE `comments` convert to character set utf8 collate utf8_general_ci');
        $this->db->query('ALTER TABLE `comments_deleted` convert to character set utf8 collate utf8_general_ci');
        $this->db->query('ALTER TABLE `migrations` convert to character set utf8 collate utf8_general_ci');
        $this->db->query('ALTER TABLE `prompts` convert to character set utf8 collate utf8_general_ci');
        $this->db->query('ALTER TABLE `prompts_access` convert to character set utf8 collate utf8_general_ci');
        $this->db->query('ALTER TABLE `prompts_access_snapshot` convert to character set utf8 collate utf8_general_ci');
        $this->db->query('ALTER TABLE `prompts_deleted` convert to character set utf8 collate utf8_general_ci');
        $this->db->query('ALTER TABLE `ranking` convert to character set utf8 collate utf8_general_ci');
        $this->db->query('ALTER TABLE `tags` convert to character set utf8 collate utf8_general_ci');
        $this->db->query('ALTER TABLE `users` convert to character set utf8 collate utf8_general_ci');
        $this->db->query('ALTER TABLE `users_deleted` convert to character set utf8 collate utf8_general_ci');
    }
}
