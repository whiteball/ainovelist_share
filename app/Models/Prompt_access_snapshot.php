<?php

namespace App\Models;

use CodeIgniter\Database\BaseResult;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\Database\Query;
use CodeIgniter\Model;

class Prompt_access_snapshot extends Model
{
    protected $table            = 'prompts_access_snapshot';
    protected $primaryKey       = 'prompt_id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $allowedFields    = ['prompt_id', 'view', 'download', 'import'];

    /**
     * アクセステーブルのデータをコピーする。
     *
     * @throws DatabaseException
     *
     * @return BaseResult|bool|Query
     */
    public function copy()
    {
        /** @var Prompt_access */
        $access       = model(Prompt_access::class);
        $access_table = $this->db->escapeIdentifiers($access->getTable());

        $this->emptyTable();

        return $this->db->query('INSERT INTO ' . $this->db->escapeIdentifiers($this->table) . ' (prompt_id, view, download, import) SELECT prompt_id, view, download, import FROM ' . $access_table . ' ORDER BY prompt_id;');
    }

    /**
     * コピーしたアクセステーブルと現在のアクセステーブルを比較し、差分を返す。
     *
     * @param mixed $order ソート順
     *
     * @throws DatabaseException
     *
     * @return object[]
     */
    public function diff($order = 0)
    {
        /** @var Prompt_access */
        $access       = model(Prompt_access::class);
        $access_table = $this->db->escapeIdentifiers($access->getTable());

        $order_col = '';
        $where     = '1';

        switch ($order) {
            case Prompt_access::COUNT_TYPE_VIEW:
                $order_col = '`view` DESC,';
                $where     = '`view` != 0';
                break;

            case Prompt_access::COUNT_TYPE_DOWNLOAD:
                $order_col = '`download` DESC,';
                $where     = '`download` != 0';
                break;

            case Prompt_access::COUNT_TYPE_IMPORT:
                $order_col = '`import` DESC,';
                $where     = '`import` != 0';
                break;

            case Prompt_access::COUNT_TYPE_DOWNLOAD_IMPORT:
                $order_col = '`download_import` DESC,';
                $where     = '`download_import` != 0';
                break;
        }

        return $this->db->query('SELECT `cur`.prompt_id, (cur.view - IFNULL(snap.view, 0)) AS view, (cur.download - IFNULL(snap.download, 0)) AS download, (cur.import - IFNULL(snap.import, 0)) AS import, (cur.import - IFNULL(snap.import, 0) + cur.download - IFNULL(snap.download, 0)) AS download_import FROM ' . $access_table . ' AS cur LEFT JOIN ' . $this->db->escapeIdentifiers($this->table) . ' AS snap ON `cur`.prompt_id = `snap`.prompt_id HAVING ' . $where . ' ORDER BY ' . $order_col . ' `cur`.`prompt_id` DESC;')->getResult();
    }
}
