<?php
/**
*对外接口
*/
class ApiController extends Action{
	public function __construct(){
		// parent::__construct();
		// 验证key
		if('be8df8080f1ffac31ca0dafea10b5d166dc4ef' != input('Key')){
			echo 'Invalid Request!';
			exit;
		}
	}


	public function test(){
		// $value = '529329260';
		// echo '1<br/>';
		// if(is_null($value)){print_r('NULLdddd');exit;}
		// echo '2<br/>';
		// if(is_bool($value)){print_r($value ? 1 : 0);exit;}
		// echo '3<br/>';
		// if(is_int($value)){print_r((int)$value);exit;}
		// echo '4<br/>';
		// if(is_float($value)){print_r((float)$value);exit;}
		// echo '5<br/>';
		// if(@get_magic_quotes_gpc())$value = stripslashes($value);
		// print_r('!!');
		
		// print_r('\''.mysqli_real_escape_string($this->conn, $value).'\'');// 注意mysqli_real_escape_string的参数位置exit;
		// echo '6<br/>';
		// echo '------';
		mydebug(model('qq_info')->is_auth(529329260));
	}

	/**
	* ajax请求返回正确信息
	*/
	public function asuccess($message , $data = array()){
		$result = array(
			'info' => $message,
			'status' => 1,
			'data' => $data,
		);
		echo json_encode($result);
		exit;
	}

	/**
	* ajax请求返回失败信息
	*/
	public function aerror($message , $data = array()){
		$result = array(
			'info' => $message,
			'status' => 0,
			'data' => $data,
		);
		echo json_encode($result);
		exit;
	}

	/**
	* 增加消息推送
	*/
	public function add_push(){
		$message = input('message');
		$type = input('type' , '2');
		$qq = input('qq');
		if(!$message || !$qq){
			$this->asuccess("参数错误");
		}
		$con = array(
			'message' => $message,
			'qq_type' => $type,
			'to_qq' => $qq,
			'create_time' => time(),
		);
		$res = model('qq_push')->insert($con);
		if(!$res){
			$this->aerror('更新推送数据失败');
		}	
		$this->asuccess('操作成功');
	}
}

