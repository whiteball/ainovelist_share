<?php

namespace App\Models;

use CodeIgniter\Model;

class Action_log extends Model
{
    protected $table      = 'action_logs';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = 'object';
    protected $allowedFields = ['user_id', 'message', 'registered_at'];

	public function write(int $user_id, string $message)
	{
		return $this->insert([
			'user_id' => $user_id,
			'message' => $message,
		]);
	}
}