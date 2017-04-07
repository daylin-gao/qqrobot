<?php
/**
 * User: porter
 * Date: 2016/12/13
 * Time: 17:59
 * Description: qq授权model
 */

class QqInfoModel extends Model{
	public $table_name = 'qq_info';
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

	/**
	 * 获取群列表
	 * @param bool|true $echo
	 * @return bool|string
	 */
	public function getQQList(){
		$where = array(
			'status' => 1,
			'type' => 1,
		);
		$result = model('qq_info')->where($where)->select();
		$echo = '';
		foreach($result as $k=>$v){
			$echo .= "群号:".$v['qq_number']."  ".$v['qq_name']."\n";
		}
		return $echo;
	}

	/**
	 * 已经通过授权的群/qq
	 */
	public function auth_qq($type = 1){
		$where = array(
			'status' => 1,
			'type' => $type,
		);
		$result = model('qq_info')->where($where)->column('qq_number');
		return $result;
	}

	/**
	* 判断群/qq 是否已经授权
	* @param $qq 号码
	* @param $type 类型 1-群 2-QQ
	*/
	public function is_auth($qq , $type = 1){
		$where = array(
			'status' => 1,
			'type' => $type,
			'qq_number' => $qq,
		);
		$result = model('qq_info')->where($where)->find();
		return $result;
	}

	// 添加授权
	public function addQQ($type , $qq , $name){
		$qq = intval($qq);
		// 查询是否已经授权过了
		$info = model('qq_info')->where("qq_number = '$qq' and type = $type")->find();
		if($info){
			if(1 == $info['status']){
				$echo = "该QQ已授权";
				return $echo;
			}else{
				$update = array(
					'status' => 1,
				);
				$res = model('qq_info')->where("qq_number = '$qq' and type = $type")->update($update);
				if($res){
					$echo = "授权{$qq}成功";
					return $echo;
				}
			}
		}

		$con = array(
			'qq_number' => $qq,
			'type' => $type,
			'create_time' => time(),
			'qq_name' => $name,
		);
		$res = model('qq_info')->insert($con);

		if($res){
			$echo = "授权{$qq}成功";
			return $echo;
		}
//		print_r($con);exit;
		return '授权失败';
	}
}