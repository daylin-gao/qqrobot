<?php
/**
 * User: porter
 * Date: 2016/12/13
 * Time: 18:11
 * Description: 功能描述
 */
class Model{
	public $db;
	public $where = ' 1=1 ';
	public $field = '*';
	public $limit;
	public $table_name;
	public function __construct($table_name){

		$this->db = $this->getDb();
//		$this->table_name = strtolower($table_name);
	}

	// 得到数据库实例
	public function getDb(){
		$db_host = config('DB_HOST');
		$db_name = config('DB_NAME');
		$db_user = config('DB_USER');
		$db_pass = config('DB_PASS');
		$db_pdo = 'mysql:host=' . $db_host . ';dbname=' . $db_name . ';charset=utf8';

		if (empty($this->db)) {
			$db = Db::getPDO($db_pdo, $db_user, $db_pass);
			$this->db = $db;
		}
		return empty($this->db) ? FALSE : $this->db;
	}

	// 解析条件
	public function where($con){
		if(is_array($con)){
			$where = ' 1=1 ';
			foreach($con as $k => $v){
				$v = $this->escape($v);
				if(!is_numeric($v)){
					$v = "'$v'";
				}
				$where .= " and $k = $v ";
			}
		} else {
			$where = ' '.$con;
		}
		$this->where = $where;
		return $this;
	}

	// select查询方法
	public function select(){
		$sql = 'select '.$this->field . ' from '.$this->table_name . ' where '.$this->where;
		$result = $this->query($sql);
		return $result;
	}

	// find查找一条
	public function find(){
		$result = $this->select();
		return $result ? $result[0] : false;
	}

	/**
	 * 返回select查询的某个字段组成的一维数组
	 */
	public function column($field){
		$arr = array();
		$data = $this->select();
		foreach($data as $v){
			if(isset($v[$field])){
				$arr[] = $v[$field];
			}
		}
		return $arr;
	}

	// insert插入方法
	public function insert($con){
		$sql = 'insert into '. $this->table_name;
		$key = '(';
		$value = 'values(';
		foreach($con as $k=>$v){
			$key .= $k . ',';
			if(!is_numeric($v)){
				$v = "'$v'";
			}
			$value .= $v . ',';
		}
		$key = substr($key , 0 , -1) . ')';
		$value = substr($value , 0 , -1) . ')';
		$sql .= ' ' . $key . ' '.$value;
		$res = $this->db->exec($sql);
		return $res ? $this->db->lastInsertId() : false;
	}

	/**
	 * 修改方法
	 */
	public function update($con){
		$sql = "update " . $this->table_name . ' set ';
		if(empty($con)){ // 必须填写修改字段
			return false;
		}
		if($this->where == ' 1=1 '){ // 必须有修改条件
			return false;
		}
		foreach($con as $k => $v){
			if(!is_numeric($v)){
				$v = "'$v'";
			}
			$sql .= $k . '=' .$v . ',';
		}
		$sql = substr($sql , 0 , -1);
		$sql .= ' where '.$this->where;
		return $this->db->exec($sql);
	}

	public function query($sql){
		return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
	}

	// 将pdo返回的转成数组
	public function toArray(){

	}


	/**
	 * 对特殊字符进行过滤
	 *
	 * @param value  值
	 */
	public function escape($value) {
		if(is_null($value))return 'NULL';
		if(is_bool($value))return $value ? 1 : 0;
		if(is_int($value))return (int)$value;
		if(is_float($value))return (float)$value;
		if(@get_magic_quotes_gpc())$value = stripslashes($value);
		// return '\''.mysqli_real_escape_string($this->conn, $value).'\'';// 注意mysqli_real_escape_string的参数位置
		return $value;
	}
}