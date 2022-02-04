<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Session\Session;

class Prompt_access extends Model
{
    protected $table            = 'prompts_access';
    protected $primaryKey       = 'prompt_id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $allowedFields    = ['prompt_id', 'view', 'download', 'import'];

    public function new(int $prompt_id)
    {
        return $this->insert([
            'prompt_id' => $prompt_id,
            'view'      => 0,
            'download'  => 0,
            'import'    => 0,
        ]);
    }

	const COUNT_TYPE_VIEW = 1;
	const COUNT_TYPE_DOWNLOAD = 2;
	const COUNT_TYPE_IMPORT = 3;

	public function countUp(int $prompt_id, int $type)
	{
		if (empty($prompt_id) || empty($type)) {
			return false;
		}

		/** @var Session */
		$session = service('session');
		$this->where('prompt_id', $prompt_id);
		switch ((int) $type) {
			case self::COUNT_TYPE_VIEW:
				$session_key = 'C_V_' . $prompt_id;
				$this->set('view', '`view` + 1', false);
				break;
			case self::COUNT_TYPE_DOWNLOAD:
				$session_key = 'C_D_' . $prompt_id;
				$this->set('download', '`download` + 1', false);
				break;
			case self::COUNT_TYPE_IMPORT:
				$session_key = 'C_I_' . $prompt_id;
				$this->set('import', '`import` + 1', false);
				break;
			default:
				return false;
		}

		if ($session->getTempdata($session_key) === 1) {
			return false;
		}
		

		$_SESSION[$session_key] = 1;
		$session->markAsTempdata($session_key, 86400); // 1day
		return $this->update();
	}
}
