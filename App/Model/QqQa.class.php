<?php
/**
 * User: porter
 * Date: 2016/12/13
 * Time: 17:59
 * Description: 问答记录model
 */

class QqQaModel extends Model{
	public $table_name = 'qq_qa';
	public $db;
	public function __construct(){
		$this->db = $this->getDb();
	}

	// 得到数据库实例
	public function getDb(){
		if (empty($this->db)) {
			$db = Db::getPDO(DB_PDO, DB_USER, DB_PASS);
			$this->db = $db;
		}
		return empty($this->db) ? FALSE : $this->db;
	}


}