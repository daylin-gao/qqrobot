<?php
/**
 * User: porter
 * Date: 2016/12/13
 * Time: 17:59
 * Description: qq推送model
 */

class QqPushModel extends Model{
	public $table_name = 'qq_push';
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
	public function getPush(){
		$where = array(
			'status' => 0,
			
		);
		$result = model('qq_push')->where($where)->select();
		$echo = '';
		$msg_arr = array(); 
		$to_arr = array(); 
		foreach($result as $k=>$v){
			if(!in_array($v['message'] , $msg_arr) && count($to_arr[$v['to_qq']]) < 3){ // 防止刷屏
				if('1' == $v['qq_type']){ // qq群
					$echo .= "<&&>SendClusterMessage<&>".$v['to_qq']."<&>".$v['message'];
				} 
				elseif ('2' == $v['qq_type']) {
					$echo .= "<&&>SendMessage<&>".$v['to_qq']."<&>".$v['message'];
				}
			}
			model('qq_push')->where("id = ".$v['id'])->update(array('status' => 1)); // 更新推送状态
			$msg_arr[] = $v['message'];
			$to_arr[$v['to_qq']][] = 1;
		}
		return $echo;
	}
}